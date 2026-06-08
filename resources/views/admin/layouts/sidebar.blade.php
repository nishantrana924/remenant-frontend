<!-- Mobile sidebar backdrop -->
<div id="admin-sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 lg:hidden hidden" onclick="toggleAdminSidebar()"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="fixed top-0 left-0 z-[60] h-screen bg-white text-slate-600 transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:fixed lg:z-[60] border-r border-slate-100" data-collapsed="false">
    <div class="flex flex-col h-full bg-white">
        <!-- Sidebar Header -->
        <div class="flex items-center h-16 px-6 border-b border-slate-100 sidebar-header-container">
            <div class="flex items-center gap-3 sidebar-header-inner">
                <div class="w-8 h-8 rounded-lg bg-orange-500 flex items-center justify-center shrink-0">
                    <i data-lucide="zap" class="w-5 h-5 text-white"></i>
                </div>
                <h1 class="font-bold text-slate-900 text-sm tracking-tight sidebar-text uppercase">REMENANT</h1>
            </div>
        </div>

        <!-- Navigation -->
        <nav id="admin-nav" class="flex-1 px-3 py-4 space-y-1 overflow-y-auto custom-scrollbar" up-nav>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active-nav-item' : '' }}" up-target="#main-content, #admin-nav">
                <i data-lucide="layout-grid" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active-nav-item' : '' }}" up-alias="/admin/products*" up-target="#main-content, #admin-nav">
                <i data-lucide="package" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Products</span>
            </a>

            <a href="{{ route('admin.inventory.index') }}" class="nav-item {{ request()->routeIs('admin.inventory.*') ? 'active-nav-item' : '' }}" up-alias="/admin/inventory*" up-target="#main-content, #admin-nav">
                <i data-lucide="box" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Inventory</span>
            </a>

            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active-nav-item' : '' }}" up-alias="/admin/orders*" up-target="#main-content, #admin-nav">
                <i data-lucide="shopping-cart" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Orders</span>
            </a>

            <a href="{{ route('admin.logistics.dashboard') }}" class="nav-item {{ request()->routeIs('admin.logistics.*') ? 'active-nav-item' : '' }}" up-alias="/admin/logistics*" up-target="#main-content, #admin-nav">
                <i data-lucide="truck" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Logistics</span>
            </a>


            <a href="{{ route('admin.refunds.index') }}" class="nav-item {{ request()->routeIs('admin.refunds.*') ? 'active-nav-item' : '' }}" up-alias="/admin/refunds*" up-target="#main-content, #admin-nav">
                <i data-lucide="rotate-ccw" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Refunds</span>
            </a>

            <a href="{{ route('admin.reviews.index') }}" class="nav-item {{ request()->routeIs('admin.reviews.*') ? 'active-nav-item' : '' }}" up-alias="/admin/reviews*" up-target="#main-content, #admin-nav">
                <i data-lucide="message-square" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Reviews</span>
            </a>

            <a href="{{ route('admin.admins.index') }}" class="nav-item {{ request()->routeIs('admin.admins.*') ? 'active-nav-item' : '' }}" up-alias="/admin/admins*" up-target="#main-content, #admin-nav">
                <i data-lucide="shield-check" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Administrators</span>
            </a>

            <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers.*') ? 'active-nav-item' : '' }}" up-alias="/admin/customers*" up-target="#main-content, #admin-nav">
                <i data-lucide="users" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Customers</span>
            </a>

            <a href="{{ route('admin.contact-messages.index') }}" class="nav-item {{ request()->routeIs('admin.contact-messages.*') ? 'active-nav-item' : '' }}" up-alias="/admin/contact-messages*" up-target="#main-content, #admin-nav">
                <i data-lucide="mail-search" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Messages</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="sidebar-text px-4 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Marketing</p>
            </div>

            <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons.*') ? 'active-nav-item' : '' }}" up-alias="/admin/coupons*" up-target="#main-content, #admin-nav">
                <i data-lucide="ticket" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Coupons</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="sidebar-text px-4 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Branding</p>
            </div>

            <a href="{{ route('admin.sliders.index') }}" class="nav-item {{ request()->routeIs('admin.sliders.*') ? 'active-nav-item' : '' }}" up-alias="/admin/sliders*" up-target="#main-content, #admin-nav">
                <i data-lucide="image" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Web Banners</span>
            </a>

            <a href="{{ route('admin.about.edit') }}" class="nav-item {{ request()->routeIs('admin.about.*') ? 'active-nav-item' : '' }}" up-alias="/admin/about*" up-target="#main-content, #admin-nav">
                <i data-lucide="info" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">About Page</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="sidebar-text px-4 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Compliance</p>
            </div>

            <a href="{{ route('admin.legal.index') }}" class="nav-item {{ request()->routeIs('admin.legal.*') ? 'active-nav-item' : '' }}" up-alias="/admin/legal*" up-target="#main-content, #admin-nav">
                <i data-lucide="shield" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Legal Pages</span>
            </a>

            <div class="pt-4 pb-2">
                <p class="sidebar-text px-4 text-[10px] font-semibold text-slate-400 uppercase tracking-widest">Settings</p>
            </div>

            <a href="{{ route('admin.settings.shipping') }}" class="nav-item {{ request()->is('admin/settings/shipping*') ? 'active-nav-item' : '' }}" up-alias="/admin/settings/shipping*" up-target="#main-content, #admin-nav">
                <i data-lucide="package" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Shipping</span>
            </a>

            <a href="{{ route('admin.settings.invoice') }}" class="nav-item {{ request()->is('admin/settings/invoice*') ? 'active-nav-item' : '' }}" up-alias="/admin/settings/invoice*" up-target="#main-content, #admin-nav">
                <i data-lucide="file-text" class="w-4 h-4 mr-3"></i>
                <span class="sidebar-text">Invoice</span>
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
    
    /* Hide text and adjust padding when collapsed */
    #admin-sidebar[data-collapsed="true"] .sidebar-text { 
        display: none !important; 
    }
    
    #admin-sidebar[data-collapsed="true"] .sidebar-header-container {
        padding-left: 0 !important;
        padding-right: 0 !important;
        justify-content: center !important;
    }
    
    #admin-sidebar[data-collapsed="true"] .sidebar-header-inner {
        gap: 0 !important;
        justify-content: center !important;
    }

    #admin-sidebar[data-collapsed="true"] .nav-item {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    #admin-sidebar[data-collapsed="true"] .nav-item i {
        margin-right: 0 !important;
        width: 1.25rem !important; /* w-5 */
        height: 1.25rem !important; /* h-5 */
    }

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
            main.style.marginLeft = newState ? '4.5rem' : '15rem';
        }
        
        if (newState) {
            icon.setAttribute('data-lucide', 'chevron-right');
        } else {
            icon.setAttribute('data-lucide', 'chevron-left');
            // Hide tooltips when expanding
            document.querySelectorAll('#sidebar-floating-tooltip').forEach(t => t.remove());
        }
        refreshIcons();
    }

    function initSidebarTooltips() {
        // Clear any orphaned tooltips first
        document.querySelectorAll('#sidebar-floating-tooltip').forEach(t => t.remove());

        const items = document.querySelectorAll('.nav-item');
        items.forEach(item => {
            item.removeEventListener('mouseenter', handleMouseEnter);
            item.removeEventListener('mouseleave', handleMouseLeave);
            
            item.addEventListener('mouseenter', handleMouseEnter);
            item.addEventListener('mouseleave', handleMouseLeave);
        });
    }

    function handleMouseEnter(e) {
        const sidebar = document.getElementById('admin-sidebar');
        if (sidebar && sidebar.getAttribute('data-collapsed') !== 'true') return;
        
        // Immediate cleanup of any existing tooltips
        document.querySelectorAll('#sidebar-floating-tooltip').forEach(t => t.remove());
        
        const item = e.currentTarget;
        const textSpan = item.querySelector('.sidebar-text');
        if (!textSpan) return;
        
        const text = textSpan.innerText;
        
        const tooltip = document.createElement('div');
        tooltip.id = 'sidebar-floating-tooltip';
        tooltip.className = 'fixed z-[9999] px-3 py-2 bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-2xl pointer-events-none transform -translate-y-1/2 opacity-0 transition-opacity duration-200';
        tooltip.innerText = text;
        
        const arrow = document.createElement('div');
        arrow.className = 'absolute -left-1 top-1/2 -translate-y-1/2 w-2 h-2 bg-slate-900 rotate-45';
        tooltip.appendChild(arrow);
        
        document.body.appendChild(tooltip);
        
        const rect = item.getBoundingClientRect();
        tooltip.style.left = (rect.right + 12) + 'px';
        tooltip.style.top = (rect.top + rect.height / 2) + 'px';
        
        requestAnimationFrame(() => {
            if (tooltip.parentElement) tooltip.classList.remove('opacity-0');
        });
    }

    function handleMouseLeave() {
        document.querySelectorAll('#sidebar-floating-tooltip').forEach(tooltip => {
            tooltip.classList.add('opacity-0');
            setTimeout(() => {
                if (tooltip.parentElement) tooltip.remove();
            }, 200);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('admin-sidebar');
        const main = document.getElementById('admin-main-content');
        const savedState = localStorage.getItem('admin-sidebar-collapsed');
        
        if (savedState === 'true' && window.innerWidth >= 1024) {
            sidebar.setAttribute('data-collapsed', 'true');
            main.style.marginLeft = '4.5rem';
            const icon = document.getElementById('admin-header-toggle-icon');
            if(icon) {
                icon.setAttribute('data-lucide', 'chevron-right');
                refreshIcons();
            }
        }

        initSidebarTooltips();
    });

    // Re-init tooltips after Unpoly fragment updates just in case
    document.addEventListener('up:fragment:inserted', initSidebarTooltips);
</script>
