<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WarehouseBatch;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;

#[Layout('admin.layouts.app')]
class BatchManagement extends Component
{
    use WithPagination;

    // Search and Filters with Query String Persistence
    #[Url]
    public $search = '';

    #[Url]
    public $status = '';

    #[Url]
    public $type = '';

    #[Url]
    public $assignedCourier = '';

    // Reset pagination on filter change
    public function updating($field)
    {
        if (in_array($field, ['search', 'status', 'type', 'assignedCourier'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        // 1. Feature Flag Protection
        if (config('warehouse.automation_mode') === 'disabled') {
            abort(404, 'Warehouse engine disabled.');
        }

        // 2. Policy Authorization
        $this->authorize('viewAny', WarehouseBatch::class);

        // 3. Eager Loaded Paginated Query (Strictly 1 query per render)
        $query = WarehouseBatch::with(['suggestedCourier', 'assignedCourier', 'lockedByUser'])
            ->orderBy('id', 'desc');

        // Server-Side Filters
        if ($this->search) {
            $query->where('batch_signature', 'like', "%{$this->search}%")
                  ->orWhere('id', 'like', "%{$this->search}%");
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->type) {
            $query->where('batch_type', $this->type);
        }

        if ($this->assignedCourier) {
            $query->where('assigned_courier_id', $this->assignedCourier);
        }

        $batches = $query->paginate(25);

        return view('livewire.warehouse.batch-management', [
            'batches' => $batches,
            'isParallel' => config('warehouse.automation_mode') === 'parallel',
        ]);
    }
}
