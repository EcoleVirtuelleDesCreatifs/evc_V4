@extends('layouts.ki-admin')

@section('title', 'Programmes de Formation - EVC 2024')
@section('page-title', 'Programmes de Formation')

@push('styles')
<style>
    .pgi-bg {
        position: fixed; inset: 0; z-index: -1;
        background: linear-gradient(180deg, #081126 0%, #0b1220 55%, #081126 100%);
    }
    .content-wrapper, .main-content { background: transparent !important; }

    /* ─── Hero ─── */
    .pgi-hero {
        border-radius: 20px; position: relative; overflow: hidden;
        background: linear-gradient(90deg, #0a1128 0%, #001f54 50%, #034078 100%);
        border: 1px solid rgba(255,255,255,0.10);
        padding: 2rem 1.5rem; margin-bottom: 1.5rem; text-align: center;
    }
    .pgi-hero::before {
        content: ''; position: absolute; inset: -2px;
        background: radial-gradient(circle at 20% 20%, rgba(249,115,22,0.22), transparent 45%),
                    radial-gradient(circle at 80% 20%, rgba(59,130,246,0.18), transparent 40%);
        pointer-events: none;
    }
    .pgi-hero > * { position: relative; z-index: 1; }
    .pgi-hero h1 { color: #fff; font-weight: 900; letter-spacing: -0.02em; font-size: 1.8rem; margin-bottom: 0.35rem; }
    .pgi-hero .lead { color: rgba(255,255,255,0.82); font-weight: 700; }
    .pgi-hero .breadcrumb { justify-content: center; margin-bottom: 0.75rem; }
    .pgi-hero .breadcrumb-item + .breadcrumb-item::before { color: rgba(255,255,255,0.35); }
    .pgi-hero .breadcrumb a { color: rgba(255,255,255,0.75); font-weight: 700; text-decoration: none; }
    .pgi-hero .breadcrumb a:hover { color: #f97316; }
    .pgi-hero .breadcrumb-item.active { color: rgba(255,255,255,0.55); }
    .pgi-count-chip {
        display: inline-flex; align-items: center; gap: 0.5rem; margin-top: 1rem;
        padding: 0.6rem 1.1rem; border-radius: 999px;
        background: rgba(255,255,255,0.10); border: 1px solid rgba(255,255,255,0.18);
        color: #fff; font-weight: 800;
    }

    /* ─── Barre de filtres ─── */
    .pgi-toolbar {
        display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
        background: rgba(15,23,42,0.55); border: 1px solid rgba(191,219,254,0.18);
        border-radius: 16px; padding: 0.75rem 1rem; margin-bottom: 1.5rem;
        position: sticky; top: 10px; z-index: 50; backdrop-filter: blur(12px);
    }
    .pgi-search {
        flex: 1 1 220px; display: flex; align-items: center; gap: 0.5rem;
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px; padding: 0.45rem 0.8rem;
    }
    .pgi-search i { color: #64748b; }
    .pgi-search input {
        flex: 1; background: transparent; border: none; outline: none;
        color: #fff; font-size: 0.88rem;
    }
    .pgi-search input::placeholder { color: #64748b; }
    .pgi-pills { display: flex; gap: 0.4rem; flex-wrap: wrap; }
    .pgi-pill {
        padding: 0.4rem 0.85rem; border-radius: 999px; font-size: 0.78rem; font-weight: 800;
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.75); cursor: pointer; transition: all 0.15s ease; user-select: none;
    }
    .pgi-pill:hover { border-color: rgba(59,130,246,0.5); }
    .pgi-pill.active { background: #2563eb; border-color: #2563eb; color: #fff; }
    .pgi-pill.pill-dg.active { background: #f97316; border-color: #f97316; }
    .pgi-pill.pill-cm.active { background: #8b5cf6; border-color: #8b5cf6; }
    .pgi-sep { width: 1px; height: 24px; background: rgba(255,255,255,0.12); }
    .pgi-noresults { display: none; text-align: center; padding: 3rem 1rem; color: rgba(255,255,255,0.6); }
    .pgi-noresults i { font-size: 2.5rem; display: block; margin-bottom: 1rem; opacity: 0.4; }

    /* ─── Cartes programme ─── */
    .pgi-card {
        border-radius: 18px; background: rgba(15,23,42,0.55);
        border: 1px solid rgba(255,255,255,0.10); overflow: hidden;
        display: flex; flex-direction: column; height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .pgi-card:hover { transform: translateY(-3px); box-shadow: 0 20px 50px rgba(0,0,0,0.35); }
    .pgi-card.hidden { display: none; }
    .pgi-cover { position: relative; aspect-ratio: 16/8; background: rgba(37,99,235,0.08); overflow: hidden; }
    .pgi-cover img { width: 100%; height: 100%; object-fit: cover; }
    .pgi-cover .pgi-cover-ph {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.15); font-size: 2.5rem;
        background: linear-gradient(135deg, rgba(37,99,235,0.15), rgba(249,115,22,0.10));
    }
    .pgi-status {
        position: absolute; top: 10px; right: 10px;
        padding: 0.3rem 0.75rem; border-radius: 999px;
        font-size: 0.72rem; font-weight: 900; color: #fff;
        backdrop-filter: blur(6px);
    }
    .pgi-status.en_cours { background: rgba(245,158,11,0.9); }
    .pgi-status.a_venir { background: rgba(59,130,246,0.9); }
    .pgi-status.terminee { background: rgba(100,116,139,0.85); }
    .pgi-card-body { padding: 1rem 1.1rem; flex: 1 1 auto; }
    .pgi-card-title { color: #fff; font-weight: 900; font-size: 1.02rem; margin-bottom: 0.4rem; }
    .pgi-card-desc { color: rgba(255,255,255,0.65); font-size: 0.85rem; font-weight: 600; }
    .pgi-chips { display: flex; gap: 0.35rem; flex-wrap: wrap; margin-bottom: 0.6rem; }
    .pgi-chip {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.25rem 0.6rem; border-radius: 999px;
        font-size: 0.72rem; font-weight: 800; color: rgba(255,255,255,0.85);
        background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
    }
    .pgi-next {
        margin-top: 0.75rem; padding: 0.6rem 0.8rem; border-radius: 10px;
        background: rgba(16,185,129,0.10); border: 1px solid rgba(16,185,129,0.3);
        font-size: 0.8rem; color: rgba(255,255,255,0.9); font-weight: 700;
        display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap;
    }
    .pgi-next i { color: #10b981; }
    .pgi-card-foot {
        padding: 0.75rem 1.1rem 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;
        border-top: 1px solid rgba(255,255,255,0.06);
    }
    .pgi-btn {
        border-radius: 999px; font-weight: 800; font-size: 0.8rem;
        padding: 0.45rem 0.9rem; border: none; cursor: pointer;
        display: inline-flex; align-items: center; gap: 0.4rem;
        transition: all 0.15s ease; text-decoration: none;
    }
    .pgi-btn-primary { background: linear-gradient(135deg, #1d4ed8, #2563eb); color: #fff; }
    .pgi-btn-primary:hover { filter: brightness(1.1); color: #fff; }
    .pgi-btn-ghost { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14); color: rgba(255,255,255,0.85); }
    .pgi-btn-ghost:hover { background: rgba(255,255,255,0.14); color: #fff; }
    .pgi-btn-toggle { margin-left: auto; }

    /* Séances dépliables dans la carte */
    .pgi-sessions { display: none; padding: 0 1.1rem 1rem; }
    .pgi-sessions.open { display: block; }
    .pgi-session {
        display: flex; align-items: flex-start; gap: 0.7rem;
        padding: 0.6rem 0.7rem; border-radius: 10px; margin-bottom: 0.4rem;
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07);
    }
    .pgi-session.is-future { background: rgba(16,185,129,0.08); border-color: rgba(16,185,129,0.25); }
    .pgi-session.is-past { opacity: 0.65; }
    .pgi-session-date {
        width: 42px; height: 42px; border-radius: 10px; flex-shrink: 0;
        background: rgba(37,99,235,0.15); border: 1px solid rgba(37,99,235,0.3);
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        color: #fff; line-height: 1;
    }
    .pgi-session.is-future .pgi-session-date { background: rgba(16,185,129,0.15); border-color: rgba(16,185,129,0.35); }
    .pgi-session-date .d { font-size: 0.95rem; font-weight: 900; }
    .pgi-session-date .m { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; opacity: 0.8; }
    .pgi-session-info { flex: 1; min-width: 0; }
    .pgi-session-title { color: #fff; font-weight: 800; font-size: 0.85rem; }
    .pgi-session-meta { color: rgba(255,255,255,0.6); font-size: 0.72rem; font-weight: 700; display: flex; gap: 0.5rem; flex-wrap: wrap; margin-top: 0.15rem; }
    .pgi-session-badge {
        font-size: 0.65rem; font-weight: 900; padding: 0.15rem 0.5rem; border-radius: 999px;
    }
    .pgi-session-badge.online { background: rgba(37,99,235,0.2); color: #93c5fd; }
    .pgi-session-badge.presentielle { background: rgba(249,115,22,0.2); color: #fdba74; }
    .pgi-session-dl { color: rgba(255,255,255,0.5); font-size: 0.8rem; }
    .pgi-session-dl:hover { color: #fff; }

    /* ─── Timeline séances du mois ─── */
    .pgi-month-card {
        background: rgba(15,23,42,0.55); border: 1px solid rgba(191,219,254,0.18);
        border-radius: 18px; padding: 1.25rem; margin-top: 2rem;
    }
    .pgi-month-head { display: flex; align-items: flex-end; justify-content: space-between; margin-bottom: 1rem; flex-wrap: wrap; gap: 0.5rem; }
    .pgi-month-title { font-weight: 950; color: #fff; font-size: 1.15rem; }
    .pgi-month-sub { color: rgba(219,234,254,0.8); font-weight: 700; font-size: 0.85rem; }
    .pgi-month-count {
        background: linear-gradient(135deg, rgba(37,99,235,0.9), rgba(249,115,22,0.9));
        color: #fff; font-weight: 950; border-radius: 999px; padding: 0.4rem 0.9rem;
    }
    .pgi-timeline { position: relative; padding-left: 1.5rem; }
    .pgi-timeline::before {
        content: ''; position: absolute; left: 6px; top: 4px; bottom: 4px;
        width: 2px; background: rgba(255,255,255,0.12); border-radius: 2px;
    }
    .pgi-tl-item { position: relative; padding: 0.5rem 0 0.5rem 0.8rem; }
    .pgi-tl-item::before {
        content: ''; position: absolute; left: -1.28rem; top: 0.85rem;
        width: 12px; height: 12px; border-radius: 50%;
        background: #64748b; border: 2px solid #0b1220;
    }
    .pgi-tl-item.is-future::before { background: #10b981; box-shadow: 0 0 8px rgba(16,185,129,0.6); }
    .pgi-tl-title { color: #fff; font-weight: 800; font-size: 0.9rem; }
    .pgi-tl-meta { color: rgba(255,255,255,0.6); font-size: 0.75rem; font-weight: 700; display: flex; gap: 0.6rem; flex-wrap: wrap; }

    /* ─── Lecteur PDF (book modal) ─── */
    .book-modal-content { background: #0b1220; border: none; color: #fff; }
    .book-modal-header {
        background: #081126; border-bottom: 1px solid rgba(191,219,254,0.12);
        display: flex; justify-content: space-between; align-items: center;
        flex-wrap: wrap; gap: 0.75rem; padding: 0.85rem 1.15rem; z-index: 10;
    }
    #bookModalTitle { color: #fff; font-weight: 900; margin: 0; max-width: 40vw; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .book-toolbar { display: inline-flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
    .book-btn {
        background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
        color: #fff; border-radius: 10px; padding: 0.45rem 0.75rem; font-weight: 700;
        cursor: pointer; transition: all 0.2s ease; display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none;
    }
    .book-btn:hover { background: rgba(255,255,255,0.16); color: #fff; }
    .book-btn:disabled { opacity: 0.4; cursor: not-allowed; }
    .book-page-info { color: rgba(255,255,255,0.92); font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem; }
    .book-page-info input {
        width: 60px; background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
        color: #fff; border-radius: 8px; padding: 0.4rem 0.5rem; text-align: center; font-weight: 700;
    }
    .book-select {
        background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);
        color: #fff; border-radius: 8px; padding: 0.45rem 0.65rem; font-weight: 700; cursor: pointer;
    }
    .book-select option { background: #0b1220; color: #fff; }
    .book-modal-body {
        background: radial-gradient(circle at 50% 50%, #0e1d3a 0%, #0b1220 100%);
        display: flex; align-items: center; justify-content: center;
        overflow: auto; position: relative; padding: 1rem; min-height: calc(100vh - 80px);
    }
    .book-sheet {
        background: #fff; border-radius: 3px 10px 10px 3px;
        box-shadow: 0 25px 80px rgba(0,0,0,0.55), 0 4px 12px rgba(0,0,0,0.25), inset -12px 0 24px rgba(0,0,0,0.04);
        border-left: 5px solid rgba(11,18,32,0.10);
        display: inline-block; max-width: 100%; max-height: calc(100vh - 130px);
        overflow: auto; position: relative;
    }
    .book-page { display: block; transition: opacity 0.2s ease; }
    .book-page-changing { opacity: 0.45; }
    .book-loader {
        position: absolute; inset: 0; display: flex; flex-direction: column;
        align-items: center; justify-content: center; color: #fff; gap: 0.75rem;
        font-weight: 700; background: rgba(11,18,32,0.75); z-index: 20;
    }
    .book-error {
        color: #fecaca; background: rgba(239,68,68,0.14); border: 1px solid rgba(239,68,68,0.32);
        border-radius: 12px; padding: 0.85rem 1.1rem; max-width: 520px; text-align: center; font-weight: 600; z-index: 25;
    }
    @media (max-width: 768px) {
        .pgi-hero h1 { font-size: 1.5rem; }
        #bookModalTitle { max-width: 90vw; }
        .book-toolbar { width: 100%; justify-content: center; }
        .pgi-toolbar { position: static; }
    }
</style>
@endpush

@section('content')
@php
    $studentProgram = (string) ($student->program ?? '');
    $isDgCm = str_contains(strtolower($studentProgram), 'design') && (str_contains(strtolower($studentProgram), 'community') || str_contains(strtolower($studentProgram), 'cm'));

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

    $now = now();
    $dgCount = $programmes->where('canonical_formation', 'Design Graphique')->count();
    $cmCount = $programmes->where('canonical_formation', 'Community Management')->count();
    $enCoursCount = $programmes->where('status', 'en_cours')->count();
    $totalSessions = $programmes->sum('items_count');

    $statusLabels = ['en_cours' => 'En cours', 'a_venir' => 'À venir', 'terminee' => 'Terminé'];
@endphp

<div class="pgi-bg" aria-hidden="true"></div>

{{-- ═══ Hero ═══ --}}
<div class="pgi-hero">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route($dashboardRoute) }}">Accueil</a></li>
            <li class="breadcrumb-item active" aria-current="page">Programme</li>
        </ol>
    </nav>
    <h1>Programmes de formation</h1>
    <div class="lead">{{ $student->program ?? 'Votre formation' }}</div>
    <div class="pgi-count-chip">
        <i class="fas fa-book-open"></i>
        <span>{{ $programmes->count() }} programme{{ $programmes->count() > 1 ? 's' : '' }} • {{ $totalSessions }} séance{{ $totalSessions > 1 ? 's' : '' }}</span>
    </div>
</div>

{{-- ═══ Barre de filtres ═══ --}}
<div class="pgi-toolbar">
    <div class="pgi-search">
        <i class="fas fa-search"></i>
        <input type="text" id="pgiSearch" placeholder="Rechercher un programme, une séance...">
    </div>
    <div class="pgi-sep"></div>
    <div class="pgi-pills" id="statusPills">
        <span class="pgi-pill active" data-status="">Tous</span>
        <span class="pgi-pill" data-status="en_cours">En cours{{ $enCoursCount > 0 ? ' (' . $enCoursCount . ')' : '' }}</span>
        <span class="pgi-pill" data-status="a_venir">À venir</span>
        <span class="pgi-pill" data-status="terminee">Terminés</span>
    </div>
    @if($isDgCm)
    <div class="pgi-sep"></div>
    <div class="pgi-pills" id="formationPills">
        <span class="pgi-pill active" data-formation="">Toutes formations</span>
        <span class="pgi-pill pill-dg" data-formation="Design Graphique">Design Graphique ({{ $dgCount }})</span>
        <span class="pgi-pill pill-cm" data-formation="Community Management">Community Mgmt ({{ $cmCount }})</span>
    </div>
    @endif
</div>

{{-- ═══ Grille des programmes ═══ --}}
<div class="row g-4" id="programmesGrid">
    @foreach($programmes as $index => $programme)
        @php
            $items = $programme->items ?? collect();
            $itemsCount = (int) ($programme->items_count ?? $items->count());
            $monthLabel = null;
            try { $monthLabel = !empty($programme->month_start) ? \Carbon\Carbon::parse($programme->month_start)->translatedFormat('F Y') : null; } catch (\Throwable $e) {}
            $pStatus = $programme->status ?? 'a_venir';
            $pStatusLabel = $statusLabels[$pStatus] ?? 'À venir';
            $imageUrl = !empty($programme->image) ? \App\Models\MediaUrl::fromPath($programme->image) : null;
            $nextItem = $programme->next_item ?? null;
            $searchText = strtolower(($programme->titre ?? '') . ' ' . ($programme->description ?? '') . ' ' . ($monthLabel ?? '') . ' ' . $items->pluck('thematique')->implode(' '));
        @endphp
        <div class="col-12 col-md-6 col-xl-4">
            <div class="pgi-card"
                 data-status="{{ $pStatus }}"
                 data-formation="{{ $programme->canonical_formation ?? '' }}"
                 data-search="{{ $searchText }}">

                <div class="pgi-cover">
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $programme->titre ?? 'Programme' }}" loading="lazy">
                    @else
                        <div class="pgi-cover-ph"><i class="fas fa-book-open"></i></div>
                    @endif
                    <span class="pgi-status {{ $pStatus }}">{{ $pStatusLabel }}</span>
                </div>

                <div class="pgi-card-body">
                    <div class="pgi-chips">
                        @if($monthLabel)<span class="pgi-chip"><i class="fas fa-calendar"></i>{{ $monthLabel }}</span>@endif
                        <span class="pgi-chip"><i class="fas fa-list"></i>{{ $itemsCount }} séance{{ $itemsCount > 1 ? 's' : '' }}</span>
                        @if($isDgCm)<span class="pgi-chip"><i class="fas fa-tag"></i>{{ $programme->canonical_formation }}</span>@endif
                    </div>
                    <div class="pgi-card-title">{{ $programme->titre ?? 'Programme' }}</div>
                    <div class="pgi-card-desc">
                        {{ !empty($programme->description) ? \Illuminate\Support\Str::limit($programme->description, 120) : 'Téléchargez le programme pour consulter le détail.' }}
                    </div>

                    @if($nextItem && !empty($nextItem->session_date))
                        @php
                            $ntime = !empty($nextItem->session_time) ? $nextItem->session_time : '00:00';
                            $ndt = \Carbon\Carbon::parse($nextItem->session_date . ' ' . $ntime);
                        @endphp
                        <div class="pgi-next">
                            <i class="fas fa-bolt"></i>
                            <span>Prochaine : {{ Str::limit($nextItem->thematique ?? 'Séance', 30) }}</span>
                            <span><i class="fas fa-calendar me-1"></i>{{ $ndt->format('d/m') }} <i class="fas fa-clock ms-1 me-1"></i>{{ $ndt->format('H:i') }}</span>
                            @if(($nextItem->type_formation ?? '') === 'presentielle')
                                <span><i class="fas fa-map-marker-alt me-1"></i>{{ $nextItem->lieu ?? 'Présentiel' }}</span>
                            @else
                                <span><i class="fas fa-video me-1"></i>En ligne</span>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Séances dépliables --}}
                @if($itemsCount > 0)
                <div class="pgi-sessions" id="pgiSessions{{ $index }}">
                    @foreach($items as $item)
                        @php
                            $isFuture = false;
                            $dateObj = null;
                            try {
                                if (!empty($item->session_date)) {
                                    $dateObj = \Carbon\Carbon::parse($item->session_date . ' ' . ($item->session_time ?? '00:00'));
                                    $isFuture = $dateObj->isFuture();
                                }
                            } catch (\Throwable $e) {}
                        @endphp
                        <div class="pgi-session {{ $dateObj ? ($isFuture ? 'is-future' : 'is-past') : '' }}">
                            <div class="pgi-session-date">
                                @if($dateObj)
                                    <span class="d">{{ $dateObj->format('d') }}</span>
                                    <span class="m">{{ $dateObj->translatedFormat('M') }}</span>
                                @else
                                    <span class="d">?</span>
                                @endif
                            </div>
                            <div class="pgi-session-info">
                                <div class="pgi-session-title">{{ $item->thematique ?? 'Séance' }}</div>
                                <div class="pgi-session-meta">
                                    <span><i class="fas fa-clock me-1"></i>{{ !empty($item->session_time) ? \Carbon\Carbon::parse($item->session_time)->format('H:i') : '--:--' }}</span>
                                    @if(($item->type_formation ?? '') === 'presentielle' && !empty($item->lieu))
                                        <span><i class="fas fa-map-marker-alt me-1"></i>{{ $item->lieu }}</span>
                                    @endif
                                    @if(!empty($item->type_formation))
                                        <span class="pgi-session-badge {{ ($item->type_formation === 'presentielle') ? 'presentielle' : 'online' }}">
                                            {{ $item->type_formation === 'presentielle' ? 'Présentielle' : 'En ligne' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if(!empty($item->piece_jointe))
                                <a class="pgi-session-dl" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($item->piece_jointe) }}" title="Télécharger la pièce jointe">
                                    <i class="fas fa-download"></i>
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="pgi-card-foot">
                    @if(!empty($programme->fichier_pdf))
                        <button type="button" class="pgi-btn pgi-btn-primary book-open-btn"
                                data-pdf="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}"
                                data-title="{{ $programme->titre ?? 'Programme' }}"
                                onclick="openBook(this.dataset.pdf, this.dataset.title)">
                            <i class="fas fa-book-open"></i>Lire
                        </button>
                        <a class="pgi-btn pgi-btn-ghost" target="_blank" href="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}" title="Télécharger le PDF">
                            <i class="fas fa-download"></i>
                        </a>
                    @else
                        <span class="pgi-btn pgi-btn-ghost" style="opacity:0.5; cursor:not-allowed;"><i class="fas fa-file-pdf"></i>PDF indisponible</span>
                    @endif
                    @if($itemsCount > 0)
                        <button type="button" class="pgi-btn pgi-btn-ghost pgi-btn-toggle" data-target="pgiSessions{{ $index }}">
                            <i class="fas fa-chevron-down"></i><span>Séances ({{ $itemsCount }})</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Aucun résultat / aucun programme --}}
@if($programmes->isEmpty())
    <div class="text-center py-5">
        <i class="fas fa-book-open fa-3x d-block mb-3" style="color:rgba(255,255,255,0.2);"></i>
        <div class="text-white fw-bold">Aucun programme disponible pour le moment.</div>
        <div class="mt-2" style="color:rgba(255,255,255,0.55);">Les programmes publiés par l'administration apparaîtront ici.</div>
    </div>
@else
    <div class="pgi-noresults" id="pgiNoResults">
        <i class="fas fa-search"></i>
        <div class="fw-bold">Aucun programme ne correspond à vos filtres.</div>
    </div>
@endif

{{-- ═══ Séances du mois (timeline) ═══ --}}
@if(($currentMonthSessions ?? collect())->isNotEmpty())
<div class="pgi-month-card">
    <div class="pgi-month-head">
        <div>
            <div class="pgi-month-title"><i class="fas fa-calendar-day me-2" style="color:#f97316;"></i>Séances du mois</div>
            <div class="pgi-month-sub">{{ $now->translatedFormat('F Y') }}</div>
        </div>
        <div class="pgi-month-count">{{ $currentMonthSessions->count() }}</div>
    </div>
    <div class="pgi-timeline">
        @foreach($currentMonthSessions as $item)
            @php
                $isFuture = false;
                try {
                    if (!empty($item->session_date)) {
                        $isFuture = \Carbon\Carbon::parse($item->session_date . ' ' . ($item->session_time ?? '00:00'))->isFuture();
                    }
                } catch (\Throwable $e) {}
            @endphp
            <div class="pgi-tl-item {{ $isFuture ? 'is-future' : '' }}">
                <div class="pgi-tl-title">{{ $item->thematique ?? 'Séance' }}</div>
                <div class="pgi-tl-meta">
                    <span><i class="fas fa-calendar me-1"></i>{{ !empty($item->session_date) ? \Carbon\Carbon::parse($item->session_date)->format('d/m/Y') : '—' }}</span>
                    <span><i class="fas fa-clock me-1"></i>{{ !empty($item->session_time) ? \Carbon\Carbon::parse($item->session_time)->format('H:i') : '--:--' }}</span>
                    @if(!empty($item->programme_title))<span><i class="fas fa-book me-1"></i>{{ $item->programme_title }}</span>@endif
                    @if(($item->type_formation ?? '') === 'presentielle' && !empty($item->lieu))
                        <span><i class="fas fa-map-marker-alt me-1"></i>{{ $item->lieu }}</span>
                    @elseif(!empty($item->type_formation))
                        <span><i class="fas fa-video me-1"></i>En ligne</span>
                    @endif
                    @if(!empty($item->piece_jointe))
                        <a href="{{ \App\Models\MediaUrl::fromPath($item->piece_jointe) }}" target="_blank" style="color:#93c5fd;"><i class="fas fa-download me-1"></i>Support</a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- ═══ Lecteur PDF (livre numérique) ═══ --}}
<div class="modal fade" id="programmeBookModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content book-modal-content">
            <div class="modal-header book-modal-header">
                <h5 class="modal-title" id="bookModalTitle">Programme</h5>
                <div class="book-toolbar">
                    <button type="button" class="book-btn" id="bookPrev" disabled><i class="fas fa-chevron-left"></i></button>
                    <span class="book-page-info">
                        <input type="number" id="bookPageInput" min="1" value="1" aria-label="Page">
                        <span>/</span><span id="bookPageTotal">1</span>
                    </span>
                    <button type="button" class="book-btn" id="bookNext" disabled><i class="fas fa-chevron-right"></i></button>
                    <select class="book-select" id="bookZoom" aria-label="Zoom">
                        <option value="fit">Ajuster</option>
                        <option value="1" selected>100%</option>
                        <option value="1.25">125%</option>
                        <option value="1.5">150%</option>
                        <option value="2">200%</option>
                    </select>
                    <a class="book-btn" id="bookDownload" href="#" target="_blank" title="Télécharger le PDF"><i class="fas fa-download"></i></a>
                    <button type="button" class="book-btn" data-bs-dismiss="modal" aria-label="Fermer"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="modal-body book-modal-body" id="bookBody">
                <div id="bookLoader" class="book-loader">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <span>Chargement du livre numérique…</span>
                </div>
                <div id="bookError" class="book-error" hidden></div>
                <div id="bookSheet" class="book-sheet"><canvas id="bookPageCanvas" class="book-page"></canvas></div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('pgiSearch');
    const cards = document.querySelectorAll('.pgi-card');
    const noResults = document.getElementById('pgiNoResults');
    let activeStatus = '';
    let activeFormation = '';

    function applyFilters() {
        const q = (searchInput ? searchInput.value : '').toLowerCase().trim();
        let visible = 0;
        cards.forEach(card => {
            const matchStatus = !activeStatus || card.dataset.status === activeStatus;
            const matchFormation = !activeFormation || card.dataset.formation === activeFormation;
            const matchSearch = !q || (card.dataset.search || '').includes(q);
            const show = matchStatus && matchFormation && matchSearch;
            card.classList.toggle('hidden', !show);
            if (show) visible++;
        });
        if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
    }

    // Pills statut
    document.querySelectorAll('#statusPills .pgi-pill').forEach(pill => {
        pill.addEventListener('click', function () {
            document.querySelectorAll('#statusPills .pgi-pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeStatus = this.dataset.status;
            applyFilters();
        });
    });

    // Pills formation (étudiants combinés)
    document.querySelectorAll('#formationPills .pgi-pill').forEach(pill => {
        pill.addEventListener('click', function () {
            document.querySelectorAll('#formationPills .pgi-pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeFormation = this.dataset.formation;
            applyFilters();
        });
    });

    if (searchInput) searchInput.addEventListener('input', applyFilters);

    // Toggle séances dans les cartes
    document.querySelectorAll('.pgi-btn-toggle').forEach(btn => {
        btn.addEventListener('click', function () {
            const target = document.getElementById(this.dataset.target);
            if (!target) return;
            const open = target.classList.toggle('open');
            this.querySelector('i').className = open ? 'fas fa-chevron-up' : 'fas fa-chevron-down';
        });
    });
});
</script>

<script>
// ─── Lecteur PDF (livre numérique) ───
(function() {
    const modalEl = document.getElementById('programmeBookModal');
    if (!modalEl) return;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    const titleEl = document.getElementById('bookModalTitle');
    const canvas = document.getElementById('bookPageCanvas');
    const ctx = canvas ? canvas.getContext('2d') : null;
    const sheet = document.getElementById('bookSheet');
    const body = document.getElementById('bookBody');
    const loader = document.getElementById('bookLoader');
    const errorBox = document.getElementById('bookError');
    const prevBtn = document.getElementById('bookPrev');
    const nextBtn = document.getElementById('bookNext');
    const pageInput = document.getElementById('bookPageInput');
    const totalEl = document.getElementById('bookPageTotal');
    const zoomSelect = document.getElementById('bookZoom');
    const downloadLink = document.getElementById('bookDownload');

    let pdfDoc = null, numPages = 0, currentPage = 1, zoom = 1, baseScale = 1, renderTask = null;

    window.openBook = function(url, bookTitle) {
        if (titleEl) titleEl.textContent = bookTitle || 'Programme';
        if (downloadLink) { downloadLink.href = url; downloadLink.style.display = url ? '' : 'none'; }
        modal.show();
        loadPdf(url);
    };

    function loadPdf(url) {
        reset();
        showLoader(true);
        loadPdfJs(function() {
            if (!window.pdfjsLib) { showError("Le lecteur PDF n'a pas pu être chargé."); return; }
            pdfjsLib.getDocument({ url: url, withCredentials: true }).promise.then(function(pdf) {
                pdfDoc = pdf; numPages = pdf.numPages; currentPage = 1; zoom = 1;
                if (zoomSelect) zoomSelect.value = '1';
                showLoader(false);
                renderCurrentPage();
            }).catch(function(err) {
                console.error(err);
                showError('Impossible de charger le PDF. Vérifiez que le fichier est accessible ou téléchargez-le directement.');
            });
        });
    }

    function loadPdfJs(callback) {
        if (window.pdfjsLib) { callback(); return; }
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js';
        script.onload = function() {
            if (window.pdfjsLib) {
                window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';
            }
            callback();
        };
        script.onerror = function() { showError("Le lecteur PDF (PDF.js) n'a pas pu être chargé."); };
        document.head.appendChild(script);
    }

    function reset() {
        if (renderTask) { try { renderTask.cancel(); } catch (e) {} renderTask = null; }
        pdfDoc = null; numPages = 0; currentPage = 1; zoom = 1; baseScale = 1;
        if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);
        if (pageInput) pageInput.value = 1;
        if (totalEl) totalEl.textContent = 1;
        if (prevBtn) prevBtn.disabled = true;
        if (nextBtn) nextBtn.disabled = true;
        if (errorBox) errorBox.hidden = true;
    }

    function showLoader(show) { if (loader) loader.style.display = show ? 'flex' : 'none'; }
    function showError(msg) { showLoader(false); if (errorBox) { errorBox.textContent = msg; errorBox.hidden = false; } }

    function renderCurrentPage() {
        if (!pdfDoc || !canvas || !ctx || currentPage < 1 || currentPage > numPages) return;
        canvas.classList.add('book-page-changing');
        pdfDoc.getPage(currentPage).then(function(page) {
            const dpr = window.devicePixelRatio || 1;
            const viewport1 = page.getViewport({ scale: 1 });
            const availableW = Math.max(220, sheet.getBoundingClientRect().width - 32);
            const availableH = Math.max(220, body.getBoundingClientRect().height - 100);
            baseScale = Math.min(availableW / viewport1.width, availableH / viewport1.height, 3);
            const currentScale = zoom === 'fit' ? baseScale : baseScale * zoom;
            const viewport = page.getViewport({ scale: currentScale * dpr });
            canvas.width = viewport.width;
            canvas.height = viewport.height;
            canvas.style.width = (viewport.width / dpr) + 'px';
            canvas.style.height = (viewport.height / dpr) + 'px';
            if (renderTask) { try { renderTask.cancel(); } catch (e) {} }
            renderTask = page.render({ canvasContext: ctx, viewport: viewport });
            renderTask.promise.then(function() {
                canvas.classList.remove('book-page-changing');
                updateControls();
            }).catch(function() { canvas.classList.remove('book-page-changing'); });
        }).catch(function() { showError('Erreur lors du rendu de la page.'); });
    }

    function updateControls() {
        if (pageInput) pageInput.value = currentPage;
        if (totalEl) totalEl.textContent = numPages;
        if (prevBtn) prevBtn.disabled = currentPage <= 1;
        if (nextBtn) nextBtn.disabled = currentPage >= numPages;
    }

    function changePage(delta) {
        const n = currentPage + delta;
        if (n >= 1 && n <= numPages) { currentPage = n; renderCurrentPage(); }
    }
    function goToPage(v) {
        let n = parseInt(v, 10);
        if (!Number.isFinite(n)) return;
        currentPage = Math.max(1, Math.min(numPages, n));
        renderCurrentPage();
    }

    if (prevBtn) prevBtn.addEventListener('click', () => changePage(-1));
    if (nextBtn) nextBtn.addEventListener('click', () => changePage(1));
    if (pageInput) {
        pageInput.addEventListener('change', function() { goToPage(this.value); });
        pageInput.addEventListener('keydown', function(e) { if (e.key === 'Enter') { goToPage(this.value); e.preventDefault(); } });
    }
    if (zoomSelect) {
        zoomSelect.addEventListener('change', function() {
            zoom = this.value === 'fit' ? 'fit' : parseFloat(this.value);
            if (pdfDoc) renderCurrentPage();
        });
    }

    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => { if (pdfDoc) renderCurrentPage(); }, 250);
    });

    modalEl.addEventListener('shown.bs.modal', () => { if (pdfDoc) renderCurrentPage(); });
    modalEl.addEventListener('hidden.bs.modal', reset);
})();
</script>
@endpush
