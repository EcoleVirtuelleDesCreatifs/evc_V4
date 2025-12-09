# 📧 Système de Notifications par Email

Ce document explique comment utiliser le système de notifications par email pour les administrateurs.

## 🎯 Vue d'ensemble

Le système envoie automatiquement des emails aux administrateurs selon leurs préférences configurées dans `Paramètres > Notifications`.

## 📋 Types de notifications disponibles

1. **Nouvelles inscriptions** (`new_registrations`)
2. **Nouveaux paiements** (`new_payments`)
3. **Documents soumis** (`documents_submitted`)
4. **Projets terminés** (`projects_completed`)
5. **Alertes système** (`system_alerts`)
6. **Sauvegardes** (`backups`)
7. **Rapports hebdomadaires** (`weekly_reports`)
8. **Activités d'équipe** (`team_activities`)

## 🔧 Utilisation

### Méthode 1 : Utiliser les helpers

```php
use App\Services\AdminNotificationService;

// Nouvelle inscription
AdminNotificationService::newRegistration([
    'name' => 'Jean Dupont',
    'email' => 'jean@example.com',
    'formation' => 'Design Graphique',
    'date' => now()->format('d/m/Y à H:i')
]);

// Nouveau paiement
AdminNotificationService::newPayment([
    'student_name' => 'Jean Dupont',
    'amount' => '150000',
    'type' => 'Inscription',
    'date' => now()->format('d/m/Y à H:i')
]);

// Document soumis
AdminNotificationService::documentSubmitted([
    'student_name' => 'Jean Dupont',
    'type' => 'CV',
    'filename' => 'cv_jean_dupont.pdf',
    'date' => now()->format('d/m/Y à H:i')
]);

// Projet terminé
AdminNotificationService::projectCompleted([
    'student_name' => 'Jean Dupont',
    'title' => 'Création de logo',
    'date' => now()->format('d/m/Y à H:i')
]);

// Alerte système
AdminNotificationService::systemAlert('Le disque dur est presque plein (90%)');

// Sauvegarde
AdminNotificationService::backupCompleted([
    'size' => '250 MB',
    'date' => now()->format('d/m/Y à H:i')
]);

// Rapport hebdomadaire
AdminNotificationService::weeklyReport([
    'registrations' => 15,
    'payments' => 10,
    'documents' => 25,
    'projects' => 8
]);

// Activité d'équipe
AdminNotificationService::teamActivity([
    'name' => 'Marie Admin',
    'action' => 'A modifié une formation',
    'date' => now()->format('d/m/Y à H:i')
]);
```

### Méthode 2 : Utiliser la méthode générique

```php
use App\Services\AdminNotificationService;

AdminNotificationService::send(
    'new_registration',  // Type
    'Nouvelle inscription',  // Sujet
    [  // Données
        'student' => [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'formation' => 'Design Graphique'
        ]
    ]
);
```

## 📝 Exemples d'intégration

### Dans un contrôleur d'inscription

```php
use App\Services\AdminNotificationService;

public function register(Request $request)
{
    // ... logique d'inscription ...
    
    $student = Student::create($validatedData);
    
    // Envoyer la notification
    AdminNotificationService::newRegistration([
        'name' => $student->first_name . ' ' . $student->last_name,
        'email' => $student->email,
        'formation' => $student->formation->name ?? 'N/A',
        'date' => $student->created_at->format('d/m/Y à H:i')
    ]);
    
    return redirect()->back()->with('success', 'Inscription réussie');
}
```

### Dans un contrôleur de paiement

```php
use App\Services\AdminNotificationService;

public function storePayment(Request $request)
{
    // ... logique de paiement ...
    
    $payment = Payment::create($validatedData);
    
    // Envoyer la notification
    AdminNotificationService::newPayment([
        'student_name' => $payment->student->name,
        'amount' => number_format($payment->amount, 0, ',', ' '),
        'type' => $payment->type,
        'date' => $payment->created_at->format('d/m/Y à H:i')
    ]);
    
    return redirect()->back()->with('success', 'Paiement enregistré');
}
```

### Dans un upload de document

```php
use App\Services\AdminNotificationService;

public function uploadDocument(Request $request)
{
    // ... logique d'upload ...
    
    $document = Document::create($documentData);
    
    // Envoyer la notification
    AdminNotificationService::documentSubmitted([
        'student_name' => auth()->user()->name,
        'type' => $request->document_type,
        'filename' => $document->filename,
        'date' => now()->format('d/m/Y à H:i')
    ]);
    
    return redirect()->back()->with('success', 'Document uploadé');
}
```

### Dans une commande planifiée (Rapport hebdomadaire)

```php
// app/Console/Commands/SendWeeklyReport.php
use App\Services\AdminNotificationService;

public function handle()
{
    $stats = [
        'registrations' => Student::whereBetween('created_at', [now()->subWeek(), now()])->count(),
        'payments' => Payment::whereBetween('created_at', [now()->subWeek(), now()])->count(),
        'documents' => Document::whereBetween('created_at', [now()->subWeek(), now()])->count(),
        'projects' => Project::where('status', 'completed')
                            ->whereBetween('updated_at', [now()->subWeek(), now()])
                            ->count()
    ];
    
    AdminNotificationService::weeklyReport($stats);
    
    $this->info('Rapport hebdomadaire envoyé !');
}
```

## ⚙️ Configuration

### 1. Configurer l'email dans `.env`

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

### 2. Gérer les préférences

Les administrateurs peuvent activer/désactiver chaque type de notification dans :
`http://127.0.0.1:8000/evc/app/admin/parametres/notifications`

## 🔍 Logs

Toutes les notifications sont logguées dans `storage/logs/laravel.log` :

```
[2024-11-30 19:00:00] INFO: Notification envoyée 
{
    "type": "new_registration",
    "admins_notified": 3
}
```

## 🎨 Personnalisation

### Modifier le template d'email

Le template se trouve dans :
`resources/views/emails/admin-notification.blade.php`

### Ajouter un nouveau type de notification

1. Ajoutez le type dans `AdminNotificationService::send()` dans le `$preferenceMap`
2. Ajoutez une méthode helper dans `AdminNotificationService`
3. Ajoutez la section dans le template `admin-notification.blade.php`
4. Ajoutez le toggle dans la page des paramètres de notifications

## 🚀 Tester l'envoi

### Test rapide dans tinker

```bash
php artisan tinker
```

```php
use App\Services\AdminNotificationService;

AdminNotificationService::newRegistration([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'formation' => 'Test Formation',
    'date' => now()->format('d/m/Y à H:i')
]);
```

## 📊 Statistiques

Pour voir combien d'admins ont été notifiés, la méthode retourne le nombre :

```php
$count = AdminNotificationService::newPayment($data);
echo "✅ $count administrateurs notifiés";
```

## 🛡️ Sécurité

- Les emails ne sont envoyés qu'aux admins actifs (`is_active = true`)
- Vérification des préférences avant chaque envoi
- Logs détaillés pour le suivi
- Gestion des erreurs avec try-catch

---

**Note** : Assurez-vous que votre configuration email est correcte avant d'utiliser ce système.
