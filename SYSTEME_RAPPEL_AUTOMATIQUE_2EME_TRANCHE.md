# ✅ Système Automatique : Rappel 2ème Tranche Après 2 Mois

## 🎯 Vue d'Ensemble

**Système automatisé** qui envoie un email de rappel **2 mois après** le paiement de la 1ère tranche avec **menace de désactivation** du compte si non paiement dans les 7 jours.

---

## 🚀 Workflow Automatique Complet

```
Jour 0 : Paiement 1ère tranche (50 000 FCFA)
   ↓
⏳ 60 jours (2 mois) de formation
   ↓
Jour 60 : 🤖 Email automatique envoyé
   "⚠️ URGENT : Finalisez votre paiement - Risque de désactivation"
   ↓
⏳ 7 jours de délai supplémentaire
   ↓
Jour 67 : 🤖 Désactivation automatique du compte si non payé
   ↓
❌ Compte désactivé + Paiement marqué "expired"
```

---

## 📧 Email de Rappel Automatique

### **Contenu**
- ⚠️ **Encadré rouge** : "RAPPEL IMPORTANT"
- **Message clair** : "2 mois écoulés depuis le 1er paiement"
- **Délai restant** : "7 jours pour payer"
- **Menace** : "Sinon votre compte sera automatiquement désactivé"
- **Montant** : 27 000 FCFA (27 000 FCFA en production)
- **Lien de paiement** : Bouton CTA

### **Fichiers**
- **Mailable** : `app/Mail/SecondInstallmentReminderAutomatic.php`
- **Template** : `resources/views/emails/second_installment_reminder_auto.blade.php`

---

## 🤖 Commandes Laravel

### **1. Envoi Automatique des Rappels**

#### **Commande**
```bash
php artisan payments:send-second-installment-reminders
```

#### **Fonctionnement**
- ✅ Recherche tous les paiements 1ère tranche payés **il y a exactement 60 jours**
- ✅ Vérifie que la 2ème tranche n'est **pas encore payée**
- ✅ Vérifie qu'un rappel **n'a pas déjà été envoyé**
- ✅ Envoie l'email de rappel avec menace
- ✅ Enregistre dans `second_installment_reminders` (évite les doublons)

#### **Mode Dry-Run (Test)**
```bash
php artisan payments:send-second-installment-reminders --dry-run
```
Affiche ce qui serait envoyé **sans envoyer réellement**.

#### **Fichier**
`app/Console/Commands/SendSecondInstallmentReminders.php`

---

### **2. Désactivation Automatique des Comptes**

#### **Commande**
```bash
php artisan accounts:deactivate-unpaid
```

#### **Fonctionnement**
- ✅ Recherche les rappels envoyés **il y a 7 jours ou plus**
- ✅ Vérifie que la 2ème tranche **n'est toujours pas payée**
- ✅ **Désactive le compte utilisateur** (`is_active = 0`)
- ✅ Marque le paiement comme **`expired`**
- ✅ Log l'action dans `storage/logs/laravel.log`

#### **Mode Dry-Run (Test)**
```bash
php artisan accounts:deactivate-unpaid --dry-run
```

#### **Fichier**
`app/Console/Commands/DeactivateUnpaidAccounts.php`

---

## 📊 Base de Données

### **Nouvelle Table : `second_installment_reminders`**

```sql
CREATE TABLE second_installment_reminders (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    payment_id BIGINT,              -- ID du paiement 1ère tranche
    candidate_email VARCHAR(255),
    sent_at TIMESTAMP,              -- Date d'envoi du rappel
    days_remaining INT DEFAULT 7,   -- Jours avant désactivation
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    INDEX (payment_id),
    INDEX (candidate_email),
    INDEX (sent_at)
);
```

### **But**
- ✅ Éviter d'envoyer plusieurs fois le même rappel
- ✅ Tracker quand les rappels ont été envoyés
- ✅ Permettre la désactivation automatique après délai

### **Migration**
```bash
database/migrations/2025_12_09_114631_create_second_installment_reminders_table.php
```

---

## ⚙️ Configuration Automatique (Cron)

### **Pour Exécuter Automatiquement Tous les Jours**

#### **1. Ouvrir le crontab**
```bash
crontab -e
```

#### **2. Ajouter ces lignes**
```bash
# Envoi des rappels 2ème tranche (tous les jours à 9h00)
0 9 * * * cd /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC && php artisan payments:send-second-installment-reminders >> /dev/null 2>&1

# Désactivation des comptes non payés (tous les jours à 10h00)
0 10 * * * cd /Applications/XAMPP/xamppfiles/htdocs/web/evc2024/V4_EVC && php artisan accounts:deactivate-unpaid >> /dev/null 2>&1
```

#### **3. Sauvegarder et quitter**
- **Linux/Mac** : `:wq` puis Enter
- **Windows** : Utiliser Task Scheduler

#### **Vérifier le cron**
```bash
crontab -l
```

---

## 🧪 Test Manuel

### **Scénario de Test**

#### **Étape 1 : Créer un test payment**
```bash
# Simuler un paiement 1ère tranche il y a 60 jours
php artisan tinker

DB::table('payments')->insert([
    'pre_registration_id' => 1,
    'amount' => 50000,
    'currency' => 'XOF',
    'payment_reference' => 'TEST-PAY-60DAYS',
    'status' => 'completed',
    'paid_at' => now()->subDays(60),
    'payment_type' => 'installment',
    'installment_number' => 1,
    'total_installments' => 2,
    'total_amount' => 77000,
    'created_at' => now(),
    'updated_at' => now()
]);
exit
```

