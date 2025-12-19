# 🛒 Workflow Complet - Paiement Chariow

## 📊 Vue d'Ensemble

Voici exactement ce qui se passe dans **tous les scénarios** de paiement avec Chariow.

---

## ✅ SCÉNARIO 1 : Paiement Réussi

### **1. Étudiant Clique sur "Procéder au paiement sécurisé"**

```
Page : http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX
   ↓
PaymentController@processPayment
   ↓
Redirection vers Chariow :
https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout?reference=EVC-PAY-XXXXXXXX&...
```

### **2. Étudiant Paie sur Chariow**

```
Étudiant entre ses informations de paiement
   ↓
Chariow traite le paiement
   ↓
✅ Paiement accepté
```

### **3. Chariow Redirige vers EVC**

**URL de retour configurée dans Chariow :**
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference=EVC-PAY-XXXXXXXX&transaction_id=CHARIOW-123456
```

### **4. Traitement Automatique par EVC**

**Méthode : `PaymentController@chariowReturn()`**

```php
1. Récupération du paiement via la référence ✅
   
2. Mise à jour du paiement :
   - status → 'completed'
   - transaction_id → 'CHARIOW-123456'
   - paid_at → Date actuelle
   
3. Si 1ère tranche (50 000 FCFA) :
   a. Vérification : utilisateur existe déjà ?
      - NON → Création du compte
      - OUI → Skip
   
   b. Création de l'utilisateur dans table 'users' :
      - first_name, last_name, email
      - phone, country, city
      - status → 'En attente'
   
   c. Création du profil étudiant dans table 'students' :
      - student_id → 'EVC20250005'
      - program → 'Design Graphique'
      - specialization → 'design_graphique'
      - status → 'active'
   
   d. Génération du token de confirmation :
      - Token sécurisé encodé en base64
      - Sauvegardé dans payments.account_creation_token
   
   e. Envoi de l'email avec lien de création de compte :
      - Template : emails.payment_confirmed
      - Lien : /student/confirm-registration/{token}
      - Subject : "✅ Paiement confirmé - Créez votre compte EVC"
   
   f. Redirection automatique vers page de création de mot de passe :
      URL : /student/confirm-registration/{token}
      Message : "✅ Paiement confirmé ! Créez votre mot de passe"

4. Si 2ème tranche (27 000 FCFA) :
   a. Envoi email de confirmation 2ème tranche
   b. Affichage page de succès
   
5. Mise à jour préinscription :
   - status → 'paid'
```

---

## ❌ SCÉNARIO 2 : Paiement Échoué ou Annulé

### **1. Étudiant Annule le Paiement sur Chariow**

```
Étudiant sur la page Chariow
   ↓
Clique sur "Annuler" ou ferme la page
   ↓
Chariow redirige vers URL d'annulation
```

### **2. Chariow Redirige vers EVC (Annulation)**

**URL d'annulation configurée dans Chariow :**
```
http://127.0.0.1:8000/evc/payment/chariow/cancel?reference=EVC-PAY-XXXXXXXX
```

### **3. Traitement Automatique par EVC**

**Méthode : `PaymentController@chariowCancel()`**

```php
1. Récupération du paiement via la référence
   
2. Mise à jour du paiement :
   - status → 'cancelled'
   - updated_at → Date actuelle
   
3. Log de l'annulation pour traçabilité

4. Redirection vers page de connexion :
   Message : "❌ Paiement annulé. Vous pouvez réessayer en utilisant 
             le lien de paiement reçu par email."
```

### **4. Étudiant Peut Réessayer**

```
L'étudiant conserve le lien de paiement original
   ↓
Il peut cliquer à nouveau sur le lien dans son email
   ↓
