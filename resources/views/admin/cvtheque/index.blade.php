@extends('layouts.admin')

@section('title', 'CVthèque - Profils Étudiants')

@push('styles')
<style>
    /* Cartes de statistiques compactes et modernes */
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

    .stat-card-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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

    /* Onglets modernes */
    .modern-tabs {
        background: #0f172a;
        border-bottom: 2px solid #334155;
        padding: 0;
        margin: 0;
    }

    .modern-tabs .nav-item {
        margin: 0;
    }

    .modern-tabs .nav-link {
        background: transparent;
        border: none;
        border-bottom: 3px solid transparent;
        color: #94a3b8;
        padding: 1.25rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        position: relative;
        display: flex;
        align-items: center;
    }

    .modern-tabs .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.05);
    }

    .modern-tabs .nav-link.active {
        color: #4fc3f7;
        border-bottom-color: #4fc3f7;
        background: rgba(79, 195, 247, 0.1);
    }

    .modern-tabs .nav-link .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    /* Animation des onglets */
    .tab-pane {
        animation: fadeIn 0.4s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 text-white mb-0">
            <i class="fas fa-user-tie me-2 text-primary"></i>CVthèque - Profils Étudiants
        </h1>
        <button class="btn" style="background: linear-gradient(135deg, #4fc3f7, #29b6f6); border: none; color: white; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 600;" onclick="exportProfiles()">
            <i class="fas fa-download me-2"></i>Exporter les Profils
        </button>
    </div>

    <!-- Cartes de Statistiques Compactes -->
    <div class="row mb-4">
        <!-- Total Étudiants -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format($stats['total_students'] ?? 0) }}</h3>
                    <p class="stat-label">Total Étudiants</p>
                </div>
            </div>
        </div>

        <!-- Avec Profil CVthèque -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format($stats['with_profile'] ?? 0) }}</h3>
                    <p class="stat-label">Avec Profil CVthèque</p>
                </div>
            </div>
        </div>

        <!-- Complétion Moyenne -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['avg_completion'] ?? 0 }}%</h3>
                    <p class="stat-label">Taux de Complétion Moyen</p>
                </div>
            </div>
        </div>

        <!-- Profils Visibles -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="stat-card stat-card-danger">
                <div class="stat-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ number_format($stats['visible_profiles'] ?? 0) }}</h3>
                    <p class="stat-label">Profils Visibles</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs par Formation -->
    <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body p-0">
            <!-- Navigation par onglets -->
            <ul class="nav nav-tabs modern-tabs" id="formationTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>
                        Tous
                        <span class="badge bg-info ms-2">{{ $students->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="design-tab" data-bs-toggle="tab" data-bs-target="#design" type="button" role="tab">
                        <i class="fas fa-palette me-2"></i>
                        Design Graphique
                        <span class="badge bg-primary ms-2">{{ ($studentsByFormation['Design Graphique'] ?? collect())->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="community-tab" data-bs-toggle="tab" data-bs-target="#community" type="button" role="tab">
                        <i class="fas fa-users me-2"></i>
                        Community Management
                        <span class="badge bg-warning ms-2">{{ ($studentsByFormation['Community Management'] ?? collect())->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="design-cm-tab" data-bs-toggle="tab" data-bs-target="#design-cm" type="button" role="tab">
                        <i class="fas fa-object-group me-2"></i>
                        Design Graphique &amp; Community Manager
                        <span class="badge bg-info ms-2">{{ ($studentsByFormation['Design Graphique & Community Manager'] ?? collect())->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="gestion-tab" data-bs-toggle="tab" data-bs-target="#gestion" type="button" role="tab">
                        <i class="fas fa-laptop-code me-2"></i>
                        Gestion Informatique
                        <span class="badge bg-success ms-2">{{ ($studentsByFormation['Gestion Informatique'] ?? collect())->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ia-tab" data-bs-toggle="tab" data-bs-target="#ia" type="button" role="tab">
                        <i class="fas fa-brain me-2"></i>
                        Intelligence Artificielle
                        <span class="badge bg-danger ms-2">{{ ($studentsByFormation['Intelligence Artificielle'] ?? collect())->count() }}</span>
                    </button>
                </li>
            </ul>

            <!-- Contenu des onglets -->
            <div class="tab-content p-4" id="formationTabsContent">
                <!-- Onglet Tous -->
                <div class="tab-pane fade show active" id="all" role="tabpanel">
                    @include('admin.cvtheque.partials.students-table', ['students' => $students])
                </div>

                <!-- Onglet Design Graphique -->
                <div class="tab-pane fade" id="design" role="tabpanel">
                    @include('admin.cvtheque.partials.students-table', ['students' => $studentsByFormation['Design Graphique'] ?? collect()])
                </div>

                <!-- Onglet Community Management -->
                <div class="tab-pane fade" id="community" role="tabpanel">
                    @include('admin.cvtheque.partials.students-table', ['students' => $studentsByFormation['Community Management'] ?? collect()])
                </div>

                <!-- Onglet Design Graphique & Community Manager -->
                <div class="tab-pane fade" id="design-cm" role="tabpanel">
                    @include('admin.cvtheque.partials.students-table', ['students' => $studentsByFormation['Design Graphique & Community Manager'] ?? collect()])
                </div>

                <!-- Onglet Gestion Informatique -->
                <div class="tab-pane fade" id="gestion" role="tabpanel">
                    @include('admin.cvtheque.partials.students-table', ['students' => $studentsByFormation['Gestion Informatique'] ?? collect()])
                </div>

                <!-- Onglet Intelligence Artificielle -->
                <div class="tab-pane fade" id="ia" role="tabpanel">
                    @include('admin.cvtheque.partials.students-table', ['students' => $studentsByFormation['Intelligence Artificielle'] ?? collect()])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportProfiles() {
    window.location.href = '{{ route("admin.cvtheque.export") }}';
}
</script>
@endpush
