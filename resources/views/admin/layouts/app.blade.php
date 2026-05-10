<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin - {{ config('app.name', 'Laravel') }}</title>
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

    <!-- Assets -->
    @vite(['resources/css/admin.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="font-sans antialiased bg-white h-screen overflow-hidden flex flex-col">

    <div class="flex-1 flex overflow-hidden">
        <!-- Sidebar -->
        @include('admin.layouts.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col h-full overflow-hidden" id="admin-main-content">
            @include('admin.layouts.header')

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 overflow-y-auto" up-main>
                <div class="min-h-[70vh]">
                    @yield('content')
                </div>
                @include('admin.layouts.footer')
            </main>
        </div>
    </div>

    <!-- External JavaScript (Deferred) -->
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
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

        // 1.1 Global SweetAlert Helpers
        window.toast = (title, icon = 'success') => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: icon,
                title: title
            });
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

            const submitBtn = form ? (form.querySelector('[type="submit"]') || form.querySelector('button:not([type="button"])')) : null;

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
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
                        'Content-Type': (data instanceof FormData) ? 'multipart/form-data' : 'application/json'
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
                let message = 'Something went wrong. Please try again.';
                if (error.response && error.response.data.message) message = error.response.data.message;
                Swal.fire({ icon: 'error', title: 'Error', text: message });
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitBtn.innerHTML = originalContent;
                    refreshIcons();
                }
            }
        }

        // 5. Link Prefetching
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
        <script>toast("{{ session('success') }}");</script>
    @endif
    @if(session('error'))
        <script>Swal.fire({ icon: 'error', title: 'Error', text: "{{ session('error') }}", confirmButtonColor: '#FF6B00' });</script>
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