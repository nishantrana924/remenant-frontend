<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'gallery' => 'array',
        'benefits' => 'array',
        'specs' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];
}
