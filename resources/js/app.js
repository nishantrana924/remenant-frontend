import './bootstrap';

/**
 * REMENANT HEALTH - Professional Unpoly SPA Implementation
 */

document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.up === 'undefined') return;

    // --- 1. CORE CONFIGURATION ---
    up.fragment.config.mainTargets = ['#main-content', '[up-main]', '.content-area'];
    up.network.config.progressBar = false; 
    if (up.link.config.prefetchSelectors) {
        up.link.config.prefetchSelectors.push('a[href^="/admin"]');
    }
    up.fragment.config.navigateOptions.cache = true;

    // --- 2. MULTI-TARGET SYNC ---
    up.on('up:link:follow', function(event) {
        if (window.NProgress) NProgress.start();
        var url = event.target.pathname;
        if (url.indexOf('/admin') === 0 || url.indexOf('/dashboard') === 0) {
            event.renderOptions.target = '#main-content, #admin-nav, #admin-header-stats';
            event.renderOptions.fallback = '#main-content';
        }
    });

    // --- 3. NAVIGATION GUARDS ---
    up.on('up:fragment:loaded', function(event) {
        var currentLayout = document.querySelector('meta[name="layout"]');
        var currentLayoutContent = currentLayout ? currentLayout.getAttribute('content') : null;
        var layoutMatch = event.response.text.match(/<meta\s+name="layout"\s+content="(admin|public)"/i);
        var nextLayout = layoutMatch ? layoutMatch[1] : null;

        if (currentLayoutContent && nextLayout && currentLayoutContent !== nextLayout) {
            event.preventDefault();
            window.location.href = event.response.url;
        }
    });

    // Instant Sidebar Sync for Admin
    up.on('up:location:changed', function() {
        var navItems = document.querySelectorAll('.nav-item');
        navItems.forEach(function(link) {
            var href = link.getAttribute('href');
            if (href && href !== '#') {
                var isActive = window.location.pathname === href || (href !== '/admin' && window.location.pathname.indexOf(href + '/') === 0);
                link.classList.toggle('active-nav-item', isActive);
                link.classList.toggle('up-current', isActive);
            }
        });
    });

    // --- 4. ASSETS & UI INIT ---
    up.on('up:assets:changed', function() {
        up.cache.clear().then(function() {
            window.location.reload();
        });
    });

    up.on('up:request:finished', function() {
        if (window.NProgress) NProgress.done();
    });

    up.on('up:fragment:inserted', function() {
        if (window.lucide) window.lucide.createIcons();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // Initial Icon Run
    if (window.lucide) window.lucide.createIcons();
});
