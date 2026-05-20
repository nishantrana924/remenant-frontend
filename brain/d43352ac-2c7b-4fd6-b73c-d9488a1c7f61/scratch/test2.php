<?php
require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
use App\Services\NimbusPostService;
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$nimbus = app(NimbusPostService::class);

$payload = [
    'order_number' => 'TEST-5555',
    'payment_type' => 'prepaid',
    'order_amount' => 100,
    'consignee' => [
        'name' => 'John Doe',
        'address' => '123 Street',
        'city' => 'New Delhi',
        'state' => 'Delhi',
        'pincode' => '110001',
        'phone' => '9999999999',
    ]
];
echo "\nTesting payload wrapped in array (Bulk/List format)...\n";
print_r($nimbus->createShipment([$payload]));

// What if the fields are capitalized?
$payload_cap = [
    'Order_Number' => 'TEST-5555',
    'Payment_Type' => 'prepaid',
    'Order_Total' => 100,
    'consignee' => [
        'name' => 'John Doe',
        'address' => '123 Street',
        'city' => 'New Delhi',
        'state' => 'Delhi',
        'pincode' => '110001',
        'phone' => '9999999999',
    ]
];
print_r($nimbus->createShipment($payload_cap));

// What if it's order_id, payment_mode, amount?
$payload_alt = [
    'order_id' => 'TEST-5555',
    'payment_mode' => 'prepaid',
    'amount' => 100,
    'consignee' => [
        'name' => 'John Doe',
        'address' => '123 Street',
        'city' => 'New Delhi',
        'state' => 'Delhi',
        'pincode' => '110001',
        'phone' => '9999999999',
    ]
];
print_r($nimbus->createShipment($payload_alt));
