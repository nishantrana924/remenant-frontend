@extends('public.layouts.app')

@section('title', 'Shopping Cart - Remenant Health')

@section('content')
    <div class="bg-[#FDF9F6] py-12 sm:py-16 min-h-screen">
        <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">

                <!-- Left: Cart Items Card -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h1 class="text-2xl font-extrabold text-[color:var(--text-primary)] cart-count-display">My Cart
                                (2)</h1>
                        </div>

                        <div class="divide-y divide-gray-200 px-6">
                            @php
                                $cartItems = [
                                    [
                                        'id' => 1,
                                        'title' => 'Ultimate Immunity Duo',
                                        'subtitle' => 'Remenant Health',
                                        'details' => '60 Tablets | 500mg',
                                        'price' => 3299,
                                        'mrp' => 4999,
                                        'image' => 'remenant-product1.jpg',
                                        'quantity' => 1,
                                        'rating' => 4.8,
                                        'reviews' => 124,
                                        'delivery' => 'Friday, May 10'
                                    ],
                                    [
                                        'id' => 2,
                                        'title' => 'Glow & Strength Bundle',
                                        'subtitle' => 'Remenant Health',
                                        'details' => '30 Tablets | High Strength',
                                        'price' => 3499,
                                        'mrp' => 5499,
                                        'image' => 'remenant-product2.jpg',
                                        'quantity' => 2,
                                        'rating' => 4.9,
                                        'reviews' => 86,
                                        'delivery' => 'Friday, May 10'
                                    ]
                                ];
                            @endphp

                            @if(count($cartItems) > 0)
                                @foreach($cartItems as $item)
                                    <div class="py-6 sm:py-8 flex flex-col sm:flex-row gap-4 sm:gap-6 cart-item border-b border-gray-200 last:border-0" data-item-id="{{ $item['id'] }}"
                                        data-price="{{ $item['price'] }}" data-mrp="{{ $item['mrp'] }}">
                                        
                                        <!-- Desktop Layout (Left Image) -->
                                        <div class="hidden sm:block w-32 h-32 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                                            <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['title'] }}"
                                                class="h-full w-full object-cover">
                                        </div>

                                        <!-- Mobile Layout (Header Row) -->
                                        <div class="flex sm:hidden gap-4 items-start">
                                            <div class="w-20 h-20 bg-gray-50 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100">
                                                <img src="{{ asset('images/products/' . $item['image']) }}" alt="{{ $item['title'] }}"
                                                    class="h-full w-full object-cover">
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-sm font-extrabold text-[color:var(--text-primary)] leading-tight">
                                                    {{ $item['title'] }}</h3>
                                                <p class="text-[11px] text-gray-400 mt-1 font-medium">{{ $item['subtitle'] }} • {{ $item['details'] }}</p>
                                                <div class="flex items-center gap-2 mt-2">
                                                    <div class="flex items-center gap-1 bg-green-50 px-1 py-0.5 rounded text-[9px] font-bold text-green-700 border border-green-100">
                                                        {{ $item['rating'] }} <i data-lucide="star" class="w-2 h-2 fill-green-700"></i>
                                                    </div>
                                                    <span class="text-[9px] text-gray-400 font-bold uppercase">({{ $item['reviews'] }} reviews)</span>
                                                </div>
                                                <div class="mt-3 flex items-center gap-2">
                                                    <span class="text-sm text-gray-400 line-through">₹{{ number_format($item['mrp']) }}</span>
                                                    <span class="text-lg font-black text-[color:var(--text-primary)]">₹{{ number_format($item['price']) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Desktop Content Area -->
                                        <div class="hidden sm:flex flex-1 flex flex-col justify-between">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex-1">
                                                    <h3 class="text-base font-semibold text-[color:var(--text-primary)] leading-tight">
                                                        {{ $item['title'] }}</h3>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <div class="flex items-center gap-1 bg-green-50 px-1.5 py-0.5 rounded text-[10px] font-bold text-green-700 border border-green-100">
                                                            {{ $item['rating'] }} <i data-lucide="star" class="w-2.5 h-2.5 fill-green-700"></i>
                                                        </div>
                                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">({{ $item['reviews'] }} Reviews)</span>
                                                    </div>
                                                    <p class="text-xs text-gray-400 mt-2 font-medium">{{ $item['subtitle'] }} • {{ $item['details'] }}</p>
                                                    <div class="mt-3 flex items-center gap-2 text-[11px] text-gray-500 font-bold bg-gray-50/50 w-fit px-2 py-1 rounded border border-gray-100">
                                                        <i data-lucide="truck" class="w-3.5 h-3.5 text-gray-400"></i>
                                                        Delivery by <span class="text-gray-900">{{ $item['delivery'] }}</span>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col items-end gap-4">
                                                    <div class="flex items-center quantity-selector">
                                                        <button class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-500 rounded-l-md border border-gray-200 hover:bg-gray-100 transition btn-decrease">
                                                            <i data-lucide="minus" class="w-4 h-4"></i>
                                                        </button>
                                                        <div class="w-12 h-10 flex items-center justify-center border-y border-gray-200 text-sm font-bold text-gray-700 quantity-value">
                                                            {{ $item['quantity'] }}
                                                        </div>
                                                        <button class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-500 rounded-r-md border border-gray-200 hover:bg-gray-100 transition btn-increase">
                                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                                        </button>
                                                    </div>
                                                    <button class="text-xs text-gray-500 hover:text-red-600 flex items-center gap-1.5 transition btn-remove group">
                                                        <i data-lucide="trash-2" class="w-4 h-4 text-gray-400 group-hover:text-red-500 transition-colors"></i> Remove
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mt-4 flex items-center gap-3">
                                                <span class="text-sm text-gray-400 line-through">₹{{ number_format($item['mrp']) }}</span>
                                                <span class="text-xl font-bold text-[color:var(--text-primary)]">₹{{ number_format($item['price']) }}</span>
                                                <span class="text-sm font-bold text-[#FF4D4D] bg-red-50 px-2 py-0.5 rounded">{{ round((1 - ($item['price'] / $item['mrp'])) * 100) }}% OFF</span>
                                            </div>
                                        </div>

                                        <!-- Mobile Only Stacking Section -->
                                        <div class="sm:hidden space-y-4">
                                            <div class="flex items-center gap-2 text-[11px] text-gray-500 font-bold bg-gray-50/50 w-full px-3 py-2 rounded border border-gray-100">
                                                <i data-lucide="truck" class="w-3.5 h-3.5 text-gray-400"></i>
                                                Delivery by <span class="text-gray-900">{{ $item['delivery'] }}</span>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center quantity-selector">
                                                    <button class="w-9 h-9 flex items-center justify-center bg-gray-50 text-gray-500 rounded-l-md border border-gray-200 btn-decrease">
                                                        <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                    <div class="w-10 h-9 flex items-center justify-center border-y border-gray-200 text-sm font-bold text-gray-700 quantity-value">
                                                        {{ $item['quantity'] }}
                                                    </div>
                                                    <button class="w-9 h-9 flex items-center justify-center bg-gray-50 text-gray-500 rounded-r-md border border-gray-200 btn-increase">
                                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </div>
                                                <button class="p-2 text-gray-400 hover:text-red-500 transition btn-remove">
                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                        @else
                                <div class="py-24 text-center">
                                    <div
                                        class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                                        <i data-lucide="shopping-cart" class="w-10 h-10"></i>
                                    </div>
                                    <h2 class="text-2xl font-black text-gray-900 mb-2">Your cart is empty</h2>
                                    <p class="text-gray-500 font-bold mb-8">Looks like you haven't added anything to your cart
                                        yet.</p>
                                    <a href="{{ route('products.index') }}"
                                        class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-[color:var(--primary)] text-white text-sm font-black uppercase tracking-widest hover:brightness-110 transition shadow-lg shadow-orange-100">
                                        Start Shopping
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Continue Shopping Link -->
                    <div class="mt-8 px-2">
                        <a href="{{ route('products.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-[color:var(--primary)] transition">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i> Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Right: Summary & Sidebar -->
                <div class="lg:col-span-4">
                    <div class="sticky top-24 space-y-6">
                        <!-- Coupon Card -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                        <h2 class="text-[10px] font-black text-gray-400 mb-4 uppercase tracking-widest">Have a Promo Code?</h2>
                        <div class="flex gap-2">
                            <input type="text" placeholder="Enter coupon code"
                                class="flex-1 bg-white border border-gray-200 rounded-md px-4 py-2 text-sm focus:outline-none focus:border-[color:var(--primary)] focus:ring-0 transition-colors">
                            <button
                                class="bg-gray-50 text-gray-600 border border-gray-200 px-4 py-2 text-[10px] font-black uppercase tracking-widest rounded-md hover:bg-gray-100 transition">APPLY
                                NOW</button>
                        </div>
                    </div>

                    <!-- Order Summary Card (Flipkart Style) -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                        <div class="p-6 border-b border-gray-50">
                            <h2 class="text-base font-extrabold text-gray-500 uppercase tracking-wider">Price Details</h2>
                        </div>

                        <div class="p-6 space-y-5">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Price (2 items)</span>
                                <span class="text-gray-900 font-medium">₹10,498</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Discount</span>
                                <span class="text-green-600 font-bold">- ₹3,201</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Coupon Savings</span>
                                <span class="text-green-600 font-bold">- ₹0</span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Delivery Charges</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-400 line-through">₹80</span>
                                    <span class="text-green-600 font-bold uppercase text-[10px]">Free</span>
                                </div>
                            </div>

                            <div class="border-t border-dashed border-gray-200 pt-5 flex justify-between items-center">
                                <span class="text-lg font-black text-gray-900">Total Amount</span>
                                <span class="text-lg font-black text-gray-900 total-payable-display">₹10,297</span>
                            </div>

                            <div class="bg-green-50 rounded-lg p-3 flex items-center gap-2 text-green-700">
                                <i data-lucide="badge-check" class="w-4 h-4"></i>
                                <p class="text-sm font-bold">You'll save ₹3,201 on this order!</p>
                            </div>

                            <button class="w-full bg-[color:var(--primary)] text-white py-4 rounded-md text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-orange-100 hover:brightness-110 active:scale-[0.98] transition-all mt-4">
                                PROCEED TO CHECKOUT
                            </button>

                            <div class="mt-6 flex items-center gap-3 text-[10px] text-gray-400 font-bold uppercase tracking-widest justify-center">
                                <i data-lucide="shield-check" class="w-4 h-4"></i> Safe and Secure Payments
                            </div>
                        </div>
                    </div>
                </div>
                </div>

            </div>

            <!-- Recommendations Section -->
            @php
                $recommendations = [
                    ['title' => 'Ultimate Immunity Duo', 'tagline' => 'Double protection', 'price' => 3299, 'mrp' => 4999, 'image' => 'remenant-product1.jpg', 'rating' => 4.8],
                    ['title' => 'Glow & Strength Bundle', 'tagline' => 'Beauty essentials', 'price' => 3499, 'mrp' => 5499, 'image' => 'remenant-product2.jpg', 'rating' => 4.9],
                    ['title' => 'Daily Wellness Pack', 'tagline' => 'Energy boost', 'price' => 2899, 'mrp' => 3999, 'image' => 'remenant-product3.jpg', 'rating' => 4.7],
                    ['title' => 'Vitality Essentials', 'tagline' => 'Daily health', 'price' => 1999, 'mrp' => 2999, 'image' => 'remenant-product1.jpg', 'rating' => 4.6],
                ];
            @endphp

            @if(count($recommendations) > 0)
                <div class="mt-8 sm:mt-24 border-t border-gray-200 pt-6 sm:pt-16">
                    <div class="mb-6 sm:mb-12">
                        <h2 class="text-2xl font-extrabold text-[color:var(--text-primary)]">You Might Also Need</h2>
                        <p class="text-sm text-gray-400 mt-1 uppercase tracking-widest font-bold">Selected for your wellness
                            journey</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($recommendations as $rec)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
                                <div class="aspect-square bg-gray-50">
                                    <img src="{{ asset('images/products/' . $rec['image']) }}" alt="{{ $rec['title'] }}"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="p-5 flex-1 flex flex-col">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[color:var(--primary)] mb-1">
                                        {{ $rec['tagline'] }}</p>
                                    <h4 class="text-sm font-extrabold text-[color:var(--text-primary)] mb-4 line-clamp-1">
                                        {{ $rec['title'] }}</h4>
                                    <div class="mt-auto flex items-center justify-between">
                                        <div class="flex items-baseline gap-2">
                                            <span
                                                class="text-lg font-black text-[color:var(--text-primary)]">₹{{ number_format($rec['price']) }}</span>
                                            <span class="text-xs text-gray-400 line-through">₹{{ number_format($rec['mrp']) }}</span>
                                        </div>
                                        <button
                                            class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full hover:bg-[color:var(--primary)] hover:text-white transition">
                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('scripts')
    <!-- Item Removal Confirmation Modal -->
    <div id="removeConfirmModal"
        class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div
            class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl transform transition-transform duration-300 scale-90">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="trash-2" class="w-8 h-8 text-red-500"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Remove Item?</h3>
                <p class="text-gray-500 font-bold mb-8">Are you sure you want to remove this item from your cart?</p>
                <div class="flex gap-4">
                    <button id="cancelRemove"
                        class="flex-1 px-6 py-3 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition active:scale-95">
                        Cancel
                    </button>
                    <button id="confirmRemove"
                        class="flex-1 px-6 py-3 rounded-xl bg-red-500 text-white text-sm font-black hover:bg-red-600 transition shadow-lg shadow-red-100 active:scale-95">
                        Remove
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            let itemToRemove = null;

            function updateTotals() {
                let subtotal = 0;
                let count = 0;
                $('.cart-item').each(function () {
                    const price = parseFloat($(this).data('price'));
                    const qty = parseInt($(this).find('.quantity-value').text());
                    subtotal += price * qty;
                    count += qty;
                });

                // Update displays (mockup update)
                $('.subtotal-display').text('₹' + subtotal.toLocaleString());
                $('.cart-count-display').text('My Cart (' + $('.cart-item').length + ')');

                // For this mockup, we'll just update the main total similarly
                // Real implementation would involve server-side logic or more complex frontend state
                $('.total-payable-display').text('₹' + (subtotal + 79 + 15 + 70 - 80).toLocaleString()); // Simple calc matching mockup logic
            }

            // Increase Quantity
            $('.btn-increase').on('click', function () {
                const $val = $(this).siblings('.quantity-value');
                let currentVal = parseInt($val.text());
                $val.text(currentVal + 1);
                updateTotals();
            });

            // Decrease Quantity
            $('.btn-decrease').on('click', function () {
                const $val = $(this).siblings('.quantity-value');
                let currentVal = parseInt($val.text());
                if (currentVal > 1) {
                    $val.text(currentVal - 1);
                    updateTotals();
                } else if (currentVal === 1) {
                    showRemoveModal($(this).closest('.cart-item'));
                }
            });

            // Remove Button
            $('.btn-remove').on('click', function () {
                showRemoveModal($(this).closest('.cart-item'));
            });

            function showRemoveModal($item) {
                itemToRemove = $item;
                const $modal = $('#removeConfirmModal');
                $('body').addClass('overflow-hidden');
                if (window.lenis) window.lenis.stop();

                $modal.removeClass('hidden').addClass('flex');
                setTimeout(() => {
                    $modal.removeClass('opacity-0').addClass('opacity-100');
                    $modal.find('> div').removeClass('scale-90').addClass('scale-100');
                }, 10);
            }

            function hideRemoveModal() {
                const $modal = $('#removeConfirmModal');
                $modal.find('> div').removeClass('scale-100').addClass('scale-90');
                $modal.removeClass('opacity-100').addClass('opacity-0');
                setTimeout(() => {
                    $modal.removeClass('flex').addClass('hidden');
                    $('body').removeClass('overflow-hidden');
                    if (window.lenis) window.lenis.start();
                    itemToRemove = null;
                }, 300);
            }

            $('#cancelRemove').on('click', hideRemoveModal);

            $('#confirmRemove').on('click', function () {
                if (itemToRemove) {
                    itemToRemove.fadeOut(300, function () {
                        $(this).remove();
                        updateTotals();
                        if ($('.cart-item').length === 0) {
                            location.reload(); // Show empty cart state
                        }
                    });
                }
                hideRemoveModal();
            });

            // Close modal on background click
            $('#removeConfirmModal').on('click', function (e) {
                if (e.target === this) hideRemoveModal();
            });
        });
    </script>
@endpush