<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - EVC</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS from CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome from CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Votre CSS personnalisé reste local, mais nous nous assurons que le chemin est correct -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @yield('styles')
    @stack('styles')

    @push('styles')
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
    @endpush
</head>
<body>
    <div id="toast-container"></div>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <h3><i class="fas fa-graduation-cap"></i> EVC Admin</h3>
            </div>

            <nav>
                <ul class="admin-nav">
                    <!-- Dashboard -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt"></i>
                            Dashboard
                        </a>
                    </li>

                    <!-- Pré-inscriptions (Admin) -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.preinscriptions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.preinscriptions.*') ? 'active' : '' }}">
                            <i class="fas fa-user-check"></i>
                            Pré-inscriptions
                        </a>
                    </li>

                    <!-- Gestion des Étudiants -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#studentsMenu">
                            <i class="fas fa-users"></i>
                            Gestion des Étudiants
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="studentsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.etudiants.design-graphique') }}" class="admin-nav-sublink">
                                    <i class="fas fa-palette"></i>Design Graphique
                                </a></li>
                                <li><a href="{{ route('admin.etudiants.community-management') }}" class="admin-nav-sublink">
                                    <i class="fas fa-share-alt"></i>Community Management
                                </a></li>
                                <li><a href="{{ route('admin.etudiants.intelligence-artificielle') }}" class="admin-nav-sublink">
                                    <i class="fas fa-robot"></i>Intelligence Artificielle
                                </a></li>
                                <li><a href="{{ route('admin.etudiants.gestion-informatique') }}" class="admin-nav-sublink">
                                    <i class="fas fa-server"></i>Gestion Informatique
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des Formations -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#formationsMenu">
                            <i class="fas fa-chalkboard-teacher"></i>
                            Gestion des Formations
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="formationsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.formations.categories.index') }}" class="admin-nav-sublink">
                                    <i class="fas fa-tags"></i>Catégories
                                </a></li>
                                <li><a href="{{ route('admin.formations.index') }}" class="admin-nav-sublink">
                                    <i class="fas fa-book-open"></i>Formations
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Bibliothèque -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#bibliothequeMenu">
                            <i class="fas fa-book-reader"></i>
                            Bibliothèque
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="bibliothequeMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.bibliotheque.categories.index') }}" class="admin-nav-sublink">
                                    <i class="fas fa-folder-open"></i>Catégories
                                </a></li>
                                <li><a href="{{ route('admin.bibliotheque.index') }}" class="admin-nav-sublink">
                                    <i class="fas fa-book-open"></i>Bibliothèque
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des Documents -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#documentsMenu">
                            <i class="fas fa-folder-open"></i>
                            Gestion des Documents
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="documentsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.documents.pending') }}" class="admin-nav-sublink">
                                    <i class="fas fa-hourglass-half"></i>À valider
                                </a></li>
                                <li><a href="{{ route('admin.documents.all') }}" class="admin-nav-sublink">
                                    <i class="fas fa-file-invoice"></i>Tous documents
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des Programmes -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.programmes') }}" class="admin-nav-link">
                            <i class="fas fa-graduation-cap"></i>
                            Gestion des Programmes
                        </a>
                    </li>

                    <!-- Gestion des Travaux -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#travauxMenu">
                            <i class="fas fa-tasks"></i>
                            Gestion des Travaux
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="travauxMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.travaux.pending') }}" class="admin-nav-sublink">
                                    <i class="fas fa-hourglass-half"></i>À valider
                                </a></li>
                                <li><a href="{{ route('admin.travaux.to-send') }}" class="admin-nav-sublink">
                                    <i class="fas fa-paper-plane"></i>TP à envoyer
                                </a></li>
                                <li><a href="{{ route('admin.travaux.all') }}" class="admin-nav-sublink">
                                    <i class="fas fa-list"></i>Tous
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des Projets -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#projetsMenu">
                            <i class="fas fa-project-diagram"></i>
                            Gestion des Projets
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="projetsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.projets.pending') }}" class="admin-nav-sublink">
                                    <i class="fas fa-eye"></i>À valider
                                </a></li>
                                <li><a href="{{ route('admin.projets.to-send') }}" class="admin-nav-sublink">
                                    <i class="fas fa-share"></i>À envoyer
                                </a></li>
                                <li><a href="{{ route('admin.projets.all') }}" class="admin-nav-sublink">
                                    <i class="fas fa-folder-open"></i>Tous
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des Articles -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#articlesMenu">
                            <i class="fas fa-newspaper"></i>
                            Gestion des Articles
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="articlesMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.articles.evenements') }}" class="admin-nav-sublink">
                                    <i class="fas fa-calendar-alt"></i>Événements
                                </a></li>
                                <li><a href="{{ route('admin.articles.actualites') }}" class="admin-nav-sublink">
                                    <i class="fas fa-rss"></i>Actualités
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des Certificats -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#certificatsMenu">
                            <i class="fas fa-certificate"></i>
                            Gestion des Certificats
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="certificatsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.certificats.eligible') }}" class="admin-nav-sublink">
                                    <i class="fas fa-check-circle"></i>Éligibles
                                </a></li>
                                <li><a href="{{ route('admin.certificats.not-eligible') }}" class="admin-nav-sublink">
                                    <i class="fas fa-times-circle"></i>Pas éligibles
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des Paiements -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#paiementsMenu">
                            <i class="fas fa-credit-card"></i>
                            Gestion des Paiements
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="paiementsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.paiements.a-jour') }}" class="admin-nav-sublink">
                                    <i class="fas fa-check-double"></i>À jour
                                </a></li>
                                <li><a href="{{ route('admin.paiements.a-solder') }}" class="admin-nav-sublink">
                                    <i class="fas fa-exclamation-triangle"></i>À solder
                                </a></li>
                                <li><a href="{{ route('admin.paiements.reste-a-payer') }}" class="admin-nav-sublink">
                                    <i class="fas fa-clock"></i>Reste à payer
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Gestion des CVthèque -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.cvtheque') }}" class="admin-nav-link">
                            <i class="fas fa-user-tie"></i>
                            Gestion des CVthèque
                        </a>
                    </li>

                    <!-- Gestion des Rapports -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.rapports') }}" class="admin-nav-link">
                            <i class="fas fa-chart-line"></i>
                            Gestion des Rapports
                        </a>
                    </li>

                    <!-- Gestion des Admins -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#adminsMenu">
                            <i class="fas fa-user-shield"></i>
                            Gestion des Admins
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="adminsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.admins.index') }}" class="admin-nav-sublink">
                                    <i class="fas fa-users-cog"></i>Admins
                                </a></li>
                                <li><a href="{{ route('admin.admins.roles') }}" class="admin-nav-sublink">
                                    <i class="fas fa-key"></i>Rôles
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Paramètres -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.parametres.index') }}" class="admin-nav-link">
                            <i class="fas fa-cog"></i>
                            Paramètres
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <h1>@yield('title', 'Dashboard Admin')</h1>

                <div class="admin-user">
                    <div class="admin-user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">{{ session('admin_name', 'Admin') }}</div>
                        <small class="text-muted">Administrateur</small>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-white" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt me-2"></i>
                                        Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <!-- Messages Flash -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Erreurs de validation :</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Content -->
            <div class="admin-content">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

        @yield('scripts')
    @stack('scripts')
</body>
</html>
