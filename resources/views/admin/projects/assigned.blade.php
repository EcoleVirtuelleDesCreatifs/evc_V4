@extends('layouts.admin')

@section('title', 'Projets attribués - Design Graphique')

@section('content')
<style>
    .assigned-page {
        background: #f8f9fa;
        min-height: 100vh;
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

    .stats-row {
        margin-bottom: 3rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1e293b;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid py-5 assigned-page">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="mb-1" style="font-weight: 700; color: #1e293b;">
                <i class="fas fa-folder-open me-2" style="color: #667eea;"></i>
                Projets Design Graphique
            </h1>
            <p class="text-muted mb-0">Sélectionnez une formation pour voir les projets assignés</p>
        </div>
        <div>
            <a href="{{ route('admin.projets.design-graphique.to-send') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-plus me-2"></i>Attribuer un projet
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row stats-row">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total Projets</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['en_cours'] ?? 0 }}</div>
                <div class="stat-label">En Cours</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['termine'] ?? 0 }}</div>
                <div class="stat-label">Terminés</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-number">{{ $stats['valide'] ?? 0 }}</div>
                <div class="stat-label">Validés</div>
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
        <div class="text-center py-5">
            <div style="width: 120px; height: 120px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-inbox" style="font-size: 3rem; color: #94a3b8;"></i>
            </div>
            <h4 class="mb-2" style="color: #1e293b; font-weight: 700;">Aucun projet assigné</h4>
            <p class="text-muted">Commencez par attribuer un projet aux étudiants</p>
        </div>
    @endif
</div>
@endsection
