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
            <div class="rounded-2xl bg-[var(--primary-soft)] p-4">
                <p class="text-xs font-bold uppercase tracking-wide text-[color:var(--primary)]">Deals</p>
                <p class="mt-1 text-sm font-extrabold text-gray-900">Exclusive best sellers this week</p>
                <a href="#shop"
                    class="mt-3 inline-flex rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-extrabold text-white hover:opacity-95 transition"
                    data-sidebar-initial-focus>
                    Shop now
                </a>
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
                    Shop
                </a>
                <a href="#categories"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-bold text-gray-900 hover:bg-black/5 transition">
                    <i data-lucide="grid-3x3" class="h-5 w-5 text-gray-600"></i>
                    Categories
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