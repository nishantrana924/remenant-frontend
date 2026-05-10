<!-- Trust & Certifications Section -->
<section class="bg-[#F5F8F7] py-16 sm:py-24 border-y border-black/5 overflow-hidden">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        <div class="text-center mb-16">
            <h2 class="section-heading">{{ $certifications['title'] }}</h2>
            <p class="mt-4 text-base text-[color:var(--text-secondary)] font-medium">{{ $certifications['subtitle'] }}</p>
        </div>
    </div>

    <div class="marquee select-none">
        <div class="marquee__track gap-12 sm:gap-24 px-12">
            @foreach(range(1, 4) as $iteration)
                @foreach ($certifications['list'] as $cert)
                    <div class="flex flex-col items-center shrink-0 w-[180px]">
                        <div class="mb-4 flex h-20 w-20 items-center justify-center transition-transform hover:scale-110">
                            <img src="{{ !empty($cert['image']) ? \App\Helpers\ImageHelper::getUrl($cert['image'], 'images/about') : asset('images/icons/' . $cert['id'] . '.png') }}" alt="{{ $cert['name'] }}"
                                class="h-full w-full object-contain">
                        </div>
                        <h3 class="text-sm font-black uppercase tracking-widest text-[color:var(--text-primary)] text-center">
                            {{ $cert['name'] }}</h3>
                        <p
                            class="mt-1 text-[10px] font-bold text-[color:var(--text-secondary)] uppercase text-center whitespace-normal">
                            {{ $cert['desc'] }}</p>
                    </div>
                @endforeach
            @endforeach
        </div>
    </div>
</section>
