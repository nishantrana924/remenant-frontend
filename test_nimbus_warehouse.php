<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nimbus = app(\App\Services\NimbusPostService::class);
$apiKey = config('services.nimbuspost.api_key');
$token = $nimbus->getToken();
$data = [
    'name' => '224 Ambika Pinnacle Mall',
    'contact_name' => 'THUMMAR JIMMY',
    'phone' => '9876543210',
    'address_1' => 'Test',
    'city' => 'Surat',
    'state' => 'Gujarat',
    'zip' => '394101',
    'id' => '260736'
];

$ch = curl_init('https://ship.nimbuspost.com/api/warehouse/update');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'NP-API-KEY: ' . $apiKey,
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json',
    'Accept: application/json',
    'Expect:'
]);
$res = curl_exec($ch);
echo "POST /warehouse/update : " . $res . "\n";
