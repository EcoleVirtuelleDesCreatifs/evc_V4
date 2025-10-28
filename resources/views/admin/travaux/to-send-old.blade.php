@extends('layouts.admin')

@section('title', 'Envoyer des TP aux Étudiants')

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
/* Variables CSS pour cohérence */
:root {
    --primary-cyan: #26c6da;
    --primary-cyan-dark: #00acc1;
    --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.08);
    --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.12);
    --shadow-lg: 0 8px 24px rgba(38, 198, 218, 0.25);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --border-radius: 20px;
}

/* Animations globales */
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

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

/* Header moderne avec glassmorphism */
.modern-header {
    background: linear-gradient(135deg, var(--primary-cyan) 0%, var(--primary-cyan-dark) 100%);
    border-radius: var(--border-radius);
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.6s ease;
}

.modern-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    opacity: 0.3;
}

.header-icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    animation: pulse 2s ease-in-out infinite;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

/* Cartes statistiques modernes */
.stat-card {
    border: none;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    height: 100%;
    transition: var(--transition);
    background: white;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
    animation: fadeInUp 0.6s ease;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(180deg, var(--primary-cyan), var(--primary-cyan-dark));
    transform: scaleY(0);
    transform-origin: top;
    transition: transform 0.4s ease;
}

.stat-card:hover::before {
    transform: scaleY(1);
}

.stat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: var(--shadow-lg);
}

.stat-card .stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    transition: var(--transition);
}

.stat-card:hover .stat-icon {
    transform: rotate(10deg) scale(1.1);
}

/* Carte formulaire moderne */
.modern-form-card {
    border: none;
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    animation: fadeInUp 0.8s ease;
}

.modern-form-card .card-header {
    background: linear-gradient(135deg, rgba(38, 198, 218, 0.1) 0%, rgba(0, 172, 193, 0.05) 100%);
    border-bottom: 2px solid rgba(38, 198, 218, 0.2);
    padding: 1.5rem 2rem;
}

.modern-form-card .card-body {
    padding: 2.5rem;
}

/* Champs de formulaire modernes */
.modern-input-group {
    margin-bottom: 2rem;
    animation: slideIn 0.6s ease;
}

.modern-label {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
    letter-spacing: 0.3px;
}

.modern-label i {
    margin-right: 0.5rem;
    color: var(--primary-cyan);
    font-size: 1.1rem;
}

.modern-input {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.875rem 1.25rem;
    font-size: 0.95rem;
    transition: var(--transition);
    background: #f8fafc;
}

.modern-input:focus {
    border-color: var(--primary-cyan);
    box-shadow: 0 0 0 4px rgba(38, 198, 218, 0.1);
    background: white;
    transform: translateY(-2px);
}

.modern-input:hover:not(:focus) {
    border-color: #cbd5e0;
}

/* Quill Editor moderne */
#quill-editor {
    background: white;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    transition: var(--transition);
}

#quill-editor:focus-within {
    border-color: var(--primary-cyan);
    box-shadow: 0 0 0 4px rgba(38, 198, 218, 0.1);
}

.ql-container {
    min-height: 300px;
    font-size: 15px;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.ql-editor {
    min-height: 300px;
    padding: 1.5rem;
}

.ql-toolbar {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0 !important;
}

/* Zone d'upload ultra moderne */
.upload-zone {
    border: 3px dashed var(--primary-cyan);
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(38, 198, 218, 0.05) 0%, rgba(0, 172, 193, 0.08) 100%);
    padding: 3rem 2rem;
    text-align: center;
    transition: var(--transition);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.upload-zone::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent 30%,
        rgba(38, 198, 218, 0.1) 50%,
        transparent 70%
    );
    animation: shimmer 3s infinite;
    pointer-events: none;
}

