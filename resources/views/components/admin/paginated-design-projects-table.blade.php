{{--
    📋 SECTION PROJETS RÉELS (DESIGN PROJECTS) - Composant Dédié

    ✨ Fonctionnalités :
    - Interface dashboard cohérente spécifique aux projets design
    - Tableau responsive avec colonnes adaptées aux design_projects
    - Pagination et filtrage par session
    - Actions CRUD complètes (voir, éditer, valider, supprimer)
    - Gestion spécifique des types de projets design (logo, web, print, etc.)
    - Support JSON pour les logiciels utilisés

    @param array $imagesData - Données des projets design avec pagination
    @param string $tableId - ID unique du tableau
    @param string $pageParam - Paramètre de pagination (ex: 'design_images_page')
    @param int $studentId - ID de l'étudiant
--}}

@props([
    'imagesData',
    'tableId',
    'pageParam' => 'design_images_page',
    'studentId',
    'title' => 'Projets Réels (Design Projects)',
    'icon' => 'fas fa-paint-brush',
    'description' => 'Projets de design graphique avec images et fichiers'
])

<style>
/* Dashboard Variables - Cohérent avec le reste de l'application */
:root {
    --dashboard-bg: rgba(255, 255, 255, 0.1);
    --dashboard-border: rgba(255, 255, 255, 0.2);
    --dashboard-hover: rgba(255, 255, 255, 0.15);
    --dashboard-text: #ffffff;
    --dashboard-accent: #3399ff;
    --dashboard-success: #28a745;
    --dashboard-warning: #ffc107;
    --dashboard-danger: #dc3545;
    --dashboard-info: #17a2b8;
    --dashboard-transition: all 0.3s ease;
}

/* Section principale */
.dashboard-section {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
    border-radius: 12px;
    backdrop-filter: blur(10px);
    margin-bottom: 2rem;
    overflow: hidden;
}

