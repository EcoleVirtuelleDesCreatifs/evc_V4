@extends('layouts.ki-admin')

@section('title', 'Projets Groupe - Design Graphique')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-users me-2" style="color: #003366;"></i>
                        Mes Projets Groupe
                    </h1>
                    <p class="text-muted mb-0">Liste complète de tous vos projets collaboratifs</p>
                </div>
                <div>
                    <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                    <a href="{{ route('design-graphique.projets.tous') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list"></i> Tous les projets
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
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
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Projets Validés
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($projects)->where('status', 'validated')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                En Cours
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($projects)->where('status', 'active')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Brouillons
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($projects)->where('status', 'draft')->count() }}
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

    <!-- Liste des projets -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>
                Liste des Projets Groupe ({{ count($projects) }})
            </h6>
        </div>
        <div class="card-body">
            @if(count($projects) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Projet</th>
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
                            <tr>
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
                                    <span class="badge bg-info text-white px-3 py-2" style="font-size: 0.9em;">{{ ucfirst($project['project_type']) }}</span>
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
                                        <span class="badge {{ $statusColors[$project['status']] ?? 'bg-secondary text-white' }} px-3 py-2" style="font-size: 0.9em;">{{ $statusLabels[$project['status']] ?? $project['status'] }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary text-white px-3 py-2" style="font-size: 0.9em;">
                                        <i class="fas fa-users me-1"></i>
                                        {{ $project['collaborators_count'] ?? 0 }} membre(s)
                                    </span>
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
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucun projet groupe trouvé</h5>
                    <p class="text-muted">Vous n'avez pas encore créé de projet collaboratif.</p>
                    <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Créer mon premier projet groupe
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
