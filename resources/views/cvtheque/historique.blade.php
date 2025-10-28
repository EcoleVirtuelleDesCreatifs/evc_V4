@extends('layouts.ki-admin')

@section('title', 'Historique des documents - CVThèque')
@section('page-title', 'Historique des documents')

@php
    // Détecter automatiquement la formation de l'utilisateur pour les routes
    $routePrefix = session('user_formation_raw', 'design-graphique');
    
    if (!$routePrefix || $routePrefix === 'design-graphique') {
        $formation = session('user_formation', 'Design Graphique');
        $routePrefix = match(strtolower($formation)) {
            'community management' => 'community-management',
            'gestion informatique' => 'gestion-informatique',
            'intelligence artificielle' => 'intelligence-artificielle',
            default => 'design-graphique'
        };
    }
@endphp

@section('content')
<!-- Header avec navigation -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #833AB4, #C13584, #E1306C); color: white; box-shadow: 0 20px 60px rgba(131, 58, 180, 0.3); border-radius: 20px;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-2">
                            <i class="fas fa-history me-2"></i>
                            Historique de vos documents
                        </h3>
                        <p class="mb-0">Suivez le statut de validation de tous vos documents</p>
                    </div>
                    <div>
                        <a href="{{ route($routePrefix . '.cvtheque.index') }}" class="btn btn-light me-2" style="border-radius: 30px;">
                            <i class="fas fa-arrow-left me-1"></i>
                            Retour CVThèque
                        </a>
                        <button class="btn" onclick="exportHistory()" style="background: linear-gradient(135deg, #C13584, #E1306C); color: white; border: none; border-radius: 30px;">
                            <i class="fas fa-download me-1"></i>
                            Exporter
                        </button>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Liste des documents ({{ $documentStats['total_documents'] ?? 0 }})</h5>
                    <div>
                        <a href="{{ route($routePrefix . '.cvtheque.documents.export') }}" class="btn btn-outline-light btn-sm" style="border-radius: 20px;">
                            <i class="fas fa-download"></i> Exporter CSV
                        </a>
                        <button class="btn btn-outline-light btn-sm" onclick="location.reload()" style="border-radius: 20px;">
                            <i class="fas fa-sync-alt"></i> Actualiser
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="testFunction()" style="border-radius: 20px;">
                            <i class="fas fa-bug"></i> Test JS
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques rapides -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #833AB4, #C13584); border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(131, 58, 180, 0.3);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $documentStats['total_documents'] ?? 0 }}</h4>
                        <p class="mb-0">Documents</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #C13584, #E1306C); border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(193, 53, 132, 0.3);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ ($profile->profile_completion_score ?? 0) }}%</h4>
                        <p class="mb-0">Profil complété</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #F56040, #FCAF45); border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(245, 96, 64, 0.3);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $documentStats['recent_uploads'] ?? 0 }}</h4>
                        <p class="mb-0">Cette semaine</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-week fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white" style="background: linear-gradient(135deg, #E1306C, #F56040); border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(225, 48, 108, 0.3);">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ count($documentStats['types_count'] ?? []) }}</h4>
                        <p class="mb-0">Types différents</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-layer-group fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <label class="form-label small">Filtrer par statut</label>
                        <select class="form-select form-select-sm" id="statusFilter" onchange="filterDocuments()">
                            <option value="">Tous les statuts</option>
                            <option value="valide">Validé</option>
                            <option value="en-cours">En cours de validation</option>
                            <option value="rejete">Rejeté</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Filtrer par type</label>
                        <select class="form-select form-select-sm" id="typeFilter" onchange="filterDocuments()">
                            <option value="">Tous les types</option>
                            <option value="cv">CV</option>
                            <option value="motivation">Lettre de motivation</option>
                            <option value="realisation">Réalisation</option>
                            <option value="pressbook">Pressbook</option>
                            <option value="rapport">Rapport</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Période</label>
                        <select class="form-select form-select-sm" id="periodFilter" onchange="filterDocuments()">
                            <option value="">Toute période</option>
                            <option value="7">7 derniers jours</option>
                            <option value="30">30 derniers jours</option>
                            <option value="90">3 derniers mois</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Recherche</label>
                        <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Nom du fichier..." onkeyup="filterDocuments()">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste des documents avec architecture Laravel structurée -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Liste des documents
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="documentsTable">
                        <thead class="table-light">
                            <tr>
                                <th>Document</th>
                                <th>Type</th>
                                <th>Date d'ajout</th>
                                <th>Statut fichier</th>
                                <th>Validation</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($documentsHistory ?? [] as $document)
                            <tr data-type="{{ strtolower($document['type'] ?? 'document') }}" data-date="{{ $document['uploaded_at'] ?? now() }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $document['icon'] ?? 'fas fa-file' }} {{ $document['color'] ?? 'text-secondary' }} me-2"></i>
                                        <div>
                                            <div class="fw-bold">{{ $document['name'] ?? 'Fichier' }}</div>
                                            <small class="text-muted">{{ $document['type'] ?? 'Document' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $document['badge'] ?? 'bg-secondary' }}">
                                        {{ $document['type'] ?? 'Document' }}
                                    </span>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($document['uploaded_at'] ?? now())->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if(($document['exists'] ?? false))
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Disponible
                                        </span>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Manquant
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $validationBadge = $document['validation_badge'] ?? [
                                            'text' => 'En cours d\'analyse',
                                            'class' => 'bg-warning text-dark',
                                            'icon' => 'fas fa-clock'
                                        ];
                                    @endphp
                                    <div class="d-flex flex-column">
                                        <span class="badge {{ $validationBadge['class'] }} mb-1">
                                            <i class="{{ $validationBadge['icon'] }} me-1"></i>
                                            {{ $validationBadge['text'] }}
                                        </span>
                                        @if(isset($document['validation_comment']) && $document['validation_comment'])
                                            <small class="text-muted" title="{{ $document['validation_comment'] }}">
                                                <i class="fas fa-comment me-1"></i>
                                                Commentaire
                                            </small>
                                        @endif
                                        @if(isset($document['validated_at']) && $document['validated_at'])
                                            <small class="text-muted">
                                                {{ $document['validated_at'] }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if(isset($document['download_url']) && $document['download_url'])
                                            <a href="{{ $document['download_url'] }}" target="_blank" class="btn btn-outline-primary" title="Télécharger">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @endif
                                        @if(isset($document['validation_comment']) && $document['validation_comment'])
                                            <button class="btn btn-outline-info" onclick="showValidationComment('{{ addslashes($document['validation_comment']) }}')" title="Voir le commentaire">
                                                <i class="fas fa-comment"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-outline-danger" onclick="deleteDocument('{{ $document['type'] ?? 'document' }}', '{{ $document['name'] ?? 'fichier' }}')" title="Supprimer le document">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <h5>Aucun document uploadé</h5>
                                        <p>Commencez par ajouter vos documents dans votre profil CVThèque.</p>
                                        <a href="{{ route('design-graphique.cvtheque.index') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>
                                            Ajouter des documents
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les commentaires de validation -->
<div class="modal fade" id="validationCommentModal" tabindex="-1" aria-labelledby="validationCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="validationCommentModalLabel">
                    <i class="fas fa-comment me-2"></i>
                    Commentaire de validation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-comment-alt fa-3x text-info mb-3"></i>
                    <div id="validationCommentContent" class="text-start">
                        <!-- Le commentaire sera inséré ici -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmer la suppression
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                    <h6>Êtes-vous sûr de vouloir supprimer ce document ?</h6>
                    <p class="text-muted mb-3">
                        <strong id="documentToDelete"></strong><br>
                        <small>Cette action est irréversible.</small>
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Annuler
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash me-1"></i>
                    <span id="deleteButtonText">Supprimer</span>
                    <span id="deleteSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #003366;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 51, 102, 0.05);
}

.badge {
    font-size: 0.75rem;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
}

.timeline-item {
    position: relative;
    padding-left: 2rem;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.5rem;
    width: 8px;
    height: 8px;
    background-color: #003366;
    border-radius: 50%;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }

    .btn-group-sm .btn {
        padding: 0.125rem 0.25rem;
    }

    .badge {
        font-size: 0.7rem;
    }
}
</style>

