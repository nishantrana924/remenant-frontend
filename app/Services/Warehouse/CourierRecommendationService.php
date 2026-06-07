<?php

namespace App\Services\Warehouse;

use App\Models\Order;
use App\Models\ShippingRule;
use App\Models\WarehouseBatch;

class CourierRecommendationService
{
    /**
     * Evaluates a batch's calculated weight against the Shipping Rules engine.
     * Assigns the optimal `suggested_courier_id`.
     * 
     * @param WarehouseBatch $batch
     * @return WarehouseBatch
     */
    public function suggestForBatch(WarehouseBatch $batch): WarehouseBatch
    {
        // Skip if weight is missing or manual review is required
        if (is_null($batch->assigned_weight) || $batch->batch_type === 'manual_review') {
            return $batch;
        }

        // Strict DB query to find the correct rule matching the weight bracket.
        // Sorted by priority so admins can override defaults (higher priority = preferred).
        $rule = ShippingRule::where('is_active', true)
            ->where('min_weight', '<=', $batch->assigned_weight)
            ->where('max_weight', '>=', $batch->assigned_weight)
            ->orderByDesc('priority')
            ->first();

        if ($rule) {
            $batch->suggested_courier_id = $rule->courier_id;
            $batch->save();
            
            // Audit log the recommendation
            app(WarehouseAuditService::class)->log(
                userId: null,
                action: 'courier_recommended',
                description: "System recommended Courier ID {$rule->courier_id} via Rule: {$rule->name}",
                batchId: $batch->id
            );
        }

        return $batch;
    }
}
