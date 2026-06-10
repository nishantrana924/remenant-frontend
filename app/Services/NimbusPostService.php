<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ShippingLog;

class NimbusPostService
{
    protected $baseUrl = 'https://api.nimbuspost.com/v1';

    /**
     * Get authentication token from NimbusPost
     */
    public function getToken($forceRefresh = false)
    {
        if ($forceRefresh) {
            Cache::forget('nimbuspost_token');
        }

        $token = Cache::get('nimbuspost_token');

        // Only trust the cached token if it looks like a valid JWT (starts with 'eyJ')
        // A corrupt/stale/non-JWT value (e.g. a hash or garbage) causes the
        // "Invalid key=value pair in Authorization header" error from NimbusPost.
        $isValidJwt = $token && str_starts_with($token, 'eyJ');

        // Use cached token if available and valid
        if ($isValidJwt && !$forceRefresh) {
            return $token;
        }

        // Clear stale/invalid token and fetch fresh
        Cache::forget('nimbuspost_token');

        $email    = config('services.nimbuspost.email');
        $password = config('services.nimbuspost.password');

        if (empty($email) || empty($password)) {
            Log::error('NimbusPost: Email/Password not configured in .env');
            return null;
        }

        Log::info('NimbusPost: Attempting login with email: ' . $email);

        try {
            $response = Http::timeout(15)
                ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
                ->post('https://api.nimbuspost.com/v1/users/login', [
                    'email'    => $email,
                    'password' => $password,
                ]);

            Log::info('NimbusPost Login Response:', [
                'status' => $response->status(),
                'body'   => $response->json()
            ]);

            if ($response->successful() && !empty($response['data'])) {
                $token = $response['data'];
                Cache::put('nimbuspost_token', $token, 7200);
                Log::info('NimbusPost: Login successful. Token cached.');
                return $token;
            }

            Log::error('NimbusPost: Login failed.', ['response' => $response->json()]);
            return null;

        } catch (\Exception $e) {
            Log::error('NimbusPost: Login exception: ' . $e->getMessage());
            return null;
        }
    }

    private function getMockToken()
    {
        $mockToken = 'MOCK_TOKEN_' . Str::random(32);
        Cache::put('nimbuspost_token', $mockToken, 43200);
        return $mockToken;
    }

    /**
     * Helper to make authorized requests using raw cURL
     * (Bypasses Laravel HTTP client / Guzzle Bearer token parsing issues)
     */
    protected function request($method, $endpoint, $data = [])
    {

        try {
            $url     = $this->baseUrl . $endpoint;
            $method  = strtoupper($method);

            Log::info('NimbusPost cURL Request:', ['method' => $method, 'url' => $url]);

            // Use standard headers for all requests
            $token = trim($this->getToken());
            if (!$token) {
                Log::error('NimbusPost: No valid auth token available.');
                return [
                    'status'  => false,
                    'message' => 'NimbusPost authentication failed. Check NIMBUSPOST_EMAIL and NIMBUSPOST_PASSWORD in .env.'
                ];
            }
            
            $headers = [
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
                'Accept: application/json',
            ];

            $ch = curl_init();

            curl_setopt_array($ch, [
                CURLOPT_URL            => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTPHEADER     => $headers,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            ]);


            if ($method === 'GET' && !empty($data)) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
            } elseif ($method !== 'GET') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                // Always send JSON body
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }

            $rawResponse = curl_exec($ch);
            $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError   = curl_error($ch);
            curl_close($ch);

            if ($curlError) {
                Log::error('NimbusPost cURL Error:', ['error' => $curlError]);
                return ['status' => false, 'message' => 'cURL Error: ' . $curlError];
            }

            $resData = json_decode($rawResponse, true);
            $success = $httpCode >= 200 && $httpCode < 300 && ($resData['status'] ?? true);

            // Retry once on 401
            if ($httpCode === 401) {
                Log::warning('NimbusPost: 401, refreshing token...');
                $token = $this->getToken(true);
                if ($token) {
                    return $this->request(strtolower($method), $endpoint, $data);
                }
            }

            if (!$success) {
                Log::error('NimbusPost API Error:', ['status' => $httpCode, 'response' => $resData]);
            }

            // Log headers used
            $this->logActivity($endpoint, $data, $resData, $success, ['Authorization' => 'Bearer [token]']);

            return $resData;

        } catch (\Exception $e) {
            Log::critical('NimbusPost Exception:', ['message' => $e->getMessage()]);
            $this->logActivity($endpoint, $data, ['error' => $e->getMessage()], false, []);
            return [
                'status'  => false,
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
        // Ensure request_auto_pickup is 'yes' or 'no' string
        if (isset($payload['request_auto_pickup'])) {
            $payload['request_auto_pickup'] = $payload['request_auto_pickup'] ? 'yes' : 'no';
        }
        return $this->request('post', '/shipments', $payload);
    }

    /**
     * Create a Reverse (Return) Pickup Shipment
     * Customer's address = pickup point, Warehouse = delivery point
     * NimbusPost tracking: PP → IT → RT-IT → RT-DL
     */
    public function createReverseShipment($payload)
    {
        // Force reverse order type — this is the key difference
        $payload['order_type'] = 'reverse';
        $payload['payment_type'] = 'prepaid'; // seller always pays for return pickup

        if (isset($payload['request_auto_pickup'])) {
            $payload['request_auto_pickup'] = $payload['request_auto_pickup'] ? 'yes' : 'no';
        }

        Log::info('NimbusPost: Creating REVERSE shipment', ['order_number' => $payload['order_number'] ?? 'N/A']);
        return $this->request('post', '/shipments', $payload);
    }

    /**
     * Create a Hyperlocal shipment
     */
    public function createHyperlocalShipment($payload)
    {
        // Ensure request_auto_pickup is 'yes' or 'no' string
        if (isset($payload['request_auto_pickup'])) {
            $payload['request_auto_pickup'] = $payload['request_auto_pickup'] ? 'yes' : 'no';
        }
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
    public function cancelShipment($shipmentId, $awb)
    {
        return $this->request('post', '/shipments/cancel', [
            'id' => (int)$shipmentId,
            'awb' => $awb,
        ]);
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
        // Official NimbusPost endpoint: GET /v1/shipments/track/{awb}
        return $this->request('get', '/shipments/track/' . $awbNumber);
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
        return $this->request('get', '/courier');
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
