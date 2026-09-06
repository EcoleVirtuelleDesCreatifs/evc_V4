@extends('layouts.ki-admin')

@section('title', 'Mon éligibilité')

@section('page-title', 'Certification')

@section('content')
<style>
    .eligibility-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: #fff;
    }
    .criterion-card {
        background: linear-gradient(145deg, #1e293b, #334155);
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
        margin-bottom: 1rem;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .criterion-label { font-weight: 600; }
    .criterion-detail { color: #94a3b8; font-size: 0.9rem; }
    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-success { background: rgba(16,185,129,0.15); color: #10b981; }
    .status-danger { background: rgba(239,68,68,0.15); color: #ef4444; }
    .status-warning { background: rgba(251,191,36,0.15); color: #fbbf24; }
    .info-box {
        background: linear-gradient(145deg, #1e293b, #334155);
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.1);
        padding: 1.5rem;
        color: #fff;
        margin-bottom: 1.5rem;
    }
</style>

<div class="container-fluid py-4">
    <div class="eligibility-header">
        <h1 class="h3 mb-1"><i class="fas fa-award me-2"></i>Mon éligibilité à la certification</h1>
        <p class="mb-0 opacity-75">Formation : {{ $eval['formation_label'] ?? $student->program }}</p>
    </div>

    @if(! $eval['formation_supported'])
        <div class="info-box text-center">
            <i class="fas fa-info-circle fa-3x mb-3" style="color:#60a5fa"></i>
            <h3 class="h5">Votre formation n'est pas encore éligible à ce module</h3>
            <p class="text-muted mb-0">Seuls les étudiants en Design Graphique sont concernés pour le moment.</p>
        </div>
    @else
        @if($eval['eligible'])
            <div class="info-box text-center" style="border:1px solid rgba(16,185,129,0.4)">
                <i class="fas fa-trophy fa-3x mb-3" style="color:#10b981"></i>
                <h3 class="h4 mb-2">Félicitations.</h3>
                <p class="mb-0 text-light">Votre éligibilité à la certification EVC a été confirmée.</p>
            </div>
        @elseif($eval['pre_eligible'])
            <div class="info-box text-center" style="border:1px solid rgba(251,191,36,0.4)">
                <i class="fas fa-user-check fa-3x mb-3" style="color:#fbbf24"></i>
                <h3 class="h4 mb-2">Votre dossier est pré-éligible</h3>
                <p class="mb-0 text-light">La validation finale est en cours auprès de l'administration EVC.</p>
            </div>
        @endif

        <div class="criterion-card">
            <div>
                <div class="criterion-label">Projets / TP</div>
                <div class="criterion-detail">Minimum {{ $eval['projects_required'] }} validés</div>
            </div>
            <div class="text-end">
                <div class="fw-bold {{ $eval['projects_ok'] ? 'text-success' : 'text-danger' }}">
                    {{ $eval['projects_count'] }} / {{ $eval['projects_required'] }}
                </div>
                <span class="badge-custom {{ $eval['projects_ok'] ? 'status-success' : 'status-danger' }}">
                    {{ $eval['projects_ok'] ? 'Validé' : 'Non validé' }}
                </span>
            </div>
        </div>

        <div class="criterion-card">
            <div>
                <div class="criterion-label">Paiement</div>
                <div class="criterion-detail">Formation soldée</div>
            </div>
            <div class="text-end">
                <div class="fw-bold {{ $eval['payment']['ok'] ? 'text-success' : 'text-danger' }}">
                    {{ $eval['payment']['ok'] ? 'Soldé' : number_format($eval['payment']['remaining'], 0, ',', ' ') . ' FCFA restants' }}
                </div>
                <span class="badge-custom {{ $eval['payment']['ok'] ? 'status-success' : 'status-danger' }}">
                    {{ $eval['payment']['ok'] ? 'Validé' : 'Non validé' }}
                </span>
            </div>
        </div>

        <div class="criterion-card">
            <div>
                <div class="criterion-label">Rapport de formation</div>
                <div class="criterion-detail">Dépôt requis</div>
            </div>
            <div class="text-end">
                <span class="badge-custom {{ $eval['report_ok'] ? 'status-success' : 'status-danger' }}">
                    {{ $eval['report_ok'] ? 'Déposé' : 'Manquant' }}
                </span>
            </div>
        </div>

        <div class="criterion-card">
            <div>
                <div class="criterion-label">Portfolio</div>
                <div class="criterion-detail">Dépôt requis</div>
            </div>
            <div class="text-end">
                <span class="badge-custom {{ $eval['portfolio_ok'] ? 'status-success' : 'status-danger' }}">
                    {{ $eval['portfolio_ok'] ? 'Déposé' : 'Manquant' }}
                </span>
            </div>
        </div>

        <div class="criterion-card">
            <div>
                <div class="criterion-label">Studio Creative</div>
                <div class="criterion-detail">Validation par l'administration</div>
            </div>
            <div class="text-end">
                @if($eval['studio_creative_status'] === 'validated')
                    <span class="badge-custom status-success">Confirmé</span>
                @elseif($eval['studio_creative_status'] === 'rejected')
                    <span class="badge-custom status-danger">Refusé</span>
                @else
                    <span class="badge-custom status-warning">En cours de vérification</span>
                @endif
            </div>
        </div>

        @if(! $eval['pre_eligible'])
            <div class="info-box">
                <h5 class="mb-3"><i class="fas fa-exclamation-circle me-2 text-warning"></i>Il vous reste des critères à valider</h5>
                <ul class="list-unstyled mb-0">
                    @foreach($eval['missing'] as $missing)
                        <li class="mb-2"><i class="fas fa-circle text-danger me-2" style="font-size:0.5rem;vertical-align:middle"></i>{{ $missing }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif
</div>
@endsection
