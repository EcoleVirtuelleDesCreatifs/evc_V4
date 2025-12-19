<?php

namespace App\Helpers;

class ProfilePhotoHelper
{
    /**
     * Résout l'URL d'une photo de profil en vérifiant tous les chemins possibles.
     *
     * @param string|null $photoPath Le chemin stocké en base de données
     * @return string|null L'URL complète de la photo ou null si non trouvée
     */
    public static function getUrl(?string $photoPath): ?string
    {
        if (empty($photoPath)) {
            return null;
        }

        // Si c'est déjà une URL complète
        if (str_starts_with($photoPath, 'http://') || str_starts_with($photoPath, 'https://')) {
            return $photoPath;
        }

        $filename = basename($photoPath);

        // Si le chemin commence déjà par storage/, on retourne l'asset direct
        if (str_starts_with($photoPath, 'storage/')) {
            return asset($photoPath);
        }

        // Gestion spécifique des dossiers connus
        if (str_starts_with($photoPath, 'photos_preregistrations/')) {
            return asset('storage/' . $photoPath);
        }

        if (str_starts_with($photoPath, 'uploads/')) {
            return asset('storage/' . $photoPath);
        }

        // Si c'est juste un nom de fichier, on essaie les dossiers communs dans storage
        if ($filename === $photoPath) {
            // On privilégie le dossier des préinscriptions car c'est le plus courant pour les profils
            return asset('storage/photos_preregistrations/' . $filename);
        }

        // Par défaut, on tente via le storage
        return asset('storage/' . $photoPath);
    }

    /**
     * Retourne l'URL de la photo ou une image par défaut.
     *
     * @param string|null $photoPath Le chemin stocké en base de données
     * @param string $default L'URL de l'image par défaut
     * @return string L'URL de la photo ou l'image par défaut
     */
    public static function getUrlOrDefault(?string $photoPath, string $default = '/assets/img/default-avatar.png'): string
    {
        $url = self::getUrl($photoPath);
        return $url ?? asset($default);
    }
}