<script>
function filterDocuments() {
    const statusFilter = document.getElementById('statusFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const periodFilter = document.getElementById('periodFilter').value;
    const searchInput = document.getElementById('searchInput').value.toLowerCase();

    const rows = document.querySelectorAll('#documentsTable tbody tr');

    rows.forEach(row => {
        let show = true;

        // Filtre par statut
        if (statusFilter && row.dataset.status !== statusFilter) {
            show = false;
        }

        // Filtre par type
        if (typeFilter && row.dataset.type !== typeFilter) {
            show = false;
        }

        // Filtre par recherche
        if (searchInput) {
            const filename = row.querySelector('.fw-bold').textContent.toLowerCase();
            if (!filename.includes(searchInput)) {
                show = false;
            }
        }

        // Filtre par période (simulation)
        if (periodFilter) {
            const rowDate = new Date(row.dataset.date);
            const now = new Date();
            const diffDays = Math.ceil((now - rowDate) / (1000 * 60 * 60 * 24));

            if (diffDays > parseInt(periodFilter)) {
                show = false;
            }
        }

        row.style.display = show ? '' : 'none';
    });

    updateStats();
}

function updateStats() {
    const visibleRows = document.querySelectorAll('#documentsTable tbody tr[style=""]');
    const totalVisible = visibleRows.length;

    // Mettre à jour le compteur si nécessaire
    console.log(`${totalVisible} documents affichés`);
}

function exportHistory() {
    // Simulation d'export
    alert('Export de l\'historique en cours...\nLe fichier sera téléchargé dans quelques instants.');

    // Ici, vous pourriez implémenter une vraie logique d'export
    setTimeout(() => {
        console.log('Export terminé');
    }, 2000);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    console.log('Historique des documents chargé');
    updateStats();
});
</script>
@endsection

