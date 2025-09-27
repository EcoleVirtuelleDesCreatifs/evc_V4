@extends('layouts.admin')

@section('title', 'Bibliothèque de Médias')

@push('styles')
<style>
    .table-dark a.text-white {
        color: #fff !important;
        text-decoration: none;
    }
    .table-dark a.text-white:hover {
        text-decoration: underline;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">

        <a href="{{ route('admin.bibliotheque.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter un Média</a>
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
                            <th>Type</th>
                            <th>Destinataires</th>
                            <th>Date d'ajout</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>
                                    @if(in_array(strtolower($item->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                        <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->title }}" width="80" class="rounded shadow-sm">
                                    @else
                                        <div style="width: 80px; height: 60px; background-color: #334155;" class="rounded shadow-sm d-flex align-items-center justify-content-center">
                                            <i class="fas fa-file-alt text-white fa-2x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ asset('storage/' . $item->path) }}" target="_blank" class="text-white">{{ $item->title }}</a>
                                    <small class="d-block text-muted">{{ $item->name }}</small>
                                </td>
                                <td>{{ $item->libraryCategory->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ strtoupper($item->file_type) }}</span>
                                </td>
                                <td>
                                    @if(!empty($item->recipients))
                                        @foreach($item->recipients as $recipient)
                                            <span class="badge bg-info me-1">{{ ucfirst(str_replace(['-', '_'], ' ', $recipient)) }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-light text-dark">Tous</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if($item->status == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.bibliotheque.show', $item) }}" class="btn btn-sm btn-outline-info" title="Voir"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.bibliotheque.edit', $item) }}" class="btn btn-sm btn-outline-warning" title="Modifier"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('admin.bibliotheque.toggleStatus', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $item->status == 'active' ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $item->status == 'active' ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-toggle-{{ $item->status == 'active' ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.bibliotheque.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
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
                                <td colspan="8" class="text-center py-4">Aucun média trouvé dans la bibliothèque.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
