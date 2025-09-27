@extends('layouts.ki-admin')

@section('title', 'Fin de Formation - EVC 2024')
@section('page-title', 'Fin de Formation - Infographie')

@section('content')
<!-- En-tête avec statut global -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #003366 0%, #3399ff 100%); color: white;">
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-graduation-cap me-3"></i>
                            Formation Infographie - Bilan de fin de formation
                        </h3>
                        <p class="mb-0 opacity-75">Suivi de votre progression et critères d'éligibilité à la certification</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="progress-circle-large mb-2" style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
                            <svg width="100" height="100" style="transform: rotate(-90deg);">
                                <circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="6"></circle>
                                <circle cx="50" cy="50" r="40" fill="none" stroke="white" stroke-width="6" 
                                        stroke-dasharray="251" stroke-dashoffset="63" stroke-linecap="round"></circle>
                            </svg>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.2rem; font-weight: bold;">75%</div>
                        </div>
                        <small class="opacity-75">Progression globale</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <!-- Progression des TP -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-laptop-code me-2" style="color: #28a745;"></i>
                    Travaux Pratiques (TP)
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="text-center p-3" style="background-color: #e8f5e8; border-radius: 10px;">
                            <h2 class="mb-1" style="color: #28a745;">12 / 15</h2>
                            <p class="mb-0 text-muted">TP réalisés</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center p-3" style="background-color: #fff3e0; border-radius: 10px;">
                            <h2 class="mb-1" style="color: #FF9900;">3</h2>
                            <p class="mb-0 text-muted">TP restants</p>
                        </div>
                    </div>
                </div>
                
                <div class="progress mb-3" style="height: 15px;">
                    <div class="progress-bar bg-success" style="width: 80%;" role="progressbar">
                        <span class="fw-bold">80% complétés</span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-success mb-2">
                            <i class="fas fa-check-circle me-1"></i>
                            TP validés (12)
                        </h6>
                        <ul class="list-unstyled small">
                            <li class="mb-1"><i class="fas fa-check text-success me-2"></i>TP Photoshop - Retouche photo (4/4)</li>
                            <li class="mb-1"><i class="fas fa-check text-success me-2"></i>TP Illustrator - Création logo (4/4)</li>
                            <li class="mb-1"><i class="fas fa-check text-success me-2"></i>TP InDesign - Mise en page (4/4)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-warning mb-2">
                            <i class="fas fa-clock me-1"></i>
                            TP en attente (3)
                        </h6>
                        <ul class="list-unstyled small">
                            <li class="mb-1"><i class="fas fa-hourglass-half text-warning me-2"></i>TP Strategy Business - Analyse marché (1/1)</li>
                            <li class="mb-1"><i class="fas fa-hourglass-half text-warning me-2"></i>TP Portfolio - Présentation (1/1)</li>
                            <li class="mb-1"><i class="fas fa-hourglass-half text-warning me-2"></i>TP Projet final - Intégration (1/1)</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression des Projets -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-project-diagram me-2" style="color: #3399ff;"></i>
                    Projets de Formation
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="text-center p-3" style="background-color: #e3f2fd; border-radius: 10px;">
                            <h2 class="mb-1" style="color: #3399ff;">3 / 4</h2>
                            <p class="mb-0 text-muted">Projets réalisés</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center p-3" style="background-color: #fff3e0; border-radius: 10px;">
                            <h2 class="mb-1" style="color: #FF9900;">1</h2>
                            <p class="mb-0 text-muted">Projet final</p>
                        </div>
                    </div>
                </div>
                
                <div class="progress mb-3" style="height: 15px;">
                    <div class="progress-bar" style="width: 75%; background-color: #3399ff;" role="progressbar">
                        <span class="fw-bold">75% complétés</span>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Projet</th>
                                <th>Module</th>
                                <th>Note</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Identité visuelle complète</strong></td>
                                <td><span class="badge" style="background-color: #ff6633;">Photoshop + Illustrator</span></td>
                                <td><span class="text-success fw-bold">16/20</span></td>
                                <td><span class="badge bg-success">Validé</span></td>
                            </tr>
                            <tr>
                                <td><strong>Magazine 20 pages</strong></td>
                                <td><span class="badge" style="background-color: #9c27b0;">InDesign</span></td>
                                <td><span class="text-success fw-bold">18/20</span></td>
                                <td><span class="badge bg-success">Validé</span></td>
                            </tr>
                            <tr>
                                <td><strong>Campagne publicitaire</strong></td>
                                <td><span class="badge" style="background-color: #FF9900;">Strategy Business</span></td>
                                <td><span class="text-success fw-bold">15/20</span></td>
                                <td><span class="badge bg-success">Validé</span></td>
                            </tr>
                            <tr>
                                <td><strong>Portfolio professionnel</strong></td>
                                <td><span class="badge" style="background-color: #003366;">Projet final</span></td>
                                <td><span class="text-muted">-</span></td>
                                <td><span class="badge bg-warning">En cours</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rapport de fin de formation -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-alt me-2" style="color: #ff6633;"></i>
                    Rapport de fin de formation
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-1">Rapport en cours de rédaction</h6>
                            <p class="mb-0 small">Le rapport de fin de formation doit être rédigé et soumis avant la certification</p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <h6 class="mb-3">Contenu requis du rapport :</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-square-check text-muted me-2"></i>
                                Bilan de compétences acquises
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-square-check text-muted me-2"></i>
                                Analyse des projets réalisés
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-square-check text-muted me-2"></i>
                                Perspectives professionnelles
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-square-check text-muted me-2"></i>
                                Retour d'expérience sur la formation
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="mb-3">
                            <i class="fas fa-edit" style="font-size: 3rem; color: #ff6633;"></i>
                        </div>
                        <button class="btn btn-outline-primary btn-sm mb-2" onclick="startReport()">
                            <i class="fas fa-pen me-1"></i>
                            Commencer la rédaction
                        </button>
                        <br>
                        <small class="text-muted">Format : 5-10 pages minimum</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar - Critères d'éligibilité -->
    <div class="col-md-4">
        <!-- Critères d'éligibilité à la certification -->
        <div class="card mb-4">
            <div class="card-header" style="background: linear-gradient(135deg, #003366 0%, #3399ff 100%); color: white;">
                <h6 class="mb-0">
                    <i class="fas fa-certificate me-2"></i>
                    Critères d'éligibilité à la certification
                </h6>
            </div>
            <div class="card-body">
                <div class="eligibility-criteria">
                    <!-- Paiement -->
                    <div class="criteria-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-credit-card text-warning me-2"></i>
                                <strong>Paiement intégral</strong>
                            </div>
                            <span class="badge bg-warning">En attente</span>
                        </div>
                        <small class="text-muted">300€ restants à régler</small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-warning" style="width: 75%;" role="progressbar"></div>
                        </div>
                    </div>

                    <!-- TP obligatoires -->
                    <div class="criteria-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-laptop-code text-warning me-2"></i>
                                <strong>15 TP obligatoires</strong>
                            </div>
                            <span class="badge bg-warning">12/15</span>
                        </div>
                        <small class="text-muted">3 TP restants à valider</small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-success" style="width: 80%;" role="progressbar"></div>
                        </div>
                    </div>

                    <!-- Projets obligatoires -->
                    <div class="criteria-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-project-diagram text-warning me-2"></i>
                                <strong>4 projets obligatoires</strong>
                            </div>
                            <span class="badge bg-warning">3/4</span>
                        </div>
                        <small class="text-muted">Portfolio professionnel en cours</small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar" style="width: 75%; background-color: #3399ff;" role="progressbar"></div>
                        </div>
                    </div>

                    <!-- Rapport de fin de formation -->
                    <div class="criteria-item mb-3 p-3" style="border: 1px solid #e9ecef; border-radius: 8px;">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt text-danger me-2"></i>
                                <strong>Rapport de fin de formation</strong>
                            </div>
                            <span class="badge bg-danger">Non rédigé</span>
                        </div>
                        <small class="text-muted">À rédiger et soumettre</small>
                        <div class="progress mt-2" style="height: 4px;">
                            <div class="progress-bar bg-danger" style="width: 0%;" role="progressbar"></div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Statut global d'éligibilité -->
                <div class="text-center p-3" style="background-color: #fff3e0; border-radius: 8px; border: 2px solid #FF9900;">
                    <div class="mb-2">
                        <i class="fas fa-hourglass-half" style="color: #FF9900; font-size: 2rem;"></i>
                    </div>
                    <h6 class="fw-bold" style="color: #e65100;">NON ÉLIGIBLE</h6>
                    <small class="text-muted">Finalisez tous les critères pour obtenir votre certification</small>
                </div>
            </div>
        </div>

        <!-- Résumé des exigences -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-list-check me-2"></i>
                    Résumé des exigences
                </h6>
            </div>
            <div class="card-body">
                <div class="requirement-summary">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">TP minimum requis :</span>
                        <strong style="color: #28a745;">15 TP</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Projets minimum requis :</span>
                        <strong style="color: #3399ff;">4 projets</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Note minimum :</span>
                        <strong style="color: #FF9900;">12/20</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Assiduité minimum :</span>
                        <strong style="color: #6f42c1;">80%</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Formation soldée :</span>
                        <strong style="color: #ff6633;">Obligatoire</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Actions rapides
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-success btn-sm" onclick="viewTP()">
                        <i class="fas fa-laptop-code me-1"></i>
                        Voir les TP restants
                    </button>
                    <button class="btn btn-outline-primary btn-sm" onclick="viewProjects()">
                        <i class="fas fa-project-diagram me-1"></i>
                        Finaliser le portfolio
                    </button>
                    <button class="btn btn-outline-warning btn-sm" onclick="startReport()">
                        <i class="fas fa-file-alt me-1"></i>
                        Rédiger le rapport
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="payRemaining()">
                        <i class="fas fa-credit-card me-1"></i>
                        Finaliser le paiement
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
:root {
    --primary-color: #003366;
    --secondary-color: #3399ff;
    --accent-color: #ff6633;
    --warning-color: #FF9900;
    --success-color: #28a745;
}

