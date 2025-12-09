@extends('layouts.admin')

@section('title', 'Envoyer des TP aux Étudiants')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    /* Statistiques en haut */
    .stats-row {
        margin-bottom: 2rem;
    }

    .stat-card {
        background: var(--form-surface);
        border: 1px solid var(--form-border);
        border-radius: 16px;
        padding: 1.5rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        height: 100%;
    }

    .stat-card:hover {
        border-color: var(--form-primary);
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(56, 189, 248, 0.2);
    }

    .stat-card .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }

    .stat-card .stat-title {
        font-size: 0.875rem;
        color: var(--form-text-muted);
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--form-text);
        margin-bottom: 0;
    }

    .stat-card .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card .stat-footer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: var(--form-text-muted);
        margin-top: 0.75rem;
    }

    /* Upload Zone */
    .upload-zone {
        border: 2px dashed var(--form-border);
        border-radius: 12px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: rgba(15, 23, 42, 0.5);
    }

    .upload-zone:hover {
        border-color: var(--form-primary);
        background: rgba(56, 189, 248, 0.1);
    }

    .upload-zone.drag-over {
        border-color: var(--form-primary);
        background: rgba(56, 189, 248, 0.2);
        border-style: solid;
    }

    .upload-icon {
        font-size: 3rem;
        color: var(--form-primary);
        margin-bottom: 1rem;
    }

    .upload-zone h5 {
        color: var(--form-text);
        margin-bottom: 0.5rem;
    }

    .upload-zone p {
        color: var(--form-text-muted);
        margin-bottom: 1rem;
    }

    .upload-info {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid var(--form-border);
        font-size: 0.875rem;
        color: var(--form-text-muted);
    }

    /* Files Preview */
    .file-item {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid var(--form-border);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .file-item:hover {
        border-color: var(--form-primary);
        transform: translateX(5px);
    }

    .file-icon {
        font-size: 2rem;
        min-width: 40px;
        text-align: center;
    }

    .file-icon.image { color: #10b981; }
    .file-icon.pdf { color: #ef4444; }

    .file-info {
        flex-grow: 1;
    }

    .file-name {
        color: var(--form-text);
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .file-size {
        color: var(--form-text-muted);
        font-size: 0.875rem;
    }

    .file-remove {
        color: #ef4444;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .file-remove:hover {
        transform: scale(1.2);
    }

    /* Recipients Info */
    .recipients-info {
        background: rgba(56, 189, 248, 0.1);
        border: 1px solid rgba(56, 189, 248, 0.3);
        border-radius: 10px;
        padding: 1rem;
        margin-top: 1rem;
    }

    .recipients-info i {
        color: var(--form-primary);
    }

    .recipients-info strong {
        color: var(--form-text);
    }

    /* Badge personnalisé */
    .badge-custom {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    #quill-editor {
        height: 300px;
    }
</style>
@endpush

@section('content')

<!-- BANDEAU DE DÉBOGAGE (TEMPORAIRE) -->
<form id="sendTpForm" action="{{ route('admin.travaux.send') }}" method="POST" enctype="multipart/form-data" class="interactive-dashboard-form">
    @csrf

    <!-- Statistiques par formation -->
    <div class="row g-4 stats-row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Design Graphique</div>
                        <h2 class="stat-value" style="color: #1e3c72;">{{ $stats['design_graphique'] }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Community Management</div>
                        <h2 class="stat-value" style="color: #4fc3f7;">{{ $stats['community_management'] }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4fc3f7, #29b6f6);">
                        <i class="fas fa-share-alt"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Gestion Informatique</div>
                        <h2 class="stat-value" style="color: #ff9800;">{{ $stats['gestion_informatique'] }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ff9800, #fb8c00);">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Intelligence Artificielle</div>
                        <h2 class="stat-value" style="color: #26c6da;">{{ $stats['intelligence_artificielle'] }}</h2>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #26c6da, #00acc1);">
                        <i class="fas fa-robot"></i>
                    </div>
                </div>
                <div class="stat-footer">
                    <i class="fas fa-user-check"></i>
                    <span>Étudiants actifs</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Informations principales -->
        <div class="col-12">
            <div class="row g-4">
                <!-- Titre et Description -->
                <div class="col-lg-8">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-file-alt"></i>
                            <h3>Informations du TP</h3>
                        </div>
                        <div class="form-card-body">
                            <!-- Titre -->
                            <div class="form-group">
                                <label for="tp_title">
                                    Titre du TP <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('tp_title') is-invalid @enderror"
                                       id="tp_title"
                                       name="tp_title"
                                       value="{{ old('tp_title') }}"
                                       required
                                       placeholder="Ex: Créer une affiche publicitaire">
                                @error('tp_title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date limite -->
                            <div class="form-group">
                                <label for="tp_deadline">
                                    Date limite de rendu <span class="text-danger">*</span>
                                </label>
                                <input type="datetime-local"
                                       class="form-control @error('tp_deadline') is-invalid @enderror"
                                       id="tp_deadline"
                                       name="tp_deadline"
                                       value="{{ old('tp_deadline') }}"
                                       required>
                                @error('tp_deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ciblage -->
                <div class="col-lg-4">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-bullseye"></i>
                            <h3>Ciblage</h3>
                        </div>
                        <div class="form-card-body">
                            <!-- Sélection formation -->
                            <div class="form-group">
                                <label for="formation">
                                    Formation cible <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('formation') is-invalid @enderror"
                                        id="formation"
                                        name="formation"
                                        required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="all">📚 Toutes les formations ({{ $stats['total_students'] }})</option>
                                    <option value="Design Graphique">🎨 Design Graphique ({{ $stats['design_graphique'] }})</option>
                                    <option value="Community Management">📱 Community Management ({{ $stats['community_management'] }})</option>
                                    <option value="Gestion Informatique">💻 Gestion Informatique ({{ $stats['gestion_informatique'] }})</option>
                                    <option value="Intelligence Artificielle">🤖 Intelligence Artificielle ({{ $stats['intelligence_artificielle'] }})</option>
                                    @if(isset($stats['sans_formation']) && $stats['sans_formation'] > 0)
                                        <option value="Sans formation">❓ Sans formation ({{ $stats['sans_formation'] }})</option>
                                    @endif
                                </select>
                                @error('formation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sélection étudiants spécifiques -->
                            <div class="form-group" id="studentsSelectContainer" style="display: none;">
                                <label for="students">
                                    Étudiants spécifiques
                                </label>
                                <select class="form-select"
                                        id="students"
                                        name="students[]"
                                        multiple
                                        size="10">
                                    @foreach($all_students as $student)
                                        <option value="{{ $student->id }}" data-formation="{{ $student->formation_normalized }}">
                                            {{ $student->prenom }} {{ $student->nom }}
                                            @if($student->user_email)
                                                ({{ $student->user_email }})
                                            @endif
                                            - {{ $student->formation }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Maintenez Ctrl/Cmd pour sélectionner plusieurs étudiants
                                </small>
                            </div>

                            <!-- Info destinataires -->
                            <div class="recipients-info" id="recipientsInfo">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong id="recipientsCount">Sélectionnez une formation</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description détaillée -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-align-left"></i>
                    <h3>Description du TP</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group mb-0">
                        <label for="tp_description">
                            Consignes et instructions <span class="text-danger">*</span>
                        </label>
                        <div id="quill-editor">{{ old('tp_description') }}</div>
                        <input type="hidden" id="tp_description" name="tp_description" required>
                        @error('tp_description')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Utilisez l'éditeur pour formater votre texte (gras, italique, listes, liens, etc.)
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fichiers joints -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-paperclip"></i>
                    <h3>Fichiers joints</h3>
                    <span class="badge-custom" style="background: rgba(56, 189, 248, 0.2); color: var(--form-primary); margin-left: auto;" id="filesCount">
                        <i class="fas fa-file"></i>
                        <span>0 fichier</span>
                    </span>
                </div>
                <div class="form-card-body">
                    <!-- Zone de drag & drop -->
                    <div class="upload-zone" id="dropZone">
                        <div class="upload-icon">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <h5>Glissez-déposez vos fichiers ici</h5>
                        <p>ou</p>
                        <button type="button" class="btn btn-primary btn-sm" id="selectFilesBtn">
                            <i class="fas fa-folder-open me-2"></i>
                            Parcourir les fichiers
                        </button>
                        <input type="file"
                               id="tp_files"
                               name="tp_files[]"
                               multiple
                               accept="image/*,.pdf"
                               class="d-none">

                        <div class="upload-info">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Formats :</strong> JPG, PNG, GIF, PDF
                            <span class="mx-2">•</span>
                            <strong>Taille max :</strong> 5 Mo/fichier
                            <span class="mx-2">•</span>
                            <strong>Maximum :</strong> 10 fichiers
                        </div>
                    </div>

                    <!-- Aperçu des fichiers -->
                    <div id="filesPreview" class="mt-3"></div>

                    @error('tp_files')
                        <div class="alert alert-danger mt-2">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Footer avec boutons -->
        <div class="col-12">
            <div class="form-footer d-flex justify-content-between align-items-center">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Annuler
                </a>

                <div class="d-flex gap-3">
                    <button type="button" class="btn btn-warning" id="previewBtn">
                        <i class="fas fa-eye me-2"></i>
                        Aperçu
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-2"></i>
                        Envoyer le TP
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
// Initialiser Quill
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'color': [] }, { 'background': [] }],
            ['link', 'image'],
            ['clean']
        ]
    },
    placeholder: 'Décrivez les consignes du TP en détail...'
});

// Synchroniser avec le champ hidden
quill.on('text-change', function() {
    document.getElementById('tp_description').value = quill.root.innerHTML;
});

// Gestion des fichiers
let selectedFiles = [];
const maxFiles = 10;
const maxFileSize = 5 * 1024 * 1024; // 5 MB

const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('tp_files');
const selectFilesBtn = document.getElementById('selectFilesBtn');
const filesPreview = document.getElementById('filesPreview');
const filesCount = document.getElementById('filesCount');

selectFilesBtn.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', function(e) {
    handleFiles(this.files);
});

// Drag & Drop
dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('drag-over');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('drag-over');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    handleFiles(e.dataTransfer.files);
});

function handleFiles(files) {
    const filesArray = Array.from(files);

    // Vérifier le nombre de fichiers
    if (selectedFiles.length + filesArray.length > maxFiles) {
        alert(`⚠️ Vous ne pouvez sélectionner que ${maxFiles} fichiers maximum`);
        return;
    }

    filesArray.forEach(file => {
        // Vérifier la taille
        if (file.size > maxFileSize) {
            alert(`⚠️ ${file.name} est trop volumineux (max 5 Mo)`);
            return;
        }

        // Vérifier le type
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
        if (!validTypes.includes(file.type)) {
            alert(`⚠️ ${file.name} n'est pas un format valide`);
            return;
        }

        selectedFiles.push(file);
    });

    updateFilesPreview();
    updateFilesCount();
}

function updateFilesPreview() {
    filesPreview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';

        const icon = file.type.startsWith('image/') ? 'fa-file-image image' : 'fa-file-pdf pdf';
        const size = (file.size / 1024).toFixed(1);

        fileItem.innerHTML = `
            <div class="file-icon ${icon.split(' ')[1]}">
                <i class="fas ${icon.split(' ')[0]}"></i>
            </div>
            <div class="file-info">
                <div class="file-name">${file.name}</div>
                <div class="file-size">${size} Ko</div>
            </div>
            <div class="file-remove" onclick="removeFile(${index})">
                <i class="fas fa-times-circle fa-lg"></i>
            </div>
        `;

        filesPreview.appendChild(fileItem);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    updateFilesPreview();
    updateFilesCount();
}

function updateFilesCount() {
    const count = selectedFiles.length;
    filesCount.innerHTML = `
        <i class="fas fa-file"></i>
        <span>${count} fichier${count > 1 ? 's' : ''}</span>
    `;
}

// Gestion de la sélection de formation
const formationSelect = document.getElementById('formation');
const studentsSelectContainer = document.getElementById('studentsSelectContainer');
const studentsSelect = document.getElementById('students');
const recipientsCount = document.getElementById('recipientsCount');

const stats = {
    'all': {{ $stats['total_students'] }},
    'Design Graphique': {{ $stats['design_graphique'] }},
    'Community Management': {{ $stats['community_management'] }},
    'Gestion Informatique': {{ $stats['gestion_informatique'] }},
    'Intelligence Artificielle': {{ $stats['intelligence_artificielle'] }},
    'Sans formation': {{ $stats['sans_formation'] ?? 0 }}
};

formationSelect.addEventListener('change', function() {
    const selectedFormation = this.value;

    if (selectedFormation && selectedFormation !== 'all') {
        // Afficher le sélecteur d'étudiants
        studentsSelectContainer.style.display = 'block';

        // Filtrer les options
        const options = studentsSelect.querySelectorAll('option');
        options.forEach(option => {
            const optionFormation = option.getAttribute('data-formation');
            if (optionFormation === selectedFormation || !optionFormation) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
                option.selected = false;
            }
        });

        updateRecipientsCount();
    } else if (selectedFormation === 'all') {
        studentsSelectContainer.style.display = 'none';
        recipientsCount.textContent = `Tous les étudiants (${stats['all']} étudiants)`;
    } else {
        studentsSelectContainer.style.display = 'none';
        recipientsCount.textContent = 'Sélectionnez une formation';
    }
});

