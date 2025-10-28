<!-- Section Nos Lauréats -->
<div id="laureats" class="relative py-24 sm:py-32 overflow-hidden">
    
    <div class="absolute inset-0 opacity-70" style="background: linear-gradient(135deg, #063E77 0%, #2071C3 50%, #3399ff 100%);"></div>
    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center" data-aos="fade-up">
            <h2 class="text-base font-semibold leading-7 evc-orange">Nos Lauréats</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Ils transforment leur passion en carrière.</p>
        </div>
        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-6 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-4 lg:gap-8">
            @php
                $laureats = [
                    ['img' => '5', 'name' => 'Adama Guèye', 'title' => 'Graphiste Senior @ Creative Corp', 'country' => 'Sénégal', 'flag' => '🇸🇳'],
                    ['img' => '6', 'name' => 'Coulibaly Bakary', 'title' => 'Social Media Manager @ Digital Wave', 'country' => 'Côte d’Ivoire', 'flag' => '🇨🇮'],
                    ['img' => '7', 'name' => 'Fatoumata Diarra', 'title' => 'Développeuse IA @ Tech Innovate', 'country' => 'Mali', 'flag' => '🇲🇱'],
                    ['img' => '8', 'name' => 'Jean Dupont', 'title' => 'Chef de Projet Digital @ EVC Studio', 'country' => 'France', 'flag' => '🇫🇷'],
                ];
            @endphp

            @foreach ($laureats as $laureat)
                <div class="card-hover-effect" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="https://i.pravatar.cc/1200?img={{ $laureat['img'] }}" data-fancybox="laureats-gallery" data-caption="{{ $laureat['name'] }} - {{ $laureat['title'] }}">
                        <div class="bg-black/20 backdrop-blur-lg rounded-2xl p-6 border border-white/10 h-full flex flex-col text-center">
                            <img class="aspect-square w-full rounded-full object-cover mx-auto shadow-lg" src="https://i.pravatar.cc/400?img={{ $laureat['img'] }}" alt="Photo de {{ $laureat['name'] }}">
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
