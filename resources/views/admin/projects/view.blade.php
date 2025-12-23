@extends('layouts.admin')

@section('title', 'Détails du Projet - ' . $project->title)

@section('content')
<div class="container-fluid">
    <!-- Header avec retour -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-white mb-1">
                        <i class="fas fa-eye me-2"></i>Détails du Projet
                    </h2>
                    <p class="text-white-50 mb-0">Visualisation complète du projet étudiant</p>
                </div>
                <a href="{{ url()->previous() }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Succès !</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="errorAlert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Erreur !</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <script>
    // Auto-dismiss flash messages après 4 secondes
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        if (successAlert) {
            setTimeout(() => {
                const alert = new bootstrap.Alert(successAlert);
                alert.close();
            }, 4000);
        }

        if (errorAlert) {
            setTimeout(() => {
                const alert = new bootstrap.Alert(errorAlert);
                alert.close();
            }, 6000);
        }
    });

    // 📧 MODALE DE LOADING AVANCÉE POUR EMAILS
    function showEmailLoadingModal(action = 'validation') {
        hideEmailLoadingModal(); // Supprimer toute modale existante

        const actionText = action === 'validation' ? 'Validation' : 'Suppression';
        const actionIcon = action === 'validation' ? '✅' : '🗑️';
        const actionColor = action === 'validation' ? '#28a745' : '#dc3545';

        const loadingModal = document.createElement('div');
        loadingModal.id = 'emailLoadingModal';
        loadingModal.className = 'email-loading-modal';
        loadingModal.innerHTML = `
            <div class="email-loading-overlay">
                <div class="email-loading-content">
                    <div class="email-loading-header">
                        <div class="email-loading-icon" style="color: ${actionColor}">
                            ${actionIcon}
                        </div>
                        <h4 class="email-loading-title">${actionText} en cours...</h4>
                    </div>

                    <div class="email-loading-steps">
                        <div class="email-step active" id="step1">
                            <div class="step-icon">⚡</div>
                            <div class="step-text">Traitement du projet</div>
                            <div class="step-spinner">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div>
                        </div>

                        <div class="email-step" id="step2">
                            <div class="step-icon">📧</div>
                            <div class="step-text">Envoi de l'email</div>
                            <div class="step-spinner">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div>
                        </div>

                        <div class="email-step" id="step3">
                            <div class="step-icon">✨</div>
                            <div class="step-text">Finalisation</div>
                            <div class="step-spinner">
                                <div class="spinner-border spinner-border-sm" role="status"></div>
                            </div>
                        </div>
                    </div>

                    <div class="email-loading-progress">
                        <div class="progress-bar" id="emailProgressBar"></div>
                    </div>

                    <div class="email-loading-message">
                        <small class="text-muted">L'étudiant sera notifié par email...</small>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(loadingModal);

        // Animation d'apparition
        setTimeout(() => {
            loadingModal.classList.add('show');
            simulateEmailSteps();
        }, 10);
    }

    function simulateEmailSteps() {
        const steps = ['step1', 'step2', 'step3'];
        const progressBar = document.getElementById('emailProgressBar');
        let currentStep = 0;

        function activateNextStep() {
            if (currentStep > 0) {
                // Marquer l'étape précédente comme terminée
                const prevStep = document.getElementById(steps[currentStep - 1]);
                if (prevStep) {
                    prevStep.classList.remove('active');
                    prevStep.classList.add('completed');
                    prevStep.querySelector('.step-spinner').innerHTML = '<i class="fas fa-check text-success"></i>';
                }
            }

            if (currentStep < steps.length) {
                // Activer l'étape actuelle
                const currentStepEl = document.getElementById(steps[currentStep]);
                if (currentStepEl) {
                    currentStepEl.classList.add('active');
                }

                // Mettre à jour la barre de progression
                const progress = ((currentStep + 1) / steps.length) * 100;
                if (progressBar) {
                    progressBar.style.width = progress + '%';
                }

                currentStep++;

                if (currentStep <= steps.length) {
                    setTimeout(activateNextStep, 800);
                }
            }
        }

        activateNextStep();
    }

    function hideEmailLoadingModal() {
        const loadingModal = document.getElementById('emailLoadingModal');
        if (loadingModal) {
            loadingModal.classList.remove('show');
            setTimeout(() => {
                loadingModal.remove();
            }, 300);
        }
    }

    // 🎯 GESTION SOUMISSION VALIDATION AVEC EMAIL
    function handleValidationSubmit(event) {
        if (!confirm('Voulez-vous valider ce projet et envoyer un email à l\'étudiant ?')) {
            event.preventDefault();
            return false;
        }

        // Afficher la modale de loading pour validation
        showEmailLoadingModal('validation');

        // Laisser le formulaire se soumettre normalement
        return true;
    }

    // 🗑️ GESTION SOUMISSION SUPPRESSION AVEC EMAIL
    function handleDeleteSubmit(event) {
        if (!confirm('⚠️ ATTENTION: Cette action est irréversible!\n\nÊtes-vous absolument sûr de vouloir supprimer définitivement ce projet et tous ses fichiers associés ?\n\nL\'étudiant sera notifié par email.')) {
            event.preventDefault();
            return false;
        }

        // Afficher la modale de loading pour suppression
        showEmailLoadingModal('suppression');

        // Laisser le formulaire se soumettre normalement
        return true;
    }
    </script>

    <style>
    /* 📧 STYLES MODALE EMAIL LOADING AVANCÉE */
    .email-loading-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .email-loading-modal.show {
        opacity: 1;
        visibility: visible;
    }

    .email-loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(8px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .email-loading-content {
        background: linear-gradient(135deg,
            rgba(255, 255, 255, 0.98) 0%,
            rgba(248, 249, 250, 0.98) 100%);
        padding: 2.5rem;
        border-radius: 25px;
        text-align: center;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.4);
        min-width: 400px;
        max-width: 500px;
        transform: scale(0.8);
        transition: transform 0.3s ease;
    }

    .email-loading-modal.show .email-loading-content {
        transform: scale(1);
    }

    .email-loading-header {
        margin-bottom: 2rem;
    }

    .email-loading-icon {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        animation: pulse 2s infinite;
    }

    .email-loading-title {
        color: #333;
        font-weight: 600;
        margin: 0;
    }

    .email-loading-steps {
        margin: 2rem 0;
    }

    .email-step {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin: 0.5rem 0;
        border-radius: 15px;
        background: rgba(248, 249, 250, 0.8);
        transition: all 0.3s ease;
        opacity: 0.5;
    }

    .email-step.active {
        opacity: 1;
        background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .email-step.completed {
        opacity: 1;
        background: linear-gradient(135deg, #e8f5e8, #f0f8f0);
    }

    .step-icon {
        font-size: 1.5rem;
        margin-right: 1rem;
        min-width: 40px;
    }

    .step-text {
        flex: 1;
        text-align: left;
        font-weight: 500;
        color: #495057;
    }

    .step-spinner {
        margin-left: 1rem;
    }

    .email-loading-progress {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        height: 8px;
        margin: 1.5rem 0;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #007bff, #0056b3);
        border-radius: 10px;
        width: 0%;
        transition: width 0.8s ease;
    }

    .email-loading-message {
        margin-top: 1rem;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    </style>

    <!-- Projet Principal -->
    <div class="row">
        <div class="col-12">
            <!-- Carte Principale du Projet -->
            <div class="dashboard-card text-white mb-4">
                <div class="card-header border-secondary d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="text-primary mb-1">
                            <i class="fas fa-project-diagram me-2"></i>{{ $project->title }}
                        </h3>
                        <small class="text-white-50">ID: #{{ $project->id }}</small>
                    </div>
                    @php
                        $statusClass = match($project->status) {
                            'valide' => 'bg-success',
                            'en_cours' => 'bg-warning',
                            'termine' => 'bg-info',
                            'rejete' => 'bg-danger',
                            default => 'bg-secondary'
                        };

                        $statusIcon = match($project->status) {
                            'valide' => 'fas fa-check-circle',
                            'en_cours' => 'fas fa-clock',
                            'termine' => 'fas fa-flag-checkered',
                            'rejete' => 'fas fa-times-circle',
                            default => 'fas fa-info-circle'
                        };

                        $statusLabel = match($project->status) {
                            'valide' => 'Validé',
                            'en_cours' => 'Pas encore Fait',
                            'termine' => 'Terminé',
                            'rejete' => 'Rejeté',
                            default => 'Inconnu'
                        };
                    @endphp
                    <span class="badge {{ $statusClass }} fs-6 px-3 py-2">
                        <i class="{{ $statusIcon }} me-2"></i>{{ $statusLabel }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informations Principales -->
                        <div class="col-lg-8">
                            @if($project->description)
                                <div class="mb-4">
                                    <h5 class="text-white mb-3">
                                        <i class="fas fa-align-left me-2"></i>Description
                                    </h5>
                                    <div class="p-3 rounded" style="background: rgba(255,255,255,0.1); border-left: 4px solid #007bff;">
                                        <p class="text-white-50 mb-0">{{ $project->description }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($project->software_used)
                                <div class="mb-4">
                                    <h5 class="text-white mb-3">
                                        <i class="fas fa-tools me-2"></i>Logiciels utilisés
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2">
                                        @php
                                            $softwareList = is_array($project->software_used)
                                                ? $project->software_used
                                                : explode(',', $project->software_used);
                                        @endphp
                                        @foreach($softwareList as $software)
                                            <span class="badge bg-primary text-white px-3 py-2">
                                                <i class="fas fa-cog me-1"></i>{{ trim($software) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($project->link)
                                <div class="mb-4">
                                    <h5 class="text-white mb-3">
                                        <i class="fas fa-link me-2"></i>Lien du projet
                                    </h5>
                                    <a href="{{ $project->link }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt me-2"></i>{{ $project->link }}
                                    </a>
                                </div>
                            @endif
                        </div>

                        <!-- Métadonnées -->
                        <div class="col-lg-4">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.05);">
                                <h6 class="text-white mb-3">
                                    <i class="fas fa-info-circle me-2"></i>Métadonnées
                                </h6>

                                <div class="mb-3">
                                    <small class="text-white-50 d-block">Créé le</small>
                                    <span class="text-white">{{ $project->created_at->format('d/m/Y à H:i') }}</span>
                                </div>

                                <div class="mb-3">
                                    <small class="text-white-50 d-block">Modifié le</small>
                                    <span class="text-white">{{ $project->updated_at->format('d/m/Y à H:i') }}</span>
                                </div>

                                <div class="mb-3">
                                    <small class="text-white-50 d-block">Étudiant</small>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            @php
                                                $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($project->user->profile_photo ?? null);
                                            @endphp
                                            @if($photoUrl)
                                                <img src="{{ $photoUrl }}"
                                                     alt="Photo de profil"
                                                     class="rounded-circle"
                                                     style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-white">{{ $project->user->name ?? 'Utilisateur' }}</div>
                                            <small class="text-white-50">{{ $project->user->email }}</small>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.students.profile', $project->user->id) }}" class="btn btn-outline-info btn-sm mt-2 w-100">
                                        <i class="fas fa-user me-1"></i>Voir le profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Images et Fichiers du Projet -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card text-white mb-4">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">
                        <i class="fas fa-folder-open me-2"></i>Fichiers du projet
                        <span class="badge bg-primary ms-2">{{ $project->images->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @if($project->images->count() > 0)
                        <div class="row">
                            @foreach($project->images as $image)
                                <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
                                    <div class="card bg-dark border-secondary h-100">
                                        <div class="position-relative">
                                            @php
                                                $filePath = $image->file_path ?? $image->path ?? '';
                                                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                                $isPdf = $extension === 'pdf';
                                            @endphp

                                            @if($isImage)
                                                <img src="{{ \App\Models\MediaUrl::fromPath($filePath) }}"
                                                     alt="{{ $image->filename ?? 'Image du projet' }}"
                                                     class="card-img-top"
                                                     style="height: 120px; object-fit: cover; cursor: pointer;">
                                            @elseif($isPdf)
                                                <div class="card-img-top d-flex align-items-center justify-content-center bg-danger" style="height: 120px;">
                                                    <i class="fas fa-file-pdf fa-2x text-white"></i>
                                                </div>
                                            @else
                                                <div class="card-img-top d-flex align-items-center justify-content-center bg-secondary" style="height: 120px;">
                                                    <i class="fas fa-file fa-2x text-white"></i>
                                                </div>
                                            @endif

                                            <div class="position-absolute top-0 end-0 m-1">
                                                <span class="badge bg-dark bg-opacity-75 small">
                                                    {{ strtoupper($extension) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="card-body p-2">
                                            <h6 class="card-title text-white text-truncate small mb-1" title="{{ $image->filename ?? 'Fichier' }}">
                                                {{ $image->filename ?? 'Fichier' }}
                                            </h6>
                                            <p class="card-text text-white-50 small mb-2">
                                                {{ $image->created_at->format('d/m/Y') }}
                                            </p>
                                        </div>

                                        <div class="card-footer bg-transparent border-secondary p-2">
                                            <div class="d-flex justify-content-center gap-1">
                                                <a href="{{ \App\Models\MediaUrl::fromPath($filePath) }}"
                                                   target="_blank"
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ \App\Models\MediaUrl::fromPath($filePath) }}"
                                                   download
                                                   class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                            <h5 class="text-white-50">Aucun fichier</h5>
                            <p class="text-muted">Aucun fichier n'est associé à ce projet pour le moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions sur une seule ligne -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card text-white">
                <div class="card-header border-secondary">
                    <h5 class="mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions disponibles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>

                        @if($project->status !== 'valide')
                            <form action="{{ route('admin.projects.validate', $project->id) }}" method="POST" class="d-inline" onsubmit="return handleValidationSubmit(event)">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check me-2"></i>Valider
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.students.profile', $project->user->id) }}" class="btn btn-info">
                            <i class="fas fa-user me-2"></i>Voir l'étudiant
                        </a>

                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Retour au dashboard
                        </a>

                        <form action="{{ route('admin.projects.assigned.delete', $project->id) }}" method="POST" class="d-inline" onsubmit="return handleDeleteSubmit(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-2"></i>Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
