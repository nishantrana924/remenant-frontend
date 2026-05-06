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

                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)] mb-6">Categories</h3>
                                <div class="space-y-3">
                                    <label class="group flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="categories[]" value="all" 
                                               onchange="filterProducts()"
                                               class="category-checkbox h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all">
                                        <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">All Products</span>
                                    </label>
                                    @foreach($categories as $cat)
                                        <label class="group flex items-center gap-3 cursor-pointer">
                                            <input type="checkbox" name="categories[]" value="{{ $cat->slug }}"
                                                   onchange="filterProducts()"
                                                   class="category-checkbox h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all">
                                            <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                    <label class="group flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="categories[]" value="Combo Offers"
                                               onchange="filterProducts()"
                                               class="category-checkbox h-5 w-5 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none cursor-pointer transition-all">
                                        <span class="text-sm font-bold text-[color:var(--text-secondary)] group-hover:text-[color:var(--text-primary)] transition">Combo Offers</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)] mb-6">Price Range</h3>
                                <div class="space-y-4">
                                    <input type="range" id="price-range" min="0" max="5000" step="100" value="{{ request('max_price', 5000) }}" class="w-full accent-[var(--primary)]">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-black text-[color:var(--text-muted)]">₹0</span>
                                        <span class="text-xs font-black text-[color:var(--primary)]" id="price-value">₹{{ number_format(request('max_price', 5000)) }}</span>
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

                    @if(isset($combos) && $combos->isNotEmpty())
                    <!-- Combo Offers Slider (Only on products page) -->
                    <div class="mt-24 mb-16">
                        <div class="flex items-center justify-between mb-8">
                            <h2 class="text-2xl font-bold italic text-[color:var(--text-primary)]">Special Combo Offers</h2>
                            <div class="flex items-center gap-2">
                                <button type="button" data-combo-prev class="h-10 w-10 rounded-full bg-white shadow-sm ring-1 ring-black/5 flex items-center justify-center hover:bg-gray-50 transition active:scale-95">
                                    <i data-lucide="chevron-left" class="h-5 w-5"></i>
                                </button>
                                <button type="button" data-combo-next class="h-10 w-10 rounded-full bg-white shadow-sm ring-1 ring-black/5 flex items-center justify-center hover:bg-gray-50 transition active:scale-95">
                                    <i data-lucide="chevron-right" class="h-5 w-5"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="combo-carousel owl-carousel owl-theme" data-items-count="{{ count($combos) }}">
                            @foreach ($combos as $combo)
                                <div class="item">
                                    <div class="group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-black/5">
                                        <a href="{{ route('products.show', $combo->slug) }}" class="absolute inset-0 z-10"></a>
                                        <div class="aspect-square bg-gray-50 overflow-hidden">
                                            <img src="{{ \App\Helpers\ImageHelper::getUrl($combo->image, 'images/products') }}" 
                                                 alt="{{ $combo->title }}"
                                                 class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                        </div>
                                        <div class="p-4 flex-1 flex flex-col">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-[color:var(--primary)] mb-1">{{ $combo->tagline }}</p>
                                            <h4 class="text-sm font-bold text-[color:var(--text-primary)] mb-2 line-clamp-1">{{ $combo->title }}</h4>
                                            <div class="mt-auto flex items-center justify-between">
                                                <div class="flex items-baseline gap-2">
                                                    <span class="text-base font-bold text-[color:var(--text-primary)]">₹{{ number_format($combo->price) }}</span>
                                                    <span class="text-xs text-gray-400 line-through">₹{{ number_format($combo->mrp) }}</span>
                                                </div>
                                                <div class="h-8 w-8 rounded-full bg-[var(--primary)] flex items-center justify-center text-white relative z-20">
                                                    <i data-lucide="plus" class="h-4 w-4"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

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
    @push('scripts')
    <script>
        function filterProducts() {
            const gridContainer = document.getElementById('products-grid-container');
            const gridLoader = document.getElementById('grid-loader');
            const countLabel = document.getElementById('results-count');
            
            // Show loader
            gridLoader.classList.remove('hidden');
            
            // Gather filters
            const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked'))
                .map(cb => cb.value)
                .filter(v => v !== 'all');
            
            const maxPrice = document.getElementById('price-range').value;
            const sort = document.getElementById('sort-select').value;
            
            // Build URL
            const url = new URL(window.location.href);
            url.searchParams.delete('categories[]');
            selectedCategories.forEach(cat => url.searchParams.append('categories[]', cat));
            url.searchParams.set('max_price', maxPrice);
            url.searchParams.set('sort', sort);
            
            // Push to history
            window.history.pushState({}, '', url);
            
            // Fetch via AJAX
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                gridContainer.innerHTML = data.html + `
                    <!-- Loading Overlay (Restored after innerHTML replace) -->
                    <div id="grid-loader" class="hidden absolute inset-0 z-20 bg-white/50 backdrop-blur-[2px] flex items-center justify-center rounded-3xl">
                        <div class="h-10 w-10 border-4 border-[var(--primary)] border-t-transparent rounded-full animate-spin"></div>
                    </div>
                `;
                countLabel.textContent = data.count;
                
                // Re-initialize Lucide icons if any
                if (window.lucide) {
                    window.lucide.createIcons();
                }
            })
            .catch(error => console.error('Error filtering:', error))
            .finally(() => {
                // Loader is now inside gridContainer, need to find it again
                document.getElementById('grid-loader').classList.add('hidden');
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const priceRange = document.getElementById('price-range');
            const priceValue = document.getElementById('price-value');

            if (priceRange) {
                priceRange.addEventListener('input', function() {
                    priceValue.textContent = '₹' + parseInt(this.value).toLocaleString();
                });

                priceRange.addEventListener('change', function() {
                    filterProducts();
                });
            }

            // Initialize Combo Carousel
            if ($('.combo-carousel').length > 0) {
                const comboCarousel = $('.combo-carousel').owlCarousel({
                    loop: false,
                    margin: 20,
                    nav: false,
                    dots: false,
                    responsive: {
                        0: { items: 1.2, margin: 15 },
                        640: { items: 2.2 },
                        1024: { items: 3 },
                        1280: { items: 4 }
                    }
                });

                $('[data-combo-prev]').click(function() {
                    comboCarousel.trigger('prev.owl.carousel');
                });
                $('[data-combo-next]').click(function() {
                    comboCarousel.trigger('next.owl.carousel');
                });
            }
        });
    </script>
    @endpush
@endsection
