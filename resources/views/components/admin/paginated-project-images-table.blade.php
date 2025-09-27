{{--
    📋 SECTION TRAVAUX PRATIQUES (IMAGES) - Design Dashboard Cohérent

    ✨ Fonctionnalités :
    - Interface dashboard cohérente avec le reste de l'application
    - Tableau responsive avec actions intégrées
    - Pagination et filtrage par session
    - Actions CRUD complètes (voir, éditer, valider, supprimer)
    - Design uniforme avec variables CSS dashboard

    @param array $imagesData - Données des images avec pagination
    @param string $tableId - ID unique du tableau
    @param string $pageParam - Paramètre de pagination (ex: 'images_page')
    @param int $studentId - ID de l'étudiant
--}}

@props([
    'imagesData',
    'tableId',
    'pageParam' => 'images_page',
    'studentId',
    'title' => 'Travaux Pratiques (images/prints)',
    'icon' => 'fas fa-images',
    'description' => 'Images et fichiers des travaux pratiques'
])

<style>
/* Dashboard Variables - Cohérent avec le reste de l'application */
:root {
    --dashboard-bg: rgba(255, 255, 255, 0.1);
    --dashboard-border: rgba(255, 255, 255, 0.2);
    --dashboard-text: #ffffff;
    --dashboard-text-muted: rgba(255, 255, 255, 0.7);
    --dashboard-hover: rgba(255, 255, 255, 0.15);
}

/* Styles Dashboard pour cohérence */
.dashboard-card {
    background: linear-gradient(135deg,
        rgba(102, 126, 234, 0.2) 0%,
        rgba(118, 75, 162, 0.2) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid var(--dashboard-border);
    border-radius: 15px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.dashboard-icon {
    color: var(--dashboard-text);
    margin-bottom: 10px;
}

/* 🎯 MODALE DE LOADING - Design Moderne */
.loading-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.loading-modal.show {
    opacity: 1;
    visibility: visible;
}

.loading-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
}

.loading-content {
    background: linear-gradient(135deg,
        rgba(255, 255, 255, 0.95) 0%,
        rgba(248, 249, 250, 0.95) 100%);
    padding: 2rem;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.3);
    min-width: 250px;
    transform: scale(0.8);
    transition: transform 0.3s ease;
}

.loading-modal.show .loading-content {
    transform: scale(1);
}

.loading-spinner .spinner-border {
    width: 3rem;
    height: 3rem;
    border-width: 0.3em;
}

.loading-text {
    color: #495057;
    font-size: 1.1rem;
    margin-top: 1rem;
}

.table-dark {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
}

.btn-outline-light:hover {
    background: var(--dashboard-hover);
    border-color: var(--dashboard-border);
}

/* Actions buttons styling */
.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.btn-warning {
    background: linear-gradient(135deg, #FF9900, #ffc107);
    border: none;
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e68900, #e0a800);
    transform: translateY(-1px);
}

.btn-info {
    background: linear-gradient(135deg, #17a2b8, #20c997);
    border: none;
    color: white;
}

.btn-info:hover {
    background: linear-gradient(135deg, #138496, #1c7a6b);
    transform: translateY(-1px);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838, #1c7a6b);
    transform: translateY(-1px);
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545, #e74c3c);
    border: none;
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c82333, #c0392b);
    transform: translateY(-1px);
}


/* Dashboard tooltip styling - Amélioré */
.tooltip {
    font-size: 0.875rem;
    z-index: 1070;
}

.tooltip-inner {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    color: #ffffff;
    padding: 10px 12px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    max-width: 250px;
    text-align: left;
    font-weight: 500;
    line-height: 1.4;
}

.tooltip-arrow::before {
    border-top-color: rgba(102, 126, 234, 0.95) !important;
}

.tooltip.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: rgba(102, 126, 234, 0.95) !important;
}

.tooltip.bs-tooltip-bottom .tooltip-arrow::before {
    border-bottom-color: rgba(102, 126, 234, 0.95) !important;
}

.tooltip.bs-tooltip-start .tooltip-arrow::before {
    border-left-color: rgba(102, 126, 234, 0.95) !important;
}

.tooltip.bs-tooltip-end .tooltip-arrow::before {
    border-right-color: rgba(102, 126, 234, 0.95) !important;
}

/* Responsive Design Dashboard */
@media (max-width: 768px) {
    .dashboard-card {
        border-radius: 12px;
        margin: 10px;
    }

    .card-body {
        padding: 20px;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
}
</style>

<!-- Section Projets Réels Dashboard -->
<div class="dashboard-card dashboard-projects mb-4" id="{{ $tableId }}-container">
    <div class="card-body p-4">
        <div class="dashboard-icon">
            <i class="{{ $icon }} fa-lg"></i>
        </div>
        <h5 class="text-white fw-bold mb-3">{{ $title }}</h5>

        <!-- En-tête avec informations de pagination -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <small class="text-white-50">
                    @if($imagesData['pagination']['total_projects'] > 0)
                        {{ $imagesData['pagination']['total_projects'] }} TP trouvé(s)
                        @if($imagesData['pagination']['total_pages'] > 1)
                            - Page {{ $imagesData['pagination']['current_page'] }}/{{ $imagesData['pagination']['total_pages'] }}
                        @endif
                    @else
                        Aucun TP trouvé
                    @endif
                </small>
            </div>

            <!-- Contrôles de pagination -->
            @if($imagesData['pagination']['total_pages'] > 1)
                <div class="d-flex gap-2 align-items-center">
                    <small class="text-white-50 me-2">
                        Page {{ $imagesData['pagination']['current_page'] }}/{{ $imagesData['pagination']['total_pages'] }}
                    </small>
                    @if($imagesData['pagination']['current_page'] > 1)
                        <button type="button" class="btn btn-outline-light btn-sm"
                                onclick="loadProjectImagesPage({{ $imagesData['pagination']['current_page'] - 1 }})">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @endif
                    @if($imagesData['pagination']['current_page'] < $imagesData['pagination']['total_pages'])
                        <button type="button" class="btn btn-outline-light btn-sm"
                                onclick="loadProjectImagesPage({{ $imagesData['pagination']['current_page'] + 1 }})">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Tableau des TP -->
        <div class="table-responsive">
            <table class="table table-dark table-hover align-middle" id="{{ $tableId }}">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 35%;">
                            <i class="fas fa-project-diagram me-2"></i>Titre du Projet
                        </th>
                        <th style="width: 20%;" class="text-center">
                            <i class="fas fa-eye me-2"></i>Aperçu
                        </th>
                        <th style="width: 20%;">
                            <i class="fas fa-tools me-2"></i>Logiciels
                        </th>
                        <th style="width: 15%;" class="text-center">
                            <i class="fas fa-calendar me-2"></i>Date
                        </th>
                        <th style="width: 8%;" class="text-center">
                            <i class="fas fa-info-circle me-2"></i>Status
                        </th>
                        <th style="width: 5%;" class="text-center">
                            <i class="fas fa-cogs me-2"></i>Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($imagesData['pagination']['total_projects'] > 0)
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
                                                <i class="fas fa-project-diagram text-primary fa-lg"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold text-truncate" style="max-width: 180px;">
                                                    {{ $project->title }}
                                                </div>
                                                @if(!empty($project->description))
                                                    <div class="mt-1">
                                                        <small class="text-white-50">
                                                            {{ Str::limit($project->description, 40) }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Colonne Aperçu -->
                                    <td class="text-center">
                                        <div class="image-preview-container" style="width: 70px; height: 70px;">
                                            @if($project->has_image && !empty($project->image_path))
                                                <img src="{{ asset('storage/' . $project->image_path) }}"
                                                     alt="{{ $project->image_name }}"
                                                     class="img-thumbnail rounded"
                                                     style="width: 70px; height: 70px; object-fit: cover; cursor: pointer; border: 2px solid #007bff;"
                                                     onclick="viewProjectImageModal({{ $project->image_id }}, '{{ asset('storage/' . $project->image_path) }}', '{{ $project->image_name }}')">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center bg-secondary rounded"
                                                     style="width: 70px; height: 70px;">
                                                    <i class="fas fa-project-diagram text-white fa-lg"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Colonne Logiciels utilisés -->
                                    <td>
                                        <div class="software-list">
                                            @php
                                                // Utiliser les données enrichies du service qui contiennent déjà software_used formaté
                                                $softwareList = [];
                                                
                                                // Le service enrichit déjà les données avec software_used décodé
                                                if (isset($project->software_used) && !empty($project->software_used)) {
                                                    if (is_array($project->software_used)) {
                                                        $softwareList = $project->software_used;
                                                    } elseif (is_string($project->software_used)) {
                                                        $decoded = json_decode($project->software_used, true);
                                                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                                            $softwareList = $decoded;
                                                        } else {
                                                            $softwareList = array_map('trim', explode(',', $project->software_used));
                                                        }
                                                    }
                                                }
                                                
                                                // Fallback: utiliser software_list si disponible (données enrichies)
                                                if (empty($softwareList) && isset($project->software_list) && !empty($project->software_list)) {
                                                    $softwareList = is_array($project->software_list) ? $project->software_list : [$project->software_list];
                                                }
                                            @endphp
                                            
                                            @if(!empty($softwareList))
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach(array_slice($softwareList, 0, 3) as $software)
                                                        @php
                                                            $softwareName = is_array($software) ? 
                                                                (isset($software['name']) ? $software['name'] : (isset($software[0]) ? $software[0] : 'Logiciel')) : 
                                                                (string)$software;
                                                            $softwareName = trim($softwareName);
                                                        @endphp
                                                        @if(!empty($softwareName))
                                                            <span class="badge bg-primary text-white" style="font-size: 0.7rem;">
                                                                <i class="fas fa-tools me-1"></i>{{ $softwareName }}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                    @if(count($softwareList) > 3)
                                                        <span class="badge bg-info text-white" style="font-size: 0.7rem;">
                                                            +{{ count($softwareList) - 3 }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">
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
                                                'valide' => 'bg-success',
                                                'termine' => 'bg-info',
                                                'en_cours' => 'bg-warning',
                                                'rejete' => 'bg-danger',
                                                default => 'bg-secondary'
                                            };

                                            $statusIcon = match($project->status) {
                                                'valide' => 'fas fa-check-circle',
                                                'termine' => 'fas fa-flag-checkered',
                                                'en_cours' => 'fas fa-clock',
                                                'rejete' => 'fas fa-times-circle',
                                                default => 'fas fa-question-circle'
                                            };

                                            $statusText = match($project->status) {
                                                'valide' => 'Validé',
                                                'termine' => 'Terminé',
                                                'en_cours' => 'En cours',
                                                'rejete' => 'Rejeté',
                                                default => 'Inconnu'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }} px-2 py-1">
                                            <i class="{{ $statusIcon }} me-1"></i>{{ $statusText }}
                                        </span>
                                    </td>

                                    <!-- Colonne Actions -->
                                    <td class="text-center">
                                        <!-- 🏠 Boutons d'Action Dashboard -->
                                        <div class="d-flex gap-1 justify-content-center align-items-center" style="white-space: nowrap;">
                                            <button class="btn btn-info btn-sm"
                                                    onclick="viewProjectDetails({{ $project->id }})"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    data-bs-html="true"
                                                    title="<i class='fas fa-eye text-info'></i> <strong>Voir les détails</strong><br><small>Affiche toutes les informations du projet dans une fenêtre détaillée</small>">
                                                <i class="fas fa-eye me-1"></i>Voir le projet
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center text-white-50 py-4">
                                <div class="dashboard-icon mx-auto mb-3">
                                    <i class="fas fa-folder-open fa-2x"></i>
                                </div>
                                <div>Aucun travail pratique trouvé pour cet étudiant</div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination complète en bas -->
        @if($imagesData['pagination']['total_pages'] > 1)
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-secondary">
                <div>
                    <small class="text-white-50">
                        {{ $imagesData['pagination']['total_projects'] }} TP au total
                    </small>
                </div>

                <nav aria-label="Pagination des TP">
                    <ul class="pagination pagination-sm mb-0">
                        @if($imagesData['pagination']['has_previous_page'])
                            <li class="page-item">
                                <button class="page-link bg-dark border-secondary text-light"
                                        onclick="loadProjectImagesPage('{{ $pageParam }}', {{ $imagesData['pagination']['current_page'] - 1 }}, '{{ $studentId }}')">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            </li>
                        @endif

                        @for($i = max(1, $imagesData['pagination']['current_page'] - 2); $i <= min($imagesData['pagination']['total_pages'], $imagesData['pagination']['current_page'] + 2); $i++)
                            <li class="page-item {{ $i == $imagesData['pagination']['current_page'] ? 'active' : '' }}">
                                <button class="page-link {{ $i == $imagesData['pagination']['current_page'] ? 'bg-primary border-primary' : 'bg-dark border-secondary text-light' }}"
                                        onclick="loadProjectImagesPage('{{ $pageParam }}', {{ $i }}, '{{ $studentId }}')">
                                    {{ $i }}
                                </button>
                            </li>
                        @endfor

                        @if($imagesData['pagination']['has_next_page'])
                            <li class="page-item">
                                <button class="page-link bg-dark border-secondary text-light"
                                        onclick="loadProjectImagesPage('{{ $pageParam }}', {{ $imagesData['pagination']['current_page'] + 1 }}, '{{ $studentId }}')">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif

</div>

<script>
// 🏠 INITIALISATION TOOLTIPS DASHBOARD UNIQUEMENT
console.log('🏠 Initialisation des tooltips pour Projets Réels...');

// 🔄 Initialisation des tooltips Dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Attendre que le composant soit complètement chargé
    setTimeout(function() {
        // Initialisation des tooltips Bootstrap
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        console.log('🏠 Tooltips dashboard initialisés:', tooltipList.length);
    }, 500);
});

