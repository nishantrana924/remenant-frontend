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
                // For Buy Now, we use a temporary "virtual" cart with just this item
                $items = [
                    $buyNowProduct->id => [
                        "id" => $buyNowProduct->id,
                        "title" => $buyNowProduct->title,
                        "quantity" => 1,
                        "price" => $buyNowProduct->price,
                        "image" => $buyNowProduct->image,
                        "slug" => $buyNowProduct->slug
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
            $product = \App\Models\Product::find($request->buy_now_product_id);
            $items = [
                $product->id => [
                    "id" => $product->id,
                    "title" => $product->title,
                    "quantity" => 1,
                    "price" => $product->price,
                    "image" => $product->image,
                    "slug" => $product->slug
                ]
            ];
        }

        if (empty($items)) {
            return redirect()->back()->with('error', 'Nothing to checkout!');
        }

        $subtotal = collect($items)->sum(fn($item) => $item['price'] * $item['quantity']);
        $shipping = $subtotal > 999 ? 0 : 99;
        
        // Handle Coupon
        $discount = 0;
        if ($request->filled('coupon_code')) {
            $coupon = \App\Models\Coupon::where('code', $request->coupon_code)->first();
            if ($coupon) {
                if ($coupon->type === 'percentage') {
                    $discount = ($subtotal * $coupon->value) / 100;
                } else {
                    $discount = $coupon->value;
                }
                $discount = min($discount, $subtotal); // Don't exceed subtotal
            }
        }

        $total = ($subtotal - $discount) + $shipping;

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => $total,
            'shipping_charge' => $shipping,
            'discount_amount' => $discount,
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'payment_method' => $request->payment_method, // prepaid or cod
            'customer_name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pincode' => $request->pincode,
        ]);

        foreach ($items as $item) {
            $order->orderItems()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Clear cart
        if (!$request->has('buy_now_product_id')) {
            session()->forget('cart');
        }

        // If COD, go straight to success
        if ($order->payment_method === 'cod') {
            $order->update(['status' => 'processing']);
            return redirect()->route('checkout.success', ['order' => $order->order_number])->with('success', 'Order placed successfully! (COD)');
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

        // REAL MODE (Only if keys are present)
        if ($razorpayKey && $razorpaySecret && !str_contains($razorpayKey, 'YOUR_')) {
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
                // If real API fails, fallback to sandbox/mock for user testing
                \Illuminate\Support\Facades\Log::error('Razorpay Error: ' . $e->getMessage());
            }
        }

        // SANDBOX / MOCK MODE
        return view('public.payment', [
            'order' => $order,
            'is_sandbox' => true
        ]);
    }

    public function mockPayment(Request $request, $orderNumber)
    {
        $order = \App\Models\Order::where('order_number', $orderNumber)->firstOrFail();
        $order->update([
            'payment_status' => 'paid',
            'razorpay_payment_id' => 'pay_mock_' . Str::random(14),
            'status' => 'processing'
        ]);

        return redirect()->route('checkout.success', ['order' => $order->order_number])->with('success', 'Payment Successful (Sandbox Mode)!');
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
            $order = \App\Models\Order::where('razorpay_order_id', $input['razorpay_order_id'])->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'paid',
                    'razorpay_payment_id' => $input['razorpay_payment_id'],
                    'status' => 'processing'
                ]);
            }
            return redirect()->route('checkout.success', ['order' => $order->order_number])->with('success', 'Payment Successful!');
        } else {
            return redirect()->route('checkout.payment', ['order' => $request->order_number])->with('error', $error);
        }
    }

    public function success($orderNumber)
    {
        $order = \App\Models\Order::with('orderItems.product')->where('order_number', $orderNumber)->firstOrFail();
        return view('public.success', compact('order'));
    }

    public function track($orderNumber = null)
    {
        $order = null;
        if ($orderNumber) {
            $order = \App\Models\Order::with('orderItems.product')->where('order_number', $orderNumber)->first();
        }
        return view('public.track', compact('order'));
    }

    public function invoice($orderNumber)
    {
        $order = \App\Models\Order::with('orderItems.product')->where('order_number', $orderNumber)->firstOrFail();
        return view('public.invoice', compact('order'));
    }
}
