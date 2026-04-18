(() => {
    const CONTAINER_SELECTOR = '[data-combo-slider-container]';
    const TRACK_SELECTOR = '[data-combo-slider-track]';
    const NEXT_SELECTOR = '[data-combo-next]';
    const PREV_SELECTOR = '[data-combo-prev]';
    const INTERVAL_MS = 4000;

    function initComboSlider(container) {
        const track = container.querySelector(TRACK_SELECTOR);
        const nextBtn = container.querySelector(NEXT_SELECTOR);
        const prevBtn = container.querySelector(PREV_SELECTOR);
        if (!track) return;

        let originalSlides = Array.from(track.children);
        if (originalSlides.length === 0) return;

        // Clone multiple items for a smoother infinite look on all screens
        const clonesCount = 4;
        for (let i = 0; i < clonesCount; i++) {
            const firstClone = originalSlides[i % originalSlides.length].cloneNode(true);
            const lastClone = originalSlides[(originalSlides.length - 1 - i) % originalSlides.length].cloneNode(true);
            track.appendChild(firstClone);
            track.insertBefore(lastClone, track.firstChild);
        }

        const allSlides = Array.from(track.children);
        let currentIndex = clonesCount;
        let isTransitioning = false;
        let autoPlayTimer = null;

        function getSlideWidth() {
            const firstSlide = track.querySelector('.group');
            if (!firstSlide) return 0;
            return firstSlide.offsetWidth + 24;
        }

        function updatePosition(animate = true) {
            const width = getSlideWidth();
            track.style.transition = animate ? 'transform 0.7s cubic-bezier(0.4, 0, 0.2, 1)' : 'none';
            track.style.transform = `translateX(${-currentIndex * width}px)`;
        }

        function moveNext() {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex++;
            updatePosition();

            track.addEventListener('transitionend', () => {
                if (currentIndex >= allSlides.length - clonesCount) {
                    currentIndex = clonesCount;
                    updatePosition(false);
                }
                isTransitioning = false;
            }, { once: true });
        }

        function movePrev() {
            if (isTransitioning) return;
            isTransitioning = true;
            currentIndex--;
            updatePosition();

            track.addEventListener('transitionend', () => {
                if (currentIndex < clonesCount) {
                    currentIndex = allSlides.length - clonesCount * 2 + currentIndex;
                    updatePosition(false);
                }
                isTransitioning = false;
            }, { once: true });
        }

        function startAutoPlay() {
            stopAutoPlay();
            autoPlayTimer = setInterval(moveNext, INTERVAL_MS);
        }

        function stopAutoPlay() {
            if (autoPlayTimer) clearInterval(autoPlayTimer);
        }

        // Event Listeners
        nextBtn?.addEventListener('click', () => {
            stopAutoPlay();
            moveNext();
            startAutoPlay();
        });

        prevBtn?.addEventListener('click', () => {
            stopAutoPlay();
            movePrev();
            startAutoPlay();
        });

        // Initialize position
        window.addEventListener('resize', () => updatePosition(false));

        // Initial render
        setTimeout(() => {
            updatePosition(false);
            startAutoPlay();
        }, 100);

        // Unified drag/touch logic
        let isDown = false;
        let startX;
        let currentTranslate = 0;

        function onDragStart(pageX) {
            stopAutoPlay();
            isDown = true;
            startX = pageX;
            const style = window.getComputedStyle(track);
            const matrix = new WebKitCSSMatrix(style.transform);
            currentTranslate = matrix.m41;
            track.style.transition = 'none';
        }

        function onDragMove(pageX) {
            if (!isDown) return;
            const walk = pageX - startX;
            track.style.transform = `translateX(${currentTranslate + walk}px)`;
        }

        function onDragEnd() {
            if (!isDown) return;
            isDown = false;
            const width = getSlideWidth();
            const transform = track.style.transform;
            const currentX = parseInt(transform.replace('translateX(', '').replace('px)', '')) || 0;
            const movedBy = currentX - (-currentIndex * width);

            if (Math.abs(movedBy) > width / 5) {
                if (movedBy > 0) movePrev();
                else moveNext();
            } else {
                updatePosition();
            }
            startAutoPlay();
        }

        // Mouse Events
        track.addEventListener('mousedown', (e) => onDragStart(e.pageX));
        window.addEventListener('mousemove', (e) => onDragMove(e.pageX));
        window.addEventListener('mouseup', onDragEnd);
        track.ondragstart = () => false;

        // Touch Events
        track.addEventListener('touchstart', (e) => onDragStart(e.touches[0].pageX), { passive: true });
        track.addEventListener('touchmove', (e) => onDragMove(e.touches[0].pageX), { passive: true });
        track.addEventListener('touchend', onDragEnd);
    }

    function init() {
        document.querySelectorAll(CONTAINER_SELECTOR).forEach(initComboSlider);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
