<?php
require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';

use App\Services\NimbusPostService;
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$nimbus = app(NimbusPostService::class);

$payload = [
    'order_number' => 'TEST-1234',
    'payment_type' => 'prepaid',
    'order_amount' => 100,
    'package_weight' => 500,
    'package_length' => 10,
    'package_breadth' => 10,
    'package_height' => 10,
    'pickup_warehouse_id' => '260736',
    'consignee_name' => 'John Doe',
    'consignee_address' => '123 Street',
    'consignee_city' => 'New Delhi',
    'consignee_state' => 'Delhi',
    'consignee_pincode' => '110001',
    'consignee_phone' => '9999999999',
    'order_items' => [
        [
            'name' => 'Test Product',
            'qty' => 1,
            'price' => 100,
            'sku' => 'TEST-SKU'
        ]
    ]
];

echo "Testing flat payload...\n";
$response = $nimbus->createShipment($payload);
print_r($response);

$payload_nested_test = [
    'order_number' => 'TEST-1234', // Try order_number
    'order_id' => 'TEST-1234',     // Try order_id
    'reference_number' => 'TEST-1234',
    
    'payment_type' => 'prepaid',
    'payment_mode' => 'prepaid',
    
    'order_amount' => 100,
    'order_total' => 100,
    'total_amount' => 100,
    'amount' => 100,
    
    'package_weight' => 500,
    'weight' => 500,
    
    'pickup_warehouse_id' => '260736',
    'warehouse_id' => '260736',
    
    'consignee' => [
        'name' => 'John Doe',
        'address' => '123 Street',
        'address_1' => '123 Street',
        'address1' => '123 Street',
        'city' => 'New Delhi',
        'state' => 'Delhi',
        'pincode' => '110001',
        'zip' => '110001',
        'zipcode' => '110001',
        'phone' => '9999999999',
    ],
    'order_items' => [
        [
            'name' => 'Test Product',
            'qty' => 1,
            'price' => 100,
            'sku' => 'TEST-SKU'
        ]
    ]
];

echo "\nTesting kitchen sink nested payload...\n";
$response = $nimbus->createShipment($payload_nested_test);
print_r($response);

// Test raw Http call with asForm
echo "\nTesting asForm...\n";
$apiKey = config('services.nimbuspost.api_key');
$token = Cache::get('nimbuspost_token');
$res = \Illuminate\Support\Facades\Http::asForm()->withHeaders([
    'NP-API-KEY' => $apiKey,
    'Authorization' => 'Bearer ' . $token,
])->post('https://ship.nimbuspost.com/api/shipments/create', $payload_wrapped);
print_r($res->json());

// Test raw Http call with JSON without Content-Type override
echo "\nTesting strict asJson()...\n";
$res2 = \Illuminate\Support\Facades\Http::asJson()->withHeaders([
    'NP-API-KEY' => $apiKey,
    'Authorization' => 'Bearer ' . $token,
])->post('https://ship.nimbuspost.com/api/shipments', $payload_wrapped); // note /shipments
print_r($res2->json());
