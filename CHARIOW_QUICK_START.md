# 🚀 Chariow - Guide Rapide

## ⚡ En 3 Étapes

### **1️⃣ Activer Chariow**

**Fichier `.env` :**
```env
CHARIOW_ENABLED=true
```

### **2️⃣ Configurer les Liens**

**Fichier `config/chariow.php` :**
```php
'payment_links' => [
    'Design Graphique' => [
        'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/designgraphique/checkout',
        'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/designgraphique/tranche2/checkout',
    ],
    // Ajouter vos autres formations
],
```

### **3️⃣ Ajouter les Méthodes au Contrôleur**

**Fichier `app/Http/Controllers/PaymentController.php` :**

Copiez-collez ces 3 méthodes à la fin de la classe (avant le dernier `}`) :

```php
/**
 * Retour après paiement Chariow réussi
 */
public function chariowReturn(Request $request)
{
    $reference = $request->input('reference');
    $transactionId = $request->input('transaction_id') ?? 'CHARIOW-' . uniqid();
    
    if (!$reference) {
        return redirect()->route('login')->with('error', 'Référence manquante');
    }

    $payment = DB::table('payments')->where('payment_reference', $reference)->first();
    
    if (!$payment) {
        return redirect()->route('login')->with('error', 'Paiement introuvable');
    }

    // Marquer comme payé
    DB::table('payments')->where('id', $payment->id)->update([
        'status' => 'completed',
        'transaction_id' => $transactionId,
        'paid_at' => now(),
        'updated_at' => now(),
    ]);

    // Récupérer le candidat
    $candidate = DB::table('pre_registrations')->where('id', $payment->pre_registration_id)->first();

    // Créer le compte si 1ère tranche
    if ($payment->installment_number == 1) {
        // Logique de création de compte (utilisateur + profil étudiant)
        // Voir INTEGRATION_CHARIOW_GUIDE.md pour le code complet
    }

    // Mettre à jour la préinscription
    DB::table('pre_registrations')->where('id', $payment->pre_registration_id)->update([
        'status' => 'paid',
        'updated_at' => now(),
    ]);

    return view('payment.success', compact('payment', 'candidate'));
}

/**
 * Retour après annulation
 */
public function chariowCancel(Request $request)
{
    return redirect()->route('login')->with('error', 'Paiement annulé');
}

/**
 * Webhook Chariow (optionnel)
 */
public function chariowWebhook(Request $request)
{
    Log::info('Webhook Chariow reçu', $request->all());
    
    // Traiter le webhook si Chariow en envoie
    
    return response()->json(['status' => 'success']);
}
```

---

## ✅ Tester

### **1. Accepter une candidature**
```
http://127.0.0.1:8000/evc/app/admin/preinscriptions
```

### **2. Ouvrir le lien de paiement**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX
```

### **3. Cliquer sur "Procéder au paiement"**

Vous serez redirigé vers :
```
https://ecolevirtuelle.mychariow.shop/designgraphique/checkout?reference=...&email=...&amount=50000
```

### **4. Après paiement sur Chariow**

Chariow doit rediriger vers :
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference=EVC-PAY-XXXXXXXX&transaction_id=CHARIOW-123
```

---

## 📋 Workflow Complet

```
┌─────────────────────────────────────┐
│ 1. Admin accepte candidature        │
│    → Email avec lien paiement       │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│ 2. Candidat ouvre le lien          │
│    → Page paiement EVC s'affiche   │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│ 3. Clic "Procéder au paiement"     │
│    → Redirection vers Chariow      │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│ 4. Paiement sur Chariow            │
│    → Formulaire de paiement        │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│ 5. Retour vers EVC                 │
│    → chariowReturn() s'exécute     │
└─────────────────────────────────────┘
              ↓
┌─────────────────────────────────────┐
│ 6. Paiement marqué "completed"     │
│    → Compte créé automatiquement   │
│    → Email envoyé                  │
└─────────────────────────────────────┘
```

---

## 🛠️ Configuration Chariow (Boutique)

Dans votre boutique Chariow, pour chaque formation :

### **Créer un Produit**
- Nom : Formation Design Graphique - 1ère Tranche
- Prix : 50 000 FCFA

### **Configurer l'URL de Retour**
Après paiement, Chariow doit rediriger vers :
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}
```

Où :
- `{reference}` = La référence EVC reçue dans l'URL (paramètre GET)
- `{transaction_id}` = L'ID de transaction Chariow

---

## 📦 Fichiers Créés

| Fichier | Description |
|---------|-------------|
| `config/chariow.php` | Configuration des liens de paiement |
| `app/Services/ChariowService.php` | Service pour gérer Chariow |
| `.env.chariow.example` | Exemple de configuration .env |
| `routes/web.php` | Routes Chariow ajoutées (lignes 100-105) |
| `INTEGRATION_CHARIOW_GUIDE.md` | Guide complet et détaillé |

---

## 💡 Avantages

✅ **Plus simple** que CinetPay (pas d'API complexe)  
✅ **Flexible** : Un lien par formation et par tranche  
✅ **Rapide** : Simple redirection  
✅ **Compatible** avec votre boutique existante  

---

## 🆚 CinetPay vs Chariow

| Critère | CinetPay | Chariow |
|---------|----------|---------|
| Complexité | ⭐⭐⭐⭐ | ⭐ |
| Configuration | API Key, Secret | Liens de paiement |
| Code nécessaire | Beaucoup | Minimal |
| Flexibilité | Haute | Moyenne |

---

## 🔄 Basculer Entre les Deux

**Pour utiliser Chariow :**
```env
CHARIOW_ENABLED=true
```

**Pour utiliser CinetPay :**
```env
CHARIOW_ENABLED=false
```

C'est tout ! 🎉

---

## 📚 Documentation Complète

Pour plus de détails, voir :
- `INTEGRATION_CHARIOW_GUIDE.md` - Guide complet
- `config/chariow.php` - Configuration détaillée
- `app/Services/ChariowService.php` - Code du service

---

## ✅ Prêt à Utiliser !

**Suivez les 3 étapes ci-dessus et testez ! 🚀**

---

**Questions ? Consultez le guide complet : `INTEGRATION_CHARIOW_GUIDE.md`**
