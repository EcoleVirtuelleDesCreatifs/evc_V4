@extends('layouts.admin')

@section('title', 'Travaux - ' . ($data['student']['prenom'] ?? 'Étudiant') . ' ' . ($data['student']['nom'] ?? ''))

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
                <a href="{{ route('admin.students.profile', !empty($data['student']['id']) ? $data['student']['id'] : ['id' => $data['student']['user_id'], 'source' => 'user']) }}" class="text-white text-decoration-none" title="Retour au profil">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
                @if(!empty($data['student']['photo_url']))
                    <img src="{{ $data['student']['photo_url'] }}" alt="" class="works-header-avatar">
                @else
                    <div class="works-header-avatar-placeholder">
                        {{ strtoupper(substr($data['student']['prenom'], 0, 1)) }}{{ strtoupper(substr($data['student']['nom'], 0, 1)) }}
                    </div>
                @endif
                <div>
                    <h4 class="mb-0 fw-bold">{{ $data['student']['prenom'] }} {{ $data['student']['nom'] }}</h4>
                    <small class="opacity-75">{{ $data['student']['formation'] }} &mdash; {{ $data['student']['email'] }}</small>
                </div>
            </div>
            <a href="{{ route('admin.students.profile', !empty($data['student']['id']) ? $data['student']['id'] : ['id' => $data['student']['user_id'], 'source' => 'user']) }}" class="btn btn-modern btn-primary-modern">
                <i class="fas fa-user me-1"></i>Retour au profil
            </a>
        </div>
    </div>

    {{-- Stats rapides --}}
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-mini fade-in" style="animation-delay: 0.1s;">
                <h4 style="color: #4fc3f7;">{{ $data['stats']['total_tp'] }}</h4>
                <small class="text-white-50">TPs Total</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-mini fade-in" style="animation-delay: 0.15s;">
                <h4 style="color: #10b981;">{{ $data['stats']['tp_valides'] }}</h4>
                <small class="text-white-50">TPs Validés</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-mini fade-in" style="animation-delay: 0.2s;">
                <h4 style="color: #f59e0b;">{{ $data['stats']['todos_non_traites'] }}</h4>
                <small class="text-white-50">Projets Non Traités</small>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-mini fade-in" style="animation-delay: 0.25s;">
                <h4 style="color: #10b981;">{{ $data['stats']['todos_traites'] }}</h4>
                <small class="text-white-50">Projets Traités</small>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="works-tabs fade-in" style="animation-delay: 0.3s;">
        <div class="works-tab active" data-tab="tps">
            <i class="fas fa-tasks me-1"></i>Travaux Pratiques
            <span class="tab-count">{{ $data['stats']['total_tp'] }}</span>
        </div>
        <div class="works-tab" data-tab="non-traites">
            <i class="fas fa-clock me-1"></i>Non Traités
            <span class="tab-count">{{ $data['stats']['todos_non_traites'] }}</span>
        </div>
        <div class="works-tab" data-tab="traites">
            <i class="fas fa-check-double me-1"></i>Traités
            <span class="tab-count">{{ $data['stats']['todos_traites'] }}</span>
        </div>
        <div class="works-tab" data-tab="projets-disponibles">
            <i class="fas fa-box-open me-1"></i>Projets Disponibles
        </div>
    </div>

    {{-- Panel: Travaux Pratiques --}}
    <div class="works-panel active" id="panel-tps">
        <div class="info-card fade-in" style="animation-delay: 0.35s;">
            <div class="info-card-header">
                <i class="fas fa-tasks"></i>
                <span>Travaux Pratiques ({{ $data['stats']['total_tp'] }})</span>
            </div>
            <div class="info-card-body">
                @if(isset($data['tps']) && is_countable($data['tps']) && count($data['tps']) > 0)
                    <div class="row g-3">
                        @foreach($data['tps'] as $tp)
                        @php
                            $tpImages = collect($tp->tp_files ?? collect())->map(function ($file) {
                                $path = $file->file_path ?? '';
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                if (!in_array($ext, ['jpg','jpeg','png','gif','webp'], true)) {
                                    return null;
                                }
                                $url = $path !== '' ? \App\Models\MediaUrl::fromPath($path) : null;
                                if (!$url) return null;
                                return [
                                    'url' => $url,
                                    'original_name' => $file->original_name ?? basename($path),
                                ];
                            })->filter()->values();

                            $tpOtherFiles = collect($tp->tp_files ?? collect())->filter(function ($file) {
                                $path = $file->file_path ?? '';
                                $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                return !in_array($ext, ['jpg','jpeg','png','gif','webp'], true);
                            })->values();
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 1.25rem; height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease;">
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#4fc3f7,#29b6f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-tasks text-white"></i>
                                    </div>
                                    <div style="min-width:0;">
                                        <h6 class="text-white mb-1" style="font-weight:700;">{{ $tp->title ?? 'TP' }}</h6>
                                        @if($tp->status === 'validated')
                                            <span class="badge" style="background:linear-gradient(135deg,#10b981,#059669); color:#fff; font-size:0.7rem; padding:0.25rem 0.6rem; border-radius:20px;">
                                                <i class="fas fa-check-circle me-1"></i>Validé
                                            </span>
                                        @elseif($tp->status === 'pending')
                                            <span class="badge" style="background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff; font-size:0.7rem; padding:0.25rem 0.6rem; border-radius:20px;">
                                                <i class="fas fa-hourglass-half me-1"></i>En attente
                                            </span>
                                        @else
                                            <span class="badge" style="background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; font-size:0.7rem; padding:0.25rem 0.6rem; border-radius:20px;">
                                                <i class="fas fa-times-circle me-1"></i>Rejeté
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:0.75rem; margin-bottom:0.75rem; border:1px solid rgba(255,255,255,0.06);">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar-plus text-white-50" style="width:14px; font-size:0.75rem;"></i>
                                        <small class="text-white-50">Soumis le {{ $tp->created_at ? date('d/m/Y H:i', strtotime($tp->created_at)) : '—' }}</small>
                                    </div>
                                </div>

                                {{-- Affichage des images TP --}}
                                @if($tpImages->count() > 0)
                                <div class="mb-3">
                                    <small class="text-white-50 d-block mb-2"><i class="fas fa-images me-1"></i>Images ({{ $tpImages->count() }})</small>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($tpImages as $img)
                                        <a href="{{ $img['url'] }}" target="_blank" style="display:block; width:80px; height:80px; border-radius:10px; overflow:hidden; border:2px solid rgba(255,255,255,0.1); transition:all 0.2s;" onmouseover="this.style.borderColor='#4fc3f7'; this.style.transform='scale(1.05)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.transform='none';">
                                            <img src="{{ $img['url'] }}" alt="{{ $img['original_name'] }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- Affichage des autres fichiers TP --}}
                                @if($tpOtherFiles->count() > 0)
                                <div class="mb-3">
                                    <small class="text-white-50 d-block mb-2"><i class="fas fa-file me-1"></i>Fichiers ({{ $tpOtherFiles->count() }})</small>
                                    @foreach($tpOtherFiles->take(3) as $file)
                                    @php
                                        $fPath = $file->file_path ?? '';
                                        $fUrl = $fPath !== '' ? \App\Models\MediaUrl::fromPath($fPath) : null;
                                        $fName = $file->original_name ?? basename($fPath);
                                        $fExt = strtolower(pathinfo($fPath, PATHINFO_EXTENSION));
                                    @endphp
                                    @if($fUrl)
                                    <a href="{{ $fUrl }}" target="_blank" class="d-flex align-items-center gap-2 mb-1" style="color:#4fc3f7; text-decoration:none; font-size:0.8rem;">
                                        <i class="fas fa-{{ $fExt === 'pdf' ? 'file-pdf' : 'file' }}" style="font-size:0.85rem;"></i>
                                        {{ Str::limit($fName, 30) }}
                                    </a>
                                    @endif
                                    @endforeach
                                </div>
                                @endif

                                <button type="button"
                                        class="btn btn-sm btn-modern btn-primary-modern mt-auto"
                                        data-tp-open
                                        data-tp-title="{{ e($tp->title ?? 'TP') }}"
                                        data-tp-status="{{ e($tp->status ?? '') }}"
                                        data-tp-created="{{ e($tp->created_at ? date('d/m/Y H:i', strtotime($tp->created_at)) : '') }}"
                                        data-tp-images='@json($tpImages)'>
                                    <i class="fas fa-eye me-1"></i>Voir détails
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-white-50 py-4 mb-0">Aucun TP soumis</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Panel: Non Traités --}}
    <div class="works-panel" id="panel-non-traites">
        <div class="info-card fade-in">
            <div class="info-card-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <i class="fas fa-clock"></i>
                <span>Projets Assignés — Non Traités ({{ $data['stats']['todos_non_traites'] }})</span>
            </div>
            <div class="info-card-body">
                @if(isset($data['todos_non_traites']) && $data['todos_non_traites']->count() > 0)
                    <div class="row g-3">
                        @foreach($data['todos_non_traites'] as $todo)
                        <div class="col-md-6 col-lg-4">
                            <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(245,158,11,0.25); border-radius: 16px; padding: 1.25rem; height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; position: relative; overflow: hidden;" onmouseover="this.style.borderColor='#f59e0b'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(245,158,11,0.15)';" onmouseout="this.style.borderColor='rgba(245,158,11,0.25)'; this.style.transform='none'; this.style.boxShadow='none';">
                                <div style="position:absolute;top:0;left:0;width:100%;height:3px;background:linear-gradient(90deg,#f59e0b,#d97706);"></div>

                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-{{ ($todo->source_table ?? '') === 'projects' ? 'folder-open' : 'file-alt' }} text-white"></i>
                                    </div>
                                    <div style="min-width:0;">
                                        <h6 class="text-white mb-1" style="font-weight:700; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $todo->title ?? 'Projet' }}</h6>
                                        <span class="badge" style="background:rgba(245,158,11,0.15); color:#f59e0b; font-size:0.7rem; padding:0.25rem 0.6rem; border-radius:20px;">
                                            <i class="fas fa-hourglass-half me-1"></i>À traiter
                                        </span>
                                    </div>
                                </div>

