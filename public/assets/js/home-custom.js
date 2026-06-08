function initHomeSliders() {
    if (typeof jQuery === 'undefined' || typeof $.fn.owlCarousel !== 'function') {
        setTimeout(initHomeSliders, 100);
        return;
    }

    // Hero Slider
    function initHeroSlider() {
        const $hero = $(".hero-carousel");
        if ($hero.length && !$hero.hasClass('owl-loaded')) {
            const heroSlider = $hero.owlCarousel({
                items: 1,
                loop: true,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: false,
                nav: false,
                dots: true,
                smartSpeed: 800,
                autoHeight: false
            });
            $hero.addClass('owl-loaded');
        }
    }

    // Combo Slider
    function initComboCarousel() {
        const $combo = $(".combo-carousel");
        if ($combo.length && !$combo.hasClass('owl-loaded')) {
            const comboCount = parseInt($combo.attr('data-items-count')) || 0;
            const comboCarousel = $combo.owlCarousel({
                margin: 20,
                loop: comboCount > 4,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: true,
                smartSpeed: 1000,
                nav: false,
                dots: false,
                touchDrag: true,
                mouseDrag: true,
                responsive: {
                    0: { items: 1.3, margin: 16, stagePadding: 0 },
                    640: { items: 2.4, margin: 20, stagePadding: 0 },
                    1024: { items: 4, margin: 24 },
                    1400: { items: 4, margin: 24 }
                }
            });
            $combo.addClass('owl-loaded');

            $('[data-combo-prev]').off('click').on('click', function () {
                comboCarousel.trigger('prev.owl.carousel');
            });
            $('[data-combo-next]').off('click').on('click', function () {
                comboCarousel.trigger('next.owl.carousel');
            });
        }
    }

    // Testimonials Slider
    function initTestimonialCarousel() {
        const $testi = $(".testimonial-carousel");
        if ($testi.length && !$testi.hasClass('owl-loaded')) {
            $testi.owlCarousel({
                items: 1,
                margin: 16,
                loop: true,
                autoplay: true,
                autoplayTimeout: 4000,
                autoplayHoverPause: true,
                nav: false,
                dots: false,
                smartSpeed: 800,
                responsive: {
                    0: { items: 1, stagePadding: 30, margin: 10 },
                    768: { items: 2, stagePadding: 0, margin: 16 },
                    1024: { items: 3, stagePadding: 0, margin: 16 }
                }
            });
            $testi.addClass('owl-loaded');
        }
    }

    initHeroSlider();
    initComboCarousel();
    initTestimonialCarousel();
}

// Initial call
if (typeof jQuery !== 'undefined') {
    $(document).ready(initHomeSliders);
} else {
    document.addEventListener('DOMContentLoaded', initHomeSliders);
}

// Unpoly re-init
if (window.up) {
    up.on('up:fragment:inserted', function() {
        if (typeof initHomeSliders === 'function') {
            initHomeSliders();
        }
    });
}
