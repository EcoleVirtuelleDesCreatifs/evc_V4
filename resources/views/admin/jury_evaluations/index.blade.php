@extends('layouts.admin')

@section('title', 'Évaluations — ' . $juryMember->name)

@section('content')
@php
    $photoSrc = null;
    if (!empty($juryMember->image_url)) {
        $photoSrc = $juryMember->image_url;
    } elseif (!empty($juryMember->image_path)) {
        $photoSrc = asset('storage/' . ltrim($juryMember->image_path, '/'));
    }
    $submitted = $evaluations->where('status', 'submitted');
    $totalScore = $submitted->sum('total_score');
@endphp
<style>
    .eval-page { background: #0b1120; min-height: 100vh; }
    .eval-profile-card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; padding: 1.8rem 2rem; display: flex; align-items: center; gap: 1.5rem; }
    .eval-avatar { width: 72px; height: 72px; border-radius: 50%; object-fit: cover; object-position: top; border: 3px solid #334155; flex-shrink: 0; }
    .eval-avatar-placeholder { width: 72px; height: 72px; border-radius: 50%; background: #1e3347; display: flex; align-items: center; justify-content: center; font-size: 2rem; flex-shrink: 0; border: 3px solid #334155; }
    .stat-mini { background: #0f172a; border: 1px solid #334155; border-radius: 10px; padding: .7rem 1.1rem; text-align: center; }
    .stat-mini-val { font-size: 1.4rem; font-weight: 700; color: #f1f5f9; line-height: 1; }
    .stat-mini-lbl { font-size: .68rem; color: #64748b; text-transform: uppercase; letter-spacing: .05em; margin-top: 3px; }
    .eval-table { width: 100%; border-collapse: collapse; }
    .eval-table thead tr { background: #0f172a; }
    .eval-table th { padding: .75rem 1.2rem; font-size: .72rem; text-transform: uppercase; letter-spacing: .07em; color: #64748b; font-weight: 600; border-bottom: 1px solid #334155; white-space: nowrap; }
    .eval-table td { padding: .9rem 1.2rem; border-bottom: 1px solid #1e3347; color: #cbd5e1; vertical-align: middle; }
    .eval-table tbody tr:hover { background: rgba(255,255,255,.02); }
    .eval-table tbody tr:last-child td { border-bottom: none; }
    .score-bar-bg { background: #0f172a; border-radius: 99px; height: 6px; width: 100%; min-width: 80px; }
    .score-bar-fill { height: 6px; border-radius: 99px; background: linear-gradient(90deg, #3b82f6, #8b5cf6); }
    /* Modal dark — préfixé jury-modal pour éviter conflits Bootstrap */
    .jury-modal .modal-content { background: #1e293b !important; border: 1px solid #334155 !important; color: #f1f5f9 !important; border-radius: 12px !important; }
    .jury-modal .modal-header { background: #0f172a !important; border-bottom: 1px solid #334155 !important; border-radius: 12px 12px 0 0 !important; }
    .jury-modal .modal-body { background: #1e293b !important; }
    .jury-modal .modal-footer { background: #0f172a !important; border-top: 1px solid #334155 !important; border-radius: 0 0 12px 12px !important; }
    .jury-modal .modal-title { color: #f1f5f9 !important; }
    .jury-crit-row { display: flex; justify-content: space-between; align-items: center; padding: .45rem 0; border-bottom: 1px solid #1e3347; font-size: .85rem; }
    .jury-crit-row:last-child { border-bottom: none; }
    .jury-cat-block { background: #0f172a; border: 1px solid #334155; border-radius: 10px; padding: 1rem 1.2rem; margin-bottom: .75rem; }
    .jury-cat-block-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .6rem; }
    .jury-progress-sm { height: 5px; background: #1e3347; border-radius: 99px; overflow: hidden; margin-top: 4px; }
    .jury-progress-sm-fill { height: 5px; border-radius: 99px; }
</style>

<div class="eval-page p-4">

    {{-- Header --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('admin.jury-members.index') }}"
           style="background:#1e293b;border:1px solid #334155;color:#94a3b8;border-radius:8px;padding:6px 12px;font-size:.82rem;text-decoration:none;">
            ← Retour
        </a>
        <div>
            <h1 class="text-white mb-0" style="font-size:1.3rem;font-weight:700;">Évaluations du jury</h1>
            <p style="color:#64748b;margin:0;font-size:.82rem;">Studio Créatif</p>
        </div>
    </div>

    {{-- Profile card --}}
    <div class="eval-profile-card mb-4">
        @if($photoSrc)
            <img src="{{ $photoSrc }}" alt="{{ $juryMember->name }}" class="eval-avatar"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div class="eval-avatar-placeholder" style="display:none;">👤</div>
        @else
            <div class="eval-avatar-placeholder">👤</div>
        @endif
        <div class="flex-grow-1">
            <div style="font-size:1.15rem;font-weight:700;color:#f1f5f9;">{{ $juryMember->name }}</div>
            <div style="font-size:.82rem;color:#64748b;margin-top:2px;">
                {{ $juryMember->title ?: 'Fonction non renseignée' }}
                @if($juryMember->flag) · {{ $juryMember->flag }} {{ $juryMember->country }} @endif
            </div>
            <div style="margin-top:.5rem;">
                <code style="background:#0f172a;color:#93c5fd;padding:2px 8px;border-radius:5px;border:1px solid #1e3a5f;font-size:.75rem;">{{ $juryMember->unique_identifier ?? '—' }}</code>
            </div>
        </div>
        <div class="d-flex gap-3 ms-auto flex-wrap">
            <div class="stat-mini">
                <div class="stat-mini-val">{{ $evaluations->count() }}</div>
                <div class="stat-mini-lbl">Total</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-val" style="color:#4ade80;">{{ $submitted->count() }}</div>
                <div class="stat-mini-lbl">Soumis</div>
            </div>
            <div class="stat-mini">
                <div class="stat-mini-val" style="color:#f59e0b;">{{ $totalScore }}</div>
                <div class="stat-mini-lbl">Score total</div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div style="background:#1e293b;border:1px solid #334155;border-radius:16px;overflow:hidden;">
        <div style="background:#0f172a;border-bottom:1px solid #334155;padding:.85rem 1.4rem;">
            <span style="color:#f1f5f9;font-weight:600;font-size:.9rem;">📋 Historique des évaluations</span>
        </div>
        @if($evaluations->isEmpty())
            <div style="text-align:center;padding:4rem;color:#475569;">
                <i class="fas fa-clipboard-list" style="font-size:2.5rem;display:block;margin-bottom:1rem;"></i>
                Aucune évaluation pour ce membre du jury.
            </div>
        @else
            <div class="table-responsive">
                <table class="eval-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Groupe</th>
                            <th>Score</th>
                            <th>Progression</th>
                            <th>Statut</th>
                            <th>Commentaire</th>
                            <th style="text-align:right;">Détails</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($evaluations as $evaluation)
                            @php $pct = $evaluation->total_score > 0 ? round(($evaluation->total_score / 320) * 100) : 0; @endphp
                            <tr>
                                <td style="color:#94a3b8;font-size:.83rem;white-space:nowrap;">
                                    {{ $evaluation->evaluation_date ? $evaluation->evaluation_date->format('d/m/Y') : '—' }}
                                </td>
                                <td>
                                    <span style="background:#1e3a5f;color:#93c5fd;border:1px solid #1e40af;border-radius:20px;padding:3px 10px;font-size:.78rem;font-weight:600;">
                                        {{ $evaluation->group_name }}
                                    </span>
                                </td>
                                <td style="font-weight:700;color:#f1f5f9;white-space:nowrap;">
                                    {{ $evaluation->total_score }}
                                    <span style="color:#475569;font-size:.75rem;font-weight:400;">/320</span>
                                </td>
                                <td style="min-width:100px;">
                                    <div style="font-size:.72rem;color:#64748b;margin-bottom:3px;">{{ $pct }}%</div>
                                    <div class="score-bar-bg">
                                        <div class="score-bar-fill" style="width:{{ $pct }}%;"></div>
                                    </div>
                                </td>
                                <td>
                                    @if($evaluation->status === 'submitted')
                                        <span style="background:rgba(16,185,129,.15);color:#10b981;border:1px solid rgba(16,185,129,.3);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;">✓ Soumis</span>
                                    @else
                                        <span style="background:rgba(100,116,139,.15);color:#64748b;border:1px solid rgba(100,116,139,.3);border-radius:20px;padding:3px 10px;font-size:.75rem;font-weight:600;">Brouillon</span>
                                    @endif
                                </td>
                                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#64748b;font-size:.82rem;">
                                    {{ $evaluation->global_comment ?: '—' }}
                                </td>
                                <td style="text-align:right;">
                                    <button type="button"
                                            class="btn btn-sm"
                                            style="background:#1e3a5f;color:#93c5fd;border:1px solid #1e40af;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-{{ $evaluation->id }}">
                                        <i class="fas fa-eye me-1"></i> Détails
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection

@push('modals')
@foreach($evaluations as $evaluation)
<div class="modal fade jury-modal" id="modal-{{ $evaluation->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="background:#1e293b;border:1px solid #334155;color:#f1f5f9;border-radius:12px;">
            <div class="modal-header" style="background:#0f172a;border-bottom:1px solid #334155;border-radius:12px 12px 0 0;">
                <div>
                    <h5 class="modal-title mb-0" style="color:#f1f5f9;">
                        {{ $evaluation->group_name }}
                        <span style="font-size:.82rem;color:#64748b;margin-left:.5rem;">
                            {{ $evaluation->evaluation_date ? $evaluation->evaluation_date->format('d/m/Y') : '' }}
                        </span>
                    </h5>
                    <div style="font-size:.78rem;color:#64748b;margin-top:2px;">{{ $juryMember->name }}</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" style="background:#1e293b;">

                {{-- Score global --}}
                <div style="display:flex;align-items:center;justify-content:space-between;background:#0f172a;border:1px solid #334155;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
                    <span style="color:#94a3b8;font-size:.85rem;">Score total</span>
                    <div style="text-align:right;">
                        <span style="font-size:1.5rem;font-weight:700;color:#f1f5f9;">{{ $evaluation->total_score }}</span>
                        <span style="color:#475569;font-size:.82rem;">/320</span>
                        @php $pctModal = $evaluation->total_score > 0 ? round(($evaluation->total_score / 320) * 100) : 0; @endphp
                        <span style="margin-left:.5rem;background:rgba(59,130,246,.15);color:#60a5fa;border:1px solid rgba(59,130,246,.3);border-radius:20px;padding:2px 8px;font-size:.75rem;">{{ $pctModal }}%</span>
                    </div>
                </div>

                @if($evaluation->global_comment)
                    <div style="background:#0f172a;border:1px solid #334155;border-radius:10px;padding:.85rem 1.1rem;margin-bottom:1.25rem;">
                        <div style="font-size:.72rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.4rem;">Commentaire global</div>
                        <p style="color:#cbd5e1;font-size:.88rem;margin:0;">{{ $evaluation->global_comment }}</p>
                    </div>
                @endif

                {{-- Catégories --}}
                <div style="font-size:.72rem;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.75rem;">Détails par catégorie</div>
                @foreach($evaluation->scores->groupBy('category_key') as $categoryKey => $categoryScores)
                    @php
                        $catTotal = $categoryScores->sum('score');
                        $catMax   = $categoryScores->count() * 20;
                        $catPct   = $catMax > 0 ? round(($catTotal / $catMax) * 100) : 0;
                        $catColor = match(true) {
                            $catPct >= 80 => '#4ade80',
                            $catPct >= 50 => '#f59e0b',
                            default       => '#f87171',
                        };
                    @endphp
                    <div style="background:#0f172a;border:1px solid #334155;border-radius:10px;padding:1rem 1.2rem;margin-bottom:.75rem;">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.6rem;">
                            <span style="font-weight:600;color:#f1f5f9;font-size:.9rem;">{{ $categoryScores->first()->category_label }}</span>
                            <span style="font-size:.85rem;font-weight:700;color:{{ $catColor }};">{{ $catTotal }}/{{ $catMax }} <span style="font-size:.7rem;color:#64748b;">({{ $catPct }}%)</span></span>
                        </div>
                        <div style="height:5px;background:#1e3347;border-radius:99px;overflow:hidden;margin-bottom:.75rem;">
                            <div style="height:5px;border-radius:99px;width:{{ $catPct }}%;background:{{ $catColor }};"></div>
                        </div>
                        @foreach($categoryScores as $score)
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:.4rem 0;border-bottom:1px solid #1e3347;font-size:.85rem;">
                                <span style="color:#94a3b8;">{{ $score->criterion_label }}</span>
                                <span style="font-weight:600;color:#f1f5f9;">{{ $score->score }}<span style="color:#475569;font-weight:400;">/20</span></span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <div class="modal-footer" style="background:#0f172a;border-top:1px solid #334155;border-radius:0 0 12px 12px;">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endpush
