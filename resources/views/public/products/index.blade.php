@extends('public.layouts.app')

@section('title', 'Shop All Products - ' . config('app.name', 'Remenant Health'))

@section('content')
    <div class="bg-[var(--bg-main)]">
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-[var(--secondary)] py-16 text-white">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12 relative z-10">
                <div class="max-w-3xl">
                    <h1 class="text-4xl font-bold italic tracking-tight sm:text-6xl leading-tight text-white">
                        Pure Wellness <br> In Every Sip.
                    </h1>
                    <p class="mt-6 text-lg font-semibold text-white/80 leading-relaxed max-w-2xl">
                        Discover our collection of clean-label, effervescent wellness formulas designed for modern life.
                    </p>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 h-full w-1/3 opacity-10">
                <div class="absolute top-10 right-10 text-9xl">🍏</div>
                <div class="absolute bottom-10 right-20 text-8xl">🍊</div>
            </div>
            <div class="absolute -left-20 -top-20 h-96 w-96 rounded-full bg-[var(--primary)]/20 blur-[120px]"></div>
        </section>

        <!-- Main Shop Layout -->
        <section class="mx-auto max-w-[1600px] px-4 py-12 sm:px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Left Sidebar: Filters -->
                <aside class="w-full lg:w-64 shrink-0">
                    <div id="filters-container" class="hidden lg:block fixed inset-0 z-[100] bg-white lg:static lg:z-auto lg:bg-transparent overflow-y-auto lg:overflow-visible">
                        <!-- Mobile Header -->
                        <div class="lg:hidden flex items-center justify-between px-6 py-6 border-b border-black/5 sticky top-0 bg-white z-20">
                            <span class="text-sm font-black uppercase tracking-widest text-[color:var(--text-primary)]">Filters & Sort</span>
                            <button type="button" 
                                    onclick="document.getElementById('filters-container').classList.add('hidden'); document.body.style.overflow = 'auto'"
                                    class="p-2 rounded-full bg-black/5 text-gray-500 hover:bg-black/10 transition">
                                <i data-lucide="x" class="h-5 w-5"></i>
                            </button>
                        </div>

                        <div class="p-6 lg:p-0 space-y-10 lg:space-y-10">
                            <!-- Sort (Mobile only view as part of filters) -->
                            <div class="lg:hidden">
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)] mb-4">Sort By</h3>
                                <div class="relative">
                                    <select class="w-full appearance-none rounded-2xl bg-white px-6 py-4 pr-12 text-sm font-semibold uppercase tracking-widest outline-none ring-1 ring-black/5 shadow-sm">
                                        <option>Best Selling</option>
                                        <option>Price: Low to High</option>
                                        <option>Price: High to Low</option>
                                        <option>Newest First</option>
                                    </select>
                                    <i data-lucide="chevron-down" class="absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Categories -->
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)] mb-6">Categories</h3>
                                <div class="space-y-3">
                                    @php
                                        $categories = ['All Products', 'Immunity', 'Beauty & Skin', 'Metabolism', 'Daily Energy', 'Weight Care'];
                                    @endphp
                                    @foreach($categories as $cat)
                                        <label class="group flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" 
                                                   class="h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all"
                                                   {{ $loop->first ? 'checked' : '' }}>
                                            <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">{{ $cat }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)] mb-6">Price Range</h3>
                                <div class="space-y-4">
                                    <input type="range" min="0" max="5000" step="100" class="w-full accent-[var(--primary)]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-[color:var(--text-muted)]">₹0</span>
                                        <span class="text-xs font-black text-[color:var(--text-muted)]">₹5,000+</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Availability -->
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)] mb-6">Availability</h3>
                                <div class="space-y-3">
                                    <label class="group flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" 
                                               class="h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all"
                                               checked>
                                        <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">In Stock</span>
                                    </label>
                                    <label class="group flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" 
                                               class="h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all">
                                        <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">Out of Stock</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Reset Filters -->
                            <div class="pt-6 lg:pt-0">
                                <button type="button" class="w-full rounded-2xl bg-black/5 py-4 text-xs font-bold uppercase tracking-widest text-gray-500 hover:bg-black/10 transition">
                                    Reset Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Right Side: Products -->
                <div class="flex-1">
                    <!-- Top Bar: Results Count & Sort -->
                    <div class="flex items-center justify-between mb-10 border-b border-black/5 pb-6">
                        <div class="flex items-center gap-4">
                            <!-- Mobile Filter Toggle -->
                            <button type="button" 
                                    onclick="document.getElementById('filters-container').classList.remove('hidden'); document.body.style.overflow = 'hidden'"
                                    class="lg:hidden flex items-center justify-center rounded-xl bg-white h-11 w-11 shadow-sm ring-1 ring-black/5 text-gray-900 active:scale-95 transition">
                                <i data-lucide="sliders-horizontal" class="h-5 w-5"></i>
                            </button>
                            <p class="text-sm font-bold uppercase tracking-widest text-[color:var(--text-secondary)]">
                                Showing {{ count($products) }} Products
                            </p>
                        </div>
                        
                        <div class="hidden lg:flex items-center gap-4">
                            <span class="text-xs font-bold uppercase tracking-widest text-[color:var(--text-muted)]">Sort By:</span>
                            <div class="relative">
                                <select class="appearance-none rounded-2xl bg-white px-6 py-3 pr-12 text-sm font-semibold uppercase tracking-widest outline-none ring-1 ring-black/5 shadow-sm hover:bg-gray-50 transition">
                                    <option>Best Selling</option>
                                    <option>Price: Low to High</option>
                                    <option>Price: High to Low</option>
                                    <option>Newest First</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Product Grid -->
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
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
                                        {{ $product['tagline'] }}</p>
                                    <h3 class="mt-1 text-[color:var(--text-primary)] font-semibold">
                                        {{ $product['title'] }}</h3>

                                    <div class="mt-3 flex items-center justify-between gap-3">
                                        <div class="flex items-baseline gap-2">
                                            <p class="text-base font-semibold text-[color:var(--primary)] tracking-tighter">
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

                    <!-- Load More (Placeholder) -->
                    @if(count($products) > 9)
                        <div class="mt-20 flex justify-center">
                            <button type="button" class="rounded-full bg-white px-10 py-5 text-sm font-black uppercase tracking-widest text-gray-900 shadow-xl ring-1 ring-black/5 hover:bg-gray-50 transition active:scale-95">
                                Load More Products
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
        <section class="bg-white py-24 border-t border-black/5">
            <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
                <div class="rounded-[4rem] bg-[var(--bg-sage)] p-8 sm:p-20 relative overflow-hidden">
                    <div class="relative z-10 text-center max-w-3xl mx-auto">
                        <h2 class="text-4xl font-bold italic tracking-tight text-[#074D3D] sm:text-5xl">Join the Wellness Revolution</h2>
                        <p class="mt-6 text-lg font-semibold text-[#074D3D]/80">
                            Get 10% off your first order and stay updated with our latest health tips.
                        </p>
                        
                        <form class="mt-10 flex flex-col sm:flex-row gap-4 max-w-2xl mx-auto">
                            <input type="email" placeholder="Enter your email" class="flex-1 rounded-2xl border-none bg-white px-6 py-4 text-sm font-bold shadow-sm outline-none ring-1 ring-black/5 focus:ring-[var(--primary)] transition">
                            <button type="submit" class="rounded-2xl bg-[#074D3D] px-8 py-4 text-sm font-black uppercase tracking-widest text-white hover:opacity-90 transition active:scale-95 shadow-xl shadow-[#074D3D]/20">
                                Subscribe
                            </button>
                        </form>
                    </div>

                    <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/20 blur-3xl"></div>
                    <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-black/5 blur-3xl"></div>
                </div>
            </div>
        </section>
    </div>
@endsection
