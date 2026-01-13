@extends('layouts.ki-admin')

@section('title', 'Programmes de Formation - EVC 2024')
@section('page-title', 'Programmes de Formation')

@section('content')
<div class="programme-page-bg" aria-hidden="true"></div>
<!-- Header avec palette Instagram -->
<div class="row mb-4">
    <div class="col-12">
        <div class="instagram-header">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-circle">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <h3 class="mb-1" style="font-weight: 700; font-size: 1.8rem;">
                                Programmes de Formation
                            </h3>
                            <p class="mb-0 text-white-50">{{ $student->program ?? 'Votre formation' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="badge" style="background: rgba(255,255,255,0.2); padding: 0.75rem 1.5rem; font-size: 1rem; border-radius: 30px;">
                            <i class="fas fa-layer-group me-2"></i>
                            {{ $programmes->count() }} Programme(s) du mois
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Outils (recherche + filtre) -->
@if(!$programmes->isEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="tools-card">
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text tools-input-addon">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="programmeSearch" class="form-control tools-input" placeholder="Rechercher une thématique, un lieu...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select id="programmeTypeFilter" class="form-select tools-input">
                            <option value="">Tous les types</option>
                            <option value="en_ligne">En ligne</option>
                            <option value="presentielle">Présentielle</option>
                        </select>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <button type="button" class="btn btn-light tools-reset" id="programmeReset">
                            Réinitialiser
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Liste dynamique des programmes -->
<div class="row">
    <div class="col-12">
        @if($programmes->isEmpty())
            <!-- Message si aucun programme -->
            <div class="empty-state">
                <div class="text-center py-5">
                    <div class="icon-circle-large mb-4">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="mb-3" style="color: #1f2937; font-weight: 600;">Aucun programme disponible</h3>
                    <p class="text-muted mb-0">Les programmes de formation seront publiés prochainement par votre formateur.</p>
                </div>
            </div>
        @else
            <!-- Liste des programmes mensuels -->
            <div class="row g-4">
                @foreach($programmes as $programme)
                    <div class="col-12">
                        <div class="programme-card instagram-card">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                                <div>
                                    <h4 class="programme-title mb-1" style="text-align:left;">
                                        {{ $programme->titre }}
                                    </h4>
                                    <div class="programme-badges">
                                        <span class="badge badge-soft">
                                            <i class="fas fa-graduation-cap me-1"></i>
                                            {{ $programme->formation ?? 'Programme' }}
                                        </span>
                                        @if(!empty($programme->month_start))
                                            <span class="badge badge-soft">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($programme->month_start)->translatedFormat('F Y') }}
                                            </span>
                                        @endif
                                        <span class="badge badge-soft">
                                            <i class="fas fa-list me-1"></i>
                                            {{ (int) ($programme->items_count ?? 0) }} séance(s)
                                        </span>
                                    </div>
                                </div>
                                <div class="text-muted small">
                                    <i class="fas fa-clock me-1"></i>
                                    Publié le {{ \Carbon\Carbon::parse($programme->created_at)->format('d/m/Y') }}
                                </div>
                            </div>

                            @if($programme->description)
                                <p class="programme-description" style="text-align:left;">
                                    {{ $programme->description }}
                                </p>
                            @endif

                            @if(!empty($programme->fichier_pdf))
                                <div class="mt-3" style="text-align:left;">
                                    <a class="btn btn-sm btn-primary" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        Télécharger le programme (PDF)
                                    </a>
                                </div>
                            @endif

                            @php
                                $items = $programme->items ?? collect();
                            @endphp

                            @if($items->isEmpty())
                                <div class="empty-state" style="padding: 1.5rem 1.25rem;">
                                    <p class="text-muted mb-0">Aucune séance n'a été ajoutée pour ce programme.</p>
                                </div>
                            @else
                                @php
                                    $now = now();
                                    $upcomingItems = collect();
                                    $pastItems = collect();

                                    foreach ($items as $it) {
                                        $dt = null;
                                        try {
                                            $dt = \Carbon\Carbon::parse($it->session_date . ' ' . $it->session_time);
                                        } catch (\Throwable $e) {
                                            $dt = null;
                                        }

                                        $isPast = $dt ? $dt->lt($now) : false;
                                        if ($isPast) {
                                            $pastItems->push($it);
                                        } else {
                                            $upcomingItems->push($it);
                                        }
                                    }

                                    // Tri : à venir du plus proche au plus lointain
                                    $upcomingItems = $upcomingItems->sortBy(function ($it) {
                                        try {
                                            return \Carbon\Carbon::parse($it->session_date . ' ' . $it->session_time)->timestamp;
                                        } catch (\Throwable $e) {
                                            return PHP_INT_MAX;
                                        }
                                    })->values();

                                    // Tri : terminées du plus récent au plus ancien
                                    $pastItems = $pastItems->sortByDesc(function ($it) {
                                        try {
                                            return \Carbon\Carbon::parse($it->session_date . ' ' . $it->session_time)->timestamp;
                                        } catch (\Throwable $e) {
                                            return 0;
                                        }
                                    })->values();
                                @endphp

                                @if($upcomingItems->isNotEmpty())
                                    <div class="sessions-focus mt-3">
                                        <div class="sessions-focus-header">
                                            <div class="sessions-focus-title">
                                                <i class="fas fa-bolt"></i>
                                                Séances en cours
                                            </div>
                                            <div class="sessions-focus-subtitle">À suivre maintenant</div>
                                        </div>

                                        <div class="sessions-list">
                                            @foreach($upcomingItems as $item)
                                                @php
                                                    $downloadPath = $item->piece_jointe ?? null;
                                                    $lowerPath = strtolower((string) $downloadPath);
                                                    $isPdf = $downloadPath && str_ends_with($lowerPath, '.pdf');
                                                    $isImage = $downloadPath && (str_ends_with($lowerPath, '.jpg') || str_ends_with($lowerPath, '.jpeg') || str_ends_with($lowerPath, '.png'));
                                                    $typeFormation = $item->type_formation ?? null;

                                                    $sessionDt = null;
                                                    try {
                                                        $sessionDt = \Carbon\Carbon::parse($item->session_date . ' ' . $item->session_time);
                                                    } catch (\Throwable $e) {
                                                        $sessionDt = null;
                                                    }

                                                    $sessionStatus = 'current';
                                                @endphp

                                                <div class="session-row programme-item session-row-focus"
                                                     data-status="{{ $sessionStatus }}"
                                                     data-type="{{ $typeFormation }}"
                                                     data-search="{{ strtolower(($item->thematique ?? '') . ' ' . ($item->description ?? '') . ' ' . ($item->lieu ?? '') . ' ' . ($programme->titre ?? '') . ' ' . ($programme->formation ?? '')) }}">
                                                    <div class="session-icon">
                                                        @if($isPdf)
                                                            <i class="fas fa-file-pdf"></i>
                                                        @elseif($isImage)
                                                            <i class="fas fa-image"></i>
                                                        @elseif($downloadPath)
                                                            <i class="fas fa-paperclip"></i>
                                                        @else
                                                            <i class="fas fa-calendar-check"></i>
                                                        @endif
                                                    </div>
                                                    <div class="session-content">
                                                        <div class="session-topline">
                                                            <div class="session-title">{{ $item->thematique }}</div>

                                                            <div class="session-badges">
                                                                <span class="badge session-status-badge session-status-current">
                                                                    <i class="fas fa-bolt me-1"></i>
                                                                    EN COURS
                                                                </span>

                                                                @if($typeFormation)
                                                                    <span class="badge session-type-badge {{ $typeFormation === 'presentielle' ? 'type-presentielle' : 'type-enligne' }}">
                                                                        @if($typeFormation === 'presentielle')
                                                                            <i class="fas fa-location-dot me-1"></i>
                                                                            PRÉSENTIELLE
                                                                        @else
                                                                            <i class="fas fa-video me-1"></i>
                                                                            EN LIGNE
                                                                        @endif
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="session-meta">
                                                            <span class="session-when">
                                                                <i class="fas fa-calendar me-1"></i>
                                                                {{ \Carbon\Carbon::parse($item->session_date)->format('d/m/Y') }}
                                                                <span class="session-dot">•</span>
                                                                {{ \Carbon\Carbon::parse($item->session_time)->format('H:i') }}
                                                            </span>
                                                            @if(($typeFormation ?? null) === 'presentielle' && !empty($item->lieu))
                                                                <span class="session-where">
                                                                    <i class="fas fa-map-marker-alt me-1"></i>
                                                                    {{ $item->lieu }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        @if(!empty($item->description))
                                                            <div class="session-desc">
                                                                <i class="fas fa-quote-left me-2"></i>
                                                                {{ $item->description }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="session-actions">
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
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="sessions-list mt-3">
                                    @foreach($pastItems as $item)
                                        @php
                                            $downloadPath = $item->piece_jointe ?? null;
                                            $lowerPath = strtolower((string) $downloadPath);
                                            $isPdf = $downloadPath && str_ends_with($lowerPath, '.pdf');
                                            $isImage = $downloadPath && (str_ends_with($lowerPath, '.jpg') || str_ends_with($lowerPath, '.jpeg') || str_ends_with($lowerPath, '.png'));
                                            $typeFormation = $item->type_formation ?? null;

                                            $sessionDt = null;
                                            try {
                                                $sessionDt = \Carbon\Carbon::parse($item->session_date . ' ' . $item->session_time);
                                            } catch (\Throwable $e) {
                                                $sessionDt = null;
                                            }

                                            $now = now();
                                            $isPast = $sessionDt ? $sessionDt->lt($now) : false;
                                            $isSoon = $sessionDt ? $sessionDt->between($now->copy(), $now->copy()->addDays(3)) : false;

                                            $sessionStatus = $isPast ? 'past' : ($isSoon ? 'soon' : '');
                                        @endphp

                                        <div class="session-row programme-item"
                                             data-status="{{ $sessionStatus }}"
                                             data-type="{{ $typeFormation }}"
                                             data-search="{{ strtolower(($item->thematique ?? '') . ' ' . ($item->description ?? '') . ' ' . ($item->lieu ?? '') . ' ' . ($programme->titre ?? '') . ' ' . ($programme->formation ?? '')) }}">
                                            <div class="session-icon">
                                                @if($isPdf)
                                                    <i class="fas fa-file-pdf"></i>
                                                @elseif($isImage)
                                                    <i class="fas fa-image"></i>
                                                @elseif($downloadPath)
                                                    <i class="fas fa-paperclip"></i>
                                                @else
                                                    <i class="fas fa-calendar-check"></i>
                                                @endif
                                            </div>
                                            <div class="session-content">
                                                <div class="session-topline">
                                                    <div class="session-title">{{ $item->thematique }}</div>

                                                    <div class="session-badges">
                                                        @if($sessionStatus === 'current')
                                                            <span class="badge session-status-badge session-status-current">
                                                                <i class="fas fa-bolt me-1"></i>
                                                                EN COURS
                                                            </span>
                                                        @elseif($sessionStatus === 'soon')
                                                            <span class="badge session-status-badge session-status-soon">
                                                                <i class="fas fa-hourglass-half me-1"></i>
                                                                À VENIR
                                                            </span>
                                                        @elseif($sessionStatus === 'past')
                                                            <span class="badge session-status-badge session-status-past">
                                                                <i class="fas fa-check me-1"></i>
                                                                TERMINÉE
                                                            </span>
                                                        @endif

                                                        @if($typeFormation)
                                                            <span class="badge session-type-badge {{ $typeFormation === 'presentielle' ? 'type-presentielle' : 'type-enligne' }}">
                                                                @if($typeFormation === 'presentielle')
                                                                    <i class="fas fa-location-dot me-1"></i>
                                                                    PRÉSENTIELLE
                                                                @else
                                                                    <i class="fas fa-video me-1"></i>
                                                                    EN LIGNE
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="session-meta">
                                                    <span class="session-when">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ \Carbon\Carbon::parse($item->session_date)->format('d/m/Y') }}
                                                        <span class="session-dot">•</span>
                                                        {{ \Carbon\Carbon::parse($item->session_time)->format('H:i') }}
                                                    </span>
                                                    @if(($typeFormation ?? null) === 'presentielle' && !empty($item->lieu))
                                                        <span class="session-where">
                                                            <i class="fas fa-map-marker-alt me-1"></i>
                                                            {{ $item->lieu }}
                                                        </span>
                                                    @endif
                                                </div>

                                                @if(!empty($item->description))
                                                    <div class="session-desc">
                                                        <i class="fas fa-quote-left me-2"></i>
                                                        {{ $item->description }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="session-actions">
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
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
/* Palette Instagram */
:root {
    --instagram-purple: #833AB4;
    --instagram-pink: #FD1D1D;
    --instagram-red: #E1306C;
    --instagram-orange: #F77737;
    --instagram-yellow: #FCAF45;
    --instagram-blue: #405DE6;
    --instagram-bg: #0b1220;
    --instagram-card: rgba(255, 255, 255, 0.86);
    --instagram-glass: rgba(255, 255, 255, 0.12);
}

/* Fond (isolé à cette page) */
.programme-page-bg {
    position: fixed;
    inset: 0;
    z-index: -1;
    background:
        radial-gradient(1200px 800px at 15% 10%, rgba(64, 93, 230, 0.42), transparent 60%),
        radial-gradient(900px 700px at 80% 0%, rgba(225, 48, 108, 0.36), transparent 55%),
        radial-gradient(900px 700px at 90% 85%, rgba(252, 175, 69, 0.30), transparent 55%),
        linear-gradient(135deg, rgba(64, 93, 230, 0.16), rgba(225, 48, 108, 0.14), rgba(252, 175, 69, 0.12)),
        var(--instagram-bg);
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
    background: linear-gradient(135deg, var(--instagram-blue), var(--instagram-purple), var(--instagram-red), var(--instagram-orange));
    border-radius: 20px;
    color: white;
    box-shadow: 0 18px 55px rgba(0, 0, 0, 0.45);
    animation: fadeInDown 0.6s ease;
    margin-bottom: 2rem;
    position: relative;
    overflow: hidden;
}

/* Bordure dégradée Instagram */
.programme-card::after {
    content: '';
    position: absolute;
    inset: 0;
    padding: 2px;
    border-radius: 20px;
    background: linear-gradient(135deg, var(--instagram-blue), var(--instagram-purple), var(--instagram-red), var(--instagram-orange), var(--instagram-yellow));
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
    color: var(--instagram-pink);
    margin: 0 auto;
}

/* Carte de programme avec style Instagram */
.programme-card {
    background: linear-gradient(135deg,
        rgba(64, 93, 230, 0.10),
        rgba(225, 48, 108, 0.10),
        rgba(252, 175, 69, 0.08)
    ), var(--instagram-card);
    border-radius: 20px;
    padding: 2rem;
    border: 2px solid transparent;
    box-shadow: 0 18px 55px rgba(0, 0, 0, 0.25);
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
    background: radial-gradient(700px 280px at 10% 0%, rgba(64, 93, 230, 0.12), transparent 55%),
                radial-gradient(640px 260px at 100% 0%, rgba(225, 48, 108, 0.14), transparent 55%),
                radial-gradient(640px 260px at 100% 100%, rgba(252, 175, 69, 0.10), transparent 55%);
    pointer-events: none;
}

.programme-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 24px 70px rgba(0, 0, 0, 0.32);
    border-color: rgba(225, 48, 108, 0.8);
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
    background: linear-gradient(135deg, var(--instagram-pink), var(--instagram-red));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 8px 24px rgba(42, 82, 152, 0.25);
    transition: all 0.3s ease;
}

.programme-card:hover .pdf-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Titre du programme */
.programme-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    text-align: center;
    line-height: 1.4;
}

