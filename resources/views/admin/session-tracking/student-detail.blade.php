@extends('layouts.admin')

@section('title', 'Session Tracking - Étudiant')

@section('content')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.session-tracking.index') }}" class="btn btn-outline-secondary mb-2">
                    <i class="fas fa-arrow-left me-1"></i>Retour
                </a>
                <h1 class="h3 mb-0">
                    <i class="fas fa-user me-2"></i>{{ $student->first_name }} {{ $student->last_name }}
                </h1>
                <p class="text-muted mb-0">{{ $student->user->email ?? '' }} • {{ $student->program }}</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row g-4 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Sessions Totales</p>
                                <h3 class="mb-0">{{ $stats['total_sessions'] }}</h3>
                            </div>
                            <div class="text-primary">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Temps Total</p>
                                <h3 class="mb-0">{{ $stats['total_hours'] }}h {{ $stats['total_minutes'] }}m</h3>
                            </div>
                            <div class="text-success">
                                <i class="fas fa-hourglass-half fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Moyenne/Session</p>
                                <h3 class="mb-0">
                                    {{ $stats['total_sessions'] > 0 ? gmdate('H:i:s', $stats['total_seconds'] / $stats['total_sessions']) : '00:00:00' }}
                                </h3>
                            </div>
                            <div class="text-info">
                                <i class="fas fa-chart-line fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Formation</p>
                                <h3 class="mb-0">{{ $student->program }}</h3>
                            </div>
                            <div class="text-warning">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Period Filter -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Période</label>
                        <select name="period" class="form-select">
                            <option value="today" {{ $period === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                            <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Ce mois</option>
                            <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Tout</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i>Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Time by Seance -->
        @if ($stats['by_seance']->isNotEmpty())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Temps par Séance
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Séance</th>
                                    <th>Nombre de Sessions</th>
                                    <th>Temps Total</th>
                                    <th>Moyenne/Session</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats['by_seance'] as $seanceStat)
                                    <tr>
                                        <td>{{ $seanceStat->seance?->title ?? 'N/A' }}</td>
                                        <td>{{ $seanceStat->count }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ gmdate('H:i:s', $seanceStat->total_seconds) }}
                                            </span>
                                        </td>
                                        <td>{{ gmdate('H:i:s', $seanceStat->total_seconds / $seanceStat->count) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Time by Page Type -->
        @if ($stats['by_page_type']->isNotEmpty())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-desktop me-2 text-info"></i>Temps par Type de Page
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Type de Page</th>
                                    <th>Nombre de Sessions</th>
                                    <th>Temps Total</th>
                                    <th>Moyenne/Session</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stats['by_page_type'] as $pageType => $pageStat)
                                    <tr>
                                        <td>
                                            <span class="badge bg-info">{{ $pageType }}</span>
                                        </td>
                                        <td>{{ $pageStat->count }}</td>
                                        <td>
                                            <span class="badge bg-success">
                                                {{ gmdate('H:i:s', $pageStat->total_seconds) }}
                                            </span>
                                        </td>
                                        <td>{{ gmdate('H:i:s', $pageStat->total_seconds / $pageStat->count) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Sessions Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Historique des Sessions
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type de Page</th>
                                <th>Séance</th>
                                <th>Début</th>
                                <th>Fin</th>
                                <th>Durée</th>
                                <th>IP Address</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sessions as $session)
                                <tr>
                                    <td>{{ $session->id }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $session->page_type }}</span>
                                    </td>
                                    <td>{{ $session->seance?->title ?? 'N/A' }}</td>
                                    <td>{{ $session->session_start?->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ $session->session_end?->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ gmdate('H:i:s', $session->duration_seconds) }}
                                        </span>
                                    </td>
                                    <td>{{ $session->ip_address }}</td>
                                    <td>
                                        @if ($session->is_active)
                                            <span class="badge bg-warning">En cours</span>
                                        @else
                                            <span class="badge bg-secondary">Terminée</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $sessions->links() }}
            </div>
        </div>
    </div>
@endsection
