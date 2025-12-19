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

        // Liste des chemins possibles à vérifier (dans l'ordre de priorité)
        $possiblePaths = [
            // Chemin direct dans public/
            'uploads/photos/' . $filename,
            'photos_preregistrations/' . $filename,
            
            // Chemin complet si déjà présent
            $photoPath,
            
            // Chemins storage
            'storage/profile_photos/' . $filename,
            'storage/' . $photoPath,
            'storage/uploads/photos/' . $filename,
        ];

        // Vérifier chaque chemin
        foreach ($possiblePaths as $path) {
            $fullPath = public_path($path);
            if (file_exists($fullPath)) {
                return asset($path);
            }
        }

        // Fallback: retourner le chemin le plus probable
        if (str_starts_with($photoPath, 'uploads/')) {
            return asset($photoPath);
        }
        
        if (str_starts_with($photoPath, 'photos_preregistrations/')) {
            return asset('storage/' . $photoPath);
        }

        // Par défaut, essayer uploads/photos/
        return asset('uploads/photos/' . $filename);
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
