@extends('layouts.admin')

@section('title', 'Certificats - Étudiants Non Éligibles')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-times-circle me-2"></i>Étudiants Non Éligibles aux Certificats
        </h1>
        <button class="btn btn-warning">
            <i class="fas fa-file-export me-2"></i>Exporter la liste
        </button>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total_not_eligible'] }}</h3>
                    <p class="stat-label">Non Éligibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['missing_tp'] }}</h3>
                    <p class="stat-label">TP Manquants</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['missing_projects'] }}</h3>
                    <p class="stat-label">Projets Manquants</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-danger">
                <div class="stat-icon">
                    <i class="fas fa-file-times"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['missing_report'] }}</h3>
                    <p class="stat-label">Rapports Manquants</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants non éligibles -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom Complet</th>
                            <th>Formation</th>
                            <th class="text-center">TP</th>
                            <th class="text-center">Projets</th>
                            <th class="text-center">Rapport</th>
                            <th class="text-center">Manquant</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($notEligibleStudents as $index => $item)
                            @php
                                $student = $item['student'];
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #ff6b6b, #ee5a6f); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
                                            {{ strtoupper(substr($student->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'T', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $student->first_name }} {{ $student->last_name }}</div>
                                            <small class="text-muted">{{ $student->student_id ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($student->program == 'Design Graphique')
                                        <span class="badge" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                                            <i class="fas fa-palette me-1"></i>{{ $student->program }}
                                        </span>
                                    @elseif($student->program == 'Community Management')
                                        <span class="badge" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                                            <i class="fas fa-users me-1"></i>{{ $student->program }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $student->program ?? 'N/A' }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item['tp_eligible'])
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>{{ $item['tp_validated'] }}/{{ $minTPRequired }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>{{ $item['tp_validated'] }}/{{ $minTPRequired }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item['projects_eligible'])
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>{{ $item['projects_completed'] }}/{{ $minProjectsRequired }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times me-1"></i>{{ $item['projects_completed'] }}/{{ $minProjectsRequired }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item['report_uploaded'])
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Oui
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Non
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap gap-1 justify-content-center">
                                        @foreach($item['missing'] as $missing)
                                            <span class="badge bg-warning text-dark">{{ $missing }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.certificats.preview', $student->id) }}" class="btn btn-outline-primary" title="Aperçu du certificat" target="_blank">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3 d-block"></i>
                                    <p class="text-white mb-2 fs-5">Tous les étudiants actifs sont éligibles !</p>
                                    <small class="text-muted">Aucun étudiant n'a de critères manquants</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 2px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }

    .stat-card-warning { color: #ffa726; }
    .stat-card-warning:hover { border-color: #ffa726; }

    .stat-card-info { color: #4fc3f7; }
    .stat-card-info:hover { border-color: #4fc3f7; }

    .stat-card-primary { color: #667eea; }
    .stat-card-primary:hover { border-color: #667eea; }

    .stat-card-danger { color: #ff6b6b; }
    .stat-card-danger:hover { border-color: #ff6b6b; }

    .stat-icon {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: currentColor;
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.95rem;
        color: #94a3b8;
        margin: 0.5rem 0 0 0;
        font-weight: 500;
    }
</style>
@endpush
@endsection
