<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'nimbus_id', 'name', 'contact_person', 'address', 'address_2', 'city', 'state', 'pincode', 'phone', 'gst_number', 'is_default'
    ];
}
