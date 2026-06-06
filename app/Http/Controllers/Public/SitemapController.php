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
        $products = Product::where('status', 'published')->latest()->cursor();
        $categories = Category::cursor();

        $staticPages = [
            route('home'),
            route('products.index'),
            route('about'),
            route('contact'),
            route('terms'),
            route('privacy'),
            route('shipping'),
            route('refund'),
        ];

        return response()->view('public.sitemap', [
            'products' => $products,
            'categories' => $categories,
            'staticPages' => $staticPages,
        ])->header('Content-Type', 'text/xml');
    }
}
