@extends('layouts.ki-admin')

@section('title', 'Programmes de Formation - EVC 2024')
@section('page-title', 'Programmes de Formation')

@section('content')
@php
    $studentProgram = (string) ($student->program ?? '');
    $studentProgramLower = strtolower($studentProgram);
    $isDgCm = str_contains($studentProgramLower, 'design') && (str_contains($studentProgramLower, 'community') || str_contains($studentProgramLower, 'cm'));

    $routeName = request()->route() ? (request()->route()->getName() ?? '') : '';
    $formationPrefix = $formationPrefix ?? (string) (session('user_formation') ?? 'design-graphique');
    if (str_contains($routeName, 'design-graphique-cm')) {
        $formationPrefix = 'design-graphique-cm';
    } elseif (str_contains($routeName, 'community-management') || str_contains($routeName, 'community-manager')) {
        $formationPrefix = 'community-management';
    } elseif (str_contains($routeName, 'intelligence-artificielle')) {
        $formationPrefix = 'intelligence-artificielle';
    } elseif (str_contains($routeName, 'gestion-informatique')) {
        $formationPrefix = 'gestion-informatique';
    }

    $dashboardRoute = match ($formationPrefix) {
        'design-graphique-cm' => 'dashboard.design-graphique-cm',
        'community-management' => 'dashboard.community-management',
        'intelligence-artificielle' => 'dashboard.intelligence-artificielle',
        'gestion-informatique' => 'dashboard.gestion-informatique',
        default => 'dashboard.design-graphique',
    };

    $dgCount = $programmes->where('canonical_formation', 'Design Graphique')->count();
    $cmCount = $programmes->where('canonical_formation', 'Community Management')->count();
    $now = now();
    $currentMonthCount = $programmes->filter(function ($p) use ($now) {
        try {
            if (!empty($p->month_start) && \Carbon\Carbon::parse($p->month_start)->isSameMonth($now)) {
                return true;
            }
        } catch (\Throwable $e) {
            // ignore
        }

        $items = $p->items ?? collect();
        return collect($items)->contains(function ($it) use ($now) {
            try {
                return !empty($it->session_date) && \Carbon\Carbon::parse($it->session_date)->isSameMonth($now);
            } catch (\Throwable $e) {
                return false;
            }
        });
    })->count();

    $formationProgrammeSlug = 'design-graphique';
    if ($formationPrefix === 'community-management') {
        $formationProgrammeSlug = 'community-management';
    }
