@extends('layouts.admin')

@section('title', 'Rapports et Analytics')

@push('styles')
<style>
    body {
        background: #0f172a;
    }

    .reports-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        animation: fadeIn 0.5s ease;
    }

    .reports-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .reports-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .quick-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }

    .btn-action {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-action:hover {
        background: rgba(255,255,255,0.3);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(255,255,255,0.2);
        color: white;
    }

    .stats-modern {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        animation: fadeIn 0.6s ease;
    }

    .stat-modern {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #4fc3f7 0%, #29b6f6 100%);
    }

    .stat-modern.success::before {
        background: linear-gradient(90deg, #56ab2f 0%, #a8e6cf 100%);
    }

    .stat-modern.warning::before {
        background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-modern.info::before {
        background: linear-gradient(90deg, #4facfe 0%, #00f2fe 100%);
    }

    .stat-modern:hover {
        transform: translateY(-5px);
        border-color: #4fc3f7;
        box-shadow: 0 10px 30px rgba(79, 195, 247, 0.2);
    }

    .stat-icon-modern {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1rem;
        background: rgba(79, 195, 247, 0.2);
        color: #4fc3f7;
    }

    .stat-modern.success .stat-icon-modern {
        background: rgba(86, 171, 47, 0.2);
        color: #56ab2f;
    }

    .stat-modern.warning .stat-icon-modern {
        background: rgba(240, 147, 251, 0.2);
        color: #f093fb;
    }

    .stat-modern.info .stat-icon-modern {
        background: rgba(79, 172, 254, 0.2);
        color: #4facfe;
    }

    .stat-number-modern {
        font-size: 2.5rem;
        font-weight: 700;
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .stat-modern.success .stat-number-modern {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-modern.warning .stat-number-modern {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-modern.info .stat-number-modern {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label-modern {
        color: #94a3b8;
        font-size: 0.95rem;
    }

    .reports-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
        animation: fadeIn 0.7s ease;
    }

    .report-card-modern {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
    }

    .report-card-modern:hover {
        transform: translateY(-8px);
        border-color: #4fc3f7;
        box-shadow: 0 12px 40px rgba(79, 195, 247, 0.3);
    }

    .report-icon-modern {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
    }

    .report-icon-modern.primary { color: #4fc3f7; }
    .report-icon-modern.success { color: #56ab2f; }
    .report-icon-modern.warning { color: #ffc107; }
    .report-icon-modern.info { color: #4facfe; }

    .report-title-modern {
        font-size: 1.3rem;
        font-weight: 600;
        color: white;
        margin-bottom: 1rem;
    }

    .report-description-modern {
        color: #94a3b8;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        line-height: 1.6;
    }

    .btn-generate {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-generate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .btn-generate.success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    }

    .btn-generate.success:hover {
        box-shadow: 0 6px 20px rgba(86, 171, 47, 0.4);
    }

    .btn-generate.warning {
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    }

    .btn-generate.warning:hover {
        box-shadow: 0 6px 20px rgba(255, 193, 7, 0.4);
    }

    .btn-generate.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .btn-generate.info:hover {
        box-shadow: 0 6px 20px rgba(79, 172, 254, 0.4);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .reports-title {
            font-size: 2rem;
        }

        .stats-modern, .reports-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="reports-header">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h1 class="reports-title">
                    <i class="fas fa-chart-line me-3"></i>Rapports et Analytics
                </h1>
                <p class="reports-subtitle">
                    Analysez les performances et générez des rapports détaillés sur votre plateforme
                </p>
                <div class="quick-actions">
                    <button class="btn-action" onclick="generateCustomReport()">
                        <i class="fas fa-plus me-2"></i>Nouveau Rapport
                    </button>
                    <button class="btn-action" onclick="exportData()">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                    <button class="btn-action" onclick="scheduleReport()">
                        <i class="fas fa-clock me-2"></i>Programmer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="stats-modern">
        <div class="stat-modern">
            <div class="stat-icon-modern">
                <i class="fas fa-file-alt"></i>
            </div>
            <h3 class="stat-number-modern">{{ $stats['total_reports'] ?? 47 }}</h3>
            <p class="stat-label-modern mb-0">Rapports Générés</p>
        </div>

        <div class="stat-modern success">
            <div class="stat-icon-modern">
                <i class="fas fa-download"></i>
            </div>
            <h3 class="stat-number-modern">{{ $stats['monthly_exports'] ?? 23 }}</h3>
            <p class="stat-label-modern mb-0">Exports ce Mois</p>
        </div>

        <div class="stat-modern warning">
            <div class="stat-icon-modern">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h3 class="stat-number-modern">{{ $stats['active_analytics'] ?? 12 }}</h3>
            <p class="stat-label-modern mb-0">Analytics Actives</p>
        </div>

        <div class="stat-modern info">
            <div class="stat-icon-modern">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="stat-number-modern">{{ $stats['scheduled_reports'] ?? 8 }}</h3>
            <p class="stat-label-modern mb-0">Rapports Programmés</p>
        </div>
    </div>

    <!-- Types de Rapports -->
    <div class="reports-grid">
        <!-- Rapport Étudiants -->
        <div class="report-card-modern">
            <div class="report-icon-modern primary">
                <i class="fas fa-users"></i>
            </div>
            <h3 class="report-title-modern">Rapport Étudiants</h3>
            <p class="report-description-modern">
                Statistiques complètes sur les étudiants inscrits et leur progression
            </p>
            <button class="btn-generate" onclick="generateReport('students', event)">
                <i class="fas fa-play me-2"></i>Générer
            </button>
        </div>

        <!-- Rapport Formations -->
        <div class="report-card-modern">
            <div class="report-icon-modern success">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h3 class="report-title-modern">Rapport Formations</h3>
            <p class="report-description-modern">
                Performance des formations, modules et taux de réussite
            </p>
            <a href="{{ route('admin.rapports.formations') }}" class="btn-generate success">
                <i class="fas fa-eye me-2"></i>Voir le Rapport
            </a>
        </div>

        <!-- Rapport Financier -->
        <div class="report-card-modern">
            <div class="report-icon-modern warning">
                <i class="fas fa-chart-pie"></i>
            </div>
            <h3 class="report-title-modern">Rapport Financier</h3>
            <p class="report-description-modern">
                Revenus, paiements et analytics financières détaillées
            </p>
            <a href="{{ route('admin.rapports.financier') }}" class="btn-generate warning">
                <i class="fas fa-eye me-2"></i>Voir le Rapport
            </a>
        </div>

        <!-- Rapport Activités -->
        <div class="report-card-modern">
            <div class="report-icon-modern info">
                <i class="fas fa-tasks"></i>
            </div>
            <h3 class="report-title-modern">Rapport Activités</h3>
            <p class="report-description-modern">
                TP, projets et activités des étudiants par formation
            </p>
            <button class="btn-generate info" onclick="generateReport('activities', event)">
                <i class="fas fa-play me-2"></i>Générer
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateReport(type, event) {
    // Désactiver le bouton et afficher un loader
    const btn = event ? event.target.closest('button') : null;
    let originalHTML = '';

    if (btn) {
        originalHTML = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Génération...';
    }

    fetch('{{ route("admin.rapports.generate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }

        if (data.success) {
            showReportModal(type, data.data);
        } else {
            alert('Erreur: ' + data.message);
        }
    })
    .catch(error => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = originalHTML;
        }
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la génération du rapport.');
    });
}

function showReportModal(type, data) {
    let content = '';

    switch(type) {
        case 'students':
            content = `
                <div class="report-details">
                    <h4 class="text-white mb-3">📊 Rapport Étudiants</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-primary">${data.total_students_including_legacy ?? data.total_students}</h3>
                                <p class="mb-0">Total Étudiants</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-success">${data.active_students}</h3>
                                <p class="mb-0">Étudiants Actifs</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-warning">${data.legacy_inactive_students ?? 0}</h3>
                                <p class="mb-0">Anciens formés (inactifs)</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-info">${data.avg_tp_validated}</h3>
                                <p class="mb-0">Moyenne TP Validés</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-warning">${data.avg_projects_completed}</h3>
                                <p class="mb-0">Moyenne Projets Complétés</p>
                            </div>
                        </div>
                    </div>
                    <h5 class="text-white mt-4 mb-3">Par Formation</h5>
                    <div class="formation-stats">
                        ${Object.entries(data.by_formation).map(([formation, stats]) => `
                            <div class="formation-item">
                                <strong>${formation}</strong>: ${stats.total} étudiants (${stats.active} actifs)
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
            break;

        case 'formations':
            content = `
                <div class="report-details">
                    <h4 class="text-white mb-3">🎓 Rapport Formations</h4>
                    <div class="stat-box mb-4">
                        <h3 class="text-primary">${data.total_formations}</h3>
                        <p class="mb-0">Total Formations</p>
                    </div>
                    <div class="formations-list">
                        ${data.formations.map(f => `
                            <div class="formation-card">
                                <h5 class="text-white">${f.name}</h5>
                                <p class="mb-1"><strong>Module:</strong> ${f.module}</p>
                                <p class="mb-0"><strong>Inscrits:</strong> ${f.enrolled} étudiants</p>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
            break;

        case 'financial':
            content = `
                <div class="report-details">
                    <h4 class="text-white mb-3">💰 Rapport Financier</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-success">${formatCurrency(data.total_revenue)}</h3>
                                <p class="mb-0">Revenu Total</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-info">${formatCurrency(data.monthly_revenue)}</h3>
                                <p class="mb-0">Revenu du Mois</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-warning">${formatCurrency(data.pending_payments)}</h3>
                                <p class="mb-0">Paiements en Attente</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-danger">${formatCurrency(data.balance)}</h3>
                                <p class="mb-0">Solde Restant</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;

        case 'activities':
            content = `
                <div class="report-details">
                    <h4 class="text-white mb-3">📝 Rapport Activités</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-primary">${data.total_tps}</h3>
                                <p class="mb-0">Total TPs</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-success">${data.validated_tps}</h3>
                                <p class="mb-0">TPs Validés</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-info">${data.total_projects}</h3>
                                <p class="mb-0">Total Projets</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="stat-box">
                                <h3 class="text-warning">${data.completed_projects}</h3>
                                <p class="mb-0">Projets Complétés</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="progress-card">
                                <p class="mb-2">Taux de complétion TPs</p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-success" style="width: ${data.completion_rate_tps}%">
                                        ${data.completion_rate_tps}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="progress-card">
                                <p class="mb-2">Taux de complétion Projets</p>
                                <div class="progress" style="height: 25px;">
                                    <div class="progress-bar bg-info" style="width: ${data.completion_rate_projects}%">
                                        ${data.completion_rate_projects}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            break;
    }

    // Créer et afficher la modale
    const modal = `
        <div class="modal fade" id="reportModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border: 1px solid #334155;">
                    <div class="modal-header" style="border-bottom: 1px solid #334155;">
                        <h5 class="modal-title text-white">Résultats du Rapport</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        ${content}
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #334155;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" onclick="downloadReportData('${type}')">
                            <i class="fas fa-download me-2"></i>Télécharger
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Supprimer l'ancienne modale si elle existe
    const oldModal = document.getElementById('reportModal');
    if (oldModal) {
        oldModal.remove();
    }

    // Ajouter la nouvelle modale
    document.body.insertAdjacentHTML('beforeend', modal);
    const reportModal = new bootstrap.Modal(document.getElementById('reportModal'));
    reportModal.show();
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('fr-FR', {
        style: 'currency',
        currency: 'XOF',
        minimumFractionDigits: 0
    }).format(amount);
}

function downloadReportData(type) {
    window.location.href = `/evc/app/admin/rapports/download/${type}`;
}

function generateCustomReport() {
    alert('Création de rapport personnalisé - Fonctionnalité bientôt disponible');
}

function exportData() {
    window.location.href = '{{ route("admin.rapports.exports") }}';
}

function scheduleReport() {
    alert('Programmation de rapports automatiques - Fonctionnalité bientôt disponible');
}
</script>

<style>
.stat-box {
    background: rgba(79, 195, 247, 0.1);
    border: 1px solid rgba(79, 195, 247, 0.3);
    border-radius: 12px;
    padding: 1.5rem;
    text-align: center;
}

.stat-box h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.stat-box p {
    color: #94a3b8;
    font-size: 0.9rem;
}

.formation-stats {
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    padding: 1rem;
}

.formation-item {
    color: #94a3b8;
    padding: 0.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.formation-item:last-child {
    border-bottom: none;
}

.formation-card {
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.formation-card p {
    color: #94a3b8;
    margin-bottom: 0.5rem;
}

.progress-card {
    background: rgba(255,255,255,0.05);
    border-radius: 12px;
    padding: 1rem;
}

.progress-card p {
    color: #94a3b8;
}
</style>
@endpush
