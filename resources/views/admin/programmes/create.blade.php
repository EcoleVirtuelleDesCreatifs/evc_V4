@extends('layouts.admin')

@section('title', 'Ajouter un Programme')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .pg-header {
        background: linear-gradient(135deg, rgba(139,92,246,0.15) 0%, rgba(59,130,246,0.10) 100%);
        border: 1px solid rgba(139,92,246,0.3);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .pg-header-icon {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.4rem; flex-shrink: 0;
    }

    /* Mode destinataires : 2 grandes cartes radio */
    .pg-mode-card {
        display: flex; align-items: center; gap: 0.85rem;
        border: 1.5px solid rgba(255,255,255,0.1);
        border-radius: 12px; padding: 0.9rem 1rem;
        cursor: pointer; transition: all 0.2s ease;
        background: rgba(255,255,255,0.03);
        margin-bottom: 0.6rem;
    }
    .pg-mode-card:hover { border-color: rgba(139,92,246,0.4); }
    .pg-mode-card.active {
        border-color: #8b5cf6;
        background: rgba(139,92,246,0.12);
        box-shadow: 0 0 0 3px rgba(139,92,246,0.15);
    }
    .pg-mode-card input { display: none; }
    .pg-mode-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
        background: rgba(139,92,246,0.15); color: #a78bfa;
    }
    .pg-mode-card.active .pg-mode-icon { background: #8b5cf6; color: #fff; }
    .pg-mode-title { color: #e2e8f0; font-weight: 600; font-size: 0.9rem; }
    .pg-mode-sub { color: #94a3b8; font-size: 0.75rem; }

    /* Pills formations */
    .pg-pill {
        display: inline-flex; align-items: center; gap: 0.4rem;
        border: 1.5px solid rgba(255,255,255,0.12);
        border-radius: 999px; padding: 0.45rem 0.95rem;
        cursor: pointer; font-size: 0.82rem; color: #cbd5e1;
        background: rgba(255,255,255,0.03);
        transition: all 0.15s ease; user-select: none;
    }
    .pg-pill:hover { border-color: rgba(139,92,246,0.5); }
    .pg-pill input { display: none; }
    .pg-pill.checked {
        border-color: #8b5cf6; color: #fff;
        background: rgba(139,92,246,0.2);
    }
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
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px; margin-bottom: 0.5rem;
        background: rgba(255,255,255,0.02); overflow: hidden;
    }
    .pg-group-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.55rem 0.85rem; cursor: pointer;
        background: rgba(255,255,255,0.03);
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
        border: 1px solid rgba(139,92,246,0.4);
        border-radius: 10px; padding: 0.6rem 0.9rem;
        color: #fff; font-size: 0.85rem; font-weight: 600;
        display: flex; align-items: center; justify-content: space-between;
        margin-top: 0.75rem; backdrop-filter: blur(8px);
    }

    /* Upload zones */
    .pg-upload {
        border: 2px dashed rgba(255,255,255,0.15);
        border-radius: 12px; padding: 1.25rem;
        text-align: center; cursor: pointer;
        transition: all 0.2s ease; background: rgba(255,255,255,0.02);
        display: block;
    }
    .pg-upload:hover, .pg-upload.dragover { border-color: #8b5cf6; background: rgba(139,92,246,0.08); }
    .pg-upload input { display: none; }
    .pg-upload-icon { font-size: 1.6rem; color: #a78bfa; margin-bottom: 0.4rem; }
    .pg-upload-text { color: #cbd5e1; font-size: 0.85rem; }
    .pg-upload-hint { color: #64748b; font-size: 0.72rem; }
    .pg-upload.has-file { border-style: solid; border-color: #10b981; background: rgba(16,185,129,0.06); }
    .pg-upload.has-file .pg-upload-icon { color: #10b981; }

    .pg-submit-bar {
        position: sticky; bottom: 0; z-index: 50;
        background: rgba(15,23,42,0.92); backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 14px; padding: 0.9rem 1.25rem;
        display: flex; align-items: center; justify-content: space-between;
        gap: 1rem; margin-top: 1.5rem;
    }
    .pg-summary { color: #94a3b8; font-size: 0.85rem; }
    .pg-summary strong { color: #a78bfa; }
</style>
@endpush

@section('content')
@php
    $studentsByProgram = collect($students ?? [])->groupBy(function ($s) {
        return trim($s->program ?? '') !== '' ? $s->program : 'Sans formation';
    })->sortKeys();

    $formationsList = [
        'Toutes' => ['icon' => 'fa-book', 'label' => 'Toutes les formations'],
        'Design Graphique' => ['icon' => 'fa-palette', 'label' => 'Design Graphique'],
        'Community Management' => ['icon' => 'fa-mobile-alt', 'label' => 'Community Management'],
        'Design Graphique & Community Manager' => ['icon' => 'fa-layer-group', 'label' => 'Design Graphique & Community Manager'],
        'Gestion Informatique' => ['icon' => 'fa-laptop-code', 'label' => 'Gestion Informatique'],
        'Intelligence Artificielle' => ['icon' => 'fa-robot', 'label' => 'Intelligence Artificielle'],
    ];

    $oldFormations = old('formation');
    if (!is_array($oldFormations)) { $oldFormations = $oldFormations ? [$oldFormations] : []; }
    $oldStudents = old('students');
    if (!is_array($oldStudents)) { $oldStudents = $oldStudents ? [$oldStudents] : []; }
    $oldStudents = array_map('intval', $oldStudents);
    $oldMode = old('recipients_mode', 'formation');
@endphp

<div class="container-fluid py-4" style="max-width: 1400px;">

    {{-- ═══ Header ═══ --}}
    <div class="pg-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="pg-header-icon"><i class="fas fa-calendar-alt"></i></div>
                <div>
                    <h2 class="text-white mb-1" style="font-size:1.4rem; font-weight:700;">Ajouter un Programme</h2>
                    <p class="text-muted mb-0" style="font-size:0.85rem;">Créez le programme du mois et ciblez les destinataires</p>
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

    <form action="{{ route('admin.programmes.store') }}" method="POST" enctype="multipart/form-data" id="programmeForm">
        @csrf

        <div class="row g-4">
            {{-- ═══ Colonne principale : infos programme ═══ --}}
            <div class="col-lg-7">
                <div class="form-card mb-4">
                    <div class="form-card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Programme du mois</h3>
                    </div>
                    <div class="form-card-body">
                        {{-- Titre --}}
                        <div class="form-group mb-4">
                            <label for="titre" class="form-label">Titre du programme <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror"
                                   id="titre" name="titre" required
                                   value="{{ old('titre') }}"
                                   placeholder="Ex : Programme - Janvier 2026">
                            @error('titre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Mois --}}
                        <div class="form-group mb-4">
                            <label for="month_start" class="form-label">Mois <span class="text-danger">*</span></label>
                            <input type="month" class="form-control @error('month_start') is-invalid @enderror"
                                   id="month_start" name="month_start" required
                                   value="{{ old('month_start') }}">
                            @error('month_start')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>Ce programme regroupera toutes les séances de ce mois
                            </small>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label for="description" class="form-label">Description (optionnel)</label>
                            <textarea class="form-control" id="description" name="description" rows="4"
                                      placeholder="Contenu du programme, objectifs pédagogiques...">{{ old('description') }}</textarea>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Visible par les étudiants</small>
                        </div>
                    </div>
                </div>

                {{-- Fichiers --}}
                <div class="form-card">
                    <div class="form-card-header">
                        <i class="fas fa-paperclip"></i>
                        <h3>Fichiers</h3>
                    </div>
                    <div class="form-card-body">
                        <div class="row g-3">
                            {{-- PDF --}}
                            <div class="col-md-7">
                                <label class="form-label">PDF du programme <span class="text-danger">*</span></label>
                                <label class="pg-upload" id="pdfZone">
                                    <input type="file" name="programme_pdf" id="programme_pdf" accept="application/pdf" required>
                                    <div class="pg-upload-icon"><i class="fas fa-file-pdf"></i></div>
                                    <div class="pg-upload-text" id="pdfLabel">Cliquez ou glissez le PDF ici</div>
                                    <div class="pg-upload-hint">PDF uniquement • Max 50 Mo</div>
                                </label>
                                @error('programme_pdf')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            {{-- Image --}}
                            <div class="col-md-5">
                                <label class="form-label">Image d'illustration</label>
                                <label class="pg-upload" id="imageZone">
                                    <input type="file" name="image" id="image" accept="image/*">
                                    <div class="pg-upload-icon"><i class="fas fa-image"></i></div>
                                    <div class="pg-upload-text" id="imageLabel">Image (optionnel)</div>
                                    <div class="pg-upload-hint">JPG, PNG, WEBP • Max 5 Mo</div>
                                </label>
                                <div id="imagePreview" class="mt-2" style="display:none;">
                                    <img src="" alt="Aperçu" style="max-width:100%; max-height:120px; border-radius:10px; object-fit:cover;">
                                </div>
                                @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
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

                        {{-- Choix du mode --}}
                        <label class="pg-mode-card {{ $oldMode === 'formation' ? 'active' : '' }}" id="modeCardFormation">
                            <input type="radio" name="recipients_mode" value="formation" {{ $oldMode === 'formation' ? 'checked' : '' }}>
                            <div class="pg-mode-icon"><i class="fas fa-graduation-cap"></i></div>
                            <div>
                                <div class="pg-mode-title">Par formation</div>
                                <div class="pg-mode-sub">Envoyer à tous les étudiants d'une ou plusieurs formations</div>
                            </div>
                        </label>
                        <label class="pg-mode-card {{ $oldMode === 'students' ? 'active' : '' }}" id="modeCardStudents">
                            <input type="radio" name="recipients_mode" value="students" {{ $oldMode === 'students' ? 'checked' : '' }}>
                            <div class="pg-mode-icon"><i class="fas fa-user-check"></i></div>
                            <div>
                                <div class="pg-mode-title">Étudiants spécifiques</div>
                                <div class="pg-mode-sub">Choisir un ou plusieurs étudiants individuellement</div>
                            </div>
                        </label>

                        {{-- Mode formations --}}
                        <div id="zoneFormations" style="{{ $oldMode === 'students' ? 'display:none;' : '' }}">
                            <div class="pv-section-title" style="font-size:0.72rem; font-weight:600; text-transform:uppercase; letter-spacing:0.6px; color:#94a3b8; margin:1rem 0 0.6rem;">
                                Formations <span class="text-danger">*</span>
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
                            <div class="pg-counter" style="margin-top:1rem;">
                                <span><i class="fas fa-graduation-cap me-2"></i><span id="formationCount">{{ count($oldFormations) }}</span> formation(s) sélectionnée(s)</span>
                            </div>
                        </div>

                        {{-- Mode étudiants --}}
                        <div id="zoneStudents" style="{{ $oldMode === 'students' ? '' : 'display:none;' }}">
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
                                <span><i class="fas fa-user-check me-2"></i><span id="studentCount">0</span> étudiant(s) sélectionné(s)</span>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3 mb-0" style="font-size:0.8rem;">
                            <i class="fas fa-envelope me-1"></i>Les étudiants concernés recevront un <strong>email de notification</strong>.
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
                    <i class="fas fa-paper-plane me-1"></i>Créer le programme
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
    bindUpload('pdfZone', 'programme_pdf', 'pdfLabel', null);
    bindUpload('imageZone', 'image', 'imageLabel', 'imagePreview');

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

    // ─── Pills formations ───
    const pills = document.querySelectorAll('#formationPills .pg-pill');
    const formationCount = document.getElementById('formationCount');

    pills.forEach(pill => {
        const cb = pill.querySelector('input');
        pill.addEventListener('click', function (e) {
            e.preventDefault();
            const isToutes = pill.dataset.value === 'Toutes';
            if (!cb.checked) {
                if (isToutes) {
                    // "Toutes" est exclusif
                    pills.forEach(p => {
                        const c = p.querySelector('input');
                        c.checked = p.dataset.value === 'Toutes';
                        p.classList.toggle('checked', c.checked);
                    });
                } else {
                    // Décocher "Toutes" si on choisit une formation précise
                    pills.forEach(p => {
                        if (p.dataset.value === 'Toutes') {
                            p.querySelector('input').checked = false;
                            p.classList.remove('checked');
                        }
                    });
                    cb.checked = true;
                    pill.classList.add('checked');
                }
            } else {
                cb.checked = false;
                pill.classList.remove('checked');
            }
            formationCount.textContent = document.querySelectorAll('#formationPills input:checked').length;
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
        }

        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => {
                if (cb.closest('.pg-student-row').style.display !== 'none') cb.checked = selectAll.checked;
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
            count = document.querySelectorAll('#formationPills input:checked').length;
            submitSummary.innerHTML = count > 0
                ? `<i class="fas fa-graduation-cap me-2"></i><strong>${count}</strong> formation(s) ciblée(s)`
                : '<i class="fas fa-bullseye me-2"></i>Sélectionnez au moins une formation';
        }
        submitBtn.disabled = count === 0;
    }

    updateMode();
    refreshTotal();
});
</script>
@endpush
@endsection
