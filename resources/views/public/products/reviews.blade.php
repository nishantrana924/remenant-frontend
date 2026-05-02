@extends('public.layouts.app')

@section('title', $product['title'] . ' - Customer Reviews | Remenant Health')

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
                <li><a href="{{ route('products.show', $product['slug']) }}" class="text-[color:var(--text-muted)] hover:text-[color:var(--primary)] transition">{{ $product['title'] }}</a></li>
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
                        <img src="{{ asset('images/products/' . $product['image']) }}" alt="{{ $product['title'] }}" class="h-24 w-24 rounded-3xl bg-gray-50 object-contain p-3 shadow-inner">
                        <div>
                            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[color:var(--primary)]">Product Feedback</span>
                            <h1 class="text-2xl font-black text-[color:var(--text-primary)] leading-tight mt-1">{{ $product['title'] }}</h1>
                        </div>
                    </div>
                    
                    <div class="flex items-end gap-6">
                        <span class="text-7xl font-black text-[color:var(--text-primary)] tracking-tighter leading-none">{{ $product['rating'] }}</span>
                        <div class="pb-2">
                            <div class="flex text-orange-400 gap-0.5 mb-2">
                                @for($i = 0; $i < 5; $i++)
                                    <i data-lucide="star" class="h-5 w-5 {{ $i < floor($product['rating']) ? 'fill-current' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                            <p class="text-xs font-bold text-[color:var(--text-muted)] uppercase tracking-widest">Global score from {{ number_format($product['reviews']) }} buyers</p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 border-b lg:border-b-0 lg:border-r border-black/[0.05] pb-12 lg:pb-0 lg:px-12">
                    <h3 class="text-sm font-black uppercase tracking-widest mb-6 text-[color:var(--text-primary)]">Rating Breakdown</h3>
                    <div class="space-y-4">
                        @foreach([5 => 85, 4 => 10, 3 => 3, 2 => 1, 1 => 1] as $star => $percentage)
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
                    <button class="w-full rounded-full bg-[color:var(--text-primary)] text-white py-4 text-xs font-black uppercase tracking-[0.2em] transition-all duration-300 hover:bg-[var(--primary)] hover:shadow-xl hover:shadow-[var(--primary)]/20 active:scale-95">
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
            $sampleReviews = [
                [
                    'name' => 'Aditi Sharma',
                    'date' => '2 days ago',
                    'rating' => 5,
                    'title' => 'Truly Refreshing!',
                    'content' => 'I’ve been taking these for a month now and I can definitely feel the difference. It’s so much easier than swallowing big pills and the taste is amazing!',
                    'verified' => true,
                    'images' => ['remenant-product1.jpg', 'remenant-product13.jpg']
                ],
                [
                    'name' => 'Rohan Gupta',
                    'date' => '1 week ago',
                    'rating' => 5,
                    'title' => 'Best Wellness Product',
                    'content' => 'Highly recommend for anyone with a busy lifestyle. Quick, easy, and effective. The Apple Cider Vinegar flavor is my personal favorite.',
                    'verified' => true,
                    'images' => ['remenant-product10.jpg']
                ],
                [
                    'name' => 'Karan Patel',
                    'date' => '2 weeks ago',
                    'rating' => 4,
                    'title' => 'Great but slightly sweet',
                    'content' => 'The quality is top-notch and it fizzes perfectly. Just wish it was a tiny bit less sweet, but overall a great product that I will buy again.',
                    'verified' => true,
                    'images' => ['remenant-product5.jpg', 'remenant-product7.jpg']
                ],
                [
                    'name' => 'Priya Singh',
                    'date' => '3 weeks ago',
                    'rating' => 5,
                    'title' => 'Love the results',
                    'content' => 'The packaging is so premium. I’ve noticed my skin looking much clearer after using the Vitamin C tablets. Highly satisfied!',
                    'verified' => true,
                    'images' => ['remenant-product11.jpg']
                ],
                [
                    'name' => 'Amit Verma',
                    'date' => '1 month ago',
                    'rating' => 5,
                    'title' => 'Perfect for travelers',
                    'content' => 'I travel a lot for work and these are so easy to carry. No spillages like liquid vitamins. The fizz is very satisfying.',
                    'verified' => true,
                    'images' => []
                ],
                [
                    'name' => 'Sneha Reddy',
                    'date' => '1 month ago',
                    'rating' => 4,
                    'title' => 'Effective and yummy',
                    'content' => 'Tastes great and I feel more energetic throughout the day. Best way to start my morning routine.',
                    'verified' => true,
                    'images' => ['remenant-product12.jpg']
                ],
                [
                    'name' => 'Vikram Singh',
                    'date' => '2 months ago',
                    'rating' => 5,
                    'title' => 'Excellent Quality',
                    'content' => 'One of the best ACV tablets in the market. Doesn\'t feel artificial at all. The energy boost is real!',
                    'verified' => true,
                    'images' => ['remenant-product13.jpg']
                ],
                [
                    'name' => 'Ananya Das',
                    'date' => '2 months ago',
                    'rating' => 5,
                    'title' => 'Superb Packaging',
                    'content' => 'The attention to detail in packaging is amazing. The product itself is very effective and easy to use.',
                    'verified' => true,
                    'images' => ['remenant-product11.jpg']
                ]
            ];

            // Global review images for lightbox
            $allReviewImages = [];
            foreach($sampleReviews as $rev) {
                foreach($rev['images'] as $img) {
                    $allReviewImages[] = asset('images/products/' . $img);
                }
            }
        @endphp

        <!-- Reviews Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @php $currentGlobalImgIdx = 0; @endphp
            @foreach($sampleReviews as $review)
                <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-sm ring-1 ring-black/[0.03] transition-all duration-500 hover:shadow-xl hover:shadow-black/[0.02] flex flex-col h-full">
                    <div class="flex flex-col gap-5 flex-1">
                        <!-- Review Top: Rating & Title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1 rounded bg-green-600 px-2 py-1 text-[10px] font-black text-white">
                                <span>{{ $review['rating'] }}</span>
                                <i data-lucide="star" class="h-3 w-3 fill-current"></i>
                            </div>
                            <h3 class="text-base font-black text-[color:var(--text-primary)] tracking-tight">{{ $review['title'] }}</h3>
                        </div>

                        <!-- Review Content -->
                        <p class="text-sm leading-relaxed text-[color:var(--text-secondary)] flex-1">
                            {{ $review['content'] }}
                        </p>

                        <!-- Review Images -->
                        @if(!empty($review['images']))
                            <div class="flex flex-wrap gap-2 pt-2">
                                @foreach($review['images'] as $imgIndex => $img)
                                    <div class="group/img relative h-16 w-16 overflow-hidden rounded-xl bg-gray-50 ring-1 ring-black/5 cursor-zoom-in"
                                         onclick="openDynamicLightbox({{ json_encode($allReviewImages) }}, {{ $currentGlobalImgIdx++ }})">
                                        <img src="{{ asset('images/products/' . $img) }}" 
                                             alt="User review image" 
                                             class="h-full w-full object-cover transition duration-500 group-hover/img:scale-110">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            @php $currentGlobalImgIdx += 0; @endphp
                        @endif
                    </div>

                    <!-- Reviewer Info & Interactions -->
                    <div class="mt-8 pt-6 border-t border-black/[0.03] flex items-center justify-between">
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-black text-[color:var(--text-primary)]">{{ $review['name'] }}</span>
                                @if($review['verified'])
                                    <i data-lucide="check-circle-2" class="h-3.5 w-3.5 text-green-600"></i>
                                @endif
                            </div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $review['date'] }}</span>
                        </div>

                        <!-- Like/Dislike Icons Only -->
                        <div class="flex items-center gap-4">
                            <button type="button" class="group flex items-center gap-1.5 text-gray-400 hover:text-green-600 transition-colors">
                                <i data-lucide="thumbs-up" class="h-4 w-4 transition-transform group-active:scale-125"></i>
                                <span class="text-[10px] font-black">12</span>
                            </button>
                            <button type="button" class="group flex items-center gap-1.5 text-gray-400 hover:text-red-500 transition-colors">
                                <i data-lucide="thumbs-down" class="h-4 w-4 transition-transform group-active:scale-125"></i>
                                <span class="text-[10px] font-black">2</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modern Pagination -->
        <div class="mt-16 flex justify-center">
            <nav class="inline-flex items-center gap-2 p-2 rounded-full bg-white ring-1 ring-black/[0.05] shadow-sm">
                <button class="h-12 w-12 flex items-center justify-center rounded-full text-[color:var(--text-muted)] hover:bg-gray-50 transition active:scale-90">
                    <i data-lucide="arrow-left" class="h-5 w-5"></i>
                </button>
                <div class="flex items-center gap-1 px-4">
                    @foreach([1, 2, 3, '...', 24] as $page)
                        @if($page === '...')
                            <span class="px-2 text-xs font-bold text-gray-300">...</span>
                        @else
                            <button class="h-10 w-10 flex items-center justify-center rounded-full text-xs font-black transition {{ $page === 1 ? 'bg-[color:var(--text-primary)] text-white shadow-lg' : 'text-[color:var(--text-muted)] hover:bg-gray-50' }}">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                </div>
                <button class="h-12 w-12 flex items-center justify-center rounded-full text-[color:var(--text-muted)] hover:bg-gray-50 transition active:scale-90">
                    <i data-lucide="arrow-right" class="h-5 w-5"></i>
                </button>
            </nav>
        </div>
    </div>
</div>
@endsectiondsection
