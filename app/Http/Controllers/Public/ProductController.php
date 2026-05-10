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

        // Fetch top 3 approved reviews
        $topReviews = $product->reviews()->where('status', 'approved')
            ->orderBy('is_featured', 'desc')
            ->latest()
            ->take(3)
            ->get();
        
        $totalApproved = $product->reviews()->where('status', 'approved')->count();
        $avgRating = $product->reviews()->where('status', 'approved')->avg('rating') ?: $product->rating;

        return view('public.products.show', compact('product', 'relatedProducts', 'topReviews', 'totalApproved', 'avgRating'));
    }

    /**
     * Preview unsaved product content
     */
    public function preview(Request $request)
    {
        $data = $request->input('content');
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        
        // Ensure all properties used in show.blade.php exist as at least null
        $defaults = [
            'id' => 0,
            'slug' => 'product-preview',
            'title' => 'Product Preview',
            'tagline' => '',
            'description' => '',
            'long_description' => '',
            'price' => 0,
            'mrp' => 0,
            'image' => null,
            'gallery' => [],
            'rating' => 4.8,
            'reviews' => 1240,
            'theme_color' => 'orange',
            'benefits' => [],
            'highlights' => [],
            'ritual' => [],
            'specs' => [],
            'meta_title' => '',
            'meta_description' => '',
            'meta_keywords' => '',
            'benefits_title' => 'Key Benefits',
            'benefits_subtitle' => 'Expertly engineered for you'
        ];

        $data = array_merge($defaults, (array)$data);
        
        // Cast only top-level to object, keep nested as arrays for Blade compatibility
        $product = (object)$data;
        
        // Mock related products
        $relatedProducts = Product::where('status', 'published')->take(4)->get();
        
        return view('public.products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'isPreview' => true
        ]);
    }


    public function getProducts()
    {
        return Product::where('status', 'published')->get();
    }

    public function reviews($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        $reviews = $product->reviews()->where('status', 'approved')->paginate(10);
        $totalCount = $product->reviews()->where('status', 'approved')->count();
        $avgRating = $product->reviews()->where('status', 'approved')->avg('rating') ?: $product->rating;
        
        // Calculate Breakdown
        $breakdown = [];
        for($i=5; $i>=1; $i--) {
            $count = $product->reviews()->where('status', 'approved')->where('rating', $i)->count();
            $breakdown[$i] = $totalCount > 0 ? round(($count / $totalCount) * 100) : 0;
        }

        return view('public.products.reviews', compact('product', 'reviews', 'totalCount', 'avgRating', 'breakdown'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120'
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = \App\Helpers\ImageHelper::upload($image, 'reviews');
                $imagePaths[] = $path;
            }
        }

        \App\Models\Review::create([
            'product_id' => $id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'images' => $imagePaths,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your review has been submitted for moderation.'
        ]);
    }
}
