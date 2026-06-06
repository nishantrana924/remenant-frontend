<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\PurifiesHtml;

class Product extends Model
{
    use HasFactory, PurifiesHtml;

    public $htmlPurifiable = [
        'description',
        'long_description',
        'specs',
        'nutrition_description',
        'brand_info',
        'benefits_title',
        'benefits_subtitle'
    ];

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

    /**
     * If this product is a combo, it contains these items.
     */
    public function comboItems()
    {
        return $this->hasMany(ComboProductItem::class, 'combo_id');
    }

    /**
     * Relationship to get actual product objects included in this combo.
     */
    public function linkedProducts()
    {
        return $this->belongsToMany(Product::class, 'combo_product_items', 'combo_id', 'product_id')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) return 'https://ui-avatars.com/api/?name=P&background=ea5f06&color=fff';
        
        if (\Illuminate\Support\Str::startsWith($this->image, 'uploads/')) {
            return asset($this->image);
        }
        
        return \Illuminate\Support\Str::startsWith($this->image, 'products/') 
            ? asset('storage/' . $this->image) 
            : asset('images/products/' . $this->image);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }
}
