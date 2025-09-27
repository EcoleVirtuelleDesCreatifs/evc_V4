@extends('layouts.admin')

@section('title', 'Gestion des Catégories')

@push('styles')
<style>
/* Design identique à admin/students - Épuré et Moderne */
.dashboard-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4);
    position: relative;
    overflow: hidden;
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%, rgba(255,255,255,0.1) 100%);
    pointer-events: none;
}

.dashboard-icon {
    background: rgba(255,255,255,0.2);
    border-radius: 15px;
    padding: 15px;
    backdrop-filter: blur(10px);
}

.dashboard-stat-mini {
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
}

.dashboard-stat-mini:hover {
    transform: translateY(-2px);
    background: rgba(255,255,255,0.15) !important;
}

:root {
    --dashboard-bg: rgba(255,255,255,0.1);
    --dashboard-border: rgba(255,255,255,0.2);
}

.text-white-75 {
    color: rgba(255,255,255,0.75) !important;
}

.text-white-50 {
    color: rgba(255,255,255,0.5) !important;
}

/* Table moderne et épurée */
.table-modern {
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.1);
}

.table-modern th {
    background: rgba(255,255,255,0.1);
    color: white;
    font-weight: 600;
    border: none;
    padding: 15px;
}

.table-modern td {
    background: rgba(255,255,255,0.03);
    color: white;
    border: none;
    padding: 12px 15px;
    vertical-align: middle;
}

.table-modern tbody tr:hover {
    background: rgba(255,255,255,0.08);
    transform: scale(1.01);
    transition: all 0.2s ease;
}

/* Badges modernes */
.badge-modern {
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 500;
    font-size: 0.75rem;
}

.badge-success {
    background: rgba(40, 167, 69, 0.2);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.badge-warning {
    background: rgba(255, 193, 7, 0.2);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.badge-primary {
    background: rgba(0, 123, 255, 0.2);
    color: #007bff;
    border: 1px solid rgba(0, 123, 255, 0.3);
}

/* Boutons d'action modernes */
.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    margin: 0 2px;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.btn-action::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%, rgba(255,255,255,0.1) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-action:hover::before {
    opacity: 1;
}

.btn-action:hover {
    transform: scale(1.15) rotate(5deg);
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
}

.btn-view {
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.3), rgba(0, 123, 255, 0.1));
    color: #007bff;
    border: 1px solid rgba(0, 123, 255, 0.3);
}

.btn-edit {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.3), rgba(255, 193, 7, 0.1));
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.btn-delete {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.3), rgba(220, 53, 69, 0.1));
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

/* Améliorations du tableau */
.table-modern thead th {
    position: relative;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    font-weight: 700;
}

.table-modern thead th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 15px;
    right: 15px;
    height: 2px;
    background: linear-gradient(90deg, rgba(255,255,255,0.3), transparent);
}

.table-modern tbody tr {
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.table-modern tbody tr::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.table-modern tbody tr:hover::before {
    opacity: 1;
}

.table-modern tbody tr:hover {
    background: rgba(255,255,255,0.12) !important;
    transform: translateX(5px) scale(1.005);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
}

/* Icônes de catégorie améliorées */
.dashboard-icon {
    position: relative;
    overflow: hidden;
}

.dashboard-icon::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
    transform: rotate(45deg);
    transition: transform 0.6s ease;
}

.dashboard-icon:hover::before {
    transform: rotate(45deg) translate(50%, 50%);
}

/* Badges améliorés */
.badge-modern {
    position: relative;
    overflow: hidden;
    text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.badge-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.badge-modern:hover::before {
    left: 100%;
}

/* Animation d'entrée pour les lignes */
@keyframes slideInFromLeft {
    0% {
        opacity: 0;
        transform: translateX(-30px);
    }
    100% {
        opacity: 1;
        transform: translateX(0);
    }
}

.table-modern tbody tr {
    animation: slideInFromLeft 0.5s ease forwards;
}

.table-modern tbody tr:nth-child(1) { animation-delay: 0.1s; }
.table-modern tbody tr:nth-child(2) { animation-delay: 0.2s; }
.table-modern tbody tr:nth-child(3) { animation-delay: 0.3s; }
.table-modern tbody tr:nth-child(4) { animation-delay: 0.4s; }
.table-modern tbody tr:nth-child(5) { animation-delay: 0.5s; }

/* Styles pour les fonctionnalités de tri et filtrage */
.sortable {
    cursor: pointer;
    user-select: none;
    transition: all 0.2s ease;
}

.sortable:hover {
    background: rgba(255,255,255,0.1) !important;
}

.sort-icon {
    opacity: 0.5;
    transition: all 0.3s ease;
    font-size: 0.7rem;
}

.sortable:hover .sort-icon {
    opacity: 1;
}

.sortable.sorted-asc .sort-icon::before {
    content: "\f0de";
    color: #667eea;
}

.sortable.sorted-desc .sort-icon::before {
    content: "\f0dd";
    color: #667eea;
}

.category-icon-wrapper {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: rgba(255,255,255,0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(5px);
}

.category-info {
    min-height: 45px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.description-cell {
    max-width: 300px;
}

.stat-cell,
.status-cell {
    min-height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Styles pour les filtres */
.form-select:focus,
.form-control:focus {
    background: rgba(255,255,255,0.15) !important;
    border-color: rgba(102, 126, 234, 0.5) !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
    color: white !important;
}

.form-select option {
    background: #1a1a1a;
    color: white;
}

/* Animation pour les lignes filtrées */
.category-row.hidden {
    opacity: 0;
    transform: translateX(-20px);
    transition: all 0.3s ease;
    pointer-events: none;
}

.category-row.visible {
    opacity: 1;
    transform: translateX(0);
    transition: all 0.3s ease;
}

/* Responsive amélioré */
@media (max-width: 768px) {
    .btn-action {
        width: 30px;
        height: 30px;
        font-size: 0.8rem;
    }

    .table-modern th,
    .table-modern td {
        padding: 10px 8px;
        font-size: 0.85rem;
    }

    .dashboard-icon {
        padding: 6px;
        font-size: 0.8rem;
    }

    .d-flex.align-items-center.justify-content-between {
        flex-direction: column;
        gap: 15px;
    }

    .d-flex.align-items-center.gap-2 {
        flex-direction: column;
        width: 100%;
        gap: 10px;
    }

    .form-control,
    .form-select {
        width: 100% !important;
    }
}

/* Layout Ultra-Professionnel */
.professional-container {
    min-height: 100vh;
    background: linear-gradient(135deg, var(--gray-25) 0%, var(--gray-50) 100%);
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    font-feature-settings: 'cv02', 'cv03', 'cv04', 'cv11';
    position: relative;
    overflow-x: hidden;
}

.professional-container::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 300px;
    background: var(--primary-gradient);
    opacity: 0.03;
    z-index: 0;
    pointer-events: none;
}

.page-header {
    background: var(--white);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid var(--gray-100);
    padding: var(--space-8) 0;
    margin-bottom: var(--space-8);
    position: relative;
    z-index: 10;
    box-shadow: var(--shadow-xs);
}

.content-wrapper {
    max-width: 1440px;
    margin: 0 auto;
    padding: 0 var(--space-6);
    position: relative;
    z-index: 5;
}

/* Header Ultra-Moderne */
.page-title {
    font-size: 2.25rem;
    font-weight: 800;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
    letter-spacing: -0.03em;
    line-height: 1.1;
}

.page-subtitle {
    font-size: 1.125rem;
    color: var(--gray-500);
    margin: var(--space-2) 0 0 0;
    font-weight: 500;
    letter-spacing: -0.01em;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: var(--space-3);
}

.btn-primary-modern {
    background: var(--primary-gradient);
    border: none;
    color: var(--white);
    padding: var(--space-3) var(--space-6);
    border-radius: var(--radius-xl);
    font-weight: 600;
    font-size: 0.875rem;
    transition: all var(--transition-base);
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    cursor: pointer;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.btn-primary-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: var(--transition-slow);
}

.btn-primary-modern:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow: var(--shadow-xl);
    color: var(--white);
}

.btn-primary-modern:hover::before {
    left: 100%;
}

.btn-primary-modern:active {
    transform: translateY(-1px) scale(1.01);
    transition: var(--transition-micro);
}

/* Métriques Ultra-Modernes */
.metrics-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--space-6);
    margin-bottom: var(--space-12);
}

