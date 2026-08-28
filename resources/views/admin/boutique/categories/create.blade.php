@extends('layouts.admin')

@section('title', 'Gestion des catégories')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-white">
            <i class="fas fa-folder-plus me-2"></i>Gestion des catégories
        </h1>
        <a href="{{ route('admin.boutique.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour aux produits
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: rgba(255,255,255,0.05); color: white;">
            <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Créer une catégorie</h5>
        </div>
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

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header" style="background-color: rgba(255,255,255,0.05); color: white;">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Liste des catégories</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-dark table-hover mb-0 align-middle" style="--bs-table-bg: transparent; --bs-table-border-color: #334155;">
                    <thead style="background-color: rgba(255,255,255,0.05);">
                        <tr>
                            <th class="text-white ps-4">Nom</th>
                            <th class="text-white">Statut</th>
                            <th class="text-white text-end pe-4" style="width: 260px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="ps-4">
                                    <form action="{{ route('admin.boutique.categories.update', $category) }}" method="POST" id="edit-form-{{ $category->id }}" class="d-flex gap-2 align-items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ $category->name }}" required>
                                    </form>
                                </td>
                                <td>
                                    <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $category->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <button type="submit" form="edit-form-{{ $category->id }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-save me-1"></i>Modifier
                                        </button>
                                        <form action="{{ route('admin.boutique.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Supprimer cette catégorie ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash me-1"></i>Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <tr style="border: none;">
                                <td colspan="3" class="pt-0 pb-2 ps-4">
                                    <div class="form-check form-check-inline d-flex gap-2 align-items-center">
                                        <input type="checkbox" class="form-check-input" form="edit-form-{{ $category->id }}" id="is_active_{{ $category->id }}" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label text-white" for="is_active_{{ $category->id }}">Catégorie active</label>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    Aucune catégorie enregistrée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
