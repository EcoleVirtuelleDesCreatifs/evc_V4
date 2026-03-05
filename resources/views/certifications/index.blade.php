@extends('layouts.ki-admin')

@section('title', 'Mes Certifications')

@section('content')
<style>
    .cert-page-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: #fff;
    }
    .cert-list-card {
        background: linear-gradient(145deg, #1e293b, #334155);
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    .cert-list-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
        border-color: rgba(99,102,241,0.3);
    }
    .cert-status {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .status-available { background: rgba(16,185,129,0.15); color: #10b981; }
    .status-in-progress { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .status-submitted { background: rgba(59,130,246,0.15); color: #60a5fa; }
    .status-passed { background: rgba(16,185,129,0.2); color: #10b981; }
    .status-failed { background: rgba(239,68,68,0.15); color: #ef4444; }
    .cert-info { display: flex; gap: 1.5rem; flex-wrap: wrap; margin-top: 0.5rem; }
    .cert-info span { color: #94a3b8; font-size: 0.85rem; }
    .cert-info i { margin-right: 4px; }
    .btn-start-cert {
        background: linear-gradient(45deg, #10b981, #059669);
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    .btn-start-cert:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(16,185,129,0.4);
        color: #fff;
    }
    .btn-resume {
        background: linear-gradient(45deg, #f59e0b, #d97706);
    }
    .btn-resume:hover { box-shadow: 0 4px 15px rgba(245,158,11,0.4); }
    .btn-result {
        background: linear-gradient(45deg, #6366f1, #4f46e5);
    }
    .btn-result:hover { box-shadow: 0 4px 15px rgba(99,102,241,0.4); }
    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #94a3b8;
    }
    .empty-state i { font-size: 3rem; margin-bottom: 1rem; opacity: 0.5; }
</style>

<div class="container-fluid">
    <div class="cert-page-header">
        <h2 class="mb-1"><i class="fas fa-certificate me-2"></i>Mes Certifications</h2>
        <p class="mb-0 opacity-75">Passez vos examens de certification en ligne</p>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    @if($certifications->isEmpty())
        <div class="cert-list-card">
            <div class="empty-state">
                <i class="fas fa-certificate d-block"></i>
                <h5 class="text-white">Aucune certification disponible</h5>
                <p>Votre évaluation finale apparaîtra ici dès que votre formateur l’aura assignée en fin de parcours. Une fois l’évaluation validée, votre certificat sera disponible.</p>
            </div>
        </div>
    @else
        @foreach($certifications as $cert)
        <div class="cert-list-card">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <h4 class="text-white mb-0">{{ $cert->title }}</h4>
                        @php
                            $statusClass = 'status-available';
                            if ($cert->attempt) {
                                $statusClass = match($cert->attempt->status) {
                                    'in_progress' => 'status-in-progress',
                                    'submitted' => 'status-submitted',
                                    'graded' => $cert->attempt->passed ? 'status-passed' : 'status-failed',
                                    default => 'status-available',
                                };
                            }
                        @endphp
                        <span class="cert-status {{ $statusClass }}">{{ $cert->status_label }}</span>
                    </div>
                    @if($cert->description)
                        <p class="text-white-50 mb-1">{{ Str::limit($cert->description, 150) }}</p>
                    @endif
                    <div class="cert-info">
                        <span><i class="fas fa-clock"></i>{{ $cert->duration_minutes }} minutes</span>
                        <span><i class="fas fa-question-circle"></i>{{ $cert->questions_count }} questions</span>
                        <span><i class="fas fa-check-double"></i>Note de passage: {{ $cert->passing_score }}%</span>
                        @if($cert->formation)
                            <span><i class="fas fa-graduation-cap"></i>{{ $cert->formation }}</span>
                        @endif
                    </div>
                </div>
                <div>
                    @if($cert->can_start)
                        <a href="{{ route('certification.start', $cert->id) }}" class="btn-start-cert">
                            <i class="fas fa-play me-2"></i>Commencer
                        </a>
                    @elseif($cert->attempt && $cert->attempt->status === 'in_progress')
                        <a href="{{ route('certification.take', $cert->id) }}" class="btn-start-cert btn-resume">
                            <i class="fas fa-redo me-2"></i>Reprendre
                        </a>
                    @elseif($cert->attempt)
                        <a href="{{ route('certification.result', $cert->id) }}" class="btn-start-cert btn-result">
                            <i class="fas fa-eye me-2"></i>Voir résultat
                        </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    @endif
</div>
@endsection
