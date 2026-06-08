<?php

namespace App\Console\Commands\Warehouse;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class RestoreWarehouseArchiveCommand extends Command
{
    protected $signature = 'warehouse:restore-archive {--month= : The YYYY-MM month to restore}';
    protected $description = 'Restore archived NDJSON warehouse logs from S3 into the active database';

    public function handle()
    {
        $month = $this->option('month');
        if (!$month) {
            $this->error('Must provide --month=YYYY-MM parameter.');
            return 1;
        }

        $files = Storage::disk('s3')->files("warehouse-archives/{$month}");
        if (empty($files)) {
            $this->error("No archives found for month {$month} in S3.");
            return 1;
        }

        $this->info("Found " . count($files) . " archive files. Beginning hydration...");

        $totalRestored = 0;

        foreach ($files as $file) {
            $stream = Storage::disk('s3')->readStream($file);
            $batch = [];
            $batchSize = 1000;
            
            while (($line = fgets($stream)) !== false) {
                $data = json_decode($line, true);
                
                // Schema validation to prevent script crash on corrupted JSON
                if (json_last_error() === JSON_ERROR_NONE && isset($data['action'], $data['created_at'])) {
                    unset($data['id']); // Let database assign new primary keys safely
                    $batch[] = $data;
                    $totalRestored++;
                }
                
                // Chunked mass inserts
                if (count($batch) >= $batchSize) {
                    DB::table('warehouse_activity_logs')->insert($batch);
                    $batch = [];
                }
            }
            
            if (!empty($batch)) {
                DB::table('warehouse_activity_logs')->insert($batch);
            }
            
            fclose($stream);
        }

        // Generate official compliance hydration audit trail
        DB::table('warehouse_activity_logs')->insert([
            'batch_id' => null,
            'action' => 'hydration_completed',
            'details' => "Restored {$totalRestored} archive logs for {$month}.",
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->info("Hydration completed. Restored {$totalRestored} logs.");
        return 0;
    }
}
