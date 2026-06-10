<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

header('Content-Type: text/plain');

echo "=== DATABASE CONNECTION TEST ===\n";
try {
    $dbName = DB::connection()->getDatabaseName();
    echo "Connected to database: {$dbName}\n";
} catch (\Exception $e) {
    echo "DB Connection Failed: " . $e->getMessage() . "\n";
    exit;
}

echo "\n=== WEBHOOK AUDITS ===\n";
try {
    $audits = DB::table('webhook_audits')->orderBy('id', 'desc')->limit(10)->get();
    if ($audits->isEmpty()) {
        echo "No webhook audits found.\n";
    } else {
        foreach ($audits as $audit) {
            echo "ID: {$audit->id} | AWB/Webhook: {$audit->webhook_id} | Status: {$audit->status} | Result: {$audit->result} | Date: {$audit->created_at}\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== WEBHOOK LOGS ===\n";
try {
    $logs = DB::table('webhook_logs')->orderBy('id', 'desc')->limit(10)->get();
    if ($logs->isEmpty()) {
        echo "No webhook logs found.\n";
    } else {
        foreach ($logs as $log) {
            echo "ID: {$log->id} | Webhook ID: {$log->webhook_id} | Event: {$log->event_type} | Date: {$log->created_at}\n";
            echo "Payload: " . substr($log->payload, 0, 150) . "...\n";
            echo "----------------------------------------\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "\n=== ORDERS WITH TRACKING INFO ===\n";
try {
    $orders = DB::table('orders')
        ->whereNotNull('tracking_id')
        ->orWhereNotNull('courier_name')
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();
    if ($orders->isEmpty()) {
        echo "No orders with tracking info found.\n";
    } else {
        foreach ($orders as $order) {
            echo "ID: {$order->id} | Order: {$order->order_number} | Status: {$order->status} | Delivery Status: {$order->delivery_status} | Courier: {$order->courier_name} | AWB: {$order->tracking_id}\n";
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
