<!-- about section  -->
<section id="philosophy" class="relative overflow-hidden bg-[var(--bg-main)] py-6 lg:py-10">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        <div class="grid grid-cols-1 gap-16 lg:grid-cols-2 lg:items-center">
            <div class="relative z-10">
                <span class="text-xs font-black uppercase tracking-[0.4em] text-[color:var(--primary)] mb-6 block">
                    {{ $hero['tag'] }}
                </span>
                <h1 class="text-4xl sm:text-7xl font-black text-[color:var(--text-primary)] leading-[0.9] tracking-tighter mb-8 uppercase">
                    {!! $hero['title'] !!}
                </h1>
                <div class="space-y-6 text-base leading-relaxed text-[color:var(--text-secondary)] font-medium max-w-xl">
                    <p>
                        {{ $hero['description'] }}
                    </p>
                </div>
            </div>
            <div class="relative">
                <div class="aspect-square overflow-hidden rounded-[3rem] bg-[var(--bg-section)]">
                    <img src="{{ \App\Helpers\ImageHelper::getUrl($hero['image'], 'images/about') }}" alt="{{ strip_tags($hero['title']) }}"
                        class="h-full w-full object-cover">
                </div>
            </div>
        </div>
    </div>
</section>
