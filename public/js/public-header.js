(() => {
    const HEADER_SELECTOR = '[data-public-header]';
    const MOBILE_SEARCH_SELECTOR = '[data-mobile-search]';
    const SCROLL_THRESHOLD = 10; // Minimum scroll delta to trigger change
    
    let lastScrollY = window.scrollY;
    let isHidden = false;
    let ticking = false;

    function updateHeader() {
        const header = document.querySelector(HEADER_SELECTOR);
        if (!header) return;

        const currentScrollY = window.scrollY;
        const mobileSearch = header.querySelector(MOBILE_SEARCH_SELECTOR);
        if (!mobileSearch) return;

        // At the very top, always show instantly
        if (currentScrollY < 40) {
            if (isHidden) {
                mobileSearch.classList.remove('is-hidden');
                isHidden = false;
            }
            lastScrollY = currentScrollY;
            ticking = false;
            return;
        }

        // Calculate delta
        const delta = Math.abs(currentScrollY - lastScrollY);

        // Only trigger if scrolled more than threshold
        if (delta >= SCROLL_THRESHOLD) {
            // Scroll Down -> Hide
            if (currentScrollY > lastScrollY && currentScrollY > 80) {
                if (!isHidden) {
                    mobileSearch.classList.add('is-hidden');
                    isHidden = true;
                }
            } 
            // Scroll Up -> Show
            else if (currentScrollY < lastScrollY) {
                if (isHidden) {
                    mobileSearch.classList.remove('is-hidden');
                    isHidden = false;
                }
            }
            lastScrollY = currentScrollY;
        }

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            window.requestAnimationFrame(updateHeader);
            ticking = true;
        }
    }

    function init() {
        try {
            updateHeader();
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

