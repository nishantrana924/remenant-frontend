<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLog extends Model
{
    protected $fillable = ['order_id', 'action', 'request_payload', 'response_data', 'status', 'error_message'];

    protected $casts = [
        'request_payload' => 'array',
        'response_data' => 'array',
    ];
}
