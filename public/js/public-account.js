(() => {
    const DROPDOWN_SELECTOR = '[data-account-dropdown]';

    function closeAll(exceptEl) {
        document.querySelectorAll(DROPDOWN_SELECTOR).forEach((el) => {
            if (exceptEl && el === exceptEl) return;
            el.removeAttribute('open');
        });
    }

    function isClickInside(target, container) {
        return container === target || container.contains(target);
    }

    function onDocClick(e) {
        const openDropdowns = Array.from(document.querySelectorAll(`${DROPDOWN_SELECTOR}[open]`));
        if (openDropdowns.length === 0) return;

        // If click is inside any open dropdown, do nothing
        for (const dd of openDropdowns) {
            if (isClickInside(e.target, dd)) return;
        }

        closeAll();
    }

    function onKeyDown(e) {
        if (e.key === 'Escape') closeAll();
    }

    function onToggle(e) {
        const dd = e.currentTarget;
        if (dd.hasAttribute('open')) {
            closeAll(dd);
        }
    }

    function init() {
        document.querySelectorAll(DROPDOWN_SELECTOR).forEach((dd) => {
            dd.addEventListener('toggle', onToggle);
        });
        document.addEventListener('click', onDocClick);
        document.addEventListener('keydown', onKeyDown);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

