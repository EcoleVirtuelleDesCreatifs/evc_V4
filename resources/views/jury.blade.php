@extends('layouts.app')

@section('title', 'Membres du Jury - EVC | École Virtuelle des Créatifs')
@section('description', 'Découvrez les experts internationaux qui composent notre jury et qui guident nos étudiants vers l\'excellence dans les métiers du digital.')

@section('content')
    <!-- Page Jury -->
    <div class="relative py-24 sm:py-32 overflow-hidden min-h-screen">

        <div class="absolute inset-0 opacity-70" style="background: linear-gradient(135deg, #063E77 0%, #2071C3 50%, #3399ff 100%);"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
            <!-- Header -->
            <div class="mx-auto max-w-4xl lg:text-center mb-16" data-aos="fade-up">
                <h1 class="text-base font-semibold leading-7 evc-orange">Excellence & Expertise</h1>
                <p class="mt-2 text-4xl font-bold tracking-tight text-white sm:text-5xl">Tous les Membres du Jury</p>
                <div class="mt-6 h-1 w-32 bg-gradient-to-r from-orange-400 to-orange-600 mx-auto rounded-full"></div>
                <p class="mt-6 text-lg leading-8 text-white/90 max-w-3xl mx-auto">
                    Une équipe internationale d'experts qui évaluent, guident et inspirent nos étudiants à atteindre l'excellence dans les métiers du digital et de la créativité.
                </p>
            </div>

            <!-- Grid des membres du jury -->
            <div class="grid gap-8 lg:grid-cols-4 md:grid-cols-2 grid-cols-1">
                @php
                    $all_jury_members = [
                        [
                            'name' => 'Marc Aurèle',
                            'title' => 'Directeur Créatif Chez Agence X',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Marc-Aurele-Directeur-Creatif-Chez-Agence-X-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Danielle Attebi Epse Kouyo',
                            'title' => 'Chef de projet web',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Danielle-Attebi-Epse-Kouyo-Chef-de-projet-web-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Monsieur H',
                            'title' => 'Directeur Artistique',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Monsieur-H-Directeur-Artistique-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Adaezé Chukwu',
                            'title' => 'Creative Designer',
                            'country' => 'Nigeria',
                            'flag' => '🇳🇬',
                            'image' => 'Adaeze-Chukwu-Creative-Designer-Nigeria.jpg'
                        ],
                        [
                            'name' => 'Elie Foua Bi',
                            'title' => 'Directeur Artistique',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Elie-Foua-Bi-Directeur-Artistique-Cote-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Doris Dagri',
                            'title' => 'Graphiste Senior',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Doris-Dagri-Graphiste-Senior-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Jean Michel',
                            'title' => 'Créateur d\'expérience 360',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Jean-Michel-Createur-d-experience-360-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Délima Aby',
                            'title' => 'Infographiste senior',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Delima-Aby-Infographiste-senior-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Frank Ouedraogo',
                            'title' => 'Graphiste',
                            'country' => 'Burkina Faso',
                            'flag' => "\u{1F1E7}\u{1F1EB}",
                            'image' => 'Frank-Ouedraogo-Graphiste-Burkina-Faso.jpg'
                        ],
                        [
                            'name' => 'Alban M\'Lan',
                            'title' => 'Chef d\'Entreprise',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Alban-M-Lan-Chef-d-Entreprise-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Abdoul Latif',
                            'title' => 'Senior Graphiste',
                            'country' => 'Burkina Faso',
                            'flag' => "\u{1F1E7}\u{1F1EB}",
                            'image' => 'Abdoul-Latif-Senior-Graphiste-Burkina-Faso.jpg'
                        ],
                        [
                            'name' => 'Lydie Wendkuuni',
                            'title' => 'Graphic Designer',
                            'country' => 'Burkina Faso',
                            'flag' => "\u{1F1E7}\u{1F1EB}",
                            'image' => 'Lydie-Wendkuuni-Graphic-Designer-Burkina-Faso.jpg'
                        ],
                        [
                            'name' => 'Armel ABÉ',
                            'title' => 'Graphiste Photographe',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Armel-ABE-Graphiste-Photographe-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Bernice Alikpa',
                            'title' => 'Graphiste Designer Senior',
                            'country' => 'Bénin',
                            'flag' => '🇧🇯',
                            'image' => 'Bernice-Alikpa-Graphiste-designer-Senior-Benin.jpg'
                        ],
                        [
                            'name' => 'Check Maiga',
                            'title' => 'Graphiste Imprimeur',
                            'country' => 'Burkina Faso',
                            'flag' => "\u{1F1E7}\u{1F1EB}",
                            'image' => 'Check-Maiga-Graphiste-Imprimeur-Burkina-Faso.jpg'
                        ],
                        [
                            'name' => 'Cissé Moctar',
                            'title' => 'Journaliste Bilingue',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Cisse-Moctar-Journaliste-Bilinge-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Désiré Ganh',
                            'title' => 'Professeur en Design Graphic',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Desire-Ganh-Professeur-en-Design-Graphic-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Eugène Ndiolène',
                            'title' => 'Brand Identity Designer',
                            'country' => 'Sénégal',
                            'flag' => '🇸🇳',
                            'image' => 'Eugene-Ndiolene-Brand-Identity-Designer-Senegal.jpg'
                        ],
                        [
                            'name' => 'Ingrid Zaté',
                            'title' => 'Graphic Designer',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Ingrid-Zate-Graphic-Designer-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'K Steven Lanyan',
                            'title' => 'Graphiste Designer',
                            'country' => 'Bénin',
                            'flag' => '🇧🇯',
                            'image' => 'K-Steven-Lanyan-Graphiste-Designer-Benin.jpg'
                        ],
                        [
                            'name' => 'Somey Amegnibo',
                            'title' => 'Designer Créateur de Contenus',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Somey-Amegnibo-Designer-Createur-de-Contenus-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Sylla Rokia',
                            'title' => 'Journaliste Professionnelle',
                            'country' => 'Côte d\'Ivoire',
                            'flag' => '🇨🇮',
                            'image' => 'Sylla-Rokia-Journaliste-Professionnelle-Cote-d-Ivoire.jpg'
                        ],
                        [
                            'name' => 'Wei Zhang',
                            'title' => 'Expert Digital Innovation',
                            'country' => 'Chine',
                            'flag' => '🇨🇳',
                            'image' => 'https://randomuser.me/api/portraits/men/11.jpg',
                            'is_external' => true
                        ],
                        [
                            'name' => 'Omar Al-Fayed',
                            'title' => 'Senior Art Director',
                            'country' => 'Arabie Saoudite',
                            'flag' => '🇸🇦',
                            'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                            'is_external' => true
                        ],
                    ];
                @endphp

                @foreach ($all_jury_members as $index => $member)
                    <div class="card-hover-effect group" data-aos="fade-up" data-aos-delay="{{ ($index % 4) * 100 }}">
                        <div class="bg-black/20 backdrop-blur-lg rounded-2xl p-6 border border-white/10 h-full flex flex-col text-center transition-all duration-300 group-hover:bg-black/30 group-hover:border-orange-500/30">
                            <div class="w-32 h-32 mx-auto mb-4 relative rounded-full overflow-hidden border-4 border-white/20 shadow-lg group-hover:border-orange-500 transition-colors duration-300">
                                @if(isset($member['is_external']) && $member['is_external'])
                                    <img src="{{ $member['image'] }}"
                                         alt="{{ $member['name'] }}"
                                         class="w-full h-full object-cover object-top transition-transform duration-500">
                                @else
                                    <img src="{{ asset('assets/img/membre_du_jury/' . $member['image']) }}"
                                         alt="{{ $member['name'] }}"
                                         class="w-full h-full object-cover object-top transition-transform duration-500">
                                @endif
                            </div>
                            <h3 class="mt-4 text-lg font-semibold leading-tight tracking-tight text-white group-hover:text-orange-400 transition-colors">{{ $member['name'] }}</h3>
                            <p class="text-xs text-gray-300 mt-1"><span class="text-lg">{{ $member['flag'] }}</span> {{ $member['country'] }}</p>
                            <p class="text-sm leading-7 text-gray-400 flex-grow mt-2">{{ $member['title'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Section CTA -->
            <div class="mt-20" data-aos="fade-up" data-aos-delay="400">
                <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-orange-500 rounded-3xl p-12 shadow-2xl border border-orange-400/30">
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-white mb-4">Rejoignez une Formation d'Excellence</h2>
                        <p class="text-white/90 text-lg mb-8 max-w-2xl mx-auto">
                            Bénéficiez de l'expertise de ces professionnels reconnus et développez vos compétences dans le digital.
                        </p>
                        <a href="{{ route('preinscription.start') }}" class="inline-flex items-center gap-2 bg-white text-orange-600 font-bold px-10 py-5 rounded-full hover:bg-gray-50 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
                            <span>Préinscrivez-vous maintenant</span>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
