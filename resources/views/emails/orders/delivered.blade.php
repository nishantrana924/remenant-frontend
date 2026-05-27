@extends('emails.layouts.master')

@section('title', 'Order Delivered - ' . $order->order_number)

@section('content')
    <h1 class="h1" style="color: #10b981;">Order Delivered!</h1>
    <p class="p">Hi {{ $order->customer_name }},</p>
    <p class="p">Awesome! Our delivery partner has confirmed that order <strong>#{{ $order->order_number }}</strong> has been successfully delivered to your address. We hope you love your purchase!</p>

    <div class="order-summary" style="background-color: #ecfdf5; border: 1px solid #a7f3d0; padding: 25px; margin: 30px 0;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 28px;">🎉</div>
            <div>
                <h2 style="font-size: 10px; font-weight: 900; color: #047857; text-transform: uppercase; letter-spacing: 2px; margin: 0;">Successful Delivery</h2>
                <p style="font-size: 13px; font-weight: 700; color: #111827; margin: 5px 0 0 0;">Delivered via {{ $order->courier_name ?? 'Logistics Partner' }}</p>
            </div>
        </div>
    </div>

    <div class="order-summary">
        <h2 style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">Delivered Items</h2>
        @foreach($order->orderItems as $item)
        <div style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 800; color: #111827; text-transform: uppercase;">{{ $item->product->title ?? 'Product' }}</span>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">QTY: {{ $item->quantity }}</div>
            </div>
            <div style="font-size: 12px; font-weight: 800; color: #111827;">₹{{ number_format($item->price * $item->quantity) }}</div>
        </div>
        @endforeach
    </div>

    <div style="text-align: center; margin-top: 40px;">
        <p class="p" style="font-size: 14px;">We'd love to hear your feedback on your shopping experience.</p>
        <a href="{{ route('products.index') }}" class="btn">Shop More Products</a>
    </div>

    <p class="p" style="font-size: 12px; text-align: center; color: #94a3b8; margin-top: 50px;">Thank you for being a part of the Remenant community.</p>
@endsection
