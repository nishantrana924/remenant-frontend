<?php

namespace App\Jobs\Warehouse;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WarehouseBatch;
use App\Services\Warehouse\WarehouseAuditService;

class ScheduleBatchPickupJob implements ShouldQueue
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

        // Idempotency: Skip if pickup scheduled
        if (in_array($batch->status, ['ready_for_pickup', 'dispatched', 'completed'])) {
            $auditService->log(null, 'job_skipped', "Pickup already scheduled.", $this->batchId, null, $this->jobUuid);
            return;
        }

        if ($batch->status !== 'slips_printed') {
            $auditService->log(null, 'job_failed', "Batch must be slips_printed.", $this->batchId, null, $this->jobUuid);
            return;
        }

        app(\App\Services\Warehouse\CircuitBreakerService::class)->execute('nimbus_post_pickup', function () use ($batch, $auditService) {
            $batch->update(['status' => 'ready_for_pickup']);
            $auditService->log(null, 'pickup_scheduled', "Pickup scheduled successfully.", $this->batchId, null, $this->jobUuid);
        });
    }
}
