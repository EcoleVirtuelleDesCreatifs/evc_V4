@extends('layouts.admin')

@section('title', 'Documents en Attente de Validation')

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

    .stats-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        animation: fadeInUp 0.6s ease;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

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

    .document-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border-left: 4px solid var(--admin-blue-light);
    }

    .document-card:hover {
        transform: translateX(5px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    }

    .btn-validate {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-validate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
        color: white;
    }

    .btn-reject {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-reject:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        color: white;
    }

    .badge-pending {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--admin-blue-light);
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Fix modal z-index et backdrop */
    .modal-backdrop {
        z-index: 999998 !important;
    }

    #reportDetailsModal {
        z-index: 999999 !important;
    }

    #reportDetailsModal .modal-dialog {
        z-index: 1000000 !important;
    }

    #reportDetailsModal .modal-content {
        position: relative;
        z-index: 1000001 !important;
        pointer-events: auto !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Header -->
    <div class="page-header">
        <h1>
            <div class="icon-circle">
                <i class="fas fa-file-alt"></i>
            </div>
            Documents en Attente de Validation
        </h1>
        <p class="mb-0" style="opacity: 0.9;">
            <i class="fas fa-info-circle me-2"></i>
            Gérez les documents soumis par les étudiants en attente de validation
        </p>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1" style="color: var(--admin-blue-dark); font-weight: 700;">
                            {{ $stats['total'] }}
                        </h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-clock me-2"></i>
                            Total en attente
                        </p>
                    </div>
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-alt" style="color: white; font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="mb-1" style="color: var(--admin-blue-dark); font-weight: 700;">
                            {{ $stats['today'] }}
                        </h3>
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar-day me-2"></i>
                            Soumis aujourd'hui
                        </p>
                    </div>
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #ffc107, #ff9800); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-calendar-check" style="color: white; font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des documents -->
    @if($rapports->count() > 0)
        <div class="row">
            <div class="col-12">
                @foreach($rapports as $rapport)
                <div class="document-card">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-2" style="color: var(--admin-blue-dark); font-weight: 600;">
                                <i class="fas fa-file-pdf me-2" style="color: var(--admin-blue-light);"></i>
                                {{ $rapport->title }}
                            </h5>
                            <p class="text-muted mb-2">
                                <i class="fas fa-folder me-2"></i>
                                <strong>Étudiant:</strong> {{ $rapport->user_name ?? 'Non spécifié' }}
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Soumis le:</strong> {{ \Carbon\Carbon::parse($rapport->created_at)->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                        <div class="col-md-3 text-center">
                            <span class="badge-pending">
                                <i class="fas fa-hourglass-half me-1"></i>
                                En attente
                            </span>
                        </div>
                        <div class="col-md-3 text-end">
                            <div class="d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-info"
                                        title="Voir les détails et fichiers"
                                        onclick='viewReportDetails(@json($rapport))'
                                        style="border-radius: 6px;">
                                    <i class="fas fa-eye"></i>
                                </button>

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

                                <form action="{{ url('/evc/app/admin/travaux/' . $rapport->id . '/update-status') }}"
                                      method="POST"
                                      style="display: inline;"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir rejeter ce rapport ?');">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Rejeter"
                                            style="border-radius: 6px;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3 style="color: var(--admin-blue-dark); font-weight: 600; margin-bottom: 1rem;">
                Aucun document en attente
            </h3>
            <p class="text-muted mb-0">
                Tous les documents ont été traités. Bravo ! 🎉
            </p>
        </div>
    @endif
</div>

@push('scripts')
<script>
function validateDocument(id) {
    if (confirm('Êtes-vous sûr de vouloir valider ce document ?')) {
        // TODO: Implémenter la validation via AJAX
        fetch(`/evc/app/admin/documents/${id}/validate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la validation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la validation');
        });
    }
}

function rejectDocument(id) {
    const reason = prompt('Raison du rejet (optionnel):');
    if (reason !== null) {
        // TODO: Implémenter le rejet via AJAX
        fetch(`/evc/app/admin/documents/${id}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors du rejet');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du rejet');
        });
    }
}

function viewReportDetails(rapport) {
    // Formater la date
    const date = new Date(rapport.created_at);
    const formattedDate = date.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
    const formattedTime = date.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

    // Badge de statut
    let statusBadge = '<span class="badge" style="background: linear-gradient(135deg, #F56040 0%, #FCAF45 100%); color: white; padding: 0.5rem 1rem; font-size: 0.9rem;"><i class="fas fa-clock me-1"></i>En attente</span>';

    // Liste des fichiers
    let filesHtml = '<p class="text-muted">Aucun fichier</p>';
    if (rapport.files && rapport.files.length > 0) {
        filesHtml = '<div class="list-group">';
        rapport.files.forEach(file => {
            const fileSize = file.file_size            // Construire le chemin correct du fichier
            let filePath = file.file_path;
            if (!filePath.startsWith('http')) {
                // Nettoyer le chemin
                filePath = filePath.replace(/^public\//, '').replace(/^storage\//, '').replace(/^\//, '');

                // Forcer l'utilisation de storage/ pour tous les fichiers locaux
                // y compris ceux dans uploads/
                filePath = '{{ asset("storage") }}/' + filePath;
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

</script>
@endpush
@endsection
