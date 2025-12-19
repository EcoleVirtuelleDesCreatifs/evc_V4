# 🔍 Guide de débogage CinetPay

## ✅ Corrections appliquées

1. **Mapping des formations** - Ajout des versions minuscules (design_graphique, etc.)
2. **Logs améliorés** - Plus de détails sur les erreurs
3. **Mode TEST activé** - Pour déboguer facilement

---

## 🎯 Étapes de débogage

### Étape 1 : Réessayer le paiement

1. Cliquez à nouveau sur le lien de paiement
2. Cliquez sur "Procéder au paiement"
3. Notez l'erreur exacte affichée

### Étape 2 : Consulter les logs

```bash
tail -f storage/logs/laravel.log
```

Recherchez les lignes contenant :
- `CinetPay - Initiation paiement`
- `CinetPay - Erreur HTTP`

### Étape 3 : Vérifier la réponse de l'API

Dans les logs, vous verrez :
- Le payload envoyé
- La réponse de CinetPay
- Le code d'erreur exact

---

## ⚠️ Causes possibles de l'erreur

### 1. Clés API invalides

**Vérifiez dans votre dashboard CinetPay :**
- API Key : `10668199396890d4fd224ef9.31505780`
- Site ID : `105904453`
- Secret Key : `466948352689ad54be91ca012056423`

**Ces clés doivent correspondre exactement !**

### 2. Mode TEST vs PRODUCTION

Si vos clés sont pour le mode **TEST**, assurez-vous que :
- `config/cinetpay.php` : `'mode' => 'TEST'`
- Ou dans `.env` : `CINETPAY_MODE=TEST`

### 3. URL de notification (Webhook)

CinetPay doit pouvoir accéder à votre URL de webhook.

**Problème :** En local (`http://127.0.0.1:8000`), CinetPay ne peut PAS accéder

**Solutions :**
- **Option A** : Utiliser ngrok pour exposer votre serveur local
  ```bash
  ngrok http 8000
  # Utiliser l'URL fournie dans .env
  ```
- **Option B** : Tester sur un serveur avec une IP publique/domaine

### 4. Compte CinetPay non activé

Vérifiez que votre compte CinetPay est :
- ✅ Validé
- ✅ Compte marchand activé
- ✅ Autorisé à recevoir des paiements

---

## 🔧 Configuration recommandée pour les tests

### Fichier `.env`

```env
# Mode TEST pour déboguer
CINETPAY_MODE=TEST

# Clés CinetPay (à vérifier sur votre dashboard)
CINETPAY_API_KEY=10668199396890d4fd224ef9.31505780
CINETPAY_DESIGN_SITE_ID=105904453
CINETPAY_DESIGN_SECRET=466948352689ad54be91ca012056423

# URLs (avec ngrok si en local)
CINETPAY_RETURN_URL="${APP_URL}/evc/payment/return"
CINETPAY_NOTIFY_URL="${APP_URL}/evc/payment/webhook"
CINETPAY_CANCEL_URL="${APP_URL}/evc/payment/cancel"
```

### Utiliser ngrok (pour tester en local)

```bash
# 1. Installer ngrok (si pas encore fait)
# 2. Lancer ngrok
ngrok http 8000

# 3. Copier l'URL fournie (ex: https://abc123.ngrok.io)
# 4. Mettre à jour APP_URL dans .env
APP_URL=https://abc123.ngrok.io

# 5. Nettoyer le cache
php artisan config:clear
```

---

## 📊 Interprétation des codes d'erreur CinetPay

| Code | Signification | Solution |
|------|---------------|----------|
| **201** | Paiement initié avec succès | ✅ Tout va bien |
| **401** | API Key invalide | Vérifier CINETPAY_API_KEY |
| **403** | Site ID invalide | Vérifier CINETPAY_DESIGN_SITE_ID |
| **404** | Endpoint introuvable | Vérifier l'URL de l'API |
| **422** | Données invalides | Vérifier le payload (logs) |
| **500** | Erreur serveur CinetPay | Réessayer plus tard |

---

## 🧪 Test avec données fictives

Si vous voulez tester sans vraiment payer :

1. **Mode TEST** doit être activé
2. Utilisez les numéros de test CinetPay :
   - Orange Money : `+2250700000000`
   - MTN Money : `+2250500000000`
   - Moov Money : `+2250100000000`

---

## 📞 Support CinetPay

Si le problème persiste :

1. **Documentation** : https://docs.cinetpay.com
2. **Support** : support@cinetpay.com
3. **Dashboard** : https://dashboard.cinetpay.com

---

## ✅ Checklist de vérification

- [ ] Clés API correctes dans `.env`
- [ ] Mode TEST ou PRODUCTION approprié
- [ ] Cache nettoyé (`php artisan config:clear`)
- [ ] Logs consultés (`tail -f storage/logs/laravel.log`)
- [ ] Compte CinetPay activé
- [ ] Webhook accessible (ngrok si local)

---

## 🎯 Prochaines étapes

1. **Réessayer** le paiement
2. **Consulter** les logs pour voir l'erreur exacte
3. **Vérifier** les clés API sur le dashboard CinetPay
4. **Utiliser ngrok** si vous testez en local
5. **Me communiquer** les logs pour plus d'aide

---

💡 **Astuce** : La plupart des erreurs viennent de :
1. Clés API incorrectes (90%)
2. Mauvais mode TEST/PRODUCTION (5%)
3. Webhook inaccessible en local (5%)
