@extends('layouts.admin')

@section('title', 'Projets attribués - Design Graphique')

@push('styles')
<style>
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

    .stat-card-info {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    }

    .stat-card-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
    }

    .stat-card-cyan {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
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
        line-height: 1;
    }

    .stat-label {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .formation-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        display: block;
        height: 100%;
    }

    .formation-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    .formation-card.dg {
        border-top: 4px solid #667eea;
    }

    .formation-card.cm {
        border-top: 4px solid #f5576c;
    }

    .formation-card.dgcm {
        border-top: 4px solid #4facfe;
    }

    .formation-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: white;
    }

    .formation-card.dg .formation-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .formation-card.cm .formation-icon {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .formation-card.dgcm .formation-icon {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .formation-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .formation-count {
        font-size: 3rem;
        font-weight: 800;
        color: #667eea;
        margin-bottom: 0.5rem;
    }

    .formation-card.cm .formation-count {
        color: #f5576c;
    }

    .formation-card.dgcm .formation-count {
        color: #4facfe;
    }

    .formation-subtitle {
        color: #64748b;
        font-size: 0.9rem;
    }

    .status-badge {
        display: inline-block;
        padding: .35em .65em;
        font-size: .75em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
    }
    .status-badge.en_cours { background-color: #0dcaf0; color: #000; }
    .status-badge.termine { background-color: #ffc107; color: #000; }
    .status-badge.valide { background-color: #198754; }
    .status-badge.rejete { background-color: #dc3545; }
    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-white">
                <i class="fas fa-folder-open me-2" style="color: #667eea;"></i>
                Projets Design Graphique
            </h1>
            <p class="text-white-50 mb-0">Sélectionnez une formation pour voir les projets assignés</p>
        </div>
        <div>
            <a href="{{ route('admin.projets.design-graphique.to-send') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Attribuer un projet
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="stat-label">Total Projets</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-info">
                <div class="stat-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['en_cours'] ?? 0 }}</h3>
                    <p class="stat-label">En Cours</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['termine'] ?? 0 }}</h3>
                    <p class="stat-label">Terminés</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-thumbs-up"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['valide'] ?? 0 }}</h3>
                    <p class="stat-label">Validés</p>
                </div>
            </div>
        </div>

        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-danger">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['rejete'] ?? 0 }}</h3>
                    <p class="stat-label">Rejetés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Formation Cards -->
    <div class="row">
        <div class="col-md-4 mb-4">
            @php
                $dgProjects = $groupedAssignments['Design Graphique'] ?? collect();
                $dgCount = $dgProjects->flatten()->pluck('students')->flatten()->count();
            @endphp
            <a href="{{ route('admin.projets.design-graphique.assigned.dg') }}" class="formation-card dg">
                <div class="formation-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <h3 class="formation-title">Design Graphique</h3>
                <div class="formation-count">{{ $dgCount }}</div>
                <p class="formation-subtitle">étudiant(s) avec projets assignés</p>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            @php
                $cmProjects = $groupedAssignments['Community Management'] ?? collect();
                $cmCount = $cmProjects->flatten()->pluck('students')->flatten()->count();
            @endphp
            <a href="{{ route('admin.projets.design-graphique.assigned.cm') }}" class="formation-card cm">
                <div class="formation-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="formation-title">Community Management</h3>
                <div class="formation-count">{{ $cmCount }}</div>
                <p class="formation-subtitle">étudiant(s) avec projets assignés</p>
            </a>
        </div>

        <div class="col-md-4 mb-4">
            @php
                $dgcmProjects = $groupedAssignments['Design Graphique et Community Management'] ?? collect();
                $dgcmCount = $dgcmProjects->flatten()->pluck('students')->flatten()->count();
            @endphp
            <a href="{{ route('admin.projets.design-graphique.assigned.dgcm') }}" class="formation-card dgcm">
                <div class="formation-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3 class="formation-title">DG + Community Management</h3>
                <div class="formation-count">{{ $dgcmCount }}</div>
                <p class="formation-subtitle">étudiant(s) avec projets assignés</p>
            </a>
        </div>
    </div>

    @if($groupedAssignments->flatten()->flatten()->pluck('students')->flatten()->isEmpty())
        <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-body text-center py-5">
                <i class="fas fa-folder-open fa-3x mb-3 text-white-50"></i>
                <div class="text-white-50">Aucun projet assigné</div>
                <p class="text-white-50">Commencez par attribuer un projet aux étudiants</p>
            </div>
        </div>
    @endif
</div>
@endsection
