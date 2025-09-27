/**
 * JavaScript pour la gestion de la pagination AJAX des projets étudiants
 * 
 * @author Développeur Senior EVC
 * @version 1.0
 */

class AdminProjectsPagination {
    constructor() {
        this.loadingStates = new Map();
        this.init();
    }

    /**
     * Initialisation du système de pagination
     */
    init() {
        console.log('AdminProjectsPagination initialisé');
        this.setupEventListeners();
    }

    /**
     * Configuration des écouteurs d'événements
     */
    setupEventListeners() {
        // Écouter les changements d'URL pour la pagination
        window.addEventListener('popstate', (event) => {
            if (event.state && event.state.page) {
                this.loadProjectsPage(event.state.pageParam, event.state.page, event.state.studentId, false);
            }
        });
    }

    /**
     * Charger une page de projets via AJAX
     * 
     * @param {string} pageParam - Paramètre de pagination (design_page|laravel_page)
     * @param {number} page - Numéro de page
     * @param {number} studentId - ID de l'étudiant
     * @param {boolean} updateHistory - Mettre à jour l'historique du navigateur
     */
    async loadProjectsPage(pageParam, page, studentId, updateHistory = true) {
        try {
            // Vérifier si une requête est déjà en cours
            const loadingKey = `${pageParam}_${studentId}`;
            if (this.loadingStates.get(loadingKey)) {
                console.log('Requête déjà en cours pour:', loadingKey);
                return;
            }

            // Marquer comme en cours de chargement
            this.loadingStates.set(loadingKey, true);
            
            // Afficher l'indicateur de chargement
            this.showLoadingState(pageParam);

            // Construire l'URL de la requête
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set(pageParam, page);

            // Effectuer la requête AJAX
            const response = await fetch(currentUrl.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });

            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }

            // Parser la réponse HTML
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Extraire le contenu mis à jour
            const tableId = pageParam === 'design_page' ? 'design-projects-table' : 'laravel-projects-table';
            const newTableContainer = doc.querySelector(`#${tableId}-container`);

