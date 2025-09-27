@extends('layouts.admin')

@section('title', 'Gestion des Catégories')

@push('styles')
<style>
    th, td { vertical-align: middle; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="text-white">Gestion des Catégories</h1>
        <a href="{{ route('admin.formations.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Ajouter une catégorie</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Slug</th>
                            <th>Formations</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>
                                    <span class="badge bg-primary rounded-pill">{{ $category->formations_count }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                    <button class="btn btn-sm btn-danger">Supprimer</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">Aucune catégorie trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