@endphp
<div class="programme-page-bg" aria-hidden="true"></div>
<div class="programme-hero-wrap mb-4">
    <div class="container-fluid">
        <div class="programme-hero-inner">
            <div class="text-center">
                <nav aria-label="breadcrumb" class="programme-breadcrumb">
                    <ol class="breadcrumb justify-content-center mb-3">
                        <li class="breadcrumb-item">
                            <a href="{{ route($dashboardRoute) }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Programme</li>
                    </ol>
                </nav>

                <h1 class="programme-hero-h1">Programmes de formation</h1>
                <div class="programme-hero-lead">{{ $student->program ?? 'Votre formation' }}</div>

                <div class="programme-count-chip mt-4">
                    <i class="fas fa-book-open"></i>
                    <span>{{ $programmes->count() }} programme{{ $programmes->count() > 1 ? 's' : '' }}</span>
                </div>

                <div class="mt-3 d-flex justify-content-center gap-2 flex-wrap">
                    @if($isDgCm)
                        @if(\Illuminate\Support\Facades\Route::has(($formationPrefix ?? 'design-graphique') . '.programme.formation'))
                            <a class="btn btn-sm btn-outline-light" href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.formation', 'design-graphique') }}">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Voir les séances (DG)
                            </a>
                            <a class="btn btn-sm btn-outline-light" href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.formation', 'community-management') }}">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Voir les séances (CM)
                            </a>
                        @endif
                    @else
                        @if(\Illuminate\Support\Facades\Route::has(($formationPrefix ?? 'design-graphique') . '.programme.formation'))
                            <a class="btn btn-sm btn-outline-light" href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.formation', $formationProgrammeSlug) }}">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Voir les séances
                            </a>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="programme-cards-grid">
            <div class="row g-4">
                @foreach($programmes as $index => $programme)
                    @php
                        $items = $programme->items ?? collect();
                        $itemsCount = (int) ($programme->items_count ?? (is_countable($items) ? count($items) : 0));
                        $monthLabel = null;
                        try {
                            $monthLabel = !empty($programme->month_start) ? \Carbon\Carbon::parse($programme->month_start)->translatedFormat('F Y') : null;
                        } catch (\Throwable $e) {
                            $monthLabel = null;
                        }

                        $pStatus = $programme->status ?? null;
                        $pStatusLabel = $pStatus === 'en_cours' ? 'En cours' : ($pStatus === 'terminee' ? 'Terminée' : 'À venir');
                        $pStatusBadge = $pStatus === 'en_cours' ? 'badge-warning' : ($pStatus === 'terminee' ? 'badge-success' : 'badge-info');
                    @endphp
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="programme-card-modern">
                            <div class="programme-card-top">
                                <div class="programme-card-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="programme-card-meta">
                                    @if($monthLabel)
                                        <span class="programme-chip">{{ $monthLabel }}</span>
                                    @endif
                                    <span class="programme-chip programme-chip-soft">{{ $itemsCount }} séance{{ $itemsCount > 1 ? 's' : '' }}</span>
                                    <span class="programme-chip {{ $pStatusBadge }}">{{ $pStatusLabel }}</span>
                                </div>
                            </div>

                            <div class="programme-card-body">
                                <div class="programme-card-title">{{ $programme->titre ?? 'Programme' }}</div>
                                @if(!empty($programme->description))
                                    <div class="programme-card-desc">{{ \Illuminate\Support\Str::limit($programme->description, 130) }}</div>
                                @else
                                    <div class="programme-card-desc programme-card-desc-muted">Téléchargez le programme pour consulter le détail.</div>
                                @endif
                            </div>

                            <div class="programme-card-actions">
                                @if(!empty($programme->id) && \Illuminate\Support\Facades\Route::has(($formationPrefix ?? 'design-graphique') . '.programme.show'))
                                    <a class="btn btn-sm btn-outline-light" href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.show', (int) $programme->id) }}">
                                        Voir les séances
                                    </a>
                                @endif
                                @if(!empty($programme->fichier_pdf))
                                    <a class="btn btn-sm btn-primary" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}">
                                        Télécharger
                                    </a>
                                @else
                                    <span class="btn btn-sm btn-secondary disabled">PDF indisponible</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@if($isDgCm)
    @php
        $dgStatus = $formationStatuses['Design Graphique'] ?? null;
        $cmStatus = $formationStatuses['Community Management'] ?? null;

        $statusLabel = function ($s) {
            if ($s === 'en_cours') return 'En cours';
            if ($s === 'terminee') return 'Terminée';
            if ($s === 'a_venir') return 'À venir';
            return null;
        };

        $statusClass = function ($s) {
            if ($s === 'en_cours') return 'is-running';
            if ($s === 'terminee') return 'is-done';
            if ($s === 'a_venir') return 'is-upcoming';
            return '';
        };
    @endphp
    <div class="row g-3 mb-4" id="programmeFormationCards">
        <div class="col-12 col-md-6">
            <a href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.formation', 'design-graphique') }}" class="text-decoration-none">
                <div class="formation-card formation-card-dg">
                    <div class="formation-card-top">
                        <div>
                            <div class="formation-card-label">Formation</div>
                            <div class="formation-card-title">Design Graphique</div>
                            @if($statusLabel($dgStatus))
                                <div class="formation-status-badge {{ $statusClass($dgStatus) }}">{{ $statusLabel($dgStatus) }}</div>
                            @endif
                        </div>
                        <div class="formation-card-count">{{ $dgCount }}</div>
                    </div>
                    <div class="formation-card-cta">Voir le programme</div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6">
            <a href="{{ route(($formationPrefix ?? 'design-graphique') . '.programme.formation', 'community-management') }}" class="text-decoration-none">
                <div class="formation-card formation-card-cm">
                    <div class="formation-card-top">
                        <div>
                            <div class="formation-card-label">Formation</div>
                            <div class="formation-card-title">Community Management</div>
                            @if($statusLabel($cmStatus))
                                <div class="formation-status-badge {{ $statusClass($cmStatus) }}">{{ $statusLabel($cmStatus) }}</div>
                            @endif
                        </div>
                        <div class="formation-card-count">{{ $cmCount }}</div>
                    </div>
                    <div class="formation-card-cta">Voir le programme</div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="all-programmes-card">
                <div class="all-programmes-header">
                    <div>
                        <div class="all-programmes-title">Tous les programmes</div>
                        <div class="all-programmes-subtitle">Retrouve ici tous les programmes (tous les mois).</div>
                    </div>
                </div>

                @if(($programmes ?? collect())->isEmpty())
                    <div class="all-programmes-empty">Aucun programme disponible pour le moment.</div>
                @else
                    <div class="accordion" id="programmeAllAccordion">
                        @foreach($programmes as $i => $programme)
                            @php
                                $items = $programme->items ?? collect();
                                $itemsCount = (int) ($programme->items_count ?? (is_countable($items) ? count($items) : 0));
                                $monthLabel = !empty($programme->month_start) ? \Carbon\Carbon::parse($programme->month_start)->translatedFormat('F Y') : null;

                                $pStatus = $programme->status ?? null;
                                $pStatusLabel = $pStatus === 'en_cours' ? 'En cours' : ($pStatus === 'terminee' ? 'Terminée' : 'À venir');
                                $pStatusClass = $pStatus === 'en_cours' ? 'is-running' : ($pStatus === 'terminee' ? 'is-done' : 'is-upcoming');
                            @endphp
                            <div class="accordion-item programme-acc-item">
                                <h2 class="accordion-header" id="allHeading{{ $i }}">
                                    <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }} programme-acc-btn" type="button" data-bs-toggle="collapse" data-bs-target="#allCollapse{{ $i }}" aria-expanded="{{ $i === 0 ? 'true' : 'false' }}" aria-controls="allCollapse{{ $i }}">
                                        <div class="programme-acc-title">
                                            <div class="programme-acc-name">
                                                {{ $programme->titre ?? 'Programme' }}
                                                <span class="programme-status-badge {{ $pStatusClass }}">{{ $pStatusLabel }}</span>
                                            </div>
                                            <div class="programme-acc-meta">
                                                @if(!empty($monthLabel))
                                                    <span class="badge badge-soft"><i class="fas fa-calendar me-1"></i>{{ $monthLabel }}</span>
                                                @endif
                                                <span class="badge badge-soft"><i class="fas fa-list me-1"></i>{{ $itemsCount }} séance(s)</span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="allCollapse{{ $i }}" class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}" aria-labelledby="allHeading{{ $i }}" data-bs-parent="#programmeAllAccordion">
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
                                                        $sessionRowClass = '';
                                                        try {
                                                            if (!empty($item->session_date)) {
                                                                $dt = \Carbon\Carbon::parse(($item->session_date ?? '') . ' ' . ($item->session_time ?? '00:00'));
                                                                $sessionRowClass = $dt->isFuture() ? 'is-future' : 'is-past';
                                                            }
                                                        } catch (\Throwable $e) {
                                                        }
                                                    @endphp
                                                    <div class="month-session-row {{ $sessionRowClass }}">
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
                                $canonical = (string) ($item->canonical_formation ?? '');
                                $canonicalLower = strtolower($canonical);
                                $tag = str_contains($canonicalLower, 'community') ? 'CM' : (str_contains($canonicalLower, 'design') ? 'DG' : '');
                                $typeFormation = $item->type_formation ?? null;
                                $sessionRowClass = '';
                                try {
                                    if (!empty($item->session_date)) {
                                        $dt = \Carbon\Carbon::parse(($item->session_date ?? '') . ' ' . ($item->session_time ?? '00:00'));
                                        $sessionRowClass = $dt->isFuture() ? 'is-future' : 'is-past';
                                    }
                                } catch (\Throwable $e) {
                                }
                            @endphp
                            <div class="month-session-row {{ $sessionRowClass }}">
                                <div class="month-session-left">
                                    <div class="month-session-title">
                                        {{ $item->thematique ?? 'Séance' }}
                                        @if($tag !== '')
                                            <span class="month-tag">{{ $tag }}</span>
                                        @endif
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
                                </div>
                                <div class="month-session-right">
                                    @if($typeFormation)
                                        <span class="month-type {{ $typeFormation === 'presentielle' ? 'type-presentielle' : 'type-enligne' }}">
                                            {{ $typeFormation === 'presentielle' ? 'Présentielle' : 'En ligne' }}
                                        </span>
                                    @endif

                                    @php
                                        $downloadPath = $item->piece_jointe ?? null;
                                    @endphp
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
@endif

