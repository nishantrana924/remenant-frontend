(() => {
    const ROOT_SELECTOR = '[data-hero-slider]';

    function clampIndex(i, len) {
        if (len <= 0) return 0;
        return (i % len + len) % len;
    }

    function initSlider(root) {
        const slides = Array.from(root.querySelectorAll('[data-slide]'));
        const track = root.querySelector('[data-slider-track]');
        const dots = Array.from(root.querySelectorAll('[data-dot]'));
        const prevBtn = root.querySelector('[data-prev]');
        const nextBtn = root.querySelector('[data-next]');

        if (slides.length === 0 || !track) return;

        let index = clampIndex(Number(root.getAttribute('data-index') || 0), slides.length);
        let timer = null;
        const intervalMs = Number(root.getAttribute('data-interval') || 4000);

        function render() {
            // Sliding transition
            const offset = -index * 100;
            track.style.transform = `translateX(${offset}%)`;

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

        // REMOVED: Pause on hover / focus as requested by user

        // Enhanced real-time swipe support
        let startX = 0;
        let currentX = 0;
        let isDragging = false;
        let trackWidth = 0;

        root.addEventListener('touchstart', (e) => {
            startX = e.touches[0].pageX;
            currentX = startX; // Initialize currentX
            trackWidth = track.offsetWidth;
            isDragging = true;
            stop();
            
            // Remove transition during drag for real-time response
            track.style.transition = 'none';
        }, { passive: true });

        root.addEventListener('touchmove', (e) => {
            if (!isDragging) return;
            currentX = e.touches[0].pageX;
            const deltaX = currentX - startX;
            
            // Calculate current translation in pixels
            const currentOffsetPx = -(index * trackWidth);
            const newTranslatePx = currentOffsetPx + deltaX;
            
            track.style.transform = `translateX(${newTranslatePx}px)`;
        }, { passive: true });

        root.addEventListener('touchend', (e) => {
            if (!isDragging) return;
            isDragging = false;
            
            const deltaX = currentX - startX;
            const threshold = trackWidth / 4; // Swipe 25% of width to change slide

            // Restore transition
            track.style.transition = '';

            if (Math.abs(deltaX) > threshold) {
                if (deltaX < 0) next();
                else prev();
            } else {
                render(); // Snap back to current
            }
            
            start();
            startX = 0;
            currentX = 0;
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