studentsSelect.addEventListener('change', function() {
    updateRecipientsCount();
});

function updateRecipientsCount() {
    const selectedFormation = formationSelect.value;
    const selectedStudents = Array.from(studentsSelect.selectedOptions);

    if (selectedStudents.length > 0) {
        recipientsCount.textContent = `${selectedStudents.length} étudiant(s) sélectionné(s)`;
    } else if (selectedFormation && selectedFormation !== 'all') {
        const count = stats[selectedFormation] || 0;
        recipientsCount.textContent = `Tous les étudiants de ${selectedFormation} (${count} étudiants)`;
    }
}

// Validation du formulaire
document.getElementById('sendTpForm').addEventListener('submit', function(e) {
    // IMPORTANT: Synchroniser le contenu de Quill avec le champ hidden
    const quillContent = quill.root.innerHTML;
    document.getElementById('tp_description').value = quillContent;

    console.log('Contenu Quill:', quillContent);
    console.log('Valeur champ hidden:', document.getElementById('tp_description').value);

    // Vérifier que la description n'est pas vide
    const textContent = quill.getText().trim();
    if (!textContent || textContent.length === 0) {
        e.preventDefault();
        alert('⚠️ Veuillez remplir la description du TP');
        quill.focus();
        return false;
    }

    const formation = formationSelect.value;
    if (!formation) {
        e.preventDefault();
        alert('⚠️ Veuillez sélectionner une formation');
        formationSelect.focus();
        return false;
    }

    // Synchroniser les fichiers avec le formulaire
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => {
        dataTransfer.items.add(file);
    });
    fileInput.files = dataTransfer.files;

    // Confirmation
    const selectedStudents = Array.from(studentsSelect.selectedOptions);
    let message = '';

    if (selectedStudents.length > 0) {
        message = `Envoyer ce TP à ${selectedStudents.length} étudiant(s) sélectionné(s) ?`;
    } else if (formation === 'all') {
        message = `Envoyer ce TP à TOUS les étudiants (${stats['all']} étudiants) ?`;
    } else {
        const count = stats[formation] || 0;
        message = `Envoyer ce TP à tous les étudiants de ${formation} (${count} étudiants) ?`;
    }

    if (!confirm(message)) {
        e.preventDefault();
        return false;
    }
});

// Bouton aperçu
document.getElementById('previewBtn').addEventListener('click', function() {
    const title = document.getElementById('tp_title').value;
    const description = quill.root.innerHTML;
    const deadline = document.getElementById('tp_deadline').value;

    if (!title || !description || !deadline) {
        alert('⚠️ Veuillez remplir tous les champs obligatoires');
        return;
    }

    // Créer une modale d'aperçu (simplifiée)
    alert(`Aperçu du TP:\n\nTitre: ${title}\nDate limite: ${deadline}\nNombre de fichiers: ${selectedFiles.length}`);
});
</script>
@endpush
