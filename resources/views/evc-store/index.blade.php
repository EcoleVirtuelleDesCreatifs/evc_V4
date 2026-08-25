@extends('layouts.app')

@section('title', 'EVC STORE - Boutique officielle')
@section('description', 'La boutique officielle de l\'École Virtuelle des Créatifs : livres, accessoires, ressources pédagogiques, produits EVC et éditions limitées.')
@section('keywords', 'evc store, boutique ecole design, livres design, accessoires creatives, ressources pedagogiques')

@push('styles')
<style>
    html {
        scroll-behavior: smooth;
    }

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
        margin-top: 60px;
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

    .store-filters {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 12px;
        max-width: 1000px;
        margin: 0 auto 24px;
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 16px 20px;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 12px;
    }

    .filter-group input, .filter-select {
        background: transparent;
        border: none;
        color: var(--text-primary);
        font-size: 0.875rem;
        outline: none;
        width: 120px;
    }

    .filter-group input::placeholder, .filter-select option {
        color: var(--text-secondary);
    }

    .filter-select {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 9px 12px;
    }

    .filter-promo {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--text-primary);
        font-size: 0.875rem;
        cursor: pointer;
    }

    .filter-btn {
        padding: 10px 18px;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.875rem;
        cursor: pointer;
        text-decoration: none;
    }

    .filter-btn.reset {
        background: rgba(255, 255, 255, 0.1);
        color: var(--text-primary);
    }

    .product-badges {
        position: absolute;
        top: 12px;
        right: 12px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .product-badge {
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .product-badge.promo { background: #dc3545; color: #fff; }
    .product-badge.in { background: #198754; color: #fff; }
    .product-badge.low { background: #ffc107; color: #000; }
    .product-badge.out { background: #6c757d; color: #fff; }

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
        grid-template-columns: repeat(3, 1fr);
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
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.45s ease forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        font-size: 1.25rem;
        font-weight: 800;
        margin-bottom: 10px;
        line-height: 1.25;
    }

    .store-product .product-desc {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.55;
        margin: 0 0 8px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .store-product .product-desc.expanded {
        -webkit-line-clamp: unset;
        overflow-y: auto;
        max-height: 240px;
    }

    .product-desc-toggle {
        color: var(--primary);
        font-weight: 700;
        cursor: pointer;
        font-size: 0.8rem;
        margin-bottom: 16px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .product-desc-toggle:hover {
        text-decoration: underline;
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

    .cart-promo {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
    }

    .cart-promo input {
        flex: 1;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        outline: none;
    }

    .cart-promo input::placeholder {
        color: var(--text-secondary);
    }

    .cart-summary-row {
        display: flex;
        justify-content: space-between;
        color: var(--text-secondary);
        font-size: 0.95rem;
        margin-bottom: 8px;
    }

    .cart-summary-row.discount {
        color: #20c997;
    }

    .store-cart-item-qty {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-left: auto;
        margin-right: 12px;
    }

    .store-cart-item-qty span {
        color: var(--text-primary);
        font-weight: 600;
        min-width: 20px;
        text-align: center;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        border: 1px solid var(--border);
        border-radius: 6px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .store-toast {
        position: fixed;
        top: 35%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.85) perspective(800px) rotateX(-12deg);
        width: 100%;
        max-width: 1100px;
        height: 161px;
        background: rgba(13, 19, 51, 0.98);
        border: 1px solid var(--border);
        border-radius: 28px;
        padding: 0 60px;
        color: #ffffff;
        font-weight: 700;
        font-size: 1.75rem;
        box-shadow: 0 35px 90px rgba(0, 0, 0, 0.65);
        z-index: 10001;
        opacity: 0;
        visibility: hidden;
        display: flex;
        align-items: center;
        gap: 24px;
        text-align: left;
        justify-content: flex-start;
        overflow: hidden;
        transition: all 0.55s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .store-toast i {
        font-size: 2.5rem;
    }

    .store-toast-message {
        flex: 1;
    }

    .store-toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 8px;
        width: 100%;
        background: rgba(255, 255, 255, 0.25);
    }

    .store-toast.active .store-toast-progress {
        width: 0%;
        transition: width 2500ms linear;
    }

    .store-toast.store-toast-success {
        border-color: rgba(34, 197, 94, 0.6);
        box-shadow: 0 35px 90px rgba(34, 197, 94, 0.25), 0 0 60px rgba(34, 197, 94, 0.15);
    }

    .store-toast.store-toast-success i,
    .store-toast.store-toast-success .store-toast-progress {
        color: #22c55e;
        background: #22c55e;
    }

    .store-toast.store-toast-warning {
        border-color: rgba(255, 107, 53, 0.6);
        box-shadow: 0 35px 90px rgba(255, 107, 53, 0.3), 0 0 60px rgba(255, 107, 53, 0.15);
    }

    .store-toast.store-toast-warning i,
    .store-toast.store-toast-warning .store-toast-progress {
        color: #ff6b35;
        background: #ff6b35;
    }

    .store-toast.active {
        transform: translate(-50%, -50%) scale(1) perspective(800px) rotateX(0deg);
        opacity: 1;
        visibility: visible;
    }

    .store-order-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10001;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: all 0.35s ease;
    }

    .store-order-modal.active {
        opacity: 1;
        visibility: visible;
    }

    .store-order-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
    }

    .store-order-content {
        position: relative;
        background: rgba(13, 19, 51, 0.98);
        border: 1px solid var(--border);
        border-radius: 24px;
        width: 100%;
        max-width: 520px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 30px 80px rgba(0, 0, 0, 0.6);
        padding: 36px;
        transform: scale(0.95);
        opacity: 0;
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .store-order-modal.active .store-order-content {
        transform: scale(1);
        opacity: 1;
    }

    .store-order-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .store-order-header h3 {
        color: var(--text-primary);
        font-size: 1.5rem;
        font-weight: 700;
    }

    .store-order-close {
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

    .store-order-close:hover {
        background: var(--primary);
        color: #ffffff;
    }

    .store-order-summary {
        background: rgba(21, 26, 61, 0.6);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .store-order-summary h4 {
        color: var(--primary);
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .store-order-summary ul {
        list-style: none;
        padding: 0;
        margin: 0 0 12px;
    }

    .store-order-summary li {
        color: var(--text-secondary);
        font-size: 0.875rem;
        padding: 6px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        display: flex;
        justify-content: space-between;
    }

    .store-order-summary li:last-child {
        border-bottom: none;
    }

    .store-order-summary li.order-delivery,
    .store-order-summary li.order-promo {
        color: var(--text-primary);
        font-weight: 600;
    }

    .store-order-summary li.order-promo {
        color: #20c997;
    }

    .store-order-summary .store-order-total {
        color: var(--text-primary);
        font-weight: 700;
        text-align: right;
        font-size: 1.0625rem;
    }

    .store-order-field {
        margin-bottom: 20px;
    }

    .store-order-field label {
        display: block;
        color: var(--text-primary);
        font-size: 0.9375rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .store-order-field label span {
        color: var(--primary);
        margin-left: 2px;
    }

    .order-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .order-option {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 10px 14px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border);
        border-radius: 10px;
        color: var(--text-primary);
        font-size: 0.9rem;
        cursor: pointer;
    }

    .order-option:has(input:checked) {
        border-color: var(--primary);
        background: rgba(255, 107, 53, 0.1);
    }

    .order-option input {
        accent-color: var(--primary);
    }

    .store-order-input,
    .store-order-textarea {
        width: 100%;
        padding: 14px 18px;
        background: rgba(10, 14, 39, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        color: var(--text-primary);
        font-size: 0.9375rem;
        font-family: inherit;
        transition: all 0.3s ease;
    }

    .store-order-input:focus,
    .store-order-textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.15);
    }

    .store-order-textarea {
        min-height: 100px;
        resize: vertical;
        line-height: 1.6;
    }

    .store-order-submit {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .store-order-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(255, 107, 53, 0.4);
    }

    .store-order-submit:disabled,
    .store-order-submit.loading {
        opacity: 0.85;
        cursor: wait;
        transform: none;
    }

    .store-order-submit:disabled i,
    .store-order-submit.loading i {
        animation: spin 0.8s linear infinite;
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

    .store-search {
        display: flex;
        align-items: center;
        gap: 10px;
        max-width: 560px;
        margin: 0 auto 28px;
        padding: 12px 18px;
        background: rgba(21, 26, 61, 0.7);
        border: 1px solid var(--border);
        border-radius: 9999px;
        color: var(--text-secondary);
    }

    .store-search input {
        flex: 1;
        background: transparent;
        border: none;
        color: var(--text-primary);
        font-size: 0.95rem;
        outline: none;
    }

    .store-search input::placeholder {
        color: var(--text-secondary);
        opacity: 0.7;
    }

    .product-image {
        width: 100%;
        height: auto;
        max-height: 220px;
        display: block;
        object-fit: contain;
    }

    .product-quick-view {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        color: #ffffff;
        font-weight: 700;
        font-size: 0.95rem;
        border: none;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.25s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .store-product-image:hover .product-quick-view {
        opacity: 1;
    }

    .product-modal-desc {
        color: var(--text-secondary);
        line-height: 1.65;
        margin-bottom: 24px;
    }

    .product-modal-stock {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.875rem;
        margin-bottom: 16px;
        padding: 6px 12px;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
    }

    .product-modal-variants {
        margin-bottom: 20px;
    }

    .variant-group {
        margin-bottom: 12px;
    }

    .variant-group label {
        display: block;
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 6px;
    }

    .variant-options {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .variant-option {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 6px 12px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--border);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .variant-option:hover, .variant-option:has(input:checked) {
        border-color: var(--primary);
        background: rgba(255, 107, 53, 0.1);
    }

    .variant-option input {
        accent-color: var(--primary);
    }

    .similar-products {
        border-top: 1px solid var(--border);
        padding-top: 20px;
    }

    .similar-products h4 {
        color: var(--text-primary);
        font-size: 1rem;
        margin-bottom: 12px;
    }

    .similar-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .similar-card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
    }

    .similar-card:hover {
        border-color: var(--primary);
    }

    .similar-card img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 8px;
    }

    .similar-card .name {
        color: var(--text-primary);
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .similar-card .price {
        color: var(--primary);
        font-size: 0.8rem;
        font-weight: 700;
    }

    .product-carousel {
        position: relative;
        text-align: center;
    }

    .product-carousel-main img {
        width: 100%;
        height: auto;
        max-height: 360px;
        display: block;
        object-fit: contain;
    }

    .product-carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: #ffffff;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-carousel-nav.prev {
        left: 12px;
    }

    .product-carousel-nav.next {
        right: 12px;
    }

    .product-carousel-dots {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 12px;
    }

    .product-carousel-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.4);
        cursor: pointer;
    }

    .product-carousel-dot.active {
        background: var(--primary);
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

            <!-- Filters -->
            <form class="store-filters" id="store-filters">
                <div class="filter-group search">
                    <i class="fas fa-search"></i>
                    <input type="search" id="product-search" placeholder="Rechercher un produit..." autocomplete="off" name="search" value="{{ request('search') }}">
                </div>
                <div class="filter-group price">
                    <input type="number" id="filter-min-price" placeholder="Prix min" min="0" name="min_price" value="{{ request('min_price') }}">
                    <input type="number" id="filter-max-price" placeholder="Prix max" min="0" name="max_price" value="{{ request('max_price') }}">
                </div>
                <select id="filter-stock" name="stock" class="filter-select">
                    <option value="" {{ request('stock') == '' ? 'selected' : '' }}>Toutes dispos</option>
                    <option value="in_stock" {{ request('stock') == 'in_stock' ? 'selected' : '' }}>En stock</option>
                    <option value="available" {{ request('stock') == 'available' ? 'selected' : '' }}>Stock OK</option>
                </select>
                <label class="filter-promo">
                    <input type="checkbox" id="filter-promo" name="promo" value="1" {{ request('promo') ? 'checked' : '' }}> Promotions
                </label>
                <button type="submit" class="filter-btn">Filtrer</button>
                <a href="{{ route('evc.store') }}" class="filter-btn reset">Réinitialiser</a>
            </form>

            <!-- Categories -->
            <div class="store-categories" id="category-filters">
                <button class="store-category active" data-category="all">Tous</button>
                @foreach($categories as $category)
                    <button class="store-category" data-category="{{ $category->slug }}">{{ $category->name }}</button>
                @endforeach
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
            <div class="cart-promo">
                <input type="text" id="cart-promo-code" placeholder="Code promo" maxlength="50">
                <button type="button" id="cart-promo-apply" class="filter-btn">Appliquer</button>
            </div>
            <div class="cart-summary-row"><span>Sous-total</span><span id="cart-subtotal">0 FCFA</span></div>
            <div class="cart-summary-row"><span>Livraison</span><span id="cart-delivery">0 FCFA</span></div>
            <div class="cart-summary-row discount"><span>Remise</span><span id="cart-discount">-0 FCFA</span></div>
            <div class="store-cart-total">
                <span>Total</span>
                <span id="cart-total">0 FCFA</span>
            </div>
            <button type="button" class="store-cart-checkout" id="cart-checkout">
                <i class="fas fa-shopping-bag"></i>
                Commander
            </button>
        </div>
    </div>

    <!-- Order Modal -->
    <div class="store-order-modal" id="order-modal" role="dialog" aria-modal="true">
        <div class="store-order-overlay" id="order-overlay"></div>
        <div class="store-order-content">
            <div class="store-order-header">
                <h3>Finaliser ma commande</h3>
                <button class="store-order-close" id="order-close" aria-label="Fermer">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="store-order-summary">
                <h4>Récapitulatif</h4>
                <ul id="order-items"></ul>
                <div class="store-order-total" id="order-total">Total : 0 FCFA</div>
            </div>

            <form id="order-form" novalidate>
                <div class="store-order-field">
                    <label for="order-nom">Nom <span>*</span></label>
                    <input type="text" id="order-nom" name="nom" class="store-order-input" required>
                </div>
                <div class="store-order-field">
                    <label for="order-prenoms">Prénoms <span>*</span></label>
                    <input type="text" id="order-prenoms" name="prenoms" class="store-order-input" required>
                </div>
                <div class="store-order-field">
                    <label for="order-numero">Numéro de téléphone <span>*</span></label>
                    <input type="tel" id="order-numero" name="numero" class="store-order-input" required>
                </div>
                <div class="store-order-field">
                    <label for="order-lieu">Adresse / Lieu de retrait <span>*</span></label>
                    <input type="text" id="order-lieu" name="lieu" class="store-order-input" required>
                </div>

                <div class="store-order-field">
                    <label>Mode de réception <span>*</span></label>
                    <div class="order-options">
                        <label class="order-option">
                            <input type="radio" name="delivery_mode" value="delivery" checked> Livraison
                        </label>
                        <label class="order-option">
                            <input type="radio" name="delivery_mode" value="pickup"> Retrait
                        </label>
                    </div>
                </div>

                <div class="store-order-field">
                    <label>Moyen de paiement <span>*</span></label>
                    <div class="order-options">
                        <label class="order-option">
                            <input type="radio" name="payment_method" value="cash" checked> Espèces à la livraison
                        </label>
                        <label class="order-option">
                            <input type="radio" name="payment_method" value="mobile_money"> Mobile Money
                        </label>
                    </div>
                </div>

                <div class="store-order-field">
                    <label for="order-autre">Autres informations</label>
                    <textarea id="order-autre" name="autre" class="store-order-textarea" placeholder="Instructions complémentaires..."></textarea>
                </div>
                <button type="submit" class="store-order-submit">
                    <i class="fas fa-paper-plane"></i>
                    Envoyer ma commande
                </button>
            </form>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="store-order-modal" id="product-modal" role="dialog" aria-modal="true">
        <div class="store-order-overlay" id="product-modal-overlay"></div>
        <div class="store-order-content" style="max-width: 680px;">
            <div class="store-order-header">
                <h3 id="product-modal-title">Fiche produit</h3>
                <button class="store-order-close" id="product-modal-close" aria-label="Fermer">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="product-modal-image" style="margin-bottom: 20px; border-radius: 16px; overflow: hidden; background: rgba(21,26,61,0.6);"></div>
            <div class="store-order-total" id="product-modal-price" style="margin-bottom: 12px; text-align: left; color: var(--primary); font-size: 1.35rem;">0 FCFA</div>
            <div class="product-modal-stock" id="product-modal-stock"></div>
            <div class="product-modal-variants" id="product-modal-variants"></div>
            <div class="product-modal-desc" id="product-modal-desc"></div>
            <div id="product-modal-similar" style="margin-bottom: 24px;"></div>
            <div style="display: flex; gap: 12px;">
                <button class="store-product-buy" id="product-modal-buy" style="flex: 1;">
                    <i class="fas fa-shopping-bag"></i> Commander
                </button>
                <button class="store-product-add" id="product-modal-add" style="flex: 1;">
                    <i class="fas fa-cart-plus"></i> Ajouter au panier
                </button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="store-toast" id="store-toast"></div>
</div>

<script>
    (function() {
        const products = @json($productsForJs);
        const categoryLabels = @json($categories->pluck('name', 'slug')->put('all', 'Tous')->toArray());

        let cart = JSON.parse(localStorage.getItem('evc_cart') || '[]');

        function formatPrice(price) {
            return new Intl.NumberFormat('fr-FR').format(price) + ' FCFA';
        }

        let currentFilter = 'all';

        function renderProducts(filter, query = '') {
            const grid = document.getElementById('product-grid');
            grid.innerHTML = '';
            const q = query.toLowerCase().trim();
            const minPrice = parseInt(document.getElementById('filter-min-price').value) || 0;
            const maxPrice = parseInt(document.getElementById('filter-max-price').value) || 0;
            const promoOnly = document.getElementById('filter-promo').checked;
            const stockFilter = document.getElementById('filter-stock').value;
            let visibleCount = 0;
            products.forEach(product => {
                if (filter !== 'all' && product.category !== filter) return;
                const matchesSearch = !q || product.name.toLowerCase().includes(q) || (product.desc && product.desc.toLowerCase().includes(q));
                if (!matchesSearch) return;
                if (product.price < minPrice) return;
                if (maxPrice > 0 && product.price > maxPrice) return;
                if (promoOnly && !product.is_promotion) return;
                if (stockFilter === 'in_stock' && product.stock <= 0) return;
                if (stockFilter === 'available' && product.stock <= 10) return;

                const stockLabel = product.stock_status === 'en_stock' ? 'En stock' : product.stock_status === 'stock_limite' ? 'Stock limité' : 'Rupture';
                const stockClass = product.stock_status === 'en_stock' ? 'in' : product.stock_status === 'stock_limite' ? 'low' : 'out';
                const promoBadge = product.is_promotion ? '<span class="product-badge promo">Promo</span>' : '';
                const priceDisplay = product.is_promotion
                    ? `<span style="text-decoration:line-through;opacity:.6;margin-right:8px;font-size:1rem;">${formatPrice(product.old_price)}</span> ${formatPrice(product.price)}`
                    : formatPrice(product.price);

                const card = document.createElement('div');
                card.className = 'store-product';
                card.dataset.category = product.category;
                card.innerHTML = `
                    <div class="store-product-image" style="height:auto;">
                        ${product.image_url ? `<img src="${product.image_url}" alt="${product.name}" class="product-image">` : `<i class="fas fa-image" style="font-size:4rem;color:var(--primary); padding: 40px 0; display:block; text-align:center;"></i>`}
                        <span class="store-product-category">${categoryLabels[product.category] || 'Non classé'}</span>
                        <div class="product-badges">
                            ${promoBadge}
                            <span class="product-badge ${stockClass}">${stockLabel}</span>
                        </div>
                        <button class="product-quick-view" data-id="${product.id}">
                            <i class="fas fa-eye"></i> Voir la fiche
                        </button>
                    </div>
                    <div class="store-product-body">
                        <h3>${product.name}</h3>
                        <div class="store-product-price">${priceDisplay}</div>
                        ${product.desc ? `<div class="product-desc">${product.desc}</div>` : ''}
                        <div class="store-product-actions">
                            <button class="store-product-buy" data-id="${product.id}" ${product.stock <= 0 ? 'disabled' : ''}>
                                <i class="fas fa-shopping-bag"></i> ${product.stock <= 0 ? 'Rupture' : 'Commander'}
                            </button>
                            <button class="store-product-add" data-id="${product.id}">
                                <i class="fas fa-cart-plus"></i> Panier
                            </button>
                        </div>
                    </div>
                `;
                grid.appendChild(card);
                visibleCount++;
            });
            if (visibleCount === 0) {
                grid.innerHTML = '<div class="text-center" style="grid-column:1/-1; color:var(--text-secondary); padding:40px;">Aucun produit ne correspond à vos critères.</div>';
            }
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
                    <div class="store-cart-item" data-id="${item.id}" data-variant="${item.variant || ''}">
                        <div class="store-cart-item-info">
                            <div class="store-cart-item-title">${item.name}</div>
                            <div class="store-cart-item-price">${formatPrice(item.price)} x ${item.qty}</div>
                        </div>
                        <div class="store-cart-item-qty">
                            <button class="qty-btn" data-action="minus"><i class="fas fa-minus"></i></button>
                            <span>${item.qty}</span>
                            <button class="qty-btn" data-action="plus"><i class="fas fa-plus"></i></button>
                        </div>
                        <button class="store-cart-item-remove" aria-label="Retirer ${item.name}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `).join('');
            }

            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const delivery = cart.reduce((sum, item) => sum + ((item.delivery_cost || 0) * item.qty), 0);
            const discount = parseInt(localStorage.getItem('evc_cart_discount') || '0');
            const total = subtotal + delivery - discount;

            document.getElementById('cart-subtotal').textContent = formatPrice(subtotal);
            document.getElementById('cart-delivery').textContent = formatPrice(delivery);
            document.getElementById('cart-discount').textContent = '-' + formatPrice(discount);
            document.getElementById('cart-total').textContent = formatPrice(total);
            localStorage.setItem('evc_cart', JSON.stringify(cart));
        }

        function addToCart(id, open = false, options = {}) {
            const product = products.find(p => p.id === id);
            if (!product) return;
            if (product.stock <= 0) {
                showToast(`${product.name} est en rupture de stock`, 'warning');
                return;
            }
            const variantName = Object.keys(options).length ? Object.entries(options).map(([k, v]) => `${k}: ${v}`).join(' / ') : null;
            const fullName = variantName ? `${product.name} - ${variantName}` : product.name;
            const inCart = cart.reduce((sum, item) => item.id === id && item.variant === variantName ? sum + item.qty : sum, 0);
            if (inCart + 1 > product.stock) {
                showToast(`Stock insuffisant pour ${fullName}`, 'warning');
                return;
            }
            const existing = cart.find(item => item.id === id && item.variant === variantName);
            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ id: product.id, name: fullName, price: product.price, delivery_cost: product.delivery_cost ?? 0, qty: 1, variant: variantName });
            }
            updateCartUI();
            showToast(`${fullName} ajouté au panier`);
            if (open) openOrderModal();
        }

        function removeFromCart(id, variant) {
            cart = cart.filter(item => !(item.id === id && item.variant === variant));
            updateCartUI();
        }

        function changeQty(id, variant, delta) {
            const item = cart.find(item => item.id === id && item.variant === variant);
            if (!item) return;
            const product = products.find(p => p.id === id);
            const newQty = item.qty + delta;
            if (newQty < 1) {
                removeFromCart(id, variant);
                return;
            }
            if (product && newQty > product.stock) {
                showToast(`Stock insuffisant pour ${item.name}`, 'warning');
                return;
            }
            item.qty = newQty;
            updateCartUI();
        }

        async function applyPromo() {
            const input = document.getElementById('cart-promo-code');
            const code = input.value.trim();
            if (!code) {
                showToast('Saisissez un code promo', 'warning');
                return;
            }
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            try {
                const res = await fetch('{{ route('evc.store.promo') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ code, subtotal })
                });
                const data = await res.json();
                if (data.success) {
                    localStorage.setItem('evc_cart_promo', code);
                    localStorage.setItem('evc_cart_discount', data.discount);
                    showToast(`Code promo appliqué : -${formatPrice(data.discount)}`);
                } else {
                    localStorage.removeItem('evc_cart_promo');
                    localStorage.setItem('evc_cart_discount', '0');
                    showToast(data.message || 'Code promo invalide', 'warning');
                }
            } catch (e) {
                localStorage.removeItem('evc_cart_promo');
                localStorage.setItem('evc_cart_discount', '0');
                showToast('Erreur de vérification', 'warning');
            }
            updateCartUI();
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('store-toast');
            const icon = type === 'warning' ? 'fa-exclamation-circle' : 'fa-check-circle';
            toast.innerHTML = `<i class="fas ${icon}"></i><span class="store-toast-message">${message}</span><span class="store-toast-progress"></span>`;
            toast.className = 'store-toast store-toast-' + type;
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
            const quickView = e.target.closest('.product-quick-view');
            if (quickView) {
                e.stopPropagation();
                openProductModal(parseInt(quickView.dataset.id));
                return;
            }
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
            currentFilter = btn.dataset.category;
            renderProducts(currentFilter, document.getElementById('product-search').value);
        });

        document.getElementById('store-filters').addEventListener('submit', function(e) {
            e.preventDefault();
            renderProducts(currentFilter, document.getElementById('product-search').value);
        });

        document.getElementById('store-filters').addEventListener('change', function(e) {
            if (e.target.id !== 'product-search') {
                renderProducts(currentFilter, document.getElementById('product-search').value);
            }
        });

        document.getElementById('cart-toggle').addEventListener('click', openCart);
        document.getElementById('cart-close').addEventListener('click', closeCart);
        document.getElementById('cart-overlay').addEventListener('click', closeCart);

        document.getElementById('cart-items').addEventListener('click', function(e) {
            const btn = e.target.closest('button');
            if (!btn) return;
            const item = btn.closest('.store-cart-item');
            const id = parseInt(item.dataset.id);
            const variant = item.dataset.variant || null;
            if (btn.classList.contains('store-cart-item-remove')) {
                removeFromCart(id, variant);
            } else if (btn.classList.contains('qty-btn')) {
                changeQty(id, variant, btn.dataset.action === 'plus' ? 1 : -1);
            }
        });

        document.getElementById('cart-promo-apply').addEventListener('click', applyPromo);

        document.getElementById('cart-checkout').addEventListener('click', function(e) {
            e.preventDefault();
            if (cart.length === 0) {
                showToast('Votre panier est vide', 'warning');
            } else {
                openOrderModal();
            }
        });

        function recalcOrderTotal() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const isPickup = document.querySelector('input[name="delivery_mode"]:checked')?.value === 'pickup';
            const delivery = isPickup ? 0 : cart.reduce((sum, item) => sum + ((item.delivery_cost || 0) * item.qty), 0);
            const discount = parseInt(localStorage.getItem('evc_cart_discount') || '0');
            const total = subtotal + delivery - discount;

            let deliveryRow = document.querySelector('#order-items .order-delivery span:last-child');
            if (deliveryRow) deliveryRow.textContent = formatPrice(delivery);

            document.getElementById('order-total').textContent = 'Total : ' + formatPrice(total);
            return { subtotal, delivery, discount, total };
        }

        function openOrderModal() {
            closeCart();
            const orderItems = document.getElementById('order-items');
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const delivery = cart.reduce((sum, item) => sum + ((item.delivery_cost || 0) * item.qty), 0);
            const discount = parseInt(localStorage.getItem('evc_cart_discount') || '0');
            const total = subtotal + delivery - discount;

            orderItems.innerHTML = cart.map(item => `
                <li><span>${item.name} x${item.qty}</span><span>${formatPrice(item.price * item.qty)}</span></li>
            `).join('');

            const promoCode = localStorage.getItem('evc_cart_promo') || '';
            const promoHtml = discount > 0
                ? `<li class="order-promo"><span>Code promo ${promoCode}</span><span>-${formatPrice(discount)}</span></li>`
                : '';

            orderItems.insertAdjacentHTML('beforeend', `
                <li class="order-delivery"><span>Livraison</span><span>${formatPrice(delivery)}</span></li>
                ${promoHtml}
            `);

            document.getElementById('order-total').textContent = 'Total : ' + formatPrice(total);
            document.getElementById('order-modal').classList.add('active');
            document.body.style.overflow = 'hidden';

            document.querySelectorAll('input[name="delivery_mode"]').forEach(r => r.addEventListener('change', recalcOrderTotal));
        }

        function closeOrderModal() {
            document.getElementById('order-modal').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.getElementById('order-close').addEventListener('click', closeOrderModal);
        document.getElementById('order-overlay').addEventListener('click', closeOrderModal);

        document.getElementById('order-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const nom = document.getElementById('order-nom').value.trim();
            const prenoms = document.getElementById('order-prenoms').value.trim();
            const numero = document.getElementById('order-numero').value.trim();
            const lieu = document.getElementById('order-lieu').value.trim();

            if (!nom || !prenoms || !numero || !lieu) {
                showToast('Veuillez remplir tous les champs obligatoires', 'warning');
                return;
            }

            const submitBtn = this.querySelector('.store-order-submit');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner"></i> Envoi en cours...';

            const autre = document.getElementById('order-autre').value.trim();
            const pricing = recalcOrderTotal();
            const deliveryMode = document.querySelector('input[name="delivery_mode"]:checked')?.value || 'delivery';
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked')?.value || 'cash';
            const promoCode = localStorage.getItem('evc_cart_promo') || null;

            const order = {
                nom: nom,
                prenoms: prenoms,
                numero: numero,
                lieu: lieu,
                delivery_mode: deliveryMode,
                payment_method: paymentMethod,
                autre: autre,
                items: cart,
                promo_code: promoCode,
                total: pricing.total
            };

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            fetch('{{ route('evc.store.order', [], false) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(order)
            })
            .then(response => response.json().then(data => ({ status: response.status, ok: response.ok, data })))
            .then(({ ok, data }) => {
                if (ok && data.success) {
                    console.log('Commande enregistrée :', data.order);
                    cart = [];
                    updateCartUI();
                    closeOrderModal();
                    showToast('Commande envoyée avec succès');
                    this.reset();
                } else {
                    const message = data.message || 'Erreur lors de l\'envoi de la commande';
                    showToast(message, 'warning');
                }
            })
            .catch(err => {
                console.error('Erreur commande :', err);
                showToast('Erreur réseau, veuillez réessayer', 'warning');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.classList.remove('loading');
                submitBtn.innerHTML = originalText;
            });
        });

        function getSelectedOptions() {
            const selected = {};
            document.querySelectorAll('.product-modal-variants .variant-group').forEach((group, i) => {
                const label = group.dataset.label;
                const checked = group.querySelector('input:checked');
                if (checked) selected[label] = checked.value;
            });
            return selected;
        }

        function openProductModal(id) {
            const product = products.find(p => p.id === id);
            if (!product) return;
            fetch(`/evc-store/track/${id}`).catch(() => {});
            document.getElementById('product-modal-title').textContent = product.name;
            document.getElementById('product-modal-price').textContent = (product.is_promotion ? `<span style="text-decoration:line-through;opacity:.6;margin-right:8px;">${formatPrice(product.old_price)}</span>` : '') + formatPrice(product.price);
            document.getElementById('product-modal-desc').innerHTML = product.description || product.desc || '';

            const stockLabel = product.stock_status === 'en_stock' ? 'En stock' : product.stock_status === 'stock_limite' ? 'Stock limité' : 'Rupture';
            const stockClass = product.stock_status === 'en_stock' ? 'text-success' : product.stock_status === 'stock_limite' ? 'text-warning' : 'text-danger';
            document.getElementById('product-modal-stock').innerHTML = `<i class="fas fa-circle ${stockClass}" style="font-size:0.5rem;"></i> ${stockLabel} (${product.stock} disponible${product.stock > 1 ? 's' : ''})`;

            const imageContainer = document.getElementById('product-modal-image');
            const images = product.images && product.images.length ? product.images : (product.image_url ? [product.image_url] : []);
            let currentImage = 0;

            function showImage(index) {
                if (images.length === 0) return;
                currentImage = (index + images.length) % images.length;
                const img = imageContainer.querySelector('.product-carousel-main img');
                if (img) img.src = images[currentImage];
                imageContainer.querySelectorAll('.product-carousel-dot').forEach((dot, i) => dot.classList.toggle('active', i === currentImage));
            }

            if (images.length === 0) {
                imageContainer.innerHTML = `<div style="padding:70px 0;text-align:center;"><i class="fas fa-image" style="font-size:5rem;color:var(--primary);"></i></div>`;
            } else if (images.length === 1) {
                imageContainer.innerHTML = `<div class="product-carousel"><img src="${images[0]}" alt="${product.name}" style="width:100%;height:auto;display:block;max-height:360px;object-fit:contain;"></div>`;
            } else {
                imageContainer.innerHTML = `
                    <div class="product-carousel">
                        <div class="product-carousel-main">
                            <img src="${images[0]}" alt="${product.name}">
                        </div>
                        <button class="product-carousel-nav prev" aria-label="Image précédente"><i class="fas fa-chevron-left"></i></button>
                        <button class="product-carousel-nav next" aria-label="Image suivante"><i class="fas fa-chevron-right"></i></button>
                        <div class="product-carousel-dots">
                            ${images.map((_, i) => `<button class="product-carousel-dot ${i === 0 ? 'active' : ''}" data-index="${i}" aria-label="Image ${i + 1}"></button>`).join('')}
                        </div>
                    </div>
                `;
                imageContainer.querySelector('.product-carousel-nav.prev').addEventListener('click', () => showImage(currentImage - 1));
                imageContainer.querySelector('.product-carousel-nav.next').addEventListener('click', () => showImage(currentImage + 1));
                imageContainer.querySelectorAll('.product-carousel-dot').forEach(dot => {
                    dot.addEventListener('click', () => showImage(parseInt(dot.dataset.index)));
                });
            }

            const variantsContainer = document.getElementById('product-modal-variants');
            if (product.variants && product.variants.length) {
                variantsContainer.innerHTML = product.variants.map((v, i) => `
                    <div class="variant-group" data-label="${v.label}">
                        <label>${v.label}</label>
                        <div class="variant-options">
                            ${(v.options || []).map((opt, j) => `
                                <label class="variant-option">
                                    <input type="radio" name="variant-${i}" value="${opt}" ${j === 0 ? 'checked' : ''}>
                                    <span>${opt}</span>
                                </label>
                            `).join('')}
                        </div>
                    </div>
                `).join('');
            } else {
                variantsContainer.innerHTML = '';
            }

            const similarContainer = document.getElementById('product-modal-similar');
            const similar = products.filter(p => p.id !== product.id && p.category === product.category).slice(0, 4);
            if (similar.length) {
                similarContainer.innerHTML = `
                    <div class="similar-products">
                        <h4><i class="fas fa-th-large me-2"></i>Produits similaires</h4>
                        <div class="similar-grid">
                            ${similar.map(p => `
                                <div class="similar-card" data-id="${p.id}">
                                    <img src="${p.image_url || '/images/placeholder.png'}" alt="${p.name}">
                                    <div class="name">${p.name}</div>
                                    <div class="price">${formatPrice(p.price)}</div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `;
                similarContainer.querySelectorAll('.similar-card').forEach(card => {
                    card.addEventListener('click', () => openProductModal(parseInt(card.dataset.id)));
                });
            } else {
                similarContainer.innerHTML = '';
            }

            document.getElementById('product-modal-buy').onclick = () => { closeProductModal(); addToCart(product.id, true, getSelectedOptions()); };
            document.getElementById('product-modal-add').onclick = () => addToCart(product.id, false, getSelectedOptions());
            document.getElementById('product-modal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeProductModal() {
            document.getElementById('product-modal').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.getElementById('product-modal-close').addEventListener('click', closeProductModal);
        document.getElementById('product-modal-overlay').addEventListener('click', closeProductModal);

        document.getElementById('product-search').addEventListener('input', function(e) {
            renderProducts(currentFilter, e.target.value);
        });

        renderProducts('all');
        updateCartUI();
    })();
</script>
@endsection