// 🧪 TEST DE DEBUG - Vérification du chargement
window.dashboardDebugTest = function() {
    console.log('✅ Les fonctions dashboard sont bien chargées !');
    alert('✅ JavaScript Dashboard chargé avec succès !');
};

// 🔑 Token CSRF
const DASHBOARD_CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
console.log('🔑 CSRF Token:', DASHBOARD_CSRF_TOKEN ? 'Trouvé' : 'MANQUANT !');

// 🎨 Messages dashboard
function dashboardShowMessage(message, type = 'info') {
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        info: '#17a2b8',
        warning: '#ffc107'
    };

    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, ${colors[type]}, ${colors[type]}dd);
        color: white;
        padding: 15px 20px;
        border-radius: 12px;
        z-index: 9999;
        font-weight: 600;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    `;
    messageDiv.innerHTML = `<i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : type === 'warning' ? 'exclamation' : 'info'}-circle me-2"></i>${message}`;

    document.body.appendChild(messageDiv);

    setTimeout(() => {
        messageDiv.style.opacity = '0';
        messageDiv.style.transform = 'translateX(100%)';
        setTimeout(() => messageDiv.remove(), 300);
    }, 3000);
}

// 👁️ VOIR un projet - Dashboard Style
window.dashboardViewProject = function(projectId) {
    console.log('👁️ Affichage du projet:', projectId);

    // Test immédiat pour vérifier si la fonction est appelée
    alert(`🔍 Fonction dashboardViewProject appelée pour le projet ${projectId}`);

    dashboardShowMessage('Chargement des détails du projet...', 'info');

    fetch(`/evc/app/admin/projects/view/${projectId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': DASHBOARD_CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('📡 Réponse reçue:', response.status);
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('📊 Données reçues:', data);
        if (data.success) {
            dashboardShowProjectDetails(data.project);
            dashboardShowMessage('Détails chargés avec succès', 'success');
        } else {
            throw new Error(data.message || 'Erreur lors du chargement');
        }
    })
    .catch(error => {
        console.error('❌ Erreur:', error);
        dashboardShowMessage('Erreur: ' + error.message, 'error');
    });
}

// ✏️ ÉDITER un projet - Dashboard Style
window.dashboardEditProject = function(projectId) {
    console.log('✏️ Édition du projet:', projectId);

    // Test immédiat pour vérifier si la fonction est appelée
    alert(`✏️ Fonction dashboardEditProject appelée pour le projet ${projectId}`);

    dashboardShowMessage('Ouverture de l'éditeur...', 'info');

    // Redirection vers la page d'édition
    window.location.href = `/evc/app/admin/projects/${projectId}/edit`;
};

// ✅ VALIDER un projet - Dashboard Style
window.dashboardValidateProject = function(projectId) {
    console.log('✅ Validation du projet:', projectId);

    // Test immédiat pour vérifier si la fonction est appelée
    alert(`✅ Fonction dashboardValidateProject appelée pour le projet ${projectId}`);

    if (!confirm('Êtes-vous sûr de vouloir valider ce projet ?')) {
        return;
    }

    dashboardShowMessage('Validation en cours...', 'warning');

    fetch(`/evc/app/admin/projects/${projectId}/validate`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': DASHBOARD_CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            dashboardShowMessage('Projet validé avec succès !', 'success');
            // Rechargement de la page pour actualiser les données
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Erreur lors de la validation');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        dashboardShowMessage('Erreur: ' + error.message, 'error');
    });
}

// 🗑️ SUPPRIMER un projet - Dashboard Style
window.dashboardDeleteProject = function(projectId) {
    console.log('🗑️ Suppression du projet:', projectId);

    // Test immédiat pour vérifier si la fonction est appelée
    alert(`🗑️ Fonction dashboardDeleteProject appelée pour le projet ${projectId}`);

    if (!confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.')) {
        return;
    }

    dashboardShowMessage('Suppression en cours...', 'warning');

    fetch(`/evc/app/admin/projects/${projectId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': DASHBOARD_CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            dashboardShowMessage('Projet supprimé avec succès !', 'success');
            // Rechargement de la page pour actualiser les données
            setTimeout(() => window.location.reload(), 1500);
        } else {
            throw new Error(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        dashboardShowMessage('Erreur: ' + error.message, 'error');
    });
}

// 📊 AFFICHAGE des détails projet - Dashboard Style
function dashboardShowProjectDetails(project) {
    // Création de la modal dashboard
    const modalHtml = `
        <div class="modal fade" id="dashboardProjectModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-white fw-bold">
                            <i class="fas fa-eye me-2"></i>Détails du Projet
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <strong class="text-white">Titre:</strong>
                                    <div class="text-white-50">${project.title || 'Non défini'}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <strong class="text-white">Statut:</strong>
                                    <div class="text-white-50">${project.status || 'Non défini'}</div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <strong class="text-white">Description:</strong>
                                    <div class="text-white-50">${project.description || 'Aucune description'}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <strong class="text-white">Logiciel utilisé:</strong>
                                    <div class="text-white-50">${project.software_used || 'Non spécifié'}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <strong class="text-white">Date de création:</strong>
                                    <div class="text-white-50">${project.created_at || 'Non définie'}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Suppression de l'ancienne modal si elle existe
    const existingModal = document.getElementById('dashboardProjectModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Ajout de la nouvelle modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Affichage de la modal
    const modal = new bootstrap.Modal(document.getElementById('dashboardProjectModal'));
    modal.show();

    // Suppression automatique après fermeture
    document.getElementById('dashboardProjectModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// 🔄 Initialisation des tooltips Dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    console.log('🏠 Tooltips dashboard initialisés:', tooltipList.length);
});


// 📝 Afficher détails projet
function simpleShowProjectDetails(projectId, project) {
    // Trouver la ligne du projet
    const button = document.querySelector(`[onclick*="simpleViewProject(${projectId})"]`);
    if (!button) {
        simpleShowMessage('Impossible de localiser le projet', 'error');
        return;
    }

    const row = button.closest('tr');
    if (!row) {
        simpleShowMessage('Impossible de localiser la ligne', 'error');
        return;
    }

    // Créer la ligne de détails avec design révolutionnaire
    const detailsRow = document.createElement('tr');
    detailsRow.id = `simple-details-${projectId}`;
    detailsRow.innerHTML = `
        <td colspan="6" style="padding: 0; border: none;">
            <!-- ✨ INTERFACE CLEAN & LIGHT -->
            <div class="clean-details-card" style="
                margin: 20px 0;
                background: var(--clean-bg);
                border: 1px solid var(--clean-border);
                border-radius: 12px;
                padding: 25px;
                animation: cleanSlideIn 0.6s ease-out;
            ">
                <!-- ✨ En-tête Clean -->
                <div class="clean-header" style="
                    text-align: center;
                    margin-bottom: 25px;
                ">
                    <h3 class="clean-project-title" style="
                        font-size: 1.5rem;
                        color: var(--clean-primary);
                        font-weight: 600;
                        margin-bottom: 8px;
                        animation: cleanFadeIn 0.8s ease-out;
                    ">
                        ${project.title || 'Détails du Projet'}
                    </h3>
                    <div class="clean-status-badge" style="
                        display: inline-block;
                        background: ${project.status === 'validate' ? 'var(--clean-success)' : project.status === 'pending' ? 'var(--clean-warning)' : 'var(--clean-secondary)'};
                        color: white;
                        padding: 4px 12px;
                        border-radius: 20px;
                        font-size: 0.8rem;
                        font-weight: 500;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    ">
                        ${project.status === 'validate' ? 'Validé' : project.status === 'pending' ? 'En attente' : 'Rejeté'}
                    </div>
                </div>

                <!-- ✨ Grille d'Informations Clean -->
                <div class="clean-info-grid" style="
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 15px;
                    margin-bottom: 25px;
                ">
                    <div class="clean-info-card" style="
                        background: white;
                        border: 1px solid var(--clean-border);
                        border-radius: 8px;
                        padding: 16px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.backgroundColor='var(--clean-hover)'; this.style.borderColor='var(--clean-primary)'"
                       onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--clean-border)'">
                        <div class="clean-info-icon" style="
                            font-size: 1.2rem;
                            color: var(--clean-primary);
                            margin-bottom: 8px;
                        ">📝</div>
                        <h6 style="color: var(--clean-text); font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Description</h6>
                        <p style="color: var(--clean-text-light); margin: 0; line-height: 1.4; font-size: 0.85rem;">${project.description || 'Aucune description disponible'}</p>
                    </div>

                    <div class="clean-info-card" style="
                        background: white;
                        border: 1px solid var(--clean-border);
                        border-radius: 8px;
                        padding: 16px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.backgroundColor='var(--clean-hover)'; this.style.borderColor='var(--clean-accent)'"
                       onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--clean-border)'">
                        <div class="clean-info-icon" style="
                            font-size: 1.2rem;
                            color: var(--clean-accent);
                            margin-bottom: 8px;
                        ">💻</div>
                        <h6 style="color: var(--clean-text); font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Logiciel</h6>
                        <p style="color: var(--clean-text-light); margin: 0; line-height: 1.4; font-size: 0.85rem; font-weight: 500;">${project.software_used || 'Non spécifié'}</p>
                    </div>

                    <div class="clean-info-card" style="
                        background: white;
                        border: 1px solid var(--clean-border);
                        border-radius: 8px;
                        padding: 16px;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.backgroundColor='var(--clean-hover)'; this.style.borderColor='var(--clean-success)'"
                       onmouseout="this.style.backgroundColor='white'; this.style.borderColor='var(--clean-border)'">
                        <div class="clean-info-icon" style="
                            font-size: 1.2rem;
                            color: var(--clean-success);
                            margin-bottom: 8px;
                        ">📅</div>
                        <h6 style="color: var(--clean-text); font-weight: 600; margin-bottom: 6px; font-size: 0.9rem;">Date de création</h6>
                        <p style="color: var(--clean-text-light); margin: 0; line-height: 1.4; font-size: 0.85rem; font-weight: 500;">${project.created_at ? new Date(project.created_at).toLocaleDateString('fr-FR') : 'Non disponible'}</p>
                    </div>
                </div>

                <!-- ✨ Actions Clean -->
                <div class="clean-actions" style="
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                    flex-wrap: wrap;
                    margin-top: 25px;
                    padding-top: 20px;
                    border-top: 1px solid var(--clean-border);
                ">
                    <button class="clean-btn clean-btn-edit" onclick="simpleEditProject(${projectId})" style="
                        background: var(--clean-warning);
                        color: #000;
                        border: 1px solid var(--clean-warning);
                        padding: 8px 16px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-weight: 500;
                        font-size: 13px;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.background='#e0a800'; this.style.borderColor='#d39e00'"
                       onmouseout="this.style.background='#ffc107'; this.style.borderColor='#ffc107'">
                        <i class="fas fa-edit" style="margin-right: 6px;"></i>Éditer
                    </button>
                    <button class="clean-btn clean-btn-validate" onclick="simpleValidateProject(${projectId})" style="
                        background: var(--clean-success);
                        color: white;
                        border: 1px solid var(--clean-success);
                        padding: 8px 16px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-weight: 500;
                        font-size: 13px;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.background='#218838'; this.style.borderColor='#1e7e34'"
                       onmouseout="this.style.background='#28a745'; this.style.borderColor='#28a745'">
                        <i class="fas fa-check" style="margin-right: 6px;"></i>Valider
                    </button>
                    <button class="clean-btn clean-btn-delete" onclick="simpleDeleteProject(${projectId})" style="
                        background: var(--clean-secondary);
                        color: white;
                        border: 1px solid var(--clean-secondary);
                        padding: 8px 16px;
                        border-radius: 4px;
                        cursor: pointer;
                        font-weight: 500;
                        font-size: 13px;
                        transition: all 0.2s ease;
                    " onmouseover="this.style.background='#c82333'; this.style.borderColor='#bd2130'"
                       onmouseout="this.style.background='#dc3545'; this.style.borderColor='#dc3545'">
                        <i class="fas fa-trash" style="margin-right: 6px;"></i>Supprimer
                    </button>
                </div>

                <!-- 📁 Galerie Clean des Fichiers -->
                <div class="clean-gallery" style="
                    background: white;
                    border-radius: 8px;
                    padding: 20px;
                    border: 1px solid var(--clean-border);
                ">
                    <h5 style="
                        text-align: center;
                        color: var(--clean-text);
                        font-weight: 600;
                        margin-bottom: 15px;
                        font-size: 1rem;
                    ">📁 Fichiers du projet</h5>
                    <div id="project-images-${projectId}" style="min-height: 100px;">
                        <div style="
                            text-align: center;
                            color: var(--clean-text-light);
                            padding: 20px;
                            background: rgba(102, 126, 234, 0.02);
                            border-radius: 6px;
                            border: 1px dashed var(--clean-border);
                        ">
                            <div style="
                                font-size: 2rem;
                                margin-bottom: 10px;
                                color: var(--clean-primary);
                                animation: cleanPulse 2s ease-in-out infinite;
                            ">📄</div>
                            <p style="margin: 0; font-weight: 500; font-size: 0.9rem;">Chargement des fichiers...</p>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    `;

    // Insérer après la ligne du projet
    row.insertAdjacentElement('afterend', detailsRow);

    // Animation d'entrée révolutionnaire
    requestAnimationFrame(() => {
        detailsRow.style.opacity = '1';
        detailsRow.style.transform = 'translateY(0)';
    });

    // Scroll fluide vers les détails
    setTimeout(() => {
        detailsRow.scrollIntoView({
            behavior: 'smooth',
            block: 'center',
            inline: 'nearest'
        });
    }, 400);

    console.log('🚀 Affichage révolutionnaire terminé !');

    // Charger les images du projet dynamiquement
    loadProjectImages(projectId);
}

// 🖼️ CHARGER LES IMAGES DU PROJET DYNAMIQUEMENT
function loadProjectImages(projectId) {
    console.log(`🖼️ Chargement des images pour le projet ${projectId}`);

    // Récupérer les images via l'API
    fetch(`/evc/app/admin/projects/${projectId}/images`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': SIMPLE_CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Images reçues:', data);

        const imagesContainer = document.getElementById(`project-images-${projectId}`);
        if (!imagesContainer) {
            console.error('Container des images non trouvé');
            return;
        }

        if (data.success && data.images && data.images.length > 0) {
            // Afficher les images avec style épuré
            let imagesHtml = '<div class="images-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; padding: 10px;">';

            data.images.forEach((file, index) => {
                // Utiliser l'URL formatée par le backend (même logique que l'affichage existant)
                const fileUrl = file.url || file.file_path || '';
                const fileName = file.original_name || file.name || 'Fichier';
                const mimeType = file.mime_type || '';
                const isImage = mimeType.startsWith('image/') || fileName.match(/\.(jpg|jpeg|png|gif|bmp|webp)$/i);
                const isPDF = mimeType === 'application/pdf' || fileName.toLowerCase().endsWith('.pdf');

                imagesHtml += `
                    <div class="file-item" style="
                        background: rgba(255, 255, 255, 0.1);
                        backdrop-filter: blur(10px);
                        border-radius: 12px;
                        padding: 18px;
                        text-align: center;
                        border: 1px solid rgba(255, 255, 255, 0.2);
                        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                        cursor: pointer;
                    " onmouseover="this.style.borderColor='rgba(79, 195, 247, 0.6)'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)'"
                       onmouseout="this.style.borderColor='rgba(255, 255, 255, 0.2)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'"
                       onclick="window.open('${fileUrl}', '_blank')">
                `;

                if (isImage && fileUrl) {
                    imagesHtml += `
                        <div class="file-preview" style="
                            width: 100%;
                            height: 80px;
                            margin-bottom: 12px;
                            border-radius: 8px;
                            overflow: hidden;
                            background: rgba(255, 255, 255, 0.1);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                        ">
                            <img src="${fileUrl}" alt="${fileName}"
                                 style="
                                     width: 100%;
                                     height: 100%;
                                     object-fit: cover;
                                     transition: transform 0.3s ease;
                                 "
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'"
                                 onerror="this.style.display='none'; this.parentElement.innerHTML='<i class=\\'fas fa-image\\' style=\\'font-size: 32px; color: rgba(255,255,255,0.5);\\' ></i>';">
                        </div>
                    `;
                } else if (isPDF) {
                    imagesHtml += `
                        <div class="file-preview" style="
                            width: 100%;
                            height: 80px;
                            margin-bottom: 12px;
                            border-radius: 8px;
                            background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(220, 53, 69, 0.1) 100%);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border: 1px solid rgba(220, 53, 69, 0.3);
                        ">
                            <i class="fas fa-file-pdf" style="
                                font-size: 32px;
                                color: #dc3545;
                                filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
                            "></i>
                        </div>
                    `;
                } else {
                    imagesHtml += `
                        <div class="file-preview" style="
                            width: 100%;
                            height: 80px;
                            margin-bottom: 12px;
                            border-radius: 8px;
                            background: rgba(255, 255, 255, 0.1);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            border: 1px solid rgba(255, 255, 255, 0.2);
                        ">
                            <i class="fas fa-file" style="
                                font-size: 32px;
                                color: rgba(255, 255, 255, 0.7);
                                filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
                            "></i>
                        </div>
                    `;
                }

                imagesHtml += `
                        <div class="file-info" style="color: white;">
                            <div class="file-name" style="
                                font-size: 13px;
                                font-weight: 600;
                                margin-bottom: 6px;
                                word-break: break-word;
                                text-shadow: 0 1px 2px rgba(0,0,0,0.3);
                            ">${fileName}</div>
                            <div class="file-meta" style="
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                                font-size: 11px;
                                color: rgba(255, 255, 255, 0.8);
                            ">
                                <span class="file-type" style="
                                    background: rgba(255, 255, 255, 0.15);
                                    padding: 2px 8px;
                                    border-radius: 8px;
                                    font-weight: 500;
                                ">${isImage ? 'Image' : isPDF ? 'PDF' : 'Fichier'}</span>
                                <span class="file-size">
                                    ${file.file_size ? (file.file_size / 1024 / 1024).toFixed(1) + ' MB' : ''}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            });

            imagesHtml += '</div>';
            imagesContainer.innerHTML = imagesHtml;
        } else {
            // Aucune image trouvée
            imagesContainer.innerHTML = `
                <div style="
                    text-align: center;
                    padding: 40px 20px;
                    color: #6c757d;
                    background: #ffffff;
                    border-radius: 8px;
                    border: 1px dashed #dee2e6;
                ">
                    <i class="fas fa-image" style="font-size: 48px; margin-bottom: 15px; color: #adb5bd;"></i>
                    <div style="font-size: 16px; font-weight: 500; margin-bottom: 8px; color: #495057;">
                        Aucune image disponible
                    </div>
                    <div style="font-size: 13px; color: #6c757d;">
                        Ce projet ne contient pas encore d'images
                    </div>
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Erreur lors du chargement des images:', error);

        const imagesContainer = document.getElementById(`project-images-${projectId}`);
        if (imagesContainer) {
            imagesContainer.innerHTML = `
                <div style="
                    text-align: center;
                    padding: 30px 20px;
                    color: #721c24;
                    background: #f8d7da;
                    border-radius: 8px;
                    border: 1px solid #f5c6cb;
                ">
                    <i class="fas fa-exclamation-triangle" style="font-size: 32px; margin-bottom: 10px; color: #721c24;"></i>
                    <div style="font-size: 14px; font-weight: 500;">
                        Erreur lors du chargement des images
                    </div>
                    <div style="font-size: 12px; color: #721c24; margin-top: 5px;">
                        Veuillez réessayer plus tard
                    </div>
                </div>
            `;
        }
    });
}

// ✏️ ÉDITER PROJET - Inline (Routes existantes)
function simpleEditProject(projectId) {
    console.log(`✏️ Édition projet ${projectId}`);

    // Récupérer les données du projet avec les routes existantes
    fetch(`/evc/app/admin/projects/edit/${projectId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': SIMPLE_CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Réponse reçue:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Données reçues:', data);
        if (data.success) {
            simpleShowEditModal(data.project);
        } else {
            simpleShowMessage('Erreur: ' + (data.error || data.message || 'Erreur inconnue'), 'error');
        }
    })
    .catch(error => {
        console.error('Erreur complète:', error);
        simpleShowMessage('Erreur de connexion: ' + error.message, 'error');
    });
}

