# 🚀 Checklist de Déploiement - École Virtuelle des Créatifs

## 📋 Pré-requis Serveur

### Versions Requises
- ✅ **PHP**: 8.2 ou supérieur
- ✅ **MySQL**: 5.7+ ou MariaDB 10.3+
- ✅ **Composer**: 2.x
- ✅ **Node.js**: 18.x ou supérieur
- ✅ **NPM**: 9.x ou supérieur

### Extensions PHP Requises
```bash
php -m | grep -E 'pdo|pdo_mysql|mbstring|openssl|tokenizer|xml|ctype|json|bcmath|fileinfo|gd'
```

Extensions nécessaires:
- ✅ PDO
- ✅ pdo_mysql
- ✅ mbstring
- ✅ openssl
- ✅ tokenizer
- ✅ XML
- ✅ ctype
- ✅ JSON
- ✅ BCMath
- ✅ fileinfo
- ✅ GD (pour les images)

---

## 🔧 Configuration Serveur

### 1. Configuration Apache (.htaccess déjà présent)
```apache
php_value upload_max_filesize 50M
php_value post_max_size 50M
php_value max_execution_time 300
php_value max_input_time 300
php_value memory_limit 256M
```

### 2. Permissions des Dossiers
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 3. Créer le lien symbolique pour le storage
```bash
php artisan storage:link
```

---

## 📝 Configuration .env Production

### Variables Essentielles à Modifier

```env
# ==========================================
# APPLICATION
# ==========================================
APP_NAME="École Virtuelle des Créatifs"
APP_ENV=production
APP_KEY=base64:XXXXX  # Générer avec: php artisan key:generate
APP_DEBUG=false
APP_URL=https://votre-domaine.com

# ==========================================
# BASE DE DONNÉES
# ==========================================
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nom_base_de_donnees
DB_USERNAME=utilisateur_mysql
DB_PASSWORD=mot_de_passe_securise

# ==========================================
# MAIL (SMTP Production)
# ==========================================
MAIL_MAILER=smtp
MAIL_HOST=smtp.votre-domaine.com
MAIL_PORT=587
MAIL_USERNAME=contact@votre-domaine.com
MAIL_PASSWORD=mot_de_passe_email
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=contact@votre-domaine.com
MAIL_FROM_NAME="${APP_NAME}"

# Email admin pour notifications
ADMIN_EMAIL=admin@votre-domaine.com

# ==========================================
# CINETPAY - PRODUCTION
# ==========================================
CINETPAY_API_KEY=votre_api_key_production
CINETPAY_MODE=PRODUCTION

# Design Graphique
CINETPAY_DESIGN_SITE_ID=votre_site_id
CINETPAY_DESIGN_SECRET=votre_secret_key

# Community Management
CINETPAY_CM_SITE_ID=votre_site_id
CINETPAY_CM_SECRET=votre_secret_key

# Autres formations (utiliser les mêmes credentials si nécessaire)
CINETPAY_INFO_SITE_ID=votre_site_id
CINETPAY_INFO_SECRET=votre_secret_key
CINETPAY_IA_SITE_ID=votre_site_id
CINETPAY_IA_SECRET=votre_secret_key

# URLs de callback CinetPay (IMPORTANT - Remplacer par votre domaine)
CINETPAY_RETURN_URL=https://votre-domaine.com/evc/payment/return
CINETPAY_NOTIFY_URL=https://votre-domaine.com/evc/payment/webhook
CINETPAY_CANCEL_URL=https://votre-domaine.com/evc/payment/cancel

# ==========================================
# SESSION & CACHE
# ==========================================
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database

# ==========================================
# SÉCURITÉ
# ==========================================
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax
```

---

## 🗄️ Base de Données

