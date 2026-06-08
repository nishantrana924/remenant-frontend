<?php

namespace App\Jobs\Warehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WarehouseBatch;
use App\Services\Warehouse\WarehouseAuditService;
use Illuminate\Support\Str;

class ProcessBatchAWBJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30; // Exponential backoff via queue configuration later

    protected int $batchId;
    protected string $jobUuid;

    public function __construct(int $batchId, ?string $jobUuid = null)
    {
        $this->batchId = $batchId;
        $this->jobUuid = $jobUuid ?? Str::uuid()->toString();
    }

    public function handle(WarehouseAuditService $auditService)
    {
        $batch = WarehouseBatch::find($this->batchId);
        if (!$batch) return;

        // Idempotency Hardening
        if ($batch->status !== 'pending') {
            $auditService->log(null, 'job_skipped', "Batch not pending (Status: {$batch->status})", $this->batchId, null, $this->jobUuid);
            return;
        }

        if (!is_null($batch->awb_generated_at) || $batch->awb_job_uuid === $this->jobUuid) {
            $auditService->log(null, 'job_skipped', "AWB already generated. Synced and exited.", $this->batchId, null, $this->jobUuid);
            return;
        }

        // Simulating Circuit Breaker API Execution
        app(\App\Services\Warehouse\CircuitBreakerService::class)->execute('nimbus_post_awb', function () use ($batch, $auditService) {
            // Bulk limits validation handled prior by UI chunking...
            
            // Success: Update batch
            $batch->update([
                'status' => 'processing', // Will become awb_generated in a real downstream webhook or next step
                'awb_generated_at' => now(),
                'awb_job_uuid' => $this->jobUuid,
            ]);

            // Transition to Frozen State
            $batch->update(['status' => 'awb_generated']);

            $auditService->log(null, 'awb_generated', "AWB generated via Job.", $this->batchId, null, $this->jobUuid);
        });
    }
}
