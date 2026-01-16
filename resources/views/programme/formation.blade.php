@extends('layouts.ki-admin')

@section('title', 'Programme - ' . ($targetCanonical ?? 'Formation') . ' - EVC 2024')
@section('page-title', 'Programme')

@section('content')
@php
    $now = now();
    $formationLabel = $targetCanonical ?? 'Formation';
    $formationSlug = $slug ?? '';

    $statusLabel = null;
    if (($formationStatus ?? null) === 'en_cours') {
        $statusLabel = 'En cours';
    }
    if (($formationStatus ?? null) === 'terminee') {
        $statusLabel = 'Terminée';
    }
    if (($formationStatus ?? null) === 'a_venir') {
        $statusLabel = 'À venir';
    }
@endphp

<div class="programme-page-bg" aria-hidden="true"></div>

<div class="row mb-4">
    <div class="col-12">
        <div class="formation-header">
            <div class="formation-header-left">
                <a href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.index') }}" class="btn btn-light btn-sm formation-back">
                    <i class="fas fa-arrow-left me-1"></i>
                    Retour
                </a>
                <div>
                    <div class="formation-header-title">{{ $formationLabel }}</div>
                    <div class="formation-header-subtitle">
                        Programme & séances
                        @if(!empty($statusLabel))
                            <span class="formation-status-badge {{ ($formationStatus ?? null) === 'en_cours' ? 'is-running' : ((($formationStatus ?? null) === 'terminee') ? 'is-done' : 'is-upcoming') }}">{{ $statusLabel }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="formation-header-right">
                <a href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.formation', 'design-graphique') }}" class="btn btn-sm {{ ($formationSlug === 'design-graphique' || $formationSlug === 'design_graphique' || $formationSlug === 'design') ? 'btn-primary' : 'btn-outline-light' }}">
                    Design Graphique
                </a>
                <a href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.formation', 'community-management') }}" class="btn btn-sm {{ ($formationSlug === 'community-management' || $formationSlug === 'community_management' || $formationSlug === 'community' || $formationSlug === 'cm') ? 'btn-primary' : 'btn-outline-light' }}">
                    Community Management
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="month-sessions-card">
            <div class="month-sessions-header">
                <div>
                    <div class="month-sessions-title">Séances du mois</div>
                    <div class="month-sessions-subtitle">{{ $now->translatedFormat('F Y') }}</div>
                </div>
                <div class="month-sessions-count">{{ (int) ($currentMonthSessions->count() ?? 0) }}</div>
            </div>

            @if(($currentMonthSessions ?? collect())->isEmpty())
                <div class="month-empty">Aucune séance planifiée pour ce mois.</div>
            @else
                <div class="month-sessions-list">
                    @foreach($currentMonthSessions as $item)
                        @php
                            $typeFormation = $item->type_formation ?? null;
                            $downloadPath = $item->piece_jointe ?? null;
                        @endphp
                        <div class="month-session-row">
                            <div class="month-session-left">
                                <div class="month-session-title">
                                    {{ $item->thematique ?? 'Séance' }}
                                </div>
                                <div class="month-session-meta">
                                    <span>
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ !empty($item->session_date) ? \Carbon\Carbon::parse($item->session_date)->format('d/m/Y') : 'Date à confirmer' }}
                                    </span>
                                    <span class="month-dot">•</span>
                                    <span>
                                        <i class="fas fa-clock me-1"></i>
                                        {{ !empty($item->session_time) ? \Carbon\Carbon::parse($item->session_time)->format('H:i') : 'Heure à confirmer' }}
                                    </span>
                                    @if(($typeFormation ?? null) === 'presentielle' && !empty($item->lieu))
                                        <span class="month-dot">•</span>
                                        <span>
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $item->lieu }}
                                        </span>
                                    @endif
                                </div>
                                @if(!empty($item->programme_title))
                                    <div class="month-session-programme">
                                        <i class="fas fa-book me-1"></i>
                                        {{ $item->programme_title }}
                                    </div>
                                @endif
                            </div>
                            <div class="month-session-right">
                                @if($typeFormation)
                                    <span class="month-type {{ $typeFormation === 'presentielle' ? 'type-presentielle' : 'type-enligne' }}">
                                        {{ $typeFormation === 'presentielle' ? 'Présentielle' : 'En ligne' }}
                                    </span>
                                @endif
                                @if(!empty($downloadPath))
                                    <a class="btn btn-sm btn-primary" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($downloadPath) }}">
                                        <i class="fas fa-download me-1"></i>
                                        Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="all-programmes-card">
            <div class="all-programmes-header">
                <div class="all-programmes-title">Tous les programmes</div>
                <div class="all-programmes-subtitle">Retrouve ici toutes les séances planifiées.</div>
            </div>

            @if(($programmes ?? collect())->isEmpty())
                <div class="all-programmes-empty">Aucun programme disponible pour le moment.</div>
            @else
                <div class="accordion" id="programmeAccordion">
                    @foreach($programmes as $i => $programme)
                        @php
                            $items = $programme->items ?? collect();
                            $itemsCount = (int) ($programme->items_count ?? (is_countable($items) ? count($items) : 0));
                            $monthLabel = !empty($programme->month_start) ? \Carbon\Carbon::parse($programme->month_start)->translatedFormat('F Y') : null;
                        @endphp
                        <div class="accordion-item programme-acc-item">
                            <h2 class="accordion-header" id="heading{{ $i }}">
                                <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }} programme-acc-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $i }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $i }}">
                                    <div class="programme-acc-title">
                                        <div class="programme-acc-name">{{ $programme->titre ?? 'Programme' }}</div>
                                        <div class="programme-acc-meta">
                                            @if(!empty($monthLabel))
                                                <span class="badge badge-soft"><i class="fas fa-calendar me-1"></i>{{ $monthLabel }}</span>
                                            @endif
                                            <span class="badge badge-soft"><i class="fas fa-list me-1"></i>{{ $itemsCount }} séance(s)</span>
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="collapse{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" aria-labelledby="heading{{ $i }}" data-bs-parent="#programmeAccordion">
                                <div class="accordion-body programme-acc-body">
                                    @if(!empty($programme->description))
                                        <div class="programme-desc">{{ $programme->description }}</div>
                                    @endif

                                    @if(!empty($programme->fichier_pdf))
                                        <div class="mb-3">
                                            <a class="btn btn-sm btn-primary" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}">
                                                <i class="fas fa-file-pdf me-1"></i>
                                                Télécharger le programme (PDF)
                                            </a>
                                        </div>
                                    @endif

                                    @if(collect($items)->isEmpty())
                                        <div class="programme-empty">Aucune séance n'a été ajoutée pour ce programme.</div>
                                    @else
                                        <div class="month-sessions-list">
                                            @foreach(collect($items) as $item)
                                                @php
                                                    $typeFormation = $item->type_formation ?? null;
                                                    $downloadPath = $item->piece_jointe ?? null;
                                                @endphp
                                                <div class="month-session-row">
                                                    <div class="month-session-left">
                                                        <div class="month-session-title">{{ $item->thematique ?? 'Séance' }}</div>
                                                        <div class="month-session-meta">
                                                            <span>
                                                                <i class="fas fa-calendar me-1"></i>
                                                                {{ !empty($item->session_date) ? \Carbon\Carbon::parse($item->session_date)->format('d/m/Y') : 'Date à confirmer' }}
                                                            </span>
                                                            <span class="month-dot">•</span>
                                                            <span>
                                                                <i class="fas fa-clock me-1"></i>
                                                                {{ !empty($item->session_time) ? \Carbon\Carbon::parse($item->session_time)->format('H:i') : 'Heure à confirmer' }}
                                                            </span>
                                                            @if(($typeFormation ?? null) === 'presentielle' && !empty($item->lieu))
                                                                <span class="month-dot">•</span>
                                                                <span>
                                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                                    {{ $item->lieu }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        @if(!empty($item->description))
                                                            <div class="programme-desc">{{ $item->description }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="month-session-right">
                                                        @if($typeFormation)
                                                            <span class="month-type {{ $typeFormation === 'presentielle' ? 'type-presentielle' : 'type-enligne' }}">
                                                                {{ $typeFormation === 'presentielle' ? 'Présentielle' : 'En ligne' }}
                                                            </span>
                                                        @endif
                                                        @if(!empty($downloadPath))
                                                            <a class="btn btn-sm btn-primary" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($downloadPath) }}">
                                                                <i class="fas fa-download me-1"></i>
                                                                Télécharger
                                                            </a>
                                                        @endif
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
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
 .programme-page-bg {
     position: fixed;
     inset: 0;
     z-index: -1;
     background: linear-gradient(180deg, #081126 0%, #0b1220 55%, #081126 100%);
 }

 .content-wrapper {
     background: transparent !important;
 }

 .main-content {
     background: transparent !important;
 }

 .formation-status-badge {
     display: inline-flex;
     align-items: center;
     margin-left: 0.5rem;
     padding: 0.18rem 0.55rem;
     border-radius: 999px;
     font-size: 0.75rem;
     font-weight: 950;
     border: 1px solid rgba(255, 255, 255, 0.18);
     background: rgba(2, 6, 23, 0.22);
     color: rgba(255, 255, 255, 0.98);
 }

 .formation-status-badge.is-running {
     background: rgba(16, 185, 129, 0.24);
     border-color: rgba(16, 185, 129, 0.35);
 }

 .formation-status-badge.is-done {
     background: rgba(148, 163, 184, 0.24);
     border-color: rgba(148, 163, 184, 0.35);
 }

 .formation-status-badge.is-upcoming {
     background: rgba(249, 115, 22, 0.24);
     border-color: rgba(249, 115, 22, 0.35);
 }

.formation-header {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.85), rgba(249, 115, 22, 0.85));
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 18px;
    padding: 1.25rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
    box-shadow: 0 20px 55px rgba(2, 6, 23, 0.35);
}

