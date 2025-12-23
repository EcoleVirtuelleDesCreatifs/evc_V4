<?php

namespace App\Models;

use Illuminate\Support\Str;

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

        return asset('storage/app/public/' . ltrim($path, '/'));
    }
}
