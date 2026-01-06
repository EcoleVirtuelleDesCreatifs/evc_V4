<!-- Section Nos Lauréats -->
<div id="laureats" class="relative py-24 sm:py-32 overflow-hidden">

    <div class="absolute inset-0 opacity-70" style="background: linear-gradient(135deg, #063E77 0%, #2071C3 50%, #3399ff 100%);"></div>
    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
        @php
            $basePath = rtrim(request()->getBasePath(), '/');
            $edition = [
                'numero' => 4,
                'annee' => '2025',
                'badge' => 'Promotion Actuelle',
                'color' => 'from-purple-500 to-indigo-500',
                'laureats' => [
                    ['image' => 'laureats/edition-4-2025/Agnero-Alexandre-Cote-D-Ivoire.png', 'color' => 'from-indigo-500 to-indigo-600', 'name' => 'Agnero Alexandre', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                    ['image' => 'laureats/edition-4-2025/Pascal-Adjiri-Cote-d-Ivoire.png', 'color' => 'from-green-500 to-green-600', 'name' => 'Pascal Adjiri', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                    ['image' => 'laureats/edition-2024/Jean-Baptiste-Cote-d-Ivoire.jpg', 'color' => 'from-orange-500 to-orange-600', 'name' => 'Jean Baptiste', 'title' => 'Infographiste & Community Manager', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮', 'linkedin' => 'https://www.linkedin.com/in/jean-baptiste-enokou-62969819b/'],
                    ['image' => 'laureats/edition-2024/Yakouba-Adam-Cote-d-Ivoire.jpg', 'color' => 'from-purple-500 to-purple-600', 'name' => 'Yakouba Adam', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '��'],
                    ['image' => 'laureats/edition-4-2025/Dao-Sidiki-Burkina-Faso.png', 'color' => 'from-orange-500 to-orange-600', 'name' => 'Dao Sidiki', 'title' => '', 'country' => 'Burkina Faso', 'flag' => '��'],
                    ['image' => 'laureats/edition-4-2025/Soma-Roseline-Burkina-Faso.png', 'color' => 'from-purple-500 to-purple-600', 'name' => 'Soma Roseline', 'title' => '', 'country' => 'Burkina Faso', 'flag' => '🇧🇫'],
                ]
            ];
        @endphp

        <div class="text-center" data-aos="fade-up">
            <h2 class="text-base font-semibold leading-7 evc-orange">Nos Lauréats</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Ils transforment leur passion en carrière.</p>
        </div>

        <div class="mt-14 text-center mb-12" data-aos="fade-up">
            <div class="inline-flex flex-col items-center gap-3">
                <div class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r {{ $edition['color'] }} rounded-full shadow-lg">
                    <i class="fas fa-graduation-cap text-2xl text-white"></i>
                    <div class="text-left">
                        <div class="text-sm text-white/80 font-medium">{{ $edition['badge'] }}</div>
                        <div class="text-2xl font-bold text-white">Édition {{ $edition['numero'] }} - {{ $edition['annee'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($edition['laureats'] as $index => $laureat)
                <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/10 h-full flex flex-col text-center transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                        <div class="absolute -top-3 -right-3 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-2 shadow-lg">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>

                        <div class="relative mx-auto mb-6">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white/10 group-hover:border-orange-500/30 transition-all duration-300">
                                <a href="{{ $basePath }}/assets/img/{{ $laureat['image'] }}" data-fancybox="laureats-gallery" data-caption="{{ $laureat['name'] }}@if(!empty($laureat['title'])) - {{ $laureat['title'] }}@endif">
                                    <img src="{{ $basePath }}/assets/img/{{ $laureat['image'] }}"
                                         alt="{{ $laureat['name'] }}"
                                         class="w-full h-full object-cover object-top" loading="lazy" decoding="async">
                                </a>
                            </div>
                            <div class="absolute bottom-2 right-2 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">{{ $laureat['name'] }}</h3>

                        <div class="flex items-center justify-center gap-2 mb-4">
                            <span class="text-2xl">{{ $laureat['flag'] }}</span>
                            <span class="text-sm text-gray-400">{{ $laureat['country'] }}</span>
                        </div>

                        <div class="flex-grow">
                            @if(!empty($laureat['title']))
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10">
                                    <i class="fas fa-briefcase text-orange-500 text-xs"></i>
                                    <p class="text-sm text-gray-300">{{ $laureat['title'] }}</p>
                                </div>
                            @endif
                        </div>

                        @if(isset($laureat['linkedin']))
                            <div class="mt-4 pt-4 border-t border-white/10">
                                <a href="{{ $laureat['linkedin'] }}" target="_blank" class="text-gray-400 hover:text-orange-500 transition-colors text-sm inline-flex items-center">
                                    <i class="fab fa-linkedin mr-2"></i>Voir le profil
                                </a>
                            </div>
                        @endif
                    </div>
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
