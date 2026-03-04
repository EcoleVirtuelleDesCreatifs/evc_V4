@extends('layouts.admin')

@section('title', 'Tentative - ' . $attempt->first_name . ' ' . $attempt->last_name)

@push('styles')
<style>
    .page-header { background: linear-gradient(135deg, #1e3c72, #2a5298); border-radius: 16px; padding: 2rem; margin-bottom: 2rem; }
    .form-card { background: linear-gradient(145deg, #1e293b, #334155); border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); padding: 1.5rem; margin-bottom: 1.5rem; }
    .form-control, .form-select { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 10px; }
    .form-control:focus { background: rgba(255,255,255,0.08); border-color: #6366f1; color: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
    .form-label { color: #cbd5e1; }
    .answer-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; }
    .answer-correct { border-left: 4px solid #10b981; }
    .answer-wrong { border-left: 4px solid #ef4444; }
    .answer-pending { border-left: 4px solid #f59e0b; }
    .option-line { padding: 0.3rem 0; }
    .option-selected { font-weight: 600; }
    .option-is-correct { color: #10b981; }
    .option-is-wrong { color: #ef4444; }
    .btn-grade { background: linear-gradient(45deg, #6366f1, #8b5cf6); border: none; border-radius: 10px; color: #fff; padding: 0.5rem 1rem; }
    .btn-grade:hover { transform: translateY(-1px); color: #fff; }
    .btn-finalize { background: linear-gradient(45deg, #10b981, #059669); border: none; border-radius: 12px; color: #fff; padding: 0.75rem 2rem; font-weight: 600; }
    .btn-finalize:hover { transform: translateY(-2px); color: #fff; }
    .info-box { display: flex; gap: 2rem; flex-wrap: wrap; }
    .info-item { text-align: center; }
    .info-item .val { font-size: 1.3rem; font-weight: 700; color: #fff; }
    .info-item .lbl { font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-white mb-1">
                    <i class="fas fa-file-alt me-2"></i>{{ $attempt->first_name }} {{ $attempt->last_name }}
                </h1>
                <p class="text-white-50 mb-0">{{ $attempt->certification_title }} — {{ $attempt->email }}</p>
            </div>
            <a href="{{ route('admin.certifications.edit', $attempt->certification_id) }}" class="btn btn-secondary" style="border-radius: 10px;">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <!-- Infos tentative -->
    <div class="form-card">
        <div class="info-box">
            <div class="info-item">
                <div class="val">{{ \Carbon\Carbon::parse($attempt->started_at)->format('d/m/Y H:i') }}</div>
                <div class="lbl">Démarré le</div>
            </div>
            <div class="info-item">
                <div class="val">{{ $attempt->duration_minutes }} min</div>
                <div class="lbl">Durée</div>
            </div>
            <div class="info-item">
                <div class="val">{{ $attempt->submitted_at ? \Carbon\Carbon::parse($attempt->submitted_at)->format('H:i') : '—' }}</div>
                <div class="lbl">Soumis à</div>
            </div>
            <div class="info-item">
                <div class="val {{ $attempt->is_auto_submitted ? 'text-warning' : 'text-success' }}">
                    {{ $attempt->is_auto_submitted ? 'Auto' : 'Manuel' }}
                </div>
                <div class="lbl">Type soumission</div>
            </div>
            <div class="info-item">
                <div class="val">{{ $attempt->score ?? '—' }} / {{ $attempt->total_points }}</div>
                <div class="lbl">Score</div>
            </div>
            <div class="info-item">
                <div class="val">{{ $attempt->score_percentage ?? '—' }}%</div>
                <div class="lbl">Pourcentage</div>
            </div>
            @if($attempt->passed !== null)
            <div class="info-item">
                <div class="val {{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                    {{ $attempt->passed ? 'RÉUSSI' : 'ÉCHOUÉ' }}
                </div>
                <div class="lbl">Résultat</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Réponses -->
    <h4 class="text-white mb-3"><i class="fas fa-list-ol me-2"></i>Réponses</h4>

    @foreach($answers as $idx => $ans)
    @php
        $cardClass = 'answer-pending';
        if ($ans->question_type === 'qcm') {
            $cardClass = $ans->is_correct ? 'answer-correct' : 'answer-wrong';
        } elseif ($ans->score !== null) {
            $cardClass = $ans->score > 0 ? 'answer-correct' : 'answer-wrong';
        }
    @endphp
    <div class="answer-card {{ $cardClass }}">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <span class="text-white-50">Q{{ $idx + 1 }}.</span>
                <span class="badge {{ $ans->question_type === 'qcm' ? 'bg-primary' : 'bg-purple' }}" style="{{ $ans->question_type === 'redaction' ? 'background:#8b5cf6;' : '' }}">
                    {{ $ans->question_type === 'qcm' ? 'QCM' : 'Rédaction' }}
                </span>
                <span class="text-muted ms-2">{{ $ans->max_points }} pt{{ $ans->max_points > 1 ? 's' : '' }}</span>
            </div>
            @if($ans->score !== null)
                <span class="text-white fw-bold">{{ $ans->score }} / {{ $ans->max_points }}</span>
            @endif
        </div>

        <p class="text-white mb-2">{!! nl2br(e($ans->question_text)) !!}</p>

        @if($ans->media_url)
            <img src="{{ asset('storage/' . $ans->media_url) }}" alt="Media" style="max-height: 150px; border-radius: 8px;" class="mb-2">
        @endif

        @if($ans->question_type === 'qcm')
            <div class="ms-3">
                @foreach($ans->all_options as $opt)
                    @php
                        $isSelected = $ans->selected_option_id == $opt->id;
                        $classes = '';
                        if ($isSelected && $opt->is_correct) $classes = 'option-is-correct option-selected';
                        elseif ($isSelected && !$opt->is_correct) $classes = 'option-is-wrong option-selected';
                        elseif ($opt->is_correct) $classes = 'option-is-correct';
                    @endphp
                    <div class="option-line {{ $classes }}">
                        <i class="fas {{ $isSelected ? ($opt->is_correct ? 'fa-check-circle' : 'fa-times-circle') : ($opt->is_correct ? 'fa-check text-success' : 'fa-circle') }} me-1" style="font-size: 0.8rem;"></i>
                        {{ $opt->option_text }}
                        @if($isSelected) <span class="ms-1">(choisi)</span> @endif
                    </div>
                @endforeach
            </div>
        @else
            <!-- Rédaction -->
            <div class="p-3 mb-3" style="background: rgba(255,255,255,0.05); border-radius: 10px;">
                <small class="text-muted d-block mb-1">Réponse de l'étudiant :</small>
                <div class="text-white">{!! nl2br(e($ans->answer_text ?? 'Aucune réponse')) !!}</div>
            </div>

            <!-- Formulaire de notation -->
            <form action="{{ route('admin.certifications.answers.grade', $ans->id) }}" method="POST" class="d-flex align-items-end gap-3">
                @csrf
                <div style="width: 120px;">
                    <label class="form-label" style="font-size: 0.8rem;">Note / {{ $ans->max_points }}</label>
                    <input type="number" class="form-control" name="score" value="{{ $ans->score }}" min="0" max="{{ $ans->max_points }}" step="0.5" required>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label" style="font-size: 0.8rem;">Commentaire (optionnel)</label>
                    <input type="text" class="form-control" name="admin_comment" value="{{ $ans->admin_comment }}" placeholder="Feedback pour l'étudiant...">
                </div>
                <button type="submit" class="btn btn-grade"><i class="fas fa-save me-1"></i>Noter</button>
            </form>
        @endif
    </div>
    @endforeach

    <!-- Finaliser -->
    @if($attempt->status === 'submitted')
    <div class="text-center mt-4 mb-4">
        <form action="{{ route('admin.certifications.attempts.finalize', $attempt->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-finalize" onclick="return confirm('Finaliser la notation ? Le résultat sera définitif.')">
                <i class="fas fa-check-double me-2"></i>Finaliser la notation
            </button>
        </form>
    </div>
    @endif
</div>
@endsection
