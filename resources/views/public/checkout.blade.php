@extends('public.layouts.app')

@section('title', 'Checkout - ' . config('app.name', 'Remenant Health'))

@section('content')
<div class="bg-[var(--bg-main)] min-h-screen pt-24 pb-12 sm:pt-32 sm:pb-24">
    <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-12">
        
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-10 sm:mb-16">
            <div>
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-[color:var(--primary)] transition group">
                    <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1"></i>
                    Back to shopping
                </a>
                <h1 class="text-3xl sm:text-4xl font-black text-[color:var(--text-primary)] mt-4 tracking-tight uppercase">Checkout</h1>
            </div>
            <div class="hidden sm:flex items-center gap-4 text-xs font-black uppercase tracking-widest text-gray-400">
                <span class="text-[color:var(--primary)]">1. Information</span>
                <span class="w-8 h-px bg-gray-200"></span>
                <span>2. Shipping</span>
                <span class="w-8 h-px bg-gray-200"></span>
                <span>3. Payment</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20">
            
            <!-- Left Column: Checkout Form -->
            <div class="lg:col-span-7 space-y-12">
                
                <!-- Contact Information -->
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-[color:var(--primary)] shadow-sm">
                            <i data-lucide="user" class="h-6 w-6"></i>
                        </div>
                        <h2 class="text-xl font-extrabold text-[color:var(--text-primary)] tracking-tight uppercase">Contact Information</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Email Address</label>
                            <input type="email" placeholder="alex@example.com" class="w-full rounded-2xl bg-white border border-gray-100 px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:outline-none focus:border-[color:var(--primary)] shadow-sm transition-all">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Phone Number</label>
                            <input type="tel" placeholder="+91 98765 43210" class="w-full rounded-2xl bg-white border border-gray-100 px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:outline-none focus:border-[color:var(--primary)] shadow-sm transition-all">
                        </div>
                    </div>
                </section>

                <!-- Shipping Address -->
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 shadow-sm">
                            <i data-lucide="map-pin" class="h-6 w-6"></i>
                        </div>
                        <h2 class="text-xl font-extrabold text-[color:var(--text-primary)] tracking-tight uppercase">Shipping Address</h2>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">First Name</label>
                                <input type="text" placeholder="First Name" class="w-full rounded-2xl bg-white border border-gray-100 px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:outline-none focus:border-[color:var(--primary)] shadow-sm transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Last Name</label>
                                <input type="text" placeholder="Last Name" class="w-full rounded-2xl bg-white border border-gray-100 px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:outline-none focus:border-[color:var(--primary)] shadow-sm transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Address Line 1</label>
                            <input type="text" placeholder="Flat, House no., Building, Company, Apartment" class="w-full rounded-2xl bg-white border border-gray-100 px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:outline-none focus:border-[color:var(--primary)] shadow-sm transition-all">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Pincode</label>
                                <input type="text" placeholder="400051" class="w-full rounded-2xl bg-white border border-gray-100 px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:outline-none focus:border-[color:var(--primary)] shadow-sm transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">City</label>
                                <input type="text" placeholder="Mumbai" class="w-full rounded-2xl bg-white border border-gray-100 px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:outline-none focus:border-[color:var(--primary)] shadow-sm transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">State</label>
                                <select class="w-full rounded-2xl bg-white border border-gray-100 px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:outline-none focus:border-[color:var(--primary)] shadow-sm transition-all appearance-none">
                                    <option value="Maharashtra">Maharashtra</option>
                                    <option value="Delhi">Delhi</option>
                                    <option value="Karnataka">Karnataka</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Payment Method -->
                <section>
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-sm">
                            <i data-lucide="credit-card" class="h-6 w-6"></i>
                        </div>
                        <h2 class="text-xl font-extrabold text-[color:var(--text-primary)] tracking-tight uppercase">Payment Method</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative flex items-center gap-4 p-6 rounded-2xl bg-white border-2 border-gray-100 cursor-pointer transition-all hover:border-[color:var(--primary)]/30 group has-[:checked]:border-[color:var(--primary)] has-[:checked]:bg-[var(--primary-soft)]">
                            <input type="radio" name="payment" value="prepaid" class="peer h-5 w-5 text-[color:var(--primary)] focus:ring-[color:var(--primary)] border-gray-300" checked>
                            <div class="flex-1">
                                <span class="block text-sm font-black text-[color:var(--text-primary)] uppercase tracking-wider">Online Payment</span>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">UPI, Cards, NetBanking</span>
                            </div>
                            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 group-hover:bg-white transition-colors peer-checked:bg-white">
                                <i data-lucide="zap" class="h-5 w-5 text-[color:var(--primary)]"></i>
                            </div>
                        </label>

                        <label class="relative flex items-center gap-4 p-6 rounded-2xl bg-white border-2 border-gray-100 cursor-pointer transition-all hover:border-[color:var(--primary)]/30 group has-[:checked]:border-[color:var(--primary)] has-[:checked]:bg-[var(--primary-soft)]">
                            <input type="radio" name="payment" value="cod" class="peer h-5 w-5 text-[color:var(--primary)] focus:ring-[color:var(--primary)] border-gray-300">
                            <div class="flex-1">
                                <span class="block text-sm font-black text-[color:var(--text-primary)] uppercase tracking-wider">Cash on Delivery</span>
                                <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Pay at your doorstep</span>
                            </div>
                            <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-gray-50 group-hover:bg-white transition-colors peer-checked:bg-white">
                                <i data-lucide="truck" class="h-5 w-5 text-gray-400"></i>
                            </div>
                        </label>
                    </div>
                </section>

                <div class="pt-4">
                    <button class="w-full rounded-2xl bg-[color:var(--text-primary)] py-5 text-base font-black text-white uppercase tracking-[0.2em] shadow-2xl transition hover:brightness-110 active:scale-[0.98] flex items-center justify-center gap-4">
                        Confirm Purchase
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </button>
                    <p class="text-[10px] font-bold text-gray-400 text-center uppercase tracking-widest mt-6 flex items-center justify-center gap-2">
                        <i data-lucide="shield-check" class="h-4 w-4 text-emerald-500"></i>
                        SSL Secure & Encrypted Checkout
                    </p>
                </div>

            </div>

            <!-- Right Column: Order Summary -->
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-32 space-y-8">
                    <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-sm ring-1 ring-black/[0.03]">
                        <h2 class="text-xl font-extrabold text-[color:var(--text-primary)] tracking-tight uppercase mb-8">Order Summary</h2>
                        
                        <!-- Product List -->
                        <div class="space-y-6 pb-8 border-b border-black/5 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @if($product)
                                <div class="flex gap-6 items-center">
                                    <div class="h-24 w-24 shrink-0 rounded-2xl bg-gray-50 p-2 ring-1 ring-black/5 relative">
                                        <img src="{{ asset('images/products/' . $product['image']) }}" alt="{{ $product['title'] }}" class="h-full w-full object-contain">
                                        <span class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-[color:var(--text-primary)] text-white text-[10px] font-black flex items-center justify-center shadow-lg">1</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-black text-[color:var(--text-primary)] uppercase tracking-tight leading-snug">{{ $product['title'] }}</h3>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $product['tagline'] }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-base font-black text-[color:var(--text-primary)]">₹{{ number_format($product['price']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                @php
                                    $mockCartItems = [
                                        [
                                            'title' => 'Ultimate Immunity Duo',
                                            'tagline' => 'Double protection',
                                            'price' => 3299,
                                            'image' => 'remenant-product1.jpg',
                                            'qty' => 1
                                        ],
                                        [
                                            'title' => 'Glow & Strength Bundle',
                                            'tagline' => 'Beauty essentials',
                                            'price' => 3499,
                                            'image' => 'remenant-product2.jpg',
                                            'qty' => 2
                                        ]
                                    ];
                                    $totalPrice = array_reduce($mockCartItems, fn($acc, $item) => $acc + ($item['price'] * $item['qty']), 0);
                                @endphp
                                @foreach($mockCartItems as $item)
                                    <div class="flex gap-6 items-center">
                                        <div class="h-24 w-24 shrink-0 rounded-2xl bg-gray-50 p-2 ring-1 ring-black/5 relative">
                                            <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['title'] }}" class="h-full w-full object-contain">
                                            <span class="absolute -top-2 -right-2 h-6 w-6 rounded-full bg-[color:var(--text-primary)] text-white text-[10px] font-black flex items-center justify-center shadow-lg">{{ $item['qty'] }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-sm font-black text-[color:var(--text-primary)] uppercase tracking-tight leading-snug">{{ $item['title'] }}</h3>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $item['tagline'] }}</p>
                                            <div class="flex items-center justify-between mt-2">
                                                <span class="text-base font-black text-[color:var(--text-primary)]">₹{{ number_format($item['price']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <!-- Calculations -->
                        <div class="py-8 space-y-4 border-b border-black/5">
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Subtotal</span>
                                <span class="font-black text-[color:var(--text-primary)]">₹{{ number_format($product ? $product['price'] : ($totalPrice ?? 0)) }}</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Shipping</span>
                                <span class="font-bold text-emerald-600 uppercase tracking-widest text-[10px]">Free</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="font-bold text-gray-400 uppercase tracking-widest text-[10px]">Tax (GST)</span>
                                <span class="font-black text-[color:var(--text-primary)]">₹0</span>
                            </div>
                        </div>

                        <!-- Total -->
                        <div class="pt-8">
                            <div class="flex justify-between items-end">
                                <div>
                                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Total to pay</span>
                                    <span class="text-4xl font-black text-[color:var(--text-primary)] tracking-tighter">₹{{ number_format($product ? $product['price'] : ($totalPrice ?? 0)) }}</span>
                                </div>
                                <div class="bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 flex items-center gap-2">
                                    <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">Pricing Guaranteed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Strip -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-6 rounded-3xl bg-white/50 border border-gray-100 flex flex-col items-center text-center gap-3">
                            <i data-lucide="rotate-ccw" class="h-6 w-6 text-gray-400"></i>
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest leading-tight">15-Day Easy<br>Returns Policy</span>
                        </div>
                        <div class="p-6 rounded-3xl bg-white/50 border border-gray-100 flex flex-col items-center text-center gap-3">
                            <i data-lucide="award" class="h-6 w-6 text-gray-400"></i>
                            <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest leading-tight">Certified Safe<br>& Effective</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
