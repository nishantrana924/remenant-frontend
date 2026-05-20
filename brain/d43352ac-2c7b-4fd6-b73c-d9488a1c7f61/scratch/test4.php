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
        'order_amount' => 100,
    ],
    'consignee' => ['name' => 'John', 'address_1' => '123 Street'],
    'order_items' => [['name' => 'Test', 'qty' => 1, 'price' => 100, 'sku' => 'abc']],
    'pickup_warehouse_id' => '123',
];

echo "Testing 'order' wrapper...\n";
print_r($nimbus->createShipment($payload));

$payload2 = [
    'order_info' => [
        'order_number' => '12345',
        'payment_type' => 'prepaid',
        'order_amount' => 100,
    ],
    'consignee' => ['name' => 'John', 'address_1' => '123 Street'],
    'order_items' => [['name' => 'Test', 'qty' => 1, 'price' => 100, 'sku' => 'abc']],
    'pickup_warehouse_id' => '123',
];

echo "Testing 'order_info' wrapper...\n";
print_r($nimbus->createShipment($payload2));

$payload3 = [
    'shipment_details' => [
        'order_number' => '12345',
        'payment_type' => 'prepaid',
        'order_amount' => 100,
    ],
    'consignee' => ['name' => 'John', 'address_1' => '123 Street'],
    'order_items' => [['name' => 'Test', 'qty' => 1, 'price' => 100, 'sku' => 'abc']],
    'pickup_warehouse_id' => '123',
];

echo "Testing 'shipment_details' wrapper...\n";
print_r($nimbus->createShipment($payload3));

