<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== LAST 10 WEBHOOK AUDITS ===\n";
try {
    $audits = DB::table('webhook_audits')->orderBy('id', 'desc')->limit(10)->get();
    if ($audits->isEmpty()) {
        echo "No webhook audits found.\n";
    } else {
        foreach ($audits as $audit) {
            echo "ID: {$audit->id} | Webhook ID: {$audit->webhook_id} | Provider: {$audit->provider} | IP: {$audit->ip} | Status: {$audit->status} | Result: {$audit->result} | Created At: {$audit->created_at}\n";
        }
    }
} catch (\Exception $e) {
    echo "Error querying webhook_audits: " . $e->getMessage() . "\n";
}

echo "\n=== LAST 5 WEBHOOK LOGS ===\n";
try {
    $logs = DB::table('webhook_logs')->orderBy('id', 'desc')->limit(5)->get();
    if ($logs->isEmpty()) {
        echo "No webhook logs found.\n";
    } else {
        foreach ($logs as $log) {
            echo "ID: {$log->id} | Webhook ID: {$log->webhook_id} | Event: {$log->event_type} | Created At: {$log->created_at}\n";
            echo "Payload: " . substr($log->payload, 0, 150) . "...\n";
            echo "----------------------------------------\n";
        }
    }
} catch (\Exception $e) {
    echo "Error querying webhook_logs: " . $e->getMessage() . "\n";
}
