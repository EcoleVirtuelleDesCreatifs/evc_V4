# 💳 Guide d'Implémentation - Paiement par Tranche

## 🎯 Objectif

Permettre aux candidats de payer leur formation en 2 tranches :
- **1ère tranche** : 50 000 FCFA (au moment de l'inscription)
- **2ème tranche** : 27 000 FCFA (ultérieurement)
- **Total** : 77 000 FCFA

---

## ✅ Étape 1 : Base de Données (DÉJÀ FAIT)

La migration a déjà été créée et exécutée. Nouveaux champs dans `payments` :

```sql
payment_type          ENUM('full', 'installment')  -- Type de paiement
installment_number    INT NULL                      -- Numéro de tranche (1 ou 2)
total_installments    INT NULL                      -- Total de tranches (toujours 2)
total_amount          DECIMAL(10,2) NULL            -- Montant total
parent_payment_id     BIGINT NULL                   -- Lien vers paiement parent
```

---

## 📝 Étape 2 : Modifier PreRegistrationAdminController

### Fichier : `app/Http/Controllers/Admin/PreRegistrationAdminController.php`

Modifier la méthode `acceptCandidate` :

```php
public function acceptCandidate(Request $request, $id)
{
    try {
        $pre = PreRegistration::findOrFail($id);

        if ($pre->status === 'accepted') {
            return redirect()->back()->with('warning', 'Cette candidature a déjà été acceptée.');
        }

        $pre->status = 'accepted';
        $pre->save();

        $formationName = $this->getFormationLabel($pre->choix_formation);
        $totalAmount = \App\Services\CinetPayService::getFormationPrice($formationName);

        // NOUVEAU : Demander le mode de paiement
        // Par défaut, on crée le paiement en 2 tranches
        $paymentMode = $request->input('payment_mode', 'installment'); // 'full' ou 'installment'

        if ($paymentMode === 'installment') {
            // Créer le paiement de la 1ère tranche
            $firstInstallmentRef = 'EVC-PAY-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
            
            $firstInstallmentId = DB::table('payments')->insertGetId([
                'pre_registration_id' => $pre->id,
                'amount' => 50000, // 1ère tranche
                'currency' => 'XOF',
                'payment_reference' => $firstInstallmentRef,
                'status' => 'pending',
                'payer_email' => $pre->email,
                'payer_name' => $pre->prenom . ' ' . $pre->nom,
                'expires_at' => now()->addDays(7),
                'payment_type' => 'installment',
                'installment_number' => 1,
                'total_installments' => 2,
                'total_amount' => $totalAmount,
                'parent_payment_id' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Créer le paiement de la 2ème tranche (status: pending mais non encore payable)
            $secondInstallmentRef = 'EVC-PAY-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
            
            DB::table('payments')->insert([
                'pre_registration_id' => $pre->id,
                'amount' => 27000, // 2ème tranche
                'currency' => 'XOF',
                'payment_reference' => $secondInstallmentRef,
                'status' => 'pending',
                'payer_email' => $pre->email,
                'payer_name' => $pre->prenom . ' ' . $pre->nom,
                'expires_at' => now()->addDays(30), // Plus de délai
                'payment_type' => 'installment',
                'installment_number' => 2,
                'total_installments' => 2,
                'total_amount' => $totalAmount,
                'parent_payment_id' => $firstInstallmentId,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $paymentUrl = url('/evc/payment/' . $firstInstallmentRef);
            $message = '✅ Candidature acceptée ! Un email avec le lien de paiement (1ère tranche : 50 000 FCFA) a été envoyé.';

        } else {
            // Paiement unique (code existant)
            $paymentReference = 'EVC-PAY-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

            DB::table('payments')->insert([
                'pre_registration_id' => $pre->id,
                'amount' => $totalAmount,
                'currency' => 'XOF',
                'payment_reference' => $paymentReference,
                'status' => 'pending',
                'payer_email' => $pre->email,
                'payer_name' => $pre->prenom . ' ' . $pre->nom,
                'expires_at' => now()->addDays(7),
                'payment_type' => 'full',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $paymentUrl = url('/evc/payment/' . $paymentReference);
            $message = '✅ Candidature acceptée ! Un email avec le lien de paiement a été envoyé.';
        }

        // Créer l'objet payment pour l'email
        $payment = (object)[
            'amount' => $paymentMode === 'installment' ? 50000 : $totalAmount,
            'payment_reference' => $paymentMode === 'installment' ? $firstInstallmentRef : $paymentReference,
            'expires_at' => now()->addDays(7)->format('d/m/Y'),
            'payment_type' => $paymentMode,
        ];

        Mail::to($pre->email)->send(new CandidatureAcceptee($pre, $paymentUrl, $payment));

        Log::info('Candidature acceptée avec paiement', [
            'pre_id' => $pre->id,
            'payment_mode' => $paymentMode,
        ]);

        return redirect()->back()->with('success', $message);

    } catch (\Exception $e) {
        Log::error('Erreur lors de l\'acceptation de la candidature', [
            'id' => $id,
            'error' => $e->getMessage()
        ]);

        return redirect()->back()
            ->with('error', '❌ Erreur lors de l\'acceptation : ' . $e->getMessage());
    }
}
```

---

## 📧 Étape 3 : Modifier l'Email de Candidature Acceptée

### Fichier : `resources/views/emails/candidature_acceptee.blade.php`

Ajouter la section paiement par tranche :

```blade
{{-- Section Paiement --}}
<tr>
    <td style="padding: 30px 40px;">
        <h2 style="color: #667eea; margin-bottom: 20px;">💳 Informations de Paiement</h2>
        
        @if($payment->payment_type === 'installment')
            <div style="background: #eff6ff; padding: 20px; border-radius: 12px; border-left: 4px solid #3b82f6; margin-bottom: 20px;">
                <p style="font-weight: 600; color: #1e40af; margin-bottom: 10px;">
                    📊 Paiement Fractionné en 2 Tranches
                </p>
                <p style="color: #475569; line-height: 1.6; margin: 0;">
                    Vous avez choisi de payer en 2 fois pour faciliter votre inscription. Voici le détail :
                </p>
            </div>

            <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
                <tr>
                    <td style="padding: 15px; background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <strong style="color: #1e40af;">1ère Tranche (À payer maintenant)</strong>
                    </td>
                    <td style="padding: 15px; background: #f8fafc; text-align: right; border-bottom: 2px solid #e2e8f0;">
                        <strong style="color: #2563eb; font-size: 1.2em;">50 000 FCFA</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px; background: #ffffff; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #64748b;">2ème Tranche (Ultérieurement)</span>
                    </td>
                    <td style="padding: 15px; background: #ffffff; text-align: right; border-bottom: 1px solid #e2e8f0;">
                        <span style="color: #64748b;">27 000 FCFA</span>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 15px; background: #f1f5f9;">
                        <strong>Montant Total</strong>
                    </td>
                    <td style="padding: 15px; background: #f1f5f9; text-align: right;">
                        <strong style="color: #0f172a; font-size: 1.3em;">77 000 FCFA</strong>
                    </td>
                </tr>
            </table>

            <div style="background: #fef3c7; padding: 15px; border-radius: 8px; border-left: 4px solid #f59e0b; margin-top: 20px;">
                <p style="margin: 0; color: #92400e; font-size: 0.95em;">
                    ⚡ <strong>Important :</strong> Vous recevrez un lien pour la 2ème tranche après validation de votre premier paiement.
                </p>
            </div>
        @else
            <div style="background: #f8fafc; padding: 20px; border-radius: 12px; margin-bottom: 20px;">
                <p style="color: #475569; margin: 0 0 10px 0;">Montant à payer :</p>
                <p style="font-size: 2em; font-weight: 700; color: #2563eb; margin: 0;">
                    {{ number_format($payment->amount, 0, ',', ' ') }} FCFA
                </p>
            </div>
        @endif

        <p style="margin-top: 20px;">
            <strong>Référence :</strong> <code style="background: #f1f5f9; padding: 5px 10px; border-radius: 6px;">{{ $payment->payment_reference }}</code>
        </p>
        <p><strong>Date limite :</strong> {{ $payment->expires_at }}</p>
    </td>
</tr>

{{-- Bouton Payer --}}
<tr>
    <td style="padding: 20px 40px; text-align: center;">
        <a href="{{ $paymentUrl }}" 
           style="display: inline-block; background: linear-gradient(135deg, #2563eb, #f97316); color: white; padding: 18px 50px; text-decoration: none; border-radius: 50px; font-weight: 700; font-size: 1.1em; box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);">
            @if($payment->payment_type === 'installment')
                💳 Payer la 1ère Tranche (50 000 FCFA)
            @else
                💳 Procéder au Paiement
            @endif
        </a>
    </td>
</tr>
```

---

## 🔄 Étape 4 : Gérer le Déclenchement de la 2ème Tranche

### Fichier : `app/Http/Controllers/PaymentController.php`

Dans la méthode webhook qui gère le retour CinetPay, ajouter :

```php
public function handleWebhook(Request $request)
{
    // ... code existant pour vérifier le paiement ...

    if ($status['success'] && $status['status'] == 'ACCEPTED') {
        DB::table('payments')
            ->where('id', $payment->id)
            ->update([
                'status' => 'completed',
                'transaction_id' => $transactionId,
                'updated_at' => now()
            ]);

        // NOUVEAU : Si c'est la 1ère tranche, envoyer l'email pour la 2ème
        if ($payment->payment_type === 'installment' && $payment->installment_number === 1) {
            $this->sendSecondInstallmentEmail($payment);
        }

        return response()->json(['success' => true]);
    }
}

protected function sendSecondInstallmentEmail($firstPayment)
{
    // Récupérer le paiement de la 2ème tranche
    $secondPayment = DB::table('payments')
        ->where('parent_payment_id', $firstPayment->id)
        ->where('installment_number', 2)
        ->first();

    if (!$secondPayment) {
        return;
    }

    $candidate = DB::table('pre_registrations')
        ->where('id', $firstPayment->pre_registration_id)
        ->first();

    $paymentUrl = url('/evc/payment/' . $secondPayment->payment_reference);

    // Envoyer un nouvel email
    Mail::to($candidate->email)->send(new \App\Mail\SecondInstallmentReminder($candidate, $secondPayment, $paymentUrl));

    Log::info('Email 2ème tranche envoyé', [
        'pre_id' => $candidate->id,
        'payment_ref' => $secondPayment->payment_reference
    ]);
}
```

---

## 📧 Étape 5 : Créer l'Email pour la 2ème Tranche

### Créer le Mailable :

```bash
php artisan make:mail SecondInstallmentReminder
```

### Fichier : `app/Mail/SecondInstallmentReminder.php`

```php
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SecondInstallmentReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $candidate;
    public $payment;
    public $paymentUrl;

    public function __construct($candidate, $payment, string $paymentUrl)
    {
        $this->candidate = $candidate;
        $this->payment = $payment;
        $this->paymentUrl = $paymentUrl;
    }

    public function build()
    {
        return $this->subject('Finalisation de votre inscription - 2ème Tranche - EVC')
            ->view('emails.second_installment_reminder');
    }
}
```

### Créer la vue : `resources/views/emails/second_installment_reminder.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>2ème Tranche - EVC</title>
</head>
<body style="font-family: 'Arial', sans-serif; margin: 0; padding: 0; background-color: #f8fafc;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0" style="background: white; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.1);">
                    
                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1e40af, #3b82f6, #f97316, #fb923c); padding: 40px; text-align: center; border-radius: 20px 20px 0 0;">
                            <h1 style="color: white; margin: 0; font-size: 2em;">🎓 Finalisez Votre Inscription</h1>
                            <p style="color: rgba(255,255,255,0.9); margin: 10px 0 0 0;">École Virtuelle des Créatifs</p>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding: 40px;">
                            <p style="font-size: 1.1em; color: #0f172a;">
                                Bonjour <strong>{{ $candidate->prenom }} {{ $candidate->nom }}</strong> ! 👋
                            </p>

                            <div style="background: #d1fae5; padding: 20px; border-radius: 12px; border-left: 4px solid #10b981; margin: 20px 0;">
                                <p style="margin: 0; color: #065f46;">
                                    ✅ <strong>Bonne nouvelle !</strong> Votre 1ère tranche a été payée avec succès !
                                </p>
                            </div>

                            <p style="color: #475569; line-height: 1.8;">
                                Pour finaliser complètement votre inscription à la formation <strong style="color: #2563eb;">{{ $candidate->choix_formation }}</strong>, il vous reste à régler la 2ème et dernière tranche.
                            </p>

                            <table style="width: 100%; margin: 30px 0; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 15px; background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                        <strong>2ème Tranche</strong>
                                    </td>
                                    <td style="padding: 15px; background: #f8fafc; text-align: right; border-bottom: 2px solid #e2e8f0;">
                                        <strong style="color: #2563eb; font-size: 1.5em;">27 000 FCFA</strong>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #475569;">
                                <strong>Référence :</strong> <code style="background: #f1f5f9; padding: 5px 10px; border-radius: 6px;">{{ $payment->payment_reference }}</code><br>
                                <strong>Date limite :</strong> {{ \Carbon\Carbon::parse($payment->expires_at)->format('d/m/Y') }}
                            </p>
                        </td>
                    </tr>

                    {{-- CTA --}}
                    <tr>
                        <td style="padding: 20px 40px 40px; text-align: center;">
                            <a href="{{ $paymentUrl }}" 
                               style="display: inline-block; background: linear-gradient(135deg, #2563eb, #f97316); color: white; padding: 18px 50px; text-decoration: none; border-radius: 50px; font-weight: 700; font-size: 1.1em; box-shadow: 0 10px 30px rgba(37, 99, 235, 0.4);">
                                💳 Payer la 2ème Tranche
                            </a>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background: #f8fafc; padding: 30px; text-align: center; border-radius: 0 0 20px 20px;">
                            <p style="margin: 0; color: #64748b; font-size: 0.9em;">
                                © 2025 École Virtuelle des Créatifs. Tous droits réservés.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
```

---

## 🎯 Étape 6 : Ajouter le Choix dans l'Interface Admin (Optionnel)

### Modifier la page `resources/views/admin/preregistrations/show.blade.php`

Ajouter un modal pour demander le mode de paiement avant validation :

```javascript
function validateCandidate(id) {
    Swal.fire({
        title: 'Mode de paiement',
        html: `
            <div style="text-align: left; padding: 20px;">
                <p style="margin-bottom: 15px; color: #475569;">Choisissez le mode de paiement pour ce candidat :</p>
                
                <div style="margin-bottom: 15px;">
                    <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer;">
                        <input type="radio" name="payment_mode" value="full" checked style="margin-right: 10px;">
                        <div>
                            <strong>Paiement Unique</strong><br>
                            <small style="color: #64748b;">77 000 FCFA en une seule fois</small>
                        </div>
                    </label>
                </div>

                <div>
                    <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #e2e8f0; border-radius: 12px; cursor: pointer;">
                        <input type="radio" name="payment_mode" value="installment" style="margin-right: 10px;">
                        <div>
                            <strong>Paiement par Tranche</strong> <span style="background: #10b981; color: white; padding: 2px 8px; border-radius: 12px; font-size: 0.8em;">Facilité</span><br>
                            <small style="color: #64748b;">50 000 FCFA + 27 000 FCFA</small>
                        </div>
                    </label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Valider',
        cancelButtonText: 'Annuler',
        preConfirm: () => {
            const selectedMode = document.querySelector('input[name="payment_mode"]:checked').value;
            return selectedMode;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/evc/app/admin/preinscriptions/' + id + '/accept';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const modeInput = document.createElement('input');
            modeInput.type = 'hidden';
            modeInput.name = 'payment_mode';
            modeInput.value = result.value;
            form.appendChild(modeInput);

            document.body.appendChild(form);
            form.submit();
        }
    });
}
```

---

## 🧪 Étape 7 : Tester le Système

### Test 1 : Paiement par Tranche

1. Admin valide une candidature en choisissant "Paiement par Tranche"
2. Le candidat reçoit un email avec le lien pour payer 50 000 FCFA
3. Le candidat paie la 1ère tranche
4. Automatiquement, il reçoit un email pour payer la 2ème tranche (27 000 FCFA)
5. Il paie la 2ème tranche
6. L'inscription est finalisée

### Test 2 : Paiement Unique

1. Admin valide une candidature en choisissant "Paiement Unique"
2. Le candidat reçoit un email avec le lien pour payer 77 000 FCFA
3. Le candidat paie
4. L'inscription est finalisée

---

## 📊 Vérification en Base de Données

### Exemple de paiement par tranche :

**Paiement 1 (1ère tranche) :**
```sql
id: 1
amount: 50000
payment_type: 'installment'
installment_number: 1
total_installments: 2
total_amount: 77000
parent_payment_id: NULL
status: 'completed'
```

**Paiement 2 (2ème tranche) :**
```sql
id: 2
amount: 27000
payment_type: 'installment'
installment_number: 2
total_installments: 2
total_amount: 77000
parent_payment_id: 1
status: 'pending'
```

---

## 🎯 Avantages du Système

- ✅ **Flexibilité** - Le candidat choisit son mode de paiement
- ✅ **Augmentation des conversions** - Plus accessible financièrement
- ✅ **Traçabilité** - Tout est enregistré en base
- ✅ **Automatisation** - Email 2ème tranche envoyé automatiquement
- ✅ **Sécurité** - Lien unique pour chaque tranche
- ✅ **Expiration** - Chaque tranche a sa propre date limite

---

## 🚀 Résumé des Changements

| Fichier | Action |
|---------|--------|
| **Migration** | ✅ Ajout champs installment |
| **PreRegistrationAdminController** | 🔄 Modifier acceptCandidate() |
| **PaymentController** | 🔄 Ajouter sendSecondInstallmentEmail() |
| **candidature_acceptee.blade.php** | 🔄 Ajouter section tranches |
| **SecondInstallmentReminder** | ✨ Créer nouveau Mailable |
| **second_installment_reminder.blade.php** | ✨ Créer nouvelle vue |
| **show.blade.php (admin)** | 🔄 Ajouter modal choix paiement |

---

## ⚠️ Important

1. **Tester en mode TEST CinetPay** avant de passer en PRODUCTION
2. **Sauvegarder la base** avant de déployer
3. **Vérifier les emails** (templates et envoi)
4. **Logger toutes les transactions** pour traçabilité
5. **Gérer les cas d'échec** (paiement rejeté, expiré, etc.)

---

## 🎓 Prêt à Implémenter !

Tous les éléments sont fournis. Il suffit de suivre les étapes dans l'ordre pour avoir un système de paiement par tranche fonctionnel et professionnel !
