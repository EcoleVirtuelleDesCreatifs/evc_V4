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

    <!-- Carte de statistique -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Étudiants Non Éligibles</p>
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
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Formation</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Raison</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td>{{ $student->last_name ?? 'N/A' }}</td>
                                <td>{{ $student->first_name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $student->program ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $student->email ?? 'N/A' }}</td>
                                <td>{{ $student->phone ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-warning">Critères non remplis</span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-info" title="Voir le profil">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning" title="Voir les détails">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <p class="text-white mb-0">Tous les étudiants sont éligibles !</p>
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
