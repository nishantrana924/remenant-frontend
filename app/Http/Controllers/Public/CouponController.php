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
        // Sanitize amount: remove commas and currency symbols if present
        if ($request->has('amount')) {
            $amount = str_replace([',', '₹', ' '], '', $request->amount);
            $request->merge(['amount' => $amount]);
        }

        $request->validate([
            'code' => 'required|string',
            'product_id' => 'nullable|exists:products,id',
            'amount' => 'nullable|numeric'
        ]);

        $coupon = Coupon::where('code', $request->code)->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.'], 422);
        }

        $inputAmount = $request->amount ?? 0;

        if (!$coupon->is_active) {
            return response()->json(['success' => false, 'message' => 'This coupon is no longer active.'], 422);
        }

        if ($coupon->min_order_amount && $inputAmount < $coupon->min_order_amount) {
            return response()->json(['success' => false, 'message' => "Min. order amount for this coupon is ₹{$coupon->min_order_amount}."], 422);
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return response()->json(['success' => false, 'message' => 'Coupon usage limit has been reached.'], 422);
        }

        if (auth()->check()) {
            $hasUsed = \App\Models\Order::where('user_id', auth()->id())
                ->where('coupon_code', $coupon->code)
                ->whereNotIn('status', ['cancelled', 'failed'])
                ->exists();

            if ($hasUsed) {
                return response()->json(['success' => false, 'message' => 'You have already used this coupon code.'], 422);
            }
        }

        if ($coupon->start_date && now()->lt($coupon->start_date)) {
            return response()->json(['success' => false, 'message' => 'This coupon is not yet active.'], 422);
        }

        if ($coupon->end_date && now()->gt($coupon->end_date)) {
            return response()->json(['success' => false, 'message' => 'This coupon has expired.'], 422);
        }

        $product = $request->product_id ? Product::find($request->product_id) : null;
        
        if ($product) {
            if (!empty($coupon->product_ids) && !in_array($product->id, $coupon->product_ids)) {
                return response()->json(['success' => false, 'message' => 'This coupon is not valid for this product.'], 422);
            }

            if (!empty($coupon->category_ids)) {
                $productCategoryIds = $product->categories->pluck('id')->toArray();
                if (empty(array_intersect($productCategoryIds, $coupon->category_ids))) {
                    return response()->json(['success' => false, 'message' => 'This coupon is not valid for this product category.'], 422);
                }
            }
        }
        
        if (!$coupon->isValidFor($product, $inputAmount)) {
            return response()->json(['success' => false, 'message' => 'This coupon cannot be applied to this order.'], 422);
        }

        // Calculate discount
        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = ($inputAmount * $coupon->value) / 100;
        } else {
            $discount = $coupon->value;
        }

        // Limit discount to not exceed product price
        $discount = min($discount, $inputAmount);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => round($discount, 2),
            'new_total' => round($inputAmount - $discount, 2),
            'code' => $coupon->code
        ]);
    }
}
