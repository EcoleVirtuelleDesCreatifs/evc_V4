document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let pdfCount = 0;
    const maxPdfs = 10;
    const maxFileSize = 50 * 1024 * 1024; // 50MB en bytes
    
    // Éléments DOM
    const addPdfBtn = document.getElementById('addPdfBtn');
    const pdfContainer = document.getElementById('pdfContainer');
    const globalPdfDropZone = document.getElementById('globalPdfDropZone');
    const pdfFieldTemplate = document.getElementById('pdfFieldTemplate');
    const submitBtn = document.getElementById('submitBtn');
    const formProgress = document.getElementById('formProgress');
    const progressText = document.getElementById('progressText');
    const pdfCountDisplay = document.getElementById('pdfCount');
    
    // Éléments de validation
    const titleInput = document.getElementById('title');
    const categorySelect = document.getElementById('category');
    const softwareCheckboxes = document.querySelectorAll('input[name="software_used[]"]');
    
    // Ajouter le premier champ PDF au chargement
    addPdfField();
    
    // Event listeners
    addPdfBtn.addEventListener('click', addPdfField);
    
    // Validation en temps réel
    titleInput.addEventListener('input', updateFormValidation);
    categorySelect.addEventListener('change', updateFormValidation);
    softwareCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateFormValidation);
    });
    
    // Drag & Drop global
    setupGlobalDragDrop();
    
    // Fonction pour ajouter un champ PDF
    function addPdfField() {
        if (pdfCount >= maxPdfs) {
            alert(`Vous ne pouvez ajouter que ${maxPdfs} PDF maximum.`);
            return;
        }
        
        pdfCount++;
        
        // Cloner le template
        const template = pdfFieldTemplate.content.cloneNode(true);
        const pdfField = template.querySelector('.pdf-field');
        
        // Configurer le champ
        pdfField.setAttribute('data-index', pdfCount);
        pdfField.querySelector('.pdf-number').textContent = pdfCount;
        
        // Ajouter au container
        pdfContainer.appendChild(pdfField);
        
        // Configurer les événements pour ce champ
        setupPdfField(pdfField);
        
        // Mettre à jour l'affichage
        updatePdfCount();
        updateFormValidation();
        
        // Masquer la zone de drop si on a des champs
        if (pdfCount > 0) {
            globalPdfDropZone.style.display = 'none';
        }
    }
    
    // Fonction pour configurer un champ PDF
    function setupPdfField(pdfField) {
        const pdfInput = pdfField.querySelector('.pdf-input');
        const uploadZone = pdfField.querySelector('.pdf-upload-zone');
        const uploadPlaceholder = pdfField.querySelector('.upload-placeholder');
        const uploadPreview = pdfField.querySelector('.upload-preview');
        const removeBtn = pdfField.querySelector('.remove-pdf');
        
        // Click sur la zone d'upload
        uploadZone.addEventListener('click', () => {
            if (!pdfInput.files.length) {
                pdfInput.click();
            }
        });
        
        // Changement de fichier
        pdfInput.addEventListener('change', function(e) {
            handlePdfUpload(e.target.files[0], pdfField);
        });
        
        // Drag & Drop sur le champ
        setupFieldDragDrop(uploadZone, pdfField);
        
        // Bouton supprimer
        removeBtn.addEventListener('click', () => {
            removePdfField(pdfField);
        });
    }
    
    // Fonction pour gérer l'upload d'un PDF
    function handlePdfUpload(file, pdfField) {
        if (!file) return;
        
        // Validation du type de fichier
        if (file.type !== 'application/pdf') {
            alert('Seuls les fichiers PDF sont acceptés.');
            return;
        }
        
        // Validation de la taille
        if (file.size > maxFileSize) {
            alert(`Le fichier est trop volumineux. Taille maximum: ${maxFileSize / (1024 * 1024)}MB`);
            return;
        }
        
        // Mettre à jour l'aperçu
        const uploadPlaceholder = pdfField.querySelector('.upload-placeholder');
        const uploadPreview = pdfField.querySelector('.upload-preview');
        const pdfName = pdfField.querySelector('.pdf-name');
        const pdfSize = pdfField.querySelector('.pdf-size');
        
        uploadPlaceholder.style.display = 'none';
        uploadPreview.style.display = 'block';
        
        pdfName.textContent = file.name;
        pdfSize.textContent = formatFileSize(file.size);
        
        // Marquer le champ comme ayant un fichier
        pdfField.setAttribute('data-has-file', 'true');
        
        updateFormValidation();
        updateProjectPreview();
    }
    
    // Fonction pour supprimer un champ PDF
    function removePdfField(pdfField) {
        pdfField.remove();
        pdfCount--;
        
        // Renuméroter les champs restants
        const remainingFields = pdfContainer.querySelectorAll('.pdf-field');
        remainingFields.forEach((field, index) => {
            field.setAttribute('data-index', index + 1);
            field.querySelector('.pdf-number').textContent = index + 1;
        });
        
        // Réafficher la zone de drop si plus de champs
        if (pdfCount === 0) {
            globalPdfDropZone.style.display = 'block';
        }
        
        updatePdfCount();
        updateFormValidation();
        updateProjectPreview();
    }
    
    // Configuration du drag & drop global
    function setupGlobalDragDrop() {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            globalPdfDropZone.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            globalPdfDropZone.addEventListener(eventName, () => {
                globalPdfDropZone.classList.add('border-success');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            globalPdfDropZone.addEventListener(eventName, () => {
                globalPdfDropZone.classList.remove('border-success');
            }, false);
        });
        
        globalPdfDropZone.addEventListener('drop', handleGlobalDrop, false);
    }
    
    // Configuration du drag & drop pour un champ
    function setupFieldDragDrop(uploadZone, pdfField) {
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, preventDefaults, false);
        });
        
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.classList.add('border-success');
            }, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.classList.remove('border-success');
            }, false);
        });
        
        uploadZone.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handlePdfUpload(files[0], pdfField);
            }
        }, false);
    }
    
    // Gérer le drop global
    function handleGlobalDrop(e) {
        const files = Array.from(e.dataTransfer.files);
        
        files.forEach(file => {
            if (file.type === 'application/pdf') {
                addPdfField();
                const lastField = pdfContainer.querySelector('.pdf-field:last-child');
                handlePdfUpload(file, lastField);
            }
        });
    }
    
    // Prévenir les comportements par défaut
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    // Mettre à jour le compteur de PDF
    function updatePdfCount() {
        const actualPdfCount = pdfContainer.querySelectorAll('.pdf-field[data-has-file="true"]').length;
        pdfCountDisplay.textContent = actualPdfCount;
    }
    
    // Validation du formulaire
    function updateFormValidation() {
        const title = titleInput.value.trim();
        const category = categorySelect.value;
        const hasPdf = pdfContainer.querySelectorAll('.pdf-field[data-has-file="true"]').length > 0;
        const hasSoftware = Array.from(softwareCheckboxes).some(cb => cb.checked);
        
        // Afficher/masquer les alertes
        document.getElementById('pdfAlert').style.display = hasPdf ? 'none' : 'block';
        document.getElementById('softwareAlert').style.display = hasSoftware ? 'none' : 'block';
        
        // Calculer la progression (25% par champ obligatoire)
        let progress = 0;
        if (title) progress += 25;
        if (category) progress += 25;
        if (hasPdf) progress += 25;
        if (hasSoftware) progress += 25;
        
        // Mettre à jour la barre de progression
        formProgress.style.width = progress + '%';
        
        // Mettre à jour le texte de progression
        if (progress === 0) {
            progressText.textContent = 'Remplissez le formulaire';
        } else if (progress < 100) {
            progressText.textContent = `Progression: ${progress}%`;
        } else {
            progressText.textContent = 'Formulaire complet !';
        }
        
        // Activer/désactiver le bouton de soumission
        const isValid = title && category && hasPdf && hasSoftware;
        submitBtn.disabled = !isValid;
        
        updatePdfCount();
    }
    
    // Mettre à jour l'aperçu du projet
    function updateProjectPreview() {
        const title = titleInput.value.trim();
        const category = categorySelect.value;
        const pdfCount = pdfContainer.querySelectorAll('.pdf-field[data-has-file="true"]').length;
        const selectedSoftware = Array.from(softwareCheckboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.nextElementSibling.textContent.trim());
        
        const previewContainer = document.getElementById('projectPreview');
        
        if (!title && !category && pdfCount === 0) {
            previewContainer.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-file-pdf fa-2x mb-2"></i>
                    <p class="mb-0">Remplissez les informations pour voir l'aperçu</p>
                </div>
            `;
            return;
        }
        
        const categoryNames = {
            'carte_visite': 'Carte de Visite',
            'plaquette': 'Plaquette',
            'catalogue': 'Catalogue',
            'livre': 'Livre',
            'depliant': 'Dépliant',
            'brochure': 'Brochure',
            'flyer': 'Flyer',
            'affiche': 'Affiche',
            'menu': 'Menu',
            'rapport': 'Rapport'
        };
        
        previewContainer.innerHTML = `
            <div class="mb-3">
                <h6 class="text-primary mb-1">${title || 'Titre du projet'}</h6>
                <small class="text-muted">${categoryNames[category] || 'Type de document'}</small>
            </div>
            <div class="mb-3">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-file-pdf text-danger me-2"></i>
                    <span class="small">${pdfCount} PDF${pdfCount > 1 ? 's' : ''}</span>
                </div>
                ${selectedSoftware.length > 0 ? `
                <div class="d-flex align-items-center">
                    <i class="fas fa-tools text-warning me-2"></i>
                    <span class="small">${selectedSoftware.join(', ')}</span>
                </div>
                ` : ''}
            </div>
        `;
    }
    
    // Formater la taille de fichier
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    // Initialiser la validation
    updateFormValidation();
});
