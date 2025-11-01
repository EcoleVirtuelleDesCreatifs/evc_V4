@extends('layouts.app')

@section('title', 'Pré-inscription - École Virtuelle des Créatifs')
@section('description', 'Inscrivez-vous à l\'École Virtuelle des Créatifs et transformez votre passion en carrière')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #1e3c72;
        --primary-light: #2a5298;
        --accent: #4fc3f7;
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
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Header */
    .header {
        text-align: center;
        margin-bottom: 50px;
    }

    .header-badge {
        display: inline-block;
        padding: 8px 20px;
        background: rgba(30, 60, 114, 0.1);
        border: 1px solid rgba(30, 60, 114, 0.3);
        border-radius: 20px;
        color: var(--accent);
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
    }

    .header h1 {
        font-size: 42px;
        font-weight: 700;
        margin-bottom: 16px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header p {
        font-size: 16px;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
    }

    /* Form Container */
    .form-container {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border);
        max-height: 85vh;
        overflow-y: auto;
    }

    /* Custom Scrollbar */
    .form-container::-webkit-scrollbar {
        width: 8px;
    }

    .form-container::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.5);
        border-radius: 10px;
    }

    .form-container::-webkit-scrollbar-thumb {
        background: var(--accent);
        border-radius: 10px;
    }

    /* Progress Bar */
    .progress-container {
        margin-bottom: 30px;
        position: sticky;
        top: 0;
        background: var(--bg-card);
        padding-bottom: 15px;
        z-index: 10;
    }

    .progress-bar {
        width: 100%;
        height: 6px;
        background: rgba(79, 195, 247, 0.1);
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 10px;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary) 0%, var(--accent) 100%);
        border-radius: 10px;
        transition: width 0.3s ease;
    }

    .progress-text {
        text-align: center;
        color: var(--accent);
        font-size: 13px;
        font-weight: 600;
    }

    /* Form Sections */
    .form-section {
        margin-bottom: 35px;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 18px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border);
        position: sticky;
        top: 60px;
        background: var(--bg-card);
        z-index: 9;
    }

    .section-title i {
        color: var(--accent);
        font-size: 20px;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-secondary);
        margin-bottom: 8px;
    }

    .form-input, .form-select {
        width: 100%;
        padding: 12px 16px;
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 14px;
        transition: all 0.3s ease;
        font-family: 'Inter', sans-serif;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(79, 195, 247, 0.1);
    }

    .form-input::placeholder {
        color: #64748b;
    }

    textarea.form-input {
        min-height: 100px;
        resize: vertical;
    }

    /* Submit Button */
    .submit-btn {
        width: 100%;
        padding: 16px 32px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        box-shadow: 0 4px 16px rgba(79, 195, 247, 0.3);
        position: sticky;
        bottom: 0;
        margin-top: 30px;
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(79, 195, 247, 0.4);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    /* Alerts */
    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        font-size: 14px;
    }

    .alert-success {
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #22c55e;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #ef4444;
    }

    .alert ul {
        margin-top: 8px;
        padding-left: 20px;
    }

    /* Checkboxes */
    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: var(--accent);
        cursor: pointer;
    }

    .checkbox-label {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: var(--text-secondary);
        font-size: 14px;
        margin-bottom: 12px;
        cursor: pointer;
    }

    .checkbox-label:hover {
        color: var(--text-primary);
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
        text-align: center;
    }

    .loader-subtext {
        color: #94a3b8;
        font-size: 14px;
        margin-top: 8px;
        text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-container {
            padding: 140px 16px 60px;
        }

        .header h1 {
            font-size: 32px;
        }

        .form-container {
            padding: 24px;
        }

        .section-title {
            font-size: 16px;
        }
    }
</style>
@endpush

@section('content')
<div class="page-container">
    <div class="header">
        <div class="header-badge">
            <i class="fas fa-graduation-cap"></i> REJOIGNEZ-NOUS
        </div>
        <h1>Pré-inscription</h1>
        <p>Transformez votre passion en carrière. Remplissez le formulaire ci-dessous pour démarrer votre parcours à l'EVC.</p>
    </div>

    <div class="form-container">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle" style="margin-top: 2px;"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle" style="margin-top: 2px;"></i>
                <div>
                    <strong>Veuillez corriger les erreurs suivantes :</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <!-- Progress Bar -->
        <div class="progress-container">
            <div class="progress-bar">
                <div class="progress-fill" id="progressBar" style="width: 0%"></div>
            </div>
            <div class="progress-text" id="progressText">Progression: 0%</div>
        </div>

        <form action="/candidature" method="POST" enctype="multipart/form-data" id="preinscriptionForm">
            @csrf
            @include('preinscription._form_fields_new')
        </form>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div>
        <div class="loader"></div>
        <div class="loader-text">Envoi en cours...</div>
        <div class="loader-subtext">Merci de patienter</div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('preinscriptionForm');
    const submitBtn = form.querySelector('.submit-btn');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    
    // Gestion du loader lors de la soumission
    if (form && submitBtn && loadingOverlay) {
        form.addEventListener('submit', function(e) {
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

    // Calcul de la progression du formulaire
    function updateProgress() {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        let filled = 0;
        
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                if (input.checked) filled++;
            } else if (input.type === 'file') {
                if (input.files.length > 0) filled++;
            } else if (input.value.trim() !== '') {
                filled++;
            }
        });
        
        const percentage = Math.round((filled / inputs.length) * 100);
        progressBar.style.width = percentage + '%';
        progressText.textContent = 'Progression: ' + percentage + '%';
    }

    // Écouter les changements dans le formulaire
    form.addEventListener('input', updateProgress);
    form.addEventListener('change', updateProgress);

    // Initialiser la progression au chargement
    updateProgress();
});
</script>
@endpush
