@extends('layouts.admin')

@section('title', 'Ajouter une Catégorie')

@section('content')
<div class="container-fluid py-4">
    <h1 class="text-white">Ajouter une Nouvelle Catégorie</h1>

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <form action="{{ route('admin.formations.categories.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label text-white">Nom de la catégorie</label>
                    <input type="text" class="form-control bg-dark text-white @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label text-white">Description</label>
                    <textarea class="form-control bg-dark text-white" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.formations.categories.index') }}" class="btn btn-secondary me-2">Annuler</a>
                    <button type="submit" class="btn btn-primary">Enregistrer la catégorie</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
