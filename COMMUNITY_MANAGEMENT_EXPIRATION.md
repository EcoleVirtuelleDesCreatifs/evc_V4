# Système d'Expiration - Community Management

## 📋 Vue d'ensemble

Le système d'expiration des comptes a été étendu pour **Community Management** avec une **durée de 3 mois** (au lieu de 4 mois pour Design Graphique).

---

## ⏱️ Durées par Formation

| Formation | Durée | Configuration |
|-----------|-------|---------------|
| **Design Graphique** | 4 mois | Par défaut |
| **Community Management** | 3 mois | ✅ Configuré |
| **Intelligence Artificielle** | 4 mois | Par défaut |
| **Gestion Informatique** | 4 mois | Par défaut |

---

## 🔧 Fichiers Modifiés

### **1. AccountExpirationHelper.php**

**Nouvelle méthode** : `getDefaultDurationMonths()`

```php
private static function getDefaultDurationMonths($program = null): int
{
    $durations = [
        'Design Graphique' => 4,
        'Community Management' => 3,  // ← 3 MOIS
        'Intelligence Artificielle' => 4,
        'Gestion Informatique' => 4,
    ];

    return $durations[$program] ?? 4;
}
```

**Méthode mise à jour** : `getExpirationDate()`
- Prend en compte le programme de l'étudiant
- Calcule automatiquement la bonne durée

---

### **2. DashboardController.php**

**Méthode** : `communityManagement()`

**Logique d'expiration** :
```php
// 1. Priorité : students.expiration_date
if ($student->expiration_date) {
    $expirationDate = parse($student->expiration_date);
}

// 2. Fallback : created_at + 3 mois
else {
    $durationMonths = 3; // Community Management
    $expirationDate = $accountCreatedAt->addMonths(3);
}
```

---

### **3. Vue community-management.blade.php**

**Créée** : `/resources/views/dashboard/community-management.blade.php`

**Fonctionnalités incluses** :
- ✅ Bannière d'expiration
- ✅ Compte à rebours (jours restants)
- ✅ Alertes expiration
- ✅ Désactivation automatique des boutons "Créer"
- ✅ Menu Bibliothèque masqué si expiré

---

## 🎯 Comportement Automatique

### **Compte Community Management Actif**

```
Création du compte : 08/12/2025
Durée : 3 mois
Expiration : 08/03/2026
Jours restants : 90 jours

Status : ✅ Actif
Badge : ✅ Actif (vert)
Actions : Toutes disponibles
```

---

### **Compte Community Management Expiré**

```
Création du compte : 08/09/2025
Durée : 3 mois
Expiration : 08/12/2025
Jours restants : 0 jours

Status : ❌ Inactif
Badge : ❌ Inactif (rouge)
Actions : Lecture seule
```

---

## 📊 Admin - Liste des Community Managers

**URL** : http://127.0.0.1:8000/evc/app/admin/etudiants/community-manager

**Affichage** :
- ✅ Jours restants calculés sur 3 mois
- ✅ Badge "Actif" si jours > 0
- ✅ Badge "Inactif" si jours = 0
- ✅ Réactivation automatique si admin prolonge

---

## 🔒 Restrictions pour Comptes Expirés

### **Bloqué pour Community Management Expiré**

```
❌ Créer un TP
❌ Soumettre un projet
❌ Publier un rapport
❌ Upload de documents
❌ Créer/Modifier CV
❌ Accéder à la bibliothèque
❌ Créer des tâches TODO
❌ Toutes les actions POST/PUT/DELETE
```

### **Autorisé pour Community Management Expiré**

```
✅ Se connecter
✅ Voir le dashboard
✅ Consulter les TP déjà soumis
✅ Voir les projets réalisés
✅ Lire les documents existants
✅ Consulter son profil
✅ Voir ses notes
✅ Navigation en lecture seule
```

---

## 🧪 Tests

### **Test 1 : Nouveau compte Community Management**

```bash
# Créer un compte
created_at = aujourd'hui
program = 'Community Management'

# Vérifier expiration
Expiration = created_at + 3 mois ✅
Jours restants = 90 jours ✅
Status = 'active' ✅
```

---

### **Test 2 : Compte expiré**

