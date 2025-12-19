# 🧪 Bouton Test de Paiement

## ✅ Fonctionnalité Ajoutée

Un **bouton de test** a été ajouté sur la page de paiement pour simuler un paiement réussi en environnement de développement.

---

## 📍 Où le Trouver

**Page de paiement :**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX
```

**Apparence :**
- Zone jaune/orange avec fond dégradé
- Titre : "🧪 Mode Développement"
- Bouton orange : "⚡ Simuler Paiement Réussi (TEST)"

**Visibilité :**
- ✅ **Visible UNIQUEMENT en environnement local** (`.env` avec `APP_ENV=local`)
- ❌ **Caché en production** pour éviter les abus

---

## 🎯 Utilité

### **Sans ce bouton (avant) :**
```
Accepter candidature
   ↓
Email envoyé avec lien paiement
   ↓
Ouvrir le lien
   ↓
❌ BLOQUÉ : Impossible de payer sans vraie carte ou compte CinetPay
   ↓
Impossible de tester la suite du workflow
```

### **Avec ce bouton (maintenant) :**
```
Accepter candidature
   ↓
Email envoyé avec lien paiement
   ↓
Ouvrir le lien
   ↓
✅ Cliquer sur "Simuler Paiement Réussi (TEST)"
   ↓
✅ Paiement marqué comme "completed"
   ↓
✅ Email de création de compte envoyé
   ↓
✅ Utilisateur créé automatiquement
   ↓
✅ Workflow complet testé !
```

---

## 🚀 Comment Utiliser

### **Étape 1 : Accepter une Candidature**
```
http://127.0.0.1:8000/evc/app/admin/preinscriptions
```
1. Cliquer sur **"Accepter"** pour une candidature
2. Confirmer

**Résultat :**
- 2 paiements créés (50 000 + 27 000 FCFA)
- Email envoyé au candidat avec lien de paiement

### **Étape 2 : Récupérer le Lien de Paiement**

**Option A - Email :**
Vérifier l'email du candidat (si configuré)

**Option B - Base de Données :**
```sql
SELECT payment_reference, amount, status 
FROM payments 
WHERE pre_registration_id = 33 
ORDER BY created_at DESC 
LIMIT 1;
```

**Option C - Logs Laravel :**
```bash
tail -f storage/logs/laravel.log | grep "payment_reference"
```

### **Étape 3 : Ouvrir le Lien de Paiement**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-20251209-XXXXXX
```

### **Étape 4 : Utiliser le Bouton Test**

Vous verrez une section jaune/orange :

```
┌─────────────────────────────────────────────────┐
│  🧪 Mode Développement                          │
│                                                 │
│  Ce bouton simule un paiement réussi sans      │
│  passer par CinetPay.                           │
│                                                 │
│  Utilisation : Test du workflow complet        │
│  (webhook, email, création de compte)          │
│                                                 │
│  [⚡ Simuler Paiement Réussi (TEST)]           │
└─────────────────────────────────────────────────┘
```

**Cliquer sur le bouton orange**

---

## ✅ Ce Qui Se Passe Automatiquement

### **1. Mise à Jour du Paiement**
```php
Status : pending → completed
Transaction ID : TEST-{uniqid}
Date de paiement : now()
```

### **2. Création de l'Utilisateur**
```php
Table : users
Données : nom, prénom, email, téléphone, formation
Status : "En attente"
```

### **3. Génération du Token**
```php
Format : email|timestamp|hash
Token encodé en base64
Stocké dans payments.account_creation_token
```

### **4. Envoi de l'Email**
```
À : candidat@example.com
Sujet : "🧪 TEST - Paiement confirmé - Créez votre compte EVC"
Contenu : Lien de création de compte avec token
```

### **5. Mise à Jour Préinscription**
```php
Status : "en cours" → "paid"
```

### **6. Redirection**
```
→ /evc/payment/return?transaction_id=TEST-XXXXX
→ Message : "🧪 TEST - Paiement simulé avec succès ! Vérifiez votre email."
```

---

## 🔍 Vérification Après Test

### **En Base de Données**

