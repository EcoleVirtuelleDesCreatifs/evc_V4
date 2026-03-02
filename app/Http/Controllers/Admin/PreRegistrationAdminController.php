<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdmissionApprovedRegistrationLink;
use App\Models\User;
use App\Models\AccountingTransaction;
use App\Services\TrainingQuoteGenerator;
use Carbon\Carbon;

class PreRegistrationAdminController extends Controller
{
    private function syncAcceptedToActiveIfEmailVerified(): void
    {
        // Synchroniser les statuts: (accepted|Validé) -> Actif si l'utilisateur a confirmé son compte
        try {
            $verifiedEmails = DB::table('users')->whereNotNull('email_verified_at')->pluck('email');
            if ($verifiedEmails->count() > 0) {
                $update = ['status' => 'Actif'];
                if (\Illuminate\Support\Facades\Schema::hasColumn('pre_registrations', 'activated_at')) {
                    $update['activated_at'] = now();
                }
                PreRegistration::whereIn('status', ['accepted', 'Validé'])
                    ->whereIn('email', $verifiedEmails)
                    ->update($update);
            }
        } catch (\Throwable $e) {
            Log::warning('Sync accepted->Actif failed', ['error' => $e->getMessage()]);
        }
    }

    private function baseQuery(Request $request)
    {
        $query = PreRegistration::query()->latest();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        if ($formation = $request->get('formation')) {
            $query->where('choix_formation', $formation);
        }

        return $query;
    }

    private function computeStats(Request $request): array
    {
        $statsBase = $this->baseQuery($request);

        return [
            'total' => (clone $statsBase)->count(),
            'pending' => (clone $statsBase)->whereIn('status', ['pending', 'en cours'])->count(),
            'accepted' => (clone $statsBase)->whereIn('status', ['accepted', 'Validé', 'Actif'])->count(),
            'rejected' => (clone $statsBase)->where('status', 'rejected')->count(),
        ];
    }

    private function renderList(Request $request, ?string $statusFilter = null)
    {
        $this->syncAcceptedToActiveIfEmailVerified();

        $query = $this->baseQuery($request);

        if (!empty($statusFilter)) {
            if ($statusFilter === 'pending') {
                $query->whereIn('status', ['pending', 'en cours']);
            } elseif ($statusFilter === 'accepted') {
                $query->whereIn('status', ['accepted', 'Validé', 'Actif']);
            } elseif ($statusFilter === 'rejected') {
                $query->where('status', 'rejected');
            } else {
                $query->where('status', $statusFilter);
            }
        } elseif ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $stats = $this->computeStats($request);
        $pres = $query->paginate(20)->withQueryString();

        return view('admin.preregistrations.index', compact('pres', 'stats'));
    }

    public function index(Request $request)
    {
        return $this->renderList($request);
    }

    public function pending(Request $request)
    {
        return $this->renderList($request, 'pending');
    }

    public function accepted(Request $request)
    {
        return $this->renderList($request, 'accepted');
    }

    public function rejected(Request $request)
    {
        return $this->renderList($request, 'rejected');
    }

    public function eligibles(Request $request)
    {
        $query = PreRegistration::query()->latest();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenom', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        if ($formation = $request->get('formation')) {
            $query->where('choix_formation', $formation);
        }

        $query->whereNotNull('date_inscription_souhaitee');

        if ($request->get('notified') === '0') {
            $query->whereNull('eligibility_notified_at');
        } elseif ($request->get('notified') === '1') {
            $query->whereNotNull('eligibility_notified_at');
        }

        $statsQuery = clone $query;
        $stats = [
            'total' => (int) $statsQuery->count(),
            'notified' => (int) (clone $statsQuery)->whereNotNull('eligibility_notified_at')->count(),
            'not_notified' => (int) (clone $statsQuery)->whereNull('eligibility_notified_at')->count(),
        ];

        $pres = $query->paginate(20)->withQueryString();

