<!-- Hero Section -->
<main id="hero-section" class="relative bg-gradient-to-b from-[#000033] to-[#000066] pt-24 sm:pt-32 z-40">
    <div class="mx-auto max-w-7xl lg:grid lg:grid-cols-12 lg:gap-x-8 lg:px-8">
        <div class="px-6 lg:col-span-7 xl:col-span-6 text-center lg:text-left flex flex-col justify-center">
            <div class="relative z-10">
                <!-- Text Slider -->
                <div class="swiper-container hero-text-slider" style="height: 150px;">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide flex items-center justify-center lg:justify-start">
                            <h1 class="font-sans text-4xl sm:text-5xl md:text-6xl font-black text-white leading-tight">De Débutant à Pro</h1>
                        </div>
                        <div class="swiper-slide flex items-center justify-center lg:justify-start">
                            <h1 class="font-sans text-4xl sm:text-5xl md:text-6xl font-black text-white leading-tight">De La Passion au Métier</h1>
                        </div>
                        <div class="swiper-slide flex items-center justify-center lg:justify-start">
                            <h1 class="font-sans text-3xl sm:text-4xl md:text-5xl font-black text-white leading-tight">1ère École Digitale <span class="evc-orange">Ultra-Pratique</span><br>en Afrique Francophone</h1>
                        </div>
                    </div>
                </div>
                <p class="mt-4 text-lg leading-8 text-gray-300">
                    École légalement constituée en SARL, <strong class="text-white">reconnue par l’État ivoirien</strong>, EVC est spécialisée dans les domaines du <strong class="text-white">Design Graphique, du Community Management, du Social Media Management, de la Gestion en Informatique et l’Intelligence Artificielle appliquée</strong>.
                </p>
                <div class="mt-8 flex items-center justify-center lg:justify-start gap-x-6">
                    <a href="{{ route('preinscription.start') }}" class="btn btn-primary" aria-label="Démarrer ma préinscription">Je me préinscris</a>
                    <a href="#formations" class="btn btn-secondary">Nos formations <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </div>
        <div class="relative lg:col-span-5 xl:col-span-6 mt-16 lg:mt-0 flex items-center justify-center">
            <!-- Background Image Slider -->
            <div class="swiper-container hero-bg-slider w-full max-w-md lg:max-w-none aspect-square">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full">
                            <div class="h-full w-full bg-cover bg-center rounded-2xl" style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80');"></div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full">
                            <div class="h-full w-full bg-cover bg-center rounded-2xl" style="background-image: url('https://images.unsplash.com/photo-1556740738-b6a63e27c4df?auto=format&fit=crop&w=800&q=80');"></div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="bg-white/10 p-2 rounded-3xl backdrop-blur-sm h-full w-full">
                            <div class="h-full w-full bg-cover bg-center rounded-2xl" style="background-image: url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&q=80');"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</main>
