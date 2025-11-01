@extends('layouts.app')

@section('title', 'Devenir Collaborateur - École Virtuelle des Créatifs')
@section('description', 'Rejoignez l\'équipe de l\'École Virtuelle des Créatifs')
@section('keywords', 'emploi evc, recrutement evc abidjan')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #1e3c72;
        --primary-light: #2a5298;
        --primary-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        --accent: #4fc3f7;
        --accent-dark: #29b6f6;
        --bg-dark: #0a0e1a;
        --bg-card: #1a1f35;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --border: #2a3f5f;
        --glow-blue: rgba(79, 195, 247, 0.3);
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
        background: rgba(79, 195, 247, 0.1);
        border: 1px solid rgba(79, 195, 247, 0.3);
        border-radius: 20px;
        color: var(--accent);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
        box-shadow: 0 0 20px var(--glow-blue);
    }

    .header h1 {
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 16px;
        letter-spacing: -1px;
        background: linear-gradient(135deg, var(--accent) 0%, var(--primary-light) 100%);
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

    /* Stats */
    .stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 60px;
    }

    .stat-card {
        background: var(--primary-gradient);
        border: 1px solid var(--accent);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        transition: all 0.3s;
        box-shadow: 0 4px 20px rgba(30, 60, 114, 0.3);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(79, 195, 247, 0.2), transparent);
        transition: left 0.5s;
    }

    .stat-card:hover::before {
        left: 100%;
    }

    .stat-card:hover {
        border-color: var(--accent);
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 8px 30px var(--glow-blue);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: white;
        margin-bottom: 8px;
        text-shadow: 0 2px 10px rgba(79, 195, 247, 0.5);
    }

    .stat-label {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.9);
        font-weight: 500;
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
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(30, 60, 114, 0.2);
        transition: all 0.3s;
    }

    .info-box:hover {
        border-color: var(--accent);
        box-shadow: 0 6px 25px var(--glow-blue);
        transform: translateX(5px);
    }

    .info-box h3 {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 16px;
        color: var(--accent);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .info-box h3::before {
        content: '';
        width: 4px;
        height: 20px;
        background: var(--primary-gradient);
        border-radius: 2px;
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
        content: "●";
        position: absolute;
        left: 0;
        color: var(--accent);
        font-weight: bold;
        font-size: 12px;
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
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 8px 30px rgba(30, 60, 114, 0.3);
        position: relative;
        overflow: hidden;
    }

    .form-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .form-header {
        margin-bottom: 32px;
    }

    .form-header h2 {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        color: var(--accent);
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
        color: var(--accent);
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
        border-color: var(--accent);
        background: var(--bg-dark);
        box-shadow: 0 0 0 3px var(--glow-blue);
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
        border-color: var(--accent);
        background: rgba(79, 195, 247, 0.05);
    }

    .file-upload.drag-over {
        border-color: var(--accent);
        background: rgba(79, 195, 247, 0.1);
        box-shadow: 0 0 20px var(--glow-blue);
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
        color: var(--accent);
        margin-bottom: 12px;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
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
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 4px 20px rgba(30, 60, 114, 0.4);
        position: relative;
        overflow: hidden;
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .submit-btn:hover {
        background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 30px var(--glow-blue);
    }

    .submit-btn:active {
        transform: translateY(-1px);
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
        background: rgba(10, 14, 26, 0.95);
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
        border: 4px solid rgba(30, 60, 114, 0.2);
        border-top: 4px solid #1e3c72;
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

        .stats {
            grid-template-columns: repeat(2, 1fr);
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

        .stats {
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
                    <span class="header-badge">OPPORTUNITÉS DE CARRIÈRE</span>
                    <h1>Rejoignez notre équipe</h1>
                    <p>Participez à la transformation de l'éducation digitale en Afrique</p>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="stats">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Collaborateurs</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">200+</div>
                        <div class="stat-label">Projets</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Étudiants</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Satisfaction</div>
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
                            <h3>Pourquoi nous rejoindre ?</h3>
                            <ul>
                                <li>Environnement innovant</li>
                                <li>Évolution de carrière</li>
                                <li>Équipe dynamique</li>
                                <li>Flexibilité</li>
                                <li>Impact social</li>
                            </ul>
                        </div>

                        <div class="info-box">
                            <h3>Profils recherchés</h3>
                            <ul>
                                <li>Développeurs Web/Mobile</li>
                                <li>Designers UI/UX</li>
                                <li>Community Managers</li>
                                <li>Marketing Digital</li>
                                <li>Assistants Admin</li>
                            </ul>
                        </div>

                        <div class="info-box">
                            <h3>Contact</h3>
                            <div class="contact-info">
                                <strong>Email:</strong><br>
                                recrutement@evc.ci<br><br>
                                <strong>Téléphone:</strong><br>
                                +225 XX XX XX XX
                            </div>
                        </div>
                    </aside>

                    <!-- Form -->
                    <div class="form-card">
                        <div class="form-header">
                            <h2>Formulaire de candidature</h2>
                            <p>Remplissez ce formulaire pour postuler à un poste</p>
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
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('rejoignez-nous.collaborateur.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Prénom <span class="required">*</span></label>
                                    <input type="text" name="prenom" class="form-control @error('prenom') error @enderror" placeholder="Votre prénom" value="{{ old('prenom') }}" required>
                                    @error('prenom')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nom <span class="required">*</span></label>
                                    <input type="text" name="nom" class="form-control @error('nom') error @enderror" placeholder="Votre nom" value="{{ old('nom') }}" required>
                                    @error('nom')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') error @enderror" placeholder="votre@email.com" value="{{ old('email') }}" required>
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

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Poste souhaité <span class="required">*</span></label>
                                    <select name="poste" class="form-select @error('poste') error @enderror" required>
                                        <option value="">Sélectionnez</option>
                                        <option value="developpeur_web" {{ old('poste') == 'developpeur_web' ? 'selected' : '' }}>Développeur Web</option>
                                        <option value="developpeur_mobile" {{ old('poste') == 'developpeur_mobile' ? 'selected' : '' }}>Développeur Mobile</option>
                                        <option value="designer_graphique" {{ old('poste') == 'designer_graphique' ? 'selected' : '' }}>Designer Graphique</option>
                                        <option value="community_manager" {{ old('poste') == 'community_manager' ? 'selected' : '' }}>Community Manager</option>
                                        <option value="responsable_marketing" {{ old('poste') == 'responsable_marketing' ? 'selected' : '' }}>Responsable Marketing</option>
                                        <option value="assistant_administratif" {{ old('poste') == 'assistant_administratif' ? 'selected' : '' }}>Assistant Administratif</option>
                                        <option value="autre" {{ old('poste') == 'autre' ? 'selected' : '' }}>Autre</option>
                                    </select>
                                    @error('poste')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Expérience <span class="required">*</span></label>
                                    <select name="experience" class="form-select @error('experience') error @enderror" required>
                                        <option value="">Sélectionnez</option>
                                        <option value="0-1" {{ old('experience') == '0-1' ? 'selected' : '' }}>Moins d'1 an</option>
                                        <option value="1-3" {{ old('experience') == '1-3' ? 'selected' : '' }}>1 à 3 ans</option>
                                        <option value="3-5" {{ old('experience') == '3-5' ? 'selected' : '' }}>3 à 5 ans</option>
                                        <option value="5+" {{ old('experience') == '5+' ? 'selected' : '' }}>Plus de 5 ans</option>
                                    </select>
                                    @error('experience')
                                        <span class="error-text">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Lettre de motivation <span class="required">*</span></label>
                                <textarea name="message" class="form-control @error('message') error @enderror" placeholder="Parlez-nous de vous et de vos motivations..." required>{{ old('message') }}</textarea>
                                @error('message')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">CV (PDF, max 2MB) <span class="required">*</span></label>
                                <div class="file-upload" id="fileUpload">
                                    <input type="file" name="cv" id="cvFile" accept=".pdf" required>
                                    <div class="file-upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <div class="file-upload-text">Glissez votre CV ici</div>
                                    <div class="file-upload-hint">ou cliquez pour parcourir</div>
                                </div>
                                <div class="file-name" id="fileName"></div>
                                @error('cv')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Portfolio / LinkedIn (optionnel)</label>
                                <input type="url" name="portfolio" class="form-control @error('portfolio') error @enderror" placeholder="https://..." value="{{ old('portfolio') }}">
                                @error('portfolio')
                                    <span class="error-text">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="submit-btn">
                                <i class="fas fa-paper-plane"></i>
                                <span>Envoyer ma candidature</span>
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
    // Gestion du loader lors de la soumission du formulaire
    const form = document.querySelector('form[action*="collaborateur"]');
    const submitBtn = form ? form.querySelector('.submit-btn') : null;
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

    // Gestion du drag & drop pour le CV
    const fileUpload = document.getElementById('fileUpload');
    const fileInput = document.getElementById('cvFile');
    const fileName = document.getElementById('fileName');

    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files[0]) {
            showFileName(e.target.files[0].name);
        }
    });

    // Drag & drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUpload.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUpload.addEventListener(eventName, () => fileUpload.classList.add('drag-over'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUpload.addEventListener(eventName, () => fileUpload.classList.remove('drag-over'), false);
    });

    fileUpload.addEventListener('drop', function(e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            showFileName(files[0].name);
        }
    });

    function showFileName(name) {
        fileName.textContent = `✓ ${name}`;
        fileName.classList.add('show');
    }
});
</script>
@endpush
