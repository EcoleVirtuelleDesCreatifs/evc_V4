@extends('layouts.admin')

@section('title', 'Modifier un Programme')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .pg-header {
        background: linear-gradient(135deg, rgba(139,92,246,0.15) 0%, rgba(59,130,246,0.10) 100%);
        border: 1px solid rgba(139,92,246,0.3);
        border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem;
    }
    .pg-header-icon {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.4rem; flex-shrink: 0;
    }
    .pg-meta-chip {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 999px; padding: 0.3rem 0.8rem;
        font-size: 0.75rem; color: rgba(255,255,255,0.75); font-weight: 600;
    }

    /* Mode destinataires : 2 grandes cartes radio */
    .pg-mode-card {
        display: flex; align-items: center; gap: 0.85rem;
        border: 1.5px solid rgba(255,255,255,0.1);
        border-radius: 12px; padding: 0.9rem 1rem;
        cursor: pointer; transition: all 0.2s ease;
        background: rgba(255,255,255,0.03); margin-bottom: 0.6rem;
    }
    .pg-mode-card:hover { border-color: rgba(139,92,246,0.4); }
    .pg-mode-card.active {
        border-color: #8b5cf6; background: rgba(139,92,246,0.12);
        box-shadow: 0 0 0 3px rgba(139,92,246,0.15);
    }
    .pg-mode-card input { display: none; }
    .pg-mode-icon {
        width: 38px; height: 38px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1rem;
        background: rgba(139,92,246,0.15); color: #a78bfa;
    }
    .pg-mode-card.active .pg-mode-icon { background: #8b5cf6; color: #fff; }
    .pg-mode-title { color: #e2e8f0; font-weight: 600; font-size: 0.9rem; }
    .pg-mode-sub { color: #94a3b8; font-size: 0.75rem; }

    /* Pills formations (single-select en édition) */
    .pg-pill {
        display: inline-flex; align-items: center; gap: 0.4rem;
        border: 1.5px solid rgba(255,255,255,0.12);
        border-radius: 999px; padding: 0.45rem 0.95rem;
        cursor: pointer; font-size: 0.82rem; color: #cbd5e1;
        background: rgba(255,255,255,0.03); transition: all 0.15s ease; user-select: none;
    }
    .pg-pill:hover { border-color: rgba(139,92,246,0.5); }
    .pg-pill input { display: none; }
    .pg-pill.checked { border-color: #8b5cf6; color: #fff; background: rgba(139,92,246,0.2); }
    .pg-pill.checked::before {
        content: "\f00c"; font-family: "Font Awesome 5 Free"; font-weight: 900;
        font-size: 0.65rem; color: #a78bfa;
    }

    /* Étudiants groupés */
    .pg-search {
        background: rgba(255,255,255,0.05) !important;
        border: 1px solid rgba(255,255,255,0.12) !important;
        color: #fff !important; border-radius: 10px;
    }
    .pg-search::placeholder { color: #64748b; }
    .pg-search:focus { border-color: #8b5cf6 !important; box-shadow: 0 0 0 3px rgba(139,92,246,0.15) !important; }

    .pg-group {
        border: 1px solid rgba(255,255,255,0.08); border-radius: 10px;
        margin-bottom: 0.5rem; background: rgba(255,255,255,0.02); overflow: hidden;
    }
    .pg-group-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.55rem 0.85rem; cursor: pointer; background: rgba(255,255,255,0.03);
    }
    .pg-group-head:hover { background: rgba(255,255,255,0.06); }
    .pg-group-title { font-size: 0.78rem; font-weight: 700; color: #a78bfa; text-transform: uppercase; letter-spacing: 0.4px; }
    .pg-group-count { font-size: 0.7rem; color: #94a3b8; }
    .pg-group-body { display: none; padding: 0.35rem 0.5rem; max-height: 220px; overflow-y: auto; }
    .pg-group.open .pg-group-body { display: block; }
    .pg-group.open .pg-caret { transform: rotate(180deg); }
    .pg-caret { transition: transform 0.2s; color: #64748b; font-size: 0.75rem; }

    .pg-student-row {
        display: flex; align-items: center; gap: 0.6rem;
        padding: 0.4rem 0.5rem; border-radius: 8px; cursor: pointer;
    }
    .pg-student-row:hover { background: rgba(139,92,246,0.08); }
    .pg-student-row input { accent-color: #8b5cf6; width: 15px; height: 15px; flex-shrink: 0; }
    .pg-student-name { font-size: 0.82rem; color: #e2e8f0; }
    .pg-student-email { font-size: 0.7rem; color: #64748b; }
    .pg-student-row.hidden-by-search, .pg-group.hidden-by-search { display: none; }

    .pg-counter {
        position: sticky; bottom: 0;
        background: linear-gradient(135deg, rgba(139,92,246,0.25), rgba(99,102,241,0.2));
        border: 1px solid rgba(139,92,246,0.4); border-radius: 10px;
        padding: 0.6rem 0.9rem; color: #fff; font-size: 0.85rem; font-weight: 600;
        display: flex; align-items: center; justify-content: space-between;
        margin-top: 0.75rem; backdrop-filter: blur(8px);
    }

    /* Upload zones */
    .pg-upload {
        border: 2px dashed rgba(255,255,255,0.15); border-radius: 12px;
        padding: 1.1rem; text-align: center; cursor: pointer;
        transition: all 0.2s ease; background: rgba(255,255,255,0.02); display: block;
    }
    .pg-upload:hover, .pg-upload.dragover { border-color: #8b5cf6; background: rgba(139,92,246,0.08); }
    .pg-upload input { display: none; }
    .pg-upload-icon { font-size: 1.5rem; color: #a78bfa; margin-bottom: 0.35rem; }
    .pg-upload-text { color: #cbd5e1; font-size: 0.82rem; }
    .pg-upload-hint { color: #64748b; font-size: 0.7rem; }
    .pg-upload.has-file { border-style: solid; border-color: #10b981; background: rgba(16,185,129,0.06); }
    .pg-upload.has-file .pg-upload-icon { color: #10b981; }
    .pg-current-file {
        display: flex; align-items: center; gap: 0.6rem;
        background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.25);
        border-radius: 10px; padding: 0.55rem 0.8rem; margin-bottom: 0.6rem;
        font-size: 0.8rem; color: #93c5fd;
    }
    .pg-current-file img { max-height: 44px; border-radius: 8px; object-fit: cover; }
    .pg-current-file a { color: #93c5fd; text-decoration: none; font-weight: 700; }
    .pg-current-file a:hover { color: #bfdbfe; }

    /* Séances */
    .pg-item {
        border: 1px solid rgba(255,255,255,0.09); border-radius: 14px;
        background: rgba(255,255,255,0.025); overflow: hidden; margin-bottom: 0.85rem;
    }
    .pg-item-head {
        display: flex; align-items: center; gap: 0.9rem;
        padding: 0.75rem 1rem; cursor: pointer;
        background: rgba(255,255,255,0.04);
    }
    .pg-item-head:hover { background: rgba(255,255,255,0.07); }
    .pg-item-num {
        width: 32px; height: 32px; border-radius: 9px; flex-shrink: 0;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 800; font-size: 0.85rem;
    }
    .pg-item-title { color: #e2e8f0; font-weight: 700; font-size: 0.88rem; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pg-item-badge {
        font-size: 0.68rem; font-weight: 700; border-radius: 999px; padding: 0.2rem 0.6rem;
        white-space: nowrap; flex-shrink: 0;
    }
    .badge-online { background: rgba(34,197,94,0.12); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
    .badge-onsite { background: rgba(251,146,60,0.12); color: #fb923c; border: 1px solid rgba(251,146,60,0.3); }
    .pg-item-date { color: #94a3b8; font-size: 0.75rem; flex-shrink: 0; }
    .pg-item-body { padding: 1rem; border-top: 1px solid rgba(255,255,255,0.07); }
    .pg-item.collapsed .pg-item-body { display: none; }
    .pg-item.collapsed .pg-item-caret { transform: rotate(-90deg); }
    .pg-item-caret { color: #64748b; font-size: 0.8rem; transition: transform 0.2s; flex-shrink: 0; }
    .pg-item-del {
        background: transparent; border: 1px solid rgba(239,68,68,0.35); color: #f87171;
        border-radius: 8px; padding: 0.3rem 0.55rem; font-size: 0.75rem; flex-shrink: 0;
    }
    .pg-item-del:hover { background: rgba(239,68,68,0.12); }
    .pg-attach-hint { font-size: 0.7rem; color: #64748b; margin-top: 0.35rem; display: block; }

    .pg-submit-bar {
        position: sticky; bottom: 0; z-index: 50;
        background: rgba(15,23,42,0.92); backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.1); border-radius: 14px;
        padding: 0.9rem 1.25rem;
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; margin-top: 1.5rem;
    }
    .pg-summary { color: #94a3b8; font-size: 0.85rem; }
    .pg-summary strong { color: #a78bfa; }
</style>
@endpush

@section('content')
@php
    $mediaUrl = function (?string $path) {
        $p = ltrim((string) $path, '/');
        if ($p === '') return null;
        if (str_starts_with($p, 'storage/app/public/')) return url($p);
        return url('storage/app/public/' . $p);
    };

    $studentsByProgram = collect($students ?? [])->groupBy(function ($s) {
        return trim($s->program ?? '') !== '' ? $s->program : 'Sans formation';
    })->sortKeys();

    $formationsList = [
        'Toutes' => ['icon' => 'fa-book', 'label' => 'Toutes les formations'],
        'Design Graphique' => ['icon' => 'fa-palette', 'label' => 'Design Graphique'],
        'Community Management' => ['icon' => 'fa-mobile-alt', 'label' => 'Community Management'],
        'Design Graphique & Community Manager' => ['icon' => 'fa-layer-group', 'label' => 'Design Graphique & CM'],
        'Gestion Informatique' => ['icon' => 'fa-laptop-code', 'label' => 'Gestion Informatique'],
        'Intelligence Artificielle' => ['icon' => 'fa-robot', 'label' => 'Intelligence Artificielle'],
    ];

    // Ciblage actuel
    $isTargeted = !empty($programme->student_ids);
    $studentIds = [];
    if ($isTargeted) {
        $decoded = json_decode($programme->student_ids, true);
        if (is_array($decoded)) $studentIds = array_map('intval', $decoded);
    }
    $recipientsMode = old('recipients_mode') ?: ($isTargeted ? 'students' : 'formation');

    $oldStudents = old('students');
    if (!is_array($oldStudents)) { $oldStudents = $oldStudents ? [$oldStudents] : []; }
    $oldStudents = !empty($oldStudents) ? array_map('intval', $oldStudents) : $studentIds;

    $oldFormations = old('formation');
    if (!is_array($oldFormations)) { $oldFormations = $oldFormations ? [$oldFormations] : []; }
    if (empty($oldFormations) && !$isTargeted) { $oldFormations = [$programme->formation]; }
    if ($isTargeted) { $oldFormations = []; }

    $oldItems = old('items');
    $hasOld = is_array($oldItems);
@endphp

<div class="container-fluid py-4" style="max-width: 1400px;">

    {{-- ═══ Header ═══ --}}
    <div class="pg-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="pg-header-icon"><i class="fas fa-edit"></i></div>
                <div>
                    <h2 class="text-white mb-1" style="font-size:1.4rem; font-weight:700;">{{ $programme->titre }}</h2>
                    <div class="d-flex gap-2 flex-wrap">
                        <span class="pg-meta-chip"><i class="fas fa-calendar"></i>{{ \Carbon\Carbon::parse($programme->month_start)->translatedFormat('F Y') }}</span>
                        <span class="pg-meta-chip"><i class="fas fa-graduation-cap"></i>{{ $programme->formation }}</span>
                        <span class="pg-meta-chip"><i class="fas fa-list"></i>{{ $items->count() }} séance(s)</span>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.programmes') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Retour à la liste
            </a>
        </div>
    </div>

    {{-- Erreurs --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fas fa-exclamation-circle me-2"></i><strong>Erreur :</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.programmes.update', $programme->id) }}" method="POST" enctype="multipart/form-data" id="programmeForm">
        @csrf
        @method('PUT')

        <div class="row g-4">
            {{-- ═══ Colonne principale ═══ --}}
            <div class="col-lg-7">
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Programme du mois</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label">Titre du programme <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror"
                                   id="titre" name="titre" required
                                   value="{{ old('titre', $programme->titre) }}"
                                   placeholder="Ex : Programme - Janvier 2026">
                            @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="month_start" class="form-label">Mois <span class="text-danger">*</span></label>
                            <input type="month" class="form-control @error('month_start') is-invalid @enderror"
                                   id="month_start" name="month_start" required
                                   value="{{ old('month_start', $programme->month_start ? \Carbon\Carbon::parse($programme->month_start)->format('Y-m') : '') }}">
                            @error('month_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Description (optionnel)</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                      placeholder="Contenu du programme, objectifs pédagogiques...">{{ old('description', $programme->description) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Fichiers --}}
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-paperclip"></i>
                        <h3>Fichiers</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            {{-- PDF --}}
                            <div class="col-md-7">
                                <label class="form-label">PDF du programme</label>
                                @if(!empty($programme->fichier_pdf))
                                    <div class="pg-current-file">
                                        <i class="fas fa-file-pdf"></i>
                                        <a href="{{ $mediaUrl($programme->fichier_pdf) }}" target="_blank">PDF actuel</a>
                                        <span class="text-white-50 ms-auto" style="font-size:0.7rem;">remplacé si nouveau fichier</span>
                                    </div>
                                @endif
                                <label class="pg-upload" id="pdfZone">
                                    <input type="file" name="fichier_pdf" id="fichier_pdf" accept="application/pdf">
                                    <div class="pg-upload-icon"><i class="fas fa-file-pdf"></i></div>
                                    <div class="pg-upload-text" id="pdfLabel">Cliquez ou glissez le nouveau PDF</div>
                                    <div class="pg-upload-hint">PDF uniquement • Max 50 Mo</div>
                                </label>
                                @error('fichier_pdf')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            {{-- Image --}}
                            <div class="col-md-5">
                                <label class="form-label">Image d'illustration</label>
                                @if(!empty($programme->image))
                                    <div class="pg-current-file">
                                        <img src="{{ $mediaUrl($programme->image) }}" alt="Image actuelle">
                                        <a href="{{ $mediaUrl($programme->image) }}" target="_blank">Image actuelle</a>
                                    </div>
                                @endif
                                <label class="pg-upload" id="imageZone">
                                    <input type="file" name="image" id="image" accept="image/*">
                                    <div class="pg-upload-icon"><i class="fas fa-image"></i></div>
                                    <div class="pg-upload-text" id="imageLabel">Nouvelle image</div>
                                    <div class="pg-upload-hint">JPG, PNG, WEBP • Max 50 Mo</div>
                                </label>
                                <div id="imagePreview" class="mt-2" style="display:none;">
                                    <img src="" alt="Aperçu" style="max-width:100%; max-height:120px; border-radius:10px; object-fit:cover;">
                                </div>
                                @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Séances --}}
                <div class="form-card">
                    <div class="form-card-header" style="display:flex; justify-content:space-between; align-items:center; gap:1rem;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-calendar-alt"></i>
                            <h3 style="margin:0;">Séances du mois</h3>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm" id="addProgrammeItem">
                            <i class="fas fa-plus me-1"></i>Ajouter une séance
                        </button>
                    </div>
                    <div class="form-card-body">
                        <div id="programmeItems">
                            @if($hasOld)
                                @foreach($oldItems as $index => $it)
                                    @php $isPres = ($it['type_formation'] ?? '') === 'presentielle'; @endphp
                                    <div class="pg-item" data-index="{{ $index }}">
                                        <div class="pg-item-head">
                                            <div class="pg-item-num item-num">#</div>
                                            <div class="pg-item-title">{{ $it['thematique'] ?? 'Nouvelle séance' }}</div>
                                            <span class="pg-item-date">{{ !empty($it['session_date']) ? \Carbon\Carbon::parse($it['session_date'])->format('d/m') : '' }}</span>
                                            <span class="pg-item-badge {{ $isPres ? 'badge-onsite' : 'badge-online' }}">{{ $isPres ? 'Présentielle' : 'En ligne' }}</span>
                                            <i class="fas fa-chevron-down pg-item-caret"></i>
                                            <button type="button" class="pg-item-del remove-item" onclick="event.stopPropagation();"><i class="fas fa-trash"></i></button>
                                        </div>
                                        <div class="pg-item-body">
                                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $it['id'] ?? '' }}">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Thématique <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control item-thematique" name="items[{{ $index }}][thematique]" required value="{{ $it['thematique'] ?? '' }}">
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control item-date" name="items[{{ $index }}][session_date]" required value="{{ $it['session_date'] ?? '' }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Heure <span class="text-danger">*</span></label>
                                                    <input type="time" class="form-control" name="items[{{ $index }}][session_time]" required value="{{ $it['session_time'] ?? '' }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                                    <select class="form-select item-type" name="items[{{ $index }}][type_formation]" required>
                                                        <option value="en_ligne" {{ !$isPres ? 'selected' : '' }}>En ligne</option>
                                                        <option value="presentielle" {{ $isPres ? 'selected' : '' }}>Présentielle</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3 item-lieu-container" style="display: {{ $isPres ? 'block' : 'none' }};">
                                                <label class="form-label">Lieu <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control item-lieu" name="items[{{ $index }}][lieu]" value="{{ $it['lieu'] ?? '' }}" {{ $isPres ? 'required' : '' }} placeholder="Ex : Salle 2 - Cocody / Abidjan">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="form-label">Description (optionnel)</label>
                                                <textarea class="form-control" name="items[{{ $index }}][description]" rows="2">{{ $it['description'] ?? '' }}</textarea>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="form-label">Pièce jointe (facultatif)</label>
                                                <input type="file" class="form-control" name="items[{{ $index }}][piece_jointe]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach(($items ?? []) as $i => $it)
                                    @php
                                        $type = $it->type_formation ?? '';
                                        $isPres = $type === 'presentielle';
                                        $filePath = $it->piece_jointe ?? null;
                                        $timeVal = $it->session_time;
                                        if (!empty($timeVal)) {
                                            try { $timeVal = \Carbon\Carbon::parse($timeVal)->format('H:i'); } catch (\Throwable $e) { $timeVal = ''; }
                                        }
                                    @endphp
                                    <div class="pg-item" data-index="{{ $i }}">
                                        <div class="pg-item-head">
                                            <div class="pg-item-num item-num">{{ $i + 1 }}</div>
                                            <div class="pg-item-title">{{ $it->thematique }}</div>
                                            <span class="pg-item-date">{{ !empty($it->session_date) ? \Carbon\Carbon::parse($it->session_date)->format('d/m') : '' }}</span>
                                            <span class="pg-item-badge {{ $isPres ? 'badge-onsite' : 'badge-online' }}">{{ $isPres ? 'Présentielle' : 'En ligne' }}</span>
                                            <i class="fas fa-chevron-down pg-item-caret"></i>
                                            <button type="button" class="pg-item-del remove-item" onclick="event.stopPropagation();"><i class="fas fa-trash"></i></button>
                                        </div>
                                        <div class="pg-item-body">
                                            <input type="hidden" name="items[{{ $i }}][id]" value="{{ $it->id }}">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Thématique <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control item-thematique" name="items[{{ $i }}][thematique]" required value="{{ old('items.' . $i . '.thematique', $it->thematique) }}">
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-4">
                                                    <label class="form-label">Date <span class="text-danger">*</span></label>
                                                    <input type="date" class="form-control item-date" name="items[{{ $i }}][session_date]" required value="{{ old('items.' . $i . '.session_date', $it->session_date) }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Heure <span class="text-danger">*</span></label>
                                                    <input type="time" class="form-control" name="items[{{ $i }}][session_time]" required value="{{ old('items.' . $i . '.session_time', $timeVal) }}">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label">Type <span class="text-danger">*</span></label>
                                                    <select class="form-select item-type" name="items[{{ $i }}][type_formation]" required>
                                                        <option value="en_ligne" {{ !$isPres ? 'selected' : '' }}>En ligne</option>
                                                        <option value="presentielle" {{ $isPres ? 'selected' : '' }}>Présentielle</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group mb-3 item-lieu-container" style="display: {{ $isPres ? 'block' : 'none' }};">
                                                <label class="form-label">Lieu <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control item-lieu" name="items[{{ $i }}][lieu]" value="{{ old('items.' . $i . '.lieu', $it->lieu) }}" {{ $isPres ? 'required' : '' }} placeholder="Ex : Salle 2 - Cocody / Abidjan">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label class="form-label">Description (optionnel)</label>
                                                <textarea class="form-control" name="items[{{ $i }}][description]" rows="2">{{ old('items.' . $i . '.description', $it->description) }}</textarea>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label class="form-label">Pièce jointe</label>
                                                @if(!empty($filePath))
                                                    <div class="pg-current-file">
                                                        <i class="fas fa-paperclip"></i>
                                                        <a href="{{ $mediaUrl($filePath) }}" target="_blank">Fichier actuel</a>
                                                    </div>
                                                @endif
                                                <input type="file" class="form-control" name="items[{{ $i }}][piece_jointe]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                                                <span class="pg-attach-hint">PDF, Images, Office • Max 50 Mo — laisser vide pour conserver</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div id="noItems" class="text-center py-3 {{ ($hasOld && count($oldItems)) || $items->count() ? 'd-none' : '' }}" style="color:#64748b; font-size:0.85rem;">
                            <i class="fas fa-calendar-times d-block mb-2" style="font-size:1.4rem; opacity:0.4;"></i>
                            Aucune séance — cliquez sur "Ajouter une séance".
                        </div>

                        @error('items')<div class="text-danger mt-2">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- ═══ Sidebar : destinataires ═══ --}}
            <div class="col-lg-5">
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-bullseye"></i>
                        <h3>Destinataires</h3>
                    </div>
                    <div class="form-card-body">

                        <label class="pg-mode-card {{ $recipientsMode === 'formation' ? 'active' : '' }}" id="modeCardFormation">
                            <input type="radio" name="recipients_mode" value="formation" {{ $recipientsMode === 'formation' ? 'checked' : '' }}>
                            <div class="pg-mode-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div>
                                <div class="pg-mode-title">Par formation</div>
                                <div class="pg-mode-sub">Tous les étudiants d'une formation</div>
                            </div>
                        </label>
                        <label class="pg-mode-card {{ $recipientsMode === 'students' ? 'active' : '' }}" id="modeCardStudents">
                            <input type="radio" name="recipients_mode" value="students" {{ $recipientsMode === 'students' ? 'checked' : '' }}>
                            <div class="pg-mode-icon"><i class="fas fa-user-check"></i></div>
                            <div>
                                <div class="pg-mode-title">Étudiants spécifiques</div>
                                <div class="pg-mode-sub">Choisir un ou plusieurs étudiants</div>
                            </div>
                        </label>

                        {{-- Mode formation (single-select en édition) --}}
                        <div id="zoneFormations" style="{{ $recipientsMode === 'students' ? 'display:none;' : '' }}">
                            <div style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.6px; color:#94a3b8; margin:1rem 0 0.6rem;">
                                Formation <span class="text-danger">*</span>
                            </div>
                            <div class="d-flex flex-wrap gap-2" id="formationPills">
                                @foreach($formationsList as $value => $f)
                                    <label class="pg-pill {{ in_array($value, $oldFormations, true) ? 'checked' : '' }}" data-value="{{ $value }}">
                                        <input type="checkbox" name="formation[]" value="{{ $value }}" {{ in_array($value, $oldFormations, true) ? 'checked' : '' }}>
                                        <i class="fas {{ $f['icon'] }}"></i>{{ $f['label'] }}
                                    </label>
                                @endforeach
                            </div>
                            @error('formation')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-2" style="font-size:0.75rem;">
                                <i class="fas fa-info-circle me-1"></i>En édition, une seule formation est prise en compte.
                            </small>
                        </div>

                        {{-- Mode étudiants --}}
                        <div id="zoneStudents" style="{{ $recipientsMode === 'students' ? '' : 'display:none;' }}">
                            <div class="mt-3">
                                <input type="text" id="studentSearch" class="form-control pg-search" placeholder="🔍 Rechercher un étudiant (nom, email)...">
                            </div>
                            <div class="mt-2" style="max-height:420px; overflow-y:auto;" id="studentsGroups">
                                @foreach($studentsByProgram as $program => $group)
                                    <div class="pg-group" data-program="{{ $program }}">
                                        <div class="pg-group-head">
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="checkbox" class="pg-select-group" title="Tout sélectionner" onclick="event.stopPropagation();" style="accent-color:#8b5cf6;">
                                                <span class="pg-group-title">{{ $program }}</span>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="pg-group-count"><span class="sel-count">0</span>/{{ $group->count() }}</span>
                                                <i class="fas fa-chevron-down pg-caret"></i>
                                            </div>
                                        </div>
                                        <div class="pg-group-body">
                                            @foreach($group as $student)
                                                <label class="pg-student-row" data-search="{{ strtolower(($student->first_name ?? '') . ' ' . ($student->last_name ?? '') . ' ' . ($student->email ?? '')) }}">
                                                    <input type="checkbox" name="students[]" value="{{ $student->id }}" class="pg-student-cb" {{ in_array((int) $student->id, $oldStudents, true) ? 'checked' : '' }}>
                                                    <span>
                                                        <span class="pg-student-name d-block">{{ $student->first_name }} {{ $student->last_name }}</span>
                                                        <span class="pg-student-email d-block">{{ $student->email }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('students')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                            <div class="pg-counter">
                                <span><i class="fas fa-user-check me-2"></i><span id="studentCount">{{ count($oldStudents) }}</span> étudiant(s) sélectionné(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Barre de soumission ═══ --}}
        <div class="pg-submit-bar">
            <div class="pg-summary" id="submitSummary">
                <i class="fas fa-bullseye me-2"></i>Sélectionnez les destinataires
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.programmes') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Annuler
                </a>
                <button type="submit" class="btn btn-success" id="submitBtn" disabled>
                    <i class="fas fa-save me-1"></i>Enregistrer les modifications
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ─── Upload zones ───
    function bindUpload(zoneId, inputId, labelId, previewId) {
        const zone = document.getElementById(zoneId);
        const input = document.getElementById(inputId);
        const label = document.getElementById(labelId);
        if (!zone || !input) return;

        input.addEventListener('change', function () {
            if (input.files && input.files[0]) {
                zone.classList.add('has-file');
                label.textContent = input.files[0].name;
                if (previewId) {
                    const preview = document.getElementById(previewId);
                    const img = preview.querySelector('img');
                    const reader = new FileReader();
                    reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
                    reader.readAsDataURL(input.files[0]);
                }
            }
        });
        ['dragover', 'dragleave', 'drop'].forEach(ev => {
            zone.addEventListener(ev, function (e) {
                e.preventDefault();
                zone.classList.toggle('dragover', ev === 'dragover');
                if (ev === 'drop' && e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
    }
    bindUpload('pdfZone', 'fichier_pdf', 'pdfLabel', null);
    bindUpload('imageZone', 'image', 'imageLabel', 'imagePreview');

    // ─── Séances : collapse, numérotation, lieu ───
    const itemsContainer = document.getElementById('programmeItems');
    const noItems = document.getElementById('noItems');

    function refreshItemNums() {
        itemsContainer.querySelectorAll('.pg-item').forEach((item, i) => {
            const num = item.querySelector('.item-num');
            if (num) num.textContent = i + 1;
        });
        if (noItems) noItems.classList.toggle('d-none', itemsContainer.querySelectorAll('.pg-item').length > 0);
    }

    function bindItemEvents(wrapper) {
        const head = wrapper.querySelector('.pg-item-head');
        const typeSelect = wrapper.querySelector('.item-type');
        const lieuContainer = wrapper.querySelector('.item-lieu-container');
        const lieuInput = wrapper.querySelector('.item-lieu');
        const titleEl = wrapper.querySelector('.pg-item-title');
        const dateEl = wrapper.querySelector('.pg-item-date');
        const badgeEl = wrapper.querySelector('.pg-item-badge');
        const thematiqueInput = wrapper.querySelector('.item-thematique');
        const dateInput = wrapper.querySelector('.item-date');
        const removeBtn = wrapper.querySelector('.remove-item');

        if (head) {
            head.addEventListener('click', function (e) {
                if (e.target.closest('.remove-item')) return;
                wrapper.classList.toggle('collapsed');
            });
        }

        function updateLieu() {
            const isPres = typeSelect && typeSelect.value === 'presentielle';
            if (lieuContainer) lieuContainer.style.display = isPres ? 'block' : 'none';
            if (lieuInput) {
                lieuInput.required = !!isPres;
                if (!isPres) lieuInput.value = '';
            }
            if (badgeEl) {
                badgeEl.className = 'pg-item-badge ' + (isPres ? 'badge-onsite' : 'badge-online');
                badgeEl.textContent = isPres ? 'Présentielle' : 'En ligne';
            }
        }
        if (typeSelect) typeSelect.addEventListener('change', updateLieu);

        if (thematiqueInput && titleEl) {
            thematiqueInput.addEventListener('input', function () {
                titleEl.textContent = this.value.trim() || 'Nouvelle séance';
            });
        }
        if (dateInput && dateEl) {
            dateInput.addEventListener('change', function () {
                if (!this.value) { dateEl.textContent = ''; return; }
                const d = new Date(this.value + 'T00:00:00');
                dateEl.textContent = String(d.getDate()).padStart(2, '0') + '/' + String(d.getMonth() + 1).padStart(2, '0');
            });
        }
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                wrapper.remove();
                refreshItemNums();
            });
        }
    }

    itemsContainer.querySelectorAll('.pg-item').forEach(bindItemEvents);
    refreshItemNums();

    function renderProgrammeItem(index) {
        const wrapper = document.createElement('div');
        wrapper.className = 'pg-item';
        wrapper.setAttribute('data-index', index.toString());
        wrapper.innerHTML = `
            <div class="pg-item-head">
                <div class="pg-item-num item-num">#</div>
                <div class="pg-item-title">Nouvelle séance</div>
                <span class="pg-item-date"></span>
                <span class="pg-item-badge badge-online">En ligne</span>
                <i class="fas fa-chevron-down pg-item-caret"></i>
                <button type="button" class="pg-item-del remove-item"><i class="fas fa-trash"></i></button>
            </div>
            <div class="pg-item-body">
                <input type="hidden" name="items[${index}][id]" value="">
                <div class="form-group mb-3">
                    <label class="form-label">Thématique <span class="text-danger">*</span></label>
                    <input type="text" class="form-control item-thematique" name="items[${index}][thematique]" required placeholder="Ex : Initiation à Adobe Illustrator">
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control item-date" name="items[${index}][session_date]" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Heure <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" name="items[${index}][session_time]" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select item-type" name="items[${index}][type_formation]" required>
                            <option value="en_ligne" selected>En ligne</option>
                            <option value="presentielle">Présentielle</option>
                        </select>
                    </div>
                </div>
                <div class="form-group mb-3 item-lieu-container" style="display:none;">
                    <label class="form-label">Lieu <span class="text-danger">*</span></label>
                    <input type="text" class="form-control item-lieu" name="items[${index}][lieu]" placeholder="Ex : Salle 2 - Cocody / Abidjan">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Description (optionnel)</label>
                    <textarea class="form-control" name="items[${index}][description]" rows="2" placeholder="Détails de la séance..."></textarea>
                </div>
                <div class="form-group mb-0">
                    <label class="form-label">Pièce jointe (facultatif)</label>
                    <input type="file" class="form-control" name="items[${index}][piece_jointe]" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                    <span class="pg-attach-hint">PDF, Images, Office • Max 50 Mo</span>
                </div>
            </div>
        `;
        bindItemEvents(wrapper);
        return wrapper;
    }

    document.getElementById('addProgrammeItem').addEventListener('click', function () {
        itemsContainer.appendChild(renderProgrammeItem(Date.now()));
        refreshItemNums();
    });

    // ─── Mode destinataires ───
    const modeFormation = document.querySelector('input[name="recipients_mode"][value="formation"]');
    const modeStudents = document.querySelector('input[name="recipients_mode"][value="students"]');
    const cardFormation = document.getElementById('modeCardFormation');
    const cardStudents = document.getElementById('modeCardStudents');
    const zoneFormations = document.getElementById('zoneFormations');
    const zoneStudents = document.getElementById('zoneStudents');

    function updateMode() {
        const isStudents = modeStudents.checked;
        cardFormation.classList.toggle('active', !isStudents);
        cardStudents.classList.toggle('active', isStudents);
        zoneFormations.style.display = isStudents ? 'none' : '';
        zoneStudents.style.display = isStudents ? '' : 'none';
        updateSubmitState();
    }
    modeFormation.addEventListener('change', updateMode);
    modeStudents.addEventListener('change', updateMode);

    // ─── Pills formation : sélection UNIQUE en édition ───
    const pills = document.querySelectorAll('#formationPills .pg-pill');
    pills.forEach(pill => {
        const cb = pill.querySelector('input');
        pill.addEventListener('click', function (e) {
            e.preventDefault();
            pills.forEach(p => {
                p.querySelector('input').checked = (p === pill);
                p.classList.toggle('checked', p === pill);
            });
            updateSubmitState();
        });
    });

    // ─── Étudiants : groupes, recherche, sélection ───
    const groups = document.querySelectorAll('#studentsGroups .pg-group');
    const studentCount = document.getElementById('studentCount');
    const searchInput = document.getElementById('studentSearch');

    groups.forEach(group => {
        const head = group.querySelector('.pg-group-head');
        const selectAll = group.querySelector('.pg-select-group');
        const selCount = group.querySelector('.sel-count');
        const checkboxes = group.querySelectorAll('.pg-student-cb');

        head.addEventListener('click', function (e) {
            if (e.target === selectAll) return;
            group.classList.toggle('open');
        });

        function refreshGroup() {
            const checked = [...checkboxes].filter(c => c.checked).length;
            selCount.textContent = checked;
            selectAll.checked = checked === checkboxes.length && checkboxes.length > 0;
            selectAll.indeterminate = checked > 0 && checked < checkboxes.length;
            if (checked > 0) group.classList.add('open');
        }

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => {
                if (!cb.closest('.pg-student-row').classList.contains('hidden-by-search')) {
                    cb.checked = selectAll.checked;
                }
            });
            refreshGroup();
            refreshTotal();
        });

        checkboxes.forEach(cb => cb.addEventListener('change', function () {
            refreshGroup();
            refreshTotal();
        }));

        refreshGroup();
    });

    function refreshTotal() {
        const total = document.querySelectorAll('.pg-student-cb:checked').length;
        studentCount.textContent = total;
        updateSubmitState();
    }

    searchInput.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        groups.forEach(group => {
            let visible = 0;
            group.querySelectorAll('.pg-student-row').forEach(row => {
                const match = !q || row.dataset.search.includes(q);
                row.classList.toggle('hidden-by-search', !match);
                if (match) visible++;
            });
            group.classList.toggle('hidden-by-search', visible === 0);
            if (q && visible > 0) group.classList.add('open');
        });
    });

    // ─── État du bouton submit + résumé ───
    const submitBtn = document.getElementById('submitBtn');
    const submitSummary = document.getElementById('submitSummary');

    function updateSubmitState() {
        const isStudents = modeStudents.checked;
        let count = 0;
        if (isStudents) {
            count = document.querySelectorAll('.pg-student-cb:checked').length;
            submitSummary.innerHTML = count > 0
                ? `<i class="fas fa-user-check me-2"></i><strong>${count}</strong> étudiant(s) recevront le programme`
                : '<i class="fas fa-bullseye me-2"></i>Sélectionnez au moins un étudiant';
        } else {
            const checkedPill = document.querySelector('#formationPills input:checked');
            count = checkedPill ? 1 : 0;
            submitSummary.innerHTML = checkedPill
                ? `<i class="fas fa-graduation-cap me-2"></i>Formation : <strong>${checkedPill.closest('.pg-pill').dataset.value}</strong>`
                : '<i class="fas fa-bullseye me-2"></i>Sélectionnez une formation';
        }
        submitBtn.disabled = count === 0;
    }

    updateMode();
    refreshTotal();
});
</script>
@endpush
@endsection
