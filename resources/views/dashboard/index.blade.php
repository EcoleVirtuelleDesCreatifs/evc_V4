@extends('layouts.ki-admin')

@section('title', 'Espace étudiant - EVC 2024')
@section('page-title', 'Espace étudiant')

@section('content')
<!-- En-tête avec informations utilisateur -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #003366 0%, #3399ff 100%); color: white;">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-2 text-center">
                        <img src="https://via.placeholder.com/100x100/003366/ffffff?text=JD" 
                             alt="Photo de profil" class="rounded-circle" 
                             style="width: 100px; height: 100px; object-fit: cover; border: 3px solid white;">
                    </div>
                    <div class="col-md-6">
                        <h3 class="mb-2">
                            <i class="fas fa-user me-3"></i>
                            Jean Dupont - Formation Infographie EVC
                        </h3>
                        <p class="mb-2 opacity-75">Étudiant niveau Débutant • Inscrit depuis Avril 2024</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                Paris, France
                            </span>
                            <span class="badge bg-warning">
                                <i class="fas fa-graduation-cap me-1"></i>
                                Étudiant actif
                            </span>
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Profil 85% complété
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="row">
                            <div class="col-6">
                                <h4 class="mb-1">68%</h4>
                                <small class="opacity-75">Progression globale</small>
                            </div>
                            <div class="col-6">
                                <h4 class="mb-1">4.2/5</h4>
                                <small class="opacity-75">Note moyenne</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques principales -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center stats-card" style="border-left: 4px solid #003366;">
            <div class="card-body">
                <i class="fas fa-book fa-2x mb-3" style="color: #003366;"></i>
                <h4 style="color: #003366;">4</h4>
                <p class="text-muted mb-0">Modules de formation</p>
                <small class="text-success">
                    <i class="fas fa-arrow-up me-1"></i>
                    +1 ce mois
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center stats-card" style="border-left: 4px solid #3399ff;">
            <div class="card-body">
                <i class="fas fa-flask fa-2x mb-3" style="color: #3399ff;"></i>
                <h4 style="color: #3399ff;">12/15</h4>
                <p class="text-muted mb-0">TP réalisés</p>
                <small class="text-warning">
                    <i class="fas fa-clock me-1"></i>
                    3 restants
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center stats-card" style="border-left: 4px solid #ff6633;">
            <div class="card-body">
                <i class="fas fa-project-diagram fa-2x mb-3" style="color: #ff6633;"></i>
                <h4 style="color: #ff6633;">3/4</h4>
                <p class="text-muted mb-0">Projets terminés</p>
                <small class="text-info">
                    <i class="fas fa-tasks me-1"></i>
                    1 en cours
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center stats-card" style="border-left: 4px solid #FF9900;">
            <div class="card-body">
                <i class="fas fa-percentage fa-2x mb-3" style="color: #FF9900;"></i>
                <h4 style="color: #FF9900;">68%</h4>
                <p class="text-muted mb-0">Progression globale</p>
                <small class="text-success">
                    <i class="fas fa-chart-line me-1"></i>
                    +5% cette semaine
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques détaillées -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-center mini-stat">
            <div class="card-body py-3">
                <i class="fas fa-calendar-check text-success mb-2"></i>
                <h6 class="mb-1">156</h6>
                <small class="text-muted">Jours actifs</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center mini-stat">
            <div class="card-body py-3">
                <i class="fas fa-clock text-primary mb-2"></i>
                <h6 class="mb-1">245h</h6>
                <small class="text-muted">Temps d'étude</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center mini-stat">
            <div class="card-body py-3">
                <i class="fas fa-star text-warning mb-2"></i>
                <h6 class="mb-1">4.2/5</h6>
                <small class="text-muted">Note moyenne</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center mini-stat">
            <div class="card-body py-3">
                <i class="fas fa-trophy text-warning mb-2"></i>
                <h6 class="mb-1">8</h6>
                <small class="text-muted">Badges obtenus</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center mini-stat">
            <div class="card-body py-3">
                <i class="fas fa-euro-sign text-info mb-2"></i>
                <h6 class="mb-1">900€</h6>
                <small class="text-muted">Payé / 1200€</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center mini-stat">
            <div class="card-body py-3">
                <i class="fas fa-file-pdf text-danger mb-2"></i>
                <h6 class="mb-1">15</h6>
                <small class="text-muted">Documents</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Progression détaillée -->
    <div class="col-md-8">
        <!-- Progression par module -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2" style="color: #003366;"></i>
                    Progression par module
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fab fa-adobe me-2" style="color: #FF0000;"></i><strong>Adobe Photoshop</strong></span>
                            <span class="badge bg-success">85%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 85%; background-color: #FF0000;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">12/14 leçons complétées</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fab fa-adobe me-2" style="color: #FF9A00;"></i><strong>Adobe Illustrator</strong></span>
                            <span class="badge bg-warning">65%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 65%; background-color: #FF9A00;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">9/14 leçons complétées</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fab fa-adobe me-2" style="color: #FF3366;"></i><strong>Adobe InDesign</strong></span>
                            <span class="badge bg-info">45%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 45%; background-color: #FF3366;" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">6/14 leçons complétées</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span><i class="fas fa-chart-bar me-2" style="color: #3399ff;"></i><strong>Strategy Business</strong></span>
                            <span class="badge bg-secondary">25%</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 25%; background-color: #3399ff;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <small class="text-muted">3/12 leçons complétées</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Activité récente -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2" style="color: #ff6633;"></i>
                    Activité récente
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">TP Photoshop - Retouche photo complété</h6>
                                    <p class="mb-1 text-muted">Note obtenue : 18/20</p>
                                    <small class="text-muted">Aujourd'hui à 14:30</small>
                                </div>
                                <span class="badge bg-success">Excellent</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Connexion à la plateforme</h6>
                                    <p class="mb-1 text-muted">Session d'étude de 2h30</p>
                                    <small class="text-muted">Aujourd'hui à 09:15</small>
                                </div>
                                <span class="badge bg-primary">Actif</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Projet Illustrator soumis</h6>
                                    <p class="mb-1 text-muted">Création d'un logo d'entreprise</p>
                                    <small class="text-muted">Hier à 16:45</small>
                                </div>
                                <span class="badge bg-warning">En attente</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Paiement mensuel effectué</h6>
                                    <p class="mb-1 text-muted">Mensualité 3/4 - 300€</p>
                                    <small class="text-muted">Il y a 2 jours</small>
                                </div>
                                <span class="badge bg-success">Payé</span>
                            </div>
                        </div>
                    </div>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-secondary"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">Badge obtenu : "Maître Photoshop"</h6>
                                    <p class="mb-1 text-muted">Pour avoir complété 10 TP Photoshop</p>
                                    <small class="text-muted">Il y a 3 jours</small>
                                </div>
                                <span class="badge bg-warning"><i class="fas fa-trophy"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Profil utilisateur -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user-circle me-2" style="color: #003366;"></i>
                    Informations personnelles
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <img src="https://via.placeholder.com/80x80/003366/ffffff?text=JD" 
                         alt="Photo de profil" class="rounded-circle mb-2" 
                         style="width: 80px; height: 80px; border: 2px solid #3399ff;">
                    <h6 class="mb-1">Jean Dupont</h6>
                    <small class="text-muted">jean.dupont@email.com</small>
                </div>
                <div class="profile-details">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-birthday-cake text-primary me-2"></i>Âge :</span>
                        <span>25 ans</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-phone text-success me-2"></i>Téléphone :</span>
                        <span>+33 6 12 34 56 78</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="fas fa-graduation-cap text-warning me-2"></i>Niveau :</span>
                        <span>Débutant</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Badges -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2" style="color: #FF9900;"></i>
                    Badges obtenus
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="badge-item p-2 border rounded">
                            <i class="fas fa-medal text-warning fa-2x mb-2"></i>
                            <h6 class="mb-1">Maître Photoshop</h6>
                            <small class="text-muted">10 TP complétés</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="badge-item p-2 border rounded">
                            <i class="fas fa-star text-primary fa-2x mb-2"></i>
                            <h6 class="mb-1">Assidu</h6>
                            <small class="text-muted">30 jours consécutifs</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2" style="color: #003366;"></i>
                    Actions rapides
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="/formations" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-play me-2"></i>
                        Continuer la formation
                    </a>
                    <a href="/tp" class="btn btn-outline-info btn-sm">
                        <i class="fas fa-flask me-2"></i>
                        Voir les TP disponibles
                    </a>
                    <a href="/paiement" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-credit-card me-2"></i>
                        Gestion des paiements
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stats-card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
}

.mini-stat {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.mini-stat:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
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
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #3399ff;
}

.badge-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.badge-item:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
    border-color: #3399ff !important;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach((bar, index) => {
        const width = bar.style.width;
        bar.style.width = '0%';
        
        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = width;
        }, 500 + (index * 200));
    });
});
</script>
@endsection
