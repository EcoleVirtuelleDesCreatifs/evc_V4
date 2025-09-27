/**
 * TP (Travaux Pratiques) JavaScript
 * Gestion des fonctionnalités interactives pour l'ajout de TP
 */

document.addEventListener('DOMContentLoaded', function() {
    // Configuration (ÉLARGIE POUR PDF)
    const config = {
        maxFileSize: 10 * 1024 * 1024, // 10MB en bytes
        allowedTypes: [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif',
            'application/zip',
            'application/x-rar-compressed',
            'application/x-zip-compressed',
            'text/plain',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            // Types MIME supplémentaires pour PDF
            'application/x-pdf',
            'application/acrobat',
            'applications/vnd.pdf',
            'text/pdf',
            'text/x-pdf',
            // Types vides ou génériques
            '',
            'application/octet-stream'
        ],
        allowedExtensions: ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'zip', 'rar', 'txt', 'ppt', 'pptx', 'xls', 'xlsx']
    };

    // Variables globales
    let selectedFiles = [];
    let dragCounter = 0;

    // Éléments DOM
    const fileInput = document.getElementById('files');
    const fileUploadZone = document.getElementById('fileUploadZone');
    const filesList = document.getElementById('filesList');
    const tpForm = document.getElementById('tp-form');
    const submitBtn = document.querySelector('.tp-submit-btn');

    // Initialisation
    init();

    function init() {
        if (fileInput && fileUploadZone) {
            setupFileUpload();
        }
        
        if (tpForm) {
            setupFormValidation();
        }
        
        setupSubmitButton();
        setupCharacterCounters();
        setupDynamicFileUpload();
        updateFileCounters();
    }

    /**
     * Configuration du système d'upload de fichiers
     */
    function setupFileUpload() {
        // Événements pour le drag & drop
        fileUploadZone.addEventListener('dragenter', handleDragEnter);
        fileUploadZone.addEventListener('dragover', handleDragOver);
        fileUploadZone.addEventListener('dragleave', handleDragLeave);
        fileUploadZone.addEventListener('drop', handleDrop);
        
        // Événement pour le clic sur la zone
        fileUploadZone.addEventListener('click', () => {
            fileInput.click();
        });
        
        // Événement pour la sélection de fichiers
        fileInput.addEventListener('change', handleFileSelect);
    }

    /**
     * Gestion des événements de drag & drop
     */
    function handleDragEnter(e) {
        e.preventDefault();
        e.stopPropagation();
        dragCounter++;
        fileUploadZone.classList.add('dragover');
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function handleDragLeave(e) {
        e.preventDefault();
        e.stopPropagation();
        dragCounter--;
        if (dragCounter === 0) {
            fileUploadZone.classList.remove('dragover');
        }
    }

    function handleDrop(e) {
        e.preventDefault();
        e.stopPropagation();
        dragCounter = 0;
        fileUploadZone.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files);
        processFiles(files);
    }

    /**
     * Gestion de la sélection de fichiers
     */
    function handleFileSelect(e) {
        const files = Array.from(e.target.files);
        processFiles(files);
    }

    /**
     * Traitement des fichiers sélectionnés
     */
    function processFiles(files) {
        files.forEach(file => {
            if (validateFile(file)) {
                addFileToList(file);
            }
        });
        
        updateFileInput();
        updateFilesList();
    }

    /**
     * Validation d'un fichier (ULTRA PERMISSIVE POUR PDF)
     */
    function validateFile(file) {
        console.log('🔍 Validation du fichier:', file.name, 'Type:', file.type, 'Taille:', file.size);
        
        // Vérification de la taille
        if (file.size > config.maxFileSize) {
            console.log('❌ Fichier trop volumineux:', file.size, '>', config.maxFileSize);
            showAlert(`Le fichier "${file.name}" est trop volumineux. Taille maximum : 10MB`, 'danger');
            return false;
        }

        // Obtenir l'extension du fichier
        const extension = file.name.split('.').pop().toLowerCase();
        console.log('📂 Extension détectée:', extension);
        
        // VALIDATION ULTRA PERMISSIVE : Accepter par extension d'abord
        const isExtensionAllowed = config.allowedExtensions.includes(extension);
        console.log('✅ Extension autorisée:', isExtensionAllowed, '(', extension, ')');
        
        // Si l'extension est autorisée, accepter directement le fichier
        if (isExtensionAllowed) {
            console.log('✅ Fichier accepté par extension:', extension);
            
            // Vérification des doublons seulement
            const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
            if (isDuplicate) {
                console.log('⚠️ Fichier déjà sélectionné');
                showAlert(`Le fichier "${file.name}" est déjà sélectionné.`, 'warning');
                return false;
            }
            
            console.log('✅ Fichier validé avec succès par extension:', file.name);
            return true;
        }
        
        // Fallback: vérification par type MIME seulement si extension non reconnue
        const isMimeTypeAllowed = config.allowedTypes.includes(file.type);
        console.log('✅ Type MIME autorisé:', isMimeTypeAllowed, '(', file.type, ')');
        
        if (isMimeTypeAllowed) {
            console.log('✅ Fichier accepté par type MIME:', file.type);
            
            // Vérification des doublons
            const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
            if (isDuplicate) {
                console.log('⚠️ Fichier déjà sélectionné');
                showAlert(`Le fichier "${file.name}" est déjà sélectionné.`, 'warning');
                return false;
            }
            
            console.log('✅ Fichier validé avec succès par type MIME:', file.name);
            return true;
        }
        
        // Rejet seulement si ni extension ni type MIME ne sont reconnus
        console.log('❌ Fichier rejeté: extension ET type MIME non reconnus');
        showAlert(`Le fichier "${file.name}" n'est pas d'un type autorisé. Types acceptés: PDF, DOC, DOCX, JPG, PNG, GIF, ZIP, RAR, TXT, PPT, XLS`, 'danger');
        return false;
    }

    /**
     * Ajout d'un fichier à la liste
     */
    function addFileToList(file) {
        selectedFiles.push(file);
    }

    /**
     * Mise à jour de l'input file
     */
    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(file => {
            dt.items.add(file);
        });
        fileInput.files = dt.files;
    }

    /**
     * Mise à jour de l'affichage de la liste des fichiers
     */
    function updateFilesList() {
        if (!filesList) return;

        filesList.innerHTML = '';

        if (selectedFiles.length === 0) {
            filesList.style.display = 'none';
            return;
        }

        filesList.style.display = 'block';

        selectedFiles.forEach((file, index) => {
            const fileItem = createFileItem(file, index);
            filesList.appendChild(fileItem);
        });
    }

    /**
     * Création d'un élément de fichier
     */
    function createFileItem(file, index) {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';
        
        const fileIcon = getFileIcon(file);
        const fileSize = formatFileSize(file.size);
        
        fileItem.innerHTML = `
            <div class="file-info">
                <div class="file-icon ${fileIcon.class}">
                    <i class="${fileIcon.icon}"></i>
                </div>
                <div class="file-details">
                    <h6>${file.name}</h6>
                    <small>${fileSize}</small>
                </div>
            </div>
            <button type="button" class="file-remove" onclick="removeFile(${index})" title="Supprimer le fichier">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        return fileItem;
    }

    /**
     * Obtention de l'icône appropriée pour un fichier
     */
    function getFileIcon(file) {
        const extension = file.name.split('.').pop().toLowerCase();
        
        switch (extension) {
            case 'pdf':
                return { class: 'pdf', icon: 'fas fa-file-pdf' };
            case 'doc':
            case 'docx':
                return { class: 'doc', icon: 'fas fa-file-word' };
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                return { class: 'image', icon: 'fas fa-file-image' };
            case 'zip':
            case 'rar':
                return { class: 'archive', icon: 'fas fa-file-archive' };
            case 'ppt':
            case 'pptx':
                return { class: 'doc', icon: 'fas fa-file-powerpoint' };
            case 'xls':
            case 'xlsx':
                return { class: 'doc', icon: 'fas fa-file-excel' };
            case 'txt':
                return { class: 'default', icon: 'fas fa-file-alt' };
            default:
                return { class: 'default', icon: 'fas fa-file' };
        }
    }

    /**
     * Formatage de la taille de fichier
     */
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    /**
     * Suppression d'un fichier (fonction globale)
     */
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        updateFilesList();
        
        showAlert('Fichier supprimé avec succès', 'success', 2000);
    };

    /**
     * Configuration de la validation du formulaire
     */
    function setupFormValidation() {
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        
        if (titleInput) {
            titleInput.addEventListener('blur', validateTitle);
            titleInput.addEventListener('input', clearValidationError);
        }
        
        if (descriptionInput) {
            descriptionInput.addEventListener('input', validateDescription);
        }
        
        // Validation avant soumission
        tpForm.addEventListener('submit', handleFormSubmit);
    }

    /**
     * Validation du titre
     */
    function validateTitle() {
        const titleInput = document.getElementById('title');
        const title = titleInput.value.trim();
        
        if (title.length === 0) {
            showFieldError(titleInput, 'Le titre est obligatoire');
            return false;
        }
        
        if (title.length > 255) {
            showFieldError(titleInput, 'Le titre ne peut pas dépasser 255 caractères');
            return false;
        }
        
        clearFieldError(titleInput);
        return true;
    }

    /**
     * Validation de la description
     */
    function validateDescription() {
        const descriptionInput = document.getElementById('description');
        const description = descriptionInput.value.trim();
        
        if (description.length > 2000) {
            showFieldError(descriptionInput, 'La description ne peut pas dépasser 2000 caractères');
            return false;
        }
        
        clearFieldError(descriptionInput);
        return true;
    }

    /**
     * Affichage d'une erreur de champ
     */
    function showFieldError(input, message) {
        input.classList.add('is-invalid');
        
        let feedback = input.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            input.parentNode.appendChild(feedback);
        }
        
        feedback.textContent = message;
    }

    /**
     * Suppression d'une erreur de champ
     */
    function clearFieldError(input) {
        input.classList.remove('is-invalid');
        const feedback = input.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }

    /**
     * Suppression des erreurs de validation
     */
    function clearValidationError(e) {
        clearFieldError(e.target);
    }

    /**
     * Validation des fichiers : au moins une image requise (CORRIGÉE)
     */
    function validateFiles() {
        let totalFiles = 0;
        let totalImages = 0;
        
        // Compter les fichiers de la zone principale
        if (selectedFiles && selectedFiles.length > 0) {
            totalFiles += selectedFiles.length;
            totalImages += selectedFiles.filter(file => file.type.startsWith('image/')).length;
        }
        
        // Compter les fichiers des zones supplémentaires
        const additionalInputs = document.querySelectorAll('.additional-file-input');
        additionalInputs.forEach(input => {
            if (input.files && input.files.length > 0) {
                totalFiles += input.files.length;
                Array.from(input.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        totalImages++;
                    }
                });
            }
        });
        
        const fileUploadArea = document.getElementById('fileUploadArea');
        
        // Vérifier qu'il y a au moins un fichier et au moins une image
        if (totalFiles === 0 || totalImages === 0) {
            // Ajouter une classe d'erreur à la zone d'upload
            if (fileUploadArea) {
                fileUploadArea.classList.add('upload-error');
            }
            
            if (totalFiles === 0) {
                showAlert('Vous devez ajouter au moins un fichier à votre TP.', 'danger');
            } else {
                showAlert('Vous devez ajouter au moins une image (JPG, PNG, GIF) à votre TP.', 'danger');
            }
            return false;
        } else {
            // Supprimer la classe d'erreur si elle existe
            if (fileUploadArea) {
                fileUploadArea.classList.remove('upload-error');
            }
            return true;
        }
    }

    /**
     * Gestion de la soumission du formulaire
     */
    function handleFormSubmit(e) {
        let isValid = true;
        
        // Validation du titre
        if (!validateTitle()) {
            isValid = false;
        }
        
        // Validation de la description
        if (!validateDescription()) {
            isValid = false;
        }
        
        // Validation des fichiers : au moins une image requise
        if (!validateFiles()) {
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            showAlert('Veuillez corriger les erreurs dans le formulaire', 'danger');
            return false;
        }
        
        // Animation du bouton de soumission
        if (submitBtn) {
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        }
        
        return true;
    }

    /**
     * Configuration du bouton de soumission
     */
    function setupSubmitButton() {
        if (!submitBtn) return;
        
        submitBtn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        submitBtn.addEventListener('mouseleave', function() {
            if (!this.classList.contains('loading')) {
                this.style.transform = 'translateY(0)';
            }
        });
    }

    /**
     * Configuration des compteurs de caractères
     */
    function setupCharacterCounters() {
        const titleInput = document.getElementById('title');
        const descriptionInput = document.getElementById('description');
        
        if (titleInput) {
            addCharacterCounter(titleInput, 255);
        }
        
        if (descriptionInput) {
            addCharacterCounter(descriptionInput, 2000);
        }
    }

    /**
     * Ajout d'un compteur de caractères
     */
    function addCharacterCounter(input, maxLength) {
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.style.cssText = `
            font-size: 0.8rem;
            color: #6c757d;
            text-align: right;
            margin-top: 0.25rem;
        `;
        
        const updateCounter = () => {
            const length = input.value.length;
            counter.textContent = `${length}/${maxLength}`;
            
            if (length > maxLength * 0.9) {
                counter.style.color = '#ff6633';
            } else if (length > maxLength * 0.8) {
                counter.style.color = '#FF9900';
            } else {
                counter.style.color = '#6c757d';
            }
        };
        
        input.addEventListener('input', updateCounter);
        input.parentNode.appendChild(counter);
        updateCounter();
    }

    /**
     * Affichage d'une alerte
     */
    function showAlert(message, type = 'info', duration = 5000) {
        // Supprimer les alertes existantes
        const existingAlerts = document.querySelectorAll('.tp-alert');
        existingAlerts.forEach(alert => alert.remove());
        
        // Créer la nouvelle alerte
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show tp-alert`;
        alert.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            max-width: 500px;
        `;
        
        const icon = getAlertIcon(type);
        alert.innerHTML = `
            <i class="${icon} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alert);
        
        // Animation d'entrée
        setTimeout(() => {
            alert.style.transform = 'translateX(0)';
            alert.style.opacity = '1';
        }, 100);
        
        // Suppression automatique
        if (duration > 0) {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }, duration);
        }
    }

    /**
     * Obtention de l'icône d'alerte
     */
    function getAlertIcon(type) {
        switch (type) {
            case 'success':
                return 'fas fa-check-circle';
            case 'danger':
                return 'fas fa-exclamation-circle';
            case 'warning':
                return 'fas fa-exclamation-triangle';
            default:
                return 'fas fa-info-circle';
        }
    }

    /**
     * Utilitaires pour les statistiques (si présentes)
     */
    function updateStats() {
        // Cette fonction peut être étendue pour mettre à jour les statistiques
        // en temps réel si nécessaire
    }

    /**
     * Animation des éléments au chargement
     */
    function animateElements() {
        const cards = document.querySelectorAll('.tp-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }

    // Animation au chargement
    animateElements();
});

