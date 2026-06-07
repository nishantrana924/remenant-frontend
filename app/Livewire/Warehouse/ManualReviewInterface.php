<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Services\Warehouse\WarehouseValidationService;
use App\Services\Warehouse\WarehouseAuditService;
use Illuminate\Support\Facades\Auth;

#[Layout('admin.layouts.app')]
class ManualReviewInterface extends Component
{
    use WithPagination;

    public $selectedOrder = null;
    
    // Address Modal State
    public $showAddressModal = false;
    public $addressForm = [
        'address_line1' => '',
        'city' => '',
        'state' => '',
        'pincode' => '',
    ];

    // Dimensions Modal State
    public $showDimensionsModal = false;
    public $dimensionsForm = [
        'length' => '',
        'width' => '',
        'height' => '',
        'weight' => '',
    ];

    public function render()
    {
        if (config('warehouse.automation_mode') === 'disabled') {
            abort(404, 'Warehouse engine disabled.');
        }

        $orders = Order::where('requires_manual_review', true)
            ->orWhere('status', 'manual_review')
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('livewire.warehouse.manual-review-interface', [
            'orders' => $orders,
            'isParallel' => config('warehouse.automation_mode') === 'parallel',
        ]);
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = Order::with('orderItems')->findOrFail($orderId);
    }

    public function openAddressModal($orderId)
    {
        $this->selectOrder($orderId);
        $this->addressForm = [
            'address_line1' => $this->selectedOrder->shipping_address ?? '',
            'city' => $this->selectedOrder->shipping_city ?? '',
            'state' => $this->selectedOrder->shipping_state ?? '',
            'pincode' => $this->selectedOrder->shipping_pincode ?? '',
        ];
        $this->showAddressModal = true;
    }

    public function closeAddressModal()
    {
        $this->showAddressModal = false;
    }

    public function updateAddress(WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        
        $this->validate([
            'addressForm.address_line1' => 'required|string',
            'addressForm.pincode' => 'required|numeric',
        ]);

        $this->selectedOrder->update([
            'shipping_address' => $this->addressForm['address_line1'],
            'shipping_city' => $this->addressForm['city'],
            'shipping_state' => $this->addressForm['state'],
            'shipping_pincode' => $this->addressForm['pincode'],
        ]);

        $auditService->log(null, 'manual_review_address_fixed', Auth::id(), "Address fixed for Order #{$this->selectedOrder->order_number}");
        
        $this->closeAddressModal();
        session()->flash('success', 'Address updated successfully.');
    }

    public function openDimensionsModal($orderId)
    {
        $this->selectOrder($orderId);
        $this->dimensionsForm = [
            'length' => $this->selectedOrder->length ?? '',
            'width' => $this->selectedOrder->width ?? '',
            'height' => $this->selectedOrder->height ?? '',
            'weight' => $this->selectedOrder->total_weight ?? '',
        ];
        $this->showDimensionsModal = true;
    }

    public function closeDimensionsModal()
    {
        $this->showDimensionsModal = false;
    }

    public function updateDimensions(WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        
        $this->validate([
            'dimensionsForm.weight' => 'required|numeric|min:0.1',
        ]);

        $this->selectedOrder->update([
            'length' => $this->dimensionsForm['length'],
            'width' => $this->dimensionsForm['width'],
            'height' => $this->dimensionsForm['height'],
            'total_weight' => $this->dimensionsForm['weight'],
        ]);

        $auditService->log(null, 'manual_review_dimensions_fixed', Auth::id(), "Dimensions fixed for Order #{$this->selectedOrder->order_number}");

        $this->closeDimensionsModal();
        session()->flash('success', 'Dimensions updated successfully.');
    }

    public function requeueOrder($orderId, WarehouseValidationService $validationService, WarehouseAuditService $auditService)
    {
        if (config('warehouse.automation_mode') === 'parallel') return;
        
        $order = Order::findOrFail($orderId);

        try {
            // Re-run validation logic (assumed method on service)
            if (method_exists($validationService, 'validateOrderForAutomation')) {
                $validationService->validateOrderForAutomation($order);
            }

            $order->update([
                'requires_manual_review' => false,
                'status' => 'pending'
            ]);

            $auditService->log(null, 'manual_review_resolved', Auth::id(), "Order #{$order->order_number} resolved and returned to engine.");
            
            session()->flash('success', "Order #{$order->order_number} returned to processing queue.");
            $this->selectedOrder = null;
        } catch (\Exception $e) {
            $auditService->log(null, 'manual_review_resolution_failed', Auth::id(), "Re-queue failed: " . $e->getMessage());
            $this->addError('requeue', "Validation failed: " . $e->getMessage());
        }
    }
}
