@extends('layouts.admin')

@section('title', 'Plaquettes de formation')

@push('styles')
<style>
    /* Cartes de statistiques modernes (même base que Formations) */
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

    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">Gestion des Plaquettes</h1>
        <a href="{{ route('admin.plaquettes.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Créer une plaquette</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="stat-label">Total Plaquettes</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['published'] ?? 0 }}</h3>
                    <p class="stat-label">Publiées</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-power-off"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['active'] ?? 0 }}</h3>
                    <p class="stat-label">Actives</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['ce_mois'] ?? 0 }}</h3>
                    <p class="stat-label">Ajoutées ce Mois</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste des Plaquettes</h5>
            <span class="badge bg-primary">{{ $plaquettes->count() }}</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th style="min-width: 220px;">Titre</th>
                            <th>Formation</th>
                            <th>Format</th>
                            <th class="text-nowrap">Date de début</th>
                            <th class="text-nowrap">Date de fin</th>
                            <th class="text-nowrap">Statut</th>
                            <th class="text-nowrap">Téléchargements</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plaquettes as $p)
                            @php
                                $statusLabel = 'Brouillon';
                                $statusClass = 'secondary';
                                if (!$p->is_active) {
                                    $statusLabel = 'Désactivée';
                                    $statusClass = 'warning';
                                } elseif ($p->is_published) {
                                    $statusLabel = 'Publiée';
                                    $statusClass = 'success';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $p->title }}</div>
                                    <div class="small text-muted">{{ $p->original_filename }}</div>
                                </td>
                                <td>{{ $p->formation?->name ?? '—' }}</td>
                                <td>{{ $p->format === 'offline' ? 'Off line' : 'En ligne' }}</td>
                                <td class="text-nowrap">{{ $p->start_date ? $p->start_date->format('d/m/Y') : '—' }}</td>
                                <td class="text-nowrap">{{ $p->end_date ? $p->end_date->format('d/m/Y') : '—' }}</td>
                                <td>
                                    <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-nowrap">{{ (int) ($p->download_count ?? 0) }}</td>
                                <td>
                                    <a href="{{ route('admin.plaquettes.download', $p) }}" class="btn btn-sm btn-info">Voir</a>
                                    <a href="{{ route('admin.plaquettes.edit', $p) }}" class="btn btn-sm btn-warning">Modifier</a>

                                    <form method="POST" action="{{ route('admin.plaquettes.toggle-publish', $p) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-light" title="Publier / Dépublier">
                                            <i class="fas fa-bullhorn"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.plaquettes.toggle-active', $p) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Activer / Désactiver">
                                            <i class="fas fa-power-off"></i>
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.plaquettes.delete', $p) }}" class="d-inline" onsubmit="return confirm('Supprimer cette plaquette ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">Aucune plaquette trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
