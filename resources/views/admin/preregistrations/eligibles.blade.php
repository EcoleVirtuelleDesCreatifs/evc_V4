@extends('layouts.admin')

@section('title', 'Pré-inscriptions - Éligibles')

@section('content')
<div class="container-fluid py-4">
    @php
        $filterAction = route('admin.preinscriptions.eligibles');
        $hasEligibilityNotifiedAt = \Illuminate\Support\Facades\Schema::hasColumn('pre_registrations', 'eligibility_notified_at');
        $formationLabels = [
            'design_graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'design_graphique_community_manager' => 'Design Graphique & Community Manager',
            'gestion_informatique' => 'Gestion Informatique',
            'intelligence_artificielle' => 'Intelligence Artificielle',
            'design_cm' => 'Design Graphique & Community Management',
            'design_graphique_community_management' => 'Design Graphique & Community Management',
        ];
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h1 class="text-white mb-0">
                <i class="fas fa-user-check me-2"></i>Pré-inscriptions - Éligibles
            </h1>
            <div style="color: rgba(255,255,255,0.75);">Candidatures avec une date de paiement choisie.</div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form method="GET" action="{{ $filterAction }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche (nom, prénom, email, whatsapp)" class="form-control" />
                </div>
                <div class="col-auto">
                    <select name="formation" class="form-select">
                        <option value="">Toutes formations</option>
                        <option value="design_graphique" @selected(request('formation')==='design_graphique')>Design Graphique</option>
                        <option value="community_management" @selected(request('formation')==='community_management')>Community Management</option>
                        <option value="design_graphique_community_manager" @selected(request('formation')==='design_graphique_community_manager')>Design Graphique & Community Manager</option>
                        <option value="gestion_informatique" @selected(request('formation')==='gestion_informatique')>Gestion Informatique</option>
                        <option value="intelligence_artificielle" @selected(request('formation')==='intelligence_artificielle')>Intelligence Artificielle</option>
                    </select>
                </div>
                @if($hasEligibilityNotifiedAt)
                    <div class="col-auto">
                        <select name="notified" class="form-select">
                            <option value="">Tous (mail)</option>
                            <option value="0" @selected(request('notified')==='0')>Non notifiés</option>
                            <option value="1" @selected(request('notified')==='1')>Déjà notifiés</option>
                        </select>
                    </div>
                @endif
                <div class="col-auto">
                    <button class="btn btn-primary" style="border-radius: 12px;"><i class="fas fa-filter me-2"></i>Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="stat-icon">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                    <p>Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);">
                <div class="stat-icon">
                    <i class="fas fa-paper-plane"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['notified'] ?? 0 }}</h3>
                    <p>Notifiés</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['not_notified'] ?? 0 }}</h3>
                    <p>Non notifiés</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 70px;">Photo</th>
                            <th>Candidat</th>
                            <th>Formation</th>
                            <th>Date paiement</th>
                            <th>Statut</th>
                            <th>Notification</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pres as $pre)
                            <tr>
                                <td>{{ $pre->id }}</td>
                                <td>
                                    @php
                                        $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrl($pre->photo ?? null);
                                    @endphp
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="Photo" class="img-thumbnail" style="width: 44px; height: 44px; object-fit: cover; border-radius: 10px;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; border-radius: 10px;">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 800;">{{ $pre->prenom }} {{ $pre->nom }}</div>
                                    <div class="text-muted" style="font-size: 13px;">{{ $pre->email }}{{ !empty($pre->whatsapp) ? ' • ' . $pre->whatsapp : '' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $formationLabels[$pre->choix_formation] ?? ($pre->choix_formation ?? 'N/A') }}</span>
                                </td>
                                <td>
                                    @if(!empty($pre->date_inscription_souhaitee))
                                        {{ \Carbon\Carbon::parse($pre->date_inscription_souhaitee)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $pre->status }}</span>
                                </td>
                                <td>
                                    @if($hasEligibilityNotifiedAt)
                                        @if(!empty($pre->eligibility_notified_at))
                                            <span class="badge bg-success">Oui</span>
                                            <div class="text-muted" style="font-size: 12px;">{{ \Carbon\Carbon::parse($pre->eligibility_notified_at)->format('d/m/Y H:i') }}</div>
                                        @else
                                            <span class="badge bg-warning text-dark">Non</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.preinscriptions.notify-eligible', $pre->id) }}">
                                        @csrf
                                        @php
                                            $isRelance = $hasEligibilityNotifiedAt && !empty($pre->eligibility_notified_at);
                                        @endphp
                                        <button type="submit" class="btn btn-sm {{ $isRelance ? 'btn-outline-warning' : 'btn-success' }}" style="border-radius: 10px; font-weight: 800;">
                                            <i class="fas {{ $isRelance ? 'fa-rotate' : 'fa-paper-plane' }} me-1"></i>{{ $isRelance ? 'Relance' : 'Envoyer mail' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Aucune candidature éligible trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pres->hasPages())
                <div class="d-flex justify-content-end">
                    {{ $pres->links('pagination::bootstrap-4') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
