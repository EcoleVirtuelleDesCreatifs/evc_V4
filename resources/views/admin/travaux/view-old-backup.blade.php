@extends('layouts.admin')

@section('title', 'Détails du Travail Pratique')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec dégradé moderne -->
    <div class="modern-header mb-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                <a href="{{ route('admin.travaux.pending') }}" class="back-link mb-3 d-inline-block">
                    <i class="fas fa-arrow-left me-2"></i> Retour aux travaux en attente
                </a>
                <h1 class="display-5 fw-bold mb-2 text-white">
                    <i class="fas fa-graduation-cap me-3"></i>
                    Détails du TP
                </h1>
                <p class="text-white-50 mb-0">
                    <i class="fas fa-hashtag me-1"></i> ID: {{ $tp->id }} |
                    <i class="fas fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($tp->created_at)->format('d M Y') }}
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                @if($tp->status === 'submitted' || $tp->status === 'pending')
                    <div class="badge-status badge-pending">
                        <i class="fas fa-clock me-2"></i>En attente de validation
                    </div>
                @elseif($tp->status === 'validated')
                    <div class="badge-status badge-validated">
                        <i class="fas fa-check-circle me-2"></i>Validé
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Colonne principale -->
        <div class="col-lg-8">
            <!-- Informations du TP -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Informations du TP
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="text-muted small mb-1">Titre</label>
                        <h4 class="mb-0">{!! $tp->title !!}</h4>
                    </div>

                    @if($tp->description)
                        <div class="mb-4">
                            <label class="text-muted small mb-1">Description</label>
                            <div class="bg-light p-3 rounded">
                                {!! nl2br($tp->description) !!}
                            </div>
                        </div>
                    @endif

                    @if($tp->link)
                        <div class="mb-4">
                            <label class="text-muted small mb-1">Lien externe</label>
                            <div>
                                <a href="{{ $tp->link }}" target="_blank" class="btn btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-2"></i>
                                    Ouvrir le lien
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small mb-1">Date de soumission</label>
                            <div class="fw-bold">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                {{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y à H:i') }}
                            </div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($tp->created_at)->diffForHumans() }}</small>
                        </div>

                        @if($tp->validated_at)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small mb-1">Date de validation</label>
                                <div class="fw-bold">
                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                    {{ \Carbon\Carbon::parse($tp->validated_at)->format('d/m/Y à H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>

                    @if($tp->admin_comment)
                        <div class="alert alert-info">
                            <strong><i class="fas fa-comment me-2"></i>Commentaire de l'administrateur :</strong>
                            <p class="mb-0 mt-2">{{ $tp->admin_comment }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Fichiers et Images -->
            @if($files && $files->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-paperclip me-2 text-success"></i>
                            Fichiers joints ({{ $files->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($files as $file)
                                @php
                                    $extension = pathinfo($file->original_name, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
                                    $fileSize = $file->file_size ? round($file->file_size / 1024, 2) : 0;
                                @endphp

                                <div class="col-md-6">
                                    <div class="card border h-100">
                                        @if($isImage)
                                            <!-- Prévisualisation de l'image -->
                                            <div class="position-relative" style="height: 200px; overflow: hidden;">
                                                @php
                                                    // Générer l'URL correcte selon le système
                                                    $imageUrl = $file->file_path;
                                                    // Si le chemin ne commence pas par http ou /, c'est un chemin storage
                                                    if (!str_starts_with($imageUrl, 'http') && !str_starts_with($imageUrl, '/')) {
                                                        // Pour les fichiers dans storage/app/public/
                                                        $imageUrl = \App\Models\MediaUrl::fromPath($file->file_path);
                                                    } else {
                                                        $imageUrl = asset($file->file_path);
                                                    }
                                                @endphp
                                                <img src="{{ $imageUrl }}"
                                                     alt="{{ $file->original_name }}"
                                                     class="w-100 h-100"
                                                     style="object-fit: cover;"
                                                     onerror="this.parentElement.innerHTML='<div class=\"d-flex align-items-center justify-content-center h-100 bg-light\"><div class=\"text-center text-muted\"><i class=\"fas fa-image-slash fa-3x mb-2\"></i><br>Image non disponible</div></div>';">
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-image me-1"></i>Image
                                                    </span>
                                                </div>
                                            </div>
                                        @else
                                            <!-- Icône pour les fichiers non-image -->
                                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                                                <div class="text-center">
                                                    @php
                                                        $iconClass = 'fa-file';
                                                        $iconColor = 'text-secondary';

                                                        if (in_array(strtolower($extension), ['pdf'])) {
                                                            $iconClass = 'fa-file-pdf';
                                                            $iconColor = 'text-danger';
                                                        } elseif (in_array(strtolower($extension), ['doc', 'docx'])) {
                                                            $iconClass = 'fa-file-word';
                                                            $iconColor = 'text-primary';
                                                        } elseif (in_array(strtolower($extension), ['xls', 'xlsx'])) {
                                                            $iconClass = 'fa-file-excel';
                                                            $iconColor = 'text-success';
                                                        } elseif (in_array(strtolower($extension), ['ppt', 'pptx'])) {
                                                            $iconClass = 'fa-file-powerpoint';
                                                            $iconColor = 'text-warning';
                                                        } elseif (in_array(strtolower($extension), ['zip', 'rar', '7z'])) {
                                                            $iconClass = 'fa-file-archive';
                                                            $iconColor = 'text-dark';
                                                        }
                                                    @endphp
                                                    <i class="fas {{ $iconClass }} {{ $iconColor }}" style="font-size: 4rem;"></i>
                                                    <div class="mt-2">
                                                        <span class="badge bg-secondary">{{ strtoupper($extension) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="card-body">
                                            <h6 class="card-title text-truncate" title="{{ $file->original_name }}">
                                                {{ $file->original_name }}
                                            </h6>
                                            <p class="card-text small text-muted mb-2">
                                                <i class="fas fa-hdd me-1"></i>
                                                {{ $fileSize }} KB
                                            </p>
                                            <div class="d-flex gap-2">
                                                <a href="{{ asset($file->file_path) }}"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-primary flex-fill">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Voir
                                                </a>
                                                <a href="{{ asset($file->file_path) }}"
                                                   download="{{ $file->original_name }}"
                                                   class="btn btn-sm btn-outline-success flex-fill">
                                                    <i class="fas fa-download me-1"></i>
                                                    Télécharger
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                        <h5 class="mt-3 text-muted">Aucun fichier joint</h5>
                        <p class="text-muted">Ce TP ne contient aucun fichier ou image.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Colonne latérale -->
        <div class="col-lg-4">
            <!-- Informations de l'étudiant -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2 text-info"></i>
                        Étudiant
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($student && $student->profile_photo)
                            <div class="d-inline-block" style="width: 80px; height: 80px; border-radius: 50%; overflow: hidden; box-shadow: 0 4px 16px rgba(30, 60, 114, 0.3);">
                                <img src="{{ asset($student->profile_photo) }}"
                                     alt="{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}"
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        @else
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white; font-weight: bold; font-size: 2rem; box-shadow: 0 4px 16px rgba(30, 60, 114, 0.3);">
                                {{ strtoupper(substr($student->first_name ?? $user->name, 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h5 class="text-center mb-3">
                        {{ $student->first_name ?? '' }} {{ $student->last_name ?? $user->name }}
                    </h5>

                    <div class="mb-2">
                        <small class="text-muted">ID Étudiant</small>
                        <div class="fw-bold">{{ $student->student_id ?? 'N/A' }}</div>
                    </div>

                    <div class="mb-2">
                        <small class="text-muted">Email</small>
                        <div class="fw-bold">{{ $user->email }}</div>
                    </div>

                    @if($student && $student->program)
                        <div class="mb-2">
                            <small class="text-muted">Formation</small>
                            <div>
                                <span class="badge bg-primary">{{ $student->program }}</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Statut et Actions -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-tasks me-2 text-warning"></i>
                        Statut et Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small mb-2">Statut actuel</label>
                        <div>
                            @if($tp->status === 'pending')
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="fas fa-clock me-1"></i>
                                    En attente
                                </span>
                            @elseif($tp->status === 'validated')
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Validé
                                </span>
                            @elseif($tp->status === 'rejected')
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Rejeté
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($tp->status === 'pending')
                        <!-- Boutons d'action -->
                        <div id="actionButtons" class="d-grid gap-2">
                            <form action="{{ route('admin.tp.validate', $tp->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" onclick="return confirm('Êtes-vous sûr de vouloir valider ce TP ?')">
                                    <i class="fas fa-check me-2"></i>
                                    Valider le TP
                                </button>
                            </form>

                            <button type="button" class="btn btn-danger w-100" id="btnShowReject">
                                <i class="fas fa-times me-2"></i>
                                Rejeter le TP
                            </button>
                        </div>

                        <!-- Formulaire de rejet -->
                        <div id="rejectFormContainer" style="display: none; margin-top: 1rem;">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Rejeter le TP
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning mb-3">
                                        <small><strong>Attention !</strong> L'étudiant recevra un email avec votre commentaire.</small>
                                    </div>
                                    <form id="formReject" action="{{ route('admin.tp.reject', $tp->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="rejectReason" class="form-label fw-bold">
                                                Raison du rejet <span class="text-danger">*</span>
                                            </label>
                                            <textarea
                                                class="form-control"
                                                id="rejectReason"
                                                name="reason"
                                                rows="6"
                                                required
                                                placeholder="Expliquez pourquoi ce TP est rejeté et ce que l'étudiant doit améliorer..."></textarea>
                                            <small class="text-muted">Minimum 10 caractères</small>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fas fa-check me-2"></i>
                                                Confirmer le rejet
                                            </button>
                                            <button type="button" class="btn btn-secondary" id="btnCancelReject">
                                                <i class="fas fa-times me-2"></i>
                                                Annuler
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Page chargée, initialisation des événements...');

    // Bouton pour afficher le formulaire de rejet
    const btnShowReject = document.getElementById('btnShowReject');
    const btnCancelReject = document.getElementById('btnCancelReject');
    const rejectFormContainer = document.getElementById('rejectFormContainer');
    const rejectReason = document.getElementById('rejectReason');
    const formReject = document.getElementById('formReject');

    // Vérifier que les éléments existent
    console.log('Éléments trouvés:', {
        btnShowReject: !!btnShowReject,
        btnCancelReject: !!btnCancelReject,
        rejectFormContainer: !!rejectFormContainer,
        rejectReason: !!rejectReason,
        formReject: !!formReject
    });

    // Afficher le formulaire de rejet
    if (btnShowReject && rejectFormContainer && rejectReason) {
        btnShowReject.addEventListener('click', function() {
            console.log('🔴 Clic sur Rejeter le TP');
            rejectFormContainer.style.display = 'block';
            setTimeout(function() {
                rejectReason.focus();
                console.log('✅ Focus sur textarea');
            }, 100);
        });
    }

    // Annuler et masquer le formulaire
    if (btnCancelReject && rejectFormContainer && rejectReason) {
        btnCancelReject.addEventListener('click', function() {
            console.log('❌ Annulation du rejet');
            rejectFormContainer.style.display = 'none';
            rejectReason.value = '';
        });
    }

    // Validation avant soumission
    if (formReject && rejectReason) {
        formReject.addEventListener('submit', function(e) {
            const reason = rejectReason.value.trim();
            console.log('📝 Soumission du formulaire, raison:', reason);

            if (reason.length < 10) {
                e.preventDefault();
                alert('⚠️ La raison du rejet doit contenir au moins 10 caractères.');
                return false;
            }

            if (!confirm('❌ Êtes-vous sûr de vouloir rejeter ce TP ?\n\nL\'étudiant recevra un email avec votre commentaire.')) {
                e.preventDefault();
                return false;
            }

            console.log('✅ Formulaire validé, soumission en cours...');
            return true;
        });
    }

    console.log('✅ Tous les événements initialisés');
});
</script>

<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

/* Fix pour le modal */
.modal {
    z-index: 1055 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

.modal-content {
    position: relative;
    z-index: 1056 !important;
}

.modal textarea,
.modal input {
    pointer-events: auto !important;
    z-index: 1057 !important;
    position: relative;
}
</style>
@endsection