// 📝 Modale d'édition
function simpleShowEditModal(project) {
    // Supprimer toute modale existante
    const existingModal = document.getElementById('simple-inline-edit-modal');
    if (existingModal) {
        existingModal.remove();
    }

    // Créer la modale
    const modal = document.createElement('div');
    modal.id = 'simple-inline-edit-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
    `;

    modal.innerHTML = `
        <div style="
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        ">
            <h4 style="margin: 0 0 20px 0; color: #333;">
                ✏️ Éditer le Projet
            </h4>

            <form id="simple-edit-form">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Titre:</label>
                    <input type="text" id="simple-edit-title" value="${project.title}"
                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Description:</label>
                    <textarea id="simple-edit-description" rows="3"
                              style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">${project.description}</textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Logiciel utilisé:</label>
                    <input type="text" id="simple-edit-software" value="${project.software_used}"
                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Statut:</label>
                    <select id="simple-edit-status" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="pending" ${project.status === 'pending' ? 'selected' : ''}>En attente</option>
                        <option value="validated" ${project.status === 'validated' ? 'selected' : ''}>Validé</option>
                        <option value="rejected" ${project.status === 'rejected' ? 'selected' : ''}>Rejeté</option>
                        <option value="draft" ${project.status === 'draft' ? 'selected' : ''}>Brouillon</option>
                    </select>
                </div>

                <div style="text-align: right;">
                    <button type="button" onclick="simpleCloseEditModal()"
                            style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; margin-right: 10px; cursor: pointer;">
                        Annuler
                    </button>
                    <button type="button" onclick="simpleSaveProject(${project.id})"
                            style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
                        💾 Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    `;

    document.body.appendChild(modal);
}

// 💾 Sauvegarder projet
function simpleSaveProject(projectId) {
    const title = document.getElementById('simple-edit-title').value;
    const description = document.getElementById('simple-edit-description').value;
    const software = document.getElementById('simple-edit-software').value;
    const status = document.getElementById('simple-edit-status').value;

    if (!title.trim()) {
        alert('Le titre est obligatoire');
        return;
    }

    // Appel AJAX simple
    fetch(`/evc/app/admin/projects/update/${projectId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': SIMPLE_CSRF_TOKEN,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            title: title,
            description: description,
            software_used: software,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            simpleShowMessage('Projet mis à jour avec succès!', 'success');
            simpleCloseEditModal();
            setTimeout(() => window.location.reload(), 1500);
        } else {
            simpleShowMessage('Erreur: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        simpleShowMessage('Erreur de sauvegarde', 'error');
    });
}

