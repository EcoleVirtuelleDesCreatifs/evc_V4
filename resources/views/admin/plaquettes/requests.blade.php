@extends('layouts.admin')

@section('title', 'Demandes de plaquettes')

@push('styles')
<style>
    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Demandes de Plaquettes</h1>
        <a href="{{ route('admin.plaquettes.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour aux plaquettes
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-bg-dark">
                <div class="card-body">
                    <div class="fw-semibold">Total</div>
                    <div class="display-6">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <div class="fw-semibold">En attente</div>
                    <div class="display-6">{{ $stats['pending'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    <div class="fw-semibold">Validées</div>
                    <div class="display-6">{{ $stats['approved'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-bg-secondary">
                <div class="card-body">
                    <div class="fw-semibold">Rejetées</div>
                    <div class="display-6">{{ $stats['rejected'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-inbox me-2"></i>Liste des demandes</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Plaquette</th>
                            <th>Demandeur</th>
                            <th>Pays</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $r)
                            @php
                                $badge = 'secondary';
                                $label = 'En attente';
                                if ($r->status === 'approved') { $badge = 'success'; $label = 'Validée'; }
                                if ($r->status === 'rejected') { $badge = 'danger'; $label = 'Rejetée'; }
                            @endphp
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $r->plaquette?->title ?? '—' }}</div>
                                    <div class="small text-warning">{{ $r->plaquette?->original_filename ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $r->prenoms }} {{ $r->nom }}</div>
                                    <div class="small text-warning">{{ $r->type_formation }} • {{ $r->niveau_etude }}</div>
                                </td>
                                <td>
                                    <div>{{ $r->pays }}</div>
                                    <div class="small text-muted">{{ $r->ville }}</div>
                                </td>
                                <td><span class="badge bg-{{ $badge }}">{{ $label }}</span></td>
                                <td class="text-nowrap">{{ $r->created_at ? $r->created_at->format('d/m/Y H:i') : '—' }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#readModal{{ $r->id }}">
                                        <i class="fas fa-eye"></i> Lire complet
                                    </button>

                                    <div class="modal fade" id="readModal{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content bg-dark text-white">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Demande de plaquette #{{ $r->id }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <div class="small text-muted">Plaquette</div>
                                                            <div class="fw-semibold">{{ $r->plaquette?->title ?? '—' }}</div>
                                                            <div class="text-warning">{{ $r->plaquette?->original_filename ?? '' }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted">Statut</div>
                                                            <div><span class="badge bg-{{ $badge }}">{{ $label }}</span></div>
                                                            <div class="small text-muted mt-2">Date</div>
                                                            <div>{{ $r->created_at ? $r->created_at->format('d/m/Y H:i') : '—' }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="small text-muted">Demandeur</div>
                                                            <div class="fw-semibold">{{ $r->prenoms }} {{ $r->nom }}</div>
                                                            <div class="text-warning">{{ $r->type_formation }} • {{ $r->niveau_etude }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted">Pays / Ville</div>
                                                            <div class="fw-semibold">{{ $r->pays }}</div>
                                                            <div class="text-muted">{{ $r->ville }}</div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="small text-muted">Email</div>
                                                            <div class="fw-semibold">{{ $r->email }}</div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="small text-muted">WhatsApp</div>
                                                            <div class="fw-semibold">{{ $r->whatsapp }}</div>
                                                        </div>

                                                        <div class="col-12">
                                                            <div class="small text-muted">Motivation</div>
                                                            <div class="mt-1" style="white-space: pre-wrap;">{{ $r->motivation }}</div>
                                                        </div>

                                                        @if(!empty($r->admin_comment))
                                                            <div class="col-12">
                                                                <div class="small text-muted">Commentaire admin</div>
                                                                <div class="mt-1" style="white-space: pre-wrap;">{{ $r->admin_comment }}</div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($r->status === 'pending')
                                        <form method="POST" action="{{ route('admin.plaquettes.requests.approve', $r) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Valider et envoyer la plaquette par email ?')">
                                                <i class="fas fa-check"></i> Valider
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $r->id }}">
                                            <i class="fas fa-times"></i> Rejeter
                                        </button>

                                        <div class="modal fade" id="rejectModal{{ $r->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content bg-dark text-white">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Rejeter la demande #{{ $r->id }}</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form method="POST" action="{{ route('admin.plaquettes.requests.reject', $r) }}">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <label class="form-label">Commentaire (optionnel)</label>
                                                            <textarea name="admin_comment" class="form-control" rows="4" placeholder="Raison du rejet..."></textarea>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            <button type="submit" class="btn btn-danger">Confirmer le rejet</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Aucune demande.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
