<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {!! seo()->render() !!}

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

    @php
        $routeName = request()->route() ? request()->route()->getName() : '';
        $excludedRoutes = [
            'products.show',
            'checkout',
            'checkout.store',
            'checkout.payment',
            'checkout.payment.verify',
            'checkout.success',
            'order.track',
            'order.invoice',
            'order.reorder',
            'dashboard',
            'my-orders',
            'profile.edit',
            'profile.update',
            'profile.destroy',
        ];
        $isAdmin = request()->is('admin*');
    @endphp

    @if(!in_array($routeName, $excludedRoutes) && !$isAdmin)
        <!-- WhatsApp Floating Button -->
        <a href="https://wa.me/917567776796" target="_blank" aria-label="Chat with us on WhatsApp"
            class="fixed bottom-6 left-6 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-[#25D366] text-white shadow-lg shadow-[#25D366]/40 transition-all duration-300 hover:scale-110 hover:-translate-y-1 hover:shadow-xl hover:shadow-[#25D366]/50">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </a>
    @endif

    @stack('scripts')
</body>

</html>