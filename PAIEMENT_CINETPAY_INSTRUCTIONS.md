# 📋 Instructions Complètes - Système de Paiement CinetPay

## ✅ Ce qui a été créé automatiquement

### 1. Fichiers de configuration
- ✅ `config/cinetpay.php` - Configuration CinetPay avec les tarifs
- ✅ `.env` variables (à ajouter manuellement)

### 2. Base de données
- ✅ `database/migrations/2025_12_09_000001_create_payments_table.php`
- ✅ `database/migrations/2025_12_09_000002_add_payment_fields_to_pre_registrations.php`

### 3. Backend
- ✅ `app/Services/CinetPayService.php` - Service d'intégration CinetPay
- ✅ `app/Http/Controllers/PaymentController.php` - Gestion des paiements
- ✅ `app/Http/Controllers/Admin/PreRegistrationAdminController.php` - Méthode acceptCandidate ajoutée

### 4. Vues de paiement
- ✅ `resources/views/payment/checkout.blade.php` - Page de paiement
- ✅ `resources/views/payment/success.blade.php` - Paiement réussi
- ✅ `resources/views/payment/pending.blade.php` - En attente
- ✅ `resources/views/payment/expired.blade.php` - Lien expiré
- ✅ `resources/views/payment/cancelled.blade.php` - Paiement annulé

### 5. Templates email
- ✅ `resources/views/emails/payment_confirmed.blade.php` - Email après paiement
- ✅ `resources/views/emails/candidature_acceptee.blade.php` - Email d'acceptation

### 6. Routes
- ✅ Routes de paiement ajoutées dans `routes/web.php`
- ✅ Route admin pour accepter candidature

---

## 🚀 Étapes d'Installation

### Étape 1 : Exécuter les migrations

```bash
cd /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC
php artisan migrate
```

### Étape 2 : Ajouter les variables dans `.env`

Ouvrez le fichier `.env` et ajoutez à la fin :

```env
# ============================================
# CinetPay Configuration
# ============================================
CINETPAY_API_KEY=10668199396890d4fd224ef9.31505780
CINETPAY_DESIGN_SITE_ID=105904453
CINETPAY_DESIGN_SECRET=466948352689ad54be91ca012056423
CINETPAY_MODE=PRODUCTION

# URLs de retour CinetPay
CINETPAY_RETURN_URL="${APP_URL}/evc/payment/return"
CINETPAY_NOTIFY_URL="${APP_URL}/evc/payment/webhook"
CINETPAY_CANCEL_URL="${APP_URL}/evc/payment/cancel"
```

### Étape 3 : Configurer le Webhook sur CinetPay

1. Connectez-vous sur votre compte CinetPay
2. Allez dans **Paramètres → Webhooks**
3. Ajoutez l'URL de notification : `https://votre-domaine.com/evc/payment/webhook`
4. Activez le webhook

---

## 🔄 Workflow Complet

### 1. Admin accepte une candidature

Dans l'interface admin (`/evc/app/admin/preinscriptions`), l'admin voit les candidatures en attente et clique sur **"Accepter"**.

**Code dans la vue admin :**
```blade
<form action="{{ route('admin.preinscriptions.accept', $candidature->id) }}" method="POST">
    @csrf
    <button type="submit" class="btn btn-success">
        <i class="fas fa-check"></i> Accepter
    </button>
</form>
```

### 2. Système génère le lien de paiement

- La méthode `PreRegistrationAdminController::acceptCandidate()` est appelée
- Un paiement est créé dans la table `payments`
- Un email est envoyé au candidat avec le lien de paiement

### 3. Candidat reçoit l'email et clique sur le lien

**Email reçu :** `emails.candidature_acceptee`
**Lien :** `https://votre-domaine.com/evc/payment/EVC-PAY-20251209-ABCD1234`

### 4. Page de paiement affichée

Le candidat arrive sur `payment/checkout.blade.php` et voit :
- Récapitulatif de sa formation
- Montant à payer
- Moyens de paiement acceptés (Orange Money, MTN, Wave, Carte)

### 5. Redirection vers CinetPay

Le candidat clique sur **"Procéder au paiement"** → redirection vers CinetPay pour effectuer le paiement.

### 6. Paiement effectué

Après paiement, CinetPay envoie une notification au webhook : `/evc/payment/webhook`

### 7. Webhook traite le paiement

Le `PaymentController::webhook()` :
1. Vérifie le statut du paiement
2. Met à jour la table `payments` (status = completed)
3. Crée l'entrée `users` si elle n'existe pas
4. Génère le token de confirmation d'inscription
5. Envoie l'email avec le lien de création de compte

