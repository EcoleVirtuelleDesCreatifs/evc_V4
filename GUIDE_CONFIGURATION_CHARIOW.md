# 🛒 Guide de Configuration Chariow - URLs de Retour

## 📋 URLs à Configurer

Voici les **URLs exactes** à configurer dans votre boutique Chariow :

---

## ✅ URL de Retour (Paiement Réussi)

### **Pour l'Environnement Local :**
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}
```

### **Pour la Production (à adapter) :**
```
https://votre-domaine.com/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}
```

**Variables dynamiques :**
- `{reference}` → Sera remplacée par la référence EVC (ex: EVC-PAY-20251210-XXXXXXXX)
- `{transaction_id}` → Sera remplacée par l'ID de transaction Chariow

---

## ❌ URL d'Annulation (Paiement Annulé/Échoué)

### **Pour l'Environnement Local :**
```
http://127.0.0.1:8000/evc/payment/chariow/cancel?reference={reference}
```

### **Pour la Production (à adapter) :**
```
https://votre-domaine.com/evc/payment/chariow/cancel?reference={reference}
```

**Variable dynamique :**
- `{reference}` → Sera remplacée par la référence EVC

---

## 🔔 URL Webhook (Optionnel mais Recommandé)

### **Pour l'Environnement Local (ngrok requis) :**
```
https://votre-url-ngrok.ngrok.io/evc/payment/chariow/webhook
```

### **Pour la Production :**
```
https://votre-domaine.com/evc/payment/chariow/webhook
```

**Méthode HTTP :** POST  
**Format de données :** JSON

---

## 🎯 Étapes de Configuration dans Chariow

### **Étape 1 : Connexion à Chariow**

1. Allez sur : https://mychariow.shop/
2. Connectez-vous à votre compte
3. Accédez au **Tableau de bord**

---

### **Étape 2 : Accéder aux Paramètres du Produit**

1. Dans le menu, cliquez sur **"Produits"** ou **"Mes Produits"**
2. Trouvez votre produit : **`prd_ngqtqy`**
3. Cliquez sur **"Modifier"** ou **"Paramètres"**

---

### **Étape 3 : Configurer les URLs de Redirection**

Cherchez les sections suivantes (les noms peuvent varier) :

#### **A. URL de Succès / Success URL / Redirect URL**
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}
```

**Copiez-collez exactement cette URL** ⬆️

#### **B. URL d'Annulation / Cancel URL / Failure URL**
```
http://127.0.0.1:8000/evc/payment/chariow/cancel?reference={reference}
```

**Copiez-collez exactement cette URL** ⬆️

#### **C. URL de Notification / Webhook URL / IPN URL** (si disponible)
```
https://votre-url-ngrok.ngrok.io/evc/payment/chariow/webhook
```

**Note :** Pour le webhook en local, vous aurez besoin de **ngrok** (voir plus bas)

---

### **Étape 4 : Configuration des Paramètres Avancés**

Si Chariow le permet, configurez également :

#### **Paramètres à Transmettre :**
- ✅ **reference** → Obligatoire
- ✅ **transaction_id** → Recommandé
- ✅ **amount** → Optionnel
- ✅ **status** → Optionnel
- ✅ **customer_email** → Optionnel

#### **Méthode de Redirection :**
- ✅ **GET** pour les URLs de succès et d'annulation
- ✅ **POST** pour le webhook

---

### **Étape 5 : Sauvegarder**

1. Cliquez sur **"Enregistrer"** ou **"Sauvegarder"**
2. Vérifiez que les URLs sont bien enregistrées
3. Testez avec un paiement de test

---

## 🔍 Vérification des URLs Configurées

### **Dans Chariow :**
Après sauvegarde, vérifiez que les URLs affichées sont :

```
✅ URL de Succès : 
   http://127.0.0.1:8000/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}

✅ URL d'Annulation :
   http://127.0.0.1:8000/evc/payment/chariow/cancel?reference={reference}
```

---

## 🧪 Test Manuel (Sans Payer)

Vous pouvez tester les URLs directement dans votre navigateur :

### **Test Succès :**
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference=EVC-PAY-20251210-6F5D7A52&transaction_id=TEST-123
```

**Résultat attendu :**
- ✅ Compte créé
- ✅ Email envoyé
- ✅ Redirection vers création de mot de passe

### **Test Annulation :**
```
http://127.0.0.1:8000/evc/payment/chariow/cancel?reference=EVC-PAY-20251210-6F5D7A52
```

**Résultat attendu :**
- ❌ Message d'erreur affiché
- ❌ Paiement marqué "cancelled"
- ❌ Aucun compte créé

---

## 🌐 Configuration pour la Production

### **Étape 1 : Obtenir Votre Nom de Domaine**

Exemple : `www.ecolevirtuelle.ci` ou `evc.com`

### **Étape 2 : Modifier les URLs**

Remplacez `http://127.0.0.1:8000` par votre domaine :

```
https://www.ecolevirtuelle.ci/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}
https://www.ecolevirtuelle.ci/evc/payment/chariow/cancel?reference={reference}
https://www.ecolevirtuelle.ci/evc/payment/chariow/webhook
```

### **Étape 3 : Configurer le HTTPS**

**Important :** Chariow peut exiger le HTTPS en production.

