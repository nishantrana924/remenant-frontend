<?php

namespace App\Services\Warehouse;

use App\Models\WarehouseActivityLog;

class WarehouseAuditService
{
    /**
     * Log a warehouse activity securely.
     * This acts as the mandatory timeline tracker for all warehouse actions.
     *
     * @param int|null $userId
     * @param string $action
     * @param string|null $description
     * @param int|null $batchId
     * @param int|null $orderId
     * @return WarehouseActivityLog
     */
    public function log(
        ?int $userId, 
        string $action, 
        ?string $description = null, 
        ?int $batchId = null, 
        ?int $orderId = null,
        ?string $jobUuid = null,
        ?string $requestUuid = null
    ): WarehouseActivityLog {
        return WarehouseActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'job_uuid' => $jobUuid,
            'request_uuid' => $requestUuid,
            'related_batch_id' => $batchId,
            'related_order_id' => $orderId,
        ]);
    }
}
