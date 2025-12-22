@extends('layouts.ki-admin')

@section('title', 'Mes Travaux Pratiques')
@section('page-title', 'Travaux Pratiques')

@section('content')
@php
    $currentModule = request()->segment(3);
    $routePrefix = $currentModule;
    $isCombinedProfile = $currentModule === 'design-graphique-cm';
@endphp

<style>
    /* Modern Design System - Bleu Theme */
    :root {
        --primary-blue: #1e3c72;
        --primary-orange: #4fc3f7;
        --gradient-primary: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        --gradient-blue: linear-gradient(135deg, #2a5298 0%, #4fc3f7 100%);
        --gradient-orange: linear-gradient(135deg, #2a5298 0%, #4fc3f7 100%);
        --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --gradient-warning: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        --gradient-danger: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        --bg-light: #f8fafc;
        --bg-card: #ffffff;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-light);
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        color: var(--text-dark);
        line-height: 1.6;
    }

    .container-fluid {
        padding: 2rem 1.5rem;
        max-width: 1400px;
        margin: 0 auto;
    }

    /* Hero Section */
    .hero-section {
        background: var(--gradient-primary);
        border-radius: 24px;
        padding: 3rem 2rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-xl);
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }

    .hero-section::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
        border-radius: 50%;
        animation: float 8s ease-in-out infinite reverse;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) scale(1); }
        50% { transform: translateY(-20px) scale(1.05); }
    }

    .hero-content {
        position: relative;
        z-index: 1;
        color: white;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.95;
        font-weight: 400;
    }

    .hero-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .hero-stat {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        padding: 1.25rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .hero-stat:hover {
        background: rgba(255, 255, 255, 0.25);
        transform: translateY(-4px);
    }

    .hero-stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        display: block;
        margin-bottom: 0.25rem;
    }

    .hero-stat-label {
        font-size: 0.85rem;
        opacity: 0.9;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    /* Stats Cards Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .stat-card {
        background: var(--bg-card);
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--shadow-sm);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-blue);
    }

    .stat-card.blue::before { background: var(--gradient-blue); }
    .stat-card.orange::before { background: var(--gradient-orange); }
    .stat-card.success::before { background: var(--gradient-success); }
    .stat-card.warning::before { background: var(--gradient-warning); }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: 1rem;
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .stat-card.blue .stat-icon {
        background: var(--gradient-blue);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .stat-card.orange .stat-icon {
        background: var(--gradient-orange);
        box-shadow: 0 4px 12px rgba(249, 115, 22, 0.3);
    }

    .stat-card.success .stat-icon {
        background: var(--gradient-success);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .stat-card.warning .stat-icon {
        background: var(--gradient-warning);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--text-dark);
        line-height: 1;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.95rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-progress {
        margin-top: 1.25rem;
        padding-top: 1.25rem;
        border-top: 1px solid var(--border-color);
    }

    .progress-bar-container {
        height: 8px;
        background: #e2e8f0;
        border-radius: 20px;
        overflow: hidden;
        margin-top: 0.75rem;
    }

    .progress-bar-fill {
        height: 100%;
        background: var(--gradient-primary);
        border-radius: 20px;
        transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .progress-text {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-top: 0.5rem;
        display: flex;
        justify-content: space-between;
    }

    /* Section Headers */
    .section-header {
        margin: 3rem 0 2rem;
    }

    .section-title {
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title .icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: var(--gradient-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.1rem;
    }

    .section-subtitle {
        font-size: 1rem;
        color: var(--text-muted);
        font-weight: 400;
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        background: var(--bg-card);
        padding: 0.75rem;
        border-radius: 16px;
        border: 1px solid var(--border-color);
    }

    .filter-tab {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        border: 2px solid transparent;
        background: transparent;
        color: var(--text-muted);
        font-weight: 600;
        font-size: 0.95rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tab:hover {
        background: var(--bg-light);
        color: var(--text-dark);
    }

    .filter-tab.active {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary-blue);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .filter-tab .count {
        background: rgba(255, 255, 255, 0.2);
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .filter-tab.active .count {
        background: rgba(255, 255, 255, 0.3);
    }

    /* TP Cards */
    .tp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .tp-card {
        background: var(--bg-card);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-sm);
    }

    .tp-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        border-color: var(--primary-blue);
    }

    .tp-card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 1px solid var(--border-color);
    }

    .tp-card-header.design {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(59, 130, 246, 0.05) 100%);
    }

    .tp-card-header.community {
        background: linear-gradient(135deg, rgba(249, 115, 22, 0.1) 0%, rgba(251, 146, 60, 0.05) 100%);
    }

    .tp-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1rem;
    }

    .tp-type-badge.design {
        background: var(--gradient-blue);
        color: white;
    }

    .tp-type-badge.community {
        background: var(--gradient-orange);
        color: white;
    }

    .tp-card-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .tp-card-body {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .tp-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-color);
    }

    .tp-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .tp-meta-item i {
        color: var(--primary-blue);
    }

    .tp-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .tp-status-badge.validated {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #065f46;
    }

    .tp-status-badge.pending {
        background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
        color: #7c2d12;
    }

    .tp-status-badge.rejected {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        color: #7f1d1d;
    }

    .tp-status-badge.assigned {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e3a8a;
    }

    .tp-card-actions {
        margin-top: auto;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem 1.75rem;
        border-radius: 12px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: var(--gradient-primary);
        color: white;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.4);
        color: white;
    }

    .btn-outline {
        background: transparent;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
    }

    .btn-outline:hover {
        background: var(--primary-blue);
        color: white;
    }

    .btn-block {
        width: 100%;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--bg-card);
        border-radius: 20px;
        border: 2px dashed var(--border-color);
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--text-muted);
    }

    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 0.5rem;
    }

    .empty-state-text {
        font-size: 1rem;
        color: var(--text-muted);
        margin-bottom: 2rem;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }

    .fade-in-delay-1 { animation-delay: 0.1s; opacity: 0; }
    .fade-in-delay-2 { animation-delay: 0.2s; opacity: 0; }
    .fade-in-delay-3 { animation-delay: 0.3s; opacity: 0; }
    .fade-in-delay-4 { animation-delay: 0.4s; opacity: 0; }

    /* Alert Messages */
    .alert {
        padding: 1.25rem 1.5rem;
        border-radius: 16px;
        margin-bottom: 1.5rem;
        border: 1px solid;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-color: #10b981;
        color: #065f46;
    }

    .alert-error {
        background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%);
        border-color: #ef4444;
        color: #7f1d1d;
    }

    .alert-info {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-color: #3b82f6;
        color: #1e3a8a;
    }

    .alert-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 3rem;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        list-style: none;
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: var(--bg-card);
        color: var(--text-dark);
        font-weight: 600;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .pagination .page-link:hover {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary-blue);
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary-blue);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .tp-grid {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }

        .hero-section {
            padding: 2rem 1.5rem;
        }

        .hero-title {
            font-size: 1.75rem;
        }

        .hero-subtitle {
            font-size: 0.95rem;
        }

        .hero-stats {
            grid-template-columns: repeat(2, 1fr);
        }

        .hero-stat-value {
            font-size: 2rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .stat-card {
            padding: 1.5rem;
        }

        .stat-value {
            font-size: 2rem;
        }

        .tp-grid {
            grid-template-columns: 1fr;
        }

        .filter-tabs {
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-tab {
            width: 100%;
            justify-content: center;
        }

        .section-title {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        .hero-stats {
            grid-template-columns: 1fr;
        }

        .hero-stat {
            padding: 1rem;
        }

        .hero-stat-value {
            font-size: 1.75rem;
        }

        .btn {
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
        }
    }
</style>


<!-- Hero Section -->
<div class="hero-section fade-in">
    <div class="hero-content">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
            <div>
                <h1 class="hero-title">
                    <i class="fas fa-laptop-code me-2"></i>
                    @if($isCombinedProfile)
                        Travaux Pratiques - Design & Community Management
                    @else
                        Mes Travaux Pratiques
                    @endif
                </h1>
                <p class="hero-subtitle">
                    @if($isCombinedProfile)
                        Développez vos compétences en Design Graphique et Community Management
                    @else
                        Suivez votre progression et gérez vos travaux pratiques
                    @endif
                </p>
            </div>
            <a href="{{ route($routePrefix . '.tp.ajouter') }}" class="btn btn-primary" style="padding: 0.75rem 1.5rem; font-size: 1rem; white-space: nowrap;">
                <i class="fas fa-plus-circle me-2"></i>
                Publier un TP
            </a>
        </div>

        @if(isset($statistiques))
        <div class="hero-stats">
            <div class="hero-stat">
                <span class="hero-stat-value">{{ $statistiques['tp_total'] }}</span>
                <span class="hero-stat-label">Total TP</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">{{ $validationStats['tp_valides'] ?? 0 }}</span>
                <span class="hero-stat-label">Validés</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">{{ $validationStats['tp_en_validation'] ?? 0 }}</span>
                <span class="hero-stat-label">En validation</span>
            </div>
            <div class="hero-stat">
                <span class="hero-stat-value">{{ $statistiques['progression_pourcentage'] }}%</span>
                <span class="hero-stat-label">Progression</span>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Stats Grid -->
@if(isset($statistiques))
<div class="stats-grid">
    <div class="stat-card blue fade-in fade-in-delay-1">
        <div class="stat-icon">
            <i class="fas fa-tasks"></i>
        </div>
        <div class="stat-value">{{ $statistiques['tp_realises'] }}</div>
        <div class="stat-label">TP Réalisés</div>
        <div class="stat-progress">
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: {{ $statistiques['progression_pourcentage'] }}%;"></div>
            </div>
            <div class="progress-text">
                <span>Progression</span>
                <span><strong>{{ $statistiques['progression_pourcentage'] }}%</strong></span>
            </div>
        </div>
    </div>

    <div class="stat-card success fade-in fade-in-delay-2">
        <div class="stat-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-value">{{ $validationStats['tp_valides'] ?? 0 }}</div>
        <div class="stat-label">TP Validés</div>
        <div class="stat-progress">
            @php
                $total = $statistiques['tp_realises'];
                $valides = $validationStats['tp_valides'] ?? 0;
                $tauxReussite = $total > 0 ? round(($valides / $total) * 100) : 0;
            @endphp
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: {{ $tauxReussite }}%; background: var(--gradient-success);"></div>
            </div>
            <div class="progress-text">
                <span>Taux de réussite</span>
                <span><strong>{{ $tauxReussite }}%</strong></span>
            </div>
        </div>
    </div>

    <div class="stat-card warning fade-in fade-in-delay-3">
        <div class="stat-icon">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-value">{{ $validationStats['tp_en_validation'] ?? 0 }}</div>
        <div class="stat-label">En Validation</div>
        <div class="stat-progress">
            <div class="progress-text" style="border-top: none; padding-top: 0; margin-top: 0.5rem;">
                <span>En attente de correction</span>
            </div>
        </div>
    </div>

    <div class="stat-card orange fade-in fade-in-delay-4">
        <div class="stat-icon">
            <i class="fas fa-list-check"></i>
        </div>
        <div class="stat-value">{{ $statistiques['tp_a_faire'] }}</div>
        <div class="stat-label">TP Restants</div>
        <div class="stat-progress">
            <div class="progress-text" style="border-top: none; padding-top: 0; margin-top: 0.5rem;">
                <span>À compléter</span>
            </div>
        </div>
    </div>
</div>
@endif

<!-- TP List Section -->
@if(isset($tps) && count($tps) > 0)
<div class="section-header fade-in">
    <h2 class="section-title">
        <span class="icon"><i class="fas fa-folder-open"></i></span>
        Vos Travaux Pratiques
    </h2>
    <p class="section-subtitle">
        @if($isCombinedProfile)
            {{ count($tps) }} travail(x) au total en Design Graphique et Community Management
        @else
            {{ count($tps) }} travail(x) disponible(s)
        @endif
    </p>
</div>

<!-- Filters -->
@if($isCombinedProfile)
<div class="filter-tabs fade-in">
    <button class="filter-tab active" data-filter="all">
        <i class="fas fa-th"></i>
        Tous
        <span class="count">{{ count($tps) }}</span>
    </button>
    <button class="filter-tab" data-filter="design">
        <i class="fas fa-pen-nib"></i>
        Design Graphique
        <span class="count">{{ $tps->where('formation', 'Design Graphique')->count() }}</span>
    </button>
    <button class="filter-tab" data-filter="community">
        <i class="fas fa-comments"></i>
        Community Management
        <span class="count">{{ $tps->where('formation', 'Community Management')->count() }}</span>
    </button>
</div>
@endif

<!-- TP Grid -->
@php
    $groupedTps = $tps->groupBy('formation');
    $orderedGroups = collect([]);
    if(isset($groupedTps['Design Graphique'])) $orderedGroups['Design Graphique'] = $groupedTps['Design Graphique'];
    if(isset($groupedTps['Community Management'])) $orderedGroups['Community Management'] = $groupedTps['Community Management'];
    foreach($groupedTps as $key => $group) {
        if($key !== 'Design Graphique' && $key !== 'Community Management') {
            $orderedGroups[$key] = $group;
        }
    }
@endphp

<div class="tp-grid">
    @foreach($tps as $index => $tp)
    @php
        $formationType = 'design';
        if(str_contains(strtolower($tp->formation ?? ''), 'community')) {
            $formationType = 'community';
        }
        $delay = ($index % 12) + 1;
    @endphp
    <div class="tp-card fade-in fade-in-delay-{{ min($delay, 4) }}" data-formation="{{ $formationType }}">
        <div class="tp-card-header {{ $formationType }}">
            <div class="tp-type-badge {{ $formationType }}">
                @if($formationType === 'design')
                    <i class="fas fa-pen-nib"></i>
                    Design Graphique
                @else
                    <i class="fas fa-comments"></i>
                    Community Management
                @endif
            </div>
            <h3 class="tp-card-title">{{ $tp->title ?? 'Sans titre' }}</h3>
        </div>
        <div class="tp-card-body">
            <div class="tp-meta">
                @if(!empty($tp->created_at))
                <div class="tp-meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y') }}</span>
                </div>
                @endif
                @if(isset($tp->files_count))
                <div class="tp-meta-item">
                    <i class="fas fa-paperclip"></i>
                    <span>{{ $tp->files_count }} fichier(s)</span>
                </div>
                @endif
            </div>

            @if(isset($tp->status))
            <div class="tp-status-badge {{ $tp->status }}">
                <i class="fas fa-{{ $tp->status == 'validated' ? 'check-circle' : ($tp->status == 'rejected' ? 'times-circle' : ($tp->status == 'assigned' ? 'tasks' : 'clock')) }}"></i>
                @if($tp->status == 'validated')
                    Validé
                @elseif($tp->status == 'rejected')
                    Rejeté
                @elseif($tp->status == 'assigned')
                    À faire
                @else
                    En validation
                @endif
            </div>
            @endif

            <div class="tp-card-actions">
                @if(!empty($tp->id))
                <a href="{{ route($routePrefix . '.tp.voir', ['id' => $tp->id, 'source' => $tp->source_table ?? 'tp']) }}" class="btn btn-primary btn-block no-fancybox">
                    <i class="fas fa-eye"></i>
                    Voir le détail
                </a>
                @else
                <button class="btn btn-outline btn-block" disabled>
                    <i class="fas fa-lock"></i>
                    Indisponible
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Pagination -->
@if($tps->hasPages())
<div class="pagination-container">
    <nav aria-label="Navigation des TP">
        <ul class="pagination">
            @if ($tps->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $tps->previousPageUrl() }}" rel="prev">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                </li>
            @endif

            @foreach ($tps->getUrlRange(1, $tps->lastPage()) as $page => $url)
                @if ($page == $tps->currentPage())
                    <li class="page-item active">
                        <span class="page-link">{{ $page }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach

            @if ($tps->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $tps->nextPageUrl() }}" rel="next">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </nav>
</div>
@endif

@else
<!-- Empty State -->
<div class="empty-state fade-in">
    <div class="empty-state-icon">
        <i class="fas fa-folder-open"></i>
    </div>
    <h3 class="empty-state-title">Aucun travail pratique</h3>
    <p class="empty-state-text">
        Vous n'avez pas encore de travaux pratiques assignés.<br>
        Ils apparaîtront ici dès qu'ils seront disponibles.
    </p>
    @php
        $dashboardRoute = 'dashboard.' . $routePrefix;
    @endphp
    <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="{{ route($routePrefix . '.tp.ajouter') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle"></i>
            Publier un TP
        </a>
        <a href="{{ route($dashboardRoute) }}" class="btn btn-outline">
            <i class="fas fa-home"></i>
            Retour au dashboard
        </a>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter functionality for combined profile
    const filterTabs = document.querySelectorAll('.filter-tab');
    const tpCards = document.querySelectorAll('.tp-card');

    filterTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const filter = this.dataset.filter;

            // Update active tab
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            // Filter cards
            tpCards.forEach(card => {
                const formation = card.dataset.formation;
                if (filter === 'all' || filter === formation) {
                    card.style.display = '';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Animate progress bars on load
    const progressBars = document.querySelectorAll('.progress-bar-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });

    // Add smooth scroll behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

@endsection
