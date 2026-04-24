<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $productController = new ProductController();
        $products = $productController->getProducts();
        
        return view('public.home', compact('products'));
    }
}
