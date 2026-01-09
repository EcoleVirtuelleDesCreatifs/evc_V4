@extends('layouts.admin')

@section('title', 'Gestion des Salaires')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des Salaires</h1>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Retour Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <div class="text-muted">Période</div>
                    <div class="fw-bold">{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</div>
                </div>
                <div class="text-muted">
                    Commission Commercial: {{ number_format(((int)($commercial_rate_bp ?? 0)) / 100, 2, ',', ' ') }}%
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Admin</th>
                            <th>Profils</th>
                            <th class="text-center">KPI</th>
                            <th class="text-end">Forfait (mois)</th>
                            <th class="text-end">Gagné (mois)</th>
                            <th class="text-center">Montant visible</th>
                            <th class="text-end">Ventes (mois)</th>
                            <th class="text-end">Commission (mois)</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($rows ?? []) as $row)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $row['name'] ?? '' }}</div>
                                    <div class="text-muted" style="font-size: 0.9rem;">{{ $row['email'] ?? '' }}</div>
                                </td>
                                <td>
                                    @php
                                        $labels = (array)($row['profile_labels'] ?? []);
                                    @endphp
                                    @if(count($labels) === 0)
                                        <span class="text-muted">Aucun</span>
                                    @else
                                        @foreach($labels as $lab)
                                            <span class="badge bg-secondary me-1">{{ $lab }}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ (int)($row['kpi_avg'] ?? 0) }}%</span>
                                </td>
                                <td class="text-end fw-bold">{{ number_format((int)($row['base_total'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                <td class="text-end fw-bold">{{ number_format((int)($row['earned_total'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                <td class="text-center">
                                    @php $canSee = (bool)($row['can_view_salary_amount'] ?? false); @endphp
                                    <form method="POST" action="{{ route('admin.payroll.admin.visibility', ['adminId' => (int)$row['id']]) }}" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="can_view_salary_amount" value="{{ $canSee ? 0 : 1 }}">
                                        <button type="submit" class="btn btn-sm {{ $canSee ? 'btn-success' : 'btn-outline-secondary' }}">
                                            {{ $canSee ? 'Oui' : 'Non' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-end fw-bold">{{ number_format((int)($row['commercial_sales_month'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                <td class="text-end fw-bold">{{ number_format((int)($row['commercial_commission_month'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#profiles-{{ (int)$row['id'] }}">
                                        Détails
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="profiles-{{ (int)$row['id'] }}">
                                <td colspan="9">
                                    <div class="mb-3">
                                        <div class="fw-bold mb-2">Détail KPI par profil</div>
                                        <div class="table-responsive">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>Profil</th>
                                                        <th class="text-center">KPI</th>
                                                        <th class="text-center">Pénalité</th>
                                                        <th class="text-center">Score</th>
                                                        <th class="text-end">Forfait</th>
                                                        <th class="text-end">Gagné</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach((array)($row['breakdown'] ?? []) as $b)
                                                        <tr>
                                                            <td>{{ $b['label'] ?? '' }}</td>
                                                            <td class="text-center"><span class="badge bg-info">{{ (int)($b['kpi'] ?? 0) }}%</span></td>
                                                            <td class="text-center"><span class="badge bg-warning text-dark">-{{ (int)($b['penalty'] ?? 0) }}</span></td>
                                                            <td class="text-center"><span class="badge bg-success">{{ (int)($b['final_score'] ?? 0) }}%</span></td>
                                                            <td class="text-end">{{ number_format((int)($b['base_monthly_amount'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                                            <td class="text-end fw-bold">{{ number_format((int)($b['earned'] ?? 0), 0, ',', ' ') }} FCFA</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.payroll.admin.profiles', ['adminId' => (int)$row['id']]) }}">
                                        @csrf
                                        <div class="fw-bold mb-2">Attribuer des profils</div>
                                        <div class="row g-2">
                                            @foreach(($profiles ?? []) as $p)
                                                @php
                                                    $checked = in_array((int)$p->id, ((array)($row['profile_ids'] ?? [])));
                                                @endphp
                                                <div class="col-md-4">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="job_profile_ids[]" value="{{ (int)$p->id }}" id="p{{ (int)$row['id'] }}-{{ (int)$p->id }}" {{ $checked ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="p{{ (int)$row['id'] }}-{{ (int)$p->id }}">
                                                            {{ $p->label }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3 d-flex justify-content-end">
                                            <button class="btn btn-success" type="submit">Enregistrer</button>
                                        </div>
                                    </form>
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
