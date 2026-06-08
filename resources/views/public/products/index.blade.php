@extends('public.layouts.app')

@php
    seo()->set([
        'title' => 'Shop All Effervescent Supplements | Remenant Health',
        'description' => 'Browse our complete range of effervescent health supplements. From weight management to skincare, find the perfect clinically tested wellness formula for your daily routine.',
    ]);

    seo()->addSchema('CollectionPage', [
        'name' => 'Remenant Health Product Collection',
        'description' => 'Complete collection of premium effervescent wellness supplements.',
        'url' => request()->url(),
    ]);
@endphp

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

                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)] mb-6">Categories</h3>
                                <div class="space-y-3">
                                    @php
                                        $activeCategories = (array) request('categories', []);
                                    @endphp
                                    <label class="group flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" id="all-products-checkbox"
                                               onchange="resetCategories(this)"
                                               class="h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all"
                                               {{ empty($activeCategories) ? 'checked' : '' }}>
                                        <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">All Products</span>
                                    </label>
                                    @foreach($categories as $cat)
                                        <label class="group flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" name="categories[]" value="{{ $cat->slug }}"
                                                   onchange="filterProducts()"
                                                   class="category-checkbox h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all"
                                                   {{ (in_array($cat->slug, $activeCategories) || in_array($cat->name, $activeCategories)) ? 'checked' : '' }}>
                                            <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                    <label class="group flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="categories[]" value="Combo Offers"
                                               onchange="filterProducts()"
                                               class="category-checkbox h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all"
                                               {{ in_array('Combo Offers', $activeCategories) ? 'checked' : '' }}>
                                        <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">Combo Offers</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)] mb-6">Price Range</h3>
                                <div class="space-y-4">
                                    <input type="range" id="price-range" min="{{ $minPrice }}" max="{{ $maxPrice }}" step="10" value="{{ request('max_price', $maxPrice) }}" class="w-full accent-[var(--primary)]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-[color:var(--text-muted)]">₹{{ number_format($minPrice) }}</span>
                                        <span class="text-xs font-black text-[color:var(--primary)]" id="price-value">₹{{ number_format(request('max_price', $maxPrice)) }}</span>
                                        <span class="text-xs font-black text-[color:var(--text-muted)]">₹{{ number_format($maxPrice) }}+</span>
                                    </div>
                                </div>
                            </div>


                            <!-- Reset Filters -->
                            <div class="pt-6 lg:pt-0">
                                <a href="{{ route('products.index') }}" class="block w-full text-center rounded-2xl bg-black/5 py-4 text-xs font-bold uppercase tracking-widest text-gray-500 hover:bg-black/10 transition">
                                    Reset Filters
                                </a>
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
                                Showing <span id="results-count">{{ count($products) }}</span> Products
                                @if(request('search'))
                                    <span class="ml-2 text-[color:var(--text-muted)] font-medium normal-case tracking-normal">
                                        for "<span class="text-[color:var(--text-primary)]">{{ request('search') }}</span>"
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="hidden lg:flex items-center gap-4">
                            <span class="text-xs font-bold uppercase tracking-widest text-[color:var(--text-muted)]">Sort By:</span>
                            <div class="relative">
                                <select id="sort-select" onchange="filterProducts()" class="appearance-none rounded-2xl bg-white px-6 py-3 pr-12 text-sm font-semibold uppercase tracking-widest outline-none ring-1 ring-black/5 shadow-sm hover:bg-gray-50 transition">
                                    <option value="best-selling">Best Selling</option>
                                    <option value="price-low">Price: Low to High</option>
                                    <option value="price-high">Price: High to Low</option>
                                    <option value="newest">Newest First</option>
                                </select>
                                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>


                    <!-- Product Grid -->
                    <div id="products-grid-container" class="relative">
                        @include('public.products._grid')
                        
                        <!-- Loading Overlay -->
                        <div id="grid-loader" class="hidden absolute inset-0 z-20 bg-white/50 backdrop-blur-[2px] flex items-center justify-center rounded-3xl">
                            <div class="h-10 w-10 border-4 border-[var(--primary)] border-t-transparent rounded-full animate-spin"></div>
                        </div>
                    </div>


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
    
    <script>
        function initShopPage() {
            const priceRange = document.getElementById('price-range');
            const priceValue = document.getElementById('price-value');

            if (priceRange) {
                // Ensure the event listener is only added once
                priceRange.oninput = function() {
                    priceValue.textContent = '₹' + parseInt(this.value).toLocaleString();
                };

                priceRange.onchange = function() {
                    filterProducts();
                };
            }

            // Initialize Combo Carousel
            const $combo = $('.combo-carousel');
            if ($combo.length > 0 && !$combo.hasClass('owl-loaded')) {
                const comboCarousel = $combo.owlCarousel({
                    loop: false,
                    margin: 32,
                    nav: false,
                    dots: false,
                    responsive: {
                        0: { items: 1.2, margin: 16 },
                        640: { items: 2.2, margin: 24 },
                        1024: { items: 3, margin: 32 },
                        1280: { items: 4, margin: 32 }
                    }
                });
                $combo.addClass('owl-loaded');

                $('[data-combo-prev]').off('click').on('click', function() {
                    comboCarousel.trigger('prev.owl.carousel');
                });
                $('[data-combo-next]').off('click').on('click', function() {
                    comboCarousel.trigger('next.owl.carousel');
                });
            }
        }

        function resetCategories(el) {
            if (el.checked) {
                document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
                filterProducts();
            }
        }

        function loadMoreProducts() {
            document.querySelectorAll('.extra-product').forEach(el => {
                el.classList.remove('hidden');
            });
            const loadMoreBtn = document.getElementById('load-more-container');
            if (loadMoreBtn) {
                loadMoreBtn.classList.add('hidden');
            }
        }

        function filterProducts() {
            const gridContainer = document.getElementById('products-grid-container');
            const gridLoader = document.getElementById('grid-loader');
            const countLabel = document.getElementById('results-count');
            const allCheckbox = document.getElementById('all-products-checkbox');
            
            if (gridLoader) gridLoader.classList.remove('hidden');
            
            const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
                .map(cb => cb.value);
            
            if (selectedCategories.length > 0) {
                if (allCheckbox) allCheckbox.checked = false;
            } else {
                if (allCheckbox) allCheckbox.checked = true;
            }

            const priceRange = document.getElementById('price-range');
            const maxPrice = priceRange ? priceRange.value : 0;
            const sortSelect = document.getElementById('sort-select');
            const sort = sortSelect ? sortSelect.value : 'best-selling';
            
            const url = new URL(window.location.href);
            url.searchParams.delete('categories[]');
            selectedCategories.forEach(cat => url.searchParams.append('categories[]', cat));
            url.searchParams.set('max_price', maxPrice);
            url.searchParams.set('sort', sort);
            
            window.history.pushState({}, '', url);
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                gridContainer.innerHTML = data.html + `
                    <div id="grid-loader" class="hidden absolute inset-0 z-20 bg-white/50 backdrop-blur-[2px] flex items-center justify-center rounded-3xl">
                        <div class="h-10 w-10 border-4 border-[var(--primary)] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                `;
                if (countLabel) countLabel.textContent = data.count;
                if (window.lucide) window.lucide.createIcons();
            })
            .catch(error => console.error('Error filtering:', error))
            .finally(() => {
                const loader = document.getElementById('grid-loader');
                if (loader) loader.classList.add('hidden');
            });
        }

        // Initial load
        $(document).ready(initShopPage);
        
        // Unpoly re-init
        if (window.up) {
            up.on('up:fragment:inserted', function(event) {
                const fragment = event.fragment || event.target;
                if (fragment && typeof fragment.querySelector === 'function') {
                    if (fragment.querySelector('#products-grid-container') || fragment.querySelector('.combo-carousel')) {
                        initShopPage();
                    }
                }
            });
        }
    </script>
@endsection
