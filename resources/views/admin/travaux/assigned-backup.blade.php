@extends('layouts.admin')

@section('title', 'TP Assignés aux Étudiants')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    /* Palette Bleue Admin */
    :root {
        --admin-blue-dark: #1e3c72;
        --admin-blue-light: #4fc3f7;
        --admin-orange: #ff9800;
        --admin-cyan: #26c6da;
        --admin-violet: #9c27b0;
    }

    /* Header avec dégradé Bleu */
    .admin-header {
        background: linear-gradient(135deg, var(--admin-blue-dark), #2a5298, var(--admin-blue-light));
        padding: 2.5rem;
        border-radius: 20px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(131, 58, 180, 0.3);
        animation: fadeInDown 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        position: relative;
        overflow: hidden;
    }

    .admin-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .instagram-header h1 {
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .admin-header p {
        opacity: 0.95;
        margin: 0;
        font-size: 1.05rem;
        position: relative;
        z-index: 1;
    }

    .icon-circle {
        width: 70px;
        height: 70px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        animation: pulse 2s infinite;
        position: relative;
        z-index: 1;
    }

    /* Cartes statistiques avec dégradés Bleu */
    .stat-card {
        background: white;
        border-radius: 18px;
        padding: 1.75rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        height: 100%;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.5s ease;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s ease;
    }

    .stat-card:hover::before {
        left: 100%;
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 35px rgba(30, 60, 114, 0.25);
        border-color: var(--admin-blue-light);
    }

    .stat-icon {
        width: 55px;
        height: 55px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        color: white;
        margin-bottom: 1rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 0.25rem;
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        font-size: 0.95rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Carte TP modernisée */
    .tp-card {
        background: white;
        border-radius: 18px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border-left: 5px solid var(--admin-blue-light);
        animation: fadeInUp 0.5s ease;
        position: relative;
        overflow: hidden;
    }

    .tp-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--admin-blue-dark), var(--admin-blue-light), var(--admin-cyan));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .tp-card:hover::after {
        transform: scaleX(1);
    }

    .tp-card:hover {
        transform: translateX(8px);
        box-shadow: 0 8px 30px rgba(79, 195, 247, 0.2);
    }

    .tp-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }

    .tp-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .tp-title i {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        padding: 0.5rem;
        border-radius: 10px;
        color: white;
        font-size: 1.2rem;
    }

    .tp-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.25rem;
        padding: 1rem;
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.05), rgba(79, 195, 247, 0.05));
        border-radius: 12px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        color: #475569;
        font-weight: 500;
    }

    .meta-item i {
        color: var(--admin-blue-light);
        font-size: 1.1rem;
    }

    .tp-description {
        color: #64748b;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: rgba(248, 250, 252, 0.8);
        border-radius: 10px;
        border-left: 3px solid var(--admin-blue-dark);
    }

    /* Badges étudiants modernisés */
    .students-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .student-badge {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, rgba(30, 60, 114, 0.08), rgba(79, 195, 247, 0.08));
        border: 2px solid rgba(79, 195, 247, 0.2);
        border-radius: 12px;
        font-size: 0.9rem;
        color: #1a202c;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .student-badge:hover {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 195, 247, 0.3);
    }

    .student-badge i {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    /* Badges de statut avec dégradés */
    .status-badge {
        padding: 0.6rem 1.25rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .status-assigned {
        background: linear-gradient(135deg, var(--admin-orange), #fb8c00);
        color: white;
    }

    .status-submitted {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }

    .status-validated {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    /* Boutons modernisés */
    .btn-admin {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        color: white;
        border: none;
        padding: 0.75rem 1.75rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
        text-decoration: none;
    }

    .btn-admin:hover {
        background: linear-gradient(135deg, #2a5298, var(--admin-cyan));
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .btn-outline-admin {
        border: 2px solid var(--admin-blue-light);
        color: var(--admin-blue-light);
        background: transparent;
        padding: 0.75rem 1.75rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-outline-admin:hover {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        border-color: var(--admin-blue-dark);
        color: white;
        transform: translateY(-3px);
    }

    .btn-danger-admin {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
        padding: 0.75rem 1.75rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-danger-admin:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        color: white;
    }

    /* Onglets filtres modernisés */
    .filter-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        padding: 0.5rem;
        background: white;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .filter-tab {
        padding: 0.85rem 1.75rem;
        background: transparent;
        border: none;
        border-radius: 30px;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tab:hover {
        color: var(--admin-blue-light);
        background: rgba(79, 195, 247, 0.1);
    }

    .filter-tab.active {
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        color: white;
        box-shadow: 0 4px 12px rgba(30, 60, 114, 0.3);
    }

    /* État vide amélioré */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-state i {
        font-size: 5rem;
        background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1.5rem;
        animation: pulse 2s infinite;
    }

    .empty-state h3 {
        color: #1a202c;
        font-weight: 700;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 2rem;
        font-size: 1.05rem;
    }

    /* Animations */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    /* Badge count */
    .count-badge {
        background: linear-gradient(135deg, var(--admin-orange), #fb8c00);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        margin-left: 0.5rem;
    }

    /* Blocs de formation */
    .formation-block {
        margin-bottom: 3rem;
        animation: fadeInUp 0.6s ease;
    }

    .formation-header {
        padding: 2rem;
        border-radius: 18px 18px 0 0;
        color: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .formation-header:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .formation-header:active {
        transform: translateY(0);
    }

    .formation-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    .formation-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .formation-title {
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .formation-subtitle {
        font-size: 1rem;
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
    }

    .formation-stats-mini {
        display: flex;
        gap: 1.5rem;
        flex-wrap: wrap;
    }

    .stat-mini {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 20px;
        backdrop-filter: blur(10px);
        font-weight: 600;
        font-size: 1rem;
    }

    .stat-mini i {
        font-size: 1.1rem;
    }

    .formation-content {
        background: #f8fafc;
        padding: 2rem;
        border-radius: 0 0 18px 18px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .formation-content .tp-card {
        background: white;
        margin-bottom: 1.5rem;
    }

    .formation-content .tp-card:last-child {
        margin-bottom: 0;
    }

    .formation-content.collapsed {
        display: none !important;
    }

    .toggle-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .toggle-icon i {
        transition: transform 0.3s ease;
    }

    .toggle-icon.rotated i {
        transform: rotate(180deg);
    }

    .formation-header:hover .toggle-icon {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .admin-header h1 {
            font-size: 1.75rem;
        }

        .tp-card {
            padding: 1.5rem;
        }

        .students-grid {
            grid-template-columns: 1fr;
        }

        .filter-tabs {
            justify-content: center;
        }

        .formation-header {
            padding: 1.5rem;
        }

        .formation-title {
            font-size: 1.5rem;
        }

        .formation-stats-mini {
            width: 100%;
            justify-content: center;
        }

        .formation-content {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')

<div class="container-fluid">


    <!-- Statistiques Bleues Admin -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--admin-blue-dark), #2a5298);">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="stat-number">{{ $stats['total'] }}</div>
                <div class="stat-label">Total TP Envoyés</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, var(--admin-orange), #fb8c00);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number">{{ $stats['assigned'] }}</div>
                <div class="stat-label">En Cours</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fbbf24, #f59e0b);">
                    <i class="fas fa-upload"></i>
                </div>
                <div class="stat-number">{{ $stats['submitted'] }}</div>
                <div class="stat-label">Soumis</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number">{{ $stats['validated'] }}</div>
                <div class="stat-label">Validés</div>
            </div>
        </div>
    </div>

    <!-- Filtres modernisés -->
    <div class="filter-tabs">
        <div class="filter-tab active" data-filter="all">
            <i class="fas fa-list"></i>
            Tous
            <span class="count-badge">{{ $stats['total'] }}</span>
        </div>
        <div class="filter-tab" data-filter="assigned">
            <i class="fas fa-clock"></i>
            En cours
            <span class="count-badge">{{ $stats['assigned'] }}</span>
        </div>
        <div class="filter-tab" data-filter="submitted">
            <i class="fas fa-upload"></i>
            Soumis
            <span class="count-badge">{{ $stats['submitted'] }}</span>
        </div>
        <div class="filter-tab" data-filter="validated">
            <i class="fas fa-check-circle"></i>
            Validés
            <span class="count-badge">{{ $stats['validated'] }}</span>
        </div>
    </div>

    <!-- Liste des TP groupés par formation -->
    <div class="row">
        <div class="col-12">
            @if($tpAssignmentsByFormation->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Aucun TP envoyé pour le moment</h3>
                    <p>Commencez par créer et envoyer des travaux pratiques à vos étudiants</p>
                    <a href="{{ route('admin.travaux.to-send') }}" class="btn-admin">
                        <i class="fas fa-plus"></i>
                        Envoyer un nouveau TP
                    </a>
                </div>
            @else
                @foreach($tpAssignmentsByFormation as $formation => $tps)
                    @php
                        // Couleurs par formation
                        $formationColors = [
                            'Design Graphique' => ['bg' => 'linear-gradient(135deg, #1e3c72, #2a5298)', 'icon' => 'fas fa-palette'],
                            'Community Management' => ['bg' => 'linear-gradient(135deg, #4fc3f7, #29b6f6)', 'icon' => 'fas fa-users'],
                            'Gestion Informatique' => ['bg' => 'linear-gradient(135deg, #ff9800, #fb8c00)', 'icon' => 'fas fa-laptop-code'],
                            'Intelligence Artificielle' => ['bg' => 'linear-gradient(135deg, #26c6da, #00acc1)', 'icon' => 'fas fa-brain'],
                        ];
                        
                        $formationStyle = $formationColors[$formation] ?? ['bg' => 'linear-gradient(135deg, #9c27b0, #7b1fa2)', 'icon' => 'fas fa-graduation-cap'];
                        $formationStats = $statsByFormation[$formation] ?? ['total' => 0, 'assigned' => 0, 'submitted' => 0, 'validated' => 0];
                    @endphp
                    
                    <!-- Bloc Formation -->
                    <div class="formation-block mb-5" data-formation="{{ $formation }}">
                        <!-- Header Formation Cliquable -->
                        <div class="formation-header" style="background: {{ $formationStyle['bg'] }};" onclick="toggleFormation('{{ str_replace(' ', '_', $formation) }}')" role="button" tabindex="0">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3 flex-grow-1">
                                    <div class="formation-icon">
                                        <i class="{{ $formationStyle['icon'] }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h2 class="formation-title">{{ $formation }}</h2>
                                        <p class="formation-subtitle">{{ $formationStats['total'] }} TP assigné(s) à {{ $tps->unique('student_id')->count() }} étudiant(s)</p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3 flex-wrap">
                                    <div class="formation-stats-mini">
                                        <div class="stat-mini">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $formationStats['assigned'] }}</span>
                                        </div>
                                        <div class="stat-mini">
                                            <i class="fas fa-upload"></i>
                                            <span>{{ $formationStats['submitted'] }}</span>
                                        </div>
                                        <div class="stat-mini">
                                            <i class="fas fa-check-circle"></i>
                                            <span>{{ $formationStats['validated'] }}</span>
                                        </div>
                                    </div>
                                    <div class="toggle-icon" id="toggle-{{ str_replace(' ', '_', $formation) }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- TP de cette formation groupés par titre -->
                        <div class="formation-content" id="content-{{ str_replace(' ', '_', $formation) }}" style="display: block;">
                            @php
                                $tpsByTitle = $tps->groupBy('title');
                            @endphp
                            
                            @foreach($tpsByTitle as $title => $tpItems)
                                <div class="tp-card" data-status="{{ $tpItems->first()->status }}">
                                    <div class="tp-card-header">
                                        <div>
                                            <div class="tp-title">
                                                <i class="fas fa-file-alt"></i>
                                                {{ $title }}
                                            </div>
                                            <div class="tp-meta">
                                                <div class="meta-item">
                                                    <i class="fas fa-calendar"></i>
                                                    <span>Envoyé le {{ \Carbon\Carbon::parse($tpItems->first()->created_at)->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="meta-item">
                                                    <i class="fas fa-clock"></i>
                                                    <span>À rendre le {{ \Carbon\Carbon::parse($tpItems->first()->deadline)->format('d/m/Y') }}</span>
                                                </div>
                                                <div class="meta-item">
                                                    <i class="fas fa-users"></i>
                                                    <span>{{ $tpItems->count() }} étudiant(s)</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <span class="status-badge status-{{ $tpItems->first()->status }}">
                                                @if($tpItems->first()->status === 'assigned')
                                                    <i class="fas fa-clock me-1"></i>En cours
                                                @elseif($tpItems->first()->status === 'submitted')
                                                    <i class="fas fa-upload me-1"></i>Soumis
                                                @elseif($tpItems->first()->status === 'validated')
                                                    <i class="fas fa-check-circle me-1"></i>Validé
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="tp-description">
                                        {!! \Str::limit(strip_tags($tpItems->first()->description), 200) !!}
                                    </div>

                                    <div class="students-grid">
                                        @foreach($tpItems->take(8) as $tp)
                                            <div class="student-badge">
                                                <i class="fas fa-user"></i>
                                                {{ $tp->student_first_name }} {{ $tp->student_last_name }}
                                            </div>
                                        @endforeach
                                        @if($tpItems->count() > 8)
                                            <div class="student-badge">
                                                <i class="fas fa-users"></i>
                                                +{{ $tpItems->count() - 8 }} autres étudiants
                                            </div>
                                        @endif
                                    </div>

                                    <div class="d-flex gap-3 mt-4 justify-content-between align-items-center flex-wrap">
                                        <div>
                                            <span style="color: #64748b; font-weight: 600; margin-right: 0.5rem;">
                                                <i class="fas fa-users me-2"></i>{{ $tpItems->count() }} étudiant(s) assigné(s)
                                            </span>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.travaux.assignment.detail', ['title' => urlencode($title)]) }}" class="btn-outline-admin">
                                                <i class="fas fa-eye"></i>
                                                Voir détails
                                            </a>
                                            <form action="{{ route('admin.travaux.assignment.delete', ['title' => urlencode($title)]) }}" method="POST" onsubmit="return confirm('⚠️ Voulez-vous vraiment supprimer ce TP pour tous les étudiants ({{ $tpItems->count() }} étudiant(s)) ?\n\nCette action est irréversible.')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger-admin">
                                                    <i class="fas fa-trash"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Fonction pour déplier/replier les sections de formation
function toggleFormation(formationId) {
    const content = document.getElementById('content-' + formationId);
    const toggleIcon = document.getElementById('toggle-' + formationId);
    
    if (content) {
        // Basculer l'affichage
        if (content.style.display === 'none') {
            content.style.display = 'block';
            toggleIcon.classList.remove('rotated');
        } else {
            content.style.display = 'none';
            toggleIcon.classList.add('rotated');
        }
    }
}

// Gestion du clavier pour l'accessibilité
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.formation-header').forEach(header => {
        header.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
});

// Gestion des filtres
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Retirer la classe active de tous les onglets
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));

        // Ajouter la classe active à l'onglet cliqué
        this.classList.add('active');

        const filter = this.dataset.filter;

        // Filtrer les items (note: les tp-card sont maintenant dans les blocs de formation)
        document.querySelectorAll('.tp-card').forEach(item => {
            if (filter === 'all') {
                item.style.display = 'block';
            } else {
                if (item.dataset.status === filter) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            }
        });
    });
});
</script>
@endpush
