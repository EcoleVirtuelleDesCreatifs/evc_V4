@extends('layouts.admin')

@section('title', 'Voir le TP - ' . $project->title)

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-eye text-primary me-2"></i>
                        Détails du TP
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.statistics.detail', 'tp') }}">Tous les TP</a></li>
                            <li class="breadcrumb-item active">{{ $project->title }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('admin.statistics.detail', 'tp') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>



    <!-- Informations du projet -->
    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Détails du projet -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations du Projet
                    </h6>
                    @if($project->status === 'validated')
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check-circle me-1"></i>Validé
                        </span>
                    @elseif($project->status === 'rejected')
                        <span class="badge bg-danger fs-6">
                            <i class="fas fa-times-circle me-1"></i>Rejeté
                        </span>
                    @else
                        <span class="badge bg-warning fs-6">
                            <i class="fas fa-clock me-1"></i>En attente
                        </span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Titre du TP</label>
                            <p class="fs-5 mb-0">{{ $project->title }}</p>
                        </div>
                        @if($project->description)
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Description</label>
                            <div class="mb-0">{!! $project->description !!}</div>
                        </div>
                        @endif
                        @if($project->link)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Lien</label>
                            <p class="mb-0">
                                <a href="{{ $project->link }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Voir le lien
                                </a>
                            </p>
                        </div>
                        @endif
                        @if($project->tags)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Tags</label>
                            <p class="mb-0">
                                @php
                                    $tags = is_array($project->tags) ? $project->tags : explode(',', $project->tags);
                                @endphp
                                @foreach($tags as $tag)
                                    <span class="badge bg-secondary me-1">{{ trim($tag) }}</span>
                                @endforeach
                            </p>
                        </div>
                        @endif
                        @if($project->software_used)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Logiciels utilisés</label>
                            <p class="mb-0">
                                @php
                                    $software_list = is_array($project->software_used) ? $project->software_used : explode(',', $project->software_used);
                                @endphp
                                @foreach($software_list as $software)
                                    <span class="badge bg-primary me-1">{{ trim($software) }}</span>
                                @endforeach
                            </p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Date de création</label>
                            <p class="mb-0">{{ date('d/m/Y à H:i', strtotime($project->created_at)) }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Dernière modification</label>
                            <p class="mb-0">{{ date('d/m/Y à H:i', strtotime($project->updated_at)) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fichiers du TP -->
            @if($project->files && $project->files->count() > 0)

                @php
                    // Fonction pour détecter si c'est une image
                    $isImage = function($file) {
                        // Vérifier d'abord le mime_type
                        if (isset($file->mime_type) && str_starts_with($file->mime_type, 'image/')) {
                            return true;
                        }

                        // Sinon, vérifier l'extension du fichier
                        $filePath = $file->file_path ?? $file->original_name ?? '';
                        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg', 'ico'];

                        return in_array($extension, $imageExtensions);
                    };

                    // Séparer les images des autres fichiers
                    $imageFiles = $project->files->filter($isImage);
                    $otherFiles = $project->files->filter(function($file) use ($isImage) {
                        return !$isImage($file);
                    });
                @endphp

                <!-- Galerie d'images -->
                @if($imageFiles->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-images me-2"></i>
                            Images du TP ({{ $imageFiles->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($imageFiles as $image)
                            <div class="col-md-4 col-sm-6">
                                <div class="position-relative image-card" style="height: 200px; overflow: hidden; border-radius: 8px; cursor: pointer; background: #f0f0f0;"
                                     onclick="viewImage('{{ asset($image->file_path) }}', '{{ $image->original_name }}')">
                                    <img src="{{ asset($image->file_path) }}"
                                         alt="{{ $image->original_name }}"
                                         class="w-100 h-100"
                                         style="object-fit: cover; transition: transform 0.3s;"
                                         onmouseover="this.style.transform='scale(1.05)'"
                                         onmouseout="this.style.transform='scale(1)'"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="d-none w-100 h-100 align-items-center justify-content-center flex-column" style="background: #f8f9fa;">
                                        <i class="fas fa-image text-muted fa-3x mb-2"></i>
                                        <small class="text-muted">Image indisponible</small>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                        <small class="text-white d-block text-truncate">{{ $image->original_name }}</small>
                                        <small class="text-white-50">{{ number_format($image->file_size / 1024, 0) }} KB</small>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <a href="{{ asset($image->file_path) }}"
                                           download="{{ $image->original_name }}"
                                           class="btn btn-sm btn-light"
                                           onclick="event.stopPropagation();"
                                           title="Télécharger">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Autres fichiers (non-images) -->
                @if($otherFiles->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-file me-2"></i>
                            Autres fichiers ({{ $otherFiles->count() }})
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            @foreach($otherFiles as $file)
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if(str_starts_with($file->mime_type, 'video/'))
                                            <i class="fas fa-video fa-2x text-danger"></i>
                                        @elseif(str_contains($file->mime_type, 'pdf'))
                                            <i class="fas fa-file-pdf fa-2x text-danger"></i>
                                        @else
                                            <i class="fas fa-file fa-2x text-secondary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $file->original_name }}</div>
                                        <small class="text-muted">
                                            {{ number_format($file->file_size / 1024, 2) }} KB
                                            • {{ $file->mime_type }}
                                        </small>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ asset($file->file_path) }}"
                                       download="{{ $file->original_name }}"
                                       class="btn btn-sm btn-primary"
                                       title="Télécharger">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>
                        Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($project->status === 'pending')
                            <form action="{{ route('admin.tp.validate', $project->id) }}" method="POST" class="mb-2" id="validateForm" onsubmit="return handleValidateSubmit(event)">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" id="validateBtn">
                                    <span id="validateBtnText">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Valider le TP
                                    </span>
                                    <span id="validateBtnLoading" style="display: none;">
                                        <i class="fas fa-spinner fa-spin me-2" style="color: #FFD700; animation: colorPulse 1.5s ease-in-out infinite;"></i>
                                        <strong style="color: #FFD700; text-shadow: 0 0 10px rgba(255,215,0,0.8); animation: pulse 2s ease-in-out infinite;">
                                            Validation en cours...
                                        </strong>
                                    </span>
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger w-100" onclick="openRejectModal()">
                                <i class="fas fa-times-circle me-2"></i>
                                Rejeter le TP
                            </button>
                        @elseif($project->status === 'validated')
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>TP validé</strong>
                                @if($project->validated_at)
                                    <br><small>Validé le {{ \Carbon\Carbon::parse($project->validated_at)->format('d/m/Y à H:i') }}</small>
                                @endif
                            </div>
                        @elseif($project->status === 'rejected')
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>TP rejeté</strong>
                                @if($project->admin_comment)
                                    <hr class="my-2">
                                    <small><strong>Commentaire :</strong> {{ $project->admin_comment }}</small>
                                @endif
                            </div>
                        @endif
                        <a href="{{ route('admin.statistics.detail', 'tp') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="fas fa-list me-2"></i>
                            Voir tous les TP
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal de rejet CUSTOM (sans Bootstrap) -->
<div id="customRejectModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); z-index: 999999; overflow-y: auto;">
    <div style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
        <div style="background: white; border-radius: 8px; max-width: 600px; width: 100%; box-shadow: 0 5px 15px rgba(0,0,0,0.3); position: relative; z-index: 1000000;">
            <!-- Header -->
            <div style="background: #dc3545; color: white; padding: 20px; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <h5 style="margin: 0; font-size: 1.25rem;">
                    <i class="fas fa-times-circle me-2"></i>Rejeter le TP
                </h5>
                <button type="button" onclick="closeCustomRejectModal()" style="background: none; border: none; color: white; font-size: 1.5rem; cursor: pointer; padding: 0; width: 30px; height: 30px; line-height: 1;">
                    &times;
                </button>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.tp.reject', $project->id) }}" method="POST" id="customRejectForm" onsubmit="return validateCustomReject(event)">
                @csrf
                <div style="padding: 20px;">
                    <!-- Alert -->
                    <div style="background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; padding: 12px; margin-bottom: 20px;">
                        <i class="fas fa-exclamation-triangle" style="color: #856404;"></i>
                        <strong style="color: #856404;">Attention :</strong>
                        <span style="color: #856404;">L'étudiant recevra un email avec vos commentaires.</span>
                    </div>

                    <!-- Textarea -->
                    <div style="margin-bottom: 15px;">
                        <label for="custom_rejection_reason" style="display: block; font-weight: bold; margin-bottom: 8px;">
                            Raison du rejet <span style="color: #dc3545;">*</span>
                        </label>
                        <textarea
                            id="custom_rejection_reason"
                            name="reason"
                            rows="6"
                            required
                            placeholder="Expliquez clairement les raisons du rejet et les améliorations attendues..."
                            style="width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; font-size: 1rem; resize: vertical; position: relative; z-index: 1000001;"
                        ></textarea>
                        <small style="color: #6c757d; display: block; margin-top: 5px;">
                            Soyez constructif et précis dans vos commentaires (minimum 10 caractères).
                        </small>
                        <div id="customReasonError" style="display: none; color: #dc3545; margin-top: 5px;">
                            <small><i class="fas fa-exclamation-circle me-1"></i>Veuillez saisir au moins 10 caractères.</small>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div style="padding: 15px 20px; border-top: 1px solid #dee2e6; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="closeCustomRejectModal()" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-danger" id="customRejectSubmitBtn">
                        <i class="fas fa-paper-plane me-2"></i>Rejeter et notifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* Lightbox pour les images */
#imageLightbox {
    display: none;
    position: fixed;
    z-index: 1040; /* En dessous des modals Bootstrap (1055) */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    overflow: auto;
    pointer-events: none; /* DÉSACTIVER complètement les interactions quand caché */
}

/* Réactiver les interactions uniquement quand affiché */
#imageLightbox[style*="display: block"] {
    pointer-events: auto;
}

/* S'assurer que les modals Bootstrap sont au-dessus */
.modal {
    z-index: 1055 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

#imageLightbox img {
    margin: auto;
    display: block;
    max-width: 90%;
    max-height: 90%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    animation: zoomIn 0.3s;
}

@keyframes zoomIn {
    from { transform: translate(-50%, -50%) scale(0.5); }
    to { transform: translate(-50%, -50%) scale(1); }
}

#imageLightbox .close-lightbox {
    position: absolute;
    top: 20px;
    right: 40px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.3s;
    z-index: 1; /* Relatif au parent lightbox, pas absolu */
}

#imageLightbox .close-lightbox:hover {
    color: #ccc;
}

.image-card:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

/* Animation pour la barre de progression */
@keyframes progressBar {
    0% { transform: translateX(-100%); }
    50% { transform: translateX(100%); }
    100% { transform: translateX(-100%); }
}

/* Animation pour le dégradé de texte */
@keyframes gradientShift {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}

/* Animation pulse pour le texte */
@keyframes pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.8; transform: scale(1.05); }
}

/* Animation pour le spinner coloré */
@keyframes colorPulse {
    0%, 100% {
        color: #FFD700;
        filter: drop-shadow(0 0 10px #FFD700);
    }
    33% {
        color: #FFA500;
        filter: drop-shadow(0 0 10px #FFA500);
    }
    66% {
        color: #FF6347;
        filter: drop-shadow(0 0 10px #FF6347);
    }
}
</style>
@endpush

@push('scripts')
<script>
/**
 * Afficher une image en plein écran (lightbox)
 * @param {string} imageUrl - URL de l'image
 * @param {string} imageName - Nom de l'image
 */
function viewImage(imageUrl, imageName) {
    // Créer le lightbox s'il n'existe pas
    let lightbox = document.getElementById('imageLightbox');
    if (!lightbox) {
        lightbox = document.createElement('div');
        lightbox.id = 'imageLightbox';
        lightbox.innerHTML = `
            <span class="close-lightbox" onclick="closeLightbox()">&times;</span>
            <img id="lightboxImage" src="" alt="">
            <div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); color: white; text-align: center; background: rgba(0,0,0,0.7); padding: 10px 20px; border-radius: 5px;">
                <span id="lightboxImageName"></span>
            </div>
        `;
        document.body.appendChild(lightbox);

        // Fermer au clic sur le fond
        lightbox.onclick = function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        };
    }

    // Afficher l'image
    document.getElementById('lightboxImage').src = imageUrl;
    document.getElementById('lightboxImageName').textContent = imageName;
    lightbox.style.display = 'block';
    document.body.style.overflow = 'hidden';
}

/**
 * Fermer le lightbox
 */
function closeLightbox() {
    const lightbox = document.getElementById('imageLightbox');
    if (lightbox) {
        lightbox.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Fermer le lightbox avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});

// Fermer le lightbox automatiquement quand un modal Bootstrap s'ouvre
document.addEventListener('DOMContentLoaded', function() {
    // Écouter l'ouverture de tous les modals Bootstrap
    document.querySelectorAll('.modal').forEach(function(modal) {
        modal.addEventListener('show.bs.modal', function() {
            closeLightbox(); // Fermer le lightbox si ouvert
        });
    });

    // Initialiser le modal de rejet
    initRejectModal();
});

/**
 * OUVRIR le modal custom de rejet
 */
function openRejectModal() {
    // Fermer le lightbox s'il est ouvert
    closeLightbox();

    const modal = document.getElementById('customRejectModal');
    const textarea = document.getElementById('custom_rejection_reason');

    if (modal && textarea) {
        // Afficher le modal
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';

        // Focus sur le textarea après un court délai
        setTimeout(() => {
            textarea.focus();
            textarea.value = '';
        }, 100);
    }
}

/**
 * FERMER le modal custom de rejet
 */
function closeCustomRejectModal() {
    const modal = document.getElementById('customRejectModal');
    const form = document.getElementById('customRejectForm');
    const textarea = document.getElementById('custom_rejection_reason');
    const errorDiv = document.getElementById('customReasonError');

    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    if (form) {
        form.reset();
    }

    if (textarea) {
        textarea.style.borderColor = '#ced4da';
    }

    if (errorDiv) {
        errorDiv.style.display = 'none';
    }
}

/**
 * VALIDER le formulaire de rejet custom
 */
function validateCustomReject(event) {
    const textarea = document.getElementById('custom_rejection_reason');
    const reason = textarea.value.trim();
    const errorDiv = document.getElementById('customReasonError');
    const submitBtn = document.getElementById('customRejectSubmitBtn');

    // Validation
    if (reason.length < 10) {
        event.preventDefault();
        errorDiv.style.display = 'block';
        textarea.style.borderColor = '#dc3545';
        textarea.focus();
        return false;
    }

    // Confirmation
    if (!confirm('Êtes-vous sûr de vouloir rejeter ce TP ?\n\nL\'étudiant recevra un email avec vos commentaires.')) {
        event.preventDefault();
        return false;
    }

    // Désactiver le bouton pendant la soumission
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2" style="color: #FFD700; animation: colorPulse 1.5s ease-in-out infinite;"></i><strong style="color: #FFD700; text-shadow: 0 0 10px rgba(255,215,0,0.8); animation: pulse 2s ease-in-out infinite;">Envoi en cours...</strong>';

    return true;
}

// Fermer le modal avec la touche Echap
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('customRejectModal');
        if (modal && modal.style.display === 'block') {
            closeCustomRejectModal();
        }
    }
});

