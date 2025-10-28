@extends('layouts.admin')

@section('title', 'Tous les Documents')

@push('styles')
<style>
    .table-dark a.text-white {
        color: #fff !important;
        text-decoration: none;
    }
    .table-dark a.text-white:hover {
        text-decoration: underline;
    }
    .preview-icon {
        font-size: 2rem;
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">

        <a href="{{ route('admin.bibliotheque.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter un Document</a>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Prévisualisation</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Utilisateur</th>
                            <th>Type</th>
                            <th>Date d'ajout</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($documents as $document)
                            <tr>
                                <td>
                                    @if(in_array(strtolower($document->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                        <img src="{{ asset('storage/' . $document->path) }}" alt="{{ $document->title }}" width="80" class="rounded shadow-sm">
                                    @else
                                        <div style="width: 80px; height: 60px;" class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-file-alt preview-icon"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ asset('storage/' . $document->path) }}" target="_blank" class="text-white">{{ $document->title }}</a>
                                    <small class="d-block text-muted">{{ $document->name }}</small>
                                </td>
                                <td>{{ $document->libraryCategory->name ?? 'N/A' }}</td>
                                <td>{{ $document->user->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ strtoupper($document->file_type) }}</span>
                                </td>
                                <td>{{ $document->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($document->status == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.bibliotheque.show', $document) }}" class="btn btn-sm btn-outline-info" title="Voir"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.bibliotheque.edit', $document) }}" class="btn btn-sm btn-outline-warning" title="Modifier"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.bibliotheque.destroy', $document) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Aucun document trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
