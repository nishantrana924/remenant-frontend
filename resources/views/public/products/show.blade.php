@extends('public.layouts.app')

@section('title', $product['title'] . ' - ' . config('app.name', 'Remenant Health'))

@section('content')
    @push('styles')
    <style>
        /* Prevent flicker on reload: show first slide immediately */
        .product-gallery-carousel:not(.owl-loaded) {
            display: flex;
            overflow: hidden;
        }
        .product-gallery-carousel:not(.owl-loaded) > div:not(:first-child) {
            display: none;
        }
        .product-gallery-carousel:not(.owl-loaded) > div:first-child {
            width: 100%;
            display: block;
        }
        .lightbox-carousel:not(.owl-loaded) {
            display: flex;
            overflow: hidden;
        }
        .lightbox-carousel:not(.owl-loaded) > div:not(:first-child) {
            display: none;
        }
        .lightbox-carousel:not(.owl-loaded) > div:first-child {
            width: 100%;
            display: block;
        }
        /* Mobile-first: edge-to-edge product image */
        .product-gallery-shell .owl-stage-outer {
            border-radius: 0;
        }
        @media (min-width: 640px) {
            .product-gallery-shell .owl-stage-outer {
                border-radius: 2rem;
            }
        }
        /* Keep modal image frame consistent */
        .lightbox-frame {
            width: min(94vw, 1240px);
            height: clamp(300px, 76vh, 860px);
        }
        .lightbox-carousel .owl-stage-outer,
        .lightbox-carousel .owl-stage,
        .lightbox-carousel .owl-item,
        .lightbox-carousel .owl-item > div {
            height: 100%;
        }
        .lightbox-nav-btn {
            height: 3.5rem;
            width: 3.5rem;
        }
        @media (max-width: 640px) {
            .lightbox-frame {
                width: 96vw;
                height: 62vh;
            }
            .lightbox-nav-btn {
                height: 2.75rem;
                width: 2.75rem;
            }
        }

        /* Ensure footer is visible above sticky bar */
        footer {
            padding-bottom: 90px;
        }
        @media (min-width: 768px) {
            footer {
                padding-bottom: 100px;
            }
        }
    </style>
    @endpush
    <div class="bg-[var(--bg-main)]">
        @php
            $galleryImages = array_values(array_unique(array_merge([$product['image']], $product['gallery'])));
        @endphp

        <!-- Product Hero Section -->
        <section class="mx-auto max-w-[1600px] px-4 pt-0 lg:pt-8 pb-2 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 items-start">
                
                <!-- Left: Product Images (Sticky on Desktop) -->
                <div class="lg:sticky lg:top-24 lg:self-start">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:gap-4">
                        <div class="hidden sm:flex gap-3 sm:gap-4 overflow-x-auto lg:order-first lg:flex-col lg:overflow-y-auto lg:max-h-[620px] lg:w-24 lg:shrink-0 no-scrollbar product-other-images">
                            @foreach($galleryImages as $index => $img)
                                <button type="button" 
                                        data-index="{{ $index }}"
                                        class="product-other-image-thumb relative aspect-square w-20 sm:w-24 shrink-0 overflow-hidden rounded-2xl bg-white border-2 transition-colors duration-200 shadow-sm {{ $index === 0 ? 'border-[var(--primary)]' : 'border-transparent hover:border-[var(--primary)]/70' }}">
                                    <img src="{{ asset('images/products/' . $img) }}" alt="Product other image" class="h-full w-full object-contain">
                                </button>
                            @endforeach
                        </div>

                    <div class="relative group/gallery product-gallery-shell overflow-hidden -mx-4 rounded-none bg-[var(--bg-section)] ring-1 ring-black/5 shadow-sm sm:mx-0 sm:rounded-[2rem]">
                        <div class="absolute right-3 top-3 sm:right-4 sm:top-4 z-30 flex flex-col gap-2">
                            <button type="button" aria-label="Add to wishlist" class="flex h-11 w-11 items-center justify-center rounded-full bg-white/90 backdrop-blur-md text-[color:var(--text-primary)] shadow-lg ring-1 ring-black/10 transition hover:text-[color:var(--primary)] active:scale-95">
                                <i data-lucide="heart" class="h-5 w-5"></i>
                            </button>
                            <button type="button" aria-label="Share product" class="flex h-11 w-11 items-center justify-center rounded-full bg-white/90 backdrop-blur-md text-[color:var(--text-primary)] shadow-lg ring-1 ring-black/10 transition hover:text-[color:var(--primary)] active:scale-95">
                                <i data-lucide="send" class="h-5 w-5"></i>
                            </button>
                        </div>
                        <div class="product-gallery-carousel owl-carousel owl-theme">
                            @foreach($galleryImages as $index => $img)
                                <div class="relative aspect-square overflow-hidden cursor-zoom-in"
                                     onclick="openLightbox({{ $index }})">
                                    <img src="{{ asset('images/products/' . $img) }}" 
                                         width="1200"
                                         height="1200"
                                         alt="{{ $product['title'] }}" 
                                         class="h-full w-full object-contain select-none">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    </div>
                    <div class="sm:hidden px-2 mt-2">
                        <div data-mobile-image-track class="relative h-[2px] w-full rounded-full bg-black/15 overflow-hidden">
                            <div data-mobile-image-progress class="absolute left-0 top-0 h-full rounded-full bg-black/80 transition-transform duration-300 ease-out will-change-transform"></div>
                        </div>
                    </div>

                </div>

                <!-- Right: Product Info -->
                <div class="flex flex-col">
                    <div class="pb-4">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[color:var(--primary)]">{{ $product['tagline'] }}</p>
                        <h1 class="mt-2 text-2xl font-semibold tracking-tight text-[color:var(--text-primary)] sm:text-3xl lg:text-4xl leading-tight">
                            {{ $product['title'] }}
                        </h1>
                        
                        <div class="mt-6 flex items-start justify-between gap-3 sm:items-center sm:gap-6">
                            <a href="#reviews" class="flex items-center gap-3 sm:gap-6 group min-w-0">
                                <div class="flex shrink-0 items-center gap-1.5 rounded-full bg-orange-50 px-3 py-1.5 text-orange-600 group-hover:bg-orange-100 transition">
                                    <i data-lucide="star" class="h-5 w-5 fill-current"></i>
                                    <span class="text-sm font-black">{{ $product['rating'] }}</span>
                                </div>
                                <span class="text-[11px] sm:text-sm font-semibold leading-tight text-gray-500 group-hover:text-[color:var(--primary)] transition underline decoration-gray-300 underline-offset-4">{{ number_format($product['reviews']) }} Verified Reviews</span>
                            </a>
                            <span class="inline-flex shrink-0 items-center gap-1.5 text-xs sm:text-sm font-bold text-green-600">
                                <i data-lucide="check-circle" class="h-4 w-4 sm:h-4 sm:w-4"></i>
                                In Stock
                            </span>
                        </div>
                    </div>

                    <div class="py-4">
                        @php
                            $discount = (int) round((1 - ($product['price'] / max(1, $product['mrp']))) * 100);
                        @endphp
                        <!-- Hot Deal Badge -->
                        <div class="inline-flex items-center rounded-md bg-[#008A48] px-3 py-1.5 mb-6">
                            <span class="text-sm font-black uppercase tracking-wider text-white">Hot Deal</span>
                        </div>
                        
                        <div class="flex items-center flex-wrap gap-x-4 gap-y-2 sm:gap-x-6 sm:gap-y-4">
                            <div class="flex items-center text-[#008A48]">
                                <i data-lucide="arrow-down" class="h-5 w-5 sm:h-7 sm:w-7 stroke-[4px]"></i>
                                <span class="text-2xl sm:text-4xl font-semibold">{{ $discount }}%</span>
                            </div>
                            <span class="text-lg sm:text-2xl font-semibold text-gray-400/50 line-through decoration-gray-400/30">₹{{ number_format($product['mrp']) }}</span>
                            <span class="text-3xl sm:text-5xl font-semibold text-[color:var(--text-primary)] tracking-tighter">₹{{ number_format($product['price']) }}</span>
                        </div>
                        <p class="mt-2 text-xs font-bold text-[color:var(--text-muted)] uppercase tracking-wider">Inclusive of all taxes</p>
                        
                        <!-- Professional Minimalist 'Available Offers' (No Code Badges) -->
                        <div class="mt-10 rounded-[1.5rem] bg-gray-50/50 border border-gray-100 overflow-hidden shadow-sm">
                            <button type="button" 
                                    onclick="toggleOffersDropdown()"
                                    class="w-full flex items-center justify-between p-6 bg-white border-b border-gray-100 group/header transition-colors hover:bg-gray-50/30">
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                    <h3 class="text-xl font-bold text-[#1B4B36]">Available Offers</h3>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Buy at</span>
                                        <span class="text-xl font-bold text-[#1B4B36]">₹{{ number_format($product['price'] - 250) }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="bg-[var(--primary-soft)] px-3 py-1.5 rounded-lg flex items-center gap-2 shadow-sm border border-[var(--primary)]/10">
                                        <i data-lucide="tag" class="h-3.5 w-3.5 fill-current text-[color:var(--primary)]"></i>
                                        <span class="text-[10px] font-bold text-[color:var(--primary)]">4 OFFERS</span>
                                    </div>
                                    <i data-lucide="chevron-down" id="offers-chevron" class="h-5 w-5 text-gray-500 transition-transform duration-500"></i>
                                </div>
                            </button>

                            <div id="offers-content" class="p-6 space-y-4" style="display: none;">
                                @php
                                    $premiumOffers = [
                                        [
                                            'title' => 'Flat 10% OFF on your first purchase',
                                            'desc' => 'Enjoy an exclusive welcome discount on your entire cart. No minimum order required.',
                                            'code' => 'WELCOME10'
                                        ],
                                        [
                                            'title' => 'Complimentary Wellness Kit',
                                            'desc' => 'Get a premium sample set of our best-selling products on orders above ₹1,299.',
                                            'code' => 'FREEKIT'
                                        ],
                                        [
                                            'title' => 'Flat ₹250 Instant Cashback',
                                            'desc' => 'Get immediate cashback credited to your wallet on orders above ₹2,499.',
                                            'code' => 'CASH250'
                                        ],
                                        [
                                            'title' => 'Free Express Shipping',
                                            'desc' => 'Unlock zero delivery charges on all prepaid orders placed today.',
                                            'code' => 'FREESHIP'
                                        ]
                                    ];
                                @endphp

                                @foreach($premiumOffers as $offer)
                                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-5 transition-all">
                                        <div class="flex-1">
                                            <h4 class="text-[15px] font-bold text-[#1B4B36] leading-tight mb-2">{{ $offer['title'] }}</h4>
                                            <p class="text-[11px] font-medium text-gray-400 leading-relaxed">{{ $offer['desc'] }}</p>
                                        </div>
                                        <button type="button" 
                                                onclick="applyCoupon('{{ $offer['code'] }}', this)"
                                                class="text-xs font-bold text-[#1B4B36] uppercase tracking-[0.15em] hover:opacity-70 transition-all active:scale-95 self-end sm:self-center">APPLY</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <script>
                            function toggleOffersDropdown() {
                                const content = document.getElementById('offers-content');
                                const chevron = document.getElementById('offers-chevron');
                                
                                if (content.style.display === 'none') {
                                    content.style.display = 'block';
                                    chevron.style.transform = 'rotate(180deg)';
                                } else {
                                    content.style.display = 'none';
                                    chevron.style.transform = 'rotate(0deg)';
                                }
                            }
                            
                            // Initialize state (Closed by default)
                            document.addEventListener('DOMContentLoaded', function() {
                                document.getElementById('offers-content').style.display = 'none';
                            });

                            function applyCoupon(code, btn) {
                                // Simulate applying coupon
                                const originalText = btn.innerText;
                                btn.innerText = 'APPLIED';
                                btn.classList.add('text-green-600');
                                setTimeout(() => {
                                    btn.innerText = originalText;
                                    btn.classList.remove('text-green-600');
                                }, 2000);
                                
                                // Optional: copy to clipboard as well
                                navigator.clipboard.writeText(code);
                            }
                        </script>

                        <!-- Refined Minimalist Delivery Section -->
                        <div class="mt-10 border-t border-black/5 pt-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gray-50 text-gray-400">
                                    <i data-lucide="truck" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold uppercase tracking-widest text-gray-900 leading-none">Delivery details</h3>
                                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mt-2">Check availability & delivery dates</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="relative flex items-center gap-3">
                                    <div class="flex-1 relative">
                                        <input type="text" 
                                               id="pincode-input"
                                               maxlength="6"
                                               placeholder="Enter Pincode"
                                               onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                               class="w-full bg-transparent border-0 border-b-2 border-gray-100 px-0 py-3 text-lg font-bold text-gray-900 focus:outline-none focus:border-[var(--primary)] transition-all placeholder:text-gray-300 placeholder:font-semibold">
                                        <button type="button" 
                                                onclick="checkDelivery()"
                                                class="absolute right-0 top-1/2 -translate-y-1/2 text-sm font-bold text-[color:var(--primary)] uppercase tracking-widest hover:opacity-70 transition-all active:scale-90">Check</button>
                                    </div>
                                    
                                    <!-- Info Icon with Tooltip (Mobile Optimized) -->
                                    <div class="relative group/info">
                                        <button type="button" class="h-8 w-8 flex items-center justify-center rounded-full text-gray-300 hover:text-gray-500 hover:bg-gray-50 transition-all">
                                            <i data-lucide="info" class="h-4 w-4"></i>
                                        </button>
                                        <div class="absolute bottom-full right-0 sm:left-1/2 sm:-translate-x-1/2 mb-3 w-64 p-4 bg-gray-900 text-white rounded-2xl text-[10px] font-bold uppercase tracking-widest leading-relaxed opacity-0 invisible group-hover/info:opacity-100 group-hover/info:visible transition-all shadow-xl z-20">
                                            Enter your 6-digit pincode to check delivery dates and availability for your location.
                                            <div class="absolute top-full right-4 sm:left-1/2 sm:-translate-x-1/2 border-8 border-transparent border-t-gray-900"></div>
                                        </div>
                                    </div>
                                </div>

                                <div id="delivery-info" class="hidden">
                                    <div class="flex items-center gap-4 py-4 px-6 rounded-2xl bg-green-50/50 border border-green-100/50">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                                            <i data-lucide="calendar-days" class="h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">Delivery by {{ date('j M, l', strtotime('+3 days')) }}</p>
                                            <p class="text-[10px] font-semibold text-green-600 uppercase tracking-widest">Free Shipping Available</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            function checkDelivery() {
                                const pincode = document.getElementById('pincode-input').value;
                                if (pincode.length === 6) {
                                    document.getElementById('delivery-info').classList.remove('hidden');
                                } else {
                                    alert('Please enter a valid 6-digit pincode');
                                }
                            }
                        </script>

                        {{-- 
                        <div class="mt-8 relative group/desc">
                            <div id="product-description" class="text-lg leading-relaxed text-[color:var(--text-secondary)] font-medium line-clamp-3 transition-all duration-500">
                                {{ $product['description'] }}
                            </div>
                            <button type="button" 
                                    id="read-more-btn"
                                    onclick="toggleDescription()"
                                    class="mt-2 text-sm font-black text-[color:var(--primary)] hover:underline underline-offset-4 uppercase tracking-widest">
                                Read More
                            </button>
                        </div>
                        --}}

                        {{-- 
                        <script>
                            function checkDescriptionOverflow() {
                                const desc = document.getElementById('product-description');
                                const btn = document.getElementById('read-more-btn');
                                
                                // Temporarily remove line-clamp to check actual height
                                desc.classList.remove('line-clamp-3');
                                const fullHeight = desc.scrollHeight;
                                desc.classList.add('line-clamp-3');
                                
                                // Check if content actually overflows 3 lines (approx 80-90px)
                                if (fullHeight <= desc.offsetHeight + 5) {
                                    btn.style.display = 'none';
                                }
                            }

                            function toggleDescription() {
                                const desc = document.getElementById('product-description');
                                const btn = document.getElementById('read-more-btn');
                                
                                if (desc.classList.contains('line-clamp-3')) {
                                    desc.classList.remove('line-clamp-3');
                                    btn.innerText = 'Read Less';
                                } else {
                                    desc.classList.add('line-clamp-3');
                                    btn.innerText = 'Read More';
                                }
                            }

                            // Run on load
                            window.addEventListener('DOMContentLoaded', checkDescriptionOverflow);
                        </script>
                        --}}


                    </div>


                    <!-- Enhanced Trust Signals -->
                    <div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-6 sm:flex sm:flex-wrap sm:items-center sm:justify-between border-t border-black/5 pt-8">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-green-50 text-green-600 shadow-sm transition-transform hover:scale-110">
                                <i data-lucide="refresh-cw" class="h-6 w-6"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-[0.1em] text-[color:var(--text-primary)] leading-tight">Easy <br> Returns</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-sm transition-transform hover:scale-110">
                                <i data-lucide="banknote" class="h-6 w-6"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-[0.1em] text-[color:var(--text-primary)] leading-tight">Cash On <br> Delivery</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-orange-600 shadow-sm transition-transform hover:scale-110">
                                <i data-lucide="truck" class="h-6 w-6"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-[0.1em] text-[color:var(--text-primary)] leading-tight">Fast <br> Delivery</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 shadow-sm transition-transform hover:scale-110">
                                <i data-lucide="shield-check" class="h-6 w-6"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-[0.1em] text-[color:var(--text-primary)] leading-tight">100% <br> Secure</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Product Information TRUE Table Layout -->
        <section class="py-12 sm:py-20 bg-white">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="overflow-hidden border-t border-black/10">
                    
                    <!-- Table Header Row -->
                    <div class="hidden md:grid grid-cols-3 border-b border-black/10">
                        <div class="p-8 border-r border-black/10">
                            <div class="flex items-center gap-3">
                                <i data-lucide="info" class="h-5 w-5 text-orange-600"></i>
                                <span class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">Description</span>
                            </div>
                        </div>
                        <div class="p-8 border-r border-black/10">
                            <div class="flex items-center gap-3">
                                <i data-lucide="clipboard-list" class="h-5 w-5 text-blue-600"></i>
                                <span class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">Specifications</span>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="flex items-center gap-3">
                                <i data-lucide="building-2" class="h-5 w-5 text-emerald-600"></i>
                                <span class="text-sm font-black uppercase tracking-[0.2em] text-gray-400">Brand Info</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Content Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        
                        <!-- Cell 1: Description -->
                        <div class="p-8 sm:p-10 md:border-r border-black/10">
                            <!-- Mobile Header -->
                            <div class="md:hidden flex items-center gap-3 mb-6 py-2 border-b border-black/5">
                                <i data-lucide="info" class="h-5 w-5 text-orange-600"></i>
                                <span class="text-sm font-black uppercase tracking-widest text-gray-400">Description</span>
                            </div>
                            
                            <p class="text-base sm:text-lg leading-relaxed text-gray-600 font-medium">
                                {{ $product['long_description'] }}
                            </p>
                            <div class="mt-8 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-2 w-2 rounded-full bg-orange-400"></div>
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-500">Clinically Formulated</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="h-2 w-2 rounded-full bg-orange-400"></div>
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-500">100% Vegan & Clean</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cell 2: Specifications -->
                        <div class="p-8 sm:p-10 md:border-r border-black/10">
                            <!-- Mobile Header -->
                            <div class="md:hidden flex items-center gap-3 mb-6 py-2 border-b border-black/5">
                                <i data-lucide="clipboard-list" class="h-5 w-5 text-blue-600"></i>
                                <span class="text-sm font-black uppercase tracking-widest text-gray-400">Specifications</span>
                            </div>

                            <div class="space-y-6">
                                @foreach($product['specs'] as $label => $value)
                                    <div class="flex items-center justify-between border-b border-black/5 pb-4 last:border-0">
                                        <span class="text-xs font-black uppercase tracking-widest text-gray-400">{{ $label }}</span>
                                        <span class="text-base font-black text-gray-800">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Cell 3: Brand Info -->
                        <div class="p-8 sm:p-10">
                            <!-- Mobile Header -->
                            <div class="md:hidden flex items-center gap-3 mb-6 py-2 border-b border-black/5">
                                <i data-lucide="building-2" class="h-5 w-5 text-emerald-600"></i>
                                <span class="text-sm font-black uppercase tracking-widest text-gray-400">Brand Info</span>
                            </div>

                            <div class="space-y-8">
                                <div>
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Marketed By</span>
                                    <p class="mt-2 text-lg font-black text-gray-800">Remenant Health Private Limited</p>
                                    <p class="text-sm font-bold text-gray-500">BKC, Mumbai - 400051</p>
                                </div>
                                <div class="pt-6 border-t border-black/5">
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-400">Customer Support</span>
                                    <p class="mt-2 text-lg font-black text-orange-600">care@remenanthealth.com</p>
                                </div>
                                <div class="inline-flex items-center gap-3 rounded-xl bg-emerald-50 px-5 py-2.5 ring-1 ring-emerald-500/10">
                                    <i data-lucide="shield-check" class="h-5 w-5 text-emerald-600"></i>
                                    <span class="text-xs font-black uppercase tracking-widest text-emerald-700">FSSAI Certified</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>


            {{-- product Highlights --}}
        <!-- Polished Experience Excellence Card -->
        <section class="pt-12 pb-6 sm:pt-28 sm:pb-12 bg-white border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="relative rounded-[2.5rem] sm:rounded-[4rem] bg-[var(--bg-sage)] p-6 sm:p-10 lg:p-24 overflow-hidden shadow-2xl shadow-[var(--bg-sage)]/10">
                    <!-- Premium Background Accents -->
                    <div class="absolute top-0 right-0 h-full w-1/3 bg-white/5 -skew-x-12 translate-x-20"></div>
                    <div class="absolute -top-32 -left-32 h-64 w-64 rounded-full bg-white/20 blur-[120px]"></div>
                    
                    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-16 lg:items-center">
                        <!-- Column 1: Editorial Heading -->
                        <div class="lg:col-span-5">
                            <h2 class="text-5xl sm:text-7xl font-bold tracking-tighter text-[color:var(--text-primary)] leading-[0.9] mb-6">
                                Experience <br> <span class="text-white">Excellence</span>
                            </h2>
                            <div class="h-1.5 w-16 bg-[var(--primary)] rounded-full mb-8 sm:mb-10"></div>
                            <p class="text-xl text-[color:var(--text-primary)]/80 leading-relaxed max-w-md font-medium">Advanced effervescent technology designed to seamlessly integrate into your modern lifestyle.</p>
                        </div>

                        <!-- Column 2 & 3: Content Grid -->
                        <div class="lg:col-span-7">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-16 lg:gap-20">
                                <!-- Highlights Column -->
                                <div class="space-y-12">
                                    <div class="flex gap-6 group">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-[color:var(--primary)] backdrop-blur-sm border border-white/20 transition-transform group-hover:scale-110">
                                            <i data-lucide="zap" class="h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black uppercase tracking-widest mb-2 text-[color:var(--text-primary)]">Maximum Absorption</h4>
                                            <p class="text-xs text-[color:var(--text-primary)]/70 leading-relaxed font-medium">100% Bioavailable Formula for rapid action.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-6 group">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-emerald-600 backdrop-blur-sm border border-white/20 transition-transform group-hover:scale-110">
                                            <i data-lucide="leaf" class="h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black uppercase tracking-widest mb-2 text-[color:var(--text-primary)]">Pure Ingredients</h4>
                                            <p class="text-xs text-[color:var(--text-primary)]/70 leading-relaxed font-medium">Clean Label Certified with no additives.</p>
                                        </div>
                                    </div>
                                    <div class="flex gap-6 group">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/10 text-rose-600 backdrop-blur-sm border border-white/20 transition-transform group-hover:scale-110">
                                            <i data-lucide="ban" class="h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black uppercase tracking-widest mb-2 text-[color:var(--text-primary)]">Zero Compromise</h4>
                                            <p class="text-xs text-[color:var(--text-primary)]/70 leading-relaxed font-medium">No Sugar & No Artificial Colors used.</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Ritual Column (Timeline) -->
                                <div class="relative pl-8">
                                    <div class="absolute left-0 top-2 bottom-2 w-px bg-white/20"></div>
                                    <div class="space-y-12">
                                        <div class="relative group/step">
                                            <div class="absolute -left-10 top-0 h-4 w-4 rounded-full bg-[var(--primary)] border-4 border-[var(--bg-sage)] z-10 transition-transform group-hover/step:scale-125"></div>
                                            <h4 class="text-xs font-black uppercase tracking-widest mb-2 text-[color:var(--text-primary)]">01. Drop it</h4>
                                            <p class="text-xs text-[color:var(--text-primary)]/70 font-medium">Dissolve one tablet in 200ml water.</p>
                                        </div>
                                        <div class="relative group/step">
                                            <div class="absolute -left-10 top-0 h-4 w-4 rounded-full bg-white/40 border-4 border-[var(--bg-sage)] z-10 transition-transform group-hover/step:scale-125 group-hover/step:bg-white"></div>
                                            <h4 class="text-xs font-black uppercase tracking-widest mb-2 text-[color:var(--text-primary)]">02. Fizz it</h4>
                                            <p class="text-xs text-[color:var(--text-primary)]/70 font-medium">Watch the pure formulation dissolve.</p>
                                        </div>
                                        <div class="relative group/step">
                                            <div class="absolute -left-10 top-0 h-4 w-4 rounded-full bg-white/40 border-4 border-[var(--bg-sage)] z-10 transition-transform group-hover/step:scale-125 group-hover/step:bg-white"></div>
                                            <h4 class="text-xs font-black uppercase tracking-widest mb-2 text-[color:var(--text-primary)]">03. Fuel Up</h4>
                                            <p class="text-xs text-[color:var(--text-primary)]/70 font-medium">Drink and take on your day.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section id="reviews" class="pt-6 pb-12 sm:pt-12 sm:pb-24 bg-[var(--bg-main)] scroll-mt-24">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="flex flex-col lg:flex-row gap-16">
                    <!-- Left: Rating Summary (Sticky) -->
                    <div class="lg:w-1/3 lg:self-start lg:sticky lg:top-28 h-fit">
                        <div class="p-8 sm:p-10 rounded-[2.5rem] bg-white shadow-sm ring-1 ring-black/5">
                            <h2 class="text-xl sm:text-2xl font-semibold tracking-tight text-[color:var(--text-primary)]">Verified Reviews</h2>
                            <div class="mt-8 flex items-end gap-5">
                                <span class="text-6xl font-bold leading-none text-[color:var(--text-primary)]">{{ $product['rating'] }}</span>
                                <div class="flex flex-col gap-1 pb-1">
                                    <div class="flex text-orange-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <i data-lucide="star" class="h-5 w-5 fill-current"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-medium text-[color:var(--text-secondary)]">Based on {{ number_format($product['reviews']) }} reviews</span>
                                </div>
                            </div>

                            <!-- Rating Bars -->
                            <div class="mt-10 space-y-4">
                                @php
                                    $ratings = [
                                        ['stars' => 5, 'count' => 850],
                                        ['stars' => 4, 'count' => 100],
                                        ['stars' => 3, 'count' => 20],
                                        ['stars' => 2, 'count' => 8],
                                        ['stars' => 1, 'count' => 4],
                                    ];
                                @endphp
                                @foreach($ratings as $r)
                                    <div class="flex items-center gap-4">
                                        <span class="w-4 text-xs font-semibold text-[color:var(--text-secondary)]">{{ $r['stars'] }}</span>
                                        <i data-lucide="star" class="h-3 w-3 text-orange-400 fill-current"></i>
                                        <div class="flex-1 h-1.5 rounded-full bg-black/5 overflow-hidden">
                                            <div class="h-full bg-orange-400 rounded-full" style="width: {{ ($r['count'] / 982) * 100 }}%"></div>
                                        </div>
                                        <span class="w-12 text-xs font-medium text-[color:var(--text-muted)] text-right">{{ $r['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" onclick="openWriteReviewModal()" class="mt-10 w-full rounded-2xl bg-[var(--primary)] py-4 text-sm font-semibold tracking-wide text-white shadow-lg shadow-[var(--primary)]/15 hover:bg-[var(--primary-hover)] transition active:scale-95 flex items-center justify-center gap-2">
                                <i data-lucide="pencil" class="h-4 w-4"></i>
                                Write a Review
                            </button>
                        </div>
                    </div>

                    <!-- Right: Review List -->
                    <div class="flex-1 space-y-8">
                        <!-- Filters -->
                        <div class="flex items-center justify-between border-b border-black/5 pb-6">
                            <h3 class="text-base font-semibold text-[color:var(--text-secondary)]">Most Relevant</h3>
                        </div>
                        @php
                            $sampleReviews = [
                                [
                                    'name' => 'Aditi Sharma',
                                    'date' => '2 days ago',
                                    'rating' => 5,
                                    'title' => 'Truly Refreshing!',
                                    'content' => 'I’ve been taking these for a month now and I can definitely feel the difference. It’s so much easier than swallowing big pills and the taste is amazing!',
                                    'verified' => true,
                                    'images' => ['remenant-product1.jpg', 'remenant-product13.jpg']
                                ],
                                [
                                    'name' => 'Rohan Gupta',
                                    'date' => '1 week ago',
                                    'rating' => 5,
                                    'title' => 'Best Wellness Product',
                                    'content' => 'Highly recommend for anyone with a busy lifestyle. Quick, easy, and effective. The Apple Cider Vinegar flavor is my personal favorite.',
                                    'verified' => true,
                                    'images' => ['remenant-product10.jpg']
                                ],
                                [
                                    'name' => 'Karan Patel',
                                    'date' => '2 weeks ago',
                                    'rating' => 4,
                                    'title' => 'Great but slightly sweet',
                                    'content' => 'The quality is top-notch and it fizzes perfectly. Just wish it was a tiny bit less sweet, but overall a great product that I will buy again.',
                                    'verified' => true,
                                    'images' => ['remenant-product5.jpg', 'remenant-product7.jpg']
                                ]
                            ];

                        @endphp

                        @foreach($sampleReviews as $review)
                            @php 
                                $reviewImages = array_map(fn($img) => asset('images/products/' . $img), $review['images'] ?? []);
                            @endphp
                            <div class="bg-white p-5 sm:p-8 rounded-3xl shadow-sm ring-1 ring-black/[0.03] transition-all duration-300">
                                <div class="flex flex-col gap-4">
                                    <!-- Review Top: Rating & Title -->
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 rounded bg-[var(--primary)] px-1.5 py-0.5 text-xs font-bold text-white">
                                            <span class="text-white">{{ $review['rating'] }}</span>
                                            <i data-lucide="star" class="h-3 w-3 fill-current"></i>
                                        </div>
                                        <h5 class="text-base font-semibold text-[color:var(--text-primary)] tracking-tight">{{ $review['title'] }}</h5>
                                    </div>

                                    <!-- Review Content -->
                                    <p class="text-sm sm:text-base leading-relaxed text-[color:var(--text-secondary)]">
                                        {{ Str::limit($review['content'], 120) }}
                                        @if(strlen($review['content']) > 120)
                                            <a href="{{ route('products.reviews', $product['slug']) }}" class="text-[color:var(--primary)] font-semibold hover:underline ml-1">Read more</a>
                                        @endif
                                    </p>

                                    <!-- Review Images -->
                                    @if(!empty($review['images']))
                                        <div class="flex flex-wrap gap-2 pt-1">
                                            @foreach($review['images'] as $imgIndex => $img)
                                                <div class="group/img relative h-16 w-16 sm:h-20 sm:w-20 overflow-hidden rounded-xl bg-gray-50 ring-1 ring-black/5 cursor-zoom-in"
                                                     onclick="openReviewLightbox({{ json_encode($reviewImages) }}, {{ $imgIndex }})">
                                                    <img src="{{ asset('images/products/' . $img) }}" 
                                                         alt="User review image" 
                                                         class="h-full w-full object-cover">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Reviewer Info & Interactions -->
                                    <div class="flex items-center justify-between pt-4 border-t border-black/[0.03]">
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-[color:var(--text-primary)]">{{ $review['name'] }}</span>
                                                @if($review['verified'])
                                                    <div class="flex items-center gap-1 text-[10px] font-bold text-gray-400">
                                                        <i data-lucide="check-circle-2" class="h-3.5 w-3.5 text-[color:var(--primary)]"></i>
                                                        Certified Buyer
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="text-[10px] font-medium text-gray-400">{{ $review['date'] }}</span>
                                        </div>

                                        <!-- Like/Dislike Icons Only -->
                                        <div class="flex items-center gap-4">
                                            <button type="button" class="group flex items-center gap-1.5 text-gray-400 hover:text-[color:var(--primary)] transition-colors">
                                                <i data-lucide="thumbs-up" class="h-4 w-4 transition-transform group-active:scale-125"></i>
                                                <span class="text-[10px] font-bold">12</span>
                                            </button>
                                            <button type="button" class="group flex items-center gap-1.5 text-gray-400 hover:text-red-500 transition-colors">
                                                <i data-lucide="thumbs-down" class="h-4 w-4 transition-transform group-active:scale-125"></i>
                                                <span class="text-[10px] font-bold">2</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Clean Simple Button -->
                        <div class="mt-12 flex justify-center">
                            <a href="{{ route('products.reviews', $product['slug']) }}" class="inline-flex items-center justify-center px-10 py-3.5 rounded-full border border-orange-200 text-sm font-semibold text-[color:var(--primary)] transition-all duration-300 hover:bg-orange-50 hover:border-orange-300 active:scale-95">
                                Read All {{ number_format($product['reviews']) }} Reviews
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Products -->
        <section class="pt-20 pb-32 border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <h2 class="text-3xl font-bold tracking-tight text-[color:var(--text-primary)]">You may also like</h2>
                        <p class="mt-2 text-[color:var(--text-secondary)] font-semibold">Complete your wellness routine.</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="hidden sm:inline-flex rounded-full bg-orange-50 text-[color:var(--primary)] px-8 py-3 text-xs font-black uppercase tracking-widest hover:bg-orange-100 transition ring-1 ring-orange-100">View All Collections</a>
                </div>

                <div class="related-carousel owl-carousel owl-theme" style="padding: 8px 0;">
                    @foreach ($relatedProducts as $rp)
                        @php
                            $discount = (int) round((1 - ($rp['price'] / max(1, $rp['mrp']))) * 100);
                        @endphp
                        <div class="product-card group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5 hover:shadow-md transition">
                            <a href="{{ route('products.show', $rp['slug']) }}" class="absolute inset-0 z-[5]"></a>
                            <button type="button"
                                class="absolute right-3 top-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 ring-1 ring-black/10 hover:bg-white transition"
                                aria-label="Add to wishlist">
                                <i data-lucide="heart" class="h-5 w-5 text-[color:var(--text-primary)]"></i>
                            </button>

                            <div class="relative aspect-square overflow-hidden bg-[var(--bg-section)]">
                                <img src="{{ asset('images/products/' . $rp['image']) }}" alt="{{ $rp['title'] }}"
                                    class="h-full w-full object-contain" loading="lazy">
                                @if($discount > 0)
                                <div class="absolute left-3 top-3 rounded-full bg-[var(--primary)] px-3 py-1 text-xs font-extrabold text-white">
                                    -{{ $discount }}%
                                </div>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-4">
                                <p class="text-xs font-bold tracking-wide text-[color:var(--primary)] uppercase">
                                    {{ $rp['tagline'] }}
                                </p>
                                <h3 class="mt-1 text-[color:var(--text-primary)] line-clamp-2 min-h-[2.5rem]">{{ $rp['title'] }}</h3>

                                <div class="mt-3 flex items-center justify-between gap-3">
                                    <div class="flex items-baseline gap-2">
                                        <p class="text-lg font-bold text-[color:var(--primary)]">
                                            ₹{{ number_format($rp['price']) }}</p>
                                        @if($rp['mrp'] > $rp['price'])
                                        <p class="text-xs font-medium text-[color:var(--text-muted)] line-through">
                                            ₹{{ number_format($rp['mrp']) }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 rounded-full bg-black/5 px-2 py-1 text-xs font-semibold text-[color:var(--text-secondary)]">
                                        <i data-lucide="star" class="h-4 w-4 fill-yellow-400 text-yellow-400"></i>
                                        {{ number_format($rp['rating'], 1) }} ({{ number_format($rp['reviews'] ?? 0) }})
                                    </div>
                                </div>

                                <div class="mt-auto pt-3 relative z-10">
                                    <a href="{{ route('products.show', $rp['slug']) }}"
                                        class="block w-full text-center rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-extrabold text-white hover:opacity-95 transition">
                                        Add to cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <!-- Sticky Bottom Bar (Always Visible) -->
    <div class="fixed bottom-0 left-0 right-0 z-[60] bg-white/90 backdrop-blur-xl border-t border-black/5 p-3 sm:p-4 sm:px-6 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
        <div class="mx-auto max-w-[1600px] flex items-center justify-between gap-8 px-4 sm:px-6 lg:px-12">
            <!-- Product Info (Desktop Only) -->
            <div class="hidden md:flex items-center gap-4">
                <img src="{{ asset('images/products/' . $product['image']) }}" alt="{{ $product['title'] }}" class="h-12 w-12 rounded-lg object-cover">
                <div>
                    <h4 class="text-sm font-semibold text-[color:var(--text-primary)] line-clamp-1">{{ $product['title'] }}</h4>
                    <p class="text-xs font-bold text-[color:var(--primary)]">₹{{ number_format($product['price']) }}</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-1 items-center gap-2 sm:gap-3 max-w-[600px] lg:flex-none lg:w-[450px]">
                <a href="{{ route('cart') }}" class="flex-1 h-11 sm:h-12 flex items-center justify-center rounded-xl sm:rounded-2xl bg-[var(--primary)] text-white font-bold uppercase tracking-[0.1em] text-[10px] sm:text-xs shadow-lg shadow-[var(--primary)]/20 active:scale-95 transition hover:brightness-105">
                    Add to Cart
                </a>
                <button type="button" class="flex-1 h-11 sm:h-12 rounded-xl sm:rounded-2xl bg-[var(--secondary)] text-white font-bold uppercase tracking-[0.1em] text-[10px] sm:text-xs shadow-lg shadow-[var(--secondary)]/20 active:scale-95 transition hover:brightness-105">
                    Buy It Now
                </button>
            </div>
        </div>
    </div>

    <!-- Image Lightbox Modal -->
    <div id="lightbox-modal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center bg-[#0a1a0f]/95 backdrop-blur-sm transition-all duration-300 p-3 sm:p-6">
        <!-- Close Button -->
        <button type="button" data-lightbox-close class="absolute right-3 top-3 sm:right-6 sm:top-6 z-[110] flex h-11 w-11 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-black/40 backdrop-blur-md text-white hover:bg-red-500 transition-all duration-300 shadow-2xl ring-1 ring-white/20">
            <i data-lucide="x" class="h-6 w-6"></i>
        </button>

        <!-- Navigation -->
        <button type="button" data-lightbox-prev class="lightbox-nav-btn absolute left-3 sm:left-6 md:left-10 top-1/2 -translate-y-1/2 z-[120] flex h-10 w-10 sm:h-16 sm:w-16 items-center justify-center rounded-full bg-white/95 backdrop-blur-xl text-black transition-all duration-500 group shadow-[0_10px_40px_rgba(0,0,0,0.2)] ring-1 ring-black/5 hover:bg-black hover:text-white active:scale-90">
            <i data-lucide="chevron-left" class="h-5 w-5 sm:h-8 sm:w-8 transition-transform duration-500 group-hover:-translate-x-1"></i>
        </button>
        <button type="button" data-lightbox-next class="lightbox-nav-btn absolute right-3 sm:right-6 md:right-10 top-1/2 -translate-y-1/2 z-[120] flex h-10 w-10 sm:h-16 sm:w-16 items-center justify-center rounded-full bg-white/95 backdrop-blur-xl text-black transition-all duration-500 group shadow-[0_10px_40px_rgba(0,0,0,0.2)] ring-1 ring-black/5 hover:bg-black hover:text-white active:scale-90">
            <i data-lucide="chevron-right" class="h-5 w-5 sm:h-8 sm:w-8 transition-transform duration-500 group-hover:translate-x-1"></i>
        </button>

        <!-- Main Image Carousel -->
        <div class="relative h-full w-full flex items-center justify-center">
            <div class="lightbox-frame">
                <div class="lightbox-carousel owl-carousel owl-theme h-full w-full" data-gallery-type="product">
                @foreach($galleryImages as $img)
                    <div class="relative flex h-full w-full items-center justify-center" data-lightbox-slide>
                        <div class="absolute inset-0 flex items-center justify-center" data-image-loader>
                            <x-dot-spinner class="[--uib-size:2.6rem] [--uib-color:#ffffff]" />
                        </div>
                        <img src="{{ asset('images/products/' . $img) }}" alt="{{ $product['title'] }}" data-lightbox-image class="h-full w-full object-contain select-none">
                    </div>
                @endforeach
                </div>
            </div>
        </div>

        <!-- Counter hidden as requested -->
        <div class="absolute bottom-4 sm:bottom-8 left-1/2 -translate-x-1/2 z-[110] hidden">
            <p class="rounded-full bg-black/40 px-3 py-1 text-xs sm:text-sm font-black tracking-widest text-white/70 uppercase">
                <span id="lightbox-current" class="text-white">1</span> / <span id="lightbox-total">1</span>
            </p>
        </div>
    </div>

    <!-- Write a Review Modal -->
    <div id="review-modal" class="fixed inset-0 z-[100] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeWriteReviewModal()"></div>
        <!-- Scroll Wrapper - this is what actually scrolls on desktop -->
        <div class="relative z-10 w-full h-full overflow-y-auto flex items-center justify-center px-3 py-6 sm:p-6">
            <div id="review-modal-card" class="relative w-full max-w-lg md:max-w-2xl bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden animate-[modalSlideUp_0.35s_ease-out] my-auto flex flex-col" style="max-height: calc(100vh - 3rem);">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-5 sm:px-6 pt-5 sm:pt-6 pb-4 border-b border-gray-100 shrink-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Write a Review</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Share your experience with this product</p>
                    </div>
                    <button type="button" onclick="closeWriteReviewModal()" class="h-9 w-9 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <!-- Modal Body - scrollable, 2-col on desktop -->
                <div class="px-5 sm:px-6 py-5 flex-1 min-h-0 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: Rating, Title, Review -->
                        <div class="space-y-5">
                            <!-- Star Rating -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Your Rating</label>
                                <div class="flex items-center gap-1" id="review-star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" data-star="{{ $i }}" onclick="setReviewRating({{ $i }})" class="review-star p-0.5 text-gray-300 hover:text-orange-400 transition-colors duration-150">
                                            <i data-lucide="star" class="h-7 w-7"></i>
                                        </button>
                                    @endfor
                                    <span id="rating-label" class="ml-3 text-xs font-medium text-gray-400">Select a rating</span>
                                </div>
                            </div>

                            <!-- Review Title -->
                            <div>
                                <label for="review-title" class="text-sm font-medium text-gray-700 mb-1.5 block">Review Title</label>
                                <input type="text" id="review-title" placeholder="Sum up your experience in a few words" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder:text-gray-400 transition" style="outline:none !important; box-shadow:none !important;">
                            </div>

                            <!-- Review Content -->
                            <div>
                                <label for="review-content" class="text-sm font-medium text-gray-700 mb-1.5 block">Your Review</label>
                                <textarea id="review-content" rows="4" placeholder="What did you like or dislike about this product?" 
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder:text-gray-400 transition resize-none" style="outline:none !important; box-shadow:none !important;"></textarea>
                            </div>
                        </div>

                        <!-- Right Column: Image Upload -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-700 mb-1.5 block">Add Photos <span class="text-gray-400 font-normal">(optional)</span></label>
                            <div id="review-upload-zone" class="relative border-2 border-dashed border-gray-200 rounded-xl p-5 sm:p-6 text-center cursor-pointer hover:border-[var(--primary)]/40 hover:bg-orange-50/30 transition-all duration-200 group flex-1 flex items-center justify-center min-h-[180px]">
                                <input type="file" id="review-images" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="handleReviewImageUpload(this)">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-orange-100 group-hover:text-[var(--primary)] transition">
                                        <i data-lucide="camera" class="h-6 w-6"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Click to upload photos</p>
                                        <p class="text-[10px] text-gray-400 mt-1">JPG, PNG up to 5MB · Max 4 photos</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Image Preview Grid -->
                            <div id="review-image-previews" class="flex flex-wrap gap-2 mt-3 hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 bg-gray-50/50 shrink-0">
                    <button type="button" onclick="submitReview()" class="w-full py-3.5 rounded-xl bg-[var(--primary)] text-white text-sm font-semibold hover:bg-[var(--primary-hover)] transition active:scale-[0.98] shadow-lg shadow-[var(--primary)]/15 flex items-center justify-center gap-2">
                        Submit Review
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Hide counter specifically */
        #lightbox-current, #lightbox-total { display: none !important; }
        .lightbox-nav-btn { z-index: 120 !important; }

        /* Allow related carousel cards to show fully */
        .related-carousel .owl-stage-outer { overflow: visible !important; }
        .related-carousel { overflow: hidden; }

        /* Review modal: strip all focus outlines */
        #review-modal input:focus,
        #review-modal textarea:focus {
            outline: none !important;
            box-shadow: none !important;
            border-color: #d1d5db !important;
        }

        /* Review modal animation */
        @keyframes modalSlideUp {
            from { opacity: 0; transform: translateY(24px) scale(0.97); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .review-star.active svg {
            fill: currentColor;
        }
        #review-image-previews .preview-thumb {
            position: relative;
            width: 72px;
            height: 72px;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.06);
        }
        #review-image-previews .preview-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #review-image-previews .preview-thumb .remove-btn {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(0,0,0,0.55);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 11px;
            line-height: 1;
            transition: background 0.15s;
        }
        #review-image-previews .preview-thumb .remove-btn:hover {
            background: rgba(220,38,38,0.85);
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        const gallery = [
            @foreach($galleryImages as $img)
                '{{ asset("images/products/" . $img) }}',
            @endforeach
        ];
        
        $(document).ready(function(){
            if(window.lucide) window.lucide.createIcons();
            const $gallery = $(".product-gallery-carousel");
            const $lightbox = $(".lightbox-carousel");
            const $modal = $("#lightbox-modal");
            const $currentCounter = $("#lightbox-current");
            const $totalCounter = $("#lightbox-total");
            const $galleryPrev = $("[data-gallery-prev]");
            const $galleryNext = $("[data-gallery-next]");
            const $lightboxPrev = $("[data-lightbox-prev]");
            const $lightboxNext = $("[data-lightbox-next]");
            const $mobileImageTrack = $("[data-mobile-image-track]");
            const $mobileImageProgress = $("[data-mobile-image-progress]");
            const $lightboxImages = $("[data-lightbox-image]");
            let currentImageIndex = 0;
            let lockedScrollY = 0;
            $totalCounter.text(gallery.length);

            function setNavDisabled($btn, isDisabled) {
                $btn.toggleClass("!opacity-0 !pointer-events-none invisible", isDisabled);
            }

            function updateGalleryNavState(index) {
                setNavDisabled($galleryPrev, index <= 0);
                setNavDisabled($galleryNext, index >= gallery.length - 1);
            }

            function updateLightboxNavState(index) {
                const total = $lightbox.find('.owl-item:not(.cloned)').length;
                setNavDisabled($lightboxPrev, index <= 0);
                setNavDisabled($lightboxNext, index >= total - 1);
            }

            function updateMobileImageProgress(index) {
                if (!$mobileImageProgress.length || !$mobileImageTrack.length || gallery.length <= 0) return;
                const trackWidth = $mobileImageTrack.width() || 0;
                if (trackWidth <= 0) return;

                const segmentWidth = trackWidth / gallery.length;
                const indicatorWidth = Math.max(segmentWidth, 24);
                const maxOffset = Math.max(trackWidth - indicatorWidth, 0);
                const targetOffset = Math.min(segmentWidth * index, maxOffset);

                $mobileImageProgress.css({
                    width: `${indicatorWidth}px`,
                    transform: `translateX(${targetOffset}px)`
                });
            }

            function hideImageLoader(imgEl) {
                const $slide = $(imgEl).closest("[data-lightbox-slide]");
                $slide.find("[data-image-loader]").addClass("hidden");
            }

            function initLightboxImageLoaders() {
                $lightboxImages.each(function() {
                    const img = this;
                    if (img.complete && img.naturalWidth > 0) {
                        hideImageLoader(img);
                        return;
                    }

                    $(img).one("load error", function() {
                        hideImageLoader(img);
                    });
                });
            }

            function lockBackgroundScroll() {
                lockedScrollY = window.scrollY || window.pageYOffset || 0;
                $("body").css({
                    position: "fixed",
                    top: `-${lockedScrollY}px`,
                    left: "0",
                    right: "0",
                    width: "100%",
                    overflow: "hidden"
                });
            }

            function unlockBackgroundScroll() {
                $("body").css({
                    position: "",
                    top: "",
                    left: "",
                    right: "",
                    width: "",
                    overflow: ""
                });
                window.scrollTo(0, lockedScrollY);
            }
            
            $gallery.owlCarousel({
                items: 1,
                loop: false,
                dots: false,
                nav: false,
                smartSpeed: 800,
                onChanged: function(event) {
                    const index = event.item.index;
                    currentImageIndex = index;
                    $currentCounter.text(index + 1);
                    $(".product-other-image-thumb")
                        .removeClass("border-[var(--primary)]")
                        .addClass("border-transparent");
                    $(`.product-other-image-thumb[data-index="${index}"]`)
                        .removeClass("border-transparent hover:border-[var(--primary)]/70")
                        .addClass("border-[var(--primary)]");
                    updateGalleryNavState(index);
                    updateMobileImageProgress(index);
                }
            });

            $lightbox.owlCarousel({
                items: 1,
                loop: false,
                dots: false,
                nav: false,
                smartSpeed: 500,
                mouseDrag: true,
                touchDrag: true,
                pullDrag: true,
                onChanged: function(event) {
                    if (event.item) {
                        const index = event.item.index;
                        currentImageIndex = index;
                        $currentCounter.text(index + 1);
                        if ($lightbox.attr('data-gallery-type') === 'product') {
                            $gallery.trigger("to.owl.carousel", [index, 200, true]);
                        }
                        updateLightboxNavState(index);
                    }
                }
            });

            initLightboxImageLoaders();

            $(".product-other-image-thumb").on("click", function() {
                const index = $(this).data("index");
                $gallery.trigger("to.owl.carousel", [index, 300]);
            });

            $galleryPrev.on("click", function() {
                $gallery.trigger("prev.owl.carousel");
            });

            $galleryNext.on("click", function() {
                $gallery.trigger("next.owl.carousel");
            });

            $lightboxPrev.on("click", function() {
                $lightbox.trigger("prev.owl.carousel");
            });

            $lightboxNext.on("click", function() {
                $lightbox.trigger("next.owl.carousel");
            });

            $("[data-lightbox-close]").on("click", function() {
                $modal.addClass("hidden").removeClass("flex");
                unlockBackgroundScroll();
            });

            window.openLightbox = function(index) {
                openDynamicLightbox(gallery, index, true);
            };

            window.openDynamicLightbox = function(images, index, isProductGallery = false) {
                const galleryType = isProductGallery ? 'product' : 'reviews';
                const currentType = $lightbox.attr('data-gallery-type');
                
                currentImageIndex = index;
                
                // Show modal first
                $modal.removeClass("hidden").addClass("flex");
                lockBackgroundScroll();

                setTimeout(() => {
                    // Only rebuild if the gallery type has changed
                    if (currentType !== galleryType) {
                        if (currentType) {
                            $lightbox.trigger('destroy.owl.carousel');
                            $lightbox.empty();
                            $lightbox.removeClass('owl-loaded owl-drag owl-hidden');
                        }
                        
                        // Batch append for performance
                        let html = '';
                        images.forEach(src => {
                            html += `
                                <div class="relative flex h-full w-full items-center justify-center" data-lightbox-slide>
                                    <img src="${src}" class="h-full w-full object-contain select-none" loading="lazy">
                                </div>
                            `;
                        });
                        $lightbox.append(html);
                        $lightbox.attr('data-gallery-type', galleryType);
                        
                        $totalCounter.text(images.length);
                        
                        $lightbox.owlCarousel({
                            items: 1,
                            loop: false,
                            dots: false,
                            nav: false,
                            smartSpeed: 400,
                            mouseDrag: true,
                            touchDrag: true,
                            pullDrag: true,
                            onChanged: function(event) {
                                if (event.item) {
                                    const idx = event.item.index;
                                    currentImageIndex = idx;
                                    $currentCounter.text(idx + 1);
                                    if ($lightbox.attr('data-gallery-type') === 'product') {
                                        $gallery.trigger("to.owl.carousel", [idx, 200, true]);
                                    }
                                    updateLightboxNavState(idx);
                                }
                            }
                        });
                    } else {
                        // Crucial fix: refresh carousel to correct dimensions after changing to flex
                        $lightbox.trigger('refresh.owl.carousel');
                    }
                    
                    // Go to the specific image
                    $lightbox.trigger('to.owl.carousel', [index, 0]);
                    $currentCounter.text(index + 1);
                    updateLightboxNavState(index);
                    
                    if (window.lucide) lucide.createIcons();
                }, 10);
            };

            // Review-specific lightbox: always rebuild since each card has different images
            window.openReviewLightbox = function(images, index) {
                currentImageIndex = index;
                
                $modal.removeClass("hidden").addClass("flex");
                lockBackgroundScroll();

                setTimeout(() => {
                    // Always destroy and rebuild for review images
                    const currentType = $lightbox.attr('data-gallery-type');
                    if (currentType) {
                        $lightbox.trigger('destroy.owl.carousel');
                        $lightbox.empty();
                        $lightbox.removeClass('owl-loaded owl-drag owl-hidden');
                    }
                    
                    let html = '';
                    images.forEach(src => {
                        html += `
                            <div class="relative flex h-full w-full items-center justify-center" data-lightbox-slide>
                                <img src="${src}" class="h-full w-full object-contain select-none" loading="lazy">
                            </div>
                        `;
                    });
                    $lightbox.append(html);
                    $lightbox.attr('data-gallery-type', 'reviews');
                    
                    $totalCounter.text(images.length);
                    
                    $lightbox.owlCarousel({
                        items: 1,
                        loop: false,
                        dots: false,
                        nav: false,
                        smartSpeed: 400,
                        mouseDrag: true,
                        touchDrag: true,
                        pullDrag: true,
                        onChanged: function(event) {
                            if (event.item) {
                                const idx = event.item.index;
                                currentImageIndex = idx;
                                $currentCounter.text(idx + 1);
                                updateLightboxNavState(idx);
                            }
                        }
                    });
                    
                    $lightbox.trigger('to.owl.carousel', [index, 0]);
                    $currentCounter.text(index + 1);
                    updateLightboxNavState(index);
                    
                    if (window.lucide) lucide.createIcons();
                }, 10);
            };

            window.closeLightbox = function() {
                $modal.addClass("hidden").removeClass("flex");
                unlockBackgroundScroll();
            };

            $(document).on("keydown", function(e) {
                if ($modal.hasClass("hidden")) return;
                if (e.key === "Escape") window.closeLightbox();
                if (e.key === "ArrowRight") $lightbox.trigger("next.owl.carousel");
                if (e.key === "ArrowLeft") $lightbox.trigger("prev.owl.carousel");
            });

            $(".related-carousel").owlCarousel({
                items: 1,
                margin: 16,
                loop: false,
                autoplay: false,
                nav: false,
                dots: false,
                responsive: {
                    0: { items: 1.2, stagePadding: 20 },
                    640: { items: 2 },
                    1024: { items: 4 }
                }
            });

            updateGalleryNavState(0);
            updateLightboxNavState(0);
            updateMobileImageProgress(0);
            $(window).on("resize", function() {
                updateMobileImageProgress(currentImageIndex);
            });
        });

        // === Write a Review Modal ===
        let selectedRating = 0;
        let reviewUploadedFiles = [];
        const ratingLabels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
        const $reviewModal = $('#review-modal');

        let reviewModalScrollY = 0;

        window.openWriteReviewModal = function() {
            selectedRating = 0;
            reviewUploadedFiles = [];
            $('#review-title').val('');
            $('#review-content').val('');
            $('#review-image-previews').empty().addClass('hidden');
            $('#rating-label').text('Select a rating').removeClass('text-orange-500');
            $('.review-star').removeClass('active text-orange-400').addClass('text-gray-300');
            // Lock background scroll (works on iOS Safari too)
            reviewModalScrollY = window.scrollY || window.pageYOffset || 0;
            $('body').css({
                position: 'fixed',
                top: `-${reviewModalScrollY}px`,
                left: '0',
                right: '0',
                width: '100%',
                overflow: 'hidden'
            });
            $reviewModal.removeClass('hidden');
            if (window.lucide) lucide.createIcons();
        };

        window.closeWriteReviewModal = function() {
            $reviewModal.addClass('hidden');
            // Unlock background scroll
            $('body').css({
                position: '',
                top: '',
                left: '',
                right: '',
                width: '',
                overflow: ''
            });
            window.scrollTo(0, reviewModalScrollY);
        };




        // Close on Escape
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && !$reviewModal.hasClass('hidden')) {
                closeWriteReviewModal();
            }
        });

        window.setReviewRating = function(rating) {
            selectedRating = rating;
            $('.review-star').each(function() {
                const starVal = parseInt($(this).data('star'));
                if (starVal <= rating) {
                    $(this).addClass('active text-orange-400').removeClass('text-gray-300');
                } else {
                    $(this).removeClass('active text-orange-400').addClass('text-gray-300');
                }
            });
            $('#rating-label').text(ratingLabels[rating]).addClass('text-orange-500').removeClass('text-gray-400');
        };

        window.handleReviewImageUpload = function(input) {
            const files = Array.from(input.files);
            const maxFiles = 4;
            const maxSize = 5 * 1024 * 1024; // 5MB

            files.forEach(file => {
                if (reviewUploadedFiles.length >= maxFiles) return;
                if (file.size > maxSize) return;
                if (!file.type.startsWith('image/')) return;
                
                reviewUploadedFiles.push(file);
                const reader = new FileReader();
                reader.onload = function(e) {
                    const idx = reviewUploadedFiles.length - 1;
                    const thumb = `
                        <div class="preview-thumb" data-file-index="${idx}">
                            <img src="${e.target.result}" alt="Preview">
                            <div class="remove-btn" onclick="removeReviewImage(${idx})">✕</div>
                        </div>
                    `;
                    const $previews = $('#review-image-previews');
                    $previews.append(thumb).removeClass('hidden');
                };
                reader.readAsDataURL(file);
            });

            // Reset input so same file can be re-selected
            input.value = '';
        };

        window.removeReviewImage = function(index) {
            reviewUploadedFiles.splice(index, 1);
            renderReviewPreviews();
        };

        function renderReviewPreviews() {
            const $previews = $('#review-image-previews');
            $previews.empty();
            if (reviewUploadedFiles.length === 0) {
                $previews.addClass('hidden');
                return;
            }
            reviewUploadedFiles.forEach((file, idx) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $previews.append(`
                        <div class="preview-thumb" data-file-index="${idx}">
                            <img src="${e.target.result}" alt="Preview">
                            <div class="remove-btn" onclick="removeReviewImage(${idx})">✕</div>
                        </div>
                    `);
                };
                reader.readAsDataURL(file);
            });
            $previews.removeClass('hidden');
        }

        window.submitReview = function() {
            if (selectedRating === 0) {
                $('#rating-label').text('Please select a rating').css('color', '#ef4444');
                return;
            }
            const title = $('#review-title').val().trim();
            const content = $('#review-content').val().trim();
            if (!title || !content) {
                alert('Please fill in the title and review.');
                return;
            }

            // Simulate submission
            const $btn = $reviewModal.find('button:contains("Submit Review")');
            const originalText = $btn.html();
            $btn.html('<span class="inline-block h-4 w-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Submitting...');
            $btn.prop('disabled', true);

            setTimeout(() => {
                $btn.html('<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Review Submitted!');
                $btn.removeClass('bg-[var(--primary)]').addClass('bg-green-500');
                setTimeout(() => {
                    closeWriteReviewModal();
                    $btn.html(originalText);
                    $btn.removeClass('bg-green-500').addClass('bg-[var(--primary)]');
                    $btn.prop('disabled', false);
                }, 1500);
            }, 1200);
        };
    </script>
    @endpush
@endsection
