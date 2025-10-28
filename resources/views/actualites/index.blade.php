@extends('layouts.ki-admin')

@section('title', 'Actualités - EVC')
@section('page-title', 'Actualités')

@section('content')
<div class="container-fluid">
    <!-- Header Section avec Statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%); border-radius: 20px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-6 text-white mb-3 mb-md-0">
                            <h2 class="mb-2" style="font-weight: 700;">
                                <i class="fas fa-newspaper me-3"></i>Actualités EVC
                            </h2>
                            <p class="mb-0 opacity-75">Restez informé des dernières nouvelles de l'école</p>
                        </div>
                        <div class="col-md-6">
                            <div class="row text-center text-white">
                                <div class="col-4">
                                    <div class="bg-white bg-opacity-10 rounded p-3">
                                        <h3 class="mb-1" style="font-weight: 700;">{{ $stats['total'] }}</h3>
                                        <small class="opacity-75">Actualités</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-white bg-opacity-10 rounded p-3">
                                        <h3 class="mb-1" style="font-weight: 700;">{{ $stats['categories']->count() }}</h3>
                                        <small class="opacity-75">Catégories</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="bg-white bg-opacity-10 rounded p-3">
                                        <h3 class="mb-1" style="font-weight: 700;">{{ number_format($stats['vues_total']) }}</h3>
                                        <small class="opacity-75">Vues</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques par Catégorie -->
    <div class="row mb-4">
        @php
            $categoryInfo = [
                'general' => ['label' => 'Général', 'icon' => 'newspaper', 'color' => '#6c757d', 'gradient' => 'linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%)'],
                'formation' => ['label' => 'Formation', 'icon' => 'graduation-cap', 'color' => '#0d6efd', 'gradient' => 'linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%)'],
                'evenement' => ['label' => 'Événement', 'icon' => 'calendar-alt', 'color' => '#0dcaf0', 'gradient' => 'linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%)'],
                'partenariat' => ['label' => 'Partenariat', 'icon' => 'handshake', 'color' => '#198754', 'gradient' => 'linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%)'],
                'succes' => ['label' => 'Succès', 'icon' => 'trophy', 'color' => '#ffc107', 'gradient' => 'linear-gradient(135deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%)'],
            ];
        @endphp
        @foreach($stats['categories'] as $cat => $count)
            @php
                $info = $categoryInfo[$cat] ?? ['label' => ucfirst($cat), 'icon' => 'tag', 'color' => '#6c757d', 'gradient' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'];
            @endphp
            <div class="col-md-6 col-lg-4 col-xl mb-3">
                <div class="card border-0 shadow-sm h-100 hover-lift" style="border-radius: 16px; transition: all 0.3s ease;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 50px; height: 50px; background: {{ $info['gradient'] }};">
                                <i class="fas fa-{{ $info['icon'] }} fa-lg text-white"></i>
                            </div>
                            <div>
                                <h3 class="mb-0" style="font-weight: 700; color: {{ $info['color'] }};">{{ $count }}</h3>
                                <small class="text-muted">{{ $info['label'] }}</small>
                            </div>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $stats['total'] > 0 ? ($count / $stats['total'] * 100) : 0 }}%; background: {{ $info['gradient'] }};"
                                 aria-valuenow="{{ $count }}" aria-valuemin="0" aria-valuemax="{{ $stats['total'] }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Actualité à la une -->
    @if($featured)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                <div class="position-absolute top-0 start-0 m-3" style="z-index: 10;">
                    <span class="badge bg-warning text-dark px-3 py-2" style="font-size: 0.9rem; border-radius: 10px;">
                        <i class="fas fa-star me-2"></i>À LA UNE
                    </span>
                </div>
                <div class="row g-0">
                    <div class="col-md-5">
                        <div class="d-flex align-items-center justify-content-center" style="height: 100%; min-height: 400px; background-color: #f8f9fa; padding: 1rem;">
                            <img src="{{ asset('storage/' . $featured->cover_image) }}" 
                                 alt="{{ $featured->title }}" 
                                 class="img-fluid" 
                                 style="max-height: 400px; max-width: 100%; width: auto; object-fit: contain;">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="card-body p-5">
                            @php
                                $categoryLabels = [
                                    'general' => ['label' => 'Général', 'icon' => 'newspaper', 'color' => 'secondary'],
                                    'formation' => ['label' => 'Formation', 'icon' => 'graduation-cap', 'color' => 'primary'],
                                    'evenement' => ['label' => 'Événement', 'icon' => 'calendar-alt', 'color' => 'info'],
                                    'partenariat' => ['label' => 'Partenariat', 'icon' => 'handshake', 'color' => 'success'],
                                    'succes' => ['label' => 'Succès', 'icon' => 'trophy', 'color' => 'warning'],
                                ];
                                $category = $categoryLabels[$featured->category] ?? ['label' => 'Général', 'icon' => 'tag', 'color' => 'secondary'];
                            @endphp
                            <span class="badge bg-{{ $category['color'] }} mb-3">
                                <i class="fas fa-{{ $category['icon'] }} me-1"></i>{{ $category['label'] }}
                            </span>
                            <h2 class="mb-3" style="font-weight: 700; color: #1e3a8a;">{{ $featured->title }}</h2>
                            <p class="text-muted mb-4" style="font-size: 1.05rem;">{{ $featured->excerpt }}</p>
                            <div class="d-flex align-items-center gap-4 mb-4 text-muted">
                                <div>
                                    <i class="fas fa-user me-2"></i>
                                    <small>{{ $featured->author->name ?? 'EVC' }}</small>
                                </div>
                                <div>
                                    <i class="fas fa-calendar me-2"></i>
                                    <small>{{ $featured->created_at->format('d/m/Y') }}</small>
                                </div>
                                <div>
                                    <i class="fas fa-eye me-2"></i>
                                    <small>{{ number_format($featured->views_count) }} vues</small>
                                </div>
                            </div>
                            <a href="{{ url(request()->segment(1) . '/' . request()->segment(2) . '/' . request()->segment(3) . '/actualites/' . $featured->id) }}" 
                               class="btn btn-primary btn-lg px-4" style="border-radius: 10px;">
                                <i class="fas fa-arrow-right me-2"></i>Lire l'article
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filtres par catégorie -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-3">
                    <div class="d-flex gap-2 flex-wrap align-items-center">
                        <span class="text-muted me-2"><i class="fas fa-filter me-2"></i>Filtrer :</span>
                        <button class="btn btn-sm btn-primary active" data-filter="all" style="border-radius: 10px;">
                            <i class="fas fa-th me-2"></i>Toutes ({{ $stats['total'] }})
                        </button>
                        @foreach($stats['categories'] as $cat => $count)
                            @php
                                $info = $categoryInfo[$cat] ?? ['label' => ucfirst($cat), 'icon' => 'tag', 'color' => 'secondary'];
                            @endphp
                            <button class="btn btn-sm btn-outline-{{ $info['color'] ?? 'secondary' }}" data-filter="{{ $cat }}" style="border-radius: 10px;">
                                <i class="fas fa-{{ $info['icon'] }} me-2"></i>{{ $info['label'] }} ({{ $count }})
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grille des actualités -->
    <div class="row g-4" id="actualites-grid">
        @forelse($actualites->where('is_featured', false) as $actualite)
        <div class="col-md-6 col-lg-3 actualite-card" data-category="{{ $actualite->category }}">
            <div class="card h-100 border-0 shadow-sm hover-card" style="border-radius: 16px; transition: all 0.3s ease;">
                <!-- Image -->
                <div class="position-relative d-flex align-items-center justify-content-center" style="border-radius: 16px 16px 0 0; min-height: 250px; background-color: #f8f9fa; padding: 1rem;">
                    <img src="{{ asset('storage/' . $actualite->cover_image) }}" 
                         alt="{{ $actualite->title }}" 
                         class="img-fluid" 
                         style="max-height: 250px; width: auto; max-width: 100%; object-fit: contain; transition: transform 0.3s ease;">
                    @php
                        $categoryLabels = [
                            'general' => ['label' => 'Général', 'color' => 'secondary', 'icon' => 'newspaper'],
                            'formation' => ['label' => 'Formation', 'color' => 'primary', 'icon' => 'graduation-cap'],
                            'evenement' => ['label' => 'Événement', 'color' => 'info', 'icon' => 'calendar-alt'],
                            'partenariat' => ['label' => 'Partenariat', 'color' => 'success', 'icon' => 'handshake'],
                            'succes' => ['label' => 'Succès', 'color' => 'warning', 'icon' => 'trophy'],
                        ];
                        $category = $categoryLabels[$actualite->category] ?? ['label' => 'N/A', 'color' => 'secondary', 'icon' => 'tag'];
                    @endphp
                    <span class="badge bg-{{ $category['color'] }} position-absolute top-0 start-0 m-3" style="border-radius: 8px;">
                        <i class="fas fa-{{ $category['icon'] }} me-1"></i>{{ $category['label'] }}
                    </span>
                </div>

                <!-- Contenu -->
                <div class="card-body d-flex flex-column p-4">
                    <h5 class="card-title mb-3" style="font-weight: 600; color: #1e3a8a; line-height: 1.4; min-height: 50px;">
                        {{ Str::limit($actualite->title, 60) }}
                    </h5>
                    <p class="card-text text-muted mb-4 flex-grow-1" style="font-size: 0.95rem;">
                        {{ Str::limit($actualite->excerpt, 100) }}
                    </p>

                    <!-- Footer -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="d-flex align-items-center gap-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $actualite->created_at->format('d/m/Y') }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>
                                {{ number_format($actualite->views_count) }}
                            </small>
                        </div>
                        <a href="{{ url(request()->segment(1) . '/' . request()->segment(2) . '/' . request()->segment(3) . '/actualites/' . $actualite->id) }}" 
                           class="btn btn-sm btn-outline-primary" style="border-radius: 8px;">
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-newspaper fa-4x text-muted opacity-50"></i>
                    </div>
                    <h4 class="text-muted mb-2">Aucune actualité disponible</h4>
                    <p class="text-muted mb-0">Revenez plus tard pour découvrir les dernières nouvelles de l'EVC</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>
</div>

<style>
.hover-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
}

.hover-card:hover img {
    transform: scale(1.05);
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
}

.btn.active {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.bg-opacity-10 {
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.opacity-75 {
    opacity: 0.75;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('[data-filter]');
    const cards = document.querySelectorAll('.actualite-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.getAttribute('data-filter');
            
            // Update active button
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.classList.remove('btn-primary');
                const color = btn.getAttribute('data-filter') === 'all' ? 'primary' : 
                             (btn.classList.contains('btn-outline-secondary') ? 'secondary' :
                              btn.classList.contains('btn-outline-primary') ? 'primary' :
                              btn.classList.contains('btn-outline-info') ? 'info' :
                              btn.classList.contains('btn-outline-success') ? 'success' :
                              btn.classList.contains('btn-outline-warning') ? 'warning' : 'secondary');
                btn.className = `btn btn-sm btn-outline-${color}`;
                btn.style.borderRadius = '10px';
            });
            
            if (filter === 'all') {
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary', 'active');
            } else {
                this.classList.add('active');
            }

            // Filter cards with animation
            cards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-category') === filter) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    }, 10);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });

    // Initialize cards
    cards.forEach(card => {
        card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    });
});
</script>
@endsection
