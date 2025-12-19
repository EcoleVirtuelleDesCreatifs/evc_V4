@extends('layouts.admin')

@section('title', 'Détails du Projet Design - ' . $project->title)

@section('content')
<div class="container-fluid">
    <!-- Header avec retour -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h2 class="text-white mb-1">
                        <i class="fas fa-paint-brush me-2"></i>Détails du Projet Design
                    </h2>
                    <p class="text-white-50 mb-0">Visualisation complète du projet design étudiant</p>
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

    <!-- Informations principales du projet design -->
    <div class="row mb-4">
        <!-- Informations du projet -->
        <div class="col-lg-8">
            <!-- Carte principale du projet -->
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-primary">
                    <h5 class="card-title text-white mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informations du Projet Design
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Informations principales en format structuré -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-dark table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="text-info fw-bold" style="width: 200px;">
                                                <i class="fas fa-tag me-2"></i>Titre du projet :
                                            </td>
                                            <td class="text-white">
                                                <strong>{{ $project->title }}</strong>
                                            </td>
                                        </tr>
                                        @if($project->description)
                                        <tr>
                                            <td class="text-info fw-bold">
                                                <i class="fas fa-align-left me-2"></i>Description :
                                            </td>
                                            <td class="text-white">
                                                {{ $project->description }}
                                            </td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <td class="text-info fw-bold">
                                                <i class="fas fa-layer-group me-2"></i>Type :
                                            </td>
                                            <td class="text-white">
                                                @php
                                                    $typeIcon = match($project->project_type ?? 'autre') {
                                                        'logo' => 'fas fa-copyright',
                                                        'web' => 'fas fa-globe',
                                                        'print' => 'fas fa-print',
                                                        'packaging' => 'fas fa-box',
                                                        'illustration' => 'fas fa-palette',
                                                        'motion' => 'fas fa-video',
                                                        'strategy' => 'fas fa-chess',
                                                        default => 'fas fa-project-diagram'
                                                    };
                                                @endphp
                                                <span class="badge bg-primary px-3 py-2">
                                                    <i class="{{ $typeIcon }} me-1"></i>{{ $project->project_type_label ?? 'Non spécifié' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-info fw-bold">
                                                <i class="fas fa-tools me-2"></i>Logiciels :
                                            </td>
                                            <td class="text-white">
                                                @if($project->software_used && is_array($project->software_used) && !empty($project->software_used))
                                                    <div class="d-flex flex-wrap gap-2">
                                                        @foreach($project->software_used as $software)
                                                            @php
                                                                $softwareName = is_array($software) ? (isset($software['name']) ? $software['name'] : 'Logiciel') : (string)$software;
                                                                $softwareName = trim($softwareName);
                                                            @endphp
                                                            @if(!empty($softwareName))
                                                                <span class="badge bg-secondary text-white px-2 py-1">
                                                                    <i class="fas fa-cog me-1"></i>{{ $softwareName }}
                                                                </span>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @elseif($project->formatted_software)
                                                    <span class="text-white-50">{{ $project->formatted_software }}</span>
                                                @else
                                                    <span class="text-white-50">Aucun logiciel spécifié</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-info fw-bold">
                                                <i class="fas fa-users me-2"></i>Mode :
                                            </td>
                                            <td class="text-white">
                                                @php
                                                    $modeClass = ($project->project_mode ?? 'solo') === 'solo' ? 'bg-info' : 'bg-warning';
                                                    $modeIcon = ($project->project_mode ?? 'solo') === 'solo' ? 'fas fa-user' : 'fas fa-users';
                                                @endphp
                                                <span class="badge {{ $modeClass }} px-3 py-2">
                                                    <i class="{{ $modeIcon }} me-1"></i>{{ $project->mode_label ?? 'Solo' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-info fw-bold">
                                                <i class="fas fa-info-circle me-2"></i>Status :
                                            </td>
                                            <td class="text-white">
                                                @php
                                                    $statusClass = match($project->status) {
                                                        'validated' => 'bg-success',
                                                        'active' => 'bg-info',
                                                        'draft' => 'bg-warning',
                                                        'pending' => 'bg-secondary',
                                                        default => 'bg-secondary'
                                                    };

                                                    $statusIcon = match($project->status) {
                                                        'validated' => 'fas fa-check-circle',
                                                        'active' => 'fas fa-play-circle',
                                                        'draft' => 'fas fa-edit',
                                                        'pending' => 'fas fa-clock',
                                                        default => 'fas fa-question-circle'
                                                    };
                                                @endphp
                                                <span class="badge {{ $statusClass }} px-3 py-2">
                                                    <i class="{{ $statusIcon }} me-1"></i>{{ $project->status_label ?? 'Non défini' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-info fw-bold">
                                                <i class="fas fa-calendar me-2"></i>Date de création :
                                            </td>
                                            <td class="text-white">
                                                {{ isset($project->created_at) && $project->created_at ? $project->created_at->format('d/m/Y à H:i') : 'Non disponible' }}
                                            </td>
                                        </tr>
                                        @if(isset($project->updated_at) && $project->updated_at && isset($project->created_at) && $project->updated_at != $project->created_at)
                                        <tr>
                                            <td class="text-info fw-bold">
                                                <i class="fas fa-edit me-2"></i>Date de mise à jour :
                                            </td>
                                            <td class="text-white">
                                                {{ $project->updated_at->format('d/m/Y à H:i') }}
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Actions du projet -->
            <div class="card bg-dark border-secondary mt-4">
                <div class="card-header bg-secondary">
                    <h5 class="card-title text-white mb-0">
                        <i class="fas fa-cogs me-2"></i>Actions du Projet
                    </h5>
                </div>

                <div class="card-body">
                    <div class="row g-2">
                        <!-- Bouton Valider -->
                        <div class="col-12 col-md-3">
                            @if($project->status !== 'validated')
                                <button type="button"
                                        class="btn btn-success btn-lg w-100 px-3 py-2"
                                        onclick="validateDesignProject({{ $project->id }})"
                                        title="Valider le projet">
                                    <i class="fas fa-check-circle me-2"></i>Valider
                                </button>
                            @else
                                <button type="button" class="btn btn-outline-success btn-lg w-100 px-3 py-2" disabled>
                                    <i class="fas fa-check-circle me-2"></i>Déjà validé
                                </button>
                            @endif
                        </div>

                        <!-- Bouton Rejeter -->
                        <div class="col-12 col-md-3">
                            @if($project->status !== 'rejected')
                                <button type="button"
                                        class="btn btn-outline-danger btn-lg w-100 px-3 py-2"
                                        onclick="rejectDesignProject({{ $project->id }})"
                                        title="Rejeter le projet">
                                    <i class="fas fa-times-circle me-2"></i>Rejeter
                                </button>
                            @else
                                <button type="button" class="btn btn-danger btn-lg w-100 px-3 py-2" disabled>
                                    <i class="fas fa-times-circle me-2"></i>Rejeté
                                </button>
                            @endif
                        </div>

                        <!-- Bouton Modifier -->
                        <div class="col-12 col-md-3">
                            <button type="button"
                                    class="btn btn-warning btn-lg w-100 px-3 py-2"
                                    onclick="editDesignProject({{ $project->id }})"
                                    title="Modifier le projet">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </button>
                        </div>

                        <!-- Bouton Supprimer -->
                        <div class="col-12 col-md-3">
                            <button type="button"
                                    class="btn btn-danger btn-lg w-100 px-3 py-2"
                                    onclick="deleteDesignProject({{ $project->id }})"
                                    title="Supprimer le projet">
                                <i class="fas fa-trash-alt me-2"></i>Supprimer
                            </button>
                        </div>

                        <!-- Bouton Retour -->
                        <div class="col-12 col-md-3">
                            <a href="{{ url()->previous() }}"
                               class="btn btn-secondary btn-lg w-100 px-3 py-2"
                               title="Retour à la page précédente">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations de l'étudiant -->
        <div class="col-lg-4">
            <div class="card bg-dark border-secondary">
                <div class="card-header bg-info">
                    <h5 class="card-title text-white mb-0">
                        <i class="fas fa-user me-2"></i>Informations Étudiant
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @php
                            $studentProfilePhoto = $project->user->student->profile_photo ?? null;
                            $userProfilePhoto = $project->user->profile_photo ?? null;
                            $profilePhotoPath = $studentProfilePhoto ?: $userProfilePhoto;
                        @endphp
                        @if(!empty($profilePhotoPath))
                            <img src="{{ asset('storage/' . ltrim($profilePhotoPath, '/')) }}"
                                 alt="Photo de {{ $project->user->first_name ?? 'N/A' }}"
                                 class="rounded-circle"
                                 style="width: 80px; height: 80px; object-fit: cover; border: 3px solid #17a2b8;"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">

                        @else
                            <div class="rounded-circle bg-info d-inline-flex align-items-center justify-content-center"
                                 style="width: 80px; height: 80px;">
                                <span class="text-white fw-bold fs-4">
                                    {{ strtoupper(substr($project->user->first_name ?? 'N', 0, 1)) }}{{ strtoupper(substr($project->user->last_name ?? 'A', 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>


                    <h6 class="text-white mb-2">{{ $project->user->first_name ?? 'N/A' }} {{ $project->user->last_name ?? '' }}</h6>
                    <p class="text-white-50 mb-2">
                        <i class="fas fa-envelope me-1"></i>{{ $project->user->email ?? 'N/A' }}
                    </p>
                    @if(isset($project->user->formation_souhaitee))
                        <p class="text-white-50 mb-2">
                            <i class="fas fa-graduation-cap me-1"></i>{{ ucfirst(str_replace(['_', '-'], ' ', $project->user->formation_souhaitee)) }}
                        </p>
                    @endif
                    <p class="text-white-50 mb-3">
                        <i class="fas fa-calendar me-1"></i>Inscrit le {{ isset($project->user->created_at) && $project->user->created_at ? $project->user->created_at->format('d/m/Y') : 'N/A' }}
                    </p>

                    <hr class="border-secondary my-3">

                    <!-- Statistiques rapides -->
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="text-info">
                                <i class="fas fa-project-diagram fa-lg"></i>
                                <div class="small text-white-50 mt-1">Projets</div>
                                <div class="text-white">{{ $project->user->designProjects->count() ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-success">
                                <i class="fas fa-check-circle fa-lg"></i>
                                <div class="small text-white-50 mt-1">Validés</div>
                                <div class="text-white">{{ $project->user->designProjects->where('status', 'validated')->count() ?? 0 }}</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-warning">
                                <i class="fas fa-clock fa-lg"></i>
                                <div class="small text-white-50 mt-1">En cours</div>
                                <div class="text-white">{{ $project->user->designProjects->where('status', 'active')->count() ?? 0 }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <a href="{{ route('admin.students.profile', $project->user->id) }}" class="btn btn-outline-info">
                            <i class="fas fa-user-graduate me-2"></i>Voir le Profil Étudiant
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Fichiers du projet -->
    @if($project->files && $project->files->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark border-secondary">
                    <div class="card-header bg-success">
                        <h5 class="card-title text-white mb-0">
                            <i class="fas fa-folder-open me-2"></i>Fichiers du Projet ({{ $project->files->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Images Gallery Header -->
                        @php
                            $imageFiles = $project->files->filter(function($file) {
                                return str_starts_with($file->mime_type ?? '', 'image/');
                            });
                            $otherFiles = $project->files->filter(function($file) {
                                return !str_starts_with($file->mime_type ?? '', 'image/');
                            });
                        @endphp

                        @if($imageFiles->count() > 0)
                            <!-- Image Gallery Section -->
                            <div class="mb-5">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="text-white mb-0">
                                        <i class="fas fa-images me-2 text-info"></i>Galerie d'Images ({{ $imageFiles->count() }})
                                    </h6>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleGalleryView('grid')" id="gridViewBtn">
                                            <i class="fas fa-th"></i> Grille
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-info" onclick="toggleGalleryView('carousel')" id="carouselViewBtn">
                                            <i class="fas fa-images"></i> Carrousel
                                        </button>
                                    </div>
                                </div>

                                <!-- Grid View -->
                                <div id="gridView" class="row g-2">
                                    @foreach($imageFiles as $index => $file)
                                        <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-6 col-sm-6 col-6 mb-2">
                                            <div class="card bg-secondary border-dark h-100 image-card" data-index="{{ $index }}">
                                                <div class="position-relative image-container">
                                                    @php
                                                        // Optimized path detection
                                                        $possiblePaths = [
                                                            'storage/' . $file->file_path,
                                                            'uploads/design_projects/' . basename($file->file_path),
                                                            'uploads/' . $file->file_path,
                                                            $file->file_path
                                                        ];
                                                        $imagePath = null;
                                                        foreach ($possiblePaths as $path) {
                                                            if (file_exists(public_path($path))) {
                                                                $imagePath = asset($path);
                                                                break;
                                                            }
                                                        }
                                                        if (!$imagePath) {
                                                            $imagePath = asset('storage/' . $file->file_path);
                                                        }
                                                    @endphp


                                                    <!-- Actual image -->
                                                    <img src="{{ $imagePath }}"
                                                         alt="{{ $file->original_name ?? 'Image' }}"
                                                         class="card-img-top image-preview"
                                                         style="height: 200px; object-fit: cover; cursor: pointer; display: none;"
                                                         data-index="{{ $index }}"
                                                         data-filename="{{ $file->original_name ?? 'Image' }}"
                                                         onclick="openImageGallery({{ $index }})"
                                                         onload="this.style.display='block'; this.previousElementSibling.style.display='none';"
                                                         onerror="this.style.display='none'; this.previousElementSibling.style.display='none'; this.nextElementSibling.style.display='flex';">



                                                    <!-- Image overlay -->
                                                    <div class="image-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                                         style="background: rgba(0,0,0,0.7); opacity: 0; transition: opacity 0.3s ease;">
                                                        <div class="text-center">
                                                            <button class="btn btn-light btn-sm mb-2" onclick="openImageGallery({{ $index }})">
                                                                <i class="fas fa-search-plus me-1"></i>Agrandir
                                                            </button>
                                                            <br>
                                                            <a href="{{ $imagePath }}" target="_blank" class="btn btn-outline-light btn-sm">
                                                                <i class="fas fa-external-link-alt me-1"></i>Ouvrir
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <!-- Image info badge -->
                                                    <div class="position-absolute bottom-0 start-0 m-2">
                                                        <span class="badge bg-dark bg-opacity-75 text-white">
                                                            {{ $index + 1 }}/{{ $imageFiles->count() }}
                                                        </span>
                                                    </div>

                                                    <!-- File size badge -->
                                                    @if(isset($file->file_size))
                                                        <div class="position-absolute top-0 start-0 m-2">
                                                            <span class="badge bg-info bg-opacity-75">
                                                                @if($file->file_size > 1048576)
                                                                    {{ number_format($file->file_size / 1048576, 1) }} MB
                                                                @elseif($file->file_size > 1024)
                                                                    {{ number_format($file->file_size / 1024, 1) }} KB
                                                                @else
                                                                    {{ $file->file_size }} bytes
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="card-body p-3">
                                                    <h6 class="text-white mb-2 text-truncate" title="{{ $file->original_name ?? 'Image' }}">
                                                        <i class="fas fa-image text-info me-2"></i>{{ $file->original_name ?? 'Image' }}
                                                    </h6>
                                                    @if(isset($file->file_category))
                                                        <small class="text-info">
                                                            <i class="fas fa-tag me-1"></i>{{ ucfirst($file->file_category) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Carousel View (hidden by default) -->
                                <div id="carouselView" style="display: none;">
                                    <div id="imageCarousel" class="carousel slide" data-bs-ride="false">
                                        <div class="carousel-indicators">
                                            @foreach($imageFiles as $index => $file)
                                                <button type="button" data-bs-target="#imageCarousel" data-bs-slide-to="{{ $index }}"
                                                        class="{{ $index === 0 ? 'active' : '' }}"
                                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                        aria-label="Image {{ $index + 1 }}"></button>
                                            @endforeach
                                        </div>
                                        <div class="carousel-inner">
                                            @foreach($imageFiles as $index => $file)
                                                @php
                                                    $possiblePaths = [
                                                        'storage/' . $file->file_path,
                                                        'uploads/design_projects/' . basename($file->file_path),
                                                        'uploads/' . $file->file_path,
                                                        $file->file_path
                                                    ];
                                                    $imagePath = null;
                                                    foreach ($possiblePaths as $path) {
                                                        if (file_exists(public_path($path))) {
                                                            $imagePath = asset($path);
                                                            break;
                                                        }
                                                    }
                                                    if (!$imagePath) {
                                                        $imagePath = asset('storage/' . $file->file_path);
                                                    }
                                                @endphp
                                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                    <div class="d-flex justify-content-center">
                                                        <img src="{{ $imagePath }}"
                                                             class="d-block carousel-image"
                                                             alt="{{ $file->original_name ?? 'Image' }}"
                                                             style="max-height: 500px; max-width: 100%; object-fit: contain;"
                                                             onclick="openImageGallery({{ $index }})">
                                                    </div>
                                                    <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-75 rounded">
                                                        <h5>{{ $file->original_name ?? 'Image' }}</h5>
                                                        @if(isset($file->file_category))
                                                            <p>{{ ucfirst($file->file_category) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Précédent</span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Suivant</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Other Files Section -->
                        @if($otherFiles->count() > 0)
                            <div class="mb-4">
                                <h6 class="text-white mb-3">
                                    <i class="fas fa-file me-2 text-warning"></i>Autres Fichiers ({{ $otherFiles->count() }})
                                </h6>
                                <div class="row">
                                    @foreach($otherFiles as $file)
                                        <div class="col-lg-4 col-md-6 mb-4">
                                            <div class="card bg-secondary border-dark h-100">
                                                <div class="card-body">
                                                    <!-- File header with icon and name -->
                                                    <div class="d-flex align-items-center mb-3">
                                                        @if(str_starts_with($file->mime_type ?? '', 'image/'))
                                                            <i class="fas fa-image text-info me-2"></i>
                                                        @elseif(str_contains($file->mime_type ?? '', 'pdf'))
                                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                                        @elseif(str_contains($file->mime_type ?? '', 'video'))
                                                            <i class="fas fa-video text-warning me-2"></i>
                                                        @elseif(str_contains($file->mime_type ?? '', 'audio'))
                                                            <i class="fas fa-music text-success me-2"></i>
                                                        @elseif(str_contains($file->mime_type ?? '', 'zip') || str_contains($file->mime_type ?? '', 'archive'))
                                                            <i class="fas fa-file-archive text-warning me-2"></i>
                                                        @else
                                                            <i class="fas fa-file text-white-50 me-2"></i>
                                                        @endif
                                                        <h6 class="text-white mb-0 flex-grow-1 text-truncate" title="{{ $file->original_name ?? 'Fichier sans nom' }}">
                                                            {{ $file->original_name ?? 'Fichier sans nom' }}
                                                        </h6>
                                                    </div>

                                                    <!-- File metadata -->
                                                    <div class="mb-3">
                                                        @if(isset($file->file_category))
                                                            <div class="mb-2">
                                                                <small class="text-info">
                                                                    <i class="fas fa-tag me-1"></i>{{ ucfirst($file->file_category) }}
                                                                </small>
                                                            </div>
                                                        @endif

                                                        @if(isset($file->file_size))
                                                            <div class="mb-2">
                                                                <small class="text-white-50">
                                                                    <i class="fas fa-hdd me-1"></i>
                                                                    @if($file->file_size > 1048576)
                                                                        {{ number_format($file->file_size / 1048576, 1) }} MB
                                                                    @elseif($file->file_size > 1024)
                                                                        {{ number_format($file->file_size / 1024, 1) }} KB
                                                                    @else
                                                                        {{ $file->file_size }} bytes
                                                                    @endif
                                                                </small>
                                                            </div>
                                                        @endif

                                                        @if($file->mime_type)
                                                            <div class="mb-2">
                                                                <small class="text-white-50">
                                                                    <i class="fas fa-file-code me-1"></i>{{ $file->mime_type }}
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Action buttons -->
                                                    @if(!empty($file->file_path))
                                                        <div class="d-flex gap-2">
                                                            @if(str_contains($file->mime_type ?? '', 'pdf'))
                                                                <!-- PDF Viewer Button -->
                                                                <button class="btn btn-sm btn-outline-danger flex-fill"
                                                                        onclick="openPdfViewer('{{ route('admin.design-projects.file', $file->id) }}', '{{ $file->original_name ?? 'Document PDF' }}')">
                                                                    <i class="fas fa-eye me-1"></i>Lire PDF
                                                                </button>
                                                                <!-- Download Button -->
                                                                <a href="{{ route('admin.design-projects.file', $file->id) }}"
                                                                   download="{{ $file->original_name ?? 'document.pdf' }}"
                                                                   class="btn btn-sm btn-outline-success"
                                                                   title="Télécharger">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            @else
                                                                <!-- Regular file open button -->
                                                                <a href="{{ route('admin.design-projects.file', $file->id) }}"
                                                                   target="_blank"
                                                                   class="btn btn-sm btn-outline-primary flex-fill">
                                                                    <i class="fas fa-external-link-alt me-1"></i>Ouvrir le fichier
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <div class="d-grid">
                                                            <button class="btn btn-sm btn-outline-secondary" disabled>
                                                                <i class="fas fa-exclamation-triangle me-1"></i>Fichier indisponible
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark border-secondary">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-folder-open text-white-50 fa-3x mb-3"></i>
                        <h5 class="text-white-50">Aucun fichier associé</h5>
                        <p class="text-white-50 mb-0">Ce projet design ne contient pas encore de fichiers.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- PDF Viewer Modal -->
<div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="pdfViewerModalLabel">
                    <i class="fas fa-file-pdf text-danger me-2"></i>Lecteur PDF
                </h5>
                <div class="d-flex align-items-center gap-3">
                    <!-- PDF Controls -->
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-light" onclick="zoomOut()" title="Zoom -">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-light" onclick="resetZoom()" title="Zoom 100%">
                            <span id="zoomLevel">100%</span>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-light" onclick="zoomIn()" title="Zoom +">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>

                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="downloadCurrentPdf()" title="Télécharger">
                            <i class="fas fa-download"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-info" onclick="openPdfInNewTab()" title="Ouvrir dans un nouvel onglet">
                            <i class="fas fa-external-link-alt"></i>
                        </button>
                    </div>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
            </div>
            <div class="modal-body p-0 position-relative">
                <!-- Loading indicator -->
                <div id="pdfLoadingIndicator" class="position-absolute top-50 start-50 translate-middle text-center">
                    <div class="spinner-border text-danger mb-3" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <div class="text-white">
                        <i class="fas fa-file-pdf text-danger me-2"></i>Chargement du PDF...
                    </div>
                </div>

                <!-- PDF Container -->
                <div id="pdfContainer" class="w-100 h-100 d-flex justify-content-center align-items-center" style="min-height: 80vh; background: #2c2c2c;">
                    <iframe id="pdfFrame"
                            class="w-100 h-100 border-0"
                            style="min-height: 80vh; display: none;"
                            onload="onPdfLoad()"
                            onerror="onPdfError()">
                    </iframe>

                    <!-- Error message -->
                    <div id="pdfErrorMessage" class="text-center text-white" style="display: none;">
                        <i class="fas fa-exclamation-triangle text-warning fa-3x mb-3"></i>
                        <h5>Erreur de chargement</h5>
                        <p class="text-white-50">Impossible de charger le fichier PDF.</p>
                        <button class="btn btn-outline-primary" onclick="retryPdfLoad()">
                            <i class="fas fa-redo me-1"></i>Réessayer
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div class="text-white-50">
                        <i class="fas fa-info-circle me-1"></i>
                        <span id="pdfFileName">Document PDF</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Image Gallery Modal -->
<div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title text-white" id="imageGalleryModalLabel">
                    <i class="fas fa-images me-2"></i>Galerie d'Images
                </h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-info me-3" id="imageCounter">1/1</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-0">
                <div class="position-relative">
                    <!-- Main image display -->
                    <div class="text-center p-4">
                        <img id="galleryModalImage" src="" alt="Image" class="img-fluid rounded shadow"
                             style="max-height: 75vh; max-width: 100%; object-fit: contain;">
                    </div>

                    <!-- Navigation arrows -->
                    <button class="btn btn-outline-light position-absolute top-50 start-0 translate-middle-y ms-3"
                            id="prevImageBtn" onclick="navigateGallery(-1)" style="z-index: 10;">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="btn btn-outline-light position-absolute top-50 end-0 translate-middle-y me-3"
                            id="nextImageBtn" onclick="navigateGallery(1)" style="z-index: 10;">
                        <i class="fas fa-chevron-right"></i>
                    </button>

                    <!-- Loading indicator -->
                    <div id="galleryLoading" class="position-absolute top-50 start-50 translate-middle" style="display: none;">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>

                <!-- Image thumbnails -->
                <div class="border-top border-secondary p-3">
                    <div class="d-flex overflow-auto" id="imageThumbnails" style="gap: 10px;">
                        <!-- Thumbnails will be populated by JavaScript -->
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <div>
                        <span class="text-white" id="currentImageName">Image</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-outline-light me-2" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Fermer
                        </button>
                        <a id="galleryImageLink" href="" target="_blank" class="btn btn-primary me-2">
                            <i class="fas fa-external-link-alt me-1"></i>Ouvrir
                        </a>
                        <button type="button" class="btn btn-success" onclick="downloadCurrentImage()">
                            <i class="fas fa-download me-1"></i>Télécharger
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale de Loading pour Validation -->
<div class="modal fade" id="validationLoadingModal" tabindex="-1" aria-labelledby="validationLoadingModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <!-- Phase de Loading -->
                <div id="loadingPhase" class="validation-phase">
                    <div class="loading-animation mb-4">
                        <div class="spinner-container">
                            <div class="validation-spinner"></div>
                            <div class="spinner-glow"></div>
                        </div>
                    </div>

                    <h4 class="text-white mb-3">
                        <i class="fas fa-cogs me-2 text-info"></i>
                        Validation en cours...
                    </h4>

                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-gradient-info progress-bar-animated progress-bar-striped"
                             role="progressbar"
                             style="width: 0%"
                             id="validationProgressBar">
                        </div>
                    </div>

                    <p class="text-white-50 mb-3" id="loadingStatusText">
                        Initialisation de la validation...
                    </p>

                    <div class="loading-steps">
                        <div class="step-item" id="step1">
                            <i class="fas fa-circle-notch fa-spin text-info me-2"></i>
                            <span class="text-white-50">Vérification du projet</span>
                        </div>
                        <div class="step-item" id="step2">
                            <i class="fas fa-circle text-secondary me-2"></i>
                            <span class="text-secondary">Mise à jour du statut</span>
                        </div>
                        <div class="step-item" id="step3">
                            <i class="fas fa-circle text-secondary me-2"></i>
                            <span class="text-secondary">Envoi de l'email</span>
                        </div>
                        <div class="step-item" id="step4">
                            <i class="fas fa-circle text-secondary me-2"></i>
                            <span class="text-secondary">Finalisation</span>
                        </div>
                    </div>
                </div>

                <!-- Phase de Succès -->
                <div id="successPhase" class="validation-phase" style="display: none;">
                    <div class="success-animation mb-4">
                        <div class="success-checkmark">
                            <div class="check-icon">
                                <span class="icon-line line-tip"></span>
                                <span class="icon-line line-long"></span>
                                <div class="icon-circle"></div>
                                <div class="icon-fix"></div>
                            </div>
                        </div>
                    </div>

                    <h4 class="text-success mb-3">
                        <i class="fas fa-check-circle me-2"></i>
                        Validation Réussie !
                    </h4>

                    <p class="text-white mb-4">
                        Le projet a été validé avec succès.<br>
                        Un email de félicitations a été envoyé à l'étudiant.
                    </p>

                    <div class="success-confetti">
                        <div class="confetti-piece"></div>
                        <div class="confetti-piece"></div>
                        <div class="confetti-piece"></div>
                        <div class="confetti-piece"></div>
                        <div class="confetti-piece"></div>
                    </div>
                </div>

                <!-- Phase d'Erreur -->
                <div id="errorPhase" class="validation-phase" style="display: none;">
                    <div class="error-animation mb-4">
                        <div class="error-icon">
                            <i class="fas fa-exclamation-triangle text-danger"></i>
                        </div>
                    </div>

                    <h4 class="text-danger mb-3">
                        <i class="fas fa-times-circle me-2"></i>
                        Erreur de Validation
                    </h4>

                    <p class="text-white mb-4" id="errorMessage">
                        Une erreur s'est produite lors de la validation.
                    </p>

                    <button type="button" class="btn btn-outline-light" onclick="closeValidationModal()">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Image Gallery Styles */
#gridView {
    display: flex !important;
    flex-wrap: wrap !important;
}

#gridView > div {
    flex: 0 0 auto !important;
    max-width: none !important;
}

/* Force multi-column layout */
@media (min-width: 1400px) {
    #gridView > div { width: 16.666667% !important; } /* 6 columns */
}

@media (min-width: 1200px) and (max-width: 1399px) {
    #gridView > div { width: 25% !important; } /* 4 columns */
}

@media (min-width: 992px) and (max-width: 1199px) {
    #gridView > div { width: 33.333333% !important; } /* 3 columns */
}

@media (min-width: 768px) and (max-width: 991px) {
    #gridView > div { width: 50% !important; } /* 2 columns */
}

@media (max-width: 767px) {
    #gridView > div { width: 50% !important; } /* 2 columns on mobile */
}

.image-card {
    transition: all 0.3s ease;
    overflow: hidden;
    width: 100% !important;
}

.image-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.3);
}

.image-container {
    overflow: hidden;
}

.image-preview {
    transition: all 0.3s ease;
}

.image-preview:hover {
    transform: scale(1.05);
}

.image-card:hover .image-overlay {
    opacity: 1 !important;
}

.image-loading {
    transition: opacity 0.3s ease;
}

/* Gallery view toggle buttons */
.btn-group .btn.active {
    background-color: #17a2b8;
    border-color: #17a2b8;
    color: white;
}

/* Carousel enhancements */
.carousel-image {
    cursor: pointer;
    transition: transform 0.3s ease;
}

.carousel-image:hover {
    transform: scale(1.02);
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    opacity: 1;
}

/* Modal enhancements */
.modal-xl {
    max-width: 95vw;
}

#imageThumbnails {
    max-height: 120px;
}

.thumbnail-item {
    width: 80px;
    height: 80px;
    object-fit: cover;
    cursor: pointer;
    border: 2px solid transparent;
    border-radius: 8px;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.thumbnail-item:hover {
    border-color: #17a2b8;
    transform: scale(1.1);
}

.thumbnail-item.active {
    border-color: #ffc107;
    box-shadow: 0 0 10px rgba(255, 193, 7, 0.5);
}

/* Loading animations */
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.image-loading {
    animation: pulse 1.5s ease-in-out infinite;
}

/* Badge animations */
.badge {
    transition: all 0.3s ease;
}

.image-container:hover .badge {
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-xl {
        max-width: 98vw;
    }

    .image-preview {
        height: 200px !important;
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 10%;
    }

    #imageThumbnails {
        max-height: 80px;
    }

    .thumbnail-item {
        width: 60px;
        height: 60px;
    }
}

/* Custom scrollbar for thumbnails */
#imageThumbnails::-webkit-scrollbar {
    height: 8px;
}

#imageThumbnails::-webkit-scrollbar-track {
    background: #2d3748;
    border-radius: 4px;
}

#imageThumbnails::-webkit-scrollbar-thumb {
    background: #4a5568;
    border-radius: 4px;
}

#imageThumbnails::-webkit-scrollbar-thumb:hover {
    background: #718096;
}

/* Text truncation */
.text-truncate {
    max-width: 200px;
}

/* File type specific colors */
.file-pdf { color: #dc3545; }
.file-video { color: #ffc107; }
.file-audio { color: #28a745; }
.file-archive { color: #fd7e14; }
.file-image { color: #17a2b8; }

/* Loading animations for validation button */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes pulse-glow {
    0% { box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); }
    50% { box-shadow: 0 0 20px rgba(40, 167, 69, 0.8), 0 0 30px rgba(40, 167, 69, 0.6); }
    100% { box-shadow: 0 0 5px rgba(40, 167, 69, 0.5); }
}

@keyframes progress-bar {
    0% { width: 0%; }
    25% { width: 25%; }
    50% { width: 50%; }
    75% { width: 75%; }
    100% { width: 100%; }
}

.btn-loading {
    position: relative;
    overflow: hidden;
    animation: pulse-glow 2s ease-in-out infinite;
}

.btn-loading::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    animation: shimmer 1.5s ease-in-out infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.validation-progress {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: rgba(40, 167, 69, 0.3);
    z-index: 9999;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.validation-progress.active {
    opacity: 1;
}

.validation-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #28a745, #20c997);
    width: 0%;
    animation: progress-bar 3s ease-in-out;
}

.loading-spinner {
    animation: spin 1s linear infinite;
}

.btn-success-animated {
    background: linear-gradient(45deg, #28a745, #20c997);
    border: none;
    transition: all 0.3s ease;
}

.btn-success-animated:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
}

/* Notification animations */
@keyframes slideInRight {
    0% {
        transform: translateX(100%);
        opacity: 0;
    }
    100% {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    0% {
        transform: translateX(0);
        opacity: 1;
    }
    100% {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Validation Modal Styles */
#validationLoadingModal .modal-content {
    border-radius: 20px;
    backdrop-filter: blur(10px);
    background: rgba(33, 37, 41, 0.95) !important;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.validation-phase {
    transition: all 0.5s ease;
}

/* Loading Spinner Animation */
.spinner-container {
    position: relative;
    width: 80px;
    height: 80px;
    margin: 0 auto;
}

.validation-spinner {
    width: 80px;
    height: 80px;
    border: 4px solid rgba(23, 162, 184, 0.3);
    border-top: 4px solid #17a2b8;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    position: relative;
    z-index: 2;
}

.spinner-glow {
    position: absolute;
    top: -10px;
    left: -10px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(23, 162, 184, 0.3) 0%, transparent 70%);
    animation: pulse-glow 2s ease-in-out infinite;
    z-index: 1;
}

/* Progress Bar Enhancement */
.progress {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.3s ease;
    background: linear-gradient(45deg, #17a2b8, #20c997) !important;
}

/* Loading Steps */
.loading-steps {
    text-align: left;
    max-width: 300px;
    margin: 0 auto;
}

.step-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.step-item.active {
    background: rgba(23, 162, 184, 0.2);
    border-left: 3px solid #17a2b8;
}

.step-item.completed {
    background: rgba(40, 167, 69, 0.2);
    border-left: 3px solid #28a745;
}

.step-item.completed i {
    color: #28a745 !important;
}

/* Success Animation */
.success-checkmark {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: block;
    stroke-width: 2;
    stroke: #28a745;
    stroke-miterlimit: 10;
    margin: 0 auto;
    box-shadow: inset 0px 0px 0px #28a745;
    animation: fill 0.4s ease-in-out 0.4s forwards, scale 0.3s ease-in-out 0.9s both;
    position: relative;
}

.success-checkmark .check-icon {
    width: 56px;
    height: 56px;
    position: absolute;
    left: 12px;
    top: 12px;
    z-index: 1;
    transform: scale(0);
    animation: scale 0.3s ease-in-out 0.9s both;
}

.check-icon .icon-line {
    height: 2px;
    background-color: #28a745;
    display: block;
    border-radius: 2px;
    position: absolute;
    z-index: 10;
}

.check-icon .line-tip {
    top: 19px;
    left: 14px;
    width: 25px;
    transform: rotate(45deg);
    animation: icon-line-tip 0.75s;
}

.check-icon .line-long {
    top: 38px;
    right: 8px;
    width: 47px;
    transform: rotate(-45deg);
    animation: icon-line-long 0.75s;
}

.check-icon .icon-circle {
    top: -2px;
    left: -2px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 2px solid rgba(40, 167, 69, 0.2);
    position: absolute;
}

.check-icon .icon-fix {
    top: 8px;
    width: 5px;
    left: 26px;
    z-index: 1;
    height: 85px;
    position: absolute;
    transform: rotate(-45deg);
}

@keyframes icon-line-tip {
    0% { width: 0; left: 1px; top: 19px; }
    54% { width: 0; left: 1px; top: 19px; }
    70% { width: 50px; left: -8px; top: 37px; }
    84% { width: 17px; left: 21px; top: 48px; }
    100% { width: 25px; left: 14px; top: 45px; }
}

@keyframes icon-line-long {
    0% { width: 0; right: 46px; top: 54px; }
    65% { width: 0; right: 46px; top: 54px; }
    84% { width: 55px; right: 0px; top: 35px; }
    100% { width: 47px; right: 8px; top: 38px; }
}

@keyframes scale {
    0%, 50% { transform: scale(0); }
    100% { transform: scale(1); }
}

@keyframes fill {
    100% { box-shadow: inset 0px 0px 0px 60px #28a745; }
}

/* Success Confetti */
.success-confetti {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
}

.confetti-piece {
    position: absolute;
    width: 10px;
    height: 10px;
    background: #ffc107;
    animation: confetti-fall 3s ease-in-out infinite;
}

.confetti-piece:nth-child(1) {
    left: 10%;
    animation-delay: 0s;
    background: #28a745;
}

.confetti-piece:nth-child(2) {
    left: 30%;
    animation-delay: 0.5s;
    background: #17a2b8;
}

.confetti-piece:nth-child(3) {
    left: 50%;
    animation-delay: 1s;
    background: #ffc107;
}

.confetti-piece:nth-child(4) {
    left: 70%;
    animation-delay: 1.5s;
    background: #dc3545;
}

.confetti-piece:nth-child(5) {
    left: 90%;
    animation-delay: 2s;
    background: #6f42c1;
}

@keyframes confetti-fall {
    0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
    }
    100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
    }
}

/* Error Animation */
.error-animation {
    animation: shake 0.5s ease-in-out;
}

.error-icon {
    font-size: 4rem;
    animation: pulse 2s ease-in-out infinite;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

/* Modal entrance animation */
#validationLoadingModal.show .modal-dialog {
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    0% {
        transform: translateY(-50px) scale(0.9);
        opacity: 0;
    }
    100% {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}
</style>

<script>
// Enhanced Image Gallery Management
let currentGalleryImages = [];
let currentImageIndex = 0;

// Initialize gallery data on page load
document.addEventListener('DOMContentLoaded', function() {
    // Collect all image data
    const imageElements = document.querySelectorAll('.image-preview');
    currentGalleryImages = Array.from(imageElements).map((img, index) => ({
        src: img.src,
        alt: img.alt,
        filename: img.dataset.filename || `Image ${index + 1}`,
        index: index
    }));

    // Initialize gallery view buttons
    const gridBtn = document.getElementById('gridViewBtn');
    const carouselBtn = document.getElementById('carouselViewBtn');

    if (gridBtn && carouselBtn) {
        gridBtn.classList.add('active');
        toggleGalleryView('grid');
    }

    // Auto-dismiss flash messages
    setTimeout(function() {
        const successAlert = document.getElementById('successAlert');
        const errorAlert = document.getElementById('errorAlert');

        if (successAlert) {
            successAlert.style.transition = 'opacity 0.5s';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }

        if (errorAlert) {
            errorAlert.style.transition = 'opacity 0.5s';
            errorAlert.style.opacity = '0';
            setTimeout(() => errorAlert.remove(), 500);
        }
    }, 4000);
});

// Toggle between grid and carousel view
function toggleGalleryView(viewType) {
    const gridView = document.getElementById('gridView');
    const carouselView = document.getElementById('carouselView');
    const gridBtn = document.getElementById('gridViewBtn');
    const carouselBtn = document.getElementById('carouselViewBtn');

    if (viewType === 'grid') {
        gridView.style.display = 'block';
        carouselView.style.display = 'none';
        gridBtn.classList.add('active');
        carouselBtn.classList.remove('active');
    } else {
        gridView.style.display = 'none';
        carouselView.style.display = 'block';
        gridBtn.classList.remove('active');
        carouselBtn.classList.add('active');
    }
}

// Open enhanced image gallery modal
function openImageGallery(startIndex = 0) {
    if (currentGalleryImages.length === 0) return;

    currentImageIndex = startIndex;
    const modal = new bootstrap.Modal(document.getElementById('imageGalleryModal'));

    // Update modal content
    updateGalleryModal();

    // Generate thumbnails
    generateThumbnails();

    // Show modal
    modal.show();

    // Add keyboard navigation
    document.addEventListener('keydown', handleKeyboardNavigation);
}

// Update gallery modal content
function updateGalleryModal() {
    if (currentGalleryImages.length === 0) return;

    const currentImage = currentGalleryImages[currentImageIndex];
    const modalImage = document.getElementById('galleryModalImage');
    const imageCounter = document.getElementById('imageCounter');
    const imageName = document.getElementById('currentImageName');
    const imageLink = document.getElementById('galleryImageLink');
    const loading = document.getElementById('galleryLoading');

    // Show loading
    loading.style.display = 'block';
    modalImage.style.opacity = '0.5';

    // Update image
    modalImage.onload = function() {
        loading.style.display = 'none';
        modalImage.style.opacity = '1';
    };

    modalImage.src = currentImage.src;
    modalImage.alt = currentImage.alt;
    imageCounter.textContent = `${currentImageIndex + 1}/${currentGalleryImages.length}`;
    imageName.textContent = currentImage.filename;
    imageLink.href = currentImage.src;

    // Update navigation buttons
    const prevBtn = document.getElementById('prevImageBtn');
    const nextBtn = document.getElementById('nextImageBtn');

    prevBtn.style.display = currentImageIndex > 0 ? 'block' : 'none';
    nextBtn.style.display = currentImageIndex < currentGalleryImages.length - 1 ? 'block' : 'none';

    // Update thumbnails active state
    updateThumbnailsActive();
}

// Generate thumbnail navigation
function generateThumbnails() {
    const thumbnailContainer = document.getElementById('imageThumbnails');
    thumbnailContainer.innerHTML = '';

    currentGalleryImages.forEach((image, index) => {
        const thumbnail = document.createElement('img');
        thumbnail.src = image.src;
        thumbnail.alt = image.alt;
        thumbnail.className = `thumbnail-item ${index === currentImageIndex ? 'active' : ''}`;
        thumbnail.onclick = () => {
            currentImageIndex = index;
            updateGalleryModal();
        };

        thumbnailContainer.appendChild(thumbnail);
    });
}

// Update active thumbnail
function updateThumbnailsActive() {
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    thumbnails.forEach((thumb, index) => {
        thumb.classList.toggle('active', index === currentImageIndex);
    });
}

// Navigate gallery (prev/next)
function navigateGallery(direction) {
    const newIndex = currentImageIndex + direction;

    if (newIndex >= 0 && newIndex < currentGalleryImages.length) {
        currentImageIndex = newIndex;
        updateGalleryModal();
    }
}

// Keyboard navigation
function handleKeyboardNavigation(event) {
    const modal = document.getElementById('imageGalleryModal');
    if (!modal.classList.contains('show')) return;

    switch(event.key) {
        case 'ArrowLeft':
            event.preventDefault();
            navigateGallery(-1);
            break;
        case 'ArrowRight':
            event.preventDefault();
            navigateGallery(1);
            break;
        case 'Escape':
            event.preventDefault();
            bootstrap.Modal.getInstance(modal).hide();
            break;
    }
}

// Download current image
function downloadCurrentImage() {
    if (currentGalleryImages.length === 0) return;

    const currentImage = currentGalleryImages[currentImageIndex];
    const link = document.createElement('a');
    link.href = currentImage.src;
    link.download = currentImage.filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Clean up event listeners when modal is hidden
document.getElementById('imageGalleryModal').addEventListener('hidden.bs.modal', function() {
    document.removeEventListener('keydown', handleKeyboardNavigation);
});

// Preload images for better performance
function preloadImages() {
    currentGalleryImages.forEach(image => {
        const img = new Image();
        img.src = image.src;
    });
}

// Call preload after a short delay
setTimeout(preloadImages, 1000);

// Legacy function for backward compatibility
function openImageModal(imageSrc, imageName) {
    // Find the index of this image in the gallery
    const imageIndex = currentGalleryImages.findIndex(img => img.src === imageSrc);
    openImageGallery(imageIndex >= 0 ? imageIndex : 0);
}

// Design Project Action Functions
function validateDesignProject(projectId) {
    if (confirm('Êtes-vous sûr de vouloir valider ce projet design ?\n\nUn email de félicitations sera envoyé à l\'étudiant.')) {
        // Show validation modal
        showValidationModal();

        // Start validation process
        startValidationProcess(projectId);
    }
}

function rejectDesignProject(projectId) {
    if (!confirm('Êtes-vous sûr de vouloir rejeter ce projet design ?')) {
        return;
    }

    const rejectUrl = `{{ url('evc/app/admin/design-projects/reject') }}/${projectId}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    fetch(rejectUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message || 'Projet rejeté avec succès.', 'success');
            setTimeout(() => window.location.reload(), 800);
            return;
        }
        throw new Error(data.error || 'Erreur de rejet');
    })
    .catch(err => {
        console.error(err);
        showNotification(err.message || 'Erreur lors du rejet', 'error');
    });
}

// Show validation modal
function showValidationModal() {
    const modal = new bootstrap.Modal(document.getElementById('validationLoadingModal'), {
        backdrop: 'static',
        keyboard: false
    });

    // Reset modal to loading phase
    resetModalToLoading();

    // Show modal
    modal.show();
}

// Reset modal to loading phase
function resetModalToLoading() {
    // Hide all phases
    document.getElementById('loadingPhase').style.display = 'block';
    document.getElementById('successPhase').style.display = 'none';
    document.getElementById('errorPhase').style.display = 'none';

    // Reset progress bar
    document.getElementById('validationProgressBar').style.width = '0%';

    // Reset steps
    const steps = document.querySelectorAll('.step-item');
    steps.forEach((step, index) => {
        step.classList.remove('active', 'completed');
        const icon = step.querySelector('i');
        const text = step.querySelector('span');

        if (index === 0) {
            step.classList.add('active');
            icon.className = 'fas fa-circle-notch fa-spin text-info me-2';
            text.className = 'text-white-50';
        } else {
            icon.className = 'fas fa-circle text-secondary me-2';
            text.className = 'text-secondary';
        }
    });

    // Reset status text
    document.getElementById('loadingStatusText').textContent = 'Initialisation de la validation...';
}

// Start validation process with animated steps
function startValidationProcess(projectId) {
    const steps = [
        { id: 'step1', text: 'Vérification du projet...', progress: 25 },
        { id: 'step2', text: 'Mise à jour du statut...', progress: 50 },
        { id: 'step3', text: 'Envoi de l\'email...', progress: 75 },
        { id: 'step4', text: 'Finalisation...', progress: 100 }
    ];

    let currentStep = 0;

    // Animate steps
    const stepInterval = setInterval(() => {
        if (currentStep < steps.length) {
            animateStep(steps[currentStep]);
            currentStep++;
        } else {
            clearInterval(stepInterval);
            // Start actual AJAX request after animation
            performValidationRequest(projectId);
        }
    }, 800);
}

// Animate individual step
function animateStep(step) {
    const stepElement = document.getElementById(step.id);
    const icon = stepElement.querySelector('i');
    const text = stepElement.querySelector('span');
    const statusText = document.getElementById('loadingStatusText');
    const progressBar = document.getElementById('validationProgressBar');

    // Remove active from previous step
    document.querySelectorAll('.step-item').forEach(s => s.classList.remove('active'));

    // Activate current step
    stepElement.classList.add('active');
    icon.className = 'fas fa-circle-notch fa-spin text-info me-2';
    text.className = 'text-white-50';

    // Update status text
    statusText.textContent = step.text;

    // Update progress bar
    progressBar.style.width = step.progress + '%';

    // Complete previous steps
    const stepIndex = parseInt(step.id.replace('step', '')) - 1;
    for (let i = 0; i < stepIndex; i++) {
        const prevStep = document.getElementById(`step${i + 1}`);
        prevStep.classList.remove('active');
        prevStep.classList.add('completed');

        const prevIcon = prevStep.querySelector('i');
        const prevText = prevStep.querySelector('span');
        prevIcon.className = 'fas fa-check-circle text-success me-2';
        prevText.className = 'text-success';
    }
}

// Perform actual validation request
function performValidationRequest(projectId) {
    const validationUrl = `{{ url('evc/app/admin/design-projects/validate') }}/${projectId}`;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

    console.log('CSRF Token:', csrfToken);
    console.log('Validation URL:', validationUrl);

    fetch(validationUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);

        // Check if response is HTML (redirect/error page)
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('text/html')) {
            throw new Error(`Réponse HTML reçue au lieu de JSON. Status: ${response.status}. Vérifiez l'authentification admin.`);
        }

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Complete all steps
            completeAllSteps();

            // Show success phase after a delay
            setTimeout(() => {
                showSuccessPhase();

                // Auto-close and reload after success
                setTimeout(() => {
                    closeValidationModal();
                    window.location.reload();
                }, 3000);
            }, 1000);
        } else {
            throw new Error(data.error || 'Erreur de validation');
        }
    })
    .catch(error => {
        console.error('Erreur complète:', error);

        let errorMsg = error.message;
        if (error.message.includes('HTML reçue')) {
            errorMsg = 'Problème d\'authentification admin. Veuillez vous reconnecter.';
        }

        // Show error phase
        showErrorPhase(errorMsg);
    });
}

// Complete all steps
function completeAllSteps() {
    const steps = document.querySelectorAll('.step-item');
    steps.forEach(step => {
        step.classList.remove('active');
        step.classList.add('completed');

        const icon = step.querySelector('i');
        const text = step.querySelector('span');
        icon.className = 'fas fa-check-circle text-success me-2';
        text.className = 'text-success';
    });

    // Complete progress bar
    document.getElementById('validationProgressBar').style.width = '100%';
    document.getElementById('loadingStatusText').textContent = 'Validation terminée avec succès !';
}

// Show success phase
function showSuccessPhase() {
    document.getElementById('loadingPhase').style.display = 'none';
    document.getElementById('successPhase').style.display = 'block';
    document.getElementById('errorPhase').style.display = 'none';
}

// Show error phase
function showErrorPhase(errorMessage) {
    document.getElementById('loadingPhase').style.display = 'none';
    document.getElementById('successPhase').style.display = 'none';
    document.getElementById('errorPhase').style.display = 'block';

    // Update error message
    document.getElementById('errorMessage').textContent = errorMessage;
}

// Close validation modal
function closeValidationModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('validationLoadingModal'));
    if (modal) {
        modal.hide();
    }
}

// PDF Viewer Functions
let currentPdfUrl = '';
let currentPdfName = '';
let currentZoom = 100;

// Open PDF viewer
function openPdfViewer(pdfUrl, fileName) {
    currentPdfUrl = pdfUrl;
    currentPdfName = fileName || 'Document PDF';

    // Update modal title and filename
    document.getElementById('pdfFileName').textContent = currentPdfName;

    // Show loading indicator
    document.getElementById('pdfLoadingIndicator').style.display = 'block';
    document.getElementById('pdfFrame').style.display = 'none';
    document.getElementById('pdfErrorMessage').style.display = 'none';

    // Reset zoom
    currentZoom = 100;
    document.getElementById('zoomLevel').textContent = '100%';

    // Load PDF in iframe
    const iframe = document.getElementById('pdfFrame');
    iframe.src = pdfUrl + '#toolbar=1&navpanes=1&scrollbar=1&page=1&view=FitH';

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('pdfViewerModal'));
    modal.show();
}

// PDF load success
function onPdfLoad() {
    document.getElementById('pdfLoadingIndicator').style.display = 'none';
    document.getElementById('pdfFrame').style.display = 'block';
    document.getElementById('pdfErrorMessage').style.display = 'none';
}

// PDF load error
function onPdfError() {
    document.getElementById('pdfLoadingIndicator').style.display = 'none';
    document.getElementById('pdfFrame').style.display = 'none';
    document.getElementById('pdfErrorMessage').style.display = 'block';
}

// Retry PDF load
function retryPdfLoad() {
    if (currentPdfUrl) {
        openPdfViewer(currentPdfUrl, currentPdfName);
    }
}

// Zoom functions
function zoomIn() {
    if (currentZoom < 200) {
        currentZoom += 25;
        updateZoom();
    }
}

function zoomOut() {
    if (currentZoom > 50) {
        currentZoom -= 25;
        updateZoom();
    }
}

function resetZoom() {
    currentZoom = 100;
    updateZoom();
}

function updateZoom() {
    document.getElementById('zoomLevel').textContent = currentZoom + '%';
    const iframe = document.getElementById('pdfFrame');
    if (iframe.src && currentPdfUrl) {
        // Update iframe with new zoom
        iframe.src = currentPdfUrl + '#toolbar=1&navpanes=1&scrollbar=1&page=1&view=FitH&zoom=' + currentZoom;
    }
}

// Download current PDF
function downloadCurrentPdf() {
    if (currentPdfUrl) {
        const link = document.createElement('a');
        link.href = currentPdfUrl;
        link.download = currentPdfName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
}

// Open PDF in new tab
function openPdfInNewTab() {
    if (currentPdfUrl) {
        window.open(currentPdfUrl, '_blank');
    }
}

// Create animated progress bar
function createProgressBar() {
    const progressContainer = document.createElement('div');
    progressContainer.className = 'validation-progress';

    const progressBar = document.createElement('div');
    progressBar.className = 'validation-progress-bar';

    progressContainer.appendChild(progressBar);
    return progressContainer;
}

// Start loading animation
function startValidationLoading(btn, progressBar) {
    // Animate button
    btn.classList.add('btn-loading');
    btn.innerHTML = `
        <i class="fas fa-spinner loading-spinner me-2"></i>
        <span class="loading-text">Validation en cours...</span>
    `;
    btn.disabled = true;

    // Show progress bar
    progressBar.classList.add('active');

    // Animate loading text
    animateLoadingText(btn);
}

// Animate loading text
function animateLoadingText(btn) {
    const loadingTexts = [
        'Validation en cours...',
        'Vérification du projet...',
        'Envoi de l\'email...',
        'Finalisation...'
    ];

    let textIndex = 0;
    const textInterval = setInterval(() => {
        const textElement = btn.querySelector('.loading-text');
        if (textElement && textIndex < loadingTexts.length - 1) {
            textIndex++;
            textElement.textContent = loadingTexts[textIndex];
        } else {
            clearInterval(textInterval);
        }
    }, 800);

    // Store interval for cleanup
    btn.dataset.textInterval = textInterval;
}

// Show success animation
function showValidationSuccess(btn, progressBar) {
    // Clear text animation
    if (btn.dataset.textInterval) {
        clearInterval(btn.dataset.textInterval);
    }

    // Success button animation
    btn.classList.remove('btn-loading');
    btn.classList.add('btn-success-animated');
    btn.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        <span>Validé avec succès !</span>
    `;

    // Complete progress bar
    const progressBarElement = progressBar.querySelector('.validation-progress-bar');
    progressBarElement.style.width = '100%';
    progressBarElement.style.background = 'linear-gradient(90deg, #28a745, #20c997)';

    // Remove progress bar after animation
    setTimeout(() => {
        progressBar.classList.remove('active');
        setTimeout(() => progressBar.remove(), 300);
    }, 1000);
}

// Show error animation
function showValidationError(btn, progressBar, originalContent) {
    // Clear text animation
    if (btn.dataset.textInterval) {
        clearInterval(btn.dataset.textInterval);
    }

    // Error button animation
    btn.classList.remove('btn-loading');
    btn.classList.add('btn-danger');
    btn.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>
        <span>Erreur de validation</span>
    `;

    // Error progress bar
    const progressBarElement = progressBar.querySelector('.validation-progress-bar');
    progressBarElement.style.background = 'linear-gradient(90deg, #dc3545, #c82333)';
    progressBarElement.style.width = '100%';

    // Restore button after delay
    setTimeout(() => {
        btn.classList.remove('btn-danger');
        btn.innerHTML = originalContent;
        btn.disabled = false;

        progressBar.classList.remove('active');
        setTimeout(() => progressBar.remove(), 300);
    }, 3000);
}

// Show success notification
function showSuccessNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-success position-fixed';
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 300px;
        animation: slideInRight 0.5s ease;
    `;
    notification.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        ${message.replace('\n', '<br>')}
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.5s ease';
        setTimeout(() => notification.remove(), 500);
    }, 4000);
}

// Show error notification
function showErrorNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-danger position-fixed';
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 300px;
        animation: slideInRight 0.5s ease;
    `;
    notification.innerHTML = `
        <i class="fas fa-exclamation-triangle me-2"></i>
        ${message}
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.5s ease';
        setTimeout(() => notification.remove(), 500);
    }, 5000);
}

// Simple validation function only

function editDesignProject(projectId) {
    // Redirect to edit page
    window.location.href = `{{ url('evc/app/admin/design-projects/edit') }}/${projectId}`;
}

function deleteDesignProject(projectId) {
    if (confirm('⚠️ ATTENTION ⚠️\n\nÊtes-vous absolument sûr de vouloir supprimer ce projet design ?\n\nCette action est IRRÉVERSIBLE et supprimera :\n- Le projet et toutes ses données\n- Tous les fichiers associés\n- L\'historique du projet\n\nTapez "SUPPRIMER" pour confirmer :')) {
        const confirmation = prompt('Pour confirmer la suppression, tapez exactement "SUPPRIMER" :');

        if (confirmation === 'SUPPRIMER') {
            // Show loading state
            const btn = event.target.closest('button');
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Suppression...';
            btn.disabled = true;

            // Send AJAX request
            const deleteUrl = '{{ route("admin.design-projects.delete", ["id" => "__ID__"]) }}'.replace('__ID__', projectId);
            fetch(deleteUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Projet supprimé avec succès !', 'success');

                    // Redirect to previous page after 2 seconds
                    setTimeout(() => {
                        window.location.href = document.referrer || '/admin/students';
                    }, 2000);
                } else {
                    throw new Error(data.message || 'Erreur lors de la suppression');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showNotification('Erreur lors de la suppression du projet', 'error');

                // Restore button state
                btn.innerHTML = originalContent;
                btn.disabled = false;
            });
        } else if (confirmation !== null) {
            showNotification('Suppression annulée - confirmation incorrecte', 'warning');
        }
    }
}

// Utility function to show notifications
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.notification-toast');
    existingNotifications.forEach(notification => notification.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show notification-toast`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    `;

    const icon = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-exclamation-triangle',
        'warning': 'fas fa-exclamation-triangle',
        'info': 'fas fa-info-circle'
    }[type] || 'fas fa-info-circle';

    notification.innerHTML = `
        <i class="${icon} me-2"></i>
        <strong>${type === 'error' ? 'Erreur !' : type === 'success' ? 'Succès !' : type === 'warning' ? 'Attention !' : 'Information !'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
</script>
@endsection