.criteria-item {
    transition: all 0.3s ease;
}

.criteria-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-color) !important;
}

.requirement-summary {
    font-size: 0.9rem;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn:hover {
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    border-radius: 10px;
}

@media (max-width: 768px) {
    .criteria-item {
        margin-bottom: 1rem;
    }
    
    .requirement-summary {
        font-size: 0.8rem;
    }
}
</style>

<script>
// Fonction pour voir les TP restants
function viewTP() {
    showNotification('Redirection vers la page des TP...', 'info');
    // Ici on redirigerait vers la page des TP
    setTimeout(() => {
        window.location.href = '/tp';
    }, 1000);
}

// Fonction pour voir les projets
function viewProjects() {
    showNotification('Redirection vers la page des projets...', 'info');
    // Ici on redirigerait vers la page des projets
    setTimeout(() => {
        window.location.href = '/projets';
    }, 1000);
}

// Fonction pour commencer la rédaction du rapport
function startReport() {
    showNotification('Ouverture de l\'éditeur de rapport...', 'info');
    // Ici on ouvrirait un éditeur ou une modal pour le rapport
}

// Fonction pour finaliser le paiement
function payRemaining() {
    showNotification('Redirection vers la page de paiement...', 'info');
    // Ici on redirigerait vers la page de paiement
    setTimeout(() => {
        window.location.href = '/paiement';
    }, 1000);
}

// Fonction pour afficher les notifications
function showNotification(message, type) {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 350px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${getIconForType(type)} me-2"></i>
            ${message}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Supprimer automatiquement après 3 secondes
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 3000);
}

