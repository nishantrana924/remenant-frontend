<?php

namespace App\Jobs\Warehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WarehouseBatch;
use App\Services\Warehouse\WarehouseAuditService;

class ProcessBatchLabelsJob implements ShouldQueue
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

        // Idempotency: Skip if labels exist
        if ($batch->status === 'labels_generated' || $batch->status === 'slips_printed' || $batch->status === 'ready_for_pickup') {
            $auditService->log(null, 'job_skipped', "Labels already exist.", $this->batchId, null, $this->jobUuid);
            return;
        }

        if ($batch->status !== 'awb_generated') {
            $auditService->log(null, 'job_failed', "Batch must be awb_generated.", $this->batchId, null, $this->jobUuid);
            return;
        }

        app(\App\Services\Warehouse\CircuitBreakerService::class)->execute('nimbus_post_labels', function () use ($batch, $auditService) {
            $batch->update(['status' => 'labels_generated']);
            $auditService->log(null, 'labels_generated', "Shipping labels generated.", $this->batchId, null, $this->jobUuid);
        });
    }
}
