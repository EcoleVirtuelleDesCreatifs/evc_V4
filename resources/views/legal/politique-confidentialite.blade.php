@extends('layouts.app')

@section('title', 'Politique de Confidentialité - École Virtuelle des Créatifs')
@section('description', 'Politique de confidentialité et protection des données personnelles de l\'École Virtuelle des Créatifs')

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
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
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
        color: #4fc3f7;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .legal-section h2::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
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
        color: #4fc3f7;
        font-weight: bold;
    }

    .legal-info-box {
        background: rgba(79, 195, 247, 0.1);
        border-left: 4px solid #4fc3f7;
        padding: 20px;
        border-radius: 8px;
        margin: 24px 0;
    }

    .legal-info-box p {
        margin: 0;
        color: #e2e8f0;
    }

    .legal-info-box strong {
        color: #4fc3f7;
    }

    .legal-table {
        width: 100%;
        border-collapse: collapse;
        margin: 24px 0;
        background: #0f172a;
        border-radius: 8px;
        overflow: hidden;
    }

    .legal-table th {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        color: white;
        padding: 12px;
        text-align: left;
        font-weight: 600;
    }

    .legal-table td {
        padding: 12px;
        border-bottom: 1px solid #334155;
        color: #cbd5e1;
    }

    .legal-table tr:last-child td {
        border-bottom: none;
    }

    @media (max-width: 768px) {
        .legal-page {
            padding: 140px 16px 60px;
        }

        .legal-header h1 {
            font-size: 32px;
        }

        .legal-content {
            padding: 24px;
        }

        .legal-section h2 {
            font-size: 20px;
        }

        .legal-table {
            font-size: 14px;
        }

        .legal-table th,
        .legal-table td {
            padding: 8px;
        }
    }
</style>
@endpush

