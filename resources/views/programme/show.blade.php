@extends('layouts.ki-admin')

@section('title', 'Programme - ' . (($programme->titre ?? null) ?: 'Détail') . ' - EVC 2024')
@section('page-title', 'Programme')

@section('content')
@php
    $itemsCollection = $items ?? ($programme->items ?? collect());
    if (!($itemsCollection instanceof \Illuminate\Support\Collection)) {
        $itemsCollection = collect($itemsCollection);
    }

    $formationPrefixSafe = $formationPrefix ?? 'design-graphique';

    $dashboardRouteNameByPrefix = [
        'design-graphique' => 'dashboard.design-graphique',
        'design-graphique-cm' => 'dashboard.design-graphique-cm',
        'community-management' => 'dashboard.community-management',
    ];

    $dashboardRouteName = $dashboardRouteNameByPrefix[$formationPrefixSafe] ?? 'dashboard.design-graphique';
@endphp

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '›';">
                <li class="breadcrumb-item">
                    <a href="{{ route($dashboardRouteName) }}" class="text-decoration-none">Espace étudiant</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route($formationPrefixSafe . '.programme.index') }}" class="text-decoration-none">Programmes</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{ $programme->titre ?? 'Programme' }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h3 class="mb-1" style="font-weight: 900;">{{ $programme->titre ?? 'Programme' }}</h3>
                <div class="text-muted">{{ $programme->formation ?? '' }}</div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route($formationPrefixSafe . '.programme.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour
                </a>
                @if(!empty($programme->fichier_pdf))
                    <a class="btn btn-sm btn-primary" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}">
                        <i class="fas fa-file-pdf me-1"></i>
                        Télécharger le programme
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius: 18px; background: rgba(15, 23, 42, 0.55); border: 1px solid rgba(255,255,255,0.10);">
            <div class="card-header" style="background: transparent; border-bottom: 1px solid rgba(255,255,255,0.10);">
                <div class="d-flex justify-content-between align-items-center">
                    <div style="font-weight: 900;">Séances</div>
                    <span class="badge bg-light text-dark">{{ $itemsCollection->count() }}</span>
                </div>
            </div>
            <div class="card-body">
                @if($itemsCollection->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucune séance</h5>
                        <p class="text-muted mb-0">Ce programme ne contient pas encore de séances.</p>
                    </div>
                @else
                    <div class="d-flex flex-column gap-3">
                        @foreach($itemsCollection as $item)
                            @php
                                $typeFormation = $item->type_formation ?? null;
                                $downloadPath = $item->piece_jointe ?? null;
                            @endphp

                            <div class="p-3" style="border-radius: 16px; border: 1px solid rgba(255,255,255,0.10); background: rgba(11, 31, 68, 0.35);">
                                <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                    <div>
                                        <div style="font-weight: 900;">{{ $item->thematique ?? 'Séance' }}</div>

                                        <div class="text-muted small mt-1">
                                            <span class="me-2">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ !empty($item->session_date) ? \Carbon\Carbon::parse($item->session_date)->format('d/m/Y') : 'Date à confirmer' }}
                                            </span>
                                            <span class="me-2">•</span>
                                            <span>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ !empty($item->session_time) ? \Carbon\Carbon::parse($item->session_time)->format('H:i') : 'Heure à confirmer' }}
                                            </span>
                                            @if(($typeFormation ?? null) === 'presentielle' && !empty($item->lieu))
                                                <span class="me-2">•</span>
                                                <span>
                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                    {{ $item->lieu }}
                                                </span>
                                            @endif
                                        </div>

                                        @if(!empty($item->description))
                                            <div class="text-muted small mt-2">{{ $item->description }}</div>
                                        @endif

                                        @if($typeFormation)
                                            <div class="mt-2">
                                                <span class="badge {{ $typeFormation === 'presentielle' ? 'bg-info' : 'bg-primary' }}">
                                                    {{ $typeFormation === 'presentielle' ? 'Présentielle' : 'En ligne' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div>
                                        @if(!empty($downloadPath))
                                            <a class="btn btn-sm btn-primary" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($downloadPath) }}">
                                                <i class="fas fa-download me-1"></i>
                                                Télécharger
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                <i class="fas fa-ban me-1"></i>
                                                Aucun fichier
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