            if (newTableContainer) {
                // Remplacer le contenu du tableau
                const currentTableContainer = document.querySelector(`#${tableId}-container`);
                if (currentTableContainer) {
                    // Animation de transition
                    currentTableContainer.style.opacity = '0.5';
                    currentTableContainer.style.transition = 'opacity 0.3s ease';

                    setTimeout(() => {
                        currentTableContainer.outerHTML = newTableContainer.outerHTML;
                        
                        // Réinitialiser les tooltips et dropdowns
                        this.reinitializeComponents();
                        
                        // Animation d'apparition
                        const updatedContainer = document.querySelector(`#${tableId}-container`);
                        if (updatedContainer) {
                            updatedContainer.style.opacity = '0';
                            updatedContainer.style.transition = 'opacity 0.3s ease';
                            setTimeout(() => {
                                updatedContainer.style.opacity = '1';
                            }, 50);
                        }
                    }, 300);
                }

                // Mettre à jour l'historique du navigateur
                if (updateHistory) {
                    const newUrl = new URL(window.location);
                    newUrl.searchParams.set(pageParam, page);
                    
                    window.history.pushState(
                        { page, pageParam, studentId },
                        '',
                        newUrl.toString()
                    );
                }

                // Afficher une notification de succès
                this.showNotification('Projets mis à jour avec succès', 'success');

            } else {
                throw new Error('Impossible de trouver le contenu mis à jour');
            }

        } catch (error) {
            console.error('Erreur lors du chargement de la page:', error);
            this.showNotification('Erreur lors du chargement des projets', 'error');
        } finally {
            // Supprimer l'état de chargement
            const loadingKey = `${pageParam}_${studentId}`;
            this.loadingStates.delete(loadingKey);
            this.hideLoadingState(pageParam);
        }
    }

    /**
     * Afficher l'état de chargement
     * 
     * @param {string} pageParam - Paramètre de pagination
     */
    showLoadingState(pageParam) {
        const tableId = pageParam === 'design_page' ? 'design-projects-table' : 'laravel-projects-table';
        const container = document.querySelector(`#${tableId}-container`);
        
        if (container) {
            // Ajouter un overlay de chargement
            const loadingOverlay = document.createElement('div');
            loadingOverlay.className = 'loading-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center';
            loadingOverlay.style.cssText = `
                background: rgba(0, 0, 0, 0.7);
                z-index: 1000;
                border-radius: 8px;
            `;
            
            loadingOverlay.innerHTML = `
                <div class="text-center text-white">
                    <div class="spinner-border text-info mb-2" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <div class="small">Chargement des projets...</div>
                </div>
            `;

            // Assurer que le container est en position relative
            container.style.position = 'relative';
            container.appendChild(loadingOverlay);
        }
    }

    /**
     * Masquer l'état de chargement
     * 
     * @param {string} pageParam - Paramètre de pagination
     */
    hideLoadingState(pageParam) {
        const tableId = pageParam === 'design_page' ? 'design-projects-table' : 'laravel-projects-table';
        const container = document.querySelector(`#${tableId}-container`);
        
        if (container) {
            const loadingOverlay = container.querySelector('.loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
        }
    }

    /**
     * Réinitialiser les composants Bootstrap après mise à jour AJAX
     */
    reinitializeComponents() {
        // Réinitialiser les tooltips
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Réinitialiser les dropdowns
        if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        }
    }

    /**
     * Afficher une notification
     * 
     * @param {string} message - Message à afficher
     * @param {string} type - Type de notification (success|error|info|warning)
     */
    showNotification(message, type = 'info') {
        // Créer l'élément de notification
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        `;

        const icon = this.getNotificationIcon(type);
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="${icon} me-2"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        // Ajouter au DOM
        document.body.appendChild(notification);

        // Auto-supprimer après 5 secondes
        setTimeout(() => {
            if (notification.parentElement) {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 150);
            }
        }, 5000);
    }

    /**
     * Obtenir l'icône pour le type de notification
     * 
     * @param {string} type - Type de notification
     * @returns {string} Classe CSS de l'icône
     */
    getNotificationIcon(type) {
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-triangle',
            warning: 'fas fa-exclamation-circle',
            info: 'fas fa-info-circle'
        };
        return icons[type] || icons.info;
    }

    /**
     * Actualiser tous les tableaux de projets
     * 
     * @param {number} studentId - ID de l'étudiant
     */
    async refreshAllProjectsTables(studentId) {
        try {
            // Actualiser les projets design
            await this.loadProjectsPage('design_page', 1, studentId, false);
            
            // Attendre un peu avant la deuxième requête
            await new Promise(resolve => setTimeout(resolve, 500));
            
            // Actualiser les projets Laravel
            await this.loadProjectsPage('laravel_page', 1, studentId, false);
            
            this.showNotification('Tous les tableaux ont été actualisés', 'success');
            
        } catch (error) {
            console.error('Erreur lors de l\'actualisation:', error);
            this.showNotification('Erreur lors de l\'actualisation des tableaux', 'error');
        }
    }

    /**
     * Exporter toutes les données des projets
     * 
     * @param {number} studentId - ID de l'étudiant
     */
    async exportAllProjectsData(studentId) {
        try {
            this.showNotification('Préparation de l\'export en cours...', 'info');
            
            // Simuler l'export (à implémenter côté serveur)
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            this.showNotification('Export terminé avec succès', 'success');
            
        } catch (error) {
            console.error('Erreur lors de l\'export:', error);
            this.showNotification('Erreur lors de l\'export des données', 'error');
        }
    }

    /**
     * Charger une page spécifique des project_images (TP) via AJAX
     * @param {string} pageParam - Paramètre de page (ex: 'images_page')
     * @param {number} page - Numéro de la page
     * @param {number} studentId - ID de l'étudiant
     * @param {boolean} updateHistory - Mettre à jour l'historique du navigateur
     */
    async loadProjectImagesPage(pageParam, page, studentId, updateHistory = true) {
        try {
            // Vérifier si une requête est déjà en cours
            const loadingKey = `${pageParam}_${studentId}`;
            if (this.loadingStates.get(loadingKey)) {
                console.log('Requête déjà en cours pour:', loadingKey);
                return;
            }

            // Marquer comme en cours de chargement
            this.loadingStates.set(loadingKey, true);
            
            // Afficher l'indicateur de chargement pour les project_images
            this.showLoadingStateForImages(pageParam);

            // Construire l'URL de la requête
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set(pageParam, page);

            // Effectuer la requête AJAX
            const response = await fetch(currentUrl.toString(), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });

            if (!response.ok) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }

            // Parser la réponse HTML
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            // Extraire le contenu mis à jour pour les project_images
            const tableId = 'project-images-table';
            const newTableContainer = doc.querySelector(`#${tableId}-container`);

            if (newTableContainer) {
                // Remplacer le contenu du tableau
                const currentTableContainer = document.querySelector(`#${tableId}-container`);
                if (currentTableContainer) {
                    // Animation de transition
                    currentTableContainer.style.opacity = '0.5';
                    currentTableContainer.style.transition = 'opacity 0.3s ease';

                    setTimeout(() => {
                        currentTableContainer.outerHTML = newTableContainer.outerHTML;
                        
                        // Réinitialiser les tooltips et dropdowns
                        this.reinitializeComponents();
                        
                        // Animation d'apparition
                        const updatedContainer = document.querySelector(`#${tableId}-container`);
                        if (updatedContainer) {
                            updatedContainer.style.opacity = '0';
                            updatedContainer.style.transition = 'opacity 0.3s ease';
                            setTimeout(() => {
                                updatedContainer.style.opacity = '1';
                            }, 50);
                        }

                        // Mettre à jour l'historique du navigateur
                        if (updateHistory) {
                            const newState = {
                                pageParam: pageParam,
                                page: page,
                                studentId: studentId,
                                type: 'project_images'
                            };
                            
                            const newUrl = currentUrl.toString();
                            history.pushState(newState, '', newUrl);
                        }

                        this.showNotification(`TP chargés - Page ${page}`, 'success');
                        
                    }, 300);
                }
            } else {
                throw new Error('Impossible de trouver le nouveau contenu du tableau des TP');
            }

        } catch (error) {
            console.error('Erreur lors du chargement de la page des TP:', error);
            this.showNotification('Erreur lors du chargement des TP', 'error');
        } finally {
            // Libérer l'état de chargement
            const loadingKey = `${pageParam}_${studentId}`;
            this.loadingStates.set(loadingKey, false);
        }
    }

    /**
     * Afficher l'état de chargement pour les project_images
     * @param {string} pageParam - Paramètre de page
     */
    showLoadingStateForImages(pageParam) {
        const tableId = 'project-images-table';
        const container = document.querySelector(`#${tableId}-container`);
        
        if (container) {
            // Ajouter une classe de chargement
            container.classList.add('loading');
            
            // Optionnel: ajouter un spinner
            const existingSpinner = container.querySelector('.loading-spinner');
            if (!existingSpinner) {
                const spinner = document.createElement('div');
                spinner.className = 'loading-spinner position-absolute top-50 start-50 translate-middle';
                spinner.innerHTML = '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Chargement...</span></div>';
                container.style.position = 'relative';
                container.appendChild(spinner);
            }
        }
    }
}

