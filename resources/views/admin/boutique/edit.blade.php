@extends('layouts.admin')

@section('title', 'Modifier le produit')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="text-white mb-1">
                <i class="fas fa-edit me-2"></i>Modifier le produit
            </h1>
            <p class="text-muted mb-0">Mettez à jour les informations du produit</p>
        </div>
        <a href="{{ route('admin.boutique.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <form action="{{ route('admin.boutique.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Informations de base -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations de base</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="title" class="form-label">Titre du produit <span class="text-danger">*</span></label>
                            <input type="text" class="form-control modern-input @error('title') is-invalid @enderror"
                                   id="title" name="title" value="{{ old('title', $product->title) }}" required
                                   placeholder="Ex: Pack Création Logo Premium">
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="summary" class="form-label">Résumé</label>
                            <input type="text" class="form-control modern-input @error('summary') is-invalid @enderror"
                                   id="summary" name="summary" value="{{ old('summary', $product->summary) }}" maxlength="500"
                                   placeholder="Courte description du produit">
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Apparaît dans le catalogue</small>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description complète</label>
                            <textarea class="form-control modern-input @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="5"
                                      placeholder="Décrivez le produit en détail...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tarification et stock -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>Tarification et stock</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="price" class="form-label">Prix (FCFA) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control modern-input @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price', $product->price) }}" min="0" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="old_price" class="form-label">Ancien prix (FCFA)</label>
                                <input type="number" class="form-control modern-input @error('old_price') is-invalid @enderror"
                                       id="old_price" name="old_price" value="{{ old('old_price', $product->old_price) }}" min="0"
                                       placeholder="Pour les promotions">
                                @error('old_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-4">
                                <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" class="form-control modern-input @error('stock') is-invalid @enderror"
                                       id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="variants" class="form-label">Variantes (JSON)</label>
                            <textarea class="form-control modern-input @error('variants') is-invalid @enderror"
                                      id="variants" name="variants" rows="4"
                                      placeholder='[{"label":"Couleur","options":["Rouge","Bleu"]},{"label":"Taille","options":["S","M","L"]}]'>{{ old('variants', $product->variants ? json_encode($product->variants) : '') }}</textarea>
                            @error('variants')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Laissez vide si le produit n'a pas de variantes</small>
                        </div>
                    </div>
                </div>

                <!-- SEO / GEO -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-search me-2"></i>Optimisation SEO / GEO</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="seo_geo" class="form-label">Mots-clés et localisation</label>
                            <textarea class="form-control modern-input @error('seo_geo') is-invalid @enderror"
                                      id="seo_geo" name="seo_geo" rows="3"
                                      placeholder="Mots-clés, ville, pays...">{{ old('seo_geo', $product->seo_geo) }}</textarea>
                            @error('seo_geo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="col-lg-4">
                <!-- Publication -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Publication</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="category_id" class="form-label">Catégorie <span class="text-danger">*</span></label>
                            <select class="form-select modern-input @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Sélectionner une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <i class="fas fa-eye text-warning me-1"></i>Produit actif
                                </label>
                            </div>
                            <small class="text-muted d-block mt-2">Rendre le produit visible dans la boutique</small>
                        </div>
                    </div>
                </div>

                <!-- Livraison -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-truck me-2"></i>Livraison</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="delivery_mode" class="form-label">Mode de livraison <span class="text-danger">*</span></label>
                            <select class="form-select modern-input @error('delivery_mode') is-invalid @enderror" id="delivery_mode" name="delivery_mode" required>
                                <option value="cash_on_delivery" {{ old('delivery_mode', $product->delivery_mode) == 'cash_on_delivery' ? 'selected' : '' }}>Paiement à la livraison</option>
                                <option value="deposit" {{ old('delivery_mode', $product->delivery_mode) == 'deposit' ? 'selected' : '' }}>Dépôt de confirmation</option>
                            </select>
                            @error('delivery_mode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="delivery_cost" class="form-label">Coût de livraison (FCFA)</label>
                            <input type="number" class="form-control modern-input @error('delivery_cost') is-invalid @enderror"
                                   id="delivery_cost" name="delivery_cost" value="{{ old('delivery_cost', $product->delivery_cost) }}" min="0">
                            @error('delivery_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Adresse email</label>
                            <input type="email" class="form-control modern-input @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $product->email) }}"
                                   placeholder="contact@exemple.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="card modern-card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-images me-2"></i>Images du produit</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($product->images))
                            <div class="mb-3 d-flex flex-wrap gap-2">
                                @foreach($product->images as $img)
                                    <div class="text-center">
                                        <img src="{{ \App\Models\MediaUrl::fromPath($img) }}" alt="{{ $product->title }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="form-check mt-1">
                                            <input type="checkbox" name="remove_images[]" value="{{ $img }}" class="form-check-input" id="remove-img-{{ $loop->index }}">
                                            <label class="form-check-label" for="remove-img-{{ $loop->index }}" style="font-size: 0.7rem;">Supprimer</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="images" class="form-label">Ajouter des images</label>
                            <input type="file" class="form-control modern-input @error('images.*') is-invalid @enderror"
                                   id="images" name="images[]" accept="image/*" multiple>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">Cochez les images existantes pour les supprimer. La première image est principale.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card modern-card">
                    <div class="card-body">
                        <div class="d-flex gap-3 justify-content-end">
                            <a href="{{ route('admin.boutique.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<style>
    .modern-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid #334155;
        border-radius: 16px;
        overflow: hidden;
    }

    .modern-card .card-header {
        background: rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid #334155;
        padding: 1.25rem 1.5rem;
    }

    .modern-card .card-header h5 {
        color: #4fc3f7;
        font-weight: 600;
        margin: 0;
    }

    .modern-card .card-body {
        padding: 1.5rem;
    }

    .modern-input {
        background: rgba(255, 255, 255, 0.05);
        border: 2px solid #334155;
        border-radius: 12px;
        color: white;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .modern-input:focus {
        background: rgba(255, 255, 255, 0.08);
        border-color: #4fc3f7;
        box-shadow: 0 0 0 0.2rem rgba(79, 195, 247, 0.25);
        color: white;
    }

    .modern-input::placeholder {
        color: #64748b;
    }

    .form-label {
        color: #cbd5e1;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    .form-check-input:checked {
        background-color: #4fc3f7;
        border-color: #4fc3f7;
    }

    .form-check-label {
        color: #cbd5e1;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(79, 195, 247, 0.4);
    }

    .btn-outline-secondary {
        border: 2px solid #334155;
        color: #cbd5e1;
        border-radius: 12px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-outline-secondary:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: #4fc3f7;
        color: #4fc3f7;
    }
</style>
@endpush
@endsection