#### **Étape 2 : Tester l'envoi de rappel (dry-run)**
```bash
php artisan payments:send-second-installment-reminders --dry-run
```

**Résultat attendu** :
```
🔍 Recherche des paiements 1ère tranche ayant 2 mois...
📧 Trouvé 1 paiement(s) à traiter.
🔍 [DRY RUN] Email serait envoyé à: test@example.com (John Doe)

📊 Résumé:
✅ Emails envoyés: 1
```

#### **Étape 3 : Envoyer réellement**
```bash
php artisan payments:send-second-installment-reminders
```

#### **Étape 4 : Vérifier la base de données**
```sql
SELECT * FROM second_installment_reminders ORDER BY sent_at DESC LIMIT 5;
```

#### **Étape 5 : Tester la désactivation (après 7 jours)**
```bash
# Modifier manuellement la date du rappel
UPDATE second_installment_reminders SET sent_at = DATE_SUB(NOW(), INTERVAL 8 DAY) WHERE id = 1;

# Tester
php artisan accounts:deactivate-unpaid --dry-run
```

---

## 📝 Logs et Monitoring

### **Vérifier les logs**
```bash
tail -f storage/logs/laravel.log | grep -i "rappel\|désactivation"
```

### **Logs créés**
- ✅ `Rappel 2ème tranche envoyé automatiquement`
- ✅ `Compte désactivé pour non paiement 2ème tranche`
- ❌ `Erreur envoi rappel 2ème tranche`
- ❌ `Erreur désactivation compte`

---

## 🎯 Vérifications Importantes

### **Avant de passer en production**

1. ✅ **Tester avec `--dry-run`** d'abord
2. ✅ **Vérifier la configuration email** (SMTP)
3. ✅ **Configurer les crons** correctement
4. ✅ **Surveiller les logs** les premiers jours
5. ✅ **Tester le processus de réactivation** manuelle

### **Processus de Réactivation Manuelle**
```sql
-- Réactiver un compte désactivé par erreur
UPDATE users SET is_active = 1, deactivated_at = NULL, deactivation_reason = NULL WHERE email = 'student@example.com';

-- Remettre le paiement en pending
UPDATE payments SET status = 'pending' WHERE payment_reference = 'EVC-PAY-XXX';
```

---

## 📂 Fichiers Créés/Modifiés

### **Nouveaux Fichiers**
1. ✅ `app/Mail/SecondInstallmentReminderAutomatic.php`
2. ✅ `resources/views/emails/second_installment_reminder_auto.blade.php`
3. ✅ `app/Console/Commands/SendSecondInstallmentReminders.php`
4. ✅ `app/Console/Commands/DeactivateUnpaidAccounts.php`
5. ✅ `database/migrations/2025_12_09_114631_create_second_installment_reminders_table.php`

### **Fichiers Modifiés**
1. ✅ `routes/web.php` - Routes accept/reject ajoutées
2. ✅ `resources/views/admin/preregistrations/index.blade.php` - Boutons ajoutés

---

## ⚠️ Points Importants

### **1. Délais**
- **60 jours** (2 mois) après 1er paiement → Email de rappel
- **7 jours** après rappel → Désactivation automatique
- **Total : 67 jours** depuis le 1er paiement

### **2. Sécurité**
- ✅ Vérifications multiples avant envoi
- ✅ Évite les doublons (table `second_installment_reminders`)
- ✅ Logs détaillés de toutes les actions

### **3. Réversibilité**
- ✅ Les comptes désactivés peuvent être **réactivés manuellement**
- ✅ Les paiements expirés peuvent être **remis en pending**

---

## 🔄 Workflow Complet (Résumé)

| Jour | Action | Automatique ? |
|------|--------|---------------|
| **0** | Paiement 1ère tranche | Manuel (Candidat) |
| **1-59** | Formation en cours | - |
| **60** | 📧 Email rappel + menace | ✅ Automatique (Cron) |
| **61-66** | Délai de grâce | - |
| **67** | ❌ Désactivation compte | ✅ Automatique (Cron) |

---

## 🎓 Exemple Réel

### **Timeline**
```
01/01/2025 : Candidat paie 1ère tranche (50 000 FCFA)
01/01 - 28/02 : Formation (2 mois)
01/03/2025 : 🤖 Email rappel envoyé automatiquement
             "⚠️ 7 jours pour payer sinon désactivation"
01/03 - 07/03 : Délai de paiement
08/03/2025 : 🤖 Compte désactivé automatiquement
             Paiement marqué "expired"
```

---

## ✅ **Système Prêt !**

**Le système automatique est maintenant opérationnel :**

✅ **Email de rappel** envoyé automatiquement après 2 mois
✅ **Menace de désactivation** claire dans l'email  
✅ **Désactivation automatique** après 7 jours de délai
✅ **Traçabilité** complète (logs + table dédiée)
✅ **Crons configurables** pour exécution quotidienne
✅ **Modes dry-run** pour tests sans risque

**Configurez les crons et c'est parti ! 🚀**
