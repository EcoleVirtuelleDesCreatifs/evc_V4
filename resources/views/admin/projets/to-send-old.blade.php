@extends('layouts.admin')

@section('title', 'Envoyer un Projet')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')

<div class="interactive-dashboard-form">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 style="color: var(--form-text); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-project-diagram me-3"></i>
                Envoyer un Projet aux Étudiants
            </h1>
            <p style="color: var(--form-text-muted); margin: 0;">
                Assignez un nouveau projet à vos étudiants
            </p>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_students'] }}</div>
                    <div class="stat-label">Total Étudiants</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4fc3f7, #29b6f6);">
                <div class="stat-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['design_graphique'] }}</div>
                    <div class="stat-label">Design Graphique</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800, #fb8c00);">
                <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['community_management'] }}</div>
                    <div class="stat-label">Community Management</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #26c6da, #00acc1);">
                <div class="stat-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['gestion_informatique'] }}</div>
                    <div class="stat-label">Gestion Informatique</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form action="{{ route('admin.projets.send') }}" method="POST" class="form-modern">
        @csrf

        <div class="row g-4">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations du projet -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle me-2"></i>
                        <h3 class="mb-0">Informations du Projet</h3>
                    </div>
                    <div class="form-card-body">
                        <!-- Titre -->
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="fas fa-heading me-2"></i>Titre du Projet <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="title" name="title"
                                   placeholder="Ex: Création d'une identité visuelle complète" required>
                        </div>

                        <!-- Catégorie -->
                        <div class="mb-4">
                            <label for="category" class="form-label">
                                <i class="fas fa-tag me-2"></i>Catégorie <span class="text-danger">*</span>
                            </label>
                            <select class="form-control" id="category" name="category" required>
                                <option value="">Sélectionner une catégorie</option>
                                <option value="Design Graphique">Design Graphique</option>
                                <option value="Branding">Branding</option>
                                <option value="UI/UX Design">UI/UX Design</option>
                                <option value="Social Media">Social Media</option>
                                <option value="Content Creation">Content Creation</option>
                                <option value="Digital Marketing">Digital Marketing</option>
                                <option value="Web Design">Web Design</option>
                                <option value="Motion Design">Motion Design</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="description" name="description" rows="6"
                                      placeholder="Décrivez en détail le projet à réaliser..." required></textarea>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Incluez les objectifs, livrables attendus et critères d'évaluation
                            </small>
                        </div>

                        <!-- Tags -->
                        <div class="mb-4">
                            <label for="tags" class="form-label">
                                <i class="fas fa-tags me-2"></i>Tags (optionnel)
                            </label>
                            <input type="text" class="form-control" id="tags" name="tags"
                                   placeholder="Ex: logo, branding, print (séparés par des virgules)">
                        </div>

                        <!-- Logiciels requis -->
                        <div class="mb-4">
                            <label for="software_used" class="form-label">
                                <i class="fas fa-laptop-code me-2"></i>Logiciels à Utiliser (optionnel)
                            </label>
                            <input type="text" class="form-control" id="software_used" name="software_used"
                                   placeholder="Ex: Photoshop, Illustrator, Figma (séparés par des virgules)">
                        </div>

                        <!-- Lien de référence -->
                        <div class="mb-0">
                            <label for="reference_link" class="form-label">
                                <i class="fas fa-link me-2"></i>Lien de Référence (optionnel)
                            </label>
                            <input type="url" class="form-control" id="reference_link" name="reference_link"
                                   placeholder="https://example.com/references">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Lien vers des ressources, exemples ou brief détaillé
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <!-- Sélection des étudiants -->
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-users me-2"></i>
                        <h3 class="mb-0">Destinataires</h3>
                    </div>
                    <div class="form-card-body">
                        <label for="students" class="form-label">
                            Étudiants <span class="text-danger">*</span>
                        </label>

                        <!-- Toolbar de sélection -->
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAll()">
                                <i class="fas fa-check-double me-1"></i>Tout sélectionner
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deselectAll()">
                                <i class="fas fa-times me-1"></i>Tout désélectionner
                            </button>
                        </div>

                        <select class="form-control" id="students" name="students[]" multiple size="15" required>
                            @php
                                $studentsByFormation = $students->groupBy('program');
                            @endphp

                            @foreach($studentsByFormation as $formation => $formationStudents)
                                <optgroup label="📚 {{ $formation ?? 'Sans formation' }}">
                                    @foreach($formationStudents as $student)
                                        <option value="{{ $student->id }}">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                            @if($student->email)
                                                ({{ $student->email }})
                                            @endif
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>

                        <!-- Compteur -->
                        <div class="mt-3">
                            <div class="alert alert-info mb-0" style="border-radius: 12px; border-left: 4px solid #38bdf8;">
                                <i class="fas fa-info-circle me-2"></i>
                                <span id="selectedCount">0</span> étudiant(s) sélectionné(s)
                            </div>
                        </div>

                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-lightbulb me-1"></i>
                            Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs étudiants
                        </small>
                    </div>
                </div>

                <!-- Actions -->
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-cog me-2"></i>
                        <h3 class="mb-0">Actions</h3>
                    </div>
                    <div class="form-card-body">
                        <button type="submit" class="btn btn-success w-100 mb-3">
                            <i class="fas fa-paper-plane me-2"></i>
                            Envoyer le Projet
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Compteur de sélection
function updateSelectedCount() {
    const select = document.getElementById('students');
    const count = select.selectedOptions.length;
    document.getElementById('selectedCount').textContent = count;
}

// Sélectionner tous les étudiants
function selectAll() {
    const select = document.getElementById('students');
    for (let i = 0; i < select.options.length; i++) {
        select.options[i].selected = true;
    }
    updateSelectedCount();
}

// Désélectionner tous les étudiants
function deselectAll() {
    const select = document.getElementById('students');
    for (let i = 0; i < select.options.length; i++) {
        select.options[i].selected = false;
    }
    updateSelectedCount();
}

// Mise à jour du compteur lors du changement
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('students');
    select.addEventListener('change', updateSelectedCount);
    updateSelectedCount();
});
</script>

@endsection

@push('styles')
<style>
    .stat-card {
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }
</style>
@endpush
