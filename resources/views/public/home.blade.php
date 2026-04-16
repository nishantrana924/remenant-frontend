<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Shipping Product') }} - Efficient Shipping Management</title>
    <link rel="icon" href="{{ asset('images/logo/remenant-health-favicon.jpg') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS heloo -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { overflow-x: hidden; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="font-sans antialiased bg-[var(--bg-main)]">
    <div class="min-h-screen flex flex-col">
        <header class="sticky top-0 z-50">
            <!-- Top promo bar -->
            <div class="bg-[var(--secondary)] text-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 text-sm sm:px-6 lg:px-8">
                    <p class="font-semibold">Get our Exclusive Best Sellers!</p>
                    <a href="#shop" class="rounded-full bg-white/10 px-3 py-1 font-semibold hover:bg-white/20 transition">Shop Now</a>
                </div>
            </div>

            <!-- Main header -->
            <div class="brand-gradient">
                <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-3">
                        <!-- Menu -->
                        <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition" aria-label="Open menu">
                            <i data-lucide="menu" class="h-5 w-5"></i>
                        </button>

                        <!-- Logo -->
                        <a href="/" class="flex items-center rounded-full bg-white/15 px-3 py-2 text-white hover:bg-white/20 transition">
                            <img
                                src="{{ asset('images/logo/remenant-health-logo.jpg') }}"
                                alt="{{ config('app.name', 'Remenant Health') }} logo"
                                class="h-9 w-9 rounded-lg object-contain bg-white"
                            >
                        </a>

                        <!-- Search -->
                        <div class="flex-1">
                            <div class="relative max-w-[520px]">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-white/80">
                                    <i data-lucide="search" class="h-5 w-5"></i>
                                </span>
                                <input
                                    type="search"
                                    placeholder="Search for products…"
                                    class="w-full rounded-full border border-white/25 bg-white/15 py-2 pl-10 pr-4 text-white placeholder:text-white/70 outline-none ring-0 focus:border-white/40 focus:bg-white/20"
                                >
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2">
                            <a href="#cart" class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition" aria-label="Cart">
                                <i data-lucide="shopping-cart" class="h-5 w-5"></i>
                            </a>

                            @auth
                                <a href="{{ route('dashboard') }}" class="hidden rounded-full bg-white px-4 py-2 text-sm font-extrabold text-[var(--primary)] shadow-sm ring-1 ring-white/40 hover:bg-white/90 transition sm:inline-flex">
                                    Dashboard
                                </a>
                            @else
                                <details class="relative">
                                    <summary class="list-none inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-white/15 text-white hover:bg-white/20 transition" aria-label="Account">
                                        <i data-lucide="user" class="h-5 w-5"></i>
                                    </summary>

                                    <div class="absolute right-0 mt-2 w-48 overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-black/10">
                                        <div class="px-4 py-3">
                                            <p class="text-xs font-semibold text-gray-500">Account</p>
                                            <p class="text-sm font-extrabold text-gray-900">{{ config('app.name', 'Remenant Health') }}</p>
                                        </div>
                                        <div class="h-px bg-gray-100"></div>
                                        <div class="p-2">
                                            <a href="{{ route('login') }}" class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                                <i data-lucide="log-in" class="h-4 w-4"></i>
                                                Login
                                            </a>
                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}" class="mt-1 flex items-center gap-2 rounded-xl px-3 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">
                                                    <i data-lucide="user-plus" class="h-4 w-4"></i>
                                                    Register
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </details>

                                @if (Route::has('register'))
                                    <a href="#shop" class="hidden rounded-full bg-white px-4 py-2 text-sm font-extrabold text-[var(--primary)] shadow-sm ring-1 ring-white/40 hover:bg-white/90 transition sm:inline-flex">
                                        Shop All
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1">
            <section class="bg-[var(--bg-light)]">
                <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 py-10 sm:px-6 lg:grid-cols-12 lg:px-8">
                    <div class="lg:col-span-7">
                        <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl" style="color: var(--text-primary);">
                            Discover products you’ll love.
                        </h1>
                        <p class="mt-3 max-w-2xl text-base sm:text-lg" style="color: var(--text-secondary);">
                            A clean, fast storefront UI inspired by your reference — with brand colors controlled via CSS variables.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="#shop" class="btn-primary">Shop Now</a>
                            <a href="#categories" class="rounded-full border border-black/10 bg-white px-4 py-2 font-semibold hover:bg-black/5 transition">
                                Browse Categories
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5">
                        @php
                            $featuredCards = [
                                ['image' => 'remanent018.png', 'name' => 'Wellness Boost', 'price' => '19.99'],
                                ['image' => 'remanent019.png', 'name' => 'Glow Formula', 'price' => '21.49'],
                                ['image' => 'remanent020.png', 'name' => 'Herb Blend', 'price' => '18.99'],
                                ['image' => 'remanent021.png', 'name' => 'Daily Care', 'price' => '20.00'],
                            ];
                        @endphp
                        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold" style="color: var(--text-primary);">Featured</p>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold" style="background: var(--primary-soft); color: var(--primary);">
                                    Hot
                                </span>
                            </div>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                @foreach ($featuredCards as $card)
                                    <div class="rounded-2xl overflow-hidden bg-[var(--bg-section)] ring-1 ring-black/5 shadow-sm">
                                        <img src="{{ asset('images/one/'.$card['image']) }}" alt="{{ $card['name'] }}" class="aspect-square w-full object-cover" />
                                        <div class="px-3 py-3">
                                            <p class="text-sm font-semibold" style="color: var(--text-primary);">{{ $card['name'] }}</p>
                                            <p class="text-xs" style="color: var(--text-secondary);">${{ $card['price'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="shop" class="bg-[var(--bg-main)]">
                <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                    <div class="flex items-end justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-extrabold" style="color: var(--text-primary);">Best Sellers</h2>
                            <p class="mt-3 max-w-2xl text-sm" style="color: var(--text-secondary);">Discover products you’ll love. A clean, fast storefront UI inspired by your reference — with brand colors controlled via CSS variables.</p>
                            <p class="mt-1 text-sm" style="color: var(--text-secondary);">Starter grid — we’ll replace with real products later.</p>
                        </div>
                        <a href="#all" class="rounded-full bg-black/5 px-4 py-2 text-sm font-semibold hover:bg-black/10 transition">View all</a>
                    </div>

                    @php
                        $bestSellers = [
                            ['image' => 'remanent010.png', 'name' => 'Healthy Glow', 'price' => '24'],
                            ['image' => 'remanent011.png', 'name' => 'Daily Wellness', 'price' => '28'],
                            ['image' => 'remanent012.png', 'name' => 'Natural Boost', 'price' => '22'],
                            ['image' => 'remanent013.png', 'name' => 'Pure Essentials', 'price' => '30'],
                            ['image' => 'remanent014.png', 'name' => 'Herbal Care', 'price' => '26'],
                            ['image' => 'remanent015.png', 'name' => 'Calm Restore', 'price' => '29'],
                            ['image' => 'remanent016.png', 'name' => 'Vital Mix', 'price' => '21'],
                            ['image' => 'remanent017.png', 'name' => 'Daily Ritual', 'price' => '27'],
                        ];
                    @endphp

                    <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        @foreach ($bestSellers as $item)
                            <div class="group rounded-3xl bg-white shadow-sm ring-1 ring-black/5 hover:shadow-md transition">
                                <div class="overflow-hidden rounded-t-3xl bg-[var(--bg-section)] ring-1 ring-black/5">
                                    <img src="{{ asset('images/one/'.$item['image']) }}" alt="{{ $item['name'] }}" class="h-48 w-full object-cover transition duration-300 group-hover:scale-105" />
                                </div>
                                <div class="p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-bold" style="color: var(--text-primary);">{{ $item['name'] }}</p>
                                            <p class="mt-1 text-xs" style="color: var(--text-secondary);">Starter product card</p>
                                        </div>
                                        <p class="text-sm font-extrabold" style="color: var(--primary);">${{ $item['price'] }}</p>
                                    </div>
                                    <button class="mt-4 w-full rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white transition group-hover:opacity-95">
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        </main>

        <footer class="border-t border-black/5 bg-[var(--bg-main)] py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="flex items-center space-x-2 mb-2 md:mb-0">
                            <img
                                src="{{ asset('images/logo/remenant-health-logo.jpg') }}"
                                alt="{{ config('app.name', 'Remenant Health') }} logo"
                                class="h-5 w-5 rounded object-contain"
                            >
                        <span class="text-sm font-semibold" style="color: var(--text-primary);">{{ config('app.name', 'Remenant Health') }}</span>
                    </div>
                    <p class="text-sm text-gray-500">
                        &copy; {{ date('Y') }} All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>

