@extends('layouts.ki-admin')

@section('title', 'Communauté - EVC 2024')
@section('page-title', 'Communauté')

@section('content')
<!-- Statistiques de la communauté -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100" style="background: linear-gradient(135deg, #003366, #0066cc); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-users fa-2x mb-2"></i>
                <h4 class="mb-1">{{ number_format($communityStats['active_members']) }}</h4>
                <small>Membres actifs</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100" style="background: linear-gradient(135deg, #3399ff, #66b3ff); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-comments fa-2x mb-2"></i>
                <h4 class="mb-1">{{ number_format($communityStats['total_messages']) }}</h4>
                <small>Messages échangés</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100" style="background: linear-gradient(135deg, #ff6633, #ff9966); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-project-diagram fa-2x mb-2"></i>
                <h4 class="mb-1">{{ number_format($communityStats['shared_projects']) }}</h4>
                <small>Projets partagés</small>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card h-100" style="background: linear-gradient(135deg, #FF9900, #ffb84d); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-user-graduate fa-2x mb-2"></i>
                <h4 class="mb-1">{{ number_format($communityStats['graduates']) }}</h4>
                <small>Diplômés</small>
            </div>
        </div>
    </div>
</div>

<!-- Canaux de communication -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-share-alt me-2"></i>
                    Rejoins les différentes classes de Travail
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Canal Telegram -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card border h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fab fa-telegram-plane fa-3x" style="color: #0088cc;"></i>
                                </div>
                                <h6 class="mb-2">Canal Telegram</h6>
                                <p class="text-muted small mb-3">Actualités et annonces officielles</p>
                                <div class="mb-2">
                                    <span class="badge bg-success">892 abonnés</span>
                                </div>
                                <a href="https://t.me/evc2024_canal" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fab fa-telegram-plane me-1"></i>
                                    Rejoindre
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Groupe Telegram -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card border h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fab fa-telegram-plane fa-3x" style="color: #0088cc;"></i>
                                </div>
                                <h6 class="mb-2">Groupe Telegram</h6>
                                <p class="text-muted small mb-3">Discussions et entraide entre étudiants</p>
                                <div class="mb-2">
                                    <span class="badge bg-info">456 membres</span>
                                </div>
                                <a href="https://t.me/evc2024_groupe" target="_blank" class="btn btn-outline-primary btn-sm">
                                    <i class="fab fa-telegram-plane me-1"></i>
                                    Rejoindre
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Canal WhatsApp -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card border h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fab fa-whatsapp fa-3x" style="color: #25D366;"></i>
                                </div>
                                <h6 class="mb-2">Canal WhatsApp</h6>
                                <p class="text-muted small mb-3">Notifications importantes</p>
                                <div class="mb-2">
                                    <span class="badge bg-success">1,123 abonnés</span>
                                </div>
                                <a href="https://whatsapp.com/channel/evc2024" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fab fa-whatsapp me-1"></i>
                                    S'abonner
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Groupe WhatsApp -->
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="card border h-100">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <i class="fab fa-whatsapp fa-3x" style="color: #25D366;"></i>
                                </div>
                                <h6 class="mb-2">Groupe WhatsApp</h6>
                                <p class="text-muted small mb-3">Chat communautaire</p>
                                <div class="mb-2">
                                    <span class="badge bg-warning">256 membres</span>
                                </div>
                                <a href="https://chat.whatsapp.com/evc2024groupe" target="_blank" class="btn btn-outline-success btn-sm">
                                    <i class="fab fa-whatsapp me-1"></i>
                                    Rejoindre
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Réseaux sociaux EVC -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-thumbs-up me-2"></i>
                    Suivez-nous sur les réseaux sociaux
                </h5>
                <p class="text-white mb-0 mt-2">Restez connectés avec EVC et découvrez nos dernières actualités, conseils et success stories</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Facebook -->
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <div class="card border h-100 text-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fab fa-facebook fa-3x" style="color: #1877F2;"></i>
                                </div>
                                <h6 class="mb-2">Facebook</h6>
                                <p class="text-muted small mb-3">Actualités & Communauté</p>
                                <div class="mb-2">
                                    <span class="badge bg-primary">{{ $socialMediaStats['facebook']['formatted'] }} followers
                                        @if($socialMediaStats['facebook']['trend'] === 'up')
                                            <i class="fas fa-arrow-up ms-1 text-success"></i>
                                        @elseif($socialMediaStats['facebook']['trend'] === 'down')
                                            <i class="fas fa-arrow-down ms-1 text-danger"></i>
                                        @endif
                                    </span>
                                </div>
                                <a href="https://www.facebook.com/bilebossombraofficiel" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fab fa-facebook me-1"></i>
                                    Suivre
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Instagram -->
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <div class="card border h-100 text-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fab fa-instagram fa-3x" style="color: #E4405F;"></i>
                                </div>
                                <h6 class="mb-2">Instagram</h6>
                                <p class="text-muted small mb-3">Inspirations visuelles</p>
                                <div class="mb-2">
                                    <span class="badge bg-danger">{{ $socialMediaStats['instagram']['formatted'] }} followers
                                        @if($socialMediaStats['instagram']['trend'] === 'up')
                                            <i class="fas fa-arrow-up ms-1 text-success"></i>
                                        @elseif($socialMediaStats['instagram']['trend'] === 'down')
                                            <i class="fas fa-arrow-down ms-1 text-danger"></i>
                                        @endif
                                    </span>
                                </div>
                                <a href="https://instagram.com/evc2024" target="_blank" class="btn btn-outline-danger btn-sm">
                                    <i class="fab fa-instagram me-1"></i>
                                    Suivre
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- TikTok -->
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <div class="card border h-100 text-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fab fa-tiktok fa-3x" style="color: #000000;"></i>
                                </div>
                                <h6 class="mb-2">TikTok</h6>
                                <p class="text-muted small mb-3">Tutoriels courts</p>
                                <div class="mb-2">
                                    <span class="badge bg-dark">{{ $socialMediaStats['tiktok']['formatted'] }} followers
                                        @if($socialMediaStats['tiktok']['trend'] === 'up')
                                            <i class="fas fa-arrow-up ms-1 text-success"></i>
                                        @elseif($socialMediaStats['tiktok']['trend'] === 'down')
                                            <i class="fas fa-arrow-down ms-1 text-danger"></i>
                                        @endif
                                    </span>
                                </div>
                                <a href="https://www.tiktok.com/@ecolevirtuelledescreatif" target="_blank" class="btn btn-dark btn-sm">
                                    <i class="fab fa-tiktok me-1"></i>
                                    Suivre
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- YouTube -->
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <div class="card border h-100 text-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fab fa-youtube fa-3x" style="color: #FF0000;"></i>
                                </div>
                                <h6 class="mb-2">YouTube</h6>
                                <p class="text-muted small mb-3">Cours & Webinaires</p>
                                <div class="mb-2">
                                    <span class="badge bg-danger">{{ $socialMediaStats['youtube']['formatted'] }} abonnés
                                        @if($socialMediaStats['youtube']['trend'] === 'up')
                                            <i class="fas fa-arrow-up ms-1 text-success"></i>
                                        @elseif($socialMediaStats['youtube']['trend'] === 'down')
                                            <i class="fas fa-arrow-down ms-1 text-danger"></i>
                                        @endif
                                    </span>
                                </div>
                                <a href="https://www.youtube.com/@ecolevirtuelledescreatifs459" target="_blank" class="btn btn-danger btn-sm">
                                    <i class="fab fa-youtube me-1"></i>
                                    S'abonner
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- LinkedIn -->
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <div class="card border h-100 text-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fab fa-linkedin fa-3x" style="color: #0A66C2;"></i>
                                </div>
                                <h6 class="mb-2">LinkedIn</h6>
                                <p class="text-muted small mb-3">Réseau professionnel</p>
                                <div class="mb-2">
                                    <span class="badge bg-primary">{{ $socialMediaStats['linkedin']['formatted'] }} followers
                                        @if($socialMediaStats['linkedin']['trend'] === 'up')
                                            <i class="fas fa-arrow-up ms-1 text-success"></i>
                                        @elseif($socialMediaStats['linkedin']['trend'] === 'down')
                                            <i class="fas fa-arrow-down ms-1 text-danger"></i>
                                        @endif
                                    </span>
                                </div>
                                <a href="https://www.linkedin.com/company/82489374/" target="_blank" class="btn btn-primary btn-sm">
                                    <i class="fab fa-linkedin me-1"></i>
                                    Suivre
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Site Web -->
                    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
                        <div class="card border h-100 text-center">
                            <div class="card-body">
                                <div class="mb-3">
                                    <i class="fas fa-globe fa-3x" style="color: #FF6B35;"></i>
                                </div>
                                <h6 class="mb-2">Site Web</h6>
                                <p class="text-muted small mb-3">Portail officiel</p>
                                <div class="mb-2">
                                    <span class="badge bg-warning">En ligne</span>
                                </div>
                                <a href="https://www.ecolevirtuelledescreatifs.com/" target="_blank" class="btn btn-warning btn-sm">
                                    <i class="fas fa-external-link-alt me-1"></i>
                                    Visiter
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Call to action -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h6 class="mb-1">Restez connectés avec EVC !</h6>
                                <p class="mb-0">Suivez-nous sur vos réseaux préférés pour ne rien manquer de nos actualités, conseils en design graphique, success stories de nos étudiants et opportunités professionnelles.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
