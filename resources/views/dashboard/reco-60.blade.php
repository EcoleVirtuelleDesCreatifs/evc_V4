@extends('layouts.ki-admin')

@section('title', 'RECO 60 - École Virtuelle des Créatifs')
@section('page-title', 'RECO 60')

@section('content')
<style>
    .reco-hero {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 50%, #F77737 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 15px 40px rgba(131, 58, 180, 0.3);
    }

    .reco-hero h1 {
        font-weight: 800;
        font-size: 2.2rem;
        margin-bottom: 0.75rem;
    }

    .reco-hero p {
        font-size: 1.05rem;
        opacity: 0.95;
        margin-bottom: 0;
    }

    .reco-section {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.75rem;
        color: white;
        margin-bottom: 1.5rem;
    }

    .reco-section h2 {
        color: #f8fafc;
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .reco-section p,
    .reco-section li {
        color: #cbd5e1;
        line-height: 1.8;
    }

    .reco-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        padding: 0.45rem 1rem;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .reco-form-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 2px solid #E1306C;
        border-radius: 16px;
        padding: 1.75rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(225, 48, 108, 0.15);
    }

    .reco-form-card h2 {
        color: #f8fafc;
        font-size: 1.35rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .reco-form-card .form-label {
        color: #e2e8f0;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .reco-form-card .form-control,
    .reco-form-card .form-select {
        background: #0f172a;
        border: 1px solid #334155;
        color: #f8fafc;
        border-radius: 10px;
    }

    .reco-form-card .form-control:focus,
    .reco-form-card .form-select:focus {
        background: #0f172a;
        border-color: #E1306C;
        color: #f8fafc;
        box-shadow: 0 0 0 0.2rem rgba(225, 48, 108, 0.25);
    }

    .reco-form-card .form-control::placeholder {
        color: #64748b;
    }

    .reco-submit-btn {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 50%, #F77737 100%);
        border: none;
        color: white;
        font-weight: 700;
        padding: 0.8rem 2rem;
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .reco-submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(225, 48, 108, 0.4);
        color: white;
    }

    .reco-summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin: 1rem 0;
    }

    .reco-summary-item {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid #334155;
        border-radius: 12px;
        padding: 0.9rem 1rem;
    }

    .reco-summary-item .label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        margin-bottom: 0.25rem;
    }

    .reco-summary-item .value {
        font-weight: 700;
        color: #f8fafc;
    }

    .reco-progress {
        height: 10px;
        background: #334155;
        border-radius: 999px;
        overflow: hidden;
        margin-top: 0.5rem;
    }

    .reco-progress > div {
        height: 100%;
        background: linear-gradient(90deg, #833AB4, #E1306C, #F77737);
        border-radius: 999px;
    }
</style>

<div class="reco-hero">
    <div class="reco-badge"><i class="fas fa-briefcase"></i> RECO 60</div>
    <h1>RECO 60 : 60 jours pour transformer la formation en expérience professionnelle</h1>
    <p>À l’École Virtuelle des Créatifs (EVC), la fin des cours ne marque pas automatiquement la fin du parcours de formation. Avec la RECO 60, l’établissement instaure une période de 60 jours de mise en pratique destinée aux étudiants en Community Management / Social Media Management et en Gestion Informatique Appliquée. L’objectif : transformer les compétences acquises en expérience professionnelle concrète avant la certification.</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $recoStart = $enrollment ? \Carbon\Carbon::parse($enrollment->start_date)->startOfDay() : null;
    $recoEnd = $enrollment ? \Carbon\Carbon::parse($enrollment->end_date)->startOfDay() : null;
    $recoTotalDays = $enrollment ? max(1, $recoStart->diffInDays($recoEnd)) : 0;
    $recoElapsedDays = $enrollment ? min($recoTotalDays, max(0, $recoStart->diffInDays(now()))) : 0;
    $recoRemainingDays = $enrollment ? max(0, now()->startOfDay()->diffInDays($recoEnd, false)) : 0;
    $recoProgress = $enrollment ? min(100, round(($recoElapsedDays / $recoTotalDays) * 100)) : 0;
    $recoProjectTypes = [
        'marque' => 'Une marque',
        'entreprise' => 'Une entreprise',
        'activite' => 'Une activité professionnelle',
        'projet_personnel' => 'Un projet personnel',
    ];
@endphp

@if($enrollment)
<div class="reco-form-card">
    <h2><i class="fas fa-rocket me-2"></i>Ma RECO 60 est en cours</h2>
    <p style="color:#cbd5e1;">Vous avez déclaré le démarrage de votre période pratique. Voici le suivi de votre projet.</p>

    <div class="reco-summary-grid">
        <div class="reco-summary-item">
            <div class="label">Marque / Projet</div>
            <div class="value">{{ $enrollment->brand_name }}</div>
        </div>
        <div class="reco-summary-item">
            <div class="label">Type</div>
            <div class="value">{{ $recoProjectTypes[$enrollment->project_type] ?? $enrollment->project_type }}</div>
        </div>
        <div class="reco-summary-item">
            <div class="label">Début</div>
            <div class="value">{{ $recoStart->format('d/m/Y') }}</div>
        </div>
        <div class="reco-summary-item">
            <div class="label">Fin prévue</div>
            <div class="value">{{ $recoEnd->format('d/m/Y') }}</div>
        </div>
        <div class="reco-summary-item">
            <div class="label">Progression</div>
            <div class="value">
                {{ $recoElapsedDays }} / {{ $recoTotalDays }} jours
                @if($recoRemainingDays > 0)
                    <span style="font-weight:400;color:#94a3b8;">({{ $recoRemainingDays }} j restants)</span>
                @else
                    <span class="badge bg-success">Terminée</span>
                @endif
            </div>
            <div class="reco-progress"><div style="width: {{ $recoProgress }}%"></div></div>
        </div>
    </div>

    <button class="reco-submit-btn" type="button" data-bs-toggle="collapse" data-bs-target="#recoFormCollapse">
        <i class="fas fa-edit me-2"></i>Modifier ma déclaration
    </button>
</div>
@endif

<div class="reco-form-card collapse {{ $enrollment ? '' : 'show' }}" id="recoFormCollapse">
    <h2><i class="fas fa-play-circle me-2"></i>{{ $enrollment ? 'Modifier ma déclaration' : 'Déclarer le démarrage de ma RECO 60' }}</h2>
    <p style="color:#cbd5e1;">Renseignez les informations de votre projet pour officialiser le début de vos 60 jours de pratique professionnelle.</p>

    <form method="POST" action="{{ route('community-management.reco-60.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" for="brand_name">Nom de la marque <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="brand_name" name="brand_name" required
                       value="{{ old('brand_name', $enrollment->brand_name ?? '') }}"
                       placeholder="Ex : EVC, Ma Boutique, Restaurant Le Bon Goût...">
            </div>
            <div class="col-md-6">
                <label class="form-label" for="project_type">Type de projet <span class="text-danger">*</span></label>
                <select class="form-select" id="project_type" name="project_type" required>
                    @foreach($recoProjectTypes as $value => $label)
                        <option value="{{ $value }}" {{ old('project_type', $enrollment->project_type ?? 'marque') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label" for="presentation">Présentation du projet <span class="text-danger">*</span></label>
                <textarea class="form-control" id="presentation" name="presentation" rows="4" required
                          placeholder="Présentez la marque ou l'entreprise : activité, cible, présence digitale actuelle, contexte...">{{ old('presentation', $enrollment->presentation ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label" for="platforms">Plateformes / réseaux utilisés</label>
                <input type="text" class="form-control" id="platforms" name="platforms"
                       value="{{ old('platforms', $enrollment->platforms ?? '') }}"
                       placeholder="Ex : Instagram, Facebook, TikTok, LinkedIn...">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="start_date">Date de début <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="start_date" name="start_date" required
                       value="{{ old('start_date', $enrollment->start_date ?? now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-3">
                <label class="form-label" for="end_date">Date de fin prévue <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="end_date" name="end_date" required
                       value="{{ old('end_date', $enrollment->end_date ?? now()->addDays(60)->format('Y-m-d')) }}">
            </div>
            <div class="col-12">
                <label class="form-label" for="objectives">Objectifs des 60 jours</label>
                <textarea class="form-control" id="objectives" name="objectives" rows="3"
                          placeholder="Ex : +30% d'abonnés, publier 3 fois par semaine, lancer une campagne...">{{ old('objectives', $enrollment->objectives ?? '') }}</textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="reco-submit-btn">
                    <i class="fas fa-check me-2"></i>{{ $enrollment ? 'Enregistrer les modifications' : 'Démarrer ma RECO 60' }}
                </button>
            </div>
        </div>
    </form>
</div>

<div class="reco-section">
    <h2><i class="fas fa-arrow-right me-2"></i>De la formation à la réalité du terrain</h2>
    <p>Apprendre un métier ne consiste pas uniquement à maîtriser des logiciels, des outils ou des notions théoriques. L’enjeu commence véritablement lorsqu’il faut mobiliser ces connaissances pour répondre à un besoin concret, organiser son travail, respecter des objectifs et produire des résultats.</p>
    <p>C’est dans cette logique que l’École Virtuelle des Créatifs (EVC) met en place la RECO 60, une période pratique de 60 jours destinée aux étudiants arrivés au terme de leur formation en Community Management / Social Media Management (CM/SMM) et en Gestion Informatique Appliquée (GIA).</p>
    <p>La RECO 60 intervient après la phase d’apprentissage et place l’étudiant dans une dynamique proche de celle du monde professionnel.</p>
    <p>Pendant deux mois, il ne s’agit plus seulement de montrer ce que l’on connaît, mais surtout de démontrer ce que l’on est capable de faire avec les compétences acquises.</p>
</div>

<div class="reco-section">
    <h2><i class="fas fa-bullhorn me-2"></i>RECO 60 en CM/SMM : gérer un projet digital pendant 60 jours</h2>
    <p>Pour les étudiants en Community Management / Social Media Management, la RECO 60 prend la forme d’une expérience pratique autour d’un projet digital.</p>
    <p>L’étudiant peut notamment travailler sur une marque, une entreprise, une activité professionnelle ou un projet personnel.</p>
    <p>Il doit analyser la situation de départ, définir des objectifs, élaborer une stratégie, organiser son calendrier éditorial, créer et publier des contenus, animer les plateformes et suivre les performances obtenues.</p>
    <p>Cette période permet ainsi de reproduire plusieurs responsabilités auxquelles un Community Manager ou un Social Media Manager peut être confronté dans une entreprise, une agence ou dans le cadre d’une activité indépendante.</p>
    <p>L’étudiant passe alors de la connaissance des méthodes à leur application dans la durée, avec suffisamment de temps pour observer les résultats de ses actions, ajuster sa stratégie et mesurer sa progression.</p>
</div>

<div class="reco-section">
    <h2><i class="fas fa-laptop-code me-2"></i>RECO 60 en Gestion Informatique Appliquée : mettre les outils numériques au service d’un besoin professionnel</h2>
    <p>Pour les étudiants en Gestion Informatique Appliquée, la RECO 60 repose également sur une logique de mise en situation professionnelle.</p>
    <p>L’objectif est de démontrer la capacité de l’étudiant à utiliser les outils bureautiques, numériques et collaboratifs pour répondre à des besoins concrets.</p>
    <p>Durant cette période, l’étudiant peut être amené à produire et organiser des documents professionnels, créer des tableaux de suivi et de gestion sur Excel, préparer des présentations, structurer des fichiers et dossiers numériques, créer des formulaires ou encore utiliser les différents services Google dans le cadre d’une organisation professionnelle.</p>
    <p>Il ne s’agit donc plus simplement de savoir utiliser Word, Excel, PowerPoint, Canva ou les services Google, mais de savoir choisir et utiliser le bon outil pour répondre efficacement à une problématique.</p>
    <p>La RECO 60 permet ainsi d’évaluer la capacité de l’étudiant à transformer ses connaissances techniques en solutions utiles dans un environnement de travail.</p>
</div>

<div class="reco-section">
    <h2><i class="fas fa-chart-line me-2"></i>60 jours orientés vers des résultats concrets</h2>
    <p>L’une des particularités de la RECO 60 réside dans son approche orientée vers les résultats.</p>
    <p>Sur une période de deux mois, l’étudiant dispose de suffisamment de temps pour mettre en œuvre ses compétences, observer les premiers résultats, identifier les difficultés, apporter des corrections et mesurer l’évolution de son travail.</p>
    <p>Ces résultats prennent naturellement des formes différentes selon la filière.</p>
    <p>En CM/SMM, ils peuvent notamment être observés à travers la régularité des publications, la portée, les interactions, l’évolution de la communauté, les vues ou les performances des contenus.</p>
    <p>En Gestion Informatique Appliquée, ils peuvent se traduire par la qualité et l’organisation des documents produits, la pertinence des tableaux de suivi, l’automatisation ou la simplification de certaines tâches, la bonne utilisation des outils collaboratifs ou encore la capacité à organiser efficacement l’information.</p>
    <p>L’objectif n’est donc pas d’appliquer le même modèle d’évaluation à tous les étudiants.</p>
    <p>Il s’agit d’observer, dans chaque domaine, la capacité à comprendre un besoin, choisir une méthode, utiliser les outils appropriés et produire un résultat exploitable.</p>
</div>

<div class="reco-section">
    <h2><i class="fas fa-folder-open me-2"></i>Construire une première expérience professionnelle documentée</h2>
    <p>Pour EVC, l’enjeu de la RECO 60 dépasse l’évaluation académique.</p>
    <p>Cette période doit permettre à l’étudiant de sortir de sa formation avec une expérience concrète et documentée qu’il pourra valoriser professionnellement.</p>
    <p>Pour un étudiant en CM/SMM, il peut s’agir de publications, calendriers éditoriaux, statistiques, rapports de performances, créations de contenus ou résultats obtenus sur les plateformes.</p>
    <p>Pour un étudiant en Gestion Informatique Appliquée, cette expérience peut être documentée à travers des tableaux de bord, documents professionnels, présentations, formulaires, systèmes d’organisation de fichiers ou autres productions réalisées pendant les 60 jours.</p>
    <p>Ces éléments deviennent progressivement des preuves de compétences que l’étudiant peut valoriser dans son CV, son portfolio ou lors d’un entretien.</p>
</div>

<div class="reco-section">
    <h2><i class="fas fa-file-alt me-2"></i>Un rapport d’activité pour documenter les 60 jours d’expérience</h2>
    <p>Au terme des 60 jours, chaque étudiant doit produire un rapport d’activité RECO 60 retraçant l’ensemble de son expérience.</p>
    <p>Le document doit notamment présenter le projet ou la problématique traitée, les objectifs définis, les actions réalisées, les outils utilisés, les résultats obtenus, les difficultés rencontrées et les solutions apportées.</p>
    <p>L’étudiant doit également être capable de prendre du recul sur son expérience afin d’identifier ce qui a fonctionné, les erreurs commises et les améliorations possibles.</p>
    <p>Cette étape est importante car, dans le monde professionnel, savoir exécuter une mission ne suffit pas. Il faut également pouvoir documenter son travail, analyser ses résultats et rendre compte de ses actions.</p>
</div>

<div class="reco-section">
    <h2><i class="fas fa-award me-2"></i>La RECO 60 intégrée au processus de certification EVC</h2>
    <p>La RECO 60 ne constitue pas une activité facultative ajoutée après les cours.</p>
    <p>Elle s’inscrit dans le processus d’évaluation et de certification de l’EVC pour les étudiants concernés.</p>
    <p>Elle permet notamment d’apprécier l’autonomie, la méthodologie, l’organisation, la régularité, la maîtrise des outils et la capacité de l’étudiant à appliquer ses compétences dans une situation pratique sur une période suffisamment longue.</p>
    <p>L’objectif est de renforcer la place de la démonstration des compétences dans le parcours de certification.</p>
    <p>Car au-delà d’un diplôme ou d’une certification, les entreprises attendent également d’un candidat qu’il puisse expliquer ce qu’il sait faire et, surtout, en apporter la preuve.</p>
</div>

<div class="reco-section">
    <h2><i class="fas fa-lightbulb me-2"></i>Former des étudiants capables de faire, pas seulement de savoir</h2>
    <p>Avec la RECO 60, l’École Virtuelle des Créatifs poursuit son approche pédagogique fondée sur la pratique et la confrontation progressive aux réalités professionnelles.</p>
    <p>La formation permet d’apprendre. Les exercices permettent de s’entraîner. Les projets permettent d’expérimenter. La RECO 60 permet de démontrer.</p>
    <p>Pendant 60 jours, l’étudiant devient responsable de son projet, de son organisation et de ses résultats.</p>
    <p>Pour les étudiants en Community Management / Social Media Management, il s’agit de démontrer leur capacité à gérer et développer une présence digitale dans la durée.</p>
    <p>Pour ceux en Gestion Informatique Appliquée, il s’agit de démontrer leur capacité à mobiliser efficacement les outils numériques et bureautiques pour répondre à des besoins professionnels.</p>
    <p>Dans les deux cas, la philosophie reste la même : transformer la fin de la formation en début d’expérience professionnelle.</p>
    <p>Avec la RECO 60, les 60 derniers jours ne constituent donc pas une simple étape supplémentaire du parcours : ils deviennent les premiers jours où l’étudiant doit véritablement démontrer, dans la durée, ce qu’il sait faire.</p>
</div>
@endsection
