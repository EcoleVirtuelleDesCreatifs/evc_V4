<?php

namespace App\Http\Controllers;

use App\Services\CinetPayService;
use App\Services\ChariowService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Afficher la page de paiement
     */
    public function showCheckout($paymentReference)
    {
        $payment = DB::table('payments')
            ->where('payment_reference', $paymentReference)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()->route('login')
                ->with('error', 'Lien de paiement invalide ou expiré');
        }

        // Vérifier expiration
        if ($payment->expires_at && strtotime($payment->expires_at) < time()) {
            return view('payment.expired', compact('payment'));
        }

        $candidate = DB::table('pre_registrations')
            ->where('id', $payment->pre_registration_id)
            ->first();

        return view('payment.checkout', compact('payment', 'candidate'));
    }

    /**
     * Traiter le paiement
     */
    public function processPayment(Request $request)
    {
        // Vérifier si c'est une requête AJAX (du widget)
        if ($request->expectsJson() || $request->ajax()) {
            $request->validate([
                'payment_reference' => 'required|string',
                'transaction_id' => 'required|string',
                'phone_number' => 'nullable|string',
            ]);

            $payment = DB::table('payments')
                ->where('payment_reference', $request->payment_reference)
                ->where('status', 'pending')
                ->first();

            if (!$payment) {
                return response()->json(['success' => false, 'message' => 'Paiement introuvable'], 404);
            }

            $candidate = DB::table('pre_registrations')
                ->where('id', $payment->pre_registration_id)
                ->first();

            try {
                // Mettre à jour le paiement avec la transaction_id
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'transaction_id' => $request->transaction_id,
                        'phone_number' => $request->phone_number,
                        'payer_name' => $candidate->prenom . ' ' . $candidate->nom,
                        'payer_email' => $candidate->email,
                        'updated_at' => now(),
                    ]);

                Log::info('Paiement initialisé via widget', [
                    'payment_id' => $payment->id,
                    'transaction_id' => $request->transaction_id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Paiement initialisé',
                    'transaction_id' => $request->transaction_id
                ]);

            } catch (\Exception $e) {
                Log::error('Erreur traitement paiement widget : ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Une erreur est survenue'], 500);
            }
        }

        // Requête classique (formulaire)
        $request->validate([
            'payment_reference' => 'required|string',
            'phone_number' => 'nullable|string',
        ]);

        $payment = DB::table('payments')
            ->where('payment_reference', $request->payment_reference)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return back()->with('error', 'Paiement introuvable');
        }

        $candidate = DB::table('pre_registrations')
            ->where('id', $payment->pre_registration_id)
            ->first();

        try {
            // ✅ NOUVEAU : Vérifier si Chariow est activé
            if (ChariowService::isEnabled()) {
                Log::info('🛒 Redirection vers Chariow', [
                    'payment_reference' => $payment->payment_reference,
                    'formation' => $candidate->choix_formation,
                    'installment_number' => $payment->installment_number
                ]);

                // Créer le service Chariow
                $chariow = new ChariowService();

                // Récupérer le lien direct du produit (sans paramètres GET)
                $chariowUrl = $chariow->getPaymentLink($candidate->choix_formation, $payment->installment_number ?? 1);

                // Mettre à jour le paiement
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'payment_url' => $chariowUrl,
                        'phone_number' => $request->phone_number ?? $candidate->whatsapp,
                        'payer_name' => $candidate->prenom . ' ' . $candidate->nom,
                        'payer_email' => $candidate->email,
                        'updated_at' => now(),
                    ]);

                Log::info('✅ Redirection Chariow', ['url' => $chariowUrl]);

                // Rediriger vers Chariow (lien direct du produit)
                return redirect($chariowUrl);
            }

            // Initialiser CinetPay (ancien système)
            $cinetpay = new CinetPayService($candidate->choix_formation);

            $transactionId = CinetPayService::generateTransactionId();

            // Formater le numéro au format international si nécessaire
            $phoneNumber = $request->phone_number ?? $candidate->whatsapp ?? '';
            if ($phoneNumber && !str_starts_with($phoneNumber, '+')) {
                // Si le numéro ne commence pas par +, ajouter +225 pour la Côte d'Ivoire
                $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber); // Garder seulement les chiffres
                if (strlen($phoneNumber) == 10 && str_starts_with($phoneNumber, '0')) {
                    // Format: 0X XX XX XX XX → +225 X XX XX XX XX
                    $phoneNumber = '+225' . substr($phoneNumber, 1);
                } else if (strlen($phoneNumber) >= 8) {
                    $phoneNumber = '+225' . $phoneNumber;
                }
            }

            $paymentData = [
                'transaction_id' => $transactionId,
                'amount' => $payment->amount,
                'description' => "Formation {$candidate->choix_formation} - EVC",
                'customer_name' => $candidate->prenom,
                'customer_surname' => $candidate->nom,
                'customer_email' => $candidate->email,
                'customer_phone' => $phoneNumber,
                'customer_address' => $candidate->adresse ?? 'Abidjan',
                'customer_city' => $candidate->ville ?? 'Abidjan',
                'customer_country' => 'CI',
                'metadata' => json_encode([
                    'payment_id' => $payment->id,
                    'pre_registration_id' => $candidate->id,
                    'formation' => $candidate->choix_formation,
                ]),
            ];

            $result = $cinetpay->initiatePayment($paymentData);

            if ($result['success']) {
                // Mettre à jour le paiement
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'transaction_id' => $transactionId,
                        'phone_number' => $request->phone_number,
                        'payer_name' => $candidate->prenom . ' ' . $candidate->nom,
                        'payer_email' => $candidate->email,
                        'updated_at' => now(),
                    ]);

                // Rediriger vers la page de paiement CinetPay
                return redirect($result['payment_url']);
            }

            return back()->with('error', $result['message'] ?? 'Erreur lors de l\'initialisation du paiement');

        } catch (\Exception $e) {
            Log::error('Erreur traitement paiement : ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue. Veuillez réessayer.');
        }
    }

    /**
     * Retour après paiement (return_url)
     */
    public function paymentReturn(Request $request)
    {
        $transactionId = $request->input('transaction_id');

        Log::info('🔍 paymentReturn appelé', [
            'transaction_id' => $transactionId,
            'all_params' => $request->all()
        ]);

        if (!$transactionId) {
            Log::error('❌ Transaction ID manquant');
            return redirect()->route('login')
                ->with('error', 'Lien de paiement invalide ou expiré');
        }

        $payment = DB::table('payments')
            ->where('transaction_id', $transactionId)
            ->first();

        Log::info('🔍 Recherche paiement', [
            'transaction_id' => $transactionId,
            'payment_found' => $payment ? 'OUI' : 'NON'
        ]);

        if (!$payment) {
            Log::error('❌ Paiement non trouvé', [
                'transaction_id' => $transactionId
            ]);
            return redirect()->route('login')
                ->with('error', 'Lien de paiement invalide ou expiré');
        }

        // Récupérer le candidat
        $candidate = DB::table('pre_registrations')
            ->where('id', $payment->pre_registration_id)
            ->first();

        // Si c'est un paiement de test (commence par TEST-) ou déjà completed
        if (str_starts_with($transactionId, 'TEST-') || $payment->status === 'completed') {
            // Si c'est la 1ère tranche et qu'il y a un token de création de compte
            if ($payment->installment_number == 1 && $payment->account_creation_token) {
                // Rediriger vers la page de création de compte
                $confirmationUrl = url('/student/confirm-registration/' . $payment->account_creation_token);

                return redirect($confirmationUrl)
                    ->with('success', '✅ Paiement confirmé ! Créez votre mot de passe pour accéder à votre espace étudiant.');
            }

            // Afficher la page de succès
            return view('payment.success', compact('payment', 'candidate'));
        }

        // Pour les vrais paiements CinetPay, vérifier le statut
        $cinetpay = new CinetPayService($candidate->choix_formation);
        $status = $cinetpay->checkPaymentStatus($transactionId);

        if ($status['success'] && $status['status'] == 'ACCEPTED') {
            return view('payment.success', compact('payment', 'candidate'));
        }

        return view('payment.pending', compact('payment', 'candidate'));
    }

    /**
     * Webhook CinetPay (notify_url)
     */
    public function webhook(Request $request)
    {
        Log::info('CinetPay Webhook reçu', $request->all());

        try {
            $cpmTransId = $request->input('cpm_trans_id');
            $cpmAmount = $request->input('cpm_amount');
            $cpmTransStatus = $request->input('cpm_trans_status');
            $cpmSiteId = $request->input('cpm_site_id');
            $signature = $request->input('signature');
            $cpmCustom = $request->input('cpm_custom');

            if (!$cpmTransId) {
                Log::error('Webhook CinetPay : transaction_id manquant');
                return response()->json(['status' => 'error', 'message' => 'transaction_id required'], 400);
            }

            // Récupérer le paiement via cpm_trans_id (qui est notre transaction_id)
            $payment = DB::table('payments')
                ->where('transaction_id', $cpmTransId)
                ->first();

            if (!$payment) {
                Log::error('Webhook CinetPay : Paiement non trouvé', ['transaction_id' => $cpmTransId]);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }

            // Vérifier que le paiement n'est pas déjà complété
            if ($payment->status === 'completed') {
                Log::info('Webhook CinetPay : Paiement déjà traité', ['payment_id' => $payment->id]);
                return response()->json(['status' => 'success', 'message' => 'Already processed']);
            }

            // Récupérer le candidat
            $candidate = DB::table('pre_registrations')
                ->where('id', $payment->pre_registration_id)
                ->first();

            // Vérifier le statut du paiement
            if ($cpmTransStatus == 'ACCEPTED' || $cpmTransStatus == '00') {
                DB::beginTransaction();

                try {
                    // Générer le token de confirmation (même format que StudentConfirmationController)
                    $timestamp = time();
                    $hash = md5($candidate->email . config('app.key'));
                    $tokenData = $candidate->email . '|' . $timestamp . '|' . $hash;
                    $confirmationToken = base64_encode($tokenData);

                    // Mettre à jour le paiement
                    DB::table('payments')
                        ->where('id', $payment->id)
                        ->update([
                            'status' => 'completed',
                            'paid_at' => now(),
                            'cpm_trans_id' => $cpmTransId,
                            'cpm_site_id' => $cpmSiteId,
                            'cpm_custom' => $cpmCustom,
                            'account_creation_token' => $confirmationToken,
                            'updated_at' => now(),
                        ]);

                    // Mettre à jour la pré-inscription
                    DB::table('pre_registrations')
                        ->where('id', $payment->pre_registration_id)
                        ->update([
                            'status' => 'paid',
                            'updated_at' => now(),
                        ]);

                    // Enregistrer dans la comptabilité
                    $formationLabel = $candidate->choix_formation ?? 'Formation';
                    $installmentInfo = '';
                    if ($payment->payment_type === 'installment') {
                        $installmentInfo = ' (Tranche ' . $payment->installment_number . '/' . $payment->total_installments . ')';
                    }

                    \App\Models\AccountingTransaction::create([
                        'type' => 'income',
                        'category' => 'Scolarité',
                        'amount' => $payment->amount,
                        'date' => now(),
                        'title' => 'Paiement scolarité - ' . $candidate->prenom . ' ' . $candidate->nom . $installmentInfo,
                        'description' => 'Paiement de scolarité pour la formation : ' . $formationLabel,
                        'reference' => $payment->payment_reference,
                        'payment_method' => 'CinetPay',
                        'student_name' => $candidate->prenom . ' ' . $candidate->nom,
                        'training_module' => $formationLabel,
                    ]);

                    Log::info('Transaction comptable créée', [
                        'payment_id' => $payment->id,
                        'amount' => $payment->amount,
                        'student' => $candidate->prenom . ' ' . $candidate->nom
                    ]);

                    // Créer l'entrée user si elle n'existe pas
                    $existingUser = DB::table('users')->where('email', $candidate->email)->first();

                    if (!$existingUser) {
                        DB::table('users')->insert([
                            'first_name' => $candidate->prenom,
                            'last_name' => $candidate->nom,
                            'email' => $candidate->email,
                            'phone' => $candidate->whatsapp ?? null,
                            'country' => $candidate->pays ?? 'Côte d\'Ivoire',
                            'city' => $candidate->ville ?? null,
                            'formation_souhaitee' => $candidate->choix_formation ?? null,
                            'profile_photo' => $candidate->photo ?? null,
                            'status' => 'En attente',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        Log::info('User créé automatiquement après paiement', [
                            'email' => $candidate->email
                        ]);
                    }

                    // Envoyer email avec lien de confirmation d'inscription
                    $confirmationUrl = url('/student/confirm-registration/' . $confirmationToken);

                    Mail::send('emails.payment_confirmed', [
                        'candidate' => $candidate,
                        'payment' => $payment,
                        'accountCreationUrl' => $confirmationUrl,
                    ], function ($message) use ($candidate) {
                        $message->to($candidate->email)
                            ->subject('✅ Paiement confirmé - Créez votre compte EVC');
                    });

                    DB::commit();

                    Log::info('Webhook CinetPay : Paiement traité avec succès', [
                        'payment_id' => $payment->id,
                        'transaction_id' => $cpmTransId,
                        'confirmation_url' => $confirmationUrl
                    ]);

                    // ========== EMAIL 2ÈME TRANCHE (DÉSACTIVÉ) ==========
                    // L'email de la 2ème tranche doit être envoyé MANUELLEMENT par l'admin
                    // APRÈS 2 MOIS DE FORMATION, pas automatiquement après le 1er paiement
                    //
                    // Pour envoyer l'email 2ème tranche :
                    // 1. Créer une commande artisan ou un bouton admin
                    // 2. Appeler : $this->sendSecondInstallmentEmail($payment, $candidate);
                    //
                    // Code commenté (envoi automatique) :
                    // if ($payment->payment_type === 'installment' && $payment->installment_number == 1) {
                    //     $this->sendSecondInstallmentEmail($payment, $candidate);
                    // }

                    return response()->json(['status' => 'success', 'message' => 'Payment processed']);

                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Webhook CinetPay : Erreur traitement', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }
            } else {
                // Paiement échoué
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'status' => 'failed',
                        'cpm_trans_id' => $cpmTransId,
                        'cpm_site_id' => $cpmSiteId,
                        'updated_at' => now(),
                    ]);

                Log::warning('Webhook CinetPay : Paiement échoué', [
                    'payment_id' => $payment->id,
                    'status' => $cpmTransStatus
                ]);
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('Webhook CinetPay : Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Page de paiement annulé
     */
    public function paymentCancel(Request $request)
    {
        $transactionId = $request->input('transaction_id');

        if ($transactionId) {
            DB::table('payments')
                ->where('transaction_id', $transactionId)
                ->update([
                    'status' => 'cancelled',
                    'updated_at' => now(),
                ]);
        }

        return view('payment.cancelled');
    }

    /**
     * Envoyer l'email pour la 2ème tranche
     *
     * @param object $firstPayment
     * @param object $candidate
     */
    protected function sendSecondInstallmentEmail($firstPayment, $candidate)
    {
        try {
            // Récupérer le paiement de la 2ème tranche
            $secondPayment = DB::table('payments')
                ->where('parent_payment_id', $firstPayment->id)
                ->where('installment_number', 2)
                ->first();

            if (!$secondPayment) {
                Log::warning('2ème tranche introuvable', ['first_payment_id' => $firstPayment->id]);
                return;
            }

            $paymentUrl = url('/evc/payment/' . $secondPayment->payment_reference);

            // Envoyer l'email
            Mail::to($candidate->email)->send(
                new \App\Mail\SecondInstallmentReminder($candidate, $secondPayment, $paymentUrl)
            );

            Log::info('Email 2ème tranche envoyé', [
                'candidate_id' => $candidate->id,
                'payment_ref' => $secondPayment->payment_reference,
                'email' => $candidate->email
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur envoi email 2ème tranche', [
                'error' => $e->getMessage(),
                'first_payment_id' => $firstPayment->id
            ]);
        }
    }

    /**
     * Simuler un webhook CinetPay pour tests en développement local
     * (car les webhooks ne peuvent pas fonctionner vers 127.0.0.1)
     *
     * @param Request $request
     */
    public function simulateWebhook(Request $request)
    {
        try {
            $transactionId = $request->input('transaction_id');
            $amount = $request->input('amount');
            $status = $request->input('status', '00');

            if (!$transactionId) {
                return redirect()->back()->with('error', 'Transaction ID requis');
            }

            // Simuler les paramètres CinetPay
            $simulatedRequest = new Request([
                'cpm_trans_id' => $transactionId,
                'cpm_amount' => $amount,
                'cpm_trans_status' => $status,
                'cpm_site_id' => config('cinetpay.sites.design_graphique.site_id'),
                'signature' => 'simulated',
                'cpm_custom' => '',
            ]);

            // Appeler la méthode webhook normale
            $response = $this->webhook($simulatedRequest);

            if ($response->status() === 200) {
                return redirect()->back()->with('success', '✅ Webhook simulé avec succès ! Vérifiez les emails et la base de données.');
            } else {
                return redirect()->back()->with('error', '❌ Erreur lors de la simulation: ' . $response->getContent());
            }

        } catch (\Exception $e) {
            Log::error('Erreur simulation webhook', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', '❌ Exception: ' . $e->getMessage());
        }
    }

    /**
     * Envoyer manuellement l'email de la 2ème tranche (après 2 mois de formation)
     *
     * @param Request $request
     */
    public function sendSecondInstallmentEmailManual(Request $request)
    {
        try {
            $firstPaymentId = $request->input('payment_id');

            if (!$firstPaymentId) {
                return redirect()->back()->with('error', 'Payment ID requis');
            }

            // Récupérer le paiement de la 1ère tranche
            $firstPayment = DB::table('payments')->where('id', $firstPaymentId)->first();

            if (!$firstPayment) {
                return redirect()->back()->with('error', 'Paiement introuvable');
            }

            // Vérifier que c'est bien la 1ère tranche et qu'elle est payée
            if ($firstPayment->installment_number != 1 || $firstPayment->status !== 'completed') {
                return redirect()->back()->with('error', 'Ce paiement n\'est pas une 1ère tranche payée');
            }

            // Récupérer les infos du candidat
            $candidate = DB::table('pre_registrations')
                ->where('id', $firstPayment->pre_registration_id)
                ->first();

            if (!$candidate) {
                return redirect()->back()->with('error', 'Candidat introuvable');
            }

            // Envoyer l'email de la 2ème tranche
            $this->sendSecondInstallmentEmail($firstPayment, $candidate);

            Log::info('Email 2ème tranche envoyé manuellement', [
                'first_payment_id' => $firstPaymentId,
                'candidate_email' => $candidate->email
            ]);

            return redirect()->back()->with('success', '✅ Email de la 2ème tranche envoyé à ' . $candidate->prenom . ' ' . $candidate->nom);

        } catch (\Exception $e) {
            Log::error('Erreur envoi manuel email 2ème tranche', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', '❌ Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Simuler un paiement réussi (uniquement en environnement local)
     */
    public function testPaymentSuccess(Request $request)
    {
        // Sécurité : uniquement en environnement local
        if (!app()->environment('local')) {
            abort(403, 'Cette fonctionnalité n\'est disponible qu\'en environnement de développement');
        }

        $paymentReference = $request->input('payment_reference');

        if (!$paymentReference) {
            return redirect()->back()->with('error', 'Référence de paiement manquante');
        }

        try {
            // Récupérer le paiement
            $payment = DB::table('payments')
                ->where('payment_reference', $paymentReference)
                ->first();

            if (!$payment) {
                return redirect()->back()->with('error', 'Paiement introuvable');
            }

            // Générer un ID de transaction test
            $transactionId = 'TEST-' . strtoupper(uniqid());

            // Mettre à jour le paiement comme complété
            DB::table('payments')
                ->where('id', $payment->id)
                ->update([
                    'status' => 'completed',
                    'transaction_id' => $transactionId,
                    'paid_at' => now(),
                    'updated_at' => now(),
                ]);

            Log::info('🧪 TEST - Paiement simulé comme réussi', [
                'payment_id' => $payment->id,
                'payment_reference' => $paymentReference,
                'transaction_id' => $transactionId,
                'amount' => $payment->amount,
            ]);

            // Récupérer le candidat
            $candidate = DB::table('pre_registrations')
                ->where('id', $payment->pre_registration_id)
                ->first();

            if (!$candidate) {
                return redirect()->back()->with('error', 'Candidat introuvable');
            }

            // Enregistrer dans la comptabilité
            $formationLabel = $candidate->choix_formation ?? 'Formation';
            $installmentInfo = '';
            if ($payment->payment_type === 'installment') {
                $installmentInfo = ' (Tranche ' . $payment->installment_number . '/' . $payment->total_installments . ')';
            }

            \App\Models\AccountingTransaction::create([
                'type' => 'income',
                'category' => 'Scolarité',
                'amount' => $payment->amount,
                'date' => now(),
                'title' => 'Paiement scolarité - ' . $candidate->prenom . ' ' . $candidate->nom . $installmentInfo,
                'description' => 'Paiement de scolarité pour la formation : ' . $formationLabel,
                'reference' => $payment->payment_reference,
                'payment_method' => 'Test (Développement)',
                'student_name' => $candidate->prenom . ' ' . $candidate->nom,
                'training_module' => $formationLabel,
            ]);

            Log::info('🧪 TEST - Transaction comptable créée', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'student' => $candidate->prenom . ' ' . $candidate->nom
            ]);

            // Si c'est la 1ère tranche, envoyer le lien de création de compte
            if ($payment->installment_number == 1) {
                Log::info('🧪 TEST - Envoi email création compte (1ère tranche)', [
                    'candidate_email' => $candidate->email
                ]);

                // Vérifier si un utilisateur et un étudiant existent déjà
                $existingUser = DB::table('users')->where('email', $candidate->email)->first();
                $existingStudent = DB::table('students')->where('email', $candidate->email)->first();

                // Créer l'utilisateur si nécessaire
                if (!$existingUser) {
                    $userId = DB::table('users')->insertGetId([
                        'name' => $candidate->prenom . ' ' . $candidate->nom,
                        'email' => $candidate->email,
                        'password' => bcrypt('temporary_password_' . uniqid()),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::info('✅ Utilisateur créé', ['user_id' => $userId, 'email' => $candidate->email]);
                } else {
                    $userId = $existingUser->id;
                    Log::info('ℹ️ Utilisateur existe déjà', ['user_id' => $userId, 'email' => $candidate->email]);
                }

                // Créer le profil étudiant si nécessaire
                if (!$existingStudent) {
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
                        'degree' => 'Licence',
                        'Level_education' => 'Licence',
                        'status' => 'active',
                        'city' => $candidate->ville ?? 'Abidjan',
                        'country' => $candidate->pays ?? 'Côte d\'Ivoire',
                        'profile_photo' => $candidate->photo ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('✅ Profil étudiant créé', [
                        'student_id' => $studentId,
                        'user_id' => $userId,
                        'email' => $candidate->email
                    ]);
                }

                // Générer token et envoyer email uniquement si c'est un nouveau compte
                if (!$existingUser || !$existingStudent) {
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

                    // Envoyer l'email avec le lien de création de compte
                    $confirmationUrl = url('/student/confirm-registration/' . $confirmationToken);

                    Mail::send('emails.payment_confirmed', [
                        'candidate' => $candidate,
                        'payment' => $payment,
                        'accountCreationUrl' => $confirmationUrl,
                    ], function ($message) use ($candidate) {
                        $message->to($candidate->email)
                            ->subject('🧪 TEST - Paiement confirmé - Créez votre compte EVC');
                    });

                    Log::info('✅ Email création compte envoyé', [
                        'email' => $candidate->email,
                        'confirmation_url' => $confirmationUrl
                    ]);
                } else {
                    Log::info('ℹ️ Utilisateur et étudiant existent déjà', ['email' => $candidate->email]);
                }

                // Mettre à jour la préinscription
                DB::table('pre_registrations')
                    ->where('id', $payment->pre_registration_id)
                    ->update([
                        'status' => 'paid',
                        'updated_at' => now(),
                    ]);

                Log::info('ℹ️ 2ème tranche - Email sera envoyé manuellement par l\'admin après 2 mois');
            }

            // Si c'est la 2ème tranche
            if ($payment->installment_number == 2) {
                Log::info('🧪 TEST - Paiement 2ème tranche complété', [
                    'candidate_email' => $candidate->email
                ]);

                // Envoyer email de confirmation 2ème tranche
                Mail::send('emails.second_payment_confirmation', [
                    'candidate' => $candidate,
                    'payment' => $payment,
                ], function ($message) use ($candidate) {
                    $message->to($candidate->email)
                        ->subject('🧪 TEST - Paiement 2ème tranche confirmé - EVC');
                });

                Log::info('✅ Email confirmation 2ème tranche envoyé', [
                    'email' => $candidate->email
                ]);
            }

            // Redirection selon tranche
            if ($payment->installment_number == 1) {
                // Récupérer le token depuis le paiement mis à jour
                $updatedPayment = DB::table('payments')
                    ->where('id', $payment->id)
                    ->first();

                if ($updatedPayment->account_creation_token) {
                    $confirmationUrl = url('/student/confirm-registration/' . $updatedPayment->account_creation_token);

                    Log::info('✅ Redirection vers création de compte', [
                        'url' => $confirmationUrl
                    ]);

                    return redirect($confirmationUrl)
                        ->with('success', '🧪 TEST - Paiement confirmé ! Créez votre mot de passe.');
                } else {
                    Log::info('ℹ️ Pas de token, redirection vers succès');
                    return view('payment.success', compact('payment', 'candidate'))
                        ->with('success', '🧪 TEST - Paiement confirmé !');
                }
            }

            // Pour la 2ème tranche, afficher page de succès
            return view('payment.success', compact('payment', 'candidate'))
                ->with('success', '🧪 TEST - Paiement 2ème tranche confirmé !');

        } catch (\Exception $e) {
            Log::error('🧪 TEST - Erreur simulation paiement', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Erreur lors de la simulation : ' . $e->getMessage());
        }
    }

    /**
     * Retour après paiement Chariow réussi
     */
    public function chariowReturn(Request $request)
    {
        $reference = $request->input('reference');
        $transactionId = $request->input('transaction_id') ?? 'CHARIOW-' . uniqid();

        Log::info('🛒 Retour Chariow (succès)', [
            'reference' => $reference,
            'transaction_id' => $transactionId,
            'all_params' => $request->all()
        ]);

        if (!$reference) {
            return redirect()->route('login')
                ->with('error', 'Référence de paiement manquante');
        }

        try {
            $payment = DB::table('payments')
                ->where('payment_reference', $reference)
                ->first();

            if (!$payment) {
                Log::error('❌ Paiement introuvable', ['reference' => $reference]);
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

            Log::info('✅ Paiement Chariow marqué comme complété', [
                'payment_id' => $payment->id,
                'transaction_id' => $transactionId
            ]);

            // Récupérer le candidat
            $candidate = DB::table('pre_registrations')
                ->where('id', $payment->pre_registration_id)
                ->first();

            // Enregistrer dans la comptabilité
            $formationLabel = $candidate->choix_formation ?? 'Formation';
            $installmentInfo = '';
            if ($payment->payment_type === 'installment') {
                $installmentInfo = ' (Tranche ' . $payment->installment_number . '/' . $payment->total_installments . ')';
            }

            \App\Models\AccountingTransaction::create([
                'type' => 'income',
                'category' => 'Scolarité',
                'amount' => $payment->amount,
                'date' => now(),
                'title' => 'Paiement scolarité - ' . $candidate->prenom . ' ' . $candidate->nom . $installmentInfo,
                'description' => 'Paiement de scolarité pour la formation : ' . $formationLabel,
                'reference' => $payment->payment_reference,
                'payment_method' => 'Chariow',
                'student_name' => $candidate->prenom . ' ' . $candidate->nom,
                'training_module' => $formationLabel,
            ]);

            Log::info('Transaction comptable créée (Chariow)', [
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'student' => $candidate->prenom . ' ' . $candidate->nom
            ]);

            // Si 1ère tranche : créer le compte utilisateur et étudiant
            if ($payment->installment_number == 1) {
                $existingUser = DB::table('users')->where('email', $candidate->email)->first();
                $existingStudent = DB::table('students')->where('email', $candidate->email)->first();

                // Créer l'utilisateur si nécessaire
                if (!$existingUser) {
                    $userId = DB::table('users')->insertGetId([
                        'name' => $candidate->prenom . ' ' . $candidate->nom,
                        'email' => $candidate->email,
                        'password' => bcrypt('temporary_password_' . uniqid()),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    Log::info('✅ Utilisateur créé (Chariow)', ['user_id' => $userId, 'email' => $candidate->email]);
                } else {
                    $userId = $existingUser->id;
                    Log::info('ℹ️ Utilisateur existe déjà (Chariow)', ['user_id' => $userId, 'email' => $candidate->email]);
                }

                // Créer le profil étudiant si nécessaire
                if (!$existingStudent) {
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
                        'degree' => 'Licence',
                        'Level_education' => 'Licence',
                        'status' => 'active',
                        'city' => $candidate->ville ?? 'Abidjan',
                        'country' => $candidate->pays ?? 'Côte d\'Ivoire',
                        'profile_photo' => $candidate->photo ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('✅ Profil étudiant créé (Chariow)', [
                        'student_id' => $studentId,
                        'user_id' => $userId,
                        'email' => $candidate->email
                    ]);
                }

                // Générer token et envoyer email uniquement si c'est un nouveau compte
                if (!$existingUser || !$existingStudent) {
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

                    // Envoyer l'email de création de compte
                    $confirmationUrl = url('/student/confirm-registration/' . $confirmationToken);

                    Mail::send('emails.payment_confirmed', [
                        'candidate' => $candidate,
                        'payment' => $payment,
                        'accountCreationUrl' => $confirmationUrl,
                    ], function ($message) use ($candidate) {
                        $message->to($candidate->email)
                            ->subject('✅ Paiement confirmé - Créez votre compte EVC');
                    });

                    Log::info('✅ Email création compte envoyé (Chariow)', [
                        'email' => $candidate->email,
                        'confirmation_url' => $confirmationUrl
                    ]);
                } else {
                    Log::info('ℹ️ Utilisateur et étudiant existent déjà (Chariow)', ['email' => $candidate->email]);
                }
            }

            // Si 2ème tranche : envoyer email confirmation
            if ($payment->installment_number == 2) {
                Mail::send('emails.second_payment_confirmation', [
                    'candidate' => $candidate,
                    'payment' => $payment,
                ], function ($message) use ($candidate) {
                    $message->to($candidate->email)
                        ->subject('✅ Paiement 2ème tranche confirmé - EVC');
                });

                Log::info('✅ Email confirmation 2ème tranche envoyé (Chariow)', [
                    'email' => $candidate->email
                ]);
            }

            // Mettre à jour la préinscription
            DB::table('pre_registrations')
                ->where('id', $payment->pre_registration_id)
                ->update([
                    'status' => 'paid',
                    'updated_at' => now(),
                ]);

            // Rediriger vers la page de succès ou création de compte
            if ($payment->installment_number == 1 && !empty($confirmationToken)) {
                return redirect('/student/confirm-registration/' . $confirmationToken)
                    ->with('success', '✅ Paiement confirmé ! Créez votre mot de passe pour accéder à votre espace étudiant.');
            }

            return view('payment.success', compact('payment', 'candidate'));

        } catch (\Exception $e) {
            Log::error('❌ Erreur traitement retour Chariow', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('login')
                ->with('error', 'Une erreur est survenue lors de la confirmation du paiement. Contactez le support.');
        }
    }

    /**
     * Retour après annulation Chariow
     */
    public function chariowCancel(Request $request)
    {
        $reference = $request->input('reference');

        Log::info('🛒 Retour Chariow (annulé)', [
            'reference' => $reference,
            'all_params' => $request->all()
        ]);

        if ($reference) {
            $payment = DB::table('payments')
                ->where('payment_reference', $reference)
                ->first();

            if ($payment) {
                // Optionnel : marquer le paiement comme annulé
                DB::table('payments')
                    ->where('id', $payment->id)
                    ->update([
                        'status' => 'cancelled',
                        'updated_at' => now(),
                    ]);

                Log::info('ℹ️ Paiement marqué comme annulé', [
                    'payment_id' => $payment->id,
                    'reference' => $reference
                ]);
            }
        }

        return redirect()->route('login')
            ->with('error', '❌ Paiement annulé. Vous pouvez réessayer en utilisant le lien de paiement reçu par email.');
    }

    /**
     * Webhook Chariow (optionnel)
     */
    public function chariowWebhook(Request $request)
    {
        Log::info('🛒 Webhook Chariow reçu', $request->all());

        try {
            $chariow = new ChariowService();
            $result = $chariow->handleWebhook($request->all());

            if (!$result || !isset($result['reference'])) {
                Log::error('❌ Webhook Chariow invalide');
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

                // Enregistrer dans la comptabilité
                if ($candidate) {
                    $formationLabel = $candidate->choix_formation ?? 'Formation';
                    $installmentInfo = '';
                    if ($payment->payment_type === 'installment') {
                        $installmentInfo = ' (Tranche ' . $payment->installment_number . '/' . $payment->total_installments . ')';
                    }

                    \App\Models\AccountingTransaction::create([
                        'type' => 'income',
                        'category' => 'Scolarité',
                        'amount' => $payment->amount,
                        'date' => now(),
                        'title' => 'Paiement scolarité - ' . $candidate->prenom . ' ' . $candidate->nom . $installmentInfo,
                        'description' => 'Paiement de scolarité pour la formation : ' . $formationLabel,
                        'reference' => $payment->payment_reference,
                        'payment_method' => 'Chariow (Webhook)',
                        'student_name' => $candidate->prenom . ' ' . $candidate->nom,
                        'training_module' => $formationLabel,
                    ]);

                    Log::info('Transaction comptable créée (Chariow Webhook)', [
                        'payment_id' => $payment->id,
                        'amount' => $payment->amount,
                        'student' => $candidate->prenom . ' ' . $candidate->nom
                    ]);
                }

                if ($payment->installment_number == 1 && $candidate) {
                    // Logique de création de compte (similaire à chariowReturn)
                    Log::info('✅ Paiement confirmé via webhook', [
                        'payment_id' => $payment->id,
                        'reference' => $result['reference']
                    ]);
                }
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('❌ Erreur traitement webhook Chariow', [
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erreur serveur'], 500);
        }
    }
}
