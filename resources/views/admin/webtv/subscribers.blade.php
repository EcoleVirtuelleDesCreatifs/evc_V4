@extends('layouts.admin')

@section('title', 'Abonnés WebTV - Admin EVC')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    body {
        background: #0f172a;
    }

    .page-header {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(249, 115, 22, 0.3);
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
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
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(249, 115, 22, 0.2);
        border-color: #f97316;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #f97316 0%, #ea580c 100%);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        color: #94a3b8;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .content-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .card-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #334155;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .card-title .icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .action-buttons {
        display: flex;
        gap: 0.75rem;
    }

    .btn-custom {
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        color: white;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(249, 115, 22, 0.3);
        color: white;
    }

    .btn-secondary-custom {
        background: rgba(249, 115, 22, 0.1);
        color: #f97316;
        border: 1px solid rgba(249, 115, 22, 0.3);
    }

    .btn-secondary-custom:hover {
        background: rgba(249, 115, 22, 0.2);
        color: #f97316;
    }

    .table-custom {
        color: white;
    }

    .table-custom thead th {
        background: rgba(249, 115, 22, 0.1);
        border-bottom: 2px solid #f97316;
        color: #f97316;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem;
    }

    .table-custom tbody td {
        padding: 1rem;
        border-bottom: 1px solid #334155;
        vertical-align: middle;
    }

    .table-custom tbody tr {
        transition: all 0.3s ease;
    }

    .table-custom tbody tr:hover {
        background: rgba(249, 115, 22, 0.05);
    }

    .badge-custom {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-success {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .badge-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .badge-warning {
        background: rgba(251, 191, 36, 0.1);
        color: #fbbf24;
    }

    .badge-info {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .btn-action {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        border: none;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-verify {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .btn-verify:hover {
        background: rgba(34, 197, 94, 0.2);
    }

    .btn-deactivate {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .btn-deactivate:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    .btn-activate {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .btn-activate:hover {
        background: rgba(34, 197, 94, 0.2);
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.2);
    }

    /* DataTables customization */
    .dataTables_wrapper {
        color: white;
    }

    .dataTables_filter input,
    .dataTables_length select {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid #334155;
        border-radius: 8px;
        color: white;
        padding: 0.5rem;
    }

    .dataTables_info,
    .dataTables_paginate {
        color: #94a3b8 !important;
    }

    .page-link {
        background: rgba(249, 115, 22, 0.1);
        border: 1px solid rgba(249, 115, 22, 0.3);
        color: #f97316;
    }

    .page-link:hover {
        background: rgba(249, 115, 22, 0.2);
        color: #f97316;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-color: #f97316;
    }

    /* Modal personnalisé */
    .modal-content {
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 20px;
    }

    .modal-header {
        border-bottom: 1px solid #334155;
    }

    .modal-title {
        color: white;
    }

    .modal-body {
        color: #94a3b8;
    }

    .btn-close {
        filter: invert(1);
    }

    .form-control {
        background: rgba(15, 23, 42, 0.8);
        border: 1px solid #334155;
        color: white;
    }

    .form-control:focus {
        background: rgba(15, 23, 42, 0.9);
        border-color: #f97316;
        color: white;
        box-shadow: 0 0 0 0.2rem rgba(249, 115, 22, 0.25);
    }

    .form-label {
        color: #94a3b8;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header">
        <h1 class="page-title">
            <i class="fas fa-tv me-2"></i>
            Abonnés WebTV
        </h1>
        <p class="page-subtitle mb-0">
            Gérez les abonnements aux notifications de la WebTV
        </p>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Abonnés</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $stats['actifs'] }}</div>
            <div class="stat-label">Actifs</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-number">{{ $stats['verifies'] }}</div>
            <div class="stat-label">Vérifiés</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-user-times"></i>
            </div>
            <div class="stat-number">{{ $stats['non_verifies'] }}</div>
            <div class="stat-label">Non Vérifiés</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-ban"></i>
            </div>
            <div class="stat-number">{{ $stats['inactifs'] }}</div>
            <div class="stat-label">Inactifs</div>
        </div>
    </div>

    <!-- Alerts -->
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

    <!-- Liste des abonnés -->
    <div class="content-card">
        <div class="card-header-custom">
            <h2 class="card-title">
                <span class="icon">
                    <i class="fas fa-list"></i>
                </span>
                Liste des Abonnés
            </h2>
            <div class="action-buttons">
                <button class="btn-custom btn-secondary-custom" data-bs-toggle="modal" data-bs-target="#notifyAllModal">
                    <i class="fas fa-bell"></i>
                    Notifier Tous
                </button>
                <a href="{{ route('admin.webtv.export') }}" class="btn-custom btn-primary-custom">
                    <i class="fas fa-download"></i>
                    Exporter CSV
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table id="subscribersTable" class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Vérifié</th>
                        <th>Date d'inscription</th>
                        <th>Dernière notification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subscribers as $subscriber)
                    <tr>
                        <td>{{ $subscriber->id }}</td>
                        <td>{{ $subscriber->name ?? 'N/A' }}</td>
                        <td>{{ $subscriber->email }}</td>
                        <td>
                            @if($subscriber->is_active)
                                <span class="badge-custom badge-success">
                                    <i class="fas fa-check-circle me-1"></i>Actif
                                </span>
                            @else
                                <span class="badge-custom badge-danger">
                                    <i class="fas fa-times-circle me-1"></i>Inactif
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($subscriber->verified_at)
                                <span class="badge-custom badge-success">
                                    <i class="fas fa-check me-1"></i>Oui
                                </span>
                            @else
                                <span class="badge-custom badge-warning">
                                    <i class="fas fa-clock me-1"></i>Non
                                </span>
                            @endif
                        </td>
                        <td>{{ $subscriber->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($subscriber->last_notified_at)
                                {{ $subscriber->last_notified_at->diffForHumans() }}
                            @else
                                <span class="text-muted">Jamais</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                @if(!$subscriber->verified_at)
                                    <form action="{{ route('admin.webtv.verify', $subscriber->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-verify" title="Vérifier">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($subscriber->is_active)
                                    <form action="{{ route('admin.webtv.deactivate', $subscriber->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-deactivate" title="Désactiver">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.webtv.activate', $subscriber->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn-action btn-activate" title="Activer">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('admin.webtv.destroy', $subscriber->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet abonné ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Notify All -->
<div class="modal fade" id="notifyAllModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-bell me-2"></i>
                    Notifier tous les abonnés
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.webtv.notifyAll') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label">Sujet de l'email</label>
                        <input type="text" class="form-control" id="subject" name="subject" required>
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Message</label>
                        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Cette notification sera envoyée à <strong>{{ $stats['verifies'] }}</strong> abonné(s) vérifié(s) et actif(s).
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn-custom btn-primary-custom">
                        <i class="fas fa-paper-plane me-2"></i>
                        Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#subscribersTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/fr-FR.json'
            },
            order: [[5, 'desc']],
            pageLength: 25,
            responsive: true
        });
    });
</script>
@endpush
