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
</style>

<div class="reco-hero">
    <div class="reco-badge"><i class="fas fa-briefcase"></i> RECO 60</div>
    <h1>RECO 60 : 60 jours pour transformer la formation en expérience professionnelle</h1>
    <p>À l’École Virtuelle des Créatifs (EVC), la fin des cours ne marque pas automatiquement la fin du parcours de formation. Avec la RECO 60, l’établissement instaure une période de 60 jours de mise en pratique destinée aux étudiants en Community Management / Social Media Management et en Gestion Informatique Appliquée. L’objectif : transformer les compétences acquises en expérience professionnelle concrète avant la certification.</p>
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
