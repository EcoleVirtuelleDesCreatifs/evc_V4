@extends('layouts.admin')

@section('title', 'CVthèque - Tous les Profils')

@push('styles')
<style>
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
    
    .stat-card-danger {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .stat-card-cyan {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    }

    .stat-card-purple {
        background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);
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

    .profile-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #4fc3f7;
    }

    .profile-avatar-placeholder {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1.2rem;
    }

    .formation-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-design {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
    }

    .badge-cm {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        color: white;
    }

    .badge-gi {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .badge-ia {
        background: linear-gradient(135deg, #26c6da, #00acc1);
        color: white;
    }

    .progress-custom {
        height: 25px;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        overflow: hidden;
    }

    .progress-bar-custom {
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        transition: width 0.6s ease;
    }

    .document-icons {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .document-icons i {
        font-size: 1.2rem;
    }

    .btn-view {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .btn-export {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .table-dark {
        background-color: #1e293b !important;
    }

    .table-dark th {
        background-color: #0f172a !important;
        color: #94a3b8 !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 1rem !important;
        border: none !important;
    }

    .table-dark td {
        padding: 1rem !important;
        vertical-align: middle;
        border-color: #334155 !important;
        color: #e2e8f0 !important;
    }

    .table-dark tbody tr {
        transition: all 0.3s ease;
    }

    .table-dark tbody tr:hover {
        background-color: #334155 !important;
        transform: scale(1.01);
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #1e293b;
        border-radius: 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #475569;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: #e2e8f0;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-briefcase me-2"></i>CVthèque - Profils Étudiants
        </h1>
        <a href="{{ route('admin.cvtheque.export') }}" class="btn-export">
            <i class="fas fa-file-excel me-2"></i>Exporter CSV
        </a>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Profils</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['complete'] }}</h3>
                    <p class="stat-label">Profils Complets</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['with_cv'] }}</h3>
                    <p class="stat-label">Avec CV</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-images"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['with_portfolio'] }}</h3>
                    <p class="stat-label">Avec Portfolio</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par formation -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $formationStats['design_graphique'] }}</h3>
                    <p class="stat-label">Design Graphique</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $formationStats['community_management'] }}</h3>
                    <p class="stat-label">Community Management</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $formationStats['gestion_informatique'] }}</h3>
                    <p class="stat-label">Gestion Informatique</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-cyan">
                <div class="stat-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $formationStats['intelligence_artificielle'] }}</h3>
                    <p class="stat-label">Intelligence Artificielle</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques documents -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-danger" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="stat-icon" style="font-size: 1.5rem;">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" style="font-size: 2rem;">{{ $stats['with_cv'] }}</h3>
                    <p class="stat-label" style="font-size: 0.85rem;">CV</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon" style="font-size: 1.5rem;">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" style="font-size: 2rem;">{{ $stats['with_motivation'] }}</h3>
                    <p class="stat-label" style="font-size: 0.85rem;">Lettres</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon" style="font-size: 1.5rem;">
                    <i class="fas fa-images"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" style="font-size: 2rem;">{{ $stats['with_portfolio'] }}</h3>
                    <p class="stat-label" style="font-size: 0.85rem;">Portfolios</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon" style="font-size: 1.5rem;">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" style="font-size: 2rem;">{{ $stats['with_pressbook'] }}</h3>
                    <p class="stat-label" style="font-size: 0.85rem;">Pressbooks</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="stat-card stat-card-purple">
                <div class="stat-icon" style="font-size: 1.5rem;">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number" style="font-size: 2rem;">{{ $stats['with_report'] }}</h3>
                    <p class="stat-label" style="font-size: 0.85rem;">Rapports</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des profils -->
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155; border-radius: 16px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th style="width: 8%;">Photo</th>
                            <th style="width: 20%;">Étudiant</th>
                            <th style="width: 15%;">Formation</th>
                            <th style="width: 18%;">Titre Professionnel</th>
                            <th style="width: 10%;">Expérience</th>
                            <th style="width: 12%;">Complétion</th>
                            <th style="width: 10%;">Documents</th>
                            <th style="width: 7%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profiles as $profile)
                        <tr>
                            <td class="text-center">
                                @if($profile->profile_photo)
                                    <img src="{{ asset('storage/' . $profile->profile_photo) }}" 
                                         alt="{{ $profile->first_name }}" 
                                         class="profile-avatar">
                                @else
                                    <div class="profile-avatar-placeholder mx-auto">
                                        {{ strtoupper(substr($profile->first_name ?? 'U', 0, 1)) }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight: 600; color: #e2e8f0;">{{ $profile->first_name }} {{ $profile->last_name }}</div>
                                <small style="color: #94a3b8;">{{ $profile->user_email }}</small><br>
                                <small style="color: #94a3b8;">{{ $profile->phone }}</small>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($profile->formation) {
                                        'Design Graphique' => 'badge-design',
                                        'Community Management' => 'badge-cm',
                                        'Gestion Informatique' => 'badge-gi',
                                        'Intelligence Artificielle' => 'badge-ia',
                                        default => 'badge-design'
                                    };
                                @endphp
                                <span class="formation-badge {{ $badgeClass }}">{{ $profile->formation }}</span>
                                @if($profile->specialization)
                                    <br><small style="color: #94a3b8; font-size: 0.8rem;">{{ $profile->specialization }}</small>
                                @endif
                            </td>
                            <td>
                                @if($profile->professional_title)
                                    <div style="font-weight: 500; color: #e2e8f0;">{{ $profile->professional_title }}</div>
                                @else
                                    <span style="color: #64748b;">Non renseigné</span>
                                @endif
                                @if($profile->availability)
                                    <small style="color: #10b981;">
                                        <i class="fas fa-calendar-check"></i> {{ $profile->availability }}
                                    </small>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($profile->experience_years)
                                    <span class="formation-badge" style="background: rgba(148, 163, 184, 0.2); color: #e2e8f0;">
                                        {{ $profile->experience_years }} an(s)
                                    </span>
                                @else
                                    <span style="color: #64748b;">-</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $score = $profile->profile_completion_score;
                                    $progressColor = $score >= 80 ? '#10b981' : ($score >= 50 ? '#f59e0b' : '#ef4444');
                                @endphp
                                <div class="progress-custom">
                                    <div class="progress-bar-custom" 
                                         style="width: {{ $score }}%; background: linear-gradient(90deg, {{ $progressColor }}, {{ $progressColor }}dd);">
                                        {{ $score }}%
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="document-icons justify-content-center">
                                    @if($profile->cv_file_path)
                                        <i class="fas fa-file-pdf" style="color: #ef4444;" title="CV"></i>
                                    @endif
                                    @if($profile->motivation_letter_path)
                                        <i class="fas fa-envelope" style="color: #4fc3f7;" title="Lettre de motivation"></i>
                                    @endif
                                    @if($profile->portfolio_files)
                                        <i class="fas fa-images" style="color: #f59e0b;" title="Portfolio"></i>
                                    @endif
                                    @if($profile->pressbook_file_path)
                                        <i class="fas fa-book" style="color: #10b981;" title="Pressbook"></i>
                                    @endif
                                    @if($profile->report_file_path)
                                        <i class="fas fa-file-alt" style="color: #9c27b0;" title="Rapport"></i>
                                    @endif
                                    @if(!$profile->cv_file_path && !$profile->motivation_letter_path && !$profile->portfolio_files && !$profile->pressbook_file_path && !$profile->report_file_path)
                                        <span style="color: #64748b;">Aucun</span>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.cvtheque.show', $profile->id) }}" 
                                   class="btn-view btn-sm" 
                                   title="Voir le profil complet">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <h3>Aucun profil CV disponible</h3>
                                    <p>Les profils CV des étudiants apparaîtront ici</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#dataTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        },
        "order": [[1, "asc"]],
        "pageLength": 25,
        "responsive": true,
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip'
    });
});
</script>
@endpush
@endsection
