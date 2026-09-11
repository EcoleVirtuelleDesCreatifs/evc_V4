@extends('layouts.admin')

@section('title', 'Marquer les présences - ' . $seance->title)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Marquer les présences</h1>
            <p class="text-muted mb-0">
                <i class="far fa-calendar me-1"></i>{{ $seance->title }} — {{ $seance->scheduled_at->format('d/m/Y H:i') }}
                <span class="badge {{ $seance->type === 'online' ? 'bg-info text-dark' : 'bg-success' }} ms-2">
                    {{ $seance->type === 'online' ? 'En ligne' : 'Présentiel' }}
                </span>
            </p>
        </div>
        <div class="d-flex gap-2">
            @if(in_array($seance->type, ['onsite', 'hybrid']))
                <a href="{{ route('admin.seances.qr', $seance) }}" class="btn btn-outline-dark">
                    <i class="fas fa-qrcode me-1"></i> QR Code
                </a>
            @endif
            <button type="button" class="btn btn-outline-success" onclick="markAll('present')">
                <i class="fas fa-check-double me-1"></i> Tous présents
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Statistiques en temps réel (recalculées automatiquement au changement de statut) --}}
    <div class="row g-3 mb-4" id="attendance-stats">
        <div class="col-6 col-md-2">
            <div class="card text-center h-100">
                <div class="card-body py-3">
                    <div class="h4 mb-0" id="stat-total">{{ $stats['total'] }}</div>
                    <small class="text-muted">Étudiants</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card text-center h-100 border-success">
                <div class="card-body py-3">
                    <div class="h4 mb-0 text-success" id="stat-present">{{ $stats['present'] }}</div>
                    <small class="text-muted">Présents</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card text-center h-100 border-warning">
                <div class="card-body py-3">
                    <div class="h4 mb-0 text-warning" id="stat-late">{{ $stats['late'] }}</div>
                    <small class="text-muted">Retards</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card text-center h-100 border-danger">
                <div class="card-body py-3">
                    <div class="h4 mb-0 text-danger" id="stat-absent">{{ $stats['absent'] }}</div>
                    <small class="text-muted">Absents</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card text-center h-100 border-info">
                <div class="card-body py-3">
                    <div class="h4 mb-0 text-info" id="stat-excused">{{ $stats['excused'] }}</div>
                    <small class="text-muted">Excusés</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-2">
            <div class="card text-center h-100 border-primary">
                <div class="card-body py-3">
                    <div class="h4 mb-0 text-primary" id="stat-rate">{{ $stats['rate'] }}%</div>
                    <small class="text-muted">Taux présence</small>
                </div>
            </div>
        </div>
    </div>

    <div class="progress mb-4" style="height: 8px;" title="Répartition des statuts">
        <div class="progress-bar bg-success" id="bar-present" style="width: 0%"></div>
        <div class="progress-bar bg-warning" id="bar-late" style="width: 0%"></div>
        <div class="progress-bar bg-danger" id="bar-absent" style="width: 0%"></div>
        <div class="progress-bar bg-info" id="bar-excused" style="width: 0%"></div>
    </div>

    <div class="card">
        <div class="card-body p-0 table-responsive">
            <form method="POST" action="{{ route('admin.seances.attendance.save', $seance) }}">
                @csrf
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Étudiant</th>
                            <th>Email</th>
                            <th>Statut</th>
                            <th>Mode</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                $attendance = $attendances[$student->id] ?? null;
                                $record = old("attendances.{$student->id}.status", $attendance->status ?? 'absent');
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $student->first_name }} {{ $student->last_name }}</strong>
                                    @if($attendance && $attendance->check_in_at)
                                        <span class="badge bg-success ms-1" title="Pointage {{ $attendance->check_method === 'qrcode' ? 'QR code' : $attendance->check_method }}">
                                            <i class="fas fa-qrcode me-1"></i>{{ \Carbon\Carbon::parse($attendance->check_in_at)->format('H:i') }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <select name="attendances[{{ $student->id }}][status]" class="form-select form-select-sm attendance-status">
                                        <option value="absent" {{ $record == 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="present" {{ $record == 'present' ? 'selected' : '' }}>Présent</option>
                                        <option value="late" {{ $record == 'late' ? 'selected' : '' }}>En retard</option>
                                        <option value="excused" {{ $record == 'excused' ? 'selected' : '' }}>Excusé</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="attendances[{{ $student->id }}][check_method]" class="form-select form-select-sm">
                                        <option value="manual" {{ (old("attendances.{$student->id}.check_method", $attendance->check_method ?? 'manual')) == 'manual' ? 'selected' : '' }}>Manuel</option>
                                        <option value="meet" {{ (old("attendances.{$student->id}.check_method", $attendance->check_method ?? '')) == 'meet' ? 'selected' : '' }}>Google Meet</option>
                                        <option value="qrcode" {{ (old("attendances.{$student->id}.check_method", $attendance->check_method ?? '')) == 'qrcode' ? 'selected' : '' }}>QR code</option>
                                        <option value="auto" {{ (old("attendances.{$student->id}.check_method", $attendance->check_method ?? '')) == 'auto' ? 'selected' : '' }}>Auto</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="attendances[{{ $student->id }}][notes]"
                                           class="form-control form-control-sm"
                                           value="{{ old("attendances.{$student->id}.notes", $attendance->notes ?? '') }}">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Aucun étudiant actif trouvé pour la formation {{ $seance->formation }}.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-3 border-top d-flex gap-2 justify-content-end">
                    <a href="{{ route('admin.seances.index') }}" class="btn btn-outline-secondary">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer les présences
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function refreshStats() {
        const selects = document.querySelectorAll('select.attendance-status');
        const counts = { present: 0, late: 0, absent: 0, excused: 0 };
        selects.forEach(function (s) {
            if (counts[s.value] !== undefined) counts[s.value]++;
        });
        const total = selects.length;
        const rate = total > 0 ? Math.round(((counts.present + counts.late) / total) * 1000) / 10 : 0;

        document.getElementById('stat-total').textContent = total;
        document.getElementById('stat-present').textContent = counts.present;
        document.getElementById('stat-late').textContent = counts.late;
        document.getElementById('stat-absent').textContent = counts.absent;
        document.getElementById('stat-excused').textContent = counts.excused;
        document.getElementById('stat-rate').textContent = rate + '%';

        const pct = function (n) { return total > 0 ? (n / total) * 100 : 0; };
        document.getElementById('bar-present').style.width = pct(counts.present) + '%';
        document.getElementById('bar-late').style.width = pct(counts.late) + '%';
        document.getElementById('bar-absent').style.width = pct(counts.absent) + '%';
        document.getElementById('bar-excused').style.width = pct(counts.excused) + '%';
    }

    function markAll(status) {
        document.querySelectorAll('select.attendance-status').forEach(function (select) {
            select.value = status;
        });
        refreshStats();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('select.attendance-status').forEach(function (select) {
            select.addEventListener('change', refreshStats);
        });
        refreshStats();
    });
</script>
@endpush
