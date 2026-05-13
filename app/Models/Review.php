<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'images',
        'status',
        'is_featured',
        'location'
    ];

    protected $casts = [
        'images' => 'array',
        'is_featured' => 'boolean'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($review) {
            if ($review->images && is_array($review->images)) {
                foreach ($review->images as $image) {
                    \App\Helpers\ImageHelper::delete($image);
                }
            }
        });
    }
}
