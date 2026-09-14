@extends('layouts.admin')

@section('title', 'Session Tracking Admin')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">
            <i class="fas fa-clock me-2"></i>Session Tracking
        </h1>
        <a href="{{ route('admin.session-tracking.export', request()->all()) }}" class="btn btn-success">
            <i class="fas fa-download me-1"></i>Exporter CSV
        </a>
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
                            <p class="text-muted mb-1">Étudiants Uniques</p>
                            <h3 class="mb-0">{{ $stats['unique_students'] }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="fas fa-users fa-2x"></i>
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
                            <h3 class="mb-0">{{ $stats['total_sessions'] > 0 ? gmdate('H:i:s', $stats['total_seconds'] / $stats['total_sessions']) : '00:00:00' }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Période</label>
                    <select name="period" class="form-select">
                        <option value="today" {{ $filters['period'] === 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                        <option value="week" {{ $filters['period'] === 'week' ? 'selected' : '' }}>Cette semaine</option>
                        <option value="month" {{ $filters['period'] === 'month' ? 'selected' : '' }}>Ce mois</option>
                        <option value="all" {{ $filters['period'] === 'all' ? 'selected' : '' }}>Tout</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Formation</label>
                    <select name="formation" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($formations as $formation)
                            <option value="{{ $formation }}" {{ $filters['formation'] === $formation ? 'selected' : '' }}>{{ $formation }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ID Étudiant</label>
                    <input type="text" name="student_id" class="form-control" value="{{ $filters['student_id'] }}" placeholder="ID étudiant">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Top Students -->
    @if($stats['top_students']->isNotEmpty())
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-trophy me-2 text-warning"></i>Top Étudiants (Temps)
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Email</th>
                            <th>Temps Total</th>
                            <th>Formation</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['top_students'] as $student)
                        <tr>
                            <td>{{ $student->student->first_name }} {{ $student->student->last_name }}</td>
                            <td>{{ $student->student->user->email ?? '' }}</td>
                            <td>
                                <span class="badge bg-success">
                                    {{ gmdate('H:i:s', $student->total_seconds) }}
                                </span>
                            </td>
                            <td>{{ $student->student->program }}</td>
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
                <i class="fas fa-list me-2"></i>Sessions
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Étudiant</th>
                            <th>Formation</th>
                            <th>Type de Page</th>
                            <th>Séance</th>
                            <th>Début</th>
                            <th>Fin</th>
                            <th>Durée</th>
                            <th>IP Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sessions as $session)
                        <tr>
                            <td>{{ $session->id }}</td>
                            <td>
                                <a href="{{ route('admin.session-tracking.student', $session->student_id) }}">
                                    {{ $session->student->first_name }} {{ $session->student->last_name }}
                                </a>
                            </td>
                            <td>{{ $session->student->program }}</td>
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
                                <a href="{{ route('admin.session-tracking.student', $session->student_id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
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