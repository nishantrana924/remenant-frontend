<?php

namespace App\Services\Warehouse;

use App\Models\WarehouseBatch;
use Exception;

class BatchValidationService
{
    protected BatchLockService $lockService;
    protected WarehouseAuditService $auditService;

    // Linear progression map
    private const TRANSITION_MAP = [
        'pending' => 'processing',
        'processing' => 'awb_generated',
        'awb_generated' => 'labels_generated',
        'labels_generated' => 'slips_printed',
        'slips_printed' => 'ready_for_pickup',
        'ready_for_pickup' => 'dispatched',
    ];

    public function __construct(BatchLockService $lockService, WarehouseAuditService $auditService)
    {
        $this->lockService = $lockService;
        $this->auditService = $auditService;
    }

    /**
     * Complete validation pipeline before execution of any Queue Job.
     */
    public function validateForJob(WarehouseBatch $batch, int $userId, string $targetStatus, string $jobUuid): void
    {
        $this->enforceLock($batch, $userId, $jobUuid);
        $this->enforceStateTransition($batch, $targetStatus, $jobUuid);
        $this->enforceFrozenState($batch, $jobUuid);
        $this->enforceFinancials($batch, $jobUuid);
        
        if ($targetStatus === 'processing') {
            $this->enforceShipmentReadiness($batch, $jobUuid);
        }
    }

    /**
     * Lock validation. Throws HTTP 423 via LockService if invalid.
     */
    public function enforceLock(WarehouseBatch $batch, int $userId, string $jobUuid = null): void
    {
        try {
            $this->lockService->enforceLockOwnership($batch, $userId);
        } catch (Exception $e) {
            $this->logFailure($batch, 'lock_validation_failed', $e->getMessage(), $jobUuid);
            throw $e;
        }
    }

    /**
     * Validates that the status transition is strictly linear.
     */
    public function enforceStateTransition(WarehouseBatch $batch, string $targetStatus, string $jobUuid = null): void
    {
        $expectedNext = self::TRANSITION_MAP[$batch->status] ?? null;

        if ($expectedNext !== $targetStatus) {
            $msg = "Invalid transition. Expected {$expectedNext}, requested {$targetStatus}.";
            $this->logFailure($batch, 'state_transition_failed', $msg, $jobUuid);
            throw new Exception($msg);
        }
    }

    /**
     * Reject mutations on frozen batches.
     * Note: This is exposed so BatchingService can call it directly before adding/removing.
     */
    public function enforceFrozenState(WarehouseBatch $batch, string $jobUuid = null): void
    {
        $frozenStatuses = ['awb_generated', 'labels_generated', 'slips_printed', 'ready_for_pickup', 'dispatched', 'completed'];

        if (in_array($batch->status, $frozenStatuses)) {
            $msg = "Batch is frozen ({$batch->status}). Modifications strictly prohibited.";
            $this->logFailure($batch, 'frozen_batch_violation', $msg, $jobUuid);
            throw new Exception($msg);
        }
    }

    /**
     * Verify batch.total_order_value > 0
     */
    public function enforceFinancials(WarehouseBatch $batch, string $jobUuid = null): void
    {
        if ($batch->total_order_value <= 0) {
            $msg = "Financial validation failed. Batch total order value must be > 0.";
            $this->logFailure($batch, 'financial_validation_failed', $msg, $jobUuid);
            throw new Exception($msg);
        }
    }

    /**
     * Readiness check before hitting NimbusPost.
     */
    public function enforceShipmentReadiness(WarehouseBatch $batch, string $jobUuid = null): void
    {
        $errors = [];

        if (is_null($batch->assigned_courier_id)) {
            $errors[] = "Missing assigned courier.";
        }
        if (is_null($batch->assigned_weight) || $batch->assigned_weight <= 0) {
            $errors[] = "Invalid assigned weight.";
        }
        
        $actualOrderCount = $batch->orders()->count();
        if ($batch->total_orders <= 0 || $batch->total_orders !== $actualOrderCount) {
            $errors[] = "Order count mismatch. Expected {$batch->total_orders}, got {$actualOrderCount}.";
        }

        if ($batch->orders()->where('requires_manual_review', true)->exists()) {
            $errors[] = "One or more orders requires manual review.";
        }

        // Simulating checking shipping addresses
        if ($batch->orders()->whereNull('shipping_address_id')->exists()) {
            $errors[] = "Missing shipping addresses.";
        }

        if (!empty($errors)) {
            $msg = "Shipment readiness failed: " . implode(' ', $errors);
            $this->logFailure($batch, 'readiness_validation_failed', $msg, $jobUuid);
            throw new Exception($msg);
        }
    }

    private function logFailure(WarehouseBatch $batch, string $action, string $msg, ?string $jobUuid): void
    {
        $this->auditService->log(
            userId: null, 
            action: $action, 
            description: $msg, 
            batchId: $batch->id, 
            orderId: null, 
            jobUuid: $jobUuid
        );
    }
}
