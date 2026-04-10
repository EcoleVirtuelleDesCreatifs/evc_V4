@extends('layouts.admin')

@section('title', 'Modifier un Programme')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')

<div class="interactive-dashboard-form">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('programmes') }}" class="btn btn-secondary mb-3">
                <i class="fas fa-arrow-left me-2"></i>
                Retour à la liste
            </a>

            <h1 style="color: var(--form-text); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-edit me-3"></i>
                Modifier un Programme
            </h1>
            <p style="color: var(--form-text-muted); margin: 0;">
                Modifiez le programme et ses séances
            </p>
        </div>
    </div>

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

    <form action="{{ route('programmes.update', $programme->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Programme du mois</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label">
                                Titre du programme (mois) <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   class="form-control @error('titre') is-invalid @enderror"
                                   id="titre"
                                   name="titre"
                                   required
                                   value="{{ old('titre', $programme->titre) }}"
                                   placeholder="Ex: Programme - Janvier 2026">
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="month_start" class="form-label">
                                        Mois <span class="text-danger">*</span>
                                    </label>
                                    @php
                                        $monthStartValue = old('month_start');
                                        if (!$monthStartValue) {
                                            $ms = $programme->month_start ?? null;
                                            $monthStartValue = $ms ? \Carbon\Carbon::parse($ms)->format('Y-m') : '';
                                        }
                                    @endphp
                                    <input type="month"
                                           class="form-control @error('month_start') is-invalid @enderror"
                                           id="month_start"
                                           name="month_start"
                                           required
                                           value="{{ $monthStartValue }}">
                                    @error('month_start')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="image" class="form-label">
                                Image d’illustration (optionnel)
                            </label>
                            @if(property_exists($programme, 'image') && !empty($programme->image))
                                <div class="mb-2">
                                    <a href="{{ \App\Models\MediaUrl::fromPath($programme->image) }}" target="_blank" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-image me-1"></i>
                                        Voir l’image actuelle
                                    </a>
                                </div>
                            @endif
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
                                Laisser vide pour conserver l’image actuelle.
                            </small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="fichier_pdf" class="form-label">
                                PDF du programme (optionnel)
                            </label>
                            @if(!empty($programme->fichier_pdf))
                                <div class="mb-2">
                                    <a href="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}" target="_blank" class="btn btn-sm btn-secondary">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        Télécharger le PDF actuel
                                    </a>
                                </div>
                            @endif
                            <input type="file"
                                   class="form-control @error('fichier_pdf') is-invalid @enderror"
                                   id="fichier_pdf"
                                   name="fichier_pdf"
                                   accept=".pdf">
                            @error('fichier_pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Laisser vide pour conserver le PDF actuel.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">
                                Description (optionnel)
                            </label>
                            <textarea class="form-control"
                                      id="description"
                                      name="description"
                                      rows="5"
                                      placeholder="Décrivez brièvement le contenu du programme, les objectifs pédagogiques...">{{ old('description', $programme->description) }}</textarea>
                        </div>

                        <div class="form-card mt-4" style="background: rgba(15, 23, 42, 0.35);">
                            <div class="form-card-header">
                                <i class="fas fa-calendar-alt"></i>
                                <h3>Séances du mois</h3>
                            </div>
                            <div class="form-card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Modifiez les séances existantes ou ajoutez-en de nouvelles
                                    </small>
                                    <button type="button" class="btn btn-primary" id="addProgrammeItem">
                                        <i class="fas fa-plus me-2"></i>
                                        Ajouter une séance
                                    </button>
                                </div>

                                <div id="programmeItems">
                                    @php
                                        $oldItems = old('items');
                                        $hasOld = is_array($oldItems);
                                    @endphp

                                    @if($hasOld)
                                        @foreach($oldItems as $index => $it)
                                            @php
                                                $type = $it['type_formation'] ?? '';
                                                $isPresentielle = $type === 'presentielle';
                                            @endphp
                                            <div class="programme-item form-card mb-3" data-index="{{ $index }}">
                                                <div class="form-card-header" style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
                                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                                        <i class="fas fa-list"></i>
                                                        <h3 style="margin:0;">Séance</h3>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="form-card-body">
                                                    <input type="hidden" name="items[{{ $index }}][id]" value="{{ $it['id'] ?? '' }}">

                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Thématique <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="items[{{ $index }}][thematique]" required value="{{ $it['thematique'] ?? '' }}">
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Date <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="items[{{ $index }}][session_date]" required value="{{ $it['session_date'] ?? '' }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Heure <span class="text-danger">*</span></label>
                                                            <input type="time" class="form-control" name="items[{{ $index }}][session_time]" required value="{{ $it['session_time'] ?? '' }}">
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Type <span class="text-danger">*</span></label>
                                                            <select class="form-select item-type" name="items[{{ $index }}][type_formation]" required>
                                                                <option value="en_ligne" {{ $type === 'en_ligne' ? 'selected' : '' }}>En ligne</option>
                                                                <option value="presentielle" {{ $type === 'presentielle' ? 'selected' : '' }}>Présentielle</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 item-lieu-container" style="display: {{ $isPresentielle ? 'block' : 'none' }};">
                                                            <label class="form-label">Lieu <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control item-lieu" name="items[{{ $index }}][lieu]" value="{{ $it['lieu'] ?? '' }}" {{ $isPresentielle ? 'required' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Description (optionnel)</label>
                                                        <textarea class="form-control" name="items[{{ $index }}][description]" rows="3">{{ $it['description'] ?? '' }}</textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Pièce jointe (facultatif)</label>
                                                        <input type="file" class="form-control" name="items[{{ $index }}][piece_jointe]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        @foreach(($items ?? []) as $i => $it)
                                            @php
                                                $type = $it->type_formation ?? '';
                                                $isPresentielle = $type === 'presentielle';
                                                $filePath = $it->piece_jointe ?? null;
                                            @endphp
                                            <div class="programme-item form-card mb-3" data-index="{{ $i }}">
                                                <div class="form-card-header" style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
                                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                                        <i class="fas fa-list"></i>
                                                        <h3 style="margin:0;">Séance #{{ $i + 1 }}</h3>
                                                    </div>
                                                    <button type="button" class="btn btn-danger btn-sm remove-item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                                <div class="form-card-body">
                                                    <input type="hidden" name="items[{{ $i }}][id]" value="{{ $it->id }}">

                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Thématique <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="items[{{ $i }}][thematique]" required value="{{ old('items.' . $i . '.thematique', $it->thematique) }}">
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Date <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" name="items[{{ $i }}][session_date]" required value="{{ old('items.' . $i . '.session_date', $it->session_date) }}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Heure <span class="text-danger">*</span></label>
                                                            <input type="time" class="form-control" name="items[{{ $i }}][session_time]" required value="{{ old('items.' . $i . '.session_time', \Carbon\Carbon::parse($it->session_time)->format('H:i')) }}">
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mb-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Type <span class="text-danger">*</span></label>
                                                            <select class="form-select item-type" name="items[{{ $i }}][type_formation]" required>
                                                                <option value="en_ligne" {{ ($type === 'en_ligne') ? 'selected' : '' }}>En ligne</option>
                                                                <option value="presentielle" {{ ($type === 'presentielle') ? 'selected' : '' }}>Présentielle</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 item-lieu-container" style="display: {{ $isPresentielle ? 'block' : 'none' }};">
                                                            <label class="form-label">Lieu <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control item-lieu" name="items[{{ $i }}][lieu]" value="{{ old('items.' . $i . '.lieu', $it->lieu) }}" {{ $isPresentielle ? 'required' : '' }}>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Description (optionnel)</label>
                                                        <textarea class="form-control" name="items[{{ $i }}][description]" rows="3">{{ old('items.' . $i . '.description', $it->description) }}</textarea>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="form-label">Pièce jointe (facultatif)</label>
                                                        @if(!empty($filePath))
                                                            <div class="mb-2">
                                                                <a href="{{ \App\Models\MediaUrl::fromPath($filePath) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                                    <i class="fas fa-download me-1"></i>
                                                                    Télécharger la pièce jointe actuelle
                                                                </a>
                                                            </div>
                                                        @endif
                                                        <input type="file" class="form-control" name="items[{{ $i }}][piece_jointe]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>

                                @error('items')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-bullseye"></i>
                        <h3>Ciblage</h3>
                    </div>
                    <div class="form-card-body">
                        @php
                            $isTargeted = !empty($programme->student_ids);
                            $oldRecipientsMode = old('recipients_mode');
                            $recipientsMode = $oldRecipientsMode ?: ($isTargeted ? 'students' : 'formation');

                            $studentIds = [];
                            if (!empty($programme->student_ids)) {
                                $decoded = json_decode($programme->student_ids, true);
                                if (is_array($decoded)) {
                                    $studentIds = array_map('intval', $decoded);
                                }
                            }
                            $oldStudents = old('students');
                            if (!is_array($oldStudents)) {
                                $oldStudents = $oldStudents ? [$oldStudents] : [];
                            }
                            $oldStudents = !empty($oldStudents) ? array_map('intval', $oldStudents) : $studentIds;

                            $oldFormations = old('formation');
                            if (!is_array($oldFormations)) {
                                $oldFormations = $oldFormations ? [$oldFormations] : [];
                            }
                            if (empty($oldFormations)) {
                                $oldFormations = [$programme->formation];
                            }
                        @endphp

                        <div class="form-group mb-4">
                            <label class="form-label">
                                Destinataires <span class="text-danger">*</span>
                            </label>
                            <div class="mt-2">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="recipients_mode" id="recipients_mode_formation" value="formation" {{ $recipientsMode === 'formation' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="recipients_mode_formation">
                                        Formation
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="recipients_mode" id="recipients_mode_students" value="students" {{ $recipientsMode === 'students' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="recipients_mode_students">
                                        Étudiants spécifiques
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="formationContainer">
                            <label for="formation" class="form-label">
                                Formation destinataire <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('formation') is-invalid @enderror"
                                    id="formation"
                                    name="formation[]"
                                    multiple
                                    size="6">
                                <option value="Toutes" {{ in_array('Toutes', $oldFormations, true) ? 'selected' : '' }}>📚 Toutes les formations</option>
                                <option value="Design Graphique" {{ in_array('Design Graphique', $oldFormations, true) ? 'selected' : '' }}>🎨 Design Graphique</option>
                                <option value="Community Management" {{ in_array('Community Management', $oldFormations, true) ? 'selected' : '' }}>📱 Community Management</option>
                                <option value="Design Graphique & Community Manager" {{ in_array('Design Graphique & Community Manager', $oldFormations, true) ? 'selected' : '' }}>🎨📱 Design Graphique & Community Manager</option>
                                <option value="Gestion Informatique" {{ in_array('Gestion Informatique', $oldFormations, true) ? 'selected' : '' }}>💻 Gestion Informatique</option>
                                <option value="Intelligence Artificielle" {{ in_array('Intelligence Artificielle', $oldFormations, true) ? 'selected' : '' }}>🤖 Intelligence Artificielle</option>
                            </select>
                            @error('formation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                En édition, seule la première formation sélectionnée sera prise en compte.
                            </small>
                        </div>

                        <div class="form-group mt-4" id="studentsSelectContainer" style="display:none;">
                            <label for="students" class="form-label">
                                Étudiants spécifiques <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('students') is-invalid @enderror"
                                    id="students"
                                    name="students[]"
                                    multiple
                                    size="10">
                                @foreach(($students ?? []) as $student)
                                    <option value="{{ $student->id }}" {{ in_array((int) $student->id, $oldStudents, true) ? 'selected' : '' }}>
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
                            La pièce jointe est ajoutée <strong>par séance</strong> (pas au niveau du mois).
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.programmes') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>
                        Enregistrer les modifications
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
const itemsContainer = document.getElementById('programmeItems');
const addItemBtn = document.getElementById('addProgrammeItem');

function bindItemEvents(wrapper) {
    const typeSelect = wrapper.querySelector('.item-type');
    const lieuContainer = wrapper.querySelector('.item-lieu-container');
    const lieuInput = wrapper.querySelector('.item-lieu');

    function updateItemLieu() {
        const isPresentielle = (typeSelect && typeSelect.value === 'presentielle');
        if (lieuContainer) {
            lieuContainer.style.display = isPresentielle ? 'block' : 'none';
        }
        if (lieuInput) {
            lieuInput.required = !!isPresentielle;
            if (!isPresentielle) {
                lieuInput.value = '';
            }
        }
    }

    if (typeSelect) typeSelect.addEventListener('change', updateItemLieu);
    updateItemLieu();

    const removeBtn = wrapper.querySelector('.remove-item');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            wrapper.remove();
        });
    }
}

// Bind pour les séances existantes
if (itemsContainer) {
    Array.from(itemsContainer.querySelectorAll('.programme-item')).forEach(bindItemEvents);
}

function renderProgrammeItem(index) {
    const wrapper = document.createElement('div');
    wrapper.className = 'programme-item form-card mb-3';
    wrapper.setAttribute('data-index', index.toString());
    wrapper.innerHTML = `
        <div class="form-card-header" style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <i class="fas fa-list"></i>
                <h3 style="margin:0;">Séance</h3>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-item">
                <i class="fas fa-trash"></i>
            </button>
        </div>
        <div class="form-card-body">
            <input type="hidden" name="items[${index}][id]" value="">

            <div class="form-group mb-3">
                <label class="form-label">Thématique <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="items[${index}][thematique]" required placeholder="Ex: Initiation à Adobe Illustrator">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="items[${index}][session_date]" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Heure <span class="text-danger">*</span></label>
                    <input type="time" class="form-control" name="items[${index}][session_time]" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Type <span class="text-danger">*</span></label>
                    <select class="form-select item-type" name="items[${index}][type_formation]" required>
                        <option value="" disabled selected>Sélectionner...</option>
                        <option value="en_ligne">En ligne</option>
                        <option value="presentielle">Présentielle</option>
                    </select>
                </div>
                <div class="col-md-6 item-lieu-container" style="display:none;">
                    <label class="form-label">Lieu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control item-lieu" name="items[${index}][lieu]" placeholder="Ex: Salle 2 - Cocody / Abidjan">
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="form-label">Description (optionnel)</label>
                <textarea class="form-control" name="items[${index}][description]" rows="3" placeholder="Détails de la séance..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Pièce jointe (facultatif)</label>
                <input type="file" class="form-control" name="items[${index}][piece_jointe]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Formats: PDF, Images, Office • Taille max: 50 Mo
                </small>
            </div>
        </div>
    `;

    bindItemEvents(wrapper);
    return wrapper;
}

function addItem() {
    if (!itemsContainer) return;
    const index = Date.now();
    itemsContainer.appendChild(renderProgrammeItem(index));
}

if (addItemBtn) {
    addItemBtn.addEventListener('click', addItem);
}

// Ciblage: toggle formation vs students
const modeFormation = document.getElementById('recipients_mode_formation');
const modeStudents = document.getElementById('recipients_mode_students');
const formationSelect = document.getElementById('formation');
const studentsSelectContainer = document.getElementById('studentsSelectContainer');
const studentsSelect = document.getElementById('students');

function updateRecipientsModeUI() {
    const isStudents = modeStudents && modeStudents.checked;

    if (studentsSelectContainer) {
        studentsSelectContainer.style.display = isStudents ? 'block' : 'none';
    }

    if (studentsSelect) {
        studentsSelect.required = !!isStudents;
        if (!isStudents) {
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
</script>
@endpush
