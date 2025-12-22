@extends('layouts.admin')

@section('title', 'Studio Creative')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .payments-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 24px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 20px 60px rgba(42, 82, 152, 0.35);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: #2a5298;
        box-shadow: 0 10px 30px rgba(42, 82, 152, 0.25);
    }

    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #60a5fa;
        margin-bottom: 0.5rem;
    }

    .stat-card .stat-label {
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .payments-table-container {
        background: #1e293b;
        border-radius: 20px;
        padding: 2rem;
        border: 1px solid #334155;
    }

    .payments-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .payments-table thead th {
        background: #0f172a;
        color: #94a3b8;
        padding: 1rem;
        text-align: left;
        font-weight: 600;
        border-bottom: 2px solid #334155;
    }

    .payments-table tbody tr {
        background: #1e293b;
        transition: all 0.2s ease;
    }

    .payments-table tbody tr:hover {
        background: #2d3748;
        transform: scale(1.01);
    }

    .payments-table tbody td {
        padding: 1rem;
        color: #e2e8f0;
        border-bottom: 1px solid #334155;
    }

    .badge {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .badge-completed {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .badge-pending {
        background: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }

    .badge-cancelled {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .btn-view {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        display: inline-block;
    }

    .btn-view:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="payments-header">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
            <div>
                <h1 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                    <i class="fas fa-magic me-2"></i>
                    Studio Creative
                </h1>
                <p style="opacity: 0.9; font-size: 1.1rem;">Projets Design Graphique créés depuis l'espace étudiant</p>
            </div>
            <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
                <a href="{{ route('admin.design-projects.index') }}" class="btn btn-outline-light">
                    <i class="fas fa-layer-group me-2"></i>
                    Tous les projets Design
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour au dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ (int) ($stats['total'] ?? 0) }}</div>
            <div class="stat-label">
                <i class="fas fa-list me-1"></i>
                Total projets
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: #3b82f6;">{{ (int) ($stats['solo'] ?? 0) }}</div>
            <div class="stat-label">
                <i class="fas fa-user me-1"></i>
                Solo
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: #f59e0b;">{{ (int) ($stats['groupe'] ?? 0) }}</div>
            <div class="stat-label">
                <i class="fas fa-users me-1"></i>
                Groupe
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: #8b5cf6;">{{ (int) ($stats['in_progress'] ?? 0) }}</div>
            <div class="stat-label">
                <i class="fas fa-spinner me-1"></i>
                En cours
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-value" style="color: #f59e0b;">{{ (int) ($stats['pending'] ?? 0) }}</div>
            <div class="stat-label">
                <i class="fas fa-clock me-1"></i>
                En validation
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-value">{{ (int) ($stats['completed'] ?? 0) }}</div>
            <div class="stat-label">
                <i class="fas fa-check-circle me-1"></i>
                Terminés / Validés
            </div>
        </div>
    </div>

    <div class="payments-table-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap;">
            <h2 style="color: white; font-size: 1.5rem; margin: 0;">
                <i class="fas fa-list me-2"></i>
                Liste des Projets
            </h2>
            <span style="color: #94a3b8;">{{ ($projects ?? collect())->count() }} projets</span>
        </div>

        <div style="overflow-x: auto;">
            <table class="payments-table">
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Projet</th>
                        <th>Mode</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($projects as $project)
                        @php
                            $student = optional(optional($project->user)->student);
                            $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
                            $studentName = $studentName !== '' ? $studentName : (optional($project->user)->name ?? 'Étudiant');
                            $studentEmail = optional($project->user)->email;

                            $rawPhoto = $student->profile_photo ?? null;
                            if ($rawPhoto) {
                                if (preg_match('/^https?:\/\//', $rawPhoto)) {
                                    $studentPhotoUrl = $rawPhoto;
                                } elseif (str_starts_with($rawPhoto, 'photos_preregistrations/')) {
                                    $studentPhotoUrl = asset('storage/' . $rawPhoto);
                                } elseif (str_starts_with($rawPhoto, 'uploads/')) {
                                    $studentPhotoUrl = asset($rawPhoto);
                                } else {
                                    $studentPhotoUrl = asset('storage/' . $rawPhoto);
                                }
                            } else {
                                $studentPhotoUrl = asset('assets/img/avatar.png');
                            }

                            $modeRaw = $project->project_mode ?? ($project->category ?? null);
                            $modeRaw = is_string($modeRaw) ? strtolower(trim($modeRaw)) : null;
                            $modeLabel = $modeRaw === 'groupe' ? 'Groupe' : ($modeRaw === 'solo' ? 'Solo' : '-');

                            $status = $project->status ?? null;
                            $statusLabel = $status === 'validated' ? 'Validé' : 'En cours';
                            if ($status === 'validated') {
                                $statusClass = 'badge-completed';
                            } elseif ($status === 'pending') {
                                $statusClass = 'badge-pending';
                            } elseif ($status === 'rejected') {
                                $statusClass = 'badge-cancelled';
                            } else {
                                $statusClass = 'badge-pending';
                            }

                            $progress = (int) ($project->progress_percentage ?? 0);
                        @endphp
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <img src="{{ $studentPhotoUrl }}"
                                         alt="{{ $studentName }}"
                                         style="width: 38px; height: 38px; border-radius: 10px; object-fit: cover; border: 1px solid rgba(255,255,255,0.15);"
                                         onerror="this.src='{{ asset('assets/img/avatar.png') }}'">
                                    <div style="font-weight: 600;">{{ $studentName }}</div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 600;">{{ $project->title ?? 'Projet' }}</div>
                                @if(!empty($project->project_type))
                                    <div style="font-size: 0.85rem; color: #94a3b8;">Type: {{ $project->project_type }}</div>
                                @endif
                            </td>
                            <td>
                                <span style="background: #0f172a; padding: 0.3rem 0.6rem; border-radius: 6px;">{{ $modeLabel }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $statusClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <div>{{ optional($project->created_at)->format('d/m/Y') ?? '-' }}</div>
                                <div style="font-size: 0.85rem; color: #94a3b8;">{{ optional($project->created_at)->format('H:i') ?? '' }}</div>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-start; flex-wrap: wrap;">
                                    <a href="{{ route('admin.design-projects.view', $project->id) }}" class="btn-view">
                                        <i class="fas fa-eye me-1"></i>Voir
                                    </a>

                                    <a href="{{ route('admin.design-projects.edit', $project->id) }}" class="btn btn-outline-light btn-sm" style="border-radius: 8px;">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>

                                    <form action="{{ route('admin.design-projects.validate', $project->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" style="border-radius: 8px;">
                                            <i class="fas fa-check me-1"></i>Valider
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.design-projects.reject', $project->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" style="border-radius: 8px;">
                                            <i class="fas fa-times me-1"></i>Rejeter
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.design-projects.delete', $project->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce projet ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 8px;">
                                            <i class="fas fa-trash me-1"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 3rem; color: #64748b;">
                                <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                                <p>Aucun projet trouvé</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
