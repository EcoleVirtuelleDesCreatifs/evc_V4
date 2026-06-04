@extends('layouts.app')

@section('title', 'À Propos EVC | École Numérique N°1 Abidjan | Bilé Bossombra | École Virtuelle des Créatifs')
@section('description', 'Découvrez l\'EVC, école numérique N°1 à Abidjan, fondée par Bilé Bossombra. 1500+ étudiants formés en Design Graphique, Motion Design, Community Management en Côte d\'Ivoire. École virtuelle des créatifs certifiée.')
@section('keywords', 'école numérique Abidjan, ecole numérique, EVC, école virtuelle des créatifs, Bilé Bossombra, école de formation Abidjan, école d\'infographie Abidjan, centre de formation professionnelle Abidjan, formation certifiante reconnue par l\'État, formation en ligne reconnue à l\'international, formation design graphique Abidjan, école de communication visuelle Abidjan, école community management Abidjan, ECV, ECAV, ev ecole')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes pulse-border {
        0%, 100% { box-shadow: 0 0 0 0 rgba(255, 152, 0, 0.7); }
        50% { box-shadow: 0 0 0 20px rgba(255, 152, 0, 0); }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease-out;
    }

    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }

    .hero-gradient {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
        position: relative;
        overflow: hidden;
    }

    .hero-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 20% 50%, rgba(255, 152, 0, 0.1) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        animation: float 20s ease-in-out infinite;
    }

    .stat-card {
        background: linear-gradient(135deg, rgba(255, 152, 0, 0.1) 0%, rgba(251, 140, 0, 0.05) 100%);
        border: 2px solid rgba(255, 152, 0, 0.3);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .stat-card:hover {
        transform: translateY(-10px) scale(1.05);
        box-shadow: 0 20px 40px rgba(255, 152, 0, 0.3);
        border-color: rgba(255, 152, 0, 0.6);
    }

    .value-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.4s ease;
    }

    .value-card:hover {
        background: rgba(255, 152, 0, 0.1);
        border-color: rgba(255, 152, 0, 0.5);
        transform: translateY(-10px);
    }

    .value-card:hover .value-icon {
        transform: scale(1.2) rotate(10deg);
        background: linear-gradient(135deg, #ff9800, #ff6b00);
    }

    .value-icon {
        transition: all 0.4s ease;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 3px;
        height: 100%;
        background: linear-gradient(180deg, #ff9800, #3b82f6);
    }

    .cta-button {
        background: linear-gradient(135deg, #ff9800, #ff6b00);
        box-shadow: 0 10px 30px rgba(255, 152, 0, 0.4);
        transition: all 0.3s ease;
        animation: pulse-border 2s infinite;
    }

    .cta-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(255, 152, 0, 0.6);
    }

    /* Responsive Mobile pour boutons CTA */
    @media (max-width: 768px) {
        .cta-buttons-mobile {
            flex-direction: column !important;
            width: 100%;
        }

        .cta-buttons-mobile a {
            width: 100% !important;
            text-align: center;
        }

        .hero-cta-mobile {
            flex-direction: column !important;
            gap: 1rem !important;
        }

        .hero-cta-mobile a {
            width: 100% !important;
            justify-content: center !important;
        }
    }

    /* Responsive Mobile pour section formateur */
    @media (max-width: 768px) {
        .formateur-section {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
        }

        .formateur-header h2 {
            font-size: 2.5rem !important;
            line-height: 1.2 !important;
        }

        .formateur-header p {
            font-size: 0.95rem !important;
            padding: 0 1rem;
        }

        .formateur-card {
            padding: 1.5rem !important;
            margin-top: 1rem;
        }

        .formateur-card h3 {
            font-size: 1.5rem !important;
        }

        .formateur-card p {
            font-size: 0.9rem !important;
        }

        .formateur-icon {
            width: 3rem !important;
            height: 3rem !important;
        }

        .formateur-icon i {
            font-size: 1.25rem !important;
        }

        .stats-grid {
            gap: 0.75rem !important;
        }

        .stat-item {
            font-size: 2rem !important;
        }

        .stat-label {
            font-size: 0.65rem !important;
        }

        .country-badges {
            gap: 0.5rem !important;
        }

        .country-badge {
            padding: 0.5rem 0.75rem !important;
            font-size: 0.7rem !important;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero-gradient pt-[200px] pb-20 relative">
    <div class="mx-auto max-w-7xl px-6 lg:px-8 relative z-10">
        <div class="mx-auto max-w-4xl text-center" style="animation: fadeInUp 1s ease-out">
            <div class="inline-block mb-6">
                <span class="inline-flex items-center gap-2 rounded-full bg-orange-500/10 px-6 py-2 text-sm font-semibold text-orange-400 ring-1 ring-inset ring-orange-500/20">
                    <i class="fas fa-graduation-cap"></i>
                    Première École Digitale en Afrique francophone
                </span>
            </div>
            <h1 class="text-5xl font-black tracking-tight text-white sm:text-7xl mb-6" style="line-height: 1.1;">
                Façonner l'<span class="bg-gradient-to-r from-orange-400 to-orange-600 bg-clip-text text-transparent">Avenir Digital</span> de l'Afrique
            </h1>
            <p class="mt-6 text-xl leading-8 text-gray-300 max-w-3xl mx-auto">
                L'École Virtuelle des Créatifs (EVC) est une institution innovante dédiée à la formation professionnelle ultra-pratique. Nous transformons des passionnés en experts du digital prêts à conquérir le marché.
            </p>
            <div class="hero-cta-mobile mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('preinscription.start') }}" class="cta-button rounded-full px-8 py-4 text-lg font-bold text-white shadow-sm hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">
                    <i class="fas fa-rocket mr-2"></i>Démarrer ma Formation
                </a>
                <a href="{{ route('formations') }}" class="rounded-full bg-white/10 px-8 py-4 text-lg font-semibold text-white shadow-sm hover:bg-white/20 backdrop-blur-sm">
                    Nos Formations <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques Impactantes -->
<div class="bg-gradient-to-b from-slate-900 to-slate-800 py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="stat-card rounded-2xl p-8 text-center animate-on-scroll">
                <div class="text-5xl font-black text-orange-500 mb-3">
                    <i class="fas fa-users"></i>
                </div>
                <div class="text-4xl font-black text-white mb-2">1542+</div>
                <div class="text-gray-400 font-semibold">Étudiants Formés</div>
            </div>
            <div class="stat-card rounded-2xl p-8 text-center animate-on-scroll" style="animation-delay: 0.1s">
                <div class="text-5xl font-black text-orange-500 mb-3">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="text-4xl font-black text-white mb-2">473</div>
                <div class="text-gray-400 font-semibold">Étudiants Certifiés et Confirmés</div>
            </div>
            <div class="stat-card rounded-2xl p-8 text-center animate-on-scroll" style="animation-delay: 0.2s">
                <div class="text-5xl font-black text-blue-500 mb-3">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="text-4xl font-black text-white mb-2">54</div>
                <div class="text-gray-400 font-semibold">Étudiants Déjà Embauchés</div>
            </div>
            <div class="stat-card rounded-2xl p-8 text-center animate-on-scroll" style="animation-delay: 0.3s">
                <div class="text-5xl font-black text-orange-500 mb-3">
                    <i class="fas fa-award"></i>
                </div>
                <div class="text-4xl font-black text-white mb-2">72</div>
                <div class="text-gray-400 font-semibold">Stagiaires Intégrés en Entreprise</div>
            </div>
        </div>
    </div>
</div>

<!-- Notre Mission -->
<div class="bg-slate-800 py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-16 animate-on-scroll">
            <h2 class="text-base font-semibold leading-7 text-orange-500"><i class="fas fa-rocket mr-2"></i>NOTRE MISSION</h2>
            <p class="mt-2 text-4xl font-black tracking-tight text-white sm:text-5xl">Former les Leaders du Digital de Demain</p>
            <p class="mt-6 text-lg leading-8 text-gray-300">
                À EVC, notre mission est claire : transformer des passionnés en professionnels compétents et opérationnels. Nous vous dotons des compétences pratiques et techniques qui répondent aux exigences du marché pour relever les défis du monde numérique.
            </p>
        </div>

        <!-- Nos Valeurs -->
        <div class="mt-20">
            <h3 class="text-3xl font-black text-center text-white mb-12 animate-on-scroll">
                <i class="fas fa-gem text-orange-500 mr-3"></i>Nos Valeurs Fondamentales
            </h3>
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @php
                    $values = [
                        ['name' => 'Excellence', 'description' => 'Formations de haute qualité en adéquation avec les standards internationaux du marché.', 'icon' => 'fa-star', 'color' => 'orange'],
                        ['name' => 'Innovation', 'description' => 'Intégration des dernières technologies et tendances pour un enseignement à la pointe.', 'icon' => 'fa-lightbulb', 'color' => 'blue'],
                        ['name' => 'Pratique', 'description' => 'Formations axées sur des projets réels pour des compétences immédiatement applicables.', 'icon' => 'fa-code', 'color' => 'orange'],
                        ['name' => 'Accompagnement', 'description' => 'Suivi individualisé 24/7 pour une expérience d\'apprentissage réussie.', 'icon' => 'fa-user-friends', 'color' => 'blue']
                    ];
                @endphp
                @foreach ($values as $index => $value)
                <div class="value-card rounded-2xl p-8 text-center animate-on-scroll" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="value-icon mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-{{ $value['color'] }}-500 mx-auto">
                        <i class="fas {{ $value['icon'] }} text-white text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-white mb-3">{{ $value['name'] }}</h4>
                    <p class="text-gray-400 leading-relaxed">{{ $value['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Nos Atouts -->
<div class="bg-gradient-to-b from-slate-900 to-slate-800 py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center mb-16 animate-on-scroll">
            <h2 class="text-base font-semibold leading-7 text-orange-500"><i class="fas fa-star mr-2"></i>NOS ATOUTS</h2>
            <p class="mt-2 text-4xl font-black tracking-tight text-white sm:text-5xl">Pourquoi Choisir l'EVC ?</p>
        </div>

        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $advantages = [
                    ['name' => 'Ultra-Pratiques', 'description' => 'Formations axées sur des projets d\'entreprises réels et des cas concrets du marché.', 'icon' => 'fa-briefcase', 'gradient' => 'from-orange-500 to-orange-600'],
                    ['name' => 'Coaching 24/7', 'description' => 'Assistance et coaching hors pair avec une équipe de professionnels dédiés.', 'icon' => 'fa-headset', 'gradient' => 'from-blue-500 to-blue-600'],
                    ['name' => 'Certifiées', 'description' => 'Reconnue par l\'État, garantissant la certification officielle de nos formations.', 'icon' => 'fa-certificate', 'gradient' => 'from-orange-600 to-orange-700'],
                    ['name' => 'Flexible', 'description' => 'Formations en ligne et en présentiel selon des programmes mensuels adaptés.', 'icon' => 'fa-clock', 'gradient' => 'from-blue-600 to-blue-700']
                ];
            @endphp
            @foreach ($advantages as $index => $advantage)
            <div class="group relative animate-on-scroll" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="absolute -inset-0.5 bg-gradient-to-r {{ $advantage['gradient'] }} rounded-2xl blur opacity-30 group-hover:opacity-100 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative bg-slate-800 rounded-2xl p-8 text-center">
                    <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-xl bg-gradient-to-r {{ $advantage['gradient'] }} mx-auto transform group-hover:scale-110 transition-transform duration-300">
                        <i class="fas {{ $advantage['icon'] }} text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">{{ $advantage['name'] }}</h3>
                    <p class="text-gray-400 leading-relaxed">{{ $advantage['description'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Formateur Principal -->
<div class="formateur-section relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 py-32 overflow-hidden">
    <!-- Effet de fond animé -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-10 w-72 h-72 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-72 h-72 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <!-- Header percutant -->
        <div class="formateur-header text-center mb-20 animate-on-scroll">
            <div class="inline-flex items-center gap-2 bg-orange-500/10 backdrop-blur-sm border border-orange-500/30 rounded-full px-6 py-3 mb-6">
                <i class="fas fa-user-tie text-orange-500"></i>
                <span class="text-orange-500 font-bold text-sm tracking-wider">NOTRE FORMATEUR PRINCIPAL</span>
            </div>
            <h2 class="text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
                Bilé <span class="bg-gradient-to-r from-orange-500 to-orange-600 bg-clip-text text-transparent">Bossombra</span>
            </h2>
            <p class="text-xl text-gray-300 max-w-4xl mx-auto leading-relaxed">
                Chef de projet digital, Brand Designer, Directeur Artistique, Publicitaire, Écrivain, Rédacteur et Développeur Fullstack.
            </p>
        </div>

        <!-- Layout moderne -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center max-w-7xl mx-auto">
            <!-- Photo du formateur - Plus grande et impactante -->
            <div class="lg:col-span-5 animate-on-scroll">
                <div class="relative group">
                    <!-- Effet glow animé -->
                    <div class="absolute -inset-4 bg-gradient-to-r from-orange-500 via-orange-600 to-blue-500 rounded-3xl blur-2xl opacity-30 group-hover:opacity-50 transition duration-500 animate-pulse"></div>

                    <!-- Cadre avec bordure gradient -->
                    <div class="relative">
                        <div class="absolute -inset-1 bg-gradient-to-r from-orange-500 to-blue-500 rounded-3xl opacity-75"></div>
                        <div class="relative bg-slate-900 rounded-3xl overflow-hidden p-2">
                            <img src="{{ asset('assets/img/founder/Bile_Bossombra.jpg') }}" alt="Bilé Bossombra" class="w-full h-auto object-cover rounded-2xl transform group-hover:scale-105 transition duration-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Carte d'expérience - Plus spacieuse -->
            <div class="lg:col-span-7 animate-on-scroll">
                <div class="relative">
                    <!-- Effet de profondeur -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-orange-500/20 to-blue-500/20 rounded-3xl blur-xl"></div>

                    <div class="formateur-card relative bg-slate-800/80 backdrop-blur-xl rounded-3xl p-10 border border-slate-700/50 hover:border-orange-500/50 transition-all duration-500 shadow-2xl">
                        <div class="flex items-start gap-6">
                            <!-- Icône percutante -->
                            <div class="flex-shrink-0">
                                <div class="formateur-icon w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg shadow-orange-500/50 transform hover:rotate-6 transition-transform duration-300">
                                    <i class="fas fa-briefcase text-white text-2xl"></i>
                                </div>
                            </div>

                            <div class="flex-1">
                                <h3 class="text-3xl font-black text-white mb-4 flex items-center gap-3">
                                    Parcours & Expérience
                                    <span class="inline-block w-12 h-1 bg-gradient-to-r from-orange-500 to-transparent rounded-full"></span>
                                </h3>
                                <p class="text-gray-300 text-lg leading-relaxed">
                                    Fort de plus de <span class="text-orange-500 font-bold">10 ans d'expérience</span> dans le web et le digital, professionnel passionné et diplômé ayant mené à bien de nombreux projets dans divers secteurs à l'échelle <span class="text-blue-400 font-semibold">panafricaine et internationale</span>.
                                </p>
                                <p class="text-gray-300 text-lg leading-relaxed mt-4">
                                    Véritable <span class="text-orange-500 font-bold">expert à 360°</span> du digital, combinant compétences techniques et vision stratégique pour répondre aux défis de la transformation digitale.
                                </p>

                                <!-- Stats rapides -->
                                <div class="stats-grid grid grid-cols-3 gap-4 mt-8 pt-8 border-t border-slate-700">
                                    <div class="text-center">
                                        <div class="stat-item text-3xl font-black text-orange-500 mb-1">10+</div>
                                        <div class="stat-label text-xs text-gray-400 uppercase tracking-wider">Années</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-3xl font-black text-blue-500 mb-1">500+</div>
                                        <div class="text-xs text-gray-400 uppercase tracking-wider">Projets</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-3xl font-black text-orange-500 mb-1">5</div>
                                        <div class="text-xs text-gray-400 uppercase tracking-wider">Pays</div>
                                    </div>
                                </div>

                                <!-- Pays d'intervention -->
                                <div class="mt-6 pt-6 border-t border-slate-700/50">
                                    <p class="text-sm text-gray-400 mb-3 font-semibold">Expérience internationale :</p>
                                    <div class="country-badges flex flex-wrap gap-2">
                                        <span class="country-badge inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900/50 rounded-full text-xs text-gray-300 border border-slate-700">
                                            <span class="text-orange-500">🇨🇮</span> Côte d'Ivoire
                                        </span>
                                        <span class="country-badge inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900/50 rounded-full text-xs text-gray-300 border border-slate-700">
                                            <span class="text-blue-500">🇫🇷</span> France
                                        </span>
                                        <span class="country-badge inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900/50 rounded-full text-xs text-gray-300 border border-slate-700">
                                            <span class="text-orange-500">🇨🇳</span> Chine
                                        </span>
                                        <span class="country-badge inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900/50 rounded-full text-xs text-gray-300 border border-slate-700">
                                            <span class="text-blue-500">🇦🇪</span> Émirats Arabes
                                        </span>
                                        <span class="country-badge inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900/50 rounded-full text-xs text-gray-300 border border-slate-700">
                                            <span class="text-orange-500">🇺🇸</span> USA
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CTA Final -->
<div class="bg-slate-900 py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="relative isolate overflow-hidden bg-gradient-to-br from-orange-600 to-orange-800 px-6 py-24 text-center shadow-2xl rounded-3xl sm:px-16 animate-on-scroll">
            <h2 class="mx-auto max-w-2xl text-4xl font-black tracking-tight text-white sm:text-5xl">
                Prêt à Transformer Votre Avenir ?
            </h2>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-orange-100">
                Rejoignez plus de 1000 étudiants qui ont déjà fait le choix de l'excellence. Commencez votre parcours vers le succès dès aujourd'hui.
            </p>
            <div class="cta-buttons-mobile mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('preinscription.start') }}" class="rounded-full bg-white px-8 py-4 text-lg font-bold text-orange-600 shadow-sm hover:bg-orange-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transform hover:scale-105 transition-all">
                    <i class="fas fa-rocket mr-2"></i>Je m'inscris maintenant
                </a>
                <a href="{{ route('formations') }}" class="rounded-full bg-orange-700/50 px-8 py-4 text-lg font-semibold text-white shadow-sm hover:bg-orange-700 backdrop-blur-sm">
                    Découvrir les formations <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            <svg viewBox="0 0 1024 1024" class="absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-x-1/2 [mask-image:radial-gradient(closest-side,white,transparent)]" aria-hidden="true">
                <circle cx="512" cy="512" r="512" fill="url(#gradient)" fill-opacity="0.7" />
                <defs>
                    <radialGradient id="gradient">
                        <stop stop-color="#ff9800" />
                        <stop offset="1" stop-color="#ff6b00" />
                    </radialGradient>
                </defs>
            </svg>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Animation on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });

    // Counter animation for stats
    function animateCounter(element, target) {
        let current = 0;
        const increment = target / 100;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                element.textContent = target + (element.textContent.includes('%') ? '%' : '+');
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(current) + (element.textContent.includes('%') ? '%' : '+');
            }
        }, 20);
    }

    // Trigger counter animation when stats are visible
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumber = entry.target.querySelector('.text-4xl');
                if (statNumber && !statNumber.classList.contains('animated')) {
                    statNumber.classList.add('animated');
                    const text = statNumber.textContent;
                    const number = parseInt(text.replace(/\D/g, ''));
                    statNumber.textContent = '0';
                    animateCounter(statNumber, number);
                }
            }
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('.stat-card').forEach(card => {
        statsObserver.observe(card);
    });
</script>
@endpush
@endsection
