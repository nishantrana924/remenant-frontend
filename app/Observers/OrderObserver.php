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
        if ($order->isDirty('status') && $order->status === 'cancelled') {
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

            // Send Order Cancelled Email
            try {
               \Illuminate\Support\Facades\Mail::to($order->email)->queue(new \App\Mail\OrderCancelled($order));
           } catch (\Exception $e) {
               Log::error("Failed to send order cancelled email for #{$order->order_number}: " . $e->getMessage());
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
}
