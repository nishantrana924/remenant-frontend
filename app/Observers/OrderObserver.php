<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        try {
            \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\OrderPlaced($order));
        } catch (\Exception $e) {
            Log::error("Failed to send order placed email for #{$order->order_number}: " . $e->getMessage());
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Deduct stock when status changes to 'processing' (Confirmed)
        if ($order->isDirty('status') && $order->status === 'processing') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $this->deductProductStock($product, $item->quantity, $order);
                }
            }

            // Send Order Confirmed Email
            try {
                \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\OrderConfirmed($order));
            } catch (\Exception $e) {
                Log::error("Failed to send order confirmed email for #{$order->order_number}: " . $e->getMessage());
            }

            // Phase 2: Warehouse Automation Integration
            $this->processWarehouseAutomation($order);
        }

        // Send Shipment Booked / AWB Assigned Email
        if ($order->isDirty('tracking_id') && !empty($order->tracking_id)) {
            try {
                \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\ShipmentBooked($order));
            } catch (\Exception $e) {
                Log::error("Failed to send shipment booked email for #{$order->order_number}: " . $e->getMessage());
            }
        }

        // Send Shipped Email
        if (($order->isDirty('delivery_status') && $order->delivery_status === 'shipped') || ($order->isDirty('status') && $order->status === 'shipped')) {
            try {
               \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\OrderShipped($order));
            } catch (\Exception $e) {
               Log::error("Failed to send order shipped email for #{$order->order_number}: " . $e->getMessage());
            }
        }

        // Send Delivered Email
        if (($order->isDirty('delivery_status') && $order->delivery_status === 'delivered') || ($order->isDirty('status') && $order->status === 'delivered')) {
            try {
               \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\OrderDelivered($order));
            } catch (\Exception $e) {
               Log::error("Failed to send order delivered email for #{$order->order_number}: " . $e->getMessage());
            }
        }

        // Restock when order is 'cancelled'
        if ($order->isDirty('status') && in_array($order->status, ['cancelled', 'cancellation_requested'])) {
            
            if ($order->status === 'cancelled') {
                // SECURITY PATCH: Only restore if stock was actually deducted!
                $previousStatus = $order->getOriginal('status');
                if (in_array($previousStatus, ['processing', 'shipped', 'delivered'])) {
                    foreach ($order->orderItems as $item) {
                        $product = $item->product;
                        if ($product) {
                            $this->restoreProductStock($product, $item->quantity, $order);
                        }
                    }
                }
            }

            // Send Order Cancelled Email
            try {
               \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\OrderCancelled($order));
           } catch (\Exception $e) {
               Log::error("Failed to send order cancelled email for #{$order->order_number}: " . $e->getMessage());
           }

            // If payment was already captured via Razorpay, send refund notification
            if ($order->status === 'cancelled' && $order->payment_status === 'paid' && $order->payment_method === 'razorpay') {
                try {
                    \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\PaymentRefundInitiated($order));
                } catch (\Exception $e) {
                    Log::error("Failed to send refund initiated email for #{$order->order_number}: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Deduct stock for a product, handling combo items if necessary.
     */
    protected function deductProductStock($product, $quantity, $order)
    {
        $oldStock = $product->stock;
        $product->decrement('stock', $quantity);
        
        InventoryLog::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'change_amount' => -$quantity,
            'reason' => "Order #{$order->order_number} confirmed. Stock auto-deducted.",
            'old_stock' => $oldStock,
            'new_stock' => $product->stock
        ]);

        // If it's a combo, deduct from included items too
        if (in_array($product->product_type, ['combo', 'both'])) {
            foreach ($product->comboItems as $comboItem) {
                $linkedProduct = $comboItem->product;
                if ($linkedProduct) {
                    $itemQty = $comboItem->quantity * $quantity;
                    $this->deductProductStock($linkedProduct, $itemQty, $order);
                }
            }
        }
    }

    /**
     * Restore stock for a product, handling combo items if necessary.
     */
    protected function restoreProductStock($product, $quantity, $order)
    {
        $oldStock = $product->stock;
        $product->increment('stock', $quantity);
        
        InventoryLog::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'change_amount' => $quantity,
            'reason' => "Order #{$order->order_number} cancelled. Stock restored.",
            'old_stock' => $oldStock,
            'new_stock' => $product->stock
        ]);

        // If it's a combo, restore included items too
        if (in_array($product->product_type, ['combo', 'both'])) {
            foreach ($product->comboItems as $comboItem) {
                $linkedProduct = $comboItem->product;
                if ($linkedProduct) {
                    $itemQty = $comboItem->quantity * $quantity;
                    $this->restoreProductStock($linkedProduct, $itemQty, $order);
                }
            }
        }
    }

    /**
     * Executes the Smart Warehouse Engine via isolation and delegation.
     */
    protected function processWarehouseAutomation(Order $order): void
    {
        $mode = config('warehouse.automation_mode', 'disabled');

        if ($mode === 'disabled') {
            return;
        }

        try {
            $auditService = app(\App\Services\Warehouse\WarehouseAuditService::class);
            $batchingService = app(\App\Services\Warehouse\BatchingService::class);

            // 1. Idempotency Protection
            if ($order->requires_manual_review) {
                $auditService->log(null, 'observer_skipped', "Order already under manual review", null, $order->id);
                return;
            }
            if (in_array($order->status, ['completed', 'cancelled'])) {
                $auditService->log(null, 'observer_skipped', "Order is completed or cancelled", null, $order->id);
                return;
            }
            if ($order->warehouseBatches()->where('status', '!=', 'completed')->exists()) {
                $auditService->log(null, 'observer_skipped', "Order already assigned to an active batch", null, $order->id);
                return;
            }

            // 2. Manual Review Routing
            if (empty($order->shipping_address_id)) {
                $reason = "Invalid or missing shipping address.";
                $order->update(['requires_manual_review' => true, 'manual_review_reason' => $reason]);
                $auditService->log(null, 'manual_review_routed', $reason, null, $order->id);
                return;
            }

            // 3. Delegate to BatchingService (calculates packaging, generates batch, assigns courier)
            // In 'parallel' mode, these batches and assignments are created but the external queue jobs will ignore them.
            // In 'enabled' mode, full execution flows downstream.
            $batchingService->assignToBatch($order);

        } catch (\Exception $e) {
            // Failure Isolation: Never break customer order approval
            Log::error("Warehouse Observer Exception for Order #{$order->id}: " . $e->getMessage());
            
            try {
                app(\App\Services\Warehouse\WarehouseAuditService::class)->log(
                    userId: null, 
                    action: 'observer_failure', 
                    description: "Exception isolated: " . substr($e->getMessage(), 0, 255), 
                    batchId: null, 
                    orderId: $order->id
                );
            } catch (\Exception $inner) {
                // If DB is completely unreachable, fail silently to protect checkout
            }
        }
    }
}
