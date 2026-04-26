(() => {
    const HEADER_SELECTOR = '[data-public-header]';
    const MOBILE_SEARCH_SELECTOR = '[data-mobile-search]';

    function onScroll() {
        const header = document.querySelector(HEADER_SELECTOR);
        if (!header) return;

        const isScrolled = window.scrollY > 10;

        const mobileSearch = header.querySelector(MOBILE_SEARCH_SELECTOR);
        if (mobileSearch) {
            mobileSearch.classList.toggle('is-hidden', isScrolled);
        }
    }

    function init() {
        try {
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        } catch (e) {
            console.error('Error initializing public header:', e);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

