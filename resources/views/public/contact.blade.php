@extends('public.layouts.app')

@section('title', 'Contact Us - Remenant Health')

@section('content')

    <!-- Contact Hero Section -->
    <section class="relative overflow-hidden bg-[var(--bg-main)] py-16 lg:py-24 border-b border-black/5">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="text-center">
                <span class="text-sm font-extrabold uppercase tracking-widest text-[color:var(--primary)]">Contact
                    Support</span>
                <h1 class="mt-4 text-5xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-7xl">
                    How Can We Help?
                </h1>
                <p class="mt-8 mx-auto max-w-2xl text-xl leading-relaxed text-[color:var(--text-secondary)]">
                    Whether you have a question about our products, an order, or our team is here for you.
                </p>
            </div>
        </div>

        <!-- Background Decoration -->
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-[var(--primary)]/5 blur-3xl"></div>
        <div class="absolute -left-24 -bottom-24 h-96 w-96 rounded-full bg-black/5 blur-3xl"></div>
    </section>


    <!-- Contact Form & Details Section -->
    <section class="py-24 bg-white">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-16 lg:grid-cols-12 lg:items-start">

                <!-- Left: Contact Form -->
                <div class="lg:col-span-7">
                    <div class="rounded-[3rem] bg-white p-8 sm:p-12 shadow-2xl border border-black/5">
                        <h2 class="text-3xl font-extrabold text-[color:var(--text-primary)] tracking-tight mb-8">Send Us a
                            Message</h2>

                        <form action="#" class="space-y-6">
                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-[color:var(--text-secondary)] mb-2">Full
                                    Name</label>
                                <input type="text" placeholder="Enter your full name"
                                    class="w-full rounded-2xl border-black/5 bg-gray-50 px-6 py-4 text-sm font-medium focus:border-[var(--primary)] focus:bg-white focus:ring-0 transition-all outline-none">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-[color:var(--text-secondary)] mb-2">Email
                                    Address</label>
                                <input type="email" placeholder="Enter your email address"
                                    class="w-full rounded-2xl border-black/5 bg-gray-50 px-6 py-4 text-sm font-medium focus:border-[var(--primary)] focus:bg-white focus:ring-0 transition-all outline-none">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-[color:var(--text-secondary)] mb-2">Phone
                                    Number</label>
                                <input type="tel" placeholder="Enter you phone number"
                                    class="w-full rounded-2xl border-black/5 bg-gray-50 px-6 py-4 text-sm font-medium focus:border-[var(--primary)] focus:bg-white focus:ring-0 transition-all outline-none">
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-[color:var(--text-secondary)] mb-2">Subject</label>
                                <select
                                    class="w-full rounded-2xl border-black/5 bg-gray-50 px-6 py-4 text-sm font-medium focus:border-[var(--primary)] focus:bg-white focus:ring-0 transition-all outline-none appearance-none">
                                    <option>General Inquiry</option>
                                    <option>Order Support</option>
                                    <option>Product Information</option>
                                    <option>Payment & Refunds</option>
                                    <option>Bulk Order Related</option>
                                    <option>Business Partnership</option>
                                    <option>Other</option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-black uppercase tracking-widest text-[color:var(--text-secondary)] mb-2">Message</label>
                                <textarea rows="6" placeholder="How can we help you?"
                                    class="w-full rounded-2xl border-black/5 bg-gray-50 px-6 py-4 text-sm font-medium focus:border-[var(--primary)] focus:bg-white focus:ring-0 transition-all outline-none resize-none"></textarea>
                            </div>

                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="terms"
                                    class="mt-1 h-4 w-4 rounded border-gray-300 text-[var(--primary)] focus:ring-0 focus:ring-offset-0 outline-none"
                                    required>
                                <label for="terms" class="text-sm font-medium text-gray-600">
                                    I accept the <a href="#" class="text-[var(--primary)] hover:underline">Terms and
                                        Conditions</a> and <a href="#" class="text-[var(--primary)] hover:underline">Privacy
                                        Policy</a>
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full rounded-2xl bg-black py-5 text-sm font-black uppercase tracking-[0.2em] text-white shadow-xl transition-all hover:bg-black/90 hover:-translate-y-1 active:scale-95">
                                Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right: Contact Info Card -->
                <div class="lg:col-span-5">
                    <div class="rounded-[3rem] bg-gray-50 p-10 border border-black/5 shadow-xl space-y-10">

                        <!-- Headquarters Section -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-black text-white shadow-lg">
                                    <i data-lucide="map-pin" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-black uppercase tracking-wider leading-tight">
                                        Headquarters</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Surat, India</p>
                                </div>
                            </div>
                            <address class="not-italic text-gray-600 leading-relaxed font-medium sm:pl-16 mt-2 sm:mt-0">
                                224, Ambika pinnacle, lajamani chowk,<br>
                                mota varachha, surat,<br>
                                Gujarat, India - 394101
                            </address>
                        </div>

                        <div class="h-px bg-black/5"></div>

                        <!-- WhatsApp Section -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#25D366] text-white shadow-lg">
                                    <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-[#25D366] uppercase tracking-wider leading-tight">
                                        WhatsApp</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Instant Support</p>
                                </div>
                            </div>
                            <div class="sm:pl-16 space-y-4 mt-2 sm:mt-0">
                                <p class="text-sm text-gray-600 font-medium leading-relaxed">Need help right now? Chat with
                                    our experts for instant guidance.</p>
                                <a href="https://wa.me/yournumber" target="_blank"
                                    class="inline-flex items-center justify-center rounded-xl bg-[#25D366] px-6 py-3 text-xs font-black uppercase tracking-wider text-white transition-all hover:bg-[#20bd5c] hover:-translate-y-1">
                                    Chat Now
                                </a>
                            </div>
                        </div>

                        <div class="h-px bg-black/5"></div>

                        <!-- Call Section -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-orange-600 text-white shadow-lg">
                                    <i data-lucide="phone" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-orange-600 uppercase tracking-wider leading-tight">
                                        Call Us</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Direct Support</p>
                                </div>
                            </div>
                            <div class="sm:pl-16 flex flex-col gap-2 mt-2 sm:mt-0">
                                <a href="tel:+918849550164"
                                    class="text-lg font-black text-black hover:text-orange-600 transition-colors">+91
                                    8849550164</a>
                                <a href="tel:+919662342235"
                                    class="text-lg font-black text-black hover:text-orange-600 transition-colors">+91
                                    9662342235</a>
                            </div>
                        </div>

                        <div class="h-px bg-black/5"></div>

                        <!-- Email Section -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-[var(--primary)] text-white shadow-lg">
                                    <i data-lucide="mail" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    <h3
                                        class="text-lg font-black text-[var(--primary)] uppercase tracking-wider leading-tight">
                                        Email Us</h3>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">24/7 Inquiry</p>
                                </div>
                            </div>
                            <div class="sm:pl-16 mt-2 sm:mt-0">
                                <a href="mailto:support@remenanthealth.com"
                                    class="text-lg font-black text-black hover:text-[var(--primary)] transition-colors break-all">
                                    support@remenanthealth.com
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!-- Map/Visual Section (Optional Mockup) -->
    <section class="pb-24 bg-white">
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