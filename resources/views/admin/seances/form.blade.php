@extends('layouts.admin')

@section('title', isset($seance) ? 'Modifier la séance' : 'Nouvelle séance')

@push('styles')
<style>
    .form-label { font-weight: 600; }
    .required:after { content: ' *'; color: #e74c3c; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-4">{{ isset($seance) ? 'Modifier la séance' : 'Nouvelle séance' }}</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ isset($seance) ? route('admin.seances.update', $seance) : route('admin.seances.store') }}">
                @csrf
                @if(isset($seance))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-8">
                        <label for="title" class="form-label required">Titre</label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $seance->title ?? '') }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4">
                        <label for="formation" class="form-label required">Formation</label>
                        <select id="formation" name="formation" class="form-select @error('formation') is-invalid @enderror" required>
                            <option value="">Choisir une formation</option>
                            @foreach($formations as $formation)
                                <option value="{{ $formation }}" {{ old('formation', $seance->formation ?? '') == $formation ? 'selected' : '' }}>{{ $formation }}</option>
                            @endforeach
                        </select>
                        @error('formation')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="type" class="form-label required">Type de séance</label>
                        <select id="type" name="type" class="form-select @error('type') is-invalid @enderror" required onchange="toggleFields()">
                            <option value="">Choisir...</option>
                            <option value="presentiel" {{ old('type', $seance->type ?? '') == 'presentiel' ? 'selected' : '' }}>Présentiel</option>
                            <option value="online" {{ old('type', $seance->type ?? '') == 'online' ? 'selected' : '' }}>En ligne (Google Meet)</option>
                        </select>
                        @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="scheduled_at" class="form-label required">Date et heure</label>
                        <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                               class="form-control @error('scheduled_at') is-invalid @enderror"
                               value="{{ old('scheduled_at', isset($seance) ? $seance->scheduled_at->format('Y-m-d\\TH:i') : '') }}" required>
                        @error('scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="duration_minutes" class="form-label required">Durée (minutes)</label>
                        <input type="number" id="duration_minutes" name="duration_minutes" min="1"
                               class="form-control @error('duration_minutes') is-invalid @enderror"
                               value="{{ old('duration_minutes', $seance->duration_minutes ?? 60) }}" required>
                        @error('duration_minutes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label for="status" class="form-label required">Statut</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="planned" {{ old('status', $seance->status ?? 'planned') == 'planned' ? 'selected' : '' }}>Planifiée</option>
                            <option value="completed" {{ old('status', $seance->status ?? '') == 'completed' ? 'selected' : '' }}>Terminée</option>
                            <option value="cancelled" {{ old('status', $seance->status ?? '') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-8" id="location-group">
                        <label for="location" class="form-label">Lieu (présentiel)</label>
                        <input type="text" id="location" name="location"
                               class="form-control @error('location') is-invalid @enderror"
                               value="{{ old('location', $seance->location ?? '') }}">
                        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12" id="meet-group">
                        <label for="meet_link" class="form-label">Lien Google Meet</label>
                        <input type="url" id="meet_link" name="meet_link"
                               class="form-control @error('meet_link') is-invalid @enderror"
                               value="{{ old('meet_link', $seance->meet_link ?? '') }}" placeholder="https://meet.google.com/...">
                        @error('meet_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $seance->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ isset($seance) ? 'Mettre à jour' : 'Créer la séance' }}
                    </button>
                    <a href="{{ route('admin.seances.index') }}" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleFields() {
        const type = document.getElementById('type').value;
        const locationGroup = document.getElementById('location-group');
        const meetGroup = document.getElementById('meet-group');
        const locationInput = document.getElementById('location');
        const meetInput = document.getElementById('meet_link');

        if (type === 'online') {
            meetGroup.style.display = 'block';
            locationGroup.style.display = 'none';
            locationInput.value = '';
        } else if (type === 'presentiel') {
            meetGroup.style.display = 'none';
            locationGroup.style.display = 'block';
            meetInput.value = '';
        } else {
            meetGroup.style.display = 'block';
            locationGroup.style.display = 'block';
        }
    }
    document.addEventListener('DOMContentLoaded', toggleFields);
</script>
@endpush
