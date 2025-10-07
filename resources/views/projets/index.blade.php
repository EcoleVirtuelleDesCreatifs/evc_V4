@extends('layouts.ki-admin')

@section('title', 'Projets - Design Graphique')

@section('content')
<style>
    .project-stat-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        color: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .project-stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    
    .project-stat-card.solo {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
    }
    
    .project-stat-card.groupe {
        background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%);
    }
    
    .project-stat-card.total {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
    }
    
    .project-stat-number {
        font-size: 4rem;
        font-weight: 900;
        line-height: 1;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.2);
        margin: 1rem 0;
    }
    
    .project-stat-label {
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        opacity: 0.95;
    }
    
    .project-stat-icon {
        font-size: 3.5rem;
        opacity: 0.2;
        position: absolute;
        right: 15px;
        top: 15px;
    }
    
    .project-stat-btn {
        background: rgba(255,255,255,0.25);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        font-weight: 700;
        padding: 0.6rem 1.5rem;
        border-radius: 30px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    
    .project-stat-btn:hover {
        background: rgba(255,255,255,0.4);
        border-color: white;
        color: white;
        transform: scale(1.05);
    }
    
    .section-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%);
        border-radius: 2px;
    }
    
    .nav-card {
        border: none;
        border-radius: 20px;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    
    .nav-btn-group .btn {
        border-radius: 10px;
        font-weight: 600;
        padding: 0.6rem 1.2rem;
        transition: all 0.3s ease;
    }
    
    .nav-btn-group .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>

<div class="container-fluid">
    <!-- Section Titre -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="section-title">
                <i class="fas fa-folder-open me-2"></i>
                Gestion des Projets
            </h2>
        </div>
    </div>



    <!-- Statistiques des projets -->
    <div class="row mb-5">
        <div class="col-md-4 mb-4 fade-in-up" style="animation-delay: 0.2s;">
            <div class="project-stat-card solo h-100">
                <i class="fas fa-user project-stat-icon"></i>
                <div class="card-body p-4 text-center">
                    <div class="project-stat-label mb-2">Projets Solo</div>
                    <div class="project-stat-number">{{ $stats['solo_projects'] ?? 0 }}</div>
                    <a href="{{ route('design-graphique.projets.solo') }}" class="btn project-stat-btn w-100 mt-3">
                        <i class="fas fa-arrow-right me-2"></i>
                        Explorer
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4 fade-in-up" style="animation-delay: 0.3s;">
            <div class="project-stat-card groupe h-100">
                <i class="fas fa-users project-stat-icon"></i>
                <div class="card-body p-4 text-center">
                    <div class="project-stat-label mb-2">Projets Groupe</div>
                    <div class="project-stat-number">{{ $stats['group_projects'] ?? 0 }}</div>
                    <a href="{{ route('design-graphique.projets.groupe') }}" class="btn project-stat-btn w-100 mt-3">
                        <i class="fas fa-arrow-right me-2"></i>
                        Explorer
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4 fade-in-up" style="animation-delay: 0.4s;">
            <div class="project-stat-card total h-100">
                <i class="fas fa-project-diagram project-stat-icon"></i>
                <div class="card-body p-4 text-center">
                    <div class="project-stat-label mb-2">Total Projets</div>
                    <div class="project-stat-number">{{ $stats['total_projects'] ?? 0 }}</div>
                    <a href="{{ route('design-graphique.projets.tous') }}" class="btn project-stat-btn w-100 mt-3">
                        <i class="fas fa-arrow-right me-2"></i>
                        Voir Tout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire Ajouter un Projet -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        Ajouter un Nouveau Projet
                    </h5>
                    <button type="button" class="btn btn-sm btn-outline-light" id="toggleFormBtn">
                        <i class="fas fa-chevron-down" id="toggleIcon"></i>
                    </button>
                </div>
                <div class="card-body" id="projectForm" style="display: none;">
                    <form action="{{ route('design-graphique.projets.store') }}" method="POST" enctype="multipart/form-data" id="addProjectForm">
                        @csrf
                        <div class="row">
                            <!-- Titre du projet -->
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">
                                    <i class="fas fa-tag me-1" style="color: var(--primary-color);"></i>
                                    <strong>Titre du projet</strong> <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control" id="title" name="title" required maxlength="255"
                                       placeholder="Ex: Logo pour entreprise tech">
                                <div class="form-text">
                                    <span id="titleCounter">0</span>/255 caractères
                                </div>
                            </div>

                            <!-- Type de projet -->
                            <div class="col-md-6 mb-3">
                                <label for="project_type" class="form-label">
                                    <i class="fas fa-layer-group me-1" style="color: var(--secondary-color);"></i>
                                        <strong>Type de projet</strong> <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="project_type" name="project_type" required>
                                    <option value="">Choisir un type...</option>
                                    <option value="logo">Logo & Identité visuelle</option>
                                    <option value="web">Design Web</option>
                                    <option value="print">Design Print</option>
                                    <option value="packaging">Packaging</option>
                                    <option value="illustration">Illustration</option>
                                    <option value="motion">Motion Design</option>
                                    <option value="strategy">Strategy Business</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Description -->
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1" style="color: var(--accent-color);"></i>
                                    <strong>Description du projet</strong>
                                </label>
                                <textarea class="form-control" id="description" name="description" rows="4" maxlength="2000"
                                          placeholder="Décrivez votre projet, les objectifs, le public cible, les contraintes..."></textarea>
                                <div class="form-text">
                                    <span id="descCounter">0</span>/2000 caractères
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Logiciels utilisés -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-laptop-code me-1" style="color: var(--warning-color);"></i>
                                    <strong>Logiciels utilisés</strong>
                                </label>
                                <div class="software-checkboxes">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="photoshop" id="software_photoshop">
                                                <label class="form-check-label" for="software_photoshop">
                                                    <i class="fab fa-adobe text-primary me-1"></i>
                                                    Adobe Photoshop
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="illustrator" id="software_illustrator">
                                                <label class="form-check-label" for="software_illustrator">
                                                    <i class="fab fa-adobe text-warning me-1"></i>
                                                    Adobe Illustrator
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="indesign" id="software_indesign">
                                                <label class="form-check-label" for="software_indesign">
                                                    <i class="fab fa-adobe text-danger me-1"></i>
                                                    Adobe InDesign
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="after_effects" id="software_after_effects">
                                                <label class="form-check-label" for="software_after_effects">
                                                    <i class="fab fa-adobe text-info me-1"></i>
                                                    Adobe After Effects
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="premiere_pro" id="software_premiere_pro">
                                                <label class="form-check-label" for="software_premiere_pro">
                                                    <i class="fab fa-adobe text-purple me-1"></i>
                                                    Adobe Premiere Pro
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="xd" id="software_xd">
                                                <label class="form-check-label" for="software_xd">
                                                    <i class="fab fa-adobe text-success me-1"></i>
                                                    Adobe XD
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="figma" id="software_figma">
                                                <label class="form-check-label" for="software_figma">
                                                    <i class="fab fa-figma text-primary me-1"></i>
                                                    Figma
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="sketch" id="software_sketch">
                                                <label class="form-check-label" for="software_sketch">
                                                    <i class="fas fa-pencil-ruler text-warning me-1"></i>
                                                    Sketch
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="canva" id="software_canva">
                                                <label class="form-check-label" for="software_canva">
                                                    <i class="fas fa-paint-brush text-info me-1"></i>
                                                    Canva
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="software_used[]" value="other" id="software_other">
                                                <label class="form-check-label" for="software_other">
                                                    <i class="fas fa-plus-circle text-secondary me-1"></i>
                                                    Autre
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">Sélectionnez tous les logiciels que vous utiliserez pour ce projet</div>
                            </div>

                            <!-- Type de projet (Solo/Groupe) -->
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">
                                    <i class="fas fa-users me-1" style="color: var(--accent-color);"></i>
                                    <strong>Catégorie de projet</strong>
                                </label>
                                <select class="form-select" id="category" name="category">
                                    <option value="solo" selected>PROJET Solo</option>
                                    <option value="groupe">PROJET Groupe</option>
                                </select>
                            </div>
                        </div>

                        <!-- Zone de téléchargement de fichiers -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-cloud-upload-alt me-1" style="color: var(--primary-color);"></i>
                                <strong>Fichiers du projet</strong>
                            </label>
                            <div class="file-drop-zone" id="fileDropZone">
                                <div class="text-center p-4">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: var(--primary-color); opacity: 0.5;"></i>
                                    <p class="mb-2">Glissez-déposez vos fichiers ici ou</p>
                                    <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('projectFiles').click()">
                                        <i class="fas fa-folder-open me-1"></i>
                                        Parcourir les fichiers
                                    </button>
                                    <input type="file" id="projectFiles" name="files[]" multiple style="display: none;"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.ai,.psd,.eps,.svg,.zip,.rar">
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            Formats acceptés: PDF, DOC, DOCX, Images, AI, PSD, EPS, SVG, ZIP, RAR<br>
                                            Taille max: 10MB par fichier
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div id="filesList" class="mt-3"></div>
                        </div>

                        <!-- URL de référence -->
                        <div class="mb-3">
                            <label for="reference_url" class="form-label">
                                <i class="fas fa-link me-1" style="color: var(--secondary-color);"></i>
                                <strong>URL de référence (optionnel)</strong>
                            </label>
                            <input type="url" class="form-control" id="reference_url" name="reference_url"
                                   placeholder="https://exemple.com/inspiration">
                            <div class="form-text">Lien vers des références, inspirations ou brief client</div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="saveAsDraft" name="save_as_draft">
                                <label class="form-check-label" for="saveAsDraft">
                                    <strong>Enregistrer comme brouillon</strong>
                                </label>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary me-2" id="resetFormBtn">
                                    <i class="fas fa-undo me-1"></i>
                                    Réinitialiser
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Créer le Projet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
    /* Styles pour le formulaire de projet */
    .file-drop-zone {
        border: 2px dashed #ddd;
        border-radius: 10px;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
    }

    .file-drop-zone:hover,
    .file-drop-zone.dragover {
        border-color: var(--primary-color);
        background-color: rgba(0, 51, 102, 0.05);
    }

    .file-drop-zone.dragover {
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(0, 51, 102, 0.1);
    }

    .file-item {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .file-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .file-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
    }

    .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        color: white;
        font-size: 16px;
    }

    .file-icon.pdf { background-color: #dc3545; }
    .file-icon.image { background-color: #28a745; }
    .file-icon.doc { background-color: #007bff; }
    .file-icon.archive { background-color: #6f42c1; }
    .file-icon.design { background-color: #fd7e14; }
    .file-icon.default { background-color: #6c757d; }

    .file-details h6 {
        margin: 0;
        font-size: 14px;
        color: #333;
    }

    .file-details small {
        color: #666;
        font-size: 12px;
    }

    .remove-file {
        background: none;
        border: none;
        color: #dc3545;
        font-size: 18px;
        cursor: pointer;
        padding: 5px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .remove-file:hover {
        background-color: #dc3545;
        color: white;
        transform: scale(1.1);
    }

    /* Animation pour les compteurs de caractères */
    .form-text {
        transition: color 0.3s ease;
    }

    .form-text.warning {
        color: var(--warning-color) !important;
    }

    .form-text.danger {
        color: var(--accent-color) !important;
    }

    /* Animation pour le toggle du formulaire */
    #projectForm {
        transition: all 0.5s ease;
    }

    #toggleIcon {
        transition: transform 0.3s ease;
    }

    #toggleIcon.rotated {
        transform: rotate(180deg);
    }

    /* Styles pour les champs requis */
    .form-control:invalid,
    .form-select:invalid {
        border-color: #dc3545;
    }

    .form-control:valid,
    .form-select:valid {
        border-color: #28a745;
    }

    /* Animation pour le bouton de soumission */
    .btn-primary {
        position: relative;
        overflow: hidden;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-primary:hover::before {
        left: 100%;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .file-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .file-info {
            width: 100%;
            margin-bottom: 10px;
        }

        .remove-file {
            align-self: flex-end;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables globales
        let selectedFiles = [];
        const maxFileSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml',
            'application/postscript', // AI files
            'image/vnd.adobe.photoshop', // PSD files
            'application/zip', 'application/x-rar-compressed'
        ];

        // Toggle du formulaire
        const toggleBtn = document.getElementById('toggleFormBtn');
        const projectForm = document.getElementById('projectForm');
        const toggleIcon = document.getElementById('toggleIcon');

        toggleBtn.addEventListener('click', function() {
            if (projectForm.style.display === 'none') {
                projectForm.style.display = 'block';
                toggleIcon.classList.add('rotated');
                setTimeout(() => {
                    projectForm.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 300);
            } else {
                projectForm.style.display = 'none';
                toggleIcon.classList.remove('rotated');
            }
        });

        // Compteurs de caractères
        const titleInput = document.getElementById('title');
        const descInput = document.getElementById('description');
        const titleCounter = document.getElementById('titleCounter');
        const descCounter = document.getElementById('descCounter');

        function updateCounter(input, counter, max) {
            const length = input.value.length;
            counter.textContent = length;

            const parent = counter.closest('.form-text');
            parent.classList.remove('warning', 'danger');

            if (length > max * 0.9) {
                parent.classList.add('danger');
            } else if (length > max * 0.7) {
                parent.classList.add('warning');
            }
        }

        titleInput.addEventListener('input', () => updateCounter(titleInput, titleCounter, 255));
        descInput.addEventListener('input', () => updateCounter(descInput, descCounter, 2000));

        // Gestion des fichiers
        const fileDropZone = document.getElementById('fileDropZone');
        const fileInput = document.getElementById('projectFiles');
        const filesList = document.getElementById('filesList');

        // Drag & Drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight(e) {
            fileDropZone.classList.add('dragover');
        }

        function unhighlight(e) {
            fileDropZone.classList.remove('dragover');
        }

        fileDropZone.addEventListener('drop', handleDrop, false);
        fileInput.addEventListener('change', handleFileSelect, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }

        function handleFileSelect(e) {
            const files = e.target.files;
            handleFiles(files);
        }

        function handleFiles(files) {
            [...files].forEach(file => {
                if (validateFile(file)) {
                    addFile(file);
                }
            });
            updateFileInput();
        }

        function validateFile(file) {
            // Vérifier la taille
            if (file.size > maxFileSize) {
                showAlert(`Le fichier "${file.name}" est trop volumineux (max 10MB)`, 'danger');
                return false;
            }

            // Vérifier le type
            if (!allowedTypes.includes(file.type) && !isValidExtension(file.name)) {
                showAlert(`Le type de fichier "${file.name}" n'est pas autorisé`, 'danger');
                return false;
            }

            // Vérifier les doublons
            if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                showAlert(`Le fichier "${file.name}" est déjà ajouté`, 'warning');
                return false;
            }

            return true;
        }

        function isValidExtension(filename) {
            const validExtensions = ['.ai', '.psd', '.eps', '.svg'];
            return validExtensions.some(ext => filename.toLowerCase().endsWith(ext));
        }

        function addFile(file) {
            selectedFiles.push(file);
            displayFile(file, selectedFiles.length - 1);
        }

        function displayFile(file, index) {
            const fileItem = document.createElement('div');
            fileItem.className = 'file-item';
            fileItem.innerHTML = `
                <div class="file-info">
                    <div class="file-icon ${getFileIconClass(file)}">
                        <i class="fas ${getFileIcon(file)}"></i>
                    </div>
                    <div class="file-details">
                        <h6>${file.name}</h6>
                        <small>${formatFileSize(file.size)} • ${getFileTypeLabel(file)}</small>
                    </div>
                </div>
                <button type="button" class="remove-file" onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
            filesList.appendChild(fileItem);
        }

        function getFileIconClass(file) {
            if (file.type.startsWith('image/')) return 'image';
            if (file.type === 'application/pdf') return 'pdf';
            if (file.type.includes('word') || file.type.includes('document')) return 'doc';
            if (file.type.includes('zip') || file.type.includes('rar')) return 'archive';
            if (file.name.toLowerCase().match(/\.(ai|psd|eps)$/)) return 'design';
            return 'default';
        }

        function getFileIcon(file) {
            if (file.type.startsWith('image/')) return 'fa-image';
            if (file.type === 'application/pdf') return 'fa-file-pdf';
            if (file.type.includes('word') || file.type.includes('document')) return 'fa-file-word';
            if (file.type.includes('zip') || file.type.includes('rar')) return 'fa-file-archive';
            if (file.name.toLowerCase().match(/\.(ai|psd|eps)$/)) return 'fa-palette';
            return 'fa-file';
        }

        function getFileTypeLabel(file) {
            if (file.type.startsWith('image/')) return 'Image';
            if (file.type === 'application/pdf') return 'PDF';
            if (file.type.includes('word') || file.type.includes('document')) return 'Document';
            if (file.type.includes('zip') || file.type.includes('rar')) return 'Archive';
            if (file.name.toLowerCase().endsWith('.ai')) return 'Adobe Illustrator';
            if (file.name.toLowerCase().endsWith('.psd')) return 'Adobe Photoshop';
            if (file.name.toLowerCase().endsWith('.eps')) return 'EPS';
            return 'Fichier';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Fonction globale pour supprimer un fichier
        window.removeFile = function(index) {
            selectedFiles.splice(index, 1);
            refreshFilesList();
            updateFileInput();
        };

        function refreshFilesList() {
            filesList.innerHTML = '';
            selectedFiles.forEach((file, index) => {
                displayFile(file, index);
            });
        }

        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => {
                dt.items.add(file);
            });
            fileInput.files = dt.files;
        }

        // Réinitialiser le formulaire
        document.getElementById('resetFormBtn').addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir réinitialiser le formulaire ?')) {
                document.getElementById('addProjectForm').reset();
                selectedFiles = [];
                filesList.innerHTML = '';
                titleCounter.textContent = '0';
                descCounter.textContent = '0';
                document.querySelectorAll('.form-text').forEach(el => {
                    el.classList.remove('warning', 'danger');
                });
            }
        });

        // Validation du formulaire avant soumission
        function validateForm() {
            const title = document.getElementById('title').value.trim();
            const projectType = document.getElementById('project_type').value;
            const projectMode = document.getElementById('category').value;
            const saveAsDraft = document.getElementById('save_as_draft').checked;
            const fileInput = document.getElementById('files');

            if (!title) {
                showError('Le titre du projet est requis');
                return false;
            }

            if (!projectType) {
                showError('Le type de projet est requis');
                return false;
            }

            if (!projectMode) {
                showError('Le mode de projet est requis');
                return false;
            }

            // Vérifier si au moins une image est présente pour la publication
            if (!saveAsDraft) {
                const hasFiles = fileInput.files && fileInput.files.length > 0;
                if (!hasFiles) {
                    showError('Au moins une image est requise pour publier un projet. Cochez "Sauvegarder en brouillon" si vous souhaitez sauvegarder sans image.');
                    return false;
                }
            }

            return true;
        }

        // Validation du formulaire
        document.getElementById('addProjectForm').addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            } else {
                const submitBtn = e.target.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Création en cours...';
                submitBtn.disabled = true;
            }
        });

        // Fonction d'alerte
        function showAlert(message, type = 'info') {
            // Créer l'alerte
            const alert = document.createElement('div');
            alert.className = `alert alert-${type} alert-dismissible fade show`;
            alert.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            // Insérer au début du formulaire
            const form = document.getElementById('addProjectForm');
            form.insertBefore(alert, form.firstChild);

            // Auto-suppression après 5 secondes
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 5000);
        }

        // Fonction d'erreur (alias pour showAlert avec type danger)
        function showError(message) {
            showAlert(message, 'danger');
        }

        // Fonction pour faire défiler vers une section
        function scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
                // Ajouter une classe de surbrillance temporaire
                element.classList.add('highlight-section');
                setTimeout(() => {
                    element.classList.remove('highlight-section');
                }, 2000);
            }
        }

        // Fonctions pour les actions sur les projets
        function viewProject(projectId) {
            window.location.href = `/evc/compte/design-graphique/projets/${projectId}`;
        }

        function editProject(projectId) {
            // Pour l'instant, rediriger vers la vue (à implémenter plus tard)
            showAlert('Fonctionnalité d\'édition en cours de développement', 'info');
        }

        function manageCollaborators(projectId) {
            // Pour l'instant, afficher un message (à implémenter plus tard)
            showAlert('Gestion des collaborateurs en cours de développement', 'info');
        }

        // Styles CSS pour la surbrillance des sections
        const style = document.createElement('style');
        style.textContent = `
            .highlight-section {
                animation: highlightPulse 2s ease-in-out;
                border: 2px solid var(--primary-color);
                border-radius: 8px;
            }

            @keyframes highlightPulse {
                0% { box-shadow: 0 0 0 0 rgba(0, 51, 102, 0.4); }
                50% { box-shadow: 0 0 0 10px rgba(0, 51, 102, 0.1); }
                100% { box-shadow: 0 0 0 0 rgba(0, 51, 102, 0); }
            }
        `;
        document.head.appendChild(style);
    });
    </script>

    <!-- Section Projets Solo -->
    <div class="row mb-4" id="solo-projects">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2" style="color: var(--primary-color);"></i>
                        Projets Solo
                    </h5>
                    <span class="badge" style="background-color: var(--primary-color); color: white;">{{ count($soloProjects) }} projet{{ count($soloProjects) > 1 ? 's' : '' }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Projet</th>
                                    <th>Type</th>
                                    <th>Logiciels</th>
                                    <th>Fichiers</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($soloProjects as $project)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $project['title'] }}</strong>
                                            @if($project['description'])
                                                <br><small class="text-muted">{{ Str::limit($project['description'], 50) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($project['project_type']) }}</span>
                                    </td>
                                    <td>
                                        @if(isset($project['software_used_array']) && !empty($project['software_used_array']))
                                            @foreach(array_slice($project['software_used_array'], 0, 2) as $software)
                                                <span class="badge bg-secondary me-1">{{ ucfirst($software) }}</span>
                                            @endforeach
                                            @if(isset($project['software_used_array']) && count($project['software_used_array']) > 2)
                                                <span class="badge bg-light text-dark">+{{ count($project['software_used_array']) - 2 }}</span>
                                            @endif
                                        @else
                                            <small class="text-muted">Non spécifié</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $project['files_count'] }} fichier{{ $project['files_count'] > 1 ? 's' : '' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-secondary',
                                                'active' => 'bg-warning',
                                                'completed' => 'bg-info',
                                                'validated' => 'bg-success',
                                                'cancelled' => 'bg-danger'
                                            ];
                                            $statusLabels = [
                                                'draft' => 'Brouillon',
                                                'active' => 'En cours',
                                                'completed' => 'Terminé',
                                                'validated' => 'Projet validé avec succès',
                                                'cancelled' => 'Annulé'
                                            ];
                                        @endphp
                                        @if($project['status'] === 'validated')
                                        <span style="display: inline-block; padding: 8px 16px; background-color: #198754; color: white; border-radius: 6px; font-size: 0.85em; font-weight: bold; text-align: center; white-space: nowrap;">
                                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Projet validé avec succès
                                        </span>
                                        @else
                                        <span class="badge {{ $statusColors[$project['status']] ?? 'bg-secondary' }}">
                                            {{ $statusLabels[$project['status']] ?? $project['status'] }}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('design-graphique.projets.show', $project['id']) }}"
                                           class="btn btn-sm btn-primary me-1" title="Voir le projet">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($project['status'] !== 'completed' && $project['status'] !== 'validated')
                                        <a href="{{ route('design-graphique.projets.edit', $project['id']) }}"
                                           class="btn btn-sm btn-success me-1" title="Modifier le projet">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if($project['status'] !== 'validated' && $project['status'] !== 'completed')
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $project['id'] }}, '{{ addslashes($project['title']) }}')"
                                                title="Supprimer le projet">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2"></i>
                                        <br>Aucun projet solo pour le moment
                                        <br><small>Créez votre premier projet ci-dessus !</small>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Solo Projects -->
                    @if($soloPagination['total_pages'] > 1)
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Affichage {{ (($soloPagination['current_page'] - 1) * $soloPagination['per_page']) + 1 }} à
                            {{ min($soloPagination['current_page'] * $soloPagination['per_page'], $soloPagination['total_items']) }}
                            sur {{ $soloPagination['total_items'] }} projets
                        </div>
                        <nav aria-label="Pagination projets solo">
                            <ul class="pagination pagination-sm mb-0">
                                @if($soloPagination['has_prev'])
                                    <li class="page-item">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['solo_page' => $soloPagination['current_page'] - 1]) }}">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                    </li>
                                @endif

                                @for($i = max(1, $soloPagination['current_page'] - 2); $i <= min($soloPagination['total_pages'], $soloPagination['current_page'] + 2); $i++)
                                    <li class="page-item {{ $i == $soloPagination['current_page'] ? 'active' : '' }}">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['solo_page' => $i]) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if($soloPagination['has_next'])
                                    <li class="page-item">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['solo_page' => $soloPagination['current_page'] + 1]) }}">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Section Projets Groupe -->
    <div class="row mb-4" id="group-projects">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2" style="color: var(--secondary-color);"></i>
                        Projets Groupe
                    </h5>
                    <span class="badge" style="background-color: var(--secondary-color); color: white;">{{ count($groupProjects) }} projet{{ count($groupProjects) > 1 ? 's' : '' }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Projet</th>
                                    <th>Type</th>
                                    <th>Logiciels</th>
                                    <th>Fichiers</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($groupProjects as $project)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $project['title'] }}</strong>
                                            @if($project['description'])
                                                <br><small class="text-muted">{{ Str::limit($project['description'], 50) }}</small>
                                            @endif
                                            <br><small class="badge bg-warning text-dark"><i class="fas fa-users me-1"></i>Projet Groupe</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($project['project_type']) }}</span>
                                    </td>
                                    <td>
                                        @if(isset($project['software_used_array']) && !empty($project['software_used_array']))
                                            @foreach(array_slice($project['software_used_array'], 0, 2) as $software)
                                                <span class="badge bg-secondary me-1">{{ ucfirst($software) }}</span>
                                            @endforeach
                                            @if(isset($project['software_used_array']) && count($project['software_used_array']) > 2)
                                                <span class="badge bg-light text-dark">+{{ count($project['software_used_array']) - 2 }}</span>
                                            @endif
                                        @else
                                            <small class="text-muted">Non spécifié</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $project['files_count'] }} fichier{{ $project['files_count'] > 1 ? 's' : '' }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-secondary',
                                                'active' => 'bg-warning',
                                                'completed' => 'bg-info',
                                                'validated' => 'bg-success',
                                                'cancelled' => 'bg-danger'
                                            ];
                                            $statusLabels = [
                                                'draft' => 'Brouillon',
                                                'active' => 'En cours',
                                                'completed' => 'Terminé',
                                                'validated' => 'Projet validé avec succès',
                                                'cancelled' => 'Annulé'
                                            ];
                                        @endphp
                                        @if($project['status'] === 'validated')
                                        <span style="display: inline-block; padding: 8px 16px; background-color: #198754; color: white; border-radius: 6px; font-size: 0.85em; font-weight: bold; text-align: center; white-space: nowrap;">
                                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>Projet validé avec succès
                                        </span>
                                        @else
                                        <span class="badge {{ $statusColors[$project['status']] ?? 'bg-secondary' }}">
                                            {{ $statusLabels[$project['status']] ?? $project['status'] }}
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('design-graphique.projets.show', $project['id']) }}"
                                           class="btn btn-sm btn-primary me-1" title="Voir le projet">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($project['status'] !== 'completed' && $project['status'] !== 'validated')
                                        <a href="{{ route('design-graphique.projets.edit', $project['id']) }}"
                                           class="btn btn-sm btn-success me-1" title="Modifier le projet">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @endif
                                        @if($project['status'] !== 'validated' && $project['status'] !== 'completed')
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $project['id'] }}, '{{ addslashes($project['title']) }}')"
                                                title="Supprimer le projet">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <br>Aucun projet groupe pour le moment
                                        <br><small>Créez votre premier projet groupe ci-dessus !</small>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Group Projects -->
                    @if($groupPagination['total_pages'] > 1)
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted small">
                            Affichage {{ (($groupPagination['current_page'] - 1) * $groupPagination['per_page']) + 1 }} à
                            {{ min($groupPagination['current_page'] * $groupPagination['per_page'], $groupPagination['total_items']) }}
                            sur {{ $groupPagination['total_items'] }} projets
                        </div>
                        <nav aria-label="Pagination projets groupe">
                            <ul class="pagination pagination-sm mb-0">
                                @if($groupPagination['has_prev'])
                                    <li class="page-item">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['group_page' => $groupPagination['current_page'] - 1]) }}">
                                            <i class="fas fa-chevron-left"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                    </li>
                                @endif

                                @for($i = max(1, $groupPagination['current_page'] - 2); $i <= min($groupPagination['total_pages'], $groupPagination['current_page'] + 2); $i++)
                                    <li class="page-item {{ $i == $groupPagination['current_page'] ? 'active' : '' }}">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['group_page' => $i]) }}">{{ $i }}</a>
                                    </li>
                                @endfor

                                @if($groupPagination['has_next'])
                                    <li class="page-item">
                                        <a class="page-link" href="{{ request()->fullUrlWithQuery(['group_page' => $groupPagination['current_page'] + 1]) }}">
                                            <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer le projet <strong id="projectTitle"></strong> ?</p>
                <p class="text-danger"><small>Cette action est irréversible et supprimera également tous les fichiers associés.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer définitivement</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction de confirmation de suppression
function confirmDelete(projectId, projectTitle) {
    document.getElementById('projectTitle').textContent = projectTitle;
    document.getElementById('deleteForm').action = `/evc/compte/design-graphique/projets/${projectId}`;

    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Fonction pour gérer les collaborateurs (placeholder)
function manageCollaborators(projectId) {
    alert('Fonctionnalité de gestion des collaborateurs en cours de développement.');
}

// Fonction pour faire défiler vers une section
function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });

        // Ajouter un effet de surbrillance temporaire
        element.style.transition = 'background-color 0.3s ease';
        element.style.backgroundColor = 'rgba(0, 123, 255, 0.1)';

        setTimeout(() => {
            element.style.backgroundColor = '';
        }, 1500);
    }
}

// Fonction pour afficher les alertes
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

// Fonction pour afficher les erreurs
function showError(message) {
    showAlert(message, 'danger');
}
</script>
@endsection