.upload-zone:hover {
    border-color: var(--primary-cyan-dark);
    background: linear-gradient(135deg, rgba(38, 198, 218, 0.12) 0%, rgba(0, 172, 193, 0.15) 100%);
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 12px 32px rgba(38, 198, 218, 0.25);
}

.upload-zone.drag-over {
    border-color: var(--primary-cyan-dark);
    border-style: solid;
    background: linear-gradient(135deg, rgba(38, 198, 218, 0.2) 0%, rgba(0, 172, 193, 0.25) 100%);
    transform: scale(1.03);
    box-shadow: 0 16px 40px rgba(38, 198, 218, 0.35);
}

.upload-icon {
    font-size: 5rem;
    background: linear-gradient(135deg, var(--primary-cyan), var(--primary-cyan-dark));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 1.5rem;
    animation: float 3s ease-in-out infinite;
    filter: drop-shadow(0 4px 8px rgba(38, 198, 218, 0.3));
}

.upload-zone-content h5 {
    color: #2d3748;
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
}

.upload-zone-content p {
    color: #64748b;
    font-size: 0.95rem;
}

.upload-zone-info {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 2px solid rgba(38, 198, 218, 0.2);
}

/* Aperçu des fichiers moderne */
.files-preview-container {
    margin-top: 1.5rem;
}

.file-item {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    transition: var(--transition);
    animation: slideIn 0.4s ease;
    position: relative;
    overflow: hidden;
}

.file-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, var(--primary-cyan), var(--primary-cyan-dark));
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.file-item:hover::before {
    transform: scaleY(1);
}

.file-item:hover {
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    transform: translateY(-4px) translateX(4px);
    border-color: var(--primary-cyan);
}

.file-icon {
    font-size: 2.5rem;
    margin-right: 1.25rem;
    min-width: 60px;
    text-align: center;
    transition: var(--transition);
}

.file-item:hover .file-icon {
    transform: scale(1.2) rotate(5deg);
}

.file-icon.image {
    color: #10b981;
}

.file-icon.pdf {
    color: #ef4444;
}

.file-info {
    flex-grow: 1;
}

.file-name {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.35rem;
    word-break: break-word;
    font-size: 0.95rem;
}

.file-size {
    color: #64748b;
    font-size: 0.85rem;
}

.file-remove {
    margin-left: 1rem;
    transition: var(--transition);
}

.file-remove:hover {
    transform: scale(1.2) rotate(90deg);
}

/* Badges modernes */
.modern-badge {
    padding: 0.5rem 1rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 0.9rem;
    backdrop-filter: blur(10px);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

/* Boutons modernes */
.modern-btn {
    border: none;
    border-radius: 12px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    font-size: 0.95rem;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    position: relative;
    overflow: hidden;
}

.modern-btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.modern-btn:hover::before {
    width: 300px;
    height: 300px;
}

.modern-btn-primary {
    background: linear-gradient(135deg, var(--primary-cyan) 0%, var(--primary-cyan-dark) 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(38, 198, 218, 0.3);
}

.modern-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(38, 198, 218, 0.5);
}

.modern-btn-secondary {
    background: white;
    color: var(--primary-cyan);
    border: 2px solid var(--primary-cyan);
}

.modern-btn-secondary:hover {
    background: var(--primary-cyan);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 24px rgba(38, 198, 218, 0.3);
}

/* Section guide */
.guide-section {
    background: linear-gradient(135deg, rgba(38, 198, 218, 0.05) 0%, rgba(0, 172, 193, 0.08) 100%);
    border-radius: var(--border-radius);
    padding: 2rem;
    margin-top: 2rem;
    border: 2px solid rgba(38, 198, 218, 0.2);
    animation: fadeInUp 1s ease;
}

.guide-item {
    display: flex;
    align-items: start;
    margin-bottom: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 12px;
    transition: var(--transition);
}

.guide-item:hover {
    transform: translateX(8px);
    box-shadow: var(--shadow-sm);
}

.guide-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-cyan), var(--primary-cyan-dark));
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-right: 1rem;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(38, 198, 218, 0.3);
}