### 8. Candidat reçoit email de confirmation

**Email reçu :** `emails.payment_confirmed`
**Lien :** `https://votre-domaine.com/student/confirm-registration/{token}`

### 9. Création du compte

Le candidat clique sur le lien et arrive sur la page existante :
- `StudentConfirmationController::showConfirmationForm()`
- Vue : `resources/views/student/confirm-registration.blade.php`

Il peut alors :
- ✅ Choisir son mot de passe
- ✅ Ajouter sa photo de profil
- ✅ Accepter les conditions

### 10. Compte activé

Après validation du formulaire :
- Mot de passe haché et enregistré
- Compte activé (`email_verified_at` = now())
- Entrée créée dans la table `students`
- Statut pré-inscription = "Actif"

### 11. Connexion

Le candidat peut maintenant se connecter à son espace étudiant avec ses identifiants.

---

## 💰 Tarifs des Formations

| Formation | Montant (XOF) |
|-----------|---------------|
| Design Graphique | 77 000 |
| Community Management | 102 000 |
| Design Graphique & Community Management | 160 000 |
| Gestion Informatique | 152 000 |
| Intelligence Artificielle | 57 000 |

Ces tarifs sont configurés dans `config/cinetpay.php` et peuvent être modifiés facilement.

---

## 📝 Utilisation dans l'interface Admin

### Bouton "Accepter" dans la liste des pré-inscriptions

```blade
<!-- Dans resources/views/admin/preregistrations/index.blade.php -->
<td>
    @if($pre->status === 'pending' || $pre->status === 'En attente')
        <form action="{{ route('admin.preinscriptions.accept', $pre->id) }}" 
              method="POST" 
              style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success btn-sm" 
                    onclick="return confirm('Accepter cette candidature et envoyer le lien de paiement ?')">
                <i class="fas fa-check-circle"></i> Accepter
            </button>
        </form>
    @elseif($pre->status === 'accepted')
        <span class="badge bg-info">Lien de paiement envoyé</span>
    @elseif($pre->status === 'paid')
        <span class="badge bg-success">Paiement effectué</span>
    @elseif($pre->status === 'activated')
        <span class="badge bg-primary">Compte activé</span>
    @endif
</td>
```

---

## 🔍 Vérification et Tests

### Test 1 : Vérifier les migrations

```bash
php artisan migrate:status
```

Vous devriez voir :
- ✅ `2025_12_09_000001_create_payments_table`
- ✅ `2025_12_09_000002_add_payment_fields_to_pre_registrations`

### Test 2 : Tester l'acceptation d'une candidature

1. Allez sur `/evc/app/admin/preinscriptions`
2. Cliquez sur **"Accepter"** pour une candidature
3. Vérifiez que l'email est envoyé
4. Vérifiez dans la table `payments` qu'un enregistrement a été créé

```sql
SELECT * FROM payments ORDER BY id DESC LIMIT 1;
```

### Test 3 : Tester le paiement

1. Ouvrez l'email reçu
2. Cliquez sur le lien de paiement
3. Vérifiez que la page de checkout s'affiche correctement
4. Effectuez un paiement test sur CinetPay

### Test 4 : Vérifier le webhook

Après un paiement, vérifiez les logs :

```bash
tail -f storage/logs/laravel.log | grep "CinetPay"
```

Vous devriez voir :
```
[...] CinetPay Webhook reçu
[...] Webhook CinetPay : Paiement traité avec succès
```

### Test 5 : Vérifier la création de compte

1. Ouvrez l'email "Paiement confirmé"
2. Cliquez sur le lien de création de compte
3. Remplissez le formulaire (mot de passe, photo)
4. Vérifiez que le compte est créé dans les tables `users` et `students`

```sql
SELECT * FROM users WHERE email = 'test@example.com';
SELECT * FROM students WHERE email = 'test@example.com';
```

---

## 🛠️ Dépannage

### Problème : Email non reçu

**Vérification :**
```bash
# Vérifier la configuration mail
php artisan config:cache
cat .env | grep MAIL_
```

**Solution :**
- Vérifiez les paramètres SMTP dans `.env`
- Vérifiez les logs : `storage/logs/laravel.log`

### Problème : Webhook ne fonctionne pas

**Vérification :**
```bash
# Regarder les logs
tail -f storage/logs/laravel.log | grep "Webhook"
```

