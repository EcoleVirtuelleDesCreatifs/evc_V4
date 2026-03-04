@extends('layouts.admin')

@section('title', 'Modifier - ' . $certification->title)

@push('styles')
<style>
    .page-header { background: linear-gradient(135deg, #1e3c72, #2a5298); border-radius: 16px; padding: 2rem; margin-bottom: 2rem; }
    .form-card { background: linear-gradient(145deg, #1e293b, #334155); border-radius: 16px; border: 1px solid rgba(255,255,255,0.1); padding: 1.5rem; margin-bottom: 1.5rem; }
    .form-control, .form-select { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.15); color: #fff; border-radius: 10px; }
    .form-control:focus, .form-select:focus { background: rgba(255,255,255,0.08); border-color: #6366f1; color: #fff; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
    .form-control::placeholder { color: #94a3b8; opacity: 1; }
    .form-label { color: #cbd5e1; font-weight: 500; }
    .btn-save { background: linear-gradient(45deg, #10b981, #059669); border: none; padding: 0.75rem 2rem; border-radius: 12px; color: #fff; font-weight: 600; }
    .btn-save:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(16,185,129,0.4); color: #fff; }
    .question-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; }
    .question-card:hover { border-color: rgba(99,102,241,0.3); }
    .q-type-badge { display: inline-block; padding: 3px 10px; border-radius: 15px; font-size: 0.7rem; font-weight: 600; }
    .q-type-qcm { background: rgba(59,130,246,0.2); color: #60a5fa; }
    .q-type-redaction { background: rgba(168,85,247,0.2); color: #c084fc; }
    .option-row { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem; }
    .option-correct { color: #10b981; }
    .option-wrong { color: #94a3b8; }
    .attempt-row { background: rgba(255,255,255,0.03); border-radius: 10px; padding: 0.75rem 1rem; margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between; }
    .attempt-row:hover { background: rgba(255,255,255,0.06); }
    .status-badge { padding: 3px 10px; border-radius: 15px; font-size: 0.7rem; font-weight: 600; }
    .status-in_progress { background: rgba(251,191,36,0.2); color: #fbbf24; }
    .status-submitted { background: rgba(59,130,246,0.2); color: #60a5fa; }
    .status-graded { background: rgba(16,185,129,0.2); color: #10b981; }
    .nav-tabs .nav-link { color: #94a3b8; border: none; padding: 0.75rem 1.5rem; }
    .nav-tabs .nav-link.active { color: #fff; background: rgba(99,102,241,0.2); border-radius: 10px; }
    .add-option-btn { background: none; border: 1px dashed rgba(255,255,255,0.2); color: #94a3b8; border-radius: 8px; padding: 0.4rem 1rem; font-size: 0.85rem; cursor: pointer; }
    .add-option-btn:hover { border-color: #6366f1; color: #6366f1; }
    textarea.form-control { min-height: 80px; }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-white mb-1"><i class="fas fa-edit me-2"></i>{{ $certification->title }}</h1>
                <p class="text-white-50 mb-0">
                    <span class="me-3"><i class="fas fa-clock me-1"></i>{{ $certification->duration_minutes }} min</span>
                    <span class="me-3"><i class="fas fa-star me-1"></i>{{ $certification->total_points }} pts</span>
                    <span><i class="fas fa-check-double me-1"></i>Passage: {{ $certification->passing_score }}%</span>
                </p>
            </div>
            <a href="{{ route('admin.certifications.index') }}" class="btn btn-secondary" style="border-radius: 10px;">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-settings"><i class="fas fa-cog me-1"></i>Paramètres</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-questions"><i class="fas fa-question-circle me-1"></i>Questions ({{ $questions->count() }})</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-results"><i class="fas fa-chart-bar me-1"></i>Résultats ({{ $attempts->count() }})</a></li>
    </ul>

    <div class="tab-content">
        <!-- TAB: Paramètres -->
        <div class="tab-pane fade show active" id="tab-settings">
            <form action="{{ route('admin.certifications.update', $certification->id) }}" method="POST">
                @csrf @method('PUT')
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-card">
                            <h5 class="text-white mb-3"><i class="fas fa-info-circle me-2"></i>Informations</h5>
                            <div class="mb-3">
                                <label class="form-label">Titre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" value="{{ old('title', $certification->title) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3">{{ old('description', $certification->description) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Consignes</label>
                                <textarea class="form-control" name="instructions" rows="4">{{ old('instructions', $certification->instructions) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-card">
                            <h5 class="text-white mb-3"><i class="fas fa-cogs me-2"></i>Paramètres</h5>
                            <div class="mb-3">
                                <label class="form-label">Formation</label>
                                <select class="form-select" name="formation">
                                    <option value="">Toutes</option>
                                    @foreach($formations as $f)
                                        <option value="{{ $f }}" {{ $certification->formation == $f ? 'selected' : '' }}>{{ $f }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Durée (min) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="duration_minutes" value="{{ $certification->duration_minutes }}" min="5" max="480" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Note de passage (%) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="passing_score" value="{{ $certification->passing_score }}" min="0" max="100" step="0.5" required>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="shuffle_questions" name="shuffle_questions" value="1" {{ $certification->shuffle_questions ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="shuffle_questions">Mélanger les questions</label>
                            </div>
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ $certification->is_active ? 'checked' : '' }}>
                                <label class="form-check-label text-white" for="is_active">Certification active</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-save w-100"><i class="fas fa-save me-2"></i>Enregistrer</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- TAB: Questions -->
        <div class="tab-pane fade" id="tab-questions">
            <!-- Questions existantes -->
            @foreach($questions as $idx => $q)
            <div class="question-card">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <span class="text-white-50 me-2">#{{ $idx + 1 }}</span>
                        <span class="q-type-badge {{ $q->type === 'qcm' ? 'q-type-qcm' : 'q-type-redaction' }}">
                            {{ $q->type === 'qcm' ? 'QCM' : 'Rédaction' }}
                        </span>
                        <span class="text-muted ms-2">{{ $q->points }} pt{{ $q->points > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#editQ{{ $q->id }}"><i class="fas fa-edit"></i></button>
                        <form action="{{ route('admin.certifications.questions.destroy', $q->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer cette question ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
                <p class="text-white mb-1">{!! nl2br(e($q->question_text)) !!}</p>
                @if($q->media_url)
                    <img src="{{ asset('storage/' . $q->media_url) }}" alt="Media" style="max-height: 120px; border-radius: 8px;" class="mb-2">
                @endif
                @if($q->type === 'qcm' && isset($q->options))
                    <div class="ms-3 mt-2">
                        @foreach($q->options as $opt)
                            <div class="{{ $opt->is_correct ? 'option-correct' : 'option-wrong' }}">
                                <i class="fas {{ $opt->is_correct ? 'fa-check-circle' : 'fa-circle' }} me-1" style="font-size: 0.8rem;"></i>
                                {{ $opt->option_text }}
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Edit collapse -->
                <div class="collapse mt-3" id="editQ{{ $q->id }}">
                    <form action="{{ route('admin.certifications.questions.update', $q->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        <div class="mb-2">
                            <textarea class="form-control" name="question_text" rows="2">{{ $q->question_text }}</textarea>
                        </div>
                        <div class="row mb-2">
                            <div class="col-4">
                                <input type="number" class="form-control" name="points" value="{{ $q->points }}" min="0.5" step="0.5">
                            </div>
                            <div class="col-8">
                                <input type="file" class="form-control" name="media" accept="image/*,.pdf">
                            </div>
                        </div>
                        @if($q->type === 'qcm' && isset($q->options))
                            <div class="ms-2 mb-2">
                                @foreach($q->options as $oIdx => $opt)
                                    <div class="option-row">
                                        <input type="radio" name="correct_option" value="{{ $oIdx }}" {{ $opt->is_correct ? 'checked' : '' }}>
                                        <input type="text" class="form-control form-control-sm" name="options[{{ $oIdx }}][text]" value="{{ $opt->option_text }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <button type="submit" class="btn btn-sm btn-save"><i class="fas fa-save me-1"></i>Sauvegarder</button>
                    </form>
                </div>
            </div>
            @endforeach

            <!-- Ajouter une question -->
            <div class="form-card mt-4">
                <h5 class="text-white mb-3"><i class="fas fa-plus-circle me-2"></i>Ajouter une question</h5>
                <form action="{{ route('admin.certifications.questions.store', $certification->id) }}" method="POST" enctype="multipart/form-data" id="addQuestionForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Type</label>
                            <select class="form-select" name="type" id="questionType" onchange="toggleOptionsUI()">
                                <option value="qcm">QCM</option>
                                <option value="redaction">Rédaction</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Points</label>
                            <input type="number" class="form-control" name="points" value="1" min="0.5" step="0.5">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Media (optionnel)</label>
                            <input type="file" class="form-control" name="media" accept="image/*,.pdf">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Question <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="question_text" rows="3" required placeholder="Saisissez la question..."></textarea>
                    </div>
                    <div id="optionsContainer">
                        <label class="form-label">Options de réponse</label>
                        <div id="optionsList">
                            <div class="option-row">
                                <input type="radio" name="correct_option" value="0" checked>
                                <input type="text" class="form-control form-control-sm" name="options[0][text]" placeholder="Option A" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct_option" value="1">
                                <input type="text" class="form-control form-control-sm" name="options[1][text]" placeholder="Option B" required>
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct_option" value="2">
                                <input type="text" class="form-control form-control-sm" name="options[2][text]" placeholder="Option C">
                            </div>
                            <div class="option-row">
                                <input type="radio" name="correct_option" value="3">
                                <input type="text" class="form-control form-control-sm" name="options[3][text]" placeholder="Option D">
                            </div>
                        </div>
                        <button type="button" class="add-option-btn mt-2" onclick="addOption()"><i class="fas fa-plus me-1"></i>Ajouter une option</button>
                    </div>
                    <button type="submit" class="btn btn-save mt-3"><i class="fas fa-plus me-2"></i>Ajouter la question</button>
                </form>
            </div>
        </div>

        <!-- TAB: Résultats -->
        <div class="tab-pane fade" id="tab-results">
            <div class="form-card">
                <h5 class="text-white mb-3"><i class="fas fa-users me-2"></i>Tentatives des étudiants</h5>
                @if($attempts->isEmpty())
                    <p class="text-white-50 text-center py-3">Aucune tentative pour le moment.</p>
                @else
                    @foreach($attempts as $att)
                    <div class="attempt-row">
                        <div>
                            <strong class="text-white">{{ $att->first_name }} {{ $att->last_name }}</strong>
                            <span class="text-muted ms-2">{{ $att->email }}</span>
                            <span class="text-muted ms-2">{{ $att->program }}</span>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="status-badge status-{{ $att->status }}">
                                {{ $att->status === 'in_progress' ? 'En cours' : ($att->status === 'submitted' ? 'À noter' : 'Noté') }}
                            </span>
                            @if($att->score !== null)
                                <span class="text-white fw-bold">{{ $att->score_percentage ?? '—' }}%</span>
                                @if($att->passed !== null)
                                    <span class="{{ $att->passed ? 'text-success' : 'text-danger' }}">
                                        <i class="fas {{ $att->passed ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                    </span>
                                @endif
                            @endif
                            @if($att->is_auto_submitted)
                                <span class="text-warning" title="Auto-soumis (temps écoulé)"><i class="fas fa-hourglass-end"></i></span>
                            @endif
                            <a href="{{ route('admin.certifications.attempts.show', $att->id) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleOptionsUI() {
    const type = document.getElementById('questionType').value;
    document.getElementById('optionsContainer').style.display = type === 'qcm' ? 'block' : 'none';
    document.querySelectorAll('#optionsContainer input[type=text]').forEach(i => i.required = type === 'qcm');
}

let optionCount = 4;
function addOption() {
    const row = document.createElement('div');
    row.className = 'option-row';
    row.innerHTML = `<input type="radio" name="correct_option" value="${optionCount}"><input type="text" class="form-control form-control-sm" name="options[${optionCount}][text]" placeholder="Option ${String.fromCharCode(65 + optionCount)}">`;
    document.getElementById('optionsList').appendChild(row);
    optionCount++;
}
</script>
@endpush
