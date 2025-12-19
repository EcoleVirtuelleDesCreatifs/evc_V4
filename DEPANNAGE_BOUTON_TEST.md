# 🔧 Dépannage : "Lien de paiement invalide ou expiré"

## 🔍 Problème

Quand vous cliquez sur **"⚡ Simuler Paiement Réussi (TEST)"**, vous êtes redirigé vers la page de connexion avec le message d'erreur :
```
❌ Lien de paiement invalide ou expiré
```

---

## 🎯 Solution : Activer les Logs et Tester

J'ai ajouté des logs détaillés pour identifier le problème. Suivez ces étapes :

### **Étape 1 : Vider les Caches**
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC
php artisan cache:clear
php artisan route:clear
php artisan config:clear
```

### **Étape 2 : Ouvrir les Logs en Direct**
**Terminal 1 - Suivre les logs :**
```bash
tail -f storage/logs/laravel.log
```

Laissez ce terminal ouvert pour voir les logs en temps réel.

### **Étape 3 : Faire le Test**

**Terminal 2 - Ou navigateur :**

1. Aller sur la page de paiement :
   ```
   http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX
   ```

2. Cliquer sur **"⚡ Simuler Paiement Réussi (TEST)"**

3. Observer les logs dans le Terminal 1

### **Étape 4 : Analyser les Logs**

Vous devriez voir quelque chose comme :

**Cas 1 : Succès (bon scénario)**
```
[2025-12-09] local.INFO: 🧪 TEST - Paiement simulé comme réussi
[2025-12-09] local.INFO: 🔍 paymentReturn appelé {"transaction_id":"TEST-..."}
[2025-12-09] local.INFO: 🔍 Recherche paiement {"transaction_id":"TEST-...","payment_found":"OUI"}
[2025-12-09] local.INFO: 🧪 TEST - Envoi email création compte
[2025-12-09] local.INFO: ✅ Utilisateur et profil étudiant créés
```

**Cas 2 : Transaction ID manquant**
```
[2025-12-09] local.INFO: 🧪 TEST - Paiement simulé comme réussi
[2025-12-09] local.INFO: 🔍 paymentReturn appelé {"transaction_id":null}
[2025-12-09] local.ERROR: ❌ Transaction ID manquant
```
→ **Problème** : Le `transaction_id` n'est pas passé dans l'URL

**Cas 3 : Paiement non trouvé**
```
[2025-12-09] local.INFO: 🧪 TEST - Paiement simulé comme réussi
[2025-12-09] local.INFO: 🔍 paymentReturn appelé {"transaction_id":"TEST-..."}
[2025-12-09] local.INFO: 🔍 Recherche paiement {"transaction_id":"TEST-...","payment_found":"NON"}
[2025-12-09] local.ERROR: ❌ Paiement non trouvé
```
→ **Problème** : Le paiement n'a pas été correctement mis à jour en base

---

## 🔧 Solutions Selon le Cas

### **Solution 1 : Transaction ID Manquant**

**Vérifier la route :**
```bash
php artisan route:list | grep payment.return
```

Devrait afficher :
```
GET|HEAD  evc/payment/return  payment.return  PaymentController@paymentReturn
```

**Corriger si nécessaire :**
Si la route n'existe pas ou est mal définie, ajouter dans `routes/web.php` :
```php
Route::get('/evc/payment/return', [PaymentController::class, 'paymentReturn'])->name('payment.return');
```

Puis :
```bash
php artisan route:clear
```

### **Solution 2 : Paiement Non Trouvé**

**Vérifier en base de données :**
```sql
-- Vérifier le dernier paiement test
SELECT 
    id,
    payment_reference,
    transaction_id,
    status,
    paid_at,
    account_creation_token
