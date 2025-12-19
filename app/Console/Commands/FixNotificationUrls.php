<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixNotificationUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:fix-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corriger les URLs incorrectes dans les notifications existantes';

    /**
     * Mapping des formations vers les bons slugs
     */
    private $formationMapping = [
        'design-graphique-&-community-management' => 'design-graphique-cm',
        'design-graphique-community-management' => 'design-graphique-cm',
        'community-management' => 'community-management',
        'design-graphique' => 'design-graphique',
        'gestion-informatique' => 'gestion-informatique',
        'intelligence-artificielle' => 'intelligence-artificielle',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Correction des URLs de notifications en cours...');

        // Récupérer toutes les notifications
        $notifications = DB::table('notifications')->get();
        $fixed = 0;
        $total = $notifications->count();

        $this->info("📊 {$total} notification(s) trouvée(s)");

        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);

            if (!isset($data['url'])) {
                continue;
            }

            $originalUrl = $data['url'];
            $newUrl = $this->fixUrl($originalUrl);

            // Si l'URL a changé, mettre à jour
            if ($newUrl !== $originalUrl) {
                $data['url'] = $newUrl;

                DB::table('notifications')
                    ->where('id', $notification->id)
                    ->update(['data' => json_encode($data)]);

                $fixed++;
                $this->line("✓ Corrigé: {$originalUrl} → {$newUrl}");
            }
        }

        $this->newLine();
        $this->info("✅ {$fixed} notification(s) corrigée(s) sur {$total}");

        return 0;
    }

    /**
     * Corriger une URL
     */
    private function fixUrl($url)
    {
        // Extraire le chemin de l'URL
        $path = parse_url($url, PHP_URL_PATH);

        if (!$path) {
            return $url;
        }

        // Remplacer les mauvais slugs par les bons
        foreach ($this->formationMapping as $bad => $good) {
            $path = str_replace("/evc/compte/{$bad}/", "/evc/compte/{$good}/", $path);
        }

        // Reconstruire l'URL
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $host = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);

        $newUrl = $scheme . '://' . $host;
        if ($port) {
            $newUrl .= ':' . $port;
        }
        $newUrl .= $path;

        return $newUrl;
    }
}
