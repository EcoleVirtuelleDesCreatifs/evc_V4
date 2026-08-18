@extends('layouts.app')

@section('title', 'Studio Créatif - École Virtuelle des Créatifs')
@section('description', 'Découvrez le Studio Créatif de l\'École Virtuelle des Créatifs, un espace dédié à la création, l\'innovation et la réalisation de projets créatifs.')
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
        padding: 280px 20px 60px;
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
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        backdrop-filter: blur(20px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .studio-card h2 {
        color: var(--primary);
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 16px;
        letter-spacing: -0.01em;
    }

    .studio-card p {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 16px;
    }

    .studio-card p:last-child {
        margin-bottom: 0;
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
            padding: 180px 20px 60px;
        }

        .studio-grid {
            grid-template-columns: 1fr;
        }

        .studio-card {
            padding: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="studio-wrapper">
    <div class="studio-container">
        <div class="container">
            <div class="studio-hero">
                <h1 class="studio-title">Studio Créatif</h1>
                <p class="studio-subtitle">L'espace de création, d'innovation et de réalisation de projets créatifs de l'École Virtuelle des Créatifs.</p>
            </div>

            <div class="studio-content">
                <div class="studio-card">
                    <h2>Notre mission</h2>
                    <p>Le Studio Créatif est un espace dédié à l'expression artistique et à l'innovation numérique. Il permet aux apprenants et aux professionnels de collaborer sur des projets concrets dans les domaines du design graphique, du community management, de la gestion informatique et de l'intelligence artificielle.</p>
                    <p>Nous accompagnons chaque projet de l'idée à la réalisation, en mettant l'accent sur la qualité, la créativité et l'impact.</p>
                </div>

                <div class="studio-card">
                    <h2>Nos domaines d'intervention</h2>
                    <div class="studio-grid">
                        <div class="studio-item">
                            <i class="fas fa-palette"></i>
                            <h3>Design Graphique</h3>
                            <p>Identité visuelle, affiches, supports print et digitaux</p>
                        </div>
                        <div class="studio-item">
                            <i class="fas fa-video"></i>
                            <h3>Production Vidéo</h3>
                            <p>Montage, motion design et contenus audiovisuels</p>
                        </div>
                        <div class="studio-item">
                            <i class="fas fa-code"></i>
                            <h3>Développement Web</h3>
                            <p>Sites web, applications et expériences interactives</p>
                        </div>
                        <div class="studio-item">
                            <i class="fas fa-bullhorn"></i>
                            <h3>Stratégie Digitale</h3>
                            <p>Gestion de marque, community management et campagnes</p>
                        </div>
                    </div>
                </div>

                <div class="studio-card">
                    <h2>Pourquoi choisir le Studio Créatif ?</h2>
                    <p>Le Studio Créatif offre un environnement propice à l'apprentissage par la pratique. Les participants travaillent sur des projets réels, encadrés par des professionnels du secteur, et bénéficient des retours d'expérience de la communauté EVC.</p>
                    <p>Que vous soyez étudiant, professionnel ou entrepreneur, notre studio est l'endroit idéal pour donner vie à vos idées créatives.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
