# 🛒 Guide d'Intégration Chariow

## 🎯 Vue d'Ensemble

Au lieu d'utiliser l'API CinetPay, vous pouvez maintenant **rediriger directement vers des liens de paiement Chariow** prédéfinis.

**Avantages :**
- ✅ **Plus simple** : Pas besoin d'API complexe
- ✅ **Pas de SDK** : Juste des redirections
- ✅ **Flexible** : Un lien par formation et par tranche
- ✅ **Compatible** : Fonctionne avec votre boutique Chariow existante

---

## 📁 Fichiers Créés

### **1. Configuration : `config/chariow.php`**
```php
'payment_links' => [
    'Design Graphique' => [
        'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/designgraphique/checkout',
        'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/designgraphique/tranche2/checkout',
    ],
    // ... autres formations
],
```

### **2. Service : `app/Services/ChariowService.php`**
Gère les liens de paiement et les redirections

### **3. Contrôleur Modifié : `PaymentController.php`**
Détecte si Chariow est activé et redirige

---

## 🚀 Configuration

### **Étape 1 : Activer Chariow**

**Fichier `.env` :**
```env
# Activer Chariow (désactive CinetPay)
CHARIOW_ENABLED=true

# URLs de retour (optionnel)
CHARIOW_RETURN_URL="${APP_URL}/evc/payment/chariow/return"
CHARIOW_CANCEL_URL="${APP_URL}/evc/payment/chariow/cancel"
CHARIOW_WEBHOOK_URL="${APP_URL}/evc/payment/chariow/webhook"
```

### **Étape 2 : Configurer les Liens de Paiement**

**Fichier `config/chariow.php` :**

```php
'payment_links' => [
    
    'Design Graphique' => [
        'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/designgraphique/checkout',
        'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/designgraphique/tranche2/checkout',
    ],

    'Community Management' => [
        'tranche_1' => 'https://ecolevirtuelle.mychariow.shop/communitymanagement/checkout',
        'tranche_2' => 'https://ecolevirtuelle.mychariow.shop/communitymanagement/tranche2/checkout',
    ],

    // Ajouter vos autres formations ici
],
```

**⚠️ Important :** Créez ces pages de checkout dans votre boutique Chariow !

---

## 📊 Workflow de Paiement

### **1. Candidat Accepté par Admin**
```
Admin clique "Accepter" 
   → 2 paiements créés (50k + 27k FCFA)
   → Email envoyé avec lien : /evc/payment/EVC-PAY-XXXXXXXX
```

### **2. Candidat Ouvre le Lien**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-20251209-XXXXXX
   → Page de paiement EVC s'affiche
   → Bouton "Procéder au paiement"
```

### **3. Clic sur "Procéder au paiement"**
```
PaymentController@processPayment
   → Détecte CHARIOW_ENABLED=true
   → Génère l'URL Chariow avec paramètres
   → Redirige vers Chariow
```

**Exemple d'URL générée :**
```
https://ecolevirtuelle.mychariow.shop/designgraphique/checkout?
  reference=EVC-PAY-20251209-XXXXXX&
  email=candidat@example.com&
  amount=50000&
  return_url=http://127.0.0.1:8000/evc/payment/chariow/return&
  cancel_url=http://127.0.0.1:8000/evc/payment/chariow/cancel
```

### **4. Paiement sur Chariow**
```
Candidat paie sur votre boutique Chariow
   → Chariow envoie un webhook (si configuré)
   → Ou retour manuel via return_url
```

### **5. Retour après Paiement**
```
Chariow redirige vers return_url
   → PaymentController@chariowReturn
   → Marque le paiement comme "completed"
   → Crée le compte utilisateur
   → Envoie l'email de confirmation
