@extends('layouts.app')

@section('title', 'EVC STORE - Boutique officielle')
@section('description', 'La boutique officielle de l\'École Virtuelle des Créatifs : livres, accessoires, ressources pédagogiques, produits EVC et éditions limitées.')
@section('keywords', 'evc store, boutique ecole design, livres design, accessoires creatives, ressources pedagogiques')

@push('styles')
<style>
    .store-wrapper {
        --primary: #ff6b35;
        --primary-dark: #e55a2b;
        --accent: #00d4ff;
        --bg-dark: #0a0e27;
        --bg-card: #151a3d;
        --text-primary: #ffffff;
        --text-secondary: #a0aec0;
        --border: rgba(255, 255, 255, 0.1);
    }

    .store-container {
        background: linear-gradient(135deg, var(--bg-dark) 0%, #1a1f4e 50%, #0d1333 100%);
        min-height: 100vh;
        padding: 280px 20px 60px;
        position: relative;
    }

    .store-hero {
        text-align: center;
        margin-bottom: 32px;
    }

    .store-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 12px;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .store-subtitle {
        font-size: clamp(1rem, 2vw, 1.125rem);
        color: var(--text-secondary);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .store-content {
        max-width: 860px;
        margin: 0 auto;
    }

    .store-intro {
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 24px 32px;
        margin-bottom: 24px;
        text-align: center;
        color: var(--text-secondary);
        font-size: 1rem;
        line-height: 1.6;
    }

    .store-section {
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        backdrop-filter: blur(20px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
    }

    .store-section-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .store-section-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: #ffffff;
    }

    .store-section-header h2 {
        color: var(--primary);
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.01em;
    }

    .store-section-header p {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        margin: 0;
    }

    .store-products {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .store-product {
        background: rgba(10, 14, 39, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .store-product:hover {
        background: rgba(255, 107, 53, 0.08);
        border-color: rgba(255, 107, 53, 0.2);
    }

    .store-product-image {
        width: 100%;
        height: 120px;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.2) 0%, rgba(0, 212, 255, 0.1) 100%);
        border-radius: 10px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--primary);
    }

    .store-product h3 {
        color: var(--text-primary);
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .store-product p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        margin: 0 0 12px;
    }

    .store-product-price {
        color: var(--primary);
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .store-product-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 20px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 8px;
        color: #ffffff;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .store-product-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
    }

    .store-cta {
        text-align: center;
        margin-top: 32px;
        padding: 24px;
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid var(--border);
        border-radius: 16px;
    }

    .store-cta p {
        color: var(--text-secondary);
        font-size: 0.9375rem;
        margin-bottom: 16px;
    }

    .store-cta-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 16px 40px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
        text-decoration: none;
    }

    .store-cta-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 32px rgba(255, 107, 53, 0.5);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .store-container {
            padding: 180px 20px 60px;
        }

        .store-products {
            grid-template-columns: 1fr;
        }

        .store-section {
            padding: 24px;
        }
    }
</style>
@endpush

@section('content')
<div class="store-wrapper">
    <div class="store-container">
        <div class="container">
            <!-- Hero Section -->
            <div class="store-hero">
                <h1 class="store-title">EVC STORE</h1>
                <p class="store-subtitle">La boutique officielle de l'école. Découvrez nos produits dédiés à la créativité et à l'apprentissage.</p>
            </div>

            <div class="store-content">
                <div class="store-intro">
                    <i class="fas fa-store mr-2" style="color: var(--primary);"></i>
                    Notre boutique regroupe des articles sélectionnés pour les créatifs en herbe et les professionnels : livres, accessoires, ressources pédagogiques, produits EVC et éditions limitées.
                </div>

                <!-- Livres -->
                <div class="store-section">
                    <div class="store-section-header">
                        <div class="store-section-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <h2>Livres</h2>
                            <p>Ouvrages sur le design, le numérique et la créativité</p>
                        </div>
                    </div>
                    <div class="store-products">
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-book-open"></i></div>
                            <h3>Fondamentaux du Design</h3>
                            <p>Le guide complet pour débuter</p>
                            <div class="store-product-price">15.000 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-palette"></i></div>
                            <h3>Adobe pour Tous</h3>
                            <p>Maîtrisez Photoshop, Illustrator et InDesign</p>
                            <div class="store-product-price">22.000 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                    </div>
                </div>

                <!-- Accessoires -->
                <div class="store-section">
                    <div class="store-section-header">
                        <div class="store-section-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </div>
                        <div>
                            <h2>Accessoires</h2>
                            <p>Matériel créatif et goodies EVC</p>
                        </div>
                    </div>
                    <div class="store-products">
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-shopping-bag"></i></div>
                            <h3>Tote Bag EVC</h3>
                            <p>100% coton, design créatif</p>
                            <div class="store-product-price">5.000 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-mug-hot"></i></div>
                            <h3>Mug EVC</h3>
                            <p>Édition limitée créative</p>
                            <div class="store-product-price">4.500 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                    </div>
                </div>

                <!-- Ressources pédagogiques -->
                <div class="store-section">
                    <div class="store-section-header">
                        <div class="store-section-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div>
                            <h2>Ressources pédagogiques</h2>
                            <p>Supports et contenus pour apprendre efficacement</p>
                        </div>
                    </div>
                    <div class="store-products">
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-file-video"></i></div>
                            <h3>Pack Vidéos Tutoriels</h3>
                            <p>+50 tutoriels design et IA</p>
                            <div class="store-product-price">18.000 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-file-download"></i></div>
                            <h3>Templates EVC</h3>
                            <p>Maquettes, mockups et templates</p>
                            <div class="store-product-price">12.000 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                    </div>
                </div>

                <!-- Produits EVC -->
                <div class="store-section">
                    <div class="store-section-header">
                        <div class="store-section-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div>
                            <h2>Produits EVC</h2>
                            <p>Articles officiels et inspirations créatives</p>
                        </div>
                    </div>
                    <div class="store-products">
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-tshirt"></i></div>
                            <h3>T-shirt EVC</h3>
                            <p>Design exclusif, disponible en plusieurs tailles</p>
                            <div class="store-product-price">8.000 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-sticky-note"></i></div>
                            <h3>Kit Créatif EVC</h3>
                            <p>Carnet, stylo et stickers</p>
                            <div class="store-product-price">6.500 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                    </div>
                </div>

                <!-- Éditions limitées -->
                <div class="store-section">
                    <div class="store-section-header">
                        <div class="store-section-icon">
                            <i class="fas fa-gem"></i>
                        </div>
                        <div>
                            <h2>Éditions limitées</h2>
                            <p>Produits exclusifs et numérotés</p>
                        </div>
                    </div>
                    <div class="store-products">
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-award"></i></div>
                            <h3>Affiche Lauréats 2024</h3>
                            <p>Impression haute qualité, numérotée</p>
                            <div class="store-product-price">25.000 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                        <div class="store-product">
                            <div class="store-product-image"><i class="fas fa-certificate"></i></div>
                            <h3>Box Collector EVC</h3>
                            <p>Disponible en 100 exemplaires</p>
                            <div class="store-product-price">50.000 FCFA</div>
                            <a href="#" class="store-product-btn"><i class="fas fa-eye"></i> Voir</a>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="store-cta">
                    <p>Intéressé par nos produits ? Contactez-nous pour commander ou en savoir plus.</p>
                    <a href="{{ route('preinscription.start') }}" class="store-cta-button">
                        <i class="fas fa-envelope"></i>
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
