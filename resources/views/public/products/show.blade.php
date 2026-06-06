@extends('public.layouts.app')

@php
    seo()->set([
        'title' => ($product->meta_title ?? null) ?: $product->title . ' | ' . config('app.name'),
        'description' => $product->meta_description ?? Str::limit(strip_tags($product->description), 160),
        'keywords' => $product->meta_keywords ?? '',
        'image' => \App\Helpers\ImageHelper::getUrl($product->image, 'images/products'),
        'og_type' => 'product',
        'canonical' => url()->current(),
    ]);

    seo()->addSchema('Product', [
        'name' => $product->title,
        'image' => \App\Helpers\ImageHelper::getUrl($product->image, 'images/products'),
        'description' => strip_tags($product->description),
        'sku' => 'RH-' . $product->id,
        'brand' => [
            '@type' => 'Brand',
            'name' => config('app.name')
        ],
        'offers' => [
            '@type' => 'Offer',
            'url' => request()->url(),
            'priceCurrency' => 'INR',
            'price' => $product->price,
            'availability' => 'https://schema.org/InStock',
            'itemCondition' => 'https://schema.org/NewCondition'
        ],
        'aggregateRating' => [
            '@type' => 'AggregateRating',
            'ratingValue' => $product->rating ?? 4.5,
            'reviewCount' => $product->reviews ?? 10
        ]
    ]);

    seo()->addSchema('BreadcrumbList', [
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Home',
                'item' => url('/')
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Products',
                'item' => route('products.index')
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $product->title,
                'item' => request()->url()
            ]
        ]
    ]);
@endphp

