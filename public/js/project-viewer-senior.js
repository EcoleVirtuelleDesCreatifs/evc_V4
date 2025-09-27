/**
 * Module Senior pour la Visualisation des Projets
 * Approche développeur senior avec architecture modulaire et gestion d'erreurs robuste
 * 
 * @author Développeur Senior EVC
 * @version 2.0
 */

class ProjectViewer {
    constructor() {
        this.apiBaseUrl = '/evc/app/admin/api';
        this.currentProjectId = null;
        this.modalElement = null;
        this.contentElement = null;
        this.isLoading = false;
        
        this.init();
    }

    /**
     * Initialisation du module
     */
    init() {
        console.log('🚀 ProjectViewer Senior Module - Initialisation');
        
        // Configuration AJAX globale
        this.setupAjaxConfig();
        
        // Création de la modal si elle n'existe pas
        this.ensureModalExists();
        
        // Configuration des événements
        this.setupEventListeners();
        
        // Exposition de la fonction globale
        window.viewProject = (projectId) => this.viewProject(projectId);
        
        console.log('✅ ProjectViewer Senior Module - Prêt');
    }

    /**
     * Configuration AJAX avec gestion d'erreurs centralisée
     */
    setupAjaxConfig() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            timeout: 30000, // 30 secondes
            beforeSend: (xhr, settings) => {
                console.log(`🌐 AJAX Request: ${settings.type} ${settings.url}`);
            }
        });

        // Gestionnaire d'erreurs global
        $(document).ajaxError((event, xhr, settings, thrownError) => {
            console.error('🔥 AJAX Error Global:', {
                url: settings.url,
                status: xhr.status,
                error: thrownError,
                response: xhr.responseText
            });
        });
    }

    /**
     * S'assurer que la modal existe dans le DOM
     */
    ensureModalExists() {
        this.modalElement = document.getElementById('projectViewerModal');
        
        if (!this.modalElement) {
            console.log('📦 Création de la modal ProjectViewer');
            this.createModal();
        }
        
        this.contentElement = document.getElementById('projectViewerContent');
    }

    /**
     * Créer la modal Bootstrap dynamiquement
     */
    createModal() {
        const modalHtml = `
            <div class="modal fade" id="projectViewerModal" tabindex="-1" aria-labelledby="projectViewerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content bg-dark text-white">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title" id="projectViewerModalLabel">
                                <i class="fas fa-eye me-2 text-primary"></i>
                                <span id="projectViewerTitle">Détails du Projet</span>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="projectViewerContent">
                                <!-- Contenu chargé dynamiquement -->
                            </div>
                        </div>
                        <div class="modal-footer border-secondary">
                            <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">
                                <i class="fas fa-times me-2"></i>Fermer
                            </button>
                            <button type="button" class="btn btn-primary" id="projectViewerRefresh">
                                <i class="fas fa-sync-alt me-2"></i>Actualiser
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.modalElement = document.getElementById('projectViewerModal');
        this.contentElement = document.getElementById('projectViewerContent');
    }

    /**
     * Configuration des événements
     */
    setupEventListeners() {
        // Bouton actualiser
        $(document).on('click', '#projectViewerRefresh', () => {
            if (this.currentProjectId) {
                this.viewProject(this.currentProjectId);
            }
        });

        // Fermeture de modal
        $(this.modalElement).on('hidden.bs.modal', () => {
            this.currentProjectId = null;
            this.isLoading = false;
        });
    }

    /**
     * Afficher les détails d'un projet
     * 
     * @param {number} projectId 
     */
    async viewProject(projectId) {
        try {
            console.log(`🔍 ViewProject appelée - ID: ${projectId}`);
            
            if (this.isLoading) {
                console.log('⏳ Requête déjà en cours, ignorée');
                return;
            }

            this.currentProjectId = projectId;
            this.isLoading = true;

            // Afficher la modal avec loading
            this.showModal();
            this.showLoading();

            // Appel API
            const response = await this.fetchProjectData(projectId);
            
            if (response.success) {
                this.displayProjectDetails(response.data);
                this.showSuccessNotification('Projet chargé avec succès');
            } else {
                this.showError(response.error || 'Erreur lors du chargement', response.code);
            }

        } catch (error) {
            console.error('🔥 Erreur dans viewProject:', error);
            this.showError('Erreur inattendue lors du chargement');
        } finally {
            this.isLoading = false;
        }
    }

    /**
     * Récupérer les données du projet via API
     * 
     * @param {number} projectId 
     * @returns {Promise<Object>}
     */
    async fetchProjectData(projectId) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${this.apiBaseUrl}/projects/${projectId}`,
                method: 'GET',
                success: (response) => {
                    console.log('✅ API Response:', response);
                    resolve(response);
                },
                error: (xhr, status, error) => {
                    console.error('🔥 API Error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error
                    });

                    let errorResponse = {
                        success: false,
                        error: 'Erreur de communication avec le serveur',
                        code: 'NETWORK_ERROR'
                    };

                    // Traitement des erreurs spécifiques
                    if (xhr.status === 401) {
                        errorResponse.error = 'Session expirée';
                        errorResponse.code = 'UNAUTHORIZED';
                        errorResponse.redirect = '/evc/app/admin/login';
                    } else if (xhr.status === 404) {
                        errorResponse.error = 'Projet non trouvé';
                        errorResponse.code = 'NOT_FOUND';
                    } else if (xhr.responseJSON) {
                        errorResponse = xhr.responseJSON;
                    }

                    resolve(errorResponse);
                }
            });
        });
    }

    /**
     * Afficher la modal
     */
    showModal() {
        const modal = new bootstrap.Modal(this.modalElement);
        modal.show();
    }

    /**
     * Afficher l'état de chargement
     */
    showLoading() {
        const loadingHtml = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <h5 class="text-primary">Chargement des détails du projet...</h5>
                <p class="text-muted">Veuillez patienter pendant que nous récupérons les informations.</p>
            </div>
        `;
        
        this.contentElement.innerHTML = loadingHtml;
        document.getElementById('projectViewerTitle').textContent = 'Chargement...';
    }

    /**
     * Afficher les détails du projet
     * 
     * @param {Object} project 
     */
    displayProjectDetails(project) {
        console.log('📋 Affichage des détails du projet:', project);

        // Mise à jour du titre
        document.getElementById('projectViewerTitle').textContent = project.title;

        // Génération du HTML
        const html = this.generateProjectHtml(project);
        this.contentElement.innerHTML = html;

        // Initialisation des composants Bootstrap
        this.initializeComponents();
    }

    /**
     * Générer le HTML des détails du projet
     * 
     * @param {Object} project 
     * @returns {string}
     */
    generateProjectHtml(project) {
        const softwareHtml = project.software_used.length > 0 
            ? project.software_used.map(software => 
                `<span class="badge bg-secondary me-1 mb-1">${software}</span>`
              ).join('')
            : '<span class="text-muted">Aucun logiciel spécifié</span>';

        const imagesHtml = project.images.length > 0
            ? this.generateImagesHtml(project.images)
            : '<p class="text-muted">Aucun fichier associé</p>';

        return `
            <div class="row">
                <div class="col-lg-8">
                    <div class="card bg-secondary border-0 mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informations générales
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Titre:</strong> ${project.title}</p>
                                    <p><strong>Catégorie:</strong> ${project.category || 'Non spécifiée'}</p>
                                    <p><strong>Statut:</strong> 
                                        <span class="badge bg-${project.status_color}">${project.status_label}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Créé le:</strong> ${project.created_at}</p>
                                    <p><strong>Modifié le:</strong> ${project.updated_at}</p>
                                    <p><strong>Fichiers:</strong> ${project.stats.images_count}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-secondary border-0 mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-align-left me-2"></i>Description
                            </h5>
                            <p>${project.description || 'Aucune description disponible'}</p>
                        </div>
                    </div>

                    <div class="card bg-secondary border-0 mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-tools me-2"></i>Logiciels utilisés
                            </h5>
                            <div>${softwareHtml}</div>
                        </div>
                    </div>

                    <div class="card bg-secondary border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-images me-2"></i>Fichiers associés
                            </h5>
                            ${imagesHtml}
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card bg-secondary border-0 mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-user me-2"></i>Étudiant
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white me-3">
                                    ${project.user.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <h6 class="mb-1">${project.user.name}</h6>
                                    <small class="text-muted">${project.user.email}</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-secondary border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-chart-bar me-2"></i>Statistiques
                            </h5>
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="bg-primary rounded p-3">
                                        <h4 class="mb-0">${project.stats.images_count}</h4>
                                        <small>Fichiers</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-info rounded p-3">
                                        <h6 class="mb-0">${project.stats.total_size}</h6>
                                        <small>Taille totale</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    /**
     * Générer le HTML des images
     * 
     * @param {Array} images 
     * @returns {string}
     */
    generateImagesHtml(images) {
        return `
            <div class="row">
                ${images.map(image => `
                    <div class="col-md-6 col-lg-4 mb-3">
                        <div class="card bg-dark border-secondary">
                            <div class="card-body p-3">
                                <h6 class="card-title text-truncate">${image.original_name}</h6>
                                <div class="small text-muted">
                                    <div><i class="fas fa-file me-1"></i>${image.file_size}</div>
                                    <div><i class="fas fa-calendar me-1"></i>${image.created_at}</div>
                                    ${image.is_thumbnail ? '<div><i class="fas fa-star me-1 text-warning"></i>Miniature</div>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    /**
     * Afficher une erreur
     * 
     * @param {string} message 
     * @param {string} code 
     */
    showError(message, code = null) {
        console.error('🔥 Affichage erreur:', message, code);

        const errorHtml = `
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-danger mb-3">Erreur</h4>
                <p class="lead">${message}</p>
                ${code ? `<small class="text-muted">Code: ${code}</small>` : ''}
                <div class="mt-4">
                    <button type="button" class="btn btn-outline-primary" onclick="projectViewer.viewProject(${this.currentProjectId})">
                        <i class="fas fa-redo me-2"></i>Réessayer
                    </button>
                </div>
            </div>
        `;

        this.contentElement.innerHTML = errorHtml;
        document.getElementById('projectViewerTitle').textContent = 'Erreur';

        // Gestion de la redirection pour session expirée
        if (code === 'UNAUTHORIZED') {
            setTimeout(() => {
                window.location.href = '/evc/app/admin/login';
            }, 3000);
        }
    }

    /**
     * Initialiser les composants Bootstrap
     */
    initializeComponents() {
        // Initialisation des tooltips
        const tooltipTriggerList = [].slice.call(this.contentElement.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    /**
     * Afficher une notification de succès
     * 
     * @param {string} message 
     */
    showSuccessNotification(message) {
        console.log('✅', message);
        // Ici vous pouvez ajouter un système de toast si nécessaire
    }
}

// Initialisation automatique
let projectViewer;

document.addEventListener('DOMContentLoaded', function() {
    projectViewer = new ProjectViewer();
});

// Style CSS pour les composants
const style = document.createElement('style');
style.textContent = `
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    #projectViewerModal .modal-dialog {
        max-width: 1200px;
    }
    
    .card {
        transition: transform 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
    }
`;
document.head.appendChild(style);