/* Description du programme */
.programme-description {
    color: #6b7280;
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
    background: linear-gradient(135deg, rgba(64, 93, 230, 0.12), rgba(225, 48, 108, 0.10), rgba(252, 175, 69, 0.10));
    color: #0f172a;
    border: 1px solid rgba(15, 23, 42, 0.10);
    padding: 0.45rem 0.7rem;
    border-radius: 999px;
    font-weight: 600;
}

.badge-soft i {
    color: rgba(15, 23, 42, 0.75);
}

/* Bouton Instagram */
.instagram-btn {
    background: linear-gradient(135deg, var(--instagram-blue), var(--instagram-red), var(--instagram-orange));
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
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-red), var(--instagram-yellow));
    transform: translateY(-2px);
    box-shadow: 0 20px 55px rgba(0, 0, 0, 0.35);
    color: white;
}

/* État vide */
.empty-state {
    background: linear-gradient(135deg,
        rgba(64, 93, 230, 0.10),
        rgba(225, 48, 108, 0.10),
        rgba(252, 175, 69, 0.08)
    ), rgba(255,255,255,0.88);
    border-radius: 20px;
    padding: 4rem 2rem;
    box-shadow: 0 18px 55px rgba(0, 0, 0, 0.20);
    border: 1px solid rgba(255,255,255,0.35);
}

