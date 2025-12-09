<!-- Section Membres du Jury -->
<div id="jury-members" class="relative py-24 sm:py-32 overflow-hidden">

    <div class="absolute inset-0 opacity-70" style="background: linear-gradient(135deg, #063E77 0%, #2071C3 50%, #3399ff 100%);"></div>

    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Header -->
        <div class="mx-auto max-w-4xl lg:text-center mb-16" data-aos="fade-up">
            <h2 class="text-base font-semibold leading-7 evc-orange">STUDIO CRÉATIF</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-white sm:text-4xl">Les Membres du Jury</p>
            <div class="mt-6 h-1 w-24 bg-gradient-to-r from-orange-400 to-orange-600 mx-auto rounded-full"></div>
        </div>


          <!-- Présentation Studio Créatif -->
        <div class="mx-auto max-w-4xl mt-16" data-aos="fade-up" data-aos-delay="400">
            <div class="text-center space-y-4">
                <p class="text-xl md:text-2xl font-bold text-white leading-relaxed">
                    <span class="text-orange-400">À EVC</span>, rien de grand ne se construit seul.
                </p>
                <p class="text-lg md:text-xl text-white/90 leading-relaxed">
                    Notre <span class="text-orange-300 font-semibold">Studio Créatif</span> permet aux étudiants de collaborer sur des <span class="text-orange-300 font-semibold">projets concrets</span> présentés devant un <span class="text-orange-300 font-semibold">jury international</span> qui évalue leur créativité et leur maîtrise professionnelle.
                </p>
            </div>
        </div>

        <!-- Membres du Jury -->
        <div class="mx-auto mt-16 grid max-w-2xl grid-cols-1 gap-6 sm:grid-cols-2 lg:mx-0 lg:max-w-none lg:grid-cols-4 lg:gap-8">
            @php
                $jury_members = [
                    [
                        'name' => 'Marc Aurèle',
                        'title' => 'Directeur Créatif Chez Agence X',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮',
                        'image' => 'Marc-Aurèle-Directeur-Créatif-Chez-Agence-X-Côte-d\'Ivoire.jpg'
                    ],
                    [
                        'name' => 'Danielle Attebi Epse Kouyo',
                        'title' => 'Chef de projet web',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮',
                        'image' => 'Danielle-Attebi-Epse-Kouyo-Chef-de-projet-web-Côte-d\'Ivoire.jpg'
                    ],
                    [
                        'name' => 'Monsieur H',
                        'title' => 'Directeur Artistique',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮',
                        'image' => 'Monsieur-H-Directeur-Artistique-Côte-d-Ivoire.jpg'
                    ],
                    [
                        'name' => 'Adaezé Chukwu',
                        'title' => 'Creative Designer',
                        'country' => 'Nigeria',
                        'flag' => '🇳🇬',
                        'image' => 'Adaezé-Chukwu-Creative-Designer-Nigeria.jpg'
                    ],
                    [
                        'name' => 'Elie Foua Bi',
                        'title' => 'Directeur Artistique',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮',
                        'image' => 'Elie-Foua-Bi-Directeur-Artistique-Côte-Ivoire.jpg'
                    ],
                    [
                        'name' => 'Doris Dagri',
                        'title' => 'Graphiste Senior',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮',
                        'image' => 'Doris-Dagri-Graphiste-Senior-Côte-d\'Ivoire.jpg'
                    ],
                    [
                        'name' => 'Jean Michel',
                        'title' => 'Créateur d\'expérience 360',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮',
                        'image' => 'Jean-Michel-Createur-d\'expérience-360-Côte-d\'Ivoire.jpg'
                    ],
                    [
                        'name' => 'Délima Aby',
                        'title' => 'Infographiste senior',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮',
                        'image' => 'Délima-Aby-Infographiste-senior-Côte-d\'Ivoire.jpg'
                    ],
                    [
                        'name' => 'Frank Ouedraogo',
                        'title' => 'Graphiste',
                        'country' => 'Burkina Faso',
                        'flag' => '🇧🇫',
                        'image' => 'Frank-Ouedraogo-Graphiste-Burkina-Faso.jpg'
                    ],
                    [
                        'name' => 'Alban M\'Lan',
                        'title' => 'Chef d\'Entreprise',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮',
                        'image' => 'Alban-M\'Lan-Chef-d\'Entreprise-Côte-d\'Ivoire.jpg'
                    ],
                    [
                        'name' => 'Abdoul Latif',
                        'title' => 'Senior Graphiste',
                        'country' => 'Burkina Faso',
                        'flag' => '🇧🇫',
                        'image' => 'Abdoul-Latif-Senior-Graphiste-Burkina-Faso.jpg'
                    ],
                    [
                        'name' => 'Lydie Wendkuuni',
                        'title' => 'Graphic Designer',
                        'country' => 'Burkina Faso',
                        'flag' => '🇧🇫',
                        'image' => 'Lydie-Wendkuuni-Graphic-Designer-Burkina-Faso.jpg'
                    ],
                ];
            @endphp

            @foreach ($jury_members as $member)
                <div class="card-hover-effect group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="bg-black/20 backdrop-blur-lg rounded-2xl p-6 border border-white/10 h-full flex flex-col text-center transition-all duration-300 group-hover:bg-black/30 group-hover:border-orange-500/30">
                        <div class="w-32 h-32 mx-auto mb-4 relative rounded-full overflow-hidden border-4 border-white/20 shadow-lg group-hover:border-orange-500 transition-colors duration-300">
                            <img src="{{ asset('assets/img/membre_du_jury/' . $member['image']) }}"
                                 alt="{{ $member['name'] }}"
                                 class="w-full h-full object-cover object-top transition-transform duration-500">
                        </div>
                        <h3 class="mt-4 text-lg font-semibold leading-tight tracking-tight text-white group-hover:text-orange-400 transition-colors">{{ $member['name'] }}</h3>
                        <p class="text-xs text-gray-300 mt-1"><span class="text-lg">{{ $member['flag'] }}</span> {{ $member['country'] }}</p>
                        <p class="text-sm leading-7 text-gray-400 flex-grow mt-2">{{ $member['title'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>



        <!-- Call to Action -->
        <div class="mt-16 text-center" data-aos="fade-up" data-aos-delay="500">
            <a href="{{ route('jury') }}"
               class="inline-flex items-center gap-2 px-10 py-5 text-lg font-semibold text-white bg-gradient-to-r from-orange-500 to-orange-600 rounded-full hover:from-orange-600 hover:to-orange-700 transform hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-orange-500/50">
                <span>Voir tous les membres du jury</span>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</div>
