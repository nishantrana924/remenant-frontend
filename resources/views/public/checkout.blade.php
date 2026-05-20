@extends('public.layouts.app')

@php
    seo()->set([
        'title' => 'Secure Checkout | Remenant Health',
        'robots' => 'noindex, nofollow',
    ]);
@endphp

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush

@section('content')
<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>
<div class="min-h-screen bg-[#FDFCFB] pt-24 pb-12 px-4">
    <div class="max-w-[1300px] mx-auto">
        <!-- Compact Header -->
        <div class="flex items-center justify-between mb-8 pb-6 border-b border-black/5">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" class="h-10 w-10 flex items-center justify-center rounded-xl bg-white shadow-sm ring-1 ring-black/5 hover:bg-orange-50 transition-colors">
                    <i data-lucide="chevron-left" class="h-5 w-5 text-slate-600"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black italic tracking-tighter uppercase text-slate-900">Secure Checkout</h1>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Order Review & Shipping</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-3 opacity-40 grayscale text-[10px] font-black uppercase tracking-widest">
                <span>Visa</span>
                <span>•</span>
                <span>Mastercard</span>
                <span>•</span>
                <span>PCI Secured</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            
            <!-- Left Column: Checkout Form -->
            <div class="lg:col-span-7">
                <form id="checkout-form" method="POST" action="{{ route('checkout.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="coupon_code" id="coupon-input-hidden">
                    @if(isset($buyNowProduct))
                        <input type="hidden" name="buy_now_product_id" value="{{ $buyNowProduct->id }}">
                    @endif
                    
                    <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm ring-1 ring-black/[0.03] space-y-8">
                        <!-- Contact Information -->
                        <section>
                            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                                01. Contact Information
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">Email Address</label>
                                    <input type="email" name="email" required class="w-full rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none focus:border-orange-500 transition-all" value="{{ auth()->user()->email }}">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400 ml-1">Phone Number</label>
                                    <input type="tel" name="phone" required pattern="[0-9]{10}" placeholder="9876543210" class="w-full rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none focus:border-orange-500 transition-all">
                                </div>
                            </div>
                        </section>

                        <!-- Shipping Address -->
                        <section>
                            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                02. Shipping Address
                            </h2>

                            @if(count($addresses) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                                @foreach($addresses as $address)
                                <label class="relative flex flex-col p-5 rounded-2xl border-2 cursor-pointer transition-all address-card {{ $address->is_default ? 'border-orange-500 bg-orange-50/30' : 'border-slate-50 hover:border-orange-200' }}">
                                    <input type="radio" name="selected_address_id" value="{{ $address->id }}" 
                                           class="absolute top-4 right-4 h-4 w-4 text-orange-500"
                                           {{ $address->is_default ? 'checked' : '' }}
                                           onchange="handleAddressSelection(this, {{ json_encode($address) }})">
                                    <div class="flex items-center gap-2 mb-2">
                                        <i data-lucide="{{ $address->type === 'Work' ? 'briefcase' : 'home' }}" class="h-3 w-3 text-slate-400"></i>
                                        <span class="text-[10px] font-black text-slate-900 uppercase tracking-tight">{{ $address->type }}</span>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-500 leading-relaxed">
                                        <span class="text-slate-900">{{ $address->full_name }}</span><br>
                                        {{ Str::limit($address->address_line1, 30) }}<br>
                                        {{ $address->city }}, {{ $address->pincode }}
                                    </span>
                                </label>
                                @endforeach
                                <label class="relative flex flex-col items-center justify-center p-5 rounded-2xl border-2 border-dashed border-slate-100 cursor-pointer hover:border-orange-200 transition-all address-card" onclick="clearAddressForm()">
                                    <input type="radio" name="selected_address_id" value="new" class="hidden">
                                    <i data-lucide="plus" class="h-4 w-4 text-slate-300 mb-1"></i>
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">New Address</span>
                                </label>
                            </div>
                            @endif

                            <div class="space-y-4 {{ count($addresses) > 0 ? 'hidden opacity-0' : '' }} transition-all duration-300" id="address-form-fields">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <input type="text" name="first_name" id="ship-first-name" required placeholder="First Name" class="w-full rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none focus:border-orange-500 transition-all">
                                    <input type="text" name="last_name" id="ship-last-name" required placeholder="Last Name" class="w-full rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none focus:border-orange-500 transition-all">
                                </div>
                                <input type="text" name="address" id="ship-address" required placeholder="Street Address, House No, Area" class="w-full rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none focus:border-orange-500 transition-all">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <input type="text" name="pincode" id="ship-pincode" required pattern="[0-9]{6}" placeholder="Pincode" class="w-full rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none focus:border-orange-500 transition-all">
                                    <input type="text" id="city-input" name="city" required placeholder="City" class="w-full rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none focus:border-orange-500 transition-all">
                                    <div class="relative">
                                        <select id="state-select" name="state" required class="w-full rounded-xl bg-slate-50/50 border border-slate-100 px-4 py-3 text-sm font-bold text-slate-900 focus:outline-none focus:border-orange-500 appearance-none transition-all">
                                            <option value="">State</option>
                                            <option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
                                            <option value="Andhra Pradesh">Andhra Pradesh</option>
                                            <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                            <option value="Assam">Assam</option>
                                            <option value="Bihar">Bihar</option>
                                            <option value="Chandigarh">Chandigarh</option>
                                            <option value="Chhattisgarh">Chhattisgarh</option>
                                            <option value="Dadra and Nagar Haveli">Dadra and Nagar Haveli</option>
                                            <option value="Daman and Diu">Daman and Diu</option>
                                            <option value="Delhi">Delhi</option>
                                            <option value="Goa">Goa</option>
                                            <option value="Gujarat">Gujarat</option>
                                            <option value="Haryana">Haryana</option>
                                            <option value="Himachal Pradesh">Himachal Pradesh</option>
                                            <option value="Jammu and Kashmir">Jammu and Kashmir</option>
                                            <option value="Jharkhand">Jharkhand</option>
                                            <option value="Karnataka">Karnataka</option>
                                            <option value="Kerala">Kerala</option>
                                            <option value="Ladakh">Ladakh</option>
                                            <option value="Lakshadweep">Lakshadweep</option>
                                            <option value="Madhya Pradesh" selected>Madhya Pradesh</option>
                                            <option value="Maharashtra">Maharashtra</option>
                                            <option value="Manipur">Manipur</option>
                                            <option value="Meghalaya">Meghalaya</option>
                                            <option value="Mizoram">Mizoram</option>
                                            <option value="Nagaland">Nagaland</option>
                                            <option value="Odisha">Odisha</option>
                                            <option value="Puducherry">Puducherry</option>
                                            <option value="Punjab">Punjab</option>
                                            <option value="Rajasthan">Rajasthan</option>
                                            <option value="Sikkim">Sikkim</option>
                                            <option value="Tamil Nadu">Tamil Nadu</option>
                                            <option value="Telangana">Telangana</option>
                                            <option value="Tripura">Tripura</option>
                                            <option value="Uttar Pradesh">Uttar Pradesh</option>
                                            <option value="Uttarakhand">Uttarakhand</option>
                                            <option value="West Bengal">West Bengal</option>
                                        </select>
                                        <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- Payment Method -->
                        <section>
                            <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                03. Payment Method
                            </h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <label class="relative flex items-center gap-3 p-5 rounded-2xl border-2 border-slate-50 cursor-pointer hover:border-orange-500/20 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/30 transition-all group">
                                    <input type="radio" name="payment_method" value="prepaid" class="h-4 w-4 text-orange-500 border-slate-200 focus:ring-orange-500" checked>
                                    <div class="flex-1">
                                        <span class="block text-xs font-black text-slate-900 uppercase tracking-tight">Online Payment</span>
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase mt-0.5">UPI, Cards, NetBanking</span>
                                    </div>
                                    <i data-lucide="shield-check" class="h-4 w-4 text-emerald-500 opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></i>
                                </label>
                                <label class="relative flex items-center gap-3 p-5 rounded-2xl border-2 border-slate-50 cursor-pointer hover:border-orange-500/20 has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50/30 transition-all group">
                                    <input type="radio" name="payment_method" value="cod" class="h-4 w-4 text-orange-500 border-slate-200 focus:ring-orange-500">
                                    <div class="flex-1">
                                        <span class="block text-xs font-black text-slate-900 uppercase tracking-tight">Cash on Delivery</span>
                                        <span class="block text-[10px] font-bold text-slate-400 uppercase mt-0.5">Pay at your doorstep</span>
                                    </div>
                                    <i data-lucide="hand-coins" class="h-4 w-4 text-slate-400 opacity-0 group-has-[:checked]:opacity-100 transition-opacity"></i>
                                </label>
                            </div>
                        </section>
                    </div>

                    <button type="submit" id="checkout-btn" class="w-full rounded-2xl bg-slate-900 py-4 text-sm font-black text-white uppercase tracking-[0.2em] shadow-xl hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                        <span class="btn-text">Place Order & Pay</span>
                        <i data-lucide="arrow-right" class="h-4 w-4 btn-icon"></i>
                    </button>
                </form>
            </div>

            <!-- Right Column: Order Summary -->
            <div class="lg:col-span-5">
                <div class="lg:sticky lg:top-28 bg-white rounded-3xl p-6 shadow-sm ring-1 ring-black/[0.03]">
                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Order Summary</h2>
                    
                    <div class="space-y-4 mb-6">
                        @foreach($items as $item)
                            <div class="flex gap-4 items-center p-3 rounded-2xl bg-slate-50/50 ring-1 ring-black/[0.02]">
                                <a href="{{ route('products.show', $item['slug'] ?? '#') }}" class="h-16 w-16 shrink-0 rounded-xl bg-white p-2 shadow-sm relative overflow-hidden bg-gray-100 group/img">
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_1.5s_infinite] skeleton-overlay"></div>
                                    <img src="{{ \App\Helpers\ImageHelper::getUrl($item['image'], 'products') }}" 
                                         alt="{{ $item['title'] }}" 
                                         class="h-full w-full object-cover opacity-0 transition-opacity duration-300"
                                         onload="this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove(); this.parentElement.classList.remove('bg-gray-100')"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($item['title']) }}&color=ea5f06&background=fff1e8'; this.classList.remove('opacity-0'); if(this.previousElementSibling) this.previousElementSibling.remove();">
                                    <span class="absolute -top-1.5 -right-1.5 h-5 w-5 rounded-full bg-slate-900 text-white text-[9px] font-black flex items-center justify-center z-10">{{ $item['quantity'] }}</span>
                                </a>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('products.show', $item['slug'] ?? '#') }}" class="block group/title">
                                        <h3 class="text-xs font-black text-slate-900 uppercase truncate group-hover/title:text-orange-500 transition-colors">{{ $item['title'] }}</h3>
                                    </a>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase mt-0.5 truncate">₹{{ number_format($item['price']) }} x {{ $item['quantity'] }}</p>
                                    <p class="text-sm font-black text-slate-900 mt-1">₹{{ number_format($item['price'] * $item['quantity']) }}</p>
                                    
                                    @if(isset($item['model']) && in_array($item['model']->product_type, ['combo', 'both']) && $item['model']->comboItems->count() > 0)
                                        <div class="mt-2 space-y-1">
                                            <p class="text-[8px] font-black text-orange-500 uppercase tracking-widest">Included in Bundle:</p>
                                            @foreach($item['model']->comboItems as $ci)
                                                @if($ci->product)
                                                    <div class="flex items-center gap-1.5">
                                                        <i data-lucide="check" class="h-2 w-2 text-emerald-500"></i>
                                                        <span class="text-[9px] font-bold text-slate-500 uppercase truncate">{{ $ci->quantity }}x {{ $ci->product->title }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Coupon Code Section -->
                    <div class="p-6 rounded-3xl bg-slate-50 ring-1 ring-black/[0.02] mb-6" 
                         x-data="{ 
                            coupon: '', 
                            applying: false, 
                            msg: '', 
                            success: false,
                            apply() {
                                if (!this.coupon) return;
                                this.applying = true;
                                this.msg = 'Applying...';
                                
                                const rawSubtotal = '{{ $subtotal }}'.replace(/,/g, '');
                                const payload = {
                                    code: this.coupon,
                                    product_id: parseInt('{{ isset($buyNowProduct) ? $buyNowProduct->id : (count($items) > 0 ? collect($items)->first()['id'] : 0) }}'),
                                    amount: parseFloat(rawSubtotal)
                                };

                                axios.post('{{ route('coupons.apply') }}', payload, {
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content
                                    }
                                }).then(res => {
                                    this.success = true;
                                    this.msg = res.data.message;
                                    
                                    const discount = parseFloat(res.data.discount || 0);
                                    const subtotal = parseFloat(rawSubtotal);
                                    const shipping = parseFloat('{{ $shipping }}'.replace(/,/g, ''));
                                    
                                    // Update UI Safely
                                    const discountRow = document.getElementById('discount-row');
                                    const couponDisp = document.getElementById('coupon-display');
                                    const discountVal = document.getElementById('discount-val');
                                    const totalVal = document.getElementById('total-val');
                                    const hiddenInput = document.getElementById('coupon-input-hidden');

                                    if (discountRow) discountRow.style.display = 'flex';
                                    if (couponDisp) couponDisp.innerText = res.data.code;
                                    if (discountVal) discountVal.innerText = discount.toLocaleString();
                                    if (totalVal) totalVal.innerText = (subtotal + shipping - discount).toLocaleString();
                                    if (hiddenInput) hiddenInput.value = res.data.code;
                                    
                                    if (window.showToast) window.showToast(res.data.message);
                                }).catch(err => {
                                    this.success = false;
                                    this.msg = err.response?.data?.message || 'Invalid coupon';
                                    console.error('Coupon Error:', err);
                                }).finally(() => {
                                    this.applying = false;
                                });
                            }
                         }">
                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Have a promo code?</h4>
                        <div class="flex gap-2">
                            <input type="text" x-model="coupon" @keyup.enter="apply()" placeholder="ENTER CODE" class="flex-1 bg-white border border-slate-100 rounded-xl px-4 py-3 text-xs font-black placeholder:text-slate-300 focus:outline-none focus:border-orange-500 uppercase tracking-widest">
                            <button type="button" @click="apply()" :disabled="applying" class="bg-slate-900 text-white rounded-xl px-6 text-[10px] font-black uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all disabled:opacity-50">
                                <span x-show="!applying">Apply</span>
                                <span x-show="applying">...</span>
                            </button>
                        </div>
                        <p x-show="msg" :class="success ? 'text-emerald-600' : 'text-rose-500'" class="text-[10px] font-bold uppercase mt-3" x-text="msg"></p>
                    </div>

                    <!-- Totals -->
                    <div class="space-y-3 pt-6 border-t border-slate-100" id="totals-section">
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-slate-400">
                            <span>Subtotal</span>
                            <span class="text-slate-900 font-black">₹{{ number_format($subtotal) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-slate-400" id="discount-row" style="display: none;">
                            <span class="text-emerald-600">Discount (<span id="coupon-display"></span>)</span>
                            <span class="text-emerald-600 font-black">- ₹<span id="discount-val">0</span></span>
                        </div>
                        <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-slate-400">
                            <span>Shipping</span>
                            @if($shipping == 0)
                                <span class="text-emerald-600 font-black">Free</span>
                            @else
                                <span class="text-slate-900 font-black">₹{{ number_format($shipping) }}</span>
                            @endif
                        </div>
                        <div class="flex justify-between items-center pt-4 mt-4 border-t border-slate-100">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total</span>
                            <span class="text-2xl font-black text-slate-900">₹<span id="total-val">{{ number_format($total) }}</span></span>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center justify-center gap-3 text-[9px] font-black uppercase tracking-widest text-slate-300">
                        <span>Visa</span>
                        <span>•</span>
                        <span>Mastercard</span>
                        <span>•</span>
                        <span>UPI</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-Save Persistence
    const STORAGE_KEY = 'remenant_checkout_draft';

    function saveDraft() {
        const form = document.getElementById('checkout-form');
        const formData = new FormData(form);
        const data = {};
        formData.forEach((value, key) => {
            if (key !== '_token') data[key] = value;
        });
        localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
    }

    function loadDraft() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (!saved) return;
        
        try {
            const data = JSON.parse(saved);
            const form = document.getElementById('checkout-form');
            Object.keys(data).forEach(key => {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    if (input.type === 'radio') {
                        if (input.value === data[key]) input.checked = true;
                    } else {
                        input.value = data[key];
                    }
                }
            });
            if (data.city) autoDetectState(data.city);
        } catch (e) { console.error('Failed to load draft', e); }
    }

    window.handleAddressSelection = function(radio, address) {
        // Update UI
        document.querySelectorAll('.address-card').forEach(c => c.classList.remove('border-orange-500', 'bg-orange-50/30'));
        radio.parentElement.classList.add('border-orange-500', 'bg-orange-50/30');

        const formFields = document.getElementById('address-form-fields');
        formFields.classList.add('hidden', 'opacity-0');

        // Split name
        const names = address.full_name.split(' ');
        document.getElementById('ship-first-name').value = names[0] || '';
        document.getElementById('ship-last-name').value = names.slice(1).join(' ') || '';
        document.getElementById('ship-address').value = address.address_line1 + (address.address_line2 ? ', ' + address.address_line2 : '');
        document.getElementById('ship-pincode').value = address.pincode;
        document.getElementById('city-input').value = address.city;
        document.getElementById('state-select').value = address.state;
        
        // Update phone
        document.querySelector('[name="phone"]').value = address.phone;

        saveDraft();
    }

    window.clearAddressForm = function() {
        document.querySelectorAll('.address-card').forEach(c => c.classList.remove('border-orange-500', 'bg-orange-50/30'));
        const newCardInput = document.querySelector('input[value="new"]');
        if (newCardInput) {
            const newCard = newCardInput.parentElement;
            newCard.classList.add('border-orange-500', 'bg-orange-50/30');
        }

        const formFields = document.getElementById('address-form-fields');
        if (formFields) {
            formFields.classList.remove('hidden');
            setTimeout(() => formFields.classList.remove('opacity-0'), 10);
        }

        document.getElementById('ship-first-name').value = '';
        document.getElementById('ship-last-name').value = '';
        document.getElementById('ship-address').value = '';
        document.getElementById('ship-pincode').value = '';
        document.getElementById('city-input').value = '';
        document.getElementById('state-select').value = 'Madhya Pradesh';
        
        saveDraft();
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadDraft();
        const form = document.getElementById('checkout-form');
        form.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('input', saveDraft);
            input.addEventListener('change', saveDraft);
        });

        // Initialize with default address if exists
        const defaultRadio = document.querySelector('input[name="selected_address_id"]:checked');
        if (defaultRadio && defaultRadio.value !== 'new') {
            const initialAddress = @json($addresses->where('is_default', true)->first());
            if (initialAddress) handleAddressSelection(defaultRadio, initialAddress);
        } else if (!defaultRadio) {
            clearAddressForm();
        }
    });

    function autoDetectState(city) {
        // ... (existing code) ...
        const stateSelect = document.getElementById('state-select');
        const cityLower = city.toLowerCase().trim();
        const mapping = {
            'ujjain': 'Madhya Pradesh',
            'indore': 'Madhya Pradesh',
            'bhopal': 'Madhya Pradesh',
            'mumbai': 'Maharashtra',
            'pune': 'Maharashtra',
            'delhi': 'Delhi',
            'bangalore': 'Karnataka'
        };
        if (mapping[cityLower]) stateSelect.value = mapping[cityLower];
    }
</script>
@endsection
