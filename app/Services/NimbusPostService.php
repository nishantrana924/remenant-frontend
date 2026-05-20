<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ShippingLog;

class NimbusPostService
{
    protected $baseUrl = 'https://ship.nimbuspost.com/api';

    /**
     * Get authentication token from NimbusPost
     */
    public function getToken($forceRefresh = false)
    {
        if ($forceRefresh) {
            Cache::forget('nimbuspost_token');
        }

        $token = Cache::get('nimbuspost_token');
        if ($token && !$forceRefresh) {
            return $token;
        }

        $email = config('services.nimbuspost.email');
        $password = config('services.nimbuspost.password');

        // Always try login first to get the latest JWT token
        if ($email && $password && !str_contains($email, 'your_email')) {
            $response = Http::post($this->baseUrl . '/users/login', [
                'email'    => $email,
                'password' => $password,
            ]);

            if ($response->successful() && isset($response['data'])) {
                $token = $response['data'];
                Cache::put('nimbuspost_token', $token, 7200); // 2 hours (JWT expires in ~3h)
                return $token;
            }
        }

        // Fallback to static API Key if login fails
        return config('services.nimbuspost.api_key');
    }

    private function getMockToken()
    {
        $mockToken = 'MOCK_TOKEN_' . Str::random(32);
        Cache::put('nimbuspost_token', $mockToken, 43200);
        return $mockToken;
    }

    /**
     * Helper to make authorized requests
     */
    protected function request($method, $endpoint, $data = [])
    {
        try {
            $token  = $this->getToken();
            $apiKey = config('services.nimbuspost.api_key');

            $headers = [
                'Accept'       => 'application/json',
                'Content-Type' => 'application/json',
            ];

            // NimbusPost requires BOTH headers for most endpoints
            if ($apiKey) $headers['NP-API-KEY']     = $apiKey;
            if ($token)  $headers['Authorization']   = 'Bearer ' . $token;

            $url = $this->baseUrl . $endpoint;
            Log::info('NimbusPost Request:', ['method' => $method, 'url' => $url, 'data' => $data]);

            // Prevent Laravel from adding charset=utf-8 which breaks Nimbus API
            $request = Http::timeout(30)->withHeaders($headers);

            if (strtolower($method) === 'get') {
                $response = $request->get($url, $data);
            } else {
                $response = $request->send($method, $url, [
                    'body' => json_encode($data)
                ]);
            }

            $resData = $response->json();

            if (!$response->successful()) {
                Log::error('NimbusPost API Error:', [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'response' => $resData
                ]);
            }

            // Always log for our audit logs
            $this->logActivity($endpoint, $data, $resData, $response->successful() && ($resData['status'] ?? true), $headers);

            return $resData;
        } catch (\Exception $e) {
            Log::critical('NimbusPost Service Exception:', ['message' => $e->getMessage()]);
            $this->logActivity($endpoint, $data, ['error' => $e->getMessage()], false, $headers ?? []);
            return [
                'status' => false,
                'message' => 'Connection to logistics server failed. Please try again later.'
            ];
        }
    }

    public function getAllShipments($params = [])
    {
        $query = http_build_query($params);
        return $this->request('get', '/shipments' . ($query ? '?' . $query : ''));
    }

    /**
     * Create a shipment
     */
    public function createShipment($payload)
    {
        return $this->request('post', '/shipments/create', $payload);
    }

    /**
     * Create a Hyperlocal shipment
     */
    public function createHyperlocalShipment($payload)
    {
        return $this->request('post', '/shipments/hyperlocal', $payload);
    }

    public function shipOrder($nimbusOrderId, $courierId, $warehouseId)
    {
        return $this->request('post', '/orders/ship', [
            'id' => (int)$nimbusOrderId,
            'courier_id' => $courierId,
            'warehouse_id' => (int)$warehouseId
        ]);
    }

    /**
     * Generate Shipping Label
     */
    public function generateLabel($shipmentIds)
    {
        $ids = is_array($shipmentIds) ? implode(',', $shipmentIds) : $shipmentIds;
        return $this->request('post', '/shipments/label', [
            'ids' => $ids
        ]);
    }

    /**
     * Cancel a shipment
     */
    public function cancelShipment($shipmentId)
    {
        return $this->request('post', '/shipments/cancel', ['id' => (int)$shipmentId]);
    }

