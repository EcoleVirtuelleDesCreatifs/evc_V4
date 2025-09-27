@extends('layouts.ki-admin')

@section('title', 'Actualités - EVC 2024')
@section('page-title', 'Actualités')

@section('content')
<div class="container-fluid">
    <!-- Statistiques en haut -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-newspaper fa-2x mb-2" style="color: var(--primary-color);"></i>
                    <h3 style="color: var(--primary-color);">8</h3>
                    <small class="text-muted mb-3">News École</small>
                    <div class="mt-auto">
                        <button class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>
                            Voir News
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-bullhorn fa-2x mb-2" style="color: var(--secondary-color);"></i>
                    <h3 style="color: var(--secondary-color);">3</h3>
                    <small class="text-muted mb-3">Annonces</small>
                    <div class="mt-auto">
                        <button class="btn btn-secondary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>
                            Voir Annonces
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-trophy fa-2x mb-2" style="color: var(--success-color);"></i>
                    <h3 style="color: var(--success-color);">5</h3>
                    <small class="text-muted mb-3">Réussites</small>
                    <div class="mt-auto">
                        <button class="btn btn-success btn-sm w-100">
                            <i class="fas fa-medal me-1"></i>
                            Voir Réussites
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-rss fa-2x mb-2" style="color: var(--accent-color);"></i>
                    <h3 style="color: var(--accent-color);">16</h3>
                    <small class="text-muted mb-3">Total Articles</small>
                    <div class="mt-auto">
                        <button class="btn btn-secondary btn-sm w-100">
                            <i class="fas fa-history me-1"></i>
                            Archives
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section News de l'école -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-newspaper me-2" style="color: var(--primary-color);"></i>
                        News de l'École
                    </h5>
                    <span class="badge" style="background-color: var(--primary-color); color: white;">8 articles</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Article</th>
                                    <th>Catégorie</th>
                                    <th>Date</th>
                                    <th>Auteur</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Nouvelle formation Photoshop CC 2024</strong>
                                            <br><small class="text-muted">Découvrez les nouvelles fonctionnalités et outils</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Formation</span></td>
                                    <td><small>28 Juillet 2024</small></td>
                                    <td><small>Direction Pédagogique</small></td>
                                    <td><span class="badge" style="background-color: var(--success-color); color: white;">Publié</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-eye"></i> Lire
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-share"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Concours Design Graphique 2024</strong>
                                            <br><small class="text-muted">Participez au concours annuel d'infographie</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Concours</span></td>
                                    <td><small>26 Juillet 2024</small></td>
                                    <td><small>Équipe EVC</small></td>
                                    <td><span class="badge" style="background-color: var(--success-color); color: white;">Publié</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-eye"></i> Lire
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-share"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Maintenance serveurs - 30 Juillet</strong>
                                            <br><small class="text-muted">Interruption de service prévue de 2h à 4h</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-danger">Technique</span></td>
                                    <td><small>25 Juillet 2024</small></td>
                                    <td><small>Service IT</small></td>
                                    <td><span class="badge" style="background-color: var(--success-color); color: white;">Publié</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-eye"></i> Lire
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-share"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div>
                                            <strong>Nouveaux outils Adobe Creative Suite</strong>
                                            <br><small class="text-muted">Mise à jour des logiciels de création</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Outils</span></td>
                                    <td><small>22 Juillet 2024</small></td>
                                    <td><small>Prof. Martin</small></td>
                                    <td><span class="badge" style="background-color: var(--success-color); color: white;">Publié</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-eye"></i> Lire
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-share"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Annonces Importantes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-bullhorn me-2" style="color: var(--secondary-color);"></i>
                        Annonces Importantes
                    </h5>
                    <span class="badge" style="background-color: var(--secondary-color); color: white;">3 annonces</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Annonce</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Priorité</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-warning">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                            <div>
                                                <strong>Changement d'horaire - Formation InDesign</strong>
                                                <br><small class="text-muted">Le cours du 29 juillet est reporté à 16h</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Horaire</span></td>
                                    <td><small>28 Juillet 2024</small></td>
                                    <td><span class="badge bg-warning">Urgent</span></td>
                                    <td><span class="badge" style="background-color: var(--warning-color); color: white;">Actif</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning me-1">
                                            <i class="fas fa-bell"></i> Accusé
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-info"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-info">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle text-info me-2"></i>
                                            <div>
                                                <strong>Nouvelle fonctionnalité - Certificats</strong>
                                                <br><small class="text-muted">Téléchargement direct depuis votre profil</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Fonctionnalité</span></td>
                                    <td><small>27 Juillet 2024</small></td>
                                    <td><span class="badge bg-info">Info</span></td>
                                    <td><span class="badge" style="background-color: var(--primary-color); color: white;">Actif</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info me-1">
                                            <i class="fas fa-check"></i> Lu
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <div>
                                                <strong>Mise à jour plateforme réussie</strong>
                                                <br><small class="text-muted">Nouvelles fonctionnalités disponibles</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">Système</span></td>
                                    <td><small>25 Juillet 2024</small></td>
                                    <td><span class="badge bg-success">Normal</span></td>
                                    <td><span class="badge" style="background-color: var(--success-color); color: white;">Terminé</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-eye"></i> Détails
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Réussites Étudiantes -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2" style="color: var(--success-color);"></i>
                        Réussites Étudiantes
                    </h5>
                    <span class="badge" style="background-color: var(--success-color); color: white;">5 réussites</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Réussite</th>
                                    <th>Étudiant</th>
                                    <th>Formation</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-medal text-warning me-2"></i>
                                            <div>
                                                <strong>Premier Prix Concours Logo</strong>
                                                <br><small class="text-muted">Création d'identité visuelle exceptionnelle</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32" class="rounded-circle me-2" width="32" height="32">
                                            <small>Marie Dubois</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-warning">Illustrator</span></td>
                                    <td><small>26 Juillet 2024</small></td>
                                    <td><span class="badge bg-warning">Concours</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-eye"></i> Voir
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-share"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-graduation-cap text-success me-2"></i>
                                            <div>
                                                <strong>Certification Adobe Photoshop</strong>
                                                <br><small class="text-muted">Obtention de la certification officielle</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32" class="rounded-circle me-2" width="32" height="32">
                                            <small>Pierre Martin</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">Photoshop</span></td>
                                    <td><small>24 Juillet 2024</small></td>
                                    <td><span class="badge bg-success">Certification</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success me-1">
                                            <i class="fas fa-certificate"></i> Certificat
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-star text-primary me-2"></i>
                                            <div>
                                                <strong>Projet Final Excellent</strong>
                                                <br><small class="text-muted">Magazine professionnel avec InDesign</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://via.placeholder.com/32" class="rounded-circle me-2" width="32" height="32">
                                            <small>Sophie Laurent</small>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-success">InDesign</span></td>
                                    <td><small>22 Juillet 2024</small></td>
                                    <td><span class="badge bg-primary">Projet</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary me-1">
                                            <i class="fas fa-eye"></i> Portfolio
                                        </button>
                                        <button class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-thumbs-up"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
