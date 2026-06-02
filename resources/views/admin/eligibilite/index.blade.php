@extends('layouts.admin')

@section('title', 'Tests d’éligibilité SAOP')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h3 class="mb-1" style="font-weight:800;color:#0f172a;">Tests d’éligibilité SAOP</h3>
            <div class="text-muted">Réponses soumises par les candidats depuis la page publique.</div>
        </div>
        <form method="GET" action="{{ route('admin.eligibilite.index') }}" class="row g-2 align-items-center">
            <div class="col-auto"><input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Nom, email, WhatsApp"></div>
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
            <div class="col-auto"><button class="btn btn-primary" style="border-radius:12px;"><i class="fas fa-filter me-2"></i>Filtrer</button></div>
        </form>
    </div>

    <div class="row mb-4">
        <div class="col-md-4"><div class="stat-card" style="background:linear-gradient(135deg,#1e3c72 0%,#2a5298 100%);"><div class="stat-icon"><i class="fas fa-layer-group"></i></div><div class="stat-content"><h3>{{ $stats['total'] ?? 0 }}</h3><p>Total</p></div></div></div>
        <div class="col-md-4"><div class="stat-card" style="background:linear-gradient(135deg,#ff9800 0%,#fb8c00 100%);"><div class="stat-icon"><i class="fas fa-calendar-day"></i></div><div class="stat-content"><h3>{{ $stats['today'] ?? 0 }}</h3><p>Aujourd’hui</p></div></div></div>
        <div class="col-md-4"><div class="stat-card" style="background:linear-gradient(135deg,#10b981 0%,#059669 100%);"><div class="stat-icon"><i class="fas fa-calendar-alt"></i></div><div class="stat-content"><h3>{{ $stats['this_month'] ?? 0 }}</h3><p>Ce mois</p></div></div></div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Candidat</th>
                        <th>Formation</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $test)
                        <tr>
                            <td><strong>{{ $test->full_name }}</strong><br><span class="text-muted small">{{ $test->email }} @if($test->whatsapp) · {{ $test->whatsapp }} @endif</span></td>
                            <td>{{ $test->formation ? ucwords(str_replace('_', ' ', $test->formation)) : '—' }}</td>
                            <td>{{ gmdate('H\hi\ms\s', (int) $test->duration_seconds) }}</td>
                            <td>
                                @if($test->status === 'auto_submitted')
                                    <span class="badge bg-warning text-dark">Auto</span>
                                @else
                                    <span class="badge bg-success">Soumis</span>
                                @endif
                            </td>
                            <td>{{ optional($test->submitted_at)->format('d/m/Y H:i') }}</td>
                            <td class="text-end"><a href="{{ route('admin.eligibilite.show', $test) }}" class="btn btn-sm btn-primary" style="border-radius:10px;"><i class="fas fa-eye me-1"></i>Voir</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-5">Aucun test soumis pour le moment.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tests->hasPages())<div class="p-3 border-top">{{ $tests->links() }}</div>@endif
    </div>
</div>
@endsection
