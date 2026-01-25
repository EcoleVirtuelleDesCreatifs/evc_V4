@extends('layouts.admin')

@section('title', 'Demande de plaquette')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">Demande de plaquette #{{ $request->id }}</h1>
            <div class="text-muted">Détails complets de la demande</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.plaquettes.requests.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            @if($request->status === 'pending')
                <form method="POST" action="{{ route('admin.plaquettes.requests.approve', $request) }}">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Valider et envoyer la plaquette par email ?')">
                        <i class="fas fa-check me-1"></i>Valider
                    </button>
                </form>
            @endif
        </div>
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

    @php
        $badge = 'secondary';
        $label = 'En attente';
        if ($request->status === 'approved') { $badge = 'success'; $label = 'Validée'; }
        if ($request->status === 'rejected') { $badge = 'danger'; $label = 'Rejetée'; }
    @endphp

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-id-card me-2"></i>Informations demandeur</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="small text-muted">Nom complet</div>
                            <div class="fw-semibold text-white">{{ $request->prenoms }} {{ $request->nom }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Type / Niveau</div>
                            <div class="fw-semibold text-warning">{{ $request->type_formation }} • {{ $request->niveau_etude }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Pays</div>
                            <div class="fw-semibold text-white">{{ $request->pays }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Ville</div>
                            <div class="fw-semibold text-white">{{ $request->ville }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Email</div>
                            <div class="fw-semibold text-white">{{ $request->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">WhatsApp</div>
                            <div class="fw-semibold text-white">{{ $request->whatsapp }}</div>
                        </div>
                        <div class="col-12">
                            <div class="small text-muted">Motivation</div>
                            <div class="mt-1 text-white" style="white-space: pre-wrap;">{{ $request->motivation }}</div>
                        </div>
                        @if(!empty($request->admin_comment))
                            <div class="col-12">
                                <div class="small text-muted">Commentaire admin</div>
                                <div class="mt-1 text-white" style="white-space: pre-wrap;">{{ $request->admin_comment }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-file-pdf me-2"></i>Plaquette</h5>
                </div>
                <div class="card-body">
                    <div class="small text-muted">Titre</div>
                    <div class="fw-semibold text-white">{{ $request->plaquette?->title ?? '—' }}</div>
                    <div class="small text-muted mt-3">Fichier</div>
                    <div class="fw-semibold text-warning">{{ $request->plaquette?->original_filename ?? '' }}</div>
                </div>
            </div>

            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Statut</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-semibold text-white">État</div>
                        <span class="badge bg-{{ $badge }}">{{ $label }}</span>
                    </div>
                    <div class="mt-3 small text-muted">Créée le</div>
                    <div class="text-white">{{ $request->created_at ? $request->created_at->format('d/m/Y H:i') : '—' }}</div>
                    @if($request->approved_at)
                        <div class="mt-3 small text-muted">Validée le</div>
                        <div class="text-white">{{ $request->approved_at->format('d/m/Y H:i') }}</div>
                    @endif
                    @if($request->rejected_at)
                        <div class="mt-3 small text-muted">Rejetée le</div>
                        <div class="text-white">{{ $request->rejected_at->format('d/m/Y H:i') }}</div>
                    @endif
                </div>
            </div>

            @if($request->status === 'pending')
                <div class="mt-4">
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        <i class="fas fa-times me-1"></i>Rejeter
                    </button>
                </div>

                <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content bg-dark text-white">
                            <div class="modal-header">
                                <h5 class="modal-title">Rejeter la demande #{{ $request->id }}</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form method="POST" action="{{ route('admin.plaquettes.requests.reject', $request) }}">
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
            @endif
        </div>
    </div>
</div>
@endsection
