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
    <div class="row mb-4 g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm stat-card stat-card-primary h-100">
                <div class="card-body text-center p-4">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div class="h2 fw-bold mb-1 stat-number">{{ $data['stats']['total'] }}</div>
                    <div class="text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Total Étudiants</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm stat-card stat-card-success h-100">
                <div class="card-body text-center p-4">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-user-check fa-2x"></i>
                    </div>
                    <div class="h2 fw-bold mb-1 stat-number">{{ $data['stats']['active'] }}</div>
                    <div class="text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Étudiants Actifs</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm stat-card stat-card-info h-100">
                <div class="card-body text-center p-4">
                    <div class="stat-icon mb-3">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                    <div class="h2 fw-bold mb-1 stat-number">{{ $data['stats']['avg_progression'] }}%</div>
                    <div class="text-uppercase" style="font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px;">Progression Moyenne</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des étudiants -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h5 class="card-title mb-1" style="font-weight: 600; color: #2d3748;">
                                <i class="fas fa-list text-primary me-2"></i>Liste des Étudiants
                            </h5>
                            <p class="text-muted mb-0" style="font-size: 0.875rem;">
                                {{ $data['formation_name'] }} • {{ $data['stats']['total'] }} étudiant{{ $data['stats']['total'] > 1 ? 's' : '' }}
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                <i class="fas fa-sync-alt me-1"></i>Actualiser
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="window.print()">
                                <i class="fas fa-print me-1"></i>Imprimer
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="formationStudentsTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center" style="font-weight: 600; color: #2d3748;">#</th>
                                    <th width="280" style="font-weight: 600; color: #2d3748;">Nom & Prénom</th>
                                    <th width="120" style="font-weight: 600; color: #2d3748;">Pays</th>
                                    <th width="110" style="font-weight: 600; color: #2d3748;">Inscription</th>
                                    <th width="90" class="text-center" style="font-weight: 600; color: #2d3748;">TP</th>
                                    <th width="130" style="font-weight: 600; color: #2d3748;">Progression</th>
                                    <th width="140" class="text-center" style="font-weight: 600; color: #2d3748;">Jours Restants</th>
                                    <th width="160" class="text-center" style="font-weight: 600; color: #2d3748;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['students'] as $index => $student)
                                <tr class="{{ isset($student['status']) && $student['status'] === 'inactive' ? 'table-secondary opacity-75' : '' }}" style="vertical-align: middle;">
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark" style="font-size: 0.85rem; font-weight: 600;">{{ $index + 1 }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @php
                                                // Debug - afficher le chemin
                                                $hasPhoto = isset($student['photo_url']) && !empty($student['photo_url']);
                                            @endphp

                                            @if($hasPhoto)
                                                <div class="profile-photo-wrapper position-relative">
                                                    <img src="{{ $student['photo_url'] }}"
                                                         alt="Photo de {{ $student['prenom'] }} {{ $student['nom'] }}"
                                                         class="profile-photo rounded-circle"
                                                         title="{{ $student['photo_url'] }}"
                                                         onerror="console.error('Image error:', this.src); this.onerror=null; this.style.display='none'; this.nextElementSibling.classList.remove('d-none'); this.nextElementSibling.classList.add('d-flex');">
                                                    <div class="avatar-fallback bg-gradient-primary rounded-circle text-white d-none align-items-center justify-content-center position-absolute" style="top: 0; left: 0;">
                                                        <span class="fw-bold">{{ substr($student['prenom'] ?? 'E', 0, 1) }}{{ substr($student['nom'] ?? 'T', 0, 1) }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="avatar-fallback bg-gradient-primary rounded-circle text-white d-flex align-items-center justify-content-center">
                                                    <span class="fw-bold">{{ substr($student['prenom'] ?? 'E', 0, 1) }}{{ substr($student['nom'] ?? 'T', 0, 1) }}</span>
                                                </div>
                                            @endif

                                            <div class="flex-grow-1">
                                                <div class="fw-semibold text-dark" style="font-size: 0.95rem;">
                                                    {{ $student['prenom'] ?? 'Prénom' }} {{ $student['nom'] ?? 'Nom' }}
                                                </div>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <small class="text-muted" style="font-size: 0.75rem;">ID: {{ $student['id'] }}</small>
                                                    @if(isset($student['status']) && $student['status'] === 'inactive')
                                                        <span class="badge bg-danger" style="font-size: 0.65rem; padding: 2px 6px;">Inactif</span>
                                                    @else
                                                        <span class="badge bg-success" style="font-size: 0.65rem; padding: 2px 6px;">Actif</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $student['pays'] ?? '-' }}</td>
                                    <td>{{ date('d/m/Y', strtotime($student['created_at'])) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success px-2 py-1" style="font-size: 0.9rem;">{{ $student['tp_count'] ?? 0 }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar bg-success" style="width: {{ $student['progression'] ?? 0 }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $student['progression'] ?? 0 }}%</small>
                                    </td>
                                    <td class="text-center">
                                        @if(isset($student['days_remaining']) && $student['days_remaining'] !== null)
                                            @php
                                                $daysInt = (int) $student['days_remaining'];
                                                $expDate = isset($student['expiration_date']) ? $student['expiration_date']->format('d/m/Y à H:i') : '';
                                                $expIso = isset($student['expiration_date']) ? $student['expiration_date']->toIso8601String() : '';
                                                $regIso = !empty($student['created_at']) ? \Carbon\Carbon::parse($student['created_at'])->toIso8601String() : '';
                                                $durationMonths = isset($student['duration_months']) ? (int) $student['duration_months'] : 4;
                                            @endphp
                                            @if($student['is_expired'])
                                                <span class="badge bg-danger px-3 py-2 js-days-remaining"
                                                      data-expiration="{{ $expIso }}"
                                                      data-registration="{{ $regIso }}"
                                                      data-duration-months="{{ $durationMonths }}"
                                                      data-bs-toggle="tooltip"
                                                      data-bs-placement="top"
                                                      title="Expiré le {{ $expDate }}"
                                                      style="font-size: 0.9rem; min-width: 120px;">
                                                    <i class="fas fa-times-circle me-1"></i>Expiré
                                                </span>
                                            @elseif($daysInt <= 7)
                                                <span class="badge bg-danger px-3 py-2 js-days-remaining"
                                                      data-expiration="{{ $expIso }}"
                                                      data-registration="{{ $regIso }}"
                                                      data-duration-months="{{ $durationMonths }}"
                                                      data-bs-toggle="tooltip"
                                                      data-bs-placement="top"
                                                      title="Expire le {{ $expDate }}"
                                                      style="font-size: 0.9rem; min-width: 120px;">
                                                    <i class="fas fa-exclamation-triangle me-1"></i>{{ $daysInt }} jour{{ $daysInt > 1 ? 's' : '' }}
                                                </span>
                                            @elseif($daysInt <= 30)
                                                <span class="badge bg-warning text-dark px-3 py-2 js-days-remaining"
                                                      data-expiration="{{ $expIso }}"
                                                      data-registration="{{ $regIso }}"
                                                      data-duration-months="{{ $durationMonths }}"
                                                      data-bs-toggle="tooltip"
                                                      data-bs-placement="top"
                                                      title="Expire le {{ $expDate }}"
                                                      style="font-size: 0.9rem; min-width: 120px;">
                                                    <i class="fas fa-clock me-1"></i>{{ $daysInt }} jours
                                                </span>
                                            @else
                                                <span class="badge bg-success px-3 py-2 js-days-remaining"
                                                      data-expiration="{{ $expIso }}"
                                                      data-registration="{{ $regIso }}"
                                                      data-duration-months="{{ $durationMonths }}"
                                                      data-bs-toggle="tooltip"
                                                      data-bs-placement="top"
                                                      title="Expire le {{ $expDate }}"
                                                      style="font-size: 0.9rem; min-width: 120px;">
                                                    <i class="fas fa-check-circle me-1"></i>{{ $daysInt }} jours
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary px-3 py-2" style="font-size: 0.9rem; min-width: 120px;">
                                                <i class="fas fa-question-circle me-1"></i>Non défini
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.students.profile', $student['student_id'] ?? $student['id']) }}"
                                               class="btn btn-outline-primary btn-action"
                                               title="Voir profil"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.students.edit', $student['user_id'] ?? $student['id']) }}"
                                               class="btn btn-outline-warning btn-action"
                                               title="Modifier"
                                               data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button"
                                                        class="btn btn-outline-success btn-action dropdown-toggle"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        title="Prolonger"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <button class="dropdown-item js-extend-expiration" type="button" data-student-id="{{ $student['student_id'] ?? $student['id'] }}" data-months="1">Prolonger de 1 mois</button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item js-extend-expiration" type="button" data-student-id="{{ $student['student_id'] ?? $student['id'] }}" data-months="3">Prolonger de 3 mois</button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item js-extend-expiration" type="button" data-student-id="{{ $student['student_id'] ?? $student['id'] }}" data-months="6">Prolonger de 6 mois</button>
                                                    </li>
                                                </ul>
                                            </div>
                                            @if(isset($student['status']) && $student['status'] === 'inactive')
                                                <button type="button"
                                                        class="btn btn-outline-success btn-action"
                                                        title="Réactiver le compte"
                                                        data-bs-toggle="tooltip"
                                                        onclick="reactivateStudent({{ $student['student_id'] ?? $student['id'] }}, '{{ addslashes($student['prenom']) }} {{ addslashes($student['nom']) }}')">
                                                    <i class="fas fa-check-circle"></i>
                                                </button>
                                            @else
                                                <button type="button"
                                                        class="btn btn-outline-secondary btn-action"
                                                        title="Désactiver le compte"
                                                        data-bs-toggle="tooltip"
                                                        onclick="openDeactivateModal({{ $student['student_id'] ?? $student['id'] }}, '{{ addslashes($student['prenom']) }} {{ addslashes($student['nom']) }}', '{{ $student['email'] }}')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @endif
                                            <button type="button"
                                                    class="btn btn-outline-danger btn-action"
                                                    title="Supprimer définitivement"
                                                    data-bs-toggle="tooltip"
                                                    onclick="deleteStudent({{ $student['student_id'] ?? $student['id'] }}, '{{ addslashes($student['prenom']) }} {{ addslashes($student['nom']) }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
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
        const dt = $('#formationStudentsTable').DataTable({
            responsive: true,
            pageLength: 25,
            order: [[0, 'asc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
            },
            columnDefs: [
                { orderable: false, targets: [7] } // Colonne Actions non triable
            ]
        });

        // Assurer le recalcul du décompte après les redraw (pagination/recherche)
        $('#formationStudentsTable').on('draw.dt', function() {
            if (typeof window.updateDaysRemainingBadges === 'function') {
                window.updateDaysRemainingBadges();
            }
        });

        // Premier calcul après init
        if (typeof window.updateDaysRemainingBadges === 'function') {
            window.updateDaysRemainingBadges();
        }
    }

    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
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

/* Cartes de statistiques modernes */
.stat-card {
    transition: all 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
}

.stat-card-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    color: white;
}

.stat-card-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.stat-icon {
    opacity: 0.9;
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
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

/* Badges jours restants - Style optimisé */
.badge {
    font-size: 0.875rem;
    padding: 0.35rem 0.65rem;
    font-weight: 600;
    white-space: nowrap;
}

.badge i {
    font-size: 0.75rem;
}

/* Badges avec taille fixe pour uniformité */
.badge.px-3 {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

/* Optimisation des couleurs */
.badge.bg-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.badge.bg-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
}

.badge.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

/* Tooltips pour les dates d'expiration */
[data-bs-toggle="tooltip"] {
    cursor: help;
}

/* Centrage et alignement des colonnes */
.table td.text-center {
    vertical-align: middle;
}

/* Animation hover sur badges */
.badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: all 0.2s ease;
}

/* Photos de profil */
.profile-photo-wrapper {
    position: relative;
    width: 45px;
    height: 45px;
    flex-shrink: 0;
}

.profile-photo {
    width: 45px;
    height: 45px;
    object-fit: cover;
    border: 2px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.profile-photo:hover {
    transform: scale(1.1);
    border-color: var(--bs-primary);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.avatar-fallback {
    width: 45px;
    height: 45px;
    flex-shrink: 0;
    font-size: 0.875rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

.avatar-fallback:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Amélioration de l'affichage du nom */
.flex-grow-1 .fw-semibold {
    font-size: 0.95rem;
    line-height: 1.2;
}

.flex-grow-1 small {
    font-size: 0.75rem;
}

/* Optimisation du tableau */
.table {
    font-size: 0.9rem;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    padding: 0.75rem 0.5rem;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}

.table tbody td {
    padding: 0.75rem 0.5rem;
    vertical-align: middle;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
    transition: background-color 0.2s ease;
}

/* Boutons d'action optimisés */
.btn-action {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.btn-action i {
    font-size: 0.875rem;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Badge numéro de ligne */
.badge.bg-light {
    border: 1px solid #dee2e6;
}

/* Optimisation des espacements */
.card-body {
    padding: 1.25rem;
}

/* Card styling */
.card {
    border-radius: 12px;
}

.card-header {
    border-radius: 12px 12px 0 0 !important;
}

/* Amélioration du breadcrumb */
.breadcrumb {
    background: transparent;
    padding: 0;
    margin-bottom: 1rem;
}

.breadcrumb-item a {
    color: #667eea;
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-item a:hover {
    color: #764ba2;
}

.breadcrumb-item.active {
    color: #6c757d;
}

/* Responsive */
@media (max-width: 1400px) {
    .table {
        font-size: 0.85rem;
    }

    .btn-action {
        padding: 0.2rem 0.4rem;
        font-size: 0.8rem;
    }

    .stat-number {
        font-size: 2rem;
    }
}

@media (max-width: 768px) {
    .stat-card {
        margin-bottom: 1rem;
    }

    .table {
        font-size: 0.8rem;
    }
}

/* Print styles */
@media print {
    .btn, .btn-group {
        display: none !important;
    }

    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
    }

    .stat-card {
        break-inside: avoid;
        page-break-inside: avoid;
    }

    .table {
        font-size: 10pt;
    }

    thead {
        display: table-header-group;
    }

    tr {
        page-break-inside: avoid;
    }
}
</style>
@endpush
@endsection

@push('scripts')
<script>
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : '';
}

function computeExpirationFromRegistration(regIso, durationMonths) {
    if (!regIso) return null;
    const reg = new Date(regIso);
    if (Number.isNaN(reg.getTime())) return null;
    const exp = new Date(reg.getTime());
    const months = Number.isFinite(durationMonths) ? durationMonths : 4;
    exp.setMonth(exp.getMonth() + months);
    return exp;
}

function computeExpirationFromNow(durationMonths) {
    const now = new Date();
    const exp = new Date(now.getTime());
    const months = Number.isFinite(durationMonths) ? durationMonths : 4;
    exp.setMonth(exp.getMonth() + months);
    return exp;
}

function isSameDay(a, b) {
    return a.getFullYear() === b.getFullYear()
        && a.getMonth() === b.getMonth()
        && a.getDate() === b.getDate();
}

function computeRemainingParts(expDate) {
    if (!expDate) return null;
    const now = new Date();
    const diffMs = expDate.getTime() - now.getTime();
    if (Number.isNaN(diffMs)) return null;

    if (diffMs <= 0) {
        return { expired: true, days: 0, hours: 0, minutes: 0, seconds: 0 };
    }

    const totalSeconds = Math.floor(diffMs / 1000);
    const days = Math.floor(totalSeconds / 86400);
    const hours = Math.floor((totalSeconds % 86400) / 3600);
    const minutes = Math.floor((totalSeconds % 3600) / 60);
    const seconds = totalSeconds % 60;

    return { expired: false, days, hours, minutes, seconds };
}

window.updateDaysRemainingBadges = function updateDaysRemainingBadges() {
    const badges = document.querySelectorAll('.js-days-remaining');
    badges.forEach((badge) => {
        const regIso = badge.getAttribute('data-registration') || '';
        const durationMonths = parseInt(badge.getAttribute('data-duration-months') || '4', 10);
        const expIso = badge.getAttribute('data-expiration') || '';

        const computedExp = computeExpirationFromRegistration(regIso, durationMonths);

        let storedExp = null;
        if (expIso) {
            const parsed = new Date(expIso);
            if (!Number.isNaN(parsed.getTime())) {
                storedExp = parsed;
            }
        }

        // Ignorer une expiration stockée manifestement auto/erronée (basée sur maintenant + durée)
        let shouldIgnoreStored = false;
        if (storedExp && computedExp) {
            const nowBased = computeExpirationFromNow(durationMonths);
            if (isSameDay(storedExp, nowBased) && !isSameDay(storedExp, computedExp)) {
                shouldIgnoreStored = true;
            }
        }

        // Utiliser la date la plus tardive (prolongations manuelles prises en compte)
        let expDate = null;
        if (computedExp && storedExp) {
            if (shouldIgnoreStored) {
                expDate = computedExp;
            } else {
                expDate = storedExp.getTime() > computedExp.getTime() ? storedExp : computedExp;
            }
        } else {
            expDate = storedExp || computedExp;
        }

        const remaining = computeRemainingParts(expDate);
        if (!remaining) return;

        const iconEl = badge.querySelector('i');
        const iconClass = iconEl ? iconEl.className : '';

        if (remaining.expired) {
            badge.classList.remove('bg-success', 'bg-warning', 'text-dark');
            badge.classList.add('bg-danger');
            badge.innerHTML = `<i class="${iconClass || 'fas fa-times-circle me-1'}"></i>Expiré`;
            return;
        }

        const days = remaining.days;
        const label = days <= 1 ? 'jour' : 'jours';
        const hh = String(remaining.hours).padStart(2, '0');
        const mm = String(remaining.minutes).padStart(2, '0');
        const ss = String(remaining.seconds).padStart(2, '0');
        const timeLabel = `${hh}h ${mm}m ${ss}s`;

        badge.classList.remove('bg-danger');
        if (days <= 7) {
            badge.classList.remove('bg-success', 'bg-warning', 'text-dark');
            badge.classList.add('bg-danger');
            badge.innerHTML = `<i class="${iconClass || 'fas fa-exclamation-triangle me-1'}"></i>${days} ${label} ${timeLabel}`;
        } else if (days <= 30) {
            badge.classList.remove('bg-success', 'bg-danger');
            badge.classList.add('bg-warning', 'text-dark');
            badge.innerHTML = `<i class="${iconClass || 'fas fa-clock me-1'}"></i>${days} ${label} ${timeLabel}`;
        } else {
            badge.classList.remove('bg-warning', 'text-dark', 'bg-danger');
            badge.classList.add('bg-success');
            badge.innerHTML = `<i class="${iconClass || 'fas fa-check-circle me-1'}"></i>${days} ${label} ${timeLabel}`;
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.updateDaysRemainingBadges === 'function') {
        window.updateDaysRemainingBadges();
        setInterval(window.updateDaysRemainingBadges, 1000);
    }

    // Délégation d'événements pour supporter DataTables (DOM reconstruit)
    document.addEventListener('click', async function(e) {
        const btn = e.target.closest('.js-extend-expiration');
        if (!btn) return;

        const studentId = btn.getAttribute('data-student-id');
        const months = btn.getAttribute('data-months');
        if (!studentId || !months) return;

        try {
            const urlBase = @json(url('/evc/app/admin/students'));
            const url = `${urlBase}/${String(studentId)}/extend-expiration`;

            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ months: parseInt(months, 10) })
            });

            const data = await res.json();
            if (!res.ok || !data.success) {
                alert(data.message || 'Erreur lors de la prolongation');
                return;
            }

            const row = btn.closest('tr');
            if (!row) return;
            const badge = row.querySelector('.js-days-remaining');
            if (!badge) return;

            if (data.expiration_iso) {
                badge.setAttribute('data-expiration', data.expiration_iso);
            }

            if (typeof window.updateDaysRemainingBadges === 'function') {
                window.updateDaysRemainingBadges();
            }
        } catch (err) {
            alert('Erreur réseau lors de la prolongation');
        }
    });
});
</script>
@endpush
