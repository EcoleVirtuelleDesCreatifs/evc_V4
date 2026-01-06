<!-- Section Nos Lauréats -->
<div id="laureats" class="relative py-24 sm:py-32 overflow-hidden">

    <div class="absolute inset-0 opacity-70" style="background: linear-gradient(135deg, #063E77 0%, #2071C3 50%, #3399ff 100%);"></div>
    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
        @php
            $basePath = rtrim(request()->getBasePath(), '/');
        @endphp
        <div class="mx-auto max-w-2xl lg:text-center" data-aos="fade-up">
            <h2 class="text-base font-semibold leading-7 evc-orange">Nos Lauréats</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Ils transforment leur passion en carrière.</p>
        </div>
        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-6 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-3 lg:gap-8">
            @php
                $laureats = [
                    ['img' => 'laureats/edition-4-2025/Agnero-Alexandre-Cote-D-Ivoire.png', 'name' => 'Agnero Alexandre', 'title' => 'Lauréat Édition 4 - 2025', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                    ['img' => 'laureats/edition-4-2025/Pascal-Adjiri-Cote-d-Ivoire.png', 'name' => 'Pascal Adjiri', 'title' => 'Lauréat Édition 4 - 2025', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                    ['img' => 'laureats/edition-2024/Jean-Baptiste-Cote-d-Ivoire.jpg', 'name' => 'Jean Baptiste', 'title' => 'Infographiste & Community Manager', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                    ['img' => 'laureats/edition-4-2025/Dao-Sidiki-Burkina-Faso.png', 'name' => 'Dao Sidiki', 'title' => 'Lauréat Édition 4 - 2025', 'country' => 'Burkina Faso', 'flag' => '🇧🇫'],
                    ['img' => 'laureats/edition-2024/Yakouba-Adam-Cote-d-Ivoire.jpg', 'name' => 'Yakouba Adam', 'title' => 'Lauréat Édition 4 - 2025', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                    ['img' => 'laureats/edition-4-2025/Soma-Roseline-Burkina-Faso.png', 'name' => 'Soma Roseline', 'title' => 'Lauréat Édition 4 - 2025', 'country' => 'Burkina Faso', 'flag' => '🇧🇫'],
                ];
            @endphp

            @foreach ($laureats as $laureat)
                <div class="card-hover-effect" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ $basePath }}/assets/img/{{ $laureat['img'] }}" data-fancybox="laureats-gallery" data-caption="{{ $laureat['name'] }} - {{ $laureat['title'] }}">
                        <div class="bg-black/20 backdrop-blur-lg rounded-2xl p-6 border border-white/10 h-full flex flex-col text-center">
                            <img class="aspect-square w-full rounded-full object-cover mx-auto shadow-lg" src="{{ $basePath }}/assets/img/{{ $laureat['img'] }}" alt="Photo de {{ $laureat['name'] }}" loading="lazy" decoding="async">
                            <h3 class="mt-6 text-lg font-semibold leading-tight tracking-tight text-white">{{ $laureat['name'] }}</h3>
                            <p class="text-xs text-gray-300 mt-1"><span class="text-lg">{{ $laureat['flag'] }}</span> {{ $laureat['country'] }}</p>
                            <p class="text-sm leading-7 text-gray-400 flex-grow mt-2">{{ $laureat['title'] }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Bouton Voir tous les lauréats -->
        <div class="mt-12 text-center" data-aos="fade-up" data-aos-delay="500">
            <a href="{{ route('laureats') }}"
               class="inline-flex items-center gap-2 px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-full hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-orange-500/50">
                <span>Voir tous les lauréats</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
