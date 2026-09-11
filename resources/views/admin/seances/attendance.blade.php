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
                                </td>
                                <td>{{ $student->email }}</td>
                                <td>
                                    <select name="attendances[{{ $student->id }}][status]" class="form-select form-select-sm">
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
    function markAll(status) {
        document.querySelectorAll('select[name$="[status]"]').forEach(function (select) {
            select.value = status;
        });
    }
</script>
@endpush
