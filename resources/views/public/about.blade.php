@extends('public.layouts.app')

@section('title', 'About Us - Remenant Health')

@section('content')


    <!-- about section  -->
    <section class="relative overflow-hidden bg-[var(--bg-main)] py-6 lg:py-10">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-16 lg:grid-cols-2 lg:items-center">
                <div>
                    <span class="text-sm font-extrabold uppercase tracking-widest text-[color:var(--primary)]">Our
                        Philosophy</span>
                    <h1 class="mt-4 text-5xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-7xl">
                        Where Science <br> Meets Engineering
                    </h1>
                    <p class="mt-8 text-xl leading-relaxed text-[color:var(--text-secondary)]">
                        REMENANT is where medical science meets advanced engineering. We didn't just want to create
                        supplements; we wanted to create a gold standard in daily nutrition.
                    </p>
                </div>
                <div class="relative">
                    <div class="aspect-square overflow-hidden rounded-[3rem] bg-[var(--bg-section)]">
                        <img src="{{ asset('images/home/remenant-bg2.jpg') }}" alt="Advanced Engineering"
                            class="h-full w-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brand Philosophy Detailed -->
    <section class="bg-black py-24 text-white overflow-hidden">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold tracking-tight sm:text-5xl">The Gold Standard</h2>
                <div class="mt-12 grid grid-cols-1 gap-12 sm:grid-cols-3">
                    <div class="flex flex-col items-center gap-6 p-8 rounded-3xl bg-white/5 border border-white/10">
                        <div class="h-14 w-14 rounded-2xl bg-[var(--primary)] flex items-center justify-center">
                            <i data-lucide="zap" class="h-8 w-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold">Effervescent Tech</h3>
                        <p class="text-sm text-white/60 leading-relaxed">By focusing on Effervescent Technology, we ensure
                            maximum bioavailability and faster absorption for every single nutrient.</p>
                    </div>
                    <div class="flex flex-col items-center gap-6 p-8 rounded-3xl bg-white/5 border border-white/10">
                        <div class="h-14 w-14 rounded-2xl bg-[var(--primary)] flex items-center justify-center">
                            <i data-lucide="shield-check" class="h-8 w-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold">Pure & Potent</h3>
                        <p class="text-sm text-white/60 leading-relaxed">Our chemical engineering expertise allows us to
                            master the stability and quality of tablets, ensuring every sip is precise.</p>
                    </div>
                    <div class="flex flex-col items-center gap-6 p-8 rounded-3xl bg-white/5 border border-white/10">
                        <div class="h-14 w-14 rounded-2xl bg-[var(--primary)] flex items-center justify-center">
                            <i data-lucide="microscope" class="h-8 w-8 text-white"></i>
                        </div>
                        <h3 class="text-xl font-bold">Effortless Health</h3>
                        <p class="text-sm text-white/60 leading-relaxed">We believe that staying healthy should be
                            effortless, scientific, and transparent. No more boring pills.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission Redesign -->
    <section class="py-12 lg:py-24 bg-white">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <!-- Phase 1: Our Vision -->
            <div class="relative flex flex-col lg:flex-row items-center gap-12 lg:gap-0">
                <div class="hidden lg:block w-full lg:w-[60%] relative group">
                    <div class="aspect-[16/9] overflow-hidden rounded-[3rem] bg-[var(--bg-section)] shadow-2xl">
                        <img src="{{ asset('images/banners/remenant-bg13.jpg') }}" alt="Our Vision" class="h-full w-full object-cover transition duration-1000 group-hover:scale-105">
                    </div>
                </div>
                <div class="w-full lg:w-[45%] lg:-ml-24 relative z-10 p-6 sm:p-10 lg:p-16 rounded-[3rem] bg-white border border-black/5 shadow-2xl backdrop-blur-xl">
                    <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.3em] text-[var(--primary)] mb-6">
                        <span class="h-px w-8 bg-[var(--primary)]"></span>
                        Our Vision
                    </span>
                    <h2 class="text-4xl font-extrabold text-[color:var(--text-primary)] leading-tight">Revolutionizing <br> Modern Longevity</h2>
                    <p class="mt-8 text-lg leading-relaxed text-[color:var(--text-secondary)] font-medium">To be the global benchmark for bioavailable nutrition, ensuring that every individual has access to the most advanced, science-backed wellness solutions for a life without limits.</p>
                </div>
            </div>

            <!-- Phase 2: Our Mission -->
            <div class="relative flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-0 mt-12 lg:mt-32">
                <div class="hidden lg:block w-full lg:w-[60%] relative group">
                    <div class="aspect-[16/9] overflow-hidden rounded-[3rem] bg-[var(--bg-section)] shadow-2xl">
                        <img src="{{ asset('images/banners/remenant-mission.jpg') }}" alt="Our Mission" class="h-full w-full object-cover transition duration-1000 group-hover:scale-105">
                    </div>
                </div>
                <div class="w-full lg:w-[45%] lg:-mr-24 relative z-10 p-6 sm:p-10 lg:p-16 rounded-[3rem] bg-black text-white shadow-2xl">
                    <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.3em] text-[var(--primary)] mb-6">
                        <span class="h-px w-8 bg-[var(--primary)]"></span>
                        Our Mission
                    </span>
                    <h2 class="text-4xl font-extrabold leading-tight">Engineering <br> Daily Excellence</h2>
                    <p class="mt-8 text-lg leading-relaxed text-white/70 font-medium">We are on a mission to simplify health by combining medical precision with engineering brilliance. We create supplements that are not just effective, but a joy to consume every single day.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- The REMENANT Process -->
    <section class="py-24 bg-[#F0F4F3] border-y border-black/5">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="text-center mb-20">
                <span class="text-sm font-extrabold uppercase tracking-widest text-[color:var(--primary)]">How It's
                    Made</span>
                <h2 class="mt-4 text-4xl font-extrabold text-[color:var(--text-primary)]">The Gold Standard Process</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="relative flex flex-col p-8 rounded-[2.5rem] bg-gradient-to-br from-white to-[#E8EEF2] border border-black/5 shadow-xl shadow-black/[0.03] h-full transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:border-black/20">
                    <div class="relative mb-6 aspect-video overflow-hidden rounded-2xl bg-gray-100">
                        <img src="{{ asset('images/banners/remenant-bg5.jpg') }}" alt="Ethical Sourcing" class="h-full w-full object-cover">
                    </div>
                    <div class="absolute -top-6 left-10 h-14 w-14 rounded-2xl bg-black text-white flex items-center justify-center font-black text-2xl shadow-lg ring-4 ring-white">01</div>
                    <h3 class="mt-4 text-2xl font-extrabold text-[color:var(--text-primary)]">Ethical Sourcing</h3>
                    <p class="mt-4 text-[color:var(--text-secondary)] leading-relaxed text-sm font-medium">We only partner with suppliers who meet our rigorous purity standards. Every nutrient is verified for grade and potency before entering our clinical-standard facility.</p>
                </div>
                <!-- Step 2 -->
                <div class="relative flex flex-col p-8 rounded-[2.5rem] bg-[#FFF1E8] border border-[var(--primary)]/10 shadow-xl shadow-black/[0.03] h-full transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:border-[var(--primary)]/20">
                    <div class="relative mb-6 aspect-video overflow-hidden rounded-2xl bg-gray-100">
                        <img src="{{ asset('images/banners/remenant-bg2.jpg') }}" alt="Precision Engineering" class="h-full w-full object-cover">
                    </div>
                    <div class="absolute -top-6 left-10 h-14 w-14 rounded-2xl bg-[var(--primary)] text-white flex items-center justify-center font-black text-2xl shadow-lg ring-4 ring-white">02</div>
                    <h3 class="mt-4 text-2xl font-extrabold text-[color:var(--text-primary)]">Precision Engineering</h3>
                    <p class="mt-4 text-[color:var(--text-secondary)] leading-relaxed text-sm font-medium">Our Chemical Engineers optimize tablet stability and solubility. Using Effervescent technology, we ensure nutrients are protected until they hit your glass.</p>
                </div>
                <!-- Step 3 -->
                <div class="relative flex flex-col p-8 rounded-[2.5rem] bg-gradient-to-br from-white to-[#E6F3ED] border border-[#0FA47B]/10 shadow-xl shadow-black/[0.03] h-full transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:border-[#0FA47B]/30">
                    <div class="relative mb-6 aspect-video overflow-hidden rounded-2xl bg-gray-100">
                        <img src="{{ asset('images/banners/remenant-bg12.jpg') }}" alt="Double-Blind Testing" class="h-full w-full object-cover">
                    </div>
                    <div class="absolute -top-6 left-10 h-14 w-14 rounded-2xl bg-[#074D3D] text-white flex items-center justify-center font-black text-2xl shadow-lg ring-4 ring-white">03</div>
                    <h3 class="mt-4 text-2xl font-extrabold text-[color:var(--text-primary)]">Double-Blind Testing</h3>
                    <p class="mt-4 text-[color:var(--text-secondary)] leading-relaxed text-sm font-medium">Every batch undergoes third-party lab testing to guarantee potency and safety. If it doesn't meet the gold standard, it doesn't leave our doors.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet the Founders -->
    <section class="bg-[var(--bg-main)] py-16 lg:py-24">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="text-center mb-12 lg:mb-16">
                <span class="text-sm font-extrabold uppercase tracking-widest text-[color:var(--primary)]">The Power
                    Duo</span>
                <h2 class="mt-4 text-4xl font-extrabold tracking-tight text-[color:var(--text-primary)] sm:text-5xl">Meet
                    the Founders</h2>
                <p class="mt-6 mx-auto max-w-2xl text-lg text-[color:var(--text-secondary)]">Combining medical expertise
                    with technical brilliance to revolutionize modern wellness.</p>
            </div>

            <div class="space-y-16 lg:space-y-24">
                <!-- Founder 1: Dr. Jimmy Thummar -->
                <div class="flex flex-col lg:flex-row items-center gap-8 lg:gap-16">
                    <div class="w-full lg:w-[45%] group">
                        <div
                            class="relative aspect-[4/5] overflow-hidden rounded-[3rem] bg-[#FDF9F6] border border-black/5 shadow-2xl transition-transform duration-700 group-hover:scale-[1.02]">
                            <img src="{{ asset('images/logo/remenant-health-logo.png') }}" alt="Dr. Jimmy Thummar"
                                class="h-full w-full object-contain p-16 transition duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-[55%] section-reveal">
                        <div
                            class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-black text-white text-xs font-black uppercase tracking-widest mb-6">
                            <i data-lucide="award" class="h-4 w-4"></i>
                            Founder & Visionary
                        </div>
                        <h3 class="text-4xl font-extrabold text-[color:var(--text-primary)] sm:text-5xl">Dr. Jimmy Thummar
                        </h3>
                        <p class="mt-2 text-xl font-bold text-[color:var(--primary)]">MBBS</p>

                        <div class="mt-8 space-y-6 text-lg leading-relaxed text-[color:var(--text-secondary)]">
                            <p>With a solid foundation in medicine, Dr. Thummar is the driving force behind REMENANT. His
                                medical expertise ensures that every product is designed to fulfill the body’s actual
                                nutritional gaps.</p>
                            <p>As the visionary, he ensures that the brand stays committed to health safety and clinical
                                efficacy, bridging the gap between clinical needs and daily lifestyle.</p>
                        </div>
                    </div>
                </div>

                <!-- Founder 2: Het Lakhani -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-8 lg:gap-16">
                    <div class="w-full lg:w-[45%] group">
                        <div
                            class="relative aspect-[4/5] overflow-hidden rounded-[3rem] bg-[var(--bg-section)] border border-black/5 shadow-2xl transition-transform duration-700 group-hover:scale-[1.02]">
                            <img src="{{ asset('images/logo/remenant-health-logo.png') }}" alt="Het Lakhani"
                                class="h-full w-full object-contain p-16 transition duration-700 group-hover:scale-110">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-[55%] section-reveal">
                        <div
                            class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-[var(--primary)] text-white text-xs font-black uppercase tracking-widest mb-6">
                            <i data-lucide="settings" class="h-4 w-4"></i>
                            Co-Founder & Operations
                        </div>
                        <h3 class="text-4xl font-extrabold text-[color:var(--text-primary)] sm:text-5xl">Het Lakhani</h3>
                        <p class="mt-2 text-xl font-bold text-[color:var(--primary)] uppercase tracking-wider">Chemical
                            Engineer</p>

                        <div class="mt-8 space-y-6 text-lg leading-relaxed text-[color:var(--text-secondary)]">
                            <p>Bringing technical brilliance to the brand, Het Lakhani oversees the complex formulation and
                                manufacturing processes.</p>
                            <p>His background in chemical engineering allows REMENANT to master the stability and quality of
                                effervescent tablets, ensuring that every batch is pure, potent, and precise.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust & Certifications Section -->
    <section class="bg-[#F5F8F7] py-16 sm:py-24 border-y border-black/5 overflow-hidden">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-[color:var(--text-primary)]">Trust & Quality Guaranteed</h2>
                <p class="mt-4 text-lg text-[color:var(--text-secondary)]">We adhere to the highest international standards
                    to ensure every REMENANT product is pure and effective.</p>
            </div>
        </div>

        <div class="marquee select-none">
            <div class="marquee__track gap-12 sm:gap-24 px-12">
                @php
                    $certs = [
                        ['id' => 'iso', 'name' => 'ISO Certified', 'desc' => 'Quality Management'],
                        ['id' => 'haccp', 'name' => 'HACCP', 'desc' => 'Food Safety'],
                        ['id' => 'gmp', 'name' => 'GMP Consistent', 'desc' => 'Manufacturing Practice'],
                        ['id' => 'fda', 'name' => 'FDA Registered', 'desc' => 'Facility Standard'],
                        ['id' => 'kosher', 'name' => 'Kosher', 'desc' => 'Certified Quality'],
                    ];
                @endphp

                @foreach(range(1, 4) as $iteration)
                    @foreach ($certs as $cert)
                        <div class="flex flex-col items-center shrink-0 w-[180px]">
                            <div class="mb-4 flex h-20 w-20 items-center justify-center transition-transform hover:scale-110">
                                <img src="{{ asset('images/icons/' . $cert['id'] . '.png') }}" alt="{{ $cert['name'] }}"
                                    class="h-full w-full object-contain">
                            </div>
                            <h3 class="text-sm font-black uppercase tracking-widest text-[color:var(--text-primary)] text-center">
                                {{ $cert['name'] }}</h3>
                            <p
                                class="mt-1 text-[10px] font-bold text-[color:var(--text-secondary)] uppercase text-center whitespace-normal">
                                {{ $cert['desc'] }}</p>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Remenant Banner (Short Height - Customized for About) -->
    <section class="relative overflow-visible my-8 lg:my-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="relative rounded-[2rem] bg-[var(--bg-peach)] px-8 pt-10 pb-0 sm:px-16 sm:pt-16 sm:pb-16 lg:py-16 border border-white/10 shadow-2xl">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center">

                    <!-- Content Right (Text) -->
                    <div class="relative z-20 order-1 lg:order-2 text-center lg:text-left">
                        <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                            Ready to Upgrade Your Health?
                        </h2>
                        <p class="mt-4 text-base text-white/90 max-w-lg mx-auto lg:mx-0">
                            Experience the ultimate in bioavailability and convenience. Our science-backed formulations are designed to seamlessly integrate into your modern lifestyle.
                        </p>

                        <div class="mt-10 flex flex-wrap justify-center lg:justify-start gap-4">
                            <!-- WhatsApp Button -->
                            <a href="https://wa.me/yournumber" target="_blank"
                                class="inline-flex items-center gap-3 rounded-[20px] bg-[#25D366] px-8 py-4 text-white hover:opacity-90 transition shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <svg class="h-6 w-6 fill-white" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                <div class="text-left">
                                    <p class="text-[10px] uppercase opacity-70 leading-none font-bold tracking-wider text-white">Chat
                                        with us</p>
                                    <p class="text-base font-extrabold leading-tight text-white">WhatsApp</p>
                                </div>
                            </a>
                            <!-- Shop Button -->
                            <a href="/#shop"
                                class="inline-flex items-center gap-3 rounded-[20px] bg-black px-8 py-4 text-white hover:bg-black/90 transition shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <i data-lucide="shopping-bag" class="h-6 w-6"></i>
                                <div class="text-left">
                                    <p class="text-[10px] uppercase opacity-70 leading-none font-bold tracking-wider">Available now</p>
                                    <p class="text-base font-extrabold leading-tight">Shop All Products</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Mockup Left (Image) -->
                    <div class="order-2 lg:order-1 flex justify-center lg:block">
                        <div
                            class="lg:absolute relative bottom-0 left-0 w-full lg:w-[480px] flex justify-center lg:justify-start pointer-events-none">
                            <img src="{{ asset('images/home/remenant-bg1.png') }}" alt="Upgrade Your Health"
                                class="w-[320px] sm:w-[380px] lg:w-full h-auto block">
                        </div>
                    </div>
                </div>

                <!-- Decorative Circles -->
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-black/5 blur-3xl"></div>
            </div>
        </div>
    </section>
@endsection