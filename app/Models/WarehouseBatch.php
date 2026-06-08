<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseBatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'assigned_weight' => 'decimal:2',
        'total_orders' => 'integer',
        'total_units' => 'integer',
        'total_order_value' => 'decimal:2',
        'estimated_shipping_cost' => 'decimal:2',
        'actual_shipping_cost' => 'decimal:2',
        'locked_at' => 'datetime',
    ];

    public function suggestedCourier()
    {
        return $this->belongsTo(Courier::class, 'suggested_courier_id');
    }

    public function assignedCourier()
    {
        return $this->belongsTo(Courier::class, 'assigned_courier_id');
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'warehouse_batch_orders', 'batch_id', 'order_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(WarehouseActivityLog::class, 'related_batch_id');
    }
}
