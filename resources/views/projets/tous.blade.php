@extends('layouts.ki-admin')

@section('title', 'Tous les Projets - Design Graphique')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold">
                        <i class="fas fa-th-large me-2 text-success"></i>
                        Tous les Projets
                    </h2>
                    <p class="text-muted mb-0">Vue complète de tous vos projets de design graphique</p>
                </div>
                <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    @if(isset($stats))
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['solo_projects'] ?? 0 }}</h3>
                    <p class="mb-0">Projets Solo</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['group_projects'] ?? 0 }}</h3>
                    <p class="mb-0">Projets Groupe</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $stats['total_projects'] ?? 0 }}</h3>
                    <p class="mb-0">Total</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Liste des projets -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste Complète des Projets
                        @if(isset($projects))
                            <span class="badge bg-light text-dark ms-2">{{ count($projects) }}</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if(isset($projects) && count($projects) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Titre</th>
                                        <th>Catégorie</th>
                                        <th>Type</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                    <tr>
                                        <td>
                                            <strong>{{ $project['title'] ?? 'Sans titre' }}</strong>
                                            @if(isset($project['description']))
                                            <br><small class="text-muted">{{ Str::limit($project['description'], 50) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($project['category'] ?? '') === 'solo')
                                                <span class="badge bg-primary">
                                                    <i class="fas fa-user me-1"></i>Solo
                                                </span>
                                            @else
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-users me-1"></i>Groupe
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $project['project_type'] ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $status = $project['status'] ?? '';
                                                $statusConfig = [
                                                    'validated' => ['label' => 'Validé', 'class' => 'bg-success'],
                                                    'completed' => ['label' => 'Terminé', 'class' => 'bg-info'],
                                                    'active' => ['label' => 'En cours', 'class' => 'bg-primary'],
                                                    'draft' => ['label' => 'Brouillon', 'class' => 'bg-secondary'],
                                                    'cancelled' => ['label' => 'Annulé', 'class' => 'bg-danger'],
                                                    'pending' => ['label' => 'En attente', 'class' => 'bg-warning']
                                                ];
                                                $config = $statusConfig[$status] ?? ['label' => ucfirst($status), 'class' => 'bg-secondary'];
                                            @endphp
                                            <span class="badge {{ $config['class'] }}">{{ $config['label'] }}</span>
                                        </td>
                                        <td>
                                            <small>{{ isset($project['created_at']) ? date('d/m/Y', strtotime($project['created_at'])) : '-' }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('design-graphique.projets.show', $project['id']) }}" class="btn btn-sm btn-success" title="Voir le projet">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if(($project['status'] ?? '') !== 'validated')
                                                <a href="{{ route('design-graphique.projets.edit', $project['id']) }}" class="btn btn-sm btn-primary" title="Éditer le projet">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $project['id'] }})" title="Supprimer le projet">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                @else
                                                <span class="badge bg-success ms-2" title="Projet validé - modification et suppression désactivées">
                                                    <i class="fas fa-lock me-1"></i>Validé
                                                </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun projet pour le moment</h5>
                            <p class="text-muted">Créez votre premier projet depuis la page principale</p>
                            <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-success mt-3">
                                <i class="fas fa-plus me-2"></i>
                                Créer un projet
                            </a>
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
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Êtes-vous sûr de vouloir supprimer ce projet ?</p>
                <p class="text-danger mb-0"><strong>Cette action est irréversible !</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Annuler
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(projectId) {
    // Définir l'action du formulaire avec l'ID du projet
    const form = document.getElementById('deleteForm');
    form.action = `/evc/compte/design-graphique/projets/${projectId}`;
    
    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Afficher un message de succès après suppression
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <i class="fas fa-check-circle me-2"></i>
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alert);
        
        // Auto-fermer après 5 secondes
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
@endif

// Afficher un message d'erreur
@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.createElement('div');
        alert.className = 'alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
        alert.style.zIndex = '9999';
        alert.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>{{ session('error') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alert);
        
        // Auto-fermer après 5 secondes
        setTimeout(() => {
            alert.remove();
        }, 5000);
    });
@endif
</script>
@endpush
