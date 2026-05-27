@extends('emails.layouts.master')

@section('title', 'Order Confirmed - ' . $order->order_number)

@section('content')
    <h1 class="h1">Order Confirmed!</h1>
    <p class="p">Hi {{ $order->customer_name }},</p>
    <p class="p">Great news! Your order <strong>#{{ $order->order_number }}</strong> has been confirmed. Our warehouse team is already working on packing your items. We will email you the tracking details as soon as they are ready.</p>

    <div class="order-summary">
        <h2 style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">Order Summary</h2>
        
        @foreach($order->orderItems as $item)
        <div style="padding: 15px 0; border-bottom: 1px solid #e2e8f0;">
            <div style="font-size: 13px; font-weight: 900; color: #111827; text-transform: uppercase;">{{ $item->product->title ?? 'Product' }}</div>
            <div style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin-top: 4px;">Qty: {{ $item->quantity }} • Price: ₹{{ number_format($item->price) }}</div>
            
            @if($item->product && in_array($item->product->product_type, ['combo', 'both']) && $item->product->comboItems->count() > 0)
                <div style="margin-top: 8px; padding-left: 10px; border-left: 2px solid #ea5f06;">
                    @foreach($item->product->comboItems as $ci)
                        @if($ci->product)
                        <div style="font-size: 9px; font-weight: 700; color: #64748b; text-transform: uppercase;">• {{ $ci->quantity }}x {{ $ci->product->title }}</div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        @endforeach

        <div class="totals" style="margin-top: 20px;">
            <div class="total-row">
                <span class="total-label">Subtotal</span>
                <span class="total-value">₹{{ number_format($order->total_amount - $order->shipping_charge + $order->discount_amount) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row">
                <span class="total-label" style="color: #10b981;">Discount</span>
                <span class="total-value" style="color: #10b981;">- ₹{{ number_format($order->discount_amount) }}</span>
            </div>
            @endif
            <div class="total-row">
                <span class="total-label">Shipping</span>
                <span class="total-value">{{ $order->shipping_charge == 0 ? 'FREE' : '₹' . number_format($order->shipping_charge) }}</span>
            </div>
            <div class="total-row grand-total" style="padding-top: 15px; border-top: 2px solid #e2e8f0; margin-top: 15px;">
                <span class="total-label" style="color: #111827; font-size: 14px;">Total Paid</span>
                <span class="total-value" style="color: #ea5f06; font-size: 20px;">₹{{ number_format($order->total_amount) }}</span>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <p class="p" style="font-size: 14px;">Keep track of your order anytime:</p>
        <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="btn">Track Order Details</a>
    </div>

    <div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid #f1f5f9;">
        <h3 style="font-size: 10px; font-weight: 900; color: #111827; text-transform: uppercase; margin-bottom: 10px;">Billing & Shipping To</h3>
        <p style="font-size: 12px; font-weight: 700; color: #64748b; line-height: 1.5; text-transform: uppercase;">
            {{ $order->customer_name }}<br>
            {{ $order->address }}<br>
            {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}<br>
            Phone: {{ $order->phone }}
        </p>
    </div>
@endsection
