<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use App\Models\WarehouseBatch;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

#[Layout('admin.layouts.app')]
class Dashboard extends Component
{
    public $kpis = [];
    public $isLoading = true;

    // Fast loading using init
    public function mount()
    {
        // Handled in loadKpis for skeleton support
    }

    public function loadKpis()
    {
        // 1. Authorize explicitly at component level
        $this->authorize('viewAny', WarehouseBatch::class);

        // 2. Abort if disabled
        if (config('warehouse.automation_mode') === 'disabled') {
            abort(404, 'Warehouse engine is disabled.');
        }

        // Aggregate Query 1: Batch Status Counts
        $batchCounts = WarehouseBatch::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        // Aggregate Query 2: Daily Dispatch Count
        $dispatchedToday = WarehouseBatch::where('status', 'dispatched')
            ->whereDate('updated_at', today())
            ->count();

        // Aggregate Query 3: Manual Review Count
        $manualReviewCount = Order::where('requires_manual_review', true)->count();

        // Aggregate Query 4: Average Batch Size
        $averageBatchSize = (int) WarehouseBatch::avg('total_orders');

        // Total Queries: 4
        $this->kpis = [
            'pending' => $batchCounts['pending'] ?? 0,
            'processing' => $batchCounts['processing'] ?? 0,
            'ready_for_pickup' => $batchCounts['ready_for_pickup'] ?? 0,
            'dispatched_today' => $dispatchedToday,
            'manual_review' => $manualReviewCount,
            'average_size' => $averageBatchSize,
        ];

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.warehouse.dashboard');
    }
}
