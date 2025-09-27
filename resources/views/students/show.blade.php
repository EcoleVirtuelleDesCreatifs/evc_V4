@extends('layouts.ki-admin')

@section('title', 'Profil de ' . $student->full_name . ' - EVC 2024')
@section('page-title', 'Profil Étudiant')

@section('content')
<div class="row">
    <!-- Profile Header -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Profil de {{ $student->full_name }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-light">
                            <i class="fas fa-edit me-2"></i>
                            Modifier
                        </a>
                        <a href="{{ route('students.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-arrow-left me-2"></i>
                            Retour
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ $student->profile_photo_url }}" 
                             alt="{{ $student->full_name }}" 
                             class="profile-img mb-3">
                        <h4 class="mb-1">{{ $student->full_name }}</h4>
                        <p class="text-muted mb-2">{{ $student->email }}</p>
                        <span class="status-badge status-{{ $student->status }} mb-3 d-inline-block">
                            {{ ucfirst($student->status) }}
                        </span>
                        <div class="mt-3">
                            <span class="badge fs-6 px-3 py-2" style="background-color: var(--primary-color); color: white;">
                                ID: {{ $student->student_id }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-phone me-2"></i>Téléphone
                                    </label>
                                    <p class="mb-0">{{ $student->phone ?? 'Non renseigné' }}</p>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-calendar me-2"></i>Date de naissance
                                    </label>
                                    <p class="mb-0">
                                        {{ $student->date_of_birth ? $student->date_of_birth->format('d/m/Y') : 'Non renseignée' }}
                                    </p>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-venus-mars me-2"></i>Genre
                                    </label>
                                    <p class="mb-0">{{ $student->gender ? ucfirst($student->gender) : 'Non renseigné' }}</p>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-book me-2"></i>Programme
                                    </label>
                                    <p class="mb-0">{{ $student->program ?? 'Non défini' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-layer-group me-2"></i>Niveau
                                    </label>
                                    <p class="mb-0">
                                        @if($student->level)
                                            <span class="badge" style="background-color: var(--secondary-color); color: white;">{{ $student->level }}</span>
                                        @else
                                            Non défini
                                        @endif
                                    </p>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-graduation-cap me-2"></i>Spécialisation
                                    </label>
                                    <p class="mb-0">{{ $student->specialization ?? 'Non définie' }}</p>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-chart-line me-2"></i>GPA
                                    </label>
                                    <p class="mb-0">
                                        @if($student->gpa)
                                            <strong class="text-success fs-5">{{ number_format($student->gpa, 2) }}</strong>
                                            <small class="text-muted">/4.00</small>
                                        @else
                                            Non calculé
                                        @endif
                                    </p>
                                </div>
                                <div class="info-item mb-3">
                                    <label class="form-label text-muted">
                                        <i class="fas fa-coins me-2"></i>Crédits obtenus
                                    </label>
                                    <p class="mb-0">
                                        <strong style="color: var(--primary-color);">{{ $student->credits_earned }}</strong> crédits
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Contact Information -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-address-card me-2"></i>
                    Informations de Contact
                </h5>
            </div>
            <div class="card-body">
                <div class="info-item mb-3">
                    <label class="form-label text-muted">
                        <i class="fas fa-envelope me-2"></i>Email
                    </label>
                    <p class="mb-0">
                        <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                            {{ $student->email }}
                        </a>
                    </p>
                </div>
                <div class="info-item mb-3">
                    <label class="form-label text-muted">
                        <i class="fas fa-map-marker-alt me-2"></i>Adresse
                    </label>
                    <p class="mb-0">{{ $student->address ?? 'Non renseignée' }}</p>
                </div>
                <div class="info-item mb-3">
                    <label class="form-label text-muted">
                        <i class="fas fa-city me-2"></i>Ville
                    </label>
                    <p class="mb-0">{{ $student->city ?? 'Non renseignée' }}</p>
                </div>
                <div class="info-item mb-0">
                    <label class="form-label text-muted">
                        <i class="fas fa-flag me-2"></i>Pays
                    </label>
                    <p class="mb-0">{{ $student->country }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Progress -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Progression Académique
                </h5>
            </div>
            <div class="card-body">
                <!-- GPA Progress -->
                <div class="mb-4">
                    <label class="form-label">GPA Actuel</label>
                    <div class="progress mb-2" style="height: 20px;">
                        @php
                            $gpaPercentage = $student->gpa ? ($student->gpa / 4) * 100 : 0;
                        @endphp
                        <div class="progress-bar" 
                             role="progressbar" 
                             style="width: {{ $gpaPercentage }}%; background-color: var(--success-color);">
                            {{ $student->gpa ? number_format($student->gpa, 2) : '0.00' }}
                        </div>
                    </div>
                    <small class="text-muted">Sur 4.00</small>
                </div>

                <!-- Credits Progress -->
                <div class="mb-4">
                    <label class="form-label">Crédits Obtenus</label>
                    <div class="progress mb-2" style="height: 20px;">
                        @php
                            $creditsPercentage = ($student->credits_earned / 180) * 100; // Assuming 180 total credits
                        @endphp
                        <div class="progress-bar" 
                             role="progressbar" 
                             style="width: {{ min($creditsPercentage, 100) }}%; background-color: var(--primary-color);">
                            {{ $student->credits_earned }}
                        </div>
                    </div>
                    <small class="text-muted">{{ $student->credits_earned }}/180 crédits</small>
                </div>

                <!-- Status Information -->
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h4 class="mb-1" style="color: var(--primary-color);">{{ $student->level ?? 'N/A' }}</h4>
                            <small class="text-muted">Niveau</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h4 class="mb-1" style="color: var(--success-color);">
                            {{ $student->created_at->diffForHumans() }}
                        </h4>
                        <small class="text-muted">Inscrit</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Historique
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Inscription</h6>
                            <p class="text-muted mb-0">
                                Étudiant inscrit le {{ $student->created_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                    @if($student->updated_at != $student->created_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Dernière mise à jour</h6>
                            <p class="text-muted mb-0">
                                Profil mis à jour le {{ $student->updated_at->format('d/m/Y à H:i') }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .info-item label {
        font-weight: 600;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .info-item p {
        font-size: 1rem;
        color: #495057;
    }

    .timeline {
        position: relative;
        padding-left: 30px;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #dee2e6;
    }

    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }

    .timeline-marker {
        position: absolute;
        left: -22px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 3px solid #fff;
        box-shadow: 0 0 0 2px #dee2e6;
    }

    .timeline-content {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 3px solid var(--primary-color);
    }

    .progress {
        border-radius: 10px;
    }

    .progress-bar {
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>
@endsection
