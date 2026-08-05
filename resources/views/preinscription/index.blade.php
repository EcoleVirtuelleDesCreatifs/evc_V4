@extends('layouts.app')

@section('title', 'Pré-inscription - École Virtuelle des Créatifs')
@section('description', 'Inscrivez-vous à l\'École Virtuelle des Créatifs et transformez votre passion en carrière')
@section('keywords', 'inscription evc, école virtuelle abidjan, formation design graphique')

@push('styles')
<style>
    .pi-wrapper {
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
        --error: #ff4757;
    }

    .pi-container {
        background: linear-gradient(135deg, var(--bg-dark) 0%, #1a1f4e 50%, #0d1333 100%);
        min-height: 100vh;
        padding: 100px 20px 60px;
        position: relative;
        overflow-x: hidden;
    }

    .pi-content-wrapper {
        max-width: 860px;
        margin: 0 auto;
    }

    .pi-container::before {
        content: '';
        position: absolute;
        top: -20%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(255, 107, 53, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        animation: float 20s ease-in-out infinite;
    }

    .pi-container::after {
        content: '';
        position: absolute;
        bottom: -20%;
        left: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(0, 212, 255, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        animation: float 25s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(5deg); }
        66% { transform: translate(-20px, 20px) rotate(-5deg); }
    }

    .pi-hero {
        text-align: center;
        margin-bottom: 40px;
        position: relative;
        z-index: 1;
    }

    .pi-badge {
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
        animation: pulse-badge 2s ease-in-out infinite;
    }

    @keyframes pulse-badge {
        0%, 100% { box-shadow: 0 4px 16px rgba(255, 107, 53, 0.2); }
        50% { box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4); }
    }

    .pi-title {
        font-size: clamp(2.25rem, 5vw, 3.5rem);
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 50%, var(--primary) 100%);
        background-size: 300% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 16px;
        letter-spacing: -0.03em;
        line-height: 1.15;
        animation: gradient-shift 5s ease infinite;
    }

    @keyframes gradient-shift {
        0%, 100% { background-position: 0% center; }
        50% { background-position: 100% center; }
    }

    .pi-subtitle {
        font-size: clamp(1rem, 2vw, 1.25rem);
        color: var(--text-secondary);
        max-width: 700px;
        margin: 0 auto 24px;
        line-height: 1.7;
        font-weight: 400;
    }

    .pi-trust-indicators {
        display: flex;
        justify-content: center;
        gap: 32px;
        margin-top: 32px;
        flex-wrap: wrap;
    }

    .pi-trust-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
        font-size: 0.9375rem;
        font-weight: 600;
    }

    .pi-trust-item i {
        color: var(--success);
        font-size: 1.125rem;
    }

    .pi-steps {
        background: rgba(21, 26, 61, 0.8);
        border: 1px solid rgba(255, 107, 53, 0.2);
        border-radius: 24px;
        padding: 48px;
        margin-bottom: 32px;
        backdrop-filter: blur(30px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
        position: relative;
        z-index: 1;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .pi-steps::before {
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

    .pi-steps:hover {
        transform: translateY(-8px);
        box-shadow: 0 30px 80px rgba(255, 107, 53, 0.2), 0 0 0 1px rgba(255, 107, 53, 0.3);
    }

    .pi-steps-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .pi-steps-header h2 {
        color: var(--primary);
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .pi-steps-header p {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 500;
    }

    .pi-step {
        display: flex;
        gap: 24px;
        padding: 28px;
        background: linear-gradient(135deg, rgba(10, 14, 39, 0.6) 0%, rgba(21, 26, 61, 0.4) 100%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        margin-bottom: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .pi-step::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(180deg, var(--primary) 0%, var(--accent) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pi-step:hover {
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(0, 212, 255, 0.05) 100%);
        border-color: rgba(255, 107, 53, 0.3);
        transform: translateX(12px);
    }

    .pi-step:hover::before {
        opacity: 1;
    }

    .pi-step:last-child {
        margin-bottom: 0;
    }

    .pi-step-number {
        flex-shrink: 0;
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        font-weight: 900;
        color: #ffffff;
        box-shadow: 0 8px 32px rgba(255, 107, 53, 0.4);
        transition: all 0.3s ease;
        position: relative;
    }

    .pi-step-number::after {
        content: '';
        position: absolute;
        top: -4px;
        left: -4px;
        right: -4px;
        bottom: -4px;
        border: 2px solid rgba(255, 107, 53, 0.3);
        border-radius: 24px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .pi-step:hover .pi-step-number::after {
        opacity: 1;
    }

    .pi-step:hover .pi-step-number {
        transform: scale(1.1) rotate(5deg);
    }

    .pi-step-content h3 {
        color: var(--text-primary);
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 10px;
        letter-spacing: -0.01em;
    }

    .pi-step-content p {
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.7;
        margin: 0;
    }

    .pi-cta {
        text-align: center;
        margin-top: 36px;
        padding-top: 32px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .pi-button {
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
        position: relative;
        overflow: hidden;
    }

    .pi-button::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .pi-button:hover::before {
        left: 100%;
    }

    .pi-button:hover {
        transform: translateY(-6px) scale(1.02);
        box-shadow: 0 20px 60px rgba(255, 107, 53, 0.6);
    }

    .pi-button:active {
        transform: translateY(-3px) scale(1);
    }

    .pi-form-card {
        background: rgba(21, 26, 61, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 56px;
        backdrop-filter: blur(30px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
        position: relative;
        z-index: 1;
        overflow: hidden;
    }

    .pi-form-card::before {
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

    .pi-form-header {
        text-align: center;
        margin-bottom: 48px;
    }

    .pi-form-header h2 {
        color: var(--text-primary);
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .pi-form-header p {
        color: var(--text-secondary);
        font-size: 1.0625rem;
        font-weight: 500;
    }

    .pi-alert {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 24px;
        border-radius: 20px;
        margin-bottom: 40px;
        border-left: 5px solid;
        backdrop-filter: blur(10px);
    }

    .pi-alert.success {
        background: rgba(0, 212, 170, 0.1);
        border-left-color: var(--success);
        color: var(--success);
    }

    .pi-alert.error {
        background: rgba(255, 71, 87, 0.1);
        border-left-color: var(--error);
        color: var(--error);
    }

    .pi-alert i {
        font-size: 1.5rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .pi-alert ul {
        margin: 10px 0 0 24px;
        padding: 0;
    }

    .pi-alert li {
        margin-top: 8px;
        font-weight: 600;
    }

    /* Form Elements */
    .pi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 28px;
        margin-bottom: 0;
    }

    .pi-field {
        margin-bottom: 28px;
    }

    .pi-label {
        display: block;
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text-primary);
        letter-spacing: 0.02em;
    }

    .pi-label .required {
        color: var(--primary);
        margin-left: 4px;
    }

    .pi-input,
    .pi-select {
        width: 100%;
        padding: 16px 24px;
        background: rgba(10, 14, 39, 0.8);
        border: 2px solid var(--border);
        border-radius: 16px;
        color: var(--text-primary);
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
    }

    .pi-input:focus,
    .pi-select:focus {
        outline: none;
        border-color: var(--primary);
        background: rgba(10, 14, 39, 0.95);
        box-shadow: 0 0 0 5px rgba(255, 107, 53, 0.15);
    }

    .pi-input::placeholder {
        color: var(--text-secondary);
        font-weight: 400;
    }

    .pi-input.error,
    .pi-select.error {
        border-color: var(--error);
        box-shadow: 0 0 0 5px rgba(255, 71, 87, 0.15);
    }

    textarea.pi-input {
        min-height: 150px;
        resize: vertical;
        line-height: 1.7;
    }

    .pi-select option {
        background: #1a1f4e;
        color: var(--text-primary);
        padding: 16px;
    }

    .pi-submit {
        width: 100%;
        padding: 24px 48px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 20px;
        color: #ffffff;
        font-size: 1.25rem;
        font-weight: 900;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 12px 40px rgba(255, 107, 53, 0.5);
        text-transform: uppercase;
        letter-spacing: 0.08em;
        position: relative;
        overflow: hidden;
    }

    .pi-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s ease;
    }

    .pi-submit:hover::before {
        left: 100%;
    }

    .pi-submit:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 60px rgba(255, 107, 53, 0.6);
    }

    .pi-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Modal */
    .pi-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(10, 14, 39, 0.95);
        backdrop-filter: blur(15px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        padding: 20px;
    }

    .pi-modal.active {
        display: flex;
    }

    .pi-modal-content {
        background: var(--bg-card);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 24px;
        padding: 40px;
        max-width: 500px;
        width: 100%;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
    }

    /* Loading */
    .pi-loading {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(10, 14, 39, 0.95);
        backdrop-filter: blur(15px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99998;
    }

    .pi-loading.active {
        display: flex;
    }

    .pi-loader {
        width: 70px;
        height: 70px;
        border: 5px solid rgba(255, 107, 53, 0.2);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .pi-container {
            padding: 80px 20px 40px;
        }

        .pi-steps,
        .pi-form-card {
            padding: 36px;
        }
    }

    @media (max-width: 768px) {
        .pi-grid {
            grid-template-columns: 1fr;
        }

        .pi-step {
            flex-direction: column;
            text-align: center;
            padding: 24px;
        }

        .pi-step:hover {
            transform: translateY(-6px);
        }

        .pi-step-number {
            margin: 0 auto;
        }
    }
</style>
@endpush

@section('content')
<div class="pi-wrapper">
    <div class="pi-container">
        <div class="container">
            <!-- Hero Section -->
            <div class="pi-hero">
                <div class="pi-badge">
                    <i class="fas fa-rocket"></i>
                    Devenez créatif
                </div>
                <h1 class="pi-title">Transformez votre passion en carrière professionnelle</h1>
                <p class="pi-subtitle">Rejoignez l'École Virtuelle des Créatifs et lancez-vous dans une aventure créative unique en Afrique de l'Ouest. Formations certifiées Adobe, experts du design digital.</p>
                <div class="pi-trust-indicators">
                    <div class="pi-trust-item">
                        <i class="fas fa-check-circle"></i>
                        <span>95% de réussite</span>
                    </div>
                    <div class="pi-trust-item">
                        <i class="fas fa-certificate"></i>
                        <span>Certifications Adobe</span>
                    </div>
                    <div class="pi-trust-item">
                        <i class="fas fa-users"></i>
                        <span>+5000 étudiants</span>
                    </div>
                </div>
            </div>

            <!-- Steps Section -->
            <div class="row justify-content-center">
                <div class="col-8 pi-content-wrapper">
                    <div class="pi-steps">
                        <div class="pi-steps-header">
                            <h2>Votre parcours en 3 étapes</h2>
                            <p>Processus d'inscription simplifié et rapide</p>
                        </div>

                        <div class="pi-step">
                            <div class="pi-step-number">1</div>
                            <div class="pi-step-content">
                                <h3>Préinscription</h3>
                                <p>Remplissez le formulaire avec vos informations personnelles et choisissez votre formation. Un dépôt de dossier sera requis pour finaliser votre inscription.</p>
                            </div>
                        </div>

                        <div class="pi-step">
                            <div class="pi-step-number">2</div>
                            <div class="pi-step-content">
                                <h3>Diagnostic d'éligibilité</h3>
                                <p>Complétez le diagnostic d'une heure. Nos experts analyseront votre profil pour vous orienter vers la formation idéale adaptée à vos ambitions.</p>
                            </div>
                        </div>

                        <div class="pi-step">
                            <div class="pi-step-number">3</div>
                            <div class="pi-step-content">
                                <h3>Validation finale</h3>
                                <p>L'équipe pédagogique examinera votre dossier. Vous recevrez une confirmation et les instructions de paiement pour débuter votre formation.</p>
                            </div>
                        </div>

                        <div class="pi-cta">
                            <button type="button" class="pi-button" onclick="document.querySelector('.pi-form-card').scrollIntoView({behavior: 'smooth'})">
                                <i class="fas fa-pen-fancy"></i>
                                Commencer maintenant
                            </button>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="pi-form-card">
                        <div class="pi-form-header">
                            <h2>Formulaire de pré-inscription</h2>
                            <p>Tous les champs sont obligatoires</p>
                        </div>

                        @if(session('success'))
                            <div class="pi-alert success">
                                <i class="fas fa-check-circle"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="pi-alert error">
                                <i class="fas fa-exclamation-triangle"></i>
                                <div>
                                    <strong>Erreur :</strong> Veuillez corriger les champs suivants.
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        <form action="/candidature" method="POST" enctype="multipart/form-data" id="preinscriptionForm">
                            @csrf
                            @include('preinscription._form_fields_new')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Price Modal -->
<div class="pi-modal" id="programme-price-modal">
    <div class="pi-modal-content">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 24px;">
            <div style="font-weight: 900; font-size: 1.25rem; color: var(--text-primary);">Confirmer la formation</div>
            <button type="button" id="programme-price-close" style="background: transparent; border: 0; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; padding: 8px;">&times;</button>
        </div>
        <div style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 10px;">Formation sélectionnée</div>
        <div id="programme-price-name" style="color: var(--text-primary); font-size: 1.375rem; font-weight: 900; margin-bottom: 24px;"></div>
        <div style="color: var(--text-secondary); font-size: 1rem; margin-bottom: 10px;">Prix de la formation</div>
        <div id="programme-price-amount" style="color: var(--primary); font-size: 2.25rem; font-weight: 900; margin-bottom: 28px;"></div>
        <div style="display:flex; gap: 16px; justify-content:flex-end;">
            <button type="button" id="programme-price-cancel" style="padding: 14px 28px; border-radius: 16px; border: 2px solid var(--border); background: transparent; color: var(--text-primary); font-weight: 700; cursor: pointer;">Annuler</button>
            <button type="button" id="programme-price-confirm" style="padding: 14px 28px; border-radius: 16px; border: 0; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: #ffffff; font-weight: 900; cursor: pointer;">Confirmer</button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="pi-loading" id="loadingOverlay">
    <div style="text-align: center;">
        <div class="pi-loader"></div>
        <div style="color: var(--primary); font-size: 1.25rem; font-weight: 700; margin-top: 24px;">Envoi en cours...</div>
        <div style="color: var(--text-secondary); font-size: 1rem; margin-top: 10px;">Veuillez patienter</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('preinscriptionForm');
    const submitBtn = form ? form.querySelector('.submit-btn') : null;
    const loadingOverlay = document.getElementById('loadingOverlay');

    const programmeSelect = document.querySelector('select[name="programme"]');
    const priceModal = document.getElementById('programme-price-modal');
    const priceName = document.getElementById('programme-price-name');
    const priceAmount = document.getElementById('programme-price-amount');
    const closeBtn = document.getElementById('programme-price-close');
    const cancelBtn = document.getElementById('programme-price-cancel');
    const confirmBtn = document.getElementById('programme-price-confirm');

    let lastProgrammeValue = programmeSelect ? (programmeSelect.value || '') : '';
    let pendingProgrammeValue = '';

    const prices = {
        'design-graphique': { name: 'Design Graphique', amount: '185.000 FCFA' },
        'community-manager': { name: 'Community Management', amount: '165.000 FCFA' },
        'gestion-informatique': { name: 'Gestion Informatique', amount: '265.000 FCFA' },
    };

    const openPriceModal = (value) => {
        if (!priceModal || !priceName || !priceAmount) return;
        const entry = prices[value];
        if (!entry) return;
        pendingProgrammeValue = value;
        priceName.textContent = entry.name;
        priceAmount.textContent = entry.amount;
        priceModal.classList.add('active');
    };

    const closePriceModal = () => {
        if (!priceModal) return;
        priceModal.classList.remove('active');
        pendingProgrammeValue = '';
    };

    const revertProgramme = () => {
        if (!programmeSelect) return;
        programmeSelect.value = lastProgrammeValue;
    };

    if (programmeSelect) {
        programmeSelect.addEventListener('focus', () => {
            lastProgrammeValue = programmeSelect.value || '';
        });

        programmeSelect.addEventListener('change', () => {
            const v = programmeSelect.value || '';
            if (!v) {
                lastProgrammeValue = '';
                return;
            }
            if (!prices[v]) {
                lastProgrammeValue = v;
                return;
            }
            openPriceModal(v);
        });
    }

    if (closeBtn) closeBtn.addEventListener('click', () => { revertProgramme(); closePriceModal(); });
    if (cancelBtn) cancelBtn.addEventListener('click', () => { revertProgramme(); closePriceModal(); });
    if (confirmBtn) confirmBtn.addEventListener('click', () => {
        if (pendingProgrammeValue) {
            lastProgrammeValue = pendingProgrammeValue;
        }
        closePriceModal();
    });

    if (priceModal) {
        priceModal.addEventListener('click', (e) => {
            if (e.target === priceModal) {
                revertProgramme();
                closePriceModal();
            }
        });
    }

    @if($errors->any())
        const errorFields = @json($errors->keys());
        errorFields.forEach(function(fieldName) {
            const field = document.querySelector('[name="' + fieldName + '"]');
            if (field) {
                field.classList.add('error');
                field.addEventListener('input', function() { this.classList.remove('error'); });
                field.addEventListener('change', function() { this.classList.remove('error'); });
            }
        });

        const firstError = document.querySelector('.pi-input.error, .pi-select.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => firstError.focus(), 500);
        }
    @endif

    if (form && submitBtn && loadingOverlay) {
        form.addEventListener('submit', function(e) {
            const photoInput = form.querySelector('input[name="photo_profil"]');
            const maxBytes = 2 * 1024 * 1024;
            if (photoInput && photoInput.files && photoInput.files.length > 0) {
                const file = photoInput.files[0];
                if (file && typeof file.size === 'number' && file.size > maxBytes) {
                    e.preventDefault();
                    alert('❌ La photo de profil est trop lourde (max 2 Mo). Veuillez choisir une photo plus légère.');
                    return;
                }
            }

            if (form.checkValidity()) {
                loadingOverlay.classList.add('active');
                submitBtn.disabled = true;
                const btnText = submitBtn.querySelector('span');
                if (btnText) {
                    btnText.textContent = 'Envoi en cours...';
                }
            }
        });
    }
});
</script>
@endpush
