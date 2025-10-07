@extends('layouts.ki-admin')

@section('title', 'Projets Groupe - Design Graphique')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold">
                        <i class="fas fa-users me-2 text-warning"></i>
                        Projets Groupe
                    </h2>
                    <p class="text-muted mb-0">Vos projets collaboratifs de design graphique</p>
                </div>
                <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Liste des projets -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Liste des Projets Groupe
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
                                            <span class="badge bg-info">{{ $project['project_type'] ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if(($project['status'] ?? '') === 'validated')
                                                <span class="badge bg-success">Validé</span>
                                            @elseif(($project['status'] ?? '') === 'pending')
                                                <span class="badge bg-warning">En attente</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $project['status'] ?? '-' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>{{ isset($project['created_at']) ? date('d/m/Y', strtotime($project['created_at'])) : '-' }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('design-graphique.projets.show', $project['id']) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-eye"></i> Voir
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Aucun projet groupe pour le moment</h5>
                            <p class="text-muted">Créez votre premier projet collaboratif depuis la page principale</p>
                            <a href="{{ route('design-graphique.projets.index') }}" class="btn btn-warning mt-3">
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
@endsection