.metric-card {
    background: var(--white);
    border: 1px solid var(--gray-100);
    border-radius: var(--radius-2xl);
    padding: var(--space-6);
    transition: all var(--transition-base);
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    box-shadow: var(--shadow-xs);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-gradient);
    opacity: 0;
    transition: var(--transition-base);
    z-index: 0;
}

.metric-card:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-2xl);
    transform: translateY(-4px) scale(1.02);
}

.metric-card:hover::before {
    opacity: 0.02;
}

.metric-card > * {
    position: relative;
    z-index: 1;
}

.metric-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--metric-color, var(--primary-gradient));
    opacity: 0;
    transition: var(--transition-spring);
    border-radius: var(--radius-2xl) var(--radius-2xl) 0 0;
}

.metric-card:hover::after {
    opacity: 1;
    transform: scaleX(1);
}

.metric-icon {
    width: 3.5rem;
    height: 3.5rem;
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--space-4);
    background: var(--metric-bg, linear-gradient(135deg, rgba(30, 64, 175, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%));
    color: var(--metric-color, var(--primary));
    font-size: 1.375rem;
    transition: var(--transition-base);
    position: relative;
    overflow: hidden;
}

.metric-icon::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: var(--metric-color, var(--primary));
    border-radius: 50%;
    transition: var(--transition-spring);
    transform: translate(-50%, -50%);
    opacity: 0.1;
}

.metric-card:hover .metric-icon::before {
    width: 100%;
    height: 100%;
}

.metric-card:hover .metric-icon {
    transform: scale(1.1) rotate(5deg);
    color: var(--white);
}

.metric-value {
    font-size: 2.25rem;
    font-weight: 800;
    color: var(--gray-900);
    margin: 0;
    line-height: 1;
    font-variant-numeric: tabular-nums;
    transition: var(--transition-base);
}

.metric-card:hover .metric-value {
    transform: scale(1.05);
}

.metric-label {
    font-size: 0.875rem;
    color: var(--gray-500);
    font-weight: 600;
    margin: var(--space-1) 0 0 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.metric-trend {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 0.5rem;
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
}

.metric-trend.positive {
    color: var(--success);
    background: rgba(16, 185, 129, 0.1);
}

/* Section Catégories Ultra-Moderne */
.categories-section {
    margin-top: var(--space-8);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--space-6);
    padding-bottom: var(--space-4);
    border-bottom: 1px solid var(--gray-100);
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0;
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: var(--space-3);
}

.section-title::before {
    content: '';
    width: 4px;
    height: 2rem;
    background: var(--primary-gradient);
    border-radius: var(--radius-sm);
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: var(--space-6);
    align-items: start;
}

.category-card {
    background: var(--white);
    border: 1px solid var(--gray-100);
    border-radius: var(--radius-2xl);
    padding: var(--space-6);
    transition: all var(--transition-base);
    position: relative;
    cursor: pointer;
    backdrop-filter: blur(10px);
    box-shadow: var(--shadow-xs);
    overflow: hidden;
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-gradient);
    opacity: 0;
    transition: var(--transition-slow);
    z-index: 0;
}

.category-card:hover {
    border-color: var(--primary-light);
    box-shadow: var(--shadow-2xl);
    transform: translateY(-6px) scale(1.02);
}

.category-card:hover::before {
    opacity: 0.03;
}

.category-card > * {
    position: relative;
    z-index: 1;
}

.category-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: var(--space-4);
}

.category-icon-wrapper {
    width: 4rem;
    height: 4rem;
    border-radius: var(--radius-xl);
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--category-bg, linear-gradient(135deg, rgba(30, 64, 175, 0.1) 0%, rgba(59, 130, 246, 0.15) 100%));
    color: var(--category-color, var(--primary));
    flex-shrink: 0;
    font-size: 1.625rem;
    transition: var(--transition-spring);
    position: relative;
    overflow: hidden;
}

.category-icon-wrapper::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: var(--category-color, var(--primary));
    border-radius: 50%;
    transition: var(--transition-spring);
    transform: translate(-50%, -50%);
    opacity: 0.1;
}

.category-card:hover .category-icon-wrapper::before {
    width: 120%;
    height: 120%;
}

.category-card:hover .category-icon-wrapper {
    transform: scale(1.1) rotate(-5deg);
    color: var(--white);
}

.category-info {
    flex: 1;
    margin-left: var(--space-4);
}

.category-name {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0 0 var(--space-1) 0;
    line-height: 1.2;
    letter-spacing: -0.01em;
    transition: var(--transition-base);
}

.category-card:hover .category-name {
    color: var(--primary-dark);
    transform: translateX(2px);
}

.category-description {
    font-size: 0.875rem;
    color: var(--gray-500);
    line-height: 1.5;
    margin: 0;
    font-weight: 500;
}

.category-actions {
    opacity: 0;
    transition: all var(--transition-base);
    transform: translateX(10px);
}

.category-card:hover .category-actions {
    opacity: 1;
    transform: translateX(0);
}

.action-btn {
    width: 2.25rem;
    height: 2.25rem;
    border: none;
    background: var(--gray-50);
    border-radius: var(--radius-lg);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-500);
    transition: all var(--transition-base);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-gradient);
    opacity: 0;
    transition: var(--transition-base);
}

.action-btn:hover {
    color: var(--white);
    transform: scale(1.15) rotate(5deg);
    box-shadow: var(--shadow-lg);
}

.action-btn:hover::before {
    opacity: 1;
}

.action-btn > * {
    position: relative;
    z-index: 1;
}

/* Statistiques Catégorie Ultra-Modernes */
.category-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--space-3);
    margin: var(--space-4) 0;
}

.stat-item {
    text-align: center;
    padding: var(--space-3);
    background: var(--gray-25);
    border-radius: var(--radius-lg);
    border: 1px solid var(--gray-100);
    transition: all var(--transition-base);
    position: relative;
    overflow: hidden;
}

.stat-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--stat-color, var(--primary-gradient));
    opacity: 0;
    transition: var(--transition-base);
}

