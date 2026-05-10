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
        $product = Product::where('slug', $slug)->firstOrFail();

        $relatedProducts = Product::where('slug', '!=', $slug)
            ->where('status', 'published')
            ->take(4)
            ->get();

        return view('public.products.show', compact('product', 'relatedProducts'));
    }

    public function getProducts()
    {
        return Product::where('status', 'published')->get();
    }

    public function reviews($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('public.products.reviews', compact('product'));
    }
}
