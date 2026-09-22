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
    .rdv-table { color: rgba(255,255,255,0.85); font-size: 0.85rem; }
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
                <div class="rdv-card-title"><i class="fas fa-clipboard-list"></i> Demandes de rendez-vous</div>

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
    };

    let slots = @json($slotsJson);
    let appointments = @json($appointmentsJson);
    const filterState = { status: '', period: '', q: '' };

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
                    <button type="button" class="btn-del-slot" data-del="${s.id}" data-booked="${s.booked}" title="Supprimer"><i class="fas fa-trash"></i></button>
                </div>`;
        }).join('');
    }

    const STATUS_LABELS = { pending: 'En attente', confirmed: 'Confirmé', cancelled: 'Annulé', completed: 'Terminé' };

    function appointmentMatches(a) {
        if (filterState.status && a.status !== filterState.status) return false;
        if (filterState.period === 'upcoming' && a.slot_past) return false;
        if (filterState.period === 'past' && !a.slot_past) return false;
        if (filterState.q) {
            const hay = (a.student_name + ' ' + a.student_email + ' ' + a.motif + ' ' + (a.message || '')).toLowerCase();
            if (!hay.includes(filterState.q)) return false;
        }
        return true;
    }

    function actionButtons(a) {
        const label = esc(`${a.student_name} — ${a.slot_date} ${a.slot_time}`);
        const btn = (status, cls, icon, title) =>
            `<button type="button" class="act-btn ${cls}" title="${title}"
                data-rdv="${a.id}" data-status="${status}" data-label="${label}"><i class="fas ${icon}"></i></button>`;
        if (a.status === 'pending') {
            return btn('confirmed', 'act-confirm', 'fa-check', 'Confirmer') + ' ' + btn('cancelled', 'act-cancel', 'fa-times', 'Refuser / Annuler');
        }
        if (a.status === 'confirmed') {
            return btn('completed', 'act-done', 'fa-check-double', 'Marquer terminé') + ' ' + btn('cancelled', 'act-cancel', 'fa-times', 'Annuler');
        }
        return '<span class="stu-mail">—</span>';
    }

    function renderAppointments() {
        const tbody = document.getElementById('rdvTbody');
        const empty = document.getElementById('rdvEmpty');
        const rows = appointments.filter(appointmentMatches);

        empty.classList.toggle('d-none', rows.length > 0);

        tbody.innerHTML = rows.map(a => `
            <tr data-rdv-row="${a.id}">
                <td>
                    <div class="stu-name">${esc(a.student_name)}</div>
                    <div class="stu-mail">${esc(a.student_email)}${a.formation ? ' · ' + esc(a.formation) : ''}</div>
                </td>
                <td>
                    <div class="text-white fw-bold">${a.slot_date}</div>
                    <div class="stu-mail">${a.slot_time} · ${a.slot_mode === 'en_ligne' ? 'En ligne' : 'Présentiel'}</div>
                    ${a.meet_link ? `<a class="meet-link" href="${esc(a.meet_link)}" target="_blank"><i class="fas fa-video me-1"></i>lien réunion</a>` : ''}
                </td>
                <td>
                    <div class="stu-name" style="font-weight:600;">${esc(a.motif)}</div>
                    ${a.message ? `<div class="stu-mail" title="${esc(a.message)}">${esc(a.message.length > 45 ? a.message.slice(0, 45) + '…' : a.message)}</div>` : ''}
                    ${a.admin_note ? `<div class="stu-mail" style="color:#a78bfa;"><i class="fas fa-reply me-1"></i>${esc(a.admin_note.length > 40 ? a.admin_note.slice(0, 40) + '…' : a.admin_note)}</div>` : ''}
                </td>
                <td><span class="stb stb-${a.status}">${STATUS_LABELS[a.status] || a.status}</span></td>
                <td class="text-end">${actionButtons(a)}</td>
            </tr>`).join('');
    }

    function renderStats() {
        const today = new Date().toISOString().slice(0, 10);
        document.getElementById('kpiPending').textContent = appointments.filter(a => a.status === 'pending').length;
        document.getElementById('kpiConfirmed').textContent = appointments.filter(a => a.status === 'confirmed' && a.slot_date_raw >= today).length;
        document.getElementById('kpiSlots').textContent = slots.filter(s => s.active && s.booked < s.capacity).length;
        document.getElementById('kpiTotal').textContent = appointments.length;
    }

    function renderAll() { renderSlots(); renderAppointments(); renderStats(); }

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

    renderAll();
});
</script>
@endpush
