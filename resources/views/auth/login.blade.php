@extends('public.layouts.app')

@section('title', 'Login - Remenant Health')

@section('content')
    <div class="relative py-24 sm:py-32">
        <!-- Dynamic Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-[10%] -left-[10%] h-[40%] w-[40%] rounded-full bg-[color:var(--primary)]/5 blur-[120px]"></div>
            <div class="absolute top-[20%] -right-[5%] h-[35%] w-[35%] rounded-full bg-[color:var(--secondary)]/5 blur-[100px]"></div>
            <div class="absolute -bottom-[10%] left-[20%] h-[30%] w-[30%] rounded-full bg-[color:var(--primary)]/5 blur-[80px]"></div>
        </div>

        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="mx-auto w-full max-w-[520px]">
                <!-- Main Card -->
                <div class="overflow-hidden rounded-[3rem] bg-white p-8 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)] ring-1 ring-black/5 sm:p-16">
                    <div class="relative">
                        <!-- Session Status -->
                        @if (session('status'))
                            <div class="mb-6 rounded-2xl bg-green-50 p-4 ring-1 ring-green-100 transition-all">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="check-circle" class="h-5 w-5 text-green-500"></i>
                                    <p class="text-sm font-bold text-green-700">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="mb-12 text-center">
                            <h1 class="text-4xl font-black italic tracking-tight text-[color:var(--text-primary)] sm:text-5xl">Welcome Back</h1>
                            <p class="mt-4 text-xs font-black text-[color:var(--text-secondary)] uppercase tracking-[0.2em]">Sign in to your wellness journey</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="space-y-8">
                            @csrf

                            <!-- Email Address -->
                            <div class="space-y-3">
                                <label for="email" class="text-xs font-black uppercase tracking-[0.2em] text-gray-400 ml-1">
                                    Email Address
                                </label>
                                <div class="group relative">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none transition-colors group-focus-within:text-[color:var(--primary)] text-gray-400">
                                        <i data-lucide="mail" class="h-5 w-5"></i>
                                    </div>
                                    <input id="email" 
                                           class="block w-full pl-14 pr-6 py-5 rounded-[2rem] bg-gray-50 border-none ring-1 ring-black/5 focus:ring-2 focus:ring-[color:var(--primary)] focus:bg-white transition-all font-bold text-gray-900 placeholder:text-gray-400 @error('email') ring-red-500 @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required 
                                           autofocus 
                                           autocomplete="username"
                                           placeholder="name@example.com">
                                </div>
                                @error('email')
                                    <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-red-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="space-y-3">
                                <div class="flex items-center justify-between px-1">
                                    <label for="password" class="text-xs font-black uppercase tracking-[0.2em] text-gray-400">
                                        Password
                                    </label>
                                    @if (Route::has('password.request'))
                                        <a class="text-[10px] font-black uppercase tracking-widest text-[color:var(--primary)] hover:brightness-90 transition" href="{{ route('password.request') }}">
                                            Forgot?
                                        </a>
                                    @endif
                                </div>
                                <div class="group relative" x-data="{ show: false }">
                                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none transition-colors group-focus-within:text-[color:var(--primary)] text-gray-400">
                                        <i data-lucide="lock" class="h-5 w-5"></i>
                                    </div>
                                    <input id="password" 
                                           class="block w-full pl-14 pr-16 py-5 rounded-[2rem] bg-gray-50 border-none ring-1 ring-black/5 focus:ring-2 focus:ring-[color:var(--primary)] focus:bg-white transition-all font-bold text-gray-900 placeholder:text-gray-400 @error('password') ring-red-500 @enderror" 
                                           :type="show ? 'text' : 'password'"
                                           name="password" 
                                           required 
                                           autocomplete="current-password"
                                           placeholder="••••••••">
                                    <button type="button" 
                                            @click="show = !show"
                                            class="absolute inset-y-0 right-0 pr-6 flex items-center text-gray-400 hover:text-[color:var(--primary)] transition">
                                        <i x-show="!show" data-lucide="eye" class="h-5 w-5"></i>
                                        <i x-show="show" data-lucide="eye-off" class="h-5 w-5" style="display: none;"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-[10px] font-black uppercase tracking-widest text-red-500 ml-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center px-1">
                                <label for="remember_me" class="relative flex items-center cursor-pointer group">
                                    <input id="remember_me" 
                                           type="checkbox" 
                                           class="peer h-5 w-5 rounded-lg border-gray-200 text-[color:var(--primary)] focus:ring-[color:var(--primary)] transition" 
                                           name="remember">
                                    <span class="ml-3 text-xs font-black uppercase tracking-widest text-gray-500 group-hover:text-gray-700 transition">
                                        Keep me signed in
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full group relative overflow-hidden rounded-[2rem] bg-[color:var(--primary)] py-6 text-sm font-black uppercase tracking-[0.2em] text-white shadow-xl transition-all hover:brightness-105 active:scale-[0.98]">
                                <span class="relative z-10 flex items-center justify-center gap-3">
                                    Sign In
                                    <i data-lucide="arrow-right" class="h-5 w-5 transition-transform group-hover:translate-x-1"></i>
                                </span>
                            </button>

                            <!-- Social Login -->
                            <div class="relative py-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-100"></div>
                                </div>
                                <div class="relative flex justify-center text-[10px] font-black uppercase tracking-widest">
                                    <span class="bg-white px-4 text-gray-400">Or continue with</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <button type="button" class="flex items-center justify-center gap-3 rounded-[1.5rem] bg-gray-50 py-5 ring-1 ring-black/5 hover:bg-gray-100 transition active:scale-95">
                                    <i data-lucide="chrome" class="h-4 w-4"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Google</span>
                                </button>
                                <button type="button" class="flex items-center justify-center gap-3 rounded-[1.5rem] bg-gray-50 py-5 ring-1 ring-black/5 hover:bg-gray-100 transition active:scale-95">
                                    <i data-lucide="facebook" class="h-4 w-4"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Facebook</span>
                                </button>
                            </div>

                            <!-- Register Link -->
                            <div class="pt-6 text-center">
                                <p class="text-[11px] font-black uppercase tracking-widest text-gray-400">
                                    New to Remenant?
                                    <a href="{{ route('register') }}" class="text-[color:var(--primary)] hover:brightness-90 transition">
                                        Create Account
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Footer Links -->
                <div class="mt-12 flex items-center justify-center gap-8">
                    <a href="/" class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 transition hover:text-[color:var(--primary)]">
                        <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1"></i>
                        Back to Home
                    </a>
                    <span class="h-1 w-1 rounded-full bg-gray-200"></span>
                    <a href="{{ route('about') }}" class="text-xs font-black uppercase tracking-widest text-gray-400 transition hover:text-[color:var(--primary)]">
                        Support Center
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
