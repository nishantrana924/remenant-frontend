<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Laravel') }}</title>
    <meta name="layout" content="admin">
    <link rel="icon" href="{{ asset('images/logo/remenant-health-favicon.jpg') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Third Party Libraries (Head) -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FilePond -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Unpoly SPA (Required for smooth transitions) -->
    <script src="https://unpkg.com/unpoly@3.14.3/unpoly.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/unpoly@3.14.3/unpoly.css">

    <!-- Assets -->
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')

</head>

<body class="font-sans antialiased bg-white h-screen overflow-hidden flex flex-col">

    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden relative" id="admin-main-content">
            @include('admin.layouts.header')

            <!-- Page Loader (Limited to Content Area) -->
            <div id="page-loader" class="absolute inset-0 top-16 z-[80] bg-white/60 backdrop-blur-sm flex items-center justify-center transition-all duration-300 opacity-0 pointer-events-none">
                <div class="flex flex-col items-center gap-4">
                    <div class="w-12 h-12 border-4 border-slate-100 border-t-orange-500 rounded-full animate-spin"></div>
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 animate-pulse">Loading...</p>
                </div>
            </div>

            <!-- Page Content -->
            <main id="main-content" class="flex-1 p-4 sm:p-6 overflow-y-auto" up-main>
                <div class="min-h-[70vh]">
                    @yield('content')
                </div>
                @include('admin.layouts.footer')
            </main>
        </div>
    </div>

    <div id="toast-wrapper" class="toast-container"></div>

    <script>
        // Unpoly Loader Logic
        document.addEventListener('up:request:load', (e) => {
            // Only show loader for major page transitions (main content updates)
            if (e.request.target.includes('#main-content')) {
                document.getElementById('page-loader').classList.remove('opacity-0', 'pointer-events-none');
            }
        });

        document.addEventListener('up:fragment:inserted', () => {
            document.getElementById('page-loader').classList.add('opacity-0', 'pointer-events-none');
        });
    </script>

    <!-- External JavaScript (Deferred) -->
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>

    <script>
        // Configure axios for Laravel
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        window.axios = axios;

        // 1. Initialize Icons Globally & for SPA transitions
        window.refreshIcons = () => {
            if (window.lucide && typeof lucide.createIcons === 'function') {
                try {
                    lucide.createIcons();
                } catch (e) {
                    console.warn('Lucide icon initialization failed:', e);
                }
            }
        };
        document.addEventListener('DOMContentLoaded', refreshIcons);
        document.addEventListener('up:fragment:inserted', refreshIcons);

        // 1.1 Global Toast Helper (Using SweetAlert2)
        const SwalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl shadow-lg border border-slate-100',
                title: 'text-sm font-bold text-slate-800'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        window.showToast = function(message, type = 'success') {
            SwalToast.fire({
                icon: type,
                title: message
            });
        };

        window.toast = (title, icon = 'success') => {
            window.showToast(title, icon);
        };

        window.confirmAction = (title, text, callback) => {
            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#FF6B00',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'rounded-xl px-6 py-3 font-bold',
                    cancelButton: 'rounded-xl px-6 py-3 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) callback();
            });
        };

        // 3. Form Persistence Engine (Auto-Save)
        window.useFormPersistence = (key, alpine) => {
            let saveTimeout = null;
            return {
                save() {
                    clearTimeout(saveTimeout);
                    saveTimeout = setTimeout(() => {
                        localStorage.setItem(key, JSON.stringify(alpine.formData));
                    }, 1000);
                },
                load() {
                    const saved = localStorage.getItem(key);
                    if (!saved) return null;
                    try { return JSON.parse(saved); } catch (e) { return null; }
                },
                clear() { localStorage.removeItem(key); }
            };
        };

        // 4. Global AJAX Form Submission (Fast Send)
        window.fastSubmit = async function (target, options = {}) {
            let form = null;
            let data = options.data || null;
            let url = '';
            let method = options.method || 'POST';

            if (typeof target === 'string' && (target.startsWith('/') || target.startsWith('http'))) {
                url = target;
            } else {
                form = typeof target === 'string' ? document.querySelector(target) : target;
                if (!form) return;
                url = form.action;
                method = form.method || 'POST';
                data = data || new FormData(form);
            }

            const submitBtn = options.button || (form ? (form.querySelector('[type="submit"]') || form.querySelector('button:not([type="button"])')) : null);

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                var originalContent = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Processing...';
                refreshIcons();
            }

            try {
                const response = await axios({
                    method: method,
                    url: url,
                    data: data,
                    headers: {
                        'Content-Type': (data instanceof FormData) ? 'multipart/form-data' : 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
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
                if (options.error) {
                    options.error(error);
                } else {
                    let message = 'Something went wrong. Please try again.';
                    if (error.response && error.response.data.message) message = error.response.data.message;
                    Swal.fire({ icon: 'error', title: 'Error', text: message });
                }
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    submitBtn.innerHTML = originalContent;
                    refreshIcons();
                }
            }
        }

        // 5. Unpoly-Aware Button Loaders (Navigation & Forms)
        if (window.up) {
            // Handle Link Clicks (Navigation)
            up.on('up:link:follow', function(event) {
                const btn = event.target.closest('a, button');
                if (btn && (btn.classList.contains('saas-btn-primary') || btn.classList.contains('saas-btn-secondary'))) {
                    if (btn.hasAttribute('no-loader')) return;
                    
                    btn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    btn.dataset.originalHtml = btn.innerHTML;
                    btn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i>';
                    refreshIcons();
                }
            });

            // Handle Standard Form Submissions (Unpoly)
            up.on('up:form:submit', function(event) {
                const btn = event.submitter || event.target.querySelector('button[type="submit"]');
                if (btn) {
                    btn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                    btn.dataset.originalHtml = btn.innerHTML;
                    btn.innerHTML = '<i data-lucide="loader-2" class="h-4 w-4 animate-spin"></i> Processing...';
                    refreshIcons();
                }
            });

            // Revert button if Unpoly request fails or is aborted
            up.on('up:request:offline up:request:aborted', function(event) {
                document.querySelectorAll('.pointer-events-none').forEach(btn => {
                    if (btn.dataset.originalHtml) {
                        btn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                        btn.innerHTML = btn.dataset.originalHtml;
                        delete btn.dataset.originalHtml;
                    }
                });
                refreshIcons();
            });
        }

        // 6. Link Prefetching
        window.prefetched = window.prefetched || new Set();
        document.addEventListener('mouseover', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && link.origin === window.location.origin && !window.prefetched.has(link.href)) {
                const prefetchLink = document.createElement('link');
                prefetchLink.rel = 'prefetch';
                prefetchLink.href = link.href;
                document.head.appendChild(prefetchLink);
                window.prefetched.add(link.href);
            }
        });
    </script>

    @if(session('success'))
        <script>showToast("{{ session('success') }}", 'success');</script>
    @endif
    @if(session('error'))
        <script>showToast("{{ session('error') }}", 'error');</script>
    @endif
    @if($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Validation Failed',
                html: `<div class="text-left bg-rose-50 p-4 rounded-2xl border border-rose-100 mt-4">
                            <ul class="text-xs text-rose-600 space-y-1 list-disc pl-4 font-bold">
                                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>`,
                confirmButtonColor: '#FF6B00'
            });
        </script>
    @endif
    @stack('scripts')
</body>

</html>