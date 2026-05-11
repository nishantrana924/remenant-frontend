(() => {
    const SELECTORS = {
        open: '[data-sidebar-open]',
        close: '[data-sidebar-close]',
        overlay: '[data-sidebar-overlay]',
        panel: '[data-sidebar-panel]',
    };

    let lastOpenBtn = null;

    function setExpanded(btn, expanded) {
        if (!btn) return;
        btn.setAttribute('aria-expanded', expanded ? 'true' : 'false');
    }

    function openSidebar(openBtn) {
        const overlay = document.querySelector(SELECTORS.overlay);
        const panel = document.querySelector(SELECTORS.panel);
        if (!overlay || !panel) return;

        overlay.classList.remove('hidden');
        overlay.setAttribute('aria-hidden', 'false');
        requestAnimationFrame(() => {
            overlay.classList.add('opacity-100');
            panel.classList.remove('-translate-x-full');
            panel.classList.add('translate-x-0');
        });

        lastOpenBtn = openBtn || lastOpenBtn;
        setExpanded(lastOpenBtn, true);

        const focusTarget = panel.querySelector('[data-sidebar-initial-focus]') || panel;
        focusTarget.focus?.();
        document.body.classList.add('overflow-hidden');
        window.lenis?.stop();
    }

    function closeSidebar() {
        const overlay = document.querySelector(SELECTORS.overlay);
        const panel = document.querySelector(SELECTORS.panel);
        if (!overlay || !panel) return;

        overlay.classList.remove('opacity-100');
        panel.classList.remove('translate-x-0');
        panel.classList.add('-translate-x-full');

        window.setTimeout(() => {
            overlay.classList.add('hidden');
            overlay.setAttribute('aria-hidden', 'true');
        }, 200);

        setExpanded(lastOpenBtn, false);
        lastOpenBtn?.focus?.();
        document.body.classList.remove('overflow-hidden');
        window.lenis?.start();
    }

    function onKeyDown(e) {
        if (e.key === 'Escape') closeSidebar();
    }

    function init() {
        // Use event delegation for open buttons
        document.addEventListener('click', (e) => {
            const openBtn = e.target.closest(SELECTORS.open);
            if (openBtn) {
                e.preventDefault();
                openSidebar(openBtn);
            }
        });

        // Use event delegation for close buttons and sidebar links
        document.addEventListener('click', (e) => {
            const closeBtn = e.target.closest(SELECTORS.close);
            const sidebarLink = e.target.closest(`${SELECTORS.panel} a`);
            const overlayClick = e.target.closest(SELECTORS.overlay) === e.target;

            if (closeBtn || sidebarLink || overlayClick) {
                closeSidebar();
            }
        });

        document.addEventListener('keydown', onKeyDown);

        // Close sidebar when Unpoly swaps a fragment (navigation)
        if (window.up) {
            up.on('up:fragment:inserted', closeSidebar);
        }
        
        // Initial accessibility setup
        const overlay = document.querySelector(SELECTORS.overlay);
        if (overlay) overlay.setAttribute('aria-hidden', 'true');
    }

    // Initialize once on first load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

