@extends('emails.layouts.master')

@section('title', 'Shipment Booked - ' . $order->order_number)

@section('content')
    <h1 class="h1">Package Ready!</h1>
    <p class="p">Hi {{ $order->customer_name }},</p>
    <p class="p">Your package for order <strong>#{{ $order->order_number }}</strong> has been packed and registered with our shipping partner. It is now awaiting pickup at our warehouse.</p>

    <div class="order-summary" style="background-color: #fff1e8; border: 1px solid #ffddc5; padding: 25px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 28px;">📦</div>
            <div>
                <h2 style="font-size: 10px; font-weight: 900; color: #ea5f06; text-transform: uppercase; letter-spacing: 2px; margin: 0;">Courier & Tracking ID</h2>
                <p style="font-size: 14px; font-weight: 800; color: #111827; margin: 5px 0 0 0;">
                    {{ $order->courier_name ?? 'NimbusPost Logistics' }}<br>
                    AWB/Tracking ID: <span style="color: #ea5f06;">{{ $order->tracking_id ?? 'Pending Assignment' }}</span>
                </p>
            </div>
        </div>
    </div>

    <div style="text-align: center; margin: 40px 0;">
        <p class="p" style="font-size: 14px;">Once the courier scans the package, you will see live shipping updates.</p>
        <a href="{{ $order->tracking_url ?? route('order.track', ['order_number' => $order->order_number]) }}" class="btn">Track Live Shipment</a>
    </div>

    <div class="order-summary">
        <h2 style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">Shipping To</h2>
        <p style="font-size: 12px; font-weight: 700; color: #475569; line-height: 1.5; text-transform: uppercase;">
            {{ $order->customer_name }}<br>
            {{ $order->address }}<br>
            {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}
        </p>
    </div>

    <p class="p" style="font-size: 13px; text-align: center; color: #94a3b8;">If you have any questions or need modifications, feel free to contact us.</p>
@endsection
