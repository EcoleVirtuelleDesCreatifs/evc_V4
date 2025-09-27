@extends('layouts.admin')

@section('title', 'Statistiques des Étudiants')

@section('content')
<div class="container-fluid">


    <!-- Header -->
    <div class="page-header mb-4">
        <h1 class="page-title text-white">
            <i class="fas fa-graduation-cap text-primary me-2"></i>
            Statistiques des Étudiants
        </h1>
        <div class="quick-actions">
            <button class="btn btn-primary btn-sm" onclick="addStudent()">
                <i class="fas fa-user-plus me-1"></i>Ajouter
            </button>
            <button class="btn btn-success btn-sm" onclick="exportData()">
                <i class="fas fa-download me-1"></i>Exporter
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $data['main_kpi']['total_students'] ?? 0 }}">{{ $data['main_kpi']['total_students'] ?? 0 }}</h2>
                    <p class="mb-0">Total Étudiants</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-check fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $data['active_students'] ?? 0 }}">{{ $data['active_students'] ?? 0 }}</h2>
                    <p class="mb-0">Étudiants Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-warning bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-user-plus fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $data['new_this_month'] ?? 0 }}">{{ $data['new_this_month'] ?? 0 }}</h2>
                    <p class="mb-0">Nouveaux ce Mois</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-info bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold">{{ count($data['formations'] ?? []) }}</h2>
                    <p class="mb-0">Formations Actives</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formations -->
    <div class="card mb-4" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
        <div class="card-header" style="background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1);">
            <h5 class="text-white mb-0"><i class="fas fa-graduation-cap me-2"></i>Répartition par Formation</h5>
        </div>
        <div class="card-body">
            <div class="row">
                @if(isset($data['formations']) && count($data['formations']) > 0)
                    @foreach($data['formations'] as $formation)
                    <div class="col-xl-3 col-md-6 mb-3">
                        <div class="card text-white h-100" style="background: {{ $formation['gradient'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }}; border: none;">
                            <div class="card-body text-center p-4">
                                <i class="{{ $formation['icon'] ?? 'fas fa-graduation-cap' }} fa-3x mb-3 opacity-75"></i>
                                <h3 class="fw-bold mb-2" data-counter="{{ $formation['count'] ?? 0 }}">{{ $formation['count'] ?? 0 }}</h3>
                                <h6 class="mb-3">{{ $formation['name'] ?? 'Formation' }}</h6>
                                <span class="badge bg-white text-dark mb-3">{{ $formation['percentage'] ?? 0 }}% du total</span>
                                <div class="mt-2">
                                    <a href="{{ route('admin.students.by-formation', ['formation' => $formation['slug'] ?? 'default']) }}" 
                                       class="btn btn-outline-light btn-sm">
                                        <i class="fas fa-eye me-1"></i>Voir les étudiants
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-warning text-dark">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Aucune formation trouvée. Vérifiez les données.
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Table des Étudiants -->
    <div class="card" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
        <div class="card-header d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1);">
            <h5 class="text-white mb-0"><i class="fas fa-table me-2"></i>Liste des Étudiants Actifs</h5>
            <div class="text-white">
                <small>Total : {{ count($data['students'] ?? []) }} étudiants</small>
            </div>
        </div>
        <div class="card-body">
            @if(isset($data['students']) && count($data['students']) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-dark">
                        <thead class="table-primary">
                            <tr>
                                <th><i class="fas fa-user me-1"></i>Nom Complet</th>
                                <th><i class="fas fa-envelope me-1"></i>Email</th>
                                <th><i class="fas fa-graduation-cap me-1"></i>Formation</th>
                                <th><i class="fas fa-calendar me-1"></i>Date d'inscription</th>
                                <th><i class="fas fa-chart-line me-1"></i>Progression</th>
                                <th><i class="fas fa-check-circle me-1"></i>Statut</th>
                                <th><i class="fas fa-cogs me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data['students'] as $student)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            {{ strtoupper(substr($student['prenom'] ?? 'E', 0, 1) . substr($student['nom'] ?? 'V', 0, 1)) }}
                                        </div>
                                        <div>
                                            <strong>{{ $student['nom'] ?? 'Nom' }} {{ $student['prenom'] ?? 'Prénom' }}</strong>
                                            <small class="text-muted d-block">ID: {{ $student['id'] ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $student['email'] ?? '' }}" class="text-info text-decoration-none">
                                        {{ $student['email'] ?? 'email@evc.com' }}
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-primary px-2 py-1">
                                        {{ $student['formation'] ?? 'Design Graphique' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-light">
                                        {{ isset($student['created_at']) ? \Carbon\Carbon::parse($student['created_at'])->format('d/m/Y') : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 100px; height: 8px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $student['progression'] ?? 0 }}%" 
                                                 aria-valuenow="{{ $student['progression'] ?? 0 }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <small class="text-light">{{ $student['progression'] ?? 0 }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success px-2 py-1">
                                        <i class="fas fa-check me-1"></i>{{ $student['status'] ?? 'Actif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.students.profile', ['id' => $student['id'] ?? 1]) }}" 
                                           class="btn btn-sm btn-outline-info" title="Voir le profil">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-warning" 
                                                onclick="editStudent({{ $student['id'] ?? 1 }})" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-white">Aucun étudiant trouvé</h5>
                    <p class="text-muted">Les données des étudiants apparaîtront ici une fois disponibles.</p>
                    <button class="btn btn-primary" onclick="addStudent()">
                        <i class="fas fa-user-plus me-1"></i>
                        Ajouter le premier étudiant
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
/* Styles spécifiques pour les statistiques */
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.table-dark {
    --bs-table-bg: rgba(255, 255, 255, 0.05);
}

.table-primary {
    --bs-table-bg: rgba(13, 110, 253, 0.2);
}

.progress {
    background-color: rgba(255, 255, 255, 0.2);
}

.btn-group .btn {
    margin-right: 2px;
}

/* Animation pour les cartes */
.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .avatar-sm {
        width: 24px;
        height: 24px;
        font-size: 10px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Animations des compteurs
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initialisation des animations de compteurs...');
    
    const counters = document.querySelectorAll('[data-counter]');
    console.log('Compteurs trouvés:', counters.length);
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-counter'));
        console.log('Animation compteur vers:', target);
        
        let current = 0;
        const increment = target / 50;
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                counter.textContent = target;
                clearInterval(timer);
            } else {
                counter.textContent = Math.ceil(current);
            }
        }, 20);
    });
    
    // Animation terminée
    console.log('Animations des compteurs terminées');
});

function addStudent() {
    console.log('Redirection vers ajout étudiant...');
    window.location.href = "{{ route('admin.students.add') }}";
}

function exportData() {
    console.log('Export des données...');
    alert('Export en cours de développement...');
}

function editStudent(id) {
    console.log('Édition étudiant ID:', id);
    alert('Fonction d\'édition en cours de développement pour l\'étudiant ID: ' + id);
}
</script>
@endpush
@endsection
