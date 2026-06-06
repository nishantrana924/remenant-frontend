@extends('public.layouts.app')

@section('title', 'My Account - Remenant')

@section('content')
<!-- Flush Edge-to-Edge Container -->
<div class="w-full bg-[#F1F3F6] pt-0 dashboard-root">
    <div class="w-full flex flex-col md:flex-row md:items-start min-h-screen pt-0">
        
        <!-- Sidebar (Premium SaaS Style) -->
        <div class="w-full md:w-[260px] bg-white border-b md:border-b-0 md:border-r border-slate-200 shrink-0 md:min-h-[calc(100vh-80px)] md:h-[calc(100vh-80px)] md:sticky md:top-[80px] overflow-y-auto flex flex-col">
            <!-- Profile Block -->
            <div class="p-6 flex items-center gap-4 border-b border-slate-100">
                <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0 overflow-hidden border border-slate-200">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f8fafc&color=64748b" alt="Avatar" class="h-full w-full object-cover">
                </div>
                <div class="min-w-0">
                    <h2 class="text-sm font-semibold text-slate-900 truncate">{{ auth()->user()->name }}</h2>
                    <p class="text-xs font-medium text-slate-500 truncate">{{ auth()->user()->email ?? 'Customer Account' }}</p>
                </div>
            </div>

            <!-- Navigation Switcher -->
            <div class="flex-1 px-4 py-6 space-y-8" id="dashboard-nav">
                
                <!-- Group: Shopping -->
                <div class="space-y-2">
                    <h3 class="text-xs font-semibold text-slate-400 px-3 mb-3 uppercase tracking-wider">Shopping</h3>
                    <button onclick="switchTab('orders', this)" class="nav-link w-full px-3 py-2.5 flex items-center gap-3 rounded-lg {{ $activeTab === 'orders' ? 'active bg-orange-50 text-orange-600' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                        <i data-lucide="shopping-bag" class="h-4 w-4" stroke-width="1.5"></i>
                        <span class="text-sm font-medium">My Orders</span>
                    </button>
                </div>
                
                <!-- Group: Account -->
                <div class="space-y-2">
                    <h3 class="text-xs font-semibold text-slate-400 px-3 mb-3 uppercase tracking-wider">Account</h3>
                    <button onclick="switchTab('profile', this)" class="nav-link w-full px-3 py-2.5 flex items-center gap-3 rounded-lg {{ $activeTab === 'profile' ? 'active bg-orange-50 text-orange-600' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                        <i data-lucide="user-cog" class="h-4 w-4" stroke-width="1.5"></i>
                        <span class="text-sm font-medium">Profile Info</span>
                    </button>
                    <button onclick="switchTab('addresses', this)" class="nav-link w-full px-3 py-2.5 flex items-center gap-3 rounded-lg {{ $activeTab === 'addresses' ? 'active bg-orange-50 text-orange-600' : 'text-slate-600 hover:bg-slate-50' }} transition-all">
                        <i data-lucide="map-pin" class="h-4 w-4" stroke-width="1.5"></i>
                        <span class="text-sm font-medium">Addresses</span>
                    </button>
                </div>

            </div>

            <!-- Sign Out -->
            <div class="p-6 border-t border-slate-100">
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
        <div class="flex-1 bg-white min-h-[calc(100vh-80px)] relative min-w-0">
            
            <!-- SECTION: ORDERS -->
            <div id="section-orders" class="tab-section {{ $activeTab !== 'orders' ? 'hidden' : '' }} transition-all duration-300 bg-slate-50/50 min-h-screen pb-12">
                <!-- Top KPI Dashboard -->
                <div class="px-4 sm:px-8 pt-10 pb-6 border-b border-slate-200/60 bg-white">
                    <h3 class="text-2xl sm:text-3xl font-semibold tracking-tight text-slate-900 mb-8">My Orders</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center shrink-0">
                                <i data-lucide="shopping-bag" class="h-5 w-5 text-slate-500" stroke-width="1.5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500">Total Orders</p>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <span class="text-2xl font-semibold text-slate-900">{{ $orderStats['total'] ?? 0 }}</span>
                                    <span class="text-xs font-medium text-slate-400">Lifetime</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center shrink-0">
                                <i data-lucide="package" class="h-5 w-5 text-emerald-500" stroke-width="1.5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500">Active</p>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <span class="text-2xl font-semibold text-slate-900">{{ $orderStats['active'] ?? 0 }}</span>
                                    <span class="text-xs font-medium text-slate-400">In Progress</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="h-12 w-12 rounded-full bg-blue-50 flex items-center justify-center shrink-0">
                                <i data-lucide="package-check" class="h-5 w-5 text-blue-500" stroke-width="1.5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500">Delivered</p>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <span class="text-2xl font-semibold text-slate-900">{{ $orderStats['delivered'] ?? 0 }}</span>
                                    <span class="text-xs font-medium text-slate-400">History</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4 hover:shadow-md transition-shadow">
                            <div class="h-12 w-12 rounded-full bg-orange-50 flex items-center justify-center shrink-0">
                                <i data-lucide="clock" class="h-5 w-5 text-orange-500" stroke-width="1.5"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-500">Pending</p>
                                <div class="flex items-baseline gap-2 mt-0.5">
                                    <span class="text-2xl font-semibold text-slate-900">{{ $orderStats['pending'] ?? 0 }}</span>
                                    <span class="text-xs font-medium text-slate-400">Action Required</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="px-4 sm:px-8 max-w-5xl mx-auto space-y-8 mt-8">
                    @forelse($orders as $order)
                        @php
                            $isPaid = $order->payment_status === 'paid';
                            $isFailed = $order->payment_status === 'failed';
                            $isDelivered = strtolower($order->delivery_status) === 'delivered';
                            $isShipped = strtolower($order->delivery_status) === 'shipped';
                            $canCancel = !in_array(strtolower($order->delivery_status ?? ''), ['shipped', 'out_for_delivery', 'delivered', 'returned']) && !in_array(strtolower($order->status), ['shipped', 'out_for_delivery', 'delivered', 'returned', 'cancelled', 'cancellation_requested']);
                            
                            $firstItem = $order->orderItems->first();
                            $additionalCount = $order->orderItems->count() - 1;
                            $productImage = $firstItem && $firstItem->product ? \App\Helpers\ImageHelper::getUrl($firstItem->product->image, 'images/products') : 'https://ui-avatars.com/api/?name=P&background=f8fafc&color=64748b';
                            $productName = $firstItem && $firstItem->product ? $firstItem->product->title : 'Unknown Product';
                            
                            // Determine Border & Background Classes
                            if($isFailed) {
                                $cardClasses = "border-rose-100 bg-white";
                                $badgeClass = "bg-rose-50 text-rose-600 border border-rose-100";
                                $badgeIcon = "x-circle";
                                $badgeText = "Payment Failed";
                            } elseif(!$isPaid) {
                                $cardClasses = "border-orange-200 bg-white shadow-sm shadow-orange-500/5";
                                $badgeClass = "bg-orange-50 text-orange-600 border border-orange-100";
                                $badgeIcon = "alert-circle";
                                $badgeText = "Pending Payment";
                            } else {
                                $cardClasses = "border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow";
                                $badgeClass = "bg-emerald-50 text-emerald-600 border border-emerald-100";
                                $badgeIcon = "check-circle-2";
                                $badgeText = "Confirmed";
                            }
                        @endphp
                        
                        <!-- Order Card -->
                        <div class="rounded-2xl border {{ $cardClasses }} overflow-hidden">
                            <!-- Header -->
                            <div class="bg-slate-50/50 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 gap-4">
                                <div class="flex flex-wrap items-center gap-4 md:gap-8 text-sm">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-500 text-xs">Order Placed</span>
                                        <span class="font-semibold text-slate-900">{{ $order->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-500 text-xs">Total Amount</span>
                                        <span class="font-semibold text-slate-900">₹{{ number_format($order->total_amount) }}</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-slate-500 text-xs">Order Number</span>
                                        <span class="font-semibold text-slate-900">#{{ $order->order_number }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <span class="px-3 py-1 rounded-md text-xs font-semibold flex items-center gap-1.5 {{ $badgeClass }}">
                                        <i data-lucide="{{ $badgeIcon }}" class="h-3.5 w-3.5" stroke-width="2"></i> {{ $badgeText }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Body -->
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row gap-6">
                                    <!-- Image -->
                                    <div class="shrink-0 h-24 w-24 bg-slate-50 rounded-xl overflow-hidden border border-slate-100">
                                        <img src="{{ $productImage }}" alt="{{ $productName }}" class="h-full w-full object-cover">
                                    </div>
                                    
                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <h4 class="text-base font-semibold text-slate-900 leading-tight">{{ $productName }}</h4>
                                        <p class="text-sm font-normal text-slate-500 mt-1">Qty: {{ $firstItem->quantity ?? 1 }}</p>
                                        @if($additionalCount > 0)
                                            <p class="text-xs font-medium text-slate-600 mt-2 bg-slate-50 inline-block px-2.5 py-1 rounded-md border border-slate-200">+ {{ $additionalCount }} More Items</p>
                                        @endif
                                        <p class="text-sm font-medium text-slate-800 mt-4">
                                            @if($isDelivered)
                                                <span class="flex items-center gap-1.5 text-slate-600"><i data-lucide="package-check" class="h-4 w-4 text-emerald-500"></i> Delivered on {{ \Carbon\Carbon::parse($order->updated_at)->format('d M Y') }}</span>
                                            @elseif($isPaid)
                                                Expected Delivery: <span class="font-semibold text-emerald-600">{{ \Carbon\Carbon::parse($order->created_at)->addDays(3)->format('d M Y') }}</span>
                                            @else
                                                <span class="text-orange-600">Awaiting Payment Completion</span>
                                            @endif
                                        </p>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="shrink-0 flex flex-col gap-3 w-full md:w-48 mt-4 md:mt-0">
                                        @if($isPaid)
                                            <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="w-full text-center bg-orange-600 text-white px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-orange-700 hover:shadow-lg shadow-orange-500/20 transition-all">Track Order</a>
                                            <form action="{{ route('order.reorder', ['order' => $order->order_number]) }}" method="POST" class="w-full">
                                                @csrf
                                                <button type="submit" class="w-full bg-white border border-slate-200 text-slate-700 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-50 hover:border-slate-300 transition-all">Buy Again</button>
                                            </form>
                                        @else
                                            <a href="{{ route('checkout.payment', ['order' => $order->order_number]) }}" class="w-full text-center bg-orange-600 text-white px-6 py-3 rounded-xl text-sm font-semibold hover:bg-orange-700 hover:shadow-lg shadow-orange-500/20 transition-all">Complete Payment</a>
                                        @endif
                                        
                                        @if($canCancel)
                                            <button type="button" onclick="openCancelModal('{{ $order->id }}', '{{ $order->order_number }}', '{{ $isPaid ? '1' : '0' }}')" class="w-full bg-white border border-slate-200 text-slate-600 px-6 py-2.5 rounded-xl text-sm font-medium hover:bg-slate-50 hover:text-rose-600 hover:border-rose-200 transition-all">Cancel Order</button>
                                        @endif
                                        <div class="flex items-center justify-between mt-1 px-1">
                                            <a href="{{ route('order.invoice', ['order' => $order->order_number]) }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                                                <i data-lucide="download" class="h-3.5 w-3.5"></i> Invoice
                                            </a>
                                            <a href="https://wa.me/919876543210?text=Help with Order #{{ $order->order_number }}" target="_blank" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                                                <i data-lucide="life-buoy" class="h-3.5 w-3.5"></i> Help
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- Empty State -->
                        <div class="py-20 px-6 text-center border border-dashed border-slate-200 rounded-2xl bg-white shadow-sm">
                            <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-slate-100">
                                <i data-lucide="shopping-bag" class="h-8 w-8 text-slate-400" stroke-width="1.5"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 mb-2">No Orders Yet</h3>
                            <p class="text-sm font-normal text-slate-500 mb-8 max-w-sm mx-auto">You haven't placed any orders. Discover our premium collection and start your journey.</p>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center bg-orange-600 text-white px-8 py-3 rounded-xl text-sm font-medium hover:bg-orange-700 hover:shadow-lg shadow-orange-500/20 transition-all">Browse Products</a>
                        </div>
                    @endforelse
                    
                    <!-- Pagination -->
                    @if(method_exists($orders, 'links') && $orders->hasPages())
                    <div class="mt-12">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                    @endif

                    @if($cancelledOrders->count() > 0)
                        <div class="mt-16 mb-8 flex items-center justify-between">
                            <h3 class="text-xl sm:text-2xl font-semibold tracking-tight text-slate-900">Cancelled Orders</h3>
                        </div>
                        
                        <div class="space-y-6">
                            @foreach($cancelledOrders as $order)
                                @php
                                    $firstItem = $order->orderItems->first();
                                    $additionalCount = $order->orderItems->count() - 1;
                                    $productImage = $firstItem && $firstItem->product ? \App\Helpers\ImageHelper::getUrl($firstItem->product->image, 'products') : 'https://ui-avatars.com/api/?name=P&background=f8fafc&color=64748b';
                                    $productName = $firstItem && $firstItem->product ? $firstItem->product->title : 'Unknown Product';
                                    
                                    $isPendingReview = $order->status === 'cancellation_requested';
                                    $badgeText = $isPendingReview ? 'Cancellation Pending' : 'Cancelled';
                                @endphp
                                
                                <div class="rounded-2xl border border-rose-100 bg-white overflow-hidden opacity-80 hover:opacity-100 transition-opacity">
                                    <!-- Header -->
                                    <div class="bg-slate-50/50 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 gap-4">
                                        <div class="flex flex-wrap items-center gap-4 md:gap-8 text-sm">
                                            <div class="flex flex-col">
                                                <span class="font-medium text-slate-500 text-xs">Cancelled On</span>
                                                <span class="font-semibold text-slate-900">{{ $order->cancelled_at ? \Carbon\Carbon::parse($order->cancelled_at)->format('d M Y') : $order->updated_at->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-medium text-slate-500 text-xs">Total Amount</span>
                                                <span class="font-semibold text-slate-900">₹{{ number_format($order->total_amount) }}</span>
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="font-medium text-slate-500 text-xs">Order Number</span>
                                                <span class="font-semibold text-slate-900">#{{ $order->order_number }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="px-3 py-1 rounded-md text-xs font-semibold flex items-center gap-1.5 bg-rose-50 text-rose-600 border border-rose-100">
                                                <i data-lucide="{{ $isPendingReview ? 'clock' : 'x-circle' }}" class="h-3.5 w-3.5" stroke-width="2"></i> {{ $badgeText }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Body -->
                                    <div class="p-6">
                                        <div class="flex flex-col md:flex-row gap-6">
                                            <!-- Image -->
                                            <div class="shrink-0 h-24 w-24 bg-slate-50 rounded-xl overflow-hidden border border-slate-100 grayscale opacity-70">
                                                <img src="{{ $productImage }}" alt="{{ $productName }}" class="h-full w-full object-cover">
                                            </div>
                                            
                                            <!-- Product Details -->
                                            <div class="flex-1">
                                                <h4 class="text-base font-semibold text-slate-900 leading-tight">{{ $productName }}</h4>
                                                <p class="text-sm font-normal text-slate-500 mt-1">Qty: {{ $firstItem->quantity ?? 1 }}</p>
                                                @if($additionalCount > 0)
                                                    <p class="text-xs font-medium text-slate-600 mt-2 bg-slate-50 inline-block px-2.5 py-1 rounded-md border border-slate-200">+ {{ $additionalCount }} More Items</p>
                                                @endif
                                                
                                                <div class="mt-4 p-4 bg-slate-50 rounded-xl border border-slate-100 flex flex-col gap-1.5 w-full">
                                                    <p class="text-xs font-medium text-slate-600">
                                                        <span class="font-semibold text-slate-800">Reason:</span> {{ $order->cancellation_reason ?? 'Customer Request' }}
                                                    </p>
                                                    @if($order->payment_status === 'paid' || $order->refund_status !== 'none')
                                                        <div class="mt-2 space-y-1">
                                                            <p class="text-xs font-medium text-slate-600 flex items-center gap-1.5">
                                                                <span class="font-semibold text-slate-800">Refund Status:</span> 
                                                                <span class="{{ $order->refund_status === 'completed' ? 'text-emerald-600' : ($order->refund_status === 'failed' ? 'text-rose-600' : 'text-orange-600') }} font-bold uppercase tracking-wider text-[10px]">{{ $order->refund_status }}</span>
                                                            </p>
                                                            @if($order->refund_amount)
                                                                <p class="text-xs font-medium text-slate-600 flex items-center gap-1.5">
                                                                    <span class="font-semibold text-slate-800">Refund Amount:</span> 
                                                                    <span>₹{{ number_format($order->refund_amount) }}</span>
                                                                </p>
                                                            @endif
                                                            @if($order->razorpay_refund_id)
                                                                <p class="text-xs font-medium text-slate-600 flex items-center gap-1.5">
                                                                    <span class="font-semibold text-slate-800">Refund Reference:</span> 
                                                                    <span class="font-mono text-[10px] bg-slate-100 px-1.5 py-0.5 rounded">{{ $order->razorpay_refund_id }}</span>
                                                                </p>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="shrink-0 flex flex-col justify-end gap-3 w-full md:w-48 mt-4 md:mt-0">
                                                <div class="flex items-center justify-between mt-auto pt-4 md:pt-0 px-1 border-t md:border-t-0 border-slate-100">
                                                    <a href="{{ route('order.invoice', ['order' => $order->order_number]) }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                                                        <i data-lucide="download" class="h-3.5 w-3.5"></i> Invoice
                                                    </a>
                                                    <a href="https://wa.me/919876543210?text=Help with Order #{{ $order->order_number }}" target="_blank" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors flex items-center gap-1.5">
                                                        <i data-lucide="life-buoy" class="h-3.5 w-3.5"></i> Help
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Pagination -->
                        @if(method_exists($cancelledOrders, 'links') && $cancelledOrders->hasPages())
                        <div class="mt-8">
                            {{ $cancelledOrders->appends(request()->query())->links() }}
                        </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- SECTION: PROFILE -->
            <div id="section-profile" class="tab-section {{ $activeTab !== 'profile' ? 'hidden' : '' }} bg-slate-50 min-h-[calc(100vh-80px)] transition-all duration-300 p-8 sm:p-12 lg:p-16 w-full">
                <div class="w-full">
                    <!-- Header -->
                    <div class="mb-10">
                        <h3 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Profile Information</h3>
                        <p class="text-sm text-slate-500 mt-2">Manage your personal details and account settings</p>
                    </div>

                    <div class="space-y-8">
                        <!-- Profile Form Card -->
                        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200/60 p-8">
                            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                                @csrf @method('patch')
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2.5">
                                        <label class="text-[13px] font-semibold text-slate-700 block">Full Name</label>
                                        <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-transparent border border-slate-200 px-4 h-11 text-sm font-medium text-slate-900 outline-none rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                    </div>
                                    <div class="space-y-2.5">
                                        <label class="text-[13px] font-semibold text-slate-700 block">Email Address</label>
                                        <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-transparent border border-slate-200 px-4 h-11 text-sm font-medium text-slate-900 outline-none rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                    </div>
                                </div>
                                <div class="pt-4">
                                    <button type="submit" class="bg-[#0f172a] text-white px-6 h-11 text-sm font-medium hover:bg-slate-800 transition-all rounded-xl shadow-sm">Save Changes</button>
                                </div>
                            </form>
                        </div>

                        <!-- Security & Password Card -->
                        <div class="bg-white rounded-[20px] shadow-sm border border-slate-200/60 p-8">
                            <h4 class="text-xl font-bold text-slate-900 mb-8">Security & Password</h4>
                            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                                @csrf @method('put')
                                
                                <div class="space-y-2.5 max-w-full md:max-w-[calc(50%-12px)]">
                                    <label class="text-[13px] font-semibold text-slate-700 block">Current Password</label>
                                    <div class="relative">
                                        <input type="password" name="current_password" class="password-input w-full bg-transparent border border-slate-200 px-4 h-11 text-sm font-medium text-slate-900 outline-none rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                        <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i data-lucide="eye" class="h-4 w-4 eye-icon"></i>
                                            <i data-lucide="eye-off" class="h-4 w-4 eye-off-icon hidden"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                                    <div class="space-y-2.5">
                                        <div class="flex items-center justify-between">
                                            <label class="text-[13px] font-semibold text-slate-700 block">New Password</label>
                                            <button type="button" onclick="openForgotPasswordFlow('{{ auth()->user()->email }}')" class="text-[13px] font-semibold text-orange-600 hover:text-orange-700 hover:underline">Forgot Password?</button>
                                        </div>
                                        <div class="relative">
                                            <input type="password" name="password" class="password-input w-full bg-transparent border border-slate-200 px-4 h-11 text-sm font-medium text-slate-900 outline-none rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                            <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                                <i data-lucide="eye" class="h-4 w-4 eye-icon"></i>
                                                <i data-lucide="eye-off" class="h-4 w-4 eye-off-icon hidden"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="space-y-2.5">
                                        <label class="text-[13px] font-semibold text-slate-700 block">Confirm New Password</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" class="password-input w-full bg-transparent border border-slate-200 px-4 h-11 text-sm font-medium text-slate-900 outline-none rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all">
                                            <button type="button" class="toggle-password absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                                <i data-lucide="eye" class="h-4 w-4 eye-icon"></i>
                                                <i data-lucide="eye-off" class="h-4 w-4 eye-off-icon hidden"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="bg-[#0f172a] text-white px-6 h-11 text-sm font-medium hover:bg-slate-800 transition-all rounded-xl shadow-sm">Update Password</button>
                                </div>
                            </form>
                        </div>

                        <!-- Deactivate Account -->
                        <div class="bg-white rounded-[20px] shadow-sm border border-rose-100 p-8 relative overflow-hidden group">
                            <div class="absolute -right-8 -bottom-8 opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
                                <i data-lucide="user-x" class="h-48 w-48 text-rose-900"></i>
                            </div>
                            <div class="relative z-10">
                                <h4 class="text-xl font-bold text-rose-600 mb-3">Deactivate Account</h4>
                                <p class="text-sm text-slate-500 mb-8 max-w-xl leading-relaxed">Your account will be hidden and you will be logged out. All your data and order history is preserved. You can reactivate instantly by logging back in.</p>
                                
                                <form method="post" action="{{ route('profile.destroy') }}" class="flex flex-col sm:flex-row items-end gap-4">
                                    @csrf @method('delete')
                                    <div class="w-full sm:w-80 space-y-2.5">
                                        <label class="text-[13px] font-semibold text-slate-700 block">Confirm with Password</label>
                                        <input type="password" name="password" placeholder="••••••••" class="password-input w-full bg-slate-50/50 border border-slate-200 px-4 h-11 text-sm font-medium text-slate-900 outline-none rounded-xl focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all">
                                    </div>
                                    <button type="submit" class="w-full sm:w-auto bg-rose-600 text-white px-8 h-11 text-sm font-medium hover:bg-rose-700 transition-all rounded-xl shadow-sm">Deactivate Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION: ADDRESSES -->
            <div id="section-addresses" class="tab-section {{ $activeTab !== 'addresses' ? 'hidden' : '' }} p-6 md:p-10 transition-all duration-300 w-full">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900">Manage Addresses</h3>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Primary delivery locations</p>
                    </div>
                    <button onclick="openAddressModal()" class="bg-orange-600 text-white px-6 py-3 text-[9px] font-black uppercase tracking-widest hover:bg-orange-700 transition-all rounded-xl flex items-center gap-2 shadow-lg shadow-orange-100">
                        <i data-lucide="plus" class="h-3 w-3"></i> Add New Address
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="addresses-container">
                    @forelse($addresses as $address)
                    <div class="border-2 {{ $address->is_default ? 'border-orange-500 shadow-xl shadow-orange-50' : 'border-slate-100 hover:border-slate-200' }} bg-white p-8 rounded-[2rem] relative group transition-all">
                        @if($address->is_default)
                        <div class="absolute top-6 right-6">
                            <span class="bg-orange-500 text-white text-[8px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Default</span>
                        </div>
                        @endif
                        <div class="h-10 w-10 {{ $address->is_default ? 'bg-orange-50 text-orange-500' : 'bg-slate-50 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-500' }} rounded-xl flex items-center justify-center mb-6 transition-all">
                            <i data-lucide="{{ $address->type === 'Work' ? 'briefcase' : 'home' }}" class="h-5 w-5"></i>
                        </div>
                        <h4 class="font-black text-slate-900 uppercase text-xs mb-2">{{ $address->type }} Address</h4>
                        <p class="text-xs text-slate-500 leading-relaxed font-medium mb-6">
                            <strong>{{ $address->full_name }}</strong><br>
                            {{ $address->address_line1 }}@if($address->address_line2), {{ $address->address_line2 }}@endif<br>
                            {{ $address->city }}, {{ $address->state }}, {{ $address->pincode }}<br>
                            Phone: {{ $address->phone }}
                        </p>
                        <div class="flex items-center gap-4 pt-6 border-t border-slate-50">
                            <button onclick="editAddress({{ json_encode($address) }})" class="text-[9px] font-black text-slate-400 hover:text-orange-500 uppercase tracking-widest">Edit Address</button>
                            @if(!$address->is_default)
                            <span class="h-3 w-px bg-slate-100"></span>
                            <button onclick="setDefaultAddress({{ $address->id }})" class="text-[9px] font-black text-slate-400 hover:text-indigo-500 uppercase tracking-widest">Set Default</button>
                            @endif
                            <span class="h-3 w-px bg-slate-100"></span>
                            <button onclick="deleteAddress({{ $address->id }})" class="text-[9px] font-black text-slate-400 hover:text-rose-500 uppercase tracking-widest">Remove</button>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center border-2 border-dashed border-slate-100 rounded-[2.5rem]">
                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i data-lucide="map-pin" class="h-6 w-6 text-slate-200"></i>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No addresses saved yet</p>
                    </div>
                    @endforelse
                </div>
            </div>



        </div>

    </div>
</div>

<!-- Address Modal -->
<div id="address-modal" class="fixed inset-0 z-[10000] hidden">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity duration-500 opacity-0" id="address-modal-overlay" onclick="closeAddressModal()"></div>
    <div class="absolute right-0 top-0 h-screen w-full max-w-md bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-500 overflow-hidden" id="address-modal-content">
        <!-- Header: Fixed at top -->
        <div class="p-8 border-b border-slate-50 flex items-center justify-between shrink-0">
            <div>
                <h3 class="text-xl font-black uppercase tracking-tighter text-slate-900" id="address-modal-title">Add Address</h3>
                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest mt-1">Fill in the delivery details</p>
            </div>
            <button onclick="closeAddressModal()" class="h-10 w-10 rounded-full hover:bg-slate-50 flex items-center justify-center text-slate-400 transition-all">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>
        
        <!-- Scrollable content area -->
        <div class="flex-1 overflow-y-auto overscroll-contain" data-lenis-prevent style="-webkit-overflow-scrolling: touch;">
            <form id="address-form" class="p-8 space-y-6" onsubmit="saveAddress(event)">
                @csrf
                <input type="hidden" id="address-id" name="id">
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Type</label>
                        <select name="type" id="address-type" class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                            <option value="Home">Home</option>
                            <option value="Work">Work</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="space-y-2 flex items-end">
                        <label class="flex items-center gap-3 cursor-pointer group pb-3.5 pl-1">
                            <input type="checkbox" name="is_default" id="address-default" value="1" class="rounded border-slate-200 text-orange-500 focus:ring-orange-500/20">
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest group-hover:text-slate-600 transition-colors">Set Default</span>
                        </label>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Full Name</label>
                    <input type="text" name="full_name" id="address-name" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Phone Number</label>
                    <input type="text" name="phone" id="address-phone" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 1</label>
                    <input type="text" name="address_line1" id="address-line1" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Address Line 2 (Optional)</label>
                    <input type="text" name="address_line2" id="address-line2" class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">City</label>
                        <input type="text" name="city" id="address-city" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">State</label>
                        <input type="text" name="state" id="address-state" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pincode</label>
                        <input type="text" name="pincode" id="address-pincode" required class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl focus:ring-1 focus:ring-orange-500/20">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Country</label>
                        <input type="text" name="country" value="India" readonly class="w-full bg-[#F1F3F6] border-none px-6 py-3.5 text-xs font-bold outline-none rounded-xl opacity-60">
                    </div>
                </div>

                <div class="pt-6 pb-12">
                    <button type="submit" id="address-submit-btn" class="w-full bg-orange-600 !text-white py-4 text-[12px] font-black uppercase tracking-widest hover:bg-orange-700 transition-all rounded-xl shadow-lg shadow-orange-100 flex items-center justify-center gap-2">
                        <span class="!text-white">Save Address</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    main { padding: 0 !important; margin: 0 !important; max-width: none !important; }
    .nav-link.active { background: #FFF7ED; color: #F97316; border-right: 4px solid #F97316; }
    .nav-link:not(.active) { color: #64748B; border-right: 4px solid transparent; }
</style>

@push('scripts')
<script>
    // Tab Switching Logic
    window.switchTab = function(tabId, btn) {
        // Update URL without reload for persistence
        const url = new URL(window.location);
        url.searchParams.set('tab', tabId);
        window.history.pushState({}, '', url);

        // Hide all sections
        document.querySelectorAll('.tab-section').forEach(section => {
            section.classList.add('hidden');
        });

        // Show target section
        const target = document.getElementById('section-' + tabId);
        if (target) target.classList.remove('hidden');

        // Update Nav Links
        document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active', 'bg-orange-50', 'text-orange-600', 'border-r-4', 'border-orange-500');
            link.classList.add('text-slate-500');
        });

        // Activate clicked button
        if (btn) {
            btn.classList.add('active', 'bg-orange-50', 'text-orange-600', 'border-r-4', 'border-orange-500');
            btn.classList.remove('text-slate-500');
        }

        // Refresh icons in case new content appeared
        if (window.lucide) lucide.createIcons();
    };

    // Password Toggle Logic
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = button.parentElement.querySelector('.password-input');
                const eyeIcon = button.querySelector('.eye-icon');
                const eyeOffIcon = button.querySelector('.eye-off-icon');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    eyeIcon.classList.add('hidden');
                    eyeOffIcon.classList.remove('hidden');
                } else {
                    input.type = 'password';
                    eyeIcon.classList.remove('hidden');
                    eyeOffIcon.classList.add('hidden');
                }
            });
        });
    });

    // Address Management
    function openAddressModal(address = null) {
        const modal = document.getElementById('address-modal');
        const content = document.getElementById('address-modal-content');
        const overlay = document.getElementById('address-modal-overlay');
        const form = document.getElementById('address-form');
        const title = document.getElementById('address-modal-title');
        
        form.reset();
        document.getElementById('address-id').value = '';
        
        if (address) {
            title.innerText = 'Edit Address';
            document.getElementById('address-id').value = address.id;
            document.getElementById('address-type').value = address.type;
            document.getElementById('address-name').value = address.full_name;
            document.getElementById('address-phone').value = address.phone;
            document.getElementById('address-line1').value = address.address_line1;
            document.getElementById('address-line2').value = address.address_line2 || '';
            document.getElementById('address-city').value = address.city;
            document.getElementById('address-state').value = address.state;
            document.getElementById('address-pincode').value = address.pincode;
            document.getElementById('address-default').checked = address.is_default;
        } else {
            title.innerText = 'Add Address';
        }

        modal.classList.remove('hidden');
        setTimeout(() => {
            overlay.classList.remove('opacity-0');
            content.classList.remove('translate-x-full');
        }, 10);
        document.body.style.overflow = 'hidden';
        if (window.lenis) window.lenis.stop();
    }

    function closeAddressModal() {
        const content = document.getElementById('address-modal-content');
        const overlay = document.getElementById('address-modal-overlay');
        const modal = document.getElementById('address-modal');

        content.classList.add('translate-x-full');
        overlay.classList.add('opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            if (window.lenis) window.lenis.start();
        }, 500);
    }

    window.saveAddress = async function(e) {
        e.preventDefault();
        const form = e.target;
        const id = document.getElementById('address-id').value;
        const btn = document.getElementById('address-submit-btn');
        const originalHtml = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Processing...';
        if(window.lucide) lucide.createIcons();

        const url = id ? `/addresses/${id}` : '/addresses';
        const method = id ? 'PUT' : 'POST';
        
        const formData = new FormData(form);
        if (id) formData.append('_method', 'PUT');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            
            if (response.ok) {
                showToast(data.message, 'success');
                closeAddressModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Validation failed', 'error');
            }
        } catch (error) {
            showToast('Something went wrong', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if(window.lucide) lucide.createIcons();
        }
    }

    window.editAddress = function(address) {
        openAddressModal(address);
    }

    window.deleteAddress = async function(id) {
        if (!confirm('Are you sure you want to remove this address?')) return;
        
        try {
            const response = await fetch(`/addresses/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (response.ok) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        } catch (error) {
            showToast('Failed to delete address', 'error');
        }
    }

    window.setDefaultAddress = async function(id) {
        try {
            const response = await fetch(`/addresses/${id}/default`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (response.ok) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        } catch (error) {
            showToast('Failed to update default address', 'error');
        }
    }

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
    document.getElementById('cancel-form').addEventListener('submit', function(e) {
        const checkbox = document.getElementById('confirm-cancel-checkbox');
        if (!checkbox.checked) {
            e.preventDefault();
            alert('Please confirm that you want to cancel this order.');
        }
    });
</script>

<!-- Cancel Order Modal -->
<div id="cancel-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden relative" @click.outside="closeCancelModal()">
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
