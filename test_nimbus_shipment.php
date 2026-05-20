<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$apiKey   = config('services.nimbuspost.api_key');
$email    = config('services.nimbuspost.email');
$password = config('services.nimbuspost.password');

// Login
$loginCh = curl_init('https://ship.nimbuspost.com/api/users/login');
curl_setopt($loginCh, CURLOPT_RETURNTRANSFER, true);
curl_setopt($loginCh, CURLOPT_POSTFIELDS, json_encode(['email' => $email, 'password' => $password]));
curl_setopt($loginCh, CURLOPT_HTTPHEADER, ['NP-API-KEY: ' . $apiKey, 'Content-Type: application/json']);
$loginRes = json_decode(curl_exec($loginCh), true);
curl_close($loginCh);
$token = $loginRes['data'] ?? null;

$data = [
    'order' => [
        'order_number' => 'TEST-1234',
        'payment_type' => 'prepaid',
        'total' => 499,
        'courier_id' => '179',
    ],
    'consignee' => [
        'name' => 'Test User',
        'address' => 'Test Address',
        'city' => 'Delhi',
        'state' => 'Delhi',
        'pincode' => '110001',
        'phone' => '9876543210'
    ],
    'pickup_warehouse_id' => '260736',
    'package_weight' => 500,
    'package_length' => 10,
    'package_breadth' => 10,
    'package_height' => 10,
    'order_items' => [
        ['name' => 'Product 1', 'qty' => 1, 'price' => 499, 'sku' => 'SKU-1']
    ]
];

$ch = curl_init('https://ship.nimbuspost.com/api/shipments/create');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'NP-API-KEY: ' . $apiKey,
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json'
]);
$result = json_decode(curl_exec($ch), true);
curl_close($ch);

print_r($result);
