# 🚀 MVP - École Virtuelle des Créatifs

## Bienvenue dans la branche MVP !

Cette branche contient la version **Minimum Viable Product (MVP)** de la plateforme EVC, optimisée pour un lancement rapide avec les fonctionnalités essentielles.

---

## 📋 Table des matières

1. [Vue d'ensemble](#vue-densemble)
2. [Installation](#installation)
3. [Configuration](#configuration)
4. [Fonctionnalités](#fonctionnalités)
5. [Documentation](#documentation)
6. [Développement](#développement)
7. [Déploiement](#déploiement)

---

## 🎯 Vue d'ensemble

### Objectif du MVP

Fournir une plateforme fonctionnelle permettant :
- ✅ Aux étudiants de s'inscrire et suivre leurs formations
- ✅ Aux étudiants de soumettre et consulter leurs TP
- ✅ Aux administrateurs de gérer les étudiants et valider les TP
- ✅ Aux administrateurs de suivre les paiements

### Philosophie

> "Lancer rapidement, itérer intelligemment"

Le MVP se concentre sur les fonctionnalités **critiques** qui apportent le plus de valeur aux utilisateurs, tout en gardant la possibilité d'activer facilement les fonctionnalités avancées plus tard.

---

## 🛠️ Installation

### Prérequis

- PHP >= 8.0
- Composer
- MySQL >= 5.7
- Node.js & NPM (pour les assets)

### Étapes d'installation

```bash
# 1. Cloner le dépôt
git clone https://github.com/EcoleVirtuelleDesCreatifs/evc_V4.git
cd evc_V4

# 2. Basculer sur la branche MVP
git checkout MVP_V1

# 3. Installer les dépendances PHP
composer install

# 4. Installer les dépendances Node
npm install

# 5. Copier le fichier de configuration MVP
cp .env.mvp .env

# 6. Générer la clé d'application
php artisan key:generate

# 7. Configurer la base de données dans .env
# DB_DATABASE=evc_database
# DB_USERNAME=root
# DB_PASSWORD=

# 8. Exécuter les migrations
php artisan migrate

# 9. (Optionnel) Seed la base de données
php artisan db:seed

# 10. Compiler les assets
npm run build

# 11. Créer le lien symbolique pour le storage
php artisan storage:link

# 12. Lancer le serveur
php artisan serve
```

L'application sera accessible sur : http://127.0.0.1:8000

---

## ⚙️ Configuration

### Mode MVP

Le mode MVP est contrôlé par le fichier `config/mvp.php` et la variable d'environnement `MVP_MODE`.

```env
# Dans .env
MVP_MODE=true
```

### Activer/Désactiver des fonctionnalités

Éditez `config/mvp.php` :

```php
'features' => [
    'actualites' => false,      // Désactivé pour MVP
    'evenements' => false,      // Désactivé pour MVP
    'cvtheque_complete' => false, // Désactivé pour MVP
    'tp' => true,               // Activé
    'formations' => true,       // Activé
],
```

### Helper MVP

Utilisez le helper `MvpHelper` dans votre code :

```php
use App\Helpers\MvpHelper;

// Vérifier si une fonctionnalité est activée
if (MvpHelper::isFeatureEnabled('actualites')) {
    // Code pour les actualités
}

// Vérifier si un menu est visible
if (MvpHelper::isMenuVisible('admin', 'evenements')) {
    // Afficher le menu
}

// Rediriger si fonctionnalité désactivée
MvpHelper::redirectIfDisabled('certificats');
```

### Dans les vues Blade

```blade
@if(App\Helpers\MvpHelper::isFeatureEnabled('actualites'))
    <!-- Afficher les actualités -->
@else
    <div class="alert alert-info">
        {{ App\Helpers\MvpHelper::getMessage('coming_soon') }}
    </div>
@endif
```

---

## ✨ Fonctionnalités

### ✅ Fonctionnalités ACTIVÉES (MVP Core)

#### 🔐 Authentification
- Connexion étudiants et administrateurs
- Réinitialisation de mot de passe
- Gestion des sessions sécurisées

#### 📚 Formations
- 4 formations principales :
  - Design Graphique
  - Community Management
  - Gestion Informatique
  - Intelligence Artificielle
- Inscription aux formations
- Gestion des catégories et modules

#### 👥 Gestion des Étudiants
- Inscription et profils étudiants
- Modification de profil
- Photo de profil
- Filtrage par formation

#### 📝 Travaux Pratiques (TP)
- Création et assignation de TP
- Soumission de TP par les étudiants
- Validation/Rejet par les admins
- Notifications email automatiques
- Historique des TP

#### 📊 Projets
- Soumission de projets
- Validation par les admins
- Suivi des projets

#### 💰 Paiements
- Suivi des paiements étudiants
- Historique des paiements
- Statistiques

#### 📖 Bibliothèque
- Upload de ressources
- Téléchargement par formation
- Catégories de ressources

#### 📄 Programmes de Formation
- Upload de programmes PDF
- Téléchargement par les étudiants
- Filtrage par formation

#### 📊 Dashboard
- Dashboard étudiant avec statistiques
- Dashboard admin avec vue d'ensemble
- Statistiques essentielles

#### 👨‍💼 Gestion des Admins
- 3 rôles : Super Admin, Assistant, Comptable
- Permissions par rôle
- Gestion des profils admin

---

### ❌ Fonctionnalités DÉSACTIVÉES (Phase 2+)

Ces fonctionnalités sont développées mais désactivées pour le MVP :

- 📰 **Actualités** : Système complet de gestion des actualités
- 📅 **Événements** : Gestion des événements avec inscriptions
- 🎓 **CVthèque complète** : Profils CVthèque détaillés
- 🏆 **Certificats** : Génération de certificats
- 📈 **Statistiques avancées** : Graphiques et rapports détaillés

**Pour les activer** : Modifiez `config/mvp.php` et mettez la fonctionnalité à `true`.

---

## 📚 Documentation

### Fichiers de documentation MVP

- **`MVP_ROADMAP.md`** : Roadmap complète du MVP avec timeline
- **`MVP_FEATURES.md`** : Liste détaillée de toutes les fonctionnalités
- **`MVP_CLEANUP.md`** : Guide de nettoyage du code
- **`MVP_README.md`** : Ce fichier

### Documentation technique

- **Configuration** : `config/mvp.php`
- **Helper** : `app/Helpers/MvpHelper.php`
- **Migrations** : `database/migrations/`
- **Routes** : `routes/web.php`

---

## 💻 Développement

### Structure du projet

```
evc_V4/
├── app/
│   ├── Helpers/
│   │   └── MvpHelper.php          # Helper MVP
│   ├── Http/
│   │   └── Controllers/           # Contrôleurs
│   └── Models/                    # Modèles Eloquent
├── config/
│   └── mvp.php                    # Configuration MVP
├── database/
│   └── migrations/                # Migrations
├── resources/
│   └── views/                     # Vues Blade
├── routes/
│   └── web.php                    # Routes
├── .env.mvp                       # Config environnement MVP
├── MVP_ROADMAP.md                 # Roadmap MVP
├── MVP_FEATURES.md                # Liste fonctionnalités
├── MVP_CLEANUP.md                 # Guide nettoyage
└── MVP_README.md                  # Ce fichier
```

### Commandes utiles

```bash
# Nettoyer le cache
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Optimiser pour production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Compiler les assets
npm run build          # Production
npm run dev            # Développement

# Tests
php artisan test
```

### Ajouter une nouvelle fonctionnalité

1. Ajouter la fonctionnalité dans `config/mvp.php`
2. Utiliser `MvpHelper::isFeatureEnabled()` dans le code
3. Ajouter les routes nécessaires
4. Créer les vues et contrôleurs
5. Tester la fonctionnalité
6. Documenter dans `MVP_FEATURES.md`

---

## 🚀 Déploiement

### Checklist avant déploiement

- [ ] Tests passent (`php artisan test`)
- [ ] Configuration production validée (`.env`)
- [ ] Base de données migrée
- [ ] Assets compilés (`npm run build`)
- [ ] Cache optimisé
- [ ] SSL/HTTPS configuré
- [ ] Backup base de données effectué
- [ ] Monitoring en place

### Déploiement sur serveur

```bash
# 1. Mettre l'application en maintenance
php artisan down

# 2. Récupérer les dernières modifications
git pull origin MVP_V1

# 3. Installer les dépendances
composer install --no-dev --optimize-autoloader

# 4. Exécuter les migrations
php artisan migrate --force

# 5. Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Compiler les assets
npm run build

# 7. Remettre l'application en ligne
php artisan up
```

### Variables d'environnement production

```env
APP_ENV=production
APP_DEBUG=false
MVP_MODE=true

# Configurer selon votre serveur
DB_HOST=your-db-host
DB_DATABASE=your-db-name
DB_USERNAME=your-db-user
DB_PASSWORD=your-db-password

# Mail configuration
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
```

---

## 🐛 Dépannage

### L'application ne démarre pas

```bash
# Vérifier les permissions
chmod -R 775 storage bootstrap/cache

# Régénérer la clé
php artisan key:generate

# Vérifier la configuration
php artisan config:clear
```

### Erreur 500

```bash
# Activer le mode debug temporairement
APP_DEBUG=true

# Vérifier les logs
tail -f storage/logs/laravel.log
```

### Les routes ne fonctionnent pas

```bash
# Nettoyer le cache des routes
php artisan route:clear

# Vérifier les routes
php artisan route:list
```

---

## 📞 Support

### Ressources

- **Documentation Laravel** : https://laravel.com/docs
- **Dépôt GitHub** : https://github.com/EcoleVirtuelleDesCreatifs/evc_V4
- **Branche MVP** : https://github.com/EcoleVirtuelleDesCreatifs/evc_V4/tree/MVP_V1

### Contact

- **Email** : support@evc.ci
- **Issues GitHub** : https://github.com/EcoleVirtuelleDesCreatifs/evc_V4/issues

---

## 📝 Changelog

### Version MVP 1.0 (28 Octobre 2025)

**Ajouté :**
- ✅ Configuration MVP complète
- ✅ Helper MvpHelper pour gestion des features
- ✅ Documentation MVP (ROADMAP, FEATURES, CLEANUP)
- ✅ Nettoyage des fichiers backup
- ✅ Fichier .env.mvp

**Désactivé pour MVP :**
- ❌ Actualités
- ❌ Événements
- ❌ CVthèque complète
- ❌ Certificats
- ❌ Statistiques avancées

**Optimisé :**
- ⚡ Suppression des fichiers backup
- ⚡ Configuration des features flags
- ⚡ Documentation complète

---

## 🎯 Prochaines étapes

### Phase 2 (Post-MVP)
- Activer les actualités
- Activer les événements
- Améliorer les statistiques
- Ajouter la CVthèque complète

### Phase 3 (Future)
- Système de certificats
- Notifications in-app
- Application mobile (PWA)
- Intégration paiement en ligne

---

## 📄 Licence

Propriété de l'École Virtuelle des Créatifs © 2025

---

**Dernière mise à jour** : 28 Octobre 2025  
**Version** : MVP 1.0  
**Branche** : MVP_V1  
**Statut** : 🟢 Actif
