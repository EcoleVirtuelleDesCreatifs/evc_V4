<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserProfileService
{
    /**
     * Récupère le profil complet d'un utilisateur
     */
    public function getUserProfile(int $userId): object
    {
        try {
            $user = DB::selectOne("
                SELECT 
                    id,
                    prenom as first_name,
                    nom as last_name,
                    email,
                    phone,
                    whatsapp,
                    country,
                    city,
                    district,
                    photo as profile_photo,
                    age,
                    education_level,
                    last_diploma,
                    biography,
                    expectations,
                    created_at,
                    updated_at
                FROM users 
                WHERE id = ?
            ", [$userId]);

            if ($user) {
                return (object) [
                    'id' => $user->id,
                    'first_name' => $user->first_name ?? 'Utilisateur',
                    'last_name' => $user->last_name ?? '',
                    'email' => $user->email ?? '',
                    'phone' => $user->phone ?? '',
                    'whatsapp' => $user->whatsapp ?? '',
                    'country' => $user->country ?? '',
                    'city' => $user->city ?? '',
                    'district' => $user->district ?? '',
                    'profile_photo' => $user->profile_photo,
                    'age' => $user->age ?? '',
                    'education_level' => $user->education_level ?? '',
                    'last_diploma' => $user->last_diploma ?? '',
                    'biography' => $user->biography ?? '',
                    'expectations' => $user->expectations ?? '',
                    'full_name' => trim(($user->first_name ?? 'Utilisateur') . ' ' . ($user->last_name ?? '')),
                    'current_level' => session('user_niveau', 'Non spécifié'),
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ];
            }

            return $this->getDefaultProfile();

        } catch (\Exception $e) {
            Log::error('Erreur récupération profil utilisateur: ' . $e->getMessage(), [
                'user_id' => $userId,
                'file' => __FILE__,
                'line' => __LINE__
            ]);
            
            return $this->getDefaultProfile();
        }
    }

    /**
     * Retourne un profil par défaut en cas d'erreur
     */
    private function getDefaultProfile(): object
    {
        return (object) [
            'id' => 0,
            'first_name' => session('user_prenom', 'Utilisateur'),
            'last_name' => session('user_nom', ''),
            'email' => session('user_email', ''),
            'phone' => session('user_telephone', ''),
            'whatsapp' => session('user_whatsapp', ''),
            'country' => session('user_pays', ''),
            'city' => session('user_ville', ''),
            'district' => session('user_district', ''),
            'profile_photo' => session('user_photo'),
            'age' => session('user_age', ''),
            'education_level' => session('user_education_level', ''),
            'last_diploma' => session('user_last_diploma', ''),
            'biography' => session('user_biography', ''),
            'expectations' => session('user_expectations', ''),
            'full_name' => trim(session('user_prenom', 'Utilisateur') . ' ' . session('user_nom', '')),
            'current_level' => session('user_niveau', 'Non spécifié'),
            'created_at' => null,
            'updated_at' => null
        ];
    }

    /**
     * Calcule les statistiques utilisateur pour la progression
     */
    public function getUserStats(array $tpStats): object
    {
        $totalTPRequired = 20;
        $completionPercentage = $tpStats['total_tp'] > 0 
            ? min(100, round(($tpStats['total_tp'] / $totalTPRequired) * 100, 1))
            : 0;

        return (object) [
            'completion_percentage' => $completionPercentage,
            'tp_realises' => $tpStats['total_tp'],
            'tp_restants' => max(0, $totalTPRequired - $tpStats['total_tp']),
            'progression_niveau' => $this->calculateProgressionLevel($tpStats['total_tp']),
            'eligible_certificat' => $tpStats['tp_valides'] >= 10
        ];
    }

    /**
     * Calcule le niveau de progression
     */
    private function calculateProgressionLevel(int $totalTP): string
    {
        if ($totalTP >= 20) return 'Expert';
        elseif ($totalTP >= 15) return 'Avancé';
        elseif ($totalTP >= 10) return 'Intermédiaire';
        elseif ($totalTP >= 5) return 'Débutant+';
        elseif ($totalTP >= 1) return 'Débutant';
        else return 'Nouveau';
    }
}
