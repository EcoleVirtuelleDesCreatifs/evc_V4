@extends('layouts.admin')

@section('title', 'TP en Attente de Validation')

@push('styles')
<style>
    :root {
        --admin-blue-dark: #1e3c72;
        --admin-blue-light: #4fc3f7;
        --admin-blue-mid: #2a5298;
    }

    .page-header {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid), var(--admin-blue-light));
        padding: 2.5rem 2rem;
        border-radius: 20px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
        animation: fadeInDown 0.6s ease;
    }

    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header .icon-circle {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-circle">
                📋
            </div>
            <div>
                <h1 class="mb-0">TP en Attente de Validation</h1>
                <p class="mb-0" style="opacity: 0.95;">Évaluez et validez les travaux pratiques soumis par les étudiants</p>
            </div>
        </div>
    </div>



    <!-- Statistiques -->
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div class="stat-label">Total TP</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['design_graphique'] }}</div>
                    <div class="stat-label">Design Graphique</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['community_management'] }}</div>
                    <div class="stat-label">Community Management</div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['gestion_informatique'] + $stats['intelligence_artificielle'] }}</div>
                    <div class="stat-label">Gestion Info + IA</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des TP soumis -->
    <div class="tp-list-card">
        <div class="tp-list-header">
            <h5 class="mb-0"><i class="fas fa-hourglass-half me-2"></i>TP Soumis en Attente</h5>
            <span class="badge-count">{{ $tpSubmissions->count() }} en attente</span>
        </div>
        <div class="tp-list-body">
            @if($tpSubmissions->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5>Aucun TP en attente</h5>
                    <p class="text-muted">Tous les TP ont été évalués !</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table modern-table">
                        <thead>
                            <tr>
                                <th><i class="fas fa-user me-2"></i>Étudiant</th>
                                <th><i class="fas fa-file-alt me-2"></i>Titre du TP</th>
                                <th><i class="fas fa-graduation-cap me-2"></i>Formation</th>
                                <th><i class="fas fa-clock me-2"></i>Date soumission</th>
                                <th><i class="fas fa-paperclip me-2"></i>Fichiers</th>
                                <th><i class="fas fa-cog me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tpSubmissions as $tp)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white me-2">
                                                {{ substr($tp->first_name, 0, 1) }}{{ substr($tp->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $tp->first_name }} {{ $tp->last_name }}</strong><br>
                                                <small class="text-muted">{{ $tp->student_email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ $tp->title }}</strong><br>
                                        <small class="text-muted">{{ Str::limit(strip_tags($tp->description), 60) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $tp->formation }}</span>
                                    </td>
                                    <td>
                                        <small>{{ \Carbon\Carbon::parse($tp->submitted_at)->format('d/m/Y H:i') }}</small><br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($tp->submitted_at)->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        @if($tp->files->count() > 0)
                                            <span class="badge bg-success">
                                                <i class="fas fa-paperclip"></i> {{ $tp->files->count() }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.projets.pending.show', $tp->id) }}" class="btn-action btn-view" title="Voir les détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn-action btn-validate" onclick="validateTP({{ $tp->id }})" title="Valider">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn-action btn-reject" onclick="rejectTP({{ $tp->id }})" title="Rejeter">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal pour voir les détails -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du TP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Contenu chargé dynamiquement -->
            </div>
        </div>
    </div>
</div>

<!-- Modal de rejet PERSONNALISÉ (sans Bootstrap pour éviter conflits) -->
<div id="customRejectModal" class="custom-modal-overlay" style="display: none;">
    <div class="custom-modal-container">
        <div class="custom-modal-content">
            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="custom-modal-header">
                    <h5 class="custom-modal-title">
                        <i class="fas fa-times-circle me-2"></i>Rejeter le TP
                    </h5>
                    <button type="button" class="custom-modal-close" onclick="closeRejectModal()">×</button>
                </div>
                <div class="custom-modal-body">
                    <div class="alert alert-warning" style="border-left: 4px solid #ffc107; border-radius: 10px; padding: 1rem; margin-bottom: 1.5rem; background: #fff3cd;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention :</strong> L'étudiant recevra un email avec votre commentaire.
                    </div>
                    
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label" style="font-weight: 600; color: #1a202c; display: block; margin-bottom: 0.5rem;">
                            <i class="fas fa-comment-alt me-2"></i>Raison du rejet <span class="text-danger">*</span>
                        </label>
                        <textarea 
                            class="form-control" 
                            id="rejectionReason" 
                            name="reason" 
                            rows="5" 
                            required 
                            minlength="10"
                            placeholder="Expliquez clairement les points à améliorer pour aider l'étudiant..."
                            style="width: 100%; border-radius: 12px; border: 2px solid #e9ecef; padding: 1rem; font-family: inherit; resize: vertical;"
                        ></textarea>
                        <small class="text-muted" style="display: block; margin-top: 0.5rem;">
                            <i class="fas fa-info-circle me-1"></i>Minimum 10 caractères
                        </small>
                    </div>

                    <div style="background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 10px; padding: 1rem; margin-top: 1rem;">
                        <p style="margin: 0; color: #856404; font-size: 0.9rem;">
                            <strong>💡 Conseil :</strong> Soyez constructif dans vos commentaires pour aider l'étudiant à progresser.
                        </p>
                    </div>
                </div>
                <div class="custom-modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeRejectModal()" style="border-radius: 20px; padding: 0.75rem 1.5rem;">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn" style="background: linear-gradient(135deg, #e74a3b, #be2617); color: white; border: none; border-radius: 20px; padding: 0.75rem 1.5rem; font-weight: 600;">
                        <i class="fas fa-paper-plane me-2"></i>Rejeter et Notifier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Modal personnalisé - Sans Bootstrap pour garantir l'interactivité */
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 99999999;
        display: flex;
        align-items: center;
        justify-content: center;
        animation: fadeIn 0.3s ease;
    }
    
    .custom-modal-container {
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        animation: slideDown 0.3s ease;
    }
    
    .custom-modal-content {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        overflow: hidden;
    }
    
    .custom-modal-header {
        background: linear-gradient(135deg, #e74a3b, #be2617);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .custom-modal-title {
        font-weight: 700;
        font-size: 1.25rem;
        margin: 0;
    }
    
    .custom-modal-close {
        background: transparent;
        border: none;
        color: white;
        font-size: 2rem;
        line-height: 1;
        cursor: pointer;
        padding: 0;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .custom-modal-close:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }
    
    .custom-modal-body {
        padding: 2rem;
    }
    
    .custom-modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1.5rem 2rem;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    #rejectionReason:focus {
        border-color: #e74a3b !important;
        box-shadow: 0 0 0 0.2rem rgba(231, 74, 59, 0.25) !important;
        outline: none !important;
    }
    
    /* Ensure button is clickable */
    .btn-action {
        cursor: pointer;
        z-index: 1;
    }
    
    /* Cartes statistiques */
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease;
        animation-fill-mode: both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(30, 60, 114, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .stat-card-primary .stat-icon {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
    }

    .stat-card-info .stat-icon {
        background: linear-gradient(135deg, #36b9cc, #258391);
    }

    .stat-card-warning .stat-icon {
        background: linear-gradient(135deg, #f6c23e, #dda20a);
    }

    .stat-card-success .stat-icon {
        background: linear-gradient(135deg, #1cc88a, #13855c);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #1a202c;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        color: #718096;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Liste des TP */
    .tp-list-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease 0.5s both;
    }

    .tp-list-header {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: white;
    }

    .tp-list-header h5 {
        font-weight: 700;
        margin: 0;
    }

    .badge-count {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .tp-list-body {
        padding: 2rem;
    }

    /* Table moderne */
    .modern-table {
        margin: 0;
    }

    .modern-table thead th {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.05), rgba(79, 195, 247, 0.05));
        border: none;
        padding: 1rem;
        font-weight: 600;
        color: var(--admin-blue-dark);
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .modern-table tbody tr {
        border-bottom: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .modern-table tbody tr:hover {
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.03), rgba(79, 195, 247, 0.03));
        transform: translateX(5px);
    }

    .modern-table tbody td {
        padding: 1rem;
        vertical-align: middle;
    }

    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        color: white;
        box-shadow: 0 4px 10px rgba(30, 60, 114, 0.2);
    }

    /* Boutons d'action */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.875rem;
    }

    .btn-view {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-mid));
        color: white;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
    }

    .btn-validate {
        background: linear-gradient(135deg, #1cc88a, #13855c);
        color: white;
    }

    .btn-validate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(28, 200, 138, 0.3);
    }

    .btn-reject {
        background: linear-gradient(135deg, #e74a3b, #be2617);
        color: white;
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(231, 74, 59, 0.3);
    }

    /* État vide */
    .text-center.py-5 {
        padding: 4rem 2rem !important;
    }

    .text-center.py-5 i {
        color: #1cc88a;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>

<script>
const tpData = @json($tpSubmissions);

function viewDetails(tpId) {
    const tp = tpData.find(t => t.id === tpId);
    if (!tp) return;

    let filesHtml = '';
    if (tp.files && tp.files.length > 0) {
        filesHtml = '<h6 class="mt-3">Fichiers soumis :</h6><ul class="list-group">';
        tp.files.forEach(file => {
            filesHtml += `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file"></i> ${file.file_name}</span>
                    <a href="/storage/${file.file_path}" target="_blank" class="btn btn-sm btn-primary">
                        <i class="fas fa-download"></i> Télécharger
                    </a>
                </li>
            `;
        });
        filesHtml += '</ul>';
    }

    const linkHtml = tp.submission_link ? `
        <h6 class="mt-3">Lien de soumission :</h6>
        <a href="${tp.submission_link}" target="_blank" class="btn btn-link">${tp.submission_link}</a>
    ` : '';

    document.getElementById('modalBody').innerHTML = `
        <div class="mb-3">
            <h5>${tp.title}</h5>
            <p class="text-muted">Par ${tp.first_name} ${tp.last_name} (${tp.formation})</p>
        </div>
        <div class="mb-3">
            <h6>Description :</h6>
            <div class="border p-3 rounded">${tp.description}</div>
        </div>
        ${filesHtml}
        ${linkHtml}
        <div class="mt-4">
            <strong>Date de soumission :</strong> ${new Date(tp.submitted_at).toLocaleString('fr-FR')}
        </div>
    `;

    new bootstrap.Modal(document.getElementById('detailsModal')).show();
}

function validateTP(tpId) {
    if (confirm('Êtes-vous sûr de vouloir valider ce TP ?')) {
        // Créer un formulaire et le soumettre
        const form = document.createElement('form');
        form.method = 'POST';
        // Utiliser une URL template Laravel pour garantir la route correcte
        const validateUrl = '{{ route("admin.projets.pending.validate", ["id" => "__ID__"]) }}'.replace('__ID__', tpId);
        form.action = validateUrl;
        
        // Ajouter le token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Fonctions pour le modal personnalisé (sans Bootstrap)
function rejectTP(tpId) {
    // Stocker l'ID du TP dans le formulaire de rejet
    const rejectUrl = '{{ route("admin.projets.pending.reject", ["id" => "__ID__"]) }}'.replace('__ID__', tpId);
    document.getElementById('rejectForm').action = rejectUrl;
    
    // Ouvrir le modal personnalisé
    document.getElementById('customRejectModal').style.display = 'flex';
    
    // Focus sur le textarea après un court délai pour l'animation
    setTimeout(() => {
        const textarea = document.getElementById('rejectionReason');
        if (textarea) {
            textarea.focus();
        }
    }, 300);
}

function closeRejectModal() {
    // Fermer le modal
    document.getElementById('customRejectModal').style.display = 'none';
    // Nettoyer le textarea
    document.getElementById('rejectionReason').value = '';
}

// Fermer le modal si on clique en dehors
document.addEventListener('DOMContentLoaded', function() {
    const modalOverlay = document.getElementById('customRejectModal');
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            // Si on clique sur l'overlay (pas sur le contenu du modal)
            if (e.target === modalOverlay) {
                closeRejectModal();
            }
        });
    }
    
    // Fermer avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('customRejectModal');
            if (modal && modal.style.display === 'flex') {
                closeRejectModal();
            }
        }
    });
});
</script>
@endsection
