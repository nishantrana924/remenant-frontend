<?php
require __DIR__ . '/../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../bootstrap/app.php';
use App\Services\NimbusPostService;
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$nimbus = app(NimbusPostService::class);

$keys_to_test = [
    'total',
    'invoice_value',
    'invoice_amount',
    'total_value',
    'order_value',
    'value',
    'grand_total',
    'order_amount',
    'order_total',
    'amount'
];

foreach ($keys_to_test as $key) {
    echo "\nTesting key: $key\n";
    $payload = [
        'order' => [
            'order_number' => '12345',
            'payment_type' => 'prepaid',
            $key => 100,
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
    ];
    $response = $nimbus->createShipment($payload);
    if(isset($response['message'])) {
        echo $response['message'] . "\n";
    } else {
        print_r($response);
    }
}
