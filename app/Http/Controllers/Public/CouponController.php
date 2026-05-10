<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.'
            ], 422);
        }

        $product = Product::find($request->product_id);
        
        if (!$coupon->isValidFor($product, $request->amount)) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not valid for this product or order amount.'
            ], 422);
        }

        // Calculate discount
        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = ($request->amount * $coupon->value) / 100;
        } else {
            $discount = $coupon->value;
        }

        // Limit discount to not exceed product price
        $discount = min($discount, $request->amount);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => round($discount, 2),
            'new_total' => round($request->amount - $discount, 2),
            'code' => $coupon->code
        ]);
    }
}
