@extends('public.layouts.app')

@section('title', 'Track Your Order - Remenant')

@section('content')
<!-- Flush Edge-to-Edge Container -->
<div class="w-full bg-[#F1F3F6] pt-0 dashboard-root">
    <div class="w-full flex flex-col md:flex-row min-h-screen pt-0">
        
        <!-- Mobile Navigation (Top) -->
        <div class="md:hidden bg-white border-b border-slate-200 w-full flex flex-col shrink-0">
            <!-- Mobile Profile Header -->
            <div class="p-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0 overflow-hidden border border-slate-200">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f8fafc&color=64748b" alt="Avatar" class="h-full w-full object-cover">
                    </div>
                    <div class="min-w-0">
                        <h2 class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</h2>
                        <p class="text-xs font-medium text-slate-500 truncate">{{ auth()->user()->email ?? 'Customer Account' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="h-10 w-10 rounded-full flex items-center justify-center text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition-colors">
                        <i data-lucide="log-out" class="h-5 w-5" stroke-width="1.5"></i>
                    </button>
                </form>
            </div>
            
            <!-- Mobile Horizontal Scroll Tabs -->
            <div class="flex overflow-x-auto hide-scrollbar px-4 pt-1 gap-6 border-t border-slate-50">
                <a href="{{ route('my-orders') }}" class="mobile-nav-link whitespace-nowrap pb-3 border-b-2 px-1 text-sm font-medium transition-colors border-transparent text-slate-500 hover:text-slate-700">
                    My Orders
                </a>
                <a href="{{ route('order.track') }}" class="mobile-nav-link whitespace-nowrap pb-3 border-b-2 px-1 text-sm font-medium transition-colors border-orange-500 text-orange-600">
                    Track Order
                </a>
                <a href="{{ route('my-orders', ['tab' => 'profile']) }}" class="mobile-nav-link whitespace-nowrap pb-3 border-b-2 px-1 text-sm font-medium transition-colors border-transparent text-slate-500 hover:text-slate-700">
                    Profile Info
                </a>
                <a href="{{ route('my-orders', ['tab' => 'addresses']) }}" class="mobile-nav-link whitespace-nowrap pb-3 border-b-2 px-1 text-sm font-medium transition-colors border-transparent text-slate-500 hover:text-slate-700">
                    Addresses
                </a>
            </div>
        </div>

        <!-- Sidebar (Premium SaaS Style) -->
        <div class="hidden md:flex w-[260px] bg-white border-r border-slate-200 shrink-0 flex-col">
            <!-- Profile Block -->
            <div class="p-5 flex items-center gap-4 border-b border-slate-100">
                <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0 overflow-hidden border border-slate-200">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f8fafc&color=64748b" alt="Avatar" class="h-full w-full object-cover">
                </div>
                <div class="min-w-0">
                    <h2 class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</h2>
                    <p class="text-xs font-medium text-slate-500 truncate">{{ auth()->user()->email ?? 'Customer Account' }}</p>
                </div>
            </div>

            <!-- Navigation Switcher -->
            <div class="px-4 py-5 space-y-6 pb-2" id="dashboard-nav">
                
                <!-- Group: Shopping -->
                <div class="space-y-1.5">
                    <h3 class="text-xs font-semibold text-slate-400 px-3 mb-2 uppercase tracking-wider">Shopping</h3>
                    <a href="{{ route('my-orders') }}" class="nav-link w-full px-3 py-2.5 flex items-center gap-3 rounded-lg text-slate-600 hover:bg-slate-50 transition-all">
                        <i data-lucide="shopping-bag" class="h-4 w-4" stroke-width="1.5"></i>
                        <span class="text-sm font-medium">My Orders</span>
                    </a>
                    <a href="{{ route('order.track') }}" class="nav-link w-full px-3 py-2.5 flex items-center gap-3 rounded-lg bg-orange-50 text-orange-600 transition-all">
                        <i data-lucide="truck" class="h-4 w-4" stroke-width="1.5"></i>
                        <span class="text-sm font-medium">Track Order</span>
                    </a>
                </div>
                
                <!-- Group: Account -->
                <div class="space-y-1.5">
                    <h3 class="text-xs font-semibold text-slate-400 px-3 mb-2 uppercase tracking-wider">Account</h3>
                    <a href="{{ route('my-orders', ['tab' => 'profile']) }}" class="nav-link w-full px-3 py-2.5 flex items-center gap-3 rounded-lg text-slate-600 hover:bg-slate-50 transition-all">
                        <i data-lucide="user-cog" class="h-4 w-4" stroke-width="1.5"></i>
                        <span class="text-sm font-medium">Profile Info</span>
                    </a>
                    <a href="{{ route('my-orders', ['tab' => 'addresses']) }}" class="nav-link w-full px-3 py-2.5 flex items-center gap-3 rounded-lg text-slate-600 hover:bg-slate-50 transition-all">
                        <i data-lucide="map-pin" class="h-4 w-4" stroke-width="1.5"></i>
                        <span class="text-sm font-medium">Addresses</span>
                    </a>
                </div>

            </div>

            <!-- Sign Out -->
            <div class="px-4 py-2 mt-2 mb-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 text-slate-500 hover:text-rose-600 hover:bg-rose-50 px-3 py-2.5 rounded-lg transition-all w-full text-left">
                        <i data-lucide="log-out" class="h-4 w-4" stroke-width="1.5"></i>
                        <span class="text-sm font-medium">Sign Out</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Area -->
        <div class="flex-1 bg-slate-50/50 min-h-[calc(100vh-80px)] relative min-w-0 p-4 md:p-8 pt-24 md:pt-28">
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
                                <!-- Actions -->
                                @php
                                    $isPaid = $order->payment_status === 'paid';
                                    $isDelivered = $order->delivery_status === 'Delivered';
                                    $isCancelled = in_array(strtolower($order->status), ['cancelled', 'cancellation_requested']) || in_array(strtolower($order->delivery_status), ['cancelled']);
                                    $canCancel = !$isDelivered && !$isCancelled;
                                @endphp
                                
                                <div class="mt-6 flex flex-col gap-3">
                                    @if(!$isPaid && !$isCancelled)
                                        <a href="{{ route('checkout.payment', ['order' => $order->order_number]) }}" class="w-full text-center bg-orange-600 text-white px-6 py-3 rounded-sm text-sm font-bold hover:bg-orange-700 transition-all uppercase tracking-wider shadow-sm">
                                            Pay Now
                                        </a>
                                    @endif

                                    @if($isPaid && !$isCancelled)
                                        <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="w-full flex items-center justify-center gap-2 border border-blue-600 text-blue-600 py-2.5 rounded-sm text-xs font-bold hover:bg-blue-50 transition-all uppercase tracking-wider">
                                            <i data-lucide="download" class="h-3.5 w-3.5"></i> Download Invoice
                                        </a>
                                    @endif
                                    
                                    @if($canCancel)
                                        <button type="button" onclick="openCancelModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $isPaid ? '1' : '0' }}')" class="w-full bg-white border border-rose-200 text-rose-600 px-6 py-2.5 rounded-sm text-sm font-bold hover:bg-rose-50 hover:border-rose-300 transition-all uppercase tracking-wider">Cancel Order</button>
                                    @endif
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
                                            $imagePath = \App\Helpers\ImageHelper::getUrl($item->product->image, 'images/products');
                                        @endphp
                                        <img src="{{ $imagePath }}" alt="{{ $item->product->title }}" class="h-full w-full object-contain">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-bold text-gray-800 truncate">{{ $item->product->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $item->product->subtitle ?? 'Premium Health Supplement' }}</p>
                                        <p class="text-sm font-bold text-gray-800 mt-2">₹{{ number_format($item->price) }} <span class="text-xs text-gray-400 font-normal">x {{ $item->quantity }}</span></p>
                                        
                                        @if($item->product && in_array($item->product->product_type, ['combo', 'both']) && $item->product->comboItems->count() > 0)
                                            <div class="mt-3 p-3 bg-gray-50 rounded-sm border border-dashed border-gray-200">
                                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Bundle Components:</p>
                                                <div class="space-y-1.5">
                                                    @foreach($item->product->comboItems as $ci)
                                                        @if($ci->product)
                                                            <div class="flex items-center gap-2">
                                                                <i data-lucide="check" class="h-3 w-3 text-green-500"></i>
                                                                <span class="text-[10px] font-bold text-gray-600 uppercase">{{ $ci->quantity }}x {{ $ci->product->title }}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Timeline -->
                                <div class="p-6 sm:p-10">
                                    @php
                                        $status = strtolower($order->delivery_status);
                                        $isOrderCancelled = in_array(strtolower($order->status), ['cancelled', 'cancellation_requested']) || $status === 'cancelled';
                                        
                                        if ($isOrderCancelled) {
                                            if (strtolower($order->payment_status) === 'paid') {
                                                $steps = [
                                                    ['label' => 'Order Placed', 'status' => 'placed', 'desc' => $order->created_at->format('d M, Y')],
                                                    ['label' => 'Cancelled', 'status' => 'cancelled', 'desc' => $order->cancellation_reason ?: 'Cancelled by user'],
                                                    ['label' => 'Refund Process', 'status' => 'refund', 'desc' => strtolower($order->refund_status) === 'processed' ? 'Refund successful' : 'Processing Refund']
                                                ];
                                                $currentIndex = 1;
                                                if (in_array(strtolower($order->refund_status), ['pending', 'initiated', 'processed'])) {
                                                    $currentIndex = 2;
                                                }
                                            } else {
                                                $steps = [
                                                    ['label' => 'Order Placed', 'status' => 'placed', 'desc' => $order->created_at->format('d M, Y')],
                                                    ['label' => 'Cancelled', 'status' => 'cancelled', 'desc' => $order->cancellation_reason ?: 'Cancelled by user']
                                                ];
                                                $currentIndex = 1;
                                            }
                                        } else {
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
                                        }
                                    @endphp

                                    <!-- Horizontal Timeline (Desktop) -->
                                    <div class="hidden sm:block relative mb-12">
                                        <div class="absolute top-2.5 left-0 w-full h-1 bg-gray-100 -z-0">
                                            <div class="h-full {{ $isOrderCancelled ? 'bg-red-500' : 'bg-green-500' }} transition-all duration-500" style="width: {{ count($steps) > 1 ? ($currentIndex / (count($steps) - 1)) * 100 : 0 }}%"></div>
                                        </div>
                                        
                                        <div class="flex justify-between relative z-10">
                                            @foreach($steps as $index => $step)
                                                <div class="flex flex-col items-center">
                                                    @php
                                                        $isCompleted = $index <= $currentIndex;
                                                        $isCancelledStep = $isOrderCancelled;
                                                        $bgColor = $isCompleted ? ($isCancelledStep ? 'bg-red-500' : 'bg-green-500') : 'bg-gray-200';
                                                        $icon = $step['status'] === 'cancelled' ? 'x' : 'check';
                                                    @endphp
                                                    <div class="h-6 w-6 rounded-full flex items-center justify-center {{ $bgColor }} text-white border-4 border-white ring-1 ring-gray-100">
                                                        @if($isCompleted)
                                                            <i data-lucide="{{ $icon }}" class="h-3 w-3 stroke-[4px]"></i>
                                                        @endif
                                                    </div>
                                                    <div class="mt-3 text-center">
                                                        <p class="text-xs font-bold {{ $isCompleted ? 'text-gray-800' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                                                        <p class="text-[10px] text-gray-500 mt-1">{{ $step['desc'] }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Vertical Timeline (Mobile) -->
                                    <div class="sm:hidden space-y-8 relative">
                                        <div class="absolute left-3 top-0 bottom-0 w-0.5 bg-gray-100 -z-0">
                                            <div class="w-full {{ $isOrderCancelled ? 'bg-red-500' : 'bg-green-500' }}" style="height: {{ count($steps) > 1 ? ($currentIndex / (count($steps) - 1)) * 100 : 0 }}%"></div>
                                        </div>
                                        
                                        @foreach($steps as $index => $step)
                                            @php
                                                $isCompleted = $index <= $currentIndex;
                                                $isCancelledStep = $isOrderCancelled;
                                                $bgColor = $isCompleted ? ($isCancelledStep ? 'bg-red-500' : 'bg-green-500') : 'bg-gray-200';
                                                $icon = $step['status'] === 'cancelled' ? 'x' : 'check';
                                            @endphp
                                            <div class="flex items-start gap-6 relative z-10">
                                                <div class="h-6 w-6 rounded-full flex-shrink-0 flex items-center justify-center {{ $bgColor }} text-white border-4 border-white ring-1 ring-gray-100">
                                                    @if($isCompleted)
                                                        <i data-lucide="{{ $icon }}" class="h-3 w-3 stroke-[4px]"></i>
                                                    @endif
                                                </div>
                                                <div class="pt-0.5">
                                                    <p class="text-sm font-bold {{ $isCompleted ? 'text-gray-800' : 'text-gray-400' }}">{{ $step['label'] }}</p>
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
                            <p class="text-xs text-gray-500">Contact our support team at <span class="font-bold text-gray-700">support@remenant.in</span> or <span class="font-bold text-gray-700">+91 7567776796</span> for any issues regarding your shipment.</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <a href="mailto:support@remenant.in" class="px-6 py-2 border border-blue-600 text-blue-600 text-sm font-bold rounded-sm hover:bg-blue-50 transition-all">Email Us</a>
                        <a href="tel:+917567776796" class="px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-sm hover:bg-blue-700 transition-all">Call Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openCancelModal(orderId, orderNumber, isPaid) {
        document.getElementById('cancel-modal').classList.remove('hidden');
        document.getElementById('cancel-modal').classList.add('flex');
        
        // Set form action
        const form = document.getElementById('cancel-form');
        form.action = `/orders/${orderId}/cancel`;
        
        document.getElementById('modal-order-number').innerText = orderNumber;
        
        // Show refund notice if paid
        const refundNotice = document.getElementById('refund-notice');
        if (isPaid === '1') {
            refundNotice.classList.remove('hidden');
        } else {
            refundNotice.classList.add('hidden');
        }
    }

    function closeCancelModal() {
        document.getElementById('cancel-modal').classList.add('hidden');
        document.getElementById('cancel-modal').classList.remove('flex');
    }
    
    // Form Validation for checkbox
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.id === 'cancel-form') {
            const checkbox = document.getElementById('confirm-cancel-checkbox');
            if (checkbox && !checkbox.checked) {
                e.preventDefault();
                alert('Please confirm that you want to cancel this order.');
            }
        }
    });
