@extends('layouts.ki-admin')

@section('title', 'Paiement et Facturation - EVC 2024')
@section('page-title', 'Paiement et Facturation')

@section('content')
<!-- Header avec informations principales -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #003366, #0066cc); color: white;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2">
                            <i class="fas fa-graduation-cap me-2"></i>
                            Formation Infographie EVC 2024
                        </h3>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><i class="fas fa-calendar-plus me-2"></i><strong>Date d'inscription :</strong> 15 Avril 2024</p>
                                <p class="mb-1"><i class="fas fa-clock me-2"></i><strong>Durée :</strong> 4 mois</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><i class="fas fa-calendar-check me-2"></i><strong>Date de fin :</strong> 15 Août 2024</p>
                                <p class="mb-1"><i class="fas fa-euro-sign me-2"></i><strong>Coût total :</strong> 1 200 €</p>
                            </div>
                        </div>
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
                        <small>Formation payée</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PRIORITÉ : Prochain paiement - Solde de formation -->
<div class="row mb-5">
    <div class="col-12">
        <div class="card payment-due-card" style="border: 3px solid #FF9900; background: linear-gradient(135deg, #fff8f0 0%, #ffffff 100%);">
            <div class="card-header" style="background: linear-gradient(135deg, #FF9900 0%, #ff6633 100%); color: white;">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
                        <div>
                            <h4 class="mb-0">🔥 PROCHAIN PAIEMENT - SOLDE DE FORMATION</h4>
                            <small class="opacity-75">Dernière échéance pour finaliser votre formation</small>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-light text-dark fs-6 px-3 py-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Échéance : 15 Août 2024
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body py-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3 mb-md-0">
                                <div class="payment-amount-display">
                                    <div class="display-4 fw-bold" style="color: #FF9900;">300 €</div>
                                    <p class="text-muted mb-0">Montant à payer</p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="payment-details">
                                    <h6 class="fw-bold mb-3" style="color: #003366;">Détails du paiement final :</h6>
                                    <ul class="list-unstyled mb-0">
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <strong>Solde de la formation Infographie</strong>
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-certificate text-primary me-2"></i>
                                            Accès au certificat de fin de formation
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-download text-info me-2"></i>
                                            Téléchargement de tous les supports de cours
                                        </li>
                                        <li class="mb-0">
                                            <i class="fas fa-users text-warning me-2"></i>
                                            Accès à vie à la communauté des diplômés
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="payment-action">
                            <button class="btn btn-lg px-5 py-3 mb-3"
                                    style="background: linear-gradient(135deg, #FF9900 0%, #ff6633 100%); border: none; color: white; font-weight: bold; border-radius: 15px; box-shadow: 0 4px 15px rgba(255, 153, 0, 0.3);"
                                    onclick="processPayment()">
                                <i class="fas fa-credit-card me-2"></i>
                                PAYER MAINTENANT
                            </button>
                            <div class="payment-security text-muted small">
                                <i class="fas fa-shield-alt me-1"></i>
                                Paiement 100% sécurisé
                            </div>
                            <div class="payment-methods mt-2">
                                <i class="fab fa-cc-visa me-1" style="color: #1a1f71;"></i>
                                <i class="fab fa-cc-mastercard me-1" style="color: #eb001b;"></i>
                                <i class="fab fa-paypal me-1" style="color: #003087;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Résumé des paiements -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Résumé des paiements
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="payment-summary-card text-center p-3" style="background-color: #e8f5e8; border-radius: 8px;">
                            <h4 style="color: #28a745;">300 €</h4>
                            <small class="text-muted">Premier paiement</small>
                            <br><small class="text-success">✓ Payé le 15/04/2024</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="payment-summary-card text-center p-3" style="background-color: #e3f2fd; border-radius: 8px;">
                            <h4 style="color: #003366;">900 €</h4>
                            <small class="text-muted">Total payé</small>
                            <br><small class="text-muted">3 mensualités</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="payment-summary-card text-center p-3" style="background-color: #fff3e0; border-radius: 8px;">
                            <h4 style="color: #FF9900;">300 €</h4>
                            <small class="text-muted">Montant restant</small>
                            <br><small class="text-warning">1 mensualité</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="payment-summary-card text-center p-3" style="background-color: #f3e5f5; border-radius: 8px;">
                            <h4 style="color: #6f42c1;">1 200 €</h4>
                            <small class="text-muted">Coût total</small>
                            <br><small class="text-muted">Formation complète</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des paiements et factures -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Historique des paiements et factures
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date de paiement</th>
                                <th>Description</th>
                                <th>Montant</th>
                                <th>Méthode</th>
                                <th>Statut</th>
                                <th>Facture</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <strong>15 Juillet 2024</strong><br>
                                    <small class="text-muted">Mensualité 3/4</small>
                                </td>
                                <td>
                                    Formation Infographie<br>
                                    <small class="text-muted">Mois 3 - Juillet 2024</small>
                                </td>
                                <td><strong>300 €</strong></td>
                                <td>
                                    <i class="fas fa-credit-card me-1"></i>
                                    Carte bancaire
                                </td>
                                <td><span class="badge bg-success">Payé</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="downloadInvoice('INV-2024-003')">
                                        <i class="fas fa-download me-1"></i>
                                        Télécharger
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>15 Juin 2024</strong><br>
                                    <small class="text-muted">Mensualité 2/4</small>
                                </td>
                                <td>
                                    Formation Infographie<br>
                                    <small class="text-muted">Mois 2 - Juin 2024</small>
                                </td>
                                <td><strong>300 €</strong></td>
                                <td>
                                    <i class="fas fa-university me-1"></i>
                                    Virement bancaire
                                </td>
                                <td><span class="badge bg-success">Payé</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="downloadInvoice('INV-2024-002')">
                                        <i class="fas fa-download me-1"></i>
                                        Télécharger
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>15 Mai 2024</strong><br>
                                    <small class="text-muted">Mensualité 1/4</small>
                                </td>
                                <td>
                                    Formation Infographie<br>
                                    <small class="text-muted">Mois 1 - Mai 2024</small>
                                </td>
                                <td><strong>300 €</strong></td>
                                <td>
                                    <i class="fas fa-credit-card me-1"></i>
                                    Carte bancaire
                                </td>
                                <td><span class="badge bg-success">Payé</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="downloadInvoice('INV-2024-001')">
                                        <i class="fas fa-download me-1"></i>
                                        Télécharger
                                    </button>
                                </td>
                            </tr>
                            <tr style="background-color: #fff3e0;">
                                <td>
                                    <strong>15 Avril 2024</strong><br>
                                    <small class="text-success">Premier paiement</small>
                                </td>
                                <td>
                                    Inscription Formation<br>
                                    <small class="text-muted">Frais d'inscription</small>
                                </td>
                                <td><strong>300 €</strong></td>
                                <td>
                                    <i class="fas fa-credit-card me-1"></i>
                                    Carte bancaire
                                </td>
                                <td><span class="badge bg-success">Payé</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="downloadInvoice('INV-2024-000')">
                                        <i class="fas fa-download me-1"></i>
                                        Télécharger
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Actions groupées pour les factures -->
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Actions groupées :</small>
                        </div>
                        <div>
                            <button class="btn btn-outline-secondary btn-sm me-2" onclick="downloadAllInvoices()">
                                <i class="fas fa-download me-1"></i>
                                Télécharger toutes les factures
                            </button>
                            <button class="btn btn-outline-info btn-sm" onclick="emailInvoices()">
                                <i class="fas fa-envelope me-1"></i>
                                Envoyer par email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <!-- Sidebar -->
    <div class="col-md-4">
        <!-- Informations de formation -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informations de formation
                </h6>
            </div>
            <div class="card-body">
                <div class="info-item mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Date d'inscription :</span>
                        <strong>15 Avril 2024</strong>
                    </div>
                </div>
                <div class="info-item mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Durée :</span>
                        <strong>4 mois</strong>
                    </div>
                </div>
                <div class="info-item mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Date de fin :</span>
                        <strong>15 Août 2024</strong>
                    </div>
                </div>
                <div class="info-item mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Statut :</span>
                        <span class="badge bg-success">En cours</span>
                    </div>
                </div>
                <hr>
                <div class="info-item">
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Coût total :</span>
                        <strong style="color: #003366;">1 200 €</strong>
                    </div>
                </div>
            </div>
        </div>




        <!-- Support et aide -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-headset me-2"></i>
                    Support et aide
                </h6>
            </div>
            <div class="card-body">
                <p class="small mb-3">Besoin d'aide avec vos paiements ou votre facturation ?</p>
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="contactSupport()">
                        <i class="fas fa-envelope me-1"></i>
                        Contacter le support
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="openFAQ()">
                        <i class="fas fa-question-circle me-1"></i>
                        FAQ Paiements
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

.payment-summary-card {
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.payment-summary-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-color);
}

.info-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.info-item:last-child {
    border-bottom: none;
}

.payment-method {
    transition: all 0.3s ease;
}

.payment-method:hover {
    background-color: #f8f9fa;
    border-color: var(--primary-color) !important;
}

.payment-due-card {
    animation: pulse-glow 2s infinite;
}

@keyframes pulse-glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(255, 153, 0, 0.3);
    }
    50% {
        box-shadow: 0 0 20px rgba(255, 153, 0, 0.6);
    }
}

.btn:hover {
    transform: translateY(-1px);
    transition: all 0.3s ease;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 51, 102, 0.05);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

@media (max-width: 768px) {
    .payment-summary-card {
        margin-bottom: 1rem;
    }

    .table-responsive {
        font-size: 0.875rem;
    }

    .progress-circle {
        width: 100px !important;
        height: 100px !important;
    }
}
</style>

<script>
// Fonction pour télécharger une facture individuelle
function downloadInvoice(invoiceId) {
    showNotification(`Téléchargement de la facture ${invoiceId} en cours...`, 'info');

    // Simulation du téléchargement
    setTimeout(() => {
        showNotification(`Facture ${invoiceId} téléchargée avec succès!`, 'success');
    }, 1500);
}

