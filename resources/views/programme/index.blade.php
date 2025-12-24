@extends('layouts.ki-admin')

@section('title', 'Programmes de Formation - EVC 2024')
@section('page-title', 'Programmes de Formation')

@section('content')
<!-- Header avec palette Instagram -->
<div class="row mb-4">
    <div class="col-12">
        <div class="instagram-header">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center gap-3">
                        <div class="icon-circle">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div>
                            <h3 class="mb-1" style="font-weight: 700; font-size: 1.8rem;">
                                Programmes de Formation
                            </h3>
                            <p class="mb-0 text-white-50">{{ $student->program ?? 'Votre formation' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <span class="badge" style="background: rgba(255,255,255,0.2); padding: 0.75rem 1.5rem; font-size: 1rem; border-radius: 30px;">
                            <i class="fas fa-file-pdf me-2"></i>
                            {{ $programmes->count() }} Programme(s) disponible(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Liste dynamique des programmes -->
<div class="row">
    <div class="col-12">
        @if($programmes->isEmpty())
            <!-- Message si aucun programme -->
            <div class="empty-state">
                <div class="text-center py-5">
                    <div class="icon-circle-large mb-4">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="mb-3" style="color: #1f2937; font-weight: 600;">Aucun programme disponible</h3>
                    <p class="text-muted mb-0">Les programmes de formation seront publiés prochainement par votre formateur.</p>
                </div>
            </div>
        @else
            <!-- Grille des programmes -->
            <div class="row g-4">
                @foreach($programmes as $programme)
                    <div class="col-md-6 col-lg-4">
                        <div class="programme-card instagram-card">
                            <!-- Icône PDF -->
                            <div class="pdf-icon-container mb-3">
                                <div class="pdf-icon">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                            </div>

                            <!-- Titre -->
                            <h4 class="programme-title mb-2">
                                {{ $programme->titre }}
                            </h4>

                            <!-- Description -->
                            @if($programme->description)
                                <p class="programme-description mb-3">
                                    {{ Str::limit($programme->description, 120) }}
                                </p>
                            @endif

                            <!-- Informations -->
                            <div class="programme-info mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="fas fa-graduation-cap" style="color: var(--instagram-pink);"></i>
                                    @php
                                        $badgeClass = 'bg-secondary text-white';
                                        if(stripos($programme->formation, 'design') !== false) {
                                            $badgeClass = 'bg-info text-white';
                                        } elseif(stripos($programme->formation, 'community') !== false) {
                                            $badgeClass = 'bg-warning text-dark';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">
                                        {{ $programme->formation }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fas fa-calendar" style="color: var(--instagram-pink);"></i>
                                    <span class="small text-muted">Publié le {{ \Carbon\Carbon::parse($programme->created_at)->format('d/m/Y') }}</span>
                                </div>
                            </div>

                            <!-- Bouton téléchargement -->
                            <a href="{{ \App\Models\MediaUrl::fromPath($programme->fichier_pdf) }}"
                               target="_blank"
                               class="instagram-btn w-100"
                               download>
                                <i class="fas fa-download me-2"></i>
                                Télécharger le PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
/* Palette Instagram */
:root {
    --instagram-purple: #1e3c72;
    --instagram-pink: #2a5298;
    --instagram-red: #4fc3f7;
    --instagram-orange: #60a5fa;
    --instagram-yellow: #93c5fd;
}

/* Header avec dégradé Instagram */
.instagram-header {
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-pink), var(--instagram-red));
    border-radius: 20px;
    color: white;
    box-shadow: 0 8px 32px rgba(42, 82, 152, 0.3);
    animation: fadeInDown 0.6s ease;
    margin-bottom: 2rem;
}

/* Icône circulaire avec effet glassmorphism */
.icon-circle {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    animation: pulse 2s infinite;
}

.icon-circle-large {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, rgba(30, 60, 114, 0.08), rgba(79, 195, 247, 0.12));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    color: var(--instagram-pink);
    margin: 0 auto;
}

/* Carte de programme avec style Instagram */
.programme-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    border: 2px solid transparent;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    animation: fadeInUp 0.6s ease;
}

.programme-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(42, 82, 152, 0.25);
    border-color: var(--instagram-pink);
}

/* Icône PDF avec dégradé */
.pdf-icon-container {
    display: flex;
    justify-content: center;
}

.pdf-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--instagram-pink), var(--instagram-red));
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 8px 24px rgba(42, 82, 152, 0.25);
    transition: all 0.3s ease;
}

.programme-card:hover .pdf-icon {
    transform: scale(1.1) rotate(5deg);
}

/* Titre du programme */
.programme-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1f2937;
    text-align: center;
    line-height: 1.4;
}

/* Description du programme */
.programme-description {
    color: #6b7280;
    font-size: 0.95rem;
    line-height: 1.6;
    text-align: center;
}

/* Informations du programme */
.programme-info {
    padding-top: 1rem;
    border-top: 1px solid #f3f4f6;
}

/* Bouton Instagram */
.instagram-btn {
    background: linear-gradient(135deg, var(--instagram-purple), var(--instagram-red));
    color: white;
    border: none;
    border-radius: 30px;
    padding: 0.875rem 1.5rem;
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    display: inline-block;
    text-align: center;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(42, 82, 152, 0.3);
    margin-top: auto;
}

.instagram-btn:hover {
    background: linear-gradient(135deg, var(--instagram-pink), var(--instagram-red));
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(42, 82, 152, 0.35);
    color: white;
}

/* État vide */
.empty-state {
    background: white;
    border-radius: 20px;
    padding: 4rem 2rem;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
}

/* Animations */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .instagram-header h3 {
        font-size: 1.5rem;
    }

    .icon-circle {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }

    .programme-card {
        padding: 1.5rem;
    }

    .pdf-icon {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }

    .programme-title {
        font-size: 1.1rem;
    }
}

/* Animation en cascade pour les cartes */
.programme-card:nth-child(1) { animation-delay: 0.1s; }
.programme-card:nth-child(2) { animation-delay: 0.2s; }
.programme-card:nth-child(3) { animation-delay: 0.3s; }
.programme-card:nth-child(4) { animation-delay: 0.4s; }
.programme-card:nth-child(5) { animation-delay: 0.5s; }
.programme-card:nth-child(6) { animation-delay: 0.6s; }
</style>
@endpush

@push('scripts')
<script>
// Animation au scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });

    document.querySelectorAll('.programme-card').forEach(card => {
        observer.observe(card);
    });
});
</script>
@endpush
