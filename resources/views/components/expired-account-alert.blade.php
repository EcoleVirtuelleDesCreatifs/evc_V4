@if(isset($isAccountExpired) && $isAccountExpired)
<div class="alert alert-warning border-0 shadow-sm mb-4" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); border-radius: 12px;">
    <div class="d-flex align-items-center">
        <div style="font-size: 2rem; margin-right: 1rem;">⚠️</div>
        <div class="flex-grow-1">
            <h5 class="mb-1" style="color: #7c2d12; font-weight: 600;">
                <i class="fas fa-exclamation-triangle me-2"></i>Compte Expiré - Accès Limité
            </h5>
            <p class="mb-0" style="color: #78350f; font-size: 0.95rem;">
                Vous ne pouvez plus créer ou soumettre de nouveaux contenus. Vous pouvez uniquement consulter vos données existantes.
                <strong>Contactez l'administration pour renouveler votre accès.</strong>
            </p>
        </div>
    </div>
</div>
@endif
