@extends('layouts.admin')

@section('title', 'Rendez-vous Étudiants')

@push('styles')
<style>
    .rdv-header {
        background: linear-gradient(135deg, rgba(139,92,246,0.15) 0%, rgba(59,130,246,0.10) 100%);
        border: 1px solid rgba(139,92,246,0.3);
        border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem;
    }
    .rdv-header-icon {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.4rem; flex-shrink: 0;
    }
    .rdv-kpi {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px; padding: 1rem 1.1rem; display: flex; align-items: center; gap: 0.85rem;
    }
    .rdv-kpi-icon {
        width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    .rdv-kpi-val { font-size: 1.4rem; font-weight: 800; color: #fff; line-height: 1; }
    .rdv-kpi-lbl { font-size: 0.75rem; color: rgba(255,255,255,0.55); font-weight: 600; }

    .rdv-card {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem;
    }
    .rdv-card-title {
        color: #fff; font-weight: 800; font-size: 0.95rem;
        display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;
    }
    .rdv-card-title i { color: #a78bfa; }

    .rdv-form .form-control, .rdv-form .form-select {
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.14); color: #fff;
    }
    .rdv-form .form-control:focus, .rdv-form .form-select:focus {
        background: rgba(255,255,255,0.08); border-color: #8b5cf6; color: #fff;
        box-shadow: 0 0 0 3px rgba(139,92,246,0.2);
    }
    .rdv-form .form-select option { background: #0f172a; }
    .rdv-form .form-label { color: rgba(255,255,255,0.75); font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.04em; }
    .rdv-form .form-control::placeholder { color: rgba(255,255,255,0.3); }
    .rdv-form input[type="date"]::-webkit-calendar-picker-indicator,
    .rdv-form input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(0.7); }

    /* Slots list */
    .slot-row {
        display: flex; align-items: center; gap: 1rem; padding: 0.85rem 1rem;
        border-radius: 12px; background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.07); margin-bottom: 0.6rem;
        flex-wrap: wrap; transition: opacity 0.2s;
    }
    .slot-row.inactive { opacity: 0.45; }
    .slot-row.flash { animation: slotFlash 1s ease; }
    @keyframes slotFlash { 0% { background: rgba(139,92,246,0.25); } 100% { background: rgba(255,255,255,0.03); } }
    .slot-date {
        min-width: 52px; text-align: center; border-radius: 10px; padding: 0.4rem;
        background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.3);
    }
    .slot-date .d { color: #fff; font-weight: 800; font-size: 1.1rem; line-height: 1; }
    .slot-date .m { color: #c4b5fd; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; }
    .slot-meta { flex: 1; min-width: 180px; }
    .slot-meta .time { color: #fff; font-weight: 800; font-size: 0.92rem; }
    .slot-meta .sub { color: rgba(255,255,255,0.55); font-size: 0.78rem; }
    .occ-badge {
        font-size: 0.75rem; font-weight: 700; border-radius: 999px; padding: 0.25rem 0.7rem;
        background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.35);
        white-space: nowrap;
    }
    .occ-full { background: rgba(34,197,94,0.15); color: #4ade80; border-color: rgba(34,197,94,0.35); }
    .occ-off { background: rgba(148,163,184,0.12); color: #94a3b8; border-color: rgba(148,163,184,0.3); }
    .btn-del-slot {
        background: transparent; border: 1px solid rgba(239,68,68,0.35); color: #f87171;
        border-radius: 8px; padding: 0.35rem 0.6rem; font-size: 0.78rem;
    }
    .btn-del-slot:hover { background: rgba(239,68,68,0.12); color: #f87171; }
    .btn-del-slot:disabled { opacity: 0.5; cursor: wait; }

    /* Filters */
    .rdv-filters { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .rdv-pill {
        padding: 0.4rem 0.9rem; border-radius: 999px; font-size: 0.78rem; font-weight: 700;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.7); cursor: pointer; transition: all 0.15s; user-select: none;
    }
    .rdv-pill:hover { border-color: rgba(139,92,246,0.5); color: #fff; }
    .rdv-pill.active { background: #8b5cf6; border-color: #8b5cf6; color: #fff; }
    .rdv-search {
        flex: 1 1 180px; display: flex; align-items: center; gap: 0.5rem;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px; padding: 0.35rem 0.8rem;
    }
    .rdv-search i { color: #64748b; font-size: 0.8rem; }
    .rdv-search input {
        flex: 1; background: transparent; border: none; outline: none;
        color: #fff; font-size: 0.82rem;
    }
    .rdv-search input::placeholder { color: #64748b; }

    /* Table */
    .rdv-table {
        color: rgba(255,255,255,0.85); font-size: 0.85rem;
        --bs-table-bg: transparent;
        --bs-table-color: rgba(255,255,255,0.85);
        --bs-table-border-color: rgba(255,255,255,0.08);
        --bs-table-hover-bg: transparent;
        background: transparent;
    }
    .rdv-table > :not(caption) > * > * { background-color: transparent !important; box-shadow: none; }
    .rdv-table tbody tr:hover > * { background-color: rgba(139,92,246,0.06) !important; }
    .rdv-table thead th {
        color: rgba(255,255,255,0.5); font-weight: 700; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.05em;
        border-bottom: 1px solid rgba(255,255,255,0.08); padding: 0.6rem 0.75rem;
    }
    .rdv-table tbody td { border-bottom: 1px solid rgba(255,255,255,0.05); padding: 0.75rem; vertical-align: middle; }
    .rdv-table tbody tr { transition: background 0.15s; }
    .rdv-table tbody tr:hover { background: rgba(139,92,246,0.05); }
    .rdv-table tbody tr.row-flash { animation: slotFlash 1.2s ease; }
    .stu-name { color: #fff; font-weight: 700; }
    .stu-mail { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
    .stu-cell { display: flex; align-items: center; gap: 0.7rem; min-width: 0; }
    .stu-avatar {
        width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
        object-fit: cover; border: 2px solid rgba(139,92,246,0.4);
    }
    .stu-avatar-ph {
        width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 800; font-size: 0.95rem;
        border: 2px solid rgba(139,92,246,0.4);
    }
    .fmt-chip {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.68rem; font-weight: 700; border-radius: 999px;
        padding: 0.15rem 0.55rem; margin-top: 0.25rem;
    }
    .fmt-dg { background: rgba(59,130,246,0.14); color: #93c5fd; border: 1px solid rgba(59,130,246,0.35); }
    .fmt-cm { background: rgba(236,72,153,0.14); color: #f9a8d4; border: 1px solid rgba(236,72,153,0.35); }
    .fmt-dgcm { background: rgba(251,191,36,0.14); color: #fcd34d; border: 1px solid rgba(251,191,36,0.35); }
    .fmt-gi { background: rgba(249,115,22,0.14); color: #fdba74; border: 1px solid rgba(249,115,22,0.35); }
    .fmt-ia { background: rgba(34,211,238,0.14); color: #67e8f9; border: 1px solid rgba(34,211,238,0.35); }
    .fmt-other { background: rgba(148,163,184,0.12); color: #94a3b8; border: 1px solid rgba(148,163,184,0.3); }
    .rdv-msg {
        color: rgba(255,255,255,0.6); font-size: 0.76rem; font-style: italic;
        background: rgba(255,255,255,0.04); border-left: 3px solid rgba(139,92,246,0.4);
        border-radius: 6px; padding: 0.35rem 0.6rem; margin-top: 0.35rem;
        max-width: 260px; overflow: hidden; text-overflow: ellipsis;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
        cursor: help;
    }
    .stb { font-size: 0.72rem; font-weight: 800; border-radius: 999px; padding: 0.25rem 0.65rem; white-space: nowrap; }
    .stb-pending { background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.4); }
    .stb-confirmed { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.4); }
    .stb-cancelled { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.35); }
    .stb-completed { background: rgba(148,163,184,0.15); color: #94a3b8; border: 1px solid rgba(148,163,184,0.35); }
    .act-btn {
        border: none; border-radius: 8px; padding: 0.35rem 0.6rem; font-size: 0.75rem; font-weight: 700;
        transition: background 0.15s;
    }
    .act-confirm { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.4); }
    .act-confirm:hover { background: rgba(34,197,94,0.28); }
    .act-cancel { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.35); }
    .act-cancel:hover { background: rgba(239,68,68,0.22); }
    .act-done { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.4); }
    .act-done:hover { background: rgba(59,130,246,0.28); }
    .act-edit { background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.4); }
    .act-edit:hover { background: rgba(139,92,246,0.28); }
    .act-delete { background: rgba(148,163,184,0.10); color: #94a3b8; border: 1px solid rgba(148,163,184,0.3); }
    .act-delete:hover { background: rgba(239,68,68,0.25); color: #fca5a5; border-color: rgba(239,68,68,0.45); }
    .meet-link { color: #93c5fd; font-size: 0.72rem; text-decoration: none; }
    .meet-link:hover { color: #bfdbfe; }

    .rdv-modal .modal-content { background: #0f172a; border: 1px solid rgba(255,255,255,0.12); color: #fff; border-radius: 16px; }
    .rdv-modal .modal-header, .rdv-modal .modal-footer { border-color: rgba(255,255,255,0.1); }
    .rdv-modal .btn-close { filter: invert(1); }
    .rdv-modal .form-control {
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: #fff;
    }
    .rdv-modal .form-control:focus { background: rgba(255,255,255,0.08); border-color: #8b5cf6; color:#fff; }
    .rdv-modal .form-label { color: rgba(255,255,255,0.75); font-weight: 700; font-size: 0.8rem; }

    .rdv-empty { text-align: center; padding: 2.5rem 1rem; color: rgba(255,255,255,0.5); }
    .rdv-empty i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: 0.4; }

    /* Bouton +RDV sur créneau */
    .btn-add-rdv {
        background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.35); color: #4ade80;
        border-radius: 8px; padding: 0.35rem 0.6rem; font-size: 0.78rem;
    }
    .btn-add-rdv:hover { background: rgba(34,197,94,0.22); color: #4ade80; }

    /* Picker étudiants dans la modale */
    .bk-group {
        border: 1px solid rgba(255,255,255,0.08); border-radius: 10px;
        margin-bottom: 0.45rem; background: rgba(255,255,255,0.02); overflow: hidden;
    }
    .bk-group-head {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.5rem 0.8rem; cursor: pointer; background: rgba(255,255,255,0.03);
    }
    .bk-group-head:hover { background: rgba(255,255,255,0.06); }
    .bk-group-title { font-size: 0.75rem; font-weight: 700; color: #a78bfa; text-transform: uppercase; letter-spacing: 0.4px; }
    .bk-group-count { font-size: 0.7rem; color: #94a3b8; }
    .bk-group-body { display: none; padding: 0.3rem 0.45rem; max-height: 180px; overflow-y: auto; }
    .bk-group.open .bk-group-body { display: block; }
    .bk-group.open .bk-caret { transform: rotate(180deg); }
    .bk-caret { transition: transform 0.2s; color: #64748b; font-size: 0.72rem; }
    .bk-row {
        display: flex; align-items: center; gap: 0.55rem;
        padding: 0.35rem 0.45rem; border-radius: 7px; cursor: pointer;
    }
    .bk-row:hover { background: rgba(139,92,246,0.08); }
    .bk-row input { accent-color: #8b5cf6; width: 15px; height: 15px; flex-shrink: 0; }
    .bk-name { font-size: 0.82rem; color: #e2e8f0; }
    .bk-mail { font-size: 0.7rem; color: #64748b; }
    .bk-row.bk-hidden, .bk-group.bk-hidden { display: none; }
    .bk-counter {
        background: linear-gradient(135deg, rgba(139,92,246,0.25), rgba(99,102,241,0.2));
        border: 1px solid rgba(139,92,246,0.4); border-radius: 10px;
        padding: 0.55rem 0.85rem; color: #fff; font-size: 0.82rem; font-weight: 600;
        margin-top: 0.6rem; display: flex; justify-content: space-between; align-items: center;
    }
    .bk-places { color: #fbbf24; font-size: 0.75rem; }
    .bk-slot-info {
        background: rgba(37,99,235,0.12); border: 1px solid rgba(59,130,246,0.35);
        border-radius: 10px; padding: 0.6rem 0.85rem; color: #bfdbfe;
        font-weight: 700; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .rdv-modal .form-select {
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: #fff;
    }
    .rdv-modal .form-select option { background: #0f172a; }

    /* Toasts */
    .rdv-toasts { position: fixed; top: 20px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 0.6rem; }
    .rdv-toast {
        background: #0f172a; border: 1px solid rgba(255,255,255,0.12);
        border-left: 4px solid #8b5cf6; border-radius: 12px;
        color: #fff; padding: 0.85rem 1.1rem; min-width: 280px; max-width: 380px;
        font-size: 0.85rem; font-weight: 600;
        box-shadow: 0 12px 40px rgba(0,0,0,0.5);
        display: flex; align-items: center; gap: 0.6rem;
        animation: toastIn 0.25s ease;
    }
    .rdv-toast.success { border-left-color: #22c55e; }
    .rdv-toast.error { border-left-color: #ef4444; }
    .rdv-toast.out { animation: toastOut 0.3s ease forwards; }
    @keyframes toastIn { from { transform: translateX(30px); opacity: 0; } to { transform: none; opacity: 1; } }
    @keyframes toastOut { to { transform: translateX(30px); opacity: 0; } }

    .spin { animation: rdvSpin 0.8s linear infinite; }
    @keyframes rdvSpin { to { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">

    <!-- Header -->
    <div class="rdv-header d-flex align-items-center gap-3">
        <div class="rdv-header-icon"><i class="fas fa-calendar-check"></i></div>
        <div>
            <h4 class="text-white fw-bold mb-1">Rendez-vous Étudiants</h4>
            <p class="text-white-50 mb-0" style="font-size: 0.85rem;">Gérez les disponibilités des formateurs et les demandes d'assistance spéciale.</p>
        </div>
    </div>

    <!-- KPIs -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="rdv-kpi">
                <div class="rdv-kpi-icon" style="background: rgba(251,191,36,0.15); color: #fbbf24;"><i class="fas fa-hourglass-half"></i></div>
                <div><div class="rdv-kpi-val" id="kpiPending">0</div><div class="rdv-kpi-lbl">En attente</div></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rdv-kpi">
                <div class="rdv-kpi-icon" style="background: rgba(34,197,94,0.15); color: #4ade80;"><i class="fas fa-check-circle"></i></div>
                <div><div class="rdv-kpi-val" id="kpiConfirmed">0</div><div class="rdv-kpi-lbl">Confirmés à venir</div></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rdv-kpi">
                <div class="rdv-kpi-icon" style="background: rgba(59,130,246,0.15); color: #93c5fd;"><i class="fas fa-calendar-alt"></i></div>
                <div><div class="rdv-kpi-val" id="kpiSlots">0</div><div class="rdv-kpi-lbl">Créneaux ouverts</div></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rdv-kpi">
                <div class="rdv-kpi-icon" style="background: rgba(139,92,246,0.15); color: #a78bfa;"><i class="fas fa-list"></i></div>
                <div><div class="rdv-kpi-val" id="kpiTotal">0</div><div class="rdv-kpi-lbl">Total RDV</div></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- ═══ Créneaux ═══ -->
        <div class="col-lg-5">
            <div class="rdv-card">
                <div class="rdv-card-title"><i class="fas fa-plus-circle"></i> Ajouter des disponibilités</div>
                <form id="slotForm" class="rdv-form" novalidate>
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ now()->toDateString() }}" min="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Début</label>
                            <input type="time" name="start_time" class="form-control" value="09:00" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fin</label>
                            <input type="time" name="end_time" class="form-control" value="09:30" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mode</label>
                            <select name="mode" class="form-select">
                                <option value="en_ligne" selected>🎥 En ligne</option>
                                <option value="presentiel">📍 Présentiel</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Capacité</label>
                            <input type="number" name="capacity" class="form-control" value="1" min="1" max="10" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Lieu / Lien <small class="text-white-50">(optionnel)</small></label>
                            <input type="text" name="lieu" class="form-control" placeholder="Ex : Campus EVC ou lien Meet">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Répéter chaque semaine</label>
                            <select name="repeat_weeks" class="form-select">
                                <option value="0">Ne pas répéter</option>
                                @for($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">Répéter {{ $i }} semaine{{ $i > 1 ? 's' : '' }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-12 mt-2">
                            <button type="submit" class="btn w-100 fw-bold" id="slotSubmitBtn" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: #fff; border-radius: 10px;">
                                <i class="fas fa-plus me-1"></i> Créer le(s) créneau(x)
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="rdv-card">
                <div class="rdv-card-title"><i class="fas fa-calendar-alt"></i> Créneaux à venir (<span id="slotsCount">0</span>)</div>
                <div id="slotsList"></div>
            </div>
        </div>

        <!-- ═══ Demandes de RDV ═══ -->
        <div class="col-lg-7">
            <div class="rdv-card">
                <div class="rdv-card-title" style="justify-content: space-between;">
                    <span class="d-flex align-items-center gap-2"><i class="fas fa-clipboard-list"></i> Demandes de rendez-vous</span>
                    <button type="button" class="btn btn-sm fw-bold" id="newRdvBtn" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color:#fff; border-radius: 999px;">
                        <i class="fas fa-plus me-1"></i>Nouveau RDV
                    </button>
                </div>

                <div class="rdv-filters">
                    <span class="rdv-pill active" data-status="">Tous</span>
                    <span class="rdv-pill" data-status="pending">En attente</span>
                    <span class="rdv-pill" data-status="confirmed">Confirmés</span>
                    <span class="rdv-pill" data-status="completed">Terminés</span>
                    <span class="rdv-pill" data-status="cancelled">Annulés</span>
                    <span style="width:1px; background: rgba(255,255,255,0.12); margin: 0 0.25rem;"></span>
                    <span class="rdv-pill" data-period="upcoming">À venir</span>
                    <span class="rdv-pill" data-period="past">Passés</span>
                    <span class="rdv-pill active" data-period="">Toutes dates</span>
                    <div class="rdv-search">
                        <i class="fas fa-search"></i>
                        <input type="text" id="rdvSearch" placeholder="Étudiant, motif…">
                    </div>
                </div>
                <div class="rdv-filters" id="formationFilters" style="margin-top:-0.4rem;">
                    <span class="rdv-pill active" data-formation="">Toutes formations</span>
                </div>

                <div class="table-responsive">
                    <table class="table rdv-table mb-0">
                        <thead>
                            <tr>
                                <th>Étudiant</th>
                                <th>Créneau</th>
                                <th>Motif</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="rdvTbody"></tbody>
                    </table>
                </div>
                <div class="rdv-empty d-none" id="rdvEmpty"><i class="fas fa-inbox"></i>Aucune demande pour ces filtres.</div>
            </div>
        </div>
    </div>
</div>

<!-- Toasts -->
<div class="rdv-toasts" id="toastBox"></div>

<!-- ═══ Modal action statut ═══ -->
@push('modals')
<div class="modal fade rdv-modal" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="statusForm">
                <input type="hidden" id="statusInput">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalTitle">Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-white-50 mb-3" id="statusModalLabel"></p>
                    <div class="mb-3" id="meetLinkField">
                        <label class="form-label">Lien de réunion <small class="text-white-50">(auto-généré si vide)</small></label>
                        <input type="url" id="meetLinkInput" class="form-control" placeholder="https://meet.jit.si/...">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Note pour l'étudiant <small class="text-white-50">(optionnel)</small></label>
                        <textarea id="adminNoteInput" class="form-control" rows="3" maxlength="1000"
                                  placeholder="Ex : Préparez votre question, le lien vous sera envoyé…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-sm fw-bold" id="statusSubmitBtn" style="background:#8b5cf6; color:#fff;">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- ═══ Modal création RDV par l'admin ═══ -->
<div class="modal fade rdv-modal" id="bookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form id="bookForm">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus me-2" style="color:#a78bfa;"></i>Créer un rendez-vous</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="bookError" class="alert alert-danger d-none" role="alert"></div>
                    <div class="mb-3">
                        <label class="form-label" for="bookType">Organisation du rendez-vous</label>
                        <select id="bookType" class="form-select">
                            <option value="direct">Rendez-vous direct — saisir la date et les horaires</option>
                            <option value="existing">Utiliser un créneau disponible</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="bookExistingFields">
                        <label class="form-label" for="bookSlotSelect">Créneau <span class="text-danger">*</span></label>
                        <select id="bookSlotSelect" class="form-select" disabled></select>
                        <div class="bk-slot-info mt-2" id="bookSlotInfo" style="display:none;"></div>
                    </div>
                    <fieldset id="bookDirectFields" class="mb-3">
                        <div class="row g-3">
                            <div class="col-sm-4">
                                <label class="form-label" for="bookDate">Date</label>
                                <input type="date" class="form-control" id="bookDate" min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="bookStart">Début</label>
                                <input type="time" class="form-control" id="bookStart" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="bookEnd">Fin</label>
                                <input type="time" class="form-control" id="bookEnd" required>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label" for="bookMode">Mode</label>
                                <select class="form-select" id="bookMode">
                                    <option value="en_ligne">En ligne</option>
                                    <option value="presentiel">Présentiel</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label" for="bookLocation">Lieu / adresse (optionnel)</label>
                                <input type="text" class="form-control" id="bookLocation" maxlength="255">
                            </div>
                        </div>
                        <p class="text-white-50 small mt-2 mb-0">Réservé aux étudiants cochés. Ce rendez-vous ne sera pas proposé comme disponibilité aux autres étudiants.</p>
                    </fieldset>
                    <div class="mb-3">
                        <label class="form-label">Motif <span class="text-danger">*</span></label>
                        <select id="bookMotif" class="form-select">
                            @foreach($motifs as $motif)
                                <option value="{{ $motif }}">{{ $motif }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message / contexte <small class="text-white-50">(optionnel)</small></label>
                        <textarea id="bookMessage" class="form-control" rows="2" maxlength="2000"
                                  placeholder="Objet de l'assistance…"></textarea>
                    </div>
                    <div class="mb-3" id="bookMeetField">
                        <label class="form-label">Lien de réunion <small class="text-white-50">(auto-généré si vide et en ligne)</small></label>
                        <input type="url" id="bookMeetLink" class="form-control" placeholder="https://meet.jit.si/...">
                    </div>

                    <label class="form-label d-flex justify-content-between align-items-center">
                        <span>Étudiants <span class="text-danger">*</span></span>
                        <small class="text-white-50">cochez un ou plusieurs étudiants</small>
                    </label>
                    <input type="text" id="bookSearch" class="form-control mb-2" placeholder="🔍 Rechercher (nom, email)…">
                    <div id="bookGroups" style="max-height:260px; overflow-y:auto;"></div>
                    <div class="bk-counter">
                        <span><i class="fas fa-user-check me-2"></i><span id="bookCount">0</span> étudiant(s) sélectionné(s)</span>
                        <span class="bk-places" id="bookPlaces"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-sm fw-bold" id="bookSubmitBtn" style="background:#22c55e; color:#fff;" disabled>
                        <i class="fas fa-check me-1"></i>Créer et confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ═══ Modal modification RDV ═══ -->
<div class="modal fade rdv-modal" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm">
                <input type="hidden" id="editRdvId">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-pen me-2" style="color:#a78bfa;"></i>Modifier le rendez-vous</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-white-50 mb-3" id="editModalLabel"></p>
                    <div class="mb-3">
                        <label class="form-label">Créneau <span class="text-danger">*</span></label>
                        <select id="editSlotSelect" class="form-select"></select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif <span class="text-danger">*</span></label>
                        <select id="editMotif" class="form-select">
                            @foreach($motifs as $motif)
                                <option value="{{ $motif }}">{{ $motif }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message <small class="text-white-50">(optionnel)</small></label>
                        <textarea id="editMessage" class="form-control" rows="2" maxlength="2000"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lien de réunion <small class="text-white-50">(auto si vide et en ligne)</small></label>
                        <input type="url" id="editMeetLink" class="form-control" placeholder="https://meet.jit.si/...">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Note pour l'étudiant <small class="text-white-50">(optionnel)</small></label>
                        <textarea id="editNote" class="form-control" rows="2" maxlength="1000"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-sm fw-bold" id="editSubmitBtn" style="background:#8b5cf6; color:#fff;">
                        <i class="fas fa-save me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const URLS = {
        storeSlot: '{{ route('admin.appointments.slots.store') }}',
        deleteSlot: id => '{{ url('/evc/app/admin/rendez-vous/slots') }}/' + id,
        status: id => '{{ url('/evc/app/admin/rendez-vous') }}/' + id + '/status',
        update: id => '{{ url('/evc/app/admin/rendez-vous') }}/' + id + '/update',
        destroy: id => '{{ url('/evc/app/admin/rendez-vous') }}/' + id,
        storeAppointment: '{{ route('admin.appointments.store') }}',
    };

    let slots = @json($slotsJson);
    let appointments = @json($appointmentsJson);
    const students = @json($studentsJson);
    const filterState = { status: '', period: '', formation: '', q: '' };

    /* ─── Toasts ─── */
    function toast(msg, type = 'success') {
        const box = document.getElementById('toastBox');
        const el = document.createElement('div');
        el.className = 'rdv-toast ' + type;
        el.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i><span></span>`;
        el.querySelector('span').textContent = msg;
        box.appendChild(el);
        setTimeout(() => { el.classList.add('out'); setTimeout(() => el.remove(), 350); }, 4000);
    }

    async function api(url, method, body) {
        const res = await fetch(url, {
            method,
            headers: {
                'X-CSRF-TOKEN': CSRF,
                'Accept': 'application/json',
                ...(body ? { 'Content-Type': 'application/json' } : {}),
            },
            body: body ? JSON.stringify(body) : null,
        });
        const data = await res.json().catch(() => ({}));
        if (!res.ok) {
            const first = data.errors ? Object.values(data.errors).flat()[0] : (data.message || 'Erreur serveur.');
            throw new Error(first);
        }
        return data;
    }

    /* ─── Rendu ─── */
    function esc(s) {
        return String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }

    function renderSlots() {
        const list = document.getElementById('slotsList');
        document.getElementById('slotsCount').textContent = slots.length;

        if (!slots.length) {
            list.innerHTML = '<div class="rdv-empty"><i class="fas fa-calendar-times"></i>Aucun créneau programmé.</div>';
            return;
        }

        list.innerHTML = slots.map(s => {
            const occ = !s.active ? 'occ-off' : (s.booked >= s.capacity ? 'occ-full' : '');
            const mode = s.mode === 'en_ligne' ? '<i class="fas fa-video me-1"></i>En ligne' : '<i class="fas fa-map-marker-alt me-1"></i>Présentiel';
            return `
                <div class="slot-row ${s.active ? '' : 'inactive'}" data-slot-id="${s.id}">
                    <div class="slot-date"><div class="d">${s.day}</div><div class="m">${s.month}</div></div>
                    <div class="slot-meta">
                        <div class="time">${s.start} – ${s.end} <span class="text-white-50 fw-normal">· ${s.date_label}</span></div>
                        <div class="sub">${mode}${s.lieu ? ' · ' + esc(s.lieu) : ''}${s.active ? '' : ' · <span class="text-warning">désactivé</span>'}</div>
                    </div>
                    <span class="occ-badge ${occ}">${s.booked}/${s.capacity} réservé(s)</span>
                    ${s.active && !s.started && s.booked < s.capacity ? `<button type="button" class="btn-add-rdv" data-book="${s.id}" title="Créer un RDV sur ce créneau"><i class="fas fa-calendar-plus"></i></button>` : ''}
                    <button type="button" class="btn-del-slot" data-del="${s.id}" data-booked="${s.booked}" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>`;
        }).join('');
    }

    const STATUS_LABELS = { pending: 'En attente', confirmed: 'Confirmé', cancelled: 'Annulé', completed: 'Terminé' };
    const FMT_CLASSES = {
        'design graphique': 'fmt-dg',
        'community management': 'fmt-cm',
        'design graphique & community manager': 'fmt-dgcm',
        'gestion informatique': 'fmt-gi',
        'intelligence artificielle': 'fmt-ia',
    };
    const FMT_ICONS = {
        'design graphique': 'fa-palette',
        'community management': 'fa-mobile-alt',
        'design graphique & community manager': 'fa-layer-group',
        'gestion informatique': 'fa-laptop-code',
        'intelligence artificielle': 'fa-robot',
    };

    function fmtChip(formation) {
        if (!formation) return '';
        const key = formation.toLowerCase();
        const cls = FMT_CLASSES[key] || 'fmt-other';
        const icon = FMT_ICONS[key] || 'fa-graduation-cap';
        return `<span class="fmt-chip ${cls}"><i class="fas ${icon}"></i>${esc(formation)}</span>`;
    }

    function avatarHtml(a) {
        if (a.student_photo) {
            return `<img src="${esc(a.student_photo)}" class="stu-avatar" alt="" loading="lazy"
                        onerror="this.outerHTML=initialsAvatar('${esc(a.student_name)}')">`;
        }
        return initialsAvatar(a.student_name);
    }

    function initialsAvatar(name) {
        const parts = String(name || '?').trim().split(/\s+/);
        const ini = ((parts[0] || '?')[0] + (parts.length > 1 ? parts[parts.length - 1][0] : '')).toUpperCase();
        return `<div class="stu-avatar-ph">${esc(ini)}</div>`;
    }

    function appointmentMatches(a) {
        if (filterState.status && a.status !== filterState.status) return false;
        if (filterState.period === 'upcoming' && a.slot_past) return false;
        if (filterState.period === 'past' && !a.slot_past) return false;
        if (filterState.formation && (a.formation || '') !== filterState.formation) return false;
        if (filterState.q) {
            const hay = (a.student_name + ' ' + a.student_email + ' ' + a.motif + ' ' + (a.message || '') + ' ' + (a.formation || '')).toLowerCase();
            if (!hay.includes(filterState.q)) return false;
        }
        return true;
    }

    function actionButtons(a, groupSize) {
        const label = esc(`${a.student_name} — ${a.slot_date} ${a.slot_time}`);
        const edit = `<button type="button" class="act-btn act-edit" title="Modifier pour cet étudiant" data-edit="${a.id}"><i class="fas fa-pen"></i></button>`;
        const remove = groupSize > 1
            ? `<button type="button" class="act-btn act-delete" title="Retirer cet étudiant uniquement" aria-label="Retirer cet étudiant uniquement" data-del-apt="${a.id}" data-scope="participant"><i class="fas fa-user-minus"></i></button> `
            : '';
        const del = remove + `<button type="button" class="act-btn act-delete" title="Supprimer le rendez-vous pour tous les participants" aria-label="Supprimer le rendez-vous pour tous les participants" data-del-apt="${a.id}" data-scope="group"><i class="fas fa-trash"></i></button>`;
        const btn = (status, cls, icon, title) =>
            `<button type="button" class="act-btn ${cls}" title="${title}"
                data-rdv="${a.id}" data-status="${status}" data-label="${label}"><i class="fas ${icon}"></i></button>`;
        if (a.status === 'pending') {
            return btn('confirmed', 'act-confirm', 'fa-check', 'Confirmer') + ' ' + edit + ' ' + btn('cancelled', 'act-cancel', 'fa-times', 'Refuser / Annuler') + ' ' + del;
        }
        if (a.status === 'confirmed') {
            return btn('completed', 'act-done', 'fa-check-double', 'Marquer terminé') + ' ' + edit + ' ' + btn('cancelled', 'act-cancel', 'fa-times', 'Annuler') + ' ' + del;
        }
        return del;
    }

    function renderAppointments() {
        const tbody = document.getElementById('rdvTbody');
        const empty = document.getElementById('rdvEmpty');
        const rows = appointments.filter(appointmentMatches);
        const groupCounts = new Map();
        appointments.forEach(a => {
            if (a.group_id) groupCounts.set(a.group_id, (groupCounts.get(a.group_id) || 0) + 1);
        });

        empty.classList.toggle('d-none', rows.length > 0);

        tbody.innerHTML = rows.map(a => `
            <tr data-rdv-row="${a.id}">
                <td>
                    <div class="stu-cell">
                        ${avatarHtml(a)}
                        <div style="min-width:0;">
                            <div class="stu-name">${esc(a.student_name)}</div>
                            <div class="stu-mail">${esc(a.student_email)}</div>
                            ${fmtChip(a.formation)}
                        </div>
                    </div>
                </td>
                <td>
                    <div class="text-white fw-bold">${a.slot_date}</div>
                    <div class="stu-mail">${a.slot_time} · ${a.slot_mode === 'en_ligne' ? 'En ligne' : 'Présentiel'}</div>
                    ${a.meet_link ? `<a class="meet-link" href="${esc(a.meet_link)}" target="_blank"><i class="fas fa-video me-1"></i>lien réunion</a>` : ''}
                </td>
                <td>
                    <div class="stu-name" style="font-weight:600;">${esc(a.motif)}</div>
                    <div class="stu-mail">${a.slot_private ? 'Rendez-vous direct privé' : 'Sur disponibilité'}${a.group_id ? ' · Groupe ' + esc(a.group_id.slice(0, 8)) + ' · ' + groupCounts.get(a.group_id) + ' participant(s)' : ''}</div>
                    ${a.message ? `<div class="rdv-msg" title="${esc(a.message)}">${esc(a.message)}</div>` : ''}
                    ${a.admin_note ? `<div class="stu-mail" style="color:#a78bfa;"><i class="fas fa-reply me-1"></i>${esc(a.admin_note.length > 40 ? a.admin_note.slice(0, 40) + '…' : a.admin_note)}</div>` : ''}
                </td>
                <td><span class="stb stb-${a.status}">${STATUS_LABELS[a.status] || a.status}</span></td>
                <td class="text-end">${actionButtons(a, groupCounts.get(a.group_id) || 1)}</td>
            </tr>`).join('');
    }

    function renderStats() {
        const today = new Date().toISOString().slice(0, 10);
        document.getElementById('kpiPending').textContent = appointments.filter(a => a.status === 'pending').length;
        document.getElementById('kpiConfirmed').textContent = appointments.filter(a => a.status === 'confirmed' && a.slot_date_raw >= today).length;
        document.getElementById('kpiSlots').textContent = slots.filter(s => s.active && !s.started && s.booked < s.capacity).length;
        document.getElementById('kpiTotal').textContent = appointments.length;
    }

    function renderAll() { renderSlots(); renderFormationPills(); renderAppointments(); renderStats(); }

    /* ─── Filtres (instantanés, sans rechargement) ─── */
    document.querySelectorAll('.rdv-pill[data-status]').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.rdv-pill[data-status]').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            filterState.status = pill.dataset.status;
            renderAppointments();
        });
    });
    document.querySelectorAll('.rdv-pill[data-period]').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.rdv-pill[data-period]').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');
            filterState.period = pill.dataset.period;
            renderAppointments();
        });
    });
    document.getElementById('rdvSearch').addEventListener('input', function () {
        filterState.q = this.value.toLowerCase().trim();
        renderAppointments();
    });

    // Filtre formations (pills générées depuis les données)
    function renderFormationPills() {
        const box = document.getElementById('formationFilters');
        const formations = [...new Set(appointments.map(a => a.formation).filter(Boolean))].sort();
        box.innerHTML = '<span class="rdv-pill ' + (!filterState.formation ? 'active' : '') + '" data-formation="">Toutes formations</span>'
            + formations.map(f => `<span class="rdv-pill ${filterState.formation === f ? 'active' : ''}" data-formation="${esc(f)}">${esc(f)}</span>`).join('');
    }
    document.getElementById('formationFilters').addEventListener('click', function (e) {
        const pill = e.target.closest('[data-formation]');
        if (!pill) return;
        filterState.formation = pill.dataset.formation;
        this.querySelectorAll('.rdv-pill').forEach(p => p.classList.toggle('active', p === pill));
        renderAppointments();
    });

    /* ─── Création de créneau (AJAX) ─── */
    document.getElementById('slotForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('slotSubmitBtn');
        const fd = new FormData(this);
        const body = Object.fromEntries(fd.entries());

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner spin me-1"></i> Création…';
        try {
            const data = await api(URLS.storeSlot, 'POST', body);
            slots = data.slots;
            renderAll();
            toast(data.message || 'Créneau créé.');
            this.querySelector('[name="lieu"]').value = '';
        } catch (err) {
            toast(err.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-plus me-1"></i> Créer le(s) créneau(x)';
        }
    });

    /* ─── Suppression de créneau (AJAX) ─── */
    document.getElementById('slotsList').addEventListener('click', async function (e) {
        const btn = e.target.closest('[data-del]');
        if (!btn) return;

        const booked = parseInt(btn.dataset.booked || '0', 10);
        const msg = booked > 0
            ? 'Des rendez-vous sont rattachés — le créneau sera désactivé. Continuer ?'
            : 'Supprimer ce créneau ?';
        if (!confirm(msg)) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner spin"></i>';
        try {
            const data = await api(URLS.deleteSlot(btn.dataset.del), 'DELETE');
            slots = data.slots;
            renderAll();
            toast(data.message || 'Créneau supprimé.');
        } catch (err) {
            toast(err.message, 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash"></i>';
        }
    });

    /* ─── Modal statut (AJAX) ─── */
    const statusModalEl = document.getElementById('statusModal');
    const statusModal = new bootstrap.Modal(statusModalEl);
    let currentRdvId = null;

    document.getElementById('rdvTbody').addEventListener('click', function (e) {
        const btn = e.target.closest('[data-rdv]');
        if (!btn) return;
        currentRdvId = btn.dataset.rdv;
        const status = btn.dataset.status;

        document.getElementById('statusInput').value = status;
        document.getElementById('statusModalLabel').textContent = btn.dataset.label;
        document.getElementById('meetLinkInput').value = '';
        document.getElementById('adminNoteInput').value = '';

        const titles = { confirmed: 'Confirmer le rendez-vous', cancelled: 'Annuler le rendez-vous', completed: 'Marquer comme terminé' };
        document.getElementById('statusModalTitle').textContent = titles[status] || 'Action';
        document.getElementById('meetLinkField').style.display = status === 'confirmed' ? 'block' : 'none';

        const colors = { confirmed: '#22c55e', cancelled: '#ef4444', completed: '#3b82f6' };
        document.getElementById('statusSubmitBtn').style.background = colors[status] || '#8b5cf6';

        statusModal.show();
    });

    // Suppression définitive d'un rendez-vous
    document.getElementById('rdvTbody').addEventListener('click', async function (e) {
        const btn = e.target.closest('[data-del-apt]');
        if (!btn || btn.disabled) return;
        const id = btn.dataset.delApt;
        const appointment = appointments.find(a => a.id == id);
        if (!appointment) return;
        const scope = btn.dataset.scope;
        const affected = scope === 'group' && appointment.group_id
            ? appointments.filter(a => a.group_id === appointment.group_id)
            : [appointment];
        const question = scope === 'group'
            ? `Supprimer définitivement ce rendez-vous chez les ${affected.length} participant(s), y compris ceux masqués par les filtres ?`
            : 'Retirer uniquement cet étudiant du rendez-vous ?';
        const names = affected.map(a => a.student_name).join(', ');
        if (!confirm(question + '\n' + names + '\nLes participants ayant un rendez-vous actif recevront une notification d’annulation.')) return;

        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner spin"></i>';
        try {
            const data = await api(URLS.destroy(id), 'DELETE', { scope });
            const deletedIds = new Set(data.deleted_ids.map(Number));
            appointments = appointments.filter(a => !deletedIds.has(Number(a.id)));
            if (data.slots) slots = data.slots;
            renderAll();
            toast(data.message || 'Rendez-vous supprimé.');
        } catch (err) {
            toast(err.message, 'error');
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    });

    document.getElementById('statusForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('statusSubmitBtn');
        const status = document.getElementById('statusInput').value;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner spin me-1"></i> En cours…';
        try {
            const data = await api(URLS.status(currentRdvId), 'POST', {
                status,
                meet_link: document.getElementById('meetLinkInput').value || null,
                admin_note: document.getElementById('adminNoteInput').value || null,
            });

            const idx = appointments.findIndex(a => a.id == currentRdvId);
            if (idx !== -1) appointments[idx] = data.appointment;

            // Mettre à jour le compteur d'occupation du créneau
            const slot = slots.find(s => s.id === data.appointment.slot_id);
            if (slot) {
                slot.booked = appointments.filter(a =>
                    a.slot_id === slot.id && ['pending', 'confirmed'].includes(a.status)
                ).length;
            }

            renderAll();
            statusModal.hide();
            toast(data.message || 'Statut mis à jour.');

            const row = document.querySelector(`tr[data-rdv-row="${currentRdvId}"]`);
            if (row) row.classList.add('row-flash');
        } catch (err) {
            toast(err.message, 'error');
        } finally {
            btn.disabled = false;
            btn.textContent = 'Valider';
        }
    });

    /* ─── Création de RDV par l'admin ─── */
    const bookModalEl = document.getElementById('bookModal');
    const bookModal = new bootstrap.Modal(bookModalEl);
    const bookSlotSelect = document.getElementById('bookSlotSelect');
    const bookSlotInfo = document.getElementById('bookSlotInfo');
    const bookGroups = document.getElementById('bookGroups');
    const bookCount = document.getElementById('bookCount');
    const bookPlaces = document.getElementById('bookPlaces');
    const bookSubmitBtn = document.getElementById('bookSubmitBtn');
    const bookType = document.getElementById('bookType');
    const bookError = document.getElementById('bookError');
    let bookingBusy = false;

    function refreshBookingType() {
        const direct = bookType.value === 'direct';
        document.getElementById('bookExistingFields').classList.toggle('d-none', direct);
        document.getElementById('bookDirectFields').classList.toggle('d-none', !direct);
        document.getElementById('bookDirectFields').disabled = !direct;
        bookSlotSelect.disabled = direct;
        bookSlotSelect.required = !direct;
        bookError.classList.add('d-none');
        refreshBook();
    }

    bookType.addEventListener('change', refreshBookingType);
    document.getElementById('bookMode').addEventListener('change', refreshBook);

    // Grouper les étudiants par formation
    const studentsByProgram = {};
    students.forEach(s => {
        (studentsByProgram[s.program] = studentsByProgram[s.program] || []).push(s);
    });

    // Construire le picker une seule fois
    function buildBookGroups() {
        bookGroups.innerHTML = Object.keys(studentsByProgram).sort().map(prog => `
            <div class="bk-group" data-program="${esc(prog)}">
                <div class="bk-group-head">
                    <div class="d-flex align-items-center gap-2">
                        <input type="checkbox" class="bk-select-all" title="Tout sélectionner" style="accent-color:#8b5cf6;">
                        <span class="bk-group-title">${esc(prog)}</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="bk-group-count"><span class="bk-sel">0</span>/${studentsByProgram[prog].length}</span>
                        <i class="fas fa-chevron-down bk-caret"></i>
                    </div>
                </div>
                <div class="bk-group-body">
                    ${studentsByProgram[prog].map(s => `
                        <label class="bk-row" data-search="${esc((s.name + ' ' + s.email).toLowerCase())}">
                            <input type="checkbox" class="bk-cb" value="${s.id}">
                            <span><span class="bk-name d-block">${esc(s.name)}</span>
                            <span class="bk-mail d-block">${esc(s.email)}</span></span>
                        </label>`).join('')}
                </div>
            </div>`).join('');

        bookGroups.querySelectorAll('.bk-group').forEach(group => {
            const head = group.querySelector('.bk-group-head');
            const selectAll = group.querySelector('.bk-select-all');
            const selCount = group.querySelector('.bk-sel');
            const cbs = group.querySelectorAll('.bk-cb');

            head.addEventListener('click', e => {
                if (e.target === selectAll) return;
                group.classList.toggle('open');
            });
            selectAll.addEventListener('change', () => {
                cbs.forEach(cb => {
                    if (!cb.closest('.bk-row').classList.contains('bk-hidden')) cb.checked = selectAll.checked;
                });
                refreshBook();
            });
            cbs.forEach(cb => cb.addEventListener('change', refreshBook));

            function refreshBookGroup() {
                const n = [...cbs].filter(c => c.checked).length;
                selCount.textContent = n;
                selectAll.checked = n === cbs.length && n > 0;
                selectAll.indeterminate = n > 0 && n < cbs.length;
            }
            group._refresh = refreshBookGroup;
        });
    }

    function refreshBook() {
        bookGroups.querySelectorAll('.bk-group').forEach(g => g._refresh && g._refresh());
        const n = bookGroups.querySelectorAll('.bk-cb:checked').length;
        bookCount.textContent = n;

        const direct = bookType.value === 'direct';
        const slot = slots.find(s => s.id == bookSlotSelect.value);
        const remaining = slot ? slot.capacity - slot.booked : 0;
        const onsite = (direct ? document.getElementById('bookMode').value : slot?.mode) === 'presentiel';
        document.getElementById('bookMeetField').classList.toggle('d-none', onsite);
        document.getElementById('bookMeetLink').disabled = onsite;
        bookPlaces.textContent = direct ? 'Rendez-vous privé' : (slot ? remaining + ' place(s) restante(s)' : 'Choisissez un créneau');
        bookPlaces.style.color = (!direct && slot && n > remaining) ? '#f87171' : '#fbbf24';
        bookSubmitBtn.disabled = bookingBusy || n === 0 || (!direct && (!slot || n > remaining));
    }

    function refreshBookSlots(preselect) {
        const open = slots.filter(s => s.active && !s.started && s.booked < s.capacity);
        bookSlotSelect.innerHTML = open.length
            ? open.map(s => `<option value="${s.id}">${s.date_full} · ${s.start}–${s.end} · ${s.mode === 'en_ligne' ? 'En ligne' : 'Présentiel'} · ${s.capacity - s.booked} place(s)</option>`).join('')
            : '<option value="">— Aucun créneau disponible —</option>';
        if (preselect && open.some(s => s.id == preselect)) bookSlotSelect.value = preselect;

        const slot = slots.find(s => s.id == bookSlotSelect.value);
        bookSlotInfo.style.display = slot ? 'flex' : 'none';
        if (slot) {
            bookSlotInfo.innerHTML = `<i class="far fa-calendar-check"></i>
                ${slot.date_full} · ${slot.start}–${slot.end} · ${slot.mode === 'en_ligne' ? '🎥 En ligne' : '📍 Présentiel'}${slot.lieu ? ' · ' + esc(slot.lieu) : ''}`;
        }
        refreshBook();
    }

    function openBookModal(preselectSlotId) {
        bookType.value = preselectSlotId ? 'existing' : 'direct';
        refreshBookSlots(preselectSlotId);
        refreshBookingType();
        document.getElementById('bookSearch').value = '';
        bookGroups.querySelectorAll('.bk-hidden').forEach(el => el.classList.remove('bk-hidden'));
        bookModal.show();
    }

    bookSlotSelect.addEventListener('change', () => refreshBookSlots(bookSlotSelect.value));

    document.getElementById('bookSearch').addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        bookGroups.querySelectorAll('.bk-group').forEach(group => {
            let visible = 0;
            group.querySelectorAll('.bk-row').forEach(row => {
                const match = !q || row.dataset.search.includes(q);
                row.classList.toggle('bk-hidden', !match);
                if (match) visible++;
            });
            group.classList.toggle('bk-hidden', visible === 0);
            if (q && visible > 0) group.classList.add('open');
        });
    });

    // Ouvrir via le bouton global ou le bouton d'un créneau
    document.getElementById('newRdvBtn').addEventListener('click', () => openBookModal(null));
    document.getElementById('slotsList').addEventListener('click', function (e) {
        const btn = e.target.closest('[data-book]');
        if (btn) openBookModal(btn.dataset.book);
    });

    document.getElementById('bookForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const studentIds = [...bookGroups.querySelectorAll('.bk-cb:checked')].map(cb => parseInt(cb.value, 10));
        if (!studentIds.length || bookingBusy) return;
        const direct = bookType.value === 'direct';
        bookingBusy = true;
        bookError.classList.add('d-none');
        bookSubmitBtn.disabled = true;
        bookSubmitBtn.innerHTML = '<i class="fas fa-spinner spin me-1"></i> Création…';
        try {
            const data = await api(URLS.storeAppointment, 'POST', {
                booking_type: bookType.value,
                ...(direct ? {
                    date: document.getElementById('bookDate').value,
                    start_time: document.getElementById('bookStart').value,
                    end_time: document.getElementById('bookEnd').value,
                    mode: document.getElementById('bookMode').value,
                    lieu: document.getElementById('bookLocation').value || null,
                } : { slot_id: parseInt(bookSlotSelect.value, 10) }),
                student_ids: studentIds,
                motif: document.getElementById('bookMotif').value,
                message: document.getElementById('bookMessage').value || null,
                meet_link: document.getElementById('bookMeetLink').value || null,
            });

            slots = data.slots;
            appointments = data.appointments;
            renderAll();

            bookModal.hide();
            bookGroups.querySelectorAll('.bk-cb:checked').forEach(cb => cb.checked = false);
            document.getElementById('bookMessage').value = '';
            document.getElementById('bookMeetLink').value = '';
            refreshBook();
            toast(data.message || 'Rendez-vous créé(s).');
        } catch (err) {
            bookError.textContent = err.message;
            bookError.classList.remove('d-none');
            toast(err.message, 'error');
        } finally {
            bookingBusy = false;
            refreshBook();
            bookSubmitBtn.innerHTML = '<i class="fas fa-check me-1"></i>Créer et confirmer';
        }
    });

    /* ─── Modification / replanification ─── */
    const editModalEl = document.getElementById('editModal');
    const editModal = new bootstrap.Modal(editModalEl);
    const editSlotSelect = document.getElementById('editSlotSelect');

    document.getElementById('rdvTbody').addEventListener('click', function (e) {
        const btn = e.target.closest('[data-edit]');
        if (!btn) return;
        const a = appointments.find(x => x.id == btn.dataset.edit);
        if (!a) return;

        document.getElementById('editRdvId').value = a.id;
        document.getElementById('editModalLabel').textContent = `${a.student_name} — statut actuel : ${STATUS_LABELS[a.status] || a.status}. Modification pour cet étudiant uniquement.${a.group_id ? ' Un changement de créneau le retire du groupe.' : ''}`;
        document.getElementById('editMotif').value = a.motif;
        document.getElementById('editMessage').value = a.message || '';
        document.getElementById('editMeetLink').value = a.meet_link || '';
        document.getElementById('editNote').value = a.admin_note || '';

        // Créneaux : ouverts + le créneau actuel du RDV (même plein/passé)
        const currentSlot = slots.find(s => s.id === a.slot_id);
        const options = slots
            .filter(s => s.id === a.slot_id || (s.active && !s.started && s.booked < s.capacity))
            .map(s => `<option value="${s.id}">${s.date_full} · ${s.start}–${s.end} · ${s.mode === 'en_ligne' ? 'En ligne' : 'Présentiel'} · ${s.capacity - s.booked} place(s)${s.id === a.slot_id ? ' — actuel' : ''}</option>`);

        if (!currentSlot) {
            options.unshift(`<option value="${a.slot_id}">${a.slot_date} · ${a.slot_time} — actuel</option>`);
        }
        editSlotSelect.innerHTML = options.join('');
        editSlotSelect.value = a.slot_id;

        editModal.show();
    });

    document.getElementById('editForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const btn = document.getElementById('editSubmitBtn');
        const rdvId = document.getElementById('editRdvId').value;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner spin me-1"></i> Enregistrement…';
        try {
            const data = await api(URLS.update(rdvId), 'POST', {
                slot_id: parseInt(editSlotSelect.value, 10),
                motif: document.getElementById('editMotif').value,
                message: document.getElementById('editMessage').value || null,
                meet_link: document.getElementById('editMeetLink').value || null,
                admin_note: document.getElementById('editNote').value || null,
            });

            const idx = appointments.findIndex(a => a.id == rdvId);
            if (idx !== -1) appointments[idx] = data.appointment;
            slots = data.slots;

            renderAll();
            editModal.hide();
            toast(data.message || 'Rendez-vous modifié.');

            const row = document.querySelector(`tr[data-rdv-row="${rdvId}"]`);
            if (row) row.classList.add('row-flash');
        } catch (err) {
            toast(err.message, 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save me-1"></i>Enregistrer';
        }
    });

    buildBookGroups();
    renderAll();
});
</script>
@endpush
