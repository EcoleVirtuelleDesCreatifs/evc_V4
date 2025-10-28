@extends('layouts.ki-admin')

@section('title', 'Bibliothèque CM_SMM - EVC 2024')
@section('page-title', 'Bibliothèque CM_SMM')

@section('content')
<!-- Header avec palette Instagram -->
<div class="row mb-4">
    <div class="col-12">
        <div class="instagram-header">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-circle">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <h3 class="mb-1" style="font-weight: 700; font-size: 1.8rem;">
                                Bibliothèque CM_SMM
                            </h3>
                            <p class="mb-0 text-white-50">Ressources et supports de cours</p>
                        </div>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="badge" style="background: rgba(255,255,255,0.2); padding: 0.75rem 1.5rem; font-size: 1rem; border-radius: 30px;">
                            <i class="fas fa-book me-2"></i>
                            {{ $stats['total_documents'] }} Ressource(s) disponible(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques par catégorie -->
@if(isset($stats['par_categorie']) && $stats['par_categorie']->count() > 0)
<div class="row mb-4">
    @foreach($stats['par_categorie'] as $categorie)
        <div class="col-md-4 col-lg-3 mb-3">
            <div class="category-stat-card">
                <div class="category-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <div class="category-info">
                    <h4 class="category-count">{{ $categorie->count }}</h4>
                    <p class="category-name">{{ $categorie->name }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endif

<!-- Liste dynamique des ressources -->
<div class="row">
    <div class="col-12">
        @if($items->isEmpty())
            <!-- Message si aucune ressource -->
            <div class="empty-state">
                <div class="text-center py-5">
                    <div class="icon-circle-large mb-4">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="mb-3" style="color: #1f2937; font-weight: 600;">Aucune ressource disponible</h3>
                    <p class="text-muted mb-0">Les ressources de la bibliothèque seront publiées prochainement par vos formateurs.</p>
                </div>
            </div>
        @else
            <!-- Grille des ressources -->
            <div class="row g-4">
                @foreach($items as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="resource-card instagram-card">
                            <!-- Image de couverture -->
                            <div class="resource-image-container mb-3">
                                @if(in_array(strtolower($item->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                    <img src="{{ asset('storage/' . $item->path) }}" 
                                         alt="{{ $item->title }}" 
                                         class="resource-image">
                                @else
                                    <div class="resource-placeholder">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                @endif
                                
                                <!-- Badge catégorie -->
                                @if($item->libraryCategory)
                                    <div class="category-badge">
                                        <i class="fas fa-tag me-1"></i>
                                        {{ $item->libraryCategory->name }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Titre -->
                            <h4 class="resource-title mb-2">
                                {{ $item->title }}
                            </h4>
                            
                            <!-- Informations -->
                            <div class="resource-info mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-calendar" style="color: #C13584;"></i>
                                    <span class="small text-muted">Publié le {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-download" style="color: #C13584;"></i>
                                    <span class="small text-muted">{{ $item->downloads_count ?? 0 }} téléchargement(s)</span>
                                </div>
                            </div>
                            
                            <!-- Boutons d'action -->
                            <div class="resource-actions">
                                @if($item->pdf_path)
                                    <a href="{{ asset('storage/' . $item->pdf_path) }}" 
                                       target="_blank" 
                                       class="instagram-btn w-100 mb-2"
                                       download>
                                        <i class="fas fa-file-pdf me-2"></i>
                                        Télécharger le PDF
                                    </a>
                                @endif
                                
                                @if($item->download_url)
                                    <a href="{{ $item->download_url }}" 
                                       target="_blank" 
                                       class="instagram-btn-outline w-100">
                                        <i class="fas fa-external-link-alt me-2"></i>
                                        Lien externe
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
/* Palette Instagram */
:root {
    --instagram-purple: #833AB4;
    --instagram-pink: #C13584;
    --instagram-red: #E1306C;
    --instagram-orange: #F56040;
    --instagram-yellow: #FCAF45;
}

/* Header avec dégradé Instagram */
.instagram-header {
    background: linear-gradient(135deg, #833AB4, #C13584, #E1306C);
    border-radius: 20px;
    color: white;
    box-shadow: 0 8px 32px rgba(131, 58, 180, 0.3);
    animation: fadeInDown 0.6s ease;
    margin-bottom: 2rem;
}

/* Icône circulaire avec effet glassmorphism */
.icon-circle {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    animation: pulse 2s infinite;
}

.icon-circle-large {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, rgba(131, 58, 180, 0.1), rgba(193, 53, 132, 0.1));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    color: #C13584;
    margin: 0 auto;
}

/* Cartes de statistiques par catégorie */
.category-stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease;
}

.category-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(131, 58, 180, 0.2);
}

.category-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #833AB4, #E1306C);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}

.category-info {
    flex: 1;
}

.category-count {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.category-name {
    font-size: 0.9rem;
    color: #6b7280;
    margin: 0;
}

/* Carte de ressource avec style Instagram */
.resource-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    border: 2px solid transparent;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    animation: fadeInUp 0.6s ease;
}

.resource-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(131, 58, 180, 0.25);
    border-color: #C13584;
}

/* Image de couverture */
.resource-image-container {
    position: relative;
    width: 100%;
    height: 200px;
    border-radius: 12px;
    overflow: hidden;
}

.resource-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.resource-card:hover .resource-image {
    transform: scale(1.05);
}

.resource-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #E1306C, #F56040);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: white;
}

/* Badge catégorie */
.category-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    color: #C13584;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Titre de la ressource */
.resource-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.4;
}

/* Informations de la ressource */
.resource-info {
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

/* Actions de la ressource */
.resource-actions {
    margin-top: auto;
}

/* Bouton Instagram */
.instagram-btn {
    background: linear-gradient(135deg, #833AB4, #E1306C);
    color: white;
    border: none;
    border-radius: 30px;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(131, 58, 180, 0.3);
}

.instagram-btn:hover {
    background: linear-gradient(135deg, #C13584, #F56040);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(131, 58, 180, 0.4);
    color: white;
}

/* Bouton Instagram Outline */
.instagram-btn-outline {
    background: transparent;
    color: #C13584;
    border: 2px solid #C13584;
    border-radius: 30px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
}

.instagram-btn-outline:hover {
    background: linear-gradient(135deg, #833AB4, #E1306C);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
}

/* État vide */
.empty-state {
    background: white;
    border-radius: 20px;
    padding: 4rem 2rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

/* Animations */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .instagram-header h3 {
        font-size: 1.5rem;
    }
    
    .icon-circle {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
    
    .resource-card {
        padding: 1.25rem;
    }
    
    .resource-image-container {
        height: 180px;
    }
    
    .resource-title {
        font-size: 1.05rem;
    }
    
    .category-stat-card {
        padding: 1.25rem;
    }
    
    .category-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .category-count {
        font-size: 1.5rem;
    }
}

/* Animation en cascade pour les cartes */
.resource-card:nth-child(1) { animation-delay: 0.1s; }
.resource-card:nth-child(2) { animation-delay: 0.2s; }
.resource-card:nth-child(3) { animation-delay: 0.3s; }
.resource-card:nth-child(4) { animation-delay: 0.4s; }
.resource-card:nth-child(5) { animation-delay: 0.5s; }
.resource-card:nth-child(6) { animation-delay: 0.6s; }

.category-stat-card:nth-child(1) { animation-delay: 0.1s; }
.category-stat-card:nth-child(2) { animation-delay: 0.2s; }
.category-stat-card:nth-child(3) { animation-delay: 0.3s; }
.category-stat-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush

@push('scripts')
<script>
// Animation au scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.resource-card, .category-stat-card').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush
