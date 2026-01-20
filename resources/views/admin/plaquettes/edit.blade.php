@extends('layouts.admin')

@section('title', 'Modifier plaquette')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1 text-white">Modifier la plaquette</h2>
            <div class="text-muted">Mets à jour les informations ou remplace le PDF.</div>
        </div>
        <a href="{{ route('admin.plaquettes.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card border-0" style="background: rgba(255,255,255,0.04); backdrop-filter: blur(12px);">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.plaquettes.update', $plaquette) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label text-white">Titre</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title', $plaquette->title) }}" required>
                    </div>

                    <div class="col-12">
                        <label class="form-label text-white">Description</label>
                        <textarea name="description" class="form-control" rows="4" required>{{ old('description', $plaquette->description) }}</textarea>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-white">Formation</label>
                        <select name="formation_id" class="form-select" required>
                            <option value="">Choisir...</option>
                            @foreach($formations as $f)
                                <option value="{{ $f->id }}" @selected(old('formation_id', $plaquette->formation_id) == $f->id)>{{ $f->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label text-white">Date de début</label>
                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date', optional($plaquette->start_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label text-white">Date de fin</label>
                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date', optional($plaquette->end_date)->format('Y-m-d')) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-white">Format</label>
                        <select name="format" class="form-select" required>
                            <option value="online" @selected(old('format', $plaquette->format) === 'online')>En ligne</option>
                            <option value="offline" @selected(old('format', $plaquette->format) === 'offline')>Off line</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label text-white">Remplacer PDF (optionnel)</label>
                        <input type="file" name="file" class="form-control" accept="application/pdf">
                        <div class="form-text text-muted">PDF uniquement. Taille max 20 Mo.</div>
                    </div>

                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_published" value="1" id="isPublished" @checked(old('is_published', $plaquette->is_published))>
                            <label class="form-check-label text-white" for="isPublished">Publier</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                    <a href="{{ route('admin.plaquettes.download', $plaquette) }}" class="btn btn-outline-light">
                        <i class="fas fa-download me-2"></i>Télécharger PDF
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
