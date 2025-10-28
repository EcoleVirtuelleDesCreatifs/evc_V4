@extends('layouts.admin')

@section('title', 'Gestion des Programmes')

@push('styles')
<link href="{{ asset('css/admin/formation-create.css') }}?v={{ time() }}" rel="stylesheet" />
<style>
    .programme-card {
        background: var(--form-surface);
        border: 1px solid var(--form-border);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }
    
    .programme-card:hover {
        border-color: var(--form-primary);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(56, 189, 248, 0.2);
    }
    
    .pdf-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
</style>
@endpush

@section('content')

<div class="interactive-dashboard-form">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 style="color: var(--form-text); font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                        <i class="fas fa-book me-3"></i>
                        Gestion des Programmes
                    </h1>
                    <p style="color: var(--form-text-muted); margin: 0;">
                        Ajoutez et gérez les programmes de formation
                    </p>
                </div>
                <a href="{{ route('admin.programmes.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-2"></i>
                    Ajouter un programme
                </a>
            </div>
        </div>
    </div>

    <!-- Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Liste des programmes -->
    <div class="row">
        <div class="col-12">
            @if($programmes->isEmpty())
                <div style="text-align: center; padding: 4rem 2rem; color: var(--form-text-muted);">
                    <i class="fas fa-inbox" style="font-size: 4rem; color: var(--form-border); margin-bottom: 1rem;"></i>
                    <h3 style="color: var(--form-text); margin-top: 1rem;">Aucun programme</h3>
                    <p>Commencez par ajouter un programme de formation</p>
                    <a href="{{ route('admin.programmes.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i>
                        Ajouter un programme
                    </a>
                </div>
            @else
                <div class="row g-3">
                    @foreach($programmes as $programme)
                        <div class="col-md-6 col-lg-4">
                            <div class="programme-card">
                                <div class="d-flex gap-3 mb-3">
                                    <div class="pdf-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h4 style="color: var(--form-text); font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">
                                            {{ $programme->titre }}
                                        </h4>
                                        <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--form-text-muted); font-size: 0.875rem;">
                                            <i class="fas fa-graduation-cap"></i>
                                            <span>{{ $programme->formation }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($programme->description)
                                    <p style="color: var(--form-text-muted); font-size: 0.875rem; margin-bottom: 1rem;">
                                        {{ Str::limit($programme->description, 100) }}
                                    </p>
                                @endif
                                
                                <div style="display: flex; justify-content: between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--form-border);">
                                    <small style="color: var(--form-text-muted);">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($programme->created_at)->format('d/m/Y') }}
                                    </small>
                                    <div class="d-flex gap-2 ms-auto">
                                        <a href="{{ asset('storage/' . $programme->fichier_pdf) }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="{{ route('admin.programmes.destroy', $programme->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce programme ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>



@endsection
