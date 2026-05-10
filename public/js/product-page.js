/**
 * Product Page Gallery & Interactions
 * Compatible with Unpoly SPA navigation.
 */

function initProductGallery(element) {
    if (!window.jQuery || !$.fn.owlCarousel) {
        setTimeout(() => initProductGallery(element), 100);
        return;
    }

    const $shell = $(element);
    const $gallery = $shell.find(".product-gallery-carousel");
    const $lightbox = $(".lightbox-carousel");
    const $modal = $("#lightbox-modal");
    const $currentCounter = $("#lightbox-current");
    const $totalCounter = $("#lightbox-total");
    const $galleryPrev = $("[data-gallery-prev]");
    const $galleryNext = $("[data-gallery-next]");
    const $lightboxPrev = $("[data-lightbox-prev]");
    const $lightboxNext = $("[data-lightbox-next]");
    const $mobileImageTrack = $("[data-mobile-image-track]");
    const $mobileImageProgress = $("[data-mobile-image-progress]");
    
    // Get images from data attribute
    let gallery = [];
    try {
        gallery = JSON.parse($shell.attr('data-gallery-images') || '[]');
    } catch (e) {
        console.error("Failed to parse gallery images", e);
    }

    if (gallery.length === 0) return;

    let currentImageIndex = 0;
    let lockedScrollY = 0;
    
    $totalCounter.text(gallery.length);

    function setNavDisabled($btn, isDisabled) {
        $btn.toggleClass("!opacity-0 !pointer-events-none invisible", isDisabled);
    }

    function updateGalleryNavState(index) {
        setNavDisabled($galleryPrev, index <= 0);
        setNavDisabled($galleryNext, index >= gallery.length - 1);
    }

    function updateLightboxNavState(index) {
        const total = $lightbox.find('.owl-item:not(.cloned)').length;
        setNavDisabled($lightboxPrev, index <= 0);
        setNavDisabled($lightboxNext, index >= total - 1);
    }

    function updateMobileImageProgress(index) {
        if (!$mobileImageProgress.length || !$mobileImageTrack.length || gallery.length <= 0) return;
        const trackWidth = $mobileImageTrack.width() || 0;
        if (trackWidth <= 0) return;

        const segmentWidth = trackWidth / gallery.length;
        const indicatorWidth = Math.max(segmentWidth, 24);
        const maxOffset = Math.max(trackWidth - indicatorWidth, 0);
        const targetOffset = Math.min(segmentWidth * index, maxOffset);

        $mobileImageProgress.css({
            width: `${indicatorWidth}px`,
            transform: `translateX(${targetOffset}px)`
        });
    }

    // Initialize Gallery Carousel
    $gallery.owlCarousel({
        items: 1,
        loop: false,
        dots: false,
        nav: false,
        smartSpeed: 800,
        onInitialized: function() {
            $shell.addClass('owl-loaded');
        },
        onChanged: function(event) {
            const index = event.item.index;
            if (index === null || index === undefined) return;
            currentImageIndex = index;
            $currentCounter.text(index + 1);
            
            // Sync Thumbnails
            $(".product-other-image-thumb")
                .removeClass("border-[var(--primary)]")
                .addClass("border-transparent");
            $(`.product-other-image-thumb[data-index="${index}"]`)
                .removeClass("border-transparent hover:border-[var(--primary)]/70")
                .addClass("border-[var(--primary)]");
                
            updateGalleryNavState(index);
            updateMobileImageProgress(index);
        }
    });

    // Initialize Lightbox Carousel
    $lightbox.owlCarousel({
        items: 1,
        loop: false,
        dots: false,
        nav: false,
        smartSpeed: 500,
        mouseDrag: true,
        touchDrag: true,
        pullDrag: true,
        onChanged: function(event) {
            if (event.item) {
                const index = event.item.index;
                currentImageIndex = index;
                $currentCounter.text(index + 1);
                if ($lightbox.attr('data-gallery-type') === 'product') {
                    $gallery.trigger("to.owl.carousel", [index, 200, true]);
                }
                updateLightboxNavState(index);
            }
        }
    });

    // Thumbnail Clicks
    $(".product-other-image-thumb").off('click').on("click", function() {
        const index = $(this).data("index");
        $gallery.trigger("to.owl.carousel", [index, 500]);
    });

    // Global Lightbox Methods
    window.openLightbox = function(index) {
        currentImageIndex = index;
        $modal.removeClass("hidden").addClass("flex");
        $lightbox.trigger("to.owl.carousel", [index, 0]);
        lockBackgroundScroll();
        if(window.lucide) window.lucide.createIcons();
    };

    window.closeLightbox = function() {
        $modal.addClass("hidden").removeClass("flex");
        unlockBackgroundScroll();
    };

    function lockBackgroundScroll() {
        lockedScrollY = window.scrollY || window.pageYOffset || 0;
        $("body").css({
            position: "fixed",
            top: `-${lockedScrollY}px`,
            left: "0",
            right: "0",
            width: "100%",
            overflow: "hidden"
        });
    }

    function unlockBackgroundScroll() {
        $("body").css({
            position: "",
            top: "",
            left: "",
            right: "",
            width: "",
            overflow: ""
        });
        window.scrollTo(0, lockedScrollY);
    }

    // Initial State
    updateGalleryNavState(0);
    updateLightboxNavState(0);
    updateMobileImageProgress(0);

    // Lucide support
    if (window.lucide) lucide.createIcons();
}

// Register with Unpoly
if (window.up) {
    up.compiler('.product-gallery-shell', function(element) {
        initProductGallery(element);
    });
} else {
    $(document).ready(function() {
        const shell = document.querySelector('.product-gallery-shell');
        if (shell) initProductGallery(shell);
    });
}
