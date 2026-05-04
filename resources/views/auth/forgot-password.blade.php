@extends('public.layouts.app')

@section('title', 'Forgot Password - ' . config('app.name', 'Remenant Health'))

@section('content')
<div class="bg-[var(--bg-main)] flex flex-col lg:flex-row lg:h-[calc(100vh-100px)] lg:min-h-[600px]">
    
    <!-- Left Side: Full Bleed Image (Desktop Only) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center p-12 text-center">
        <img src="{{ asset('images/products/remenant-product12.jpg') }}" alt="Recovery" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10">
            <h2 class="text-6xl font-black italic tracking-tighter text-white uppercase leading-none mb-6">Recover<br>Your Path.</h2>
            <div class="h-1.5 w-16 bg-[color:var(--primary)] mx-auto rounded-full"></div>
        </div>
    </div>

    <!-- Right Side: Forgot Password Form -->
    <div class="flex-1 flex items-start justify-center p-6 lg:pt-32 relative">
        <div class="w-full max-w-[440px]">
            
            <div class="mb-10">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-[color:var(--primary)] mb-3">Security</p>
                <h1 class="text-4xl font-black italic tracking-tighter text-[color:var(--text-primary)] uppercase leading-none mb-6">Password Recovery</h1>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-100 flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                    <p class="text-[11px] font-bold text-green-700 uppercase tracking-wider">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-2xl bg-gray-50 border-2 border-transparent px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:bg-white focus:ring-0 focus:border-[color:var(--primary)] focus:outline-none transition-all placeholder:text-gray-300"
                           placeholder="Enter Your mail">
                    @error('email')
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full rounded-2xl bg-[color:var(--text-primary)] py-5 text-xs font-black text-white uppercase tracking-[0.2em] shadow-2xl shadow-black/20 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                        Send Reset Link
                        <i data-lucide="mail" class="h-4 w-4 transition-transform group-hover:scale-110"></i>
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="pt-6 text-center">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        Remembered? 
                        <a href="{{ route('login') }}" class="text-[color:var(--primary)] hover:underline underline-offset-4">Back to Sign In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
