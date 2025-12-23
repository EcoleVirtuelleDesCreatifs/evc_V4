@extends('layouts.admin')

@section('title', 'Gestion des Étudiants')

@section('content')
<div class="container-fluid">
    <!-- Header Moderne avec Gradient et Actions -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card text-white mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="dashboard-icon me-3">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <div>
                                    <h1 class="text-white fw-bold mb-1" style="font-size: 2.2rem;">Gestion des Étudiants</h1>
                                    <p class="text-white-75 mb-0">Administration et suivi des étudiants EVC</p>
                                </div>
                            </div>

                            <!-- Statistiques Rapides en Header -->
                            <div class="row g-3">
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        @php
                                            // Calculer le vrai total de tous les étudiants (toutes formations)
                                            $totalRealStudents = $formationStats->sum('total_etudiants');
                                        @endphp
                                        <div class="text-white fw-bold" style="font-size: 1.5rem;">{{ $totalRealStudents }}</div>
                                        <div class="text-white-50 small">Total Étudiants</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-success fw-bold" style="font-size: 1.5rem;">{{ $stats['actifs'] }}</div>
                                        <div class="text-white-50 small">Actifs</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-info fw-bold" style="font-size: 1.5rem;">{{ $stats['nouveaux_ce_mois'] }}</div>
                                        <div class="text-white-50 small">Ce Mois</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-primary fw-bold" style="font-size: 1.5rem;">{{ $stats['nouveaux_cette_semaine'] ?? 0 }}</div>
                                        <div class="text-white-50 small">Cette Semaine</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-warning fw-bold" style="font-size: 1.5rem;">{{ $stats['nouveaux_aujourd_hui'] }}</div>
                                        <div class="text-white-50 small">Aujourd'hui</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('admin.students.create') }}" class="btn btn-success btn-lg shadow-lg">
                                    <i class="fas fa-user-plus me-2"></i>Ajouter un Étudiant
                                </a>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-light" onclick="exportStudents()">
                                        <i class="fas fa-download me-1"></i>Exporter
                                    </button>
                                    <button type="button" class="btn btn-outline-light" onclick="importStudents()">
                                        <i class="fas fa-upload me-1"></i>Importer
                                    </button>
                                    <button type="button" class="btn btn-outline-light" onclick="refreshData()">
                                        <i class="fas fa-sync-alt me-1"></i>Actualiser
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques Fonctionnelles et Épurées -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(10px);">
                <div class="card-body p-4">
                    <!-- Header Simple -->
                    <div class="d-flex align-items-center mb-4">
                        <div class="me-3">
                            <i class="fas fa-chart-bar text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="text-white fw-bold mb-1">Statistiques par Formation</h5>
                            <p class="text-white-50 mb-0 small">Répartition et activité des étudiants</p>
                        </div>
                    </div>

                    <!-- Grille Simple et Fonctionnelle -->
                    <div class="row g-4">
                        @php
                            $formationIcons = [
                                'Design graphique' => 'fas fa-palette',
                                'Community manager' => 'fas fa-share-alt',
                                'Intelligence Artificielle' => 'fas fa-robot',
                                'Gestion informatique' => 'fas fa-server'
                            ];

                            // Debug: Vérifier la présence et structure des données
                            $debugFormationStats = isset($formationStats) && !empty($formationStats);

                            // Fallback robuste: Récupérer les vraies données depuis la base si $formationStats est vide
                            if (!$debugFormationStats) {
                                try {
                                    $formationStats = collect([
                                        (object)[
                                            'nom_formation' => 'Design graphique',
                                            'total_etudiants' => DB::table('users')->where('formation_souhaitee', 'design-graphique')->count(),
                                            'actifs' => DB::table('users')
                                                ->where('formation_souhaitee', 'design-graphique')
                                                ->whereRaw('DATE_ADD(created_at, INTERVAL 4 MONTH) > NOW()')
                                                ->count()
                                        ],
                                        (object)[
                                            'nom_formation' => 'Community manager',
                                            'total_etudiants' => DB::table('users')->where('formation_souhaitee', 'community-management')->count(),
                                            'actifs' => DB::table('users')
                                                ->where('formation_souhaitee', 'community-management')
                                                ->whereRaw('DATE_ADD(created_at, INTERVAL 3 MONTH) > NOW()')
                                                ->count()
                                        ],
                                        (object)[
                                            'nom_formation' => 'Intelligence Artificielle',
                                            'total_etudiants' => DB::table('users')->where('formation_souhaitee', 'intelligence-artificielle')->count(),
                                            'actifs' => DB::table('users')
                                                ->where('formation_souhaitee', 'intelligence-artificielle')
                                                ->whereRaw('DATE_ADD(created_at, INTERVAL 1 MONTH) > NOW()')
                                                ->count()
                                        ],
                                        (object)[
                                            'nom_formation' => 'Gestion informatique',
                                            'total_etudiants' => DB::table('users')->where('formation_souhaitee', 'gestion-informatique')->count(),
                                            'actifs' => DB::table('users')
                                                ->where('formation_souhaitee', 'gestion-informatique')
                                                ->whereRaw('DATE_ADD(created_at, INTERVAL 2 MONTH) > NOW()')
                                                ->count()
                                        ]
                                    ]);
                                } catch (\Exception $e) {
                                    // En cas d'erreur de base de données, utiliser des données par défaut
                                    $formationStats = collect([
                                        (object)['nom_formation' => 'Design graphique', 'total_etudiants' => 0, 'actifs' => 0],
                                        (object)['nom_formation' => 'Community manager', 'total_etudiants' => 0, 'actifs' => 0],
                                        (object)['nom_formation' => 'Intelligence Artificielle', 'total_etudiants' => 0, 'actifs' => 0],
                                        (object)['nom_formation' => 'Gestion informatique', 'total_etudiants' => 0, 'actifs' => 0]
                                    ]);
                                }
                            }
                        @endphp



                        <!-- Vérification finale: S'assurer qu'on a des données à afficher -->
                        @if(count($formationStats) === 0)
                            <div class="col-12">
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Aucune donnée de formation disponible</strong>
                                    <br><small>Vérifiez la connexion à la base de données ou contactez l'administrateur système.</small>
                                </div>
                            </div>
                        @endif

                        @foreach($formationStats as $formation)
                            @php
                                // Utiliser l'icône fournie par le service
                                $icon = $formation->icon ?? 'fas fa-graduation-cap';
                                // Étudiants actifs calculés par le service
                                $activeStudents = $formation->actifs;
                            @endphp
                            <div class="col-md-6 col-xl-3">
                                <div class="card h-100 border-0" style="background: rgba(255,255,255,0.05); transition: all 0.3s ease;">
                                    <div class="card-body text-center p-3">
                                        <!-- Icône -->
                                        <div class="mb-3">
                                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
                                                 style="width: 50px; height: 50px; background: rgba(59, 130, 246, 0.2);">
                                                <i class="{{ $icon }} text-primary"></i>
                                            </div>
                                        </div>

                                        <!-- Nom Formation -->
                                        <h6 class="text-white fw-semibold mb-3">{{ $formation->nom_formation }}</h6>

                                        <!-- Affichage Dynamique des Nombres : Total | Actif -->
                                        <div class="row g-3 mb-4">
                                            <div class="col-6">
                                                <div class="text-center p-2 rounded dynamic-stat-box"
                                                     style="background: rgba(255,255,255,0.08); transition: all 0.3s ease;">
                                                    <div class="text-white fw-bold mb-1 counter-number"
                                                         style="font-size: 1.8rem;"
                                                         data-target="{{ $formation->total_etudiants }}"
                                                         data-formation="{{ $formation->nom_formation }}">{{ $formation->total_etudiants }}</div>
                                                    <div class="text-white-75 small fw-semibold">TOTAL</div>
                                                    <div class="text-white-50" style="font-size: 0.75rem;">Étudiants inscrits Officiellement</div>
                                                    <!-- Barre de progression dynamique -->
                                                    <div class="mt-2">
                                                        <div class="progress" style="height: 3px; background: rgba(255,255,255,0.1);">
                                                            <div class="progress-bar bg-light progress-total"
                                                                 style="width: 0%; transition: width 2s ease-in-out;"
                                                                 data-width="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="text-center p-2 rounded dynamic-stat-box"
                                                     style="background: rgba(34, 197, 94, 0.15); transition: all 0.3s ease;">
                                                    <div class="text-success fw-bold mb-1 counter-number"
                                                         style="font-size: 1.8rem;"
                                                         data-target="{{ $activeStudents }}"
                                                         data-formation="{{ $formation->nom_formation }}">{{ $activeStudents }}</div>
                                                    <div class="text-success small fw-semibold">ACTIF</div>
                                                    <div class="text-white-50" style="font-size: 0.75rem;">Abonnement en cours</div>
                                                    <!-- Barre de progression dynamique -->
                                                    <div class="mt-2">
                                                        <div class="progress" style="height: 3px; background: rgba(255,255,255,0.1);">
                                                            <div class="progress-bar bg-success progress-active"
                                                                 style="width: 0%; transition: width 2s ease-in-out;"
                                                                 data-width="{{ $formation->total_etudiants > 0 ? round(($activeStudents / $formation->total_etudiants) * 100) : 0 }}"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Bouton Voir en bas -->
                                        <div class="text-center">
                                            <a href="{{ route('admin.students.index', ['formation' => $formation->nom_formation]) }}"
                                               class="btn btn-primary btn-sm px-4 py-2">
                                                <i class="fas fa-eye me-1"></i>
                                                Voir
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message de filtrage par formation -->
    @if(request('formation'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-filter me-2"></i>
                    <span>Affichage des étudiants de la formation : <strong>{{ request('formation') }}</strong></span>
                    <a href="{{ route('admin.students.index') }}" class="btn btn-sm btn-outline-primary ms-auto">
                        <i class="fas fa-times me-1"></i>
                        Voir tous les étudiants
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Filtres et Recherche Modernes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card text-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="dashboard-icon me-3">
                            <i class="fas fa-filter"></i>
                        </div>
                        <div>
                            <h5 class="text-white fw-bold mb-1">Filtres et Recherche</h5>
                            <p class="text-white-75 mb-0 small">Trouvez rapidement les étudiants recherchés</p>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('admin.students.index') }}" class="modern-search-form">
                        <div class="row g-3">
                            <!-- Recherche Globale -->
                            <div class="col-md-4">
                                <div class="search-input-group">
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0 text-white-50">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text"
                                               name="search"
                                               class="form-control bg-transparent border-start-0 text-white"
                                               placeholder="Rechercher un étudiant..."
                                               value="{{ request('search') }}"
                                               style="border-color: var(--dashboard-border); backdrop-filter: blur(10px);">
                                    </div>
                                </div>
                            </div>

                            <!-- Filtre Formation -->
                            <div class="col-md-2">
                                <select name="formation" class="form-select bg-transparent text-white"
                                        style="border-color: var(--dashboard-border); backdrop-filter: blur(10px);">
                                    <option value="" class="text-dark">Toutes formations</option>
                                    <option value="design-graphique" {{ request('formation') == 'design-graphique' ? 'selected' : '' }} class="text-dark">Design Graphique</option>
                                    <option value="community-manager" {{ request('formation') == 'community-manager' ? 'selected' : '' }} class="text-dark">Community Manager</option>
                                    <option value="intelligence-artificielle" {{ request('formation') == 'intelligence-artificielle' ? 'selected' : '' }} class="text-dark">IA</option>
                                    <option value="gestion-informatique" {{ request('formation') == 'gestion-informatique' ? 'selected' : '' }} class="text-dark">Gestion IT</option>
                                </select>
                            </div>

                            <!-- Filtre Statut -->
                            <div class="col-md-2">
                                <select name="status" class="form-select bg-transparent text-white"
                                        style="border-color: var(--dashboard-border); backdrop-filter: blur(10px);">
                                    <option value="" class="text-dark">Tous statuts</option>
                                    <option value="Actif" {{ request('status') == 'Actif' ? 'selected' : '' }} class="text-dark">Actif</option>
                                    <option value="Inactif" {{ request('status') == 'Inactif' ? 'selected' : '' }} class="text-dark">Inactif</option>
                                </select>
                            </div>

                            <!-- Filtre Date -->
                            <div class="col-md-2">
                                <select name="date_filter" class="form-select bg-transparent text-white"
                                        style="border-color: var(--dashboard-border); backdrop-filter: blur(10px);">
                                    <option value="" class="text-dark">Toutes dates</option>
                                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }} class="text-dark">Aujourd'hui</option>
                                    <option value="week" {{ request('date_filter') == 'week' ? 'selected' : '' }} class="text-dark">Cette semaine</option>
                                    <option value="month" {{ request('date_filter') == 'month' ? 'selected' : '' }} class="text-dark">Ce mois</option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="col-md-2">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-search me-1"></i>Filtrer
                                    </button>
                                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-light">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Étudiants Moderne -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-card text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex align-items-center">
                            <div class="dashboard-icon me-3">
                                <i class="fas fa-list"></i>
                            </div>
                            <div>
                                <h5 class="text-white fw-bold mb-1">Liste des Étudiants</h5>
                                <p class="text-white-75 mb-0 small">{{ $students->total() }} étudiants au total</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-light btn-sm" onclick="toggleView('grid')" id="grid-view-btn">
                                    <i class="fas fa-th me-1"></i>Grille
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm active" onclick="toggleView('list')" id="list-view-btn">
                                    <i class="fas fa-list me-1"></i>Liste
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Vue Liste (par défaut) -->
                    <div id="list-view" class="students-list-view">
                        @if($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-dark table-hover modern-students-table">
                                    <thead>
                                        <tr>
                                            <th class="border-0 text-white-75 fw-bold">
                                                <i class="fas fa-user-graduate me-2"></i>ÉTUDIANT
                                            </th>
                                            <th class="border-0 text-white-75 fw-bold">
                                                <i class="fas fa-graduation-cap me-2"></i>FORMATION
                                            </th>
                                            <th class="border-0 text-white-75 fw-bold">
                                                <i class="fas fa-calendar-alt me-2"></i>DATE D'INSCRIPTION
                                            </th>
                                            <th class="border-0 text-white-75 fw-bold">
                                                <i class="fas fa-clock me-2"></i>FIN D'ABONNEMENT
                                            </th>
                                            <th class="border-0 text-white-75 fw-bold text-center">
                                                <i class="fas fa-wifi me-2"></i>STATUS
                                            </th>
                                            <th class="border-0 text-white-75 fw-bold text-center">
                                                <i class="fas fa-cogs me-2"></i>ACTION
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $student)
                                            <tr class="student-row modern-row" data-student-id="{{ $student->id }}">
                                                <!-- COLONNE ÉTUDIANT -->
                                                <td class="border-0 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="student-avatar me-3">
                                                            @php
                                                                $photoUrl = \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);
                                                            @endphp
                                                            @if($photoUrl && !str_contains($photoUrl, 'default-avatar') && !str_contains($photoUrl, 'avatar.png'))
                                                                <img src="{{ $photoUrl }}"
                                                                     alt="{{ $student->first_name }}"
                                                                     class="rounded-circle shadow-sm"
                                                                     style="width: 45px; height: 45px; object-fit: cover; border: 2px solid var(--dashboard-accent);">
                                                            @else
                                                                <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                                                     style="width: 45px; height: 45px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; font-weight: bold; font-size: 1.1rem;">
                                                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="text-white fw-bold mb-1">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                            <div class="text-white-50 small">
                                                                <i class="fas fa-envelope me-1"></i>{{ $student->email }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- COLONNE FORMATION -->
                                                <td class="border-0 py-3">
                                                    @php
                                                        // Vérifier si la propriété formation_souhaitee existe
                                                        $formationValue = $student->formation_souhaitee ?? null;

                                                        // Normaliser la valeur de formation pour gérer toutes les variations
                                                        $normalizedFormation = null;
                                                        if ($formationValue) {
                                                            $formationLower = strtolower(str_replace([' ', '_'], '-', $formationValue));

                                                            // Design Graphique (4 mois)
                                                            if (strpos($formationLower, 'design') !== false || strpos($formationLower, 'graphique') !== false) {
                                                                $normalizedFormation = 'design-graphique';
                                                            }
                                                            // Community Management (3 mois)
                                                            elseif (strpos($formationLower, 'community') !== false || strpos($formationLower, 'manager') !== false) {
                                                                $normalizedFormation = 'community-management';
                                                            }
                                                            // Gestion Informatique (2 mois)
                                                            elseif (strpos($formationLower, 'gestion') !== false || strpos($formationLower, 'informatique') !== false) {
                                                                $normalizedFormation = 'gestion-informatique';
                                                            }
                                                            // Intelligence Artificielle (1 mois)
                                                            elseif (strpos($formationLower, 'intelligence') !== false || strpos($formationLower, 'artificielle') !== false) {
                                                                $normalizedFormation = 'intelligence-artificielle';
                                                            }
                                                        }

                                                        $formationColors = [
                                                            'design-graphique' => ['bg' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', 'duration' => '4 mois'],
                                                            'community-management' => ['bg' => 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)', 'duration' => '3 mois'],
                                                            'gestion-informatique' => ['bg' => 'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)', 'duration' => '2 mois'],
                                                            'intelligence-artificielle' => ['bg' => 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)', 'duration' => '1 mois']
                                                        ];

                                                        $formationNames = [
                                                            'design-graphique' => 'Design Graphique',
                                                            'community-management' => 'Community Manager',
                                                            'gestion-informatique' => 'Gestion Informatique',
                                                            'intelligence-artificielle' => 'Intelligence Artificielle'
                                                        ];

                                                        // Gestion des cas où la formation n'est pas définie ou vide
                                                        if (empty($formationValue)) {
                                                            $formationInfo = ['bg' => 'linear-gradient(135deg, #6c757d 0%, #495057 100%)', 'duration' => 'Non définie'];
                                                            $formationName = 'Formation non définie';
                                                        } else {
                                                            // Utiliser la formation normalisée si disponible, sinon la valeur originale
                                                            $keyToUse = $normalizedFormation ?? $formationValue;
                                                            $formationInfo = $formationColors[$keyToUse] ?? ['bg' => 'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)', 'duration' => '4 mois'];
                                                            $formationName = $formationNames[$keyToUse] ?? ucfirst(str_replace('-', ' ', $formationValue));
                                                        }
                                                    @endphp

                                                    <div class="formation-minimal">
                                                        @if(!empty($formationValue))
                                                            <div class="text-white fw-medium mb-1" style="font-size: 0.85rem;">
                                                                {{ $formationName }}
                                                            </div>
                                                            <div class="text-white-50 small">
                                                                {{ $formationInfo['duration'] }}
                                                            </div>
                                                        @else
                                                            <div class="text-white-50" style="font-size: 0.85rem;">
                                                                Non définie
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- COLONNE DATE D'INSCRIPTION -->
                                                <td class="border-0 py-3">
                                                    <div class="inscription-info">
                                                        <div class="text-white fw-bold">
                                                            <i class="fas fa-calendar-plus me-2 text-info"></i>
                                                            {{ $student->created_at ? \Carbon\Carbon::parse($student->created_at)->format('d/m/Y') : 'N/A' }}
                                                        </div>
                                                        <div class="text-white-50 small">
                                                            {{ $student->created_at ? \Carbon\Carbon::parse($student->created_at)->diffForHumans() : 'Non disponible' }}
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- COLONNE FIN D'ABONNEMENT - VERSION ULTRA DYNAMIQUE -->
                                                <td class="border-0 py-3 text-center">
                                                    @if($student->subscription_end_date)
                                                        @php
                                                            $endDate = \Carbon\Carbon::parse($student->subscription_end_date);
                                                            $isExpired = $endDate->isPast();
                                                            $daysRemaining = (int) $endDate->diffInDays(now(), false);

                                                            // Système de couleurs bleues minimalistes
                                                            if ($isExpired) {
                                                                $blueShade = '#1e3a8a'; // Bleu foncé pour expiré
                                                                $bgColor = 'rgba(30, 58, 138, 0.1)';
                                                            } elseif ($daysRemaining <= 7) {
                                                                $blueShade = '#3b82f6'; // Bleu moyen pour urgent
                                                                $bgColor = 'rgba(59, 130, 246, 0.1)';
                                                            } elseif ($daysRemaining <= 30) {
                                                                $blueShade = '#60a5fa'; // Bleu clair pour attention
                                                                $bgColor = 'rgba(96, 165, 250, 0.1)';
                                                            } else {
                                                                $blueShade = '#93c5fd'; // Bleu très clair pour normal
                                                                $bgColor = 'rgba(147, 197, 253, 0.1)';
                                                            }

                                                            // Calcul du pourcentage de progression
                                                            $totalDays = match(true) {
                                                                str_contains(strtolower($student->formation_souhaitee), 'design') => 120, // 4 mois
                                                                str_contains(strtolower($student->formation_souhaitee), 'community') => 90, // 3 mois
                                                                str_contains(strtolower($student->formation_souhaitee), 'gestion') => 60, // 2 mois
                                                                str_contains(strtolower($student->formation_souhaitee), 'intelligence') => 30, // 1 mois
                                                                default => 120
                                                            };
                                                            $progressPercent = $isExpired ? 0 : min(100, max(0, ($daysRemaining / $totalDays) * 100));
                                                        @endphp

                                                        <div class="countdown-container"
                                                             data-end-date="{{ $endDate->toISOString() }}"
                                                             data-student-id="{{ $student->id }}">

                                                            <div class="countdown-blue-minimal"
                                                 style="background: {{ $bgColor }};
                                                        border: 1px solid {{ $blueShade }}40;
                                                        border-radius: 8px;
                                                        padding: 12px 16px;
                                                        text-align: center;">

                                                @if($isExpired)
                                                    <!-- Affichage expiré minimaliste bleu -->
                                                    <div style="color: {{ $blueShade }}; font-weight: 600; font-size: 0.85rem;">
                                                        Expiré
                                                    </div>
                                                    <div style="color: {{ $blueShade }}; opacity: 0.7; font-size: 0.7rem; margin-top: 2px;">
                                                        {{ abs($daysRemaining) }} jour{{ abs($daysRemaining) !== 1 ? 's' : '' }}
                                                    </div>
                                                @else
                                                    <!-- Décompte bleu minimaliste -->
                                                    <div class="d-flex align-items-center justify-content-center gap-2"
                                                         style="color: {{ $blueShade }}; font-weight: 500;">
                                                        <div class="text-center">
                                                            <div class="countdown-days" style="font-size: 1.1rem; font-weight: 600;" id="days-{{ $student->id }}">{{ $daysRemaining }}</div>
                                                            <div style="font-size: 0.65rem; opacity: 0.8; margin-top: -2px;">jours</div>
                                                        </div>
                                                        <div style="opacity: 0.4; font-size: 0.8rem;">•</div>
                                                        <div class="text-center">
                                                            <div class="countdown-hours" style="font-size: 0.9rem;" id="hours-{{ $student->id }}">{{ str_pad($endDate->diffInHours(now()) % 24, 2, '0', STR_PAD_LEFT) }}</div>
                                                            <div style="font-size: 0.6rem; opacity: 0.8; margin-top: -2px;">h</div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="countdown-minutes" style="font-size: 0.9rem;" id="minutes-{{ $student->id }}">{{ str_pad($endDate->diffInMinutes(now()) % 60, 2, '0', STR_PAD_LEFT) }}</div>
                                                            <div style="font-size: 0.6rem; opacity: 0.8; margin-top: -2px;">m</div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="countdown-seconds" style="font-size: 0.9rem;" id="seconds-{{ $student->id }}">{{ str_pad($endDate->diffInSeconds(now()) % 60, 2, '0', STR_PAD_LEFT) }}</div>
                                                            <div style="font-size: 0.6rem; opacity: 0.8; margin-top: -2px;">s</div>
                                                        </div>

                                        <!-- Script inline pour ce décompte complet -->
                                        <script>
                                        (function() {
                                            const endDate = new Date('{{ $endDate->toISOString() }}');
                                            const daysEl = document.getElementById('days-{{ $student->id }}');
                                            const hoursEl = document.getElementById('hours-{{ $student->id }}');
                                            const minutesEl = document.getElementById('minutes-{{ $student->id }}');
                                            const secondsEl = document.getElementById('seconds-{{ $student->id }}');

                                            function updateCountdown() {
                                                const now = new Date();
                                                const timeLeft = endDate - now;

                                                if (timeLeft > 0) {
                                                    // Calculer toutes les unités de temps
                                                    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                                                    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                                                    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                                                    // Mettre à jour tous les éléments
                                                    if (daysEl) daysEl.textContent = days;
                                                    if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
                                                    if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
                                                    if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
                                                } else {
                                                    // Abonnement expiré
                                                    if (daysEl) daysEl.textContent = '0';
                                                    if (hoursEl) hoursEl.textContent = '00';
                                                    if (minutesEl) minutesEl.textContent = '00';
                                                    if (secondsEl) secondsEl.textContent = '00';
                                                }
                                            }

                                            // Démarrer immédiatement
                                            updateCountdown();
                                            setInterval(updateCountdown, 1000);
                                        })();
                                        </script>                </div>
                                                    </div>
                                                    <div style="color: {{ $blueShade }}; opacity: 0.6; font-size: 0.65rem; margin-top: 6px; border-top: 1px solid {{ $blueShade }}20; padding-top: 4px;">
                                                        {{ $endDate->format('d/m/Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                                    @else
                                                        <div class="no-subscription text-center">
                                                            <div class="badge badge-secondary-glow" style="padding: 8px 12px; border-radius: 15px; backdrop-filter: blur(10px);">
                                                                <i class="fas fa-question-circle me-1"></i>
                                                                <span style="font-size: 0.8rem;">Non définie</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>

                                                <!-- COLONNE STATUS (EN LIGNE/HORS LIGNE) -->
                                                <td class="border-0 py-3 text-center">
                                                    @php
                                                        // Statut en ligne basé strictement sur la valeur calculée par le contrôleur
                                                        $isOnline = isset($student->online_status) && $student->online_status === 'online';
                                                    @endphp

                                                    <div class="status-minimal">
                                                        @php
                                                            // Utiliser la vraie dernière connexion depuis user_activities (plus précis)
                                                            $lastConnectionDate = $student->last_real_login ?? $student->last_login ?? $student->created_at;
                                                            $carbonDate = \Carbon\Carbon::parse($lastConnectionDate);

                                                            // Calcul français ultra-précis inline (évite redéclaration)
                                                            $now = \Carbon\Carbon::now();
                                                            $diff = $carbonDate->diff($now);

                                                            if ($diff->y > 0) {
                                                                $frenchTimeDiff = $diff->y == 1 ? 'il y a 1 an' : "il y a {$diff->y} ans";
                                                            } elseif ($diff->m > 0) {
                                                                $frenchTimeDiff = $diff->m == 1 ? 'il y a 1 mois' : "il y a {$diff->m} mois";
                                                            } elseif ($diff->d > 0) {
                                                                $frenchTimeDiff = $diff->d == 1 ? 'il y a 1 jour' : "il y a {$diff->d} jours";
                                                            } elseif ($diff->h > 0) {
                                                                $hours = $diff->h == 1 ? '1 heure' : "{$diff->h} heures";
                                                                if ($diff->i > 0) {
                                                                    $minutes = $diff->i == 1 ? '1 minute' : "{$diff->i} minutes";
                                                                    $frenchTimeDiff = "il y a {$hours} et {$minutes}";
                                                                } else {
                                                                    $frenchTimeDiff = "il y a {$hours}";
                                                                }
                                                            } elseif ($diff->i > 0) {
                                                                $frenchTimeDiff = $diff->i == 1 ? 'il y a 1 minute' : "il y a {$diff->i} minutes";
                                                            } else {
                                                                $frenchTimeDiff = 'il y a quelques secondes';
                                                            }
                                                        @endphp

                                                        <div class="text-center">
                                                            @if($isOnline)
                                                                <div class="d-flex align-items-center justify-content-center">
                                                                    <div class="online-dot-minimal me-2"></div>
                                                                    <span class="text-success fw-medium" style="font-size: 0.8rem;">Actuellement en ligne</span>
                                                                </div>
                                                            @else
                                                                <div class="d-flex align-items-center justify-content-center mb-1">
                                                                    <div class="offline-dot-minimal me-2"></div>
                                                                    <span class="text-warning fw-medium" style="font-size: 0.8rem;">En ligne {{ $frenchTimeDiff }}</span>
                                                                </div>
                                                                <div class="text-white-50" style="font-size: 0.7rem;">
                                                                    {{ $carbonDate->format('d/m/Y à H:i') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- COLONNE ACTION -->
                                                <td class="border-0 py-3 text-center">
                                                    <div class="action-buttons-container">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('admin.students.show', $student->id) }}"
                                                               class="btn btn-sm btn-outline-info modern-action-btn"
                                                               title="Voir le profil complet"
                                                               data-bs-toggle="tooltip">
                                                                <i class="fas fa-user-circle"></i>
                                                            </a>
                                                            <a href="{{ route('admin.students.edit', $student->id) }}"
                                                               class="btn btn-sm btn-outline-warning modern-action-btn"
                                                               title="Modifier les informations"
                                                               data-bs-toggle="tooltip">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button"
                                                                    class="btn btn-sm btn-outline-{{ $student->status == 'Actif' ? 'danger' : 'success' }} modern-action-btn"
                                                                    onclick="toggleStudentStatus({{ $student->id }}, '{{ $student->status }}')"
                                                                    title="{{ $student->status == 'Actif' ? 'Désactiver l\'étudiant' : 'Activer l\'étudiant' }}"
                                                                    data-bs-toggle="tooltip">
                                                                <i class="fas fa-{{ $student->status == 'Actif' ? 'user-slash' : 'user-check' }}"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="text-white-50 mb-3">
                                    <i class="fas fa-users fa-3x"></i>
                                </div>
                                <h6 class="text-white-75">Aucun étudiant trouvé</h6>
                                <p class="text-white-50 small">Essayez de modifier vos critères de recherche</p>
                            </div>
                        @endif
                    </div>

                    <!-- Pagination Moderne -->
                    @if($students->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-4" style="border-top: 1px solid var(--dashboard-border);">
                            <div class="text-white-75 small">
                                Affichage de {{ $students->firstItem() }} à {{ $students->lastItem() }} sur {{ $students->total() }} étudiants
                            </div>
                            <div class="pagination-modern">
                                {{ $students->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>






</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
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

.table th {
    border-top: none;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Styles modernes pour le nouveau tableau des étudiants */
.modern-students-table {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 15px;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.modern-students-table thead th {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
    border: none;
    padding: 1rem;
    font-size: 0.85rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.modern-row {
    background: rgba(255, 255, 255, 0.02);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.3s ease;
}

.modern-row:hover {
    background: rgba(255, 255, 255, 0.08);
    transform: scale(1.01);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.formation-badge {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease;
}

.formation-badge:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.inscription-info, .subscription-info {
    transition: all 0.3s ease;
}

.inscription-info:hover, .subscription-info:hover {
    transform: translateX(5px);
}

/* Styles minimalistes pour les indicateurs de statut */
.status-minimal {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.25rem;
}

/* ========== STYLES ULTRA-DYNAMIQUES POUR COLONNE ABONNEMENT ========== */

/* Design bleu minimaliste pour les décomptes */
.countdown-blue-minimal {
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
}

.countdown-blue-minimal:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}

/* Animation douce pour les chiffres */
.countdown-days, .countdown-hours, .countdown-minutes {
    transition: all 0.2s ease;
}

/* Effet de focus subtil */
.countdown-blue-minimal:focus-within {
    outline: 2px solid rgba(59, 130, 246, 0.3);
    outline-offset: 2px;
}

/* Badges avec effets glow et animations */
.badge-success-glow {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 0 20px rgba(40, 167, 69, 0.4);
    border: 1px solid rgba(40, 167, 69, 0.6);
    animation: glow-success 2s ease-in-out infinite alternate;
}

.badge-warning-glow {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    color: #212529;
    box-shadow: 0 0 20px rgba(255, 193, 7, 0.4);
    border: 1px solid rgba(255, 193, 7, 0.6);
    animation: glow-warning 2s ease-in-out infinite alternate;
}

.badge-danger-glow {
    background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
    color: white;
    box-shadow: 0 0 20px rgba(220, 53, 69, 0.4);
    border: 1px solid rgba(220, 53, 69, 0.6);
    animation: glow-danger 2s ease-in-out infinite alternate;
}

.badge-secondary-glow {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    box-shadow: 0 0 15px rgba(108, 117, 125, 0.3);
    border: 1px solid rgba(108, 117, 125, 0.5);
}

/* Animations de pulsation */
.pulse-success {
    animation: pulse-success 1.5s ease-in-out infinite;
}

.pulse-warning {
    animation: pulse-warning 1.5s ease-in-out infinite;
}

.pulse-danger {
    animation: pulse-danger 1s ease-in-out infinite;
}

/* Keyframes pour les effets glow */
@keyframes glow-success {
    from {
        box-shadow: 0 0 15px rgba(40, 167, 69, 0.3);
    }
    to {
        box-shadow: 0 0 25px rgba(40, 167, 69, 0.6), 0 0 35px rgba(40, 167, 69, 0.4);
    }
}

@keyframes glow-warning {
    from {
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.3);
    }
    to {
        box-shadow: 0 0 25px rgba(255, 193, 7, 0.6), 0 0 35px rgba(255, 193, 7, 0.4);
    }
}

@keyframes glow-danger {
    from {
        box-shadow: 0 0 15px rgba(220, 53, 69, 0.3);
    }
    to {
        box-shadow: 0 0 25px rgba(220, 53, 69, 0.6), 0 0 35px rgba(220, 53, 69, 0.4);
    }
}

/* Animations de pulsation */
@keyframes pulse-success {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.05);
        opacity: 0.9;
    }
}

@keyframes pulse-warning {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.08);
        opacity: 0.85;
    }
}

@keyframes pulse-danger {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
}

/* Animation shake pour les éléments expirés */
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
    20%, 40%, 60%, 80% { transform: translateX(2px); }
}

/* Styles pour la barre de progression circulaire */
.progress-ring-container {
    position: relative;
    display: inline-block;
}

.progress-ring-fill {
    transition: stroke-dashoffset 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    animation: progress-rotate 3s linear infinite;
}

@keyframes progress-rotate {
    0% {
        stroke-dashoffset: 157;
    }
    50% {
        stroke-dashoffset: var(--progress-offset, 157);
    }
    100% {
        stroke-dashoffset: 157;
    }
}

/* Styles pour le décompte temps réel */
.countdown-time-dynamic {
    font-family: 'Courier New', monospace;
    letter-spacing: 1px;
}

.time-unit {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    padding: 2px 4px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
    min-width: 20px;
    transition: all 0.3s ease;
}

.time-unit:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.1);
}

.time-separator {
    animation: blink 1s infinite;
    font-weight: bold;
    color: rgba(255, 255, 255, 0.6);
}

@keyframes blink {
    0%, 50% { opacity: 1; }
    51%, 100% { opacity: 0.3; }
}

/* Container principal dynamique */
.subscription-dynamic {
    position: relative;
    padding: 10px;
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.subscription-dynamic:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

/* Badge principal avec effet holographique */
.subscription-badge {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.subscription-badge::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transform: rotate(45deg);
    transition: all 0.6s ease;
    opacity: 0;
}

.subscription-badge:hover::before {
    animation: shimmer 1.5s ease-in-out;
}

@keyframes shimmer {
    0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
    100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
        opacity: 0;
    }
}

/* Indicateur d'expiration avec effet dramatique */
.expired-indicator {
    position: relative;
}

.expired-icon {
    filter: drop-shadow(0 0 10px rgba(220, 53, 69, 0.8));
}

/* Date de fin moderne */
.end-date-modern {
    position: relative;
    padding: 4px 8px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    border-left: 3px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.end-date-modern:hover {
    background: rgba(0, 0, 0, 0.4);
    border-left-color: rgba(255, 255, 255, 0.6);
    transform: translateX(2px);
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .subscription-dynamic {
        padding: 8px;
    }

    .progress-ring-container {
        width: 50px;
        height: 50px;
    }

    .progress-ring {
        width: 50px;
        height: 50px;
    }

    .subscription-badge {
        padding: 6px 10px;
        font-size: 0.85rem;
    }

    .countdown-time-dynamic {
        font-size: 0.6rem;
    }
}

/* Animation d'entrée pour les nouveaux éléments */
.subscription-dynamic {
    animation: slideInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.online-dot-minimal {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #28a745;
    animation: blink-green 1.5s ease-in-out infinite;
}

.offline-dot-minimal {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #6c757d;
    opacity: 0.6;
}

@keyframes blink-green {
    0% {
        opacity: 1;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.8);
    }
    50% {
        opacity: 0.3;
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.4);
    }
    100% {
        opacity: 1;
        box-shadow: 0 0 5px rgba(40, 167, 69, 0.8);
    }
}

/* Styles pour les boutons d'action modernes */
.action-buttons-container {
    display: flex;
    justify-content: center;
}

.modern-action-btn {
    border-radius: 8px;
    padding: 0.5rem;
    margin: 0 2px;
    transition: all 0.3s ease;
    border-width: 2px;
    font-weight: 600;
}

.modern-action-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.btn-outline-info.modern-action-btn:hover {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    border-color: #17a2b8;
}

.btn-outline-warning.modern-action-btn:hover {
    background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    border-color: #ffc107;
}

.btn-outline-danger.modern-action-btn:hover {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-color: #dc3545;
}

.btn-outline-success.modern-action-btn:hover {
    background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
    border-color: #28a745;
}

/* Animation pour les avatars */
.student-avatar img, .avatar-placeholder {
    transition: all 0.3s ease;
}

.modern-row:hover .student-avatar img,
.modern-row:hover .avatar-placeholder {
    transform: scale(1.1);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

/* Responsive design pour les petits écrans */
@media (max-width: 768px) {
    .modern-students-table {
        font-size: 0.8rem;
    }

    .modern-students-table th,
    .modern-students-table td {
        padding: 0.5rem;
    }

    .formation-badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }

    .status-badge {
        font-size: 0.65rem;
        padding: 0.25rem 0.5rem;
    }

    .modern-action-btn {
        padding: 0.25rem;
        margin: 0 1px;
    }
}

.btn-group .btn {
    border-radius: 0.25rem !important;
    margin-right: 2px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

/* Gradients personnalisés pour les formations */
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Classes utilitaires pour le texte blanc */
.text-white-75 {
    color: rgba(255, 255, 255, 0.75) !important;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.5) !important;
}

.text-white-25 {
    color: rgba(255, 255, 255, 0.25) !important;
}

/* Classes utilitaires pour les bordures blanches */
.border-white-25 {
    border-color: rgba(255, 255, 255, 0.25) !important;
}

.bg-white-25 {
    background-color: rgba(255, 255, 255, 0.25) !important;
}

/* Animation pour les cartes de formation */
.card.h-100 {
    transition: all 0.3s ease;
}

.card.h-100:hover {
    transform: translateY(-5px);
    box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
}

/* Amélioration des cartes d'étudiants récents */
.border-start.border-primary.border-4 {
    border-left-width: 4px !important;
    border-left-color: #0d6efd !important;
}

/* Animation des progress bars */
.progress-bar {
    transition: width 0.6s ease;
}

/* Responsive pour les statistiques */
@media (max-width: 768px) {
    .col-md-2 {
        margin-bottom: 1rem;
    }

    .card-body.text-center h4 {
        font-size: 1.5rem;
    }

    .card-body.text-center .fa-2x {
        font-size: 1.5em;
    }
}
</style>

<script>
// Fonction de filtrage
function applyFilters() {
    const search = document.getElementById('search').value.toLowerCase();
    const formationFilter = document.getElementById('formation_filter').value.toLowerCase();
    const statusFilter = document.getElementById('status_filter').value;

    const table = document.getElementById('studentsTable');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');

        if (cells.length > 0) {
            const name = cells[1].textContent.toLowerCase();
            const email = cells[2].textContent.toLowerCase();
            const formations = cells[4].textContent.toLowerCase();
            const status = cells[6].querySelector('.badge').textContent.toLowerCase();

            let showRow = true;

            // Filtre de recherche
            if (search && !name.includes(search) && !email.includes(search)) {
                showRow = false;
            }

            // Filtre de formation
            if (formationFilter && !formations.includes(formationFilter)) {
                showRow = false;
            }

            // Filtre de statut
            if (statusFilter) {
                const isActive = status.includes('actif');
                if ((statusFilter === '1' && !isActive) || (statusFilter === '0' && isActive)) {
                    showRow = false;
                }
            }

            row.style.display = showRow ? '' : 'none';
        }
    }
}

// Recherche en temps réel
document.getElementById('search').addEventListener('input', applyFilters);
document.getElementById('formation_filter').addEventListener('change', applyFilters);
document.getElementById('status_filter').addEventListener('change', applyFilters);

// Animation dynamique des statistiques par formation
function animateFormationStats() {
    // Animation des compteurs numériques
    document.querySelectorAll('.counter-number').forEach(function(counter) {
        const target = parseInt(counter.dataset.target) || 0;
        const formation = counter.dataset.formation;

        // Commencer à 0 pour l'animation
        counter.textContent = '0';
        let current = 0;

        if (target === 0) {
            counter.textContent = '0';
            return;
        }

        const duration = 1500; // 1.5 secondes
        const steps = 60; // 60 étapes pour une animation fluide
        const increment = target / steps;
        const stepTime = duration / steps;

        const timer = setInterval(function() {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);

                // Effet de pulsation à la fin
                counter.style.transform = 'scale(1.1)';
                counter.style.transition = 'transform 0.2s ease';
                setTimeout(() => {
                    counter.style.transform = 'scale(1)';
                }, 200);
            }
            counter.textContent = Math.floor(current);
        }, stepTime);
    });

    // Animation des barres de progression
    setTimeout(function() {
        document.querySelectorAll('.progress-total').forEach(function(bar) {
            const width = bar.dataset.width;
            bar.style.width = width + '%';
        });

        document.querySelectorAll('.progress-active').forEach(function(bar) {
            const width = bar.dataset.width;
            bar.style.width = width + '%';
        });
    }, 500); // Démarrer après 0.5s

    // Animation des boîtes de statistiques
    document.querySelectorAll('.dynamic-stat-box').forEach(function(box, index) {
        box.style.opacity = '0';
        box.style.transform = 'translateY(20px)';

        setTimeout(function() {
            box.style.transition = 'all 0.6s ease';
            box.style.opacity = '1';
            box.style.transform = 'translateY(0)';
        }, index * 200); // Décalage de 200ms entre chaque boîte
    });

    // Effet hover sur les boîtes
    document.querySelectorAll('.dynamic-stat-box').forEach(function(box) {
        box.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
            this.style.boxShadow = '0 8px 25px rgba(0,0,0,0.15)';
        });

        box.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
            this.style.boxShadow = 'none';
        });
    });
}

