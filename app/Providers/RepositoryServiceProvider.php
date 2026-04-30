<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\App\Repositories\ProductRepositoryInterface::class, \App\Repositories\ProductRepository::class);
        $this->app->bind(\App\Repositories\SliderRepositoryInterface::class, \App\Repositories\SliderRepository::class);
        $this->app->bind(\App\Repositories\OrderRepositoryInterface::class, \App\Repositories\OrderRepository::class);
        $this->app->bind(\App\Repositories\OrderItemRepositoryInterface::class, \App\Repositories\OrderItemRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
