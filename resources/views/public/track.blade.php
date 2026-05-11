@extends('public.layouts.app')

@section('title', 'Track Your Order - Remenant')

@section('content')
<div class="min-h-screen bg-[#f1f3f6] pt-24 pb-12 px-2 sm:px-4">
    <div class="max-w-[1100px] mx-auto">
        
        <!-- Flipkart-style Search Bar -->
        <div class="bg-white p-4 shadow-sm border border-gray-200 rounded-sm mb-4">
            <h1 class="text-base font-bold text-gray-800 mb-3">Track Order</h1>
            <form action="{{ route('order.track') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" name="order_number" value="{{ request('order_number') }}" 
                        placeholder="Enter your Order ID (e.g. ORD-XXXXXXXXXX)" 
                        class="w-full border border-gray-300 px-4 py-2 text-sm rounded-sm focus:outline-none focus:border-blue-500 transition-all shadow-inner">
                </div>
                <button type="submit" class="bg-[#2874f0] text-white px-8 py-2 text-sm font-bold rounded-sm hover:shadow-md transition-all flex items-center justify-center gap-2">
                    <i data-lucide="search" class="h-4 w-4"></i> TRACK
                </button>
            </form>
        </div>

        @if(request('order_number') && !$order)
            <div class="bg-white p-12 text-center border border-gray-200 shadow-sm rounded-sm">
                <div class="h-16 w-16 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-circle" class="h-8 w-8"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Invalid Order ID</h3>
                <p class="text-gray-500 mt-1">We couldn't find any order matching "{{ request('order_number') }}".</p>
                <a href="{{ route('products.index') }}" class="mt-6 inline-block text-blue-600 font-bold hover:underline">Continue Shopping</a>
            </div>
        @endif

        @if($order)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left: Delivery Address & Order Info -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Delivery Address Card -->
                    <div class="bg-white p-6 shadow-sm border border-gray-200 rounded-sm">
                        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-b pb-3">Delivery Address</h2>
                        <div class="text-sm">
                            <p class="font-bold text-gray-800 mb-1">{{ $order->customer_name }}</p>
                            <p class="text-gray-600 leading-relaxed">{{ $order->address }}</p>
                            <p class="text-gray-600">{{ $order->city }}, {{ $order->state }} - {{ $order->pincode }}</p>
                            <div class="mt-4 pt-4 border-t">
                                <p class="text-gray-500 mb-1">Phone Number</p>
                                <p class="font-bold text-gray-800">{{ $order->phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details Card -->
                    <div class="bg-white p-6 shadow-sm border border-gray-200 rounded-sm">
                        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 border-b pb-3">Order Summary</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Order ID</span>
                                <span class="font-bold text-gray-800">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Order Date</span>
                                <span class="font-bold text-gray-800">{{ $order->created_at->format('d M, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Payment Status</span>
                                <span class="font-bold uppercase {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $order->payment_status }}
                                </span>
                            </div>
                            <div class="flex justify-between pt-3 border-t">
                                <span class="text-base font-bold text-gray-800">Total Amount</span>
                                <span class="text-base font-bold text-gray-800">₹{{ number_format($order->total_amount) }}</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="w-full flex items-center justify-center gap-2 border border-blue-600 text-blue-600 py-2.5 rounded-sm text-xs font-bold hover:bg-blue-50 transition-all uppercase tracking-wider">
                                <i data-lucide="download" class="h-3.5 w-3.5"></i> Download Invoice
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Tracking Timeline -->
                <div class="lg:col-span-2 space-y-6">
                    @foreach($order->orderItems as $item)
                    <div class="bg-white shadow-sm border border-gray-200 rounded-sm">
                        <!-- Product Header -->
                        <div class="p-6 flex items-start gap-6 border-b">
                            <div class="h-20 w-20 bg-gray-50 rounded-md border p-1 flex-shrink-0">
                                @php
                                    $imagePath = $item->product->image 
                                        ? (Str::startsWith($item->product->image, 'products/') ? asset('storage/' . $item->product->image) : asset('images/products/' . $item->product->image))
                                        : asset('images/products/placeholder.jpg');
                                @endphp
                                <img src="{{ $imagePath }}" alt="{{ $item->product->title }}" class="h-full w-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-bold text-gray-800 truncate">{{ $item->product->title }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $item->product->subtitle ?? 'Premium Health Supplement' }}</p>
                                <p class="text-sm font-bold text-gray-800 mt-2">₹{{ number_format($item->price) }} <span class="text-xs text-gray-400 font-normal">x {{ $item->quantity }}</span></p>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="p-6 sm:p-10">
                            @php
                                $status = $order->delivery_status;
                                $steps = [
                                    ['label' => 'Order Placed', 'status' => 'placed', 'desc' => $order->created_at->format('d M, Y')],
                                    ['label' => 'Packed', 'status' => 'packed', 'desc' => in_array($status, ['packed', 'shipped', 'delivered']) ? 'Ready for pickup' : 'Pending'],
                                    ['label' => 'Shipped', 'status' => 'shipped', 'desc' => in_array($status, ['shipped', 'delivered']) ? ($order->courier_name ?? 'In Transit') : 'Awaiting dispatch'],
                                    ['label' => 'Delivered', 'status' => 'delivered', 'desc' => ($status === 'delivered') ? 'Delivered successfully' : 'Expected delivery soon']
                                ];
                                
                                $currentIndex = 0;
                                if($status === 'packed') $currentIndex = 1;
                                if($status === 'shipped') $currentIndex = 2;
                                if($status === 'delivered') $currentIndex = 3;
                            @endphp

                            <!-- Horizontal Timeline (Desktop) -->
                            <div class="hidden sm:block relative mb-12">
                                <div class="absolute top-2.5 left-0 w-full h-1 bg-gray-100 -z-0">
                                    <div class="h-full bg-green-500 transition-all duration-500" style="width: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%"></div>
                                </div>
                                
                                <div class="flex justify-between relative z-10">
                                    @foreach($steps as $index => $step)
                                        <div class="flex flex-col items-center">
                                            <div class="h-6 w-6 rounded-full flex items-center justify-center {{ $index <= $currentIndex ? 'bg-green-500 text-white' : 'bg-gray-200 text-white' }} border-4 border-white ring-1 ring-gray-100">
                                                @if($index <= $currentIndex)
                                                    <i data-lucide="check" class="h-3 w-3 stroke-[4px]"></i>
                                                @endif
                                            </div>
                                            <div class="mt-3 text-center">
                                                <p class="text-xs font-bold {{ $index <= $currentIndex ? 'text-gray-800' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                                                <p class="text-[10px] text-gray-500 mt-1">{{ $step['desc'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Vertical Timeline (Mobile) -->
                            <div class="sm:hidden space-y-8 relative">
                                <div class="absolute left-3 top-0 bottom-0 w-0.5 bg-gray-100 -z-0">
                                    <div class="w-full bg-green-500" style="height: {{ ($currentIndex / (count($steps) - 1)) * 100 }}%"></div>
                                </div>
                                
                                @foreach($steps as $index => $step)
                                    <div class="flex items-start gap-6 relative z-10">
                                        <div class="h-6 w-6 rounded-full flex-shrink-0 flex items-center justify-center {{ $index <= $currentIndex ? 'bg-green-500 text-white' : 'bg-gray-200 text-white' }} border-4 border-white ring-1 ring-gray-100">
                                            @if($index <= $currentIndex)
                                                <i data-lucide="check" class="h-3 w-3 stroke-[4px]"></i>
                                            @endif
                                        </div>
                                        <div class="pt-0.5">
                                            <p class="text-sm font-bold {{ $index <= $currentIndex ? 'text-gray-800' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $step['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Courier / Live Tracking -->
                        @if($order->tracking_id || $order->tracking_url)
                        <div class="bg-blue-50/50 p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-3">
                                <i data-lucide="truck" class="h-5 w-5 text-blue-500"></i>
                                <div>
                                    <p class="text-xs font-bold text-blue-700">Track via {{ $order->courier_name ?? 'Courier Partner' }}</p>
                                    <p class="text-[11px] text-blue-600 mt-0.5">AWB: {{ $order->tracking_id }}</p>
                                </div>
                            </div>
                            @if($order->tracking_url)
                            <a href="{{ $order->tracking_url }}" target="_blank" class="text-blue-600 text-sm font-bold hover:underline flex items-center gap-1">
                                View Details <i data-lucide="external-link" class="h-3 w-3"></i>
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Help Section -->
        <div class="mt-12 bg-white p-6 shadow-sm border border-gray-200 rounded-sm flex flex-col sm:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center">
                    <i data-lucide="help-circle" class="h-6 w-6"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-800">Need help with your order?</h4>
                    <p class="text-xs text-gray-500">Contact our support team for any issues regarding your shipment.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <a href="mailto:help@remenant.com" class="px-6 py-2 border border-blue-600 text-blue-600 text-sm font-bold rounded-sm hover:bg-blue-50 transition-all">Email Us</a>
                <a href="#" class="px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-sm hover:bg-blue-700 transition-all">Call Support</a>
            </div>
        </div>
    </div>
</div>
@endsection
