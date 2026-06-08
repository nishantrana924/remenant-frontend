<?php

namespace App\Http\Controllers\Public;
 
use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Product;
use Illuminate\Http\Request;
 
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $ttl = config('cache.homepage_ttl', 3600);

        $sliders = \Illuminate\Support\Facades\Cache::remember('home.sliders', $ttl, function() {
            return Slider::where('status', 1)->orderBy('id', 'desc')->get();
        });
        
        $featuredProducts = \Illuminate\Support\Facades\Cache::remember('home.featured_products', $ttl, function() {
            return Product::with('categories')
                ->whereIn('product_type', ['single', 'both'])
                ->where('status', 'published')
                ->orderByRaw("FIELD(product_type, 'single', 'both')")
                ->latest()
                ->take(8)
                ->get();
        });

        $products = \Illuminate\Support\Facades\Cache::remember('home.products', $ttl, function() {
            return Product::with('categories')
                ->whereIn('product_type', ['single', 'both'])
                ->where('status', 'published')
                ->orderByRaw("FIELD(product_type, 'single', 'both')")
                ->latest()
                ->take(12)
                ->get();
        });

        $combos = \Illuminate\Support\Facades\Cache::remember('home.combos', $ttl, function() {
            return Product::with('categories')
                ->whereIn('product_type', ['combo', 'both'])
                ->where('status', 'published')
                ->orderByRaw("FIELD(product_type, 'combo', 'both')")
                ->take(8)
                ->get();
        });
        
        $featuredReviews = \Illuminate\Support\Facades\Cache::remember('home.featured_reviews', $ttl, function() {
            return \App\Models\Review::with('user')
                ->where('is_featured', true)
                ->where('status', 'approved')
                ->latest()
                ->take(5)
                ->get();
        });
        
        return view('public.home', compact('sliders', 'featuredProducts', 'products', 'combos', 'featuredReviews'));
    }
}
