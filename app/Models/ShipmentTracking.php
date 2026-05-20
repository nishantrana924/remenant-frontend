<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentTracking extends Model
{
    protected $fillable = ['shipment_id', 'status', 'activity', 'location', 'event_at'];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class);
    }
}