/* Sessions */
.sessions-list {
    display: grid;
    gap: 0.75rem;
}

.session-row {
    background: linear-gradient(135deg,
        rgba(64, 93, 230, 0.09),
        rgba(225, 48, 108, 0.08),
        rgba(252, 175, 69, 0.06)
    ), rgba(255, 255, 255, 0.88);
    border: 1px solid rgba(15, 23, 42, 0.08);
    border-radius: 16px;
    padding: 0.9rem 1rem;
    display: grid;
    grid-template-columns: 48px 1fr auto;
    gap: 0.9rem;
    align-items: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.session-row[data-status="current"] {
    border: 2px solid rgba(225, 48, 108, 0.55);
    box-shadow: 0 18px 55px rgba(225, 48, 108, 0.18);
}

.sessions-focus {
    border-radius: 20px;
    padding: 1rem;
    background: linear-gradient(135deg, rgba(64, 93, 230, 0.16), rgba(225, 48, 108, 0.14), rgba(252, 175, 69, 0.12));
    border: 1px solid rgba(255, 255, 255, 0.22);
    box-shadow: 0 22px 70px rgba(0, 0, 0, 0.35);
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
    background: linear-gradient(135deg,
        rgba(225, 48, 108, 0.16),
        rgba(252, 175, 69, 0.12),
        rgba(64, 93, 230, 0.12)
    ), rgba(255, 255, 255, 0.93);
    border: 2px solid rgba(225, 48, 108, 0.75);
    box-shadow: 0 22px 70px rgba(225, 48, 108, 0.20);
}

.session-row[data-status="soon"] {
    border: 2px solid rgba(64, 93, 230, 0.35);
    box-shadow: 0 18px 55px rgba(64, 93, 230, 0.14);
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
    background: linear-gradient(135deg, var(--instagram-blue), var(--instagram-red), var(--instagram-orange));
    box-shadow: 0 12px 30px rgba(0,0,0,0.18);
}

.session-title {
    font-weight: 800;
    color: #0f172a;
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
    background: linear-gradient(135deg, var(--instagram-red), var(--instagram-orange));
    box-shadow: 0 14px 35px rgba(225, 48, 108, 0.22);
}

.session-status-soon {
    color: #fff;
    background: linear-gradient(135deg, var(--instagram-blue), var(--instagram-purple));
    box-shadow: 0 14px 35px rgba(64, 93, 230, 0.20);
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
    border: 1px solid rgba(15, 23, 42, 0.10);
}

.session-type-badge.type-enligne {
    color: #0f172a;
    background: linear-gradient(135deg, rgba(64, 93, 230, 0.16), rgba(225, 48, 108, 0.10));
}

.session-type-badge.type-presentielle {
    color: #0f172a;
    background: linear-gradient(135deg, rgba(252, 175, 69, 0.18), rgba(225, 48, 108, 0.10));
}

.session-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    color: rgba(15, 23, 42, 0.72);
    font-weight: 600;
    margin-top: 0.15rem;
}

