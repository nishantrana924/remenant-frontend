<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Courier extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function shippingRules()
    {
        return $this->hasMany(ShippingRule::class);
    }

    public function suggestedBatches()
    {
        return $this->hasMany(WarehouseBatch::class, 'suggested_courier_id');
    }

    public function assignedBatches()
    {
        return $this->hasMany(WarehouseBatch::class, 'assigned_courier_id');
    }
}
