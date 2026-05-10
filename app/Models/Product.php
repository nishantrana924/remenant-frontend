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
        'ritual' => 'array',
        'faqs' => 'array',
        'highlights' => 'array',
        'trust_signals' => 'array',
        'nutrition' => 'array',
        'nutrition_highlights' => 'array',
        'specs' => 'array',
        'is_featured' => 'boolean',
        'status' => 'string',
    ];
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class)->latest();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }
}
