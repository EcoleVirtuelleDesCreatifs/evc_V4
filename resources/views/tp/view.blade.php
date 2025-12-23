@extends('layouts.ki-admin')

@section('title', 'Voir le TP - ' . $project->title)

@section('content')
@php
    // Debug: afficher la formation slug
    if (!isset($formationSlug)) {
        \Log::error('$formationSlug non défini dans tp/view.blade.php');
        $formationSlug = 'community-management'; // Valeur par défaut
    }
    \Log::info('tp/view.blade.php - formationSlug: ' . $formationSlug);
@endphp
<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0" style="color: white;">
                        <i class="fas fa-eye me-2" style="color: white;"></i>
                        Détails du TP
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.' . $formationSlug) }}" style="color: white;">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route($formationSlug . '.tp.index') }}" style="color: white;">Tous les TP</a></li>
                            <li class="breadcrumb-item active" style="color: white;">{{ $project->title }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route($formationSlug . '.tp.index') }}" class="btn btn-secondary">
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
                    // Fonction pour détecter les images
                    $isImage = function($file) {
                        $mime = strtolower((string) ($file->mime_type ?? ''));
                        return str_starts_with($mime, 'image/');
                    };

                    // Fonction pour obtenir l'URL correcte du fichier
                    $getFileUrl = function($filePath) {
                        $filePath = (string) $filePath;
                        $filePath = ltrim($filePath, '/');

                        // Si c'est déjà une URL, retourner tel quel
                        if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://')) {
                            return $filePath;
                        }

                        // Aligner sur Actualite::getCoverImageUrlAttribute()
                        if (str_starts_with($filePath, 'storage/app/public/')) {
                            $filePath = substr($filePath, strlen('storage/app/public/'));
                        }

                        return asset('storage/app/public/' . $filePath);
                    };

                    $getFileUrlFallback = function($filePath) {
                        $filePath = (string) $filePath;
                        $filePath = ltrim($filePath, '/');

                        if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://')) {
                            return $filePath;
                        }

                        if (str_starts_with($filePath, 'storage/app/public/')) {
                            $filePath = substr($filePath, strlen('storage/app/public/'));
                        }

                        return asset('storage/app/public/' . $filePath);
                    };

                    $imageFiles = $project->files->filter($isImage);
                    $otherFiles = $project->files->filter(fn($f) => !$isImage($f));
                @endphp

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
                                @php
                                    $imageUrl = $getFileUrl($image->file_path);
                                    $imageUrlFallback = $getFileUrlFallback($image->file_path);
                                @endphp
                            <div class="col-md-4 col-sm-6" id="image-{{ $image->id }}">
                                <div class="position-relative" style="height: 200px; overflow: hidden; border-radius: 8px; background: #f0f0f0;">
                                    <div class="w-100 h-100" style="cursor: pointer;" onclick="viewImage('{{ $imageUrl }}', '{{ $image->original_name }}')">
                                        <img src="{{ $imageUrl }}"
                                             class="w-100 h-100"
                                             style="object-fit: cover;"
                                             alt="{{ $image->original_name }}"
                                             onerror="this.onerror=null; this.src='{{ $imageUrlFallback }}';">
                                        <div class="d-none w-100 h-100 align-items-center justify-content-center flex-column" style="background: #f8f9fa;">
                                            <i class="fas fa-image text-muted fa-3x mb-2"></i>
                                            <small class="text-muted">Image non disponible</small>
                                            <small class="text-muted mt-1" style="font-size: 0.7rem;">{{ $image->file_path }}</small>
                                        </div>
                                    </div>
                                    @if($project->status === 'pending')
                                    <button type="button"
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2"
                                            style="z-index: 10;"
                                            onclick="event.stopPropagation(); deleteImage({{ $project->id }}, {{ $image->id }}, '{{ $image->original_name }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 w-100 p-2" style="background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);">
                                        <small class="text-white d-block text-truncate">{{ $image->original_name }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

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
                                @if(str_starts_with($file->mime_type, 'image/'))
                                    <i class="fas fa-image fa-2x text-info me-3"></i>
                                @elseif($file->mime_type === 'application/pdf')
                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                @elseif(str_starts_with($file->mime_type, 'video/'))
                                    <i class="fas fa-file-video fa-2x text-success me-3"></i>
                                @else
                                    <i class="fas fa-file fa-2x text-secondary me-3"></i>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $file->original_name }}</h6>
                                    <small class="text-muted">
                                        {{ number_format($file->file_size / 1024, 2) }} KB
                                        • {{ $file->mime_type }}
                                    </small>
                                </div>
                            </div>
                            <div>
                                @if(str_starts_with($file->mime_type, 'image/'))
                                    <a href="{{ $getFileUrl($file->file_path) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-info me-1"
                                       title="Voir l'image">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif
                                @if($file->mime_type === 'application/pdf')
                                    <a href="{{ $getFileUrl($file->file_path) }}"
                                       target="_blank"
                                       class="btn btn-sm btn-danger me-1"
                                       title="Lire le PDF">
                                        <i class="fas fa-book-open"></i>
                                    </a>
                                @endif
                                <a href="{{ $getFileUrl($file->file_path) }}"
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
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-clock me-2"></i>
                                <strong>En attente de validation</strong><br>
                                <small>Votre TP est en cours d'évaluation par l'équipe pédagogique.</small>
                            </div>
                            @if(!isset($project->source_table) || $project->source_table !== 'tp_assignments')
                                <a href="{{ route($formationSlug . '.tp.modifier', $project->id) }}" class="btn btn-warning w-100 mb-2">
                                    <i class="fas fa-edit me-2"></i>
                                    Modifier le TP
                                </a>
                                <button type="button" class="btn btn-danger w-100" onclick="confirmDelete({{ $project->id }})">
                                    <i class="fas fa-trash me-2"></i>
                                    Supprimer le TP
                                </button>
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>TP assigné par l'administration</strong><br>
                                    <small>Ce TP ne peut pas être modifié. Soumettez votre travail via le formulaire de soumission.</small>
                                </div>
                            @endif
                        @elseif($project->status === 'validated')
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>TP validé !</strong><br>
                                <small>Félicitations ! Votre travail a été accepté.</small>
                                @if($project->validated_at)
                                    <br><small class="text-muted">Validé le {{ \Carbon\Carbon::parse($project->validated_at)->format('d/m/Y à H:i') }}</small>
                                @endif
                            </div>
                        @elseif($project->status === 'rejected')
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-times-circle me-2"></i>
                                <strong>TP à améliorer</strong><br>
                                <small>Veuillez apporter les corrections demandées et resoumettre votre TP.</small>
                                @if($project->rejection_reason)
                                    <hr class="my-2">
                                    <small><strong>Commentaires :</strong><br>{{ $project->rejection_reason }}</small>
                                @endif
                            </div>
                        @endif
                        <a href="{{ route($formationSlug . '.tp.tous') }}" class="btn btn-secondary w-100 mt-2">
                            <i class="fas fa-list me-2"></i>
                            Voir tous mes TP
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@if($project->status === 'pending')
<script>
function confirmDelete(tpId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce TP ? Cette action est irréversible.')) {
        // Créer un formulaire de suppression dynamique
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route($formationSlug . ".tp.supprimer", "__ID__") }}'.replace('__ID__', tpId);

        // Ajouter le token CSRF
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        // Ajouter la méthode DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        // Ajouter au body et soumettre
        document.body.appendChild(form);
        form.submit();
    }
}

