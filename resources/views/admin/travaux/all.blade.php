@extends('layouts.admin')

@section('title', 'Tous les Travaux Pratiques')

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

<div class="container-fluid">
    <!-- En-tête moderne -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card border-0 shadow-lg header-gradient">
                <div class="card-body text-white p-5 position-relative">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="mb-3 fw-bold">
                                <i class="fas fa-file-alt me-3"></i>
                                Tous les Travaux Pratiques
                            </h1>
                            <p class="mb-0 fs-5 opacity-90">
                                Vue d'ensemble complète de tous les travaux soumis par les étudiants
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex flex-column align-items-end">
                                <span class="badge bg-white text-primary px-4 py-2 mb-2" style="font-size: 1.1rem;">
                                    <i class="fas fa-users me-2"></i>
                                    {{ $stats['total_students'] }} Étudiants
                                </span>
                                <span class="badge bg-white text-primary px-4 py-2" style="font-size: 1.1rem;">
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

    <!-- Statistiques par statut -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 fade-in-up" style="animation-delay: 0.1s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">En Attente</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['pending_tps'] }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-clock fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3 fade-in-up" style="animation-delay: 0.2s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #45a049 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Validés</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['validated_tps'] }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-check-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3 fade-in-up" style="animation-delay: 0.3s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #f44336 0%, #e53935 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Rejetés</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['rejected_tps'] }}</h2>
                        </div>
                        <div>
                            <i class="fas fa-times-circle fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par formation -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3 fade-in-up" style="animation-delay: 0.1s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Design Graphique</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['design_graphique'] }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-palette fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 fade-in-up" style="animation-delay: 0.2s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Community Management</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['community_management'] }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-users fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 fade-in-up" style="animation-delay: 0.3s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Gestion Informatique</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['gestion_informatique'] }}</h3>
                        </div>
                        <div>
                            <i class="fas fa-laptop-code fs-1 opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 fade-in-up" style="animation-delay: 0.4s;">
            <div class="card border-0 shadow-sm h-100 stat-card" style="background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Intelligence Artificielle</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['intelligence_artificielle'] }}</h3>
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
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h4 class="mb-0 fw-bold text-dark">
                                <i class="fas fa-list me-2 text-primary"></i>
                                Liste des Étudiants
                            </h4>
                        </div>
                        <div class="col-md-6 text-end">
                            <button class="btn btn-primary btn-sm" onclick="window.location.reload()">
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
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th class="px-4 py-3">Étudiant</th>
                                        <th class="text-center py-3">Formation</th>
                                        <th class="text-center py-3">Total TP</th>
                                        <th class="text-center py-3">En Attente</th>
                                        <th class="text-center py-3">Validés</th>
                                        <th class="text-center py-3">Rejetés</th>
                                        <th class="text-center py-3">Dernière Soumission</th>
                                        <th class="text-center py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentsTps as $student)
                                        <tr class="student-card">
                                            <td class="px-4 py-3">
                                                <div class="d-flex align-items-center">
                                                    @if($student['profile_photo'])
                                                        <img src="{{ asset($student['profile_photo']) }}"
                                                             alt="Photo de profil"
                                                             class="rounded-circle me-3"
                                                             style="width: 50px; height: 50px; object-fit: cover; border: 3px solid #1e3c72; box-shadow: 0 4px 12px rgba(30, 60, 114, 0.4);">
                                                    @else
                                                        <div class="avatar-circle me-3">
                                                            {{ strtoupper(substr($student['first_name'] ?? 'U', 0, 1)) }}{{ strtoupper(substr($student['last_name'] ?? 'N', 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">
                                                            {{ $student['first_name'] ?? 'Prénom' }} {{ $student['last_name'] ?? 'Nom' }}
                                                        </div>
                                                        <small class="text-muted">
                                                            <i class="fas fa-envelope me-1"></i>
                                                            {{ $student['user_email'] }}
                                                        </small>
                                                        @if($student['student_id'])
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-id-card me-1"></i>
                                                                {{ $student['student_id'] }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-primary">
                                                    {{ $student['program'] ?? 'Non spécifié' }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-secondary fs-6">
                                                    {{ $student['tps_count'] }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-warning text-dark fs-6">
                                                    {{ $student['pending_count'] }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-success fs-6">
                                                    {{ $student['validated_count'] }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge bg-danger fs-6">
                                                    {{ $student['rejected_count'] }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    {{ \Carbon\Carbon::parse($student['latest_submission'])->format('d/m/Y H:i') }}
                                                </small>
                                            </td>
                                            <td class="text-center align-middle">
                                                <button class="btn btn-sm btn-outline-primary"
                                                        type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#student-{{ $student['user_id'] }}"
                                                        aria-expanded="false">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Voir les TP
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="8" class="p-0 border-0">
                                                <div class="collapse" id="student-{{ $student['user_id'] }}">
                                                    <div class="p-4" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                                        <h6 class="mb-3 fw-bold">
                                                            <i class="fas fa-file-alt me-2 text-primary"></i>
                                                            Travaux de {{ $student['first_name'] }} {{ $student['last_name'] }}
                                                        </h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered bg-white">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Titre</th>
                                                                        <th class="text-center">Statut</th>
                                                                        <th class="text-center">Date de Soumission</th>
                                                                        <th class="text-center">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($student['tps'] as $tp)
                                                                        <tr>
                                                                            <td>
                                                                                <strong>{{ $tp->title ?? 'Sans titre' }}</strong>
                                                                                @if($tp->description)
                                                                                    <br>
                                                                                    <small class="text-muted">{!! Str::limit(strip_tags($tp->description), 60) !!}</small>
                                                                                @endif
                                                                            </td>
                                                                            <td class="text-center align-middle">
                                                                                @if($tp->status === 'assigned')
                                                                                    <span class="badge bg-info text-white">
                                                                                        <i class="fas fa-tasks me-1"></i>
                                                                                        À faire
                                                                                    </span>
                                                                                @elseif($tp->status === 'submitted')
                                                                                    <span class="badge bg-success">
                                                                                        <i class="fas fa-check-circle me-1"></i>
                                                                                        Déjà fait
                                                                                    </span>
                                                                                @elseif($tp->status === 'pending')
                                                                                    <span class="badge bg-warning text-dark">
                                                                                        <i class="fas fa-clock me-1"></i>
                                                                                        En attente
                                                                                    </span>
                                                                                @elseif($tp->status === 'validated')
                                                                                    <span class="badge bg-success">
                                                                                        <i class="fas fa-check-circle me-1"></i>
                                                                                        Validé
                                                                                    </span>
                                                                                @elseif($tp->status === 'rejected')
                                                                                    <span class="badge bg-danger">
                                                                                        <i class="fas fa-times-circle me-1"></i>
                                                                                        Rejeté
                                                                                    </span>
                                                                                @else
                                                                                    <span class="badge bg-secondary">{{ $tp->status }}</span>
                                                                                @endif
                                                                            </td>
                                                                            <td class="text-center align-middle">
                                                                                <small>{{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y H:i') }}</small>
                                                                            </td>
                                                                            <td class="text-center align-middle">
                                                                                <div class="btn-group btn-group-sm" role="group">
                                                                                    <a href="{{ route('admin.tp.view', $tp->id) }}"
                                                                                       class="btn btn-outline-primary"
                                                                                       title="Voir les détails">
                                                                                        <i class="fas fa-eye"></i>
                                                                                    </a>
                                                                                    <a href="{{ route('admin.tp.edit', $tp->id) }}"
                                                                                       class="btn btn-outline-warning"
                                                                                       title="Modifier">
                                                                                        <i class="fas fa-edit"></i>
                                                                                    </a>
                                                                                    <button type="button"
                                                                                            class="btn btn-outline-danger"
                                                                                            onclick="confirmDelete({{ $tp->id }}, '{{ $tp->title ?? 'ce TP' }}')"
                                                                                            title="Supprimer">
                                                                                        <i class="fas fa-trash"></i>
                                                                                    </button>
                                                                                </div>

                                                                                <!-- Formulaire de suppression caché -->
                                                                                <form id="delete-form-{{ $tp->id }}"
                                                                                      action="{{ route('admin.tp.delete', $tp->id) }}"
                                                                                      method="POST"
                                                                                      style="display: none;">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                </form>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
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
                            <i class="fas fa-inbox text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                            <h4 class="mt-3 text-muted">Aucun travail soumis</h4>
                            <p class="text-muted">Les étudiants n'ont pas encore soumis de travaux.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(tpId, tpTitle) {
    if (confirm(`⚠️ Êtes-vous sûr de vouloir supprimer le TP "${tpTitle}" ?\n\nCette action est irréversible et supprimera également tous les fichiers associés.`)) {
        document.getElementById('delete-form-' + tpId).submit();
    }
}
</script>

@endsection