// Fonction pour télécharger toutes les factures
function downloadAllInvoices() {
    showNotification('Préparation du téléchargement de toutes les factures...', 'info');

    setTimeout(() => {
        showNotification('Toutes les factures ont été téléchargées dans un fichier ZIP!', 'success');
    }, 3000);
}

// Fonction pour envoyer les factures par email
function emailInvoices() {
    showNotification('Envoi des factures par email...', 'info');

    setTimeout(() => {
        showNotification('Factures envoyées à votre adresse email!', 'success');
    }, 2000);
}

// Fonction pour traiter le paiement
function processPayment() {
    const confirmation = confirm('Confirmer le paiement de 300 € pour solder votre formation ?');

    if (confirmation) {
        showNotification('Redirection vers la page de paiement sécurisé...', 'info');

        // Simulation du processus de paiement
        setTimeout(() => {
            showNotification('Paiement traité avec succès! Formation soldée.', 'success');
            // Ici on pourrait rediriger vers une page de confirmation
        }, 3000);
    }
}

// Fonction pour gérer les moyens de paiement
function managePaymentMethods() {
    showNotification('Ouverture de la gestion des moyens de paiement...', 'info');
    // Ici on ouvrirait une modal ou redirigerait vers une page dédiée
}

// Fonction pour ajouter un moyen de paiement
function addPaymentMethod() {
    showNotification('Ouverture du formulaire d\'ajout de carte...', 'info');
    // Ici on ouvrirait une modal pour ajouter une nouvelle carte
}

// Fonction pour télécharger le récapitulatif des paiements
function downloadPaymentSummary() {
    showNotification('Génération du récapitulatif des paiements...', 'info');

    setTimeout(() => {
        showNotification('Récapitulatif des paiements téléchargé!', 'success');
    }, 2000);
}

// Fonction pour configurer le paiement automatique
function setupAutoPayment() {
    showNotification('Ouverture de la configuration du paiement automatique...', 'info');
    // Ici on ouvrirait une modal de configuration
}

// Fonction pour demander un plan de paiement
function requestPaymentPlan() {
    showNotification('Ouverture du formulaire de demande de plan de paiement...', 'info');
    // Ici on ouvrirait un formulaire de demande
}

// Fonction pour contacter le support
function contactSupport() {
    showNotification('Ouverture du formulaire de contact...', 'info');
    // Ici on ouvrirait une modal de contact ou redirigerait
}

// Fonction pour ouvrir la FAQ
function openFAQ() {
    showNotification('Ouverture de la FAQ des paiements...', 'info');
    // Ici on redirigerait vers la page FAQ
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

    // Supprimer automatiquement après 5 secondes
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
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
    // Animation des cartes de résumé
    const summaryCards = document.querySelectorAll('.payment-summary-card');
    summaryCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
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
