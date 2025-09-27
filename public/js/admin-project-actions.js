/**
 * Gestion des actions CRUD pour les projets (Travaux Pratiques Images/Print)
 * Approche développeur senior avec gestion d'erreurs robuste et UX fluide
 */

// Variables globales pour la gestion des projets
let currentProjectId = null;
let currentProjectData = null;

// Configuration CSRF pour les requêtes AJAX
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        'X-Requested-With': 'XMLHttpRequest'
    }
});

/**
 * Voir les détails d'un projet
 */
function viewProject(projectId) {
    console.log('viewProject appelée avec ID:', projectId);
    currentProjectId = projectId;
    
    // Vérifier que la modal existe
    const modal = document.getElementById('viewProjectModal');
    if (!modal) {
        console.error('Modal viewProjectModal non trouvée dans le DOM');
        showErrorToast('Erreur: Modal non trouvée');
        return;
    }
    
    // Afficher la modal avec loading
    $('#viewProjectModal').modal('show');
    $('#viewProjectContent').html(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="mt-2">Chargement des détails du projet...</p>
        </div>
    `);
    
    console.log('Envoi de la requête AJAX vers:', `/evc/app/admin/projects/view/${projectId}`);
    
    // Requête AJAX pour récupérer les détails
    $.ajax({
        url: `/evc/app/admin/projects/view/${projectId}`,
        method: 'GET',
        beforeSend: function() {
            console.log('Requête AJAX envoyée...');
        },
        success: function(response) {
            console.log('Réponse reçue:', response);
            if (response.success) {
                displayProjectDetails(response.project);
                showSuccessToast('Détails du projet chargés avec succès');
            } else {
                console.error('Erreur dans la réponse:', response);
                showProjectError('Erreur lors du chargement des détails');
            }
        },
        error: function(xhr, status, error) {
            console.error('Erreur AJAX complète:', {
                xhr: xhr,
                status: status,
                error: error,
                responseText: xhr.responseText
            });
            
            if (xhr.status === 401) {
                showErrorToast('Session expirée, redirection...');
                setTimeout(() => {
                    window.location.href = '/evc/app/admin/login';
                }, 2000);
                return;
            }
            
            let errorMessage = 'Erreur lors du chargement du projet';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                try {
                    const errorData = JSON.parse(xhr.responseText);
                    errorMessage = errorData.message || errorMessage;
                } catch (e) {
                    console.error('Impossible de parser la réponse d\'erreur:', xhr.responseText);
                }
            }
            
            showProjectError(errorMessage);
            showErrorToast(errorMessage);
        }
    });
}

/**
 * Afficher les détails du projet dans la modal
 */
function displayProjectDetails(project) {
    const softwareList = project.software_used ? 
        project.software_used.split(',').map(s => `<span class="badge bg-secondary me-1">${s.trim()}</span>`).join('') :
        '<span class="text-muted">Non spécifié</span>';
    
    const imagesHtml = project.images.length > 0 ? 
        project.images.map(image => `
            <div class="col-md-4 mb-3">
                <div class="card bg-secondary">
                    <div class="card-body p-2">
                        <h6 class="card-title text-truncate">${image.original_name}</h6>
                        <small class="text-muted">
                            ${image.file_size} • ${image.created_at}
                        </small>
                        <br>
                        <a href="${image.file_path}" target="_blank" class="btn btn-sm btn-outline-light mt-1">
                            <i class="fas fa-external-link-alt"></i> Voir
                        </a>
                    </div>
                </div>
            </div>
        `).join('') :
        '<div class="col-12"><p class="text-muted">Aucun fichier associé</p></div>';
    
    const content = `
        <div class="row">
            <div class="col-md-8">
                <h4 class="text-primary">${project.title}</h4>
                <p class="text-muted mb-3">${project.description || 'Aucune description'}</p>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Statut:</strong>
                        <span class="badge bg-${getStatusColor(project.status)} ms-2">
                            ${project.status_label}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Créé le:</strong> ${project.created_at}
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>Logiciels utilisés:</strong><br>
                    ${softwareList}
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card bg-secondary">
                    <div class="card-header">
                        <h6 class="mb-0">Étudiant</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>${project.user.name}</strong></p>
                        <p class="mb-0 text-muted">${project.user.email}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <hr class="border-secondary">
        
        <h5>Fichiers associés (${project.images.length})</h5>
        <div class="row">
            ${imagesHtml}
        </div>
    `;
    
    $('#viewProjectContent').html(content);
}

/**
 * Modifier un projet
 */
function editProject(projectId) {
    currentProjectId = projectId;
    
    // Afficher la modal d'édition
    $('#editProjectModal').modal('show');
    
    // Réinitialiser le formulaire
    $('#editProjectForm')[0].reset();
    $('#editProjectErrors').addClass('d-none');
    
    // Charger les données du projet
    $.ajax({
        url: `/evc/app/admin/projects/edit/${projectId}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                populateEditForm(response.project);
            } else {
                showEditError('Erreur lors du chargement des données');
            }
        },
        error: function(xhr) {
            console.error('Erreur AJAX:', xhr);
            if (xhr.status === 401) {
                window.location.href = '/evc/app/admin/login';
                return;
            }
            showEditError('Erreur lors du chargement des données du projet');
        }
    });
}

