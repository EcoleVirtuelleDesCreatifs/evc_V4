@extends('layouts.admin')

@section('title', 'Ajouter un Média')

@section('content')
<div class="container-fluid py-4">


    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <form action="{{ route('admin.bibliotheque.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label text-white">Titre</label>
                    <input type="text" class="form-control bg-dark text-white @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="library_category_id" class="form-label text-white">Catégorie</label>
                    <select class="form-select bg-dark text-white" id="library_category_id" name="library_category_id">
                        <option value="">Aucune</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Destinataire(s)</label>
                    <div class="p-3 rounded" style="background-color: #2d3748;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="design-graphique" id="dest_design">
                            <label class="form-check-label text-white" for="dest_design">Design Graphique</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="community-management" id="dest_cm">
                            <label class="form-check-label text-white" for="dest_cm">Community Management</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="intelligence-artificielle" id="dest_ia">
                            <label class="form-check-label text-white" for="dest_ia">Intelligence Artificielle</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="gestion-informatique" id="dest_gi">
                            <label class="form-check-label text-white" for="dest_gi">Gestion Informatique</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Affichage</label>
                    <div class="p-3 rounded" style="background-color: #2d3748;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="is_featured">
                                <i class="fas fa-star text-warning me-2"></i>
                                <strong>Mettre à la UNE</strong>
                                <small class="d-block text-white mt-1">Ce média sera affiché en vedette sur la page de la bibliothèque</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="cover_image" class="form-label text-white">Couverture (image) <span class="text-danger">*</span></label>
                    <input type="file" class="form-control bg-dark text-white @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*" required onchange="previewCoverImage(event)">
                    <small class="text-muted">Formats acceptés : JPG, PNG, WebP (max 2 Mo)</small>
                    @error('cover_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <!-- Aperçu de l'image -->
                    <div id="cover-preview" class="mt-3" style="display: none;">
                        <label class="form-label text-white">Aperçu de la couverture :</label>
                        <div class="border border-secondary rounded p-2" style="background-color: #2d3748;">
                            <img id="preview-image" src="" alt="Aperçu" class="img-fluid rounded" style="max-height: 300px; object-fit: contain;">
                        </div>
                    </div>
                </div>
                <div class="alert alert-info" style="background-color: #2d3748; border-color: #4fc3f7; color: #fff;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Remarque :</strong> Vous devez fournir <strong>soit un fichier PDF, soit un lien de téléchargement</strong> (ou les deux).
                </div>

                <div class="mb-3">
                    <label for="pdf_file" class="form-label text-white">
                        <i class="fas fa-file-pdf me-2"></i>Joindre le fichier 
                        <span class="text-warning">(PDF, DOC, DOCX, etc.)</span>
                    </label>
                    <input type="file" class="form-control bg-dark text-white @error('pdf_file') is-invalid @enderror" id="pdf_file" name="pdf_file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                    <small class="text-muted">Formats acceptés : PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX (max 50 Mo)</small>
                    @error('pdf_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="text-center my-3">
                    <span class="badge bg-secondary">OU</span>
                </div>

                <div class="mb-3">
                    <label for="external_link" class="form-label text-white">
                        <i class="fas fa-link me-2"></i>Lien externe de téléchargement
                    </label>
                    <input type="url" class="form-control bg-dark text-white @error('external_link') is-invalid @enderror" id="external_link" name="external_link" value="{{ old('external_link') }}" placeholder="https://exemple.com/document.pdf">
                    <small class="text-muted">URL complète vers le fichier hébergé ailleurs</small>
                    @error('external_link')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.bibliotheque.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Ajouter le média</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Fonction pour prévisualiser l'image de couverture
function previewCoverImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('cover-preview');
    const previewImage = document.getElementById('preview-image');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
        previewImage.src = '';
    }
}

// Validation du formulaire : au moins un fichier OU un lien doit être fourni
document.querySelector('form').addEventListener('submit', function(e) {
    const pdfFile = document.getElementById('pdf_file');
    const externalLink = document.getElementById('external_link');
    
    const hasFile = pdfFile.files.length > 0;
    const hasLink = externalLink.value.trim() !== '';
    
    if (!hasFile && !hasLink) {
        e.preventDefault();
        alert('⚠️ Vous devez fournir soit un fichier, soit un lien de téléchargement !');
        
        // Mettre en évidence les champs
        pdfFile.classList.add('is-invalid');
        externalLink.classList.add('is-invalid');
        
        return false;
    }
    
    // Retirer les classes d'erreur si la validation passe
    pdfFile.classList.remove('is-invalid');
    externalLink.classList.remove('is-invalid');
});

// Retirer les classes d'erreur quand l'utilisateur modifie les champs
document.getElementById('pdf_file').addEventListener('change', function() {
    this.classList.remove('is-invalid');
});

document.getElementById('external_link').addEventListener('input', function() {
    this.classList.remove('is-invalid');
});
</script>
@endpush
