<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="redirect-url" content="{{ session('redirect_to', route('dashboard.design-graphique')) }}">
    <title>Connexion en cours - EVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/loading.css') }}" rel="stylesheet">

</head>
<body>
    <!-- Particules d'arrière-plan -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="loading-container">
        <!-- Logo -->
        <div class="logo-container">
            <div class="logo">
                <img src="{{ asset('assets/img/loading.png') }}" alt="Loading" class="loading-icon">
            </div>
        </div>

        <!-- Texte de bienvenue -->
        <div class="welcome-text">
            <h2>Bienvenue sur EVC</h2>
            <p>Connexion à votre espace étudiant en cours...</p>
        </div>

        <!-- Informations utilisateur -->
        <div class="user-info">
            <h3 id="userName">
                @if(session('user_prenom') && session('user_nom'))
                    {{ session('user_prenom') }} {{ session('user_nom') }}
                @else
                    Étudiant EVC
                @endif
            </h3>
            <div class="formation-badge" id="userFormation">
                <i class="fas fa-palette me-2"></i>
                {{ session('user_formation_display') ?: (session('user_formation') ? ucfirst(str_replace(['_', '-'], ' ', session('user_formation'))) : 'Design Graphique') }}
            </div>
        </div>

        <!-- Spinner de chargement -->
        <div class="loading-spinner">
            <div class="spinner"></div>
            <div class="loading-text" id="loadingText">Initialisation de votre espace...</div>
        </div>

        <!-- Barre de progression -->
        <div class="progress-container">
            <div class="progress">
                <div class="progress-bar" id="progressBar" style="width: 0%"></div>
            </div>
            <div class="progress-text" id="progressText">0%</div>
        </div>
    </div>

    <!-- Effet de vague -->
    <div class="wave-effect"></div>

    <script src="{{ asset('assets/js/loading.js') }}"></script>
</body>
</html>
