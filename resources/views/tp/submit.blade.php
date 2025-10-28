@extends('layouts.ki-admin')

@section('title', 'Soumettre le TP')

@push('styles')
<style>
    /* Variables Instagram */
    :root {
        --instagram-purple: #833AB4;
        --instagram-pink: #C13584;
        --instagram-red: #E1306C;
        --instagram-orange: #F56040;
        --instagram-yellow: #FCAF45;
    }

    .submit-container {
        padding: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        background: #f5f7fa;
        min-height: 100vh;
    }

    .submit-header {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink), var(--instagram-red));
        color: white;
        padding: 3rem 2rem;
        border-radius: 20px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 30px rgba(193, 53, 132, 0.3);
        animation: fadeInDown 0.6s ease;
    }

    .submit-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .submit-form {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        animation: fadeInUp 0.6s ease;
    }

    .form-section {
        margin-bottom: 2.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--instagram-purple);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .upload-zone {
        border: 3px dashed #e2e8f0;
        border-radius: 20px;
        padding: 3rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.02), rgba(193, 53, 132, 0.02));
    }

    .upload-zone:hover, .upload-zone.drag-over {
        border-color: var(--instagram-pink);
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.08), rgba(193, 53, 132, 0.08));
        transform: scale(1.01);
    }

    .upload-zone i {
        font-size: 5rem;
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    .files-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 2rem;
    }

    .file-item {
        position: relative;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .file-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(193, 53, 132, 0.3);
    }

    .file-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .file-icon {
        width: 100%;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.1), rgba(193, 53, 132, 0.1));
    }

    .file-icon i {
        font-size: 4rem;
        color: var(--instagram-pink);
    }

    .file-info {
        padding: 1rem;
        background: white;
    }

    .file-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .file-size {
        color: #999;
        font-size: 0.85rem;
    }

    .remove-file {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 35px;
        height: 35px;
        background: rgba(239, 68, 68, 0.9);
        color: white;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .file-item:hover .remove-file {
        opacity: 1;
    }

    .remove-file:hover {
        background: #dc2626;
        transform: scale(1.1);
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--instagram-pink);
        box-shadow: 0 0 0 0.2rem rgba(193, 53, 132, 0.15);
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink));
        color: white;
        border: none;
        padding: 1rem 3rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 8px 20px rgba(193, 53, 132, 0.3);
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, var(--instagram-pink), var(--instagram-red));
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(193, 53, 132, 0.4);
    }

    .btn-cancel {
        border: 2px solid var(--instagram-pink);
        color: var(--instagram-pink);
        background: white;
        padding: 1rem 3rem;
        border-radius: 30px;
        font-weight: 700;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: var(--instagram-pink);
        color: white;
    }

    .alert-info-custom {
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.1), rgba(193, 53, 132, 0.1));
        border-left: 4px solid var(--instagram-pink);
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

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

    .tp-details-box {
        background: linear-gradient(135deg, rgba(131, 58, 180, 0.05), rgba(193, 53, 132, 0.05));
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        border: 2px solid rgba(193, 53, 132, 0.2);
    }

    .tp-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .tp-meta-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .tp-meta-item i {
        color: var(--instagram-pink);
        font-size: 1.2rem;
    }
</style>
@endpush

