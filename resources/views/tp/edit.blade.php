@extends('layouts.ki-admin')

@section('title', 'Modifier le Projet - ' . $project->title)

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Modifier le Projet
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.design-graphique') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('design-graphique.tp.tous') }}">Tous les TP</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('design-graphique.tp.voir', $project->id) }}">{{ $project->title }}</a></li>
                            <li class="breadcrumb-item active">Modifier</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('design-graphique.tp.voir', $project->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-edit me-2"></i>
                        Informations du Projet
                    </h6>
                </div>
                <div class="card-body">
                    <form id="editProjectForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label fw-bold">Titre du projet *</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ $project->title }}" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label fw-bold">Catégorie *</label>
                                <select class="form-select" id="category" name="category" required>
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="logo" {{ $project->category === 'logo' ? 'selected' : '' }}>Logo</option>
                                    <option value="affiche" {{ $project->category === 'affiche' ? 'selected' : '' }}>Affiche</option>
                                    <option value="flyer" {{ $project->category === 'flyer' ? 'selected' : '' }}>Flyer</option>
                                    <option value="brochure" {{ $project->category === 'brochure' ? 'selected' : '' }}>Brochure</option>
                                    <option value="carte_visite" {{ $project->category === 'carte_visite' ? 'selected' : '' }}>Carte de visite</option>
                                    <option value="packaging" {{ $project->category === 'packaging' ? 'selected' : '' }}>Packaging</option>
                                    <option value="web_design" {{ $project->category === 'web_design' ? 'selected' : '' }}>Web Design</option>
                                    <option value="illustration" {{ $project->category === 'illustration' ? 'selected' : '' }}>Illustration</option>
                                    <option value="autre" {{ $project->category === 'autre' ? 'selected' : '' }}>Autre</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Décrivez votre projet...">{{ $project->description }}</textarea>
                            <div class="form-text">Optionnel - Décrivez brièvement votre projet</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="link" class="form-label fw-bold">Lien du projet</label>
                                <input type="url" class="form-control" id="link" name="link" value="{{ $project->link }}" placeholder="https://...">
                                <div class="form-text">Optionnel - Lien vers votre projet en ligne</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tags" class="form-label fw-bold">Tags</label>
                                <input type="text" class="form-control" id="tags" name="tags" value="{{ is_array($project->tags) ? implode(', ', $project->tags) : $project->tags }}" placeholder="tag1, tag2, tag3">
                                <div class="form-text">Optionnel - Séparez les tags par des virgules</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="software_used" class="form-label fw-bold">Logiciels utilisés *</label>
                            <input type="text" class="form-control" id="software_used" name="software_used" value="{{ is_array($project->software_used) ? implode(', ', $project->software_used) : $project->software_used }}" placeholder="Photoshop, Illustrator, InDesign" required>
                            <div class="form-text">Séparez les logiciels par des virgules</div>
                            <div class="invalid-feedback"></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('design-graphique.tp.voir', $project->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Gestion des images -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-images me-2"></i>
                        Gestion des Images ({{ $project->images ? $project->images->count() : 0 }})
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Images actuelles -->
                    @if($project->images && $project->images->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-muted mb-3">Images actuelles</h6>
                        <div class="row" id="current-images">
                            @foreach($project->images as $image)
                            <div class="col-6 mb-3" id="image-{{ $image->id }}">
                                <div class="card border-0 shadow-sm">
                                    <div class="position-relative">
                                        <img src="{{ $image->url }}" 
                                             class="card-img-top" 
                                             alt="{{ $image->original_name }}"
                                             style="height: 100px; object-fit: cover;"
                                             onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                                        <div class="position-absolute top-0 end-0 m-1">
                                            <span class="badge bg-dark bg-opacity-75 small">
                                                {{ number_format($image->file_size / 1024, 0) }}KB
                                            </span>
                                        </div>
                                        <div class="position-absolute bottom-0 end-0 m-1">
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    onclick="deleteImage({{ $image->id }})"
                                                    title="Supprimer cette image">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-1">
                                        <p class="card-text small mb-0 text-truncate" title="{{ $image->original_name }}">
                                            {{ $image->original_name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Ajouter de nouvelles images -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-3">Ajouter de nouvelles images</h6>
                        <div class="upload-area border-2 border-dashed border-primary rounded p-4 text-center" 
                             id="image-upload-area"
                             ondrop="handleDrop(event)" 
                             ondragover="handleDragOver(event)" 
                             ondragleave="handleDragLeave(event)">
                            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                            <p class="mb-2">Glissez-déposez vos images ici ou</p>
                            <input type="file" id="new-images" name="new_images[]" multiple accept="image/*" class="d-none">
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('new-images').click()">
                                <i class="fas fa-plus me-1"></i>Sélectionner des images
                            </button>
                            <p class="small text-muted mt-2 mb-0">
                                Formats acceptés: JPG, PNG, GIF, WEBP (Max: 10MB par image)
                            </p>
                        </div>
                    </div>

                    <!-- Prévisualisation des nouvelles images -->
                    <div id="new-images-preview" class="row" style="display: none;">
                        <div class="col-12 mb-2">
                            <h6 class="text-muted">Nouvelles images à ajouter</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>
                        Informations
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block">Statut actuel</small>
                        <span class="badge bg-warning fs-6">En cours</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Date de création</small>
                        <span class="small">{{ date('d/m/Y à H:i', strtotime($project->created_at)) }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Dernière modification</small>
                        <span class="small">{{ date('d/m/Y à H:i', strtotime($project->updated_at)) }}</span>
                    </div>
                    <hr>
                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Une fois validé, ce projet ne pourra plus être modifié.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Variables globales
let newImages = [];
let imagesToDelete = [];

// Gestion du formulaire principal
document.getElementById('editProjectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    // Créer FormData manuellement (sans le @method('PUT') du formulaire)
    const formData = new FormData();
    
    // Ajouter le token CSRF
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    
    // Ajouter tous les champs du formulaire manuellement
    const formElements = this.elements;
    for (let i = 0; i < formElements.length; i++) {
        const element = formElements[i];
        if (element.name && element.name !== '_method' && element.type !== 'submit') {
            if (element.type === 'checkbox' || element.type === 'radio') {
                if (element.checked) {
                    formData.append(element.name, element.value);
                }
            } else if (element.type !== 'file') {
                formData.append(element.name, element.value);
            }
        }
    }
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Récupérer TOUS les fichiers (input HTML + drag & drop)
    const fileInput = document.getElementById('new-images');
    let allFiles = [];
    
    // Ajouter les fichiers de l'input HTML
    if (fileInput.files.length > 0) {
        allFiles = allFiles.concat(Array.from(fileInput.files));
    }
    
    // Ajouter les fichiers drag & drop (éviter les doublons)
    if (newImages.length > 0) {
        newImages.forEach(file => {
            // Vérifier si le fichier n'est pas déjà dans allFiles
            const isDuplicate = allFiles.some(existingFile => 
                existingFile.name === file.name && existingFile.size === file.size
            );
            if (!isDuplicate) {
                allFiles.push(file);
            }
        });
    }
    
    // Ajouter tous les fichiers au FormData
    allFiles.forEach((file, index) => {
        formData.append(`new_images[]`, file);
    });
    
    // Ajouter les IDs des images à supprimer
    imagesToDelete.forEach((imageId, index) => {
        formData.append(`delete_images[${index}]`, imageId);
    });
    
    // Désactiver le bouton et afficher un loader
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Enregistrement...';
    
    // Nettoyer les erreurs précédentes
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    
    const url = `{{ route('design-graphique.tp.update.images', $project->id) }}`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error(`HTTP ${response.status}: ${text}`);
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Succès !',
                    text: data.message || 'Projet modifié avec succès',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = `{{ route('design-graphique.tp.voir', $project->id) }}`;
                });
            } else {
                alert('Projet modifié avec succès');
                window.location.href = `{{ route('design-graphique.tp.voir', $project->id) }}`;
            }
        } else {
            console.log('Server returned success=false:', data);
            if (data.errors) {
                console.log('Validation errors:', data.errors);
                // Afficher les erreurs de validation
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById(field);
                    const feedback = input ? input.nextElementSibling : null;
                    if (input && feedback && feedback.classList.contains('invalid-feedback')) {
                        input.classList.add('is-invalid');
                        feedback.textContent = data.errors[field][0];
                    }
                });
            }
            const errorMessage = data.message || 'Une erreur est survenue';
            if (typeof Swal !== 'undefined') {
                Swal.fire('Erreur !', errorMessage, 'error');
            } else {
                alert('Erreur: ' + errorMessage);
            }
        }
    })
    .catch(error => {
        console.error('Request failed:', error);
        const errorMessage = 'Erreur de connexion: ' + error.message;
        if (typeof Swal !== 'undefined') {
            Swal.fire('Erreur !', errorMessage, 'error');
        } else {
            alert(errorMessage);
        }
    })
    .finally(() => {
        // Réactiver le bouton dans tous les cas
        console.log('Re-enabling button');
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});



