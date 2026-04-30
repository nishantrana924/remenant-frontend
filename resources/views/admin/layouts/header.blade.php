<header class="sticky top-0 z-50 glass-header border-b border-gray-100">
    <div class="flex items-center justify-between py-3 px-6">
        <div class="flex-1 flex items-center">
            <button onclick="toggleAdminSidebar()" class="lg:hidden p-2 mr-4 rounded-xl hover:bg-gray-100 transition text-gray-600">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <div class="flex-1">
                @hasSection('header')
                    @yield('header')
                @else
                    <h2 class="font-black text-xl text-slate-800 leading-tight">
                        Admin Portal
                    </h2>
                @endif
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="/" target="_blank" class="hidden md:flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                View Site
            </a>
            
            <div class="h-8 w-px bg-gray-100 mx-2"></div>
            
            <details class="relative">
                <summary class="list-none cursor-pointer">
                    <div class="flex items-center gap-3 p-1 pr-3 rounded-full hover:bg-gray-100 transition">
                        <div class="w-9 h-9 rounded-full bg-orange-100 flex items-center justify-center text-[#ea5f06]">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <div class="hidden sm:block">
                            <p class="text-xs font-bold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                </summary>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        Profile Settings
                    </a>
                    <div class="h-px bg-gray-50 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </details>
        </div>
    </div>
</header>

