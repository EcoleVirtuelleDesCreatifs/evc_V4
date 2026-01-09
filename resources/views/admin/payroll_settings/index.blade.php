@extends('layouts.admin')

@section('title', 'Paramètres Salaires')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">Paramètres Salaires</h1>
            <div class="text-white-50">Gestion des forfaits, commissions et conditions KPI</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            @if(!empty($moduleAvailable))
                <a href="{{ route('admin.payroll.settings.profile.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Nouveau profil
                </a>
            @endif
            <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour salaires
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <div class="fw-bold text-white"><i class="fas fa-sliders-h me-2"></i>Profils & règles</div>
        </div>
        <div class="card-body">
            @if(empty($moduleAvailable))
                <div class="alert alert-warning mb-0">
                    <div class="fw-bold mb-1">Module non disponible</div>
                    <div>
                        La table <code>admin_job_profiles</code> n'existe pas encore ou la migration n'a pas été appliquée.
                        Lance les migrations (ou vérifie la base de données) puis recharge.
                    </div>
                </div>
            @elseif(collect($profiles ?? [])->count() === 0)
                <div class="alert alert-info mb-0" style="background-color: #1e40af; border-color: #1e40af; color: white;">
                    <div class="fw-bold mb-1">Aucun profil configuré</div>
                    <div>
                        Crée/active au moins un profil salaire pour que les paramètres s’affichent.
                        <a class="text-white text-decoration-underline ms-1" href="{{ route('admin.payroll.settings.profile.create') }}">Créer un profil</a>
                    </div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Profil</th>
                                <th class="text-center">Statut</th>
                                <th class="text-end">Forfait mensuel</th>
                                <th class="text-end">Commission</th>
                                <th class="text-center">Tâches KPI</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(($profiles ?? []) as $p)
                                @php
                                    $taskCount = (int) (($taskCountsByProfile[(int)($p->id ?? 0)] ?? 0));
                                    $commission = (int) ($p->commission_rate_bp ?? 0);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $p->label ?? '' }}</div>
                                        <div class="text-muted" style="font-size: 0.9rem;">Code: {{ $p->code ?? '' }}</div>
                                    </td>
                                    <td class="text-center">
                                        @if((int)($p->is_active ?? 0) === 1)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">{{ number_format((int)($p->base_monthly_amount ?? 0), 0, ',', ' ') }} FCFA</td>
                                    <td class="text-end">
                                        @if($commission > 0)
                                            <span class="fw-bold">{{ number_format($commission / 100, 2, ',', ' ') }}%</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $taskCount }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.payroll.settings.profile.edit', ['profileId' => (int)($p->id ?? 0)]) }}">
                                            <i class="fas fa-pen me-1"></i>Forfait & commission
                                        </a>
                                        <a class="btn btn-sm btn-primary" href="{{ route('admin.payroll.settings.profile.tasks', ['profileId' => (int)($p->id ?? 0)]) }}">
                                            <i class="fas fa-sliders-h me-1"></i>Conditions KPI
                                        </a>
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
@endsection

@push('styles')
<style>
    body { background-color: #0f172a; }
    .table { color: #e2e8f0; }
    .table thead th { color: #e2e8f0; }
    .table>:not(caption)>*>* {
        background-color: transparent;
        color: inherit;
        box-shadow: none;
    }
    .table thead th {
        background-color: rgba(15, 23, 42, 0.92);
        border-color: rgba(148, 163, 184, 0.18);
    }
    .table tbody td {
        background-color: rgba(15, 23, 42, 0.55);
        border-color: rgba(148, 163, 184, 0.12);
    }
    .table tbody tr:hover td {
        background-color: rgba(30, 41, 59, 0.70);
    }
</style>
@endpush
