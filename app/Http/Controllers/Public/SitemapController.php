<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate sitemap.xml
     */
    public function index(): Response
    {
        $products = Product::where('status', 1)->latest()->get();
        $categories = Category::all();

        $staticPages = [
            route('home'),
            route('products.index'),
            route('about'),
            route('contact'),
            route('legal.show', 'terms-and-conditions'),
            route('legal.show', 'privacy-policy'),
            route('legal.show', 'shipping-policy'),
            route('legal.show', 'refund-policy'),
        ];

        return response()->view('public.sitemap', [
            'products' => $products,
            'categories' => $categories,
            'staticPages' => $staticPages,
        ])->header('Content-Type', 'text/xml');
    }
}
