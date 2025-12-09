<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\AdminNotification;

class AdminNotificationService
{
    /**
     * Envoyer une notification aux admins selon leurs préférences
     */
    public static function send($type, $subject, $data = [])
    {
        try {
            // Mapper les types de notifications
            $preferenceMap = [
                'new_registration' => 'new_registrations',
                'new_payment' => 'new_payments',
                'document_submitted' => 'documents_submitted',
                'project_completed' => 'projects_completed',
                'system_alert' => 'system_alerts',
                'backup_completed' => 'backups',
                'weekly_report' => 'weekly_reports',
                'team_activity' => 'team_activities',
            ];

            $preferenceKey = $preferenceMap[$type] ?? $type;

            // Récupérer tous les admins
            $admins = DB::table('admins')
                ->where('is_active', true)
                ->whereNotNull('email')
                ->get();

            $sentCount = 0;

            foreach ($admins as $admin) {
                // Vérifier les préférences de notification
                $preferences = [];
                if (isset($admin->notification_preferences)) {
                    $preferences = json_decode($admin->notification_preferences, true) ?? [];
                }

                // Valeurs par défaut si pas de préférences
                $defaultPreferences = [
                    'new_registrations' => true,
                    'new_payments' => true,
                    'documents_submitted' => true,
                    'projects_completed' => false,
                    'system_alerts' => true,
                    'backups' => true,
                    'weekly_reports' => false,
                    'team_activities' => false,
                ];

                $isEnabled = $preferences[$preferenceKey] ?? $defaultPreferences[$preferenceKey] ?? false;

                // Envoyer l'email si la notification est activée
                if ($isEnabled) {
                    Mail::to($admin->email)->send(
                        new AdminNotification($subject, $type, $data)
                    );
                    $sentCount++;
                }
            }

            Log::info("Notification envoyée", [
                'type' => $type,
                'admins_notified' => $sentCount
            ]);

            return $sentCount;

        } catch (\Exception $e) {
            Log::error("Erreur envoi notification: " . $e->getMessage(), [
                'type' => $type,
                'trace' => $e->getTraceAsString()
            ]);
            return 0;
        }
    }

    /**
     * Envoyer une notification de nouvelle inscription
     */
    public static function newRegistration($studentData)
    {
        return self::send('new_registration', 'Nouvelle inscription', [
            'student' => $studentData
        ]);
    }

    /**
     * Envoyer une notification de nouveau paiement
     */
    public static function newPayment($paymentData)
    {
        return self::send('new_payment', 'Nouveau paiement reçu', [
            'payment' => $paymentData
        ]);
    }

    /**
     * Envoyer une notification de document soumis
     */
    public static function documentSubmitted($documentData)
    {
        return self::send('document_submitted', 'Document soumis', [
            'document' => $documentData
        ]);
    }

    /**
     * Envoyer une notification de projet terminé
     */
    public static function projectCompleted($projectData)
    {
        return self::send('project_completed', 'Projet terminé', [
            'project' => $projectData
        ]);
    }

    /**
     * Envoyer une alerte système
     */
    public static function systemAlert($message)
    {
        return self::send('system_alert', 'Alerte système', [
            'message' => $message
        ]);
    }

    /**
     * Envoyer une notification de sauvegarde
     */
    public static function backupCompleted($backupData)
    {
        return self::send('backup_completed', 'Sauvegarde effectuée', [
            'backup' => $backupData
        ]);
    }

    /**
     * Envoyer un rapport hebdomadaire
     */
    public static function weeklyReport($statsData)
    {
        return self::send('weekly_report', 'Rapport hebdomadaire', [
            'stats' => $statsData
        ]);
    }

    /**
     * Envoyer une notification d'activité d'équipe
     */
    public static function teamActivity($activityData)
    {
        return self::send('team_activity', 'Activité d\'équipe', [
            'admin' => $activityData
        ]);
    }
}
