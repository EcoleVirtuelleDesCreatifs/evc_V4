<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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
            'design_graphique' => 4,
            'design-graphique' => 4,

            'Community Management' => 4,
            'community_management' => 4,
            'community-manager' => 4,
            'community-management' => 4,

            'Design Graphique & Community Management' => 7,
            'Design Graphique & Community Manager' => 7,
            'design_graphique_community_management' => 7,
            'design_graphique_community_manager' => 7,
            'design-graphique-community-manager' => 7,

            'Intelligence Artificielle' => 4,
            'intelligence_artificielle' => 4,
            'intelligence-artificielle' => 4,

            'Gestion Informatique' => 4,
            'gestion_informatique' => 4,
            'gestion-informatique' => 4,
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

        $program = $studentRecord->program ?? null;
        $durationMonths = self::getDefaultDurationMonths($program);

        // Date d'inscription fiable
        $registrationCandidates = [];
        if ($studentRecord && Schema::hasColumn('students', 'registration_date') && !empty($studentRecord->registration_date)) {
            $registrationCandidates[] = $studentRecord->registration_date;
        }
        if ($studentRecord && !empty($studentRecord->created_at)) {
            $registrationCandidates[] = $studentRecord->created_at;
        }
        if (!empty($user->created_at)) {
            $registrationCandidates[] = $user->created_at;
        }

        $registrationDate = null;
        if (!empty($registrationCandidates)) {
            $registrationDate = collect($registrationCandidates)
                ->map(function ($d) {
                    try {
                        return Carbon::parse($d);
                    } catch (\Exception $e) {
                        return null;
                    }
                })
                ->filter()
                ->sort()
                ->first();
        }

        $computedExpiration = $registrationDate ? $registrationDate->copy()->addMonths($durationMonths) : null;

        // Expiration stockée (potentiellement prolongation)
        $storedExpiration = null;
        if ($studentRecord && !empty($studentRecord->expiration_date)) {
            try {
                $storedExpiration = Carbon::parse($studentRecord->expiration_date);
            } catch (\Exception $e) {
                $storedExpiration = null;
            }
        }

        // Détecter une expiration auto erronée basée sur "maintenant + durée"
        $shouldIgnoreStored = false;
        if ($storedExpiration && $computedExpiration) {
            $nowBased = Carbon::now()->addMonths($durationMonths);
            if ($storedExpiration->isSameDay($nowBased) && !$storedExpiration->isSameDay($computedExpiration)) {
                $shouldIgnoreStored = true;
            }
        }

        // Détecter une expiration auto obsolète basée sur users.created_at + durée (si la vraie date d'inscription diffère)
        if (!$shouldIgnoreStored && $storedExpiration && $computedExpiration && !empty($user->created_at) && $registrationDate) {
            $userCreatedAt = Carbon::parse($user->created_at);
            $userBased = $userCreatedAt->copy()->addMonths($durationMonths);
            if ($storedExpiration->isSameDay($userBased) && !$registrationDate->isSameDay($userCreatedAt)) {
                $shouldIgnoreStored = true;
            }
        }

        if ($storedExpiration && !$shouldIgnoreStored) {
            if ($computedExpiration) {
                return $storedExpiration->greaterThan($computedExpiration) ? $storedExpiration : $computedExpiration;
            }
            return $storedExpiration;
        }

        return $computedExpiration ?: Carbon::parse($user->created_at)->copy()->addMonths($durationMonths);
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
