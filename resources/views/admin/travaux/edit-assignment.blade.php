@extends('layouts.admin')

@section('title', 'Modifier le TP')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="form-header">
        <div>
            <h1 class="mb-2" style="color: var(--form-text); font-size: 1.8rem; font-weight: 600;">Modifier le Travail Pratique</h1>
            <p style="color: var(--form-text-muted); font-size: 0.95rem;">
                <i class="fas fa-users me-2"></i>Actuellement assigné à <strong>{{ $studentsCount }}</strong> étudiant(s)
            </p>
        </div>
        <a href="{{ route('admin.travaux.assigned') }}" class="btn btn-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.travaux.assignment.update', ['title' => urlencode($title)]) }}" method="POST" class="interactive-dashboard-form">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations du TP -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-file-alt"></i>
                        <h3>Informations du TP</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Titre -->
                        <div class="form-group">
                            <label for="new_title">
                                Titre du TP
                                <span style="color: #EF4444;">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('new_title') is-invalid @enderror"
                                   id="new_title"
                                   name="new_title"
                                   value="{{ old('new_title', $assignment->title) }}"
                                   placeholder="Ex: Réalisation d'un Audit digital"
                                   required>
                            @error('new_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text" style="color: var(--form-text);">
                                <i class="fas fa-info-circle me-1"></i>
                                Ce titre sera mis à jour pour tous les étudiants assignés
                            </small>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description">
                                Description & Consignes
                                <span style="color: #EF4444;">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="10"
                                      placeholder="Décrivez les objectifs, consignes et livrables attendus..."
                                      required>{{ old('description', $assignment->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="form-text" style="color: var(--form-text);">
                                    <i class="fas fa-keyboard me-1"></i>
                                    <span id="charCount">0</span> caractères
                                </small>
                            </div>
                        </div>

                        <!-- Date limite -->
                        <div class="form-group">
                            <label for="deadline">
                                Date & Heure limite
                                <span style="color: #EF4444;">*</span>
                            </label>
                            <input type="datetime-local"
                                   class="form-control @error('deadline') is-invalid @enderror"
                                   id="deadline"
                                   name="deadline"
                                   value="{{ old('deadline', \Carbon\Carbon::parse($assignment->deadline)->format('Y-m-d\TH:i')) }}"
                                   required>
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text" style="color: var(--form-text);">
                                <i class="fas fa-clock me-1"></i>
                                <span id="daysLeft">--</span> jours restants
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Gestion des étudiants -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-users"></i>
                        <h3>Gestion des Étudiants</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group">
                            <label for="students">
                                Étudiants assignés
                                <span style="color: #EF4444;">*</span>
                            </label>
                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-sm"
                                        style="background: rgba(56, 189, 248, 0.1); border: 1px solid var(--form-primary); color: var(--form-primary);"
                                        onclick="selectAll()">
                                    <i class="fas fa-check-double me-1"></i>
                                    Tout sélectionner
                                </button>
                                <button type="button" class="btn btn-sm"
                                        style="background: rgba(239, 68, 68, 0.1); border: 1px solid #EF4444; color: #EF4444;"
                                        onclick="deselectAll()">
                                    <i class="fas fa-times me-1"></i>
                                    Tout désélectionner
                                </button>
                                <div class="ms-auto px-3 py-1 rounded"
                                     style="background: rgba(56, 189, 248, 0.1); border: 1px solid var(--form-primary);">
                                    <i class="fas fa-user-check me-1" style="color: var(--form-primary);"></i>
                                    <span style="color: var(--form-text); font-weight: 600;" id="selectedCount">{{ count($assignedStudentIds) }}</span>
                                    <span style="color: var(--form-text-muted); font-size: 0.9rem;"> sélectionné(s)</span>
                                </div>
                            </div>

                            <select name="students[]"
                                    id="students"
                                    class="form-select @error('students') is-invalid @enderror"
                                    multiple
                                    size="15"
                                    required
                                    style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; font-size: 0.9rem;">
                                @foreach($studentsByFormation as $formation => $students)
                                    <optgroup label="📚 {{ $formation ?? 'Formation non définie' }}"
                                              style="font-weight: 600; color: var(--form-primary); font-size: 0.85rem; margin-top: 0.5rem;">
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}"
                                                    {{ in_array($student->id, $assignedStudentIds) ? 'selected' : '' }}
                                                    style="padding: 0.75rem; border-radius: 4px; margin: 2px 0;">
                                                {{ $student->first_name }} {{ $student->last_name }} · {{ $student->email }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('students')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text" style="color: var(--form-text);">
                                <i class="fas fa-lightbulb me-1"></i>
                                <strong>Astuce:</strong> Maintenez Ctrl (Windows) ou Cmd (Mac) pour sélectionner plusieurs étudiants
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne sidebar -->
            <div class="col-lg-4">
                <!-- Informations actuelles -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Informations Actuelles</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="mb-3 pb-3" style="border-bottom: 1px solid var(--form-border);">
                            <small style="color: var(--form-text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Formation</small>
                            <div style="color: var(--form-text); font-weight: 500; margin-top: 0.25rem;">
                                <i class="fas fa-graduation-cap me-2" style="color: var(--form-primary);"></i>
                                {{ $assignment->formation }}
                            </div>
                        </div>

                        <div class="mb-3 pb-3" style="border-bottom: 1px solid var(--form-border);">
                            <small style="color: var(--form-text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Assigné le</small>
                            <div style="color: var(--form-text); font-weight: 500; margin-top: 0.25rem;">
                                <i class="fas fa-calendar-plus me-2" style="color: var(--form-primary);"></i>
                                {{ \Carbon\Carbon::parse($assignment->created_at)->format('d/m/Y à H:i') }}
                            </div>
                        </div>

                        <div>
                            <small style="color: var(--form-text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px;">Statut</small>
                            <div style="margin-top: 0.25rem;">
                                @if($assignment->status === 'assigned')
                                    <span class="badge" style="background: rgba(148, 163, 184, 0.2); color: #94a3b8; padding: 0.5rem 1rem; font-size: 0.85rem;">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @elseif($assignment->status === 'submitted')
                                    <span class="badge" style="background: rgba(251, 191, 36, 0.2); color: #FBBF24; padding: 0.5rem 1rem; font-size: 0.85rem;">
                                        <i class="fas fa-upload me-1"></i>Soumis
                                    </span>
                                @elseif($assignment->status === 'validated')
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10B981; padding: 0.5rem 1rem; font-size: 0.85rem;">
                                        <i class="fas fa-check-circle me-1"></i>Validé
                                    </span>
                                @else
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #EF4444; padding: 0.5rem 1rem; font-size: 0.85rem;">
                                        <i class="fas fa-times-circle me-1"></i>Rejeté
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-bolt"></i>
                        <h3>Actions Rapides</h3>
                    </div>
                    <div class="form-card-body">
                        <button type="button" class="btn btn-sm w-100 mb-2"
                                style="background: rgba(15, 23, 42, 0.8); border: 1px solid var(--form-border); color: var(--form-text-muted); text-align: left;"
                                onclick="setCurrentDate()">
                            <i class="fas fa-clock me-2" style="color: var(--form-primary);"></i>
                            Utiliser la date actuelle
                        </button>
                        <button type="button" class="btn btn-sm w-100"
                                style="background: rgba(15, 23, 42, 0.8); border: 1px solid var(--form-border); color: var(--form-text-muted); text-align: left;"
                                onclick="addWeek()">
                            <i class="fas fa-calendar-plus me-2" style="color: var(--form-primary);"></i>
                            Ajouter 1 semaine
                        </button>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-chart-bar"></i>
                        <h3>Statistiques</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: rgba(56, 189, 248, 0.1); border: 1px solid var(--form-primary);">
                                    <div style="font-size: 2rem; font-weight: 700; color: var(--form-primary);">{{ $studentsCount }}</div>
                                    <small style="color: var(--form-text-muted); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">Étudiants</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: rgba(251, 191, 36, 0.1); border: 1px solid #FBBF24;">
                                    <div style="font-size: 2rem; font-weight: 700; color: #FBBF24;" id="daysLeftStat">--</div>
                                    <small style="color: var(--form-text-muted); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px;">Jours</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="form-footer mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div style="color: var(--form-text-muted); font-size: 0.9rem;">
                    <i class="fas fa-shield-check me-2" style="color: #10B981;"></i>
                    Les modifications seront appliquées immédiatement
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.travaux.assigned') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* Options du select sélectionnées */
    #students option:checked {
        background: linear-gradient(135deg, rgba(56, 189, 248, 0.3), rgba(56, 189, 248, 0.1)) !important;
        color: var(--form-primary) !important;
        font-weight: 600;
    }

    /* Hover effect sur les buttons des actions rapides */
    .btn:hover {
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    /* Alert personnalisée */
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border-color: rgba(239, 68, 68, 0.3);
        color: #EF4444;
    }

    .btn-close {
        filter: brightness(0) invert(1);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Compteur de caractères
    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');

    function updateCharCount() {
        charCount.textContent = description.value.length;
    }
    updateCharCount();
    description.addEventListener('input', updateCharCount);

    // Compteur d'étudiants sélectionnés
    const studentsSelect = document.getElementById('students');
    const selectedCount = document.getElementById('selectedCount');

    function updateSelectedCount() {
        const count = Array.from(studentsSelect.options).filter(opt => opt.selected).length;
        selectedCount.textContent = count;
    }
    studentsSelect.addEventListener('change', updateSelectedCount);

    // Calcul des jours restants
    const deadline = document.getElementById('deadline');
    const daysLeft = document.getElementById('daysLeft');
    const daysLeftStat = document.getElementById('daysLeftStat');

    function updateDaysLeft() {
        if (deadline.value) {
            const deadlineDate = new Date(deadline.value);
            const today = new Date();
            const diffTime = deadlineDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            const days = diffDays > 0 ? diffDays : 0;
            daysLeft.textContent = days;
            daysLeftStat.textContent = days;
        }
    }
    updateDaysLeft();
    deadline.addEventListener('change', updateDaysLeft);
});

// Fonctions utilitaires
function selectAll() {
    const select = document.getElementById('students');
    for (let option of select.options) {
        option.selected = true;
    }
    document.getElementById('selectedCount').textContent = select.options.length;
}

function deselectAll() {
    const select = document.getElementById('students');
    for (let option of select.options) {
        option.selected = false;
    }
    document.getElementById('selectedCount').textContent = 0;
}

function setCurrentDate() {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('deadline').value = now.toISOString().slice(0, 16);
    document.getElementById('deadline').dispatchEvent(new Event('change'));
}

function addWeek() {
    const deadlineInput = document.getElementById('deadline');
    const currentDate = deadlineInput.value ? new Date(deadlineInput.value) : new Date();
    currentDate.setDate(currentDate.getDate() + 7);
    currentDate.setMinutes(currentDate.getMinutes() - currentDate.getTimezoneOffset());
    deadlineInput.value = currentDate.toISOString().slice(0, 16);
    deadlineInput.dispatchEvent(new Event('change'));
}
</script>
@endsection
