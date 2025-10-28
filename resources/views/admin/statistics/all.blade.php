@extends('layouts.admin')

@section('title', 'Toutes les Statistiques - EVC')

@push('styles')
<style>
/* Styles modernes pour la page des statistiques */
.page-header {
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
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
}

.stats-section {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.section-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: white;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: #007bff;
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
    height: 100%;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
    opacity: 0.9;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.95rem;
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
    display: inline-block;
}

.btn-stat:hover {
    background: rgba(255,255,255,0.3);
    color: white;
    text-decoration: none;
}

.back-button {
    background: linear-gradient(45deg, #6c757d, #5a6268);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-block;
}

.back-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    color: white;
    text-decoration: none;
}

@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
    }

    .page-title {
        font-size: 1.5rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-chart-bar text-primary me-2"></i>
                    Toutes les Statistiques
                </h1>
                <p class="page-subtitle mb-0">Vue d'ensemble complète de toutes les statistiques de la plateforme</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="back-button">
                <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
            </a>
        </div>
    </div>

    <!-- Statistiques des Étudiants par Formation -->
    <div class="stats-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title mb-0">
                <i class="fas fa-user-graduate"></i>
                Étudiants par Formation
            </h2>
            @php
                $total_etudiants_formations = ($stats['students_design_graphique'] ?? 0) + ($stats['students_community_management'] ?? 0) + ($stats['students_gestion_informatique'] ?? 0) + ($stats['students_intelligence_artificielle'] ?? 0);
            @endphp
            <span class="badge" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); font-size: 1.2rem; padding: 0.6rem 1.2rem;">
                <i class="fas fa-users me-2"></i>Total: {{ number_format($total_etudiants_formations) }} étudiants
            </span>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <div class="text-center">
                        <i class="fas fa-palette stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['students_design_graphique'] ?? '0' }}</h3>
                        <p class="stat-label">Étudiants Design Graphique</p>
                        <a href="{{ route('admin.students.index', ['formation' => 'design_graphique']) }}" class="btn-stat">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);">
                    <div class="text-center">
                        <i class="fas fa-users-cog stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['students_community_management'] ?? '0' }}</h3>
                        <p class="stat-label">Étudiants CM</p>
                        <a href="{{ route('admin.students.index', ['formation' => 'community_management']) }}" class="btn-stat">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                    <div class="text-center">
                        <i class="fas fa-server stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['students_gestion_informatique'] ?? '0' }}</h3>
                        <p class="stat-label">Étudiants Gestion Informatique</p>
                        <a href="{{ route('admin.students.index', ['formation' => 'gestion_informatique']) }}" class="btn-stat">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);">
                    <div class="text-center">
                        <i class="fas fa-brain stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['students_intelligence_artificielle'] ?? '0' }}</h3>
                        <p class="stat-label">Étudiants Intelligence Artificielle</p>
                        <a href="{{ route('admin.students.index', ['formation' => 'intelligence_artificielle']) }}" class="btn-stat">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ressources et Contenus -->
    <div class="stats-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title mb-0">
                <i class="fas fa-book"></i>
                Ressources et Contenus
            </h2>
            @php
                $total_ressources = ($stats['total_bibliotheque_documents'] ?? 0) + ($stats['total_events'] ?? 0) + ($stats['total_actualites'] ?? 0);
            @endphp
            <span class="badge" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%); font-size: 1.2rem; padding: 0.6rem 1.2rem;">
                <i class="fas fa-folder me-2"></i>Total: {{ number_format($total_ressources) }} items
            </span>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #4fc3f7 100%);">
                    <div class="text-center">
                        <i class="fas fa-book-open stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_bibliotheque_documents'] ?? '0' }}</h3>
                        <p class="stat-label">Bibliothèque (Documents)</p>
                        <a href="{{ route('admin.bibliotheque.index') }}" class="btn-stat">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%);">
                    <div class="text-center">
                        <i class="fas fa-newspaper stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_actualites'] ?? '0' }}</h3>
                        <p class="stat-label">Actualités</p>
                        <a href="#" class="btn-stat" onclick="alert('Fonctionnalité en développement'); return false;">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #29b6f6 0%, #4fc3f7 100%);">
                    <div class="text-center">
                        <i class="fas fa-calendar-alt stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_events'] ?? '0' }}</h3>
                        <p class="stat-label">Évènements</p>
                        <a href="#" class="btn-stat" onclick="alert('Fonctionnalité en développement'); return false;">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gestion Financière et Administrative -->
    <div class="stats-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title mb-0">
                <i class="fas fa-coins"></i>
                Gestion Financière et Administrative
            </h2>
            @php
                $total_admin = ($stats['total_payments'] ?? 0) + ($stats['total_reports'] ?? 0) + ($stats['total_pre_inscriptions'] ?? 0) + ($stats['total_admins'] ?? 0);
            @endphp
            <span class="badge" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); font-size: 1.2rem; padding: 0.6rem 1.2rem;">
                <i class="fas fa-clipboard-list me-2"></i>Total: {{ number_format($total_admin) }} items
            </span>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);">
                    <div class="text-center">
                        <i class="fas fa-credit-card stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_payments'] ?? '0' }}</h3>
                        <p class="stat-label">Paiements</p>
                        <a href="#" class="btn-stat" onclick="alert('Fonctionnalité en développement'); return false;">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <div class="text-center">
                        <i class="fas fa-file-chart-line stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_reports'] ?? '0' }}</h3>
                        <p class="stat-label">Rapports</p>
                        <a href="#" class="btn-stat" onclick="alert('Fonctionnalité en développement'); return false;">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #ff9800 0%, #ff6f00 100%);">
                    <div class="text-center">
                        <i class="fas fa-user-clock stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_pre_inscriptions'] ?? '0' }}</h3>
                        <p class="stat-label">Pré-inscris</p>
                        <a href="#" class="btn-stat" onclick="alert('Fonctionnalité en développement'); return false;">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #29b6f6 0%, #1e3c72 100%);">
                    <div class="text-center">
                        <i class="fas fa-user-shield stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_admins'] ?? '0' }}</h3>
                        <p class="stat-label">Admins</p>
                        <a href="{{ route('admin.statistics.detail', 'total-admins') }}" class="btn-stat">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Générales -->
    <div class="stats-section">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title mb-0">
                <i class="fas fa-chart-pie"></i>
                Statistiques Générales
            </h2>
            @php
                $total_general = ($stats['total_students'] ?? 0) + ($stats['total_formations'] ?? 0) + ($stats['total_projects'] ?? 0) + ($stats['total_tp'] ?? 0);
            @endphp
            <span class="badge" style="background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%); font-size: 1.2rem; padding: 0.6rem 1.2rem;">
                <i class="fas fa-chart-line me-2"></i>Total: {{ number_format($total_general) }} items
            </span>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                    <div class="text-center">
                        <i class="fas fa-users stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_students'] ?? '0' }}</h3>
                        <p class="stat-label">Total Étudiants</p>
                        <a href="{{ route('admin.students.index') }}" class="btn-stat">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);">
                    <div class="text-center">
                        <i class="fas fa-graduation-cap stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_formations'] ?? '0' }}</h3>
                        <p class="stat-label">Formations</p>
                        <a href="{{ route('admin.formations.index') }}" class="btn-stat">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                    <div class="text-center">
                        <i class="fas fa-project-diagram stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_projects'] ?? '0' }}</h3>
                        <p class="stat-label">Projets</p>
                        <a href="#" class="btn-stat" onclick="alert('Fonctionnalité en développement'); return false;">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card text-white" style="background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);">
                    <div class="text-center">
                        <i class="fas fa-tasks stat-icon"></i>
                        <h3 class="stat-number">{{ $stats['total_tp'] ?? '0' }}</h3>
                        <p class="stat-label">Travaux Pratiques</p>
                        <a href="#" class="btn-stat" onclick="alert('Fonctionnalité en développement'); return false;">
                            <i class="fas fa-eye me-1"></i>Voir la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Récapitulatif Général -->
    <div class="stats-section" style="background: linear-gradient(135deg, rgba(30,60,114,0.3) 0%, rgba(42,82,152,0.3) 100%); border: 2px solid rgba(255,255,255,0.3);">
        <h2 class="section-title text-center mb-4">
            <i class="fas fa-chart-bar"></i>
            Récapitulatif Général
        </h2>
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <div class="text-white">
                    <i class="fas fa-graduation-cap fa-3x mb-3" style="opacity: 0.8;"></i>
                    <h2 class="fw-bold mb-2">{{ number_format($total_etudiants_formations) }}</h2>
                    <p class="mb-0" style="opacity: 0.9; font-size: 1.1rem;">Étudiants Formations</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="text-white">
                    <i class="fas fa-book-open fa-3x mb-3" style="opacity: 0.8;"></i>
                    <h2 class="fw-bold mb-2">{{ number_format($total_ressources) }}</h2>
                    <p class="mb-0" style="opacity: 0.9; font-size: 1.1rem;">Ressources & Contenus</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="text-white">
                    <i class="fas fa-clipboard-list fa-3x mb-3" style="opacity: 0.8;"></i>
                    <h2 class="fw-bold mb-2">{{ number_format($total_admin) }}</h2>
                    <p class="mb-0" style="opacity: 0.9; font-size: 1.1rem;">Gestion Administrative</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="text-white">
                    <i class="fas fa-chart-line fa-3x mb-3" style="opacity: 0.8;"></i>
                    @php
                        $total_global = $total_etudiants_formations + $total_ressources + $total_admin + $total_general;
                    @endphp
                    <h2 class="fw-bold mb-2">{{ number_format($total_global) }}</h2>
                    <p class="mb-0" style="opacity: 0.9; font-size: 1.1rem;">Total Général</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bouton de retour en bas -->
    <div class="text-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="back-button">
            <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
        </a>
    </div>
</div>
@endsection
