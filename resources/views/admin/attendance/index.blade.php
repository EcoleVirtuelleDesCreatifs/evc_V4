@extends('layouts.admin')

@section('title', 'Présences & Assiduité')
@section('page-title', 'Présences & Assiduité')

@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-3"><i class="fas fa-clipboard-check me-2"></i>Présences & Assiduité</h1>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="display-6 fw-bold text-primary">{{ $stats['seances_today']->count() }}</div>
                    <div class="text-muted small">Séances aujourd'hui</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="display-6 fw-bold text-info">{{ $stats['expected'] }}</div>
                    <div class="text-muted small">Étudiants attendus</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="display-6 fw-bold text-success">{{ $stats['present'] }}</div>
                    <div class="text-muted small">Présents</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="display-6 fw-bold text-warning">{{ $stats['late'] }}</div>
                    <div class="text-muted small">Retards</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card text-center border-0 shadow-sm">
                <div class="card-body">
                    <div class="display-6 fw-bold text-danger">{{ $stats['absent'] }}</div>
                    <div class="text-muted small">Absents</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Taux global d'assiduité</h5>
                    <div class="progress" style="height: 24px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min($stats['rate'], 100) }}%" aria-valuenow="{{ $stats['rate'] }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $stats['rate'] }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtres</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.attendance.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Formation</label>
                    <select name="formation" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($formations as $formation)
                            <option value="{{ $formation }}" {{ ($filters['formation'] ?? '') == $formation ? 'selected' : '' }}>{{ $formation }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Séance</label>
                    <select name="session_id" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($stats['seances'] as $seance)
                            <option value="{{ $seance->id }}" {{ ($filters['session_id'] ?? '') == $seance->id ? 'selected' : '' }}>{{ $seance->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Étudiant</label>
                    <select name="student_id" class="form-select">
                        <option value="">Tous</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ ($filters['student_id'] ?? '') == $student->id ? 'selected' : '' }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Formateur</label>
                    <input type="text" name="formateur" class="form-control" value="{{ $filters['formateur'] ?? '' }}" placeholder="Nom du formateur">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ $filters['date'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Du</label>
                    <input type="date" name="from" class="form-control" value="{{ $filters['from'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Au</label>
                    <input type="date" name="to" class="form-control" value="{{ $filters['to'] ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Mode</label>
                    <select name="mode" class="form-select">
                        <option value="">Tous</option>
                        <option value="onsite" {{ ($filters['mode'] ?? '') == 'onsite' ? 'selected' : '' }}>Présentiel</option>
                        <option value="online" {{ ($filters['mode'] ?? '') == 'online' ? 'selected' : '' }}>En ligne</option>
                        <option value="hybrid" {{ ($filters['mode'] ?? '') == 'hybrid' ? 'selected' : '' }}>Hybride</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut de séance</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="scheduled" {{ ($filters['status'] ?? '') == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                        <option value="ongoing" {{ ($filters['status'] ?? '') == 'ongoing' ? 'selected' : '' }}>En cours</option>
                        <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Terminée</option>
                        <option value="cancelled" {{ ($filters['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filtrer</button>
                    <a href="{{ route('admin.attendance.index') }}" class="btn btn-outline-secondary">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Présences enregistrées</h5>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Séance</th>
                        <th>Date</th>
                        <th>Formation</th>
                        <th>Étudiant</th>
                        <th>Mode</th>
                        <th>Arrivée</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stats['attendances'] as $attendance)
                        <tr>
                            <td><strong>{{ $attendance->seance->title ?? '-' }}</strong></td>
                            <td>{{ $attendance->seance->scheduled_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td>{{ $attendance->seance->formation ?? '-' }}</td>
                            <td>{{ $attendance->student->first_name ?? '' }} {{ $attendance->student->last_name ?? '' }}</td>
                            <td>{{ $attendance->check_method }}</td>
                            <td>{{ $attendance->check_in_at?->format('H:i') ?? $attendance->recorded_at?->format('H:i') ?? '-' }}</td>
                            <td>
                                @if($attendance->status === 'present')
                                    <span class="badge bg-success">Présent</span>
                                @elseif($attendance->status === 'late')
                                    <span class="badge bg-warning text-dark">En retard</span>
                                @elseif($attendance->status === 'absent')
                                    <span class="badge bg-danger">Absent</span>
                                @elseif($attendance->status === 'excused')
                                    <span class="badge bg-info">Excusé</span>
                                @else
                                    <span class="badge bg-secondary">{{ $attendance->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                Aucune présence trouvée pour les filtres sélectionnés.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