@section('scripts')
    <script>
        /**
         * Classe JavaScript structurée pour la gestion de l'historique des documents
         * Architecture propre avec gestion d'erreurs et notifications
         */
        class DocumentHistoryManager {
            constructor() {
                this.csrfToken = '{{ csrf_token() }}';
                this.deleteRoute = '{{ route("design-graphique.cvtheque.documents.delete") }}';
                this.exportRoute = '{{ route("design-graphique.cvtheque.documents.export") }}';
                this.init();
            }

            /**
             * Initialisation des événements et configurations
             */
            init() {
                // Configuration des notifications toast
                this.setupToastContainer();

                // Gestion des erreurs globales
                window.addEventListener('unhandledrejection', (event) => {
                    console.error('Erreur non gérée:', event.reason);
                    this.showNotification('Une erreur inattendue s\'est produite', 'error');
                });

        const status = row.dataset.status || '';
        const type = row.dataset.type || '';
        const date = row.dataset.date || '';
        const fileName = row.querySelector('.fw-bold')?.textContent.toLowerCase() || '';

        let show = true;

        // Filter by status
        if (statusFilter && status !== statusFilter) {
            show = false;
        }

        // Filter by type
        if (typeFilter && type !== typeFilter) {
            show = false;
        }

        // Filter by search
        if (searchInput && !fileName.includes(searchInput)) {
            show = false;
        }

        // Filter by period
        if (periodFilter && date) {
            const docDate = new Date(date);
            const now = new Date();
            const daysDiff = (now - docDate) / (1000 * 60 * 60 * 24);

            if (parseInt(periodFilter) < daysDiff) {
                show = false;
            }
        }

        row.style.display = show ? '' : 'none';
    });
}

// Fonction pour exporter l'historique
function exportHistory() {
    // Créer un CSV simple des documents
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Type,Nom du fichier,Date d'ajout,Taille\n";

    @foreach($documentsHistory ?? [] as $document)
    csvContent += "{{ $document['type'] ?? 'Document' }},{{ $document['name'] ?? 'Fichier' }},{{ \Carbon\Carbon::parse($document['uploaded_at'] ?? now())->format('d/m/Y H:i') }},";
    @if(is_numeric($document['size'] ?? 0))
    csvContent += "{{ number_format(($document['size'] ?? 0) / 1024 / 1024, 2) }} MB";
    @else
    csvContent += "{{ $document['size'] ?? 'N/A' }}";
    @endif
    csvContent += "\n";
    @endforeach

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "historique_documents_cvtheque.csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showNotification('success', 'Export terminé avec succès');
}

