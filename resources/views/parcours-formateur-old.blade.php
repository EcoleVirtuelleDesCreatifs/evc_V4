@extends('layouts.app')

@section('title', 'Bilé Bossombra - Formateur Principal EVC | Expert Digital & Community Manager')
@section('description', 'Découvrez le parcours de Bilé Bossombra, Manager Digital et formateur principal de l\'École Virtuelle des Créatifs à Abidjan. Plus de 10 ans d\'expérience en marketing digital, développement web et formation professionnelle.')
@section('keywords', 'Bilé Bossombra, formateur digital Abidjan, expert marketing digital Côte d\'Ivoire, community manager Abidjan, formation digitale Côte d\'Ivoire, EVC formateur, développement web Abidjan, design graphique Abidjan, consultant digital Côte d\'Ivoire, expert SEO Abidjan')

@push('styles')
<!-- Structured Data - Person Schema -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "Bilé Bossombra",
  "jobTitle": "Manager Digital & Formateur Principal",
  "description": "Expert professionnel du digital avec plus de 10 ans d'expérience. Créateur de stratégies de communication pour garantir le succès à l'échelle internationale.",
  "url": "{{ url('/parcours-formateur') }}",
  "image": "{{ asset('images/bile-bossombra-formateur-evc.jpg') }}",
  "worksFor": {
    "@type": "EducationalOrganization",
    "name": "École Virtuelle des Créatifs",
    "url": "{{ url('/') }}",
    "address": {
      "@type": "PostalAddress",
      "addressLocality": "Abidjan",
      "addressCountry": "CI"
    }
  },
  "alumniOf": {
    "@type": "EducationalOrganization",
    "name": "École Virtuelle des Créatifs"
  },
  "knowsAbout": [
    "Marketing Digital",
    "Community Management",
    "Développement Web",
    "Design Graphique",
    "SEO",
    "Branding",
    "Stratégie Digitale",
    "Formation Professionnelle"
  ],
  "hasOccupation": {
    "@type": "Occupation",
    "name": "Formateur Digital",
    "occupationLocation": {
      "@type": "City",
      "name": "Abidjan"
    },
    "skills": [
      "Marketing Digital",
      "Community Management",
      "Développement Web",
      "Design Graphique",
      "SEO",
      "Publicité en ligne",
      "Gestion de projet digital"
    ]
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "telephone": "+225-07-47-25-95-07",
    "contactType": "Professional",
    "availableLanguage": ["French"]
  },
  "sameAs": [
    "https://wa.me/2250747259507"
  ]
}
</script>

<!-- Structured Data - BreadcrumbList -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Accueil",
      "item": "{{ url('/') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "Parcours Formateur",
      "item": "{{ url('/parcours-formateur') }}"
    }
  ]
}
</script>

<!-- Structured Data - ProfessionalService -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "ProfessionalService",
  "name": "Services de Formation Digitale - Bilé Bossombra",
  "description": "Formation professionnelle en marketing digital, développement web, design graphique et community management",
  "provider": {
    "@type": "Person",
    "name": "Bilé Bossombra"
  },
  "areaServed": {
    "@type": "Country",
    "name": "Côte d'Ivoire"
  },
  "serviceType": [
    "Formation Marketing Digital",
    "Formation Community Management",
    "Formation Développement Web",
    "Formation Design Graphique",
    "Conseil en Stratégie Digitale",
    "Audit Digital"
  ],
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "5",
    "bestRating": "5",
    "ratingCount": "1000"
  }
}
</script>


