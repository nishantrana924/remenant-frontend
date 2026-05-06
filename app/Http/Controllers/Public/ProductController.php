<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request = null)
    {
        $request = $request ?: request();
        $query = Product::where('status', 'published');

        // Multiple Category Filter
        if ($request->has('categories') && is_array($request->categories)) {
            $categories = $request->categories;
            
            $query->where(function($q) use ($categories) {
                // Handle "Combo Offers" special case if it's in the array
                if (in_array('Combo Offers', $categories)) {
                    $q->whereIn('product_type', ['combo', 'both']);
                    $otherCategories = array_diff($categories, ['Combo Offers']);
                    if (!empty($otherCategories)) {
                        $q->orWhereHas('categories', function($sq) use ($otherCategories) {
                            $sq->whereIn('slug', $otherCategories)->orWhereIn('name', $otherCategories);
                        });
                    }
                } else {
                    $q->whereHas('categories', function($sq) use ($categories) {
                        $sq->whereIn('slug', $categories)->orWhereIn('name', $categories);
                    });
                }
            });
        }

        // Price Filter
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting (Applied to DB)
        $sort = $request->get('sort', 'best-selling');
        if ($sort === 'price-low') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price-high') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->get();
        $combos = Product::whereIn('product_type', ['combo', 'both'])->where('status', 'published')->get();
        
        // Merge with static products if we don't have many real ones and no filters are applied
        if (!$request->has('categories') && !$request->has('max_price')) {
            $staticProducts = $this->getStaticProducts();
            if ($products->count() < 12) {
                $products = $products->toBase()->merge($staticProducts->slice(0, 12 - $products->count()));
            }
        }

        // Sort the final collection if merged
        if ($sort === 'price-low') {
            $products = $products->sortBy('price')->values();
        } elseif ($sort === 'price-high') {
            $products = $products->sortByDesc('price')->values();
        }
        
        if ($request->ajax()) {
            return response()->json([
                'html' => view('public.products._grid', compact('products'))->render(),
                'count' => $products->count()
            ]);
        }

        $categories = \App\Models\Category::all();
        
        return view('public.products.index', compact('products', 'categories', 'combos'));
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
                'image' => 'remenant-product1.jpg',
                'gallery' => ['remenant-product2.jpg', 'remenant-product3.jpg'],
                'rating' => 4.8,
                'reviews' => 1243,
                'reviews_count' => 1243,
                'theme_color' => 'orange',
                'highlights' => [
                    '100% Bioavailable Effervescent Formula',
                    'Clean Label Certified Ingredients',
                    'Supports Skin Glow & Collagen'
                ],
                'ritual' => [
                    (object)['step' => 1, 'title' => 'Drop it', 'desc' => 'Drop one tablet into 200ml of water.'],
                    (object)['step' => 2, 'title' => 'Fizz it', 'desc' => 'Watch the pure wellness dissolve.'],
                    (object)['step' => 3, 'title' => 'Fuel Up', 'desc' => 'Drink and take on your day.'],
                ],
                'specs' => [
                    'Flavor' => 'Orange Burst',
                    'Quantity' => '20 Tablets',
                    'Form' => 'Effervescent'
                ]
            ],
            [
                'id' => 2,
                'slug' => 'biotin-effervescent',
                'title' => 'Biotin Effervescent',
                'tagline' => 'Beauty & Strength',
                'description' => 'Targeted formula for hair strength and glowing skin.',
                'long_description' => '<p>Achieve your beauty goals with our premium Biotin Effervescent tablets.</p>',
                'price' => 1699,
                'mrp' => 2399,
                'image' => 'remenant-product4.jpg',
                'gallery' => ['remenant-product5.jpg', 'remenant-product6.jpg'],
                'rating' => 4.7,
                'reviews' => 982,
                'reviews_count' => 982,
                'theme_color' => 'emerald',
                'highlights' => [
                    'High Potency Biotin 10,000mcg',
                    'Promotes Hair & Nail Strength',
                    'Delicious Green Apple Flavor'
                ],
                'ritual' => [
                    (object)['step' => 1, 'title' => 'Drop it', 'desc' => 'Drop one tablet into 200ml of water.'],
                    (object)['step' => 2, 'title' => 'Fizz it', 'desc' => 'Wait for the tablet to dissolve.'],
                    (object)['step' => 3, 'title' => 'Enjoy', 'desc' => 'Sip and let your beauty shine.'],
                ],
                'specs' => [
                    'Flavor' => 'Green Apple',
                    'Quantity' => '20 Tablets',
                    'Form' => 'Effervescent'
                ]
            ],
            [
                'id' => 3,
                'slug' => 'multivitamin-effervescent',
                'title' => 'Daily Multivitamin',
                'tagline' => 'Energy & Vitality',
                'description' => '24 essential vitamins and minerals in one tasty fizz.',
                'long_description' => '<p>Keep your energy levels high and your body healthy with our daily multivitamin.</p>',
                'price' => 1499,
                'mrp' => 1999,
                'image' => 'remenant-product7.jpg',
                'gallery' => ['remenant-product8.jpg'],
                'rating' => 4.9,
                'reviews' => 2105,
                'reviews_count' => 2105,
                'theme_color' => 'blue',
                'highlights' => ['24 Essential Nutrients', 'All-day Energy', 'Sugar-Free'],
                'benefits' => [
                    (object)['icon' => 'zap', 'title' => 'Energy Surge', 'desc' => 'Pure B12 and Ginseng for a natural midday lift.'],
                    (object)['icon' => 'activity', 'title' => 'Daily Balance', 'desc' => 'Restores essential electrolytes for total hydration.']
                ],
                'ritual' => [(object)['step' => 1, 'title' => 'Morning Fizz', 'desc' => 'Dissolve in water after breakfast.']],
                'specs' => ['Flavor' => 'Mixed Berry', 'Quantity' => '20 Tablets']
            ],
            [
                'id' => 4,
                'slug' => 'sleep-effervescent',
                'title' => 'Sleep & Relax',
                'tagline' => 'Restful Sleep',
                'description' => 'Melatonin and Valerian root for a peaceful night.',
                'long_description' => '<p>Fall asleep faster and wake up refreshed.</p>',
                'price' => 1599,
                'mrp' => 2199,
                'image' => 'remenant-product10.jpg',
                'gallery' => ['remenant-product11.jpg'],
                'rating' => 4.6,
                'reviews' => 842,
                'reviews_count' => 842,
                'theme_color' => 'indigo',
                'highlights' => ['Natural Melatonin', 'Non-Habit Forming', 'Relaxation Support'],
                'benefits' => [
                    (object)['icon' => 'zap', 'title' => 'Immunity Boost', 'desc' => 'Strengthens defenses and boosts natural energy.'],
                    (object)['icon' => 'sparkles', 'title' => 'Skin Glow', 'desc' => 'Supports collagen production for radiant skin.']
                ],
                'ritual' => [(object)['step' => 1, 'title' => 'Nightly Routine', 'desc' => 'Take 30 mins before bed.']],
                'specs' => ['Flavor' => 'Lavender Lemon', 'Quantity' => '20 Tablets']
            ],
            [
                'id' => 5,
                'slug' => 'pricing-architecture',
                'title' => 'Pricing Architecture',
                'tagline' => 'Advanced Wellness',
                'description' => 'Precision formulated for maximum performance and vitality.',
                'long_description' => '<p>Experience the next generation of wellness with our Pricing Architecture series.</p>',
                'price' => 2499,
                'mrp' => 3499,
                'image' => 'remenant-product1.jpg',
                'gallery' => ['remenant-product2.jpg', 'remenant-product3.jpg'],
                'rating' => 4.8,
                'reviews' => 1540,
                'reviews_count' => 1540,
                'theme_color' => 'orange',
                'highlights' => ['High Performance', 'Pure Ingredients', 'Lab Tested'],
                'benefits' => [
                    (object)['icon' => 'zap', 'title' => 'Immunity Boost', 'desc' => 'Strengthens defenses and boosts natural energy.'],
                    (object)['icon' => 'sparkles', 'title' => 'Skin Glow', 'desc' => 'Supports collagen production for radiant skin.']
                ],
                'ritual' => [
                    (object)['step' => 1, 'title' => 'Preparation', 'desc' => 'Drop one tablet into 200ml of water.'],
                    (object)['step' => 2, 'title' => 'Fizz', 'desc' => 'Wait for it to dissolve completely.'],
                    (object)['step' => 3, 'title' => 'Enjoy', 'desc' => 'Drink immediately for maximum absorption.']
                ],
                'specs' => ['Flavor' => 'Orange Burst', 'Quantity' => '20 Tablets', 'Form' => 'Effervescent'],
                'nutrition' => ['Vitamin C' => '1000mg', 'Zinc' => '10mg', 'Vitamin D3' => '400IU']
            ]
        ];

        return collect($data)->map(fn($item) => (object)$item);
    }

    public function getProducts()
    {
        $products = Product::where('status', 'published')->get();
        if ($products->isEmpty()) {
            return $this->getStaticProducts();
        }
        return $products;
    }

    public function reviews($slug)
    {
        $allProducts = $this->getProducts();
        $product = collect($allProducts)->firstWhere('slug', $slug);

        if (!$product) {
            abort(404);
        }

        // Ensure product is an object for view compatibility
        $product = (object)$product;

        return view('public.products.reviews', compact('product'));
    }
}
