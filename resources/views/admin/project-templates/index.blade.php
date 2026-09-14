@extends('layouts.admin')

@section('title', 'Modèles de Projets')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4 mb-0">Modèles de Projets</h2>
        <a href="{{ route('admin.project-templates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Nouveau Modèle
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @if($templates->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Catégorie</th>
                                <th>Délai par défaut</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($templates as $template)
                            <tr>
                                <td>{{ $template->title }}</td>
                                <td>{{ $template->category }}</td>
                                <td>{{ $template->default_deadline_days }} jours</td>
                                <td>
                                    @if($template->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.project-templates.edit', $template) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.project-templates.destroy', $template) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun modèle de projet créé.</p>
                    <a href="{{ route('admin.project-templates.create') }}" class="btn btn-primary">
                        Créer le premier modèle
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection