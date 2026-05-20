<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nimbus = app(\App\Services\NimbusPostService::class);
$res = $nimbus->getRates([
    'origin'        => '394101',
    'destination'   => '400001',
    'payment_type'  => 'prepaid',
    'order_amount'  => 500,
    'weight'        => 500,
    'length'        => 10,
    'breadth'       => 10,
    'height'        => 10,
]);
print_r($res['data'][0] ?? $res);
