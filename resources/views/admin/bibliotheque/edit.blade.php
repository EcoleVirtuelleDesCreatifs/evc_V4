@extends('layouts.admin')

@section('title', 'Modifier le Média')

@section('content')
<div class="container-fluid py-4">
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header">
            <h1 class="text-white">Modifier le Média : {{ $item->title }}</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.bibliotheque.update', $item) }}" method="POST">
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
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="intelligence-artificielle" id="dest_ia" {{ in_array('intelligence-artificielle', old('recipients', $item->recipients ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="dest_ia">Intelligence Artificielle</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="recipients[]" value="gestion-informatique" id="dest_gi" {{ in_array('gestion-informatique', old('recipients', $item->recipients ?? [])) ? 'checked' : '' }}>
                            <label class="form-check-label text-white" for="dest_gi">Gestion Informatique</label>
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
@endsection
