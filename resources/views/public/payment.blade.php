@extends('public.layouts.app')

@section('title', 'Secure Payment - Remenant')

@section('content')
<div class="min-h-screen bg-[#FDFCFB] py-20 px-4">
    <div class="max-w-xl mx-auto">
        <!-- Brand Header -->
        <div class="text-center mb-12">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-[2rem] bg-orange-500 text-white shadow-2xl shadow-orange-200 mb-6">
                <i data-lucide="shield-check" class="h-8 w-8"></i>
            </div>
            <h1 class="text-3xl font-black italic tracking-tighter uppercase text-slate-900">Secure Payment</h1>
            <p class="text-slate-400 font-bold uppercase tracking-[0.3em] text-[10px] mt-2">Remenant Intelligence Pay</p>
        </div>

        <!-- Payment Card -->
        <div class="bg-white rounded-[3rem] p-8 sm:p-12 shadow-2xl shadow-orange-100 ring-1 ring-black/[0.03] relative overflow-hidden">
            <!-- Order Details -->
            <div class="flex justify-between items-center mb-10 pb-8 border-b border-dashed border-slate-200">
                <div>
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Order Number</span>
                    <span class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ $order->order_number }}</span>
                </div>
                <div class="text-right">
                    <span class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Amount to Pay</span>
                    <span class="text-2xl font-black text-orange-500">₹{{ number_format($order->total_amount) }}</span>
                </div>
            </div>

            <!-- Simulation Steps -->
            <div x-data="{ step: 'options', processing: false }" class="space-y-8">
                <!-- Payment Options -->
                <div x-show="step === 'options'" class="space-y-4">
                    <button @click="processing = true; setTimeout(() => { step = 'processing' }, 1000)" class="w-full group flex items-center justify-between p-6 rounded-2xl bg-slate-50 border-2 border-transparent hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition-colors shadow-sm">
                                <i data-lucide="smartphone" class="h-6 w-6"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-black text-slate-900 uppercase">UPI / QR</span>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">PhonePe, Google Pay, Paytm</span>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="h-5 w-5 text-slate-300 group-hover:text-orange-500 transition-all"></i>
                    </button>

                    <button @click="processing = true; setTimeout(() => { step = 'processing' }, 1000)" class="w-full group flex items-center justify-between p-6 rounded-2xl bg-slate-50 border-2 border-transparent hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition-colors shadow-sm">
                                <i data-lucide="credit-card" class="h-6 w-6"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-black text-slate-900 uppercase">Credit / Debit Card</span>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">Visa, Mastercard, RuPay</span>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="h-5 w-5 text-slate-300 group-hover:text-orange-500 transition-all"></i>
                    </button>

                    <button @click="processing = true; setTimeout(() => { step = 'processing' }, 1000)" class="w-full group flex items-center justify-between p-6 rounded-2xl bg-slate-50 border-2 border-transparent hover:border-orange-500 hover:bg-orange-50 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 rounded-xl bg-white flex items-center justify-center text-slate-400 group-hover:text-orange-500 transition-colors shadow-sm">
                                <i data-lucide="building-2" class="h-6 w-6"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-black text-slate-900 uppercase">Net Banking</span>
                                <span class="block text-[10px] font-bold text-slate-400 uppercase">All Indian Banks</span>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="h-5 w-5 text-slate-300 group-hover:text-orange-500 transition-all"></i>
                    </button>
                </div>

                <!-- Processing Simulation -->
                <div x-show="step === 'processing'" class="text-center py-10" x-init="$watch('step', value => { if(value === 'processing') { setTimeout(() => { step = 'success' }, 3000) } })">
                    <div class="relative inline-block mb-8">
                        <div class="h-24 w-24 rounded-[2.5rem] border-4 border-orange-100 animate-[spin_3s_linear_infinite]"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i data-lucide="lock" class="h-8 w-8 text-orange-500 animate-pulse"></i>
                        </div>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight">Verifying with Bank...</h3>
                    <p class="text-sm font-bold text-slate-400 mt-2">Please do not refresh the page or press back button.</p>
                </div>

                <!-- Success State -->
                <div x-show="step === 'success'" class="text-center py-6 animate-[modalSlideUp_0.5s_ease-out]">
                    <div class="h-20 w-20 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto mb-8 shadow-2xl shadow-emerald-200">
                        <i data-lucide="check" class="h-10 w-10"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter italic">Payment Successful!</h3>
                    <p class="text-slate-400 font-bold mt-2 uppercase tracking-widest text-xs">Your order has been confirmed.</p>
                    
                    <div class="mt-12">
                        <a href="{{ route('checkout.success', ['order' => $order->order_number]) }}" class="inline-flex items-center justify-center px-12 py-4 rounded-2xl bg-slate-900 text-white font-black uppercase tracking-[0.2em] text-xs shadow-2xl hover:brightness-110 active:scale-95 transition-all">
                            View Order Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust Signals -->
        <div class="mt-12 flex items-center justify-center gap-12 opacity-40 grayscale">
            <img src="{{ asset('images/icons/visa.png') }}" alt="Visa" class="h-4 object-contain">
            <img src="{{ asset('images/icons/mastercard.png') }}" alt="Mastercard" class="h-6 object-contain">
            <img src="{{ asset('images/icons/pci.png') }}" alt="PCI" class="h-8 object-contain">
        </div>
    </div>
</div>

<style>
    @keyframes modalSlideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
