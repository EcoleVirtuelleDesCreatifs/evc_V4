@extends('layouts.app')

@section('title', 'Lauréats EVC Abidjan | Success Stories École Numérique | Designers & Community Managers Côte d\'Ivoire')
@section('description', 'Découvrez les lauréats de l\'EVC, école numérique d\'Abidjan : designers graphiques, motion designers, community managers qui réussissent en Côte d\'Ivoire et à l\'international. Témoignages inspirants.')
@section('keywords', 'lauréats école numérique Abidjan, EVC lauréats, anciens étudiants design graphique Abidjan, emploi community management Abidjan, réussite formation digitale Côte d\'Ivoire, alumni EVC, école virtuelle des créatifs')

@section('content')
<!-- Hero Section Amélioré -->
<div class="relative pt-[500px] pb-20 bg-gradient-to-br from-[#0a1128] via-[#001f54] to-[#034078] overflow-hidden">
    <!-- Effets de fond animés -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255, 152, 0, 0.15) 1px, transparent 0); background-size: 40px 40px;"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6" data-aos="fade-down">
                <i class="fas fa-trophy text-orange-500"></i>
                <span class="text-orange-400 font-semibold text-sm">Success Stories</span>
            </div>

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight" data-aos="fade-up">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">NOS LAUREATS</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-8" data-aos="fade-up" data-aos-delay="100">
                Il est des moments qui marquent une vie d’étudiant. Des instants où la théorie s’efface pour laisser place à l’audace, à la création et à la réalité du métier.
            </p>

            <!-- Stats rapides -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">16+</div>
                    <div class="text-sm text-gray-400">Lauréats</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">18+</div>
                    <div class="text-sm text-gray-400">Pays</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">95%</div>
                    <div class="text-sm text-gray-400">Employabilité</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">5</div>
                    <div class="text-sm text-gray-400">Éditions</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Flèche de défilement -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce" data-aos="fade-up" data-aos-delay="400">
        <i class="fas fa-chevron-down text-orange-500 text-2xl"></i>
    </div>
</div>