// Variables globales pour la suppression
let documentToDeleteType = '';
let documentToDeleteName = '';

// Fonction pour supprimer un document
function deleteDocument(documentType, documentName) {
    console.log('=== DELETE DOCUMENT DEBUG ===');
    console.log('deleteDocument called with:', documentType, documentName);
    
    // Vérifier si les éléments existent
    const modalElement = document.getElementById('deleteConfirmModal');
    const documentElement = document.getElementById('documentToDelete');
    
    console.log('Modal element found:', !!modalElement);
    console.log('Document element found:', !!documentElement);
    
    if (!modalElement) {
        console.error('Modal deleteConfirmModal not found');
        alert('Erreur: Modal de confirmation non trouvée');
        return;
    }
    
    if (!documentElement) {
        console.error('Element documentToDelete not found');
        alert('Erreur: Élément de confirmation non trouvé');
        return;
    }
    
    // Stocker les informations du document à supprimer
    documentToDeleteType = documentType;
    documentToDeleteName = documentName;
    
    console.log('Stored document info:', { type: documentToDeleteType, name: documentToDeleteName });
    
    // Mettre à jour le contenu de la modale
    documentElement.textContent = documentName;
    console.log('Updated modal content with document name:', documentName);
    
    try {
        // Vérifier si Bootstrap est disponible
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap is not loaded');
            alert('Erreur: Bootstrap n\'est pas chargé');
            return;
        }
        
        // Afficher la modale de confirmation
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        console.log('Modal displayed successfully');
    } catch (error) {
        console.error('Error showing modal:', error);
        alert('Erreur lors de l\'affichage de la modale: ' + error.message);
    }
}

// Fonction de test pour vérifier que le JavaScript fonctionne
function testFunction() {
    alert('JavaScript fonctionne !');
    console.log('Test function called successfully');
}

// Fonction pour confirmer la suppression
function confirmDelete() {
    console.log('=== CONFIRM DELETE DEBUG ===');
    console.log('confirmDelete called');
    console.log('Document to delete:', { type: documentToDeleteType, name: documentToDeleteName });
    
    const confirmBtn = document.getElementById('confirmDeleteBtn');
    const deleteButtonText = document.getElementById('deleteButtonText');
    const deleteSpinner = document.getElementById('deleteSpinner');
    
    console.log('Button elements found:', {
        confirmBtn: !!confirmBtn,
        deleteButtonText: !!deleteButtonText,
        deleteSpinner: !!deleteSpinner
    });
    
    if (!confirmBtn || !deleteButtonText || !deleteSpinner) {
        console.error('Missing button elements');
        alert('Erreur: Éléments de bouton manquants');
        return;
    }
    
    // Vérifier si jQuery est disponible
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded');
        alert('Erreur: jQuery n\'est pas chargé');
        return;
    }
    
    // Désactiver le bouton et afficher le spinner
    confirmBtn.disabled = true;
    deleteButtonText.textContent = 'Suppression...';
    deleteSpinner.classList.remove('d-none');
    
    console.log('Button state updated, starting AJAX request');
    
    // Effectuer la suppression via AJAX
    $.ajax({
        url: '{{ route("design-graphique.cvtheque.documents.delete") }}',
        type: 'DELETE',
        data: {
            '_token': '{{ csrf_token() }}',
            'document_type': documentToDeleteType,
            'document_name': documentToDeleteName
        },
        success: function(response) {
            // Fermer la modale
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmModal'));
            modal.hide();
            
            // Afficher notification de succès
            showNotification('success', 'Document supprimé avec succès');
            
            // Supprimer la ligne du tableau
            removeDocumentRow(documentToDeleteType, documentToDeleteName);
            
            // Mettre à jour les statistiques
            updateStats();
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors de la suppression:', xhr, status, error);
            
            let errorMessage = 'Erreur lors de la suppression du document';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            showNotification('error', errorMessage);
        },
        complete: function() {
            // Réactiver le bouton
            confirmBtn.disabled = false;
            deleteButtonText.textContent = 'Supprimer';
            deleteSpinner.classList.add('d-none');
        }
    });
}

