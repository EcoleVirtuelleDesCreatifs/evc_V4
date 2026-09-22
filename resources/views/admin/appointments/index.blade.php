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
        flex-wrap: wrap;
    }
    .slot-row.inactive { opacity: 0.45; }
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

    /* Filters */
    .rdv-filters { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .rdv-pill {
        padding: 0.4rem 0.9rem; border-radius: 999px; font-size: 0.78rem; font-weight: 700;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.15s;
    }
    .rdv-pill:hover { border-color: rgba(139,92,246,0.5); color: #fff; }
    .rdv-pill.active { background: #8b5cf6; border-color: #8b5cf6; color: #fff; }

    /* Table */
    .rdv-table { color: rgba(255,255,255,0.85); font-size: 0.85rem; }
    .rdv-table thead th {
        color: rgba(255,255,255,0.5); font-weight: 700; font-size: 0.72rem;
        text-transform: uppercase; letter-spacing: 0.05em;
        border-bottom: 1px solid rgba(255,255,255,0.08); padding: 0.6rem 0.75rem;
    }
    .rdv-table tbody td { border-bottom: 1px solid rgba(255,255,255,0.05); padding: 0.75rem; vertical-align: middle; }
    .rdv-table tbody tr:hover { background: rgba(139,92,246,0.05); }
    .stu-name { color: #fff; font-weight: 700; }
    .stu-mail { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
    .stb { font-size: 0.72rem; font-weight: 800; border-radius: 999px; padding: 0.25rem 0.65rem; white-space: nowrap; }
    .stb-pending { background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.4); }
    .stb-confirmed { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.4); }
    .stb-cancelled { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.35); }
    .stb-completed { background: rgba(148,163,184,0.15); color: #94a3b8; border: 1px solid rgba(148,163,184,0.35); }
    .act-btn {
        border: none; border-radius: 8px; padding: 0.35rem 0.6rem; font-size: 0.75rem; font-weight: 700;
    }
    .act-confirm { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.4); }
    .act-confirm:hover { background: rgba(34,197,94,0.28); color: #4ade80; }
    .act-cancel { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.35); }
    .act-cancel:hover { background: rgba(239,68,68,0.22); color: #f87171; }
    .act-done { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.4); }
    .act-done:hover { background: rgba(59,130,246,0.28); color: #93c5fd; }

    .rdv-modal .modal-content { background: #0f172a; border: 1px solid rgba(255,255,255,0.12); color: #fff; border-radius: 16px; }
    .rdv-modal .modal-header, .rdv-modal .modal-footer { border-color: rgba(255,255,255,0.1); }
    .rdv-modal .btn-close { filter: invert(1); }
    .rdv-modal .form-control, .rdv-modal .form-select {
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.15); color: #fff;
    }
    .rdv-modal .form-label { color: rgba(255,255,255,0.75); font-weight: 700; font-size: 0.8rem; }

    .rdv-empty { text-align: center; padding: 2.5rem 1rem; color: rgba(255,255,255,0.5); }
    .rdv-empty i { font-size: 2rem; display: block; margin-bottom: 0.75rem; opacity: 0.4; }
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

    <!-- Flash -->
    @if(session('success'))
        <div class="alert alert-success" style="border-radius: 12px;"><i class="fas fa-check-circle me-1"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="border-radius: 12px;"><i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" style="border-radius: 12px;">
            @foreach($errors->all() as $err)<div><i class="fas fa-exclamation-circle me-1"></i>{{ $err }}</div>@endforeach
        </div>
    @endif

    <!-- KPIs -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="rdv-kpi">
                <div class="rdv-kpi-icon" style="background: rgba(251,191,36,0.15); color: #fbbf24;"><i class="fas fa-hourglass-half"></i></div>
                <div><div class="rdv-kpi-val">{{ $stats['pending'] }}</div><div class="rdv-kpi-lbl">En attente</div></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rdv-kpi">
                <div class="rdv-kpi-icon" style="background: rgba(34,197,94,0.15); color: #4ade80;"><i class="fas fa-check-circle"></i></div>
                <div><div class="rdv-kpi-val">{{ $stats['confirmed_upcoming'] }}</div><div class="rdv-kpi-lbl">Confirmés à venir</div></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rdv-kpi">
                <div class="rdv-kpi-icon" style="background: rgba(59,130,246,0.15); color: #93c5fd;"><i class="fas fa-calendar-alt"></i></div>
                <div><div class="rdv-kpi-val">{{ $stats['slots_open'] }}</div><div class="rdv-kpi-lbl">Créneaux ouverts</div></div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="rdv-kpi">
                <div class="rdv-kpi-icon" style="background: rgba(139,92,246,0.15); color: #a78bfa;"><i class="fas fa-list"></i></div>
                <div><div class="rdv-kpi-val">{{ $stats['total'] }}</div><div class="rdv-kpi-lbl">Total RDV</div></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- ═══ Créneaux ═══ -->
        <div class="col-lg-5">
            <div class="rdv-card">
                <div class="rdv-card-title"><i class="fas fa-plus-circle"></i> Ajouter des disponibilités</div>
                <form method="POST" action="{{ route('admin.appointments.slots.store') }}" class="rdv-form">
                    @csrf
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ old('date', now()->toDateString()) }}" min="{{ now()->toDateString() }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Début</label>
                            <input type="time" name="start_time" class="form-control" value="{{ old('start_time', '09:00') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Fin</label>
                            <input type="time" name="end_time" class="form-control" value="{{ old('end_time', '09:30') }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Mode</label>
                            <select name="mode" class="form-select">
                                <option value="en_ligne" {{ old('mode') === 'presentiel' ? '' : 'selected' }}>🎥 En ligne</option>
                                <option value="presentiel" {{ old('mode') === 'presentiel' ? 'selected' : '' }}>📍 Présentiel</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Capacité</label>
                            <input type="number" name="capacity" class="form-control" value="{{ old('capacity', 1) }}" min="1" max="10" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Lieu / Lien <small class="text-white-50">(optionnel)</small></label>
                            <input type="text" name="lieu" class="form-control" value="{{ old('lieu') }}" placeholder="Ex : Campus EVC ou lien Meet">
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
                            <button type="submit" class="btn w-100 fw-bold" style="background: linear-gradient(135deg, #8b5cf6, #6366f1); color: #fff; border-radius: 10px;">
                                <i class="fas fa-plus me-1"></i> Créer le(s) créneau(x)
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="rdv-card">
                <div class="rdv-card-title"><i class="fas fa-calendar-alt"></i> Créneaux à venir ({{ $upcomingSlots->count() }})</div>
                @if($upcomingSlots->isEmpty())
                    <div class="rdv-empty"><i class="fas fa-calendar-times"></i>Aucun créneau programmé.</div>
                @else
                    @foreach($upcomingSlots as $slot)
                        <div class="slot-row {{ $slot->is_active ? '' : 'inactive' }}">
                            <div class="slot-date">
                                <div class="d">{{ $slot->date->format('d') }}</div>
                                <div class="m">{{ $slot->date->translatedFormat('M') }}</div>
                            </div>
                            <div class="slot-meta">
                                <div class="time">
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                    <span class="text-white-50 fw-normal">· {{ $slot->date->translatedFormat('D j M') }}</span>
                                </div>
                                <div class="sub">
                                    @if($slot->mode === 'en_ligne')<i class="fas fa-video me-1"></i>En ligne
                                    @else<i class="fas fa-map-marker-alt me-1"></i>Présentiel
                                    @endif
                                    @if($slot->lieu) · {{ $slot->lieu }}@endif
                                    @if(!$slot->is_active) · <span class="text-warning">désactivé</span>@endif
                                </div>
                            </div>
                            <span class="occ-badge {{ !$slot->is_active ? 'occ-off' : ($slot->booked_count >= $slot->capacity ? 'occ-full' : '') }}">
                                {{ $slot->booked_count }}/{{ $slot->capacity }} réservé(s)
                            </span>
                            <form method="POST" action="{{ route('admin.appointments.slots.destroy', $slot->id) }}"
                                  onsubmit="return confirm('{{ $slot->booked_count > 0 ? 'Des rendez-vous sont rattachés — le créneau sera désactivé. Continuer ?' : 'Supprimer ce créneau ?' }}');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del-slot" title="Supprimer"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- ═══ Demandes de RDV ═══ -->
        <div class="col-lg-7">
            <div class="rdv-card">
                <div class="rdv-card-title"><i class="fas fa-clipboard-list"></i> Demandes de rendez-vous</div>

                <div class="rdv-filters">
                    @php
                        $statuses = ['' => 'Tous', 'pending' => 'En attente', 'confirmed' => 'Confirmés', 'completed' => 'Terminés', 'cancelled' => 'Annulés'];
                    @endphp
                    @foreach($statuses as $key => $label)
                        <a href="{{ route('admin.appointments.index', array_merge($filters, ['status' => $key ?: null])) }}"
                           class="rdv-pill {{ ($filters['status'] ?? '') === $key ? 'active' : '' }}">{{ $label }}</a>
                    @endforeach
                    <span style="width:1px; background: rgba(255,255,255,0.12); margin: 0 0.25rem;"></span>
                    @foreach(['upcoming' => 'À venir', 'past' => 'Passés', '' => 'Toutes dates'] as $pKey => $pLabel)
                        <a href="{{ route('admin.appointments.index', array_merge($filters, ['period' => $pKey ?: null])) }}"
                           class="rdv-pill {{ ($filters['period'] ?? '') === $pKey ? 'active' : '' }}">{{ $pLabel }}</a>
                    @endforeach
                </div>

                @if($appointments->isEmpty())
                    <div class="rdv-empty"><i class="fas fa-inbox"></i>Aucune demande pour ces filtres.</div>
                @else
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
                            <tbody>
                                @foreach($appointments as $rdv)
                                    @php $slot = $rdv->slot; @endphp
                                    <tr>
                                        <td>
                                            <div class="stu-name">{{ $rdv->user->name ?? $rdv->student->full_name ?? '—' }}</div>
                                            <div class="stu-mail">{{ $rdv->user->email ?? '' }}</div>
                                        </td>
                                        <td>
                                            @if($slot)
                                                <div class="text-white fw-bold">{{ $slot->date->format('d/m/Y') }}</div>
                                                <div class="stu-mail">
                                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                    · {{ $slot->mode === 'en_ligne' ? 'En ligne' : 'Présentiel' }}
                                                </div>
                                            @else
                                                <span class="text-white-50">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="stu-name" style="font-weight:600;">{{ $rdv->motif }}</div>
                                            @if($rdv->message)
                                                <div class="stu-mail" title="{{ $rdv->message }}">{{ \Illuminate\Support\Str::limit($rdv->message, 45) }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="stb stb-{{ $rdv->status }}">
                                                @switch($rdv->status)
                                                    @case('pending') En attente @break
                                                    @case('confirmed') Confirmé @break
                                                    @case('cancelled') Annulé @break
                                                    @case('completed') Terminé @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-inline-flex gap-1">
                                                @if($rdv->status === 'pending')
                                                    <button type="button" class="act-btn act-confirm" title="Confirmer"
                                                            data-bs-toggle="modal" data-bs-target="#statusModal"
                                                            data-id="{{ $rdv->id }}" data-status="confirmed"
                                                            data-label="{{ ($rdv->user->name ?? 'Étudiant') . ' — ' . ($slot ? $slot->date->format('d/m/Y H:i') : '') }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button type="button" class="act-btn act-cancel" title="Refuser / Annuler"
                                                            data-bs-toggle="modal" data-bs-target="#statusModal"
                                                            data-id="{{ $rdv->id }}" data-status="cancelled"
                                                            data-label="{{ ($rdv->user->name ?? 'Étudiant') . ' — ' . ($slot ? $slot->date->format('d/m/Y H:i') : '') }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @elseif($rdv->status === 'confirmed')
                                                    <button type="button" class="act-btn act-done" title="Marquer terminé"
                                                            data-bs-toggle="modal" data-bs-target="#statusModal"
                                                            data-id="{{ $rdv->id }}" data-status="completed"
                                                            data-label="{{ ($rdv->user->name ?? 'Étudiant') . ' — ' . ($slot ? $slot->date->format('d/m/Y H:i') : '') }}">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                    <button type="button" class="act-btn act-cancel" title="Annuler"
                                                            data-bs-toggle="modal" data-bs-target="#statusModal"
                                                            data-id="{{ $rdv->id }}" data-status="cancelled"
                                                            data-label="{{ ($rdv->user->name ?? 'Étudiant') . ' — ' . ($slot ? $slot->date->format('d/m/Y H:i') : '') }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- ═══ Modal action statut ═══ -->
@push('modals')
<div class="modal fade rdv-modal" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="statusForm" action="">
                @csrf
                <input type="hidden" name="status" id="statusInput">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalTitle">Action</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-white-50 mb-3" id="statusModalLabel"></p>
                    <div class="mb-3" id="meetLinkField">
                        <label class="form-label">Lien de réunion <small class="text-white-50">(auto-généré si vide)</small></label>
                        <input type="url" name="meet_link" class="form-control" placeholder="https://meet.jit.si/...">
                    </div>
                    <div class="mb-1">
                        <label class="form-label">Note pour l'étudiant <small class="text-white-50">(optionnel)</small></label>
                        <textarea name="admin_note" class="form-control" rows="3" maxlength="1000"
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
    const statusModal = document.getElementById('statusModal');
    statusModal.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        const id = btn.dataset.id;
        const status = btn.dataset.status;
        const label = btn.dataset.label;

        const form = document.getElementById('statusForm');
        form.action = '{{ url('/evc/app/admin/rendez-vous') }}/' + id + '/status';
        document.getElementById('statusInput').value = status;
        document.getElementById('statusModalLabel').textContent = label;

        const titles = {
            confirmed: 'Confirmer le rendez-vous',
            cancelled: 'Annuler le rendez-vous',
            completed: 'Marquer comme terminé',
        };
        document.getElementById('statusModalTitle').textContent = titles[status] || 'Action';
        document.getElementById('meetLinkField').style.display = status === 'confirmed' ? 'block' : 'none';

        const submitBtn = document.getElementById('statusSubmitBtn');
        const colors = { confirmed: '#22c55e', cancelled: '#ef4444', completed: '#3b82f6' };
        submitBtn.style.background = colors[status] || '#8b5cf6';
    });
</script>
@endpush
