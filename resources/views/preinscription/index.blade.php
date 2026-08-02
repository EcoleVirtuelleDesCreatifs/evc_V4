@extends('layouts.app')

@section('title', 'Pré-inscription - École Virtuelle des Créatifs')
@section('description', 'Inscrivez-vous à l\'École Virtuelle des Créatifs et transformez votre passion en carrière')
@section('keywords', 'inscription evc, école virtuelle abidjan, formation design graphique')

@push('styles')
<style>
    :root {
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --accent: #ffb74d;
        --border: #334155;
        --primary: #ff9800;
        --primary-gradient: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%);
    }

    .evc-preinscription {
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        min-height: 100vh;
        padding-top: 140px;
        padding-bottom: 80px;
    }

    .evc-hero {
        text-align: center;
        margin-bottom: 60px;
        padding: 0 20px;
    }

    .evc-hero h1 {
        font-size: clamp(2rem, 5vw, 3rem);
        font-weight: 800;
        background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 16px;
        letter-spacing: -0.02em;
    }

    .evc-hero p {
        font-size: clamp(1rem, 2vw, 1.125rem);
        color: #94a3b8;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .evc-steps {
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(255, 152, 0, 0.2);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }

    .evc-steps-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .evc-steps-header h2 {
        color: #ff9800;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .evc-steps-header p {
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .evc-step {
        display: flex;
        gap: 16px;
        padding: 20px;
        background: rgba(15, 23, 42, 0.5);
        border-radius: 12px;
        margin-bottom: 16px;
        transition: all 0.3s ease;
    }

    .evc-step:hover {
        background: rgba(255, 152, 0, 0.05);
        transform: translateX(4px);
    }

    .evc-step:last-child {
        margin-bottom: 0;
    }

    .evc-step-icon {
        flex-shrink: 0;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #0f172a;
    }

    .evc-step-content h3 {
        color: #f1f5f9;
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .evc-step-content p {
        color: #94a3b8;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .evc-cta {
        text-align: center;
        margin-top: 24px;
    }

    .evc-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 16px 32px;
        background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%);
        border: none;
        border-radius: 12px;
        color: #0f172a;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 20px rgba(255, 152, 0, 0.3);
    }

    .evc-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(255, 152, 0, 0.4);
    }

    .evc-form-card {
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 40px;
        backdrop-filter: blur(10px);
    }

    .evc-form-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .evc-form-header h2 {
        color: #f1f5f9;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .evc-form-header p {
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .evc-alert {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
    }

    .evc-alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }

    .evc-alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    .evc-alert i {
        font-size: 1.25rem;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .evc-alert ul {
        margin: 8px 0 0 20px;
        padding: 0;
    }

    .evc-alert li {
        margin-top: 4px;
    }

    /* Form Elements */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .required {
        color: #ff9800;
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 12px 16px;
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.875rem;
        font-family: inherit;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: #ff9800;
        background: rgba(15, 23, 42, 0.9);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    .form-control.error,
    .form-select.error {
        border-color: #ef4444;
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-select option {
        background: #1e293b;
        color: #f1f5f9;
    }

    .submit-btn {
        width: 100%;
        padding: 16px 32px;
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        color: #0f172a;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(255, 152, 0, 0.4);
    }

    .submit-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    @media (max-width: 768px) {
        .evc-preinscription {
            padding-top: 100px;
            padding-bottom: 40px;
        }

        .evc-steps,
        .evc-form-card {
            padding: 24px;
        }

        .evc-step {
            flex-direction: column;
            text-align: center;
        }

        .evc-step-icon {
            margin: 0 auto;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="evc-preinscription">
    <div class="container">
        <!-- Hero Section -->
        <div class="evc-hero">
            <h1>Commencez votre parcours créatif</h1>
            <p>Rejoignez l'École Virtuelle des Créatifs et transformez votre passion en une carrière professionnelle.</p>
        </div>

        <!-- Steps Section -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="evc-steps">
                    <div class="evc-steps-header">
                        <h2>Processus d'inscription</h2>
                        <p>Direction des Études et de Régulation Pédagogique</p>
                    </div>

                    <div class="evc-step">
                        <div class="evc-step-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="evc-step-content">
                            <h3>Étape 1 : Préinscription</h3>
                            <p>Complétez le formulaire avec vos informations personnelles et choisissez votre formation. Un dépôt de dossier sera requis pour finaliser votre inscription.</p>
                        </div>
                    </div>

                    <div class="evc-step">
                        <div class="evc-step-icon">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="evc-step-content">
                            <h3>Étape 2 : Diagnostic d'éligibilité</h3>
                            <p>Vous disposerez de 1 heure pour compléter ce diagnostic obligatoire. Répondez avec précision : vos réponses seront analysées par l'équipe pédagogique EVC pour évaluer votre profil.</p>
                        </div>
                    </div>

                    <div class="evc-step">
                        <div class="evc-step-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="evc-step-content">
                            <h3>Étape 3 : Validation finale</h3>
                            <p>L'équipe pédagogique examinera votre dossier. Vous recevrez une confirmation et les instructions pour le paiement de votre formation.</p>
                        </div>
                    </div>

                    <div class="evc-cta">
                        <button type="button" class="evc-btn" onclick="document.querySelector('.evc-form-card').scrollIntoView({behavior: 'smooth'})">
                            <i class="fas fa-edit"></i>
                            Commencer ma préinscription
                        </button>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="evc-form-card">
                    <div class="evc-form-header">
                        <h2>Formulaire de pré-inscription</h2>
                        <p>Tous les champs sont obligatoires</p>
                    </div>

                    @if(session('success'))
                        <div class="evc-alert evc-alert-success">
                            <i class="fas fa-check-circle"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="evc-alert evc-alert-error">
                            <i class="fas fa-exclamation-circle"></i>
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

<div id="programme-price-modal" class="loading-overlay" style="display:none; z-index: 99999;">
    <div class="loader-container" style="max-width: 520px; width: calc(100% - 40px);">
        <div style="display:flex; align-items:center; justify-content:space-between; gap: 12px;">
            <div style="font-weight: 800; font-size: 18px; color: var(--text-primary);">Confirmer la formation</div>
            <button type="button" id="programme-price-close" aria-label="Fermer" style="background: transparent; border: 0; color: var(--text-secondary); font-size: 20px; line-height: 1; padding: 6px; cursor: pointer;">&times;</button>
        </div>
        <div style="margin-top: 14px; text-align: left;">
            <div style="color: var(--text-secondary); font-size: 14px;">Formation sélectionnée</div>
            <div id="programme-price-name" style="color: var(--text-primary); font-size: 18px; font-weight: 800; margin-top: 4px;"></div>

            <div style="color: var(--text-secondary); font-size: 14px; margin-top: 14px;">Prix de la formation</div>
            <div id="programme-price-amount" style="color: var(--primary); font-size: 26px; font-weight: 900; margin-top: 4px;"></div>

            <div style="margin-top: 18px; display:flex; gap: 12px; justify-content:flex-end; flex-wrap: wrap;">
                <button type="button" id="programme-price-cancel" style="padding: 10px 14px; border-radius: 10px; border: 1px solid var(--border); background: rgba(255,255,255,0.06); color: var(--text-primary); font-weight: 700;">Annuler</button>
                <button type="button" id="programme-price-confirm" style="padding: 10px 14px; border-radius: 10px; border: 0; background: var(--primary-gradient); color: #111827; font-weight: 900;">Confirmer</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loader-container">
        <div class="loader"></div>
        <div class="loader-text">Envoi en cours...</div>
        <div class="loader-subtext">Veuillez patienter</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Main Form Functionality
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
        priceModal.style.display = 'flex';
        priceModal.classList.add('active');
    };

    const closePriceModal = () => {
        if (!priceModal) return;
        priceModal.classList.remove('active');
        priceModal.style.display = 'none';
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

    // Marquer les champs avec erreurs de validation
    @if($errors->any())
        const errorFields = @json($errors->keys());
        errorFields.forEach(function(fieldName) {
            const field = document.querySelector('[name="' + fieldName + '"]');
            if (field) {
                field.classList.add('error');
                // Retirer la classe error quand l'utilisateur modifie le champ
                field.addEventListener('input', function() {
                    this.classList.remove('error');
                });
                field.addEventListener('change', function() {
                    this.classList.remove('error');
                });
            }
        });

        // Scroll vers la première erreur
        const firstError = document.querySelector('.form-control.error, .form-select.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => firstError.focus(), 500);
        }
    @endif

    // Gestion du loader lors de la soumission
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
                submitBtn.classList.add('loading');
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
