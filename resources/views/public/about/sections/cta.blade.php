<!-- Why Remenant Banner (Short Height - Customized for About) -->
<section class="relative my-8 lg:my-20">
    <div class="mx-auto max-w-[1600px] px-4 sm:px-6 lg:px-12">
        <div class="relative rounded-[2rem] bg-[var(--bg-peach)] px-8 pt-10 pb-0 sm:px-16 sm:pt-16 sm:pb-16 lg:py-16 border border-white/10 shadow-2xl">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:items-center">                    
                <!-- Content Right (Text) -->
                <div class="relative z-20 order-1 lg:order-2 text-center lg:text-left">
                    <h2 class="section-heading !text-white text-center lg:text-left">
                        {{ $cta['title'] }}
                    </h2>
                    <p class="mt-4 text-base text-white/90 max-w-lg mx-auto lg:mx-0 font-medium">
                        {{ $cta['description'] }}
                    </p>
                    <div class="mt-10 flex flex-wrap justify-center lg:justify-start gap-4">
                        <!-- WhatsApp Button -->
                        <a href="{{ $cta['whatsapp'] }}" target="_blank"
                            class="inline-flex items-center gap-3 rounded-[20px] bg-[#25D366] px-8 py-4 hover:opacity-90 transition shadow-xl hover:-translate-y-1 transition-all duration-300 group">
                            <svg class="h-6 w-6 fill-white" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                            <div class="text-left">
                                <p class="text-[10px] uppercase leading-none font-bold tracking-wider !text-white">Chat
                                    with us</p>
                                <p class="text-base font-bold leading-tight !text-white">WhatsApp</p>
                            </div>
                        </a>
                        <!-- Shop Button -->
                        <a href="{{ $cta['shop_url'] }}"
                            class="inline-flex items-center gap-3 rounded-[20px] bg-black px-8 py-4 hover:bg-black/90 transition shadow-xl hover:-translate-y-1 transition-all duration-300">
                            <i data-lucide="shopping-bag" class="h-6 w-6 !text-white"></i>
                            <div class="text-left">
                                <p class="text-[10px] uppercase leading-none font-bold tracking-wider !text-white">Available now</p>
                                <p class="text-base font-bold leading-tight !text-white">Shop All Products</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Mockup Left (Image) -->
                <div class="order-2 lg:order-1 flex justify-center lg:block">
                    <div
                        class="lg:absolute relative -mt-10 lg:mt-0 bottom-0 left-0 lg:left-10 w-full lg:w-[450px] flex justify-center lg:justify-start pointer-events-none">
                        <img src="{{ \App\Helpers\ImageHelper::getUrl($cta['image'], 'images/about') }}" alt="{{ $cta['title'] }}"
                            class="w-[260px] sm:w-[320px] lg:w-full h-auto block lg:scale-105 lg:origin-bottom transition-transform duration-500 hover:scale-110">
                    </div>
                </div>
            </div>

            <!-- Decorative elements (Clipped) -->
            <div class="absolute inset-0 overflow-hidden rounded-[2rem] pointer-events-none">
                <div class="absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 h-64 w-64 rounded-full bg-black/5 blur-3xl"></div>
            </div>
        </div>
    </div>
</section>
