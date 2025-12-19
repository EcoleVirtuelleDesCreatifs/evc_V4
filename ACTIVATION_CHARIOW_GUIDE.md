# 🛒 Activation de Chariow - Guide Rapide

## ✅ Configuration Effectuée

Le lien de paiement Chariow a été configuré pour toutes les formations :

```
https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout
```

**Fichier modifié :** `config/chariow.php`

---

## 🚀 Activer Chariow (1 Étape)

### **Dans le fichier `.env` :**

Ajoutez ou modifiez cette ligne :

```env
CHARIOW_ENABLED=true
```

**Si la ligne n'existe pas**, ajoutez-la à la fin du fichier `.env`.

---

## 🧪 Tester Immédiatement

### **1. Rafraîchir la Configuration**
```bash
cd /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC
php artisan config:clear
php artisan cache:clear
```

### **2. Ouvrir le Lien de Paiement**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-20251210-D759645B
```

### **3. Cliquer sur "Procéder au paiement"**

**Résultat attendu :**
```
Vous serez redirigé vers :
https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout?reference=EVC-PAY-20251210-D759645B&email=infos.evc2022@gmail.com&amount=50000&...
```

---

## 📊 Informations du Paiement de Test

| Élément | Valeur |
|---------|--------|
| **Référence** | EVC-PAY-20251210-D759645B |
| **Formation** | Design Graphique |
| **Candidat** | Koffi Juliette |
| **Montant** | 50 000 FCFA |
| **Statut** | Pending |
| **Tranche** | 1ère (sur 2) |

---

## 🔄 Workflow Complet

```
1. Candidat ouvre le lien de paiement
   http://127.0.0.1:8000/evc/payment/EVC-PAY-20251210-D759645B
   
2. Page de paiement EVC s'affiche
   "Formation : Design Graphique"
   "Montant : 50 000 FCFA"
   
3. Candidat clique "Procéder au paiement"
   
4. PaymentController détecte CHARIOW_ENABLED=true
   
5. ChariowService génère l'URL avec paramètres :
   - reference : EVC-PAY-20251210-D759645B
   - email : infos.evc2022@gmail.com
   - amount : 50000
   - formation : design_graphique
   
6. Redirection automatique vers Chariow :
   https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout?...
   
7. Candidat paie sur Chariow
   
8. Après paiement, Chariow doit rediriger vers :
   http://127.0.0.1:8000/evc/payment/chariow/return?reference=EVC-PAY-20251210-D759645B
   
9. Système marque le paiement "completed"
   
10. Compte utilisateur créé automatiquement
    
11. Email de bienvenue envoyé
```

---

## 📝 Fichier .env - Exemple Complet

```env
# ... Autres configurations ...

# ===================================
# CHARIOW - Paiement
# ===================================

# Activer Chariow (true) ou utiliser CinetPay (false)
CHARIOW_ENABLED=true

# URLs de retour après paiement Chariow
CHARIOW_RETURN_URL="${APP_URL}/evc/payment/chariow/return"
CHARIOW_CANCEL_URL="${APP_URL}/evc/payment/chariow/cancel"
CHARIOW_WEBHOOK_URL="${APP_URL}/evc/payment/chariow/webhook"

# Mode de paiement
CHARIOW_MODE=direct_link
```

---

## 🔍 Vérifier que Chariow est Activé

**Commande rapide :**
```bash
php artisan tinker --execute="
echo 'Chariow activé : ' . (config('chariow.enabled') ? 'OUI ✅' : 'NON ❌') . PHP_EOL;
echo 'Lien Design Graphique (1ère tranche) : ' . config('chariow.payment_links.design_graphique.tranche_1');
"
```

**Résultat attendu :**
```
Chariow activé : OUI ✅
Lien Design Graphique (1ère tranche) : https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout
```

---

## 🐛 Dépannage

### **Problème : Toujours redirigé vers CinetPay**

**Solution :**
```bash
# 1. Vérifier le .env
grep CHARIOW_ENABLED .env

# 2. Si la ligne n'existe pas, l'ajouter
echo "CHARIOW_ENABLED=true" >> .env

# 3. Vider le cache
php artisan config:clear
php artisan cache:clear
```

### **Problème : Erreur "Lien de paiement introuvable"**

**Cause :** Le nom de la formation ne correspond pas exactement.

**Solution :**
Le système accepte maintenant :
- `Design Graphique` (avec majuscules)
- `design_graphique` (en minuscules)

Si le problème persiste, vérifier dans les logs :
```bash
tail -50 storage/logs/laravel.log | grep Chariow
```

### **Problème : Pas de redirection**

**Vérifier que le code de redirection est actif :**
```bash
php artisan tinker --execute="
echo 'ChariowService existe : ' . (class_exists('App\Services\ChariowService') ? 'OUI' : 'NON') . PHP_EOL;
"
```

---

## 📊 Logs à Surveiller

**Après avoir cliqué sur "Procéder au paiement" :**

```bash
tail -f storage/logs/laravel.log
```

**Logs attendus :**
```
[2025-12-10] local.INFO: 🛒 Redirection vers Chariow {"payment_reference":"EVC-PAY-20251210-D759645B","formation":"design_graphique"}
[2025-12-10] local.INFO: Lien de paiement Chariow généré {"formation":"design_graphique","installment":1,"link":"https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout"}
[2025-12-10] local.INFO: URL de paiement Chariow générée {"url":"https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout?reference=..."}
```

---

## ✅ Checklist de Vérification

- [ ] `CHARIOW_ENABLED=true` ajouté dans `.env`
- [ ] Configuration vidée (`php artisan config:clear`)
- [ ] Cache vidé (`php artisan cache:clear`)
- [ ] Lien de paiement ouvert dans le navigateur
- [ ] Clic sur "Procéder au paiement"
- [ ] Redirection vers Chariow confirmée
- [ ] URL contient `ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout`
- [ ] Paramètres `reference`, `email`, `amount` présents dans l'URL

---

## 🎯 Test Complet en 3 Commandes

```bash
# 1. Activer Chariow
echo "CHARIOW_ENABLED=true" >> .env

# 2. Vider les caches
php artisan config:clear && php artisan cache:clear

# 3. Ouvrir dans le navigateur
open http://127.0.0.1:8000/evc/payment/EVC-PAY-20251210-D759645B
```

---

## 🔗 Liens Importants

**Paiement local :**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-20251210-D759645B
```

**Boutique Chariow :**
```
https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout
```

**Retour après paiement :**
```
http://127.0.0.1:8000/evc/payment/chariow/return
```

---

## 📧 Configuration du Retour Chariow

Dans votre boutique Chariow, configurez l'URL de retour après paiement :

**URL de succès :**
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}
```

**URL d'annulation :**
```
http://127.0.0.1:8000/evc/payment/chariow/cancel?reference={reference}
```

**Webhook (optionnel) :**
```
http://127.0.0.1:8000/evc/payment/chariow/webhook
```

---

## ✅ Tout est Prêt !

**Le système est maintenant configuré pour rediriger vers votre boutique Chariow.**

**Testez immédiatement en ouvrant le lien de paiement ! 🚀**

---

**Documentation complète :** `INTEGRATION_CHARIOW_GUIDE.md`

**Guide rapide :** `CHARIOW_QUICK_START.md`
