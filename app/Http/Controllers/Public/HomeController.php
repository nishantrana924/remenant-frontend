<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active sliders from DB (sliders table is stable)
        $sliders = Slider::where('status', 1)->orderBy('id', 'desc')->get();
        
        // Static Featured Products
        $featuredProducts = $this->getStaticProducts();
            
        // Static All Products
        $products = $this->getStaticProducts();
        
        return view('public.home', compact('sliders', 'featuredProducts', 'products'));
    }

    private function getStaticProducts()
    {
        $data = [
            [
                'id' => 1,
                'slug' => 'vitamin-c-effervescent',
                'title' => 'Vitamin C Effervescent',
                'tagline' => 'Immunity & Skin Health',
                'description' => 'A refreshing citrus burst designed to boost your immunity.',
                'short_description' => 'A refreshing citrus burst designed to boost your immunity.',
                'long_description' => '<p>Our Vitamin C Effervescent tablets are the perfect daily companion for your immune system.</p>',
                'price' => 1799,
                'mrp' => 2499,
                'image' => 'products/vitamin-c.jpg',
                'gallery' => ['products/vitamin-c.jpg'],
                'rating' => 4.8,
                'reviews' => 1243,
                'reviews_count' => 1243,
                'theme_color' => '#ea5f06',
                'images' => collect([
                    (object)['image_path' => 'products/vitamin-c.jpg', 'is_main' => true]
                ]),
                'variants' => collect([
                    (object)['id' => 101, 'variant_name' => '20 Tablets', 'price' => 1799, 'mrp' => 2499]
                ]),
                'highlights' => collect([
                    (object)['title' => 'Immunity Boost', 'description' => 'Strengthens defenses.', 'icon' => 'shield'],
                    (object)['title' => 'Skin Glow', 'description' => 'Supports collagen.', 'icon' => 'sparkles'],
                ]),
                'benefits' => [
                    (object)['title' => 'Immunity Boost', 'desc' => 'Strengthens defenses.', 'icon' => 'shield'],
                    (object)['title' => 'Skin Glow', 'desc' => 'Supports collagen.', 'icon' => 'sparkles'],
                ],
                'specifications' => collect([
                    (object)['key' => 'Flavor', 'value' => 'Orange Burst'],
                    (object)['key' => 'Quantity', 'value' => '20 Tablets'],
                ]),
                'specs' => [
                    'Flavor' => 'Orange Burst',
                    'Quantity' => '20 Tablets',
                ]
            ],
            [
                'id' => 2,
                'slug' => 'biotin-effervescent',
                'title' => 'Biotin Effervescent',
                'tagline' => 'Beauty & Strength',
                'description' => 'Targeted formula for hair strength and glowing skin.',
                'short_description' => 'Targeted formula for hair strength and glowing skin.',
                'long_description' => '<p>Achieve your beauty goals with our premium Biotin Effervescent tablets.</p>',
                'price' => 1699,
                'mrp' => 2399,
                'image' => 'products/biotin.jpg',
                'gallery' => ['products/biotin.jpg'],
                'rating' => 4.7,
                'reviews' => 982,
                'reviews_count' => 982,
                'theme_color' => '#10b981',
                'images' => collect([
                    (object)['image_path' => 'products/biotin.jpg', 'is_main' => true]
                ]),
                'variants' => collect([
                    (object)['id' => 201, 'variant_name' => '20 Tablets', 'price' => 1699, 'mrp' => 2399]
                ]),
                'highlights' => collect([
                    (object)['title' => 'Hair Growth', 'description' => 'Stronger hair.', 'icon' => 'user'],
                ]),
                'benefits' => [
                    (object)['title' => 'Hair Growth', 'desc' => 'Stronger hair.', 'icon' => 'user'],
                ],
                'specifications' => collect([
                    (object)['key' => 'Flavor', 'value' => 'Green Apple'],
                ]),
                'specs' => [
                    'Flavor' => 'Green Apple',
                ]
            ],
        ];

        return collect($data)->map(fn($item) => (object)$item);
    }
}
