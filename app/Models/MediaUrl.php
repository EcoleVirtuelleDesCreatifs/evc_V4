<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;

class MediaUrl
{
    public static function fromPath($path): ?string
    {
        $path = (string) ($path ?? '');

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        $path = ltrim($path, '/');

        if (Str::startsWith($path, 'storage/app/public/')) {
            $path = Str::after($path, 'storage/app/public/');
        }

        if (Str::startsWith($path, 'public/storage/')) {
            $path = Str::after($path, 'public/storage/');
        }

        if (Str::startsWith($path, 'storage/')) {
            $path = Str::after($path, 'storage/');
        }

        $path = ltrim($path, '/');
        $parts = explode('/', $path);
        $filename = array_pop($parts);
        $encodedFilename = rawurlencode((string) $filename);
        $encodedPath = implode('/', $parts) . ($parts ? '/' : '') . $encodedFilename;

        try {
            $req = request();
            $fallbackBase = rtrim($req->getSchemeAndHttpHost() . $req->getBaseUrl(), '/');

            // Important: l'app peut être servie dans un sous-dossier (ex: /evc).
            // Dans ce cas, le fallback basé sur la requête donne une URL correcte: https://domaine.tld/evc/storage/...
            $fallbackStorageBaseUrl = $fallbackBase . '/storage';

            // Ne surcharger que si une URL de disque public est explicitement configurée (via PUBLIC_DISK_URL)
            $configuredDiskUrl = (string) Config::get('filesystems.disks.public.url', '');
            $configuredDiskUrl = rtrim($configuredDiskUrl, '/');
            $diskBaseUrl = $configuredDiskUrl !== '' ? $configuredDiskUrl : $fallbackStorageBaseUrl;

            return $diskBaseUrl . '/' . $encodedPath;
        } catch (\Throwable $e) {
            $diskBaseUrl = (string) Config::get('filesystems.disks.public.url', '');
            $diskBaseUrl = rtrim($diskBaseUrl, '/');
            if ($diskBaseUrl !== '') {
                return $diskBaseUrl . '/' . $encodedPath;
            }
            return asset('storage/' . $encodedPath);
        }
    }
}
