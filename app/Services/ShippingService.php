<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $providers = [
        'shiprocket' => ShiprocketService::class,
        // 'delhivery' => DelhiveryService::class,
        // 'bluedart' => BlueDartService::class,
    ];

    /**
     * Dispatch order to a specific provider
     */
    public function dispatch(Order $order, $providerName = 'shiprocket')
    {
        if (!isset($this->providers[$providerName])) {
            throw new \Exception("Shipping provider {$providerName} not found.");
        }

        $provider = app($this->providers[$providerName]);
        
        // Ensure the order is in the correct state
        if ($order->status !== 'packed' && $order->status !== 'processing') {
            throw new \Exception("Order must be in 'packed' or 'processing' status to dispatch.");
        }

        $response = $provider->createOrder($order);

        if (isset($response['shipment_id'])) {
            $order->update([
                'tracking_id' => $response['shipment_id'],
                'courier_name' => $response['courier_name'] ?? ucfirst($providerName),
                'delivery_status' => 'shipped',
                'status' => 'shipped',
                'tracking_url' => $response['tracking_url'] ?? null
            ]);

            $order->logStatus("Order dispatched via " . ucfirst($providerName) . ". Tracking ID: " . $response['shipment_id']);
            
            return [
                'success' => true,
                'tracking_id' => $response['shipment_id'],
                'message' => "Shipment created successfully via " . ucfirst($providerName)
            ];
        }

        return [
            'success' => false,
            'message' => $response['message'] ?? "Failed to create shipment with " . ucfirst($providerName)
        ];
    }
}
