<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'EVC 2026 - Profil Étudiant')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Page Specific Styles -->
    @yield('styles')
    @stack('styles')

    <style>
        :root {
            --primary-color: #003366;
            --secondary-color: #3399ff;
            --accent-color: #ff6633;
            --warning-color: #FF9900;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --info-color: #3399ff;
            --dark-color: #003366;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
            color: var(--dark-color);
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            text-decoration: none;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Top Navigation - Dynamic Version */
        .dynamic-topbar {
            background: linear-gradient(135deg, #003366 0%, #3399ff 100%);
            padding: 1rem 2rem;
            box-shadow: 0 4px 20px rgba(0, 51, 102, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
            z-index: 1000;
        }

        .dynamic-topbar::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .page-title-animated {
            margin: 0;
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            animation: fadeInLeft 0.8s ease-out;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
            animation: fadeInRight 0.8s ease-out;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Notification Bell */
        .notification-bell .notification-btn {
            border: none;
            background: transparent;
            transition: all 0.3s ease;
            position: relative;
        }

        .notification-bell .notification-btn:hover {
            transform: scale(1.1);
            color: #FF9900 !important;
        }

        .notification-badge {
            animation: pulse 2s infinite;
            font-size: 0.7rem;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* Profile Dropdown */
        .profile-dropdown .profile-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .profile-dropdown .profile-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .profile-avatar {
            position: relative;
        }

        .profile-avatar img {
            width: 40px;
            height: 40px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        .profile-btn:hover .profile-avatar img {
            border-color: #FF9900;
            transform: scale(1.05);
        }

        .status-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }

        .status-indicator.online {
            background: #28a745;
            animation: statusPulse 2s infinite;
        }

        @keyframes statusPulse {
            0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
            100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .profile-name {
            font-weight: 600;
            font-size: 0.95rem;
            line-height: 1.2;
        }

        .profile-role {
            font-size: 0.8rem;
            opacity: 0.8;
            line-height: 1;
        }

        .dropdown-arrow {
            transition: transform 0.3s ease;
            font-size: 0.8rem;
        }

        .profile-dropdown.show .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Profile Dropdown Container */
        .profile-dropdown {
            position: relative;
            z-index: 99999;
        }

        /* Profile Menu */
        .profile-menu {
            background: white !important;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25) !important;
            padding: 0;
            margin-top: 0.5rem;
            min-width: 280px;
            animation: dropdownSlide 0.3s ease-out;
            z-index: 999999 !important;
            position: fixed !important;
            right: 20px;
            top: 70px;
            display: none;
        }

        .profile-dropdown.show .profile-menu {
            display: block !important;
        }

        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .profile-menu .dropdown-header {
            background: linear-gradient(135deg, #003366 0%, #3399ff 100%);
            color: white;
            padding: 1.5rem;
            margin: 0;
            border-radius: 15px 15px 0 0;
        }

        .profile-menu .user-info {
            display: flex;
            align-items: center;
        }

        .profile-menu .user-info img {
            width: 50px;
            height: 50px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .profile-menu-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .profile-menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, #3399ff, #003366);
            transition: width 0.3s ease;
            z-index: -1;
        }

        .profile-menu-item:hover {
            color: white;
            transform: translateX(5px);
        }

        .profile-menu-item:hover::before {
            width: 100%;
        }

        .profile-menu-item i {
            width: 20px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .profile-menu-item:hover i {
            transform: scale(1.2);
        }

        .logout-item:hover {
            background: linear-gradient(90deg, #dc3545, #c82333) !important;
        }

        .logout-item:hover::before {
            background: linear-gradient(90deg, #dc3545, #c82333);
        }

        /* Content Area */
        .content-wrapper {
            padding: 2rem;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            border: none;
            padding: 1.5rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 51, 102, 0.4);
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
        }

        .btn-warning:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 102, 51, 0.4);
        }

        /* Profile Image */
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-active { background-color: #e6f3ff; color: var(--primary-color); }
        .status-inactive { background-color: #fee2e2; color: #991b1b; }
        .status-graduated { background-color: #fff2e6; color: var(--warning-color); }
        .status-suspended { background-color: #ffe6e6; color: var(--accent-color); }

        /* Mobile Toggle Button */
        .mobile-toggle {
            border: none !important;
            background: transparent !important;
            transition: all 0.3s ease;
            padding: 0.5rem;
            border-radius: 8px;
        }

        .mobile-toggle:hover {
            background: rgba(255, 255, 255, 0.1) !important;
            transform: scale(1.1);
        }

        .mobile-toggle:focus {
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.3);
            outline: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100vh;
                width: var(--sidebar-width);
                transform: translateX(-100%);
                z-index: 99999;
                box-shadow: 5px 0 15px rgba(0, 0, 0, 0.3);
            }

            .main-content {
                margin-left: 0;
                width: 100%;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            /* Overlay pour fermer la sidebar */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100vw;
                height: 100vh;
                background: rgba(0, 0, 0, 0.5);
                z-index: 99998;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }

            /* Topbar mobile adjustments */
            .dynamic-topbar {
                padding: 1rem;
            }

            .topbar-left {
                display: flex;
                align-items: center;
            }

            .page-title-animated {
                font-size: 1.2rem;
            }

            /* Profile dropdown mobile */
            .profile-menu {
                right: 10px;
                top: 60px;
                min-width: 250px;
            }

            .profile-info {
                display: none;
            }

            .profile-btn {
                padding: 0.5rem;
            }
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @yield('styles')
</head>
<body>
    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('dashboard') }}" class="sidebar-brand">
                <img src="{{ asset('assets/img/logo_white.png') }}" alt="EVC Logo" style="height: 100px; width: auto; margin-right: 10px;">

            </a>
            <!-- Close button for mobile -->
            <button class="btn btn-link text-white d-md-none ms-auto" onclick="closeSidebar()" style="font-size: 1.2rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <nav class="sidebar-nav">
            <!-- Vue d'ensemble -->
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i>
                    Vue d'ensemble
                </a>
            </div>

            <!-- Formation -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.formations.index') }}" class="nav-link {{ request()->routeIs('design-graphique.formations.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    Formation Ultra-pratique
                </a>
            </div>

            <!-- TP (Travaux Pratiques) -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.tp.index') }}" class="nav-link {{ request()->routeIs('design-graphique.tp.*') ? 'active' : '' }}">
                    <i class="fas fa-flask"></i>
                    Travaux pratiques (TP)
                </a>
            </div>

            <!-- Projets -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.projets.index') }}" class="nav-link {{ request()->routeIs('design-graphique.projets.*') ? 'active' : '' }}">
                    <i class="fas fa-project-diagram"></i>
                    Projets réels
                </a>
            </div>

            <!-- Events -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.events.index') }}" class="nav-link {{ request()->routeIs('design-graphique.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>
                    Evènements & Ateliers
                </a>
            </div>

            <!-- Actualité -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.actualites.index') }}" class="nav-link {{ request()->routeIs('design-graphique.actualites.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i>
                    Actualités & Annonces
                </a>
            </div>

            <!-- Bibliothèque -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.documents.index') }}" class="nav-link {{ request()->routeIs('design-graphique.documents.*') ? 'active' : '' }}">
                    <i class="fas fa-book"></i>
                    Bibliothèque
                </a>
            </div>



            <!-- Espace Communautaire -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.communaute.index') }}" class="nav-link {{ request()->routeIs('design-graphique.communaute.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    Classes Virtuelles
                </a>
            </div>

            <!-- CVThèque -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.cvtheque.index') }}" class="nav-link {{ request()->routeIs('design-graphique.cvtheque.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    CVThèque & Candidature
                </a>
            </div>

            <!-- Programme -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.programme.index') }}" class="nav-link {{ request()->routeIs('design-graphique.programme.*') ? 'active' : '' }}">
                    <i class="fas fa-brain"></i>
                    Programme Complet
                </a>
            </div>

            <!-- Paiement -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.paiements.index') }}" class="nav-link {{ request()->routeIs('design-graphique.paiements.*') ? 'active' : '' }}">
                    <i class="fas fa-credit-card"></i>
                    Paiement & Facturation
                </a>
            </div>

            <!-- Fin de formation -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.fin-formation.index') }}" class="nav-link {{ request()->routeIs('design-graphique.fin-formation.*') ? 'active' : '' }}">
                    <i class="fas fa-flag-checkered"></i>
                    Fin de formation
                </a>
            </div>

            <!-- Paramètres -->
            <div class="nav-item">
                <a href="{{ route('design-graphique.parametres.index') }}" class="nav-link {{ request()->routeIs('design-graphique.parametres.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i>
                    Paramètres
                </a>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <div class="topbar dynamic-topbar">
            <div class="topbar-left">
                <!-- Mobile Menu Toggle -->
                <button class="btn btn-link text-white d-md-none me-3 mobile-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars fa-lg"></i>
                </button>
                <h4 class="page-title-animated">@yield('page-title', 'Dashboard')</h4>
            </div>
            <div class="topbar-right">
                <!-- Notifications -->
                <div class="notification-bell me-3">
                    <button class="btn btn-link text-white position-relative notification-btn">
                        <i class="fas fa-bell fa-lg"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger notification-badge">
                            3
                            <span class="visually-hidden">notifications non lues</span>
                        </span>
                    </button>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown profile-dropdown">
                    <button class="btn profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="profile-avatar">
                            @if(session('user_photo') && session('user_photo') != '')
                                <img src="{{ asset('uploads/photos/' . basename(session('user_photo'))) }}" alt="Avatar" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                            @endif
                            <div class="status-indicator online"></div>
                        </div>
                        <div class="profile-info">
                            <span class="profile-name">
                                @if(session('user_prenom') && session('user_nom'))
                                    {{ session('user_prenom') }} {{ session('user_nom') }}
                                @elseif(session('user_name'))
                                    {{ session('user_name') }}
                                @else
                                    Utilisateur
                                @endif
                            </span>
                            <small class="profile-role">
                                @if(session('user_formation'))
                                    {{ ucfirst(str_replace('_', ' ', session('user_formation'))) }}
                                @else
                                    Étudiant EVC
                                @endif
                            </small>
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </button>
                    <ul class="dropdown-menu profile-menu">
                        <li class="dropdown-header">
                            <div class="user-info">
                                @if(session('user_photo') && session('user_photo') != '')
                                    <img src="{{ asset('uploads/photos/' . basename(session('user_photo'))) }}" alt="Avatar" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ session('user_name', 'Utilisateur') }}</h6>
                                    <small class="text-muted">{{ session('user_email', 'email@evc.com') }}</small>
                                </div>
                            </div>
                        </li>
                        <li><hr class="dropdown-divider"></li>

                        <!-- Informations utilisateur -->
                        <li class="dropdown-header px-3 py-2">
                            <div class="row g-2 text-center">
                                <div class="col-4">
                                    <small class="text-muted d-block">Niveau</small>
                                    <span class="badge bg-primary">{{ session('user_level', 'Débutant') }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Pays</small>
                                    <span class="badge bg-info">{{ session('user_pays', 'France') }}</span>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted d-block">Ville</small>
                                    <span class="badge bg-secondary">{{ session('user_ville', 'Paris') }}</span>
                                </div>
                            </div>
                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item profile-menu-item" href="{{ route('dashboard') }}">
                            <i class="fas fa-chart-pie me-3"></i>
                            <span>Mon Espace Étudiant</span>
                        </a></li>
                        <li><a class="dropdown-item profile-menu-item" href="{{ route('design-graphique.profil.editer') }}">
                            <i class="fas fa-user-edit me-3"></i>
                            <span>Modifier mon Profil</span>
                        </a></li>
                        <li><a class="dropdown-item profile-menu-item" href="{{ route('design-graphique.cvtheque.index') }}">
                            <i class="fas fa-briefcase me-3"></i>
                            <span>Ma CVThèque</span>
                        </a></li>
                        <li><a class="dropdown-item profile-menu-item" href="{{ route('design-graphique.programme.index') }}">
                            <i class="fas fa-calendar-alt me-3"></i>
                            <span>Mon Programme</span>
                        </a></li>
                        <li><a class="dropdown-item profile-menu-item" href="{{ route('design-graphique.paiements.index') }}">
                            <i class="fas fa-credit-card me-3"></i>
                            <span>Mes Paiements</span>
                        </a></li>
                        <li><a class="dropdown-item profile-menu-item" href="{{ route('design-graphique.parametres.index') }}">
                            <i class="fas fa-cog me-3"></i>
                            <span>Paramètres</span>
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-item profile-menu-item logout-item" style="border: none; background: none; width: 100%; text-align: left;">
                                    <i class="fas fa-sign-out-alt me-3"></i>
                                    <span>Déconnexion</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Page Specific Scripts -->
    @stack('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Add fade-in animation to cards
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, index * 100);
            });

            // Profile dropdown management
            const profileBtn = document.querySelector('.profile-btn');
            const profileDropdown = document.querySelector('.profile-dropdown');
            const profileMenu = document.querySelector('.profile-menu');

            if (profileBtn && profileDropdown && profileMenu) {
                profileBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Toggle dropdown
                    profileDropdown.classList.toggle('show');

                    // Update aria-expanded
                    const isExpanded = profileDropdown.classList.contains('show');
                    profileBtn.setAttribute('aria-expanded', isExpanded);
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!profileDropdown.contains(e.target)) {
                        profileDropdown.classList.remove('show');
                        profileBtn.setAttribute('aria-expanded', 'false');
                    }
                });

                // Close dropdown when pressing Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && profileDropdown.classList.contains('show')) {
                        profileDropdown.classList.remove('show');
                        profileBtn.setAttribute('aria-expanded', 'false');
                        profileBtn.focus();
                    }
                });
            }
        });

        // Mobile sidebar management
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');

            // Prevent body scroll when sidebar is open
            if (sidebar.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = 'auto';
            }
        }

        function closeSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.sidebar-overlay');

            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            document.body.style.overflow = 'auto';
        }

        // Close sidebar on window resize if mobile
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                closeSidebar();
            }
        });

        // Close sidebar with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
