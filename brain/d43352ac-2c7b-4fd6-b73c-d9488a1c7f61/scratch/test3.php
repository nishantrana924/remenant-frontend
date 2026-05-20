<?php
require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
use App\Services\NimbusPostService;
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$nimbus = app(NimbusPostService::class);

// Function to test and get missing keys
function testKeys($nimbus, $payload, $label) {
    echo "\n--- Testing: $label ---\n";
    $response = $nimbus->createShipment($payload);
    if(isset($response['message'])) {
        echo $response['message'] . "\n";
    } else {
        print_r($response);
    }
}

// 1. Minimum Consignee test
testKeys($nimbus, [
    'consignee' => [
        'name' => 'John Doe',
        'address' => '123 Street', // Will fail address
    ]
], "Base Consignee with 'address'");

testKeys($nimbus, [
    'consignee' => [
        'name' => 'John Doe',
        'address_1' => '123 Street', // Will it pass address?
    ]
], "Base Consignee with 'address_1'");

testKeys($nimbus, [
    'consignee' => [
        'name' => 'John Doe',
        'address1' => '123 Street', // Will it pass address?
    ]
], "Base Consignee with 'address1'");

// 2. Order Number test
testKeys($nimbus, [
    'consignee' => ['name' => 'John', 'address_1' => 'Street'],
    'order_number' => '12345',
], "Order Number as string '12345'");

testKeys($nimbus, [
    'consignee' => ['name' => 'John', 'address_1' => 'Street'],
    'order_number' => 12345,
], "Order Number as integer 12345");

testKeys($nimbus, [
    'consignee' => ['name' => 'John', 'address_1' => 'Street'],
    'order_id' => '12345',
], "Order ID as '12345'");

testKeys($nimbus, [
    'consignee' => ['name' => 'John', 'address_1' => 'Street'],
    'orderNumber' => '12345',
], "CamelCase orderNumber");

testKeys($nimbus, [
    'consignee' => ['name' => 'John', 'address_1' => 'Street'],
    'payment_type' => 'prepaid',
    'payment_method' => 'prepaid',
    'payment_mode' => 'prepaid',
], "Payment Types");

testKeys($nimbus, [
    'consignee' => ['name' => 'John', 'address_1' => 'Street'],
    'order_amount' => 100,
    'amount' => 100,
    'total' => 100,
    'order_total' => 100,
], "Amounts");

