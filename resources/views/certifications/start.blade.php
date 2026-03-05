@extends('layouts.ki-admin')

@section('title', 'Démarrer - ' . $certification->title)

@section('content')
<style>
    .start-container {
        max-width: 100%;
        width: 100%;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }
    .start-card {
        background: linear-gradient(145deg, #1e293b, #334155);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 2.5rem;
        text-align: center;
    }
    .cert-icon {
        width: 80px; height: 80px;
        background: linear-gradient(45deg, #6366f1, #8b5cf6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2rem;
        color: #fff;
    }
    .rules-box {
        background: rgba(251,191,36,0.1);
        border: 1px solid rgba(251,191,36,0.3);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: left;
        margin: 1.5rem 0;
    }
    .rules-box h6 { color: #fbbf24; margin-bottom: 0.75rem; }
    .rules-box ul { margin: 0; padding-left: 1.2rem; }
    .rules-box li { color: #e2e8f0; margin-bottom: 0.4rem; font-size: 0.9rem; }
    .instructions-box {
        background: rgba(99,102,241,0.1);
        border: 1px solid rgba(99,102,241,0.2);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: left;
        margin: 1.5rem 0;
        color: #e2e8f0;
    }
    .info-pills {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin: 1.5rem 0;
    }
    .info-pill {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 25px;
        padding: 0.5rem 1.2rem;
        color: #cbd5e1;
        font-size: 0.9rem;
    }
    .info-pill i { margin-right: 6px; color: #6366f1; }
    .btn-begin {
        background: linear-gradient(45deg, #10b981, #059669);
        border: none;
        padding: 1rem 3rem;
        border-radius: 14px;
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s;
        cursor: pointer;
    }
    .btn-begin:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(16,185,129,0.4);
    }
    .btn-cancel {
        color: #94a3b8;
        text-decoration: none;
        display: inline-block;
        margin-top: 1rem;
    }
    .btn-cancel:hover { color: #fff; }
</style>

<div class="start-container">
    <div class="start-card">
        <div class="cert-icon">
            <i class="fas fa-certificate"></i>
        </div>

        <h2 class="text-white mb-2">{{ $certification->title }}</h2>
        @if($certification->description)
            <p class="text-white-50">{{ $certification->description }}</p>
        @endif

        <div class="info-pills">
            <div class="info-pill"><i class="fas fa-clock"></i>{{ $certification->duration_minutes }} minutes</div>
            <div class="info-pill"><i class="fas fa-star"></i>{{ $certification->total_points }} points</div>
            <div class="info-pill"><i class="fas fa-check-double"></i>Passage: {{ $certification->passing_score }}%</div>
        </div>

        <div class="rules-box">
            <h6><i class="fas fa-exclamation-triangle me-2"></i>Règles importantes</h6>
            <ul>
                <li>Le décompte commence dès que vous cliquez sur <strong>"Commencer le test"</strong>.</li>
                <li>Vous disposez de <strong>{{ $certification->duration_minutes }} minutes</strong> pour terminer.</li>
                <li>Le test ne peut être passé qu'<strong>une seule fois</strong>. Aucun recommencement possible.</li>
                <li>Si le temps expire, vos réponses sont <strong>soumises automatiquement</strong>.</li>
                <li>Vos réponses sont <strong>sauvegardées en temps réel</strong>.</li>
                <li>Ne quittez pas la page pendant le test.</li>
            </ul>
        </div>

        @if($certification->instructions)
        <div class="instructions-box">
            <h6 class="text-white mb-2"><i class="fas fa-info-circle me-2"></i>Consignes du formateur</h6>
            {!! nl2br(e($certification->instructions)) !!}
        </div>
        @endif

        <form action="{{ route('certification.confirm', $certification->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir commencer ? Le décompte démarrera immédiatement et vous ne pourrez pas recommencer.')">
            @csrf
            <button type="submit" class="btn-begin">
                <i class="fas fa-play me-2"></i>Commencer le test
            </button>
        </form>

        <a href="{{ route('certification.index') }}" class="btn-cancel">
            <i class="fas fa-arrow-left me-2"></i>Retour aux certifications
        </a>
    </div>
</div>
@endsection
