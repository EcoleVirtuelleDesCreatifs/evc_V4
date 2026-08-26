@extends('layouts.app')

@section('title', 'Studio Creative - École Virtuelle des Créatifs')
@section('description',
    'Découvrez le Studio Creative de l\'École Virtuelle des Créatifs, un espace dédié à la création,
    l\'innovation et la réalisation de projets créatifs.')
@section('keywords', 'studio creatif evc, creation design, innovation, projets creatifs')

@push('styles')
    <style>
        .studio-wrapper {
            --primary: #ff6b35;
            --primary-dark: #e55a2b;
            --accent: #00d4ff;
            --bg-dark: #0a0e27;
            --bg-card: #151a3d;
            --text-primary: #ffffff;
            --text-secondary: #a0aec0;
        }

        .studio-container {
            background: linear-gradient(135deg, var(--bg-dark) 0%, #1a1f4e 50%, #0d1333 100%);
            min-height: 100vh;
            padding: 340px 20px 60px;
            position: relative;
        }

        .studio-hero {
            text-align: center;
            margin-bottom: 32px;
        }

        .studio-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 12px;
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .studio-subtitle {
            font-size: clamp(1rem, 2vw, 1.125rem);
            color: var(--text-secondary);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .studio-content {
            max-width: 860px;
            margin: 0 auto;
        }

        .studio-card {
            background: rgba(21, 26, 61, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            transition: all 0.3s ease;
        }

        .studio-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            opacity: 0.7;
        }

        .studio-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 107, 53, 0.2);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
        }

        .studio-card h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -0.01em;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .studio-card h2 i {
            color: var(--primary);
            font-size: 1.25rem;
        }

        .studio-card p {
            color: var(--text-secondary);
            font-size: 1rem;
            line-height: 1.7;
            margin-bottom: 20px;
        }

        .studio-card p:last-of-type {
            margin-bottom: 24px;
        }

        .studio-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .studio-item {
            background: rgba(10, 14, 39, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .studio-item:hover {
            background: rgba(255, 107, 53, 0.08);
            border-color: rgba(255, 107, 53, 0.2);
        }

        .studio-item i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .studio-item h3 {
            color: var(--text-primary);
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .studio-item p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
            margin: 0;
        }

        @media (max-width: 768px) {
            .studio-container {
                padding: 240px 20px 60px;
            }

            .studio-grid {
                grid-template-columns: 1fr;
            }

            .studio-card {
                padding: 24px;
            }
        }

        .studio-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 107, 53, 0.1);
            border: 1px solid rgba(255, 107, 53, 0.3);
            border-radius: 9999px;
            color: var(--primary);
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 24px;
        }

        .studio-badge i {
            color: var(--primary);
        }

        .studio-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 28px;
            margin-bottom: 48px;
        }

        .studio-cards .studio-card {
            margin-bottom: 0;
        }

        .studio-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .studio-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            color: var(--text-secondary);
            margin-bottom: 14px;
            line-height: 1.5;
            font-size: 0.9375rem;
        }

        .studio-list li:last-child {
            margin-bottom: 0;
        }

        .studio-list i {
            color: var(--primary);
            margin-top: 3px;
            flex-shrink: 0;
        }

        .studio-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 48px;
        }

        .studio-stat {
            background: linear-gradient(135deg, rgba(21, 26, 61, 0.6) 0%, rgba(10, 14, 39, 0.5) 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 20px;
            padding: 28px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .studio-stat::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            opacity: 0.6;
        }

        .studio-stat:hover {
            transform: translateY(-4px);
            border-color: rgba(255, 107, 53, 0.25);
        }

        .studio-stat .number {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .studio-stat .label {
            font-size: 0.875rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .studio-cta {
            text-align: center;
        }

        .studio-cta a {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 18px 40px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            font-size: 1.0625rem;
            font-weight: 700;
            border-radius: 9999px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 28px rgba(255, 107, 53, 0.35);
        }

        .studio-cta a:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(255, 107, 53, 0.45);
        }

        @media (max-width: 768px) {

            .studio-cards,
            .studio-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="studio-wrapper">
        <div class="studio-container">
            <div class="container">
                <div class="studio-hero" data-aos="fade-up">
                    <div class="studio-badge">
                        <i class="fas fa-sparkles"></i>
                        <span>Pôle Créatif EVC</span>
                    </div>
                    <h1 class="studio-title">Studio Creative</h1>
                    <p class="studio-subtitle">
                        L'incubateur où les idées deviennent projets, et où les projets deviennent des opportunités
                        professionnelles.
                    </p>
                </div>

                <div class="studio-cards" data-aos="fade-up" data-aos-delay="100">
                    <div class="studio-card">
                        <h2><i class="fas fa-flask"></i> C'est quoi le Studio Creative ?</h2>
                        <p>
                            Le Studio Creative est le laboratoire créatif de l'École Virtuelle des Créatifs. C'est un espace
                            d'expression et d'innovation où étudiants, formateurs et professionnels co-créent des projets
                            concrets dans le design, le community management, l'informatique et l'intelligence artificielle.
                        </p>
                        <ul class="studio-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Un atelier de production intégré à la formation.</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Des projets réels commandités par des entreprises et associations.</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Un accompagnement de l'idée à la livraison finale.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="studio-card">
                        <h2><i class="fas fa-rocket"></i> Pourquoi le Studio Creative ?</h2>
                        <p>
                            Parce qu'apprendre en créant est le meilleur moyen de maîtriser un métier. Le Studio met chaque
                            participant en situation professionnelle, avec des deadlines, des clients et des livrables
                            concrets.
                        </p>
                        <ul class="studio-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Travaillez sur des briefs authentiques.</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Profitez du retour d'experts en activité.</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Construisez un portfolio qui attire les recruteurs.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="studio-stats" data-aos="fade-up" data-aos-delay="200">
                    <div class="studio-stat">
                        <div class="number">150+</div>
                        <div class="label">Projets Réalisés</div>
                    </div>
                    <div class="studio-stat">
                        <div class="number">20+</div>
                        <div class="label">Formateurs Experts</div>
                    </div>
                    <div class="studio-stat">
                        <div class="number">95%</div>
                        <div class="label">Satisfaction</div>
                    </div>
                    <div class="studio-stat">
                        <div class="number">5+</div>
                        <div class="label">Pays Touchés</div>
                    </div>
                </div>

                <div class="studio-cta" data-aos="fade-up" data-aos-delay="300">
                    <a href="{{ route('formations') }}">
                        <span>Rejoindre le Studio Creative</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
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
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6">
                            <i class="fas fa-fingerprint text-orange-500"></i>
                            <span class="text-orange-400 font-semibold text-sm">L'ADN EVC</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                            L’École où l’on Apprend en Créant
                        </h2>
                        <p class="text-lg text-gray-400 mb-8">
                            À l’EVC, la pratique n’est pas une option, c’est l’ADN même de la pédagogie. Chaque projet est
                            un terrain d’expérimentation, chaque challenge une étape vers la maîtrise.
                        </p>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20">
                                    <i class="fas fa-rocket text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-2">Action Immédiate</h3>
                                    <p class="text-gray-400">"On n’apprend pas la créativité dans les livres, mais dans
                                        l’action." — C'est cette action qui transforme nos étudiants en professionnels.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                    <i class="fas fa-user-tie text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-2">Qualités Humaines</h3>
                                    <p class="text-gray-400">Au-delà de la technique, nos étudiants développent rigueur,
                                        réactivité, communication et leadership indispensables en entreprise.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div
                                    class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                                    <i class="fas fa-briefcase text-white"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white mb-2">95% d'Employabilité</h3>
                                    <p class="text-gray-400">Le Studio Créative est une rampe de lancement vers le monde
                                        professionnel. Nos lauréats sont prêts à l'emploi.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Témoignage mis en avant -->
                    <div data-aos="fade-left">
                        <div class="relative">
                            <!-- Décoration -->
                            <div
                                class="absolute -top-10 -right-10 text-[10rem] text-white/5 font-serif leading-none select-none">
                                "</div>

                            <div
                                class="bg-gradient-to-br from-white/10 to-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/10 shadow-2xl relative z-10">
                                <div class="flex items-center gap-4 mb-6">
                                    <div
                                        class="w-16 h-16 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg border-2 border-orange-400/50">
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
                                        "Avant EVC, j'avais acheté des tutos que j'ai à peine regardés. Avec EVC, tout a été
                                        différent : la pédagogie, la structuration des cours, tout m'a convaincu. Quand je
                                        suis les formations, j'ai vraiment l'impression d'être dans la même salle que le
                                        formateur. Et surtout, la disponibilité 24h/24 du coach pour nous encadrer !"
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
                            <a href="{{ route('formations') }}"
                                class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full text-white font-semibold hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-orange-500/50 group">
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
                    <div
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500/10 border border-blue-500/30 rounded-full mb-6">
                        <i class="fas fa-gavel text-blue-400"></i>
                        <span class="text-blue-300 font-semibold text-sm">Jury d'Évaluation</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Des Talents Évalués par des Experts
                    </h2>
                    <p class="text-lg text-gray-400 max-w-3xl mx-auto">
                        Un jury prestigieux composé de directeurs artistiques, entrepreneurs créatifs et designers
                        confirmés. Ce panel d’experts vient évaluer, orienter et valoriser les projets des étudiants.
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
                                'image' => 'Marc-Aurele-Directeur-Creatif-Chez-Agence-X-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Danielle Attebi Epse Kouyo',
                                'title' => 'Chef de projet web',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Danielle-Attebi-Epse-Kouyo-Chef-de-projet-web-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Monsieur H',
                                'title' => 'Directeur Artistique',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Monsieur-H-Directeur-Artistique-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Adaezé Chukwu',
                                'title' => 'Creative Designer',
                                'country' => 'Nigeria',
                                'flag' => '🇳🇬',
                                'image' => 'Adaeze-Chukwu-Creative-Designer-Nigeria.jpg',
                            ],
                            [
                                'name' => 'Elie Foua Bi',
                                'title' => 'Directeur Artistique',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Elie-Foua-Bi-Directeur-Artistique-Cote-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Doris Dagri',
                                'title' => 'Graphiste Senior',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Doris-Dagri-Graphiste-Senior-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Jean Michel',
                                'title' => 'Créateur d\'expérience 360',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Jean-Michel-Createur-d-experience-360-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Délima Aby',
                                'title' => 'Infographiste senior',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Delima-Aby-Infographiste-senior-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Frank Ouedraogo',
                                'title' => 'Graphiste',
                                'country' => 'Burkina Faso',
                                'flag' => '🇧🇫',
                                'image' => 'Frank-Ouedraogo-Graphiste-Burkina-Faso.jpg',
                            ],
                            [
                                'name' => 'Alban M\'Lan',
                                'title' => 'Chef d\'Entreprise',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Alban-M-Lan-Chef-d-Entreprise-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Abdoul Latif',
                                'title' => 'Senior Graphiste',
                                'country' => 'Burkina Faso',
                                'flag' => '🇧🇫',
                                'image' => 'Abdoul-Latif-Senior-Graphiste-Burkina-Faso.jpg',
                            ],
                            [
                                'name' => 'Lydie Wendkuuni',
                                'title' => 'Graphic Designer',
                                'country' => 'Burkina Faso',
                                'flag' => '🇧🇫',
                                'image' => 'Lydie-Wendkuuni-Graphic-Designer-Burkina-Faso.jpg',
                            ],
                            [
                                'name' => 'Armel ABÉ',
                                'title' => 'Graphiste Photographe',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Armel-ABE-Graphiste-Photographe-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Bernice Alikpa',
                                'title' => 'Graphiste Designer Senior',
                                'country' => 'Bénin',
                                'flag' => '🇧🇯',
                                'image' => 'Bernice-Alikpa-Graphiste-designer-Senior-Benin.jpg',
                            ],
                            [
                                'name' => 'Check Maiga',
                                'title' => 'Graphiste Imprimeur',
                                'country' => 'Burkina Faso',
                                'flag' => '🇧🇫',
                                'image' => 'Check-Maiga-Graphiste-Imprimeur-Burkina-Faso.jpg',
                            ],
                            [
                                'name' => 'Cissé Moctar',
                                'title' => 'Journaliste Bilingue',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Cisse-Moctar-Journaliste-Bilinge-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Désiré Ganh',
                                'title' => 'Professeur en Design Graphic',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Desire-Ganh-Professeur-en-Design-Graphic-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Eugène Ndiolène',
                                'title' => 'Brand Identity Designer',
                                'country' => 'Sénégal',
                                'flag' => '🇸🇳',
                                'image' => 'Eugene-Ndiolene-Brand-Identity-Designer-Senegal.jpg',
                            ],
                            [
                                'name' => 'Ingrid Zaté',
                                'title' => 'Graphic Designer',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Ingrid-Zate-Graphic-Designer-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'K Steven Lanyan',
                                'title' => 'Graphiste Designer',
                                'country' => 'Bénin',
                                'flag' => '🇧🇯',
                                'image' => 'K-Steven-Lanyan-Graphiste-Designer-Benin.jpg',
                            ],
                            [
                                'name' => 'Somey Amegnibo',
                                'title' => 'Designer Créateur de Contenus',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Somey-Amegnibo-Designer-Createur-de-Contenus-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Sylla Rokia',
                                'title' => 'Journaliste Professionnelle',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Sylla-Rokia-Journaliste-Professionnelle-Cote-d-Ivoire.jpg',
                            ],
                            [
                                'name' => 'Wei Zhang',
                                'title' => 'Expert Digital Innovation',
                                'country' => 'Chine',
                                'flag' => '🇨🇳',
                                'image' => 'https://randomuser.me/api/portraits/men/11.jpg',
                                'is_external' => true,
                            ],
                            [
                                'name' => 'Omar Al-Fayed',
                                'title' => 'Senior Art Director',
                                'country' => 'Arabie Saoudite',
                                'flag' => '🇸🇦',
                                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                                'is_external' => true,
                            ],
                            [
                                'name' => 'Bini Nazaire Kouame',
                                'title' => 'Directeur de création',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Bini-Nazaire-Kouame-Directeur-de-creation-Cote-d-ivoire.jpg',
                            ],
                            [
                                'name' => 'Nebout Alfred Désiré',
                                'title' => 'Directeur Artistique',
                                'country' => 'Côte d\'Ivoire',
                                'flag' => '🇨🇮',
                                'image' => 'Nebout-Alfred-Desire-Directeur-Artistique-Cote-d-Ivoire.jpg',
                            ],
                        ];
                    @endphp

                    @foreach ($juryMembers as $index => $member)
                        <div class="group" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div
                                class="relative bg-gradient-to-br from-white/5 to-white/10 backdrop-blur-lg rounded-3xl p-6 border border-white/10 h-full flex flex-col transition-all duration-500 hover:border-orange-500/50 hover:transform hover:scale-105 hover:shadow-2xl hover:shadow-orange-500/20">
                                <!-- Badge Expert -->
                                <div
                                    class="absolute -top-3 -right-3 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full px-4 py-1 shadow-lg">
                                    <span class="text-white text-xs font-bold">JURY</span>
                                </div>

                                <!-- Avatar -->
                                <div class="relative mx-auto mb-6">
                                    <div
                                        class="w-32 h-32 rounded-full overflow-hidden border-4 border-white/10 group-hover:border-orange-500/50 transition-all duration-300">
                                        <img src="{{ isset($member['is_external']) && $member['is_external'] ? $member['image'] : asset('assets/img/membre_du_jury/' . $member['image']) }}"
                                            alt="{{ $member['name'] }}" class="w-full h-full object-cover object-top">
                                    </div>
                                    <!-- Badge vérifié -->
                                    <div
                                        class="absolute bottom-0 right-2 w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full border-2 border-white flex items-center justify-center">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <h3
                                        class="text-xl font-bold text-white mb-2 group-hover:text-orange-400 transition-colors">
                                        {{ $member['name'] }}</h3>
                                    <p class="text-sm text-gray-300 font-medium mb-3 h-10 overflow-hidden">
                                        {{ $member['title'] }}</p>

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
                    <a href="{{ route('jury') }}"
                        class="inline-flex items-center gap-2 px-8 py-3 bg-white/10 hover:bg-white/20 border border-white/20 rounded-full text-white font-semibold transition-all duration-300 transform hover:scale-105">
                        <span>Voir tous les membres du jury</span>
                        <i class="fas fa-arrow-right text-orange-500"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Section Studio Creative (DÉPLACÉ ICI : La méthode après la preuve) -->
        <div class="bg-gradient-to-b from-[#001233] to-[#0a1128] py-20 relative">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-7xl"> <!-- Suppression de max-w-4xl pour permettre la largeur complète -->
                    <!-- Titre -->
                    <div class="text-center mb-16" data-aos="fade-up">
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 bg-orange-500/10 border border-orange-500/30 rounded-full mb-6">
                            <i class="fas fa-magic text-orange-400"></i>
                            <span class="text-orange-300 font-semibold text-sm">Le Secret de Fabrication</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">La Méthode "Studio Creative"</h2>
                        <p class="text-lg text-gray-400 max-w-2xl mx-auto">Une approche pédagogique unique qui simule la
                            réalité du monde professionnel pour vous rendre opérationnel dès la sortie.</p>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-8 items-stretch">
                        <!-- Pourquoi ? -->
                        <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/10 h-full flex flex-col hover:border-orange-500/30 transition-colors"
                            data-aos="fade-right" data-aos-delay="100">
                            <div class="mb-6">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-orange-500/20 flex items-center justify-center mb-4 text-orange-400">
                                    <i class="fas fa-lightbulb text-2xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-2">
                                    Une Expérience de Terrain
                                </h3>
                                <div class="h-1 w-20 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full"></div>
                            </div>

                            <div class="text-gray-300 space-y-6 leading-relaxed flex-grow">
                                <p>
                                    Ici, pas de copies parfaites ni de discours théoriques. Le Studio Créative plonge les
                                    étudiants dans <strong class="text-white">les conditions réelles d’une agence de
                                        communication</strong>.
                                </p>

                                <div class="space-y-4">
                                    <div class="flex gap-4 items-start p-4 bg-white/5 rounded-xl border border-white/5">
                                        <i class="fas fa-users text-orange-400 mt-1"></i>
                                        <div>
                                            <h4 class="text-white font-semibold text-sm mb-1">Immersion Totale</h4>
                                            <p class="text-sm text-gray-400">Brainstorming, gestion de projet, création
                                                d’identité visuelle, argumentation client, deadlines à respecter.</p>
                                        </div>
                                    </div>

                                    <div class="flex gap-4 items-start p-4 bg-white/5 rounded-xl border border-white/5">
                                        <i class="fas fa-layer-group text-orange-400 mt-1"></i>
                                        <div>
                                            <h4 class="text-white font-semibold text-sm mb-1">Mini-Studios</h4>
                                            <p class="text-sm text-gray-400">Chaque équipe devient un mini-studio où l'on
                                                échange, on s’inspire et on ose comme des pros.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-auto pt-6 border-t border-white/10">
                                    <p class="text-orange-300 text-sm font-medium italic">
                                        "C’est la salle de classe transformée en agence, et l’étudiant qui devient
                                        professionnel."
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Objectifs -->
                        <div class="bg-white/5 backdrop-blur-lg rounded-3xl p-8 border border-white/10 h-full flex flex-col hover:border-orange-500/30 transition-colors"
                            data-aos="fade-left" data-aos-delay="200">
                            <div class="mb-6">
                                <div
                                    class="w-14 h-14 rounded-2xl bg-orange-500/20 flex items-center justify-center mb-4 text-orange-400">
                                    <i class="fas fa-bullseye text-2xl"></i>
                                </div>
                                <h3 class="text-2xl font-bold text-white mb-2">
                                    Tremplin vers la Certification
                                </h3>
                                <div class="h-1 w-20 bg-gradient-to-r from-orange-500 to-orange-600 rounded-full"></div>
                            </div>

                            <div class="space-y-6 flex-grow">
                                <p class="text-gray-300 leading-relaxed">
                                    Le Studio Créative s’intègre dans le test d’éligibilité à la certification officielle de
                                    l’EVC, marquant la dernière étape d’un parcours d’excellence.
                                </p>

                                <div class="flex gap-4 group">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                                            <span class="text-white font-bold text-sm">1</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold mb-1">Vitrine Vivante</h4>
                                        <p class="text-sm text-gray-400 leading-relaxed">Démontrer l’étendue de son talent,
                                            sa personnalité et sa valeur sur le marché.</p>
                                    </div>
                                </div>

                                <div class="flex gap-4 group">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                                            <span class="text-white font-bold text-sm">2</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold mb-1">Opportunités Réelles</h4>
                                        <p class="text-sm text-gray-400 leading-relaxed">Une chance unique de se faire
                                            remarquer, de se vendre et de décrocher des collaborations.</p>
                                    </div>
                                </div>

                                <div class="flex gap-4 group">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg shadow-orange-500/20 group-hover:scale-110 transition-transform">
                                            <span class="text-white font-bold text-sm">3</span>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold mb-1">Réseautage Actif</h4>
                                        <p class="text-sm text-gray-400 leading-relaxed">Présenter son savoir-faire
                                            directement à des professionnels du milieu créatif.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 pt-4">
                                <div
                                    class="p-4 bg-orange-500/10 border border-orange-500/30 rounded-xl flex items-center gap-3">
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

    </div>
@endsection
