<!-- Top promo bar (NOT sticky) -->
<div class="bg-[var(--secondary)] text-white">
    <div class="mx-auto flex max-w-[1600px] items-center px-4 py-3 text-sm sm:px-6 lg:px-12">
        <div class="marquee flex-1">
            <div class="marquee__track">
                <span class="marquee__item font-semibold">Get our Exclusive Best Sellers!</span>
                <span class="marquee__sep">•</span>
                <span class="marquee__item font-semibold">Free delivery over ₹999</span>
                <span class="marquee__sep">•</span>
                <span class="marquee__item font-semibold">New arrivals every week</span>
                <span class="marquee__sep">•</span>
                <span class="marquee__item font-semibold">Get our Exclusive Best Sellers!</span>
                <span class="marquee__sep">•</span>
                <span class="marquee__item font-semibold">Free delivery over ₹999</span>
                <span class="marquee__sep">•</span>
                <span class="marquee__item font-semibold">New arrivals every week</span>
            </div>
        </div>
    </div>
</div>

<!-- Main header (sticky) -->
<header class="public-header public-header-main brand-gradient sticky top-0 z-50" data-public-header>
    <div class="mx-auto max-w-[1600px] px-4 py-4 sm:px-6 lg:px-12">
        <!-- Mobile header -->
        <div class="sm:hidden">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button"
                        class="header-btn inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                        aria-label="Open menu" data-sidebar-open aria-expanded="false">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>

                    <a href="/" class="flex items-center px-1 py-1 text-white">
                        <img src="{{ asset('images/logo/remenant-health-logo.png') }}"
                            alt="{{ config('app.name', 'Remenant Health') }} logo" class="h-10 w-auto object-contain">
                    </a>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('cart') }}"
                        class="header-btn inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                        aria-label="Cart">
                        <i data-lucide="shopping-cart" class="h-6 w-6"></i>
                    </a>

                    <details class="relative" data-account-dropdown>
                        <summary
                            class="header-btn list-none inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                            aria-label="Account">
                            <i data-lucide="user" class="h-6 w-6"></i>
                        </summary>

                        <div
                            class="absolute right-0 mt-2 w-48 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-black/10">
                            <div class="px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Account</p>
                                <p class="text-sm font-extrabold text-gray-900">
                                    {{ config('app.name', 'Remenant Health') }}
                                </p>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <div class="p-2">
                                @auth
                                    <a href="#wishlist"
                                        class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                        <i data-lucide="heart" class="h-4 w-4"></i>
                                        Wishlist
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="log-out" class="h-4 w-4"></i>
                                            Logout
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                        <i data-lucide="log-in" class="h-4 w-4"></i>
                                        Login
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="mt-1 flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="user-plus" class="h-4 w-4"></i>
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </details>
                </div>
            </div>

            <!-- Mobile search bar -->
            <div class="public-mobile-search mt-4" data-mobile-search>
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-white/80">
                        <i data-lucide="search" class="h-5 w-5"></i>
                    </span>
                    <input type="search" placeholder="Search for products…"
                        class="w-full rounded-full border border-white/30 bg-white/10 py-3 pl-11 pr-4 text-white placeholder:text-white/60 outline-none transition-all duration-300 focus:bg-white/20 focus:ring-0 focus:border-white/50 focus:shadow-[0_0_20px_rgba(255,255,255,0.1)]">
                </div>
            </div>
        </div>

        <!-- Desktop header -->
        <div class="hidden items-center gap-3 sm:flex">
            <!-- Menu -->
            <button type="button"
                class="header-btn inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                aria-label="Open menu" data-sidebar-open aria-expanded="false">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>

            <!-- Logo -->
            <a href="/" class="flex items-center px-1 py-1 text-white">
                <img src="{{ asset('images/logo/remenant-health-logo.png') }}"
                    alt="{{ config('app.name', 'Remenant Health') }} logo" class="h-12 w-auto object-contain">
            </a>

            <!-- Search -->
            <div class="flex-1">
                <div class="relative max-w-[520px]">
                    <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-white/80">
                        <i data-lucide="search" class="h-5 w-5"></i>
                    </span>
                    <input type="search" placeholder="Search for products…"
                        class="w-full rounded-full border border-white/30 bg-white/10 py-2 pl-10 pr-4 text-white placeholder:text-white/60 outline-none transition-all duration-300 focus:max-w-[580px] focus:bg-white/20 focus:ring-0 focus:border-white/50 focus:shadow-[0_0_20px_rgba(255,255,255,0.1)]">
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2">
                <a href="{{ route('cart') }}"
                    class="header-btn inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                    aria-label="Cart">
                    <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                </a>

                @auth
                    <a href="{{ route('dashboard') }}"
                        class="hidden rounded-full bg-white px-4 py-2 text-sm font-extrabold text-[color:var(--primary)] shadow-sm ring-1 ring-white/40 hover:bg-white/90 transition lg:inline-flex">
                        Dashboard
                    </a>
                @else
                    <details class="relative" data-account-dropdown>
                        <summary
                            class="header-btn list-none inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                            aria-label="Account">
                            <i data-lucide="user" class="h-5 w-5"></i>
                        </summary>

                        <div
                            class="absolute right-0 mt-2 w-48 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-black/10">
                            <div class="px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">Account</p>
                                <p class="text-sm font-extrabold text-gray-900">{{ config('app.name', 'Remenant Health') }}
                                </p>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <div class="p-2">
                                @auth
                                    <a href="#wishlist"
                                        class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                        <i data-lucide="heart" class="h-4 w-4"></i>
                                        Wishlist
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="log-out" class="h-4 w-4"></i>
                                            Logout
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                        <i data-lucide="log-in" class="h-4 w-4"></i>
                                        Login
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="mt-1 flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="user-plus" class="h-4 w-4"></i>
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </details>

                    @if (Route::has('register'))
                        <a href="{{ route('products.index') }}"
                            class="hidden rounded-full bg-white px-4 py-2 text-sm font-extrabold text-[color:var(--primary)] shadow-sm ring-1 ring-white/40 hover:bg-white/90 transition lg:inline-flex">
                            Shop All
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
</header>

<style>
    /* Reinforce sticky behavior */
    [data-public-header] {
        position: -webkit-sticky !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 100 !important;
        width: 100% !important;
        display: block !important;
    }

    /* Ensure parents don't break sticky */
    html,
    body {
        overflow-x: visible !important;
    }
</style>