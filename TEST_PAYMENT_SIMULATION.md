# 🧪 Simulation de Paiement CinetPay (Mode TEST Local)

## 🔍 Problème Identifié

**En développement local (127.0.0.1), les webhooks CinetPay ne peuvent pas fonctionner car :**
- CinetPay essaie de faire un callback vers `http://127.0.0.1:8000/evc/payment/webhook`
- Cette URL n'est accessible que depuis votre machine, pas depuis les serveurs CinetPay
- Le webhook ne sera jamais appelé, donc le paiement ne sera jamais confirmé

## ✅ Solutions

### **Solution 1 : Simuler le Webhook Manuellement**

Pour tester le système de paiement par tranche, vous pouvez simuler un webhook :

1. **Copier la transaction_id** depuis les logs :
   ```
   EVC-20251209-668B170C
   ```

2. **Ouvrir votre navigateur et aller sur cette URL** :
   ```
   http://127.0.0.1:8000/evc/payment/webhook?cpm_trans_id=EVC-20251209-668B170C&cpm_amount=100&cpm_trans_status=00&cpm_site_id=105904453
   ```

3. **Le système va** :
   - ✅ Marquer le paiement comme `completed`
   - ✅ Envoyer l'email de confirmation avec lien création compte
   - ✅ Détecter que c'est la 1ère tranche
   - ✅ Envoyer automatiquement l'email pour la 2ème tranche

### **Solution 2 : Utiliser ngrok (Recommandé pour Tests)**

Pour tester avec de vrais callbacks CinetPay :

1. **Installer ngrok** :
   ```bash
   brew install ngrok
   # ou télécharger depuis https://ngrok.com
   ```

2. **Exposer votre serveur local** :
   ```bash
   ngrok http 8000
   ```

3. **Copier l'URL publique** (ex: `https://abc123.ngrok.io`)

4. **Modifier `.env`** :
   ```
   APP_URL=https://abc123.ngrok.io
   ```

5. **Vider le cache** :
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

6. **Réessayer le paiement** avec la nouvelle candidature

### **Solution 3 : Mode Production (Serveur en Ligne)**

En production avec un vrai domaine (ex: `https://evc.com`), les webhooks fonctionneront automatiquement.

## 🎯 Test Rapide (Simulation Manuelle)

Pour votre test actuel, utilisez cette URL :

```
http://127.0.0.1:8000/evc/payment/webhook?cpm_trans_id=EVC-20251209-668B170C&cpm_amount=100&cpm_trans_status=00&cpm_site_id=105904453
```

Ou via la console :

```bash
curl "http://127.0.0.1:8000/evc/payment/webhook?cpm_trans_id=EVC-20251209-668B170C&cpm_amount=100&cpm_trans_status=00&cpm_site_id=105904453"
```

## 📊 Workflow Complet du Test

1. **Admin accepte candidature** → 2 paiements créés en DB
2. **Email 1ère tranche envoyé** → Lien de paiement 100 FCFA
3. **Candidat clique sur lien** → Redirigé vers CinetPay
4. **❌ CinetPay TEST échoue** (car webhook local impossible)
5. **✅ Simuler webhook manuellement** → Marque paiement comme complété
6. **✅ Email 2ème tranche envoyé automatiquement** → 76 900 FCFA
7. **Candidat paie 2ème tranche** → Même processus
8. **✅ Inscription finalisée** 🎉

## 🔄 Vérification Base de Données

Après simulation du webhook :

```sql
SELECT 
    payment_reference,
    amount,
    status,
    payment_type,
    installment_number,
    paid_at
FROM payments
ORDER BY id DESC
LIMIT 5;
```

**Résultat attendu :**
```
Paiement #1: 100 FCFA, installment 1/2, status: completed ✅
Paiement #2: 76900 FCFA, installment 2/2, status: pending
```

## 💡 Pourquoi CinetPay Échoue en Local ?

CinetPay TEST Mode essaie de :
1. Créer la transaction ✅
2. Afficher la page de paiement ✅
3. Faire un callback vers votre webhook ❌ (impossible en local)
4. Sans callback, le paiement reste "en attente" indéfiniment

**C'est normal en développement local !**

## 🚀 Prochaines Étapes

1. ✅ Corriger APP_URL dans .env ou utiliser ngrok
2. ✅ Simuler webhook manuellement pour tester le système
3. ✅ Vérifier que l'email 2ème tranche est bien envoyé
4. ✅ Tester avec ngrok pour un test plus réaliste
5. ✅ Déployer en production pour tests réels
