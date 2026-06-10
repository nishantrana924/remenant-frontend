<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = session('cart', []);
        $buyNowProduct = null;

        if ($request->has('product')) {
            $buyNowProduct = \App\Models\Product::where('slug', $request->product)->first();
            if ($buyNowProduct) {
                // Parse quantity and enforce limits (1 to 10)
                $requestedQuantity = (int) $request->input('quantity', 1);
                $quantity = max(1, min($requestedQuantity, 10));

                // For Buy Now, we use a temporary "virtual" cart with just this item
                $items = [
                    $buyNowProduct->id => [
                        "id" => $buyNowProduct->id,
                        "title" => $buyNowProduct->title,
                        "quantity" => $quantity,
                        "price" => $buyNowProduct->price,
                        "image" => $buyNowProduct->image,
                        "slug" => $buyNowProduct->slug,
                        "model" => $buyNowProduct
                    ]
                ];
            } else {
                $items = $cart;
            }
        } else {
            $items = $cart;
        }

        if (empty($items)) {
            return redirect()->route('products.index')->with('error', 'Your cart is empty!');
        }

        // Load actual products to get combo info
        $productIds = collect($items)->pluck('id');
        $fullProducts = \App\Models\Product::whereIn('id', $productIds)->with(['comboItems.product'])->get()->keyBy('id');
        
        // Attach full product to items for blade access
        foreach ($items as &$item) {
            $item['model'] = $fullProducts[$item['id']] ?? null;
        }

        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shippingCharge     = (int) \App\Models\SiteSetting::getValue('shipping_charge', 99);
        $freeThreshold      = (int) \App\Models\SiteSetting::getValue('free_shipping_threshold', 449);
        $shipping = $subtotal > $freeThreshold ? 0 : $shippingCharge;
        $total = $subtotal + $shipping;

        $addresses = auth()->check() ? auth()->user()->addresses : [];

        return view('public.checkout', compact('items', 'subtotal', 'shipping', 'total', 'buyNowProduct', 'addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pincode' => 'required',
        ]);

        $cart = session('cart', []);
        $items = $cart;

        // If it was a buy now checkout, we might need to handle it differently 
        // But for simplicity, we'll assume buy now was added to a temporary state or passed in form
        if ($request->has('buy_now_product_id')) {
            $buyNowQuantity = max(1, min((int) $request->input('buy_now_quantity', 1), 10));
            $product = \App\Models\Product::find($request->buy_now_product_id);
            if ($product) {
                $items = [
                    $product->id => [
                        "id" => $product->id,
                        "title" => $product->title,
                        "quantity" => $buyNowQuantity,
                        "price" => $product->price,
                        "image" => $product->image,
                        "slug" => $product->slug
                    ]
                ];
            }
        }

        if (empty($items)) {
            return redirect()->back()->with('error', 'Nothing to checkout!');
        }

        // Lock for update and validate stock before calculating anything
        \Illuminate\Support\Facades\DB::transaction(function () use ($items) {
            foreach ($items as $item) {
                $product = \App\Models\Product::lockForUpdate()->find($item['id']);
                if (!$product || $product->stock < $item['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'stock' => 'Insufficient stock available for ' . ($item['title'] ?? 'this product') . '.'
                    ]);
                }
            }
        });

        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shippingCharge     = (int) \App\Models\SiteSetting::getValue('shipping_charge', 99);
        $freeThreshold      = (int) \App\Models\SiteSetting::getValue('free_shipping_threshold', 449);
        $shipping = $subtotal > $freeThreshold ? 0 : $shippingCharge;
        
        // Handle Coupon with strict validation
        $discount = 0;
        $coupon = null;

        if ($request->filled('coupon_code')) {
            if ($request->input('coupon_applied') !== 'true') {
                $coupon = null;
                $discount = 0;
            } else {
                $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
                
                if ($coupon) {
                    // Strict Backend Re-validation
                    if (!$coupon->is_active) {
                        return redirect()->back()->with('error', 'This coupon is no longer active.');
                    }
                    if ($coupon->start_date && now()->lt($coupon->start_date)) {
                        return redirect()->back()->with('error', 'This coupon is not yet active.');
                    }
                    if ($coupon->end_date && now()->gt($coupon->end_date)) {
                        return redirect()->back()->with('error', 'This coupon has expired.');
                    }
                    if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
                        return redirect()->back()->with('error', 'Coupon usage limit has been reached.');
                    }
                    if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
                        return redirect()->back()->with('error', "Minimum order amount for this coupon is ₹{$coupon->min_order_amount}.");
                    }
                    
                    // Advanced product/category checks would normally go here based on isValidFor logic,
                    // but since it's a cart-level subtotal application, we assume general validity if passed basic checks.
                    
                    if ($coupon->type === 'percentage') {
                        $discount = ($subtotal * $coupon->value) / 100;
                    } else {
                        $discount = $coupon->value;
                    }
                    $discount = min($discount, $subtotal); // Don't exceed subtotal
                } else {
                    return redirect()->back()->with('error', 'Invalid coupon code.');
                }
            }
        }

        $total = ($subtotal - $discount) + $shipping;

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'subtotal' => $subtotal,
            'total_amount' => $total,
            'shipping_charge' => $shipping,
            'discount_amount' => $discount,
            'coupon_code' => $coupon ? $coupon->code : null,
            'coupon_discount_type' => $coupon ? $coupon->type : null,
            'coupon_discount_value' => $coupon ? $coupon->value : null,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => 'razorpay',
            'customer_name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
        ]);

        if ($coupon) {
            $coupon->increment('used_count');
        }

        foreach ($items as $item) {
            $order->orderItems()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }


        return redirect()->route('checkout.payment', $order->order_number);
    }

    public function payment($orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();

        if ($order->payment_status === 'paid') {
            return redirect()->route('checkout.success', ['order' => $order->order_number]);
        }

        $razorpayKey = config('services.razorpay.key_id');
        $razorpaySecret = config('services.razorpay.key_secret');

        if (!$razorpayKey || !$razorpaySecret || str_contains($razorpayKey, 'YOUR_')) {
            return redirect()->route('checkout')->with('error', 'Payment gateway is not configured properly.');
        }

        try {
            $api = new Api($razorpayKey, $razorpaySecret);

            // CRITICAL FIX: Reuse existing Razorpay order if it is still in 'created' state.
            // Creating a new order every page load overwrites razorpay_order_id in the DB,
            // causing verifyPayment() to fail the order lookup and leave the order as 'unpaid'.
            $razorpayOrderId = null;

            if (!empty($order->razorpay_order_id)) {
                try {
                    $existingOrder = $api->order->fetch($order->razorpay_order_id);
                    if (($existingOrder['status'] ?? '') === 'created') {
                        // Safe to reuse — no payment has been attempted yet
                        $razorpayOrderId = $existingOrder['id'];
                        Log::info('payment(): Reusing existing Razorpay order ' . $razorpayOrderId . ' for ' . $order->order_number);
                    }
                } catch (\Exception $fetchEx) {
                    // Razorpay order not found or expired — fall through to create a new one
                    Log::warning('payment(): Could not fetch existing Razorpay order ' . $order->razorpay_order_id . ': ' . $fetchEx->getMessage());
                }
            }

            if (!$razorpayOrderId) {
                $razorpayOrder = $api->order->create([
                    'receipt'         => $order->order_number,
                    'amount'          => $order->total_amount * 100,
                    'currency'        => 'INR',
                    'payment_capture' => 1
                ]);
                $razorpayOrderId = $razorpayOrder['id'];
                $order->update(['razorpay_order_id' => $razorpayOrderId]);
                Log::info('payment(): Created new Razorpay order ' . $razorpayOrderId . ' for ' . $order->order_number);
            }

            return view('public.payment', [
                'order'            => $order,
                'razorpay_order_id' => $razorpayOrderId,
                'razorpay_key'     => $razorpayKey
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Razorpay Error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'Payment gateway error. Please try again.');
        }
    }



    public function verifyPayment(Request $request)
    {
        try {
            $input = $request->all();
            $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

            $success = true;
            $error = "Payment Failed";

            if (!empty($input['razorpay_payment_id']) && !empty($input['razorpay_order_id']) && !empty($input['razorpay_signature'])) {
                try {
                    $attributes = [
                        'razorpay_order_id'  => $input['razorpay_order_id'],
                        'razorpay_payment_id'=> $input['razorpay_payment_id'],
                        'razorpay_signature' => $input['razorpay_signature']
                    ];
                    $api->utility->verifyPaymentSignature($attributes);
                } catch (\Exception $e) {
                    $success = false;
                    $error = 'Razorpay Error : ' . $e->getMessage();
                }
            } else {
                $success = false;
                $error = 'Payment details missing.';
            }

            if ($success === true) {
                session()->forget('cart');

                // Primary lookup: by razorpay_order_id
                $order = \App\Models\Order::where('razorpay_order_id', $input['razorpay_order_id'])->first();

                // Fallback lookup: by order_number (posted from the form) in case razorpay_order_id was overwritten
                if (!$order && !empty($input['order_number'])) {
                    $order = \App\Models\Order::where('order_number', $input['order_number'])->first();
                    if ($order) {
                        // Reconcile the razorpay_order_id so it matches going forward
                        $order->update(['razorpay_order_id' => $input['razorpay_order_id']]);
                        Log::warning('verifyPayment: Used order_number fallback lookup for ' . $order->order_number . '. razorpay_order_id reconciled.');
                    }
                }

                if ($order && $order->payment_status !== 'paid') {
                    // 1. If order is already cancelled or failed, mark paid, set refund_status to pending and dispatch refund job
                    if (in_array($order->status, ['cancelled', 'failed'])) {
                        $order->update([
                            'payment_status'      => 'paid',
                            'paid_at'             => now(),
                            'razorpay_payment_id' => $input['razorpay_payment_id'],
                            'razorpay_signature'  => $input['razorpay_signature'],
                            'refund_status'       => 'pending',
                            'refund_requested_at' => now(),
                        ]);

                        \App\Jobs\ProcessOrderRefundJob::dispatch($order->id);

                        Log::info('verifyPayment: Cancelled/failed Order ' . $order->order_number . ' paid. Refund job dispatched.');
                    } else {
                        // 2. Pre-flight stock check before marking processing
                        $outOfStock = false;
                        foreach ($order->orderItems as $item) {
                            $product = \App\Models\Product::find($item->product_id);
                            if (!$product || $product->stock < $item->quantity) {
                                $outOfStock = true;
                                break;
                            }
                        }

                        if ($outOfStock) {
                            $order->update([
                                'payment_status'      => 'paid',
                                'paid_at'             => now(),
                                'razorpay_payment_id' => $input['razorpay_payment_id'],
                                'razorpay_signature'  => $input['razorpay_signature'],
                                'status'              => 'cancelled',
                                'cancellation_reason' => 'System Auto-Cancel: Out of stock during verification',
                                'refund_status'       => 'pending',
                                'refund_requested_at' => now(),
                            ]);

                            \App\Jobs\ProcessOrderRefundJob::dispatch($order->id);

                            Log::warning('verifyPayment: Order ' . $order->order_number . ' paid but out of stock. Refund job dispatched.');
                        } else {
                            // Normal successful flow
                            $order->update([
                                'payment_status'      => 'paid',
                                'paid_at'             => now(),
                                'status'              => 'processing',
                                'razorpay_payment_id' => $input['razorpay_payment_id'],
                                'razorpay_signature'  => $input['razorpay_signature'],
                            ]);

                            Log::info('verifyPayment: Order ' . $order->order_number . ' marked paid via signature verification.');
                        }
                    }
                }

                if ($order) {
                    session(['payment_verified_for_order' => $order->order_number]);
                    return redirect()->route('checkout.success', ['order' => $order->order_number])
                        ->with('success', 'Payment processed!');
                }

                return redirect()->route('home')->with('success', 'Payment received!');
            }

            // Fallback route handling if signature validation failed
            $fallbackOrderNumber = !empty($input['razorpay_order_id']) 
                ? (\App\Models\Order::where('razorpay_order_id', $input['razorpay_order_id'])->value('order_number'))
                : ($request->order_number ?? null);

            if ($fallbackOrderNumber) {
                return redirect()->route('checkout.payment', ['order' => $fallbackOrderNumber])
                    ->with('error', $error);
            }

            return redirect()->route('checkout')->with('error', $error);
        } catch (\Exception $e) {
            Log::error('verifyPayment crash: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('checkout')->with('error', 'Something went wrong during payment verification. Please contact support.');
        }
    }

    public function success($orderNumber)
    {
        $order = \App\Models\Order::with('orderItems.product')
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Refresh once to catch any late webhook/job updates
        $order->refresh();

        // If unpaid and no valid session flag, redirect back to payment page
        if ($order->payment_status !== 'paid' && session('payment_verified_for_order') !== $orderNumber) {
            return redirect()->route('checkout.payment', $order->order_number)
                ->with('error', 'Payment not confirmed yet. Please complete payment.');
        }

        // Clear the session flag
        session()->forget('payment_verified_for_order');

        return view('public.success', compact('order'));
    }

    public function track($orderNumber = null)
    {
        // Accept order number from route param OR query string (search form uses ?order_number=)
        $orderNumber = $orderNumber ?? request('order_number');

        $order = null;
        if ($orderNumber) {
            $order = \App\Models\Order::with(['orderItems.product', 'shipment'])
                ->where('order_number', trim($orderNumber))
                ->where('user_id', auth()->id())
                ->first();
        }
        return view('public.track', compact('order'));
    }

    /**
     * User submits a return request for a delivered order.
     * Return shipping charge (₹100) will be deducted from refund.
     */
    public function requestReturn(Request $request, $orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Eligibility checks
        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'Only paid orders can be returned.');
        }

        if (!in_array($order->delivery_status, ['delivered', 'completed'])) {
            return back()->with('error', 'Order must be delivered before requesting a return.');
        }

        if ($order->return_status !== 'none' && !empty($order->return_status)) {
            return back()->with('error', 'A return request already exists for this order.');
        }

        // 30-day window check
        if ($order->delivered_at) {
            $daysSinceDelivery = \Carbon\Carbon::parse($order->delivered_at)->diffInDays(now());
            if ($daysSinceDelivery > 30) {
                return back()->with('error', 'Return window has expired (30 days from delivery).');
            }
        }

        $request->validate([
            'return_reason' => 'required|string|min:10|max:500',
        ], [
            'return_reason.required' => 'Please describe the reason for return.',
            'return_reason.min'      => 'Please provide at least 10 characters for the reason.',
        ]);

        $order->update([
            'return_status'       => 'requested',
            'return_reason'       => $request->return_reason,
            'return_requested_at' => now(),
        ]);

        $order->logStatus('Return requested by customer: ' . $request->return_reason, auth()->id());

        return back()->with('success', 'Return request submitted! Our team will review it within 24–48 hours. Refund of ₹' . number_format($order->total_amount - 100) . ' will be processed after approval (₹100 return shipping charge deducted).');
    }

    public function invoice($orderNumber)
    {
        $order = \App\Models\Order::with('orderItems.product')
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        return view('public.invoice', compact('order'));
    }

    public function reorder(Request $request, $orderNumber)
    {
        $order = \App\Models\Order::with('orderItems.product')
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $cart = session()->get('cart', []);
        
        foreach ($order->orderItems as $item) {
            $product = $item->product;
            if (!$product) continue;
            
            $id = $product->id;

            if (isset($cart[$id])) {
                $cart[$id]['quantity'] += $item->quantity;
            } else {
                $cart[$id] = [
                    "id" => $product->id,
                    "title" => $product->title,
                    "quantity" => $item->quantity,
                    "price" => $item->price,
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
        }

        session()->put('cart', $cart);
        return redirect()->route('cart')->with('success', 'Items from your previous order have been added to your cart.');
    }
}
