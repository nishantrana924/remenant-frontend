@extends('public.layouts.app')

@section('title', $product['title'] . ' - ' . config('app.name', 'Remenant Health'))

@section('content')
    <div class="bg-[var(--bg-main)]">
        <!-- Breadcrumbs -->
        <nav class="mx-auto max-w-[1600px] px-4 pt-6 sm:px-6 lg:px-12" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-xs font-bold uppercase tracking-widest text-[color:var(--text-muted)]">
                <li><a href="/" class="hover:text-[color:var(--primary)] transition">Home</a></li>
                <li><i data-lucide="chevron-right" class="h-3 w-3"></i></li>
                <li><a href="{{ route('products.index') }}" class="hover:text-[color:var(--primary)] transition">Shop</a></li>
                <li><i data-lucide="chevron-right" class="h-3 w-3"></i></li>
                <li class="text-[color:var(--text-primary)]">{{ $product['title'] }}</li>
            </ol>
        </nav>

        <!-- Product Hero Section -->
        <section class="mx-auto max-w-[1600px] px-4 py-2 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:items-start">
                
                <!-- Left: Product Images -->
                <div class="space-y-4">
                    <div class="relative aspect-[16/9] overflow-hidden rounded-[2rem] bg-[var(--bg-section)] ring-1 ring-black/5 shadow-sm">
                        <img id="main-product-image" src="{{ asset('images/products/' . $product['image']) }}" 
                             alt="{{ $product['title'] }}" 
                             class="h-full w-full object-contain cursor-zoom-in select-none">
                        
                        <!-- Actions -->
                        <div class="absolute right-6 top-6 flex flex-col gap-3 z-20">
                            <button type="button" class="flex h-11 w-11 items-center justify-center rounded-full bg-white/95 shadow-xl ring-1 ring-black/5 hover:bg-white transition active:scale-95" aria-label="Share product">
                                <i data-lucide="share-2" class="h-5 w-5 text-gray-800"></i>
                            </button>
                            <button type="button" class="flex h-11 w-11 items-center justify-center rounded-full bg-white/95 shadow-xl ring-1 ring-black/5 hover:bg-white transition active:scale-95 group" aria-label="Add to wishlist">
                                <i data-lucide="heart" class="h-5 w-5 text-gray-800 group-hover:fill-red-500 group-hover:text-red-500 transition"></i>
                            </button>
                        </div>

                        <!-- Discount Badge -->
                        @php
                            $discount = (int) round((1 - ($product['price'] / max(1, $product['mrp']))) * 100);
                        @endphp
                        <div class="absolute left-6 top-6 rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-black text-white shadow-lg z-10">
                            -{{ $discount }}% OFF
                        </div>
                    </div>

                    <!-- Gallery Thumbnails -->
                    <div class="flex gap-4 overflow-x-auto pb-2 no-scrollbar">
                        @foreach($product['gallery'] as $img)
                            <button type="button" 
                                    onclick="document.getElementById('main-product-image').src='{{ asset('images/products/' . $img) }}'"
                                    class="relative aspect-square w-24 shrink-0 overflow-hidden rounded-2xl bg-white ring-1 ring-black/5 hover:ring-[var(--primary)] transition shadow-sm">
                                <img src="{{ asset('images/products/' . $img) }}" alt="Gallery image" class="h-full w-full object-contain">
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="flex flex-col">
                    <div class="border-b border-black/5 pb-4">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--primary)]">{{ $product['tagline'] }}</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight text-[color:var(--text-primary)] sm:text-4xl leading-tight">
                            {{ $product['title'] }}
                        </h1>
                        
                        <div class="mt-6 flex items-center gap-6">
                            <a href="#reviews" class="flex items-center gap-6 group">
                                <div class="flex items-center gap-1.5 rounded-full bg-orange-50 px-3 py-1.5 text-orange-600 group-hover:bg-orange-100 transition">
                                    <i data-lucide="star" class="h-5 w-5 fill-current"></i>
                                    <span class="text-sm font-black">{{ $product['rating'] }}</span>
                                </div>
                                <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--primary)] transition underline decoration-dotted underline-offset-4">{{ number_format($product['reviews']) }} Verified Reviews</span>
                            </a>
                            <span class="inline-flex items-center gap-1.5 text-sm font-bold text-green-600">
                                <i data-lucide="check-circle" class="h-4 w-4"></i>
                                In Stock
                            </span>
                        </div>
                    </div>

                    <div class="py-4">
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
        <section class="py-8 sm:py-16 bg-white">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="overflow-hidden rounded-3xl bg-white">
                    
                    <!-- Table Header Row -->
                    <div class="hidden md:grid grid-cols-3 bg-gray-50/50 border-b border-black/5">
                        <div class="p-6 border-r border-black/10">
                            <div class="flex items-center gap-4 bg-white shadow-sm rounded-2xl px-6 py-3 w-fit ring-1 ring-black/5">
                                <i data-lucide="info" class="h-6 w-6 text-orange-600"></i>
                                <span class="text-lg font-black uppercase tracking-[0.1em] text-gray-800">Description</span>
                            </div>
                        </div>
                        <div class="p-6 border-r border-black/10">
                            <div class="flex items-center gap-4 bg-white shadow-sm rounded-2xl px-6 py-3 w-fit ring-1 ring-black/5">
                                <i data-lucide="clipboard-list" class="h-6 w-6 text-blue-600"></i>
                                <span class="text-lg font-black uppercase tracking-[0.1em] text-gray-800">Specifications</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4 bg-white shadow-sm rounded-2xl px-6 py-3 w-fit ring-1 ring-black/5">
                                <i data-lucide="building-2" class="h-6 w-6 text-emerald-600"></i>
                                <span class="text-lg font-black uppercase tracking-[0.1em] text-gray-800">Brand Info</span>
                            </div>
                        </div>
                    </div>

                    <!-- Table Content Row -->
                    <div class="grid grid-cols-1 md:grid-cols-3">
                        
                        <!-- Cell 1: Description -->
                        <div class="p-8 sm:p-10 border-r border-black/10">
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
                        <div class="p-8 sm:p-10 border-r border-black/10 bg-gray-50/10">
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


        <section class="py-12 sm:py-24 bg-white border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="rounded-[2rem] sm:rounded-[3rem] bg-[var(--bg-dark)] p-6 sm:p-20 text-[color:var(--text-white)] overflow-hidden relative shadow-2xl">
                    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 lg:items-center">
                        <!-- Left: Product Highlights -->
                        <div>
                            <h2 class="text-xs font-black uppercase tracking-[0.4em] text-[color:var(--primary)] mb-6">Experience Excellence</h2>
                            <h3 class="text-3xl sm:text-5xl font-black italic tracking-tighter uppercase mb-8 sm:mb-12 text-black">Product Highlights</h3>
                            <div class="space-y-8">
                                <div class="flex items-center gap-6">
                                    <div class="h-2 w-2 rounded-full bg-[var(--primary)] shadow-[0_0_15px_var(--primary)]"></div>
                                    <p class="text-xl font-bold italic opacity-90">100% Bioavailable Effervescent Formula</p>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="h-2 w-2 rounded-full bg-[var(--primary)] shadow-[0_0_15px_var(--primary)]"></div>
                                    <p class="text-xl font-bold italic opacity-90">Clean Label Certified Ingredients</p>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="h-2 w-2 rounded-full bg-[var(--primary)] shadow-[0_0_15px_var(--primary)]"></div>
                                    <p class="text-xl font-bold italic opacity-90">Zero Sugar & No Artificial Colors</p>
                                </div>
                                <div class="flex items-center gap-6">
                                    <div class="h-2 w-2 rounded-full bg-[var(--primary)] shadow-[0_0_15px_var(--primary)]"></div>
                                    <p class="text-xl font-bold italic opacity-90">Fast Acting & Gentle on the Stomach</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right: The Ritual -->
                        <div class="bg-orange-400 backdrop-blur-md rounded-[2rem] sm:rounded-[2.5rem] p-6 sm:p-12 border border-white/10 shadow-inner">
                            <h2 class="text-2xl sm:text-3xl font-black tracking-tighter uppercase mb-10 sm:mb-12 flex items-center gap-4 text-white">
                                <span class="h-px flex-1 bg-white/20"></span>
                                The Ritual
                                <span class="h-px flex-1 bg-white/20"></span>
                            </h2>
                            <div class="space-y-10 ">
                                <div class="flex gap-8 group">
                                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[var(--primary)] font-black text-white text-2xl shadow-xl shadow-[var(--primary)]/20 transition-transform group-hover:rotate-12">1</span>
                                    <div>
                                        <h4 class="text-xl font-bold uppercase tracking-widest text-white">Drop it</h4>
                                        <p class="mt-2 text-base text-white/90 leading-relaxed">Drop one tablet into 200ml of water.</p>
                                    </div>
                                </div>
                                <div class="flex gap-8 group">
                                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 border border-white/5 font-black text-[color:var(--text-white)] text-2xl transition-transform group-hover:rotate-12">2</span>
                                    <div>
                                        <h4 class="text-xl font-bold uppercase tracking-widest text-white">Fizz it</h4>
                                        <p class="mt-2 text-base text-white/90 leading-relaxed">Watch the pure wellness dissolve.</p>
                                    </div>
                                </div>
                                <div class="flex gap-8 group">
                                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/10 border border-white/5 font-black text-[color:var(--text-white)] text-2xl transition-transform group-hover:rotate-12">3</span>
                                    <div>
                                        <h4 class="text-xl font-bold uppercase tracking-widest text-white">Fuel Up</h4>
                                        <p class="mt-2 text-base text-white/90 leading-relaxed">Drink and take on your day.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Background Decor -->
                    <div class="absolute -top-24 -right-24 h-64 w-64 rounded-full bg-[var(--primary)]/20 blur-[100px]"></div>
                    <div class="absolute -bottom-24 -left-24 h-64 w-64 rounded-full bg-[var(--secondary)]/10 blur-[100px]"></div>
                </div>
            </div>
        </section>

        <!-- Reviews Section -->
        <section id="reviews" class="py-12 sm:py-24 bg-[var(--bg-main)] scroll-mt-24">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="flex flex-col lg:flex-row gap-16">
                    <!-- Left: Rating Summary (Sticky) -->
                    <div class="lg:w-1/3 lg:sticky lg:top-32 h-fit">
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

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
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
                <button type="button" class="flex-1 h-14 rounded-2xl bg-[var(--primary)] text-white font-black uppercase tracking-[0.1em] text-xs shadow-lg shadow-[var(--primary)]/20 active:scale-95 transition hover:brightness-105">
                    Add to Cart
                </button>
                <button type="button" class="flex-1 h-14 rounded-2xl bg-[var(--secondary)] text-white font-black uppercase tracking-[0.1em] text-xs shadow-lg shadow-[var(--secondary)]/20 active:scale-95 transition hover:brightness-105">
                    Buy It Now
                </button>
            </div>
        </div>
    </div>

    <!-- Spacer for Sticky Bar -->
    <div class="h-24"></div>
    <!-- Image Lightbox Modal -->
    <div id="lightbox-modal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center bg-[#0a1a0f]/95 backdrop-blur-sm transition-all duration-300">
        <!-- Close Button -->
        <button type="button" onclick="closeLightbox()" class="absolute right-6 top-6 z-[110] flex h-12 w-12 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition">
            <i data-lucide="x" class="h-6 w-6"></i>
        </button>

        <!-- Navigation -->
        <button type="button" onclick="prevImage()" class="absolute left-4 top-1/2 -translate-y-1/2 z-[110] flex h-14 w-14 items-center justify-center rounded-full bg-white/5 text-white hover:bg-white/10 transition sm:left-8">
            <i data-lucide="chevron-left" class="h-8 w-8"></i>
        </button>
        <button type="button" onclick="nextImage()" class="absolute right-4 top-1/2 -translate-y-1/2 z-[110] flex h-14 w-14 items-center justify-center rounded-full bg-white/5 text-white hover:bg-white/10 transition sm:right-8">
            <i data-lucide="chevron-right" class="h-8 w-8"></i>
        </button>

        <!-- Main Image -->
        <div class="relative h-full w-full p-4 sm:p-20 flex items-center justify-center">
            <img id="lightbox-image" src="" alt="Lightbox" class="max-h-full max-w-full object-contain transition-transform duration-500 scale-95 opacity-0 cursor-grab">
        </div>

        <!-- Thumbnails/Counter -->
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-[110]">
            <p class="text-sm font-black tracking-widest text-white/50 uppercase">
                <span id="lightbox-current" class="text-white">1</span> / <span id="lightbox-total">1</span>
            </p>
        </div>
    </div>

    @push('scripts')
    <script>
        const gallery = [
            '{{ asset("images/products/" . $product["image"]) }}',
            @foreach($product['gallery'] as $img)
                '{{ asset("images/products/" . $img) }}',
            @endforeach
        ];
        
        let currentImageIndex = 0;
        const modal = document.getElementById('lightbox-modal');
        const lightboxImg = document.getElementById('lightbox-image');
        const currentCounter = document.getElementById('lightbox-current');
        const totalCounter = document.getElementById('lightbox-total');

        totalCounter.innerText = gallery.length;

        function openLightbox(index) {
            currentImageIndex = index;
            updateLightboxContent();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            
            setTimeout(() => {
                lightboxImg.classList.remove('scale-95', 'opacity-0');
                lightboxImg.classList.add('scale-100', 'opacity-100');
            }, 50);
        }

        function closeLightbox() {
            lightboxImg.classList.add('scale-95', 'opacity-0');
            lightboxImg.classList.remove('scale-100', 'opacity-100');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';
            }, 300);
        }

        function updateLightboxContent(direction = 'next') {
            const offset = direction === 'next' ? 60 : -60;
            
            // Fast slide out
            lightboxImg.style.transition = 'transform 0.2s ease-in';
            lightboxImg.style.transform = `translateX(${-offset}px)`;
            
            setTimeout(() => {
                // Change source
                lightboxImg.src = gallery[currentImageIndex];
                currentCounter.innerText = currentImageIndex + 1;
                
                // Position for slide in
                lightboxImg.style.transition = 'none';
                lightboxImg.style.transform = `translateX(${offset}px)`;
                
                // Slide in
                setTimeout(() => {
                    lightboxImg.style.transition = 'transform 0.3s cubic-bezier(0.2, 0.8, 0.2, 1)';
                    lightboxImg.style.transform = 'translateX(0)';
                }, 20);
            }, 200);
        }

        function nextImage() {
            currentImageIndex = (currentImageIndex + 1) % gallery.length;
            updateLightboxContent('next');
        }

        function prevImage() {
            currentImageIndex = (currentImageIndex - 1 + gallery.length) % gallery.length;
            updateLightboxContent('prev');
        }

        // Close on Esc key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
        });

        // Close on backdrop click (but not on image)
        modal.addEventListener('click', (e) => {
            if (e.target === modal || e.target.tagName === 'DIV') {
                // closeLightbox();
            }
        });

        // Swipe/Drag logic
        let touchStartX = 0;
        let touchEndX = 0;
        let isDragging = false;
        let dragStartX = 0;

        lightboxImg.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        lightboxImg.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe(nextImage, prevImage);
        }, { passive: true });

        lightboxImg.addEventListener('mousedown', (e) => {
            isDragging = true;
            dragStartX = e.screenX;
            lightboxImg.style.cursor = 'grabbing';
            e.preventDefault();
        });

        window.addEventListener('mousemove', (e) => {
            if (!isDragging) return;
            const diff = e.screenX - dragStartX;
            if (modal.classList.contains('flex')) {
                lightboxImg.style.transform = `translateX(${diff}px)`;
            } else if (isMainDragging) {
                const mainImg = document.getElementById('main-product-image');
                mainImg.style.transform = `translateX(${diff}px)`;
            }
        });

        window.addEventListener('mouseup', (e) => {
            if (isDragging) {
                isDragging = false;
                lightboxImg.style.cursor = 'grab';
                const diff = e.screenX - dragStartX;
                lightboxImg.style.transform = ''; 
                if (Math.abs(diff) > 50) {
                    if (diff > 0) prevImage();
                    else nextImage();
                }
            }
            if (isMainDragging) {
                isMainDragging = false;
                const mainImg = document.getElementById('main-product-image');
                mainImg.style.cursor = 'zoom-in';
                const diff = e.screenX - dragStartX;
                mainImg.style.transform = '';
                
                // If the movement was very small, treat it as a click and open lightbox
                if (Math.abs(diff) < 5) {
                    openLightbox(currentImageIndex);
                } else if (Math.abs(diff) > 50) {
                    if (diff > 0) prevMainImage();
                    else nextMainImage();
                }
            }
        });

        // Main Image Swipe/Drag
        let isMainDragging = false;
        const mainImg = document.getElementById('main-product-image');

        mainImg.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        mainImg.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            
            if (Math.abs(diff) < 5) {
                openLightbox(currentImageIndex);
            } else if (Math.abs(diff) > 50) {
                if (diff > 0) prevMainImage();
                else nextMainImage();
            }
        }, { passive: true });

        mainImg.addEventListener('mousedown', (e) => {
            isMainDragging = true;
            dragStartX = e.screenX;
            mainImg.style.cursor = 'grabbing';
            e.preventDefault();
        });

        function nextMainImage() {
            currentImageIndex = (currentImageIndex + 1) % gallery.length;
            updateMainImage('next');
        }

        function prevMainImage() {
            currentImageIndex = (currentImageIndex - 1 + gallery.length) % gallery.length;
            updateMainImage('prev');
        }

        function updateMainImage(direction = 'next') {
            const mainImg = document.getElementById('main-product-image');
            const offset = direction === 'next' ? 40 : -40;
            
            mainImg.style.transition = 'transform 0.15s ease-in';
            mainImg.style.transform = `translateX(${-offset}px)`;
            
            setTimeout(() => {
                mainImg.src = gallery[currentImageIndex];
                mainImg.style.transition = 'none';
                mainImg.style.transform = `translateX(${offset}px)`;
                
                setTimeout(() => {
                    mainImg.style.transition = 'transform 0.25s cubic-bezier(0.2, 0.8, 0.2, 1)';
                    mainImg.style.transform = 'translateX(0)';
                }, 20);
            }, 150);
        }

        function handleSwipe(nextFn, prevFn) {
            const diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 50) {
                if (diff > 0) prevFn();
                else nextFn();
            }
        }
    </script>
    @endpush
@endsection