/**
 * Fonctions utilitaires globales
 */

// Fonction pour réinitialiser le formulaire
window.resetTPForm = function() {
    const form = document.getElementById('tp-form');
    if (form) {
        form.reset();
        selectedFiles = [];
        updateFilesList();
        
        // Supprimer les erreurs de validation
        const invalidInputs = form.querySelectorAll('.is-invalid');
        invalidInputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
        
        const feedbacks = form.querySelectorAll('.invalid-feedback');
        feedbacks.forEach(feedback => feedback.remove());
    }
};

/**
 * Fonctions pour l'ajout dynamique de fichiers
 */

// Variable globale pour compter les zones d'upload supplémentaires
let additionalUploadCounter = 0;

// Configuration de l'ajout dynamique de fichiers
function setupDynamicFileUpload() {
    const addMoreFilesBtn = document.getElementById('addMoreFilesBtn');
    
    if (addMoreFilesBtn) {
        addMoreFilesBtn.addEventListener('click', addAdditionalFileUpload);
    }
}

// Ajouter une nouvelle zone d'upload de fichier
function addAdditionalFileUpload() {
    additionalUploadCounter++;
    const additionalUploadsContainer = document.getElementById('additionalFileUploads');
    
    if (!additionalUploadsContainer) return;
    
    const uploadZone = document.createElement('div');
    uploadZone.className = 'additional-upload-zone';
    uploadZone.id = `additionalUpload_${additionalUploadCounter}`;
    
    uploadZone.innerHTML = `
        <div class="upload-header">
            <h6 class="upload-title">
                <i class="fas fa-file-plus me-2"></i>
                Zone d'upload #${additionalUploadCounter}
            </h6>
            <button type="button" class="remove-upload" onclick="removeAdditionalUpload(${additionalUploadCounter})">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="upload-content">
            <div class="file-upload-zone-small" onclick="triggerFileInput(${additionalUploadCounter})">
                <i class="fas fa-cloud-upload-alt text-primary mb-2" style="font-size: 2rem;"></i>
                <p class="mb-1"><strong>Cliquez pour sélectionner des fichiers</strong></p>
                <p class="text-muted small mb-0">ou glissez-déposez ici</p>
            </div>
            <input type="file" 
                   id="additionalFiles_${additionalUploadCounter}" 
                   name="files[]" 
                   multiple 
                   class="d-none additional-file-input"
                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.zip,.rar,.txt,.ppt,.pptx,.xls,.xlsx"
                   onchange="handleAdditionalFileSelect(${additionalUploadCounter}, this)">
            <div id="additionalFilesList_${additionalUploadCounter}" class="mt-3"></div>
        </div>
    `;
    
    additionalUploadsContainer.appendChild(uploadZone);
    
    // Ajouter les événements de drag & drop
    setupDragDropForZone(uploadZone, additionalUploadCounter);
    
    // Animation d'entrée
    setTimeout(() => {
        uploadZone.style.opacity = '1';
        uploadZone.style.transform = 'translateY(0)';
    }, 100);
    
    showAlert(`Zone d'upload #${additionalUploadCounter} ajoutée`, 'success', 2000);
}

