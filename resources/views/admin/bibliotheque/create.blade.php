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
                    <label for="file" class="form-label text-white">Fichier</label>
                    <input type="file" class="form-control bg-dark text-white @error('file') is-invalid @enderror" id="file" name="file" required>
                    @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="download_url" class="form-label text-white">Lien de téléchargement (Optionnel)</label>
                    <input type="url" class="form-control bg-dark text-white @error('download_url') is-invalid @enderror" id="download_url" name="download_url" value="{{ old('download_url') }}" placeholder="https://...">
                    @error('download_url')
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
