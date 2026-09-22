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
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .pe-header-icon {
        width: 52px; height: 52px; border-radius: 14px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.3rem; flex-shrink: 0;
    }
    .pe-meta-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        color: #cbd5e1;
        font-size: 0.78rem;
        padding: 0.3rem 0.7rem;
        border-radius: 20px;
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
        min-height: 220px;
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

    .pe-student-row { transition: background 0.15s ease; }
    .pe-student-row:hover { background: rgba(139,92,246,0.08) !important; }

    .pe-file-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.25);
        color: #60a5fa; font-size: 0.78rem;
        padding: 0.3rem 0.7rem; border-radius: 8px; text-decoration: none;
    }
    .pe-file-chip:hover { background: rgba(59,130,246,0.2); color: #93c5fd; }
    .pe-file-img {
        width: 64px; height: 64px; border-radius: 10px; object-fit: cover;
        border: 2px solid rgba(255,255,255,0.1); transition: all 0.2s;
    }
    .pe-file-img:hover { border-color: #8b5cf6; transform: scale(1.05); }

    .pe-danger-zone {
        border: 1px solid rgba(239,68,68,0.3);
        background: rgba(239,68,68,0.05);
        border-radius: 12px;
        padding: 1rem;
    }

    .students-tools { display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; }
    .students-tools .left, .students-tools .right { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; }
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

    {{-- Header consolidé --}}
    <div class="pe-header">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div class="d-flex align-items-start gap-3">
                <div class="pe-header-icon"><i class="fas fa-edit"></i></div>
                <div>
                    <h2 class="text-white mb-1" style="font-size:1.4rem; font-weight:700;">Modifier le projet</h2>
                    <div class="text-white-50 mb-2" style="font-size:0.95rem;">{{ $project->title }}</div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge {{ $sm['class'] }}"><i class="fas fa-{{ $sm['icon'] }} me-1"></i>{{ $sm['label'] }}</span>
                        <span class="pe-meta-badge"><i class="fas fa-tag"></i>{{ $project->category ?? '—' }}</span>
                        <span class="pe-meta-badge"><i class="fas fa-hashtag"></i>#{{ $project->id }}</span>
                        <span class="pe-meta-badge"><i class="fas fa-user"></i>{{ $project->user->student ? trim(($project->user->student->first_name ?? '') . ' ' . ($project->user->student->last_name ?? '')) : ($project->user->name ?? '—') }}</span>
                        <span class="pe-meta-badge"><i class="fas fa-calendar-plus"></i>Créé le {{ $project->created_at?->format('d/m/Y') }}</span>
                        @if($project->updated_at)
                        <span class="pe-meta-badge"><i class="fas fa-clock"></i>Maj {{ $project->updated_at->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('admin.projects.view', $project->id) }}" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-eye me-1"></i>Voir
                </a>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
            </div>
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

    <div class="row">
        {{-- ═══ Colonne principale : formulaire ═══ --}}
        <div class="col-lg-8">
            <div class="form-card mb-4">
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

            {{-- Fichiers associés --}}
            @if($project->images && $project->images->count() > 0)
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <i class="fas fa-paperclip"></i>
                    <h3>Fichiers associés ({{ $project->images->count() }})</h3>
                </div>
                <div class="form-card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($project->images as $img)
                            @php
                                $fPath = ltrim((string) ($img->file_path ?? ''), '/');
                                if (str_starts_with($fPath, 'storage/app/public/')) {
                                    $fPath = substr($fPath, strlen('storage/app/public/'));
                                }
                                $fUrl = \App\Models\MediaUrl::fromPath($fPath);
                                $fName = $img->original_name ?? basename($fPath);
                                $fExt = strtolower(pathinfo($fPath, PATHINFO_EXTENSION));
                            @endphp
                            @if(in_array($fExt, ['jpg','jpeg','png','gif','webp']))
                                <a href="{{ $fUrl }}" target="_blank"><img src="{{ $fUrl }}" alt="{{ $fName }}" class="pe-file-img" loading="lazy"></a>
                            @else
                                <a href="{{ $fUrl }}" target="_blank" class="pe-file-chip">
                                    <i class="fas fa-{{ $fExt === 'pdf' ? 'file-pdf' : 'file' }}"></i>{{ Str::limit($fName, 28) }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- ═══ Sidebar ═══ --}}
        <div class="col-lg-4">
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
                        <label for="student_user_id" class="pe-label">Ajouter un étudiant</label>
                        <select class="form-select mb-2" id="student_user_id" name="student_user_id" required>
                            <option value="">-- Sélectionner --</option>
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
                            @foreach($sortedGroups as $groupName)
                                @php $users = $groupedStudents->get($groupName, collect()); @endphp
                                <optgroup label="{{ $groupName }} ({{ $users->count() }})">
                                    @foreach($users as $u)
                                        @php
                                            $st = $u->student;
                                            $n = trim(($st->first_name ?? '') . ' ' . ($st->last_name ?? ''));
                                            if ($n === '') { $n = $u->name ?? $u->email; }
                                        @endphp
                                        <option value="{{ $u->id }}">{{ $n }} ({{ $u->email }})</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-user-plus me-1"></i>Assigner à cet étudiant
                        </button>
                    </form>
                    @endif

                    <div class="students-tools">
                        <div class="left" style="flex:1;">
                            <input type="text" id="relatedStudentsSearch" class="form-control form-control-sm" placeholder="Rechercher (nom, email...)" autocomplete="off" style="min-width: 160px;">
                        </div>
                        <div class="right">
                            <span class="text-muted" style="font-size: 0.8rem;" id="relatedStudentsCount"></span>
                            <button type="button" class="btn btn-sm btn-outline-light" id="copyVisibleEmailsBtn" title="Copier les emails visibles">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive" style="max-height: 420px; overflow-y: auto;">
                        <table class="table table-dark table-hover mb-0 align-middle">
                            <tbody>
                                @forelse($relatedProjects as $rp)
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
                                                    <img src="{{ $rpPhotoUrl }}" alt="{{ $rpName }}" class="rounded-circle me-2" style="width: 34px; height: 34px; object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 34px; height: 34px; font-weight: 600; background: linear-gradient(135deg,#8b5cf6,#6366f1);">
                                                        {{ strtoupper(substr($rpName, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div style="min-width:0;">
                                                    <div class="fw-medium text-truncate">{{ $rpName }}</div>
                                                    <small class="text-muted text-truncate d-block">{{ $rp->user->email ?? '' }}</small>
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
                                @empty
                                    <tr><td class="text-center py-3">Aucun étudiant trouvé.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- Zone danger --}}
            <div class="pe-danger-zone">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <div class="text-white fw-semibold" style="font-size:0.9rem;"><i class="fas fa-exclamation-triangle me-1 text-danger"></i>Zone de danger</div>
                        <small class="text-muted">Supprimer ce projet et ses fichiers.</small>
                    </div>
                    <form action="{{ route('admin.projects.delete', $project->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ce projet ? Cette action est irréversible.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </button>
                    </form>
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
                copyBtn.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => { copyBtn.innerHTML = '<i class="fas fa-copy"></i>'; }, 1500);
            } catch (e) {
                alert('Impossible de copier.');
            }
        });
    }
});
</script>
@endpush
@endsection
