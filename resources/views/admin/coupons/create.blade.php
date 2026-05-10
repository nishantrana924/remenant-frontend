@extends('admin.layouts.app')

@section('content')
<div class="pb-24">
    <form action="{{ route('admin.coupons.store') }}" method="POST" id="coupon-form">
        @csrf
        
        <!-- Header (Compact) -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.coupons.index') }}" class="h-10 w-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all bg-white shadow-sm">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">Create Campaign</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-0.5">Remenant Engine • Compact Classic</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="saas-btn-primary py-2 px-6 shadow-xl shadow-orange-200/40">
                    <i data-lucide="zap" class="w-4 h-4"></i>
                    Deploy Campaign
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Side: Core Configuration -->
            <div class="lg:col-span-8 space-y-8">
                
                <!-- 1. Campaign Identity -->
                <div class="saas-card">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500"><i data-lucide="ticket" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-slate-900">Code Architecture</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="saas-label font-bold">Coupon Code</label>
                            <input type="text" name="code" class="saas-input font-black text-lg uppercase tracking-widest text-orange-600" placeholder="E.G. REMENANT2026" required>
                            <p class="text-[10px] text-slate-400 mt-2 italic font-medium">Use unique, catchy codes for better conversion.</p>
                        </div>
                        <div>
                            <label class="saas-label font-bold">Incentive Status</label>
                            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 mt-1">
                                <span class="text-xs font-bold text-slate-600">Active Immediately</span>
                                <input type="checkbox" name="is_active" value="1" checked class="w-6 h-6 rounded-lg border-2 border-slate-200 text-orange-500 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Discount Parameters -->
                <div class="saas-card">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500"><i data-lucide="banknote" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-slate-900">Financial Parameters</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="saas-label font-bold">Discount Type</label>
                            <select name="type" class="saas-input font-bold" required>
                                <option value="percentage">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (₹)</option>
                            </select>
                        </div>
                        <div>
                            <label class="saas-label font-bold">Value</label>
                            <div class="relative">
                                <input type="number" name="value" class="saas-input font-black pr-12" placeholder="0" required>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs" id="value-suffix">%</div>
                            </div>
                        </div>
                        <div>
                            <label class="saas-label font-bold">Min. Purchase Amount (₹)</label>
                            <input type="number" name="min_order_amount" class="saas-input font-bold" placeholder="0">
                        </div>
                        <div>
                            <label class="saas-label font-bold">Usage Limit (Per User/Global)</label>
                            <input type="number" name="usage_limit" class="saas-input font-bold" placeholder="Unlimited">
                        </div>
                    </div>
                </div>

                <!-- 3. Target Distribution (The specific product logic) -->
                <div class="saas-card" x-data="{ target: 'all' }">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500"><i data-lucide="target" class="w-5 h-5"></i></div>
                            <h3 class="text-base font-bold text-slate-900">Incentive Scoping</h3>
                        </div>
                        <div class="flex bg-slate-100 p-1 rounded-full">
                            <button type="button" @click="target = 'all'" :class="target === 'all' ? 'bg-white shadow-sm' : ''" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">All Products</button>
                            <button type="button" @click="target = 'specific'" :class="target === 'specific' ? 'bg-white shadow-sm' : ''" class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">Restricted</button>
                        </div>
                    </div>

                    <div x-show="target === 'all'" class="p-8 bg-slate-50 rounded-[2.5rem] border border-dashed border-slate-200 text-center">
                        <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm"><i data-lucide="globe" class="w-6 h-6"></i></div>
                        <p class="text-xs font-bold text-slate-900 uppercase tracking-widest">Store-Wide Campaign</p>
                        <p class="text-[10px] text-slate-400 mt-1">This coupon will apply to every product in the catalog.</p>
                    </div>

                    <div x-show="target === 'specific'" x-cloak class="space-y-4 animate-in fade-in slide-in-from-top-2 duration-300">
                        <label class="saas-label font-bold">Select Products for this Campaign</label>
                        <div class="max-h-[300px] overflow-y-auto pr-2 grid grid-cols-1 md:grid-cols-2 gap-3" id="product-list">
                            @foreach($products as $product)
                                <label class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer group">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="w-5 h-5 rounded-lg border-2 border-slate-200 text-orange-500 focus:ring-orange-500 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-white p-1 shadow-sm"><img src="{{ \App\Helpers\ImageHelper::getUrl($product->image) }}" class="h-full w-full object-contain"></div>
                                        <span class="text-xs font-bold text-slate-600 group-hover:text-slate-900">{{ $product->title }}</span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Scheduling -->
            <div class="lg:col-span-4 space-y-8">
                <div class="saas-card">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-orange-600 flex items-center justify-center text-white"><i data-lucide="calendar" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-slate-900">Validity Timeline</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="saas-label">Start Activation</label>
                            <input type="date" name="start_date" class="saas-input" value="{{ date('Y-m-d') }}">
                        </div>
                        <div>
                            <label class="saas-label">End Expiration</label>
                            <input type="date" name="end_date" class="saas-input">
                        </div>
                    </div>
                    <div class="mt-8 p-4 bg-orange-50 rounded-2xl border border-orange-100">
                        <div class="flex gap-3">
                            <i data-lucide="info" class="w-4 h-4 text-orange-500 shrink-0"></i>
                            <p class="text-[10px] text-orange-600 font-medium leading-relaxed">Leave expiration blank for a permanent campaign. Codes automatically deactivate at midnight of the end date.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.querySelector('select[name="type"]').addEventListener('change', function(e) {
    document.getElementById('value-suffix').innerText = e.target.value === 'percentage' ? '%' : '₹';
});
</script>
@endpush
@endsection
