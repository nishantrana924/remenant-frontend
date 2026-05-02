$(document).ready(function () {
    // Hero Slider
    function initHeroSlider() {
        console.log('Hero Slider Init Start');
        const heroSlider = $(".hero-carousel").owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: false,
            nav: false,
            dots: true,
            smartSpeed: 800,
            autoHeight: false,
            onInitialized: function () {
                $('.hero-carousel').addClass('owl-loaded');
                console.log('Hero Slider Initialized');
            }
        });

        setTimeout(() => {
            heroSlider.trigger('refresh.owl.carousel');
        }, 50);
    }

    initHeroSlider();

    // Function to initialize Combo Slider
    function initComboCarousel() {
        console.log('Combo Slider Init Start');
        const comboCarousel = $(".combo-carousel").owlCarousel({
            margin: 20,
            loop: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            smartSpeed: 1000,
            nav: false,
            dots: false,
            touchDrag: true,
            mouseDrag: true,
            responsive: {
                0: { 
                    items: 1.4,
                    margin: 16,
                    stagePadding: 0
                },
                576: { 
                    items: 1.8,
                    margin: 20,
                    stagePadding: 0
                },
                768: { 
                    items: 2.4,
                    margin: 24
                },
                1024: { 
                    items: 3,
                    margin: 24
                },
                1400: { 
                    items: 4,
                    margin: 24
                }
            },
            onInitialized: function () {
                $('.combo-carousel').addClass('owl-loaded');
                console.log('Combo Slider Initialized');
            }
        });

        // Custom Nav for Combo
        $('[data-combo-prev]').off('click').on('click', function () {
            comboCarousel.trigger('prev.owl.carousel');
        });
        $('[data-combo-next]').off('click').on('click', function () {
            comboCarousel.trigger('next.owl.carousel');
        });

        setTimeout(() => {
            comboCarousel.trigger('refresh.owl.carousel');
        }, 50);
    }

    initComboCarousel();

    // Testimonials Slider
    function initTestimonialCarousel() {
        console.log('Testimonial Slider Init Start');
        const testimonialCarousel = $(".testimonial-carousel").owlCarousel({
            items: 1,
            margin: 20,
            loop: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            nav: false,
            dots: false,
            smartSpeed: 800,
            responsive: {
                0: { items: 1, stagePadding: 30, margin: 16 },
                768: { items: 2, stagePadding: 0, margin: 20 },
                1024: { items: 3, stagePadding: 0, margin: 20 }
            },
            onInitialized: function () {
                $('.testimonial-carousel').addClass('owl-loaded');
                console.log('Testimonial Slider Initialized');
            }
        });

        setTimeout(() => {
            testimonialCarousel.trigger('refresh.owl.carousel');
        }, 50);
    }

    initTestimonialCarousel();

    // New Category Slider
    function initNewCategoryCarousel() {
        console.log('New Category Slider Init Start');
        const newCategoryCarousel = $(".new-category-carousel").owlCarousel({
            items: 1,
            margin: 20,
            loop: true,
            autoplay: false,
            nav: false,
            dots: false,
            smartSpeed: 800,
            responsive: {
                0: { items: 1, stagePadding: 20, margin: 16 },
                768: { items: 2, stagePadding: 0, margin: 20 },
                1024: { items: 3, stagePadding: 0, margin: 20 }
            },
            onInitialized: function() {
                $('.new-category-carousel').addClass('owl-loaded');
                console.log('New Category Slider Initialized');
            }
        });

        setTimeout(() => {
            newCategoryCarousel.trigger('refresh.owl.carousel');
        }, 50);
    }

    initNewCategoryCarousel();
});
