# Guide de Mise à Jour des Coordonnées EVC

## 📋 Nouvelles coordonnées officielles (12 Décembre 2024)

```
📞 Téléphone : (+225) 07 17 25 86 02
📍 Adresse : Abidjan, Palmeraie
🌐 Site web : www.ecolevirtuelledescreatifs.com
📧 Email principal : info@ecolevirtuelledescreatifs.com
📧 Email secondaire : contact@ecolevirtuelledescreatifs.com
📱 WhatsApp : +225 07 47 25 95 07
```

---

## ✅ Fichiers déjà mis à jour

### Templates d'emails (6 fichiers)
1. ✅ `resources/views/emails/programme_published.blade.php`
2. ✅ `resources/views/emails/payment_reminder.blade.php`
3. ✅ `resources/views/emails/payment_confirmed.blade.php`
4. ✅ `resources/views/emails/candidature_acceptee.blade.php`

### Dashboards étudiants (2 fichiers)
5. ✅ `resources/views/dashboard/design-graphique.blade.php`
6. ✅ `resources/views/dashboard/community-management.blade.php`

### Pages publiques (1 fichier)
7. ✅ `resources/views/homepage/_footer.blade.php` (déjà à jour)

---

## 🔄 Fichiers restants à mettre à jour (40 fichiers)

### 📧 Emails prioritaires (31 fichiers)
- `resources/views/emails/pre_registration_submitted.blade.php`
- `resources/views/emails/admin_pre_registration_notification.blade.php`
- `resources/views/emails/account-deactivated.blade.php`
- `resources/views/emails/account-reactivated.blade.php`
- `resources/views/emails/password-reset.blade.php`
- `resources/views/emails/welcome-student.blade.php`
- `resources/views/emails/tp_assigned.blade.php`
- `resources/views/emails/tp_validated.blade.php`
- `resources/views/emails/tp_rejected.blade.php`
- `resources/views/emails/second_installment_reminder.blade.php`
- `resources/views/emails/second_installment_reminder_auto.blade.php`
- `resources/views/emails/admin-account-created.blade.php`
- `resources/views/emails/admin-new-project-notification.blade.php`
- `resources/views/emails/admin-new-tp-notification.blade.php`
- `resources/views/emails/admin-notification.blade.php`
- `resources/views/emails/admission_approved_registration_link.blade.php`
- `resources/views/emails/collaborateur-candidature.blade.php`
- `resources/views/emails/communique_notification.blade.php`
- `resources/views/emails/design-project-deleted.blade.php`
- `resources/views/emails/design-project-validated.blade.php`
- `resources/views/emails/formateur-candidature.blade.php`
- `resources/views/emails/partenaire-demande.blade.php`
- `resources/views/emails/project-deleted.blade.php`
- `resources/views/emails/project-validated.blade.php`
- `resources/views/emails/student-report-status-notification.blade.php`
- `resources/views/emails/student_completed_registration.blade.php`
- `resources/views/emails/tp-assignment-notification.blade.php`
- `resources/views/emails/tp-rejected.blade.php`
- `resources/views/emails/tp-submission-notification.blade.php`
- `resources/views/emails/tp-validation-notification.blade.php`
- `resources/views/emails/webtv_broadcast.blade.php`
- `resources/views/emails/webtv_live_notification.blade.php`

### 📄 Pages légales (2 fichiers)
- `resources/views/legal/mentions-legales.blade.php`
- `resources/views/legal/politique-confidentialite.blade.php`

### 👤 Profils et paramètres (4 fichiers)
- `resources/views/parametres/index.blade.php`
- `resources/views/profile/edit.blade.php`
- `resources/views/cvtheque/mon-profil.blade.php`
- `resources/views/dashboard/profil/editer.blade.php`

### 🎯 Pages publiques (3 fichiers)
- `resources/views/homepage/_faq.blade.php`
- `resources/views/laureats.blade.php`
- `resources/views/preinscription/index.blade.php`

---

## 🛠️ Méthodes de mise à jour

### Option 1 : Script automatique (RECOMMANDÉ)

Un script bash `update_coordonnees.sh` a été créé pour automatiser les remplacements.

**⚠️ ATTENTION :** Faites une sauvegarde avant d'exécuter !

```bash
# 1. Sauvegarder le projet
cp -r resources/views resources/views_backup_$(date +%Y%m%d)

# 2. Rendre le script exécutable
chmod +x update_coordonnees.sh

# 3. Exécuter le script
./update_coordonnees.sh

# 4. Vérifier les changements
git diff resources/views
```

### Option 2 : Recherche/Remplacement manuel