.dashboard-section-header {
    background: linear-gradient(135deg, var(--dashboard-accent), #0066cc);
    padding: 1.5rem;
    border-bottom: 1px solid var(--dashboard-border);
}

.dashboard-section-title {
    color: var(--dashboard-text);
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.dashboard-section-description {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    margin: 0.5rem 0 0 0;
}

/* Pagination et statistiques */
.dashboard-pagination-info {
    background: rgba(255, 255, 255, 0.05);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--dashboard-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.dashboard-stats {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.dashboard-stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--dashboard-text);
    font-size: 0.9rem;
}

.dashboard-stat-value {
    background: var(--dashboard-accent);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
    font-size: 0.8rem;
}

/* Tableau */
.table-dark {
    --bs-table-bg: transparent;
    --bs-table-border-color: var(--dashboard-border);
}

.table-dark th {
    background: rgba(255, 255, 255, 0.1);
    border-color: var(--dashboard-border);
    color: var(--dashboard-text);
    font-weight: 600;
    font-size: 0.85rem;
    padding: 1rem 0.75rem;
}

.table-dark td {
    border-color: var(--dashboard-border);
    color: var(--dashboard-text);
    padding: 0.75rem;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background: var(--dashboard-hover);
    transition: var(--dashboard-transition);
}

/* Badges et statuts */
.badge {
    font-size: 0.75rem;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-weight: 500;
}

/* Actions */
.dashboard-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.dashboard-btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.75rem;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    transition: var(--dashboard-transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.dashboard-btn-primary {
    background: var(--dashboard-accent);
    color: white;
}

.dashboard-btn-success {
    background: var(--dashboard-success);
    color: white;
}

.dashboard-btn-danger {
    background: var(--dashboard-danger);
    color: white;
}

.dashboard-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Messages d'état vide */
.dashboard-empty-state {
    text-align: center;
    padding: 3rem 2rem;
    color: rgba(255, 255, 255, 0.7);
}

.dashboard-empty-icon {
    font-size: 3rem;
    color: var(--dashboard-accent);
    margin-bottom: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .dashboard-pagination-info {
        flex-direction: column;
        align-items: stretch;
    }
    
    .dashboard-stats {
        justify-content: center;
    }
    
    .dashboard-actions {
        flex-direction: column;
    }
    
    .dashboard-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- Section principale -->
<div class="dashboard-section">
    <!-- En-tête de section -->
    <div class="dashboard-section-header">
        <h4 class="dashboard-section-title">
            <i class="{{ $icon }}"></i>
            {{ $title }}
        </h4>
        <p class="dashboard-section-description">{{ $description }}</p>
    </div>

    @if($imagesData['pagination']['total_projects'] > 0)
        <!-- Informations de pagination et statistiques -->
        <div class="dashboard-pagination-info">
            <div class="dashboard-stats">
                <div class="dashboard-stat-item">
                    <i class="fas fa-project-diagram text-info"></i>
                    <span>Total:</span>
                    <span class="dashboard-stat-value">{{ $imagesData['pagination']['total_projects'] }}</span>
                </div>
                <div class="dashboard-stat-item">
                    <i class="fas fa-calendar-alt text-warning"></i>
                    <span>Sessions:</span>
                    <span class="dashboard-stat-value">{{ count($imagesData['projects_by_session']) }}</span>
                </div>
                <div class="dashboard-stat-item">
                    <i class="fas fa-images text-success"></i>
                    <span>Page:</span>
                    <span class="dashboard-stat-value">{{ $imagesData['pagination']['current_page'] }}/{{ $imagesData['pagination']['total_pages'] }}</span>
                </div>
            </div>
        </div>

        <!-- Tableau des projets design -->
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle" id="{{ $tableId }}">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 30%;">
                            <i class="fas fa-project-diagram me-2"></i>Titre du Projet
                        </th>
                        <th style="width: 15%;" class="text-center">
                            <i class="fas fa-users me-2"></i>Mode
                        </th>
                        <th style="width: 25%;">
                            <i class="fas fa-tools me-2"></i>Logiciels
                        </th>
                        <th style="width: 15%;" class="text-center">
                            <i class="fas fa-calendar me-2"></i>Date
                        </th>
                        <th style="width: 10%;" class="text-center">
                            <i class="fas fa-info-circle me-2"></i>Status
                        </th>
                        <th style="width: 5%;" class="text-center">
                            <i class="fas fa-cogs me-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($imagesData['projects_by_session'] as $sessionLabel => $projects)
                        <!-- En-tête de session -->
                        <tr class="table-info">
                            <td colspan="6" class="fw-bold py-2">
                                <i class="fas fa-calendar-alt me-2"></i>{{ $sessionLabel }}
                                <span class="badge bg-info ms-2">{{ count($projects) }} projets</span>
                            </td>
                        </tr>

                        @foreach($projects as $project)
                            <tr>
                                <!-- Colonne Titre du Projet -->
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-paint-brush text-primary fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-truncate" style="max-width: 200px;">
                                                {{ $project->title }}
                                            </div>
                                            @if(!empty($project->description))
                                                <div class="mt-1">
                                                    <small class="text-white-50">
                                                        {{ Str::limit($project->description, 50) }}
                                                    </small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <!-- Colonne Mode de Projet -->
                                <td class="text-center">
                                    @php
                                        $projectMode = $project->project_mode ?? 'solo';
                                        $modeClass = $projectMode === 'solo' ? 'bg-info' : 'bg-warning';
                                        $modeIcon = $projectMode === 'solo' ? 'fas fa-user' : 'fas fa-users';
                                        $modeLabel = $projectMode === 'solo' ? 'Solo' : 'Groupe';
                                    @endphp
                                    <span class="badge {{ $modeClass }} px-2 py-1">
                                        <i class="{{ $modeIcon }} me-1"></i>{{ $modeLabel }}
                                    </span>
                                </td>

                                <!-- Colonne Logiciels utilisés -->
                                <td>
                                    <div class="software-list">
                                        @php
                                            // Les données sont déjà correctement formatées par le service
                                            $softwareUsed = $project->software_used ?? [];
                                            
                                            // Mapping des logiciels avec icônes spécifiques
                                            $softwareIcons = [
                                                'photoshop' => 'fab fa-adobe',
                                                'illustrator' => 'fab fa-adobe', 
                                                'indesign' => 'fab fa-adobe',
                                                'after_effects' => 'fab fa-adobe',
                                                'premiere_pro' => 'fab fa-adobe',
                                                'xd' => 'fab fa-adobe',
                                                'figma' => 'fab fa-figma',
                                                'sketch' => 'fas fa-pencil-ruler',
                                                'canva' => 'fas fa-palette',
                                                'other' => 'fas fa-tools'
                                            ];
                                            
                                            $softwareLabels = [
                                                'photoshop' => 'Photoshop',
                                                'illustrator' => 'Illustrator', 
                                                'indesign' => 'InDesign',
                                                'after_effects' => 'After Effects',
                                                'premiere_pro' => 'Premiere Pro',
                                                'xd' => 'Adobe XD',
                                                'figma' => 'Figma',
                                                'sketch' => 'Sketch',
                                                'canva' => 'Canva',
                                                'other' => 'Autre'
                                            ];
                                        @endphp
                                        
                                        @if(!empty($softwareUsed) && is_array($softwareUsed) && count($softwareUsed) > 0)
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach(array_slice($softwareUsed, 0, 3) as $software)
                                                    @php
                                                        $softwareKey = is_string($software) ? strtolower(trim($software)) : 'other';
                                                        $softwareName = $softwareLabels[$softwareKey] ?? ucfirst($software);
                                                        $softwareIcon = $softwareIcons[$softwareKey] ?? 'fas fa-tools';
                                                    @endphp
                                                    <span class="badge bg-primary text-white" style="font-size: 0.7rem;">
                                                        <i class="{{ $softwareIcon }} me-1"></i>{{ $softwareName }}
                                                    </span>
                                                @endforeach
                                                @if(count($softwareUsed) > 3)
                                                    <span class="badge bg-info text-white" style="font-size: 0.7rem;">
                                                        +{{ count($softwareUsed) - 3 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted small">
                                                <i class="fas fa-minus-circle me-1"></i>Non spécifié
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Colonne Date -->
                                <td class="text-center">
                                    <div class="text-white">{{ $project->formatted_date ?? \Carbon\Carbon::parse($project->created_at)->format('d/m/Y') }}</div>
                                </td>

                                <!-- Colonne Status -->
                                <td class="text-center">
                                    @php
                                        $statusClass = match($project->status) {
                                            'validated' => 'bg-success',
                                            'active' => 'bg-info',
                                            'draft' => 'bg-secondary',
                                            'pending' => 'bg-warning',
                                            default => 'bg-light'
                                        };

                                        $statusIcon = match($project->status) {
                                            'validated' => 'fas fa-check-circle',
                                            'active' => 'fas fa-play-circle',
                                            'draft' => 'fas fa-edit',
                                            'pending' => 'fas fa-clock',
                                            default => 'fas fa-question-circle'
                                        };

                                        $statusText = match($project->status) {
                                            'validated' => 'Validé',
                                            'active' => 'Actif',
                                            'draft' => 'Brouillon',
                                            'pending' => 'En attente',
                                            default => 'Inconnu'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} px-2 py-1">
                                        <i class="{{ $statusIcon }} me-1"></i>{{ $statusText }}
                                    </span>
                                </td>

                                <!-- Colonne Actions -->
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm"
                                            onclick="viewDesignProjectDetails({{ $project->id }})"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            data-bs-html="true"
                                            title="<i class='fas fa-eye text-info'></i> <strong>Voir les détails</strong><br><small>Affiche toutes les informations du projet design</small>"
                                            style="white-space: nowrap;">
                                        <i class="fas fa-eye me-1"></i>Voir le projet réel
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($imagesData['pagination']['total_pages'] > 1)
            <div class="dashboard-pagination-info">
                <div class="d-flex justify-content-center">
                    <nav aria-label="Pagination des projets design">
                        <ul class="pagination pagination-sm">
                            @if($imagesData['pagination']['current_page'] > 1)
                                <li class="page-item">
                                    <a class="page-link" href="?{{ $pageParam }}={{ $imagesData['pagination']['current_page'] - 1 }}">
                                        <i class="fas fa-chevron-left"></i> Précédent
                                    </a>
                                </li>
                            @endif

                            @for($i = 1; $i <= $imagesData['pagination']['total_pages']; $i++)
                                <li class="page-item {{ $i == $imagesData['pagination']['current_page'] ? 'active' : '' }}">
                                    <a class="page-link" href="?{{ $pageParam }}={{ $i }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if($imagesData['pagination']['current_page'] < $imagesData['pagination']['total_pages'])
                                <li class="page-item">
                                    <a class="page-link" href="?{{ $pageParam }}={{ $imagesData['pagination']['current_page'] + 1 }}">
                                        Suivant <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        @endif

    @else
        <!-- État vide -->
        <div class="dashboard-empty-state">
            <div class="dashboard-empty-icon">
                <i class="fas fa-paint-brush"></i>
            </div>
            <h5 class="text-white-75">Aucun projet design trouvé</h5>
            <p class="text-white-50">Cet étudiant n'a pas encore créé de projets de design graphique.</p>
        </div>
    @endif
</div>

{{-- 
    Les fonctions JavaScript pour les actions (viewProjectDetails, validateProject, deleteProject) 
    sont déjà définies dans la page principale admin/students/show.blade.php 
    et fonctionnent pour tous les types de projets.
--}}
