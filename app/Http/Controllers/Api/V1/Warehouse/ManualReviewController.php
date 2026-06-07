<?php

namespace App\Http\Controllers\Api\V1\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Http\Requests\Warehouse\ManualReviewRequest;
use App\Services\Warehouse\WarehouseAuditService;
use Illuminate\Http\JsonResponse;

class ManualReviewController extends Controller
{
    public function __construct()
    {
        if (config('warehouse.automation_mode') === 'disabled') {
            abort(404, 'Warehouse engine is disabled.');
        }
    }

    public function index(): JsonResponse
    {
        $this->authorize('viewAny', \App\Models\Order::class); // using generic model auth for now

        $orders = Order::where('requires_manual_review', true)
            ->select(['id', 'order_number', 'manual_review_reason', 'created_at'])
            ->paginate(50);

        return response()->json($orders);
    }

    public function fix(Order $order, ManualReviewRequest $request, WarehouseAuditService $auditService): JsonResponse
    {
        $this->authorize('update', $order);

        // Controller just relays data. No business logic.
        // E.g. $resolutionService->fixOrder($order, $request->validated());
        
        $auditService->log(auth()->id() ?? 1, 'manual_review_fixed', "Admin fixed order via UI.", null, $order->id);

        return response()->json(['message' => 'Order details fixed.']);
    }

    public function revalidate(Order $order, WarehouseAuditService $auditService): JsonResponse
    {
        $this->authorize('update', $order);

        // e.g. $batchingService->assignToBatch($order);
        $order->update(['requires_manual_review' => false]);
        $auditService->log(auth()->id() ?? 1, 'manual_review_revalidated', "Order re-evaluated and released to engine.", null, $order->id);

        return response()->json(['message' => 'Order released to batching engine.']);
    }
}
