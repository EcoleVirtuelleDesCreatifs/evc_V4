@extends('layouts.ki-admin')

@section('title', 'Tous les Projets - Design Graphique')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-list me-2" style="color: #003366;"></i>
                        Tous Mes Projets
                    </h1>
                    <p class="text-muted mb-0">Vue d'ensemble complète de tous vos projets (solo et groupe)</p>
                </div>
                <div>
                    <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <div class="btn-group" role="group">
                        <a href="{{ route('design-graphique.projets.solo') }}" class="btn btn-outline-info">
                            <i class="fas fa-user"></i> Solo
                        </a>
                        <a href="{{ route('design-graphique.projets.groupe') }}" class="btn btn-outline-warning">
                            <i class="fas fa-users"></i> Groupe
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques complètes -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Projets
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_projects'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Projets Solo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['solo_projects'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Projets Groupe
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['group_projects'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Validés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['validated_projects'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                En Cours
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['active_projects'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Brouillons
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['draft_projects'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-edit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="filterMode" class="form-label">Mode de projet :</label>
                    <select id="filterMode" class="form-select">
                        <option value="">Tous les modes</option>
                        <option value="solo">Solo uniquement</option>
                        <option value="groupe">Groupe uniquement</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterStatus" class="form-label">Statut :</label>
                    <select id="filterStatus" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="draft">Brouillon</option>
                        <option value="active">En cours</option>
                        <option value="completed">Terminé</option>
                        <option value="validated">Validé</option>
                        <option value="cancelled">Annulé</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="filterType" class="form-label">Type de projet :</label>
                    <select id="filterType" class="form-select">
                        <option value="">Tous les types</option>
                        <option value="logo">Logo</option>
                        <option value="affiche">Affiche</option>
                        <option value="brochure">Brochure</option>
                        <option value="site_web">Site Web</option>
                        <option value="packaging">Packaging</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>
                Liste Complète des Projets (<span id="projectCount">{{ count($projects) }}</span>)
            </h6>
        </div>
        <div class="card-body">
            @if(count($projects) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0" id="projectsTable">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Mode</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Collaborateurs</th>
                                <th>Fichiers</th>
                                <th>Créé le</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr data-mode="{{ $project['project_mode'] }}" 
                                data-status="{{ $project['status'] }}" 
                                data-type="{{ $project['project_type'] }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            @if(!empty($project['files']) && count($project['files']) > 0)
                                                @php
                                                    $firstImage = collect($project['files'])->first(function($file) {
                                                        return isset($file['name']) && in_array(strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                    });
                                                @endphp
                                                @if($firstImage && isset($firstImage['name']))
                                                    <img src="{{ asset('uploads/design_projects/' . basename($firstImage['path'] ?? $firstImage['name'])) }}" 
                                                         alt="Aperçu" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px;">
                                                        <i class="fas fa-file text-muted"></i>
                                                    </div>
                                                @endif
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-weight-bold">{{ $project['title'] }}</div>
                                            <div class="text-muted small">{{ Str::limit($project['description'], 60) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($project['project_mode'] === 'solo')
                                        <span class="badge bg-info text-white px-3 py-2" style="font-size: 0.9em;">
                                            <i class="fas fa-user me-1"></i>Solo
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 0.9em;">
                                            <i class="fas fa-users me-1"></i>Groupe
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary text-white px-3 py-2" style="font-size: 0.9em;">{{ ucfirst($project['project_type']) }}</span>
                                </td>
                                <td>
                                    @if($project['status'] === 'validated')
                                        <span style="display: inline-block; padding: 8px 16px; background-color: #198754; color: white; border-radius: 6px; font-size: 0.85em; font-weight: bold; text-align: center; white-space: nowrap;">
                                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Projet validé avec succès
                                        </span>
                                    @else
                                        @php
                                            $statusLabels = [
                                                'draft' => 'Brouillon',
                                                'active' => 'En cours',
                                                'completed' => 'Terminé',
                                                'cancelled' => 'Annulé'
                                            ];
                                            $statusColors = [
                                                'draft' => 'bg-secondary text-white',
                                                'active' => 'bg-primary text-white',
                                                'completed' => 'bg-success text-white',
                                                'cancelled' => 'bg-danger text-white'
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusColors[$project['status']] ?? 'bg-secondary text-white' }} px-3 py-2" style="font-size: 0.9em;">
                                            {{ $statusLabels[$project['status']] ?? $project['status'] }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($project['project_mode'] === 'groupe')
                                        <span class="badge bg-primary text-white px-3 py-2" style="font-size: 0.9em;">
                                            <i class="fas fa-users me-1"></i>
                                            {{ $project['collaborators_count'] ?? 0 }} membre(s)
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-dark text-white px-3 py-2" style="font-size: 0.9em;">
                                        <i class="fas fa-paperclip me-1"></i>
                                        {{ $project['files_count'] ?? 0 }} fichier(s)
                                    </span>
                                </td>
                                <td>{{ date('d/m/Y', strtotime($project['created_at'])) }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('design-graphique.projets.show', $project['id']) }}" 
                                           class="btn btn-sm btn-outline-primary" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($project['status'] !== 'validated')
                                            <a href="{{ route('design-graphique.projets.edit', $project['id']) }}" 
                                               class="btn btn-sm btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('design-graphique.projets.destroy', $project['id']) }}" 
                                                  style="display: inline;" 
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
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
                    <h5 class="text-muted">Aucun projet trouvé</h5>
                    <p class="text-muted">Vous n'avez pas encore créé de projet.</p>
                    <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer mon premier projet
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterMode = document.getElementById('filterMode');
    const filterStatus = document.getElementById('filterStatus');
    const filterType = document.getElementById('filterType');
    const projectsTable = document.getElementById('projectsTable');
    const projectCount = document.getElementById('projectCount');

    function filterProjects() {
        const modeValue = filterMode.value;
        const statusValue = filterStatus.value;
        const typeValue = filterType.value;
        
        const rows = projectsTable.querySelectorAll('tbody tr');
        let visibleCount = 0;

        rows.forEach(row => {
            const mode = row.getAttribute('data-mode');
            const status = row.getAttribute('data-status');
            const type = row.getAttribute('data-type');

            const modeMatch = !modeValue || mode === modeValue;
            const statusMatch = !statusValue || status === statusValue;
            const typeMatch = !typeValue || type === typeValue;

            if (modeMatch && statusMatch && typeMatch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        projectCount.textContent = visibleCount;
    }

    filterMode.addEventListener('change', filterProjects);
    filterStatus.addEventListener('change', filterProjects);
    filterType.addEventListener('change', filterProjects);
});
</script>
@endsection
