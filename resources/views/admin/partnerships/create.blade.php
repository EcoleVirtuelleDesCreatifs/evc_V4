@extends('layouts.admin')

@section('title', 'Ajouter partenariat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Ajouter un partenariat</h1>
            <div class="text-muted small">Création d’un nouveau partenaire (top bar + page courrier)</div>
        </div>
        <div>
            <a href="{{ route('admin.partnerships.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
        </div>
    </div>

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
            <form method="POST" action="{{ route('admin.partnerships.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-4">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="ex: onp" required>
                        <div class="form-text">Lettres/chiffres/tirets uniquement (utilisé dans l’URL).</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Préfixe</label>
                        <input type="text" name="prefix" class="form-control" value="{{ old('prefix', 'Partenaire à') }}" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Nom</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Sous-titre</label>
                        <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label">PDF (courrier)</label>
                        <input type="file" name="document" class="form-control" accept="application/pdf">
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Activer ce partenariat dans la top bar</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Créer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
