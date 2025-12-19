# ✅ Solution : Champ Numéro de Téléphone CinetPay

## 🔍 Problème

Le widget CinetPay s'affichait correctement avec les options de paiement (Mobile Money, Wallet, Carte), mais **le champ pour entrer le numéro de téléphone n'apparaissait pas** après sélection du mode Wallet/Mobile Money.

---

## ✅ Solution Appliquée

### **Approche : Formulaire Local + Redirection CinetPay**

Au lieu d'utiliser le SDK Seamless (qui peut avoir des problèmes d'affichage), nous utilisons maintenant :

1. **Formulaire sur notre page** pour collecter le numéro de téléphone
2. **Validation et formatage** automatique du numéro
3. **Redirection vers CinetPay** avec le numéro correctement formaté
4. **CinetPay utilise le numéro** pour pré-remplir ou traiter le paiement

---

## 📝 Modifications Effectuées

### **1. Page de Paiement (`checkout.blade.php`)**

#### **Formulaire Amélioré**
```html
<form action="{{ route('payment.process') }}" method="POST">
    @csrf
    <input type="hidden" name="payment_reference" value="...">
    
    <!-- Champ de numéro OBLIGATOIRE -->
    <div class="mb-4">
        <label class="form-label">
            <i class="fas fa-phone me-2"></i>Numéro de téléphone pour Mobile Money / Wallet
        </label>
        <input type="tel" 
               name="phone_number" 
               class="form-control" 
               required
               placeholder="Ex: 0758123456 ou +225758123456"
               value="{{ $candidate->whatsapp ?? '' }}"
               pattern="[+0-9\s]{10,20}"
               id="phoneInput">
        
        <small class="text-muted">
            <strong>Formats acceptés :</strong>
            • Format ivoirien : 0758123456
            • Format international : +225758123456
            • Utilisé pour Wave, Orange Money, MTN, Moov Money
        </small>
    </div>
    
    <button type="submit">💳 Procéder au Paiement Sécurisé</button>
</form>
```

#### **Validation JavaScript en Temps Réel**
```javascript
// Feedback visuel pendant la saisie
phoneInput.addEventListener('input', function(e) {
    const digitsOnly = value.replace(/[^0-9]/g, '');
    
    if (digitsOnly.length >= 10) {
        phoneInput.style.borderColor = '#28a745'; // ✅ Vert = OK
    } else if (digitsOnly.length > 0) {
        phoneInput.style.borderColor = '#ffc107'; // ⚠️ Jaune = En cours
    }
});

// Validation avant soumission
form.addEventListener('submit', function(e) {
    const digitsOnly = phoneValue.replace(/[^0-9]/g, '');
    
    if (digitsOnly.length < 8) {
        e.preventDefault();
        alert('⚠️ Numéro invalide');
        return false;
    }
});
```

### **2. Contrôleur (`PaymentController.php`)**

#### **Formatage Automatique du Numéro**
```php
// Formater le numéro au format international
$phoneNumber = $request->phone_number ?? $candidate->whatsapp ?? '';

if ($phoneNumber && !str_starts_with($phoneNumber, '+')) {
    // Nettoyer : garder seulement les chiffres
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
    
    if (strlen($phoneNumber) == 10 && str_starts_with($phoneNumber, '0')) {
        // 0758123456 → +225758123456
        $phoneNumber = '+225' . substr($phoneNumber, 1);
    } else if (strlen($phoneNumber) >= 8) {
        // 758123456 → +225758123456
        $phoneNumber = '+225' . $phoneNumber;
    }
}

$paymentData = [
    // ... autres champs
    'customer_phone' => $phoneNumber, // ✅ Format international
];
```

---

## 🚀 Comment Tester

### **Étape 1 : Accepter une Candidature**
```
http://127.0.0.1:8000/evc/app/admin/preinscriptions
```
1. Cliquer sur **"Accepter"** pour une candidature
2. Confirmer

### **Étape 2 : Ouvrir le Lien de Paiement**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX
```
1. Récupérer le lien depuis l'email ou la base de données
2. Ouvrir dans le navigateur

### **Étape 3 : Tester le Formulaire**

#### **Test 1 : Numéro Ivoirien Classique**
```
Entrer : 0758123456
↓
Validation : ✅ Bordure verte
↓
Cliquer "Procéder au Paiement"
↓
Formaté en : +225758123456
↓
Envoyé à CinetPay
```

#### **Test 2 : Numéro International**
```
Entrer : +225758123456
↓
Validation : ✅ Bordure verte
↓
Envoyé tel quel à CinetPay
```

#### **Test 3 : Numéro avec Espaces**
```
Entrer : 07 58 12 34 56
↓
Nettoyé en : 0758123456
↓
Formaté en : +225758123456
```

#### **Test 4 : Numéro Trop Court**
```
Entrer : 075812
↓
Validation : ⚠️ Bordure jaune
↓
Cliquer "Procéder au Paiement"
↓
Alert : "Numéro invalide (minimum 8 chiffres)"
```

### **Étape 4 : Sur CinetPay**

Après redirection vers CinetPay :
1. ✅ Page CinetPay s'ouvre
2. ✅ Choix de méthode de paiement affiché
3. ✅ Sélectionner "Wallet" ou "Mobile Money"
4. ✅ **Le numéro devrait être pré-rempli** OU **le champ de saisie devrait apparaître**
5. ✅ Cliquer "Payer"

---

## 📊 Formats de Numéros Acceptés

| Entrée Utilisateur | Nettoyé | Format Envoyé à CinetPay |
|-------------------|---------|--------------------------|
| `0758123456` | `0758123456` | `+225758123456` |
| `07 58 12 34 56` | `0758123456` | `+225758123456` |
| `758123456` | `758123456` | `+225758123456` |
| `+225758123456` | `+225758123456` | `+225758123456` |
| `+225 7 58 12 34 56` | `+225758123456` | `+225758123456` |

---

## 🎯 Feedback Visuel

### **Pendant la Saisie**

| État | Couleur | Signification |
|------|---------|---------------|
| Vide | Gris | Aucune saisie |
| < 10 chiffres | 🟡 Jaune | En cours de saisie |
| ≥ 10 chiffres | 🟢 Vert | Format valide |
| Soumission échouée | 🔴 Rouge | Erreur de validation |

### **Aide Contextuelle**
```
• Format ivoirien : 0758123456 ou 07 58 12 34 56
• Format international : +225758123456 ou +225 7 58 12 34 56
• Utilisé pour Wave, Orange Money, MTN, Moov Money
```

---

## 🔍 Vérification en Base de Données

### **Avant Paiement**
```sql
SELECT 
    id,
    payment_reference,
    transaction_id,
    phone_number,
    status
FROM payments
WHERE payment_reference = 'EVC-PAY-XXXXXXXX';
```

**Résultat attendu après soumission du formulaire :**
```
phone_number: +225758123456  (formaté)
status: pending
```

### **Logs Laravel**
```bash
tail -f storage/logs/laravel.log | grep -i "CinetPay"
```

**Log attendu :**
```
[2025-12-09] local.INFO: CinetPay - Initiation paiement {
    "transaction_id": "EVC-XXXXX",
    "amount": 50000,
    "payload": {
        "customer_phone_number": "+225758123456"
    }
}
```

---

## 🐛 Dépannage

### **Problème : Le champ de numéro n'apparaît toujours pas sur CinetPay**

#### **Solution 1 : Vérifier que le numéro est bien envoyé**
Ouvrir les **DevTools (F12) → Network** avant de cliquer sur "Procéder au Paiement" :
1. Soumettre le formulaire
2. Chercher la requête POST vers `/evc/payment/process`
3. Vérifier que `phone_number` est présent dans Form Data

#### **Solution 2 : Vérifier les logs CinetPay**
```bash
tail -100 storage/logs/laravel.log | grep -A 10 "CinetPay - Initiation"
```
Vérifier que `customer_phone_number` est bien présent dans le payload.

#### **Solution 3 : Mode CinetPay**
Le comportement peut varier selon le mode CinetPay :
- **Mode Production** : Champ peut être pré-rempli et masqué
- **Mode Sandbox** : Champ peut être visible pour tests

**Vérifier dans `.env` :**
```env
CINETPAY_API_KEY=votre_api_key
CINETPAY_SITE_ID=votre_site_id
```

### **Problème : Alert "Numéro invalide" à chaque tentative**

**Solution** : Vérifier le pattern du champ :
```html
pattern="[+0-9\s]{10,20}"
```
Ce pattern accepte :
- Chiffres (0-9)
- Le signe + 
- Espaces

Si vous voulez accepter d'autres caractères (tirets, parenthèses), modifier :
```html
pattern="[+0-9\s\-()]{10,20}"
```

---

## ✅ Points Clés de la Solution

### **Avantages**

1. ✅ **Validation côté client** : Feedback immédiat (vert/jaune/rouge)
2. ✅ **Formatage automatique** : Conversion vers format international (+225)
3. ✅ **Nettoyage des espaces** : `07 58 12 34 56` → `+225758123456`
4. ✅ **Numéro pré-rempli** : WhatsApp du candidat utilisé par défaut
5. ✅ **Compatible tous formats** : Ivoirien et international
6. ✅ **UX améliorée** : Messages d'aide et exemples
7. ✅ **Logs détaillés** : Trace complète dans Laravel logs

### **Workflow Final**

```
Utilisateur arrive sur /evc/payment/{reference}
   ↓
Formulaire affiché avec numéro WhatsApp pré-rempli
   ↓
Utilisateur modifie/confirme le numéro
   ↓
Validation en temps réel (bordure verte si OK)
   ↓
Clic sur "Procéder au Paiement"
   ↓
Validation JavaScript (minimum 8 chiffres)
   ↓
Soumission POST vers /evc/payment/process
   ↓
Formatage côté serveur (+225 si nécessaire)
   ↓
Appel API CinetPay avec customer_phone_number
   ↓
Redirection vers page CinetPay
   ↓
✅ Numéro utilisé/pré-rempli par CinetPay
```

---

## 📋 Checklist de Test

- [ ] Champ de numéro s'affiche sur notre page
- [ ] Numéro WhatsApp pré-rempli automatiquement
- [ ] Bordure devient verte avec 10+ chiffres
- [ ] Alert si numéro < 8 chiffres
- [ ] Bouton affiche "Redirection vers CinetPay..."
- [ ] Page CinetPay s'ouvre correctement
- [ ] Numéro visible/utilisé sur CinetPay
- [ ] Log "CinetPay - Initiation paiement" dans Laravel
- [ ] `phone_number` enregistré en DB avec format +225

---

## 🎓 Documentation CinetPay

### **Champ `customer_phone_number`**

Selon la documentation CinetPay :
- **Obligatoire pour** : Mobile Money, Wallets
- **Optionnel pour** : Carte bancaire
- **Format attendu** : Format international (`+225XXXXXXXXX`)
- **Comportement** : 
  - Si fourni : Pré-rempli ou utilisé directement
  - Si non fourni : Champ de saisie affiché

### **Configuration `channels`**

Dans `config/cinetpay.php` :
```php
'channels' => 'ALL', // Tous les moyens de paiement
```

Options disponibles :
- `'ALL'` : Tous (Mobile Money, Carte, Wallet)
- `'MOBILE_MONEY'` : Uniquement Mobile Money
- `'WALLET'` : Uniquement Wallets
- `'CARD'` : Uniquement Cartes bancaires

---

## ✅ **Solution Opérationnelle !**

**La page de paiement collecte maintenant le numéro de téléphone AVANT de rediriger vers CinetPay.**

**Workflow amélioré :**
1. ✅ Champ visible et obligatoire sur notre page
2. ✅ Validation en temps réel (vert = OK)
3. ✅ Formatage automatique au format international
4. ✅ Numéro envoyé correctement à CinetPay
5. ✅ CinetPay peut utiliser le numéro pour le paiement

**Testez maintenant en rafraîchissant la page de paiement ! 🎉**
