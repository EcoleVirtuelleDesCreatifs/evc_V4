@extends('layouts.admin')

@section('title', 'Dernières Connexions Étudiants')

@section('content')
<div class="container-fluid py-4">

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['total'] ?? 0 }}</h3>
                    <p>Étudiants (au moins 1 connexion)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #ff9800 0%, #fb8c00 100%);">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['last_24h'] ?? 0 }}</h3>
                    <p>Connexions (24h)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);">
                <div class="stat-icon">
                    <i class="fas fa-calendar-week"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['last_7d'] ?? 0 }}</h3>
                    <p>Connexions (7 jours)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card" style="background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $stats['this_month'] ?? 0 }}</h3>
                    <p>Connexions (ce mois)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-white border-0 py-3" style="border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="fas fa-user-clock me-2" style="color: #1e3c72;"></i>
                Dernières Connexions Étudiants
            </h5>
        </div>
        <div class="card-body p-0">
            @if($connections->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune connexion récente</h5>
                    <p class="text-muted">Les connexions des étudiants apparaîtront ici</p>
                </div>
            @else
                <div class="students-list">
                    @foreach($connections as $c)
                        @php
                            $fullName = trim(($c->first_name ?? '') . ' ' . ($c->last_name ?? ''));
                            $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrl($c->profile_photo ?? null);
                            $formation = $c->program ?? 'Non assigné';
                        @endphp

                        <div class="student-card">
                            <div class="student-header">
                                <div class="student-info-left">
                                    @if($photoUrl)
                                        <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="student-avatar">
                                    @else
                                        <div class="student-avatar" style="display: flex; align-items: center; justify-content: center; background: #0f172a; color: white; font-weight: 800;">
                                            {{ strtoupper(substr($fullName ?: 'E', 0, 1)) }}
                                        </div>
                                    @endif

                                    <div>
                                        <h6 class="student-name mb-1">{{ $fullName ?: 'Étudiant' }}</h6>
                                        <div class="student-meta">
                                            <span class="badge-formation" style="background: {{
                                                str_contains(strtolower($formation), 'design') ? 'linear-gradient(135deg, #667eea, #764ba2)' :
                                                (str_contains(strtolower($formation), 'community') ? 'linear-gradient(135deg, #f093fb, #f5576c)' :
                                                'linear-gradient(135deg, #4facfe, #00f2fe)')
                                            }}">
                                                {{ $formation }}
                                            </span>
                                            <span class="text-muted ms-2">
                                                <i class="fas fa-id-badge"></i>
                                                {{ $c->user_id }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="student-stats">
                                    <div class="stat-badge stat-login">
                                        <i class="fas fa-sign-in-alt"></i>
                                        {{ !empty($c->last_login) ? \Carbon\Carbon::parse($c->last_login)->locale('fr')->diffForHumans() : '—' }}
                                    </div>
                                    <div class="stat-badge stat-activity">
                                        <i class="fas fa-bolt"></i>
                                        {{ !empty($c->last_activity) ? \Carbon\Carbon::parse($c->last_activity)->locale('fr')->diffForHumans() : '—' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if($connections->hasPages())
            <div class="card-footer bg-white border-0 py-3" style="border-radius: 0 0 16px 16px;">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="pagination-info text-muted mb-2 mb-md-0">
                        Affichage de <strong>{{ $connections->firstItem() }}</strong> à <strong>{{ $connections->lastItem() }}</strong> sur <strong>{{ $connections->total() }}</strong> connexions
                    </div>
                    <div>
                        {{ $connections->withQueryString()->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        @endif
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

.students-list {
    display: flex;
    flex-direction: column;
}

.student-card {
    border-bottom: 1px solid #e9ecef;
    transition: background-color 0.2s;
}

.student-card:last-child {
    border-bottom: none;
}

.student-card:hover {
    background-color: #f8f9fa;
}

.student-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.25rem 1.5rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.student-info-left {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.student-avatar {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #fff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.student-name {
    font-weight: 600;
    font-size: 1.1rem;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.student-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.badge-formation {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    color: white;
    font-size: 0.8rem;
    font-weight: 500;
}

.student-stats {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.stat-badge {
    padding: 0.4rem 0.75rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
}

.stat-badge.stat-login {
    background-color: #e3f2fd;
    color: #1976d2;
}

.stat-badge.stat-activity {
    background-color: #e9ecef;
    color: #495057;
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

@media (max-width: 768px) {
    .student-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .student-stats {
        width: 100%;
        justify-content: flex-start;
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
@endsection
