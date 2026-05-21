<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();
$service = new App\Services\NimbusPostService();
$res = $service->getCouriers();
print_r($res);
