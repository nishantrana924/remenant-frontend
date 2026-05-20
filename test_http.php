<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$data = ['test' => 1];
$headers = [
    'Accept' => 'application/json',
    'NP-API-KEY' => 'test'
];

$request = Http::timeout(30)->withHeaders($headers)->withBody(json_encode($data), 'application/json');
$response = $request->post('https://httpbin.org/post');

print_r($response->json()['headers']);
