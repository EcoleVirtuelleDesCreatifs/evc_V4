@extends('layouts.admin')

@section('title', 'Profil CV - ' . $profile->first_name . ' ' . $profile->last_name)

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('admin.cvtheque.profiles') }}" class="btn btn-outline-secondary btn-sm mb-2">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-user-circle me-2"></i>Profil CV Complet
                    </h1>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print me-2"></i>Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations principales -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <!-- Carte profil -->
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    @if($profile->profile_photo)
                        <img src="{{ asset('storage/' . $profile->profile_photo) }}" 
                             alt="{{ $profile->first_name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 150px; height: 150px; font-size: 3rem;">
                            {{ strtoupper(substr($profile->first_name ?? 'U', 0, 1)) }}
                        </div>
                    @endif
                    
                    <h4 class="mb-1">{{ $profile->first_name }} {{ $profile->last_name }}</h4>
                    
                    @if($profile->professional_title)
                        <p class="text-muted mb-3">{{ $profile->professional_title }}</p>
                    @endif

                    @php
                        $badgeColor = match($profile->formation) {
                            'Design Graphique' => 'primary',
                            'Community Management' => 'info',
                            'Gestion Informatique' => 'warning',
                            'Intelligence Artificielle' => 'success',
                            default => 'secondary'
                        };
                    @endphp
                    <span class="badge badge-{{ $badgeColor }} mb-3">{{ $profile->formation }}</span>
                    
                    @if($profile->specialization)
                        <p class="text-muted small">{{ $profile->specialization }}</p>
                    @endif

                    <!-- Score de complétion -->
                    <div class="mt-3">
                        <h6 class="mb-2">Complétion du profil</h6>
                        @php
                            $score = $profile->profile_completion_score;
                            $progressColor = $score >= 80 ? 'success' : ($score >= 50 ? 'warning' : 'danger');
                        @endphp
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-{{ $progressColor }}" 
                                 role="progressbar" 
                                 style="width: {{ $score }}%;" 
                                 aria-valuenow="{{ $score }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <strong>{{ $score }}%</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coordonnées -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-address-card me-2"></i>Coordonnées
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Email</small>
                        <a href="mailto:{{ $profile->user_email }}">{{ $profile->user_email }}</a>
                    </div>
                    
                    @if($profile->phone)
                    <div class="mb-3">
                        <small class="text-muted d-block">Téléphone</small>
                        <a href="tel:{{ $profile->phone }}">{{ $profile->phone }}</a>
                    </div>
                    @endif

                    @if($profile->whatsapp)
                    <div class="mb-3">
                        <small class="text-muted d-block">WhatsApp</small>
                        <a href="https://wa.me/{{ $profile->whatsapp }}" target="_blank">{{ $profile->whatsapp }}</a>
                    </div>
                    @endif

                    @if($profile->country || $profile->city)
                    <div class="mb-3">
                        <small class="text-muted d-block">Localisation</small>
                        {{ $profile->city }}@if($profile->country), {{ $profile->country }}@endif
                    </div>
                    @endif

                    @if($profile->portfolio_url)
                    <div class="mb-3">
                        <small class="text-muted d-block">Portfolio URL</small>
                        <a href="{{ $profile->portfolio_url }}" target="_blank">{{ $profile->portfolio_url }}</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Résumé professionnel -->
            @if($profile->summary)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-quote-left me-2"></i>Résumé Professionnel
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $profile->summary }}</p>
                </div>
            </div>
            @endif

            <!-- Expérience et disponibilité -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-briefcase me-2"></i>Expérience & Disponibilité
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Années d'expérience</small>
                            <strong>{{ $profile->experience_years ?? 0 }} an(s)</strong>
                        </div>
                        @if($profile->availability)
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Disponibilité</small>
                            <strong class="text-success">{{ $profile->availability }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Compétences -->
            @if($profile->skills && is_array(json_decode($profile->skills, true)))
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools me-2"></i>Compétences
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(json_decode($profile->skills, true) as $skill)
                            <span class="badge badge-secondary badge-lg px-3 py-2">{{ $skill }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents téléchargés -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-folder-open me-2"></i>Documents & Fichiers
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- CV -->
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf fa-2x text-danger me-3"></i>
                                    <div>
                                        <strong>Curriculum Vitae</strong>
                                        @if($profile->cv_file_path)
                                            <br><small class="text-success">
                                                <i class="fas fa-check-circle"></i> Disponible
                                            </small>
                                        @else
                                            <br><small class="text-muted">Non téléchargé</small>
                                        @endif
                                    </div>
                                </div>
                                @if($profile->cv_file_path)
                                    <a href="{{ route('admin.cvtheque.download', ['id' => $profile->id, 'type' => 'cv']) }}" 
                                       class="btn btn-sm btn-outline-danger w-100">
                                        <i class="fas fa-download me-2"></i>Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Lettre de motivation -->
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-envelope fa-2x text-info me-3"></i>
                                    <div>
                                        <strong>Lettre de Motivation</strong>
                                        @if($profile->motivation_letter_path)
                                            <br><small class="text-success">
                                                <i class="fas fa-check-circle"></i> Disponible
                                            </small>
                                        @else
                                            <br><small class="text-muted">Non téléchargé</small>
                                        @endif
                                    </div>
                                </div>
                                @if($profile->motivation_letter_path)
                                    <a href="{{ route('admin.cvtheque.download', ['id' => $profile->id, 'type' => 'motivation']) }}" 
                                       class="btn btn-sm btn-outline-info w-100">
                                        <i class="fas fa-download me-2"></i>Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Portfolio -->
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-images fa-2x text-warning me-3"></i>
                                    <div>
                                        <strong>Portfolio / Réalisations</strong>
                                        @if($profile->portfolio_files && count($portfolioFiles) > 0)
                                            <br><small class="text-success">
                                                <i class="fas fa-check-circle"></i> {{ count($portfolioFiles) }} fichier(s)
                                            </small>
                                        @else
                                            <br><small class="text-muted">Non téléchargé</small>
                                        @endif
                                    </div>
                                </div>
                                @if($profile->portfolio_files && count($portfolioFiles) > 0)
                                    <div class="mt-2">
                                        @foreach($portfolioFiles as $file)
                                            <a href="{{ asset('storage/' . $file) }}" 
                                               target="_blank"
                                               class="btn btn-sm btn-outline-warning w-100 mb-1">
                                                <i class="fas fa-external-link-alt me-2"></i>Voir fichier {{ $loop->iteration }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Pressbook -->
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-book fa-2x text-success me-3"></i>
                                    <div>
                                        <strong>Pressbook</strong>
                                        @if($profile->pressbook_file_path)
                                            <br><small class="text-success">
                                                <i class="fas fa-check-circle"></i> Disponible
                                            </small>
                                        @else
                                            <br><small class="text-muted">Non téléchargé</small>
                                        @endif
                                    </div>
                                </div>
                                @if($profile->pressbook_file_path)
                                    <a href="{{ route('admin.cvtheque.download', ['id' => $profile->id, 'type' => 'pressbook']) }}" 
                                       class="btn btn-sm btn-outline-success w-100">
                                        <i class="fas fa-download me-2"></i>Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Rapport de fin de formation -->
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-alt fa-2x text-primary me-3"></i>
                                    <div>
                                        <strong>Rapport de Fin de Formation</strong>
                                        @if($profile->report_file_path)
                                            <br><small class="text-success">
                                                <i class="fas fa-check-circle"></i> Disponible
                                            </small>
                                        @else
                                            <br><small class="text-muted">Non téléchargé</small>
                                        @endif
                                    </div>
                                </div>
                                @if($profile->report_file_path)
                                    <a href="{{ route('admin.cvtheque.download', ['id' => $profile->id, 'type' => 'report']) }}" 
                                       class="btn btn-sm btn-outline-primary w-100">
                                        <i class="fas fa-download me-2"></i>Télécharger
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations académiques -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-graduation-cap me-2"></i>Informations Académiques
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($profile->education_level)
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Niveau d'études</small>
                            <strong>{{ $profile->education_level }}</strong>
                        </div>
                        @endif

                        @if($profile->last_diploma)
                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Dernier diplôme</small>
                            <strong>{{ $profile->last_diploma }}</strong>
                        </div>
                        @endif

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Statut étudiant</small>
                            @php
                                $statusBadge = match($profile->student_status) {
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                    'suspended' => 'warning',
                                    default => 'secondary'
                                };
                            @endphp
                            <span class="badge badge-{{ $statusBadge }}">
                                {{ ucfirst($profile->student_status ?? 'Inconnu') }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <small class="text-muted d-block">Date d'inscription</small>
                            <strong>{{ \Carbon\Carbon::parse($profile->created_at)->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .btn, .card-header, nav, .sidebar {
        display: none !important;
    }
}
.gap-2 {
    gap: 0.5rem;
}
.badge-lg {
    font-size: 0.9rem;
}
</style>
@endsection
