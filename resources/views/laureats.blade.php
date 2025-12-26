@extends('layouts.app')

@section('title', 'Nos Lauréats | Success Stories EVC | Anciens Étudiants Design & Digital Abidjan')
@section('description', 'Découvrez les success stories de nos lauréats : designers graphiques, community managers et experts digitaux qui réussissent en Côte d\'Ivoire et à l\'international. Témoignages et parcours inspirants.')
@section('keywords', 'lauréats EVC, anciens étudiants, success stories, témoignages étudiants, carrière design graphique, emploi digital Abidjan, réussite professionnelle, alumni EVC, parcours étudiants')

@section('content')
<!-- Hero Section Amélioré -->
<div class="relative pt-32 sm:pt-40 lg:pt-48 pb-20 bg-gradient-to-br from-[#0a1128] via-[#001f54] to-[#034078] overflow-hidden">
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

            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight" data-aos="fade-up">
                Passez du Rêve<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">à la Réalité</span>
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
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">4</div>
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

<!-- Section Impact & Témoignage (REMONTE ICI pour la preuve sociale immédiate) -->
<div class="bg-gradient-to-b from-[#034078] to-[#001f54] py-20 relative overflow-hidden">
    <!-- Background elements -->
    <div class="absolute top-0 right-0 w-1/3 h-full bg-orange-500/5 blur-3xl pointer-events-none"></div>

    <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Statistiques d'impact -->
            <div data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6">
                    <i class="fas fa-fingerprint text-orange-500"></i>
                    <span class="text-orange-400 font-semibold text-sm">L'ADN EVC</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    L’École où l’on Apprend en Créant
                </h2>
                <p class="text-lg text-gray-400 mb-8">
                    À l’EVC, la pratique n’est pas une option, c’est l’ADN même de la pédagogie. Chaque projet est un terrain d’expérimentation, chaque challenge une étape vers la maîtrise.
                </p>

                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20">
                            <i class="fas fa-rocket text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Action Immédiate</h3>
                            <p class="text-gray-400">"On n’apprend pas la créativité dans les livres, mais dans l’action." — C'est cette action qui transforme nos étudiants en professionnels.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <i class="fas fa-user-tie text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Qualités Humaines</h3>
                            <p class="text-gray-400">Au-delà de la technique, nos étudiants développent rigueur, réactivité, communication et leadership indispensables en entreprise.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                            <i class="fas fa-briefcase text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">95% d'Employabilité</h3>
                            <p class="text-gray-400">Le Studio Créative est une rampe de lancement vers le monde professionnel. Nos lauréats sont prêts à l'emploi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Témoignage mis en avant -->
            <div data-aos="fade-left">
                <div class="relative">
                    <!-- Décoration -->
                    <div class="absolute -top-10 -right-10 text-[10rem] text-white/5 font-serif leading-none select-none">"</div>

                    <div class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/10 shadow-2xl relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg border-2 border-orange-400/50">
                                <span class="text-white text-xl font-bold">KN</span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-white">Kevin N'Guessan</h4>
                                <p class="text-sm text-gray-400">Étudiant en Design Graphique</p>
                            </div>
                        </div>

                        <div class="mb-6">
                            <i class="fas fa-quote-left text-orange-500 text-3xl mb-4"></i>
                            <p class="text-gray-300 text-lg leading-relaxed italic">
                                "Avant EVC, j'avais acheté des tutos que j'ai à peine regardés. Avec EVC, tout a été différent : la pédagogie, la structuration des cours, tout m'a convaincu. Quand je suis les formations, j'ai vraiment l'impression d'être dans la même salle que le formateur. Et surtout, la disponibilité 24h/24 du coach pour nous encadrer !"
                            </p>
                        </div>

                        <div class="flex items-center justify-between border-t border-white/10 pt-6">
                            <div class="flex items-center gap-1 text-orange-500">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-white/60 text-sm font-semibold">Promotion 2025</span>
                        </div>
                    </div>
                </div>

                <!-- Call to action secondaire -->
                <div class="mt-8 text-center">
                    <a href="{{ route('formations') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-orange-500/50 group">
                        <span>Découvrir Nos Formations</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Membres du Jury -->
