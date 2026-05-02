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
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Performance & UI Progress -->
        <script src="https://unpkg.com/nprogress@0.2.0/nprogress.js"></script>
        <link rel="stylesheet" href="https://unpkg.com/nprogress@0.2.0/nprogress.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- FilePond -->
        <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
        
        <!-- Select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            orange: {
                                50: '#FFF7ED',
                                100: '#FFEDD5',
                                500: '#F97316',
                                600: '#EA580C',
                            }
                        }
                    }
                }
            }
        </script>

        <!-- CSS Styles -->
        <style>
            :root {
                --primary: #F97316;
                --primary-hover: #EA580C;
                --primary-soft: #FFF7ED;
                --bg-main: #FFFFFF;
                --bg-sidebar: #FFFFFF;
                --text-main: #111827;
                --text-muted: #6B7280;
                --border-color: #E5E7EB;
                --radius: 12px;
                --radius-inner: 8px;
                --shadow-soft: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                --shadow-hover: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            }

            body {
                font-family: 'Inter', sans-serif !important;
                background-color: var(--bg-main) !important;
                color: var(--text-main);
            }

            /* Clean Card Style */
            .saas-card {
                background: white;
                border: 1px solid var(--border-color);
                border-radius: var(--radius);
                padding: 20px;
                transition: all 0.2s;
            }

            .saas-card:hover {
                box-shadow: var(--shadow-hover);
                transform: translateY(-2px);
            }

            /* Minimal Form Elements */
            .saas-label {
                display: block;
                font-size: 14px;
                font-weight: 500;
                color: var(--text-muted);
                margin-bottom: 6px;
            }

            .saas-input {
                width: 100%;
                height: 40px;
                padding: 10px 12px;
                border: 1px solid var(--border-color);
                border-radius: var(--radius-inner);
                font-size: 14px;
                color: var(--text-main);
                transition: all 0.2s;
            }

            .saas-input:focus {
                border-color: var(--primary);
                outline: none;
                box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
            }

            .saas-btn-primary {
                background: var(--primary);
                color: white;
                padding: 10px 16px;
                border-radius: var(--radius-inner);
                font-weight: 600;
                font-size: 14px;
                transition: all 0.2s;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .saas-btn-primary:hover {
                background: var(--primary-hover);
                transform: scale(1.02);
            }

            .saas-btn-secondary {
                background: white;
                border: 1px solid var(--border-color);
                color: var(--text-main);
                padding: 10px 16px;
                border-radius: var(--radius-inner);
                font-weight: 600;
                font-size: 14px;
                transition: all 0.2s;
            }

            .saas-btn-secondary:hover {
                background: var(--primary-soft);
                border-color: var(--primary);
            }

            /* Minimal Table */
            .saas-table {
                width: 100%;
                border-collapse: separate;
                border-spacing: 0;
            }

            .saas-table th {
                background: #F9FAFB;
                padding: 12px 16px;
                font-size: 12px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: var(--text-muted);
                border-bottom: 1px solid var(--border-color);
            }

            .saas-table td {
                padding: 14px 16px;
                font-size: 14px;
                border-bottom: 1px solid #F3F4F6;
                transition: background 0.2s;
            }

            .saas-table tr:hover td {
                background: var(--primary-soft);
            }

            /* Nav Item Overhaul */
            .nav-item {
                display: flex;
                align-items: center;
                padding: 10px 16px;
                color: var(--text-muted);
                font-weight: 500;
                font-size: 14px;
                border-radius: 8px;
                transition: all 0.2s;
                margin: 2px 0;
            }

            .nav-item:hover {
                background: var(--primary-soft);
                color: var(--primary);
            }

            .active-nav-item {
                background: var(--primary-soft) !important;
                color: var(--primary) !important;
                font-weight: 600;
            }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #E5E7EB; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--primary); }

            /* NProgress Custom Style */
            #nprogress .bar {
                background: var(--primary) !important;
                height: 3px !important;
            }
        </style>
    </head>
    <body class="font-sans antialiased h-screen overflow-hidden">
        <div class="h-full bg-white flex overflow-hidden">
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
        
        <!-- FilePond -->
        <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
        
        <!-- SortableJS -->
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Select2 -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <!-- CKEditor 5 -->
        <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
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