// Fonctions d'action
function viewStudent(id) {
    window.location.href = `/admin/students/${id}`;
}

function editStudent(id) {
    window.location.href = `/admin/students/${id}/edit`;
}

// Fonctions pour la gestion moderne des étudiants
function toggleView(viewType) {
    const listView = document.getElementById('list-view');
    const gridView = document.getElementById('grid-view');
    const listBtn = document.getElementById('list-view-btn');
    const gridBtn = document.getElementById('grid-view-btn');

    if (viewType === 'grid') {
        listView.classList.add('d-none');
        gridView.classList.remove('d-none');
        listBtn.classList.remove('active');
        gridBtn.classList.add('active');
    } else {
        gridView.classList.add('d-none');
        listView.classList.remove('d-none');
        gridBtn.classList.remove('active');
        listBtn.classList.add('active');
    }

    // Sauvegarder la préférence
    localStorage.setItem('studentsViewType', viewType);
}

function toggleSelectAll() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.student-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });

    updateBulkActions();
}

function updateBulkActions() {
    const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
    const bulkActions = document.getElementById('bulk-actions');

    if (checkedBoxes.length > 0) {
        if (bulkActions) bulkActions.classList.remove('d-none');
    } else {
        if (bulkActions) bulkActions.classList.add('d-none');
    }
}

