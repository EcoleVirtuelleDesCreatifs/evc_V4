# Configuration Email pour l'Envoi des Identifiants Administrateurs

## 📧 Configuration requise

Pour que les emails d'identifiants soient envoyés automatiquement lors de la création d'un administrateur, vous devez configurer les paramètres MAIL dans votre fichier `.env`.

## ⚙️ Configuration dans .env

Ajoutez ou modifiez les lignes suivantes dans votre fichier `.env` :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="École Virtuelle des Créatifs"
```

## 📝 Options de Configuration

### 1. Gmail (Recommandé pour le développement)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
```

**Important :** Pour Gmail, vous devez générer un "Mot de passe d'application" :
1. Allez sur https://myaccount.google.com/security
2. Activez la validation en deux étapes
3. Générez un mot de passe d'application
4. Utilisez ce mot de passe dans `MAIL_PASSWORD`

### 2. Mailtrap (Pour les tests)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre-username-mailtrap
MAIL_PASSWORD=votre-password-mailtrap
MAIL_ENCRYPTION=tls
```

### 3. SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=votre-api-key-sendgrid
MAIL_ENCRYPTION=tls
```

### 4. Mailgun
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=votre-username-mailgun
MAIL_PASSWORD=votre-password-mailgun
MAIL_ENCRYPTION=tls
```

## 🧪 Test de Configuration

Pour tester que vos emails fonctionnent, vous pouvez utiliser la commande suivante dans Tinker :

```bash
php artisan tinker
```

Puis exécutez :

```php
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')
            ->subject('Test Email Configuration');
});
```

## 🔒 Sécurité

- **NE JAMAIS** commiter le fichier `.env` avec vos identifiants
- Utilisez des mots de passe d'application pour Gmail
- Limitez les permissions de vos clés API
- Utilisez HTTPS en production

## 📋 Fonctionnalités Implémentées

✅ Envoi automatique d'email lors de la création d'un admin
✅ Email avec design professionnel et moderne
✅ Inclut tous les identifiants nécessaires :
   - Nom complet
   - Adresse email
   - Mot de passe (en clair)
   - Rôle attribué
   - Lien de connexion direct

✅ Gestion des erreurs d'envoi (continue même si l'email échoue)
✅ Logs détaillés pour le debugging

## 📁 Fichiers Créés

1. `app/Mail/AdminAccountCreated.php` - Classe Mailable
2. `resources/views/emails/admin-account-created.blade.php` - Template email
3. Modification de `app/Http/Controllers/Admin/AdminManagementController.php`

## 🚀 Utilisation

Une fois configuré, créez simplement un administrateur via l'interface :
`http://127.0.0.1:8000/evc/app/admin/admins/create`

L'email sera envoyé automatiquement à l'adresse fournie avec tous les identifiants de connexion.

## 🐛 Debugging

Si les emails ne sont pas envoyés :

1. Vérifiez les logs Laravel : `storage/logs/laravel.log`
2. Vérifiez la configuration dans `.env`
3. Testez avec Mailtrap pour le développement
4. Vérifiez que le port n'est pas bloqué par un firewall

## 📞 Support

En cas de problème, vérifiez :
- La configuration SMTP dans `.env`
- Les logs Laravel dans `storage/logs/`
- Que PHP a l'extension `openssl` activée
- Les permissions sur le dossier `storage/`