// Fermer le modal en cliquant sur le fond (backdrop)
document.addEventListener('click', function(e) {
    const modal = document.getElementById('customRejectModal');
    if (e.target === modal) {
        closeCustomRejectModal();
    }
});

/**
 * Gérer la soumission du formulaire de validation
 */
function handleValidateSubmit(event) {
    const btn = document.getElementById('validateBtn');
    const btnText = document.getElementById('validateBtnText');
    const btnLoading = document.getElementById('validateBtnLoading');

    // Confirmation
    if (!confirm('Êtes-vous sûr de vouloir valider ce TP ?\n\nL\'étudiant recevra une notification.')) {
        event.preventDefault();
        return false;
    }

    // Afficher le loader dans le bouton
    btnText.style.display = 'none';
    btnLoading.style.display = 'inline-block';
    btn.disabled = true;

    // Créer et afficher l'overlay de chargement global
    showLoadingOverlay('Validation du TP en cours...');

    return true;
}

/**
 * Afficher un overlay de chargement coloré et visible
 */
function showLoadingOverlay(message) {
    // Supprimer l'overlay existant s'il y en a un
    const existing = document.getElementById('globalLoadingOverlay');
    if (existing) {
        existing.remove();
    }

    // Créer le nouvel overlay
    const overlay = document.createElement('div');
    overlay.id = 'globalLoadingOverlay';
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.95) 0%, rgba(23, 162, 184, 0.95) 100%);
        z-index: 9999999;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    `;

    overlay.innerHTML = `
        <div style="text-align: center;">
            <div style="margin-bottom: 20px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 5rem; color: #FFD700; animation: colorPulse 1.5s ease-in-out infinite;"></i>
            </div>
            <h2 style="
                font-size: 2.5rem;
                font-weight: bold;
                margin-bottom: 15px;
                background: linear-gradient(90deg, #FFD700, #FFA500, #FF6347, #FFD700);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                background-size: 200% auto;
                animation: gradientShift 3s linear infinite;
                text-shadow: 0 0 20px rgba(255,215,0,0.5);
                letter-spacing: 2px;
            ">
                ${message}
            </h2>
            <p style="
                font-size: 1.4rem;
                color: #FFFFFF;
                font-weight: 600;
                text-shadow: 0 0 10px rgba(255,255,255,0.8);
                animation: pulse 2s ease-in-out infinite;
            ">
                ⏳ Veuillez patienter...
            </p>
            <div style="margin-top: 25px;">
                <div style="width: 250px; height: 6px; background: rgba(255,255,255,0.3); border-radius: 3px; overflow: hidden; margin: 0 auto; box-shadow: 0 0 10px rgba(255,215,0,0.5);">
                    <div style="width: 100%; height: 100%; background: linear-gradient(90deg, #FFD700, #FFA500, #FF6347); animation: progressBar 2s ease-in-out infinite;"></div>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);
    document.body.style.overflow = 'hidden';
}
</script>
@endpush
