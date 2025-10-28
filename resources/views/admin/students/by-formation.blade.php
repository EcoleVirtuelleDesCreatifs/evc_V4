@extends('layouts.admin')

@section('title', 'Étudiants - ' . $data['formation_name'])

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.statistics.detail', 'total-students') }}">Étudiants</a></li>
                    <li class="breadcrumb-item active">{{ $data['formation_name'] }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-primary">
                <i class="fas fa-graduation-cap me-2"></i>Étudiants - {{ $data['formation_name'] }}
            </h1>
        </div>
    </div>

    <!-- Statistiques de la formation -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body text-center">
                    <div class="h2 fw-bold mb-1">{{ $data['stats']['total'] }}</div>
                    <small>Total Étudiants</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-gradient-success text-white">
                <div class="card-body text-center">
                    <div class="h2 fw-bold mb-1">{{ $data['stats']['active'] }}</div>
                    <small>Étudiants Actifs</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm bg-gradient-info text-white">
                <div class="card-body text-center">
                    <div class="h2 fw-bold mb-1">{{ $data['stats']['avg_progression'] }}%</div>
                    <small>Progression Moyenne</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des étudiants -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users text-primary me-2"></i>Liste des Étudiants - {{ $data['formation_name'] }}
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                            <i class="fas fa-sync-alt me-1"></i>Actualiser
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="formationStudentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="60">#</th>
                                    <th>Nom & Prénom</th>
                                    <th>Pays</th>
                                    <th>Inscription</th>
                                    <th>TP Réalisés</th>
                                    <th>Progression</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['students'] as $index => $student)
                                <tr class="{{ isset($student['status']) && $student['status'] === 'inactive' ? 'table-secondary opacity-75' : '' }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if(isset($student['photo_url']) && $student['photo_url'])
                                                <img src="{{ $student['photo_url'] }}" 
                                                     alt="Photo de {{ $student['prenom'] }} {{ $student['nom'] }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                <div class="avatar-sm bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="display: none; width: 40px; height: 40px;">
                                                    {{ substr($student['prenom'] ?? 'E', 0, 1) }}{{ substr($student['nom'] ?? 'T', 0, 1) }}
                                                </div>
                                            @else
                                                <div class="avatar-sm bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    {{ substr($student['prenom'] ?? 'E', 0, 1) }}{{ substr($student['nom'] ?? 'T', 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">
                                                    {{ $student['prenom'] ?? 'Prénom' }} {{ $student['nom'] ?? 'Nom' }}
                                                    @if(isset($student['status']) && $student['status'] === 'inactive')
                                                        <span class="badge bg-danger ms-2">Inactif</span>
                                                    @else
                                                        <span class="badge bg-success ms-2">Actif</span>
                                                    @endif
                                                </div>
                                                <small class="text-muted">ID: {{ $student['id'] }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student['pays'] ?? '-' }}</td>
                                    <td>{{ date('d/m/Y', strtotime($student['created_at'])) }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $student['tp_count'] ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: {{ $student['progression'] ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $student['progression'] ?? 0 }}%</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.students.profile', $student['id']) }}" class="btn btn-outline-primary" title="Voir profil">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student['id']) }}" class="btn btn-outline-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(isset($student['status']) && $student['status'] === 'inactive')
                                                <button type="button" 
                                                        class="btn btn-outline-success" 
                                                        title="Réactiver le compte"
                                                        onclick="reactivateStudent({{ $student['student_id'] ?? $student['id'] }}, '{{ addslashes($student['prenom']) }} {{ addslashes($student['nom']) }}')">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            @else
                                                <button type="button" 
                                                        class="btn btn-outline-secondary" 
                                                        title="Désactiver le compte"
                                                        onclick="openDeactivateModal({{ $student['student_id'] ?? $student['id'] }}, '{{ addslashes($student['prenom']) }} {{ addslashes($student['nom']) }}', '{{ $student['email'] }}')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @endif
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    title="Supprimer définitivement"
                                                    onclick="deleteStudent({{ $student['student_id'] ?? $student['id'] }}, '{{ addslashes($student['prenom']) }} {{ addslashes($student['nom']) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-2x mb-2"></i>
                                            <p>Aucun étudiant trouvé pour cette formation</p>
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
</div>

<!-- Modal de désactivation Custom -->
<div id="customDeactivateModal" class="custom-modal" style="display: none;">
    <div class="custom-modal-overlay" onclick="closeDeactivateModal()"></div>
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5>
                <i class="fas fa-exclamation-triangle me-2"></i>Désactiver le compte étudiant
            </h5>
            <button type="button" class="custom-modal-close" onclick="closeDeactivateModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="custom-modal-body">
            <div class="alert alert-warning">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Attention :</strong> Cette action bloquera l'accès de l'étudiant à son compte.
            </div>
            
            <p class="mb-3">
                <strong>Étudiant :</strong> <span id="studentNameDisplay"></span><br>
                <strong>Email :</strong> <span id="studentEmailDisplay"></span>
            </p>
            
            <div class="mb-3">
                <label for="deactivationReason" class="form-label">
                    <strong>Raison de la désactivation *</strong>
                </label>
                <textarea 
                    class="form-control" 
                    id="deactivationReason" 
                    rows="4" 
                    placeholder="Veuillez expliquer la raison de la désactivation du compte. Cette information sera envoyée à l'étudiant par email."></textarea>
                <small class="text-muted">Cette raison sera envoyée par email à l'étudiant.</small>
            </div>
            
            <input type="hidden" id="studentIdToDeactivate">
            <input type="hidden" id="studentEmailToDeactivate">
        </div>
        
        <div class="custom-modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeDeactivateModal()">
                <i class="fas fa-times me-1"></i>Annuler
            </button>
            <button type="button" class="btn btn-danger" onclick="confirmDeactivation()">
                <i class="fas fa-ban me-1"></i>Désactiver le compte
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser DataTable
    if (document.getElementById('formationStudentsTable')) {
        $('#formationStudentsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            columnDefs: [
                { orderable: false, targets: [6] }
            ]
        });
    }
});



// Ouvrir le modal custom
function openDeactivateModal(studentId, studentName, email) {
    document.getElementById('studentIdToDeactivate').value = studentId;
    document.getElementById('studentEmailToDeactivate').value = email;
    document.getElementById('studentNameDisplay').textContent = studentName;
    document.getElementById('studentEmailDisplay').textContent = email;
    document.getElementById('deactivationReason').value = '';
    
    // Afficher le modal
    document.getElementById('customDeactivateModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    
    // Focus sur le textarea
    setTimeout(function() {
        document.getElementById('deactivationReason').focus();
    }, 100);
}

// Fermer le modal custom
function closeDeactivateModal() {
    document.getElementById('customDeactivateModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('deactivationReason').value = '';
}

// Réactiver un compte étudiant
function reactivateStudent(studentId, studentName) {
    if (!confirm(`Êtes-vous sûr de vouloir réactiver le compte de ${studentName} ?`)) {
        return;
    }
    
    // Afficher un indicateur de chargement
    const loadingMsg = document.createElement('div');
    loadingMsg.className = 'alert alert-info position-fixed top-0 start-50 translate-middle-x mt-3';
    loadingMsg.style.zIndex = '99999';
    loadingMsg.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Réactivation en cours...';
    document.body.appendChild(loadingMsg);
    
    fetch(`/evc/app/admin/students/${studentId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            student_id: studentId,
            reason: 'Compte réactivé par l\'administration',
            email: ''
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (loadingMsg.parentNode) {
            document.body.removeChild(loadingMsg);
        }
        if (data.success) {
            showSuccessNotification(data.message);
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showErrorNotification('Erreur: ' + (data.message || 'Impossible de réactiver le compte'));
        }
    })
    .catch(error => {
        if (loadingMsg.parentNode) {
            document.body.removeChild(loadingMsg);
        }
        console.error('Erreur:', error);
        showErrorNotification('Une erreur est survenue: ' + error.message);
    });
}

// Confirmer la désactivation
function confirmDeactivation() {
    const studentId = document.getElementById('studentIdToDeactivate').value;
    const email = document.getElementById('studentEmailToDeactivate').value;
    const reason = document.getElementById('deactivationReason').value.trim();
    
    if (!reason) {
        alert('Veuillez saisir une raison pour la désactivation.');
        document.getElementById('deactivationReason').focus();
        return;
    }
    
    if (reason.length < 10) {
        alert('La raison doit contenir au moins 10 caractères.');
        document.getElementById('deactivationReason').focus();
        return;
    }
    
    // Fermer le modal
    closeDeactivateModal();
    
    // Afficher un indicateur de chargement
    const loadingMsg = document.createElement('div');
    loadingMsg.className = 'alert alert-info position-fixed top-0 start-50 translate-middle-x mt-3';
    loadingMsg.style.zIndex = '99999';
    loadingMsg.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Désactivation en cours...';
    document.body.appendChild(loadingMsg);
    
    fetch(`/evc/app/admin/students/${studentId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            student_id: studentId,
            reason: reason,
            email: email
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.text().then(text => {
                console.error('Error response:', text);
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (loadingMsg.parentNode) {
            document.body.removeChild(loadingMsg);
        }
        console.log('Success data:', data);
        if (data.success) {
            // Afficher une belle notification de succès
            showSuccessNotification(data.message);
            // Recharger après 2 secondes
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            showErrorNotification('Erreur: ' + (data.message || 'Impossible de désactiver le compte'));
        }
    })
    .catch(error => {
        if (loadingMsg.parentNode) {
            document.body.removeChild(loadingMsg);
        }
        console.error('Erreur complète:', error);
        showErrorNotification('Une erreur est survenue: ' + error.message);
    });
}

// Afficher une notification de succès
function showSuccessNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-success alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 99999; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    notification.innerHTML = `
        <i class="fas fa-check-circle me-2"></i>
        <strong>Succès !</strong>
        <div class="mt-1">${message}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(notification);
    
    // Retirer automatiquement après 3 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 150);
        }
    }, 3000);
}

// Afficher une notification d'erreur
function showErrorNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'alert alert-danger alert-dismissible fade show position-fixed';
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 99999; max-width: 500px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
    notification.innerHTML = `
        <i class="fas fa-exclamation-circle me-2"></i>
        <strong>Erreur !</strong>
        <div class="mt-1">${message}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    document.body.appendChild(notification);
    
    // Retirer automatiquement après 5 secondes
    setTimeout(() => {
        if (notification.parentNode) {
            notification.classList.remove('show');
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 150);
        }
    }, 5000);
}

// Supprimer un étudiant définitivement
function deleteStudent(studentId, studentName) {
    if (!confirm(`⚠️ ATTENTION - SUPPRESSION DÉFINITIVE !\n\nÊtes-vous absolument sûr de vouloir supprimer l'étudiant "${studentName}" ?\n\n⚠️ Cette action est IRRÉVERSIBLE et supprimera :\n- Le profil étudiant\n- Tous ses TP\n- Tous ses projets\n- Tous ses documents\n- Toutes ses données\n\nTapez OK pour confirmer la suppression.`)) {
        return;
    }

    // Deuxième confirmation
    if (!confirm(`Dernière confirmation !\n\nVoulez-vous vraiment supprimer définitivement "${studentName}" ?\n\nCette action ne peut PAS être annulée.`)) {
        return;
    }

    // Créer un formulaire de suppression
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/evc/app/admin/students/${studentId}/delete`;
    
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
    
    // Ajouter au body et soumettre
    document.body.appendChild(form);
    form.submit();
}
</script>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

.progress {
    background-color: #e9ecef;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

/* Modal Custom Styles */
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
}

.custom-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10001;
}

.custom-modal-content {
    position: relative;
    z-index: 10002;
    background: white;
    border-radius: 10px;
    max-width: 600px;
    margin: 50px auto;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.custom-modal-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    padding: 20px;
    border-radius: 10px 10px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.custom-modal-header h5 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.custom-modal-close {
    background: transparent;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 5px;
    transition: background 0.2s;
}

.custom-modal-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.custom-modal-body {
    padding: 20px;
    max-height: 70vh;
    overflow-y: auto;
}

.custom-modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    border-radius: 0 0 10px 10px;
}

/* S'assurer que tous les éléments du modal sont cliquables */
.custom-modal-content * {
    pointer-events: auto;
}

#deactivationReason {
    background-color: white;
    cursor: text;
    resize: vertical;
}

#deactivationReason:focus {
    outline: none;
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endpush
@endsection
