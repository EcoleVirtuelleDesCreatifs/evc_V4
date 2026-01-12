@extends('layouts.admin')

@section('title', 'Candidature Formateur')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Candidature Formateur #{{ $candidature->id }}</h2>
        <a class="btn btn-secondary" href="{{ route('admin.candidatures.formateurs.index') }}">Retour</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Informations</h5>
                    <div><strong>Nom :</strong> {{ $candidature->prenom }} {{ $candidature->nom }}</div>
                    <div><strong>Email :</strong> {{ $candidature->email }}</div>
                    <div><strong>Téléphone :</strong> {{ $candidature->telephone }}</div>
                    <div><strong>Domaine :</strong> {{ $candidature->domaine }}</div>
                    <div><strong>Expérience :</strong> {{ $candidature->experience }}</div>

                    <div class="mt-3"><strong>Diplômes :</strong></div>
                    <div class="border rounded p-3">{{ $candidature->diplomes }}</div>

                    <div class="mt-3"><strong>Motivation :</strong></div>
                    <div class="border rounded p-3">{{ $candidature->motivation }}</div>

                    @if(!empty($candidature->portfolio))
                        <div class="mt-3"><strong>Portfolio :</strong> <a href="{{ $candidature->portfolio }}" target="_blank">{{ $candidature->portfolio }}</a></div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-3">Traitement</h5>
                    <form method="POST" action="{{ route('admin.candidatures.formateurs.update-statut', $candidature->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <select class="form-select" name="statut" required>
                                @foreach(['nouveau' => 'Nouveau', 'en_cours' => 'En cours', 'accepte' => 'Accepté', 'refuse' => 'Refusé'] as $value => $label)
                                    <option value="{{ $value }}" {{ ($candidature->statut ?? 'nouveau') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes admin</label>
                            <textarea class="form-control" name="notes_admin" rows="5">{{ old('notes_admin', $candidature->notes_admin) }}</textarea>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Mettre à jour</button>
                    </form>

                    <div class="d-grid gap-2 mt-3">
                        <a class="btn btn-outline-primary" href="{{ route('admin.candidatures.formateurs.download-cv', $candidature->id) }}">Télécharger CV</a>
                        <form method="POST" action="{{ route('admin.candidatures.formateurs.destroy', $candidature->id) }}" onsubmit="return confirm('Supprimer cette candidature ?');">
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
