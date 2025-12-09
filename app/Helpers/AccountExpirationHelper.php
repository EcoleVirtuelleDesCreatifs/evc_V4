<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountExpirationHelper
{
    /**
     * Vérifier si le compte d'un utilisateur est expiré
     * Vérifie d'abord expiration_date dans students, sinon fallback sur created_at + 4 mois
     */
    public static function isAccountExpired($user = null): bool
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        $expirationDate = self::getExpirationDate($user);
        $now = Carbon::now();

        return $now->greaterThan($expirationDate);
    }

    /**
     * Obtenir les jours restants avant expiration
     */
    public static function getDaysRemaining($user = null): int
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return 0;
        }

        $expirationDate = self::getExpirationDate($user);
        $now = Carbon::now();

        if ($expirationDate->isFuture()) {
            return (int) $now->diffInDays($expirationDate);
        }

        return 0; // Expiré
    }

    /**
     * Vérifier si l'expiration approche (moins de 30 jours)
     */
    public static function isExpiringSoon($user = null): bool
    {
        $daysRemaining = self::getDaysRemaining($user);
        return $daysRemaining > 0 && $daysRemaining <= 30;
    }

    /**
     * Vérifier si l'utilisateur peut créer/soumettre du contenu
     */
    public static function canSubmitContent($user = null): bool
    {
        return !self::isAccountExpired($user);
    }

    /**
     * Obtenir la durée par défaut selon le programme
     */
    private static function getDefaultDurationMonths($program = null): int
    {
        $durations = [
            'Design Graphique' => 4,
            'Community Management' => 3,
            'Intelligence Artificielle' => 4,
            'Gestion Informatique' => 4,
        ];

        if ($program && isset($durations[$program])) {
            return $durations[$program];
        }

        // Par défaut : 4 mois
        return 4;
    }

    /**
     * Obtenir la date d'expiration
     * Priorité : students.expiration_date > created_at + durée selon programme
     */
    public static function getExpirationDate($user = null): Carbon
    {
        if (!$user) {
            $user = Auth::user();
        }

        // Essayer de récupérer depuis la table students
        $studentRecord = DB::table('students')
            ->where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        if ($studentRecord && !empty($studentRecord->expiration_date)) {
            return Carbon::parse($studentRecord->expiration_date);
        }

        // Fallback : created_at + durée selon le programme
        $accountCreatedAt = Carbon::parse($user->created_at);

        // Déterminer la durée selon le programme
        $program = $studentRecord->program ?? null;
        $durationMonths = self::getDefaultDurationMonths($program);

        return $accountCreatedAt->copy()->addMonths($durationMonths);
    }

    /**
     * Désactiver automatiquement le compte si expiré OU réactiver si encore valide
     * Ne met PAS de raison de désactivation (pour différencier d'une désactivation manuelle)
     */
    public static function checkAndDeactivateIfExpired($user = null): bool
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        $isExpired = self::isAccountExpired($user);

        if ($isExpired) {
            // Désactiver le compte si expiré
            // NE PAS mettre de deactivation_reason pour différencier l'expiration d'une désactivation manuelle
            DB::table('students')
                ->where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('status', 'active')
                ->update([
                    'status' => 'inactive',
                    'updated_at' => now()
                    // Pas de deactivation_reason ni deactivated_at
                ]);

            return true; // Compte désactivé par expiration
        } else {
            // Réactiver automatiquement si le compte n'est plus expiré
            // SAUF si désactivation manuelle (avec deactivation_reason)
            DB::table('students')
                ->where(function($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('status', 'inactive')
                ->whereNull('deactivation_reason') // Seulement si pas de désactivation manuelle
                ->update([
                    'status' => 'active',
                    'updated_at' => now()
                ]);

            return false; // Compte actif ou réactivé
        }
    }
}
