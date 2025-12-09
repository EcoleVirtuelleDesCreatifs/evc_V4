@extends('layouts.admin')

@section('title', 'Travaux en Attente de Validation')

@section('content')
<style>
/* Animations fluides */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes shimmer {
    0% {
        background-position: -1000px 0;
    }
    100% {
        background-position: 1000px 0;
    }
}

.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

.stat-card {
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.stat-card:hover::before {
    left: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
}

.student-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 4px solid transparent;
}

.student-card:hover {
    border-left-color: #007bff;
    background: linear-gradient(90deg, rgba(0,123,255,0.03) 0%, transparent 100%);
    transform: translateX(5px);
}

.btn-modern {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.btn-modern::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255,255,255,0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-modern:hover::before {
    width: 300px;
    height: 300px;
}

.badge-modern {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

.badge-modern:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.collapse-content {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.tp-table {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.2rem;
    box-shadow: 0 4px 12px rgba(30, 60, 114, 0.4);
    transition: all 0.3s ease;
}

.avatar-circle:hover {
    transform: rotate(360deg) scale(1.1);
}

.header-gradient {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    position: relative;
    overflow: hidden;
}

.header-gradient::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 4s ease-in-out infinite;
}

.glass-effect {
    background: linear-gradient(135deg, #ff9800 0%, #ff6f00 100%);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    box-shadow: 0 8px 24px rgba(255, 152, 0, 0.3);
}
</style>

<div class="container-fluid">
    <!-- En-tête moderne avec effet de verre -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card border-0 shadow-lg header-gradient">
                <div class="card-body text-white p-5 position-relative">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-3 fw-bold">
                                <i class="fas fa-clock me-3"></i>
                                Travaux en Attente
                            </h1>
                            <p class="mb-0 fs-5 opacity-90">
                                Gérez et validez les travaux pratiques soumis par les étudiants
                            </p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="d-inline-block glass-effect rounded-4 p-4">
                                <div class="display-3 fw-bold mb-2">{{ $stats['total_students'] }}</div>
                                <div class="fs-5 mb-2">Étudiants</div>
                                <span class="badge bg-warning text-dark badge-modern">
                                    <i class="fas fa-file-alt me-2"></i>
                                    {{ $stats['total_tps'] }} TP
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par formation avec animations -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3 fade-in-up" style="animation-delay: 0.1s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-6 mb-2 opacity-90">Design Graphique</div>
                            <div class="display-5 fw-bold">{{ $stats['design_graphique'] }}</div>
                        </div>
                        <div>
                            <i class="fas fa-palette fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 fade-in-up" style="animation-delay: 0.2s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-6 mb-2 opacity-90">Community Management</div>
                            <div class="display-5 fw-bold">{{ $stats['community_management'] }}</div>
                        </div>
                        <div>
                            <i class="fas fa-users fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 fade-in-up" style="animation-delay: 0.3s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #3a5ba8 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-6 mb-2 opacity-90">Gestion Informatique</div>
                            <div class="display-5 fw-bold">{{ $stats['gestion_informatique'] }}</div>
                        </div>
                        <div>
                            <i class="fas fa-laptop-code fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 fade-in-up" style="animation-delay: 0.4s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #2a5298 0%, #4a7bc8 100%);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="fs-6 mb-2 opacity-90">Intelligence Artificielle</div>
                            <div class="display-5 fw-bold">{{ $stats['intelligence_artificielle'] }}</div>
                        </div>
                        <div>
                            <i class="fas fa-robot fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants avec leurs TP -->
    <div class="row fade-in-up" style="animation-delay: 0.5s;">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header py-4 px-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-bottom: 2px solid #dee2e6;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-users me-3 text-primary"></i>
                            Étudiants avec TP en Attente
                        </h4>
                        <div>
                            <button class="btn btn-primary btn-modern rounded-pill px-4" onclick="window.location.reload()">
                                <i class="fas fa-sync-alt me-2"></i>
                                Actualiser
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    @if($studentsTps->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-4 py-3" style="width: 50px;">#</th>
                                        <th class="py-3">Étudiant</th>
                                        <th class="py-3">Formation</th>
                                        <th class="py-3 text-center">TP en attente</th>
                                        <th class="py-3 text-center">Dernière soumission</th>
                                        <th class="py-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentsTps as $index => $student)
                                        <tr class="student-card">
                                            <td class="px-4 align-middle">
                                                <div class="badge bg-gradient-primary badge-modern">{{ $index + 1 }}</div>
                                            </td>
                                            <td class="align-middle py-3">
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $studentData = DB::table('students')->where('user_id', $student['user_id'])->first();
                                                        $hasPhoto = $studentData && $studentData->profile_photo;
                                                    @endphp

                                                    @if($hasPhoto)
                                                        <div class="me-3" style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; box-shadow: 0 4px 12px rgba(30, 60, 114, 0.4);">
                                                            <img src="{{ asset($studentData->profile_photo) }}"
                                                                 alt="{{ $student['first_name'] ?? '' }} {{ $student['last_name'] ?? '' }}"
                                                                 style="width: 100%; height: 100%; object-fit: cover;">
                                                        </div>
                                                    @else
                                                        <div class="avatar-circle me-3">
                                                            {{ strtoupper(substr($student['first_name'] ?? $student['user_name'], 0, 1)) }}{{ strtoupper(substr($student['last_name'] ?? '', 0, 1)) }}
                                                        </div>
                                                    @endif

                                                    <div>
                                                        <div class="fw-bold fs-6 mb-1">
                                                            {{ $student['first_name'] ?? '' }} {{ $student['last_name'] ?? $student['user_name'] }}
                                                        </div>
                                                        <small class="text-muted d-flex align-items-center">
                                                            <i class="fas fa-id-card me-1"></i>
                                                            <span class="me-2">{{ $student['student_id'] ?? 'N/A' }}</span>
                                                            <span class="mx-1">•</span>
                                                            <i class="fas fa-envelope ms-2 me-1"></i>
                                                            <span>{{ $student['user_email'] }}</span>
                                                        </small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge badge-modern" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); color: white;">
                                                    {{ $student['program'] ?? 'Non spécifié' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="badge bg-warning text-dark badge-modern fs-6">
                                                    <i class="fas fa-file-alt me-2"></i>
                                                    <strong>{{ $student['tps_count'] }}</strong>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <div class="fw-bold text-primary mb-1">
                                                    <i class="fas fa-calendar-alt me-1"></i>
                                                    {{ \Carbon\Carbon::parse($student['latest_submission'])->format('d/m/Y') }}
                                                </div>
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($student['latest_submission'])->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td class="align-middle text-center">
                                                <button class="btn btn-primary btn-modern rounded-pill px-4"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#student-{{ $student['user_id'] }}"
                                                        aria-expanded="false">
                                                    <i class="fas fa-eye me-2"></i>
                                                    Voir les TP
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="collapse" id="student-{{ $student['user_id'] }}">
                                            <td colspan="6" class="p-0">
                                                <div class="bg-light p-3">
                                                    <h6 class="mb-3">
                                                        <i class="fas fa-list me-2"></i>
                                                        Liste des TP de {{ $student['first_name'] ?? '' }} {{ $student['last_name'] ?? $student['user_name'] }}
                                                    </h6>
                                                    <div class="table-responsive">
                                                        <table class="table table-sm table-bordered bg-white mb-0">
                                                            <thead class="table-secondary">
                                                                <tr>
                                                                    <th style="width: 50px;">#</th>
                                                                    <th>Titre du TP</th>
                                                                    <th style="width: 150px;">Date</th>
                                                                    <th style="width: 120px;" class="text-center">Statut</th>
                                                                    <th style="width: 180px;" class="text-center">Actions</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($student['tps'] as $tpIndex => $tp)
                                                                    <tr>
                                                                        <td class="align-middle">{{ $tpIndex + 1 }}</td>
                                                                        <td class="align-middle">
                                                                            <div class="fw-bold">{!! Str::limit($tp->title, 60) !!}</div>
                                                                            @if($tp->description)
                                                                                <small class="text-muted">{!! Str::limit(strip_tags($tp->description), 80) !!}</small>
                                                                            @endif
                                                                        </td>
                                                                        <td class="align-middle">
                                                                            <small>{{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y H:i') }}</small>
                                                                        </td>
                                                                        <td class="align-middle text-center">
                                                                            @if($tp->status === 'submitted')
                                                                                <span class="badge bg-success">
                                                                                    <i class="fas fa-check-circle me-1"></i>
                                                                                    Déjà fait
                                                                                </span>
                                                                            @elseif($tp->status === 'pending')
                                                                                <span class="badge bg-warning text-dark">
                                                                                    <i class="fas fa-clock me-1"></i>
                                                                                    En attente
                                                                                </span>
                                                                            @else
                                                                                <span class="badge bg-info">
                                                                                    <i class="fas fa-tasks me-1"></i>
                                                                                    À faire
                                                                                </span>
                                                                            @endif
                                                                        </td>
                                                                        <td class="align-middle text-center">
                                                                            <div class="btn-group btn-group-sm">
                                                                                <a href="{{ route('admin.tp.view', $tp->id) }}"
                                                                                   class="btn btn-outline-primary"
                                                                                   title="Voir">
                                                                                    <i class="fas fa-eye"></i>
                                                                                </a>
                                                                                <button type="button"
                                                                                        class="btn btn-outline-success"
                                                                                        onclick="validateTp({{ $tp->id }})"
                                                                                        title="Valider">
                                                                                    <i class="fas fa-check"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                        class="btn btn-outline-warning"
                                                                                        onclick="rejectTp({{ $tp->id }})"
                                                                                        title="Rejeter">
                                                                                    <i class="fas fa-times"></i>
                                                                                </button>
                                                                                <button type="button"
                                                                                        class="btn btn-outline-danger"
                                                                                        onclick="deleteTp({{ $tp->id }})"
                                                                                        title="Supprimer">
                                                                                    <i class="fas fa-trash"></i>
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem; opacity: 0.3;"></i>
                            <h4 class="mt-3 text-muted">Aucun travail en attente</h4>
                            <p class="text-muted">Tous les travaux ont été traités !</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validateTp(tpId) {
    if (confirm('✅ Êtes-vous sûr de vouloir valider ce travail ?\n\nL\'étudiant recevra une notification de validation.')) {
        // Créer un formulaire dynamique pour envoyer la requête POST
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/evc/app/admin/tp/validate/${tpId}`;

        // Ajouter le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.content;
            form.appendChild(csrfInput);
        }

        // Ajouter le formulaire au body et le soumettre
        document.body.appendChild(form);
        form.submit();
    }
}

function rejectTp(tpId) {
    // Rediriger vers la page de détails pour saisir la raison du rejet
    window.location.href = `/evc/app/admin/tp/view/${tpId}`;
}

function deleteTp(tpId) {
    if (confirm('❌ Êtes-vous sûr de vouloir supprimer définitivement ce travail ?\n\n⚠️ Cette action est irréversible !')) {
        // Créer un formulaire dynamique pour envoyer la requête DELETE
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/evc/app/admin/tp/delete/${tpId}`;

        // Ajouter le token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.content;
            form.appendChild(csrfInput);
        }

        // Ajouter la méthode DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        // Ajouter le formulaire au body et le soumettre
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

<style>
.table tbody tr {
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.btn-group .btn {
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
}
</style>
@endsection
