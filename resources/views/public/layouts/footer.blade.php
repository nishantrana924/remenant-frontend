<footer class="mt-8 border-t border-[#f4c8a8] bg-gradient-to-b from-[#FFF4EC] to-[#FFEBDD]">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <a href="/" class="inline-flex items-center">
                    <img
                        src="{{ asset('images/logo/remenant-health-logo2.png') }}"
                        alt="{{ config('app.name', 'Remenant Health') }} logo"
                        class="h-12 w-auto object-contain"
                    >
                </a>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-[color:var(--text-secondary)]">
                    Premium effervescent wellness formulas crafted for beauty, immunity, detox, and daily vitality.
                </p>
                <div class="mt-4 inline-flex items-center rounded-full bg-white/90 px-3 py-2 text-xs font-semibold text-[color:var(--text-secondary)] ring-1 ring-[#f4c8a8]">
                    🇮🇳 Proudly Made for India
                </div>
            </div>

            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wide text-[color:var(--text-primary)]">Shop</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="#shop" class="text-[color:var(--text-secondary)] hover:text-[color:var(--primary)] transition">Glutathione</a></li>
                    <li><a href="#shop" class="text-[color:var(--text-secondary)] hover:text-[color:var(--primary)] transition">Vitamin C</a></li>
                    <li><a href="#shop" class="text-[color:var(--text-secondary)] hover:text-[color:var(--primary)] transition">Biotin</a></li>
                    <li><a href="#shop" class="text-[color:var(--text-secondary)] hover:text-[color:var(--primary)] transition">ACV</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wide text-[color:var(--text-primary)]">Support</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li><a href="#" class="text-[color:var(--text-secondary)] hover:text-[color:var(--primary)] transition">Track Order</a></li>
                    <li><a href="#" class="text-[color:var(--text-secondary)] hover:text-[color:var(--primary)] transition">Shipping Policy</a></li>
                    <li><a href="#" class="text-[color:var(--text-secondary)] hover:text-[color:var(--primary)] transition">Returns & Refunds</a></li>
                    <li><a href="#" class="text-[color:var(--text-secondary)] hover:text-[color:var(--primary)] transition">FAQs</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-extrabold uppercase tracking-wide text-[color:var(--text-primary)]">Connect</h3>
                <ul class="mt-4 space-y-2 text-sm">
                    <li class="text-[color:var(--text-secondary)]">support@remenanthealth.com</li>
                    <li class="text-[color:var(--text-secondary)]">+91 90000 00000</li>
                </ul>
                <div class="mt-4 flex items-center gap-2">
                    <a href="#" aria-label="Instagram" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-[color:var(--text-primary)] ring-1 ring-[#f4c8a8] hover:text-[color:var(--primary)] transition">
                        <i data-lucide="instagram" class="h-4 w-4"></i>
                    </a>
                    <a href="#" aria-label="LinkedIn" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-[color:var(--text-primary)] ring-1 ring-[#f4c8a8] hover:text-[color:var(--primary)] transition">
                        <i data-lucide="linkedin" class="h-4 w-4"></i>
                    </a>
                    <a href="#" aria-label="YouTube" class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-[color:var(--text-primary)] ring-1 ring-[#f4c8a8] hover:text-[color:var(--primary)] transition">
                        <i data-lucide="youtube" class="h-4 w-4"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-3 border-t border-[#f4c8a8] pt-4 text-xs text-[color:var(--text-muted)] sm:flex-row sm:items-center sm:justify-between">
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Remenant Health') }}. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="#" class="hover:text-[color:var(--primary)] transition">Privacy Policy</a>
                <a href="#" class="hover:text-[color:var(--primary)] transition">Terms of Service</a>
            </div>
        </div>
        <p class="mt-3 text-center text-xs font-semibold text-[color:var(--text-secondary)]">
            Developed by <span class="text-[color:var(--primary)]">Brand Sphere</span>
        </p>
    </div>
</footer>