**Table `payments` :**
```sql
SELECT 
    id,
    payment_reference,
    status,
    transaction_id,
    paid_at,
    account_creation_token
FROM payments
WHERE payment_reference = 'EVC-PAY-XXXXXXXX';
```

**Résultat attendu :**
```
status: completed
transaction_id: TEST-674F7A2B3C...
paid_at: 2025-12-09 16:30:45
account_creation_token: base64encodedtoken...
```

**Table `users` :**
```sql
SELECT id, first_name, last_name, email, status
FROM users
WHERE email = 'candidat@example.com';
```

**Résultat attendu :**
```
first_name: Juliette
last_name: Koffi
email: koffi@email.com
status: En attente
```

**Table `pre_registrations` :**
```sql
SELECT id, nom, prenom, status
FROM pre_registrations
WHERE id = 33;
```

**Résultat attendu :**
```
status: paid
```

---

## 📧 Email Envoyé

**Template :** `resources/views/emails/payment_confirmed.blade.php`

**Contenu :**
```
Sujet : 🧪 TEST - Paiement confirmé - Créez votre compte EVC

Bonjour Juliette Koffi,

Félicitations ! Votre paiement de 50 000 FCFA a été confirmé.

Créez maintenant votre compte pour accéder à votre espace étudiant :

[Bouton : Créer mon compte]

Lien : http://127.0.0.1:8000/student/confirm-registration/{token}

Ce lien expire dans 7 jours.
```

---

## 🔗 Workflow Complet Testé

```
1. Admin accepte candidature
   ↓
2. Email avec lien de paiement envoyé
   ↓
3. Candidat ouvre le lien
   ↓
4. 🧪 Candidat clique "Simuler Paiement Réussi"
   ↓
5. ✅ Paiement marqué "completed"
   ↓
6. ✅ Utilisateur créé dans table users
   ↓
7. ✅ Token de confirmation généré
   ↓
8. ✅ Email envoyé avec lien de création de compte
   ↓
9. ✅ Préinscription → status "paid"
   ↓
10. Candidat clique sur le lien dans l'email
   ↓
11. Page de création de compte s'affiche
   ↓
12. Candidat crée son mot de passe
   ↓
13. ✅ Compte activé → Accès à l'espace étudiant
```

---

## 📊 Logs Générés

### **Logs Laravel (`storage/logs/laravel.log`) :**

```
[2025-12-09 16:30:45] local.INFO: 🧪 TEST - Paiement simulé comme réussi {
    "payment_id": 123,
    "payment_reference": "EVC-PAY-20251209-XXXXXX",
    "transaction_id": "TEST-674F7A2B3C",
    "amount": 50000
}

[2025-12-09 16:30:45] local.INFO: 🧪 TEST - Envoi email création compte (1ère tranche) {
    "candidate_email": "koffi@email.com"
}

[2025-12-09 16:30:46] local.INFO: ✅ Email création compte envoyé {
    "email": "koffi@email.com",
    "confirmation_url": "http://127.0.0.1:8000/student/confirm-registration/..."
}

[2025-12-09 16:30:46] local.INFO: ℹ️ 2ème tranche - Email sera envoyé manuellement par l'admin après 2 mois
```

---

## 🛡️ Sécurité

### **Protection Environnement**

```php
// Dans PaymentController::testPaymentSuccess()
if (!app()->environment('local')) {
    abort(403, 'Cette fonctionnalité n\'est disponible qu\'en environnement de développement');
}
```

**Résultat :**
- ✅ Fonctionne en **local** (`APP_ENV=local` dans `.env`)
- ❌ **Erreur 403** en production (`APP_ENV=production`)

### **Affichage Conditionnel**

```blade
@if(app()->environment('local'))
    <!-- Bouton test visible -->
@endif
```

**Résultat :**
- ✅ Bouton visible UNIQUEMENT si `APP_ENV=local`
- ❌ Complètement absent en production

---

## 🎓 Cas d'Usage

