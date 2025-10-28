@extends('layouts.admin')

@section('title', 'Détails - Étudiants Actifs')

@section('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<!-- Admin Statistics CSS -->
<link href="{{ asset('css/admin-statistics.css') }}" rel="stylesheet">
<!-- Holographic Stats CSS -->
<link href="{{ asset('css/holographic-stats.css') }}" rel="stylesheet">
<!-- AOS Animation Library -->
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<!-- Custom Optimized CSS -->
<style>
/* Variables CSS pour la cohérence */
:root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --card-bg: rgba(255, 255, 255, 0.1);
    --card-border: rgba(255, 255, 255, 0.2);
    --text-primary: #ffffff;
    --text-secondary: rgba(255, 255, 255, 0.7);
    --shadow-light: 0 10px 30px rgba(0, 0, 0, 0.2);
    --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.3);
    --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --transition-bounce: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

/* KPI Principal Optimisé */
.main-kpi-card {
    position: relative;
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--card-border);
    border-radius: 20px;
    padding: 3rem 2rem;
    overflow: hidden;
    transition: var(--transition-smooth);
    cursor: pointer;
}

.main-kpi-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: var(--shadow-heavy);
}

.kpi-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-gradient);
    opacity: 0.8;
    z-index: 1;
}

.kpi-content {
    position: relative;
    z-index: 2;
}

.kpi-icon {
    font-size: 3rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
    animation: pulse 2s infinite;
}

