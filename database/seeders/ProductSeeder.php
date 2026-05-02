<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::updateOrCreate(
            ['slug' => 'vitamin-c-effervescent'],
            [
                'title' => 'Vitamin C Effervescent',
                'tagline' => 'Immunity & Skin Health',
                'price' => 1799,
                'mrp' => 2499,
                'description' => 'A refreshing citrus burst designed to boost your immunity.',
                'long_description' => '<p>Our Vitamin C Effervescent tablets are the perfect daily companion for your immune system.</p>',
                'image' => 'products/vitamin-c.jpg',
                'gallery' => ['products/vitamin-c.jpg'],
                'rating' => 4.8,
                'reviews' => 1243,
                'reviews_count' => 1243,
                'theme_color' => 'orange',
                'status' => 'published',
                'benefits' => [
                    ['icon' => 'shield', 'title' => 'Immunity Boost', 'desc' => 'Strengthens defenses.'],
                    ['icon' => 'sparkles', 'title' => 'Skin Glow', 'desc' => 'Supports collagen.'],
                    ['icon' => 'zap', 'title' => 'Energy Boost', 'desc' => 'Keep moving all day.'],
                    ['icon' => 'droplets', 'title' => 'Hydration', 'desc' => 'Perfect with 200ml water.'],
                ],
                'highlights' => [
                    '100% Bioavailable Effervescent Formula',
                    'Clean Label Certified Ingredients',
                    'Zero Sugar & No Artificial Colors',
                    'Fast Acting & Gentle on the Stomach'
                ],
                'ritual' => [
                    ['step' => 1, 'title' => 'Drop it', 'desc' => 'Drop one tablet into 200ml of water.'],
                    ['step' => 2, 'title' => 'Fizz it', 'desc' => 'Watch the pure wellness dissolve.'],
                    ['step' => 3, 'title' => 'Fuel Up', 'desc' => 'Drink and take on your day.'],
                ],
                'specs' => [
                    'Flavor' => 'Orange Burst',
                    'Quantity' => '20 Tablets',
                    'Form' => 'Effervescent Tablets',
                    'Main Ingredient' => 'Vitamin C (1000mg)'
                ]
            ]
        );
    }
}
