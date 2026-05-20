<?php

require __DIR__ . '/../../vendor/autoload.php';
$app = require_once __DIR__ . '/../../bootstrap/app.php';

use App\Services\NimbusPostService;
use Illuminate\Support\Facades\Log;

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$nimbus = app(NimbusPostService::class);
$response = $nimbus->getWarehouses();

echo "--- NIMBUS WAREHOUSE RESPONSE ---\n";
print_r($response);
echo "---------------------------------\n";
