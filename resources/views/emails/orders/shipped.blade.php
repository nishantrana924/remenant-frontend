@extends('emails.layouts.master')

@section('title', 'Order Shipped - ' . $order->order_number)

@section('content')
    <h1 class="h1">On Its Way!</h1>
    <p class="p">Great news, {{ $order->customer_name }}!</p>
    <p class="p">Your order <strong>#{{ $order->order_number }}</strong> has been shipped and is currently in transit. Get ready to experience the Remenant standard.</p>

    <div class="order-summary" style="background-color: #fff1e8; border: 1px solid #ffddc5; padding: 25px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 24px;">🚚</div>
            <div>
                <h2 style="font-size: 10px; font-weight: 900; color: #ea5f06; text-transform: uppercase; letter-spacing: 2px; margin: 0;">Tracking Information</h2>
                <p style="font-size: 14px; font-weight: 800; color: #111827; margin: 5px 0 0 0;">{{ $order->courier_name ?? 'Standard Shipping' }} • {{ $order->tracking_id ?? 'Tracking ID Pending' }}</p>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin: 40px 0;">
        <p class="p" style="font-size: 14px;">Use the button below for live status updates on your delivery.</p>
        <a href="{{ $order->tracking_url ?? route('order.track', ['order_number' => $order->order_number]) }}" class="btn">Track Live Status</a>
    </div>

    <div class="order-summary">
        <h2 style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">Shipping Details</h2>
        <p style="font-size: 12px; font-weight: 700; color: #475569; line-height: 1.5; text-transform: uppercase;">
            {{ $order->customer_name }}<br>
            {{ $order->address }}<br>
            {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}
        </p>
    </div>

    <p class="p" style="font-size: 13px; text-align: center; color: #94a3b8;">If you have any questions, simply reply to this email or contact our support team.</p>
@endsection