Réessayer le paiement
```

---

## 🔔 SCÉNARIO 3 : Webhook Chariow (Optionnel)

**Si Chariow envoie des webhooks en plus de la redirection :**

### **URL du Webhook**
```
POST http://127.0.0.1:8000/evc/payment/chariow/webhook
```

### **Données Envoyées par Chariow**
```json
{
  "transaction_id": "CHARIOW-123456",
  "reference": "EVC-PAY-20251210-XXXXXXXX",
  "status": "success",
  "amount": 50000,
  "customer_email": "etudiant@email.com"
}
```

### **Traitement**

**Méthode : `PaymentController@chariowWebhook()`**

```php
1. Réception des données du webhook
2. Validation des données (ChariowService@handleWebhook)
3. Mise à jour du paiement (similaire à chariowReturn)
4. Retour JSON : {"status": "success"}
```

**Avantage :** Double confirmation (webhook + redirection)

---

## 📊 Comparaison des Scénarios

| Scénario | Redirection | Status Paiement | Compte Créé | Email Envoyé | Message |
|----------|-------------|-----------------|-------------|--------------|---------|
| **✅ Paiement Réussi** | `/chariow/return` | `completed` | ✅ Oui (1ère tranche) | ✅ Oui | Succès |
| **❌ Paiement Annulé** | `/chariow/cancel` | `cancelled` | ❌ Non | ❌ Non | Erreur |
| **🔔 Webhook** | Pas de redirection | `completed` | ✅ Oui | ✅ Oui | - |

---

## 🎯 Workflow Détaillé - 1ère Tranche (50 000 FCFA)

```
┌──────────────────────────────────────────────────────────┐
│  1. Admin accepte la candidature                         │
│     → 2 paiements créés (50k + 27k FCFA)                │
│     → Email envoyé avec lien de paiement                │
└──────────────────────────────────────────────────────────┘
                        ↓
┌──────────────────────────────────────────────────────────┐
│  2. Candidat ouvre le lien de paiement                   │
│     http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX  │
└──────────────────────────────────────────────────────────┘
                        ↓
┌──────────────────────────────────────────────────────────┐
│  3. Candidat clique "Procéder au paiement sécurisé"      │
│     → Redirection vers Chariow                          │
└──────────────────────────────────────────────────────────┘
                        ↓
        ┌───────────────┴───────────────┐
        ↓                               ↓
┌───────────────────┐         ┌──────────────────┐
│  ✅ PAIEMENT OK   │         │  ❌ ANNULÉ       │
└───────────────────┘         └──────────────────┘
        ↓                               ↓
┌───────────────────────────┐   ┌──────────────────────────┐
│ Chariow → /chariow/return │   │ Chariow → /chariow/cancel│
└───────────────────────────┘   └──────────────────────────┘
        ↓                               ↓
┌───────────────────────────┐   ┌──────────────────────────┐
│ EVC traite le retour :    │   │ EVC marque annulé        │
│ 1. Paiement → completed   │   │ Message d'erreur         │
│ 2. Créer utilisateur      │   │ Peut réessayer           │
│ 3. Créer profil étudiant  │   └──────────────────────────┘
│ 4. Générer token          │
│ 5. Envoyer email          │
│ 6. Préinscription → paid  │
└───────────────────────────┘
        ↓
┌───────────────────────────┐
│ Redirection automatique : │
│ /student/confirm-         │
│ registration/{token}      │
└───────────────────────────┘
        ↓
┌───────────────────────────┐
│ Étudiant crée son         │
│ mot de passe              │
└───────────────────────────┘
        ↓
┌───────────────────────────┐
│ ✅ COMPTE ACTIVÉ          │
│ Accès à la plateforme     │
└───────────────────────────┘
```

---

## 🎯 Workflow Détaillé - 2ème Tranche (27 000 FCFA)

```
┌──────────────────────────────────────────────────────────┐
│  1. Après 2 mois de formation                            │
│     → Admin envoie email 2ème tranche                   │
└──────────────────────────────────────────────────────────┘
                        ↓