@endsection

@push('styles')
<style>
/* Palette (blue) */
:root {
    --evc-blue-950: #0b1220;
    --evc-blue-900: #0b1f44;
    --evc-blue-700: #1d4ed8;
    --evc-blue-600: #2563eb;
    --evc-blue-500: #3b82f6;
    --evc-blue-200: #bfdbfe;
    --evc-blue-100: #dbeafe;
    --evc-surface: #0b1f44;
    --evc-surface-soft: #0e2a5a;
    --evc-border: rgba(191, 219, 254, 0.18);
}

 .programme-hero-wrap {
     border-radius: 22px;
     background: linear-gradient(180deg, rgba(0, 0, 51, 0.92) 0%, rgba(0, 0, 102, 0.92) 100%);
     border: 1px solid rgba(255,255,255,0.10);
     position: relative;
     overflow: hidden;
 }

 .programme-hero-wrap::before {
     content: '';
     position: absolute;
     inset: -2px;
     background: radial-gradient(circle at 20% 20%, rgba(249, 115, 22, 0.22), transparent 45%),
                 radial-gradient(circle at 80% 20%, rgba(59, 130, 246, 0.18), transparent 40%);
     pointer-events: none;
 }

 .programme-hero-inner {
     padding: 2.2rem 1.5rem;
     position: relative;
     z-index: 1;
 }

 .programme-breadcrumb .breadcrumb-item + .breadcrumb-item::before {
     color: rgba(255,255,255,0.35);
 }

 .programme-breadcrumb a {
     color: rgba(255,255,255,0.75);
     font-weight: 700;
     text-decoration: none;
 }

 .programme-breadcrumb a:hover {
     color: #f97316;
 }

 .programme-hero-h1 {
     color: #fff;
     font-weight: 900;
     letter-spacing: -0.02em;
     margin-bottom: .4rem;
     font-size: 2rem;
 }

 .programme-hero-lead {
     color: rgba(255,255,255,0.82);
     font-weight: 700;
 }

 .programme-count-chip {
     display: inline-flex;
     align-items: center;
     gap: .55rem;
     padding: .75rem 1.1rem;
     border-radius: 999px;
     background: rgba(255,255,255,0.10);
     border: 1px solid rgba(255,255,255,0.18);
     color: #fff;
     font-weight: 800;
     backdrop-filter: blur(10px);
 }

 .programme-cards-grid {
     margin-bottom: 1rem;
 }

 .programme-card-modern {
     border-radius: 18px;
     background: rgba(15, 23, 42, 0.55);
     border: 1px solid rgba(255,255,255,0.10);
     box-shadow: 0 18px 45px rgba(0,0,0,0.20);
     overflow: hidden;
     height: 100%;
     display: flex;
     flex-direction: column;
 }

 .programme-card-top {
     padding: 1rem 1rem 0.75rem;
     display: flex;
     gap: .85rem;
     align-items: flex-start;
 }

 .programme-card-icon {
     width: 46px;
     height: 46px;
     border-radius: 14px;
     display: flex;
     align-items: center;
     justify-content: center;
     background: rgba(249, 115, 22, 0.18);
     border: 1px solid rgba(249, 115, 22, 0.25);
     color: #f97316;
     flex: 0 0 auto;
 }

 .programme-card-meta {
     display: flex;
     gap: .4rem;
     flex-wrap: wrap;
 }

 .programme-chip {
     display: inline-flex;
     align-items: center;
     padding: .35rem .7rem;
     border-radius: 999px;
     font-size: .75rem;
     font-weight: 800;
     color: #fff;
     background: rgba(255,255,255,0.10);
     border: 1px solid rgba(255,255,255,0.14);
 }

 .programme-chip-soft {
     color: rgba(255,255,255,0.85);
 }

 .programme-chip.badge-warning {
     background: rgba(255,152,0,0.20);
     border-color: rgba(255,152,0,0.30);
 }

 .programme-chip.badge-success {
     background: rgba(16,185,129,0.18);
     border-color: rgba(16,185,129,0.28);
 }

 .programme-chip.badge-info {
     background: rgba(59,130,246,0.18);
     border-color: rgba(59,130,246,0.28);
 }

 .programme-card-body {
     padding: 0 1rem 1rem;
     flex: 1 1 auto;
 }

 .programme-card-title {
     color: #fff;
     font-weight: 900;
     letter-spacing: -0.01em;
     margin-bottom: .5rem;
 }

 .programme-card-desc {
     color: rgba(255,255,255,0.78);
     font-weight: 600;
     font-size: .92rem;
 }

 .programme-card-desc-muted {
     color: rgba(255,255,255,0.55);
 }

 .programme-card-actions {
     padding: 0 1rem 1rem;
 }

 @media (max-width: 768px) {
     .programme-hero-inner {
         padding: 1.6rem 1rem;
     }
     .programme-hero-h1 {
         font-size: 1.6rem;
     }
 }

