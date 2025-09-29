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
                    ['img' => '5', 'name' => 'Adama Guèye', 'title' => 'Graphiste Senior @ Creative Corp'],
                    ['img' => '6', 'name' => 'Coulibaly Bakary', 'title' => 'Social Media Manager @ Digital Wave'],
                    ['img' => '7', 'name' => 'Fatoumata Diarra', 'title' => 'Développeuse IA @ Tech Innovate'],
                    ['img' => '8', 'name' => 'Jean Dupont', 'title' => 'Chef de Projet Digital @ EVC Studio'],
                ];
            @endphp

            @foreach ($laureats as $laureat)
                <div class="card-hover-effect" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="https://i.pravatar.cc/1200?img={{ $laureat['img'] }}" data-fancybox="laureats-gallery" data-caption="{{ $laureat['name'] }} - {{ $laureat['title'] }}">
                        <div class="bg-black/20 backdrop-blur-lg rounded-2xl p-6 border border-white/10 h-full flex flex-col text-center">
                            <img class="aspect-square w-full rounded-full object-cover mx-auto shadow-lg" src="https://i.pravatar.cc/400?img={{ $laureat['img'] }}" alt="Photo de {{ $laureat['name'] }}">
                            <h3 class="mt-6 text-lg font-semibold leading-tight tracking-tight text-white">{{ $laureat['name'] }}</h3>
                            <p class="text-sm leading-7 text-gray-400 flex-grow">{{ $laureat['title'] }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
