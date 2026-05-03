<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $productSlug = $request->query('product');
        $product = null;

        if ($productSlug) {
            $productController = new ProductController();
            $allProducts = $productController->getProducts();
            $product = collect($allProducts)->firstWhere('slug', $productSlug);
            
            if (!$product) {
                return redirect()->route('home');
            }
        }

        // For now, if no specific product is provided, we might show a message or redirect to cart
        // But for "Buy It Now", we expect a product.

        return view('public.checkout', compact('product'));
    }
}