function toggleStudentStatus(id, currentStatus) {
    const newStatus = currentStatus === 'Actif' ? 'Inactif' : 'Actif';
    const action = newStatus === 'Actif' ? 'activer' : 'désactiver';

    // Modale de confirmation moderne
    if (confirm(`Êtes-vous sûr de vouloir ${action} cet étudiant ?`)) {
        // Animation du bouton
        const button = event.target.closest('button');
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch(`/admin/students/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showModernToast(`Étudiant ${action} avec succès`, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showModernToast('Erreur lors de la modification du statut', 'error');
                button.disabled = false;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showModernToast('Erreur lors de la modification du statut', 'error');
            button.disabled = false;
        });
    }
}

function exportStudents() {
    showModernToast('Export en cours...', 'info');
    window.open('/admin/students/export', '_blank');
}

function importStudents() {
    // Créer un input file temporaire
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = '.csv,.xlsx';
    input.onchange = function(e) {
        const file = e.target.files[0];
        if (file) {
            showModernToast('Import en cours...', 'info');
            // Logique d'import ici
        }
    };
    input.click();
}

function refreshData() {
    showModernToast('Actualisation en cours...', 'info');
    location.reload();
}

function showModernToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `modern-toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle"></i>
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

// 🚀 ANIMATIONS RÉVOLUTIONNAIRES
function initRevolutionaryAnimations() {
    // Animer les barres de progression
    const progressBars = document.querySelectorAll('.progress-bar-revolutionary');

    progressBars.forEach((bar, index) => {
        const targetWidth = bar.dataset.width + '%';

        // Animation décalée pour chaque barre
        setTimeout(() => {
            bar.style.width = targetWidth;
        }, index * 200 + 500);
    });

    // Animer les métriques circulaires
    const metricCircles = document.querySelectorAll('.metric-circle');

    metricCircles.forEach((circle, index) => {
        setTimeout(() => {
            circle.style.transform = 'scale(1.1)';
            setTimeout(() => {
                circle.style.transform = 'scale(1)';
            }, 200);
        }, index * 100 + 800);
    });

    // Animation des icônes flottantes
    const floatingIcons = document.querySelectorAll('.floating-icon');

    floatingIcons.forEach((icon, index) => {
        setTimeout(() => {
            icon.style.opacity = '1';
            icon.style.transform = 'translateY(0)';
        }, index * 150 + 300);
    });
}

// 📊 INITIALISATION DES ANIMATIONS DYNAMIQUES
document.addEventListener('DOMContentLoaded', function() {
    // Debug : vérifier les éléments
    console.log('🔍 Debug Statistiques Formation:');
    const counters = document.querySelectorAll('.counter-number');
    console.log(`Nombre de compteurs trouvés: ${counters.length}`);

    counters.forEach((counter, index) => {
        const target = counter.dataset.target;
        const formation = counter.dataset.formation;
        const currentText = counter.textContent;
        console.log(`Compteur ${index + 1}: Formation="${formation}", Target="${target}", Current="${currentText}"`);
    });

    // Initialiser les animations des statistiques par formation
    setTimeout(() => {
        console.log('🚀 Démarrage animation des statistiques...');
        animateFormationStats();
    }, 300);

    // Initialiser les autres animations révolutionnaires
    setTimeout(() => {
        initRevolutionaryAnimations();
    }, 800);

    // Restaurer la vue préférée
    const savedView = localStorage.getItem('studentsViewType');
    if (savedView) {
        toggleView(savedView);
    }
});

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Restaurer la vue préférée
    const savedView = localStorage.getItem('studentsViewType');
    if (savedView) {
        toggleView(savedView);
    }

    // Écouter les changements de checkbox
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    // Initialiser les animations révolutionnaires
    setTimeout(initRevolutionaryAnimations, 100);

    console.log('🎓 Interface révolutionnaire des étudiants initialisée !');
});

function exportStudents(format) {
    const url = format === 'excel' ? '/admin/students/export-excel' : '/admin/students/export-pdf';
    window.open(url, '_blank');
}

// 🕐 SYSTÈME DE DÉCOMPTE FLUIDE ET MINIMALISTE
function initCountdownTimers() {
    const countdownContainers = document.querySelectorAll('.countdown-container');

    countdownContainers.forEach(container => {
        const endDate = new Date(container.dataset.endDate);
        const studentId = container.dataset.studentId;

        // Vérifier si l'abonnement n'est pas déjà expiré
        if (endDate > new Date()) {
            updateCountdown(container, endDate);

            // Mettre à jour toutes les secondes
            setInterval(() => {
                updateCountdown(container, endDate);
            }, 1000);
        }
    });
}

function updateCountdown(container, endDate) {
    const now = new Date();
    const timeLeft = endDate - now;

    if (timeLeft <= 0) {
        // Abonnement expiré - recharger la page pour mettre à jour l'affichage
        location.reload();
        return;
    }

    // Calculer les jours, heures, minutes, secondes
    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

    // Mettre à jour l'affichage avec animation fluide
    const daysElement = container.querySelector('.countdown-days');
    const hoursElement = container.querySelector('.countdown-hours');
    const minutesElement = container.querySelector('.countdown-minutes');
    const secondsElement = container.querySelector('.countdown-seconds');
    const labelElement = container.querySelector('.countdown-label');

    if (daysElement && daysElement.textContent != days) {
        animateNumberChange(daysElement, days);
        // Mettre à jour le label des jours
        if (labelElement) {
            labelElement.textContent = `jour${days !== 1 ? 's' : ''} restant${days !== 1 ? 's' : ''}`;
        }
    }

    if (hoursElement) {
        animateNumberChange(hoursElement, hours.toString().padStart(2, '0'));
    }

    if (minutesElement) {
        animateNumberChange(minutesElement, minutes.toString().padStart(2, '0'));
    }

    if (secondsElement) {
        animateNumberChange(secondsElement, seconds.toString().padStart(2, '0'));
    }

    // Animation de pulsation pour les dernières 24h
    if (days === 0) {
        container.style.animation = 'pulse 2s ease-in-out infinite';
    } else {
        container.style.animation = 'none';
    }
}

function animateNumberChange(element, newValue) {
    if (element && element.textContent !== newValue.toString()) {
        element.style.transform = 'scale(1.1)';
        element.style.transition = 'transform 0.2s ease';

        setTimeout(() => {
            element.textContent = newValue;
            element.style.transform = 'scale(1)';
        }, 100);
    }
}



// 🚀 FONCTION DE DÉCOMPTE SIMPLIFIÉE ET ÉPURÉE
function initCountdownTimers() {
    console.log('⏰ Recherche des conteneurs de décompte...');
    const countdownContainers = document.querySelectorAll('.countdown-container');
    console.log(`📊 ${countdownContainers.length} conteneurs trouvés`);

    countdownContainers.forEach((container, index) => {
        const endDateStr = container.dataset.endDate;
        console.log(`🎯 Conteneur ${index + 1}: Date = ${endDateStr}`);

        if (!endDateStr) {
            console.warn(`⚠️ Pas de date pour le conteneur ${index + 1}`);
            return;
        }

        const endDate = new Date(endDateStr);
        const now = new Date();
        console.log(`📅 Date fin: ${endDate.toLocaleString()}, Maintenant: ${now.toLocaleString()}`);

        // Vérifier si l'abonnement n'est pas déjà expiré
        if (endDate > now) {
            console.log(`✅ Démarrage du décompte pour le conteneur ${index + 1}`);

            // Arrêter tout intervalle existant pour éviter les doublons
            if (container.dataset.intervalId) {
                clearInterval(container.dataset.intervalId);
            }

            // Mise à jour immédiate
            updateSimpleCountdown(container, endDate);

            // Mettre à jour en temps réel (chaque seconde)
            const intervalId = setInterval(() => {
                updateSimpleCountdown(container, endDate);
            }, 1000); // 1 seconde

            container.dataset.intervalId = intervalId;
            console.log(`🔄 Intervalle créé avec ID: ${intervalId}`);
        } else {
            console.log(`⏰ Abonnement expiré pour le conteneur ${index + 1}`);
        }
    });
}

// 🎨 FONCTION DE MISE À JOUR SIMPLE ET ÉPURÉE
function updateSimpleCountdown(container, endDate) {
    const now = new Date();
    const timeLeft = endDate - now;

    if (timeLeft <= 0) {
        // Abonnement expiré - arrêter le décompte et recharger
        console.log('⏰ Abonnement expiré - arrêt du décompte');
        const intervalId = container.dataset.intervalId;
        if (intervalId) {
            clearInterval(intervalId);
        }
        setTimeout(() => location.reload(), 2000);
        return;
    }

    // Calculer les unités de temps
    const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
    const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
    // Secondes qui comptent de 1 à 60 au lieu de décompter
    const seconds = 60 - Math.floor((timeLeft % (1000 * 60)) / 1000);

    // Debug: afficher le temps calculé
    console.log(`⏱️ Temps: ${days}j ${hours}h ${minutes}m ${seconds}s`);

    // Mettre à jour les éléments avec animation douce
    const daysElement = container.querySelector('.countdown-days');
    const hoursElement = container.querySelector('.countdown-hours');
    const minutesElement = container.querySelector('.countdown-minutes');
    const secondsElement = container.querySelector('.countdown-seconds');

    // Debug: vérifier si les éléments sont trouvés
    console.log(`🔍 Éléments trouvés: jours=${!!daysElement}, heures=${!!hoursElement}, minutes=${!!minutesElement}, secondes=${!!secondsElement}`);

    if (daysElement) {
        animateNumberChange(daysElement, days);
    }

    if (hoursElement) {
        animateNumberChange(hoursElement, hours.toString().padStart(2, '0'));
    }

    if (minutesElement) {
        animateNumberChange(minutesElement, minutes.toString().padStart(2, '0'));
    }

    if (secondsElement) {
        animateNumberChange(secondsElement, seconds.toString().padStart(2, '0'));
    }
}

// ⚡ DÉMARRAGE IMMÉDIAT DU DÉCOMPTE TEMPS RÉEL
function startRealTimeCountdown() {
    // Trouver tous les éléments de décompte
    const countdowns = document.querySelectorAll('.countdown-container');

    countdowns.forEach(container => {
        const endDateStr = container.dataset.endDate;
        if (!endDateStr) return;

        const endDate = new Date(endDateStr);

        // Fonction de mise à jour pour ce conteneur spécifique
        function updateThisCountdown() {
            const now = new Date();
            const timeLeft = endDate - now;

            if (timeLeft <= 0) {
                location.reload();
                return;
            }

            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = 60 - Math.floor((timeLeft % (1000 * 60)) / 1000);

            // Mise à jour directe des éléments
            const daysEl = container.querySelector('.countdown-days');
            const hoursEl = container.querySelector('.countdown-hours');
            const minutesEl = container.querySelector('.countdown-minutes');
            const secondsEl = container.querySelector('.countdown-seconds');

            if (daysEl) daysEl.textContent = days;
            if (hoursEl) hoursEl.textContent = hours.toString().padStart(2, '0');
            if (minutesEl) minutesEl.textContent = minutes.toString().padStart(2, '0');
            if (secondsEl) secondsEl.textContent = seconds.toString().padStart(2, '0');
        }

        // Mise à jour immédiate
        updateThisCountdown();

        // Démarrer l'intervalle
        setInterval(updateThisCountdown, 1000);
    });
}

// 🚨 TEST VISUEL POUR DIAGNOSTIQUER LE PROBLÈME
function testCountdown() {
    // Créer un indicateur visuel de test
    const testDiv = document.createElement('div');
    testDiv.style.cssText = `
        position: fixed;
        top: 10px;
        right: 10px;
        background: red;
        color: white;
        padding: 10px;
        z-index: 9999;
        border-radius: 5px;
    `;
    testDiv.textContent = 'TEST: Script chargé!';
    document.body.appendChild(testDiv);

    // Tester la recherche d'éléments
    const containers = document.querySelectorAll('.countdown-container');
    testDiv.innerHTML = `TEST: ${containers.length} conteneurs trouvés`;

    // Si on trouve des conteneurs, tester la mise à jour
    if (containers.length > 0) {
        const firstContainer = containers[0];
        const daysEl = firstContainer.querySelector('.countdown-days');
        const hoursEl = firstContainer.querySelector('.countdown-hours');
        const minutesEl = firstContainer.querySelector('.countdown-minutes');
        const secondsEl = firstContainer.querySelector('.countdown-seconds');

        testDiv.innerHTML += `<br>Éléments: J=${!!daysEl} H=${!!hoursEl} M=${!!minutesEl} S=${!!secondsEl}`;

        // Test de mise à jour
        let counter = 0;
        setInterval(() => {
            counter++;
            if (secondsEl) {
                secondsEl.textContent = counter.toString().padStart(2, '0');
                testDiv.innerHTML = `TEST: Mise à jour ${counter}`;
            }
        }, 1000);
    }
}

// Démarrage du test
document.addEventListener('DOMContentLoaded', testCountdown);
setTimeout(testCountdown, 500);
setTimeout(testCountdown, 2000);
</script>
@endsection

@push('styles')
<style>
/* 🎓 INTERFACE MODERNE ÉTUDIANTS - CSS OPTIMISÉ */

/* Table moderne */
.modern-table {
    background: transparent;
}

.modern-table th {
    background: rgba(255,255,255,0.05);
    border: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}

.modern-table td {
    background: rgba(255,255,255,0.02);
    border: none;
    vertical-align: middle;
}

.modern-table tbody tr:hover {
    background: rgba(255,255,255,0.08);
    transform: translateY(-1px);
    transition: all 0.2s ease;
}

/* Cartes étudiants en grille */
.dashboard-student-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.dashboard-student-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

/* Formulaire de recherche moderne */
.modern-search-form .form-control,
.modern-search-form .form-select {
    background: rgba(255,255,255,0.1);
    border: 1px solid var(--dashboard-border);
    color: white;
    backdrop-filter: blur(10px);
}

.modern-search-form .form-control:focus,
.modern-search-form .form-select:focus {
    background: rgba(255,255,255,0.15);
    border-color: var(--dashboard-accent);
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    color: white;
}

.modern-search-form .form-control::placeholder {
    color: rgba(255,255,255,0.6);
}

.modern-search-form .input-group-text {
    background: rgba(255,255,255,0.1);
    border: 1px solid var(--dashboard-border);
    border-right: none;
}

/* Badges de formation */
.formation-badge {
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
    border-radius: 1rem;
    font-weight: 500;
}

/* Avatar placeholder */
.avatar-placeholder {
    background: linear-gradient(135deg, var(--dashboard-accent) 0%, #FF8A65 100%);
    font-weight: 700;
}

/* Pagination moderne */
.pagination-modern .pagination {
    margin: 0;
}

.pagination-modern .page-link {
    background: rgba(255,255,255,0.1);
    border: 1px solid var(--dashboard-border);
    color: white;
    margin: 0 2px;
    border-radius: 8px;
}

.pagination-modern .page-link:hover {
    background: rgba(255,255,255,0.2);
    border-color: var(--dashboard-accent);
    color: white;
}

.pagination-modern .page-item.active .page-link {
    background: var(--dashboard-accent);
    border-color: var(--dashboard-accent);
}

/* Toast notifications */
.modern-toast {
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
    min-width: 300px;
}

.toast-success {
    border-left: 4px solid #28a745;
    color: #28a745;
}

.toast-error {
    border-left: 4px solid #dc3545;
    color: #dc3545;
}

.toast-info {
    border-left: 4px solid #007bff;
    color: #007bff;
}

/* ===== STATISTIQUES RÉVOLUTIONNAIRES FLUIDES ===== */

/* Container révolutionnaire */
.stats-revolutionary-container {
    background: transparent;
    padding: 2rem 0;
    position: relative;
    overflow: hidden;
}

/* Header révolutionnaire */
.stats-revolutionary-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 3rem;
    position: relative;
}

.header-content {
    flex: 1;
    text-align: center;
}

.revolutionary-title {
    color: #ffffff;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
    position: relative;
    display: inline-block;
}

.title-underline {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #3b82f6, #8b5cf6, #06b6d4, #10b981);
    margin: 0 auto 1rem;
    border-radius: 2px;
    animation: underlineGlow 3s ease-in-out infinite;
}

.revolutionary-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
    margin: 0;
    font-weight: 400;
}

