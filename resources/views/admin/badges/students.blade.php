@extends('layouts.admin')

@section('title', $title)

@push('styles')
<style>
    /* Reprendre le design des stats de /admin/formations */
    .stat-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
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

    /* Palettes demandées */
    .stat-card-actifs {
        background: linear-gradient(135deg, #1b3a7a 0%, #2a4f9c 100%);
    }

    .stat-card-dg {
        background: linear-gradient(135deg, var(--evc-dg) 0%, var(--evc-dg-2) 100%);
    }

    .stat-card-dgcm {
        background: linear-gradient(135deg, var(--evc-dgcm) 0%, var(--evc-dgcm-2) 100%);
    }

    .stat-card-cm {
        background: linear-gradient(135deg, var(--evc-cm-1) 0%, var(--evc-cm-2) 35%, var(--evc-cm-3) 70%, var(--evc-cm-4) 100%);
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
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
        min-width: 0;
    }

    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        margin: 0;
        line-height: 1;
    }

    .stat-label {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .4rem .75rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.16);
        background: rgba(255,255,255,0.06);
        color: rgba(255,255,255,0.92);
        font-weight: 700;
        font-size: .85rem;
        cursor: pointer;
        user-select: none;
        transition: transform .15s ease, border-color .15s ease, background .15s ease;
    }

    .chip:hover {
        transform: translateY(-1px);
        border-color: rgba(79,195,247,0.7);
    }

    .chip.active {
        background: rgba(79,195,247,0.14);
        border-color: rgba(79,195,247,0.9);
    }

    .search-input {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
    }

    .search-input::placeholder {
        color: rgba(255,255,255,0.55);
    }

    :root {
        --evc-dg: #41c7ff;
        --evc-dg-2: #1ea7ff;
        --evc-dgcm: #f59e0b;
        --evc-dgcm-2: #d97706;
        --evc-cm-1: #833AB4;
        --evc-cm-2: #C13584;
        --evc-cm-3: #E1306C;
        --evc-cm-4: #F56040;
    }

    .student-card {
        position: relative;
        border-radius: 22px;
        overflow: hidden;
        height: 100%;
        border: 1px solid rgba(255,255,255,0.12);
        box-shadow:
            0 18px 50px rgba(0,0,0,0.35),
            inset 0 1px 0 rgba(255,255,255,0.06);
        background: linear-gradient(135deg, rgba(15,23,42,0.75) 0%, rgba(30,41,59,0.65) 50%, rgba(15,23,42,0.85) 100%);
        transform: translateZ(0);
    }

    .student-card:hover {
        transform: translateY(-8px);
        border-color: rgba(255,255,255,0.22);
        box-shadow:
            0 26px 80px rgba(0,0,0,0.45),
            inset 0 1px 0 rgba(255,255,255,0.10);
        transition: all .22s ease;
    }

    .student-card::before {
        content: '';
        position: absolute;
        inset: -2px;
        background: var(--theme-bg, linear-gradient(135deg, var(--evc-dg) 0%, var(--evc-dg-2) 100%));
        opacity: 0.22;
        filter: blur(0px);
        z-index: 0;
    }

    .evc-card-title {
        font-weight: 950;
        font-size: 1.85rem;
        letter-spacing: .14em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.96);
        text-shadow: 0 14px 40px rgba(0,0,0,0.45);
        line-height: 1;
    }

    .evc-card-title small {
        display: block;
        font-size: .72rem;
        letter-spacing: .22em;
        margin-top: .55rem;
        color: rgba(255,255,255,0.75);
        font-weight: 800;
    }

    .student-card-watermark {
        position: absolute;
        right: -30px;
        bottom: 10px;
        font-weight: 950;
        font-size: 3.3rem;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: rgba(255,255,255,0.06);
        transform: rotate(-8deg);
        z-index: 1;
        pointer-events: none;
        user-select: none;
        white-space: nowrap;
    }

    .student-card-header {
        position: relative;
        z-index: 2;
        background: rgba(15,23,42,0.55);
        border-bottom: 1px solid rgba(255,255,255,0.10);
        padding: 1.2rem 1.1rem 1rem;
        display: grid;
        grid-template-columns: 92px 1fr;
        gap: 1rem;
        backdrop-filter: blur(10px);
    }

    .evc-title-block {
        grid-column: 1 / -1;
        display: flex;
        align-items: baseline;
        justify-content: space-between;
        gap: .75rem;
        margin-bottom: .5rem;
    }

    .evc-formation-pill {
        padding: .55rem 1rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.22);
        background: var(--theme-bg);
        color: #fff;
        font-weight: 900;
        box-shadow: 0 18px 55px rgba(0,0,0,0.35);
        font-size: .95rem;
        white-space: nowrap;
    }

    /* Thèmes formation */
    .theme-dg { --theme-bg: linear-gradient(135deg, var(--evc-dg) 0%, var(--evc-dg-2) 100%); }
    .theme-dgcm { --theme-bg: linear-gradient(135deg, var(--evc-dgcm) 0%, var(--evc-dgcm-2) 100%); }
    .theme-cm { --theme-bg: linear-gradient(135deg, var(--evc-cm-1) 0%, var(--evc-cm-2) 35%, var(--evc-cm-3) 70%, var(--evc-cm-4) 100%); }

    .theme-dg .student-card-header,
    .theme-dgcm .student-card-header,
    .theme-cm .student-card-header {
        background: rgba(15,23,42,0.45);
    }

    .theme-dg .student-avatar,
    .theme-dgcm .student-avatar,
    .theme-cm .student-avatar {
        box-shadow: 0 16px 45px rgba(0,0,0,0.35);
    }

    .student-avatar {
        width: 92px;
        height: 92px;
        border-radius: 999px;
        object-fit: cover;
        background: rgba(79,195,247,0.18);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 900;
        flex-shrink: 0;
        overflow: hidden;
        border: 4px solid rgba(255,255,255,0.18);
        box-shadow: 0 18px 65px rgba(0,0,0,0.50);
    }

    .student-meta {
        min-width: 0;
        flex: 1;
    }

    .student-name {
        color: #fff;
        font-weight: 800;
        margin: 0;
        font-size: 1.35rem;
        white-space: normal;
        overflow: visible;
    }

    .student-sub {
        color: rgba(255,255,255,0.65);
        font-size: 1.02rem;
        margin: 0;
        white-space: normal;
        overflow: visible;
    }

    .evc-id {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .35rem .7rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.18);
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.92);
        font-weight: 800;
        margin-top: .45rem;
        font-size: .92rem;
    }

    .top-performer-card {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 14px;
        padding: 12px;
    }

    .rank-badge {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        color: #fff;
        background: rgba(79,195,247,0.25);
        border: 1px solid rgba(79,195,247,0.35);
        flex-shrink: 0;
    }

    .student-card-body {
        position: relative;
        z-index: 2;
        padding: 1rem;
        background: rgba(15,23,42,0.35);
        backdrop-filter: blur(10px);
    }

    .meta-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1rem;
    }

    .meta-pill {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.10);
        border-radius: 12px;
        padding: .75rem;
        color: rgba(255,255,255,0.85);
        font-size: .9rem;
    }

    .meta-pill strong {
        display: block;
        font-size: .75rem;
        color: rgba(255,255,255,0.72);
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: .25rem;
    }

    .evc-chip {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .35rem .6rem;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.16);
        background: rgba(255,255,255,0.08);
        color: rgba(255,255,255,0.92);
        font-weight: 800;
        font-size: .78rem;
    }

    .evc-chip.theme {
        background: var(--theme-bg);
        border-color: rgba(255,255,255,0.22);
    }

    .actions-row {
        display: flex;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .export-btn {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.12);
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-white">{{ $title }}</h1>
            <div class="text-white-50">Liste des étudiants ({{ $status === 'active' ? 'actifs' : 'inactifs' }})</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.badges.students.active') }}" class="btn btn-sm {{ $status === 'active' ? 'btn-primary' : 'btn-outline-light' }}">Actifs</a>
            <a href="{{ route('admin.badges.students.inactive') }}" class="btn btn-sm {{ $status === 'inactive' ? 'btn-primary' : 'btn-outline-light' }}">Inactifs</a>
        </div>
    </div>

    @if($status === 'active')
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card stat-card-actifs">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $stats['total'] ?? 0 }}</h3>
                        <p class="stat-label">Étudiants actifs</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card stat-card-dg">
                    <div class="stat-icon"><i class="fas fa-palette"></i></div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $stats['design_graphique'] ?? 0 }}</h3>
                        <p class="stat-label">Design Graphique</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card stat-card-cm">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $stats['community_management'] ?? 0 }}</h3>
                        <p class="stat-label">Community Management</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card stat-card-dgcm">
                    <div class="stat-icon"><i class="fas fa-object-group"></i></div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $stats['design_graphique_cm'] ?? 0 }}</h3>
                        <p class="stat-label">Design + CM</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="stat-card stat-card-actifs">
                    <div class="stat-icon"><i class="fas fa-bolt"></i></div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $stats['new_today'] ?? 0 }}</h3>
                        <p class="stat-label">Nouveaux inscrits (aujourd'hui)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card stat-card-cm">
                    <div class="stat-icon"><i class="fas fa-calendar-week"></i></div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $stats['new_since_saturday'] ?? 0 }}</h3>
                        <p class="stat-label">Nouveaux inscrits (depuis samedi)</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card stat-card-dgcm">
                    <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-content">
                        <h3 class="stat-number">{{ $stats['new_month'] ?? 0 }}</h3>
                        <p class="stat-label">Nouveaux inscrits (ce mois)</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                <h5 class="mb-0 text-white"><i class="fas fa-trophy me-2"></i>Top 5 performers (Projets + TP validés)</h5>
                <small class="text-white-50">Classement basé sur le volume validé sur la période</small>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @php
                        $periodLabels = [
                            'week' => 'Semaine',
                            'month' => 'Mois',
                            'quarter' => 'Trimestre',
                            'year' => 'Année',
                        ];
                    @endphp

                    @foreach($periodLabels as $key => $label)
                        <div class="col-md-6 col-lg-3">
                            <div class="top-performer-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong class="text-white">{{ $label }}</strong>
                                    <span class="badge bg-info">Top 5</span>
                                </div>

                                @php
                                    $list = $topPerformers[$key] ?? collect();
                                @endphp

                                @if($list->isEmpty())
                                    <div class="text-white-50">Aucune donnée</div>
                                @else
                                    <div class="d-flex flex-column gap-2">
                                        @foreach($list as $idx => $p)
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="rank-badge">{{ $idx + 1 }}</span>
                                                <div class="flex-grow-1" style="min-width:0;">
                                                    <div class="text-white fw-semibold" style="line-height:1.15;">
                                                        {{ trim(($p->first_name ?? '').' '.($p->last_name ?? '')) }}
                                                    </div>
                                                    <div class="text-white-50" style="font-size:.85rem;">
                                                        {{ $p->student_id ?? '—' }}
                                                        @if(!empty($p->program))
                                                            • {{ $p->program }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-end" style="min-width:70px;">
                                                    <div class="text-white fw-bold">{{ (int) ($p->total_score ?? 0) }}</div>
                                                    <div class="text-white-50" style="font-size:.75rem;">P:{{ (int) ($p->projects_validated ?? 0) }} TP:{{ (int) ($p->tp_validated ?? 0) }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                <h5 class="mb-0 text-white"><i class="fas fa-filter me-2"></i>Filtres & Tri</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label text-white-50">Rechercher</label>
                        <input id="studentSearch" type="text" class="form-control search-input" placeholder="Nom, ID, pays, formation..." />
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label text-white-50">Trier</label>
                        <div class="d-flex gap-2">
                            @php
                                $sortValue = $sort ?? request('sort', 'date');
                                $dirValue = $dir ?? request('dir', 'desc');
                            @endphp
                            <select id="sortSelect" class="form-select search-input">
                                <option value="date" {{ $sortValue === 'date' ? 'selected' : '' }}>Date d'inscription</option>
                                <option value="projects" {{ $sortValue === 'projects' ? 'selected' : '' }}>Nombre de projets</option>
                            </select>
                            <select id="dirSelect" class="form-select search-input" style="max-width:160px;">
                                <option value="desc" {{ $dirValue === 'desc' ? 'selected' : '' }}>Décroissant</option>
                                <option value="asc" {{ $dirValue === 'asc' ? 'selected' : '' }}>Croissant</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label text-white-50">Filtrer par formation</label>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="chip active" data-filter="all">Tous</span>
                            <span class="chip" data-filter="dg">DG</span>
                            <span class="chip" data-filter="cm">CM</span>
                            <span class="chip" data-filter="dgcm">DG+CM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
            <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
                <h5 class="mb-0 text-white"><i class="fas fa-search me-2"></i>Recherche</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5">
                        <label class="form-label text-white-50">Rechercher</label>
                        <input id="studentSearch" type="text" class="form-control search-input" placeholder="Nom, ID, pays, formation..." />
                    </div>
                    <div class="col-lg-7">
                        <label class="form-label text-white-50">Filtrer par formation</label>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="chip active" data-filter="all">Tous</span>
                            <span class="chip" data-filter="dg">Design Graphique</span>
                            <span class="chip" data-filter="cm">Community Management</span>
                            <span class="chip" data-filter="dgcm">Design + CM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-id-badge me-2"></i>Liste des Étudiants</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
        @forelse($students as $student)
            @php
                $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);
                $initials = substr($student->first_name ?? 'U', 0, 1) . substr($student->last_name ?? 'U', 0, 1);
                $progRaw = (string) ($student->program ?? '');
                $prog = strtolower($progRaw);
                $hasDesign = strpos($prog, 'design') !== false;
                $hasCommunity = (strpos($prog, 'community') !== false) || (strpos($prog, 'manager') !== false) || (strpos($prog, 'management') !== false);
                $theme = $hasDesign && $hasCommunity ? 'theme-dgcm' : ($hasCommunity ? 'theme-cm' : ($hasDesign ? 'theme-dg' : ''));
                $filterKey = $hasDesign && $hasCommunity ? 'dgcm' : ($hasCommunity ? 'cm' : ($hasDesign ? 'dg' : 'other'));
            @endphp
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="student-card {{ $theme }}" data-student-card data-filter="{{ $filterKey }}" data-search="{{ strtolower(($student->first_name ?? '') . ' ' . ($student->last_name ?? '') . ' ' . ($student->student_id ?? '') . ' ' . ($student->country ?? '') . ' ' . $progRaw) }}">
                    <div class="student-card-watermark">EVC</div>
                    <div class="student-card-header">
                        <div class="evc-title-block">
                            <div class="evc-card-title">
                                ÉTUDIANT(E) EVC
                                <small>CARTE DIGITALE OFFICIELLE</small>
                            </div>
                            <div class="evc-formation-pill">{{ $student->program ?? 'Formation' }}</div>
                        </div>

                        @if(!empty($student->profile_photo) && !empty($photoUrl))
                            <img src="{{ $photoUrl }}" alt="{{ $student->first_name ?? 'Étudiant' }}" class="student-avatar" />
                        @else
                            <div class="student-avatar">{{ $initials }}</div>
                        @endif

                        <div class="student-meta">
                            <p class="student-name">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</p>
                            <div class="evc-id">
                                <i class="fas fa-id-card"></i>
                                ID Étudiant: {{ $student->student_id ?? '—' }}
                            </div>
                        </div>

                        @if($status === 'inactive')
                            <div class="ms-auto">
                                <span class="badge bg-danger">Expiré</span>
                            </div>
                        @endif
                    </div>

                    <div class="student-card-body">
                        <div class="meta-row">
                            <div class="meta-pill">
                                <strong>Pays</strong>
                                <div>{{ $student->country ?? 'N/A' }}</div>
                            </div>
                            <div class="meta-pill">
                                <strong>Formation</strong>
                                <div>{{ $student->program ?? 'N/A' }}</div>
                            </div>
                        </div>

                        @if($status === 'active')
                            <div class="meta-row">
                                <div class="meta-pill">
                                    <strong>Inscription</strong>
                                    <div>
                                        @if(!empty($student->registration_sort_date))
                                            {{ \Carbon\Carbon::parse($student->registration_sort_date)->format('d/m/Y') }}
                                        @elseif(!empty($student->created_at))
                                            {{ \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                                <div class="meta-pill">
                                    <strong>Projets réalisés</strong>
                                    <div>{{ (int) ($student->projects_count ?? 0) }}</div>
                                </div>
                            </div>
                        @endif

                        @if($status === 'inactive')
                            @php
                                $expiresAt = $student->expiration_date ?? ($student->computed_expiration_date ?? null);
                            @endphp
                            <div class="meta-pill mb-3">
                                <strong>Date d'expiration</strong>
                                <div>
                                    @if(!empty($expiresAt))
                                        {{ \Carbon\Carbon::parse($expiresAt)->format('d/m/Y') }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="actions-row">
                            <a href="{{ route('admin.students.profile', $student->id) }}" class="btn btn-sm btn-info">Voir</a>
                            @if($status === 'active')
                                @php
                                    $exportPhoto = (!empty($student->profile_photo) && !empty($photoUrl)) ? $photoUrl : null;
                                    $exportPayload = [
                                        'id' => (int) ($student->id ?? 0),
                                        'full_name' => trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? '')),
                                        'student_id' => (string) ($student->student_id ?? ''),
                                        'country' => (string) ($student->country ?? ''),
                                        'program' => (string) ($student->program ?? ''),
                                        'photo' => $exportPhoto,
                                        'projects_count' => (int) ($student->projects_count ?? 0),
                                        'registered_at' => (string) ($student->registration_sort_date ?? $student->created_at ?? ''),
                                        'theme' => $filterKey,
                                    ];
                                @endphp
                                <button type="button" class="btn btn-sm export-btn" data-export-card data-export='@json($exportPayload)'>Télécharger la carte</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
                    <div class="card-body text-center text-white-50 py-5">
                        Aucun étudiant à afficher.
                    </div>
                </div>
            </div>
        @endforelse
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $students->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
    (function () {
        const searchInput = document.getElementById('studentSearch');
        const chips = document.querySelectorAll('[data-filter]');
        const cards = document.querySelectorAll('[data-student-card]');

        if (!cards || cards.length === 0) return;

        let activeFilter = 'all';

        const applyFilters = () => {
            const q = (searchInput && searchInput.value ? searchInput.value : '').toLowerCase().trim();

            cards.forEach((card) => {
                const key = card.getAttribute('data-filter') || 'other';
                const hay = card.getAttribute('data-search') || '';
                const matchFilter = (activeFilter === 'all') || (key === activeFilter);
                const matchText = !q || hay.indexOf(q) !== -1;
                card.parentElement.style.display = (matchFilter && matchText) ? '' : 'none';
            });
        };

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                chips.forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                activeFilter = chip.getAttribute('data-filter') || 'all';
                applyFilters();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', applyFilters);
        }

        const sortSelect = document.getElementById('sortSelect');
        const dirSelect = document.getElementById('dirSelect');
        const applySort = () => {
            if (!sortSelect || !dirSelect) return;
            const url = new URL(window.location.href);
            url.searchParams.set('sort', sortSelect.value);
            url.searchParams.set('dir', dirSelect.value);
            window.location.href = url.toString();
        };
        if (sortSelect && dirSelect) {
            sortSelect.addEventListener('change', applySort);
            dirSelect.addEventListener('change', applySort);
        }

        applyFilters();
    })();

    (function () {
        const buttons = document.querySelectorAll('[data-export-card]');
        if (!buttons || buttons.length === 0) return;

        const renderCard1080x1350 = (data) => {
            const wrap = document.createElement('div');
            wrap.style.width = '1080px';
            wrap.style.height = '1350px';
            wrap.style.position = 'fixed';
            wrap.style.left = '-99999px';
            wrap.style.top = '0';
            wrap.style.background = 'linear-gradient(135deg, #0b1220 0%, #111c34 50%, #0b1220 100%)';
            wrap.style.borderRadius = '38px';
            wrap.style.overflow = 'hidden';
            wrap.style.border = '2px solid rgba(255,255,255,0.12)';

            const theme = data.theme || 'dg';
            const themeBg = theme === 'cm'
                ? 'linear-gradient(135deg, rgba(131,58,180,0.75) 0%, rgba(193,53,132,0.75) 40%, rgba(225,48,108,0.55) 70%, rgba(245,96,64,0.45) 100%)'
                : (theme === 'dgcm'
                    ? 'linear-gradient(135deg, rgba(79,195,247,0.70) 0%, rgba(30,60,114,0.75) 60%, rgba(245,158,11,0.55) 100%)'
                    : 'linear-gradient(135deg, rgba(79,195,247,0.75) 0%, rgba(30,60,114,0.80) 80%)');

            wrap.innerHTML = `
                <div style="padding:64px 64px 50px; height:100%; display:flex; flex-direction:column;">
                    <div style="display:flex; align-items:baseline; justify-content:space-between; gap:18px;">
                        <div style="color:#fff; font-weight:950; letter-spacing:.18em; text-transform:uppercase; font-size:56px; line-height:1;">ÉTUDIANT(E) EVC</div>
                        <div style="background:${themeBg}; padding:14px 22px; border-radius:999px; border:1px solid rgba(255,255,255,0.22); color:#fff; font-weight:900; font-size:24px; white-space:nowrap;">${data.program || ''}</div>
                    </div>
                    <div style="margin-top:16px; height:2px; background:rgba(255,255,255,0.14);"></div>

                    <div style="margin-top:34px; display:flex; gap:34px; align-items:center;">
                        <div style="width:220px; height:220px; border-radius:999px; overflow:hidden; border:6px solid rgba(255,255,255,0.22); background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:950; font-size:64px; box-shadow:0 26px 80px rgba(0,0,0,0.45);">
                            ${data.photo ? `<img src="${data.photo}" style="width:100%; height:100%; object-fit:cover;" />` : `${(data.full_name || 'ET').split(' ').map(p => p[0]).slice(0,2).join('').toUpperCase()}`}
                        </div>
                        <div style="min-width:0;">
                            <div style="color:#fff; font-weight:950; font-size:60px; line-height:1.05;">${data.full_name || ''}</div>
                            <div style="margin-top:16px; display:inline-flex; align-items:center; gap:10px; padding:12px 18px; border-radius:999px; border:1px solid rgba(255,255,255,0.18); background:rgba(255,255,255,0.08); color:rgba(255,255,255,0.92); font-weight:900; font-size:28px;">
                                <span style="opacity:.9;">ID Étudiant</span>
                                <span style="opacity:.65;">•</span>
                                <span>${data.student_id || ''}</span>
                            </div>
                        </div>
                    </div>

                    <div style="height:34px;"></div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:26px; padding:28px;">
                            <div style="color:rgba(255,255,255,0.7); font-size:22px; text-transform:uppercase; letter-spacing:.08em; font-weight:800;">Pays</div>
                            <div style="color:#fff; font-size:32px; font-weight:900; margin-top:10px;">${data.country || ''}</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:26px; padding:28px;">
                            <div style="color:rgba(255,255,255,0.7); font-size:22px; text-transform:uppercase; letter-spacing:.08em; font-weight:800;">Formation</div>
                            <div style="color:#fff; font-size:30px; font-weight:900; margin-top:10px; line-height:1.1;">${data.program || ''}</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:26px; padding:28px;">
                            <div style="color:rgba(255,255,255,0.7); font-size:22px; text-transform:uppercase; letter-spacing:.08em; font-weight:800;">Projets réalisés</div>
                            <div style="color:#fff; font-size:54px; font-weight:900; margin-top:6px;">${data.projects_count ?? 0}</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.10); border-radius:26px; padding:28px;">
                            <div style="color:rgba(255,255,255,0.7); font-size:22px; text-transform:uppercase; letter-spacing:.08em; font-weight:800;">Inscription</div>
                            <div style="color:#fff; font-size:32px; font-weight:900; margin-top:10px;">${data.registered_at ? (new Date(data.registered_at).toLocaleDateString('fr-FR')) : ''}</div>
                        </div>
                    </div>

                    <div style="margin-top:auto; display:flex; justify-content:space-between; align-items:center; padding-top:40px;">
                        <div style="color:rgba(255,255,255,0.7); font-size:22px; font-weight:800;">Ecole Virtuelle Des Creatifs</div>
                        <div style="color:rgba(255,255,255,0.6); font-size:20px;">Badge étudiant</div>
                    </div>
                </div>
            `;

            document.body.appendChild(wrap);
            return wrap;
        };

        const downloadBlobUrl = (blobUrl, filename) => {
            const a = document.createElement('a');
            a.href = blobUrl;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            a.remove();
        };

        buttons.forEach((btn) => {
            btn.addEventListener('click', async () => {
                if (!window.html2canvas) {
                    alert('html2canvas non chargé');
                    return;
                }

                let data = {};
                try {
                    data = JSON.parse(btn.getAttribute('data-export') || '{}');
                } catch (e) {
                    data = {};
                }

                const card = renderCard1080x1350(data);
                try {
                    const canvas = await window.html2canvas(card, {
                        backgroundColor: null,
                        scale: 1,
                        width: 1080,
                        height: 1350,
                        useCORS: true,
                    });

                    canvas.toBlob((blob) => {
                        if (!blob) return;
                        const url = URL.createObjectURL(blob);
                        const safeName = (data.full_name || 'etudiant').toLowerCase().replace(/[^a-z0-9]+/g, '_');
                        downloadBlobUrl(url, `badge_${safeName}_1080x1350.png`);
                        setTimeout(() => URL.revokeObjectURL(url), 8000);
                    }, 'image/png');
                } catch (err) {
                    console.error(err);
                    alert('Impossible de générer l\'image.');
                } finally {
                    card.remove();
                }
            });
        });
    })();
</script>
@endpush
