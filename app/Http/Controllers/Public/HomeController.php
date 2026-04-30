<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active sliders
        $sliders = Slider::where('status', 1)->orderBy('id', 'desc')->get();
        
        // Get featured products
        $featuredProducts = Product::where('status', 1)
            ->where('is_featured', 1)
            ->limit(8)
            ->get();
            
        // Get all products for the carousel/list
        $products = Product::where('status', 1)->limit(12)->get();
        
        return view('public.home', compact('sliders', 'featuredProducts', 'products'));
    }
}
