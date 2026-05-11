<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShiprocketService
{
    protected $baseUrl = 'https://apiv2.shiprocket.in/v1/external';

    /**
     * Get authentication token from Shiprocket
     */
    public function getToken()
    {
        return Cache::remember('shiprocket_token', 86400, function () {
            $email = config('services.shiprocket.email');
            $password = config('services.shiprocket.password');

            if (!$email || !$password || str_contains($email, 'your_email')) {
                Log::warning('Shiprocket Credentials Missing. Entering MOCK MODE.');
                return 'MOCK_TOKEN_' . Str::random(32);
            }

            $response = Http::post($this->baseUrl . '/auth/login', [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                return $response['token'];
            }

            Log::error('Shiprocket Login Failed', ['response' => $response->json()]);
            return null;
        });
    }

    /**
     * Create a custom order in Shiprocket
     */
    public function createOrder($order)
    {
        $token = $this->getToken();
        if (!$token) return null;

        // MOCK MODE RESPONSE
        if (str_contains($token, 'MOCK_TOKEN')) {
            return [
                'shipment_id' => 'MOCK-' . rand(100000, 999999),
                'courier_name' => 'Shiprocket Simulation',
                'status' => 'mock_success'
            ];
        }

        $items = $order->orderItems->map(function ($item) {
            return [
                'name' => $item->product->name,
                'sku' => $item->product->sku ?? 'SKU-' . $item->product_id,
                'units' => $item->quantity,
                'selling_price' => (float)$item->price,
                'discount' => 0,
                'tax' => 0,
                'hsn' => 0,
            ];
        })->toArray();

        $payload = [
            'order_id' => $order->order_number ?? $order->id,
            'order_date' => $order->created_at->format('Y-m-d H:i'),
            'pickup_location' => 'Primary', // Must match 'Pickup Location Nickname' in Shiprocket Settings
            'billing_customer_name' => $order->customer_name ?? $order->user->name,
            'billing_last_name' => '',
            'billing_address' => $order->address,
            'billing_city' => $order->city,
            'billing_pincode' => $order->pincode,
            'billing_state' => $order->state,
            'billing_country' => 'India',
            'billing_email' => $order->email ?? $order->user->email,
            'billing_phone' => $order->phone ?? $order->user->phone,
            'shipping_is_billing' => true,
            'order_items' => $items,
            'payment_method' => $order->payment_method === 'cod' ? 'COD' : 'Prepaid',
            'sub_total' => (float)$order->total_amount,
            'length' => 10, // CMS default
            'breadth' => 10,
            'height' => 10,
            'weight' => 0.5,
        ];

        $response = Http::withToken($token)->post($this->baseUrl . '/orders/create/adhoc', $payload);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Shiprocket Order Creation Failed', [
            'response' => $response->json(),
            'payload' => $payload
        ]);
        
        return $response->json();
    }

    /**
     * Generate Shipping Label
     */
    public function generateLabel($shipmentId)
    {
        $token = $this->getToken();
        if (!$token) return null;

        $response = Http::withToken($token)->post($this->baseUrl . '/courier/generate/label', [
            'shipment_id' => [$shipmentId],
        ]);

        return $response->successful() ? $response->json() : null;
    }

    /**
     * Get Tracking Details
     */
    public function getTracking($shipmentId)
    {
        $token = $this->getToken();
        if (!$token) return null;

        $response = Http::withToken($token)->get($this->baseUrl . '/courier/track/shipment/' . $shipmentId);

        return $response->successful() ? $response->json() : null;
    }
}
