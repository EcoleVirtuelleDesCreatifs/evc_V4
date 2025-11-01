@extends('layouts.app')

@section('title', 'Mentions Légales - École Virtuelle des Créatifs')
@section('description', 'Mentions légales de l\'École Virtuelle des Créatifs')

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
        background: rgba(255, 153, 0, 0.1);
        border-left: 4px solid #FF9900;
        padding: 20px;
        border-radius: 8px;
        margin: 24px 0;
    }

    .legal-info-box p {
        margin: 0;
        color: #e2e8f0;
    }

    .legal-info-box strong {
        color: #FF9900;
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
    }
</style>
@endpush

@section('content')
<div class="legal-page">
    <div class="legal-container">
        <div class="legal-header">
            <h1>Mentions Légales</h1>
            <p>Dernière mise à jour : {{ date('d/m/Y') }}</p>
        </div>

        <div class="legal-content">
            <div class="legal-section">
                <h2>1. Éditeur du site</h2>
                <div class="legal-info-box">
                    <p><strong>Raison sociale :</strong> École Virtuelle des Créatifs (EVC)</p>
                    <p><strong>Forme juridique :</strong> Société à Responsabilité Limitée (SARL)</p>
                    <p><strong>Capital social :</strong> 1.000.000 FCFA</p>
                    <p><strong>Numéro d'enregistrement :</strong> CI-ABJ-03-2024-B12-00430</p>
                    <p><strong>Siège social :</strong> Abidjan, Côte d'Ivoire</p>
                    <p><strong>Email :</strong> info@ecolevirtuelledescreatifs.com</p>
                    <p><strong>Téléphone :</strong> +225 07 47 25 95 07</p>
                    <p><strong>Site web :</strong> www.ecolevirtuelledescreatifs.com</p>
                    <p><strong>Directeur de la publication :</strong> Monsieur Bilé Bossombra</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>2. Hébergement</h2>
                <p>Le site est hébergé par :</p>
                <div class="legal-info-box">
                    <p><strong>Hébergeur :</strong> LWS (Ligne Web Services)</p>
                    <p><strong>Adresse :</strong> 10 rue Penthièvre, 75008 Paris, France</p>
                    <p><strong>Site web :</strong> www.lws.fr</p>
                </div>
            </div>

            <div class="legal-section">
                <h2>3. Propriété intellectuelle</h2>
                <p>L'ensemble du contenu de ce site (textes, images, vidéos, logos, graphismes, etc.) est la propriété exclusive de l'École Virtuelle des Créatifs, sauf mention contraire.</p>
                <p>Toute reproduction, distribution, modification, adaptation, retransmission ou publication de ces différents éléments est strictement interdite sans l'accord écrit préalable de l'EVC.</p>
                <h3>Protection des contenus</h3>
                <ul>
                    <li>Les marques, logos et signes distinctifs sont des marques déposées</li>
                    <li>Les cours et supports pédagogiques sont protégés par le droit d'auteur</li>
                    <li>Les projets d'étudiants restent la propriété de leurs auteurs</li>
                    <li>Toute utilisation non autorisée constitue une contrefaçon</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>4. Données personnelles</h2>
                <p>Conformément à la loi ivoirienne sur la protection des données personnelles, vous disposez d'un droit d'accès, de modification, de rectification et de suppression des données vous concernant.</p>
                <p>Pour exercer ce droit, vous pouvez nous contacter à l'adresse : <strong>info@ecolevirtuelledescreatifs.com</strong></p>
                <p>Pour plus d'informations sur la gestion de vos données personnelles, consultez notre <a href="{{ route('politique-confidentialite') }}" style="color: #FF9900; text-decoration: underline;">Politique de Confidentialité</a>.</p>
            </div>

            <div class="legal-section">
                <h2>5. Cookies</h2>
                <p>Le site utilise des cookies pour améliorer l'expérience utilisateur et analyser le trafic. En naviguant sur ce site, vous acceptez l'utilisation de cookies.</p>
                <h3>Types de cookies utilisés</h3>
                <ul>
                    <li>Cookies techniques : nécessaires au fonctionnement du site</li>
                    <li>Cookies de session : pour maintenir votre connexion</li>
                    <li>Cookies analytiques : pour mesurer l'audience du site</li>
                </ul>
                <p>Vous pouvez désactiver les cookies dans les paramètres de votre navigateur.</p>
            </div>

            <div class="legal-section">
                <h2>6. Responsabilité</h2>
                <p>L'École Virtuelle des Créatifs s'efforce d'assurer l'exactitude et la mise à jour des informations diffusées sur ce site. Toutefois, elle ne peut garantir l'exactitude, la précision ou l'exhaustivité des informations mises à disposition.</p>
                <h3>Limitations</h3>
                <ul>
                    <li>L'EVC ne peut être tenue responsable des erreurs ou omissions</li>
                    <li>Les informations sont fournies à titre indicatif</li>
                    <li>L'EVC se réserve le droit de modifier le contenu sans préavis</li>
                    <li>Les liens externes sont fournis à titre informatif uniquement</li>
                </ul>
            </div>

            <div class="legal-section">
                <h2>7. Liens hypertextes</h2>
                <p>Le site peut contenir des liens vers d'autres sites internet. L'EVC n'exerce aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.</p>
                <p>La création de liens hypertextes vers le site de l'EVC nécessite une autorisation préalable écrite.</p>
            </div>

            <div class="legal-section">
                <h2>8. Droit applicable</h2>
                <p>Les présentes mentions légales sont régies par le droit ivoirien. Tout litige relatif à l'utilisation du site sera soumis à la compétence exclusive des tribunaux d'Abidjan, Côte d'Ivoire.</p>
            </div>

            <div class="legal-section">
                <h2>9. Contact</h2>
                <p>Pour toute question concernant ces mentions légales, vous pouvez nous contacter :</p>
                <div class="legal-info-box">
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
