<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trackings()
    {
        return $this->hasMany(ShipmentTracking::class)->latest('event_at');
    }
}
