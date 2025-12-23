@extends('layouts.admin')

@section('title', 'Activités Récentes')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->


    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="stat-icon">
                    <i class="fas fa-list-check"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] }}</h3>
                    <p>Total Activités</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['en_attente'] }}</h3>
                    <p>En Attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['valides'] }}</h3>
                    <p>Validés</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['rejetes'] }}</h3>
                    <p>Rejetés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des activités -->
    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-white border-0 py-3" style="border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="fas fa-history me-2" style="color: #1e3c72;"></i>
                Historique des activités
            </h5>
        </div>
        <div class="card-body p-0">
            @if($studentsWithActivities->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune activité récente</h5>
                    <p class="text-muted">Les activités des étudiants apparaîtront ici</p>
                </div>
            @else
                <div class="activities-list">
                    @foreach($studentsWithActivities as $student)
                        <div class="activity-item" data-status="{{ $activity->status }}">
                            <div class="activity-avatar">
                                @php
                                    // Générer URL de la photo avec fallback ui-avatars.com
                                    $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode(($activity->first_name ?? 'E') . ' ' . ($activity->last_name ?? 'T')) . '&background=833AB4&color=fff&size=120';

                                    if ($activity->profile_photo ?? false) {
                                        $resolved = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($activity->profile_photo);
                                        if (!str_contains($resolved, 'default-avatar') && !str_contains($resolved, 'avatar.png')) {
                                            $photoUrl = $resolved;
                                        }
                                    }
                                @endphp

                                <img src="{{ $photoUrl }}" alt="{{ $activity->first_name ?? '' }} {{ $activity->last_name ?? '' }}">
                                <div class="status-indicator status-{{ $activity->status }}"></div>
                            </div>

                            <div class="activity-content">
                                <div class="activity-header">
                                    <h6 class="mb-1">
                                        <strong>{{ $activity->first_name }} {{ $activity->last_name }}</strong>
                                    </h6>
                                    <span class="activity-time">
                                        <i class="far fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($activity->updated_at)->locale('fr')->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="activity-description mb-1">
                                    @if($activity->status === 'submitted')
                                        a soumis un TP : <strong>{{ $activity->title }}</strong>
                                    @elseif($activity->status === 'validated')
                                        TP validé : <strong>{{ $activity->title }}</strong>
                                    @elseif($activity->status === 'rejected')
                                        TP rejeté : <strong>{{ $activity->title }}</strong>
                                    @else
                                        a un TP en attente : <strong>{{ $activity->title }}</strong>
                                    @endif
                                </p>
                                <div class="activity-meta">
                                    <span class="badge-formation" style="background: {{
                                        $activity->formation === 'Design Graphique' ? 'linear-gradient(135deg, #667eea, #764ba2)' :
                                        ($activity->formation === 'Community Management' ? 'linear-gradient(135deg, #f093fb, #f5576c)' :
                                        'linear-gradient(135deg, #4facfe, #00f2fe)')
                                    }}">
                                        {{ $activity->formation }}
                                    </span>
                                    @if($activity->status === 'pending' || $activity->status === 'submitted')
                                        <span class="status-badge status-waiting">
                                            <i class="fas fa-clock"></i> En attente
                                        </span>
                                    @elseif($activity->status === 'validated')
                                        <span class="status-badge status-validated">
                                            <i class="fas fa-check-circle"></i> Validé
                                        </span>
                                    @elseif($activity->status === 'rejected')
                                        <span class="status-badge status-rejected">
                                            <i class="fas fa-times-circle"></i> Rejeté
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="activity-actions">
                                <a href="{{ route('admin.tp.view', $activity->id) }}" class="btn btn-sm btn-outline-primary" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($activities->hasPages())
            <div class="card-footer bg-white border-0 py-3" style="border-radius: 0 0 16px 16px;">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="pagination-info text-muted mb-2 mb-md-0">
                        Affichage de <strong>{{ $activities->firstItem() }}</strong> à <strong>{{ $activities->lastItem() }}</strong> sur <strong>{{ $activities->total() }}</strong> activités
                    </div>
                    <div>
                        {{ $activities->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Cartes de statistiques */
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

/* Liste des activités */
.activities-list {
    display: flex;
    flex-direction: column;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.2s;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background-color: #f8f9fa;
}

/* Avatar */
.activity-avatar {
    position: relative;
    flex-shrink: 0;
}

.activity-avatar img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    object-position: center;
    display: block;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.avatar-initials {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.status-indicator {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid white;
}

.status-indicator.status-submitted,
.status-indicator.status-pending {
    background-color: #ff9800;
}

.status-indicator.status-validated {
    background-color: #4caf50;
}

.status-indicator.status-rejected {
    background-color: #f44336;
}

/* Contenu de l'activité */
.activity-content {
    flex-grow: 1;
}

.activity-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.85rem;
    color: #6c757d;
}

.activity-description {
    color: #495057;
    font-size: 0.95rem;
}

.activity-meta {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    margin-top: 0.5rem;
}

.badge-formation {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    color: white;
    font-size: 0.8rem;
    font-weight: 500;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.status-badge.status-waiting {
    background-color: #fff3cd;
    color: #856404;
}

.status-badge.status-validated {
    background-color: #d4edda;
    color: #155724;
}

.status-badge.status-rejected {
    background-color: #f8d7da;
    color: #721c24;
}

/* Actions */
.activity-actions {
    display: flex;
    gap: 0.5rem;
}

.activity-actions .btn {
    border-radius: 8px;
    transition: all 0.2s;
}

.activity-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Pagination */
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
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(30, 60, 114, 0.3);
}

.pagination .page-item.active .page-link {
    background-color: #1e3c72;
    border-color: #1e3c72;
    box-shadow: 0 2px 8px rgba(30, 60, 114, 0.3);
}

.pagination .page-item.disabled .page-link {
    background-color: #e9ecef;
    border-color: #dee2e6;
    color: #6c757d;
}

/* Responsive */
@media (max-width: 768px) {
    .activity-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .activity-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .activity-actions {
        width: 100%;
        justify-content: flex-end;
    }

    .stat-card {
        margin-bottom: 1rem;
    }

    .pagination-info {
        font-size: 0.8rem;
        text-align: center;
    }

    .card-footer .d-flex {
        flex-direction: column;
        gap: 1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
    }
}
</style>

@push('scripts')
<script>
// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    const activityItems = document.querySelectorAll('.activity-item');
    activityItems.forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            item.style.transition = 'opacity 0.3s, transform 0.3s';

            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateX(0)';
            }, 50);
        }, index * 50);
    });
});
</script>
@endpush
@endsection
