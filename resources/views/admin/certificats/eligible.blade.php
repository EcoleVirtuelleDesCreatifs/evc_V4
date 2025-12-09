@extends('layouts.admin')

@section('title', 'Certificats - Étudiants Éligibles')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-certificate me-2"></i>Étudiants Éligibles aux Certificats
        </h1>
        <button class="btn btn-success">
            <i class="fas fa-file-export me-2"></i>Exporter la liste
        </button>
    </div>

    <!-- Cartes de statistiques -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total_eligible'] }}</h3>
                    <p class="stat-label">Étudiants Éligibles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $minTPRequired }}</h3>
                    <p class="stat-label">TP Minimum Requis</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $minProjectsRequired }}</h3>
                    <p class="stat-label">Projets Minimum Requis</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total_eligible'] }}</h3>
                    <p class="stat-label">Rapports Validés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des étudiants éligibles -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom Complet</th>
                            <th>Formation</th>
                            <th>Email</th>
                            <th class="text-center">TP Validés</th>
                            <th class="text-center">Projets Complétés</th>
                            <th class="text-center">Rapport</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($eligibleStudents as $index => $item)
                            @php
                                $student = $item['student'];
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2" style="width: 35px; height: 35px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 0.8rem;">
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
                                    @elseif($student->program == 'Gestion Informatique')
                                        <span class="badge" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                                            <i class="fas fa-server me-1"></i>{{ $student->program }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ $student->program ?? 'N/A' }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $student->email ?? 'N/A' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success" style="font-size: 0.9rem;">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $item['tp_validated'] }}/{{ $minTPRequired }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary" style="font-size: 0.9rem;">
                                        <i class="fas fa-project-diagram me-1"></i>
                                        {{ $item['projects_completed'] }}/{{ $minProjectsRequired }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($item['report'])
                                        <span class="badge bg-success">
                                            <i class="fas fa-file-check me-1"></i>Uploadé
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-file-times me-1"></i>Non uploadé
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.certificats.preview', $student->id) }}" class="btn btn-primary" title="Voir le certificat" target="_blank">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <a href="{{ route('admin.certificats.generate', $student->id) }}" class="btn btn-success" title="Télécharger le certificat">
                                            <i class="fas fa-download"></i> Télécharger
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-user-times fa-3x text-muted mb-3 d-block"></i>
                                    <p class="text-white mb-2 fs-5">Aucun étudiant éligible pour le moment</p>
                                    <small class="text-muted">Les étudiants apparaîtront ici une fois qu'ils auront rempli tous les critères d'éligibilité</small>
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

    .stat-card-success { color: #66bb6a; }
    .stat-card-success:hover { border-color: #66bb6a; }

    .stat-card-info { color: #4fc3f7; }
    .stat-card-info:hover { border-color: #4fc3f7; }

    .stat-card-warning { color: #ffa726; }
    .stat-card-warning:hover { border-color: #ffa726; }

    .stat-card-primary { color: #667eea; }
    .stat-card-primary:hover { border-color: #667eea; }

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
