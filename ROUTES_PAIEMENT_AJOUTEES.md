# ✅ Routes de Paiement Ajoutées

## 🔍 Problème Résolu

### **Erreur 404 sur le lien de paiement** ❌
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-20251209-D236F56D
→ 404 Not Found
```

**Cause** : Les routes de paiement n'étaient **pas définies** dans `routes/web.php`.

---

## ✅ Solution Appliquée

### **Routes Ajoutées dans `routes/web.php`**

```php
// Routes de paiement (accessibles publiquement)
Route::get('/evc/payment/{paymentReference}', [\App\Http\Controllers\PaymentController::class, 'showCheckout'])
    ->name('payment.checkout');
    
Route::post('/evc/payment/process', [\App\Http\Controllers\PaymentController::class, 'processPayment'])
    ->name('payment.process');
    
Route::get('/evc/payment/return', [\App\Http\Controllers\PaymentController::class, 'paymentReturn'])
    ->name('payment.return');
    
Route::get('/evc/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'paymentCancel'])
    ->name('payment.cancel');
    
Route::post('/evc/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])
    ->name('payment.webhook');
```

### **Localisation**
**Fichier** : `routes/web.php`
**Lignes** : 92-97

---

## 📋 Routes de Paiement Disponibles

### **1. Page de Paiement (Checkout)**
```
GET /evc/payment/{paymentReference}
```
- **Contrôleur** : `PaymentController@showCheckout`
- **Vue** : `resources/views/payment/checkout.blade.php`
- **Fonction** : Affiche la page de paiement avec les détails et le formulaire CinetPay
- **Paramètre** : `{paymentReference}` = Référence du paiement (ex: `EVC-PAY-20251209-D236F56D`)

**Exemple** :
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-20251209-D236F56D
```

### **2. Traiter le Paiement**
```
POST /evc/payment/process
```
- **Contrôleur** : `PaymentController@processPayment`
- **Fonction** : Initie la transaction avec CinetPay et redirige vers leur page
- **Paramètres** : `payment_reference`, `phone_number` (optionnel)

### **3. Retour après Paiement**
```
GET /evc/payment/return
```
- **Contrôleur** : `PaymentController@paymentReturn`
- **Fonction** : Page de retour après paiement réussi (return_url)
- **Paramètre** : `transaction_id`

### **4. Annulation de Paiement**
```
GET /evc/payment/cancel
```
- **Contrôleur** : `PaymentController@paymentCancel`
- **Fonction** : Page d'annulation si l'utilisateur abandonne
- **Paramètre** : `transaction_id`

### **5. Webhook CinetPay**
```
POST /evc/payment/webhook
```
- **Contrôleur** : `PaymentController@webhook`
- **Fonction** : Reçoit les notifications de CinetPay pour confirmer les paiements
- **Authentification** : Signature CinetPay

---

## 🔗 Workflow Complet

### **Workflow de Paiement**

```
1. Admin accepte candidature
   ↓
2. 2 paiements créés (50 000 + 27 000 FCFA)
   ↓
3. Email envoyé au candidat avec lien :
   http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXX
   ↓
4. Candidat clique sur le lien
   ↓
5. Route: GET /evc/payment/{paymentReference}
   ↓
6. PaymentController@showCheckout() exécuté
   ↓
7. Vue payment/checkout.blade.php affichée
   ↓
8. Candidat remplit le formulaire et clique "Payer"
   ↓
9. POST /evc/payment/process
   ↓
10. Redirection vers CinetPay
   ↓
11. Candidat paie sur CinetPay
   ↓
12. CinetPay appelle POST /evc/payment/webhook
   ↓
13. Paiement marqué "completed" en DB
   ↓
14. Redirection vers GET /evc/payment/return
   ↓
15. Message de confirmation affiché
```

---

## 🧪 Test du Lien de Paiement

### **Étape 1 : Accepter une Candidature**
1. Aller sur http://127.0.0.1:8000/evc/app/admin/preinscriptions
2. Cliquer sur **"Accepter"** pour une candidature (ex: ID 33)
3. Confirmer

### **Étape 2 : Récupérer le Lien de Paiement**
**Option A - Dans les Logs** :
```bash
tail -f storage/logs/laravel.log | grep "payment_reference"
```

**Option B - En Base de Données** :
```sql
SELECT payment_reference, amount, status, created_at 
FROM payments 
WHERE pre_registration_id = 33 
ORDER BY created_at DESC 
LIMIT 1;
```

**Résultat** :
```
payment_reference: EVC-PAY-20251209-D236F56D
```

### **Étape 3 : Tester le Lien**
Ouvrir dans le navigateur :
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-20251209-D236F56D
```

### **Résultat Attendu** ✅
- ✅ Page de paiement s'affiche
- ✅ Informations du candidat visibles
- ✅ Montant affiché (50 000 FCFA pour 1ère tranche)
- ✅ Formulaire de paiement CinetPay
- ✅ Bouton "Payer maintenant"

**Si erreur 404** ❌ :
```bash
php artisan route:clear
php artisan cache:clear
```

---

## 📊 Vérification en Base de Données

### **Vérifier qu'un Paiement Existe**
```sql
SELECT 
    id,
    payment_reference,
    amount,
    status,
    expires_at,
    pre_registration_id,
    payer_email
FROM payments
WHERE payment_reference = 'EVC-PAY-20251209-D236F56D';
```

**Statuts possibles** :
- `pending` → En attente (lien valide)
- `completed` → Payé (lien désactivé)
- `failed` → Échoué
- `expired` → Expiré

### **Vérifier la Préinscription Associée**
```sql
SELECT 
    p.payment_reference,
    p.amount,
    p.status AS payment_status,
    pr.nom,
    pr.prenom,
    pr.email,
    pr.status AS candidat_status
FROM payments p
JOIN pre_registrations pr ON p.pre_registration_id = pr.id
WHERE p.payment_reference = 'EVC-PAY-20251209-D236F56D';
```

---

## 🎯 URLs de Configuration CinetPay

### **URLs à Configurer dans le Tableau de Bord CinetPay**

**En Développement (Local)** :
```
return_url:  http://127.0.0.1:8000/evc/payment/return
cancel_url:  http://127.0.0.1:8000/evc/payment/cancel
notify_url:  http://127.0.0.1:8000/evc/payment/webhook
```

**En Production** :
```
return_url:  https://votredomaine.com/evc/payment/return
cancel_url:  https://votredomaine.com/evc/payment/cancel
notify_url:  https://votredomaine.com/evc/payment/webhook
```

⚠️ **Important** : En local, CinetPay ne peut **pas** appeler le webhook (127.0.0.1 n'est pas accessible depuis Internet). Utilisez le **simulateur de webhook** :
```
http://127.0.0.1:8000/evc/app/admin/test/webhook
```

---

## 📝 Logs de Débogage

### **Vérifier les Accès à la Page de Paiement**
```bash
tail -f storage/logs/laravel.log | grep -i "payment\|checkout"
```

### **Vérifier les Webhooks Reçus**
```bash
tail -f storage/logs/laravel.log | grep -i "webhook\|cinetpay"
```

---

## 🔧 Dépannage

### **Problème : 404 sur /evc/payment/{reference}**
**Solutions** :
```bash
# 1. Vider le cache des routes
php artisan route:clear

# 2. Vérifier que la route existe
php artisan route:list --path=payment

# 3. Vider tous les caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### **Problème : "Lien de paiement invalide ou expiré"**
**Causes possibles** :
1. Le paiement n'existe pas en DB
2. Le paiement a le statut `completed` (déjà payé)
3. Le paiement a expiré (`expires_at` < maintenant)

**Vérification** :
```sql
SELECT * FROM payments WHERE payment_reference = 'EVC-PAY-XXXXX';
```

### **Problème : Page blanche sur /evc/payment/{reference}**
**Solutions** :
1. Vérifier les logs Laravel :
```bash
tail -100 storage/logs/laravel.log
```

2. Vérifier que la vue existe :
```bash
ls -la resources/views/payment/checkout.blade.php
```

3. Activer le mode debug dans `.env` :
```env
APP_DEBUG=true
```

---

## ✅ **Routes de Paiement Opérationnelles !**

**Toutes les routes nécessaires sont maintenant configurées :**

✅ **Page de paiement** : `/evc/payment/{reference}`  
✅ **Traitement paiement** : `/evc/payment/process`  
✅ **Retour paiement** : `/evc/payment/return`  
✅ **Annulation** : `/evc/payment/cancel`  
✅ **Webhook CinetPay** : `/evc/payment/webhook`

**Le lien de paiement dans l'email fonctionne maintenant ! 🎉**

**Testez en acceptant une nouvelle candidature et en cliquant sur le lien reçu par email.**