/* Alert moderne */
.modern-alert {
    border-radius: 15px;
    border: none;
    padding: 1.25rem;
    background: linear-gradient(135deg, rgba(38, 198, 218, 0.1) 0%, rgba(0, 172, 193, 0.05) 100%);
    border-left: 4px solid var(--primary-cyan);
    animation: slideIn 0.5s ease;
}

/* Select multiple moderne */
.modern-select {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.75rem;
    transition: var(--transition);
    background: #f8fafc;
}

.modern-select:focus {
    border-color: var(--primary-cyan);
    box-shadow: 0 0 0 4px rgba(38, 198, 218, 0.1);
    background: white;
}

.modern-select option {
    padding: 0.75rem;
    margin: 0.25rem 0;
    border-radius: 8px;
}

.modern-select option:checked {
    background: linear-gradient(135deg, var(--primary-cyan), var(--primary-cyan-dark));
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-header {
        padding: 1.5rem;
    }
    
    .header-icon-circle {
        width: 60px;
        height: 60px;
        font-size: 1.8rem;
    }
    
    .modern-form-card .card-body {
        padding: 1.5rem;
    }
    
    .upload-zone {
        padding: 2rem 1rem;
    }
    
    .upload-icon {
        font-size: 3.5rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.8s linear infinite;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Moderne -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="modern-header">
                <div class="d-flex justify-content-between align-items-center position-relative" style="z-index: 1;">
                    <div class="d-flex align-items-center gap-4">
                        <div class="header-icon-circle">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <div class="text-white">
                            <h1 class="mb-2 fw-bold" style="font-size: 2rem;">
                                Envoyer des TP aux Étudiants
                            </h1>
                            <p class="mb-0 opacity-90" style="font-size: 1.05rem;">
                                <i class="fas fa-magic me-2"></i>
                                Créez et diffusez des travaux pratiques ciblés par formation
                            </p>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="modern-badge bg-white text-primary" style="font-size: 1.1rem;">
                            <i class="fas fa-users"></i>
                            <span class="fw-bold">{{ $stats['total_students'] }}</span>
                            <span>Étudiants</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par formation - Cartes Modernes -->
    <div class="row mb-5 g-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal" style="font-size: 0.9rem;">Design Graphique</h6>
                        <h2 class="mb-0 fw-bold" style="color: #1e3c72; font-size: 2.5rem;">{{ $stats['design_graphique'] }}</h2>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-user-check me-1"></i>
                            Étudiants actifs
                        </small>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                        <i class="fas fa-paint-brush text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal" style="font-size: 0.9rem;">Community Management</h6>
                        <h2 class="mb-0 fw-bold" style="color: #4fc3f7; font-size: 2.5rem;">{{ $stats['community_management'] }}</h2>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-user-check me-1"></i>
                            Étudiants actifs
                        </small>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4fc3f7, #29b6f6);">
                        <i class="fas fa-share-alt text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal" style="font-size: 0.9rem;">Gestion Informatique</h6>
                        <h2 class="mb-0 fw-bold" style="color: #ff9800; font-size: 2.5rem;">{{ $stats['gestion_informatique'] }}</h2>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-user-check me-1"></i>
                            Étudiants actifs
                        </small>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ff9800, #fb8c00);">
                        <i class="fas fa-laptop-code text-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted mb-2 fw-normal" style="font-size: 0.9rem;">Intelligence Artificielle</h6>
                        <h2 class="mb-0 fw-bold" style="color: #26c6da; font-size: 2.5rem;">{{ $stats['intelligence_artificielle'] }}</h2>
                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-user-check me-1"></i>
                            Étudiants actifs
                        </small>
                    </div>
                    <div class="stat-icon" style="background: linear-gradient(135deg, #26c6da, #00acc1);">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire d'envoi -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Créer et Envoyer un TP
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.travaux.send') }}" method="POST" id="sendTpForm">
                        @csrf

                        <!-- Titre du TP -->
                        <div class="mb-4">
                            <label for="tp_title" class="form-label fw-bold">
                                <i class="fas fa-heading me-2 text-primary"></i>
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

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="tp_description" class="form-label fw-bold">
                                <i class="fas fa-align-left me-2 text-primary"></i>
                                Description du TP <span class="text-danger">*</span>
                            </label>
                            <div id="quill-editor" style="background: white;">{{ old('tp_description') }}</div>
                            <input type="hidden" id="tp_description" name="tp_description" required>
                            @error('tp_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Utilisez l'éditeur pour formater votre texte (gras, italique, listes, liens, etc.)
                            </small>
                        </div>

                        <!-- Date limite -->
                        <div class="mb-4">
                            <label for="tp_deadline" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
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

                        <!-- Fichiers joints (Images et PDF) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-paperclip me-2 text-primary"></i>
                                Fichiers joints (optionnel)
                                <span class="badge bg-info ms-2" id="filesCount">0 fichier</span>
                            </label>
                            
                            <!-- Zone de drag & drop améliorée -->
                            <div class="upload-zone" id="dropZone">
                                <div class="upload-zone-content">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <h5 class="mb-2">Glissez-déposez vos fichiers ici</h5>
                                    <p class="text-muted mb-3">ou</p>
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
                                </div>
                                <div class="upload-zone-info">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <strong>Formats acceptés :</strong> JPG, PNG, GIF, PDF
                                        <span class="mx-2">•</span>
                                        <strong>Taille max :</strong> 5 Mo par fichier
                                        <span class="mx-2">•</span>
                                        <strong>Maximum :</strong> 10 fichiers
                                    </small>
                                </div>
                            </div>

                            <!-- Aperçu des fichiers avec design amélioré -->
                            <div id="filesPreview" class="files-preview-container"></div>
                            
                            @error('tp_files')
                                <div class="alert alert-danger mt-2">
                                    <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Type d'envoi -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-paper-plane me-2 text-primary"></i>
                                Type d'envoi <span class="text-danger">*</span>
                            </label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="send_type" id="send_type_group" value="group" checked>
                                <label class="btn btn-outline-primary" for="send_type_group">
                                    <i class="fas fa-users me-2"></i>Groupe / Formation
                                </label>
                                
                                <input type="radio" class="btn-check" name="send_type" id="send_type_individual" value="individual">
                                <label class="btn btn-outline-primary" for="send_type_individual">
                                    <i class="fas fa-user me-2"></i>Étudiant individuel
                                </label>
                            </div>
                        </div>

                        <!-- Sélection de la formation (pour groupe) -->
                        <div class="mb-4" id="formationSelectContainer">
                            <label for="formation" class="form-label fw-bold">
                                <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                Formation cible <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('formation') is-invalid @enderror" 
                                    id="formation" 
                                    name="formation">
                                <option value="">-- Sélectionnez une formation --</option>
                                <option value="all">📚 Toutes les formations</option>
                                <option value="Design Graphique">🎨 Design Graphique</option>
                                <option value="Community Management">📱 Community Management</option>
                                <option value="Gestion Informatique">💻 Gestion Informatique</option>
                                <option value="Intelligence Artificielle">🤖 Intelligence Artificielle</option>
                            </select>
                            @error('formation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Sélection d'étudiants individuels -->
                        <div class="mb-4" id="individualStudentContainer" style="display: none;">
                            <label for="individual_students" class="form-label fw-bold">
                                <i class="fas fa-user me-2 text-primary"></i>
                                Sélectionner un ou plusieurs étudiants <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" 
                                    id="individual_students" 
                                    name="individual_students[]"
                                    multiple
                                    size="10">
                                @foreach($studentsByFormation as $formation => $students)
                                    <optgroup label="{{ $formation }}">
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}">
                                                {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }}) - {{ $student->program }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small class="text-muted mt-1 d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                Maintenez <kbd>Ctrl</kbd> (ou <kbd>Cmd</kbd> sur Mac) pour sélectionner plusieurs étudiants
                            </small>
                        </div>

                        <!-- Sélection des étudiants spécifiques (optionnel) -->
                        <div class="mb-4" id="studentsSelectContainer" style="display: none;">
                            <label for="students" class="form-label fw-bold">
                                <i class="fas fa-users me-2 text-primary"></i>
                                Étudiants spécifiques (optionnel)
                            </label>
                            <select class="form-select" 
                                    id="students" 
                                    name="students[]" 
                                    multiple
                                    size="8">
                                @foreach($studentsByFormation as $formation => $students)
                                    <optgroup label="{{ $formation }}">
                                        @foreach($students as $student)
                                            <option value="{{ $student->id }}" data-formation="{{ $student->program }}">
                                                {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs étudiants. Laissez vide pour envoyer à tous les étudiants de la formation.
                            </small>
                        </div>

                        <!-- Aperçu des destinataires -->
                        <div class="alert alert-info mb-4" id="recipientsPreview">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Destinataires :</strong> <span id="recipientsCount">Sélectionnez une formation</span>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane me-2"></i>
                                Envoyer le TP
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quill.js CDN -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser Quill.js pour l'éditeur de description
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        },
        placeholder: 'Décrivez les objectifs, les consignes et les livrables attendus du TP...',
    });

    // Synchroniser le contenu de Quill avec le champ hidden
    const descriptionInput = document.getElementById('tp_description');
    quill.on('text-change', function() {
        descriptionInput.value = quill.root.innerHTML;
    });

    // Charger le contenu initial si présent (pour old())
    const oldContent = descriptionInput.value;
    if (oldContent) {
        quill.root.innerHTML = oldContent;
    }

    // ========== GESTION DES FICHIERS ==========
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('tp_files');
    const filesPreview = document.getElementById('filesPreview');
    let selectedFiles = [];

    // Clic sur la zone de drop
    dropZone.addEventListener('click', () => fileInput.click());

    // Drag & Drop
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#00acc1';
        dropZone.style.background = 'rgba(38, 198, 218, 0.15)';
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.style.borderColor = '#26c6da';
        dropZone.style.background = 'rgba(38, 198, 218, 0.05)';
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = '#26c6da';
        dropZone.style.background = 'rgba(38, 198, 218, 0.05)';
        handleFiles(e.dataTransfer.files);
    });

    // Sélection de fichiers
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });

    function handleFiles(files) {
        selectedFiles = Array.from(files);
        displayFilesPreview();
    }

    function displayFilesPreview() {
        if (selectedFiles.length === 0) {
            filesPreview.innerHTML = '';
            return;
        }

        let html = '<div class="row g-2">';
        selectedFiles.forEach((file, index) => {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            const isImage = file.type.startsWith('image/');
            const icon = isImage ? 'fa-image text-success' : 'fa-file-pdf text-danger';
            
            html += `
                <div class="col-md-6">
                    <div class="card border">
                        <div class="card-body p-2 d-flex align-items-center">
                            <i class="fas ${icon} fa-2x me-3"></i>
                            <div class="flex-grow-1">
                                <div class="fw-bold small">${file.name}</div>
                                <small class="text-muted">${fileSize} Mo</small>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(${index})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        filesPreview.innerHTML = html;
    }

    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        
        // Recréer un FileList pour l'input
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        
        displayFilesPreview();
    };

    // ========== GESTION TYPE D'ENVOI ==========
    const sendTypeGroup = document.getElementById('send_type_group');
    const sendTypeIndividual = document.getElementById('send_type_individual');
    const formationSelectContainer = document.getElementById('formationSelectContainer');
    const individualStudentContainer = document.getElementById('individualStudentContainer');
    const individualStudentsSelect = document.getElementById('individual_students');
    const formationSelect = document.getElementById('formation');
    const studentsSelectContainer = document.getElementById('studentsSelectContainer');
    const studentsSelect = document.getElementById('students');
    const recipientsCount = document.getElementById('recipientsCount');

    // Gérer le changement de type d'envoi
    sendTypeGroup.addEventListener('change', function() {
        if (this.checked) {
            formationSelectContainer.style.display = 'block';
            studentsSelectContainer.style.display = 'none';
            individualStudentContainer.style.display = 'none';
            formationSelect.required = true;
            individualStudentsSelect.required = false;
            recipientsCount.textContent = 'Sélectionnez une formation';
        }
    });

    sendTypeIndividual.addEventListener('change', function() {
        if (this.checked) {
            formationSelectContainer.style.display = 'none';
            studentsSelectContainer.style.display = 'none';
            individualStudentContainer.style.display = 'block';
            formationSelect.required = false;
            individualStudentsSelect.required = true;
            recipientsCount.textContent = 'Sélectionnez un ou plusieurs étudiants';
        }
    });

    // Quand des étudiants individuels sont sélectionnés
    individualStudentsSelect.addEventListener('change', function() {
        const selectedOptions = Array.from(this.selectedOptions);
        if (selectedOptions.length > 0) {
            if (selectedOptions.length === 1) {
                recipientsCount.textContent = `1 étudiant sélectionné : ${selectedOptions[0].text}`;
            } else {
                const names = selectedOptions.slice(0, 3).map(opt => {
                    const text = opt.text;
                    const name = text.split('(')[0].trim();
                    return name;
                }).join(', ');
                
                if (selectedOptions.length > 3) {
                    recipientsCount.textContent = `${selectedOptions.length} étudiants sélectionnés : ${names} et ${selectedOptions.length - 3} autre(s)`;
                } else {
                    recipientsCount.textContent = `${selectedOptions.length} étudiants sélectionnés : ${names}`;
                }
            }
        } else {
            recipientsCount.textContent = 'Sélectionnez un ou plusieurs étudiants';
        }
    });

    // Statistiques par formation
    const stats = {
        'all': {{ $stats['total_students'] }},
        'Design Graphique': {{ $stats['design_graphique'] }},
        'Community Management': {{ $stats['community_management'] }},
        'Gestion Informatique': {{ $stats['gestion_informatique'] }},
        'Intelligence Artificielle': {{ $stats['intelligence_artificielle'] }}
    };

    // Quand la formation change
    formationSelect.addEventListener('change', function() {
        const selectedFormation = this.value;
        
        if (selectedFormation && selectedFormation !== 'all') {
            // Afficher le sélecteur d'étudiants
            studentsSelectContainer.style.display = 'block';
            
            // Filtrer les options par formation
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
            
            // Mettre à jour le compteur
            updateRecipientsCount();
        } else if (selectedFormation === 'all') {
            studentsSelectContainer.style.display = 'none';
            recipientsCount.textContent = `Tous les étudiants (${stats['all']} étudiants)`;
        } else {
            studentsSelectContainer.style.display = 'none';
            recipientsCount.textContent = 'Sélectionnez une formation';
        }
    });

    // Quand la sélection d'étudiants change
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
        const formation = formationSelect.value;
        if (!formation) {
            e.preventDefault();
            alert('⚠️ Veuillez sélectionner une formation');
            formationSelect.focus();
            return false;
        }
        
        // Confirmation avant envoi
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
});
</script>

<style>
.form-control:focus, .form-select:focus {
    border-color: #26c6da;
    box-shadow: 0 0 0 0.2rem rgba(38, 198, 218, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(38, 198, 218, 0.4);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}

#students {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
}

#students option {
    padding: 8px;
    margin: 2px 0;
}

#students option:checked {
    background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    color: white;
}
</style>
@endsection
