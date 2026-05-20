<?php

require __DIR__ . '/../../../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$apiKey = config('services.nimbuspost.api_key');

$data = [
    'name' => '224 Ambika Pinnacle Mall',
    'contact_name' => 'THUMMAR JIMMY JAYPRAKSH',
    'phone' => 0,
    'address_1' => 'N/A',
    'address_2' => '',
    'city' => 'N/A',
    'state' => 'N/A',
    'zip' => 0,
    'gst_number' => '',
    'id' => '260736'
];

$url = 'https://ship.nimbuspost.com/api/warehouse/update';

echo "Testing Http::withBody...\n";
$response = Http::withHeaders([
    'NP-API-KEY' => $apiKey,
    'Accept' => 'application/json'
])->withBody(json_encode($data), 'application/json')->post($url);

print_r($response->json());

echo "\nTesting raw cURL...\n";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'NP-API-KEY: ' . $apiKey,
    'Accept: application/json',
    'Content-Type: application/json'
]);
$curlRes = curl_exec($ch);
curl_close($ch);

echo "cURL Response:\n";
print_r(json_decode($curlRes, true));
