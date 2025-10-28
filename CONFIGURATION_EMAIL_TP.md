# Configuration de l'envoi d'emails pour les notifications de TP

## 📧 Fonctionnalité implémentée

Lorsqu'un administrateur envoie un TP aux étudiants depuis la page `/evc/app/admin/travaux/to-send`, chaque étudiant destinataire reçoit automatiquement une notification par email contenant :

- Le titre du TP
- La description complète
- La date limite de soumission
- Le nombre de fichiers joints (si applicable)
- Un lien direct vers la page des TP pour consulter et soumettre le travail

## 🎨 Design de l'email

L'email utilise la **palette de couleurs Instagram** pour une cohérence visuelle avec l'interface de la plateforme :
- Dégradé violet-rose (#833AB4 → #C13584 → #E1306C)
- Design moderne et responsive
- Animation subtile sur l'icône
- Mise en évidence de la date limite

## 📁 Fichiers créés/modifiés

### 1. Classe Mail
**Fichier :** `app/Mail/TpAssignmentNotification.php`
- Gère la construction et l'envoi de l'email
- Paramètres : étudiant, titre, description, deadline, formation, nombre de fichiers

### 2. Template Email
**Fichier :** `resources/views/emails/tp-assignment-notification.blade.php`
- Template HTML responsive et moderne
- Affichage des détails du TP
- Bouton CTA pour accéder aux TP

### 3. Contrôleur mis à jour
**Fichier :** `app/Http/Controllers/Admin/AdminDashboardController.php`
- Méthode `sendTravaux()` mise à jour (lignes 1457-1469)
- Envoi automatique d'email à chaque étudiant après l'assignation du TP

## ⚙️ Configuration requise dans le fichier `.env`

Pour que l'envoi d'emails fonctionne, vous devez configurer les paramètres SMTP dans votre fichier `.env` :

### Option 1 : Gmail (Recommandé pour les tests)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-application
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="École Virtuelle des Créatifs"
```

**Important pour Gmail :**
1. Activez l'authentification à deux facteurs sur votre compte Gmail
2. Générez un "Mot de passe d'application" : https://myaccount.google.com/apppasswords
3. Utilisez ce mot de passe d'application dans `MAIL_PASSWORD`

### Option 2 : Mailtrap (Recommandé pour le développement)

Mailtrap capture tous les emails envoyés sans les envoyer réellement (parfait pour les tests) :

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre-username-mailtrap
MAIL_PASSWORD=votre-password-mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ecolevirtuelledescreateurs.com
MAIL_FROM_NAME="École Virtuelle des Créatifs"
```

Créez un compte gratuit sur : https://mailtrap.io

### Option 3 : SendGrid (Recommandé pour la production)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=votre-api-key-sendgrid
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@ecolevirtuelledescreateurs.com
MAIL_FROM_NAME="École Virtuelle des Créatifs"
```

### Option 4 : Mailgun

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=votre-domaine.mailgun.org
MAILGUN_SECRET=votre-api-key
MAIL_FROM_ADDRESS=noreply@ecolevirtuelledescreateurs.com
MAIL_FROM_NAME="École Virtuelle des Créatifs"
```

## 🚀 Étapes de configuration

### 1. Modifier le fichier `.env`

Ajoutez ou modifiez les variables d'environnement selon l'option choisie ci-dessus.

### 2. Vider le cache de configuration

Après avoir modifié le `.env`, exécutez :

```bash
php artisan config:clear
php artisan cache:clear
```

### 3. Tester l'envoi d'email

Pour tester rapidement l'envoi d'email, vous pouvez utiliser Tinker :

```bash
php artisan tinker
```

Puis dans Tinker :

```php
$student = (object)[
    'first_name' => 'Test',
    'last_name' => 'Étudiant',
    'email' => 'test@example.com'
];

Mail::to('votre-email@example.com')->send(
    new \App\Mail\TpAssignmentNotification(
        $student,
        'TP Test',
        '<p>Ceci est un test de notification</p>',
        now()->addDays(7),
        'Design Graphique',
        2
    )
);
```

### 4. Vérifier les logs

Les logs d'envoi d'email sont enregistrés dans `storage/logs/laravel.log` :

```bash
tail -f storage/logs/laravel.log | grep "Email"
```

Vous verrez :
- `✅ Email envoyé avec succès à: email@example.com` en cas de succès
- `⚠️ Erreur envoi email TP: ...` en cas d'erreur

## 🔍 Débogage

### L'email n'est pas envoyé ?

1. **Vérifiez que l'étudiant a un `user_id` et un email valide**
   ```sql
   SELECT s.id, s.first_name, s.last_name, s.user_id, u.email 
   FROM students s 
   LEFT JOIN users u ON s.user_id = u.id 
   WHERE s.status = 'active';
   ```

2. **Vérifiez les logs Laravel**
   ```bash
   tail -100 storage/logs/laravel.log
   ```

3. **Testez la connexion SMTP**
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($message) {
       $message->to('votre-email@example.com')->subject('Test');
   });
   ```

4. **Vérifiez que la file d'attente n'est pas bloquée**
   ```bash
   php artisan queue:work --once
   ```

### L'email arrive en spam ?

- Utilisez un service professionnel (SendGrid, Mailgun) en production
- Configurez les enregistrements SPF et DKIM pour votre domaine
- Utilisez une adresse `MAIL_FROM_ADDRESS` du même domaine que votre serveur

## 📊 Statistiques d'envoi

Les logs enregistrent automatiquement :
- Le nombre d'étudiants ayant reçu le TP
- Le nombre d'emails envoyés avec succès
- Les erreurs d'envoi éventuelles

Exemple de log :
```
[2025-01-21 18:00:00] INFO: Étudiants spécifiques sélectionnés: 5
[2025-01-21 18:00:01] INFO: ✅ Email envoyé avec succès à: etudiant1@example.com
[2025-01-21 18:00:02] INFO: ✅ Email envoyé avec succès à: etudiant2@example.com
[2025-01-21 18:00:05] INFO: TP créés: 5, Erreurs: 0
```

## 🎯 Comportement de l'envoi

- **Étudiants spécifiques** : Email envoyé uniquement aux étudiants sélectionnés
- **Tous les étudiants d'une formation** : Email envoyé à tous les étudiants actifs de la formation
- **Tous les étudiants** : Email envoyé à tous les étudiants actifs de la plateforme
- **Gestion des erreurs** : Si l'envoi d'email échoue, le TP est quand même créé (l'email ne bloque pas le processus)

## 📝 Personnalisation du template

Pour modifier le design de l'email, éditez le fichier :
`resources/views/emails/tp-assignment-notification.blade.php`

Vous pouvez personnaliser :
- Les couleurs (actuellement palette Instagram)
- Le texte et les messages
- Les liens sociaux dans le footer
- L'animation de l'icône
- La mise en page

## 🔐 Sécurité

- Les mots de passe SMTP ne doivent **JAMAIS** être commités dans Git
- Utilisez toujours le fichier `.env` pour les credentials
- Le fichier `.env` est déjà dans `.gitignore`
- En production, utilisez des variables d'environnement sécurisées

## ✅ Checklist de mise en production

- [ ] Configurer un service d'email professionnel (SendGrid, Mailgun, etc.)
- [ ] Ajouter les enregistrements SPF/DKIM au DNS
- [ ] Tester l'envoi avec des emails réels
- [ ] Vérifier que les emails n'arrivent pas en spam
- [ ] Configurer un domaine personnalisé pour `MAIL_FROM_ADDRESS`
- [ ] Mettre en place un monitoring des emails (taux de délivrabilité)
- [ ] Configurer les limites d'envoi selon votre plan

## 📞 Support

Pour toute question ou problème :
1. Consultez les logs : `storage/logs/laravel.log`
2. Vérifiez la configuration `.env`
3. Testez avec Mailtrap en développement
4. Contactez l'équipe technique si le problème persiste

---

**Dernière mise à jour :** 21 janvier 2025  
**Version :** 1.0.0
