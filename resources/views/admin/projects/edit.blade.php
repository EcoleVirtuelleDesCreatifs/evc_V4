@extends('layouts.admin')

@section('title', 'Modifier le Projet - ' . $project->title)

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .pe-header {
        background: linear-gradient(135deg, rgba(139,92,246,0.15) 0%, rgba(59,130,246,0.10) 100%);
        border: 1px solid rgba(139,92,246,0.3);
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .pe-header-icon {
        width: 48px; height: 48px; border-radius: 12px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.2rem; flex-shrink: 0;
    }
    .pe-label {
        font-size: 0.72rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: 0.6px;
        color: #94a3b8; margin-bottom: 0.4rem; display: block;
    }
    .pe-hint { font-size: 0.78rem; color: #94a3b8; margin-top: 0.4rem; display: block; }
    .pe-hint i { color: #38bdf8; }

    /* Quill dark theme */
    .ql-toolbar.ql-snow {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(51,65,85,0.7) !important;
        border-radius: 10px 10px 0 0;
    }
    .ql-container.ql-snow {
        border: 1px solid rgba(51,65,85,0.7) !important;
        border-top: none;
        border-radius: 0 0 10px 10px;
        background: rgba(15,23,42,0.5);
        min-height: 260px;
        font-size: 0.95rem;
    }
    .ql-editor { color: #e2e8f0; }
    .ql-editor.ql-blank::before { color: #64748b; }
    .ql-snow .ql-stroke { stroke: #94a3b8; }
    .ql-snow .ql-fill { fill: #94a3b8; }
    .ql-snow .ql-picker { color: #94a3b8; }
    .ql-toolbar.ql-snow .ql-picker-label:hover,
    .ql-toolbar.ql-snow button:hover .ql-stroke { stroke: #38bdf8; }
    .ql-snow.ql-toolbar button:hover, .ql-snow .ql-toolbar button:hover,
    .ql-snow.ql-toolbar .ql-picker-label:hover { color: #38bdf8; }
</style>
@endpush

@section('content')
@php
    $statusMeta = [
        'en_cours' => ['label' => 'Pas encore fait', 'class' => 'bg-warning text-dark', 'icon' => 'hourglass-half'],
        'termine'  => ['label' => 'Terminé',          'class' => 'bg-info text-dark',    'icon' => 'check'],
        'valide'   => ['label' => 'Validé',           'class' => 'bg-success',           'icon' => 'check-circle'],
        'rejete'   => ['label' => 'Rejeté',           'class' => 'bg-danger',            'icon' => 'times-circle'],
    ];
    $sm = $statusMeta[$project->status] ?? ['label' => $project->status ?? '—', 'class' => 'bg-secondary', 'icon' => 'question'];
    $swArr = is_array($project->software_used)
        ? $project->software_used
        : (is_string($project->software_used) ? (json_decode($project->software_used, true) ?? $project->software_used) : []);
    $swStr = is_array($swArr) ? implode(', ', $swArr) : (string) $swArr;
@endphp

<div class="container-fluid py-4" style="max-width: 900px;">

    {{-- Header --}}
    <div class="pe-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="pe-header-icon"><i class="fas fa-edit"></i></div>
                <div>
                    <h2 class="text-white mb-1" style="font-size:1.3rem; font-weight:700;">Modifier le projet</h2>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="text-white-50" style="font-size:0.9rem;">{{ $project->title }}</span>
                        <span class="badge {{ $sm['class'] }}"><i class="fas fa-{{ $sm['icon'] }} me-1"></i>{{ $sm['label'] }}</span>
                    </div>
                </div>
            </div>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulaire d'édition --}}
    <div class="form-card">
        <div class="form-card-header">
            <i class="fas fa-edit"></i>
            <h3>Informations du projet</h3>
        </div>
        <div class="form-card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Erreurs de validation :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="editProjectForm" action="{{ route('admin.projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="title" class="pe-label">Titre du projet *</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                           id="title" name="title" value="{{ old('title', $project->title) }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label class="pe-label">Description / Brief</label>
                    <div id="quill-editor">{!! old('description', $project->description) !!}</div>
                    <input type="hidden" id="description" name="description" value="{{ old('description', $project->description) }}">
                    @error('description')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    <small class="pe-hint"><i class="fas fa-info-circle me-1"></i>Gras, listes, liens, couleurs disponibles via la barre d'outils.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="software_used" class="pe-label">Logiciels utilisés</label>
                        <input type="text" class="form-control @error('software_used') is-invalid @enderror"
                               id="software_used" name="software_used"
                               value="{{ old('software_used', $swStr) }}"
                               placeholder="Ex: photoshop, illustrator, figma">
                        <small class="pe-hint">Séparez par des virgules</small>
                        @error('software_used')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="deadline" class="pe-label">Deadline</label>
                        <input type="date" class="form-control @error('deadline') is-invalid @enderror"
                               id="deadline" name="deadline"
                               value="{{ old('deadline', $project->deadline ? $project->deadline->format('Y-m-d') : '') }}">
                        @error('deadline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="status" class="pe-label">Statut *</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="en_cours" {{ old('status', $project->status) == 'en_cours' ? 'selected' : '' }}>Pas encore fait</option>
                            <option value="termine" {{ old('status', $project->status) == 'termine' ? 'selected' : '' }}>Terminé</option>
                            <option value="valide" {{ old('status', $project->status) == 'valide' ? 'selected' : '' }}>Validé</option>
                            <option value="rejete" {{ old('status', $project->status) == 'rejete' ? 'selected' : '' }}>Rejeté</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-4">
                        <label for="link" class="pe-label">Lien externe</label>
                        <input type="url" class="form-control @error('link') is-invalid @enderror"
                               id="link" name="link" value="{{ old('link', $project->link) }}"
                               placeholder="https://exemple.com">
                        @error('link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                @if(isset($relatedProjects) && $relatedProjects->count() > 1)
                <div class="form-check mb-4" style="background:rgba(139,92,246,0.08); border:1px solid rgba(139,92,246,0.25); border-radius:10px; padding:0.75rem 0.75rem 0.75rem 2.25rem;">
                    <input class="form-check-input" type="checkbox" name="bulk" value="1" id="bulk" {{ old('bulk') ? 'checked' : '' }}>
                    <label class="form-check-label text-white-50" for="bulk">
                        <i class="fas fa-users me-1" style="color:#8b5cf6;"></i>
                        Appliquer ces modifications aux <strong class="text-white">{{ $relatedProjects->count() }} assignations</strong> de ce projet
                    </label>
                </div>
                @endif

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                    <a href="{{ route('admin.projects.view', $project->id) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quillContainer = document.getElementById('quill-editor');
    const hiddenDescription = document.getElementById('description');
    if (quillContainer && hiddenDescription) {
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    ['link'],
                    ['clean']
                ]
            },
            placeholder: 'Décrivez les consignes du projet en détail...'
        });
        hiddenDescription.value = quill.root.innerHTML;
        quill.on('text-change', function() {
            hiddenDescription.value = quill.root.innerHTML;
        });
    }
});
</script>
@endpush
@endsection
