<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Remenant Health'))</title>
    <link rel="icon" href="{{ asset('images/logo/remenant-health-favicon.jpg') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body class="font-sans antialiased bg-[var(--bg-main)] overflow-x-hidden">
    <div class="min-h-screen flex flex-col">
        @include('public.layouts.header')
        @include('public.layouts.sidebar')

        <main class="flex-1">
            @yield('content')
        </main>

        @include('public.layouts.footer')
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="{{ asset('js/icons.js') }}"></script>
    <script src="{{ asset('js/public-sidebar.js') }}"></script>
    <script src="{{ asset('js/public-header.js') }}"></script>
    <script src="{{ asset('js/public-account.js') }}"></script>
    <script src="{{ asset('js/public-hero-slider.js') }}"></script>
    <script src="{{ asset('js/public-combo-slider.js') }}"></script>
    <script src="{{ asset('js/public-category-slider.js') }}"></script>
    @stack('scripts')
</body>
</html>