/**
 * Remplir le formulaire d'édition
 */
function populateEditForm(project) {
    $('#editProjectTitle').val(project.title);
    $('#editProjectDescription').val(project.description);
    $('#editProjectSoftware').val(project.software_used);
    $('#editProjectStatus').val(project.status);
    $('#editProjectLink').val(project.link);
    
    currentProjectData = project;
}

/**
 * Sauvegarder les modifications du projet
 */
$('#editProjectForm').on('submit', function(e) {
    e.preventDefault();
    
    if (!currentProjectId) return;
    
    const formData = {
        title: $('#editProjectTitle').val(),
        description: $('#editProjectDescription').val(),
        software_used: $('#editProjectSoftware').val(),
        status: $('#editProjectStatus').val(),
        link: $('#editProjectLink').val()
    };
    
    // Désactiver le bouton de sauvegarde
    const saveBtn = $('#saveProjectBtn');
    const originalText = saveBtn.html();
    saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement...');
    
    $.ajax({
        url: `/evc/app/admin/projects/update/${currentProjectId}`,
        method: 'PUT',
        data: formData,
        success: function(response) {
            if (response.success) {
                $('#editProjectModal').modal('hide');
                showSuccessToast(response.message);
                
                // Mettre à jour l'affichage dans le tableau
                updateProjectInTable(currentProjectId, response.project);
                
                // Recharger la pagination si nécessaire
                if (typeof refreshCurrentPage === 'function') {
                    refreshCurrentPage();
                }
            } else {
                showEditError(response.error || 'Erreur lors de la mise à jour');
            }
        },
        error: function(xhr) {
            console.error('Erreur AJAX:', xhr);
            
            if (xhr.status === 401) {
                window.location.href = '/evc/app/admin/login';
                return;
            }
            
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                displayValidationErrors(xhr.responseJSON.errors);
            } else {
                showEditError('Erreur lors de la mise à jour du projet');
            }
        },
        complete: function() {
            // Réactiver le bouton
            saveBtn.prop('disabled', false).html(originalText);
        }
    });
});

/**
 * Valider un projet
 */
function validateProject(projectId) {
    currentProjectId = projectId;
    
    // Récupérer les infos du projet depuis le tableau
    const projectRow = $(`#project-row-${projectId}`);
    const projectTitle = projectRow.find('.fw-bold').text().trim();
    
    $('#validateProjectInfo').html(`
        <strong>Projet:</strong> ${projectTitle}<br>
        <strong>ID:</strong> ${projectId}
    `);
    
    $('#validateProjectModal').modal('show');
}

/**
 * Confirmer la validation du projet
 */
$('#confirmValidateBtn').on('click', function() {
    if (!currentProjectId) return;
    
    const btn = $(this);
    const originalText = btn.html();
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Validation...');
    
    $.ajax({
        url: `/evc/app/admin/projects/validate/${currentProjectId}`,
        method: 'POST',
        success: function(response) {
            if (response.success) {
                $('#validateProjectModal').modal('hide');
                showSuccessToast(response.message);
                
                // Mettre à jour le statut dans le tableau
                updateProjectStatus(currentProjectId, 'valide', 'Validé');
                
                // Recharger la pagination si nécessaire
                if (typeof refreshCurrentPage === 'function') {
                    refreshCurrentPage();
                }
            } else {
                showErrorToast(response.error || 'Erreur lors de la validation');
            }
        },
        error: function(xhr) {
            console.error('Erreur AJAX:', xhr);
            
            if (xhr.status === 401) {
                window.location.href = '/evc/app/admin/login';
                return;
            }
            
            showErrorToast('Erreur lors de la validation du projet');
        },
        complete: function() {
            btn.prop('disabled', false).html(originalText);
        }
    });
});

/**
 * Supprimer un projet
 */
function deleteProject(projectId) {
    currentProjectId = projectId;
    
    // Récupérer les infos du projet depuis le tableau
    const projectRow = $(`#project-row-${projectId}`);
    const projectTitle = projectRow.find('.fw-bold').text().trim();
    
    $('#deleteProjectInfo').html(`
        <strong>Projet:</strong> ${projectTitle}<br>
        <strong>ID:</strong> ${projectId}
    `);
    
    $('#deleteProjectModal').modal('show');
}

