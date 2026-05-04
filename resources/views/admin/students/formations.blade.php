@extends('layouts.admin')

@section('title', 'Formations - ' . ($student->first_name ?? 'Étudiant') . ' ' . ($student->last_name ?? ''))

@push('styles')
<style>
    body { background: #0f172a; }

    .works-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        border-radius: 20px;
        padding: 1.5rem 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    .works-header-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(255,255,255,0.3);
    }
    .works-header-avatar-placeholder {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 700;
        border: 3px solid rgba(255,255,255,0.3);
    }

    .info-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .info-card-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        padding: 1rem 1.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
    }
    .info-card-body { padding: 1.5rem; }

    .stat-mini {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
    }
    .stat-mini h4 { margin-bottom: 0.25rem; }

    .table-modern { color: rgba(255,255,255,0.8); }
    .table-modern th {
        border-bottom: 1px solid rgba(255,255,255,0.1);
        color: rgba(255,255,255,0.5);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.75rem;
    }
    .table-modern td {
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding: 0.75rem;
        vertical-align: middle;
    }
    .table-modern tr:hover { background: rgba(255,255,255,0.02); }

    .badge-modern {
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-success-modern { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .badge-warning-modern { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; }
    .badge-danger-modern { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }
    .badge-info-modern { background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff; }

    .btn-modern { border: none; border-radius: 10px; padding: 0.4rem 0.75rem; font-weight: 600; transition: all 0.3s ease; }
    .btn-primary-modern { background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%); color: white; }
    .btn-primary-modern:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(79,195,247,0.3); color: white; }

    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s ease forwards;
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }

    /* Tabs navigation */
    .works-tabs {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .works-tab {
        padding: 0.6rem 1.2rem;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.03);
        color: rgba(255,255,255,0.6);
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        font-size: 0.9rem;
    }
    .works-tab:hover { background: rgba(255,255,255,0.06); color: #fff; }
    .works-tab.active {
        background: linear-gradient(135deg, #4fc3f7 0%, #29b6f6 100%);
        color: #fff;
        border-color: transparent;
    }
    .works-tab .tab-count {
        display: inline-block;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        padding: 0.1rem 0.5rem;
        font-size: 0.75rem;
        margin-left: 0.4rem;
    }
    .works-panel { display: none; }
    .works-panel.active { display: block; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4" style="max-width: 1400px;">

    {{-- Header --}}
    <div class="works-header fade-in">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.students.profile', $student->id ?? $user->id) }}" class="text-white text-decoration-none" title="Retour au profil">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                @if(!empty($student->profile_photo))
                    <img src="{{ \App\Helpers\ProfilePhotoHelper::getUrlOrDefault($student->profile_photo) }}" alt="" class="works-header-avatar">
                @else
                    <div class="works-header-avatar-placeholder">
                        {{ strtoupper(substr($student->first_name ?? '', 0, 1)) }}{{ strtoupper(substr($student->last_name ?? '', 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="mb-0 fw-bold">{{ $student->first_name ?? '' }} {{ $student->last_name ?? '' }}</h4>
                    <small class="opacity-75">{{ $student->program ?? '—' }} &mdash; {{ $student->email ?? '—' }}</small>
                </div>
            </div>
            <a href="{{ route('admin.students.profile', $student->id ?? $user->id) }}" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-user me-1"></i>Retour au profil
            </a>
        </div>
    </div>

    {{-- Stats rapides --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-mini fade-in" style="animation-delay: 0.1s;">
                <h4 style="color: #4fc3f7;">{{ $student_programs->count() }}</h4>
                <small class="text-white-50">Total Formations</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-mini fade-in" style="animation-delay: 0.15s;">
                <h4 style="color: #10b981;">{{ $formations_by_category->count() }}</h4>
                <small class="text-white-50">Catégories</small>
            </div>
        </div>
    </div>

    {{-- Formations par catégorie --}}
    @if($formations_by_category->count() > 0)
        @foreach($formations_by_category as $categoryName => $categoryFormations)
        <div class="info-card fade-in" style="animation-delay: 0.2s;">
            <div class="info-card-header">
                <i class="fas fa-folder"></i>
                <span>{{ $categoryName ?? 'Sans catégorie' }} ({{ $categoryFormations->count() }})</span>
            </div>
            <div class="info-card-body">
                <div class="row g-3">
                    @foreach($categoryFormations as $formation)
                    <div class="col-md-6 col-lg-4">
                        <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(79,195,247,0.25); border-radius: 16px; padding: 1.25rem; height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; position: relative; overflow: hidden;" onmouseover="this.style.borderColor='#4fc3f7'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(79,195,247,0.15)';" onmouseout="this.style.borderColor='rgba(79,195,247,0.25)'; this.style.transform='none'; this.style.boxShadow='none';">
                            <div style="position:absolute;top:0;left:0;width:100%;height:3px;background:linear-gradient(90deg,#4fc3f7,#29b6f6);"></div>

                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#4fc3f7,#29b6f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-graduation-cap text-white"></i>
                                </div>
                                <div style="min-width:0;">
                                    <h6 class="text-white mb-1" style="font-weight:700; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $formation->name ?? 'Sans nom' }}</h6>
                                    <span class="badge badge-modern {{ $formation->status === 'active' ? 'badge-success-modern' : 'badge-warning-modern' }}">
                                        {{ $formation->status ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>

                            <p class="text-white-50 small mb-3" style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; line-height:1.5;">{{ Str::limit($formation->description ?? '-', 120) }}</p>

                            <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:0.75rem; margin-bottom:0.75rem; border:1px solid rgba(255,255,255,0.06);">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-layer-group text-white-50" style="width:14px; font-size:0.75rem;"></i>
                                    <small class="text-white-50">Niveau: {{ $formation->level ?? 'N/A' }}</small>
                                </div>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-clock text-white-50" style="width:14px; font-size:0.75rem;"></i>
                                    <small class="text-white-50">Durée: {{ $formation->duration_weeks ?? 'N/A' }} sem</small>
                                </div>
                                @if(!empty($formation->price) && $formation->price > 0)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-tag text-white-50" style="width:14px; font-size:0.75rem;"></i>
                                    <small class="text-white-50">Prix: {{ number_format($formation->price, 0) }} FCFA</small>
                                </div>
                                @endif
                            </div>

                            <div class="mt-auto">
                                <small class="text-white-50" style="font-size:0.65rem; text-transform:uppercase; letter-spacing:0.5px;">
                                    <i class="fas fa-calendar me-1"></i>Créée le {{ $formation->created_at ? date('d/m/Y', strtotime($formation->created_at)) : '—' }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="info-card fade-in">
            <div class="info-card-body">
                <p class="text-center text-white-50 py-4 mb-0">
                    <i class="fas fa-info-circle me-2"></i>Aucune formation disponible pour cet étudiant.
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
