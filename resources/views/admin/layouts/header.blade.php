<header class="sticky top-0 z-20 bg-white shadow">
    <div class="flex items-center justify-between py-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center">
            <button onclick="toggleAdminSidebar()" class="lg:hidden mr-4 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div>
                @hasSection('header')
                    @yield('header')
                @else
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Admin Dashboard
                    </h2>
                @endif
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('profile.edit') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </a>
        </div>
    </div>
</header>

