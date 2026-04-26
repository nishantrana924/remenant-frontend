<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Shipping Product') }}</title>
        <link rel="icon" href="{{ asset('images/logo/remenant-health-favicon.jpg') }}" type="image/jpeg">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Tailwind CSS -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body class="font-sans antialiased bg-[color:var(--bg-primary)] text-[color:var(--text-primary)]">
        <!-- Dynamic Background -->
        <div class="fixed inset-0 -z-10 overflow-hidden">
            <div class="absolute -top-[10%] -left-[10%] h-[40%] w-[40%] rounded-full bg-[color:var(--primary)]/5 blur-[120px]"></div>
            <div class="absolute top-[20%] -right-[5%] h-[35%] w-[35%] rounded-full bg-[color:var(--secondary)]/5 blur-[100px]"></div>
            <div class="absolute -bottom-[10%] left-[20%] h-[30%] w-[30%] rounded-full bg-[color:var(--primary)]/5 blur-[80px]"></div>
        </div>

        <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <div class="w-full max-w-[480px]">
                <!-- Logo Section -->
                <div class="mb-12 text-center">
                    <a href="/" class="group inline-block transition-transform hover:scale-105">
                        <img
                            src="{{ asset('images/logo/remenant-health-logo.png') }}"
                            alt="{{ config('app.name', 'Remenant Health') }} logo"
                            class="mx-auto h-20 w-auto object-contain drop-shadow-2xl"
                        >
                    </a>
                </div>

                <!-- Main Card -->
                <div class="overflow-hidden rounded-[3rem] bg-white p-8 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)] ring-1 ring-black/5 sm:p-12">
                    {{ $slot }}
                </div>

                <!-- Footer Links -->
                <div class="mt-12 flex items-center justify-center gap-8">
                    <a href="/" class="group flex items-center gap-2 text-xs font-black uppercase tracking-widest text-gray-400 transition hover:text-[color:var(--primary)]">
                        <i data-lucide="arrow-left" class="h-4 w-4 transition-transform group-hover:-translate-x-1"></i>
                        Back to Home
                    </a>
                    <span class="h-1 w-1 rounded-full bg-gray-200"></span>
                    <a href="{{ route('about') }}" class="text-xs font-black uppercase tracking-widest text-gray-400 transition hover:text-[color:var(--primary)]">
                        Support
                    </a>
                </div>
            </div>
        </div>

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            lucide.createIcons();
        </script>
        
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </body>

        <!-- JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="{{ asset('js/guest.js') }}"></script>
    </body>
</html>