.header-decoration {
    display: flex;
    gap: 8px;
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
}

.decoration-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--dashboard-accent);
    animation: dotPulse 2s ease-in-out infinite;
}

.decoration-dot:nth-child(2) { animation-delay: 0.3s; }
.decoration-dot:nth-child(3) { animation-delay: 0.6s; }

/* Grille Bootstrap révolutionnaire - Pas de CSS Grid personnalisé */

/* Cartes révolutionnaires */
.revolutionary-card {
    background: rgba(255, 255, 255, 0.02);
    border: 2px solid transparent;
    border-radius: 20px;
    padding: 2rem;
    position: relative;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
    cursor: pointer;
}

.revolutionary-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, var(--card-color), var(--card-accent));
    opacity: 0.05;
    transition: opacity 0.4s ease;
}

.revolutionary-card:hover {
    transform: translateY(-8px) scale(1.02);
    border-color: var(--card-color);
    background: rgba(255, 255, 255, 0.04);
}

.revolutionary-card:hover::before {
    opacity: 0.1;
}

/* Icône flottante */
.floating-icon {
    position: relative;
    width: 60px;
    height: 60px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--card-color);
    font-size: 1.8rem;
    animation: floatIcon 3s ease-in-out infinite;
}

.icon-pulse {
    position: absolute;
    width: 100%;
    height: 100%;
    border: 2px solid var(--card-color);
    border-radius: 50%;
    animation: iconPulse 2s ease-in-out infinite;
    opacity: 0.3;
}

