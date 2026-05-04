@extends('public.layouts.app')

@section('title', 'Order Confirmed - Remenant')

@section('content')
<div class="min-h-screen bg-[#FDFCFB] pt-32 pb-20 px-4">
    <div class="max-w-3xl mx-auto">
        <!-- Success Animation & Header -->
        <div class="text-center mb-12">
            <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 mb-8 animate-bounce">
                <i data-lucide="check-circle-2" class="h-10 w-10"></i>
            </div>
            <h1 class="text-4xl font-black italic tracking-tighter uppercase text-slate-900 mb-2">Booking Confirmed!</h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.3em] text-xs">Order ID: {{ $order->order_number }}</p>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-[3rem] shadow-2xl shadow-emerald-100 ring-1 ring-black/[0.03] overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="flex flex-col sm:flex-row justify-between items-start gap-8 border-b border-slate-100 pb-10 mb-10">
                    <div>
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Shipping To</h3>
                        <p class="text-lg font-black text-slate-900 uppercase tracking-tight">{{ $order->customer_name }}</p>
                        <p class="text-sm text-slate-500 font-bold mt-1">{{ $order->address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                    </div>
                    <div class="sm:text-right">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Payment Status</h3>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest">
                            <i data-lucide="shield-check" class="h-3 w-3"></i>
                            {{ str_replace('_', ' ', $order->payment_status) }}
                        </span>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Items Purchased</h3>
                    @foreach($order->orderItems as $item)
                    <div class="flex gap-6 items-center">
                        <div class="h-16 w-16 shrink-0 rounded-xl bg-slate-50 p-2 ring-1 ring-black/5">
                            <img src="{{ asset('images/products/' . ($item->product->image ?? 'placeholder.jpg')) }}" class="h-full w-full object-contain">
                        </div>
                        <div class="flex-1">
                            <span class="block text-sm font-black text-slate-900 uppercase truncate">{{ $item->product->title ?? 'Product' }}</span>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Qty: {{ $item->quantity }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-black text-slate-900">₹{{ number_format($item->price) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="mt-12 pt-8 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-6">
                    <div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Amount Paid</span>
                        <p class="text-3xl font-black text-slate-900 tracking-tighter mt-1">₹{{ number_format($order->total_amount) }}</p>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-orange-500 text-white font-black uppercase tracking-widest text-[10px] shadow-xl shadow-orange-100 hover:brightness-110 active:scale-95 transition-all">
                            Track Shipment
                        </a>
                        <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl border border-blue-500 text-blue-600 font-black uppercase tracking-widest text-[10px] hover:bg-blue-50 transition-all">
                            Download Invoice
                        </a>
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-slate-100 text-slate-600 font-black uppercase tracking-widest text-[10px] hover:bg-slate-200 transition-all">
                            Back to Store
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="bg-slate-50 p-6 text-center border-t border-slate-100">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-[0.3em]">A confirmation email has been sent to {{ $order->email }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
