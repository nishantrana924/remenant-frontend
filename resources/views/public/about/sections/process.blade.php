<!-- The REMENANT Process -->
<section id="process" class="py-24 bg-[#F0F4F3] border-y border-black/5">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        <div class="text-center mb-20">
            <span class="text-xs font-black uppercase tracking-[0.3em] text-[color:var(--primary)]">{{ $process['tag'] }}</span>
            <h2 class="section-heading">{{ $process['title'] }}</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($process['steps'] as $step)
                <div class="relative flex flex-col p-8 rounded-[2.5rem] {{ $step['bg_class'] }} border border-black/5 shadow-xl shadow-black/[0.03] h-full transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:border-black/20">
                    <div class="relative mb-6 aspect-video overflow-hidden rounded-2xl bg-gray-100">
                        <img src="{{ \App\Helpers\ImageHelper::getUrl($step['image'], 'images/banners') }}" alt="{{ $step['title'] }}" class="h-full w-full object-cover">
                    </div>
                    <div class="absolute -top-6 left-10 h-14 w-14 rounded-2xl {{ $step['accent_color'] }} text-white flex items-center justify-center font-black text-2xl shadow-lg ring-4 ring-white">
                        {{ $step['number'] }}
                    </div>
                    <h3 class="mt-4 text-2xl font-extrabold text-[color:var(--text-primary)]">{{ $step['title'] }}</h3>
                    <p class="mt-4 text-[color:var(--text-secondary)] leading-relaxed text-sm font-medium">{{ $step['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
