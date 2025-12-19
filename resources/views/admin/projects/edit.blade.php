@extends('layouts.admin')

@section('title', 'Modifier le Projet - ' . $project->title)

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .text-muted {
        color: #94a3b8 !important;
    }

    small.text-muted {
        color: #94a3b8 !important;
    }

    .students-tools {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .students-tools .left,
    .students-tools .right {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
        <div>
            <h2 class="text-white mb-1"><i class="fas fa-edit me-2"></i>Modifier le Projet</h2>
            <div class="text-muted">{{ $project->title }}</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('admin.projects.view', $project->id) }}" class="btn btn-outline-light">
                <i class="fas fa-eye me-2"></i>Voir
            </a>
            <a href="{{ route('admin.projects.view', $project->id) }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <!-- Formulaire d'édition -->
    <div class="row">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-edit"></i>
                    <h3>Informations du Projet</h3>
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

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="title" class="form-label">Titre du projet *</label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title', $project->title) }}"
                                       required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <div id="quill-editor">{!! old('description', $project->description) !!}</div>
                                <input type="hidden" id="description" name="description" value="{!! old('description', $project->description) !!}">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Utilisez l'éditeur pour formater votre texte (gras, italique, listes, liens, etc.)
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="software_used" class="form-label">Logiciels utilisés</label>
                                <input type="text"
                                       class="form-control @error('software_used') is-invalid @enderror"
                                       id="software_used"
                                       name="software_used"
                                       value="{{ old('software_used', is_array($project->software_used) ? implode(', ', $project->software_used) : $project->software_used) }}"
                                       placeholder="Ex: photoshop, illustrator, indesign">
                                <small class="form-text text-muted">Séparez les logiciels par des virgules</small>
                                @error('software_used')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="deadline" class="form-label">Délai (deadline)</label>
                                <input type="date"
                                       class="form-control @error('deadline') is-invalid @enderror"
                                       id="deadline"
                                       name="deadline"
                                       value="{{ old('deadline', $project->deadline ? $project->deadline->format('Y-m-d') : '') }}">
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Deadline
                                </small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Statut *</label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status"
                                        name="status"
                                        required>
                                    <option value="en_cours" {{ old('status', $project->status) == 'en_cours' ? 'selected' : '' }}>
                                        Pas encore Fait
                                    </option>
                                    <option value="termine" {{ old('status', $project->status) == 'termine' ? 'selected' : '' }}>
                                        Terminé
                                    </option>
                                    <option value="valide" {{ old('status', $project->status) == 'valide' ? 'selected' : '' }}>
                                        Validé
                                    </option>
                                    <option value="rejete" {{ old('status', $project->status) == 'rejete' ? 'selected' : '' }}>
                                        Rejeté
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-4">
                                <label for="link" class="form-label">Lien externe</label>
                                <input type="url"
                                       class="form-control @error('link') is-invalid @enderror"
                                       id="link"
                                       name="link"
                                       value="{{ old('link', $project->link) }}"
                                       placeholder="https://exemple.com">
                                @error('link')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

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

        <div class="col-lg-4">
            <!-- Étudiants concernés -->
            @if(isset($relatedProjects))
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-users"></i>
                    <h3>Étudiants concernés ({{ $relatedProjects->count() }})</h3>
                </div>
                <div class="form-card-body">
                    @if(isset($studentsList))
                    <form action="{{ route('admin.projects.add-student', $project->id) }}" method="POST" class="mb-3">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-12">
                                <label for="student_user_id" class="form-label">Ajouter un étudiant</label>
                                <select class="form-select" id="student_user_id" name="student_user_id" required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($studentsList as $u)
                                        @php
                                            $st = $u->student;
                                            $n = trim(($st->first_name ?? '') . ' ' . ($st->last_name ?? ''));
                                            if ($n === '') { $n = $u->name ?? $u->email; }
                                        @endphp
                                        <option value="{{ $u->id }}">{{ $n }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-user-plus me-1"></i>
                                    Ajoute une nouvelle assignation de ce projet pour l'étudiant sélectionné
                                </small>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2"></i>Ajouter
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif

                    <div class="students-tools">
                        <div class="left">
                            <input type="text" id="relatedStudentsSearch" class="form-control" placeholder="Rechercher un étudiant (nom, email...)" autocomplete="off" style="min-width: 220px;">
                            <span class="text-muted" style="font-size: 0.9rem;" id="relatedStudentsCount"></span>
                        </div>
                        <div class="right">
                            <button type="button" class="btn btn-sm btn-outline-light" id="copyVisibleEmailsBtn">
                                <i class="fas fa-copy me-1"></i>Copier emails
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Étudiant</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($relatedProjects as $rp)
                                    @php
                                        $rpStudent = $rp->user->student ?? null;
                                        $rpName = trim(($rpStudent->first_name ?? '') . ' ' . ($rpStudent->last_name ?? ''));
                                        if ($rpName === '') {
                                            $rpName = $rp->user->name ?? 'Étudiant';
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(!empty($rpStudent?->profile_photo))
                                                    <img src="{{ asset('storage/' . $rpStudent->profile_photo) }}" alt="{{ $rpName }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-weight: 600;">
                                                        {{ strtoupper(substr($rpName, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-medium">{{ $rpName }}</div>
                                                    <small class="text-muted">{{ $rp->user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.projects.view', $rp->id) }}" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.projects.edit', $rp->id) }}" class="btn btn-sm btn-outline-warning ms-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.projects.assigned.delete', $rp->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Supprimer ce projet pour cet étudiant ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-3">Aucun étudiant trouvé.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Informations du projet -->
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Informations</h3>
                </div>
                <div class="form-card-body">
                    <div class="mb-3">
                        <small class="text-muted">Statut</small>
                        <p class="text-white mb-0">{{ $project->status === 'en_cours' ? 'Pas encore Fait' : $project->status }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Deadline</small>
                        <p class="text-white mb-0">{{ $project->deadline ? $project->deadline->format('d/m/Y') : '—' }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Créé le</small>
                        <p class="text-white mb-0">{{ $project->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Dernière modification</small>
                        <p class="text-white mb-0">{{ $project->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    @if($project->images && $project->images->count() > 0)
                        <div class="mb-3">
                            <small class="text-muted">Fichiers associés</small>
                            <p class="text-white mb-0">{{ $project->images->count() }} fichier(s)</p>
                        </div>
                    @endif

                    @php
                        $statusClass = match($project->status) {
                            'valide' => 'bg-success',
                            'en_cours' => 'bg-warning',
                            'termine' => 'bg-info',
                            'rejete' => 'bg-danger',
                            default => 'bg-secondary'
                        };

                        $statusLabel = match($project->status) {
                            'valide' => 'Validé',
                            'en_cours' => 'Pas encore Fait',
                            'termine' => 'Terminé',
                            'rejete' => 'Rejeté',
                            default => 'Inconnu'
                        };
                    @endphp

                    <div class="mb-0">
                        <small class="text-muted">Statut actuel</small>
                        <br>
                        <span class="badge {{ $statusClass }} mt-1">{{ $statusLabel }}</span>
                    </div>
                </div>
            </div>
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

    const input = document.getElementById('relatedStudentsSearch');
    const countEl = document.getElementById('relatedStudentsCount');
    const copyBtn = document.getElementById('copyVisibleEmailsBtn');

    const table = input?.closest('.form-card-body')?.querySelector('table') ?? null;
    const rows = table ? Array.from(table.querySelectorAll('tbody tr')) : [];

    const updateCount = () => {
        if (!countEl) return;
        if (!table) {
            countEl.textContent = '';
            return;
        }
        const visible = rows.filter((tr) => tr.style.display !== 'none' && !tr.querySelector('td[colspan]'));
        countEl.textContent = `${visible.length} affiché(s)`;
    };

    if (input && table) {
        input.addEventListener('input', function () {
            const q = (input.value || '').toLowerCase().trim();
            rows.forEach((tr) => {
                if (tr.querySelector('td[colspan]')) return;
                const text = (tr.innerText || '').toLowerCase();
                tr.style.display = q === '' || text.includes(q) ? '' : 'none';
            });
            updateCount();
        });
    }

    updateCount();

    if (copyBtn && table) {
        copyBtn.addEventListener('click', async function () {
            const visibleRows = rows.filter((tr) => tr.style.display !== 'none' && !tr.querySelector('td[colspan]'));
            const emails = visibleRows
                .map((tr) => tr.querySelector('small')?.innerText?.trim())
                .filter((e) => e && e.includes('@'));
            const text = emails.join('\n');
            if (!text) return;
            try {
                await navigator.clipboard.writeText(text);
                copyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Copié';
                setTimeout(() => {
                    copyBtn.innerHTML = '<i class="fas fa-copy me-1"></i>Copier emails';
                }, 1500);
            } catch (e) {
                alert('Impossible de copier.');
            }
        });
    }
});
</script>
@endpush
@endsection