```

---

## 🔗 Routes à Ajouter

**Fichier `routes/web.php` :**

```php
// Routes Chariow
Route::prefix('evc/payment/chariow')->group(function () {
    
    // Retour après paiement réussi
    Route::get('/return', [PaymentController::class, 'chariowReturn'])->name('payment.chariow.return');
    
    // Retour après annulation
    Route::get('/cancel', [PaymentController::class, 'chariowCancel'])->name('payment.chariow.cancel');
    
    // Webhook Chariow (si disponible)
    Route::post('/webhook', [PaymentController::class, 'chariowWebhook'])->name('payment.chariow.webhook');
});
```

---

## 🎨 Méthodes du Contrôleur

Ajoutez ces méthodes à `PaymentController.php` :

### **1. Retour après Paiement Réussi**

```php
/**
 * Retour après paiement Chariow réussi
 */
public function chariowReturn(Request $request)
{
    $reference = $request->input('reference');
    $transactionId = $request->input('transaction_id') ?? 'CHARIOW-' . uniqid();
    
    if (!$reference) {
        return redirect()->route('login')
            ->with('error', 'Référence de paiement manquante');
    }

    Log::info('🛒 Retour Chariow (succès)', [
        'reference' => $reference,
        'transaction_id' => $transactionId
    ]);

    $payment = DB::table('payments')
        ->where('payment_reference', $reference)
        ->first();

    if (!$payment) {
        return redirect()->route('login')
            ->with('error', 'Paiement introuvable');
    }

    // Marquer le paiement comme complété
    DB::table('payments')
        ->where('id', $payment->id)
        ->update([
            'status' => 'completed',
            'transaction_id' => $transactionId,
            'paid_at' => now(),
            'updated_at' => now(),
        ]);

    // Récupérer le candidat
    $candidate = DB::table('pre_registrations')
        ->where('id', $payment->pre_registration_id)
        ->first();

    // Si 1ère tranche : créer le compte
    if ($payment->installment_number == 1) {
        $this->createUserAndSendConfirmation($payment, $candidate);
    }

    // Si 2ème tranche : envoyer email confirmation
    if ($payment->installment_number == 2) {
        $this->sendSecondInstallmentConfirmation($payment, $candidate);
    }

    // Mettre à jour la préinscription
    DB::table('pre_registrations')
        ->where('id', $payment->pre_registration_id)
        ->update([
            'status' => 'paid',
            'updated_at' => now(),
        ]);

    return view('payment.success', compact('payment', 'candidate'));
}

/**
 * Retour après annulation Chariow
 */
public function chariowCancel(Request $request)
{
    $reference = $request->input('reference');
    
    Log::info('🛒 Retour Chariow (annulé)', [
        'reference' => $reference
    ]);

    return redirect()->route('login')
        ->with('error', 'Paiement annulé. Veuillez réessayer.');
}

/**
 * Webhook Chariow (si disponible)
 */
public function chariowWebhook(Request $request)
{
    Log::info('🛒 Webhook Chariow reçu', $request->all());

    $chariow = new ChariowService();
    $result = $chariow->handleWebhook($request->all());

    if (!$result) {
        return response()->json(['error' => 'Webhook invalide'], 400);
    }

    // Mettre à jour le paiement
    $payment = DB::table('payments')
        ->where('payment_reference', $result['reference'])
        ->first();

    if ($payment && $result['success']) {
        DB::table('payments')
            ->where('id', $payment->id)
            ->update([
                'status' => 'completed',
                'transaction_id' => $result['transaction_id'],
                'paid_at' => now(),
                'updated_at' => now(),
            ]);

        // Traiter la création de compte si nécessaire
        $candidate = DB::table('pre_registrations')
            ->where('id', $payment->pre_registration_id)
            ->first();

        if ($payment->installment_number == 1) {
            $this->createUserAndSendConfirmation($payment, $candidate);
        }
    }

    return response()->json(['status' => 'success']);
}

/**
 * Créer l'utilisateur et envoyer l'email de confirmation
 */