@section('content')
    @php
        $themeColor = $product->theme_color ?? 'orange';
        $isHex = str_starts_with($themeColor, '#');
        $themes = [
            'orange' => ['primary' => '#FF6B00', 'secondary' => '#008A48'],
            'emerald' => ['primary' => '#10b981', 'secondary' => '#065f46'],
            'blue' => ['primary' => '#3b82f6', 'secondary' => '#1e3a8a'],
            'indigo' => ['primary' => '#6366f1', 'secondary' => '#312e81'],
            'rose' => ['primary' => '#f43f5e', 'secondary' => '#881337'],
        ];
        $currentTheme = $isHex ? ['primary' => $themeColor, 'secondary' => $themeColor] : ($themes[$themeColor] ?? $themes['orange']);
    @endphp
    <style>
        :root {
            --primary: {{ $currentTheme['primary'] }};
            --secondary: {{ $currentTheme['secondary'] }};
        }
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
    <div class="bg-[var(--bg-main)]">
        @php
            $gallery = $product->gallery ?? [];
            $galleryImages = array_values(array_unique(array_merge([$product->image], $gallery)));
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
                                    <img src="{{ \App\Helpers\ImageHelper::getUrl($img, 'images/products') }}" alt="Product other image" class="h-full w-full object-contain">
                                </button>
                            @endforeach
                        </div>

                    <div class="relative group/gallery product-gallery-shell overflow-hidden -mx-4 rounded-none bg-[var(--bg-section)] ring-1 ring-black/5 shadow-sm sm:mx-0 sm:rounded-[2rem]"
                         data-gallery-images='@json(array_map(fn($img) => \App\Helpers\ImageHelper::getUrl($img, "images/products"), $galleryImages))'>
                        <div class="absolute right-3 top-3 sm:right-4 sm:top-4 z-30 flex flex-col gap-2">

                            <button type="button" onclick="shareProduct()" aria-label="Share product" class="flex h-11 w-11 items-center justify-center rounded-full bg-white/90 backdrop-blur-md text-[color:var(--text-primary)] shadow-lg ring-1 ring-black/10 transition hover:text-[color:var(--primary)] active:scale-95">
                                <i data-lucide="send" class="h-5 w-5"></i>
                            </button>
                        </div>
                        <div class="product-gallery-carousel owl-carousel owl-theme">
                            @foreach($galleryImages as $index => $img)
                                <div class="relative aspect-square overflow-hidden cursor-pointer bg-gray-100"
                                     onclick="openLightbox({{ $index }})">
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_1.5s_infinite] skeleton-overlay z-[5]"></div>
                                    <img src="{{ \App\Helpers\ImageHelper::getUrl($img, 'images/products') }}" 
                                         width="1200"
                                         height="1200"
                                         alt="{{ $product->title }}" 
                                         class="h-full w-full object-contain select-none opacity-0 transition-opacity duration-500 relative z-10"
                                         onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove(); this.parentElement.classList.remove('bg-gray-100')"
                                         onerror="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();">
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
                        <p class="text-xs font-bold uppercase tracking-[0.2em]" style="color: var(--primary)">{{ $product->tagline }}</p>
                        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-5xl lg:text-6xl leading-tight">
                            {{ $product->title }}
                        </h1>
                        
                        <div class="mt-6 flex items-start justify-between gap-3 sm:items-center sm:gap-6">
                            <a href="#reviews" class="flex items-center gap-3 sm:gap-6 group min-w-0">
                                <div class="flex shrink-0 items-center gap-1.5 rounded-full px-3 py-1.5 group-hover:brightness-110 transition" style="background-color: color-mix(in srgb, var(--primary), transparent 90%); color: var(--primary)">
                                    <i data-lucide="star" class="h-5 w-5 fill-current"></i>
                                    <span class="text-sm font-black">{{ $product->rating }}</span>
                                </div>
                                <span class="text-[11px] sm:text-sm font-bold leading-tight text-[color:var(--text-secondary)] group-hover:text-[color:var(--primary)] transition underline decoration-dotted underline-offset-4">{{ number_format($product->reviews) }} Verified Reviews</span>
                            </a>
                            <span class="inline-flex shrink-0 items-center gap-1.5 text-xs sm:text-sm font-bold text-green-600">
                                <i data-lucide="check-circle" class="h-4 w-4 sm:h-4 sm:w-4"></i>
                                In Stock
                            </span>
                        </div>
                    </div>

                    <div class="py-4">
                        @php
                            $discount = (int) round((1 - ($product->price / max(1, $product->mrp))) * 100);
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
                            <span class="text-xl sm:text-3xl font-black text-gray-400/50 line-through decoration-gray-400/30">₹{{ number_format($product->mrp) }}</span>
                            <span class="text-4xl sm:text-6xl font-black text-[color:var(--text-primary)] tracking-tight current-price-text">₹{{ number_format($product->price) }}</span>
                        </div>
                        <p class="mt-2 text-xs font-bold text-[color:var(--text-muted)] uppercase tracking-wider">Inclusive of all taxes</p>
                        
                        <!-- Interactive Coupon Section -->
                        <div class="mt-8">
                            <div class="flex flex-col gap-3">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Apply Coupon Code</label>
                                <div class="relative flex items-center max-w-sm">
                                    <input type="text" 
                                           id="coupon-input"
                                           placeholder="Enter Code"
                                           class="w-full rounded-2xl bg-gray-50/50 border-2 border-dashed border-gray-200 px-5 py-4 text-sm font-bold text-[color:var(--text-primary)] placeholder:text-gray-300 focus:outline-none focus:border-[color:var(--primary)] transition-all uppercase tracking-widest">
                                    <button type="button" 
                                            id="apply-coupon-btn"
                                            onclick="applyCoupon()"
                                            class="absolute right-2 top-2 bottom-2 rounded-xl px-6 text-[10px] font-black text-white uppercase tracking-widest shadow-lg active:scale-95 transition-all hover:brightness-105"
                                            style="background-color: var(--primary); shadow: 0 10px 20px -5px color-mix(in srgb, var(--primary), transparent 60%)">
                                        Apply
                                    </button>
                                </div>
                                
                                <div id="coupon-message" class="hidden animate-in fade-in slide-in-from-top-2 duration-300">
                                    <div class="flex items-center justify-between gap-2 rounded-xl bg-green-50 px-4 py-3 border border-green-100">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="check-circle-2" class="h-4 w-4 text-green-600"></i>
                                            <p class="text-[11px] font-bold text-green-700 uppercase tracking-wider">
                                                Code <span id="applied-code-text" class="underline"></span> Applied! <span class="mx-2 text-green-300">|</span> You save <span class="text-lg">₹<span id="discount-amount">0</span></span>
                                            </p>
                                        </div>
                                        <button onclick="removeCoupon()" class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:underline">Remove</button>
                                    </div>
                                </div>
                                
                                <p id="coupon-error" class="hidden text-[10px] font-bold text-red-500 uppercase tracking-widest mt-1">
                                </p>
                            </div>
                        </div>

                        <script>
                            // Real coupon logic in bottom script stack
                        </script>
                        
                        {{-- 
                        <!-- Professional Minimalist 'Available Offers' (No Code Badges) -->
                        <div class="mt-10 rounded-[1.5rem] bg-gray-50/50 border border-gray-100 overflow-hidden shadow-sm">
                            <button type="button" 
                                    onclick="toggleOffersDropdown()"
                                    class="w-full flex items-center justify-between p-6 bg-white border-b border-gray-100 group/header transition-colors hover:bg-gray-50/30">
                                <div class="flex flex-wrap items-center gap-x-6 gap-y-2">
                                    <h3 class="text-xl font-bold text-[#1B4B36]">Available Offers</h3>
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Buy at</span>
                                        <span class="text-xl font-bold text-[#1B4B36]">₹{{ number_format($product->price - 250) }}</span>
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
                        --}}

                        <!-- Product Short Description -->
                        <div class="mt-8 border-t border-black/5 pt-8">
                            <div class="relative group/desc">
                                <div id="short-description" class="text-lg leading-relaxed text-[color:var(--text-secondary)] font-medium line-clamp-3 transition-all duration-500">
                                    {!! $product->description !!}
                                </div>
                                <button type="button" 
                                        id="short-desc-btn"
                                        onclick="toggleShortDescription()"
                                        class="mt-4 text-xs font-black text-[color:var(--primary)] hover:underline underline-offset-8 uppercase tracking-[0.2em] hidden">
                                    Read More
                                </button>
                            </div>
                        </div>
                        
                        <!-- Bundle Included Section (For Combos) -->
                        @if(in_array($product->product_type, ['combo', 'both']) && $product->comboItems->count() > 0)
                        <div class="mt-10 border-t border-black/5 pt-10">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-50 text-orange-600 shadow-sm">
                                    <i data-lucide="layers" class="h-5 w-5"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold uppercase tracking-widest text-gray-900 leading-none">Bundle Architecture</h3>
                                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mt-2">What's inside this combo</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($product->comboItems as $ci)
                                    @if($ci->product)
                                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50/50 border border-black/5 group hover:bg-white hover:shadow-lg transition-all duration-300">
                                        <div class="h-14 w-14 shrink-0 rounded-xl bg-white p-1 border border-black/5 overflow-hidden">
                                            <img src="{{ \App\Helpers\ImageHelper::getUrl($ci->product->image) }}" class="h-full w-full object-contain group-hover:scale-110 transition-transform">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-bold text-gray-900 truncate">{{ $ci->product->title }}</h4>
                                            <p class="text-[10px] font-black text-[color:var(--primary)] uppercase tracking-widest mt-1">{{ $ci->quantity }} Unit{{ $ci->quantity > 1 ? 's' : '' }} Included</p>
                                        </div>
                                        <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i data-lucide="check" class="h-4 w-4 text-green-500"></i>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <script>
                            function checkShortDesc() {
                                const desc = document.getElementById('short-description');
                                const btn = document.getElementById('short-desc-btn');
                                if (!desc || !btn) return;
                                
                                if (desc.scrollHeight > desc.offsetHeight + 5) {
                                    btn.classList.remove('hidden');
                                }
                            }

                            function toggleShortDescription() {
                                const desc = document.getElementById('short-description');
                                const btn = document.getElementById('short-desc-btn');
                                if (desc.classList.contains('line-clamp-3')) {
                                    desc.classList.remove('line-clamp-3');
                                    btn.innerText = 'Read Less';
                                } else {
                                    desc.classList.add('line-clamp-3');
                                    btn.innerText = 'Read More';
                                }
                            }

                            window.addEventListener('DOMContentLoaded', checkShortDesc);
                        </script>

                        {{-- 
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
                        --}}

                        <script>
                            // Long Description Read More Logic
                            function checkLongDescOverflow() {
                                const desc = document.getElementById('long-description-text');
                                const btn = document.getElementById('long-desc-read-more');
                                if (!desc || !btn) return;

                                if (desc.scrollHeight > desc.offsetHeight) {
                                    btn.classList.remove('hidden');
                                } else {
                                    btn.classList.add('hidden');
                                }
                            }

                            function toggleLongDescription() {
                                const desc = document.getElementById('long-description-text');
                                const btn = document.getElementById('long-desc-read-more');
                                if (desc.classList.contains('line-clamp-6')) {
                                    desc.classList.remove('line-clamp-6');
                                    btn.innerText = 'Read Less';
                                } else {
                                    desc.classList.add('line-clamp-6');
                                    btn.innerText = 'Read More';
                                }
                            }

                            window.addEventListener('DOMContentLoaded', checkLongDescOverflow);
                            window.addEventListener('resize', checkLongDescOverflow);
                        </script>

                        {{-- 
                            <div id="product-description" class="text-lg leading-relaxed text-[color:var(--text-secondary)] font-medium line-clamp-3 transition-all duration-500">
                                {{ $product->description }}
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


        <!-- Product Image Showcase -->
        <section class="bg-white pt-16 sm:pt-24">
            <div class="px-4 sm:px-6 lg:px-0 py-4 sm:py-8 lg:py-0">

                <!-- Main Banner Image (1200×450 aspect ratio) -->
                <div class="w-full overflow-hidden bg-gray-100 relative" style="aspect-ratio: 1200/450;">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_1.5s_infinite] skeleton-overlay"></div>
                    <img src="{{ \App\Helpers\ImageHelper::getUrl(($product->gallery ?? [])[0] ?? $product->image, 'images/products') }}"
                         alt="{{ $product->title }} - Main"
                         class="w-full h-full object-cover opacity-0 transition-opacity duration-500"
                         onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove(); this.parentElement.classList.remove('bg-gray-100')"
                         onerror="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();">
                </div>

                <!-- Two Images Side by Side -->
                @if(count($product->gallery ?? []) >= 3)
                <div class="mt-1 grid grid-cols-2 gap-1">
                    <div class="overflow-hidden bg-gray-100 aspect-square relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_1.5s_infinite] skeleton-overlay"></div>
                        <img src="{{ \App\Helpers\ImageHelper::getUrl($product->gallery[1] ?? null, 'images/products') }}"
                             alt="{{ $product->title }} - Detail 1"
                             class="w-full h-full object-cover opacity-0 transition-opacity duration-500"
                             onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove(); this.parentElement.classList.remove('bg-gray-100')"
                             onerror="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();">
                    </div>
                    <div class="overflow-hidden bg-gray-100 aspect-square relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_1.5s_infinite] skeleton-overlay"></div>
                        <img src="{{ \App\Helpers\ImageHelper::getUrl($product->gallery[2] ?? null, 'images/products') }}"
                             alt="{{ $product->title }} - Detail 2"
                             class="w-full h-full object-cover opacity-0 transition-opacity duration-500"
                             onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove(); this.parentElement.classList.remove('bg-gray-100')"
                             onerror="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();">
                    </div>
                </div>
                @endif

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
                                <span class="text-sm font-bold uppercase tracking-[0.2em] text-gray-400">Description</span>
                            </div>
                        </div>
                        <div class="p-8 border-r border-black/10">
                            <div class="flex items-center gap-3">
                                <i data-lucide="clipboard-list" class="h-5 w-5 text-blue-600"></i>
                                <span class="text-sm font-bold uppercase tracking-[0.2em] text-gray-400">Specifications</span>
                            </div>
                        </div>
                        <div class="p-8">
                            <div class="flex items-center gap-3">
                                <i data-lucide="building-2" class="h-5 w-5 text-emerald-600"></i>
                                <span class="text-sm font-bold uppercase tracking-[0.2em] text-gray-400">Brand Info</span>
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
                                <span class="text-sm font-bold uppercase tracking-widest text-gray-400">Description</span>
                            </div>
                            
                            <div class="relative">
                                <div id="long-description-text" class="text-base sm:text-lg leading-relaxed text-gray-600 font-medium line-clamp-6 transition-all duration-500 prose prose-sm max-w-none">
                                    {!! $product->long_description !!}
                                </div>
                                <button type="button" 
                                        id="long-desc-read-more"
                                        onclick="toggleLongDescription()"
                                        class="mt-4 text-xs font-bold text-[color:var(--primary)] hover:underline underline-offset-4 uppercase tracking-widest hidden">
                                    Read More
                                </button>
                            </div>
                            <div class="mt-8 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-2 w-2 rounded-full bg-orange-400"></div>
                                    <span class="text-xs font-semibold uppercase tracking-widest text-gray-500">Clinically Formulated</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="h-2 w-2 rounded-full bg-orange-400"></div>
                                    <span class="text-xs font-semibold uppercase tracking-widest text-gray-500">100% Vegan & Clean</span>
                                </div>
                            </div>
                        </div>

                        <!-- Cell 2: Specifications -->
                        <div class="p-8 sm:p-10 md:border-r border-black/10">
                            <!-- Mobile Header -->
                            <div class="md:hidden flex items-center gap-3 mb-6 py-2 border-b border-black/5">
                                <i data-lucide="clipboard-list" class="h-5 w-5 text-blue-600"></i>
                                <span class="text-sm font-bold uppercase tracking-widest text-gray-400">Specifications</span>
                            </div>

                            <div class="space-y-6">
                                @if(is_array($product->specs) || is_object($product->specs))
                                    @foreach($product->specs ?? [] as $label => $value)
                                        <div class="flex items-center justify-between border-b border-black/5 pb-4 last:border-0">
                                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">{{ $label }}</span>
                                            <span class="text-base font-semibold text-gray-800">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="prose prose-sm max-w-none">
                                        {!! $product->specs !!}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Cell 3: Brand Info -->
                        <div class="p-8 sm:p-10">
                            <!-- Mobile Header -->
                            <div class="md:hidden flex items-center gap-3 mb-6 py-2 border-b border-black/5">
                                <i data-lucide="building-2" class="h-5 w-5 text-emerald-600"></i>
                                <span class="text-sm font-bold uppercase tracking-widest text-gray-400">Brand Info</span>
                            </div>

                            <div class="space-y-8">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Marketed By</span>
                                    <p class="mt-2 text-lg font-semibold text-gray-800">Remenant Health Private Limited</p>
                                    <p class="text-sm font-bold text-gray-500">BKC, Mumbai - 400051</p>
                                </div>
                                <div class="pt-6 border-t border-black/5">
                                    <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Customer Support</span>
                                    <p class="mt-2 text-lg font-semibold text-orange-600">support@remenant.in</p>
                                </div>
                                <div class="inline-flex items-center gap-3 rounded-xl bg-emerald-50 px-5 py-2.5 ring-1 ring-emerald-500/10">
                                    <i data-lucide="shield-check" class="h-5 w-5 text-emerald-600"></i>
                                    <span class="text-xs font-semibold uppercase tracking-widest text-emerald-700">FSSAI Certified</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
     
        <!-- Product-Specific Highlights Section -->
        <section class="py-12 sm:py-20 bg-[#FAFAFA] border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-24">

                    <!-- Left: Label Column -->
                    <div class="lg:col-span-2 lg:sticky lg:top-28 lg:self-start">
                        <span class="text-[10px] font-bold uppercase tracking-[0.4em] mb-6 block" style="color: var(--primary)">What It Does</span>
                        <h2 class="mt-3 text-2xl sm:text-3xl font-bold tracking-tight text-[color:var(--text-primary)] leading-snug">
                            {!! $product->benefits_title ?? "Key Benefits of<br>".$product->title !!}
                        </h2>
                        <p class="mt-3 text-base sm:text-lg text-slate-500 font-medium leading-relaxed max-w-sm">
                            {{ $product->benefits_subtitle ?? $product->tagline }}
                        </p>
                    </div>

                    <!-- Right: Benefits List -->
                    <div class="lg:col-span-3 divide-y divide-black/[0.06]">
                        @if($product->benefits && count($product->benefits) > 0)
                            @foreach($product->benefits ?? [] as $index => $benefit)
                                @php 
                                    $b = (object)$benefit;
                                    $iconName = $b->icon ?? 'star';
                                @endphp
                                <div class="flex items-start gap-6 py-8 first:pt-0 group">
                                    <div class="shrink-0 w-12 pt-1">
                                        <span class="text-base font-bold text-slate-300 tabular-nums">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-4 mb-2">
                                            <div class="shrink-0" style="color: var(--primary)">
                                                <i data-lucide="{{ $iconName }}" class="h-6 w-6"></i>
                                            </div>
                                            <h3 class="text-xl font-bold text-slate-900 leading-tight">{{ $b->title ?? '' }}</h3>
                                        </div>
                                        <div class="text-sm text-slate-500 font-medium leading-relaxed max-w-2xl">
                                            {!! $b->desc ?? '' !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        {{-- Product Highlights & Ritual --}}
        @if(($product->highlights ?? null) || ($product->ritual && count((array)$product->ritual) > 0))
        <section class="py-12 sm:py-20 bg-white border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 lg:items-start">
                    <!-- Left: Product Highlights -->
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] mb-4 block" style="color: var(--primary)">Experience Excellence</span>
                        <h3 class="text-3xl sm:text-5xl font-black tracking-tighter uppercase text-slate-900 mb-8 leading-tight">Product<br>Highlights</h3>
                        
                        <div class="grid grid-cols-1 gap-4 mt-8">
                            @if(is_array($product->highlights) || is_object($product->highlights))
                                @foreach($product->highlights as $highlight)
                                    @php $h = (object)$highlight; @endphp
                                    <div class="flex items-start gap-5 p-6 rounded-[2rem] bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-xl transition-all duration-500 group" :style="{ '--tw-shadow-color': 'var(--primary)' }">
                                        <div class="shrink-0 w-12 h-12 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform" style="background-color: color-mix(in srgb, var(--primary), transparent 90%); color: var(--primary)">
                                            <i data-lucide="{{ $h->icon ?? 'star' }}" class="h-5 w-5"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-base font-black text-slate-900 mb-1 uppercase tracking-tight">{{ $h->title ?? '' }}</h4>
                                            <div class="text-sm text-slate-500 leading-relaxed font-medium prose prose-sm prose-slate">
                                                {!! $h->desc ?? $h->description ?? '' !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Right: The Ritual -->
                    <div class="rounded-[2.5rem] p-8 sm:p-12 border relative overflow-hidden" style="background-color: color-mix(in srgb, var(--primary), transparent 95%); border-color: color-mix(in srgb, var(--primary), transparent 80%)">
                        <div class="absolute top-0 right-0 w-64 h-64 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl" style="background-color: color-mix(in srgb, var(--primary), transparent 90%)"></div>
                        
                        <h2 class="text-2xl sm:text-3xl font-black tracking-tighter uppercase text-slate-900 mb-10 flex items-center gap-6">
                            The Ritual
                            <span class="h-px flex-1" style="background-color: color-mix(in srgb, var(--primary), transparent 80%)"></span>
                        </h2>
                        
                        <div class="space-y-10">
                            @if($product->ritual && (is_array($product->ritual) || is_object($product->ritual)))
                                @foreach((array)$product->ritual as $step)
                                    @php $s = (object)$step; @endphp
                                    <div class="flex gap-8 group relative">
                                        <div class="relative z-10">
                                            <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl font-black text-white text-2xl shadow-lg transition-transform group-hover:rotate-12 group-hover:scale-110 duration-500" style="background-color: var(--primary); shadow: 0 10px 25px -5px color-mix(in srgb, var(--primary), transparent 60%)">{{ $loop->iteration }}</span>
                                            @if(!$loop->last)
                                                <div class="absolute top-14 left-1/2 -translate-x-1/2 w-px h-10 bg-gradient-to-b" style="--tw-gradient-from: color-mix(in srgb, var(--primary), transparent 50%); --tw-gradient-to: transparent;"></div>
                                            @endif
                                        </div>
                                        <div class="pt-1">
                                            <h4 class="text-xl font-black uppercase tracking-widest text-slate-900 mb-2">{{ $s->title ?? '' }}</h4>
                                            <p class="text-base text-slate-500 font-medium leading-relaxed">{{ $s->desc ?? '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- Nutrition Facts Section --}}
        @if($product->nutrition && count((array)$product->nutrition) > 0)
        <section class="py-12 sm:py-24 bg-[#FDFBF7]">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 items-center">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-orange-600">Pure & Potent</span>
                        <h2 class="mt-4 text-3xl sm:text-5xl font-black tracking-tighter text-slate-900 uppercase">Nutrition Facts</h2>
                        <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed mt-6">
                            {!! $product->nutrition_description ?? 'We believe in total transparency. Every ingredient is carefully sourced and lab-tested for purity and potency. No hidden fillers, just results.' !!}
                        </div>
                        
                        <div class="mt-10 grid grid-cols-2 gap-6">
                            @php
                                $nutriHighlights = $product->nutrition_highlights ?? [
                                    ['icon' => 'leaf', 'text' => '100% Vegan'],
                                    ['icon' => 'zap', 'text' => 'Zero Sugar']
                                ];
                            @endphp
                            @foreach(array_slice($nutriHighlights, 0, 2) as $highlight)
                                @php $nh = (object)$highlight; @endphp
                                <div class="p-6 rounded-3xl bg-white border border-slate-100 shadow-sm">
                                    <i data-lucide="{{ $nh->icon ?? 'check' }}" class="h-6 w-6 text-{{ ($nh->icon ?? '') == 'leaf' ? 'green' : 'amber' }}-500 mb-3"></i>
                                    <h4 class="font-bold text-slate-900 uppercase text-xs tracking-widest">{{ $nh->text ?? '' }}</h4>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-white p-8 sm:p-12 rounded-[3rem] border border-slate-200 shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-bl-full -mr-10 -mt-10"></div>
                        
                        <div class="relative z-10">
                            <h3 class="text-xl font-bold text-slate-900 mb-8 border-b border-slate-100 pb-4">Typical Values (Per Serving)</h3>
                            <div class="space-y-4">
                                @foreach($product->nutrition as $label => $value)
                                    <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                                        <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">{{ $label }}</span>
                                        <span class="text-base font-black text-slate-900">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <p class="mt-8 text-[10px] text-slate-400 font-bold uppercase tracking-widest italic">* Daily values based on a 2,000 calorie diet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Brand Text Marquee Section -->
        <section class="bg-[#E5E9E0] py-5 overflow-hidden border-y border-black/5">
            <div class="marquee marquee--slow select-none">
                <div class="marquee__track gap-12 sm:gap-20">
                    @php
                        $brandPoints = [
                            'MADE IN INDIA',
                            'GMO FREE',
                            'GLUTEN FREE',
                            'NON-TOXIC',
                            'LAB VERIFIED',
                            'HIGH ABSORPTION'
                        ];
                    @endphp
                    @foreach(range(1, 6) as $i)
                        @foreach ($brandPoints as $point)
                            <div class="flex items-center gap-12 sm:gap-20">
                                <span class="text-sm sm:text-base font-black tracking-[0.2em] text-[#074D3D] whitespace-nowrap">{{ $point }}</span>
                                <div class="h-2 w-2 rounded-full bg-[#FF6B00] shrink-0"></div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
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
                                <span class="text-7xl font-black leading-none text-[color:var(--text-primary)]">{{ number_format($avgRating, 1) }}</span>
                                <div class="flex flex-col gap-1 pb-1">
                                    <div class="flex text-orange-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i data-lucide="star" class="h-5 w-5 {{ $i <= floor($avgRating) ? 'text-[color:var(--primary)] fill-[color:var(--primary)]' : 'text-slate-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-bold text-[color:var(--text-secondary)]">Based on {{ number_format($totalApproved) }} reviews</span>
                                </div>
                            </div>

                            <!-- Rating Bars -->
                            <div class="mt-10 space-y-4">
                                @foreach([5, 4, 3, 2, 1] as $star)
                                    @php
                                        $count = $product instanceof \App\Models\Product ? $product->reviews()->where('status', 'approved')->where('rating', $star)->count() : 0;
                                        $percentage = $totalApproved > 0 ? ($count / $totalApproved) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-4">
                                        <span class="w-4 text-xs font-semibold text-[color:var(--text-secondary)]">{{ $star }}</span>
                                        <i data-lucide="star" class="h-3 w-3 text-[color:var(--primary)] fill-current"></i>
                                        <div class="flex-1 h-1.5 rounded-full bg-black/5 overflow-hidden">
                                            <div class="h-full bg-[color:var(--primary)] rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="w-12 text-xs font-medium text-[color:var(--text-muted)] text-right">{{ $count }}</span>
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
                            // Dynamic ratings breakdown (simple version for product page)
                        @endphp

                        @forelse($topReviews as $review)
                            @php 
                                $reviewImages = array_map(fn($img) => \App\Helpers\ImageHelper::getUrl($img, 'reviews'), $review->images ?? []);
                            @endphp
                            <div class="bg-white p-5 sm:p-8 rounded-3xl shadow-sm ring-1 ring-black/[0.03] transition-all duration-300">
                                <div class="flex flex-col gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 rounded bg-[var(--primary)] px-1.5 py-0.5 text-xs font-bold text-white">
                                            <span class="text-white">{{ $review->rating }}</span>
                                            <i data-lucide="star" class="h-3 w-3 fill-current"></i>
                                        </div>
                                    </div>

                                    <p class="text-sm sm:text-base leading-relaxed text-[color:var(--text-secondary)]">
                                        {{ Str::limit($review->comment, 120) }}
                                        @if(strlen($review->comment) > 120)
                                            <a href="{{ route('products.reviews', $product->slug) }}" class="text-[color:var(--primary)] font-semibold hover:underline ml-1">Read more</a>
                                        @endif
                                    </p>

                                    @if($review->images && count($review->images) > 0)
                                        <div class="flex flex-wrap gap-2 pt-1">
                                            @foreach($review->images as $imgIndex => $img)
                                                <div class="group/img relative h-16 w-16 sm:h-20 sm:w-20 overflow-hidden rounded-xl bg-gray-50 ring-1 ring-black/5 cursor-zoom-in"
                                                     onclick='openReviewLightbox({!! json_encode($reviewImages) !!}, {{ $imgIndex }})'>
                                                    <img src="{{ \App\Helpers\ImageHelper::getUrl($img, 'reviews') }}" 
                                                         alt="User review image" 
                                                         class="h-full w-full object-cover">
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between pt-4 border-t border-black/[0.03]">
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs font-bold text-[color:var(--text-primary)]">{{ $review->user->name ?? 'Verified Buyer' }}</span>
                                                <div class="flex items-center gap-1 text-[10px] font-bold text-gray-400">
                                                    <i data-lucide="check-circle-2" class="h-3.5 w-3.5 text-[color:var(--primary)]"></i>
                                                    Certified Buyer
                                                </div>
                                            </div>
                                            <span class="text-[10px] font-medium text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-white rounded-3xl border border-dashed border-slate-200">
                                <p class="text-slate-400 font-medium italic">No reviews yet for this product.</p>
                                <button onclick="openWriteReviewModal()" class="mt-4 text-[color:var(--primary)] font-bold text-xs uppercase tracking-widest hover:underline">Be the first to review</button>
                            </div>
                        @endforelse

                        @if($totalApproved > 0)
                            <div class="mt-12 flex justify-center">
                                <a href="{{ route('products.reviews', $product->slug) }}" class="inline-flex items-center justify-center px-10 py-3.5 rounded-full border border-orange-200 text-sm font-semibold text-[color:var(--primary)] transition-all duration-300 hover:bg-orange-50 hover:border-orange-300 active:scale-95">
                                    Read All {{ number_format($totalApproved) }} Reviews
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>


        {{-- Dynamic FAQs --}}
        @if($product->faqs && is_array($product->faqs) && count($product->faqs) > 0)
        <section class="py-12 sm:py-24 bg-slate-50">
            <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-12">
                <div class="text-center mb-16">
                    <h2 class="text-xs font-black uppercase tracking-[0.4em] text-orange-500 mb-4">Got Questions?</h2>
                    <h3 class="text-3xl sm:text-5xl font-black italic tracking-tighter uppercase text-slate-900">Common Inquiries</h3>
                </div>
                
                <div class="space-y-4 max-w-4xl mx-auto">
                    @foreach($product->faqs ?? [] as $index => $faq)
                        @php $f = (object)$faq; @endphp
                        <div x-data="{ open: false }" class="bg-white rounded-[2rem] border border-slate-100 overflow-hidden transition-all hover:shadow-lg hover:shadow-slate-200/50">
                            <button @click="open = !open" class="w-full p-6 sm:p-8 flex items-center justify-between text-left focus:outline-none">
                                <span class="text-lg font-black text-slate-900">{{ $f->question ?? '' }}</span>
                                <div class="h-8 w-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 transition-transform duration-300" :class="open ? 'rotate-180 bg-orange-500 text-white' : ''">
                                    <i data-lucide="chevron-down" class="w-5 h-5"></i>
                                </div>
                            </button>
                            <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="px-6 pb-6 sm:px-8 sm:pb-8 text-slate-500 leading-relaxed font-medium">
                                {{ $f->answer ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Related Products -->
        <section class="py-20 bg-white border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 mb-12">
                    <div>
                        <h2 class="section-heading !text-left">Recommended for you</h2>
                        <p class="mt-2 text-[color:var(--text-secondary)] font-medium">Discover more premium wellness essentials.</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="inline-flex rounded-full bg-orange-50 text-[color:var(--primary)] px-8 py-3 text-xs font-black uppercase tracking-widest hover:bg-orange-100 transition ring-1 ring-orange-100">View All</a>
                </div>
                <div class="flex overflow-x-auto lg:grid lg:grid-cols-4 gap-4 sm:gap-8 pb-8 lg:pb-0 snap-x snap-mandatory scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
                    @foreach ($relatedProducts as $rp)
                        @php
                            $discount = (int) round((1 - ($rp->price / max(1, $rp->mrp))) * 100);
                        @endphp
                        <div class="group relative flex flex-col shrink-0 w-[280px] sm:w-auto bg-white rounded-[2rem] border border-black/5 overflow-hidden snap-start">
                            {{-- Image Container --}}
                            <div class="relative aspect-square overflow-hidden bg-[var(--bg-section)]">
                                <a href="{{ route('products.show', $rp->slug) }}" class="absolute inset-0 z-10"></a>
                                <img src="{{ \App\Helpers\ImageHelper::getUrl($rp->image, 'images/products') }}" alt="{{ $rp->title }}"
                                    class="h-full w-full object-contain" loading="lazy">
                                
                                {{-- Badges --}}
                                @if($discount > 0)
                                    <div class="absolute left-3 top-3 z-20 rounded-full bg-[var(--primary)] px-3 py-1.5 text-[10px] font-black text-white uppercase tracking-wider">
                                        -{{ $discount }}%
                                    </div>
                                @endif
                                

                            </div>

                            {{-- Content --}}
                            <div class="flex flex-1 flex-col p-4 sm:p-5">
                                <p class="text-[10px] sm:text-xs font-bold tracking-wider text-[color:var(--primary)] uppercase">
                                    {{ $rp->tagline }}
                                </p>
                                <h3 class="mt-1 text-base sm:text-lg font-bold text-[color:var(--text-primary)] leading-tight truncate">
                                    {{ $rp->title }}
                                </h3>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <div class="flex items-baseline gap-2">
                                        <span class="text-xl font-bold text-[color:var(--primary)]">₹{{ number_format($rp->price) }}</span>
                                        @if($rp->mrp > $rp->price)
                                            <span class="text-xs font-medium text-[color:var(--text-muted)] line-through">₹{{ number_format($rp->mrp) }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 rounded-full bg-black/5 px-2 py-1 text-[10px] font-semibold text-[color:var(--text-secondary)]">
                                        <i data-lucide="star" class="h-3.5 w-3.5 fill-[color:var(--primary)] text-[color:var(--primary)]"></i>
                                        <span>{{ number_format($rp->rating, 1) }}</span>
                                        <span>({{ number_format($rp->reviews ?? 0) }})</span>
                                    </div>
                                </div>

                                <div class="mt-auto pt-4 relative z-10">
                                    <a href="{{ route('products.show', $rp->slug) }}" class="block w-full text-center rounded-full bg-[var(--primary)] px-4 py-2.5 text-sm font-extrabold text-white hover:opacity-95 transition">
                                        Add to cart
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                 </div>
            </div>
        </section>

        <!-- Trust & Certifications Section -->

        <section class="bg-[#F5F8F7] py-8 sm:py-12 border-y border-black/5 overflow-hidden">
            <div class="marquee select-none">
                <div class="marquee__track gap-12 sm:gap-24 px-12">
                    @php
                        $certs = [
                            ['id' => 'iso', 'name' => 'ISO Certified', 'desc' => 'Quality Management'],
                            ['id' => 'haccp', 'name' => 'HACCP', 'desc' => 'Food Safety'],
                            ['id' => 'gmp', 'name' => 'GMP Consistent', 'desc' => 'Manufacturing Practice'],
                            ['id' => 'fda', 'name' => 'FDA Registered', 'desc' => 'Facility Standard'],
                            ['id' => 'kosher', 'name' => 'Kosher', 'desc' => 'Certified Quality'],
                        ];
                    @endphp

                    {{-- Multi-duplication for ultra-smooth infinite scroll --}}
                    @foreach(range(1, 4) as $iteration)
                        @foreach ($certs as $cert)
                            <div class="flex flex-col items-center shrink-0 w-[180px]">
                                <div class="mb-4 flex h-20 w-20 items-center justify-center transition-transform hover:scale-110">
                                    <img src="{{ asset('images/icons/' . $cert['id'] . '.png') }}" alt="{{ $cert['name'] }}"
                                        class="h-full w-full object-contain">
                                </div>
                                <h3 class="text-sm font-bold uppercase tracking-widest text-[color:var(--text-primary)] text-center">
                                    {{ $cert['name'] }}
                                </h3>
                                <p class="mt-1 text-[10px] font-semibold text-[color:var(--text-secondary)] uppercase text-center whitespace-normal">
                                    {{ $cert['desc'] }}
                                </p>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </section>
        
        <!-- Join the Wellness Revolution Section -->
        <section class="bg-white py-24 border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="rounded-[4rem] bg-[var(--bg-sage)] p-8 sm:p-20 relative overflow-hidden">
                    <div class="relative z-10 text-center max-w-3xl mx-auto">
                        <h2 class="text-4xl font-bold italic tracking-tight text-[#074D3D] sm:text-5xl">Join the Wellness Revolution</h2>
                        <p class="mt-6 text-lg font-semibold text-[#074D3D]/80">
                            Start your journey towards immortality today. Join our community for exclusive benefits.
                        </p>
                        
                        <div class="mt-10 flex justify-center">
                            <a href="{{ route('login') }}" class="rounded-2xl bg-[#074D3D] px-12 py-5 text-sm font-black uppercase tracking-widest text-white hover:opacity-90 transition active:scale-95 shadow-xl shadow-[#074D3D]/20">
                                Get Started
                            </a>
                        </div>
                    </div>

                    <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/20 blur-3xl"></div>
                    <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-black/5 blur-3xl"></div>
                </div>
            </div>
        </section>
    </div>

    <!-- Sticky Bottom Bar (Always Visible) -->
    <div class="fixed bottom-0 left-0 right-0 z-[100] bg-white/95 backdrop-blur-xl border-t border-black/5 p-3 sm:p-4 sm:px-6 shadow-[0_-10px_40px_rgba(0,0,0,0.1)]">
        <div class="mx-auto max-w-[1600px] flex items-center justify-between gap-8 px-4 sm:px-6 lg:px-12">
            <!-- Product Info (Desktop Only) -->
            <div class="hidden md:flex items-center gap-4">
                <img src="{{ \App\Helpers\ImageHelper::getUrl($product->image, 'images/products') }}" alt="{{ $product->title }}" class="h-14 w-14 rounded-xl bg-gray-50 object-contain p-2">
                <div>
                    <h4 class="text-sm font-black text-[color:var(--text-primary)] line-clamp-1">{{ $product->title }}</h4>
                    <p class="text-xs font-bold text-[color:var(--primary)]">₹{{ number_format($product->price) }}</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-1 items-center gap-2 sm:gap-3 max-w-[600px] lg:flex-none lg:w-[450px]">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full h-11 sm:h-12 flex items-center justify-center rounded-xl sm:rounded-2xl bg-[var(--primary)] text-white font-bold uppercase tracking-[0.1em] text-[10px] sm:text-xs shadow-lg shadow-[var(--primary)]/20 active:scale-95 transition hover:brightness-105">
                        Add to Cart
                    </button>
                </form>
                <a href="{{ route('checkout', ['product' => $product->slug]) }}" class="flex-1 h-11 sm:h-12 rounded-xl sm:rounded-2xl bg-[var(--secondary)] text-white font-bold uppercase tracking-[0.1em] text-[10px] sm:text-xs shadow-lg shadow-[var(--secondary)]/20 active:scale-95 transition hover:brightness-105 flex items-center justify-center">
                    Buy It Now
                </a>
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
                        <img src="{{ \App\Helpers\ImageHelper::getUrl($img, 'products') }}" alt="{{ $product->title }}" data-lightbox-image class="h-full w-full object-contain select-none">
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
                                        <button type="button" data-star="{{ $i }}" onclick="setReviewRating({{ $i }})" class="review-star p-0.5 text-gray-300 hover:text-[color:var(--primary)] transition-colors duration-150">
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



    <style>
        /* Hide counter specifically */
        #lightbox-current, #lightbox-total { display: none !important; }
        .lightbox-nav-btn { z-index: 120 !important; }

        /* Remove any possible blue focus ring from coupon input */
        #coupon-input, #coupon-input:focus, #coupon-input:active {
            outline: none !important;
            box-shadow: none !important;
            -webkit-tap-highlight-color: transparent !important;
        }


        
        .marquee--slow .marquee__track {
            animation: marquee-scroll 60s linear infinite !important;
        }
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

        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>

    <script>
        // Coupon & Review logic (Inline as they depend on Blade variables)
        var appliedCoupon = null;
        var originalPrice = {{ $product->price }};

        window.applyCoupon = function() {
            const code = $('#coupon-input').val().trim();
            if (!code) return;
            $.ajax({
                url: '{{ route("coupons.apply") }}',
                method: 'POST',
                data: { code: code, product_id: '{{ $product->id }}', amount: originalPrice, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    $('#applied-code-text').text(res.code);
                    $('#discount-amount').text(res.discount);
                    $('#coupon-message').removeClass('hidden');
                    $('#coupon-input').parent().addClass('hidden');
                    $('.current-price-text').text('₹' + res.new_total.toLocaleString());
                }
            });
        };

        window.removeCoupon = function() {
            $('#coupon-message').addClass('hidden');
            $('#coupon-input').val('').parent().removeClass('hidden');
            $('.current-price-text').text('₹' + originalPrice.toLocaleString());
        };

        // Review Modal Logic
        var selectedRating = 0;
        var reviewImages = [];

        window.setReviewRating = function(rating) {
            selectedRating = rating;
            $('.review-star').each(function() {
                const starVal = parseInt($(this).data('star'));
                $(this).toggleClass('active text-orange-400', starVal <= rating);
                $(this).toggleClass('text-gray-300', starVal > rating);
            });
            $('#rating-label').text(['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][rating - 1]);
        };

        window.openWriteReviewModal = function() {
            @auth
                $('#review-modal').removeClass('hidden');
                $('body').css('overflow', 'hidden');
            @else
                window.location.href = '{{ route("login") }}?redirect=' + encodeURIComponent(window.location.href + '#reviews');
            @endauth
        };

        window.closeWriteReviewModal = function() {
            $('#review-modal').addClass('hidden');
            $('body').css('overflow', 'auto');
        };

        window.handleReviewImageUpload = function(input) {
            const files = Array.from(input.files);
            const container = $('#review-image-previews');
            
            files.forEach(file => {
                if (reviewImages.length >= 4) return;
                
                const reader = new FileReader();
                reader.onload = (e) => {
                    const id = Date.now() + Math.random();
                    reviewImages.push({ id, file });
                    
                    const html = `
                        <div class="preview-thumb" data-id="${id}">
                            <img src="${e.target.result}">
                            <button type="button" class="remove-btn" onclick="removeReviewImage(${id})">
                                <i data-lucide="x" class="h-3 w-3"></i>
                            </button>
                        </div>
                    `;
                    container.append(html);
                    container.removeClass('hidden');
                    if (window.lucide) window.lucide.createIcons();
                };
                reader.readAsDataURL(file);
            });
            input.value = ''; // Reset input
        };

        window.removeReviewImage = function(id) {
            reviewImages = reviewImages.filter(img => img.id !== id);
            $(`.preview-thumb[data-id="${id}"]`).remove();
            if (reviewImages.length === 0) $('#review-image-previews').addClass('hidden');
        };

        window.submitReview = function() {
            if (selectedRating === 0) {
                showToast('Please select a rating', 'warning');
                return;
            }
            const comment = $('#review-content').val().trim();
            if (comment.length < 10) {
                showToast('Please write at least 10 characters', 'warning');
                return;
            }

            const formData = new FormData();
            formData.append('rating', selectedRating);
            formData.append('comment', comment);
            formData.append('_token', '{{ csrf_token() }}');
            
            reviewImages.forEach(img => {
                formData.append('images[]', img.file);
            });

            const btn = $('#review-modal button[onclick="submitReview()"]');
            const originalText = btn.html();
            btn.prop('disabled', true).html('<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Submitting...');
            if (window.lucide) window.lucide.createIcons();

            $.ajax({
                url: '{{ route("products.reviews.store", $product->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.requires_login) {
                        window.location.href = res.redirect;
                        return;
                    }
                    if (res.success) {
                        showToast(res.message, 'success');
                        closeWriteReviewModal();
                        // Wait a moment for the toast to be seen before reloading
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }
                },
                error: function(err) {
                    const msg = err.responseJSON?.message || 'Something went wrong';
                    showToast(msg, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html(originalText);
                    if (window.lucide) window.lucide.createIcons();
                }
            });
        };

        window.shareProduct = function() {
            const shareData = {
                title: '{{ addslashes($product->title) }}',
                text: 'Check out this amazing product from Remenant Health!',
                url: window.location.href
            };

            if (navigator.share) {
                navigator.share(shareData).catch((error) => console.log('Error sharing', error));
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    if (window.showToast) {
                        window.showToast('Product link copied to clipboard!', 'success');
                    } else {
                        alert('Link copied to clipboard!');
                    }
                });
            }
        };
    </script>
    @push('scripts')
        <script src="{{ asset('js/product-page.js') }}"></script>
    @endpush
@endsection
