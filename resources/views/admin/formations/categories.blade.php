@extends('layouts.admin')

@section('title', 'Gestion des Catégories')

@push('styles')
<style>
    /* Cartes de statistiques modernes */
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
    
    .stat-card-cyan {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
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

    .status-badge {
        display: inline-block;
        padding: .35em .65em;
        font-size: .75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
    }
    .status-badge.active { background-color: #198754; }
    .status-badge.inactive { background-color: #ffc107; color: #000; }
    th, td { vertical-align: middle; }
    
    .module-filter-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }
    
    .category-row {
        transition: all 0.3s ease;
    }
    
    .category-row:hover {
        background-color: rgba(255, 255, 255, 0.1) !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Gestion des Catégories</h1>
        <a href="{{ route('admin.formations.categories.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Créer une catégorie
        </a>
    </div>

    <!-- Statistiques Globales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total_categories'] }}</h3>
                    <p class="stat-label">Total Catégories</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total_formations'] }}</h3>
                    <p class="stat-label">Total Formations</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['categories_actives'] }}</h3>
                    <p class="stat-label">Catégories Actives</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['categories_sans_formation'] }}</h3>
                    <p class="stat-label">Sans Formation</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par Module -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-chart-pie me-2"></i>Statistiques par Module</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @php
                            $moduleColors = [
                                'design-graphique' => ['bg' => 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)', 'icon' => 'fa-palette', 'name' => 'Design Graphique'],
                                'community-management' => ['bg' => 'linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%)', 'icon' => 'fa-users', 'name' => 'Community Management'],
                                'gestion-informatique' => ['bg' => 'linear-gradient(135deg, #ff9800 0%, #fb8c00 100%)', 'icon' => 'fa-laptop-code', 'name' => 'Gestion Informatique'],
                                'intelligence-artificielle' => ['bg' => 'linear-gradient(135deg, #26c6da 0%, #00acc1 100%)', 'icon' => 'fa-brain', 'name' => 'Intelligence Artificielle'],
                            ];
                        @endphp
                        
                        @foreach($moduleColors as $moduleSlug => $moduleData)
                            @php
                                $formationsCount = $statsByModule[$moduleSlug] ?? 0;
                            @endphp
                            <div class="col-md-6 col-lg-3">
                                <div class="card h-100 border-0 module-filter-card" 
                                     data-module="{{ $moduleSlug }}"
                                     style="background: {{ $moduleData['bg'] }}; cursor: pointer; transition: all 0.3s ease;"
                                     onclick="filterByModule('{{ $moduleSlug }}')">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="text-white-50 text-uppercase mb-2" style="font-size: 0.75rem;">{{ $moduleData['name'] }}</h6>
                                                <h2 class="mb-0 fw-bold">{{ $formationsCount }}</h2>
                                                <p class="mb-0 mt-1" style="font-size: 0.85rem;">Formation(s)</p>
                                            </div>
                                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                                <i class="fas {{ $moduleData['icon'] }} fa-2x"></i>
                                            </div>
                                        </div>
                                        <div class="border-top border-white border-opacity-25 pt-2 mt-3">
                                            <small><i class="fas fa-graduation-cap me-1"></i>{{ $formationsCount }} formation(s)</small>
                                        </div>
                                        <div class="mt-2 text-end">
                                            <small class="text-white-50"><i class="fas fa-mouse-pointer me-1"></i>Cliquer pour filtrer</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des catégories -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste des Catégories</h5>
            <button class="btn btn-sm btn-outline-light" onclick="resetFilters()" id="resetFiltersBtn" style="display: none;">
                <i class="fas fa-redo me-1"></i>Réinitialiser les filtres
            </button>
        </div>
        <div class="card-body">
            <div id="filterInfo" class="alert alert-info mb-3" style="display: none; background-color: #1e40af; border-color: #1e40af; color: white;">
                <i class="fas fa-filter me-2"></i><span id="filterText"></span>
            </div>
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Formations</th>
                            <th>Statut</th>
                            <th>Date de Création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr class="category-row" 
                                data-module="{{ $category['module'] ?? '' }}"
                                data-status="{{ $category['status'] }}">
                                <td class="fw-bold">{{ $category['name'] }}</td>
                                <td><code class="text-info">{{ $category['slug'] }}</code></td>
                                <td>
                                    @if(!empty($category['module']))
                                        @php
                                            $moduleInfo = [
                                                'design-graphique' => ['name' => 'Design Graphique', 'color' => '#2a5298', 'icon' => 'fa-palette'],
                                                'community-management' => ['name' => 'Community Management', 'color' => '#29b6f6', 'icon' => 'fa-users'],
                                                'gestion-informatique' => ['name' => 'Gestion Informatique', 'color' => '#fb8c00', 'icon' => 'fa-laptop-code'],
                                                'intelligence-artificielle' => ['name' => 'Intelligence Artificielle', 'color' => '#00acc1', 'icon' => 'fa-brain'],
                                            ];
                                            $info = $moduleInfo[$category['module']] ?? ['name' => $category['module'], 'color' => '#6c757d', 'icon' => 'fa-folder'];
                                        @endphp
                                        <span class="badge" style="background-color: {{ $info['color'] }};">
                                            <i class="fas {{ $info['icon'] }} me-1"></i>{{ $info['name'] }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Non défini</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($category['description']))
                                        {{ Str::limit($category['description'], 50) }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $category['formations_count'] }}</span>
                                </td>
                                <td>
                                    <span class="status-badge {{ $category['status'] }}">
                                        {{ $category['status'] === 'active' ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($category['created_at'])->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.formations.categories.edit', $category['id']) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.formations.categories.delete', $category['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Aucune catégorie trouvée.</td>
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
// Variables globales pour le filtrage
let currentModuleFilter = null;

// Filtrer par module
function filterByModule(module) {
    currentModuleFilter = module;
    
    const rows = document.querySelectorAll('.category-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const rowModule = row.getAttribute('data-module');
        if (rowModule === module) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Mettre à jour l'interface
    updateFilterUI(module, visibleCount);
    highlightActiveFilters();
}

// Réinitialiser les filtres
function resetFilters() {
    currentModuleFilter = null;
    
    const rows = document.querySelectorAll('.category-row');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    // Cacher les éléments de filtre
    document.getElementById('filterInfo').style.display = 'none';
    document.getElementById('resetFiltersBtn').style.display = 'none';
    
    // Retirer les highlights
    document.querySelectorAll('.module-filter-card').forEach(card => {
        card.style.transform = 'scale(1)';
        card.style.boxShadow = 'none';
    });
}

// Mettre à jour l'interface de filtrage
function updateFilterUI(module, count) {
    const filterInfo = document.getElementById('filterInfo');
    const filterText = document.getElementById('filterText');
    const resetBtn = document.getElementById('resetFiltersBtn');
    
    const moduleNames = {
        'design-graphique': 'Design Graphique',
        'community-management': 'Community Management',
        'gestion-informatique': 'Gestion Informatique',
        'intelligence-artificielle': 'Intelligence Artificielle'
    };
    
    let text = `Filtré par module : <strong>${moduleNames[module]}</strong> (${count} catégorie(s))`;
    
    filterText.innerHTML = text;
    filterInfo.style.display = 'block';
    resetBtn.style.display = 'inline-block';
}

// Mettre en évidence les filtres actifs
function highlightActiveFilters() {
    // Réinitialiser tous les highlights
    document.querySelectorAll('.module-filter-card').forEach(card => {
        card.style.transform = 'scale(1)';
        card.style.boxShadow = 'none';
    });
    
    // Highlight le module actif
    if (currentModuleFilter) {
        const moduleCard = document.querySelector(`.module-filter-card[data-module="${currentModuleFilter}"]`);
        if (moduleCard) {
            moduleCard.style.transform = 'scale(1.05)';
            moduleCard.style.boxShadow = '0 10px 30px rgba(255, 255, 255, 0.3)';
        }
    }
}

// Effet hover pour les cartes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.module-filter-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (this.getAttribute('data-module') !== currentModuleFilter) {
                this.style.transform = 'scale(1.03)';
                this.style.boxShadow = '0 8px 20px rgba(0, 0, 0, 0.3)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (this.getAttribute('data-module') !== currentModuleFilter) {
                this.style.transform = 'scale(1)';
                this.style.boxShadow = 'none';
            }
        });
    });
});
</script>
@endpush
@endsection
