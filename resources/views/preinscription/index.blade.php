@extends('layouts.app')

@section('title', 'Pré-inscription - École Virtuelle des Créatifs')
@section('description', 'Inscrivez-vous à l\'École Virtuelle des Créatifs et transformez votre passion en carrière')
@section('keywords', 'inscription evc, école virtuelle abidjan, formation design graphique')

@push('styles')
<style>
    .preinscription-wrapper {
        --primary: #ff9800;
        --primary-dark: #f57c00;
        --primary-light: #ffb74d;
        --bg-dark: #0f172a;
        --bg-card: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --border: #334155;
        --success: #22c55e;
        --error: #ef4444;
    }

    .preinscription-container {
        background: linear-gradient(135deg, var(--bg-dark) 0%, #1a1f2e 50%, var(--bg-dark) 100%);
        min-height: 100vh;
        padding: 120px 20px 80px;
        position: relative;
        overflow: hidden;
    }

    .preinscription-container::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 30%, rgba(255, 152, 0, 0.08) 0%, transparent 50%);
        pointer-events: none;
    }

    .preinscription-container::after {
        content: '';
        position: absolute;
        bottom: -30%;
        right: -30%;
        width: 150%;
        height: 150%;
        background: radial-gradient(circle at 70% 70%, rgba(255, 183, 77, 0.05) 0%, transparent 50%);
        pointer-events: none;
    }

    .hero-section {
        text-align: center;
        margin-bottom: 48px;
        position: relative;
        z-index: 1;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        background: rgba(255, 152, 0, 0.1);
        border: 1px solid rgba(255, 152, 0, 0.3);
        border-radius: 50px;
        color: var(--primary);
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 24px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .hero-title {
        font-size: clamp(2.25rem, 5vw, 3.5rem);
        font-weight: 900;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 50%, var(--primary) 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 16px;
        letter-spacing: -0.02em;
        animation: shimmer 3s linear infinite;
    }

    @keyframes shimmer {
        0% { background-position: 0% center; }
        100% { background-position: 200% center; }
    }

    .hero-subtitle {
        font-size: clamp(1rem, 2vw, 1.25rem);
        color: var(--text-secondary);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.7;
        font-weight: 400;
    }

    .steps-card {
        background: rgba(30, 41, 59, 0.6);
        border: 1px solid rgba(255, 152, 0, 0.15);
        border-radius: 20px;
        padding: 40px;
        margin-bottom: 32px;
        backdrop-filter: blur(20px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .steps-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 48px rgba(255, 152, 0, 0.15);
    }

    .steps-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .steps-header h2 {
        color: var(--primary);
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 8px;
        letter-spacing: -0.01em;
    }

    .steps-header p {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        font-weight: 500;
    }

    .step-item {
        display: flex;
        gap: 20px;
        padding: 24px;
        background: rgba(15, 23, 42, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.05);
        border-radius: 16px;
        margin-bottom: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .step-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, var(--primary) 0%, var(--primary-dark) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .step-item:hover {
        background: rgba(255, 152, 0, 0.05);
        border-color: rgba(255, 152, 0, 0.2);
        transform: translateX(8px);
    }

    .step-item:hover::before {
        opacity: 1;
    }

    .step-item:last-child {
        margin-bottom: 0;
    }

    .step-number {
        flex-shrink: 0;
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: 900;
        color: #0f172a;
        box-shadow: 0 4px 16px rgba(255, 152, 0, 0.4);
    }

    .step-content h3 {
        color: var(--text-primary);
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.01em;
    }

    .step-content p {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        line-height: 1.6;
        margin: 0;
    }

    .cta-section {
        text-align: center;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.05);
    }

    .cta-button {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 18px 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 16px;
        color: #0f172a;
        font-size: 1.0625rem;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 8px 24px rgba(255, 152, 0, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .cta-button:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 12px 40px rgba(255, 152, 0, 0.5);
    }

    .cta-button:active {
        transform: translateY(-2px) scale(1);
    }

    .form-card {
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 48px;
        backdrop-filter: blur(20px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        position: relative;
        z-index: 1;
    }

    .form-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .form-header h2 {
        color: var(--text-primary);
        font-size: 1.875rem;
        font-weight: 800;
        margin-bottom: 12px;
        letter-spacing: -0.02em;
    }

    .form-header p {
        color: var(--text-secondary);
        font-size: 1rem;
        font-weight: 500;
    }

    .alert-box {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 20px;
        border-radius: 16px;
        margin-bottom: 32px;
        border-left: 4px solid;
    }

    .alert-box.success {
        background: rgba(34, 197, 94, 0.1);
        border-left-color: var(--success);
        color: var(--success);
    }

    .alert-box.error {
        background: rgba(239, 68, 68, 0.1);
        border-left-color: var(--error);
        color: var(--error);
    }

    .alert-box i {
        font-size: 1.5rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .alert-box ul {
        margin: 8px 0 0 24px;
        padding: 0;
    }

    .alert-box li {
        margin-top: 6px;
        font-weight: 500;
    }

    /* Form Elements */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 0;
    }

    .form-field {
        margin-bottom: 24px;
    }

    .form-label {
        display: block;
        font-size: 0.9375rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-primary);
        letter-spacing: 0.01em;
    }

    .form-label .required {
        color: var(--primary);
        margin-left: 2px;
    }

    .form-input,
    .form-select {
        width: 100%;
        padding: 14px 20px;
        background: rgba(15, 23, 42, 0.8);
        border: 2px solid var(--border);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 1rem;
        font-family: inherit;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
    }

    .form-input:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary);
        background: rgba(15, 23, 42, 0.95);
        box-shadow: 0 0 0 4px rgba(255, 152, 0, 0.1);
    }

    .form-input::placeholder {
        color: var(--text-secondary);
        font-weight: 400;
    }

    .form-input.error,
    .form-select.error {
        border-color: var(--error);
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
    }

    textarea.form-input {
        min-height: 140px;
        resize: vertical;
        line-height: 1.6;
    }

    .form-select option {
        background: #1e293b;
        color: var(--text-primary);
        padding: 12px;
    }

    .submit-button {
        width: 100%;
        padding: 20px 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 16px;
        color: #0f172a;
        font-size: 1.125rem;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 8px 24px rgba(255, 152, 0, 0.4);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .submit-button:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(255, 152, 0, 0.5);
    }

    .submit-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    /* Modal */
    .price-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(10px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        padding: 20px;
    }

    .price-modal.active {
        display: flex;
    }

    .price-modal-content {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 32px;
        max-width: 480px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    }

    /* Loading */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(10px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 99998;
    }

    .loading-overlay.active {
        display: flex;
    }

    .loader {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(255, 152, 0, 0.2);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .preinscription-container {
            padding: 100px 20px 60px;
        }

        .steps-card,
        .form-card {
            padding: 32px;
        }
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .step-item {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .step-item:hover {
            transform: translateY(-4px);
        }

        .step-number {
            margin: 0 auto;
        }
    }
</style>
@endpush

@section('content')
<div class="preinscription-wrapper">
    <div class="preinscription-container">
        <div class="container">
            <!-- Hero Section -->
            <div class="hero-section">
                <div class="hero-badge">
                    <i class="fas fa-rocket"></i>
                    Devenez créatif
                </div>
                <h1 class="hero-title">Transformez votre passion en carrière</h1>
                <p class="hero-subtitle">Rejoignez l'École Virtuelle des Créatifs et lancez-vous dans une aventure créative unique en Afrique de l'Ouest.</p>
            </div>

            <!-- Steps Section -->
            <div class="row justify-content-center">
                <div class="col-8">
                    <div class="steps-card">
                        <div class="steps-header">
                            <h2>Votre parcours en 3 étapes</h2>
                            <p>Processus d'inscription simplifié</p>
                        </div>

                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Préinscription</h3>
                                <p>Remplissez le formulaire avec vos informations et choisissez votre formation. Un dépôt de dossier sera requis.</p>
                            </div>
                        </div>

                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Diagnostic d'éligibilité</h3>
                                <p>Complétez le diagnostic d'une heure. Nos experts analyseront votre profil pour vous orienter vers la formation idéale.</p>
                            </div>
                        </div>

                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3>Validation finale</h3>
                                <p>L'équipe pédagogique examinera votre dossier. Vous recevrez une confirmation et les instructions de paiement.</p>
                            </div>
                        </div>

                        <div class="cta-section">
                            <button type="button" class="cta-button" onclick="document.querySelector('.form-card').scrollIntoView({behavior: 'smooth'})">
                                <i class="fas fa-pen-fancy"></i>
                                Commencer maintenant
                            </button>
                        </div>
                    </div>

                    <!-- Form Section -->
                    <div class="form-card">
                        <div class="form-header">
                            <h2>Formulaire de pré-inscription</h2>
                            <p>Tous les champs sont obligatoires</p>
                        </div>

                        @if(session('success'))
                            <div class="alert-box success">
                                <i class="fas fa-check-circle"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert-box error">
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
<div class="price-modal" id="programme-price-modal">
    <div class="price-modal-content">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div style="font-weight: 800; font-size: 1.125rem; color: var(--text-primary);">Confirmer la formation</div>
            <button type="button" id="programme-price-close" style="background: transparent; border: 0; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer; padding: 8px;">&times;</button>
        </div>
        <div style="color: var(--text-secondary); font-size: 0.9375rem; margin-bottom: 8px;">Formation sélectionnée</div>
        <div id="programme-price-name" style="color: var(--text-primary); font-size: 1.25rem; font-weight: 800; margin-bottom: 20px;"></div>
        <div style="color: var(--text-secondary); font-size: 0.9375rem; margin-bottom: 8px;">Prix de la formation</div>
        <div id="programme-price-amount" style="color: var(--primary); font-size: 2rem; font-weight: 900; margin-bottom: 24px;"></div>
        <div style="display:flex; gap: 12px; justify-content:flex-end;">
            <button type="button" id="programme-price-cancel" style="padding: 12px 24px; border-radius: 12px; border: 2px solid var(--border); background: transparent; color: var(--text-primary); font-weight: 700; cursor: pointer;">Annuler</button>
            <button type="button" id="programme-price-confirm" style="padding: 12px 24px; border-radius: 12px; border: 0; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color: #0f172a; font-weight: 800; cursor: pointer;">Confirmer</button>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div style="text-align: center;">
        <div class="loader"></div>
        <div style="color: var(--primary); font-size: 1.125rem; font-weight: 700; margin-top: 20px;">Envoi en cours...</div>
        <div style="color: var(--text-secondary); font-size: 0.9375rem; margin-top: 8px;">Veuillez patienter</div>
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

        const firstError = document.querySelector('.form-input.error, .form-select.error');
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
