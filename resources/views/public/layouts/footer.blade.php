<footer class="border-t border-black/5 bg-[var(--bg-main)] py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="flex items-center space-x-2 mb-2 md:mb-0">
                <img
                    src="{{ asset('images/logo/remenant-health-logo.png') }}"
                    alt="{{ config('app.name', 'Remenant Health') }} logo"
                    class="h-5 w-5 rounded object-contain"
                >
                <span class="text-sm font-semibold text-[color:var(--text-primary)]">
                    {{ config('app.name', 'Remenant Health') }}
                </span>
            </div>
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} All rights reserved.
            </p>
        </div>
    </div>
</footer>

