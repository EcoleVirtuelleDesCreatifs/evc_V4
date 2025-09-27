@props([
    'documents' => [],
    'pagination' => [],
    'tableId' => 'documents-table',
    'pageParam' => 'page',
    'studentId' => null,
    'title' => 'Documents',
    'icon' => 'fas fa-file-alt'
])

<style>
/* ✨ INTERFACE LIGHT & CLEAN - Variables CSS Épurées */
:root {
    --clean-primary: #667eea;
    --clean-secondary: #f093fb;
    --clean-accent: #4facfe;
    --clean-success: #43e97b;
    --clean-warning: #fa709a;
    --clean-info: #38bdf8;
    --clean-bg: rgba(255, 255, 255, 0.95);
    --clean-border: rgba(102, 126, 234, 0.15);
    --clean-text: #374151;
    --clean-text-light: #6b7280;
    --clean-hover: rgba(102, 126, 234, 0.05);
}

/* ✨ Animations Fluides & Épurées */
@keyframes cleanSlideIn {
    0% {
        transform: translateY(20px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes cleanFadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

/* Styles pour les boutons clean */
.clean-btn {
    position: relative;
    border: none;
    border-radius: 6px;
    padding: 6px 12px;
    color: white;
    font-weight: 500;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.clean-btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

.clean-btn-view {
    background: var(--clean-primary);
}

.clean-btn-download {
    background: var(--clean-info);
}

.clean-btn-validate {
    background: var(--clean-success);
}

.clean-btn-delete {
    background: var(--clean-secondary);
}
</style>

<div class="clean-container mb-4" id="{{ $tableId }}-container">
    <div class="p-4">
        <!-- En-tête clean -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="clean-icon me-3" style="color: var(--clean-primary); font-size: 1.2rem;">
                    <i class="{{ $icon }}"></i>
                </div>
                <h5 class="clean-title mb-0" style="color: var(--clean-text); font-weight: 600; font-size: 1.5rem;">
                    📄 {{ $title }}
                    @if(isset($pagination['total_documents']) && $pagination['total_documents'] > 0)
                        <span class="badge" style="background: var(--clean-primary); color: white; margin-left: 8px; font-size: 0.7rem;">{{ $pagination['total_documents'] }}</span>
                    @endif
                </h5>
            </div>

        </div>

        <!-- Contenu des documents -->
        <div id="{{ $tableId }}-content">
            @if(!empty($documents) && count($documents) > 0)
                @foreach($documents as $sessionName => $sessionDocuments)
                    <!-- En-tête de session clean -->
                    <div class="clean-session-header mb-3">
                        <div class="d-flex align-items-center justify-content-between p-3 rounded"
                             style="background: white; border: 1px solid var(--clean-border);">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-alt me-2" style="color: var(--clean-primary);"></i>
                                <h6 class="mb-0" style="color: var(--clean-text); font-weight: 600;">{{ $sessionName }}</h6>
                            </div>
                            <span class="badge" style="background: var(--clean-primary); color: white; font-size: 0.7rem;">{{ count($sessionDocuments) }} document(s)</span>
                        </div>
                    </div>

                    <!-- Tableau clean des documents pour cette session -->
                    <div class="table-responsive mb-4">
                        <table class="table" style="background: white; border: 1px solid var(--clean-border); border-radius: 8px; overflow: hidden;">
                            <thead style="background: rgba(102, 126, 234, 0.05);">
                                <tr>
                                    <th scope="col" style="color: var(--clean-text); font-weight: 600; font-size: 0.9rem; padding: 12px;">#</th>
                                    <th scope="col" style="color: var(--clean-text); font-weight: 600; font-size: 0.9rem; padding: 12px;">Nom du Fichier</th>
                                    <th scope="col" style="color: var(--clean-text); font-weight: 600; font-size: 0.9rem; padding: 12px;">Type & Source</th>
                                    <th scope="col" style="color: var(--clean-text); font-weight: 600; font-size: 0.9rem; padding: 12px;">Statut</th>
                                    <th scope="col" style="color: var(--clean-text); font-weight: 600; font-size: 0.9rem; padding: 12px;">Date</th>
                                    <th scope="col" class="text-center" style="color: var(--clean-text); font-weight: 600; font-size: 0.9rem; padding: 12px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessionDocuments as $index => $document)
                                    <tr id="document-row-{{ $document->id }}" style="border-bottom: 1px solid var(--clean-border);" onmouseover="this.style.backgroundColor='var(--clean-hover)'" onmouseout="this.style.backgroundColor='white'">
                                        <td style="color: var(--clean-text); padding: 12px;">{{ $index + 1 }}</td>
                                        <td style="padding: 12px;">
                                            <div class="d-flex align-items-center">
                                                <div class="me-2">
                                                    @if(Str::contains($document->mime_type ?? '', 'image'))
                                                        <i class="fas fa-image" style="color: var(--clean-info);"></i>
                                                    @elseif(Str::contains($document->mime_type ?? '', 'pdf'))
                                                        <i class="fas fa-file-pdf" style="color: var(--clean-secondary);"></i>
                                                    @elseif(Str::contains($document->mime_type ?? '', 'word'))
                                                        <i class="fas fa-file-word" style="color: var(--clean-primary);"></i>
                                                    @else
                                                        <i class="fas fa-file" style="color: var(--clean-text-light);"></i>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-truncate" style="max-width: 200px; color: var(--clean-text); font-size: 0.9rem;">
                                                        {{ $document->original_name ?? 'Document' }}
                                                    </div>
                                                    <small style="color: var(--clean-text-light); font-size: 0.8rem;">
                                                        {{ $document->stored_name ?? $document->filename ?? '' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 12px;">
                                            <div class="d-flex flex-column gap-1">
                                                <span class="badge" style="background: var(--clean-text-light); color: white; font-size: 0.7rem;">
                                                    {{ $document->document_type ?? 'Fichier' }}
                                                </span>
                                                <span class="badge" style="background: var(--clean-info); color: white; font-size: 0.7rem;">
                                                    <i class="fas fa-{{ $document->source === 'CVThèque' ? 'briefcase' : ($document->source === 'Projet Design' ? 'palette' : ($document->source === 'Projet Laravel' ? 'code' : 'folder')) }} me-1"></i>
                                                    {{ $document->source ?? 'Document' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td style="padding: 12px;">
                                            @php
                                                $status = $document->status ?? 'pending';
                                                $statusConfig = [
                                                    'approved' => ['color' => 'var(--clean-success)', 'icon' => 'check', 'text' => 'Approuvé'],
                                                    'rejected' => ['color' => 'var(--clean-secondary)', 'icon' => 'times', 'text' => 'Rejeté'],
                                                    'pending' => ['color' => 'var(--clean-warning)', 'icon' => 'clock', 'text' => 'En attente'],
                                                    'default' => ['color' => 'var(--clean-text-light)', 'icon' => 'question', 'text' => 'Non défini']
                                                ];
                                                $config = $statusConfig[$status] ?? $statusConfig['default'];
                                            @endphp
                                            <span class="badge" style="background: {{ $config['color'] }}; color: white; font-size: 0.75rem;">
                                                <i class="fas fa-{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                                            </span>
                                        </td>
                                        <td style="padding: 12px;">
                                            <div style="color: var(--clean-text); font-size: 0.9rem;">
                                                {{ $document->formatted_date ?? \Carbon\Carbon::parse($document->created_at)->format('d/m/Y') }}
                                            </div>
                                            <small style="color: var(--clean-text-light); font-size: 0.8rem;">
                                                {{ $document->formatted_time ?? \Carbon\Carbon::parse($document->created_at)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td class="text-center" style="padding: 12px;">
                                            <!-- Interface Clean Actions Documents -->
                                            <div class="clean-actions-hub d-flex justify-content-center gap-1">
                                                <!-- Voir Document -->
                                                <button type="button"
                                                        class="clean-btn clean-btn-view"
                                                        onclick="viewDocument({{ $document->id }})"
                                                        title="👁️ Prévisualiser le contenu du document"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Valider Document -->
                                                <button type="button"
                                                        class="clean-btn clean-btn-validate"
                                                        onclick="validateDocument({{ $document->id }})"
                                                        title="✅ Approuver et valider ce document"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-check"></i>
                                                </button>

                                                <!-- Supprimer Document -->
                                                <button type="button"
                                                        class="clean-btn clean-btn-delete"
                                                        onclick="deleteDocument({{ $document->id }})"
                                                        title="🗑️ Supprimer définitivement ce document"
                                                        data-bs-toggle="tooltip">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endforeach

                <!-- Contrôles de pagination -->
                @if(isset($pagination['total_pages']) && $pagination['total_pages'] > 1)
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-white-50">
                            Page {{ $pagination['current_page'] }} sur {{ $pagination['total_pages'] }}
                            ({{ $pagination['total_documents'] }} document(s) au total)
                        </div>
                        <nav aria-label="Pagination des documents">
                            <ul class="pagination pagination-sm mb-0">
                                <!-- Bouton Précédent -->
                                @if($pagination['has_previous'])
                                    <li class="page-item">
                                        <button class="page-link bg-dark border-secondary text-light"
                                                onclick="loadDocumentsPage({{ $pagination['current_page'] - 1 }}, '{{ $pageParam }}', '{{ $tableId }}', {{ $studentId }})">
                                            <i class="fas fa-chevron-left"></i>
                                        </button>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link bg-secondary border-secondary text-muted">
                                            <i class="fas fa-chevron-left"></i>
                                        </span>
                                    </li>
                                @endif

                                <!-- Numéros de page -->
                                @for($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++)
                                    @if($i == $pagination['current_page'])
                                        <li class="page-item active">
                                            <span class="page-link bg-primary border-primary">{{ $i }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <button class="page-link bg-dark border-secondary text-light"
                                                    onclick="loadDocumentsPage({{ $i }}, '{{ $pageParam }}', '{{ $tableId }}', {{ $studentId }})">
                                                {{ $i }}
                                            </button>
                                        </li>
                                    @endif
                                @endfor

                                <!-- Bouton Suivant -->
                                @if($pagination['has_next'])
                                    <li class="page-item">
                                        <button class="page-link bg-dark border-secondary text-light"
                                                onclick="loadDocumentsPage({{ $pagination['current_page'] + 1 }}, '{{ $pageParam }}', '{{ $tableId }}', {{ $studentId }})">
                                            <i class="fas fa-chevron-right"></i>
                                        </button>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link bg-secondary border-secondary text-muted">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            @else
                <!-- État vide -->
                <div class="text-center py-5">
                    <div class="text-white-50">
                        <i class="{{ $icon }} fa-3x mb-3 opacity-50"></i>
                        <h6 class="text-white-50">Aucun document trouvé</h6>
                        <p class="mb-0">Cet étudiant n'a encore ajouté aucun document de ce type.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Overlay de chargement -->
        <div id="{{ $tableId }}-loading" class="position-absolute top-0 start-0 w-100 h-100 d-none"
             style="background: rgba(0,0,0,0.7); z-index: 10;">
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="text-center text-white">
                    <div class="spinner-border mb-3" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <div>Chargement des documents...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS Interface Révolutionnaire Documents -->
<style>
/* Variables CSS pour l'interface révolutionnaire documents */
:root {
    --revolutionary-primary: #003366;
    --revolutionary-secondary: #3399ff;
    --revolutionary-accent: #ff6633;
    --revolutionary-warning: #FF9900;
    --revolutionary-success: #28a745;
    --revolutionary-danger: #dc3545;
    --revolutionary-dark: #1a1a1a;
    --revolutionary-light: #ffffff;
}

/* Hub d'actions révolutionnaire pour documents */
.revolutionary-actions-hub {
    position: relative;
    z-index: 1;
}

/* Boutons révolutionnaires documents */
.btn-revolutionary {
    position: relative;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.btn-revolutionary:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
    color: white;
}

.btn-revolutionary:active {
    transform: translateY(0) scale(0.95);
}

/* Couleurs spécifiques documents */
.btn-revolutionary-view {
    background: linear-gradient(135deg, var(--revolutionary-secondary), var(--revolutionary-primary));
}

.btn-revolutionary-download {
    background: linear-gradient(135deg, var(--revolutionary-success), #20c997);
}

.btn-revolutionary-approve {
    background: linear-gradient(135deg, var(--revolutionary-warning), #ffc107);
}

.btn-revolutionary-more {
    background: linear-gradient(135deg, #6c757d, #495057);
}

/* Loader dans les boutons */
.btn-revolutionary-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.btn-revolutionary.loading .btn-revolutionary-loader {
    opacity: 1;
}

.btn-revolutionary.loading i {
    opacity: 0;
}

/* Menu dropdown révolutionnaire */
.revolutionary-dropdown {
    border: 1px solid rgba(255,255,255,0.1);
    background: rgba(26, 26, 26, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
    padding: 8px 0;
    min-width: 180px;
}

.revolutionary-dropdown .dropdown-item {
    padding: 8px 16px;
    color: #ffffff;
    transition: all 0.2s ease;
    border-radius: 8px;
    margin: 2px 8px;
}

.revolutionary-dropdown .dropdown-item:hover {
    background: linear-gradient(135deg, var(--revolutionary-secondary), var(--revolutionary-primary));
    color: white;
    transform: translateX(4px);
}

.revolutionary-dropdown .dropdown-item.text-warning:hover {
    background: linear-gradient(135deg, var(--revolutionary-warning), #ffc107);
}

.revolutionary-dropdown .dropdown-item.text-danger:hover {
    background: linear-gradient(135deg, var(--revolutionary-danger), #c82333);
}

/* Animations d'entrée */
@keyframes revolutionaryFadeIn {
    from {
        opacity: 0;
        transform: translateY(10px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.revolutionary-actions-hub {
    animation: revolutionaryFadeIn 0.5s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .btn-revolutionary {
        width: 32px;
        height: 32px;
        font-size: 12px;
    }

    .revolutionary-actions-hub {
        gap: 0.25rem !important;
    }
}

/* 🎨 Tooltips Personnalisés Améliorés pour Documents */
.tooltip {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    z-index: 1080;
}

.tooltip .tooltip-inner {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 13px;
    font-weight: 500;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    backdrop-filter: blur(10px);
    max-width: 280px;
    text-align: center;
    line-height: 1.4;
}

.tooltip.bs-tooltip-top .tooltip-arrow::before {
    border-top-color: #1a1a1a;
}

.tooltip.bs-tooltip-bottom .tooltip-arrow::before {
    border-bottom-color: #1a1a1a;
}

.tooltip.bs-tooltip-start .tooltip-arrow::before {
    border-left-color: #1a1a1a;
}

.tooltip.bs-tooltip-end .tooltip-arrow::before {
    border-right-color: #1a1a1a;
}

/* Animation d'apparition des tooltips */
.tooltip {
    opacity: 0;
    transition: opacity 0.3s ease, transform 0.3s ease;
    transform: scale(0.9);
}

.tooltip.show {
    opacity: 1;
    transform: scale(1);
}
</style>

<!-- JavaScript Interface Révolutionnaire Documents -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation des tooltips pour les boutons documents
    const documentTooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    documentTooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });

    // Gestion des animations de chargement pour les boutons documents
    function setDocumentButtonLoading(button, isLoading) {
        if (isLoading) {
            button.classList.add('loading');
            button.disabled = true;
        } else {
            button.classList.remove('loading');
            button.disabled = false;
        }
    }

    // Toast notifications pour documents
    function showDocumentToast(message, type = 'success') {
        const toastContainer = document.querySelector('.toast-container') || createDocumentToastContainer();

        const toastId = 'toast-' + Date.now();
        const iconMap = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const colorMap = {
            success: 'text-success',
            error: 'text-danger',
            warning: 'text-warning',
            info: 'text-info'
        };

        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white bg-dark border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="${iconMap[type]} ${colorMap[type]} me-2"></i>
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
    }

    function createDocumentToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1080';
        document.body.appendChild(container);
        return container;
    }

    // Fonctions d'actions pour documents
    window.viewDocument = function(documentId) {
        const button = event.target.closest('.btn-revolutionary-view');
        if (button) setDocumentButtonLoading(button, true);

        console.log('Affichage du document:', documentId);
        showDocumentToast('Ouverture du document...', 'info');

        // Simulation d'action
        setTimeout(() => {
            if (button) setDocumentButtonLoading(button, false);
            showDocumentToast('Document ouvert avec succès!', 'success');
        }, 1000);
    };

    window.downloadDocument = function(documentId) {
        const button = event.target.closest('.btn-revolutionary-download');
        if (button) setDocumentButtonLoading(button, true);

        console.log('Téléchargement du document:', documentId);
        showDocumentToast('Téléchargement en cours...', 'info');

        // Simulation d'action
        setTimeout(() => {
            if (button) setDocumentButtonLoading(button, false);
            showDocumentToast('Document téléchargé!', 'success');
        }, 1500);
    };

    window.approveDocument = function(documentId) {
        const button = event.target.closest('.btn-revolutionary-approve');
        if (button) setDocumentButtonLoading(button, true);

        console.log('Approbation du document:', documentId);
        showDocumentToast('Approbation en cours...', 'info');

        // Simulation d'action
        setTimeout(() => {
            if (button) setDocumentButtonLoading(button, false);
            showDocumentToast('Document approuvé!', 'success');
        }, 1200);
    };

    window.shareDocument = function(documentId) {
        console.log('Partage du document:', documentId);
        showDocumentToast('Lien de partage copié!', 'success');
    };

    window.duplicateDocument = function(documentId) {
        console.log('Duplication du document:', documentId);
        showDocumentToast('Document dupliqué!', 'success');
    };

    window.exportDocument = function(documentId) {
        console.log('Export du document:', documentId);
        showDocumentToast('Export en cours...', 'info');
        setTimeout(() => showDocumentToast('Document exporté!', 'success'), 1000);
    };

    window.archiveDocument = function(documentId) {
        console.log('Archivage du document:', documentId);
        showDocumentToast('Document archivé!', 'warning');
    };

    window.deleteDocument = function(documentId) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce document ?')) {
            console.log('Suppression du document:', documentId);
            showDocumentToast('Document supprimé!', 'error');

            // Masquer la ligne du document
            const row = document.getElementById(`document-row-${documentId}`);
            if (row) {
                row.style.transition = 'opacity 0.3s ease';
                row.style.opacity = '0.5';
                setTimeout(() => row.remove(), 300);
            }
        }
    };

    console.log('Interface révolutionnaire documents initialisée ✨');
});
</script>