.session-desc {
    margin-top: 0.55rem;
    padding: 0.75rem 0.9rem;
    border-radius: 14px;
    background: rgba(15, 23, 42, 0.04);
    border: 1px solid rgba(15, 23, 42, 0.06);
    color: rgba(15, 23, 42, 0.85);
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
    border: 1px solid rgba(15, 23, 42, 0.10);
    font-weight: 800;
    color: rgba(15, 23, 42, 0.92);
}

.session-when {
    background: linear-gradient(135deg, rgba(64, 93, 230, 0.14), rgba(225, 48, 108, 0.10));
}

.session-where {
    background: linear-gradient(135deg, rgba(252, 175, 69, 0.16), rgba(225, 48, 108, 0.08));
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
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-red), var(--instagram-orange));
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
    .instagram-header h3 {
        font-size: 1.5rem;
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
    background: linear-gradient(135deg, rgba(64, 93, 230, 0.14), rgba(225, 48, 108, 0.12), rgba(252, 175, 69, 0.10)), rgba(255,255,255,0.86);
    border-radius: 20px;
    padding: 1rem;
    box-shadow: 0 18px 55px rgba(0, 0, 0, 0.20);
    border: 1px solid rgba(255,255,255,0.35);
}

.tools-input {
    border-radius: 12px;
    border: 1px solid rgba(15, 23, 42, 0.12);
    background: rgba(255,255,255,0.95);
}

.tools-input:focus {
    border-color: rgba(240, 148, 51, 0.85);
    box-shadow: 0 0 0 0.25rem rgba(240, 148, 51, 0.22);
}

.tools-input-addon {
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(64, 93, 230, 0.14), rgba(225, 48, 108, 0.10));
    border: 1px solid rgba(15, 23, 42, 0.10);
    color: #1f2937;
}

.tools-reset {
    border-radius: 12px;
    font-weight: 600;
    border: none;
    background: linear-gradient(135deg, rgba(64, 93, 230, 0.20), rgba(225, 48, 108, 0.18), rgba(252, 175, 69, 0.16));
}

.tools-reset:hover {
    filter: brightness(1.02);
}
</style>
@endpush

@push('scripts')
<script>
// Animation au scroll
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('programmeSearch');
    const typeFilter = document.getElementById('programmeTypeFilter');
    const resetBtn = document.getElementById('programmeReset');

    function normalize(value) {
        return (value || '').toString().toLowerCase().trim();
    }

    function applyFilters() {
        const search = normalize(searchInput ? searchInput.value : '');
        const type = normalize(typeFilter ? typeFilter.value : '');

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
            applyFilters();
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
