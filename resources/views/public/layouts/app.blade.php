<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('seo')

    <title>@yield('title', config('app.name', 'Remenant Health'))</title>
    <meta name="layout" content="public">
    <link rel="icon" href="{{ \App\Helpers\ImageHelper::getUrl('logo/remenant-health-favicon.jpg', 'images') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&family=Open+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Owl Carousel CSS (Local) -->
    <link rel="stylesheet" href="{{ asset('assets/owl-carousel/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/owl-carousel/owl.theme.default.min.css') }}">

    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/unpoly@3.14.3/unpoly.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/unpoly@3.14.3/unpoly.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/owl-carousel/owl.carousel.min.js') }}"></script>
    <!-- Performance & UI Progress -->
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
</head>

<body class="font-sans antialiased bg-white">
    <div id="toast-wrapper" class="toast-container"></div>

    @include('public.layouts.header')
    @include('public.layouts.sidebar')

    <main id="main-content" up-main>
        @yield('content')
    </main>

    @include('public.layouts.footer')

    <!-- Pre-footer scripts -->
    <script>
        // --- NATIVE SIDEBAR TOGGLE (Defined globally for immediate access) ---
        window.togglePublicSidebar = function(show) {
            if (typeof show === 'undefined') show = true;
            var overlay = document.querySelector('[data-sidebar-overlay]');
            var panel = document.querySelector('[data-sidebar-panel]');
            if (!overlay || !panel) return;

            if (show) {
                overlay.classList.remove('hidden');
                overlay.offsetHeight;
                setTimeout(function() {
                    overlay.classList.remove('opacity-0');
                    panel.classList.remove('-translate-x-full');
                }, 10);
            } else {
                overlay.classList.add('opacity-0');
                panel.classList.add('-translate-x-full');
                setTimeout(function() {
                    overlay.classList.add('hidden');
                }, 300);
            }
        };

        // Global Toast System
        function showToast(message, type = 'success') {
            const wrapper = document.getElementById('toast-wrapper');
            const toast = document.createElement('div');
            toast.className = `custom-toast toast-${type}`;

            const icon = type === 'success' ? 'check-circle' : 'alert-circle';

            toast.innerHTML = `
                <div class="toast-icon">
                    <i data-lucide="${icon}" class="w-5 h-5"></i>
                </div>
                <div class="toast-content">
                    <div class="toast-title">${type === 'success' ? 'Success' : 'Error'}</div>
                    <div class="toast-message">${message}</div>
                </div>
            `;

            wrapper.appendChild(toast);
            lucide.createIcons();

            setTimeout(() => toast.classList.add('show'), 10);

            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 400);
            }, 4000);
        }

        // NProgress for Unpoly
        document.addEventListener('up:link:follow', () => NProgress.start());
        document.addEventListener('up:fragment:inserted', () => {
            NProgress.done();
            lucide.createIcons();
        });
        
        // CKEditor Sync and Button Loader
        document.addEventListener('submit', (e) => {
            const form = e.target;
            
            // 1. Sync CKEditor if exists
            if (window.CKEDITOR) {
                for (let instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
            }

            // 2. Button Loader
            const submitBtn = e.submitter || form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.hasAttribute('no-loader')) {
                const originalContent = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                submitBtn.innerHTML = '<span class="inline-block h-4 w-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Processing...';
                
                // Fallback to restore button if submission hangs
                setTimeout(() => {
                    if (submitBtn.disabled) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.innerHTML = originalContent;
                    }
                }, 10000);
            }
        });
    </script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));
    </script>
    @endif

    @stack('scripts')
</body>

</html>