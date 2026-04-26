@extends('public.layouts.app')

@section('title', 'Shopping Cart - Remenant Health')

@section('content')
    <div class="bg-gray-50/50 py-12 sm:py-16">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">

            <!-- Page Header -->
            <div class="mb-12 text-center sm:text-left">
                <h1 class="text-4xl font-black italic tracking-tight text-[color:var(--text-primary)] sm:text-6xl">Your Cart
                </h1>
                <p class="mt-4 text-lg font-bold text-[color:var(--text-secondary)]">Review your wellness essentials before
                    checkout.</p>
            </div>

            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-start">

                <!-- Left: Cart Items -->
                <div class="lg:col-span-8">
                    @php
                        $cartItems = [
                            [
                                'id' => 1,
                                'title' => 'Ultimate Immunity Duo',
                                'tagline' => 'Double the Protection',
                                'price' => 3299,
                                'mrp' => 4999,
                                'image' => 'remenant-product1.jpg',
                                'quantity' => 1,
                                'stock' => 'In Stock',
                                'delivery' => 'Delivery by Wednesday, Oct 25'
                            ],
                            [
                                'id' => 2,
                                'title' => 'Glow & Strength Bundle',
                                'tagline' => 'Complete Beauty Care',
                                'price' => 3499,
                                'mrp' => 5499,
                                'image' => 'remenant-product2.jpg',
                                'quantity' => 2,
                                'stock' => 'Low Stock',
                                'delivery' => 'Delivery by Friday, Oct 27'
                            ]
                        ];
                    @endphp

                    @if(count($cartItems) > 0)
                        <div class="space-y-4">
                            @foreach($cartItems as $item)
                                <div
                                    class="group relative overflow-hidden rounded-[2.5rem] bg-white shadow-sm ring-1 ring-black/5 transition-all hover:shadow-md">
                                    <div class="flex flex-col sm:flex-row">
                                        <!-- Product Image -->
                                        <div
                                            class="relative w-full aspect-square sm:h-56 sm:w-56 shrink-0 bg-gray-50/50 rounded-t-[2.5rem] sm:rounded-tr-none sm:rounded-l-[2.5rem] overflow-hidden">
                                            <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['title'] }}"
                                                width="1200" height="1200" class="h-full w-full object-cover">
                                            @if($item['stock'] == 'Low Stock')
                                                <div
                                                    class="absolute bottom-3 left-3 rounded-lg bg-orange-500 px-2 py-1 text-[10px] font-black uppercase tracking-widest text-white shadow-lg">
                                                    {{ $item['stock'] }}
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex flex-1 flex-col p-6 sm:p-8">
                                            <div class="flex items-start justify-between">
                                                <div>
                                                    <p
                                                        class="text-[10px] font-black uppercase tracking-[0.2em] text-[color:var(--primary)]">
                                                        {{ $item['tagline'] }}</p>
                                                    <h3
                                                        class="mt-1 text-xl font-black text-[color:var(--text-primary)] tracking-tight">
                                                        {{ $item['title'] }}</h3>
                                                </div>
                                                <div class="flex gap-2">
                                                    <button type="button"
                                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:bg-pink-50 hover:text-pink-500 transition active:scale-95"
                                                        title="Save to wishlist">
                                                        <i data-lucide="heart" class="h-5 w-5"></i>
                                                    </button>
                                                    <button type="button"
                                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-red-50 text-red-400 hover:bg-red-100 hover:text-red-500 transition active:scale-95"
                                                        title="Remove">
                                                        <i data-lucide="trash-2" class="h-5 w-5"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="mt-4 flex items-center gap-4">
                                                <span
                                                    class="text-2xl font-black text-[color:var(--text-primary)]">₹{{ number_format($item['price']) }}</span>
                                                <span
                                                    class="text-sm font-bold text-gray-400 line-through">₹{{ number_format($item['mrp']) }}</span>
                                                <span class="text-sm font-black text-green-600 bg-green-50 px-2 py-0.5 rounded-lg">
                                                    {{ round((1 - ($item['price'] / $item['mrp'])) * 100) }}% OFF
                                                </span>
                                            </div>
                                            <div class="mt-auto flex items-center gap-6 pt-6">
                                                <!-- Quantity -->
                                                <div class="flex items-center gap-4">
                                                    <span
                                                        class="text-xs font-black uppercase tracking-widest text-gray-400">Qty</span>
                                                    <div class="flex items-center rounded-2xl bg-gray-100 p-1 ring-1 ring-black/5">
                                                        <button type="button"
                                                            class="flex h-9 w-9 items-center justify-center rounded-xl hover:bg-white hover:shadow-sm transition active:scale-90">
                                                            <i data-lucide="minus" class="h-4 w-4 text-gray-600"></i>
                                                        </button>
                                                        <span
                                                            class="w-10 text-center text-sm font-black">{{ $item['quantity'] }}</span>
                                                        <button type="button"
                                                            class="flex h-9 w-9 items-center justify-center rounded-xl hover:bg-white hover:shadow-sm transition active:scale-90">
                                                            <i data-lucide="plus" class="h-4 w-4 text-gray-600"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Delivery -->
                                                <div class="flex items-center gap-2 text-green-600">
                                                    <i data-lucide="truck" class="h-4 w-4"></i>
                                                    <span class="text-[10px] font-black uppercase tracking-wider">Delivery by Oct
                                                        25</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-8 flex items-center justify-between px-6">
                                <a href="{{ route('products.index') }}"
                                    class="flex items-center gap-2 text-sm font-black uppercase tracking-widest text-gray-500 hover:text-black transition">
                                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                                    Continue Shopping
                                </a>
                                <button type="button"
                                    class="text-sm font-black uppercase tracking-widest text-red-500 hover:text-red-600 transition">
                                    Clear Cart
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Empty Cart State -->
                        <div class="rounded-[3rem] bg-white py-24 text-center shadow-sm ring-1 ring-black/5">
                            <div
                                class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-gray-50 shadow-xl mb-8">
                                <i data-lucide="shopping-bag" class="h-10 w-10 text-gray-300"></i>
                            </div>
                            <h2 class="text-3xl font-black text-gray-900 italic">Your cart is empty</h2>
                            <p class="mt-4 text-gray-500 font-bold">Looks like you haven't added anything yet.</p>
                            <a href="{{ route('products.index') }}"
                                class="mt-10 inline-flex items-center gap-2 rounded-full bg-[var(--primary)] px-10 py-5 text-sm font-black uppercase tracking-widest text-white shadow-2xl transition hover:-translate-y-1">
                                Start Shopping
                                <i data-lucide="arrow-right" class="h-5 w-5"></i>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Right: Order Summary -->
                <div class="lg:col-span-4 lg:sticky lg:top-32">
                    <div class="rounded-[3rem] bg-white p-8 shadow-2xl ring-1 ring-black/5">
                        <h2 class="text-2xl font-black italic tracking-tight text-[color:var(--text-primary)] mb-8">Order
                            Summary</h2>

                        <!-- Coupon Section -->
                        <div class="mb-10">
                            <p class="text-xs font-black uppercase tracking-widest text-gray-400 mb-3">Have a coupon?</p>
                            <div class="relative group/promo">
                                <input type="text" placeholder="REMENANT10"
                                    class="w-full rounded-2xl bg-gray-50 px-6 py-4 text-sm font-bold shadow-inner outline-none ring-1 ring-black/5 focus:ring-[var(--primary)] transition-all">
                                <button type="button"
                                    class="absolute right-2 top-2 rounded-xl bg-[var(--primary)] px-4 py-2 text-xs font-black uppercase tracking-widest text-white hover:brightness-110 transition active:scale-95">
                                    Apply
                                </button>
                            </div>
                            <p class="mt-2 text-[10px] font-bold text-green-600 px-1">Use REMENANT10 for extra 10% off!</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-500">Cart Total (3 items)</span>
                                <span class="text-sm font-black text-gray-900">₹10,297</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-500">Product Discount</span>
                                <span class="text-sm font-black text-green-600">-₹4,199</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-gray-500">Estimated Tax</span>
                                <span class="text-sm font-black text-gray-900">₹540</span>
                            </div>

                            <div class="h-px bg-gray-100 my-6"></div>

                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-widest text-gray-400">Total Payable</p>
                                    <p class="text-4xl font-black text-[color:var(--text-primary)] tracking-tight">₹10,837
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-[10px] font-black uppercase tracking-widest text-green-600 bg-green-50 px-2 py-1 rounded-lg">
                                        You Save ₹4,348</p>
                                </div>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <button type="button"
                            class="mt-10 w-full group relative overflow-hidden rounded-[2rem] bg-[var(--primary)] py-4 text-sm font-black uppercase tracking-[0.2em] text-white shadow-xl transition-all hover:brightness-105 active:scale-95">
                            <span class="relative z-10 flex items-center justify-center gap-3">
                                Place Order
                                <i data-lucide="chevron-right"
                                    class="h-5 w-5 transition-transform group-hover:translate-x-1"></i>
                            </span>
                        </button>

                        <!-- Safety Badges -->
                        <div class="mt-10 grid grid-cols-3 gap-4">
                            <div class="flex flex-col items-center gap-2 text-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-50">
                                    <i data-lucide="shield-check" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <span
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 leading-tight">100%<br>Secure</span>
                            </div>
                            <div class="flex flex-col items-center gap-2 text-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-50">
                                    <i data-lucide="rotate-ccw" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <span
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 leading-tight">Easy<br>Returns</span>
                            </div>
                            <div class="flex flex-col items-center gap-2 text-center">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-50">
                                    <i data-lucide="credit-card" class="h-5 w-5 text-gray-400"></i>
                                </div>
                                <span
                                    class="text-[9px] font-black uppercase tracking-widest text-gray-400 leading-tight">Pay
                                    on<br>Delivery</span>
                            </div>
                        </div>
                    </div>

                    <!-- Offers for You -->
                    <div
                        class="mt-8 overflow-hidden rounded-[2.5rem] bg-gradient-to-br from-orange-50 to-orange-100/50 p-6 ring-1 ring-orange-200/50">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-500 text-white shadow-lg">
                                <i data-lucide="zap" class="h-5 w-5"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-black text-orange-900">Unlock Flash Discount!</h4>
                                <p class="mt-1 text-xs font-bold text-orange-800/70 leading-relaxed">Add ₹499 more to your
                                    cart to get a <span class="font-black">Free Wellness Bottle!</span></p>
                                <div class="mt-4 h-2 w-full overflow-hidden rounded-full bg-orange-200">
                                    <div class="h-full w-[70%] bg-orange-500"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Recommendations  hello-->
            <div class="mt-24 border-t border-black/5 pt-24">
                <div class="mb-12">
                    <h2 class="text-3xl font-black italic tracking-tight text-[color:var(--text-primary)]">You Might Also
                        Need</h2>
                    <p class="mt-2 text-sm font-bold text-gray-400 uppercase tracking-widest">Selected for your wellness
                        journey</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php
                        $recommendations = [
                            ['title' => 'Ultimate Immunity Duo', 'tagline' => 'Double protection', 'price' => 3299, 'mrp' => 4999, 'image' => 'remenant-product1.jpg', 'rating' => 4.8, 'reviews' => 124],
                            ['title' => 'Glow & Strength Bundle', 'tagline' => 'Beauty essentials', 'price' => 3499, 'mrp' => 5499, 'image' => 'remenant-product2.jpg', 'rating' => 4.9, 'reviews' => 89],
                            ['title' => 'Daily Wellness Pack', 'tagline' => 'Energy boost', 'price' => 2899, 'mrp' => 3999, 'image' => 'remenant-product3.jpg', 'rating' => 4.7, 'reviews' => 156],
                            ['title' => 'Vitality Essentials', 'tagline' => 'Daily health', 'price' => 1999, 'mrp' => 2999, 'image' => 'remenant-product1.jpg', 'rating' => 4.6, 'reviews' => 72],
                        ];
                    @endphp
                    @foreach($recommendations as $rec)
                                    @php
                                        $discount = (int) round((1 - ($rec['price'] / $rec['mrp'])) * 100);
                                    @endphp
                         <div
                                        class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5 hover:shadow-md transition">
                                        <button type="button"
                                            class="absolute right-3 top-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 ring-1 ring-black/10 hover:bg-white transition"
                                            aria-label="Add to wishlist">
                                            <i data-lucide="heart" class="h-5 w-5 text-[color:var(--text-primary)]"></i>
                                        </button>

                                        <div class="relative aspect-square overflow-hidden bg-gray-50/50">
                                            <img src="{{ asset('images/products/' . $rec['image']) }}" alt="{{ $rec['title'] }}"
                                                width="1200" height="1200" class="h-full w-full object-cover" loading="lazy">
                                            <div
                                                class="absolute left-3 top-3 rounded-lg bg-[var(--primary)] px-3 py-1 text-xs font-extrabold text-white shadow-lg">
                                                -{{ $discount }}%
                                            </div>
                                        </div>

                                        <div class="flex flex-1 flex-col p-6">
                                            <p class="text-[10px] font-black tracking-[0.2em] text-[color:var(--primary)] uppercase">
                                                {{ $rec['tagline'] }}</p>
                                            <h4 class="mt-1 text-sm font-extrabold text-[color:var(--text-primary)] line-clamp-2">
                                                {{ $rec['title'] }}</h4>

                                            <div class="mt-4 flex items-center justify-between gap-3">
                                                <div class="flex items-baseline gap-2">
                                                    <p class="text-xl font-black text-[color:var(--primary)]">
                                                        ₹{{ number_format($rec['price']) }}</p>
                                                    <p class="text-xs font-bold text-gray-400 line-through">
                                                        ₹{{ number_format($rec['mrp']) }}</p>
                                                </div>
                                                <div
                                                    class="flex items-center gap-1 rounded-lg bg-black/5 px-2 py-1 text-[10px] font-black text-gray-600">
                                                    <i data-lucide="star" class="h-3 w-3 fill-current text-orange-400"></i>
                                                    {{ $rec['rating'] }}
                                                </div>
                                            </div>

                                            <div class="mt-auto pt-6">
                                                <button type="button"
                                                    class="block w-full rounded-full bg-[var(--primary)] py-4 text-[10px] font-black uppercase tracking-[0.2em] text-white shadow-lg transition active:scale-95 hover:brightness-110">
                                                    Add to Cart
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection