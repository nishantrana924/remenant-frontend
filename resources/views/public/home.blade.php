@extends('public.layouts.app')

@section('title', config('app.name', 'Remenant Health').' - Home')

@section('content')
    @php
        $products = [
            [
                'title' => 'Remenant Glutathione Effervescent Formula (500mg)',
                'tagline' => 'Glow from Within',
                'description' => 'A powerful antioxidant blend designed to support detoxification, enhance skin glow, and promote overall wellness.',
                'price' => 1999,
                'mrp' => 2999,
                'rating' => 4.8,
                'reviews' => 1243,
                'image' => 'remenant-product10.jpg',
                'color' => 'from-red-500 to-rose-500',
                'benefits' => ['Liver Detoxification', 'Anti-Aging Support', 'UV Protection', 'Cellular Repair', 'Brain Health'],
            ],
            [
                'title' => 'Remenant Vitamin C Effervescent Formula (1000mg)',
                'tagline' => 'Strong Immunity, Everyday Energy',
                'description' => 'Boost your immunity and daily energy with a high-strength Vitamin C formula enriched with Zinc and Vitamin D.',
                'price' => 1799,
                'mrp' => 2499,
                'rating' => 4.7,
                'reviews' => 982,
                'image' => 'remenant-product11.jpg',
                'color' => 'from-orange-500 to-amber-500',
                'benefits' => ['Fast Wound Healing', 'Respiratory Health', 'Mood Elevation', 'Iron Absorption', 'Collagen Support'],
            ],
            [
                'title' => 'Remenant Biotin Effervescent Formula',
                'tagline' => 'Beauty & Strength in Every Sip',
                'description' => 'A beauty-focused formula designed to strengthen hair, enhance skin glow, and support healthy nails from within.',
                'price' => 1699,
                'mrp' => 2399,
                'rating' => 4.6,
                'reviews' => 761,
                'image' => 'remenant-product12.jpg',
                'color' => 'from-emerald-500 to-lime-500',
                'benefits' => ['Helps Reduce Hair Thinning', 'Promotes Glowy Skin', 'Supports Nail Strength', 'Improves Scalp Health', 'Supports Balanced Metabolism'],
            ],
            [
                'title' => 'Remenant ACV Effervescent Formula',
                'tagline' => 'Refresh. Detox. Balance.',
                'description' => 'A refreshing wellness formula that supports digestion, detox, and weight management with Apple Cider Vinegar.',
                'price' => 1599,
                'mrp' => 2199,
                'rating' => 4.5,
                'reviews' => 604,
                'image' => 'remenant-product13.jpg',
                'color' => 'from-green-500 to-emerald-500',
                'benefits' => ['Supports Blood Sugar Balance', 'Promotes Clear Skin', 'Instant Refreshment', 'Helps Maintain pH Balance', 'Appetite Control Support'],
            ],
        ];

        $combos = [
            [
                'title' => 'Ultimate Immunity Duo',
                'tagline' => 'Double the Protection',
                'description' => 'Vitamin C (1000mg) + Vitamin D3 & Zinc combo for peak immunity.',
                'price' => 3299,
                'mrp' => 4999,
                'image' => 'remenant-product1.jpg',
                'badge' => 'Value Pack',
            ],
            [
                'title' => 'Glow & Strength Bundle',
                'tagline' => 'Complete Beauty Care',
                'description' => 'Glutathione + Biotin combination for hair, skin and nail health.',
                'price' => 3499,
                'mrp' => 5499,
                'image' => 'remenant-product2.jpg',
                'badge' => 'Best Seller',
            ],
            [
                'title' => 'Daily Wellness Pack',
                'tagline' => 'Pure Energy',
                'description' => 'ACV + Vitamin C mix for digestion and long-lasting energy.',
                'price' => 2899,
                'mrp' => 3999,
                'image' => 'remenant-product3.jpg',
                'badge' => 'Popular',
            ],
            [
                'title' => 'Skin Transformation Kit',
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
        <div class="w-full">
            <div
                class="hero-slider relative overflow-hidden"
                data-hero-slider
                data-interval="4500"
            >
                <div class="hero-slider__viewport">
                    <div class="hero-slider__track" data-slider-track>
                        <div class="hero-slide is-active" data-slide>
                            <picture>
                                <source media="(max-width: 640px)" srcset="{{ asset('images/banners/mobile-bg/remenant-bg1.jpg') }}">
                                <img
                                    src="{{ asset('images/banners/remenant-bg22.jpg') }}"
                                    alt="Remenant Health Banner 1"
                                    loading="eager"
                                >
                            </picture>
                        </div>
                        <div class="hero-slide" data-slide>
                            <picture>
                                <source media="(max-width: 640px)" srcset="{{ asset('images/banners/mobile-bg/remenant-bg2.jpg') }}">
                                <img
                                    src="{{ asset('images/banners/remenant-bg20.jpg') }}"
                                    alt="Remenant Health Banner 2"
                                    loading="lazy"
                                >
                            </picture>
                        </div>
                        <div class="hero-slide" data-slide>
                            <picture>
                                <source media="(max-width: 640px)" srcset="{{ asset('images/banners/mobile-bg/remenant-bg3.jpg') }}">
                                <img
                                    src="{{ asset('images/banners/remenant-bg19.jpg') }}"
                                    alt="Remenant Health Banner 3"
                                    loading="lazy"
                                >
                            </picture>
                        </div>
                        <div class="hero-slide" data-slide>
                            <picture>
                                <source media="(max-width: 640px)" srcset="{{ asset('images/banners/mobile-bg/remenant-bg4.jpg') }}">
                                <img
                                    src="{{ asset('images/banners/remenant-bg18.jpg') }}"
                                    alt="Remenant Health Banner 4"
                                    loading="lazy"
                                >
                            </picture>
                        </div>
                        <div class="hero-slide" data-slide>
                            <picture>
                                <source media="(max-width: 640px)" srcset="{{ asset('images/banners/mobile-bg/remenant-bg5.jpg') }}">
                                <img
                                    src="{{ asset('images/banners/remenant-bg21.jpg') }}"
                                    alt="Remenant Health Banner 5"
                                    loading="lazy"
                                >
                            </picture>
                        </div>
                    </div>
                </div>

                <button
                    type="button"
                    class="hero-slider__nav hero-slider__nav--prev"
                    aria-label="Previous slide"
                    data-prev
                >
                    <i data-lucide="chevron-left" class="h-6 w-6"></i>
                </button>
                <button
                    type="button"
                    class="hero-slider__nav hero-slider__nav--next"
                    aria-label="Next slide"
                    data-next
                >
                    <i data-lucide="chevron-right" class="h-6 w-6"></i>
                </button>

                <div class="hero-slider__dots" role="tablist" aria-label="Hero slides">
                    <button type="button" class="hero-dot is-active" aria-label="Slide 1" data-dot></button>
                    <button type="button" class="hero-dot" aria-label="Slide 2" data-dot></button>
                    <button type="button" class="hero-dot" aria-label="Slide 3" data-dot></button>
                    <button type="button" class="hero-dot" aria-label="Slide 4" data-dot></button>
                    <button type="button" class="hero-dot" aria-label="Slide 5" data-dot></button>
                </div>
            </div>
        </div>
    </section>



    <!-- Trust / highlight strip (full width) -->
    <section class="bg-[var(--bg-main)]">
        <div class="w-full">
            <div class="relative overflow-hidden bg-gradient-to-r from-[#FFF2E8] via-[#FFE7D5] to-[#FDE7D7] px-4 py-8 sm:px-8">
                <div class="mx-auto max-w-7xl">
                    <!-- Decorative fruit background icons -->
                    <div class="pointer-events-none absolute -left-2 top-6 flex flex-col gap-3 opacity-85 sm:left-4">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/65 text-xl shadow-sm">🍉</span>
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/60 text-lg shadow-sm">🍓</span>
                    </div>
                    <div class="pointer-events-none absolute -right-2 bottom-6 flex flex-col gap-3 opacity-85 sm:right-4">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/65 text-xl shadow-sm">🍊</span>
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/60 text-lg shadow-sm">🍏</span>
                    </div>

                    <p class="text-center text-xl font-extrabold tracking-tight text-[color:var(--secondary)] sm:text-2xl">
                        Trusted by Thousands Across India
                    </p>

                    <div class="relative mt-6 grid grid-cols-3 gap-2 sm:gap-4">
                        <div class="flex flex-col items-center justify-center gap-1 px-2 py-2 text-center sm:flex-row sm:gap-3 sm:px-4 sm:py-3 sm:text-left">
                            <img
                                src="{{ asset('images/icons/clinically-tested.png') }}"
                                alt="Clinically tested"
                                class="h-8 w-8 object-contain sm:h-14 sm:w-14 lg:h-16 lg:w-16"
                            >
                            <p class="text-[10px] font-extrabold leading-tight text-[color:var(--secondary)] sm:text-sm">
                                Clean Label<br>Certified
                            </p>
                        </div>

                        <div class="flex flex-col items-center justify-center gap-1 px-2 py-2 text-center sm:flex-row sm:gap-3 sm:px-4 sm:py-3 sm:text-left">
                            <img
                                src="{{ asset('images/icons/premium-quality.png') }}"
                                alt="Premium quality"
                                class="h-8 w-8 object-contain sm:h-14 sm:w-14 lg:h-16 lg:w-16"
                            >
                            <p class="text-[10px] font-extrabold leading-tight text-[color:var(--secondary)] sm:text-sm">
                                Premium Quality<br>Ingredients
                            </p>
                        </div>

                        <div class="flex flex-col items-center justify-center gap-1 px-2 py-2 text-center sm:flex-row sm:gap-3 sm:px-4 sm:py-3 sm:text-left">
                            <img
                                src="{{ asset('images/icons/safe-daily-use.png') }}"
                                alt="Safe and effective"
                                class="h-8 w-8 object-contain sm:h-14 sm:w-14 lg:h-16 lg:w-16"
                            >
                            <p class="text-[10px] font-extrabold leading-tight text-[color:var(--secondary)] sm:text-sm">
                                Safe &amp; Effective<br>Daily Use
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="shop" class="bg-[var(--bg-main)]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-extrabold text-[color:var(--text-primary)]">Best Sellers</h2>
                    <p class="mt-1 text-sm text-[color:var(--text-secondary)]">
                        Shop our best-selling wellness formulas.
                    </p>
                </div>
                <a href="#all" class="self-start rounded-full bg-black/5 px-4 py-2 text-sm font-semibold hover:bg-black/10 transition sm:self-auto">View all</a>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($products as $product)
                    @php
                        $discount = (int) round((1 - ($product['price'] / max(1, $product['mrp']))) * 100);
                    @endphp
                    <div class="group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5 hover:shadow-md transition">
                        <button type="button" class="absolute right-3 top-3 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 ring-1 ring-black/10 hover:bg-white transition" aria-label="Add to wishlist">
                            <i data-lucide="heart" class="h-5 w-5 text-[color:var(--text-primary)]"></i>
                        </button>

                        <div class="relative aspect-square overflow-hidden bg-[var(--bg-section)]">
                            <img
                                src="{{ asset('images/products/'.$product['image']) }}"
                                alt="{{ $product['title'] }}"
                                class="h-full w-full object-contain"
                                loading="lazy"
                            >
                            <div class="absolute left-3 top-3 rounded-full bg-[var(--primary)] px-3 py-1 text-xs font-extrabold text-white">
                                -{{ $discount }}%
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col p-4">
                            <p class="text-xs font-extrabold tracking-wide text-[color:var(--primary)]">{{ $product['tagline'] }}</p>
                            <p class="mt-1 text-sm font-extrabold text-[color:var(--text-primary)]">{{ $product['title'] }}</p>

                            <div class="mt-3 flex items-center justify-between gap-3">
                                <div class="flex items-baseline gap-2">
                                    <p class="text-lg font-extrabold text-[color:var(--primary)]">₹{{ number_format($product['price']) }}</p>
                                    <p class="text-xs font-semibold text-[color:var(--text-muted)] line-through">₹{{ number_format($product['mrp']) }}</p>
                                </div>
                                <div class="flex items-center gap-1 rounded-full bg-black/5 px-2 py-1 text-xs font-semibold text-[color:var(--text-secondary)]">
                                    <i data-lucide="star" class="h-4 w-4"></i>
                                    {{ number_format($product['rating'], 1) }} ({{ number_format($product['reviews']) }})
                                </div>
                            </div>

                            <div class="mt-auto pt-3">
                                <button type="button" class="w-full rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-extrabold text-white hover:opacity-95 transition">
                                    Add to cart
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Combo Offers Section -->
            <div class="mt-12 relative" data-combo-slider-container>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between mb-10">
                    <div>
                        {{-- <span class="text-xs font-extrabold uppercase tracking-widest text-[color:var(--primary)]">Special Bundles</span> --}}
                        <h2 class="mt-2 text-4xl font-extrabold text-[color:var(--text-primary)]">Combo Offers</h2>
                        <p class="mt-4 max-w-2xl text-[color:var(--text-secondary)]">
                            We curate the perfect health combinations to help you achieve your wellness goals faster and more affordably.
                        </p>
                    </div>
                    
                    <!-- Slider Navigation -->
                    <div class="hidden sm:flex items-center gap-3">
                        <button type="button" data-combo-prev class="h-12 w-12 rounded-full bg-white shadow-sm ring-1 ring-black/5 flex items-center justify-center hover:bg-gray-50 transition active:scale-95">
                            <i data-lucide="chevron-left" class="h-6 w-6 text-[color:var(--text-primary)]"></i>
                        </button>
                        <button type="button" data-combo-next class="h-12 w-12 rounded-full bg-white shadow-sm ring-1 ring-black/5 flex items-center justify-center hover:bg-gray-50 transition active:scale-95">
                            <i data-lucide="chevron-right" class="h-6 w-6 text-[color:var(--text-primary)]"></i>
                        </button>
                    </div>
                </div>

                <div class="relative overflow-hidden -my-6 py-6">
                    <div class="flex gap-6 transition-transform duration-700 ease-in-out cursor-grab active:cursor-grabbing" data-combo-slider-track>
                    @foreach ($combos as $combo)
                        @php
                            $discount = (int) round((1 - ($combo['price'] / max(1, $combo['mrp']))) * 100);
                        @endphp
                        <div class="group relative flex w-[85vw] flex-shrink-0 snap-start flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5 hover:shadow-md transition sm:w-[500px] sm:flex-row">
                            <div class="relative aspect-square w-full overflow-hidden bg-[var(--bg-section)] sm:aspect-auto sm:w-[220px]">
                                <img
                                    src="{{ asset('images/products/'.$combo['image']) }}"
                                    alt="{{ $combo['title'] }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                    loading="lazy"
                                >
                                <div class="absolute left-3 top-3 rounded-full bg-black/80 backdrop-blur-sm px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-white">
                                    {{ $combo['badge'] }}
                                </div>
                            </div>

                            <div class="flex flex-1 flex-col p-6">
                                <p class="text-xs font-extrabold tracking-wide text-[color:var(--primary)] uppercase">Save {{ $discount }}% Today</p>
                                <h3 class="mt-1 text-lg font-extrabold text-[color:var(--text-primary)] leading-tight">{{ $combo['title'] }}</h3>
                                <p class="mt-2 text-sm text-[color:var(--text-secondary)] line-clamp-2">{{ $combo['description'] }}</p>

                                <div class="mt-auto pt-6 flex items-center justify-between gap-4">
                                    <div class="flex flex-col">
                                        <p class="text-xs text-[color:var(--text-muted)] line-through">₹{{ number_format($combo['mrp']) }}</p>
                                        <p class="text-xl font-extrabold text-[color:var(--text-primary)]">₹{{ number_format($combo['price']) }}</p>
                                    </div>
                                    <button type="button" class="rounded-full bg-[color:var(--secondary)] px-4 py-2 text-xs font-extrabold text-white hover:opacity-95 transition">
                                        Grab Combo
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Why Remenant Banner (Short Height) -->
    <section class="relative overflow-visible mt-8 mb-32">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[2rem] bg-[var(--bg-sage)] px-8 py-12 sm:px-16 sm:py-16">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center">
                    
                    <!-- Content Right -->
                    <div class="relative z-20 order-2 lg:order-2 text-center lg:text-left">
                        <h2 class="text-3xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-4xl">
                            Need Expert Health Advice? <br class="hidden sm:block"> We are Here to Help
                        </h2>
                        <p class="mt-4 text-base text-[color:var(--text-primary)]/80 max-w-lg mx-auto lg:mx-0">
                            Got questions about our products or your wellness journey? Chat with our experts for personalized recommendations and support.
                        </p>
                        
                        <div class="mt-10 flex flex-wrap justify-center lg:justify-start gap-4">
                            <!-- WhatsApp Button -->
                            <a href="https://wa.me/yournumber" target="_blank" class="inline-flex items-center gap-3 rounded-[20px] bg-[#25D366] px-8 py-4 text-white hover:opacity-90 transition shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <svg class="h-6 w-6 fill-white" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                <div class="text-left">
                                    <p class="text-[10px] uppercase opacity-70 leading-none font-bold tracking-wider">Chat with us</p>
                                    <p class="text-base font-extrabold leading-tight">WhatsApp</p>
                                </div>
                            </a>
                            <!-- Contact Form Button -->
                            <a href="/contact" class="inline-flex items-center gap-3 rounded-[20px] bg-black px-8 py-4 text-white hover:bg-black/90 transition shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <i data-lucide="mail" class="h-6 w-6"></i>
                                <div class="text-left">
                                    <p class="text-[10px] uppercase opacity-70 leading-none font-bold tracking-wider">Drop a message</p>
                                    <p class="text-base font-extrabold leading-tight">Contact Form</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Mockup Left (Overflowing) -->
                    <div class="relative order-1 lg:order-1 flex justify-center lg:block">
                        <div class="lg:absolute lg:-bottom-32 lg:left-0 w-[280px] sm:w-[350px] lg:w-[450px]">
                            <img 
                                src="{{ asset('images/products/remenant-product15.jpg') }}" 
                                alt="App Mockup" 
                                class="w-full rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.3)] ring-4 ring-white/30 transform lg:-rotate-6 hover:rotate-0 transition duration-500"
                            >
                        </div>
                    </div>
                </div>
                
                <!-- Decorative Circles -->
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-black/5 blur-3xl"></div>
            </div>
        </div>
    </section>

    <!-- How it Works section -->
    <section class="bg-[var(--bg-light)]">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                <div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-4xl">Drop. Fizz. Enjoy.</h2>
                    <p class="mt-4 text-lg text-[color:var(--text-secondary)]">The smartest way to take your daily supplements. Our effervescent formula ensures maximum absorption and great taste.</p>
                    
                    <div class="mt-10 space-y-8">
                        @php
                            $steps = [
                                ['title' => 'Drop 1 Tablet', 'desc' => 'Drop one effervescent tablet into 200ml of water.', 'icon' => 'droplets'],
                                ['title' => 'Watch it Fizz', 'desc' => 'Wait for the tablet to dissolve completely.', 'icon' => 'wind'],
                                ['title' => 'Sip & Refresh', 'desc' => 'Enjoy your delicious, nutrient-packed wellness drink.', 'icon' => 'drink'],
                            ];
                        @endphp
                        @foreach ($steps as $index => $step)
                            <div class="flex gap-4">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--primary)] text-sm font-bold text-white">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <h4 class="font-bold text-[color:var(--text-primary)]">{{ $step['title'] }}</h4>
                                    <p class="text-sm text-[color:var(--text-secondary)]">{{ $step['desc'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="aspect-square rounded-3xl bg-gradient-to-br from-[var(--primary-soft)] to-white p-8 ring-1 ring-black/5">
                        <img 
                            src="{{ asset('images/products/remenant-product10.jpg') }}" 
                            alt="How it works" 
                            class="h-full w-full object-contain"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Support / Ingredients Section -->
    <section class="bg-[var(--bg-main)]">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-[#1D1D1F] px-8 py-16 text-white sm:px-16 sm:py-24">
                <div class="mx-auto max-w-3xl text-center">
                    <h2 class="text-3xl font-extrabold tracking-tight sm:text-5xl">Engineered for Excellence.</h2>
                    <p class="mt-6 text-xl text-gray-400">We combine clinical research with premium plant-based ingredients to deliver wellness that actually works.</p>
                </div>
                
                <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-3">
                    <div class="text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/10">
                            <i data-lucide="microscope" class="h-8 w-8 text-[var(--primary)]"></i>
                        </div>
                        <h3 class="mt-6 text-xl font-bold">Clinically Proven</h3>
                        <p class="mt-2 text-gray-400">Formulas backed by scientific research and testing.</p>
                    </div>
                    <div class="text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/10">
                            <i data-lucide="leaf" class="h-8 w-8 text-[var(--primary)]"></i>
                        </div>
                        <h3 class="mt-6 text-xl font-bold">100% Vegan</h3>
                        <p class="mt-2 text-gray-400">Clean, plant-based ingredients with zero animal products.</p>
                    </div>
                    <div class="text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-white/10">
                            <i data-lucide="check-circle" class="h-8 w-8 text-[var(--primary)]"></i>
                        </div>
                        <h3 class="mt-6 text-xl font-bold">Certified Clean</h3>
                        <p class="mt-2 text-gray-400">No harmful chemicals, heavy metals, or artificial fillers.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-[var(--bg-light)]">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-[color:var(--text-primary)]">Loved by Our Community</h2>
                <p class="mt-4 text-lg text-[color:var(--text-secondary)]">Join thousands who have made Remenant a part of their daily ritual.</p>
            </div>
            
            <div class="mt-16 grid grid-cols-1 gap-8 lg:grid-cols-3">
                @php
                    $testimonials = [
                        ['name' => 'Ananya S.', 'text' => 'The Glutathione tablets have completely changed my morning routine. My skin is noticeably brighter!', 'rating' => 5],
                        ['name' => 'Rahul M.', 'text' => 'Finally a Vitamin C supplement that actually tastes good and feels effective. No more boring pills.', 'rating' => 5],
                        ['name' => 'Priya K.', 'text' => 'Perfect for my busy lifestyle. I just drop a tablet in my bottle and I am good to go. Love the Biotin!', 'rating' => 5],
                    ];
                @endphp
                @foreach ($testimonials as $testimonial)
                    <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-black/5">
                        <div class="flex gap-1 text-orange-400">
                            @for ($i = 0; $i < $testimonial['rating']; $i++)
                                <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                            @endfor
                        </div>
                        <p class="mt-4 text-lg font-semibold italic text-[color:var(--text-primary)]">"{{ $testimonial['text'] }}"</p>
                        <div class="mt-6 flex items-center gap-3">
                            <div class="h-10 w-10 rounded-full bg-[var(--primary-soft)] flex items-center justify-center font-bold text-[var(--primary)]">
                                {{ substr($testimonial['name'], 0, 1) }}
                            </div>
                            <span class="font-bold text-[color:var(--text-primary)]">{{ $testimonial['name'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="bg-[var(--bg-main)]">
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-[var(--primary)] px-8 py-16 text-center text-white sm:px-16">
                <!-- Background decoration -->
                <div class="pointer-events-none absolute -right-24 -top-24 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="pointer-events-none absolute -left-24 -bottom-24 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                
                <h2 class="relative text-3xl font-extrabold sm:text-4xl">Join the Wellness Revolution</h2>
                <p class="relative mt-4 text-lg text-white/80">Subscribe to get exclusive offers, wellness tips, and early access to new launches.</p>
                
                <form class="relative mt-10 mx-auto flex max-w-md flex-col gap-3 sm:flex-row">
                    <input 
                        type="email" 
                        placeholder="Enter your email" 
                        class="flex-1 rounded-full border-none bg-white px-6 py-3 text-black placeholder:text-gray-400 focus:ring-2 focus:ring-white/50"
                        required
                    >
                    <button type="submit" class="rounded-full bg-black px-8 py-3 font-bold transition hover:bg-black/80">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection