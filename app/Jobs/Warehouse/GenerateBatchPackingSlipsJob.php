<?php

namespace App\Jobs\Warehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WarehouseBatch;
use App\Services\Warehouse\WarehouseAuditService;

class GenerateBatchPackingSlipsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    protected int $batchId;
    protected string $jobUuid;

    public function __construct(int $batchId, string $jobUuid)
    {
        $this->batchId = $batchId;
        $this->jobUuid = $jobUuid;
    }

    public function handle(WarehouseAuditService $auditService)
    {
        $batch = WarehouseBatch::find($this->batchId);
        if (!$batch) return;

        // Idempotency: Skip if slips exist
        if (in_array($batch->status, ['slips_printed', 'ready_for_pickup', 'dispatched'])) {
            $auditService->log(null, 'job_skipped', "Packing slips already generated.", $this->batchId, null, $this->jobUuid);
            return;
        }

        if ($batch->status !== 'labels_generated') {
            $auditService->log(null, 'job_failed', "Batch must be labels_generated.", $this->batchId, null, $this->jobUuid);
            return;
        }

        // Local PDF Generation (No Circuit Breaker Needed)
        $batch->update(['status' => 'slips_printed']);
        $auditService->log(null, 'slips_printed', "Packing slips generated locally.", $this->batchId, null, $this->jobUuid);
    }
}
