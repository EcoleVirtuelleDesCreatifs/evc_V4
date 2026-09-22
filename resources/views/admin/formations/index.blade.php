@extends('layouts.admin')

@section('title', 'Liste des Formations')

@push('styles')
<style>
    .fm-kpi {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px; padding: 0.9rem 1.1rem;
        display: flex; align-items: center; gap: 0.85rem; height: 100%;
        transition: border-color 0.2s, transform 0.2s;
    }
    .fm-kpi:hover { transform: translateY(-2px); border-color: rgba(255,255,255,0.2); }
    .fm-kpi .ic {
        width: 44px; height: 44px; border-radius: 11px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center; font-size: 1.15rem; color: #fff;
    }
    .fm-kpi .num { font-size: 1.5rem; font-weight: 800; color: #fff; line-height: 1.1; }
    .fm-kpi .lbl { font-size: 0.72rem; color: rgba(255,255,255,0.55); font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; }

    .fm-toolbar {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px; padding: 0.9rem 1.1rem;
        display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: center;
    }
    .fm-pill {
        border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.04);
        color: rgba(255,255,255,0.75); border-radius: 999px; padding: 0.4rem 0.9rem;
        font-size: 0.78rem; font-weight: 700; cursor: pointer; user-select: none;
        display: inline-flex; align-items: center; gap: 0.45rem; transition: all 0.15s;
    }
    .fm-pill:hover { border-color: rgba(255,255,255,0.35); color: #fff; }
    .fm-pill.active { background: rgba(139,92,246,0.2); border-color: #8b5cf6; color: #c4b5fd; }
    .fm-pill .cnt { background: rgba(255,255,255,0.12); border-radius: 999px; padding: 0 0.45rem; font-size: 0.7rem; }
    .fm-pill.active .cnt { background: rgba(139,92,246,0.35); }

    .fm-search {
        flex: 1; min-width: 220px; display: flex; align-items: center; gap: 0.55rem;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px; padding: 0.45rem 0.8rem;
    }
    .fm-search i { color: rgba(255,255,255,0.4); }
    .fm-search input {
        background: transparent; border: none; outline: none; color: #fff;
        font-size: 0.85rem; width: 100%;
    }
    .fm-search input::placeholder { color: rgba(255,255,255,0.35); }
    .fm-select {
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.12);
        border-radius: 10px; color: #fff; font-size: 0.82rem; padding: 0.45rem 0.7rem;
    }
    .fm-select option, .fm-select optgroup { background: #0f172a; color: #fff; }

    .fm-table-wrap {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px; overflow: hidden;
    }
    .fm-table {
        color: rgba(255,255,255,0.85); font-size: 0.85rem; margin: 0;
        --bs-table-bg: transparent;
        --bs-table-color: rgba(255,255,255,0.85);
        --bs-table-border-color: rgba(255,255,255,0.07);
        --bs-table-hover-bg: transparent;
    }
    .fm-table > :not(caption) > * > * { background-color: transparent !important; box-shadow: none; }
    .fm-table thead th {
        color: rgba(255,255,255,0.45); font-weight: 700; font-size: 0.7rem;
        text-transform: uppercase; letter-spacing: 0.05em;
        border-bottom: 1px solid rgba(255,255,255,0.09); padding: 0.65rem 0.85rem;
    }
    .fm-table tbody td { border-bottom: 1px solid rgba(255,255,255,0.05); padding: 0.7rem 0.85rem; vertical-align: middle; }
    .fm-table tbody tr:hover > * { background-color: rgba(139,92,246,0.06) !important; }

    .fm-thumb { width: 58px; height: 40px; border-radius: 8px; object-fit: cover; }
    .fm-thumb-ph {
        width: 58px; height: 40px; border-radius: 8px;
        background: rgba(255,255,255,0.06); display: flex; align-items: center; justify-content: center;
        color: rgba(255,255,255,0.3);
    }
    .fm-name { color: #fff; font-weight: 700; }
    .fm-sub { color: rgba(255,255,255,0.5); font-size: 0.75rem; }

    .stb { display: inline-flex; align-items: center; gap: 0.3rem; font-size: 0.72rem; font-weight: 800; padding: 0.25rem 0.65rem; border-radius: 999px; }
    .stb-active { background: rgba(34,197,94,0.14); color: #4ade80; border: 1px solid rgba(34,197,94,0.35); }
    .stb-draft { background: rgba(148,163,184,0.14); color: #94a3b8; border: 1px solid rgba(148,163,184,0.3); }
    .stb-inactive { background: rgba(251,191,36,0.14); color: #fbbf24; border: 1px solid rgba(251,191,36,0.35); }
    .stb-archived { background: rgba(239,68,68,0.14); color: #f87171; border: 1px solid rgba(239,68,68,0.35); }

    .fm-act {
        width: 32px; height: 32px; border-radius: 9px; border: none;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.8rem; transition: all 0.15s; text-decoration: none;
    }
    .fm-act-view { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.4); }
    .fm-act-view:hover { background: rgba(59,130,246,0.3); color: #bfdbfe; }
    .fm-act-edit { background: rgba(251,191,36,0.12); color: #fbbf24; border: 1px solid rgba(251,191,36,0.35); }
    .fm-act-edit:hover { background: rgba(251,191,36,0.25); }
    .fm-act-on { background: rgba(34,197,94,0.15); color: #4ade80; border: 1px solid rgba(34,197,94,0.4); }
    .fm-act-on:hover { background: rgba(34,197,94,0.28); }
    .fm-act-off { background: rgba(148,163,184,0.12); color: #94a3b8; border: 1px solid rgba(148,163,184,0.3); }
    .fm-act-off:hover { background: rgba(251,191,36,0.2); color: #fbbf24; }
    .fm-act-del { background: rgba(239,68,68,0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.35); }
    .fm-act-del:hover { background: rgba(239,68,68,0.25); }

    .fm-empty { text-align: center; padding: 3rem 1rem; color: rgba(255,255,255,0.5); }
    .fm-empty i { font-size: 2.2rem; display: block; margin-bottom: 0.75rem; opacity: 0.4; }

    .mod-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
</style>
@endpush

@section('content')
@php
    $moduleMeta = [
        'design-graphique' => ['label' => 'Design Graphique', 'icon' => 'fa-palette', 'color' => '#3b82f6'],
        'design-graphique-cm' => ['label' => 'Design & Community', 'icon' => 'fa-object-group', 'color' => '#8b5cf6'],
        'community-management' => ['label' => 'Community Management', 'icon' => 'fa-users', 'color' => '#ec4899'],
        'gestion-informatique' => ['label' => 'Gestion Informatique', 'icon' => 'fa-laptop-code', 'color' => '#f59e0b'],
        'intelligence-artificielle' => ['label' => 'Intelligence Artificielle', 'icon' => 'fa-brain', 'color' => '#06b6d4'],
    ];
    $inactiveCount = ($stats['inactive'] ?? 0) + ($stats['archived'] ?? 0);
@endphp

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="h3 mb-0 text-white"><i class="fas fa-graduation-cap me-2" style="color:#a78bfa;"></i>Gestion des Formations</h1>
        <a href="{{ route('admin.formations.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Créer une formation</a>
    </div>

    <!-- KPIs -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md">
            <div class="fm-kpi">
                <div class="ic" style="background: linear-gradient(135deg,#1e3c72,#2a5298);"><i class="fas fa-graduation-cap"></i></div>
                <div><div class="num">{{ $stats['total'] }}</div><div class="lbl">Total</div></div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="fm-kpi">
                <div class="ic" style="background: linear-gradient(135deg,#10b981,#059669);"><i class="fas fa-check-circle"></i></div>
                <div><div class="num">{{ $stats['active'] }}</div><div class="lbl">Actives</div></div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="fm-kpi">
                <div class="ic" style="background: linear-gradient(135deg,#f59e0b,#d97706);"><i class="fas fa-file-alt"></i></div>
                <div><div class="num">{{ $stats['draft'] }}</div><div class="lbl">Brouillons</div></div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="fm-kpi">
                <div class="ic" style="background: linear-gradient(135deg,#64748b,#475569);"><i class="fas fa-pause-circle"></i></div>
                <div><div class="num">{{ $inactiveCount }}</div><div class="lbl">Inact./Arch.</div></div>
            </div>
        </div>
        <div class="col-6 col-md">
            <div class="fm-kpi">
                <div class="ic" style="background: linear-gradient(135deg,#4fc3f7,#29b6f6);"><i class="fas fa-calendar-plus"></i></div>
                <div><div class="num">{{ $stats['ce_mois'] }}</div><div class="lbl">Ce mois</div></div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="fm-toolbar mb-3">
        <span class="fm-pill active" data-module=""><i class="fas fa-layer-group"></i>Tous modules</span>
        @foreach($moduleMeta as $slug => $meta)
            @if(($statsByModule[$slug] ?? 0) > 0 || true)
                <span class="fm-pill" data-module="{{ $slug }}">
                    <span class="mod-dot" style="background: {{ $meta['color'] }};"></span>
                    {{ $meta['label'] }}
                    <span class="cnt">{{ $statsByModule[$slug] ?? 0 }}</span>
                </span>
            @endif
        @endforeach
    </div>

    <div class="fm-toolbar mb-4">
        <span class="fm-pill active" data-status="">Tous statuts</span>
        <span class="fm-pill" data-status="active"><i class="fas fa-circle" style="color:#4ade80;font-size:0.5rem;"></i>Actives</span>
        <span class="fm-pill" data-status="draft"><i class="fas fa-circle" style="color:#94a3b8;font-size:0.5rem;"></i>Brouillons</span>
        <span class="fm-pill" data-status="inactive"><i class="fas fa-circle" style="color:#fbbf24;font-size:0.5rem;"></i>Inactives</span>
        <span class="fm-pill" data-status="archived"><i class="fas fa-circle" style="color:#f87171;font-size:0.5rem;"></i>Archivées</span>

        <select class="fm-select" id="catFilter">
            <option value="">Toutes catégories</option>
            @foreach($statsByCategory as $module => $categories)
                <optgroup label="{{ $moduleMeta[$module]['label'] ?? $module }}">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->category_name }}">{{ $cat->category_name }} ({{ $cat->total }})</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>

        <div class="fm-search">
            <i class="fas fa-search"></i>
            <input type="text" id="fmSearch" placeholder="Rechercher (nom, catégorie, module)…">
        </div>

        <button type="button" class="fm-pill" id="fmReset" style="display:none;">
            <i class="fas fa-redo"></i>Réinitialiser
        </button>
    </div>

    <!-- Table -->
    <div class="fm-table-wrap">
        <div class="table-responsive">
            <table class="table fm-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Image</th>
                        <th style="min-width:200px;">Formation</th>
                        <th>Catégorie</th>
                        <th>Module</th>
                        <th>Statut</th>
                        <th>Étudiants</th>
                        <th>Créée le</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody id="fmTbody">
                    @forelse($formations as $formation)
                        @php
                            $moduleSlug = $formation->modules[0] ?? '';
                            $catModule = $formation->category->module ?? '';
                            $catName = $formation->category->name ?? 'N/A';
                        @endphp
                        <tr class="fm-row"
                            data-module="{{ $moduleSlug }}"
                            data-catmodule="{{ $catModule }}"
                            data-category="{{ $catName }}"
                            data-status="{{ $formation->status }}"
                            data-search="{{ strtolower(($formation->name ?? '') . ' ' . $catName . ' ' . ($moduleMeta[$moduleSlug]['label'] ?? $moduleSlug) . ' ' . ($moduleMeta[$catModule]['label'] ?? $catModule)) }}">
                            <td>
                                @if($formation->image_url)
                                    <img src="{{ \App\Models\MediaUrl::fromPath($formation->image_url) }}" alt="" class="fm-thumb">
                                @else
                                    <div class="fm-thumb-ph"><i class="fas fa-image"></i></div>
                                @endif
                            </td>
                            <td>
                                <div class="fm-name">{{ $formation->name }}</div>
                                @if($formation->is_featured)
                                    <div class="fm-sub"><i class="fas fa-star" style="color:#fbbf24;"></i> À la une</div>
                                @endif
                            </td>
                            <td><span class="fm-sub" style="font-size:0.8rem;">{{ $catName }}</span></td>
                            <td>
                                @php $m = $moduleMeta[$moduleSlug] ?? $moduleMeta[$catModule] ?? null; @endphp
                                @if($m)
                                    <span class="d-inline-flex align-items-center gap-2" style="font-size:0.8rem;">
                                        <span class="mod-dot" style="background:{{ $m['color'] }};"></span>{{ $m['label'] }}
                                    </span>
                                @else
                                    <span class="fm-sub">{{ $moduleSlug ?: '—' }}</span>
                                @endif
                            </td>
                            <td><span class="stb stb-{{ $formation->status }}">{{ $formation->status_label }}</span></td>
                            <td><span class="fw-bold" style="color:#93c5fd;">{{ $formation->students_count }}</span></td>
                            <td><span class="fm-sub">{{ $formation->created_at->format('d/m/Y') }}</span></td>
                            <td class="text-end" style="white-space:nowrap;">
                                <a href="{{ route('admin.formations.show', $formation) }}" class="fm-act fm-act-view" title="Voir"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.formations.edit', $formation) }}" class="fm-act fm-act-edit" title="Modifier"><i class="fas fa-pen"></i></a>
                                <form action="{{ route('admin.formations.toggleStatus', $formation) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    @if($formation->status === 'active')
                                        <button type="submit" class="fm-act fm-act-off" title="Désactiver"><i class="fas fa-power-off"></i></button>
                                    @else
                                        <button type="submit" class="fm-act fm-act-on" title="Activer"><i class="fas fa-check"></i></button>
                                    @endif
                                </form>
                                <form action="{{ route('admin.formations.destroy', $formation) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Supprimer définitivement « {{ addslashes($formation->name) }} » ?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="fm-act fm-act-del" title="Supprimer"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8"><div class="fm-empty"><i class="fas fa-graduation-cap"></i>Aucune formation trouvée.</div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="fm-empty d-none" id="fmEmpty"><i class="fas fa-search"></i>Aucune formation ne correspond aux filtres.</div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const state = { module: '', status: '', category: '', q: '' };
    const rows = Array.from(document.querySelectorAll('.fm-row'));
    const empty = document.getElementById('fmEmpty');
    const resetBtn = document.getElementById('fmReset');
    const catFilter = document.getElementById('catFilter');
    const search = document.getElementById('fmSearch');

    function apply() {
        let visible = 0;
        rows.forEach(row => {
            const mod = row.dataset.module || '';
            const catMod = row.dataset.catmodule || '';
            const okModule = !state.module || mod === state.module || catMod === state.module;
            const okStatus = !state.status || row.dataset.status === state.status;
            const okCat = !state.category || row.dataset.category === state.category;
            const okQ = !state.q || (row.dataset.search || '').includes(state.q);
            const show = okModule && okStatus && okCat && okQ;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        empty.classList.toggle('d-none', visible > 0 || rows.length === 0);
        resetBtn.style.display = (state.module || state.status || state.category || state.q) ? '' : 'none';
    }

    function bindPills(attr, key) {
        document.querySelectorAll(`.fm-pill[data-${attr}]`).forEach(pill => {
            pill.addEventListener('click', () => {
                document.querySelectorAll(`.fm-pill[data-${attr}]`).forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                state[key] = pill.dataset[attr];
                apply();
            });
        });
    }

    bindPills('module', 'module');
    bindPills('status', 'status');

    catFilter.addEventListener('change', () => { state.category = catFilter.value; apply(); });
    search.addEventListener('input', () => { state.q = search.value.toLowerCase().trim(); apply(); });

    resetBtn.addEventListener('click', () => {
        state.module = state.status = state.category = state.q = '';
        catFilter.value = '';
        search.value = '';
        document.querySelectorAll('.fm-pill').forEach(p => p.classList.remove('active'));
        document.querySelector('.fm-pill[data-module=""]').classList.add('active');
        document.querySelector('.fm-pill[data-status=""]').classList.add('active');
        apply();
    });
})();
</script>
@endpush
@endsection