.formation-header-left {
    display: flex;
    align-items: center;
    gap: .85rem;
}

.formation-back {
    border-radius: 12px;
    font-weight: 900;
}

.formation-header-title {
    font-weight: 950;
    color: rgba(255, 255, 255, 0.98);
    font-size: 1.4rem;
    line-height: 1.2;
}

.formation-header-subtitle {
    font-weight: 800;
    color: rgba(255, 255, 255, 0.85);
}

.formation-header-right {
    display: flex;
    gap: .5rem;
    flex-wrap: wrap;
}

 .month-sessions-card {
     background: rgba(15, 23, 42, 0.62);
     border: 1px solid rgba(191, 219, 254, 0.22);
     border-radius: 18px;
     padding: 1rem;
     backdrop-filter: blur(10px);
 }

 .month-sessions-header {
     display: flex;
     align-items: flex-end;
     justify-content: space-between;
     gap: 1rem;
     flex-wrap: wrap;
     margin-bottom: 0.75rem;
 }

 .month-sessions-title {
     font-weight: 950;
     color: rgba(255, 255, 255, 0.98);
     font-size: 1.2rem;
 }

 .month-sessions-subtitle {
     font-weight: 850;
     color: rgba(226, 232, 240, 0.92);
 }

 .month-sessions-count {
     background: linear-gradient(135deg, rgba(37, 99, 235, 0.92), rgba(249, 115, 22, 0.92));
     color: rgba(255, 255, 255, 0.98);
     font-weight: 950;
     border-radius: 999px;
     padding: 0.45rem 0.9rem;
     border: 1px solid rgba(255,255,255,0.18);
 }

 .month-empty {
     padding: 1rem;
     font-weight: 850;
     color: rgba(226, 232, 240, 0.95);
     border: 1px dashed rgba(191, 219, 254, 0.32);
     border-radius: 12px;
 }

 .month-sessions-list {
     display: flex;
     flex-direction: column;
     gap: 0.65rem;
 }

 .month-session-row {
     display: flex;
     align-items: flex-start;
     justify-content: space-between;
     gap: 1rem;
     padding: 0.95rem;
     border-radius: 14px;
     background: rgba(2, 6, 23, 0.30);
     border: 1px solid rgba(191, 219, 254, 0.20);
 }

 .month-session-title {
     font-weight: 950;
     color: rgba(255, 255, 255, 0.98);
 }

 .month-session-meta {
     margin-top: 0.25rem;
     display: flex;
     gap: 0.6rem;
     flex-wrap: wrap;
     align-items: center;
     font-weight: 850;
     color: rgba(226, 232, 240, 0.92);
 }

 .month-dot {
     opacity: 0.75;
 }

 .month-session-right {
     display: flex;
     gap: 0.6rem;
     align-items: center;
     flex-wrap: wrap;
 }

 .month-type {
     padding: 0.25rem 0.6rem;
     border-radius: 999px;
     font-size: 0.75rem;
     font-weight: 950;
     border: 1px solid rgba(255,255,255,0.18);
     color: rgba(255, 255, 255, 0.98);
 }

 .type-presentielle {
     background: rgba(249, 115, 22, 0.24);
 }

 .type-enligne {
     background: rgba(37, 99, 235, 0.24);
 }

