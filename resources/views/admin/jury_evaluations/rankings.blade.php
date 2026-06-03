@extends('layouts.admin')

@section('title', 'Classements Studio Créatif')

@push('styles')
<style>
    .rankings-page { background: #0b1120; min-height: 100vh; padding: 2rem 1.5rem; }

    /* Stats bar */
    .stat-card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 1.2rem 1.5rem; display: flex; align-items: center; gap: 1rem; }
    .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; flex-shrink: 0; }
    .stat-label { font-size: .75rem; color: #64748b; text-transform: uppercase; letter-spacing: .05em; }
    .stat-value { font-size: 1.6rem; font-weight: 700; color: #f1f5f9; line-height: 1; }

    /* Global podium */
    .podium-section { background: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: visible; }
    .podium-header { background: #0f172a; border-bottom: 1px solid #334155; padding: 1rem 1.5rem; display: flex; align-items: center; gap: .75rem; border-radius: 16px 16px 0 0; }
    .podium-body { padding: 2rem 2.5rem 2rem; }
    .podium-bars { display: flex; align-items: flex-end; justify-content: center; gap: 1.5rem; height: 360px; padding-top: 60px; box-sizing: border-box; }
    .podium-bar-wrap { display: flex; flex-direction: column; align-items: center; gap: .6rem; flex: 1; max-width: 160px; }
    .podium-bar { width: 100%; border-radius: 10px 10px 0 0; transition: all .3s; position: relative; min-height: 16px; }
    .podium-bar-label { font-size: .9rem; color: #94a3b8; font-weight: 700; }
    .podium-bar-score { font-size: 1.05rem; font-weight: 700; color: #f1f5f9; }
    .podium-rank { font-size: 2rem; }

    /* Category section */
    .cat-section { background: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden; }
    .cat-header { padding: 1rem 1.5rem; display: flex; align-items: center; gap: .75rem; border-bottom: 1px solid #334155; }
    .cat-icon { width: 38px; height: 38px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0; }

    /* Table */
    .rankings-table { width: 100%; border-collapse: collapse; }
    .rankings-table thead tr { background: #0f172a; }
    .rankings-table th { padding: .75rem 1.25rem; font-size: .75rem; text-transform: uppercase; letter-spacing: .07em; color: #64748b; font-weight: 600; border-bottom: 1px solid #334155; }
    .rankings-table tbody tr { border-bottom: 1px solid #1e3347; transition: background .15s; }
    .rankings-table tbody tr:hover { background: #162032; }
    .rankings-table tbody tr.gold-row { background: #1c2a0e; }
    .rankings-table td { padding: .85rem 1.25rem; color: #cbd5e1; vertical-align: middle; }

    /* Progress bar */
    .prog-track { width: 100px; height: 5px; background: #334155; border-radius: 3px; overflow: hidden; display: inline-block; vertical-align: middle; }
    .prog-fill { height: 100%; border-radius: 3px; }

    /* Medal badges */
    .medal { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 20px; font-size: .8rem; font-weight: 700; }
    .medal-1 { background: rgba(245,158,11,.15); color: #f59e0b; border: 1px solid rgba(245,158,11,.3); }
    .medal-2 { background: rgba(148,163,184,.12); color: #94a3b8; border: 1px solid rgba(148,163,184,.25); }
    .medal-3 { background: rgba(205,127,50,.12); color: #cd7f32; border: 1px solid rgba(205,127,50,.25); }

    /* Empty */
    .empty-state { text-align: center; padding: 3rem; color: #475569; }
    .empty-state i { font-size: 2.5rem; margin-bottom: .75rem; display: block; }
</style>
@endpush

@section('content')
<div class="rankings-page">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-white mb-1" style="font-size:1.5rem;font-weight:700;">🏆 Classements Studio Créatif</h1>
            <p style="color:#64748b;margin:0;font-size:.875rem;">Résultats des évaluations par catégorie</p>
        </div>
        <a href="{{ route('admin.jury-members.index') }}" class="btn btn-sm" style="background:#1e293b;border:1px solid #334155;color:#94a3b8;">
            <i class="fas fa-arrow-left me-1"></i> Retour
        </a>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(59,130,246,.15);">📋</div>
                <div>
                    <div class="stat-label">Évaluations</div>
                    <div class="stat-value">{{ $totalEvaluations }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(16,185,129,.15);">👥</div>
                <div>
                    <div class="stat-label">Jurés actifs</div>
                    <div class="stat-value">{{ $totalJurors }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(245,158,11,.15);">🎯</div>
                <div>
                    <div class="stat-label">Groupes</div>
                    <div class="stat-value">{{ count($allGroups) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(244,63,94,.15);">🏅</div>
                <div>
                    <div class="stat-label">Catégories</div>
                    <div class="stat-value">{{ count($categories) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Podium global --}}
    @if($globalScores->isNotEmpty())
    <div class="podium-section mb-4">
        <div class="podium-header">
            <span style="font-size:1.3rem;">🏆</span>
            <h5 class="mb-0 text-white">Classement général</h5>
            <span style="font-size:.8rem;color:#64748b;margin-left:auto;">Score moyen toutes catégories</span>
        </div>
        <div class="podium-body">
            @php
                $maxAvg = $globalScores->max('avg') ?: 1;
                $podiumColors = ['#f59e0b','#94a3b8','#cd7f32','#6366f1','#334155'];
                $podiumRanks  = ['🥇','🥈','🥉','4️⃣','5️⃣'];
            @endphp
            <div class="podium-bars">
                @foreach($globalScores as $i => $gs)
                    @php $barH = max(24, round(($gs['avg'] / $maxAvg) * 280)); @endphp
                    <div class="podium-bar-wrap">
                        <div class="podium-bar-score">{{ $gs['avg'] }}<small style="font-size:.65rem;color:#64748b;">/320</small></div>
                        <div class="podium-bar" style="height:{{ $barH }}px; background: {{ $podiumColors[$i] ?? '#334155' }}; opacity:{{ $i === 0 ? 1 : 0.7 }};"></div>
                        <div class="podium-rank">{{ $podiumRanks[$i] ?? ($i+1) }}</div>
                        <div class="podium-bar-label">{{ $gs['group_name'] }}</div>
                        <div style="font-size:.7rem;color:#475569;">{{ $gs['count'] }} note(s)</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- Classements par catégorie --}}
    <div class="row g-4">
    @foreach($rankings as $categoryKey => $categoryRankings)
        @php $cat = $categories[$categoryKey]; @endphp
        <div class="col-12 col-xl-6">
            <div class="cat-section h-100">
                <div class="cat-header">
                    <div class="cat-icon" style="background: {{ $cat['color'] }}22;">
                        {{ $cat['icon'] }}
                    </div>
                    <div>
                        <div class="text-white fw-600" style="font-size:.95rem;font-weight:600;">{{ $cat['label'] }}</div>
                        <div style="font-size:.75rem;color:#64748b;">Note max / 80</div>
                    </div>
                    <span class="ms-auto" style="font-size:.75rem;color:#475569;">{{ $categoryRankings->count() }} résultat(s)</span>
                </div>

                @if($categoryRankings->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        Aucune évaluation soumise
                    </div>
                @else
                    <table class="rankings-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Groupe</th>
                                <th>Score</th>
                                <th>Jury</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryRankings as $index => $ranking)
                                @php $pct = $ranking['category_score'] > 0 ? round($ranking['category_score'] / 80 * 100) : 0; @endphp
                                <tr class="{{ $index === 0 ? 'gold-row' : '' }}">
                                    <td>
                                        @if($index === 0)
                                            <span class="medal medal-1">🥇 1</span>
                                        @elseif($index === 1)
                                            <span class="medal medal-2">🥈 2</span>
                                        @elseif($index === 2)
                                            <span class="medal medal-3">🥉 3</span>
                                        @else
                                            <span style="color:#475569;font-size:.85rem;">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-white" style="font-size:.9rem;">{{ $ranking['group_name'] }}</strong>
                                    </td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:8px;">
                                            <span style="font-weight:700;color:{{ $cat['color'] }};font-size:.9rem;min-width:32px;">{{ $ranking['category_score'] }}</span>
                                            <span class="prog-track">
                                                <span class="prog-fill" style="width:{{ $pct }}%;background:{{ $cat['color'] }};"></span>
                                            </span>
                                            <span style="font-size:.72rem;color:#475569;">{{ $pct }}%</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="font-size:.78rem;color:#94a3b8;">
                                            <i class="fas fa-user-tie me-1" style="color:#334155;"></i>{{ $ranking['jury_name'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
    </div>

</div>
@endsection
