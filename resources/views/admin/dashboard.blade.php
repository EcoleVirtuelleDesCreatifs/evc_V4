@extends('layouts.admin')

@section('title', 'Dashboard Admin - EVC')

@push('styles')
<style>
    body {
        background: #0f172a;
        overflow-x: hidden;
    }

    /* Header moderne */
    .dashboard-header {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(37, 99, 235, 0.3);
        animation: fadeInDown 0.6s ease;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #f97316 0%, #fb923c 100%);
    }

    .dashboard-header::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(249, 115, 22, 0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .header-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .header-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }

    .header-time {
        position: absolute;
        top: 2.5rem;
        right: 2.5rem;
        text-align: right;
        z-index: 1;
    }

    .header-time .time {
        font-size: 2rem;
        font-weight: 700;
    }

    .header-time .date {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Stats cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        animation: fadeInUp 0.6s ease;
        animation-fill-mode: both;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        border-color: #f97316;
        box-shadow: 0 20px 40px rgba(249, 115, 22, 0.2);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6 0%, #f97316 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-bottom: 1rem;
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
    }

    .stat-card:hover .stat-icon {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        box-shadow: 0 8px 16px rgba(249, 115, 22, 0.4);
        transform: rotate(360deg);
        transition: all 0.6s ease;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
        font-family: 'Segoe UI', sans-serif;
    }

    .stat-label {
        color: #94a3b8;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .stat-change {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .stat-change.positive {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .stat-change.negative {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Activity section */
    .activity-section {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        animation: fadeIn 0.8s ease;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #334155;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title .icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .view-all-btn {
        background: rgba(249, 115, 22, 0.1);
        color: #f97316;
        border: 1px solid rgba(249, 115, 22, 0.3);
        padding: 0.5rem 1.25rem;
        border-radius: 10px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .view-all-btn:hover {
        background: #f97316;
        color: white;
        text-decoration: none;
        transform: translateX(5px);
    }

    /* Activity item */
    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.25rem;
        background: rgba(255, 255, 255, 0.02);
        border-radius: 12px;
        margin-bottom: 1rem;
        border-left: 3px solid transparent;
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: rgba(255, 255, 255, 0.04);
        border-left-color: #f97316;
        transform: translateX(5px);
    }

    .activity-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.2rem;
        flex-shrink: 0;
        position: relative;
    }

    .activity-avatar.online::after {
        content: '';
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #22c55e;
        border: 2px solid #0f172a;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    .activity-content {
        flex: 1;
    }

    .activity-name {
        color: white;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .activity-description {
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .activity-time {
        color: #64748b;
        font-size: 0.875rem;
        white-space: nowrap;
    }

    .activity-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-pending {
        background: rgba(251, 191, 36, 0.1);
        color: #fbbf24;
    }

    .badge-validated {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .badge-rejected {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Quick actions */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .quick-action-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: white;
    }

    .quick-action-card:hover {
        transform: translateY(-5px);
        border-color: #f97316;
        box-shadow: 0 12px 24px rgba(249, 115, 22, 0.2);
        text-decoration: none;
        color: white;
    }

    .quick-action-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .quick-action-card:hover .quick-action-icon {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
    }

    .quick-action-label {
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .dashboard-header {
            padding: 1.5rem;
        }
        .header-time {
            position: static;
            margin-top: 1rem;
            text-align: left;
        }
        .header-title {
            font-size: 2rem;
        }
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .quick-actions-grid {
            grid-template-columns: 1fr;
        }
        .activity-section {
            padding: 1.25rem;
        }
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .dashboard-header {
            padding: 1.25rem;
            border-radius: 18px;
        }
        .header-title {
            font-size: 1.6rem;
        }
        .header-subtitle {
            font-size: 0.95rem;
        }
        .stat-card {
            padding: 1.25rem;
            border-radius: 16px;
        }
        .stat-number {
            font-size: 2rem;
        }
        .stat-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            font-size: 22px;
        }
        .activity-item {
            flex-direction: column;
            align-items: flex-start;
        }
        .activity-time {
            white-space: normal;
        }
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

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
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
        }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-title {
            font-size: 1.75rem;
        }

        .header-time {
            position: static;
            margin-top: 1rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="dashboard-header">
        <div>
            <h1 class="header-title">
                <i class="fas fa-tachometer-alt me-3"></i>Dashboard Admin
            </h1>
            <p class="header-subtitle mb-0">
                Bienvenue, <strong>{{ session('admin_name') ?? 'Administrateur' }}</strong> •
                <span id="greeting"></span>
            </p>
        </div>
        <div class="header-time">
            <div class="time" id="currentTime"></div>
            <div class="date" id="currentDate"></div>
        </div>
    </div>

    <!-- Accounting Stats Section -->
    @if(session('admin_role') !== 'assistant')
    <div class="row g-4 mb-4">
        <!-- Recettes -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative bg-white" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold text-muted text-xs mb-1 ls-1" style="font-size: 0.75rem; letter-spacing: 1px;">Recettes Totales</p>
                            <h3 class="display-6 fw-bold text-success mb-0" style="font-size: 2.5rem;">{{ number_format($totalIncome, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></h3>
                        </div>
                        <div class="icon-shape bg-success bg-opacity-10 text-success rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success bg-opacity-10 text-success me-2" style="padding: 0.5em 0.8em;">
                            <i class="fas fa-arrow-up me-1"></i>{{ number_format($incomeThisMonth, 0, ',', ' ') }}
                        </span>
                        <span class="text-muted text-sm">ce mois-ci</span>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 4px; background: linear-gradient(90deg, #198754 0%, #a3cfbb 100%);"></div>
            </div>
        </div>

        <!-- Dépenses -->
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative bg-white" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold text-muted text-xs mb-1 ls-1" style="font-size: 0.75rem; letter-spacing: 1px;">Dépenses Totales</p>
                            <h3 class="display-6 fw-bold text-danger mb-0" style="font-size: 2.5rem;">{{ number_format($totalExpenses, 0, ',', ' ') }} <span class="fs-6 text-muted">FCFA</span></h3>
                        </div>
                        <div class="icon-shape bg-danger bg-opacity-10 text-danger rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger bg-opacity-10 text-danger me-2" style="padding: 0.5em 0.8em;">
                            <i class="fas fa-arrow-up me-1"></i>{{ number_format($expensesThisMonth, 0, ',', ' ') }}
                        </span>
                        <span class="text-muted text-sm">ce mois-ci</span>
                    </div>
                </div>
                <div class="position-absolute bottom-0 start-0 w-100" style="height: 4px; background: linear-gradient(90deg, #dc3545 0%, #f1aeb5 100%);"></div>
            </div>
        </div>

        <!-- Solde -->
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 shadow-sm h-100 overflow-hidden position-relative bg-dark text-white" style="border-radius: 20px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="text-uppercase fw-bold text-white-50 text-xs mb-1 ls-1" style="font-size: 0.75rem; letter-spacing: 1px;">CAISSE</p>
                            <h3 class="display-6 fw-bold text-white mb-0" style="font-size: 2.5rem;">{{ number_format($balance, 0, ',', ' ') }} <span class="fs-6 text-white-50">FCFA</span></h3>
                        </div>
                        <div class="icon-shape bg-white bg-opacity-25 text-white rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-pie fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        @if($balance >= 0)
                            <div class="d-flex align-items-center text-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <span class="fw-medium">Situation saine</span>
                            </div>
                        @else
                            <div class="d-flex align-items-center text-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <span class="fw-medium">Attention requise</span>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Background Decoration -->
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fas fa-chart-pie fa-5x text-white"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-number" data-target="{{ DB::table('students')->where('status', 'active')->count() }}">0</div>
            <div class="stat-label">Étudiants Actifs</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+12%</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-number" data-target="{{ DB::table('tp')->where('status', 'pending')->count() }}">0</div>
            <div class="stat-label">TP en Attente</div>
            <div class="stat-change negative">
                <i class="fas fa-arrow-down"></i>
                <span>-5%</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-number" data-target="8">0</div>
            <div class="stat-label">Formations</div>
            <div class="stat-change positive">
                <i class="fas fa-arrow-up"></i>
                <span>+2</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number" data-target="{{ DB::table('sessions')->whereNotNull('user_id')->distinct('user_id')->count('user_id') }}">0</div>
            <div class="stat-label">Utilisateurs En Ligne</div>
            <div class="stat-change positive">
                <i class="fas fa-circle"></i>
                <span>Live</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tv"></i>
            </div>
            <div class="stat-number" data-target="{{ DB::table('webtv_subscribers')->where('is_active', true)->count() }}">0</div>
            <div class="stat-label">Abonnés WebTV</div>
            <div class="stat-change positive">
                <i class="fas fa-bell"></i>
                <span>{{ DB::table('webtv_subscribers')->whereNotNull('verified_at')->count() }} vérifiés</span>
            </div>
        </div>

        @if(session('admin_role') === 'super_admin')
        <div class="stat-card" onclick="window.location='{{ route('admin.donations.index') }}'" style="cursor:pointer;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #0ea5e9 100%);">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div class="stat-number" data-target="{{ number_format($donationsCount ?? 0, 0, '', '') }}">0</div>
            <div class="stat-label">Dons (demandes)</div>
            <div class="stat-change positive">
                <i class="fas fa-coins"></i>
                <span>{{ number_format($donationsTotalAmount ?? 0, 0, ',', ' ') }} XOF déclarés</span>
            </div>
        </div>
        @endif

        @if(session('admin_role') !== 'assistant')
        <!-- Statistiques Paiements -->
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-number" data-target="{{ number_format($completedPayments, 0, '', '') }}">0</div>
            <div class="stat-label">Paiements Reçus (XOF)</div>
            <div class="stat-change positive">
                <i class="fas fa-check-circle"></i>
                <span>{{ $completedPaymentsCount }} paiements</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number" data-target="{{ number_format($pendingPayments, 0, '', '') }}">0</div>
            <div class="stat-label">Paiements En Attente (XOF)</div>
            <div class="stat-change negative">
                <i class="fas fa-hourglass-half"></i>
                <span>{{ $pendingPaymentsCount }} en attente</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-number" data-target="{{ number_format($paymentsThisMonth, 0, '', '') }}">0</div>
            <div class="stat-label">Paiements Ce Mois (XOF)</div>
            <div class="stat-change positive">
                <i class="fas fa-calendar-alt"></i>
                <span>{{ \Carbon\Carbon::now()->format('F Y') }}</span>
            </div>
        </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="activity-section">
        <div class="section-header">
            <h2 class="section-title">
                <span class="icon">
                    <i class="fas fa-bolt"></i>
                </span>
                Actions Rapides
            </h2>
            <a href="{{ route('admin.statistics.all') }}" class="view-all-btn">
                Voir tout <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="quick-actions-grid">
            <a href="{{ route('admin.students.index') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="quick-action-label">Gérer Étudiants</div>
            </a>

            <a href="{{ route('admin.travaux.pending') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="quick-action-label">Valider TP</div>
            </a>

            <a href="{{ route('admin.travaux.all') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="quick-action-label">Tous les TP</div>
            </a>

            <a href="{{ route('admin.rapports') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="quick-action-label">Rapports</div>
            </a>

            <a href="{{ route('admin.bibliotheque.index') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="quick-action-label">Bibliothèque</div>
            </a>

            <a href="{{ route('admin.parametres.index') }}" class="quick-action-card">
                <div class="quick-action-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="quick-action-label">Paramètres</div>
            </a>

            <a href="{{ route('admin.payments.index') }}" class="quick-action-card">
                <div class="quick-action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="quick-action-label">Gérer Paiements</div>
            </a>

            @if(session('admin_role') === 'super_admin')
            <a href="{{ route('admin.donations.index') }}" class="quick-action-card">
                <div class="quick-action-icon" style="background: linear-gradient(135deg, #10b981 0%, #0ea5e9 100%);">
                    <i class="fas fa-hand-holding-heart"></i>
                </div>
                <div class="quick-action-label">Gérer Dons</div>
            </a>
            @endif
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="activity-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="icon">
                            <i class="fas fa-chart-line"></i>
                        </span>
                        Inscriptions (7 derniers jours)
                    </h2>
                </div>
                <canvas id="inscriptionsChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="activity-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </span>
                        Répartition par Formation
                    </h2>
                </div>
                <canvas id="formationsChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Historique Connexions -->
        <div class="col-lg-8">
            <div class="activity-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="icon">
                            <i class="fas fa-user-clock"></i>
                        </span>
                        Dernières Connexions Étudiants
                    </h2>
                    <a href="{{ route('admin.connexions.index') }}" class="view-all-btn">
                        Tout voir
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if(isset($studentHistory) && count($studentHistory) > 0)
                    @foreach($studentHistory as $history)
                    <div class="activity-item">
                        <div class="activity-avatar" style="{{ $history->profile_photo ? 'background: none; padding: 0; overflow: hidden;' : '' }}">
                            @if($history->profile_photo)
                                @php
                                    $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrl($history->profile_photo);
                                @endphp
                                <img src="{{ $photoUrl }}" alt="{{ $history->full_name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                {{ strtoupper(substr($history->full_name, 0, 1)) }}
                            @endif
                        </div>
                        <div class="activity-content">
                            <div class="activity-name">
                                {{ $history->full_name }}
                            </div>
                            <div class="activity-description" style="color: #f97316;">
                                <i class="fas fa-graduation-cap me-1"></i> {{ $history->module ?? 'Non assigné' }}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="activity-time text-white mb-1">
                                <small class="text-muted me-1">Connexion:</small>
                                {{ \Carbon\Carbon::parse($history->last_login)->diffForHumans() }}
                            </div>
                            @if($history->last_activity)
                            <div class="activity-time" style="font-size: 0.8rem; color: #94a3b8;">
                                <small class="me-1">Dernière activité:</small>
                                {{ \Carbon\Carbon::parse($history->last_activity)->diffForHumans() }}
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 2rem; color: #64748b;">
                        <i class="fas fa-history" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                        <p>Aucune connexion récente enregistrée</p>
                    </div>
                @endif
            </div>

            <div class="activity-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="icon">
                            <i class="fas fa-history"></i>
                        </span>
                        Activités Récentes
                    </h2>
                    <a href="{{ route('admin.activites.index') }}" class="view-all-btn">
                        Tout voir
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @php
                    $recentActivities = DB::table('tp')
                        ->join('users', 'tp.user_id', '=', 'users.id')
                        ->leftJoin('students', 'users.id', '=', 'students.user_id')
                        ->select('tp.created_at', 'tp.title', 'students.first_name', 'students.last_name', 'tp.status')
                        ->orderBy('tp.created_at', 'desc')
                        ->limit(8)
                        ->get();
                @endphp

                @forelse($recentActivities as $activity)
                <div class="activity-item">
                    <div class="activity-avatar online">
                        {{ strtoupper(substr($activity->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($activity->last_name ?? 'N', 0, 1)) }}
                    </div>
                    <div class="activity-content">
                        <div class="activity-name">
                            {{ $activity->first_name }} {{ $activity->last_name }}
                        </div>
                        <div class="activity-description">
                            a soumis un TP : {{ $activity->title }}
                        </div>
                    </div>
                    <div class="activity-time">
                        {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}
                    </div>
                    <div>
                        @if($activity->status === 'validated')
                            <span class="activity-badge badge-validated">
                                <i class="fas fa-check me-1"></i>Validé
                            </span>
                        @elseif($activity->status === 'pending')
                            <span class="activity-badge badge-pending">
                                <i class="fas fa-clock me-1"></i>En attente
                            </span>
                        @else
                            <span class="activity-badge badge-rejected">
                                <i class="fas fa-times me-1"></i>Rejeté
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 3rem; color: #64748b;">
                    <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>Aucune activité récente</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Étudiants En Ligne -->
        <div class="col-lg-4">
            <div class="activity-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <span class="icon">
                            <i class="fas fa-users"></i>
                        </span>
                        En Ligne
                    </h2>
                </div>

                @php
                    $onlineStudents = DB::table('sessions')
                        ->join('users', 'sessions.user_id', '=', 'users.id')
                        ->leftJoin('students', 'users.id', '=', 'students.user_id')
                        ->whereNotNull('sessions.user_id')
                        ->whereNotNull('students.id')
                        ->select('students.id', 'students.first_name', 'students.last_name', 'students.program')
                        ->groupBy('students.id', 'students.first_name', 'students.last_name', 'students.program')
                        ->limit(6)
                        ->get();
                @endphp

                @forelse($onlineStudents as $student)
                <div class="activity-item">
                    <div class="activity-avatar online">
                        {{ strtoupper(substr($student->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? 'N', 0, 1)) }}
                    </div>
                    <div class="activity-content">
                        <div class="activity-name">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </div>
                        <div class="activity-description">
                            {{ $student->program ?? 'Formation non définie' }}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 2rem; color: #64748b;">
                    <i class="fas fa-user-slash" style="font-size: 2rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>Aucun étudiant en ligne</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// Update time and date
function updateDateTime() {
    const now = new Date();
    const time = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    const date = now.toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });

    document.getElementById('currentTime').textContent = time;
    document.getElementById('currentDate').textContent = date;
}

// Update greeting based on time
function updateGreeting() {
    const hour = new Date().getHours();
    let greeting;

    if (hour < 12) greeting = 'Bon matin';
    else if (hour < 18) greeting = 'Bon après-midi';
    else greeting = 'Bonsoir';

    document.getElementById('greeting').textContent = greeting;
}

// Animate numbers with easing
function animateNumbers() {
    document.querySelectorAll('.stat-number').forEach(element => {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 2000;
        const start = 0;
        const startTime = performance.now();

        function easeOutQuart(x) {
            return 1 - Math.pow(1 - x, 4);
        }

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const eased = easeOutQuart(progress);
            const current = Math.floor(start + (target - start) * eased);

            element.textContent = current.toLocaleString('fr-FR');

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }

        requestAnimationFrame(update);
    });
}

// Refresh activities
function refreshActivities() {
    // Simulation de rafraîchissement (vous pouvez ajouter un appel AJAX ici)
    console.log('Actualisation des activités...');

    // Animation de refresh
    const activitySection = document.querySelector('.activity-section');
    if (activitySection) {
        activitySection.style.opacity = '0.5';
        setTimeout(() => {
            activitySection.style.opacity = '1';
        }, 500);
    }
}

// Auto-refresh data every 30 seconds
setInterval(() => {
    refreshActivities();
}, 30000);

// Add ripple effect to cards
document.querySelectorAll('.stat-card, .quick-action-card, .activity-item').forEach(card => {
    card.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        ripple.style.cssText = `
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(249, 115, 22, 0.5);
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
            left: ${x}px;
            top: ${y}px;
        `;

        this.style.position = 'relative';
        this.style.overflow = 'hidden';
        this.appendChild(ripple);

        setTimeout(() => ripple.remove(), 600);
    });
});

// Add ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(20);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Intersection Observer for scroll animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, {
    threshold: 0.1
});

document.querySelectorAll('.activity-item').forEach(item => {
    item.style.opacity = '0';
    item.style.transform = 'translateY(20px)';
    item.style.transition = 'all 0.5s ease';
    observer.observe(item);
});

// Initialize Charts
function initCharts() {
    // Graphique des inscriptions (7 derniers jours)
    const inscriptionsCtx = document.getElementById('inscriptionsChart');
    if (inscriptionsCtx) {
        @php
            $last7Days = [];
            $inscriptionsData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = \Carbon\Carbon::now()->subDays($i);
                $last7Days[] = $date->format('d M');
                $count = DB::table('students')
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->count();
                $inscriptionsData[] = $count;
            }
        @endphp

        new Chart(inscriptionsCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($last7Days) !!},
                datasets: [{
                    label: 'Nouvelles inscriptions',
                    data: {!! json_encode($inscriptionsData) !!},
                    borderColor: '#f97316',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f97316',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#94a3b8',
                        borderColor: '#f97316',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#94a3b8',
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(51, 65, 85, 0.3)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#94a3b8'
                        },
                        grid: {
                            color: 'rgba(51, 65, 85, 0.3)'
                        }
                    }
                }
            }
        });
    }

    // Graphique répartition par formation
    const formationsCtx = document.getElementById('formationsChart');
    if (formationsCtx) {
        @php
            // Compter les étudiants par formation de manière précise
            $designCount = DB::table('students')->where('program', 'LIKE', 'Design Graphique')->count();
            $cmCount = DB::table('students')->where('program', 'LIKE', 'Community%')->whereNotLike('program', '%Design%')->count();
            $designCmCount = DB::table('students')->where('program', 'LIKE', '%Design%')->where('program', 'LIKE', '%Community%')->count();
            $infoCount = DB::table('students')->where(function($q) {
                $q->where('program', 'LIKE', '%Gestion%')->orWhere('program', 'LIKE', '%Informatique%');
            })->whereNotLike('program', '%Design%')->whereNotLike('program', '%Community%')->count();
            $iaCount = DB::table('students')->where(function($q) {
                $q->where('program', 'LIKE', '%Intelligence%')->orWhere('program', 'LIKE', '%IA%');
            })->whereNotLike('program', '%Design%')->whereNotLike('program', '%Community%')->count();
        @endphp

        new Chart(formationsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Design Graphique', 'Community Management', 'Design & Community', 'Gestion Informatique', 'Intelligence Artificielle'],
                datasets: [{
                    data: [{{ $designCount }}, {{ $cmCount }}, {{ $designCmCount }}, {{ $infoCount }}, {{ $iaCount }}],
                    backgroundColor: [
                        '#667eea',
                        '#f093fb',
                        '#ff6b6b',
                        '#4facfe',
                        '#43e97b'
                    ],
                    borderColor: '#0f172a',
                    borderWidth: 3,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#94a3b8',
                            padding: 15,
                            font: {
                                size: 12
                            },
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        padding: 12,
                        titleColor: '#fff',
                        bodyColor: '#94a3b8',
                        borderColor: '#f97316',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateDateTime();
    updateGreeting();
    animateNumbers();
    initCharts();
    setInterval(updateDateTime, 1000);

    // Show success message
    console.log('✅ Dashboard chargé avec succès');
    console.log('🔄 Auto-refresh activé (30s)');
    console.log('📊 Graphiques initialisés');
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + R pour rafraîchir les activités
    if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
        e.preventDefault();
        refreshActivities();
    }
});
</script>
@endpush
