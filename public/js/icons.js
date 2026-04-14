(() => {
    function initLucide() {
        if (!window.lucide || typeof window.lucide.createIcons !== 'function') return;
        window.lucide.createIcons();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLucide);
    } else {
        initLucide();
    }
})();

