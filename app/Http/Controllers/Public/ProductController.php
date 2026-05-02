<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'published')->get();
        
        // Fallback to static if DB is empty (for dev/transition)
        if ($products->isEmpty()) {
            $products = $this->getStaticProducts();
        }
        
        return view('public.products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();

        if (!$product) {
            // Fallback to static
            $staticProducts = $this->getStaticProducts();
            $product = collect($staticProducts)->firstWhere('slug', $slug);
            
            if (!$product) {
                abort(404);
            }
            // Cast to object for compatibility if static
            $product = (object)$product;
            $relatedProducts = collect($staticProducts)->where('slug', '!=', $slug)->take(4);
        } else {
            $relatedProducts = Product::where('slug', '!=', $slug)
                ->where('status', 'published')
                ->take(4)
                ->get();
        }

        return view('public.products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Keep static data as fallback during transition
     */
    private function getStaticProducts()
    {
        $data = [
            [
                'id' => 1,
                'slug' => 'vitamin-c-effervescent',
                'title' => 'Vitamin C Effervescent',
                'tagline' => 'Immunity & Skin Health',
                'description' => 'A refreshing citrus burst designed to boost your immunity.',
                'long_description' => '<p>Our Vitamin C Effervescent tablets are the perfect daily companion for your immune system.</p>',
                'price' => 1799,
                'mrp' => 2499,
                'image' => 'products/vitamin-c.jpg',
                'gallery' => ['products/vitamin-c.jpg'],
                'rating' => 4.8,
                'reviews' => 1243,
                'reviews_count' => 1243,
                'theme_color' => 'orange',
                'benefits' => [
                    (object)['icon' => 'shield', 'title' => 'Immunity Boost', 'desc' => 'Strengthens defenses.'],
                    (object)['icon' => 'sparkles', 'title' => 'Skin Glow', 'desc' => 'Supports collagen.'],
                ],
                'highlights' => [
                    '100% Bioavailable Effervescent Formula',
                    'Clean Label Certified Ingredients'
                ],
                'ritual' => [
                    (object)['step' => 1, 'title' => 'Drop it', 'desc' => 'Drop one tablet into 200ml of water.'],
                    (object)['step' => 2, 'title' => 'Fizz it', 'desc' => 'Watch the pure wellness dissolve.'],
                    (object)['step' => 3, 'title' => 'Fuel Up', 'desc' => 'Drink and take on your day.'],
                ],
                'specs' => [
                    'Flavor' => 'Orange Burst',
                    'Quantity' => '20 Tablets',
                ]
            ]
        ];

        return collect($data)->map(fn($item) => (object)$item);
    }
}