.programme-hero {
    display: flex;
    gap: 1rem;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

.programme-hero-left {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.programme-hero-title {
    font-weight: 900;
    letter-spacing: 0.01em;
    font-size: 1.65rem;
    line-height: 1.15;
}

.programme-hero-subtitle {
    color: rgba(255, 255, 255, 0.85);
    font-weight: 700;
}

.programme-hero-kpis {
    display: flex;
    gap: .6rem;
    align-items: stretch;
    flex-wrap: wrap;
}

.kpi-chip {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.16);
    border-radius: 14px;
    padding: .6rem .75rem;
    min-width: 112px;
}

.kpi-label {
    font-size: .78rem;
    font-weight: 800;
    color: rgba(255, 255, 255, 0.78);
    text-transform: uppercase;
    letter-spacing: 0.08em;
}

.kpi-value {
    font-size: 1.2rem;
    font-weight: 900;
    color: rgba(255, 255, 255, 0.98);
    line-height: 1.15;
}

.programme-card-header {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    margin-bottom: 0.75rem;
}

.programme-card-meta {
    color: rgba(219, 234, 254, 0.82);
    font-weight: 700;
    font-size: .9rem;
}

.sessions-subheader {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sessions-subtitle {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    font-weight: 900;
    color: rgba(255, 255, 255, 0.92);
    letter-spacing: 0.01em;
}

.empty-title {
    color: rgba(255, 255, 255, 0.95);
    font-weight: 900;
}

.empty-subtitle {
    color: rgba(219, 234, 254, 0.82);
    font-weight: 700;
}

.tools-input::placeholder {
    color: rgba(219, 234, 254, 0.65);
}

/* Fond (isolé à cette page) */
.programme-page-bg {
    position: fixed;
    inset: 0;
    z-index: -1;
    background:
        linear-gradient(180deg, #081126 0%, #0b1220 55%, #081126 100%);
}

/* IMPORTANT: le layout met un fond opaque sur .content-wrapper, on le rend transparent uniquement pour cette page */
.content-wrapper {
    background: transparent !important;
}

/* Donne un “glow” Instagram au container principal */
.main-content {
    background: transparent !important;
}

.row, .container, .container-fluid {
    position: relative;
}

@media (prefers-reduced-motion: no-preference) {
    .programme-page-bg {
        background-size: 140% 140%;
        animation: bgShift 10s ease-in-out infinite alternate;
    }
}

@keyframes bgShift {
    from { background-position: 0% 0%, 100% 0%, 100% 100%, 0% 0%; }
    to { background-position: 20% 10%, 80% 15%, 85% 80%, 0% 0%; }
}

/* Header avec dégradé Instagram */
.instagram-header {
    background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-600));
    border-radius: 20px;
    color: white;
    box-shadow: 0 14px 40px rgba(2, 6, 23, 0.45);
    animation: fadeInDown 0.6s ease;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

.programme-formation-card {
    color: #fff;
}

.programme-formation-card[data-programme-filter] {
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.programme-formation-card[data-programme-filter].is-active {
    border-color: rgba(255, 255, 255, 0.50);
    box-shadow: 0 18px 45px rgba(2, 6, 23, 0.35);
}

.programme-formation-card-dg {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(29, 78, 216, 0.90));
}

.programme-formation-card-cm {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.92), rgba(29, 78, 216, 0.86));
}