/* Nom de formation révolutionnaire */
.formation-name-revolutionary {
    text-align: center;
    margin-bottom: 2rem;
    position: relative;
}

.name-text {
    color: #ffffff;
    font-weight: 600;
    font-size: 1.2rem;
    position: relative;
    z-index: 2;
}

.name-glow {
    position: absolute;
    bottom: -4px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: var(--card-color);
    transition: width 0.4s ease;
}

.revolutionary-card:hover .name-glow {
    width: 100%;
}

/* Métriques révolutionnaires */
.metrics-revolutionary {
    display: flex;
    justify-content: space-around;
    margin-bottom: 2rem;
    gap: 1rem;
}

.metric-revolutionary {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}

.metric-circle {
    width: 50px;
    height: 50px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.5rem;
    position: relative;
    transition: all 0.3s ease;
}

.metric-circle.active {
    border-color: var(--card-color);
    background: rgba(255, 255, 255, 0.05);
}

.metric-circle.new {
    border-color: var(--card-accent);
    background: rgba(255, 255, 255, 0.03);
}

.metric-number {
    color: #ffffff;
    font-weight: 700;
    font-size: 1.1rem;
}

.metric-label-revolutionary {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Progress révolutionnaire */
.progress-revolutionary {
    margin-top: 1.5rem;
}

.progress-label {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    font-weight: 600;
    text-align: center;
    margin-bottom: 0.75rem;
}

.progress-container {
    position: relative;
    height: 6px;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bg {
    position: absolute;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 3px;
}

.progress-bar-revolutionary {
    position: absolute;
    height: 100%;
    background: linear-gradient(90deg, var(--card-color), var(--card-accent));
    border-radius: 3px;
    transition: width 1.5s cubic-bezier(0.23, 1, 0.32, 1);
    overflow: hidden;
}

.progress-glow {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    animation: progressGlow 2s ease-in-out infinite;
}

/* Effet de hover */
.card-hover-effect {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, var(--card-color) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
}

.revolutionary-card:hover .card-hover-effect {
    opacity: 0.03;
}

/* Animations révolutionnaires */
@keyframes underlineGlow {
    0%, 100% { opacity: 1; transform: scaleX(1); }
    50% { opacity: 0.7; transform: scaleX(1.1); }
}

@keyframes dotPulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.2); opacity: 0.7; }
}

