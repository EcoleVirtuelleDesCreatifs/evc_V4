@extends('layouts.ki-admin')

@section('title', 'Programmes de Formation - EVC 2024')
@section('page-title', 'Programmes de Formation')

@section('content')
<!-- Header avec palette Instagram -->
<div class="row mb-4">
    <div class="col-12">
        <div class="instagram-header">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-circle">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <h3 class="mb-1" style="font-weight: 700; font-size: 1.8rem;">
                                Programmes de Formation
                            </h3>
                            <p class="mb-0 text-white-50">{{ $student->program ?? 'Votre formation' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="badge" style="background: rgba(255,255,255,0.2); padding: 0.75rem 1.5rem; font-size: 1rem; border-radius: 30px;">
                            <i class="fas fa-file-pdf me-2"></i>
                            {{ $programmes->count() }} Programme(s) disponible(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Liste dynamique des programmes -->
<div class="row">
    <div class="col-12">
        @if($programmes->isEmpty())
            <!-- Message si aucun programme -->
            <div class="empty-state">
                <div class="text-center py-5">
                    <div class="icon-circle-large mb-4">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="mb-3" style="color: #1f2937; font-weight: 600;">Aucun programme disponible</h3>
                    <p class="text-muted mb-0">Les programmes de formation seront publiés prochainement par votre formateur.</p>
                </div>
            </div>
        @else
            <!-- Grille des programmes -->
            <div class="row g-4">
                @foreach($programmes as $programme)
                    <div class="col-md-6 col-lg-4">
                        <div class="programme-card instagram-card">
                            <!-- Icône PDF -->
                            <div class="pdf-icon-container mb-3">
                                <div class="pdf-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                            </div>
                            
                            <!-- Titre -->
                            <h4 class="programme-title mb-2">
                                {{ $programme->titre }}
                            </h4>
                            
                            <!-- Description -->
                            @if($programme->description)
                                <p class="programme-description mb-3">
                                    {{ Str::limit($programme->description, 120) }}
                                </p>
                            @endif
                            
                            <!-- Informations -->
                            <div class="programme-info mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-graduation-cap" style="color: #C13584;"></i>
                                    <span class="small text-muted">{{ $programme->formation }}</span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar" style="color: #C13584;"></i>
                                    <span class="small text-muted">Publié le {{ \Carbon\Carbon::parse($programme->created_at)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                            
                            <!-- Bouton téléchargement -->
                            <a href="{{ asset('storage/' . $programme->fichier_pdf) }}" 
                               target="_blank" 
                               class="instagram-btn w-100"
                               download>
                                <i class="fas fa-download me-2"></i>
                                Télécharger le PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
/* Palette Instagram */
:root {
    --instagram-purple: #833AB4;
    --instagram-pink: #C13584;
    --instagram-red: #E1306C;
    --instagram-orange: #F56040;
    --instagram-yellow: #FCAF45;
}

/* Header avec dégradé Instagram */
.instagram-header {
    background: linear-gradient(135deg, #833AB4, #C13584, #E1306C);
    border-radius: 20px;
    color: white;
    box-shadow: 0 8px 32px rgba(131, 58, 180, 0.3);
    animation: fadeInDown 0.6s ease;
    margin-bottom: 2rem;
}

/* Icône circulaire avec effet glassmorphism */
.icon-circle {
                        <thead class="table-light">
                            <tr>
                                <th>Jour</th>
                                <th>Horaire</th>
                                <th>Module</th>
                                <th>Sujet</th>
                                <th>Type</th>
                                <th>Formateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Lundi</strong><br>
                                    <small class="text-muted">26 Fév</small>
                                </td>
                                <td>09:00 - 12:00</td>
                                <td>
                                    <span class="badge" style="background-color: #FF0000; color: white;">
                                        <i class="fab fa-adobe me-1"></i>Photoshop
                                    </span>
                                </td>
                                <td>Retouche avancée et effets spéciaux</td>
                                <td><span class="badge bg-primary">Cours</span></td>
                                <td>M. Dubois</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Mardi</strong><br>
                                    <small class="text-muted">27 Fév</small>
                                </td>
                                <td>14:00 - 17:00</td>
                                <td>
                                    <span class="badge" style="background-color: #FF7C00; color: white;">
                                        <i class="fab fa-adobe me-1"></i>Illustrator
                                    </span>
                                </td>
                                <td>Création de logos et identité visuelle</td>
                                <td><span class="badge bg-warning">TP</span></td>
                                <td>Mme Martin</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Mercredi</strong><br>
                                    <small class="text-muted">28 Fév</small>
                                </td>
                                <td>10:00 - 13:00</td>
                                <td>
                                    <span class="badge" style="background-color: #FF3366; color: white;">
                                        <i class="fab fa-adobe me-1"></i>InDesign
                                    </span>
                                </td>
                                <td>Mise en page magazine et brochures</td>
                                <td><span class="badge bg-primary">Cours</span></td>
                                <td>M. Leroy</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Jeudi</strong><br>
                                    <small class="text-muted">29 Fév</small>
                                </td>
                                <td>09:00 - 12:00</td>
                                <td>
                                    <span class="badge" style="background-color: #003366; color: white;">
                                        <i class="fas fa-chart-line me-1"></i>Strategy
                                    </span>
                                </td>
                                <td>Analyse de marché et positionnement</td>
                                <td><span class="badge bg-info">Séminaire</span></td>
                                <td>Mme Rousseau</td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>Vendredi</strong><br>
                                    <small class="text-muted">1 Mars</small>
                                </td>
                                <td>14:00 - 18:00</td>
                                <td>
                                    <span class="badge" style="background-color: #28a745; color: white;">
                                        <i class="fas fa-project-diagram me-1"></i>Projet
                                    </span>
                                </td>
                                <td>Projet intégré : Campagne publicitaire</td>
                                <td><span class="badge bg-success">Projet</span></td>
                                <td>Équipe EVC</td>
                            </tr>
                            <tr class="table-info">
                                <td>
                                    <strong>Samedi</strong><br>
                                    <small class="text-muted">2 Mars</small>
                                </td>
                                <td>10:00 - 16:00</td>
                                <td>
                                    <span class="badge" style="background-color: #6f42c1; color: white;">
                                        <i class="fas fa-star me-1"></i>Master Class
                                    </span>
                                </td>
                                <td>Tendances design 2024 et portfolio</td>
                                <td><span class="badge bg-secondary">Workshop</span></td>
                                <td>Expert invité</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Progression globale -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Progression globale
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="progress-circle mb-3" style="position: relative; width: 120px; height: 120px; margin: 0 auto;">
                    <svg width="120" height="120" style="transform: rotate(-90deg);">
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#e9ecef" stroke-width="8"></circle>
                        <circle cx="60" cy="60" r="50" fill="none" stroke="#003366" stroke-width="8" 
                                stroke-dasharray="314" stroke-dashoffset="110" stroke-linecap="round"></circle>
                    </svg>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.5rem; font-weight: bold; color: #003366;">65%</div>
                </div>
                <p class="text-muted small mb-2">Formation complétée</p>
                <div class="row text-center">
                    <div class="col-6">
                        <h5 style="color: #28a745;">8</h5>
                        <small class="text-muted">Modules terminés</small>
                    </div>
                    <div class="col-6">
                        <h5 style="color: #003366;">4</h5>
                        <small class="text-muted">En cours</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Prochaines échéances -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Prochaines échéances
                </h6>
            </div>
            <div class="card-body">
                <div class="deadline-item mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Projet Photoshop</h6>
                            <small class="text-muted">Affiche publicitaire</small>
                        </div>
                        <span class="badge bg-danger">2 jours</span>
                    </div>
                </div>
                <div class="deadline-item mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">TP Illustrator</h6>
                            <small class="text-muted">Création d'icônes</small>
                        </div>
                        <span class="badge bg-warning">5 jours</span>
                    </div>
                </div>
                <div class="deadline-item mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Examen InDesign</h6>
                            <small class="text-muted">Mise en page</small>
                        </div>
                        <span class="badge bg-info">1 semaine</span>
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
                    <button class="btn btn-outline-primary btn-sm" onclick="downloadMonthProgram()">
                        <i class="fas fa-calendar-alt me-1"></i>
                        Programme du mois (PDF)
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="downloadWeekProgram()">
                        <i class="fas fa-calendar-week me-1"></i>
                        Programme de la semaine
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="exportCalendar()">
                        <i class="fas fa-calendar-plus me-1"></i>
                        Exporter vers calendrier
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="printProgram()">
                        <i class="fas fa-print me-1"></i>
                        Imprimer
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

.week-section {
    border-left: 4px solid #e9ecef;
    padding-left: 1rem;
    margin-left: 0.5rem;
}

.week-section:hover {
    border-left-color: var(--primary-color);
    background-color: #f8f9fa;
    border-radius: 0 8px 8px 0;
    transition: all 0.3s ease;
}

.module-item {
    padding: 0.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.module-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.deadline-item {
    padding: 0.75rem;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.deadline-item:hover {
    border-color: var(--primary-color);
    box-shadow: 0 2px 8px rgba(0, 51, 102, 0.1);
    transform: translateY(-2px);
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 51, 102, 0.05);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>

<script>
function downloadFullProgram() {
    // Simulation du téléchargement du programme complet
    const link = document.createElement('a');
    link.href = '#';
    link.download = 'Programme_Formation_Infographie_EVC_2024.pdf';
    
    // Afficher une notification
    showNotification('Téléchargement du programme complet en cours...', 'success');
    
    // Simuler le téléchargement
    setTimeout(() => {
        showNotification('Programme complet téléchargé avec succès!', 'success');
    }, 2000);
}

function downloadMonthProgram() {
    showNotification('Téléchargement du programme du mois...', 'info');
    setTimeout(() => {
        showNotification('Programme du mois téléchargé!', 'success');
    }, 1500);
}

function downloadWeekProgram() {
    showNotification('Téléchargement du programme de la semaine...', 'info');
    setTimeout(() => {
        showNotification('Programme de la semaine téléchargé!', 'success');
    }, 1500);
}

function exportCalendar() {
    showNotification('Export vers le calendrier en cours...', 'info');
    setTimeout(() => {
        showNotification('Événements ajoutés à votre calendrier!', 'success');
    }, 2000);
}

function printProgram() {
    window.print();
}

function showNotification(message, type) {
    // Créer une notification toast
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Supprimer automatiquement après 4 secondes
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 4000);
}

// Animation des badges au survol
document.addEventListener('DOMContentLoaded', function() {
    const badges = document.querySelectorAll('.badge');
    badges.forEach(badge => {
        badge.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
            this.style.transition = 'transform 0.2s ease';
        });
        
        badge.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
});
</script>
@endsection
