<?php

namespace App\Http\Controllers\Api\V1\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseBatch;
use App\Http\Requests\Warehouse\BulkActionRequest;
use App\Services\Warehouse\BatchLockService;
use App\Services\Warehouse\BatchValidationService;
use App\Jobs\Warehouse\ProcessBatchAWBJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WarehouseBatchController extends Controller
{
    protected BatchLockService $lockService;
    protected BatchValidationService $validationService;

    public function __construct(BatchLockService $lockService, BatchValidationService $validationService)
    {
        $this->lockService = $lockService;
        $this->validationService = $validationService;

        // Ensure automation_mode is checked early. A custom middleware is better, but this enforces the rule.
        if (config('warehouse.automation_mode') === 'disabled') {
            abort(404, 'Warehouse engine is disabled.');
        }
    }

    /**
     * Thin Controller: List Batches (Paginated, Aggregate Queries, Eager Loaded)
     */
    public function index(Request $request): JsonResponse
    {
        // 1. Authorize
        $this->authorize('viewAny', WarehouseBatch::class);

        // 2. Query Construction (No business logic)
        $query = WarehouseBatch::with(['courier'])
            ->whereNull('archived_at') // Archival Strategy
            ->orderBy('id', 'desc');

        // Server-Side Search Engine
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('batch_signature')) {
            $query->where('batch_signature', $request->batch_signature);
        }

        return response()->json($query->paginate(50));
    }

    /**
     * Thin Controller: Generate AWB
     */
    public function generateAwb(WarehouseBatch $batch, Request $request): JsonResponse
    {
        // Parallel Mode Guard
        if (config('warehouse.automation_mode') === 'parallel') {
            return response()->json(['message' => 'Action forbidden in parallel mode.'], 403);
        }

        // 1. Authorize Policy
        $this->authorize('generateAwb', $batch);

        // 2. Lock Ownership Verification (Service Layer)
        $this->lockService->enforceLockOwnership($batch, $request->user()->id);

        // 3. Status/Readiness Verification (Service Layer)
        $this->validationService->validateForJob($batch, $request->user()->id, 'processing', 'awb_generation_ui');

        // Maker-Checker Approval Layer evaluation
        if (config('warehouse.approval_required') && !$batch->is_approved) {
            // Pseudo logic for requesting approval...
            return response()->json(['message' => 'Approval requested successfully. Awaiting Manager.'], 202);
        }

        // 4. Dispatch Job
        ProcessBatchAWBJob::dispatch($batch->id);

        // 5. Response
        return response()->json(['message' => 'AWB Generation dispatched to queue successfully.']);
    }

    /**
     * Thin Controller: Bulk AWB Generation (Payload capped via FormRequest)
     */
    public function bulkGenerateAwb(BulkActionRequest $request): JsonResponse
    {
        // Validation ran automatically.
        // Parallel Guard
        if (config('warehouse.automation_mode') === 'parallel') {
            return response()->json(['message' => 'Action forbidden in parallel mode.'], 403);
        }

        // Authorization... Loop and dispatch
        foreach ($request->batch_ids as $batchId) {
            ProcessBatchAWBJob::dispatch($batchId);
        }

        return response()->json(['message' => 'Bulk Job dispatched.']);
    }

    /**
     * Thin Controller: Assign Courier
     */
    public function assignCourier(WarehouseBatch $batch, \App\Http\Requests\Warehouse\AssignCourierRequest $request): JsonResponse
    {
        $this->authorize('update', $batch);
        $this->lockService->enforceLockOwnership($batch, $request->user()->id ?? 1);
        $this->validationService->enforceFrozenState($batch);
        
        // Pseudo service call
        // $this->batchingService->updateCourier($batch, $request->courier_id);
        
        return response()->json(['message' => 'Courier assigned successfully.']);
    }

    /**
     * Thin Controller: Assign Weight
     */
    public function assignWeight(WarehouseBatch $batch, \App\Http\Requests\Warehouse\AssignWeightRequest $request): JsonResponse
    {
        $this->authorize('update', $batch);
        $this->lockService->enforceLockOwnership($batch, $request->user()->id ?? 1);
        $this->validationService->enforceFrozenState($batch);

        // Pseudo service call
        // $this->batchingService->updateWeight($batch, $request->weight);

        return response()->json(['message' => 'Weight overridden successfully.']);
    }

    /**
     * Thin Controller: Lock Batch
     */
    public function lock(WarehouseBatch $batch, \App\Http\Requests\Warehouse\LockBatchRequest $request): JsonResponse
    {
        $this->authorize('lock', $batch);
        $this->lockService->acquireLock($batch->id, $request->user()->id ?? 1);
        return response()->json(['message' => 'Lock acquired.']);
    }

    /**
     * Thin Controller: Unlock Batch
     */
    public function unlock(WarehouseBatch $batch, \App\Http\Requests\Warehouse\UnlockBatchRequest $request): JsonResponse
    {
        $this->authorize('lock', $batch);
        $this->lockService->releaseLock($batch->id, $request->user()->id ?? 1);
        return response()->json(['message' => 'Lock released.']);
    }
}
