@extends('public.layouts.app')

@section('title', 'Shipping Guide - Remenant Health')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-[var(--bg-main)] py-12 lg:py-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12 text-center">
            <div class="max-w-4xl mx-auto">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em] sm:tracking-[0.4em] text-[color:var(--primary)] mb-3 sm:mb-4 block">Delivery Logistics</span>
                <h1 class="text-3xl sm:text-5xl lg:text-7xl font-bold text-[color:var(--text-primary)] leading-[1.1] sm:leading-[0.9] tracking-tighter mb-4 sm:mb-6 uppercase">
                    Shipping <br class="sm:hidden"> Guide
                </h1>
                <div class="flex items-center justify-center gap-2 sm:gap-3">
                    <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-[color:var(--primary)] ring-1 ring-orange-200">
                         Standard & Express
                    </span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Global Delivery</span>
                </div>
            </div>
        </div>
        
        <!-- Decorative Background Element -->
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-[var(--primary)]/5 blur-3xl pointer-events-none"></div>
    </section>

    <!-- Content Section -->
    <section class="bg-white py-12 lg:py-24">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                
                <!-- Sidebar Navigation (Desktop) -->
                <div class="hidden lg:block lg:col-span-4">
                    <div class="sticky top-32 space-y-2">
                        <nav class="flex flex-col gap-1">
                            <a href="#processing" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Order Processing
                            </a>
                            <a href="#rates" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Shipping Rates
                            </a>
                            <a href="#tracking" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Tracking Info
                            </a>
                            <a href="#estimates" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Delivery Estimates
                            </a>
                            <a href="#damages" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Damaged Goods
                            </a>
                        </nav>
                        
                        <!-- Contact Card -->
                        <div class="mt-12 p-8 rounded-[2rem] bg-[var(--primary-soft)] border border-[var(--primary)]/10 shadow-sm">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-4">Need Help?</h4>
                            <p class="text-xs font-medium text-gray-500 leading-relaxed mb-6">Our support team is here to help you track your order.</p>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[color:var(--primary)] hover:underline">
                                Contact Support <i data-lucide="arrow-right" class="h-3 w-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <div class="prose prose-orange max-w-none space-y-12 sm:space-y-16">
                        
                        <!-- Processing Time -->
                        <div id="processing" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">01</span>
                                Order Processing
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>All orders are processed within 1-2 business days. Orders are not shipped or delivered on weekends or holidays.</p>
                                <p>If we are experiencing a high volume of orders, shipments may be delayed by a few days. Please allow additional days in transit for delivery.</p>
                                <p>If there will be a significant delay in shipment of your order, we will contact you via email or telephone.</p>
                            </div>
                        </div>

                        <!-- Rates & Methods -->
                        <div id="rates" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">02</span>
                                Shipping Rates
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>Shipping charges for your order will be calculated and displayed at checkout.</p>
                                <p>We offer free standard shipping on all orders over ₹999 within India. For international orders, rates vary by destination and weight.</p>
                            </div>
                        </div>

                        <!-- Tracking -->
                        <div id="tracking" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">03</span>
                                Tracking Info
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>You will receive a Shipment Confirmation email once your order has shipped containing your tracking number(s).</p>
                                <p>The tracking number will be active within 24 hours. You can track your package directly through our website or the carrier's portal.</p>
                            </div>
                        </div>

                        <!-- Delivery Estimates -->
                        <div id="estimates" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">04</span>
                                Delivery Estimates
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>Standard Shipping: 3-5 business days</p>
                                <p>Express Shipping: 1-2 business days</p>
                                <p>International Shipping: 7-14 business days (depending on customs)</p>
                            </div>
                        </div>

                        <!-- Damaged Goods -->
                        <div id="damages" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">05</span>
                                Damaged Goods
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>Remenant Health is not liable for any products damaged or lost during shipping. If you received your order damaged, please contact the shipment carrier to file a claim.</p>
                                <p>Please save all packaging materials and damaged goods before filing a claim.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Simple CTA at bottom for mobile -->
                    <div class="mt-12 lg:hidden p-10 rounded-[3rem] bg-orange-50 border border-orange-100 text-center">
                         <h4 class="text-xl font-bold text-gray-900 mb-4">Tracking Issues?</h4>
                         <p class="text-sm font-medium text-gray-500 mb-8">Our logistics team can help you locate your package.</p>
                         <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-[color:var(--primary)] text-white text-xs font-bold uppercase tracking-widest shadow-xl shadow-orange-200">
                             Contact Logistics
                         </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Banner -->
    <section class="py-12 lg:py-20 bg-white border-t border-black/5">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-12 p-10 lg:p-16 rounded-[4rem] bg-[#F5F8F7] shadow-2xl shadow-black/[0.02] border border-black/5">
                <div class="max-w-xl text-center lg:text-left">
                    <h2 class="text-2xl sm:text-4xl font-bold text-gray-900 uppercase tracking-tight leading-tight mb-4 sm:mb-6">
                        Fast & <br class="hidden sm:block"> Reliable
                    </h2>
                    <p class="text-sm sm:text-lg font-medium text-gray-500 leading-relaxed mb-6 sm:mb-0">
                        We ensure your effervescent immunity boosters reach you in record time. Read our Privacy Policy to see how we protect your shipping data.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                    <a href="{{ route('privacy') }}" class="inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-3xl bg-[var(--primary-soft)] text-[color:var(--primary)] text-[10px] sm:text-xs font-bold uppercase tracking-widest hover:bg-[color:var(--primary)] hover:text-white transition-all border border-[color:var(--primary)]/10">
                        Privacy Policy
                    </a>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-3xl border-2 border-gray-100 text-gray-900 text-[10px] sm:text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition-all">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
