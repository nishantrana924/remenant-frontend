(() => {
    const CONTAINER_SELECTOR = '[data-category-slider-container]';
    const TRACK_SELECTOR = '[data-category-slider-track]';

    function initCategorySlider(container) {
        const track = container.querySelector(TRACK_SELECTOR);
        if (!track) return;

        let originalSlides = Array.from(track.children);
        if (originalSlides.length === 0) return;

        // Clone multiple items for a smoother infinite look
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

        function getSlideWidth() {
            const firstSlide = track.querySelector('.snap-start');
            if (!firstSlide) return 0;
            return firstSlide.offsetWidth + 24; // Width + gap-6
        }

        function updatePosition(animate = true) {
            const width = getSlideWidth();
            track.style.transition = animate ? 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)' : 'none';
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

        // Initialize position
        window.addEventListener('resize', () => updatePosition(false));

        // Initial render
        setTimeout(() => {
            updatePosition(false);
        }, 100);

        // Drag/Touch logic
        let isDown = false;
        let startX;
        let startY;
        let currentTranslate = 0;
        let isScrolling = false;
        let wasDragged = false;

        function onDragStart(pageX, pageY) {
            isDown = true;
            isScrolling = false;
            wasDragged = false;
            startX = pageX;
            startY = pageY;
            const style = window.getComputedStyle(track);
            const matrix = new WebKitCSSMatrix(style.transform);
            currentTranslate = matrix.m41;
            track.style.transition = 'none';
        }

        function onDragMove(pageX, pageY) {
            if (!isDown || isScrolling) return;

            const walk = pageX - startX;
            const walkY = pageY - startY;

            if (Math.abs(walkY) > Math.abs(walk) && Math.abs(walkY) > 5) {
                isScrolling = true;
                isDown = false;
                return;
            }

            if (Math.abs(walk) > 5) {
                wasDragged = true;
                track.style.transform = `translateX(${currentTranslate + walk}px)`;
            }
        }

        function onDragEnd() {
            if (!isDown) return;
            isDown = false;
            const width = getSlideWidth();
            const style = window.getComputedStyle(track);
            const matrix = new WebKitCSSMatrix(style.transform);
            const currentX = matrix.m41;
            const movedBy = currentX - (-currentIndex * width);

            if (Math.abs(movedBy) > width / 5) {
                if (movedBy > 0) movePrev();
                else moveNext();
            } else {
                updatePosition();
            }
        }

        // Events
        track.addEventListener('mousedown', (e) => onDragStart(e.pageX, e.pageY));
        window.addEventListener('mousemove', (e) => onDragMove(e.pageX, e.pageY));
        window.addEventListener('mouseup', onDragEnd);
        track.ondragstart = () => false;

        track.addEventListener('touchstart', (e) => onDragStart(e.touches[0].pageX, e.touches[0].pageY), { passive: true });
        track.addEventListener('touchmove', (e) => onDragMove(e.touches[0].pageX, e.touches[0].pageY), { passive: true });
        track.addEventListener('touchend', onDragEnd);

        // Prevent link clicks when dragging
        track.addEventListener('click', (e) => {
            if (wasDragged) {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
    }

    function init() {
        document.querySelectorAll(CONTAINER_SELECTOR).forEach(initCategorySlider);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
