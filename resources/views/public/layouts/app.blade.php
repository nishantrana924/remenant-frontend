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
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link rel="stylesheet" href="{{ asset('plugins/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/owlcarousel/assets/owl.theme.default.min.css') }}">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --primary: #F97316;
            --primary-soft: #FFF7ED;
            --bg-main: #FFFFFF;
            --text-main: #111827;
            --text-muted: #6B7280;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-[var(--bg-main)]">
    <div class="min-h-screen flex flex-col">
        @include('public.layouts.header')
        @include('public.layouts.sidebar')

        <main class="flex-1 overflow-x-hidden">
            @yield('content')
        </main>

        @include('public.layouts.footer')
    </div>

    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{ asset('js/icons.js') }}"></script>
    <script src="{{ asset('js/public-sidebar.js') }}"></script>
    <script src="{{ asset('js/public-header.js') }}"></script>
    <script src="{{ asset('js/public-account.js') }}"></script>
    <!-- Lenis Smooth Scroll -->
    <script src="https://unpkg.com/@studio-freight/lenis@1.0.33/dist/lenis.min.js"></script>
    <script>
        const lenis = new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            smoothWheel: true,
            wheelMultiplier: 1,
            touchMultiplier: 2,
            infinite: false,
        })

        function raf(time) {
            lenis.raf(time)
            requestAnimationFrame(raf)
        }

        requestAnimationFrame(raf)

        // Anchor link scroll sync
        $(document).on('click', 'a[href^="#"]', function(e) {
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
</body>

</html>