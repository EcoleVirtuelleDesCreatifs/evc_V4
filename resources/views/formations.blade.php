@extends('layouts.app')

@section('title', 'Formations Certifiantes École Numérique EVC Abidjan | Design Graphique, Motion Design, Community Management')
@section('description', 'Formations certifiées par l\'État à l\'EVC école numérique d\'Abidjan : Design Graphique Adobe Photoshop (4 mois), Motion Design, Community Management (3 mois), Bureautique avancée (2 mois). Paiement échelonné.')
@section('keywords', 'formation design graphique Abidjan, centre de formation Adobe Photoshop Abidjan, formation motion design Abidjan, formation community management Abidjan, formation bureautique avancé Abidjan, école numérique Abidjan, école d\'infographie Abidjan, centre de formation professionnelle Abidjan, formation certifiante reconnue par l\'État, formation en ligne reconnue par l\'État, formation en ligne reconnue à l\'international, école de formatique, école de communication visuelle Abidjan, formation certifiante Côte d\'Ivoire, EVC, Adobe Illustrator, InDesign')

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
        50% { transform: translateY(-15px); }
    }

    @keyframes shimmer {
        0% { background-position: -1000px 0; }
        100% { background-position: 1000px 0; }
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

    .formation-card-modern {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .formation-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 152, 0, 0.1), transparent);
        transition: left 0.5s;
    }

    .formation-card-modern:hover::before {
        left: 100%;
    }

    .formation-card-modern:hover {
        transform: translateY(-10px) scale(1.02);
        border-color: rgba(255, 152, 0, 0.5);
        box-shadow: 0 20px 60px rgba(255, 152, 0, 0.3);
    }

    .icon-circle {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #ff9800, #ff6b00);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(255, 152, 0, 0.4);
        transition: all 0.3s ease;
    }

    .formation-card-modern:hover .icon-circle {
        transform: scale(1.1) rotate(10deg);
        box-shadow: 0 15px 40px rgba(255, 152, 0, 0.6);
    }

    .price-badge {
        background: linear-gradient(135deg, #ff9800, #ff6b00);
        box-shadow: 0 4px 15px rgba(255, 152, 0, 0.4);
    }

    .stat-number {
        background: linear-gradient(135deg, #ff9800, #ff6b00);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* Responsive Mobile */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem !important;
        }

        .formation-grid {
            grid-template-columns: 1fr !important;
        }

        .icon-circle {
            width: 60px;
            height: 60px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 pt-[500px] pb-20 overflow-hidden">
    <!-- Effet de fond animé -->
    <div class="absolute inset-0 opacity-20">
        <div class="absolute top-20 left-10 w-96 h-96 bg-orange-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-6 lg:px-8">
        <div class="text-center animate-on-scroll">
            <div class="inline-flex items-center gap-2 bg-orange-500/10 backdrop-blur-sm border border-orange-500/30 rounded-full px-6 py-3 mb-6">
                <i class="fas fa-graduation-cap text-orange-500"></i>
                <span class="text-orange-500 font-bold text-sm tracking-wider">FORMATIONS CERTIFIANTES</span>
            </div>
            <h1 class="hero-title text-6xl lg:text-7xl font-black text-white mb-6 tracking-tight">
                Transformez Votre <br/><span class="bg-gradient-to-r from-orange-400 to-orange-600 bg-clip-text text-transparent">Passion</span> en Carrière
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed mb-10">
                Des formations ultra-pratiques, certifiées par l'État, conçues pour vous rendre opérationnel dès le premier jour. Rejoignez plus de 1500 étudiants qui ont déjà fait le choix de l'excellence.
            </p>
        </div>


    </div>
</div>

<!-- Formations Grid -->
<div class="bg-gradient-to-b from-slate-900 to-slate-800 py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="formation-grid grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Design Graphique -->
            <div class="formation-card-modern rounded-3xl p-8 animate-on-scroll">
                <div class="icon-circle mb-6">
                    <i class="fas fa-palette text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 text-center">Infographie & Design Graphique</h3>
                <p class="text-gray-300 text-center mb-6 leading-relaxed">
                    Maîtrisez Adobe Photoshop, Illustrator et InDesign. Créez des identités visuelles, des logotypes, charte graphique, Étiquettes, Affiches professionnelles et devenez un infographiste recherché.
                </p>

                <div class="mb-6 p-3 bg-slate-800/50 rounded-xl border-l-4 border-orange-500">
                    <div class="text-sm text-gray-400 mb-2">Format & Lieu :</div>
                    <div class="text-sm text-gray-300">
                        <div class="mb-1"><i class="fas fa-clock text-orange-500 mr-2"></i>Format hybride : En ligne + Présentiel (01 séance/mois)</div>
                        <div><i class="fas fa-map-marker-alt text-orange-500 mr-2"></i>Immeuble Bloom Square Akwaba (Palmeraie - Carrefour Guiraud)</div>
                    </div>
                </div>

                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Adobe Photoshop, Illustrator, InDesign</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Accompagnement & assistance</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Espace Étudiant My EVC à vie</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Kit étudiant : T-shirt EVC, BlocNote, Stylo, Souris, Tapis</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Certificat reconnu</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Lettre de recommandation</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Coaching personnalisé</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Ressources pédagogiques</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Accès aux Classes virtuelles de travail</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-orange-500"></i>
                        <span>Module Pitch pour Convaincre (PPC)</span>
                    </div>
                </div>

                <div class="mb-6 p-4 bg-slate-800/50 rounded-xl border-l-4 border-orange-500">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="text-sm text-gray-400">Tarif Total</div>
                            <div class="text-2xl font-black text-white">185.000 <span class="text-sm">FCFA</span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-400">Durée</div>
                            <div class="text-lg font-bold text-orange-500">4 Mois</div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-700">
                        <div class="text-xs text-gray-400 mb-1">Modalités de paiement :</div>
                        <div class="text-sm text-gray-300">95.000 FCFA à l'inscription, 90.000 FCFA en tranche</div>
                        <div class="text-xs text-gray-500 mt-1">À solder après 2 mois (le 20 du mois en cours)</div>
                    </div>
                </div>

                <a href="{{ route('preinscription.start') }}" class="block w-full text-center bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold py-4 rounded-full hover:from-orange-600 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-rocket mr-2"></i>Choisir cette formation
                </a>

                <a href="{{ route('plaquettes.formations') }}" class="mt-3 block w-full text-center bg-white/10 text-white font-bold py-4 rounded-full hover:bg-white/15 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-file-pdf mr-2"></i>Demander la plaquette
                </a>
            </div>

            <!-- Community Management -->
            <div class="formation-card-modern rounded-3xl p-8 animate-on-scroll" style="animation-delay: 0.1s;">
                <div class="icon-circle mb-6" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <i class="fas fa-users text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 text-center">Community & Social Media Management</h3>
                <p class="text-gray-300 text-center mb-6 leading-relaxed">
                    Devenez expert des réseaux sociaux. Créez du contenu viral, gérez des communautés et développez la présence digitale des marques.
                </p>

                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Installation du logiciel Adobe Photoshop</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Projets réels d'entreprises</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Accompagnement & assistance personnalisée</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Espace Étudiant My EVC</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Kit étudiant : T-shirt EVC, BlocNote, Stylo, Souris, Tapis</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Certificat reconnu</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Lettre de recommandation</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Coaching personnalisé</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Ressources pédagogiques</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Accès aux classes virtuelles de travail</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Module Pitch pour Convaincre (PPC)</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-blue-500"></i>
                        <span>Opportunités de stage, missions et insertion professionnelle</span>
                    </div>
                </div>

                <div class="mb-6 p-3 bg-slate-800/50 rounded-xl border-l-4 border-blue-500">
                    <div class="text-sm text-gray-400 mb-2">Format & Lieu :</div>
                    <div class="text-sm text-gray-300 space-y-2">
                        <div><i class="fas fa-clock text-blue-500 mr-2"></i><strong>Durée :</strong> 08 séances</div>
                        <div class="pl-5">
                            <div class="mb-1"><i class="fas fa-building text-blue-500 mr-2"></i>04 séances en présentiel : Samedi (09h-12h)</div>
                            <div><i class="fas fa-laptop text-blue-500 mr-2"></i>04 séances en ligne : Samedi (14h-15h30) & Dimanche (14h-15h30)</div>
                        </div>
                        <div><i class="fas fa-map-marker-alt text-blue-500 mr-2"></i><strong>Lieu :</strong> Immeuble Bloom Square Akwaba (Palmeraie - Carrefour Guiraud)</div>
                    </div>
                </div>

                <div class="mb-6 p-4 bg-slate-800/50 rounded-xl border-l-4 border-blue-500">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="text-sm text-gray-400">Tarif Total</div>
                            <div class="text-2xl font-black text-white">165.000 <span class="text-sm">FCFA</span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-400">Durée</div>
                            <div class="text-lg font-bold text-blue-500">2 Mois</div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-700">
                        <div class="text-xs text-gray-400 mb-1">Modalités de paiement :</div>
                        <div class="text-sm text-gray-300">85.000 FCFA à l'inscription, 80.000 FCFA en tranche</div>
                        <div class="text-xs text-gray-500 mt-1">À solder après 1 mois (le 15 du mois en cours)</div>
                    </div>
                </div>

                <a href="{{ route('preinscription.start') }}" class="block w-full text-center bg-gradient-to-r from-blue-500 to-blue-600 text-white font-bold py-4 rounded-full hover:from-blue-600 hover:to-blue-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-rocket mr-2"></i>Choisir cette formation
                </a>

                <a href="{{ route('plaquettes.formations') }}" class="mt-3 block w-full text-center bg-white/10 text-white font-bold py-4 rounded-full hover:bg-white/15 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-file-pdf mr-2"></i>Demander la plaquette
                </a>
            </div>

            <!-- Bureautique et informatique -->
            <div class="formation-card-modern rounded-3xl p-8 animate-on-scroll" style="animation-delay: 0.2s;">
                <div class="icon-circle mb-6" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-cogs text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-black text-white mb-3 text-center">Bureautique et informatique</h3>
                <p class="text-gray-300 text-center mb-6 leading-relaxed">
                    Maîtrisez l'outil informatique et bureautique en environnement professionnel. Devenez autonome avec les outils digitaux essentiels et boostez votre employabilité en entreprise.
                </p>

                <div class="space-y-2 mb-6">
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Word, Excel, PowerPoint</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Canva & Services Google</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Accompagnement et assistance</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Possibilité d'obtention de stage ou d'emploi</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>En ligne et en présentiel</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Formation orientée pratique et résultat</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Certificat Reconnu avec lettre de recommandation</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>T-shirt EVC offert</span>
                    </div>
                    <div class="flex items-center gap-3 text-sm text-gray-300">
                        <i class="fas fa-check-circle text-green-500"></i>
                        <span>Stratégie business, des programmes orientés entreprises</span>
                    </div>
                </div>

                <div class="mb-6 p-4 bg-slate-800/50 rounded-xl border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <div class="text-sm text-gray-400">Tarif Total</div>
                            <div class="text-2xl font-black text-white">265.000 <span class="text-sm">FCFA</span></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-400">Durée</div>
                            <div class="text-lg font-bold text-green-500">2 Mois</div>
                        </div>
                    </div>
                    <div class="pt-3 border-t border-slate-700">
                        <div class="text-xs text-gray-400 mb-1">Modalités de paiement :</div>
                        <div class="text-sm text-gray-300">150.000 FCFA comme premier montant</div>
                    </div>
                </div>

                <a href="{{ route('preinscription.start') }}" class="block w-full text-center bg-gradient-to-r from-green-500 to-green-600 text-white font-bold py-4 rounded-full hover:from-green-600 hover:to-green-700 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-rocket mr-2"></i>Choisir cette formation
                </a>

                <a href="{{ route('plaquettes.formations') }}" class="mt-3 block w-full text-center bg-white/10 text-white font-bold py-4 rounded-full hover:bg-white/15 transition-all transform hover:scale-105 shadow-lg hover:shadow-xl">
                    <i class="fas fa-file-pdf mr-2"></i>Demander la plaquette
                </a>
            </div>

        </div>
    </div>
</div>

<!-- CTA Final -->
<div class="bg-slate-900 py-24">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="relative isolate overflow-hidden bg-gradient-to-br from-orange-600 to-orange-800 px-6 py-24 text-center shadow-2xl rounded-3xl sm:px-16 animate-on-scroll">
            <h2 class="mx-auto max-w-2xl text-4xl font-black tracking-tight text-white sm:text-5xl">
                Prêt à Démarrer Votre Formation ?
            </h2>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-orange-100">
                Inscrivez-vous maintenant et bénéficiez d'un accompagnement personnalisé tout au long de votre parcours.
            </p>
            <div class="mt-10">
                <a href="{{ route('preinscription.start') }}" class="inline-block rounded-full bg-white px-10 py-4 text-lg font-bold text-orange-600 shadow-sm hover:bg-orange-50 transform hover:scale-105 transition-all">
                    <i class="fas fa-rocket mr-2"></i>Je m'inscris maintenant
                </a>
            </div>
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
</script>
@endpush
@endsection
