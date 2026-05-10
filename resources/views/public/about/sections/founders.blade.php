<!-- Meet the Founders: Clean Simple UI -->
<section id="founders" class="bg-[var(--bg-main)] py-16 lg:py-24">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        <div class="text-center mb-16">
            <span class="text-xs font-black uppercase tracking-[0.3em] text-[color:var(--primary)]">{{ $founders['tag'] }}</span>
            <h2 class="section-heading mt-4">{{ $founders['title'] }}</h2>
        </div>

        <div class="space-y-24 lg:space-y-32">
            @foreach($founders['list'] as $founder)
                <div class="flex flex-col lg:flex-row {{ $founder['reverse'] ? 'lg:flex-row-reverse' : '' }} items-center gap-12 lg:gap-24">
                    <div class="w-full lg:w-[35%] group">
                        <div class="relative aspect-square max-w-sm mx-auto overflow-hidden rounded-[2.5rem] bg-white border border-black/5 shadow-xl transition-all duration-500 group-hover:shadow-2xl">
                            <img src="{{ \App\Helpers\ImageHelper::getUrl($founder['image'], 'images/about') }}" alt="{{ $founder['name'] }}"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                        </div>
                    </div>
                    <div class="w-full lg:w-[65%] {{ $founder['reverse'] ? 'lg:text-right' : '' }}">
                        <div class="max-w-xl {{ $founder['reverse'] ? 'lg:ml-auto' : '' }}">
                            <div class="flex items-center {{ $founder['reverse'] ? 'lg:flex-row-reverse' : '' }} mb-6">
                                <span class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-[var(--primary)]">
                                    <span class="h-px w-6 bg-[var(--primary)]"></span>
                                    {{ $founder['role'] }}
                                </span>
                            </div>
                            <h3 class="section-heading {{ $founder['reverse'] ? 'lg:text-right' : '' }} !text-4xl mb-2">{{ $founder['name'] }}</h3>
                            <p class="text-xl font-bold text-[color:var(--primary)] italic mb-8 uppercase tracking-widest">{{ $founder['degree'] }}</p>
                            
                            <div class="space-y-6 text-base leading-relaxed text-[color:var(--text-secondary)] font-medium">
                                @foreach($founder['bio'] as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
