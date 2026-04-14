<!-- Mobile sidebar backdrop -->
<div id="admin-sidebar-backdrop" class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden hidden" onclick="toggleAdminSidebar()"></div>

<!-- Sidebar -->
<aside id="admin-sidebar" class="fixed top-0 left-0 z-50 h-screen bg-gray-800 text-white transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:fixed lg:z-30" data-collapsed="false">
    <div class="flex flex-col h-full">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between h-16 px-4 lg:px-6 bg-gray-900 border-b border-gray-700">
            <!-- Logo and Title (hidden when collapsed) -->
            <div class="flex items-center flex-1 min-w-0 sidebar-header-content">
                <svg class="w-8 h-8 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <span class="ml-2 text-lg font-semibold sidebar-text whitespace-nowrap overflow-hidden">Admin Panel</span>
            </div>
            
            <!-- Toggle Button in Header (always visible on desktop) -->
            <button onclick="toggleAdminSidebarCollapse()" class="hidden lg:flex text-gray-400 hover:text-white p-2 rounded hover:bg-gray-700 transition" title="Toggle Sidebar">
                <svg id="admin-header-toggle-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
            
            <!-- Mobile Close Button -->
            <button onclick="toggleAdminSidebar()" class="lg:hidden text-gray-400 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- User Info -->
        <div class="px-4 lg:px-6 py-4 bg-gray-900 border-b border-gray-700 sidebar-user-info group relative">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center">
                        <span class="text-white font-semibold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="ml-3 sidebar-text overflow-hidden">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">Administrator</p>
                </div>
            </div>
            <span class="sidebar-tooltip absolute left-full ml-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 whitespace-nowrap z-50">{{ Auth::user()->name }}</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-2 lg:px-4 py-4 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 lg:px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition group relative {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700 text-white' : '' }}" title="Dashboard">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="ml-3 sidebar-text whitespace-nowrap">Dashboard</span>
                <span class="sidebar-tooltip absolute left-full ml-2 px-3 py-2 text-xs font-medium text-white bg-gray-900 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 pointer-events-none transition-all duration-200 whitespace-nowrap z-50">Dashboard</span>
            </a>

            <a href="#" class="flex items-center px-3 lg:px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition group relative" title="Products">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span class="ml-3 sidebar-text whitespace-nowrap">Products</span>
                <span class="sidebar-tooltip absolute left-full ml-2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition whitespace-nowrap z-50">Products</span>
            </a>

            <a href="#" class="flex items-center px-3 lg:px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition group relative" title="Orders">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span class="ml-3 sidebar-text whitespace-nowrap">Orders</span>
                <span class="sidebar-tooltip absolute left-full ml-2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition whitespace-nowrap z-50">Orders</span>
            </a>

            <a href="#" class="flex items-center px-3 lg:px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition group relative" title="Users">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <span class="ml-3 sidebar-text whitespace-nowrap">Users</span>
                <span class="sidebar-tooltip absolute left-full ml-2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition whitespace-nowrap z-50">Users</span>
            </a>

            <a href="#" class="flex items-center px-3 lg:px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition group relative" title="Reports">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="ml-3 sidebar-text whitespace-nowrap">Reports</span>
                <span class="sidebar-tooltip absolute left-full ml-2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition whitespace-nowrap z-50">Reports</span>
            </a>

            <a href="#" class="flex items-center px-3 lg:px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition group relative" title="Settings">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="ml-3 sidebar-text whitespace-nowrap">Settings</span>
                <span class="sidebar-tooltip absolute left-full ml-2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition whitespace-nowrap z-50">Settings</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="px-2 lg:px-4 py-4 border-t border-gray-700">
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-3 lg:px-4 py-3 text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition group relative" title="Logout">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span class="ml-3 sidebar-text whitespace-nowrap">Logout</span>
                    <span class="sidebar-tooltip absolute left-full ml-2 px-2 py-1 text-xs font-medium text-white bg-gray-900 rounded opacity-0 group-hover:opacity-100 pointer-events-none transition whitespace-nowrap z-50">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>

<style>
    #admin-sidebar[data-collapsed="true"] {
        width: 5rem;
        overflow: hidden;
    }
    #admin-sidebar[data-collapsed="false"] {
        width: 16rem;
    }
    #admin-sidebar[data-collapsed="true"] .sidebar-text {
        display: none;
    }
    #admin-sidebar[data-collapsed="true"] .sidebar-user-info .ml-3 {
        display: none;
    }
    /* Tooltip visibility - show when collapsed, hide when expanded */
    #admin-sidebar[data-collapsed="true"] .sidebar-tooltip {
        display: block !important;
    }
    #admin-sidebar[data-collapsed="false"] .sidebar-tooltip {
        display: none !important;
    }
    /* Ensure tooltip shows on hover when collapsed */
    #admin-sidebar[data-collapsed="true"] .group:hover .sidebar-tooltip {
        opacity: 1 !important;
    }
    /* User info tooltip when collapsed */
    #admin-sidebar[data-collapsed="true"] .sidebar-user-info .sidebar-tooltip {
        display: block !important;
    }
    #admin-sidebar[data-collapsed="false"] .sidebar-user-info .sidebar-tooltip {
        display: none !important;
    }
    /* Hide logo and title when collapsed */
    #admin-sidebar[data-collapsed="true"] .sidebar-header-content {
        display: none;
    }
    /* Center header when collapsed */
    #admin-sidebar[data-collapsed="true"] .flex.items-center.justify-between {
        justify-content: center;
    }
    /* Adjust padding when collapsed */
    #admin-sidebar[data-collapsed="true"] nav {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        overflow-y: auto;
        overflow-x: hidden;
    }
    #admin-sidebar[data-collapsed="true"] nav a {
        justify-content: center;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    #admin-sidebar[data-collapsed="true"] .sidebar-user-info {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        justify-content: center;
    }
    #admin-sidebar[data-collapsed="true"] .sidebar-user-info > div {
        justify-content: center;
    }
    #admin-sidebar[data-collapsed="true"] .px-2 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }
    #admin-sidebar[data-collapsed="true"] .px-2 button {
        justify-content: center;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    /* Adjust main content margin based on sidebar state - Desktop only */
    @media (min-width: 1024px) {
        #admin-main-content {
            margin-left: 16rem;
            transition: margin-left 0.3s ease-in-out;
        }
    }
    /* Mobile: No margin */
    @media (max-width: 1023px) {
        #admin-main-content {
            margin-left: 0 !important;
        }
    }
