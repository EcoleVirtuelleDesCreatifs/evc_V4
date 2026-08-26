@extends('layouts.app')

@section('title', 'Devenir Formateur - École Virtuelle des Créatifs')
@section('description', 'Devenez formateur à l\'École Virtuelle des Créatifs')
@section('keywords', 'formateur evc, enseigner école virtuelle abidjan')

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

    /* Stats */
    .stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 60px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        transition: all 0.3s;
    }

    .stat-card:hover {
        border-color: var(--primary);
        transform: translateY(-4px);
    }

    .stat-number {
        font-size: 32px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .stat-label {
        font-size: 14px;
        color: var(--text-secondary);
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
                    <span class="header-badge">ENSEIGNEMENT & FORMATION</span>
                    <h1>Devenez formateur</h1>
                    <p>Partagez votre expertise et formez la nouvelle génération de créatifs africains</p>
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
                            <h3>Pourquoi enseigner ?</h3>
                            <ul>
                                <li>Transmission de savoir</li>
                                <li>Rémunération attractive</li>
                                <li>Flexibilité horaire</li>
                                <li>Impact social</li>
                                <li>Réseau professionnel</li>
                            </ul>
                        </div>

                        <div class="info-box">
                            <h3>Domaines recherchés</h3>
                            <ul>
                                <li>Design Graphique</li>
                                <li>Community Management</li>
                                <li>Développement Web</li>
                                <li>Intelligence Artificielle</li>
                                <li>Marketing Digital</li>
                            </ul>
                        </div>

                        <div class="info-box">
                            <h3>Contact</h3>
                            <div class="contact-info">
                                <strong>Email:</strong><br>
                                formateurs@evc.ci<br><br>
                                <strong>Téléphone:</strong><br>
                                +225 XX XX XX XX
                            </div>
                        </div>
                    </aside>

                    <!-- Form -->
                    <div class="form-card">
                        <div class="form-header">
                            <h2>Candidature formateur</h2>
                            <p>Remplissez ce formulaire pour rejoindre notre équipe pédagogique</p>
                        </div>

                        @if(session('success') || request('success'))
                            <div class="alert-success">
                                <i class="fas fa-check-circle"></i>
                                {{ session('success') ?? 'Votre candidature a été envoyée avec succès ! Nous vous contacterons bientôt.' }}
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

                        <form action="{{ route('rejoignez-nous.formateur.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Prénom <span class="required">*</span></label>
                                    <input type="text" name="prenom" class="form-control" placeholder="Votre prénom" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nom <span class="required">*</span></label>
                                    <input type="text" name="nom" class="form-control" placeholder="Votre nom" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Email <span class="required">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="votre@email.com" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Téléphone <span class="required">*</span></label>
                                    <input type="tel" name="telephone" class="form-control" placeholder="+225 XX XX XX XX" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Domaine d'expertise <span class="required">*</span></label>
                                    <select name="domaine" class="form-select" required>
                                        <option value="">Sélectionnez</option>
                                        <option value="design_graphique">Design Graphique</option>
                                        <option value="community_management">Community Management</option>
                                        <option value="developpement_web">Développement Web</option>
                                        <option value="intelligence_artificielle">Intelligence Artificielle</option>
                                        <option value="marketing_digital">Marketing Digital</option>
                                        <option value="gestion_projet">Gestion de Projet</option>
                                        <option value="autre">Autre</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Années d'expérience <span class="required">*</span></label>
                                    <select name="experience" class="form-select" required>
                                        <option value="">Sélectionnez</option>
                                        <option value="1-3">1 à 3 ans</option>
                                        <option value="3-5">3 à 5 ans</option>
                                        <option value="5-10">5 à 10 ans</option>
                                        <option value="10+">Plus de 10 ans</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Diplômes et certifications <span class="required">*</span></label>
                                <textarea name="diplomes" class="form-control" placeholder="Listez vos diplômes et certifications pertinents..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Motivation <span class="required">*</span></label>
                                <textarea name="motivation" class="form-control" placeholder="Pourquoi souhaitez-vous devenir formateur chez EVC ?" required></textarea>
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
                            </div>

                            <div class="form-group">
                                <label class="form-label">Portfolio / LinkedIn (optionnel)</label>
                                <input type="url" name="portfolio" class="form-control" placeholder="https://...">
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
    const form = document.querySelector('form[action*="formateur"]');
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
