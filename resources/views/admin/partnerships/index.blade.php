@extends('layouts.admin')

@section('title', 'Partenariats')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h4 mb-1">Partenariats</h1>
            <div class="text-muted small">Gestion de la top bar et des courriers partenaires</div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-2">Erreurs</div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Statut</th>
                            <th>Slug</th>
                            <th>Nom</th>
                            <th>Document</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($partnerships as $p)
                            <tr>
                                <td>
                                    @if($p->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td><span class="badge bg-light text-dark">{{ $p->slug }}</span></td>
                                <td>
                                    <div class="fw-bold">{{ $p->name }}</div>
                                    <div class="small text-muted">{{ $p->prefix }} {{ $p->subtitle }}</div>
                                </td>
                                <td>
                                    @if(!empty($p->document_path))
                                        <a class="small" target="_blank" href="{{ url('/storage/app/public/' . ltrim($p->document_path, '/')) }}">Voir PDF</a>
                                    @else
                                        <span class="text-muted small">Aucun</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.partnerships.edit', $p) }}" class="btn btn-sm btn-primary">
                                        Modifier
                                    </a>
                                    <a href="{{ route('partnerships.show', $p->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        Page publique
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Aucun partenariat</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