private function createUserAndSendConfirmation($payment, $candidate)
{
    // Vérifier si l'utilisateur existe déjà
    $existingUser = DB::table('users')->where('email', $candidate->email)->first();
    $existingStudent = DB::table('students')->where('email', $candidate->email)->first();

    if (!$existingUser && !$existingStudent) {
        // Générer un token
        $timestamp = time();
        $hash = md5($candidate->email . config('app.key'));
        $tokenData = $candidate->email . '|' . $timestamp . '|' . $hash;
        $confirmationToken = base64_encode($tokenData);

        // Mettre à jour le paiement avec le token
        DB::table('payments')
            ->where('id', $payment->id)
            ->update([
                'account_creation_token' => $confirmationToken,
                'updated_at' => now(),
            ]);

        // Créer l'utilisateur
        $userId = DB::table('users')->insertGetId([
            'first_name' => $candidate->prenom,
            'last_name' => $candidate->nom,
            'email' => $candidate->email,
            'phone' => $candidate->whatsapp ?? null,
            'country' => $candidate->pays ?? 'Côte d\'Ivoire',
            'city' => $candidate->ville ?? null,
            'formation_souhaitee' => $candidate->choix_formation ?? null,
            'status' => 'En attente',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Créer le profil étudiant
        $studentId = 'EVC' . date('Y') . str_pad($userId, 4, '0', STR_PAD_LEFT);
        
        DB::table('students')->insert([
            'user_id' => $userId,
            'student_id' => $studentId,
            'first_name' => $candidate->prenom,
            'last_name' => $candidate->nom,
            'email' => $candidate->email,
            'phone' => $candidate->whatsapp ?? null,
            'program' => $candidate->choix_formation ?? 'Community Management',
            'specialization' => strtolower(str_replace(' ', '_', $candidate->choix_formation ?? 'community_management')),
            'level' => 'Débutant',
            'education_level' => 'Licence',
            'status' => 'active',
            'city' => $candidate->ville ?? 'Abidjan',
            'country' => $candidate->pays ?? 'Côte d\'Ivoire',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Log::info('✅ Utilisateur et profil étudiant créés (Chariow)', [
            'user_id' => $userId,
            'student_id' => $studentId,
            'email' => $candidate->email
        ]);

        // Envoyer l'email
        $confirmationUrl = url('/student/confirm-registration/' . $confirmationToken);

        Mail::send('emails.payment_confirmed', [
            'candidate' => $candidate,
            'payment' => $payment,
            'accountCreationUrl' => $confirmationUrl,
        ], function ($message) use ($candidate) {
            $message->to($candidate->email)
                ->subject('Paiement confirmé - Créez votre compte EVC');
        });
    }
}

/**
 * Envoyer email confirmation 2ème tranche
 */
private function sendSecondInstallmentConfirmation($payment, $candidate)
{
    Mail::send('emails.second_payment_confirmation', [
        'candidate' => $candidate,
        'payment' => $payment,
    ], function ($message) use ($candidate) {
        $message->to($candidate->email)
            ->subject('Paiement 2ème tranche confirmé - EVC');
    });

    Log::info('✅ Email confirmation 2ème tranche envoyé (Chariow)', [
        'email' => $candidate->email
    ]);
}
```

---

## 🛠️ Configuration Chariow (Boutique)

### **Dans votre Boutique Chariow**

Pour chaque formation, créez une page de checkout avec ces paramètres :

**Exemple : Design Graphique - 1ère Tranche**

**URL :** `https://ecolevirtuelle.mychariow.shop/designgraphique/checkout`

**Produit Chariow :**
- Nom : Formation Design Graphique - 1ère Tranche
- Prix : 50 000 FCFA
- Description : Première tranche de la formation Design Graphique

**Paramètres URL Reçus :**
- `reference` : Référence du paiement EVC (ex: EVC-PAY-20251209-XXXXXX)
- `email` : Email du candidat
- `amount` : Montant (50000)
- `return_url` : URL de retour après paiement
- `cancel_url` : URL de retour après annulation

**Configuration Chariow :**
1. Créer le produit
2. Configurer l'URL de retour après paiement : `{return_url}?reference={reference}&transaction_id={chariow_transaction_id}`
3. Configurer le webhook (optionnel) : Envoyer à `{CHARIOW_WEBHOOK_URL}`

---

## 🔄 Passer de CinetPay à Chariow

### **Méthode 1 : Basculer Complètement**

**`.env` :**
```env
CHARIOW_ENABLED=true
```

Tous les nouveaux paiements utiliseront Chariow.

### **Méthode 2 : Tester en Parallèle**

**`.env` :**
```env
CHARIOW_ENABLED=false  # CinetPay par défaut
CHARIOW_TEST_MODE=true
```

Dans le code, ajouter une condition pour tester Chariow sur certaines formations seulement.

### **Méthode 3 : Par Formation**

Modifier `config/chariow.php` :

```php
'enabled_formations' => [
    'Design Graphique',
    'Community Management',
],
```

Et dans `PaymentController` :

```php
if (ChariowService::isEnabled() && in_array($candidate->choix_formation, config('chariow.enabled_formations'))) {
    // Utiliser Chariow
} else {
    // Utiliser CinetPay
}
```

---

## 📊 Tableau Récapitulatif

| Aspect | CinetPay (Avant) | Chariow (Maintenant) |
|--------|------------------|----------------------|
| **Configuration** | API Key, Site ID, Secret Key | Liens de paiement prédéfinis |
| **Intégration** | SDK + API REST | Redirection simple |
| **Webhook** | Obligatoire | Optionnel |
| **Vérification** | API checkPaymentStatus | Callback URL |
| **Complexité** | Élevée | Faible |
| **Flexibilité** | Haute (tout customisable) | Moyenne (limité aux produits Chariow) |

---

## ✅ Checklist de Migration

- [ ] Créer les produits dans la boutique Chariow
- [ ] Configurer les URLs de checkout dans `config/chariow.php`
- [ ] Ajouter `CHARIOW_ENABLED=true` dans `.env`
- [ ] Ajouter les routes Chariow dans `routes/web.php`
- [ ] Ajouter les méthodes `chariowReturn`, `chariowCancel`, `chariowWebhook` dans `PaymentController`
- [ ] Tester avec une candidature de test
- [ ] Vérifier la création de compte automatique
- [ ] Vérifier les emails envoyés
- [ ] Tester le paiement 2ème tranche

---

## 🧪 Test Complet

### **1. Activer Chariow**
```bash
# .env
CHARIOW_ENABLED=true
```

### **2. Accepter une Candidature**
```
http://127.0.0.1:8000/evc/app/admin/preinscriptions
```
Cliquer sur "Accepter"

### **3. Ouvrir le Lien de Paiement**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX
```

### **4. Cliquer sur "Procéder au paiement"**
Vérifier que vous êtes redirigé vers :
```
https://ecolevirtuelle.mychariow.shop/designgraphique/checkout?reference=...
```

### **5. Simuler le Retour**
Comme Chariow n'est pas encore configuré, simuler manuellement le retour :
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference=EVC-PAY-XXXXXXXX&transaction_id=CHARIOW-TEST-123
```

### **6. Vérifier**
- [ ] Paiement marqué "completed"
- [ ] Utilisateur créé
- [ ] Profil étudiant créé
- [ ] Email envoyé
- [ ] Dashboard admin mis à jour

---

## 🎯 Résumé

**Avant (CinetPay) :**
```
Candidat → Page paiement EVC → API CinetPay → Widget CinetPay → Paiement
```

**Maintenant (Chariow) :**
```
Candidat → Page paiement EVC → Redirection → Boutique Chariow → Paiement → Retour EVC
```

**Plus simple, plus direct, moins de code ! 🎉**

---

## 📝 Fichiers à Consulter

- `config/chariow.php` - Configuration des liens
- `app/Services/ChariowService.php` - Service Chariow
- `app/Http/Controllers/PaymentController.php` - Gestion des paiements
- `routes/web.php` - Routes de retour Chariow

**Tout est prêt pour utiliser Chariow ! 🛒**
