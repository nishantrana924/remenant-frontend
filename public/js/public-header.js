(() => {
    const HEADER_SELECTOR = '[data-public-header]';
    const MOBILE_SEARCH_SELECTOR = '[data-mobile-search]';

    function onScroll() {
        const header = document.querySelector(HEADER_SELECTOR);
        if (!header) return;

        const isScrolled = window.scrollY > 8;

        const mobileSearch = header.querySelector(MOBILE_SEARCH_SELECTOR);
        if (mobileSearch) {
            mobileSearch.classList.toggle('is-hidden', isScrolled);
        }
    }

    function init() {
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