@keyframes floatIcon {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

@keyframes iconPulse {
    0% { transform: scale(1); opacity: 0.3; }
    50% { transform: scale(1.1); opacity: 0.1; }
    100% { transform: scale(1.2); opacity: 0; }
}

@keyframes progressGlow {
    0% { left: -100%; }
    100% { left: 100%; }
}

/* Responsive révolutionnaire */
@media (max-width: 768px) {
    .stats-revolutionary-container {
        padding: 1.5rem 0;
    }

    .revolutionary-title {
        font-size: 1.6rem;
    }

    .revolutionary-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
        padding: 0 0.5rem;
    }

    .revolutionary-card {
        padding: 1.5rem;
    }

    .header-decoration {
        display: none;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dashboard-student-card {
    animation: fadeInUp 0.5s ease-out;
}

.stat-card-clean {
    animation: fadeInUp 0.4s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .modern-search-form .row {
        flex-direction: column;
    }

    .modern-search-form .col-md-2,
    .modern-search-form .col-md-4 {
        margin-bottom: 1rem;
    }

    .dashboard-student-card {
        margin-bottom: 1rem;
    }
}

/* Actions hub legacy */
.action-hub {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px;
    min-height: 50px;
}

.action-btn {
    position: relative;
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    text-decoration: none;
}

.action-btn:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 8px 25px rgba(0,0,0,0.25);
    color: white;
    text-decoration: none;
}

.action-btn:active {
    transform: translateY(0) scale(0.95);
}

/* Actions spécifiques */
.action-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    width: 42px;
    height: 42px;
}

