<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeactivateUnpaidAccounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:deactivate-unpaid {--dry-run : Afficher les comptes qui seraient désactivés sans les désactiver}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Désactive automatiquement les comptes dont la 2ème tranche n\'a pas été payée après le délai';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('🔍 Recherche des comptes à désactiver...');

        // Trouver les rappels envoyés il y a 7 jours ou plus
        $deadline = Carbon::now()->subDays(7);

        $reminders = DB::table('second_installment_reminders')
            ->where('sent_at', '<=', $deadline)
            ->get();

        if ($reminders->isEmpty()) {
            $this->info('✅ Aucun compte à désactiver pour aujourd\'hui.');
            return 0;
        }

        $this->info("📋 Trouvé {$reminders->count()} rappel(s) expiré(s).");

        $deactivated = 0;
        $errors = 0;

        foreach ($reminders as $reminder) {
            try {
                // Récupérer le paiement
                $firstPayment = DB::table('payments')
                    ->where('id', $reminder->payment_id)
                    ->first();

                if (!$firstPayment) {
                    $this->error("❌ Paiement introuvable: {$reminder->payment_id}");
                    $errors++;
                    continue;
                }

                // Récupérer la 2ème tranche
                $secondPayment = DB::table('payments')
                    ->where('parent_payment_id', $firstPayment->id)
                    ->where('installment_number', 2)
                    ->first();

                if (!$secondPayment) {
                    $this->error("❌ 2ème tranche introuvable pour payment_id: {$firstPayment->id}");
                    $errors++;
                    continue;
                }

                // Vérifier si la 2ème tranche est déjà payée
                if ($secondPayment->status === 'completed') {
                    $this->warn("⏭️  2ème tranche déjà payée (payment_id: {$firstPayment->id})");

                    // Supprimer le rappel car le paiement est effectué
                    DB::table('second_installment_reminders')
                        ->where('id', $reminder->id)
                        ->delete();

                    continue;
                }

                // Récupérer le candidat et son compte user
                $candidate = DB::table('pre_registrations')
                    ->where('id', $firstPayment->pre_registration_id)
                    ->first();

                if (!$candidate) {
                    $this->error("❌ Candidat introuvable: {$firstPayment->pre_registration_id}");
                    $errors++;
                    continue;
                }

                // Trouver le compte utilisateur
                $user = DB::table('users')
                    ->where('email', $candidate->email)
                    ->first();

                if (!$user) {
                    $this->warn("⚠️  Utilisateur non trouvé pour {$candidate->email}");
                    continue;
                }

                // Vérifier si le compte est déjà désactivé
                if ($user->is_active == 0) {
                    $this->warn("⏭️  Compte déjà désactivé: {$user->email}");
                    continue;
                }

                if ($dryRun) {
                    $this->line("🔍 [DRY RUN] Compte serait désactivé: {$user->email} ({$candidate->prenom} {$candidate->nom})");
                    $deactivated++;
                    continue;
                }

                // Désactiver le compte
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'is_active' => 0,
                        'deactivated_at' => now(),
                        'deactivation_reason' => 'Non paiement 2ème tranche après délai',
                        'updated_at' => now()
                    ]);

                // Marquer le paiement comme expiré
                DB::table('payments')
                    ->where('id', $secondPayment->id)
                    ->update([
                        'status' => 'expired',
                        'updated_at' => now()
                    ]);

                $this->info("✅ Compte désactivé: {$user->email} ({$candidate->prenom} {$candidate->nom})");
                $deactivated++;

                Log::warning('Compte désactivé pour non paiement 2ème tranche', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'payment_id' => $secondPayment->id,
                    'deactivated_at' => now()
                ]);

            } catch (\Exception $e) {
                $this->error("❌ Erreur pour reminder_id {$reminder->id}: " . $e->getMessage());
                $errors++;

                Log::error('Erreur désactivation compte', [
                    'reminder_id' => $reminder->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("\n📊 Résumé:");
        $this->info("✅ Comptes désactivés: $deactivated");
        if ($errors > 0) {
            $this->error("❌ Erreurs: $errors");
        }

        return 0;
    }
}
