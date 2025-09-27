@extends('layouts.ki-admin')

@section('title', 'Voir le Projet - ' . $project->title)

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-eye text-primary me-2"></i>
                        Détails du Projet
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.design-graphique') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('design-graphique.tp.tous') }}">Tous les TP</a></li>
                            <li class="breadcrumb-item active">{{ $project->title }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('design-graphique.tp.tous') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour à la liste
                    </a>
                    @if($project->status === 'en_cours')
                        <a href="{{ route('design-graphique.tp.modifier', $project->id) }}" class="btn btn-warning ms-2">
                            <i class="fas fa-edit me-1"></i>
                            Modifier
                        </a>
                    @endif
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
                    <span class="badge {{ $project->status === 'valide' ? 'bg-success' : 'bg-warning' }} fs-6">
                        {{ $project->status === 'valide' ? 'Validé' : 'En cours' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Titre du projet</label>
                            <p class="fs-5 mb-0">{{ $project->title }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted">Catégorie</label>
                            <p class="mb-0">
                                <span class="badge bg-info">{{ ucfirst($project->category) }}</span>
                            </p>
                        </div>
                        @if($project->description)
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold text-muted">Description</label>
                            <p class="mb-0">{{ $project->description }}</p>
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

            <!-- Images du projet -->
            @if($project->images && $project->images->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-images me-2"></i>
                        Images du Projet ({{ $project->images->count() }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($project->images as $image)
                        <div class="col-md-4 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="position-relative">
                                    <img src="{{ $image->url }}" 
                                         class="card-img-top" 
                                         alt="{{ $image->original_name }}"
                                         style="height: 200px; object-fit: cover;"
                                         onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-dark bg-opacity-75">
                                            {{ number_format($image->file_size / 1024, 0) }} KB
                                        </span>
                                    </div>
                                </div>
                                <div class="card-body p-2">
                                    <p class="card-text small mb-0 text-truncate" title="{{ $image->original_name }}">
                                        {{ $image->original_name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistiques -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>
                        Statistiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $project->images ? $project->images->count() : 0 }}</h4>
                                <small class="text-muted">Images</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-info mb-0">
                                @if($project->images && $project->images->count() > 0)
                                    {{ number_format($project->images->sum('file_size') / 1024 / 1024, 1) }} MB
                                @else
                                    0 MB
                                @endif
                            </h4>
                            <small class="text-muted">Taille totale</small>
                        </div>
                        <div class="col-12">
                            <hr>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-clock me-1"></i>
                                Créé il y a {{ \Carbon\Carbon::parse($project->created_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

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
                        @if($project->status === 'en_cours')
                            <a href="{{ route('design-graphique.tp.modifier', $project->id) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-2"></i>
                                Modifier le projet
                            </a>
                            <button type="button" class="btn btn-danger" onclick="deleteProject({{ $project->id }})">
                                <i class="fas fa-trash me-2"></i>
                                Supprimer le projet
                            </button>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Ce projet est validé et ne peut plus être modifié.
                            </div>
                        @endif
                        <a href="{{ route('design-graphique.tp.tous') }}" class="btn btn-secondary">
                            <i class="fas fa-list me-2"></i>
                            Voir tous les projets
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($project->status === 'en_cours')
<script>
function deleteProject(projectId) {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/evc/compte/design-graphique/tp/supprimer/${projectId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Supprimé !', data.message, 'success').then(() => {
                        window.location.href = '{{ route("design-graphique.tp.tous") }}';
                    });
                } else {
                    Swal.fire('Erreur !', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Erreur !', 'Une erreur est survenue lors de la suppression.', 'error');
            });
        }
    });
}
</script>
@endif
@endsection