@section('content')
<div class="submit-container">
    <!-- Header -->
    <div class="submit-header">
        <h1><i class="fas fa-paper-plane me-3"></i>Soumettre votre Travail Pratique</h1>
        <p style="font-size: 1.1rem; opacity: 0.95; margin: 0;">
            Uploadez vos fichiers et partagez votre travail
        </p>
    </div>

    <!-- Détails du TP -->
    <div class="tp-details-box">
        <h3 style="color: var(--instagram-purple); font-weight: 700; margin-bottom: 1rem;">
            <i class="fas fa-file-alt me-2"></i>{{ $tp->title }}
        </h3>
        <div class="tp-description" style="color: #555; line-height: 1.8; margin-bottom: 1rem;">
            {!! $tp->description !!}
        </div>
        <div class="tp-meta">
            <div class="tp-meta-item">
                <i class="fas fa-calendar"></i>
                <div>
                    <small style="color: #999;">Assigné le</small><br>
                    <strong>{{ \Carbon\Carbon::parse($tp->created_at)->format('d/m/Y') }}</strong>
                </div>
            </div>
            <div class="tp-meta-item">
                <i class="fas fa-clock"></i>
                <div>
                    <small style="color: #999;">À rendre avant le</small><br>
                    <strong style="color: var(--instagram-red);">{{ \Carbon\Carbon::parse($tp->deadline)->format('d/m/Y à H:i') }}</strong>
                </div>
            </div>
            <div class="tp-meta-item">
                <i class="fas fa-graduation-cap"></i>
                <div>
                    <small style="color: #999;">Formation</small><br>
                    <strong>{{ $tp->formation }}</strong>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de soumission -->
    <form id="submitForm" action="{{ route($formationPrefix . '.tp.submit', $tp->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="submit-form">
            <!-- Alert Info -->
            <div class="alert-info-custom">
                <i class="fas fa-exclamation-circle" style="color: var(--instagram-red); font-size: 1.5rem; margin-right: 1rem;"></i>
                <strong style="color: var(--instagram-purple);">Obligatoire :</strong>
                <span style="color: #555;">Vous devez uploader au moins un fichier pour soumettre votre travail. Le lien et le commentaire sont optionnels.</span>
            </div>

            <!-- Lien de soumission -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-link"></i>
                    Lien de soumission <small style="font-weight: 400; color: #999;">(optionnel)</small>
                </div>
                <label class="form-label" style="font-weight: 600; color: #2c3e50;">
                    Lien vers votre travail (Google Drive, Dropbox, etc.)
                </label>
                <input 
                    type="url" 
                    class="form-control" 
                    name="submission_link" 
                    id="submission_link"
                    placeholder="https://drive.google.com/..." 
                >
                <small class="text-muted">
                    <i class="fas fa-lightbulb me-1"></i>
                    Exemple : Lien vers votre dossier Google Drive partagé
                </small>
            </div>

            <!-- Upload de fichiers -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-cloud-upload-alt"></i>
                    Fichiers à uploader <span style="color: var(--instagram-red);">*</span>
                </div>
                
                <div class="upload-zone" id="uploadZone">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h3 style="color: var(--instagram-purple); font-weight: 700; margin-bottom: 0.5rem;">
                        Cliquez ou glissez vos fichiers ici
                    </h3>
                    <p style="color: #999; margin-bottom: 1rem;">
                        Vous pouvez uploader plusieurs images, documents, etc.
                    </p>
                    <small style="color: #999;">
                        <i class="fas fa-check-circle me-1" style="color: #10b981;"></i>
                        Formats : JPG, PNG, PDF, DOC, ZIP, etc. • Max 10 Mo par fichier
                    </small>
                </div>

                <input 
                    type="file" 
                    id="fileInput" 
                    name="files[]" 
                    multiple 
                    accept="image/*,.pdf,.doc,.docx,.ppt,.pptx,.zip,.rar"
                    style="display: none;"
                >

                <!-- Prévisualisation des fichiers -->
                <div id="filesPreview" class="files-preview" style="display: none;"></div>
            </div>

            <!-- Commentaire -->
            <div class="form-section">
                <div class="section-title">
                    <i class="fas fa-comment"></i>
                    Commentaire <small style="font-weight: 400; color: #999;">(optionnel)</small>
                </div>
                <textarea 
                    class="form-control" 
                    name="comment" 
                    rows="4" 
                    placeholder="Ajoutez un commentaire sur votre travail, des explications, etc..."
                ></textarea>
            </div>

            <!-- Boutons -->
            <div class="d-flex gap-3 justify-content-center">
                <a href="{{ route($formationPrefix . '.todo.index') }}" class="btn-cancel">
                    <i class="fas fa-times me-2"></i>Annuler
                </a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane me-2"></i>Soumettre le TP
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Fonction pour afficher un message de succès
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(16, 185, 129, 0.4);
        z-index: 9999;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideInRight 0.5s ease, fadeOut 0.5s ease 1.5s;
    `;
    alertDiv.innerHTML = `
        <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 2000);
}

// Fonction pour afficher un message d'erreur
function showErrorMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(239, 68, 68, 0.4);
        z-index: 9999;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideInRight 0.5s ease, shake 0.5s ease;
    `;
    alertDiv.innerHTML = `
        <i class="fas fa-exclamation-circle" style="font-size: 1.5rem;"></i>
        <span>${message}</span>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.animation = 'fadeOut 0.5s ease';
        setTimeout(() => alertDiv.remove(), 500);
    }, 4000);
}