**Solutions :**
- Vérifiez que l'URL du webhook est correctement configurée sur CinetPay
- Vérifiez que l'URL est accessible publiquement (pas localhost)
- Utilisez ngrok en développement pour tester : `ngrok http 8000`

### Problème : Montant incorrect

**Solution :**
Modifiez les tarifs dans `config/cinetpay.php` :
```php
'prices' => [
    'Design Graphique' => 77000,
    'Community Management' => 102000,
    // ...
],
```

Puis :
```bash
php artisan config:cache
```

### Problème : Token de confirmation invalide

**Cause :** Le token a expiré (validité 24h)

**Solution :**
L'admin peut renvoyer le lien via la méthode existante :
```
POST /evc/app/admin/preinscriptions/{id}/resend-link
```

---

## 📊 Tables de la Base de Données

### Table `payments`

| Colonne | Type | Description |
|---------|------|-------------|
| id | bigint | ID unique |
| pre_registration_id | bigint | Lien vers la pré-inscription |
| amount | decimal | Montant en XOF |
| payment_reference | string | Référence unique (EVC-PAY-...) |
| status | enum | pending, completed, failed, cancelled |
| transaction_id | string | ID de transaction CinetPay |
| account_creation_token | string | Token pour créer le compte |
| paid_at | timestamp | Date du paiement |
| expires_at | timestamp | Expiration du lien (7 jours) |

### Table `pre_registrations` (colonnes ajoutées)

| Colonne | Type | Description |
|---------|------|-------------|
| status | enum | pending, accepted, rejected, paid, activated |
| reviewed_by | bigint | ID de l'admin qui a validé |
| reviewed_at | timestamp | Date de validation |
| rejection_reason | text | Raison du rejet (si applicable) |

---

## 🔒 Sécurité

### ✅ Mesures de sécurité implémentées

1. **Tokens uniques** : Chaque lien de paiement et de confirmation est unique
2. **Expiration** : Les liens de paiement expirent après 7 jours
3. **Validation webhook** : Vérification de la source CinetPay
4. **HTTPS requis** : En production, tous les échanges sont chiffrés
5. **CSRF Protection** : Tous les formulaires sont protégés par token CSRF
6. **Logs détaillés** : Tous les paiements sont tracés dans les logs

---

## 📈 Statistiques et Rapports

### Requêtes utiles

**Nombre total de paiements :**
```sql
SELECT COUNT(*) FROM payments WHERE status = 'completed';
```

**Revenus par formation :**
```sql
SELECT p.formation, SUM(pay.amount) as total
FROM payments pay
JOIN pre_registrations p ON pay.pre_registration_id = p.id
WHERE pay.status = 'completed'
GROUP BY p.formation;
```

**Taux de conversion :**
```sql
SELECT 
    COUNT(CASE WHEN status = 'accepted' THEN 1 END) as acceptes,
    COUNT(CASE WHEN status = 'paid' THEN 1 END) as payes,
    ROUND(COUNT(CASE WHEN status = 'paid' THEN 1 END) * 100.0 / 
          COUNT(CASE WHEN status = 'accepted' THEN 1 END), 2) as taux_conversion
FROM pre_registrations;
```

---

## 🎯 Prochaines Améliorations

### Court terme
- [ ] Page admin pour voir tous les paiements
- [ ] Filtre par statut de paiement
- [ ] Export CSV des paiements

### Moyen terme
- [ ] Paiements en plusieurs fois
- [ ] Remises et codes promo
- [ ] Factures PDF automatiques

### Long terme
- [ ] Tableau de bord financier complet
- [ ] Intégration comptable
- [ ] Relances automatiques pour paiements non effectués

---

## 📞 Support

En cas de problème :

1. **Consultez les logs :** `storage/logs/laravel.log`
2. **Vérifiez la configuration :** `.env` et `config/cinetpay.php`
3. **Testez le webhook :** Utilisez ngrok en développement
4. **Contactez CinetPay :** support@cinetpay.com

---

## ✅ Checklist de Déploiement

Avant de mettre en production :

- [ ] Variables `.env` configurées
- [ ] Migrations exécutées
- [ ] Webhook CinetPay configuré
- [ ] Tests de paiement effectués
- [ ] Configuration mail testée
- [ ] Logs vérifiés
- [ ] HTTPS activé
- [ ] Sauvegardes activées

---

🎉 **Félicitations ! Votre système de paiement CinetPay est maintenant opérationnel !**

Pour toute question, consultez ce document ou les commentaires dans le code.
