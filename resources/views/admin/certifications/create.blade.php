@extends('layouts.admin')

@section('title', 'Nouvelle Certification')

@push('styles')
<style>
    .page-header { background: linear-gradient(135deg, #1e3c72, #2a5298); border-radius: 16px; padding: 2rem; margin-bottom: 2rem; }
    .form-card { background: linear-gradient(145deg, #1e293b, #334155); border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); padding: 1.5rem; margin-bottom: 1.5rem; }
    .form-control, .form-select { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 10px; }
    .form-control:focus, .form-select:focus { background: rgba(255,255,255,0.08); border-color: #6366f1; color: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
    .form-control::placeholder, .search-students::placeholder { color: #94a3b8; opacity: 1; }
    .form-label { color: #cbd5e1; font-weight: 500; }
    textarea.form-control { min-height: 100px; }

    .btn-publish { background: linear-gradient(45deg, #10b981, #059669); border: none; padding: 0.75rem 1.5rem; border-radius: 12px; color: #fff; font-weight: 600; }
    .btn-publish:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(16,185,129,0.4); color: #fff; }
    .btn-draft { background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); padding: 0.75rem 1.5rem; border-radius: 12px; color: #94a3b8; font-weight: 600; }
    .btn-draft:hover { background: rgba(255,255,255,0.15); color: #fff; }
    .btn-schedule { background: linear-gradient(45deg, #f59e0b, #d97706); border: none; padding: 0.75rem 1.5rem; border-radius: 12px; color: #fff; font-weight: 600; }
    .btn-schedule:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(245,158,11,0.4); color: #fff; }

    .student-list { max-height: 400px; overflow-y: auto; }
    .student-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 0.75rem; border-radius: 10px; margin-bottom: 4px; cursor: pointer; transition: all 0.2s; }
    .student-item:hover { background: rgba(99,102,241,0.1); }
    .student-item.selected { background: rgba(16,185,129,0.15); border: 1px solid rgba(16,185,129,0.3); }
    .student-item input[type=checkbox] { accent-color: #10b981; width: 18px; height: 18px; cursor: pointer; }
    .student-name { color: #e2e8f0; font-weight: 500; font-size: 0.9rem; }
    .student-meta { color: #94a3b8; font-size: 0.75rem; }
    .student-badge { background: rgba(99,102,241,0.2); color: #818cf8; padding: 2px 8px; border-radius: 10px; font-size: 0.7rem; font-weight: 600; }
    .search-students { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 10px; padding: 0.5rem 1rem; width: 100%; margin-bottom: 0.75rem; }
    .search-students:focus { outline: none; border-color: #6366f1; }
    .select-actions { display: flex; gap: 0.5rem; margin-bottom: 0.75rem; }
    .select-actions button { background: none; border: 1px solid rgba(255,255,255,0.15); color: #94a3b8; padding: 3px 10px; border-radius: 8px; font-size: 0.75rem; cursor: pointer; }
    .select-actions button:hover { border-color: #6366f1; color: #fff; }
    .selected-count { color: #10b981; font-weight: 600; font-size: 0.85rem; }
    .schedule-box { display: none; margin-top: 0.75rem; }
    .schedule-box.visible { display: block; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-white mb-1"><i class="fas fa-plus-circle me-2"></i>Nouvelle Certification</h1>
                <p class="text-white-50 mb-0">Créez un examen de certification</p>
            </div>
            <a href="{{ route('admin.certifications.index') }}" class="btn btn-secondary" style="border-radius: 10px;">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <form action="{{ route('admin.certifications.store') }}" method="POST" id="certForm">
        @csrf
        <input type="hidden" name="status" id="statusField" value="draft">

        <div class="row">
            <!-- Colonne gauche -->
            <div class="col-lg-8">
                <div class="form-card">
                    <h5 class="text-white mb-3"><i class="fas fa-info-circle me-2"></i>Informations générales</h5>
                    <div class="mb-3">
                        <label for="title" class="form-label">Titre de la certification <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required placeholder="Ex: Certification Design Graphique - Niveau 1">
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description de la certification...">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="instructions" class="form-label">Consignes pour l'étudiant</label>
                        <textarea class="form-control" id="instructions" name="instructions" rows="4" placeholder="Instructions affichées avant le début du test...">{{ old('instructions') }}</textarea>
                    </div>
                </div>

                <!-- Sélection des étudiants -->
                <div class="form-card">
                    <h5 class="text-white mb-3">
                        <i class="fas fa-user-check me-2"></i>Étudiants cibles
                        <span class="selected-count ms-2" id="selectedCount">(0 sélectionné)</span>
                    </h5>
                    <p class="text-muted mb-3" style="font-size:0.85rem;">
                        <i class="fas fa-info-circle me-1"></i>Seuls les étudiants actifs ayant réalisé au moins 2 TP/Projets sont affichés.
                    </p>

                    <input type="text" class="search-students" id="searchStudents" placeholder="Rechercher un étudiant par nom, prénom ou formation...">

                    <div class="select-actions">
                        <button type="button" onclick="selectAll()"><i class="fas fa-check-double me-1"></i>Tout sélectionner</button>
                        <button type="button" onclick="deselectAll()"><i class="fas fa-times me-1"></i>Tout désélectionner</button>
                        <button type="button" onclick="filterByFormation()"><i class="fas fa-filter me-1"></i>Filtrer par formation choisie</button>
                    </div>

                    <div class="student-list" id="studentList">
                        @if($eligibleStudents->isEmpty())
                            <p class="text-center text-muted py-3">Aucun étudiant éligible (2+ TP/Projets réalisés).</p>
                        @else
                            @foreach($eligibleStudents as $student)
                            <label class="student-item" data-name="{{ strtolower(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')) }}" data-formation="{{ strtolower($student->program ?? '') }}">
                                <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" {{ in_array($student->id, old('student_ids', [])) ? 'checked' : '' }} onchange="updateCount()">
                                <div class="flex-grow-1">
                                    <div class="student-name">{{ $student->first_name }} {{ $student->last_name }}</div>
                                    <div class="student-meta">{{ $student->email ?? '—' }} &bull; {{ $student->program ?? 'Non défini' }}</div>
                                </div>
                                <span class="student-badge">{{ $student->tp_project_count }} TP/Proj</span>
                            </label>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="col-lg-4">
                <div class="form-card">
                    <h5 class="text-white mb-3"><i class="fas fa-cogs me-2"></i>Paramètres</h5>

                    <div class="mb-3">
                        <label for="formation" class="form-label">Formation cible</label>
                        <select class="form-select" id="formation" name="formation">
                            <option value="">Toutes les formations</option>
                            @foreach($formations as $f)
                                <option value="{{ $f }}" {{ old('formation') == $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="duration_minutes" class="form-label">Durée (minutes) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="duration_minutes" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" min="5" max="480" required>
                        <small class="text-muted">Le décompte démarre dès que l'étudiant clique sur "Commencer"</small>
                    </div>

                    <div class="mb-3">
                        <label for="passing_score" class="form-label">Note de passage (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="passing_score" name="passing_score" value="{{ old('passing_score', 50) }}" min="0" max="100" step="0.5" required>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="shuffle_questions" name="shuffle_questions" value="1" {{ old('shuffle_questions') ? 'checked' : '' }}>
                        <label class="form-check-label text-white" for="shuffle_questions">Mélanger les questions</label>
                    </div>
                </div>

                <!-- Programmation -->
                <div class="form-card">
                    <h5 class="text-white mb-3"><i class="fas fa-calendar-alt me-2"></i>Programmation</h5>
                    <div class="mb-3">
                        <label for="scheduled_at" class="form-label">Date et heure programmées</label>
                        <input type="datetime-local" class="form-control" id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                        <small class="text-muted">Laissez vide pour publier immédiatement ou sauvegarder en brouillon</small>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="d-flex flex-column gap-2">
                    <button type="button" class="btn-publish w-100" onclick="submitAs('published')">
                        <i class="fas fa-paper-plane me-2"></i>Publier et notifier les étudiants
                    </button>
                    <button type="button" class="btn-schedule w-100" onclick="submitAs('scheduled')">
                        <i class="fas fa-clock me-2"></i>Programmer et notifier
                    </button>
                    <button type="button" class="btn-draft w-100" onclick="submitAs('draft')">
                        <i class="fas fa-save me-2"></i>Sauvegarder en brouillon
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function updateCount() {
    const checked = document.querySelectorAll('#studentList input[type=checkbox]:checked').length;
    document.getElementById('selectedCount').textContent = '(' + checked + ' sélectionné' + (checked > 1 ? 's' : '') + ')';
}

function selectAll() {
    document.querySelectorAll('#studentList .student-item').forEach(item => {
        if (item.style.display !== 'none') {
            item.querySelector('input[type=checkbox]').checked = true;
        }
    });
    updateCount();
}

function deselectAll() {
    document.querySelectorAll('#studentList input[type=checkbox]').forEach(cb => cb.checked = false);
    updateCount();
}

function filterByFormation() {
    const formation = document.getElementById('formation').value.toLowerCase();
    document.querySelectorAll('#studentList .student-item').forEach(item => {
        if (!formation) {
            item.style.display = '';
        } else {
            item.style.display = item.dataset.formation.includes(formation) ? '' : 'none';
        }
    });
}

document.getElementById('searchStudents').addEventListener('input', function() {
    const term = this.value.toLowerCase();
    document.querySelectorAll('#studentList .student-item').forEach(item => {
        const name = item.dataset.name;
        const formation = item.dataset.formation;
        item.style.display = (name.includes(term) || formation.includes(term)) ? '' : 'none';
    });
});

function submitAs(status) {
    const form = document.getElementById('certForm');
    document.getElementById('statusField').value = status;

    if (status === 'scheduled') {
        const scheduled = document.getElementById('scheduled_at').value;
        if (!scheduled) {
            alert('Veuillez indiquer une date de programmation.');
            document.getElementById('scheduled_at').focus();
            return;
        }
    }

    if (status === 'published' || status === 'scheduled') {
        const checked = document.querySelectorAll('#studentList input[type=checkbox]:checked').length;
        if (checked === 0) {
            if (!confirm('Aucun étudiant sélectionné. Continuer quand même ?')) return;
        }
    }

    form.submit();
}

// Init count on page load
updateCount();
</script>
@endpush
