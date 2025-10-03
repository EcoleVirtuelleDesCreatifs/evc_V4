{{--
    Vue Dashboard Design Graphique - Laravel Blade

    Cette vue affiche l'espace étudiant personnalisé pour la formation Design Graphique.
    Elle utilise uniquement les directives Blade Laravel sans PHP natif.

    Variables attendues du contrôleur :
    - $userProfile : Informations de l'utilisateur connecté
    - $userStats : Statistiques de progression de l'utilisateur
    - $tpStats : Statistiques des travaux pratiques
    - $validationStats : Statistiques de validation des TP
    - $recentTPs : Liste des derniers TP ajoutés
    - $stats : Statistiques générales
--}}

@extends('layouts.ki-admin')

@section('title', 'Espace Étudiant - Design Graphique')

@section('content')
<div class="container-fluid">

    {{-- Définition des variables du profil --}}
    @php
        $sf = optional($student ?? null);
        $pr = optional($preReg ?? null);
        $userObj = isset($user) ? $user : (auth()->check() ? auth()->user() : null);

        // Photo
        $studentPhoto = $sf->profile_photo;
        $prePhoto = $pr->profile_photo ?? $pr->photo ?? $pr->image ?? $pr->image_url ?? $pr->avatar;
        $rawPhoto = $studentPhoto ?: $prePhoto;
        
        // Générer l'URL correcte de la photo
        if ($rawPhoto) {
            // Si c'est une URL complète (http/https)
            if (preg_match('/^https?:\/\//', $rawPhoto)) {
                $photoUrl = $rawPhoto;
            }
            // Si le chemin commence par 'photos_preregistrations/', ajouter 'storage/'
            elseif (str_starts_with($rawPhoto, 'photos_preregistrations/')) {
                $photoUrl = asset('storage/' . $rawPhoto);
            }
            // Si le chemin commence par 'uploads/', c'est déjà dans public/
            elseif (str_starts_with($rawPhoto, 'uploads/')) {
                $photoUrl = asset($rawPhoto);
            }
            // Autres cas : supposer que c'est dans storage
            else {
                $photoUrl = asset('storage/' . $rawPhoto);
            }
        } else {
            $photoUrl = asset('assets/img/avatar.png');
        }

        // Nom
        $fullName = trim(($sf->first_name ?? '') . ' ' . ($sf->last_name ?? ''));
        if ($fullName === '') {
            $fullName = ($userObj->name ?? '') ?: trim(($pr->first_name ?? '') . ' ' . ($pr->last_name ?? ''));
        }

        // Contact
        $email = ($sf->email ?? '') ?: (($userObj->email ?? '') ?: ($pr->email ?? ''));
        $phone = ($sf->phone ?? '') ?: ($pr->phone ?? '');
        $whatsapp = ($sf->whatsapp ?? '') ?: ($pr->whatsapp ?? '');
        $quartier = ($sf->quartier ?? '') ?: ($pr->quartier ?? '');
        $city = ($sf->city ?? '') ?: ($pr->city ?? '');
        $country = ($sf->country ?? '') ?: ($pr->country ?? '');

        // Formation
        $program = ($sf->program ?? '') ?: ($pr->program ?? '');
        $level = ($sf->level ?? '') ?: ($pr->level ?? '');
        $domain = ($sf->specialization ?? '') ?: ($pr->specialization ?? '');
        $status = $sf->status ?? '';
        $gender = ($sf->gender ?? '') ?: ($pr->gender ?? '');

        // Autres
        $studentId = $sf->student_id ?? '';
        $dob = $sf->date_of_birth ?? null;
        $age = $dob ? optional($dob)->age : null;
        $experience = $sf->years_experience ?? null;
        $sector = $sf->industry_sector ?? '';
        $gpa = $sf->gpa ?? null;
        $credits = $sf->credits_earned ?? null;
    @endphp

    {{-- Profil Étudiant Professionnel --}}
    
    {{-- Cover Header with Avatar --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg overflow-hidden">
                <div class="position-relative" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e8ba3 100%); height: 200px;">
                    <div class="position-absolute bottom-0 start-0 w-100 p-4">
                        <div class="row align-items-end">
                            <div class="col-auto">
                                <img src="{{ $photoUrl }}" alt="{{ $fullName }}" 
                                     class="rounded-circle border border-4 border-white shadow-lg"
                                     style="width: 150px; height: 150px; object-fit: cover; margin-bottom: -50px;">
                            </div>
                            <div class="col text-white pb-3">
                                <h2 class="fw-bold mb-1">{{ $fullName ?: 'Étudiant EVC' }}</h2>
                                <p class="mb-2 opacity-90">
                                    <i class="fas fa-graduation-cap me-2"></i>{{ $program ?: 'Design Graphique' }}
                                    @if($level)
                                        • {{ $level }}
                                    @endif
                                </p>
                                <div class="d-flex gap-2 flex-wrap">
                                    @if($level)
                                        <span class="badge bg-white bg-opacity-25 text-white"><i class="fas fa-layer-group me-1"></i>{{ $level }}</span>
                                    @endif
                                    @if($domain)
                                        <span class="badge bg-white bg-opacity-25 text-white"><i class="fas fa-shapes me-1"></i>{{ $domain }}</span>
                                    @endif
                                    @if($status)
                                        <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>{{ $status }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto text-end text-white pb-3">
                                <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-light">
                                    <i class="fas fa-edit me-1"></i> Modifier le profil
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding-top: 70px;">
                    {{-- Quick Contact Info --}}
                    <div class="row g-3 mb-4">
                        @if($email)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-envelope text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-muted">Email</div>
                                    <a href="mailto:{{ $email }}" class="text-decoration-none fw-semibold">{{ $email }}</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($phone)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-phone text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-muted">Téléphone</div>
                                    <a href="tel:{{ $phone }}" class="text-decoration-none fw-semibold">{{ $phone }}</a>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($whatsapp)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar avatar-sm bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fab fa-whatsapp text-success"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-muted">WhatsApp</div>
                                    <a href="https://wa.me/{{ preg_replace('/\D+/', '', $whatsapp) }}" target="_blank" class="text-decoration-none fw-semibold">{{ $whatsapp }}</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 fade-in">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary bg-opacity-10 rounded-3">
                                <i class="fas fa-map-marker-alt text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Quartier</div>
                            <h3 class="mb-0" style="font-size: 1.25rem;">{{ $quartier ?: '—' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 fade-in">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-success bg-opacity-10 rounded-3">
                                <i class="fas fa-city text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Ville</div>
                            <h3 class="mb-0" style="font-size: 1.25rem;">{{ $city ?: '—' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 fade-in">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 rounded-3">
                                <i class="fas fa-globe text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Pays</div>
                            <h3 class="mb-0" style="font-size: 1.25rem;">{{ $country ?: '—' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 fade-in">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-info bg-opacity-10 rounded-3">
                                <i class="fas fa-calendar text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="text-muted small">Âge</div>
                            <h3 class="mb-0">{{ $age ? $age.' ans' : 'N/A' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {{-- Tabs Navigation --}}
                    <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab">
                                <i class="fas fa-user-circle me-2"></i>Vue d'ensemble
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#informations" type="button" role="tab">
                                <i class="fas fa-id-card me-2"></i>Informations personnelles
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="formation-tab" data-bs-toggle="tab" data-bs-target="#formation" type="button" role="tab">
                                <i class="fas fa-graduation-cap me-2"></i>Formation
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="docs-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">
                                <i class="fas fa-folder-open me-2"></i>Documents
                            </button>
                        </li>
                    </ul>
                    
                    {{-- Tabs Content --}}
                    <div class="tab-content" id="profileTabsContent">
                        
                        {{-- Tab: Vue d'ensemble --}}
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row g-4">
                                {{-- Quick Stats Summary --}}
                                <div class="col-12">
                                    <div class="alert alert-info border-0">
                                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Bienvenue {{ $fullName }}</h5>
                                        <p class="mb-0">Voici un résumé rapide de votre profil étudiant. Utilisez les onglets ci-dessus pour explorer vos informations détaillées.</p>
                                    </div>
                                </div>

                                {{-- Profile Completion Progress --}}
                                <div class="col-12">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="mb-3"><i class="fas fa-tasks me-2"></i>Complétion du profil</h6>
                                            @php
                                                $completedFields = 0;
                                                $totalFields = 15;
                                                if($fullName) $completedFields++;
                                                if($email) $completedFields++;
                                                if($phone) $completedFields++;
                                                if($whatsapp) $completedFields++;
                                                if($gender) $completedFields++;
                                                if($dob) $completedFields++;
                                                if($quartier) $completedFields++;
                                                if($city) $completedFields++;
                                                if($country) $completedFields++;
                                                if($program) $completedFields++;
                                                if($level) $completedFields++;
                                                if($domain) $completedFields++;
                                                if($studentId) $completedFields++;
                                                if($photoUrl && $photoUrl != asset('assets/img/avatar.png')) $completedFields++;
                                                if($status) $completedFields++;
                                                $percentage = round(($completedFields / $totalFields) * 100);
                                            @endphp
                                            <div class="progress" style="height: 25px;">
                                                <div class="progress-bar bg-{{ $percentage >= 80 ? 'success' : ($percentage >= 50 ? 'warning' : 'danger') }}" role="progressbar" style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ $percentage }}% complet
                                                </div>
                                            </div>
                                            <p class="small text-muted mt-2 mb-0">{{ $completedFields }} champs sur {{ $totalFields }} renseignés</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Actions --}}
                                <div class="col-12">
                                    <h6 class="mb-3"><i class="fas fa-bolt me-2"></i>Actions rapides</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-outline-primary w-100 p-3">
                                                <i class="fas fa-user-edit d-block fs-3 mb-2"></i>
                                                <strong>Modifier mon profil</strong>
                                                <div class="small text-muted">Mettre à jour mes informations</div>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('design-graphique.documents.index') }}" class="btn btn-outline-secondary w-100 p-3">
                                                <i class="fas fa-folder-open d-block fs-3 mb-2"></i>
                                                <strong>Mes documents</strong>
                                                <div class="small text-muted">CV, lettres, réalisations</div>
                                            </a>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('design-graphique.parametres.index') }}" class="btn btn-outline-dark w-100 p-3">
                                                <i class="fas fa-cog d-block fs-3 mb-2"></i>
                                                <strong>Paramètres</strong>
                                                <div class="small text-muted">Configuration du compte</div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab: Informations personnelles --}}
                        <div class="tab-pane fade" id="informations" role="tabpanel">
                            <div class="row g-4">
                                {{-- Identité --}}
                                <div class="col-lg-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="mb-3 pb-2 border-bottom"><i class="fas fa-id-card me-2 text-primary"></i>Identité</h6>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Nom complet</label>
                                                    <strong>{{ $fullName ?: '—' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Sexe</label>
                                                    <strong>{{ $gender ?: '—' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Date de naissance</label>
                                                    <strong>{{ $dob ? $dob->format('d/m/Y') : '—' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Âge</label>
                                                    <strong>{{ $age ? $age.' ans' : '—' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Matricule</label>
                                                    <strong>{{ $studentId ?: '—' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Statut</label>
                                                    <strong>
                                                        @if($status)
                                                            <span class="badge bg-success">{{ $status }}</span>
                                                        @else
                                                            —
                                                        @endif
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Contact --}}
                                <div class="col-lg-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="mb-3 pb-2 border-bottom"><i class="fas fa-phone me-2 text-success"></i>Contact</h6>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="small text-muted d-block mb-1">Email</label>
                                                    @if($email)
                                                        <a href="mailto:{{ $email }}" class="text-decoration-none">
                                                            <i class="fas fa-envelope me-2"></i><strong>{{ $email }}</strong>
                                                        </a>
                                                    @else
                                                        <strong>—</strong>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Téléphone</label>
                                                    @if($phone)
                                                        <a href="tel:{{ $phone }}" class="text-decoration-none">
                                                            <i class="fas fa-phone me-2"></i><strong>{{ $phone }}</strong>
                                                        </a>
                                                    @else
                                                        <strong>—</strong>
                                                    @endif
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">WhatsApp</label>
                                                    @if($whatsapp)
                                                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $whatsapp) }}" target="_blank" class="text-decoration-none">
                                                            <i class="fab fa-whatsapp me-2"></i><strong>{{ $whatsapp }}</strong>
                                                        </a>
                                                    @else
                                                        <strong>—</strong>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Localisation --}}
                                <div class="col-lg-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="mb-3 pb-2 border-bottom"><i class="fas fa-map-marker-alt me-2 text-danger"></i>Localisation</h6>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Quartier</label>
                                                    <strong>{{ $quartier ?: '—' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Ville</label>
                                                    <strong>{{ $city ?: '—' }}</strong>
                                                </div>
                                                <div class="col-12">
                                                    <label class="small text-muted d-block mb-1">Pays</label>
                                                    <strong>{{ $country ?: '—' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Expérience professionnelle --}}
                                <div class="col-lg-6">
                                    <div class="card border-0 bg-light h-100">
                                        <div class="card-body">
                                            <h6 class="mb-3 pb-2 border-bottom"><i class="fas fa-briefcase me-2 text-warning"></i>Expérience</h6>
                                            <div class="row g-3">
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Années d'expérience</label>
                                                    <strong>{{ $experience !== null ? $experience.' an'.($experience > 1 ? 's' : '') : '—' }}</strong>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="small text-muted d-block mb-1">Secteur d'activité</label>
                                                    <strong>{{ $sector ?: '—' }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab: Formation --}}
                        <div class="tab-pane fade" id="formation" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="mb-4 pb-2 border-bottom"><i class="fas fa-graduation-cap me-2 text-primary"></i>Cursus académique</h6>
                                            <div class="row g-4">
                                                <div class="col-md-4">
                                                    <label class="small text-muted d-block mb-2">Programme</label>
                                                    <h5 class="mb-0">{{ $program ?: '—' }}</h5>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="small text-muted d-block mb-2">Spécialité</label>
                                                    <h5 class="mb-0">{{ $domain ?: '—' }}</h5>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="small text-muted d-block mb-2">Niveau</label>
                                                    <h5 class="mb-0">{{ $level ?: '—' }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Academic Performance --}}
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body">
                                            <h6 class="mb-3"><i class="fas fa-chart-line me-2 text-success"></i>Performance académique</h6>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar avatar-xl bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-star text-success fs-3"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="text-muted small">GPA (Grade Point Average)</div>
                                                    <h2 class="mb-0">{{ $gpa ? number_format((float)$gpa, 2) : 'N/A' }}</h2>
                                                </div>
                                            </div>
                                            @if($gpa)
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(($gpa / 4) * 100, 100) }}%"></div>
                                                </div>
                                                <p class="small text-muted mt-2 mb-0">Sur une échelle de 0 à 4.0</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body">
                                            <h6 class="mb-3"><i class="fas fa-layer-group me-2 text-primary"></i>Crédits académiques</h6>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar avatar-xl bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                        <i class="fas fa-certificate text-primary fs-3"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <div class="text-muted small">Crédits acquis</div>
                                                    <h2 class="mb-0">{{ $credits ? number_format($credits) : '0' }}</h2>
                                                </div>
                                            </div>
                                            @if($credits)
                                                @php
                                                    $totalCredits = 180; // Exemple: 180 crédits pour une licence
                                                    $creditPercentage = min(($credits / $totalCredits) * 100, 100);
                                                @endphp
                                                <div class="progress" style="height: 10px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $creditPercentage }}%"></div>
                                                </div>
                                                <p class="small text-muted mt-2 mb-0">{{ round($creditPercentage) }}% du cursus complet</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tab: Documents --}}
                        <div class="tab-pane fade" id="documents" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-12">
                                    <div class="alert alert-info border-0">
                                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Gestion des documents</h6>
                                        <p class="mb-0">Téléchargez et gérez vos documents (CV, lettres de motivation, certificats, réalisations) depuis la <a href="{{ route('design-graphique.documents.index') }}" class="alert-link">section Documents</a>.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('design-graphique.documents.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="avatar avatar-xl bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                                <i class="fas fa-file-pdf text-primary fs-2"></i>
                                            </div>
                                            <h6 class="mb-2">CV</h6>
                                            <p class="small text-muted mb-0">Télécharger votre CV</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('design-graphique.documents.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="avatar avatar-xl bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                                <i class="fas fa-file-alt text-success fs-2"></i>
                                            </div>
                                            <h6 class="mb-2">Lettre de motivation</h6>
                                            <p class="small text-muted mb-0">Télécharger votre lettre</p>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('design-graphique.documents.index') }}" class="card border-0 shadow-sm text-decoration-none h-100">
                                        <div class="card-body text-center p-4">
                                            <div class="avatar avatar-xl bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                                                <i class="fas fa-images text-warning fs-2"></i>
                                            </div>
                                            <h6 class="mb-2">Réalisations</h6>
                                            <p class="small text-muted mb-0">Portfolio & projets</p>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Old Header (to remove) --}}
                    @if(false)
                    {{-- Header --}}
                    <div class="row align-items-center g-3 mb-4">
                        <div class="col-md-8 d-flex align-items-center gap-3">
                            <img class="rounded-circle shadow-sm" src="{{ $photoUrl }}" alt="Avatar" style="height:96px;width:96px;object-fit:cover;border:3px solid #FF6B35;">
                            <div>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <h2 class="h4 fw-bold mb-0">{{ $fullName ?: '—' }}</h2>
                                    @if(!empty($level))
                                        <span class="badge rounded-pill bg-primary-subtle text-primary"><i class="fas fa-graduation-cap me-1"></i>{{ $level }}</span>
                                    @endif
                                    @if(!empty($domain))
                                        <span class="badge rounded-pill bg-warning-subtle text-warning"><i class="fas fa-shapes me-1"></i>{{ $domain }}</span>
                                    @endif
                                </div>
                                <div class="mt-1">
                                    @if($email)
                                        <a href="mailto:{{ $email }}" class="text-muted text-decoration-none me-3"><i class="fas fa-envelope me-1"></i>{{ $email }}</a>
                                    @endif
                                    @if($phone)
                                        <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}" class="text-muted text-decoration-none me-3"><i class="fas fa-phone me-1"></i>{{ $phone }}</a>
                                    @endif
                                    @if($whatsapp)
                                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $whatsapp) }}" target="_blank" rel="noopener" class="text-muted text-decoration-none"><i class="fab fa-whatsapp me-1"></i>WhatsApp</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="d-inline-flex flex-wrap gap-3 align-items-center">
                                @if(!empty($credits))
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center"><i class="fas fa-layer-group text-primary"></i></div>
                                        <div class="small">
                                            <div class="fw-semibold">{{ number_format($credits) }}</div>
                                            <div class="text-muted">Crédits</div>
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($gpa))
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm bg-success-subtle rounded-circle d-flex align-items-center justify-content-center"><i class="fas fa-chart-line text-success"></i></div>
                                        <div class="small">
                                            <div class="fw-semibold">{{ number_format((float)$gpa, 2) }}</div>
                                            <div class="text-muted">GPA</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="row g-3 mb-4">
                        <div class="col-12 d-flex flex-wrap gap-2">
                            <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-primary">
                                <i class="fas fa-user-edit me-1"></i> Modifier mon profil
                            </a>
                            <a href="{{ route('design-graphique.documents.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-folder-open me-1"></i> Mes documents
                            </a>
                            <a href="{{ route('design-graphique.parametres.index') }}" class="btn btn-outline-dark">
                                <i class="fas fa-user-cog me-1"></i> Paramètres
                            </a>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="overview-tab" data-bs-toggle="tab" data-bs-target="#overview" type="button" role="tab"><i class="fas fa-home me-1"></i> Aperçu</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="info-tab" data-bs-toggle="tab" data-bs-target="#informations" type="button" role="tab"><i class="fas fa-id-card me-1"></i> Informations</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-3" id="profileTabsContent">
                        {{-- Tab Aperçu --}}
                        <div class="tab-pane fade show active" id="overview" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12">
                                    <p class="text-muted">Bienvenue <strong>{{ $fullName }}</strong> dans votre espace Design Graphique.</p>
                                </div>
                            </div>
                        </div>
                        {{-- Tab Informations --}}
                        <div class="tab-pane fade" id="informations" role="tabpanel">
                            <div class="row g-3">
                                <div class="col-12 col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-transparent border-0"><h6 class="mb-0 text-uppercase">Identité</h6></div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Nom complet</div><div class="fw-semibold">{{ $fullName ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Sexe</div><div class="fw-semibold">{{ $gender ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Date de naissance</div><div class="fw-semibold">{{ $dob ? optional($dob)->format('d/m/Y') : '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Âge</div><div class="fw-semibold">{{ $age ? $age.' ans' : '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Matricule</div><div class="fw-semibold">{{ $studentId ?: '—' }}</div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-transparent border-0"><h6 class="mb-0 text-uppercase">Contact</h6></div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Email</div><div class="fw-semibold">{{ $email ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Téléphone</div><div class="fw-semibold">{{ $phone ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">WhatsApp</div><div class="fw-semibold">{{ $whatsapp ?: '—' }}</div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-1">
                                <div class="col-12 col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-transparent border-0"><h6 class="mb-0 text-uppercase">Localisation</h6></div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Quartier</div><div class="fw-semibold">{{ $quartier ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Ville</div><div class="fw-semibold">{{ $city ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Pays</div><div class="fw-semibold">{{ $country ?: '—' }}</div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-transparent border-0"><h6 class="mb-0 text-uppercase">Formation & Statut</h6></div>
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Programme</div><div class="fw-semibold">{{ $program ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Spécialité</div><div class="fw-semibold">{{ $domain ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Niveau</div><div class="fw-semibold">{{ $level ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Statut</div><div class="fw-semibold">{{ $status ?: '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Expérience</div><div class="fw-semibold">{{ $experience !== null ? ($experience.' an'.($experience>1?'s':'')) : '—' }}</div></div>
                                                <div class="col-12 col-sm-6"><div class="small text-muted">Secteur</div><div class="fw-semibold">{{ $sector ?: '—' }}</div></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ancien bloc "Profil utilisateur (résumé)" masqué pour éviter la redondance --}}
    @if(false)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {{-- Profil utilisateur (résumé) --}}
                    @php
                        $sf = optional($student);
                        $pr = optional($preReg);

                        $studentPhoto = $sf->profile_photo;
                        $prePhoto = $pr->profile_photo ?? $pr->photo ?? $pr->image ?? $pr->image_url ?? $pr->avatar;
                        $rawPhoto = $studentPhoto ?: $prePhoto;
                        $photoUrl = $rawPhoto ? (preg_match('/^https?:\/\//', $rawPhoto) ? $rawPhoto : asset($rawPhoto)) : asset('assets/img/avatar.png');

                        $fullName = trim(($sf->first_name ?? '') . ' ' . ($sf->last_name ?? ''));
                        if ($fullName === '') {
                            $fullName = ($user->name ?? '') ?: trim(($pr->first_name ?? '') . ' ' . ($pr->last_name ?? ''));
                        }
                        $email = ($sf->email ?? '') ?: (($user->email ?? '') ?: ($pr->email ?? ''));
                        $gender = ($sf->gender ?? '') ?: ($pr->gender ?? '');
                        $phone = ($sf->phone ?? '') ?: ($pr->phone ?? '');
                        $whatsapp = ($sf->whatsapp ?? '') ?: ($pr->whatsapp ?? '');
                        $quartier = ($sf->quartier ?? '') ?: ($pr->quartier ?? '');
                        $city = ($sf->city ?? '') ?: ($pr->city ?? '');
                        $country = ($sf->country ?? '') ?: ($pr->country ?? '');
                        $level = ($sf->level ?? '') ?: ($pr->level ?? '');
                        $domain = ($sf->specialization ?? '') ?: ($pr->specialization ?? '');

                        $gpa = $sf->gpa ?? null;
                        $credits = $sf->credits_earned ?? null;
                    @endphp

                    <div class="row g-4 align-items-center">
                        <div class="col-md-2 text-center">
                            <img class="rounded-circle shadow-sm" src="{{ $photoUrl }}" alt="avatar" style="height:96px;width:96px;object-fit:cover;border:3px solid #FF6B35;">
                        </div>
                        <div class="col-md-7">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <div class="fw-bold fs-5">{{ $fullName ?: '—' }}</div>
                                @if(!empty($level))
                                    <span class="badge bg-primary-subtle text-primary d-md-none"><i class="fas fa-graduation-cap me-1"></i>{{ $level }}</span>
                                @endif
                                @if(!empty($domain))
                                    <span class="badge bg-warning-subtle text-warning d-md-none"><i class="fas fa-shapes me-1"></i>{{ $domain }}</span>
                                @endif
                            </div>
                            <div class="text-muted">{{ $email ?: '—' }}</div>

                            <div class="d-flex flex-wrap gap-3 small text-muted mt-2">
                                @if($gender)
                                    <span><i class="fas fa-venus-mars me-1"></i>{{ $gender }}</span>
                                @endif
                                @if($phone)
                                    <span><i class="fas fa-phone me-1"></i>{{ $phone }}</span>
                                @endif
                                @if($whatsapp)
                                    <span><i class="fab fa-whatsapp me-1"></i>{{ $whatsapp }}</span>
                                @endif
                                @if($quartier)
                                    <span><i class="fas fa-home me-1"></i>{{ $quartier }}</span>
                                @endif
                                @if($city || $country)
                                    <span><i class="fas fa-map-marker-alt me-1"></i>{{ trim($city.' '.($country? ', '.$country : '')) }}</span>
                                @endif
                            </div>

                            <div class="d-flex gap-3 mt-3">
                                @if(!empty($credits))
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm bg-primary-subtle rounded-circle">
                                            <i class="fas fa-layer-group text-primary"></i>
                                        </div>
                                        <div class="small">
                                            <div class="fw-semibold">{{ number_format($credits) }}</div>
                                            <div class="text-muted">Crédits</div>
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($gpa))
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar avatar-sm bg-success-subtle rounded-circle">
                                            <i class="fas fa-chart-line text-success"></i>
                                        </div>
                                        <div class="small">
                                            <div class="fw-semibold">{{ number_format((float)$gpa, 2) }}</div>
                                            <div class="text-muted">GPA</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 text-md-end">
                            <div class="d-grid gap-2">
                                <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-primary">
                                    <i class="fas fa-user-edit me-1"></i>
                                    Modifier mon profil
                                </a>
                                <button type="button" class="btn btn-outline-secondary" disabled title="Bientôt disponible">
                                    <i class="fas fa-id-card me-1"></i>
                                    Carte Étudiant
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded border bg-light-subtle">
                                <div class="avatar avatar-lg bg-info-subtle rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-user-shield text-info"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Paramètres du profil</div>
                                    <div class="text-muted small">Mettez à jour vos informations personnelles</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('design-graphique.parametres.index') }}" class="btn btn-sm btn-info-subtle border-info text-info">
                                        Gérer
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="d-flex align-items-center gap-3 p-3 rounded border bg-light-subtle">
                                <div class="avatar avatar-lg bg-warning-subtle rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-folder-open text-warning"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Mes documents</div>
                                    <div class="text-muted small">CV, lettre de motivation, réalisations</div>
                                </div>
                                <div class="ms-auto">
                                    <a href="{{ route('design-graphique.documents.index') }}" class="btn btn-sm btn-warning-subtle border-warning text-warning">
                                        Ouvrir
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endif

    @endif

    {{-- Statistiques principales - Données dynamiques depuis la base --}}
    <div class="row g-4 mb-4 d-none">

        {{-- Carte TP Validés --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded">
                                <i class="fas fa-file-alt fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @isset($validationStats['tp_valides'])
                                    {{ $validationStats['tp_valides'] }}
                                @else
                                    0
                                @endisset
                            </div>
                            <div class="text-muted small">TP Validés</div>
                        </div>
                    </div>

                    {{-- Message de félicitation si TP validés --}}
                    @if(isset($validationStats['tp_valides']) && $validationStats['tp_valides'] > 0)
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Excellent travail !
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Carte Projets/Fichiers --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 text-warning rounded">
                                <i class="fas fa-folder-open fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @isset($tpStats['total_files'])
                                    {{ $tpStats['total_files'] }}
                                @else
                                    0
                                @endisset
                            </div>
                            <div class="text-muted small">Fichiers Uploadés</div>
                        </div>
                    </div>

                    {{-- Affichage de l'espace utilisé --}}
                    @if(isset($tpStats['total_size_mb']) && $tpStats['total_size_mb'] > 0)
                        <div class="mt-2">
                            <small class="text-info">
                                <i class="fas fa-hdd me-1"></i>
                                {{ number_format($tpStats['total_size_mb'], 1) }} MB utilisés
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Carte TP En Validation - Diagnostic amélioré --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-warning bg-opacity-10 text-warning rounded">
                                <i class="fas fa-clock fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                {{-- Affichage sécurisé avec debug --}}
                                @if(isset($validationStats) && is_array($validationStats) && array_key_exists('tp_en_validation', $validationStats))
                                    {{ $validationStats['tp_en_validation'] }}
                                @else
                                    0
                                @endif
                            </div>
                            <div class="text-muted small">En cours de validation</div>
                        </div>
                    </div>

                    {{-- Message selon l'état de validation --}}
                    @if(isset($validationStats['tp_en_validation']) && $validationStats['tp_en_validation'] > 0)

                    @else
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Aucun TP en attente
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Carte Documents PDF --}}
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-danger bg-opacity-10 text-danger rounded">
                                <i class="fas fa-file-pdf fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @isset($tpStats['total_pdf'])
                                    {{ $tpStats['total_pdf'] }}
                                @else
                                    0
                                @endisset
                            </div>
                            <div class="text-muted small">Documents PDF</div>
                        </div>
                    </div>

                    {{-- Message si des PDF sont disponibles --}}
                    @if(isset($tpStats['total_pdf']) && $tpStats['total_pdf'] > 0)
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-download me-1"></i>
                                Ressources disponibles
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
















    {{-- Section Formation de la Semaine --}}
    <div class="row g-4 mb-4 d-none">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-week text-primary me-2"></i>
                        Formation de la Semaine
                    </h5>
                </div>
                <div class="card-body">
                    {{-- En-tête de la formation --}}
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-lg bg-primary bg-opacity-10 text-primary rounded me-3">
                            <i class="fab fa-adobe"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">
                                @isset($stats['formation_semaine'])
                                    {{ $stats['formation_semaine'] }}
                                @else
                                    Formation Design Graphique - Semaine {{ date('W') }}
                                @endisset
                            </h6>
                            <small class="text-muted">Module Photoshop - Techniques avancées</small>
                        </div>
                    </div>

                    {{-- Cours de la semaine --}}
                    <div class="row g-3">
                        {{-- Cours théorique --}}
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-play-circle text-primary me-2"></i>
                                    <strong>Cours théorique</strong>
                                </div>
                                <p class="mb-2 small">Maîtrise des calques et masques de fusion</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">2h30</span>
                                    <small class="text-muted">Lundi 9h00 - 11h30</small>
                                </div>
                            </div>
                        </div>

                        {{-- TP Pratique --}}
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-laptop-code text-success me-2"></i>
                                    <strong>TP Pratique</strong>
                                </div>
                                <p class="mb-2 small">Création d'un montage photo complexe</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success bg-opacity-10 text-success">3h00</span>
                                    <small class="text-muted">Mercredi 14h00 - 17h00</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Profil Étudiant --}}
        <div class="row mb-4">

    </div>



    <!-- Section des derniers TP ajoutés -->
    @if(!empty($recentTPs))
    <div class="row mb-4 d-none">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2 text-primary"></i>
                            Mes Derniers TP
                        </h5>
                        <a href="{{ route('design-graphique.tp.index') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye me-1"></i>
                            Voir tous
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($recentTPs as $tp)
                        <div class="col-md-6 col-lg-4">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="d-flex align-items-start mb-2">
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-sm bg-primary bg-opacity-10 text-primary rounded">
                                            <i class="fas fa-file-alt"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <h6 class="mb-1 fw-semibold">{{ $tp['title'] }}</h6>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ date('d/m/Y', strtotime($tp['created_at'])) }}
                                        </small>
                                    </div>
                                </div>
                                @if($tp['description'])
                                    <p class="small text-muted mb-2">{{ Str::limit($tp['description'], 80) }}</p>
                                @endif
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark">
                                        <i class="fas fa-paperclip me-1"></i>
                                        {{ $tp['files_count'] }} fichier(s)
                                    </span>
                                    @if($tp['link'])
                                        <a href="{{ $tp['link'] }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif


    <!-- Reste du contenu existant -->
    <div class="row mb-4 d-none">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Informations Importantes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info border-0 mb-0">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-lightbulb fs-4 text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="alert-heading mb-2">Conseils pour réussir</h6>
                                <ul class="mb-0 ps-3">
                                    <li>Il faut participer toutes les activités et formations</li>
                                    <li>Publier régulièrement vos TP  validés pour suivre votre progression</li>
                                    <li>Utilisez des formats d'image de qualité (PNG, JPG)</li>
                                    <li>N'hésitez pas à joindre des documents PDF explicatifs</li>
                                    <li>Consultez le programme pour ne manquer aucun cours</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <!-- Section d'actions rapides -->
    <div class="row mb-4 d-none">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt me-2 text-warning"></i>
                        Actions Rapides
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('design-graphique.tp.ajouter') }}" class="btn btn-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-plus-circle fs-3 mb-2"></i>
                                <span class="fw-semibold">Ajouter un TP</span>
                                <small class="opacity-75">Nouveau travail pratique</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('design-graphique.tp.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-list fs-3 mb-2"></i>
                                <span class="fw-semibold">Mes TP</span>
                                <small class="opacity-75">Consulter mes travaux</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('design-graphique.profil.editer') }}" class="btn btn-outline-secondary w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-user-edit fs-3 mb-2"></i>
                                <span class="fw-semibold">Mon Profil</span>
                                <small class="opacity-75">Modifier mes informations</small>
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('design-graphique.programme.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center align-items-center py-3">
                                <i class="fas fa-calendar-alt fs-3 mb-2"></i>
                                <span class="fw-semibold">Programme</span>
                                <small class="opacity-75">Planning des cours</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Section certification (si applicable) -->
    @if(($tpStats['total_tp'] ?? 0) >= 10)
    <div class="row mb-4 d-none">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-success bg-opacity-10">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-trophy fs-1 text-success"></i>
                    </div>
                    <h5 class="text-success mb-2">Félicitations !</h5>
                    <p class="mb-3">Vous avez réalisé {{ $tpStats['total_tp'] }} TP. Vous êtes éligible pour la certification !</p>
                    <a href="{{ route('design-graphique.fin-formation.index') }}" class="btn btn-success">
                        <i class="fas fa-certificate me-2"></i>
                        Voir les critères de certification
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Ancienne section statistiques remplacée -->
    <div class="d-none">
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar avatar-lg bg-info bg-opacity-10 text-info rounded">
                                <i class="fas fa-certificate fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <div class="fw-bold text-dark fs-5">
                                @if(isset($stats['eligible_certificat']) && $stats['eligible_certificat'])
                                    <span class="text-success">Éligible</span>
                                @else
                                    <span class="text-warning">En cours</span>
                                @endif
                            </div>
                            <div class="text-muted small">Certification</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modules de formation spécialisés -->
    <div class="row g-4 mb-4 d-none">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">
                        <i class="fab fa-adobe text-danger me-2"></i>
                        Modules de la formation
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #001E36 0%, #31A8FF 100%);">
                                <i class="fab fa-adobe fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Photoshop</h6>
                                <small class="text-white opacity-75">Retouche & Montage</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #330000 0%, #FF9A00 100%);">
                                <i class="fab fa-adobe fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Illustrator</h6>
                                <small class="text-white opacity-75">Création Vectorielle</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 72%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #49021F 0%, #FF3366 100%);">
                                <i class="fab fa-adobe fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">InDesign</h6>
                                <small class="text-white opacity-75">Mise en Page</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 60%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="text-center p-3 rounded-3" style="background: linear-gradient(135deg, #003366 0%, #3399ff 100%);">
                                <i class="fas fa-chart-line fs-1 text-white mb-2"></i>
                                <h6 class="text-white mb-1">Strategy Business</h6>
                                <small class="text-white opacity-75">Marketing Digital</small>
                                <div class="progress mt-2" style="height: 4px;">
                                    <div class="progress-bar bg-light" style="width: 45%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Actions rapides spécialisées -->
    <div class="row g-4 d-none">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-primary me-2"></i>
                        Actions Rapides - Design Graphique
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-primary w-100 py-3">
                                <i class="fas fa-download d-block mb-2"></i>
                                <small>Logiciels Adobe</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-success w-100 py-3">
                                <i class="fas fa-palette d-block mb-2"></i>
                                <small>Mes Réalisations</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-warning w-100 py-3">
                                <i class="fas fa-video d-block mb-2"></i>
                                <small>Mes formations</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-info w-100 py-3">
                                <i class="fas fa-users d-block mb-2"></i>
                                <small>Mes classes</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-danger w-100 py-3">
                                <i class="fas fa-certificate d-block mb-2"></i>
                                <small>Mon Portfolio</small>
                            </a>
                        </div>
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="#" class="btn btn-outline-secondary w-100 py-3">
                                <i class="fas fa-calendar d-block mb-2"></i>
                                <small>Planning</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-lg {
    width: 56px;
    height: 56px;
}

.avatar-sm {
    width: 32px;
    height: 32px;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-warning:hover,
.btn-outline-info:hover,
.btn-outline-danger:hover,
.btn-outline-secondary:hover {
    transform: translateY(-1px);
}

/* Styles spécifiques pour les onglets de profil */
#profileTabs.nav-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    border-bottom: none;
}

#profileTabs .nav-item {
    margin-bottom: 0;
}

#profileTabs .nav-link {
    display: inline-flex !important;
    align-items: center;
    padding: 0.75rem 1.25rem;
    color: #6c757d;
    background-color: transparent;
    border: 1px solid #dee2e6;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
    white-space: nowrap;
    transform: none !important;
}

#profileTabs .nav-link:hover {
    color: #0d6efd;
    background-color: #e7f1ff;
    border-color: #0d6efd;
    transform: none !important;
}

#profileTabs .nav-link.active {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
    transform: none !important;
}

#profileTabs .nav-link i {
    margin-right: 0.5rem;
    width: auto;
}
</style>

<script>
// Initialize Bootstrap tabs
document.addEventListener('DOMContentLoaded', function() {
    // Get all tab buttons
    const tabButtons = document.querySelectorAll('#profileTabs button[data-bs-toggle="tab"]');
    
    // Add click event listeners to all tab buttons
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all buttons and tab panes
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            
            document.querySelectorAll('.tab-pane').forEach(pane => {
                pane.classList.remove('show', 'active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            // Show corresponding tab pane
            const targetId = this.getAttribute('data-bs-target');
            const targetPane = document.querySelector(targetId);
            if (targetPane) {
                targetPane.classList.add('show', 'active');
            }
        });
    });
    
    console.log('Profile tabs initialized:', tabButtons.length + ' tabs found');
});
</script>
@endsection
