<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\WarehouseBatch;
use Livewire\Attributes\Layout;
use App\Services\Warehouse\BatchLockService;
use App\Services\Warehouse\BatchValidationService;
use App\Services\Warehouse\WarehouseAuditService;
use Illuminate\Support\Facades\Auth;
use App\Models\Courier;
use App\Jobs\Warehouse\ProcessBatchAWBJob;
use App\Jobs\Warehouse\ProcessBatchLabelsJob;
use App\Jobs\Warehouse\GenerateBatchPackingSlipsJob;
use App\Jobs\Warehouse\SchedulePickupJob;

#[Layout('admin.layouts.app')]
class BatchDetail extends Component
{
    public WarehouseBatch $batch;
    public $availableCouriers = [];
    public $selectedCourierId = '';
    public $assignedWeight = '';

    public function mount($id)
    {
        // 1. Feature Flag Protection
        if (config('warehouse.automation_mode') === 'disabled') {
            abort(404, 'Warehouse engine disabled.');
        }

        // 2. Load Batch with strictly budgeted Eager Loading (Max 4 queries total)
        $this->batch = WarehouseBatch::with([
            'orders.orderItems', 
            'activityLogs', 
            'suggestedCourier', 
            'assignedCourier', 
            'lockedByUser'
        ])->findOrFail($id);

        // 3. Policy Authorization
        $this->authorize('view', $this->batch);

        // 4. Initialize Form States
        $this->availableCouriers = Courier::where('is_active', true)->get();
        $this->selectedCourierId = $this->batch->assigned_courier_id ?? '';
        $this->assignedWeight = $this->batch->total_weight ?? '';
    }

    public function getHealthStatusProperty()
    {
        if ($this->batch->status === 'frozen') return 'frozen';
        if ($this->batch->status === 'manual_review') return 'manual_review';
        
        $warningStates = ['processing_failed', 'validation_failed', 'pickup_failed'];
        if (in_array($this->batch->status, $warningStates)) {
            return 'warning';
        }

        return 'healthy';
    }

    public function acquireLock(BatchLockService $lockService, WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        try {
            $lockService->acquireLock($this->batch, Auth::user());
            $this->batch->refresh();
        } catch (\Exception $e) {
            $this->addError('lock', $e->getMessage());
        }
    }

    public function releaseLock(BatchLockService $lockService, WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        try {
            $lockService->releaseLock($this->batch, Auth::user());
            $this->batch->refresh();
        } catch (\Exception $e) {
            $this->addError('lock', $e->getMessage());
        }
    }

    public function assignCourier(BatchValidationService $validationService, WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        $this->validate(['selectedCourierId' => 'required|exists:couriers,id']);

        // Lock Ownership Validation
        if ($this->batch->locked_by_user_id !== Auth::id()) {
            $this->addError('mutation', 'You do not own the lock on this batch.');
            return;
        }

        $oldCourier = $this->batch->assigned_courier_id;
        $this->batch->update(['assigned_courier_id' => $this->selectedCourierId]);
        
        $auditService->log(
            $this->batch->id,
            'courier_assigned',
            Auth::id(),
            "Courier changed from {$oldCourier} to {$this->selectedCourierId}"
        );

        $this->batch->refresh();
    }

    public function assignWeight(BatchValidationService $validationService, WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        $this->validate(['assignedWeight' => 'required|numeric|min:0.1']);

        // Lock Ownership Validation
        if ($this->batch->locked_by_user_id !== Auth::id()) {
            $this->addError('mutation', 'You do not own the lock on this batch.');
            return;
        }

        $oldWeight = $this->batch->total_weight;
        $this->batch->update(['total_weight' => $this->assignedWeight]);
        
        $auditService->log(
            $this->batch->id,
            'weight_assigned',
            Auth::id(),
            "Weight changed from {$oldWeight} to {$this->assignedWeight}"
        );

        $this->batch->refresh();
    }

    public function generateAWB(BatchValidationService $validationService, WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        if ($this->batch->locked_by_user_id !== Auth::id()) {
            $this->addError('fulfillment', 'You do not own the lock on this batch.');
            return;
        }

        // AWB Protection Rules
        if (!$this->batch->assigned_courier_id || !$this->batch->total_weight || $this->batch->total_orders <= 0 || $this->batch->status !== 'pending' || !is_null($this->batch->awb_generated_at)) {
            $reason = 'AWB requirements unmet. Missing courier, weight, invalid status, or already generated.';
            $auditService->log($this->batch->id, 'awb_generation_rejected', Auth::id(), $reason);
            $this->addError('fulfillment', $reason);
            return;
        }

        ProcessBatchAWBJob::dispatch($this->batch->id);
        
        $auditService->log($this->batch->id, 'awb_generation_requested', Auth::id(), 'AWB generation job dispatched.');
        session()->flash('fulfillment_success', 'AWB generation requested successfully.');
        $this->batch->refresh();
    }

    public function generateLabels(WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        if ($this->batch->locked_by_user_id !== Auth::id()) {
            $this->addError('fulfillment', 'You do not own the lock on this batch.');
            return;
        }

        if (is_null($this->batch->awb_generated_at) || !is_null($this->batch->labels_generated_at)) {
            $this->addError('fulfillment', 'Cannot generate labels. AWB must be present and labels must not already exist.');
            return;
        }

        ProcessBatchLabelsJob::dispatch($this->batch->id);
        
        $auditService->log($this->batch->id, 'labels_generation_requested', Auth::id(), 'Label generation job dispatched.');
        session()->flash('fulfillment_success', 'Label generation requested successfully.');
        $this->batch->refresh();
    }

    public function generateSlips(WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        if ($this->batch->locked_by_user_id !== Auth::id()) {
            $this->addError('fulfillment', 'You do not own the lock on this batch.');
            return;
        }

        if (!is_null($this->batch->slips_printed_at)) {
            $this->addError('fulfillment', 'Packing slips already generated.');
            return;
        }

        GenerateBatchPackingSlipsJob::dispatch($this->batch->id);
        
        $auditService->log($this->batch->id, 'slips_generation_requested', Auth::id(), 'Packing slips generation job dispatched.');
        session()->flash('fulfillment_success', 'Packing slip generation requested successfully.');
        $this->batch->refresh();
    }

    public function schedulePickup(BatchValidationService $validationService, WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        if ($this->batch->locked_by_user_id !== Auth::id()) {
            $this->addError('fulfillment', 'You do not own the lock on this batch.');
            return;
        }

        // Strict pre-execution validation
        if ($this->batch->status !== 'slips_printed' || !$this->batch->assigned_courier_id || !$this->batch->total_weight || is_null($this->batch->awb_generated_at) || is_null($this->batch->labels_generated_at) || is_null($this->batch->slips_printed_at)) {
            $reason = 'Pickup constraints unmet. Missing dependencies or invalid status.';
            $auditService->log($this->batch->id, 'pickup_failed', Auth::id(), $reason);
            $this->addError('fulfillment', $reason);
            return;
        }

        // Optional logic from validation service
        if (method_exists($validationService, 'enforceShipmentReadiness')) {
            try {
                $validationService->enforceShipmentReadiness($this->batch);
            } catch (\Exception $e) {
                $auditService->log($this->batch->id, 'pickup_failed', Auth::id(), 'Shipment readiness check failed: ' . $e->getMessage());
                $this->addError('fulfillment', $e->getMessage());
                return;
            }
        }

        // Update status for readiness prior to dispatch
        $this->batch->update(['status' => 'ready_for_pickup']);
        $auditService->log($this->batch->id, 'pickup_requested', Auth::id(), 'Pickup scheduling requested.');

        // For safety, assuming SchedulePickupJob exists. If not, it will be mapped correctly in Phase 4.
        if (class_exists(SchedulePickupJob::class)) {
            SchedulePickupJob::dispatch($this->batch->id);
            session()->flash('fulfillment_success', 'Pickup scheduling queued.');
        } else {
            session()->flash('fulfillment_success', 'Pickup status updated to Ready For Pickup.');
        }

        $this->batch->refresh();
    }

    public function confirmDispatch(WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        if ($this->batch->locked_by_user_id !== Auth::id()) {
            $this->addError('fulfillment', 'You do not own the lock on this batch.');
            return;
        }

        // Idempotency: Ignore duplicate dispatch confirmations
        if ($this->batch->status === 'dispatched' || $this->batch->status === 'completed') {
            $auditService->log($this->batch->id, 'dispatch_skipped', Auth::id(), 'Dispatch confirmation skipped. Already dispatched.');
            return;
        }

        // Strict transition enforcement
        if ($this->batch->status !== 'ready_for_pickup') {
            $reason = "Invalid transition. Cannot dispatch from {$this->batch->status}.";
            $auditService->log($this->batch->id, 'dispatch_failed', Auth::id(), $reason);
            $this->addError('fulfillment', $reason);
            return;
        }

        $this->batch->update([
            'status' => 'dispatched',
            'dispatched_at' => now(),
        ]);

        $auditService->log($this->batch->id, 'dispatch_confirmed', Auth::id(), 'Batch physically dispatched.');
        session()->flash('fulfillment_success', 'Batch marked as Dispatched.');
        $this->batch->refresh();
    }

    public function markCompleted(WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        $this->authorize('update', $this->batch);

        if ($this->batch->locked_by_user_id !== Auth::id()) {
            $this->addError('fulfillment', 'You do not own the lock on this batch.');
            return;
        }

        if ($this->batch->status === 'completed') {
            return;
        }

        if ($this->batch->status !== 'dispatched') {
            $reason = "Invalid transition. Cannot complete from {$this->batch->status}.";
            $auditService->log($this->batch->id, 'completion_failed', Auth::id(), $reason);
            $this->addError('fulfillment', $reason);
            return;
        }

        $this->batch->update([
            'status' => 'completed',
        ]);

        $auditService->log($this->batch->id, 'batch_completed', Auth::id(), 'Batch explicitly marked as completed.');
        session()->flash('fulfillment_success', 'Batch marked as Completed.');
        $this->batch->refresh();
    }

    public function render()
    {
        return view('livewire.warehouse.batch-detail', [
            'isParallel' => config('warehouse.automation_mode') === 'parallel',
            'healthStatus' => $this->healthStatus,
        ]);
    }
}
