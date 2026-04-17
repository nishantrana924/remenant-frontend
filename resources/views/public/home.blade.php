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
                'image' => 'remanent010.png',
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
                'image' => 'remanent011.png',
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
                'image' => 'remanent012.png',
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
                'image' => 'remanent013.png',
                'color' => 'from-green-500 to-emerald-500',
                'benefits' => ['Supports Blood Sugar Balance', 'Promotes Clear Skin', 'Instant Refreshment', 'Helps Maintain pH Balance', 'Appetite Control Support'],
            ],
        ];
    @endphp

    <section class="bg-[var(--bg-main)]">
        <div class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8">
            <div
                class="hero-slider relative overflow-hidden rounded-3xl ring-1 ring-black/5"
                data-hero-slider
                data-interval="4500"
            >
                <div class="hero-slider__viewport">
                    <div class="hero-slide is-active" data-slide>
                        <picture>
                            <source media="(max-width: 640px)" srcset="https://assets.myntassets.com/f_webp,w_768,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/9/f8f09845-e453-49f8-ac3f-445fde6b59791623250209264-DK_Flip-Flops.jpg">
                            <img
                                src="https://assets.myntassets.com/f_webp,w_1400,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/9/f8f09845-e453-49f8-ac3f-445fde6b59791623250209264-DK_Flip-Flops.jpg"
                                alt="Deals banner 1"
                                loading="eager"
                            >
                        </picture>
                    </div>
                    <div class="hero-slide" data-slide>
                        <picture>
                            <source media="(max-width: 640px)" srcset="https://assets.myntassets.com/f_webp,w_768,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/9/f008298e-a446-4863-afdb-a1b75ab99aa81623250209248-DK_WFH.jpg">
                            <img
                                src="https://assets.myntassets.com/f_webp,w_1400,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/9/f008298e-a446-4863-afdb-a1b75ab99aa81623250209248-DK_WFH.jpg"
                                alt="Deals banner 2"
                                loading="lazy"
                            >
                        </picture>
                    </div>
                    <div class="hero-slide" data-slide>
                        <picture>
                            <source media="(max-width: 640px)" srcset="https://assets.myntassets.com/f_webp,w_768,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/8/b07ef9e8-b1b7-4b9d-9d15-e633d7ac70a91623162255312-DK-MAIN-BANNER.jpg">
                            <img
                                src="https://assets.myntassets.com/f_webp,w_1400,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/8/b07ef9e8-b1b7-4b9d-9d15-e633d7ac70a91623162255312-DK-MAIN-BANNER.jpg"
                                alt="Deals banner 3"
                                loading="lazy"
                            >
                        </picture>
                    </div>
                    <div class="hero-slide" data-slide>
                        <picture>
                            <source media="(max-width: 640px)" srcset="https://assets.myntassets.com/f_webp,w_768,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/9/182c7932-31f3-44a7-bcac-fe141fd412d21623250209232-DK_KidsWear.jpg">
                            <img
                                src="https://assets.myntassets.com/f_webp,w_1400,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/9/182c7932-31f3-44a7-bcac-fe141fd412d21623250209232-DK_KidsWear.jpg"
                                alt="Deals banner 4"
                                loading="lazy"
                            >
                        </picture>
                    </div>
                    <div class="hero-slide" data-slide>
                        <picture>
                            <source media="(max-width: 640px)" srcset="https://assets.myntassets.com/f_webp,w_768,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/9/64365564-c127-409f-b021-39287ef57d041623250209213-DK_OmniStyles.jpg">
                            <img
                                src="https://assets.myntassets.com/f_webp,w_1400,c_limit,fl_progressive,dpr_2.0/assets/images/2021/6/9/64365564-c127-409f-b021-39287ef57d041623250209213-DK_OmniStyles.jpg"
                                alt="Deals banner 5"
                                loading="lazy"
                            >
                        </picture>
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
        <div class="mx-auto max-w-7xl px-4 pb-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#FFF2E8] via-[#FFE7D5] to-[#FDE7D7] px-4 py-7 ring-1 ring-[#f4c8a8]/60 sm:px-8">
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
    </section>

    <section class="bg-[var(--bg-light)]">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 py-10 sm:px-6 lg:grid-cols-12 lg:px-8">
            <div class="lg:col-span-7">
                <h1 class="text-3xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-4xl">
                    Discover products you’ll love.
                </h1>
                <p class="mt-3 max-w-2xl text-base text-[color:var(--text-secondary)] sm:text-lg">
                    A clean, fast storefront UI inspired by your reference — with brand colors controlled via CSS variables.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="#shop" class="btn-primary">Shop Now</a>
                    <a href="#categories" class="rounded-full border border-black/10 bg-white px-4 py-2 font-semibold hover:bg-black/5 transition">
                        Browse Categories
                    </a>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-[color:var(--text-primary)]">Featured</p>
                        <span class="rounded-full bg-[var(--primary-soft)] px-3 py-1 text-xs font-semibold text-[color:var(--primary)]">
                            Top picks
                        </span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach ($products as $product)
                            @php
                                $discount = (int) round((1 - ($product['price'] / max(1, $product['mrp']))) * 100);
                            @endphp
                            <a href="#shop" class="group rounded-2xl bg-[var(--bg-section)] p-3 ring-1 ring-black/5 hover:bg-white transition">
                                <div class="flex items-start justify-between gap-2">
                                    <p class="text-xs font-extrabold text-[color:var(--text-primary)]">{{ $product['tagline'] }}</p>
                                    <span class="shrink-0 rounded-full bg-white px-2 py-1 text-[10px] font-extrabold text-[color:var(--primary)] ring-1 ring-black/5">
                                        -{{ $discount }}%
                                    </span>
                                </div>
                                <div class="mt-2 flex items-center justify-between gap-2">
                                    <p class="text-sm font-extrabold text-[color:var(--primary)]">₹{{ number_format($product['price']) }}</p>
                                    <p class="text-xs font-semibold text-[color:var(--text-muted)] line-through">₹{{ number_format($product['mrp']) }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
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

                        <div class="relative h-56 overflow-hidden bg-[var(--bg-section)]">
                            <img
                                src="{{ asset('images/one/'.$product['image']) }}"
                                alt="{{ $product['title'] }}"
                                class="h-full w-full bg-white object-contain p-4"
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
        </div>
    </section>
@endsection