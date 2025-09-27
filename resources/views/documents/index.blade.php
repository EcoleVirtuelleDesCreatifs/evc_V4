@extends('layouts.ki-admin')

@section('title', 'Bibliothèque - EVC 2024')
@section('page-title', 'Bibliothèque')

@section('content')
<div class="container-fluid">
    <!-- Statistiques en haut -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-palette fa-2x mb-2" style="color: var(--primary-color);"></i>
                    <h3 style="color: var(--primary-color);">32</h3>
                    <small class="text-muted mb-3">Ebooks Infographie</small>
                    <div class="mt-auto">
                        <button class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>
                            Voir Infographie
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-users fa-2x mb-2" style="color: var(--secondary-color);"></i>
                    <h3 style="color: var(--secondary-color);">18</h3>
                    <small class="text-muted mb-3">Community Manager</small>
                    <div class="mt-auto">
                        <button class="btn btn-secondary btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>
                            Voir CM
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-chess fa-2x mb-2" style="color: var(--success-color);"></i>
                    <h3 style="color: var(--success-color);">25</h3>
                    <small class="text-muted mb-3">Ebooks Stratégie</small>
                    <div class="mt-auto">
                        <button class="btn btn-success btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>
                            Voir Stratégie
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-share-alt fa-2x mb-2" style="color: var(--accent-color);"></i>
                    <h3 style="color: var(--accent-color);">22</h3>
                    <small class="text-muted mb-3">Réseaux Sociaux</small>
                    <div class="mt-auto">
                        <button class="btn btn-warning btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>
                            Voir Réseaux
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-rocket fa-2x mb-2" style="color: var(--info-color);"></i>
                    <h3 style="color: var(--info-color);">15</h3>
                    <small class="text-muted mb-3">Ebooks Motivations</small>
                    <div class="mt-auto">
                        <button class="btn btn-info btn-sm w-100">
                            <i class="fas fa-eye me-1"></i>
                            Voir Motivations
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <i class="fas fa-book fa-2x mb-2" style="color: var(--dark-color);"></i>
                    <h3 style="color: var(--dark-color);">112</h3>
                    <small class="text-muted mb-3">Total Ebooks</small>
                    <div class="mt-auto">
                        <button class="btn btn-dark btn-sm w-100">
                            <i class="fas fa-list me-1"></i>
                            Voir Tout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Ebooks Infographie -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-palette me-2" style="color: var(--primary-color);"></i>
                        Ebooks Infographie
                    </h5>
                    <span class="badge" style="background-color: var(--primary-color); color: white;">32 ebooks</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative">
                                    <img src="https://via.placeholder.com/200x280/003366/ffffff?text=Photoshop+CC+2024" class="card-img-top" alt="Ebook Cover">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-success">Nouveau</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Design Graphique Moderne</h6>
                                    <p class="card-text text-muted small flex-grow-1">Maîtrisez les principes du design contemporain</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Design Studio</small><br>
                                        <small class="text-muted">Pages: 380 • PDF: 18.5 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="https://via.placeholder.com/200x280/ff6633/ffffff?text=Retouche+Portrait" class="card-img-top" alt="Ebook Cover">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Identité Visuelle & Branding</h6>
                                    <p class="card-text text-muted small flex-grow-1">Créez des identités visuelles marquantes</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Brand Masters</small><br>
                                        <small class="text-muted">Pages: 295 • PDF: 14.2 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="https://via.placeholder.com/200x280/3399ff/ffffff?text=Effets+Speciaux" class="card-img-top" alt="Ebook Cover">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Typographie Créative</h6>
                                    <p class="card-text text-muted small flex-grow-1">L'art de la typographie moderne</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Type Experts</small><br>
                                        <small class="text-muted">Pages: 340 • PDF: 16.8 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="https://via.placeholder.com/200x280/FF9900/ffffff?text=Photomontage" class="card-img-top" alt="Ebook Cover">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Mise en Page Professionnelle</h6>
                                    <p class="card-text text-muted small flex-grow-1">Techniques de mise en page avancées</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Layout Pro</small><br>
                                        <small class="text-muted">Pages: 420 • PDF: 19.7 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-primary btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-plus me-1"></i> Voir tous les ebooks Infographie (32)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Ebooks Community Manager -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2" style="color: var(--secondary-color);"></i>
                        Ebooks Community Manager
                    </h5>
                    <span class="badge" style="background-color: var(--secondary-color); color: white;">18 ebooks</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative">
                                    <img src="https://via.placeholder.com/200x280/3399ff/ffffff?text=Logo+Design" class="card-img-top" alt="Ebook Cover">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-warning">Populaire</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Community Management 2024</h6>
                                    <p class="card-text text-muted small flex-grow-1">Animez et fédérez votre communauté</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Social Media Pro</small><br>
                                        <small class="text-muted">Pages: 320 • PDF: 12.4 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-secondary btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="https://via.placeholder.com/200x280/ff6633/ffffff?text=Illustration+Vectorielle" class="card-img-top" alt="Ebook Cover">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Engagement & Interaction</h6>
                                    <p class="card-text text-muted small flex-grow-1">Maximisez l'engagement de votre audience</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Engagement Expert</small><br>
                                        <small class="text-muted">Pages: 285 • PDF: 11.8 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-secondary btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-plus me-1"></i> Voir tous les ebooks Community Manager (18)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Ebooks Stratégie -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-chess me-2" style="color: var(--success-color);"></i>
                        Ebooks Stratégie
                    </h5>
                    <span class="badge" style="background-color: var(--success-color); color: white;">25 ebooks</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="https://via.placeholder.com/200x280/28a745/ffffff?text=Magazine+Layout" class="card-img-top" alt="Ebook Cover">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Stratégie Digitale 2024</h6>
                                    <p class="card-text text-muted small flex-grow-1">Développez votre stratégie numérique</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Digital Strategy</small><br>
                                        <small class="text-muted">Pages: 350 • PDF: 16.8 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="https://via.placeholder.com/200x280/6c757d/ffffff?text=Livre+Numerique" class="card-img-top" alt="Ebook Cover">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Plan Marketing Efficace</h6>
                                    <p class="card-text text-muted small flex-grow-1">Construisez des plans marketing gagnants</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Marketing Pro</small><br>
                                        <small class="text-muted">Pages: 290 • PDF: 13.5 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-success btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-success">
                            <i class="fas fa-plus me-1"></i> Voir tous les ebooks Stratégie (25)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Ebooks Réseaux Sociaux -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-share-alt me-2" style="color: var(--accent-color);"></i>
                        Ebooks Réseaux Sociaux
                    </h5>
                    <span class="badge" style="background-color: var(--accent-color); color: white;">22 ebooks</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative">
                                    <img src="https://via.placeholder.com/200x280/FF9900/ffffff?text=Instagram+Marketing" class="card-img-top" alt="Ebook Cover">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-danger">Tendance</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Instagram Marketing 2024</h6>
                                    <p class="card-text text-muted small flex-grow-1">Maîtrisez Instagram pour votre business</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Social Media Expert</small><br>
                                        <small class="text-muted">Pages: 280 • PDF: 14.2 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="https://via.placeholder.com/200x280/dc3545/ffffff?text=TikTok+Strategy" class="card-img-top" alt="Ebook Cover">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">TikTok pour Entreprises</h6>
                                    <p class="card-text text-muted small flex-grow-1">Stratégies TikTok pour professionnels</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: TikTok Pro</small><br>
                                        <small class="text-muted">Pages: 220 • PDF: 12.8 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-warning btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-warning">
                            <i class="fas fa-plus me-1"></i> Voir tous les ebooks Réseaux Sociaux (22)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Ebooks Motivations -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-rocket me-2" style="color: var(--info-color);"></i>
                        Ebooks Motivations
                    </h5>
                    <span class="badge" style="background-color: var(--info-color); color: white;">15 ebooks</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="position-relative">
                                    <img src="https://via.placeholder.com/200x280/17a2b8/ffffff?text=Mindset+Entrepreneur" class="card-img-top" alt="Ebook Cover">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-success">Inspirant</span>
                                    </div>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Mindset Entrepreneur</h6>
                                    <p class="card-text text-muted small flex-grow-1">Développez un mental de gagnant</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Success Coach</small><br>
                                        <small class="text-muted">Pages: 250 • PDF: 10.5 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-info btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="https://via.placeholder.com/200x280/6f42c1/ffffff?text=Productivite+Max" class="card-img-top" alt="Ebook Cover">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">Productivité Maximale</h6>
                                    <p class="card-text text-muted small flex-grow-1">Techniques pour optimiser votre temps</p>
                                    <div class="mb-2">
                                        <small class="text-muted">Auteur: Productivity Guru</small><br>
                                        <small class="text-muted">Pages: 180 • PDF: 8.3 MB</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-info btn-sm flex-fill">
                                            <i class="fas fa-book-open me-1"></i> Lire
                                        </button>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button class="btn btn-outline-info">
                            <i class="fas fa-plus me-1"></i> Voir tous les ebooks Motivations (15)
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