.programme-formation-card-current {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.88), rgba(29, 78, 216, 0.82));
}

 .formation-card {
     border-radius: 18px;
     padding: 1.15rem 1.2rem;
     border: 1px solid rgba(255, 255, 255, 0.16);
     box-shadow: 0 18px 55px rgba(2, 6, 23, 0.35);
     transition: transform 0.25s ease, box-shadow 0.25s ease;
     position: relative;
     overflow: hidden;
 }

 .formation-card::before {
     content: '';
     position: absolute;
     inset: -2px;
     background:
         radial-gradient(600px 220px at 20% 20%, rgba(255,255,255,0.35), transparent 55%),
         radial-gradient(480px 220px at 80% 0%, rgba(255,255,255,0.18), transparent 60%);
     pointer-events: none;
 }

 .formation-card:hover {
     transform: translateY(-2px);
     box-shadow: 0 24px 70px rgba(2, 6, 23, 0.48);
 }

 .formation-card-top {
     display: flex;
     align-items: flex-start;
     justify-content: space-between;
     gap: 1rem;
 }

 .formation-card-label {
     color: rgba(255, 255, 255, 0.78);
     font-weight: 900;
     text-transform: uppercase;
     letter-spacing: 0.08em;
     font-size: 0.78rem;
 }

 .formation-card-title {
     color: rgba(255, 255, 255, 0.98);
     font-weight: 950;
     font-size: 1.2rem;
     line-height: 1.15;
     margin-top: 0.2rem;
 }

 .formation-status-badge {
     display: inline-flex;
     align-items: center;
     margin-top: 0.5rem;
     padding: 0.22rem 0.55rem;
     border-radius: 999px;
     font-size: 0.75rem;
     font-weight: 950;
     border: 1px solid rgba(255, 255, 255, 0.18);
     background: rgba(2, 6, 23, 0.22);
     color: rgba(255, 255, 255, 0.98);
 }

 .formation-status-badge.is-running {
     background: rgba(16, 185, 129, 0.22);
     border-color: rgba(16, 185, 129, 0.35);
 }

 .formation-status-badge.is-done {
     background: rgba(148, 163, 184, 0.22);
     border-color: rgba(148, 163, 184, 0.35);
 }

 .formation-status-badge.is-upcoming {
     background: rgba(249, 115, 22, 0.22);
     border-color: rgba(249, 115, 22, 0.35);
 }

 .formation-card-count {
     background: rgba(255, 255, 255, 0.18);
     border: 1px solid rgba(255, 255, 255, 0.20);
     border-radius: 999px;
     padding: 0.5rem 0.9rem;
     font-weight: 950;
     color: rgba(255, 255, 255, 0.98);
 }

 .formation-card-cta {
     margin-top: 1rem;
     display: inline-flex;
     align-items: center;
     gap: 0.5rem;
     font-weight: 950;
     color: rgba(255, 255, 255, 0.98);
 }

 .formation-card-dg {
     background: linear-gradient(135deg, rgba(37, 99, 235, 0.92), rgba(249, 115, 22, 0.90));
 }

 .formation-card-cm {
     background: linear-gradient(135deg, rgba(249, 115, 22, 0.92), rgba(37, 99, 235, 0.90));
 }

 .month-sessions-card {
     background: rgba(15, 23, 42, 0.55);
     border: 1px solid rgba(191, 219, 254, 0.18);
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
     font-weight: 800;
     color: rgba(219, 234, 254, 0.88);
 }

 .month-sessions-count {
     background: linear-gradient(135deg, rgba(37, 99, 235, 0.9), rgba(249, 115, 22, 0.9));
     color: rgba(255, 255, 255, 0.98);
     font-weight: 950;
     border-radius: 999px;
     padding: 0.45rem 0.9rem;
     border: 1px solid rgba(255,255,255,0.18);
 }

 .month-empty {
     padding: 1rem;
     font-weight: 800;
     color: rgba(219, 234, 254, 0.9);
     border: 1px dashed rgba(191, 219, 254, 0.25);
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
     padding: 0.9rem;
     border-radius: 14px;
     background: rgba(2, 6, 23, 0.22);
     border: 1px solid rgba(191, 219, 254, 0.16);
 }

 .month-session-row.is-future {
     background: rgba(16, 185, 129, 0.10);
     border-color: rgba(16, 185, 129, 0.38);
 }

 .month-session-row.is-past {
     opacity: 0.72;
 }

 .month-session-title {
     font-weight: 950;
     color: rgba(255, 255, 255, 0.96);
 }

 .month-session-meta {
     margin-top: 0.25rem;
     display: flex;
     gap: 0.6rem;
     flex-wrap: wrap;
     align-items: center;
     font-weight: 800;
     color: rgba(219, 234, 254, 0.88);
 }

 .month-dot {
     opacity: 0.6;
 }

 .month-session-right {
     display: flex;
     gap: 0.6rem;
     align-items: center;
     flex-wrap: wrap;
 }

 .month-tag {
     margin-left: 0.5rem;
     padding: 0.2rem 0.55rem;
     border-radius: 999px;
     font-size: 0.75rem;
     font-weight: 950;
     background: rgba(255, 255, 255, 0.14);
     border: 1px solid rgba(255, 255, 255, 0.16);
 }

 .month-type {
     padding: 0.25rem 0.6rem;
     border-radius: 999px;
     font-size: 0.75rem;
     font-weight: 950;
     border: 1px solid rgba(255,255,255,0.18);
 }

 .type-presentielle {
     background: rgba(249, 115, 22, 0.18);
     color: rgba(255, 255, 255, 0.96);
 }

 .type-enligne {
     background: rgba(37, 99, 235, 0.18);
     color: rgba(255, 255, 255, 0.96);
 }

 .all-programmes-card {
     margin-top: 0.25rem;
     background: rgba(15, 23, 42, 0.55);
     border: 1px solid rgba(191, 219, 254, 0.18);
     border-radius: 18px;
     padding: 1rem;
     backdrop-filter: blur(10px);
 }

 .all-programmes-header {
     display: flex;
     justify-content: space-between;
     align-items: flex-end;
     gap: 1rem;
     flex-wrap: wrap;
     margin-bottom: 0.75rem;
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
     margin-bottom: 0.85rem;
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
     gap: 0.35rem;
     width: 100%;
 }

 .programme-acc-name {
     font-size: 1.05rem;
     font-weight: 950;
 }

 .programme-acc-meta {
     display: flex;
     gap: 0.4rem;
     flex-wrap: wrap;
 }

 .programme-acc-body {
     background: rgba(2, 6, 23, 0.22);
     color: rgba(255, 255, 255, 0.92);
 }

 .programme-desc {
     color: rgba(219, 234, 254, 0.92);
     font-weight: 700;
     margin-bottom: 0.75rem;
 }

 .programme-empty {
     padding: 0.75rem;
     font-weight: 800;
     color: rgba(219, 234, 254, 0.9);
     border: 1px dashed rgba(191, 219, 254, 0.25);
     border-radius: 12px;
 }

 .badge-soft {
     background: rgba(255, 255, 255, 0.10);
     border: 1px solid rgba(255, 255, 255, 0.12);
     color: rgba(255, 255, 255, 0.92);
     font-weight: 850;
 }

 .programme-status-badge {
     display: inline-flex;
     align-items: center;
     margin-left: 0.6rem;
     padding: 0.15rem 0.55rem;
     border-radius: 999px;
     font-size: 0.75rem;
     font-weight: 950;
     border: 1px solid rgba(255, 255, 255, 0.18);
     background: rgba(2, 6, 23, 0.22);
     color: rgba(255, 255, 255, 0.98);
 }

 .programme-status-badge.is-running {
     background: rgba(16, 185, 129, 0.22);
     border-color: rgba(16, 185, 129, 0.35);
 }

 .programme-status-badge.is-done {
     background: rgba(148, 163, 184, 0.22);
     border-color: rgba(148, 163, 184, 0.35);
 }

 .programme-status-badge.is-upcoming {
     background: rgba(249, 115, 22, 0.22);
     border-color: rgba(249, 115, 22, 0.35);
 }

