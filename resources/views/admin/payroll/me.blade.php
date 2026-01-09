@extends('layouts.admin')

@section('title', 'Gestion des Salaires')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Salaires</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Retour Dashboard</a>
    </div>

    @php
        $canSee = (bool)($admin->can_view_salary_amount ?? false);
    @endphp

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <div class="text-muted">Période</div>
                    <div class="fw-bold">{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</div>
                </div>
                <div class="text-muted">
                    {{ $admin->name ?? '' }} — {{ $admin->email ?? '' }}
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="text-muted mb-2">Profils attribués</div>
            @if(($profiles ?? collect())->count() === 0)
                <div class="text-muted">Aucun profil attribué</div>
            @else
                @foreach($profiles as $p)
                    <span class="badge bg-secondary me-1">{{ $p->label }}</span>
                @endforeach
            @endif
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Commission Commercial (mois)</div>
                    @if($canSee)
                        <div class="fs-3 fw-bold">{{ number_format((int)($commercialCommissionMonth ?? 0), 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted">Ventes: {{ number_format((int)($commercialSalesMonth ?? 0), 0, ',', ' ') }} FCFA</div>
                    @else
                        <div class="text-muted">Montant masqué (autorisation requise)</div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Forfaits (profils)</div>
                    @if(!$canSee)
                        <div class="text-muted">Montant masqué (autorisation requise)</div>
                    @else
                        @php
                            $sumBase = (int) collect($profiles)->sum(function ($x) { return (int) ($x->base_monthly_amount ?? 0); });
                        @endphp
                        <div class="fs-3 fw-bold">{{ number_format($sumBase, 0, ',', ' ') }} FCFA</div>
                        <div class="text-muted">Somme des forfaits mensuels attribués</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="text-muted mb-2">Performance (KPI)</div>
            <div class="text-muted">En cours d’activation (tâches + pénalités progressives par profil).</div>
        </div>
    </div>
</div>
@endsection
