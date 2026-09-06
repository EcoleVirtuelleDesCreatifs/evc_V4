@extends('layouts.admin')

@section('title', 'Certification - Éligibilité')

@push('styles')
<style>
    body { background: #0f172a; }
    .certif-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 20px;
        padding: 2rem 2.5rem;
        color: #fff;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(124, 58, 237, 0.3);
    }
    .stat-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        color: #fff;
        margin-bottom: 1.5rem;
    }
    .stat-value { font-size: 1.8rem; font-weight: 700; }
    .stat-label { color: #94a3b8; font-size: 0.85rem; }
    .students-table {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }
    .table { color: #fff; margin-bottom: 0; }
    .table thead { background: rgba(124, 58, 237, 0.1); }
    .table tbody tr { border-bottom: 1px solid #334155; transition: background 0.2s; }
    .table tbody tr:hover { background: rgba(124, 58, 237, 0.05); }
    .badge-status {
        padding: 0.35rem 0.7rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .filter-btn {
        border: 1px solid #475569;
        color: #cbd5e1;
        background: #1e293b;
        border-radius: 8px;
        padding: 0.45rem 0.9rem;
        text-decoration: none;
        display: inline-block;
        margin: 0 0.25rem 0.5rem 0;
    }
    .filter-btn:hover { background: #334155; color: #fff; }
    .filter-btn.active { background: #7c3aed; border-color: #7c3aed; color: #fff; }
    .avatar { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; margin-right: 0.5rem; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="certif-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-award me-2"></i>Certification - Éligibilité</h1>
            <p class="mb-0 opacity-75">Analyse et validation de l'éligibilité des étudiants</p>
        </div>
        <a href="{{ route('admin.certification-eligibility.index', ['filter' => 'all']) }}" class="btn btn-light text-nowrap">
            <i class="fas fa-sync me-1"></i> Actualiser
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-value">{{ $stats['total'] }}</div>
                <div class="stat-label"><i class="fas fa-users me-1"></i>Analysés</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-value" style="color:#fbbf24">{{ $stats['pre_eligible'] }}</div>
                <div class="stat-label"><i class="fas fa-user-check me-1"></i>Pré-éligibles</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-value" style="color:#10b981">{{ $stats['eligible_confirmed'] }}</div>
                <div class="stat-label"><i class="fas fa-award me-1"></i>Éligibles confirmés</div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-value" style="color:#f43f5e">{{ $stats['not_eligible'] }}</div>
                <div class="stat-label"><i class="fas fa-user-times me-1"></i>Non éligibles</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center mb-3">
                @foreach([
                    'all' => 'Tous',
                    'pre_eligible' => 'Pré-éligibles',
                    'eligible_confirmed' => 'Éligibles confirmés',
                    'not_eligible' => 'Non éligibles',
                    'reviewing' => 'En vérification',
                    'payment_incomplete' => 'Paiement incomplet',
                    'projects_missing' => 'Moins de 50 projets',
                    'report_missing' => 'Rapport manquant',
                    'portfolio_missing' => 'Portfolio manquant',
                    'studio_unverified' => 'Studio Creative non vérifié',
                ] as $key => $label)
                    <a href="{{ route('admin.certification-eligibility.index', ['filter' => $key, 'q' => $q]) }}" class="filter-btn {{ $filter === $key ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.certification-eligibility.index') }}" method="GET" class="d-flex gap-2" style="max-width: 500px;">
                <input type="hidden" name="filter" value="{{ $filter }}">
                <input type="text" name="q" class="form-control" placeholder="Nom, prénom, matricule, email" value="{{ $q }}">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                @if(filled($q))
                    <a href="{{ route('admin.certification-eligibility.index', ['filter' => $filter]) }}" class="btn btn-outline-light"><i class="fas fa-times"></i></a>
                @endif
            </form>
        </div>
    </div>

    <div class="students-table p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Matricule</th>
                        <th>Projets / TP</th>
                        <th>Paiement</th>
                        <th>Rapport</th>
                        <th>Portfolio</th>
                        <th>Studio Creative</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        @php
                            $student = $item['student'];
                            $pre = $item['pre_eligible'];
                            $eligible = $item['eligible'];
                            $studio = $item['studio_creative_status'];
                            $adminStatus = $item['admin_status'];
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $student->profile_photo_url }}" alt="" class="avatar">
                                    <div>
                                        <div class="fw-semibold">{{ $student->full_name }}</div>
                                        <div class="small text-muted">{{ $student->email }}</div>
                                        <div class="small text-muted">{{ $item['formation_label'] }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $student->student_id }}</td>
                            <td>
                                <span class="{{ $item['projects_ok'] ? 'text-success' : 'text-danger' }}">
                                    {{ $item['projects_count'] }} / {{ $item['projects_required'] }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $item['payment']['ok'] ? 'text-success' : 'text-danger' }}">
                                    {{ $item['payment']['ok'] ? 'Soldé' : number_format($item['payment']['remaining'], 0, ',', ' ') . ' FCFA' }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $item['report_ok'] ? 'text-success' : 'text-danger' }}">
                                    {{ $item['report_ok'] ? 'Oui' : 'Non' }}
                                </span>
                            </td>
                            <td>
                                <span class="{{ $item['portfolio_ok'] ? 'text-success' : 'text-danger' }}">
                                    {{ $item['portfolio_ok'] ? 'Oui' : 'Non' }}
                                </span>
                            </td>
                            <td>
                                @if($studio === 'validated')
                                    <span class="badge-status bg-success">{{ $statusLabels['studio']['validated'] }}</span>
                                @elseif($studio === 'rejected')
                                    <span class="badge-status bg-danger">{{ $statusLabels['studio']['rejected'] }}</span>
                                @else
                                    <span class="badge-status bg-warning text-dark">{{ $statusLabels['studio']['pending'] }}</span>
                                @endif
                            </td>
                            <td>
                                @if($eligible)
                                    <span class="badge-status bg-success">Éligible confirmé</span>
                                @elseif($pre)
                                    <span class="badge-status bg-warning text-dark">Pré-éligible</span>
                                @else
                                    <span class="badge-status bg-danger">Non éligible</span>
                                @endif
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('admin.certification-eligibility.show', $student) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-edit"></i> Voir / Ajuster
                                </a>
                                <form method="POST" action="{{ route('admin.certification-eligibility.validate-all', $student) }}" class="d-inline" onsubmit="return confirm('Confirmer définitivement cette éligibilité ?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Tout valider">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.certification-eligibility.sync', $student) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-light">
                                        <i class="fas fa-sync"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                Aucun étudiant trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
