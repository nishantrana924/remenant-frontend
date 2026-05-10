<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('seo')

    <title>@yield('title', config('app.name', 'Remenant Health'))</title>
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
    <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
    <style>
        [x-cloak] {
            display: none !important;
        }

        #nprogress .bar {
            background: #FF6B00 !important;
            height: 3px !important;
        }

        #nprogress .spinner-icon {
            border-top-color: #FF6B00 !important;
            border-left-color: #FF6B00 !important;
        }

        /* Premium Loader Animation */
        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }
    </style>
    @stack('styles')
</head>

<body class="font-sans antialiased bg-[var(--bg-main)]">
    <!-- Global Page Loader -->
    @if(!request()->routeIs('login', 'register', 'password.*', 'verification.*', 'dashboard', 'my-orders', 'profile.*'))
        <div id="global-page-loader"
            style="display:none;"
            class="fixed inset-0 z-[9999] bg-white flex flex-col items-center justify-center transition-opacity duration-500 ease-in-out">
            <div class="relative">
                <div class="h-20 w-20 rounded-[2.5rem] border-4 border-orange-100 animate-spin-slow"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div
                        class="h-12 w-12 rounded-2xl bg-orange-500 shadow-xl shadow-orange-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="w-6 h-6 text-white animate-pulse">
                            <path d="m13 2-2 10h3L11 22l2-10h-3l2-10z" />
                        </svg>
                    </div>
                </div>
            </div>
            <p class="mt-8 text-[10px] font-black uppercase tracking-[0.5em] text-slate-400 animate-pulse">Remenant Health
            </p>
        </div>
    @endif

    <div class="min-h-screen flex flex-col">
        @include('public.layouts.header')
        @include('public.layouts.sidebar')

        <main class="flex-1" style="overflow-x: clip;" up-main>
            @yield('content')
        </main>

        @include('public.layouts.footer')
    </div>

    <!-- Scripts (Local) -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/icons.js') }}"></script>
    <script src="{{ asset('js/public-sidebar.js') }}"></script>
    <script src="{{ asset('js/public-header.js') }}"></script>
    <script src="{{ asset('js/public-account.js') }}"></script>
    <script src="{{ asset('js/global-ajax.js') }}"></script>
    <script src="{{ asset('js/product-page.js') }}"></script>

    <script>
        // 1. Initialize Icons
        window.refreshIcons = function() {
            if (window.lucide && typeof lucide.createIcons === 'function') {
                try { lucide.createIcons(); } catch (e) {}
            }
        };

        // 2. Loader: starts hidden (display:none in HTML)
        // Show it only during a real full-page navigation away
        // Immediately hide it on any page restore or Unpoly swap
        window.showLoader = function() {
            var loader = document.getElementById('global-page-loader');
            if (loader) loader.style.display = 'flex';
            if (window.NProgress) NProgress.start();
        };
        window.hideLoader = function() {
            var loader = document.getElementById('global-page-loader');
            if (loader) loader.style.display = 'none';
            if (window.NProgress) NProgress.done();
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.refreshIcons();
            if (window.NProgress) NProgress.configure({ showSpinner: false, trickleSpeed: 200 });
            // Ensure loader is always hidden when page becomes ready
            window.hideLoader();
        });

        // bfcache restore (browser back/forward button)
        window.addEventListener('pageshow', function() {
            window.hideLoader();
        });

        // Unpoly SPA navigation — hide immediately after swap
        document.addEventListener('up:location:changed', function() {
            window.hideLoader();
            window.refreshIcons();
        });

        // Only show loader on a real full-page navigation away from site
        // (Unpoly intercepts same-origin links, so beforeunload only fires
        //  when leaving to external sites or closing tab)
        window.addEventListener('beforeunload', function() {
            window.showLoader();
        });

        // 3. Link Prefetching for Speed
        window.prefetched = window.prefetched || new Set();
        document.addEventListener('mouseover', function(e) {
            var link = e.target.closest('a');
            if (link && link.href && link.origin === window.location.origin && !window.prefetched.has(link.href)) {
                var prefetchLink = document.createElement('link');
                prefetchLink.rel = 'prefetch';
                prefetchLink.href = link.href;
                document.head.appendChild(prefetchLink);
                window.prefetched.add(link.href);
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

        if (!window._lenisRafRunning) {
            window._lenisRafRunning = true;
            window._lenisRaf = function(time) {
                window.lenis.raf(time);
                requestAnimationFrame(window._lenisRaf);
            };
            requestAnimationFrame(window._lenisRaf);
        }

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

    <style>
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .custom-toast {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            width: 340px;
            padding: 16px;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: start;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            animation: toast-in 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
            border: 1px solid transparent;
            backdrop-filter: blur(8px);
        }

        @keyframes toast-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        .custom-toast.hide {
            animation: toast-out 0.5s ease forwards;
        }

        @keyframes toast-out {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(120%); opacity: 0; }
        }

        /* Error Variant (As per user request) */
        .custom-toast.error {
            background: #FCE8DB;
            border-color: rgba(239, 102, 91, 0.2);
        }
        .custom-toast.error .toast-icon path { fill: #EF665B; }
        .custom-toast.error .toast-title { color: #71192F; }
        .custom-toast.error .toast-close path { fill: #71192F; }

        /* Success Variant */
        .custom-toast.success {
            background: #F0FDF4;
            border-color: rgba(34, 197, 94, 0.2);
        }
        .custom-toast.success .toast-icon path { fill: #22C55E; }
        .custom-toast.success .toast-title { color: #166534; }
        .custom-toast.success .toast-close path { fill: #166534; }

        /* Warning/Info Variant */
        .custom-toast.warning {
            background: #FFFBEB;
            border-color: rgba(245, 158, 11, 0.2);
        }
        .custom-toast.warning .toast-icon path { fill: #F59E0B; }
        .custom-toast.warning .toast-title { color: #92400E; }
        .custom-toast.warning .toast-close path { fill: #92400E; }

        .toast-icon {
            width: 22px;
            height: 22px;
            margin-right: 12px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toast-title {
            font-weight: 600;
            font-size: 13px;
            flex-grow: 1;
            line-height: 1.4;
        }

        .toast-close {
            width: 20px;
            height: 20px;
            margin-left: 12px;
            cursor: pointer;
            transition: all 0.2s;
            opacity: 0.5;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .toast-close:hover { opacity: 1; transform: rotate(90deg); }
    </style>

    <div id="toast-wrapper" class="toast-container"></div>

    <script>
        function showToast(message, type = 'success') {
            const wrapper = document.getElementById('toast-wrapper');
            if (!wrapper) return;

            const toast = document.createElement('div');
            toast.className = `custom-toast ${type}`;

            // Define icons based on type
            let iconSvg = '';
            if (type === 'success') {
                iconSvg = '<svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="currentColor"></path></svg>';
            } else if (type === 'error') {
                iconSvg = '<svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m13 13h-2v-6h2zm0 4h-2v-2h2zm-1-15c-1.3132 0-2.61358.25866-3.82683.7612-1.21326.50255-2.31565 1.23915-3.24424 2.16773-1.87536 1.87537-2.92893 4.41891-2.92893 7.07107 0 2.6522 1.05357 5.1957 2.92893 7.0711.92859.9286 2.03098 1.6651 3.24424 2.1677 1.21325.5025 2.51363.7612 3.82683.7612 2.6522 0 5.1957-1.0536 7.0711-2.9289 1.8753-1.8754 2.9289-4.4189 2.9289-7.0711 0-1.3132-.2587-2.61358-.7612-3.82683-.5026-1.21326-1.2391-2.31565-2.1677-3.24424-.9286-.92858-2.031-1.66518-3.2443-2.16773-1.2132-.50254-2.5136-.7612-3.8268-.7612z" fill="currentColor"></path></svg>';
            } else {
                iconSvg = '<svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="currentColor"></path></svg>';
            }

            toast.innerHTML = `
                <div class="toast-icon">
                    ${iconSvg}
                </div>
                <div class="toast-title">${message}</div>
                <div class="toast-close" onclick="this.parentElement.classList.add('hide'); setTimeout(() => this.parentElement.remove(), 500)">
                    <svg height="20" viewBox="0 0 20 20" width="20" xmlns="http://www.w3.org/2000/svg"><path d="m15.8333 5.34166-1.175-1.175-4.6583 4.65834-4.65833-4.65834-1.175 1.175 4.65833 4.65834-4.65833 4.6583 1.175 1.175 4.65833-4.6583 4.6583 4.6583 1.175-1.175-4.6583-4.6583z" fill="currentColor"></path></svg>
                </div>
            `;

            wrapper.appendChild(toast);

            // Auto-remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.add('hide');
                    setTimeout(() => toast.remove(), 500);
                }
            }, 5000);
        }

        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif
    </script>

    <style>
        .premium-swal-popup {
            padding: 1.5rem 2rem !important;
            border-radius: 1.5rem !important;
            border: none !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25) !important;
        }

        .premium-swal-actions {
            width: 100% !important;
            margin-top: 1.25rem !important;
        }

        .premium-swal-confirm {
            width: 100% !important;
            background: #000000 !important;
            color: white !important;
            border-radius: 100px !important;
            padding: 1.25rem 2rem !important;
            font-size: 11px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.2em !important;
            transition: all 0.3s ease !important;
            border: none !important;
            cursor: pointer !important;
            display: block !important;
        }

        .premium-swal-confirm:hover {
            background: #1f2937 !important;
            transform: scale(1.02);
        }

        .premium-swal-confirm:active {
            transform: scale(0.98);
        }
    </style>

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'error',
                    iconColor: '#ef4444',
                    title: '<span class="text-2xl font-black uppercase tracking-tight text-gray-900">Oops!</span>',
                    text: '{{ $errors->first() }}',
                    confirmButtonText: 'Try Again',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'premium-swal-popup',
                        title: 'mb-2',
                        htmlContainer: 'text-sm font-bold text-gray-500 mb-4',
                        actions: 'premium-swal-actions',
                        confirmButton: 'premium-swal-confirm'
                    }
                });
            });
        </script>
    @endif
    <!-- Forgot Password Global Flow Components -->
    <div id="forgot-password-spinner"
        class="fixed inset-0 z-[10001] bg-white/90 backdrop-blur-sm hidden flex flex-col items-center justify-center transition-all duration-300 opacity-0">
        <div class="relative">
            <div class="h-20 w-20 rounded-[2.5rem] border-4 border-orange-100 animate-spin-slow"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div
                    class="h-12 w-12 rounded-2xl bg-orange-500 shadow-xl shadow-orange-200 flex items-center justify-center">
                    <i data-lucide="mail" class="h-6 w-6 text-white animate-pulse"></i>
                </div>
            </div>
        </div>
        <p class="mt-8 text-[10px] font-black uppercase tracking-[0.5em] text-slate-400">Sending Recovery Email...</p>
    </div>

    <script>
        async function openForgotPasswordFlow(email = null) {
            // 1. Get email if not provided
            if (!email) {
                const emailInput = document.getElementById('email');
                email = emailInput ? emailInput.value : null;
            }

            if (!email) {
                showToast('Please enter your email address first', 'error');
                return;
            }

            // 2. Show Spinner
            const spinner = document.getElementById('forgot-password-spinner');
            spinner.classList.remove('hidden');
            setTimeout(() => {
                spinner.classList.add('opacity-100');
            }, 10);

            try {
                // 3. Send AJAX Request
                const response = await fetch("{{ route('password.email') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                });

                let data;
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    data = await response.json();
                } else {
                    // Handle non-JSON response (likely an HTML error page)
                    throw new Error("Unable to send mail. Please check your internet or mail settings.");
                }

                // 4. Hide Spinner
                spinner.classList.remove('opacity-100');
                setTimeout(() => {
                    spinner.classList.add('hidden');
                }, 300);

                if (response.ok) {
                    // 5. Show Success Modal (Looks like OTP/Success)
                    Swal.fire({
                        icon: 'success',
                        iconColor: '#22c55e',
                        title: '<span class="text-2xl font-black uppercase tracking-tight text-gray-900">Link Sent!</span>',
                        html: `
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest leading-relaxed mb-6">
                                We have emailed your password reset link to <br> <span class="text-orange-500">${email}</span>
                            </p>
                            <div class="flex gap-2 justify-center mb-4">
                                ${[1, 2, 3, 4].map(() => '<div class="h-12 w-10 rounded-xl bg-slate-50 border-2 border-slate-100 flex items-center justify-center text-lg font-black text-slate-300">?</div>').join('')}
                            </div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Please check your inbox or spam folder</p>
                        `,
                        confirmButtonText: 'Got It',
                        buttonsStyling: false,
                        customClass: {
                            popup: 'premium-swal-popup',
                            title: 'mb-2',
                            htmlContainer: 'mb-4',
                            actions: 'premium-swal-actions',
                            confirmButton: 'premium-swal-confirm'
                        }
                    });
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            } catch (error) {
                // Hide Spinner on error too
                spinner.classList.remove('opacity-100');
                setTimeout(() => {
                    spinner.classList.add('hidden');
                }, 300);

                Swal.fire({
                    icon: 'error',
                    iconColor: '#ef4444',
                    title: '<span class="text-2xl font-black uppercase tracking-tight text-gray-900">Error</span>',
                    text: error.message,
                    confirmButtonText: 'Try Again',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'premium-swal-popup',
                        title: 'mb-2',
                        actions: 'premium-swal-actions',
                        confirmButton: 'premium-swal-confirm'
                    }
                });
            }
        }
    </script>
</body>

</html>