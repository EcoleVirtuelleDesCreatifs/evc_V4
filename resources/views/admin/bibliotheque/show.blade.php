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
                    @if($item->cover_image)
                        {{-- Afficher l'image de couverture si elle existe --}}
                        <img src="{{ $item->cover_image_url }}" alt="{{ $item->title }}" class="img-fluid rounded shadow-sm">
                    @elseif(in_array(strtolower($item->file_type), ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']))
                        {{-- Sinon, afficher le fichier principal s'il est une image --}}
                        <img src="{{ $item->file_url }}" alt="{{ $item->title }}" class="img-fluid rounded shadow-sm">
                    @else
                        {{-- Sinon, afficher une icône de fichier --}}
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

                    <div class="mt-3">
                        <a href="{{ $item->file_url }}" class="btn btn-secondary me-2" download>
                            <i class="fas fa-image me-2"></i>Télécharger la couverture
                        </a>
                        @if($item->pdf_path)
                            <a href="{{ $item->pdf_url }}" class="btn btn-primary" download>
                                <i class="fas fa-file-pdf me-2"></i>Télécharger le PDF
                            </a>
                        @else
                            <button class="btn btn-secondary" disabled>
                                <i class="fas fa-file-pdf me-2"></i>PDF non disponible
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
