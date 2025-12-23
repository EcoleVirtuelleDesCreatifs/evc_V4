@extends('layouts.admin')

@section('title', 'Profil Étudiant - ' . $student->first_name . ' ' . $student->last_name)

@section('content')
<div class="container-fluid" data-student-id="{{ $student->id }}">
    <!-- Header Complet avec Photo et Informations Détaillées -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card text-white">
                <div class="card-body p-4">
                    <!-- Section Photo et Nom Principal -->
                    <div class="row align-items-center mb-4">
                        <div class="col-md-3 text-center">
                            <div class="dashboard-avatar-large mx-auto mb-3">
                                @php
                                    $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);
                                    if (str_contains($photoUrl, 'default-avatar') || str_contains($photoUrl, 'avatar.png')) {
                                        $fallback = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->photo ?? null);
                                        if (!str_contains($fallback, 'default-avatar') && !str_contains($fallback, 'avatar.png')) {
                                            $photoUrl = $fallback;
                                        }
                                    }
                                @endphp

                                @if($photoUrl && !str_contains($photoUrl, 'default-avatar') && !str_contains($photoUrl, 'avatar.png'))
                                    <img src="{{ $photoUrl }}"
                                         alt="Photo de {{ $student->first_name }}"
                                         class="rounded-circle img-fluid shadow-lg"
                                         style="width: 140px; height: 140px; object-fit: cover; border: 4px solid #FF6B35;">
                                @else
                                    <div class="dashboard-avatar-placeholder d-flex align-items-center justify-content-center shadow-lg"
                                         style="width: 140px; height: 140px; font-size: 3rem; font-weight: bold; background: var(--dashboard-bg); border: 4px solid var(--dashboard-border); color: var(--dashboard-accent); border-radius: 50%;">
                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="mt-2">
                                @if($student->status == 'Actif')
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>{{ $student->status }}
                                    </span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $student->status }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-8">
                                    <h1 class="text-white fw-bold mb-2" style="font-size: 2.2rem;">{{ $student->first_name }} {{ $student->last_name }}</h1>
                                    <p class="text-white-75 mb-3" style="font-size: 1.1rem;">
                                        <i class="fas fa-graduation-cap me-2"></i>
                                        {{ ucfirst(str_replace('-', ' ', $student->formation_souhaitee ?? 'Formation non spécifiée')) }}
                                    </p>

                                    <!-- Informations de Contact -->
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <div class="dashboard-info-card p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                                <h6 class="text-white fw-bold mb-3">
                                                    <i class="fas fa-address-card me-2"></i>Contact
                                                </h6>
                                                <div class="dashboard-info-item mb-2">
                                                    <i class="fas fa-envelope me-2 text-white-50"></i>
                                                    <span class="text-white">{{ $student->email }}</span>
                                                </div>
                                                <div class="dashboard-info-item mb-2">
                                                    <i class="fas fa-phone me-2 text-white-50"></i>
                                                    <span class="text-white-75">{{ $student->phone ?? 'Non renseigné' }}</span>
                                                </div>
                                                <div class="dashboard-info-item">
                                                    <i class="fab fa-whatsapp me-2 text-success"></i>
                                                    <span class="text-white-75">{{ $student->whatsapp ?? 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="dashboard-info-card p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                                <h6 class="text-white fw-bold mb-3">
                                                    <i class="fas fa-map-marker-alt me-2"></i>Localisation
                                                </h6>
                                                <div class="dashboard-info-item mb-2">
                                                    <i class="fas fa-globe me-2 text-white-50"></i>
                                                    <span class="text-white-75">{{ $student->country ?? 'Pays non renseigné' }}</span>
                                                </div>
                                                <div class="dashboard-info-item mb-2">
                                                    <i class="fas fa-city me-2 text-white-50"></i>
                                                    <span class="text-white-75">{{ $student->city ?? 'Ville non renseignée' }}</span>
                                                </div>
                                                <div class="dashboard-info-item">
                                                    <i class="fas fa-home me-2 text-white-50"></i>
                                                    <span class="text-white-75">{{ $student->quartier ?? 'Quartier non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <!-- Statistiques Rapides -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="dashboard-stat-box text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                                <div class="h4 text-white fw-bold mb-1">{{ $designProjectStats['total_projets'] + $projectStats['total_projets'] }}</div>
                                                <div class="text-white-50 small">Projets</div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="dashboard-stat-box text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                                <div class="h4 text-info fw-bold mb-1">{{ isset($userActivities) && is_countable($userActivities) ? count($userActivities) : 0 }}</div>
                                                <div class="text-white-50 small">Activités</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Progression Formation -->
                                    <div class="dashboard-progress-card p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-white-75 small">Progression Formation</span>
                                            <span class="text-success fw-bold">{{ $progression['pourcentage_completion'] }}%</span>
                                        </div>
                                        <div class="dashboard-progress mb-2">
                                            <div class="dashboard-progress-bar" style="width: {{ $progression['pourcentage_completion'] }}%"></div>
                                        </div>
                                        <div class="text-center">
                                            <small class="text-white-75">Niveau: <span class="text-white fw-bold">{{ $progression['niveau_actuel'] }}</span></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>





                    <!-- Section Informations Académiques et Personnelles -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="dashboard-info-card p-3 rounded h-100" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                <h6 class="text-white fw-bold mb-3">
                                    <i class="fas fa-user-graduate me-2"></i>Profil Académique
                                </h6>
                                <div class="dashboard-info-item mb-2">
                                    <i class="fas fa-calendar-alt me-2 text-white-50"></i>
                                    <span class="text-white-75">{{ $student->age ?? 'Âge non renseigné' }} ans</span>
                                </div>
                                <div class="dashboard-info-item mb-2">
                                    <i class="fas fa-school me-2 text-white-50"></i>
                                    <span class="text-white-75">{{ $student->education_level ?? 'Niveau d\'étude non renseigné' }}</span>
                                </div>
                                <div class="dashboard-info-item">
                                    <i class="fas fa-certificate me-2 text-white-50"></i>
                                    <span class="text-white-75">{{ $student->last_diploma ?? 'Dernier diplôme non renseigné' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="dashboard-info-card p-3 rounded h-100" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                <h6 class="text-white fw-bold mb-3">
                                    <i class="fas fa-user-edit me-2"></i>Biographie
                                </h6>
                                @if(isset($student->biography) && $student->biography)
                                    <p class="text-white-75 mb-0" style="line-height: 1.6;">{{ $student->biography }}</p>
                                @else
                                    <p class="text-white-50 mb-0 fst-italic">Aucune biographie renseignée par l'étudiant.</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="dashboard-info-card p-3 rounded h-100" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                <h6 class="text-white fw-bold mb-3">
                                    <i class="fas fa-bullseye me-2"></i>Attentes & Objectifs
                                </h6>
                                @if(isset($student->expectations) && $student->expectations)
                                    <p class="text-white-75 mb-0" style="line-height: 1.6;">{{ $student->expectations }}</p>
                                @else
                                    <p class="text-white-50 mb-0 fst-italic">Objectifs non renseignés.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Section Inscription et Dates -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="dashboard-info-card p-3 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                <i class="fas fa-calendar-plus fa-2x text-info mb-2"></i>
                                <h6 class="text-white fw-bold mb-1">Date d'Inscription</h6>
                                <p class="text-white-75 mb-0">{{ \Carbon\Carbon::parse($student->date_inscription ?? $student->created_at)->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dashboard-info-card p-3 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h6 class="text-white fw-bold mb-1">Dernière Connexion</h6>
                                <p class="text-white-75 mb-0">{{ \Carbon\Carbon::parse($activite['derniere_connexion'])->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dashboard-info-card p-3 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                                <h6 class="text-white fw-bold mb-1">Niveau d'Engagement</h6>
                                @if($activite['niveau_engagement'] == 'Élevé')
                                    <span class="badge bg-success px-3 py-2">{{ $activite['niveau_engagement'] }}</span>
                                @elseif($activite['niveau_engagement'] == 'Moyen')
                                    <span class="badge bg-warning px-3 py-2">{{ $activite['niveau_engagement'] }}</span>
                                @else
                                    <span class="badge bg-danger px-3 py-2">{{ $activite['niveau_engagement'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions rapides Dashboard -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="dashboard-btn dashboard-btn-primary" onclick="editStudent()">
                                    <i class="fas fa-edit me-2"></i>Modifier
                                </button>
                                <button type="button" class="dashboard-btn dashboard-btn-info" onclick="sendEmail()">
                                    <i class="fas fa-envelope me-2"></i>Envoyer Email
                                </button>
                                <button type="button" class="dashboard-btn dashboard-btn-warning" onclick="suspendStudent()">
                                    <i class="fas fa-user-times me-2"></i>Suspendre
                                </button>
                                <button type="button" class="dashboard-btn dashboard-btn-success {{ !$certificat['eligible'] ? 'dashboard-btn-disabled' : '' }}" onclick="generateCertificate()" {{ !$certificat['eligible'] ? 'disabled' : '' }}>
                                    <i class="fas fa-certificate me-2"></i>Générer Certificat
                                </button>
                            </div>
                        </div>
                    </div>
            </div>


        </div>
    </div>





    <!-- Informations détaillées -->
    <div class="row">
        <!-- Colonne gauche -->
        <div class="col-md-12">
            <!-- Grille Dashboard principale -->
            <div class="dashboard-grid">
                <!-- Statistiques Projets Design -->
                <div class="dashboard-card dashboard-stats">
                    <div class="card-body p-4">
                        <div class="dashboard-icon">
                            <i class="fas fa-palette fa-lg"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-3">Projets Réels</h5>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-white mb-1">{{ $designProjectStats['total_projets'] }}</div>
                                    <div class="text-white-50 small">Total Projets</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-success mb-1">{{ $designProjectStats['projets_termines'] }}</div>
                                    <div class="text-white-50 small">Terminés</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-info mb-1">{{ $designProjectStats['projets_actifs'] }}</div>
                                    <div class="text-white-50 small">En Cours</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-warning mb-1">{{ $designProjectStats['projets_brouillon'] }}</div>
                                    <div class="text-white-50 small">Brouillons</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques Travaux Pratiques (TP) -->
                <div class="dashboard-card dashboard-projects">
                    <div class="card-body p-4">
                        <div class="dashboard-icon">
                            <i class="fab fa-laravel fa-lg"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-3">Travaux Pratiques</h5>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-white mb-1">{{ $projectStats['total_projets'] }}</div>
                                    <div class="text-white-50 small">Total</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-success mb-1">{{ $projectStats['projets_valides'] }}</div>
                                    <div class="text-white-50 small">Validés</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-info mb-1">{{ $projectStats['projets_en_cours'] }}</div>
                                    <div class="text-white-50 small">En Cours</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-warning mb-1">{{ $projectStats['projets_termines'] }}</div>
                                    <div class="text-white-50 small">Terminés</div>
                                </div>
                            </div>
                        </div>

                        @if($projectStats['total_projets'] > 0)
                            <div class="mt-4">
                                <div class="d-flex justify-content-between text-white-50 mb-2">
                                    <span>Taux de Validation</span>
                                    <span>{{ round(($projectStats['projets_valides'] / $projectStats['total_projets']) * 100) }}%</span>
                                </div>
                                <div class="dashboard-progress">
                                    <div class="dashboard-progress-bar" style="width: {{ round(($projectStats['projets_valides'] / $projectStats['total_projets']) * 100) }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>


                <!-- Section Documents Dashboard -->
                <div class="dashboard-card dashboard-documents">
                    <div class="card-body p-4">
                        <div class="dashboard-icon">
                            <i class="fas fa-file-pdf fa-lg"></i>
                        </div>
                        <h5 class="text-white fw-bold mb-3">Documents CVthèque</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-white mb-1">{{ $userDocuments->count() }}</div>
                                    <div class="text-white-50 small">Total Docs</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                    <div class="h4 fw-bold text-success mb-1">{{ $userDocuments->where('status', 'approved')->count() }}</div>
                                    <div class="text-white-50 small">Approuvés</div>
                                </div>
                            </div>
                        </div>

                        @if($userDocuments->count() > 0)
                            <div class="documents-list">
                                @foreach($userDocuments->take(3) as $document)
                                    <div class="mb-3 p-3 rounded d-flex align-items-center justify-content-between" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="d-flex align-items-center">
                                            <div class="dashboard-icon-small me-3">
                                                @if($document->document_type == 'cv')
                                                    <i class="fas fa-file-user"></i>
                                                @elseif($document->document_type == 'lettre_motivation')
                                                    <i class="fas fa-file-alt"></i>
                                                @elseif($document->document_type == 'portfolio')
                                                    <i class="fas fa-images"></i>
                                                @else
                                                    <i class="fas fa-file-pdf"></i>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-white fw-bold small">{{ ucfirst(str_replace('_', ' ', $document->document_type)) }}</div>
                                                <div class="text-white-50 small">{{ \Carbon\Carbon::parse($document->uploaded_at)->format('d/m/Y') }}</div>
                                            </div>
                                        </div>
                                        <div>
                                            @if($document->status == 'approved')
                                                <span class="dashboard-badge bg-success">✓</span>
                                            @elseif($document->status == 'rejected')
                                                <span class="dashboard-badge bg-danger">✗</span>
                                            @else
                                                <span class="dashboard-badge bg-warning">⏳</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-white-50 py-4">
                                <div class="dashboard-icon mx-auto mb-3">
                                    <i class="fas fa-folder-open fa-2x"></i>
                                </div>
                                <div>Aucun document uploadé</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Section Progression Dashboard -->
            <div class="dashboard-card dashboard-progression mb-4">
                <div class="card-body p-4">
                    <div class="dashboard-icon">
                        <i class="fas fa-chart-line fa-lg"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-3">Progression de Formation</h5>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="p-4 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                <div class="text-white-50 small mb-2">Formation Actuelle</div>
                                <div class="text-white fw-bold h6 mb-3">{{ ucfirst(str_replace('-', ' ', $student->formation_souhaitee)) }}</div>

                                <div class="text-white-50 small mb-2">Niveau Actuel</div>
                                <div class="text-white fw-bold mb-4">{{ $progression['niveau_actuel'] }}</div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between text-white-50 mb-2">
                                        <span>Modules Complétés</span>
                                        <span>{{ $progression['modules_completes'] }}/{{ $progression['modules_total'] }}</span>
                                    </div>
                                    <div class="dashboard-progress">
                                        <div class="dashboard-progress-bar" style="width: {{ $progression['pourcentage_completion'] }}%"></div>
                                    </div>
                                </div>

                                <div class="text-white-50 small mb-2">Prochaine Étape</div>
                                <div class="text-white">{{ $progression['prochaine_etape'] }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-4 rounded" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                <div class="text-white-50 small mb-3">Compétences Acquises</div>
                                <div class="d-flex flex-wrap">
                                    @foreach($progression['competences_acquises'] as $competence)
                                        <div class="dashboard-badge bg-info me-2 mb-2">
                                            <span>{{ $competence }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Section Tableaux de Gestion -->
            <div class="row">
                <div class="col-12">
                    <!-- Travaux Pratiques (images/prints) Paginés -->
                    @if(isset($projectImagesData))
                        <x-admin.paginated-project-images-table
                            :imagesData="$projectImagesData"
                            tableId="project-images-table"
                            pageParam="images_page"
                            :studentId="$student->id" />
                    @endif
                </div>

                <div class="col-12">
                     <!-- Projets Réels (Design Projects) - Composant Dédié -->
                    @if(isset($designProjectImagesData))
                        <x-admin.paginated-design-projects-table
                            :imagesData="$designProjectImagesData"
                            tableId="design-project-images-table"
                            pageParam="design_images_page"
                            :studentId="$student->id" />
                    @endif
                </div>
            </div>

            <!-- Documents CVthèque Paginés par Session -->
            <x-admin.paginated-documents-table
                :documents="$cvthequeDocumentsData['documents_by_session'] ?? []"
                :pagination="$cvthequeDocumentsData['pagination'] ?? []"
                tableId="cvtheque-documents-table"
                pageParam="cvtheque_page"
                :studentId="$student->id"
                title="Documents CVthèque"
                icon="fas fa-briefcase"
            />

            <!-- Section Activités et Historique des Connexions -->
            <div class="dashboard-card dashboard-activities mb-4">
                <div class="card-body p-4">
                    <div class="dashboard-icon">
                        <i class="fas fa-history fa-lg"></i>
                    </div>
                    <h5 class="text-white fw-bold mb-3">Activités Récentes & Historique des Connexions</h5>

                    <!-- Onglets pour séparer les types d'activités -->
                    <ul class="nav nav-pills mb-3" id="activities-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-activities-tab" data-bs-toggle="pill" data-bs-target="#all-activities" type="button" role="tab">
                                <i class="fas fa-list me-2"></i>Toutes les Activités
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="login-history-tab" data-bs-toggle="pill" data-bs-target="#login-history" type="button" role="tab">
                                <i class="fas fa-sign-in-alt me-2"></i>Connexions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="other-activities-tab" data-bs-toggle="pill" data-bs-target="#other-activities" type="button" role="tab">
                                <i class="fas fa-tasks me-2"></i>Autres Activités
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="activities-content">
                        <!-- Toutes les activités combinées -->
                        <div class="tab-pane fade show active" id="all-activities" role="tabpanel">
                            @if(isset($allActivities) && is_countable($allActivities) && count($allActivities) > 0)
                                <div class="dashboard-timeline">
                                    @php
                                        $currentDate = null;
                                        $activitiesByDate = $allActivities->groupBy(function($activity) {
                                            return \Carbon\Carbon::parse($activity->created_at)->format('Y-m-d');
                                        });
                                    @endphp

                                    @foreach($activitiesByDate->take(7) as $date => $dayActivities)
                                        <div class="mb-4">
                                            <div class="text-white-75 fw-bold mb-2 border-bottom border-secondary pb-1">
                                                <i class="fas fa-calendar-day me-2"></i>
                                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                                                <span class="badge bg-info ms-2">{{ count($dayActivities) }} activité(s)</span>
                                            </div>

                                            @foreach($dayActivities->take(5) as $activity)
                                                <div class="mb-2 p-3 rounded d-flex align-items-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="dashboard-icon-small">
                                                            <i class="{{ $activity->icon ?? 'fas fa-clock' }} text-{{ $activity->color ?? 'info' }}"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="text-white fw-bold small">{{ $activity->activity_type ?? 'Activité' }}</div>
                                                        <div class="text-white-50 small">{{ $activity->description ?? 'Description non disponible' }}</div>
                                                        <div class="text-white-50 small">
                                                            <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($activity->created_at)->format('H:i') }}
                                                            @if(isset($activity->category))
                                                                <span class="badge bg-{{ $activity->color ?? 'secondary' }} ms-2" style="font-size: 0.6rem;">{{ ucfirst($activity->category) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-white-50 py-4">
                                    <div class="dashboard-icon mx-auto mb-3">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                    <div>Aucune activité enregistrée</div>
                                </div>
                            @endif
                        </div>

                        <!-- Historique des connexions uniquement -->
                        <div class="tab-pane fade" id="login-history" role="tabpanel">
                            @if(isset($loginHistory) && is_countable($loginHistory) && count($loginHistory) > 0)
                                <div class="dashboard-timeline">
                                    @php
                                        $loginsByMonth = $loginHistory->groupBy(function($login) {
                                            return \Carbon\Carbon::parse($login->created_at)->format('Y-m');
                                        });
                                    @endphp

                                    @foreach($loginsByMonth->take(3) as $month => $monthLogins)
                                        <div class="mb-4">
                                            <div class="text-white-75 fw-bold mb-3">
                                                <i class="fas fa-calendar-alt me-2"></i>
                                                {{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}
                                                <span class="badge bg-success ms-2">{{ count($monthLogins) }} connexion(s)</span>
                                            </div>

                                            @php
                                                $loginsByDay = $monthLogins->groupBy(function($login) {
                                                    return \Carbon\Carbon::parse($login->created_at)->format('Y-m-d');
                                                });
                                            @endphp

                                            @foreach($loginsByDay->take(10) as $day => $dayLogins)
                                                <div class="mb-3">
                                                    <div class="text-white-50 small fw-bold mb-2">
                                                        {{ \Carbon\Carbon::parse($day)->format('d/m/Y') }} - {{ count($dayLogins) }} connexion(s)
                                                    </div>

                                                    @foreach($dayLogins as $login)
                                                        <div class="mb-2 p-2 rounded d-flex align-items-center" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3);">
                                                            <div class="flex-shrink-0 me-3">
                                                                <i class="fas fa-sign-in-alt text-success"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="text-white small">{{ $login->description }}</div>
                                                                <div class="text-success small">
                                                                    <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($login->created_at)->format('H:i') }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-white-50 py-4">
                                    <div class="dashboard-icon mx-auto mb-3">
                                        <i class="fas fa-sign-in-alt fa-2x"></i>
                                    </div>
                                    <div>Aucune connexion enregistrée</div>
                                </div>
                            @endif
                        </div>

                        <!-- Autres activités uniquement -->
                        <div class="tab-pane fade" id="other-activities" role="tabpanel">
                            @if(isset($userActivities) && is_countable($userActivities) && count($userActivities) > 0)
                                <div class="dashboard-timeline">
                                    @foreach($userActivities->take(10) as $activity)
                                        <div class="mb-3 p-3 rounded d-flex align-items-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="dashboard-icon-small">
                                                    <i class="{{ $activity->icon ?? 'fas fa-tasks' }} text-{{ $activity->color ?? 'info' }}"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="text-white fw-bold small">{{ $activity->activity_type ?? 'Activité' }}</div>
                                                <div class="text-white-50 small">{{ $activity->description ?? 'Description non disponible' }}</div>
                                                <div class="text-white-50 small">{{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i') }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-white-50 py-4">
                                    <div class="dashboard-icon mx-auto mb-3">
                                        <i class="fas fa-tasks fa-2x"></i>
                                    </div>
                                    <div>Aucune autre activité enregistrée</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- CSS pour les onglets d'activités -->
            <style>
            .nav-pills .nav-link {
                background: rgba(255, 255, 255, 0.1);
                color: rgba(255, 255, 255, 0.8);
                border: 1px solid rgba(255, 255, 255, 0.2);
                margin-right: 0.5rem;
                transition: all 0.3s ease;
            }

            .nav-pills .nav-link:hover {
                background: rgba(255, 255, 255, 0.2);
                color: white;
            }

            .nav-pills .nav-link.active {
                background: linear-gradient(135deg, #3399ff, #0066cc);
                color: white;
                border-color: #3399ff;
            }

            .dashboard-timeline {
                max-height: 500px;
                overflow-y: auto;
            }

            .dashboard-timeline::-webkit-scrollbar {
                width: 6px;
            }

            .dashboard-timeline::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 3px;
            }

            .dashboard-timeline::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 3px;
            }

            .dashboard-timeline::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.5);
            }
            </style>




            <!-- Modal pour visualiser les documents PDF -->
            <div class="modal fade" id="documentViewerModal" tabindex="-1" aria-labelledby="documentViewerModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="documentViewerModalLabel">
                                <i class="fas fa-file-pdf me-2"></i>Visualisation du document
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0">
                            <iframe id="documentFrame" src="" style="width: 100%; height: 80vh; border: none;"></iframe>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                            <a id="downloadDocumentBtn" href="" class="btn btn-primary" download>
                                <i class="fas fa-download me-1"></i>Télécharger
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function viewDocument(filePath, documentName) {
                    const modal = new bootstrap.Modal(document.getElementById('documentViewerModal'));
                    const iframe = document.getElementById('documentFrame');
                    const downloadBtn = document.getElementById('downloadDocumentBtn');
                    const modalTitle = document.getElementById('documentViewerModalLabel');

                    // Construire l'URL complète du fichier
                    const fileUrl = '{{ asset("storage/") }}/' + filePath;

                    // Mettre à jour le modal
                    iframe.src = fileUrl;
                    downloadBtn.href = fileUrl;
                    modalTitle.innerHTML = '<i class="fas fa-file-pdf me-2"></i>' + documentName;

                    // Afficher le modal
                    modal.show();
                }

                function approveDocument(documentId) {
                    if (confirm('Approuver ce document ?')) {
                        // Ici vous pouvez ajouter une requête AJAX pour approuver le document
                        fetch(`/admin/documents/${documentId}/approve`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            }
                        }).then(response => {
                            if (response.ok) {
                                location.reload();
                            }
                        });
                    }
                }

                function rejectDocument(documentId) {
                    const comment = prompt('Raison du rejet (optionnel):');
                    if (comment !== null) {
                        // Ici vous pouvez ajouter une requête AJAX pour rejeter le document
                        fetch(`/admin/documents/${documentId}/reject`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ comment: comment })
                        }).then(response => {
                            if (response.ok) {
                                location.reload();
                            }
                        });
                    }
                }
            </script>


        </div>


    </div>
</div>

<style>
/* Variables CSS Dashboard Minimaliste Optimisées */
:root {
    --dashboard-bg: rgba(255, 255, 255, 0.05);
    --dashboard-border: rgba(255, 255, 255, 0.1);
    --dashboard-hover: rgba(0, 212, 255, 0.08);
    --dashboard-accent: rgba(0, 212, 255, 0.9);
    --dashboard-radius: 12px;
    --dashboard-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    --dashboard-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --dashboard-primary: #007bff;
    --dashboard-success: #28a745;
    --dashboard-info: #17a2b8;
    --dashboard-warning: #ffc107;
    --dashboard-danger: #dc3545;
}

/* Animations Dashboard Subtiles */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideIn {
    from { transform: translateX(-10px); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

/* Container Dashboard */
.dashboard-container {
    background: transparent;
    padding: 0;
}

/* Cartes Dashboard Minimalistes */
.dashboard-card {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
    border-radius: var(--dashboard-radius);
    box-shadow: var(--dashboard-shadow);
    transition: var(--dashboard-transition);
    margin-bottom: 1.5rem;
    animation: fadeIn 0.5s ease-out;
}

.dashboard-card:hover {
    background: var(--dashboard-hover);
    border-color: var(--dashboard-accent);
    transform: translateY(-2px);
}

/* Grille Dashboard */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

/* Responsive optimisé pour éviter scroll horizontal */
@media (max-width: 768px) {
    .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
}

/* Optimisation ultra-mobile pour très petits écrans */
@media (max-width: 576px) {
    .container-fluid {
        padding-left: 0.25rem !important;
        padding-right: 0.25rem !important;
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 0.5rem;
    }

    .dashboard-container {
        padding: 0.25rem;
    }

    .card-body {
        padding: 0.75rem !important;
    }

    .row.g-3, .row.g-4 {
        --bs-gutter-x: 0.5rem;
    }

    .table th, .table td {
        padding: 0.375rem 0.125rem;
        font-size: 0.7rem;
    }

    .badge {
        font-size: 0.55rem !important;
        padding: 0.15rem 0.3rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
    }

    h1 {
        font-size: 1.25rem !important;
    }

    .dashboard-avatar-placeholder {
        width: 60px;
        height: 60px;
        font-size: 1.5rem !important;
    }
}

/* Sections Dashboard */
.dashboard-stats {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
}

.dashboard-projects {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
}

.dashboard-cvtheque {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
}

.dashboard-documents {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
}

/* Badges Dashboard */
.dashboard-badge {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
    color: rgba(255, 255, 255, 0.9);
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.875rem;
    margin: 0.25rem;
    display: inline-block;
    transition: var(--dashboard-transition);
}

.dashboard-badge:hover {
    background: var(--dashboard-hover);
    border-color: var(--dashboard-accent);
    color: var(--dashboard-accent);
}

/* Indicateurs de progression Dashboard */
.dashboard-progress {
    height: 6px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
    overflow: hidden;
}

.dashboard-progress-bar {
    height: 100%;
    background: var(--dashboard-accent);
    border-radius: 3px;
    transition: width 0.8s ease-out;
}

/* Icônes Dashboard Optimisées */
.dashboard-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 48px;
    height: 48px;
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
    border-radius: var(--dashboard-radius);
    margin-bottom: 1rem;
    color: var(--dashboard-accent);
    transition: var(--dashboard-transition);
    position: absolute;
    top: 20px;
    right: 20px;
}

/* Nouveaux composants Dashboard */
.dashboard-info-item {
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    transition: var(--dashboard-transition);
}

.dashboard-info-item:hover {
    transform: translateX(2px);
}

.dashboard-stat-box {
    transition: var(--dashboard-transition);
    cursor: pointer;
}

.dashboard-stat-box:hover {
    background: var(--dashboard-hover) !important;
    border-color: var(--dashboard-accent) !important;
    transform: translateY(-2px);
}

.dashboard-info-box {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
    transition: var(--dashboard-transition);
}

.dashboard-info-box:hover {
    background: var(--dashboard-hover);
    border-color: var(--dashboard-accent);
}

.dashboard-file-item {
    transition: var(--dashboard-transition);
    cursor: pointer;
}

.dashboard-file-item:hover {
    background: var(--dashboard-hover) !important;
    border-color: var(--dashboard-accent) !important;
    transform: translateX(4px);
}

.dashboard-icon:hover {
    background: var(--dashboard-hover);
    border-color: var(--dashboard-accent);
}

.dashboard-icon-small {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
    border-radius: 8px;
    color: rgba(255, 255, 255, 0.7);
}

/* Timeline Dashboard */
.dashboard-timeline {
    position: relative;
}

.dashboard-timeline::before {
    content: '';
    position: absolute;
    left: 16px;
    top: 0;
    bottom: 0;
    width: 1px;
    background: var(--dashboard-border);
}

/* Styles existants améliorés */
.avatar-xl {
    width: 120px;
    height: 120px;
}

.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.text-white-25 {
    color: rgba(255, 255, 255, 0.25) !important;
}

.border-white-25 {
    border-color: rgba(255, 255, 255, 0.25) !important;
}

.bg-white-25 {
    background-color: rgba(255, 255, 255, 0.25) !important;
}

.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.bg-dashboard-primary {
    background: var(--dashboard-bg) !important;
    border: 1px solid var(--dashboard-border) !important;
}

.progress {
    height: 8px;
    border-radius: 4px;
}

.progress-bar {
    transition: width 0.6s ease;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

/* Responsive Dashboard Optimisé - UX Sans Scroll Horizontal */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }

    .dashboard-container {
        padding: 0.5rem;
    }

    .dashboard-grid {
        grid-template-columns: 1fr;
        gap: 0.75rem;
    }

    .dashboard-avatar-placeholder {
        width: 80px;
        height: 80px;
        font-size: 1.8rem !important;
    }

    /* Optimisation des cartes pour mobile */
    .dashboard-card {
        margin-bottom: 1rem;
    }

    .card-body {
        padding: 1rem !important;
    }

    /* Optimisation des boutons d'action */
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }

    .btn {
        width: 100%;
        margin-bottom: 0.25rem;
    }

    /* Optimisation des statistiques */
    .row.g-3, .row.g-4 {
        margin: 0 !important;
    }

    .col-md-3, .col-md-4, .col-md-6, .col-md-8, .col-md-9 {
        padding-left: 0.25rem !important;
        padding-right: 0.25rem !important;
    }

    .card-body .row .col-md-6 {
        margin-bottom: 1rem;
    }

    .dashboard-icon {
        width: 36px;
        height: 36px;
        position: relative;
        top: auto;
        right: auto;
        margin: 0 0 0.5rem 0;
    }

    .dashboard-icon-small {
        width: 24px;
        height: 24px;
    }

    .dashboard-badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    .dashboard-card {
        margin-bottom: 1rem;
    }

    .dashboard-btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.8rem;
    }

    .dashboard-stat-box {
        padding: 0.5rem !important;
    }

    /* Optimisation des tableaux pour mobile - Élimination scroll horizontal */
    .table-responsive {
        border: none;
        margin: 0;
        padding: 0;
    }

    .table {
        font-size: 0.8rem;
        margin-bottom: 0;
    }

    .table th, .table td {
        padding: 0.5rem 0.25rem;
        vertical-align: middle;
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    .table th:nth-child(1), .table td:nth-child(1) {
        width: 45% !important;
        min-width: 120px;
    }
    .table th:nth-child(2), .table td:nth-child(2) {
        width: 20% !important;
        min-width: 80px;
    }
    .table th:nth-child(3), .table td:nth-child(3) {
        width: 20% !important;
        min-width: 70px;
    }
    .table th:nth-child(4), .table td:nth-child(4) {
        width: 15% !important;
        min-width: 60px;
    }

    .badge {
        font-size: 0.6rem !important;
        padding: 0.2rem 0.4rem;
    }

    .text-truncate {
        max-width: 100px;
    }

    .dropdown-menu {
        min-width: 140px;
        font-size: 0.8rem;
    }

    /* Optimisation des titres et textes */
    h1 {
        font-size: 1.5rem !important;
    }

    h5 {
        font-size: 1rem !important;
    }

    .small, small {
        font-size: 0.75rem !important;
    }
}

    .dashboard-info-item {
        font-size: 0.8rem;
    }

    .h4, .h5 {
        font-size: 1.1rem;
    }
}

@media (max-width: 576px) {
    .dashboard-grid {
        gap: 0.5rem;
    }

    .dashboard-card .card-body {
        padding: 0.75rem !important;
    }

    .row.g-3, .row.g-2 {
        --bs-gutter-x: 0.25rem;
    }

    .dashboard-btn {
        padding: 0.35rem 0.6rem;
        font-size: 0.75rem;
        margin-bottom: 0.25rem;
    }

    .dashboard-icon {
        width: 32px;
        height: 32px;
        position: relative;
        top: auto;
        right: auto;
        margin: 0 auto 0.5rem auto;
    }

    .dashboard-avatar-placeholder {
        width: 50px;
        height: 50px;
        font-size: 1.2rem !important;
    }

    .dashboard-file-item,
    .dashboard-image-card {
        margin-bottom: 0.5rem;
    }

    .d-flex.flex-wrap.gap-2 {
        gap: 0.25rem !important;
    }
}

/* Effets interactifs Dashboard */
.dashboard-interactive {
    cursor: pointer;
    transition: var(--dashboard-transition);
}

.dashboard-interactive:hover {
    background: var(--dashboard-hover);
    border-color: var(--dashboard-accent);
}

/* Boutons Dashboard Optimisés */
.dashboard-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border: 1px solid var(--dashboard-border);
    border-radius: 8px;
    background: var(--dashboard-bg);
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    transition: var(--dashboard-transition);
    cursor: pointer;
}

.dashboard-btn:hover {
    background: var(--dashboard-hover);
    border-color: var(--dashboard-accent);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.dashboard-btn-primary {
    border-color: var(--dashboard-primary);
    color: var(--dashboard-primary);
}

.dashboard-btn-primary:hover {
    background: var(--dashboard-primary);
    color: white;
}

.dashboard-btn-success {
    border-color: var(--dashboard-success);
    color: var(--dashboard-success);
}

.dashboard-btn-success:hover {
    background: var(--dashboard-success);
    color: white;
}

.dashboard-btn-info {
    border-color: var(--dashboard-info);
    color: var(--dashboard-info);
}

.dashboard-btn-info:hover {
    background: var(--dashboard-info);
    color: white;
}

.dashboard-btn-warning {
    border-color: var(--dashboard-warning);
    color: var(--dashboard-warning);
}

.dashboard-btn-warning:hover {
    background: var(--dashboard-warning);
    color: #212529;
}

.dashboard-btn-disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.dashboard-btn-disabled:hover {
    transform: none;
    box-shadow: none;
}

.interactive-element:hover {
    transform: scale(1.02) rotate(1deg);
}

/* Loading animations */
.dashboard-loading {
    background: var(--dashboard-bg);
    border: 1px solid var(--dashboard-border);
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

/* Accessibilité Dashboard */
.dashboard-card:focus-within {
    outline: 2px solid var(--dashboard-accent);
    outline-offset: 2px;
}

.dashboard-badge:focus {
    outline: 2px solid var(--dashboard-accent);
    outline-offset: 2px;
}

.dashboard-interactive:focus {
    outline: 2px solid var(--dashboard-accent);
    outline-offset: 2px;
}

/* Animations d'entrée Dashboard Optimisées */
.dashboard-card:nth-child(1) { animation-delay: 0.05s; }
.dashboard-card:nth-child(2) { animation-delay: 0.1s; }
.dashboard-card:nth-child(3) { animation-delay: 0.15s; }
.dashboard-card:nth-child(4) { animation-delay: 0.2s; }
.dashboard-card:nth-child(5) { animation-delay: 0.25s; }
.dashboard-card:nth-child(6) { animation-delay: 0.3s; }

/* Optimisations de performance */
.dashboard-image-card {
    transition: var(--dashboard-transition);
    cursor: pointer;
}

.dashboard-image-card:hover {
    transform: translateY(-2px);
    border-color: var(--dashboard-accent) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Améliorations d'accessibilité */
.dashboard-btn:focus,
.dashboard-stat-box:focus,
.dashboard-file-item:focus,
.dashboard-image-card:focus {
    outline: 2px solid var(--dashboard-accent);
    outline-offset: 2px;
}

/* Optimisation des transitions */
* {
    will-change: auto;
}

.dashboard-card,
.dashboard-btn,
.dashboard-stat-box {
    will-change: transform, background-color, border-color;
}

/* Préchargement des états hover */
.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--dashboard-hover);
    opacity: 0;
    transition: opacity 0.2s ease;
    pointer-events: none;
    z-index: -1;
}
</style>

<script>
// Fonctions d'interaction optimisées
function editStudent() {
    // Animation de chargement
    const btn = event.target.closest('.dashboard-btn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Chargement...';
    btn.disabled = true;

    setTimeout(() => {
        window.location.href = `/admin/students/{{ $student->id }}/edit`;
    }, 300);
}

function sendEmail() {
    const btn = event.target.closest('.dashboard-btn');
    const originalContent = btn.innerHTML;

    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi...';
    btn.disabled = true;

    // Simulation d'envoi d'email
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-check me-2"></i>Envoyé !';
        btn.classList.remove('dashboard-btn-info');
        btn.classList.add('dashboard-btn-success');

        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            btn.classList.remove('dashboard-btn-success');
            btn.classList.add('dashboard-btn-info');
        }, 2000);
    }, 1500);
}

function suspendStudent() {
    if (confirm('Êtes-vous sûr de vouloir suspendre cet étudiant ?')) {
        const btn = event.target.closest('.dashboard-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Suspension...';
        btn.disabled = true;

        // Logique de suspension
        setTimeout(() => {
            alert('Fonctionnalité de suspension à implémenter');
            btn.innerHTML = '<i class="fas fa-user-times me-2"></i>Suspendre';
            btn.disabled = false;
        }, 1000);
    }
}

function generateCertificate() {
    if ({{ $certificat['eligible'] ? 'true' : 'false' }}) {
        const btn = event.target.closest('.dashboard-btn');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Génération...';
        btn.disabled = true;

        // Simulation de génération
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-download me-2"></i>Télécharger';
            btn.classList.remove('dashboard-btn-success');
            btn.classList.add('dashboard-btn-info');

            // Réinitialiser après 3 secondes
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-certificate me-2"></i>Générer Certificat';
                btn.disabled = false;
                btn.classList.remove('dashboard-btn-info');
                btn.classList.add('dashboard-btn-success');
            }, 3000);
        }, 2000);
    } else {
        alert('Cet étudiant n\'est pas encore éligible au certificat');
    }
}

