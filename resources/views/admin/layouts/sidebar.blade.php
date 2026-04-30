<!-- Mobile sidebar backdrop -->
<div id="admin-sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleAdminSidebar()"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="fixed top-0 left-0 z-50 h-screen bg-white text-slate-600 transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:fixed lg:z-30 border-r border-slate-100" data-collapsed="false">
    <div class="flex flex-col h-full bg-white">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-slate-50">
            <div class="flex items-center gap-3 sidebar-header-content">
                <div class="w-10 h-10 rounded-xl bg-orange-500 flex items-center justify-center shadow-lg shadow-orange-500/20">
                    <i data-lucide="shield-check" class="w-6 h-6 text-white"></i>
                </div>
                <div class="sidebar-text">
                    <h1 class="font-black text-slate-900 text-sm tracking-tight uppercase">REMENANT</h1>
                    <p class="text-[9px] text-orange-500 font-black uppercase tracking-[0.2em]">Dashboard</p>
                </div>
            </div>
            
            <button onclick="toggleAdminSidebarCollapse()" class="hidden lg:flex text-slate-400 hover:text-orange-500 p-2 rounded-xl hover:bg-orange-50 transition-all">
                <i id="admin-header-toggle-icon" data-lucide="chevron-left" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
            <p class="sidebar-text px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Operations</p>
            
            <a href="{{ route('admin.dashboard') }}" class="nav-item flex items-center px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'active-nav-item' : '' }}" title="Dashboard">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="ml-3 font-bold text-sm sidebar-text">Analytics</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-item flex items-center px-4 py-3 {{ request()->routeIs('admin.products.*') ? 'active-nav-item' : '' }}" title="Products">
                <i data-lucide="package" class="w-5 h-5"></i>
                <span class="ml-3 font-bold text-sm sidebar-text">Inventory</span>
            </a>

            <a href="{{ route('admin.sliders.index') }}" class="nav-item flex items-center px-4 py-3 {{ request()->routeIs('admin.sliders.*') ? 'active-nav-item' : '' }}" title="Sliders">
                <i data-lucide="monitor" class="w-5 h-5"></i>
                <span class="ml-3 font-bold text-sm sidebar-text">Banners</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="nav-item flex items-center px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'active-nav-item' : '' }}" title="Orders">
                <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                <span class="ml-3 font-bold text-sm sidebar-text">Orders</span>
            </a>

            <div class="my-6 border-t border-slate-50 mx-4"></div>
            <p class="sidebar-text px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Administration</p>

            <a href="#" class="nav-item flex items-center px-4 py-3 hover:bg-slate-50 transition" title="Users">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span class="ml-3 font-bold text-sm sidebar-text">Team</span>
            </a>

            <a href="#" class="nav-item flex items-center px-4 py-3 hover:bg-slate-50 transition" title="Settings">
                <i data-lucide="settings-2" class="w-5 h-5"></i>
                <span class="ml-3 font-bold text-sm sidebar-text">Configuration</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-slate-50">
            <a href="/" target="_blank" class="flex items-center px-4 py-3 text-slate-400 rounded-xl hover:bg-orange-50 hover:text-orange-600 transition-all font-bold text-sm group" title="Visit Site">
                <i data-lucide="external-link" class="w-5 h-5"></i>
                <span class="ml-3 sidebar-text">Live Website</span>
            </a>
        </div>
    </div>
</aside>

<style>
    #admin-sidebar[data-collapsed="true"] { width: 5.5rem; }
    #admin-sidebar[data-collapsed="false"] { width: 17rem; }
    #admin-sidebar[data-collapsed="true"] .sidebar-text { display: none; }
    
    #admin-sidebar[data-collapsed="true"] .sidebar-header-content { justify-content: center; width: 100%; }
    #admin-sidebar[data-collapsed="true"] nav a { justify-content: center; padding-left: 0; padding-right: 0; }
    #admin-sidebar[data-collapsed="true"] nav a i { margin: 0; }
    
    @media (min-width: 1024px) {
        #admin-main-content {
            margin-left: 17rem;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #admin-sidebar[data-collapsed="true"] ~ #admin-main-content {
            margin-left: 5.5rem;
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
