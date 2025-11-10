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
                Hier Étudiants,<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-orange-600">Aujourd'hui Professionnels</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-8" data-aos="fade-up" data-aos-delay="100">
                Découvrez les parcours inspirants de nos lauréats qui transforment leur passion en carrière dans le digital
            </p>
            
            <!-- Stats rapides -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-12" data-aos="fade-up" data-aos-delay="200">
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">16+</div>
                    <div class="text-sm text-gray-400">Lauréats</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <div class="text-3xl md:text-4xl font-bold text-orange-500 mb-2">10+</div>
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

<!-- Section Studio Creative -->
<div class="bg-gradient-to-b from-[#034078] to-[#001f54] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">
            <!-- Titre -->
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Studio Creative</h2>
                <div class="w-20 h-1 bg-gradient-to-r from-orange-500 to-orange-600 mx-auto"></div>
            </div>

            <!-- Pourquoi ? -->
            <div class="mb-12 bg-white/5 backdrop-blur-lg rounded-2xl p-8 border border-white/10" data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-2xl font-bold text-orange-500 mb-6 flex items-center gap-3">
                    <i class="fas fa-question-circle"></i>
                    Pourquoi ?
                </h3>
                <div class="text-gray-300 space-y-4 leading-relaxed">
                    <p>Depuis sa création, l'Ecole Virtuelle des Créatifs (EVC) se distingue par sa capacité à anticiper les besoins et les attentes des entreprises. À l'heure où le processus de création est de plus en plus collectif, EVC vise à former les talents qui sauront le mieux travailler en équipe.</p>
                    <p>De l'émergence du design thinking, aux industries du game art et du cinéma d'animation qui mobilisent des équipes sans cesse plus grandes, <strong class="text-white">la créativité est devenue un sport collectif</strong>.</p>
                    <p>Les différentes classes de l'Ecole Virtuelle des Créatifs (EVC) sont abordées comme des espaces de rencontre et d'expression dans lesquels dialoguent chaque personnalité. Ici, la diversité des savoirs et des cultures est célébrée et traduite en une force inestimable.</p>
                    <p class="text-orange-400 font-semibold">À EVC, nous restons plus que jamais persuadés que rien de grand ne peut se construire seul.</p>
                    <p>C'est dans cette optique que, nous avons mis en place <strong class="text-white">UN STUDIO CREATIVE</strong> constitué de maximum 4 étudiants par groupe. Les groupes sont formés par l'Ecole Virtuelle des Créatifs en fonction de plusieurs critères : niveau des étudiants, la disponibilité, la date d'inscription, etc.</p>
                </div>
            </div>

            <!-- Objectifs -->
            <div class="bg-white/5 backdrop-blur-lg rounded-2xl p-8 border border-white/10" data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-2xl font-bold text-orange-500 mb-6 flex items-center gap-3">
                    <i class="fas fa-bullseye"></i>
                    Objectifs
                </h3>
                <div class="text-gray-300 space-y-4 leading-relaxed mb-6">
                    <p>L'Ecole Virtuelle des Créatifs (EVC) a décidé de mettre en place cet <strong class="text-white">STUDIO CREATIVE</strong> pour plusieurs raisons :</p>
                </div>
                <div class="space-y-6">
                    <div class="flex gap-4" data-aos="fade-right" data-aos-delay="250">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                                <span class="text-white font-bold text-lg">1</span>
                            </div>
                        </div>
                        <div class="text-gray-300">
                            <p>En tant qu'infographiste, vous êtes tentés durant votre carrière à <strong class="text-white">collaborer avec plusieurs corps de métiers</strong>. Alors savoir collaborer avec les autres sera un atout fondamental dans la réussite de votre carrière ou de votre projet de création.</p>
                        </div>
                    </div>
                    <div class="flex gap-4" data-aos="fade-right" data-aos-delay="300">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                                <span class="text-white font-bold text-lg">2</span>
                            </div>
                        </div>
                        <div class="text-gray-300">
                            <p>En tant qu'infographiste, savoir <strong class="text-white">vendre ses créations</strong> en expliquant les raisons qui vous ont poussé à faire tels choix de couleurs, de typographie, d'images, de symboles etc est un avantage inestimable. Car cela vous permettra d'affirmer vos choix créatifs.</p>
                        </div>
                    </div>
                    <div class="flex gap-4" data-aos="fade-right" data-aos-delay="350">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                                <span class="text-white font-bold text-lg">3</span>
                            </div>
                        </div>
                        <div class="text-gray-300">
                            <p>En tant qu'infographiste, vous devez être <strong class="text-white">flexible</strong> afin d'être ouvert aux nouvelles idées et acceptés des critiques constructives.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-8 p-4 bg-orange-500/10 border border-orange-500/30 rounded-xl" data-aos="fade-up" data-aos-delay="400">
                    <p class="text-orange-400 font-semibold text-center">
                        <i class="fas fa-star mr-2"></i>
                        LE STUDIO CREATIVE est un lieu propice pour permettre à chaque étudiant d'appliquer les 3 points énumérés plus haut.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Section Membres du Jury -->
