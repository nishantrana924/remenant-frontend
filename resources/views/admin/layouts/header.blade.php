<header class="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-100">
    <div class="flex items-center justify-between py-3 px-6">
        <div class="flex-1 flex items-center">
            <button onclick="toggleAdminSidebar()" class="lg:hidden p-2 mr-4 rounded-xl hover:bg-gray-100 transition text-gray-600">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
            <button onclick="toggleAdminSidebarCollapse()" class="hidden lg:flex p-2 mr-4 rounded-xl hover:bg-slate-50 transition text-slate-400 border border-transparent hover:border-slate-100">
                <i id="admin-header-toggle-icon" data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>
            <div class="flex-1 flex items-center gap-4">
                @hasSection('header')
                    @yield('header')
                @else
                    <h2 class="font-bold text-lg sm:text-xl text-slate-800 leading-tight uppercase tracking-tight">
                        Admin Portal
                    </h2>
                @endif

                <!-- Global Quick Search -->
                <div class="hidden xl:flex items-center flex-1 max-w-md ml-8" x-data="{ 
                    search: '',
                    showResults: false,
                    links: [
                        { name: 'Dashboard', url: '{{ route('admin.dashboard') }}', icon: 'layout-dashboard' },
                        { name: 'Products', url: '{{ route('admin.products.index') }}', icon: 'package' },
                        { name: 'Inventory', url: '{{ route('admin.inventory.index') }}', icon: 'boxes' },
                        { name: 'Orders', url: '{{ route('admin.orders.index') }}', icon: 'shopping-cart' },
                        { name: 'Shipping', url: '{{ route('admin.shipping.index') }}', icon: 'truck' },
                        { name: 'Refunds', url: '{{ route('admin.refunds.index') }}', icon: 'rotate-ccw' },
                        { name: 'Customers', url: '{{ route('admin.customers.index') }}', icon: 'users' },
                        { name: 'Coupons', url: '{{ route('admin.coupons.index') }}', icon: 'ticket' },
                        { name: 'Web Banners', url: '{{ route('admin.sliders.index') }}', icon: 'image' },
                        { name: 'Legal Pages', url: '{{ route('admin.legal.index') }}', icon: 'file-text' }
                    ],
                    get filteredLinks() {
                        return this.links.filter(l => l.name.toLowerCase().includes(this.search.toLowerCase()));
                    }
                }" @click.away="showResults = false">
                    <div class="relative w-full">
                        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                        <input type="text" x-model="search" @focus="showResults = true" placeholder="Quick find... (e.g. Orders)" 
                               class="w-full bg-slate-50 border border-slate-100 rounded-2xl pl-12 pr-4 py-2 text-xs font-bold uppercase tracking-widest focus:bg-white focus:ring-4 focus:ring-orange-500/10 transition-all outline-none">
                        
                        <div x-show="showResults && search" x-cloak class="absolute top-full left-0 w-full mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-[100]">
                            <div class="p-2 space-y-1">
                                <template x-for="link in filteredLinks" :key="link.name">
                                    <a :href="link.url" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-orange-50 group transition-all">
                                        <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-orange-500 group-hover:text-white transition-all">
                                            <i :data-lucide="link.icon" class="w-4 h-4"></i>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-600 group-hover:text-orange-600 uppercase tracking-widest" x-text="link.name"></span>
                                    </a>
                                </template>
                                <div x-show="filteredLinks.length === 0" class="p-4 text-center">
                                    <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">No matching module</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="/" target="_blank" class="hidden md:flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-100 transition">
                <i data-lucide="external-link" class="w-4 h-4"></i>
                View Site
            </a>
            
            <div class="h-8 w-px bg-gray-100 mx-2"></div>
            
            <details class="relative">
                <summary class="list-none cursor-pointer outline-none">
                    <div class="flex items-center gap-3 p-1.5 pl-4 pr-1.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-orange-200 hover:bg-orange-50/30 transition-all duration-300 group">
                        <div class="hidden sm:block text-right">
                            <p class="text-[11px] font-bold text-slate-900 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[9px] text-slate-400 uppercase font-bold tracking-widest mt-1">{{ Auth::user()->role ?? 'Administrator' }}</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-white shadow-sm border border-slate-200 flex items-center justify-center text-orange-500 group-hover:scale-110 transition-all">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                    </div>
                </summary>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 transition">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        Profile Settings
                    </a>
                    <div class="h-px bg-gray-50 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}" up-submit="false">
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

