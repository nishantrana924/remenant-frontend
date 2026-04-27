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
    </style>
    @endpush
    <div class="bg-[var(--bg-main)]">
        @php
            $galleryImages = array_values(array_unique(array_merge([$product['image']], $product['gallery'])));
        @endphp

        <!-- Product Hero Section -->
        <section class="mx-auto max-w-[1600px] px-4 py-2 sm:px-6 lg:px-12 lg:pt-5">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:items-start">
                
                <!-- Left: Product Images -->
                <div class="space-y-6 self-start lg:sticky lg:top-0 lg:h-fit">
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
                        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-5xl lg:text-6xl">
                            {{ $product['title'] }}
                        </h1>
                        
                        <div class="mt-6 flex items-start justify-between gap-3 sm:items-center sm:gap-6">
                            <a href="#reviews" class="flex items-center gap-3 sm:gap-6 group min-w-0">
                                <div class="flex shrink-0 items-center gap-1.5 rounded-full bg-orange-50 px-3 py-1.5 text-orange-600 group-hover:bg-orange-100 transition">
                                    <i data-lucide="star" class="h-5 w-5 fill-current"></i>
                                    <span class="text-sm font-black">{{ $product['rating'] }}</span>
                                </div>
                                <span class="text-[11px] sm:text-sm font-bold leading-tight text-[color:var(--text-secondary)] group-hover:text-[color:var(--primary)] transition underline decoration-dotted underline-offset-4">{{ number_format($product['reviews']) }} Verified Reviews</span>
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
                                <i data-lucide="arrow-down" class="h-6 w-6 sm:h-9 sm:w-9 stroke-[4px]"></i>
                                <span class="text-3xl sm:text-5xl font-black">{{ $discount }}%</span>
                            </div>
                            <span class="text-xl sm:text-3xl font-black text-gray-400/50 line-through decoration-gray-400/30">₹{{ number_format($product['mrp']) }}</span>
                            <span class="text-4xl sm:text-6xl font-black text-[color:var(--text-primary)] tracking-tight">₹{{ number_format($product['price']) }}</span>
                        </div>
                        <p class="mt-2 text-xs font-bold text-[color:var(--text-muted)] uppercase tracking-wider">Inclusive of all taxes</p>

                        <div class="mt-8 relative group/desc">
                            <div id="product-description" class="text-lg leading-relaxed text-[color:var(--text-secondary)] line-clamp-3 transition-all duration-500">
                                {{ $product['description'] }}
                            </div>
                            <button type="button" 
                                    id="read-more-btn"
                                    onclick="toggleDescription()"
                                    class="mt-2 text-sm font-black text-[color:var(--primary)] hover:underline underline-offset-4 uppercase tracking-widest">
                                Read More
                            </button>
                        </div>

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

                        <!-- Highlights -->
                        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach(array_slice($product['benefits'], 0, 4) as $benefit)
                                <div class="flex items-start gap-3 rounded-2xl bg-white p-4 ring-1 ring-black/5">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[var(--primary-soft)] text-[color:var(--primary)]">
                                        <i data-lucide="{{ $benefit['icon'] }}" class="h-5 w-5"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-[color:var(--text-primary)]">{{ $benefit['title'] }}</p>
                                        <p class="text-xs text-[color:var(--text-secondary)]">{{ $benefit['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <!-- Enhanced Trust Signals -->
                    <div class="mt-6 grid grid-cols-2 gap-x-4 gap-y-4 sm:flex sm:flex-wrap sm:items-center sm:justify-between border-t border-black/5 pt-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-orange-50 text-[color:var(--primary)] shadow-sm">
                                <i data-lucide="truck" class="h-6 w-6"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-[0.1em] text-[color:var(--text-primary)] leading-tight">Fast <br> Delivery</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 shadow-sm">
                                <i data-lucide="shield-check" class="h-6 w-6"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-[0.1em] text-[color:var(--text-primary)] leading-tight">100% <br> Secure</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-green-50 text-green-600 shadow-sm">
                                <i data-lucide="refresh-cw" class="h-6 w-6"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-[0.1em] text-[color:var(--text-primary)] leading-tight">Easy <br> Returns</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 shadow-sm">
                                <i data-lucide="leaf" class="h-6 w-6"></i>
                            </div>
                            <p class="text-[11px] font-black uppercase tracking-[0.1em] text-[color:var(--text-primary)] leading-tight">Vegan <br> & Pure</p>
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
        <!-- Product Highlights & Ritual Section -->
        <section class="py-20 sm:py-32 bg-white overflow-hidden border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-center">
                    
                    <!-- Left: Highlights -->
                    <div class="lg:col-span-7">
                        <span class="inline-block text-xs font-black uppercase tracking-[0.4em] text-[color:var(--primary)] mb-6">Experience Excellence</span>
                        <h2 class="text-4xl sm:text-6xl font-black tracking-tighter uppercase mb-16 text-[color:var(--text-primary)] leading-[0.9]">
                            Advanced <br> <span class="text-[color:var(--primary)] italic">Formulation</span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-12 gap-y-12">
                            <!-- Feature 1 -->
                            <div class="group">
                                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-orange-50 text-[color:var(--primary)] transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-orange-200/50">
                                    <i data-lucide="zap" class="h-7 w-7"></i>
                                </div>
                                <h4 class="text-lg font-black uppercase tracking-wider text-[color:var(--text-primary)] mb-3">Maximum Absorption</h4>
                                <p class="text-base text-[color:var(--text-secondary)] leading-relaxed">100% Bioavailable Effervescent Formula designed for rapid, effective action.</p>
                            </div>

                            <!-- Feature 2 -->
                            <div class="group">
                                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-emerald-200/50">
                                    <i data-lucide="leaf" class="h-7 w-7"></i>
                                </div>
                                <h4 class="text-lg font-black uppercase tracking-wider text-[color:var(--text-primary)] mb-3">Pure Ingredients</h4>
                                <p class="text-base text-[color:var(--text-secondary)] leading-relaxed">Clean Label Certified Ingredients with no hidden additives or fillers.</p>
                            </div>

                            <!-- Feature 3 -->
                            <div class="group">
                                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-rose-50 text-rose-600 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-rose-200/50">
                                    <i data-lucide="ban" class="h-7 w-7"></i>
                                </div>
                                <h4 class="text-lg font-black uppercase tracking-wider text-[color:var(--text-primary)] mb-3">Zero Compromise</h4>
                                <p class="text-base text-[color:var(--text-secondary)] leading-relaxed">Zero Sugar & No Artificial Colors or flavors. Pure wellness in every drop.</p>
                            </div>

                            <!-- Feature 4 -->
                            <div class="group">
                                <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-blue-600 transition-all duration-300 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-blue-200/50">
                                    <i data-lucide="activity" class="h-7 w-7"></i>
                                </div>
                                <h4 class="text-lg font-black uppercase tracking-wider text-[color:var(--text-primary)] mb-3">Fast & Gentle</h4>
                                <p class="text-base text-[color:var(--text-secondary)] leading-relaxed">Fast Acting & Gentle on the Stomach, optimized for daily consumption.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: The Ritual Card -->
                    <div class="lg:col-span-5 relative">
                        <div class="relative z-10 rounded-[3rem] bg-[var(--secondary)] p-8 sm:p-14 overflow-hidden shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)]">
                            <!-- Background Decor -->
                            <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-[var(--primary)]/20 blur-[100px]"></div>
                            <div class="absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-white/5 blur-[80px]"></div>
                            
                            <h3 class="relative z-10 text-3xl font-black tracking-tight uppercase text-white mb-12 flex items-center gap-4">
                                <span class="h-px flex-1 bg-white/10"></span>
                                The Ritual
                                <span class="h-px flex-1 bg-white/10"></span>
                            </h3>

                            <div class="relative z-10 space-y-12">
                                <div class="flex gap-8 group">
                                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[var(--primary)] font-black text-white text-2xl shadow-xl shadow-[var(--primary)]/20 transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">1</span>
                                    <div>
                                        <h4 class="text-xl font-bold uppercase tracking-widest text-white">Drop it</h4>
                                        <p class="mt-2 text-base text-white/60 leading-relaxed">Drop one tablet into 200ml of fresh water.</p>
                                    </div>
                                </div>

                                <div class="flex gap-8 group">
                                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 border border-white/10 font-black text-white text-2xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">2</span>
                                    <div>
                                        <h4 class="text-xl font-bold uppercase tracking-widest text-white">Fizz it</h4>
                                        <p class="mt-2 text-base text-white/60 leading-relaxed">Watch the pure wellness dissolve instantly.</p>
                                    </div>
                                </div>

                                <div class="flex gap-8 group">
                                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 border border-white/10 font-black text-white text-2xl transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">3</span>
                                    <div>
                                        <h4 class="text-xl font-bold uppercase tracking-widest text-white">Fuel Up</h4>
                                        <p class="mt-2 text-base text-white/60 leading-relaxed">Drink and empower your daily wellness goals.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Outer Floating Decor -->
                        <div class="absolute -top-6 -left-6 h-24 w-24 rounded-full bg-[var(--primary)]/5 blur-3xl animate-pulse"></div>
                        <div class="absolute -bottom-10 -right-10 h-40 w-40 rounded-full bg-[var(--primary)]/10 blur-[80px]"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section id="reviews" class="py-12 sm:py-24 bg-[var(--bg-main)] scroll-mt-24">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="flex flex-col lg:flex-row gap-16">
                    <!-- Left: Rating Summary (Sticky) -->
                    <div class="lg:w-1/3 lg:self-start lg:sticky lg:top-28 h-fit">
                        <div class="p-8 sm:p-10 rounded-[2.5rem] bg-white shadow-sm ring-1 ring-black/5">
                            <h2 class="text-3xl sm:text-4xl font-black italic tracking-tight text-[color:var(--text-primary)]">Customer Reviews</h2>
                            <div class="mt-8 flex items-end gap-5">
                                <span class="text-7xl font-black leading-none text-[color:var(--text-primary)]">{{ $product['rating'] }}</span>
                                <div class="flex flex-col gap-1 pb-1">
                                    <div class="flex text-orange-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <i data-lucide="star" class="h-5 w-5 fill-current"></i>
                                        @endfor
                                    </div>
                                    <span class="text-sm font-bold text-[color:var(--text-secondary)]">Based on {{ number_format($product['reviews']) }} reviews</span>
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
                                        <span class="w-4 text-xs font-black text-[color:var(--text-secondary)]">{{ $r['stars'] }}</span>
                                        <i data-lucide="star" class="h-3 w-3 text-orange-400 fill-current"></i>
                                        <div class="flex-1 h-1.5 rounded-full bg-black/5 overflow-hidden">
                                            <div class="h-full bg-orange-400 rounded-full" style="width: {{ ($r['count'] / 982) * 100 }}%"></div>
                                        </div>
                                        <span class="w-12 text-xs font-bold text-[color:var(--text-muted)] text-right">{{ $r['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="mt-10 w-full rounded-2xl bg-[var(--primary)] py-5 text-sm font-black uppercase tracking-widest text-white shadow-xl shadow-[var(--primary)]/20 hover:bg-[var(--primary-hover)] transition active:scale-95">
                                Write a review
                            </button>
                        </div>
                    </div>

                    <!-- Right: Review List -->
                    <div class="flex-1 space-y-8">
                        <!-- Filters -->
                        <div class="flex items-center justify-between border-b border-black/5 pb-8">
                            <h3 class="text-xl font-black uppercase tracking-wider text-[color:var(--text-primary)]">Most Relevant</h3>
                            <button class="flex items-center gap-2 text-xs font-black uppercase tracking-widest text-[color:var(--primary)]">
                                <i data-lucide="filter" class="h-4 w-4"></i>
                                Filter Reviews
                            </button>
                        </div>
                        @php
                            $sampleReviews = [
                                [
                                    'name' => 'Aditi Sharma',
                                    'date' => '2 days ago',
                                    'rating' => 5,
                                    'title' => 'Truly Refreshing!',
                                    'content' => 'I’ve been taking these for a month now and I can definitely feel the difference. It’s so much easier than swallowing big pills and the taste is amazing!',
                                    'verified' => true
                                ],
                                [
                                    'name' => 'Rohan Gupta',
                                    'date' => '1 week ago',
                                    'rating' => 5,
                                    'title' => 'Best Wellness Product',
                                    'content' => 'Highly recommend for anyone with a busy lifestyle. Quick, easy, and effective. The Apple Cider Vinegar flavor is my personal favorite.',
                                    'verified' => true
                                ],
                                [
                                    'name' => 'Karan Patel',
                                    'date' => '2 weeks ago',
                                    'rating' => 4,
                                    'title' => 'Great but slightly sweet',
                                    'content' => 'The quality is top-notch and it fizzes perfectly. Just wish it was a tiny bit less sweet, but overall a great product that I will buy again.',
                                    'verified' => true
                                ]
                            ];
                        @endphp

                        @foreach($sampleReviews as $review)
                            <div class="bg-white p-8 sm:p-10 rounded-[2.5rem] shadow-sm ring-1 ring-black/5 transition-all hover:ring-[var(--primary)]/20 hover:shadow-md">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-orange-50 text-[color:var(--primary)] font-black text-lg">
                                            {{ substr($review['name'], 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-black text-[color:var(--text-primary)] text-lg leading-none">{{ $review['name'] }}</h4>
                                            @if($review['verified'])
                                                <div class="mt-1.5 flex items-center gap-1 text-[9px] font-black uppercase tracking-widest text-green-600 bg-green-50 px-2 py-0.5 rounded-full w-fit">
                                                    <i data-lucide="check-circle" class="h-3 w-3"></i>
                                                    Verified Buyer
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-[color:var(--text-muted)]">{{ $review['date'] }}</span>
                                </div>

                                <div class="mt-8 flex text-orange-400 gap-0.5">
                                    @for($i = 0; $i < $review['rating']; $i++)
                                        <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                                    @endfor
                                    @for($i = 0; $i < 5 - $review['rating']; $i++)
                                        <i data-lucide="star" class="h-4 w-4 text-gray-200"></i>
                                    @endfor
                                </div>

                                <h5 class="mt-4 text-xl font-black text-[color:var(--text-primary)] tracking-tight">{{ $review['title'] }}</h5>
                                <p class="mt-4 text-base leading-relaxed text-[color:var(--text-secondary)]">
                                    {{ $review['content'] }}
                                </p>

                                <div class="mt-8 flex items-center gap-6 pt-8 border-t border-black/5">
                                    <button type="button" class="flex items-center gap-2 text-xs font-bold text-[color:var(--text-muted)] hover:text-[color:var(--text-primary)] transition">
                                        <i data-lucide="thumbs-up" class="h-4 w-4"></i>
                                        Helpful (12)
                                    </button>
                                    <button type="button" class="flex items-center gap-2 text-xs font-bold text-[color:var(--text-muted)] hover:text-[color:var(--text-primary)] transition">
                                        <i data-lucide="share-2" class="h-4 w-4"></i>
                                        Share
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        <div class="pt-8 flex justify-center">
                            <button class="rounded-full bg-white px-8 py-4 text-sm font-black uppercase tracking-widest text-[color:var(--text-primary)] shadow-sm ring-1 ring-black/5 hover:ring-[var(--primary)] transition">
                                Load More Reviews
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Products -->
        <section class="py-20 border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="flex items-end justify-between mb-12">
                    <div>
                        <h2 class="text-3xl font-black italic tracking-tight text-[color:var(--text-primary)]">You may also like</h2>
                        <p class="mt-2 text-[color:var(--text-secondary)] font-bold">Complete your wellness routine.</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="hidden sm:inline-flex rounded-full bg-orange-50 text-[color:var(--primary)] px-8 py-3 text-xs font-black uppercase tracking-widest hover:bg-orange-100 transition ring-1 ring-orange-100">View All Collections</a>
                </div>

                <div class="related-carousel owl-carousel owl-theme">
                    @foreach($relatedProducts as $rp)
                        <a href="{{ route('products.show', $rp['slug']) }}" class="group block">
                            <div class="relative aspect-square overflow-hidden rounded-[2.5rem] bg-[var(--bg-section)] ring-1 ring-black/5 mb-6">
                                <img src="{{ asset('images/products/' . $rp['image']) }}" alt="{{ $rp['title'] }}" class="h-full w-full object-contain p-6 transition duration-500 group-hover:scale-110">
                            </div>
                            <h3 class="text-lg font-black text-[color:var(--text-primary)] leading-tight group-hover:text-[color:var(--primary)] transition">{{ $rp['title'] }}</h3>
                            <div class="mt-2 flex items-center justify-between">
                                <p class="text-xl font-black text-[color:var(--text-primary)]">₹{{ number_format($rp['price']) }}</p>
                                <div class="flex items-center gap-1 text-orange-500">
                                    <i data-lucide="star" class="h-4 w-4 fill-current"></i>
                                    <span class="text-xs font-black">{{ $rp['rating'] }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </div>

    <!-- Sticky Bottom Bar (Always Visible) -->
    <div class="fixed bottom-0 left-0 right-0 z-[60] bg-white/90 backdrop-blur-xl border-t border-black/5 p-4 sm:px-6 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
        <div class="mx-auto max-w-[1600px] flex items-center justify-between gap-8 px-4 sm:px-6 lg:px-12">
            <!-- Product Info (Desktop Only) -->
            <div class="hidden md:flex items-center gap-4">
                <img src="{{ asset('images/products/' . $product['image']) }}" alt="{{ $product['title'] }}" class="h-14 w-14 rounded-xl bg-gray-50 object-contain p-2">
                <div>
                    <h4 class="text-sm font-black text-[color:var(--text-primary)] line-clamp-1">{{ $product['title'] }}</h4>
                    <p class="text-xs font-bold text-[color:var(--primary)]">₹{{ number_format($product['price']) }}</p>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-1 items-center gap-3 max-w-[600px] lg:flex-none lg:w-[450px]">
                <a href="{{ route('cart') }}" class="flex-1 h-14 flex items-center justify-center rounded-2xl bg-[var(--primary)] text-white font-black uppercase tracking-[0.1em] text-xs shadow-lg shadow-[var(--primary)]/20 active:scale-95 transition hover:brightness-105">
                    Add to Cart
                </a>
                <button type="button" class="flex-1 h-14 rounded-2xl bg-[var(--secondary)] text-white font-black uppercase tracking-[0.1em] text-xs shadow-lg shadow-[var(--secondary)]/20 active:scale-95 transition hover:brightness-105">
                    Buy It Now
                </button>
            </div>
        </div>
    </div>

    <!-- Spacer for Sticky Bar -->
    <div class="h-24"></div>
    <!-- Image Lightbox Modal -->
    <div id="lightbox-modal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center bg-[#0a1a0f]/95 backdrop-blur-sm transition-all duration-300 p-3 sm:p-6">
        <!-- Close Button -->
        <button type="button" data-lightbox-close class="absolute right-3 top-3 sm:right-6 sm:top-6 z-[110] flex h-11 w-11 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-black/40 backdrop-blur-md text-white hover:bg-red-500 transition-all duration-300 shadow-2xl ring-1 ring-white/20">
            <i data-lucide="x" class="h-6 w-6"></i>
        </button>

        <!-- Navigation -->
        <button type="button" data-lightbox-prev class="lightbox-nav-btn absolute left-2 sm:left-4 md:left-8 top-1/2 -translate-y-1/2 z-[110] hidden sm:flex items-center justify-center rounded-full bg-white/90 backdrop-blur-md text-black transition-all duration-300 group shadow-2xl ring-1 ring-black/10">
            <i data-lucide="chevron-left" class="h-6 w-6 sm:h-7 sm:w-7 transition-transform group-hover:-translate-x-1"></i>
        </button>
        <button type="button" data-lightbox-next class="lightbox-nav-btn absolute right-2 sm:right-4 md:right-8 top-1/2 -translate-y-1/2 z-[110] hidden sm:flex items-center justify-center rounded-full bg-white/90 backdrop-blur-md text-black transition-all duration-300 group shadow-2xl ring-1 ring-black/10">
            <i data-lucide="chevron-right" class="h-6 w-6 sm:h-7 sm:w-7 transition-transform group-hover:translate-x-1"></i>
        </button>

        <!-- Main Image Carousel -->
        <div class="relative h-full w-full flex items-center justify-center">
            <div class="lightbox-frame">
                <div class="lightbox-carousel owl-carousel owl-theme h-full w-full">
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

        <!-- Thumbnails/Counter -->
        <div class="absolute bottom-4 sm:bottom-8 left-1/2 -translate-x-1/2 z-[110]">
            <p class="rounded-full bg-black/40 px-3 py-1 text-xs sm:text-sm font-black tracking-widest text-white/70 uppercase">
                <span id="lightbox-current" class="text-white">1</span> / <span id="lightbox-total">1</span>
            </p>
        </div>
    </div>

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
                setNavDisabled($lightboxPrev, index <= 0);
                setNavDisabled($lightboxNext, index >= gallery.length - 1);
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
                    const index = event.item.index;
                    currentImageIndex = index;
                    $currentCounter.text(index + 1);
                    $gallery.trigger("to.owl.carousel", [index, 200, true]);
                    updateLightboxNavState(index);
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
                currentImageIndex = index;
                $currentCounter.text(index + 1);
                $modal.removeClass("hidden").addClass("flex");
                $("body").css("overflow", "hidden");

                // Owl inside hidden modal can calculate wrong widths; refresh after visible.
                requestAnimationFrame(function() {
                    $lightbox.trigger("refresh.owl.carousel");
                    $lightbox.trigger("to.owl.carousel", [index, 0, true]);
                    updateLightboxNavState(index);
                    if (window.lucide) window.lucide.createIcons();
                });
                lockBackgroundScroll();
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
                margin: 24,
                loop: false,
                autoplay: false,
                nav: false,
                dots: false,
                responsive: {
                    0: { items: 1.2, stagePadding: 20 },
                    640: { items: 2 },
                    1024: { items: 3 },
                    1280: { items: 4 }
                }
            });

            updateGalleryNavState(0);
            updateLightboxNavState(0);
            updateMobileImageProgress(0);
            $(window).on("resize", function() {
                updateMobileImageProgress(currentImageIndex);
            });
        });
    </script>
    @endpush
@endsection
