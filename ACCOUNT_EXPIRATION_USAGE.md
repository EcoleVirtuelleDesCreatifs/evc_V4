# Guide d'Utilisation - Gestion des Comptes Expirés

## 📚 Vue d'ensemble

Ce système permet de gérer automatiquement les restrictions pour les comptes étudiants expirés.

---

## 🎯 Variables Globales Disponibles

Dans **toutes** les vues Blade, vous avez accès à ces variables :

```php
$isAccountExpired     // boolean - true si le compte est expiré
$canSubmitContent     // boolean - false si le compte est expiré
$accountDaysRemaining // int - Nombre de jours restants avant expiration
```

---

## 🔧 Directives Blade Personnalisées

### `@canCreate`

Affiche le contenu uniquement si le compte peut créer du contenu :

```blade
@canCreate
    <a href="{{ route('tp.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Nouveau TP
    </a>
@else
    <button class="btn btn-secondary" disabled>
        <i class="fas fa-lock"></i> Compte expiré
    </button>
@endcanCreate
```

### `@accountExpired`

Affiche le contenu uniquement si le compte est expiré :

```blade
@accountExpired
    <div class="alert alert-danger">
        Votre compte a expiré. Veuillez contacter l'administration.
    </div>
@endaccountExpired
```

---

## 📦 Composants Blade

### 1. Alerte Compte Expiré

Affiche automatiquement une bannière si le compte est expiré :

```blade
@include('components.expired-account-alert')
```

**Résultat** :
```
⚠️ Compte Expiré - Accès Limité
Vous ne pouvez plus créer ou soumettre de nouveaux contenus...
```

---

### 2. Bouton de Création Dynamique

Composant qui s'adapte automatiquement selon l'état du compte :

```blade
<x-create-button 
    route="{{ route('tp.create') }}" 
    icon="plus" 
    text="Nouveau TP" 
    variant="primary" 
/>
```

**Paramètres** :
- `route` : Route cible (requis)
- `icon` : Icône FontAwesome (défaut: "plus")
- `text` : Texte du bouton (défaut: "Créer")
- `variant` : Couleur Bootstrap (défaut: "primary")

**Compte actif** → Bouton cliquable bleu  
**Compte expiré** → Bouton grisé désactivé avec icône cadenas

---

## 💡 Exemples d'Utilisation

### Masquer un bouton complètement

```blade
@if($canSubmitContent)
    <a href="{{ route('tp.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Créer un TP
    </a>
@endif
```

### Désactiver un formulaire

```blade
<form method="POST" action="{{ route('tp.store') }}">
    @csrf
    
    @accountExpired
        <div class="alert alert-warning mb-3">
            Ce formulaire est désactivé car votre compte a expiré.
        </div>
    @endaccountExpired
    
    <input type="text" name="titre" {{ $isAccountExpired ? 'disabled' : '' }}>
    
    @canCreate
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    @else
        <button type="button" class="btn btn-secondary" disabled>
            Compte expiré
        </button>
    @endcanCreate
</form>
```

### Masquer un lien de menu

```blade
@if(!$isAccountExpired)
    <li>
        <a href="{{ route('bibliotheque.index') }}">
            <i class="fas fa-book"></i> Bibliothèque
        </a>
    </li>
@endif
```

### Carte d'action avec état

```blade
@canCreate
    <div class="col-md-4">
        <a href="{{ route('projets.create') }}" class="action-card">
            <i class="fas fa-folder-plus"></i>
            <h5>Nouveau Projet</h5>
        </a>
    </div>
@else
    <div class="col-md-4">
        <div class="action-card disabled" style="opacity: 0.5; cursor: not-allowed;">
            <i class="fas fa-lock"></i>
            <h5>Nouveau Projet</h5>
            <span class="badge bg-danger">Bloqué</span>
        </div>
    </div>
@endcanCreate
```

---

## 🛡️ Sécurité Backend

Le middleware `CheckAccountExpiration` bloque automatiquement :

```
❌ /*/tp/create
❌ /*/tp/store
❌ /*/projets/create
❌ /*/documents/upload
❌ /*/cvtheque/upload
❌ /*/fin-formation/submit
❌ /*/bibliotheque/* (tout)
❌ Toutes les requêtes POST/PUT/DELETE
```

**Même si un utilisateur contourne l'UI**, le backend bloquera la requête !

---

## 📊 Helper PHP

Utiliser dans les contrôleurs :

```php
use App\Helpers\AccountExpirationHelper;

// Vérifier si expiré
if (AccountExpirationHelper::isAccountExpired($user)) {
    return redirect()->back()->with('error', 'Compte expiré');
}

// Vérifier si peut soumettre
if (!AccountExpirationHelper::canSubmitContent($user)) {
    abort(403, 'Vous ne pouvez pas créer de contenu');
}

// Obtenir jours restants
$days = AccountExpirationHelper::getDaysRemaining($user);

// Obtenir date d'expiration
$date = AccountExpirationHelper::getExpirationDate($user);
```

---

## ✅ Checklist d'Implémentation

Pour chaque page avec création/soumission :

- [ ] Ajouter `@include('components.expired-account-alert')` en haut
- [ ] Entourer les boutons "Créer/Nouveau" avec `@canCreate`
- [ ] Entourer les formulaires avec `@if($canSubmitContent)`
- [ ] Masquer les uploads avec `@if(!$isAccountExpired)`
- [ ] Désactiver les inputs avec `{{ $isAccountExpired ? 'disabled' : '' }}`
- [ ] Tester avec un compte expiré

---

## 🧪 Tests

```bash
# Test 1: Compte actif peut créer
✅ Boutons visibles
✅ Formulaires actifs
✅ Upload possible

# Test 2: Compte expiré ne peut pas créer
❌ Boutons désactivés
❌ Formulaires bloqués
❌ Upload impossible
✅ Bannière d'alerte affichée

# Test 3: Backend bloque les requêtes
❌ POST vers create → 403
❌ PUT vers update → 403
✅ GET vers index → OK
```

---

## 🎨 Styles CSS Recommandés

```css
/* Carte désactivée */
.action-card.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    pointer-events: none;
    filter: grayscale(50%);
}

/* Bouton expiré */
.btn-expired {
    background: #6b7280;
    cursor: not-allowed;
    opacity: 0.7;
}

/* Badge bloqué */
.badge-blocked {
    background: #ef4444;
    font-size: 0.7rem;
}
```

---

## 📞 Support

Pour toute question, consultez :
- `AccountExpirationHelper.php`
- `CheckAccountExpiration.php`
- `ViewServiceProvider.php`

---

**Dernière mise à jour** : 08 Décembre 2025
