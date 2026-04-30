<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all active products
     */
    public function getProducts()
    {
        return Product::where('status', 1)->orderBy('id', 'desc')->get();
    }

    /**
     * List all products
     */
    public function index()
    {
        $products = Product::where('status', 1)->paginate(12);
        return view('public.products.index', compact('products'));
    }

    /**
     * Show single product
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->where('status', 1)->firstOrFail();
        
        // Load related products
        $relatedProducts = Product::where('status', 1)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('public.products.show', compact('product', 'relatedProducts'));
    }
}
