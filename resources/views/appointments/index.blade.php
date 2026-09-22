@extends('layouts.ki-admin')

@section('title', 'Mes Rendez-vous - EVC')
@section('page-title', 'Rendez-vous')

@push('styles')
<style>
    .rdv-bg { position: fixed; inset: 0; z-index: -1; background: linear-gradient(180deg, #081126 0%, #0b1220 55%, #081126 100%); }
    .content-wrapper, .main-content { background: transparent !important; }

    /* ─── Hero ─── */
    .rdv-hero {
        border-radius: 20px; position: relative; overflow: hidden;
        background: linear-gradient(90deg, #0a1128 0%, #001f54 50%, #034078 100%);
        border: 1px solid rgba(255,255,255,0.10);
        padding: 2rem 1.5rem; margin-bottom: 1.5rem; text-align: center;
    }
    .rdv-hero::before {
        content: ''; position: absolute; inset: -2px;
        background: radial-gradient(circle at 20% 20%, rgba(249,115,22,0.22), transparent 45%),
                    radial-gradient(circle at 80% 20%, rgba(59,130,246,0.18), transparent 40%);
        pointer-events: none;
    }
    .rdv-hero > * { position: relative; z-index: 1; }
    .rdv-hero h1 { color: #fff; font-weight: 900; letter-spacing: -0.02em; font-size: 1.8rem; margin-bottom: 0.35rem; }
    .rdv-hero .lead { color: rgba(255,255,255,0.82); font-weight: 700; }

    /* ─── Sections ─── */
    .rdv-section-title {
        color: #fff; font-weight: 800; font-size: 1.05rem;
        display: flex; align-items: center; gap: 0.6rem; margin: 2rem 0 1rem;
    }
    .rdv-section-title i { color: #f97316; }
    .rdv-section-title .count-badge {
        background: rgba(249,115,22,0.15); color: #fb923c; border: 1px solid rgba(249,115,22,0.35);
        border-radius: 999px; padding: 0.1rem 0.6rem; font-size: 0.75rem;
    }

    /* ─── Date group ─── */
    .rdv-date-group { margin-bottom: 1.5rem; }
    .rdv-date-label {
        display: inline-flex; align-items: center; gap: 0.5rem;
        color: rgba(255,255,255,0.9); font-weight: 800; font-size: 0.92rem;
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.10);
        padding: 0.4rem 0.9rem; border-radius: 999px; margin-bottom: 0.75rem;
    }
    .rdv-date-label i { color: #60a5fa; }

    /* ─── Slot cards ─── */
    .slot-card {
        border-radius: 16px; background: rgba(15,23,42,0.55);
        border: 1px solid rgba(255,255,255,0.10); padding: 1.1rem 1.25rem;
        display: flex; align-items: center; gap: 1rem; height: 100%;
        transition: transform 0.15s ease, border-color 0.15s ease;
    }
    .slot-card:hover { transform: translateY(-2px); border-color: rgba(59,130,246,0.45); }
    .slot-time {
        min-width: 76px; text-align: center;
        background: rgba(37,99,235,0.15); border: 1px solid rgba(59,130,246,0.35);
        border-radius: 12px; padding: 0.55rem 0.5rem;
    }
    .slot-time .t1 { color: #fff; font-weight: 900; font-size: 1rem; line-height: 1.1; }
    .slot-time .t2 { color: #93c5fd; font-weight: 700; font-size: 0.72rem; }
    .slot-info { flex: 1; min-width: 0; }
    .slot-info .dur { color: rgba(255,255,255,0.9); font-weight: 800; font-size: 0.9rem; }
    .slot-info .meta { color: rgba(255,255,255,0.55); font-size: 0.8rem; display: flex; gap: 0.9rem; flex-wrap: wrap; margin-top: 0.15rem; }
    .slot-info .meta i { margin-right: 0.25rem; }
    .slot-book-btn {
        background: linear-gradient(135deg, #f97316, #ea580c); border: none; color: #fff;
        font-weight: 800; font-size: 0.82rem; border-radius: 999px; padding: 0.55rem 1.1rem;
        white-space: nowrap; transition: filter 0.15s ease;
    }
    .slot-book-btn:hover { filter: brightness(1.1); color: #fff; }
    .mode-chip {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.72rem; font-weight: 800; padding: 0.2rem 0.6rem; border-radius: 999px;
    }
    .mode-online { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.35); }
    .mode-onsite { background: rgba(139,92,246,0.15); color: #a78bfa; border: 1px solid rgba(139,92,246,0.35); }
    .places-left { color: #fbbf24; font-weight: 700; }

    /* ─── Appointment cards ─── */
    .rdv-card {
        border-radius: 16px; background: rgba(15,23,42,0.55);
        border: 1px solid rgba(255,255,255,0.10); padding: 1.1rem 1.25rem;
        display: flex; align-items: flex-start; gap: 1rem; height: 100%;
    }
    .rdv-date-box {
        min-width: 60px; text-align: center; border-radius: 12px; padding: 0.5rem;
        background: rgba(249,115,22,0.12); border: 1px solid rgba(249,115,22,0.3);
    }
    .rdv-date-box .d { color: #fff; font-weight: 900; font-size: 1.25rem; line-height: 1; }
    .rdv-date-box .m { color: #fdba74; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; }
    .rdv-body { flex: 1; min-width: 0; }
    .rdv-motif { color: #fff; font-weight: 800; font-size: 0.92rem; }
    .rdv-sub { color: rgba(255,255,255,0.6); font-size: 0.8rem; margin-top: 0.2rem; }
    .rdv-sub i { margin-right: 0.25rem; }
    .rdv-note { color: rgba(255,255,255,0.75); font-size: 0.8rem; background: rgba(255,255,255,0.05); border-left: 3px solid rgba(59,130,246,0.5); border-radius: 6px; padding: 0.45rem 0.7rem; margin-top: 0.5rem; }
    .status-badge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        font-size: 0.72rem; font-weight: 800; padding: 0.28rem 0.7rem; border-radius: 999px;
    }
    .st-pending { background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.4); }
    .st-confirmed { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.4); }
    .st-cancelled { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.35); }
    .st-completed { background: rgba(148,163,184,0.15); color: #94a3b8; border: 1px solid rgba(148,163,184,0.35); }
    .meet-btn {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff;
        font-weight: 800; font-size: 0.78rem; border-radius: 999px; padding: 0.45rem 0.95rem;
        text-decoration: none; margin-top: 0.5rem;
    }
    .meet-btn:hover { color: #fff; filter: brightness(1.1); }
    .cancel-btn {
        background: transparent; border: 1px solid rgba(239,68,68,0.4); color: #f87171;
        font-weight: 700; font-size: 0.75rem; border-radius: 999px; padding: 0.35rem 0.85rem;
    }
    .cancel-btn:hover { background: rgba(239,68,68,0.12); color: #f87171; }

    /* ─── Empty state ─── */
    .rdv-empty {
        text-align: center; padding: 2.5rem 1rem; color: rgba(255,255,255,0.55);
        background: rgba(15,23,42,0.4); border: 1px dashed rgba(255,255,255,0.12); border-radius: 16px;
    }
    .rdv-empty i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: 0.4; }

    /* ─── Modal ─── */
    .rdv-modal-content { background: #0f172a; border: 1px solid rgba(255,255,255,0.12); color: #fff; border-radius: 18px; }
    .rdv-modal-content .modal-header { border-bottom: 1px solid rgba(255,255,255,0.10); }
    .rdv-modal-content .modal-footer { border-top: 1px solid rgba(255,255,255,0.10); }
    .rdv-modal-content .btn-close { filter: invert(1); }
    .rdv-modal-content .form-control, .rdv-modal-content .form-select {
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: #fff;
    }
    .rdv-modal-content .form-control:focus, .rdv-modal-content .form-select:focus {
        background: rgba(255,255,255,0.08); border-color: #3b82f6; color: #fff; box-shadow: 0 0 0 3px rgba(59,130,246,0.2);
    }
    .rdv-modal-content .form-select option { background: #0f172a; }
    .rdv-modal-content .form-label { color: rgba(255,255,255,0.8); font-weight: 700; font-size: 0.85rem; }
    .rdv-modal-slot {
        background: rgba(37,99,235,0.12); border: 1px solid rgba(59,130,246,0.35);
        border-radius: 12px; padding: 0.7rem 1rem; color: #bfdbfe; font-weight: 700; font-size: 0.88rem;
        display: flex; align-items: center; gap: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="rdv-bg"></div>

<div class="container-fluid py-3">

    <!-- Hero -->
    <div class="rdv-hero">
        <h1><i class="fas fa-calendar-check me-2"></i>Assistance &amp; Rendez-vous</h1>
        <p class="lead mb-0">Besoin d'une aide particulière ? Réservez un créneau avec l'équipe EVC selon les disponibilités des formateurs.</p>
    </div>

    <!-- Flash messages -->
    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center gap-2" style="border-radius: 12px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger d-flex align-items-center gap-2" style="border-radius: 12px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- ═══ Mes rendez-vous à venir ═══ -->
    <div class="rdv-section-title">
        <i class="fas fa-clock"></i> Mes rendez-vous
        <span class="count-badge">{{ $upcomingAppointments->count() }}</span>
    </div>

    @if($upcomingAppointments->isEmpty())
        <div class="rdv-empty">
            <i class="fas fa-calendar-day"></i>
            Aucun rendez-vous à venir. Réservez un créneau ci-dessous.
        </div>
    @else
        <div class="row g-3">
            @foreach($upcomingAppointments as $rdv)
                @php $slot = $rdv->slot; @endphp
                <div class="col-md-6 col-xl-4">
                    <div class="rdv-card">
                        <div class="rdv-date-box">
                            <div class="d">{{ $slot->date->format('d') }}</div>
                            <div class="m">{{ $slot->date->translatedFormat('M') }}</div>
                        </div>
                        <div class="rdv-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="rdv-motif">{{ $rdv->motif }}</div>
                                <span class="status-badge st-{{ $rdv->status }}">
                                    @if($rdv->status === 'pending') <i class="fas fa-hourglass-half"></i> En attente
                                    @elseif($rdv->status === 'confirmed') <i class="fas fa-check"></i> Confirmé
                                    @endif
                                </span>
                            </div>
                            <div class="rdv-sub">
                                <i class="far fa-clock"></i>{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                &nbsp;·&nbsp;
                                @if($slot->mode === 'en_ligne')
                                    <i class="fas fa-video"></i>En ligne
                                @else
                                    <i class="fas fa-map-marker-alt"></i>Présentiel
                                @endif
                            </div>
                            @if($rdv->status === 'confirmed' && $rdv->meet_link && $slot->mode === 'en_ligne')
                                <a href="{{ $rdv->meet_link }}" target="_blank" class="meet-btn"><i class="fas fa-video"></i> Rejoindre la réunion</a>
                            @endif
                            @if($rdv->admin_note)
                                <div class="rdv-note"><i class="fas fa-info-circle me-1"></i>{{ $rdv->admin_note }}</div>
                            @endif
                            @if($rdv->isCancellable())
                                <form method="POST" action="{{ route('student.appointments.cancel', $rdv->id) }}" class="mt-2"
                                      onsubmit="return confirm('Annuler ce rendez-vous ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="cancel-btn"><i class="fas fa-times me-1"></i>Annuler</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- ═══ Créneaux disponibles ═══ -->
    <div class="rdv-section-title">
        <i class="fas fa-calendar-alt"></i> Créneaux disponibles
        <span class="count-badge">{{ $slotsByDate->flatten()->count() }}</span>
    </div>

    @if($slotsByDate->isEmpty())
        <div class="rdv-empty">
            <i class="fas fa-calendar-times"></i>
            Aucun créneau disponible pour le moment. Revenez bientôt !
        </div>
    @else
        @foreach($slotsByDate as $dateStr => $slots)
            <div class="rdv-date-group">
                <div class="rdv-date-label">
                    <i class="far fa-calendar"></i>
                    {{ \Carbon\Carbon::parse($dateStr)->translatedFormat('l j F Y') }}
                </div>
                <div class="row g-3">
                    @foreach($slots as $slot)
                        <div class="col-md-6 col-xl-4">
                            <div class="slot-card">
                                <div class="slot-time">
                                    <div class="t1">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}</div>
                                    <div class="t2">{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</div>
                                </div>
                                <div class="slot-info">
                                    <div class="dur">{{ $slot->durationMinutes() }} min</div>
                                    <div class="meta">
                                        @if($slot->mode === 'en_ligne')
                                            <span class="mode-chip mode-online"><i class="fas fa-video"></i>En ligne</span>
                                        @else
                                            <span class="mode-chip mode-onsite"><i class="fas fa-map-marker-alt"></i>Présentiel</span>
                                        @endif
                                        <span class="places-left"><i class="fas fa-user"></i>{{ $slot->capacity - $slot->booked_count }} place(s)</span>
                                    </div>
                                </div>
                                <button type="button" class="slot-book-btn"
                                        data-bs-toggle="modal" data-bs-target="#bookModal"
                                        data-slot-id="{{ $slot->id }}"
                                        data-slot-label="{{ $slot->date->translatedFormat('l j F') }} · {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}-{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }} · {{ $slot->mode === 'en_ligne' ? 'En ligne' : 'Présentiel' }}">
                                    Réserver
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif

    <!-- ═══ Historique ═══ -->
    @if($pastAppointments->isNotEmpty())
        <div class="rdv-section-title">
            <i class="fas fa-history"></i> Historique
            <span class="count-badge">{{ $pastAppointments->count() }}</span>
        </div>
        <div class="row g-3">
            @foreach($pastAppointments as $rdv)
                @php $slot = $rdv->slot; @endphp
                <div class="col-md-6 col-xl-4">
                    <div class="rdv-card" style="opacity: 0.75;">
                        <div class="rdv-date-box" style="background: rgba(148,163,184,0.1); border-color: rgba(148,163,184,0.3);">
                            <div class="d">{{ $slot ? $slot->date->format('d') : '—' }}</div>
                            <div class="m" style="color: #94a3b8;">{{ $slot ? $slot->date->translatedFormat('M') : '' }}</div>
                        </div>
                        <div class="rdv-body">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div class="rdv-motif">{{ $rdv->motif }}</div>
                                <span class="status-badge st-{{ $rdv->status }}">
                                    @if($rdv->status === 'completed') <i class="fas fa-check-double"></i> Terminé
                                    @elseif($rdv->status === 'cancelled') <i class="fas fa-ban"></i> Annulé
                                    @elseif($rdv->status === 'confirmed') <i class="fas fa-check"></i> Confirmé
                                    @else <i class="fas fa-hourglass-half"></i> En attente @endif
                                </span>
                            </div>
                            <div class="rdv-sub">
                                @if($slot)
                                    <i class="far fa-clock"></i>{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                    · {{ $slot->date->format('d/m/Y') }}
                                @endif
                            </div>
                            @if($rdv->admin_note)
                                <div class="rdv-note">{{ $rdv->admin_note }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

<!-- ═══ Modal réservation ═══ -->
<div class="modal fade" id="bookModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rdv-modal-content">
            <form method="POST" action="{{ route('student.appointments.store') }}">
                @csrf
                <input type="hidden" name="slot_id" id="bookSlotId">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus me-2" style="color:#f97316;"></i>Réserver un rendez-vous</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="rdv-modal-slot mb-3">
                        <i class="far fa-calendar-check"></i>
                        <span id="bookSlotLabel">Créneau</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motif du rendez-vous <span class="text-danger">*</span></label>
                        <select name="motif" class="form-select" required>
                            <option value="">— Choisir un motif —</option>
                            @foreach($motifs as $motif)
                                <option value="{{ $motif }}">{{ $motif }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Précisez votre besoin <small class="text-muted">(optionnel)</small></label>
                        <textarea name="message" class="form-control" rows="4" maxlength="2000"
                                  placeholder="Décrivez brièvement ce pour quoi vous avez besoin d'aide…"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="slot-book-btn"><i class="fas fa-paper-plane me-1"></i>Envoyer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const bookModal = document.getElementById('bookModal');
    bookModal.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        document.getElementById('bookSlotId').value = btn.dataset.slotId;
        document.getElementById('bookSlotLabel').textContent = btn.dataset.slotLabel;
    });
</script>
@endpush
