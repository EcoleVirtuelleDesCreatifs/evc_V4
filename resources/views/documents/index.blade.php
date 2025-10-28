@extends('layouts.ki-admin')

@section('title', 'Mes Rapports - EVC 2024')
@section('page-title', 'Mes Rapports')

@push('styles')
<style>
    :root {
        --instagram-purple: #bc1888;
        --instagram-pink: #cc2366;
        --instagram-red: #dc2743;
        --instagram-orange: #e6683c;
        --instagram-yellow: #f09433;
    }
    
    .instagram-gradient {
        background: linear-gradient(135deg, var(--instagram-yellow) 0%, var(--instagram-orange) 25%, var(--instagram-red) 50%, var(--instagram-pink) 75%, var(--instagram-purple) 100%);
    }
    
    .instagram-gradient-reverse {
        background: linear-gradient(135deg, var(--instagram-purple) 0%, var(--instagram-pink) 25%, var(--instagram-red) 50%, var(--instagram-orange) 75%, var(--instagram-yellow) 100%);
    }
    
    .hero-section {
        border-radius: 24px;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    
    .stat-card {
        border-radius: 20px;
        padding: 2rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: inherit;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    
    .stat-card:hover::before {
        opacity: 0.1;
    }
    
    .stat-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin: 0 auto 1.5rem;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
    }
    
    .report-card {
        border-radius: 20px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        overflow: hidden;
        height: 100%;
        background: white;
    }
    
    .report-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 25px 70px rgba(0,0,0,0.25);
    }
    
    .report-header {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .report-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    
    .status-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        backdrop-filter: blur(10px);
        z-index: 10;
    }
    
    .btn-create {
        border-radius: 16px;
        padding: 1rem 2.5rem;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        border: none;
        position: relative;
        overflow: hidden;
    }
    
    .btn-create::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-create:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-create:hover {
        transform: scale(1.05);
        box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    }
    
    .btn-action {
        border-radius: 12px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-action:hover {
        transform: scale(1.08);
    }
    
    .section-title {
        border-radius: 16px;
        padding: 1.5rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .section-title::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: rgba(255,255,255,0.5);
    }
    
    .empty-state {
        padding: 5rem 2rem;
        text-align: center;
        border-radius: 24px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    }
    
    .floating-btn {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        z-index: 1000;
        transition: all 0.3s ease;
        border: none;
    }
    
    .floating-btn:hover {
        transform: scale(1.15) rotate(90deg);
        box-shadow: 0 15px 50px rgba(0,0,0,0.4);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-in {
        animation: fadeInUp 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .report-meta {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .report-meta:last-child {
        border-bottom: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Hero Section avec dégradé Instagram -->
    <div class="row mb-5 animate-in">
        <div class="col-12">
            <div class="hero-section instagram-gradient shadow-lg text-white">
                <div class="position-relative" style="z-index: 1;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h1 class="mb-3" style="font-weight: 800; font-size: 3rem;">
                                <i class="fas fa-file-alt me-3"></i>
                                Mes Rapports
                            </h1>
                            <p class="mb-0 opacity-90" style="font-size: 1.2rem;">
                                Créez, gérez et consultez tous vos travaux pratiques
                            </p>
                        </div>
                        <div>
                            <button class="btn btn-create btn-light" onclick="window.location.href='#create-modal'" data-bs-toggle="modal" data-bs-target="#createReportModal">
                                <i class="fas fa-plus me-2"></i>Créer un Rapport
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques avec dégradés Instagram -->
    <div class="row mb-5 animate-in" style="animation-delay: 0.1s;">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card instagram-gradient shadow-lg text-white">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="text-center mb-2" style="font-weight: 800; font-size: 3rem;">{{ $stats['validés'] }}</h2>
                <p class="text-center mb-0 opacity-90" style="font-size: 1.1rem; font-weight: 600;">Validés</p>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card instagram-gradient-reverse shadow-lg text-white">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h2 class="text-center mb-2" style="font-weight: 800; font-size: 3rem;">{{ $stats['en_attente'] }}</h2>
                <p class="text-center mb-0 opacity-90" style="font-size: 1.1rem; font-weight: 600;">En Attente</p>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card shadow-lg text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h2 class="text-center mb-2" style="font-weight: 800; font-size: 3rem;">{{ $stats['rejetés'] }}</h2>
                <p class="text-center mb-0 opacity-90" style="font-size: 1.1rem; font-weight: 600;">Rejetés</p>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card shadow-lg text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="stat-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h2 class="text-center mb-2" style="font-weight: 800; font-size: 3rem;">{{ $stats['total'] }}</h2>
                <p class="text-center mb-0 opacity-90" style="font-size: 1.1rem; font-weight: 600;">Total</p>
            </div>
        </div>
    </div>

    <!-- Tableau unique minimaliste Instagram -->
    <div class="row animate-in" style="animation-delay: 0.2s;">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table align-middle mb-0" style="background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 32px rgba(131, 58, 180, 0.15);">
                    <thead style="background: linear-gradient(135deg, #833AB4 0%, #C13584 50%, #E1306C 100%); color: white;">
                        <tr>
                            <th style="padding: 1.5rem 1.2rem; font-weight: 600; border: none; font-size: 0.95rem; letter-spacing: 0.5px;">
                                Titre
                            </th>
                            <th style="padding: 1.5rem 1.2rem; font-weight: 600; border: none; font-size: 0.95rem; letter-spacing: 0.5px;">
                                Description
                            </th>
                            <th style="padding: 1.5rem 1.2rem; font-weight: 600; border: none; text-align: center; font-size: 0.95rem; letter-spacing: 0.5px;">
                                Statut
                            </th>
                            <th style="padding: 1.5rem 1.2rem; font-weight: 600; border: none; text-align: center; font-size: 0.95rem; letter-spacing: 0.5px;">
                                Fichiers
                            </th>
                            <th style="padding: 1.5rem 1.2rem; font-weight: 600; border: none; text-align: center; font-size: 0.95rem; letter-spacing: 0.5px;">
                                Date
                            </th>
                            <th style="padding: 1.5rem 1.2rem; font-weight: 600; border: none; text-align: center; font-size: 0.95rem; letter-spacing: 0.5px;">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $doc)
                        <tr style="transition: all 0.3s ease; border-bottom: 1px solid #f5f5f5;">
                            <td style="padding: 1.2rem; font-weight: 600; color: #262626; font-size: 0.95rem;">
                                <i class="fas fa-file-pdf me-2" style="color: #E1306C;"></i>
                                {{ Str::limit($doc['titre'], 45) }}
                            </td>
                            <td style="padding: 1.2rem; color: #8e8e8e; font-size: 0.9rem;">
                                {{ Str::limit(strip_tags($doc['description']), 70) }}
                            </td>
                            <td style="padding: 1.2rem; text-align: center;">
                                @if($doc['status'] === 'validated')
                                    <span class="badge" style="background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); color: white; padding: 0.4rem 0.9rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-check-circle me-1"></i>Validé
                                    </span>
                                @elseif($doc['status'] === 'pending')
                                    <span class="badge" style="background: linear-gradient(135deg, #F56040 0%, #FCAF45 100%); color: white; padding: 0.4rem 0.9rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @else
                                    <span class="badge" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; padding: 0.4rem 0.9rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                                        <i class="fas fa-times-circle me-1"></i>Rejeté
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1.2rem; text-align: center;">
                                <span style="color: #8e8e8e; font-size: 0.9rem; font-weight: 500;">
                                    {{ $doc['files_count'] }}
                                </span>
                            </td>
                            <td style="padding: 1.2rem; text-align: center; color: #8e8e8e; font-size: 0.9rem;">
                                {{ \Carbon\Carbon::parse($doc['date_ajout'])->format('d/m/Y') }}
                            </td>
                            <td style="padding: 1.2rem; text-align: center;">
                                <div class="d-flex gap-2 justify-content-center">
                                    @if($doc['lien'] !== '#')
                                        <a href="{{ $doc['lien'] }}" 
                                           target="_blank" 
                                           class="btn btn-sm text-white" 
                                           style="background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(131, 58, 180, 0.2);"
                                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(131, 58, 180, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(131, 58, 180, 0.2)';"
                                           title="Voir le document">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                    
                                    @if($doc['status'] === 'pending')
                                        <a href="{{ route($currentModule . '.tp.modifier', $doc['id']) }}" 
                                           class="btn btn-sm text-white" 
                                           style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);"
                                           onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(102, 126, 234, 0.3)';"
                                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.2)';"
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm text-white" 
                                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.2);"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(240, 147, 251, 0.3)';"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(240, 147, 251, 0.2)';"
                                                onclick="deleteReport({{ $doc['id'] }}, {{ json_encode(strip_tags($doc['titre'])) }})"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- État vide -->
    @if(empty($documents) || count($documents) == 0)
    <div class="row animate-in">
        <div class="col-12">
            <div class="empty-state shadow-sm">
                <i class="fas fa-folder-open fa-6x text-muted mb-4" style="opacity: 0.3;"></i>
                <h2 class="text-muted mb-3" style="font-weight: 700;">Aucun rapport pour le moment</h2>
                <p class="text-muted mb-4" style="font-size: 1.1rem;">Commencez par créer votre premier rapport de travail pratique</p>
                <button class="btn btn-create instagram-gradient text-white" data-bs-toggle="modal" data-bs-target="#createReportModal">
                    <i class="fas fa-plus me-2"></i>Créer mon premier rapport
                </button>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Bouton flottant pour créer un rapport -->
<button class="floating-btn instagram-gradient text-white" data-bs-toggle="modal" data-bs-target="#createReportModal" title="Créer un rapport">
    <i class="fas fa-plus"></i>
</button>

<!-- Modal de création de rapport -->
<div class="modal fade" id="createReportModal" tabindex="-1" aria-labelledby="createReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header instagram-gradient text-white" style="border-radius: 20px 20px 0 0;">
                <h5 class="modal-title" id="createReportModalLabel" style="font-weight: 700; font-size: 1.5rem;">
                    <i class="fas fa-plus-circle me-2"></i>Créer un Nouveau Rapport
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route($currentModule . '.tp.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <!-- Titre du rapport -->
                    <div class="mb-4">
                        <label for="title" class="form-label" style="font-weight: 600; font-size: 1.1rem;">
                            <i class="fas fa-heading me-2 text-primary"></i>Titre du rapport <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control form-control-lg" 
                               id="title" 
                               name="title" 
                               placeholder="Ex: Rapport de stage - Design graphique"
                               required
                               style="border-radius: 12px; border: 2px solid #e9ecef; padding: 1rem;">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>Donnez un titre clair et descriptif à votre rapport
                        </small>
                    </div>

                    <!-- Document -->
                    <div class="mb-4">
                        <label for="files" class="form-label" style="font-weight: 600; font-size: 1.1rem;">
                            <i class="fas fa-file-upload me-2 text-success"></i>Ajouter le document <span class="text-danger">*</span>
                        </label>
                        <div class="upload-area" style="border: 3px dashed #e9ecef; border-radius: 16px; padding: 2rem; text-align: center; background: #f8f9fa; transition: all 0.3s ease;">
                            <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                            <p class="mb-3 text-muted">Cliquez pour sélectionner ou glissez-déposez vos fichiers</p>
                            <input type="file" 
                                   class="form-control" 
                                   id="files" 
                                   name="files[]" 
                                   multiple
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   required
                                   style="border-radius: 12px;">
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-check-circle me-1"></i>Formats acceptés : PDF, DOC, DOCX, JPG, PNG (Max 10MB par fichier)
                            </small>
                        </div>
                        <div id="filesList" class="mt-3"></div>
                    </div>

                    <!-- Description (optionnel) -->
                    <div class="mb-4">
                        <label for="description" class="form-label" style="font-weight: 600; font-size: 1.1rem;">
                            <i class="fas fa-align-left me-2 text-info"></i>Description <span class="text-muted">(optionnel)</span>
                        </label>
                        <textarea class="form-control" 
                                  id="description" 
                                  name="description" 
                                  rows="4"
                                  placeholder="Ajoutez une description détaillée de votre rapport (contexte, objectifs, résultats...)"
                                  style="border-radius: 12px; border: 2px solid #e9ecef; padding: 1rem;"></textarea>
                        <small class="text-muted">
                            <i class="fas fa-lightbulb me-1"></i>Une bonne description aide à mieux comprendre votre travail
                        </small>
                    </div>

                    <!-- Informations -->
                    <div class="alert alert-info" style="border-radius: 12px; border: none; background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note :</strong> Votre rapport sera soumis pour validation. Vous recevrez une notification une fois qu'il sera validé.
                    </div>
                </div>

                <div class="modal-footer" style="border: none; padding: 1.5rem;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 0.7rem 1.5rem;">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn instagram-gradient text-white" style="border-radius: 12px; padding: 0.7rem 2rem; font-weight: 600;">
                        <i class="fas fa-paper-plane me-2"></i>Soumettre le rapport
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Animation au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

document.querySelectorAll('.animate-in').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(40px)';
    el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
    observer.observe(el);
});

// Effet parallax sur le hero
document.addEventListener('mousemove', (e) => {
    const hero = document.querySelector('.hero-section');
    if (hero) {
        const x = (e.clientX / window.innerWidth - 0.5) * 20;
        const y = (e.clientY / window.innerHeight - 0.5) * 20;
        hero.style.transform = `perspective(1000px) rotateY(${x}deg) rotateX(${-y}deg)`;
    }
});

// Reset parallax on mouse leave
document.querySelector('.hero-section')?.addEventListener('mouseleave', function() {
    this.style.transform = 'perspective(1000px) rotateY(0deg) rotateX(0deg)';
});

// Gestion de l'upload de fichiers
const fileInput = document.getElementById('files');
const filesList = document.getElementById('filesList');
const uploadArea = document.querySelector('.upload-area');

if (fileInput) {
    fileInput.addEventListener('change', function(e) {
        displayFiles(this.files);
    });

    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#f09433';
        this.style.background = 'linear-gradient(135deg, #fff5f5 0%, #ffe5e5 100%)';
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#e9ecef';
        this.style.background = '#f8f9fa';
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#e9ecef';
        this.style.background = '#f8f9fa';
        
        const files = e.dataTransfer.files;
        fileInput.files = files;
        displayFiles(files);
    });
}

