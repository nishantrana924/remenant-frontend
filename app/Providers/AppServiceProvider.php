<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\SEOService::class, function ($app) {
            return new \App\Services\SEOService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Order::observe(\App\Observers\OrderObserver::class);

        $clearCache = function () {
            app(\App\Services\HomepageCacheService::class)->clear();
        };

        \App\Models\Product::saved($clearCache);
        \App\Models\Product::deleted($clearCache);

        \App\Models\Slider::saved($clearCache);
        \App\Models\Slider::deleted($clearCache);

        \App\Models\Review::saved($clearCache);
        \App\Models\Review::deleted($clearCache);
    }
}
