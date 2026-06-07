<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nimbus = new \App\Services\NimbusPostService();
// We will just try to cancel a random AWB
$response = $nimbus->cancelShipment('1234', 'AWB123456');
print_r($response);