### **Cas 1 : Tester l'Email de Création de Compte**
```
1. Accepter candidature
2. Ouvrir lien paiement
3. Cliquer "Simuler Paiement Réussi"
4. Vérifier email reçu
5. Cliquer sur lien dans email
6. ✅ Vérifier page de création de compte
```

### **Cas 2 : Tester la Création Automatique d'Utilisateur**
```
1. Accepter candidature
2. Ouvrir lien paiement
3. Cliquer "Simuler Paiement Réussi"
4. SELECT * FROM users WHERE email = '...'
5. ✅ Vérifier que l'utilisateur existe
```

### **Cas 3 : Tester le Workflow 2ème Tranche**
```
1. Simuler paiement 1ère tranche
2. Attendre ou modifier manually la date en DB
3. Admin → Interface 2ème tranche
4. Envoyer email 2ème tranche
5. Ouvrir lien paiement 2ème tranche
6. Cliquer "Simuler Paiement Réussi"
7. ✅ Inscription complète
```

### **Cas 4 : Tester Sans Config Email**
```
Même si l'email n'est pas configuré dans .env :
- Le paiement sera marqué "completed"
- L'utilisateur sera créé
- Le token sera généré
- Seul l'email ne sera pas envoyé (erreur loggée)
- Workflow continue normalement
```

---

## 🔧 Dépannage

### **Problème : Bouton test non visible**

**Cause :** `APP_ENV` n'est pas `local`

**Solution :**
```env
# Dans .env
APP_ENV=local
APP_DEBUG=true
```

Puis :
```bash
php artisan config:clear
```

### **Problème : Erreur 403 "Cette fonctionnalité n'est disponible..."**

**Cause :** Environnement = production

**Solution :** Changer en local (voir ci-dessus)

### **Problème : "Paiement introuvable"**

**Cause :** Le paiement n'existe pas ou a déjà été payé

**Vérification :**
```sql
SELECT * FROM payments WHERE payment_reference = 'EVC-PAY-XXXXX';
```

**Solutions :**
1. Accepter une nouvelle candidature
2. Utiliser un paiement avec `status = 'pending'`

### **Problème : Email non reçu**

**Causes possibles :**
1. Email pas configuré dans `.env`
2. Serveur SMTP inaccessible
3. Email invalide dans pre_registrations

**Vérification :**
```bash
tail -100 storage/logs/laravel.log | grep -i "email\|mail"
```

**Note :** L'action continue même si l'email échoue

---

## 📝 Fichiers Modifiés

### **1. Page de Paiement**
**Fichier :** `resources/views/payment/checkout.blade.php`
**Lignes :** 453-472
**Ajout :** Section "Mode Développement" avec bouton test

### **2. Routes**
**Fichier :** `routes/web.php`
**Ligne :** 98
**Ajout :** `Route::post('/evc/payment/test/success', ...)`

### **3. Contrôleur**
**Fichier :** `app/Http/Controllers/PaymentController.php`
**Lignes :** 546-703
**Ajout :** Méthode `testPaymentSuccess()`

---

## ✅ Avantages

- ✅ **Test rapide** : Plus besoin de vraie carte bancaire
- ✅ **Workflow complet** : Teste TOUT le processus
- ✅ **Sécurisé** : Visible uniquement en dev
- ✅ **Logs détaillés** : Trace complète de chaque action
- ✅ **Robuste** : Continue même si email échoue
- ✅ **Réaliste** : Utilise le vrai code de production
- ✅ **Facile** : Un seul clic
- ✅ **Complet** : Crée utilisateur, token, email

---

## 🎉 Prêt à Tester !

**Commencez maintenant :**

```bash
# 1. Aller sur admin preregistrations
http://127.0.0.1:8000/evc/app/admin/preinscriptions

# 2. Accepter une candidature

# 3. Récupérer le lien de paiement en DB
SELECT payment_reference FROM payments ORDER BY id DESC LIMIT 1;

# 4. Ouvrir le lien
http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX

# 5. Cliquer sur le bouton orange "⚡ Simuler Paiement Réussi (TEST)"

# 6. Vérifier les résultats en DB et logs
```

**C'est prêt ! 🎉**
