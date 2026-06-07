<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\WarehouseBatch;

#[Layout('admin.layouts.app')]
class MonitoringDashboard extends Component
{
    public function render()
    {
        $this->authorize('viewAny', WarehouseBatch::class);

        // 1. Queue Backlog Count
        $queueBacklog = DB::table('jobs')->count();

        // 2. Failed Jobs Count
        $failedJobs = DB::table('failed_jobs')->count();

        // 3. Circuit Breaker Status
        $circuitBreakerStatus = Cache::get('circuit_breaker:nimbus_post_awb:state', 'CLOSED');

        // 4. Validation Failure Count
        $validationFailures = DB::table('warehouse_activity_logs')
            ->where('action', 'validation_failed')
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        // 5. Lock Timeout Count
        $lockTimeouts = DB::table('warehouse_activity_logs')
            ->where('action', 'lock_released_timeout')
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        // 6. Notification Failure Count
        $notificationFailures = DB::table('warehouse_activity_logs')
            ->where('action', 'notification_failed')
            ->where('created_at', '>=', now()->startOfDay())
            ->count();

        // 7. Manual Review Count
        $manualReviewCount = WarehouseBatch::where('status', 'manual_review')->count();

        // 8. Rates Calculation
        $awbSuccesses = DB::table('warehouse_activity_logs')->where('action', 'awb_generated')->where('created_at', '>=', now()->startOfDay())->count();
        $awbAttempts = DB::table('warehouse_activity_logs')->where('action', 'awb_generation_requested')->where('created_at', '>=', now()->startOfDay())->count();
        $awbSuccessRate = $awbAttempts > 0 ? round(($awbSuccesses / $awbAttempts) * 100, 1) : 100;

        $pickupSuccesses = DB::table('warehouse_activity_logs')->where('action', 'pickup_scheduled')->where('created_at', '>=', now()->startOfDay())->count();
        $pickupFails = DB::table('warehouse_activity_logs')->where('action', 'pickup_failed')->where('created_at', '>=', now()->startOfDay())->count();
        $pickupSuccessRate = ($pickupSuccesses + $pickupFails) > 0 ? round(($pickupSuccesses / ($pickupSuccesses + $pickupFails)) * 100, 1) : 100;

        $dispatchSuccesses = DB::table('warehouse_activity_logs')->where('action', 'dispatch_confirmed')->where('created_at', '>=', now()->startOfDay())->count();
        $dispatchSkips = DB::table('warehouse_activity_logs')->where('action', 'dispatch_skipped')->where('created_at', '>=', now()->startOfDay())->count();
        $dispatchSuccessRate = ($dispatchSuccesses + $dispatchSkips) > 0 ? round(($dispatchSuccesses / ($dispatchSuccesses + $dispatchSkips)) * 100, 1) : 100;

        return view('livewire.warehouse.monitoring-dashboard', compact(
            'queueBacklog',
            'failedJobs',
            'circuitBreakerStatus',
            'validationFailures',
            'lockTimeouts',
            'notificationFailures',
            'manualReviewCount',
            'awbSuccessRate',
            'pickupSuccessRate',
            'dispatchSuccessRate'
        ));
    }
}
