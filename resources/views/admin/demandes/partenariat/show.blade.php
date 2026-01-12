@extends('layouts.admin')

@section('title', 'Demande Partenariat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Demande de partenariat #{{ $demande->id }}</h2>
        <a class="btn btn-secondary" href="{{ route('admin.demandes.partenariat.index') }}">Retour</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Informations</h5>
                    <div><strong>Organisation :</strong> {{ $demande->organisation }}</div>
                    <div><strong>Contact :</strong> {{ $demande->nom_contact }}</div>
                    <div><strong>Email :</strong> {{ $demande->email }}</div>
                    <div><strong>Téléphone :</strong> {{ $demande->telephone }}</div>
                    @if(!empty($demande->site_web))
                        <div><strong>Site :</strong> <a href="{{ $demande->site_web }}" target="_blank">{{ $demande->site_web }}</a></div>
                    @endif
                    <div><strong>Type :</strong> {{ $demande->type_partenariat }}</div>
                    <div><strong>Secteur :</strong> {{ $demande->secteur }}</div>

                    <div class="mt-3"><strong>Message :</strong></div>
                    <div class="border rounded p-3">{{ $demande->message }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Traitement</h5>
                    <form method="POST" action="{{ route('admin.demandes.partenariat.update-statut', $demande->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <select class="form-select" name="statut" required>
                                @foreach(['nouveau' => 'Nouveau', 'en_cours' => 'En cours', 'accepte' => 'Accepté', 'refuse' => 'Refusé'] as $value => $label)
                                    <option value="{{ $value }}" {{ ($demande->statut ?? 'nouveau') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes admin</label>
                            <textarea class="form-control" name="notes_admin" rows="5">{{ old('notes_admin', $demande->notes_admin) }}</textarea>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Mettre à jour</button>
                    </form>

                    <div class="d-grid gap-2 mt-3">
                        <form method="POST" action="{{ route('admin.demandes.partenariat.destroy', $demande->id) }}" onsubmit="return confirm('Supprimer cette demande ?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger" type="submit">Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