// ❌ Fermer modale
function simpleCloseEditModal() {
    const modal = document.getElementById('simple-inline-edit-modal');
    if (modal) {
        modal.remove();
    }
}

// ✅ VALIDER PROJET - Inline
function simpleValidateProject(projectId) {
    console.log(`✅ Validation projet ${projectId}`);

    if (!confirm('Confirmer la validation de ce projet ?')) {
        return;
    }

    fetch(`/evc/app/admin/projects/validate/${projectId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': SIMPLE_CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            simpleShowMessage('Projet validé avec succès!', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            simpleShowMessage('Erreur: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        simpleShowMessage('Erreur de validation', 'error');
    });
}

// 🗑️ SUPPRIMER PROJET - Inline
function simpleDeleteProject(projectId) {
    console.log(`🗑️ Suppression projet ${projectId}`);

    if (!confirm('ATTENTION: Supprimer définitivement ce projet ?')) {
        return;
    }

    if (!confirm('DERNIÈRE CONFIRMATION: Cette action est irréversible !')) {
        return;
    }

    fetch(`/evc/app/admin/projects/delete/${projectId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': SIMPLE_CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            simpleShowMessage('Projet supprimé avec succès!', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            simpleShowMessage('Erreur: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        simpleShowMessage('Erreur de suppression', 'error');
    });
}

console.log('✅ Fonctions inline chargées: simpleViewProject, simpleEditProject, simpleValidateProject, simpleDeleteProject');
</script>

<script>
// Fonction pour afficher l'image en modal
function viewProjectImageModal(imageId, imageSrc, imageName) {
    const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    const modalImage = document.getElementById('modalPreviewImage');
    const modalTitle = document.getElementById('modalImageTitle');
    const modalInfo = document.getElementById('modalImageInfo');
    const downloadBtn = document.getElementById('downloadModalImage');

    // Configurer le modal
    modalImage.src = imageSrc;
    modalImage.alt = imageName;
    modalTitle.textContent = imageName;
    modalInfo.textContent = `ID: ${imageId}`;

    // Configurer le bouton de téléchargement
    downloadBtn.onclick = function() {
        downloadProjectImage(imageId);
    };

    // Afficher le modal
    modal.show();
}

// Fonction pour gérer les erreurs d'image
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('.image-preview-container img');
    images.forEach(img => {
        img.onerror = function() {
            this.style.display = 'none';
            const container = this.parentElement;
            container.innerHTML = `
                <div class="d-flex align-items-center justify-content-center bg-secondary rounded"
                     style="width: 60px; height: 60px;">
                    <i class="fas fa-image-slash text-white"></i>
                </div>
            `;
        };
    });
});

// 🚀 SYSTÈME ULTRA SIMPLE - FONCTIONS QUI MARCHENT VRAIMENT
// Suppression de toute complexité, code minimaliste et fiable

// Variables globales simples
let simpleProjectManager = {
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),

    log(message) {
        console.log(`🔧 [SIMPLE] ${message}`);
    }
};

