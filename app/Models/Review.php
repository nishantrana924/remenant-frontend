<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\PurifiesHtml;

class Review extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory, PurifiesHtml;

    public $htmlPurifiable = ['comment'];

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

        $syncProductStats = function ($review) {
            $product = $review->product;
            if ($product) {
                $approvedCount = $product->reviews()->where('status', 'approved')->count();
                $approvedAvg = $product->reviews()->where('status', 'approved')->avg('rating') ?: 0;
                $product->update([
                    'reviews' => $approvedCount,
                    'rating' => round($approvedAvg, 1)
                ]);
            }
        };

        static::saved($syncProductStats);
        static::deleted($syncProductStats);
    }
}
