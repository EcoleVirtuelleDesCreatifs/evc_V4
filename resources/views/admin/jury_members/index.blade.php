@extends('layouts.admin')

@section('title', 'Membres du jury')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Membres du jury</h1>
            <div class="text-muted small">Gestion des profils affichés sur la page publique du jury</div>
        </div>
        <div>
            <a href="{{ route('admin.jury-members.create') }}" class="btn btn-sm btn-primary">Ajouter</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">Erreurs</div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th>Ordre</th>
                            <th>Photo</th>
                            <th>Membre</th>
                            <th>Identifiant</th>
                            <th>Pays</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $member)
                            <tr>
                                <td>
                                    @if($member->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>{{ $member->sort_order }}</td>
                                <td>
                                    <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="rounded-circle" style="width: 52px; height: 52px; object-fit: cover; object-position: top;">
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $member->name }}</div>
                                    <div class="small text-muted">{{ $member->title ?: 'Fonction non renseignée' }}</div>
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $member->unique_identifier }}</span></td>
                                <td>
                                    <span>{{ $member->flag }}</span>
                                    <span>{{ $member->country ?: 'Non renseigné' }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.jury-members.evaluations.index', $member) }}" class="btn btn-sm btn-outline-primary">Évaluations</a>
                                    <a href="{{ route('admin.jury-members.edit', $member) }}" class="btn btn-sm btn-primary">Modifier</a>
                                    <form method="POST" action="{{ route('admin.jury-members.destroy', $member) }}" class="d-inline" onsubmit="return confirm('Supprimer ce membre du jury ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Aucun membre du jury enregistré</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
