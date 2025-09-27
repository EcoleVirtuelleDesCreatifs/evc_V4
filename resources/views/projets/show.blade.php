@extends('layouts.ki-admin')

@section('title', 'Détails du Projet - ' . $project['title'])
@section('page-title', 'Détails du Projet')

@section('content')
<div class="container-fluid">
    <!-- En-tête avec titre et actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">{{ $project['title'] }}</h2>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('design-graphique.projets.index') }}">Projets</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $project['title'] }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('design-graphique.projets.index') }}" 
                       class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    @if($project['status'] !== 'validated')
                    <a href="{{ route('design-graphique.projets.edit', $project['id']) }}" 
                       class="btn btn-primary">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <button type="button" class="btn btn-danger" 
                            onclick="confirmDelete({{ $project['id'] }}, '{{ addslashes($project['title']) }}')">
                        <i class="fas fa-trash"></i> Supprimer
                    </button>
                    @else
                    <div class="alert alert-success mb-0 d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <span><strong>Projet Validé</strong> - Ce projet ne peut plus être modifié ni supprimé.</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations principales -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations du Projet
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Titre du projet</label>
                            <p class="form-control-plaintext">{{ $project['title'] }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Type de projet</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-info">{{ ucfirst($project['project_type']) }}</span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Mode de projet</label>
                            <p class="form-control-plaintext">
                                <span class="badge {{ $project['project_mode'] === 'solo' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ $project['project_mode'] === 'solo' ? 'Projet Solo' : 'Projet Groupe' }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Statut</label>
                            <p class="form-control-plaintext">
                                @php
                                    $statusColors = [
                                        'draft' => 'bg-secondary',
                                        'active' => 'bg-warning',
                                        'completed' => 'bg-info',
                                        'validated' => 'bg-success',
                                        'cancelled' => 'bg-danger'
                                    ];
                                    $statusLabels = [
                                        'draft' => 'Brouillon',
                                        'active' => 'En cours',
                                        'completed' => 'Terminé',
                                        'validated' => 'Validé',
                                        'cancelled' => 'Annulé'
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$project['status']] ?? 'bg-secondary' }}">
                                    {{ $statusLabels[$project['status']] ?? $project['status'] }}
                                </span>
                            </p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <div class="form-control-plaintext">
                                @if($project['description'])
                                    {!! nl2br(e($project['description'])) !!}
                                @else
                                    <em class="text-muted">Aucune description fournie</em>
                                @endif
                            </div>
                        </div>
                        @if($project['reference_url'])
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">URL de référence</label>
                            <p class="form-control-plaintext">
                                <a href="{{ $project['reference_url'] }}" target="_blank" class="text-decoration-none">
                                    {{ $project['reference_url'] }} <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Logiciels utilisés -->
            @if(isset($project['software_used_array']) && !empty($project['software_used_array']))
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>
                        Logiciels Utilisés
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($project['software_used_array'] as $software)
                            <span class="badge bg-secondary fs-6 px-3 py-2">
                                {{ ucfirst($software) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Fichiers du projet -->
            @if(isset($project['files']) && !empty($project['files']))
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file me-2"></i>
                        Fichiers du Projet ({{ count($project['files']) }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($project['files'] as $file)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border">
                                @php
                                    $isImage = in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                @endphp
                                
                                @if($isImage)
                                    <img src="{{ asset($file['path']) }}" 
                                         class="card-img-top" 
                                         alt="{{ $file['name'] }}"
                                         style="height: 200px; object-fit: cover;">
                                @endif
                                
                                <div class="card-body text-center">
                                    @if(!$isImage)
                                        @php
                                            $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                                            $iconClass = match($extension) {
                                                'pdf' => 'fas fa-file-pdf text-danger',
                                                'doc', 'docx' => 'fas fa-file-word text-primary',
                                                'xls', 'xlsx' => 'fas fa-file-excel text-success',
                                                'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
                                                'zip', 'rar' => 'fas fa-file-archive text-secondary',
                                                default => 'fas fa-file text-muted'
                                            };
                                        @endphp
                                        <i class="{{ $iconClass }} fa-2x mb-2"></i>
                                    @endif
                                    
                                    <h6 class="card-title">{{ $file['name'] }}</h6>
                                    <p class="text-muted small">
                                        {{ number_format($file['file_size'] / 1024, 1) }} KB
                                    </p>
                                    
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ asset($file['path']) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <a href="{{ asset($file['path']) }}" download="{{ $file['name'] }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar avec métadonnées -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Métadonnées
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Créé le</label>
                        <p class="form-control-plaintext">
                            {{ isset($project['created_at_formatted']) ? $project['created_at_formatted'] : date('d/m/Y H:i', strtotime($project['created_at'])) }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Dernière modification</label>
                        <p class="form-control-plaintext">
                            {{ isset($project['updated_at_formatted']) ? $project['updated_at_formatted'] : date('d/m/Y H:i', strtotime($project['updated_at'])) }}
                        </p>
                    </div>
                    @if(isset($project['progress_percentage']))
                    <div class="mb-3">
                        <label class="form-label fw-bold">Progression</label>
                        <div class="progress mb-2" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ $project['progress_percentage'] }}%;" role="progressbar"></div>
                        </div>
                        <small class="text-muted">{{ $project['progress_percentage'] }}%</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le projet <strong id="projectTitle"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible et supprimera également tous les fichiers associés.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(projectId, projectTitle) {
    document.getElementById('projectTitle').textContent = projectTitle;
    document.getElementById('deleteForm').action = `/evc/compte/design-graphique/projets/${projectId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection
