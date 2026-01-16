@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    .page-card {
        background-color: #1e293b;
        border: 1px solid #334155;
        border-radius: 14px;
    }
    .page-card .card-header {
        background-color: #0f172a;
        border-bottom: 1px solid #334155;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
    }
    .rank-pill {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        color: #fff;
        background: rgba(79,195,247,0.25);
        border: 1px solid rgba(79,195,247,0.35);
        flex-shrink: 0;
    }

    .score-pill {
        min-width: 76px;
        padding: .35rem .6rem;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.06);
        text-align: center;
    }

    .score-total {
        font-weight: 900;
        color: #fff;
        font-size: 1.05rem;
        line-height: 1.1;
    }

    .score-breakdown {
        color: rgba(255,255,255,0.70);
        font-size: .75rem;
        line-height: 1.2;
        margin-top: .1rem;
        white-space: nowrap;
    }

    .avatar {
        width: 42px;
        height: 42px;
        border-radius: 999px;
        object-fit: cover;
        border: 2px solid rgba(255,255,255,0.16);
        background: rgba(255,255,255,0.06);
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        color: rgba(255,255,255,0.95);
    }

    .country-pill {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .2rem .55rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.75);
        font-size: .78rem;
        line-height: 1.1;
        margin-top: .2rem;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">{{ $title }}</h1>
            <div class="text-white-50">Classement Top 5 (Projets + TP validés) par formation</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.badges.students.active') }}" class="btn btn-sm btn-outline-light">Retour</a>
        </div>
    </div>

    <div class="card page-card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 text-white"><i class="fas fa-filter me-2"></i>Période</h5>
                    <small class="text-white-50">Choisissez la période de calcul du classement</small>
                </div>
                <div class="d-flex gap-2">
                    @php
                        $periodLabels = [
                            'week' => 'Semaine',
                            'month' => 'Mois',
                            'quarter' => 'Trimestre',
                            'year' => 'Année',
                        ];
                    @endphp
                    @foreach($periodLabels as $key => $label)
                        <a href="{{ route('admin.badges.students.top-performers', ['period' => $key]) }}"
                           class="btn btn-sm {{ ($period ?? 'month') === $key ? 'btn-primary' : 'btn-outline-light' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="card-body">
            @php
                $sections = [
                    'global' => ['label' => 'Global (toutes formations)', 'icon' => 'fa-globe'],
                    'dg' => ['label' => 'Design Graphique', 'icon' => 'fa-palette'],
                    'cm' => ['label' => 'Community Management', 'icon' => 'fa-users'],
                    'dgcm' => ['label' => 'Design + CM', 'icon' => 'fa-object-group'],
                ];
            @endphp

            <div class="row g-3">
                @foreach($sections as $formationKey => $meta)
                    @php
                        $list = $formationKey === 'global'
                            ? ($topGlobal ?? collect())
                            : ($topByFormation[$formationKey] ?? collect());
                    @endphp
                    <div class="col-12 col-xl-4">
                        <div class="card page-card h-100">
                            <div class="card-header">
                                <h6 class="mb-0 text-white">
                                    <i class="fas {{ $meta['icon'] }} me-2"></i>
                                    {{ $meta['label'] }}
                                </h6>
                                <small class="text-white-50">Top 5 — Total = Projets validés + TP validés — période: {{ $periodLabels[$period ?? 'month'] ?? 'Mois' }}</small>
                            </div>
                            <div class="card-body">
                                @if($list->isEmpty())
                                    <div class="text-white-50">Aucune donnée</div>
                                @else
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($list as $idx => $p)
                                            @php
                                                $country = (string) ($p->country ?? '');
                                                $photoUrl = $p->photo_url ?? null;
                                                $initials = strtoupper(substr($p->first_name ?? 'U', 0, 1) . substr($p->last_name ?? 'U', 0, 1));
                                            @endphp
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="rank-pill">{{ $idx + 1 }}</span>
                                                @if(!empty($photoUrl))
                                                    <img src="{{ $photoUrl }}"
                                                         alt="{{ trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')) }}"
                                                         class="avatar"
                                                         onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='inline-flex';" />
                                                    <span class="avatar" style="display:none;">{{ $initials }}</span>
                                                @else
                                                    <span class="avatar">{{ $initials }}</span>
                                                @endif
                                                <div class="flex-grow-1" style="min-width:0;">
                                                    <div class="text-white fw-semibold" style="line-height:1.15;">
                                                        {{ trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')) }}
                                                    </div>
                                                    @if($country !== '')
                                                        <div class="country-pill">
                                                            <i class="fas fa-flag"></i>
                                                            {{ $country }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="score-pill">
                                                    <div class="score-total">Total: {{ (int) ($p->total_score ?? 0) }}</div>
                                                    <div class="score-breakdown">P:{{ (int) ($p->projects_validated ?? 0) }} + TP:{{ (int) ($p->tp_validated ?? 0) }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
