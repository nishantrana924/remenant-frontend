<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('public.cart', compact('cart'));
    }

    public function add(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "id" => $product->id,
                "title" => $product->title,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image,
                "slug" => $product->slug,
                "subtitle" => "Remenant Health",
                "details" => $product->category_name ?? 'Health Supplement',
                "mrp" => ($product->mrp > 0) ? $product->mrp : ($product->price * 1.5),
                "rating" => 4.8,
                "reviews" => rand(50, 200),
                "delivery" => date('l, M d', strtotime('+3 days'))
            ];
        }

        session()->put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => count($cart)
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart!');
    }

    public function update(Request $request)
    {
        if ($request->id && $request->quantity) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                $cart[$request->id]["quantity"] = (int) $request->quantity;
                session()->put('cart', $cart);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated',
                    'item_total' => number_format($cart[$request->id]["price"] * $cart[$request->id]["quantity"]),
                    'totals' => $this->getCartTotals()
                ]);
            }
        }
        return response()->json(['success' => false], 400);
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);

                return response()->json([
                    'success' => true,
                    'message' => 'Item removed',
                    'totals' => $this->getCartTotals()
                ]);
            }
        }
        return response()->json(['success' => false], 400);
    }

    private function getCartTotals()
    {
        $cart = session()->get('cart', []);
        $totalPrice = 0;
        $totalMrp = 0;
        
        foreach ($cart as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
            $totalMrp += ($item['mrp'] ?? $item['price']) * $item['quantity'];
        }
        
        $totalDiscount = $totalMrp - $totalPrice;
        
        return [
            'subtotal' => number_format($totalPrice),
            'total' => number_format($totalPrice),
            'discount' => number_format($totalDiscount),
            'count' => count($cart)
        ];
    }
}
