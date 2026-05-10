@extends('admin.layouts.app')

@section('content')
<div class="pb-24">
    <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST" id="coupon-form">
        @csrf
        @method('PUT')
        
        <!-- Header (Compact) -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.coupons.index') }}" class="h-10 w-10 rounded-xl border border-slate-200 flex items-center justify-center text-slate-400 hover:text-orange-500 hover:border-orange-200 transition-all bg-white shadow-sm">
                    <i data-lucide="chevron-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <h1 class="text-xl font-black text-slate-900 tracking-tight">Optimize Incentive</h1>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-0.5">Remenant Engine • Compact Classic</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="saas-btn-primary py-2 px-6 shadow-xl shadow-orange-200/40">
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Sync Changes
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
                            <input type="text" name="code" class="saas-input font-black text-lg uppercase tracking-widest text-orange-600" value="{{ $coupon->code }}" required>
                        </div>
                        <div>
                            <label class="saas-label font-bold">Incentive Status</label>
                            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-2xl border border-slate-100 mt-1">
                                <span class="text-xs font-bold text-slate-600">Currently Active</span>
                                <input type="checkbox" name="is_active" value="1" {{ $coupon->is_active ? 'checked' : '' }} class="w-6 h-6 rounded-lg border-2 border-slate-200 text-orange-500 focus:ring-orange-500">
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
                                <option value="percentage" {{ $coupon->type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                <option value="fixed" {{ $coupon->type === 'fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                            </select>
                        </div>
                        <div>
                            <label class="saas-label font-bold">Value</label>
                            <div class="relative">
                                <input type="number" name="value" class="saas-input font-black pr-12" value="{{ $coupon->value }}" required>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs" id="value-suffix">{{ $coupon->type === 'percentage' ? '%' : '₹' }}</div>
                            </div>
                        </div>
                        <div>
                            <label class="saas-label font-bold">Min. Purchase Amount (₹)</label>
                            <input type="number" name="min_order_amount" class="saas-input font-bold" value="{{ $coupon->min_order_amount }}">
                        </div>
                        <div>
                            <label class="saas-label font-bold">Usage Limit</label>
                            <input type="number" name="usage_limit" class="saas-input font-bold" value="{{ $coupon->usage_limit }}">
                        </div>
                    </div>
                </div>

                <!-- 3. Target Distribution -->
                <div class="saas-card" x-data="{ target: '{{ count($coupon->product_ids ?? []) > 0 ? 'specific' : 'all' }}' }">
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
                    </div>

                    <div x-show="target === 'specific'" x-cloak class="space-y-4">
                        <label class="saas-label font-bold">Manage Restricted Products</label>
                        <div class="max-h-[300px] overflow-y-auto pr-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($products as $product)
                                <label class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer group">
                                    <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" 
                                           {{ in_array($product->id, $coupon->product_ids ?? []) ? 'checked' : '' }}
                                           class="w-5 h-5 rounded-lg border-2 border-slate-200 text-orange-500 focus:ring-orange-500 transition-all">
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

            <!-- Right Side -->
            <div class="lg:col-span-4 space-y-8">
                <div class="saas-card">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="h-10 w-10 rounded-xl bg-orange-600 flex items-center justify-center text-white"><i data-lucide="calendar" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-slate-900">Validity Timeline</h3>
                    </div>
                    <div class="space-y-6">
                        <div>
                            <label class="saas-label">Start Activation</label>
                            <input type="date" name="start_date" class="saas-input" value="{{ $coupon->start_date ? $coupon->start_date->format('Y-m-d') : '' }}">
                        </div>
                        <div>
                            <label class="saas-label">End Expiration</label>
                            <input type="date" name="end_date" class="saas-input" value="{{ $coupon->end_date ? $coupon->end_date->format('Y-m-d') : '' }}">
                        </div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="saas-card bg-slate-900 text-white border-0 shadow-xl shadow-slate-200">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-10 w-10 rounded-xl bg-white/10 flex items-center justify-center text-orange-400"><i data-lucide="bar-chart-3" class="w-5 h-5"></i></div>
                        <h3 class="text-base font-bold text-white">Live Performance</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Times Used</span>
                            <span class="text-sm font-black">{{ $coupon->used_count }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-400">Remaining</span>
                            <span class="text-sm font-black">{{ $coupon->usage_limit ? ($coupon->usage_limit - $coupon->used_count) : '∞' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
