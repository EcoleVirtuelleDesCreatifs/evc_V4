@extends('layouts.ki-admin')

@section('title', 'Bibliothèque - EVC 2024')
@section('page-title', 'Bibliothèque')

@push('styles')
<style>
    .pagination {
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.25rem;
    }

    .pagination .page-item {
        margin-bottom: 0.25rem;
    }
</style>
@endpush

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
                                Bibliothèque
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

<!-- Section À la UNE -->
@php
    $itemsCollection = $items instanceof \Illuminate\Pagination\AbstractPaginator ? $items->getCollection() : $items;
    $featuredMedia = $itemsCollection->where('is_featured', true)->first() ?? $itemsCollection->first();
@endphp
@if($featuredMedia)
<div class="row mb-4">
    <div class="col-12">
        <div class="featured-section">
            <div class="featured-header">
                <h4 class="mb-0"><i class="fas fa-star me-2"></i>À la UNE</h4>
            </div>
            <div class="featured-body">
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <div class="featured-image-wrapper">
                            @if($featuredMedia->cover_image)
                                <img src="{{ asset('storage/' . $featuredMedia->cover_image) }}" alt="{{ $featuredMedia->title }}" class="featured-image">
                            @elseif(in_array(strtolower($featuredMedia->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                <img src="{{ asset('storage/' . $featuredMedia->path) }}" alt="{{ $featuredMedia->title }}" class="featured-image">
                            @else
                                <div class="featured-placeholder">
                                    <i class="fas fa-file-pdf fa-4x"></i>
                                </div>
                            @endif
                            @if($featuredMedia->libraryCategory)
                                <span class="featured-badge">{{ $featuredMedia->libraryCategory->name }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="featured-info">
                            <h2 class="featured-title">{{ $featuredMedia->title }}</h2>
                            <p class="featured-date"><i class="fas fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($featuredMedia->created_at)->format('d/m/Y') }}</p>
                            <p class="featured-date"><i class="fas fa-download me-2" style="color: var(--instagram-pink);"></i>{{ $featuredMedia->downloads_count ?? 0 }} téléchargement(s)</p>
                            <div class="featured-actions">
                                @php
                                    $hasFile = false;
                                    if ($featuredMedia->external_link) {
                                        $hasFile = true;
                                    } elseif ($featuredMedia->path && file_exists(storage_path('app/public/' . $featuredMedia->path))) {
                                        $hasFile = true;
                                    } elseif ($featuredMedia->pdf_path && file_exists(storage_path('app/public/' . $featuredMedia->pdf_path))) {
                                        $hasFile = true;
                                    }
                                @endphp
                                @if($hasFile)
                                    <a href="{{ route($formationPrefix . '.bibliotheque.download', $featuredMedia->id) }}" class="instagram-btn">
                                        <i class="fas fa-download me-2"></i>Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Statistiques par catégorie -->
@if(isset($stats['par_categorie']) && $stats['par_categorie']->count() > 0)
<div class="row mb-4">
    @foreach($stats['par_categorie'] as $categorie)
        <div class="col-md-4 col-lg-3 mb-3">
            <div class="category-stat-card" data-category="{{ $categorie->name }}" onclick="filterByCategory('{{ $categorie->name }}')" style="cursor: pointer;">
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

    <!-- Bouton pour afficher tous les livres -->
    <div class="col-12 text-center mt-3">
        <button class="instagram-btn-outline" onclick="showAllBooks()">
            <i class="fas fa-th-large me-2"></i>Afficher tous les livres
        </button>
    </div>
</div>
@endif

<!-- Liste dynamique des ressources -->
<div class="row">
    <div class="col-12">
        @if($itemsCollection->isEmpty())
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
            @if($itemsCollection->count() > 1)
            <div class="row g-4" id="resources-container">
                @foreach($itemsCollection->skip(1) as $item)
                    <div class="col-md-6 col-lg-3 resource-item">
                        <div class="resource-card instagram-card" data-category="{{ $item->libraryCategory->name ?? 'Non catégorisé' }}">
                            <!-- Image de couverture -->
                            <div class="resource-image-container mb-3">
                                @if($item->cover_image)
                                    <img src="{{ asset('storage/' . $item->cover_image) }}"
                                         alt="{{ $item->title }}"
                                         class="resource-image">
                                @elseif(in_array(strtolower($item->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
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
                                    <i class="fas fa-calendar" style="color: var(--instagram-pink);"></i>
                                    <span class="small text-muted">Publié le {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</span>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-download" style="color: var(--instagram-pink);"></i>
                                    <span class="small text-muted">{{ $item->downloads_count ?? 0 }} téléchargement(s)</span>
                                </div>
                            </div>
                            <div class="resource-actions">
                                @php
                                    $hasFile = false;
                                    if ($item->external_link) {
                                        $hasFile = true;
                                    } elseif ($item->path && file_exists(storage_path('app/public/' . $item->path))) {
                                        $hasFile = true;
                                    } elseif ($item->pdf_path && file_exists(storage_path('app/public/' . $item->pdf_path))) {
                                        $hasFile = true;
                                    }
                                @endphp
                                @if($hasFile)
                                    <a href="{{ route($formationPrefix . '.bibliotheque.download', $item->id) }}"
                                       class="instagram-btn w-100 mb-2">
                                        <i class="fas fa-download me-2"></i>
                                        Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            @endif
        @endif
    </div>
</div>

@if($items instanceof \Illuminate\Pagination\AbstractPaginator)
<div class="row mt-4">
    <div class="col-12 d-flex justify-content-center">
        {{ $items->withQueryString()->links() }}
    </div>
</div>
@endif
@endsection

@push('styles')
<style>
/* Palette Instagram */
:root {
    --instagram-purple: #1e3c72;
    --instagram-pink: #2a5298;
    --instagram-red: #4fc3f7;
    --instagram-orange: #60a5fa;
    --instagram-yellow: #93c5fd;
}

/* Header avec dégradé Instagram */
.instagram-header {
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink), var(--instagram-red));
    border-radius: 20px;
    color: white;
    box-shadow: 0 8px 32px rgba(42, 82, 152, 0.3);
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
    background: linear-gradient(135deg, rgba(30, 60, 114, 0.08), rgba(79, 195, 247, 0.12));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    color: var(--instagram-pink);
    margin: 0 auto;
}

/* Section À la UNE */
.featured-section {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    animation: fadeInUp 0.6s ease;
}

.featured-header {
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink), var(--instagram-red));
    padding: 1.25rem 1.5rem;
    color: white;
}

.featured-header h4 {
    color: white;
    font-weight: 700;
    margin: 0;
}

.featured-body {
    padding: 2rem;
}

.featured-image-wrapper {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    background: #f8f9fa;
}

.featured-image {
    width: 100%;
    height: auto;
    display: block;
    object-fit: contain;
}

.featured-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-red));
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(42, 82, 152, 0.35);
}

.featured-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
}

