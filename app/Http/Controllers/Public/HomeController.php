<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active sliders from DB
        $sliders = Slider::where('status', 1)->orderBy('id', 'desc')->get();
        
        $productController = new ProductController();
        $allProducts = $productController->index()->getData()['products'];
            
        $featuredProducts = $allProducts->take(8);
        $products = $allProducts;
        
        return view('public.home', compact('sliders', 'featuredProducts', 'products'));
    }
}
