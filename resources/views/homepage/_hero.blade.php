<!-- Hero Section -->
<main id="hero-section" class="relative bg-gradient-to-b from-[#000033] to-[#000066] pt-[180px] sm:pt-[300px] pb-12 sm:pb-16 z-40">
    @php
        $basePath = rtrim(request()->getBasePath(), '/');
    @endphp
    {{-- Spacer pour compenser topbar(40) + header(90) + flash(150) = 280px --}}
    <div class="mx-auto max-w-7xl lg:grid lg:grid-cols-12 lg:gap-x-3 lg:px-8">
        <div class="px-6 lg:col-span-7 xl:col-span-6 text-center lg:text-left flex flex-col justify-center">
            <div class="relative z-10">
                <!-- Image Slider - Visible uniquement sur mobile, positionné AU DESSUS des titres -->
                <div class="relative lg:hidden mt-12 mb-8 flex items-center justify-center">
                    <div class="swiper-container hero-bg-slider-mobile w-full max-w-md aspect-square">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full hero-slide-shell">
                                    <div class="h-full w-full bg-cover bg-center rounded-2xl hero-slide-bg" style="background-image: url('{{ $basePath }}/assets/img/cover/formation-infographie-a-abidjan.jpg'), url('/assets/img/cover/formation-infographie-a-abidjan.jpg');"></div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full hero-slide-shell">
                                    <div class="h-full w-full bg-cover bg-center rounded-2xl hero-slide-bg" style="background-image: url('{{ $basePath }}/assets/img/cover/formation-community-manager-a-abidjan.jpg'), url('/assets/img/cover/formation-community-manager-a-abidjan.jpg');"></div>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full hero-slide-shell">
                                    <div class="h-full w-full bg-cover bg-center rounded-2xl hero-slide-bg" style="background-image: url('{{ $basePath }}/assets/img/cover/premiere-ecole-dgitale-ultra-pratique-a-abidjan.jpg'), url('/assets/img/cover/premiere-ecole-dgitale-ultra-pratique-a-abidjan.jpg');"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Text Slider -->
                <div class="swiper-container hero-text-slider" style="height: auto;">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide flex items-center justify-center lg:justify-start">
                            <h1 class="font-sans text-2xl sm:text-5xl md:text-6xl font-black text-white leading-tight text-center lg:text-left">De Débutant <br/>à Pro : C'est Possible</h1>
                        </div>
                        <div class="swiper-slide flex items-center justify-center lg:justify-start">
                            <h1 class="font-sans text-2xl sm:text-5xl md:text-6xl font-black text-white leading-tight text-center lg:text-left">De La Passion <br/>au Métier : C'est Possible</h1>
                        </div>
                        <div class="swiper-slide flex items-center justify-center lg:justify-start">
                            <h1 class="font-sans text-2xl sm:text-5xl md:text-6xl font-black text-white leading-tight text-center lg:text-left">1ère Ecole Digitale Ultra-Pratique</h1>
                        </div>
                    </div>
                </div>
                <p class="mt-6 text-base sm:text-lg leading-7 sm:leading-8 text-gray-300 text-center lg:text-left">
                    <strong class="text-white">L’École Virtuelle des Créatifs (EVC) est une référence incontournable en formation digitale. Reconnue par l’État ivoirien, cette SARL mise sur une pédagogie 100 % Ultra-pratique, axée sur les besoins réels du marché.</strong>
                </p>

                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 sm:gap-x-6">
                    <a href="{{ route('preinscription.start') }}" class="btn btn-primary w-full sm:w-auto" aria-label="Démarrer ma préinscription">Je me préinscris</a>
                    <a href="#formations" class="btn btn-secondary w-full sm:w-auto">Nos formations <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </div>

        <!-- Image Slider Desktop - Visible uniquement sur desktop -->
        <div class="relative hidden lg:flex lg:col-span-5 xl:col-span-6 mt-16 lg:mt-0 items-center justify-center">
            <div class="swiper-container hero-bg-slider w-full max-w-md lg:max-w-none aspect-square">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full hero-slide-shell">
                            <div class="h-full w-full bg-cover bg-center rounded-2xl hero-slide-bg" style="background-image: url('{{ $basePath }}/assets/img/cover/formation-infographie-a-abidjan.jpg'), url('/assets/img/cover/formation-infographie-a-abidjan.jpg');"></div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full hero-slide-shell">
                            <div class="h-full w-full bg-cover bg-center rounded-2xl hero-slide-bg" style="background-image: url('{{ $basePath }}/assets/img/cover/formation-community-manager-a-abidjan.jpg'), url('/assets/img/cover/formation-community-manager-a-abidjan.jpg');"></div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full hero-slide-shell">
                            <div class="h-full w-full bg-cover bg-center rounded-2xl hero-slide-bg" style="background-image: url('{{ $basePath }}/assets/img/cover/premiere-ecole-dgitale-ultra-pratique-a-abidjan.jpg'), url('/assets/img/cover/premiere-ecole-dgitale-ultra-pratique-a-abidjan.jpg');"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="swiper-button-prev hero-nav-btn hero-nav-prev" aria-label="Slide précédent"></div>
    <div class="swiper-button-next hero-nav-btn hero-nav-next" aria-label="Slide suivant"></div>
</main>
