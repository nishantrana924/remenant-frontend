@extends('public.layouts.app')

@section('title', $product->title . ' - Customer Reviews | Remenant Health')

@section('content')
<div class="bg-[var(--bg-main)] min-h-screen pt-24 pb-20">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        <!-- Breadcrumbs -->
        <nav class="flex mb-12" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-[10px] font-black uppercase tracking-[0.2em]">
                <li><a href="{{ route('home') }}" class="text-[color:var(--text-muted)] hover:text-[color:var(--primary)] transition">Home</a></li>
                <li><i data-lucide="chevron-right" class="h-3 w-3 text-gray-300"></i></li>
                <li><a href="{{ route('products.index') }}" class="text-[color:var(--text-muted)] hover:text-[color:var(--primary)] transition">Products</a></li>
                <li><i data-lucide="chevron-right" class="h-3 w-3 text-gray-300"></i></li>
                <li><a href="{{ route('products.show', $product->slug) }}" class="text-[color:var(--text-muted)] hover:text-[color:var(--primary)] transition">{{ $product->title }}</a></li>
                <li><i data-lucide="chevron-right" class="h-3 w-3 text-gray-300"></i></li>
                <li class="text-[color:var(--text-primary)]">Customer Reviews</li>
            </ol>
        </nav>

        <!-- Premium Hero Summary -->
        <div class="relative overflow-hidden rounded-[3rem] bg-white p-8 sm:p-16 shadow-sm ring-1 ring-black/[0.03] mb-16">
            <!-- Background Decoration -->
            <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-[var(--primary)]/5 blur-[100px]"></div>
            <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-emerald-500/5 blur-[100px]"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:items-center">
                <div class="lg:col-span-4 border-b lg:border-b-0 lg:border-r border-black/[0.05] pb-12 lg:pb-0 lg:pr-12">
                    <div class="flex items-center gap-6 mb-8">
                        <img src="{{ \App\Helpers\ImageHelper::getUrl($product->image, 'products') }}" alt="{{ $product->title }}" class="h-24 w-24 rounded-3xl bg-gray-50 object-contain p-3 shadow-inner">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[color:var(--primary)]">Product Feedback</span>
                            <h1 class="text-2xl font-black text-[color:var(--text-primary)] leading-tight mt-1">{{ $product->title }}</h1>
                        </div>
                    </div>
                    
                    <div class="flex items-end gap-6">
                        <span class="text-7xl font-black text-[color:var(--text-primary)] tracking-tighter leading-none">{{ number_format($avgRating, 1) }}</span>
                        <div class="pb-2">
                            <div class="flex text-[color:var(--primary)] gap-0.5 mb-2">
                                @for($i = 0; $i < 5; $i++)
                                    <i data-lucide="star" class="h-5 w-5 {{ $i < floor($avgRating) ? 'fill-current' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                            <p class="text-xs font-bold text-[color:var(--text-muted)] uppercase tracking-widest">Global score from {{ number_format($totalCount) }} buyers</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 border-b lg:border-b-0 lg:border-r border-black/[0.05] pb-12 lg:pb-0 lg:px-12">
                    <h3 class="text-sm font-black uppercase tracking-widest mb-6 text-[color:var(--text-primary)]">Rating Breakdown</h3>
                    <div class="space-y-4">
                        @foreach([5, 4, 3, 2, 1] as $star)
                        @php $percentage = $breakdown[$star] ?? 0; @endphp
                        <div class="flex items-center gap-4 text-[10px] font-black uppercase tracking-[0.15em]">
                            <span class="w-4">{{ $star }} ★</span>
                            <div class="flex-1 h-2 bg-gray-50 rounded-full overflow-hidden ring-1 ring-black/[0.03]">
                                <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="w-10 text-right text-[color:var(--text-muted)]">{{ $percentage }}%</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="lg:col-span-3 lg:pl-12 text-center lg:text-left">
                    <p class="text-sm font-medium text-[color:var(--text-secondary)] mb-6">Have you used this product? Share your experience with our community.</p>
                    <button onclick="openWriteReviewModal()" class="w-full rounded-full bg-[color:var(--text-primary)] text-white py-4 text-xs font-black uppercase tracking-[0.2em] transition-all duration-300 hover:bg-[var(--primary)] hover:shadow-xl hover:shadow-[var(--primary)]/20 active:scale-95">
                        Write a Review
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 mb-12">
            <h2 class="text-2xl font-black italic tracking-tight text-[color:var(--text-primary)] underline decoration-[var(--primary)] decoration-4 underline-offset-8">Community Gallery</h2>
            <div class="flex items-center gap-4 w-full sm:w-auto">
                <select class="flex-1 sm:w-48 bg-white border-none rounded-full px-8 py-3 text-[10px] font-black uppercase tracking-widest ring-1 ring-black/[0.05] shadow-sm focus:ring-[var(--primary)]/30 outline-none cursor-pointer transition-all">
                    <option>Most Helpful</option>
                    <option>Most Recent</option>
                    <option>Highest Rating</option>
                    <option>Lowest Rating</option>
                </select>
            </div>
        </div>

        @php
            // Global review images for lightbox
            $allReviewImages = [];
            foreach($reviews as $rev) {
                if($rev->images) {
                    foreach($rev->images as $img) {
                        $allReviewImages[] = \App\Helpers\ImageHelper::getUrl($img, 'reviews');
                    }
                }
            }
        @endphp

        <!-- Reviews Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @php $currentGlobalImgIdx = 0; @endphp
            @forelse($reviews as $review)
                <div class="group bg-white p-7 rounded-[2rem] border border-gray-100 flex flex-col h-full transition-all duration-500 hover:border-[var(--primary)]/20 hover:shadow-2xl hover:shadow-black/[0.02]">
                    <div class="flex flex-col gap-5 flex-1">
                        <!-- Review Top: Rating & Title -->
                        <div class="flex items-center gap-4">
                            <div class="flex items-center gap-1 text-orange-600">
                                @for($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="h-3 w-3 {{ $i <= $review->rating ? 'fill-current' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                        </div>

                        <!-- Review Content -->
                        <p class="text-[13px] leading-relaxed text-[color:var(--text-secondary)] font-medium flex-1">
                            {{ $review->comment }}
                        </p>

                        <!-- Review Images -->
                        @if($review->images && count($review->images) > 0)
                            <div class="flex flex-wrap gap-2 pt-2">
                                @foreach($review->images as $img)
                                    <div class="group/img relative h-16 w-16 overflow-hidden rounded-xl bg-gray-50 ring-1 ring-black/5 cursor-zoom-in"
                                         onclick="openDynamicLightbox({{ json_encode($allReviewImages) }}, {{ $currentGlobalImgIdx++ }})">
                                        <img src="{{ \App\Helpers\ImageHelper::getUrl($img, 'reviews') }}" 
                                             alt="User review image" 
                                             class="h-full w-full object-cover transition duration-500 group-hover/img:scale-110">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Reviewer Info & Interactions -->
                    <div class="mt-8 pt-6 border-t border-black/[0.03] flex items-center justify-between">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-[color:var(--text-primary)]">{{ $review->user->name ?? 'Customer' }}</span>
                                <i data-lucide="check-circle-2" class="h-3.5 w-3.5 text-green-600"></i>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="h-20 w-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-6 text-slate-200"><i data-lucide="message-square" class="w-10 h-10"></i></div>
                    <h3 class="text-xl font-black text-slate-900">No reviews yet</h3>
                    <p class="text-slate-500 mt-2">Be the first to share your experience!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-20 flex justify-center">
            {{ $reviews->links() }}
        </div>
    </div>

    <!-- Image Lightbox Modal -->
    <div id="lightbox-modal" class="fixed inset-0 z-[100] hidden flex-col items-center justify-center bg-[#0a1a0f]/95 backdrop-blur-sm transition-all duration-300 p-3 sm:p-6">
        <!-- Close Button -->
        <button type="button" data-lightbox-close class="absolute right-3 top-3 sm:right-6 sm:top-6 z-[110] flex h-11 w-11 sm:h-12 sm:w-12 items-center justify-center rounded-full bg-black/40 backdrop-blur-md text-white hover:bg-red-500 transition-all duration-300 shadow-2xl ring-1 ring-white/20">
            <i data-lucide="x" class="h-6 w-6"></i>
        </button>

        <!-- Navigation -->
        <button type="button" data-lightbox-prev class="lightbox-nav-btn absolute left-3 sm:left-6 md:left-10 top-1/2 -translate-y-1/2 z-[120] flex h-10 w-10 sm:h-16 sm:w-16 items-center justify-center rounded-full bg-white/95 backdrop-blur-xl text-black transition-all duration-500 group shadow-[0_10px_40px_rgba(0,0,0,0.2)] ring-1 ring-black/5 hover:bg-black hover:text-white active:scale-90">
            <i data-lucide="chevron-left" class="h-5 w-5 sm:h-8 sm:w-8 transition-transform duration-500 group-hover:-translate-x-1"></i>
        </button>
        <button type="button" data-lightbox-next class="lightbox-nav-btn absolute right-3 sm:right-6 md:right-10 top-1/2 -translate-y-1/2 z-[120] flex h-10 w-10 sm:h-16 sm:w-16 items-center justify-center rounded-full bg-white/95 backdrop-blur-xl text-black transition-all duration-500 group shadow-[0_10px_40px_rgba(0,0,0,0.2)] ring-1 ring-black/5 hover:bg-black hover:text-white active:scale-90">
            <i data-lucide="chevron-right" class="h-5 w-5 sm:h-8 sm:w-8 transition-transform duration-500 group-hover:translate-x-1"></i>
        </button>

        <!-- Main Image Carousel -->
        <div class="relative h-full w-full flex items-center justify-center">
            <div class="lightbox-frame">
                <div class="lightbox-carousel owl-carousel owl-theme h-full w-full" data-gallery-type="reviews">
                    <!-- Dynamic content -->
                </div>
            </div>
        </div>

        <div class="absolute bottom-4 sm:bottom-8 left-1/2 -translate-x-1/2 z-[110] hidden">
            <p class="rounded-full bg-black/40 px-3 py-1 text-xs sm:text-sm font-black tracking-widest text-white/70 uppercase">
                <span id="lightbox-current" class="text-white">1</span> / <span id="lightbox-total">1</span>
            </p>
        </div>
    </div>

    <!-- Write a Review Modal -->
    <div id="review-modal" class="fixed inset-0 z-[100] hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeWriteReviewModal()"></div>
        <!-- Scroll Wrapper -->
        <div class="relative z-10 w-full h-full overflow-y-auto flex items-center justify-center px-3 py-6 sm:p-6">
            <div id="review-modal-card" class="relative w-full max-w-lg md:max-w-2xl bg-white rounded-2xl sm:rounded-3xl shadow-2xl overflow-hidden animate-[modalSlideUp_0.35s_ease-out] my-auto flex flex-col" style="max-height: calc(100vh - 3rem);">
                <!-- Modal Header -->
                <div class="flex items-center justify-between px-5 sm:px-6 pt-5 sm:pt-6 pb-4 border-b border-gray-100 shrink-0">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Write a Review</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Share your experience with this product</p>
                    </div>
                    <button type="button" onclick="closeWriteReviewModal()" class="h-9 w-9 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 hover:text-gray-600 transition">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="px-5 sm:px-6 py-5 flex-1 min-h-0 overflow-y-auto">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: Rating, Title, Review -->
                        <div class="space-y-5">
                            <!-- Star Rating -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">Your Rating</label>
                                <div class="flex items-center gap-1" id="review-star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" data-star="{{ $i }}" onclick="setReviewRating({{ $i }})" class="review-star p-0.5 text-gray-300 hover:text-[color:var(--primary)] transition-colors duration-150">
                                            <i data-lucide="star" class="h-7 w-7"></i>
                                        </button>
                                    @endfor
                                    <span id="rating-label" class="ml-3 text-xs font-medium text-gray-400">Select a rating</span>
                                </div>
                            </div>

                            <!-- Review Title -->
                            <div>
                                <label for="review-title" class="text-sm font-medium text-gray-700 mb-1.5 block">Review Title</label>
                                <input type="text" id="review-title" placeholder="Sum up your experience in a few words" 
                                       class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder:text-gray-400 transition" style="outline:none !important; box-shadow:none !important;">
                            </div>

                            <!-- Review Content -->
                            <div>
                                <label for="review-content" class="text-sm font-medium text-gray-700 mb-1.5 block">Your Review</label>
                                <textarea id="review-content" rows="4" placeholder="What did you like or dislike about this product?" 
                                          class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder:text-gray-400 transition resize-none" style="outline:none !important; box-shadow:none !important;"></textarea>
                            </div>
                        </div>

                        <!-- Right Column: Image Upload -->
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-700 mb-1.5 block">Add Photos <span class="text-gray-400 font-normal">(optional)</span></label>
                            <div id="review-upload-zone" class="relative border-2 border-dashed border-gray-200 rounded-xl p-5 sm:p-6 text-center cursor-pointer hover:border-[var(--primary)]/40 hover:bg-orange-50/30 transition-all duration-200 group flex-1 flex items-center justify-center min-h-[180px]">
                                <input type="file" id="review-images" multiple accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="handleReviewImageUpload(this)">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="h-12 w-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 group-hover:bg-orange-100 group-hover:text-[var(--primary)] transition">
                                        <i data-lucide="camera" class="h-6 w-6"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Click to upload photos</p>
                                        <p class="text-[10px] text-gray-400 mt-1">JPG, PNG up to 5MB · Max 4 photos</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Image Preview Grid -->
                            <div id="review-image-previews" class="flex flex-wrap gap-2 mt-3 hidden"></div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 bg-gray-50/50 shrink-0">
                    <button type="button" onclick="submitReview()" class="w-full py-3.5 rounded-xl bg-[var(--primary)] text-white text-sm font-semibold hover:bg-[var(--primary-hover)] transition active:scale-[0.98] shadow-lg shadow-[var(--primary)]/15 flex items-center justify-center gap-2">
                        Submit Review
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Hide counter specifically */
    #lightbox-current, #lightbox-total { display: none !important; }
    .lightbox-nav-btn { z-index: 120 !important; }

    /* Review modal: strip all focus outlines */
    #review-modal input:focus,
    #review-modal textarea:focus {
        outline: none !important;
        box-shadow: none !important;
        border-color: #d1d5db !important;
    }

    /* Review modal animation */
    @keyframes modalSlideUp {
        from { opacity: 0; transform: translateY(24px) scale(0.97); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .review-star.active svg {
        fill: currentColor;
    }
    #review-image-previews .preview-thumb {
        position: relative;
        width: 72px;
        height: 72px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.06);
    }
    #review-image-previews .preview-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    #review-image-previews .preview-thumb .remove-btn {
        position: absolute;
        top: 3px;
        right: 3px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(0,0,0,0.55);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 11px;
        line-height: 1;
        transition: background 0.15s;
    }
    #review-image-previews .preview-thumb .remove-btn:hover {
        background: rgba(220,38,38,0.85);
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function(){
        if(window.lucide) window.lucide.createIcons();
        const $lightbox = $(".lightbox-carousel");
        const $modal = $("#lightbox-modal");
        const $currentCounter = $("#lightbox-current");
        const $totalCounter = $("#lightbox-total");
        const $lightboxPrev = $("[data-lightbox-prev]");
        const $lightboxNext = $("[data-lightbox-next]");
        let currentImageIndex = 0;
        let lockedScrollY = 0;

        function setNavDisabled($btn, isDisabled) {
            $btn.toggleClass("!opacity-0 !pointer-events-none invisible", isDisabled);
        }

        function updateLightboxNavState(index) {
            const total = $lightbox.find('.owl-item:not(.cloned)').length;
            setNavDisabled($lightboxPrev, index <= 0);
            setNavDisabled($lightboxNext, index >= total - 1);
        }

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
                    updateLightboxNavState(index);
                }
            }
        });

        $lightboxPrev.on("click", function() {
            $lightbox.trigger("prev.owl.carousel");
        });

        $lightboxNext.on("click", function() {
            $lightbox.trigger("next.owl.carousel");
        });

        $("[data-lightbox-close]").on("click", function() {
            $modal.addClass("hidden").removeClass("flex");
            unlockBackgroundScroll();
        });

        window.openDynamicLightbox = function(images, index) {
            currentImageIndex = index;
            $modal.removeClass("hidden").addClass("flex");
            lockBackgroundScroll();

            setTimeout(() => {
                const currentType = $lightbox.attr('data-gallery-type');
                if (currentType) {
                    $lightbox.trigger('destroy.owl.carousel');
                    $lightbox.empty();
                    $lightbox.removeClass('owl-loaded owl-drag owl-hidden');
                }
                
                let html = '';
                images.forEach(src => {
                    html += `
                        <div class="relative flex h-full w-full items-center justify-center" data-lightbox-slide>
                            <img src="${src}" class="h-full w-full object-contain select-none" loading="lazy">
                        </div>
                    `;
                });
                $lightbox.append(html);
                $lightbox.attr('data-gallery-type', 'reviews');
                
                $totalCounter.text(images.length);
                
                $lightbox.owlCarousel({
                    items: 1,
                    loop: false,
                    dots: false,
                    nav: false,
                    smartSpeed: 400,
                    mouseDrag: true,
                    touchDrag: true,
                    pullDrag: true,
                    onChanged: function(event) {
                        if (event.item) {
                            const idx = event.item.index;
                            currentImageIndex = idx;
                            $currentCounter.text(idx + 1);
                            updateLightboxNavState(idx);
                        }
                    }
                });
                
                $lightbox.trigger('to.owl.carousel', [index, 0]);
                $currentCounter.text(index + 1);
                updateLightboxNavState(index);
                
                if (window.lucide) lucide.createIcons();
            }, 10);
        };

        window.closeLightbox = function() {
            $modal.addClass("hidden").removeClass("flex");
            unlockBackgroundScroll();
        };

        $(document).on("keydown", function(e) {
            if ($modal.hasClass("hidden")) return;
            if (e.key === "Escape") window.closeLightbox();
            if (e.key === "ArrowRight") $lightbox.trigger("next.owl.carousel");
            if (e.key === "ArrowLeft") $lightbox.trigger("prev.owl.carousel");
        });

        updateLightboxNavState(0);
    });

    // === Write a Review Modal ===
    let selectedRating = 0;
    let reviewUploadedFiles = [];
    const ratingLabels = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
    const $reviewModal = $('#review-modal');
    let reviewModalScrollY = 0;

    window.openWriteReviewModal = function() {
        selectedRating = 0;
        reviewUploadedFiles = [];
        $('#review-title').val('');
        $('#review-content').val('');
        $('#review-image-previews').empty().addClass('hidden');
        $('#rating-label').text('Select a rating').removeClass('text-orange-500');
        $('.review-star').removeClass('active text-[color:var(--primary)]').addClass('text-gray-300');
        
        reviewModalScrollY = window.scrollY || window.pageYOffset || 0;
        $('body').css({
            position: 'fixed',
            top: `-${reviewModalScrollY}px`,
            left: '0',
            right: '0',
            width: '100%',
            overflow: 'hidden'
        });
        $('#review-modal').removeClass('hidden');
        if (window.lucide) lucide.createIcons();
    };

    window.closeWriteReviewModal = function() {
        $('#review-modal').addClass('hidden');
        $('body').css({
            position: '',
            top: '',
            left: '',
            right: '',
            width: '',
            overflow: ''
        });
        window.scrollTo(0, reviewModalScrollY);
    };

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && !$('#review-modal').hasClass('hidden')) {
            closeWriteReviewModal();
        }
    });

    window.setReviewRating = function(rating) {
        selectedRating = rating;
        $('.review-star').each(function() {
            const starVal = parseInt($(this).data('star'));
            if (starVal <= rating) {
                $(this).addClass('active text-orange-400').removeClass('text-gray-300');
            } else {
                $(this).removeClass('active text-orange-400').addClass('text-gray-300');
            }
        });
        $('#rating-label').text(ratingLabels[rating]).addClass('text-orange-500').removeClass('text-gray-400');
    };

    window.handleReviewImageUpload = function(input) {
        const files = Array.from(input.files);
        const maxFiles = 4;
        const maxSize = 5 * 1024 * 1024;

        files.forEach(file => {
            if (reviewUploadedFiles.length >= maxFiles) return;
            if (file.size > maxSize) return;
            if (!file.type.startsWith('image/')) return;
            
            reviewUploadedFiles.push(file);
            const reader = new FileReader();
            reader.onload = function(e) {
                const idx = reviewUploadedFiles.length - 1;
                const thumb = `
                    <div class="preview-thumb" data-file-index="${idx}">
                        <img src="${e.target.result}" alt="Preview">
                        <div class="remove-btn" onclick="removeReviewImage(${idx})">✕</div>
                    </div>
                `;
                $('#review-image-previews').append(thumb).removeClass('hidden');
            };
            reader.readAsDataURL(file);
        });
        input.value = '';
    };

    window.removeReviewImage = function(index) {
        reviewUploadedFiles.splice(index, 1);
        renderReviewPreviews();
    };

    function renderReviewPreviews() {
        const $previews = $('#review-image-previews');
        $previews.empty();
        if (reviewUploadedFiles.length === 0) {
            $previews.addClass('hidden');
            return;
        }
        reviewUploadedFiles.forEach((file, idx) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                $previews.append(`
                    <div class="preview-thumb" data-file-index="${idx}">
                        <img src="${e.target.result}" alt="Preview">
                        <div class="remove-btn" onclick="removeReviewImage(${idx})">✕</div>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        });
        $previews.removeClass('hidden');
    }

    window.submitReview = function() {
        if (selectedRating === 0) {
            $('#rating-label').text('Please select a rating').css('color', '#ef4444');
            return;
        }
        const content = $('#review-content').val().trim();
        if (!content || content.length < 10) {
            Swal.fire({
                icon: 'warning',
                title: 'Review too short',
                text: 'Please write at least 10 characters for your review.',
                confirmButtonText: 'Understood',
                buttonsStyling: false,
                customClass: {
                    popup: 'premium-swal-popup',
                    confirmButton: 'premium-swal-confirm'
                }
            });
            return;
        }

        const $btn = $('#review-modal').find('button:contains("Submit Review")');
        const originalText = $btn.html();
        $btn.html('<span class="inline-block h-4 w-4 border-2 border-white/40 border-t-white rounded-full animate-spin"></span> Submitting...');
        $btn.prop('disabled', true);

        // Prepare FormData for image upload
        let formData = new FormData();
        formData.append('rating', selectedRating);
        formData.append('comment', content);
        formData.append('_token', '{{ csrf_token() }}');
        
        reviewUploadedFiles.forEach((file, i) => {
            formData.append('images[]', file);
        });

        $.ajax({
            url: '{{ route("products.reviews.store", $product->id) }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thank You!',
                    text: res.message,
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: {
                        popup: 'premium-swal-popup'
                    }
                });
                $btn.html('<svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg> Review Submitted!');
                $btn.removeClass('bg-[var(--primary)]').addClass('bg-green-500');
                setTimeout(() => {
                    closeWriteReviewModal();
                    window.location.reload();
                }, 1500);
            },
            error: function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: err.responseJSON?.message || 'Something went wrong. Please try again.',
                    confirmButtonText: 'Try Again',
                    buttonsStyling: false,
                    customClass: {
                        popup: 'premium-swal-popup',
                        confirmButton: 'premium-swal-confirm'
                    }
                });
                $btn.html(originalText);
                $btn.prop('disabled', false);
            }
        });
    };
</script>
@endpush

