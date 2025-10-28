@extends('layouts.admin')

@section('title', 'Liste des Administrateurs - EVC')

@push('styles')
<style>
.page-header {
    background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.stats-card {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.role-section {
    background: rgba(255,255,255,0.05);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.1);
}

.role-header {
    padding: 1rem 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.admin-card {
    background: rgba(255,255,255,0.08);
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border: 1px solid rgba(255,255,255,0.1);
    transition: all 0.3s ease;
}

.admin-card:hover {
    background: rgba(255,255,255,0.12);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.permission-badge {
    background: rgba(255,255,255,0.15);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    margin: 0.25rem;
    display: inline-block;
    border: 1px solid rgba(255,255,255,0.2);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: white;
}

.back-button {
    background: linear-gradient(45deg, #6c757d, #5a6268);
    border: none;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
    display: inline-block;
}

.back-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    color: white;
    text-decoration: none;
}

.no-admins {
    text-align: center;
    padding: 2rem;
    color: rgba(255,255,255,0.6);
    font-style: italic;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="text-white mb-2">
                    <i class="fas fa-user-shield me-2"></i>
                    Liste des Administrateurs
                </h1>
                <p class="text-white-50 mb-0">Gestion des rôles et permissions des administrateurs</p>
            </div>
            <div>
                <a href="{{ route('admin.admins.create') }}" class="btn btn-success me-2" style="background: linear-gradient(45deg, #28a745, #20c997); border: none; padding: 0.75rem 1.5rem; border-radius: 10px; font-weight: 500;">
                    <i class="fas fa-plus me-2"></i>Ajouter un Administrateur
                </a>
                <a href="{{ route('admin.dashboard') }}" class="back-button">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques Globales -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <i class="fas fa-users-cog fa-3x mb-3" style="opacity: 0.8; color: white;"></i>
                <h2 class="stat-number">{{ $stats['total_admins'] }}</h2>
                <p class="text-white mb-0">Total Administrateurs</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
                <i class="fas fa-crown fa-3x mb-3" style="opacity: 0.8; color: white;"></i>
                <h2 class="stat-number">{{ $stats['total_super_admins'] }}</h2>
                <p class="text-white mb-0">Super Admins</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);">
                <i class="fas fa-user-tie fa-3x mb-3" style="opacity: 0.8; color: white;"></i>
                <h2 class="stat-number">{{ $stats['total_assistants'] }}</h2>
                <p class="text-white mb-0">Assistants</p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card text-center" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%);">
                <i class="fas fa-calculator fa-3x mb-3" style="opacity: 0.8; color: white;"></i>
                <h2 class="stat-number">{{ $stats['total_comptables'] }}</h2>
                <p class="text-white mb-0">Comptables</p>
            </div>
        </div>
    </div>

    @foreach(['super_admin', 'assistant', 'comptable'] as $roleKey)
        @if($adminsByRole[$roleKey]->count() > 0)
        <div class="role-section">
            <div class="role-header text-white" style="background: linear-gradient(135deg, {{ $permissions[$roleKey]['color'] }} 0%, {{ $permissions[$roleKey]['color'] }}dd 100%);">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-1">
                            @if($roleKey === 'super_admin')
                                <i class="fas fa-crown me-2"></i>
                            @elseif($roleKey === 'moderator')
                                <i class="fas fa-user-tie me-2"></i>
                            @else
                                <i class="fas fa-user-shield me-2"></i>
                            @endif
                            {{ $permissions[$roleKey]['label'] }}
                        </h3>
                        <p class="mb-0 small" style="opacity: 0.9;">{{ $permissions[$roleKey]['description'] }}</p>
                    </div>
                    <span class="badge bg-light text-dark fs-5 px-3 py-2">
                        {{ $adminsByRole[$roleKey]->count() }} {{ $adminsByRole[$roleKey]->count() > 1 ? 'personnes' : 'personne' }}
                    </span>
                </div>
            </div>

            <!-- Permissions -->
            <div class="mb-3">
                <h6 class="text-white mb-2"><i class="fas fa-key me-2"></i>Permissions & Accès :</h6>
                <div>
                    @foreach($permissions[$roleKey]['access'] as $permission)
                        <span class="permission-badge text-white">
                            <i class="fas fa-check-circle me-1"></i>{{ $permission }}
                        </span>
                    @endforeach
                </div>
            </div>

            <!-- Liste des admins -->
            <div class="row">
                @foreach($adminsByRole[$roleKey] as $admin)
                <div class="col-md-6 mb-3">
                    <div class="admin-card text-white">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <div style="width: 50px; height: 50px; background: linear-gradient(135deg, {{ $permissions[$roleKey]['color'] }} 0%, {{ $permissions[$roleKey]['color'] }}dd 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user fa-lg"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $admin->name }}</h5>
                                <p class="mb-1 small" style="opacity: 0.8;">
                                    <i class="fas fa-envelope me-1"></i>{{ $admin->email }}
                                </p>
                                @if($admin->last_login_at)
                                <p class="mb-1 small" style="opacity: 0.8;">
                                    <i class="fas fa-clock me-1"></i>Dernière connexion : {{ \Carbon\Carbon::parse($admin->last_login_at)->format('d/m/Y H:i') }}
                                </p>
                                @endif
                                <p class="mb-0 small" style="opacity: 0.7;">
                                    <i class="fas fa-calendar me-1"></i>Membre depuis {{ \Carbon\Carbon::parse($admin->created_at)->format('d/m/Y') }}
                                </p>
                            </div>
                            @if(session('admin_role') === 'super_admin')
                            <div class="ms-3">
                                <a href="{{ route('admin.admins.edit', $admin->id) }}" 
                                   class="btn btn-sm btn-warning mb-2" 
                                   style="background: linear-gradient(45deg, #ffc107, #ff9800); border: none; padding: 0.4rem 0.8rem; border-radius: 8px; width: 100%;">
                                    <i class="fas fa-edit me-1"></i>Modifier
                                </a>
                                @if($admin->id != session('admin_id'))
                                <form action="{{ route('admin.admins.destroy', $admin->id) }}" method="POST" class="d-inline" onsubmit="return confirm('\u00cates-vous s\u00fbr de vouloir supprimer cet administrateur ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger" 
                                            style="background: linear-gradient(45deg, #dc3545, #c82333); border: none; padding: 0.4rem 0.8rem; border-radius: 8px; width: 100%;">
                                        <i class="fas fa-trash me-1"></i>Supprimer
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    @endforeach

    @if($stats['total_admins'] === 0)
    <div class="role-section">
        <div class="no-admins">
            <i class="fas fa-users-slash fa-4x mb-3" style="opacity: 0.3;"></i>
            <h4 class="text-white-50">Aucun administrateur trouvé</h4>
            <p class="text-white-50">Il n'y a actuellement aucun administrateur dans le système.</p>
        </div>
    </div>
    @endif

    <!-- Bouton retour en bas -->
    <div class="text-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="back-button">
            <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
        </a>
    </div>
</div>
@endsection
