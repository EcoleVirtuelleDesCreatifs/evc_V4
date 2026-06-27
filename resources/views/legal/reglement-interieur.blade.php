@extends('layouts.app')

@section('title', 'Règlement Intérieur - École Virtuelle des Créatifs')
@section('description', 'Règlement intérieur et engagement de l\'étudiant inscrit à l\'École Virtuelle des Créatifs')

@push('styles')
<style>
    .legal-page {
        min-height: 100vh;
        background: #0a0a0a;
        color: #f1f5f9;
        padding: 160px 20px 80px;
    }

    .legal-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .legal-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .legal-header h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 16px;
        background: linear-gradient(135deg, #FF9900 0%, #F97316 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .legal-header p {
        color: #94a3b8;
        font-size: 16px;
    }

    .legal-content {
        background: #1e293b;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .legal-section {
        margin-bottom: 40px;
    }

    .legal-section:last-child {
        margin-bottom: 0;
    }

    .legal-section h2 {
        font-size: 24px;
        font-weight: 600;
        color: #FF9900;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .legal-section h2::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(135deg, #FF9900 0%, #F97316 100%);
        border-radius: 2px;
    }

    .legal-section h3 {
        font-size: 18px;
        font-weight: 600;
        color: #e2e8f0;
        margin: 24px 0 12px;
    }

    .legal-section p {
        color: #cbd5e1;
        line-height: 1.8;
        margin-bottom: 16px;
    }

    .legal-section ul {
        list-style: none;
        padding-left: 0;
        margin: 16px 0;
    }

    .legal-section li {
        color: #cbd5e1;
        padding-left: 24px;
        position: relative;
        margin-bottom: 12px;
        line-height: 1.6;
    }

    .legal-section li::before {
        content: '→';
        position: absolute;
        left: 0;
        color: #FF9900;
        font-weight: bold;
    }

    .legal-info-box {
        background: rgba(255, 153, 0, 0.08);
        border-left: 4px solid #FF9900;
        padding: 20px;
        border-radius: 8px;
        margin: 24px 0;
    }

    .legal-info-box p {
        margin: 0;
        color: #e2e8f0;
    }

    @media (max-width: 768px) {
        .legal-header h1 {
            font-size: 32px;
        }

        .legal-content {
            padding: 24px;
        }

        .legal-section h2 {
            font-size: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="legal-page">
    <div class="legal-container">
        <div class="legal-header">
            <h1>Règlement Intérieur</h1>
            <p>Engagement de l'étudiant inscrit à l'EVC (cadre de formation, discipline et certification).</p>
        </div>

        <div class="legal-content">
            <div class="legal-section">
                <h2>1. Objet et champ d'application</h2>
                <p>Le présent règlement intérieur fixe les règles de fonctionnement, de discipline et d'engagement applicables à tout étudiant inscrit à l'École Virtuelle des Créatifs (EVC). Il constitue un cadre de référence assimilable à un engagement mutuel entre l'EVC et l'étudiant, visant à garantir une formation efficace, un suivi sérieux et un climat de respect.</p>
                <div class="legal-info-box">
                    <p><strong>Important :</strong> L'inscription à une formation EVC implique l'acceptation pleine et entière de ce règlement.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>2. Engagements de l'EVC</h2>
                <p>L'EVC s'engage à :</p>
                <ul>
                    <li>Mettre à disposition un programme de formation structuré, des supports pédagogiques et un encadrement adapté.</li>
                    <li>Assurer un suivi pédagogique selon les modalités prévues (cours, TP, projets, évaluations, feedbacks).</li>
                    <li>Informer l'étudiant des échéances importantes (dates limites, validations, examens, procédures).</li>
                    <li>Évaluer les travaux et la progression selon des critères transparents.</li>
                    <li>Délivrer un certificat lorsque les critères de certification sont remplis.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>3. Engagements de l'étudiant</h2>
                <p>L'étudiant s'engage à adopter un comportement compatible avec l'exigence d'une formation professionnelle. À ce titre, l'étudiant s'engage à :</p>
                <ul>
                    <li><strong>Assiduité :</strong> participer aux cours/ateliers, et respecter le planning de formation.</li>
                    <li><strong>Discipline :</strong> respecter les règles, le cadre, les formateurs, l'équipe EVC et les autres étudiants.</li>
                    <li><strong>Réactivité :</strong> répondre dans un délai raisonnable aux demandes pédagogiques (messages, devoirs, corrections, convocations).</li>
                    <li><strong>Qualité de travail :</strong> fournir des travaux personnels, sérieux et conformes aux consignes.</li>
                    <li><strong>Respect des délais :</strong> soumettre les TP/projets avant les dates limites.</li>
                    <li><strong>Éthique :</strong> éviter tout plagiat, fraude, triche ou usurpation d'identité.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>4. Conditions d'obtention du certificat</h2>
                <p>Le certificat EVC n'est pas automatique. Il est délivré lorsque l'étudiant remplit l'ensemble des conditions ci-dessous :</p>
                <ul>
                    <li>Assiduité et participation active tout au long du parcours.</li>
                    <li>Réalisation et validation des travaux pratiques (TP) requis.</li>
                    <li>Réalisation et validation des projets requis.</li>
                    <li>Respect des règles de discipline et du comportement attendu.</li>
                    <li>Respect des procédures administratives et pédagogiques (documents demandés, échéances, etc.).</li>
                </ul>
                <p>En cas de manquement grave ou répété, l'EVC se réserve le droit de refuser la délivrance du certificat, même si l'étudiant a suivi une partie des cours.</p>
            </div>

            <div class="legal-section" id="critere-certification">
                <h2>5. Critères d'éligibilité à la certification EVC</h2>
                @include('legal.partials.criteres-certification')
            </div>

            <div class="legal-section">
                <h2>6. Inactivité, indisponibilité et exclusion</h2>
                <p>Pour garantir l'efficacité de la formation, l'étudiant doit rester actif et disponible. Sauf cas de force majeure signalé et justifié, toute indisponibilité prolongée compromet la progression.</p>
                <div class="legal-info-box">
                    <p><strong>Clause d'inactivité :</strong> si l'étudiant ne se rend pas disponible, ne répond pas et ne participe pas pendant une durée continue de <strong>2 mois</strong>, l'EVC peut procéder à une <strong>exclusion</strong> du programme.</p>
                </div>
                <p>Avant toute exclusion, l'EVC peut :</p>
                <ul>
                    <li>relancer l'étudiant (message / appel / email) ;</li>
                    <li>demander une justification ;</li>
                    <li>proposer une solution de rattrapage si la situation le permet.</li>
                </ul>
                <p>L'exclusion peut entraîner l'arrêt du suivi, l'arrêt des évaluations, et l'impossibilité d'obtenir le certificat.</p>
            </div>

            <div class="legal-section">
                <h2>7. Plagiat, fraude et intégrité académique</h2>
                <p>Tout acte de fraude (plagiat, utilisation d'un travail d'autrui, duplication, falsification, usurpation) est interdit.</p>
                <ul>
                    <li>Un travail plagié peut être automatiquement invalidé.</li>
                    <li>En cas de récidive ou de fraude grave, l'EVC peut prononcer une sanction allant jusqu'à l'exclusion.</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>8. Respect, conduite et communication</h2>
                <p>La communication avec les formateurs et l'équipe EVC doit rester professionnelle et respectueuse.</p>
                <ul>
                    <li>Interdiction des propos insultants, menaçants ou discriminatoires.</li>
                    <li>Interdiction du harcèlement ou des comportements perturbateurs.</li>
                    <li>Respect des canaux officiels et des horaires raisonnables de communication.</li>
                </ul>
                <p>L'EVC peut limiter l'accès aux groupes/espaces de discussion en cas d'abus.</p>
            </div>

            <div class="legal-section">
                <h2>9. Organisation pédagogique (TP, projets, évaluations)</h2>
                <p>Les TP et projets sont des éléments essentiels de la formation. L'étudiant doit :</p>
                <ul>
                    <li>respecter les consignes pédagogiques et les formats demandés ;</li>
                    <li>soumettre les travaux dans les délais ;</li>
                    <li>accepter les corrections et itérations ;</li>
                    <li>conserver les fichiers sources et livrables, si demandés.</li>
                </ul>
                <p>Le statut de validation des travaux dépend de la qualité attendue et des critères de l'EVC.</p>
            </div>

            <div class="legal-section">
                <h2>10. Suspension et réintégration</h2>
                <p>Dans certains cas, l'EVC peut proposer une suspension temporaire (ex: raisons médicales, contraintes majeures) si l'étudiant en fait la demande et fournit un justificatif.</p>
                <p>La réintégration peut être soumise à :</p>
                <ul>
                    <li>une mise à niveau (rattrapage) ;</li>
                    <li>un nouveau planning ;</li>
                    <li>des conditions particulières de suivi.</li>
                </ul>
            </div>

            <div class="legal-section" id="acces-post-formation">
                <h2>11. Accès au compte MY EVC après la fin de formation</h2>
                <p>À l'issue de sa formation, l'étudiant perd l'accès aux espaces de travail collaboratifs de l'EVC. Les droits d'accès suivants s'appliquent :</p>
                <ul>
                    <li><strong>Accès aux classes virtuelles de travail :</strong> l'étudiant n'aura plus accès aux différentes classes virtuelles de travail actives de l'EVC une fois sa formation terminée.</li>
                    <li><strong>Accès au compte MY EVC :</strong> l'étudiant conserve un accès à son compte MY EVC, mais ce dernier ne sera plus mis à jour. Il s'agit d'un accès en lecture seule.</li>
                    <li><strong>Consultation des anciens supports :</strong> l'étudiant pourra consulter et télécharger ses anciens supports de formation archivés dans son espace personnel.</li>
                    <li><strong>Aucune publication possible :</strong> l'étudiant ne pourra plus publier de contenu, soumettre de travaux, ni interagir dans les espaces de classe virtuelle.</li>
                </ul>
                <div class="legal-info-box">
                    <p><strong>Résumé :</strong> après la fin de formation, le compte MY EVC passe en mode <strong>lecture seule</strong>. L'accès aux classes virtuelles actives est révoqué, mais les anciens supports restent consultables.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>12. Modification du règlement</h2>
                <p>L'EVC peut faire évoluer le présent règlement pour s'adapter à l'organisation interne, à la pédagogie et à la réglementation. Toute mise à jour est applicable dès sa publication sur le site.</p>
            </div>

            <div class="legal-section">
                <h2>13. Contact</h2>
                <p>Pour toute question concernant ce règlement intérieur, vous pouvez contacter l'EVC :</p>
                <div class="legal-info-box">
                    <p><strong>Email :</strong> info@ecolevirtuelledescreatifs.com</p>
                    <p><strong>Téléphone :</strong> +225 07 47 25 95 07</p>
                    <p><strong>Adresse :</strong> Abidjan, Côte d'Ivoire</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
