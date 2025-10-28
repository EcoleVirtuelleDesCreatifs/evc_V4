@extends('layouts.admin')

@section('title', 'Bibliothèque de Médias')

@push('styles')
<style>
    .table-dark a.text-white {
        color: #fff !important;
        text-decoration: none;
    }
    .table-dark a.text-white:hover {
        text-decoration: underline;
    }
    
    /* Cartes de statistiques compactes et modernes */
    .stat-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
    }
    
    .stat-card-primary {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    }
    
    .stat-card-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .stat-card-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .stat-card-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .stat-card-cyan {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    }

    .stat-card-purple {
        background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
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
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .stat-label {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    /* Bouton moderne */
    .btn-primary {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 195, 247, 0.4);
        color: white;
        background: linear-gradient(135deg, #29b6f6, #4fc3f7);
    }
    
    /* Onglets modernes */
    .modern-tabs {
        background: #0f172a;
        border-bottom: 2px solid #334155;
        padding: 0;
        margin: 0;
    }
    
    .modern-tabs .nav-item {
        margin: 0;
    }
    
    .modern-tabs .nav-link {
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: #94a3b8;
        padding: 1.25rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .modern-tabs .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
    }
    
    .modern-tabs .nav-link.active {
        color: #4fc3f7;
        border-bottom-color: #4fc3f7;
        background: rgba(79, 195, 247, 0.1);
    }
    
    .modern-tabs .nav-link .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    
    /* Animation des onglets */
    .tab-pane {
        animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Cartes de catégorie modernes */
    .category-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 2px solid #334155;
        border-radius: 20px;
        padding: 0;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        height: 100%;
    }
    
    .category-card:hover {
        transform: translateY(-8px);
        border-color: #4fc3f7;
        box-shadow: 0 12px 40px rgba(79, 195, 247, 0.3);
    }
    
    .category-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #4fc3f7 100%);
        padding: 2rem 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    .category-card-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: shimmer 3s infinite;
    }
    
    @keyframes shimmer {
        0%, 100% { transform: translate(0, 0); }
        50% { transform: translate(10%, 10%); }
    }
    
    .category-icon-wrapper {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2.5rem;
        color: white;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .category-count {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin: 0;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .category-card-body {
        padding: 1.5rem;
        background: #1e293b;
    }
    
    .category-name {
        font-size: 1.25rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .category-desc {
        font-size: 0.9rem;
        color: #94a3b8;
        margin-bottom: 1.5rem;
    }
    
    .btn-category-filter {
        width: 100%;
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .btn-category-filter:hover {
        background: linear-gradient(135deg, #29b6f6 0%, #039be5 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 195, 247, 0.4);
    }
    
    .btn-category-filter:active {
        transform: translateY(0);
    }
    
    /* Animation d'entrée en cascade */
    .category-card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
    }
    
    .category-card:nth-child(2) { animation-delay: 0.1s; }
    .category-card:nth-child(3) { animation-delay: 0.2s; }
    .category-card:nth-child(4) { animation-delay: 0.3s; }
    .category-card:nth-child(5) { animation-delay: 0.4s; }
    
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
    
    /* Styles pour le filtrage */
    .media-row {
        transition: all 0.3s ease;
    }
    
    .media-row.hidden {
        display: none !important;
    }
    
    /* Cartes d'espace de formation */
    .space-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 2px solid #334155;
        border-radius: 20px;
        padding: 0;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        height: 100%;
    }
    
    .space-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }
    
    .space-card-header {
        padding: 2rem 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    
    /* Dégradés par espace selon la palette admin */
    .space-design-graphique {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }
    
    .space-community-management {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    }
    
    .space-gestion-informatique {
        background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);
    }
    
    .space-intelligence-artificielle {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    }
    
    .space-card:hover .space-design-graphique {
        box-shadow: 0 8px 32px rgba(30, 60, 114, 0.4);
    }
    
    .space-card:hover .space-community-management {
        box-shadow: 0 8px 32px rgba(79, 195, 247, 0.4);
    }
    
    .space-card:hover .space-gestion-informatique {
        box-shadow: 0 8px 32px rgba(255, 152, 0, 0.4);
    }
    
    .space-card:hover .space-intelligence-artificielle {
        box-shadow: 0 8px 32px rgba(38, 198, 218, 0.4);
    }
    
    .space-icon-wrapper {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 2.5rem;
        color: white;
        animation: pulse 2s infinite;
    }
    
    .space-count {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin: 0;
        text-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
    
    .space-card-body {
        padding: 1.5rem;
        background: #1e293b;
    }
    
    .space-name {
        font-size: 1.15rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .space-desc {
        font-size: 0.9rem;
        color: #94a3b8;
        margin-bottom: 1.5rem;
    }
    
    .btn-space-filter {
        width: 100%;
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .btn-space-filter:hover {
        background: linear-gradient(135deg, #475569 0%, #334155 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
    }
    
    /* Animation pour les cartes d'espace */
    .space-card {
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
    }
    
    .space-card:nth-child(2) { animation-delay: 0.1s; }
    .space-card:nth-child(3) { animation-delay: 0.2s; }
    .space-card:nth-child(4) { animation-delay: 0.3s; }
    .space-card:nth-child(5) { animation-delay: 0.4s; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-white mb-0">
            <i class="fas fa-books me-2 text-primary"></i>Bibliothèque de Médias
        </h1>
        <a href="{{ route('admin.bibliotheque.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Ajouter un Média
        </a>
    </div>

    <!-- Cartes de Statistiques Compactes -->
    <div class="row mb-4">
        <!-- Total Documents -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format($stats['total_documents'] ?? 0) }}</h3>
                    <p class="stat-label">Total Documents</p>
                </div>
            </div>
        </div>

        <!-- Documents Actifs -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format($stats['documents_actifs'] ?? 0) }}</h3>
                    <p class="stat-label">Documents Actifs</p>
                </div>
            </div>
        </div>

        <!-- Total Téléchargements -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-download"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format($stats['total_downloads'] ?? 0) }}</h3>
                    <p class="stat-label">Téléchargements</p>
                </div>
            </div>
        </div>

        <!-- Documents ce Mois -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format($stats['documents_ce_mois'] ?? 0) }}</h3>
                    <p class="stat-label">Ajoutés ce Mois</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Système d'onglets moderne pour les filtres -->
    <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body p-0">
            <!-- Navigation par onglets -->
            <ul class="nav nav-tabs modern-tabs" id="filterTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="spaces-tab" data-bs-toggle="tab" data-bs-target="#spaces-content" type="button" role="tab">
                        <i class="fas fa-graduation-cap me-2"></i>
                        Espaces de Formation
                        <span class="badge bg-info ms-2">{{ count($stats['par_espace'] ?? []) }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="categories-tab" data-bs-toggle="tab" data-bs-target="#categories-content" type="button" role="tab">
                        <i class="fas fa-layer-group me-2"></i>
                        Catégories
                        <span class="badge bg-warning ms-2">{{ $stats['par_categorie']->count() ?? 0 }}</span>
                    </button>
                </li>
            </ul>

            <!-- Contenu des onglets -->
            <div class="tab-content p-4" id="filterTabsContent">
                <!-- Onglet Espaces de Formation -->
                <div class="tab-pane fade show active" id="spaces-content" role="tabpanel">
                    @if(isset($stats['par_espace']) && count($stats['par_espace']) > 0)
                    <div class="row">
        
        @foreach($stats['par_espace'] as $index => $espace)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="space-card" data-space="{{ $espace['slug'] }}">
                <div class="space-card-header space-{{ $espace['slug'] }}">
                    <div class="space-icon-wrapper">
                        @if($espace['slug'] == 'design-graphique')
                            <i class="fas fa-palette"></i>
                        @elseif($espace['slug'] == 'community-management')
                            <i class="fas fa-share-alt"></i>
                        @elseif($espace['slug'] == 'gestion-informatique')
                            <i class="fas fa-laptop-code"></i>
                        @elseif($espace['slug'] == 'intelligence-artificielle')
                            <i class="fas fa-robot"></i>
                        @endif
                    </div>
                    <h3 class="space-count">{{ $espace['count'] }}</h3>
                </div>
                <div class="space-card-body">
                    <h6 class="space-name">{{ $espace['name'] }}</h6>
                    <p class="space-desc">{{ $espace['count'] }} média(s) disponible(s)</p>
                    <button class="btn-space-filter" onclick="filterBySpace('{{ $espace['slug'] }}')">
                        <i class="fas fa-filter me-2"></i>Voir les médias
                    </button>
                </div>
            </div>
        </div>
        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-white">Aucun espace de formation disponible</p>
                    </div>
                    @endif
                </div>

                <!-- Onglet Catégories -->
                <div class="tab-pane fade" id="categories-content" role="tabpanel">
                    @if(isset($stats['par_categorie']) && $stats['par_categorie']->count() > 0)
                    <div class="row">
        
        @foreach($stats['par_categorie'] as $index => $categorie)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="category-card" data-category="{{ $categorie['name'] }}">
                <div class="category-card-header">
                    <div class="category-icon-wrapper">
                        <i class="fas fa-folder-open"></i>
                    </div>
                    <h3 class="category-count">{{ $categorie['count'] }}</h3>
                </div>
                <div class="category-card-body">
                    <h6 class="category-name">{{ $categorie['name'] }}</h6>
                    <p class="category-desc">{{ $categorie['count'] }} média(s) disponible(s)</p>
                    <button class="btn-category-filter" onclick="filterByCategory('{{ $categorie['name'] }}')">
                        <i class="fas fa-filter me-2"></i>Voir les médias
                    </button>
                </div>
            </div>
        </div>
        @endforeach
                    </div>
                    
                    <!-- Bouton pour afficher tous les médias -->
                    <div class="text-center mt-4">
                        <button class="btn btn-outline-light btn-lg" onclick="showAllMedia()">
                            <i class="fas fa-th-large me-2"></i>Afficher tous les médias
                        </button>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-white">Aucune catégorie disponible</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Documents -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="text-white mb-0">
                <i class="fas fa-list me-2"></i>Liste des Documents
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Prévisualisation</th>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Destinataires</th>
                            <th>À la UNE</th>
                            <th>Date d'ajout</th>
                            <th>Téléchargements</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr class="media-row" 
                                data-category="{{ $item->libraryCategory->name ?? 'Non catégorisé' }}"
                                data-spaces="{{ !empty($item->recipients) ? implode(',', $item->recipients) : 'tous' }}">
                                <td>
                                    @if($item->cover_image)
                                        {{-- Afficher l'image de couverture si elle existe --}}
                                        <img src="{{ asset('storage/' . $item->cover_image) }}" alt="{{ $item->title }}" width="80" class="rounded shadow-sm">
                                    @elseif(in_array(strtolower($item->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                                        {{-- Sinon, afficher le fichier principal s'il est une image --}}
                                        <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->title }}" width="80" class="rounded shadow-sm">
                                    @else
                                        {{-- Sinon, afficher une icône de fichier --}}
                                        <div style="width: 80px; height: 60px; background-color: #334155;" class="rounded shadow-sm d-flex align-items-center justify-content-center">
                                            <i class="fas fa-file-alt text-white fa-2x"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ asset('storage/' . $item->path) }}" target="_blank" class="text-white">{{ $item->title }}</a>
                                </td>
                                <td>{{ $item->libraryCategory->name ?? 'N/A' }}</td>
                                <td>
                                    @if(!empty($item->recipients))
                                        @foreach($item->recipients as $recipient)
                                            <span class="badge bg-info me-1">{{ ucfirst(str_replace(['-', '_'], ' ', $recipient)) }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-light text-dark">Tous</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->is_featured)
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-star me-1"></i>À la UNE
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-download me-1"></i>{{ $item->downloads_count ?? 0 }}
                                    </span>
                                </td>
                                <td>
                                    @if($item->status == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Inactif</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{ route('admin.bibliotheque.show', $item) }}" class="btn btn-sm btn-outline-info" title="Voir"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.bibliotheque.edit', $item) }}" class="btn btn-sm btn-outline-warning" title="Modifier"><i class="fas fa-edit"></i></a>
                                    
                                    <form action="{{ route('admin.bibliotheque.toggleStatus', $item) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm {{ $item->status == 'active' ? 'btn-outline-secondary' : 'btn-outline-success' }}" title="{{ $item->status == 'active' ? 'Désactiver' : 'Activer' }}">
                                            <i class="fas fa-toggle-{{ $item->status == 'active' ? 'on' : 'off' }}"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.bibliotheque.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Aucun média trouvé dans la bibliothèque.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variable pour stocker la catégorie actuellement filtrée
let currentFilter = null;

/**
 * Filtrer les médias par catégorie
 */
function filterByCategory(categoryName) {
    const rows = document.querySelectorAll('.media-row');
    const categoryCards = document.querySelectorAll('.category-card');
    let visibleCount = 0;
    
    // Mettre à jour la catégorie actuelle
    currentFilter = categoryName;
    
    // Parcourir toutes les lignes
    rows.forEach(row => {
        const rowCategory = row.getAttribute('data-category');
        
        if (rowCategory === categoryName) {
            row.classList.remove('hidden');
            // Animation d'apparition
            row.style.animation = 'fadeIn 0.5s ease';
            visibleCount++;
        } else {
            row.classList.add('hidden');
        }
    });
    
    // Mettre en surbrillance la carte sélectionnée
    categoryCards.forEach(card => {
        const cardCategory = card.getAttribute('data-category');
        if (cardCategory === categoryName) {
            card.style.borderColor = '#10b981';
            card.style.boxShadow = '0 12px 40px rgba(16, 185, 129, 0.4)';
        } else {
            card.style.borderColor = '#334155';
            card.style.boxShadow = 'none';
        }
    });
    
    // Afficher une notification
    showNotification(`${visibleCount} média(s) dans la catégorie "${categoryName}"`, 'info');
    
    // Scroll vers le tableau
    document.querySelector('.table-responsive').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
}

/**
 * Afficher tous les médias
 */
function showAllMedia() {
    const rows = document.querySelectorAll('.media-row');
    const categoryCards = document.querySelectorAll('.category-card');
    const spaceCards = document.querySelectorAll('.space-card');
    
    // Réinitialiser le filtre
    currentFilter = null;
    
    // Afficher toutes les lignes
    rows.forEach(row => {
        row.classList.remove('hidden');
        row.style.animation = 'fadeIn 0.5s ease';
    });
    
    // Réinitialiser le style des cartes
    categoryCards.forEach(card => {
        card.style.borderColor = '#334155';
        card.style.boxShadow = 'none';
    });
    
    spaceCards.forEach(card => {
        card.style.borderColor = '#334155';
        card.style.boxShadow = 'none';
    });
    
    // Afficher une notification
    showNotification(`Tous les médias sont maintenant affichés (${rows.length} au total)`, 'success');
    
    // Scroll vers le tableau
    document.querySelector('.table-responsive').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
}

/**
 * Filtrer les médias par espace de formation
 */
function filterBySpace(spaceSlug) {
    const rows = document.querySelectorAll('.media-row');
    const spaceCards = document.querySelectorAll('.space-card');
    const categoryCards = document.querySelectorAll('.category-card');
    let visibleCount = 0;
    
    // Mettre à jour le filtre actuel
    currentFilter = spaceSlug;
    
    // Parcourir toutes les lignes
    rows.forEach(row => {
        const rowSpaces = row.getAttribute('data-spaces');
        
        // Vérifier si le média est disponible pour cet espace
        if (rowSpaces === 'tous' || rowSpaces.includes(spaceSlug)) {
            row.classList.remove('hidden');
            row.style.animation = 'fadeIn 0.5s ease';
            visibleCount++;
        } else {
            row.classList.add('hidden');
        }
    });
    
    // Mettre en surbrillance la carte sélectionnée
    spaceCards.forEach(card => {
        const cardSpace = card.getAttribute('data-space');
        if (cardSpace === spaceSlug) {
            card.style.borderColor = '#10b981';
            card.style.boxShadow = '0 12px 40px rgba(16, 185, 129, 0.4)';
        } else {
            card.style.borderColor = '#334155';
            card.style.boxShadow = 'none';
        }
    });
    
    // Réinitialiser les cartes de catégories
    categoryCards.forEach(card => {
        card.style.borderColor = '#334155';
        card.style.boxShadow = 'none';
    });
    
    // Nom lisible de l'espace
    const spaceNames = {
        'design-graphique': 'Design Graphique',
        'community-management': 'Community Management',
        'gestion-informatique': 'Gestion Informatique',
        'intelligence-artificielle': 'Intelligence Artificielle'
    };
    
    // Afficher une notification
    showNotification(`${visibleCount} média(s) pour l'espace "${spaceNames[spaceSlug]}"`, 'info');
    
    // Scroll vers le tableau
    document.querySelector('.table-responsive').scrollIntoView({ 
        behavior: 'smooth', 
        block: 'start' 
    });
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

// Au chargement de la page, ajouter des événements sur les cartes
document.addEventListener('DOMContentLoaded', function() {
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            const categoryName = this.getAttribute('data-category');
            filterByCategory(categoryName);
        });
    });
});
</script>
@endpush

@endsection