FROM payments
WHERE transaction_id LIKE 'TEST-%'
ORDER BY id DESC
LIMIT 1;
```

**Si `transaction_id` est NULL :**
Le paiement n'a pas été mis à jour. C'est un problème dans `testPaymentSuccess()`.

**Solution :**
Vérifier que la mise à jour du paiement se fait correctement. Ajouter un log après la mise à jour :

```php
// Dans testPaymentSuccess(), après l'update
Log::info('✅ Paiement mis à jour', [
    'payment_id' => $payment->id,
    'transaction_id' => $transactionId
]);
```

### **Solution 3 : Problème de Cache**

**Vider tous les caches :**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

**Redémarrer le serveur :**
Si vous utilisez `php artisan serve` :
1. Arrêter avec Ctrl+C
2. Relancer : `php artisan serve`

---

## 🧪 Test Alternatif : Utiliser l'URL Directe

Si le problème persiste, tester en utilisant l'URL directe au lieu de la redirection :

### **Étape 1 : Récupérer le Transaction ID**
```bash
# Après avoir cliqué sur "Simuler Paiement Réussi"
tail -10 storage/logs/laravel.log | grep "transaction_id"
```

Vous verrez quelque chose comme :
```
"transaction_id":"TEST-674F7A2B3C1234"
```

### **Étape 2 : Ouvrir l'URL Directement**
```
http://127.0.0.1:8000/evc/payment/return?transaction_id=TEST-674F7A2B3C1234
```

Remplacez `TEST-674F7A2B3C1234` par le vrai transaction ID des logs.

---

## 📊 Vérification Complète du Paiement

### **Script SQL de Diagnostic**
```sql
-- Vérifier le paiement
SELECT 
    p.id,
    p.payment_reference,
    p.transaction_id,
    p.status,
    p.paid_at,
    p.installment_number,
    p.account_creation_token,
    pr.nom,
    pr.prenom,
    pr.email,
    pr.status as pre_registration_status
FROM payments p
LEFT JOIN pre_registrations pr ON p.pre_registration_id = pr.id
WHERE p.payment_reference = 'EVC-PAY-20251209-XXXXXX';  -- Remplacer par votre référence
```

**Résultat attendu :**
```
transaction_id: TEST-674F7A2B3C1234
status: completed
paid_at: 2025-12-09 16:30:45
account_creation_token: {base64 string}
installment_number: 1
```

---

## 🔄 Refaire le Test Complètement

Si rien ne fonctionne, refaire depuis le début :

### **1. Nettoyer les Anciennes Données**
```sql
-- Supprimer les paiements de test
DELETE FROM payments WHERE transaction_id LIKE 'TEST-%';

-- Supprimer les utilisateurs de test
DELETE FROM users WHERE email = 'koffi@email.com';  -- Remplacer par l'email test

-- Supprimer les étudiants de test
DELETE FROM students WHERE email = 'koffi@email.com';

-- Remettre la préinscription en attente
UPDATE pre_registrations 
SET status = 'accepted' 
WHERE email = 'koffi@email.com';
```

### **2. Accepter Une Nouvelle Candidature**
```
http://127.0.0.1:8000/evc/app/admin/preinscriptions
```
Cliquer sur "Accepter" pour une candidature

### **3. Récupérer le Nouveau Lien**
```sql
SELECT payment_reference 
FROM payments 
ORDER BY id DESC 
LIMIT 1;
```

### **4. Ouvrir et Tester**
```
http://127.0.0.1:8000/evc/payment/{payment_reference}
```

Cliquer sur "⚡ Simuler Paiement Réussi (TEST)"

---

## 📝 Copier-Coller ces Commandes

**Commandes à exécuter dans l'ordre :**

```bash
# Terminal 1 - Logs
cd /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC
tail -f storage/logs/laravel.log

# Terminal 2 - Caches
cd /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear

# Faire le test dans le navigateur
# Observer les logs dans Terminal 1
```

---

## ✅ Checklist de Dépannage

- [ ] Caches vidés (cache, route, config, view)
- [ ] Logs activés (`tail -f storage/logs/laravel.log`)
- [ ] Test effectué en observant les logs
- [ ] Vérification du paiement en DB (`SELECT * FROM payments WHERE transaction_id LIKE 'TEST-%'`)
- [ ] Vérification de la route `payment.return` (`php artisan route:list | grep payment.return`)
- [ ] Test avec URL directe si nécessaire

---

## 📧 Partagez les Logs

Si le problème persiste, partagez les logs :

```bash
# Copier les 50 dernières lignes
tail -50 storage/logs/laravel.log | grep -A 5 -B 5 "TEST"
```

Collez le résultat pour analyser le problème.

---

## 🎯 Résumé

Le message **"Lien de paiement invalide ou expiré"** vient de `paymentReturn()` qui ne trouve pas le paiement avec le `transaction_id`.

**Causes possibles :**
1. ❌ Transaction ID pas passé dans l'URL
2. ❌ Paiement pas mis à jour avec le transaction_id
3. ❌ Cache de routes ou config
4. ❌ Problème de redirection

**Avec les logs activés, on pourra identifier exactement la cause ! 🔍**
