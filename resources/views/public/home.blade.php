@extends('public.layouts.app')

@section('title', config('app.name', 'Remenant Health') . ' - Home')

@section('content')
    @php

        $combos = [
            [
                'title' => 'Ultimate Immunity Duo',
                'slug' => 'ultimate-immunity-duo',
                'category' => 'Immunity',
                'tagline' => 'Double the Protection',
                'description' => 'Vitamin C (1000mg) + Vitamin D3 & Zinc combo for peak immunity.',
                'price' => 3299,
                'mrp' => 4999,
                'image' => 'remenant-product1.jpg',
                'badge' => 'Value Pack',
            ],
            [
                'title' => 'Glow & Strength Bundle',
                'slug' => 'glow-strength-bundle',
                'category' => 'Beauty',
                'tagline' => 'Complete Beauty Care',
                'description' => 'Glutathione + Biotin combination for hair, skin and nail health.',
                'price' => 3499,
                'mrp' => 5499,
                'image' => 'remenant-product2.jpg',
                'badge' => 'Best Seller',
            ],
            [
                'title' => 'Daily Wellness Pack',
                'slug' => 'daily-wellness-pack',
                'category' => 'Wellness',
                'tagline' => 'Pure Energy',
                'description' => 'ACV + Vitamin C mix for digestion and long-lasting energy.',
                'price' => 2899,
                'mrp' => 3999,
                'image' => 'remenant-product3.jpg',
                'badge' => 'Popular',
            ],
            [
                'title' => 'Skin Transformation Kit',
                'slug' => 'skin-transformation-kit',
                'category' => 'Skin Care',
                'tagline' => 'Radiant Results',
                'description' => 'Premium Collagen + Glutathione for age-defying skin.',
                'price' => 4599,
                'mrp' => 6999,
                'image' => 'remenant-product4.jpg',
                'badge' => 'Expert Choice',
            ],
        ];
    @endphp

    <section class="bg-[var(--bg-main)]">
        <div class="hero-carousel owl-carousel owl-theme">
            @php
                $slides = [
                    ['desktop' => 'remenant-bg22.jpg', 'mobile' => 'remenant-bg1.jpg', 'alt' => 'Banner 1'],
                    ['desktop' => 'remenant-bg20.jpg', 'mobile' => 'remenant-bg2.jpg', 'alt' => 'Banner 2'],
                    ['desktop' => 'remenant-bg19.jpg', 'mobile' => 'remenant-bg3.jpg', 'alt' => 'Banner 3'],
                    ['desktop' => 'remenant-bg18.jpg', 'mobile' => 'remenant-bg4.jpg', 'alt' => 'Banner 4'],
                    ['desktop' => 'remenant-bg21.jpg', 'mobile' => 'remenant-bg5.jpg', 'alt' => 'Banner 5'],
                ];
            @endphp
            @foreach($slides as $slide)
                <div class="item">
                    <picture>
                        <source media="(max-width: 640px)" srcset="{{ asset('images/banners/mobile-bg/' . $slide['mobile']) }}">
                        <img src="{{ asset('images/banners/' . $slide['desktop']) }}" alt="{{ $slide['alt'] }}" class="hero-img"
                            loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                    </picture>
                </div>
            @endforeach
        </div>
    </section>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('assets/css/home-custom.css') }}">
    @endpush

    @push('scripts')
        <script src="{{ asset('assets/js/home-custom.js') }}"></script>
    @endpush



    <!-- Trust / highlight strip (full width) -->
    <section class="bg-[var(--bg-main)]">
        <div class="w-full">
            <div
                class="relative overflow-hidden bg-gradient-to-r from-[#FFF2E8] via-[#FFE7D5] to-[#FDE7D7] px-4 py-8 sm:px-8">
                <div class="mx-auto max-w-[1600px]">
                    <!-- Decorative fruit background icons -->
                    <div
                        class="pointer-events-none absolute -left-4 top-6 hidden flex-col gap-3 opacity-50 sm:flex sm:left-4">
                        <span
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/65 text-xl shadow-sm">🍉</span>
                        <span
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/60 text-lg shadow-sm">🍓</span>
                    </div>
                    <div
                        class="pointer-events-none absolute -right-4 bottom-6 hidden flex-col gap-3 opacity-50 sm:flex sm:right-4">
                        <span
                            class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/65 text-xl shadow-sm">🍊</span>
                        <span
                            class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/60 text-lg shadow-sm">🍏</span>
                    </div>

                    <p class="text-center text-xl font-extrabold tracking-tight text-[color:var(--secondary)] sm:text-2xl">
                        Trusted by Thousands Across India
                    </p>

                    <div class="relative mt-6 grid grid-cols-3 gap-2 sm:gap-4">
                        <div
                            class="flex flex-col items-center justify-center gap-1 px-2 py-2 text-center sm:flex-row sm:gap-3 sm:px-4 sm:py-3 sm:text-left">
                            <img src="{{ asset('images/icons/clinically-tested.png') }}" alt="Clinically tested"
                                class="h-8 w-8 object-contain sm:h-14 sm:w-14 lg:h-16 lg:w-16">
                            <p class="text-[11px] font-extrabold leading-tight text-[color:var(--secondary)] sm:text-sm">
                                Clean Label<br>Certified
                            </p>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center gap-1 px-2 py-2 text-center sm:flex-row sm:gap-3 sm:px-4 sm:py-3 sm:text-left">
                            <img src="{{ asset('images/icons/premium-quality.png') }}" alt="Premium quality"
                                class="h-8 w-8 object-contain sm:h-14 sm:w-14 lg:h-16 lg:w-16">
                            <p class="text-[11px] font-extrabold leading-tight text-[color:var(--secondary)] sm:text-sm">
                                Premium Quality<br>Ingredients
                            </p>
                        </div>

                        <div
                            class="flex flex-col items-center justify-center gap-1 px-2 py-2 text-center sm:flex-row sm:gap-3 sm:px-4 sm:py-3 sm:text-left">
                            <img src="{{ asset('images/icons/safe-daily-use.png') }}" alt="Safe and effective"
                                class="h-8 w-8 object-contain sm:h-14 sm:w-14 lg:h-16 lg:w-16">
                            <p class="text-[11px] font-extrabold leading-tight text-[color:var(--secondary)] sm:text-sm">
                                Safe &amp; Effective<br>Daily Use
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="shop" class="bg-[var(--bg-main)]">
        <div class="mx-auto max-w-[1600px] px-4 py-10 sm:px-6 lg:px-12">
            <div class="flex items-center justify-between mb-10">
                <h2 class="section-heading">Best Sellers</h2>
                <a href="{{ route('products.index') }}"
                    class="rounded-full bg-black/5 px-4 py-2 text-sm font-semibold hover:bg-black/10 transition">View
                    all</a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    @php
                        $discount = (int) round((1 - ($product['price'] / max(1, $product['mrp']))) * 100);
                    @endphp
                    <div
                        class="product-card group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5 hover:shadow-md transition">
                        <a href="{{ route('products.show', $product['slug']) }}" class="absolute inset-0 z-[5]"></a>
                        <button type="button"
                            class="absolute right-3 top-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 ring-1 ring-black/10 hover:bg-white transition"
                            aria-label="Add to wishlist">
                            <i data-lucide="heart" class="h-5 w-5 text-[color:var(--text-primary)]"></i>
                        </button>

                        <div class="relative aspect-square overflow-hidden bg-[var(--bg-section)]">
                            <img src="{{ asset('images/products/' . $product['image']) }}" alt="{{ $product['title'] }}"
                                class="h-full w-full object-contain" loading="lazy">
                            <div
                                class="absolute left-3 top-3 rounded-full bg-[var(--primary)] px-3 py-1 text-xs font-extrabold text-white">
                                -{{ $discount }}%
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col p-4">
                            <p class="text-xs font-bold tracking-wide text-[color:var(--primary)] uppercase">
                                {{ $product['tagline'] }}
                            </p>
                            <h3 class="mt-1 text-[color:var(--text-primary)] truncate">{{ $product['title'] }}</h3>

                            <div class="mt-3 flex items-center justify-between gap-3">
                                <div class="flex items-baseline gap-2">
                                    <p class="text-lg font-bold text-[color:var(--primary)]">
                                        ₹{{ number_format($product['price']) }}</p>
                                    <p class="text-xs font-medium text-[color:var(--text-muted)] line-through">
                                        ₹{{ number_format($product['mrp']) }}</p>
                                </div>
                                <div
                                    class="flex items-center gap-1 rounded-full bg-black/5 px-2 py-1 text-xs font-semibold text-[color:var(--text-secondary)]">
                                    <i data-lucide="star" class="h-4 w-4 fill-[color:var(--primary)] text-[color:var(--primary)]"></i>
                                    {{ number_format($product['rating'], 1) }} ({{ number_format($product['reviews']) }})
                                </div>
                            </div>

                            <div class="mt-auto pt-3 relative z-10">
                                <a href="{{ route('products.show', $product['slug']) }}"
                                    class="block w-full text-center rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-extrabold text-white hover:opacity-95 transition">
                                    Add to cart
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Combo Offers Section -->
            <div class="mt-12 relative">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="section-heading !text-left">Combo Offers</h2>

                    <!-- Slider Navigation -->
                    <div class="hidden sm:flex items-center gap-3">
                        <button type="button" data-combo-prev
                            class="h-12 w-12 rounded-full bg-white shadow-sm ring-1 ring-black/5 flex items-center justify-center hover:bg-gray-50 transition active:scale-95">
                            <i data-lucide="chevron-left" class="h-6 w-6 text-[color:var(--text-primary)]"></i>
                        </button>
                        <button type="button" data-combo-next
                            class="h-12 w-12 rounded-full bg-white shadow-sm ring-1 ring-black/5 flex items-center justify-center hover:bg-gray-50 transition active:scale-95">
                            <i data-lucide="chevron-right" class="h-6 w-6 text-[color:var(--text-primary)]"></i>
                        </button>
                    </div>
                </div>


                <div class="combo-carousel owl-carousel owl-theme">
                    @foreach ($combos as $combo)
                        @php
                            $discount = (int) round((1 - ($combo['price'] / max(1, $combo['mrp']))) * 100);
                        @endphp
                        <div class="item h-full">
                            <div class="combo-card group relative flex flex-col h-full overflow-hidden rounded-[2rem] bg-white shadow-sm ring-1 ring-black/5 transition-all duration-500">
                                <a href="{{ route('products.show', $combo['slug']) }}" class="absolute inset-0 z-10"></a>
                                
                                <!-- Image Container -->
                                <div class="aspect-square w-full overflow-hidden bg-[var(--bg-section)]">
                                    <img src="{{ asset('images/products/' . $combo['image']) }}" alt="{{ $combo['title'] }}"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-110"
                                        loading="lazy">
                                    <div class="absolute left-4 top-4 z-20">
                                        <span class="rounded-full bg-[var(--primary)] px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-white shadow-lg">
                                            -{{ $discount }}%
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Content Area -->
                                <div class="flex flex-col flex-1 p-5 sm:p-6">
                                    <!-- Meta Row: Category & Reviews -->
                                    <div class="flex items-center justify-between gap-2 mb-3">
                                        <span class="text-[10px] font-black uppercase tracking-widest text-[color:var(--primary)]">
                                            {{ $combo['category'] ?? 'Wellness' }}
                                        </span>
                                        
                                        <div class="flex items-center gap-1 rounded-full bg-black/[0.03] px-2 py-1 text-[10px] font-bold text-gray-500 ring-1 ring-black/[0.05]">
                                            <i data-lucide="star" class="h-3 w-3 fill-[color:var(--primary)] text-[color:var(--primary)]"></i>
                                            <span>4.9 ({{ rand(500, 1500) }})</span>
                                        </div>
                                    </div>

                                    <h3 class="text-base sm:text-lg font-semibold text-[color:var(--text-primary)] leading-tight group-hover:text-[color:var(--primary)] transition-colors truncate">
                                        {{ $combo['title'] }}
                                    </h3>
                                    
                                    <div class="mt-auto pt-4 flex flex-col gap-1">
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-lg sm:text-xl font-bold text-[color:var(--text-primary)]">₹{{ number_format($combo['price']) }}</span>
                                            <span class="text-xs sm:text-sm font-medium text-gray-400 line-through">₹{{ number_format($combo['mrp']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Why Remenant Banner (Short Height) -->
    <section class="relative my-8 lg:my-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="relative rounded-[2rem] bg-[var(--bg-sage)] px-8 pt-10 pb-0 sm:px-16 sm:pt-16 sm:pb-16 lg:py-16">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center">

                    <!-- Content Right (Text) -->
                    <div class="relative z-20 order-1 lg:order-2 text-center lg:text-left">
                        <h2 class="section-heading text-center lg:text-left">
                            Need Expert Health Advice? We are Here to Help
                        </h2>
                        <p class="mt-4 text-base text-[color:var(--text-primary)]/80 max-w-lg mx-auto lg:mx-0">
                            Got questions about our products or your wellness journey? Chat with our experts for
                            personalized recommendations and support.
                        </p>

                        <div class="mt-10 flex flex-wrap justify-center lg:justify-start gap-4">
                            <!-- WhatsApp Button -->
                            <a href="https://wa.me/17567776796" target="_blank"
                                class="inline-flex items-center gap-3 rounded-[20px] bg-[#25D366] px-8 py-4 hover:opacity-90 shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                                <svg class="h-6 w-6 fill-white" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                <div class="text-left">
                                    <p class="text-[10px] uppercase leading-none font-bold tracking-wider !text-white">Chat
                                        with us</p>
                                    <p class="text-base font-bold leading-tight !text-white">WhatsApp</p>
                                </div>
                            </a>
                            <!-- Contact Form Button -->
                            <a href="/contact"
                                class="inline-flex items-center gap-3 rounded-[20px] bg-black px-8 py-4 hover:bg-black/90 transition shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <i data-lucide="mail" class="h-6 w-6 !text-white"></i>
                                <div class="text-left">
                                    <p class="text-[10px] uppercase leading-none font-bold tracking-wider !text-white">Drop a
                                        message</p>
                                    <p class="text-base font-bold leading-tight !text-white">Contact Form</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Mockup Left (Image) -->
                    <div class="order-2 lg:order-1 flex justify-center lg:block">
                        <div
                            class="lg:absolute relative -mt-10 lg:mt-0 bottom-0 left-0 lg:left-10 w-full lg:w-[450px] flex justify-center lg:justify-start pointer-events-none">
                            <img src="{{ asset('images/home/remenant-bg.png') }}" alt="Why Remenant"
                                class="w-[260px] sm:w-[320px] lg:w-full h-auto block lg:scale-105 lg:origin-bottom transition-transform duration-500 hover:scale-110">
                        </div>
                    </div>
                </div>

                <!-- Decorative elements (Clipped) -->
                <div class="absolute inset-0 overflow-hidden rounded-[2rem] pointer-events-none">
                    <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                    <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-black/5 blur-3xl"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works section -->
    <section class="bg-[var(--bg-peach)] py-10 lg:py-14 text-white">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2 lg:gap-20">
                <!-- Left Content -->
                <div class="order-2 lg:order-1">
                    <h2 class="section-heading !text-left !text-white">Drop. Fizz. Enjoy.</h2>
                    <p class="section-subtitle !text-left !text-white/90 !max-w-none mt-6">The smartest way to take your
                        daily supplements. Our effervescent
                        formula ensures maximum absorption and a refreshing taste.</p>

                    <div class="mt-12 space-y-8">
                        @php
                            $steps = [
                                ['title' => 'Drop 1 Tablet', 'desc' => 'Drop one effervescent tablet into 200ml of water.', 'icon' => 'droplets'],
                                ['title' => 'Watch it Fizz', 'desc' => 'Wait for the tablet to dissolve completely.', 'icon' => 'wind'],
                                ['title' => 'Sip & Refresh', 'desc' => 'Enjoy your delicious, nutrient-packed wellness drink.', 'icon' => 'cup-soda'],
                            ];
                        @endphp
                        @foreach ($steps as $index => $step)
                            <div class="flex gap-5">
                                <span
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-white font-bold text-[var(--bg-peach)] shadow-sm">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h4 class="text-lg font-bold text-white">{{ $step['title'] }}</h4>
                                    <p class="mt-1 text-sm text-white/80">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Image -->
                <div class="order-1 lg:order-2">
                    <div class="relative max-w-sm mx-auto lg:max-w-md">
                        <img src="{{ asset('images/home/remenant-bg2.jpg') }}" alt="How it works"
                            class="w-full h-auto object-contain rounded-[2rem] transition-transform duration-500 hover:scale-105">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Infinite Brand Marquee -->
    <section class="bg-[var(--bg-sage)] py-4 overflow-hidden border-y border-black/5">
        <div class="marquee select-none">
            <div class="marquee__track gap-12 sm:gap-20">
                @php
                    $highlights = [
                        'Clinically Tested',
                        '100% Vegan',
                        'Sugar Free',
                        'Fast Absorption',
                        'Made in India',
                        'GMO Free',
                        'Gluten Free',
                        'Non-Toxic',
                        'Lab Verified'
                    ];
                @endphp
                @foreach(range(1, 3) as $i) {{-- Duplicate for infinite loop --}}
                    @foreach ($highlights as $highlight)
                        <div class="flex items-center gap-8 !text-[#074D3D]">
                            <span class="text-sm font-black uppercase tracking-[0.2em] whitespace-nowrap !text-[#074D3D]">{{ $highlight }}</span>
                            <div class="h-1.5 w-1.5 rounded-full bg-[var(--primary)] shadow-[0_0_8px_rgba(255,107,0,0.4)]"></div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>


    <!-- Targeted Wellness (New Category Section) -->
    <section class="bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="mb-10">
                <h2 class="section-heading !text-left">Shop By Concern</h2>
            </div>

            <div class="new-category-carousel owl-carousel owl-theme" style="min-height: 450px;">
                <!-- Weight Management -->
                <div class="item">
                    <div
                        class="group relative block h-[450px] overflow-hidden rounded-[2.5rem] bg-[#FFF0F3] ring-1 ring-black/5 transition">
                        <div class="relative px-8 py-12 z-20 h-full flex flex-col">
                            <h3 class="text-3xl font-black italic tracking-tighter text-[#E91E63] uppercase leading-tight">
                                Weight<br>Management</h3>

                            <div class="mt-auto">
                                <a href="{{ route('products.index') }}"
                                    class="btn-text inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 text-[#E91E63] shadow-xl transition hover:bg-pink-50 hover:scale-105 active:scale-95">
                                    Explore All
                                </a>
                            </div>
                        </div>
                        <img src="{{ asset('images/home/remenant-bg.png') }}"
                            class="absolute bottom-0 -right-8 h-[55%] w-auto object-contain object-bottom transition-transform duration-700 group-hover:scale-110 group-hover:-translate-x-4 select-none pointer-events-none z-10">
                        <div class="absolute bottom-0 left-0 right-0 h-[50%] bg-[#FFD1DC] rounded-t-[3rem] z-0 opacity-40">
                        </div>
                    </div>
                </div>

                <!-- Daily Performance -->
                <div class="item">
                    <div
                        class="group relative block h-[450px] overflow-hidden rounded-[2.5rem] bg-[#E8F5E9] ring-1 ring-black/5 transition">
                        <div class="relative px-8 py-12 z-20 h-full flex flex-col">
                            <h3 class="text-3xl font-black italic tracking-tighter text-[#2E7D32] uppercase leading-tight">
                                Daily<br>Performance</h3>

                            <div class="mt-auto">
                                <a href="{{ route('products.index') }}"
                                    class="btn-text inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 text-[#2E7D32] shadow-xl transition hover:bg-green-50 hover:scale-105 active:scale-95">
                                    Explore All
                                </a>
                            </div>
                        </div>
                        <img src="{{ asset('images/products/remenant-product13.jpg') }}"
                            class="absolute bottom-0 -right-8 h-[55%] w-auto object-contain object-bottom transition-transform duration-700 group-hover:scale-110 select-none pointer-events-none z-10 opacity-40">
                        <div class="absolute bottom-0 left-0 right-0 h-[50%] bg-[#A5D6A7] rounded-t-[3rem] z-0 opacity-30">
                        </div>
                    </div>
                </div>

                <!-- Hair & Skin -->
                <div class="item">
                    <div
                        class="group relative block h-[450px] overflow-hidden rounded-[2.5rem] bg-[#FFF3E0] ring-1 ring-black/5 transition">
                        <div class="relative px-8 py-12 z-20 h-full flex flex-col">
                            <h3 class="text-3xl font-black italic tracking-tighter text-[#EF6C00] uppercase leading-tight">
                                Hair &<br>Skin Care</h3>

                            <div class="mt-auto">
                                <a href="{{ route('products.index') }}"
                                    class="btn-text inline-flex items-center justify-center rounded-2xl bg-white px-8 py-4 text-[#EF6C00] shadow-xl transition hover:bg-orange-50 hover:scale-105 active:scale-95">
                                    Explore All
                                </a>
                            </div>
                        </div>
                        <img src="{{ asset('images/products/remenant-product12.jpg') }}"
                            class="absolute bottom-0 -right-8 h-[55%] w-auto object-contain object-bottom transition-transform duration-700 group-hover:scale-110 select-none pointer-events-none z-10 opacity-40">
                        <div class="absolute bottom-0 left-0 right-0 h-[50%] bg-[#FFCC80] rounded-t-[3rem] z-0 opacity-30">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Full Width Banner Section -->
    <section class="w-full overflow-hidden leading-[0] min-h-[250px] sm:min-h-[400px]">
        <picture class="w-full">
            <!-- Mobile Image -->
            <source media="(max-width: 640px)" srcset="{{ asset('images/banners/mobile-bg/remenant-bg4.jpg') }}">
            <!-- Desktop Image -->
            <img src="{{ asset('images/banners/remenant-bg21.jpg') }}" alt="Remenant Wellness" class="w-full h-auto"
                loading="eager">
        </picture>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-[#FDF9F6] relative overflow-hidden">
        <!-- Floating decorative elements -->
        <div class="section-decor top-20 left-[5%] text-6xl opacity-10 animate-float" style="animation-delay: 0s;">🍏</div>
        <div class="section-decor bottom-20 right-[8%] text-5xl opacity-10 animate-float" style="animation-delay: 1.5s;">🍊
        </div>
        <div class="section-decor top-1/2 right-[3%] text-4xl opacity-10 animate-float" style="animation-delay: 3s;">🍓
        </div>
        <div class="section-decor bottom-40 left-[10%] text-5xl opacity-10 animate-float" style="animation-delay: 4.5s;">🍉
        </div>
        <div class="section-decor top-40 right-1/4 text-4xl opacity-5 animate-float" style="animation-delay: 2.5s;">🍋</div>
        <div class="mx-auto max-w-[1600px] px-4 py-20 sm:px-6 lg:px-12">
            <div class="text-left mb-10">
                <h2 class="section-heading">Community Stories</h2>
                <p class="section-subtitle !ml-0">Join thousands who have made Remenant a part of
                    their daily ritual.</p>
            </div>

            <div class="mt-16 testimonial-carousel owl-carousel owl-theme">
                @php
                    $testimonials = [
                        ['name' => 'Ananya Sharma', 'location' => 'Mumbai', 'text' => 'The Glutathione tablets have completely changed my morning routine. My skin is noticeably brighter and I feel more confident!', 'rating' => 5],
                        ['name' => 'Rahul Mehta', 'location' => 'Bangalore', 'text' => 'Finally a Vitamin C supplement that actually tastes good and feels effective. No more boring pills or chalky tablets.', 'rating' => 5],
                        ['name' => 'Priya Kapoor', 'location' => 'Delhi', 'text' => 'Perfect for my busy lifestyle. I just drop a tablet in my bottle and I am good to go. Love the Biotin formula!', 'rating' => 5],
                        ['name' => 'Vikram Rao', 'location' => 'Pune', 'text' => 'Best ACV tablets I have tried. No bad aftertaste and helped with my digestion significantly. Highly recommend for detox.', 'rating' => 5],
                        ['name' => 'Sneha Patil', 'location' => 'Hyderabad', 'text' => 'Quality ingredients and great packaging. You can tell they care about the product. The results speak for themselves!', 'rating' => 5],
                        ['name' => 'Arjun Gupta', 'location' => 'Chennai', 'text' => 'The energy boost from the Vitamin C combo is real. Feeling much more active throughout the day without the jitter.', 'rating' => 5],
                    ];
                @endphp

                @foreach ($testimonials as $testimonial)
                    <div class="item">
                        <div
                            class="testimonial-card rounded-[2.5rem] bg-white p-7 sm:p-10 shadow-xl shadow-gray-100/50 ring-1 ring-black/[0.02] flex flex-col relative overflow-hidden group h-full">
                            <!-- Quote Mark -->
                            <div
                                class="absolute -right-4 -top-4 opacity-[0.03] transform rotate-12 transition-transform group-hover:rotate-0 duration-700">
                                <i data-lucide="quote" class="h-32 w-32 text-black"></i>
                            </div>

                            <div class="flex gap-1 text-orange-400">
                                @for ($i = 0; $i < $testimonial['rating']; $i++)
                                    <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                                @endfor
                            </div>

                            <div class="mt-6 sm:mt-8 flex-1">
                                <p class="text-lg sm:text-xl font-semibold leading-relaxed text-gray-900">
                                    "{{ $testimonial['text'] }}"</p>
                            </div>

                            <div class="mt-8 sm:mt-10 flex items-center gap-3 sm:gap-4">
                                <div
                                    class="h-12 w-12 sm:h-14 sm:w-14 shrink-0 rounded-full bg-gradient-to-br from-[var(--primary)] to-orange-400 flex items-center justify-center font-black text-white text-lg sm:text-xl shadow-lg">
                                    {{ substr($testimonial['name'], 0, 1) }}
                                </div>
                                <div>
                                    <h4
                                        class="text-sm sm:text-base font-bold text-gray-900 leading-none flex items-center gap-2">
                                        {{ $testimonial['name'] }}
                                        <i data-lucide="check-circle-2"
                                            class="h-3 w-4 sm:h-4 sm:w-4 text-blue-500 fill-blue-50"></i>
                                    </h4>
                                    <p class="mt-1.5 text-[10px] sm:text-xs font-semibold text-gray-400 uppercase tracking-widest">
                                        {{ $testimonial['location'] }} • Verified
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Trust & Certifications Section -->
    <section class="bg-[#F5F8F7] py-8 sm:py-12 border-y border-black/5 overflow-hidden">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        </div>

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

                {{-- Triple duplication for extra smoothness on large screens --}}
                @foreach(range(1, 4) as $iteration)
                    @foreach ($certs as $cert)
                        <div class="flex flex-col items-center shrink-0 w-[180px]">
                            <div class="mb-4 flex h-20 w-20 items-center justify-center transition-transform hover:scale-110">
                                <img src="{{ asset('images/icons/' . $cert['id'] . '.png') }}" alt="{{ $cert['name'] }}"
                                    class="h-full w-full object-contain">
                            </div>
                            <h3 class="text-sm font-semibold uppercase tracking-widest text-[color:var(--text-primary)] text-center">
                                {{ $cert['name'] }}
                            </h3>
                            <p
                                class="mt-1 text-[10px] font-medium text-[color:var(--text-secondary)] uppercase text-center whitespace-normal">
                                {{ $cert['desc'] }}
                            </p>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    <!-- About Section (Reference Style) -->
    <section id="about" class="bg-[#FDFBF0] py-16 sm:py-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <h2 class="section-heading !text-left italic !text-[#074D3D]">About Remenant</h2>
            <div class="mt-6 max-w-6xl">
                <p class="text-base font-medium text-[#074D3D]/90 leading-relaxed sm:text-lg">
                    Remenant is one of India's most trusted brands in the Skincare & Wellness space. As a Clean-Label
                    Project Certified brand, we strive to bring you non-GMO, vegan, toxin-free products that you can easily
                    add to your daily lifestyle.
                    <a href="/about"
                        class="text-[#0FA47B] underline font-bold ml-1 hover:text-[#074D3D] transition-colors">Read
                        More</a>
                </p>
            </div>
        </div>
    </section>

    <!-- Feature Marquee Section -->
    <section class="bg-[#074D3D] py-6 overflow-hidden border-t border-white/5">
        <div class="marquee select-none">
            <div class="marquee__track gap-12 sm:gap-24">
                @php
                    $bottomFeatures = [
                        ['icon' => 'truck', 'text' => 'Free Express Shipping'],
                        ['icon' => 'shield-check', 'text' => '100% Secure Checkout'],
                        ['icon' => 'leaf', 'text' => '100% Vegan & Non-GMO'],
                        ['icon' => 'refresh-cw', 'text' => 'Hassle-Free Returns'],
                        ['icon' => 'award', 'text' => 'GMP Certified Quality'],
                        ['icon' => 'heart', 'text' => 'Proudly Made in India'],
                    ];
                @endphp
                @foreach(range(1, 4) as $i)
                    @foreach ($bottomFeatures as $f)
                        <div class="flex items-center gap-6 !text-white">
                            <i data-lucide="{{ $f['icon'] }}" class="h-5 w-5 !text-white"></i>
                            <span class="text-sm font-bold uppercase tracking-[0.15em] whitespace-nowrap !text-white">{{ $f['text'] }}</span>
                            <div class="h-1.5 w-1.5 rounded-full bg-[var(--primary)] ml-4 shadow-[0_0_8px_rgba(255,107,0,0.4)]"></div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>
@endsection