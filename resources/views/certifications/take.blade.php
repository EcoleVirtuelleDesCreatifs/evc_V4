@extends('layouts.ki-admin')

@section('title', $certification->title . ' - Examen')

@section('content')
<style>
    .exam-header {
        background: linear-gradient(135deg, #1e293b, #334155);
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
    }
    .timer-box {
        background: rgba(239,68,68,0.15);
        border: 2px solid #ef4444;
        border-radius: 14px;
        padding: 0.6rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1.3rem;
        font-weight: 700;
        color: #ef4444;
        min-width: 140px;
        justify-content: center;
    }
    .timer-box.warning { border-color: #f59e0b; color: #f59e0b; background: rgba(245,158,11,0.15); }
    .timer-box.danger { border-color: #ef4444; color: #ef4444; background: rgba(239,68,68,0.2); animation: pulse-timer 1s infinite; }
    @keyframes pulse-timer { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }
    .question-card {
        background: linear-gradient(145deg, #1e293b, #334155);
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        transition: border-color 0.3s;
    }
    .question-card.answered { border-color: rgba(16,185,129,0.4); }
    .question-card.active { border-color: rgba(99,102,241,0.5); }
    .q-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .q-number {
        background: rgba(99,102,241,0.2);
        color: #818cf8;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .q-type {
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .q-type-qcm { background: rgba(59,130,246,0.2); color: #60a5fa; }
    .q-type-redaction { background: rgba(168,85,247,0.2); color: #c084fc; }
    .q-points { color: #94a3b8; font-size: 0.8rem; }
    .q-text { color: #fff; font-size: 1.05rem; line-height: 1.6; margin-bottom: 1rem; }
    .option-item {
        background: rgba(255,255,255,0.03);
        border: 2px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        padding: 0.9rem 1.2rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #e2e8f0;
    }
    .option-item:hover { border-color: rgba(99,102,241,0.4); background: rgba(99,102,241,0.05); }
    .option-item.selected { border-color: #6366f1; background: rgba(99,102,241,0.15); color: #fff; }
    .option-radio {
        width: 20px; height: 20px;
        border: 2px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .option-item.selected .option-radio {
        border-color: #6366f1;
        background: #6366f1;
    }
    .option-item.selected .option-radio::after {
        content: '';
        width: 8px; height: 8px;
        background: #fff;
        border-radius: 50%;
    }
    .redaction-area {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        color: #fff;
        padding: 1rem;
        width: 100%;
        min-height: 200px;
        resize: vertical;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .redaction-area:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
    }
    .save-indicator {
        font-size: 0.75rem;
        padding: 3px 10px;
        border-radius: 10px;
        display: inline-block;
    }
    .save-ok { background: rgba(16,185,129,0.15); color: #10b981; }
    .save-pending { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .save-error { background: rgba(239,68,68,0.15); color: #ef4444; }
    .btn-submit-exam {
        background: linear-gradient(45deg, #10b981, #059669);
        border: none;
        padding: 1rem 3rem;
        border-radius: 14px;
        color: #fff;
        font-weight: 700;
        font-size: 1.1rem;
        width: 100%;
        max-width: 400px;
        margin: 2rem auto;
        display: block;
        transition: all 0.3s;
    }
    .btn-submit-exam:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(16,185,129,0.4); }
    .progress-bar-exam {
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        height: 6px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .progress-bar-exam .fill {
        height: 100%;
        background: linear-gradient(90deg, #6366f1, #10b981);
        border-radius: 10px;
        transition: width 0.3s;
    }
    .q-nav { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 1rem; }
    .q-nav-btn {
        width: 36px; height: 36px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.03);
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .q-nav-btn:hover { border-color: #6366f1; color: #fff; }
    .q-nav-btn.done { background: rgba(16,185,129,0.2); border-color: rgba(16,185,129,0.4); color: #10b981; }
    .expired-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.85); z-index: 9999;
        display: none; align-items: center; justify-content: center;
        flex-direction: column; color: #fff; text-align: center;
    }
    .expired-overlay.show { display: flex; }
    .q-media { max-width: 100%; max-height: 300px; border-radius: 10px; margin-bottom: 1rem; }
    .char-count { color: #94a3b8; font-size: 0.75rem; text-align: right; margin-top: 4px; }
</style>

<!-- Overlay temps écoulé -->
<div class="expired-overlay" id="expiredOverlay">
    <i class="fas fa-hourglass-end" style="font-size: 4rem; color: #ef4444; margin-bottom: 1rem;"></i>
    <h2>Temps écoulé !</h2>
    <p class="text-white-50">Vos réponses ont été soumises automatiquement.</p>
    <a href="{{ route('certification.result', $certification->id) }}" class="btn-submit-exam" style="max-width: 250px; margin-top: 1rem;">
        <i class="fas fa-eye me-2"></i>Voir le résultat
    </a>
</div>

<div class="container-fluid">
    <!-- Header sticky avec timer -->
    <div class="exam-header" id="examHeader">
        <div>
            <h5 class="text-white mb-0">{{ $certification->title }}</h5>
            <small class="text-muted"><span id="answeredCount">0</span> / {{ $questions->count() }} répondues</small>
        </div>
        <div class="timer-box" id="timerBox">
            <i class="fas fa-clock"></i>
            <span id="timerDisplay">--:--</span>
        </div>
    </div>

    <!-- Barre de progression -->
    <div class="progress-bar-exam">
        <div class="fill" id="progressFill" style="width: 0%"></div>
    </div>

    <!-- Navigation rapide -->
    <div class="q-nav" id="qNav">
        @foreach($questions as $idx => $q)
            <div class="q-nav-btn" data-q="{{ $idx }}" onclick="scrollToQuestion({{ $idx }})" id="qNavBtn{{ $idx }}">{{ $idx + 1 }}</div>
        @endforeach
    </div>

    <!-- Questions -->
    @foreach($questions as $idx => $q)
    <div class="question-card {{ ($q->answer && ($q->answer->selected_option_id || $q->answer->answer_text)) ? 'answered' : '' }}" id="question{{ $idx }}" data-question-id="{{ $q->id }}">
        <div class="q-header">
            <div class="d-flex align-items-center gap-2">
                <span class="q-number">Question {{ $idx + 1 }}</span>
                <span class="q-type {{ $q->type === 'qcm' ? 'q-type-qcm' : 'q-type-redaction' }}">
                    {{ $q->type === 'qcm' ? 'QCM' : 'Rédaction' }}
                </span>
                <span class="q-points">{{ $q->points }} pt{{ $q->points > 1 ? 's' : '' }}</span>
            </div>
            <span class="save-indicator save-ok" id="saveStatus{{ $idx }}" style="display:none;">
                <i class="fas fa-check me-1"></i>Sauvegardé
            </span>
        </div>

        @if($q->media_url)
            <img src="{{ asset('storage/' . $q->media_url) }}" alt="Media" class="q-media">
        @endif

        <div class="q-text">{!! nl2br(e($q->question_text)) !!}</div>

        @if($q->type === 'qcm')
            @foreach($q->options as $opt)
            <div class="option-item {{ ($q->answer && $q->answer->selected_option_id == $opt->id) ? 'selected' : '' }}"
                 data-question-index="{{ $idx }}"
                 data-question-id="{{ $q->id }}"
                 data-option-id="{{ $opt->id }}"
                 onclick="selectOption(this)">
                <div class="option-radio"></div>
                <span>{{ $opt->option_text }}</span>
            </div>
            @endforeach
        @else
            <textarea class="redaction-area"
                      id="redaction{{ $idx }}"
                      data-question-index="{{ $idx }}"
                      data-question-id="{{ $q->id }}"
                      placeholder="Rédigez votre réponse ici..."
                      oninput="onRedactionInput(this)">{{ $q->answer->answer_text ?? '' }}</textarea>
            <div class="char-count" id="charCount{{ $idx }}">
                {{ strlen($q->answer->answer_text ?? '') }} caractères
            </div>
        @endif
    </div>
    @endforeach

    <!-- Bouton soumission -->
    <form action="{{ route('certification.submit', $certification->id) }}" method="POST" id="submitForm" onsubmit="return confirmSubmit()">
        @csrf
        <button type="submit" class="btn-submit-exam" id="submitBtn">
            <i class="fas fa-paper-plane me-2"></i>Soumettre le test
        </button>
    </form>
</div>

<script>
(function() {
    const CERT_ID = {{ $certification->id }};
    const SAVE_URL = "{{ route('certification.save-answer', $certification->id) }}";
    const CSRF_TOKEN = "{{ csrf_token() }}";
    const TOTAL_QUESTIONS = {{ $questions->count() }};
    let remainingSeconds = {{ (int)$remainingSeconds }};
    let timerInterval;
    let savingQueue = {};
    let debounceTimers = {};

    // ─── TIMER ───────────────────────────────────────────
    function startTimer() {
        updateTimerDisplay();
        timerInterval = setInterval(function() {
            remainingSeconds--;
            updateTimerDisplay();
            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                timeExpired();
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        const mins = Math.floor(Math.max(0, remainingSeconds) / 60);
        const secs = Math.max(0, remainingSeconds) % 60;
        document.getElementById('timerDisplay').textContent =
            String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');

        const box = document.getElementById('timerBox');
        box.classList.remove('warning', 'danger');
        if (remainingSeconds <= 60) box.classList.add('danger');
        else if (remainingSeconds <= 300) box.classList.add('warning');
    }

    function timeExpired() {
        // Auto-submit via AJAX
        fetch(SAVE_URL.replace('save-answer', 'submit'), {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN },
        }).catch(() => {});

        document.getElementById('expiredOverlay').classList.add('show');
        document.getElementById('submitBtn').disabled = true;
    }

    // ─── SAUVEGARDE RÉPONSES ─────────────────────────────
    window.selectOption = function(el) {
        if (remainingSeconds <= 0) return;

        const qIdx = el.dataset.questionIndex;
        const qId = el.dataset.questionId;
        const optId = el.dataset.optionId;

        // Désélectionner les autres
        document.querySelectorAll(`.option-item[data-question-index="${qIdx}"]`).forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');

        markAnswered(qIdx);
        saveAnswer(qIdx, { question_id: qId, selected_option_id: optId });
    };

    window.onRedactionInput = function(el) {
        if (remainingSeconds <= 0) return;

        const qIdx = el.dataset.questionIndex;
        const qId = el.dataset.questionId;
        const text = el.value;

        // Compteur caractères
        const counter = document.getElementById('charCount' + qIdx);
        if (counter) counter.textContent = text.length + ' caractères';

        // Marquer comme répondu si texte non vide
        if (text.trim().length > 0) markAnswered(qIdx);
        else unmarkAnswered(qIdx);

        // Debounce (sauvegarde 1s après arrêt de frappe)
        showSaveStatus(qIdx, 'pending');
        clearTimeout(debounceTimers[qIdx]);
        debounceTimers[qIdx] = setTimeout(function() {
            saveAnswer(qIdx, { question_id: qId, answer_text: text });
        }, 1000);
    };

    function saveAnswer(qIdx, data) {
        showSaveStatus(qIdx, 'pending');

        fetch(SAVE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(res => {
            if (res.ok) {
                showSaveStatus(qIdx, 'ok');
            } else {
                return res.json().then(d => {
                    if (d.expired) timeExpired();
                    else showSaveStatus(qIdx, 'error');
                });
            }
        })
        .catch(() => showSaveStatus(qIdx, 'error'));
    }

    function showSaveStatus(qIdx, status) {
        const el = document.getElementById('saveStatus' + qIdx);
        if (!el) return;
        el.style.display = 'inline-block';
        el.className = 'save-indicator save-' + status;
        if (status === 'ok') el.innerHTML = '<i class="fas fa-check me-1"></i>Sauvegardé';
        else if (status === 'pending') el.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Sauvegarde...';
        else el.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Erreur';
    }

    // ─── PROGRESSION ─────────────────────────────────────
    function markAnswered(qIdx) {
        document.getElementById('question' + qIdx)?.classList.add('answered');
        document.getElementById('qNavBtn' + qIdx)?.classList.add('done');
        updateProgress();
    }

    function unmarkAnswered(qIdx) {
        document.getElementById('question' + qIdx)?.classList.remove('answered');
        document.getElementById('qNavBtn' + qIdx)?.classList.remove('done');
        updateProgress();
    }

    function updateProgress() {
        const answered = document.querySelectorAll('.question-card.answered').length;
        document.getElementById('answeredCount').textContent = answered;
        document.getElementById('progressFill').style.width = (answered / TOTAL_QUESTIONS * 100) + '%';
    }

    window.scrollToQuestion = function(idx) {
        document.getElementById('question' + idx)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    };

    window.confirmSubmit = function() {
        const answered = document.querySelectorAll('.question-card.answered').length;
        const remaining = TOTAL_QUESTIONS - answered;
        let msg = 'Êtes-vous sûr de vouloir soumettre votre test ?';
        if (remaining > 0) {
            msg = `Attention ! Vous n'avez pas répondu à ${remaining} question(s).\n\nVoulez-vous quand même soumettre ?`;
        }
        return confirm(msg);
    };

    // ─── Anti-retour navigateur ──────────────────────────
    window.addEventListener('beforeunload', function(e) {
        if (remainingSeconds > 0) {
            e.preventDefault();
            e.returnValue = 'Votre test est en cours. Si vous quittez, vos réponses non sauvegardées seront perdues.';
        }
    });

    // ─── INIT ────────────────────────────────────────────
    startTimer();
    updateProgress();
})();
</script>
@endsection