<div class="bg-gradient-to-b from-[#001f54] to-[#001233] py-20 border-t border-white/5">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/30 rounded-full mb-6">
                <i class="fas fa-gavel text-blue-400"></i>
                <span class="text-blue-300 font-semibold text-sm">Jury d'Évaluation</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Des Talents Évalués par des Experts
            </h2>
            <p class="text-lg text-gray-400 max-w-3xl mx-auto">
                Un jury prestigieux composé de directeurs artistiques, entrepreneurs créatifs et designers confirmés. Ce panel d’experts vient évaluer, orienter et valoriser les projets des étudiants.
            </p>
        </div>

        <!-- Grille des membres du jury -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            @php
                $juryMembers = [
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
                ];
            @endphp

            @foreach($juryMembers as $index => $member)
            <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/10 h-full flex flex-col transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                    <!-- Badge Expert -->
                    <div class="absolute -top-3 -right-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full px-4 py-1 shadow-lg">
                        <span class="text-white text-xs font-bold">JURY</span>
                    </div>

                    <!-- Avatar -->
                    <div class="relative mx-auto mb-6">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white/10 group-hover:border-orange-500/50 transition-all duration-300">
                            <img src="{{ asset('assets/img/membre_du_jury/' . $member['image']) }}"
                                 alt="{{ $member['name'] }}"
                                 class="w-full h-full object-cover object-top">
                        </div>
                        <!-- Badge vérifié -->
                        <div class="absolute bottom-0 right-2 w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full border-2 border-white flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>

                    <div class="text-center">
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">{{ $member['name'] }}</h3>
                        <p class="text-sm text-gray-300 font-medium mb-3 h-10 overflow-hidden">{{ $member['title'] }}</p>

                        <!-- Pays -->
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <span class="text-2xl">{{ $member['flag'] }}</span>
                            <span class="text-sm text-gray-400">{{ $member['country'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Bouton Voir plus -->
        <div class="text-center mb-16" data-aos="fade-up">
            <a href="{{ route('jury') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white font-semibold transition-all duration-300 transform hover:scale-105">
                <span>Voir tous les membres du jury</span>
                <i class="fas fa-arrow-right text-orange-500"></i>
            </a>
        </div>
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

<!-- Section Studio Creative (DÉPLACÉ ICI : La méthode après la preuve) -->
<div class="bg-gradient-to-b from-[#001233] to-[#0a1128] py-20 relative">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-7xl"> <!-- Suppression de max-w-4xl pour permettre la largeur complète -->
            <!-- Titre -->
            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6">
                    <i class="fas fa-magic text-orange-400"></i>
                    <span class="text-orange-300 font-semibold text-sm">Le Secret de Fabrication</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">La Méthode "Studio Creative"</h2>
                <p class="text-lg text-gray-400 max-w-2xl mx-auto">Une approche pédagogique unique qui simule la réalité du monde professionnel pour vous rendre opérationnel dès la sortie.</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                <!-- Pourquoi ? -->
                <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/10 h-full flex flex-col hover:border-orange-500/30 transition-colors" data-aos="fade-right" data-aos-delay="100">
                    <div class="mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-orange-500/20 flex items-center justify-center mb-4 text-orange-400">
                            <i class="fas fa-lightbulb text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">
                            Une Expérience de Terrain
                        </h3>
                        <div class="h-1 w-20 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full"></div>
                    </div>

                    <div class="text-gray-300 space-y-6 leading-relaxed flex-grow">
                        <p>
                            Ici, pas de copies parfaites ni de discours théoriques. Le Studio Créative plonge les étudiants dans <strong class="text-white">les conditions réelles d’une agence de communication</strong>.
                        </p>

                        <div class="space-y-4">
                            <div class="flex gap-4 items-start p-4 bg-white/5 rounded-xl border border-white/5">
                                <i class="fas fa-users text-orange-400 mt-1"></i>
                                <div>
                                    <h4 class="text-white font-semibold text-sm mb-1">Immersion Totale</h4>
                                    <p class="text-sm text-gray-400">Brainstorming, gestion de projet, création d’identité visuelle, argumentation client, deadlines à respecter.</p>
                                </div>
                            </div>

                            <div class="flex gap-4 items-start p-4 bg-white/5 rounded-xl border border-white/5">
                                <i class="fas fa-layer-group text-orange-400 mt-1"></i>
                                <div>
                                    <h4 class="text-white font-semibold text-sm mb-1">Mini-Studios</h4>
                                    <p class="text-sm text-gray-400">Chaque équipe devient un mini-studio où l'on échange, on s’inspire et on ose comme des pros.</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-auto pt-6 border-t border-white/10">
                            <p class="text-orange-300 text-sm font-medium italic">
                                "C’est la salle de classe transformée en agence, et l’étudiant qui devient professionnel."
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Objectifs -->
                <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/10 h-full flex flex-col hover:border-orange-500/30 transition-colors" data-aos="fade-left" data-aos-delay="200">
                    <div class="mb-6">
                        <div class="w-14 h-14 rounded-2xl bg-orange-500/20 flex items-center justify-center mb-4 text-orange-400">
                            <i class="fas fa-bullseye text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">
                            Tremplin vers la Certification
                        </h3>
                        <div class="h-1 w-20 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full"></div>
                    </div>

                    <div class="space-y-6 flex-grow">
                        <p class="text-gray-300 leading-relaxed">
                            Le Studio Créative s’intègre dans le test d’éligibilité à la certification officielle de l’EVC, marquant la dernière étape d’un parcours d’excellence.
                        </p>

                        <div class="flex gap-4 group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                                    <span class="text-white font-bold text-sm">1</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Vitrine Vivante</h4>
                                <p class="text-sm text-gray-400 leading-relaxed">Démontrer l’étendue de son talent, sa personnalité et sa valeur sur le marché.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                                    <span class="text-white font-bold text-sm">2</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Opportunités Réelles</h4>
                                <p class="text-sm text-gray-400 leading-relaxed">Une chance unique de se faire remarquer, de se vendre et de décrocher des collaborations.</p>
                            </div>
                        </div>

                        <div class="flex gap-4 group">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                                    <span class="text-white font-bold text-sm">3</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-1">Réseautage Actif</h4>
                                <p class="text-sm text-gray-400 leading-relaxed">Présenter son savoir-faire directement à des professionnels du milieu créatif.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-4">
                        <div class="p-4 bg-orange-500/10 border border-orange-500/30 rounded-xl flex items-center gap-3">
                            <i class="fas fa-check-circle text-orange-400 text-xl"></i>
                            <p class="text-white text-sm font-medium">
                                Certains décrochent des stages ou des emplois dès cette journée de démonstration.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