{{--                                 @if(!empty($todo->description)) --}}
{{--                                 <p class="text-white-50 small mb-3" style="display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; line-height:1.5;">{{ Str::limit(strip_tags($todo->description), 120) }}</p> --}}
{{--                                 @endif --}}

                                <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:0.75rem; margin-bottom:0.75rem; border:1px solid rgba(255,255,255,0.06);">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-graduation-cap text-white-50" style="width:14px; font-size:0.75rem;"></i>
                                        <small class="text-white-50">{{ $todo->formation ?? ($todo->category ?? '—') }}</small>
                                    </div>
                                    @if($todo->deadline)
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="fas fa-clock text-white-50" style="width:14px; font-size:0.75rem;"></i>
                                        <small class="text-white-50">Délai: {{ \Carbon\Carbon::parse($todo->deadline)->format('d/m/Y') }}</small>
                                    </div>
                                    @endif
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar-plus text-white-50" style="width:14px; font-size:0.75rem;"></i>
                                        <small class="text-white-50">Assigné le {{ $todo->created_at ? date('d/m/Y', strtotime($todo->created_at)) : '—' }}</small>
                                    </div>
                                </div>

                                {{-- Affichage des fichiers brief (images) --}}
                                @if(isset($todo->brief_files) && count($todo->brief_files) > 0)
                                <div class="mb-3">
                                    <small class="text-white-50 d-block mb-2"><i class="fas fa-images me-1"></i>Fichiers brief ({{ count($todo->brief_files) }})</small>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($todo->brief_files as $briefFile)
                                        @php
                                            $bPath = $briefFile->file_path ?? '';
                                            $bPath = ltrim((string) $bPath, '/');
                                            if (str_starts_with($bPath, 'storage/app/public/')) {
                                                $bPath = substr($bPath, strlen('storage/app/public/'));
                                            }
                                            $bUrl = \App\Models\MediaUrl::fromPath($bPath);
                                            $bName = $briefFile->original_name ?? basename($bPath);
                                            $bExt = strtolower(pathinfo($bPath, PATHINFO_EXTENSION));
                                            $isImage = in_array($bExt, ['jpg','jpeg','png','gif','webp']);
                                        @endphp
                                        @if($isImage)
                                        <a href="{{ $bUrl }}" target="_blank" style="display:block; width:80px; height:80px; border-radius:10px; overflow:hidden; border:2px solid rgba(255,255,255,0.1); transition:all 0.2s;" onmouseover="this.style.borderColor='#f59e0b'; this.style.transform='scale(1.05)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.transform='none';">
                                            <img src="{{ $bUrl }}" alt="{{ $bName }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                        </a>
                                        @else
                                        <a href="{{ $bUrl }}" target="_blank" class="d-flex align-items-center gap-2 px-3 py-2" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:8px; color:#f59e0b; text-decoration:none; font-size:0.8rem;">
                                            <i class="fas fa-{{ $bExt === 'pdf' ? 'file-pdf' : 'file' }}"></i>
                                            {{ Str::limit($bName, 20) }}
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif



                                <form method="POST" action="{{ route('admin.students.works.toggle-hidden', $data['student']['id'] ?? $data['student']['user_id']) }}" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="source_table" value="{{ $todo->source_table }}">
                                    <input type="hidden" name="work_id" value="{{ $todo->id }}">
                                    <button type="submit" class="btn btn-sm btn-modern" style="background: {{ !empty($todo->admin_hidden) ? 'rgba(79,195,247,0.15)' : 'rgba(239,68,68,0.15)' }}; color: {{ !empty($todo->admin_hidden) ? '#4fc3f7' : '#ef4444' }}; border: 1px solid {{ !empty($todo->admin_hidden) ? 'rgba(79,195,247,0.35)' : 'rgba(239,68,68,0.35)' }};" onclick="return confirm('{{ !empty($todo->admin_hidden) ? 'Afficher ce projet à l\'étudiant ?' : 'Masquer ce projet à l\'étudiant ?' }}')">
                                        <i class="fas fa-{{ !empty($todo->admin_hidden) ? 'eye' : 'eye-slash' }} me-1"></i>{{ !empty($todo->admin_hidden) ? 'Afficher' : 'Masquer' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.students.works.update-deadline', $data['student']['id'] ?? $data['student']['user_id']) }}" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="source_table" value="{{ $todo->source_table }}">
                                    <input type="hidden" name="work_id" value="{{ $todo->id }}">
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="date" name="deadline" value="{{ !empty($todo->deadline) ? \Illuminate\Support\Carbon::parse($todo->deadline)->format('Y-m-d') : '' }}" class="form-control form-control-sm" style="max-width: 170px;">
                                        <button type="submit" class="btn btn-sm btn-modern" style="background: rgba(59,130,246,0.15); color: #3b82f6; border: 1px solid rgba(59,130,246,0.35);">
                                            <i class="fas fa-calendar-check me-1"></i>Mettre à jour
                                        </button>
                                    </div>
                                </form>

                                <form method="POST" action="{{ route('admin.students.works.remove', $data['student']['id'] ?? $data['student']['user_id']) }}" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="source_table" value="{{ $todo->source_table }}">
                                    <input type="hidden" name="work_id" value="{{ $todo->id }}">
                                    <button type="submit" class="btn btn-sm btn-modern w-100" style="background: rgba(239,68,68,0.15); color: #ef4444; border: 1px solid rgba(239,68,68,0.35);" onclick="return confirm('Êtes-vous sûr de vouloir retirer ce projet du compte de l\'étudiant ? Cette action est irréversible.')">
                                        <i class="fas fa-trash-alt me-1"></i>Retirer le projet
                                    </button>
                                </form>
                                <div class="mt-auto">
                                    <small class="text-white-50" style="font-size:0.65rem; text-transform:uppercase; letter-spacing:0.5px;">
                                        <i class="fas fa-database me-1"></i>{{ ($todo->source_table ?? '') === 'projects' ? 'Projet' : 'TP Assignment' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-white-50 py-4 mb-0">
                        <i class="fas fa-check-circle me-2 text-success"></i>Aucun projet en attente de traitement
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Panel: Projets Disponibles --}}
    <div class="works-panel" id="panel-projets-disponibles">
        <div class="info-card fade-in">
            <div class="info-card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                <i class="fas fa-box-open"></i>
                <span>Projets Disponibles</span>
            </div>
            <div class="info-card-body">
                @if(isset($data['available_projects']) && $data['available_projects']->count() > 0)
                    <div class="row g-3">
                        @foreach($data['available_projects'] as $project)
                        <div class="col-md-6 col-lg-4">
                            <div style="background: rgba(255,255,255,0.04); border: 1px solid rgba(139,92,246,0.25); border-radius: 16px; padding: 1.25rem; height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; position: relative; overflow: hidden;" onmouseover="this.style.borderColor='#8b5cf6'; this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(139,92,246,0.15)';" onmouseout="this.style.borderColor='rgba(139,92,246,0.25)'; this.style.transform='none'; this.style.boxShadow='none';">
                                <div style="position:absolute;top:0;left:0;width:100%;height:3px;background:linear-gradient(90deg,#8b5cf6,#6366f1);"></div>

                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div style="width:42px;height:42px;border-radius:10px;background:linear-gradient(135deg,#8b5cf6,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-folder-open text-white"></i>
                                    </div>
                                    <div style="min-width:0;">
                                        <h6 class="text-white mb-1" style="font-weight:700; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $project->title ?? 'Projet' }}</h6>
                                        <span class="badge" style="background:rgba(139,92,246,0.15); color:#8b5cf6; font-size:0.7rem; padding:0.25rem 0.6rem; border-radius:20px;">
                                            <i class="fas fa-tag me-1"></i>{{ $project->category ?? '—' }}
                                        </span>
                                    </div>
                                </div>

                                <div style="background:rgba(255,255,255,0.03); border-radius:10px; padding:0.75rem; margin-bottom:0.75rem; border:1px solid rgba(255,255,255,0.06);">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-calendar-plus text-white-50" style="width:14px; font-size:0.75rem;"></i>
                                        <small class="text-white-50">Créé le {{ $project->created_at ? date('d/m/Y', strtotime($project->created_at)) : '—' }}</small>
                                    </div>
                                </div>

                                {{-- Affichage des fichiers brief avec images --}}
                                @if(isset($project->brief_files) && count($project->brief_files) > 0)
                                <div class="mb-3">
                                    <small class="text-white-50 d-block mb-2"><i class="fas fa-images me-1"></i>Fichiers brief ({{ count($project->brief_files) }})</small>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($project->brief_files as $briefFile)
                                        @php
                                            $bPath = $briefFile->file_path ?? '';
                                            $bPath = ltrim((string) $bPath, '/');
                                            if (str_starts_with($bPath, 'storage/app/public/')) {
                                                $bPath = substr($bPath, strlen('storage/app/public/'));
                                            }
                                            $bUrl = \App\Models\MediaUrl::fromPath($bPath);
                                            $bName = $briefFile->original_name ?? basename($bPath);
                                            $bExt = strtolower(pathinfo($bPath, PATHINFO_EXTENSION));
                                            $isImage = in_array($bExt, ['jpg','jpeg','png','gif','webp']);
                                        @endphp
                                        @if($isImage)
                                        <a href="{{ $bUrl }}" target="_blank" style="display:block; width:80px; height:80px; border-radius:10px; overflow:hidden; border:2px solid rgba(255,255,255,0.1); transition:all 0.2s;" onmouseover="this.style.borderColor='#8b5cf6'; this.style.transform='scale(1.05)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.transform='none';">
                                            <img src="{{ $bUrl }}" alt="{{ $bName }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                        </a>
                                        @else
                                        <a href="{{ $bUrl }}" target="_blank" class="d-flex align-items-center gap-2 px-3 py-2" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:8px; color:#8b5cf6; text-decoration:none; font-size:0.8rem;">
                                            <i class="fas fa-{{ $bExt === 'pdf' ? 'file-pdf' : 'file' }}"></i>
                                            {{ Str::limit($bName, 20) }}
                                        </a>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <div class="d-flex gap-2 mb-2">
                                    <button type="button" class="btn btn-sm flex-fill" style="background: rgba(255,255,255,0.08); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.15); border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#detailsModal-{{ !empty($project->is_template) ? 't' : 'p' }}-{{ $project->id }}">
                                        <i class="fas fa-eye me-1"></i>Détails
                                    </button>
                                    @if(!empty($project->is_template))
                                    <a href="{{ route('admin.project-templates.edit', $project->id) }}" class="btn btn-sm flex-fill" style="background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); border-radius: 8px;">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    @else
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm flex-fill" style="background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); border-radius: 8px;">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    @endif
                                </div>

                                @if(!empty($project->is_template))
                                <form method="POST" action="{{ route('admin.students.assign-template', $data['student']['id'] ?? $data['student']['user_id']) }}" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="template_id" value="{{ $project->id }}">
                                    <button type="submit" class="btn btn-sm btn-modern w-100" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); color: white; border: none;" onclick="return confirm('Assigner ce projet à l\'étudiant ?')">
                                        <i class="fas fa-plus me-1"></i>Assigner ce projet
                                    </button>
                                </form>
                                @else
                                <form method="POST" action="{{ route('admin.students.assign-project', $data['student']['id'] ?? $data['student']['user_id']) }}" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="project_id" value="{{ $project->id }}">
                                    <button type="submit" class="btn btn-sm btn-modern w-100" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); color: white; border: none;" onclick="return confirm('Assigner ce projet à l\'étudiant ?')">
                                        <i class="fas fa-plus me-1"></i>Assigner ce projet
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Modales de détails des projets disponibles --}}
                    @foreach($data['available_projects'] as $project)
                    @php
                        $modalId = 'detailsModal-' . (!empty($project->is_template) ? 't' : 'p') . '-' . $project->id;
                        $swList = [];
                        if (!empty($project->software_used)) {
                            $decoded = is_string($project->software_used) ? json_decode($project->software_used, true) : $project->software_used;
                            $swList = is_array($decoded) ? $decoded : [];
                        }
                    @endphp
                    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content" style="background:#1e293b; border:1px solid rgba(139,92,246,0.3); border-radius:16px;">
                                <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.1);">
                                    <h5 class="modal-title text-white">
                                        <i class="fas fa-folder-open me-2" style="color:#8b5cf6;"></i>{{ $project->title ?? 'Projet' }}
                                        @if(!empty($project->is_template))
                                        <span class="badge ms-2" style="background:rgba(139,92,246,0.2); color:#a78bfa; font-size:0.65rem;">Modèle</span>
                                        @endif
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <span class="badge" style="background:rgba(139,92,246,0.15); color:#8b5cf6; padding:0.35rem 0.8rem; border-radius:20px;">
                                            <i class="fas fa-tag me-1"></i>{{ $project->category ?? '—' }}
                                        </span>
                                        <small class="text-white-50 ms-2">
                                            <i class="fas fa-calendar-plus me-1"></i>Créé le {{ $project->created_at ? date('d/m/Y', strtotime($project->created_at)) : '—' }}
                                        </small>
                                    </div>

                                    @if(!empty($project->description))
                                    <div class="mb-3">
                                        <h6 class="text-white-50 mb-1" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Description</h6>
                                        <p class="text-white mb-0" style="white-space:pre-wrap;">{{ $project->description }}</p>
                                    </div>
                                    @endif

                                    @if(!empty($project->brief_content))
                                    <div class="mb-3">
                                        <h6 class="text-white-50 mb-1" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Brief</h6>
                                        <div class="text-white" style="white-space:pre-wrap;">{{ $project->brief_content }}</div>
                                    </div>
                                    @endif

                                    <div class="row g-3 mb-3">
                                        @if(!empty($project->link))
                                        <div class="col-md-6">
                                            <h6 class="text-white-50 mb-1" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Lien de référence</h6>
                                            <a href="{{ $project->link }}" target="_blank" style="color:#8b5cf6; word-break:break-all;">{{ $project->link }}</a>
                                        </div>
                                        @endif
                                        @if(!empty($project->deadline))
                                        <div class="col-md-6">
                                            <h6 class="text-white-50 mb-1" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Deadline</h6>
                                            <span class="text-white"><i class="fas fa-clock me-1 text-warning"></i>{{ date('d/m/Y', strtotime($project->deadline)) }}</span>
                                        </div>
                                        @endif
                                        @if(!empty($project->default_deadline_days))
                                        <div class="col-md-6">
                                            <h6 class="text-white-50 mb-1" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Délai par défaut</h6>
                                            <span class="text-white"><i class="fas fa-clock me-1 text-warning"></i>{{ $project->default_deadline_days }} jours</span>
                                        </div>
                                        @endif
                                    </div>

                                    @if(!empty($project->tags))
                                    <div class="mb-3">
                                        <h6 class="text-white-50 mb-1" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Tags</h6>
                                        <span class="text-white">{{ $project->tags }}</span>
                                    </div>
                                    @endif

                                    @if(count($swList) > 0)
                                    <div class="mb-3">
                                        <h6 class="text-white-50 mb-2" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Logiciels</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($swList as $sw)
                                            <span class="badge" style="background:rgba(59,130,246,0.15); color:#60a5fa; padding:0.3rem 0.7rem; border-radius:20px;">{{ $sw }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    @if(isset($project->brief_files) && count($project->brief_files) > 0)
                                    <div class="mb-2">
                                        <h6 class="text-white-50 mb-2" style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">
                                            <i class="fas fa-images me-1"></i>Fichiers brief ({{ count($project->brief_files) }})
                                        </h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($project->brief_files as $briefFile)
                                            @php
                                                $mPath = ltrim((string) ($briefFile->file_path ?? ''), '/');
                                                if (str_starts_with($mPath, 'storage/app/public/')) {
                                                    $mPath = substr($mPath, strlen('storage/app/public/'));
                                                }
                                                $mUrl = \App\Models\MediaUrl::fromPath($mPath);
                                                $mName = $briefFile->original_name ?? basename($mPath);
                                                $mExt = strtolower(pathinfo($mPath, PATHINFO_EXTENSION));
                                                $mIsImage = in_array($mExt, ['jpg','jpeg','png','gif','webp']);
                                            @endphp
                                            @if($mIsImage)
                                            <a href="{{ $mUrl }}" target="_blank" style="display:block; width:90px; height:90px; border-radius:10px; overflow:hidden; border:2px solid rgba(255,255,255,0.1);">
                                                <img src="{{ $mUrl }}" alt="{{ $mName }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                            </a>
                                            @else
                                            <a href="{{ $mUrl }}" target="_blank" class="d-flex align-items-center gap-2 px-3 py-2" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:8px; color:#8b5cf6; text-decoration:none; font-size:0.8rem;">
                                                <i class="fas fa-{{ $mExt === 'pdf' ? 'file-pdf' : 'file' }}"></i>
                                                {{ Str::limit($mName, 25) }}
                                            </a>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.1);">
                                    @if(!empty($project->is_template))
                                    <a href="{{ route('admin.project-templates.edit', $project->id) }}" class="btn btn-sm" style="background:rgba(245,158,11,0.15); color:#f59e0b; border:1px solid rgba(245,158,11,0.3);">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    @else
                                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn btn-sm" style="background:rgba(245,158,11,0.15); color:#f59e0b; border:1px solid rgba(245,158,11,0.3);">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-center text-white-50 py-4 mb-0">
                        <i class="fas fa-inbox me-2 text-secondary"></i>Aucun projet disponible pour l'assignation
                    </p>
                @endif
            </div>
        </div>
    </div>

    {{-- Panel: Traités --}}
    <div class="works-panel" id="panel-traites">
        <div class="info-card fade-in">
            <div class="info-card-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <i class="fas fa-check-double"></i>
                <span>Projets Assignés — Traités ({{ $data['stats']['todos_traites'] }})</span>
            </div>
            <div class="info-card-body">
                @if(isset($data['todos_traites']) && $data['todos_traites']->count() > 0)
                    <div class="row g-3">
                        @foreach($data['todos_traites'] as $todo)
                        @php
                            $statusConfig = [
                                'submitted' => ['label' => 'Soumis', 'icon' => 'paper-plane', 'bg' => 'linear-gradient(135deg,#3b82f6,#2563eb)', 'border' => 'rgba(59,130,246,0.3)'],
                                'validated' => ['label' => 'Validé', 'icon' => 'check-circle', 'bg' => 'linear-gradient(135deg,#10b981,#059669)', 'border' => 'rgba(16,185,129,0.3)'],
                                'rejected'  => ['label' => 'Rejeté', 'icon' => 'times-circle', 'bg' => 'linear-gradient(135deg,#ef4444,#dc2626)', 'border' => 'rgba(239,68,68,0.3)'],
                            ];
                            $todoStatus = $todo->normalized_status ?? $todo->status ?? '';
                            $sc = $statusConfig[$todoStatus] ?? $statusConfig['submitted'];
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div style="background: rgba(255,255,255,0.04); border: 1px solid {{ $sc['border'] }}; border-radius: 16px; padding: 1.25rem; height: 100%; display: flex; flex-direction: column; transition: all 0.3s ease; position: relative; overflow: hidden;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(0,0,0,0.15)';" onmouseout="this.style.transform='none'; this.style.boxShadow='none';">
                                <div style="position:absolute;top:0;left:0;width:100%;height:3px;background:{{ $sc['bg'] }};"></div>

                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <div style="width:42px;height:42px;border-radius:10px;background:{{ $sc['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-{{ ($todo->source_table ?? '') === 'projects' ? 'folder-open' : 'file-alt' }} text-white"></i>
                                    </div>
                                    <div style="min-width:0;">
                                        <h6 class="text-white mb-1" style="font-weight:700; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $todo->title ?? 'Projet' }}</h6>
                                        <span class="badge" style="background:{{ $sc['bg'] }}; color:#fff; font-size:0.7rem; padding:0.25rem 0.6rem; border-radius:20px;">
                                            <i class="fas fa-{{ $sc['icon'] }} me-1"></i>{{ $sc['label'] }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Galerie images/fichiers soumis --}}
                                @php
                                    $allFiles = collect($todo->submission_files ?? []);
                                    $imageFiles = $allFiles->filter(function ($f) {
                                        $mime = $f->mime_type ?? '';
                                        $path = $f->file_path ?? ($f->filename ?? '');
                                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        return str_starts_with($mime, 'image/') || in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                    });
                                    $otherFiles = $allFiles->filter(function ($f) {
                                        $mime = $f->mime_type ?? '';
                                        $path = $f->file_path ?? ($f->filename ?? '');
                                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        return !str_starts_with($mime, 'image/') && !in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                    });
                                @endphp

                                @if($imageFiles->count() > 0)
                                <div class="mb-3">
                                    <small class="text-white-50 d-block mb-2"><i class="fas fa-images me-1"></i>Fichiers soumis ({{ $allFiles->count() }})</small>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($imageFiles as $imgFile)
                                        @php
                                            $fPath = $imgFile->file_path ?? ($imgFile->filename ?? '');
                                            $fPath = ltrim((string) $fPath, '/');
                                            if (str_starts_with($fPath, 'storage/app/public/')) {
                                                $fPath = substr($fPath, strlen('storage/app/public/'));
                                            }
                                            $fUrl = \App\Models\MediaUrl::fromPath($fPath);
                                            $fName = $imgFile->original_name ?? ($imgFile->file_name ?? basename($fPath));
                                        @endphp
                                        <a href="{{ $fUrl }}" target="_blank" style="display:block; width:80px; height:80px; border-radius:10px; overflow:hidden; border:2px solid rgba(255,255,255,0.1); transition:all 0.2s;" onmouseover="this.style.borderColor='#10b981'; this.style.transform='scale(1.05)';" onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'; this.style.transform='none';">
                                            <img src="{{ $fUrl }}" alt="{{ $fName }}" style="width:100%; height:100%; object-fit:cover;" loading="lazy">
                                        </a>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if($otherFiles->count() > 0)
                                <div class="mb-3">
                                    <small class="text-white-50 d-block mb-2"><i class="fas fa-file me-1"></i>Autres fichiers ({{ $otherFiles->count() }})</small>
                                    @foreach($otherFiles->take(3) as $oFile)
                                    @php
                                        $oPath = $oFile->file_path ?? ($oFile->filename ?? '');
                                        $oPath = ltrim((string) $oPath, '/');
                                        if (str_starts_with($oPath, 'storage/app/public/')) {
                                            $oPath = substr($oPath, strlen('storage/app/public/'));
                                        }
                                        $oUrl = \App\Models\MediaUrl::fromPath($oPath);
                                        $oName = $oFile->original_name ?? ($oFile->file_name ?? basename($oPath));
                                        $oExt = strtolower(pathinfo($oPath, PATHINFO_EXTENSION));
                                    @endphp
                                    <a href="{{ $oUrl }}" target="_blank" class="d-flex align-items-center gap-2 px-3 py-2 mb-1" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); border-radius:8px; color:#10b981; text-decoration:none; font-size:0.8rem;">
                                        <i class="fas fa-{{ $oExt === 'pdf' ? 'file-pdf' : 'file' }}"></i>
                                        {{ Str::limit($oName, 30) }}
                                    </a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-center text-white-50 py-4 mb-0">
                        <i class="fas fa-inbox me-2"></i>Aucun projet traité
                    </p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tabs
    document.querySelectorAll('.works-tab').forEach(function(tab) {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.works-tab').forEach(function(t) { t.classList.remove('active'); });
            document.querySelectorAll('.works-panel').forEach(function(p) { p.classList.remove('active'); });
            tab.classList.add('active');
            var panelId = 'panel-' + tab.getAttribute('data-tab');
            var panel = document.getElementById(panelId);
            if (panel) panel.classList.add('active');
        });
    });

    // Fade-in observer
    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });
    document.querySelectorAll('.fade-in').forEach(function(el) { observer.observe(el); });

    // TP Modal
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('[data-tp-open]');
        if (!btn) return;
        var title = btn.getAttribute('data-tp-title') || 'TP';
        var status = btn.getAttribute('data-tp-status') || '';
        var created = btn.getAttribute('data-tp-created') || '';
        var images = [];
        try { images = JSON.parse(btn.getAttribute('data-tp-images') || '[]'); } catch(err) {}
        images = images.filter(function(i) { return i && i.url; });

        var modalId = 'tpGalleryModal';
        var existing = document.getElementById(modalId);
        if (existing) existing.remove();

        var galleryHTML = images.length > 0
            ? '<div class="row g-2">' + images.map(function(img) {
                return '<div class="col-4 col-md-3"><a href="' + img.url + '" target="_blank"><img src="' + img.url + '" alt="' + (img.original_name || 'Image') + '" style="width:100%;height:120px;object-fit:cover;border-radius:10px;border:2px solid rgba(255,255,255,0.1);"></a></div>';
              }).join('') + '</div>'
            : '<p class="text-white-50 mb-0">Aucune image disponible.</p>';

        var statusBadge = '';
        if (status === 'validated') statusBadge = '<span class="badge bg-success">Validé</span>';
        else if (status === 'pending') statusBadge = '<span class="badge bg-warning text-dark">En attente</span>';
        else if (status === 'rejected') statusBadge = '<span class="badge bg-danger">Rejeté</span>';

        var html = '<div class="modal fade" id="' + modalId + '" tabindex="-1" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content bg-dark text-white"><div class="modal-header border-secondary"><h5 class="modal-title">' + title + '</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div><div class="modal-body"><div class="d-flex gap-2 mb-3">' + statusBadge + (created ? '<span class="badge bg-secondary">' + created + '</span>' : '') + '</div>' + galleryHTML + '</div><div class="modal-footer border-secondary"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button></div></div></div></div>';
        document.body.insertAdjacentHTML('beforeend', html);
        var modal = new bootstrap.Modal(document.getElementById(modalId));
        modal.show();
    });
});
</script>
@endpush