.kpi-value {
    font-size: 4rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.kpi-label {
    font-size: 1.5rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
    font-weight: 600;
}

.growth-badge {
    background: rgba(40, 167, 69, 0.2);
    border: 1px solid rgba(40, 167, 69, 0.4);
    color: #28a745;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    backdrop-filter: blur(10px);
    animation: glow 2s ease-in-out infinite alternate;
}

/* Particules animées */
.kpi-particles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.particle {
    position: absolute;
    width: 4px;
    height: 4px;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.particle:nth-child(1) { top: 20%; left: 20%; animation-delay: 0s; }
.particle:nth-child(2) { top: 60%; left: 80%; animation-delay: 1s; }
.particle:nth-child(3) { top: 80%; left: 40%; animation-delay: 2s; }
.particle:nth-child(4) { top: 40%; left: 70%; animation-delay: 3s; }
.particle:nth-child(5) { top: 30%; left: 10%; animation-delay: 4s; }

/* Cartes de Formation Optimisées */
.enhanced-stat-card {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    transition: var(--transition-bounce);
    cursor: pointer;
    height: 280px;
}

.enhanced-stat-card:hover {
    transform: translateY(-15px) rotateY(5deg);
    box-shadow: var(--shadow-heavy);
}

.card-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1;
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    z-index: 2;
}

.card-content {
    position: relative;
    z-index: 3;
    padding: 2rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center;
}

.stat-icon-enhanced {
    position: relative;
    font-size: 3rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.icon-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    animation: glow-pulse 2s ease-in-out infinite;
}

.stat-value-enhanced {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.stat-label-enhanced {
    font-size: 1rem;
    color: var(--text-primary);
    margin-bottom: 1rem;
    font-weight: 600;
}

.stat-progress {
    width: 100%;
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.progress-bar-custom {
    height: 100%;
    background: linear-gradient(90deg, rgba(255, 255, 255, 0.8), rgba(255, 255, 255, 0.4));
    border-radius: 2px;
    transition: width 2s ease-out;
    animation: shimmer 2s infinite;
}

.btn-stat-enhanced {
    position: relative;
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: var(--text-primary);
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition-smooth);
    overflow: hidden;
}

.btn-stat-enhanced:hover {
    background: rgba(255, 255, 255, 0.3);
    color: var(--text-primary);
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-ripple {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.btn-stat-enhanced:active .btn-ripple {
    width: 300px;
    height: 300px;
}

/* Particules des cartes */
.card-particles {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 2;
}

.particle-small {
    position: absolute;
    width: 2px;
    height: 2px;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    animation: float-small 4s ease-in-out infinite;
}

.particle-small:nth-child(1) { top: 15%; left: 15%; animation-delay: 0s; }
.particle-small:nth-child(2) { top: 75%; left: 85%; animation-delay: 1.5s; }
.particle-small:nth-child(3) { top: 45%; left: 25%; animation-delay: 3s; }

/* Animations */
@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

@keyframes glow {
    from { box-shadow: 0 0 20px rgba(40, 167, 69, 0.4); }
    to { box-shadow: 0 0 30px rgba(40, 167, 69, 0.8); }
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-20px) rotate(120deg); }
    66% { transform: translateY(10px) rotate(240deg); }
}

@keyframes float-small {
    0%, 100% { transform: translateY(0px); opacity: 0.4; }
    50% { transform: translateY(-15px); opacity: 0.8; }
}

@keyframes glow-pulse {
    0%, 100% { opacity: 0.3; transform: translate(-50%, -50%) scale(1); }
    50% { opacity: 0.6; transform: translate(-50%, -50%) scale(1.2); }
}

@keyframes shimmer {
    0% { background-position: -200px 0; }
    100% { background-position: 200px 0; }
}

/* Responsive optimisé */
@media (max-width: 768px) {
    .main-kpi-card {
        padding: 2rem 1rem;
    }
    
    .kpi-value {
        font-size: 3rem;
    }
    
    .kpi-label {
        font-size: 1.2rem;
    }
    
    .enhanced-stat-card {
        height: 240px;
    }
    
    .stat-value-enhanced {
        font-size: 2rem;
    }
}
</style>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header Section -->
    <div class="welcome-section">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb justify-content-center" style="background: transparent; margin-bottom: 0;">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" style="color: rgba(255,255,255,0.8);">Dashboard</a></li>
                <li class="breadcrumb-item active" style="color: white;">Étudiants Actifs</li>
            </ol>
        </nav>
        
        <div class="welcome-title">
            <i class="fas fa-users me-3"></i>Gestion des Étudiants
        </div>
        <div class="welcome-subtitle">
            Analyse complète et gestion de la base étudiante EVC
        </div>
        
        <!-- Actions Rapides en Haut -->
        <div class="quick-actions">
            <button class="btn-quick" onclick="exportStudents('pdf')">
                <i class="fas fa-file-pdf me-2"></i>Exporter PDF
            </button>
            <button class="btn-quick" onclick="exportStudents('excel')">
                <i class="fas fa-file-excel me-2"></i>Exporter Excel
            </button>
            <button class="btn-quick" onclick="sendBulkEmail()">
                <i class="fas fa-envelope me-2"></i>Email Groupé
            </button>
            <a href="{{ route('admin.students.settings') }}" class="btn-quick">
                <i class="fas fa-cog me-2"></i>Paramètres
            </a>
        </div>
    </div>

    <!-- KPI Principal -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="main-kpi-card text-center" data-aos="fade-up" data-aos-duration="1000">
                <div class="kpi-background"></div>
                <div class="kpi-particles">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                <div class="kpi-content">
                    <div class="stat-icon-enhanced">
                        <div class="icon-glow"></div>
                        <i class="fas fa-users kpi-icon"></i>
                    </div>
                    <div class="kpi-value" data-counter="{{ $data['main_kpi']['total_students'] ?? 0 }}">0</div>
                    <div class="kpi-label">Total des Étudiants Actifs</div>
                    @if(isset($data['growth']['percentage']))
                    <div class="growth-badge">
                        <i class="fas fa-arrow-up me-1"></i>
                        +{{ $data['growth']['percentage'] }}% ce mois
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par Formation -->
    <div class="row mb-5">
        @if(isset($data['formations']) && is_array($data['formations']))
            @foreach($data['formations'] as $index => $formation)
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="{{ ($index + 1) * 100 }}">
                <div class="enhanced-stat-card">
                    <div class="card-background" style="background: {{ $formation['gradient'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)' }};"></div>
                    <div class="card-overlay"></div>
                    <div class="card-particles">
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                    </div>
                    <div class="card-content">
                        <div class="stat-icon-enhanced">
                            <div class="icon-glow"></div>
                            <i class="{{ $formation['icon'] ?? 'fas fa-graduation-cap' }}"></i>
                        </div>
                        <div class="stat-value-enhanced" data-counter="{{ $formation['count'] ?? 0 }}">0</div>
                        <div class="stat-label-enhanced">{{ $formation['name'] ?? 'Formation' }}</div>
                        <div class="stat-progress">
                            <div class="progress-bar-custom" data-width="{{ $formation['percentage'] ?? 0 }}" style="width: 0%;"></div>
                        </div>
                        <a href="{{ route('admin.students.by-formation', ['formation' => $formation['slug'] ?? 'design-graphique']) }}" 
                           class="btn-stat-enhanced">
                            <span class="btn-ripple"></span>
                            Voir la liste
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <!-- Fallback si pas de données formations -->
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="enhanced-stat-card">
                    <div class="card-background" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                    <div class="card-overlay"></div>
                    <div class="card-particles">
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                    </div>
                    <div class="card-content">
                        <div class="stat-icon-enhanced">
                            <div class="icon-glow"></div>
                            <i class="fas fa-paint-brush"></i>
                        </div>
                        <div class="stat-value-enhanced" data-counter="{{ $data['totals']['design_graphique'] ?? 0 }}">0</div>
                        <div class="stat-label-enhanced">Design Graphique</div>
                        <div class="stat-progress">
                            <div class="progress-bar-custom" data-width="75" style="width: 0%;"></div>
                        </div>
                        <a href="{{ route('admin.students.by-formation', ['formation' => 'design-graphique']) }}" 
                           class="btn-stat-enhanced">
                            <span class="btn-ripple"></span>
                            Voir la liste
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="enhanced-stat-card">
                    <div class="card-background" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);"></div>
                    <div class="card-overlay"></div>
                    <div class="card-particles">
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                    </div>
                    <div class="card-content">
                        <div class="stat-icon-enhanced">
                            <div class="icon-glow"></div>
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="stat-value-enhanced" data-counter="{{ $data['totals']['community_management'] ?? 0 }}">0</div>
                        <div class="stat-label-enhanced">Community Management</div>
                        <div class="stat-progress">
                            <div class="progress-bar-custom" data-width="60" style="width: 0%;"></div>
                        </div>
                        <a href="{{ route('admin.students.by-formation', ['formation' => 'community-management']) }}" 
                           class="btn-stat-enhanced">
                            <span class="btn-ripple"></span>
                            Voir la liste
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                <div class="enhanced-stat-card">
                    <div class="card-background" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
                    <div class="card-overlay"></div>
                    <div class="card-particles">
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                    </div>
                    <div class="card-content">
                        <div class="stat-icon-enhanced">
                            <div class="icon-glow"></div>
                            <i class="fas fa-robot"></i>
                        </div>
                        <div class="stat-value-enhanced" data-counter="{{ $data['totals']['intelligence_artificielle'] ?? 0 }}">0</div>
                        <div class="stat-label-enhanced">Intelligence Artificielle</div>
                        <div class="stat-progress">
                            <div class="progress-bar-custom" data-width="45" style="width: 0%;"></div>
                        </div>
                        <a href="{{ route('admin.students.by-formation', ['formation' => 'intelligence-artificielle']) }}" 
                           class="btn-stat-enhanced">
                            <span class="btn-ripple"></span>
                            Voir la liste
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                <div class="enhanced-stat-card">
                    <div class="card-background" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);"></div>
                    <div class="card-overlay"></div>
                    <div class="card-particles">
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                        <div class="particle-small"></div>
                    </div>
                    <div class="card-content">
                        <div class="stat-icon-enhanced">
                            <div class="icon-glow"></div>
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="stat-value-enhanced" data-counter="{{ $data['totals']['gestion_informatique'] ?? 0 }}">0</div>
                        <div class="stat-label-enhanced">Gestion Informatique</div>
                        <div class="stat-progress">
                            <div class="progress-bar-custom" data-width="55" style="width: 0%;"></div>
                        </div>
                        <a href="{{ route('admin.students.by-formation', ['formation' => 'gestion-informatique']) }}" 
                           class="btn-stat-enhanced">
                            <span class="btn-ripple"></span>
                            Voir la liste
                        </a>
                    </div>
                </div>
            </div>
        @endif
        </div>
    </div>

    <!-- Tableau des Étudiants Actifs Optimisé -->
    <div class="enhanced-table-container mb-4" data-aos="fade-up" data-aos-delay="600">
        <div class="table-header">
            <div class="table-title">
                <div class="title-icon">
                    <i class="fas fa-table"></i>
                </div>
                <h5 class="title-text">Liste des Étudiants Actifs</h5>
                <div class="title-badge">{{ count($data['students'] ?? []) }} étudiants</div>
            </div>
            <div class="table-actions">
                <button class="action-btn refresh-btn" onclick="refreshStudentsTable()" title="Actualiser">
                    <i class="fas fa-sync-alt"></i>
                    <span class="btn-tooltip">Actualiser</span>
                </button>
                <button class="action-btn filter-btn" onclick="toggleFilters()" title="Filtres">
                    <i class="fas fa-filter"></i>
                    <span class="btn-tooltip">Filtres</span>
                </button>
                <button class="action-btn search-btn" onclick="toggleSearch()" title="Recherche">
                    <i class="fas fa-search"></i>
                    <span class="btn-tooltip">Recherche</span>
                </button>
            </div>
        </div>
        
        <!-- Barre de recherche rapide -->
        <div class="quick-search-bar" id="quickSearchBar" style="display: none;">
            <div class="search-input-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="search-input" placeholder="Rechercher un étudiant..." id="quickSearchInput">
                <button class="clear-search" onclick="clearSearch()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="enhanced-table-wrapper">
            <table class="enhanced-table" id="studentsTable">
                <thead>
                    <tr>
                        <th class="sortable" data-sort="index">
                            <span class="th-content">
                                <span class="th-text">#</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </span>
                        </th>
                        <th class="sortable" data-sort="name">
                            <span class="th-content">
                                <span class="th-text">Étudiant</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </span>
                        </th>
                        <th class="sortable" data-sort="email">
                            <span class="th-content">
                                <span class="th-text">Email</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </span>
                        </th>
                        <th class="sortable" data-sort="formation">
                            <span class="th-content">
                                <span class="th-text">Formation</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </span>
                        </th>
                        <th class="sortable" data-sort="date">
                            <span class="th-content">
                                <span class="th-text">Inscription</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </span>
                        </th>
                        <th class="sortable" data-sort="progress">
                            <span class="th-content">
                                <span class="th-text">Progression</span>
                                <i class="fas fa-sort sort-icon"></i>
                            </span>
                        </th>
                        <th>
                            <span class="th-content">
                                <span class="th-text">Statut</span>
                            </span>
                        </th>
                        <th>
                            <span class="th-content">
                                <span class="th-text">Actions</span>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data['students'] ?? [] as $index => $student)
                    <tr class="table-row" data-student-id="{{ $student['id'] ?? $index + 1 }}">
                        <td class="row-index">{{ $index + 1 }}</td>
                        <td class="student-info">
                            <div class="student-card">
                                <div class="student-avatar">
                                    <div class="avatar-circle">
                                        {{ strtoupper(substr($student['prenom'] ?? 'E', 0, 1)) }}{{ strtoupper(substr($student['nom'] ?? 'T', 0, 1)) }}
                                    </div>
                                    <div class="avatar-status online"></div>
                                </div>
                                <div class="student-details">
                                    <div class="student-name">{{ ($student['prenom'] ?? 'Prénom') . ' ' . ($student['nom'] ?? 'Nom') }}</div>
                                    <div class="student-id">ID: {{ $student['id'] ?? '000' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="student-email">
                            <div class="email-container">
                                <i class="fas fa-envelope email-icon"></i>
                                <span class="email-text">{{ $student['email'] ?? 'etudiant@evc.com' }}</span>
                            </div>
                        </td>
                        <td class="student-formation">
                            <div class="formation-badge formation-{{ strtolower(str_replace(' ', '-', $student['formation'] ?? 'design-graphique')) }}">
                                {{ $student['formation'] ?? 'Design Graphique' }}
                            </div>
                        </td>
                        <td class="student-date">
                            <div class="date-container">
                                <i class="fas fa-calendar date-icon"></i>
                                <span class="date-text">{{ isset($student['created_at']) ? date('d/m/Y', strtotime($student['created_at'])) : '01/01/2024' }}</span>
                            </div>
                        </td>
                        <td class="student-progress">
                            <div class="progress-container">
                                <div class="progress-circle" data-progress="{{ $student['progression'] ?? 75 }}">
                                    <svg class="progress-ring" width="40" height="40">
                                        <circle class="progress-ring-circle" stroke="rgba(255,255,255,0.2)" stroke-width="3" fill="transparent" r="16" cx="20" cy="20"/>
                                        <circle class="progress-ring-progress" stroke="#28a745" stroke-width="3" fill="transparent" r="16" cx="20" cy="20" stroke-dasharray="100.53" stroke-dashoffset="25"/>
                                    </svg>
                                    <span class="progress-text">{{ $student['progression'] ?? 75 }}%</span>
                                </div>
                            </div>
                        </td>
                        <td class="student-status">
                            <div class="status-badge status-active">
                                <div class="status-dot"></div>
                                <span class="status-text">Actif</span>
                            </div>
                        </td>
                        <td class="student-actions">
                            <div class="action-buttons">
                                <a href="{{ route('admin.students.profile', $student['id'] ?? 1) }}" class="action-btn-small view-btn" title="Voir profil">
                                    <i class="fas fa-eye"></i>
                                    <div class="btn-ripple-small"></div>
                                </a>
                                <a href="{{ route('admin.students.edit', $student['id'] ?? 1) }}" class="action-btn-small edit-btn" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                    <div class="btn-ripple-small"></div>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="empty-title">Aucun étudiant trouvé</div>
                                <div class="empty-subtitle">Les étudiants apparaissent ici après leur inscription</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination personnalisée -->
        <div class="table-pagination">
            <div class="pagination-info">
                <span class="pagination-text">Affichage de <strong>1-{{ count($data['students'] ?? []) }}</strong> sur <strong>{{ count($data['students'] ?? []) }}</strong> étudiants</span>
            </div>
            <div class="pagination-controls">
                <button class="pagination-btn" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="pagination-current">1</span>
                <button class="pagination-btn" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<!-- AOS Animation Library -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- Admin Statistics JS -->
<script src="{{ asset('js/admin-statistics.js') }}"></script>
<!-- Holographic Stats JS -->
<script src="{{ asset('js/holographic-stats.js') }}"></script>

<script>
$(document).ready(function() {
    // ===== INITIALISATION DES ANIMATIONS =====
    
    // Initialisation AOS (Animate On Scroll)
    AOS.init({
        duration: 800,
        easing: 'ease-out-cubic',
        once: true,
        offset: 100,
        delay: 0
    });
    
    // ===== ANIMATIONS DE COMPTEURS =====
    
    function animateCounter(element, target, duration = 2000) {
        const start = 0;
        const increment = target / (duration / 16);
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString('fr-FR');
        }, 16);
    }
    
    // Animation des compteurs avec Intersection Observer
    const observerOptions = {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                const target = parseInt(entry.target.dataset.counter) || 0;
                animateCounter(entry.target, target, 2500);
                entry.target.classList.add('animated');
            }
        });
    }, observerOptions);
    
    // Observer tous les compteurs
    document.querySelectorAll('[data-counter]').forEach(counter => {
        counterObserver.observe(counter);
    });
    
    // ===== ANIMATIONS DES BARRES DE PROGRESSION =====
    
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
                const targetWidth = entry.target.dataset.width || 0;
                setTimeout(() => {
                    entry.target.style.width = targetWidth + '%';
                    entry.target.classList.add('animated');
                }, 500);
            }
        });
    }, observerOptions);
    
    // Observer toutes les barres de progression
    document.querySelectorAll('[data-width]').forEach(progress => {
        progressObserver.observe(progress);
    });
    
    // ===== EFFETS DE SURVOL AVANCÉS =====
    
    // Effet parallax sur les cartes
    document.querySelectorAll('.enhanced-stat-card').forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
        });
    });
    
    // Effet ripple sur les boutons
    document.querySelectorAll('.btn-stat-enhanced').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = this.querySelector('.btn-ripple');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            
            ripple.classList.add('animate');
            
            setTimeout(() => {
                ripple.classList.remove('animate');
            }, 600);
        });
    });
    
    // ===== ANIMATIONS DES PARTICULES =====
    
    function createFloatingParticles() {
        document.querySelectorAll('.kpi-particles, .card-particles').forEach(container => {
            const particles = container.querySelectorAll('.particle, .particle-small');
            
            particles.forEach((particle, index) => {
                const delay = index * 1000;
                const duration = 4000 + Math.random() * 2000;
                
                setInterval(() => {
                    particle.style.animation = 'none';
                    setTimeout(() => {
                        particle.style.animation = `float ${duration}ms ease-in-out infinite`;
                    }, 10);
                }, duration + delay);
            });
        });
    }
    
    // Démarrer les animations de particules après un délai
    setTimeout(createFloatingParticles, 1000);
    
    // ===== MICRO-INTERACTIONS =====
    
    // Feedback tactile sur les éléments interactifs
    document.querySelectorAll('.enhanced-stat-card, .main-kpi-card').forEach(element => {
        element.addEventListener('mousedown', function() {
            this.style.transform += ' scale(0.98)';
        });
        
        element.addEventListener('mouseup', function() {
            this.style.transform = this.style.transform.replace(' scale(0.98)', '');
        });
        
        element.addEventListener('mouseleave', function() {
            this.style.transform = this.style.transform.replace(' scale(0.98)', '');
        });
    });
    
    // ===== CONFIGURATION DATATABLE AVANCÉE =====
    
    const table = $('#studentsTable').DataTable({
        responsive: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>' +
             '<"row"<"col-sm-12"B>>',
        buttons: [
            {
                extend: 'copy',
                className: 'btn btn-outline-primary btn-sm me-1',
                text: '<i class="fas fa-copy me-1"></i>Copier'
            },
            {
                extend: 'csv',
                className: 'btn btn-outline-success btn-sm me-1',
                text: '<i class="fas fa-file-csv me-1"></i>CSV'
            },
            {
                extend: 'excel',
                className: 'btn btn-outline-success btn-sm me-1',
                text: '<i class="fas fa-file-excel me-1"></i>Excel'
            },
            {
                extend: 'pdf',
                className: 'btn btn-outline-danger btn-sm me-1',
                text: '<i class="fas fa-file-pdf me-1"></i>PDF'
            },
            {
                extend: 'print',
                className: 'btn btn-outline-info btn-sm',
                text: '<i class="fas fa-print me-1"></i>Imprimer'
            }
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json'
        },
        pageLength: 25,
        order: [[0, 'desc']],
        columnDefs: [
            {
                targets: '_all',
                className: 'align-middle'
            }
        ],
        drawCallback: function() {
            // Réappliquer les animations après le redraw
            setTimeout(() => {
                AOS.refresh();
            }, 100);
        }
    });
    
    // Animation d'apparition du tableau
    setTimeout(() => {
        $('.dataTables_wrapper').addClass('fade-in');
    }, 500);
    
    // ===== NOTIFICATIONS ET FEEDBACKS =====
    
    // Système de notifications toast
    function showToast(message, type = 'success') {
        const toast = $(`
            <div class="toast align-items-center text-white bg-${type} border-0 position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999;" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto"></button>
                </div>
            </div>
        `);
        
        $('body').append(toast);
        toast.toast('show');
        
        setTimeout(() => {
            toast.remove();
        }, 5000);
    }
    
    // ===== ACCESSIBILITÉ AMÉLIORÉE =====
    
    // Navigation au clavier
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Tab') {
            document.body.classList.add('keyboard-navigation');
        }
    });
    
    document.addEventListener('mousedown', () => {
        document.body.classList.remove('keyboard-navigation');
    });
    
    // Focus visible pour les éléments interactifs
    document.querySelectorAll('.enhanced-stat-card, .btn-stat-enhanced').forEach(element => {
        element.setAttribute('tabindex', '0');
        
        element.addEventListener('focus', function() {
            this.style.outline = '2px solid #007bff';
            this.style.outlineOffset = '2px';
        });
        
        element.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
        
        element.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
    
    // ===== PERFORMANCE ET OPTIMISATION =====
    
    // Lazy loading des images si présentes
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('lazy');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Debounce pour les événements de redimensionnement
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            AOS.refresh();
            table.columns.adjust().responsive.recalc();
        }, 250);
    });
    
    // ===== ANIMATIONS DE CHARGEMENT =====
    
    // Masquer le loader après le chargement complet
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.body.classList.add('loaded');
            showToast('Interface chargée avec succès !', 'success');
        }, 500);
    });
    
    console.log('🚀 Interface statistiques optimisée chargée avec succès !');
});

