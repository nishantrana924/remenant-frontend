<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('seo')

    <title>@yield('title', config('app.name', 'Remenant Health'))</title>
    <link rel="icon" href="{{ asset('images/logo/remenant-health-favicon.jpg') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Open+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Owl Carousel CSS (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/owl-carousel/owl.theme.default.min.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/global-styles.css') }}">
    <style>
        :root {
            --primary: #F97316;
            --primary-soft: #FFF7ED;
            --bg-main: #FFFFFF;
            --text-main: #111827;
            --text-muted: #6B7280;
        }
    </style>
    <!-- Performance & UI Progress -->
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] { display: none !important; }
        #nprogress .bar { background: #FF6B00 !important; height: 3px !important; }
        #nprogress .spinner-icon { border-top-color: #FF6B00 !important; border-left-color: #FF6B00 !important; }
        
        /* Premium Loader Animation */
        @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .animate-spin-slow { animation: spin-slow 3s linear infinite; }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-[var(--bg-main)]">
    <!-- Global Page Loader -->
    <div id="global-page-loader" class="fixed inset-0 z-[9999] bg-white flex flex-col items-center justify-center transition-all duration-700 ease-in-out">
        <div class="relative">
            <div class="h-20 w-20 rounded-[2.5rem] border-4 border-orange-100 animate-spin-slow"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="h-12 w-12 rounded-2xl bg-orange-500 shadow-xl shadow-orange-200 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-white animate-pulse"><path d="m13 2-2 10h3L11 22l2-10h-3l2-10z"/></svg>
                </div>
            </div>
        </div>
        <p class="mt-8 text-[10px] font-black uppercase tracking-[0.5em] text-slate-400 animate-pulse">Remenant Health</p>
    </div>

    <div class="min-h-screen flex flex-col">
        @include('public.layouts.header')
        @include('public.layouts.sidebar')

        <main class="flex-1" style="overflow-x: clip;">
            @yield('content')
        </main>

        @include('public.layouts.footer')
    </div>

    <!-- Scripts (Local) -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{ asset('js/icons.js') }}"></script>
    <script src="{{ asset('js/public-sidebar.js') }}"></script>
    <script src="{{ asset('js/public-header.js') }}"></script>
    <script src="{{ asset('js/public-account.js') }}"></script>
    <script src="{{ asset('js/global-ajax.js') }}"></script>
    
    <script>
        // 1. Initialize Icons & Progress
        document.addEventListener('DOMContentLoaded', () => {
            if(window.lucide) lucide.createIcons();
            NProgress.configure({ showSpinner: false, trickleSpeed: 200 });
        });

        // 2. Fast Navigation Feedback
        window.onbeforeunload = () => { NProgress.start(); };
        window.onload = () => { 
            NProgress.done(); 
            const loader = document.getElementById('global-page-loader');
            if (loader) {
                loader.style.opacity = '0';
                loader.style.pointerEvents = 'none';
                setTimeout(() => loader.style.display = 'none', 700);
            }
        };

        // 3. Link Prefetching for Speed
        const prefetched = new Set();
        document.addEventListener('mouseover', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && link.origin === window.location.origin && !prefetched.has(link.href)) {
                const prefetchLink = document.createElement('link');
                prefetchLink.rel = 'prefetch';
                prefetchLink.href = link.href;
                document.head.appendChild(prefetchLink);
                prefetched.add(link.href);
            }
        });
    </script>

    <!-- Lenis Smooth Scroll -->
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.33/dist/lenis.min.js"></script>
    <script>
        window.lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            smoothWheel: true,
            wheelMultiplier: 1,
            touchMultiplier: 2,
            infinite: false,
        })

        function raf(time) {
            window.lenis.raf(time)
            requestAnimationFrame(raf)
        }

        requestAnimationFrame(raf)

        // Anchor link scroll sync
        $(document).on('click', 'a[href^="#"]', function (e) {
            const target = this.getAttribute('href');
            if (target && target !== '#') {
                const element = document.querySelector(target);
                if (element) {
                    e.preventDefault();
                    lenis.scrollTo(target);
                }
            }
        });
    </script>
    @stack('scripts')

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    icon: 'success',
                    title: "{{ session('success') }}",
                    customClass: { popup: 'rounded-2xl' }
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Failed',
                    html: `<div class="text-left bg-rose-50 p-4 rounded-2xl border border-rose-100 mt-4">
                        <ul class="text-xs text-rose-600 space-y-1 list-disc pl-4 font-bold">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>`,
                    confirmButtonColor: '#F97316',
                    customClass: {
                        popup: 'rounded-[2rem]',
                        confirmButton: 'rounded-xl px-6 py-3 font-bold uppercase tracking-widest text-xs'
                    }
                });
            });
        </script>
    @endif
</body>

</html>