// Ajouter les animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes fadeOut {
        from {
            opacity: 1;
        }
        to {
            opacity: 0;
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
`;
document.head.appendChild(style);

let selectedFiles = [];
const uploadZone = document.getElementById('uploadZone');
const fileInput = document.getElementById('fileInput');
const filesPreview = document.getElementById('filesPreview');

// Clic sur la zone d'upload
uploadZone.addEventListener('click', () => {
    fileInput.click();
});

// Changement de fichiers
fileInput.addEventListener('change', function(e) {
    addFiles(Array.from(this.files));
});

// Drag & Drop
uploadZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadZone.classList.add('drag-over');
});

uploadZone.addEventListener('dragleave', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('drag-over');
});

uploadZone.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadZone.classList.remove('drag-over');
    addFiles(Array.from(e.dataTransfer.files));
});

function addFiles(files) {
    files.forEach(file => {
        if (file.size <= 10485760) { // 10 Mo max
            selectedFiles.push(file);
        } else {
            alert(`❌ Le fichier "${file.name}" est trop volumineux (max 10 Mo)`);
        }
    });
    displayFiles();
}

function displayFiles() {
    if (selectedFiles.length === 0) {
        filesPreview.style.display = 'none';
        return;
    }

    filesPreview.style.display = 'grid';
    filesPreview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const fileItem = document.createElement('div');
        fileItem.className = 'file-item';

        const isImage = file.type.startsWith('image/');
        const fileSize = (file.size / 1024 / 1024).toFixed(2);

        if (isImage) {
            const reader = new FileReader();
            reader.onload = function(e) {
                fileItem.innerHTML = `
                    <img src="${e.target.result}" class="file-image" alt="${file.name}">
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="file-size">${fileSize} Mo</div>
                    </div>
                    <button type="button" class="remove-file" onclick="removeFile(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            const icon = getFileIcon(file.name);
            fileItem.innerHTML = `
                <div class="file-icon">
                    <i class="${icon}"></i>
                </div>
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${fileSize} Mo</div>
                </div>
                <button type="button" class="remove-file" onclick="removeFile(${index})">
                    <i class="fas fa-times"></i>
                </button>
            `;
        }

        filesPreview.appendChild(fileItem);
    });
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    displayFiles();
}

function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
    const icons = {
        'pdf': 'fas fa-file-pdf',
        'doc': 'fas fa-file-word',
        'docx': 'fas fa-file-word',
        'ppt': 'fas fa-file-powerpoint',
        'pptx': 'fas fa-file-powerpoint',
        'zip': 'fas fa-file-archive',
        'rar': 'fas fa-file-archive',
    };
    return icons[ext] || 'fas fa-file';
}

// Soumission du formulaire
document.getElementById('submitForm').addEventListener('submit', function(e) {
    e.preventDefault();
    e.stopPropagation();
    
    // Vérifier qu'au moins un fichier est sélectionné
    if (selectedFiles.length === 0) {
        showErrorMessage('❌ Vous devez uploader au moins un fichier pour soumettre votre TP.');
        return false;
    }
    
    // Créer un FormData vide (ne pas utiliser le formulaire pour éviter la duplication)
    const formData = new FormData();
    
    // Ajouter le token CSRF
    formData.append('_token', document.querySelector('input[name="_token"]').value);
    
    // Ajouter le lien de soumission si présent
    const submissionLink = document.querySelector('input[name="submission_link"]');
    if (submissionLink && submissionLink.value) {
        formData.append('submission_link', submissionLink.value);
    }
    
    // Ajouter les fichiers (une seule fois)
    selectedFiles.forEach((file) => {
        formData.append('files[]', file);
    });
    
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher un message de succès élégant
            showSuccessMessage('✅ ' + data.message);
            
            // Rediriger après 2 secondes
            setTimeout(() => {
                window.location.href = '{{ route($formationPrefix . ".todo.index") }}';
            }, 2000);
        } else {
            showErrorMessage('❌ ' + (data.message || 'Erreur lors de la soumission'));
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showErrorMessage('❌ Erreur lors de la soumission. Veuillez réessayer.');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});
</script>
@endsection
