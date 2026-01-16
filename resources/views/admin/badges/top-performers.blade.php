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
                    'dg' => ['label' => 'Design Graphique', 'icon' => 'fa-palette'],
                    'cm' => ['label' => 'Community Management', 'icon' => 'fa-users'],
                    'dgcm' => ['label' => 'Design + CM', 'icon' => 'fa-object-group'],
                ];
            @endphp

            <div class="row g-3">
                @foreach($sections as $formationKey => $meta)
                    @php
                        $list = $topByFormation[$formationKey] ?? collect();
                    @endphp
                    <div class="col-12 col-xl-4">
                        <div class="card page-card h-100">
                            <div class="card-header">
                                <h6 class="mb-0 text-white">
                                    <i class="fas {{ $meta['icon'] }} me-2"></i>
                                    {{ $meta['label'] }}
                                </h6>
                                <small class="text-white-50">Top 5 — période: {{ $periodLabels[$period ?? 'month'] ?? 'Mois' }}</small>
                            </div>
                            <div class="card-body">
                                @if($list->isEmpty())
                                    <div class="text-white-50">Aucune donnée</div>
                                @else
                                    <div class="d-flex flex-column gap-3">
                                        @foreach($list as $idx => $p)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="rank-pill">{{ $idx + 1 }}</span>
                                                <div class="flex-grow-1" style="min-width:0;">
                                                    <div class="text-white fw-semibold" style="line-height:1.15;">
                                                        {{ trim(($p->first_name ?? '') . ' ' . ($p->last_name ?? '')) }}
                                                    </div>
                                                    <div class="text-white-50" style="font-size:.85rem;">
                                                        {{ $p->student_id ?? '—' }}
                                                    </div>
                                                </div>
                                                <div class="text-end" style="min-width:70px;">
                                                    <div class="text-white fw-bold">{{ (int) ($p->total_score ?? 0) }}</div>
                                                    <div class="text-white-50" style="font-size:.75rem;">P:{{ (int) ($p->projects_validated ?? 0) }} TP:{{ (int) ($p->tp_validated ?? 0) }}</div>
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