.stat-item:hover {
    background: var(--white);
    border-color: var(--primary-light);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.stat-item:hover::before {
    opacity: 1;
}

.stat-value {
    font-size: 1.375rem;
    font-weight: 800;
    color: var(--stat-color, var(--gray-900));
    margin: 0;
    line-height: 1;
    font-variant-numeric: tabular-nums;
    transition: var(--transition-base);
}

.stat-item:hover .stat-value {
    transform: scale(1.1);
    color: var(--primary-dark);
}

.stat-label {
    font-size: 0.75rem;
    color: var(--gray-500);
    font-weight: 600;
    margin: var(--space-1) 0 0 0;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.category-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid var(--gray-100);
}

.category-status {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: var(--gray-700);
}

.status-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background: var(--status-color, var(--success));
}

.category-actions-group {
    display: flex;
    gap: 0.5rem;
}

.btn-sm-modern {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: var(--radius-md);
    font-weight: 500;
    transition: var(--transition-fast);
    border: 1px solid var(--gray-200);
    background: white;
    color: var(--gray-700);
    cursor: pointer;
}

.btn-sm-modern:hover {
    border-color: var(--primary);
    color: var(--primary);
    background: rgba(37, 99, 235, 0.05);
}

/* Empty State Ultra-Engageant */
.empty-state {
    text-align: center;
    padding: var(--space-16) var(--space-8);
    background: var(--white);
    border: 2px dashed var(--gray-200);
    border-radius: var(--radius-2xl);
    transition: all var(--transition-base);
    grid-column: 1 / -1;
    position: relative;
    overflow: hidden;
}

.empty-state::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: var(--primary-gradient);
    opacity: 0;
    transition: var(--transition-base);
}

.empty-state:hover {
    border-color: var(--primary);
    transform: translateY(-2px);
    box-shadow: var(--shadow-xl);
}

.empty-state:hover::before {
    opacity: 0.02;
}

.empty-state > * {
    position: relative;
    z-index: 1;
}

.empty-state-icon {
    width: 5rem;
    height: 5rem;
    margin: 0 auto var(--space-4);
    color: var(--gray-400);
    background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-50) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    transition: var(--transition-spring);
    position: relative;
    overflow: hidden;
}

.empty-state-icon::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: var(--primary-gradient);
    border-radius: 50%;
    transition: var(--transition-spring);
    transform: translate(-50%, -50%);
}

.empty-state:hover .empty-state-icon::before {
    width: 100%;
    height: 100%;
}

.empty-state:hover .empty-state-icon {
    color: var(--white);
    transform: scale(1.1) rotate(10deg);
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin-bottom: var(--space-2);
    transition: var(--transition-base);
}

.empty-state:hover .empty-state-title {
    color: var(--primary-dark);
}

.empty-state-description {
    color: var(--gray-500);
    margin-bottom: var(--space-6);
    font-size: 1rem;
    line-height: 1.6;
}

/* FAB Moderne */
.floating-action {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 3.5rem;
    height: 3.5rem;
    border-radius: 50%;
    background: var(--primary);
    border: none;
    color: white;
    font-size: 1.25rem;
    box-shadow: var(--shadow-lg);
    transition: var(--transition-base);
    z-index: 1000;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.floating-action:hover {
    background: var(--primary-dark);
    transform: scale(1.1);
    box-shadow: var(--shadow-xl);
}

.floating-action:active {
    transform: scale(0.95);
}

/* Animations Ultra-Professionnelles */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

@keyframes shimmer {
    0% {
        background-position: -200px 0;
    }
    100% {
        background-position: calc(200px + 100%) 0;
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-slide-in-right {
    animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-stagger {
    animation-delay: calc(var(--stagger-delay, 0) * 150ms);
}

.animate-counter {
    font-variant-numeric: tabular-nums;
    transition: var(--transition-base);
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-shimmer {
    background: linear-gradient(90deg, var(--gray-100) 0px, var(--gray-50) 40px, var(--gray-100) 80px);
    background-size: 200px;
    animation: shimmer 1.5s infinite;
}

/* Responsive Ultra-Professionnel */
@media (max-width: 1200px) {
    .categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    }

    .metrics-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .page-title {
        font-size: 1.875rem;
    }

    .categories-grid {
        grid-template-columns: 1fr;
        gap: var(--space-4);
    }

    .metrics-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-4);
    }

    .category-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .content-wrapper {
        padding: 0 var(--space-4);
    }

    .empty-state {
        padding: var(--space-12) var(--space-6);
    }
}

@media (max-width: 480px) {
    .page-title {
        font-size: 1.5rem;
    }

    .section-title {
        font-size: 1.25rem;
    }

    .metrics-grid {
        grid-template-columns: 1fr;
    }

    .category-stats {
        grid-template-columns: 1fr;
        gap: var(--space-2);
    }

    .header-actions {
        flex-direction: column;
        gap: var(--space-2);
    }
}

@media (max-width: 768px) {
    .content-wrapper {
        padding: 0 1rem;
    }

    .page-header {
        padding: 1.5rem 0;
    }

    .page-title {
        font-size: 1.75rem;
    }

    .categories-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .metrics-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .category-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .floating-action {
        width: 3rem;
        height: 3rem;
        bottom: 1.5rem;
        right: 1.5rem;
        font-size: 1rem;
    }

    .header-actions {
        flex-direction: column;
        gap: 0.5rem;
        align-items: stretch;
    }
}

@media (max-width: 480px) {
    .category-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .category-info {
        margin-left: 0;
    }

    .category-stats {
        grid-template-columns: 1fr;
    }

    .category-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
    transform: translateX(-100%);
    transition: transform 0.6s ease;
}

.stats-card:hover::before {
    transform: translateX(100%);
}

.stats-card:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 15px 40px rgba(255, 255, 255, 0.2);
}

/* Métriques Avancées */
.metrics-section {
    margin: 3rem 0;
}

.metric-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius);
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

.metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--metric-gradient, var(--primary-gradient));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.6s ease;
}

.metric-card:hover::before {
    transform: scaleX(1);
}

.metric-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-hover);
}

/* Progress Rings Fluides */
.progress-ring {
    transform: rotate(-90deg);
    filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));
}

.progress-ring-circle {
    stroke-dasharray: 251.2;
    stroke-dashoffset: 251.2;
    transition: stroke-dashoffset 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    stroke-linecap: round;
}

/* Category Cards Révolutionnaires */
.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 2rem;
    margin: 3rem 0;
}

.category-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius);
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-soft);
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--category-gradient, var(--primary-gradient));
    opacity: 0;
    transition: opacity 0.4s ease;
}

.category-card::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 0%, rgba(255,255,255,0.1) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
}

.category-card:hover::before,
.category-card:hover::after {
    opacity: 1;
}

.category-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: var(--shadow-hover);
}

/* Icônes Gradient Fluides */
.category-icon {
    position: relative;
    z-index: 2;
    transition: var(--transition-smooth);
}

.category-icon::before {
    content: '';
    position: absolute;
    top: -8px;
    left: -8px;
    right: -8px;
    bottom: -8px;
    background: inherit;
    border-radius: inherit;
    opacity: 0.3;
    z-index: -1;
    transition: var(--transition-smooth);
    filter: blur(8px);
}

.category-card:hover .category-icon::before {
    transform: scale(1.2);
    opacity: 0.5;
}

.category-card:hover .category-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Boutons Fluides */
.btn-fluid {
    background: var(--primary-gradient);
    border: none;
    border-radius: 16px;
    color: white;
    font-weight: 600;
    padding: 12px 24px;
    transition: var(--transition-smooth);
    position: relative;
    overflow: hidden;
}

.btn-fluid::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.btn-fluid:hover::before {
    left: 100%;
}

.btn-fluid:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

/* Floating Action Révolutionnaire */
.floating-action {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: var(--primary-gradient);
    border: none;
    color: white;
    font-size: 24px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    transition: var(--transition-smooth);
    z-index: 1000;
    cursor: pointer;
}

.floating-action::before {
    content: '';
    position: absolute;
    top: -5px;
    left: -5px;
    right: -5px;
    bottom: -5px;
    background: var(--primary-gradient);
    border-radius: 50%;
    opacity: 0.3;
    z-index: -1;
    transition: var(--transition-smooth);
    animation: pulse-ring 2s infinite;
}

@keyframes pulse-ring {
    0% { transform: scale(1); opacity: 0.3; }
    50% { transform: scale(1.1); opacity: 0.1; }
    100% { transform: scale(1); opacity: 0.3; }
}

.floating-action:hover {
    transform: scale(1.15) rotate(90deg);
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.6);
}

/* Animations Fluides */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.slide-in-up {
    animation: slideInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.fade-in-scale {
    animation: fadeInScale 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.animate-counter {
    font-variant-numeric: tabular-nums;
    font-weight: 700;
    background: var(--primary-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Modal Fluide */
.modal-content {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-hover);
}

/* Responsive Fluide */
@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .hero-section {
        margin: 1rem 0;
        border-radius: 16px;
    }

    .floating-action {
        width: 60px;
        height: 60px;
        bottom: 20px;
        right: 20px;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header Moderne avec Gradient et Actions -->
    <div class="row">
        <div class="col-12">
            <div class="dashboard-card text-white mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <div class="dashboard-icon me-3">
                                    <i class="fas fa-layer-group fa-2x"></i>
                                </div>
                                <div>
                                    <h1 class="text-white fw-bold mb-1" style="font-size: 2.2rem;">Gestion des Catégories</h1>
                                    <p class="text-white-75 mb-0">Organisation et gestion des catégories de formations</p>
                                </div>
                            </div>

                            <!-- Statistiques Rapides en Header -->
                            <div class="row g-3">
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-white fw-bold" style="font-size: 1.5rem;">{{ count($categories ?? []) }}</div>
                                        <div class="text-white-50 small">Total Catégories</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-success fw-bold" style="font-size: 1.5rem;">{{ $globalStats['active_categories'] ?? 0 }}</div>
                                        <div class="text-white-50 small">Actives</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-info fw-bold" style="font-size: 1.5rem;">{{ $globalStats['total_formations'] ?? 0 }}</div>
                                        <div class="text-white-50 small">Formations</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-primary fw-bold" style="font-size: 1.5rem;">{{ $globalStats['total_students'] ?? 0 }}</div>
                                        <div class="text-white-50 small">Étudiants</div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="dashboard-stat-mini p-2 rounded text-center" style="background: var(--dashboard-bg); border: 1px solid var(--dashboard-border);">
                                        <div class="text-warning fw-bold" style="font-size: 1.5rem;">{{ $globalStats['satisfaction_rate'] ?? 95 }}%</div>
                                        <div class="text-white-50 small">Satisfaction</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('admin.formations.categories.create') }}" class="btn btn-success btn-lg shadow-lg">
                                    <i class="fas fa-plus me-2"></i>Nouvelle Catégorie
                                </a>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-light" onclick="exportCategories()">
                                        <i class="fas fa-download me-1"></i>Exporter
                                    </button>
                                    <button type="button" class="btn btn-outline-light" onclick="refreshData()">
                                        <i class="fas fa-sync-alt me-1"></i>Actualiser
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Catégories - Style identique à admin/students -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 table-modern">
                <div class="card-body p-4">
                    <!-- Header avec Filtres et Actions -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-list text-primary" style="font-size: 1.5rem;"></i>
                            </div>
                            <div>
                                <h5 class="text-black fw-bold mb-1">Liste des Catégories</h5>
                                <p class="text-black-50 mb-0 small">{{ count($categories ?? []) }} catégorie(s) • Gestion et organisation</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Filtre par statut -->
                            <select class="form-select form-select-sm" id="statusFilter" style="width: 120px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: rgb(0, 0, 0);">
                                <option value="">Tous statuts</option>
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                            </select>
                            <!-- Recherche -->
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-sm" id="searchCategories" placeholder="Rechercher..." style="width: 200px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: rgb(0, 0, 0); padding-left: 35px;">
                                <i class="fas fa-search position-absolute" style="left: 10px; top: 50%; transform: translateY(-50%); color: rgba(27, 13, 13, 0.5);"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Table moderne avec fond bleu nuit foncé -->
                    <div class="table-responsive" style="border-radius: 16px; overflow: hidden; background: linear-gradient(135deg, #1a237e 0%, #283593 100%); box-shadow: 0 8px 32px rgba(26, 35, 126, 0.3);">
                        <table class="table table-dark mb-0" style="background: transparent; border: none; color: #ffffff !important;">
                            <thead style="background: rgba(0, 0, 0, 0.2); border-bottom: 2px solid rgba(255, 255, 255, 0.1);">
                                <tr>
                                    <th style="width: 60px; padding: 20px 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; border: none;" class="sortable" data-sort="id">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hashtag me-2" style="color: #64b5f6; font-size: 0.8rem;"></i>
                                            #
                                            <i class="fas fa-sort ms-1 sort-icon" style="color: rgba(255, 255, 255, 0.5); font-size: 0.7rem;"></i>
                                        </div>
                                    </th>
                                    <th style="padding: 20px 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; border: none;" class="sortable" data-sort="name">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-layer-group me-2" style="color: #64b5f6; font-size: 0.8rem;"></i>
                                            Catégorie
                                            <i class="fas fa-sort ms-1 sort-icon" style="color: rgba(255, 255, 255, 0.5); font-size: 0.7rem;"></i>
                                        </div>
                                    </th>

                                    <th style="width: 120px; padding: 20px 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; border: none;" class="sortable text-center" data-sort="formations">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-graduation-cap me-2" style="color: #64b5f6; font-size: 0.8rem;"></i>
                                            Formations
                                            <i class="fas fa-sort ms-1 sort-icon" style="color: rgba(255, 255, 255, 0.5); font-size: 0.7rem;"></i>
                                        </div>
                                    </th>
                                    <th style="width: 120px; padding: 20px 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; border: none;" class="sortable text-center" data-sort="students">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-users me-2" style="color: #64b5f6; font-size: 0.8rem;"></i>
                                            Étudiants
                                            <i class="fas fa-sort ms-1 sort-icon" style="color: rgba(255, 255, 255, 0.5); font-size: 0.7rem;"></i>
                                        </div>
                                    </th>
                                    <th style="width: 100px; padding: 20px 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; border: none;" class="sortable text-center" data-sort="status">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-toggle-on me-2" style="color: #64b5f6; font-size: 0.8rem;"></i>
                                            Statut
                                            <i class="fas fa-sort ms-1 sort-icon" style="color: rgba(255, 255, 255, 0.5); font-size: 0.7rem;"></i>
                                        </div>
                                    </th>
                                    <th style="width: 150px; padding: 20px 16px; color: #ffffff; font-weight: 600; font-size: 0.9rem; border: none;" class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-cogs me-2" style="color: #64b5f6; font-size: 0.8rem;"></i>
                                            Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories ?? [] as $index => $category)
                                <tr class="category-row" data-category-id="{{ $category['id'] ?? 0 }}" data-status="{{ $category['status'] ?? 'active' }}" data-name="{{ strtolower($category['name'] ?? 'catégorie') }}" style="border-bottom: 1px solid rgba(255, 255, 255, 0.08); transition: all 0.3s ease; background: rgba(255, 255, 255, 0.02);" onmouseover="this.style.background='rgba(255, 255, 255, 0.08)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.02)'; this.style.transform='translateY(0)'">
                                    <td style="padding: 18px 16px; border: none; vertical-align: middle;">
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon-wrapper me-3" style="width: 40px; height: 40px; background: rgba(100, 181, 246, 0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                                <i class="{{ $category['icon'] ?? 'fas fa-layer-group' }}" style="color: #64b5f6; font-size: 1.1rem;"></i>
                                            </div>
                                            <span class="fw-bold" style="color: #ffffff; font-size: 1rem;">{{ $index + 1 }}</span>
                                        </div>
                                    </td>
                                    <td style="padding: 18px 16px; border: none; vertical-align: middle;">
                                        <div class="category-info">
                                            <div class="fw-bold mb-1" style="color: #ffffff; font-size: 1rem;">{{ $category['name'] ?? 'Catégorie' }}</div>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="small" style="color: rgba(255, 255, 255, 0.6);">{{ $category['slug'] ?? 'categorie' }}</span>
                                                @if(isset($category['created_at']))
                                                    <span class="badge" style="background: rgba(100, 181, 246, 0.2); color: #64b5f6; font-size: 0.7rem; padding: 4px 8px; border-radius: 8px;">{{ \Carbon\Carbon::parse($category['created_at'])->format('d/m/Y') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center" style="padding: 18px 16px; border: none; vertical-align: middle;">
                                        <div class="stat-cell">
                                            <span class="badge d-flex align-items-center justify-content-center" style="min-width: 50px; background: rgba(76, 175, 80, 0.2); color: #4caf50; border: 1px solid rgba(76, 175, 80, 0.3); border-radius: 12px; padding: 8px 12px; font-weight: 600;">
                                                <i class="fas fa-graduation-cap me-1" style="font-size: 0.8rem;"></i>
                                                {{ $category['formations_count'] ?? 0 }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center" style="padding: 18px 16px; border: none; vertical-align: middle;">
                                        <div class="stat-cell">
                                            <span class="badge d-flex align-items-center justify-content-center" style="min-width: 50px; background: rgba(33, 150, 243, 0.2); color: #2196f3; border: 1px solid rgba(33, 150, 243, 0.3); border-radius: 12px; padding: 8px 12px; font-weight: 600;">
                                                <i class="fas fa-users me-1" style="font-size: 0.8rem;"></i>
                                                {{ $category['students_count'] ?? 0 }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center" style="padding: 18px 16px; border: none; vertical-align: middle;">
                                        <div class="status-cell">
                                            @if(($category['status'] ?? 'active') === 'active')
                                                <span class="badge d-flex align-items-center justify-content-center" style="min-width: 70px; background: rgba(76, 175, 80, 0.2); color: #4caf50; border: 1px solid rgba(76, 175, 80, 0.3); border-radius: 12px; padding: 8px 12px; font-weight: 600;">
                                                    <i class="fas fa-check-circle me-1" style="font-size: 0.8rem;"></i>
                                                    Actif
                                                </span>
                                            @else
                                                <span class="badge d-flex align-items-center justify-content-center" style="min-width: 70px; background: rgba(255, 152, 0, 0.2); color: #ff9800; border: 1px solid rgba(255, 152, 0, 0.3); border-radius: 12px; padding: 8px 12px; font-weight: 600;">
                                                    <i class="fas fa-pause-circle me-1" style="font-size: 0.8rem;"></i>
                                                    Inactif
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td style="padding: 18px 16px; border: none; vertical-align: middle;">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <button class="btn-action-modern btn-view" onclick="viewCategory({{ $category['id'] ?? 0 }})" title="Voir les détails" style="width: 36px; height: 36px; background: rgba(100, 181, 246, 0.15); color: #64b5f6; border: 1px solid rgba(100, 181, 246, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(100, 181, 246, 0.25)'; this.style.transform='scale(1.05)'" onmouseout="this.style.background='rgba(100, 181, 246, 0.15)'; this.style.transform='scale(1)'">
                                                <i class="fas fa-eye" style="font-size: 0.9rem;"></i>
                                            </button>
                                            <button class="btn-action-modern btn-edit" onclick="editCategory({{ $category['id'] ?? 0 }})" title="Modifier la catégorie" style="width: 36px; height: 36px; background: rgba(76, 175, 80, 0.15); color: #4caf50; border: 1px solid rgba(76, 175, 80, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(76, 175, 80, 0.25)'; this.style.transform='scale(1.05)'" onmouseout="this.style.background='rgba(76, 175, 80, 0.15)'; this.style.transform='scale(1)'">
                                                <i class="fas fa-edit" style="font-size: 0.9rem;"></i>
                                            </button>
                                            <button class="btn-action-modern btn-delete" onclick="deleteCategory({{ $category['id'] ?? 0 }})" title="Supprimer la catégorie" style="width: 36px; height: 36px; background: rgba(244, 67, 54, 0.15); color: #f44336; border: 1px solid rgba(244, 67, 54, 0.3); border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(244, 67, 54, 0.25)'; this.style.transform='scale(1.05)'" onmouseout="this.style.background='rgba(244, 67, 54, 0.15)'; this.style.transform='scale(1)'">
                                                <i class="fas fa-trash" style="font-size: 0.9rem;"></i>
                                            </button>
                                                <ul class="dropdown-menu dropdown-menu-dark" style="background: rgba(0,0,0,0.8); backdrop-filter: blur(10px);">
                                                    <li><a class="dropdown-item" href="#" onclick="duplicateCategory({{ $category['id'] ?? 0 }})"><i class="fas fa-copy me-2"></i>Dupliquer</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="toggleStatus({{ $category['id'] ?? 0 }})"><i class="fas fa-toggle-on me-2"></i>Changer statut</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCategory({{ $category['id'] ?? 0 }})"><i class="fas fa-trash me-2"></i>Supprimer</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="text-white-50">
                                            <i class="fas fa-layer-group fa-3x mb-3 opacity-50"></i>
                                            <h5 class="text-white">Aucune catégorie</h5>
                                            <p class="mb-3">Créez votre première catégorie pour organiser vos formations</p>
                                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                                                <i class="fas fa-plus me-2"></i>Nouvelle Catégorie
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>

@push('scripts')
<script>
// JavaScript Fluide Révolutionnaire - Catégories EVC
document.addEventListener('DOMContentLoaded', function() {
    initFluidAnimations();
    initProgressRings();
    initCounterAnimations();
    initParallaxEffects();
});

// Animations fluides au chargement
function initFluidAnimations() {
    // Animation séquentielle des éléments
    const elements = document.querySelectorAll('.fade-in-scale, .slide-in-up');
    elements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = el.classList.contains('fade-in-scale') ? 'scale(0.9)' : 'translateY(40px)';

        setTimeout(() => {
            el.style.transition = 'all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            el.style.opacity = '1';
            el.style.transform = 'scale(1) translateY(0)';
        }, index * 150);
    });
}

// Cercles de progression fluides
function initProgressRings() {
    setTimeout(() => {
        document.querySelectorAll('.progress-ring-circle').forEach((circle, index) => {
            const percentages = [70, 75, 80, 85];
            const percentage = percentages[index] || 70;
            const circumference = 2 * Math.PI * 31;
            const offset = circumference - (percentage / 100) * circumference;

            circle.style.transition = 'stroke-dashoffset 2s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            circle.style.strokeDashoffset = offset;
        });
    }, 800);
}

// Compteurs animés avec intersection observer
function initCounterAnimations() {
    const observerOptions = {
        threshold: 0.3,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                animateFluidCounter(counter, target);
                observer.unobserve(counter);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-counter').forEach(counter => {
        observer.observe(counter);
    });
}

// Effets parallax subtils
function initParallaxEffects() {
    let ticking = false;

    function updateParallax() {
        const scrolled = window.pageYOffset;
        const parallaxElements = document.querySelectorAll('.hero-section, .metric-card');

        parallaxElements.forEach((el, index) => {
            const speed = 0.5 + (index * 0.1);
            const yPos = -(scrolled * speed / 10);
            el.style.transform = `translateY(${yPos}px)`;
        });

        ticking = false;
    }

    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }

    window.addEventListener('scroll', requestTick);
}

// Animation de compteur fluide
function animateFluidCounter(element, target) {
    let current = 0;
    const duration = 2000;
    const startTime = performance.now();

    function updateCounter(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function pour une animation fluide
        const easeOutCubic = 1 - Math.pow(1 - progress, 3);
        current = Math.floor(target * easeOutCubic);

        element.textContent = current;

        if (progress < 1) {
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target;
        }
    }

    requestAnimationFrame(updateCounter);
}

// Fonctions d'actions avec feedback fluide
function viewCategory(id) {
    showFluidNotification(`Consultation de la catégorie #${id}`, 'info', 'fas fa-eye');
    // Redirection vers la page de détails
    setTimeout(() => {
        window.location.href = `/evc/app/admin/formations/categories/${id}`;
    }, 500);
}

function editCategory(id) {
    showFluidNotification(`Édition de la catégorie #${id}`, 'warning', 'fas fa-edit');
    // Redirection vers la page d'édition
    setTimeout(() => {
        window.location.href = `/evc/app/admin/formations/categories/${id}/edit`;
    }, 500);
}

function viewFormations(id) {
    showFluidNotification(`Formations de la catégorie #${id}`, 'info', 'fas fa-list');
}

function duplicateCategory(id) {
    showFluidNotification(`Duplication de la catégorie #${id}`, 'info', 'fas fa-copy');
}

function deleteCategory(id) {
    // Confirmation de suppression
    if (confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action est irréversible.')) {
        // Afficher un loading
        showFluidNotification('Suppression en cours...', 'info', 'fas fa-spinner fa-spin');
        
        // Appel AJAX pour supprimer la catégorie
        fetch(`/evc/app/admin/formations/categories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFluidNotification(data.message, 'success', 'fas fa-check-circle');
                // Recharger la page après 1.5 secondes
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                showFluidNotification(data.message, 'error', 'fas fa-exclamation-triangle');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showFluidNotification('Une erreur est survenue lors de la suppression.', 'error', 'fas fa-times-circle');
        });
    }
}

// Système de notifications fluides
function showFluidNotification(message, type = 'info', icon = 'fas fa-info-circle') {
    const colors = {
        info: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
        success: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
        warning: 'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
        error: 'linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%)'
    };

    const notification = document.createElement('div');
    notification.className = 'position-fixed';
    notification.style.cssText = `
        top: 30px; right: 30px; z-index: 9999;
        background: ${colors[type]};
        color: white;
        padding: 20px 25px;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        backdrop-filter: blur(20px);
        min-width: 350px;
        transform: translateX(400px);
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    `;

    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="${icon} me-3 fs-5"></i>
            <div class="flex-grow-1">
                <div class="fw-bold">${message}</div>
            </div>
            <button class="btn btn-link text-white p-0 ms-3" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    // Animation d'entrée
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);

    // Auto-suppression
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 500);
    }, 4000);
}

// Modal de confirmation fluide
function createFluidConfirmModal(title, message, onConfirm) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                border: none;
                border-radius: 24px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            ">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">${title}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="text-muted mb-0">${message}</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius: 12px;">Annuler</button>
                    <button type="button" class="btn btn-danger confirm-btn" style="border-radius: 12px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%); border: none;">Confirmer</button>
                </div>
            </div>
        </div>
    `;

    modal.querySelector('.confirm-btn').addEventListener('click', () => {
        onConfirm();
        bootstrap.Modal.getInstance(modal).hide();
    });

    return modal;
}

// Gestion du floating action button
document.addEventListener('scroll', function() {
    const fab = document.querySelector('.floating-action');
    if (fab) {
        const scrolled = window.pageYOffset;
        const opacity = Math.min(scrolled / 300, 1);
        fab.style.opacity = opacity;
        fab.style.transform = `scale(${0.8 + (opacity * 0.2)}) rotate(${scrolled * 0.1}deg)`;
    }
});
</script>
@endpush
</div>
@endsection

@push('scripts')
<script>
class ProfessionalCategoriesInterface {
    constructor() {
        this.sortOrder = {};
        this.init();
    }

    init() {
        this.initIntersectionObserver();
        this.initCounterAnimations();
        this.initInteractiveEffects();
        this.initSlugGeneration();
        this.initModalAnimations();
        this.initResponsiveAdjustments();
        this.initTableOptimization();
    }

    // Nouvelle méthode pour l'optimisation du tableau
    initTableOptimization() {
        this.initSorting();
        this.initFiltering();
        this.initSearch();
        this.initTableActions();
    }

    // Système de tri des colonnes
    initSorting() {
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', (e) => {
                const sortKey = header.dataset.sort;
                const currentOrder = this.sortOrder[sortKey] || 'none';

                // Reset tous les autres headers
                document.querySelectorAll('.sortable').forEach(h => {
                    h.classList.remove('sorted-asc', 'sorted-desc');
                });

                // Déterminer le nouvel ordre
                let newOrder;
                if (currentOrder === 'none' || currentOrder === 'desc') {
                    newOrder = 'asc';
                    header.classList.add('sorted-asc');
                } else {
                    newOrder = 'desc';
                    header.classList.add('sorted-desc');
                }

                this.sortOrder = { [sortKey]: newOrder };
                this.sortTable(sortKey, newOrder);
            });
        });
    }

    // Tri du tableau
    sortTable(sortKey, order) {
        const tbody = document.querySelector('.table-modern tbody');
        const rows = Array.from(tbody.querySelectorAll('.category-row'));

        rows.sort((a, b) => {
            let aVal, bVal;

            switch(sortKey) {
                case 'id':
                    aVal = parseInt(a.dataset.categoryId);
                    bVal = parseInt(b.dataset.categoryId);
                    break;
                case 'name':
                    aVal = a.dataset.name;
                    bVal = b.dataset.name;
                    break;
                case 'formations':
                    aVal = parseInt(a.querySelector('.badge-primary').textContent.trim());
                    bVal = parseInt(b.querySelector('.badge-primary').textContent.trim());
                    break;
                case 'students':
                    aVal = parseInt(a.querySelector('.badge-success').textContent.trim());
                    bVal = parseInt(b.querySelector('.badge-success').textContent.trim());
                    break;
                case 'status':
                    aVal = a.dataset.status;
                    bVal = b.dataset.status;
                    break;
                default:
                    return 0;
            }

            if (typeof aVal === 'string') {
                aVal = aVal.toLowerCase();
                bVal = bVal.toLowerCase();
            }

            if (order === 'asc') {
                return aVal < bVal ? -1 : aVal > bVal ? 1 : 0;
            } else {
                return aVal > bVal ? -1 : aVal < bVal ? 1 : 0;
            }
        });

        // Réorganiser les lignes avec animation
        rows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
            tbody.appendChild(row);
        });
    }

    // Filtrage par statut
    initFiltering() {
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.filterByStatus(e.target.value);
            });
        }
    }

    filterByStatus(status) {
        const rows = document.querySelectorAll('.category-row');

        rows.forEach(row => {
            const rowStatus = row.dataset.status;
            const shouldShow = !status || rowStatus === status;

            if (shouldShow) {
                row.classList.remove('hidden');
                row.classList.add('visible');
                row.style.display = '';
            } else {
                row.classList.add('hidden');
                row.classList.remove('visible');
                setTimeout(() => {
                    if (row.classList.contains('hidden')) {
                        row.style.display = 'none';
                    }
                }, 300);
            }
        });

        this.updateResultsCount();
    }

    // Recherche en temps réel
    initSearch() {
        const searchInput = document.getElementById('searchCategories');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.searchCategories(e.target.value);
                }, 300);
            });
        }
    }

    searchCategories(query) {
        const rows = document.querySelectorAll('.category-row');
        const searchTerm = query.toLowerCase().trim();

        rows.forEach(row => {
            const name = row.dataset.name;
            const description = row.querySelector('.description-cell span').textContent.toLowerCase();
            const slug = row.querySelector('.text-white-50').textContent.toLowerCase();

            const matches = !searchTerm ||
                           name.includes(searchTerm) ||
                           description.includes(searchTerm) ||
                           slug.includes(searchTerm);

            if (matches) {
                row.classList.remove('hidden');
                row.classList.add('visible');
                row.style.display = '';
            } else {
                row.classList.add('hidden');
                row.classList.remove('visible');
                setTimeout(() => {
                    if (row.classList.contains('hidden')) {
                        row.style.display = 'none';
                    }
                }, 300);
            }
        });

        this.updateResultsCount();
    }

    // Mise à jour du compteur de résultats
    updateResultsCount() {
        const visibleRows = document.querySelectorAll('.category-row:not(.hidden)').length;
        const totalRows = document.querySelectorAll('.category-row').length;
        const countElement = document.querySelector('.text-white-50.small');

        if (countElement) {
            if (visibleRows === totalRows) {
                countElement.textContent = `${totalRows} catégorie(s) • Gestion et organisation`;
            } else {
                countElement.textContent = `${visibleRows} sur ${totalRows} catégorie(s) • Résultats filtrés`;
            }
        }
    }

    // Actions du tableau
    initTableActions() {
        // Placeholder pour les actions futures
        console.log('Actions du tableau initialisées');
    }
}

// Animations Ultra-Fluides
function initAnimations() {
    // Intersection Observer pour animations d'entrée
    const animationObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0) scale(1)';
                }, index * 100);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });

    // Observer les éléments animés
    document.querySelectorAll('.animate-fade-in-up, .metric-card, .category-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px) scale(0.95)';
        el.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
        animationObserver.observe(el);
    });

    // Animation staggered pour les cartes
    document.querySelectorAll('.category-card').forEach((card, index) => {
        card.style.animationDelay = `${index * 150}ms`;
    });
}

// Compteurs Animés Professionnels
function initCounters() {
    const counterElements = document.querySelectorAll('.animate-counter[data-target]');

    const animateCounter = (element) => {
        const target = parseInt(element.dataset.target);
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString();
            }
        };

        updateCounter();
    };

    // Observer pour déclencher les compteurs
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                entry.target.dataset.animated = 'true';
                setTimeout(() => animateCounter(entry.target), 300);
            }
        });
    }, { threshold: 0.5 });

    counterElements.forEach(el => counterObserver.observe(el));
}

// Interactions Modernes
function initInteractions() {
        // Hover effects avancés pour les cartes métriques
        document.querySelectorAll('.metric-card').forEach(card => {
            card.addEventListener('mouseenter', (e) => {
                e.currentTarget.style.transform = 'translateY(-6px) scale(1.02)';
                e.currentTarget.style.boxShadow = 'var(--shadow-2xl)';
            });

            card.addEventListener('mouseleave', (e) => {
                e.currentTarget.style.transform = 'translateY(0) scale(1)';
                e.currentTarget.style.boxShadow = 'var(--shadow-xs)';
            });
        });

        // Interactions fluides pour les cartes catégories
        document.querySelectorAll('.category-card').forEach(card => {
            card.addEventListener('mouseenter', (e) => {
                const icon = e.currentTarget.querySelector('.category-icon-wrapper');
                const name = e.currentTarget.querySelector('.category-name');
                const actions = e.currentTarget.querySelector('.category-actions');

                if (icon) icon.style.transform = 'scale(1.1) rotate(-5deg)';
                if (name) name.style.transform = 'translateX(4px)';
                if (actions) {
                    actions.style.opacity = '1';
                    actions.style.transform = 'translateX(0)';
                }
            });

            card.addEventListener('mouseleave', (e) => {
                const icon = e.currentTarget.querySelector('.category-icon-wrapper');
                const name = e.currentTarget.querySelector('.category-name');
                const actions = e.currentTarget.querySelector('.category-actions');

                if (icon) icon.style.transform = 'scale(1) rotate(0deg)';
                if (name) name.style.transform = 'translateX(0)';
                if (actions) {
                    actions.style.opacity = '0';
                    actions.style.transform = 'translateX(10px)';
                }
            });
        });

        // Boutons avec effet shimmer
        document.querySelectorAll('.btn-primary-modern').forEach(btn => {
            btn.addEventListener('mouseenter', (e) => {
                e.currentTarget.style.transform = 'translateY(-2px) scale(1.02)';
            });

            btn.addEventListener('mouseleave', (e) => {
                e.currentTarget.style.transform = 'translateY(0) scale(1)';
            });
        });
    }

    // Gestion Modales Professionnelle
    initModals() {
        // Auto-génération du slug
        const nameInput = document.querySelector('input[name="name"]');
        const slugInput = document.querySelector('input[name="slug"]');

        if (nameInput && slugInput) {
            nameInput.addEventListener('input', (e) => {
                const slug = this.generateSlug(e.target.value);
                slugInput.value = slug;
            });
        }

        // Animation d'ouverture modale
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('show.bs.modal', (e) => {
                const content = e.target.querySelector('.modal-content');
                if (content) {
                    content.style.transform = 'scale(0.9) translateY(-50px)';
                    content.style.opacity = '0';

                    setTimeout(() => {
                        content.style.transform = 'scale(1) translateY(0)';
                        content.style.opacity = '1';
                    }, 100);
                }
            });
        });
    }

