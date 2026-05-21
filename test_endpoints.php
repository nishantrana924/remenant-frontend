<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$token = Cache::get('nimbuspost_token');

function test_endpoint($path) {
    global $token;
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.nimbuspost.com/v1' . $path,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
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

test_endpoint('/couriers');
test_endpoint('/courier');
test_endpoint('/shipments');
test_endpoint('/warehouse');
test_endpoint('/warehouses');
test_endpoint('/users/profile');