┌──────────────────────────────────────────────────────────┐
│  2. Étudiant reçoit email avec lien de paiement          │
│     http://127.0.0.1:8000/evc/payment/EVC-PAY-XXXXXXXX  │
└──────────────────────────────────────────────────────────┘
                        ↓
┌──────────────────────────────────────────────────────────┐
│  3. Étudiant clique "Procéder au paiement sécurisé"      │
│     → Redirection vers Chariow                          │
└──────────────────────────────────────────────────────────┘
                        ↓
        ┌───────────────┴───────────────┐
        ↓                               ↓
┌───────────────────┐         ┌──────────────────┐
│  ✅ PAIEMENT OK   │         │  ❌ ANNULÉ       │
└───────────────────┘         └──────────────────┘
        ↓                               ↓
┌───────────────────────────┐   ┌──────────────────────────┐
│ Chariow → /chariow/return │   │ Chariow → /chariow/cancel│
└───────────────────────────┘   └──────────────────────────┘
        ↓                               ↓
┌───────────────────────────┐   ┌──────────────────────────┐
│ EVC traite le retour :    │   │ EVC marque annulé        │
│ 1. Paiement → completed   │   │ Peut réessayer           │
│ 2. Envoyer email confirm. │   └──────────────────────────┘
│ 3. Afficher page succès   │
└───────────────────────────┘
        ↓
