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

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'completed' => 'emerald',
            'pending' => 'orange',
            'cancelled' => 'rose',
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
