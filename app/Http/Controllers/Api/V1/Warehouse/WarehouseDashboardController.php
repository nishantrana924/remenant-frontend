<?php

namespace App\Http\Controllers\Api\V1\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\WarehouseBatch;
use Illuminate\Http\JsonResponse;

class WarehouseDashboardController extends Controller
{
    public function __construct()
    {
        if (config('warehouse.automation_mode') === 'disabled') {
            abort(404, 'Warehouse module disabled.');
        }
    }

    /**
     * Fetch aggregated KPIs for the main dashboard safely.
     */
    public function index(): JsonResponse
    {
        // 1. Authorize via implicit logic or dedicated Policy
        // Only counting active (un-archived) batches
        $query = WarehouseBatch::whereNull('archived_at');

        $kpis = [
            'pending_batches' => (clone $query)->where('status', 'pending')->count(),
            'ready_for_pickup' => (clone $query)->where('status', 'ready_for_pickup')->count(),
            'dispatched_today' => (clone $query)->where('status', 'dispatched')->whereDate('updated_at', today())->count(),
            'average_batch_size' => (clone $query)->avg('total_orders') ?? 0,
            // Audit queries and health metrics would be wired via WarehouseAuditService aggregations
        ];

        return response()->json(['kpis' => $kpis]);
    }

    /**
     * Parallel Monitoring metrics (accessible only in parallel mode).
     */
    public function monitoring(): JsonResponse
    {
        if (config('warehouse.automation_mode') !== 'parallel') {
            abort(403, 'Monitoring screen is strictly for parallel mode execution testing.');
        }

        $metrics = [
            'engine_batches_created_today' => WarehouseBatch::whereDate('created_at', today())->count(),
            // Legacy comparisons would be queried from the Order model
        ];

        return response()->json(['metrics' => $metrics]);
    }
}
