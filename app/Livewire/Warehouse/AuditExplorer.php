<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\DB;
use App\Models\WarehouseBatch;

#[Layout('admin.layouts.app')]
class AuditExplorer extends Component
{
    use WithPagination;

    #[Url] public $search = '';
    #[Url] public $batchId = '';
    #[Url] public $orderId = '';
    #[Url] public $userId = '';
    #[Url] public $actionType = '';
    #[Url] public $severity = '';
    #[Url] public $dateRange = '';

    public function updating($field)
    {
        $this->resetPage();
    }

    public function render()
    {
        // Enforce Read-Only
        $this->authorize('viewAny', WarehouseBatch::class);

        $query = DB::table('warehouse_activity_logs');

        if (!empty($this->search)) {
            $query->where('details', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->batchId)) {
            $query->where('batch_id', $this->batchId);
        }

        if (!empty($this->orderId)) {
            $query->where('order_id', $this->orderId);
        }

        if (!empty($this->userId)) {
            $query->where('user_id', $this->userId);
        }

        if (!empty($this->actionType)) {
            $query->where('action', $this->actionType);
        }

        if (!empty($this->dateRange)) {
            if ($this->dateRange === 'today') {
                $query->where('created_at', '>=', now()->startOfDay());
            } elseif ($this->dateRange === 'week') {
                $query->where('created_at', '>=', now()->subDays(7));
            }
        }

        // Strict Cursor Pagination Enforcement
        $logs = $query->orderByDesc('id')->cursorPaginate(50);

        $actions = [
            'batch_created', 'lock_acquired', 'lock_released', 'lock_released_timeout',
            'courier_assigned', 'weight_assigned', 'awb_generation_requested', 'awb_generated',
            'pickup_requested', 'pickup_scheduled', 'pickup_failed', 'dispatch_confirmed',
            'dispatch_skipped', 'batch_completed', 'notification_sent', 'notification_failed',
            'validation_failed', 'webhook_regression_rejected', 'webhook_duplicate_ignored'
        ];

        return view('livewire.warehouse.audit-explorer', compact('logs', 'actions'));
    }
}
