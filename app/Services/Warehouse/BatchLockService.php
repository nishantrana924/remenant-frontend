<?php

namespace App\Services\Warehouse;

use App\Models\WarehouseBatch;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Exception;
use Carbon\Carbon;

class BatchLockService
{
    protected WarehouseAuditService $auditService;

    public function __construct(WarehouseAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Attempts to acquire a lock on a batch.
     * Throws 423 Locked Exception if someone else holds an active lock.
     */
    public function acquireLock(int $batchId, int $userId): WarehouseBatch
    {
        return DB::transaction(function () use ($batchId, $userId) {
            $batch = WarehouseBatch::where('id', $batchId)->lockForUpdate()->firstOrFail();

            // Detect and clear stale lock
            $this->evaluateStaleLock($batch);

            if (!is_null($batch->locked_by) && $batch->locked_by !== $userId) {
                // Lock ownership validation failed
                $this->auditService->log(
                    userId: $userId, 
                    action: 'lock_conflict_detected', 
                    description: "Admin attempted access but batch is locked by User ID: {$batch->locked_by}", 
                    batchId: $batch->id
                );
                throw new HttpException(423, 'Batch is currently being processed by another Admin.');
            }

            // If we don't already own the lock, acquire it
            if ($batch->locked_by !== $userId) {
                $batch->update([
                    'locked_by' => $userId,
                    'locked_at' => now(),
                ]);

                $this->auditService->log($userId, 'batch_locked', "Admin acquired lock for processing.", $batch->id);
            }

            return $batch;
        }, 3);
    }

    /**
     * Explicitly releases a lock.
     */
    public function releaseLock(int $batchId, int $userId): void
    {
        DB::transaction(function () use ($batchId, $userId) {
            $batch = WarehouseBatch::where('id', $batchId)->lockForUpdate()->firstOrFail();

            if ($batch->locked_by === $userId) {
                $batch->update([
                    'locked_by' => null,
                    'locked_at' => null,
                ]);

                $this->auditService->log($userId, 'batch_released', "Admin manually released lock.", $batch->id);
            }
        });
    }

    /**
     * Validates lock ownership before performing a mutation.
     * Throws 423 if the action is illegal.
     */
    public function enforceLockOwnership(WarehouseBatch $batch, int $userId): void
    {
        $this->evaluateStaleLock($batch);

        if ($batch->locked_by !== $userId) {
            $this->auditService->log(
                userId: $userId, 
                action: 'unauthorized_action_attempt', 
                description: "Attempted action without holding the lock.", 
                batchId: $batch->id
            );
            throw new HttpException(423, 'You must acquire the lock before performing this action.');
        }
    }

    /**
     * Detects stale locks and auto-releases them.
     */
    private function evaluateStaleLock(WarehouseBatch $batch): void
    {
        if (is_null($batch->locked_by) || is_null($batch->locked_at)) {
            return;
        }

        $timeoutMinutes = config('warehouse.lock_timeout_minutes', 15);
        $lockExpiredAt = Carbon::parse($batch->locked_at)->addMinutes($timeoutMinutes);

        if (now()->greaterThanOrEqualTo($lockExpiredAt)) {
            // Auto release expired lock
            $oldUser = $batch->locked_by;
            
            $batch->locked_by = null;
            $batch->locked_at = null;
            $batch->save();

            $this->auditService->log(
                userId: null, 
                action: 'lock_timeout_released', 
                description: "Batch lock automatically released due to timeout. Previously held by User ID: {$oldUser}", 
                batchId: $batch->id
            );
        }
    }
}