<!-- Lauréats par Édition -->
<div class="bg-gradient-to-b from-[#001233] to-[#001233] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">

        <!-- Intro Section -->
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                <span class="text-orange-500">16 Talents</span> Formés, <span class="text-orange-500">16 Carrières</span> Lancées
            </h2>
            <p class="text-lg text-gray-400 max-w-3xl mx-auto">
                De 2021 à 2024, nos lauréats ont intégré les meilleures agences et entreprises du digital en Afrique et à l'international
            </p>
        </div>

        @php
            $editions = [
                [
                    'numero' => 5,
                    'annee' => '2026',
                    'badge' => 'Nouvelle Promotion',
                    'color' => 'from-pink-500 to-rose-500',
                    'laureats' => [
                        ['image' => 'laureats/edition-5/Afi-Constance-Sitsofe-Ayim-Togo.png', 'color' => 'from-indigo-500 to-indigo-600', 'name' => 'Afi Constance Sitsofé Ayim', 'title' => '', 'country' => 'Togo', 'flag' => '🇹🇬'],
                        ['image' => 'laureats/edition-5/Anna-Mae-Priscille-Apphiwa-Loukou-Yao-Cote-d-ivoire.jpg', 'color' => 'from-green-500 to-green-600', 'name' => 'Anna Maé Priscille Apphiwa Loukou Yao', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['image' => 'laureats/edition-5/Yao-Desire-Aime-Avonyo-Cote-d-Ivoire.png', 'color' => 'from-orange-500 to-orange-600', 'name' => 'Yao Désiré Aimé Avonyo', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['image' => 'laureats/edition-5/jean-yves-roland-n-cho-Maroc.jpg', 'color' => 'from-purple-500 to-purple-600', 'name' => 'Jean-Yves Roland N\'cho', 'title' => '', 'country' => 'Maroc', 'flag' => '🇲🇦'],
                    ]
                ],
                [
                    'numero' => 4,
                    'annee' => '2025',
                    'badge' => 'Promotion Actuelle',
                    'color' => 'from-purple-500 to-indigo-500',
                    'laureats' => [
                        ['image' => 'laureats/edition-4-2025/Agnero-Alexandre-Cote-D-Ivoire.png', 'color' => 'from-indigo-500 to-indigo-600', 'name' => 'Agnero Alexandre', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['image' => 'laureats/edition-4-2025/Pascal-Adjiri-Cote-d-Ivoire.png', 'color' => 'from-green-500 to-green-600', 'name' => 'Pascal Adjiri', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['image' => 'laureats/edition-2024/Jean-Baptiste-Cote-d-Ivoire.jpg', 'color' => 'from-orange-500 to-orange-600', 'name' => 'Jean Baptiste', 'title' => 'Infographiste & Community Manager', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮', 'linkedin' => 'https://www.linkedin.com/in/jean-baptiste-enokou-62969819b/'],
                        ['image' => 'laureats/edition-2024/Yakouba-Adam-Cote-d-Ivoire.jpg', 'color' => 'from-purple-500 to-purple-600', 'name' => 'Yakouba Adam', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['image' => 'laureats/edition-4-2025/Dao-Sidiki-Burkina-Faso.png', 'color' => 'from-orange-500 to-orange-600', 'name' => 'Dao Sidiki', 'title' => '', 'country' => 'Burkina Faso', 'flag' => '🇧🇫'],
                        ['image' => 'laureats/edition-4-2025/Soma-Roseline-Burkina-Faso.png', 'color' => 'from-purple-500 to-purple-600', 'name' => 'Soma Roseline', 'title' => '', 'country' => 'Burkina Faso', 'flag' => '🇧🇫'],
                    ]
                ],
                [
                    'numero' => 3,
                    'annee' => '2024',
                    'badge' => 'Promotion Actuelle',
                    'color' => 'from-orange-500 to-red-500',
                    'laureats' => [
                        ['image' => 'laureats/edition-2024/Adobley-Innocent-Togo.jpg', 'color' => 'from-indigo-500 to-indigo-600', 'name' => 'Adobley Innocent', 'title' => '', 'country' => 'Togo', 'flag' => '🇹🇬'],
                        ['image' => 'laureats/edition-2024/Dakouri-Isaie-Cote-d-Ivoire.jpg', 'color' => 'from-green-500 to-green-600', 'name' => 'Dakouri Isaie', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['image' => 'laureats/edition-2024/Fatou-Rebecca-Cote-d-Ivoire.jpg', 'color' => 'from-red-500 to-red-600', 'name' => 'Fatou Rebecca', 'title' => 'Graphic Designer | Communicante Junior', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮', 'linkedin' => 'https://www.linkedin.com/in/fatou-rebecca-zire-164664301/'],
                        ['image' => 'laureats/edition-2024/Jean-Baptiste-Cote-d-Ivoire.jpg', 'color' => 'from-orange-500 to-orange-600', 'name' => 'Jean Baptiste', 'title' => 'Infographiste & Community Manager', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮', 'linkedin' => 'https://www.linkedin.com/in/jean-baptiste-enokou-62969819b/'],
                        ['image' => 'laureats/edition-2024/Mathieu-Teyotonmin-Cote-d-Ivoire.jpg', 'color' => 'from-blue-500 to-blue-600', 'name' => 'Mathieu Téyotonmin', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['image' => 'laureats/edition-2024/Yakouba-Adam-Cote-d-Ivoire.jpg', 'color' => 'from-purple-500 to-purple-600', 'name' => 'Yakouba Adam', 'title' => '', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                    ]
                ],
                [
                    'numero' => '1 & 2',
                    'annee' => '2022-2023',
                    'badge' => 'Confirmés',
                    'color' => 'from-blue-500 to-cyan-500',
                    'laureats' => [
                        ['image' => 'laureats/edition-2022-2023/Bianca-Defo-Dubai.jpg', 'color' => 'from-sky-500 to-sky-600', 'name' => 'Bianca Defo', 'title' => 'Digital Marketing Manager', 'country' => 'Émirats Arabes Unis', 'flag' => '🇦🇪', 'linkedin' => 'https://www.linkedin.com/in/bianca-defo/'],
                        ['image' => 'laureats/edition-2022-2023/Dely-Ahileu-Cote-d-Ivoire.jpg', 'color' => 'from-emerald-500 to-emerald-600', 'name' => 'Dely Ahileu', 'title' => 'Infographiste Senior', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮', 'linkedin' => 'https://www.linkedin.com/in/dely-ahileu-8524a5313/'],
                        ['image' => 'laureats/edition-2022-2023/Eve-Adingra-Ghana.jpg', 'color' => 'from-fuchsia-500 to-fuchsia-600', 'name' => 'Eve Adingra', 'title' => 'Adjoint administratif | Comptabilité', 'country' => 'Ghana', 'flag' => '🇬🇭', 'linkedin' => 'https://www.linkedin.com/in/eve-carole-floriane-adingra-2861aba3/'],
                        ['image' => 'laureats/edition-2022-2023/Kouame-Yvannes-Cote-d-Ivoire.jpg', 'color' => 'from-cyan-500 to-cyan-600', 'name' => 'Kouamé Yvannes', 'title' => 'Entrepreneur social | Fondateur Le Cercle Rouge', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮', 'linkedin' => 'https://www.linkedin.com/in/maloudayvanneskouame5843/'],
                        ['image' => 'laureats/edition-2022-2023/Nagalo-Parfait-Burkina-Faso.jpg', 'color' => 'from-amber-500 to-amber-600', 'name' => 'Nagalo Parfait', 'title' => 'Heavy Equipment Trainer', 'country' => 'Burkina Faso', 'flag' => '🇧🇫', 'linkedin' => 'https://www.linkedin.com/in/y-boulayom-parfait-nagalo-583b2985/'],
                    ]
                ],
            ];
        @endphp

        @foreach($editions as $editionIndex => $edition)
        <!-- Édition {{ $edition['numero'] }} -->
        <div class="mb-20" data-aos="fade-up">
            <!-- Header de l'édition amélioré -->
            <div class="text-center mb-12">
                <div class="inline-flex flex-col items-center gap-3 mb-6">
                    <div class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r {{ $edition['color'] }} rounded-full shadow-lg">
                        <i class="fas fa-graduation-cap text-2xl text-white"></i>
                        <div class="text-left">
                            <div class="text-sm text-white/80 font-medium">{{ $edition['badge'] }}</div>
                            <div class="text-2xl font-bold text-white">Édition {{ $edition['numero'] }} - {{ $edition['annee'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grille des lauréats améliorée -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($edition['laureats'] as $index => $laureat)
                <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/10 h-full flex flex-col text-center transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                        <!-- Badge succès -->
                        <div class="absolute -top-3 -right-3 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-2 shadow-lg">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>

                        <!-- Avatar -->
                        <div class="relative mx-auto mb-6">
                            <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white/10 group-hover:border-orange-500/30 transition-all duration-300">
                                <img src="{{ asset('assets/img/' . $laureat['image']) }}"
                                     alt="{{ $laureat['name'] }}"
                                     class="w-full h-full object-cover object-top">
                            </div>
                            <!-- Indicateur en ligne -->
                            <div class="absolute bottom-2 right-2 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                        </div>

                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">{{ $laureat['name'] }}</h3>

                        <!-- Pays avec flag -->
                        <div class="flex items-center justify-center gap-2 mb-4">
                            <span class="text-2xl">{{ $laureat['flag'] }}</span>
                            <span class="text-sm text-gray-400">{{ $laureat['country'] }}</span>
                        </div>

                        <!-- Titre professionnel -->
                        <div class="flex-grow">
                            @if(!empty($laureat['title']))
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10">
                                <i class="fas fa-briefcase text-orange-500 text-xs"></i>
                                <p class="text-sm text-gray-300">{{ $laureat['title'] }}</p>
                            </div>
                            @endif
                        </div>

                        <!-- Lien LinkedIn (optionnel) -->
                        @if(isset($laureat['linkedin']))
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <a href="{{ $laureat['linkedin'] }}" target="_blank" class="text-gray-400 hover:text-orange-500 transition-colors text-sm inline-flex items-center">
                                <i class="fab fa-linkedin mr-2"></i>Voir le profil
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="group" data-aos="fade-up">
                    <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/10 h-full flex flex-col text-center">
                        <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-orange-500/20 to-orange-600/20 border border-orange-500/30 flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-orange-400 text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Lauréats à venir</h3>
                        <p class="text-sm text-gray-400">Cette édition est en cours. Les lauréats seront publiés prochainement.</p>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach

    </div>
</div>


<!-- Section Devenir Lauréat -->
<div class="bg-gradient-to-b from-[#0a1128] to-black py-20">
    <div class="mx-auto max-w-5xl px-6 lg:px-8 text-center">
        <div data-aos="zoom-in">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Prêt à Écrire Votre Success Story ?
            </h2>
            <p class="text-xl text-gray-400 mb-12 max-w-3xl mx-auto">
                Rejoignez la prochaine promotion et transformez votre passion pour le digital en une carrière épanouissante. Les inscriptions sont ouvertes !
            </p>

            <!-- Avantages rapides -->
            <div class="grid md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-colors">
                    <i class="fas fa-laptop-code text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Formation 100% Pratique</h3>
                    <p class="text-sm text-gray-400">Projets réels et portfolio professionnel</p>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-colors">
                    <i class="fas fa-certificate text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Certification Reconnue</h3>
                    <p class="text-sm text-gray-400">Diplôme valorisé par les entreprises</p>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10 hover:bg-white/10 transition-colors">
                    <i class="fas fa-handshake text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Accompagnement Carrière</h3>
                    <p class="text-sm text-gray-400">Suivi personnalisé jusqu'à l'emploi</p>
                </div>
            </div>

            <!-- CTA Principal -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('preinscription.start') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white text-lg font-bold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-2xl hover:shadow-orange-500/50">
                    <i class="fas fa-rocket"></i>
                    <span>Rejoindre la Prochaine Promotion</span>
                </a>
                <a href="https://wa.me/2250747259507?text=Bonjour,%20je%20souhaite%20obtenir%20plus%20d'informations%20sur%20les%20formations%20EVC" target="_blank" class="inline-flex items-center gap-3 px-10 py-5 bg-white/5 backdrop-blur-sm border border-white/20 rounded-full text-white text-lg font-semibold hover:bg-white/10 transition-all duration-300">
                    <i class="fab fa-whatsapp"></i>
                    <span>Parler à un Conseiller</span>
                </a>
            </div>

            <!-- Urgence -->
            <div class="mt-8 inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 border border-red-500/30 rounded-full animate-pulse">
                <i class="fas fa-clock text-red-500"></i>
                <span class="text-red-400 font-semibold text-sm">Places limitées - Inscrivez-vous maintenant !</span>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
@include('homepage._cta-final')
@endsection
