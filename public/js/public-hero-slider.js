(() => {
    const ROOT_SELECTOR = '[data-hero-slider]';

    function clampIndex(i, len) {
        if (len <= 0) return 0;
        return (i % len + len) % len;
    }

    function initSlider(root) {
        const slides = Array.from(root.querySelectorAll('[data-slide]'));
        const dots = Array.from(root.querySelectorAll('[data-dot]'));
        const prevBtn = root.querySelector('[data-prev]');
        const nextBtn = root.querySelector('[data-next]');

        if (slides.length === 0) return;

        let index = clampIndex(Number(root.getAttribute('data-index') || 0), slides.length);
        let timer = null;
        const intervalMs = Number(root.getAttribute('data-interval') || 4000);

        function render() {
            slides.forEach((el, i) => el.classList.toggle('is-active', i === index));
            dots.forEach((el, i) => el.classList.toggle('is-active', i === index));
            root.setAttribute('data-index', String(index));
        }

        function goTo(i) {
            index = clampIndex(i, slides.length);
            render();
        }

        function next() {
            goTo(index + 1);
        }

        function prev() {
            goTo(index - 1);
        }

        function stop() {
            if (timer) window.clearInterval(timer);
            timer = null;
        }

        function start() {
            stop();
            if (intervalMs > 0) timer = window.setInterval(next, intervalMs);
        }

        // Controls
        prevBtn?.addEventListener('click', () => {
            stop();
            prev();
            start();
        });

        nextBtn?.addEventListener('click', () => {
            stop();
            next();
            start();
        });

        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                stop();
                goTo(i);
                start();
            });
        });

        // Pause on hover / focus (desktop)
        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', start);
        root.addEventListener('focusin', stop);
        root.addEventListener('focusout', start);

        // Basic swipe support
        let startX = 0;
        let deltaX = 0;
        root.addEventListener('touchstart', (e) => {
            startX = e.touches?.[0]?.clientX ?? 0;
            deltaX = 0;
        }, { passive: true });
        root.addEventListener('touchmove', (e) => {
            const x = e.touches?.[0]?.clientX ?? 0;
            deltaX = x - startX;
        }, { passive: true });
        root.addEventListener('touchend', () => {
            if (Math.abs(deltaX) > 40) {
                stop();
                if (deltaX < 0) next();
                else prev();
                start();
            }
            startX = 0;
            deltaX = 0;
        });

        render();
        start();
    }

    function init() {
        document.querySelectorAll(ROOT_SELECTOR).forEach(initSlider);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