/**
 * Confirmer la suppression du projet
 */
$('#confirmDeleteBtn').on('click', function() {
    if (!currentProjectId) return;
    
    const btn = $(this);
    const originalText = btn.html();
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Suppression...');
    
    $.ajax({
        url: `/evc/app/admin/projects/delete/${currentProjectId}`,
        method: 'DELETE',
        success: function(response) {
            if (response.success) {
                $('#deleteProjectModal').modal('hide');
                showSuccessToast(response.message);
                
                // Supprimer la ligne du tableau avec animation
                const projectRow = $(`#project-row-${currentProjectId}`);
                projectRow.fadeOut(300, function() {
                    $(this).remove();
                    
                    // Recharger la pagination si nécessaire
                    if (typeof refreshCurrentPage === 'function') {
                        refreshCurrentPage();
                    }
                });
            } else {
                showErrorToast(response.error || 'Erreur lors de la suppression');
            }
        },
        error: function(xhr) {
            console.error('Erreur AJAX:', xhr);
            
            if (xhr.status === 401) {
                window.location.href = '/evc/app/admin/login';
                return;
            }
            
            showErrorToast('Erreur lors de la suppression du projet');
        },
        complete: function() {
            btn.prop('disabled', false).html(originalText);
        }
    });
});

/**
 * Fonctions utilitaires
 */

function getStatusColor(status) {
    const colors = {
        'valide': 'success',
        'en_cours': 'warning',
        'termine': 'info',
        'rejete': 'danger'
    };
    return colors[status] || 'secondary';
}

function updateProjectInTable(projectId, projectData) {
    const row = $(`#project-row-${projectId}`);
    if (row.length) {
        // Mettre à jour le titre
        row.find('.fw-bold').text(projectData.title);
        
        // Mettre à jour le statut
        updateProjectStatus(projectId, projectData.status, projectData.status_label);
    }
}

function updateProjectStatus(projectId, status, statusLabel) {
    const statusBadge = $(`#project-row-${projectId}`).find('.badge');
    const statusColor = getStatusColor(status);
    const statusIcon = getStatusIcon(status);
    
    statusBadge
        .removeClass('bg-success bg-warning bg-info bg-danger bg-secondary')
        .addClass(`bg-${statusColor}`)
        .html(`<i class="${statusIcon} me-1"></i>${statusLabel}`);
}

function getStatusIcon(status) {
    const icons = {
        'valide': 'fas fa-check-circle',
        'en_cours': 'fas fa-clock',
        'termine': 'fas fa-flag-checkered',
        'rejete': 'fas fa-times-circle'
    };
    return icons[status] || 'fas fa-info-circle';
}

function showProjectError(message) {
    $('#viewProjectContent').html(`
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>${message}
        </div>
    `);
}

function showEditError(message) {
    $('#editProjectErrors').removeClass('d-none').html(`
        <i class="fas fa-exclamation-triangle me-2"></i>${message}
    `);
}

function displayValidationErrors(errors) {
    let errorHtml = '<ul class="mb-0">';
    for (const field in errors) {
        errors[field].forEach(error => {
            errorHtml += `<li>${error}</li>`;
        });
    }
    errorHtml += '</ul>';
    
    $('#editProjectErrors').removeClass('d-none').html(errorHtml);
}

function showSuccessToast(message) {
    // Utiliser la fonction toast existante ou créer une notification
    if (typeof showToast === 'function') {
        showToast('success', message);
    } else {
        // Créer un toast Bootstrap simple
        createBootstrapToast(message, 'success');
    }
}

function showErrorToast(message) {
    // Utiliser la fonction toast existante ou créer une notification
    if (typeof showToast === 'function') {
        showToast('error', message);
    } else {
        // Créer un toast Bootstrap simple
        createBootstrapToast(message, 'danger');
    }
}

function createBootstrapToast(message, type) {
    // Créer le conteneur de toasts s'il n'existe pas
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    // Créer le toast
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;

    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Initialiser et afficher le toast
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, {
        autohide: true,
        delay: 5000
    });
    
    toast.show();
    
    // Supprimer le toast du DOM après fermeture
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

// Initialisation au chargement de la page
$(document).ready(function() {
    console.log('Admin Project Actions JS loaded successfully');
    
    // Fermer les modales en cas d'erreur de session
    $(document).ajaxError(function(event, xhr, settings) {
        if (xhr.status === 401) {
            $('.modal').modal('hide');
        }
    });
});
