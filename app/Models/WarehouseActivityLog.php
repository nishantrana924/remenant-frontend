<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseActivityLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function batch()
    {
        return $this->belongsTo(WarehouseBatch::class, 'related_batch_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'related_order_id');
    }
}
