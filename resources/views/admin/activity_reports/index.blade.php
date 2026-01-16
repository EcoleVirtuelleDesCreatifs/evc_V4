@extends('layouts.admin')

@section('title', "Rapports d'activité")

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 text-white mb-1">Rapports d'activité</h1>
            <div class="text-muted">Gestion des rapports d'activité affichés sur le site public.</div>
        </div>
        <a href="{{ route('admin.activity-reports.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Nouveau rapport
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body">
            @if($reports->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-2 fw-bold">Aucun rapport</div>
                    <div class="text-muted">Clique sur “Nouveau rapport” pour publier le premier PDF.</div>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Année</th>
                                <th>Statut</th>
                                <th>Fichier</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $report)
                                <tr>
                                    <td class="fw-semibold">{{ $report->title }}</td>
                                    <td>{{ $report->year ?? '—' }}</td>
                                    <td>
                                        @if($report->is_published)
                                            <span class="badge bg-success">Publié</span>
                                        @else
                                            <span class="badge bg-secondary">Brouillon</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.activity-reports.download', $report) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i>PDF
                                        </a>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <form method="POST" action="{{ route('admin.activity-reports.toggle', $report) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $report->is_published ? 'btn-warning' : 'btn-success' }}">
                                                    <i class="fas {{ $report->is_published ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                                    {{ $report->is_published ? 'Dépublier' : 'Publier' }}
                                                </button>
                                            </form>

                                            <a href="{{ route('admin.activity-reports.edit', $report) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit me-1"></i>Modifier
                                            </a>

                                            <form method="POST" action="{{ route('admin.activity-reports.destroy', $report) }}" onsubmit="return confirm('Supprimer ce rapport ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash me-1"></i>Supprimer
                                                </button>
                                            </form>
                                        </div>
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
@endsection
