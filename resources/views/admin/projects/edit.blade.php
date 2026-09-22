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
    .students-tools { display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
    .students-tools .left, .students-tools .right { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }

    .pe-assign-group {
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px;
        margin-bottom: 0.6rem;
        overflow: hidden;
    }
    .pe-assign-group-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.55rem 0.8rem;
        background: rgba(255,255,255,0.04);
        cursor: pointer; user-select: none;
    }
    .pe-assign-group-head:hover { background: rgba(139,92,246,0.08); }
    .pe-assign-group-head .gname { font-size: 0.82rem; font-weight: 600; color: #e2e8f0; }
    .pe-assign-group-head .gcount { font-size: 0.7rem; color: #94a3b8; }
    .pe-assign-group-body { padding: 0.4rem 0.6rem; display: none; }
    .pe-assign-group.open .pe-assign-group-body { display: block; }
    .pe-assign-check {
        display: flex; align-items: center; gap: 0.55rem;
        padding: 0.35rem 0.4rem; border-radius: 8px; cursor: pointer;
    }
    .pe-assign-check:hover { background: rgba(139,92,246,0.1); }
    .pe-assign-check input { accent-color: #8b5cf6; width: 15px; height: 15px; flex-shrink: 0; }
    .pe-assign-check .sname { font-size: 0.85rem; color: #e2e8f0; }
    .pe-assign-check .semail { font-size: 0.72rem; color: #64748b; }
    .pe-selected-bar {
        position: sticky; bottom: 0;
        background: linear-gradient(135deg, rgba(139,92,246,0.2), rgba(99,102,241,0.15));
        border: 1px solid rgba(139,92,246,0.4);
        border-radius: 10px;
        padding: 0.6rem 0.8rem;
        display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;
        margin-top: 0.75rem;
    }
    .pe-student-row { transition: background 0.15s ease; }
    .pe-student-row:hover { background: rgba(139,92,246,0.08) !important; }
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

<div class="container-fluid py-4" style="max-width: 1400px;">

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
    @if(session('emails_failures') && is_array(session('emails_failures')) && count(session('emails_failures')))
        <div class="alert alert-warning alert-dismissible fade show mb-4">
            <strong><i class="fas fa-envelope me-2"></i>Emails non envoyés :</strong>
            <ul class="mb-0 mt-1">
                @foreach(session('emails_failures') as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Formulaire d'édition --}}
    <div class="row">
        <div class="col-lg-7">
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

        {{-- Sidebar : assignation multi-étudiants --}}
        <div class="col-lg-5">
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-user-plus"></i>
                    <h3>Assigner à des étudiants</h3>
                </div>
                <div class="form-card-body">
                    @if(isset($studentsList) && $studentsList->count() > 0)
                    <form action="{{ route('admin.projects.add-student', $project->id) }}" method="POST" id="assignStudentsForm">
                        @csrf
                        <div class="mb-2">
                            <input type="text" id="assignSearch" class="form-control form-control-sm" placeholder="Rechercher un étudiant (nom, email...)" autocomplete="off">
                        </div>

                        @php
                            $groupedStudents = collect($studentsList ?? [])->groupBy(function ($u) {
                                $program = $u->student->program ?? null;
                                $program = is_string($program) ? trim($program) : '';
                                if ($program === '') {
                                    return 'Sans formation';
                                }
                                $normalized = strtolower(str_replace([' ', '_', '-', '&'], '', $program));
                                if (str_contains($normalized, 'designgraphiquecommunity') || (str_contains($normalized, 'designgraphique') && str_contains($normalized, 'community'))) {
                                    return 'Design Graphique & Community Management';
                                }
                                if (str_contains($normalized, 'designgraphique')) {
                                    return 'Design Graphique';
                                }
                                if (str_contains($normalized, 'community')) {
                                    return 'Community Management';
                                }
                                if (str_contains($normalized, 'informatique') || str_contains($normalized, 'gestioninformatique')) {
                                    return 'Gestion Informatique';
                                }
                                if (str_contains($normalized, 'intelligence')) {
                                    return 'Intelligence Artificielle';
                                }
                                return $program;
                            });

                            $order = [
                                'Design Graphique',
                                'Design Graphique & Community Management',
                                'Community Management',
                                'Gestion Informatique',
                                'Intelligence Artificielle',
                                'Sans formation',
                            ];

                            $sortedGroups = collect($order)
                                ->filter(fn ($k) => $groupedStudents->has($k))
                                ->merge($groupedStudents->keys()->diff($order)->sort())
                                ->values();
                        @endphp

                        <div style="max-height: 380px; overflow-y: auto;" id="assignGroups">
                            @foreach($sortedGroups as $groupName)
                                @php $users = $groupedStudents->get($groupName, collect()); @endphp
                                <div class="pe-assign-group" data-group="{{ $groupName }}">
                                    <div class="pe-assign-group-head" onclick="this.parentElement.classList.toggle('open')">
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="checkbox" class="group-select-all" title="Tout sélectionner" onclick="event.stopPropagation()">
                                            <span class="gname">{{ $groupName }}</span>
                                        </div>
                                        <span class="gcount">{{ $users->count() }} <i class="fas fa-chevron-down ms-1"></i></span>
                                    </div>
                                    <div class="pe-assign-group-body">
                                        @foreach($users as $u)
                                            @php
                                                $st = $u->student;
                                                $n = trim(($st->first_name ?? '') . ' ' . ($st->last_name ?? ''));
                                                if ($n === '') { $n = $u->name ?? $u->email; }
                                            @endphp
                                            <label class="pe-assign-check" data-search="{{ strtolower($n . ' ' . ($u->email ?? '')) }}">
                                                <input type="checkbox" name="student_user_ids[]" value="{{ $u->id }}">
                                                <div style="min-width:0;">
                                                    <div class="sname text-truncate">{{ $n }}</div>
                                                    <div class="semail text-truncate">{{ $u->email }}</div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="pe-selected-bar">
                            <span class="text-white" style="font-size:0.85rem;">
                                <i class="fas fa-check-square me-1" style="color:#8b5cf6;"></i>
                                <strong id="assignSelectedCount">0</strong> sélectionné(s)
                            </span>
                            <button type="submit" class="btn btn-sm" id="assignSubmitBtn" disabled
                                    style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color:#fff; border:none;">
                                <i class="fas fa-user-plus me-1"></i>Assigner
                            </button>
                        </div>
                    </form>
                    @else
                    <p class="text-white-50 mb-0 text-center py-3">
                        <i class="fas fa-check-circle me-1"></i>Tous les étudiants ont déjà ce projet.
                    </p>
                    @endif
                </div>
            </div>

            {{-- Étudiants déjà assignés --}}
            @if(isset($relatedProjects) && $relatedProjects->count() > 0)
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-users"></i>
                    <h3>Déjà assignés ({{ $relatedProjects->count() }})</h3>
                </div>
                <div class="form-card-body">
                    <div class="mb-2">
                        <input type="text" id="relatedStudentsSearch" class="form-control form-control-sm" placeholder="Rechercher (nom, email...)" autocomplete="off">
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <span class="text-muted" style="font-size:0.78rem;" id="relatedStudentsCount"></span>
                            <button type="button" class="btn btn-sm btn-outline-light" id="copyVisibleEmailsBtn" title="Copier les emails visibles">
                                <i class="fas fa-copy me-1"></i>Emails
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 320px; overflow-y: auto;">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <tbody>
                                @foreach($relatedProjects as $rp)
                                    @php
                                        $rpStudent = $rp->user->student ?? null;
                                        $rpName = trim(($rpStudent->first_name ?? '') . ' ' . ($rpStudent->last_name ?? ''));
                                        if ($rpName === '') { $rpName = $rp->user->name ?? 'Étudiant'; }
                                        $rpPhotoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($rpStudent?->profile_photo ?? null);
                                    @endphp
                                    <tr class="pe-student-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($rpPhotoUrl)
                                                    <img src="{{ $rpPhotoUrl }}" alt="{{ $rpName }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-weight: 600; background: linear-gradient(135deg,#8b5cf6,#6366f1); font-size:0.8rem;">
                                                        {{ strtoupper(substr($rpName, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div style="min-width:0;">
                                                    <div class="fw-medium text-truncate" style="font-size:0.88rem;">{{ $rpName }}</div>
                                                    <small class="text-muted text-truncate d-block" style="font-size:0.75rem;">{{ $rp->user->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end" style="white-space:nowrap;">
                                            <a href="{{ route('admin.projects.view', $rp->id) }}" class="btn btn-sm btn-outline-info" title="Voir"><i class="fas fa-eye"></i></a>
                                            <form action="{{ route('admin.projects.assigned.delete', $rp->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Retirer ce projet pour cet étudiant ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Retirer"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
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

    // ═══ Assignation multi-étudiants ═══
    const assignForm = document.getElementById('assignStudentsForm');
    const assignSearch = document.getElementById('assignSearch');
    const assignCountEl = document.getElementById('assignSelectedCount');
    const assignSubmitBtn = document.getElementById('assignSubmitBtn');

    if (assignForm) {
        const studentChecks = Array.from(assignForm.querySelectorAll('input[name="student_user_ids[]"]'));
        const groupHeads = Array.from(assignForm.querySelectorAll('.group-select-all'));

        const refreshCount = () => {
            const n = studentChecks.filter(c => c.checked).length;
            if (assignCountEl) assignCountEl.textContent = n;
            if (assignSubmitBtn) assignSubmitBtn.disabled = n === 0;
        };

        studentChecks.forEach(c => c.addEventListener('change', refreshCount));

        groupHeads.forEach(head => {
            head.addEventListener('change', function () {
                const group = head.closest('.pe-assign-group');
                group.querySelectorAll('input[name="student_user_ids[]"]').forEach(c => {
                    if (c.closest('.pe-assign-check').style.display !== 'none') {
                        c.checked = head.checked;
                    }
                });
                refreshCount();
            });
        });

        if (assignSearch) {
            assignSearch.addEventListener('input', function () {
                const q = (assignSearch.value || '').toLowerCase().trim();
                assignForm.querySelectorAll('.pe-assign-group').forEach(group => {
                    let visibleInGroup = 0;
                    group.querySelectorAll('.pe-assign-check').forEach(label => {
                        const hay = label.getAttribute('data-search') || '';
                        const show = q === '' || hay.includes(q);
                        label.style.display = show ? '' : 'none';
                        if (show) visibleInGroup++;
                    });
                    group.style.display = visibleInGroup > 0 ? '' : 'none';
                    if (q !== '' && visibleInGroup > 0) group.classList.add('open');
                });
            });
        }

        const firstGroup = assignForm.querySelector('.pe-assign-group');
        if (firstGroup) firstGroup.classList.add('open');

        refreshCount();
    }

    // ═══ Étudiants déjà assignés : recherche + copie emails ═══
    const input = document.getElementById('relatedStudentsSearch');
    const countEl = document.getElementById('relatedStudentsCount');
    const copyBtn = document.getElementById('copyVisibleEmailsBtn');
    const table = input?.closest('.form-card-body')?.querySelector('table') ?? null;
    const rows = table ? Array.from(table.querySelectorAll('tbody tr')) : [];

    const updateCount = () => {
        if (!countEl) return;
        if (!table) { countEl.textContent = ''; return; }
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
                setTimeout(() => { copyBtn.innerHTML = '<i class="fas fa-copy me-1"></i>Emails'; }, 1500);
            } catch (e) {
                alert('Impossible de copier.');
            }
        });
    }
});
</script>
@endpush
@endsection
