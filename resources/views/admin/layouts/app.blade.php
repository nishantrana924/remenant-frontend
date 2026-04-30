<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Admin - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Performance & UI Progress -->
        <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Tailwind CSS -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <style>
            :root {
                --primary: #ea5f06;
                --primary-hover: #cf5305;
                --primary-soft: #fff1e8;
                --bg-main: #FFFFFF;
                --bg-light: #FDFDFD;
                --bg-section: #F8FAFC;
                --secondary: #FFFFFF;
                --text-main: #1E293B;
                --text-muted: #64748B;
                --brand-gradient: linear-gradient(135deg, #ea5f06, #FF7A1A);
                --card-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05), 0 2px 10px -2px rgba(0, 0, 0, 0.03);
                --glass-bg: rgba(255, 255, 255, 0.9);
            }

            body {
                font-family: 'Open Sans', sans-serif !important;
                background-color: var(--bg-light) !important;
                color: var(--text-main);
            }

            h1, h2, h3, h4, h5, h6 {
                font-family: 'Montserrat', sans-serif !important;
                letter-spacing: -0.01em;
                color: var(--text-main);
            }

            /* Premium Finishing */
            .glass-header {
                background: var(--glass-bg);
                backdrop-filter: blur(16px);
                border-bottom: 1px solid #F1F5F9;
            }

            .premium-card {
                background: white;
                border-radius: 20px;
                border: 1px solid #F1F5F9;
                box-shadow: var(--card-shadow);
                transition: all 0.3s ease;
            }

            .premium-card.brand-gradient {
                background: var(--brand-gradient) !important;
                border: none;
                color: white !important;
            }
            
            .premium-card.brand-gradient h2, 
            .premium-card.brand-gradient h3, 
            .premium-card.brand-gradient p {
                color: white !important;
            }

            .premium-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 12px 30px -5px rgba(0, 0, 0, 0.08);
            }

            .premium-input {
                border-radius: 12px;
                border: 1px solid #E2E8F0;
                background: #F8FAFC;
                transition: all 0.2s;
            }

            .premium-input:focus {
                background: white;
                border-color: var(--primary);
                box-shadow: 0 0 0 4px var(--primary-soft);
                outline: none;
            }

            .premium-button {
                border-radius: 12px;
                font-weight: 700;
                transition: all 0.3s;
                background: var(--primary);
                color: white;
                box-shadow: 0 4px 12px rgba(234, 95, 6, 0.15);
            }

            .premium-button:hover {
                background: var(--primary-hover);
                transform: translateY(-1px);
                box-shadow: 0 6px 15px rgba(234, 95, 6, 0.25);
            }

            /* Admin Sidebar Adjustments */
            #admin-sidebar {
                background: white !important;
                border-right: 1px solid #F1F5F9;
            }

            .nav-item {
                color: var(--text-muted);
                transition: all 0.2s;
                border-radius: 12px;
                margin: 4px 0;
            }

            .nav-item:hover {
                background: #F8FAFC;
                color: var(--primary);
            }

            .active-nav-item {
                background: var(--primary-soft) !important;
                color: var(--primary) !important;
                font-weight: 700;
            }
            
            .active-nav-item i {
                color: var(--primary) !important;
            }

            /* Custom scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
            }
            ::-webkit-scrollbar-track {
                background: transparent;
            }
            ::-webkit-scrollbar-thumb {
                background: #E2E8F0;
                border-radius: 10px;
            }
            ::-webkit-scrollbar-thumb:hover {
                background: var(--primary);
            }

            /* NProgress Custom Style */
            #nprogress .bar {
                background: var(--primary) !important;
                height: 3px !important;
            }
            #nprogress .spinner-icon {
                border-top-color: var(--primary) !important;
                border-left-color: var(--primary) !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased h-screen overflow-hidden">
        <div class="h-full bg-gray-100 flex overflow-hidden">
            <!-- Sidebar -->
            @include('admin.layouts.sidebar')

            <!-- Main Content -->
            <div class="flex-1 flex flex-col h-full overflow-hidden" id="admin-main-content">
                @include('admin.layouts.header')

                <!-- Page Content -->
                <main class="flex-1 p-6 overflow-y-auto">
                    @yield('content')
                </main>

                @include('admin.layouts.footer')
            </div>
        </div>

        <!-- JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://unpkg.com/lucide@latest"></script>
        <script>
            // Configure axios for Laravel
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            window.axios = axios;

            // --- GLOBAL FUNCTIONS FOR SPEED ---

            // 1. Initialize Icons & Progress
            document.addEventListener('DOMContentLoaded', () => {
                lucide.createIcons();
                NProgress.configure({ showSpinner: false, trickleSpeed: 200 });
            });

            // 2. Fast Navigation Feedback
            window.onbeforeunload = () => { NProgress.start(); };
            window.onload = () => { NProgress.done(); };

            // 3. Global AJAX Form Submission (Fast Send)
            async function fastSubmit(formElement, options = {}) {
                const form = typeof formElement === 'string' ? document.getElementById(formElement) : formElement;
                if (!form) return;

                const formData = new FormData(form);
                const submitBtn = form.querySelector('[type="submit"]') || form.querySelector('button:not([type="button"])');
                
                // Visual feedback
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    var originalContent = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Processing...';
                    lucide.createIcons();
                }

                NProgress.start();

                try {
                    const response = await axios({
                        method: form.method || 'POST',
                        url: form.action,
                        data: formData,
                        headers: { 'Content-Type': 'multipart/form-data' }
                    });

                    if (options.success) {
                        options.success(response.data);
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.data.message || 'Data saved successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            if (response.data.redirect) window.location.href = response.data.redirect;
                        });
                    }
                } catch (error) {
                    console.error('Submission error:', error);
                    let message = 'Something went wrong. Please try again.';
                    if (error.response && error.response.data.message) message = error.response.data.message;
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message
                    });
                } finally {
                    NProgress.done();
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        submitBtn.innerHTML = originalContent;
                        lucide.createIcons();
                    }
                }
            }

            // 4. Link Prefetching (Experimental for speed)
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
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>