/* Bordure dégradée Instagram */
.programme-card::after {
    content: '';
    position: absolute;
    inset: 0;
    padding: 2px;
    border-radius: 20px;
    background: linear-gradient(135deg, rgba(191, 219, 254, 0.35), rgba(191, 219, 254, 0.16));
    -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    pointer-events: none;
    opacity: 0.55;
}

.instagram-header::before {
    content: '';
    position: absolute;
    inset: -2px;
    background: radial-gradient(600px 220px at 20% 20%, rgba(255,255,255,0.35), transparent 55%),
                radial-gradient(480px 220px at 80% 0%, rgba(255,255,255,0.18), transparent 60%);
    pointer-events: none;
}

/* Icône circulaire avec effet glassmorphism */
.icon-circle {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    border: 1px solid rgba(255, 255, 255, 0.28);
    animation: pulse 2s infinite;
}

.icon-circle-large {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, rgba(30, 60, 114, 0.08), rgba(79, 195, 247, 0.12));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    color: var(--evc-blue-600);
    margin: 0 auto;
}

/* Carte de programme avec style Instagram */
.programme-card {
    background: var(--evc-surface);
    border-radius: 20px;
    padding: 2rem;
    border: 1px solid var(--evc-border);
    box-shadow: 0 18px 55px rgba(2, 6, 23, 0.45);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    animation: fadeInUp 0.6s ease;
    position: relative;
    overflow: hidden;
}

.programme-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: transparent;
    pointer-events: none;
}

.programme-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 22px 70px rgba(2, 6, 23, 0.55);
    border-color: rgba(191, 219, 254, 0.30);
}

.programme-card:hover::after {
    opacity: 0.85;
}

/* Icône PDF avec dégradé */
.pdf-icon-container {
    display: flex;
    justify-content: center;
}

.pdf-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-600));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.25);
    transition: all 0.3s ease;
}

.programme-card:hover .pdf-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Titre du programme */
.programme-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.95);
    text-align: center;
    line-height: 1.4;
}

/* Description du programme */
.programme-description {
    color: rgba(219, 234, 254, 0.90);
    font-size: 0.95rem;
    line-height: 1.6;
    text-align: center;
}

/* Option B: light premium (cartes très lisibles) */
.programme-card,
.tools-card,
.empty-state,
.session-row {
    backdrop-filter: blur(10px);
}

.programme-card {
    border: 1px solid rgba(255, 255, 255, 0.55);
}

.programme-title,
.programme-description {
    text-align: left;
}

/* Informations du programme */
.programme-info {
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

.programme-badges {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
    gap: 0.5rem;
}

.badge-soft {
    background: #12336b;
    color: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(191, 219, 254, 0.22);
    padding: 0.45rem 0.7rem;
    border-radius: 999px;
    font-weight: 600;
}

.badge-soft i {
    color: rgba(255, 255, 255, 0.85);
}

/* Bouton Instagram */
.instagram-btn {
    background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-600));
    color: white;
    border: none;
    border-radius: 30px;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 14px 40px rgba(0, 0, 0, 0.28);
    margin-top: auto;
}

