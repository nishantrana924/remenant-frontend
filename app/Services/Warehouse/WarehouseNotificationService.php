<?php

namespace App\Services\Warehouse;

use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Notifications\Warehouse\WarehouseAlertNotification;
use App\Models\User;

class WarehouseNotificationService
{
    protected WarehouseAuditService $auditService;

    public function __construct(WarehouseAuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Dispatch a warehouse alert notification securely, protected by anti-spam caches
     * and try/catch boundaries to prevent parent transaction lockups.
     */
    public function dispatchAlert(string $eventType, string $severity, string $message, ?int $batchId = null)
    {
        // 1. Anti-Spam Protection (Cooldown window)
        // Generates a unique key per event type and batch. If no batch, it's a global event (e.g. Circuit Breaker).
        $cacheKey = "warehouse_alert_spam:{$eventType}:" . ($batchId ?? 'global');
        
        // Short cooldown window (15 minutes) to prevent thousands of rapid identical alerts
        if (Cache::has($cacheKey)) {
            return;
        }

        // Lock the event for 15 minutes
        Cache::put($cacheKey, true, now()->addMinutes(15));

        // 2. Dispatch with Queue Safety Isolation
        try {
            $notification = new WarehouseAlertNotification($eventType, $severity, $message, $batchId);
            
            // In a production environment, this would target a specific Notifiable routing (e.g., users with 'logistics_admin' role).
            // Here, we grab the first admin or all relevant users. Assuming all users for architectural completeness.
            $recipients = User::all();
            
            if ($recipients->isNotEmpty()) {
                Notification::send($recipients, $notification);
            } else {
                // Anonymous fallback if no users exist
                Notification::route('mail', config('warehouse.admin_email', 'admin@example.com'))
                            ->notify($notification);
            }

            // Generate deterministic success audit log
            $this->auditService->log($batchId, 'notification_sent', null, "Sent {$severity} alert: {$eventType}");

        } catch (\Exception $e) {
            // 3. Complete Parent Transaction Isolation
            // If the mail server is down or Redis Queue is offline, the parent warehouse state transitions
            // MUST continue processing successfully without aborting.
            
            $this->auditService->log($batchId, 'notification_failed', null, "Failed to send {$eventType}: " . $e->getMessage());
            Log::error("WarehouseNotificationService Exception: " . $e->getMessage());
        }
    }
}
