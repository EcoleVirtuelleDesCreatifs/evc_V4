@extends('layouts.admin')

@section('title', 'Nouvelle plaquette')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1 text-white">Nouvelle plaquette</h2>
            <div class="text-muted">Crée une plaquette et publie-la si besoin.</div>
        </div>
        <a href="{{ route('admin.plaquettes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card border-0" style="background: rgba(255,255,255,0.04); backdrop-filter: blur(12px);">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.plaquettes.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-white">Titre</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-white">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-white">Formation</label>
                        <select name="formation_id" class="form-select" required>
                            <option value="">Choisir...</option>
                            @foreach($formations as $f)
                                <option value="{{ $f->id }}" @selected(old('formation_id') == $f->id)>{{ $f->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label text-white">Date de début</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label text-white">Date de fin</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-white">Format</label>
                        <select name="format" class="form-select" required>
                            <option value="online" @selected(old('format', 'online') === 'online')>En ligne</option>
                            <option value="offline" @selected(old('format') === 'offline')>Off line</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-white">Joindre PDF</label>
                        <input type="file" name="file" class="form-control" accept="application/pdf" required>
                        <div class="form-text text-muted">PDF uniquement. Taille max 20 Mo.</div>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="isPublished" @checked(old('is_published'))>
                            <label class="form-check-label text-white" for="isPublished">Publier</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