<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #ff9800;
        --accent: #ff6f00;
        --bg-dark: #0f172a;
        --bg-card: #1e293b;
        --text-primary: #f1f5f9;
        --text-secondary: #94a3b8;
        --red-tie: #dc2626;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body {
        background: var(--bg-dark);
        color: var(--text-primary);
        font-family: 'Inter', sans-serif;
    }

    /* Hero Section */
    .hero-section {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        padding: 140px 20px 80px;
        position: relative;
        overflow: hidden;
    }

    .hero-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0.1;
        background-image: radial-gradient(circle at 20% 50%, var(--primary) 0%, transparent 50%);
    }

    .hero-container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .hero-content h1 {
        font-size: 56px;
        font-weight: 900;
        margin-bottom: 16px;
        line-height: 1.1;
    }

    .hero-name {
        background: linear-gradient(135deg, var(--primary) 0%, #ffb74d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-badge {
        display: inline-block;
        padding: 8px 20px;
        background: rgba(220, 38, 38, 0.1);
        border: 2px solid rgba(220, 38, 38, 0.3);
        border-radius: 30px;
        color: var(--red-tie);
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 24px;
    }

    .hero-subtitle {
        font-size: 24px;
        color: var(--text-secondary);
        margin-bottom: 32px;
        line-height: 1.6;
    }

    .hero-image {
        position: relative;
    }

    .hero-image-wrapper {
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        padding: 8px;
        margin: 0 auto;
        box-shadow: 0 20px 60px rgba(255, 152, 0, 0.3);
    }

    .hero-image-inner {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--bg-card);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 120px;
        color: var(--primary);
    }

    .btn-primary {
        padding: 16px 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        color: white;
        border: none;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: transform 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(255, 152, 0, 0.4);
    }

    /* Stats Section */
    .stats-section {
        padding: 80px 20px;
        background: var(--bg-card);
    }

    .stats-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .stat-card {
        text-align: center;
        padding: 40px 20px;
        background: rgba(255, 152, 0, 0.05);
        border: 1px solid rgba(255, 152, 0, 0.2);
        border-radius: 20px;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
    }

    .stat-number {
        font-size: 48px;
        font-weight: 900;
        color: var(--primary);
        margin-bottom: 12px;
    }

    .stat-label {
        font-size: 16px;
        color: var(--text-secondary);
    }

    /* About Section */
    .about-section {
        padding: 100px 20px;
        background: var(--bg-dark);
    }

    .section-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 80px;
    }

    .section-title {
        font-size: 48px;
        font-weight: 800;
        margin-bottom: 20px;
        background: linear-gradient(135deg, var(--primary) 0%, #ffb74d 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .section-subtitle {
        font-size: 20px;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .about-content {
        max-width: 1000px;
        margin: 0 auto;
        background: var(--bg-card);
        padding: 60px;
        border-radius: 20px;
        border: 1px solid rgba(255, 152, 0, 0.2);
    }

    .about-text {
        font-size: 18px;
        line-height: 1.8;
        color: var(--text-secondary);
        margin-bottom: 24px;
    }

    /* Skills Section */
    .skills-section {
        padding: 100px 20px;
        background: var(--bg-card);
    }

    .skills-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
    }

    .skill-card {
        background: var(--bg-dark);
        padding: 40px;
        border-radius: 20px;
        border: 1px solid rgba(255, 152, 0, 0.2);
        transition: all 0.3s;
    }

    .skill-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
        box-shadow: 0 10px 30px rgba(255, 152, 0, 0.2);
    }

    .skill-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 24px;
    }

    .skill-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 16px;
    }

    .skill-list {
        list-style: none;
        padding: 0;
    }

    .skill-list li {
        padding: 8px 0;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
    }

    .skill-list li::before {
        content: '✓';
        color: var(--primary);
        font-weight: 800;
    }

    /* Clients Section */
    .clients-section {
        padding: 100px 20px;
        background: var(--bg-dark);
    }

    .clients-grid {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .client-card {
        background: var(--bg-card);
        padding: 30px;
        border-radius: 16px;
        border: 1px solid rgba(255, 152, 0, 0.1);
        text-align: center;
        transition: all 0.3s;
    }

    .client-card:hover {
        border-color: var(--primary);
        transform: translateY(-3px);
    }

    .client-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 8px;
    }

    .client-description {
        font-size: 14px;
        color: var(--text-secondary);
        line-height: 1.6;
    }

    /* CTA Section */
    .cta-section {
        padding: 100px 20px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
        text-align: center;
    }

    .cta-title {
        font-size: 48px;
        font-weight: 800;
        color: white;
        margin-bottom: 24px;
    }

    .cta-description {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 40px;
    }

    .btn-white {
        padding: 16px 40px;
        background: white;
        color: var(--primary);
        border: none;
        border-radius: 50px;
        font-size: 18px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
    }

    .btn-white:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    @media (max-width: 768px) {
        .hero-container {
            grid-template-columns: 1fr;
            text-align: center;
        }
        .hero-content h1 { font-size: 36px; }
        .hero-image-wrapper { width: 300px; height: 300px; }
        .about-content { padding: 30px; }
        .section-title { font-size: 32px; }
        .cta-title { font-size: 32px; }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-background"></div>
    <div class="hero-container">
        <div class="hero-content">
            <span class="hero-badge">🎩 L'HOMME À LA CRAVATE ROUGE</span>
            <h1>
                <span class="hero-name">Bilé Bossombra</span><br>
                <span style="color: var(--text-primary);">Manager Digital & Formateur Principal</span>
            </h1>
            <p class="hero-subtitle">
                Expert professionnel du digital avec plus de 10 ans d'expérience. Créateur de stratégies de communication pour garantir le succès à l'échelle internationale.
            </p>
            <a href="https://wa.me/2250747259507" target="_blank" class="btn-primary">
                <i class="fab fa-whatsapp"></i>
                Contactez-moi
            </a>
        </div>
        <div class="hero-image">
            <div class="hero-image-wrapper">
                <div class="hero-image-inner">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">10+</div>
            <div class="stat-label">Années d'Expérience</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">1000+</div>
            <div class="stat-label">Étudiants Formés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">50+</div>
            <div class="stat-label">Projets Réalisés</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">100%</div>
            <div class="stat-label">Satisfaction Client</div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="section-header">
        <h2 class="section-title">Qui est Bilé Bossombra ?</h2>
        <p class="section-subtitle">Un parcours d'excellence au service de la transformation digitale</p>
    </div>
    <div class="about-content">
        <p class="about-text">
            <strong>Bilé Bossombra</strong>, l'Homme à la cravate rouge, cumule plus de <strong>10 ans d'expérience</strong> dans le domaine du digital. Au fil des années, il a acquis une expertise solide dans divers domaines, devenant ainsi un créateur de contenus expérimenté.
        </p>
        <p class="about-text">
            Sa longue carrière l'a vu évoluer, s'adapter aux dernières tendances, et maîtriser l'art de la <strong>transformation numérique</strong>. Il a géré des campagnes et stratégies de communication complexes à l'échelle internationale.
        </p>
        <p class="about-text">
            Il a aidé des <strong>entreprises internationales</strong>, des <strong>personnalités publiques et politiques</strong>, des particuliers, des marques, etc. à atteindre des niveaux inégalés de visibilité et de succès en ligne.
        </p>
        <p class="about-text">
            Sa vaste expérience et connaissance du digital font de lui un <strong>spécialiste du digital</strong> reconnu et le <strong>formateur principal de l'École Virtuelle des Créatifs (EVC)</strong>.
        </p>
    </div>
</section>

<!-- Skills Section -->
<section class="skills-section">
    <div class="section-header">
        <h2 class="section-title">Domaines de Compétences</h2>
        <p class="section-subtitle">Une expertise complète en marketing digital, développement web et infographie</p>
    </div>
    <div class="skills-grid">
        <div class="skill-card">
            <div class="skill-icon">🎨</div>
            <h3 class="skill-title">Création Visuelle</h3>
            <ul class="skill-list">
                <li>Identité Visuelle & Charte Graphique</li>
                <li>Branding de marque</li>
                <li>Packaging de produit</li>
                <li>Affiches Corporates</li>
                <li>Stratégie visuelle</li>
            </ul>
        </div>

        <div class="skill-card">
            <div class="skill-icon">📱</div>
            <h3 class="skill-title">Communication Digitale</h3>
            <ul class="skill-list">
                <li>Gestion Réseaux Sociaux</li>
                <li>Stratégies Médias Sociaux</li>
                <li>Publicité Médias Sociaux</li>
                <li>Emailling Marketing</li>
                <li>Gestion d'image</li>
            </ul>
        </div>

        <div class="skill-card">
            <div class="skill-icon">💻</div>
            <h3 class="skill-title">Développement Web</h3>
            <ul class="skill-list">
                <li>Application web</li>
                <li>Site vitrine & institutionnel</li>
                <li>Blog & E-commerce</li>
                <li>Solution d'hébergement</li>
                <li>Installation de chatbot</li>
            </ul>
        </div>

        <div class="skill-card">
            <div class="skill-icon">🖥️</div>
            <h3 class="skill-title">Web Design</h3>
            <ul class="skill-list">
                <li>Interface Utilisateur (UI)</li>
                <li>Expérience Utilisateur (UX)</li>
                <li>Design Emotionnel</li>
                <li>Responsive design</li>
            </ul>
        </div>

        <div class="skill-card">
            <div class="skill-icon">💡</div>
            <h3 class="skill-title">Conseil</h3>
            <ul class="skill-list">
                <li>Audit digital & Relooking</li>
                <li>Copywritting & Branding</li>
                <li>Réseaux sociaux & Publicité</li>
                <li>Site web & Sécurité</li>
                <li>SEO & Tunnel de vente</li>
            </ul>
        </div>

        <div class="skill-card">
            <div class="skill-icon">🔍</div>
            <h3 class="skill-title">Référencement Web</h3>
            <ul class="skill-list">
                <li>Référencement naturel (SEO)</li>
                <li>Référencement payant (SEA)</li>
                <li>Publicité digitale</li>
                <li>Rédaction de contenu</li>
            </ul>
        </div>
    </div>
</section>

<!-- Clients Section -->
<section class="clients-section">
    <div class="section-header">
        <h2 class="section-title">Ils Lui Ont Fait Confiance</h2>
        <p class="section-subtitle">Des collaborations prestigieuses avec des organisations internationales</p>
    </div>
    <div class="clients-grid">
        <div class="client-card">
            <div class="client-name">MTN Côte d'Ivoire</div>
            <div class="client-description">Opérateur téléphonie mobile, fixe et data Internet</div>
        </div>
        <div class="client-card">
            <div class="client-name">Transsion Holdings</div>
            <div class="client-description">Multinationale Chinoise, plus grand fabricant de smartphones en Afrique</div>
        </div>
        <div class="client-card">
            <div class="client-name">PDCI-RDA</div>
            <div class="client-description">Parti politique ivoirien fondé en 1946</div>
        </div>
        <div class="client-card">
            <div class="client-name">Hon. Tidjane THIAM</div>
            <div class="client-description">Président du PDCI, ancien patron du Crédit Suisse</div>
        </div>
        <div class="client-card">
            <div class="client-name">Hon. Yasmina Ouegnin</div>
            <div class="client-description">Femme Politique Ivoirienne, Député de Cocody</div>
        </div>
        <div class="client-card">
            <div class="client-name">Global Initiative 4/14</div>
            <div class="client-description">Organisation religieuse basée à New York</div>
        </div>
        <div class="client-card">
            <div class="client-name">Institut 2iE</div>
            <div class="client-description">Centre d'enseignement supérieur au Burkina Faso</div>
        </div>
        <div class="client-card">
            <div class="client-name">PEMMS Chocolat</div>
            <div class="client-description">Artisan Chocolatier Ivoirien Made In Côte d'Ivoire</div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div>
        <h2 class="cta-title">Formez-vous avec un Expert</h2>
        <p class="cta-description">
            Rejoignez l'École Virtuelle des Créatifs et bénéficiez de l'expertise de Bilé Bossombra
        </p>
        <a href="{{ route('preinscription.start') }}" class="btn-white">
            <i class="fas fa-graduation-cap"></i>
            S'inscrire Maintenant
        </a>
    </div>
</section>
@endsection
