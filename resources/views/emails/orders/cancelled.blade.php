@extends('emails.layouts.master')

@section('title', 'Order Cancelled - ' . $order->order_number)

@section('content')
    <h1 class="h1" style="color: #ef4444;">Order Cancelled</h1>
    <p class="p">Hi {{ $order->customer_name }},</p>
    <p class="p">We are writing to confirm that your order <strong>#{{ $order->order_number }}</strong> has been cancelled. If you did not request this cancellation or have questions, please reach out to our support team.</p>

    <div class="order-summary" style="background-color: #fef2f2; border: 1px solid #fca5a5; padding: 25px; margin: 30px 0;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <div style="font-size: 28px;">❌</div>
            <div>
                <h2 style="font-size: 10px; font-weight: 900; color: #b91c1c; text-transform: uppercase; letter-spacing: 2px; margin: 0;">Cancellation Details</h2>
                <p style="font-size: 13px; font-weight: 700; color: #111827; margin: 5px 0 0 0;">
                    Refund Status: 
                    @if($order->payment_method === 'prepaid')
                        Processing Refund
                    @else
                        No Action Required (COD)
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="order-summary">
        <h2 style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">Cancelled Items</h2>
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

    <p class="p" style="font-size: 13px; text-align: center; color: #94a3b8; margin-top: 50px;">If you have any questions or would like to place a new order, contact support at support@remenant.in.</p>
@endsection