// Responsive Intelligent
function initResponsive() {
    const handleResize = () => {
        const isMobile = window.innerWidth <= 768;
        const isTablet = window.innerWidth <= 1024;

        // Ajustements dynamiques pour mobile
        if (isMobile) {
            document.querySelectorAll('.category-stats').forEach(stats => {
                stats.style.gridTemplateColumns = 'repeat(2, 1fr)';
            });
        } else {
            document.querySelectorAll('.category-stats').forEach(stats => {
                stats.style.gridTemplateColumns = 'repeat(4, 1fr)';
            });
        }
    };

    window.addEventListener('resize', debounce(handleResize, 250));
    handleResize(); // Initial call
}

// Utilitaires
function generateSlug(text) {
    return text
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}

function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Notifications Modernes
function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            border-radius: var(--radius-xl);
            backdrop-filter: blur(10px);
            animation: slideInRight 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        `;
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
}

// Actions Catégories
function editCategory(id) {
    console.log('Édition catégorie:', id);
    // Logique d'édition
}

function viewCategory(id) {
    console.log('Voir catégorie:', id);
    // Logique de visualisation
}

function viewFormations(categoryId) {
    console.log('Voir formations de la catégorie:', categoryId);
    // Redirection vers formations filtrées
    window.location.href = `/admin/formations?category=${categoryId}`;
}

function saveCategory() {
    const form = document.getElementById('categoryForm');
    const formData = new FormData(form);

    // Simulation sauvegarde
    interface.showNotification('Catégorie créée avec succès!', 'success');

    // Fermer la modale
    const modal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
    if (modal) modal.hide();

    // Reset form
    form.reset();
}

// Initialisation globale au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser toutes les fonctionnalités
    initAnimations();
    initCounters();
    initInteractions();
    initResponsive();

    console.log('🚀 Interface catégories initialisée avec succès !');
});

// Styles CSS pour animations supplémentaires
const additionalStyles = document.createElement('style');
additionalStyles.textContent = `
@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}