.btn-disabled {
    background: linear-gradient(135deg, #94a3b8, #64748b);
    opacity: 0.85;
    cursor: not-allowed;
}

.instagram-btn:hover {
    background: linear-gradient(135deg, var(--evc-blue-600), var(--evc-blue-500));
    transform: translateY(-2px);
    box-shadow: 0 20px 55px rgba(0, 0, 0, 0.35);
    color: white;
}

/* État vide */
.empty-state {
    background: var(--evc-surface);
    border-radius: 20px;
    padding: 4rem 2rem;
    box-shadow: 0 18px 55px rgba(2, 6, 23, 0.45);
    border: 1px solid var(--evc-border);
}

/* Sessions */
.sessions-list {
    display: grid;
    gap: 0.75rem;
}

.session-row {
    background: var(--evc-surface-soft);
    border: 1px solid var(--evc-border);
    border-radius: 16px;
    padding: 0.9rem 1rem;
    display: grid;
    grid-template-columns: 48px 1fr auto;
    gap: 0.9rem;
    align-items: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.session-row[data-status="current"] {
    border: 2px solid rgba(37, 99, 235, 0.45);
    box-shadow: 0 14px 35px rgba(37, 99, 235, 0.16);
}

.sessions-focus {
    border-radius: 20px;
    padding: 1rem;
    background: #0a2a5c;
    border: 1px solid rgba(191, 219, 254, 0.18);
    box-shadow: 0 22px 70px rgba(2, 6, 23, 0.55);
}

.sessions-focus-header {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.75rem;
    padding: 0.25rem 0.25rem 0.5rem 0.25rem;
}

.sessions-focus-title {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-weight: 900;
    color: rgba(255, 255, 255, 0.95);
    text-shadow: 0 14px 40px rgba(0,0,0,0.35);
    letter-spacing: 0.02em;
}

.sessions-focus-subtitle {
    color: rgba(255, 255, 255, 0.82);
    font-weight: 700;
}

.session-row-focus {
    background: rgba(255, 255, 255, 0.96);
    border: 2px solid rgba(37, 99, 235, 0.55);
    box-shadow: 0 18px 55px rgba(37, 99, 235, 0.16);
}

.session-row[data-status="soon"] {
    border: 2px solid rgba(37, 99, 235, 0.28);
    box-shadow: 0 14px 35px rgba(37, 99, 235, 0.12);
}

.session-row[data-status="past"] {
    opacity: 0.78;
}

.session-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 16px 45px rgba(0, 0, 0, 0.18);
}

.session-icon {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-600));
    box-shadow: 0 12px 30px rgba(0,0,0,0.18);
}

.session-title {
    font-weight: 800;
    color: rgba(255, 255, 255, 0.95);
}

.session-topline {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    gap: 0.6rem;
    align-items: center;
}

.session-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.session-status-badge {
    border-radius: 999px;
    padding: 0.45rem 0.7rem;
    font-weight: 900;
    letter-spacing: 0.02em;
}

.session-status-current {
    color: #fff;
    background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-600));
    box-shadow: 0 14px 35px rgba(37, 99, 235, 0.18);
}

.session-status-soon {
    color: #fff;
    background: rgba(37, 99, 235, 0.85);
    box-shadow: 0 14px 35px rgba(37, 99, 235, 0.16);
}

.session-status-past {
    color: rgba(15, 23, 42, 0.85);
    background: rgba(15, 23, 42, 0.08);
    border: 1px solid rgba(15, 23, 42, 0.12);
}

.session-type-badge {
    border-radius: 999px;
    padding: 0.45rem 0.7rem;
    font-weight: 900;
    letter-spacing: 0.02em;
    border: 1px solid rgba(37, 99, 235, 0.16);
}

.session-type-badge.type-enligne {
    color: #0b1f44;
    background: rgba(37, 99, 235, 0.10);
}

.session-type-badge.type-presentielle {
    color: #0b1f44;
    background: rgba(37, 99, 235, 0.10);
}

.session-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    color: rgba(219, 234, 254, 0.85);
    font-weight: 600;
    margin-top: 0.15rem;
}

.session-desc {
    margin-top: 0.55rem;
    padding: 0.75rem 0.9rem;
    border-radius: 14px;
    background: #12336b;
    border: 1px solid rgba(191, 219, 254, 0.18);
    color: rgba(255, 255, 255, 0.88);
    font-weight: 600;
    line-height: 1.6;
}

.session-when,
.session-where {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.45rem 0.7rem;
    border-radius: 999px;
    border: 1px solid rgba(191, 219, 254, 0.18);
    font-weight: 800;
    color: rgba(255, 255, 255, 0.92);
}

.session-when {
    background: #12336b;
}

.session-where {
    background: #12336b;
}

.session-dot {
    opacity: 0.7;
    font-weight: 900;
}

.session-actions .btn {
    border-radius: 999px;
    font-weight: 800;
}

.session-actions .btn-primary {
    background: linear-gradient(135deg, var(--evc-blue-700), var(--evc-blue-600));
    border: none;
    box-shadow: 0 14px 35px rgba(0,0,0,0.20);
}

.session-actions .btn-primary:hover {
    filter: brightness(1.05);
}

/* Animations */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .programme-hero-title {
        font-size: 1.35rem;
    }

    .programme-hero-subtitle {
        font-size: 0.95rem;
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .programme-card {
        padding: 1.5rem;
    }

    .pdf-icon {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }

    .programme-title {
        font-size: 1.1rem;
    }
}

