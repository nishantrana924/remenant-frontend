@extends('emails.layouts.master')

@section('title', 'Payment Successful - Receipt for ' . $order->order_number)

@section('content')
    <div style="text-align: center; margin-bottom: 30px;">
        <div style="display: inline-block; background-color: #10b981; color: #ffffff; width: 60px; height: 60px; border-radius: 50%; line-height: 60px; font-size: 30px; margin-bottom: 15px;">✓</div>
        <h1 class="h1" style="color: #10b981;">Payment Successful!</h1>
        <p class="p" style="font-size: 16px; font-weight: bold; color: #111827;">Amount Paid: ₹{{ number_format($order->total_amount) }}</p>
    </div>

    <p class="p">Hi {{ $order->customer_name }},</p>
    <p class="p">We have successfully received your payment for order <strong>#{{ $order->order_number }}</strong>. Below is your payment slip and bill details. Your order is now being processed.</p>

    <div class="order-summary" style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 25px; margin: 30px 0; border-radius: 8px;">
        <h2 style="font-size: 12px; font-weight: 900; color: #475569; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; border-bottom: 1px solid #cbd5e1; padding-bottom: 10px;">Payment Slip / Bill</h2>
        
        <div style="margin-bottom: 15px;">
            <p style="font-size: 13px; margin: 5px 0;"><strong style="color: #111827;">Order Number:</strong> #{{ $order->order_number }}</p>
            <p style="font-size: 13px; margin: 5px 0;"><strong style="color: #111827;">Transaction ID:</strong> {{ $order->razorpay_payment_id ?? 'N/A' }}</p>
            <p style="font-size: 13px; margin: 5px 0;"><strong style="color: #111827;">Date:</strong> {{ now()->format('d M, Y h:i A') }}</p>
            <p style="font-size: 13px; margin: 5px 0;"><strong style="color: #111827;">Payment Method:</strong> Prepaid (Online)</p>
        </div>

        <h3 style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 10px 0;">Item Details</h3>
        @foreach($order->orderItems as $item)
        <div style="padding: 10px 0; border-bottom: 1px dashed #cbd5e1; display: flex; justify-content: space-between;">
            <div>
                <span style="font-size: 13px; font-weight: 700; color: #111827;">{{ $item->product->title ?? 'Product' }}</span>
                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Qty: {{ $item->quantity }}</div>
            </div>
            <div style="font-size: 13px; font-weight: 700; color: #111827;">₹{{ number_format($item->price * $item->quantity) }}</div>
        </div>
        @endforeach

        <div class="totals" style="margin-top: 20px;">
            <div class="total-row" style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 13px;">
                <span class="total-label" style="color: #475569;">Subtotal</span>
                <span class="total-value">₹{{ number_format($order->subtotal) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row" style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 13px;">
                <span class="total-label" style="color: #10b981;">Discount</span>
                <span class="total-value" style="color: #10b981;">- ₹{{ number_format($order->discount_amount) }}</span>
            </div>
            @endif
            <div class="total-row" style="display: flex; justify-content: space-between; padding: 5px 0; font-size: 13px;">
                <span class="total-label" style="color: #475569;">Shipping</span>
                <span class="total-value">{{ $order->shipping_charge == 0 ? 'FREE' : '₹' . number_format($order->shipping_charge) }}</span>
            </div>
            <div class="total-row grand-total" style="display: flex; justify-content: space-between; padding-top: 15px; border-top: 2px solid #94a3b8; margin-top: 15px;">
                <span class="total-label" style="color: #111827; font-size: 15px; font-weight: 900;">Total Paid</span>
                <span class="total-value" style="color: #10b981; font-size: 18px; font-weight: 900;">₹{{ number_format($order->total_amount) }}</span>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="btn">View Order Status</a>
    </div>
@endsection