// Fonction pour obtenir l'icône selon le type de notification
function getIconForType(type) {
    switch(type) {
        case 'success': return 'check-circle';
        case 'info': return 'info-circle';
        case 'warning': return 'exclamation-triangle';
        case 'danger': return 'times-circle';
        default: return 'info-circle';
    }
}

// Animation des cartes au chargement
document.addEventListener('DOMContentLoaded', function() {
    // Animation des critères d'éligibilité
    const criteriaItems = document.querySelectorAll('.criteria-item');
    criteriaItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.5s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
        }, index * 200);
    });
    
    // Animation des boutons au survol
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.15)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>
@endsection
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h6 class="mb-3">Critères de certification</h6>
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="small">Compléter tous les modules (8/11)</span>
                            </div>
                            <span class="badge" style="background-color: var(--warning-color); color: white;">73%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="small">Moyenne générale ≥ 12/20 (15.2/20)</span>
                            </div>
                            <span class="badge" style="background-color: var(--success-color); color: white;">✓</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <span class="small">Projet final validé</span>
                            </div>
                            <span class="badge" style="background-color: var(--success-color); color: white;">✓</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-muted me-2"></i>
                                <span class="small">Paiements à jour</span>
                            </div>
                            <span class="badge" style="background-color: var(--success-color); color: white;">✓</span>
                        </div>
                    </div>
                </div>

                <div class="progress mb-3" style="height: 15px;">
                    <div class="progress-bar" style="width: 85%; background-color: var(--primary-color);" role="progressbar">85%</div>
                </div>
                <p class="text-center small text-muted">Progression globale vers la certification</p>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-tasks me-2"></i>
                    Modules restants
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">TypeScript Avancé</h6>
                                <p class="text-muted small mb-1">Concepts avancés et intégration avec React</p>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: 60%; background-color: var(--warning-color);"></div>
                                </div>
                            </div>
                            <span class="badge" style="background-color: var(--warning-color); color: white;">En cours</span>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Déploiement & DevOps</h6>
                                <p class="text-muted small mb-1">Docker, CI/CD, déploiement cloud</p>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: 0%; background-color: var(--secondary-color);"></div>
                                </div>
                            </div>
                            <span class="badge bg-secondary">À venir</span>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Soutenance Finale</h6>
                                <p class="text-muted small mb-1">Présentation du projet final</p>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" style="width: 0%; background-color: var(--accent-color);"></div>
                                </div>
                            </div>
                            <span class="badge bg-secondary">À planifier</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-check me-2"></i>
                    Planning de fin de formation
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Étape</th>
                                <th>Date prévue</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Fin TypeScript</td>
                                <td>15 Août 2024</td>
                                <td><span class="badge" style="background-color: var(--warning-color); color: white;">En cours</span></td>
                            </tr>
                            <tr>
                                <td>Module DevOps</td>
                                <td>30 Août 2024</td>
                                <td><span class="badge bg-secondary">Planifié</span></td>
                            </tr>
                            <tr>
                                <td>Soutenance</td>
                                <td>15 Sept 2024</td>
                                <td><span class="badge bg-secondary">À confirmer</span></td>
                            </tr>
                            <tr>
                                <td>Remise diplôme</td>
                                <td>30 Sept 2024</td>
                                <td><span class="badge" style="background-color: var(--accent-color); color: white;">Prévu</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-award me-2"></i>
                    Votre certification
                </h6>
            </div>
            <div class="card-body text-center">
                <i class="fas fa-certificate fa-3x mb-3" style="color: var(--warning-color);"></i>
                <h6>Développeur Web Full Stack</h6>
                <p class="small text-muted mb-3">Certification EVC 2024</p>
                <div class="alert alert-warning">
                    <small>Disponible après validation de tous les critères</small>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Vos résultats
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center mb-3">
                    <h4 style="color: var(--success-color);">15.2/20</h4>
                    <small class="text-muted">Moyenne générale</small>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Modules terminés</small>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" style="width: 73%; background-color: var(--primary-color);"></div>
                    </div>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Projets validés</small>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar" style="width: 100%; background-color: var(--success-color);"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-handshake me-2"></i>
                    Accompagnement
                </h6>
            </div>
            <div class="card-body">
                <p class="small mb-2">Besoin d'aide pour terminer ?</p>
                <button class="btn btn-sm btn-primary w-100 mb-2">Contacter un mentor</button>
                <button class="btn btn-sm btn-outline-primary w-100">Planning personnalisé</button>
            </div>
        </div>
    </div>
</div>
@endsection
