<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

$token = Cache::get('nimbuspost_token');

function test_header($headers) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => 'https://api.nimbuspost.com/v1/couriers',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ]);
    $rawResponse = curl_exec($ch);
    echo "Headers:\n"; print_r($headers);
    echo "Response:\n"; print_r($rawResponse);
    echo "\n--------------------------------\n";
}

test_header([
    'Authorization: Bearer ' . $token,
    'Content-Type: application/json',
    'Accept: application/json',
]);

test_header([
    'Authorization: ' . $token,
    'Content-Type: application/json',
    'Accept: application/json',
]);

test_header([
    'Authorization: Token ' . $token,
    'Content-Type: application/json',
    'Accept: application/json',
]);

test_header([
    'Token: ' . $token,
    'Content-Type: application/json',
    'Accept: application/json',
]);
