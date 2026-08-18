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

    .store-cart-toggle {
        position: fixed;
        top: 300px;
        right: 20px;
        z-index: 60;
        width: 54px;
        height: 54px;
        background: rgba(21, 26, 61, 0.9);
        border: 1px solid var(--border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffffff;
        font-size: 1.25rem;
        cursor: pointer;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
    }

    .store-cart-toggle:hover {
        background: var(--primary);
        transform: scale(1.05);
    }

    .store-cart-count {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 22px;
        height: 22px;
        background: var(--primary);
        color: #ffffff;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .store-categories {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 10px;
        margin-bottom: 32px;
    }

    .store-category {
        padding: 10px 22px;
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid var(--border);
        border-radius: 9999px;
        color: var(--text-secondary);
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .store-category:hover,
    .store-category.active {
        background: var(--primary);
        border-color: var(--primary);
        color: #ffffff;
    }

    .store-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .store-product {
        background: rgba(21, 26, 61, 0.7);
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .store-product:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
        border-color: rgba(255, 107, 53, 0.3);
    }

    .store-product-image {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.2) 0%, rgba(0, 212, 255, 0.1) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: var(--primary);
        position: relative;
    }

    .store-product-category {
        position: absolute;
        top: 12px;
        left: 12px;
        padding: 4px 12px;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 9999px;
        color: #ffffff;
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .store-product-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .store-product h3 {
        color: var(--text-primary);
        font-size: 1.0625rem;
        font-weight: 700;
        margin-bottom: 8px;
        line-height: 1.3;
    }

    .store-product p {
        color: var(--text-secondary);
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0 0 16px;
        flex: 1;
    }

    .store-product-price {
        color: var(--primary);
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 16px;
    }

    .store-product-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .store-product-actions button,
    .store-product-actions a {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 0.875rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s ease;
        border: none;
        text-decoration: none;
    }

    .store-product-buy {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: #ffffff;
    }

    .store-product-buy:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 107, 53, 0.4);
    }

    .store-product-add {
        background: transparent;
        color: var(--text-primary);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .store-product-add:hover {
        background: rgba(255, 255, 255, 0.08);
        border-color: var(--primary);
        color: var(--primary);
    }

    .store-cart {
        position: fixed;
        top: 0;
        right: 0;
        width: 100%;
        max-width: 420px;
        height: 100%;
        background: rgba(13, 19, 51, 0.98);
        border-left: 1px solid var(--border);
        box-shadow: -20px 0 60px rgba(0, 0, 0, 0.5);
        z-index: 9999;
        transform: translateX(100%);
        transition: transform 0.35s ease;
        display: flex;
        flex-direction: column;
    }

    .store-cart.active {
        transform: translateX(0);
    }

    .store-cart-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }

    .store-cart-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .store-cart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px;
        border-bottom: 1px solid var(--border);
    }

    .store-cart-header h3 {
        color: var(--text-primary);
        font-size: 1.25rem;
        font-weight: 700;
    }

    .store-cart-close {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-primary);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
    }

    .store-cart-close:hover {
        background: var(--primary);
        color: #ffffff;
    }

    .store-cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 24px;
    }

    .store-cart-empty {
        color: var(--text-secondary);
        text-align: center;
        padding: 40px 0;
    }

    .store-cart-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 12px;
    }

    .store-cart-item-info {
        flex: 1;
    }

    .store-cart-item-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 0.9375rem;
        margin-bottom: 4px;
    }

    .store-cart-item-price {
        color: var(--primary);
        font-size: 0.875rem;
        font-weight: 600;
    }

    .store-cart-item-remove {
        width: 32px;
        height: 32px;
        background: transparent;
        border: 1px solid rgba(255, 71, 87, 0.3);
        border-radius: 8px;
        color: #ff6b6b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.25s ease;
    }

    .store-cart-item-remove:hover {
        background: rgba(255, 71, 87, 0.1);
    }

    .store-cart-footer {
        padding: 24px;
        border-top: 1px solid var(--border);
    }

    .store-cart-total {
        color: var(--text-primary);
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
    }

    .store-cart-total span {
        color: var(--primary);
    }

    .store-cart-checkout {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 16px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border-radius: 12px;
        color: #ffffff;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.25s ease;
    }

    .store-cart-checkout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
    }

    .store-toast {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: rgba(21, 26, 61, 0.95);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px 24px;
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.4);
        z-index: 10000;
        opacity: 0;
        transition: all 0.35s ease;
    }

    .store-toast.active {
        transform: translateX(-50%) translateY(0);
        opacity: 1;
    }

    @media (max-width: 1024px) {
        .store-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .store-container {
            padding: 180px 16px 40px;
        }

        .store-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .store-product-image {
            height: 140px;
        }

        .store-cart {
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .store-grid {
            grid-template-columns: 1fr;
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

            <!-- Cart Toggle -->
            <button class="store-cart-toggle" id="cart-toggle" aria-label="Ouvrir le panier">
                <i class="fas fa-shopping-bag"></i>
                <span class="store-cart-count" id="cart-count">0</span>
            </button>

            <!-- Categories -->
            <div class="store-categories" id="category-filters">
                <button class="store-category active" data-category="all">Tous</button>
                <button class="store-category" data-category="livres">Livres</button>
                <button class="store-category" data-category="accessoires">Accessoires</button>
                <button class="store-category" data-category="ressources">Ressources pédagogiques</button>
                <button class="store-category" data-category="produits">Produits EVC</button>
                <button class="store-category" data-category="editions">Éditions limitées</button>
            </div>

            <!-- Product Grid -->
            <div class="store-grid" id="product-grid"></div>
        </div>
    </div>

    <!-- Cart Overlay -->
    <div class="store-cart-overlay" id="cart-overlay"></div>

    <!-- Cart Panel -->
    <div class="store-cart" id="cart-panel" role="dialog" aria-modal="true">
        <div class="store-cart-header">
            <h3>Mon panier</h3>
            <button class="store-cart-close" id="cart-close" aria-label="Fermer le panier">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="store-cart-items" id="cart-items">
            <div class="store-cart-empty">Votre panier est vide</div>
        </div>
        <div class="store-cart-footer">
            <div class="store-cart-total">
                <span>Total</span>
                <span id="cart-total">0 FCFA</span>
            </div>
            <a href="{{ route('preinscription.start') }}" class="store-cart-checkout" id="cart-checkout">
                <i class="fas fa-shopping-bag"></i>
                Commander
            </a>
        </div>
    </div>

    <!-- Toast -->
    <div class="store-toast" id="store-toast"></div>
</div>

<script>
    (function() {
        const products = [
            { id: 1, category: 'livres', icon: 'fa-book-open', name: 'Fondamentaux du Design', desc: 'Le guide complet pour débuter en design graphique.', price: 15000 },
            { id: 2, category: 'livres', icon: 'fa-palette', name: 'Adobe pour Tous', desc: 'Maîtrisez Photoshop, Illustrator et InDesign.', price: 22000 },
            { id: 3, category: 'accessoires', icon: 'fa-shopping-bag', name: 'Tote Bag EVC', desc: '100% coton, design créatif.', price: 5000 },
            { id: 4, category: 'accessoires', icon: 'fa-mug-hot', name: 'Mug EVC', desc: 'Édition limitée créative.', price: 4500 },
            { id: 5, category: 'ressources', icon: 'fa-file-video', name: 'Pack Vidéos Tutoriels', desc: '+50 tutoriels design et IA.', price: 18000 },
            { id: 6, category: 'ressources', icon: 'fa-file-download', name: 'Templates EVC', desc: 'Maquettes, mockups et templates.', price: 12000 },
            { id: 7, category: 'produits', icon: 'fa-tshirt', name: 'T-shirt EVC', desc: 'Design exclusif, plusieurs tailles.', price: 8000 },
            { id: 8, category: 'produits', icon: 'fa-sticky-note', name: 'Kit Créatif EVC', desc: 'Carnet, stylo et stickers.', price: 6500 },
            { id: 9, category: 'editions', icon: 'fa-award', name: 'Affiche Lauréats 2024', desc: 'Impression haute qualité, numérotée.', price: 25000 },
            { id: 10, category: 'editions', icon: 'fa-certificate', name: 'Box Collector EVC', desc: 'Disponible en 100 exemplaires.', price: 50000 }
        ];

        const categoryLabels = {
            livres: 'Livres',
            accessoires: 'Accessoires',
            ressources: 'Ressources',
            produits: 'Produits EVC',
            editions: 'Éditions limitées'
        };

        let cart = JSON.parse(localStorage.getItem('evc_cart') || '[]');

        function formatPrice(price) {
            return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
        }

        function renderProducts(filter) {
            const grid = document.getElementById('product-grid');
            grid.innerHTML = '';
            products.forEach(product => {
                if (filter !== 'all' && product.category !== filter) return;
                const card = document.createElement('div');
                card.className = 'store-product';
                card.dataset.category = product.category;
                card.innerHTML = `
                    <div class="store-product-image">
                        <i class="fas ${product.icon}"></i>
                        <span class="store-product-category">${categoryLabels[product.category]}</span>
                    </div>
                    <div class="store-product-body">
                        <h3>${product.name}</h3>
                        <p>${product.desc}</p>
                        <div class="store-product-price">${formatPrice(product.price)}</div>
                        <div class="store-product-actions">
                            <button class="store-product-buy" data-id="${product.id}">
                                <i class="fas fa-shopping-bag"></i> Commander
                            </button>
                            <button class="store-product-add" data-id="${product.id}">
                                <i class="fas fa-cart-plus"></i> Ajouter au panier
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
        }

        function updateCartUI() {
            const count = document.getElementById('cart-count');
            const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            count.textContent = totalItems;
            count.style.display = totalItems > 0 ? 'flex' : 'none';

            const itemsContainer = document.getElementById('cart-items');
            if (cart.length === 0) {
                itemsContainer.innerHTML = '<div class="store-cart-empty">Votre panier est vide</div>';
            } else {
                itemsContainer.innerHTML = cart.map(item => `
                    <div class="store-cart-item" data-id="${item.id}">
                        <div class="store-cart-item-info">
                            <div class="store-cart-item-title">${item.name}</div>
                            <div class="store-cart-item-price">${formatPrice(item.price)} x ${item.qty}</div>
                        </div>
                        <button class="store-cart-item-remove" aria-label="Retirer ${item.name}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `).join('');
            }

            const total = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            document.getElementById('cart-total').textContent = formatPrice(total);
            localStorage.setItem('evc_cart', JSON.stringify(cart));
        }

        function addToCart(id, open = false) {
            const product = products.find(p => p.id === id);
            if (!product) return;
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ id: product.id, name: product.name, price: product.price, qty: 1 });
            }
            updateCartUI();
            showToast(`${product.name} ajouté au panier`);
            if (open) openCart();
        }

        function removeFromCart(id) {
            cart = cart.filter(item => item.id !== id);
            updateCartUI();
        }

        function showToast(message) {
            const toast = document.getElementById('store-toast');
            toast.textContent = message;
            toast.classList.add('active');
            setTimeout(() => toast.classList.remove('active'), 2500);
        }

        function openCart() {
            document.getElementById('cart-panel').classList.add('active');
            document.getElementById('cart-overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeCart() {
            document.getElementById('cart-panel').classList.remove('active');
            document.getElementById('cart-overlay').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.getElementById('product-grid').addEventListener('click', function(e) {
            const btn = e.target.closest('button');
            if (!btn) return;
            const id = parseInt(btn.dataset.id);
            if (btn.classList.contains('store-product-buy')) {
                addToCart(id, true);
            } else if (btn.classList.contains('store-product-add')) {
                addToCart(id);
            }
        });

        document.getElementById('category-filters').addEventListener('click', function(e) {
            const btn = e.target.closest('button');
            if (!btn) return;
            document.querySelectorAll('.store-category').forEach(c => c.classList.remove('active'));
            btn.classList.add('active');
            renderProducts(btn.dataset.category);
        });

        document.getElementById('cart-toggle').addEventListener('click', openCart);
        document.getElementById('cart-close').addEventListener('click', closeCart);
        document.getElementById('cart-overlay').addEventListener('click', closeCart);

        document.getElementById('cart-items').addEventListener('click', function(e) {
            const btn = e.target.closest('.store-cart-item-remove');
            if (!btn) return;
            const item = btn.closest('.store-cart-item');
            removeFromCart(parseInt(item.dataset.id));
        });

        document.getElementById('cart-checkout').addEventListener('click', function() {
            if (cart.length === 0) {
                showToast('Votre panier est vide');
            } else {
                localStorage.setItem('evc_cart', JSON.stringify(cart));
            }
        });

        renderProducts('all');
        updateCartUI();
    })();
</script>
@endsection
