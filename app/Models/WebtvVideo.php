<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebtvVideo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'video_url',
        'vimeo_playlist_id',
        'vimeo_showcase_id',
        'vimeo_user_id',
        'total_videos',
        'playlist_data',
        'embed_code',
        'thumbnail',
        'type',
        'category',
        'status',
        'scheduled_start',
        'scheduled_end',
        'actual_start',
        'actual_end',
        'loop_enabled',
        'autoplay',
        'autopause',
        'loop_count',
        'current_video_index',
        'view_count',
        'order',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'loop_enabled' => 'boolean',
        'autoplay' => 'boolean',
        'autopause' => 'boolean',
        'is_active' => 'boolean',
        'loop_count' => 'integer',
        'current_video_index' => 'integer',
        'total_videos' => 'integer',
        'view_count' => 'integer',
        'order' => 'integer',
        'playlist_data' => 'array',
        'settings' => 'array',
    ];

    /**
     * Scope pour les vidéos actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les vidéos en live
     */
    public function scopeLive($query)
    {
        return $query->where('type', 'live');
    }

    /**
     * Scope pour les vidéos normales
     */
    public function scopeNormal($query)
    {
        return $query->where('type', 'normal');
    }

    /**
     * Scope pour les vidéos en cours
     */
    public function scopeCurrentlyActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pour les vidéos programmées
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Vérifie si la vidéo est en cours
     */
    public function isLive()
    {
        return $this->status === 'active' && $this->type === 'live';
    }

    /**
     * Vérifie si la vidéo est en boucle
     */
    public function isLooping()
    {
        return $this->loop_enabled && $this->status === 'active';
    }

    /**
     * Démarre la vidéo
     */
    public function start()
    {
        $this->update([
            'status' => 'active',
            'actual_start' => now(),
        ]);
    }

    /**
     * Met en pause la vidéo
     */
    public function pause()
    {
        $this->update(['status' => 'paused']);
    }

    /**
     * Termine la vidéo
     */
    public function end()
    {
        $this->update([
            'status' => 'ended',
            'actual_end' => now(),
        ]);
    }

    /**
     * Incrémente le compteur de boucles
     */
    public function incrementLoopCount()
    {
        $this->increment('loop_count');
    }

    /**
     * Incrémente le compteur de vues
     */
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    /**
     * Obtient le type formaté
     */
    public function getTypeLabel()
    {
        return $this->type === 'live' ? 'Live' : 'Normal';
    }

    /**
     * Obtient le statut formaté
     */
    public function getStatusLabel()
    {
        $labels = [
            'scheduled' => 'Programmé',
            'active' => 'En cours',
            'paused' => 'En pause',
            'ended' => 'Terminé',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Extrait l'ID de la playlist/vidéo Vimeo depuis une URL
     */
    public static function extractVimeoPlaylistId($url)
    {
        // Formats supportés:
        // https://vimeo.com/showcase/XXXXXXX
        // https://vimeo.com/album/XXXXXXX
        // https://vimeo.com/manage/showcases/XXXXXXX
        // https://vimeo.com/event/XXXXXXX (événement live)
        // https://vimeo.com/manage/videos/XXXXXXX (vidéo individuelle)
        // https://vimeo.com/XXXXXXX (vidéo individuelle)

        $patterns = [
            '/vimeo\.com\/showcase\/(\d+)/',
            '/vimeo\.com\/album\/(\d+)/',
            '/vimeo\.com\/manage\/showcases\/(\d+)/',
            '/vimeo\.com\/event\/(\d+)/',           // Événement Live
            '/vimeo\.com\/manage\/videos\/(\d+)/',  // Vidéo individuelle (manage)
            '/vimeo\.com\/(\d+)/',                   // Vidéo individuelle (direct)
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Génère le code embed pour une vidéo/playlist Vimeo
     */
    public function generateEmbedCode()
    {
        if (!$this->vimeo_playlist_id) {
            return null;
        }

        // Paramètres pour forcer l'autoplay et masquer l'interface
        $params = [
            'autoplay' => '1',        // TOUJOURS activer autoplay
            'muted' => '1',           // TOUJOURS muté (requis pour autoplay)
            'loop' => $this->loop_enabled ? '1' : '0',
            'autopause' => '0',       // Désactiver autopause
            'title' => '0',           // Masquer le titre
            'byline' => '0',          // Masquer l'auteur
            'portrait' => '0',        // Masquer le portrait
            'badge' => '0',           // Masquer le badge Vimeo (si permis)
            'dnt' => '1',             // Do Not Track
            'playsinline' => '1',     // Lecture inline sur mobile
            'transparent' => '0',     // Fond opaque
        ];

        $queryString = http_build_query($params);

        // Vérifier le type de contenu Vimeo
        $isShowcase = strpos($this->video_url, 'showcase') !== false || strpos($this->video_url, 'album') !== false;
        $isEvent = strpos($this->video_url, 'event') !== false;

        if ($isShowcase) {
            // URL pour showcase/album
            $embedUrl = sprintf('https://vimeo.com/showcase/%s/embed?%s', $this->vimeo_playlist_id, $queryString);
        } elseif ($isEvent) {
            // URL pour événement live
            $embedUrl = sprintf('https://vimeo.com/event/%s/embed?%s', $this->vimeo_playlist_id, $queryString);
        } else {
            // URL pour vidéo individuelle
            $embedUrl = sprintf('https://player.vimeo.com/video/%s?%s', $this->vimeo_playlist_id, $queryString);
        }

        return sprintf(
            '<iframe src="%s" width="100%%" height="100%%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position: absolute; top: 0; left: 0;"></iframe>',
            $embedUrl
        );
    }

    /**
     * Obtient l'URL du player embed
     */
    public function getEmbedUrl()
    {
        if (!$this->vimeo_playlist_id) {
            return null;
        }

        $params = [
            'autopause' => $this->autopause ? '1' : '0',
            'loop' => $this->loop_enabled ? '1' : '0',
            'autoplay' => $this->autoplay ? '1' : '0',
            'muted' => $this->autoplay ? '1' : '0',
        ];

        return sprintf(
            'https://vimeo.com/showcase/%s/embed?%s',
            $this->vimeo_playlist_id,
            http_build_query($params)
        );
    }

    /**
     * Vérifie si c'est une playlist Vimeo
     */
    public function isVimeoPlaylist()
    {
        return !empty($this->vimeo_playlist_id);
    }
}
