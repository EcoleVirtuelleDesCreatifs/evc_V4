@extends('layouts.admin')

@section('title', 'Créer une catégorie')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">
            <i class="fas fa-folder-plus me-2"></i>Créer une catégorie
        </h1>
        <a href="{{ route('admin.boutique.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour aux produits
        </a>
    </div>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <form action="{{ route('admin.boutique.categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label text-white">Nom de la catégorie</label>
                    <input type="text" class="form-control bg-dark text-white border-secondary" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <label class="form-check-label text-white" for="is_active">Catégorie active</label>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Enregistrer
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