function deleteImage(tpId, fileId, fileName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'image "${fileName}" ?`)) {
        // Afficher un loader
        const imageElement = document.getElementById(`image-${fileId}`);
        if (imageElement) {
            imageElement.style.opacity = '0.5';
            imageElement.style.pointerEvents = 'none';
        }

        // Envoyer la requête de suppression
        fetch(`{{ route($formationSlug . ".tp.fichier.supprimer", ["tpId" => "__TP_ID__", "fileId" => "__FILE_ID__"]) }}`
            .replace('__TP_ID__', tpId)
            .replace('__FILE_ID__', fileId), {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer l'élément avec animation
                if (imageElement) {
                    imageElement.style.transition = 'all 0.3s ease';
                    imageElement.style.transform = 'scale(0)';
                    setTimeout(() => {
                        imageElement.remove();

                        // Vérifier s'il reste des images
                        const imagesContainer = document.querySelector('.row.g-3');
                        if (imagesContainer && imagesContainer.children.length === 0) {
                            // Recharger la page si plus d'images
                            location.reload();
                        }
                    }, 300);
                }

                // Afficher un message de succès
                alert(data.message || 'Image supprimée avec succès !');
            } else {
                // Restaurer l'élément en cas d'erreur
                if (imageElement) {
                    imageElement.style.opacity = '1';
                    imageElement.style.pointerEvents = 'auto';
                }
                alert(data.message || 'Erreur lors de la suppression de l\'image.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            // Restaurer l'élément en cas d'erreur
            if (imageElement) {
                imageElement.style.opacity = '1';
                imageElement.style.pointerEvents = 'auto';
            }
            alert('Erreur lors de la suppression de l\'image.');
        });
    }
}
</script>
@endif

@endsection

@push('styles')
<style>
#imageLightbox {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
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
}
#imageLightbox .close-lightbox {
    position: absolute;
    top: 20px;
    right: 40px;
    color: #fff;
    font-size: 40px;
    cursor: pointer;
}
</style>
@endpush

@push('scripts')
<script>
function viewImage(url, name) {
    let lb = document.getElementById('imageLightbox');
    if (!lb) {
        lb = document.createElement('div');
        lb.id = 'imageLightbox';
        lb.innerHTML = '<span class="close-lightbox" onclick="closeLightbox()">&times;</span><img id="lbImg" src=""><div style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); color: white; background: rgba(0,0,0,0.7); padding: 10px 20px; border-radius: 5px;"><span id="lbName"></span></div>';
        document.body.appendChild(lb);
        lb.onclick = e => e.target === lb && closeLightbox();
    }
    document.getElementById('lbImg').src = url;
    document.getElementById('lbName').textContent = name;
    lb.style.display = 'block';
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    const lb = document.getElementById('imageLightbox');
    if (lb) {
        lb.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}
document.addEventListener('keydown', e => e.key === 'Escape' && closeLightbox());
</script>
@endpush
