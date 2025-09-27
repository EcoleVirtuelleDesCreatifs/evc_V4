<!-- Modal Voir Projet -->
<div class="modal fade" id="viewProjectModal" tabindex="-1" aria-labelledby="viewProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="viewProjectModalLabel">
                    <i class="fas fa-eye me-2"></i>Détails du Projet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="viewProjectContent">
                    <!-- Contenu chargé dynamiquement -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modifier Projet -->
<div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="editProjectModalLabel">
                    <i class="fas fa-edit me-2"></i>Modifier le Projet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProjectForm">
                <div class="modal-body">
                    <div id="editProjectErrors" class="alert alert-danger d-none"></div>
                    
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="editProjectTitle" class="form-label">Titre du projet *</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="editProjectTitle" name="title" required>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="editProjectDescription" class="form-label">Description</label>
                            <textarea class="form-control bg-dark text-white border-secondary" 
                                      id="editProjectDescription" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="editProjectSoftware" class="form-label">Logiciels utilisés</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" 
                                   id="editProjectSoftware" name="software_used" 
                                   placeholder="Ex: photoshop, illustrator">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="editProjectStatus" class="form-label">Statut *</label>
                            <select class="form-select bg-dark text-white border-secondary" 
                                    id="editProjectStatus" name="status" required>
                                <option value="en_cours">En cours</option>
                                <option value="termine">Terminé</option>
                                <option value="valide">Validé</option>
                                <option value="rejete">Rejeté</option>
                            </select>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="editProjectLink" class="form-label">Lien externe</label>
                            <input type="url" class="form-control bg-dark text-white border-secondary" 
                                   id="editProjectLink" name="link" 
                                   placeholder="https://exemple.com">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="saveProjectBtn">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmation Validation -->
<div class="modal fade" id="validateProjectModal" tabindex="-1" aria-labelledby="validateProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="validateProjectModalLabel">
                    <i class="fas fa-check-circle text-success me-2"></i>Valider le Projet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir valider ce projet ?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Cette action changera le statut du projet à "Validé".
                </div>
                <div id="validateProjectInfo" class="bg-secondary p-3 rounded">
                    <!-- Informations du projet -->
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-success" id="confirmValidateBtn">
                    <i class="fas fa-check me-2"></i>Valider
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmation Suppression -->
<div class="modal fade" id="deleteProjectModal" tabindex="-1" aria-labelledby="deleteProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-secondary">
                <h5 class="modal-title" id="deleteProjectModalLabel">
                    <i class="fas fa-trash text-danger me-2"></i>Supprimer le Projet
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer ce projet ?</p>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Attention :</strong> Cette action est irréversible et supprimera également tous les fichiers associés.
                </div>
                <div id="deleteProjectInfo" class="bg-secondary p-3 rounded">
                    <!-- Informations du projet -->
                </div>
            </div>
            <div class="modal-footer border-secondary">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