        return view('admin.preregistrations.eligibles', compact('pres', 'stats'));
    }

    public function notifyEligible(Request $request, $id)
    {
        $pre = PreRegistration::findOrFail($id);

        if (empty($pre->email)) {
            return redirect()->back()->with('error', 'Email du candidat introuvable.');
        }

        if (!empty($pre->eligibility_notified_at)) {
            return redirect()->back()->with('warning', 'Ce candidat a déjà été notifié.');
        }

        $formationName = $this->getFormationLabel($pre->choix_formation);
        $candidateName = trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? ''));
        $paymentDate = null;
        if (!empty($pre->date_inscription_souhaitee)) {
            try {
                $paymentDate = Carbon::parse($pre->date_inscription_souhaitee)->format('d/m/Y');
            } catch (\Throwable $e) {
                $paymentDate = (string) $pre->date_inscription_souhaitee;
            }
        }

        try {
            Mail::send('emails.preinscription_eligible_notification', [
                'candidateName' => $candidateName,
                'formationName' => $formationName,
                'paymentDate' => $paymentDate,
            ], function ($message) use ($pre) {
                $message->to($pre->email)
                    ->subject('✅ Votre candidature est éligible - EVC');
            });

            $pre->eligibility_notified_at = now();
            $pre->save();
        } catch (\Throwable $e) {
            Log::error('Erreur envoi mail éligibilité', [
                'pre_registration_id' => $pre->id,
                'email' => $pre->email,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', "Erreur lors de l'envoi de l'email.");
        }

        return redirect()->back()->with('success', 'Email d\'éligibilité envoyé.');
    }

    public function devis($id)
    {
        $pre = PreRegistration::findOrFail($id);

        $formationName = $this->getFormationLabel($pre->choix_formation);
        $totalAmount = (int) round((float) \App\Services\CinetPayService::getFormationPrice($formationName));

        $installment1Amount = 50000;
        $installment2Amount = max(0, $totalAmount - $installment1Amount);

        if ($formationName === 'Design Graphique') {
            $installment1Amount = 53500;
            $installment2Amount = 27000;
        } elseif ($formationName === 'Community Management') {
            $installment1Amount = 53500;
            $installment2Amount = 53500;
        } elseif ($formationName === 'Design Graphique & Community Management' || $formationName === 'Design Graphique & Community Manager') {
            $installment1Amount = 100000;
            $installment2Amount = 65000;
        } else {
            $installment2Amount = max(0, $totalAmount - $installment1Amount);
        }

        $quoteNumber = 'EVC-DEVIS-' . now()->format('Ymd') . '-' . strtoupper(substr(md5($pre->id . '|' . $pre->email . '|' . now()->timestamp), 0, 8));
        $issuedAt = now()->format('d/m/Y');
        $validUntil = now()->addDays(30)->format('d/m/Y');

        $duration = '3 Mois';
        if ($formationName === 'Design Graphique') {
            $duration = '4 Mois';
        } elseif ($formationName === 'Community Management') {
            $duration = '3 Mois';
        } elseif ($formationName === 'Design Graphique & Community Management' || $formationName === 'Design Graphique & Community Manager') {
            $duration = '7 Mois';
        } elseif ($formationName === 'Gestion Informatique') {
            $duration = '2 Mois';
        }

        $items = [
            [
                'label' => 'Tranche 1',
                'detail' => '1ère tranche (inscription)',
                'amount' => $installment1Amount,
            ],
            [
                'label' => 'Tranche 2',
                'detail' => '2ème tranche',
                'amount' => $installment2Amount,
            ],
        ];

        $generator = new TrainingQuoteGenerator();
        $result = $generator->generate([
            'quote_number' => $quoteNumber,
            'issued_at' => $issuedAt,
            'valid_until' => $validUntil,
            'candidate_name' => trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
            'candidate_email' => $pre->email ?? '',
            'candidate_phone' => $pre->whatsapp ?? '',
            'formation' => $formationName,
            'level' => $pre->niveau_dans_formation ?? null,
            'duration' => $duration,
            'total_amount' => $totalAmount,
            'items' => $items,
            'filename' => 'Devis_' . preg_replace('/\s+/', '_', trim(($pre->prenom ?? '') . '_' . ($pre->nom ?? ''))) . '_' . now()->format('Ymd') . '.pdf',
        ]);

        return response()->download($result['path'], $result['filename'])->deleteFileAfterSend(true);
    }

    public function show($id)
    {
        $pre = PreRegistration::findOrFail($id);

        $commercialAdmins = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admins')) {
                $commercialAdmins = DB::table('admins')
                    ->where('is_active', 1)
                    ->orderBy('name')
                    ->get(['id', 'name', 'email']);
            }
        } catch (\Throwable $e) {
        }

        return view('admin.preregistrations.show', compact('pre', 'commercialAdmins'));
    }

    public function edit($id)
    {
        $pre = PreRegistration::findOrFail($id);

        $commercialAdmins = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admins')) {
                $commercialAdmins = DB::table('admins')
                    ->where('is_active', 1)
                    ->orderBy('name')
                    ->get(['id', 'name', 'email']);
            }
        } catch (\Throwable $e) {
        }

        return view('admin.preregistrations.edit', compact('pre', 'commercialAdmins'));
    }

    public function update(Request $request, $id)
    {
        $pre = PreRegistration::findOrFail($id);

        $validated = $request->validate([
            'nom' => 'required|string|max:191',
            'prenom' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'whatsapp' => 'nullable|string|max:50',
            'age' => 'nullable|integer|min:1|max:120',
            'sexe' => 'nullable|string|max:50',
            'nationalite' => 'nullable|string|max:191',
            'ville' => 'nullable|string|max:191',
            'pays' => 'nullable|string|max:191',
            'niveau_etude' => 'nullable|string|max:191',
            'domaine_etude' => 'nullable|string|max:191',
            'competences' => 'nullable|string',
            'choix_formation' => 'required|string|in:design_graphique,community_management,design_graphique_community_manager,gestion_informatique,intelligence_artificielle,design_cm,design_graphique_community_management',
            'niveau_dans_formation' => 'nullable|string|in:aucune_notion,quelques_notions,me_perfectionner,Aucune notion,Certaines notions,Monter en compétence',
            'programme' => 'nullable|string|max:191',
            'how_known' => 'nullable|string|in:reseaux,ami,publicite,autre,Réseaux sociaux,Reseaux sociaux,Ami,Publicité,Publicite,Autre',
            'has_computer' => 'nullable|boolean',
            'has_smartphone' => 'nullable|boolean',
            'disponibilite' => 'nullable|string|in:semaine_soir,weekend,flexible,Semaine (soir),Week-end,Weekend,Flexible',
            'motivation' => 'nullable|string',
            'commercial_admin_id' => 'nullable|integer',
            'status' => 'required|string|in:pending,en cours,accepted,Validé,Actif,rejected,Rejeté,En attente,paid',
        ]);

        $validated['has_computer'] = (bool) ($validated['has_computer'] ?? false);
        $validated['has_smartphone'] = (bool) ($validated['has_smartphone'] ?? false);

        // Normaliser les valeurs legacy (évite les doublons et aligne avec /preinscription)
        $niveauLegacyMap = [
            'Aucune notion' => 'aucune_notion',
            'Certaines notions' => 'quelques_notions',
            'Monter en compétence' => 'me_perfectionner',
        ];
        if (!empty($validated['niveau_dans_formation']) && isset($niveauLegacyMap[$validated['niveau_dans_formation']])) {
            $validated['niveau_dans_formation'] = $niveauLegacyMap[$validated['niveau_dans_formation']];
        }

        $howKnownLegacyMap = [
            'Réseaux sociaux' => 'reseaux',
            'Reseaux sociaux' => 'reseaux',
            'Ami' => 'ami',
            'Publicité' => 'publicite',
            'Publicite' => 'publicite',
            'Autre' => 'autre',
        ];
        if (!empty($validated['how_known']) && isset($howKnownLegacyMap[$validated['how_known']])) {
            $validated['how_known'] = $howKnownLegacyMap[$validated['how_known']];
        }

        $disponibiliteLegacyMap = [
            'Semaine (soir)' => 'semaine_soir',
            'Week-end' => 'weekend',
            'Weekend' => 'weekend',
            'Flexible' => 'flexible',
        ];
        if (!empty($validated['disponibilite']) && isset($disponibiliteLegacyMap[$validated['disponibilite']])) {
            $validated['disponibilite'] = $disponibiliteLegacyMap[$validated['disponibilite']];
        }

        $statusLegacyMap = [
            'en cours' => 'pending',
            'En cours' => 'pending',
            'Rejeté' => 'rejected',
        ];
        if (!empty($validated['status']) && isset($statusLegacyMap[$validated['status']])) {
            $validated['status'] = $statusLegacyMap[$validated['status']];
        }

        $pre->update($validated);

        return redirect()->route('admin.preinscriptions.show', $pre->id)
            ->with('success', '✅ Candidature mise à jour avec succès.');
    }

    public function payment($id)
    {
        $pre = PreRegistration::findOrFail($id);

        $commercialAdmins = collect();
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('admins')) {
                $commercialAdmins = DB::table('admins')
                    ->where('is_active', 1)
                    ->orderBy('name')
                    ->get(['id', 'name', 'email']);
            }
        } catch (\Throwable $e) {
        }

        $payments = collect();
        if (\Illuminate\Support\Facades\Schema::hasTable('payments')) {
            $payments = DB::table('payments')
                ->where('pre_registration_id', $pre->id)
                ->orderByDesc('id')
                ->get();
        }

        $formationName = $this->getFormationLabel($pre->choix_formation);
        $totalAmount = (int) round((float) ($payments->max('total_amount') ?? 0));
        if ($totalAmount <= 0) {
            $totalAmount = (int) \App\Services\CinetPayService::getFormationPrice($formationName);
        }

        $amountPaid = (int) round((float) $payments->where('status', 'completed')->sum('amount'));
        $remaining = max(0, $totalAmount - $amountPaid);

        return view('admin.preregistrations.payment', compact('pre', 'payments', 'formationName', 'totalAmount', 'amountPaid', 'remaining', 'commercialAdmins'));
    }

    public function bulkStatus(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'integer',
                'action' => 'required|string'
            ]);

            // Vérifier que les IDs existent
            $ids = array_filter($request->ids, 'is_numeric');
            if (empty($ids)) {
                return redirect()->back()->with('warning', 'Aucun élément sélectionné.');
            }

            // Vérifier que l'action est valide
            $validActions = ['accepted', 'rejected', 'pending', 'Validé', 'Rejeté', 'En attente', 'delete'];
            if (!in_array($request->action, $validActions)) {
                return redirect()->back()->with('error', 'Action non valide.');
            }

            // Si l'action est delete, supprimer les enregistrements
            if ($request->action === 'delete') {
                $pres = PreRegistration::whereIn('id', $ids)->get();

                if ($pres->isEmpty()) {
                    return redirect()->back()->with('warning', 'Aucune pré-inscription trouvée avec ces IDs.');
                }

                // Supprimer les photos associées
                foreach ($pres as $pre) {
                    if ($pre->photo) {
                        $photoPath = storage_path('app/public/' . $pre->photo);
                        if (file_exists($photoPath)) {
                            @unlink($photoPath);
                        }
                    }
                }

                // Supprimer les enregistrements
                $count = PreRegistration::whereIn('id', $ids)->delete();

                return redirect()->route('admin.preinscriptions.index', $request->only(['q', 'formation', 'status']))
                    ->with('success', "✅ {$count} pré-inscription(s) supprimée(s) avec succès.");
            }

            // Sinon, mettre à jour le statut
            $count = PreRegistration::whereIn('id', $ids)->update(['status' => $request->action]);

            if ($count === 0) {
                return redirect()->back()->with('warning', 'Aucune pré-inscription trouvée avec ces IDs.');
            }

            Log::info('Statut groupé mis à jour', [
                'count' => $count,
                'action' => $request->action,
                'updated_by' => session('admin_id')
            ]);

            return redirect()->route('admin.preinscriptions.index', $request->only(['q', 'formation', 'status']))
                ->with('success', "✅ Statut mis à jour pour {$count} élément(s) sélectionné(s).");
        } catch (\Exception $e) {
            Log::error('Erreur action groupée pré-inscriptions', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', '❌ Erreur lors de l\'action groupée : ' . $e->getMessage());
        }
    }

    public function manualPayment(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'installment_number' => 'nullable|integer|in:1,2',
            'method' => 'nullable|string|max:50',
            'reference' => 'nullable|string|max:191',
            'paid_at' => 'nullable|date',
        ]);

        $pre = PreRegistration::findOrFail($id);

        $hadCompletedBefore = DB::table('payments')
            ->where('pre_registration_id', $pre->id)
            ->where('status', 'completed')
            ->exists();

        $amount = (int) round((float) $validated['amount']);
        $installmentNumber = $validated['installment_number'] ?? null;
        $method = $validated['method'] ?? 'Manual';
        $reference = $validated['reference'] ?? null;
        $paidAt = !empty($validated['paid_at']) ? \Carbon\Carbon::parse($validated['paid_at']) : now();

        $formationName = $this->getFormationLabel($pre->choix_formation);
        $totalAmount = \App\Services\CinetPayService::getFormationPrice($formationName);

        DB::beginTransaction();
        try {
            $manualTransactionId = 'MANUAL-' . strtoupper(uniqid());
            $paymentReference = $reference ?: ('EVC-MANUAL-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8)));

            // Si les paiements existent déjà (ex: créés lors de l'acceptation), on tente de compléter la tranche correspondante.
            // Sinon, on insère un paiement manual en completed.
            $updated = false;
            $paymentId = null;

            if ($installmentNumber) {
                $existingPending = DB::table('payments')
                    ->where('pre_registration_id', $pre->id)
                    ->where('installment_number', $installmentNumber)
                    ->whereIn('status', ['pending', 'PENDING'])
                    ->orderByDesc('id')
                    ->first();

                if ($existingPending) {
                    $expected = (int) round((float) ($existingPending->amount ?? 0));

                    // Paiement partiel sur une tranche: on ne clôture pas toute la tranche.
                    if ($expected > 0 && $amount < $expected) {
                        $completedRef = $paymentReference;
                        if ($completedRef === ($existingPending->payment_reference ?? null)) {
                            $completedRef = 'EVC-MANUAL-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
                        }

                        $paymentId = DB::table('payments')->insertGetId([
                            'pre_registration_id' => $pre->id,
                            'commercial_admin_id' => $pre->commercial_admin_id,
                            'amount' => $amount,
                            'currency' => 'XOF',
                            'payment_reference' => $completedRef,
                            'status' => 'completed',
                            'payer_email' => $pre->email,
                            'payer_name' => trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
                            'payment_type' => 'installment',
                            'installment_number' => $installmentNumber,
                            'total_installments' => 2,
                            'total_amount' => $totalAmount,
                            'transaction_id' => $manualTransactionId,
                            'paid_at' => $paidAt,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                        DB::table('payments')
                            ->where('id', $existingPending->id)
                            ->update([
                                'amount' => max(0, $expected - $amount),
                                'commercial_admin_id' => $existingPending->commercial_admin_id ?? $pre->commercial_admin_id,
                                'updated_at' => now(),
                            ]);

                        $updated = true;
                    } else {
                        // Paiement complet (ou supérieur) de la tranche: on clôture la tranche.
                        DB::table('payments')
                            ->where('id', $existingPending->id)
                            ->update([
                                'status' => 'completed',
                                'transaction_id' => $manualTransactionId,
                                'paid_at' => $paidAt,
                                'commercial_admin_id' => $existingPending->commercial_admin_id ?? $pre->commercial_admin_id,
                                'updated_at' => now(),
                            ]);
                        $paymentId = $existingPending->id;
                        $updated = true;

                        // Si montant saisi > montant attendu sur la tranche: enregistrer le surplus en paiement complémentaire.
                        if ($expected > 0 && $amount > $expected) {
                            $extraAmount = $amount - $expected;
                            $extraRef = 'EVC-MANUAL-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));
                            DB::table('payments')->insert([
                                'pre_registration_id' => $pre->id,
                                'commercial_admin_id' => $pre->commercial_admin_id,
                                'amount' => $extraAmount,
                                'currency' => 'XOF',
                                'payment_reference' => $extraRef,
                                'status' => 'completed',
                                'payer_email' => $pre->email,
                                'payer_name' => trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
                                'payment_type' => 'full',
                                'total_amount' => $totalAmount,
                                'transaction_id' => $manualTransactionId,
                                'paid_at' => $paidAt,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }

            if (!$updated) {
                $paymentType = $installmentNumber ? 'installment' : 'full';

                $paymentId = DB::table('payments')->insertGetId([
                    'pre_registration_id' => $pre->id,
                    'commercial_admin_id' => $pre->commercial_admin_id,
                    'amount' => $amount,
                    'currency' => 'XOF',
                    'payment_reference' => $paymentReference,
                    'status' => 'completed',
                    'payer_email' => $pre->email,
                    'payer_name' => trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
                    'payment_type' => $paymentType,
                    'installment_number' => $installmentNumber,
                    'total_installments' => $installmentNumber ? 2 : null,
                    'total_amount' => $totalAmount,
                    'transaction_id' => $manualTransactionId,
                    'paid_at' => $paidAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Mettre à jour le total_amount sur les paiements existants si manquant
            DB::table('payments')
                ->where('pre_registration_id', $pre->id)
                ->where(function ($q) {
                    $q->whereNull('total_amount')->orWhere('total_amount', 0);
                })
                ->update([
                    'total_amount' => $totalAmount,
                    'updated_at' => now(),
                ]);

            // Comptabilité
            AccountingTransaction::create([
                'type' => 'income',
                'category' => 'Scolarité',
                'amount' => $amount,
                'date' => $paidAt,
                'title' => 'Paiement manuel - ' . trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
                'description' => 'Paiement manuel de scolarité pour la formation : ' . $formationName,
                'reference' => $paymentReference,
                'payment_method' => $method,
                'student_name' => trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
                'training_module' => $formationName,
            ]);

            // Vérifier si solde atteint
            $paidSum = (int) round((float) DB::table('payments')
                ->where('pre_registration_id', $pre->id)
                ->where('status', 'completed')
                ->sum('amount'));

            if ($totalAmount > 0 && $paidSum >= $totalAmount) {
                $pre->status = 'paid';
                $pre->save();
            } else {
                $shouldAccept = !$hadCompletedBefore && !in_array($pre->status, ['accepted', 'Validé', 'Actif', 'paid'], true);
                if ($shouldAccept) {
                    $pre->status = 'accepted';
                    $pre->save();
                }
            }

            Log::info('Paiement manuel enregistré', [
                'pre_registration_id' => $pre->id,
                'payment_id' => $paymentId,
                'amount' => $amount,
                'installment_number' => $installmentNumber,
                'method' => $method,
                'admin_id' => session('admin_id'),
            ]);

            DB::commit();

            // Email à l'étudiant (ne doit pas bloquer l'enregistrement)
            try {
                if (!empty($pre->email)) {
                    $remaining = max(0, (int) $totalAmount - (int) $paidSum);

                    $isFirstManualPayment = !$hadCompletedBefore && in_array($pre->status, ['accepted', 'paid'], true);

                    // Si c'est le 1er paiement (cash/dépôt) d'une candidature soumise: envoyer acceptation + bouton création de compte.
                    if ($isFirstManualPayment) {
                        $existingUser = DB::table('users')->where('email', $pre->email)->first();
                        if (!$existingUser) {
                            DB::table('users')->insert([
                                'name' => trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
                                'email' => $pre->email,
                                'password' => bcrypt('temporary_password_' . uniqid()),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                        $timestamp = time();
                        $hash = md5($pre->email . config('app.key'));
                        $token = base64_encode($pre->email . '|' . $timestamp . '|' . $hash);
                        $accountCreationUrl = url('/student/confirm-registration/' . $token);

                        Mail::send('emails.manual_payment_acceptance', [
                            'candidateName' => trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
                            'formationName' => $formationName,
                            'amount' => $amount,
                            'installmentNumber' => $installmentNumber,
                            'method' => $method,
                            'reference' => $paymentReference,
                            'paidAt' => $paidAt instanceof \Carbon\Carbon ? $paidAt->format('d/m/Y H:i') : \Carbon\Carbon::parse($paidAt)->format('d/m/Y H:i'),
                            'totalAmount' => (int) $totalAmount,
                            'amountPaid' => (int) $paidSum,
                            'remaining' => (int) $remaining,
                            'accountCreationUrl' => $accountCreationUrl,
                        ], function ($message) use ($pre) {
                            $message->to($pre->email)
                                ->subject('Félicitations ! Créez votre compte EVC');
                        });
                    } else {
                        // Sinon: email reçu simple
                        Mail::send('emails.manual_payment_receipt', [
                            'candidateName' => trim(($pre->prenom ?? '') . ' ' . ($pre->nom ?? '')),
                            'formationName' => $formationName,
                            'amount' => $amount,
                            'installmentNumber' => $installmentNumber,
                            'method' => $method,
                            'reference' => $paymentReference,
                            'paidAt' => $paidAt instanceof \Carbon\Carbon ? $paidAt->format('d/m/Y H:i') : \Carbon\Carbon::parse($paidAt)->format('d/m/Y H:i'),
                            'totalAmount' => (int) $totalAmount,
                            'amountPaid' => (int) $paidSum,
                            'remaining' => (int) $remaining,
                        ], function ($message) use ($pre, $paymentReference) {
                            $message->to($pre->email)
                                ->subject('Reçu de paiement - ' . $paymentReference);
                        });
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Email reçu paiement manuel non envoyé', [
                    'pre_registration_id' => $pre->id,
                    'email' => $pre->email,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->route('admin.preinscriptions.index')
                ->with('success', '✅ Paiement manuel enregistré avec succès.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erreur paiement manuel', [
                'pre_registration_id' => $pre->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->withInput()->with('error', '❌ Erreur paiement manuel : ' . $e->getMessage());
        }
    }

    public function downloadPhoto($id)
    {
        $pre = PreRegistration::findOrFail($id);
        if (!$pre->photo) {
            return redirect()->back()->with('error', 'Aucune photo disponible.');
        }
        $path = storage_path('app/public/' . $pre->photo);
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Fichier photo introuvable.');
        }
        return response()->download($path, 'photo_preinscription_' . $pre->id . '.' . pathinfo($path, PATHINFO_EXTENSION));
    }

    public function validateOne($id)
    {
        $pre = PreRegistration::findOrFail($id);
        // 1) Marquer Validé
        $pre->status = 'Validé';
        $pre->save();

        // 2) Créer ou récupérer l'utilisateur lié à cette candidature
        $user = User::where('email', $pre->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = trim(($pre->prenom ? $pre->prenom . ' ' : '') . ($pre->nom ?? '')) ?: $pre->email;
            $user->email = $pre->email;
            // Mot de passe temporaire aléatoire (sera remplacé lors de la confirmation)
            $user->password = bcrypt(str()->random(32));
            $user->save();
        }

        // 2.5) Créer ou mettre à jour l'enregistrement student
        $student = \App\Models\Student::where('user_id', $user->id)->first();
        if (!$student) {
            $student = new \App\Models\Student();
            $student->user_id = $user->id;
        }

        // Mapper les champs de pré-inscription vers student
        $student->first_name = $pre->prenom;
        $student->last_name = $pre->nom;
        $student->email = $pre->email;
        $student->phone = $pre->whatsapp ?? null;
        $student->whatsapp = $pre->whatsapp ?? null;
        $student->age = $pre->age ?? null;
        $student->date_of_birth = $pre->date_naissance ?? null;

        // Mapper le sexe (F/M) vers gender (Femme/Homme/Autre)
        if (!empty($pre->sexe)) {
            $genderMap = [
                'F' => 'Femme',
                'M' => 'Homme',
                'f' => 'Femme',
                'm' => 'Homme',
                'Femme' => 'Femme',
                'Homme' => 'Homme',
                'Autre' => 'Autre',
            ];
            $student->gender = $genderMap[$pre->sexe] ?? null;
        } else {
            $student->gender = null;
        }

        $student->city = $pre->ville ?? null;
        $student->country = $pre->pays ?? 'Côte d\'Ivoire';
        $student->program = $pre->choix_formation ?? null;
        $student->level = $pre->niveau_dans_formation ?? null;
        $student->Level_education = $pre->niveau_etude ?? null;
        $student->degree = $pre->niveau_etude ?? null; // Ajout du champ degree
        $student->profile_photo = $pre->photo ?? null;
        $student->status = 'active';

        // Générer un student_id unique si nouveau
        if (!$student->exists || empty($student->student_id)) {
            $year = date('Y');
            $lastStudent = \App\Models\Student::where('student_id', 'like', "EVC{$year}%")
                ->orderBy('student_id', 'desc')
                ->first();

            if ($lastStudent && preg_match('/EVC' . $year . '(\d+)/', $lastStudent->student_id, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }

            $student->student_id = 'EVC' . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        $student->save();

        // 3) Générer un lien unique de création de compte (valide 24h)
        $email = $pre->email;
        $timestamp = time();
        $hash = md5($email . config('app.key'));
        $token = base64_encode($email . '|' . $timestamp . '|' . $hash);
        $registerUrl = route('student.confirm-registration', ['token' => $token]);

        // 4) Envoyer l'e-mail de félicitations avec le lien
        try {
            Mail::to($pre->email)->send(new AdmissionApprovedRegistrationLink($pre, $registerUrl));
        } catch (\Throwable $e) {
            Log::error('Echec envoi mail de validation de candidature', ['error' => $e->getMessage(), 'pre_id' => $pre->id]);
            return redirect()->back()->with('warning', "Candidature validée mais l'e-mail n'a pas pu être envoyé.");
        }

        return redirect()->back()->with('success', 'Pré-inscription validée, profil étudiant créé et e-mail envoyé au candidat.');
    }

    public function destroy($id)
    {
        try {
            $pre = PreRegistration::findOrFail($id);
            $name = $pre->prenom . ' ' . $pre->nom;
            $email = $pre->email;

            // Supprimer la photo associée si elle existe
            if ($pre->photo) {
                $photoPath = storage_path('app/public/' . $pre->photo);
                if (file_exists($photoPath)) {
                    @unlink($photoPath);
                }
            }

            $pre->delete();

            Log::info('Pré-inscription supprimée', [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'deleted_by' => session('admin_id')
            ]);

            return redirect()->route('admin.preinscriptions.index')
                ->with('success', "✅ Pré-inscription de {$name} ({$email}) supprimée avec succès.");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de pré-inscription', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', '❌ Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function resendRegistrationLink($id)
    {
        $pre = PreRegistration::findOrFail($id);

        // Regénérer un token 24h
        $email = $pre->email;
        $timestamp = time();
        $hash = md5($email . config('app.key'));
        $token = base64_encode($email . '|' . $timestamp . '|' . $hash);
        $registerUrl = route('student.confirm-registration', ['token' => $token]);

        try {
            Mail::to($pre->email)->send(new AdmissionApprovedRegistrationLink($pre, $registerUrl));
        } catch (\Throwable $e) {
            Log::error('Echec renvoi lien d\'inscription', ['error' => $e->getMessage(), 'pre_id' => $pre->id]);
            return redirect()->back()->with('warning', "Le lien n'a pas pu être renvoyé. Veuillez réessayer.");
        }

        return redirect()->back()->with('success', 'Lien de création de compte renvoyé au candidat.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'preinscriptions_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = [
            'id',
            'nom',
            'prenom',
            'age',
            'email',
            'whatsapp',
            'pays',
            'niveau_etude',
            'choix_formation',
            'niveau_dans_formation',
            'date_inscription_souhaitee',
            'has_computer',
            'has_smartphone',
            'disponibilite',
            'motivation',
            'status',
            'created_at'
        ];

        $search = $request->get('q');
        $formation = $request->get('formation');
        $status = $request->get('status');

        $callback = function () use ($columns, $search, $formation, $status) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $columns);

            $base = PreRegistration::query();
            if ($search) {
                $base->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('whatsapp', 'like', "%{$search}%");
                });
            }
            if ($formation) {
                $base->where('choix_formation', $formation);
            }
            if ($status) {
                $base->where('status', $status);
            }

            $base->orderBy('id', 'desc')->chunk(500, function ($chunk) use ($handle, $columns) {
                foreach ($chunk as $pre) {
                    $row = [];
                    foreach ($columns as $col) {
                        $val = $pre->{$col} ?? '';
                        if (is_bool($val)) {
                            $val = $val ? '1' : '0';
                        }
                        $row[] = $val;
                    }
                    fputcsv($handle, $row);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Accepter une candidature et créer les paiements (PRODUCTION: 50 000 + 27 000 FCFA)
     */
    public function acceptCandidate(Request $request, $id)
    {
        try {
            $pre = PreRegistration::findOrFail($id);

            if ($pre->status === 'accepted') {
                return redirect()->back()->with('warning', 'Cette candidature est déjà acceptée.');
            }

            $pre->status = 'accepted';
            $pre->save();

            $formationName = $this->getFormationLabel($pre->choix_formation);
            $totalAmount = \App\Services\CinetPayService::getFormationPrice($formationName);

            $paymentMode = $request->input('payment_mode', 'installment');

            if ($paymentMode === 'installment') {
                $installment1Amount = 50000;
                $installment2Amount = 27000;
                if ($formationName === 'Design Graphique') {
                    $installment1Amount = 53500;
                    $installment2Amount = 27000;
                } elseif ($formationName === 'Community Management') {
                    $installment1Amount = 53500;
                    $installment2Amount = 53500;
                } elseif ($formationName === 'Design Graphique & Community Management') {
                    $installment1Amount = 100000;
                    $installment2Amount = 65000;
                }

                // Paiement par tranche (PRODUCTION)
                $firstInstallmentRef = 'EVC-PAY-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

                $firstInstallmentId = DB::table('payments')->insertGetId([
                    'pre_registration_id' => $pre->id,
                    'commercial_admin_id' => $pre->commercial_admin_id,
                    'amount' => $installment1Amount,
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

                $secondInstallmentRef = 'EVC-PAY-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

                DB::table('payments')->insert([
                    'pre_registration_id' => $pre->id,
                    'commercial_admin_id' => $pre->commercial_admin_id,
                    'amount' => $installment2Amount,
                    'currency' => 'XOF',
                    'payment_reference' => $secondInstallmentRef,
                    'status' => 'pending',
                    'payer_email' => $pre->email,
                    'payer_name' => $pre->prenom . ' ' . $pre->nom,
                    'expires_at' => now()->addMonths(2),
                    'payment_type' => 'installment',
                    'installment_number' => 2,
                    'total_installments' => 2,
                    'total_amount' => $totalAmount,
                    'parent_payment_id' => $firstInstallmentId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Toujours utiliser la page de paiement interne (comme Design Graphique)
                $paymentUrl = url('/evc/payment/' . $firstInstallmentRef);
                $message = '✅ Candidature acceptée ! Email envoyé avec lien 1ère tranche.';

                $payment = (object)[
                    'amount' => $installment1Amount,
                    'installment2_amount' => $installment2Amount,
                    'payment_reference' => $firstInstallmentRef,
                    'expires_at' => now()->addDays(7)->format('d/m/Y'),
                    'payment_type' => 'installment',
                    'total_amount' => $totalAmount,
                ];
            } else {
                // Paiement unique
                $paymentReference = 'EVC-PAY-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 8));

                DB::table('payments')->insert([
                    'pre_registration_id' => $pre->id,
                    'commercial_admin_id' => $pre->commercial_admin_id,
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
                $message = '✅ Candidature acceptée ! Email envoyé avec lien de paiement unique.';

                $payment = (object)[
                    'amount' => $totalAmount,
                    'payment_reference' => $paymentReference,
                    'expires_at' => now()->addDays(7)->format('d/m/Y'),
                    'payment_type' => 'full',
                    'total_amount' => $totalAmount,
                ];
            }

            Mail::to($pre->email)->send(new \App\Mail\CandidatureAcceptee($pre, $paymentUrl, $payment));

            Log::info('Candidature acceptée avec paiement', [
                'pre_id' => $pre->id,
                'payment_mode' => $paymentMode
            ]);

            return redirect()->route('admin.preinscriptions.index')->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Erreur acceptation candidature', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', '❌ Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Rejeter une candidature
     */
    public function rejectCandidate($id)
    {
        try {
            $pre = PreRegistration::findOrFail($id);
            $pre->status = 'rejected';
            $pre->save();

            return redirect()->route('admin.preinscriptions.index')
                ->with('success', '✅ Candidature rejetée.');
        } catch (\Exception $e) {
            Log::error('Erreur rejet candidature', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', '❌ Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Obtenir le label de formation formaté
     */
    public function getFormationLabel($choix)
    {
        $mapping = [
            'design_graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'design_cm' => 'Design Graphique & Community Management',
            'design_graphique_community_management' => 'Design Graphique & Community Management',
            'gestion_informatique' => 'Gestion Informatique',
            'intelligence_artificielle' => 'Intelligence Artificielle'
        ];

        return $mapping[$choix] ?? $choix;
    }
}
