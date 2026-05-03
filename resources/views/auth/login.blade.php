@extends('public.layouts.app')

@section('title', 'Login - ' . config('app.name', 'Remenant Health'))

@section('content')
<div class="bg-[var(--bg-main)] flex flex-col lg:flex-row lg:h-[calc(100vh-100px)] lg:min-h-[600px]">
    
    <!-- Left Side: Full Bleed Image (Desktop Only) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center p-12 text-center">
        <img src="{{ asset('images/products/remenant-product10.jpg') }}" alt="Wellness" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10">
            <h2 class="text-6xl font-black italic tracking-tighter text-white uppercase leading-none mb-6">Elevate<br>Your Life.</h2>
            <div class="h-1.5 w-16 bg-[color:var(--primary)] mx-auto rounded-full"></div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="flex-1 flex items-start justify-center p-6 lg:pt-32 relative">
        <div class="w-full max-w-[440px]">
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-100 flex items-center gap-3">
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-500"></i>
                    <p class="text-[11px] font-bold text-green-700 uppercase tracking-wider">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full rounded-2xl bg-gray-50 border-2 border-transparent px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:bg-white focus:ring-0 focus:border-[color:var(--primary)] focus:outline-none transition-all placeholder:text-gray-300"
                           placeholder="your@email.com">
                    @error('email')
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between px-1">
                        <label for="password" class="text-[10px] font-black uppercase tracking-widest text-gray-400">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[9px] font-black uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] transition">Forgot?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="w-full rounded-2xl bg-gray-50 border-2 border-transparent px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:bg-white focus:ring-0 focus:border-[color:var(--primary)] focus:outline-none transition-all placeholder:text-gray-300"
                           placeholder="••••••••">
                    @error('password')
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center px-1">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-200 text-[color:var(--primary)] focus:ring-[color:var(--primary)] transition">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400 group-hover:text-gray-600 transition">Keep me signed in</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full rounded-2xl bg-[color:var(--text-primary)] py-5 text-xs font-black text-white uppercase tracking-[0.2em] shadow-2xl shadow-black/20 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                        Enter Account
                        <i data-lucide="arrow-right" class="h-4 w-4 transition-transform group-hover:translate-x-1"></i>
                    </button>
                </div>

                <!-- Register Redirect -->
                <div class="pt-6 text-center">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">
                        No account? 
                        <a href="{{ route('register') }}" class="text-[color:var(--primary)] hover:underline underline-offset-4">Create One</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
