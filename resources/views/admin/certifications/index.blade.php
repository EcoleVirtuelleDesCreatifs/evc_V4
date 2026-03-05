@extends('layouts.admin')

@section('title', 'Gestion des Certifications')

@push('styles')
<style>
    .cert-card {
        background: linear-gradient(145deg, #1e293b, #334155);
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .cert-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    .cert-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-active { background: rgba(16,185,129,0.2); color: #10b981; }
    .badge-inactive { background: rgba(239,68,68,0.2); color: #ef4444; }
    .badge-draft { background: rgba(148,163,184,0.2); color: #94a3b8; }
    .badge-scheduled { background: rgba(245,158,11,0.2); color: #f59e0b; }
    .badge-formation { background: rgba(99,102,241,0.2); color: #818cf8; }
    .stat-mini {
        text-align: center;
        padding: 0.5rem;
    }
    .stat-mini .num { font-size: 1.5rem; font-weight: 700; color: #fff; }
    .stat-mini .lbl { font-size: 0.7rem; color: #94a3b8; text-transform: uppercase; }
    .page-header {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }
    .btn-create {
        background: linear-gradient(45deg, #10b981, #059669);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-create:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(16,185,129,0.4); color: #fff; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-white mb-1"><i class="fas fa-certificate me-2"></i>Certifications</h1>
                <p class="text-white-50 mb-0">Gérez les examens de certification des étudiants</p>
            </div>
            <a href="{{ route('admin.certifications.create') }}" class="btn btn-create">
                <i class="fas fa-plus me-2"></i>Nouvelle Certification
            </a>
        </div>
    </div>

    @if($certifications->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-certificate fa-3x text-muted mb-3"></i>
            <h4 class="text-white-50">Aucune certification créée</h4>
            <p class="text-muted">Créez votre première certification pour commencer.</p>
            <a href="{{ route('admin.certifications.create') }}" class="btn btn-create mt-2">
                <i class="fas fa-plus me-2"></i>Créer une certification
            </a>
        </div>
    @else
        @foreach($certifications as $cert)
        <div class="cert-card">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <h4 class="text-white mb-0">{{ $cert->title }}</h4>
                        @php
                            $statusBadge = match($cert->status ?? 'draft') {
                                'published' => ['badge-active', 'Publiée'],
                                'scheduled' => ['badge-scheduled', 'Programmée'],
                                default => ['badge-draft', 'Brouillon'],
                            };
                        @endphp
                        <span class="cert-badge {{ $statusBadge[0] }}">{{ $statusBadge[1] }}</span>
                        @if($cert->formation)
                            <span class="cert-badge badge-formation">{{ $cert->formation }}</span>
                        @endif
                        @if($cert->status === 'scheduled' && $cert->scheduled_at)
                            <span class="text-muted" style="font-size:0.8rem;"><i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($cert->scheduled_at)->format('d/m/Y H:i') }}</span>
                        @endif
                    </div>
                    @if($cert->description)
                        <p class="text-white-50 mb-2">{{ Str::limit(strip_tags($cert->description), 120) }}</p>
                    @endif
                    <div class="d-flex gap-3" style="font-size: 0.85rem; color: #94a3b8;">
                        <span><i class="fas fa-clock me-1"></i>{{ $cert->duration_minutes }} min</span>
                        <span><i class="fas fa-question-circle me-1"></i>{{ $cert->questions_count }} questions</span>
                        <span><i class="fas fa-star me-1"></i>{{ $cert->total_points }} pts</span>
                        <span><i class="fas fa-check-double me-1"></i>Passage: {{ $cert->passing_score }}%</span>
                    </div>
                </div>
                <div class="d-flex gap-3 me-3">
                    <div class="stat-mini">
                        <div class="num text-info">{{ $cert->participants_count ?? 0 }}</div>
                        <div class="lbl">Participants</div>
                    </div>
                    <div class="stat-mini">
                        <div class="num">{{ $cert->attempts_count }}</div>
                        <div class="lbl">Tentatives</div>
                    </div>
                    <div class="stat-mini">
                        <div class="num">{{ $cert->submitted_count }}</div>
                        <div class="lbl">Soumis</div>
                    </div>
                    <div class="stat-mini">
                        <div class="num text-success">{{ $cert->passed_count }}</div>
                        <div class="lbl">Réussis</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.certifications.edit', $cert->id) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.certifications.toggle', $cert->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm {{ $cert->is_active ? 'btn-outline-warning' : 'btn-outline-success' }}" title="{{ $cert->is_active ? 'Désactiver' : 'Activer' }}">
                            <i class="fas {{ $cert->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                        </button>
                    </form>
                    <form action="{{ route('admin.certifications.destroy', $cert->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette certification ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