// Gestion des nouvelles images
document.getElementById('new-images').addEventListener('change', function(e) {
    // Vider le tableau newImages pour éviter les doublons
    newImages = [];
    
    // Ajouter les fichiers sélectionnés au tableau newImages
    Array.from(e.target.files).forEach(file => {
        newImages.push(file);
        addImagePreview(file);
    });
    
    // Afficher la zone de prévisualisation
    if (e.target.files.length > 0) {
        document.getElementById('new-images-preview').style.display = 'block';
    } else {
        document.getElementById('new-images-preview').style.display = 'none';
    }
});

// Gestion du drag & drop
function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
    document.getElementById('image-upload-area').classList.add('border-success', 'bg-light');
}

function handleDragLeave(e) {
    e.preventDefault();
    document.getElementById('image-upload-area').classList.remove('border-success', 'bg-light');
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('image-upload-area').classList.remove('border-success', 'bg-light');
    handleFiles(e.dataTransfer.files);
}

function handleFiles(files) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    
    Array.from(files).forEach(file => {
        if (!allowedTypes.includes(file.type)) {
            Swal.fire('Erreur', `Le fichier "${file.name}" n'est pas un format d'image valide.`, 'error');
            return;
        }
        
        if (file.size > maxSize) {
            Swal.fire('Erreur', `Le fichier "${file.name}" est trop volumineux (max: 10MB).`, 'error');
            return;
        }
        
        newImages.push(file);
        addImagePreview(file);
    });
}