</style>

<script>
    // Mobile sidebar toggle
    function toggleAdminSidebar() {
        const sidebar = document.getElementById('admin-sidebar');
        const backdrop = document.getElementById('admin-sidebar-backdrop');
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    }

    // Desktop sidebar collapse/expand
    function toggleAdminSidebarCollapse() {
        const sidebar = document.getElementById('admin-sidebar');
        const isCollapsed = sidebar.getAttribute('data-collapsed') === 'true';
        const newState = !isCollapsed;
        
        sidebar.setAttribute('data-collapsed', newState);
        localStorage.setItem('admin-sidebar-collapsed', newState);
        
        // Update main content margin (desktop only)
        const mainContent = document.getElementById('admin-main-content');
        if (mainContent && window.innerWidth >= 1024) {
            if (newState) {
                mainContent.style.marginLeft = '5rem';
            } else {
                mainContent.style.marginLeft = '16rem';
            }
        }
        
        // Update header toggle icon
        const headerIcon = document.getElementById('admin-header-toggle-icon');
        
        if (newState) {
            if (headerIcon) headerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>';
        } else {
            if (headerIcon) headerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>';
        }
    }

    // Restore sidebar state on page load
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('admin-sidebar');
        const savedState = localStorage.getItem('admin-sidebar-collapsed');
        
        if (savedState === 'true') {
            sidebar.setAttribute('data-collapsed', 'true');
            const headerIcon = document.getElementById('admin-header-toggle-icon');
            const mainContent = document.getElementById('admin-main-content');
            
            if (headerIcon) {
                headerIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>';
            }
            if (mainContent && window.innerWidth >= 1024) {
                mainContent.style.marginLeft = '5rem';
            }
        } else {
            const mainContent = document.getElementById('admin-main-content');
            if (mainContent && window.innerWidth >= 1024) {
                mainContent.style.marginLeft = '16rem';
            }
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('admin-sidebar');
            const mainContent = document.getElementById('admin-main-content');
            if (window.innerWidth < 1024) {
                if (mainContent) mainContent.style.marginLeft = '0';
            } else {
                const isCollapsed = sidebar.getAttribute('data-collapsed') === 'true';
                if (mainContent) {
                    mainContent.style.marginLeft = isCollapsed ? '5rem' : '16rem';
                }
            }
        });
    });
</script>