Utilisez votre éditeur de code pour remplacer globalement :

**Anciennes valeurs → Nouvelles valeurs :**
- `contact@ecolevirtuelle.ci` → `info@ecolevirtuelledescreatifs.com`
- `contact@evc.ci` → `info@ecolevirtuelledescreatifs.com`
- `www.ecolevirtuelle.ci` → `www.ecolevirtuelledescreatifs.com`
- `www.evc.ci` → `www.ecolevirtuelledescreatifs.com`
- `Abidjan, Côte d'Ivoire` → `Abidjan, Palmeraie`
- `+225 XX XX XX XX XX` → `+225 07 17 25 86 02`

### Option 3 : Commandes sed (Linux/Mac)

```bash
cd resources/views

# Remplacer les emails
find . -name "*.blade.php" -exec sed -i '' 's/contact@ecolevirtuelle\.ci/info@ecolevirtuelledescreatifs.com/g' {} +
find . -name "*.blade.php" -exec sed -i '' 's/contact@evc\.ci/info@ecolevirtuelledescreatifs.com/g' {} +

# Remplacer les sites web
find . -name "*.blade.php" -exec sed -i '' 's/www\.ecolevirtuelle\.ci/www.ecolevirtuelledescreatifs.com/g' {} +
find . -name "*.blade.php" -exec sed -i '' 's/www\.evc\.ci/www.ecolevirtuelledescreatifs.com/g' {} +

# Remplacer les adresses
find . -name "*.blade.php" -exec sed -i '' 's/Abidjan, Côte d'\''Ivoire/Abidjan, Palmeraie/g' {} +
```

---

## 📊 Statistiques

- **Total de fichiers identifiés :** 46
- **Fichiers mis à jour :** 7
- **Fichiers restants :** 39
- **Total d'occurrences :** ~137
- **Progression :** 15%

---

## ✅ Checklist de vérification

Après la mise à jour, vérifier :

### Emails
- [ ] Tous les footers d'emails ont les nouvelles coordonnées
- [ ] Les liens mailto utilisent `info@ecolevirtuelledescreatifs.com`
- [ ] Les numéros WhatsApp sont `+225 07 47 25 95 07`

### Pages publiques
- [ ] Footer du site (homepage)
- [ ] Pages légales (mentions légales, politique de confidentialité)
- [ ] Page FAQ
- [ ] Page contact

### Dashboards
- [ ] Dashboards étudiants (Design Graphique, CM, etc.)
- [ ] Pages de profil
- [ ] Pages de paramètres

### Configuration
- [ ] Fichier `.env` → `MAIL_FROM_ADDRESS=info@ecolevirtuelledescreatifs.com`
- [ ] Fichier `.env` → `MAIL_FROM_NAME="École Virtuelle des Créatifs"`

---

## 🎯 Template standard de footer pour emails

```html
<div class="footer">
    <p><strong>École Virtuelle des Créatifs (EVC)</strong></p>
    <p>
        📞 (+225) 07 17 25 86 02<br>
        📍 Abidjan, Palmeraie<br>
        📧 <a href="mailto:info@ecolevirtuelledescreatifs.com">info@ecolevirtuelledescreatifs.com</a> | 
        <a href="mailto:contact@ecolevirtuelledescreatifs.com">contact@ecolevirtuelledescreatifs.com</a><br>
        🌐 <a href="https://www.ecolevirtuelledescreatifs.com">www.ecolevirtuelledescreatifs.com</a><br>
        📱 WhatsApp : +225 07 47 25 95 07
    </p>
</div>
```

---

## 📝 Notes importantes

1. **Double email :** Conserver les deux emails (`info@` et `contact@`) pour la transition
2. **WhatsApp :** Nouveau numéro dédié : `+225 07 47 25 95 07`
3. **Téléphone principal :** `(+225) 07 17 25 86 02`
4. **Adresse :** Simplifier en "Abidjan, Palmeraie" (au lieu de Cocody Riviera Palmeraie)
5. **Site web :** Toujours utiliser la version complète avec `https://www.`

---

## 🚀 Prochaines étapes recommandées

1. ✅ Exécuter le script de mise à jour automatique
2. ✅ Vérifier visuellement 5-10 fichiers critiques
3. ✅ Tester l'envoi d'un email de test
4. ✅ Vérifier les pages publiques dans le navigateur
5. ✅ Mettre à jour le fichier `.env`
6. ✅ Commit Git avec message : "chore: mise à jour coordonnées EVC (téléphone, emails, adresse)"

---

**Date de création :** 12 Décembre 2024
**Statut :** En cours (15% complété manuellement)
