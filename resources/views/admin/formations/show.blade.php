@extends('layouts.admin')

@section('title', 'Détail de la Formation: ' . $formation->name)

@push('styles')
<style>
    .details-card { background-color: #1e293b; border: 1px solid #334155; color: #f8fafc; }
    .details-header { border-bottom: 1px solid #334155; }
    .details-label { color: #94a3b8; font-weight: 600; }
    .details-value { color: #f8fafc; }
    .status-badge {
        display: inline-block;
        padding: .35em .65em;
        font-size: .85em;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
    }
    .status-badge.active { background-color: #198754; }
    .status-badge.draft { background-color: #6c757d; }
    .status-badge.inactive { background-color: #ffc107; color: #000; }
    .status-badge.archived { background-color: #dc3545; }
    .ratio iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <a href="{{ route('admin.formations.index') }}" class="btn btn-light mb-3"><i class="fas fa-arrow-left me-2"></i>Retour à la liste</a>
            <h1 class="text-white">{{ $formation->name }}</h1>
        </div>
        <div>
            <a href="{{ route('admin.formations.edit', $formation) }}" class="btn btn-warning"><i class="fas fa-edit me-2"></i>Modifier</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card details-card">
                <div class="card-header details-header">
                    <h4>Détails de la Formation</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="details-label">Catégorie</div>
                            <div class="details-value">{{ $formation->category->name ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="details-label">Module Principal</div>
                            <div class="details-value">{{ $formation->modules[0] ?? 'N/A' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="details-label">Statut</div>
                            <div class="details-value">
                                <span class="status-badge {{ $formation->status }}">{{ $formation->status_label }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="details-label">Type</div>
                            <div class="details-value">{{ $formation->format_label }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="details-label">Date de Création</div>
                            <div class="details-value">{{ $formation->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="details-label">Dernière Mise à Jour</div>
                            <div class="details-value">{{ $formation->updated_at->format('d/m/Y H:i') }}</div>
                        </div>
                         <div class="col-12 mt-3">
                            <div class="details-label">Description</div>
                            <div class="details-value bg-dark p-3 rounded">{!! $formation->description !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card details-card">
                 <div class="card-header details-header">
                    <h4>Média</h4>
                </div>
                <div class="card-body text-center">
                    @if($formation->image_url)
                        <img src="{{ asset('storage/' . $formation->image_url) }}" alt="{{ $formation->name }}" class="img-fluid rounded shadow-sm">
                    @else
                        <div style="height: 200px; background-color: #334155;" class="rounded shadow-sm d-flex align-items-center justify-content-center">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($formation->vimeo_code)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card details-card">
                <div class="card-header details-header">
                    <h4>Vidéo de Présentation</h4>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9">
                        {!! $formation->vimeo_code !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
