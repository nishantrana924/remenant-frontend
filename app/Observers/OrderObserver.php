<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\InventoryLog;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
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
                    $oldStock = $product->stock;
                    $product->decrement('stock', $item->quantity);
                    
                    // Log to inventory logs
                    InventoryLog::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'quantity' => $item->quantity,
                        'type' => 'out',
                        'note' => "Order #{$order->order_number} confirmed. Stock auto-deducted.",
                        'previous_stock' => $oldStock,
                        'current_stock' => $product->stock
                    ]);
                }
            }
        }

        // Restock when order is 'cancelled'
        if ($order->isDirty('status') && $order->status === 'cancelled') {
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                if ($product) {
                    $oldStock = $product->stock;
                    $product->increment('stock', $item->quantity);
                    
                    InventoryLog::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'quantity' => $item->quantity,
                        'type' => 'in',
                        'note' => "Order #{$order->order_number} cancelled. Stock restored.",
                        'previous_stock' => $oldStock,
                        'current_stock' => $product->stock
                    ]);
                }
            }
        }
    }
}
