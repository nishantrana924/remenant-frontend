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
        // Get active sliders from DB
        $sliders = Slider::where('status', 1)->orderBy('id', 'desc')->get();
        
        $productController = new ProductController();
        $allProducts = $productController->index($request)->getData()['products'];
            
        $featuredProducts = $allProducts->take(8);
        $products = $allProducts;
        $combos = Product::whereIn('product_type', ['combo', 'both'])->where('status', 'published')->get();
        
        // Fetch featured reviews for homepage
        $featuredReviews = \App\Models\Review::with('user')
            ->where('is_featured', true)
            ->where('status', 'approved')
            ->latest()
            ->get();
        
        return view('public.home', compact('sliders', 'featuredProducts', 'products', 'combos', 'featuredReviews'));
    }
}
