<?php

namespace App\Jobs\Warehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArchiveWarehouseLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 3600; // Allows up to 1 hour for mass DB extraction

    public function handle()
    {
        $cutoffDate = now()->subDays(90)->endOfDay();
        $query = DB::table('warehouse_activity_logs')->where('created_at', '<=', $cutoffDate);
        
        if ($query->count() === 0) {
            return;
        }

        $monthStr = $cutoffDate->format('Y-m');
        $fileName = "warehouse-archives/{$monthStr}/archive_".now()->timestamp.".ndjson";
        
        // Open highly scalable memory stream for NDJSON formatting
        $stream = fopen('php://temp', 'r+');
        
        // Chunk extraction to prevent memory exhaustion
        $query->orderBy('id')->chunk(1000, function ($logs) use ($stream) {
            foreach ($logs as $log) {
                fwrite($stream, json_encode($log) . "\n");
            }
        });
        
        rewind($stream);
        
        // Push directly to configured S3 cold storage bucket
        Storage::disk('s3')->put($fileName, $stream);
        fclose($stream);
        
        // Only delete local records if S3 upload succeeds
        $query->delete();
    }
}
