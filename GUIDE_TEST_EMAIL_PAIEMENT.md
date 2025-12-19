# 📧 Guide - Test Email de Paiement

## 🔍 Pourquoi l'email n'a pas été envoyé ?

**Raison :** Le profil étudiant existait déjà dans la base de données.

**Logique actuelle :** L'email est envoyé **uniquement** si :
- L'utilisateur n'existe pas **OU**
- Le profil étudiant n'existe pas

Si les deux existent déjà → **Pas d'email** (pour éviter les doublons).

---

## ✅ Solution Appliquée

Le profil étudiant a été **supprimé** de la base de données.

```sql
DELETE FROM students WHERE email = 'infos.evc2022@gmail.com';
```

---

## 🧪 Comment Tester Maintenant

### **1. Ouvrir la page de paiement :**
```
http://127.0.0.1:8000/evc/payment/EVC-PAY-20251210-5AE352CB
```

### **2. Cliquer sur "⚡ Simuler Paiement Réussi (TEST)"**

### **3. Résultat Attendu :**

✅ **Paiement marqué "completed"**  
✅ **Profil étudiant créé** (EVC20250029)  
✅ **Email ENVOYÉ** à infos.evc2022@gmail.com  
✅ **Redirection vers création de mot de passe**

---

## 📧 Vérifier l'Email

### **Option 1 : Vérifier les logs**

```bash
tail -f storage/logs/laravel.log | grep Email
```

**Log attendu :**
```
✅ Email création compte envoyé {"email":"infos.evc2022@gmail.com"}
```

### **Option 2 : Vérifier la configuration email**

```bash
php artisan tinker --execute="
echo 'Mail Driver: ' . config('mail.default') . PHP_EOL;
echo 'Mail From: ' . config('mail.from.address') . PHP_EOL;
echo 'SMTP Host: ' . config('mail.mailers.smtp.host') . PHP_EOL;
"
```

---

## ⚙️ Configuration Email (.env)

Pour que les emails soient **réellement envoyés**, vérifiez votre fichier `.env` :

### **Option 1 : SMTP Gmail**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=votre-email@gmail.com
MAIL_FROM_NAME="École Virtuelle des Créatifs"
```

### **Option 2 : Mailtrap (Test)**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=votre-username-mailtrap
MAIL_PASSWORD=votre-password-mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=test@evc.ci
MAIL_FROM_NAME="École Virtuelle des Créatifs"
```

### **Option 3 : Log (Développement)**

```env
MAIL_MAILER=log
```

Emails sauvegardés dans `storage/logs/laravel.log`

---

## 🔄 Workflow Test Complet

```
1. Profil supprimé ✅
   ↓
2. Clic sur "Simuler Paiement Réussi" ✅
   ↓
3. Paiement traité ✅
   ↓
4. Utilisateur existe → réutilisé ✅
   ↓
5. Profil n'existe pas → CRÉÉ ✅
   ↓
6. Condition email : (!existingUser || !existingStudent)
   → TRUE (profil n'existait pas) ✅
   ↓
7. Token généré ✅
   ↓
8. EMAIL ENVOYÉ ✅
   ↓
9. Redirection vers création mot de passe ✅
```

---

## 📊 Logs Détaillés Attendus

```bash
tail -f storage/logs/laravel.log
```

**Logs complets :**
```
[2025-12-10] local.INFO: 🧪 TEST - Paiement simulé comme réussi
[2025-12-10] local.INFO: 🧪 TEST - Envoi email création compte (1ère tranche)
[2025-12-10] local.INFO: ℹ️ Utilisateur existe déjà {"user_id":29}
[2025-12-10] local.INFO: ✅ Profil étudiant créé {"student_id":"EVC20250029"}
[2025-12-10] local.INFO: ✅ Email création compte envoyé {"email":"infos.evc2022@gmail.com","confirmation_url":"http://..."}
[2025-12-10] local.INFO: ✅ Redirection vers création de compte
```

---

## 🔧 Si l'Email n'Arrive Toujours Pas

### **1. Vérifier si l'email est dans les logs**

```bash
grep -r "infos.evc2022@gmail.com" storage/logs/laravel.log | tail -5
```

### **2. Vérifier la configuration SMTP**

```bash
php artisan config:clear
php artisan tinker --execute="
try {
    Mail::raw('Test email', function (\$message) {
        \$message->to('infos.evc2022@gmail.com')
                ->subject('Test Email EVC');
    });
    echo '✅ Email de test envoyé !';
} catch (Exception \$e) {
    echo '❌ Erreur : ' . \$e->getMessage();
}
"
```

### **3. Vérifier les spams**

L'email peut être dans les **spams** ou **courrier indésirable**.

---

## 🎯 Pour les Prochains Tests

### **Si vous voulez re-tester :**

1. **Supprimer le profil étudiant :**
```bash
php artisan tinker --execute="
DB::table('students')->where('email', 'infos.evc2022@gmail.com')->delete();
echo 'Profil supprimé, vous pouvez re-tester !';
"
```

2. **Ou utiliser un autre email** dans une nouvelle préinscription.

---

## 💡 Amélioration Suggérée (Optionnelle)

Pour **toujours envoyer l'email en mode TEST**, même si le compte existe :

**Modifier la condition ligne 725 dans `PaymentController.php` :**

```php
// ❌ Logique actuelle
if (!$existingUser || !$existingStudent) {
    // Envoyer email
}

// ✅ Logique améliorée pour TEST
$isNewAccount = (!$existingUser || !$existingStudent);
$isTestMode = str_starts_with($transactionId, 'TEST-');

if ($isNewAccount || $isTestMode) {
    // Toujours envoyer email en mode TEST
    if (!$isNewAccount) {
        Log::info('📧 Mode TEST : Email renvoyé même si compte existe');
    }
    // Envoyer email...
}
```

---

## 📧 Contenu de l'Email Envoyé

**Sujet :** 🧪 TEST - Paiement confirmé - Créez votre compte EVC

**Contenu :**
- ✅ Confirmation du paiement de 50 000 XOF
- ✅ Formation : design_graphique
- ✅ Lien de création de compte
- ✅ Instructions pour créer le mot de passe
- ✅ Détails du paiement (référence, montant)

**Lien inclus :**
```
http://127.0.0.1:8000/student/confirm-registration/{TOKEN}
```

---

## ✅ Checklist Test Email

- [x] Profil étudiant supprimé
- [ ] Cliquer sur "Simuler Paiement Réussi"
- [ ] Vérifier les logs : "Email création compte envoyé"
- [ ] Vérifier la boîte email (et spams)
- [ ] Cliquer sur le lien dans l'email
- [ ] Créer le mot de passe
- [ ] Se connecter avec le nouveau compte

---

## 🎉 Résumé

### **Problème Identifié :**
Le profil étudiant existait déjà → Email non envoyé

### **Solution Appliquée :**
Profil supprimé → Email sera envoyé au prochain test

### **Action à Faire :**
Cliquer sur "Simuler Paiement Réussi" maintenant !

---

**📧 L'email sera envoyé cette fois ! 🚀**