// Instance globale
let adminProjectsPagination;

// Initialisation au chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    adminProjectsPagination = new AdminProjectsPagination();
});

// Fonctions globales pour la compatibilité avec les templates Blade
window.loadProjectsPage = function(pageParam, page, studentId) {
    if (adminProjectsPagination) {
        adminProjectsPagination.loadProjectsPage(pageParam, page, studentId);
    }
};

// Fonction spécifique pour la pagination des project_images (TP)
window.loadProjectImagesPage = function(pageParam, page, studentId) {
    if (adminProjectsPagination) {
        adminProjectsPagination.loadProjectImagesPage(pageParam, page, studentId);
    }
};

window.refreshAllProjectsTables = function() {
    const studentId = document.querySelector('[data-student-id]')?.dataset.studentId;
    if (adminProjectsPagination && studentId) {
        adminProjectsPagination.refreshAllProjectsTables(parseInt(studentId));
    }
};

window.exportAllProjectsData = function() {
    const studentId = document.querySelector('[data-student-id]')?.dataset.studentId;
    if (adminProjectsPagination && studentId) {
        adminProjectsPagination.exportAllProjectsData(parseInt(studentId));
    }
};

// Fonctions pour les actions sur les projets (compatibilité)
window.viewProject = function(projectId) {
    AdminProjectsPagination.showNotification(`Affichage du projet ${projectId}`, 'info');
    // TODO: Implement project view logic
};

