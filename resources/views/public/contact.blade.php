@extends('public.layouts.app')

@section('title', 'Contact Us - Remenant Health')

@section('content')

    <!-- Contact Hero Section -->
    <section class="relative overflow-hidden bg-[var(--bg-sage)] py-20 lg:py-28 border-b border-black/5">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12 relative z-10">
            <div class="text-center">
                <span class="inline-block px-4 py-1.5 rounded-full bg-white/50 text-xs font-semibold uppercase tracking-[0.2em] text-[#074D3D] backdrop-blur-sm">Get in Touch</span>
                <h1 class="mt-6 text-5xl font-semibold italic tracking-tight text-[#074D3D] sm:text-7xl lg:text-8xl">
                    Let's Connect
                </h1>
                <p class="mt-8 mx-auto max-w-2xl text-lg font-medium leading-relaxed text-[#074D3D]/80 sm:text-xl">
                    Have a question or just want to say hi? We're always here to listen and help you on your wellness journey.
                </p>
            </div>
        </div>

        <!-- Decorative Floating Elements (Branded) -->
        <div class="pointer-events-none absolute -left-12 top-20 text-7xl opacity-20 animate-float" style="animation-delay: 0s;">🌿</div>
        <div class="pointer-events-none absolute -right-12 bottom-10 text-6xl opacity-20 animate-float" style="animation-delay: 2s;">🍉</div>
        <div class="pointer-events-none absolute left-1/4 -top-10 text-5xl opacity-10 animate-float" style="animation-delay: 4s;">🍊</div>
        
        <!-- Background Blurs -->
        <div class="absolute -right-24 -top-24 h-[500px] w-[500px] rounded-full bg-white/30 blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 h-[500px] w-[500px] rounded-full bg-black/5 blur-3xl"></div>
    </section>


    <!-- Contact Form & Details Section -->
    <section class="py-20 lg:py-24 bg-[#FDF9F6]">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12 lg:items-start">

                <!-- Left: Contact Form -->
                <div class="lg:col-span-7">
                    <div class="rounded-[3rem] bg-white p-10 sm:p-14 shadow-2xl shadow-gray-200/50 border border-black/[0.03] relative overflow-hidden">
                        <!-- Subtle corner accent -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-[var(--bg-sage)] opacity-20 rounded-bl-[5rem] -mr-10 -mt-10"></div>
                        
                        <h2 class="text-3xl font-semibold italic text-[#074D3D] tracking-tight mb-10 relative">Drop us a Line</h2>

                        <form action="#" class="space-y-8 relative">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2.5 ml-1">Your Name</label>
                                    <input type="text" placeholder="John Doe"
                                        class="w-full rounded-2xl border border-gray-100 bg-gray-50/50 px-6 py-4 text-sm font-bold text-gray-900 focus:border-[var(--primary)] focus:bg-white focus:ring-4 focus:ring-[var(--primary)]/5 transition-all outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2.5 ml-1">Email Address</label>
                                    <input type="email" placeholder="john@example.com"
                                        class="w-full rounded-2xl border border-gray-100 bg-gray-50/50 px-6 py-4 text-sm font-bold text-gray-900 focus:border-[var(--primary)] focus:bg-white focus:ring-4 focus:ring-[var(--primary)]/5 transition-all outline-none">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2.5 ml-1">Phone Number</label>
                                    <input type="tel" placeholder="756 777 6796"
                                        class="w-full rounded-2xl border border-gray-100 bg-gray-50/50 px-6 py-4 text-sm font-bold text-gray-900 focus:border-[var(--primary)] focus:bg-white focus:ring-4 focus:ring-[var(--primary)]/5 transition-all outline-none">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2.5 ml-1">I'm asking about</label>
                                    <div class="relative">
                                        <select class="w-full rounded-2xl border border-gray-100 bg-gray-50/50 px-6 py-4 text-sm font-bold text-gray-900 focus:border-[var(--primary)] focus:bg-white focus:ring-4 focus:ring-[var(--primary)]/5 transition-all outline-none appearance-none">
                                            <option>General Support</option>
                                            <option>Order Tracking</option>
                                            <option>Product Guidance</option>
                                            <option>Bulk/Business</option>
                                        </select>
                                        <i data-lucide="chevron-down" class="absolute right-6 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-2.5 ml-1">Your Message</label>
                                <textarea rows="5" placeholder="Tell us how we can help..."
                                    class="w-full rounded-2xl border border-gray-100 bg-gray-50/50 px-6 py-4 text-sm font-bold text-gray-900 focus:border-[var(--primary)] focus:bg-white focus:ring-4 focus:ring-[var(--primary)]/5 transition-all outline-none resize-none"></textarea>
                            </div>

                            <button type="submit"
                                class="w-full rounded-2xl brand-gradient py-5 text-sm font-black uppercase tracking-[0.25em] text-white shadow-xl shadow-orange-200/50 transition-all hover:scale-[1.02] active:scale-[0.98] flex items-center justify-center gap-3">
                                <span>Send Message</span>
                                <i data-lucide="send" class="h-4 w-4"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right: Contact Info Card -->
                <div class="lg:col-span-5">
                    <div class="rounded-[3rem] bg-white p-10 border border-black/[0.03] shadow-2xl shadow-gray-200/40 space-y-12">
                        
                        <!-- Contact Item -->
                        <div class="flex gap-6">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[var(--primary-soft)] text-[var(--primary)] shadow-sm shadow-orange-100">
                                <i data-lucide="map-pin" class="h-6 w-6"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-widest text-gray-400 mb-2">Our Studio</h3>
                                <p class="text-lg font-medium text-gray-900 leading-relaxed">
                                    224, Ambika pinnacle, Lajamani chowk,<br>
                                    Mota Varachha, Surat, Gujarat - 394101
                                </p>
                            </div>
                        </div>

                        <!-- Contact Item -->
                        <div class="flex gap-6">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#E8F9F1] text-[#25D366] shadow-sm shadow-green-100">
                                <i data-lucide="message-circle" class="h-6 w-6"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-widest text-gray-400 mb-2">Instant Help</h3>
                                <p class="text-lg font-medium text-gray-900 mb-4">Chat with us on WhatsApp for quick support.</p>
                                <a href="https://wa.me/17567776796" class="inline-flex items-center gap-2 text-sm font-semibold text-[#25D366] hover:gap-3 transition-all">
                                    START CHAT <i data-lucide="arrow-right" class="h-4 w-4"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Contact Item -->
                        <div class="flex gap-6">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gray-100 text-gray-600 shadow-sm shadow-gray-200">
                                <i data-lucide="phone" class="h-6 w-6"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-widest text-gray-400 mb-2">Call Us</h3>
                                <div class="flex flex-col gap-1">
                                    <a href="tel:7567776796" class="text-lg font-semibold text-gray-900 hover:text-[var(--primary)] transition-colors">756 777 6796</a>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Item -->
                        <div class="flex gap-6">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#F0F4FF] text-[var(--info)] shadow-sm shadow-blue-100">
                                <i data-lucide="mail" class="h-6 w-6"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold uppercase tracking-widest text-gray-400 mb-2">Email Support</h3>
                                <a href="mailto:support@remenanthealth.com" class="text-lg font-semibold text-gray-900 hover:text-[var(--primary)] transition-colors break-all">
                                    support@remenanthealth.com
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map/Visual Section (Optional Mockup) -->
    <section class="pb-16 bg-white">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div
                class="relative h-[400px] overflow-hidden rounded-[3rem] bg-[var(--bg-section)] shadow-2xl border border-black/5">
                <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                    <div class="text-center">
                        <div
                            class="inline-flex h-20 w-20 items-center justify-center rounded-full bg-white shadow-xl mb-6 animate-bounce">
                            <i data-lucide="map-pin" class="h-10 w-10 text-[var(--primary)]"></i>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 uppercase tracking-widest">Global Reach. Local Care.
                        </h3>
                        <p class="mt-2 text-gray-500 font-medium">Shipping to over 20+ countries worldwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection