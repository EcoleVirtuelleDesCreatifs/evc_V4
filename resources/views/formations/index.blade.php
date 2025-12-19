@extends('layouts.ki-admin')

@section('title', 'Formations - EVC 2024')
@section('page-title', 'Formation')

@section('content')
<style>
    .stat-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        position: relative;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    }

    .stat-card.photoshop {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); /* Dégradé bleu foncé vers bleu */
    }

    .stat-card.illustrator {
        background: linear-gradient(135deg, #ea580c 0%, #fb923c 100%); /* Dégradé orange */
    }

    .stat-card.indesign {
        background: linear-gradient(135deg, #0ea5e9 0%, #7dd3fc 100%); /* Dégradé bleu clair */
    }

    .stat-card.masterclass {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); /* Dégradé bleu vers orange */
    }

    .stat-card.community-management {
        background: linear-gradient(135deg, #833AB4 0%, #E1306C 100%); /* Dégradé violet/rose pour CM */
    }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .stat-icon {
        font-size: 3rem;
        opacity: 0.3;
        position: absolute;
        right: 20px;
        top: 20px;
    }

    .stat-label {
        font-size: 0.9rem;
        font-weight: 500;
        opacity: 0.95;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .stat-btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        font-weight: 600;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .stat-btn:hover {
        background: rgba(255,255,255,0.3);
        border-color: white;
        color: white;
        transform: scale(1.05);
    }

    .formation-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .formation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.15);
    }

    .formation-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); /* Dégradé bleu */
        color: white;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .formation-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: pulse 3s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .formation-item {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        position: relative;
    }

    .formation-item:hover {
        transform: scale(1.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .formation-icon-wrapper {
        width: 70px;
        height: 70px;
        border-radius: 15px;
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); /* Dégradé bleu vers orange */
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
    }

    .formation-title {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .badge-custom {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
    }

    .btn-view-formation {
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); /* Dégradé bleu vers orange */
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        transition: all 0.3s ease;
    }

    .btn-view-formation:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(37, 99, 235, 0.4);
        color: white;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: #2d3748;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(135deg, #2563eb 0%, #f97316 100%); /* Dégradé bleu vers orange */
        border-radius: 2px;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    }
</style>

<!-- Section Statistiques -->
<div class="row mb-5">
    <div class="col-12">
        <h2 class="section-title" style="color: white;">
            <i class="fas fa-chart-bar me-2"></i>
            Vos Formations par Catégorie
        </h2>
    </div>
</div>

@if(isset($groupedCategories) && count($groupedCategories) > 0)
    @foreach($groupedCategories as $groupName => $categories)
        @if(count($categories) > 0)
            <!-- Section Header -->
            <div class="row mb-4">
                <div class="col-12">
                    @php
                        $headerIcon = 'fa-folder-open';
                        $lowerName = strtolower($groupName);
                        if (str_contains($lowerName, 'community')) $headerIcon = 'fa-comments';
                        elseif (str_contains($lowerName, 'design') || str_contains($lowerName, 'graphique')) $headerIcon = 'fa-pen-nib';
                        elseif (str_contains($lowerName, 'informatique') || str_contains($lowerName, 'code') || str_contains($lowerName, 'dev')) $headerIcon = 'fa-laptop-code';
                        elseif (str_contains($lowerName, 'intelligence') || str_contains($lowerName, 'ia')) $headerIcon = 'fa-brain';
                    @endphp
                    <h3 class="text-white mb-4 pb-2 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                        <i class="fas {{ $headerIcon }} me-2"></i>
                        {{ $groupName }}
                    </h3>
                </div>
            </div>

            <!-- Categories Grid -->
            <div class="row mb-5">
                @foreach($categories as $index => $stat)
                    @php
                        $slug = \Illuminate\Support\Str::slug($stat->slug ?? $stat->name);
                        // Déterminer la classe de couleur
                        $gradients = ['photoshop', 'illustrator', 'indesign', 'masterclass', 'community-management'];
                        $cardClass = '';

                        if (str_contains($slug, 'photoshop')) $cardClass = 'photoshop';
                        elseif (str_contains($slug, 'illustrator')) $cardClass = 'illustrator';
                        elseif (str_contains($slug, 'indesign')) $cardClass = 'indesign';
                        elseif (str_contains($slug, 'master')) $cardClass = 'masterclass';
                        elseif (str_contains($slug, 'community')) $cardClass = 'community-management';
                        else {
                            $cardClass = $gradients[$index % count($gradients)];
                        }

                        // Déterminer l'icône
                        $icon = 'fa-folder';
                        if (str_contains($slug, 'photoshop')) $icon = 'fa-image';
                        elseif (str_contains($slug, 'illustrator')) $icon = 'fa-vector-square';
                        elseif (str_contains($slug, 'indesign')) $icon = 'fa-file-alt';
                        elseif (str_contains($slug, 'master')) $icon = 'fa-crown';
                        elseif (str_contains($slug, 'community')) $icon = 'fa-comments';
                        elseif (str_contains($slug, 'logiciel')) $icon = 'fa-laptop-code';

                        // Route pour la catégorie
                        $categoryRouteName = \Illuminate\Support\Str::replaceLast('.formations.index', '.formations.category', Route::currentRouteName());
                    @endphp
                    <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: {{ 0.1 * ($index + 1) }}s;">
                        <div class="stat-card {{ $cardClass }} h-100">
                            <i class="fas {{ $icon }} stat-icon"></i>
                            <div class="card-body p-4">
                                <div class="stat-number mb-2">{{ $stat->total }}</div>
                                <div class="stat-label mb-3">{{ $stat->name }}</div>
                                <a href="{{ route($categoryRouteName, $stat->slug) }}" class="btn stat-btn w-100">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Explorer
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach
@elseif(isset($categoriesStats) && count($categoriesStats) > 0)
    <div class="row mb-5">
        @foreach($categoriesStats as $index => $stat)
            @php
                $slug = \Illuminate\Support\Str::slug($stat->slug ?? $stat->name);
                // Déterminer la classe de couleur
                $gradients = ['photoshop', 'illustrator', 'indesign', 'masterclass', 'community-management'];
                $cardClass = '';

                if (str_contains($slug, 'photoshop')) $cardClass = 'photoshop';
                elseif (str_contains($slug, 'illustrator')) $cardClass = 'illustrator';
                elseif (str_contains($slug, 'indesign')) $cardClass = 'indesign';
                elseif (str_contains($slug, 'master')) $cardClass = 'masterclass';
                elseif (str_contains($slug, 'community')) $cardClass = 'community-management';
                else {
                    $cardClass = $gradients[$index % count($gradients)];
                }

                // Déterminer l'icône
                $icon = 'fa-folder';
                if (str_contains($slug, 'photoshop')) $icon = 'fa-image';
                elseif (str_contains($slug, 'illustrator')) $icon = 'fa-vector-square';
                elseif (str_contains($slug, 'indesign')) $icon = 'fa-file-alt';
                elseif (str_contains($slug, 'master')) $icon = 'fa-crown';
                elseif (str_contains($slug, 'community')) $icon = 'fa-comments';
                elseif (str_contains($slug, 'logiciel')) $icon = 'fa-laptop-code';

                // Route pour la catégorie
                $categoryRouteName = \Illuminate\Support\Str::replaceLast('.formations.index', '.formations.category', Route::currentRouteName());
            @endphp
            <div class="col-lg-3 col-md-6 mb-4 fade-in-up" style="animation-delay: {{ 0.1 * ($index + 1) }}s;">
                <div class="stat-card {{ $cardClass }} h-100">
                    <i class="fas {{ $icon }} stat-icon"></i>
                    <div class="card-body p-4">
                        <div class="stat-number mb-2">{{ $stat->total }}</div>
                        <div class="stat-label mb-3">{{ $stat->name }}</div>
                        <a href="{{ route($categoryRouteName, $stat->slug) }}" class="btn stat-btn w-100">
                            <i class="fas fa-arrow-right me-2"></i>
                            Explorer
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="row mb-5">
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Aucune catégorie de formation disponible pour le moment.
            </div>
        </div>
    </div>
@endif

@if(isset($formations_publiees) && count($formations_publiees))
<!-- Section Formations de la Semaine -->
<div class="row mt-5">
    <div class="col-12">
        <div class="formation-card fade-in-up">
            <div class="formation-header">
                <h3 class="mb-0 position-relative">
                    <i class="fas fa-fire me-3"></i>
                    Formations en Vedette cette Semaine
                </h3>
                <p class="mb-0 mt-2 opacity-75">Découvrez nos contenus les plus récents et populaires</p>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach($formations_publiees as $index => $f)
                    <div class="col-lg-4 col-md-6 fade-in-up" style="animation-delay: {{ 0.1 * ($index + 1) }}s;">
                        <div class="formation-item h-100">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="formation-icon-wrapper flex-shrink-0">
                                        <i class="fas fa-graduation-cap fa-2x text-white"></i>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="formation-title">{{ $f->title ?? ($f->name ?? '—') }}</h5>
                                        @if(!empty($f->category_name))
                                            <span class="badge badge-custom bg-primary">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ $f->category_name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if(!empty($f->created_at))
                                    <p class="text-muted small mb-3">
                                        <i class="fas fa-calendar-plus me-1"></i>
                                        Ajoutée le {{ \Carbon\Carbon::parse($f->created_at)->format('d/m/Y') }}
                                    </p>
                                @endif

                                <div class="mt-auto">
                                    @if(!empty($f->id))
                                        <a href="{{ route($module_slug . '.formations.show', $f->id) }}" class="btn btn-view-formation w-100">
                                            <i class="fas fa-play-circle me-2"></i>
                                            Commencer la Formation
                                        </a>
                                    @else
                                        <button class="btn btn-secondary w-100" disabled>
                                            <i class="fas fa-lock me-2"></i>
                                            Indisponible
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<script>
// Activation des onglets Bootstrap
document.addEventListener('DOMContentLoaded', function() {
    var triggerTabList = [].slice.call(document.querySelectorAll('#formationTabs button'))
    triggerTabList.forEach(function (triggerEl) {
        var tabTrigger = new bootstrap.Tab(triggerEl)
        triggerEl.addEventListener('click', function (event) {
            event.preventDefault()
            tabTrigger.show()
        })
    })
})
</script>
@endsection