window.editProject = function(projectId) {
    AdminProjectsPagination.showNotification(`Édition du projet ${projectId}`, 'info');
    // TODO: Implement project edit logic
};

window.validateProject = function(projectId) {
    if (confirm('Êtes-vous sûr de vouloir valider ce projet ?')) {
        AdminProjectsPagination.showNotification(`Projet ${projectId} validé`, 'success');
        // TODO: Implement project validation logic
    }
};

window.deleteProject = function(projectId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')) {
        AdminProjectsPagination.showNotification(`Projet ${projectId} supprimé`, 'success');
        // TODO: Implement project deletion logic
    }
};

// Fonctions pour les actions sur les documents (compatibilité)
window.viewDocument = function(documentId) {
    AdminProjectsPagination.showNotification(`Affichage du document ${documentId}`, 'info');
    // TODO: Implement document view logic
};

window.downloadDocument = function(documentId) {
    AdminProjectsPagination.showNotification(`Téléchargement du document ${documentId}`, 'info');
    // TODO: Implement document download logic
};

window.approveDocument = function(documentId) {
    if (confirm('Êtes-vous sûr de vouloir approuver ce document ?')) {
        AdminProjectsPagination.showNotification(`Document ${documentId} approuvé`, 'success');
        // TODO: Implement document approval logic
    }
};

window.deleteDocument = function(documentId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
        AdminProjectsPagination.showNotification(`Document ${documentId} supprimé`, 'success');
        // TODO: Implement document deletion logic
    }
};

// Fonctions pour les actions sur les project_images (TP)
window.viewProjectImage = function(imageId) {
    AdminProjectsPagination.showNotification(`Affichage du TP ${imageId}`, 'info');
    // TODO: Implement project image view logic
};

window.downloadProjectImage = function(imageId) {
    AdminProjectsPagination.showNotification(`Téléchargement du TP ${imageId}`, 'info');
    // TODO: Implement project image download logic
};

window.approveProjectImage = function(imageId) {
    if (confirm('Êtes-vous sûr de vouloir approuver ce TP ?')) {
        AdminProjectsPagination.showNotification(`TP ${imageId} approuvé`, 'success');
        // TODO: Implement project image approval logic
    }
};

window.deleteProjectImage = function(imageId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce TP ?')) {
        AdminProjectsPagination.showNotification(`TP ${imageId} supprimé`, 'success');
        // TODO: Implement project image deletion logic
    }
};

// Fonction globale pour charger la page des documents (exposée à window)
window.loadDocumentsPage = function(page, pageParam, tableId, studentId) {
    AdminProjectsPagination.loadDocumentsPage(page, pageParam, tableId, studentId);
};
