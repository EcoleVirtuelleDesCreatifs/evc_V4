@extends('layouts.admin')

@section('title', 'Modifier partenariat')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Modifier partenariat</h1>
            <div class="text-muted small">{{ $partnership->slug }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.partnerships.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
            <a href="{{ route('partnerships.show', $partnership->slug) }}" target="_blank" class="btn btn-outline-primary btn-sm">Voir page publique</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.partnerships.update', $partnership) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Texte (préfixe)</label>
                            <input type="text" name="prefix" class="form-control" value="{{ old('prefix', $partnership->prefix) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $partnership->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sous-titre</label>
                            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $partnership->subtitle) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">PDF (courrier)</label>
                            <input type="file" name="document" class="form-control" accept="application/pdf">
                            @if(!empty($partnership->document_path))
                                <div class="mt-2 d-flex align-items-center gap-3">
                                    <a target="_blank" href="{{ url('/storage/app/public/' . ltrim($partnership->document_path, '/')) }}">Voir PDF actuel</a>
                                    <button class="btn btn-sm btn-outline-danger" type="button" onclick="document.getElementById('deletePartnershipDocumentForm').submit()">Supprimer le PDF</button>
                                </div>
                            @endif
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $partnership->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Activer ce partenariat dans la top bar</label>
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>

                    @if(!empty($partnership->document_path))
                        <form id="deletePartnershipDocumentForm" method="POST" action="{{ route('admin.partnerships.document.delete', $partnership) }}" class="d-none">
                            @csrf
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="fw-bold mb-2">Aperçu top bar</div>
                    <div class="rounded-3 p-3" style="background: linear-gradient(to right, #ff6b00, #ff9800, #ff6b00); color: #fff;">
                        <div class="d-flex align-items-center justify-content-between gap-3">
                            <div class="text-truncate">
                                {{ $partnership->prefix }} {{ $partnership->name }} {{ $partnership->subtitle }}
                            </div>
                            <div class="px-3 py-1 rounded-pill" style="background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.2); font-weight: 800; font-size: 12px;">
                                En savoir plus
                            </div>
                        </div>
                    </div>
                    <div class="text-muted small mt-3">
                        Le bouton redirige vers /partenariat/{{ $partnership->slug }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
