@extends('layouts.admin')

@section('title', 'Ajouter un Programme')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')

<div class="interactive-dashboard-form">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('admin.programmes') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>
                Retour à la liste
            </a>

            <h1 style="color: var(--form-text); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-plus-circle me-3"></i>
                Ajouter un Programme
            </h1>
            <p style="color: var(--form-text-muted); margin: 0;">
                Créez le programme du mois avec plusieurs séances
            </p>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>Erreur :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('admin.programmes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Programme du mois</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Titre -->
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label">
                                Titre du programme (mois) <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('titre') is-invalid @enderror"
                                   id="titre"
                                   name="titre"
                                   required
                                   value="{{ old('titre') }}"
                                   placeholder="Ex: Programme - Janvier 2026">
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="image" class="form-label">
                                Image d’illustration (optionnel)
                            </label>
                            <input type="file"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   name="image"
                                   accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Formats: JPG, PNG, WEBP • Taille max: 5 Mo
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="programme_pdf" class="form-label">
                                PDF du programme du mois <span class="text-danger">*</span>
                            </label>
                            <input type="file"
                                   class="form-control @error('programme_pdf') is-invalid @enderror"
                                   id="programme_pdf"
                                   name="programme_pdf"
                                   accept="application/pdf"
                                   required>
                            @error('programme_pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Format: PDF • Taille max: 50 Mo
                            </small>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="month_start" class="form-label">
                                        Mois <span class="text-danger">*</span>
                                    </label>
                                    <input type="month"
                                           class="form-control @error('month_start') is-invalid @enderror"
                                           id="month_start"
                                           name="month_start"
                                           required
                                           value="{{ old('month_start') }}">
                                    @error('month_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Ce programme regroupera toutes les séances de ce mois
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="form-label">
                                Description (optionnel)
                            </label>
                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      rows="5"
                                      placeholder="Décrivez brièvement le contenu du programme, les objectifs pédagogiques...">{{ old('description') }}</textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Cette description sera visible par les étudiants
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ciblage et fichier -->
            <div class="col-lg-4">
                <!-- Formation destinataire -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-bullseye"></i>
                        <h3>Ciblage</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group mb-4">
                            <label class="form-label">
                                Destinataires <span class="text-danger">*</span>
                            </label>
                            <div class="mt-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="recipients_mode" id="recipients_mode_formation" value="formation" {{ old('recipients_mode', 'formation') === 'formation' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="recipients_mode_formation">
                                        Formation
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="recipients_mode" id="recipients_mode_students" value="students" {{ old('recipients_mode') === 'students' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="recipients_mode_students">
                                        Étudiants spécifiques
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="formation" class="form-label">
                                Formation destinataire <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('formation') is-invalid @enderror"
                                    id="formation"
                                    name="formation[]"
                                    multiple
                                    size="6"
                                    required>
                                @php
                                    $oldFormations = old('formation');
                                    if (!is_array($oldFormations)) {
                                        $oldFormations = $oldFormations ? [$oldFormations] : [];
                                    }
                                @endphp
                                <option value="Toutes" {{ in_array('Toutes', $oldFormations, true) ? 'selected' : '' }}>
                                    📚 Toutes les formations
                                </option>
                                <option value="Design Graphique" {{ in_array('Design Graphique', $oldFormations, true) ? 'selected' : '' }}>
                                    🎨 Design Graphique
                                </option>
                                <option value="Community Management" {{ in_array('Community Management', $oldFormations, true) ? 'selected' : '' }}>
                                    📱 Community Management
                                </option>
                                <option value="Design Graphique & Community Manager" {{ in_array('Design Graphique & Community Manager', $oldFormations, true) ? 'selected' : '' }}>
                                    🎨📱 Design Graphique & Community Manager
                                </option>
                                <option value="Gestion Informatique" {{ in_array('Gestion Informatique', $oldFormations, true) ? 'selected' : '' }}>
                                    💻 Gestion Informatique
                                </option>
                                <option value="Intelligence Artificielle" {{ in_array('Intelligence Artificielle', $oldFormations, true) ? 'selected' : '' }}>
                                    🤖 Intelligence Artificielle
                                </option>
                            </select>
                            @error('formation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Maintenez Ctrl/Cmd pour sélectionner plusieurs formations
                            </small>
                        </div>

                        <div class="form-group mt-4" id="studentsSelectContainer" style="display: none;">
                            <label for="students" class="form-label">
                                Étudiants spécifiques <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('students') is-invalid @enderror"
                                    id="students"
                                    name="students[]"
                                    multiple
                                    size="10">
                                @php
                                    $oldStudents = old('students');
                                    if (!is_array($oldStudents)) {
                                        $oldStudents = $oldStudents ? [$oldStudents] : [];
                                    }
                                @endphp
                                @foreach(($students ?? []) as $student)
                                    <option value="{{ $student->id }}" {{ in_array($student->id, $oldStudents, false) ? 'selected' : '' }}>
                                        {{ $student->first_name }} {{ $student->last_name }}
                                        @if($student->email)
                                            ({{ $student->email }})
                                        @endif
                                        @if($student->program)
                                            - {{ $student->program }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('students')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Maintenez Ctrl/Cmd pour sélectionner plusieurs étudiants
                            </small>
                        </div>
                    </div>
                </div>

                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Rappel</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-paperclip me-2"></i>
                            Le PDF est ajouté <strong>au niveau du mois</strong> et sera visible par les étudiants.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.programmes') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer le programme
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
const modeFormation = document.getElementById('recipients_mode_formation');
const modeStudents = document.getElementById('recipients_mode_students');
const studentsSelectContainer = document.getElementById('studentsSelectContainer');
const studentsSelect = document.getElementById('students');
const formationSelect = document.getElementById('formation');

function updateRecipientsModeUI() {
    const isStudents = modeStudents && modeStudents.checked;
    if (studentsSelectContainer) {
        studentsSelectContainer.style.display = isStudents ? 'block' : 'none';
    }
    if (studentsSelect) {
        studentsSelect.required = !!isStudents;
        if (!isStudents) {
            // vider la sélection si on repasse en mode formation
            Array.from(studentsSelect.options).forEach(o => { o.selected = false; });
        }
    }
    if (formationSelect) {
        formationSelect.required = !isStudents;
        if (isStudents) {
            Array.from(formationSelect.options).forEach(o => { o.selected = false; });
        }
    }
}

if (modeFormation) modeFormation.addEventListener('change', updateRecipientsModeUI);
if (modeStudents) modeStudents.addEventListener('change', updateRecipientsModeUI);
updateRecipientsModeUI();

// Si "Toutes" est sélectionné, on désélectionne les autres formations
if (formationSelect) {
    formationSelect.addEventListener('change', function() {
        const selectedValues = Array.from(this.selectedOptions).map(o => o.value);
        if (selectedValues.includes('Toutes') && selectedValues.length > 1) {
            Array.from(this.options).forEach(opt => {
                opt.selected = (opt.value === 'Toutes');
            });
        }
    });
}

// Validation du formulaire
document.querySelector('form[action="{{ route('admin.programmes.store') }}"]').addEventListener('submit', function(e) {
    const isStudents = modeStudents && modeStudents.checked;
    if (isStudents) {
        const selectedStudents = studentsSelect ? Array.from(studentsSelect.selectedOptions) : [];
        if (!selectedStudents.length) {
            e.preventDefault();
            alert('⚠️ Veuillez sélectionner au moins un étudiant');
            studentsSelect && studentsSelect.focus();
            return false;
        }
    } else {
        const selectedFormations = formationSelect ? Array.from(formationSelect.selectedOptions).map(o => o.value).filter(Boolean) : [];
        if (!selectedFormations.length) {
            e.preventDefault();
            alert('⚠️ Veuillez sélectionner au moins une formation');
            formationSelect && formationSelect.focus();
            return false;
        }
    }
});
</script>
@endpush
