<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$service = new App\Services\NimbusPostService();
$token = $service->getToken(true);

function test_post($path, $payload = []) {
    global $token;
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.nimbuspost.com/v1' . $path,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_CUSTOMREQUEST  => 'POST',
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Accept: application/json'
        ],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    $rawResponse = curl_exec($ch);
    echo "Path: $path\nResponse: $rawResponse\n\n";
}

test_post('/shipments/create', ['order_number' => '123']);
test_post('/shipments', ['order_number' => '123']);
test_post('/orders/create', ['order_number' => '123']);
test_post('/orders/ship', ['order_number' => '123']);
