@extends('layouts.admin')

@section('title', 'Détails du Média')

@section('content')
<div class="container-fluid py-4">
    <div class="card" style="background-color: #1e293b; border: 1px solid #334155;">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="text-white">Détails du Média : {{ $item->title }}</h1>
            <a href="{{ route('admin.bibliotheque.index') }}" class="btn btn-secondary">Retour à la liste</a>
        </div>
        <div class="card-body text-white">
            <div class="row">
                <div class="col-md-4">
                    @if(in_array(strtolower($item->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                        <img src="{{ asset('storage/' . $item->path) }}" alt="{{ $item->title }}" class="img-fluid rounded shadow-sm">
                    @else
                        <div style="height: 200px; background-color: #334155;" class="rounded shadow-sm d-flex align-items-center justify-content-center">
                            <i class="fas fa-file-alt text-white fa-4x"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h3>{{ $item->title }}</h3>
                    <p><strong class="text-white-50">Nom du fichier :</strong> {{ $item->name }}</p>
                    <p><strong class="text-white-50">Catégorie :</strong> {{ $item->libraryCategory->name ?? 'N/A' }}</p>
                    <p><strong class="text-white-50">Type :</strong> <span class="badge bg-secondary">{{ strtoupper($item->file_type) }}</span></p>
                    <p><strong class="text-white-50">Taille :</strong> {{ number_format($item->size / 1024, 2) }} KB</p>
                    <p><strong class="text-white-50">Date d'ajout :</strong> {{ $item->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong class="text-white-50">Statut :</strong> 
                        @if($item->status == 'active')
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-danger">Inactif</span>
                        @endif
                    </p>
                    @if(!empty($item->recipients))
                        <p><strong class="text-white-50">Destinataires :</strong>
                            @foreach($item->recipients as $recipient)
                                <span class="badge bg-info me-1">{{ ucfirst(str_replace('_', ' ', $recipient)) }}</span>
                            @endforeach
                        </p>
                    @endif
                    <a href="{{ asset('storage/' . $item->path) }}" class="btn btn-primary mt-3" target="_blank"><i class="fas fa-download me-2"></i>Télécharger</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
