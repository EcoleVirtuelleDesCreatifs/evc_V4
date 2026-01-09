@extends('layouts.admin')

@section('title', 'Attribuer des profils')

@section('content')
<div class="container-fluid py-4">
    <div class="payroll-hero mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <div class="payroll-hero-kicker">Paramétrage RH</div>
                <h1 class="payroll-hero-title">Attribuer des profils</h1>
                <div class="payroll-hero-subtitle">
                    {{ $admin->name ?? '' }} — {{ $admin->email ?? '' }}
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.payroll.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
                <a href="{{ route('admin.payroll.admin.show', ['adminId' => (int)($admin->id ?? 0)]) }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>Détail
                </a>
            </div>
        </div>
        <div class="payroll-hero-divider"></div>
        <div class="text-white-50" style="max-width: 920px;">
            Tu peux attribuer plusieurs profils à un admin. Les règles de paie et KPI se calculent automatiquement par profil.
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
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden;">
                <div class="card-body">
                    <div class="fw-bold text-white mb-2">Sélection des profils</div>
                    <div class="text-white-50 mb-3" style="font-size: 0.95rem;">Coche les profils à activer pour cet admin.</div>

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
                                                    <div class="fw-bold text-white">
                                                        {{ $p->label ?? '' }}
                                                        @if(!$isActive)
                                                            <span class="badge bg-secondary ms-2">Inactif</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-white-50" style="font-size: 0.9rem;">{{ number_format((int)($p->base_monthly_amount ?? 0), 0, ',', ' ') }} FCFA</div>
                                                </div>
                                                @if(($p->code ?? '') === 'commercial')
                                                    <div class="text-white-50" style="font-size: 0.9rem;">Commission: {{ number_format(((int)($p->commission_rate_bp ?? 0)) / 100, 2, ',', ' ') }}%</div>
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
            <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-body">
                    <div class="fw-bold text-white mb-2">Visibilité des montants</div>
                    <div class="text-white-50 mb-3" style="font-size: 0.95rem;">Active si tu veux que le salarié voit ses montants (forfaits, gagné, commission).</div>

                    @php $canSee = (bool)($admin->can_view_salary_amount ?? false); @endphp
                    <form method="POST" action="{{ route('admin.payroll.admin.visibility', ['adminId' => (int)($admin->id ?? 0)]) }}">
                        @csrf
                        <input type="hidden" name="can_view_salary_amount" value="{{ $canSee ? 0 : 1 }}">
                        <button class="btn w-100 {{ $canSee ? 'btn-success' : 'btn-outline-secondary' }}">
                            <i class="fas {{ $canSee ? 'fa-eye' : 'fa-eye-slash' }} me-2"></i>
                            {{ $canSee ? 'Montants visibles' : 'Montants masqués' }}
                        </button>
                    </form>

                    <div class="mt-3 text-white-50" style="font-size: 0.9rem;">
                        Astuce: tu peux activer/désactiver à tout moment.
                    </div>
                </div>
            </div>

            <div class="card mt-3" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
                <div class="card-body">
                    <div class="fw-bold text-white mb-2">Raccourcis</div>
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
    .payroll-hero {
        background: radial-gradient(1200px 500px at 20% -20%, rgba(59, 130, 246, 0.35), transparent 60%),
                    radial-gradient(900px 420px at 90% 0%, rgba(16, 185, 129, 0.22), transparent 55%),
                    linear-gradient(135deg, rgba(30, 41, 59, 0.85), rgba(15, 23, 42, 0.95));
        border: 1px solid rgba(148, 163, 184, 0.22);
        border-radius: 18px;
        padding: 18px;
        overflow: hidden;
    }
    .payroll-hero-kicker {
        color: rgba(226, 232, 240, 0.75);
        letter-spacing: .08em;
        text-transform: uppercase;
        font-size: .78rem;
        font-weight: 700;
    }
    .payroll-hero-title {
        color: #fff;
        margin: 2px 0 4px;
        font-weight: 800;
        letter-spacing: -0.02em;
    }
    .payroll-hero-subtitle {
        color: rgba(226, 232, 240, 0.75);
        font-size: .95rem;
    }
    .payroll-hero-divider {
        height: 1px;
        background: rgba(148, 163, 184, 0.22);
        margin: 14px 0 16px;
    }
    .profile-tile {
        display: flex;
        gap: 10px;
        align-items: flex-start;
        border: 1px solid rgba(148, 163, 184, 0.18);
        padding: 12px 12px;
        border-radius: 14px;
        width: 100%;
        cursor: pointer;
        transition: transform .12s ease, box-shadow .12s ease, border-color .12s ease;
        background: rgba(15, 23, 42, 0.60);
        backdrop-filter: blur(8px);
    }
    .profile-tile:hover {
        transform: translateY(-1px);
        box-shadow: 0 18px 38px rgba(0,0,0,0.22);
        border-color: rgba(59, 130, 246, 0.35);
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
