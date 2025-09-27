@extends('layouts.ki-admin')

@section('title', 'Prévisualisation du profil - CVThèque')
@section('page-title', 'Prévisualisation du profil')

@section('content')
<!-- Header avec actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #003366, #0066cc); color: white;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-2">
                            <i class="fas fa-eye me-2"></i>
                            Prévisualisation de votre profil professionnel
                        </h3>
                        <p class="mb-0">Voici comment votre profil apparaîtra aux employeurs</p>
                        @if($profile->profile_completion_score < 80)
                            <div class="mt-2">
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Profil incomplet ({{ $profile->profile_completion_score }}%)
                                </span>
                            </div>
                        @else
                            <div class="mt-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Profil complet ({{ $profile->profile_completion_score }}%)
                                </span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <a href="{{ route('design-graphique.cvtheque.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-arrow-left me-1"></i>
                            Retour
                        </a>
                        <button class="btn btn-success" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>
                            Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profil principal -->
<div class="row">
    <div class="col-md-4">
        <!-- Informations personnelles -->
        <div class="card mb-4">
            <div class="card-body text-center">
                <div class="text-center">
                    @if(isset($userProfile) && $userProfile->profile_photo)
                        <img src="{{ asset('uploads/photos/' . basename($userProfile->profile_photo)) }}"
                             alt="Photo de profil"
                             class="rounded-circle mb-3"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #003366;">
                    @elseif(session('user_photo'))
                        <img src="{{ asset('uploads/photos/' . basename(session('user_photo'))) }}"
                             alt="Photo de profil"
                             class="rounded-circle mb-3"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #003366;">
                    @elseif($userInfo->profile_photo && file_exists(public_path('uploads/photos/' . $userInfo->profile_photo)))
                        <img src="{{ asset('uploads/photos/' . $userInfo->profile_photo) }}"
                             class="rounded-circle mb-3"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #003366;"
                             alt="Photo de {{ $userInfo->name ?? 'profil' }}">
                    @else
                        <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mb-3 mx-auto"
                             style="width: 120px; height: 120px; border: 4px solid #003366;">
                            <i class="fas fa-user fs-1 text-primary"></i>
                        </div>
                    @endif

                    <h4 class="fw-bold mb-1" style="color: #003366;">
                        @if(isset($userProfile) && $userProfile->first_name && $userProfile->last_name)
                            {{ $userProfile->first_name }} {{ $userProfile->last_name }}
                        @elseif(session('user_prenom') && session('user_nom'))
                            {{ session('user_prenom') }} {{ session('user_nom') }}
                        @else
                            {{ $userInfo->name ?? 'Nom non renseigné' }}
                        @endif
                    </h4>
                </div>
                <p class="text-muted mb-2">
                    {{ $profile->professional_title ?? 'Étudiant(e) en Design Graphique' }}
                    @if($profile->years_experience > 0)
                        <br><small>{{ $profile->years_experience }} {{ $profile->years_experience > 1 ? 'années' : 'année' }} d'expérience</small>
                    @endif
                </p>
                <div class="mb-3">
                    <span class="badge" style="background-color: #3399ff; color: white;">
                        Formation EVC 2024
                    </span>
                    @if($profile->job_type && $profile->job_type !== 'Tout')
                        <span class="badge bg-secondary ms-1">
                            {{ $profile->job_type }}
                        </span>
                    @endif
                </div>

                <!-- Informations de contact -->
                <div class="text-start">
                    @if($userInfo->email)
                        <div class="mb-2">
                            <i class="fas fa-envelope text-primary me-2"></i>
                            <small>{{ $userInfo->email }}</small>
                        </div>
                    @endif
                    @if($profile->professional_email && $profile->professional_email !== $userInfo->email)
                        <div class="mb-2">
                            <i class="fas fa-envelope-open text-primary me-2"></i>
                            <small>{{ $profile->professional_email }} <span class="text-muted">(pro)</span></small>
                        </div>
                    @endif
                    @if($profile->professional_phone)
                        <div class="mb-2">
                            <i class="fas fa-phone text-primary me-2"></i>
                            <small>{{ $profile->professional_phone }}</small>
                        </div>
                    @endif
                    @if($profile->preferred_locations && count($profile->preferred_locations) > 0)
                        <div class="mb-2">
                            <i class="fas fa-map-marker-alt text-primary me-2"></i>
                            <small>{{ implode(', ', $profile->preferred_locations) }}</small>
                        </div>
                    @endif
                    @if($profile->linkedin_profile)
                        <div class="mb-2">
                            <i class="fab fa-linkedin text-primary me-2"></i>
                            <small>
                                <a href="{{ $profile->linkedin_url }}" target="_blank" class="text-decoration-none">
                                    {{ str_replace(['https://', 'http://'], '', $profile->linkedin_profile) }}
                                </a>
                            </small>
                        </div>
                    @endif
                    @if($profile->professional_website)
                        <div class="mb-2">
                            <i class="fas fa-globe text-primary me-2"></i>
                            <small>
                                <a href="{{ $profile->website_url }}" target="_blank" class="text-decoration-none">
                                    {{ str_replace(['https://', 'http://'], '', $profile->professional_website) }}
                                </a>
                            </small>
                        </div>
                    @endif
                    @if($profile->behance_profile)
                        <div class="mb-2">
                            <i class="fab fa-behance text-primary me-2"></i>
                            <small>
                                <a href="{{ $profile->behance_profile }}" target="_blank" class="text-decoration-none">
                                    Behance
                                </a>
                            </small>
                        </div>
                    @endif
                    @if($profile->dribbble_profile)
                        <div class="mb-2">
                            <i class="fab fa-dribbble text-primary me-2"></i>
                            <small>
                                <a href="{{ $profile->dribbble_profile }}" target="_blank" class="text-decoration-none">
                                    Dribbble
                                </a>
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Compétences -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-star me-2"></i>
                    Compétences techniques
                </h6>
            </div>
            <div class="card-body">
                @if($profile->software_skills && count($profile->software_skills) > 0)
                    @php
                        $softwareLabels = [
                            'photoshop' => 'Photoshop',
                            'illustrator' => 'Illustrator',
                            'indesign' => 'InDesign',
                            'figma' => 'Figma',
                            'canva' => 'Canva',
                            'after_effects' => 'After Effects'
                        ];
                        $colors = ['#003366', '#3399ff', '#ff6633', '#FF9900', '#28a745', '#dc3545'];
                    @endphp
                    @foreach($profile->software_skills as $index => $skill)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <small>{{ $softwareLabels[$skill] ?? ucfirst($skill) }}</small>
                                <small>
                                    @switch($skill)
                                        @case('photoshop')
                                        @case('illustrator')
                                            Avancé
                                            @break
                                        @case('indesign')
                                        @case('figma')
                                            Intermédiaire
                                            @break
                                        @default
                                            Débutant
                                    @endswitch
                                </small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" style="width: {{ in_array($skill, ['photoshop', 'illustrator']) ? '90' : (in_array($skill, ['indesign', 'figma']) ? '75' : '60') }}%; background-color: {{ $colors[$index % count($colors)] }};"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune compétence technique renseignée
                    </p>
                @endif

                @if($profile->technical_skills && count($profile->technical_skills) > 0)
                    <hr class="my-3">
                    <h6 class="mb-3">
                        <i class="fas fa-cogs me-2"></i>
                        Autres compétences
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($profile->technical_skills as $skill)
                            <span class="badge bg-light text-dark border">{{ $skill }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Langues -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-globe me-2"></i>
                    Langues
                </h6>
            </div>
            <div class="card-body">
                @if($profile->languages && count($profile->languages) > 0)
                    @foreach($profile->languages as $language)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <small>{{ $language }}</small>
                                <small class="text-muted">
                                    @if(strtolower($language) === 'français')
                                        Langue maternelle
                                    @else
                                        Niveau à préciser
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucune langue renseignée
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- À propos -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    À propos de moi
                </h5>
            </div>
            <div class="card-body">
                @if($profile->professional_summary)
                    <p class="mb-0">{{ $profile->professional_summary }}</p>
                @else
                    <p class="text-muted mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Aucun résumé professionnel renseigné
                    </p>
                @endif

                @if($profile->current_position || $profile->current_company)
                    <hr class="my-3">
                    <div class="row">
                        @if($profile->current_position)
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Poste actuel</h6>
                                <p class="mb-0">{{ $profile->current_position }}</p>
                            </div>
                        @endif
                        @if($profile->current_company)
                            <div class="col-md-6">
                                <h6 class="text-muted mb-1">Entreprise</h6>
                                <p class="mb-0">{{ $profile->current_company }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                @if($profile->availability_date || $profile->salary_expectation || $profile->remote_work || $profile->willing_to_relocate)
                    <hr class="my-3">
                    <h6 class="mb-3">
                        <i class="fas fa-briefcase me-2"></i>
                        Préférences professionnelles
                    </h6>
                    <div class="row">
                        @if($profile->availability_date)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Disponibilité :</small><br>
                                <small>{{ \Carbon\Carbon::parse($profile->availability_date)->format('d/m/Y') }}</small>
                            </div>
                        @endif
                        @if($profile->salary_expectation)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Salaire souhaité :</small><br>
                                <small>{{ $profile->salary_expectation }}</small>
                            </div>
                        @endif
                        @if($profile->remote_work)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Télétravail :</small><br>
                                <small><i class="fas fa-check text-success me-1"></i>Accepté</small>
                            </div>
                        @endif
                        @if($profile->willing_to_relocate)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Mobilité :</small><br>
                                <small><i class="fas fa-check text-success me-1"></i>Ouvert à la mobilité</small>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>


        <!-- Documents disponibles -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2"></i>
                    Documents disponibles
                    @php
                    // Compter uniquement les documents administratifs (sans réalisations)
                    $adminDocumentsCount = 0;
                    if (isset($documentsHistory)) {
                        foreach ($documentsHistory as $document) {
                            if ($document['type'] !== 'Réalisation') {
                                $adminDocumentsCount++;
                            }
                        }
                    }
                @endphp
                    @if($adminDocumentsCount > 0)
                        <span class="badge bg-primary ms-2">{{ $adminDocumentsCount }}</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @php
                    // Filtrer les documents pour exclure les réalisations
                    $documentsOnly = [];
                    if (isset($documentsHistory)) {
                        foreach ($documentsHistory as $document) {
                            if ($document['type'] !== 'Réalisation') {
                                $documentsOnly[] = $document;
                            }
                        }
                    }
                @endphp

                @if(count($documentsOnly) > 0)
                    <div class="row">
                        @foreach($documentsOnly as $document)
                            <div class="col-md-6 mb-3">
                                <div class="document-item">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $document['icon'] ?? 'fas fa-file' }} {{ $document['color'] ?? 'text-secondary' }} fa-2x me-3"></i>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $document['type'] ?? 'Document' }}</h6>
                                            <small class="text-muted">{{ $document['name'] ?? 'Fichier' }}</small>
                                            <div class="mt-1">
                                                @php
                                                    $validationBadge = $document['validation_badge'] ?? [
                                                        'text' => 'En cours d\'analyse',
                                                        'class' => 'bg-warning text-dark',
                                                        'icon' => 'fas fa-clock'
                                                    ];
                                                @endphp
                                                <span class="badge {{ $validationBadge['class'] }}">
                                                    <i class="{{ $validationBadge['icon'] }} me-1"></i>
                                                    {{ $validationBadge['text'] }}
                                                </span>
                                                @if(isset($document['validated_at']) && $document['validated_at'])
                                                    <small class="text-muted d-block">{{ $document['validated_at'] }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @if(isset($document['download_url']) && $document['download_url'])
                                            <a href="{{ $document['download_url'] }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled title="Fichier non disponible">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Aucun document uploadé</h6>
                        <p class="text-muted mb-3">Les documents administratifs (CV, lettre de motivation, etc.) seront affichés ici une fois uploadés.</p>
                        <a href="{{ route('design-graphique.cvtheque.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Ajouter des documents
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Portfolio - Réalisations -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-palette me-2"></i>
                    Portfolio - Mes réalisations
                    @if(isset($documentsHistory) && count(array_filter($documentsHistory, function($doc) { return $doc['type'] === 'Réalisation'; })) > 0)
                        <span class="badge bg-primary ms-2">
                            {{ count(array_filter($documentsHistory, function($doc) { return $doc['type'] === 'Réalisation'; })) }}
                        </span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @php
                    $realisations = [];
                    if (isset($documentsHistory)) {
                        foreach ($documentsHistory as $document) {
                            if ($document['type'] === 'Réalisation') {
                                // Les réalisations sont directement dans $document, pas dans $document['files']
                                $extension = strtolower(pathinfo($document['name'], PATHINFO_EXTENSION));
                                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'ai', 'psd'])) {
                                    $realisations[] = [
                                        'name' => $document['name'],
                                        'url' => $document['download_url'] ?? '#',
                                        'size' => $document['size_bytes'] ?? 0,
                                        'path' => $document['path'] ?? '',
                                        'validation_badge' => $document['validation_badge'] ?? null
                                    ];
                                }
                            }
                        }
                    }
                @endphp

                @if(count($realisations) > 0)
                    <div class="row">
                        @foreach($realisations as $index => $realisation)
                            <div class="col-md-4 mb-3">
                                <div class="portfolio-item">
                                    <div class="portfolio-image">
                                        @if($realisation['url'] && $realisation['url'] !== '#')
                                            <img src="{{ $realisation['url'] }}"
                                                 class="img-fluid rounded"
                                                 style="width: 100%; height: 200px; object-fit: cover;"
                                                 alt="{{ $realisation['name'] ?? 'Réalisation' }}"
                                                 loading="lazy">
                                        @else
                                            <div class="d-flex align-items-center justify-content-center rounded"
                                                 style="width: 100%; height: 200px; background-color: #f8f9fa; border: 2px dashed #dee2e6;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="portfolio-overlay">
                                            @if($realisation['url'] && $realisation['url'] !== '#')
                                                <a href="{{ $realisation['url'] }}" target="_blank" class="btn btn-light btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <h6 class="mt-2 mb-1">{{ pathinfo($realisation['name'], PATHINFO_FILENAME) }}</h6>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            {{ strtoupper(pathinfo($realisation['name'], PATHINFO_EXTENSION)) }}
                                            @if(isset($realisation['size']) && $realisation['size'] > 0)
                                                • {{ number_format($realisation['size'] / 1024, 1) }} KB
                                            @endif
                                        </small>
                                        @if(isset($realisation['validation_badge']))
                                            <span class="badge {{ $realisation['validation_badge']['class'] ?? 'bg-secondary' }} ms-1">
                                                <i class="{{ $realisation['validation_badge']['icon'] ?? 'fas fa-clock' }} me-1"></i>
                                                {{ $realisation['validation_badge']['text'] ?? 'En cours' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-images fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">Aucune réalisation uploadée</h6>
                        <p class="text-muted mb-3">Vos créations visuelles apparaîtront ici une fois uploadées.</p>
                        <a href="{{ route('design-graphique.cvtheque.index') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i>
                            Ajouter des réalisations
                        </a>
                    </div>
                @endif
            </div>
        </div>


    </div>
</div>

<style>
/* Timeline */
.timeline-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-top: 6px;
}

/* Documents */
.document-item {
    padding: 1rem;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.document-item:hover {
    border-color: #003366;
    box-shadow: 0 2px 8px rgba(0, 51, 102, 0.1);
}

/* Portfolio */
.portfolio-item {
    position: relative;
}

.portfolio-image {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.portfolio-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 51, 102, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.portfolio-image:hover .portfolio-overlay {
    opacity: 1;
}

/* Print styles */
@media print {
    .btn, .card-header, .portfolio-overlay {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    .card-body {
        padding: 0.5rem !important;
    }

    .portfolio-image img {
        max-height: 150px;
        object-fit: cover;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: flex-start !important;
    }

    .portfolio-item {
        margin-bottom: 1rem;
    }
}
</style>

<script>
// Smooth scrolling for internal links
document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive functionality here
    console.log('Profile preview loaded');
});
</script>
@endsection
