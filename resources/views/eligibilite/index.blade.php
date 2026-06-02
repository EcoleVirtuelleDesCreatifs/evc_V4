@extends('layouts.app')

@section('title', 'Test d\'éligibilité SAOP - École Virtuelle des Créatifs')
@section('description', 'Parcours officiel d\'intégration et d\'orientation SAOP pour les futurs étudiants EVC.')
@section('keywords', 'test éligibilité EVC, SAOP, admission EVC, orientation pédagogique')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #ff9800;
        --primary-dark: #f57c00;
        --primary-gradient: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);
        --accent: #ffb74d;
        --bg-dark: #0f172a;
        --bg-card: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --border: #334155;
        --glow-orange: rgba(255, 152, 0, 0.3);
    }

    body {
        background: var(--bg-dark);
        font-family: 'Inter', sans-serif;
        color: var(--text-primary);
    }

    .saop-page {
        min-height: 100vh;
        padding: 160px 20px 70px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .saop-hero {
        text-align: center;
        padding-top: 60px;
        margin-bottom: 42px;
    }

    .saop-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: rgba(255, 152, 0, 0.1);
        border: 1px solid rgba(255, 152, 0, 0.3);
        border-radius: 999px;
        color: var(--primary);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .6px;
        box-shadow: 0 0 24px var(--glow-orange);
        margin-bottom: 20px;
    }

    .saop-hero h1 {
        font-size: clamp(34px, 6vw, 64px);
        font-weight: 900;
        margin: 0 0 14px;
        letter-spacing: -1.5px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .saop-hero h2 {
        font-size: clamp(16px, 2.5vw, 24px);
        font-weight: 800;
        color: var(--text-primary);
        margin: 0 0 12px;
        text-transform: uppercase;
    }

    .saop-hero p {
        max-width: 850px;
        margin: 0 auto;
        color: var(--text-secondary);
        font-size: 17px;
        line-height: 1.8;
    }

    .saop-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    .saop-card {
        background: rgba(30, 41, 59, .92);
        border: 1px solid var(--border);
        border-radius: 18px;
        padding: 30px;
        box-shadow: 0 24px 70px rgba(0,0,0,.24);
    }

    .saop-card.highlight {
        border-color: rgba(255, 152, 0, .38);
        background: linear-gradient(135deg, rgba(255,152,0,.11), rgba(30,41,59,.94));
    }

    .saop-section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 22px;
        font-weight: 900;
        margin: 0 0 18px;
        color: var(--text-primary);
    }

    .saop-section-title i {
        color: var(--primary);
    }

    .saop-card p,
    .saop-card li {
        color: var(--text-secondary);
        line-height: 1.85;
        font-size: 15px;
    }

    .saop-card ul {
        margin: 0;
        padding-left: 18px;
    }

    .questions-card {
        margin-top: 24px;
    }

    .question-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        margin-top: 22px;
    }

    .question-item {
        display: grid;
        grid-template-columns: 58px 1fr;
        gap: 18px;
        align-items: start;
        padding: 20px;
        border-radius: 16px;
        background: rgba(15, 23, 42, .72);
        border: 1px solid rgba(51, 65, 85, .9);
        transition: border-color .25s, transform .25s, background .25s;
    }

    .question-item:hover {
        border-color: rgba(255, 152, 0, .45);
        transform: translateY(-2px);
        background: rgba(15, 23, 42, .9);
    }

    .question-number {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-gradient);
        color: #111827;
        font-weight: 900;
        box-shadow: 0 10px 25px rgba(255, 152, 0, .22);
    }

    .question-text {
        color: var(--text-primary);
        line-height: 1.75;
        font-size: 16px;
        margin: 0;
    }

    .saop-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        justify-content: center;
        margin-top: 32px;
    }

    .saop-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 22px;
        border-radius: 999px;
        text-decoration: none;
        font-weight: 900;
        transition: transform .2s, box-shadow .2s;
    }

    .saop-btn.primary {
        background: var(--primary-gradient);
        color: #111827;
        box-shadow: 0 16px 35px rgba(255, 152, 0, .22);
    }

    .saop-btn.secondary {
        color: var(--text-primary);
        border: 1px solid var(--border);
        background: rgba(255,255,255,.04);
    }

    .saop-btn:hover {
        transform: translateY(-2px);
    }

    @media (max-width: 900px) {
        .saop-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 640px) {
        .saop-page { padding: 140px 16px 50px; }
        .saop-hero { padding-top: 38px; }
        .saop-card { padding: 22px; }
        .question-item { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="saop-page">
    <section class="saop-hero">
        <span class="saop-badge"><i class="fas fa-clipboard-check"></i> SAOP - ADMISSION & ORIENTATION</span>
        <h1>Test d’éligibilité</h1>
        <h2>Direction des Études et de Régulation Pédagogique</h2>
        <p>Parcours officiel d'intégration et d'orientation — un diagnostic obligatoire conçu pour évaluer l’adéquation technique, la disponibilité, la motivation et la capacité de réussite de chaque futur étudiant EVC.</p>
    </section>

    <div class="saop-grid">
        <article class="saop-card highlight">
            <h3 class="saop-section-title"><i class="fas fa-shield-halved"></i> Charte qualité EVC</h3>
            <p>Le système EVC Admission et d'Orientation est le pilier de notre charte qualité. Il ne s'agit pas d'un simple test, mais d'un diagnostic obligatoire pour chaque futur étudiant.</p>
        </article>

        <article class="saop-card">
            <h3 class="saop-section-title"><i class="fas fa-route"></i> Objectif du parcours</h3>
            <p>Le présent document définit les étapes obligatoires et éliminatoires pour l'admission des apprenants au sein des cursus de l'École Virtuelle des Créatifs.</p>
        </article>
    </div>

    <article class="saop-card">
        <h3 class="saop-section-title"><i class="fas fa-award"></i> Positionnement pédagogique</h3>
        <p>Fidèle à son positionnement d'excellence pratique, l'établissement applique un protocole de sélection rigoureux afin de garantir l'adéquation technique et la réussite de chaque cohorte.</p>
    </article>

    <article class="saop-card questions-card">
        <h3 class="saop-section-title"><i class="fas fa-list-check"></i> Questionnaire officiel de validation</h3>
        <p>Répondez avec précision à chaque question. Les réponses permettent à l’équipe pédagogique d’évaluer votre environnement technique, votre disponibilité et votre engagement.</p>

        @php
            $questions = [
                "Quelle est la configuration technique complète de l’ordinateur de bureau ou portable que vous exploiterez au quotidien durant votre formation (RAM, processeur, stockage, etc.) ?",
                "De quel volume horaire hebdomadaire disposez-vous de manière effective pour la production des projets en dehors des heures de formations ?",
                "Quel est le projet professionnel précis que vous visez à l’issue de votre formation à EVC ? Dans quel but voulez-vous faire cette formation ?",
                "Quelle est la nature et la stabilité de la connexion internet dont vous disposez pour le téléchargement des ressources de haute définition ? Avez-vous un Wi-Fi à domicile, utilisez-vous un cyber café ou une souscription mobile ?",
                "Avez-vous déjà manipulé un logiciel de la Suite Adobe (Photoshop, Illustrator, InDesign) ou démarrez-vous un apprentissage totalement à zéro ?",
                "Face à un environnement d'apprentissage 100 % digital, quelles sont vos stratégies personnelles pour maintenir votre autodiscipline et éviter la procrastination ?",
                "La formation d'EVC exclut les examens théoriques au profit d'une matrice intensive de 75 projets pratiques. Comment gérez-vous le stress des rendus multiples et la critique technique de vos livrables ?",
                "Quelle est votre maîtrise ou votre niveau d'aisance actuel avec les outils informatiques de base (gestion des fichiers, navigation internet, installation de logiciels) ?",
                "Dans quelle mesure êtes-vous disponible pour participer activement aux événements majeurs de l'écosystème, notamment la remise des certificats en ligne ou en présentiel ? Si vous résidez en Côte d’Ivoire, pourrez-vous effectuer le déplacement ?",
                "Si votre profil est retenu, êtes-vous prêt à valider administrativement et financièrement votre inscription sous un délai de 72 heures pour bloquer votre place au sein de la cohorte ?",
            ];
        @endphp

        <div class="question-list">
            @foreach($questions as $index => $question)
                <div class="question-item">
                    <div class="question-number">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <p class="question-text">{{ $question }}</p>
                </div>
            @endforeach
        </div>

        <div class="saop-actions">
            <a href="{{ route('preinscription.start') }}" class="saop-btn primary"><i class="fas fa-user-plus"></i> Accéder à la préinscription</a>
            <a href="{{ url('/') }}" class="saop-btn secondary"><i class="fas fa-house"></i> Retour à l’accueil</a>
        </div>
    </article>
</div>
@endsection
