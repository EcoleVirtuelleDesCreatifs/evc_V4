@extends('layouts.admin')

@section('title', 'Gestion des Programmes')

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(30, 60, 114, 0.3);
    }
    
    .stat-card-primary {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
    }
    
    .stat-card-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .stat-card-warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    
    .stat-card-cyan {
        background: linear-gradient(135deg, #26c6da 0%, #00acc1 100%);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0;
    }
    
    .stat-label {
        margin: 0;
        opacity: 0.9;
        font-size: 0.95rem;
    }

    .btn-export {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-export:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .programme-card {
        background: #1e293b;
        border: 2px solid #334155;
        border-radius: 16px;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }
    
    .programme-card:hover {
        border-color: #4fc3f7;
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(79, 195, 247, 0.3);
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

    .formation-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge-design {
        background: linear-gradient(135deg, #1e3c72, #2a5298);
        color: white;
    }

    .badge-cm {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        color: white;
    }

    .badge-gi {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .badge-ia {
        background: linear-gradient(135deg, #26c6da, #00acc1);
        color: white;
    }

    .badge-tous {
        background: linear-gradient(135deg, #9c27b0, #7b1fa2);
        color: white;
    }

    .btn-download {
        background: linear-gradient(135deg, #4fc3f7, #29b6f6);
        border: none;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 195, 247, 0.4);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #1e293b;
        border-radius: 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #475569;
        margin-bottom: 1.5rem;
    }

    .empty-state h3 {
        color: #e2e8f0;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #94a3b8;
    }
</style>
@endpush

@section('content')

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-white mb-0">
            <i class="fas fa-book me-2"></i>Gestion des Programmes
        </h1>
        <a href="{{ route('admin.programmes.create') }}" class="btn-export">
            <i class="fas fa-plus me-2"></i>Ajouter un Programme
        </a>
    </div>

    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['total'] }}</h3>
                    <p class="stat-label">Total Programmes</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-success">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['ce_mois'] }}</h3>
                    <p class="stat-label">Ajoutés ce Mois</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['tous'] }}</h3>
                    <p class="stat-label">Toutes Formations</p>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['design_graphique'] + $stats['community_management'] + $stats['gestion_informatique'] + $stats['intelligence_artificielle'] }}</h3>
                    <p class="stat-label">Spécifiques</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par formation -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['design_graphique'] }}</h3>
                    <p class="stat-label">Design Graphique</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-primary">
                <div class="stat-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['community_management'] }}</h3>
                    <p class="stat-label">Community Management</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-warning">
                <div class="stat-icon">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['gestion_informatique'] }}</h3>
                    <p class="stat-label">Gestion Informatique</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card stat-card-cyan">
                <div class="stat-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-number">{{ $stats['intelligence_artificielle'] }}</h3>
                    <p class="stat-label">Intelligence Artificielle</p>
                </div>
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
    @if($programmes->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Aucun programme disponible</h3>
            <p>Commencez par ajouter un programme de formation</p>
            <a href="{{ route('admin.programmes.create') }}" class="btn-export mt-3">
                <i class="fas fa-plus me-2"></i>
                Ajouter un Programme
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($programmes as $programme)
                <div class="col-md-6 col-lg-4">
                    <div class="programme-card">
                        <div class="d-flex gap-3 mb-3">
                            <div class="pdf-icon">
                                <i class="fas fa-file-pdf"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 style="color: #e2e8f0; font-size: 1.1rem; font-weight: 600; margin-bottom: 0.5rem;">
                                    {{ $programme->titre }}
                                </h4>
                                @php
                                    $badgeClass = match($programme->formation) {
                                        'Design Graphique' => 'badge-design',
                                        'Community Management' => 'badge-cm',
                                        'Gestion Informatique' => 'badge-gi',
                                        'Intelligence Artificielle' => 'badge-ia',
                                        'Toutes' => 'badge-tous',
                                        default => 'badge-tous'
                                    };
                                @endphp
                                <span class="formation-badge {{ $badgeClass }}">
                                    {{ $programme->formation }}
                                </span>
                            </div>
                        </div>
                        
                        @if($programme->description)
                            <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;">
                                {{ Str::limit($programme->description, 100) }}
                            </p>
                        @endif
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #334155;">
                            <small style="color: #94a3b8;">
                                <i class="fas fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($programme->created_at)->format('d/m/Y') }}
                            </small>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/' . $programme->fichier_pdf) }}" target="_blank" class="btn-download btn-sm">
                                    <i class="fas fa-download me-1"></i>
                                    Télécharger
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



@endsection
