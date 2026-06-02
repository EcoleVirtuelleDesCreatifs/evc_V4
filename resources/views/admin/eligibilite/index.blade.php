@extends('layouts.admin')

@section('title', 'Tests d’éligibilité SAOP')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <div class="text-muted">Suivi des réponses soumises par les candidats.</div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <form method="GET" action="{{ route('admin.eligibilite.index') }}" class="row g-2 align-items-center">
                <div class="col-auto">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Recherche (nom, email, whatsapp)" class="form-control" />
                </div>
                <div class="col-auto">
                    <select name="formation" class="form-select">
                        <option value="">Toutes formations</option>
                        <option value="design_graphique" @selected(request('formation')==='design_graphique')>Design Graphique</option>
                        <option value="community_management" @selected(request('formation')==='community_management')>Community Management</option>
                        <option value="design_graphique_community_manager" @selected(request('formation')==='design_graphique_community_manager')>Design Graphique & Community Manager</option>
                        <option value="gestion_informatique" @selected(request('formation')==='gestion_informatique')>Gestion Informatique</option>
                        <option value="intelligence_artificielle" @selected(request('formation')==='intelligence_artificielle')>Intelligence Artificielle</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" style="border-radius: 12px;"><i class="fas fa-filter me-2"></i>Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="stat-icon"><i class="fas fa-layer-group"></i></div>
                <div class="stat-content"><h3>{{ $stats['total'] ?? 0 }}</h3><p>Total tests</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-content"><h3>{{ $stats['today'] ?? 0 }}</h3><p>Aujourd’hui</p></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);">
                <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-content"><h3>{{ $stats['this_month'] ?? 0 }}</h3><p>Ce mois</p></div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-white border-0 py-3" style="border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="fas fa-clipboard-check me-2" style="color: #1e3c72;"></i>
                Liste des tests soumis
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-sm align-middle mb-0 text-body">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>ID</th>
                            <th>Candidat</th>
                            <th>Email</th>
                            <th>WhatsApp</th>
                            <th>Formation</th>
                            <th>Durée</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tests as $test)
                            <tr>
                                <td>{{ $test->id }}</td>
                                <td>{{ $test->full_name }}</td>
                                <td>{{ $test->email }}</td>
                                <td>{{ $test->whatsapp ?: '—' }}</td>
                                <td>{{ $test->formation ? ucwords(str_replace('_', ' ', $test->formation)) : '—' }}</td>
                                <td>{{ gmdate('H\hi\ms\s', (int) $test->duration_seconds) }}</td>
                                <td>
                                    @if($test->status === 'auto_submitted')
                                        <span class="badge bg-warning text-dark">Auto</span>
                                    @else
                                        <span class="badge bg-success">Soumis</span>
                                    @endif
                                </td>
                                <td>{{ optional($test->submitted_at)->format('Y-m-d H:i') }}</td>
                                <td class="text-nowrap">
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <a href="{{ route('admin.eligibilite.show', $test) }}" class="btn btn-sm btn-outline-secondary btn-icon" title="Voir détails" aria-label="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Aucun test soumis pour le moment.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card-footer bg-white border-0 py-3" style="border-radius: 0 0 16px 16px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="pagination-info text-muted mb-2 mb-md-0">
                @if($tests->total() > 0)
                    Affichage de <strong>{{ $tests->firstItem() }}</strong> à <strong>{{ $tests->lastItem() }}</strong> sur <strong>{{ $tests->total() }}</strong> tests
                @else
                    Aucun résultat
                @endif
            </div>
            <div>
                {{ $tests->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    border-radius: 16px;
    padding: 1.5rem;
    color: white;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.btn-icon {
    width: 36px;
    height: 36px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.2);
}

.stat-icon {
    font-size: 2rem;
    opacity: 0.9;
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    line-height: 1;
}

.stat-content p {
    margin: 0.5rem 0 0 0;
    font-size: 0.9rem;
    opacity: 0.9;
}

.pagination-info {
    font-size: 0.9rem;
}

.pagination {
    margin: 0;
}

.pagination .page-link {
    color: #1e3c72;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin: 0 0.25rem;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    transition: all 0.3s;
}

.pagination .page-link:hover {
    background-color: #1e3c72;
    color: white;
    border-color: #1e3c72;
}

.pagination .page-item.active .page-link {
    background-color: #1e3c72;
    border-color: #1e3c72;
}
</style>
@endsection
