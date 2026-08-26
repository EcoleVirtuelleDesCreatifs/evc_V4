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
                <div class="calendar-section" data-aos="fade-up" data-aos-delay="100">
                    <div class="calendar-section-header">
                        <h2>Sessions et événements 2027</h2>
                    </div>

                    <div class="calendar-timeline">
                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-calendar-day"></i> Mars 2027
                            </div>
                            <h3>Session 01</h3>
                            <p>
                                Ouverture de la première session de formation 2027. Intégration des nouveaux apprenants et lancement des parcours en design graphique, community management, informatique et intelligence artificielle.
                            </p>
                        </div>

                        <div class="calendar-event important">
                            <div class="calendar-event-date">
                                <i class="fas fa-award"></i> Juin 2027
                            </div>
                            <h3>Remise des certificats officiels</h3>
                            <p>
                                Cérémonie de remise des certificats officiels aux apprenants ayant validé leur formation. La remise des certificats se fait une seule fois par an.
                            </p>
                            <div class="calendar-note">
                                <i class="fas fa-info-circle"></i>
                                <span>La remise des certificats officiels est un événement annuel unique. Assurez-vous d'être éligible en respectant les critères de validation de votre formation.</span>
                            </div>
                        </div>

                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-calendar-day"></i> Juillet 2027
                            </div>
                            <h3>Session 02</h3>
                            <p>
                                Deuxième session de l'année 2027. Nouvelle promotion de créatifs et professionnels pour des parcours courts et des modules spécialisés.
                            </p>
                        </div>

                        <div class="calendar-event">
                            <div class="calendar-event-date">
                                <i class="fas fa-calendar-day"></i> Novembre 2027
                            </div>
                            <h3>Session 03</h3>
                            <p>
                                Troisième session de formation 2027. Dernière rentrée de l'année pour préparer les projets et le portfolio de fin d'année.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="calendar-cta" data-aos="fade-up" data-aos-delay="200">
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
