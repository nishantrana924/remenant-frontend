<footer class="border-t border-black/5 bg-[var(--bg-sage)] overflow-hidden">
    <div class="mx-auto max-w-[1600px] px-4 py-20 sm:px-6 lg:px-12">
        <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-12">
            <!-- Brand Column -->
            <div class="lg:col-span-3">
                <a href="/" class="inline-flex">
                    <img src="{{ \App\Helpers\ImageHelper::getUrl('logo/remenant-health-logo.png', 'images') }}" alt="Remenant Health"
                        class="h-10 w-auto object-contain">
                </a>
                <p class="mt-6 text-sm leading-relaxed text-black/70 max-w-xs">
                    Premium effervescent wellness formulas crafted for immortality. High-bioavailability supplements
                    engineered for modern lives.
                </p>
                <!-- Social Links (Using SVGs for reliability) -->
                <div class="mt-8 flex items-center gap-3">
                    <a href="https://www.instagram.com/remenant_health?igsh=cm1lcjIzbWRteTk4" target="_blank"
                        aria-label="Instagram"
                        class="group flex h-10 w-10 items-center justify-center rounded-xl bg-white/50 text-[color:var(--text-primary)] ring-1 ring-black/5 hover:bg-black hover:text-white transition-all duration-300 shadow-sm">
                        <svg class="h-5 w-5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
                            <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                            <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
                        </svg>
                    </a>

                    <a href="https://www.facebook.com/share/18Pdh9bjAU/?mibextid=wwXIfr" target="_blank"
                        aria-label="Facebook"
                        class="group flex h-10 w-10 items-center justify-center rounded-xl bg-white/50 text-[color:var(--text-primary)] ring-1 ring-black/5 hover:bg-black hover:text-white transition-all duration-300 shadow-sm">
                        <svg class="h-5 w-5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Links Columns -->
            <div class="lg:col-span-2">
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)]">Catalog
                </h3>
                <ul class="mt-6 space-y-3 text-sm font-medium">
                    <li><a href="{{ route('products.index', ['sort' => 'best-selling']) }}"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">Best Sellers</a>
                    </li>
                    <li><a href="{{ route('products.index', ['category' => 'Combo Offers']) }}"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">Combo Offers</a>
                    </li>
                    <li><a href="{{ route('products.index', ['category' => 'Immunity']) }}"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">Immunity
                            Boosters</a></li>
                    <li><a href="{{ route('products.index', ['category' => 'Beauty & Skin']) }}"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">Beauty Formulas</a>
                    </li>
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)]">Company
                </h3>
                <ul class="mt-6 space-y-3 text-sm font-medium">
                    <li><a href="{{ route('about') }}"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">About Us</a></li>
                    <li><a href="{{ route('about') }}#philosophy"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">Our Philosophy</a>
                    </li>
                    <li><a href="{{ route('about') }}#process"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">The Process</a></li>
                    <li><a href="{{ route('about') }}#founders"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">Our Founders</a>
                    </li>
                    <li><a href="{{ route('contact') }}"
                            class="text-[color:var(--text-primary)]/70 hover:text-black transition">Contact
                            Us</a></li>
                </ul>
            </div>

            <div class="lg:col-span-2">
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)]">
                    Assistance</h3>
                <ul class="mt-6 space-y-3 text-sm font-medium">
                    <li><a href="{{ route('refund') }}" class="text-[color:var(--text-primary)]/70 hover:text-black transition">Refund
                            Policy</a></li>
                    <li><a href="{{ route('shipping') }}" class="text-[color:var(--text-primary)]/70 hover:text-black transition">Shipping
                            Guide</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-[color:var(--text-primary)]/70 hover:text-black transition">Privacy
                            Policy</a></li>
                    <li><a href="{{ route('terms') }}" class="text-[color:var(--text-primary)]/70 hover:text-black transition">Terms &
                            Conditions</a></li>
                </ul>
            </div>

            <!-- Location/Contact -->
            <div class="lg:col-span-3">
                <h3 class="text-xs font-semibold uppercase tracking-[0.2em] text-[color:var(--text-primary)]">
                    Headquarters</h3>
                <address class="mt-6 not-italic text-sm leading-relaxed text-[color:var(--text-primary)]/70">
                    224, Ambika pinnacle Mall, Lajamani Chowk,<br>
                    Mota Varachha, Surat, Gujarat - 394101
                </address>
                <div class="mt-4 text-sm font-bold text-black">
                    <a href="tel:7567776796" class="hover:underline">756 777 6796</a>
                </div>
                <div class="mt-4 text-sm font-bold text-black">
                    <a href="mailto:support@remenant.in" class="hover:underline">support@remenant.in</a>
                </div>
                <div class="mt-4 text-sm font-bold text-black">
                    <span class="block text-[10px] uppercase text-[color:var(--text-primary)]/70 tracking-wider mb-0.5">Business Inquiries</span>
                    <a href="mailto:info@remenanthealth.com" class="hover:underline">info@remenanthealth.com</a>
                </div>
            </div>
        </div>

        <!-- Payment & Trust Badges -->
        <div class="mt-12 border-t border-black/5 pt-10">
            <div class="flex flex-wrap items-center justify-center gap-3 sm:gap-4">
                <div
                    class="flex h-9 w-14 items-center justify-center rounded-lg bg-white shadow-sm ring-1 ring-black/[0.06] transition hover:shadow-md">
                    <span class="text-[10px] font-bold italic text-[#1a1f71]">VISA</span>
                </div>
                <div
                    class="flex h-9 w-14 items-center justify-center rounded-lg bg-white shadow-sm ring-1 ring-black/[0.06] transition hover:shadow-md">
                    <div class="flex items-center -space-x-1.5">
                        <div class="h-4 w-4 rounded-full bg-[#eb001b] opacity-90"></div>
                        <div class="h-4 w-4 rounded-full bg-[#f79e1b] opacity-90"></div>
                    </div>
                </div>
                <div
                    class="flex h-9 w-14 items-center justify-center rounded-lg bg-white shadow-sm ring-1 ring-black/[0.06] transition hover:shadow-md">
                    <span class="text-[10px] font-bold text-[#003480] italic">RuPay<span
                            class="text-[#f58220]">❯</span></span>
                </div>
                <div
                    class="flex h-9 items-center justify-center rounded-lg bg-white px-4 shadow-sm ring-1 ring-black/[0.06] gap-2 transition hover:shadow-md text-black/70">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 20H4V4h16v16zM4 9h16M9 4v16" />
                    </svg>
                    <span class="text-[8px] font-bold uppercase tracking-wider">Net Banking</span>
                </div>
                <div
            </div>
        </div>

        <!-- Medical Disclaimer -->
        <div class="mt-12 border-t border-black/5 pt-10">
            <p class="text-[10px] sm:text-[11px] leading-relaxed text-black/40 text-center max-w-5xl mx-auto italic">
                <strong>Disclaimer:</strong> The information provided on this website is for educational purposes only and is not intended as a substitute for professional medical advice, diagnosis, or treatment. These products are not intended to diagnose, treat, cure, or prevent any disease. Always consult your healthcare professional before starting any new supplement regimen, especially if you are pregnant, nursing, or have a pre-existing medical condition.
            </p>
        </div>

        <div
            class="mt-10 border-t border-black/5 pt-8 flex flex-col items-center gap-6 sm:flex-row sm:justify-between text-[11px] sm:text-xs font-medium !text-black">
            <div class="flex flex-col items-center gap-4 sm:flex-row sm:gap-8 text-center sm:text-left">
                <div class="flex items-center gap-6">
                    <p class="font-semibold !text-black">&copy; {{ date('Y') }} Remenant Health. All rights reserved.
                    </p>
                </div>
            </div>
            <div
                class="flex items-center gap-2.5 bg-black/[0.04] px-4 py-2 rounded-full border border-black/[0.05] shadow-inner transition hover:bg-black/[0.06]">
                <a href="https://brandsphereit.com/" target="_blank"
                    class="text-[10px] uppercase font-semibold tracking-tight !text-black">Crafted by
                    Brand
                    Sphere</a>
            </div>
        </div>
    </div>
</footer>