<!-- Mobile sidebar backdrop -->
<div id="admin-sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleAdminSidebar()"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="fixed top-0 left-0 z-50 h-screen bg-white text-slate-600 transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:fixed lg:z-30 border-r border-slate-100" data-collapsed="false">
    <div class="flex flex-col h-full bg-white">
        <!-- Sidebar Header -->
        <div class="flex items-center h-16 px-6 border-b border-slate-100">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center">
                    <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                </div>
                <h1 class="font-bold text-slate-900 text-sm tracking-tight sidebar-text">REMENANT</h1>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active-nav-item' : '' }}">
                <i data-lucide="layout-grid" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Intelligence</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active-nav-item' : '' }}">
                <i data-lucide="package" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Product Hub</span>
            </a>

            <a href="{{ route('admin.inventory.index') }}" class="nav-item {{ request()->routeIs('admin.inventory.*') ? 'active-nav-item' : '' }}">
                <i data-lucide="box" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Stock Logistics</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active-nav-item' : '' }}">
                <i data-lucide="shopping-cart" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Order Flow</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active-nav-item' : '' }}">
                <i data-lucide="users" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Customers</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="sidebar-text px-4 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Branding</p>
            </div>

            <a href="{{ route('admin.sliders.index') }}" class="nav-item {{ request()->routeIs('admin.sliders.*') ? 'active-nav-item' : '' }}">
                <i data-lucide="image" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Web Banners</span>
            </a>
        </nav>

        <div class="p-4 border-t border-slate-100">
            <a href="/" target="_blank" class="nav-item hover:bg-slate-50 transition-all">
                <i data-lucide="external-link" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Live Store</span>
            </a>
        </div>
    </div>
</aside>

<style>
    #admin-sidebar[data-collapsed="true"] { width: 4.5rem; }
    #admin-sidebar[data-collapsed="false"] { width: 15rem; }
    #admin-sidebar[data-collapsed="true"] .sidebar-text { display: none; }
    
    @media (min-width: 1024px) {
        #admin-main-content {
            margin-left: 15rem;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #admin-sidebar[data-collapsed="true"] ~ #admin-main-content {
            margin-left: 4.5rem;
        }
    }

    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #F1F5F9; border-radius: 10px; }
</style>

<script>
    function toggleAdminSidebar() {
        document.getElementById('admin-sidebar').classList.toggle('-translate-x-full');
        document.getElementById('admin-sidebar-backdrop').classList.toggle('hidden');
    }

    function toggleAdminSidebarCollapse() {
        const sidebar = document.getElementById('admin-sidebar');
        const main = document.getElementById('admin-main-content');
        const icon = document.getElementById('admin-header-toggle-icon');
        const isCollapsed = sidebar.getAttribute('data-collapsed') === 'true';
        
        const newState = !isCollapsed;
        sidebar.setAttribute('data-collapsed', newState);
        localStorage.setItem('admin-sidebar-collapsed', newState);
        
        if (window.innerWidth >= 1024) {
            main.style.marginLeft = newState ? '5.5rem' : '17rem';
        }
        
        if (newState) {
            icon.setAttribute('data-lucide', 'chevron-right');
        } else {
            icon.setAttribute('data-lucide', 'chevron-left');
        }
        lucide.createIcons();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('admin-sidebar');
        const main = document.getElementById('admin-main-content');
        const savedState = localStorage.getItem('admin-sidebar-collapsed');
        
        if (savedState === 'true' && window.innerWidth >= 1024) {
            sidebar.setAttribute('data-collapsed', 'true');
            main.style.marginLeft = '5.5rem';
            const icon = document.getElementById('admin-header-toggle-icon');
            if(icon) {
                icon.setAttribute('data-lucide', 'chevron-right');
                lucide.createIcons();
            }
        }
    });
</script>
