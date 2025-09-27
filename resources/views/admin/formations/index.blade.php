@extends('layouts.admin')

@section('title', 'Liste des Formations')

@push('styles')
<style>
    .status-badge {
        display: inline-block;
        padding: .35em .65em;
        font-size: .75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
    }
    .status-badge.active { background-color: #198754; }
    .status-badge.draft { background-color: #6c757d; }
    .status-badge.inactive { background-color: #ffc107; color: #000; }
    .status-badge.archived { background-color: #dc3545; }
    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">

        <a href="{{ route('admin.formations.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Créer une formation</a>
    </div>



    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th style="min-width: 200px;">Nom de la Formation</th>
                            <th>Catégorie</th>
                            <th>Module Principal</th>
                            <th>Statut</th>
                            <th>Étudiants</th>
                            <th>Date de Création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($formations as $formation)
                            <tr>
                                <td>
                                    @if($formation->image_url)
                                        <img src="{{ asset('storage/' . $formation->image_url) }}" alt="{{ $formation->name }}" width="60" class="rounded shadow-sm">
                                    @else
                                        <div style="width: 60px; height: 40px; background-color: #334155;" class="rounded shadow-sm d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $formation->name }}</td>
                                <td>{{ $formation->category->name ?? 'N/A' }}</td>
                                <td>{{ $formation->modules[0] ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge {{ $formation->status }}">{{ $formation->status_label }}</span>
                                </td>
                                <td>{{ $formation->students_count }}</td>
                                <td>{{ $formation->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.formations.show', $formation) }}" class="btn btn-sm btn-info">Voir</a>
                                    <a href="{{ route('admin.formations.edit', $formation) }}" class="btn btn-sm btn-warning">Modifier</a>
                                    <form action="{{ route('admin.formations.toggleStatus', $formation) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($formation->status === 'active')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Désactiver">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Activer">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        @endif
                                    </form>
                                    <form action="{{ route('admin.formations.destroy', $formation) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Aucune formation trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
