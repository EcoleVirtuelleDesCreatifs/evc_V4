@extends('layouts.app')

@section('title', 'Devenir Partenaire - École Virtuelle des Créatifs')
@section('description', 'Devenez partenaire de l\'École Virtuelle des Créatifs')
@section('keywords', 'partenariat evc, partenaire école virtuelle abidjan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #4fc3f7;
        --primary-dark: #29b6f6;
        --bg-dark: #0f172a;
        --bg-card: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --border: #334155;
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
        padding-top: 120px;
    }

    .header-badge {
        display: inline-block;
        padding: 6px 16px;
        background: rgba(79, 195, 247, 0.1);
        border: 1px solid rgba(79, 195, 247, 0.3);
        border-radius: 20px;
        color: var(--primary);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
    }

    .header h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 16px;
        letter-spacing: -1px;
    }

    .header p {
        font-size: 18px;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
        font-weight: 400;
    }

    /* Partnership Types */
    .partnership-types {
        margin-bottom: 60px;
    }

    .partnership-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .partnership-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        transition: all 0.3s;
    }

    .partnership-card:hover {
        border-color: var(--primary);
        transform: translateY(-4px);
    }

    .partnership-icon {
        font-size: 32px;
        color: var(--primary);
        margin-bottom: 12px;
    }

    .partnership-title {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .partnership-desc {
        font-size: 13px;
        color: var(--text-secondary);
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 50px;
        align-items: start;
        max-width: 100%;
    }

    /* Sidebar */
    .sidebar {
        position: sticky;
        top: 100px;
    }

    .info-box {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
    }

    .info-box h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 16px;
        color: var(--text-primary);
    }

    .info-box ul {
        list-style: none;
    }

    .info-box li {
        font-size: 14px;
        color: var(--text-secondary);
        margin-bottom: 12px;
        padding-left: 20px;
        position: relative;
    }

    .info-box li:before {
        content: "•";
        position: absolute;
        left: 0;
        color: var(--primary);
        font-weight: bold;
    }

    .contact-info {
        font-size: 14px;
        color: var(--text-secondary);
        line-height: 1.8;
    }

    .contact-info strong {
        color: var(--text-primary);
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
        border: 4px solid rgba(79, 195, 247, 0.2);
        border-top: 4px solid #4fc3f7;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .loader-text {
        color: #4fc3f7;
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

    /* Responsive */
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }

        .sidebar {
            position: static;
        }

        .partnership-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-container {
            padding: 140px 16px 40px;
        }

        .header {
            padding-top: 80px;
        }

        .header h1 {
            font-size: 32px;
        }

        .header p {
            font-size: 16px;
        }

        .partnership-grid {
            grid-template-columns: 1fr;
            gap: 12px;
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
                    <span class="header-badge">PARTENARIATS STRATÉGIQUES</span>
                    <h1>Devenez partenaire</h1>
                    <p>Collaborez avec nous pour développer des synergies et créer de la valeur ensemble</p>
                </div>
            </div>
        </div>

        <!-- Partnership Types -->
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="partnership-types">
                    <div class="partnership-grid">
                        <div class="partnership-card">
                            <div class="partnership-icon">
                                <i class="fas fa-briefcase"></i>
                            </div>
                            <div class="partnership-title">Entreprises</div>
                            <div class="partnership-desc">Recrutement & stages</div>
                        </div>
                        <div class="partnership-card">
                            <div class="partnership-icon">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="partnership-title">Institutions</div>
                            <div class="partnership-desc">Projets académiques</div>
                        </div>
                        <div class="partnership-card">
                            <div class="partnership-icon">
                                <i class="fas fa-lightbulb"></i>
                            </div>
                            <div class="partnership-title">Startups</div>
                            <div class="partnership-desc">Innovation & tech</div>
                        </div>
                        <div class="partnership-card">
                            <div class="partnership-icon">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="partnership-title">ONG</div>
                            <div class="partnership-desc">Projets sociaux</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="content-grid">
                    <!-- Sidebar -->
                    <aside class="sidebar">
                        <div class="info-box">
                            <h3>Avantages</h3>
                            <ul>
                                <li>Accès à des talents qualifiés</li>
                                <li>Visibilité accrue</li>
                                <li>Projets collaboratifs</li>
                                <li>Innovation partagée</li>
                                <li>Réseau étendu</li>
                            </ul>
                        </div>

                        <div class="info-box">
                            <h3>Types de collaboration</h3>
                            <ul>
                                <li>Recrutement de talents</li>
                                <li>Offres de stages</li>
                                <li>Projets collaboratifs</li>
                                <li>Masterclass</li>
                                <li>Sponsoring</li>
                            </ul>
                        </div>

                        <div class="info-box">
                            <h3>Contact</h3>
                            <div class="contact-info">
                                <strong>Email:</strong><br>
                                info@ecolevirtuelledescreatifs.com<br><br>
                                <strong>Téléphone:</strong><br>
                                +225 07 47 25 95 07
                            </div>
                        </div>
                    </aside>

                    <!-- Form -->
                    <div class="form-card">
                        <div class="form-header">
                            <h2>Demande de partenariat</h2>
                            <p>Remplissez ce formulaire pour initier une collaboration</p>
                        </div>

                        @if(session('success') || request('success'))
                            <div class="alert-success">
                                <i class="fas fa-check-circle"></i>
                                {{ session('success') ?? 'Votre demande de partenariat a été envoyée avec succès ! Nous vous contacterons bientôt.' }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <div>
                                    <strong>Erreur :</strong> Veuillez corriger les champs suivants.
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('rejoignez-nous.partenaire.submit') }}" method="POST">
                            @csrf

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nom de l'organisation <span class="required">*</span></label>
                                    <input type="text" name="organisation" class="form-control @error('organisation') error @enderror" placeholder="Nom de votre structure" value="{{ old('organisation') }}" required>
                                    @error('organisation')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nom du contact <span class="required">*</span></label>
                                    <input type="text" name="nom_contact" class="form-control @error('nom_contact') error @enderror" placeholder="Votre nom" value="{{ old('nom_contact') }}" required>
                                    @error('nom_contact')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') error @enderror" placeholder="contact@exemple.com" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Téléphone <span class="required">*</span></label>
                                    <input type="tel" name="telephone" class="form-control @error('telephone') error @enderror" placeholder="+225 XX XX XX XX" value="{{ old('telephone') }}" required>
                                    @error('telephone')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Site web (optionnel)</label>
                                <input type="url" name="site_web" class="form-control @error('site_web') error @enderror" placeholder="https://..." value="{{ old('site_web') }}">
                                @error('site_web')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Type de partenariat <span class="required">*</span></label>
                                    <select name="type_partenariat" class="form-select @error('type_partenariat') error @enderror" required>
                                        <option value="">Sélectionnez</option>
                                        <option value="recrutement" {{ old('type_partenariat') == 'recrutement' ? 'selected' : '' }}>Recrutement de talents</option>
                                        <option value="stages" {{ old('type_partenariat') == 'stages' ? 'selected' : '' }}>Offres de stages</option>
                                        <option value="projets" {{ old('type_partenariat') == 'projets' ? 'selected' : '' }}>Projets collaboratifs</option>
                                        <option value="masterclass" {{ old('type_partenariat') == 'masterclass' ? 'selected' : '' }}>Interventions / Masterclass</option>
                                        <option value="sponsoring" {{ old('type_partenariat') == 'sponsoring' ? 'selected' : '' }}>Parrainage / Sponsoring</option>
                                        <option value="ressources" {{ old('type_partenariat') == 'ressources' ? 'selected' : '' }}>Mise à disposition de ressources</option>
                                        <option value="autre" {{ old('type_partenariat') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('type_partenariat')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Secteur d'activité <span class="required">*</span></label>
                                    <input type="text" name="secteur" class="form-control @error('secteur') error @enderror" placeholder="Ex: Technologie, Marketing..." value="{{ old('secteur') }}" required>
                                    @error('secteur')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Décrivez votre projet <span class="required">*</span></label>
                                <textarea name="message" class="form-control @error('message') error @enderror" placeholder="Présentez votre structure et vos objectifs de partenariat..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane"></i>
                                <span>Envoyer la demande</span>
                            </button>
                        </form>
                    </div>
                </div>
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
    const form = document.querySelector('form[action*="partenaire"]');
    const submitBtn = document.querySelector('.submit-btn');
    const loadingOverlay = document.getElementById('loadingOverlay');

    if (form && submitBtn && loadingOverlay) {
        form.addEventListener('submit', function(e) {
            // Vérifier si le formulaire est valide
            if (form.checkValidity()) {
                // Afficher le loader
                loadingOverlay.classList.add('active');
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;

                // Changer le texte du bouton
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
