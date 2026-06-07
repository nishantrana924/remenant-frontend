<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function timelines()
    {
        return $this->hasMany(OrderTimeline::class)->latest();
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function logStatus($message = null, $userId = null)
    {
        $this->timelines()->create([
            'status' => $this->status,
            'message' => $message ?? "Order status updated to " . ucfirst($this->status),
            'user_id' => $userId ?? auth()->id(),
        ]);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'completed', 'delivered' => 'emerald',
            'pending' => 'orange',
            'processing', 'packed' => 'indigo',
            'shipped', 'out_for_delivery' => 'blue',
            'cancelled', 'returned' => 'rose',
            default => 'slate',
        };
    }

    public function getDeliveryStatusColorAttribute()
    {
        return match($this->delivery_status) {
            'delivered' => 'emerald',
            'shipped' => 'blue',
            'packed' => 'indigo',
            'pending' => 'orange',
            'returned' => 'rose',
            default => 'slate',
        };
    }
    public function warehouseBatches()
    {
        return $this->belongsToMany(WarehouseBatch::class, 'warehouse_batch_orders', 'order_id', 'batch_id');
    }

    public function warehouseActivityLogs()
    {
        return $this->hasMany(WarehouseActivityLog::class, 'related_order_id');
    }

    public function getCalculatedDimensionsAttribute()
    {
        $weight = 0;
        $length = 0;
        $breadth = 0;
        $height = 0;

        if (!$this->relationLoaded('orderItems')) {
            $this->load('orderItems.product');
        }

        foreach ($this->orderItems as $item) {
            $product = $item->product;
            $quantity = $item->quantity ?? 1;

            if ($product) {
                // If it's a combo, default to 500g, otherwise 90g per bottle
                $w = $product->weight ?? (isset($product->product_type) && $product->product_type === 'combo' ? 500 : 90);
                $l = $product->length ?? 17;
                $b = $product->breadth ?? $product->width ?? 10;
                $h = $product->height ?? 5;
                
                $weight += ($w * $quantity);
                
                if ($l > $length) $length = $l;
                if ($b > $breadth) $breadth = $b;
                $height += ($h * $quantity);
            } else {
                $weight += (90 * $quantity);
                if (17 > $length) $length = 17;
                if (10 > $breadth) $breadth = 10;
                $height += (5 * $quantity);
            }
        }

        return [
            'weight' => $weight > 0 ? $weight : 90,
            'length' => $length > 0 ? $length : 17,
            'breadth' => $breadth > 0 ? $breadth : 10,
            'height' => $height > 0 ? $height : 5,
        ];
    }
}
