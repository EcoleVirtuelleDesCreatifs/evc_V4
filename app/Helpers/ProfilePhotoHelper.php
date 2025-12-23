<?php

namespace App\Helpers;

use App\Models\MediaUrl;

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

        // Normaliser tous les chemins relatifs via MediaUrl (force storage/app/public)
        if (str_starts_with($photoPath, 'storage/')) {
            return MediaUrl::fromPath($photoPath);
        }

        if (str_starts_with($photoPath, 'photos_preregistrations/') || str_starts_with($photoPath, 'uploads/')) {
            return MediaUrl::fromPath($photoPath);
        }

        // Si c'est juste un nom de fichier, on essaie les dossiers communs dans storage
        if ($filename === $photoPath) {
            // On privilégie le dossier des préinscriptions car c'est le plus courant pour les profils
            return MediaUrl::fromPath('photos_preregistrations/' . $filename);
        }

        // Par défaut, on tente via le storage
        return MediaUrl::fromPath($photoPath);
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