.featured-title {
    font-size: 2rem;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 1rem;
    line-height: 1.2;
}

.featured-date {
    color: #6b7280;
    font-size: 0.95rem;
    margin-bottom: 1.5rem;
}

.featured-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
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
    box-shadow: 0 8px 24px rgba(42, 82, 152, 0.2);
}

.category-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-red));
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
    box-shadow: 0 12px 40px rgba(42, 82, 152, 0.25);
    border-color: var(--instagram-pink);
}

/* Image de couverture */
.resource-image-container {
    position: relative;
    width: 100%;
    min-height: 350px;
    border-radius: 12px;
    overflow: hidden;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.resource-image {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.resource-card:hover .resource-image {
    transform: scale(1.05);
}

.resource-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--instagram-pink), var(--instagram-red));
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
    color: var(--instagram-pink);
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
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-red));
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
    box-shadow: 0 4px 12px rgba(42, 82, 152, 0.3);
}

.instagram-btn:hover {
    background: linear-gradient(135deg, var(--instagram-pink), var(--instagram-red));
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(42, 82, 152, 0.35);
    color: white;
}

/* Bouton Instagram Outline */
.instagram-btn-outline {
    background: transparent;
    color: var(--instagram-pink);
    border: 2px solid var(--instagram-pink);
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
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-red));
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
// Variables globales
let currentFilter = null;
let itemsPerPage = 8; // Nombre d'items affichés initialement
let itemsToLoad = 4; // Nombre d'items à charger à chaque clic
let currentlyShown = itemsPerPage;

/**
 * Initialiser l'affichage des ressources (afficher seulement les 8 premiers)
 */
function initializeResourcesDisplay() {
    const allItems = document.querySelectorAll('.resource-item');
    const totalCount = allItems.length;

    // Cacher tous les items au-delà des 8 premiers
    allItems.forEach((item, index) => {
        if (index >= itemsPerPage) {
            item.style.display = 'none';
        }
    });

    // Mettre à jour le compteur
    document.getElementById('shown-count').textContent = Math.min(itemsPerPage, totalCount);
    document.getElementById('total-count').textContent = totalCount;

    // Cacher le bouton si tous les items sont déjà affichés
    if (totalCount <= itemsPerPage) {
        document.getElementById('load-more-container').style.display = 'none';
    }
}

