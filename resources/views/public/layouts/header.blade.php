@php
    $isAuthPage = request()->routeIs('login', 'register', 'password.*', 'verification.*');
@endphp

@if (request()->routeIs('home'))
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
@endif

<!-- Main header (sticky) -->
<header class="public-header public-header-main brand-gradient sticky top-0 z-50" data-public-header>
    <div class="mx-auto max-w-[1600px] px-4 py-4 sm:px-6 lg:px-12">
        <!-- Mobile header -->
        <div class="sm:hidden">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <button type="button"
                        class="header-btn inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                        aria-label="Open menu" onclick="togglePublicSidebar(true)" aria-expanded="false">
                        <i data-lucide="menu" class="h-6 w-6"></i>
                    </button>

                    <a href="/" class="flex items-center px-1 py-1 text-white">
                        <img src="{{ \App\Helpers\ImageHelper::getUrl('logo/remenant-health-logo.png', 'images') }}"
                            alt="{{ config('app.name', 'Remenant Health') }} logo" class="h-10 w-auto object-contain">
                    </a>
                </div>

                @if(!$isAuthPage)
                    <div class="flex items-center gap-2">
                        @php $cartCount = count(session('cart', [])); @endphp
                        <a href="{{ route('cart') }}"
                            class="header-btn relative inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                            aria-label="Cart">
                            <i data-lucide="shopping-cart" class="h-6 w-6"></i>
                            <span
                                class="cart-count-badge absolute -top-1 -right-1 {{ $cartCount > 0 ? '' : 'hidden' }} flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-black text-[color:var(--primary)] shadow-sm">
                                {{ $cartCount }}
                            </span>
                        </a>

                        <details class="relative" data-account-dropdown-mobile>
                            <summary
                                class="header-btn list-none inline-flex h-11 w-11 cursor-pointer items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                                aria-label="Account">
                                <i data-lucide="user" class="h-6 w-6"></i>
                            </summary>

                            <div
                                class="absolute right-0 mt-2 w-48 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-black/10">
                                <div class="px-4 py-3">
                                    <p class="text-xs font-semibold text-gray-500">
                                        {{ auth()->check() ? 'Profile Setting' : 'Account' }}
                                    </p>
                                    <p class="text-sm font-extrabold text-gray-900">
                                        {{ auth()->check() ? auth()->user()->name : 'Guest' }}
                                    </p>
                                </div>
                                <div class="h-px bg-gray-100"></div>
                                <div class="p-2">
                                    @auth
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('admin.dashboard') }}" up-follow="false"
                                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                                <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                                                Admin Dashboard
                                            </a>
                                        @else
                                            <a href="{{ route('my-orders') }}"
                                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                                <i data-lucide="package" class="h-4 w-4"></i>
                                                My Orders
                                            </a>
                                            <a href="{{ route('my-orders', ['tab' => 'profile']) }}"
                                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                                <i data-lucide="settings" class="h-4 w-4"></i>
                                                Profile Settings
                                            </a>
                                        @endif

                                        <form method="POST" action="{{ route('logout') }}" class="mt-1" up-follow="false">
                                            @csrf
                                            <button type="submit"
                                                class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                                <i data-lucide="log-out" class="h-4 w-4"></i>
                                                Logout
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}"
                                            up-follow="false"
                                            class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="log-in" class="h-4 w-4"></i>
                                            Login
                                        </a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}"
                                                up-follow="false"
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
                @endif
            </div>

        </div>

        <!-- Desktop header -->
        <div class="hidden items-center gap-3 sm:flex">
            <!-- Menu -->
            <button type="button"
                class="header-btn inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                aria-label="Open menu" onclick="togglePublicSidebar(true)" aria-expanded="false">
                <i data-lucide="menu" class="h-5 w-5"></i>
            </button>

            <!-- Logo -->
            <a href="/" class="flex items-center px-1 py-1 text-white">
                <img src="{{ \App\Helpers\ImageHelper::getUrl('logo/remenant-health-logo.png', 'images') }}"
                    alt="{{ config('app.name', 'Remenant Health') }} logo" class="h-12 w-auto object-contain">
            </a>

            @if(!$isAuthPage)
                <!-- Search -->
                <div class="flex-1">
                    <form action="{{ route('products.index') }}" method="GET" class="relative max-w-[520px]" data-search-form>
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-white/80">
                            <i data-lucide="search" class="h-5 w-5"></i>
                        </span>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Search for products…" autocomplete="off"
                            class="w-full rounded-full border border-white/30 bg-white/10 py-2 pl-10 pr-4 text-white placeholder:text-white/60 outline-none transition-all duration-300 focus:max-w-[580px] focus:bg-white/20 focus:ring-0 focus:border-white/50 focus:shadow-[0_0_20px_rgba(255,255,255,0.1)]"
                            data-search-input>
                        
                        <!-- Suggestions Container -->
                        <div class="absolute left-0 right-0 top-full mt-2 hidden overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/10 z-[60]" data-search-suggestions>
                            <!-- AJAX content here -->
                        </div>
                    </form>
                </div>
            @else
                <div class="flex-1"></div>
            @endif

            @if(!$isAuthPage)
                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('cart') }}"
                        class="header-btn relative inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                        aria-label="Cart">
                        <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                        <span
                            class="cart-count-badge absolute -top-1 -right-1 {{ $cartCount > 0 ? '' : 'hidden' }} flex h-4 w-4 items-center justify-center rounded-full bg-white text-[9px] font-black text-[color:var(--primary)] shadow-sm">
                            {{ $cartCount }}
                        </span>
                    </a>

                    <details class="relative" data-account-dropdown>
                        <summary
                            class="header-btn list-none inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition"
                            aria-label="Account">
                            <i data-lucide="user" class="h-5 w-5"></i>
                        </summary>

                        <div
                            class="absolute right-0 mt-2 w-48 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-black/10">
                            <div class="px-4 py-3">
                                <p class="text-xs font-semibold text-gray-500">
                                    {{ auth()->check() ? 'Profile Setting' : 'Account' }}
                                </p>
                                <p class="text-sm font-extrabold text-gray-900">
                                    {{ auth()->check() ? auth()->user()->name : 'Guest' }}
                                </p>
                            </div>
                            <div class="h-px bg-gray-100"></div>
                            <div class="p-2">
                                @auth
                                    @if(auth()->user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" up-follow="false"
                                            class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="layout-dashboard" class="h-4 w-4"></i>
                                            Admin Dashboard
                                        </a>
                                    @else
                                        <a href="{{ route('my-orders') }}"
                                            class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="package" class="h-4 w-4"></i>
                                            My Orders
                                        </a>
                                        <a href="{{ route('my-orders', ['tab' => 'profile']) }}"
                                            class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="settings" class="h-4 w-4"></i>
                                            Profile Settings
                                        </a>
                                    @endif

                                    <form method="POST" action="{{ route('logout') }}" class="mt-1" up-follow="false">
                                        @csrf
                                        <button type="submit"
                                            class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-left text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="log-out" class="h-4 w-4"></i>
                                            Logout
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}"
                                        up-follow="false"
                                        class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                        <i data-lucide="log-in" class="h-4 w-4"></i>
                                        Login
                                    </a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            up-follow="false"
                                            class="mt-1 flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                            <i data-lucide="user-plus" class="h-4 w-4"></i>
                                            Register
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </details>

                    <a href="{{ route('products.index') }}"
                        class="hidden rounded-full bg-white px-4 py-2 text-sm font-extrabold text-[color:var(--primary)] shadow-sm ring-1 ring-white/40 hover:bg-white/90 transition lg:inline-flex">
                        Shop All
                    </a>
                </div>
            @endif
        </div>

        <!-- Mobile search bar (Sticky) -->
        <div class="sm:hidden public-mobile-search px-4 pb-4 -mt-1 {{ $isAuthPage || request()->routeIs('products.show', 'cart', 'dashboard', 'my-orders') ? 'hidden' : '' }}"
            data-mobile-search>
            <form action="{{ route('products.index') }}" method="GET" class="relative" data-search-form>
                <span class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-white/80">
                    <i data-lucide="search" class="h-5 w-5"></i>
                </span>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search for products…" autocomplete="off"
                    class="w-full rounded-full border border-white/30 bg-white/10 py-3 pl-11 pr-4 text-white placeholder:text-white/60 outline-none transition-all duration-300 focus:bg-white/20 focus:ring-0 focus:border-white/50 focus:shadow-[0_0_20px_rgba(255,255,255,0.1)]"
                    data-search-input>
                
                <!-- Mobile Suggestions Container -->
                <div class="absolute left-0 right-0 top-full mt-1 hidden overflow-hidden rounded-2xl bg-white shadow-2xl ring-1 ring-black/10 z-[60]" data-search-suggestions>
                    <!-- AJAX content here -->
                </div>
            </form>
        </div>
    </div>
</header>

<script>
    // Handle Account Dropdown Persistence and Behavior
    (function() {
        function initAccountDropdown() {
            const dropdowns = document.querySelectorAll('[data-account-dropdown], [data-account-dropdown-mobile]');
            
            dropdowns.forEach(dropdown => {
                // Close on outside click
                const outsideClickListener = (e) => {
                    if (!dropdown.contains(e.target) && dropdown.open) {
                        dropdown.open = false;
                    }
                };
                
                document.removeEventListener('click', outsideClickListener);
                document.addEventListener('click', outsideClickListener);

                // Close on Escape key
                const escListener = (e) => {
                    if (e.key === 'Escape' && dropdown.open) {
                        dropdown.open = false;
                    }
                };
                document.removeEventListener('keydown', escListener);
                document.addEventListener('keydown', escListener);

                // Close when a link inside is clicked
                const links = dropdown.querySelectorAll('a, button');
                links.forEach(link => {
                    link.addEventListener('click', () => {
                        dropdown.open = false;
                    });
                });
            });
        }

        // Initialize on load and after Unpoly fragment insertion
        initAccountDropdown();
        if (window.up) {
            up.on('up:fragment:inserted', initAccountDropdown);
        }
    })();
</script>

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