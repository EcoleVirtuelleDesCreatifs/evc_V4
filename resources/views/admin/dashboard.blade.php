@extends('layouts.admin')

@section('title', 'Dashboard Admin - EVC')

@push('styles')
<style>
/* Styles modernes uniformes pour toutes les pages admin */
.page-header {
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

/* Animation clignotante pour les badges "En ligne" */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.6;
        transform: scale(0.95);
    }
}

.pulse-badge {
    animation: pulse 2s ease-in-out infinite;
}

/* Indicateur en ligne sur l'avatar */
.online-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 12px;
    height: 12px;
    background: #4caf50;
    border: 2px solid white;
    border-radius: 50%;
    animation: pulse-dot 1.5s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% {
        box-shadow: 0 0 0 0 rgba(76, 175, 80, 0.7);
    }
    50% {
        box-shadow: 0 0 0 6px rgba(76, 175, 80, 0);
    }
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: rgba(255,255,255,0.7);
    font-size: 1.1rem;
    margin-bottom: 0;
}

.quick-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-start;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.btn-quick {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    font-size: 0.9rem;
}

.btn-quick:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    color: white;
    text-decoration: none;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    border-radius: 15px;
    padding: 1.5rem;
    color: white;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.stat-card.primary { background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); }
.stat-card.success { background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%); }
.stat-card.warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); }
.stat-card.danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); }
.stat-card.info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }
.stat-card.secondary { background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%); }

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.stat-number {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 1rem;
}

.btn-stat {
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.btn-stat:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    text-decoration: none;
}

.content-section {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1rem;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .page-header {
        padding: 1.5rem;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .quick-actions {
        justify-content: center;
    }
}

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

/* Quick Actions */
.quick-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
    flex-wrap: wrap;
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

    .quick-actions {
        justify-content: center;
        margin-top: 1rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="page-header mb-4">
        <h1 class="page-title text-white">
            <i class="fas fa-tachometer-alt text-primary me-2"></i>
            Dashboard Administrateur
        </h1>
        <div class="quick-actions">
            <button class="btn btn-success btn-sm" onclick="exportData()">
                <i class="fas fa-download me-1"></i>Exporter
            </button>
            <button class="btn btn-info btn-sm" onclick="generateReport()">
                <i class="fas fa-chart-line me-1"></i>Rapport
            </button>
        </div>
    </div>

    @if(session('admin_role') === 'super_admin')
    <!-- Section Activité de la Plateforme -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, rgba(30, 60, 114, 0.95) 0%, rgba(42, 82, 152, 0.95) 100%); border: none; box-shadow: 0 8px 32px rgba(0,0,0,0.3);">
                <div class="card-header border-0" style="background: rgba(255,255,255,0.1);">
                    <h5 class="text-white mb-0">
                        <i class="fas fa-chart-line me-2"></i>Activité de la Plateforme
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Total Étudiants -->
                        <div class="col-md-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.1); border-left: 4px solid #4fc3f7;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-white-50 small mb-1">Total Étudiants</div>
                                        <h3 class="text-white mb-0 fw-bold">{{ DB::table('students')->where('status', 'active')->count() }}</h3>
                                    </div>
                                    <div class="text-info" style="font-size: 2rem; opacity: 0.5;">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TP en Attente -->
                        <div class="col-md-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.1); border-left: 4px solid #4caf50;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-white-50 small mb-1">
                                            <i class="fas fa-clock text-warning me-1" style="font-size: 8px;"></i>
                                            TP en Attente
                                        </div>
                                        <h3 class="text-white mb-0 fw-bold">{{ DB::table('tp')->where('status', 'pending')->count() }}</h3>
                                    </div>
                                    <div class="text-success" style="font-size: 2rem; opacity: 0.5;">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TP Cette Semaine -->
                        <div class="col-md-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.1); border-left: 4px solid #ff9800;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-white-50 small mb-1">TP Cette Semaine</div>
                                        <h3 class="text-white mb-0 fw-bold">{{ DB::table('tp')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</h3>
                                    </div>
                                    <div class="text-warning" style="font-size: 2rem; opacity: 0.5;">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Projets Actifs -->
                        <div class="col-md-3">
                            <div class="p-3 rounded" style="background: rgba(255,255,255,0.1); border-left: 4px solid #9c27b0;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="text-white-50 small mb-1">Total Projets</div>
                                        @php
                                            try {
                                                $projectsCount = DB::table('design_projects')->count();
                                            } catch (\Exception $e) {
                                                $projectsCount = 0;
                                            }
                                        @endphp
                                        <h3 class="text-white mb-0 fw-bold">{{ $projectsCount }}</h3>
                                    </div>
                                    <div style="font-size: 2rem; opacity: 0.5; color: #9c27b0;">
                                        <i class="fas fa-project-diagram"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Étudiants En Ligne -->
                    <div class="mt-4">
                        <h6 class="text-white mb-3">
                            <i class="fas fa-users me-2"></i>Étudiants En Ligne
                            <span class="badge bg-success ms-2 pulse-badge">
                                <i class="fas fa-circle" style="font-size: 6px;"></i>
                                {{ DB::table('sessions')->whereNotNull('user_id')->distinct('user_id')->count('user_id') }}
                            </span>
                        </h6>
                        <div class="row g-2">
                            @php
                                $onlineStudents = DB::table('sessions')
                                    ->join('users', 'sessions.user_id', '=', 'users.id')
                                    ->leftJoin('students', 'users.id', '=', 'students.user_id')
                                    ->whereNotNull('sessions.user_id')
                                    ->whereNotNull('students.id')
                                    ->select('students.id', 'students.first_name', 'students.last_name', 'students.program', 'students.student_id', DB::raw('MAX(sessions.last_activity) as last_activity'))
                                    ->groupBy('students.id', 'students.first_name', 'students.last_name', 'students.program', 'students.student_id')
                                    ->orderBy('last_activity', 'desc')
                                    ->limit(8)
                                    ->get();
                            @endphp

                            @forelse($onlineStudents as $student)
                            <div class="col-md-6">
                                <div class="p-2 rounded d-flex align-items-center" style="background: rgba(255,255,255,0.05); border-left: 3px solid #4caf50;">
                                    <div class="me-3 position-relative">
                                        @php
                                            $avatarColor = 'linear-gradient(135deg, #4fc3f7, #29b6f6)';
                                            if(str_contains($student->program ?? '', 'Design')) {
                                                $avatarColor = 'linear-gradient(135deg, #1e3c72, #2a5298)';
                                            } elseif(str_contains($student->program ?? '', 'Community')) {
                                                $avatarColor = 'linear-gradient(135deg, #4caf50, #66bb6a)';
                                            } elseif(str_contains($student->program ?? '', 'Gestion')) {
                                                $avatarColor = 'linear-gradient(135deg, #ff9800, #fb8c00)';
                                            } elseif(str_contains($student->program ?? '', 'Intelligence')) {
                                                $avatarColor = 'linear-gradient(135deg, #9c27b0, #7b1fa2)';
                                            }
                                        @endphp
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                             style="width: 40px; height: 40px; background: {{ $avatarColor }};">
                                            {{ strtoupper(substr($student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'N', 0, 1)) }}
                                        </div>
                                        <span class="online-indicator"></span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="text-white small fw-bold">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </div>
                                        <div class="text-white-50" style="font-size: 0.75rem;">
                                            {{ $student->program ?? 'Formation non définie' }}
                                        </div>
                                    </div>
                                    <div>
                                        <span class="badge bg-success pulse-badge">
                                            <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                            En ligne
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center text-white-50 py-3">
                                    <i class="fas fa-user-slash me-2"></i>Aucun étudiant en ligne actuellement
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Dernières Activités -->
                    <div class="mt-4">
                        <h6 class="text-white mb-3">
                            <i class="fas fa-history me-2"></i>Dernières Activités
                        </h6>
                        <div class="row g-2">
                            @php
                                $recentActivities = DB::table('tp')
                                    ->join('users', 'tp.user_id', '=', 'users.id')
                                    ->leftJoin('students', 'users.id', '=', 'students.user_id')
                                    ->select('tp.created_at', 'tp.title', 'students.first_name', 'students.last_name', 'tp.status')
                                    ->orderBy('tp.created_at', 'desc')
                                    ->limit(5)
                                    ->get();
                            @endphp

                            @forelse($recentActivities as $activity)
                            <div class="col-12">
                                <div class="p-2 rounded d-flex align-items-center" style="background: rgba(255,255,255,0.05);">
                                    <div class="me-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" 
                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #4fc3f7, #29b6f6);">
                                            {{ strtoupper(substr($activity->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($activity->last_name ?? 'N', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="text-white small">
                                            <strong>{{ $activity->first_name }} {{ $activity->last_name }}</strong> a soumis un TP
                                        </div>
                                        <div class="text-white-50" style="font-size: 0.75rem;">
                                            {{ $activity->title }} • {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                                        </div>
                                    </div>
                                    <div>
                                        @if($activity->status === 'validated')
                                            <span class="badge bg-success">Validé</span>
                                        @elseif($activity->status === 'pending')
                                            <span class="badge bg-warning">En attente</span>
                                        @else
                                            <span class="badge bg-danger">Rejeté</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12">
                                <div class="text-center text-white-50 py-3">
                                    <i class="fas fa-inbox me-2"></i>Aucune activité récente
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- KPI Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-primary bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-users fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $stats['total_students_active'] ?? 0 }}">{{ number_format($stats['total_students_active'] ?? 0) }}</h2>
                    <p class="mb-0">Étudiants Actifs</p>
                </div>
            </div>
        </div>
        @if(session('admin_role') === 'super_admin')
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-success bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-graduation-cap fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $stats['total_formations'] ?? '8' }}">{{ $stats['total_formations'] ?? '8' }}</h2>
                    <p class="mb-0">Formations Disponibles</p>
                </div>
            </div>
        </div>
        @endif
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-info bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-project-diagram fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $stats['total_projects'] ?? '456' }}">{{ $stats['total_projects'] ?? '456' }}</h2>
                    <p class="mb-0">Projets Étudiants</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card bg-warning bg-gradient text-white h-100">
                <div class="card-body text-center">
                    <i class="fas fa-tasks fa-3x mb-3 opacity-75"></i>
                    <h2 class="fw-bold" data-counter="{{ $stats['total_tp'] ?? '2340' }}">{{ $stats['total_tp'] ?? '2,340' }}</h2>
                    <p class="mb-0">Travaux Pratiques</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Détaillées -->
    <div class="card mb-4" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
        <div class="card-header" style="background: rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.1);">
            <h5 class="text-white mb-0"><i class="fas fa-chart-bar me-2"></i>Statistiques Détaillées</h5>
        </div>
        <div class="card-body">
            <!-- Section: Étudiants par Formation -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-white mb-0"><i class="fas fa-graduation-cap me-2"></i>Étudiants par Formation</h6>
                    @php
                        $total_etudiants_formations = ($stats['students_design_graphique'] ?? 0) + ($stats['students_community_management'] ?? 0) + ($stats['students_gestion_informatique'] ?? 0) + ($stats['students_intelligence_artificielle'] ?? 0);
                    @endphp
                    <span class="badge" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); font-size: 1.1rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-users me-1"></i>Total: {{ number_format($total_etudiants_formations) }} étudiants
                    </span>
                </div>
            <div class="row">
                <!-- Étudiants Design Graphique -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-palette fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['students_design_graphique'] ?? '456' }}">{{ $stats['students_design_graphique'] ?? '456' }}</h3>
                            <h6 class="mb-3">Étudiants Design Graphique</h6>
                            <a href="{{ route('admin.students.index', ['formation' => 'design_graphique']) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Étudiants Community Management -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-users-cog fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['students_community_management'] ?? '298' }}">{{ $stats['students_community_management'] ?? '298' }}</h3>
                            <h6 class="mb-3">Étudiants CM</h6>
                            <a href="{{ route('admin.students.index', ['formation' => 'community_management']) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Étudiants Gestion Informatique -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-server fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['students_gestion_informatique'] ?? '124' }}">{{ $stats['students_gestion_informatique'] ?? '124' }}</h3>
                            <h6 class="mb-3">Étudiants Gestion Informatique</h6>
                            <a href="{{ route('admin.students.index', ['formation' => 'gestion_informatique']) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Étudiants Intelligence Artificielle -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-brain fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['students_intelligence_artificielle'] ?? '187' }}">{{ $stats['students_intelligence_artificielle'] ?? '187' }}</h3>
                            <h6 class="mb-3">Étudiants Intelligence Artificielle</h6>
                            <a href="{{ route('admin.students.index', ['formation' => 'intelligence_artificielle']) }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            </div>

            @if(session('admin_role') === 'super_admin')
            <!-- Section: Ressources & Contenus -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-white mb-0"><i class="fas fa-book me-2"></i>Ressources & Contenus</h6>
                    @php
                        $total_ressources = ($stats['total_bibliotheque_documents'] ?? 0) + ($stats['total_events'] ?? 0) + ($stats['total_actualites'] ?? 0);
                    @endphp
                    <span class="badge" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%); font-size: 1.1rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-folder me-1"></i>Total: {{ number_format($total_ressources) }} items
                    </span>
                </div>
            <div class="row">
                <!-- Bibliothèque -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #4fc3f7 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-book-open fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_bibliotheque_documents'] ?? '234' }}">{{ $stats['total_bibliotheque_documents'] ?? '234' }}</h3>
                            <h6 class="mb-3">Bibliothèque (Documents)</h6>
                            <a href="{{ route('admin.bibliotheque.index') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Évènements -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #29b6f6 0%, #4fc3f7 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-calendar-alt fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_events'] ?? '45' }}">{{ $stats['total_events'] ?? '45' }}</h3>
                            <h6 class="mb-3">Évènements</h6>
                            <a href="#" class="btn btn-sm btn-light" onclick="alert('Fonctionnalité en développement'); return false;">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Actualités -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-newspaper fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_actualites'] ?? '89' }}">{{ $stats['total_actualites'] ?? '89' }}</h3>
                            <h6 class="mb-3">Actualités</h6>
                            <a href="#" class="btn btn-sm btn-light" onclick="alert('Fonctionnalité en développement'); return false;">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

            </div>
            </div>
            @endif

            @if(in_array(session('admin_role'), ['super_admin', 'comptable']))
            <!-- Section: Gestion Administrative -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-white mb-0"><i class="fas fa-tasks me-2"></i>Gestion Administrative</h6>
                    @php
                        $total_admin = ($stats['total_payments'] ?? 0) + ($stats['total_reports'] ?? 0) + ($stats['total_pre_inscriptions'] ?? 0) + ($stats['total_admins'] ?? 0);
                    @endphp
                    <span class="badge" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); font-size: 1.1rem; padding: 0.5rem 1rem;">
                        <i class="fas fa-clipboard-list me-1"></i>Total: {{ number_format($total_admin) }} items
                    </span>
                </div>
            <div class="row">
                <!-- Paiements -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-credit-card fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_payments'] ?? '1247' }}">{{ $stats['total_payments'] ?? '1247' }}</h3>
                            <h6 class="mb-3">Paiements</h6>
                            <a href="#" class="btn btn-sm btn-light" onclick="alert('Fonctionnalité en développement'); return false;">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Rapports -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-file-chart-line fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_reports'] ?? '156' }}">{{ $stats['total_reports'] ?? '156' }}</h3>
                            <h6 class="mb-3">Rapports</h6>
                            <a href="#" class="btn btn-sm btn-light" onclick="alert('Fonctionnalité en développement'); return false;">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pré-inscris -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #ff9800 0%, #ff6f00 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-user-clock fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_pre_inscriptions'] ?? '342' }}">{{ $stats['total_pre_inscriptions'] ?? '342' }}</h3>
                            <h6 class="mb-3">Pré-inscris</h6>
                            <a href="#" class="btn btn-sm btn-light" onclick="alert('Fonctionnalité en développement'); return false;">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Admins -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card text-white h-100" style="background: linear-gradient(135deg, #29b6f6 0%, #1e3c72 100%); border: none;">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-user-shield fa-3x mb-3 opacity-75"></i>
                            <h3 class="fw-bold mb-2" data-counter="{{ $stats['total_admins'] ?? '8' }}">{{ $stats['total_admins'] ?? '8' }}</h3>
                            <h6 class="mb-3">Admins</h6>
                            <a href="{{ route('admin.statistics.detail', 'total-admins') }}" class="btn btn-sm btn-light">
                                <i class="fas fa-eye me-1"></i>Voir plus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            @endif

            @if(session('admin_role') === 'super_admin')
            <!-- Récapitulatif Général -->
            <div class="mt-4 p-4" style="background: linear-gradient(135deg, rgba(30,60,114,0.3) 0%, rgba(42,82,152,0.3) 100%); border-radius: 15px; border: 2px solid rgba(255,255,255,0.2);">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="text-white">
                            <i class="fas fa-graduation-cap fa-2x mb-2" style="opacity: 0.8;"></i>
                            <h3 class="fw-bold mb-1">{{ number_format($total_etudiants_formations) }}</h3>
                            <p class="mb-0 small" style="opacity: 0.9;">Étudiants Formations</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-white">
                            <i class="fas fa-book-open fa-2x mb-2" style="opacity: 0.8;"></i>
                            <h3 class="fw-bold mb-1">{{ number_format($total_ressources) }}</h3>
                            <p class="mb-0 small" style="opacity: 0.9;">Ressources & Contenus</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-white">
                            <i class="fas fa-clipboard-list fa-2x mb-2" style="opacity: 0.8;"></i>
                            <h3 class="fw-bold mb-1">{{ number_format($total_admin) }}</h3>
                            <p class="mb-0 small" style="opacity: 0.9;">Gestion Administrative</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="text-white">
                            <i class="fas fa-chart-line fa-2x mb-2" style="opacity: 0.8;"></i>
                            @php
                                $total_global = $total_etudiants_formations + $total_ressources + $total_admin;
                            @endphp
                            <h3 class="fw-bold mb-1">{{ number_format($total_global) }}</h3>
                            <p class="mb-0 small" style="opacity: 0.9;">Total Général</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(session('admin_role') === 'super_admin')
            <!-- Bouton pour voir toutes les statistiques -->
            <div class="text-center mt-4">
                <a href="{{ route('admin.statistics.all') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-list me-2"></i>Voir Toutes les Statistiques
                </a>
            </div>
            @endif
        </div>
    </div>


</div>

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

    console.log('Animations des compteurs terminées');
});



