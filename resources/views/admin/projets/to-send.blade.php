@extends('layouts.admin')

@section('title', 'Envoyer un Projet')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
@endpush

@section('content')

<div class="interactive-dashboard-form">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 style="color: var(--form-text); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-project-diagram me-3"></i>
                Envoyer un Projet aux Étudiants
            </h1>
            <p style="color: var(--form-text-muted); margin: 0;">
                Assignez un nouveau projet à vos étudiants
            </p>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_students'] }}</div>
                    <div class="stat-label">Total Étudiants</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4fc3f7, #29b6f6);">
                <div class="stat-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['design_graphique'] }}</div>
                    <div class="stat-label">Design Graphique</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800, #fb8c00);">
                <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['community_management'] }}</div>
                    <div class="stat-label">Community Management</div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #26c6da, #00acc1);">
                <div class="stat-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['gestion_informatique'] }}</div>
                    <div class="stat-label">Gestion Informatique</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message temporaire -->
    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="form-card-body text-center py-5">
                    <i class="fas fa-tools fa-5x mb-4" style="color: var(--form-primary);"></i>
                    <h3 style="color: var(--form-text); margin-bottom: 1rem;">
                        Module en cours de développement
                    </h3>
                    <p style="color: var(--form-text-muted); margin-bottom: 2rem;">
                        La fonctionnalité d'envoi de projets aux étudiants sera bientôt disponible.
                    </p>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        Retour au tableau de bord
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .stat-card {
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
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
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }
</style>
@endpush