</script>

<!-- Cancel Order Modal -->
<div id="cancel-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden relative">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-slate-900">Cancel Order #<span id="modal-order-number"></span></h3>
            <button type="button" onclick="closeCancelModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        
        <form id="cancel-form" method="POST" action="">
            @csrf
            <div class="p-6 space-y-5">
                <div>
                    <label for="cancellation_reason" class="block text-sm font-medium text-slate-700 mb-1.5">Why are you cancelling?</label>
                    <select name="cancellation_reason" id="cancellation_reason" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option value="">Select a reason...</option>
                        <option value="Changed my mind">I changed my mind</option>
                        <option value="Found a better price elsewhere">Found a better price elsewhere</option>
                        <option value="Ordered by mistake">Ordered by mistake</option>
                        <option value="Delivery is taking too long">Delivery is taking too long</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div id="refund-notice" class="hidden bg-orange-50 border border-orange-100 rounded-xl p-4 flex gap-3">
                    <i data-lucide="info" class="h-5 w-5 text-orange-600 shrink-0"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-orange-800">Refund Information</h4>
                        <p class="text-xs text-orange-700 mt-1 leading-relaxed">Since you have already paid for this order, a refund will be initiated automatically. Please allow 5-7 business days for the amount to reflect in your original payment method.</p>
                    </div>
                </div>

                <label class="flex items-start gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="checkbox" id="confirm-cancel-checkbox" class="mt-0.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                    <span class="text-sm text-slate-700 leading-snug">I understand that cancelling this order is permanent and cannot be undone.</span>
                </label>
            </div>
            
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="button" onclick="closeCancelModal()" class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Go Back</button>
                <button type="submit" class="px-6 py-2.5 bg-rose-600 text-white text-sm font-semibold rounded-xl hover:bg-rose-700 transition-colors shadow-sm shadow-rose-500/20">Confirm Cancellation</button>
            </div>
        </form>
    </div>
</div>
@endpush
@endsection
