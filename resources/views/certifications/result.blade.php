@extends('layouts.ki-admin')

@section('title', 'Résultat - ' . $certification->title)

@section('content')
<style>
    .result-container { max-width: 700px; margin: 2rem auto; }
    .result-card {
        background: linear-gradient(145deg, #1e293b, #334155);
        border-radius: 20px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 2.5rem;
        text-align: center;
    }
    .result-icon {
        width: 100px; height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        font-size: 2.5rem;
    }
    .result-passed { background: rgba(16,185,129,0.15); color: #10b981; border: 3px solid #10b981; }
    .result-failed { background: rgba(239,68,68,0.15); color: #ef4444; border: 3px solid #ef4444; }
    .result-pending { background: rgba(251,191,36,0.15); color: #fbbf24; border: 3px solid #fbbf24; }
    .score-display { font-size: 3rem; font-weight: 800; margin: 1rem 0; }
    .score-passed { color: #10b981; }
    .score-failed { color: #ef4444; }
    .score-pending { color: #fbbf24; }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 1rem;
        margin: 1.5rem 0;
    }
    .info-cell {
        background: rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 1rem;
    }
    .info-cell .val { font-size: 1.2rem; font-weight: 700; color: #fff; }
    .info-cell .lbl { font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; }
    .feedback-box {
        background: rgba(99,102,241,0.1);
        border: 1px solid rgba(99,102,241,0.2);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: left;
        margin: 1.5rem 0;
        color: #e2e8f0;
    }
    .btn-back {
        background: linear-gradient(45deg, #6366f1, #4f46e5);
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        color: #fff;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }
    .btn-back:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(99,102,241,0.4); color: #fff; }
</style>

<div class="result-container">
    <div class="result-card">
        @if($attempt->status === 'graded')
            @if($attempt->passed)
                <div class="result-icon result-passed"><i class="fas fa-trophy"></i></div>
                <h2 class="text-white">Félicitations !</h2>
                <p class="text-white-50">Vous avez réussi votre certification</p>
                <div class="score-display score-passed">{{ $attempt->score_percentage }}%</div>
            @else
                <div class="result-icon result-failed"><i class="fas fa-times"></i></div>
                <h2 class="text-white">Non réussi</h2>
                <p class="text-white-50">Votre score est en dessous de la note de passage</p>
                <div class="score-display score-failed">{{ $attempt->score_percentage }}%</div>
            @endif
        @elseif($attempt->status === 'submitted')
            <div class="result-icon result-pending"><i class="fas fa-hourglass-half"></i></div>
            <h2 class="text-white">Test soumis</h2>
            <p class="text-white-50">Votre test contient des questions de rédaction. Il est en cours de correction par votre formateur.</p>
            <div class="score-display score-pending">En attente</div>
        @else
            <div class="result-icon result-pending"><i class="fas fa-spinner fa-spin"></i></div>
            <h2 class="text-white">En cours de traitement</h2>
            <div class="score-display score-pending">...</div>
        @endif

        <h4 class="text-white mt-3">{{ $certification->title }}</h4>

        <div class="info-grid">
            <div class="info-cell">
                <div class="val">{{ $attempt->score ?? '—' }} / {{ $certification->total_points }}</div>
                <div class="lbl">Score</div>
            </div>
            <div class="info-cell">
                <div class="val">{{ $certification->passing_score }}%</div>
                <div class="lbl">Note de passage</div>
            </div>
            <div class="info-cell">
                <div class="val">{{ $attempt->started_at ? \Carbon\Carbon::parse($attempt->started_at)->format('d/m H:i') : '—' }}</div>
                <div class="lbl">Démarré</div>
            </div>
            <div class="info-cell">
                <div class="val">{{ $attempt->submitted_at ? \Carbon\Carbon::parse($attempt->submitted_at)->format('d/m H:i') : '—' }}</div>
                <div class="lbl">Soumis</div>
            </div>
        </div>

        @if($attempt->is_auto_submitted)
            <div class="alert alert-warning" style="border-radius: 12px; text-align: left;">
                <i class="fas fa-hourglass-end me-2"></i>Votre test a été soumis automatiquement car le temps était écoulé.
            </div>
        @endif

        @if($attempt->admin_feedback)
            <div class="feedback-box">
                <h6 class="text-white mb-2"><i class="fas fa-comment-alt me-2"></i>Commentaire du formateur</h6>
                {!! nl2br(e($attempt->admin_feedback)) !!}
            </div>
        @endif

        <a href="{{ route('certification.index') }}" class="btn-back mt-3">
            <i class="fas fa-arrow-left me-2"></i>Retour aux certifications
        </a>
    </div>
</div>
@endsection
