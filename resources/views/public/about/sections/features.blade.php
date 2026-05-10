<!-- Brand Philosophy Detailed -->
<section class="bg-white py-24 text-[color:var(--text-primary)] overflow-hidden">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        <div class="text-center">
            <span class="text-xs font-black uppercase tracking-[0.4em] text-[color:var(--primary)] mb-6 block">Our Quality Pledge</span>
            <h2 class="section-heading">The Gold Standard</h2>
            
            <div class="mt-20 grid grid-cols-1 gap-8 lg:grid-cols-3">
                @foreach($features as $feature)
                    <div class="flex flex-col items-center text-center p-12 rounded-[3rem] {{ $feature['bg_class'] }} shadow-xl transition-all duration-500 hover:-translate-y-2">
                        <div class="h-20 w-20 rounded-3xl bg-white flex items-center justify-center shadow-lg mb-8">
                            <i data-lucide="{{ $feature['icon'] }}" class="h-10 w-10" style="color: {{ $feature['text_color'] }}"></i>
                        </div>
                        <h3 class="text-xl font-bold uppercase tracking-tight mb-4" style="color: {{ $feature['text_color'] }}">{{ $feature['title'] }}</h3>
                        <p class="text-base leading-relaxed font-medium" style="color: {{ $feature['text_color'] }}CC">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
