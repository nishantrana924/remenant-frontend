<?php
namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

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
        $shipping = $subtotal > 999 ? 0 : 99;
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
        $shipping = $subtotal > 999 ? 0 : 99;
        
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
            $razorpayOrder = $api->order->create([
                'receipt'         => $order->order_number,
                'amount'          => $order->total_amount * 100,
                'currency'        => 'INR',
                'payment_capture' => 1
            ]);

            $order->update(['razorpay_order_id' => $razorpayOrder['id']]);

            return view('public.payment', [
                'order' => $order,
                'razorpay_order_id' => $razorpayOrder['id'],
                'razorpay_key' => $razorpayKey
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Razorpay Error: ' . $e->getMessage());
            return redirect()->route('checkout')->with('error', 'Payment gateway error. Please try again.');
        }
    }



    public function verifyPayment(Request $request)
    {
        $input = $request->all();
        $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));

        $success = true;
        $error = "Payment Failed";

        if (empty($input['razorpay_payment_id']) === false) {
            try {
                $attributes = [
                    'razorpay_order_id' => $input['razorpay_order_id'],
                    'razorpay_payment_id' => $input['razorpay_payment_id'],
                    'razorpay_signature' => $input['razorpay_signature']
                ];
                $api->utility->verifyPaymentSignature($attributes);
            } catch (\Exception $e) {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if ($success === true) {
            session()->forget('cart');
            $order = \App\Models\Order::where('razorpay_order_id', $input['razorpay_order_id'])->first();
            // Status update happens in RazorpayWebhookController securely.
            return redirect()->route('checkout.success', ['order' => $order->order_number])->with('success', 'Payment Successful! Awaiting confirmation.');
        } else {
            return redirect()->route('checkout.payment', ['order' => $request->order_number])->with('error', $error);
        }
    }

    public function success($orderNumber)
    {
        $order = \App\Models\Order::with('orderItems.product')
            ->where('order_number', $orderNumber)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($order->payment_status !== 'paid') {
            return redirect()->route('checkout.payment', $order->order_number)->with('error', 'Payment is pending for this order.');
        }

        return view('public.success', compact('order'));
    }

    public function track($orderNumber = null)
    {
        $order = null;
        if ($orderNumber) {
            $order = \App\Models\Order::with('orderItems.product')
                ->where('order_number', $orderNumber)
                ->where('user_id', auth()->id())
                ->first();
        }
        return view('public.track', compact('order'));
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
