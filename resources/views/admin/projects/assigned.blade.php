@extends('layouts.admin')

@section('title', 'Projets attribués - Design Graphique')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div></div>
        <div>
            <a href="{{ route('admin.projets.design-graphique.to-send') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-paper-plane me-2"></i>Attribuer un projet
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-tasks"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['total'] ?? 0 }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Total</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-clock"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['en_cours'] ?? 0 }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Pas encore Fait</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['termine'] ?? 0 }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Terminés</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 16px; padding: 1.5rem; color: white; display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon" style="width: 60px; height: 60px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem;">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 2.2rem; font-weight: 700; margin: 0;">{{ $stats['valide'] ?? 0 }}</h3>
                    <p style="margin: 0; opacity: 0.9; font-size: 0.95rem;">Validés</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: #0f172a; border-bottom: 1px solid #334155;">
            <h5 class="mb-0 text-white"><i class="fas fa-list me-2"></i>Liste des travaux attribués</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Travail</th>
                            <th>Catégorie</th>
                            <th>Étudiant</th>
                            <th>Formation</th>
                            <th>Attribué le</th>
                            <th>Deadline</th>
                            <th>Statut</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($assignments as $work)
                            <tr>
                                <td>#{{ $work->id }}</td>
                                <td>{{ $work->title }}</td>
                                <td>{{ $work->category ?? '' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php
                                            $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($work->profile_photo ?? null);
                                        @endphp
                                        @if($photoUrl)
                                            <img src="{{ $photoUrl }}" alt="{{ $work->first_name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-weight: 600;">
                                                {{ strtoupper(substr($work->first_name ?? 'E', 0, 1)) }}{{ strtoupper(substr($work->last_name ?? '', 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-medium">{{ $work->first_name }} {{ $work->last_name }}</div>
                                            <small class="text-muted">{{ $work->student_email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $work->formation }}</td>
                                <td>{{ $work->created_at ? \Carbon\Carbon::parse($work->created_at)->format('d/m/Y H:i') : '' }}</td>
                                <td>{{ $work->deadline ? \Carbon\Carbon::parse($work->deadline)->format('d/m/Y') : '' }}</td>
                                <td>
                                    @php
                                        $status = $work->status ?? '';
                                        $statusLabel = $status === 'en_cours' ? 'Pas encore Fait' : $status;
                                        $statusClass = match ($status) {
                                            'en_cours' => 'bg-warning text-dark',
                                            'termine' => 'bg-info text-dark',
                                            'valide' => 'bg-success',
                                            'rejete' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.projects.view', $work->id) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye me-1"></i>Voir
                                    </a>

                                    <a href="{{ route('admin.projects.edit', $work->id) }}" class="btn btn-sm btn-outline-warning ms-1">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>

                                    <form action="{{ route('admin.projects.assigned.delete', $work->id) }}" method="POST" class="d-inline ms-1" onsubmit="return confirm('Supprimer ce projet ? Cette action est irréversible.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash me-1"></i>Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">Aucun projet attribué trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($assignments->hasPages())
            <div class="card-footer">
                {{ $assignments->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
