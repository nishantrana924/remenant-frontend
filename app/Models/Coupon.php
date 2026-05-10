<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'value', 'min_order_amount', 
        'usage_limit', 'used_count', 'product_ids', 
        'category_ids', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'product_ids' => 'array',
        'category_ids' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function isValidFor($product = null, $total = 0)
    {
        if (!$this->is_active) return false;
        
        $now = now();
        if ($this->start_date && $now->lt($this->start_date)) return false;
        if ($this->end_date && $now->gt($this->end_date)) return false;
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        
        if ($total < $this->min_order_amount) return false;

        // If product is provided, check if it's restricted
        if ($product) {
            // Check product IDs
            if (!empty($this->product_ids) && !in_array($product->id, $this->product_ids)) {
                return false;
            }
            
            // Check category IDs
            if (!empty($this->category_ids)) {
                $productCategoryIds = $product->categories->pluck('id')->toArray();
                if (empty(array_intersect($productCategoryIds, $this->category_ids))) {
                    return false;
                }
            }
        }

        return true;
    }
}
