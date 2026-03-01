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
                        <input type="text" id="partnership-slug" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="ex: onp">
                        <div class="form-text">Lettres/chiffres/tirets uniquement (utilisé dans l’URL).</div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Préfixe</label>
                        <input type="text" name="prefix" class="form-control" value="{{ old('prefix', 'Partenaire à') }}" required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Nom</label>
                        <input type="text" id="partnership-name" name="name" class="form-control" value="{{ old('name') }}" required>
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

@push('scripts')
<script>
    (function () {
        const nameInput = document.getElementById('partnership-name');
        const slugInput = document.getElementById('partnership-slug');
        if (!nameInput || !slugInput) return;

        const initialSlug = (slugInput.value || '').trim();
        let slugTouched = initialSlug.length > 0;

        const slugify = (value) => {
            return (value || '')
                .toString()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        };

        slugInput.addEventListener('input', () => {
            slugTouched = true;
        });

        const sync = () => {
            if (slugTouched) return;
            const v = slugify(nameInput.value);
            if (v) slugInput.value = v;
        };

        nameInput.addEventListener('input', sync);
        sync();
    })();
</script>
@endpush