// Supprimer une zone d'upload supplémentaire
function removeAdditionalUpload(uploadId) {
    const uploadZone = document.getElementById(`additionalUpload_${uploadId}`);
    if (uploadZone) {
        // Animation de sortie
        uploadZone.style.opacity = '0';
        uploadZone.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            uploadZone.remove();
            updateFileCounters();
            showAlert('Zone d\'upload supprimée', 'info', 2000);
        }, 300);
    }
}

// Déclencher la sélection de fichier pour une zone spécifique
function triggerFileInput(uploadId) {
    const fileInput = document.getElementById(`additionalFiles_${uploadId}`);
    if (fileInput) {
        fileInput.click();
    }
}

// Gérer la sélection de fichiers pour une zone supplémentaire
function handleAdditionalFileSelect(uploadId, input) {
    const files = Array.from(input.files);
    const filesList = document.getElementById(`additionalFilesList_${uploadId}`);
    
    if (!filesList) return;
    
    // Vider la liste précédente
    filesList.innerHTML = '';
    
    files.forEach((file, index) => {
        if (validateFile(file)) {
            // Ajouter le fichier à la liste globale
            selectedFiles.push(file);
            
            // Créer l'affichage du fichier
            const fileItem = createAdditionalFileItem(file, uploadId, index);
            filesList.appendChild(fileItem);
        }
    });
    
    updateFileCounters();
    updateFileInput();
}