### 1. Créer la base de données
```sql
CREATE DATABASE evc_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'evc_user'@'localhost' IDENTIFIED BY 'mot_de_passe_securise';
GRANT ALL PRIVILEGES ON evc_production.* TO 'evc_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Exécuter les migrations
```bash
php artisan migrate --force
```

### 3. (Optionnel) Seeders si nécessaire
```bash
php artisan db:seed --force
```

---

## 📦 Déploiement des Fichiers

### 1. Sur votre serveur, cloner ou uploader le projet
```bash
git clone votre-repo.git
# OU uploader via FTP/SFTP
```

### 2. Installer les dépendances PHP
```bash
composer install --optimize-autoloader --no-dev
```

### 3. Installer les dépendances NPM et compiler
```bash
npm install
npm run build
```

### 4. Générer la clé d'application (si pas déjà fait)
```bash
php artisan key:generate --force
```

### 5. Optimiser pour la production
```bash
# Cache des configurations
php artisan config:cache

# Cache des routes
php artisan route:cache

# Cache des vues
php artisan view:cache

# Optimiser l'autoloader
composer dump-autoload --optimize
```

---

## 🔐 Sécurité

### 1. Vérifications de sécurité

- ✅ `.env` n'est PAS commité dans Git (vérifié dans `.gitignore`)
- ✅ `APP_DEBUG=false` en production
- ✅ `APP_ENV=production`
- ✅ Clés API CinetPay en mode PRODUCTION
- ✅ Fichiers sensibles protégés (storage, .env, etc.)

### 2. Permissions critiques
```bash
# Le .env doit être en lecture seule
chmod 600 .env

# Seulement www-data peut écrire dans storage
chown -R www-data:www-data storage bootstrap/cache
```

### 3. Fichiers à NE PAS déployer
```
.env.local
.env.development
*_backup.php
*_corrupted.php
*_fixed.php
*_old.php
/node_modules
/tests (optionnel)
.git (optionnel, mais recommandé de garder pour versioning)
```

---

## 🌐 Configuration DNS et Domaine

### 1. Pointer le domaine vers votre serveur
```
Type: A
Nom: @
Valeur: IP_DE_VOTRE_SERVEUR
TTL: 3600
```

### 2. Sous-domaine www (optionnel)
```
Type: CNAME
Nom: www
Valeur: votre-domaine.com
TTL: 3600
```

### 3. Configuration VirtualHost Apache
```apache
<VirtualHost *:80>
    ServerName votre-domaine.com
    ServerAlias www.votre-domaine.com
    DocumentRoot /var/www/html/V4_EVC/public

    <Directory /var/www/html/V4_EVC/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/evc-error.log
    CustomLog ${APACHE_LOG_DIR}/evc-access.log combined
</VirtualHost>
```

### 4. SSL/HTTPS avec Let's Encrypt (FORTEMENT RECOMMANDÉ)
```bash
sudo apt install certbot python3-certbot-apache
sudo certbot --apache -d votre-domaine.com -d www.votre-domaine.com
```

---

## ✅ Tests Post-Déploiement

### 1. Vérifications de base
- [ ] Le site est accessible via https://votre-domaine.com
- [ ] La page d'accueil se charge correctement
- [ ] Les CSS/JS sont chargés (vérifier le réseau dans DevTools)
- [ ] Pas d'erreurs dans les logs: `tail -f storage/logs/laravel.log`

### 2. Tests fonctionnels
- [ ] **Connexion admin**: https://votre-domaine.com/evc/app/admin/login
- [ ] **Inscription étudiant**: Tester le parcours complet
- [ ] **Paiement CinetPay**: Tester en mode PRODUCTION avec petit montant
- [ ] **Webhook CinetPay**: Vérifier que les webhooks fonctionnent
- [ ] **Envoi d'emails**: Tester l'envoi (inscription, notifications)
- [ ] **Upload de fichiers**: TP, projets, photos de profil
- [ ] **Dashboard étudiant**: Vérifier les statistiques
- [ ] **Comptabilité**: Vérifier que les paiements s'enregistrent

### 3. Performance
```bash
# Vérifier les temps de réponse
curl -w "@curl-format.txt" -o /dev/null -s https://votre-domaine.com
```

### 4. Monitoring
```bash
# Surveiller les logs en temps réel
tail -f storage/logs/laravel.log
tail -f /var/log/apache2/evc-error.log
```

---

## 🐛 Problèmes Connus et Solutions

### Problème: Webhooks CinetPay ne fonctionnent pas
**Solution**: 
- Vérifier que `CINETPAY_NOTIFY_URL` pointe vers votre domaine HTTPS
- Vérifier dans le dashboard CinetPay que les URLs de callback sont correctes
- Tester avec le simulateur webhook en développement d'abord

### Problème: Emails ne partent pas
**Solution**:
- Vérifier les credentials SMTP dans `.env`
- Tester avec: `php artisan tinker` puis `Mail::raw('Test', function($m) { $m->to('test@email.com')->subject('Test'); });`
- Vérifier les logs: `storage/logs/laravel.log`

### Problème: Images/fichiers ne s'affichent pas
**Solution**:
```bash
php artisan storage:link
chmod -R 755 storage/app/public
```

### Problème: Erreur 500
**Solution**:
```bash
# Activer temporairement le mode debug
APP_DEBUG=true

# Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📊 Monitoring et Maintenance

### Logs à surveiller
```bash
# Logs Laravel
tail -f storage/logs/laravel.log

# Logs Apache
tail -f /var/log/apache2/error.log
tail -f /var/log/apache2/access.log

# Logs MySQL
tail -f /var/log/mysql/error.log
```

### Sauvegardes Automatiques
```bash
# Créer un script de sauvegarde quotidienne
# /root/backup-evc.sh

#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u evc_user -p'password' evc_production > /backups/evc_db_$DATE.sql
tar -czf /backups/evc_storage_$DATE.tar.gz storage/app
find /backups -type f -mtime +30 -delete  # Supprimer les sauvegardes > 30 jours
```

### Cron Jobs (si nécessaire)
```bash
# Ajouter dans crontab -e
* * * * * cd /var/www/html/V4_EVC && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🎯 Checklist Finale

### Avant le déploiement
- [ ] `.env` configuré avec valeurs de production
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Clés CinetPay PRODUCTION configurées
- [ ] SMTP email configuré et testé
- [ ] Base de données créée
- [ ] DNS configuré et propagé
- [ ] SSL/HTTPS configuré
- [ ] VirtualHost Apache configuré

### Pendant le déploiement
- [ ] `composer install --optimize-autoloader --no-dev`
- [ ] `npm install && npm run build`
- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`
- [ ] Permissions correctes (755 storage, 600 .env)

### Après le déploiement
- [ ] Site accessible en HTTPS
- [ ] Connexion admin fonctionnelle
- [ ] Test inscription étudiant
- [ ] Test paiement CinetPay (petit montant)
- [ ] Test webhook CinetPay
- [ ] Test envoi emails
- [ ] Vérifier comptabilité automatique
- [ ] Monitoring logs activé
- [ ] Sauvegardes configurées

---

## 🆘 Support et Contacts

**En cas de problème:**
1. Vérifier les logs: `storage/logs/laravel.log`
2. Vérifier la configuration: `php artisan config:show`
3. Tester la connectivité DB: `php artisan tinker` puis `DB::connection()->getPdo();`
4. Vérifier les permissions: `ls -la storage`

**Clés API importantes:**
- CinetPay Dashboard: https://dashboard.cinetpay.com
- Chariow (si utilisé): https://mychariow.shop

---

## 📝 Notes Importantes

### Différences Local vs Production
- **Local**: Utilise simulateur webhook pour tests
- **Production**: Webhooks CinetPay réels

### URLs à mettre à jour en production
1. Configuration CinetPay (`config/cinetpay.php`):
   - ✅ Déjà configuré avec `env()` - Juste mettre à jour `.env`

2. Emails avec liens:
   - ✅ Utilisent `url()` Laravel - Automatique si `APP_URL` correct

### Fichiers de backup à supprimer
```bash
find app/Http/Controllers -name "*_backup.php" -delete
find app/Http/Controllers -name "*_corrupted.php" -delete
find app/Http/Controllers -name "*_fixed.php" -delete
find app/Http/Controllers -name "*_old.php" -delete
```

---

**Version du document**: 1.0  
**Dernière mise à jour**: 19 Décembre 2025  
**Status**: ✅ Prêt pour déploiement
