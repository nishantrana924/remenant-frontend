@extends('admin.layouts.app')

@section('header')
    <h2 class="font-bold text-xl text-slate-900 leading-tight">Shipping Settings</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto pb-12">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight uppercase">Shipping Configuration</h1>
            <div class="flex items-center gap-2 mt-1">
                <span class="h-1.5 w-1.5 rounded-full bg-orange-500"></span>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Shipping Charge • Free Shipping Threshold</p>
            </div>
        </div>
        <a href="{{ url()->previous() }}" class="saas-btn-secondary py-2 px-4 text-xs font-bold flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 px-5 py-4 bg-emerald-50 border border-emerald-100 rounded-2xl">
        <i data-lucide="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
        <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
    </div>
    @endif

    <form action="{{ route('admin.settings.shipping.update') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Main Settings Card --}}
        <div class="saas-card p-8 space-y-8">
            <div class="flex items-center gap-3 pb-5 border-b border-slate-100">
                <div class="h-9 w-9 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
                <div>

                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Delivery Charges</h3>
                    <p class="text-[9px] font-medium text-slate-400 uppercase tracking-widest mt-0.5">Configure when to charge shipping and how much</p>
                </div>
            </div>

            {{-- Two Inputs Side by Side --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- Shipping Charge --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Shipping Charge (₹)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-slate-400">₹</span>
                        <input
                            type="number"
                            name="shipping_charge"
                            id="shipping_charge"
                            value="{{ $settings['shipping_charge'] }}"
                            min="0"
                            step="1"
                            oninput="updatePreview()"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-8 pr-4 py-3.5 text-lg font-black text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all"
                            placeholder="99"
                        >
                    </div>
                    <p class="text-[9px] text-slate-400 font-medium">Amount charged when order is below the free shipping threshold.</p>
                </div>

                {{-- Free Shipping Threshold --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Free Shipping Above (₹)
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-black text-slate-400">₹</span>
                        <input
                            type="number"
                            name="free_shipping_threshold"
                            id="free_shipping_threshold"
                            value="{{ $settings['free_shipping_threshold'] }}"
                            min="0"
                            step="1"
                            oninput="updatePreview()"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl pl-8 pr-4 py-3.5 text-lg font-black text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all"
                            placeholder="449"
                        >
                    </div>
                    <p class="text-[9px] text-slate-400 font-medium">Orders above this amount get free delivery.</p>
                </div>
            </div>

            {{-- Live Preview --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl p-6 mt-4">
                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-4">Live Preview — How it appears to customers</p>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 rounded-xl p-4 border border-white/10">
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mb-2">Order below threshold</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-300">Shipping</span>
                            <span id="preview-charge" class="text-sm font-black text-orange-400">₹{{ $settings['shipping_charge'] }}</span>
                        </div>
                    </div>
                    <div class="bg-emerald-500/10 rounded-xl p-4 border border-emerald-500/20">
                        <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest mb-2">Order above threshold</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-300">Shipping</span>
                            <span class="text-sm font-black text-emerald-400">FREE 🎉</span>
                        </div>
                    </div>
                </div>
                <p id="preview-rule" class="text-center text-[10px] font-bold text-slate-400 mt-4">
                    Orders above ₹{{ $settings['free_shipping_threshold'] }} get free delivery. Others pay ₹{{ $settings['shipping_charge'] }}.
                </p>
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex justify-end">
            <button type="submit" class="saas-btn-primary py-3.5 px-14 text-sm font-bold flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i>
                Save Shipping Settings
            </button>
        </div>
    </form>
</div>

<script>
function updatePreview() {
    const charge = document.getElementById('shipping_charge').value || 0;
    const threshold = document.getElementById('free_shipping_threshold').value || 0;
    document.getElementById('preview-charge').textContent = '₹' + charge;
    document.getElementById('preview-rule').textContent =
        `Orders above ₹${threshold} get free delivery. Others pay ₹${charge}.`;
}
</script>
@endsection