Utilisez un certificat SSL (Let's Encrypt gratuit) :
```bash
sudo certbot --apache -d www.ecolevirtuelle.ci
```

---

## 🔧 Configuration Ngrok pour Webhook Local

Pour tester le webhook en environnement local, vous avez besoin de **ngrok** :

### **Installation Ngrok**

**macOS :**
```bash
brew install ngrok
```

**Ou télécharger sur :** https://ngrok.com/download

### **Démarrer Ngrok**

```bash
ngrok http 8000
```

**Résultat :**
```
Forwarding   https://abc123.ngrok.io -> http://localhost:8000
```

### **Configurer dans Chariow**

Utilisez l'URL ngrok pour le webhook :
```
https://abc123.ngrok.io/evc/payment/chariow/webhook
```

**Note :** L'URL ngrok change à chaque redémarrage (version gratuite)

---

## 📊 Structure des Paramètres Chariow

### **Ce que Chariow ENVOIE à EVC :**

#### **Succès (GET sur /chariow/return) :**
```
http://127.0.0.1:8000/evc/payment/chariow/return?
    reference=EVC-PAY-20251210-6F5D7A52
    &transaction_id=CHARIOW-123456789
    &status=success
    &amount=50000
```

#### **Annulation (GET sur /chariow/cancel) :**
```
http://127.0.0.1:8000/evc/payment/chariow/cancel?
    reference=EVC-PAY-20251210-6F5D7A52
    &status=cancelled
```

#### **Webhook (POST sur /chariow/webhook) :**
```json
{
  "transaction_id": "CHARIOW-123456789",
  "reference": "EVC-PAY-20251210-6F5D7A52",
  "status": "success",
  "amount": 50000,
  "currency": "XOF",
  "customer_email": "etudiant@email.com",
  "created_at": "2025-12-10T14:30:00Z"
}
```

---

## 🔒 Sécurité

### **Validation Côté EVC**

Le système EVC valide automatiquement :

✅ **Référence de paiement** existe dans la base de données  
✅ **Paiement non déjà traité** (pas de double traitement)  
✅ **Données cohérentes** (montant, email, etc.)

### **Protection CSRF**

Les routes de retour Chariow sont exemptées du CSRF :

```php
// Dans app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'evc/payment/chariow/webhook',
];
```

---

## 🐛 Dépannage

### **Problème : "URL de retour introuvable" dans Chariow**

**Cause :** Vous n'avez pas trouvé où configurer les URLs

**Solutions :**
1. Cherchez : "Paramètres", "Settings", "URLs de redirection"
2. Contactez le support Chariow
3. Consultez la documentation Chariow

### **Problème : Pas de Redirection après Paiement**

**Vérifier :**
```bash
# 1. Les routes EVC existent
php artisan route:list | grep chariow

# Résultat attendu :
# GET|HEAD  evc/payment/chariow/return
# GET|HEAD  evc/payment/chariow/cancel
# POST      evc/payment/chariow/webhook
```

**Vérifier les logs :**
```bash
tail -f storage/logs/laravel.log | grep Chariow
```

### **Problème : Webhook Ne Fonctionne Pas**

**En local, utilisez ngrok :**
```bash
ngrok http 8000
```

**Puis configurez l'URL ngrok dans Chariow**

**En production, vérifiez :**
- ✅ HTTPS activé
- ✅ Pare-feu autorise les requêtes POST
- ✅ URL accessible publiquement

---

## ✅ Checklist de Configuration

- [ ] Connexion à Chariow
- [ ] Produit `prd_ngqtqy` trouvé
- [ ] URL de succès configurée : `/chariow/return?reference={reference}&transaction_id={transaction_id}`
- [ ] URL d'annulation configurée : `/chariow/cancel?reference={reference}`
- [ ] URL webhook configurée (optionnel) : `/chariow/webhook`
- [ ] Paramètres sauvegardés
- [ ] Test manuel effectué : `/chariow/return?reference=TEST&transaction_id=123`
- [ ] Test manuel effectué : `/chariow/cancel?reference=TEST`
- [ ] Logs vérifiés : `tail -f storage/logs/laravel.log`
- [ ] Paiement de test effectué sur Chariow
- [ ] Redirection fonctionne
- [ ] Compte créé automatiquement
- [ ] Email reçu

---

## 📞 Support

### **Si vous ne trouvez pas où configurer les URLs dans Chariow :**

**Contactez le support Chariow :**
- Email : support@mychariow.shop
- Ou via leur interface de support

**Dites-leur :**
```
Bonjour,

Je souhaite configurer les URLs de retour pour mon produit prd_ngqtqy.

URLs à configurer :

1. URL de succès (après paiement réussi) :
   http://127.0.0.1:8000/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}

2. URL d'annulation (après paiement annulé) :
   http://127.0.0.1:8000/evc/payment/chariow/cancel?reference={reference}

3. URL webhook (notifications) :
   https://mon-ngrok.ngrok.io/evc/payment/chariow/webhook

Pourriez-vous m'indiquer où configurer ces URLs ?

Merci !
```

---

## 📚 Documentation Complémentaire

**Guides créés :**
- ✅ `ACTIVATION_CHARIOW_GUIDE.md` - Activation de Chariow
- ✅ `WORKFLOW_PAIEMENT_CHARIOW.md` - Workflow complet
- ✅ `INTEGRATION_CHARIOW_GUIDE.md` - Guide d'intégration
- ✅ `CHARIOW_QUICK_START.md` - Démarrage rapide
- ✅ `PROBLEME_PAYMENT_URL_CORRIGE.md` - Solutions aux problèmes

---

## 🎯 Résumé Ultra-Rapide

**À copier-coller dans Chariow :**

```
URL de Succès :
http://127.0.0.1:8000/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}

URL d'Annulation :
http://127.0.0.1:8000/evc/payment/chariow/cancel?reference={reference}

URL Webhook (optionnel) :
https://votre-ngrok.ngrok.io/evc/payment/chariow/webhook
```

**Puis :**
1. Sauvegarder dans Chariow
2. Tester avec un paiement
3. Vérifier les logs EVC

---

**C'est tout ! Votre configuration est prête ! 🎉**
