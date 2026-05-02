<!-- Sidebar overlay -->
<div class="fixed inset-0 z-[130] hidden bg-black/50 opacity-0 backdrop-blur-[1px] transition-opacity duration-200"
    data-sidebar-overlay>
    <!-- Sidebar panel -->
    <aside
        class="absolute left-0 top-0 h-full w-[88vw] max-w-[360px] -translate-x-full overflow-y-auto bg-[var(--sidebar-bg)] shadow-2xl transition-transform duration-200 sm:w-[360px]"
        data-sidebar-panel role="dialog" aria-modal="true" aria-label="Sidebar" tabindex="-1">
        <div class="flex items-center justify-between border-b border-black/5 px-5 py-4">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo/remenant-health-logo2.png') }}"
                    alt="{{ config('app.name', 'Remenant Health') }} logo" class="h-9 w-auto object-contain">
            </div>

            <button type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-black/5 hover:bg-black/10 transition"
                data-sidebar-close aria-label="Close sidebar">
                <i data-lucide="x" class="h-5 w-5"></i>
            </button>
        </div>

        <div class="px-5 py-5">
            <div class="rounded-2xl bg-[var(--primary-soft)] p-5 border border-[var(--primary)]/10">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-black uppercase tracking-widest text-[color:var(--primary)]">Special Offer</p>
                    <span class="text-[10px] font-black px-2 py-0.5 rounded bg-[var(--primary)] text-white">DEAL</span>
                </div>
                <p class="text-sm font-bold text-gray-900 leading-snug">Get 10% OFF on your <br>first purchase</p>
                <div class="mt-4 flex items-center gap-2">
                    <div class="flex-1 rounded-xl border-2 border-dashed border-[var(--primary)]/30 bg-white/50 px-3 py-2 text-center">
                        <span class="text-xs font-black tracking-widest text-[color:var(--primary)]">WELCOME10</span>
                    </div>
                    <a href="{{ route('products.index') }}"
                        class="shrink-0 h-10 w-10 flex items-center justify-center rounded-xl bg-[var(--primary)] text-white shadow-lg shadow-[var(--primary)]/20 transition hover:scale-105 active:scale-95"
                        data-sidebar-initial-focus>
                        <i data-lucide="arrow-right" class="h-5 w-5"></i>
                    </a>
                </div>
            </div>

            <nav class="mt-6 space-y-1">
                <a href="/"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 hover:bg-black/5 transition">
                    <i data-lucide="home" class="h-5 w-5 text-gray-600"></i>
                    Home
                </a>
                <a href="{{ route('products.index') }}"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 hover:bg-black/5 transition">
                    <i data-lucide="store" class="h-5 w-5 text-gray-600"></i>
                    Shop All
                </a>
                <a href="{{ route('about') }}"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 hover:bg-black/5 transition">
                    <i data-lucide="info" class="h-5 w-5 text-gray-600"></i>
                    About Us
                </a>
                <a href="{{ route('contact') }}"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 hover:bg-black/5 transition">
                    <i data-lucide="mail" class="h-5 w-5 text-gray-600"></i>
                    Contact Us
                </a>
            </nav>

            <div class="mt-6 border-t border-black/5 pt-5">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center justify-between rounded-2xl bg-black/5 px-4 py-3 text-sm font-extrabold text-gray-900 hover:bg-black/10 transition">
                        <span class="flex items-center gap-3">
                            <i data-lucide="layout-dashboard" class="h-5 w-5 text-gray-600"></i>
                            Dashboard
                        </span>
                        <i data-lucide="chevron-right" class="h-5 w-5 text-gray-600"></i>
                    </a>
                @else
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-black/5 px-4 py-3 text-sm font-extrabold text-gray-900 hover:bg-black/10 transition">
                            Login
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center rounded-2xl bg-[var(--primary)] px-4 py-3 text-sm font-extrabold text-white hover:opacity-95 transition">
                                Register
                            </a>
                        @endif
                    </div>
                @endauth
            </div>
        </div>
    </aside>
</div>