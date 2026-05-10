@extends('public.layouts.app')

@section('title', 'Shopping Cart - Remenant Health')

@section('content')
    <style>
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }
    </style>
    <div class="bg-[#FDF9F6] py-12 sm:py-16 min-h-screen pt-24">
        <div class="mx-auto max-w-[1400px] px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-start">
                
                <!-- Left: Cart Items Card -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                        <div class="p-6 border-b border-gray-100">
                            <h1 class="text-2xl font-extrabold text-slate-900">My Cart ({{ count($cart) }})</h1>
                        </div>

                        <div class="divide-y divide-gray-200 px-6">
                            @if(count($cart) > 0)
                                @foreach($cart as $id => $details)
                                    <div class="py-6 sm:py-8 flex flex-col sm:flex-row gap-4 sm:gap-6 cart-item border-b border-gray-200 last:border-0" data-item-id="{{ $id }}" data-price="{{ $details['price'] }}" data-mrp="{{ $details['mrp'] }}">
                                        @php
                                            $imagePath = \App\Helpers\ImageHelper::getUrl($details['image'] ?? null, 'products');
                                        @endphp
                                        
                                        <!-- Desktop Image -->
                                        <div class="hidden sm:block w-32 h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100 relative group/img">
                                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_1.5s_infinite] skeleton-overlay"></div>
                                            <img src="{{ $imagePath }}" 
                                                 alt="{{ $details['title'] }}" 
                                                 class="h-full w-full object-contain p-2 opacity-0 transition-opacity duration-300"
                                                 onload="this.classList.remove('opacity-0'); this.previousElementSibling.remove(); this.parentElement.classList.remove('bg-gray-100')"
                                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($details['title']) }}&color=ea5f06&background=fff1e8'; this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();">
                                        </div>

                                        <!-- Mobile Header -->
                                        <div class="flex sm:hidden gap-4 items-start">
                                            <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0 border border-gray-100 relative">
                                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_1.5s_infinite] skeleton-overlay"></div>
                                                <img src="{{ $imagePath }}" 
                                                     alt="{{ $details['title'] }}" 
                                                     class="h-full w-full object-contain p-2 opacity-0 transition-opacity duration-300"
                                                     onload="this.classList.remove('opacity-0'); this.previousElementSibling.remove(); this.parentElement.classList.remove('bg-gray-100')"
                                                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($details['title']) }}&color=ea5f06&background=fff1e8'; this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();">
                                            </div>
                                            <div class="flex-1">
                                                <h3 class="text-sm font-extrabold text-slate-900 leading-tight">{{ $details['title'] }}</h3>
                                                <p class="text-[11px] text-gray-400 mt-1 font-medium">{{ $details['subtitle'] ?? 'Remenant Health' }}</p>
                                                <div class="mt-3 flex items-center gap-2">
                                                    <span class="text-sm text-gray-400 line-through">₹{{ number_format($details['mrp']) }}</span>
                                                    <span class="text-lg font-black text-slate-900">₹{{ number_format($details['price']) }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Desktop Content Area -->
                                        <div class="hidden sm:flex flex-1 flex flex-col justify-between">
                                            <div class="flex justify-between items-start gap-4">
                                                <div class="flex-1">
                                                    <h3 class="text-base font-semibold text-slate-900 leading-tight">{{ $details['title'] }}</h3>
                                                    <p class="text-xs text-gray-400 mt-2 font-medium">{{ $details['subtitle'] ?? 'Remenant Health' }} • {{ $details['details'] ?? 'Standard' }}</p>
                                                    <div class="mt-3 flex items-center gap-2 text-[11px] text-gray-500 font-bold bg-gray-50/50 w-fit px-2 py-1 rounded border border-gray-100">
                                                        <i data-lucide="truck" class="w-3.5 h-3.5 text-gray-400"></i>
                                                        Delivery by <span class="text-gray-900">{{ $details['delivery'] }}</span>
                                                    </div>
                                                </div>

                                                <div class="flex flex-col items-end gap-4">
                                                    <div class="flex items-center quantity-selector">
                                                        <button class="w-10 h-10 flex items-center justify-center bg-gray-50 text-gray-500 rounded-l-md border border-gray-200 hover:bg-gray-100 transition btn-decrease">
                                                            <i data-lucide="minus" class="w-4 h-4"></i>
                                                        </button>
                                                        <div class="w-12 h-10 flex items-center justify-center border-y border-gray-200 text-sm font-bold text-gray-700 quantity-value">
                                                            {{ $details['quantity'] }}
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
                                                <span class="text-sm text-gray-400 line-through">₹{{ number_format($details['mrp']) }}</span>
                                                <span class="text-xl font-bold text-slate-900">₹{{ number_format($details['price']) }}</span>
                                                @php 
                                                    $mrp = (float) ($details['mrp'] ?? 0);
                                                    $price = (float) ($details['price'] ?? 0);
                                                    $discount = $mrp > 0 ? round((1 - ($price / $mrp)) * 100) : 0; 
                                                @endphp
                                                @if($discount > 0)
                                                    <span class="text-sm font-bold text-[#FF4D4D] bg-red-50 px-2 py-0.5 rounded">{{ $discount }}% OFF</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Mobile Stacking -->
                                        <div class="sm:hidden space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center quantity-selector">
                                                    <button class="w-9 h-9 flex items-center justify-center bg-gray-50 text-gray-500 rounded-l-md border border-gray-200 btn-decrease">
                                                        <i data-lucide="minus" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                    <div class="w-10 h-9 flex items-center justify-center border-y border-gray-200 text-sm font-bold text-gray-700 quantity-value">
                                                        {{ $details['quantity'] }}
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
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300">
                                        <i data-lucide="shopping-cart" class="w-10 h-10"></i>
                                    </div>
                                    <h2 class="text-2xl font-black text-gray-900 mb-2">Your cart is empty</h2>
                                    <p class="text-gray-500 font-bold mb-8">Looks like you haven't added anything to your cart yet.</p>
                                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl bg-orange-500 text-white text-sm font-black uppercase tracking-widest hover:brightness-110 transition shadow-lg shadow-orange-100">
                                        Start Shopping
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Summary -->
                @if(count($cart) > 0)
                <div class="lg:col-span-4">
                    <div class="sticky top-24 space-y-6">
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                            <div class="p-6 border-b border-gray-50">
                                <h2 class="text-base font-extrabold text-gray-500 uppercase tracking-wider">Price Details</h2>
                            </div>

                            <div class="p-6 space-y-5">
                                @php
                                    $totalPrice = 0;
                                    $totalMrp = 0;
                                    foreach($cart as $item) {
                                        $totalPrice += $item['price'] * $item['quantity'];
                                        $totalMrp += $item['mrp'] * $item['quantity'];
                                    }
                                    $totalDiscount = $totalMrp - $totalPrice;
                                @endphp
                                <div class="flex justify-between items-center text-sm">
                                    <span id="cart-items-count" class="text-gray-600">Price ({{ count($cart) }} items)</span>
                                    <span id="cart-mrp-total" class="text-gray-900 font-medium">₹{{ number_format($totalMrp) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm text-green-600 font-bold">
                                    <span>Discount</span>
                                    <span id="cart-discount-total">- ₹{{ number_format($totalDiscount) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-600">Delivery Charges</span>
                                    <span class="text-green-600 font-bold uppercase text-[10px]">Free</span>
                                </div>
                                <div class="border-t border-dashed border-gray-200 pt-5 flex justify-between items-center">
                                    <span class="text-lg font-black text-gray-900">Total Amount</span>
                                    <span id="cart-final-total" class="text-lg font-black text-gray-900">₹{{ number_format($totalPrice) }}</span>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3 flex items-center gap-2 text-green-700">
                                    <i data-lucide="badge-check" class="w-4 h-4"></i>
                                    <p class="text-sm font-bold">You'll save <span id="cart-savings-amount">₹{{ number_format($totalDiscount) }}</span> on this order!</p>
                                </div>

                                <a href="{{ route('checkout') }}" class="w-full bg-orange-500 text-white py-4 rounded-md text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-orange-100 hover:brightness-110 active:scale-[0.98] transition-all mt-4 flex items-center justify-center">
                                    PROCEED TO CHECKOUT
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Remove Modal -->
    <div id="removeConfirmModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div class="bg-white rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl transform transition-transform duration-300 scale-90">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="trash-2" class="w-8 h-8 text-red-500"></i>
                </div>
                <h3 class="text-xl font-black text-gray-900 mb-2">Remove Item?</h3>
                <p class="text-gray-500 font-bold mb-8">Are you sure you want to remove this item from your cart?</p>
                <div class="flex gap-4">
                    <button id="cancelRemove" class="flex-1 px-6 py-3 rounded-xl border border-gray-200 text-sm font-bold text-gray-600 hover:bg-gray-50 transition active:scale-95">Cancel</button>
                    <button id="confirmRemove" class="flex-1 px-6 py-3 rounded-xl bg-red-500 text-white text-sm font-black hover:bg-red-600 transition shadow-lg shadow-red-100 active:scale-95">Remove</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        let itemToRemove = null;

        // AJAX Update Quantity
        function updateCart(id, qty, row) {
            $.ajax({
                url: '{{ route("cart.update") }}',
                method: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    quantity: qty
                },
                success: function (response) {
                    if (response.success) {
                        // Update quantity in UI
                        row.find('.quantity-value').text(qty);
                        
                        // Update individual item total if needed (optional)
                        
                        // Update sidebar totals
                        updateSidebarTotals(response.totals);
                        
                        // Show success toast using our global system
                        if (window.RemenantApp) {
                            RemenantApp.showToast('success', 'Cart updated');
                        }
                    }
                }
            });
        }

        // AJAX Remove Item
        function removeItem(id) {
            $.ajax({
                url: '{{ route("cart.remove") }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function (response) {
                    if (response.success) {
                        // Remove item from DOM
                        $(`.cart-item[data-item-id="${id}"]`).fadeOut(300, function() {
                            $(this).remove();
                            
                            // If cart is empty, reload to show empty state
                            if (response.totals.count === 0) {
                                location.reload();
                            }
                        });

                        // Update sidebar totals
                        updateSidebarTotals(response.totals);
                        
                        if (window.RemenantApp) {
                            RemenantApp.showToast('success', 'Item removed');
                            RemenantApp.updateCartCount(response.totals.count);
                        }
                        
                        hideRemoveModal();
                    }
                }
            });
        }

        function updateSidebarTotals(totals) {
            $('#cart-items-count').text(`Price (${totals.count} items)`);
            $('#cart-mrp-total').text(`₹${totals.subtotal}`);
            $('#cart-discount-total').text(`- ₹${totals.discount}`);
            $('#cart-final-total').text(`₹${totals.total}`);
            $('#cart-savings-amount').text(`₹${totals.discount}`);
        }

        $('.btn-increase').on('click', function () {
            const row = $(this).closest('.cart-item');
            const id = row.data('item-id');
            const qty = parseInt(row.find('.quantity-value').first().text()) + 1;
            updateCart(id, qty, row);
        });

        $('.btn-decrease').on('click', function () {
            const row = $(this).closest('.cart-item');
            const id = row.data('item-id');
            const qty = parseInt(row.find('.quantity-value').first().text()) - 1;
            if (qty > 0) updateCart(id, qty, row);
            else showRemoveModal(id);
        });

        $('.btn-remove').on('click', function () {
            const id = $(this).closest('.cart-item').data('item-id');
            showRemoveModal(id);
        });

        function showRemoveModal(id) {
            itemToRemove = id;
            $('#removeConfirmModal').removeClass('hidden').addClass('flex');
            setTimeout(() => { $('#removeConfirmModal').removeClass('opacity-0').addClass('opacity-100'); $('#removeConfirmModal > div').removeClass('scale-90').addClass('scale-100'); }, 10);
        }

        function hideRemoveModal() {
            $('#removeConfirmModal').addClass('opacity-0').removeClass('opacity-100');
            setTimeout(() => { $('#removeConfirmModal').addClass('hidden').removeClass('flex'); itemToRemove = null; }, 300);
        }

        $('#cancelRemove').on('click', hideRemoveModal);
        $('#confirmRemove').on('click', function () { if (itemToRemove) removeItem(itemToRemove); hideRemoveModal(); });
    });
</script>
@endpush