function addImagePreview(file) {
    const previewContainer = document.getElementById('new-images-preview');
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const imageIndex = newImages.length - 1;
        const imageHtml = `
            <div class="col-6 mb-3" id="new-image-${imageIndex}">
                <div class="card border-0 shadow-sm">
                    <div class="position-relative">
                        <img src="${e.target.result}" 
                             class="card-img-top" 
                             alt="${file.name}"
                             style="height: 100px; object-fit: cover;">
                        <div class="position-absolute top-0 end-0 m-1">
                            <span class="badge bg-success">Nouveau</span>
                        </div>
                        <div class="position-absolute bottom-0 end-0 m-1">
                            <button type="button" class="btn btn-danger btn-sm" 
                                    onclick="removeNewImage(${imageIndex})"
                                    title="Retirer cette image">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-1">
                        <p class="card-text small mb-0 text-truncate" title="${file.name}">
                            ${file.name}
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        previewContainer.insertAdjacentHTML('beforeend', imageHtml);
        previewContainer.style.display = 'flex';
    };
    
    reader.readAsDataURL(file);
}

function removeNewImage(index) {
    newImages.splice(index, 1);
    document.getElementById(`new-image-${index}`).remove();
    
    if (newImages.length === 0) {
        document.getElementById('new-images-preview').style.display = 'none';
    }
}

// Supprimer une image existante
function deleteImage(imageId) {
    Swal.fire({
        title: 'Supprimer cette image ?',
        text: "Cette action est irréversible !",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimer !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            // Ajouter l'ID à la liste des images à supprimer
            imagesToDelete.push(imageId);
            
            // Masquer l'image visuellement
            const imageElement = document.getElementById(`image-${imageId}`);
            imageElement.style.opacity = '0.5';
            imageElement.querySelector('img').style.filter = 'grayscale(100%)';
            
            // Remplacer le bouton supprimer par un indicateur
            const deleteBtn = imageElement.querySelector('.btn-danger');
            deleteBtn.outerHTML = '<span class="badge bg-danger">À supprimer</span>';
            
            Swal.fire('Marqué pour suppression', 'L\'image sera supprimée lors de la sauvegarde.', 'success');
        }
    });
}
</script>
@endsection