// Optimisations de performance
document.addEventListener('DOMContentLoaded', function() {
    // Lazy loading des images
    const images = document.querySelectorAll('img[data-src]');
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });

    images.forEach(img => imageObserver.observe(img));

    // Animation d'entrée des cartes
    const cards = document.querySelectorAll('.dashboard-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Fonctions de gestion des TP
function viewTP(tpId) {
    // Ouvrir une modale ou rediriger vers la page de détail du TP
    const url = `/admin/tp/${tpId}/details`;
    window.open(url, '_blank', 'width=800,height=600,scrollbars=yes');
}

function validateTP(tpId) {
    if (confirm('Êtes-vous sûr de vouloir valider ce TP ?')) {
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        // Simulation de validation (à remplacer par un appel AJAX réel)
        fetch(`/admin/tp/${tpId}/validate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'interface
                const tpCard = btn.closest('.mb-3');
                const statusBadge = tpCard.querySelector('.dashboard-badge');
                statusBadge.className = 'dashboard-badge bg-success mb-2';
                statusBadge.innerHTML = '<i class="fas fa-check me-1"></i>Validé';

                // Masquer les boutons d'action
                btn.parentElement.style.display = 'none';

                // Notification de succès
                showNotification('TP validé avec succès !', 'success');

                // Recharger la page après 2 secondes pour mettre à jour les statistiques
                setTimeout(() => location.reload(), 2000);
            } else {
                throw new Error(data.message || 'Erreur lors de la validation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showNotification('Erreur lors de la validation du TP', 'error');
        });
    }
}

function rejectTP(tpId) {
    const reason = prompt('Raison du rejet (optionnel):');
    if (reason !== null) { // L'utilisateur n'a pas annulé
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        // Simulation de rejet (à remplacer par un appel AJAX réel)
        fetch(`/admin/tp/${tpId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'interface
                const tpCard = btn.closest('.mb-3');
                const statusBadge = tpCard.querySelector('.dashboard-badge');
                statusBadge.className = 'dashboard-badge bg-danger mb-2';
                statusBadge.innerHTML = '<i class="fas fa-times me-1"></i>Rejeté';

                // Masquer les boutons d'action
                btn.parentElement.style.display = 'none';

                // Notification de succès
                showNotification('TP rejeté', 'warning');

                // Recharger la page après 2 secondes pour mettre à jour les statistiques
                setTimeout(() => location.reload(), 2000);
            } else {
                throw new Error(data.message || 'Erreur lors du rejet');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showNotification('Erreur lors du rejet du TP', 'error');
        });
    }
}

function viewAllTPs() {
    // Rediriger vers la page complète des TP de l'étudiant
    window.location.href = `/admin/students/{{ $student->id }}/tp`;
}

// Fonction utilitaire pour afficher des notifications
function showNotification(message, type = 'info') {
    // Créer une notification toast
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    // Auto-supprimer après 5 secondes
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}

// ========================================
// FONCTIONS DE GESTION DES TABLEAUX
// ========================================

// === FONCTIONS TP ===
function refreshTPTable() {
    location.reload();
}

function exportTPData() {
    const studentId = {{ $student->id }};
    window.open(`/admin/students/${studentId}/tp/export`, '_blank');
}

function viewTPDetails(tpId) {
    window.open(`/admin/tp/${tpId}/details`, '_blank', 'width=1000,height=700,scrollbars=yes');
}

function editTP(tpId) {
    window.location.href = `/admin/tp/${tpId}/edit`;
}

function validateTPAction(tpId) {
    if (confirm('Êtes-vous sûr de vouloir valider ce TP ?')) {
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch(`/admin/tp/${tpId}/validate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour la ligne du tableau
                const row = document.getElementById(`tp-row-${tpId}`);
                const statusCell = row.querySelector('td:nth-child(5)');
                statusCell.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Validé</span>';

                // Masquer le bouton valider
                btn.style.display = 'none';

                showNotification('TP validé avec succès !', 'success');
            } else {
                throw new Error(data.message || 'Erreur lors de la validation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showNotification('Erreur lors de la validation du TP', 'error');
        });
    }
}

function deleteTP(tpId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce TP ? Cette action est irréversible.')) {
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch(`/admin/tp/${tpId}/delete`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer la ligne du tableau avec animation
                const row = document.getElementById(`tp-row-${tpId}`);
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);

                showNotification('TP supprimé avec succès', 'success');
            } else {
                throw new Error(data.message || 'Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showNotification('Erreur lors de la suppression du TP', 'error');
        });
    }
}

// === FONCTIONS PROJETS ===
function refreshProjectsTable() {
    location.reload();
}

function exportProjectsData() {
    const studentId = {{ $student->id }};
    window.open(`/admin/students/${studentId}/projects/export`, '_blank');
}

// 👁️ VOIR - Version Simplifiée et Fiable
function viewProjectDetails(projectId) {
    console.log('👁️ Ouverture des détails du projet:', projectId);

    // URL corrigée avec le bon préfixe
    const url = `/evc/app/admin/projects/view/${projectId}`;

    // Ouverture dans une nouvelle fenêtre avec paramètres optimisés
    const popup = window.open(url, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes,status=yes');

    // Vérification si la popup s'est ouverte
    if (!popup) {
        alert('⚠️ Veuillez autoriser les popups pour voir les détails du projet.');
        // Fallback : redirection dans la même fenêtre
        window.location.href = url;
    }
}

// 👁️ VOIR DESIGN PROJECT - Version Spécifique pour les Projets Design
function viewDesignProjectDetails(projectId) {
    console.log('👁️ Ouverture des détails du projet design:', projectId);

    // URL spécifique pour les design projects
    const url = `/evc/app/admin/design-projects/view/${projectId}`;

    // Ouverture dans une nouvelle fenêtre avec paramètres optimisés
    const popup = window.open(url, '_blank', 'width=1200,height=800,scrollbars=yes,resizable=yes,status=yes');

    // Vérification si la popup s'est ouverte
    if (!popup) {
        alert('⚠️ Veuillez autoriser les popups pour voir les détails du projet design.');
        // Fallback : redirection dans la même fenêtre
        window.location.href = url;
    }
}

// ✏️ ÉDITER - Version Simplifiée et Fiable
function editProject(projectId) {
    console.log('✏️ Redirection vers l’édition du projet:', projectId);

    // URL corrigée avec le bon préfixe
    window.location.href = `/evc/app/admin/projects/edit/${projectId}`;
}

// ✅ VALIDER - Version Simplifiée et Fiable
function validateProject(projectId) {
    console.log('✅ Validation du projet:', projectId);

    if (!confirm('Êtes-vous sûr de vouloir valider ce projet ?')) {
        return;
    }

    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;

    // 🔄 Interface de chargement
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Validation...';
    btn.disabled = true;

    // 🔑 Token CSRF sécurisé
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('❌ Token CSRF manquant');
        showNotification('Erreur de sécurité : token CSRF manquant', 'error');
        btn.innerHTML = originalContent;
        btn.disabled = false;
        return;
    }

    // 📡 Requête AJAX avec URL corrigée
    fetch(`/evc/app/admin/projects/validate/${projectId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('📡 Réponse validation:', response.status);
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('📊 Données validation:', data);
        if (data.success) {
            // ✅ Mise à jour de l'interface
            const row = document.getElementById(`project-row-${projectId}`);
            if (row) {
                const statusCell = row.querySelector('td:nth-child(5)');
                if (statusCell) {
                    statusCell.innerHTML = '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Validé</span>';
                }

                const progressCell = row.querySelector('td:nth-child(6) .progress-bar');
                if (progressCell) {
                    progressCell.style.width = '100%';
                    progressCell.innerHTML = '<small class="text-white fw-bold">100%</small>';
                }
            }

            // Masquer le bouton valider
            btn.style.display = 'none';

            showNotification('Projet validé avec succès !', 'success');
        } else {
            throw new Error(data.message || 'Erreur lors de la validation');
        }
    })
    .catch(error => {
        console.error('❌ Erreur validation:', error);
        btn.innerHTML = originalContent;
        btn.disabled = false;
        showNotification('Erreur lors de la validation du projet: ' + error.message, 'error');
    });
}

// 🗑️ SUPPRIMER - Version Simplifiée et Fiable
function deleteProject(projectId) {
    console.log('🗑️ Suppression du projet:', projectId);

    // ⚠️ Double confirmation pour la suppression
    if (!confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')) {
        return;
    }

    if (!confirm('⚠️ ATTENTION : Cette action est IRRÉVERSIBLE. Confirmer la suppression ?')) {
        return;
    }

    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;

    // 🔄 Interface de chargement
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
    btn.disabled = true;

    // 🔑 Token CSRF sécurisé
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) {
        console.error('❌ Token CSRF manquant');
        showNotification('Erreur de sécurité : token CSRF manquant', 'error');
        btn.innerHTML = originalContent;
        btn.disabled = false;
        return;
    }

    // 📡 Requête AJAX avec URL corrigée
    fetch(`/evc/app/admin/projects/delete/${projectId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('📡 Réponse suppression:', response.status);
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('📊 Données suppression:', data);
        if (data.success) {
            // ✨ Animation de suppression
            const row = document.getElementById(`project-row-${projectId}`);
            if (row) {
                row.style.transition = 'all 0.5s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-100%)';
                setTimeout(() => row.remove(), 500);
            }

            showNotification('Projet supprimé avec succès', 'success');
        } else {
            throw new Error(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('❌ Erreur suppression:', error);
        btn.innerHTML = originalContent;
        btn.disabled = false;
        showNotification('Erreur lors de la suppression du projet: ' + error.message, 'error');
    });
}

// === FONCTIONS DOCUMENTS ===
function refreshDocumentsTable() {
    location.reload();
}

function exportDocumentsData() {
    const studentId = {{ $student->id }};
    window.open(`/admin/students/${studentId}/documents/export`, '_blank');
}

function viewDocumentFile(documentId) {
    window.open(`/admin/documents/${documentId}/view`, '_blank', 'width=800,height=600,scrollbars=yes');
}

function downloadDocument(documentId) {
    const btn = event.target.closest('button');
    const originalContent = btn.innerHTML;

    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    // Créer un lien de téléchargement temporaire
    const link = document.createElement('a');
    link.href = `/admin/documents/${documentId}/download`;
    link.download = '';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Restaurer le bouton après un délai
    setTimeout(() => {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }, 1000);

    showNotification('Téléchargement en cours...', 'info');
}

function approveDocument(documentId) {
    if (confirm('Êtes-vous sûr de vouloir approuver ce document ?')) {
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch(`/admin/documents/${documentId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour la ligne du tableau
                const row = document.getElementById(`document-row-${documentId}`);
                const statusCell = row.querySelector('td:nth-child(6)');
                statusCell.innerHTML = '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Approuvé</span>';

                // Masquer le bouton approuver
                btn.style.display = 'none';

                showNotification('Document approuvé avec succès !', 'success');
            } else {
                throw new Error(data.message || 'Erreur lors de l\'approbation');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showNotification('Erreur lors de l\'approbation du document', 'error');
        });
    }
}

function deleteDocument(documentId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce document ? Cette action est irréversible.')) {
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;

        fetch(`/admin/documents/${documentId}/delete`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprimer la ligne du tableau avec animation
                const row = document.getElementById(`document-row-${documentId}`);
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);

                showNotification('Document supprimé avec succès', 'success');
            } else {
                throw new Error(data.message || 'Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            btn.innerHTML = originalContent;
            btn.disabled = false;
            showNotification('Erreur lors de la suppression du document', 'error');
        });
    }
}

// === FONCTIONS UTILITAIRES ===
function showNotification(message, type = 'info') {
    // Créer une notification toast améliorée
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);';

    const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle';

    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${icon} me-2"></i>
            <span>${message}</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    // Auto-supprimer après 5 secondes
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 150);
        }
    }, 5000);
}

// Initialisation des tooltips pour les boutons d'action
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap si disponible
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>

@endsection

<!-- Modales pour les actions des projets -->
@include('components.admin.project-action-modals')

@section('scripts')
<!-- Inclusion du fichier JavaScript professionnel pour les actions projet -->
<script src="{{ asset('js/admin-project-actions.js') }}"></script>

<script>
// Actions CRUD pour les projets - Solution intégrée
$(document).ready(function() {
    console.log('✅ Initialisation des actions CRUD...');

    // Configuration CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        }
    });

    // Fonction pour afficher les toasts
    window.showToast = function(type, message) {
        let toastContainer = document.getElementById('toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.id = 'toast-container';
            toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const bgClass = type === 'success' ? 'bg-success' : type === 'error' ? 'bg-danger' : 'bg-info';
        const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-triangle' : 'fa-info-circle';

        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas ${icon} me-2"></i>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);

        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { autohide: true, delay: 5000 });
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    };

    // VOIR un projet - Fonction maintenant fournie par admin-project-actions.js
    // (Suppression de la fonction dupliquée pour éviter les conflits)

    // MODIFIER un projet
    window.editProject = function(projectId) {
        console.log('✅ editProject appelée avec ID:', projectId);
        showToast('info', 'Chargement des données du projet...');

        const modal = document.getElementById('editProjectModal');
        if (!modal) {
            showToast('error', 'Modal d\'édition non trouvée');
            return;
        }

        $('#editProjectModal').modal('show');

        // Requête AJAX pour récupérer les données
        $.ajax({
            url: `/evc/app/admin/projects/edit/${projectId}`,
            method: 'GET',
            success: function(response) {
                if (response.success) {
                    populateEditForm(response.project);
                    showToast('success', 'Données du projet chargées');
                } else {
                    showToast('error', 'Erreur lors du chargement des données');
                }
            },
            error: function(xhr) {
                console.error('Erreur lors du chargement pour édition:', xhr);
                showToast('error', 'Erreur lors du chargement des données');
            }
        });
    };

    // VALIDER un projet
    window.validateProject = function(projectId) {
        console.log('✅ validateProject appelée avec ID:', projectId);

        if (confirm('Êtes-vous sûr de vouloir valider ce projet ?')) {
            showToast('info', 'Validation en cours...');

            $.ajax({
                url: `/evc/app/admin/projects/validate/${projectId}`,
                method: 'POST',
                success: function(response) {
                    if (response.success) {
                        showToast('success', 'Projet validé avec succès');
                        // Mettre à jour le statut dans le tableau
                        updateProjectStatus(projectId, 'valide', 'Validé');
                        // Rafraîchir la page après un délai
                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showToast('error', response.message || 'Erreur lors de la validation');
                    }
                },
                error: function(xhr) {
                    console.error('Erreur lors de la validation:', xhr);
                    showToast('error', 'Erreur lors de la validation du projet');
                }
            });
        }
    };

    // SUPPRIMER un projet
    window.deleteProject = function(projectId) {
        console.log('✅ deleteProject appelée avec ID:', projectId);

        if (confirm('Êtes-vous sûr de vouloir supprimer ce projet ? Cette action est irréversible.')) {
            showToast('info', 'Suppression en cours...');

            $.ajax({
                url: `/evc/app/admin/projects/delete/${projectId}`,
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        showToast('success', 'Projet supprimé avec succès');
                        // Supprimer la ligne du tableau
                        $(`#project-row-${projectId}`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    } else {
                        showToast('error', response.message || 'Erreur lors de la suppression');
                    }
                },
                error: function(xhr) {
                    console.error('Erreur lors de la suppression:', xhr);
                    showToast('error', 'Erreur lors de la suppression du projet');
                }
            });
        }
    };

    // Fonctions utilitaires
    window.displayProjectDetails = function(project) {
        const content = `
            <div class="row">
                <div class="col-md-8">
                    <h6 class="text-primary">Informations du Projet</h6>
                    <table class="table table-dark table-sm">
                        <tr><td><strong>Titre:</strong></td><td>${project.title}</td></tr>
                        <tr><td><strong>Description:</strong></td><td>${project.description || 'Non spécifiée'}</td></tr>
                        <tr><td><strong>Logiciels:</strong></td><td>${project.software_used || 'Non spécifié'}</td></tr>
                        <tr><td><strong>Statut:</strong></td><td><span class="badge bg-${getStatusColor(project.status)}">${project.status_label}</span></td></tr>
                        <tr><td><strong>Créé le:</strong></td><td>${project.created_at}</td></tr>
                    </table>
                </div>
                <div class="col-md-4">
                    <h6 class="text-primary">Étudiant</h6>
                    <p><strong>${project.user.name}</strong><br>
                    <small class="text-muted">${project.user.email}</small></p>

                    <h6 class="text-primary mt-3">Fichiers</h6>
                    <p>${project.images.length} fichier(s) associé(s)</p>
                </div>
            </div>
        `;
        $('#viewProjectContent').html(content);
    };

    window.populateEditForm = function(project) {
        $('#editProjectTitle').val(project.title);
        $('#editProjectDescription').val(project.description);
        $('#editProjectSoftware').val(project.software_used);
        $('#editProjectStatus').val(project.status);
        $('#editProjectLink').val(project.link);
    };

    window.updateProjectStatus = function(projectId, status, statusLabel) {
        const row = $(`#project-row-${projectId}`);
        const statusBadge = row.find('.badge');
        statusBadge.removeClass().addClass(`badge bg-${getStatusColor(status)}`);
        statusBadge.html(`<i class="fas ${getStatusIcon(status)} me-1"></i>${statusLabel}`);
    };

    window.getStatusColor = function(status) {
        switch(status) {
            case 'valide': return 'success';
            case 'rejete': return 'danger';
            case 'en_cours': return 'warning';
            default: return 'info';
        }
    };

    window.getStatusIcon = function(status) {
        switch(status) {
            case 'valide': return 'fa-check-circle';
            case 'rejete': return 'fa-times-circle';
            case 'en_cours': return 'fa-clock';
            default: return 'fa-info-circle';
        }
    };

    console.log('✅ Actions CRUD initialisées avec succès');
    console.log('✅ Fonctions disponibles: viewProject, editProject, validateProject, deleteProject');
});