```bash
# Simuler expiration
created_at = il y a 4 mois
program = 'Community Management'

# Vérifier
Jours restants = 0 ✅
Status = 'inactive' ✅
Badge = Rouge "Inactif" ✅
Créer TP = Bloqué ✅
```

---

### **Test 3 : Prolongation par admin**

```bash
# Admin prolonge +3 mois
Nouvelle expiration = aujourd'hui + 3 mois

# Vérifier réactivation
Jours restants = 90 ✅
Status = 'active' (réactivé auto) ✅
Badge = Vert "Actif" ✅
Créer TP = Autorisé ✅
```

---

## 📝 Routes Community Management

```php
// Dashboard
GET /evc/compte/community-management/espace-etudiant

// Profil
GET /evc/compte/community-management/profil/editer

// Documents
GET /evc/compte/community-management/documents/index

// TP
GET /evc/compte/community-management/tp/index

// Projets
GET /evc/compte/community-management/projets/index

// Paramètres
GET /evc/compte/community-management/parametres/index
```

---

## 🔄 Middleware Actifs

```php
// Sur toutes les routes community-management
'auth'              // Authentification requise
'student.active'    // Compte actif (ou expiré sans raison)
'formation.access'  // Accès à la formation
'CheckAccountExpiration' // Vérification expiration (global)
```

---

## 💡 Scénarios Spéciaux

### **Scénario 1 : Compte créé avant le système**

```php
// Ancien compte sans expiration_date
created_at = 01/01/2024
expiration_date = NULL

// Calcul automatique
expiration = 01/01/2024 + 3 mois = 01/04/2024
→ Compte expiré
→ Status = 'inactive'
```

---

### **Scénario 2 : Admin définit date custom**

```php
// Admin définit manuellement
expiration_date = 31/12/2025

// Système utilise cette date
→ Priorité sur le calcul automatique
→ Durée ignorée (3 mois)
→ Expiration = 31/12/2025
```

---

### **Scénario 3 : Changement de formation**

```php
// Étudiant change de formation
program = 'Design Graphique' → 'Community Management'

// Recalcul nécessaire
Nouvelle expiration = created_at + 3 mois
→ Mettre à jour students.expiration_date
```

---

## 📈 Dashboard Community Management

**Variables disponibles** :

```blade
$isAccountExpired     // boolean
$canSubmitContent     // boolean
$accountDaysRemaining // int (jours restants)
$expirationDate       // Carbon (date d'expiration)
$isExpiringSoon       // boolean (< 30 jours)
$program              // 'Community Management'
$level                // Niveau de l'étudiant
```

---

## ✅ Checklist d'Implémentation

- [x] Helper avec durée 3 mois
- [x] DashboardController.communityManagement()
- [x] Vue community-management.blade.php
- [x] Bannière d'expiration
- [x] Compte à rebours
- [x] Boutons "Créer" désactivés si expiré
- [x] Menu Bibliothèque masqué si expiré
- [x] Middleware CheckAccountExpiration
- [x] Réactivation automatique
- [x] Admin : liste avec statut

---

## 🎓 Formation par Formation

| Formation | Durée | URL Admin | URL Dashboard |
|-----------|-------|-----------|---------------|
| Design Graphique | 4 mois | `/admin/etudiants/design-graphique` | `/compte/design-graphique/espace-etudiant` |
| Community Management | 3 mois | `/admin/etudiants/community-manager` | `/compte/community-management/espace-etudiant` |
| Intelligence Artificielle | 4 mois | `/admin/etudiants/intelligence-artificielle` | `/compte/intelligence-artificielle/espace-etudiant` |
| Gestion Informatique | 4 mois | `/admin/etudiants/gestion-informatique` | `/compte/gestion-informatique/espace-etudiant` |

---

## 🚀 Déploiement

**Aucune migration nécessaire** - Le système utilise la colonne `students.expiration_date` existante.

**Redémarrage** :
```bash
# Vider le cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Redémarrer le serveur
php artisan serve
```

---

## 📞 Support

Pour ajouter une nouvelle formation avec durée personnalisée :

1. Modifier `AccountExpirationHelper.php` :
```php
$durations = [
    'Nouvelle Formation' => 6, // 6 mois
];
```

2. Mettre à jour le DashboardController

3. Créer la vue dashboard

---

**Dernière mise à jour** : 08 Décembre 2025  
**Version** : 1.0  
**Statut** : ✅ Production Ready
