@extends('layouts.app')

@section('title', 'Calendrier 2027 - École Virtuelle des Créatifs')
@section('description', 'Découvrez le calendrier 2027 de l\'École Virtuelle des Créatifs : sessions de formation, activités et remise des certificats officiels.')
@section('keywords', 'calendrier 2027 evc, sessions formation, remise certificats, ecole creatifs')

@push('styles')
<style>
    .calendar-wrapper {
        --primary: #ff6b35;
        --primary-dark: #e55a2b;
        --accent: #00d4ff;
        --bg-dark: #0a0e27;
        --bg-card: #151a3d;
        --text-primary: #ffffff;
        --text-secondary: #a0aec0;
    }

    .calendar-container {
        background: linear-gradient(135deg, var(--bg-dark) 0%, #1a1f4e 50%, #0d1333 100%);
        min-height: 100vh;
        padding: 340px 20px 60px;
        position: relative;
    }

    .calendar-hero {
        text-align: center;
        margin-bottom: 48px;
    }

    .calendar-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 12px;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .calendar-subtitle {
        font-size: clamp(1rem, 2vw, 1.125rem);
        color: var(--text-secondary);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .calendar-content {
        max-width: 860px;
        margin: 0 auto;
    }

    .calendar-section {
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        backdrop-filter: blur(20px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .calendar-section-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .calendar-section-header h2 {
        color: var(--primary);
        font-size: 1.5rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .calendar-timeline {
        position: relative;
        padding-left: 32px;
    }

    .calendar-timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 8px;
        bottom: 8px;
        width: 2px;
        background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 2px;
    }

    .calendar-event {
        position: relative;
        margin-bottom: 32px;
    }

    .calendar-event:last-child {
        margin-bottom: 0;
    }

    .calendar-event::before {
        content: '';
        position: absolute;
        left: -28px;
        top: 8px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--primary);
        box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.2);
    }

    .calendar-event.important::before {
        background: var(--accent);
        box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.2);
    }

    .calendar-section.sessions .calendar-timeline::before {
        background: linear-gradient(180deg, #ff6b35 0%, #e55a2b 100%);
    }

    .calendar-section.sessions .calendar-event::before {
        background: #ff6b35;
        box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.2);
    }

    .calendar-section.sessions .calendar-event-date {
        color: #ff6b35;
        background: rgba(255, 107, 53, 0.1);
        border-color: rgba(255, 107, 53, 0.3);
    }

    .calendar-section.creative .calendar-timeline::before {
        background: linear-gradient(180deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .calendar-section.creative .calendar-event::before {
        background: #8b5cf6;
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.2);
    }

    .calendar-section.creative .calendar-event-date {
        color: #8b5cf6;
        background: rgba(139, 92, 246, 0.1);
        border-color: rgba(139, 92, 246, 0.3);
    }

    .calendar-section.official .calendar-timeline::before {
        background: linear-gradient(180deg, #00d4ff 0%, #0ea5e9 100%);
    }

    .calendar-section.official .calendar-event::before {
        background: #00d4ff;
        box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.2);
    }

    .calendar-section.official .calendar-event-date {
        color: #00d4ff;
        background: rgba(0, 212, 255, 0.1);
        border-color: rgba(0, 212, 255, 0.3);
    }

    .calendar-section-header h2 i {
        margin-right: 8px;
    }

    .calendar-event h4 {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        margin: 16px 0 8px;
    }

    .calendar-event ul {
        color: var(--text-secondary);
        font-size: 0.95rem;
        line-height: 1.7;
        margin: 0 0 0 18px;
        padding: 0;
        list-style: disc;
    }

    .calendar-event ul li {
        margin-bottom: 6px;
    }

    .calendar-event {
        position: relative;
        margin-bottom: 32px;
    }

    .calendar-event-date {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        background: rgba(255, 107, 53, 0.1);
        border: 1px solid rgba(255, 107, 53, 0.3);
        border-radius: 9999px;
        color: var(--primary);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .calendar-event.important .calendar-event-date {
        background: rgba(0, 212, 255, 0.1);
        border-color: rgba(0, 212, 255, 0.3);
        color: var(--accent);
    }

    .calendar-event h3 {
        color: var(--text-primary);
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .calendar-event p {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.7;
        margin: 0;
    }

    .calendar-note {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 16px;
        padding: 16px;
        background: rgba(0, 212, 255, 0.08);
        border: 1px solid rgba(0, 212, 255, 0.15);
        border-radius: 12px;
        color: var(--text-secondary);
        font-size: 0.9375rem;
        line-height: 1.6;
    }

    .calendar-note i {
        color: var(--accent);
        margin-top: 3px;
        flex-shrink: 0;
    }

    .calendar-cta {
        text-align: center;
        margin-top: 32px;
    }

    .calendar-cta a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 16px 32px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        font-weight: 600;
        border-radius: 9999px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.3);
    }

    .calendar-cta a:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(255, 107, 53, 0.4);
    }

    @media (max-width: 768px) {
        .calendar-container {
            padding: 240px 20px 60px;
        }

        .calendar-section {
            padding: 24px;
        }

        .calendar-timeline {
            padding-left: 24px;
        }

        .calendar-event::before {
            left: -22px;
        }
    }
</style>
@endpush

@section('content')
<div class="calendar-wrapper">
    <div class="calendar-container">
        <div class="container">
            <div class="calendar-hero" data-aos="fade-up">
                <h1 class="calendar-title">Calendrier 2027</h1>
                <p class="calendar-subtitle">
                    Retrouvez les sessions de formation, les activités et les événements de l'année 2027 à l'École Virtuelle des Créatifs.
                </p>
            </div>

            <div class="calendar-content">
                <div class="calendar-section sessions" data-aos="fade-up" data-aos-delay="100">
                    <div class="calendar-section-header">
                        <h2><i class="fas fa-graduation-cap"></i> Sessions de formation 2027</h2>
                    </div>

                    <div class="calendar-timeline">
                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-rocket"></i> Mars 2027
                            </div>
                            <h3>Session 01 — Rentrée de printemps</h3>
                            <p>
                                Lancement de la première promotion de l'année. Intégration complète des nouveaux apprenants, découverte des parcours et mise en route des outils digitaux.
                            </p>
                            <h4>Au programme :</h4>
                            <ul>
                                <li>Ateliers d'orientation design graphique, community management, IA & informatique</li>
                                <li>Prise en main de la plateforme pédagogique et des ressources en ligne</li>
                                <li>Premiers modules théoriques et premiers exercices pratiques</li>
                            </ul>
                        </div>

                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-sun"></i> Juillet 2027
                            </div>
                            <h3>Session 02 — Rentrée d'été</h3>
                            <p>
                                Session estivale axée sur les parcours courts et les modules spécialisés. Un rythme dynamique pour avancer sereinement sur les compétences techniques.
                            </p>
                            <h4>Au programme :</h4>
                            <ul>
                                <li>Modules condensés et ateliers pratiques quotidiens</li>
                                <li>Challenges créatifs en équipe et retours d'expérience</li>
                                <li>Suivi personnalisé et évaluation continue des acquis</li>
                            </ul>
                        </div>

                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-leaf"></i> Novembre 2027
                            </div>
                            <h3>Session 03 — Rentrée d'automne</h3>
                            <p>
                                Dernière session de formation 2027. Concentration sur les projets finaux, le portfolio professionnel et la préparation des certifications.
                            </p>
                            <h4>Au programme :</h4>
                            <ul>
                                <li>Projets de synthèse et constitution du portfolio</li>
                                <li>Coaching individuel pour l'insertion professionnelle</li>
                                <li>Évaluations finales et passage des certifications</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="calendar-section creative" data-aos="fade-up" data-aos-delay="200">
                    <div class="calendar-section-header">
                        <h2><i class="fas fa-palette"></i> Studios Créatifs 2027</h2>
                    </div>

                    <div class="calendar-timeline">
                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-lightbulb"></i> Mai 2027
                            </div>
                            <h3>Studio Créatif Printemps</h3>
                            <p>
                                Un mois entier consacré à l'expérimentation libre, la création visuelle et la production multimédia. L'occasion de transformer ses idées en projets concrets.
                            </p>
                            <h4>En détail :</h4>
                            <ul>
                                <li>Ateliers de design, photo, vidéo et communication visuelle</li>
                                <li>Challenges créatifs et hackathons thématiques</li>
                                <li>Exposition collective des productions réalisées</li>
                            </ul>
                        </div>

                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-magic"></i> Octobre 2027
                            </div>
                            <h3>Studio Créatif Automne</h3>
                            <p>
                                Nouvelle immersion créative tournée vers l'innovation digitale, le motion design et les projets multimédias interactifs.
                            </p>
                            <h4>En détail :</h4>
                            <ul>
                                <li>Masterclasses animées par des professionnels du secteur</li>
                                <li>Projets tutorés en design graphique et motion design</li>
                                <li>Présentation des travaux et feedbacks d'experts</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="calendar-section official" data-aos="fade-up" data-aos-delay="300">
                    <div class="calendar-section-header">
                        <h2><i class="fas fa-certificate"></i> Événements officiels 2027</h2>
                    </div>

                    <div class="calendar-timeline">
                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-trophy"></i> Juin 2027
                            </div>
                            <h3>Cérémonie de remise des certificats</h3>
                            <p>
                                Moment de reconnaissance pour les apprenants ayant validé leur formation. Remise des certificats officiels, témoignages et célébration des parcours réussis.
                            </p>
                            <h4>Déroulé :</h4>
                            <ul>
                                <li>Allocutions d'ouverture et présentation des lauréats</li>
                                <li>Remise des certificats officiels et attestations</li>
                                <li>Networking entre étudiants, anciens et professionnels</li>
                            </ul>
                            <div class="calendar-note">
                                <i class="fas fa-info-circle"></i>
                                <span>La remise des certificats officiels est un événement annuel unique. Assurez-vous d'être éligible en respectant les critères de validation de votre formation.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="calendar-cta" data-aos="fade-up" data-aos-delay="400">
                    <a href="{{ route('admissions') }}">
                        <i class="fas fa-arrow-left"></i>
                        <span>Retour aux admissions</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
