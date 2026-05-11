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
}
