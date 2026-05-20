<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class ShippingService
{
    protected $providers = [
        'nimbuspost' => NimbusPostService::class,
    ];

    /**
     * Dispatch order to a specific provider
     */
    public function dispatch(Order $order, $providerName = 'nimbuspost')
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

        if (isset($response['status']) && $response['status'] === true) {
            $data = $response['data'];
            $order->update([
                'tracking_id' => $data['awb_number'] ?? $data['shipment_id'],
                'courier_name' => $data['courier_name'] ?? ucfirst($providerName),
                'delivery_status' => 'shipped',
                'status' => 'shipped',
                'tracking_url' => $data['tracking_url'] ?? null
            ]);

            $order->logStatus("Order dispatched via " . ucfirst($providerName) . ". AWB: " . ($data['awb_number'] ?? $data['shipment_id']));
            
            return [
                'success' => true,
                'tracking_id' => $data['awb_number'] ?? $data['shipment_id'],
                'message' => "Shipment created successfully via " . ucfirst($providerName)
            ];
        }

        return [
            'success' => false,
            'message' => $response['message'] ?? "Failed to create shipment with " . ucfirst($providerName)
        ];
    }
}