┌───────────────────────────┐
│ ✅ INSCRIPTION COMPLÈTE   │
│ Total payé : 77 000 FCFA  │
└───────────────────────────┘
```

---

## 📝 Configuration Chariow Requise

### **Dans Votre Boutique Chariow**

Pour que tout fonctionne, configurez ces URLs dans les paramètres de votre produit `prd_ngqtqy` :

#### **1. URL de Retour (Succès)**
```
http://127.0.0.1:8000/evc/payment/chariow/return?reference={reference}&transaction_id={transaction_id}
```

**Variables à passer :**
- `{reference}` → La référence EVC reçue dans l'URL
- `{transaction_id}` → L'ID de transaction Chariow

#### **2. URL d'Annulation (Échec)**
```
http://127.0.0.1:8000/evc/payment/chariow/cancel?reference={reference}
```

**Variable à passer :**
- `{reference}` → La référence EVC reçue dans l'URL

#### **3. URL Webhook (Optionnel)**
```
http://127.0.0.1:8000/evc/payment/chariow/webhook
```

**Méthode :** POST  
**Format :** JSON

---

## 🔍 Logs à Surveiller

### **Paiement Réussi**
```bash
tail -f storage/logs/laravel.log
```

**Logs attendus :**
```
[2025-12-10] local.INFO: 🛒 Retour Chariow (succès) {"reference":"EVC-PAY-XXXXXXXX","transaction_id":"CHARIOW-123456"}
[2025-12-10] local.INFO: ✅ Paiement Chariow marqué comme complété {"payment_id":23,"transaction_id":"CHARIOW-123456"}
[2025-12-10] local.INFO: ✅ Utilisateur et profil étudiant créés (Chariow) {"user_id":5,"student_id":"EVC20250005","email":"etudiant@email.com"}
[2025-12-10] local.INFO: ✅ Email création compte envoyé (Chariow) {"email":"etudiant@email.com","confirmation_url":"http://..."}
```

### **Paiement Annulé**
```
[2025-12-10] local.INFO: 🛒 Retour Chariow (annulé) {"reference":"EVC-PAY-XXXXXXXX"}
[2025-12-10] local.INFO: ℹ️ Paiement marqué comme annulé {"payment_id":23,"reference":"EVC-PAY-XXXXXXXX"}
```

---

## 🐛 Dépannage

### **Problème : Pas de Redirection après Paiement**

**Cause :** URLs de retour non configurées dans Chariow

**Solution :**
1. Aller dans les paramètres du produit Chariow
2. Configurer les URLs de retour et d'annulation
3. Tester à nouveau

### **Problème : Compte Non Créé après Paiement**

**Vérifier les logs :**
```bash
tail -100 storage/logs/laravel.log | grep "Chariow"
```

**Vérifier en DB :**
```sql
SELECT * FROM payments WHERE payment_reference = 'EVC-PAY-XXXXXXXX';
SELECT * FROM users WHERE email = 'etudiant@email.com';
SELECT * FROM students WHERE email = 'etudiant@email.com';
```

### **Problème : Email Non Reçu**

**Vérifier les logs d'envoi :**
```bash
tail -100 storage/logs/laravel.log | grep "Email"
```

**Vérifier la configuration email :**
```bash
php artisan tinker --execute="
echo 'Mail driver: ' . config('mail.default') . PHP_EOL;
echo 'Mail from: ' . config('mail.from.address');
"
```

---

## ✅ Checklist de Test

### **Test Paiement Réussi**
- [ ] Accepter une candidature
- [ ] Ouvrir le lien de paiement
- [ ] Cliquer sur "Procéder au paiement sécurisé"
- [ ] Être redirigé vers Chariow
- [ ] Simuler un paiement réussi sur Chariow
- [ ] Être redirigé vers `/chariow/return`
- [ ] Vérifier que le paiement est marqué `completed` en DB
- [ ] Vérifier que l'utilisateur est créé
- [ ] Vérifier que le profil étudiant est créé
- [ ] Vérifier que l'email est envoyé
- [ ] Vérifier la redirection vers la page de création de mot de passe

### **Test Paiement Annulé**
- [ ] Ouvrir le lien de paiement
- [ ] Cliquer sur "Procéder au paiement sécurisé"
- [ ] Être redirigé vers Chariow
- [ ] Annuler le paiement sur Chariow
- [ ] Être redirigé vers `/chariow/cancel`
- [ ] Vérifier le message d'erreur
- [ ] Vérifier que le paiement est marqué `cancelled` en DB
- [ ] Vérifier qu'aucun compte n'a été créé

---

## 📊 Données en Base de Données

### **Après Paiement Réussi**

**Table `payments` :**
```sql
id: 23
payment_reference: EVC-PAY-20251210-XXXXXXXX
status: completed
transaction_id: CHARIOW-123456
paid_at: 2025-12-10 14:30:00
account_creation_token: {base64_encoded_token}
payment_url: https://ecolevirtuelle.mychariow.shop/prd_ngqtqy/checkout?...
```

**Table `users` :**
```sql
id: 5
first_name: Koffi
last_name: Juliette
email: infos.evc2022@gmail.com
status: En attente
formation_souhaitee: Design Graphique
```

**Table `students` :**
```sql
id: 5
user_id: 5
student_id: EVC20250005
first_name: Koffi
last_name: Juliette
email: infos.evc2022@gmail.com
program: Design Graphique
specialization: design_graphique
status: active
```

**Table `pre_registrations` :**
```sql
status: paid
```

### **Après Paiement Annulé**

**Table `payments` :**
```sql
status: cancelled
updated_at: 2025-12-10 14:35:00
```

**Tables `users` et `students` :**
```
Aucune entrée créée
```

---

## 🎓 Résumé

### **Paiement Réussi = Tout Automatique**
1. ✅ Paiement marqué "completed"
2. ✅ Compte utilisateur créé
3. ✅ Profil étudiant créé avec `status = active`
4. ✅ Email de bienvenue envoyé
5. ✅ Redirection vers création de mot de passe
6. ✅ Visible dans dashboard admin

### **Paiement Annulé = Rien de Créé**
1. ❌ Paiement marqué "cancelled"
2. ❌ Aucun compte créé
3. ❌ Message d'erreur affiché
4. ✅ Peut réessayer avec le même lien

---

**Tout est maintenant automatisé ! 🎉**

**Documentation complète créée ! 📚**
