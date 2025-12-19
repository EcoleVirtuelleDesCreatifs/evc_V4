<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SecondInstallmentReminderAutomatic;
use Carbon\Carbon;

class SendSecondInstallmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:send-second-installment-reminders {--dry-run : Afficher les emails qui seraient envoyés sans les envoyer}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie automatiquement les rappels de 2ème tranche aux étudiants ayant payé la 1ère il y a 2 mois';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('🔍 Recherche des paiements 1ère tranche ayant 2 mois...');

        // Trouver les paiements de 1ère tranche payés il y a exactement 60 jours (2 mois)
        $twoMonthsAgo = Carbon::now()->subDays(60);

        $firstPayments = DB::table('payments')
            ->where('payment_type', 'installment')
            ->where('installment_number', 1)
            ->where('status', 'completed')
            ->whereDate('paid_at', $twoMonthsAgo->format('Y-m-d'))
            ->get();

        if ($firstPayments->isEmpty()) {
            $this->info('✅ Aucun paiement trouvé pour aujourd\'hui.');
            return 0;
        }

        $this->info("📧 Trouvé {$firstPayments->count()} paiement(s) à traiter.");

        $sent = 0;
        $errors = 0;

        foreach ($firstPayments as $firstPayment) {
            try {
                // Récupérer le candidat
                $candidate = DB::table('pre_registrations')
                    ->where('id', $firstPayment->pre_registration_id)
                    ->first();

                if (!$candidate) {
                    $this->error("❌ Candidat introuvable pour payment_id: {$firstPayment->id}");
                    $errors++;
                    continue;
                }

                // Récupérer le paiement de la 2ème tranche
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
                    $this->warn("⏭️  2ème tranche déjà payée pour {$candidate->prenom} {$candidate->nom}");
                    continue;
                }

                // Vérifier si un email de rappel n'a pas déjà été envoyé
                $reminderSent = DB::table('second_installment_reminders')
                    ->where('payment_id', $firstPayment->id)
                    ->exists();

                if ($reminderSent) {
                    $this->warn("⏭️  Email déjà envoyé pour {$candidate->prenom} {$candidate->nom}");
                    continue;
                }

                if ($dryRun) {
                    $this->line("🔍 [DRY RUN] Email serait envoyé à: {$candidate->email} ({$candidate->prenom} {$candidate->nom})");
                    $sent++;
                    continue;
                }

                // Envoyer l'email
                $paymentUrl = url('/evc/payment/' . $secondPayment->payment_reference);
                $daysRemaining = 7; // 7 jours après le rappel

                Mail::to($candidate->email)->send(
                    new SecondInstallmentReminderAutomatic(
                        $candidate,
                        $secondPayment,
                        $paymentUrl,
                        $daysRemaining
                    )
                );

                // Enregistrer qu'un rappel a été envoyé
                DB::table('second_installment_reminders')->insert([
                    'payment_id' => $firstPayment->id,
                    'candidate_email' => $candidate->email,
                    'sent_at' => now(),
                    'days_remaining' => $daysRemaining,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $this->info("✅ Email envoyé à: {$candidate->email} ({$candidate->prenom} {$candidate->nom})");
                $sent++;

                Log::info('Rappel 2ème tranche envoyé automatiquement', [
                    'payment_id' => $firstPayment->id,
                    'candidate_email' => $candidate->email,
                    'sent_at' => now()
                ]);

            } catch (\Exception $e) {
                $this->error("❌ Erreur pour payment_id {$firstPayment->id}: " . $e->getMessage());
                $errors++;

                Log::error('Erreur envoi rappel 2ème tranche', [
                    'payment_id' => $firstPayment->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        $this->info("\n📊 Résumé:");
        $this->info("✅ Emails envoyés: $sent");
        if ($errors > 0) {
            $this->error("❌ Erreurs: $errors");
        }

        return 0;
    }
}
