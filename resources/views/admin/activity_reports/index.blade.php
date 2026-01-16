@extends('layouts.admin')

@section('title', "Rapports d'activité")

@push('styles')
<style>
    /* Cartes de statistiques modernes (mêmes styles que /admin/formations) */
    .stat-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
    }

    .stat-card-primary {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    }

    .stat-card-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .stat-card-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .stat-label {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Rapports d'activité</h1>
        <a href="{{ route('admin.activity-reports.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Nouveau rapport</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Statistiques Globales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="stat-label">Total Rapports</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['published'] ?? 0 }}</h3>
                    <p class="stat-label">Publiés</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-file"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['draft'] ?? 0 }}</h3>
                    <p class="stat-label">Brouillons</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['ce_mois'] ?? 0 }}</h3>
                    <p class="stat-label">Ajoutés ce Mois</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste des Rapports</h5>
        </div>
        <div class="card-body">
            @if($reports->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-2 fw-bold text-white">Aucun rapport</div>
                    <div class="text-white-50">Clique sur “Nouveau rapport” pour publier le premier PDF.</div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Année</th>
                                <th>Statut</th>
                                <th>Téléchargements</th>
                                <th>Fichier</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td class="fw-semibold">{{ $report->title }}</td>
                                    <td>{{ $report->year ?? '—' }}</td>
                                    <td>
                                        @if($report->is_published)
                                            <span class="badge bg-success">Publié</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>{{ (int) ($report->download_count ?? 0) }}</td>
                                    <td>
                                        <a href="{{ route('admin.activity-reports.download', $report) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i>PDF
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <form method="POST" action="{{ route('admin.activity-reports.toggle', $report) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $report->is_published ? 'btn-warning' : 'btn-success' }}">
                                                    <i class="fas {{ $report->is_published ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                                    {{ $report->is_published ? 'Dépublier' : 'Publier' }}
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.activity-reports.edit', $report) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>

                                            <form method="POST" action="{{ route('admin.activity-reports.destroy', $report) }}" onsubmit="return confirm('Supprimer ce rapport ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