/* Animation en cascade pour les cartes */
.programme-card:nth-child(1) { animation-delay: 0.1s; }
.programme-card:nth-child(2) { animation-delay: 0.2s; }
.programme-card:nth-child(3) { animation-delay: 0.3s; }
.programme-card:nth-child(4) { animation-delay: 0.4s; }
.programme-card:nth-child(5) { animation-delay: 0.5s; }
.programme-card:nth-child(6) { animation-delay: 0.6s; }

.tools-card {
    background: var(--evc-surface);
    border-radius: 20px;
    padding: 1rem;
    box-shadow: 0 18px 55px rgba(2, 6, 23, 0.45);
    border: 1px solid var(--evc-border);
}

.tools-input {
    border-radius: 12px;
    border: 1px solid rgba(191, 219, 254, 0.18);
    background: #0e2a5a;
    color: rgba(255, 255, 255, 0.92);
}

.tools-input:focus {
    border-color: rgba(59, 130, 246, 0.65);
    box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.20);
}

.tools-input-addon {
    border-radius: 12px;
    background: #0e2a5a;
    border: 1px solid rgba(191, 219, 254, 0.18);
    color: rgba(255, 255, 255, 0.92);
}

.tools-reset {
    border-radius: 12px;
    font-weight: 600;
    border: none;
    background: #12336b;
    color: rgba(255, 255, 255, 0.92);
}

.tools-reset:hover {
    filter: brightness(1.02);
}

.programme-hero-wrap{background:linear-gradient(90deg,#0a1128 0%,#001f54 50%,#034078 100%) !important;}
.programme-card-modern{background:#fff !important;border:1px solid rgba(15,23,42,.08) !important;}
.programme-card-title{color:#0f172a !important;}
.programme-card-desc,.programme-card-desc-muted{color:rgba(15,23,42,.72) !important;}
.programme-chip{color:rgba(15,23,42,.85) !important;background:rgba(15,23,42,.04) !important;border:1px solid rgba(15,23,42,.08) !important;}
.programme-chip-soft{color:rgba(15,23,42,.70) !important;}
.programme-card-icon{background:rgba(249,115,22,.12) !important;border:1px solid rgba(249,115,22,.22) !important;color:#f97316 !important;}
.programme-card-actions .btn{border-radius:999px !important;font-weight:800 !important;}
</style>
@endpush

@push('scripts')
<script>
// Animation au scroll
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('programmeSearch');
    const typeFilter = document.getElementById('programmeTypeFilter');
    const resetBtn = document.getElementById('programmeReset');
    const programmeFilterResetBtn = document.getElementById('programmeFilterReset');
    const cardsWrap = document.getElementById('programmeFormationCards');
    const programmeCards = Array.from(document.querySelectorAll('.programme-card-item'));

    let activeProgrammeFilter = '';

    function setProgrammeFilter(filter) {
        activeProgrammeFilter = (filter || '').toString();
        if (cardsWrap) {
            Array.from(cardsWrap.querySelectorAll('[data-programme-filter]')).forEach(el => {
                const isActive = (el.getAttribute('data-programme-filter') || '') === activeProgrammeFilter;
                el.classList.toggle('is-active', !!activeProgrammeFilter && isActive);
            });
        }
        applyFilters();
    }

    function normalize(value) {
        return (value || '').toString().toLowerCase().trim();
    }

    function applyFilters() {
        const search = normalize(searchInput ? searchInput.value : '');
        const type = normalize(typeFilter ? typeFilter.value : '');

        // Filtre sur les PROGRAMMES (DG / CM / Mois en cours)
        programmeCards.forEach(el => {
            const canonical = el.getAttribute('data-canonical') || '';
            const currentMonth = el.getAttribute('data-current-month') || '0';
            let show = true;

            if (activeProgrammeFilter === 'dg') {
                show = canonical === 'dg';
            } else if (activeProgrammeFilter === 'cm') {
                show = canonical === 'cm';
            } else if (activeProgrammeFilter === 'current') {
                show = currentMonth === '1';
            }

            el.style.display = show ? '' : 'none';
        });

        document.querySelectorAll('.programme-item').forEach(item => {
            const itemSearch = normalize(item.getAttribute('data-search'));
            const itemType = normalize(item.getAttribute('data-type'));

            const matchSearch = !search || itemSearch.includes(search);
            const matchType = !type || itemType === type;

            item.style.display = (matchSearch && matchType) ? '' : 'none';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    if (typeFilter) {
        typeFilter.addEventListener('change', applyFilters);
    }
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (searchInput) searchInput.value = '';
            if (typeFilter) typeFilter.value = '';
            setProgrammeFilter('');
            applyFilters();
        });
    }

    if (programmeFilterResetBtn) {
        programmeFilterResetBtn.addEventListener('click', function() {
            setProgrammeFilter('');
        });
    }

    if (cardsWrap) {
        Array.from(cardsWrap.querySelectorAll('[data-programme-filter]')).forEach(el => {
            el.addEventListener('click', function(e) {
                const filter = this.getAttribute('data-programme-filter');
                if (!filter) return;
                e.preventDefault();
                e.stopPropagation();
                setProgrammeFilter(filter);
            });
        });
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.programme-card').forEach(card => {
        observer.observe(card);
    });

    applyFilters();
});
</script>
@endpush
