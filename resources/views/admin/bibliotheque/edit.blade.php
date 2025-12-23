@extends('layouts.admin')

@section('title', 'Modifier le Média')

@section('content')
<div class="container-fluid py-4">
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header">
            <h1 class="text-white">Modifier le Média : {{ $item->title }}</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.bibliotheque.update', $item) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label text-white">Titre</label>
                    <input type="text" class="form-control bg-dark text-white" id="title" name="title" value="{{ old('title', $item->title) }}" required>
                </div>

                <div class="mb-3">
                    <label for="library_category_id" class="form-label text-white">Catégorie</label>
                    <select class="form-select bg-dark text-white" id="library_category_id" name="library_category_id">
                        <option value="">Aucune catégorie</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ (old('library_category_id', $item->library_category_id) == $category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Fichier actuel -->
                <div class="mb-3">
                    <label class="form-label text-white">Fichier actuel</label>
                    <div class="p-3 rounded" style="background-color: #2d3748;">
                        @if(in_array(strtolower($item->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                            <img src="{{ \App\Models\MediaUrl::fromPath($item->path) }}" alt="{{ $item->title }}" class="img-fluid rounded mb-2" style="max-height: 200px;">
                        @else
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-alt fa-3x text-primary me-3"></i>
                                <div>
                                    <p class="text-white mb-1"><strong>{{ $item->name }}</strong></p>
                                    <small class="text-muted">Type: {{ strtoupper($item->file_type) }}</small>
                                </div>
                            </div>
                        @endif
                        <div class="mt-2">
                            <a href="{{ \App\Models\MediaUrl::fromPath($item->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt me-1"></i>Voir le fichier
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Remplacer le fichier -->
                <div class="mb-3">
                    <label for="file" class="form-label text-white">
                        <i class="fas fa-upload me-2"></i>Remplacer le fichier (optionnel)
                    </label>
                    <input type="file" class="form-control bg-dark text-white" id="file" name="file" accept="image/*,.pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx">
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle me-1"></i>
                        Formats acceptés: Images (JPG, PNG, GIF, SVG, WEBP), Documents (PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX). Max: 10 Mo
                    </small>
                </div>

                <!-- Lien externe -->
                <div class="mb-3">
                    <label for="external_link" class="form-label text-white">
                        <i class="fas fa-link me-2"></i>Lien externe (optionnel)
                    </label>
                    <input type="url" class="form-control bg-dark text-white" id="external_link" name="external_link" value="{{ old('external_link', $item->external_link ?? '') }}" placeholder="https://exemple.com/document.pdf">
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle me-1"></i>
                        Si vous fournissez un lien externe, il sera utilisé à la place du fichier uploadé
                    </small>
                </div>

                <!-- Image de couverture actuelle -->
                @if($item->cover_image)
                <div class="mb-3">
                    <label class="form-label text-white">Image de couverture actuelle</label>
                    <div class="p-3 rounded" style="background-color: #2d3748;">
                        <img src="{{ \App\Models\MediaUrl::fromPath($item->cover_image) }}" alt="Couverture" class="img-fluid rounded mb-2" style="max-height: 200px;">
                        <div class="mt-2">
                            <a href="{{ \App\Models\MediaUrl::fromPath($item->cover_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-external-link-alt me-1"></i>Voir l'image
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Remplacer l'image de couverture -->
                <div class="mb-3">
                    <label for="cover_image" class="form-label text-white">
                        <i class="fas fa-image me-2"></i>{{ $item->cover_image ? 'Remplacer l\'image de couverture (optionnel)' : 'Ajouter une image de couverture (optionnel)' }}
                    </label>
                    <input type="file" class="form-control bg-dark text-white" id="cover_image" name="cover_image" accept="image/*" onchange="previewCoverImage(event)">
                    <small class="text-muted d-block mt-1">
                        <i class="fas fa-info-circle me-1"></i>
                        Formats acceptés: JPG, PNG, GIF, SVG, WEBP. Max: 2 Mo
                    </small>
                    <!-- Prévisualisation -->
                    <div id="cover-preview" class="mt-3" style="display: none;">
                        <img id="cover-preview-img" src="" alt="Prévisualisation" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Destinataire(s)</label>
                    <div class="p-3 rounded" style="background-color: #2d3748;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="design-graphique" id="dest_design" {{ in_array('design-graphique', old('recipients', $item->recipients ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="dest_design">Design Graphique</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="community-management" id="dest_cm" {{ in_array('community-management', old('recipients', $item->recipients ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="dest_cm">Community Management</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="design-graphique-community-manager" id="dest_dgcm" {{ in_array('design-graphique-community-manager', old('recipients', $item->recipients ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="dest_dgcm">Design Graphique & Community Manager</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="intelligence-artificielle" id="dest_ia" {{ in_array('intelligence-artificielle', old('recipients', $item->recipients ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="dest_ia">Intelligence Artificielle</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="gestion-informatique" id="dest_gi" {{ in_array('gestion-informatique', old('recipients', $item->recipients ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="dest_gi">Gestion Informatique</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Affichage</label>
                    <div class="p-3 rounded" style="background-color: #2d3748;">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="is_featured">
                                <i class="fas fa-star text-warning me-2"></i>
                                <strong>Mettre à la UNE</strong>
                                <small class="d-block text-white mt-1">Ce média sera affiché en vedette sur la page de la bibliothèque</small>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.bibliotheque.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewCoverImage(event) {
    const file = event.target.files[0];
    const previewContainer = document.getElementById('cover-preview');
    const previewImg = document.getElementById('cover-preview-img');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.style.display = 'none';
    }
}
</script>
@endsection
