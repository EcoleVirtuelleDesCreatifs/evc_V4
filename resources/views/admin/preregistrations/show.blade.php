@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 mb-0">Détails de la pré-inscription #{{ $pre->id }}</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.preinscriptions.index') }}" class="btn btn-outline-secondary">Retour</a>
            @if($pre->photo)
                <a href="{{ route('admin.preinscriptions.download-photo', $pre->id) }}" class="btn btn-primary"><i class="fas fa-download me-2"></i>Télécharger la photo</a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card mb-3">
                <div class="card-header"><strong>Informations personnelles</strong></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6"><small class="text-muted d-block">Nom</small>{{ $pre->nom }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Prénom</small>{{ $pre->prenom }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Âge</small>{{ $pre->age }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Email</small>{{ $pre->email }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">WhatsApp</small>{{ $pre->whatsapp }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Pays</small>{{ $pre->pays }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><strong>Parcours & Objectifs</strong></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6"><small class="text-muted d-block">Niveau d'étude</small>{{ $pre->niveau_etude }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Formation choisie</small>{{ $pre->choix_formation }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Niveau dans la formation</small>{{ $pre->niveau_dans_formation }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Statut actuel</small>{{ $pre->disponibilite }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Ordinateur</small>{{ $pre->has_computer ? 'Oui' : 'Non' }}</div>
                        <div class="col-sm-6"><small class="text-muted d-block">Smartphone</small>{{ $pre->has_smartphone ? 'Oui' : 'Non' }}</div>
                    </div>
                    <div class="mt-3">
                        <small class="text-muted d-block mb-1">Motivation</small>
                        <div class="white-space-pre-line">{{ $pre->motivation }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><strong>Statut</strong></div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.preinscriptions.bulk-status') }}" class="row g-2 align-items-center">
                        @csrf
                        <input type="hidden" name="ids[]" value="{{ $pre->id }}">
                        <div class="col-auto">
                            <select name="action" class="form-select">
                                <option value="accepted" @selected($pre->status==='accepted')>Accepté</option>
                                <option value="rejected" @selected($pre->status==='rejected')>Rejeté</option>
                                <option value="pending" @selected($pre->status==='pending')>En attente</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-header"><strong>Photo</strong></div>
                <div class="card-body">
                    @if($pre->photo)
                        <img src="{{ asset('storage/'.$pre->photo) }}" alt="Photo du candidat" class="img-fluid rounded">
                    @else
                        <div class="text-muted">Aucune photo fournie.</div>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-header"><strong>Méta</strong></div>
                <div class="card-body">
                    <div><small class="text-muted d-block">Créé le</small>{{ $pre->created_at->format('Y-m-d H:i') }}</div>
                    <div class="mt-2"><small class="text-muted d-block">Mis à jour le</small>{{ $pre->updated_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
