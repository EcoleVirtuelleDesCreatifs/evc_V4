@extends('layouts.admin')

@section('title', 'Statistiques Formations - EVC Analytics')

@push('styles')
<style>
    /* ===== DESIGN RÉVOLUTIONNAIRE EVC - PHASE 1 ===== */
    
    :root {
        --primary-gradient: linear-gradient(135deg, #0066ff 0%, #00ccff 100%);
        --secondary-gradient: linear-gradient(135deg, #ff6600 0%, #ffaa00 100%);
        --accent-gradient: linear-gradient(135deg, #6600cc 0%, #9933ff 100%);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --text-primary: #ffffff;
        --text-secondary: rgba(255, 255, 255, 0.8);
        --animation-speed: 0.6s;
    }

    /* Background Immersif */
    .revolutionary-bg {
        background: var(--primary-gradient);
        min-height: 100vh;
        position: relative;
        overflow: hidden;
    }

    .revolutionary-bg::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
            radial-gradient(circle at 20% 80%, rgba(255, 102, 0, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 80% 20%, rgba(102, 0, 204, 0.1) 0%, transparent 50%);
        animation: backgroundPulse 8s ease-in-out infinite alternate;
    }

    @keyframes backgroundPulse {
        0% { opacity: 0.3; }
        100% { opacity: 0.7; }
    }

    /* Hero Section Révolutionnaire */
    .hero-stats {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    .hero-stats::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Métriques Révolutionnaires */
    .metric-revolutionary {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 30px 20px;
        text-align: center;
        position: relative;
        transition: all var(--animation-speed) cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .metric-revolutionary:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: rgba(255, 255, 255, 0.4);
    }

    .metric-revolutionary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--secondary-gradient);
        transform: scaleX(0);
        transition: transform var(--animation-speed) ease;
    }

    .metric-revolutionary:hover::before {
        transform: scaleX(1);
    }

    /* Icônes Révolutionnaires */
    .metric-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        background: var(--secondary-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: white;
        animation: iconFloat 4s ease-in-out infinite;
        position: relative;
    }

    .metric-icon::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: inherit;
        opacity: 0.3;
        animation: iconPulse 2s ease-in-out infinite;
    }

    @keyframes iconFloat {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
    }

    @keyframes iconPulse {
        0%, 100% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.2); opacity: 0.1; }
    }

    /* Compteurs Animés */
    .counter-revolutionary {
        font-size: 3rem;
        font-weight: 800;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 10px;
        font-family: 'Poppins', sans-serif;
    }

    /* Cartes Glassmorphism */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 20px;
        transition: all var(--animation-speed) ease;
    }

    .glass-card:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.3);
    }

    /* Navigation Révolutionnaire */
    .nav-revolutionary {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 20px 25px;
        margin-bottom: 30px;
    }

    /* Boutons Révolutionnaires */
    .btn-revolutionary {
        background: var(--secondary-gradient);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        color: white;
        font-weight: 600;
        transition: all var(--animation-speed) ease;
        position: relative;
        overflow: hidden;
    }

    .btn-revolutionary:hover {
        transform: translateY(-2px);
        color: white;
    }

    .btn-revolutionary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .btn-revolutionary:hover::before {
        left: 100%;
    }

    /* Animations d'entrée */
    .fade-in-up {
        animation: fadeInUp 0.8s ease forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    .fade-in-up:nth-child(1) { animation-delay: 0.1s; }
    .fade-in-up:nth-child(2) { animation-delay: 0.2s; }
    .fade-in-up:nth-child(3) { animation-delay: 0.3s; }
    .fade-in-up:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-stats { padding: 25px; }
        .metric-revolutionary { padding: 20px 15px; }
        .counter-revolutionary { font-size: 2.5rem; }
        .metric-icon { width: 60px; height: 60px; font-size: 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="gradient-bg">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-radius: 12px; padding: 15px 20px;">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none">
                        <i class="fas fa-home me-1"></i>Tableau de bord
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.formations.categories.index') }}" class="text-white text-decoration-none">
                        <i class="fas fa-layer-group me-1"></i>Catégories
                    </a>
                </li>
                <li class="breadcrumb-item active text-white" aria-current="page">
                    <i class="fas fa-eye me-1"></i>{{ $category->name }}
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h1 class="text-white fw-bold mb-2">
                            <i class="fas fa-layer-group me-3" style="color: #ffd700;"></i>
                            {{ $category->name }}
                        </h1>
                        <p class="text-white-50 mb-0">Détails et statistiques de la catégorie</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.formations.categories.edit', $category->id) }}" class="btn btn-warning btn-action">
                            <i class="fas fa-edit me-2"></i>Modifier
                        </a>
                        <a href="{{ route('admin.formations.categories.index') }}" class="btn btn-outline-light btn-action">
                            <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Informations principales -->
            <div class="col-lg-8">
                <div class="info-card">
                    <h4 class="fw-bold mb-4">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Informations de la Catégorie
                    </h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Nom de la catégorie</label>
                            <div class="p-3 bg-light rounded-3">
                                <i class="fas fa-tag text-primary me-2"></i>
                                <strong>{{ $category->name }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Slug (URL)</label>
                            <div class="p-3 bg-light rounded-3">
                                <i class="fas fa-link text-primary me-2"></i>
                                <code>{{ $category->slug }}</code>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold text-muted">Description</label>
                            <div class="p-3 bg-light rounded-3">
                                <i class="fas fa-align-left text-primary me-2"></i>
                                {{ $category->description ?: 'Aucune description fournie' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Statut</label>
                            <div class="p-3 bg-light rounded-3">
                                @if($category->status === 'active')
                                    <span class="badge bg-success badge-status">
                                        <i class="fas fa-check-circle me-1"></i>Actif
                                    </span>
                                @else
                                    <span class="badge bg-warning badge-status">
                                        <i class="fas fa-pause-circle me-1"></i>Inactif
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold text-muted">Date de création</label>
                            <div class="p-3 bg-light rounded-3">
                                <i class="fas fa-calendar text-primary me-2"></i>
                                {{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="info-card">
                    <h5 class="fw-bold mb-3">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        Actions Rapides
                    </h5>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.formations.categories.edit', $category->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit me-1"></i>Modifier
                        </a>
                        <button class="btn btn-danger btn-sm me-2" onclick="deleteCategory({{ $category->id }})">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </button>
                        <a href="{{ route('admin.formations.categories.create') }}" class="btn btn-success btn-sm me-2">
                            <i class="fas fa-plus me-1"></i>Nouvelle Catégorie
                        </a>
                        <a href="{{ route('admin.formations.categories.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Retour à la liste
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="col-lg-4">
                <div class="stat-card mb-4">
                    <i class="fas fa-graduation-cap fa-3x mb-3"></i>
                    <h3 class="fw-bold">0</h3>
                    <p class="mb-0">Formations liées</p>
                </div>

                <div class="stat-card mb-4">
                    <i class="fas fa-users fa-3x mb-3"></i>
                    <h3 class="fw-bold">0</h3>
                    <p class="mb-0">Étudiants inscrits</p>
                </div>

                <div class="stat-card mb-4">
                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                    <h3 class="fw-bold">95%</h3>
                    <p class="mb-0">Taux de satisfaction</p>
                </div>

                <!-- Informations système -->
                <div class="info-card">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info text-primary me-2"></i>
                        Informations Système
                    </h6>
                    <div class="small text-muted">
                        <div class="d-flex justify-content-between mb-2">
                            <span>ID:</span>
                            <strong>#{{ $category->id }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Créé le:</span>
                            <strong>{{ \Carbon\Carbon::parse($category->created_at)->format('d/m/Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Modifié le:</span>
                            <strong>{{ \Carbon\Carbon::parse($category->updated_at)->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function deleteCategory(id) {
    // Confirmation de suppression
    if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.')) {
        // Afficher un loading
        const loadingToast = showToast('Suppression en cours...', 'info');
        
        // Appel AJAX pour supprimer la catégorie
        fetch(`/app/admin/formations/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                // Rediriger vers la liste après 1.5 secondes
                setTimeout(() => {
                    window.location.href = '{{ route("admin.formations.categories.index") }}';
                }, 1500);
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Une erreur est survenue lors de la suppression.', 'error');
        });
    }
}

function showToast(message, type = 'info') {
    // Créer une notification toast simple
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
        ${message}
    `;
    
    document.body.appendChild(toast);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        toast.remove();
    }, 3000);
    
    return toast;
}
</script>
@endpush
