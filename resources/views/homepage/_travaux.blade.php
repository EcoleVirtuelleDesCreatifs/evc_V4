<!-- Section Travaux Étudiants -->
<div class="pt-32 sm:pt-40 lg:pt-48 pb-24 sm:pb-32 bg-gradient-to-b from-[#0a1128] via-[#001f54] to-[#034078]">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center" data-aos="fade-up">
            <h2 class="text-base font-semibold leading-7 evc-orange">Travaux Étudiants</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Nos étudiants ne se contentent pas d'apprendre, ils créent !</p>
        </div>

        @php
            $categories = [
                'affiches' => [1, 96, 29, 40, 109, 108],
                'crea' => [118],
                'events' => [1, 2, 9, 11, 15, 18, 20, 21, 101, 100, 87, 128, 127, 121, 114],
                'logos' => [48, 111, 49]
            ];
        @endphp

        <div class="relative mx-auto mt-16 max-w-6xl overflow-hidden">
            <div class="swiper travaux-carousel relative">
                <div class="swiper-wrapper">
                    @foreach($categories as $category => $images)
                        @foreach($images as $imageNum)
                            <div class="swiper-slide">
                                <a href="{{ asset('assets/img/tp_etudiant_evc/' . $category . '/' . $imageNum . '.jpg') }}"
                                   data-fancybox="gallery"
                                   data-caption="{{ ucfirst($category) }} - {{ $imageNum }}">
                                    <div class="overflow-hidden rounded-2xl shadow-lg aspect-square">
                                        <img src="{{ asset('assets/img/tp_etudiant_evc/' . $category . '/' . $imageNum . '.jpg') }}"
                                             alt="{{ ucfirst($category) }} {{ $imageNum }}"
                                             class="h-full w-full object-cover object-center transition-transform duration-300 hover:scale-105" loading="lazy" decoding="async">
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
            <!-- Navigation Buttons will be positioned by CSS -->
            <div class="swiper-button-prev travaux-prev"></div>
            <div class="swiper-button-next travaux-next"></div>
            <!-- Pagination -->
            <div class="swiper-pagination travaux-pagination mt-8 relative"></div>
        </div>

        <!-- Bouton Voir Plus -->
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('travaux') }}" class="inline-flex items-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-full shadow-lg hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300">
                <span>Voir plus de travaux</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
