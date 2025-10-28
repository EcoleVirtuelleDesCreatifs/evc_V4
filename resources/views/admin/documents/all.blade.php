@extends('layouts.admin')

@section('title', 'Rapports Étudiants')

@push('styles')
<style>
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
    
    .stat-card-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
        background: #f8fafc;
    }

    .search-input:focus {
        border-color: #4fc3f7;
        box-shadow: 0 0 0 4px rgba(79, 195, 247, 0.1);
        background: white;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
    }

    .filter-btn {
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        background: white;
        color: var(--text-primary);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .filter-btn:hover, .filter-btn.active {
        border-color: #4fc3f7;
        background: #4fc3f7;
        color: white;
    }

    /* Grille de documents */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
    }

    .document-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        border-color: #4fc3f7;
    }

    .document-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .document-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.3rem;
        flex-shrink: 0;
    }

    .document-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }

    .document-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .meta-item i {
        color: #4fc3f7;
    }

    .student-info-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #4fc3f7;
    }

    .student-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .student-formation {
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-validated {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .document-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .action-btn {
        flex: 1;
        padding: 0.75rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-view {
        background: #e0f2fe;
        color: #0369a1;
    }

    .btn-view:hover {
        background: #0369a1;
        color: white;
    }

    .btn-download {
        background: #dbeafe;
        color: #1e40af;
    }

    .btn-download:hover {
        background: #1e40af;
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    }

    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: var(--text-primary);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-secondary);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-file-alt me-2"></i>Rapports Étudiants
        </h1>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['validated'] }}</h3>
                    <p class="stat-label">Validés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['pending'] }}</h3>
                    <p class="stat-label">En attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-danger">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['rejected'] }}</h3>
                    <p class="stat-label">Rejetés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des rapports -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th style="width: 25%;">Étudiant</th>
                            <th style="width: 30%;">Titre</th>
                            <th style="width: 15%;">Statut</th>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 15%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rapports as $index => $rapport)
                            <tr>
                                <!-- Étudiant -->
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @php
                                            $photoUrl = null;
                                            $photoExists = false;
                                            
                                            if ($rapport->user_photo) {
                                                // Essayer différents chemins possibles
                                                $possiblePaths = [
                                                    $rapport->user_photo, // Chemin direct
                                                    'storage/' . $rapport->user_photo,
                                                    'uploads/profile_photos/' . $rapport->user_photo,
                                                    'storage/profile_photos/' . $rapport->user_photo,
                                                    'storage/uploads/profile_photos/' . $rapport->user_photo,
                                                ];
                                                
                                                foreach ($possiblePaths as $path) {
                                                    if (str_starts_with($path, 'http')) {
                                                        $photoUrl = $path;
                                                        $photoExists = true;
                                                        break;
                                                    } elseif (file_exists(public_path($path))) {
                                                        $photoUrl = asset($path);
                                                        $photoExists = true;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if($photoExists && $photoUrl)
                                            <img src="{{ $photoUrl }}" 
                                                 alt="{{ $rapport->user_name }}" 
                                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #4fc3f7;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); display: none; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                                {{ strtoupper(substr($rapport->user_name, 0, 2)) }}
                                            </div>
                                        @else
                                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700;">
                                                {{ strtoupper(substr($rapport->user_name, 0, 2)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-white fw-bold">{{ $rapport->user_name }}</div>
                                            <small class="text-white" style="font-weight: 500; opacity: 0.8;">{{ $rapport->formation ?? $rapport->specialization ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>

                                <!-- Titre -->
                                <td>
                                    <div class="text-white" style="font-size: 0.95rem; line-height: 1.4;">
                                        {{ strip_tags($rapport->title) }}
                                    </div>
                                </td>

                                <!-- Statut -->
                                <td>
                                    @if($rapport->status === 'validated')
                                        <span class="status-badge bg-success">
                                            <i class="fas fa-check-circle"></i> Validé
                                        </span>
                                    @elseif($rapport->status === 'pending')
                                        <span class="status-badge bg-warning text-dark">
                                            <i class="fas fa-clock"></i> En attente
                                        </span>
                                    @elseif($rapport->status === 'rejected')
                                        <span class="status-badge bg-danger">
                                            <i class="fas fa-times-circle"></i> Rejeté
                                        </span>
                                    @else
                                        <span class="status-badge bg-secondary">
                                            <i class="fas fa-question-circle"></i> {{ ucfirst($rapport->status) }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Date -->
                                <td>
                                    <div>
                                        <i class="fas fa-calendar text-white me-1" style="opacity: 0.6;"></i>
                                        <span class="text-white">{{ \Carbon\Carbon::parse($rapport->created_at)->format('d/m/Y') }}</span>
                                        <small class="d-block text-white mt-1" style="opacity: 0.7;">{{ \Carbon\Carbon::parse($rapport->created_at)->format('H:i') }}</small>
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-info" 
                                                title="Voir les détails et fichiers"
                                                onclick='viewReportDetails(@json($rapport))'
                                                style="border-radius: 6px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($rapport->status !== 'validated')
                                            <form action="{{ url('/evc/app/admin/travaux/' . $rapport->id . '/update-status') }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="validated">
                                                <button type="submit" 
                                                        class="btn btn-sm btn-success" 
                                                        title="Valider"
                                                        style="border-radius: 6px;">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ url('/evc/app/admin/travaux/' . $rapport->id . '/delete') }}" 
                                              method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport ?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="redirect_to" value="{{ url()->current() }}">
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger" 
                                                    title="Supprimer"
                                                    style="border-radius: 6px;">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 opacity-50"></i>
                                        <p class="mb-0">Aucun rapport trouvé</p>
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

<script>
// Fonction pour afficher la photo de l'étudiant en grand
function viewPhoto(photoUrl, studentName) {
    // Créer un modal pour afficher la photo
    const modalHtml = `
        <div class="modal fade" id="photoModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                    <div class="modal-header" style="border-bottom: 1px solid #334155;">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-user-circle me-2"></i>Photo de ${studentName}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-4">
                        <img src="${photoUrl}" alt="${studentName}" 
                             style="max-width: 100%; max-height: 500px; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.3);">
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Ajouter le modal au DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Afficher le modal
    const photoModal = new bootstrap.Modal(document.getElementById('photoModal'));
    photoModal.show();
    
    // Nettoyer le modal après fermeture
    document.getElementById('photoModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// Fonction pour supprimer un rapport
function deleteReport(reportId, reportTitle) {
    // Créer un modal de confirmation
    const modalHtml = `
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                    <div class="modal-header text-white" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none;">
                        <h5 class="modal-title" style="font-weight: 700;">
                            <i class="fas fa-exclamation-triangle me-2"></i>Supprimer le rapport
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="text-white mb-3" style="font-size: 1.05rem;">
                            Êtes-vous sûr de vouloir supprimer ce rapport ?
                        </p>
                        <div class="alert alert-warning" style="border-radius: 12px; border: none;">
                            <i class="fas fa-file-alt me-2"></i>
                            <strong>${reportTitle}</strong>
                        </div>
                        <p class="text-danger mb-0" style="font-size: 0.95rem;">
                            <i class="fas fa-info-circle me-2"></i>
                            Cette action est irréversible. Tous les fichiers associés seront supprimés.
                        </p>
                    </div>
                    <div class="modal-footer" style="border: none; padding: 1.5rem;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                            <i class="fas fa-times me-2"></i>Annuler
                        </button>
                        <button type="button" class="btn text-white" onclick="confirmDeleteReport(${reportId})" 
                                style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none; border-radius: 8px; font-weight: 600;">
                            <i class="fas fa-trash me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Ajouter le modal au DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Afficher le modal
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
    
    // Nettoyer le modal après fermeture
    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// Confirmer la suppression du rapport
function confirmDeleteReport(reportId) {
    // Créer un formulaire pour envoyer la requête DELETE
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/evc/app/admin/tp/delete/${reportId}`;
    
    // Token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = csrfToken;
    form.appendChild(csrfInput);
    
    // Méthode DELETE
    const methodInput = document.createElement('input');
    methodInput.type = 'hidden';
    methodInput.name = '_method';
    methodInput.value = 'DELETE';
    form.appendChild(methodInput);
    
    // Ajouter au DOM et soumettre
    document.body.appendChild(form);
    form.submit();
}

// Fonction pour afficher les détails complets du rapport
function viewReportDetails(rapport) {
    // Formater la date
    const date = new Date(rapport.created_at);
    const formattedDate = date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
    const formattedTime = date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    
    // Badge de statut
    let statusBadge = '';
    if (rapport.status === 'validated') {
        statusBadge = '<span class="badge" style="background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%); color: white; padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-check-circle me-1"></i>Validé</span>';
    } else if (rapport.status === 'pending') {
        statusBadge = '<span class="badge" style="background: linear-gradient(135deg, #F56040 0%, #FCAF45 100%); color: white; padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-clock me-1"></i>En attente</span>';
    } else if (rapport.status === 'rejected') {
        statusBadge = '<span class="badge" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-times-circle me-1"></i>Rejeté</span>';
    }
    
    // Liste des fichiers
    let filesHtml = '<p class="text-muted">Aucun fichier</p>';
    if (rapport.files && rapport.files.length > 0) {
        filesHtml = '<div class="list-group">';
        rapport.files.forEach(file => {
            const fileSize = file.file_size ? (file.file_size / 1024).toFixed(2) : 'N/A';
            // Construire le chemin correct du fichier
            let filePath = file.file_path;
            if (!filePath.startsWith('http')) {
                // Si le chemin commence par 'uploads/', utiliser asset() directement
                if (filePath.startsWith('uploads/')) {
                    filePath = '{{ url("") }}/' + filePath;
                } else {
                    // Sinon, ajouter storage/
                    filePath = '{{ asset("storage") }}/' + filePath;
                }
            }
            const fileIcon = file.original_name.toLowerCase().endsWith('.pdf') ? 'fa-file-pdf text-danger' : 'fa-file text-primary';
            filesHtml += `
                <a href="${filePath}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; margin-bottom: 0.5rem; color: white;">
                    <div>
                        <i class="fas ${fileIcon} me-2"></i>
                        <strong>${file.original_name}</strong>
                    </div>
                    <div>
                        <span class="badge" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);">${fileSize} KB</span>
                        <i class="fas fa-download ms-2"></i>
                    </div>
                </a>
            `;
        });
        filesHtml += '</div>';
    }
    
    const modalHtml = `
        <div class="modal fade" id="reportDetailsModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="background: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                    <div class="modal-header" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); border: none;">
                        <h5 class="modal-title text-white" style="font-weight: 700;">
                            <i class="fas fa-file-alt me-2"></i>Détails du Rapport
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4" style="max-height: 70vh; overflow-y: auto;">
                        <!-- Titre et Statut -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h4 class="text-white mb-0">${rapport.title}</h4>
                                ${statusBadge}
                            </div>
                        </div>
                        
                        <!-- Description -->
                        ${rapport.description ? `
                        <div class="mb-4">
                            <h6 class="text-white mb-2"><i class="fas fa-align-left me-2"></i>Description</h6>
                            <p class="text-white" style="opacity: 0.9; line-height: 1.6;">${rapport.description}</p>
                        </div>
                        ` : ''}
                        
                        <!-- Informations Étudiant -->
                        <div class="mb-4" style="background: rgba(255,255,255,0.05); padding: 1.5rem; border-radius: 12px;">
                            <h6 class="text-white mb-3"><i class="fas fa-user me-2"></i>Informations de l'étudiant</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Nom :</small>
                                    <p class="text-white mb-0">${rapport.user_name}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <small class="text-muted">Formation :</small>
                                    <p class="text-white mb-0">${rapport.formation || rapport.specialization || 'N/A'}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Fichiers -->
                        <div class="mb-4">
                            <h6 class="text-white mb-3"><i class="fas fa-paperclip me-2"></i>Fichiers joints (${rapport.files ? rapport.files.length : 0})</h6>
                            ${filesHtml}
                        </div>
                        
                        <!-- Date de soumission -->
                        <div style="background: rgba(255,255,255,0.05); padding: 1rem; border-radius: 8px;">
                            <small class="text-muted"><i class="fas fa-calendar me-2"></i>Date de soumission</small>
                            <p class="text-white mb-0">${formattedDate} à ${formattedTime}</p>
                        </div>
                    </div>
                    <div class="modal-footer" style="border: none; padding: 1.5rem;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">
                            <i class="fas fa-times me-2"></i>Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Ajouter le modal au DOM
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Afficher le modal
    const detailsModal = new bootstrap.Modal(document.getElementById('reportDetailsModal'));
    detailsModal.show();
    
    // Nettoyer le modal après fermeture
    document.getElementById('reportDetailsModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

function updateStatus(tpId, status) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de ce rapport ?')) {
        // Créer un formulaire pour envoyer la requête
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/evc/app/admin/travaux/${tpId}/update-status`;
        
        // Token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);
        
        // Méthode PATCH
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PATCH';
        form.appendChild(methodInput);
        
        // Statut
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
