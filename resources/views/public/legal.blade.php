@extends('public.layouts.app')

@section('title', $data['seo']['title'] ?? ($data['title'] . ' - Remenant Health'))
@section('meta_description', $data['seo']['description'] ?? '')

@section('content')
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-[var(--bg-main)] py-12 lg:py-20">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12 text-center">
            <div class="max-w-4xl mx-auto">
                <span class="text-[10px] sm:text-xs font-bold uppercase tracking-[0.3em] sm:tracking-[0.4em] text-[color:var(--primary)] mb-3 sm:mb-4 block">Legal Agreement</span>
                <h1 class="text-3xl sm:text-5xl lg:text-7xl font-bold text-[color:var(--text-primary)] leading-[1.1] sm:leading-[0.9] tracking-tighter mb-4 sm:mb-6 uppercase">
                    {{ $data['title'] }}
                </h1>
                <div class="flex items-center justify-center gap-2 sm:gap-3">
                    <span class="inline-flex items-center rounded-full bg-orange-50 px-3 py-1 text-[10px] font-bold uppercase tracking-widest text-[color:var(--primary)] ring-1 ring-orange-200">
                        Version 1.0
                    </span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Last Updated: {{ $data['last_updated'] ?? 'May 03, 2026' }}</span>
                </div>
            </div>
        </div>
        
        <!-- Decorative Background Element -->
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-[var(--primary)]/5 blur-3xl pointer-events-none"></div>
    </section>

    <!-- Content Section -->
    <section class="bg-white py-12 lg:py-24">
        <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-12">
                
                <!-- Sidebar Navigation (Desktop) -->
                <div class="hidden lg:block lg:col-span-4">
                    <div class="sticky top-32 space-y-2">
                        <nav class="flex flex-col gap-1">
                            @foreach($data['sections'] ?? [] as $index => $section)
                            <a href="#section-{{ $index }}" class="flex items-center gap-3 px-6 py-4 rounded-2xl text-sm font-bold uppercase tracking-widest text-gray-400 hover:text-[color:var(--primary)] hover:bg-orange-50/50 transition-all group">
                                <span class="h-px w-4 bg-gray-200 group-hover:bg-[color:var(--primary)] transition-colors"></span>
                                {{ $section['title'] }}
                            </a>
                            @endforeach
                        </nav>
                        
                        <!-- Contact Card -->
                        <div class="mt-12 p-8 rounded-[2rem] bg-[var(--primary-soft)] border border-[var(--primary)]/10 shadow-sm">
                            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-900 mb-4">Have Questions?</h4>
                            <p class="text-xs font-medium text-gray-500 leading-relaxed mb-6">If you have any questions about our terms, please contact our legal team.</p>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-[color:var(--primary)] hover:underline">
                                Contact Legal <i data-lucide="arrow-right" class="h-3 w-3"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <div class="prose prose-orange max-w-none space-y-16">
                        
                        @foreach($data['sections'] ?? [] as $index => $section)
                        <div id="section-{{ $index }}" class="scroll-mt-32">
                            <h2 class="text-lg sm:text-2xl lg:text-3xl font-bold text-gray-900 uppercase tracking-tight mb-3 sm:mb-8 flex items-center gap-2 sm:gap-4">
                                <span class="flex h-7 w-7 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg sm:rounded-xl bg-orange-100 text-[color:var(--primary)] text-[9px] sm:text-sm font-bold">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                {{ $section['title'] }}
                            </h2>
                            <div class="space-y-4 sm:space-y-6 text-sm sm:text-lg leading-relaxed text-gray-600 font-medium">
                                {!! nl2br(e($section['content'])) !!}
                            </div>
                        </div>
                        @endforeach

                    </div>

                    <!-- Simple CTA at bottom for mobile -->
                    <div class="mt-12 lg:hidden p-10 rounded-[3rem] bg-orange-50 border border-orange-100 text-center">
                         <h4 class="text-xl font-bold text-gray-900 mb-4">Questions?</h4>
                         <p class="text-sm font-medium text-gray-500 mb-8">Our legal team is here to help you understand our terms better.</p>
                         <a href="{{ route('contact') }}" class="inline-flex items-center justify-center px-8 py-4 rounded-2xl bg-[color:var(--primary)] text-white text-xs font-bold uppercase tracking-widest shadow-xl shadow-orange-200">
                             Contact Us
                         </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
