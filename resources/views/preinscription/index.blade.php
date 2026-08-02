@extends('layouts.app')

@section('title', 'Pré-inscription - École Virtuelle des Créatifs')
@section('description', 'Inscrivez-vous à l\'École Virtuelle des Créatifs et transformez votre passion en carrière')
@section('keywords', 'inscription evc, école virtuelle abidjan, formation design graphique')

@section('content')
<div class="container py-5">
        <!-- Header -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="header">
                    <span class="header-badge">REJOIGNEZ-NOUS</span>
                    <h1>Pré-inscription</h1>
                    <p>Transformez votre passion en carrière. Remplissez le formulaire ci-dessous pour démarrer votre parcours à l'EVC.</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="content-grid">
                    <!-- Eligibility Test Section -->
                    <div class="eligibility-section" id="eligibilitySection">
                        <div class="eligibility-header">
                            <h2>Test d'Éligibilité</h2>
                            <p>Direction des Études et de Régulation Pédagogique</p>
                        </div>

                        <div class="eligibility-subtitle" style="margin-bottom: 24px;">
                            Processus d'inscription en 3 étapes
                        </div>

                        <div class="eligibility-form">
                            <div class="eligibility-question">
                                <h3><i class="fas fa-file-alt mr-2" style="color: #ff9800;"></i>Étape 1 : Préinscription</h3>
                                <p style="color: #94a3b8; font-size: 14px; margin-top: 8px;">
                                    Complétez le formulaire avec vos informations personnelles et choisissez votre formation.
                                    Un dépôt de dossier sera requis pour finaliser votre inscription.
                                </p>
                            </div>

                            <div class="eligibility-question">
                                <h3><i class="fas fa-clipboard-check mr-2" style="color: #ff9800;"></i>Étape 2 : Diagnostic d'éligibilité</h3>
                                <p style="color: #94a3b8; font-size: 14px; margin-top: 8px;">
                                    Vous disposerez de 1 heure pour compléter ce diagnostic obligatoire.
                                    Répondez avec précision : vos réponses seront analysées par l'équipe pédagogique EVC
                                    pour évaluer votre profil et vous orienter vers la formation adaptée.
                                </p>
                            </div>

                            <div class="eligibility-question" style="border-bottom: none;">
                                <h3><i class="fas fa-check-circle mr-2" style="color: #ff9800;"></i>Étape 3 : Validation finale</h3>
                                <p style="color: #94a3b8; font-size: 14px; margin-top: 8px;">
                                    L'équipe pédagogique examinera votre dossier. Vous recevrez une confirmation
                                    et les instructions pour le paiement de votre formation.
                                </p>
                            </div>
                        </div>

                        <button type="button" class="start-test-btn" onclick="document.querySelector('.form-card').scrollIntoView({behavior: 'smooth'})">
                            <i class="fas fa-edit mr-2"></i>Préinscription
                        </button>
                    </div>

                    <!-- Form -->
                    <div class="form-card">
                        <div class="form-header">
                            <h2>Formulaire de pré-inscription</h2>
                            <p>Remplissez soigneusement le formulaire. Tous les champs sont obligatoires.</p>
                        </div>

                        @if(session('success'))
                            <div class="alert-success">
                                <i class="fas fa-check-circle"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <div>
                                    <strong>Erreur :</strong> Veuillez corriger les champs suivants.
                                    <ul style="margin-top: 10px; padding-left: 20px;">
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
