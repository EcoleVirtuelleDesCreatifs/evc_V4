@extends('layouts.admin')

@section('title', 'Envoyer un Projet')

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

    #quill-editor {
        height: 300px;
    }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success mx-4 mt-4 mb-0">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger mx-4 mt-4 mb-0">
        {{ session('error') }}
    </div>
@endif

@if(session('errors_list') && is_array(session('errors_list')) && count(session('errors_list')))
    <div class="alert alert-warning mx-4 mt-3 mb-0">
        <strong>Détails des erreurs :</strong>
        <ul class="mb-0">
            @foreach(session('errors_list') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('emails_failures') && is_array(session('emails_failures')) && count(session('emails_failures')))
    <div class="alert alert-warning mx-4 mt-3 mb-0">
        <strong>Emails non envoyés :</strong>
        <ul class="mb-0">
            @foreach(session('emails_failures') as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form id="sendProjectForm" action="{{ route('admin.projets.send') }}" method="POST" class="interactive-dashboard-form" enctype="multipart/form-data">
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
                        <h2 class="stat-value" style="color: #26c6da;">{{ $stats['intelligence_artificielle'] ?? 0 }}</h2>
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
                <!-- Titre et Catégorie -->
                <div class="col-lg-8">
                    <div class="form-card h-100">
                        <div class="form-card-header">
                            <i class="fas fa-file-alt"></i>
                            <h3>Informations du Projet</h3>
                        </div>
                        <div class="form-card-body">
                            <!-- Titre -->
                            <div class="form-group">
                                <label for="title">
                                    Titre du Projet <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       required
                                       placeholder="Ex: Création d'une identité visuelle complète">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Catégorie -->
                            <div class="form-group">
                                <label for="category">
                                    Catégorie <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('category') is-invalid @enderror"
                                        id="category"
                                        name="category"
                                        required>
                                    <option value="">-- Sélectionner --</option>
                                    <option value="Design Graphique">🎨 Design Graphique</option>
                                    <option value="Branding">🏷️ Branding</option>
                                    <option value="UI/UX Design">📱 UI/UX Design</option>
                                    <option value="Social Media">📱 Social Media</option>
                                    <option value="Content Creation">✍️ Content Creation</option>
                                    <option value="Digital Marketing">📊 Digital Marketing</option>
                                    <option value="Web Design">🌐 Web Design</option>
                                    <option value="Motion Design">🎬 Motion Design</option>
                                    <option value="Autre">📂 Autre</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="form-group mb-0">
                                <label for="tags">
                                    Tags (optionnel)
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="tags"
                                       name="tags"
                                       value="{{ old('tags') }}"
                                       placeholder="Ex: logo, branding, print (séparés par des virgules)">
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Séparez les tags par des virgules
                                </small>
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
                                    <option value="all">📚 Toutes les formations</option>
                                    <option value="Design Graphique" {{ (old('formation') ?? ($defaultFormation ?? null)) === 'Design Graphique' ? 'selected' : '' }}>🎨 Design Graphique ({{ $stats['design_graphique'] }})</option>
                                    <option value="Design Graphique & Community Management" {{ (old('formation') ?? ($defaultFormation ?? null)) === 'Design Graphique & Community Management' ? 'selected' : '' }}>🎨📱 Design Graphique & Community Management ({{ $stats['design_graphique_cm'] ?? 0 }})</option>
                                    <option value="Community Management" {{ (old('formation') ?? ($defaultFormation ?? null)) === 'Community Management' ? 'selected' : '' }}>📱 Community Management ({{ $stats['community_management'] }})</option>
                                    <option value="Gestion Informatique" {{ (old('formation') ?? ($defaultFormation ?? null)) === 'Gestion Informatique' ? 'selected' : '' }}>💻 Gestion Informatique ({{ $stats['gestion_informatique'] }})</option>
                                    <option value="Intelligence Artificielle" {{ (old('formation') ?? ($defaultFormation ?? null)) === 'Intelligence Artificielle' ? 'selected' : '' }}>🤖 Intelligence Artificielle ({{ $stats['intelligence_artificielle'] ?? 0 }})</option>
                                    @if($stats['sans_formation'] > 0)
                                        <option value="Sans formation" {{ (old('formation') ?? ($defaultFormation ?? null)) === 'Sans formation' ? 'selected' : '' }}>❓ Sans formation ({{ $stats['sans_formation'] }})</option>
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
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" data-formation="{{ $student->program_normalized }}">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                            @if($student->email)
                                                ({{ $student->email }})
                                            @endif
                                            - {{ $student->program_normalized }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
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
                    <h3>Description du Projet</h3>
                </div>
                <div class="form-card-body">
                    <div class="form-group mb-0">
                        <label for="description">
                            Consignes et instructions <span class="text-danger">*</span>
                        </label>
                        <div id="quill-editor">{{ old('description') }}</div>
                        <input type="hidden" id="description" name="description" required>
                        @error('description')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                        <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                            <i class="fas fa-info-circle me-1"></i>
                            Utilisez l'éditeur pour formater votre texte (gras, italique, listes, liens, etc.)
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations complémentaires -->
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-header">
                    <i class="fas fa-info-circle"></i>
                    <h3>Informations Complémentaires</h3>
                </div>
                <div class="form-card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="deadline">
                                    Délai (deadline) <span class="text-danger">*</span>
                                </label>
                                <input type="date"
                                       class="form-control @error('deadline') is-invalid @enderror"
                                       id="deadline"
                                       name="deadline"
                                       value="{{ old('deadline') }}"
                                       required>
                                @error('deadline')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Date limite de rendu du projet
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="software_used">
                                    Logiciels à Utiliser
                                </label>
                                <input type="text"
                                       class="form-control"
                                       id="software_used"
                                       name="software_used"
                                       value="{{ old('software_used') }}"
                                       placeholder="Ex: Photoshop, Illustrator, Figma">
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Séparez par des virgules
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="reference_link">
                                    Lien de Référence
                                </label>
                                <input type="url"
                                       class="form-control"
                                       id="reference_link"
                                       name="reference_link"
                                       value="{{ old('reference_link') }}"
                                       placeholder="https://example.com/references">
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-link me-1"></i>
                                    Lien vers ressources, exemples ou brief
                                </small>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group mb-0">
                                <label for="attachments">Fichiers (images ou PDF)</label>
                                <input type="file"
                                       class="form-control @error('attachments') is-invalid @enderror @error('attachments.*') is-invalid @enderror"
                                       id="attachments"
                                       name="attachments[]"
                                       multiple
                                       accept="image/*,application/pdf">
                                @error('attachments')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @error('attachments.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2" style="color: #94a3b8 !important;">
                                    <i class="fas fa-paperclip me-1"></i>
                                    Vous pouvez joindre plusieurs fichiers (JPG/PNG/WebP/PDF)
                                </small>
                            </div>
                        </div>
                    </div>
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

                <button type="submit" class="btn btn-success">
                    <i class="fas fa-paper-plane me-2"></i>
                    Envoyer le Projet
                </button>
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
    placeholder: 'Décrivez les consignes du projet en détail...'
});

// Synchroniser avec le champ hidden
quill.on('text-change', function() {
    document.getElementById('description').value = quill.root.innerHTML;
});

document.getElementById('description').value = quill.root.innerHTML;

// Gestion de la sélection de formation
const formationSelect = document.getElementById('formation');
const studentsSelectContainer = document.getElementById('studentsSelectContainer');
const studentsSelect = document.getElementById('students');
const recipientsCount = document.getElementById('recipientsCount');

const stats = {
    'all': {{ $stats['total_students'] }},
    'Design Graphique': {{ $stats['design_graphique'] }},
    'Design Graphique & Community Management': {{ $stats['design_graphique_cm'] ?? 0 }},
    'Community Management': {{ $stats['community_management'] }},
    'Gestion Informatique': {{ $stats['gestion_informatique'] }},
    'Intelligence Artificielle': {{ $stats['intelligence_artificielle'] ?? 0 }},
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

if (formationSelect.value) {
    formationSelect.dispatchEvent(new Event('change'));
}

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
document.getElementById('sendProjectForm').addEventListener('submit', function(e) {
    // IMPORTANT: Synchroniser le contenu de Quill avec le champ hidden
    const quillContent = quill.root.innerHTML;
    document.getElementById('description').value = quillContent;

    // Vérifier que la description n'est pas vide
    const textContent = quill.getText().trim();
    if (!textContent || textContent.length === 0) {
        e.preventDefault();
        alert('⚠️ Veuillez remplir la description du projet');
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

    // Confirmation
    const selectedStudents = Array.from(studentsSelect.selectedOptions);
    let message = '';

    if (selectedStudents.length > 0) {
        message = `Envoyer ce projet à ${selectedStudents.length} étudiant(s) sélectionné(s) ?`;
    } else if (formation === 'all') {
        message = `Envoyer ce projet à TOUS les étudiants (${stats['all']} étudiants) ?`;
    } else {
        const count = stats[formation] || 0;
        message = `Envoyer ce projet à tous les étudiants de ${formation} (${count} étudiants) ?`;
    }

    if (!confirm(message)) {
        e.preventDefault();
        return false;
    }
});
</script>
@endpush
