@extends('layouts.admin')

@section('title', 'Plaquettes de formation')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="h4 mb-1 text-white">Plaquettes de formation</h2>
            <div class="text-muted">Gérez les plaquettes (métadonnées + PDF) affichées sur le site public.</div>
        </div>
        <a href="{{ route('admin.plaquettes.create') }}" class="btn btn-warning">
            <i class="fas fa-plus me-2"></i>Nouvelle plaquette
        </a>
    </div>

    <div class="card border-0" style="background: rgba(255,255,255,0.04); backdrop-filter: blur(12px);">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="h6 text-white mb-0">Toutes les plaquettes</h3>
                <span class="badge text-bg-dark">{{ $plaquettes->count() }}</span>
            </div>

            @if($plaquettes->isEmpty())
                <div class="text-muted">Aucune plaquette pour le moment.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle mb-0" style="--bs-table-bg: rgba(0,0,0,0.15); --bs-table-border-color: rgba(255,255,255,0.08);">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Formation</th>
                                <th class="text-nowrap">Date de début</th>
                                <th class="text-nowrap">Date de fin</th>
                                <th class="text-nowrap">Statut</th>
                                <th class="text-nowrap">Téléchargements</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plaquettes as $p)
                                @php
                                    $statusLabel = 'Brouillon';
                                    if (!$p->is_active) {
                                        $statusLabel = 'Désactivée';
                                    } elseif ($p->is_published) {
                                        $statusLabel = 'Publiée';
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $p->title }}</div>
                                        <div class="small text-muted">{{ $p->format === 'offline' ? 'Off line' : 'En ligne' }}</div>
                                    </td>
                                    <td>{{ $p->formation?->name ?? '—' }}</td>
                                    <td class="text-nowrap">{{ $p->start_date ? $p->start_date->format('d/m/Y') : '—' }}</td>
                                    <td class="text-nowrap">{{ $p->end_date ? $p->end_date->format('d/m/Y') : '—' }}</td>
                                    <td class="text-nowrap">{{ $statusLabel }}</td>
                                    <td class="text-nowrap">{{ (int) ($p->download_count ?? 0) }}</td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-light" href="{{ route('admin.plaquettes.download', $p) }}">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a class="btn btn-sm btn-outline-warning" href="{{ route('admin.plaquettes.edit', $p) }}">
                                            <i class="fas fa-pen"></i>
                                        </a>

                                        <form method="POST" action="{{ route('admin.plaquettes.toggle-publish', $p) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-info" title="Publier / Dépublier">
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
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection
