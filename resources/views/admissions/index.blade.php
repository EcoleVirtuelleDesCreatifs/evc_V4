@extends('layouts.app')

@section('title', 'Admissions - École Virtuelle des Créatifs')
@section('description', 'Toutes les informations pour intégrer l\'École Virtuelle des Créatifs : conditions d\'admission, calendrier, tarifs, modalités de paiement et documents requis.')
@section('keywords', 'admissions evc, inscription école design abidjan, conditions admission, tarifs formation, documents inscription')

@push('styles')
<style>
    .admissions-wrapper {
        --primary: #ff6b35;
        --primary-dark: #e55a2b;
        --primary-light: #ff8c5a;
        --accent: #00d4ff;
        --bg-dark: #0a0e27;
        --bg-card: #151a3d;
        --text-primary: #ffffff;
        --text-secondary: #a0aec0;
        --border: #2d3748;
        --success: #00d4aa;
    }

    .admissions-container {
        background: linear-gradient(135deg, var(--bg-dark) 0%, #1a1f4e 50%, #0d1333 100%);
        min-height: 100vh;
        padding: 140px 20px 80px;
        position: relative;
        overflow-x: hidden;
    }

    .admissions-hero {
        text-align: center;
        margin-top: 60px;
        margin-bottom: 40px;
        position: relative;
        z-index: 1;
    }

    .admissions-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 28px;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.25) 0%, rgba(255, 107, 53, 0.15) 100%);
        border: 2px solid rgba(255, 107, 53, 0.4);
        border-radius: 50px;
        color: var(--primary);
        font-size: 0.875rem;
        font-weight: 800;
        margin-bottom: 28px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 16px rgba(255, 107, 53, 0.2);
    }

    .admissions-title {
        font-size: clamp(2.5rem, 6vw, 4rem);
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 50%, var(--primary) 100%);
        background-size: 300% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 20px;
        letter-spacing: -0.03em;
        line-height: 1.1;
    }

    .admissions-subtitle {
        font-size: clamp(1.125rem, 2.5vw, 1.5rem);
        color: var(--text-secondary);
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.8;
        font-weight: 400;
    }

    .admissions-content {
        max-width: 860px;
        margin: 0 auto;
    }

    .admissions-section {
        background: rgba(21, 26, 61, 0.8);
        border: 1px solid rgba(255, 107, 53, 0.2);
        border-radius: 24px;
        padding: 48px;
        margin-bottom: 40px;
        backdrop-filter: blur(30px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .admissions-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 50%, var(--primary) 100%);
        background-size: 200% auto;
        animation: gradient-shift 3s linear infinite;
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% center; }
        50% { background-position: 100% center; }
    }

    .admissions-section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .admissions-section-header h2 {
        color: var(--primary);
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .admissions-section-header p {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 500;
    }

    .admissions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 0;
    }

    .admissions-item {
        background: linear-gradient(135deg, rgba(10, 14, 39, 0.6) 0%, rgba(21, 26, 61, 0.4) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 28px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .admissions-item:hover {
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(0, 212, 255, 0.05) 100%);
        border-color: rgba(255, 107, 53, 0.3);
        transform: translateY(-4px);
    }

    .admissions-item-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #ffffff;
        margin-bottom: 20px;
        box-shadow: 0 8px 32px rgba(255, 107, 53, 0.4);
    }

    .admissions-item h3 {
        color: var(--text-primary);
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 12px;
        letter-spacing: -0.01em;
    }

    .admissions-item p {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.7;
        margin: 0;
    }

    .admissions-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .admissions-list li {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        background: linear-gradient(135deg, rgba(10, 14, 39, 0.6) 0%, rgba(21, 26, 61, 0.4) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        margin-bottom: 16px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .admissions-list li:hover {
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(0, 212, 255, 0.05) 100%);
        border-color: rgba(255, 107, 53, 0.3);
        transform: translateX(8px);
    }

    .admissions-list li:last-child {
        margin-bottom: 0;
    }

    .admissions-list-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #ffffff;
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
    }

    .admissions-list-content h4 {
        color: var(--text-primary);
        font-size: 1.125rem;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.01em;
    }

    .admissions-list-content p {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        line-height: 1.6;
        margin: 0;
    }

    .admissions-price {
        text-align: center;
        padding: 32px;
        background: linear-gradient(135deg, rgba(10, 14, 39, 0.6) 0%, rgba(21, 26, 61, 0.4) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        margin-bottom: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .admissions-price:hover {
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(0, 212, 255, 0.05) 100%);
        border-color: rgba(255, 107, 53, 0.3);
        transform: translateY(-4px);
    }

    .admissions-price-amount {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .admissions-price-label {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 500;
    }

    .admissions-cta {
        text-align: center;
        margin-top: 40px;
        padding-top: 32px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .admissions-button {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 20px 48px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 20px;
        color: #ffffff;
        font-size: 1.125rem;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 12px 40px rgba(255, 107, 53, 0.5);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        text-decoration: none;
    }

    .admissions-button:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 60px rgba(255, 107, 53, 0.6);
    }

    /* FAQ */
    .admissions-faq-item {
        background: linear-gradient(135deg, rgba(10, 14, 39, 0.6) 0%, rgba(21, 26, 61, 0.4) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        margin-bottom: 16px;
        overflow: hidden;
    }

    .admissions-faq-question {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .admissions-faq-question:hover {
        background: rgba(255, 107, 53, 0.05);
    }

    .admissions-faq-question h4 {
        color: var(--text-primary);
        font-size: 1.0625rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.01em;
    }

    .admissions-faq-answer {
        padding: 0 24px 20px;
        color: var(--text-secondary);
        font-size: 0.9375rem;
        line-height: 1.7;
        display: none;
    }

    .admissions-faq-item.active .admissions-faq-answer {
        display: block;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admissions-grid {
            grid-template-columns: 1fr;
        }

        .admissions-section {
            padding: 32px;
        }
    }
</style>
@endpush

@section('content')
<div class="admissions-wrapper">
    <div class="admissions-container">
        <div class="container">
            <!-- Hero Section -->
            <div class="admissions-hero">
                <div class="admissions-badge">
                    <i class="fas fa-graduation-cap"></i>
                    Rejoignez-nous
                </div>
                <h1 class="admissions-title">Admissions</h1>
                <p class="admissions-subtitle">Toutes les informations pour intégrer l'École Virtuelle des Créatifs et lancer votre carrière créative.</p>
            </div>

            <div class="admissions-content">
                <!-- Conditions d'admission -->
                <div class="admissions-section">
                    <div class="admissions-section-header">
                        <h2>Conditions d'admission</h2>
                        <p>Les critères pour rejoindre notre école</p>
                    </div>
                    <ul class="admissions-list">
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Niveau d'étude minimum</h4>
                                <p>Avoir au moins le niveau de la classe de Terminale ou équivalent</p>
                            </div>
                        </li>
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-laptop"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Équipements requis</h4>
                                <p>Disposer d'un ordinateur et d'une connexion internet stable</p>
                            </div>
                        </li>
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-passport"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Documents obligatoires</h4>
                                <p>Photo d'identité, copie de diplôme ou dernier bulletin, pièce d'identité</p>
                            </div>
                        </li>
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-heart"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Motivation</h4>
                                <p>Passion pour le design et volonté d'apprendre</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Calendrier des rentrées -->
                <div class="admissions-section">
                    <div class="admissions-section-header">
                        <h2>Calendrier des rentrées</h2>
                        <p>Les dates de début de nos formations</p>
                    </div>
                    <div class="admissions-grid">
                        <div class="admissions-price">
                            <div class="admissions-price-amount">Janvier</div>
                            <div class="admissions-price-label">Rentrée Janvier 2025</div>
                        </div>
                        <div class="admissions-price">
                            <div class="admissions-price-amount">Mai</div>
                            <div class="admissions-price-label">Rentrée Mai 2025</div>
                        </div>
                        <div class="admissions-price">
                            <div class="admissions-price-amount">Septembre</div>
                            <div class="admissions-price-label">Rentrée Septembre 2025</div>
                        </div>
                        <div class="admissions-price">
                            <div class="admissions-price-amount">Octobre</div>
                            <div class="admissions-price-label">Rentrée Octobre 2025</div>
                        </div>
                    </div>
                </div>

                <!-- Tarifs -->
                <div class="admissions-section">
                    <div class="admissions-section-header">
                        <h2>Tarifs</h2>
                        <p>Investissement dans votre avenir créatif</p>
                    </div>
                    <div class="admissions-grid">
                        <div class="admissions-item">
                            <div class="admissions-item-icon">
                                <i class="fas fa-palette"></i>
                            </div>
                            <h3>Design Graphique</h3>
                            <p>185.000 FCFA</p>
                        </div>
                        <div class="admissions-item">
                            <div class="admissions-item-icon">
                                <i class="fas fa-share-alt"></i>
                            </div>
                            <h3>Community Management</h3>
                            <p>165.000 FCFA</p>
                        </div>
                        <div class="admissions-item">
                            <div class="admissions-item-icon">
                                <i class="fas fa-database"></i>
                            </div>
                            <h3>Gestion Informatique</h3>
                            <p>265.000 FCFA</p>
                        </div>
                        <div class="admissions-item">
                            <div class="admissions-item-icon">
                                <i class="fas fa-brain"></i>
                            </div>
                            <h3>Intelligence Artificielle</h3>
                            <p>Sur demande</p>
                        </div>
                    </div>
                </div>

                <!-- Modalités de paiement -->
                <div class="admissions-section">
                    <div class="admissions-section-header">
                        <h2>Modalités de paiement</h2>
                        <p>Options de paiement flexibles</p>
                    </div>
                    <ul class="admissions-list">
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Paiement en 1 fois</h4>
                                <p>Paiement complet à l'inscription (avantage de 5%)</p>
                            </div>
                        </li>
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Paiement en 2 fois</h4>
                                <p>50% à l'inscription, 50% à mi-formation</p>
                            </div>
                        </li>
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Paiement en 3 fois</h4>
                                <p>Échelonnement mensuel disponible sur demande</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Documents à fournir -->
                <div class="admissions-section">
                    <div class="admissions-section-header">
                        <h2>Documents à fournir</h2>
                        <p>Les pièces requises pour votre dossier</p>
                    </div>
                    <ul class="admissions-list">
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Photo d'identité</h4>
                                <p>Photo récente de type passeport (format JPG ou PNG)</p>
                            </div>
                        </li>
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-file-alt"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Diplôme ou bulletin</h4>
                                <p>Copie du dernier diplôme ou bulletin scolaire</p>
                            </div>
                        </li>
                        <li>
                            <div class="admissions-list-icon">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div class="admissions-list-content">
                                <h4>Pièce d'identité</h4>
                                <p>CNI, passeport ou carte d'étudiant</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- FAQ -->
                <div class="admissions-section">
                    <div class="admissions-section-header">
                        <h2>FAQ</h2>
                        <p>Questions fréquentes</p>
                    </div>
                    <div class="admissions-faq-item">
                        <div class="admissions-faq-question" onclick="this.parentElement.classList.toggle('active')">
                            <h4>Quelle est la durée des formations ?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="admissions-faq-answer">
                            <p>Nos formations durent généralement 6 à 9 mois selon le programme choisi, avec des cours en ligne et des projets pratiques.</p>
                        </div>
                    </div>
                    <div class="admissions-faq-item">
                        <div class="admissions-faq-question" onclick="this.parentElement.classList.toggle('active')">
                            <h4>Les formations sont-elles certifiantes ?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="admissions-faq-answer">
                            <p>Oui, toutes nos formations sont certifiantes et reconnues. Vous obtenez une certification Adobe à l'issue de votre parcours.</p>
                        </div>
                    </div>
                    <div class="admissions-faq-item">
                        <div class="admissions-faq-question" onclick="this.parentElement.classList.toggle('active')">
                            <h4>Puis-je travailler pendant la formation ?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="admissions-faq-answer">
                            <p>Absolument ! Nos formations sont conçues pour être compatibles avec une activité professionnelle. Les cours sont disponibles en différé et les horaires sont flexibles.</p>
                        </div>
                    </div>
                    <div class="admissions-faq-item">
                        <div class="admissions-faq-question" onclick="this.parentElement.classList.toggle('active')">
                            <h4>Y a-t-il un examen d'admission ?</h4>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="admissions-faq-answer">
                            <p>Un diagnostic d'éligibilité d'une heure est requis pour évaluer votre profil et vous orienter vers la formation la plus adaptée.</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="admissions-cta">
                    <a href="{{ route('preinscription.start') }}" class="admissions-button">
                        <i class="fas fa-edit"></i>
                        Préinscription
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
