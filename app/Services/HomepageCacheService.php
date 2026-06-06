<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class HomepageCacheService
{
    /**
     * Clear all cached items related to the homepage.
     */
    public function clear(): void
    {
        Cache::forget('home.sliders');
        Cache::forget('home.featured_products');
        Cache::forget('home.products');
        Cache::forget('home.combos');
        Cache::forget('home.featured_reviews');
    }
}