<div class="bg-gradient-to-b from-[#001f54] to-[#001233] py-16">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6">
                <i class="fas fa-gavel text-orange-500"></i>
                <span class="text-orange-400 font-semibold text-sm">Jury d'Évaluation</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Les Experts Qui Valident Nos Lauréats
            </h2>
            <p class="text-lg text-gray-400 max-w-3xl mx-auto">
                Un jury composé de professionnels reconnus du digital qui évaluent et certifient l'excellence de nos étudiants
            </p>
        </div>

        <!-- Grille des membres du jury -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @php
                $juryMembers = [
                    [
                        'initiales' => 'BB',
                        'color' => 'from-orange-500 to-orange-600',
                        'name' => 'Bilé Bossombra',
                        'title' => 'Fondateur & Formateur Principal',
                        'specialite' => 'Design Graphique & Pédagogie',
                        'experience' => '10+ ans d\'expérience',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮'
                    ],
                    [
                        'initiales' => 'JD',
                        'color' => 'from-blue-500 to-blue-600',
                        'name' => 'Jean Dupont',
                        'title' => 'Expert en Community Management',
                        'specialite' => 'Stratégie Digitale',
                        'experience' => '8+ ans d\'expérience',
                        'country' => 'France',
                        'flag' => '🇫🇷'
                    ],
                    [
                        'initiales' => 'MK',
                        'color' => 'from-orange-400 to-orange-500',
                        'name' => 'Marie Kouassi',
                        'title' => 'Directrice Créative',
                        'specialite' => 'Branding & Identité Visuelle',
                        'experience' => '12+ ans d\'expérience',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮'
                    ],
                    [
                        'initiales' => 'AT',
                        'color' => 'from-blue-400 to-blue-500',
                        'name' => 'Amadou Traoré',
                        'title' => 'Expert en Intelligence Artificielle',
                        'specialite' => 'IA & Automatisation',
                        'experience' => '7+ ans d\'expérience',
                        'country' => 'Mali',
                        'flag' => '🇲🇱'
                    ],
                    [
                        'initiales' => 'SK',
                        'color' => 'from-orange-600 to-orange-700',
                        'name' => 'Sarah Koné',
                        'title' => 'Spécialiste Marketing Digital',
                        'specialite' => 'Growth & Performance',
                        'experience' => '9+ ans d\'expérience',
                        'country' => 'Sénégal',
                        'flag' => '🇸🇳'
                    ],
                    [
                        'initiales' => 'DN',
                        'color' => 'from-blue-600 to-blue-700',
                        'name' => 'David N\'Dri',
                        'title' => 'Développeur & Formateur',
                        'specialite' => 'Gestion Informatique',
                        'experience' => '11+ ans d\'expérience',
                        'country' => 'Côte d\'Ivoire',
                        'flag' => '🇨🇮'
                    ],
                ];
            @endphp

            @foreach($juryMembers as $index => $member)
            <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/10 h-full flex flex-col transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                    <!-- Badge Expert -->
                    <div class="absolute -top-3 -right-3 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full px-4 py-1 shadow-lg">
                        <span class="text-white text-xs font-bold">JURY</span>
                    </div>
                    
                    <!-- Avatar avec initiales -->
                    <div class="relative mx-auto mb-6">
                        <div class="w-24 h-24 rounded-full bg-gradient-to-br {{ $member['color'] }} flex items-center justify-center shadow-xl ring-4 ring-white/10 group-hover:ring-orange-500/30 transition-all duration-300">
                            <span class="text-white text-2xl font-bold">{{ $member['initiales'] }}</span>
                        </div>
                        <!-- Badge vérifié -->
                        <div class="absolute bottom-0 right-0 w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full border-2 border-white flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">{{ $member['name'] }}</h3>
                        <p class="text-sm text-orange-400 font-semibold mb-3">{{ $member['title'] }}</p>
                        
                        <!-- Pays -->
                        <div class="flex items-center justify-center gap-2 mb-3">
                            <span class="text-2xl">{{ $member['flag'] }}</span>
                            <span class="text-sm text-gray-300">{{ $member['country'] }}</span>
                        </div>
                        
                        <!-- Spécialité -->
                        <div class="mb-3">
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/5 rounded-full border border-white/10">
                                <i class="fas fa-star text-orange-500 text-xs"></i>
                                <span class="text-xs text-gray-300">{{ $member['specialite'] }}</span>
                            </div>
                        </div>
                        
                        <!-- Expérience -->
                        <div class="flex items-center justify-center gap-2 text-gray-400 text-sm">
                            <i class="fas fa-briefcase text-orange-500"></i>
                            <span>{{ $member['experience'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Info supplémentaire -->
        <div class="max-w-4xl mx-auto" data-aos="fade-up">
            <div class="bg-gradient-to-r from-orange-500/10 to-blue-500/10 backdrop-blur-lg rounded-2xl p-8 border border-orange-500/30">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                        <i class="fas fa-info text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-white mb-2">Processus d'Évaluation Rigoureux</h4>
                        <p class="text-gray-300 leading-relaxed">
                            Chaque lauréat est évalué par notre jury d'experts selon des critères stricts : qualité technique, créativité, respect des délais, et professionnalisme. Seuls les étudiants ayant démontré une excellence constante obtiennent leur certification.
                        </p>
                    </div>
                </div>
            </div>
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
                    'annee' => '2024',
                    'badge' => 'Promotion Actuelle',
                    'color' => 'from-orange-500 to-red-500',
                    'laureats' => [
                        ['initiales' => 'KS', 'color' => 'from-indigo-500 to-indigo-600', 'name' => 'Kamanri Serge', 'title' => 'Motion Designer @ Video Pro', 'country' => 'Tchad', 'flag' => '🇹🇩'],
                        ['initiales' => 'FD', 'color' => 'from-green-500 to-green-600', 'name' => 'Fatoumata Diarra', 'title' => 'Social Media Manager @ Digital Agency', 'country' => 'Mali', 'flag' => '🇲🇱'],
                        ['initiales' => 'CN', 'color' => 'from-red-500 to-red-600', 'name' => 'Claudine Ngoa', 'title' => 'Community Manager @ Brand Studio', 'country' => 'Cameroun', 'flag' => '🇨🇲'],
                        ['initiales' => 'KN', 'color' => 'from-orange-500 to-orange-600', 'name' => 'Kevin N\'Guessan', 'title' => 'Vidéaste & Monteur @ Freelance', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                    ]
                ],
                [
                    'numero' => 3,
                    'annee' => '2023',
                    'badge' => 'En Activité',
                    'color' => 'from-blue-500 to-cyan-500',
                    'laureats' => [
                        ['initiales' => 'TN', 'color' => 'from-sky-500 to-sky-600', 'name' => 'Tog-Yenouba Ngarleita', 'title' => 'Web Designer @ Digital Creators', 'country' => 'Tchad', 'flag' => '🇹🇩'],
                        ['initiales' => 'DA', 'color' => 'from-emerald-500 to-emerald-600', 'name' => 'Dely Ahileu', 'title' => 'Graphic Designer @ Visual Arts', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['initiales' => 'GE', 'color' => 'from-fuchsia-500 to-fuchsia-600', 'name' => 'Gossé Eric', 'title' => 'Digital Artist @ Creative Space', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['initiales' => 'YM', 'color' => 'from-cyan-500 to-cyan-600', 'name' => 'Yao Marcel', 'title' => 'Community Manager Freelance', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                    ]
                ],
                [
                    'numero' => 2,
                    'annee' => '2022',
                    'badge' => 'Confirmés',
                    'color' => 'from-purple-500 to-pink-500',
                    'laureats' => [
                        ['initiales' => 'YS', 'color' => 'from-rose-500 to-rose-600', 'name' => 'Yocbé Stella', 'title' => 'Content Creator @ Media Studio', 'country' => 'Belgique', 'flag' => '🇧🇪'],
                        ['initiales' => 'AG', 'color' => 'from-amber-500 to-amber-600', 'name' => 'Adama Guèye', 'title' => 'Community Manager @ Tech Hub', 'country' => 'Sénégal', 'flag' => '🇸🇳'],
                        ['initiales' => 'CB', 'color' => 'from-lime-500 to-lime-600', 'name' => 'Coulibaly Bakary', 'title' => 'Marketing Digital @ Start-up CI', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                        ['initiales' => 'SH', 'color' => 'from-violet-500 to-violet-600', 'name' => 'Soumahoro Hadja', 'title' => 'Social Media Strategist @ Agency Plus', 'country' => 'Côte d\'Ivoire', 'flag' => '🇨🇮'],
                    ]
                ],
                [
                    'numero' => 1,
                    'annee' => '2021',
                    'badge' => 'Pionniers',
                    'color' => 'from-green-500 to-teal-500',
                    'laureats' => [
                        ['initiales' => 'LM', 'color' => 'from-blue-500 to-blue-600', 'name' => 'Lombi Moïse', 'title' => 'Graphiste Senior @ Creative Corp', 'country' => 'RDC', 'flag' => '🇨🇩'],
                        ['initiales' => 'EM', 'color' => 'from-purple-500 to-purple-600', 'name' => 'Eddy Marc', 'title' => 'Social Media Manager @ Digital Wave', 'country' => 'France', 'flag' => '🇫🇷'],
                        ['initiales' => 'AE', 'color' => 'from-pink-500 to-pink-600', 'name' => 'Adingra Eve', 'title' => 'UX/UI Designer @ Tech Solutions', 'country' => 'Ghana', 'flag' => '🇬🇭'],
                        ['initiales' => 'AA', 'color' => 'from-teal-500 to-teal-600', 'name' => 'Alimasi Abdoullah', 'title' => 'Brand Designer @ Innovation Lab', 'country' => 'Guinée', 'flag' => '🇬🇳'],
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
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($edition['laureats'] as $index => $laureat)
                <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <div class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/10 h-full flex flex-col text-center transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                        <!-- Badge succès -->
                        <div class="absolute -top-3 -right-3 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full p-2 shadow-lg">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        
                        <!-- Avatar avec initiales -->
                        <div class="relative mx-auto mb-6">
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br {{ $laureat['color'] }} flex items-center justify-center shadow-xl ring-4 ring-white/10 group-hover:ring-orange-500/30 transition-all duration-300">
                                <span class="text-white text-3xl font-bold">{{ $laureat['initiales'] }}</span>
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
                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10">
                                <i class="fas fa-briefcase text-orange-500 text-xs"></i>
                                <p class="text-sm text-gray-300">{{ $laureat['title'] }}</p>
                            </div>
                        </div>
                        
                        <!-- Lien LinkedIn (optionnel) -->
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <button class="text-gray-400 hover:text-orange-500 transition-colors text-sm">
                                <i class="fab fa-linkedin mr-2"></i>Voir le profil
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
        
    </div>
</div>

<!-- Section Impact & Témoignage -->
<div class="bg-gradient-to-b from-[#001233] to-[#0a1128] py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <!-- Statistiques d'impact -->
            <div data-aos="fade-right">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                    Des Résultats Qui Parlent d'Eux-Mêmes
                </h2>
                <p class="text-lg text-gray-400 mb-8">
                    Nos lauréats ne sont pas de simples diplômés, ce sont des professionnels recherchés qui font la différence dans l'industrie du digital.
                </p>
                
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                            <i class="fas fa-rocket text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">95% d'Employabilité</h3>
                            <p class="text-gray-400">Nos lauréats trouvent un emploi ou lancent leur activité freelance dans les 3 mois suivant leur formation</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                            <i class="fas fa-globe-africa text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Rayonnement International</h3>
                            <p class="text-gray-400">Présents dans plus de 10 pays africains et européens, nos lauréats font rayonner l'excellence africaine</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center">
                            <i class="fas fa-users text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white mb-2">Réseau Professionnel Actif</h3>
                            <p class="text-gray-400">Une communauté soudée qui s'entraide et collabore sur des projets d'envergure</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Témoignage mis en avant -->
            <div data-aos="fade-left">
                <div class="bg-gradient-to-br from-orange-500/10 to-orange-600/10 backdrop-blur-lg rounded-3xl p-8 border border-orange-500/30">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center">
                            <span class="text-white text-xl font-bold">KN</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-white">Kevin N'Guessan</h4>
                            <p class="text-sm text-gray-400">Vidéaste & Monteur</p>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <i class="fas fa-quote-left text-orange-500 text-3xl mb-4"></i>
                        <p class="text-gray-300 text-lg leading-relaxed italic">
                            "Avant EVC, j'avais acheté des tutos que j'ai à peine regardés. Avec EVC, tout a été différent : la pédagogie, la structuration des cours, tout m'a convaincu. Quand je suis les formations, j'ai vraiment l'impression d'être dans la même salle que le formateur. Et surtout, la disponibilité 24h/24 du coach pour nous encadrer !"
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-2 text-orange-500">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span class="text-white ml-2 font-semibold">5/5</span>
                    </div>
                </div>
                
                <!-- Call to action secondaire -->
                <div class="mt-8 text-center">
                    <a href="{{ route('formations') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-orange-500/50">
                        <span>Découvrir Nos Formations</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
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
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <i class="fas fa-laptop-code text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Formation 100% Pratique</h3>
                    <p class="text-sm text-gray-400">Projets réels et portfolio professionnel</p>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
                    <i class="fas fa-certificate text-orange-500 text-3xl mb-4"></i>
                    <h3 class="text-lg font-bold text-white mb-2">Certification Reconnue</h3>
                    <p class="text-sm text-gray-400">Diplôme valorisé par les entreprises</p>
                </div>
                <div class="bg-white/5 backdrop-blur-sm rounded-2xl p-6 border border-white/10">
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
            <div class="mt-8 inline-flex items-center gap-2 px-4 py-2 bg-red-500/10 border border-red-500/30 rounded-full">
                <i class="fas fa-clock text-red-500"></i>
                <span class="text-red-400 font-semibold text-sm">Places limitées - Inscrivez-vous maintenant !</span>
            </div>
        </div>
    </div>
</div>

<!-- CTA Section -->
@include('homepage._cta-final')
@endsection