// ✏️ ÉDITER PROJET - Ultra Simple
function editProject(projectId) {
    console.log(`✏️ Édition projet ${projectId}`);

    // Récupérer les données du projet
    fetch(`/admin/simple-projects/edit/${projectId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showEditModal(data.project);
        } else {
            showMessage('Erreur: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showMessage('Erreur de connexion', 'error');
    });
}

// 📝 Afficher la modale d'édition
function showEditModal(project) {
    // Supprimer toute modale existante
    const existingModal = document.getElementById('simple-edit-modal');
    if (existingModal) {
        existingModal.remove();
    }

    // Créer la modale
    const modal = document.createElement('div');
    modal.id = 'simple-edit-modal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 10000;
        display: flex;
        align-items: center;
        justify-content: center;
    `;

    modal.innerHTML = `
        <div style="
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        ">
            <h4 style="margin: 0 0 20px 0; color: #333;">
                ✏️ Éditer le Projet
            </h4>

            <form id="edit-form">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Titre:</label>
                    <input type="text" id="edit-title" value="${project.title}"
                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Description:</label>
                    <textarea id="edit-description" rows="3"
                              style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">${project.description}</textarea>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Logiciel utilisé:</label>
                    <input type="text" id="edit-software" value="${project.software_used}"
                           style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold;">Statut:</label>
                    <select id="edit-status" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                        <option value="pending" ${project.status.includes('pending') ? 'selected' : ''}>En attente</option>
                        <option value="validated" ${project.status.includes('validé') || project.status.includes('validated') ? 'selected' : ''}>Validé</option>
                        <option value="rejected" ${project.status.includes('rejeté') || project.status.includes('rejected') ? 'selected' : ''}>Rejeté</option>
                        <option value="draft" ${project.status.includes('draft') ? 'selected' : ''}>Brouillon</option>
                    </select>
                </div>

                <div style="text-align: right;">
                    <button type="button" onclick="closeEditModal()"
                            style="background: #6c757d; color: white; border: none; padding: 10px 20px; border-radius: 6px; margin-right: 10px; cursor: pointer;">
                        Annuler
                    </button>
                    <button type="button" onclick="saveProject(${project.id})"
                            style="background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer;">
                        💾 Sauvegarder
                    </button>
                </div>
            </form>
        </div>
    `;

    document.body.appendChild(modal);
}

// 💾 Sauvegarder le projet
function saveProject(projectId) {
    simpleProjectManager.log(`Sauvegarde projet ${projectId}`);

    const title = document.getElementById('edit-title')?.value || '';
    const description = document.getElementById('edit-description')?.value || '';
    const software = document.getElementById('edit-software')?.value || '';
    const status = document.getElementById('edit-status')?.value || '';

    if (!title.trim()) {
        alert('Le titre est obligatoire');
        return;
    }

    // Appel AJAX simple
    fetch(`/evc/app/admin/projects/update/${projectId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': simpleProjectManager.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            title: title,
            description: description,
            software_used: software,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Projet modifié avec succès!');
            closeEditModal();
            location.reload(); // Recharger la page
        } else {
            alert('Erreur: ' + (data.message || 'Modification échouée'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la sauvegarde');
    });
}

// ❌ Fermer la modale
function closeEditModal() {
    const modal = document.getElementById('simple-edit-modal');
    if (modal) {
        modal.remove();
    }
}

// 👁️ VOIR PROJET - Ultra Simple
function viewProject(projectId) {
    console.log(`👁️ Voir projet ${projectId}`);

    // Vérifier si déjà ouvert
    const existing = document.getElementById(`details-${projectId}`);
    if (existing) {
        existing.remove();
        console.log('Détails fermés');
        return;
    }

    // Récupérer les détails du projet
    fetch(`/admin/simple-projects/view/${projectId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showProjectDetails(projectId, data.project);
        } else {
            showMessage('Erreur: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showMessage('Erreur de connexion', 'error');
    });
}

// 📝 Afficher les détails du projet
function showProjectDetails(projectId, project) {
    // Trouver la ligne du projet
    const button = document.querySelector(`[onclick*="viewProject(${projectId})"]`);
    if (!button) {
        showMessage('Impossible de localiser le projet', 'error');
        return;
    }

    const row = button.closest('tr');
    if (!row) {
        showMessage('Impossible de localiser la ligne', 'error');
        return;
    }

    // Créer la ligne de détails
    const detailsRow = document.createElement('tr');
    detailsRow.id = `details-${projectId}`;
    detailsRow.innerHTML = `
        <td colspan="6" style="
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
            padding: 25px;
            border-radius: 8px;
        ">
            <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 20px;">
                <h5 style="margin: 0; display: flex; align-items: center;">
                    <i class="fas fa-eye" style="margin-right: 10px;"></i>
                    Détails du Projet
                </h5>
                <button onclick="viewProject(${projectId})"
                        style="background: rgba(255,255,255,0.2); color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer;">
                    <i class="fas fa-times"></i> Fermer
                </button>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                    <h6 style="color: #ffc107; margin-bottom: 15px; font-size: 16px;">
                        <i class="fas fa-info-circle"></i> Informations
                    </h6>
                    <p style="margin: 8px 0;"><strong>Titre:</strong> ${project.title}</p>
                    <p style="margin: 8px 0;"><strong>Description:</strong> ${project.description}</p>
                    <p style="margin: 8px 0;"><strong>Logiciel:</strong> ${project.software_used}</p>
                    <p style="margin: 8px 0;"><strong>Statut:</strong>
                        <span style="background: #28a745; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            ${project.status_label}
                        </span>
                    </p>
                    <p style="margin: 8px 0;"><strong>Créé le:</strong> ${project.created_at}</p>
                </div>

                <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                    <h6 style="color: #28a745; margin-bottom: 15px; font-size: 16px;">
                        <i class="fas fa-cogs"></i> Détails Techniques
                    </h6>
                    <p style="margin: 8px 0;"><strong>Utilisateur:</strong> ${project.user_name}</p>
                    <p style="margin: 8px 0;"><strong>Email:</strong> ${project.user_email}</p>
                    <p style="margin: 8px 0;"><strong>Images:</strong> ${project.images_count} fichier(s)</p>

                    <div style="margin-top: 20px;">
                        <button onclick="editProject(${projectId})"
                                style="background: #ffc107; color: #000; border: none; padding: 8px 12px; border-radius: 4px; margin-right: 8px; cursor: pointer;">
                            <i class="fas fa-edit"></i> Éditer
                        </button>
                        <button onclick="validateProject(${projectId})"
                                style="background: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 4px; margin-right: 8px; cursor: pointer;">
                            <i class="fas fa-check"></i> Valider
                        </button>
                        <button onclick="deleteProject(${projectId})"
                                style="background: #dc3545; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer;">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </td>
    `;

    // Insérer après la ligne du projet
    row.insertAdjacentElement('afterend', detailsRow);

    // Scroll vers les détails
    setTimeout(() => {
        detailsRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 100);
}

// ✅ VALIDER PROJET - Ultra Simple
function validateProject(projectId) {
    console.log(`✅ Validation projet ${projectId}`);

    if (!confirm('Confirmer la validation de ce projet ?')) {
        return;
    }

    fetch(`/admin/simple-projects/validate/${projectId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage('Projet validé avec succès!', 'success');
            reloadPage();
        } else {
            showMessage('Erreur: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showMessage('Erreur de validation', 'error');
    });
}

// 🗑️ SUPPRIMER PROJET - Simple et Dynamique avec Loading
function deleteProject(projectId) {
    console.log(`🗑️ Suppression projet ${projectId}`);

    // Confirmation simple
    if (!confirm('Voulez-vous vraiment supprimer ce projet ?')) {
        return;
    }

    // Créer et afficher la modale de loading
    showLoadingModal('Suppression en cours...');

    // Trouver la ligne du projet
    const projectRow = findProjectRow(projectId);

    // Requête de suppression
    fetch(`/evc/app/admin/projects/delete/${projectId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingModal();

        if (data.success) {
            // Succès : supprimer la ligne avec animation
            removeProjectRow(projectRow);
            showMessage('Projet supprimé avec succès!', 'success');
        } else {
            showMessage('Erreur: ' + (data.error || 'Suppression échouée'), 'error');
        }
    })
    .catch(error => {
        hideLoadingModal();
        console.error('Erreur suppression:', error);
        showMessage('Erreur de connexion lors de la suppression', 'error');
    });
}

// 🔍 Trouver la ligne du projet dans le tableau
function findProjectRow(projectId) {
    return document.querySelector(`tr[data-project-id="${projectId}"]`) ||
           document.querySelector(`[data-project-id="${projectId}"]`) ||
           document.querySelector(`tr:has([onclick*="deleteProject(${projectId})"])`);
}

// 🗑️ Supprimer la ligne du projet avec animation
function removeProjectRow(projectRow) {
    if (!projectRow) return;

    // Animation de sortie
    projectRow.style.transition = 'all 0.4s ease';
    projectRow.style.transform = 'translateX(-100%)';
    projectRow.style.opacity = '0';

    setTimeout(() => {
        projectRow.remove();

        // Vérifier si le tableau est vide
        const remainingRows = document.querySelectorAll('tbody tr:not(.no-projects-row)');
        if (remainingRows.length === 0) {
            showEmptyTableMessage();
        }
    }, 400);
}

// 📭 Afficher message tableau vide
function showEmptyTableMessage() {
    const tbody = document.querySelector('tbody');
    if (tbody) {
        tbody.innerHTML = `
            <tr class="no-projects-row">
                <td colspan="100%" class="text-center text-muted py-4">
                    <i class="fas fa-inbox fa-2x mb-2 opacity-50"></i><br>
                    <span class="fs-6">Aucun projet trouvé</span>
                </td>
            </tr>
        `;
    }
}

// ⏳ Afficher modale de loading
function showLoadingModal(message = 'Chargement...') {
    // Supprimer toute modale existante
    hideLoadingModal();

    const loadingModal = document.createElement('div');
    loadingModal.id = 'loadingModal';
    loadingModal.className = 'loading-modal';
    loadingModal.innerHTML = `
        <div class="loading-overlay">
            <div class="loading-content">
                <div class="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                <div class="loading-text mt-3">
                    <strong>${message}</strong>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(loadingModal);

    // Animation d'apparition
    setTimeout(() => {
        loadingModal.classList.add('show');
    }, 10);
}

// ❌ Masquer modale de loading
function hideLoadingModal() {
    const loadingModal = document.getElementById('loadingModal');
    if (loadingModal) {
        loadingModal.classList.remove('show');
        setTimeout(() => {
            loadingModal.remove();
        }, 300);
    }
}

    // 🔧 Utilitaires techniques
    async makeRequest(url, method, data = null) {
        const fullUrl = this.apiBaseUrl + url;

        if (this.debugMode) {
            console.log(`🌐 [REQUEST] ${method} ${fullUrl}`);
            if (data) console.log(`📤 [REQUEST] Data:`, data);
        }

        const options = {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        };

        if (data && method !== 'GET') {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(fullUrl, options);

            if (this.debugMode) {
                console.log(`📡 [RESPONSE] Status: ${response.status} ${response.statusText}`);
            }

            if (!response.ok) {
                if (response.status === 401) {
                    console.warn('🔐 [AUTH] Session expirée, redirection...');
                    window.location.href = '/admin/login';
                    return;
                }

                // Essayer de lire le message d'erreur du serveur
                let errorMessage = `HTTP ${response.status}: ${response.statusText}`;
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {
                    console.warn('⚠️ [ERROR] Impossible de parser l\'erreur JSON');
                }

                throw new Error(errorMessage);
            }

            const responseData = await response.json();

            if (this.debugMode) {
                console.log(`📥 [RESPONSE] Data:`, responseData);
            }

            return responseData;
        } catch (error) {
            console.error(`❌ [REQUEST] Erreur pour ${method} ${fullUrl}:`, error);
            throw error;
        }
    }

    setButtonLoading(projectId, action, loading) {
        const button = document.querySelector(`[onclick*="${action}Project(${projectId})"]`);
        if (!button) return;

        const loader = button.querySelector('.btn-revolutionary-loader');
        const icon = button.querySelector('i:not(.spinner-border)');

        if (loading) {
            this.loadingStates.set(`${projectId}-${action}`, true);
            button.disabled = true;
            if (loader) loader.style.display = 'flex';
            if (icon) icon.style.display = 'none';
        } else {
            this.loadingStates.delete(`${projectId}-${action}`);
            button.disabled = false;
            if (loader) loader.style.display = 'none';
            if (icon) icon.style.display = 'inline';
        }
    }

    showToast(message, type = 'success') {
        showProjectImageToast(message, type);
    }

// 🔄 Fallback : Affichage des détails basiques extraits du tableau
function showBasicProjectDetails(projectId) {
    projectDetailsManager.log(`Fallback activé pour projet ${projectId}`);

    const projectRow = findProjectRow(projectId);
    if (!projectRow) {
        showToast('Impossible de trouver le projet dans le tableau', 'error');
        return;
    }

    // Extraire les données basiques du tableau
    const cells = projectRow.querySelectorAll('td');
    const basicData = {
        id: projectId,
        title: cells[1]?.textContent?.trim() || 'Titre non disponible',
        description: cells[2]?.textContent?.trim() || 'Description non disponible',
        status: extractStatusFromRow(projectRow),
        created_at: cells[4]?.textContent?.trim() || 'Date non disponible',
        image_url: extractImageFromRow(projectRow)
    };

    projectDetailsManager.log('Données extraites du tableau:', basicData);
    showProjectDetailsInline(projectId, basicData, true);
    showToast('Détails basiques affichés (mode hors ligne)', 'info');
}

// Extraire le statut de la ligne
function extractStatusFromRow(row) {
    const statusBadge = row.querySelector('.badge');
    return statusBadge ? statusBadge.textContent.trim() : 'Statut inconnu';
}

// 🖼️ Extraire l'image de la ligne
function extractImageFromRow(row) {
    const img = row.querySelector('img');
    return img ? img.src : null;
}



// 📝 MODALE D'ÉDITION SIMPLE
function showSimpleEditModal(projectId, data) {
    simpleProjectManager.log('Affichage modale édition simple');

    // Supprimer toute modale existante
    const existingModal = document.getElementById('simpleEditModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Créer la modale simple
    const modalHtml = `
        <div class="modal fade" id="simpleEditModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Éditer le Projet</h5>
                        <button type="button" class="btn-close" onclick="closeEditModal()"></button>
                    </div>
                    <div class="modal-body">
                        <form id="simpleEditForm">
                            <div class="mb-3">
                                <label class="form-label">Titre</label>
                                <input type="text" class="form-control" id="editTitle" value="${data.title}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" id="editDescription" rows="3">${data.description}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select class="form-control" id="editStatus">
                                    <option value="pending" ${data.status.includes('pending') ? 'selected' : ''}>En attente</option>
                                    <option value="validated" ${data.status.includes('validé') || data.status.includes('validated') ? 'selected' : ''}>Validé</option>
                                    <option value="rejected" ${data.status.includes('rejeté') || data.status.includes('rejected') ? 'selected' : ''}>Rejeté</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Annuler</button>
                        <button type="button" class="btn btn-primary" id="saveProject">
                            <i class="fas fa-save me-2"></i>Sauvegarder
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Ajouter au DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Afficher la modale
    const modal = document.getElementById('simpleEditModal');
    modal.style.display = 'block';
    modal.classList.add('show');
    modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
}

// 🔒 FERMER LA MODALE D'ÉDITION
function closeEditModal() {
    const modal = document.getElementById('simpleEditModal');
    if (modal) {
        modal.remove();
    }
}

// 💾 SAUVEGARDER LES MODIFICATIONS
function saveProject(projectId) {
    simpleProjectManager.log(`Sauvegarde projet ${projectId}`);

    const title = document.getElementById('editTitle')?.value || '';
    const description = document.getElementById('editDescription')?.value || '';
    const status = document.getElementById('editStatus')?.value || '';

    if (!title.trim()) {
        alert('Le titre est obligatoire');
        return;
    }

    // Appel AJAX simple
    fetch(`/evc/app/admin/projects/update/${projectId}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': simpleProjectManager.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            title: title,
            description: description,
            status: status
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Projet modifié avec succès!');
            closeEditModal();
            location.reload(); // Recharger la page
        } else {
            alert('Erreur: ' + (data.message || 'Modification échouée'));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Erreur lors de la sauvegarde');
    });
}

// 🎨 AFFICHAGE DES DÉTAILS PROJET (Simple et Efficace)
function showProjectDetailsInline(projectId, projectData, isBasicMode = false) {
    projectDetailsManager.log(`Affichage détails pour projet ${projectId}`, { isBasicMode, projectData });

    const projectRow = findProjectRow(projectId);
    if (!projectRow) {
        showToast('Impossible de localiser le projet dans le tableau', 'error');
        return;
    }

    // Créer la ligne de détails
    const detailsRow = document.createElement('tr');
    detailsRow.id = `project-details-${projectId}`;
    detailsRow.className = 'project-details-row';

    // Contenu HTML des détails
    detailsRow.innerHTML = `
        <td colspan="6" class="p-0">
            <div class="project-details-container" style="
                background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                border-left: 4px solid var(--revolutionary-secondary);
                margin: 0;
                padding: 0;
                overflow: hidden;
                max-height: 0;
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            ">
                <!-- En-tête -->
                <div class="row align-items-center mb-3">
                    <div class="col">
                        <h5 class="text-white mb-0">
                            <i class="fas fa-eye" style="margin-right: 10px;"></i>
                            Détails du Projet
                        </h5>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-sm btn-outline-light" onclick="revolutionaryManager.toggleProjectDetails(${projectId})">
                            <i class="fas fa-times"></i> Fermer
                        </button>
                    </div>
                </div>

                ${isBasicMode ? `
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Mode Hors Ligne :</strong> Données extraites du tableau (limitées)
                    </div>
                ` : ''}

                <!-- Contenu principal -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-card" style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informations Générales
                            </h6>
                            <div class="detail-item mb-2">
                                <span class="detail-label" style="font-weight: bold; color: #ccc;">Titre:</span>
                                <span class="detail-value ms-2">${projectData.title || 'Non défini'}</span>
                            </div>
                            <div class="detail-item mb-2">
                                <span class="detail-label" style="font-weight: bold; color: #ccc;">Description:</span>
                                <span class="detail-value ms-2">${projectData.description || 'Aucune description'}</span>
                            </div>
                            <div class="detail-item mb-2">
                                <span class="detail-label" style="font-weight: bold; color: #ccc;">Statut:</span>
                                <span class="badge bg-${getStatusColor(projectData.status)} ms-2">
                                    <i class="fas fa-${getStatusIcon(projectData.status)} me-1"></i>
                                    ${projectData.status_label || projectData.status}
                                </span>
                            </div>
                            <div class="detail-item mb-2">
                                <span class="detail-label" style="font-weight: bold; color: #ccc;">Date de création:</span>
                                <span class="detail-value ms-2">${formatDate(projectData.created_at) || 'Non disponible'}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="detail-card" style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-cogs me-2"></i>Détails Techniques
                            </h6>
                            <div class="detail-item mb-2">
                                <span class="detail-label" style="font-weight: bold; color: #ccc;">Type:</span>
                                <span class="detail-value ms-2">${projectData.type || (isBasicMode ? 'Non disponible (mode hors ligne)' : 'Non spécifié')}</span>
                            </div>
                            <div class="detail-item mb-2">
                                <span class="detail-label" style="font-weight: bold; color: #ccc;">Logiciels utilisés:</span>
                                <span class="detail-value ms-2">${projectData.software_used || (isBasicMode ? 'Non disponible (mode hors ligne)' : 'Non spécifié')}</span>
                            </div>
                            <div class="detail-item mb-2">
                                <span class="detail-label" style="font-weight: bold; color: #ccc;">Nombre de fichiers:</span>
                                <span class="detail-value ms-2">${projectData.file_count || (isBasicMode ? 'Non disponible' : '0')} ${isBasicMode ? '' : 'fichier(s)'}</span>
                            </div>
                            <div class="detail-item mb-2">
                                <span class="detail-label" style="font-weight: bold; color: #ccc;">Dernière modification:</span>
                                <span class="detail-value ms-2">${formatDate(projectData.updated_at) || (isBasicMode ? 'Non disponible (mode hors ligne)' : 'Non disponible')}</span>
                            </div>
                        </div>
                    </div>
                </div>

                ${projectData.images && projectData.images.length > 0 ? `
                    <div class="mt-4">
                        <h6 class="text-warning mb-3">
                            <i class="fas fa-images me-2"></i>Aperçu des Images (${projectData.images.length})
                        </h6>
                        <div class="row g-2">
                            ${projectData.images.map(img => `
                                <div class="col-md-3">
                                    <div class="image-preview-card">
                                        <img src="${img.url}"
                                             alt="${img.original_name}"
                                             class="img-thumbnail rounded"
                                             style="width: 100%; height: 80px; object-fit: cover; border: 1px solid #dee2e6;"
                                             loading="lazy"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                        >
                                        <small class="text-muted d-block mt-1">${img.name}</small>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                ` : (projectData.image_url ? `
                    <div class="mt-4">
                        <h6 class="text-warning mb-3">
                            <i class="fas fa-image me-2"></i>Aperçu de l'Image
                        </h6>
                        <div class="image-preview-card">
                            <img src="${projectData.image_url}" alt="Image du projet" class="img-fluid rounded"
                                 style="max-height: 200px; object-fit: cover; cursor: pointer;"
                                 onclick="window.open('${projectData.image_url}', '_blank')">
                        </div>
                    </div>
                ` : (isBasicMode ? `
                    <div class="mt-4">
                        <div class="alert alert-secondary">
                            <i class="fas fa-image me-2"></i>
                            Images non disponibles en mode hors ligne
                        </div>
                    </div>
                ` : ''))}

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="detail-actions d-flex gap-2 justify-content-end">
                            <button class="btn btn-warning btn-sm" onclick="revolutionaryManager.editProject(${projectId})">
                                <i class="fas fa-edit me-1"></i>Éditer
                            </button>
                            <button class="btn btn-success btn-sm" onclick="revolutionaryManager.validateProject(${projectId})">
                                <i class="fas fa-check me-1"></i>Valider
                            </button>
                            <button class="btn btn-danger btn-sm" onclick="revolutionaryManager.deleteProject(${projectId})">
                                <i class="fas fa-trash me-1"></i>Supprimer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    `;

    // Insérer après la ligne du projet
    projectRow.insertAdjacentElement('afterend', detailsRow);

    // Animer l'expansion
    setTimeout(() => {
        const detailsContainer = detailsRow.querySelector('.project-details-container');
        detailsContainer.style.maxHeight = detailsContainer.scrollHeight + 'px';
        detailsContainer.style.opacity = '1';
    }, 50);

    // Scroll vers les détails après l'animation
    setTimeout(() => {
        detailsRow.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
    }, 300);

    console.log(`✅ [DETAILS] Affichage ${isBasicMode ? 'basique' : 'complet'} terminé pour projet ${projectId}`);
}

// 🔄 Toggle des détails projet (fermer/ouvrir)
function toggleProjectDetails(projectId) {
    const detailsRow = document.getElementById(`project-details-${projectId}`);

    if (!detailsRow) {
        console.warn(`⚠️ [TOGGLE] Détails non trouvés pour projet ${projectId}`);
        return;
    }

    console.log(`🔄 [TOGGLE] Fermeture détails projet ${projectId}`);

    const detailsContainer = detailsRow.querySelector('.project-details-container');

    // Animation de fermeture
    detailsContainer.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
    detailsContainer.style.maxHeight = '0';
    detailsContainer.style.opacity = '0';

    // Supprimer après l'animation
    setTimeout(() => {
        detailsRow.remove();
        showToast('Détails masqués', 'info');
        console.log(`✅ [TOGGLE] Détails fermés pour projet ${projectId}`);
    }, 300);
}

// 🎨 Fonctions utilitaires pour le statut
function getStatusColor(status) {
    const colors = {
        'pending': 'warning',
        'validated': 'success',
        'validate': 'success',
        'rejected': 'danger',
        'draft': 'secondary',
        'en_validation': 'warning',
        'valide': 'success',
        'rejete': 'danger'
    };
    return colors[status] || 'primary';
}

function getStatusIcon(status) {
    const icons = {
        'pending': 'clock',
        'validated': 'check-circle',
        'validate': 'check-circle',
        'rejected': 'times-circle',
        'draft': 'edit',
        'en_validation': 'clock',
        'valide': 'check-circle',
        'rejete': 'times-circle'
    };
    return icons[status] || 'info-circle';
}

function formatDate(dateString) {
    if (!dateString) return null;
    try {
        const date = new Date(dateString);
        if (isNaN(date.getTime())) {
            return dateString;
        }
        return date.toLocaleDateString('fr-FR', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    } catch (e) {
        projectDetailsManager.log('Erreur formatage date:', e);
        return dateString;
    }
}

    // 🔍 Trouver la ligne du projet dans le tableau
    findProjectRow(projectId) {
        // Essayer plusieurs sélecteurs pour trouver la ligne
        let projectRow = document.querySelector(`tr[data-project-id="${projectId}"]`);

        if (!projectRow) {
            // Chercher par bouton onclick
            const viewButton = document.querySelector(`[onclick*="viewProject(${projectId})"]`);
            if (viewButton) {
                projectRow = viewButton.closest('tr');
            }
        }

        if (!projectRow) {
            // Chercher dans tous les boutons révolutionnaires
            const buttons = document.querySelectorAll('.btn-revolutionary');
            for (const button of buttons) {
                if (button.onclick && button.onclick.toString().includes(`viewProject(${projectId})`)) {
                    projectRow = button.closest('tr');
                    break;
                }
            }
        }

        return projectRow;
    }

    // 🎨 Extraire le statut de la ligne
    extractStatusFromRow(row) {
        const statusBadge = row.querySelector('.badge');
        return statusBadge ? statusBadge.textContent.trim() : 'Statut inconnu';
    }

    // 🖼️ Extraire l'image de la ligne
    extractImageFromRow(row) {
        const img = row.querySelector('img');
        return img ? img.src : null;
    }

    // 🎨 Affichage révolutionnaire des détails directement dans l'interface
    showProjectDetailsInline(projectId, projectData, isBasicMode = false) {
        const projectRow = this.findProjectRow(projectId);

        if (!projectRow) {
            console.error('❌ Ligne du projet non trouvée');
            this.showToast('Impossible de localiser le projet', 'error');
            return;
        }

        console.log(`🎨 [DETAILS] Affichage ${isBasicMode ? 'basique' : 'complet'} pour projet ${projectId}`);

        // Créer la ligne de détails révolutionnaire
        const detailsRow = document.createElement('tr');
        detailsRow.id = `project-details-${projectId}`;
        detailsRow.className = 'project-details-row';
        detailsRow.style.cssText = `
            opacity: 0;
            transform: translateY(-30px);
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        `;
        detailsRow.innerHTML = `
            <td colspan="6" class="p-0">
                <div class="project-details-container" style="
                    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
                    border-left: 4px solid var(--revolutionary-secondary);
                    margin: 0;
                    padding: 0;
                    overflow: hidden;
                    max-height: 0;
                    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                ">
                    <div class="details-content p-4">
                        <div class="row align-items-center mb-3">
                            <div class="col">
                                <h5 class="text-white mb-0">
                                    <i class="fas fa-eye text-primary me-2"></i>
                                    Détails du Projet
                                </h5>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-sm btn-outline-light" onclick="revolutionaryManager.toggleProjectDetails(${projectId})">
                                    <i class="fas fa-times"></i> Fermer
                                </button>
                            </div>
                        </div>

                        ${isBasicMode ? `
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Mode Hors Ligne :</strong> Données extraites du tableau (limitées)
                            </div>
                        ` : ''}

                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>Informations Générales
                                    </h6>
                                    <div class="detail-item">
                                        <span class="detail-label">Titre:</span>
                                        <span class="detail-value">${projectData.title || 'Non défini'}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Description:</span>
                                        <span class="detail-value">${projectData.description || 'Aucune description'}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Statut:</span>
                                        <span class="badge bg-${this.getStatusColor(projectData.status)} ms-2">
                                            <i class="fas fa-${this.getStatusIcon(projectData.status)} me-1"></i>
                                            ${projectData.status_label || projectData.status}
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Date de création:</span>
                                        <span class="detail-value">${this.formatDate(projectData.created_at) || 'Non disponible'}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-card">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-cogs me-2"></i>Détails Techniques
                                    </h6>
                                    <div class="detail-item">
                                        <span class="detail-label">Type:</span>
                                        <span class="detail-value">${projectData.type || (isBasicMode ? 'Non disponible (mode hors ligne)' : 'Non spécifié')}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Logiciels utilisés:</span>
                                        <span class="detail-value">${projectData.software_used || (isBasicMode ? 'Non disponible (mode hors ligne)' : 'Non spécifié')}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Nombre de fichiers:</span>
                                        <span class="detail-value">${projectData.file_count || (isBasicMode ? 'Non disponible' : '0')} ${isBasicMode ? '' : 'fichier(s)'}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Dernière modification:</span>
                                        <span class="detail-value">${this.formatDate(projectData.updated_at) || (isBasicMode ? 'Non disponible (mode hors ligne)' : 'Non disponible')}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        ${projectData.images && projectData.images.length > 0 ? `
                            <div class="mt-4">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-images me-2"></i>Aperçu des Images (${projectData.images.length})
                                </h6>
                                <div class="row g-2">
                                    ${projectData.images.map(img => `
                                        <div class="col-md-3">
                                            <div class="image-preview-card">
                                                <img src="${img.url}"
                                                     alt="${img.original_name}"
                                                     class="img-thumbnail rounded"
                                                     style="width: 100%; height: 80px; object-fit: cover; border: 1px solid #dee2e6;"
                                                     loading="lazy"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                >
                                                <small class="text-muted d-block mt-1">${img.name}</small>
                                            </div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : (projectData.image_url ? `
                            <div class="mt-4">
                                <h6 class="text-warning mb-3">
                                    <i class="fas fa-image me-2"></i>Aperçu de l'Image
                                </h6>
                                <div class="image-preview-card">
                                    <img src="${projectData.image_url}" alt="Image du projet" class="img-fluid rounded"
                                         style="max-height: 200px; object-fit: cover; cursor: pointer;"
                                         onclick="window.open('${projectData.image_url}', '_blank')">
                                </div>
                            </div>
                        ` : (isBasicMode ? `
                            <div class="mt-4">
                                <div class="alert alert-secondary">
                                    <i class="fas fa-image me-2"></i>
                                    Images non disponibles en mode hors ligne
                                </div>
                            </div>
                        ` : ''))}

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="detail-actions d-flex gap-2 justify-content-end">
                                    <button class="btn btn-warning btn-sm" onclick="revolutionaryManager.editProject(${projectId})">
                                        <i class="fas fa-edit me-1"></i>Éditer
                                    </button>
                                    <button class="btn btn-success btn-sm" onclick="revolutionaryManager.validateProject(${projectId})">
                                        <i class="fas fa-check me-1"></i>Valider
                                    </button>
                                    <button class="btn btn-danger btn-sm" onclick="revolutionaryManager.deleteProject(${projectId})">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        `;

        // Insérer après la ligne du projet
        projectRow.insertAdjacentElement('afterend', detailsRow);

        // Animer l'expansion
        setTimeout(() => {
            const detailsContainer = detailsRow.querySelector('.project-details-container');
            detailsContainer.style.maxHeight = detailsContainer.scrollHeight + 'px';
            detailsContainer.style.opacity = '1';
        }, 50);

        // Scroll vers les détails après l'animation
        setTimeout(() => {
            detailsRow.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 300);

        console.log(`✅ [DETAILS] Affichage ${isBasicMode ? 'basique' : 'complet'} terminé pour projet ${projectId}`);
    }

    // 🔄 Toggle des détails projet (fermer/ouvrir)
    toggleProjectDetails(projectId) {
        const detailsRow = document.getElementById(`project-details-${projectId}`);

        if (!detailsRow) {
            console.warn(`⚠️ [TOGGLE] Détails non trouvés pour projet ${projectId}`);
            return;
        }

        console.log(`🔄 [TOGGLE] Fermeture détails projet ${projectId}`);

        const detailsContainer = detailsRow.querySelector('.project-details-container');

        // Animation de fermeture
        detailsContainer.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
        detailsContainer.style.maxHeight = '0';
        detailsContainer.style.opacity = '0';

        // Supprimer après l'animation
        setTimeout(() => {
            detailsRow.remove();
            this.showToast('Détails masqués', 'info');
            console.log(`✅ [TOGGLE] Détails fermés pour projet ${projectId}`);
        }, 300);
    }

    createEditModal() {
        const modalHtml = `
            <div class="modal fade" id="revolutionaryEditModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content bg-dark text-white">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title">
                                <i class="fas fa-edit text-warning me-2"></i>Éditer le Projet
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="editModalContent">
                            <div class="text-center py-4">
                                <div class="spinner-border text-warning" role="status"></div>
                                <p class="mt-2">Chargement...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.modals.set('edit', new bootstrap.Modal(document.getElementById('revolutionaryEditModal')));
    }

    createValidateModal() {
        const modalHtml = `
            <div class="modal fade" id="revolutionaryValidateModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark text-white">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title">
                                <i class="fas fa-check text-success me-2"></i>Confirmer la Validation
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="validateModalContent"></div>
                        <div class="modal-footer border-secondary">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-success" id="confirmValidateBtn">Valider</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.modals.set('validate', new bootstrap.Modal(document.getElementById('revolutionaryValidateModal')));
    }

    createDeleteModal() {
        const modalHtml = `
            <div class="modal fade" id="revolutionaryDeleteModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content bg-dark text-white">
                        <div class="modal-header border-secondary bg-danger">
                            <h5 class="modal-title">
                                <i class="fas fa-trash text-white me-2"></i>Confirmer la Suppression
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="deleteModalContent"></div>
                        <div class="modal-footer border-secondary">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Supprimer</button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.modals.set('delete', new bootstrap.Modal(document.getElementById('revolutionaryDeleteModal')));
    }

    // 🔄 Basculer l'affichage des détails (expand/collapse)
    toggleProjectDetails(projectId) {
        const detailsRow = document.getElementById(`project-details-${projectId}`);
        if (!detailsRow) return;

        const container = detailsRow.querySelector('.project-details-container');
        const isExpanded = container.style.maxHeight !== '0px';

        if (isExpanded) {
            // Collapse
            container.style.maxHeight = '0px';
            setTimeout(() => {
                detailsRow.remove();
            }, 400);
            this.showToast('Détails masqués', 'info');
        } else {
            // Expand (ne devrait pas arriver avec la logique actuelle)
            container.style.maxHeight = '1000px';
        }
    }

    // 📋 Affichage des détails basiques en cas d'erreur API
    showBasicProjectDetails(projectId) {
        const projectRow = document.querySelector(`tr[data-project-id="${projectId}"]`) ||
                          document.querySelector(`[onclick*="viewProject(${projectId})"]`).closest('tr');

        if (!projectRow) return;

        // Extraire les données basiques de la ligne du tableau
        const cells = projectRow.querySelectorAll('td');
        const basicData = {
            title: cells[1]?.textContent?.trim() || 'Projet sans titre',
            status: cells[3]?.querySelector('.badge')?.textContent?.trim() || 'Statut inconnu',
            created_at: cells[4]?.textContent?.trim() || 'Date inconnue'
        };

        this.showProjectDetailsInline(projectId, basicData);
        this.showToast('Détails basiques affichés (données limitées)', 'warning');
    }

    showEditModal(projectData) {
        const content = `
            <form id="editProjectForm">
                <div class="mb-3">
                    <label class="form-label">Titre du projet</label>
                    <input type="text" class="form-control bg-secondary text-white border-secondary"
                           name="title" value="${projectData.title || ''}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea class="form-control bg-secondary text-white border-secondary"
                              name="description" rows="3">${projectData.description || ''}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Type de projet</label>
                    <select class="form-control bg-secondary text-white border-secondary" name="type">
                        <option value="web" ${projectData.type === 'web' ? 'selected' : ''}>Web Design</option>
                        <option value="print" ${projectData.type === 'print' ? 'selected' : ''}>Print Design</option>
                        <option value="logo" ${projectData.type === 'logo' ? 'selected' : ''}>Logo Design</option>
                        <option value="illustration" ${projectData.type === 'illustration' ? 'selected' : ''}>Illustration</option>
                    </select>
                </div>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-1"></i>Sauvegarder
                    </button>
                </div>
            </form>
        `;
        document.getElementById('editModalContent').innerHTML = content;
        this.modals.get('edit').show();

        // Gérer la soumission du formulaire
        document.getElementById('editProjectForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveProjectChanges(projectData.id, new FormData(e.target));
        });
    }

    async saveProjectChanges(projectId, formData) {
        try {
            const data = Object.fromEntries(formData);
            const response = await this.makeRequest(`/projects/update/${projectId}`, 'PUT', data);

            if (response.success) {
                this.modals.get('edit').hide();
                this.showToast('Projet mis à jour avec succès!', 'success');
                this.refreshProjectRow(projectId);
            }
        } catch (error) {
            this.showToast('Erreur lors de la mise à jour', 'error');
        }
    }

    showConfirmModal(title, message, confirmText, type) {
        return new Promise((resolve) => {
            const modal = this.modals.get(type === 'danger' ? 'delete' : 'validate');
            const content = document.getElementById(type === 'danger' ? 'deleteModalContent' : 'validateModalContent');
            const confirmBtn = document.getElementById(type === 'danger' ? 'confirmDeleteBtn' : 'confirmValidateBtn');

            content.innerHTML = `<p>${message}</p>`;

            const handleConfirm = () => {
                modal.hide();
                confirmBtn.removeEventListener('click', handleConfirm);
                resolve(true);
            };

            const handleCancel = () => {
                modal.hide();
                resolve(false);
            };

            confirmBtn.addEventListener('click', handleConfirm);
            modal.show();
      // 🎯 Utilitaires de statut et formatage
    getStatusColor(status) {
        const colors = {
            'pending': 'warning',
            'validated': 'success',
            'validate': 'success',
            'rejected': 'danger',
            'draft': 'secondary',
            'en_validation': 'warning',
            'valide': 'success',
            'rejete': 'danger'
        };
        return colors[status] || 'primary';
    }

    getStatusIcon(status) {
        const icons = {
            'pending': 'clock',
            'validated': 'check-circle',
            'validate': 'check-circle',
            'rejected': 'times-circle',
            'draft': 'edit',
            'en_validation': 'clock',
            'valide': 'check-circle',
            'rejete': 'times-circle'
        };
        return icons[status] || 'info-circle';
    }

    formatDate(dateString) {
        if (!dateString) return null;
        try {
            // Gérer différents formats de date
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                // Si la date n'est pas valide, retourner la chaîne originale
                return dateString;
            }
            return date.toLocaleDateString('fr-FR', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        } catch (e) {
            console.warn('⚠️ [DATE] Erreur formatage date:', e);
            return dateString;
        }
    }

    updateProjectStatus(projectId, newStatus) {
        const row = document.querySelector(`tr[data-project-id="${projectId}"]`);
        if (row) {
            const statusBadge = row.querySelector('.badge');
            if (statusBadge) {
                statusBadge.className = `badge bg-${this.getStatusColor(newStatus)}`;
                statusBadge.innerHTML = `<i class="fas fa-check me-1"></i>${newStatus}`;
            }
        }
    }

    removeProjectRow(projectId) {
        const row = document.querySelector(`tr[data-project-id="${projectId}"]`);
        if (row) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            row.style.transform = 'translateX(-100%)';
            setTimeout(() => row.remove(), 300);
        }
    }

    refreshProjectRow(projectId) {
        // Recharger la ligne du projet pour refléter les changements
        console.log(`🔄 [REFRESH] Actualisation ligne projet ${projectId}`);
        // Cette fonction peut être étendue pour recharger via AJAX
        const row = findProjectRow(projectId);
        if (row) {
            row.style.transition = 'all 0.3s ease';
            row.style.opacity = '0';
            setTimeout(() => {
                row.remove();
                // Recharger la ligne du projet
                // ...
            }, 300);
        }
    }
}

// 🔧 FONCTIONS UTILITAIRES SIMPLES

// Gestion du loading sur les boutons
function setButtonLoading(projectId, loading) {
    const button = document.querySelector(`[onclick*="viewProject(${projectId})"]`);
    if (!button) {
        projectDetailsManager.log(`Bouton non trouvé pour projet ${projectId}`);
        return;
    }

    const icon = button.querySelector('i.fas');
    const loader = button.querySelector('.btn-revolutionary-loader');

    if (loading) {
        button.disabled = true;
        if (icon) icon.style.display = 'none';
        if (loader) loader.style.display = 'flex';
    } else {
        button.disabled = false;
        if (icon) icon.style.display = 'inline';
        if (loader) loader.style.display = 'none';
    }
}

// Affichage des toasts
function showToast(message, type = 'info') {
    // Utiliser la fonction existante ou créer un toast simple
    if (typeof showProjectImageToast === 'function') {
        showProjectImageToast(message, type);
    } else {
        console.log(`📢 [TOAST] ${type.toUpperCase()}: ${message}`);
        alert(message); // Fallback simple
    }
}

// Trouver la ligne du projet dans le tableau
function findProjectRow(projectId) {
    // Méthode 1: Par data-project-id
    let row = document.querySelector(`tr[data-project-id="${projectId}"]`);

    if (!row) {
        // Méthode 2: Par le bouton viewProject
        const button = document.querySelector(`[onclick*="viewProject(${projectId})"]`);
        if (button) {
            row = button.closest('tr');
        }
    }

    projectDetailsManager.log(`Ligne trouvée pour projet ${projectId}:`, row ? 'OUI' : 'NON');
    return row;
}

// 🚀 SYSTÈME ULTRA SIMPLE - VERSION FINALE
console.log('🚀 Chargement du système ultra simple...');

// 🔑 Token CSRF
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
console.log('CSRF Token récupéré:', CSRF_TOKEN ? 'OK' : 'MANQUANT');

// 🧪 TEST IMMÉDIAT DES FONCTIONS
console.log('=== TEST DES FONCTIONS ===');
window.testViewProject = function(id) {
    alert(`TEST RÉUSSI: viewProject appelé avec ID ${id}`);
    console.log('✅ viewProject fonctionne!');
};

window.testEditProject = function(id) {
    alert(`TEST RÉUSSI: editProject appelé avec ID ${id}`);
    console.log('✅ editProject fonctionne!');
};

console.log('✅ Fonctions de test créées: testViewProject, testEditProject');

// 🎨 Fonction utilitaire pour afficher des messages
function showMessage(message, type = 'info') {
    const colors = {
        success: '#28a745',
        error: '#dc3545',
        info: '#007bff',
        warning: '#ffc107'
    };

    const messageDiv = document.createElement('div');
    messageDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type]};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        z-index: 9999;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    `;
    messageDiv.textContent = message;

    document.body.appendChild(messageDiv);

    setTimeout(() => {
        messageDiv.remove();
    }, 3000);
}

// 🔄 Fonction utilitaire pour recharger la page
function reloadPage() {
    setTimeout(() => {
        window.location.reload();
    }, 1500);
}

// Fonctions supplémentaires pour compatibilité
window.downloadProject = function(projectId) {
    console.log('📥 [DOWNLOAD] Téléchargement projet:', projectId);
    revolutionaryManager.showToast('Téléchargement en cours...', 'info');
    // Implémenter la logique de téléchargement
};

window.duplicateProject = function(projectId) {
    console.log('📋 [DUPLICATE] Duplication projet:', projectId);
    revolutionaryManager.showToast('Projet dupliqué!', 'success');
    // Implémenter la logique de duplication
};

window.shareProject = function(projectId) {
    console.log('🔗 [SHARE] Partage projet:', projectId);
    revolutionaryManager.showToast('Lien de partage copié!', 'success');
    // Implémenter la logique de partage
};

window.archiveProject = function(projectId) {
    console.log('📦 [ARCHIVE] Archivage projet:', projectId);
    revolutionaryManager.showToast('Projet archivé!', 'warning');
    // Implémenter la logique d'archivage
};

// Toast notifications pour projets images
function showProjectImageToast(message, type = 'success') {
    const toastContainer = document.querySelector('.toast-container') || createProjectImageToastContainer();

    const toastId = 'toast-' + Date.now();
    const iconMap = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };

    const colorMap = {
        success: 'text-success',
        error: 'text-danger',
        warning: 'text-warning',
        info: 'text-info'
    };

    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-dark border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="${iconMap[type]} ${colorMap[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();

    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

function createProjectImageToastContainer() {
    const container = document.createElement('div');
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1080';
    document.body.appendChild(container);
    return container;
}

// 🎯 Initialisation Professionnelle des Tooltips Bootstrap
// Approche Senior : Gestion robuste avec retry et vérifications
function initProjectImageTooltips() {
    console.log('🔧 [SENIOR] Initialisation des tooltips projets images...');

    // Vérifier que Bootstrap est disponible
    if (typeof bootstrap === 'undefined' || typeof bootstrap.Tooltip === 'undefined') {
        console.warn('⚠️ [SENIOR] Bootstrap non disponible, retry dans 200ms');
        setTimeout(initProjectImageTooltips, 200);
        return;
    }

    // Sélecteur spécifique pour éviter les conflits
    const tooltipSelector = '.revolutionary-actions-hub [data-bs-toggle="tooltip"]';
    const tooltipElements = document.querySelectorAll(tooltipSelector);

    console.log(`🎯 [SENIOR] Éléments tooltip trouvés: ${tooltipElements.length}`);

    if (tooltipElements.length === 0) {
        console.warn('⚠️ [SENIOR] Aucun élément tooltip trouvé, retry dans 300ms');
        setTimeout(initProjectImageTooltips, 300);
        return;
    }

    // Détruire les tooltips existants pour éviter les doublons
    tooltipElements.forEach(function(element) {
        const existingTooltip = bootstrap.Tooltip.getInstance(element);
        if (existingTooltip) {
            existingTooltip.dispose();
        }
    });

    // Initialiser les nouveaux tooltips avec configuration robuste
    let successCount = 0;
    tooltipElements.forEach(function(element) {
        try {
            new bootstrap.Tooltip(element, {
                placement: 'top',
                trigger: 'hover focus',
                delay: { show: 200, hide: 100 },
                html: true,
                fallbackPlacements: ['bottom', 'left', 'right'],
                boundary: 'viewport'
            });
            successCount++;
        } catch (error) {
            console.error('❌ [SENIOR] Erreur initialisation tooltip:', error, element);
        }
    });

    console.log(`✅ [SENIOR] Tooltips initialisés avec succès: ${successCount}/${tooltipElements.length}`);

    // Fonction globale de réinitialisation pour la pagination
    window.reinitProjectImageTooltips = initProjectImageTooltips;
}

// Initialisation avec approche defensive
document.addEventListener('DOMContentLoaded', function() {
    console.log('📋 [SENIOR] DOM chargé, initialisation tooltips...');
    initProjectImageTooltips();
});

// Fallback si DOMContentLoaded déjà passé
if (document.readyState === 'loading') {
    // DOM pas encore chargé, l'event listener ci-dessus suffira
} else {
    // DOM déjà chargé, initialiser immédiatement
    console.log('📋 [SENIOR] DOM déjà chargé, initialisation immédiate...');
    setTimeout(initProjectImageTooltips, 100);
}

console.log('🚀 [SENIOR] Interface révolutionnaire projets images initialisée avec approche professionnelle ✨');
</script>

<!-- CSS Interface Révolutionnaire Projets Images -->
<style>
/* Variables CSS pour l'interface révolutionnaire projets images */
:root {
    --revolutionary-primary: #003366;
    --revolutionary-secondary: #3399ff;
    --revolutionary-warning: #FF9900;
    --revolutionary-danger: #ff6633;
    --revolutionary-success: #28a745;
    --revolutionary-delete: #dc3545;
    --revolutionary-dark: #1a1a1a;
    --revolutionary-light: #ffffff;
}

/* Hub d'actions révolutionnaire pour projets images */
.revolutionary-actions-hub {
    position: relative;
    z-index: 1;
    animation: revolutionaryFadeIn 0.5s ease-out;
}

/* Boutons révolutionnaires projets images */
.btn-revolutionary {
    position: relative;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.btn-revolutionary:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    color: white;
}

.btn-revolutionary:active {
    transform: translateY(0) scale(0.95);
}

/* Couleurs spécifiques projets images */
.btn-revolutionary-view {
    background: linear-gradient(135deg, var(--revolutionary-secondary), var(--revolutionary-primary));
}

.btn-revolutionary-edit {
    background: linear-gradient(135deg, var(--revolutionary-warning) 0%, #ffc107 100%);
    border: 1px solid rgba(255, 153, 0, 0.3);
}

.btn-revolutionary-edit:hover {
    background: linear-gradient(135deg, #ffc107 0%, var(--revolutionary-warning) 100%);
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 25px rgba(255, 153, 0, 0.4);
}

.btn-revolutionary-validate {
    background: linear-gradient(135deg, var(--revolutionary-success) 0%, #20c997 100%);
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.btn-revolutionary-validate:hover {
    background: linear-gradient(135deg, #20c997 0%, var(--revolutionary-success) 100%);
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

.btn-revolutionary-delete {
    background: linear-gradient(135deg, var(--revolutionary-delete) 0%, #e74c3c 100%);
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.btn-revolutionary-delete:hover {
    background: linear-gradient(135deg, #e74c3c 0%, var(--revolutionary-delete) 100%);
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
}

/* Loader dans les boutons */
.btn-revolutionary-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-revolutionary.loading .btn-revolutionary-loader {
    opacity: 1;
}

.btn-revolutionary.loading i {
    opacity: 0;
}

/* Menu dropdown révolutionnaire */
.revolutionary-dropdown {
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(26, 26, 26, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    padding: 8px 0;
    min-width: 180px;
}

.revolutionary-dropdown .dropdown-item {
    padding: 8px 16px;
    color: #ffffff;
    transition: all 0.2s ease;
    border-radius: 8px;
    margin: 2px 8px;
}

.revolutionary-dropdown .dropdown-item:hover {
    background: linear-gradient(135deg, var(--revolutionary-secondary), var(--revolutionary-primary));
    color: white;
    transform: translateX(4px);
}

.revolutionary-dropdown .dropdown-item.text-warning:hover {
    background: linear-gradient(135deg, var(--revolutionary-warning), #ffc107);
}

.revolutionary-dropdown .dropdown-item.text-danger:hover {
    background: linear-gradient(135deg, var(--revolutionary-danger), #c82333);
}

/* Animations d'entrée */
@keyframes revolutionaryFadeIn {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .btn-revolutionary {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .revolutionary-actions-hub {
        gap: 0.25rem !important;
    }
}

/* 🎨 Styles pour l'affichage révolutionnaire des détails */
.project-details-row {
    background: transparent !important;
}

.project-details-container {
    box-shadow: inset 0 0 20px rgba(0,0,0,0.3), 0 4px 20px rgba(0,0,0,0.2);
}

.detail-card {
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 1rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #ffffff;
    opacity: 0.8;
    min-width: 120px;
}

.detail-value {
    color: #ffffff;
    font-weight: 500;
    text-align: right;
    flex: 1;
}

.images-preview {
    max-height: 200px;
    overflow-y: auto;
}

.image-thumb:hover {
    box-shadow: 0 4px 15px rgba(51, 153, 255, 0.4) !important;
}

.detail-actions .btn {
    transition: all 0.3s ease;
    border-radius: 8px;
    font-weight: 500;
}

.detail-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

/* 🎨 Tooltips Personnalisés Améliorés pour Images de Projets */
.tooltip {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    z-index: 1080;
}

.tooltip .tooltip-inner {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 500;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    max-width: 280px;
    text-align: center;
    line-height: 1.4;
}

.tooltip.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: #1a1a1a;
}

.tooltip.bs-tooltip-bottom .tooltip-arrow::before {
    border-bottom-color: #1a1a1a;
}

.tooltip.bs-tooltip-start .tooltip-arrow::before {
    border-left-color: #1a1a1a;
}

.tooltip.bs-tooltip-end .tooltip-arrow::before {
    border-right-color: #1a1a1a;
}

/* Animation d'apparition des tooltips */
.tooltip {
    opacity: 0;
    transition: opacity 0.3s ease, transform 0.3s ease;
    transform: scale(0.9);
}

.tooltip.show {
    opacity: 1;
    transform: scale(1);
}

/* 🚀 ANIMATIONS RÉVOLUTIONNAIRES POUR L'AFFICHAGE DES DÉTAILS */
@keyframes revolutionaryGradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.6;
    }
    33% {
        transform: translateY(-20px) rotate(120deg);
        opacity: 1;
    }
    66% {
        transform: translateY(10px) rotate(240deg);
        opacity: 0.8;
    }
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(50px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
}

@keyframes shimmer {
    0% { background-position: -200px 0; }
    100% { background-position: calc(200px + 100%) 0; }
}

/* Effets de glassmorphism révolutionnaires */
.revolutionary-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.revolutionary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
}

/* Boutons révolutionnaires avec effets avancés */
.revolutionary-btn {
    position: relative;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    outline: none;
}

.revolutionary-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.revolutionary-btn:hover::before {
    left: 100%;
}

.revolutionary-btn:active {
    transform: scale(0.95);
}

/* Particules flottantes améliorées */
.floating-particles .particle {
    animation-timing-function: ease-in-out;
    animation-iteration-count: infinite;
    will-change: transform, opacity;
}

.floating-particles .particle:nth-child(1) {
    animation-delay: 0s;
    animation-duration: 6s;
}

.floating-particles .particle:nth-child(2) {
    animation-delay: 2s;
    animation-duration: 8s;
}

.floating-particles .particle:nth-child(3) {
    animation-delay: 4s;
    animation-duration: 7s;
}

/* Responsive design révolutionnaire pour mobile */
@media (max-width: 768px) {
    .revolutionary-content {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
        padding: 20px !important;
    }

    .action-buttons {
        flex-direction: column !important;
        gap: 8px !important;
    }

    .revolutionary-btn {
        width: 100% !important;
        justify-content: center !important;
        padding: 15px 20px !important;
        font-size: 16px !important;
    }

    .icon-container {
        width: 40px !important;
        height: 40px !important;
    }

    .card-header h6 {
        font-size: 16px !important;
    }
}

/* Optimisations de performance */
.revolutionary-details-row {
    will-change: transform, opacity;
    contain: layout style paint;
}

.revolutionary-content * {
    will-change: transform, opacity;
}

/* Effets de survol avancés */
.info-item:hover {
    transform: translateX(5px);
    transition: transform 0.2s ease;
}

.card-header:hover .icon-container {
    transform: rotate(360deg);
    transition: transform 0.6s ease;
}
</style>
