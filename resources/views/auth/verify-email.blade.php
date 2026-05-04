@extends('public.layouts.app')

@section('title', 'Verify Email - ' . config('app.name', 'Remenant Health'))

@section('content')
<div class="bg-[var(--bg-main)] flex flex-col lg:flex-row lg:h-[calc(100vh-100px)] lg:min-h-[600px]">
    
    <!-- Left Side: Full Bleed Image (Desktop Only) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center p-12 text-center">
        <img src="{{ asset('images/products/remenant-product15.jpg') }}" alt="Welcome" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10">
            <h2 class="text-6xl font-black italic tracking-tighter text-white uppercase leading-none mb-6">Welcome<br>Aboard.</h2>
            <div class="h-1.5 w-16 bg-[color:var(--primary)] mx-auto rounded-full"></div>
        </div>
    </div>

    <!-- Right Side: Verify Email Content -->
    <div class="flex-1 flex items-start justify-center p-6 lg:pt-32 relative">
        <div class="w-full max-w-[440px]">
            
            <div class="mb-10 text-center lg:text-left">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-[color:var(--primary)] mb-3">Verification</p>
                <h1 class="text-4xl font-black italic tracking-tighter text-[color:var(--text-primary)] uppercase leading-none mb-6">Verify Your Email</h1>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">
                    Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-100 flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                    <p class="text-[11px] font-bold text-green-700 uppercase tracking-wider">
                        A new verification link has been sent to your email address.
                    </p>
                </div>
            @endif

            <div class="space-y-6">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl bg-[color:var(--text-primary)] py-5 text-xs font-black text-white uppercase tracking-[0.2em] shadow-2xl shadow-black/20 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                        Resend Email
                        <i data-lucide="refresh-cw" class="h-4 w-4 transition-transform group-hover:rotate-180 duration-500"></i>
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] transition-colors underline underline-offset-4">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
