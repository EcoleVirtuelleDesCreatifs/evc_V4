@extends('layouts.admin')

@section('title', 'Modifier le produit')
@section('page-title', 'Modifier un produit')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-edit me-2"></i>Modifier le produit
        </h1>
        <a href="{{ route('admin.boutique.index') }}" class="btn btn-outline-light">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <form action="{{ route('admin.boutique.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label text-white">Titre du produit <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $product->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="summary" class="form-label text-white">Résumé</label>
                            <input type="text" name="summary" id="summary" class="form-control @error('summary') is-invalid @enderror" value="{{ old('summary', $product->summary) }}" maxlength="500">
                            @error('summary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label text-white">Description</label>
                            <textarea name="description" id="description" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="seo_geo" class="form-label text-white">SEO / GEO optimisé</label>
                            <textarea name="seo_geo" id="seo_geo" rows="3" class="form-control @error('seo_geo') is-invalid @enderror" placeholder="Mots-clés, localisation...">{{ old('seo_geo', $product->seo_geo) }}</textarea>
                            @error('seo_geo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="category_id" class="form-label text-white">Catégorie <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Choisir --</option>
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

                        <div class="mb-3">
                            <label for="price" class="form-label text-white">Prix (FCFA) <span class="text-danger">*</span></label>
                            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" min="0" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_mode" class="form-label text-white">Mode de livraison <span class="text-danger">*</span></label>
                            <select name="delivery_mode" id="delivery_mode" class="form-select @error('delivery_mode') is-invalid @enderror" required>
                                <option value="cash_on_delivery" {{ old('delivery_mode', $product->delivery_mode) == 'cash_on_delivery' ? 'selected' : '' }}>Paiement à la livraison</option>
                                <option value="deposit" {{ old('delivery_mode', $product->delivery_mode) == 'deposit' ? 'selected' : '' }}>Dépôt de confirmation</option>
                            </select>
                            @error('delivery_mode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="delivery_cost" class="form-label text-white">Coût de livraison (FCFA)</label>
                            <input type="number" name="delivery_cost" id="delivery_cost" class="form-control @error('delivery_cost') is-invalid @enderror" value="{{ old('delivery_cost', $product->delivery_cost) }}" min="0">
                            @error('delivery_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label text-white">Adresse email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $product->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Images du carousel</label>
                            @if(!empty($product->images))
                                <div class="mb-2 d-flex flex-wrap gap-2">
                                    @foreach($product->images as $img)
                                        <div class="text-center">
                                            <img src="{{ \App\Models\MediaUrl::fromPath($img) }}" alt="{{ $product->title }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" name="remove_images[]" value="{{ $img }}" class="form-check-input" id="remove-img-{{ $loop->index }}">
                                                <label class="form-check-label text-white-50" for="remove-img-{{ $loop->index }}" style="font-size: 0.7rem;">Supprimer</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <input type="file" name="images[]" id="images" class="form-control @error('images.*') is-invalid @enderror" accept="image/*" multiple>
                            <div class="form-text text-white-50">Ajoutez une ou plusieurs images. Cochez pour supprimer celles existantes.</div>
                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label for="is_active" class="form-check-label text-white">Produit actif</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route('admin.boutique.index') }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
