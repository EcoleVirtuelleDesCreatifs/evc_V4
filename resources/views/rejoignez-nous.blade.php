@extends('layouts.app')

@section('title', 'Rejoignez-nous - École Virtuelle des Créatifs')
@section('description', 'Rejoignez l\'École Virtuelle des Créatifs en tant que collaborateur, partenaire ou formateur. Participez à la formation de la prochaine génération de créatifs en Côte d\'Ivoire.')
@section('keywords', 'rejoindre evc, collaborateur evc, partenaire evc, devenir formateur, école virtuelle abidjan, emploi formation côte d\'ivoire')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0a1628 0%, #1a2942 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            padding: 120px 0 80px;
            text-align: center;
            color: white;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(79, 195, 247, 0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 50%, #ffffff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fadeInDown 1s ease-out;
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            max-width: 700px;
            margin: 0 auto 3rem;
            line-height: 1.8;
            animation: fadeInUp 1s ease-out 0.2s both;
        }

        /* Cards Container */
        .cards-container {
            padding: 60px 0 100px;
            position: relative;
        }

        .join-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
            height: 100%;
            animation: fadeInUp 0.8s ease-out both;
        }

        .join-card:nth-child(1) { animation-delay: 0.1s; }
        .join-card:nth-child(2) { animation-delay: 0.2s; }
        .join-card:nth-child(3) { animation-delay: 0.3s; }

        .join-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, transparent, var(--card-color), transparent);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .join-card:hover::before {
            opacity: 1;
        }

        .join-card:hover {
            transform: translateY(-10px);
            border-color: var(--card-color);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 40px var(--card-color-alpha);
        }

        /* Card Colors */
        .card-collaborateur {
            --card-color: #ff9800;
            --card-color-alpha: rgba(255, 152, 0, 0.2);
        }

        .card-partenaire {
            --card-color: #4fc3f7;
            --card-color-alpha: rgba(79, 195, 247, 0.2);
        }

        .card-formateur {
            --card-color: #9c27b0;
            --card-color-alpha: rgba(156, 39, 176, 0.2);
        }

        /* Icon Circle */
        .icon-circle {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            position: relative;
            transition: all 0.4s;
        }

        .card-collaborateur .icon-circle {
            background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);
            box-shadow: 0 10px 30px rgba(255, 152, 0, 0.3);
        }

        .card-partenaire .icon-circle {
            background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
            box-shadow: 0 10px 30px rgba(79, 195, 247, 0.3);
        }

        .card-formateur .icon-circle {
            background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
            box-shadow: 0 10px 30px rgba(156, 39, 176, 0.3);
        }

        .join-card:hover .icon-circle {
            transform: scale(1.1) rotate(5deg);
        }

        /* Card Content */
        .card-title {
            font-size: 2rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }

        .card-description {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
            margin-bottom: 2rem;
            min-height: 100px;
        }

        .card-features {
            list-style: none;
            padding: 0;
            margin: 2rem 0;
            text-align: left;
        }

        .card-features li {
            padding: 0.8rem 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .card-features li i {
            color: var(--card-color);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        /* CTA Button */
        .cta-button {
            display: inline-block;
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, var(--card-color) 0%, var(--card-color) 100%);
            border: none;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 10px 30px var(--card-color-alpha);
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px var(--card-color-alpha);
            color: white;
        }

        /* Back Button */
        .back-button {
            position: fixed;
            top: 30px;
            left: 30px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateX(-5px);
            color: white;
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

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

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .join-card {
                margin-bottom: 2rem;
            }

            .back-button {
                top: 15px;
                left: 15px;
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .icon-circle {
                width: 100px;
                height: 100px;
                font-size: 2.5rem;
            }

            .card-title {
                font-size: 1.6rem;
            }

            .card-description {
                font-size: 1rem;
                min-height: auto;
            }
        }

        /* Floating Particles */
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(79, 195, 247, 0.5);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) translateX(100px);
                opacity: 0;
            }
        }
</style>
@endpush

@section('content')
    <!-- Back Button -->
    <a href="{{ url('/') }}" class="back-button">
        <i class="fas fa-arrow-left"></i>
        <span>Retour à l'accueil</span>
    </a>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Rejoignez-nous</h1>
            <p class="hero-subtitle">
                Participez à notre mission de former la prochaine génération de créatifs en Côte d'Ivoire.
                Ensemble, construisons l'avenir du digital africain.
            </p>
        </div>
    </section>

    <!-- Cards Section -->
    <section class="cards-container">
        <div class="container">
            <div class="row g-4">
                <!-- Collaborateur Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="join-card card-collaborateur">
                        <div class="icon-circle">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h2 class="card-title">Collaborateur</h2>
                        <p class="card-description">
                            Intégrez notre équipe dynamique et contribuez à transformer l'éducation digitale en Afrique.
                        </p>
                        <ul class="card-features">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Environnement de travail stimulant</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Opportunités de développement</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Équipe passionnée et innovante</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Impact social significatif</span>
                            </li>
                        </ul>
                        <a href="{{ route('rejoignez-nous.collaborateur') }}" class="cta-button">
                            Postuler maintenant
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Partenaire Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="join-card card-partenaire">
                        <div class="icon-circle">
                            <i class="fas fa-users"></i>
                        </div>
                        <h2 class="card-title">Partenaire</h2>
                        <p class="card-description">
                            Collaborez avec nous pour développer des synergies et créer de la valeur ensemble.
                        </p>
                        <ul class="card-features">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Partenariats stratégiques</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Visibilité accrue</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Accès à un vivier de talents</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Projets collaboratifs innovants</span>
                            </li>
                        </ul>
                        <a href="{{ route('rejoignez-nous.partenaire') }}" class="cta-button">
                            Devenir partenaire
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>

                <!-- Devenir Formateur Card -->
                <div class="col-lg-4 col-md-6">
                    <div class="join-card card-formateur">
                        <div class="icon-circle">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <h2 class="card-title">Devenir formateur</h2>
                        <p class="card-description">
                            Partagez votre expertise et formez la nouvelle génération de créatifs africains.
                        </p>
                        <ul class="card-features">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Transmission de savoir</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Rémunération attractive</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Flexibilité des horaires</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Réseau professionnel étendu</span>
                            </li>
                        </ul>
                        <a href="{{ route('rejoignez-nous.formateur') }}" class="cta-button">
                            Rejoindre l'équipe
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Floating Particles -->
    <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
    <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
    <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
    <div class="particle" style="left: 40%; animation-delay: 1s;"></div>
    <div class="particle" style="left: 50%; animation-delay: 3s;"></div>
    <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
    <div class="particle" style="left: 70%; animation-delay: 2.5s;"></div>
    <div class="particle" style="left: 80%; animation-delay: 4.5s;"></div>
    <div class="particle" style="left: 90%; animation-delay: 1.5s;"></div>
@endsection
