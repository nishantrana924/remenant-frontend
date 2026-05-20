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

// Fetch rates
$data = [
    'origin'        => '394101',
    'destination'   => '110001',
    'payment_type'  => 'prepaid',
    'order_amount'  => 499,
    'weight'        => 500,
    'length'        => 10,
    'breadth'       => 10,
    'height'        => 10,
];

$ch = curl_init('https://ship.nimbuspost.com/api/courier/serviceability');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'NP-API-KEY: ' . $apiKey,
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json'
]);
$result = json_decode(curl_exec($ch), true);
curl_close($ch);

// Show first 2 couriers full structure
echo "Total couriers: " . count($result['data'] ?? []) . "\n\n";
echo "First courier full structure:\n";
print_r($result['data'][0] ?? []);
echo "\nSecond courier:\n";
print_r($result['data'][1] ?? []);
