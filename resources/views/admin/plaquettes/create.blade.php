@extends('layouts.admin')

@section('title', 'Nouvelle plaquette')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .form-footer .btn-secondary {
        background-color: #4A5568 !important;
        border-color: #4A5568 !important;
        color: white !important;
    }
    .form-footer .btn-warning {
        background-color: #FBBF24 !important;
        border-color: #FBBF24 !important;
        color: #1F2937 !important;
    }
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('admin.plaquettes.store') }}" enctype="multipart/form-data" class="interactive-dashboard-form">
    @csrf

    <div class="row g-4">
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Informations Principales</h3>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6 form-group">
                            <label for="title">Titre</label>
                            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                        </div>

                        <div class="col-12 col-lg-6 form-group">
                            <label for="formation_id">Formation</label>
                            <select name="formation_id" id="formation_id" class="form-select" required>
                                <option value="" disabled {{ old('formation_id') ? '' : 'selected' }}>Choisir une formation...</option>
                                @foreach($formations as $f)
                                    <option value="{{ $f->id }}" @selected(old('formation_id') == $f->id)>{{ $f->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-calendar-alt"></i>
                    <h3>Période</h3>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-md-4 form-group">
                            <label for="start_date">Date de début</label>
                            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date') }}" required>
                        </div>
                        <div class="col-12 col-md-4 form-group">
                            <label for="end_date">Date de fin</label>
                            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date') }}" required>
                        </div>
                        <div class="col-12 col-md-4 form-group">
                            <label for="format">Format</label>
                            <select name="format" id="format" class="form-select" required>
                                <option value="online" @selected(old('format', 'online') === 'online')>En ligne</option>
                                <option value="offline" @selected(old('format') === 'offline')>Off line</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-file-pdf"></i>
                    <h3>Document</h3>
                </div>
                <div class="form-card-body">
                    <div class="row g-3">
                        <div class="col-12 col-lg-6 form-group">
                            <label for="file">Joindre PDF</label>
                            <input type="file" name="file" id="file" class="form-control" accept="application/pdf" required>
                            <small class="form-text text-muted">PDF uniquement. Taille max 20 Mo.</small>
                        </div>
                        <div class="col-12 col-lg-6 form-group d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="isPublished" @checked(old('is_published'))>
                                <label class="form-check-label" for="isPublished">Publier</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-footer mt-4 d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.plaquettes.index') }}" class="btn btn-light"><i class="fas fa-arrow-left me-2"></i>Retour à la liste</a>
        <div>
            <button type="submit" class="btn btn-warning"><i class="fas fa-save me-2"></i>Enregistrer</button>
        </div>
    </div>
</form>
@endsection
