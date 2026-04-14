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
    <body class="font-sans antialiased bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-6 sm:py-0 px-4">
            <div class="w-full sm:max-w-md">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex items-center space-x-2">
                        <img
                            src="{{ asset('images/logo/remenant-health-logo.jpg') }}"
                            alt="{{ config('app.name', 'Remenant Health') }} logo"
                            class="h-12 w-12 rounded-lg object-contain"
                        >
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            {{ config('app.name', 'Shipping Product') }}
                        </span>
                    </a>
                </div>

                <!-- Card -->
                <div class="bg-white/80 backdrop-blur-md shadow-xl rounded-2xl border border-gray-200/50 overflow-hidden">
                    <div class="px-6 py-8">
                        {{ $slot }}
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-6">
                    <a href="/" class="text-sm text-gray-600 hover:text-blue-600 transition">
                        ← Back to Home
                    </a>
                </div>
            </div>
        </div>

        <!-- JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="{{ asset('js/guest.js') }}"></script>
    </body>
</html>
