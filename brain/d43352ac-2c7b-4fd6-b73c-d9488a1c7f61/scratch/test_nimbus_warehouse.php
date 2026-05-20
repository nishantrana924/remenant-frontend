<?php

require __DIR__ . '/../../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.nimbuspost.api_key');

$data = [
    'name' => 'Test API Warehouse',
    'contact_name' => 'John Doe',
    'phone' => '9999999999',
    'address_1' => 'Test Address 1',
    'city' => 'Delhi',
    'state' => 'Delhi',
    'zip' => '110001'
];

echo "Testing with standard Laravel post()...\n";
$response1 = Http::withHeaders([
    'NP-API-KEY' => $apiKey,
    'Accept' => 'application/json'
])->post('https://ship.nimbuspost.com/api/warehouse/create', $data);

echo "Response 1:\n";
print_r($response1->json());

echo "\nTesting with raw Guzzle body...\n";
$response2 = Http::withHeaders([
    'NP-API-KEY' => $apiKey,
    'Accept' => 'application/json',
    'Content-Type' => 'application/json'
])->send('POST', 'https://ship.nimbuspost.com/api/warehouse/create', [
    'body' => json_encode($data)
]);

echo "Response 2:\n";
print_r($response2->json());

echo "\nTesting ONLY with NP-API-KEY (No Bearer)...\n";
$response3 = Http::withBody(json_encode($data), 'application/json')
    ->withHeaders([
        'NP-API-KEY' => $apiKey,
        'Accept' => 'application/json'
    ])->post('https://ship.nimbuspost.com/api/warehouse/create');

echo "Response 3:\n";
print_r($response3->json());