function displayFiles(files) {
    filesList.innerHTML = '';
    
    if (files.length === 0) return;
    
    const filesArray = Array.from(files);
    
    filesArray.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'alert alert-success d-flex justify-content-between align-items-center';
        fileItem.style.borderRadius = '12px';
        fileItem.style.marginBottom = '0.5rem';
        
        const fileInfo = document.createElement('div');
        fileInfo.innerHTML = `
            <i class="fas fa-file-${getFileIcon(file.name)} me-2"></i>
            <strong>${file.name}</strong>
            <small class="text-muted ms-2">(${formatFileSize(file.size)})</small>
        `;
        
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-sm btn-outline-danger';
        removeBtn.innerHTML = '<i class="fas fa-times"></i>';
        removeBtn.onclick = function() {
            // Remove file from input (complex, so we'll just hide the item)
            fileItem.remove();
        };
        
        fileItem.appendChild(fileInfo);
        fileItem.appendChild(removeBtn);
        filesList.appendChild(fileItem);
    });
}

function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        'pdf': 'pdf',
        'doc': 'word',
        'docx': 'word',
        'jpg': 'image',
        'jpeg': 'image',
        'png': 'image'
    };
    return icons[ext] || 'alt';
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

// Reset form when modal is closed
document.getElementById('createReportModal')?.addEventListener('hidden.bs.modal', function () {
    document.querySelector('#createReportModal form')?.reset();
    filesList.innerHTML = '';
});

