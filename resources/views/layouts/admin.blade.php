<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CRM-EVC')</title>

    @stack('meta')

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
    <div class="admin-sidebar-overlay" id="adminSidebarOverlay"></div>
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
                            <i class="fas fa-home"></i>
                            Tableau de bord
                        </a>
                    </li>

                    <!-- Séparateur : Gestion Académique -->
                    <li class="nav-section-title">
                        <i class="fas fa-graduation-cap me-2"></i>Gestion Académique
                    </li>

                    <!-- Pré-inscriptions -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.preinscriptions.index') }}" class="admin-nav-link {{ request()->routeIs('admin.preinscriptions.*') ? 'active' : '' }}">
                            <i class="fas fa-user-plus"></i>
                            Pré-inscriptions
                        </a>
                    </li>

                    <!-- Gestion des Étudiants -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.etudiants.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#studentsMenu">
                            <i class="fas fa-users"></i>
                            Gestion des Étudiants
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.etudiants.*') ? 'show' : '' }}" id="studentsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.etudiants.design-graphique') }}" class="admin-nav-sublink {{ request()->routeIs('admin.etudiants.design-graphique') ? 'active' : '' }}">
                                    <i class="fas fa-palette"></i>Design Graphique
                                </a></li>
                                <li><a href="{{ route('admin.etudiants.community-management') }}" class="admin-nav-sublink {{ request()->routeIs('admin.etudiants.community-management') ? 'active' : '' }}">
                                    <i class="fas fa-share-alt"></i>Community Management
                                </a></li>
                                <li><a href="{{ route('admin.etudiants.design-graphique-community-manager') }}" class="admin-nav-sublink {{ request()->routeIs('admin.etudiants.design-graphique-community-manager') ? 'active' : '' }}">
                                    <i class="fas fa-palette-alt"></i>Design Graphique & Community Manager
                                </a></li>
                                <li><a href="{{ route('admin.etudiants.intelligence-artificielle') }}" class="admin-nav-sublink {{ request()->routeIs('admin.etudiants.intelligence-artificielle') ? 'active' : '' }}">
                                    <i class="fas fa-robot"></i>Intelligence Artificielle
                                </a></li>
                                <li><a href="{{ route('admin.etudiants.gestion-informatique') }}" class="admin-nav-sublink {{ request()->routeIs('admin.etudiants.gestion-informatique') ? 'active' : '' }}">
                                    <i class="fas fa-server"></i>Gestion Informatique
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    @if(session('admin_role') === 'super_admin')
                    <!-- Formations -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.formations.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#formationsMenu">
                            <i class="fas fa-chalkboard-teacher"></i>
                            Formations
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.formations.*') ? 'show' : '' }}" id="formationsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.formations.index') }}" class="admin-nav-sublink {{ request()->routeIs('admin.formations.index') ? 'active' : '' }}">
                                    <i class="fas fa-book-open"></i>Toutes les formations
                                </a></li>
                                <li><a href="{{ route('admin.formations.categories.index') }}" class="admin-nav-sublink {{ request()->routeIs('admin.formations.categories.*') ? 'active' : '' }}">
                                    <i class="fas fa-tags"></i>Catégories
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Programmes -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.programmes') }}" class="admin-nav-link {{ request()->routeIs('admin.programmes') ? 'active' : '' }}">
                            <i class="fas fa-file-alt"></i>
                            Programmes
                        </a>
                    </li>
                    @endif

                    <!-- Séparateur : Contenu Pédagogique -->
                    <li class="nav-section-title">
                        <i class="fas fa-book me-2"></i>Contenu Pédagogique
                    </li>

                    @if(session('admin_role') === 'super_admin')
                    <!-- Bibliothèque -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.bibliotheque.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#bibliothequeMenu">
                            <i class="fas fa-book-reader"></i>
                            Bibliothèque
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.bibliotheque.*') ? 'show' : '' }}" id="bibliothequeMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.bibliotheque.index') }}" class="admin-nav-sublink {{ request()->routeIs('admin.bibliotheque.index') ? 'active' : '' }}">
                                    <i class="fas fa-books"></i>Tous les médias
                                </a></li>
                                <li><a href="{{ route('admin.bibliotheque.categories.index') }}" class="admin-nav-sublink {{ request()->routeIs('admin.bibliotheque.categories.*') ? 'active' : '' }}">
                                    <i class="fas fa-folder-open"></i>Catégories
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Rapports Étudiants -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#documentsMenu">
                            <i class="fas fa-file-pdf"></i>
                            Rapports Étudiants
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.documents.*') ? 'show' : '' }}" id="documentsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.documents.pending') }}" class="admin-nav-sublink">
                                    <i class="fas fa-clock"></i>À valider
                                </a></li>
                                <li><a href="{{ route('admin.documents.all') }}" class="admin-nav-sublink">
                                    <i class="fas fa-file-invoice"></i>Tous
                                </a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    <!-- Séparateur : Travaux & Projets -->
                    <li class="nav-section-title">
                        <i class="fas fa-tasks me-2"></i>Travaux & Projets
                    </li>

                    <li class="admin-nav-item">
                        <a href="{{ route('admin.projets.design-graphique.to-send') }}" class="admin-nav-link {{ request()->routeIs('admin.projets.design-graphique.to-send') ? 'active' : '' }}">
                            <i class="fas fa-paper-plane"></i>
                            ENVOYER UN PROJET
                        </a>
                    </li>

                    <li class="admin-nav-item">
                        <a href="{{ route('admin.projets.design-graphique.assigned') }}" class="admin-nav-link {{ request()->routeIs('admin.projets.design-graphique.assigned') ? 'active' : '' }}">
                            <i class="fas fa-tasks"></i>
                            Projets attribués
                        </a>
                    </li>

                    <!-- Projets Design Graphique -->
                    @php
                        $isDesignGraphiqueMenuActive = request()->routeIs('admin.projets.design-graphique.*')
                            && !request()->routeIs('admin.projets.design-graphique.to-send')
                            && !request()->routeIs('admin.projets.design-graphique.assigned');
                    @endphp
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ $isDesignGraphiqueMenuActive ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#designGraphiqueMenu">
                            <i class="fas fa-palette"></i>
                            Projets Design Graphique
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ $isDesignGraphiqueMenuActive ? 'show' : '' }}" id="designGraphiqueMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.projets.design-graphique.pending') }}" class="admin-nav-sublink {{ request()->routeIs('admin.projets.design-graphique.pending') ? 'active' : '' }}">
                                    <i class="fas fa-clock"></i>À valider
                                </a></li>
                                <li><a href="{{ route('admin.studio-creative') }}" class="admin-nav-sublink">
                                    <i class="fas fa-external-link-alt"></i>Studio Creative
                                </a></li>
                                <li><a href="{{ route('admin.projets.design-graphique.all') }}" class="admin-nav-sublink {{ request()->routeIs('admin.projets.design-graphique.all') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i>Tous les projets
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Projets CM/SMM -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.projets.cm-smm.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#cmSmmMenu">
                            <i class="fas fa-hashtag"></i>
                            Projets CM/SMM
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.projets.cm-smm.*') ? 'show' : '' }}" id="cmSmmMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.projets.cm-smm.pending') }}" class="admin-nav-sublink {{ request()->routeIs('admin.projets.cm-smm.pending') ? 'active' : '' }}">
                                    <i class="fas fa-clock"></i>À valider
                                </a></li>
                                <li><a href="{{ route('admin.projets.cm-smm.all') }}" class="admin-nav-sublink {{ request()->routeIs('admin.projets.cm-smm.all') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i>Tous les projets
                                </a></li>
                            </ul>
                        </div>
                    </li>

                    <!-- Projets Design & CM -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.projets.design-cm.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#designCmMenu">
                            <i class="fas fa-object-group"></i>
                            Projets Design & CM
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.projets.design-cm.*') ? 'show' : '' }}" id="designCmMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.projets.design-cm.pending') }}" class="admin-nav-sublink {{ request()->routeIs('admin.projets.design-cm.pending') ? 'active' : '' }}">
                                    <i class="fas fa-clock"></i>À valider
                                </a></li>
                                <li><a href="{{ route('admin.projets.design-cm.all') }}" class="admin-nav-sublink {{ request()->routeIs('admin.projets.design-cm.all') ? 'active' : '' }}">
                                    <i class="fas fa-list"></i>Tous les projets
                                </a></li>
                            </ul>
                        </div>
                    </li>


                    <!-- Séparateur : Communication -->
                    <li class="nav-section-title">
                        <i class="fas fa-bullhorn me-2"></i>Communication
                    </li>

                    @if(in_array(session('admin_role'), ['super_admin', 'assistant']))
                    <!-- Événements -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.articles.evenements') }}" class="admin-nav-link">
                            <i class="fas fa-calendar-alt"></i>
                            Événements
                        </a>
                    </li>

                    <!-- Actualités -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.articles.actualites') }}" class="admin-nav-link">
                            <i class="fas fa-newspaper"></i>
                            Actualités
                        </a>
                    </li>

                    <!-- Communiqués -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.communiques.index') }}" class="admin-nav-link {{ request()->routeIs('admin.communiques.*') ? 'active' : '' }}">
                            <i class="fas fa-bullhorn"></i>
                            Communiqués
                        </a>
                    </li>
                    @endif

                    <!-- Séparateur : Finances & Certificats -->
                    <li class="nav-section-title">
                        <i class="fas fa-coins me-2"></i>Finances & Certificats
                    </li>

                    @if(in_array(session('admin_role'), ['super_admin', 'comptable']))
                    <!-- Comptabilité -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.accounting.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#accountingMenu">
                            <i class="fas fa-calculator"></i>
                            Comptabilité
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.accounting.*') ? 'show' : '' }}" id="accountingMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.accounting.index') }}" class="admin-nav-sublink {{ request()->routeIs('admin.accounting.index') ? 'active' : '' }}">
                                    <i class="fas fa-tachometer-alt"></i>Vue d'ensemble
                                </a></li>
                                <li><a href="{{ route('admin.accounting.expenses') }}" class="admin-nav-sublink {{ request()->routeIs('admin.accounting.expenses') ? 'active' : '' }}">
                                    <i class="fas fa-file-invoice-dollar"></i>Dépenses
                                </a></li>
                                <li><a href="{{ route('admin.accounting.sales') }}" class="admin-nav-sublink {{ request()->routeIs('admin.accounting.sales') ? 'active' : '' }}">
                                    <i class="fas fa-chart-line"></i>Ventes
                                </a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(in_array(session('admin_role'), ['super_admin', 'comptable']))
                    <!-- Paiements -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.paiements.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#paiementsMenu">
                            <i class="fas fa-wallet"></i>
                            Paiements
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.payments.*') || request()->routeIs('admin.paiements.*') ? 'show' : '' }}" id="paiementsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.payments.index') }}" class="admin-nav-sublink {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                                    <i class="fas fa-money-bill-wave"></i>Gestion des Paiements
                                </a></li>
                                <li><a href="{{ route('admin.paiements.a-jour') }}" class="admin-nav-sublink {{ request()->routeIs('admin.paiements.a-jour') ? 'active' : '' }}">
                                    <i class="fas fa-check-circle"></i>À jour
                                </a></li>
                                <li><a href="{{ route('admin.paiements.a-solder') }}" class="admin-nav-sublink {{ request()->routeIs('admin.paiements.a-solder') ? 'active' : '' }}">
                                    <i class="fas fa-exclamation-circle"></i>À solder
                                </a></li>
                                <li><a href="{{ route('admin.paiements.reste-a-payer') }}" class="admin-nav-sublink {{ request()->routeIs('admin.paiements.reste-a-payer') ? 'active' : '' }}">
                                    <i class="fas fa-clock"></i>Reste à payer
                                </a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(session('admin_role') === 'super_admin')
                    <!-- Certificats -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#certificatsMenu">
                            <i class="fas fa-certificate"></i>
                            Certificats
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse" id="certificatsMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.certificats.eligible') }}" class="admin-nav-sublink">
                                    <i class="fas fa-check-circle"></i>Éligibles
                                </a></li>
                                <li><a href="{{ route('admin.certificats.not-eligible') }}" class="admin-nav-sublink">
                                    <i class="fas fa-times-circle"></i>Non éligibles
                                </a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(session('admin_role') === 'super_admin')
                    <!-- Gestion des CVthèque -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.cvtheque.profiles') }}" class="admin-nav-link">
                            <i class="fas fa-briefcase"></i>
                            CVthèque - Profils
                        </a>
                    </li>
                    @endif

                    @if(session('admin_role') === 'super_admin')
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.donations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
                            <i class="fas fa-hand-holding-heart"></i>
                            Dons
                        </a>
                    </li>
                    @endif

                    @if(in_array(session('admin_role'), ['super_admin', 'assistant']))
                    <!-- Gestion WebTV -->
                    <li class="admin-nav-item dropdown">
                        <a href="#" class="admin-nav-link dropdown-toggle {{ request()->routeIs('admin.webtv.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#webtvMenu">
                            <i class="fas fa-tv"></i>
                            WebTV
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <div class="collapse {{ request()->routeIs('admin.webtv.*') ? 'show' : '' }}" id="webtvMenu">
                            <ul class="admin-nav-submenu">
                                <li><a href="{{ route('admin.webtv.videos') }}" class="admin-nav-sublink {{ request()->routeIs('admin.webtv.videos*') ? 'active' : '' }}">
                                    <i class="fas fa-video"></i>Programmer un Live
                                </a></li>
                                <li><a href="{{ route('admin.webtv.subscribers') }}" class="admin-nav-sublink {{ request()->routeIs('admin.webtv.subscribers') || request()->routeIs('admin.webtv.show') ? 'active' : '' }}">
                                    <i class="fas fa-users"></i>Abonnés
                                </a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(session('admin_role') === 'super_admin')
                    <!-- Gestion des Rapports -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.rapports') }}" class="admin-nav-link">
                            <i class="fas fa-chart-line"></i>
                            Gestion des Rapports
                        </a>
                    </li>
                    @endif

                    @if(session('admin_role') === 'super_admin')
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
                                    <i class="fas fa-users-cog"></i>Liste des Admins
                                </a></li>
                                <li><a href="{{ route('admin.admins.create') }}" class="admin-nav-sublink">
                                    <i class="fas fa-user-plus"></i>Ajouter un Admin
                                </a></li>
                            </ul>
                        </div>
                    </li>
                    @endif

                    @if(session('admin_role') === 'super_admin')
                    <!-- Paramètres -->
                    <li class="admin-nav-item">
                        <a href="{{ route('admin.parametres.index') }}" class="admin-nav-link {{ request()->routeIs('admin.parametres.*') ? 'active' : '' }}">
                            <i class="fas fa-cog"></i>
                            Paramètres
                        </a>
                    </li>
                    @endif
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="admin-mobile-menu-btn" id="adminMobileMenuBtn" aria-label="Ouvrir le menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="mb-0">@yield('title', 'CRM-EVC')</h1>
                </div>

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

    <script>
        (function () {
            const btn = document.getElementById('adminMobileMenuBtn');
            const overlay = document.getElementById('adminSidebarOverlay');
            const sidebar = document.querySelector('.admin-sidebar');
            if (!btn || !overlay) return;

            function openSidebar() {
                document.body.classList.add('sidebar-open');
                if (sidebar) sidebar.classList.add('show');
                overlay.classList.add('show');
            }
            function closeSidebar() {
                document.body.classList.remove('sidebar-open');
                if (sidebar) sidebar.classList.remove('show');
                overlay.classList.remove('show');
            }

            function toggleSidebar() {
                if (document.body.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }

            window.__toggleAdminSidebar = toggleSidebar;

            btn.addEventListener('click', toggleSidebar);
            btn.addEventListener('touchstart', function (e) {
                e.preventDefault();
                toggleSidebar();
            }, { passive: false });

            overlay.addEventListener('click', function () {
                closeSidebar();
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 1024) {
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>