function exportData() {
    console.log('Export des données...');
    alert('Export en cours de développement...');
}

function generateReport() {
    console.log('Génération de rapport...');
    alert('Génération de rapport en cours de développement...');
}

// ========== ÉTUDIANTS CONNECTÉS EN TEMPS RÉEL ==========
function loadOnlineStudents() {
    const loadingSpinner = document.getElementById('loadingSpinner');
    const refreshIcon = document.getElementById('refreshIcon');
    const tableBody = document.getElementById('onlineStudentsTable');
    const onlineCount = document.getElementById('onlineCount');
    const lastUpdate = document.getElementById('lastUpdate');
    
    if (!tableBody) return; // Si l'élément n'existe pas, sortir
    
    // Afficher le spinner
    if (loadingSpinner) loadingSpinner.style.display = 'inline-block';
    if (refreshIcon) refreshIcon.classList.add('fa-spin');
    
    fetch('{{ route("admin.api.online-students") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour le compteur
                if (onlineCount) onlineCount.textContent = data.total;
                
                // Mettre à jour l'heure
                if (lastUpdate) lastUpdate.textContent = data.timestamp;
                
                // Générer le HTML du tableau
                if (data.students.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-user-slash me-2"></i>Aucun étudiant connecté actuellement
                            </td>
                        </tr>
                    `;
                } else {
                    let html = '';
                    data.students.forEach(student => {
                        const initials = (student.first_name?.charAt(0) || '') + (student.last_name?.charAt(0) || '');
                        const fullName = `${student.first_name || ''} ${student.last_name || ''}`;
                        const lastActivity = new Date(student.last_activity);
                        const now = new Date();
                        const diffMinutes = Math.floor((now - lastActivity) / 60000);
                        const timeAgo = diffMinutes === 0 ? 'À l\'instant' : `Il y a ${diffMinutes} min`;
                        
                        // Couleur de l'avatar selon la formation
                        let avatarColor = 'bg-primary';
                        if (student.program?.includes('Design')) avatarColor = 'bg-info';
                        else if (student.program?.includes('Community')) avatarColor = 'bg-success';
                        else if (student.program?.includes('Gestion')) avatarColor = 'bg-warning';
                        else if (student.program?.includes('Intelligence')) avatarColor = 'bg-danger';
                        
                        html += `
                            <tr class="fade-in">
                                <td>
                                    <small class="text-success">
                                        <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                                        ${timeAgo}
                                    </small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm ${avatarColor} rounded-circle d-flex align-items-center justify-content-center me-2 text-white fw-bold">
                                            ${initials}
                                        </div>
                                        <div>
                                            <span class="text-white">${fullName}</span>
                                            <br>
                                            <small class="text-muted">${student.email || ''}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-white">${student.program || 'Non défini'}</td>
                                <td><small class="text-muted">${student.student_id || 'N/A'}</small></td>
                                <td><span class="badge bg-success"><i class="fas fa-circle me-1" style="font-size: 6px;"></i>En ligne</span></td>
                            </tr>
                        `;
                    });
                    tableBody.innerHTML = html;
                }
            } else {
                console.error('Erreur lors du chargement des étudiants:', data.error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>Erreur lors du chargement
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Erreur réseau:', error);
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Erreur de connexion
                    </td>
                </tr>
            `;
        })
        .finally(() => {
            // Masquer le spinner
            if (loadingSpinner) loadingSpinner.style.display = 'none';
            if (refreshIcon) refreshIcon.classList.remove('fa-spin');
        });
}

// Charger les étudiants au démarrage
if (document.getElementById('onlineStudentsTable')) {
    loadOnlineStudents();
    
    // Rafraîchir automatiquement toutes les 10 secondes
    setInterval(loadOnlineStudents, 10000);
}
</script>
@endpush
            <div class="stat-actions">
                <a href="{{ route('admin.statistics.detail', 'total-documents') }}" class="btn-stat">
                    <i class="fas fa-eye me-1"></i>Voir plus
                </a>
            </div>
        </div>
    </div>


</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'activité
    const activityCtx = document.getElementById('activityChart').getContext('2d');
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Connexions',
                data: [65, 78, 85, 92, 88, 45, 32],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: 'white'
                    }
                }
            },
            scales: {
                x: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                },
                y: {
                    ticks: {
                        color: 'rgba(255, 255, 255, 0.7)'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    }
                }
            }
        }
    });

    // Graphique formations
    const formationCtx = document.getElementById('formationChart').getContext('2d');
    new Chart(formationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Design Graphique', 'Community Management', 'Intelligence Artificielle', 'Gestion Informatique'],
            datasets: [{
                data: [45, 25, 20, 10],
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'white',
                        padding: 20
                    }
                }
            }
        }
    });
});
</script>


@endsection
