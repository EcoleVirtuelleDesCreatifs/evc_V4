@extends('layouts.admin')

@section('title', 'Liste des Formations')

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
    .status-badge.draft { background-color: #6c757d; }
    .status-badge.inactive { background-color: #ffc107; color: #000; }
    .status-badge.archived { background-color: #dc3545; }
    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Gestion des Formations</h1>
        <a href="{{ route('admin.formations.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Créer une formation</a>
    </div>

    <!-- Statistiques Globales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Formations</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['active'] }}</h3>
                    <p class="stat-label">Formations Actives</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['draft'] }}</h3>
                    <p class="stat-label">Brouillons</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['ce_mois'] }}</h3>
                    <p class="stat-label">Ajoutées ce Mois</p>
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
                                'design-graphique' => ['bg' => 'linear-gradient(135deg, #1e3c72 0%, #2a5298 100%)', 'icon' => 'fa-palette'],
                                'design-graphique-cm' => ['bg' => 'linear-gradient(135deg, #2563eb 0%, #f97316 100%)', 'icon' => 'fa-object-group'],
                                'community-management' => ['bg' => 'linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%)', 'icon' => 'fa-users'],
                                'gestion-informatique' => ['bg' => 'linear-gradient(135deg, #ff9800 0%, #fb8c00 100%)', 'icon' => 'fa-laptop-code'],
                                'intelligence-artificielle' => ['bg' => 'linear-gradient(135deg, #26c6da 0%, #00acc1 100%)', 'icon' => 'fa-brain'],
                            ];
                            $moduleNames = [
                                'design-graphique' => 'Design Graphique',
                                'design-graphique-cm' => 'Design Graphique & Community Management',
                                'community-management' => 'Community Management',
                                'gestion-informatique' => 'Gestion Informatique',
                                'intelligence-artificielle' => 'Intelligence Artificielle',
                            ];
                        @endphp

                        @foreach($moduleColors as $moduleSlug => $moduleData)
                            <div class="col-md-6 col-lg-3">
                                <div class="card h-100 border-0 module-filter-card"
                                     data-module="{{ $moduleSlug }}"
                                     style="background: {{ $moduleData['bg'] }}; cursor: pointer; transition: all 0.3s ease;"
                                     onclick="filterByModule('{{ $moduleSlug }}')">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="text-white-50 text-uppercase mb-2" style="font-size: 0.75rem;">{{ $moduleNames[$moduleSlug] ?? $moduleSlug }}</h6>
                                                <h2 class="mb-0 fw-bold">{{ $statsByModule[$moduleSlug] ?? 0 }}</h2>
                                                <p class="mb-0 mt-1" style="font-size: 0.85rem;">Formation(s) active(s)</p>
                                            </div>
                                            <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                                <i class="fas {{ $moduleData['icon'] }} fa-2x"></i>
                                            </div>
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

    <!-- Statistiques par Catégorie -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                    <h5 class="mb-0 text-white"><i class="fas fa-tags me-2"></i>Statistiques par Catégorie</h5>
                </div>
                <div class="card-body">
                    @if($statsByCategory->isEmpty())
                        <p class="text-white-50 mb-0">Aucune catégorie avec des formations actives.</p>
                    @else
                        <div class="row g-3">
                            @foreach($statsByCategory as $module => $categories)
                                <div class="col-12">
                                    <h6 class="text-white mb-3">
                                        <i class="fas {{ $moduleColors[$module]['icon'] ?? 'fa-folder' }} me-2"></i>
                                        {{ $moduleNames[$module] ?? $module }}
                                    </h6>
                                    <div class="row g-2">
                                        @foreach($categories as $category)
                                            <div class="col-md-4 col-lg-3">
                                                <div class="card bg-dark border-secondary category-filter-card"
                                                     data-category="{{ $category->category_name }}"
                                                     data-module="{{ $module }}"
                                                     style="cursor: pointer; transition: all 0.3s ease;"
                                                     onclick="filterByCategory('{{ $category->category_name }}', '{{ $module }}')">
                                                    <div class="card-body py-2">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="text-white" style="font-size: 0.9rem;">
                                                                <i class="fas fa-tag me-1"></i>{{ $category->category_name }}
                                                            </span>
                                                            <span class="badge bg-primary">{{ $category->total }}</span>
                                                        </div>
                                                        <small class="text-white-50 d-block mt-1" style="font-size: 0.7rem;">
                                                            <i class="fas fa-mouse-pointer me-1"></i>Cliquer pour filtrer
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des formations -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste des Formations</h5>
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
                            <th>Image</th>
                            <th style="min-width: 200px;">Nom de la Formation</th>
                            <th>Catégorie</th>
                            <th>Module Principal</th>
                            <th>Statut</th>
                            <th>Étudiants</th>
                            <th>Date de Création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($formations as $formation)
                            <tr class="formation-row"
                                data-module="{{ $formation->modules[0] ?? '' }}"
                                data-category="{{ $formation->category->name ?? '' }}"
                                data-status="{{ $formation->status }}">
                                <td>
                                    @if($formation->image_url)
                                        <img src="{{ \App\Models\MediaUrl::fromPath($formation->image_url) }}" alt="{{ $formation->name }}" width="60" class="rounded shadow-sm">
                                    @else
                                        <div style="width: 60px; height: 40px; background-color: #334155;" class="rounded shadow-sm d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $formation->name }}</td>
                                <td>{{ $formation->category->name ?? 'N/A' }}</td>
                                <td>{{ $formation->modules[0] ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge {{ $formation->status }}">{{ $formation->status_label }}</span>
                                </td>
                                <td>{{ $formation->students_count }}</td>
                                <td>{{ $formation->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.formations.show', $formation) }}" class="btn btn-sm btn-info">Voir</a>
                                    <a href="{{ route('admin.formations.edit', $formation) }}" class="btn btn-sm btn-warning">Modifier</a>
                                    <form action="{{ route('admin.formations.toggleStatus', $formation) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($formation->status === 'active')
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="Désactiver">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Activer">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        @endif
                                    </form>
                                    <form action="{{ route('admin.formations.destroy', $formation) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Aucune formation trouvée.</td>
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
let currentCategoryFilter = null;

// Filtrer par module
function filterByModule(module) {
    currentModuleFilter = module;
    currentCategoryFilter = null; // Réinitialiser le filtre de catégorie

    const rows = document.querySelectorAll('.formation-row');
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
    updateFilterUI(module, null, visibleCount);
    highlightActiveFilters();
}

// Filtrer par catégorie
function filterByCategory(category, module) {
    currentModuleFilter = module;
    currentCategoryFilter = category;

    const rows = document.querySelectorAll('.formation-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const rowModule = row.getAttribute('data-module');
        const rowCategory = row.getAttribute('data-category');

        if (rowModule === module && rowCategory === category) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Mettre à jour l'interface
    updateFilterUI(module, category, visibleCount);
    highlightActiveFilters();
}

// Réinitialiser les filtres
function resetFilters() {
    currentModuleFilter = null;
    currentCategoryFilter = null;

    const rows = document.querySelectorAll('.formation-row');
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

    document.querySelectorAll('.category-filter-card').forEach(card => {
        card.style.transform = 'scale(1)';
        card.style.border = '';
    });
}

// Mettre à jour l'interface de filtrage
function updateFilterUI(module, category, count) {
    const filterInfo = document.getElementById('filterInfo');
    const filterText = document.getElementById('filterText');
    const resetBtn = document.getElementById('resetFiltersBtn');

    const moduleNames = {
        'design-graphique': 'Design Graphique',
        'design-graphique-cm': 'Design Graphique & Community Management',
        'community-management': 'Community Management',
        'gestion-informatique': 'Gestion Informatique',
        'intelligence-artificielle': 'Intelligence Artificielle'
    };

    let text = '';
    if (category) {
        text = `Filtré par : <strong>${moduleNames[module]}</strong> → <strong>${category}</strong> (${count} formation(s))`;
    } else {
        text = `Filtré par module : <strong>${moduleNames[module]}</strong> (${count} formation(s))`;
    }

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

    document.querySelectorAll('.category-filter-card').forEach(card => {
        card.style.transform = 'scale(1)';
        card.style.border = '';
    });

    // Highlight le module actif
    if (currentModuleFilter) {
        const moduleCard = document.querySelector(`.module-filter-card[data-module="${currentModuleFilter}"]`);
        if (moduleCard) {
            moduleCard.style.transform = 'scale(1.05)';
            moduleCard.style.boxShadow = '0 10px 30px rgba(255, 255, 255, 0.3)';
        }
    }

    // Highlight la catégorie active
    if (currentCategoryFilter) {
        const categoryCard = document.querySelector(`.category-filter-card[data-category="${currentCategoryFilter}"][data-module="${currentModuleFilter}"]`);
        if (categoryCard) {
            categoryCard.style.transform = 'scale(1.05)';
            categoryCard.style.border = '2px solid #60a5fa';
        }
    }
}

// Effet hover pour les cartes
document.addEventListener('DOMContentLoaded', function() {
    // Hover sur les cartes de module
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

    // Hover sur les cartes de catégorie
    document.querySelectorAll('.category-filter-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (this.getAttribute('data-category') !== currentCategoryFilter) {
                this.style.transform = 'scale(1.03)';
                this.style.border = '2px solid #3b82f6';
            }
        });

        card.addEventListener('mouseleave', function() {
            if (this.getAttribute('data-category') !== currentCategoryFilter) {
                this.style.transform = 'scale(1)';
                this.style.border = '';
            }
        });
    });
});
</script>
@endpush
@endsection
