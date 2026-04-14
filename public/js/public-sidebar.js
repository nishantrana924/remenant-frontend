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
    }

    function onKeyDown(e) {
        if (e.key === 'Escape') closeSidebar();
    }

    function init() {
        const openBtns = Array.from(document.querySelectorAll(SELECTORS.open));
        const closeBtn = document.querySelector(SELECTORS.close);
        const overlay = document.querySelector(SELECTORS.overlay);
        const panel = document.querySelector(SELECTORS.panel);

        if (openBtns.length === 0 || !closeBtn || !overlay || !panel) return;

        overlay.setAttribute('aria-hidden', 'true');
        openBtns.forEach((btn) => setExpanded(btn, false));

        openBtns.forEach((btn) => {
            btn.addEventListener('click', () => openSidebar(btn));
        });
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeSidebar();
        });
        document.addEventListener('keydown', onKeyDown);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

