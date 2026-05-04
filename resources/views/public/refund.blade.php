@extends('public.layouts.app')

@section('title', 'Refund Policy - Remenant Health')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-[var(--bg-main)] py-12 lg:py-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12 text-center">
            <div class="max-w-4xl mx-auto">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em] sm:tracking-[0.4em] text-[color:var(--primary)] mb-3 sm:mb-4 block">Satisfaction Guaranteed</span>
                <h1 class="text-3xl sm:text-5xl lg:text-7xl font-bold text-[color:var(--text-primary)] leading-[1.1] sm:leading-[0.9] tracking-tighter mb-4 sm:mb-6 uppercase">
                    Refund <br class="sm:hidden"> Policy
                </h1>
                <div class="flex items-center justify-center gap-2 sm:gap-3">
                    <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-[color:var(--primary)] ring-1 ring-orange-200">
                         30-Day Window
                    </span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Hassle-Free Returns</span>
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
                            <a href="#eligibility" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Eligibility
                            </a>
                            <a href="#process" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Return Process
                            </a>
                            <a href="#refunds" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Refunds Info
                            </a>
                            <a href="#exchanges" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Exchanges
                            </a>
                            <a href="#non-returnable" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Exclusions
                            </a>
                        </nav>
                        
                        <!-- Contact Card -->
                        <div class="mt-12 p-8 rounded-[2rem] bg-[var(--primary-soft)] border border-[var(--primary)]/10 shadow-sm">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-4">Need a Return?</h4>
                            <p class="text-xs font-medium text-gray-500 leading-relaxed mb-6">Our returns team is here to help you with your request.</p>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[color:var(--primary)] hover:underline">
                                Start a Return <i data-lucide="arrow-right" class="h-3 w-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <div class="prose prose-orange max-w-none space-y-12 sm:space-y-16">
                        
                        <!-- Eligibility -->
                        <div id="eligibility" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">01</span>
                                Eligibility
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>To be eligible for a return, your item must be unused and in the same condition that you received it. It must also be in the original packaging.</p>
                                <p>Our return policy lasts 30 days. If 30 days have gone by since your purchase, unfortunately, we cannot offer you a refund or exchange.</p>
                            </div>
                        </div>

                        <!-- Return Process -->
                        <div id="process" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">02</span>
                                Return Process
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>To complete your return, we require a receipt or proof of purchase. Please do not send your purchase back to the manufacturer without first contacting us.</p>
                                <p>Contact our support team at support@remenanthealth.com with your order number to initiate the process.</p>
                            </div>
                        </div>

                        <!-- Refunds Info -->
                        <div id="refunds" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">03</span>
                                Refunds Info
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>Once your return is received and inspected, we will send you an email to notify you that we have received your returned item. We will also notify you of the approval or rejection of your refund.</p>
                                <p>If approved, your refund will be processed, and a credit will automatically be applied to your original method of payment within 5-10 business days.</p>
                            </div>
                        </div>

                        <!-- Exchanges -->
                        <div id="exchanges" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">04</span>
                                Exchanges
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>We only replace items if they are defective or damaged. If you need to exchange it for the same item, send us an email at support@remenanthealth.com.</p>
                            </div>
                        </div>

                        <!-- Exclusions -->
                        <div id="non-returnable" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">05</span>
                                Exclusions
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>Several types of goods are exempt from being returned. Perishable goods such as opened supplement bottles cannot be returned for hygiene and safety reasons.</p>
                                <p>Additional non-returnable items: Gift cards, downloadable software products, and some health and personal care items.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Simple CTA at bottom for mobile -->
                    <div class="mt-12 lg:hidden p-10 rounded-[3rem] bg-orange-50 border border-orange-100 text-center">
                         <h4 class="text-xl font-bold text-gray-900 mb-4">Need Help?</h4>
                         <p class="text-sm font-medium text-gray-500 mb-8">Our support team is here to assist with any refund questions.</p>
                         <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-[color:var(--primary)] text-white text-xs font-bold uppercase tracking-widest shadow-xl shadow-orange-200">
                             Contact Support
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
                        Risk-Free <br class="hidden sm:block"> Wellness
                    </h2>
                    <p class="text-sm sm:text-lg font-medium text-gray-500 leading-relaxed mb-6 sm:mb-0">
                        Your satisfaction is our priority. If our products don't meet your expectations, our refund policy has you covered.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                    <a href="{{ route('shipping') }}" class="inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-3xl bg-[var(--primary-soft)] text-[color:var(--primary)] text-[10px] sm:text-xs font-bold uppercase tracking-widest hover:bg-[color:var(--primary)] hover:text-white transition-all border border-[color:var(--primary)]/10">
                        Shipping Guide
                    </a>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-3xl border-2 border-gray-100 text-gray-900 text-[10px] sm:text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition-all">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
