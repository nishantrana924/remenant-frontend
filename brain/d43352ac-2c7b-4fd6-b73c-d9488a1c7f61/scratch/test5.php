<?php
require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
use App\Services\NimbusPostService;
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$nimbus = app(NimbusPostService::class);

$payload = [
    'order' => [
        'order_number' => '12345',
        'payment_type' => 'prepaid',
        'amount' => 100,
    ],
    'consignee' => [
        'name' => 'John', 
        'address' => '123 Street',
        'city' => 'New Delhi',
        'state' => 'Delhi',
        'pincode' => '110001',
        'phone' => '9999999999'
    ],
    'order_items' => [['name' => 'Test', 'qty' => 1, 'price' => 100, 'sku' => 'abc']],
    'pickup_warehouse_id' => '123',
    'package_weight' => 500,
    'package_length' => 10,
    'package_breadth' => 10,
    'package_height' => 10,
];

echo "Testing 'amount' and 'address'...\n";
print_r($nimbus->createShipment($payload));

$payload['order']['order_total'] = 100;
unset($payload['order']['amount']);
$payload['consignee']['address_1'] = '123 Street';
unset($payload['consignee']['address']);

echo "Testing 'order_total' and 'address_1'...\n";
print_r($nimbus->createShipment($payload));

$payload['order']['total_amount'] = 100;
unset($payload['order']['order_total']);
$payload['consignee']['address1'] = '123 Street';
unset($payload['consignee']['address_1']);

echo "Testing 'total_amount' and 'address1'...\n";
print_r($nimbus->createShipment($payload));
