@extends('public.layouts.app')

@section('title', 'Terms & Conditions - Remenant Health')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-[var(--bg-main)] py-12 lg:py-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12 text-center">
            <div class="max-w-4xl mx-auto">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em] sm:tracking-[0.4em] text-[color:var(--primary)] mb-3 sm:mb-4 block">Legal Agreement</span>
                <h1 class="text-3xl sm:text-5xl lg:text-7xl font-bold text-[color:var(--text-primary)] leading-[1.1] sm:leading-[0.9] tracking-tighter mb-4 sm:mb-6 uppercase">
                    Terms & <br class="sm:hidden"> Conditions
                </h1>
                <div class="flex items-center justify-center gap-2 sm:gap-3">
                    <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-[color:var(--primary)] ring-1 ring-orange-200">
                        Version 1.0
                    </span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Last Updated: May 03, 2026</span>
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
                            <a href="#introduction" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Introduction
                            </a>
                            <a href="#eligibility" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-black uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Eligibility
                            </a>
                            <a href="#accounts" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-black uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                User Accounts
                            </a>
                            <a href="#orders" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-black uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Orders & Pricing
                            </a>
                            <a href="#intellectual-property" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-black uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                IP Rights
                            </a>
                            <a href="#termination" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-black uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Termination
                            </a>
                        </nav>
                        
                        <!-- Contact Card -->
                        <div class="mt-12 p-8 rounded-[2rem] bg-[var(--primary-soft)] border border-[var(--primary)]/10 shadow-sm">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-4">Have Questions?</h4>
                            <p class="text-xs font-medium text-gray-500 leading-relaxed mb-6">If you have any questions about our terms, please contact our legal team.</p>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[color:var(--primary)] hover:underline">
                                Contact Legal <i data-lucide="arrow-right" class="h-3 w-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <div class="prose prose-orange max-w-none space-y-16">
                        
                        <!-- Introduction -->
                        <div id="introduction" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">01</span>
                                Introduction
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>Welcome to Remenant Health. These Terms and Conditions govern your use of our website located at remenanthealth.com and our related services.</p>
                                <p>By accessing or using our website, you agree to be bound by these terms. If you do not agree with any part of these terms, you must not use our website or services.</p>
                                <p>We reserve the right to modify these terms at any time. We will notify users of any significant changes by posting a notice on our homepage or via email.</p>
                            </div>
                        </div>

                        <!-- Eligibility -->
                        <div id="eligibility" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">02</span>
                                Eligibility
                            </h2>
                            <div class="space-y-6 text-lg leading-relaxed text-gray-600 font-medium">
                                <p>By using our services, you represent and warrant that you are at least 18 years of age and have the legal capacity to enter into a binding agreement.</p>
                                <p>If you are using our services on behalf of an entity, you represent and warrant that you have the authority to bind that entity to these Terms and Conditions.</p>
                            </div>
                        </div>

                        <!-- User Accounts -->
                        <div id="accounts" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">03</span>
                                User Accounts
                            </h2>
                            <div class="space-y-6 text-lg leading-relaxed text-gray-600 font-medium">
                                <p>To access certain features of our website, you may be required to create an account. You are responsible for maintaining the confidentiality of your account information, including your password.</p>
                                <p>You agree to provide accurate, current, and complete information during the registration process and to update such information to keep it accurate, current, and complete.</p>
                                <p>We reserve the right to suspend or terminate your account if any information provided proves to be inaccurate, fraudulent, or in violation of these terms.</p>
                            </div>
                        </div>

                        <!-- Orders & Pricing -->
                        <div id="orders" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">04</span>
                                Orders & Pricing
                            </h2>
                            <div class="space-y-6 text-lg leading-relaxed text-gray-600 font-medium">
                                <p>All orders placed through our website are subject to acceptance by us. We reserve the right to refuse or cancel any order for any reason, including limitations on quantities available for purchase or inaccuracies in product or pricing information.</p>
                                <p>Prices for our products are subject to change without notice. We shall not be liable to you or to any third-party for any modification, price change, suspension, or discontinuance of the service.</p>
                                <p>Payment must be made through our authorized payment gateways. By providing payment information, you represent and warrant that you have the legal right to use the chosen payment method.</p>
                            </div>
                        </div>

                        <!-- Intellectual Property -->
                        <div id="intellectual-property" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">05</span>
                                Intellectual Property
                            </h2>
                            <div class="space-y-6 text-lg leading-relaxed text-gray-600 font-medium">
                                <p>The content on our website, including text, graphics, logos, images, and software, is the property of Remenant Health and is protected by intellectual property laws.</p>
                                <p>You may not use, reproduce, distribute, or create derivative works based on our content without our express written permission.</p>
                            </div>
                        </div>

                        <!-- Termination -->
                        <div id="termination" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">06</span>
                                Termination
                            </h2>
                            <div class="space-y-6 text-lg leading-relaxed text-gray-600 font-medium">
                                <p>We reserve the right to terminate or suspend your access to our website and services at any time, without notice, for any conduct that we, in our sole discretion, believe is in violation of these terms or is harmful to other users or us.</p>
                                <p>Upon termination, your right to use the website and services will immediately cease.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Simple CTA at bottom for mobile -->
                    <div class="mt-12 lg:hidden p-10 rounded-[3rem] bg-orange-50 border border-orange-100 text-center">
                         <h4 class="text-xl font-bold text-gray-900 mb-4">Questions?</h4>
                         <p class="text-sm font-medium text-gray-500 mb-8">Our legal team is here to help you understand our terms better.</p>
                         <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-[color:var(--primary)] text-white text-xs font-bold uppercase tracking-widest shadow-xl shadow-orange-200">
                             Contact Us
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
                        Stay Informed <br class="hidden sm:block"> Stay Healthy
                    </h2>
                    <p class="text-sm sm:text-lg font-medium text-gray-500 leading-relaxed mb-6 sm:mb-0">
                        Our commitment to transparency ensures you always know how we operate. Check our Privacy Policy for more details on data handling.
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