.action-edit {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.action-status.active {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.action-status.inactive {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.action-more {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

/* Indicateur de statut intelligent */
.status-indicator {
    position: absolute;
    top: 0;
    right: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px rgba(0,0,0,0.1);
}

.status-indicator.online {
    background: #00ff88;
    animation: pulse-online 2s infinite;
}

.status-indicator.offline {
    background: #ff4757;
}

@keyframes pulse-online {
    0% { box-shadow: 0 0 0 0 rgba(0, 255, 136, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(0, 255, 136, 0); }
    100% { box-shadow: 0 0 0 0 rgba(0, 255, 136, 0); }
}

/* Tooltips modernes */
.action-btn[data-tooltip]:hover::before {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 120%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(0,0,0,0.9);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    white-space: nowrap;
    z-index: 1000;
    animation: tooltip-appear 0.3s ease;
}

.action-btn[data-tooltip]:hover::after {
    content: '';
    position: absolute;
    bottom: 110%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(0,0,0,0.9);
    z-index: 1000;
}

@keyframes tooltip-appear {
    from { opacity: 0; transform: translateX(-50%) translateY(5px); }
    to { opacity: 1; transform: translateX(-50%) translateY(0); }
}

/* Actions secondaires */
.secondary-actions {
    display: flex;
    gap: 6px;
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.action-hub:hover .secondary-actions {
    opacity: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .action-hub {
        gap: 4px;
        padding: 4px;
    }

    .action-btn {
        width: 32px;
        height: 32px;
    }

    .action-view {
        width: 36px;
        height: 36px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// 🚀 FONCTIONS RÉVOLUTIONNAIRES - JAVASCRIPT ULTRA-MODERNE

/**
 * Édition rapide avec modal intelligente
 */
function quickEdit(studentId) {
    // Animation de feedback
    const btn = event.target.closest('.action-btn');
    btn.style.transform = 'scale(0.9)';
    setTimeout(() => btn.style.transform = '', 150);

    // TODO: Ouvrir modal d'édition rapide
    window.location.href = `/evc/app/admin/students/${studentId}/edit`;
}

/**
 * Toggle intelligent avec animation
 */
function smartToggle(studentId, newStatus) {
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
            statusBtn.setAttribute('onclick', `smartToggle(${studentId}, 0)`);
            statusIndicator.className = 'status-indicator online';
        } else {
            statusBtn.className = 'action-btn action-status inactive';
            statusBtn.innerHTML = '<i class="fas fa-user-plus"></i>';
            statusBtn.setAttribute('data-tooltip', 'Activer');
            statusBtn.setAttribute('onclick', `smartToggle(${studentId}, 1)`);
            statusIndicator.className = 'status-indicator offline';
        }

        statusBtn.disabled = false;

        // Animation de succès
        statusBtn.style.transform = 'scale(1.2)';
        setTimeout(() => statusBtn.style.transform = '', 200);

        // Notification toast moderne
        showModernToast(newStatus === 1 ? 'Étudiant activé' : 'Étudiant désactivé', 'success');

    }, 800);
}

/**
 * Menu d'actions rapides contextuel
 */
function showQuickActions(studentId) {
    const actionHub = document.querySelector(`[data-student-id="${studentId}"]`);

    // Créer menu contextuel moderne
    const quickMenu = document.createElement('div');
    quickMenu.className = 'quick-actions-menu';
    quickMenu.innerHTML = `
        <div class="quick-action" onclick="exportStudent(${studentId})">
            <i class="fas fa-download"></i> Exporter
        </div>
        <div class="quick-action" onclick="duplicateStudent(${studentId})">
            <i class="fas fa-copy"></i> Dupliquer
        </div>
        <div class="quick-action" onclick="sendMessage(${studentId})">
            <i class="fas fa-envelope"></i> Message
        </div>
        <div class="quick-action danger" onclick="deleteStudent(${studentId})">
            <i class="fas fa-trash"></i> Supprimer
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
 * Toast notification moderne
 */
function showModernToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `modern-toast toast-${type}`;
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

// Actions supplémentaires
function exportStudent(id) { console.log('Export étudiant:', id); }
function duplicateStudent(id) { console.log('Dupliquer étudiant:', id); }
function sendMessage(id) { console.log('Envoyer message:', id); }
function deleteStudent(id) {
    if (confirm('Supprimer définitivement cet étudiant ?')) {
        console.log('Supprimer étudiant:', id);
    }
}

console.log('🚀 Interface révolutionnaire initialisée !');
</script>

<!-- CSS pour les composants dynamiques -->
<style>
.quick-actions-menu {
    position: fixed;
    top: 50%;
    right: 20px;
    transform: translateY(-50%) translateY(20px);
    background: white;
    border-radius: 12px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    padding: 12px;
    z-index: 10000;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.quick-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.2s ease;
    color: #333;
    font-size: 14px;
}

.quick-action:hover {
    background: #f8f9fa;
}

.quick-action.danger {
    color: #dc3545;
}

.quick-action.danger:hover {
    background: #fff5f5;
}

.modern-toast {
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

.toast-success {
    border-left: 4px solid #28a745;
    color: #28a745;
}

.toast-info {
    border-left: 4px solid #007bff;
    color: #007bff;
}
</style>
@endpush
