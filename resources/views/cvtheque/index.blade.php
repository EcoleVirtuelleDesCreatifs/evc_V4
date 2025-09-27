@extends('layouts.ki-admin')

@section('title', 'CVThèque - EVC 2024')
@section('page-title', 'CVThèque')

@push('styles')
<!-- Animate.css pour les animations de la modale -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<!-- En-tête de la CVThèque -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #003366, #0066cc); color: white;">
            <div class="card-body text-center py-4">
                <i class="fas fa-briefcase fa-3x mb-3"></i>
                <h3 class="mb-2">Votre CVThèque Professionnelle</h3>
                <p class="mb-0">Gérez vos documents professionnels et présentez votre profil aux employeurs</p>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques des documents -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100 text-center" style="background: linear-gradient(135deg, #003366, #0066cc); color: white;">
            <div class="card-body">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <h5 class="mb-1">CV</h5>
                <small id="cv-status">Non ajouté</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100 text-center" style="background: linear-gradient(135deg, #3399ff, #66b3ff); color: white;">
            <div class="card-body">
                <i class="fas fa-envelope fa-2x mb-2"></i>
                <h5 class="mb-1">Motivation</h5>
                <small id="motivation-status">Non ajoutée</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100 text-center" style="background: linear-gradient(135deg, #ff6633, #ff9966); color: white;">
            <div class="card-body">
                <i class="fas fa-palette fa-2x mb-2"></i>
                <h5 class="mb-1">Réalisations</h5>
                <small id="realisations-count">0 fichier(s)</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100 text-center" style="background: linear-gradient(135deg, #FF9900, #ffb84d); color: white;">
            <div class="card-body">
                <i class="fas fa-book fa-2x mb-2"></i>
                <h5 class="mb-1">Pressbook</h5>
                <small id="pressbook-status">Non ajouté</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100 text-center" style="background: linear-gradient(135deg, #28a745, #5cbf2a); color: white;">
            <div class="card-body">
                <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                <h5 class="mb-1">Rapport</h5>
                <small id="rapport-status">Non ajouté</small>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card h-100 text-center" style="background: linear-gradient(135deg, #6f42c1, #8a63d2); color: white;">
            <div class="card-body">
                <i class="fas fa-chart-line fa-2x mb-2"></i>
                <h5 class="mb-1">Profil</h5>
                <small>85% complet</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Section de téléchargement des documents -->
    <div class="col-md-8">
        <!-- FORMULAIRE DE PROFIL AU PREMIER PLAN -->




        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header" style="background: linear-gradient(135deg, #003366, #0066cc); color: white; border-radius: 15px 15px 0 0;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-user-edit me-2"></i>
                            Mon Profil Professionnel
                        </h5>
                        <small class="opacity-75">
                            <i class="fas fa-info-circle me-1"></i>
                            Complétez votre profil pour maximiser vos opportunités
                        </small>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-light text-dark px-3 py-2">
                            <i class="fas fa-chart-pie me-1"></i>
                            <span id="completion-percentage">85%</span> complet
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <!-- Navigation par onglets améliorée -->
                <ul class="nav nav-tabs nav-tabs-modern border-0" id="profile-tabs" role="tablist" style="background-color: #f8f9fa; padding: 10px 20px 0;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-top" id="basic-info-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab" style="border: none; background: white; margin-right: 5px;">
                            <i class="fas fa-user me-2 text-primary"></i>
                            <span class="fw-bold">Informations de base</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-top" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills" type="button" role="tab" style="border: none; background: #f8f9fa; margin-right: 5px;">
                            <i class="fas fa-tools me-2 text-warning"></i>
                            <span class="fw-bold">Compétences</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-top" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" style="border: none; background: #f8f9fa; margin-right: 5px;">
                            <i class="fas fa-envelope me-2 text-success"></i>
                            <span class="fw-bold">Contact</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-top" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab" style="border: none; background: #f8f9fa;">
                            <i class="fas fa-cog me-2 text-info"></i>
                            <span class="fw-bold">Préférences</span>
                        </button>
                    </li>
                </ul>

                <form id="profile-form" method="POST" action="{{ route('design-graphique.cvtheque.update-profile') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="tab-content" id="profile-tab-content" style="background: white; padding: 30px; border-radius: 0 0 15px 15px;">
                        <!-- Onglet Informations de base -->
                        <div class="tab-pane fade show active" id="basic-info" role="tabpanel">
                            <div class="alert alert-info border-0 mb-4" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-radius: 10px;">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-lightbulb fs-4 me-3 text-primary"></i>
                                    <div>
                                        <h6 class="mb-1 text-primary fw-bold">Conseil professionnel</h6>
                                        <small class="text-muted">Présentez-vous de manière professionnelle pour attirer les recruteurs et maximiser vos opportunités</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="professional_title" name="professional_title"
                                               value="{{ $cvthequeProfile->professional_title ?? '' }}"
                                               placeholder="Ex: Designer Graphique Junior"
                                               required style="border-radius: 10px; border: 2px solid #e9ecef;">
                                        <label for="professional_title">
                                            <i class="fas fa-briefcase me-2 text-primary"></i>
                                            Titre Professionnel *
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <select class="form-select" id="years_experience" name="years_experience" style="border-radius: 10px; border: 2px solid #e9ecef;">
                                            <option value="0" {{ ($cvthequeProfile->years_experience ?? 0) == 0 ? 'selected' : '' }}>Débutant (0 an)</option>
                                            <option value="1" {{ ($cvthequeProfile->years_experience ?? 0) == 1 ? 'selected' : '' }}>1 an</option>
                                            <option value="2" {{ ($cvthequeProfile->years_experience ?? 0) == 2 ? 'selected' : '' }}>2 ans</option>
                                            <option value="3" {{ ($cvthequeProfile->years_experience ?? 0) == 3 ? 'selected' : '' }}>3 ans</option>
                                            <option value="5" {{ ($cvthequeProfile->years_experience ?? 0) == 5 ? 'selected' : '' }}>5+ ans</option>
                                        </select>
                                        <label for="years_experience">
                                            <i class="fas fa-calendar-alt me-2 text-warning"></i>
                                            Années d'Expérience
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="form-floating">
                                    <textarea class="form-control" id="professional_summary" name="professional_summary" rows="4"
                                              placeholder="Décrivez votre parcours, vos compétences clés et vos objectifs..."
                                              required style="height: 120px; border-radius: 10px; border: 2px solid #e9ecef;">{{ $cvthequeProfile->professional_summary ?? '' }}</textarea>
                                    <label for="professional_summary">
                                        <i class="fas fa-file-alt me-2 text-success"></i>
                                        Résumé Professionnel *
                                    </label>
                                </div>
                                <div class="form-text mt-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Décrivez votre parcours, vos compétences et vos objectifs professionnels
                                        </small>
                                        <small class="badge bg-light text-dark">
                                            <span id="summary-count">0</span>/500 caractères
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Onglet Compétences -->
                        <div class="tab-pane fade" id="skills" role="tabpanel">
                            <div class="alert alert-success alert-sm mb-3">
                                <i class="fas fa-star me-2"></i>
                                <small>Mettez en avant vos compétences techniques pour vous démarquer</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-laptop me-1 text-primary"></i>
                                    <small class="fw-bold">Logiciels Maîtrisés *</small>
                                </label>
                                <div class="row">
                                    @php
                                        $softwareList = [
                                            'photoshop' => ['name' => 'Adobe Photoshop', 'icon' => 'fab fa-adobe', 'color' => 'primary'],
                                            'illustrator' => ['name' => 'Adobe Illustrator', 'icon' => 'fab fa-adobe', 'color' => 'warning'],
                                            'indesign' => ['name' => 'Adobe InDesign', 'icon' => 'fab fa-adobe', 'color' => 'danger'],
                                            'figma' => ['name' => 'Figma', 'icon' => 'fab fa-figma', 'color' => 'info'],
                                            'canva' => ['name' => 'Canva', 'icon' => 'fas fa-palette', 'color' => 'success'],
                                            'after_effects' => ['name' => 'After Effects', 'icon' => 'fab fa-adobe', 'color' => 'dark']
                                        ];
                                        $userSoftware = $cvthequeProfile->software_skills ?? [];
                                    @endphp
                                    @foreach($softwareList as $key => $software)
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="software_skills[]" value="{{ $key }}" id="software_{{ $key }}"
                                                   {{ in_array($key, $userSoftware) ? 'checked' : '' }}>
                                            <label class="form-check-label d-flex align-items-center" for="software_{{ $key }}">
                                                <i class="{{ $software['icon'] }} text-{{ $software['color'] }} me-2"></i>
                                                <small>{{ $software['name'] }}</small>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="job_type" class="form-label">
                                    <i class="fas fa-search me-1 text-info"></i>
                                    <small class="fw-bold">Type de Poste Recherché</small>
                                </label>
                                <select class="form-select" id="job_type" name="job_type">
                                    <option value="" {{ ($cvthequeProfile->job_type ?? '') == '' ? 'selected' : '' }}>Tous types de contrats</option>
                                    <option value="cdi" {{ ($cvthequeProfile->job_type ?? '') == 'cdi' ? 'selected' : '' }}>CDI</option>
                                    <option value="cdd" {{ ($cvthequeProfile->job_type ?? '') == 'cdd' ? 'selected' : '' }}>CDD</option>
                                    <option value="freelance" {{ ($cvthequeProfile->job_type ?? '') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="stage" {{ ($cvthequeProfile->job_type ?? '') == 'stage' ? 'selected' : '' }}>Stage</option>
                                    <option value="alternance" {{ ($cvthequeProfile->job_type ?? '') == 'alternance' ? 'selected' : '' }}>Alternance</option>
                                </select>
                            </div>
                        </div>

                        <!-- Onglet Contact -->
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <div class="alert alert-warning alert-sm mb-3">
                                <i class="fas fa-shield-alt me-2"></i>
                                <small>Ces informations ne seront visibles que par les recruteurs autorisés</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="professional_email" class="form-label">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        <small class="fw-bold">Email Professionnel *</small>
                                    </label>
                                    <input type="email" class="form-control" id="professional_email" name="professional_email"
                                           value="{{ $cvthequeProfile->professional_email ?? '' }}"
                                           placeholder="votre.email@professionnel.com"
                                           required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="linkedin_profile" class="form-label">
                                        <i class="fab fa-linkedin me-1 text-info"></i>
                                        <small class="fw-bold">Profil LinkedIn</small>
                                    </label>
                                    <input type="url" class="form-control" id="linkedin_profile" name="linkedin_profile"
                                           value="{{ $cvthequeProfile->linkedin_profile ?? '' }}"
                                           placeholder="https://linkedin.com/in/votre-profil">
                                </div>
                            </div>
                        </div>

                        <!-- Onglet Préférences -->
                        <div class="tab-pane fade" id="preferences" role="tabpanel">
                            <div class="alert alert-info alert-sm mb-3">
                                <i class="fas fa-sliders-h me-2"></i>
                                <small>Définissez vos préférences de travail pour recevoir des offres adaptées</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                        Préférences de Travail
                                    </h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="remote_work" value="1" id="remote_work"
                                               {{ ($cvthequeProfile->remote_work ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remote_work">
                                            <i class="fas fa-home me-1 text-success"></i>
                                            <strong>Travail à distance</strong>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="willing_to_relocate" value="1" id="willing_to_relocate"
                                               {{ ($cvthequeProfile->willing_to_relocate ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="willing_to_relocate">
                                            <i class="fas fa-plane me-1 text-info"></i>
                                            <strong>Mobilité géographique</strong>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-eye me-2 text-warning"></i>
                                        Visibilité
                                    </h6>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="profile_visible" value="1" id="profile_visible"
                                               {{ ($cvthequeProfile->profile_visible ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="profile_visible">
                                            <i class="fas fa-eye me-1 text-success"></i>
                                            <strong>Profil visible</strong>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="allow_contact" value="1" id="allow_contact"
                                               {{ ($cvthequeProfile->allow_contact ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_contact">
                                            <i class="fas fa-envelope me-1 text-primary"></i>
                                            <strong>Autoriser les contacts</strong>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
        </div>





        <!-- SECTION PIÈCES JOINTES ALIGNÉES HORIZONTALEMENT -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-paperclip me-2"></i>
                    Mes documents et pièces jointes
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <!-- CV -->
                    <div class="col-lg-6 col-md-12">
                        <div class="upload-card h-100">
                            <div class="upload-card-header">
                                <i class="fas fa-file-alt text-primary"></i>
                                <h6 class="mb-0">CV (En version pdf)</h6>
                            </div>
                            <div class="upload-zone compact" id="cv-upload-zone" onclick="document.getElementById('cv-file').click()">
                                <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                                <p class="mb-1"><strong>Télécharger CV</strong></p>
                                <small class="text-muted">PDF, DOC, DOCX (5MB max)</small>
                            </div>
                            <input type="file" id="cv-file" accept=".pdf,.doc,.docx" style="display: none;" onchange="uploadFile('cv', this)">
                            <div id="cv-preview" class="mt-2" style="display: none;">
                                <div class="alert alert-success alert-sm">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span id="cv-filename"></span>
                                    <button class="btn btn-sm btn-outline-danger float-end" onclick="removeFile('cv')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lettre de motivation -->
                    <div class="col-lg-6 col-md-12">
                        <div class="upload-card h-100">
                            <div class="upload-card-header">
                                <i class="fas fa-envelope text-success"></i>
                                <h6 class="mb-0">Lettre de motivation (En version pdf)</h6>
                            </div>
                            <div class="upload-zone compact" id="motivation-upload-zone" onclick="document.getElementById('motivation-file').click()">
                                <i class="fas fa-envelope-open fa-2x mb-2"></i>
                                <p class="mb-1"><strong>Télécharger lettre</strong></p>
                                <small class="text-muted">PDF, DOC, DOCX (5MB max)</small>
                            </div>
                            <input type="file" id="motivation-file" accept=".pdf,.doc,.docx" style="display: none;" onchange="uploadFile('motivation', this)">
                            <div id="motivation-preview" class="mt-2" style="display: none;">
                                <div class="alert alert-success alert-sm">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span id="motivation-filename"></span>
                                    <button class="btn btn-sm btn-outline-danger float-end" onclick="removeFile('motivation')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <!-- Réalisations visuelles -->
                    <div class="col-lg-4 col-md-6">
                        <div class="upload-card h-100">
                            <div class="upload-card-header">
                                <i class="fas fa-palette text-warning"></i>
                                <h6 class="mb-0">Réalisations</h6>
                            </div>
                            <div class="upload-zone compact" id="realisations-upload-zone" onclick="document.getElementById('realisations-file').click()">
                                <i class="fas fa-images fa-2x mb-2"></i>
                                <p class="mb-1"><strong>Mes créations</strong></p>
                                <small class="text-muted">JPG, PNG, PDF, AI, PSD</small>
                            </div>
                            <input type="file" id="realisations-file" accept=".jpg,.jpeg,.png,.pdf,.ai,.psd" multiple style="display: none;" onchange="uploadFile('realisations', this)">
                            <div id="realisations-preview" class="mt-2">
                                <!-- Les fichiers téléchargés apparaîtront ici -->
                            </div>
                        </div>
                    </div>

                    <!-- Pressbook -->
                    <div class="col-lg-4 col-md-6">
                        <div class="upload-card h-100">
                            <div class="upload-card-header">
                                <i class="fas fa-book text-info"></i>
                                <h6 class="mb-0">Pressbook (En version pdf)</h6>
                            </div>
                            <div class="upload-zone compact" id="pressbook-upload-zone" onclick="document.getElementById('pressbook-file').click()">
                                <i class="fas fa-file-pdf fa-2x mb-2"></i>
                                <p class="mb-1"><strong>Portfolio PDF</strong></p>
                                <small class="text-muted">PDF uniquement (20MB max)</small>
                            </div>
                            <input type="file" id="pressbook-file" accept=".pdf" style="display: none;" onchange="uploadFile('pressbook', this)">
                            <div id="pressbook-preview" class="mt-2" style="display: none;">
                                <div class="alert alert-success alert-sm">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span id="pressbook-filename"></span>
                                    <button class="btn btn-sm btn-outline-danger float-end" onclick="removeFile('pressbook')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rapport de fin de formation -->
                    <div class="col-lg-4 col-md-6">
                        <div class="upload-card h-100">
                            <div class="upload-card-header">
                                <i class="fas fa-graduation-cap text-danger"></i>
                                <h6 class="mb-0">Rapport de fin de formation (En version pdf)</h6>
                            </div>
                            <div class="upload-zone compact" id="rapport-upload-zone" onclick="document.getElementById('rapport-file').click()">
                                <i class="fas fa-file-contract fa-2x mb-2"></i>
                                <p class="mb-1"><strong>Rapport formation</strong></p>
                                <small class="text-muted">PDF, DOC, DOCX (10MB max)</small>
                            </div>
                            <input type="file" id="rapport-file" accept=".pdf,.doc,.docx" style="display: none;" onchange="uploadFile('rapport', this)">
                            <div id="rapport-preview" class="mt-2" style="display: none;">
                                <div class="alert alert-success alert-sm">
                                    <i class="fas fa-check-circle me-1"></i>
                                    <span id="rapport-filename"></span>
                                    <button class="btn btn-sm btn-outline-danger float-end" onclick="removeFile('rapport')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Sidebar avec informations et conseils -->
    <div class="col-md-4">
        <!-- Conseils -->


        <!-- Prévisualiser mon profil et historique des documents -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    Mon Profil CVThèque
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="{{ route('design-graphique.cvtheque.preview') }}" class="btn-visual-primary">
                        <div class="btn-visual-content">
                            <div class="btn-visual-icon">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="btn-visual-text">
                                <h6 class="mb-1">Prévisualiser mon profil</h6>
                                <small>Voir comment votre profil apparaît aux employeurs</small>
                            </div>
                            <div class="btn-visual-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="mb-3">
                    <a href="{{ route('design-graphique.cvtheque.historique') }}" class="btn-visual-secondary">
                        <div class="btn-visual-content">
                            <div class="btn-visual-icon">
                                <i class="fas fa-history"></i>
                            </div>
                            <div class="btn-visual-text">
                                <h6 class="mb-1">Historique des documents</h6>
                                <small>Suivez le statut de validation de vos fichiers</small>
                            </div>
                            <div class="btn-visual-arrow">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Gestion du Profil Organisée -->



    </div>
</div>

<!-- Bouton de sauvegarde global pour profil et documents -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-lg border-0" style="border-radius: 15px; background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="me-4">
                                <i class="fas fa-shield-check fs-1 text-success"></i>
                            </div>
                            <div>
                                <h5 class="mb-2 text-dark fw-bold">
                                    <i class="fas fa-save me-2"></i>
                                    Sauvegarde complète de votre CVThèque
                                </h5>
                                <p class="mb-1 text-muted">
                                    Sauvegardez vos informations de profil ET vos documents en une seule fois
                                </p>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Vos données sont protégées et chiffrées pour votre sécurité
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid">
                            <button type="button" class="btn btn-lg shadow" id="save-all-btn"
                                    style="background: linear-gradient(135deg, #003366, #0066cc); color: white; border: none; border-radius: 12px; font-weight: 600; padding: 15px 30px; transition: all 0.3s ease;"
                                    onclick="saveCompleteProfile()">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-cloud-upload-alt me-3 fs-5"></i>
                                    <div class="text-start">
                                        <div class="fw-bold">Sauvegarder tout</div>
                                        <small class="opacity-75">Profil + Documents</small>
                                    </div>
                                    <div class="spinner-border spinner-border-sm ms-3 d-none" id="save-all-spinner" role="status">
                                        <span class="visually-hidden">Sauvegarde...</span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Indicateurs de progression -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted fw-bold">
                                <i class="fas fa-user me-1"></i>
                                Profil professionnel
                            </small>
                            <small class="badge bg-primary">85% complet</small>
                        </div>
                        <div class="progress mb-2" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar" role="progressbar" style="width: 85%; background: linear-gradient(135deg, #003366, #0066cc);" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted fw-bold">
                                <i class="fas fa-paperclip me-1"></i>
                                Documents uploadés
                            </small>
                            <small class="badge bg-warning text-dark" id="documents-status">2/5 documents</small>
                        </div>
                        <div class="progress mb-2" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 40%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>

                <!-- Messages d'encouragement -->
                <div class="mt-3 p-3 rounded" style="background: rgba(0, 51, 102, 0.1);">
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-lightbulb me-1 text-warning"></i>
                                <strong>Conseil :</strong> Complétez tous les onglets du profil pour maximiser vos opportunités
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-upload me-1 text-info"></i>
                                <strong>Documents :</strong> Ajoutez CV, lettre de motivation et réalisations pour un profil complet
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de notification dynamique -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" id="notificationModalContent">
            <div class="modal-header border-0" id="notificationModalHeader">
                <h5 class="modal-title d-flex align-items-center" id="notificationModalLabel">
                    <i id="notificationIcon" class="me-2 fs-4"></i>
                    <span id="notificationTitle"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="notificationModalBody">
                <p id="notificationMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer border-0" id="notificationModalFooter">
                <button type="button" class="btn" id="notificationCloseBtn" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles pour la modale de notification */
#notificationModal .modal-content {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    overflow: hidden;
}

#notificationModal .modal-header {
    border-radius: 15px 15px 0 0;
    padding: 20px 25px;
}

#notificationModal .modal-body {
    padding: 25px;
    font-size: 16px;
    line-height: 1.6;
}

#notificationModal .modal-footer {
    padding: 15px 25px 20px;
    justify-content: center;
}

#notificationModal .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

#notificationModal .btn-close:hover {
    opacity: 1;
}

#notificationModal #notificationCloseBtn {
    min-width: 120px;
    border-radius: 25px;
    font-weight: 600;
    padding: 10px 20px;
    transition: all 0.3s ease;
}

#notificationModal #notificationCloseBtn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* Animation d'entrée personnalisée */
#notificationModal.show .modal-dialog {
    animation: modalSlideIn 0.4s ease-out;
}

@keyframes modalSlideIn {
    from {
        transform: translate(0, -50px) scale(0.9);
        opacity: 0;
    }
    to {
        transform: translate(0, 0) scale(1);
        opacity: 1;
    }
}

/* Styles pour les zones d'upload originales */
.upload-zone {
    border: 2px dashed #003366;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}

.upload-zone:hover {
    border-color: #3399ff;
    background-color: #e3f2fd;
    transform: translateY(-2px);
}

.upload-zone i {
    color: #003366;
}

.upload-zone:hover i {
    color: #3399ff;
}

/* Styles pour les nouvelles cartes d'upload compactes */
.upload-card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    background: #fff;
    transition: all 0.3s ease;
    overflow: hidden;
}

.upload-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.upload-card-header {
    background: #f8f9fa;
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.upload-card-header h6 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 600;
}

.upload-zone.compact {
    border: 2px dashed #dee2e6;
    border-radius: 6px;
    padding: 1.5rem 1rem;
    margin: 1rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: #fdfdfd;
    min-height: 120px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.upload-zone.compact:hover {
    border-color: #007bff;
    background-color: #f0f8ff;
    transform: translateY(-1px);
}

.upload-zone.compact i {
    color: #6c757d;
    transition: color 0.3s ease;
}

.upload-zone.compact:hover i {
    color: #007bff;
}

.upload-zone.compact p {
    margin: 0;
    font-size: 0.9rem;
    color: #495057;
}

.upload-zone.compact small {
    font-size: 0.75rem;
    color: #6c757d;
}

/* Responsive pour les cartes d'upload */
@media (max-width: 768px) {
    .upload-zone.compact {
        padding: 1rem 0.5rem;
        min-height: 100px;
    }

    .upload-card-header {
        padding: 0.5rem 0.75rem;
    }

    .upload-zone.compact i {
        font-size: 1.5rem !important;
    }
}

/* Styles pour les alertes de succès compactes */
.alert-sm {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
}

.alert-sm .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Styles pour améliorer la visibilité des onglets */
#profile-tabs .nav-link {
    color: #495057 !important;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-bottom: none;
    font-weight: 500;
    padding: 0.75rem 1.25rem;
    margin-right: 0.25rem;
    border-radius: 0.375rem 0.375rem 0 0;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

#profile-tabs .nav-link:hover {
    color: #007bff !important;
    background-color: #e3f2fd;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,123,255,0.2);
}

#profile-tabs .nav-link.active {
    color: #007bff !important;
    background-color: #ffffff;
    border-color: #007bff #007bff #ffffff;
    font-weight: 600;
    z-index: 2;
    box-shadow: 0 -2px 8px rgba(0,123,255,0.1);
}

#profile-tabs .nav-link i {
    font-size: 0.9rem;
    margin-right: 0.5rem;
}

/* Amélioration du contenu des onglets */
.tab-content {
    background-color: #ffffff;
    border: 1px solid #dee2e6;
    border-top: 2px solid #007bff;
    border-radius: 0 0.375rem 0.375rem 0.375rem;
    padding: 1.5rem;
    margin-top: -1px;
    position: relative;
    z-index: 1;
}

/* Indicateurs visuels pour les onglets complétés */
#profile-tabs .nav-link.completed::after {
    content: '✓';
    position: absolute;
    top: -5px;
    right: -5px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 18px;
    height: 18px;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* Responsive pour les onglets */
@media (max-width: 768px) {
    #profile-tabs .nav-link {
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        margin-right: 0.125rem;
    }

    #profile-tabs .nav-link i {
        font-size: 0.8rem;
        margin-right: 0.25rem;
    }

    .tab-content {
        padding: 1rem;
    }
}

.file-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #003366;
}

.file-item img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
}

/* Boutons visuels très attractifs */
.btn-visual-primary, .btn-visual-secondary {
    display: block;
    text-decoration: none;
    border-radius: 15px;
    padding: 0;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: none;
    position: relative;
    animation: pulseGlow 2s ease-in-out infinite alternate;
}

.btn-visual-primary {
    background: linear-gradient(135deg, #003366, #0066cc);
    color: white;
}

.btn-visual-primary::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    animation: waveEffect 4s ease-in-out infinite;
    z-index: 1;
}

.btn-visual-secondary {
    background: linear-gradient(135deg, #3399ff, #66b3ff);
    color: white;
}

.btn-visual-secondary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    animation: waveEffect 3.5s ease-in-out infinite 0.5s;
    z-index: 1;
}

.btn-visual-primary:hover, .btn-visual-secondary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    text-decoration: none;
    color: white;
}

.btn-visual-content {
    display: flex;
    align-items: center;
    padding: 20px;
    position: relative;
}

.btn-visual-icon {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 24px;
    backdrop-filter: blur(10px);
    animation: iconBlink 3s ease-in-out infinite, glowBorder 4s ease-in-out infinite;
    position: relative;
    overflow: hidden;
}

/* Effet de vague d'eau sur les icônes */
.btn-visual-icon::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: waveEffect 3s ease-in-out infinite;
    border-radius: 50%;
}

.btn-visual-text {
    flex-grow: 1;
    text-align: left;
}

.btn-visual-text h6 {
    font-weight: 600;
    font-size: 16px;
    margin-bottom: 5px;
    color: white;
}

.btn-visual-text small {
    color: rgba(255, 255, 255, 0.8);
    font-size: 12px;
    line-height: 1.3;
}

.btn-visual-arrow {
    flex-shrink: 0;
    font-size: 18px;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.btn-visual-primary:hover .btn-visual-arrow,
.btn-visual-secondary:hover .btn-visual-arrow {
    transform: translateX(5px);
    opacity: 1;
}

/* Animation de pulsation pour attirer l'attention */
.btn-visual-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-visual-primary:hover::before {
    opacity: 1;
}

/* Effet de brillance */
.btn-visual-secondary::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    transition: all 0.6s ease;
    opacity: 0;
}

.btn-visual-secondary:hover::after {
    animation: shine 0.6s ease-in-out;
}

@keyframes shine {
    0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
        opacity: 0;
    }
}

/* Animation de clignotement et pulsation */
@keyframes pulseGlow {
    0% {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1), 0 0 0 0 rgba(0, 51, 102, 0.7);
        transform: scale(1);
    }
    50% {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2), 0 0 20px 5px rgba(0, 51, 102, 0.4);
        transform: scale(1.02);
    }
    100% {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1), 0 0 0 0 rgba(0, 51, 102, 0.7);
        transform: scale(1);
    }
}

/* Animation de vague d'eau */
@keyframes waveEffect {
    0% {
        transform: translateX(-100%);
        opacity: 0;
    }
    50% {
        opacity: 0.6;
    }
    100% {
        transform: translateX(100%);
        opacity: 0;
    }
}

/* Effet de clignotement des icônes */
@keyframes iconBlink {
    0%, 50% {
        opacity: 1;
        transform: scale(1);
    }
    25% {
        opacity: 0.7;
        transform: scale(1.1);
    }
    75% {
        opacity: 0.9;
        transform: scale(0.95);
    }
}

/* Animation de bordure lumineuse */
@keyframes glowBorder {
    0% {
        border: 2px solid transparent;
    }
    50% {
        border: 2px solid rgba(255, 255, 255, 0.5);
        box-shadow: inset 0 0 20px rgba(255, 255, 255, 0.1);
    }
    100% {
        border: 2px solid transparent;
    }
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .btn-visual-content {
        padding: 15px;
    }

    .btn-visual-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
        margin-right: 15px;
    }

    .btn-visual-text h6 {
        font-size: 14px;
    }

    .btn-visual-text small {
        font-size: 11px;
    }
}
</style>

<script>
function uploadFile(type, input) {
    const files = input.files;
    if (files.length === 0) return;

    // Simulation d'upload (remplacer par vraie logique d'upload)
    const formData = new FormData();

    if (type === 'realisations') {
        // Gestion multiple pour les réalisations
        for (let i = 0; i < files.length; i++) {
            formData.append('files[]', files[i]);
        }
        displayRealisations(files);
        updateRealisationsCount();
    } else {
        // Gestion simple pour les autres types
        const file = files[0];
        formData.append('file', file);
        displaySingleFile(type, file);
        updateStatus(type, 'Ajouté');
    }

    // Masquer la zone d'upload et afficher le preview
    document.getElementById(type + '-upload-zone').style.display = 'none';
    document.getElementById(type + '-preview').style.display = 'block';
}

function displaySingleFile(type, file) {
    const filename = document.getElementById(type + '-filename');
    if (filename) {
        filename.textContent = file.name;
    }
}

function displayRealisations(files) {
    const preview = document.getElementById('realisations-preview');
    preview.innerHTML = '';

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';

        let preview_content = '';
        if (file.type.startsWith('image/')) {
            const url = URL.createObjectURL(file);
            preview_content = `<img src="${url}" alt="${file.name}">`;
        } else {
            preview_content = `<i class="fas fa-file fa-2x"></i>`;
        }

        fileItem.innerHTML = `
            <div class="d-flex align-items-center">
                ${preview_content}
                <div class="ms-2">
                    <small class="fw-bold">${file.name}</small><br>
                    <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-danger" onclick="removeRealization(this)">
                <i class="fas fa-trash"></i>
            </button>
        `;

        preview.appendChild(fileItem);
    }
}

function removeFile(type) {
    document.getElementById(type + '-upload-zone').style.display = 'block';
    document.getElementById(type + '-preview').style.display = 'none';
    document.getElementById(type + '-file').value = '';
    updateStatus(type, 'Non ajouté');
}

function removeRealization(button) {
    button.parentElement.remove();
    updateRealisationsCount();
}

function updateStatus(type, status) {
    const statusElement = document.getElementById(type + '-status');
    if (statusElement) {
        statusElement.textContent = status;
    }
}

function updateRealisationsCount() {
    const count = document.getElementById('realisations-preview').children.length;
    document.getElementById('realisations-count').textContent = count + ' fichier(s)';
}

// Drag and drop functionality
document.addEventListener('DOMContentLoaded', function() {
    const uploadZones = document.querySelectorAll('.upload-zone');

    uploadZones.forEach(zone => {
        zone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '#e3f2fd';
        });

        zone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '#f8f9fa';
        });

        zone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.backgroundColor = '#f8f9fa';

            const files = e.dataTransfer.files;
            const type = this.id.replace('-upload-zone', '');
            const input = document.getElementById(type + '-file');

            // Simuler la sélection de fichiers
            Object.defineProperty(input, 'files', {
                value: files,
                writable: false,
            });

            uploadFile(type, input);
        });
    });

    // Gestion améliorée du formulaire de profil organisé

    // Compteur de caractères pour le résumé professionnel
    $('#professional_summary').on('input', function() {
        const maxLength = 500;
        const currentLength = $(this).val().length;
        const $counter = $('#summary-count');

        $counter.text(currentLength);

        // Changer la couleur selon la progression
        if (currentLength > maxLength * 0.9) {
            $counter.removeClass('text-muted text-warning').addClass('text-danger');
        } else if (currentLength > maxLength * 0.7) {
            $counter.removeClass('text-muted text-danger').addClass('text-warning');
        } else {
            $counter.removeClass('text-warning text-danger').addClass('text-muted');
        }

        // Limiter la saisie
        if (currentLength > maxLength) {
            $(this).val($(this).val().substring(0, maxLength));
            $counter.text(maxLength);
        }
    });

    // Initialiser le compteur au chargement
    $('#professional_summary').trigger('input');

    // Validation en temps réel des champs requis
    function validateRequiredFields() {
        const requiredFields = ['professional_title', 'professional_summary', 'professional_email'];
        let isValid = true;

        requiredFields.forEach(function(fieldId) {
            const $field = $('#' + fieldId);
            const value = $field.val().trim();

            if (!value) {
                $field.addClass('is-invalid');
                isValid = false;
            } else {
                $field.removeClass('is-invalid').addClass('is-valid');
            }
        });

        // Validation spéciale pour les logiciels (au moins un sélectionné)
        const selectedSoftware = $('input[name="software_skills[]"]:checked').length;
        if (selectedSoftware === 0) {
            $('.skills-validation').remove();
            $('#skills .alert').after('<div class="alert alert-warning alert-sm skills-validation"><small><i class="fas fa-exclamation-triangle me-1"></i>Sélectionnez au moins un logiciel</small></div>');
            isValid = false;
        } else {
            $('.skills-validation').remove();
        }

        return isValid;
    }

    // Validation en temps réel sur les champs requis
    $('input[required], textarea[required]').on('blur', validateRequiredFields);
    $('input[name="software_skills[]"]').on('change', validateRequiredFields);

    // Indicateurs visuels pour les onglets complétés
    function updateTabIndicators() {
        // Onglet Infos de base
        const hasBasicInfo = $('#professional_title').val().trim() && $('#professional_summary').val().trim();
        updateTabIcon('basic-info-tab', hasBasicInfo);

        // Onglet Compétences
        const hasSkills = $('input[name="software_skills[]"]:checked').length > 0 && $('#job_type').val();
        updateTabIcon('skills-tab', hasSkills);

        // Onglet Contact
        const hasContact = $('#professional_email').val().trim();
        updateTabIcon('contact-tab', hasContact);

        // Onglet Préférences (toujours valide car optionnel)
        updateTabIcon('preferences-tab', true);
    }

    function updateTabIcon(tabId, isComplete) {
        const $tab = $('#' + tabId);
        const $icon = $tab.find('i').first();

        if (isComplete) {
            // Ajouter la classe completed pour l'indicateur visuel
            $tab.addClass('completed');
            // Garder l'icône originale mais ajouter une couleur de succès
            $icon.addClass('text-success');
        } else {
            // Retirer la classe completed et la couleur de succès
            $tab.removeClass('completed');
            $icon.removeClass('text-success');
        }
    }

    // Mettre à jour les indicateurs lors des changements
    $('#professional_title, #professional_summary, #professional_email').on('input', updateTabIndicators);
    $('input[name="software_skills[]"], #job_type').on('change', updateTabIndicators);

    // Initialiser les indicateurs
    setTimeout(updateTabIndicators, 500);

    // Gestion du formulaire de profil en AJAX avec fichiers
    $('#profile-form').on('submit', function(e) {
        e.preventDefault();

        // Valider avant soumission
        if (!validateRequiredFields()) {
            showNotification('error', 'Veuillez remplir tous les champs obligatoires');
            return;
        }

        const $form = $(this);
        const $submitBtn = $('#save-profile-btn');
        const originalText = $submitBtn.html();

        // Désactiver le bouton et afficher un indicateur de chargement
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Sauvegarde en cours...');

        // Préparer les données du formulaire avec fichiers
        const formData = new FormData(this);

        // Ajouter les fichiers des zones d'upload si ils existent
        const fileInputs = [
            { id: 'cv-file', name: 'cv_file' },
            { id: 'motivation-file', name: 'motivation_file' },
            { id: 'pressbook-file', name: 'pressbook_file' },
            { id: 'rapport-file', name: 'rapport_file' },
            { id: 'realisations-file', name: 'realisations_files' }
        ];

        fileInputs.forEach(function(input) {
            const fileInput = document.getElementById(input.id);
            if (fileInput && fileInput.files.length > 0) {
                if (input.id === 'realisations-file') {
                    // Pour les réalisations, ajouter tous les fichiers
                    for (let i = 0; i < fileInput.files.length; i++) {
                        formData.append('realisations_files[]', fileInput.files[i]);
                    }
                } else {
                    formData.append(input.name, fileInput.files[0]);
                }
            }
        });

        // Envoyer la requête AJAX
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Afficher un message de succès
                    showNotification('success', response.message);

                    // Mettre à jour le score de complétion si disponible
                    if (response.completion_score) {
                        $('.progress-bar').css('width', response.completion_score + '%')
                                         .attr('aria-valuenow', response.completion_score);
                        $('.progress').next('small').text('Profil complété à ' + response.completion_score + '%');

                        // Mettre à jour aussi dans les statistiques
                        $('.card').find('small').each(function() {
                            if ($(this).text().includes('% complet')) {
                                $(this).text(response.completion_score + '% complet');
                            }
                        });
                    }

                    // Mettre à jour l'affichage des fichiers si disponible
                    if (response.files) {
                        updateFileDisplays(response.files);
                    }

                    // Mettre à jour les indicateurs d'onglets
                    updateTabIndicators();
                } else {
                    showNotification('error', response.message || 'Erreur lors de la sauvegarde');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erreur AJAX:', xhr, status, error);
                let errorMessage = 'Erreur lors de la sauvegarde du profil';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors).flat();
                    errorMessage = errors.join(', ');
                } else if (xhr.status === 413) {
                    errorMessage = 'Fichier trop volumineux. Veuillez réduire la taille de vos fichiers.';
                } else if (xhr.status === 422) {
                    errorMessage = 'Données invalides. Vérifiez vos fichiers et réessayez.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Erreur serveur. Veuillez réessayer plus tard.';
                }

                showNotification('error', errorMessage);
            },
            complete: function() {
                // Toujours réactiver le bouton, même en cas d'erreur
                $submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Fonction pour mettre à jour l'affichage des fichiers
    function updateFileDisplays(files) {
        // Mettre à jour l'affichage pour chaque type de fichier
        Object.keys(files).forEach(function(fileType) {
            const fileData = files[fileType];

            if (fileType === 'realisations') {
                // Gérer les fichiers multiples de réalisations
                updateRealisationsDisplay(fileData);
            } else {
                // Gérer les fichiers uniques
                updateSingleFileDisplay(fileType, fileData);
            }
        });
    }

    // Fonction pour mettre à jour l'affichage d'un fichier unique
    function updateSingleFileDisplay(fileType, fileData) {
        const $preview = $('#' + fileType + '-preview');
        const $uploadZone = $('#' + fileType + '-upload-zone');

        if (fileData.exists && fileData.name) {
            // Afficher le fichier existant
            $preview.html(`
                <div class="alert alert-success alert-sm">
                    <i class="fas fa-check-circle me-1"></i>
                    <span>${fileData.name}</span>
                    <button class="btn btn-sm btn-outline-danger float-end" onclick="removeFile('${fileType}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `).show();
            $uploadZone.hide();
        } else {
            // Afficher la zone d'upload
            $preview.hide();
            $uploadZone.show();
        }
    }

    // Fonction pour mettre à jour l'affichage des réalisations
    function updateRealisationsDisplay(realisations) {
        const $preview = $('#realisations-preview');

        if (realisations && realisations.length > 0) {
            let html = '';
            realisations.forEach(function(file, index) {
                html += `
                    <div class="alert alert-success alert-sm mb-2">
                        <i class="fas fa-check-circle me-1"></i>
                        <span>${file.name}</span>
                        <button class="btn btn-sm btn-outline-danger float-end" onclick="removePortfolioFile('${file.name}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });
            $preview.html(html);
        }
    }

    // Fonction pour supprimer un fichier de portfolio
    function removePortfolioFile(fileName) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce fichier ?')) {
            return;
        }

        $.ajax({
            url: '{{ route("design-graphique.cvtheque.documents.delete") }}',
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                file_type: 'portfolio',
                file_name: fileName
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    // Recharger l'affichage des fichiers
                    location.reload();
                } else {
                    showNotification('error', response.message);
                }
            },
            error: function() {
                showNotification('error', 'Erreur lors de la suppression du fichier');
            }
        });
    }

    // Fonction améliorée pour afficher les notifications en modal
    function showNotification(type, message) {
        const modal = $('#notificationModal');
        const modalContent = $('#notificationModalContent');
        const modalHeader = $('#notificationModalHeader');
        const modalFooter = $('#notificationModalFooter');
        const icon = $('#notificationIcon');
        const title = $('#notificationTitle');
        const messageElement = $('#notificationMessage');
        const closeBtn = $('#notificationCloseBtn');

        // Configuration selon le type de notification
        if (type === 'success') {
            modalContent.removeClass('border-danger border-warning').addClass('border-success');
            modalHeader.removeClass('bg-danger bg-warning text-white').addClass('bg-success text-white');
            modalFooter.removeClass('bg-light').addClass('bg-light');
            icon.removeClass('fa-exclamation-triangle fa-exclamation-circle text-warning text-danger')
                .addClass('fa-check-circle text-white');
            title.text('Succès');
            closeBtn.removeClass('btn-outline-danger btn-outline-warning').addClass('btn-success');
        } else if (type === 'error') {
            modalContent.removeClass('border-success border-warning').addClass('border-danger');
            modalHeader.removeClass('bg-success bg-warning text-white').addClass('bg-danger text-white');
            modalFooter.removeClass('bg-light').addClass('bg-light');
            icon.removeClass('fa-check-circle fa-exclamation-circle text-success text-warning')
                .addClass('fa-exclamation-triangle text-white');
            title.text('Erreur');
            closeBtn.removeClass('btn-outline-success btn-outline-warning').addClass('btn-danger');
        } else if (type === 'warning') {
            modalContent.removeClass('border-success border-danger').addClass('border-warning');
            modalHeader.removeClass('bg-success bg-danger text-white').addClass('bg-warning text-dark');
            modalFooter.removeClass('bg-light').addClass('bg-light');
            icon.removeClass('fa-check-circle fa-exclamation-triangle text-success text-danger')
                .addClass('fa-exclamation-circle text-dark');
            title.text('Attention');
            closeBtn.removeClass('btn-outline-success btn-outline-danger').addClass('btn-warning');
        }

        // Définir le message
        messageElement.html(message);

        // Afficher la modal avec animation
        modal.modal('show');

        // Auto-fermer après 8 secondes pour les succès, 12 secondes pour les erreurs
        const autoCloseDelay = type === 'success' ? 8000 : (type === 'error' ? 12000 : 10000);
        setTimeout(function() {
            if (modal.hasClass('show')) {
                modal.modal('hide');
            }
        }, autoCloseDelay);

        // Ajouter un effet de vibration pour les erreurs
        if (type === 'error') {
            modalContent.addClass('animate__animated animate__shakeX');
            setTimeout(function() {
                modalContent.removeClass('animate__animated animate__shakeX');
            }, 1000);
        }

        // Ajouter un effet de bounce pour les succès
        if (type === 'success') {
            modalContent.addClass('animate__animated animate__bounceIn');
            setTimeout(function() {
                modalContent.removeClass('animate__animated animate__bounceIn');
            }, 1000);
        }
    }

    // Fonction pour sauvegarder le profil complet (profil + documents)
    window.saveCompleteProfile = function() {
        const $saveBtn = $('#save-all-btn');
        const $spinner = $('#save-all-spinner');
        const originalText = $saveBtn.html();

        // Désactiver le bouton et afficher le spinner
        $saveBtn.prop('disabled', true);
        $spinner.removeClass('d-none');

        // Étape 1: Sauvegarder le profil
        const profileForm = document.getElementById('profile-form');
        const profileFormData = new FormData(profileForm);

        $.ajax({
            url: '{{ route("design-graphique.cvtheque.update-profile") }}',
            type: 'POST',
            data: profileFormData,
            processData: false,
            contentType: false,
            success: function(profileResponse) {
                console.log('Profil sauvegardé avec succès');

                // Étape 2: Sauvegarder les documents uploadés
                saveAllDocuments().then(function() {
                    // Succès complet
                    showNotification('success', 'Profil et documents sauvegardés avec succès !');

                    // Mettre à jour les indicateurs de progression
                    updateProgressIndicators();

                }).catch(function(error) {
                    console.error('Erreur lors de la sauvegarde des documents:', error);
                    showNotification('warning', 'Profil sauvegardé, mais erreur lors de la sauvegarde des documents');
                });
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la sauvegarde du profil:', xhr, status, error);
                showNotification('error', 'Erreur lors de la sauvegarde du profil');
            },
            complete: function() {
                // Réactiver le bouton
                $saveBtn.prop('disabled', false).html(originalText);
                $spinner.addClass('d-none');
            }
        });
    };

    // Fonction pour sauvegarder tous les documents
    function saveAllDocuments() {
        return new Promise(function(resolve, reject) {
            const fileInputs = [
                { id: 'cv-file', name: 'cv_file' },
                { id: 'motivation-file', name: 'motivation_file' },
                { id: 'pressbook-file', name: 'pressbook_file' },
                { id: 'rapport-file', name: 'rapport_file' },
                { id: 'realisations-file', name: 'realisations_files' }
            ];

            let uploadPromises = [];

            fileInputs.forEach(function(input) {
                const fileInput = document.getElementById(input.id);
                if (fileInput && fileInput.files.length > 0) {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');

                    if (input.id === 'realisations-file') {
                        // Fichiers multiples pour les réalisations
                        for (let i = 0; i < fileInput.files.length; i++) {
                            formData.append('realisations_files[]', fileInput.files[i]);
                        }
                    } else {
                        formData.append(input.name, fileInput.files[0]);
                    }

                    // Déterminer la route d'upload selon le type de fichier
                    let uploadRoute;
                    switch(input.id) {
                        case 'cv-file':
                            uploadRoute = '{{ route("design-graphique.cvtheque.upload-cv") }}';
                            break;
                        case 'motivation-file':
                            uploadRoute = '{{ route("design-graphique.cvtheque.upload-motivation") }}';
                            break;
                        case 'pressbook-file':
                            uploadRoute = '{{ route("design-graphique.cvtheque.upload-pressbook") }}';
                            break;
                        case 'rapport-file':
                            uploadRoute = '{{ route("design-graphique.cvtheque.upload-rapport") }}';
                            break;
                        case 'realisations-file':
                            uploadRoute = '{{ route("design-graphique.cvtheque.upload-realisation") }}';
                            break;
                    }

                    const uploadPromise = $.ajax({
                        url: uploadRoute,
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log('Upload success for ' + input.id + ':', response);
                        },
                        error: function(xhr, status, error) {
                            console.error('Upload error for ' + input.id + ':', {
                                status: xhr.status,
                                statusText: xhr.statusText,
                                responseText: xhr.responseText,
                                error: error
                            });
                        }
                    });

                    uploadPromises.push(uploadPromise);
                }
            });

            if (uploadPromises.length === 0) {
                resolve(); // Aucun fichier à uploader
            } else {
                Promise.all(uploadPromises)
                    .then(function(results) {
                        console.log('Tous les documents ont été sauvegardés');
                        resolve(results);
                    })
                    .catch(function(error) {
                        console.error('Erreur lors de l\'upload des documents:', error);
                        reject(error);
                    });
            }
        });
    }

    // Fonction pour mettre à jour les indicateurs de progression
    function updateProgressIndicators() {
        // Compter les documents uploadés
        const fileInputs = ['cv-file', 'motivation-file', 'pressbook-file', 'rapport-file', 'realisations-file'];
        let documentsCount = 0;

        fileInputs.forEach(function(inputId) {
            const fileInput = document.getElementById(inputId);
            if (fileInput && fileInput.files.length > 0) {
                documentsCount++;
            }
        });

        // Mettre à jour l'affichage
        const documentsPercentage = (documentsCount / 5) * 100;
        $('#documents-status').text(documentsCount + '/5 documents');
        $('.progress-bar.bg-warning').css('width', documentsPercentage + '%');

        // Mettre à jour la couleur selon le pourcentage
        if (documentsPercentage === 100) {
            $('#documents-status').removeClass('bg-warning text-dark').addClass('bg-success text-white');
            $('.progress-bar.bg-warning').removeClass('bg-warning').addClass('bg-success');
        }
    }

    // Mettre à jour les indicateurs au chargement de la page
    $(document).ready(function() {
        updateProgressIndicators();
    });
});
</script>
@endsection
