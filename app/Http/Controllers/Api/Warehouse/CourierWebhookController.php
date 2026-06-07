<?php

namespace App\Http\Controllers\Api\Warehouse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warehouse\CourierWebhookRequest;
use App\Models\WarehouseBatch;
use App\Services\Warehouse\WarehouseAuditService;

class CourierWebhookController extends Controller
{
    protected WarehouseAuditService $auditService;

    public function __construct(WarehouseAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function handle(CourierWebhookRequest $request)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $batch = WarehouseBatch::where('id', $request->validated('batch_id'))->lockForUpdate()->firstOrFail();
            $newStatus = $request->validated('status');

            // Status Regression Protection
            $statusRank = [
                'pending' => 1,
                'processing' => 2,
                'awb_generated' => 3,
                'labels_generated' => 4,
                'slips_printed' => 5,
                'ready_for_pickup' => 6,
                'dispatched' => 7,
                'completed' => 8,
                'failed' => 9,
                'frozen' => 10,
                'manual_review' => 11,
            ];

            $currentRank = $statusRank[$batch->status] ?? 0;
            $incomingRank = $statusRank[$newStatus] ?? 0;

            if ($incomingRank <= $currentRank && !in_array($newStatus, ['failed', 'completed'])) {
                $this->auditService->log($batch->id, 'webhook_regression_rejected', null, "Attempted regression from {$batch->status} to {$newStatus}");
                return response()->json(['error' => 'Status regression not allowed'], 400);
            }

            // Apply Status Update
            if ($newStatus === 'dispatched') {
                $batch->update([
                    'status' => 'dispatched',
                    'dispatched_at' => now(),
                ]);
                $this->auditService->log($batch->id, 'dispatch_confirmed', null, 'Webhook confirmed dispatch.');
            } elseif ($newStatus === 'completed') {
                $batch->update(['status' => 'completed']);
                $this->auditService->log($batch->id, 'batch_completed', null, 'Webhook confirmed completion.');
            } else {
                // Edge cases or failed status handling
                $batch->update(['status' => 'manual_review']);
                $this->auditService->log($batch->id, 'pickup_failed', null, "Webhook reported failure: {$request->validated('details')}");
            }

            return response()->json(['message' => 'Webhook processed successfully'], 200);
        });
    }
}
