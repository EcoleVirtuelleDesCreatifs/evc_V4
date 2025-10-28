# 🧹 MVP CLEANUP CHECKLIST

## Fichiers à supprimer

Cette liste contient tous les fichiers backup, old et de test à supprimer pour nettoyer le projet MVP.

### ✅ Fichiers Backup Views (À supprimer)

```bash
# Actualités
resources/views/actualites/index.blade.php.backup
resources/views/actualites/index.blade.php.v2backup
resources/views/actualites/show.blade.php.backup

# Dashboard
resources/views/dashboard/community-management-v2.backup
resources/views/dashboard/community-management.blade.php.backup

# Documents
resources/views/documents/index.blade.php.backup

# Admin Documents
resources/views/admin/documents/all.blade.php.backup
resources/views/admin/documents/all.blade.php.old

# Admin Formations
resources/views/admin/formations/categories_backup.blade.php

# Admin Programmes
resources/views/admin/programmes/index_old.blade.php

# Admin Travaux
resources/views/admin/travaux/assigned-backup.blade.php
resources/views/admin/travaux/view-old-backup.blade.php

# Events
resources/views/events/index_old_backup.blade.php

# Programmes
resources/views/programme/index_old.blade.php

# TP
resources/views/tp/create.blade.php.backup
resources/views/tp/edit.blade.php.backup
resources/views/tp/index.blade.php.backup

# Bibliothèque
resources/views/bibliotheque/index_old.blade.php
```

### 📝 Commande de suppression automatique

```bash
# Depuis la racine du projet
cd /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC

# Supprimer tous les fichiers .backup
find resources/views -name "*.backup" -type f -delete

# Supprimer tous les fichiers _old
find resources/views -name "*_old*" -type f -delete

# Supprimer tous les fichiers .v2backup
find resources/views -name "*.v2backup" -type f -delete

# Vérifier les fichiers supprimés
echo "Nettoyage terminé !"
```

---

## 🗂️ Fichiers à conserver (NE PAS SUPPRIMER)

### Fichiers de développement importants
- `resources/views/admin/travaux/debug.blade.php` (utile pour debug)
- `resources/views/admin/travaux/diagnostic.blade.php` (utile pour diagnostic)
- `CONFIGURATION_EMAIL_TP.md` (documentation importante)

### Fichiers actifs
- Tous les fichiers sans suffixe .backup, _old, .v2backup

---

## 📂 Dossiers à analyser

### Dossier uploads (À vérifier)
```
public/uploads/tp/
public/uploads/photos/
```
**Action** : Vérifier si les fichiers sont utilisés, sinon les archiver

### Dossier assets (À optimiser)
```
public/assets/img/cover/
public/assets/img/founder/
```
**Action** : Compresser les images pour améliorer les performances

---

## 🔧 Optimisations à effectuer

### 1. Routes non utilisées
**Fichier** : `routes/web.php`

**Routes à commenter/désactiver pour MVP :**
```php
// Actualités (désactivées pour MVP)
// Route::get('/actualites', ...);
// Route::get('/actualite/{slug}', ...);

// Événements (désactivés pour MVP)
// Route::get('/evenements', ...);
// Route::get('/evenement/{slug}', ...);

// CVthèque complète (désactivée pour MVP)
// Route::get('/cvtheque/historique', ...);
```

### 2. Migrations à optimiser
**Action** : Vérifier si certaines migrations peuvent être fusionnées

### 3. Contrôleurs non utilisés
**À vérifier** :
- Contrôleurs pour fonctionnalités désactivées
- Méthodes non utilisées dans les contrôleurs

---

## 📊 Statistiques de nettoyage

### Avant nettoyage
- Fichiers backup : ~20 fichiers
- Taille estimée : ~500 KB

### Après nettoyage
- Fichiers backup : 0
- Espace libéré : ~500 KB
- Code plus propre et maintenable

---

## ✅ Checklist de validation

Après le nettoyage, vérifier :

- [ ] L'application fonctionne correctement
- [ ] Aucune erreur 404 sur les pages principales
- [ ] Les formulaires fonctionnent
- [ ] Les uploads fonctionnent
- [ ] Les emails sont envoyés
- [ ] Les statistiques s'affichent
- [ ] Le responsive fonctionne

---

## 🚀 Commandes de vérification

```bash
# Vérifier qu'il n'y a plus de fichiers backup
find resources/views -name "*.backup" -o -name "*_old*" -o -name "*.v2backup"

# Vérifier la taille du projet
du -sh .

# Nettoyer le cache Laravel
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Optimiser pour production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📝 Notes importantes

1. **Backup avant suppression** : Faire un commit Git avant de supprimer les fichiers
2. **Tests après nettoyage** : Tester toutes les fonctionnalités critiques
3. **Documentation** : Mettre à jour la documentation si nécessaire

---

**Date de création** : 28 Octobre 2025  
**Statut** : 🟡 En attente d'exécution