@section('content')
<div class="legal-page">
    <div class="legal-container">
        <div class="legal-header">
            <h1>Politique de Confidentialité</h1>
            <p>Dernière mise à jour : {{ date('d/m/Y') }}</p>
        </div>

        <div class="legal-content">
            <div class="legal-section">
                <h2>1. Introduction</h2>
                <p>L'École Virtuelle des Créatifs (EVC) s'engage à protéger la vie privée et les données personnelles de ses utilisateurs. Cette politique de confidentialité explique comment nous collectons, utilisons, partageons et protégeons vos informations personnelles.</p>
                <div class="legal-info-box">
                    <p><strong>Important :</strong> En utilisant notre site web et nos services, vous acceptez les pratiques décrites dans cette politique de confidentialité.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>2. Données collectées</h2>
                <p>Nous collectons différents types de données personnelles selon votre interaction avec nos services :</p>
                
                <h3>2.1. Données d'inscription</h3>
                <ul>
                    <li>Nom et prénom</li>
                    <li>Adresse email</li>
                    <li>Numéro de téléphone</li>
                    <li>Date de naissance</li>
                    <li>Adresse postale</li>
                    <li>Formation choisie</li>
                </ul>

                <h3>2.2. Données académiques</h3>
                <ul>
                    <li>Parcours de formation</li>
                    <li>Résultats et évaluations</li>
                    <li>Travaux et projets réalisés</li>
                    <li>Présence et assiduité</li>
                    <li>Certificats et diplômes obtenus</li>
                </ul>

                <h3>2.3. Données de paiement</h3>
                <ul>
                    <li>Informations de facturation</li>
                    <li>Historique des paiements</li>
                    <li>Mode de paiement (les données bancaires sont traitées par des prestataires sécurisés)</li>
                </ul>

                <h3>2.4. Données de navigation</h3>
                <ul>
                    <li>Adresse IP</li>
                    <li>Type de navigateur</li>
                    <li>Pages visitées</li>
                    <li>Durée de visite</li>
                    <li>Cookies et technologies similaires</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>3. Utilisation des données</h2>
                <p>Vos données personnelles sont utilisées pour les finalités suivantes :</p>

                <table class="legal-table">
                    <thead>
                        <tr>
                            <th>Finalité</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Gestion des inscriptions</strong></td>
                            <td>Traiter votre inscription et créer votre compte étudiant</td>
                        </tr>
                        <tr>
                            <td><strong>Fourniture des services</strong></td>
                            <td>Donner accès aux cours, ressources pédagogiques et plateformes</td>
                        </tr>
                        <tr>
                            <td><strong>Suivi académique</strong></td>
                            <td>Évaluer vos progrès et délivrer des certificats</td>
                        </tr>
                        <tr>
                            <td><strong>Communication</strong></td>
                            <td>Vous informer des actualités, événements et opportunités</td>
                        </tr>
                        <tr>
                            <td><strong>Amélioration des services</strong></td>
                            <td>Analyser l'utilisation pour améliorer nos formations</td>
                        </tr>
                        <tr>
                            <td><strong>Obligations légales</strong></td>
                            <td>Respecter nos obligations réglementaires et fiscales</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="legal-section">
                <h2>4. Partage des données</h2>
                <p>Nous ne vendons jamais vos données personnelles. Nous pouvons partager vos informations uniquement dans les cas suivants :</p>
                
                <h3>4.1. Partenaires pédagogiques</h3>
                <p>Avec des formateurs et intervenants externes dans le cadre de votre formation.</p>

                <h3>4.2. Prestataires de services</h3>
                <ul>
                    <li>Hébergement web et cloud</li>
                    <li>Plateformes de paiement sécurisées</li>
                    <li>Services d'emailing</li>
                    <li>Outils d'analyse et statistiques</li>
                </ul>

                <h3>4.3. Obligations légales</h3>
                <p>En cas de demande des autorités compétentes ou pour se conformer à la loi.</p>

                <h3>4.4. Employeurs potentiels</h3>
                <p>Avec votre consentement explicite, pour faciliter votre insertion professionnelle.</p>
            </div>

            <div class="legal-section">
                <h2>5. Sécurité des données</h2>
                <p>Nous mettons en œuvre des mesures techniques et organisationnelles pour protéger vos données :</p>
                <ul>
                    <li>Chiffrement SSL/TLS pour les transmissions de données</li>
                    <li>Stockage sécurisé sur des serveurs protégés</li>
                    <li>Accès restreint aux données personnelles</li>
                    <li>Sauvegardes régulières</li>
                    <li>Surveillance et détection des intrusions</li>
                    <li>Formation du personnel à la protection des données</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>6. Vos droits</h2>
                <p>Conformément à la législation sur la protection des données, vous disposez des droits suivants :</p>

                <h3>6.1. Droit d'accès</h3>
                <p>Vous pouvez demander une copie de toutes les données personnelles que nous détenons sur vous.</p>

                <h3>6.2. Droit de rectification</h3>
                <p>Vous pouvez demander la correction de données inexactes ou incomplètes.</p>

                <h3>6.3. Droit à l'effacement</h3>
                <p>Vous pouvez demander la suppression de vos données dans certaines conditions.</p>

                <h3>6.4. Droit à la portabilité</h3>
                <p>Vous pouvez recevoir vos données dans un format structuré et couramment utilisé.</p>

                <h3>6.5. Droit d'opposition</h3>
                <p>Vous pouvez vous opposer au traitement de vos données à des fins de marketing direct.</p>

                <h3>6.6. Droit de limitation</h3>
                <p>Vous pouvez demander la limitation du traitement de vos données dans certains cas.</p>

                <div class="legal-info-box">
                    <p><strong>Pour exercer vos droits :</strong></p>
                    <p>Contactez-nous à : <strong>info@ecolevirtuelledescreatifs.com</strong></p>
                    <p>Téléphone : <strong>+225 07 47 25 95 07</strong></p>
                    <p>Nous répondrons à votre demande dans un délai de 30 jours.</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>7. Conservation des données</h2>
                <p>Nous conservons vos données personnelles pendant les durées suivantes :</p>
                <ul>
                    <li><strong>Données d'inscription :</strong> Pendant toute la durée de votre formation + 5 ans</li>
                    <li><strong>Données académiques :</strong> Pendant 10 ans après l'obtention du diplôme</li>
                    <li><strong>Données de paiement :</strong> Pendant 10 ans (obligation légale)</li>
                    <li><strong>Données de navigation :</strong> 13 mois maximum</li>
                    <li><strong>CV et candidatures :</strong> 2 ans après le dernier contact</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>8. Cookies</h2>
                <p>Notre site utilise des cookies pour améliorer votre expérience. Vous pouvez gérer vos préférences de cookies dans les paramètres de votre navigateur.</p>
                
                <h3>Types de cookies utilisés</h3>
                <ul>
                    <li><strong>Cookies essentiels :</strong> Nécessaires au fonctionnement du site</li>
                    <li><strong>Cookies de performance :</strong> Pour analyser l'utilisation du site</li>
                    <li><strong>Cookies fonctionnels :</strong> Pour mémoriser vos préférences</li>
                    <li><strong>Cookies marketing :</strong> Pour personnaliser les publicités (avec votre consentement)</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>9. Mineurs</h2>
                <p>Nos services sont destinés aux personnes âgées de 16 ans et plus. Si vous avez moins de 18 ans, vous devez obtenir le consentement de vos parents ou tuteurs légaux avant de vous inscrire.</p>
            </div>

            <div class="legal-section">
                <h2>10. Modifications</h2>
                <p>Nous nous réservons le droit de modifier cette politique de confidentialité à tout moment. Les modifications seront publiées sur cette page avec une nouvelle date de mise à jour.</p>
                <p>Nous vous encourageons à consulter régulièrement cette page pour rester informé de nos pratiques en matière de protection des données.</p>
            </div>

            <div class="legal-section">
                <h2>11. Contact</h2>
                <p>Pour toute question concernant cette politique de confidentialité ou pour exercer vos droits :</p>
                <div class="legal-info-box">
                    <p><strong>Responsable de la protection des données :</strong></p>
                    <p><strong>Raison sociale :</strong> École Virtuelle des Créatifs (EVC) - SARL</p>
                    <p><strong>Numéro d'enregistrement :</strong> CI-ABJ-03-2024-B12-00430</p>
                    <p><strong>Email :</strong> info@ecolevirtuelledescreatifs.com</p>
                    <p><strong>Téléphone :</strong> +225 07 47 25 95 07</p>
                    <p><strong>Adresse :</strong> Abidjan, Côte d'Ivoire</p>
                    <p><strong>Site web :</strong> www.ecolevirtuelledescreatifs.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