.all-programmes-card {
    background: rgba(15, 23, 42, 0.55);
    border: 1px solid rgba(191, 219, 254, 0.18);
    border-radius: 18px;
    padding: 1rem 1rem;
    backdrop-filter: blur(10px);
}

.all-programmes-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: .75rem;
}

.all-programmes-title {
    font-weight: 950;
    color: rgba(255, 255, 255, 0.98);
    font-size: 1.25rem;
}

.all-programmes-subtitle {
    font-weight: 800;
    color: rgba(219, 234, 254, 0.88);
}

.all-programmes-empty {
    padding: 1rem;
    color: rgba(219, 234, 254, 0.9);
    font-weight: 800;
}

.programme-acc-item {
    background: transparent;
    border: 1px solid rgba(191, 219, 254, 0.18);
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: .85rem;
}

.programme-acc-btn {
    background: rgba(2, 6, 23, 0.25);
    color: rgba(255, 255, 255, 0.95);
    font-weight: 900;
}

.programme-acc-btn:focus {
    box-shadow: none;
}

.programme-acc-title {
    display: flex;
    flex-direction: column;
    gap: .35rem;
    width: 100%;
}

.programme-acc-name {
    font-size: 1.05rem;
    font-weight: 950;
}

.programme-acc-meta {
    display: flex;
    gap: .4rem;
    flex-wrap: wrap;
}

.programme-acc-body {
    background: rgba(2, 6, 23, 0.22);
    color: rgba(255, 255, 255, 0.92);
}

.programme-desc {
    color: rgba(219, 234, 254, 0.92);
    font-weight: 700;
    margin-bottom: .75rem;
}

.programme-empty {
    padding: .75rem;
    font-weight: 800;
    color: rgba(219, 234, 254, 0.9);
    border: 1px dashed rgba(191, 219, 254, 0.25);
    border-radius: 12px;
}

.month-session-programme {
    margin-top: .35rem;
    font-weight: 800;
    color: rgba(219, 234, 254, 0.9);
}
</style>
@endpush
