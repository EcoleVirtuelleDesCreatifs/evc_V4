@extends('layouts.admin')

@section('title', 'Paramètres Salaires')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Paramètres Salaires</h1>
            <div class="text-muted">Gestion des forfaits, commissions et conditions KPI</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
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

    <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body">
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
        </div>
    </div>
</div>
@endsection
