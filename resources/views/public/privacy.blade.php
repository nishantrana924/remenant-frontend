@extends('public.layouts.app')

@section('title', 'Privacy Policy - Remenant Health')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-[var(--bg-main)] py-12 lg:py-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12 text-center">
            <div class="max-w-4xl mx-auto">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em] sm:tracking-[0.4em] text-[color:var(--primary)] mb-3 sm:mb-4 block">Transparency & Trust</span>
                <h1 class="text-3xl sm:text-5xl lg:text-7xl font-bold text-[color:var(--text-primary)] leading-[1.1] sm:leading-[0.9] tracking-tighter mb-4 sm:mb-6 uppercase">
                    Privacy <br class="sm:hidden"> Policy
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
                            <a href="#data-collection" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Data Collection
                            </a>
                            <a href="#data-usage" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Data Usage
                            </a>
                            <a href="#cookies" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Cookies Policy
                            </a>
                            <a href="#third-party" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Third Parties
                            </a>
                            <a href="#data-security" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Data Security
                            </a>
                            <a href="#user-rights" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                Your Rights
                            </a>
                        </nav>
                        
                        <!-- Contact Card -->
                        <div class="mt-12 p-8 rounded-[2rem] bg-[var(--primary-soft)] border border-[var(--primary)]/10 shadow-sm">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-4">Privacy Concerns?</h4>
                            <p class="text-xs font-medium text-gray-500 leading-relaxed mb-6">If you have any questions about your data, please contact our DPO.</p>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[color:var(--primary)] hover:underline">
                                Contact Privacy Team <i data-lucide="arrow-right" class="h-3 w-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <div class="prose prose-orange max-w-none space-y-12 sm:space-y-16">
                        
                        <!-- Data Collection -->
                        <div id="data-collection" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">01</span>
                                Data Collection
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>We collect information that you provide directly to us when you create an account, make a purchase, or communicate with us.</p>
                                <p>This may include your name, email address, phone number, shipping address, and payment information.</p>
                                <p>We also automatically collect certain technical information when you visit our website, such as your IP address, browser type, and device information.</p>
                            </div>
                        </div>

                        <!-- Data Usage -->
                        <div id="data-usage" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">02</span>
                                Data Usage
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>We use the information we collect to provide, maintain, and improve our services, including processing your orders and managing your account.</p>
                                <p>We may also use your information to communicate with you about products, services, offers, and events that we think will be of interest to you.</p>
                            </div>
                        </div>

                        <!-- Cookies Policy -->
                        <div id="cookies" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">03</span>
                                Cookies Policy
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>We use cookies and similar tracking technologies to track activity on our service and hold certain information.</p>
                                <p>Cookies are files with small amount of data which may include an anonymous unique identifier. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent.</p>
                            </div>
                        </div>

                        <!-- Third Parties -->
                        <div id="third-party" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">04</span>
                                Third Parties
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>We do not sell your personal information to third parties. We may share information with service providers who perform services on our behalf, such as payment processing and shipping.</p>
                                <p>These third parties are obligated to maintain the confidentiality and security of your personal information.</p>
                            </div>
                        </div>

                        <!-- Data Security -->
                        <div id="data-security" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">05</span>
                                Data Security
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>The security of your data is important to us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure.</p>
                                <p>While we strive to use commercially acceptable means to protect your personal data, we cannot guarantee its absolute security.</p>
                            </div>
                        </div>

                        <!-- Your Rights -->
                        <div id="user-rights" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">06</span>
                                Your Rights
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                <p>You have the right to access, update, or delete the information we have on you. You can do this directly within your account settings or by contacting us.</p>
                                <p>You also have the right to object to our processing of your personal data and to request that we restrict the processing of your personal information.</p>
                            </div>
                        </div>

                    </div>

                    <!-- Simple CTA at bottom for mobile -->
                    <div class="mt-12 lg:hidden p-10 rounded-[3rem] bg-orange-50 border border-orange-100 text-center">
                         <h4 class="text-xl font-bold text-gray-900 mb-4">Privacy Questions?</h4>
                         <p class="text-sm font-medium text-gray-500 mb-8">Our privacy team is here to help you understand your data rights.</p>
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
                        Safety & <br class="hidden sm:block"> Transparency
                    </h2>
                    <p class="text-sm sm:text-lg font-medium text-gray-500 leading-relaxed mb-6 sm:mb-0">
                        Your privacy is our gold standard. Read our Terms and Conditions to understand how we provide our high-bioavailability services.
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                    <a href="{{ route('terms') }}" class="inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-3xl bg-[var(--primary-soft)] text-[color:var(--primary)] text-[10px] sm:text-xs font-bold uppercase tracking-widest hover:bg-[color:var(--primary)] hover:text-white transition-all border border-[color:var(--primary)]/10">
                        Terms & Conditions
                    </a>
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-3xl border-2 border-gray-100 text-gray-900 text-[10px] sm:text-xs font-bold uppercase tracking-widest hover:bg-gray-50 transition-all">
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