// Créer un élément d'affichage pour un fichier supplémentaire
function createAdditionalFileItem(file, uploadId, index) {
    const fileItem = document.createElement('div');
    fileItem.className = `file-item ${file.type.startsWith('image/') ? 'is-image' : ''}`;
    
    const fileIcon = getFileIcon(file);
    const fileSize = formatFileSize(file.size);
    
    fileItem.innerHTML = `
        <div class="file-info">
            <i class="${fileIcon} file-icon me-2"></i>
            <div class="file-details">
                <div class="file-name">${file.name}</div>
                <div class="file-size text-muted">${fileSize}</div>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger" 
                onclick="removeAdditionalFile(${uploadId}, ${index}, this)">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    return fileItem;
}

// Supprimer un fichier d'une zone supplémentaire
function removeAdditionalFile(uploadId, fileIndex, button) {
    // Supprimer l'élément de l'affichage
    const fileItem = button.closest('.file-item');
    if (fileItem) {
        fileItem.remove();
    }
    
    // Mettre à jour les compteurs
    updateFileCounters();
    updateFileInput();
}

// Configurer le drag & drop pour une zone spécifique
function setupDragDropForZone(uploadZone, uploadId) {
    const dropZone = uploadZone.querySelector('.file-upload-zone-small');
    if (!dropZone) return;
    
    let dragCounter = 0;
    
    dropZone.addEventListener('dragenter', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dragCounter++;
        dropZone.classList.add('dragover');
    });
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        e.stopPropagation();
    });
    
    dropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dragCounter--;
        if (dragCounter === 0) {
            dropZone.classList.remove('dragover');
        }
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        e.stopPropagation();
        dragCounter = 0;
        dropZone.classList.remove('dragover');
        
        const files = Array.from(e.dataTransfer.files);
        const fileInput = document.getElementById(`additionalFiles_${uploadId}`);
        
        if (fileInput && files.length > 0) {
            // Simuler la sélection de fichiers
            const dt = new DataTransfer();
            files.forEach(file => dt.items.add(file));
            fileInput.files = dt.files;
            
            // Déclencher l'événement de changement
            handleAdditionalFileSelect(uploadId, fileInput);
        }
    });
}

// Mettre à jour les compteurs de fichiers
function updateFileCounters() {
    const filesCounter = document.getElementById('filesCounter');
    const imagesCounter = document.getElementById('imagesCounter');
    
    if (!filesCounter || !imagesCounter) return;
    
    // Compter tous les fichiers (zone principale + zones supplémentaires)
    let totalFiles = selectedFiles.length;
    let totalImages = selectedFiles.filter(file => file.type.startsWith('image/')).length;
    
    // Ajouter les fichiers des zones supplémentaires
    const additionalInputs = document.querySelectorAll('.additional-file-input');
    additionalInputs.forEach(input => {
        if (input.files) {
            totalFiles += input.files.length;
            Array.from(input.files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    totalImages++;
                }
            });
        }
    });
    
    filesCounter.textContent = `${totalFiles} fichier(s) sélectionné(s)`;
    imagesCounter.textContent = `${totalImages} image(s)`;
    
    // Changer la couleur du badge des images selon le nombre
    if (totalImages === 0) {
        imagesCounter.className = 'badge bg-danger ms-2';
    } else {
        imagesCounter.className = 'badge bg-success ms-2';
    }
}

// Rendre les fonctions globales accessibles
window.removeAdditionalUpload = removeAdditionalUpload;
window.triggerFileInput = triggerFileInput;
window.handleAdditionalFileSelect = handleAdditionalFileSelect;
window.removeAdditionalFile = removeAdditionalFile;

// Fonction pour prévisualiser un fichier (si applicable)
window.previewFile = function(file) {
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Créer une modal de prévisualisation
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Prévisualisation - ${file.name}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="${e.target.result}" class="img-fluid" alt="Prévisualisation">
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            modal.addEventListener('hidden.bs.modal', () => {
                modal.remove();
            });
        };
        reader.readAsDataURL(file);
    }
};
