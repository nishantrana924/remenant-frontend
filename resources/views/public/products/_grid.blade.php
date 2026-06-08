<div class="grid grid-cols-1 gap-8 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4" id="products-grid-inner">
    @foreach ($products as $product)
        @php
            $discount = (int) round((1 - ($product->price / max(1, $product->mrp))) * 100);
        @endphp
        <div class="product-card group relative flex h-full flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5 {{ $loop->index >= 12 ? 'hidden extra-product' : '' }}">
            <a href="{{ route('products.show', $product->slug) }}" class="absolute inset-0 z-[5]"></a>

            <div class="relative aspect-square overflow-hidden bg-[var(--bg-section)]">
                @php
                    $imageSrc = \App\Helpers\ImageHelper::getUrl($product->image, 'images/products');
                @endphp
                <img src="{{ $imageSrc }}" 
                     alt="{{ $product->title ?? 'Product' }}"
                     class="h-full w-full object-contain" 
                     onerror="this.src='{{ \App\Helpers\ImageHelper::getUrl('products/remenant-product1.jpg', 'images') }}'"
                     loading="lazy">
                 @if(isset($discount) && $discount > 0)
                     <div class="absolute left-3 top-3 rounded-full bg-[var(--primary)] px-3 py-1 text-xs font-extrabold text-white">
                         -{{ $discount }}%
                     </div>
                 @endif
             </div>

            <div class="flex flex-1 flex-col p-4">
                <p class="text-xs font-bold tracking-wide text-[color:var(--primary)] uppercase">
                    {{ $product->tagline }}</p>
                <h3 class="mt-1 text-[color:var(--text-primary)] font-semibold truncate">
                    {{ $product->title }}</h3>

                <div class="mt-3 flex items-center justify-between gap-3">
                    <div class="flex items-baseline gap-2">
                        <p class="text-base font-semibold text-[color:var(--primary)] tracking-tighter">
                            ₹{{ number_format($product->price) }}</p>
                        <p class="text-xs font-medium text-[color:var(--text-muted)] line-through">
                            ₹{{ number_format($product->mrp) }}</p>
                    </div>
                    <div
                        class="flex items-center gap-1 rounded-full bg-black/5 px-2 py-1 text-xs font-semibold text-[color:var(--text-secondary)]">
                        <i data-lucide="star" class="h-4 w-4 fill-[color:var(--primary)] text-[color:var(--primary)]"></i>
                        {{ number_format($product->rating, 1) }} ({{ number_format($product->reviews) }})
                    </div>
                </div>

                <div class="mt-auto pt-3 relative z-10">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" data-ajax="true">
                        @csrf
                        <button type="submit" class="w-full text-center rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-extrabold text-white hover:opacity-95 transition">
                            Add to cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

@if(count($products) == 0)
    <div class="py-20 text-center w-full col-span-full">
        <div class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-gray-50 mb-6">
            <i data-lucide="search-x" class="h-10 w-10 text-gray-300"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">No products found</h3>
        <p class="text-gray-500">Try adjusting your filters to find what you're looking for.</p>
    </div>
@endif

@if(count($products) > 12)
    <div id="load-more-container" class="mt-12 flex justify-center w-full col-span-full pb-10">
        <button type="button" onclick="loadMoreProducts()" class="rounded-full bg-white px-10 py-5 text-sm font-black uppercase tracking-widest text-gray-900 shadow-xl ring-1 ring-black/5 hover:bg-gray-50 transition active:scale-95">
            Load More Products
        </button>
    </div>
@endif
