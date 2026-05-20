<?php
$token = '588ede261ed450f16b76999572db93b8ff45b2760267839';
$response = \Illuminate\Support\Facades\Http::withHeaders([
    'NP-API-KEY' => $token,
    'Accept' => 'application/json'
])->get('https://ship.nimbuspost.com/api/warehouse');

echo "New Base URL (Warehouse): " . $response->body() . "\n";
