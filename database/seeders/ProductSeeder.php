<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'title' => 'Vitamin C Effervescent',
                'slug' => 'vitamin-c-effervescent',
                'tagline' => 'Immunity & Skin Health',
                'price' => 1799,
                'mrp' => 2499,
                'description' => 'A refreshing citrus burst designed to boost your immunity.',
                'long_description' => '<p>Our Vitamin C Effervescent tablets are the perfect daily companion for your immune system.</p>',
                'image' => 'remenant-product11.jpg',
                'gallery' => ['remenant-product11.jpg', 'remenant-product12.jpg'],
                'color_theme' => 'orange',
                'color_gradient' => 'from-orange-500 to-amber-500',
                'benefits' => [
                    ['title' => 'Immunity Boost', 'desc' => 'Strengthens defenses.', 'icon' => 'shield'],
                ],
                'specs' => ['Flavor' => 'Orange', 'Quantity' => '20 Tablets'],
                'is_featured' => true,
            ],
            [
                'title' => 'Biotin Effervescent',
                'slug' => 'biotin-effervescent',
                'tagline' => 'Beauty & Strength',
                'price' => 1699,
                'mrp' => 2399,
                'description' => 'Targeted formula for hair strength and glowing skin.',
                'long_description' => '<p>Achieve your beauty goals with our premium Biotin Effervescent tablets.</p>',
                'image' => 'remenant-product12.jpg',
                'gallery' => ['remenant-product12.jpg', 'remenant-product10.jpg'],
                'color_theme' => 'emerald',
                'color_gradient' => 'from-emerald-500 to-lime-500',
                'benefits' => [
                    ['title' => 'Hair Growth', 'desc' => 'Stronger hair.', 'icon' => 'user'],
                ],
                'specs' => ['Flavor' => 'Green Apple', 'Quantity' => '20 Tablets'],
                'is_featured' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
