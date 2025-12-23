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

    <!-- Liste des activités par étudiant -->
    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-white border-0 py-3" style="border-radius: 16px 16px 0 0;">
            <h5 class="mb-0 d-flex align-items-center">
                <i class="fas fa-users me-2" style="color: #1e3c72;"></i>
                Étudiants avec activités
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
                <div class="students-list">
                    @foreach($studentsWithActivities as $student)
                        <div class="student-card" data-student-id="{{ $student->student_id }}">
                            <!-- Header de l'étudiant (cliquable) -->
                            <div class="student-header" onclick="toggleActivities({{ $student->student_id }})" style="cursor: pointer;">
                                <div class="student-info-left">
                                    @php
                                        $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);

                                        // Si c'est l'avatar par défaut, utiliser ui-avatars
                                        if (str_contains($photoUrl, 'default-avatar') || str_contains($photoUrl, 'avatar.png')) {
                                            $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode(($student->first_name ?? 'E') . ' ' . ($student->last_name ?? 'T')) . '&background=833AB4&color=fff&size=120';
                                        }
                                    @endphp

                                    <img src="{{ $photoUrl }}" alt="{{ $student->first_name }} {{ $student->last_name }}" class="student-avatar">

                                    <div>
                                        <h6 class="student-name mb-1">{{ $student->first_name }} {{ $student->last_name }}</h6>
                                        <div class="student-meta">
                                            <span class="badge-formation" style="background: {{
                                                $student->formation === 'Design Graphique' ? 'linear-gradient(135deg, #667eea, #764ba2)' :
                                                ($student->formation === 'Community Management' ? 'linear-gradient(135deg, #f093fb, #f5576c)' :
                                                'linear-gradient(135deg, #4facfe, #00f2fe)')
                                            }}">
                                                {{ $student->formation }}
                                            </span>
                                            <span class="text-muted ms-2">
                                                <i class="far fa-clock"></i>
                                                {{ \Carbon\Carbon::parse($student->last_activity)->locale('fr')->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="student-stats">
                                    <div class="stat-badge stat-total">
                                        <i class="fas fa-list"></i> {{ $student->total_activities }}
                                    </div>
                                    <div class="stat-badge stat-validated">
                                        <i class="fas fa-check-circle"></i> {{ $student->validated_count }}
                                    </div>
                                    <div class="stat-badge stat-pending">
                                        <i class="fas fa-clock"></i> {{ $student->pending_count }}
                                    </div>
                                    @if($student->rejected_count > 0)
                                        <div class="stat-badge stat-rejected">
                                            <i class="fas fa-times-circle"></i> {{ $student->rejected_count }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Indicateur de déroulement -->
                                <div class="toggle-icon" id="toggle-icon-{{ $student->student_id }}">
                                    <i class="fas fa-chevron-down"></i>
                                </div>
                            </div>

                            <!-- Liste des activités récentes de l'étudiant (cachée par défaut) -->
                            <div class="student-activities" id="activities-{{ $student->student_id }}" style="display: none;">
                                @foreach($student->recent_activities as $activity)
                                    <div class="activity-row">
                                        <div class="activity-icon status-{{ $activity->status }}">
                                            @if($activity->status === 'validated')
                                                <i class="fas fa-check"></i>
                                            @elseif($activity->status === 'rejected')
                                                <i class="fas fa-times"></i>
                                            @else
                                                <i class="fas fa-clock"></i>
                                            @endif
                                        </div>
                                        <div class="activity-details">
                                            <div class="activity-title">{{ $activity->title }}</div>
                                            <div class="activity-date">
                                                {{ \Carbon\Carbon::parse($activity->updated_at)->format('d/m/Y à H:i') }}
                                            </div>
                                        </div>
                                        <div class="activity-status">
                                            @if($activity->status === 'pending' || $activity->status === 'submitted')
                                                <span class="badge-status badge-pending">En attente</span>
                                            @elseif($activity->status === 'validated')
                                                <span class="badge-status badge-validated">Validé</span>
                                            @else
                                                <span class="badge-status badge-rejected">Rejeté</span>
                                            @endif
                                        </div>
                                        <a href="{{ route('admin.tp.view', $activity->id) }}" class="btn-view" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($studentsWithActivities->hasPages())
            <div class="card-footer bg-white border-0 py-3" style="border-radius: 0 0 16px 16px;">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="pagination-info text-muted mb-2 mb-md-0">
                        Affichage de <strong>{{ $studentsWithActivities->firstItem() }}</strong> à <strong>{{ $studentsWithActivities->lastItem() }}</strong> sur <strong>{{ $studentsWithActivities->total() }}</strong> étudiants
                    </div>
                    <div>
                        {{ $studentsWithActivities->links('pagination::bootstrap-4') }}
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

/* Liste des étudiants */
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

/* Header de l'étudiant */
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

/* Stats badges */
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

.stat-badge.stat-total {
    background-color: #e3f2fd;
    color: #1976d2;
}

.stat-badge.stat-validated {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.stat-badge.stat-pending {
    background-color: #fff3cd;
    color: #856404;
}

.stat-badge.stat-rejected {
    background-color: #f8d7da;
    color: #721c24;
}

/* Toggle icon */
.toggle-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e3f2fd;
    color: #1976d2;
    border-radius: 50%;
    transition: all 0.3s;
    font-size: 0.9rem;
}

.toggle-icon.rotated {
    transform: rotate(180deg);
    background-color: #1976d2;
    color: white;
}

.student-header:hover .toggle-icon {
    background-color: #1976d2;
    color: white;
}

/* Activités de l'étudiant */
.student-activities {
    background-color: #f8f9fa;
    padding: 1rem 1.5rem;
    transition: opacity 0.3s ease;
    overflow: hidden;
}

.activity-row {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    margin-bottom: 0.5rem;
    transition: all 0.2s;
}

.activity-row:last-child {
    margin-bottom: 0;
}

.activity-row:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateX(5px);
}

.activity-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    flex-shrink: 0;
}

.activity-icon.status-validated {
    background-color: #e8f5e9;
    color: #2e7d32;
}

.activity-icon.status-pending,
.activity-icon.status-submitted {
    background-color: #fff3cd;
    color: #856404;
}

.activity-icon.status-rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.activity-details {
    flex-grow: 1;
}

.activity-title {
    font-weight: 500;
    color: #2d3748;
    margin-bottom: 0.1rem;
}

.activity-date {
    font-size: 0.8rem;
    color: #718096;
}

.badge-status {
    padding: 0.25rem 0.65rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 500;
}

.badge-status.badge-pending {
    background-color: #fff3cd;
    color: #856404;
}

.badge-status.badge-validated {
    background-color: #d4edda;
    color: #155724;
}

.badge-status.badge-rejected {
    background-color: #f8d7da;
    color: #721c24;
}

.btn-view {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e3f2fd;
    color: #1976d2;
    border: none;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-view:hover {
    background-color: #1976d2;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(25, 118, 210, 0.3);
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

    .activity-row {
        flex-wrap: wrap;
    }
}
</style>

@push('scripts')
<script>
// Fonction pour afficher/masquer les activités d'un étudiant
function toggleActivities(studentId) {
    const activitiesDiv = document.getElementById('activities-' + studentId);
    const toggleIcon = document.getElementById('toggle-icon-' + studentId);

    if (activitiesDiv.style.display === 'none') {
        // Afficher les activités
        activitiesDiv.style.display = 'block';
        toggleIcon.classList.add('rotated');

        // Animation d'apparition
        setTimeout(() => {
            activitiesDiv.style.opacity = '0';
            activitiesDiv.style.transition = 'opacity 0.3s';
            setTimeout(() => {
                activitiesDiv.style.opacity = '1';
            }, 10);
        }, 10);
    } else {
        // Masquer les activités
        activitiesDiv.style.opacity = '0';
        setTimeout(() => {
            activitiesDiv.style.display = 'none';
            toggleIcon.classList.remove('rotated');
        }, 300);
    }
}

// Animation au chargement
document.addEventListener('DOMContentLoaded', function() {
    const studentCards = document.querySelectorAll('.student-card');
    studentCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.3s, transform 0.3s';

            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });
});
</script>
@endpush
@endsection
