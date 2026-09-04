@extends('layouts.ki-admin')

@section('title', 'Mon assiduité')
@section('page-title', 'Mon assiduité')

@push('styles')
<style>
    .assiduite-page { color: #f8fafc; }
    .assiduite-hero {
        background: linear-gradient(135deg, #0b1220 0%, #0e1d3a 50%, #312e81 100%);
        border-radius: 18px;
        padding: 2rem 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid rgba(191, 219, 254, 0.12);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
    }
    .assiduite-hero h1 { color: #fff; font-weight: 900; margin: 0 0 0.5rem 0; }
    .assiduite-hero p { color: #cbd5e1; margin: 0; }
    .stat-card {
        background: #0b1220;
        border: 1px solid rgba(191, 219, 254, 0.12);
        border-radius: 16px;
        padding: 1.25rem;
        text-align: center;
    }
    .stat-value {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 0.25rem;
    }
    .stat-label { color: #94a3b8; font-size: 0.85rem; font-weight: 600; }
    .stat-rate { color: #34d399; }
    .stat-present { color: #34d399; }
    .stat-late { color: #fbbf24; }
    .stat-absent { color: #f87171; }
    .stat-excused { color: #a5b4fc; }
    .assiduite-table {
        background: #0b1220;
        border: 1px solid rgba(191, 219, 254, 0.12);
        border-radius: 16px;
        overflow: hidden;
    }
    .assiduite-table th { background: #081126; color: #fff; font-weight: 700; padding: 1rem; }
    .assiduite-table td { color: #e2e8f0; padding: 1rem; border-top: 1px solid rgba(191, 219, 254, 0.08); }
    .badge-seance { border-radius: 20px; padding: 0.35rem 0.7rem; font-weight: 700; font-size: 0.78rem; }
    .badge-present { background: rgba(16, 185, 129, 0.18); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.35); }
    .badge-absent { background: rgba(239, 68, 68, 0.18); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.35); }
    .badge-late { background: rgba(245, 158, 11, 0.18); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.35); }
    .badge-excused { background: rgba(99, 102, 241, 0.18); color: #a5b4fc; border: 1px solid rgba(99, 102, 241, 0.35); }
    .badge-unknown { background: rgba(255, 255, 255, 0.08); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.12); }
    .progress-custom {
        background: rgba(255, 255, 255, 0.08);
        border-radius: 10px;
        height: 12px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    .progress-fill {
        background: linear-gradient(90deg, #34d399 0%, #60a5fa 100%);
        height: 100%;
        border-radius: 10px;
        transition: width 0.4s ease;
    }
    .empty-assiduite {
        text-align: center;
        color: #94a3b8;
        padding: 3rem 1rem;
    }
    .empty-assiduite i { font-size: 3rem; color: #64748b; margin-bottom: 1rem; }
</style>
@endpush

@section('content')
<div class="assiduite-page">
    <div class="assiduite-hero">
        <h1><i class="fas fa-clipboard-check me-2"></i>Mon assiduité</h1>
        <p>Suivez votre taux de présence pour les séances de votre formation.</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-value stat-rate">{{ $stats['rate'] }}%</div>
                <div class="stat-label">Taux de présence</div>
                <div class="progress-custom">
                    <div class="progress-fill" style="width: {{ $stats['rate'] }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-value stat-present">{{ $stats['present'] }}</div>
                <div class="stat-label">Présences</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-value stat-late">{{ $stats['late'] }}</div>
                <div class="stat-label">Retards</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-value stat-absent">{{ $stats['absent'] }}</div>
                <div class="stat-label">Absences</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-value stat-excused">{{ $stats['excused'] }}</div>
                <div class="stat-label">Excusés</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-value text-white">{{ $stats['completed'] }}</div>
                <div class="stat-label">Séances terminées</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card">
                <div class="stat-value text-white">{{ $stats['total'] }}</div>
                <div class="stat-label">Séances prévues</div>
            </div>
        </div>
    </div>

    <h2 class="h5 text-white fw-bold mb-3"><i class="fas fa-list me-2"></i>Historique</h2>
    @if($seances->isEmpty())
        <div class="empty-assiduite">
            <i class="fas fa-clipboard-list"></i>
            <h4>Aucune séance enregistrée</h4>
            <p>Votre formateur n'a pas encore planifié de séances pour votre formation.</p>
        </div>
    @else
        <div class="table-responsive assiduite-table">
            <table class="table table-borderless m-0">
                <thead>
                    <tr>
                        <th>Séance</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Mode de marquage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($seances as $seance)
                        @php $attendance = $attendances[$seance->id] ?? null; @endphp
                        <tr>
                            <td>
                                <strong>{{ $seance->title }}</strong>
                                @if($seance->type === 'presentiel' && $seance->location)
                                    <div class="small text-muted">{{ $seance->location }}</div>
                                @endif
                            </td>
                            <td>{{ $seance->scheduled_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($seance->type === 'online')
                                    <span class="badge-seance" style="background: rgba(37, 99, 235, 0.14); color: #60a5fa; border: 1px solid rgba(37, 99, 235, 0.25);"><i class="fas fa-video me-1"></i>En ligne</span>
                                @else
                                    <span class="badge-seance" style="background: rgba(16, 185, 129, 0.14); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.25);"><i class="fas fa-map-marker-alt me-1"></i>Présentiel</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance)
                                    <span class="badge-seance badge-{{ $attendance->status }}">
                                        @if($attendance->status === 'present') Présent
                                        @elseif($attendance->status === 'absent') Absent
                                        @elseif($attendance->status === 'late') En retard
                                        @elseif($attendance->status === 'excused') Excusé
                                        @endif
                                    </span>
                                @else
                                    <span class="badge-seance badge-unknown">Non marqué</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance)
                                    @if($attendance->check_method === 'meet') Google Meet
                                    @elseif($attendance->check_method === 'qrcode') QR code
                                    @elseif($attendance->check_method === 'manual') Manuel
                                    @else Système
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
