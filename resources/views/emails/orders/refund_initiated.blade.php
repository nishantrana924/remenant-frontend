@extends('emails.layouts.master')

@section('title', 'Refund Initiated - ' . $order->order_number)

@section('content')
    <h1 class="h1" style="color: #0ea5e9;">Refund Initiated</h1>
    <p class="p">Hi {{ $order->customer_name }},</p>
    <p class="p">We're sorry for the inconvenience. Your order <strong>#{{ $order->order_number }}</strong> could not be fulfilled, but we have <strong>already initiated your full refund</strong>.</p>

    {{-- Refund Info Box --}}
    <div style="background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 2px solid #3b82f6; border-radius: 20px; padding: 28px; margin: 30px 0;">
        <div style="display: flex; align-items: flex-start; gap: 16px;">
            <div style="font-size: 32px; line-height: 1;">💰</div>
            <div>
                <h2 style="font-size: 11px; font-weight: 900; color: #1d4ed8; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 10px 0;">Refund Details</h2>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="font-size: 12px; color: #6b7280; font-weight: 700; padding: 4px 0;">Refund Amount</td>
                        <td style="font-size: 14px; color: #111827; font-weight: 900; text-align: right; padding: 4px 0;">₹{{ number_format($order->total_amount) }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; color: #6b7280; font-weight: 700; padding: 4px 0;">Order Number</td>
                        <td style="font-size: 12px; color: #111827; font-weight: 800; text-align: right; padding: 4px 0;">#{{ $order->order_number }}</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; color: #6b7280; font-weight: 700; padding: 4px 0;">Payment Method</td>
                        <td style="font-size: 12px; color: #111827; font-weight: 800; text-align: right; padding: 4px 0;">Razorpay</td>
                    </tr>
                    <tr>
                        <td style="font-size: 12px; color: #6b7280; font-weight: 700; padding: 4px 0;">Expected Timeline</td>
                        <td style="font-size: 12px; color: #059669; font-weight: 900; text-align: right; padding: 4px 0;">5–7 Business Days</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- What happens next --}}
    <div style="background-color: #f8fafc; border-radius: 16px; padding: 24px; margin: 20px 0; border: 1px solid #e2e8f0;">
        <h2 style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin: 0 0 16px 0;">What Happens Next?</h2>
        <div style="margin-bottom: 12px; display: flex; gap: 12px; align-items: flex-start;">
            <div style="font-size: 16px; line-height: 1; flex-shrink: 0;">✅</div>
            <p style="font-size: 13px; color: #374151; margin: 0; line-height: 1.5;">The refund has been automatically raised on our end and sent to Razorpay.</p>
        </div>
        <div style="margin-bottom: 12px; display: flex; gap: 12px; align-items: flex-start;">
            <div style="font-size: 16px; line-height: 1; flex-shrink: 0;">🏦</div>
            <p style="font-size: 13px; color: #374151; margin: 0; line-height: 1.5;">Razorpay will process the refund back to your original payment method (Card/UPI/Net Banking) within <strong>5–7 business days</strong>.</p>
        </div>
        <div style="display: flex; gap: 12px; align-items: flex-start;">
            <div style="font-size: 16px; line-height: 1; flex-shrink: 0;">📧</div>
            <p style="font-size: 13px; color: #374151; margin: 0; line-height: 1.5;">If you don't receive your refund within 7 days, please contact us at <a href="mailto:support@remenant.in" style="color: #ea5f06; font-weight: 700;">support@remenant.in</a>.</p>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="order-summary">
        <h2 style="font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px;">Items You Ordered</h2>
        @foreach($order->orderItems as $item)
        <div style="padding: 10px 0; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
            <div>
                <span style="font-size: 12px; font-weight: 800; color: #111827; text-transform: uppercase;">{{ $item->product->title ?? 'Product' }}</span>
                <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">QTY: {{ $item->quantity }}</div>
            </div>
            <div style="font-size: 12px; font-weight: 800; color: #111827;">₹{{ number_format($item->price * $item->quantity) }}</div>
        </div>
        @endforeach
        <div style="display: flex; justify-content: space-between; padding-top: 16px; margin-top: 8px; border-top: 2px solid #e2e8f0;">
            <span style="font-size: 12px; font-weight: 900; color: #111827; text-transform: uppercase;">Refund Total</span>
            <span style="font-size: 16px; font-weight: 900; color: #0ea5e9;">₹{{ number_format($order->total_amount) }}</span>
        </div>
    </div>

    <p class="p" style="font-size: 13px; text-align: center; color: #94a3b8; margin-top: 40px;">
        We apologize for this experience. We are constantly working to improve our service.<br>
        Thank you for your patience. — Team Remenant
    </p>
@endsection