.modal-content {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.category-card {
    will-change: transform;
}

.metric-card {
    will-change: transform, box-shadow;
}

/* Styles pour le tableau avec fond bleu nuit foncé */
.table-dark {
    --bs-table-bg: transparent !important;
    --bs-table-striped-bg: rgba(255, 255, 255, 0.02) !important;
    --bs-table-striped-color: #ffffff !important;
    --bs-table-active-bg: rgba(255, 255, 255, 0.05) !important;
    --bs-table-active-color: #ffffff !important;
    --bs-table-hover-bg: rgba(255, 255, 255, 0.08) !important;
    --bs-table-hover-color: #ffffff !important;
    color: #ffffff !important;
    background: transparent !important;
}

/* Force tous les textes à être blancs dans le tableau sombre */
.table-dark,
.table-dark th,
.table-dark td,
.table-dark thead th,
.table-dark tbody td {
    color: #ffffff !important;
    background: transparent !important;
    border-color: rgba(255, 255, 255, 0.1) !important;
}

/* Force la couleur blanche sur tous les éléments enfants du tableau */
.table-dark * {
    color: #ffffff !important;
}

.table-dark .fw-bold,
.table-dark .category-info,
.table-dark .description-cell,
.table-dark .stat-cell,
.table-dark .status-cell,
.table-dark span,
.table-dark div {
    color: #ffffff !important;
}

.table-dark .small {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* Styles pour les badges dans le tableau sombre */
.table-dark .badge {
    color: inherit !important;
}

/* Hover effects pour le tableau sombre */
.table-dark tbody tr:hover {
    background: rgba(255, 255, 255, 0.08) !important;
    color: #ffffff !important;
}

.table-dark tbody tr:hover * {
    color: #ffffff !important;
}

.table-dark-modern td,
.table-dark-modern th {
    background: transparent !important;
    color: #ffffff !important;
    border-color: rgba(255, 255, 255, 0.08) !important;
}

.table-dark-modern tbody tr {
    background: rgba(255, 255, 255, 0.02) !important;
}

.table-dark-modern tbody tr:hover {
    background: rgba(255, 255, 255, 0.08) !important;
}

.table-dark-modern th {
    border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.2);
    position: relative;
}

.table-dark-modern th.sortable:hover {
    background: rgba(255, 255, 255, 0.05);
    cursor: pointer;
}

.table-dark-modern th .sort-icon {
    transition: all 0.3s ease;
    opacity: 0.5;
}

.table-dark-modern th.sortable:hover .sort-icon {
    opacity: 1;
    transform: scale(1.1);
}

.table-dark-modern tbody tr {
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.table-dark-modern tbody tr:hover {
    background: rgba(255, 255, 255, 0.08) !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

/* Force la couleur blanche sur tous les textes du tableau */
.table-dark-modern * {
    color: inherit !important;
}

.table-dark-modern .fw-bold,
.table-dark-modern .category-info,
.table-dark-modern .description-cell,
.table-dark-modern .stat-cell,
.table-dark-modern .status-cell {
    color: #ffffff !important;
}

.table-dark-modern .small,
.table-dark-modern .text-muted {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* Styles spécifiques pour les badges dans le tableau sombre */
.table-dark-modern .badge {
    color: inherit !important;
}

/* Correction des couleurs pour les éléments avec fond transparent */
.table-dark-modern tbody tr {
    color: #ffffff !important;
}

.table-dark-modern tbody tr * {
    color: inherit !important;
}

.btn-action-modern {
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    backdrop-filter: blur(10px);
}

.btn-action-modern:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.category-icon-wrapper {
    transition: all 0.3s ease;
}

.category-row:hover .category-icon-wrapper {
    transform: scale(1.05) rotate(5deg);
}

/* Animation pour les badges */
.badge {
    transition: all 0.3s ease;
}

.category-row:hover .badge {
    transform: scale(1.05);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Effet de glassmorphism pour le conteneur du tableau */
.table-responsive {
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Animation d'entrée pour les lignes du tableau */
.category-row {
    animation: fadeInUp 0.6s ease-out;
    animation-fill-mode: both;
}

.category-row:nth-child(1) { animation-delay: 0.1s; }
.category-row:nth-child(2) { animation-delay: 0.2s; }
.category-row:nth-child(3) { animation-delay: 0.3s; }
.category-row:nth-child(4) { animation-delay: 0.4s; }
.category-row:nth-child(5) { animation-delay: 0.5s; }

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

/* CSS global simple pour forcer la visibilité du texte */
.table-responsive .table {
    color: white !important;
}

.table-responsive .table * {
    color: white !important;
}

.table-responsive .table .badge {
    color: inherit !important;
}

.table-responsive .table .small {
    color: rgba(255, 255, 255, 0.8) !important;
}
`;
document.head.appendChild(additionalStyles);
</script>
@endpush