/**
 * Charger plus de ressources
 */
function loadMoreResources() {
    const allItems = document.querySelectorAll('.resource-item');
    const hiddenItems = Array.from(allItems).filter(item => item.style.display === 'none');

    // Afficher les prochains items
    hiddenItems.slice(0, itemsToLoad).forEach(item => {
        item.style.display = 'block';
        item.style.animation = 'fadeInUp 0.6s ease';
    });

    // Mettre à jour le compteur
    currentlyShown = Array.from(allItems).filter(item => item.style.display !== 'none').length;
    document.getElementById('shown-count').textContent = currentlyShown;

    // Cacher le bouton si tous les items sont affichés
    if (hiddenItems.length <= itemsToLoad) {
        document.getElementById('load-more-btn').style.display = 'none';
        document.getElementById('resources-counter').innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-2"></i>Tous les livres sont affichés</span>';
    }

    // Notification
    showNotification(`${Math.min(itemsToLoad, hiddenItems.length)} livre(s) supplémentaire(s) chargé(s)`, 'success');
}

/**
 * Filtrer les livres par catégorie
 */
function filterByCategory(categoryName) {
    const cards = document.querySelectorAll('.resource-card');
    const categoryCards = document.querySelectorAll('.category-stat-card');
    let visibleCount = 0;

    // Mettre à jour le filtre actuel
    currentFilter = categoryName;

    // Parcourir toutes les cartes
    cards.forEach(card => {
        const cardCategory = card.getAttribute('data-category');

        if (cardCategory === categoryName) {
            card.style.display = 'block';
            card.style.animation = 'fadeIn 0.5s ease';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Mettre en surbrillance la carte sélectionnée
    categoryCards.forEach(card => {
        const cardCat = card.getAttribute('data-category');
        if (cardCat === categoryName) {
            card.style.borderColor = 'var(--instagram-pink)';
            card.style.boxShadow = '0 12px 40px rgba(42, 82, 152, 0.35)';
            card.style.transform = 'translateY(-5px)';
        } else {
            card.style.borderColor = 'transparent';
            card.style.boxShadow = '0 4px 16px rgba(0, 0, 0, 0.08)';
            card.style.transform = 'translateY(0)';
        }
    });

    // Afficher une notification
    showNotification(`${visibleCount} livre(s) dans la catégorie "${categoryName}"`, 'info');

    // Scroll vers les ressources
    document.querySelector('.resource-card')?.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

/**
 * Afficher tous les livres
 */
function showAllBooks() {
    const cards = document.querySelectorAll('.resource-card');
    const categoryCards = document.querySelectorAll('.category-stat-card');

    // Réinitialiser le filtre
    currentFilter = null;

    // Afficher toutes les cartes
    cards.forEach(card => {
        card.style.display = 'block';
        card.style.animation = 'fadeIn 0.5s ease';
    });

    // Réinitialiser le style des cartes de catégorie
    categoryCards.forEach(card => {
        card.style.borderColor = 'transparent';
        card.style.boxShadow = '0 4px 16px rgba(0, 0, 0, 0.08)';
        card.style.transform = 'translateY(0)';
    });

    // Afficher une notification
    showNotification(`Tous les livres sont maintenant affichés (${cards.length} au total)`, 'success');
}

/**
 * Afficher une notification toast
 */
function showNotification(message, type = 'info') {
    // Créer l'élément toast
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; animation: slideInRight 0.5s ease;';

    // Icône selon le type
    let icon = 'fa-info-circle';
    if (type === 'success') icon = 'fa-check-circle';
    if (type === 'warning') icon = 'fa-exclamation-triangle';
    if (type === 'danger') icon = 'fa-times-circle';

    toast.innerHTML = `
        <i class="fas ${icon} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    // Supprimer automatiquement après 4 secondes
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.5s ease';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 500);
    }, 4000);
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser l'affichage des ressources (afficher seulement les 8 premiers)
    initializeResourcesDisplay();

    // Animation au scroll
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

    document.querySelectorAll('.resource-card').forEach(card => {
        observer.observe(card);
    });
});

// Ajouter les animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(100px); }
        to { opacity: 1; transform: translateX(0); }
    }
    @keyframes slideOutRight {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(100px); }
    }
`;
document.head.appendChild(style);
</script>
@endpush
