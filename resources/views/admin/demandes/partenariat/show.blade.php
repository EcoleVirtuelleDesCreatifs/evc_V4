@extends('layouts.admin')

@section('title', 'Demande Partenariat')

@push('styles')
<style>
    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Demande de partenariat #{{ $demande->id }}</h1>
        @php
            $backUrl = \Illuminate\Support\Facades\Route::has('admin.demandes.partenariat.index')
                ? route('admin.demandes.partenariat.index')
                : url('/evc/app/admin/demandes/partenariat');
        @endphp
        <a class="btn btn-outline-light" href="{{ $backUrl }}"><i class="fas fa-arrow-left me-2"></i>Retour</a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2"></i>Informations</h5>
                </div>
                <div class="card-body text-white">
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
                    <div class="border rounded p-3" style="border-color: #334155 !important; background-color: #0f172a;">{{ $demande->message }}</div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-cog me-2"></i>Traitement</h5>
                </div>
                <div class="card-body text-white">
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
                        <button class="btn btn-primary w-100" type="submit"><i class="fas fa-save me-2"></i>Mettre à jour</button>
                    </form>

                    <div class="d-grid gap-2 mt-3">
                        <form method="POST" action="{{ route('admin.demandes.partenariat.destroy', $demande->id) }}" onsubmit="return confirm('Supprimer cette demande ?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger" type="submit"><i class="fas fa-trash me-2"></i>Supprimer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
