@extends('public.layouts.app')

@section('title', config('app.name', 'Remenant Health').' - Home')

@section('content')
    <section class="bg-[var(--bg-light)]">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 py-10 sm:px-6 lg:grid-cols-12 lg:px-8">
            <div class="lg:col-span-7">
                <h1 class="text-3xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-4xl">
                    Discover products you’ll love.
                </h1>
                <p class="mt-3 max-w-2xl text-base text-[color:var(--text-secondary)] sm:text-lg">
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
                <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-black/5">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-semibold text-[color:var(--text-primary)]">Featured</p>
                        <span class="rounded-full bg-[var(--primary-soft)] px-3 py-1 text-xs font-semibold text-[color:var(--primary)]">
                            Hot
                        </span>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @for ($i = 0; $i < 4; $i++)
                            <div class="rounded-2xl bg-[var(--bg-section)] p-3">
                                <div class="aspect-square w-full rounded-xl bg-white/80 ring-1 ring-black/5"></div>
                                <p class="mt-2 text-sm font-semibold text-[color:var(--text-primary)]">Product {{ $i + 1 }}</p>
                                <p class="text-xs text-[color:var(--text-secondary)]">₹1,999</p>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="shop" class="bg-[var(--bg-main)]">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-extrabold text-[color:var(--text-primary)]">Best Sellers</h2>
                    <p class="mt-1 text-sm text-[color:var(--text-secondary)]">Starter grid — we’ll replace with real products later.</p>
                </div>
                <a href="#all" class="rounded-full bg-black/5 px-4 py-2 text-sm font-semibold hover:bg-black/10 transition">View all</a>
            </div>

            <div class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @for ($i = 0; $i < 8; $i++)
                    <div class="group rounded-3xl bg-white p-4 shadow-sm ring-1 ring-black/5 hover:shadow-md transition">
                        <div class="aspect-square w-full rounded-2xl bg-[var(--bg-section)] ring-1 ring-black/5"></div>
                        <div class="mt-3 flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-bold text-[color:var(--text-primary)]">Item {{ $i + 1 }}</p>
                                <p class="text-xs text-[color:var(--text-secondary)]">Short description</p>
                            </div>
                            <p class="text-sm font-extrabold text-[color:var(--primary)]">₹2,499</p>
                        </div>
                        <button class="mt-3 w-full rounded-full bg-[var(--primary)] px-4 py-2 text-sm font-semibold text-white transition group-hover:opacity-95">
                            Add to cart
                        </button>
                    </div>
                @endfor
            </div>
        </div>
    </section>
@endsection

