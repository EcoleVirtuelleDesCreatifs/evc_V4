@extends('layouts.admin')

@section('title', 'Gestion des Programmes')

@push('styles')
<style>
    .pgm-header {
        background: linear-gradient(135deg, rgba(139,92,246,0.15) 0%, rgba(59,130,246,0.10) 100%);
        border: 1px solid rgba(139,92,246,0.3);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .pgm-header-icon {
        width: 56px; height: 56px; border-radius: 14px;
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 1.4rem; flex-shrink: 0;
    }

    /* KPI cards */
    .pgm-kpi {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px; padding: 1rem 1.1rem;
        display: flex; align-items: center; gap: 0.85rem;
    }
    .pgm-kpi-icon {
        width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem;
    }
    .pgm-kpi-num { font-size: 1.4rem; font-weight: 800; color: #fff; line-height: 1; }
    .pgm-kpi-lbl { font-size: 0.72rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Toolbar */
    .pgm-toolbar {
        display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px; padding: 0.75rem 1rem; margin-bottom: 1.25rem;
    }
    .pgm-search {
        flex: 1 1 240px; display: flex; align-items: center; gap: 0.5rem;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px; padding: 0.45rem 0.8rem;
    }
    .pgm-search i { color: #64748b; }
    .pgm-search input { flex: 1; background: transparent; border: none; outline: none; color: #fff; font-size: 0.88rem; }
    .pgm-search input::placeholder { color: #64748b; }
    .pgm-select {
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);
        color: #e2e8f0; border-radius: 10px; padding: 0.45rem 0.8rem; font-size: 0.85rem;
    }
    .pgm-select option { background: #1e293b; }
    .pgm-pill {
        padding: 0.35rem 0.8rem; border-radius: 999px; font-size: 0.75rem; font-weight: 800;
        background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.7); cursor: pointer; transition: all 0.15s ease; user-select: none;
    }
    .pgm-pill:hover { border-color: rgba(139,92,246,0.5); }
    .pgm-pill.active { background: #8b5cf6; border-color: #8b5cf6; color: #fff; }

    /* Table */
    .pgm-table-wrap {
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px; overflow: hidden;
    }
    .pgm-table { width: 100%; border-collapse: collapse; color: #e2e8f0; }
    .pgm-table thead th {
        background: rgba(15,23,42,0.6); color: #94a3b8;
        font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.6px;
        padding: 0.8rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.08);
        white-space: nowrap;
    }
    .pgm-table tbody.pgm-group > tr.pgm-main td { padding: 0.85rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.06); vertical-align: middle; }
    .pgm-table tbody.pgm-group > tr.pgm-detail td { padding: 0; border-bottom: 1px solid rgba(255,255,255,0.08); }
    .pgm-group.hidden-by-filter { display: none; }
    .pgm-main:hover td { background: rgba(139,92,246,0.04); }

    .pgm-thumb {
        width: 64px; height: 44px; border-radius: 8px; object-fit: cover;
        border: 1px solid rgba(255,255,255,0.1); display: block;
    }
    .pgm-thumb-ph {
        width: 64px; height: 44px; border-radius: 8px;
        background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2);
        display: flex; align-items: center; justify-content: center;
        color: #a78bfa; font-size: 0.9rem;
    }
    .pgm-title { font-weight: 700; color: #fff; font-size: 0.92rem; }
    .pgm-desc { font-size: 0.78rem; color: #94a3b8; margin-top: 0.15rem; }

    .pgm-fbadge {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.3rem 0.7rem; border-radius: 999px;
        font-size: 0.72rem; font-weight: 800; color: #fff; white-space: nowrap;
    }
    .pgm-fbadge.f-dg { background: rgba(59,130,246,0.25); border: 1px solid rgba(59,130,246,0.4); color: #93c5fd; }
    .pgm-fbadge.f-cm { background: rgba(225,48,108,0.2); border: 1px solid rgba(225,48,108,0.4); color: #f9a8d4; }
    .pgm-fbadge.f-dgcm { background: rgba(245,158,11,0.2); border: 1px solid rgba(245,158,11,0.4); color: #fcd34d; }
    .pgm-fbadge.f-gi { background: rgba(249,115,22,0.2); border: 1px solid rgba(249,115,22,0.4); color: #fdba74; }
    .pgm-fbadge.f-ia { background: rgba(6,182,212,0.2); border: 1px solid rgba(6,182,212,0.4); color: #67e8f9; }
    .pgm-fbadge.f-all { background: rgba(156,39,176,0.25); border: 1px solid rgba(156,39,176,0.4); color: #d8b4fe; }
    .pgm-fbadge.f-ciblage { background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.4); color: #6ee7b7; }
    .pgm-fbadge.f-default { background: rgba(148,163,184,0.15); border: 1px solid rgba(148,163,184,0.3); color: #cbd5e1; }

    .pgm-status {
        display: inline-flex; align-items: center; gap: 0.3rem;
        font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 999px;
    }
    .pgm-status.published { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.3); }
    .pgm-status.draft { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.3); }

    .pgm-act {
        width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.05); color: #cbd5e1; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.78rem; transition: all 0.15s ease; text-decoration: none;
    }
    .pgm-act:hover { transform: translateY(-1px); color: #fff; }
    .pgm-act.act-view:hover { border-color: #38bdf8; background: rgba(56,189,248,0.12); }
    .pgm-act.act-pub { color: #fbbf24; }
    .pgm-act.act-pub.is-pub { color: #34d399; }
    .pgm-act.act-edit:hover { border-color: #f59e0b; background: rgba(245,158,11,0.12); }
    .pgm-act.act-del { color: #f87171; }
    .pgm-act.act-del:hover { border-color: #ef4444; background: rgba(239,68,68,0.12); }

    .pgm-detail-body { background: rgba(15,23,42,0.35); padding: 1.1rem 1.25rem; }
    .pgm-detail-grid { display: grid; grid-template-columns: 280px 1fr; gap: 1rem; }
    @media (max-width: 992px) { .pgm-detail-grid { grid-template-columns: 1fr; } }
    .pgm-detail-box {
        background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px; padding: 0.9rem;
    }
    .pgm-detail-title { font-size: 0.72rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.6px; color: #94a3b8; margin-bottom: 0.6rem; }
    .pgm-target-row { display: flex; align-items: center; gap: 0.5rem; color: #cbd5e1; font-size: 0.82rem; padding: 0.25rem 0; }
    .pgm-target-row i { color: #8b5cf6; width: 14px; }

    .pgm-items { width: 100%; border-collapse: collapse; font-size: 0.82rem; }
    .pgm-items th {
        color: #94a3b8; font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.5px; padding: 0.4rem 0.6rem; border-bottom: 1px solid rgba(255,255,255,0.08);
    }
    .pgm-items td { padding: 0.5rem 0.6rem; border-bottom: 1px solid rgba(255,255,255,0.05); color: #e2e8f0; vertical-align: middle; }
    .pgm-items tr.is-past td { opacity: 0.55; }
    .pgm-type { font-size: 0.68rem; font-weight: 800; padding: 0.15rem 0.5rem; border-radius: 999px; }
    .pgm-type.online { background: rgba(37,99,235,0.2); color: #93c5fd; }
    .pgm-type.presentielle { background: rgba(249,115,22,0.2); color: #fdba74; }

    .pgm-empty { text-align: center; padding: 3.5rem 1rem; }
    .pgm-empty i { font-size: 3rem; color: #475569; display: block; margin-bottom: 1rem; }
    .pgm-noresults { display: none; text-align: center; padding: 2.5rem; color: #94a3b8; }
</style>
@endpush

@section('content')
@php
    // URL same-origin pour les fichiers (cohérent avec la page étudiante)
    $mediaUrl = function ($path) {
        $path = ltrim((string) ($path ?? ''), '/');
        if ($path === '') return null;
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
        foreach (['storage/app/public/', 'public/storage/', 'storage/'] as $pre) {
            if (str_starts_with($path, $pre)) { $path = substr($path, strlen($pre)); break; }
        }
        $parts = explode('/', ltrim($path, '/'));
        $file = rawurlencode((string) array_pop($parts));
        return url('storage/app/public/' . implode('/', $parts) . ($parts ? '/' : '') . $file);
    };

    $formationBadgeClass = function ($f) {
        $l = strtolower((string) $f);
        if ($l === 'ciblage') return 'f-ciblage';
        if (str_contains($l, 'design') && (str_contains($l, 'community') || str_contains($l, 'cm'))) return 'f-dgcm';
        if (str_contains($l, 'design') || str_contains($l, 'infographie')) return 'f-dg';
        if (str_contains($l, 'community')) return 'f-cm';
        if (str_contains($l, 'informatique')) return 'f-gi';
        if (str_contains($l, 'intelligence')) return 'f-ia';
        if (str_contains($l, 'toutes')) return 'f-all';
        return 'f-default';
    };

    $months = $programmes->map(fn($p) => $p->month_start ?? null)->filter()->unique()->sortDesc()->values();
    $publishedCount = $programmes->where('status', 'published')->count();
@endphp

<div class="container-fluid py-4" style="max-width: 1500px;">

    {{-- ═══ Header ═══ --}}
    <div class="pgm-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="pgm-header-icon"><i class="fas fa-book"></i></div>
                <div>
                    <h2 class="text-white mb-1" style="font-size:1.4rem; font-weight:700;">Gestion des Programmes</h2>
                    <p class="text-muted mb-0" style="font-size:0.85rem;">{{ $programmes->count() }} programme(s) • {{ $publishedCount }} publié(s)</p>
                </div>
            </div>
            <a href="{{ route('admin.programmes.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>Ajouter un Programme
            </a>
        </div>
    </div>

    {{-- Messages --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ═══ KPIs ═══ --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="pgm-kpi">
                <div class="pgm-kpi-icon" style="background:rgba(139,92,246,0.15); color:#a78bfa;"><i class="fas fa-book"></i></div>
                <div><div class="pgm-kpi-num">{{ $stats['total'] ?? 0 }}</div><div class="pgm-kpi-lbl">Total</div></div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="pgm-kpi">
                <div class="pgm-kpi-icon" style="background:rgba(59,130,246,0.15); color:#60a5fa;"><i class="fas fa-palette"></i></div>
                <div><div class="pgm-kpi-num">{{ $stats['design_graphique'] ?? 0 }}</div><div class="pgm-kpi-lbl">Design</div></div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="pgm-kpi">
                <div class="pgm-kpi-icon" style="background:rgba(225,48,108,0.15); color:#f472b6;"><i class="fas fa-mobile-alt"></i></div>
                <div><div class="pgm-kpi-num">{{ $stats['community_management'] ?? 0 }}</div><div class="pgm-kpi-lbl">Community</div></div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="pgm-kpi">
                <div class="pgm-kpi-icon" style="background:rgba(249,115,22,0.15); color:#fb923c;"><i class="fas fa-laptop-code"></i></div>
                <div><div class="pgm-kpi-num">{{ $stats['gestion_informatique'] ?? 0 }}</div><div class="pgm-kpi-lbl">Informatique</div></div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="pgm-kpi">
                <div class="pgm-kpi-icon" style="background:rgba(6,182,212,0.15); color:#22d3ee;"><i class="fas fa-robot"></i></div>
                <div><div class="pgm-kpi-num">{{ $stats['intelligence_artificielle'] ?? 0 }}</div><div class="pgm-kpi-lbl">IA</div></div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="pgm-kpi">
                <div class="pgm-kpi-icon" style="background:rgba(16,185,129,0.15); color:#34d399;"><i class="fas fa-calendar-check"></i></div>
                <div><div class="pgm-kpi-num">{{ $stats['ce_mois'] ?? 0 }}</div><div class="pgm-kpi-lbl">Ce mois</div></div>
            </div>
        </div>
    </div>

    @if ($programmes->isEmpty())
        <div class="pgm-empty pgm-table-wrap">
            <i class="fas fa-inbox"></i>
            <h3 class="text-white fw-bold">Aucun programme disponible</h3>
            <p class="text-muted">Commencez par ajouter un programme de formation</p>
            <a href="{{ route('admin.programmes.create') }}" class="btn btn-success mt-3">
                <i class="fas fa-plus me-1"></i>Ajouter un Programme
            </a>
        </div>
    @else

        {{-- ═══ Toolbar filtres ═══ --}}
        <div class="pgm-toolbar">
            <div class="pgm-search">
                <i class="fas fa-search"></i>
                <input type="text" id="pgmSearch" placeholder="Rechercher un programme, une séance, un lieu...">
            </div>
            <select id="pgmFormation" class="pgm-select">
                <option value="">Toutes formations</option>
                <option value="Design Graphique">Design Graphique</option>
                <option value="Community Management">Community Management</option>
                <option value="Design Graphique & Community Manager">Design Graphique &amp; CM</option>
                <option value="Gestion Informatique">Gestion Informatique</option>
                <option value="Intelligence Artificielle">Intelligence Artificielle</option>
                <option value="Toutes">Toutes (commun)</option>
                <option value="Ciblage">Ciblage étudiants</option>
            </select>
            <select id="pgmMonth" class="pgm-select">
                <option value="">Tous les mois</option>
                @foreach ($months as $m)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::parse($m)->translatedFormat('F Y') }}</option>
                @endforeach
            </select>
            <div class="d-flex gap-1">
                <span class="pgm-pill active" data-status="">Tous</span>
                <span class="pgm-pill" data-status="published">Publiés</span>
                <span class="pgm-pill" data-status="draft">Brouillons</span>
            </div>
        </div>

        {{-- ═══ Table ═══ --}}
        <div class="pgm-table-wrap">
            <div class="table-responsive">
                <table class="pgm-table">
                    <thead>
                        <tr>
                            <th>Programme</th>
                            <th>Mois</th>
                            <th>Formation</th>
                            <th>Statut</th>
                            <th class="text-center">Séances</th>
                            <th>PDF</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    @foreach ($programmes as $programme)
                        @php
                            $formation = $programme->formation ?? '';
                            $items = $programme->items ?? collect();
                            $imageUrl = $mediaUrl($programme->image ?? null);
                            $pdfUrl = $mediaUrl($programme->fichier_pdf ?? null);
                            $status = $programme->status ?? 'draft';
                            $monthLabel = null;
                            try { $monthLabel = !empty($programme->month_start) ? \Carbon\Carbon::parse($programme->month_start)->translatedFormat('F Y') : null; } catch (\Throwable $e) {}

                            $studentIds = [];
                            if (!empty($programme->student_ids) && is_string($programme->student_ids)) {
                                try { $studentIds = json_decode($programme->student_ids, true) ?? []; } catch (\Throwable $e) {}
                            }
                            $isStudentTargeting = !empty($studentIds);

                            $searchText = strtolower(($programme->titre ?? '') . ' ' . ($programme->description ?? '') . ' ' . $formation . ' ' . ($monthLabel ?? '') . ' ' . $items->pluck('thematique')->implode(' ') . ' ' . $items->pluck('lieu')->implode(' '));
                        @endphp
                        <tbody class="pgm-group"
                               data-search="{{ $searchText }}"
                               data-formation="{{ $formation }}"
                               data-month="{{ $programme->month_start ?? '' }}"
                               data-status="{{ $status }}">
                            <tr class="pgm-main">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" class="pgm-thumb" alt="Cover" loading="lazy">
                                        @else
                                            <div class="pgm-thumb-ph"><i class="fas fa-book"></i></div>
                                        @endif
                                        <div>
                                            <div class="pgm-title">{{ $programme->titre }}</div>
                                            @if(!empty($programme->description))
                                                <div class="pgm-desc">{{ \Illuminate\Support\Str::limit($programme->description, 70) }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td><span style="color:#cbd5e1; font-size:0.85rem;">{{ $monthLabel ?? '—' }}</span></td>
                                <td>
                                    <span class="pgm-fbadge {{ $formationBadgeClass($formation) }}">
                                        {{ $isStudentTargeting ? 'Ciblage (' . count($studentIds) . ')' : ($formation ?: '—') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="pgm-status {{ $status === 'published' ? 'published' : 'draft' }}" id="pgmStatus{{ $programme->id }}">
                                        <i class="fas fa-{{ $status === 'published' ? 'check-circle' : 'pen' }}"></i>{{ $status === 'published' ? 'Publié' : 'Brouillon' }}
                                    </span>
                                </td>
                                <td class="text-center"><span class="badge" style="background:rgba(255,255,255,0.08); color:#cbd5e1;">{{ $items->count() }}</span></td>
                                <td>
                                    @if($pdfUrl)
                                        <a href="{{ $pdfUrl }}" target="_blank" class="pgm-act act-view" title="Ouvrir le PDF"><i class="fas fa-file-pdf"></i></a>
                                    @else
                                        <span style="color:#475569;">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 justify-content-end">
                                        <button type="button" class="pgm-act act-view" title="Détails"
                                                data-bs-toggle="collapse" data-bs-target="#pgmDetail{{ $programme->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="pgm-act act-pub {{ $status === 'published' ? 'is-pub' : '' }}"
                                                id="pgmPubBtn{{ $programme->id }}"
                                                title="{{ $status === 'published' ? 'Dépublier' : 'Publier' }}"
                                                onclick="toggleProgrammeStatus({{ $programme->id }})">
                                            <i class="fas fa-{{ $status === 'published' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                        <a href="{{ route('admin.programmes.edit', $programme->id) }}" class="pgm-act act-edit" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.programmes.destroy', $programme->id) }}" method="POST"
                                              onsubmit="return confirm('Supprimer définitivement ce programme ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="pgm-act act-del" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr class="pgm-detail">
                                <td colspan="7">
                                    <div id="pgmDetail{{ $programme->id }}" class="collapse">
                                        <div class="pgm-detail-body">
                                            <div class="pgm-detail-grid">
                                                {{-- Ciblage --}}
                                                <div class="pgm-detail-box">
                                                    <div class="pgm-detail-title"><i class="fas fa-bullseye me-1" style="color:#8b5cf6;"></i>Ciblage</div>
                                                    @if($isStudentTargeting)
                                                        @php
                                                            $targetedStudents = DB::table('students')
                                                                ->whereIn('students.id', $studentIds)
                                                                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                                                                ->select('students.first_name', 'students.last_name', 'users.email')
                                                                ->get();
                                                        @endphp
                                                        @foreach($targetedStudents as $ts)
                                                            <div class="pgm-target-row">
                                                                <i class="fas fa-user"></i>
                                                                <span>{{ $ts->first_name }} {{ $ts->last_name }}
                                                                    @if(!empty($ts->email))<span class="text-muted">({{ $ts->email }})</span>@endif
                                                                </span>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <div class="pgm-target-row">
                                                            <i class="fas fa-graduation-cap"></i>
                                                            <span><strong>{{ $formation ?: 'Non défini' }}</strong></span>
                                                        </div>
                                                    @endif
                                                    @if(!empty($programme->description))
                                                        <div class="pgm-detail-title mt-3"><i class="fas fa-align-left me-1"></i>Description</div>
                                                        <div style="font-size:0.82rem; color:#94a3b8;">{{ $programme->description }}</div>
                                                    @endif
                                                </div>

                                                {{-- Séances --}}
                                                <div class="pgm-detail-box">
                                                    <div class="pgm-detail-title"><i class="fas fa-calendar-alt me-1" style="color:#38bdf8;"></i>Séances ({{ $items->count() }})</div>
                                                    @if($items->isEmpty())
                                                        <div class="text-center py-3">
                                                            <i class="fas fa-inbox text-muted d-block mb-2" style="font-size:1.4rem;"></i>
                                                            <span class="text-muted" style="font-size:0.82rem;">Aucune séance pour ce programme.</span>
                                                        </div>
                                                    @else
                                                        <div class="table-responsive">
                                                            <table class="pgm-items">
                                                                <thead>
                                                                    <tr><th>Thématique</th><th>Date</th><th>Heure</th><th>Type</th><th>Lieu</th><th>Pièce</th></tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach($items as $it)
                                                                        @php
                                                                            $isPast = false;
                                                                            try {
                                                                                if (!empty($it->session_date)) {
                                                                                    $isPast = \Carbon\Carbon::parse($it->session_date . ' ' . ($it->session_time ?? '00:00'))->isPast();
                                                                                }
                                                                            } catch (\Throwable $e) {}
                                                                        @endphp
                                                                        <tr class="{{ $isPast ? 'is-past' : '' }}">
                                                                            <td>
                                                                                <div style="font-weight:700;">{{ $it->thematique ?? '—' }}</div>
                                                                                @if(!empty($it->description))<div style="font-size:0.72rem; color:#64748b;">{{ Str::limit($it->description, 50) }}</div>@endif
                                                                            </td>
                                                                            <td>{{ !empty($it->session_date) ? \Carbon\Carbon::parse($it->session_date)->format('d/m/Y') : '—' }}</td>
                                                                            <td>{{ !empty($it->session_time) ? \Carbon\Carbon::parse($it->session_time)->format('H:i') : '—' }}</td>
                                                                            <td><span class="pgm-type {{ ($it->type_formation ?? '') === 'presentielle' ? 'presentielle' : 'online' }}">{{ ($it->type_formation ?? '') === 'presentielle' ? 'Présentielle' : 'En ligne' }}</span></td>
                                                                            <td>{{ $it->lieu ?? '—' }}</td>
                                                                            <td>
                                                                                @if(!empty($it->piece_jointe))
                                                                                    <a href="{{ $mediaUrl($it->piece_jointe) }}" target="_blank" class="pgm-act act-view" style="width:26px; height:26px; font-size:0.7rem;"><i class="fas fa-paperclip"></i></a>
                                                                                @else
                                                                                    <span style="color:#475569;">—</span>
                                                                                @endif
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    @endforeach
                </table>
            </div>
            <div class="pgm-noresults" id="pgmNoResults">
                <i class="fas fa-search d-block mb-2" style="font-size:1.8rem; opacity:0.4;"></i>
                Aucun programme ne correspond aux filtres.
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('pgmSearch');
    const formationSel = document.getElementById('pgmFormation');
    const monthSel = document.getElementById('pgmMonth');
    const groups = document.querySelectorAll('tbody.pgm-group');
    const noResults = document.getElementById('pgmNoResults');
    let activeStatus = '';

    const norm = v => (v || '').toString().toLowerCase().trim();

    function applyFilters() {
        const q = norm(searchInput ? searchInput.value : '');
        const formation = norm(formationSel ? formationSel.value : '');
        const month = norm(monthSel ? monthSel.value : '');
        let visible = 0;

        groups.forEach(g => {
            const matchSearch = !q || (g.dataset.search || '').includes(q);
            const matchFormation = !formation || norm(g.dataset.formation) === formation;
            const matchMonth = !month || norm(g.dataset.month) === month;
            const matchStatus = !activeStatus || g.dataset.status === activeStatus;
            const show = matchSearch && matchFormation && matchMonth && matchStatus;
            g.classList.toggle('hidden-by-filter', !show);
            if (show) visible++;
        });

        if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (formationSel) formationSel.addEventListener('change', applyFilters);
    if (monthSel) monthSel.addEventListener('change', applyFilters);

    document.querySelectorAll('.pgm-pill[data-status]').forEach(pill => {
        pill.addEventListener('click', function () {
            document.querySelectorAll('.pgm-pill[data-status]').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            activeStatus = this.dataset.status;
            applyFilters();
        });
    });
});

// Publier / dépublier (AJAX)
function toggleProgrammeStatus(programmeId) {
    const btn = document.getElementById('pgmPubBtn' + programmeId);
    const badge = document.getElementById('pgmStatus' + programmeId);
    if (!btn) return;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

    fetch(`{{ route('admin.programmes.toggleStatus', ':id') }}`.replace(':id', programmeId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const isPub = data.status === 'published';
            btn.classList.toggle('is-pub', isPub);
            btn.title = isPub ? 'Dépublier' : 'Publier';
            btn.innerHTML = `<i class="fas ${isPub ? 'fa-eye-slash' : 'fa-eye'}"></i>`;
            if (badge) {
                badge.className = `pgm-status ${isPub ? 'published' : 'draft'}`;
                badge.innerHTML = `<i class="fas fa-${isPub ? 'check-circle' : 'pen'}"></i>${isPub ? 'Publié' : 'Brouillon'}`;
            }
            const group = btn.closest('tbody.pgm-group');
            if (group) group.dataset.status = data.status;
        } else {
            alert(data.message || 'Erreur lors du changement de statut');
            btn.innerHTML = '<i class="fas fa-eye"></i>';
        }
    })
    .catch(err => {
        console.error(err);
        alert('Erreur lors du changement de statut');
        btn.innerHTML = '<i class="fas fa-eye"></i>';
    })
    .finally(() => { btn.disabled = false; });
}
</script>
@endpush
