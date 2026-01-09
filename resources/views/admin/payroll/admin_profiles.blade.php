@extends('layouts.admin')

@section('title', 'Attribuer des profils')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Attribuer des profils</h1>
            <div class="text-muted">{{ $admin->name ?? '' }} — {{ $admin->email ?? '' }}</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
            <a href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($admin->id ?? 0)]) }}" class="btn btn-outline-primary">
                <i class="fas fa-eye me-2"></i>Détail
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1">Veuillez corriger les erreurs</div>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
                <div class="card-body">
                    <div class="fw-bold mb-2">Sélection des profils</div>
                    <div class="text-muted mb-3" style="font-size: 0.95rem;">Tu peux attribuer plusieurs profils à un admin. Les règles de paie et KPI se calculent automatiquement par profil.</div>

                    <form method="POST" action="{{ route('admin.payroll.admin.profiles', ['adminId' => (int)($admin->id ?? 0)]) }}">
                        @csrf
                        @if(collect($profiles ?? [])->count() === 0)
                            <div class="alert alert-warning mb-0">
                                <div class="fw-bold mb-1">Aucun profil disponible</div>
                                <div>
                                    Tu dois d'abord créer/activer des profils dans
                                    <a href="{{ route('admin.payroll.settings.index') }}">Paramètres Salaires</a>.
                                </div>
                            </div>
                        @else
                            <div class="row g-2">
                                @foreach(($profiles ?? []) as $p)
                                    @php
                                        $isChecked = in_array((int)($p->id ?? 0), (array)($assigned ?? []));
                                        $isActive = !property_exists($p, 'is_active') || ((int)($p->is_active ?? 1) === 1);
                                    @endphp
                                    <div class="col-md-6">
                                        <label class="profile-tile {{ $isActive ? '' : 'profile-tile-disabled' }}">
                                            <input type="checkbox" class="form-check-input me-2" name="job_profile_ids[]" value="{{ (int)($p->id ?? 0) }}" {{ $isChecked ? 'checked' : '' }} {{ $isActive ? '' : 'disabled' }}>
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="fw-bold">
                                                        {{ $p->label ?? '' }}
                                                        @if(!$isActive)
                                                            <span class="badge bg-secondary ms-2">Inactif</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-muted" style="font-size: 0.9rem;">{{ number_format((int)($p->base_monthly_amount ?? 0), 0, ',', ' ') }} FCFA</div>
                                                </div>
                                                @if(($p->code ?? '') === 'commercial')
                                                    <div class="text-muted" style="font-size: 0.9rem;">Commission: {{ number_format(((int)($p->commission_rate_bp ?? 0)) / 100, 2, ',', ' ') }}%</div>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="d-flex justify-content-end mt-4">
                            <button class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="fw-bold mb-2">Visibilité des montants</div>
                    <div class="text-muted mb-3" style="font-size: 0.95rem;">Active si tu veux que le salarié voit ses montants (forfaits, gagné, commission).</div>

                    @php $canSee = (bool)($admin->can_view_salary_amount ?? false); @endphp
                    <form method="POST" action="{{ route('admin.payroll.admin.visibility', ['adminId' => (int)($admin->id ?? 0)]) }}">
                        @csrf
                        <input type="hidden" name="can_view_salary_amount" value="{{ $canSee ? 0 : 1 }}">
                        <button class="btn w-100 {{ $canSee ? 'btn-success' : 'btn-outline-secondary' }}">
                            <i class="fas {{ $canSee ? 'fa-eye' : 'fa-eye-slash' }} me-2"></i>
                            {{ $canSee ? 'Montants visibles' : 'Montants masqués' }}
                        </button>
                    </form>

                    <div class="mt-3 text-muted" style="font-size: 0.9rem;">
                        Astuce: tu peux activer/désactiver à tout moment.
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3" style="border-radius: 16px;">
                <div class="card-body">
                    <div class="fw-bold mb-2">Raccourcis</div>
                    <div class="d-grid gap-2">
                        <a class="btn btn-outline-primary" href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($admin->id ?? 0)]) }}">
                            <i class="fas fa-chart-pie me-2"></i>Voir KPI & gains
                        </a>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.payroll.index') }}">
                            <i class="fas fa-list me-2"></i>Retour liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    body {
        background-color: #0f172a;
    }
    .profile-tile {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        border: 1px solid rgba(0,0,0,0.08);
        padding: 12px 12px;
        border-radius: 14px;
        width: 100%;
        cursor: pointer;
        transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
        background: #fff;
    }
    .profile-tile:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 28px rgba(0,0,0,0.08);
        border-color: rgba(13, 110, 253, 0.25);
    }
    .profile-tile input {
        margin-top: 3px;
    }
    .profile-tile-disabled {
        opacity: 0.65;
        cursor: not-allowed;
    }
</style>
@endpush