    /**
     * Get Specific Shipment Details
     */
    public function getShipmentDetails($shipmentId)
    {
        return $this->request('get', '/shipments/' . $shipmentId);
    }

    /**
     * Raise a pickup request
     */
    public function requestPickup($shipmentIds)
    {
        return $this->request('post', '/shipments/pickups', [
            'ids' => (array)$shipmentIds
        ]);
    }

    /**
     * Get Tracking Details by AWB
     */
    public function getTracking($awbNumber)
    {
        return $this->request('get', '/shipments/track_awb/' . $awbNumber);
    }

    /**
     * Get Tracking Details by Shipment ID
     */
    public function getTrackingById($shipmentId)
    {
        return $this->request('get', '/shipments/track/' . $shipmentId);
    }

    /**
     * Bulk Tracking (Up to 100 AWBs)
     */
    public function trackBulk($awbs)
    {
        return $this->request('post', '/shipments/track/bulk', ['awb' => (array)$awbs]);
    }

    /**
     * Generate Manifest
     */
    public function generateManifest($awbs)
    {
        return $this->request('post', '/shipments/manifest', ['awbs' => (array)$awbs]);
    }

    public function getCouriers()
    {
        return $this->request('get', '/couriers');
    }

    /**
     * Get Serviceable Pincodes List
     */
    public function getServiceablePincodes()
    {
        return $this->request('get', '/courier/serviceability');
    }

    /**
     * Get Serviceable Couriers and Rates
     */
    public function getRates($data)
    {
        return $this->request('post', '/courier/serviceability', $data);
    }

    /**
     * Warehouse Management
     */
    public function getWarehouses()
    {
        return $this->request('get', '/warehouse');
    }

    public function createWarehouse($data)
    {
        return $this->request('post', '/warehouse/create', $data);
    }

    public function updateWarehouse($nimbusId, $data)
    {
        $data['id'] = $nimbusId;
        return $this->request('post', '/warehouse/update', $data);
    }

    /**
     * NDR Management
     */
    public function getNDR($params = [])
    {
        // Support common params: awb_number, per_page, page_no
        $query = http_build_query($params);
        return $this->request('get', '/ndr' . ($query ? '?' . $query : ''));
    }

    public function ndrAction($actions)
    {
        // Documentation shows it expects an ARRAY of objects (can take up to 100)
        // If single action passed, wrap in array
        if (isset($actions['awb'])) {
            $actions = [$actions];
        }
        return $this->request('post', '/ndr/action', $actions);
    }

    /**
     * Log API activity for debugging and audit
     */
    protected function logActivity($action, $request, $response, $success, $headers = [])
    {
        ShippingLog::create([
            'action' => $action,
            'request_payload' => [
                'payload' => $request,
                'headers' => $headers
            ],
            'response_data' => $response,
            'status' => $success ? 'success' : 'failure',
            'error_message' => !$success ? ($response['message'] ?? 'Unknown Error') : null,
        ]);
    }

    /**
     * Handle Mock requests for testing without credentials
     */
    protected function handleMockRequest($endpoint, $data)
    {
        if (str_contains($endpoint, 'shipments')) {
            return [
                'status' => true,
                'data' => [
                    'shipment_id' => 'NIMB-' . rand(100000, 999999),
                    'awb_number' => 'AWB' . rand(100000000, 999999999),
                    'courier_name' => 'Nimbus Simulation',
                    'label' => 'https://nimbuspost.com/mock-label.pdf'
                ]
            ];
        }
        
        if (str_contains($endpoint, 'courier/serviceability')) {
            return [
                'status' => true,
                'data' => [
                    [
                        'courier_id' => 1,
                        'courier_name' => 'BlueDart Express',
                        'rate' => 45.00,
                        'expected_delivery' => '2-3 Days',
                        'min_weight' => 0.5
                    ],
                    [
                        'courier_id' => 2,
                        'courier_name' => 'Delhivery Surface',
                        'rate' => 32.50,
                        'expected_delivery' => '4-6 Days',
                        'min_weight' => 1.0
                    ]
                ]
            ];
        }

        return ['status' => true, 'message' => 'Mock Success', 'data' => []];
    }
}
