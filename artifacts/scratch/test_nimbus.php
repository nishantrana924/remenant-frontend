<?php
require __DIR__.'/../../vendor/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$nimbus = new \App\Services\NimbusPostService();
// We need a valid nimbus shipment id. Let's find one in db.
$shipment = \App\Models\Shipment::whereNotNull('nimbus_shipment_id')->first();
if ($shipment) {
    echo "Found Shipment ID: " . $shipment->nimbus_shipment_id . "\n";
    $res = $nimbus->getShipmentDetails($shipment->nimbus_shipment_id);
    echo json_encode($res, JSON_PRETTY_PRINT);
} else {
    echo "No shipment found";
}