// ===== STYLES CSS ADDITIONNELS VIA JAVASCRIPT =====

const additionalStyles = `
    <style>
        .fade-in {
            opacity: 0;
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .keyboard-navigation *:focus {
            outline: 2px solid #007bff !important;
            outline-offset: 2px !important;
        }
        
        .btn-ripple.animate {
            animation: ripple 0.6s linear;
        }
        
        @keyframes ripple {
            to {
                transform: translate(-50%, -50%) scale(4);
                opacity: 0;
            }
        }
        
        .loaded .main-kpi-card,
        .loaded .enhanced-stat-card {
            animation: slideInUp 0.8s ease-out;
        }
        
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
`;

document.head.insertAdjacentHTML('beforeend', additionalStyles);
// Fonction pour envoyer un email groupé
function sendBulkEmail() {
    alert('Fonctionnalité d\'email groupé en développement');
}
</script>

<style>
/* Harmonisation avec le style du dashboard admin */
.admin-content {
    padding: 2rem;
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.holographic-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-bottom: 2rem;
}

@media (max-width: 1200px) {
    .holographic-stats-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .holographic-stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .holographic-stats-grid {
        grid-template-columns: 1fr;
    }
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.stat-icon {
    font-size: 2.5rem;
    color: white;
    margin-bottom: 1rem;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: bold;
    color: white;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.9);
    margin-bottom: 1rem;
}

.btn-stat {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    text-decoration: none;
    font-size: 0.85rem;
    transition: all 0.3s ease;
    display: inline-block;
}

.btn-stat:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.welcome-section {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    text-align: center;
}

.welcome-title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: white;
}

.welcome-subtitle {
    color: rgba(255, 255, 255, 0.7);
    font-size: 1.1rem;
}

.quick-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1.5rem;
    flex-wrap: wrap;
}

.btn-quick {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-quick:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(0, 123, 255, 0.3);
    color: white;
    text-decoration: none;
}

/* Styles pour le tableau */
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
    font-weight: bold;
}

.table-dark {
    --bs-table-bg: transparent;
    --bs-table-striped-bg: rgba(255, 255, 255, 0.05);
}

.table-dark th,
.table-dark td {
    border-color: rgba(255, 255, 255, 0.1);
}

.table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.btn-group-sm > .btn-stat {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    margin: 0 2px;
}

/* Masquer les boutons DataTables par défaut */
.dt-buttons {
    display: none;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-content {
        padding: 1rem;
    }
    
    .welcome-title {
        font-size: 1.5rem;
    }
    
    .quick-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-quick {
        width: 100%;
        max-width: 300px;
    }
}
</style>
@endpush
@endsection
