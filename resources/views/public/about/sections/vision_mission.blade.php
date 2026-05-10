<!-- Vision & Mission Redesign -->
<section class="py-12 lg:py-24 bg-white">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        <!-- Phase 1: Our Vision -->
        <div class="relative flex flex-col lg:flex-row items-center gap-12 lg:gap-0">
            <div class="hidden lg:block w-full lg:w-[60%] relative group">
                <div class="aspect-[16/9] overflow-hidden rounded-[3rem] bg-[var(--bg-section)] shadow-2xl">
                    <img src="{{ \App\Helpers\ImageHelper::getUrl($vision['image'], 'images/banners') }}" alt="{{ $vision['tag'] }}" class="h-full w-full object-cover transition duration-1000 group-hover:scale-105">
                </div>
            </div>
            <div class="w-full lg:w-[45%] lg:-ml-24 relative z-10 p-6 sm:p-10 lg:p-16 rounded-[3rem] bg-white border border-black/5 shadow-2xl backdrop-blur-xl">
                <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.3em] text-[var(--primary)] mb-6">
                    <span class="h-px w-8 bg-[var(--primary)]"></span>
                    {{ $vision['tag'] }}
                </span>
                <h2 class="section-heading text-left">{!! $vision['title'] !!}</h2>
                <p class="mt-8 text-base leading-relaxed text-[color:var(--text-secondary)] font-medium">{{ $vision['description'] }}</p>
            </div>
        </div>

        <!-- Phase 2: Our Mission -->
        <div class="relative flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-0 mt-12 lg:mt-32">
            <div class="hidden lg:block w-full lg:w-[60%] relative group">
                <div class="aspect-[16/9] overflow-hidden rounded-[3rem] bg-[var(--bg-section)] shadow-2xl">
                    <img src="{{ \App\Helpers\ImageHelper::getUrl($mission['image'], 'images/banners') }}" alt="{{ $mission['tag'] }}" class="h-full w-full object-cover transition duration-1000 group-hover:scale-105">
                </div>
            </div>
            <div class="w-full lg:w-[45%] lg:-mr-24 relative z-10 p-6 sm:p-10 lg:p-16 rounded-[3rem] bg-[var(--primary)] text-white shadow-2xl">
                <span class="inline-flex items-center gap-2 text-xs font-black uppercase tracking-[0.3em] text-white/90 mb-6">
                    <span class="h-px w-8 bg-white"></span>
                    {{ $mission['tag'] }}
                </span>
                <h2 class="section-heading text-left !text-white">{!! $mission['title'] !!}</h2>
                <p class="mt-8 text-base leading-relaxed text-white/90 font-medium">{{ $mission['description'] }}</p>
            </div>
        </div>
    </div>
</section>