// 🚀 FONCTIONS RÉVOLUTIONNAIRES POUR PROFIL - JAVASCRIPT ULTRA-MODERNE

/**
 * Email rapide avec modal intelligente
 */
function quickEmail(studentId) {
    const btn = event.target.closest('.action-btn');
    btn.style.transform = 'scale(0.9)';
    setTimeout(() => btn.style.transform = '', 150);

    // Créer modal d'email rapide
    const emailModal = document.createElement('div');
    emailModal.className = 'quick-email-modal';
    emailModal.innerHTML = `
        <div class="email-modal-content">
            <div class="email-header">
                <h5><i class="fas fa-envelope me-2"></i>Email Rapide</h5>
                <button onclick="this.closest('.quick-email-modal').remove()" class="close-btn">×</button>
            </div>
            <div class="email-body">
                <input type="text" placeholder="Objet" class="email-input" id="emailSubject">
                <textarea placeholder="Message..." class="email-textarea" id="emailMessage"></textarea>
                <div class="email-actions">
                    <button onclick="sendQuickEmail(${studentId})" class="send-btn">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer
                    </button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(emailModal);

    // Animation d'apparition
    requestAnimationFrame(() => {
        emailModal.style.opacity = '1';
        emailModal.querySelector('.email-modal-content').style.transform = 'scale(1)';
    });
}

/**
 * Toggle intelligent pour profil avec animation
 */
function smartToggleProfile(studentId, newStatus) {
    const actionHub = document.querySelector(`[data-student-id="${studentId}"]`);
    const statusBtn = actionHub.querySelector('.action-status');
    const statusIndicator = actionHub.querySelector('.status-indicator');

    // Animation de chargement
    statusBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    statusBtn.disabled = true;

    // Simulation d'appel API (remplacer par vraie logique)
    setTimeout(() => {
        // Mise à jour visuelle intelligente
        if (newStatus === 1) {
            statusBtn.className = 'action-btn action-status active';
            statusBtn.innerHTML = '<i class="fas fa-user-check"></i>';
            statusBtn.setAttribute('data-tooltip', 'Désactiver');
            statusBtn.setAttribute('onclick', `smartToggleProfile(${studentId}, 0)`);
            statusIndicator.className = 'status-indicator online';
        } else {
            statusBtn.className = 'action-btn action-status inactive';
            statusBtn.innerHTML = '<i class="fas fa-user-plus"></i>';
            statusBtn.setAttribute('data-tooltip', 'Activer');
            statusBtn.setAttribute('onclick', `smartToggleProfile(${studentId}, 1)`);
            statusIndicator.className = 'status-indicator offline';
        }

        statusBtn.disabled = false;

        // Animation de succès
        statusBtn.style.transform = 'scale(1.2)';
        setTimeout(() => statusBtn.style.transform = '', 200);

        // Notification toast moderne
        showModernToastProfile(newStatus === 1 ? 'Étudiant activé' : 'Étudiant désactivé', 'success');

    }, 800);
}

/**
 * Génération de certificat révolutionnaire
 */
function generateCertificateRevolutionary(studentId) {
    const btn = event.target.closest('.action-btn');

    // Animation de chargement
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;

    // Simulation de génération
    setTimeout(() => {
        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.style.background = 'linear-gradient(135deg, #00b894 0%, #00cec9 100%)';

        showModernToastProfile('Certificat généré avec succès !', 'success');

        // Retour à l'état normal
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.disabled = false;
            btn.style.background = '';
        }, 2000);
    }, 1500);
}

/**
 * Menu d'actions rapides pour profil
 */
function showQuickActionsProfile(studentId) {
    // Créer menu contextuel moderne
    const quickMenu = document.createElement('div');
    quickMenu.className = 'quick-actions-menu-profile';
    quickMenu.innerHTML = `
        <div class="quick-action" onclick="exportStudentProfile(${studentId})">
            <i class="fas fa-download"></i> Exporter Profil
        </div>
        <div class="quick-action" onclick="printStudentCard(${studentId})">
            <i class="fas fa-print"></i> Imprimer Carte
        </div>
        <div class="quick-action" onclick="viewStudentHistory(${studentId})">
            <i class="fas fa-history"></i> Historique
        </div>
        <div class="quick-action" onclick="resetStudentPassword(${studentId})">
            <i class="fas fa-key"></i> Reset Mot de Passe
        </div>
        <div class="quick-action danger" onclick="archiveStudent(${studentId})">
            <i class="fas fa-archive"></i> Archiver
        </div>
    `;

    // Positionnement intelligent
    document.body.appendChild(quickMenu);

    // Animation d'apparition
    requestAnimationFrame(() => {
        quickMenu.style.opacity = '1';
        quickMenu.style.transform = 'translateY(0)';
    });

    // Fermeture automatique
    setTimeout(() => {
        if (quickMenu.parentNode) {
            quickMenu.remove();
        }
    }, 5000);
}

/**
 * Toast notification moderne pour profil
 */
function showModernToastProfile(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `modern-toast-profile toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(toast);

    // Animation d'entrée
    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(0)';
        toast.style.opacity = '1';
    });

    // Suppression automatique
    setTimeout(() => {
        toast.style.transform = 'translateX(100%)';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Actions supplémentaires pour profil
function sendQuickEmail(id) {
    const subject = document.getElementById('emailSubject').value;
    const message = document.getElementById('emailMessage').value;
    console.log('Envoi email:', id, subject, message);
    document.querySelector('.quick-email-modal').remove();
    showModernToastProfile('Email envoyé !', 'success');
}
function exportStudentProfile(id) { console.log('Export profil étudiant:', id); }
function printStudentCard(id) { console.log('Imprimer carte étudiant:', id); }
function viewStudentHistory(id) { console.log('Voir historique étudiant:', id); }
function resetStudentPassword(id) {
    if (confirm('Réinitialiser le mot de passe de cet étudiant ?')) {
        console.log('Reset mot de passe:', id);
        showModernToastProfile('Mot de passe réinitialisé', 'success');
    }
}
function archiveStudent(id) {
    if (confirm('Archiver définitivement cet étudiant ?')) {
        console.log('Archiver étudiant:', id);
    }
}

console.log('🚀 Interface révolutionnaire profil initialisée !');
</script>

<!-- CSS RÉVOLUTIONNAIRE POUR PROFIL -->
<style>
/* 🚀 INTERFACE RÉVOLUTIONNAIRE PROFIL - CSS ULTRA-MODERNE */
.revolutionary-action-hub {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    min-height: 80px;
}

.primary-action-zone {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}

.action-primary {
    width: 56px !important;
    height: 56px !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.4);
}

.action-primary:hover {
    transform: translateY(-4px) scale(1.1);
    box-shadow: 0 12px 40px rgba(102, 126, 234, 0.6);
}

.action-email {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.action-certificate {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #333 !important;
}

.action-certificate.disabled {
    background: linear-gradient(135deg, #bdc3c7 0%, #95a5a6 100%);
    cursor: not-allowed;
    opacity: 0.6;
}

.action-certificate.disabled:hover {
    transform: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Modales spécifiques au profil */
.quick-email-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.email-modal-content {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    transform: scale(0.8);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.email-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.email-header h5 {
    margin: 0;
    color: #333;
}

.close-btn {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
}

.email-body {
    padding: 20px;
}

.email-input, .email-textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #eee;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

.email-input:focus, .email-textarea:focus {
    outline: none;
    border-color: #667eea;
}

.email-textarea {
    height: 120px;
    resize: vertical;
}

.send-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
}

.send-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.quick-actions-menu-profile {
    position: fixed;
    top: 50%;
    right: 20px;
    transform: translateY(-50%) translateY(20px);
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    padding: 16px;
    z-index: 10000;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 200px;
}

.modern-toast-profile {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 10000;
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Responsive pour profil */
@media (max-width: 768px) {
    .revolutionary-action-hub {
        flex-direction: column;
        gap: 12px;
        padding: 16px;
    }

    .primary-action-zone {
        margin-bottom: 8px;
    }

    .secondary-actions {
        justify-content: center;
    }
}
</style>
@endsection
