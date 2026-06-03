@extends('layouts.admin')

@section('title', 'Modifier membre du jury')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Modifier un membre du jury</h1>
            <div class="text-muted small">Mise à jour du profil public de {{ $juryMember->name }}</div>
        </div>
        <div>
            <a href="{{ route('admin.jury-members.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
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
            <form method="POST" action="{{ route('admin.jury-members.update', $juryMember) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <img src="{{ $juryMember->photo_url }}" alt="{{ $juryMember->name }}" class="rounded-circle border" style="width: 96px; height: 96px; object-fit: cover; object-position: top;">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Nom et prénom</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $juryMember->name) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Identifiant unique</label>
                        <input type="text" name="unique_identifier" class="form-control" value="{{ old('unique_identifier', $juryMember->unique_identifier) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Fonction</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $juryMember->title) }}">
                    </div>

                    <div class="col-12 col-md-5">
                        <label class="form-label">Pays</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $juryMember->country) }}">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label">Drapeau</label>
                        <input type="text" name="flag" class="form-control" value="{{ old('flag', $juryMember->flag) }}" placeholder="🇨🇮">
                    </div>

                    <div class="col-12 col-md-4">
                        <label class="form-label">Ordre d’affichage</label>
                        <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $juryMember->sort_order) }}" min="0">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Nouvelle photo</label>
                        <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">URL image externe</label>
                        <input type="url" name="image_url" class="form-control" value="{{ old('image_url', $juryMember->image_url) }}" placeholder="https://...">
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $juryMember->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Afficher ce membre sur la page publique</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