// Fonction pour supprimer la ligne du tableau
function removeDocumentRow(documentType, documentName) {
    const rows = document.querySelectorAll('#documentsTable tbody tr');
    rows.forEach(row => {
        const nameElement = row.querySelector('.fw-bold');
        const typeElement = row.querySelector('.badge');
        
        if (nameElement && typeElement) {
            const rowName = nameElement.textContent.trim();
            const rowType = typeElement.textContent.trim().toLowerCase();
            
            if (rowName === documentName && rowType === documentType.toLowerCase()) {
                row.remove();
            }
        }
    });
}

// Fonction pour afficher les notifications
function showNotification(type, message) {
    // Supprimer les notifications existantes
    $('.alert-notification').remove();

    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const icon = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';

    const notification = `
        <div class="alert ${alertClass} alert-dismissible fade show alert-notification position-fixed"
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    $('body').append(notification);

    // Auto-hide après 5 secondes
    setTimeout(() => {
        $('.alert-notification').fadeOut();
    }, 5000);
}

// Initialisation
$(document).ready(function() {
    console.log('Document ready - jQuery loaded');
    
    // Vérifier si Bootstrap est disponible
    if (typeof bootstrap !== 'undefined') {
        console.log('Bootstrap is loaded');
    } else {
        console.error('Bootstrap is NOT loaded');
        alert('Erreur: Bootstrap n\'est pas chargé');
    }
    
    // Vérifier si jQuery est disponible
    // Fonction pour tester les événements
    function testEvents() {
        console.log('Test des événements - fonction appelée');
        showNotification('Test réussi !', 'success');
    }

    // Fonction pour afficher les commentaires de validation
    function showValidationComment(comment) {
        console.log('Affichage du commentaire de validation:', comment);
        
        // Nettoyer et formater le commentaire
        const formattedComment = comment.replace(/\n/g, '<br>');
        
        // Insérer le commentaire dans la modal
        document.getElementById('validationCommentContent').innerHTML = `
            <div class="alert alert-info">
                <h6><i class="fas fa-info-circle me-2"></i>Commentaire de l'administrateur :</h6>
                <p class="mb-0">${formattedComment}</p>
            </div>
        `;
        
        // Afficher la modal
        const modal = new bootstrap.Modal(document.getElementById('validationCommentModal'));
        modal.show();
    }
    
    // Vérifier si la modale existe
    const modalElement = document.getElementById('deleteConfirmModal');
    if (modalElement) {
        console.log('Modal element found');
    } else {
        console.error('Modal element NOT found');
    }
    
    // Auto-focus sur le champ de recherche
    $('#searchInput').focus();

    // Ajouter des tooltips Bootstrap
    $('[title]').tooltip();
    
    // Événement pour le bouton de confirmation de suppression
    console.log('=== EVENT BINDING DEBUG ===');
    const confirmDeleteBtnElement = document.getElementById('confirmDeleteBtn');
    console.log('confirmDeleteBtn element found:', !!confirmDeleteBtnElement);
    
    if (confirmDeleteBtnElement) {
        // Utiliser addEventListener au lieu de jQuery pour plus de fiabilité
        confirmDeleteBtnElement.addEventListener('click', function(e) {
            console.log('Delete confirmation button clicked!');
            e.preventDefault();
            confirmDelete();
        });
        
        // Aussi garder jQuery pour compatibilité
        $('#confirmDeleteBtn').off('click').on('click', function(e) {
            console.log('jQuery delete confirmation button clicked!');
            e.preventDefault();
            confirmDelete();
        });
        
        console.log('Delete confirmation event bound successfully');
    } else {
        console.error('confirmDeleteBtn element not found during initialization');
    }
    
    console.log('Initialization complete');
});
</script>
@endsection
