@extends('layouts.admin')

@section('title', 'Gestion Étudiants - Interface Révolutionnaire')

@section('content')
<!-- Interface Révolutionnaire Fluide -->
<div class="revolutionary-container">
    <!-- Header Dynamique -->
    <div class="revolutionary-header">
        <div class="header-content">
            <div class="header-main">
                <div class="header-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <div class="header-text">
                    <h1 class="header-title">Gestion des Étudiants</h1>
                    <p class="header-subtitle">Interface révolutionnaire de suivi et administration</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="rev-btn primary" onclick="addStudent()">
                    <i class="fas fa-plus"></i>
                    <span>Nouvel Étudiant</span>
                </button>
                <button class="rev-btn secondary" onclick="exportData()">
                    <i class="fas fa-download"></i>
                    <span>Exporter</span>
                </button>
            </div>
        </div>

        <!-- Stats Dynamiques -->
        <div class="dynamic-stats">
            <div class="stat-card" data-stat="total">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" data-count="{{ $stats['total'] ?? 0 }}">0</div>
                    <div class="stat-label">Total Étudiants</div>
                </div>
            </div>
            <div class="stat-card" data-stat="active">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" data-count="{{ $stats['actifs'] ?? 0 }}">0</div>
                    <div class="stat-label">Actifs</div>
                </div>
            </div>
            <div class="stat-card" data-stat="new">
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" data-count="{{ $stats['nouveaux_ce_mois'] ?? 0 }}">0</div>
                    <div class="stat-label">Ce Mois</div>
                </div>
            </div>
            <div class="stat-card" data-stat="online">
                <div class="stat-icon">
                    <i class="fas fa-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number" data-count="12">0</div>
                    <div class="stat-label">En Ligne</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contrôles Intelligents -->
    <div class="smart-controls">
        <div class="search-zone">
            <div class="search-input-wrapper">
                <i class="fas fa-search search-icon"></i>
                <input type="text" class="smart-search" placeholder="Recherche intelligente..." id="smartSearch">
                <div class="search-suggestions" id="searchSuggestions"></div>
            </div>
        </div>

        <div class="filter-zone">
            <div class="filter-group">
                <button class="filter-btn active" data-filter="all">
                    <span>Tous</span>
                    <div class="filter-count">{{ $students->total() }}</div>
                </button>
                <button class="filter-btn" data-filter="active">
                    <span>Actifs</span>
                    <div class="filter-count">{{ $stats['actifs'] ?? 0 }}</div>
                </button>
                <button class="filter-btn" data-filter="new">
                    <span>Nouveaux</span>
                    <div class="filter-count">{{ $stats['nouveaux_ce_mois'] ?? 0 }}</div>
                </button>
            </div>

            <div class="view-toggle">
                <button class="view-btn active" data-view="grid">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Zone de Contenu Adaptative -->
    <div class="content-zone">
        <!-- Vue Grille -->
        <div class="students-grid active" id="studentsGrid">
            @foreach($students as $student)
            <div class="student-card" data-student-id="{{ $student->id }}">
                <div class="card-header">
                    <div class="student-avatar">
                        @php
                            $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrl($student->profile_photo);
                        @endphp
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="Photo">
                        @else
                            <div class="avatar-placeholder">
                                {{ strtoupper(substr($student->prenoms ?? $student->first_name, 0, 1)) }}{{ strtoupper(substr($student->nom ?? $student->last_name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="student-status">
                        @if($student->online_status)
                            <div class="status-indicator online"></div>
                        @else
                            <div class="status-indicator offline"></div>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <h3 class="student-name">{{ $student->prenoms ?? $student->first_name }} {{ $student->nom ?? $student->last_name }}</h3>
                    <p class="student-formation">{{ $student->formations ?? $student->formation_souhaitee }}</p>

                    <div class="student-metrics">
                        <div class="metric">
                            <span class="metric-label">Progression</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ rand(20, 95) }}%"></div>
                            </div>
                        </div>

                        <div class="metric">
                            <span class="metric-label">Abonnement</span>
                            @php
                                $endDate = \Carbon\Carbon::parse($student->created_at)->addMonths(4);
                                $daysLeft = max(0, $endDate->diffInDays(now()));
                            @endphp
                            <span class="metric-value {{ $daysLeft < 30 ? 'warning' : 'success' }}">
                                {{ $daysLeft }} jours
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-actions">
                    <button class="action-btn primary" onclick="viewStudent({{ $student->id }})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn secondary" onclick="editStudent({{ $student->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="action-btn" onclick="moreActions({{ $student->id }})">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Vue Liste -->
        <div class="students-list" id="studentsList">
            <div class="list-header">
                <div class="col-header">Étudiant</div>
                <div class="col-header">Formation</div>
                <div class="col-header">Status</div>
                <div class="col-header">Progression</div>
                <div class="col-header">Actions</div>
            </div>

            @foreach($students as $student)
            <div class="list-row" data-student-id="{{ $student->id }}">
                <div class="col-student">
                    <div class="student-info">
                        <div class="student-avatar-small">
                            @php
                                $photoUrl2 = null;
                                if (!empty($student->profile_photo)) {
                                    $filename2 = basename($student->profile_photo);
                                    if (file_exists(public_path('uploads/photos/' . $filename2))) {
                                        $photoUrl2 = asset('uploads/photos/' . $filename2);
                                    } elseif (file_exists(public_path($student->profile_photo))) {
                                        $photoUrl2 = asset($student->profile_photo);
                                    } elseif (file_exists(public_path('storage/' . $student->profile_photo))) {
                                        $photoUrl2 = asset('storage/' . $student->profile_photo);
                                    }
                                }
                            @endphp
                            @if($photoUrl2)
                                <img src="{{ $photoUrl2 }}" alt="Photo">
                            @else
                                <div class="avatar-placeholder-small">
                                    {{ strtoupper(substr($student->prenoms ?? $student->first_name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="student-details">
                            <div class="student-name">{{ $student->prenoms ?? $student->first_name }} {{ $student->nom ?? $student->last_name }}</div>
                            <div class="student-email">{{ $student->email }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-formation">
                    <span class="formation-badge">{{ $student->formations ?? $student->formation_souhaitee }}</span>
                </div>

                <div class="col-status">
                    @if($student->online_status)
                        <span class="status-badge online">En ligne</span>
                    @else
                        <span class="status-badge offline">Hors ligne</span>
                    @endif
                </div>

                <div class="col-progress">
                    <div class="progress-container">
                        <div class="progress-bar-small">
                            <div class="progress-fill-small" style="width: {{ rand(20, 95) }}%"></div>
                        </div>
                        <span class="progress-text">{{ rand(20, 95) }}%</span>
                    </div>
                </div>

                <div class="col-actions">
                    <button class="action-btn-small primary" onclick="viewStudent({{ $student->id }})">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn-small secondary" onclick="editStudent({{ $student->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pagination Moderne -->
    <div class="modern-pagination">
        {{ $students->links() }}
    </div>
</div>

<!-- Notifications Flottantes -->
<div class="notification-hub" id="notificationHub"></div>

<!-- Modal Actions Rapides -->
<div class="quick-modal" id="quickModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Actions Rapides</h3>
            <button class="close-btn" onclick="closeQuickModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="quick-actions">
                <button class="quick-action-btn">
                    <i class="fas fa-envelope"></i>
                    <span>Envoyer Message</span>
                </button>
                <button class="quick-action-btn">
                    <i class="fas fa-download"></i>
                    <span>Exporter Données</span>
                </button>
                <button class="quick-action-btn">
                    <i class="fas fa-ban"></i>
                    <span>Suspendre</span>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Variables CSS Révolutionnaires */
:root {
    --rev-primary: #2563eb;
    --rev-secondary: #64748b;
    --rev-success: #10b981;
    --rev-warning: #f59e0b;
    --rev-danger: #ef4444;
    --rev-bg: #0f172a;
    --rev-surface: #1e293b;
    --rev-border: #334155;
    --rev-text: #f8fafc;
    --rev-text-muted: #94a3b8;
    --rev-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --rev-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --rev-radius: 12px;
    --rev-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Container Principal */
.revolutionary-container {
    padding: 2rem;
    background: var(--rev-bg);
    min-height: 100vh;
    color: var(--rev-text);
}

/* Header Révolutionnaire */
.revolutionary-header {
    background: var(--rev-surface);
    border: 1px solid var(--rev-border);
    border-radius: var(--rev-radius);
    padding: 2rem;
    margin-bottom: 2rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.header-main {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-icon {
    width: 60px;
    height: 60px;
    background: var(--rev-gradient);
    border-radius: var(--rev-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.header-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0;
    background: var(--rev-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.header-subtitle {
    color: var(--rev-text-muted);
    margin: 0.5rem 0 0 0;
    font-size: 1.1rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

/* Boutons Révolutionnaires */
.rev-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: var(--rev-radius);
    font-weight: 500;
    cursor: pointer;
    transition: var(--rev-transition);
    text-decoration: none;
}

.rev-btn.primary {
    background: var(--rev-primary);
    color: white;
}

.rev-btn.primary:hover {
    background: #1d4ed8;
    transform: translateY(-2px);
}

.rev-btn.secondary {
    background: var(--rev-surface);
    color: var(--rev-text);
    border: 1px solid var(--rev-border);
}

.rev-btn.secondary:hover {
    background: var(--rev-border);
    transform: translateY(-2px);
}

/* Stats Dynamiques */
.dynamic-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--rev-border);
    border-radius: var(--rev-radius);
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--rev-transition);
}

.stat-card:hover {
    border-color: var(--rev-primary);
    transform: translateY(-2px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-card[data-stat="total"] .stat-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.stat-card[data-stat="active"] .stat-icon {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
}

.stat-card[data-stat="new"] .stat-icon {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: white;
}

.stat-card[data-stat="online"] .stat-icon {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: white;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--rev-text);
    line-height: 1;
}

.stat-label {
    color: var(--rev-text-muted);
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* Contrôles Intelligents */
.smart-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    gap: 2rem;
}

.search-zone {
    flex: 1;
    max-width: 400px;
}

.search-input-wrapper {
    position: relative;
}

.smart-search {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 3rem;
    background: var(--rev-surface);
    border: 1px solid var(--rev-border);
    border-radius: var(--rev-radius);
    color: var(--rev-text);
    font-size: 1rem;
    transition: var(--rev-transition);
}

.smart-search:focus {
    outline: none;
    border-color: var(--rev-primary);
    background: rgba(37, 99, 235, 0.1);
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--rev-text-muted);
}

.filter-zone {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.filter-group {
    display: flex;
    gap: 0.5rem;
}

.filter-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--rev-surface);
    border: 1px solid var(--rev-border);
    border-radius: var(--rev-radius);
    color: var(--rev-text-muted);
    cursor: pointer;
    transition: var(--rev-transition);
}

.filter-btn.active {
    background: var(--rev-primary);
    color: white;
    border-color: var(--rev-primary);
}

.filter-count {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.view-toggle {
    display: flex;
    gap: 0.25rem;
}

.view-btn {
    width: 40px;
    height: 40px;
    background: var(--rev-surface);
    border: 1px solid var(--rev-border);
    border-radius: 8px;
    color: var(--rev-text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--rev-transition);
}

.view-btn.active {
    background: var(--rev-primary);
    color: white;
    border-color: var(--rev-primary);
}

/* Grille d'Étudiants */
.students-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.students-grid.active {
    display: grid;
}

.students-list {
    display: none;
}

.student-card {
    background: var(--rev-surface);
    border: 1px solid var(--rev-border);
    border-radius: var(--rev-radius);
    overflow: hidden;
    transition: var(--rev-transition);
}

.student-card:hover {
    border-color: var(--rev-primary);
    transform: translateY(-4px);
}

.card-header {
    position: relative;
    padding: 1.5rem;
    background: var(--rev-gradient);
}

.student-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    margin: 0 auto;
}

.student-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
}

.student-status {
    position: absolute;
    top: 1rem;
    right: 1rem;
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.status-indicator.online {
    background: var(--rev-success);
    animation: pulse 2s infinite;
}

.status-indicator.offline {
    background: var(--rev-text-muted);
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}

.card-body {
    padding: 1.5rem;
}

.student-name {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--rev-text);
    margin: 0 0 0.5rem 0;
    text-align: center;
}

.student-formation {
    color: var(--rev-text-muted);
    text-align: center;
    margin-bottom: 1rem;
}

.student-metrics {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.metric {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.metric-label {
    font-size: 0.875rem;
    color: var(--rev-text-muted);
}

.metric-value {
    font-weight: 600;
}

.metric-value.success {
    color: var(--rev-success);
}

.metric-value.warning {
    color: var(--rev-warning);
}

.progress-bar {
    flex: 1;
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
    margin-left: 1rem;
}

.progress-fill {
    height: 100%;
    background: var(--rev-primary);
    border-radius: 3px;
    transition: width 0.5s ease;
}

.card-actions {
    padding: 1rem 1.5rem;
    background: rgba(255, 255, 255, 0.02);
    display: flex;
    justify-content: center;
    gap: 0.5rem;
}

.action-btn {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: var(--rev-transition);
}

.action-btn.primary {
    background: var(--rev-primary);
    color: white;
}

.action-btn.secondary {
    background: var(--rev-surface);
    color: var(--rev-text-muted);
    border: 1px solid var(--rev-border);
}

.action-btn:hover {
    transform: scale(1.1);
}

/* Responsive */
@media (max-width: 768px) {
    .revolutionary-container {
        padding: 1rem;
    }

    .header-content {
        flex-direction: column;
        gap: 1.5rem;
        align-items: stretch;
    }

    .header-actions {
        justify-content: stretch;
    }

    .rev-btn {
        flex: 1;
        justify-content: center;
    }

    .smart-controls {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .filter-zone {
        justify-content: space-between;
    }

    .students-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Animation des compteurs
function animateCounters() {
    document.querySelectorAll('.stat-number').forEach(counter => {
        const target = parseInt(counter.dataset.count);
        const duration = 2000;
        const step = target / (duration / 16);
        let current = 0;

        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            counter.textContent = Math.floor(current);
        }, 16);
    });
}

// Recherche intelligente
document.getElementById('smartSearch').addEventListener('input', function(e) {
    const query = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.student-card');

    cards.forEach(card => {
        const name = card.querySelector('.student-name').textContent.toLowerCase();
        const formation = card.querySelector('.student-formation').textContent.toLowerCase();

        if (name.includes(query) || formation.includes(query)) {
            card.style.display = 'block';
            card.style.animation = 'fadeIn 0.3s ease';
        } else {
            card.style.display = 'none';
        }
    });
});

// Filtres dynamiques
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const filter = this.dataset.filter;
        // Logique de filtrage ici
        console.log('Filtrer par:', filter);
    });
});

// Toggle vue
document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const view = this.dataset.view;
        const grid = document.getElementById('studentsGrid');
        const list = document.getElementById('studentsList');

        if (view === 'grid') {
            grid.style.display = 'grid';
            list.style.display = 'none';
        } else {
            grid.style.display = 'none';
            list.style.display = 'block';
        }
    });
});

// Actions étudiants
function viewStudent(id) {
    window.location.href = `/evc/app/admin/students/${id}`;
}

function editStudent(id) {
    console.log('Éditer étudiant:', id);
}

function moreActions(id) {
    document.getElementById('quickModal').style.display = 'flex';
}

function closeQuickModal() {
    document.getElementById('quickModal').style.display = 'none';
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    animateCounters();
    console.log('🚀 Interface révolutionnaire initialisée !');
});
</script>
@endpush
