@extends('layouts.app')

@section('title', 'Pré-inscription - École Virtuelle des Créatifs')
@section('description', 'Inscrivez-vous à l\'École Virtuelle des Créatifs et transformez votre passion en carrière')
@section('keywords', 'inscription evc, école virtuelle abidjan, formation design graphique')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #ff9800;
        --primary-dark: #f57c00;
        --primary-gradient: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);
        --accent: #ffb74d;
        --bg-dark: #0f172a;
        --bg-card: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --border: #334155;
        --glow-orange: rgba(255, 152, 0, 0.3);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-dark);
        font-family: 'Inter', sans-serif;
        color: var(--text-primary);
        line-height: 1.6;
    }

    .page-container {
        min-height: 100vh;
        padding: 160px 20px 60px;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Header */
    .header {
        text-align: center;
        margin-bottom: 60px;
        padding-top: 60px;
    }

    .header-badge {
        display: inline-block;
        padding: 6px 16px;
        background: rgba(255, 152, 0, 0.1);
        border: 1px solid rgba(255, 152, 0, 0.3);
        border-radius: 20px;
        color: var(--primary);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
        box-shadow: 0 0 20px var(--glow-orange);
    }

    .header h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 16px;
        letter-spacing: -1px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header p {
        font-size: 18px;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
        font-weight: 400;
    }

    /* Content Grid */
    .content-grid {
        max-width: 100%;
    }

    /* Form Card */
    .form-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 40px;
    }

    .form-header {
        margin-bottom: 32px;
    }

    .form-header h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .form-header p {
        font-size: 14px;
        color: var(--text-secondary);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 8px;
        color: var(--text-primary);
    }

    .required {
        color: var(--primary);
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 12px 16px;
        background: var(--bg-dark);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 14px;
        font-family: 'Inter', sans-serif;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--primary);
        background: var(--bg-dark);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    textarea.form-control {
        min-height: 120px;
        resize: vertical;
    }

    .form-select option {
        background: var(--bg-dark);
    }

    /* File Upload */
    .file-upload {
        position: relative;
        border: 2px dashed var(--border);
        border-radius: 8px;
        padding: 32px;
        text-align: center;
        background: var(--bg-dark);
        cursor: pointer;
        transition: all 0.2s;
    }

    .file-upload:hover {
        border-color: var(--primary);
        background: rgba(156, 39, 176, 0.05);
    }

    .file-upload.drag-over {
        border-color: var(--primary);
        background: rgba(156, 39, 176, 0.1);
    }

    .file-upload input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .file-upload-icon {
        font-size: 32px;
        color: var(--primary);
        margin-bottom: 12px;
    }

    .file-upload-text {
        font-size: 14px;
        color: var(--text-primary);
        font-weight: 500;
        margin-bottom: 4px;
    }

    .file-upload-hint {
        font-size: 13px;
        color: var(--text-secondary);
    }

    .file-name {
        margin-top: 12px;
        padding: 8px 12px;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 6px;
        color: #22c55e;
        font-size: 13px;
        display: none;
    }

    .file-name.show {
        display: block;
    }

    /* Submit Button */
    .submit-btn {
        width: 100%;
        padding: 14px 24px;
        background: var(--primary);
        border: none;
        border-radius: 8px;
        color: white;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .submit-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    /* Alert */
    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        border-radius: 8px;
        padding: 12px 16px;
        color: #22c55e;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        border-radius: 8px;
        padding: 12px 16px;
        color: #ef4444;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 14px;
    }

    .error-text {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    .form-control.error,
    .form-select.error {
        border-color: #ef4444;
    }

    /* Loading Overlay */
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.95);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(5px);
    }

    .loading-overlay.active {
        display: flex;
    }

    .loader-container {
        text-align: center;
    }

    .loader {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(255, 152, 0, 0.2);
        border-top: 4px solid #ff9800;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loader-text {
        color: #ff9800;
        font-size: 16px;
        font-weight: 600;
        margin-top: 10px;
    }

    .loader-subtext {
        color: #94a3b8;
        font-size: 14px;
        margin-top: 8px;
    }

    .submit-btn.loading {
        opacity: 0.7;
        cursor: not-allowed;
        pointer-events: none;
    }

    .submit-btn.loading i {
        animation: pulse 1.5s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    /* Eligibility Test Section */
    .eligibility-section {
        background: linear-gradient(135deg, #1e3a5f 0%, #0d1b2a 100%);
        border: 2px solid rgba(255, 152, 0, 0.3);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }

    .eligibility-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff9800, #ff6b00, #ff9800);
        background-size: 200% 100%;
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .eligibility-header {
        text-align: center;
        margin-bottom: 24px;
    }

    .eligibility-header h2 {
        font-size: 28px;
        font-weight: 700;
        color: #ff9800;
        margin-bottom: 12px;
    }

    .eligibility-header p {
        color: #94a3b8;
        font-size: 16px;
    }

    .eligibility-subtitle {
        text-align: center;
        color: #f1f5f9;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
    }

    .timer-container {
        background: rgba(255, 152, 0, 0.1);
        border: 2px solid #ff9800;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        margin-bottom: 24px;
    }

    .timer-label {
        color: #94a3b8;
        font-size: 14px;
        margin-bottom: 8px;
    }

    .timer-display {
        font-size: 48px;
        font-weight: 900;
        color: #ff9800;
        font-family: 'Courier New', monospace;
    }

    .timer-warning {
        color: #ef4444;
    }

    .eligibility-form {
        background: rgba(15, 23, 42, 0.5);
        border-radius: 12px;
        padding: 24px;
    }

    .eligibility-question {
        margin-bottom: 24px;
        padding-bottom: 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .eligibility-question:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .eligibility-question h3 {
        color: #f1f5f9;
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 12px;
    }

    .eligibility-options {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .eligibility-option {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        background: rgba(30, 41, 59, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .eligibility-option:hover {
        background: rgba(255, 152, 0, 0.1);
        border-color: rgba(255, 152, 0, 0.3);
    }

    .eligibility-option input[type="radio"] {
        margin-right: 12px;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .eligibility-option label {
        cursor: pointer;
        color: #94a3b8;
        font-size: 14px;
        flex: 1;
    }

    .start-test-btn {
        width: 100%;
        padding: 16px 32px;
        background: linear-gradient(135deg, #ff9800 0%, #ff6b00 100%);
        border: none;
        border-radius: 12px;
        color: #0f172a;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
        margin-bottom: 16px;
    }

    .start-test-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(255, 152, 0, 0.3);
    }

    .submit-test-btn {
        width: 100%;
        padding: 16px 32px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 18px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s;
    }

    .submit-test-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
    }

    .submit-test-btn:disabled {
        background: #475569;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .test-completed {
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid #10b981;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
    }

    .test-completed h3 {
        color: #10b981;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .test-completed p {
        color: #94a3b8;
        font-size: 16px;
    }

    .hidden {
        display: none !important;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-container {
            padding: 140px 16px 40px;
        }

        .header {
            padding-top: 40px;
        }

        .header h1 {
            font-size: 32px;
        }

        .header p {
            font-size: 16px;
        }

        .form-card {
            padding: 24px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <div class="container">
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

                        <!-- Initial State -->
                        <div id="eligibilityInitial">
                            <div class="eligibility-subtitle">
                                Vous disposez de 1 heure pour compléter ce diagnostic obligatoire.
                                Répondez avec précision : vos réponses seront analysées par l'équipe pédagogique EVC.
                            </div>
                            <button type="button" class="start-test-btn" id="startTestBtn">
                                <i class="fas fa-clipboard-check mr-2"></i>Commencer le Test
                            </button>
                        </div>

                        <!-- Test Form -->
                        <div id="eligibilityTest" class="hidden">
                            <div class="timer-container">
                                <div class="timer-label">Temps restant</div>
                                <div class="timer-display" id="timerDisplay">60:00</div>
                            </div>

                            <form id="eligibilityForm" class="eligibility-form">
                                @csrf

                                <div class="eligibility-question">
                                    <h3>1. Quel est votre niveau d'éducation actuel ?</h3>
                                    <div class="eligibility-options">
                                        <div class="eligibility-option">
                                            <input type="radio" name="education" id="edu1" value="bac" required>
                                            <label for="edu1">Baccalauréat ou équivalent</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="education" id="edu2" value="licence">
                                            <label for="edu2">Licence (Bac+3)</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="education" id="edu3" value="master">
                                            <label for="edu3">Master ou supérieur</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="education" id="edu4" value="autre">
                                            <label for="edu4">Autre</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="eligibility-question">
                                    <h3>2. Avez-vous déjà suivi une formation en design ou en digital ?</h3>
                                    <div class="eligibility-options">
                                        <div class="eligibility-option">
                                            <input type="radio" name="experience" id="exp1" value="oui" required>
                                            <label for="exp1">Oui, j'ai une expérience ou une formation</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="experience" id="exp2" value="non">
                                            <label for="exp2">Non, je débute</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="eligibility-question">
                                    <h3>3. Quelle formation vous intéresse le plus ?</h3>
                                    <div class="eligibility-options">
                                        <div class="eligibility-option">
                                            <input type="radio" name="formation_interest" id="form1" value="design-graphique" required>
                                            <label for="form1">Design Graphique</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="formation_interest" id="form2" value="community-management">
                                            <label for="form2">Community Management</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="formation_interest" id="form3" value="gestion-informatique">
                                            <label for="form3">Gestion Informatique</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="eligibility-question">
                                    <h3>4. Quelle est votre motivation pour rejoindre EVC ?</h3>
                                    <div class="eligibility-options">
                                        <div class="eligibility-option">
                                            <input type="radio" name="motivation" id="mot1" value="carriere" required>
                                            <label for="mot1">Développer une carrière professionnelle</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="motivation" id="mot2" value="competence">
                                            <label for="mot2">Acquérir de nouvelles compétences</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="motivation" id="mot3" value="entrepreneuriat">
                                            <label for="mot3">Lancer mon propre projet/entreprise</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="motivation" id="mot4" value="autre">
                                            <label for="mot4">Autre raison</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="eligibility-question">
                                    <h3>5. Disposez-vous d'un ordinateur personnel pour suivre les formations ?</h3>
                                    <div class="eligibility-options">
                                        <div class="eligibility-option">
                                            <input type="radio" name="equipment" id="equip1" value="oui" required>
                                            <label for="equip1">Oui, j'ai un ordinateur</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="equipment" id="equip2" value="non">
                                            <label for="equip2">Non, je prévois d'en acquérir un</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="equipment" id="equip3" value="partage">
                                            <label for="equip3">Je peux en partager un</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="eligibility-question">
                                    <h3>6. Combien de temps pouvez-vous consacrer par semaine à votre formation ?</h3>
                                    <div class="eligibility-options">
                                        <div class="eligibility-option">
                                            <input type="radio" name="time_availability" id="time1" value="5-10" required>
                                            <label for="time1">5 à 10 heures</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="time_availability" id="time2" value="10-15">
                                            <label for="time2">10 à 15 heures</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="time_availability" id="time3" value="15-20">
                                            <label for="time3">15 à 20 heures</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="time_availability" id="time4" value="20+">
                                            <label for="time4">Plus de 20 heures</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="eligibility-question">
                                    <h3>7. Comment avez-vous connu EVC ?</h3>
                                    <div class="eligibility-options">
                                        <div class="eligibility-option">
                                            <input type="radio" name="source" id="src1" value="reseaux" required>
                                            <label for="src1">Réseaux sociaux (Facebook, Instagram, LinkedIn)</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="source" id="src2" value="bouche">
                                            <label for="src2">Bouche-à-oreille</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="source" id="src3" value="google">
                                            <label for="src3">Recherche Google</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="source" id="src4" value="evenement">
                                            <label for="src4">Événement ou salon</label>
                                        </div>
                                        <div class="eligibility-option">
                                            <input type="radio" name="source" id="src5" value="autre">
                                            <label for="src5">Autre</label>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="submit-test-btn" id="submitTestBtn">
                                    <i class="fas fa-paper-plane mr-2"></i>Soumettre le Diagnostic
                                </button>
                            </form>
                        </div>

                        <!-- Completed State -->
                        <div id="eligibilityCompleted" class="test-completed hidden">
                            <h3><i class="fas fa-check-circle mr-2"></i>Diagnostic Complété</h3>
                            <p>Votre test d'éligibilité a été soumis avec succès. L'équipe pédagogique EVC analysera vos réponses.</p>
                            <p style="margin-top: 12px; color: #10b981; font-weight: 600;">Vous pouvez maintenant procéder à votre pré-inscription.</p>
                        </div>
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
    // Eligibility Test Functionality
    const eligibilitySection = document.getElementById('eligibilitySection');
    const eligibilityInitial = document.getElementById('eligibilityInitial');
    const eligibilityTest = document.getElementById('eligibilityTest');
    const eligibilityCompleted = document.getElementById('eligibilityCompleted');
    const startTestBtn = document.getElementById('startTestBtn');
    const eligibilityForm = document.getElementById('eligibilityForm');
    const timerDisplay = document.getElementById('timerDisplay');

    let timerInterval;
    let timeRemaining = 60 * 60; // 1 hour in seconds

    // Start Test
    startTestBtn.addEventListener('click', function() {
        eligibilityInitial.classList.add('hidden');
        eligibilityTest.classList.remove('hidden');
        startTimer();
    });

    // Timer Function
    function startTimer() {
        updateTimerDisplay();
        timerInterval = setInterval(function() {
            timeRemaining--;
            updateTimerDisplay();

            if (timeRemaining <= 0) {
                clearInterval(timerInterval);
                timerDisplay.classList.add('timer-warning');
                timerDisplay.textContent = '00:00';
                // Optionally auto-submit or disable form
                submitTestBtn.disabled = true;
            } else if (timeRemaining <= 300) { // 5 minutes warning
                timerDisplay.classList.add('timer-warning');
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const hours = Math.floor(timeRemaining / 3600);
        const minutes = Math.floor((timeRemaining % 3600) / 60);
        const seconds = timeRemaining % 60;

        const displayHours = hours.toString().padStart(2, '0');
        const displayMinutes = minutes.toString().padStart(2, '0');
        const displaySeconds = seconds.toString().padStart(2, '0');

        timerDisplay.textContent = `${displayHours}:${displayMinutes}:${displaySeconds}`;
    }

    // Submit Eligibility Test
    eligibilityForm.addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate form
        const formData = new FormData(eligibilityForm);
        let isValid = true;

        // Check all radio groups
        const radioGroups = ['education', 'experience', 'formation_interest', 'motivation', 'equipment', 'time_availability', 'source'];
        radioGroups.forEach(group => {
            const selected = formData.get(group);
            if (!selected) {
                isValid = false;
            }
        });

        if (!isValid) {
            alert('Veuillez répondre à toutes les questions avant de soumettre.');
            return;
        }

        // Show loading state
        const submitTestBtn = document.getElementById('submitTestBtn');
        submitTestBtn.disabled = true;
        submitTestBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Envoi en cours...';

        // Simulate submission (in real implementation, send to server)
        setTimeout(function() {
            clearInterval(timerInterval);
            eligibilityTest.classList.add('hidden');
            eligibilityCompleted.classList.remove('hidden');

            // Store test completion in localStorage
            localStorage.setItem('evc_eligibility_test_completed', 'true');
            localStorage.setItem('evc_eligibility_test_data', JSON.stringify(Object.fromEntries(formData)));
        }, 1500);
    });

    // Check if test was already completed
    if (localStorage.getItem('evc_eligibility_test_completed') === 'true') {
        eligibilityInitial.classList.add('hidden');
        eligibilityCompleted.classList.remove('hidden');
    }

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
