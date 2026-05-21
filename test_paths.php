<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$service = new App\Services\NimbusPostService();
$token = $service->getToken(true);

function test($method, $path, $payload = []) {
    global $token;
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.nimbuspost.com/v1' . $path,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_CUSTOMREQUEST  => strtoupper($method),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    if (strtoupper($method) !== 'GET' && !empty($payload)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    }
    $rawResponse = curl_exec($ch);
    if (!str_contains($rawResponse, 'Invalid key=value pair') && !str_contains($rawResponse, 'Missing Authentication Token')) {
        echo "SUCCESS: $method $path -> $rawResponse\n";
    }
}

// Couriers
test('GET', '/courier');

// Warehouses
test('POST', '/warehouses');
test('GET', '/users/warehouse');
test('GET', '/warehouse/list');

// Label
test('POST', '/shipments/label', ['awb' => '123']);
test('POST', '/shipment/label', ['awb' => '123']);

// Cancel
test('POST', '/shipments/cancel', ['awb' => '123']);
test('POST', '/shipment/cancel', ['awb' => '123']);

// NDR
test('GET', '/ndr');
test('POST', '/ndr');

// Track
test('GET', '/shipment/track');
test('GET', '/shipment/track/123');

// Get all shipments
test('GET', '/shipment');
test('GET', '/shipments');
test('POST', '/shipments');
