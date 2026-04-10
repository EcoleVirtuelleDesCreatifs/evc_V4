@extends('layouts.admin')

@section('title', 'Gestion des Programmes')

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
    }

    .stat-card-primary {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    }

    .stat-card-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .stat-card-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-card-cyan {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }

    .stat-label {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .btn-export {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .programme-card {
        background: #1e293b;
        border: 2px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .programme-card:hover {
        border-color: #4fc3f7;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(79, 195, 247, 0.3);
    }

    .pdf-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .formation-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-design {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
    }

    .badge-cm {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        color: white;
    }

    .badge-gi {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .badge-ia {
        background: linear-gradient(135deg, #26c6da, #00acc1);
        color: white;
    }

    .badge-tous {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        color: white;
    }

    .btn-download {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #1e293b;
        border-radius: 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #475569;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: #e2e8f0;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
    }

    .tools-card {
        background: #1e293b;
        border: 2px solid #334155;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .tools-input {
        background: rgba(15, 23, 42, 0.85);
        border: 1px solid rgba(51, 65, 85, 0.7);
        color: #e2e8f0;
        border-radius: 12px;
    }

    .tools-input::placeholder {
        color: #94a3b8;
    }

    .tools-input:focus {
        background: rgba(15, 23, 42, 0.95);
        border-color: #4fc3f7;
        box-shadow: 0 0 0 0.25rem rgba(79, 195, 247, 0.15);
        color: #e2e8f0;
    }

    .programme-accordion {
        background: #1e293b;
        border: 2px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }

    .programme-wrapper .programme-accordion {
        height: 100%;
    }

    .programme-accordion.theme-dg { border-left: 6px solid #4fc3f7; }
    .programme-accordion.theme-cm { border-left: 6px solid #e1306c; }
    .programme-accordion.theme-dgcm { border-left: 6px solid #f59e0b; }
    .programme-accordion.theme-gi { border-left: 6px solid #ff9800; }
    .programme-accordion.theme-ia { border-left: 6px solid #26c6da; }
    .programme-accordion.theme-all { border-left: 6px solid #9c27b0; }

    .formation-chip {
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .35rem .7rem;
        border-radius: 999px;
        font-weight: 800;
        font-size: .85rem;
        color: #fff;
        border: 1px solid rgba(255,255,255,0.14);
        box-shadow: 0 10px 25px rgba(0,0,0,0.25);
        white-space: nowrap;
    }

    .formation-chip.dg { background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); }
    .formation-chip.cm { background: linear-gradient(135deg, #833AB4 0%, #E1306C 60%, #F56040 100%); }
    .formation-chip.dgcm { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    .formation-chip.gi { background: linear-gradient(135deg, #ff9800 0%, #ff6f00 100%); }
    .formation-chip.ia { background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%); }
    .formation-chip.all { background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); }

    .programme-accordion-header {
        padding: 1rem 1.25rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #334155;
    }

    .programme-header-left {
        display: flex;
        align-items: center;
        gap: .9rem;
        min-width: 260px;
        flex: 1 1 auto;
    }

    .programme-cover-thumb {
        width: 74px;
        height: 54px;
        border-radius: 12px;
        overflow: hidden;
        background: rgba(15, 23, 42, 0.25);
        border: 1px solid rgba(51, 65, 85, 0.8);
        flex: 0 0 auto;
    }

    .programme-cover-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .programme-cover-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: rgba(226, 232, 240, 0.55);
        background: linear-gradient(135deg, rgba(79, 195, 247, 0.16), rgba(225, 48, 108, 0.10));
    }

    .programme-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.35rem;
        color: #94a3b8;
        font-size: 0.875rem;
    }

    .programme-meta span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(51, 65, 85, 0.7);
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
    }

    .programme-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .btn-toggle {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        border: none;
        color: white;
        padding: 0.5rem 0.9rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-toggle:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .items-table {
        width: 100%;
        color: #e2e8f0;
        border-collapse: collapse;
    }

    .items-table th,
    .items-table td {
        padding: 0.75rem 0.9rem;
        border-bottom: 1px solid rgba(51, 65, 85, 0.7);
        vertical-align: top;
    }

    .items-table th {
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        background: rgba(15, 23, 42, 0.55);
    }

    .item-title {
        font-weight: 700;
        color: #e2e8f0;
    }

    .item-sub {
        color: #94a3b8;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-book me-2"></i>Gestion des Programmes
        </h1>
        <a href="{{ route('admin.programmes.create') }}" class="btn-export">
            <i class="fas fa-plus me-2"></i>Ajouter un Programme
        </a>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Programmes</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <a href="#currentMonthProgrammes" style="text-decoration: none;">
                <div class="stat-card stat-card-cyan">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ isset($programmesCurrentMonth) ? $programmesCurrentMonth->count() : 0 }}</h3>
                        <p class="stat-label">Formations en cours ({{ now()->translatedFormat('F') }})</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['ce_mois'] }}</h3>
                    <p class="stat-label">Ajoutés ce Mois</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['tous'] }}</h3>
                    <p class="stat-label">Toutes Formations</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['design_graphique'] + $stats['community_management'] + $stats['gestion_informatique'] + $stats['intelligence_artificielle'] }}</h3>
                    <p class="stat-label">Spécifiques</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par formation -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['design_graphique'] }}</h3>
                    <p class="stat-label">Design Graphique</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['community_management'] }}</h3>
                    <p class="stat-label">Community Management</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['gestion_informatique'] }}</h3>
                    <p class="stat-label">Gestion Informatique</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-cyan">
                <div class="stat-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['intelligence_artificielle'] }}</h3>
                    <p class="stat-label">Intelligence Artificielle</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @php
        $months = $programmes
            ->map(fn($p) => $p->month_start ?? null)
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();
    @endphp

    @if(!$programmes->isEmpty())
        <div class="tools-card">
            <div class="row g-3 align-items-center">
                <div class="col-lg-6">
                    <input type="text" id="programmeSearch" class="form-control tools-input" placeholder="Rechercher un programme, une séance, un lieu...">
                </div>
                <div class="col-lg-3">
                    <select id="programmeFormationFilter" class="form-select tools-input">
                        <option value="">Toutes les formations</option>
                        <option value="Design Graphique">Design Graphique</option>
                        <option value="Community Management">Community Management</option>
                        <option value="Design Graphique & Community Manager">Design Graphique & Community Manager</option>
                        <option value="Gestion Informatique">Gestion Informatique</option>
                        <option value="Intelligence Artificielle">Intelligence Artificielle</option>
                        <option value="Toutes">Toutes</option>
                        <option value="Ciblage">Ciblage</option>
                    </select>
                </div>
                <div class="col-lg-3">
                    <select id="programmeMonthFilter" class="form-select tools-input">
                        <option value="">Tous les mois</option>
                        @foreach($months as $m)
                            <option value="{{ $m }}">{{ \Carbon\Carbon::parse($m)->translatedFormat('F Y') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endif

    @if(isset($programmesCurrentMonth) && !$programmesCurrentMonth->isEmpty())
        @php
            $currentMonthByFormation = $programmesCurrentMonth
                ->groupBy(fn($p) => (string) ($p->formation ?? ''));
        @endphp

        <div class="mb-4" id="currentMonthProgrammes">
            <div class="d-flex align-items-center justify-content-between mb-2" style="padding: 0 .25rem;">
                <div class="formation-chip all">
                    <i class="fas fa-calendar-check"></i>
                    Formations en cours ({{ now()->translatedFormat('F Y') }})
                </div>
                <span class="badge bg-secondary">{{ $programmesCurrentMonth->count() }}</span>
            </div>

            @foreach($currentMonthByFormation as $formationGroup => $formationProgrammes)
                @php
                    $fg = (string) ($formationGroup ?? '');
                    $fgLower = strtolower($fg);
                    $formationKey = 'all';
                    if (str_contains($fgLower, 'design') && (str_contains($fgLower, 'community') || str_contains($fgLower, 'manager'))) {
                        $formationKey = 'dgcm';
                    } elseif (str_contains($fgLower, 'design')) {
                        $formationKey = 'dg';
                    } elseif (str_contains($fgLower, 'community')) {
                        $formationKey = 'cm';
                    } elseif (str_contains($fgLower, 'informatique')) {
                        $formationKey = 'gi';
                    } elseif (str_contains($fgLower, 'intelligence')) {
                        $formationKey = 'ia';
                    } elseif ($fgLower === 'toutes' || $fgLower === 'tous' || $fgLower === 'toutes les formations' || $fgLower === 'toute') {
                        $formationKey = 'all';
                    }

                    $formationIcons = [
                        'dg' => 'fa-palette',
                        'cm' => 'fa-bullhorn',
                        'dgcm' => 'fa-object-group',
                        'gi' => 'fa-laptop-code',
                        'ia' => 'fa-brain',
                        'all' => 'fa-layer-group',
                    ];
                    $fgIcon = $formationIcons[$formationKey] ?? 'fa-graduation-cap';
                    $sectionLabel = $fg !== '' ? $fg : 'Formation';
                @endphp

                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-2" style="padding: 0 .25rem;">
                        <div class="formation-chip {{ $formationKey }}">
                            <i class="fas {{ $fgIcon }}"></i>
                            {{ $sectionLabel }}
                        </div>
                        <span class="badge bg-secondary">{{ $formationProgrammes->count() }}</span>
                    </div>

                    <div class="row g-4">
                        @foreach($formationProgrammes as $programme)
                            @php
                                $formation = $programme->formation ?? '';
                                $monthStart = $programme->month_start ?? '';
                                $items = $programme->items ?? collect();
                                $formationClass = $formationKey === 'dg' ? 'theme-dg'
                                    : ($formationKey === 'cm' ? 'theme-cm'
                                        : ($formationKey === 'dgcm' ? 'theme-dgcm'
                                            : ($formationKey === 'gi' ? 'theme-gi'
                                                : ($formationKey === 'ia' ? 'theme-ia' : 'theme-all'))));
                                $searchText = strtolower(
                                    ($programme->titre ?? '') . ' ' .
                                    ($programme->description ?? '') . ' ' .
                                    ($formation ?? '') . ' ' .
                                    $items->pluck('thematique')->implode(' ') . ' ' .
                                    $items->pluck('lieu')->implode(' ') . ' ' .
                                    $items->pluck('description')->implode(' ')
                                );
                            @endphp

                            <div class="col-12 col-md-6 col-xl-4 programme-wrapper" data-formation="{{ $formation }}" data-month="{{ $monthStart }}" data-search="{{ $searchText }}">
                                <div class="programme-accordion {{ $formationClass }}">
                                    <div class="programme-accordion-header">
                                        @php
                                            $programmeImageUrl = null;
                                            try {
                                                if (property_exists($programme, 'image') && !empty($programme->image)) {
                                                    $programmeImageUrl = \App\Models\MediaUrl::fromPath($programme->image);
                                                }
                                            } catch (\Throwable $e) {
                                                $programmeImageUrl = null;
                                            }

                                            $nextItem = $programme->next_item ?? null;
                                            $nextDateLabel = null;
                                            $nextTimeLabel = null;
                                            $nextType = null;
                                            $nextLieu = null;
                                            $isPresentiel = false;
                                            try {
                                                if (!empty($nextItem?->session_date)) {
                                                    $time = !empty($nextItem?->session_time) ? $nextItem->session_time : '00:00';
                                                    $dt = \Carbon\Carbon::parse($nextItem->session_date . ' ' . $time);
                                                    $nextDateLabel = $dt->format('d/m');
                                                    $nextTimeLabel = $dt->format('H:i');
                                                    $nextType = $nextItem->type_formation ?? null;
                                                    $nextLieu = $nextItem->lieu ?? null;
                                                    $isPresentiel = in_array(strtolower((string) $nextType), ['presentielle', 'presentiel'], true);
                                                }
                                            } catch (\Throwable $e) {
                                                $nextDateLabel = null;
                                                $nextTimeLabel = null;
                                                $nextType = null;
                                                $nextLieu = null;
                                                $isPresentiel = false;
                                            }
                                        @endphp
                                        <div class="programme-header-left">
                                            <div class="programme-cover-thumb">
                                                @if(!empty($programmeImageUrl))
                                                    <img src="{{ $programmeImageUrl }}" alt="Illustration" loading="lazy">
                                                @else
                                                    <div class="programme-cover-thumb-placeholder">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div style="min-width: 260px;">
                                                <div style="color:#e2e8f0; font-weight:800; font-size:1.05rem;">
                                                    {{ $programme->titre }}
                                                </div>
                                                <div class="programme-meta">
                                                    <span>
                                                        <i class="fas fa-graduation-cap"></i>
                                                        {{ $formation }}
                                                    </span>
                                                    @if(!empty($monthStart))
                                                        <span>
                                                            <i class="fas fa-calendar"></i>
                                                            {{ \Carbon\Carbon::parse($monthStart)->translatedFormat('F Y') }}
                                                        </span>
                                                    @endif
                                                    <span>
                                                        <i class="fas fa-list"></i>
                                                        {{ (int) ($programme->items_count ?? 0) }} séance(s)
                                                    </span>
                                                    @if(!empty($nextDateLabel) && !empty($nextTimeLabel))
                                                        <span>
                                                            <i class="fas fa-calendar"></i>
                                                            {{ $nextDateLabel }}
                                                        </span>
                                                        <span>
                                                            <i class="fas fa-clock"></i>
                                                            {{ $nextTimeLabel }}
                                                        </span>
                                                        <span>
                                                            <i class="fas {{ $isPresentiel ? 'fa-map-marker-alt' : 'fa-video' }}"></i>
                                                            @if($isPresentiel)
                                                                Présentiel{{ !empty($nextLieu) ? ' • ' . $nextLieu : '' }}
                                                            @else
                                                                En ligne
                                                            @endif
                                                        </span>
                                                    @else
                                                        <span>
                                                            <i class="fas fa-bolt"></i>
                                                            Aucune séance à venir
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="programme-actions">
                                            <button class="btn-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#current_prog_{{ $programme->id }}" aria-expanded="false">
                                                <i class="fas fa-eye me-1"></i>
                                                Détails
                                            </button>
                                            <a href="{{ route('admin.programmes.edit', $programme->id) }}" class="btn-toggle" style="border-radius:10px; font-weight:600; text-decoration:none;">
                                                <i class="fas fa-edit me-1"></i>
                                                Modifier
                                            </a>
                                            <form action="{{ route('admin.programmes.destroy', $programme->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce programme ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" style="border-radius:10px; font-weight:600;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div id="current_prog_{{ $programme->id }}" class="collapse">
                                        <div style="padding: 1rem 1.25rem;">
                                            @if($programme->description)
                                                <div style="color:#94a3b8; margin-bottom: 1rem;">
                                                    {{ $programme->description }}
                                                </div>
                                            @endif

                                            <!-- PDF du programme -->
                                            @if(!empty($programme->fichier_pdf))
                                                <div style="margin-bottom: 1rem;">
                                                    <div style="color:#e2e8f0; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem;">
                                                        <i class="fas fa-file-pdf me-2" style="color:#ef4444;"></i>PDF du programme
                                                    </div>
                                                    @php
                                                        try {
                                                            $pdfUrl = \App\Models\MediaUrl::fromPath($programme->fichier_pdf);
                                                        } catch (\Throwable $e) {
                                                            $pdfUrl = null;
                                                        }
                                                    @endphp
                                                    @if(!empty($pdfUrl))
                                                        <a href="{{ $pdfUrl }}" target="_blank" class="btn-download">
                                                            <i class="fas fa-download me-1"></i>Télécharger le PDF
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Ciblage -->
                                            <div style="margin-bottom: 1rem;">
                                                <div style="color:#e2e8f0; font-weight:600; font-size:0.9rem; margin-bottom:0.5rem;">
                                                    <i class="fas fa-bullseye me-2" style="color:#4fc3f7;"></i>Ciblage
                                                </div>
                                                <div style="color:#94a3b8; font-size:0.85rem;">
                                                    @php
                                                        $isStudentTargeting = !empty($programme->student_ids) && is_string($programme->student_ids);
                                                        $studentIds = [];
                                                        if ($isStudentTargeting) {
                                                            try {
                                                                $studentIds = json_decode($programme->student_ids, true) ?? [];
                                                            } catch (\Throwable $e) {
                                                                $studentIds = [];
                                                            }
                                                            $isStudentTargeting = !empty($studentIds);
                                                        }
                                                    @endphp
                                                    @if($isStudentTargeting)
                                                        <span style="color:#f59e0b; font-weight:600;">
                                                            <i class="fas fa-user-graduate me-1"></i>Étudiants spécifiques ({{ count($studentIds) }})
                                                        </span>
                                                        @if(!empty($studentIds))
                                                            <div style="margin-top:0.5rem; padding:0.5rem; background:rgba(245, 158, 11, 0.1); border-radius:8px;">
                                                                @php
                                                                    $targetedStudents = DB::table('students')
                                                                        ->whereIn('students.id', $studentIds)
                                                                        ->leftJoin('users', 'students.user_id', '=', 'users.id')
                                                                        ->select('students.*', 'users.email')
                                                                        ->get();
                                                                @endphp
                                                                @foreach($targetedStudents as $ts)
                                                                    <div style="margin-bottom:0.25rem;">
                                                                        <i class="fas fa-user me-1"></i>
                                                                        {{ $ts->first_name }} {{ $ts->last_name }}
                                                                        @if(!empty($ts->email))
                                                                            <span style="color:#64748b;">({{ $ts->email }})</span>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    @else
                                                        <span style="color:#10b981; font-weight:600;">
                                                            <i class="fas fa-graduation-cap me-1"></i>Formation : {{ $programme->formation ?? 'Non défini' }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            @if(($programme->items ?? collect())->isEmpty())
                                                <div class="empty-state" style="padding: 1.5rem 1rem;">
                                                    <i class="fas fa-inbox"></i>
                                                    <h3>Aucune séance</h3>
                                                    <p>Ce programme ne contient pas encore de séances.</p>
                                                </div>
                                            @else
                                                <table class="items-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Thématique</th>
                                                            <th>Date</th>
                                                            <th>Heure</th>
                                                            <th>Type</th>
                                                            <th>Lieu</th>
                                                            <th>Pièce jointe</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($programme->items as $it)
                                                            <tr>
                                                                <td>
                                                                    <div class="item-title">{{ $it->thematique ?? '' }}</div>
                                                                    @if(!empty($it->description))
                                                                        <div class="item-sub">{{ $it->description }}</div>
                                                                    @endif
                                                                </td>
                                                                <td>{{ !empty($it->session_date) ? \Carbon\Carbon::parse($it->session_date)->format('d/m/Y') : '' }}</td>
                                                                <td>{{ !empty($it->session_time) ? \Carbon\Carbon::parse($it->session_time)->format('H:i') : '' }}</td>
                                                                <td>{{ ($it->type_formation ?? '') === 'presentielle' ? 'Présentielle' : 'En ligne' }}</td>
                                                                <td>{{ $it->lieu ?? '—' }}</td>
                                                                <td>
                                                                    @if(!empty($it->piece_jointe))
                                                                        @php
                                                                            $url = \App\Models\MediaUrl::fromPath($it->piece_jointe);
                                                                        @endphp
                                                                        <a href="{{ $url }}" target="_blank" class="btn-download" style="padding: 0.35rem 0.75rem;">
                                                                            <i class="fas fa-paperclip me-1"></i>
                                                                            Ouvrir
                                                                        </a>
                                                                    @else
                                                                        —
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Liste des programmes -->
    @if($programmes->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucun programme disponible</h3>
            <p>Commencez par ajouter un programme de formation</p>
            <a href="{{ route('admin.programmes.create') }}" class="btn-export mt-3">
                <i class="fas fa-plus me-2"></i>Ajouter un Programme
            </a>
        </div>
    @else
        <div style="background: #1e293b; border: 2px solid #334155; border-radius: 16px; overflow: hidden;">
            <table class="table" style="color: #e2e8f0; margin-bottom: 0;">
                <thead>
                    <tr style="background: rgba(15, 23, 42, 0.55);">
                        <th style="padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">Image</th>
                        <th style="padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">Titre</th>
                        <th style="padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">Formation</th>
                        <th style="padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">Mois</th>
                        <th style="padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">Séances</th>
                        <th style="padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">PDF</th>
                        <th style="padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">Ciblage</th>
                        <th style="padding: 1rem; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($programmes as $programme)
                        @php
                            $formation = $programme->formation ?? '';
                            $monthStart = $programme->month_start ?? '';
                            $items = $programme->items ?? collect();

                            $programmeImageUrl = null;
                            try {
                                if (property_exists($programme, 'image') && !empty($programme->image)) {
                                    $programmeImageUrl = \App\Models\MediaUrl::fromPath($programme->image);
                                }
                            } catch (\Throwable $e) {
                                $programmeImageUrl = null;
                            }

                            $isStudentTargeting = !empty($programme->student_ids) && is_string($programme->student_ids);
                            $studentIds = [];
                            if ($isStudentTargeting) {
                                try {
                                    $studentIds = json_decode($programme->student_ids, true) ?? [];
                                } catch (\Throwable $e) {
                                    $studentIds = [];
                                }
                                $isStudentTargeting = !empty($studentIds);
                            }

                            $pdfUrl = null;
                            try {
                                if (!empty($programme->fichier_pdf)) {
                                    $pdfUrl = \App\Models\MediaUrl::fromPath($programme->fichier_pdf);
                                }
                            } catch (\Throwable $e) {
                                $pdfUrl = null;
                            }
                        @endphp
                        <tr style="border-bottom: 1px solid rgba(51, 65, 85, 0.7);">
                            <td style="padding: 1rem; vertical-align: middle;">
                                <div style="width: 60px; height: 45px; border-radius: 8px; overflow: hidden; background: rgba(15, 23, 42, 0.25); border: 1px solid rgba(51, 65, 85, 0.8);">
                                    @if(!empty($programmeImageUrl))
                                        <img src="{{ $programmeImageUrl }}" alt="Illustration" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: rgba(226, 232, 240, 0.55);">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 1rem; vertical-align: middle;">
                                <div style="font-weight: 700; color: #e2e8f0;">{{ $programme->titre }}</div>
                                @if(!empty($programme->description))
                                    <div style="font-size: 0.85rem; color: #94a3b8; margin-top: 0.25rem;">{{ \Illuminate\Support\Str::limit($programme->description, 80) }}</div>
                                @endif
                            </td>
                            <td style="padding: 1rem; vertical-align: middle;">
                                <span class="formation-badge badge-design">{{ $formation }}</span>
                            </td>
                            <td style="padding: 1rem; vertical-align: middle;">
                                @if(!empty($monthStart))
                                    <div style="color: #94a3b8;">
                                        <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($monthStart)->translatedFormat('F Y') }}
                                    </div>
                                @else
                                    <span style="color: #64748b;">—</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; vertical-align: middle;">
                                <span class="badge bg-info">{{ (int) ($programme->items_count ?? 0) }}</span>
                            </td>
                            <td style="padding: 1rem; vertical-align: middle;">
                                @if(!empty($pdfUrl))
                                    <a href="{{ $pdfUrl }}" target="_blank" class="btn-download" style="padding: 0.35rem 0.75rem;">
                                        <i class="fas fa-file-pdf me-1"></i>Télécharger
                                    </a>
                                @else
                                    <span style="color: #64748b;">—</span>
                                @endif
                            </td>
                            <td style="padding: 1rem; vertical-align: middle;">
                                @if($isStudentTargeting)
                                    <span style="color: #f59e0b; font-weight: 600;">
                                        <i class="fas fa-user-graduate me-1"></i>{{ count($studentIds) }} étudiants
                                    </span>
                                @else
                                    <span style="color: #10b981; font-weight: 600;">
                                        <i class="fas fa-graduation-cap me-1"></i>Formation
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 1rem; vertical-align: middle;">
                                <div style="display: flex; gap: 0.5rem;">
                                    <button class="btn-toggle btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#prog_table_{{ $programme->id }}" aria-expanded="false" style="padding: 0.35rem 0.75rem;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.programmes.edit', $programme->id) }}" class="btn-toggle" style="padding: 0.35rem 0.75rem; text-decoration: none;">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.programmes.destroy', $programme->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce programme ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="padding: 0.35rem 0.75rem;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" style="padding: 0; border-bottom: 1px solid rgba(51, 65, 85, 0.7);">
                                <div id="prog_table_{{ $programme->id }}" class="collapse" style="padding: 1rem; background: rgba(15, 23, 42, 0.3);">
                                    @if($programme->description)
                                        <div style="color: #94a3b8; margin-bottom: 1rem;">
                                            <strong style="color: #e2e8f0;">Description :</strong> {{ $programme->description }}
                                        </div>
                                    @endif

                                    <!-- Ciblage détaillé -->
                                    <div style="margin-bottom: 1rem;">
                                        <div style="color: #e2e8f0; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">
                                            <i class="fas fa-bullseye me-2" style="color: #4fc3f7;"></i>Détails du ciblage
                                        </div>
                                        @if($isStudentTargeting && !empty($studentIds))
                                            @php
                                                $targetedStudents = DB::table('students')
                                                    ->whereIn('students.id', $studentIds)
                                                    ->leftJoin('users', 'students.user_id', '=', 'users.id')
                                                    ->select('students.*', 'users.email')
                                                    ->get();
                                            @endphp
                                            <div style="padding: 0.5rem; background: rgba(245, 158, 11, 0.1); border-radius: 8px;">
                                                @foreach($targetedStudents as $ts)
                                                    <div style="margin-bottom: 0.25rem; color: #94a3b8;">
                                                        <i class="fas fa-user me-1"></i>
                                                        {{ $ts->first_name }} {{ $ts->last_name }}
                                                        @if(!empty($ts->email))
                                                            <span style="color: #64748b;">({{ $ts->email }})</span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div style="color: #94a3b8;">
                                                <i class="fas fa-graduation-cap me-1"></i>Formation destinataire : <strong style="color: #10b981;">{{ $programme->formation ?? 'Non défini' }}</strong>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Séances -->
                                    @if(($programme->items ?? collect())->isEmpty())
                                        <div style="padding: 1.5rem 1rem; text-align: center; border: 1px dashed rgba(255,255,255,0.14); border-radius: 12px;">
                                            <i class="fas fa-inbox" style="font-size: 2rem; color: #475569; margin-bottom: 0.5rem;"></i>
                                            <div style="color: #e2e8f0; font-weight: 600;">Aucune séance</div>
                                            <div style="color: #94a3b8; font-size: 0.85rem;">Ce programme ne contient pas encore de séances.</div>
                                        </div>
                                    @else
                                        @php
                                            $now = now();
                                            $items = $programme->items ?? collect();
                                        @endphp
                                        <table class="items-table" style="margin-top: 1rem;">
                                            <thead>
                                                <tr>
                                                    <th>Thématique</th>
                                                    <th>Date</th>
                                                    <th>Heure</th>
                                                    <th>Type</th>
                                                    <th>Lieu</th>
                                                    <th>Pièce jointe</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($programme->items as $it)
                                                    <tr>
                                                        <td>
                                                            <div class="item-title">{{ $it->thematique ?? '' }}</div>
                                                            @if(!empty($it->description))
                                                                <div class="item-sub">{{ $it->description }}</div>
                                                            @endif
                                                        </td>
                                                        <td>{{ !empty($it->session_date) ? \Carbon\Carbon::parse($it->session_date)->format('d/m/Y') : '' }}</td>
                                                        <td>{{ !empty($it->session_time) ? \Carbon\Carbon::parse($it->session_time)->format('H:i') : '' }}</td>
                                                        <td>{{ ($it->type_formation ?? '') === 'presentielle' ? 'Présentielle' : 'En ligne' }}</td>
                                                        <td>{{ $it->lieu ?? '—' }}</td>
                                                        <td>
                                                            @if(!empty($it->piece_jointe))
                                                                @php
                                                                    $url = \App\Models\MediaUrl::fromPath($it->piece_jointe);
                                                                @endphp
                                                                <a href="{{ $url }}" target="_blank" class="btn-download" style="padding: 0.35rem 0.75rem;">
                                                                    <i class="fas fa-paperclip me-1"></i>Ouvrir
                                                                </a>
                                                            @else
                                                                —
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>



@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('programmeSearch');
    const formationFilter = document.getElementById('programmeFormationFilter');
    const monthFilter = document.getElementById('programmeMonthFilter');

    function normalize(v) {
        return (v || '').toString().toLowerCase().trim();
    }

    function applyFilters() {
        const search = normalize(searchInput ? searchInput.value : '');
        const formation = normalize(formationFilter ? formationFilter.value : '');
        const month = normalize(monthFilter ? monthFilter.value : '');

        document.querySelectorAll('.programme-wrapper').forEach(el => {
            const elSearch = normalize(el.getAttribute('data-search'));
            const elFormation = normalize(el.getAttribute('data-formation'));
            const elMonth = normalize(el.getAttribute('data-month'));

            const matchSearch = !search || elSearch.includes(search);
            const matchFormation = !formation || elFormation === normalize(formation);
            const matchMonth = !month || elMonth === normalize(month);

            el.style.display = (matchSearch && matchFormation && matchMonth) ? '' : 'none';
        });

        document.querySelectorAll('[data-formation-section]').forEach(section => {
            const anyVisible = Array.from(section.querySelectorAll('.programme-wrapper'))
                .some(p => p.style.display !== 'none');
            section.style.display = anyVisible ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (formationFilter) formationFilter.addEventListener('change', applyFilters);
    if (monthFilter) monthFilter.addEventListener('change', applyFilters);

    applyFilters();
});
</script>
@endpush
