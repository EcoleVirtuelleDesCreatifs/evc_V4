{{--
    Composant Blade pour l'affichage des projets paginés par session

    @param array $projectsData - Données des projets avec pagination
    @param string $tableId - ID unique du tableau
    @param string $pageParam - Paramètre de pagination (ex: 'design_page')
--}}

@props([
    'projectsData',
    'tableId',
    'pageParam' => 'page',
    'studentId'
])

<div class="dashboard-card mb-4" id="{{ $tableId }}-container">
    <div class="card-body p-4">
        <!-- En-tête avec informations de pagination -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="dashboard-icon me-3">
                    <i class="fas fa-project-diagram fa-lg text-info"></i>
                </div>
                <div>
                    <h5 class="text-white fw-bold mb-0">
                        {{ $projectsData['project_type_label'] }}
                        <span class="badge bg-info ms-2">{{ $projectsData['pagination']['total_projects'] }}</span>
                    </h5>
                    <small class="text-white-50">
                        @if($projectsData['pagination']['total_projects'] > 0)
                            Affichage {{ $projectsData['pagination']['showing_from'] }}-{{ $projectsData['pagination']['showing_to'] }}
                            sur {{ $projectsData['pagination']['total_projects'] }} projets
                        @else
                            Aucun projet trouvé
                        @endif
                    </small>
                </div>
            </div>

            <!-- Contrôles de pagination -->
            @if($projectsData['pagination']['total_pages'] > 1)
                <div class="d-flex gap-2 align-items-center">
                    <small class="text-white-50 me-2">
                        Page {{ $projectsData['pagination']['current_page'] }} / {{ $projectsData['pagination']['total_pages'] }}
                    </small>

                    @if($projectsData['pagination']['has_previous_page'])
                        <button class="btn btn-outline-light btn-sm"
                                onclick="loadProjectsPage('{{ $pageParam }}', {{ $projectsData['pagination']['current_page'] - 1 }}, '{{ $studentId }}')">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                    @endif

                    @if($projectsData['pagination']['has_next_page'])
                        <button class="btn btn-outline-light btn-sm"
                                onclick="loadProjectsPage('{{ $pageParam }}', {{ $projectsData['pagination']['current_page'] + 1 }}, '{{ $studentId }}')">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            @endif
        </div>

        <!-- Affichage par session -->
        @if(count($projectsData['projects_by_session']) > 0)
            @foreach($projectsData['projects_by_session'] as $sessionName => $sessionData)
                <div class="session-group mb-4">
                    <!-- En-tête de session -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="session-divider flex-grow-1" style="height: 2px; background: linear-gradient(90deg, #FF6B35, transparent);"></div>
                        <div class="mx-3">
                            <span class="badge bg-gradient-primary px-3 py-2" style="background: linear-gradient(45deg, #003366, #3399ff);">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $sessionName }}
                                <span class="ms-1">({{ $sessionData['projects_count'] }})</span>
                            </span>
                        </div>
                        <div class="session-divider flex-grow-1" style="height: 2px; background: linear-gradient(90deg, transparent, #FF6B35);"></div>
                    </div>

                    <!-- Tableau des projets de la session -->
                    <div class="table-responsive">
                        <table class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 45%;">Nom du Projet</th>
                                    <th scope="col" style="width: 20%;">Statut</th>
                                    <th scope="col" style="width: 20%;">Date</th>
                                    <th scope="col" style="width: 15%;" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessionData['projects'] as $project)
                                    <tr id="project-row-{{ $project->id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    <i class="fas fa-project-diagram text-info"></i>
                                                </div>
                                                <div style="min-width: 0;">
                                                    <div class="text-white fw-bold text-truncate" title="{{ $project->project_name }}">
                                                        {{ $project->project_name }}
                                                    </div>
                                                    <div class="d-flex gap-1 mt-1">
                                                        <span class="badge bg-info" style="font-size: 0.6rem;">
                                                            {{ $project->type_label }}
                                                        </span>
                                                        @if(isset($project->project_mode))
                                                            @if($project->project_mode == 'solo')
                                                                <span class="badge bg-primary" style="font-size: 0.6rem;">
                                                                    <i class="fas fa-user me-1"></i>Solo
                                                                </span>
                                                            @else
                                                                <span class="badge bg-warning" style="font-size: 0.6rem;">
                                                                    <i class="fas fa-users me-1"></i>Groupe
                                                                </span>
                                                            @endif
                                                        @endif
                                                        @if(isset($project->files_count) && $project->files_count > 0)
                                                            <span class="badge bg-secondary" style="font-size: 0.6rem;">
                                                                <i class="fas fa-file me-1"></i>{{ $project->files_count }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                    @if(isset($project->description) && $project->description)
                                                        <small class="text-white-50" style="font-size: 0.7rem;">
                                                            {{ Str::limit($project->description, 60) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($project->status) {
                                                    'completed', 'valide' => 'bg-success',
                                                    'active', 'en_cours' => 'bg-warning',
                                                    'draft' => 'bg-secondary',
                                                    'rejected', 'rejete' => 'bg-danger',
                                                    default => 'bg-info'
                                                };

                                                $statusIcon = match($project->status) {
                                                    'completed', 'valide' => 'fas fa-check-circle',
                                                    'active', 'en_cours' => 'fas fa-clock',
                                                    'draft' => 'fas fa-edit',
                                                    'rejected', 'rejete' => 'fas fa-times-circle',
                                                    default => 'fas fa-info-circle'
                                                };
                                            @endphp

                                            <span class="badge {{ $statusClass }}">
                                                <i class="{{ $statusIcon }} me-1"></i>{{ $project->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-white-75">
                                                {{ \Carbon\Carbon::parse($project->created_at)->format('d/m/Y') }}
                                            </div>
                                            <small class="text-white-50">
                                                {{ \Carbon\Carbon::parse($project->created_at)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td class="text-center">
                                            <!-- 🚀 INTERFACE RÉVOLUTIONNAIRE - Actions Hub Projet -->
                                            <div class="project-action-hub" data-project-id="{{ $project->id }}">
                                                <!-- Action primaire - Voir projet -->
                                                <button type="button" 
                                                        class="action-btn action-view action-primary" 
                                                        data-tooltip="👁️ Afficher les détails complets du projet"
                                                        onclick="viewProject({{ $project->id }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Actions secondaires -->
                                                <div class="secondary-project-actions">
                                                    <button type="button" 
                                                            class="action-btn action-edit" 
                                                            data-tooltip="✏️ Modifier le titre, description et paramètres"
                                                            onclick="editProject({{ $project->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    @if(!in_array($project->status, ['completed', 'valide']))
                                                        <button type="button" 
                                                                class="action-btn action-validate" 
                                                                data-tooltip="✅ Valider et approuver ce projet"
                                                                onclick="validateProjectRevolutionary({{ $project->id }})">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif

                                                    <button type="button" 
                                                            class="action-btn action-download" 
                                                            data-tooltip="📥 Télécharger tous les fichiers du projet"
                                                            onclick="downloadProject({{ $project->id }})">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        @else
            <!-- Message d'état vide -->
            <div class="text-center py-5">
                <div class="text-white-50">
                    <i class="fas fa-project-diagram fa-3x mb-3 opacity-50"></i>
                    <h6 class="text-white-75">Aucun projet {{ strtolower($projectsData['project_type_label']) }}</h6>
                    <p class="mb-0">Cet étudiant n'a pas encore créé de projet de ce type.</p>
                </div>
            </div>
        @endif

        <!-- Navigation de pagination en bas -->
        @if($projectsData['pagination']['total_pages'] > 1)
            <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid var(--dashboard-border);">
                <div class="text-white-50">
                    <small>
                        {{ $projectsData['pagination']['total_projects'] }} projet(s) au total
                    </small>
                </div>

                <nav aria-label="Pagination des projets">
                    <ul class="pagination pagination-sm mb-0">
                        @if($projectsData['pagination']['has_previous_page'])
                            <li class="page-item">
                                <button class="page-link bg-dark border-secondary text-light"
                                        onclick="loadProjectsPage('{{ $pageParam }}', {{ $projectsData['pagination']['current_page'] - 1 }}, '{{ $studentId }}')">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                            </li>
                        @endif

                        @for($i = max(1, $projectsData['pagination']['current_page'] - 2); $i <= min($projectsData['pagination']['total_pages'], $projectsData['pagination']['current_page'] + 2); $i++)
                            <li class="page-item {{ $i == $projectsData['pagination']['current_page'] ? 'active' : '' }}">
                                <button class="page-link {{ $i == $projectsData['pagination']['current_page'] ? 'bg-primary border-primary' : 'bg-dark border-secondary text-light' }}"
                                        onclick="loadProjectsPage('{{ $pageParam }}', {{ $i }}, '{{ $studentId }}')">
                                    {{ $i }}
                                </button>
                            </li>
                        @endfor

                        @if($projectsData['pagination']['has_next_page'])
                            <li class="page-item">
                                <button class="page-link bg-dark border-secondary text-light"
                                        onclick="loadProjectsPage('{{ $pageParam }}', {{ $projectsData['pagination']['current_page'] + 1 }}, '{{ $studentId }}')">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        @endif
    </div>
</div>

<style>
.session-group {
    margin-bottom: 2rem;
}

.session-divider {
    opacity: 0.6;
}

.pagination .page-link:hover {
    background-color: var(--dashboard-accent) !important;
    border-color: var(--dashboard-accent) !important;
    color: white !important;
}

.table-responsive {
    border-radius: 8px;
    overflow: hidden;
}

.badge {
    font-weight: 500;
}

.dropdown-menu-dark {
    background-color: rgba(0, 0, 0, 0.9);
    border: 1px solid var(--dashboard-border);
}

.dropdown-item:hover {
    background-color: var(--dashboard-accent);
    color: white;
}

/* 🚀 INTERFACE RÉVOLUTIONNAIRE POUR TABLEAUX PROJETS */
.project-action-hub {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 4px;
    min-height: 40px;
}

.project-action-hub .action-btn {
    position: relative;
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    font-size: 12px;
}

.project-action-hub .action-btn:hover {
    transform: translateY(-2px) scale(1.1);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

.project-action-hub .action-btn:active {
    transform: translateY(0) scale(0.95);
}

/* Actions spécifiques pour projets */
.project-action-hub .action-primary {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.project-action-hub .action-edit {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.project-action-hub .action-validate {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.project-action-hub .action-download {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #333;
}

.project-action-hub .action-more {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

/* Actions secondaires pour projets */
.secondary-project-actions {
    display: flex;
    gap: 4px;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.project-action-hub:hover .secondary-project-actions {
    opacity: 1;
}

/* Tooltips pour tableaux */
.project-action-hub .action-btn[data-tooltip]:hover::before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.9);
    color: white;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    white-space: nowrap;
    z-index: 1000;
    animation: tooltip-appear 0.3s ease;
}

.project-action-hub .action-btn[data-tooltip]:hover::after {
    content: '';
    position: absolute;
    bottom: 110%;
    left: 50%;
    transform: translateX(-50%);
    border: 4px solid transparent;
    border-top-color: rgba(0,0,0,0.9);
    z-index: 1000;
}

@keyframes tooltip-appear {
    from { opacity: 0; transform: translateX(-50%) translateY(5px); }
    to { opacity: 1; transform: translateX(-50%) translateY(0); }
}

/* Responsive pour tableaux */
@media (max-width: 768px) {
    .project-action-hub {
        transform: scale(0.9);
    }
    
    .action-btn {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
    
    .secondary-project-actions {
        gap: 4px;
    }
}

/* 🎨 Tooltips Personnalisés Améliorés */
.tooltip {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
    max-width: 250px;
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
</style>

<script>
// 🚀 FONCTIONS RÉVOLUTIONNAIRES POUR TABLEAUX PROJETS

/**
 * Validation de projet révolutionnaire avec animation
 */
function validateProjectRevolutionary(projectId) {
    const actionHub = document.querySelector(`[data-project-id="${projectId}"]`);
    const validateBtn = actionHub.querySelector('.action-validate');
    
    if (!validateBtn) return;
    
    // Animation de chargement
    const originalContent = validateBtn.innerHTML;
    validateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    validateBtn.disabled = true;
    
    // Simulation de validation (remplacer par vraie logique)
    setTimeout(() => {
        // Animation de succès
        validateBtn.innerHTML = '<i class="fas fa-check"></i>';
        validateBtn.style.background = 'linear-gradient(135deg, #00b894 0%, #00cec9 100%)';
        
        // Notification toast
        showProjectToast('Projet validé avec succès !', 'success');
        
        // Masquer le bouton après validation
        setTimeout(() => {
            validateBtn.style.opacity = '0';
            validateBtn.style.transform = 'scale(0)';
            setTimeout(() => validateBtn.remove(), 300);
        }, 1500);
        
    }, 1000);
}

/**
 * Téléchargement de projet avec feedback
 */
function downloadProject(projectId) {
    const actionHub = document.querySelector(`[data-project-id="${projectId}"]`);
    const downloadBtn = actionHub.querySelector('.action-download');
    
    // Animation de téléchargement
    const originalContent = downloadBtn.innerHTML;
    downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    downloadBtn.disabled = true;
    
    // Simulation de téléchargement
    setTimeout(() => {
        downloadBtn.innerHTML = '<i class="fas fa-check"></i>';
        showProjectToast('Téléchargement démarré !', 'info');
        
        // Retour à l'état normal
        setTimeout(() => {
            downloadBtn.innerHTML = originalContent;
            downloadBtn.disabled = false;
        }, 1500);
    }, 800);
}

/**
 * Menu d'actions rapides pour projets
 */
function showProjectQuickActions(projectId) {
    // Créer menu contextuel moderne
    const quickMenu = document.createElement('div');
    quickMenu.className = 'project-quick-actions-menu';
    quickMenu.innerHTML = `
        <div class="quick-action" onclick="duplicateProject(${projectId})">
            <i class="fas fa-copy"></i> Dupliquer
        </div>
        <div class="quick-action" onclick="shareProject(${projectId})">
            <i class="fas fa-share"></i> Partager
        </div>
        <div class="quick-action" onclick="exportProject(${projectId})">
            <i class="fas fa-file-export"></i> Exporter
        </div>
        <div class="quick-action" onclick="archiveProject(${projectId})">
            <i class="fas fa-archive"></i> Archiver
        </div>
        <div class="quick-action danger" onclick="deleteProjectConfirm(${projectId})">
            <i class="fas fa-trash"></i> Supprimer
        </div>
    `;
    
    // Positionnement intelligent
    document.body.appendChild(quickMenu);
    
    // Animation d'apparition
    requestAnimationFrame(() => {
        quickMenu.style.opacity = '1';
        quickMenu.style.transform = 'translateY(0)';
    });
    
    // Fermeture automatique
    setTimeout(() => {
        if (quickMenu.parentNode) {
            quickMenu.remove();
        }
    }, 5000);
}

/**
 * Toast notification pour projets
 */
function showProjectToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `project-toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}-circle"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(toast);
    
    // Animation d'entrée
    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    });
    
    // Suppression automatique
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Actions supplémentaires pour projets
function duplicateProject(id) { 
    console.log('Dupliquer projet:', id);
    showProjectToast('Projet dupliqué !', 'success');
}
function shareProject(id) { 
    console.log('Partager projet:', id);
    showProjectToast('Lien de partage copié !', 'info');
}
function exportProject(id) { 
    console.log('Exporter projet:', id);
    showProjectToast('Export en cours...', 'info');
}
function archiveProject(id) { 
    console.log('Archiver projet:', id);
    showProjectToast('Projet archivé !', 'success');
}
function deleteProjectConfirm(id) { 
    if (confirm('Supprimer définitivement ce projet ?')) {
        console.log('Supprimer projet:', id);
        showProjectToast('Projet supprimé !', 'success');
        // Masquer la ligne du tableau
        const row = document.getElementById(`project-row-${id}`);
        if (row) {
            row.style.opacity = '0';
            row.style.transform = 'translateX(-100%)';
            setTimeout(() => row.remove(), 500);
        }
    }
}

console.log('🚀 Interface révolutionnaire projets initialisée !');
</script>

<!-- CSS pour les composants dynamiques des projets -->
<style>
.project-quick-actions-menu {
    position: fixed;
    top: 50%;
    right: 20px;
    transform: translateY(-50%) translateY(20px);
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    padding: 12px;
    z-index: 10000;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 180px;
}

.project-quick-actions-menu .quick-action {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s ease;
    color: #333;
    font-size: 13px;
}

.project-quick-actions-menu .quick-action:hover {
    background: #f8f9fa;
}

.project-quick-actions-menu .quick-action.danger {
    color: #dc3545;
}

.project-quick-actions-menu .quick-action.danger:hover {
    background: #fff5f5;
}

.project-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 12px 16px;
    border-radius: 8px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 10000;
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    font-size: 14px;
}

.project-toast.toast-success {
    border-left: 4px solid #28a745;
    color: #28a745;
}

.project-toast.toast-info {
    border-left: 4px solid #007bff;
    color: #007bff;
}

.project-toast.toast-error {
    border-left: 4px solid #dc3545;
    color: #dc3545;
}
</style>
