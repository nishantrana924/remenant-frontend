@extends('public.layouts.app')

@section('title', 'Reset Password - ' . config('app.name', 'Remenant Health'))

@section('content')
<div class="bg-[var(--bg-main)] flex flex-col lg:flex-row lg:h-[calc(100vh-100px)] lg:min-h-[720px]">
    
    <!-- Left Side: Full Bleed Image (Desktop Only) -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden items-center justify-center p-12 text-center">
        <img src="{{ asset('images/products/remenant-product13.jpg') }}" alt="Security" class="absolute inset-0 h-full w-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative z-10">
            <h2 class="text-6xl font-black italic tracking-tighter text-white uppercase leading-none mb-6">Security<br>First.</h2>
            <div class="h-1.5 w-16 bg-[color:var(--primary)] mx-auto rounded-full"></div>
        </div>
    </div>

    <!-- Right Side: Reset Password Form -->
    <div class="flex-1 flex items-start justify-center p-6 lg:pt-16 relative">
        <div class="w-full max-w-[520px]">
            
            <div class="mb-10 text-center lg:text-left">
                <p class="text-[10px] font-black uppercase tracking-[0.25em] text-[color:var(--primary)] mb-3">Authentication</p>
                <h1 class="text-4xl font-black italic tracking-tighter text-[color:var(--text-primary)] uppercase leading-none mb-4">Reset Password</h1>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest leading-relaxed">Choose a new, strong password to secure your premium account.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                           class="w-full rounded-2xl bg-gray-50 border-2 border-transparent px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:bg-white focus:ring-0 focus:border-[color:var(--primary)] focus:outline-none transition-all placeholder:text-gray-300"
                           placeholder="Enter your mail">
                    @error('email')
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Password -->
                    <div class="space-y-2" x-data="{ show: false }">
                        <label for="password" class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">New Password</label>
                        <div class="relative group">
                            <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                                   class="w-full rounded-2xl bg-gray-50 border-2 border-transparent px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:bg-white focus:ring-0 focus:border-[color:var(--primary)] focus:outline-none transition-all placeholder:text-gray-300 shadow-inner pr-14"
                                   placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[color:var(--primary)] transition-colors">
                                <i :data-lucide="show ? 'eye-off' : 'eye'" class="h-4 w-4"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-[10px] font-bold text-red-500 uppercase tracking-widest ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2" x-data="{ show: false }">
                        <label for="password_confirmation" class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-1">Confirm</label>
                        <div class="relative group">
                            <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                                   class="w-full rounded-2xl bg-gray-50 border-2 border-transparent px-6 py-4 text-sm font-bold text-[color:var(--text-primary)] focus:bg-white focus:ring-0 focus:border-[color:var(--primary)] focus:outline-none transition-all placeholder:text-gray-300 shadow-inner pr-14"
                                   placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[color:var(--primary)] transition-colors">
                                <i :data-lucide="show ? 'eye-off' : 'eye'" class="h-4 w-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full rounded-2xl bg-[color:var(--text-primary)] py-5 text-xs font-black text-white uppercase tracking-[0.2em] shadow-2xl shadow-black/20 hover:brightness-110 active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                        Update Password
                        <i data-lucide="shield-check" class="h-4 w-4 transition-transform group-hover:scale-110"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
