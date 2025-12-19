@extends('layouts.ki-admin')

@section('title', 'Mon Profil CVThèque - EVC')
@section('page-title', 'Mon Profil Professionnel')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }

    .profile-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    /* Header avec dégradé */
    .profile-header {
        background: linear-gradient(135deg, #003366 0%, #0066cc 100%);
        color: white;
        padding: 3rem 2rem;
        position: relative;
        overflow: hidden;
    }

    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    .profile-photo-container {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 5px solid white;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        margin: 0 auto 1.5rem;
        position: relative;
        z-index: 2;
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-photo-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
        font-weight: 700;
    }

    .profile-name {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .profile-title {
        font-size: 1.3rem;
        opacity: 0.9;
        text-align: center;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }

    .profile-stats {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 2rem;
        position: relative;
        z-index: 2;
    }

    .stat-item {
        text-align: center;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    /* Sections du profil */
    .profile-section {
        padding: 2rem;
        border-bottom: 1px solid #e9ecef;
    }

    .profile-section:last-child {
        border-bottom: none;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #003366;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-title i {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #003366 0%, #0066cc 100%);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .section-content {
        color: #374151;
        line-height: 1.8;
    }

    /* Compétences */
    .skills-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .skill-item {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1rem;
        border-radius: 12px;
        border-left: 4px solid #0066cc;
        transition: all 0.3s ease;
    }

    .skill-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 102, 204, 0.2);
    }

    .skill-name {
        font-weight: 600;
        color: #003366;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Contact */
    .contact-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .contact-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .contact-icon {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #003366 0%, #0066cc 100%);
        color: white;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .contact-info {
        flex: 1;
    }

    .contact-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.25rem;
    }

    .contact-value {
        font-weight: 600;
        color: #1f2937;
        word-break: break-all;
    }

    /* Documents */
    .documents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .document-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-radius: 12px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .document-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-color: #0066cc;
    }

    .document-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin: 0 auto 1rem;
    }

    .document-name {
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .document-status {
        font-size: 0.85rem;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        display: inline-block;
    }

    .status-available {
        background: #d4edda;
        color: #155724;
    }

    .status-missing {
        background: #f8d7da;
        color: #721c24;
    }

    .download-btn {
        background: linear-gradient(135deg, #003366 0%, #0066cc 100%);
        color: white;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        margin-top: 0.75rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .download-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 102, 204, 0.3);
        color: white;
    }

    /* Préférences */
    .preferences-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .preference-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 12px;
        text-align: center;
    }

    .preference-label {
        font-size: 0.85rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }

    .preference-value {
        font-weight: 600;
        color: #1f2937;
        font-size: 1.1rem;
    }

    /* Badge */
    .badge-custom {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .badge-yes {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .badge-no {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
    }

    /* Actions flottantes */
    .floating-actions {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        z-index: 1000;
    }

    .floating-btn {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .floating-btn:hover {
        transform: scale(1.1);
    }

    .btn-edit {
        background: linear-gradient(135deg, #0066cc 0%, #003366 100%);
    }

    .btn-print {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    }

    .btn-back {
        background: linear-gradient(135deg, #FF9900 0%, #ff6600 100%);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .profile-name {
            font-size: 2rem;
        }

        .profile-title {
            font-size: 1.1rem;
        }

        .profile-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .profile-section {
            padding: 1.5rem;
        }

        .floating-actions {
            bottom: 1rem;
            right: 1rem;
        }

        .floating-btn {
            width: 50px;
            height: 50px;
            font-size: 1.2rem;
        }
    }

    /* Animation d'entrée */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out;
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
</style>
@endpush

@section('content')
<div class="profile-container">
    <!-- Carte principale du profil -->
    <div class="profile-card fade-in-up">
        <!-- Header avec photo et infos principales -->
        <div class="profile-header">
            <div class="profile-photo-container">
                @php
                    $photoUrl = null;
                    if (!empty($userInfo->profile_photo)) {
                        $filename = basename($userInfo->profile_photo);
                        if (file_exists(public_path('uploads/photos/' . $filename))) {
                            $photoUrl = asset('uploads/photos/' . $filename);
                        } elseif (file_exists(public_path($userInfo->profile_photo))) {
                            $photoUrl = asset($userInfo->profile_photo);
                        } elseif (file_exists(public_path('storage/' . $userInfo->profile_photo))) {
                            $photoUrl = asset('storage/' . $userInfo->profile_photo);
                        }
                    }
                @endphp
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="{{ $userInfo->first_name }}" class="profile-photo">
                @else
                    <div class="profile-photo-placeholder">
                        {{ strtoupper(substr($userInfo->first_name ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </div>

            <h1 class="profile-name">{{ $userInfo->first_name }} {{ $userInfo->last_name }}</h1>
            <p class="profile-title">{{ $cvthequeProfile->professional_title ?? 'Designer Graphique' }}</p>

            <div class="profile-stats">
                <div class="stat-item">
                    <div class="stat-value">{{ $cvthequeProfile->years_experience ?? 0 }}</div>
                    <div class="stat-label">Années d'expérience</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ $completionScore }}%</div>
                    <div class="stat-label">Profil complété</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">{{ count($documents ?? []) }}</div>
                    <div class="stat-label">Documents</div>
                </div>
            </div>
        </div>

        <!-- Résumé professionnel -->
        @if($cvthequeProfile->professional_summary)
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-user"></i>
                À propos de moi
            </h2>
            <div class="section-content">
                <p>{{ $cvthequeProfile->professional_summary }}</p>
            </div>
        </div>
        @endif

        <!-- Compétences logicielles -->
        @if(!empty($cvthequeProfile->software_skills))
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-laptop-code"></i>
                Logiciels maîtrisés
            </h2>
            <div class="skills-grid">
                @foreach(json_decode($cvthequeProfile->software_skills, true) ?? [] as $skill)
                <div class="skill-item">
                    <div class="skill-name">
                        <i class="fas fa-check-circle text-success"></i>
                        {{ $softwareOptions[$skill] ?? $skill }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Compétences techniques -->
        @if(!empty($cvthequeProfile->technical_skills))
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-tools"></i>
                Compétences techniques
            </h2>
            <div class="skills-grid">
                @foreach(json_decode($cvthequeProfile->technical_skills, true) ?? [] as $skill)
                <div class="skill-item">
                    <div class="skill-name">
                        <i class="fas fa-star text-warning"></i>
                        {{ $skillsOptions[$skill] ?? $skill }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Langues -->
        @if(!empty($cvthequeProfile->languages))
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-language"></i>
                Langues
            </h2>
            <div class="skills-grid">
                @foreach(json_decode($cvthequeProfile->languages, true) ?? [] as $language)
                <div class="skill-item">
                    <div class="skill-name">
                        <i class="fas fa-globe text-primary"></i>
                        {{ $languageOptions[$language] ?? $language }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Informations de contact -->
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-address-card"></i>
                Coordonnées
            </h2>
            <div class="contact-grid">
                @if($cvthequeProfile->professional_email ?? $userInfo->email)
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-info">
                        <div class="contact-label">Email</div>
                        <div class="contact-value">{{ $cvthequeProfile->professional_email ?? $userInfo->email }}</div>
                    </div>
                </div>
                @endif

                @if($cvthequeProfile->professional_phone ?? $userInfo->phone)
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-info">
                        <div class="contact-label">Téléphone</div>
                        <div class="contact-value">{{ $cvthequeProfile->professional_phone ?? $userInfo->phone }}</div>
                    </div>
                </div>
                @endif

                @if($cvthequeProfile->linkedin_profile)
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fab fa-linkedin"></i>
                    </div>
                    <div class="contact-info">
                        <div class="contact-label">LinkedIn</div>
                        <div class="contact-value">
                            <a href="{{ $cvthequeProfile->linkedin_profile }}" target="_blank" class="text-primary">Voir le profil</a>
                        </div>
                    </div>
                </div>
                @endif

                @if($cvthequeProfile->behance_profile)
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fab fa-behance"></i>
                    </div>
                    <div class="contact-info">
                        <div class="contact-label">Behance</div>
                        <div class="contact-value">
                            <a href="{{ $cvthequeProfile->behance_profile }}" target="_blank" class="text-primary">Voir le portfolio</a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Documents disponibles -->
        @if(!empty($documents))
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-file-alt"></i>
                Mes documents
            </h2>
            <div class="documents-grid">
                @foreach($documents as $doc)
                <div class="document-card">
                    <div class="document-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="document-name">{{ $doc['name'] }}</div>
                    @if($doc['available'])
                        <span class="document-status status-available">Disponible</span>
                        <br>
                        <a href="{{ $doc['url'] }}" class="download-btn" target="_blank">
                            <i class="fas fa-download me-2"></i>Télécharger
                        </a>
                    @else
                        <span class="document-status status-missing">Non ajouté</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Préférences professionnelles -->
        <div class="profile-section">
            <h2 class="section-title">
                <i class="fas fa-briefcase"></i>
                Préférences professionnelles
            </h2>
            <div class="preferences-grid">
                <div class="preference-item">
                    <div class="preference-label">Type de poste</div>
                    <div class="preference-value">{{ $cvthequeProfile->job_type ?? 'Tout' }}</div>
                </div>
                <div class="preference-item">
                    <div class="preference-label">Télétravail</div>
                    <div class="preference-value">
                        <span class="badge-custom {{ $cvthequeProfile->remote_work ? 'badge-yes' : 'badge-no' }}">
                            {{ $cvthequeProfile->remote_work ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                </div>
                <div class="preference-item">
                    <div class="preference-label">Mobilité</div>
                    <div class="preference-value">
                        <span class="badge-custom {{ $cvthequeProfile->willing_to_relocate ? 'badge-yes' : 'badge-no' }}">
                            {{ $cvthequeProfile->willing_to_relocate ? 'Oui' : 'Non' }}
                        </span>
                    </div>
                </div>
                @if($cvthequeProfile->salary_expectation)
                <div class="preference-item">
                    <div class="preference-label">Prétentions salariales</div>
                    <div class="preference-value">{{ $cvthequeProfile->salary_expectation }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Actions flottantes -->
<div class="floating-actions">
    <a href="{{ route('design-graphique.cvtheque.index') }}" class="floating-btn btn-back" title="Retour">
        <i class="fas fa-arrow-left"></i>
    </a>
    <button onclick="window.print()" class="floating-btn btn-print" title="Imprimer">
        <i class="fas fa-print"></i>
    </button>
    <a href="{{ route('design-graphique.cvtheque.index') }}" class="floating-btn btn-edit" title="Modifier">
        <i class="fas fa-edit"></i>
    </a>
</div>
@endsection

@push('scripts')
<script>
    // Animation au scroll
    document.addEventListener('DOMContentLoaded', function() {
        const sections = document.querySelectorAll('.profile-section');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.6s ease-out';
                }
            });
        }, { threshold: 0.1 });

        sections.forEach(section => observer.observe(section));
    });

    // Style d'impression
    window.addEventListener('beforeprint', function() {
        document.querySelector('.floating-actions').style.display = 'none';
    });

    window.addEventListener('afterprint', function() {
        document.querySelector('.floating-actions').style.display = 'flex';
    });
</script>
@endpush
