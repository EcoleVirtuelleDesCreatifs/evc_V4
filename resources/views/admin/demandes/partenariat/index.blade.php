@extends('layouts.admin')

@section('title', 'Demandes - Partenaires')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Demandes de partenariat</h2>
        <div class="text-muted">Total : {{ $stats['total'] ?? 0 }}</div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-2">
            <div class="card"><div class="card-body">Nouveau : {{ $stats['nouveau'] ?? 0 }}</div></div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card"><div class="card-body">En cours : {{ $stats['en_cours'] ?? 0 }}</div></div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card"><div class="card-body">Accepté : {{ $stats['accepte'] ?? 0 }}</div></div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card"><div class="card-body">Refusé : {{ $stats['refuse'] ?? 0 }}</div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Organisation</th>
                            <th>Contact</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($demandes as $d)
                            <tr>
                                <td>#{{ $d->id }}</td>
                                <td>{{ $d->organisation }}</td>
                                <td>{{ $d->nom_contact }}</td>
                                <td>{{ $d->email }}</td>
                                <td>{{ $d->telephone }}</td>
                                <td>{{ $d->type_partenariat }}</td>
                                <td>{{ $d->statut ?? 'nouveau' }}</td>
                                <td>{{ optional($d->created_at)->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-primary" href="{{ route('admin.demandes.partenariat.show', $d->id) }}">Voir</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Aucune demande</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