// Fonction de suppression de rapport
function deleteReport(reportId, reportTitle) {
    // Créer un modal de confirmation personnalisé
    const modalHtml = `
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 20px; border: none;">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px 20px 0 0;">
                        <h5 class="modal-title" style="font-weight: 700;">
                            <i class="fas fa-exclamation-triangle me-2"></i>Confirmer la suppression
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-3" style="font-size: 1.1rem;">
                            Êtes-vous sûr de vouloir supprimer ce rapport ?
                        </p>
                        <div class="alert alert-warning" style="border-radius: 12px; border: none;">
                            <i class="fas fa-file-alt me-2"></i>
                            <strong>${reportTitle}</strong>
                        </div>
                        <p class="text-danger mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Cette action est irréversible. Tous les fichiers associés seront supprimés.
                        </p>
                    </div>
                    <div class="modal-footer" style="border: none; padding: 1.5rem;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 12px; padding: 0.7rem 1.5rem;">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="button" class="btn text-white" onclick="confirmDelete(${reportId})" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 12px; padding: 0.7rem 1.5rem; font-weight: 600;">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Ajouter le modal au DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Afficher le modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
    
    // Nettoyer le modal après fermeture
    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// Confirmer la suppression
function confirmDelete(reportId) {
    // Créer un formulaire pour envoyer la requête DELETE
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `{{ url('/') }}/evc/compte/{{ $currentModule }}/tp/${reportId}`;
    
    // Token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
    }
    
    // Méthode DELETE
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    // Ajouter au DOM et soumettre
    document.body.appendChild(form);
    form.submit();
}
</script>
@endsection
@endsection
