@extends('layouts.admin')

@section('title', 'Gestion des séances')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Gestion des séances</h1>
        <a href="{{ route('admin.seances.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Nouvelle séance
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.seances.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Formation</label>
                    <select name="formation" class="form-select">
                        <option value="">Toutes</option>
                        @foreach($formations as $formation)
                            <option value="{{ $formation }}" {{ request('formation') == $formation ? 'selected' : '' }}>{{ $formation }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select">
                        <option value="">Tous</option>
                        <option value="planned" {{ request('status') == 'planned' ? 'selected' : '' }}>Planifiée</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Tous</option>
                        <option value="presentiel" {{ request('type') == 'presentiel' ? 'selected' : '' }}>Présentiel</option>
                        <option value="online" {{ request('type') == 'online' ? 'selected' : '' }}>En ligne</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="fas fa-filter me-1"></i> Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0 table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Titre</th>
                        <th>Formation</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($seances as $seance)
                        <tr>
                            <td><strong>{{ $seance->title }}</strong></td>
                            <td>{{ $seance->formation }}</td>
                            <td>
                                @if($seance->type === 'online')
                                    <span class="badge bg-info text-dark"><i class="fas fa-video me-1"></i>En ligne</span>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-map-marker-alt me-1"></i>Présentiel</span>
                                @endif
                            </td>
                            <td>{{ $seance->scheduled_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $seance->duration_minutes }} min</td>
                            <td>
                                @if($seance->status === 'planned')
                                    <span class="badge bg-secondary">Planifiée</span>
                                @elseif($seance->status === 'completed')
                                    <span class="badge bg-primary">Terminée</span>
                                @else
                                    <span class="badge bg-danger">Annulée</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.seances.attendance', $seance) }}" class="btn btn-sm btn-outline-success me-1" title="Marquer les présences">
                                    <i class="fas fa-clipboard-check"></i>
                                </a>
                                <a href="{{ route('admin.seances.edit', $seance) }}" class="btn btn-sm btn-outline-primary me-1" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.seances.destroy', $seance) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Aucune séance trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
