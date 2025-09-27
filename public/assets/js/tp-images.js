/**
 * Gestionnaire d'interface pour l'ajout de projets avec images multiples
 * Version: 1.0.0
 */

class ProjectImageManager {
    constructor() {
        this.imageCount = 0;
        this.maxImages = 15;
        this.thumbnailIndex = 0;
        this.allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
        this.maxFileSize = 20 * 1024 * 1024; // 20MB
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupDragAndDrop();
        
        // Initialiser la barre de progression à zéro
        this.initializeProgressBar();
        
        // Ajouter une première image par défaut
        this.addImageField();
    }

    initializeProgressBar() {
        // S'assurer que la barre commence à 0%
        document.getElementById('formProgress').style.width = '0%';
        document.getElementById('progressText').textContent = 'Commencez par remplir le titre';
        document.getElementById('submitBtn').disabled = true;
    }

    bindEvents() {
        // Bouton d'ajout d'image
        document.getElementById('addImageBtn').addEventListener('click', () => {
            this.addImageField();
        });

        // Compteur de caractères pour la description
        const descriptionField = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        
        descriptionField.addEventListener('input', () => {
            const count = descriptionField.value.length;
            charCount.textContent = count;
            charCount.className = count > 1900 ? 'text-warning' : 'text-muted';
            this.updateFormProgress();
        });

        // Mise à jour de l'aperçu en temps réel
        ['title', 'category', 'description', 'tags'].forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('input', () => {
                this.updatePreview();
                this.updateFormProgress();
            });
        });

        // Validation des logiciels utilisés
        document.querySelectorAll('input[name="software_used[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                this.updateFormProgress();
                this.validateSoftware();
            });
        });

        // Validation du formulaire
        document.getElementById('projectForm').addEventListener('submit', (e) => {
            if (!this.validateForm()) {
                e.preventDefault();
                this.showAlert('Veuillez corriger les erreurs avant de soumettre le formulaire.', 'danger');
            }
        });
    }

    setupDragAndDrop() {
        const globalDropZone = document.getElementById('globalDropZone');

        // Événements pour la zone de drop globale
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            globalDropZone.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            globalDropZone.addEventListener(eventName, () => {
                globalDropZone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            globalDropZone.addEventListener(eventName, () => {
                globalDropZone.classList.remove('dragover');
            }, false);
        });

        globalDropZone.addEventListener('drop', (e) => {
            const files = Array.from(e.dataTransfer.files);
            this.handleMultipleFiles(files);
        }, false);

        // Clic sur la zone globale
        globalDropZone.addEventListener('click', () => {
            this.addImageField();
        });
    }

    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    addImageField() {
        if (this.imageCount >= this.maxImages) {
            this.showAlert(`Maximum ${this.maxImages} images autorisées.`, 'warning');
            return;
        }

        const template = document.getElementById('imageFieldTemplate');
        const clone = template.content.cloneNode(true);
        const imageField = clone.querySelector('.image-field');
        
        // Configurer l'index
        const index = this.imageCount;
        imageField.setAttribute('data-index', index);
        imageField.querySelector('.image-number').textContent = index + 1;
        
        // Configurer l'input file
        const fileInput = imageField.querySelector('.image-input');
        fileInput.setAttribute('id', `image_${index}`);
        
        // Événements pour ce champ
        this.bindImageFieldEvents(imageField, index);
        
        // Ajouter au container
        document.getElementById('imagesContainer').appendChild(imageField);
        
        // Animation d'apparition
        imageField.classList.add('fade-in');
        
        // Ne pas incrémenter imageCount ici - seulement quand une image est uploadée
        this.updateImageCount();
        
        // Si c'est la première image, la définir comme thumbnail
        if (this.imageCount === 1) {
            this.setThumbnail(index);
        }
    }

    bindImageFieldEvents(imageField, index) {
        const fileInput = imageField.querySelector('.image-input');
        const uploadZone = imageField.querySelector('.image-upload-zone');
        const removeBtn = imageField.querySelector('.remove-image');
        const thumbnailBtn = imageField.querySelector('.set-thumbnail');

        // Clic sur la zone d'upload
        uploadZone.addEventListener('click', () => {
            fileInput.click();
        });

        // Changement de fichier
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                this.handleFileSelect(e.target.files[0], imageField, index);
            }
        });

        // Drag & Drop sur le champ individuel
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, this.preventDefaults, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.classList.add('dragover');
            }, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadZone.addEventListener(eventName, () => {
                uploadZone.classList.remove('dragover');
            }, false);
        });

        uploadZone.addEventListener('drop', (e) => {
            const files = Array.from(e.dataTransfer.files);
            if (files.length > 0) {
                this.handleFileSelect(files[0], imageField, index);
            }
        });

        // Bouton de suppression
        removeBtn.addEventListener('click', () => {
            this.removeImageField(imageField, index);
        });

        // Bouton thumbnail
        thumbnailBtn.addEventListener('click', () => {
            this.setThumbnail(index);
        });
    }

    handleMultipleFiles(files) {
        const validFiles = files.filter(file => this.validateFile(file));
        
        validFiles.forEach((file, i) => {
            if (this.imageCount < this.maxImages) {
                this.addImageField();
                const lastField = document.querySelector(`.image-field[data-index="${this.imageCount - 1}"]`);
                this.handleFileSelect(file, lastField, this.imageCount - 1);
            }
        });

        if (validFiles.length < files.length) {
            this.showAlert('Certains fichiers ont été ignorés (format ou taille non valide).', 'warning');
        }
    }

    handleFileSelect(file, imageField, index) {
        if (!this.validateFile(file)) {
            return;
        }

        const fileInput = imageField.querySelector('.image-input');
        const placeholder = imageField.querySelector('.upload-placeholder');
        const preview = imageField.querySelector('.image-preview');
        const img = preview.querySelector('img');
        const imageName = preview.querySelector('.image-name');
        const imageSize = preview.querySelector('.image-size');

        // Créer un nouveau FileList avec le fichier
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        fileInput.files = dataTransfer.files;

        // Afficher l'aperçu
        const reader = new FileReader();
        reader.onload = (e) => {
            img.src = e.target.result;
            imageName.textContent = file.name;
            imageSize.textContent = this.formatFileSize(file.size);
            
            placeholder.style.display = 'none';
            preview.style.display = 'block';
            
            // Mettre à jour le compteur d'images puis la progression
            this.updateImageCount();
            this.updateFormProgress();
        };
        reader.readAsDataURL(file);
    }

    validateFile(file) {
        // Vérifier le type
        if (!this.allowedTypes.includes(file.type) && !file.name.toLowerCase().endsWith('.psd') && !file.name.toLowerCase().endsWith('.ai')) {
            this.showAlert(`Format de fichier non supporté: ${file.name}`, 'danger');
            return false;
        }

        // Vérifier la taille
        if (file.size > this.maxFileSize) {
            this.showAlert(`Fichier trop volumineux: ${file.name} (max 20MB)`, 'danger');
            return false;
        }

        return true;
    }

    removeImageField(imageField, index) {
        // Vérifier qu'il reste au moins une image
        if (this.imageCount <= 1) {
            this.showAlert('Au moins une image est requise.', 'warning');
            return;
        }

        // Si c'était le thumbnail, définir le premier comme nouveau thumbnail
        if (this.thumbnailIndex === index) {
            const firstField = document.querySelector('.image-field:not([data-index="' + index + '"])');
            if (firstField) {
                const firstIndex = parseInt(firstField.getAttribute('data-index'));
                this.setThumbnail(firstIndex);
            }
        }

        // Supprimer le champ
        imageField.remove();
        
        // Recalculer le compteur d'images et mettre à jour la progression
        this.updateImageCount();
        this.renumberImageFields();
        this.updateFormProgress();
    }

    setThumbnail(index) {
        // Retirer l'ancien thumbnail
        document.querySelectorAll('.image-field.thumbnail').forEach(field => {
            field.classList.remove('thumbnail');
        });

        // Définir le nouveau thumbnail
        const imageField = document.querySelector(`.image-field[data-index="${index}"]`);
        if (imageField) {
            imageField.classList.add('thumbnail');
            this.thumbnailIndex = index;
            
            // Ajouter un input hidden pour indiquer le thumbnail
            const existingThumbnailInput = document.querySelector('input[name="thumbnail"]');
            if (existingThumbnailInput) {
                existingThumbnailInput.remove();
            }
            
            const thumbnailInput = document.createElement('input');
            thumbnailInput.type = 'hidden';
            thumbnailInput.name = 'thumbnail';
            thumbnailInput.value = index;
            document.getElementById('projectForm').appendChild(thumbnailInput);
        }
    }

    renumberImageFields() {
        const fields = document.querySelectorAll('.image-field');
        fields.forEach((field, index) => {
            field.setAttribute('data-index', index);
            field.querySelector('.image-number').textContent = index + 1;
            field.querySelector('.image-input').setAttribute('id', `image_${index}`);
        });
    }

    updateImageCount() {
        // Compter seulement les images réellement uploadées (preview visible)
        const uploadedImages = document.querySelectorAll('.image-preview[style*="display: block"]').length;
        this.imageCount = uploadedImages;
        
        document.getElementById('imageCount').textContent = `${this.imageCount} image(s)`;
        
        // Compter les champs d'images totaux pour la limite
        const totalFields = document.querySelectorAll('.image-field').length;
        
        // Masquer/afficher le bouton d'ajout
        const addBtn = document.getElementById('addImageBtn');
        if (totalFields >= this.maxImages) {
            addBtn.disabled = true;
            addBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i>Maximum atteint';
        } else {
            addBtn.disabled = false;
            addBtn.innerHTML = '<i class="fas fa-plus me-1"></i>Ajouter une image';
        }
    }

    updatePreview() {
        const title = document.getElementById('title').value;
        const category = document.getElementById('category').value;
        const description = document.getElementById('description').value;
        const tags = document.getElementById('tags').value;
        
        const preview = document.getElementById('projectPreview');
        
        if (!title && !category && !description) {
            preview.innerHTML = `
                <div class="text-center text-muted py-4">
                    <i class="fas fa-image fa-2x mb-2"></i>
                    <p class="mb-0">Remplissez les informations pour voir l'aperçu</p>
                </div>
            `;
            return;
        }

        const categoryLabels = {
            'photoshop': 'Photoshop',
            'illustrator': 'Illustrator',
            'indesign': 'InDesign',
            'web-design': 'Web Design',
            'ui-ux': 'UI/UX Design',
            'branding': 'Branding'
        };

        preview.innerHTML = `
            <div>
                ${title ? `<h6 class="mb-2">${title}</h6>` : ''}
                ${category ? `<span class="badge bg-primary mb-2">${categoryLabels[category] || category}</span>` : ''}
                ${description ? `<p class="small text-muted mb-2">${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</p>` : ''}
                ${tags ? `<div class="mb-2">${tags.split(',').map(tag => `<span class="badge bg-light text-dark me-1">#${tag.trim()}</span>`).join('')}</div>` : ''}
                <small class="text-muted">
                    <i class="fas fa-images me-1"></i>${this.imageCount} image(s)
                </small>
            </div>
        `;
    }

    updateFormProgress() {
        const title = document.getElementById('title').value.trim();
        const category = document.getElementById('category').value;
        const hasImages = this.imageCount > 0;
        const hasSoftware = this.getSelectedSoftware().length > 0;
        
        let progress = 0;
        let progressText = '';
        
        // Progression linéaire : 25% par champ obligatoire rempli
        if (title) progress += 25;
        if (category) progress += 25;
        if (hasImages) progress += 25;
        if (hasSoftware) progress += 25;
        
        // Messages de progression guidés
        if (progress === 0) {
            progressText = 'Commencez par remplir le titre';
        } else if (progress === 25) {
            progressText = 'Sélectionnez une catégorie';
        } else if (progress === 50) {
            progressText = 'Ajoutez au moins une image';
        } else if (progress === 75) {
            progressText = 'Sélectionnez au moins un logiciel';
        } else if (progress === 100) {
            progressText = 'Prêt à soumettre !';
        }
        
        document.getElementById('formProgress').style.width = `${progress}%`;
        document.getElementById('progressText').textContent = progressText;
        
        // Activer le bouton seulement à 100%
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = progress < 100;
        
        if (progress === 100) {
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-primary');
        } else {
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-secondary');
        }
    }

    validateForm() {
        const title = document.getElementById('title').value;
        const category = document.getElementById('category').value;
        const hasSoftware = this.getSelectedSoftware().length > 0;
        
        if (!title || !category || this.imageCount === 0 || !hasSoftware) {
            // Afficher les alertes spécifiques
            if (this.imageCount === 0) {
                this.showAlert('Au moins une image est obligatoire.', 'warning');
            }
            if (!hasSoftware) {
                this.showAlert('Au moins un logiciel utilisé doit être sélectionné.', 'warning');
                this.showSoftwareAlert();
            }
            return false;
        }
        
        return true;
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    getSelectedSoftware() {
        return Array.from(document.querySelectorAll('input[name="software_used[]"]:checked'))
            .map(checkbox => checkbox.value);
    }

    validateSoftware() {
        const hasSoftware = this.getSelectedSoftware().length > 0;
        const softwareAlert = document.getElementById('softwareAlert');
        
        if (hasSoftware) {
            softwareAlert.style.display = 'none';
        } else {
            // Ne pas afficher l'alerte immédiatement, seulement si l'utilisateur essaie de soumettre
        }
    }

    showSoftwareAlert() {
        const softwareAlert = document.getElementById('softwareAlert');
        softwareAlert.style.display = 'block';
        
        // Faire défiler vers l'alerte
        softwareAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    showAlert(message, type = 'info') {
        // Créer une alerte Bootstrap
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 400px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto-suppression après 5 secondes
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

// Initialiser quand le DOM est prêt
document.addEventListener('DOMContentLoaded', () => {
    new ProjectImageManager();
});

// Événement beforeunload supprimé pour éviter la boîte modale "Quitter le site Web ?"
