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

    .programme-accordion-header {
        padding: 1rem 1.25rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #334155;
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

    <!-- Liste des programmes -->
    @if($programmes->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucun programme disponible</h3>
            <p>Commencez par ajouter un programme de formation</p>
            <a href="{{ route('admin.programmes.create') }}" class="btn-export mt-3">
                <i class="fas fa-plus me-2"></i>
                Ajouter un Programme
            </a>
        </div>
    @else
        <div class="row g-4" id="programmesContainer">
            @foreach($programmes as $programme)
                @php
                    $formation = $programme->formation ?? '';
                    $monthStart = $programme->month_start ?? '';
                    $items = $programme->items ?? collect();
                    $searchText = strtolower(
                        ($programme->titre ?? '') . ' ' .
                        ($programme->description ?? '') . ' ' .
                        ($formation ?? '') . ' ' .
                        $items->pluck('thematique')->implode(' ') . ' ' .
                        $items->pluck('lieu')->implode(' ') . ' ' .
                        $items->pluck('description')->implode(' ')
                    );
                @endphp

                <div class="col-12 programme-wrapper" data-formation="{{ $formation }}" data-month="{{ $monthStart }}" data-search="{{ $searchText }}">
                    <div class="programme-accordion">
                        <div class="programme-accordion-header">
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
                                    @if(!empty($programme->next_item))
                                        <span>
                                            <i class="fas fa-bolt"></i>
                                            Prochaine: {{ \Carbon\Carbon::parse($programme->next_item->session_date)->format('d/m') }} {{ \Carbon\Carbon::parse($programme->next_item->session_time)->format('H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="programme-actions">
                                <button class="btn-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#prog_{{ $programme->id }}" aria-expanded="false">
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

                        <div id="prog_{{ $programme->id }}" class="collapse">
                            <div style="padding: 1rem 1.25rem;">
                                @if($programme->description)
                                    <div style="color:#94a3b8; margin-bottom: 1rem;">
                                        {{ $programme->description }}
                                    </div>
                                @endif

                                @if($items->isEmpty())
                                    <div class="empty-state" style="padding: 1.5rem 1rem;">
                                        <i class="fas fa-inbox"></i>
                                        <h3>Aucune séance</h3>
                                        <p>Ce programme ne contient pas encore de séances.</p>
                                    </div>
                                @else
                                    <div style="overflow:auto; border-radius: 12px; border: 1px solid rgba(51, 65, 85, 0.7);">
                                        <table class="items-table">
                                            <thead>
                                                <tr>
                                                    <th>Séance</th>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Fichier</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $it)
                                                    @php
                                                        $filePath = $it->piece_jointe ?? null;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="item-title">{{ $it->thematique }}</div>
                                                            @if(!empty($it->description))
                                                                <div class="item-sub">{{ Str::limit($it->description, 120) }}</div>
                                                            @endif
                                                        </td>
                                                        <td style="white-space:nowrap;">
                                                            {{ \Carbon\Carbon::parse($it->session_date)->format('d/m/Y') }}
                                                            <div class="item-sub">{{ \Carbon\Carbon::parse($it->session_time)->format('H:i') }}</div>
                                                        </td>
                                                        <td>
                                                            <div style="white-space:nowrap;">
                                                                {{ ($it->type_formation ?? '') === 'presentielle' ? 'Présentielle' : 'En ligne' }}
                                                            </div>
                                                            @if(($it->type_formation ?? '') === 'presentielle' && !empty($it->lieu))
                                                                <div class="item-sub">{{ $it->lieu }}</div>
                                                            @endif
                                                        </td>
                                                        <td style="white-space:nowrap;">
                                                            @if(!empty($filePath))
                                                                <a href="{{ \App\Models\MediaUrl::fromPath($filePath) }}" target="_blank" class="btn-download btn-sm">
                                                                    <i class="fas fa-download me-1"></i>
                                                                    Télécharger
                                                                </a>
                                                            @else
                                                                <span class="item-sub">Aucun</span>
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
            @endforeach
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
    }

    if (searchInput) searchInput.addEventListener('input', applyFilters);
    if (formationFilter) formationFilter.addEventListener('change', applyFilters);
    if (monthFilter) monthFilter.addEventListener('change', applyFilters);

    applyFilters();
});
</script>
@endpush
