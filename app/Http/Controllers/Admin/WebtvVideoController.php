<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebtvVideo;
use App\Models\WebtvSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class WebtvVideoController extends Controller
{
    /**
     * Afficher la liste des vidéos programmées
     */
    public function index()
    {
        $videos = WebtvVideo::orderBy('order', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => WebtvVideo::count(),
            'active' => WebtvVideo::currentlyActive()->count(),
            'scheduled' => WebtvVideo::scheduled()->count(),
            'lives' => WebtvVideo::live()->count(),
            'normal' => WebtvVideo::normal()->count(),
        ];

        return view('admin.webtv.videos', compact('videos', 'stats'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('admin.webtv.create-video');
    }

    /**
     * Enregistrer une nouvelle vidéo
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:normal,live',
            'category' => 'nullable|in:design-graphique,community-management,intelligence-artificielle,gestion-informatique',
            'scheduled_start' => 'nullable|date',
            'scheduled_end' => 'nullable|date|after:scheduled_start',
            'loop_enabled' => 'boolean',
            'order' => 'nullable|integer',
        ], [
            'title.required' => 'Le titre est requis',
            'video_url.required' => 'L\'URL de la vidéo est requise',
            'video_url.url' => 'L\'URL de la vidéo n\'est pas valide',
            'type.required' => 'Le type est requis',
            'scheduled_end.after' => 'La date de fin doit être après la date de début',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('thumbnail');
            $data['loop_enabled'] = $request->has('loop_enabled');
            $data['is_active'] = $request->has('is_active');
            $data['autoplay'] = $request->has('autoplay') ? true : false;
            $data['autopause'] = $request->has('autopause') ? true : false;

            // Extraire l'ID de la playlist Vimeo depuis l'URL
            if ($request->video_url) {
                $playlistId = WebtvVideo::extractVimeoPlaylistId($request->video_url);
                if ($playlistId) {
                    $data['vimeo_playlist_id'] = $playlistId;
                }
            }

            // Gérer l'upload de la miniature
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('webtv/thumbnails', 'public');
                $data['thumbnail'] = $thumbnailPath;
            }

            // Si pas d'ordre spécifié, prendre le prochain disponible
            if (!isset($data['order'])) {
                $data['order'] = WebtvVideo::max('order') + 1;
            }

            $video = WebtvVideo::create($data);

            // Générer le code embed
            if ($video->vimeo_playlist_id) {
                $video->update(['embed_code' => $video->generateEmbedCode()]);
            }

            return redirect()->route('admin.webtv.videos')
                ->with('success', 'Playlist Vimeo programmée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur création vidéo WebTV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la création de la vidéo.')
                ->withInput();
        }
    }

    /**
     * Afficher les détails d'une vidéo
     */
    public function show($id)
    {
        $video = WebtvVideo::findOrFail($id);
        return view('admin.webtv.show-video', compact('video'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit($id)
    {
        $video = WebtvVideo::findOrFail($id);
        return view('admin.webtv.edit-video', compact('video'));
    }

    /**
     * Mettre à jour une vidéo
     */
    public function update(Request $request, $id)
    {
        $video = WebtvVideo::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'required|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:normal,live',
            'category' => 'nullable|in:design-graphique,community-management,intelligence-artificielle,gestion-informatique',
            'scheduled_start' => 'nullable|date',
            'scheduled_end' => 'nullable|date|after:scheduled_start',
            'loop_enabled' => 'boolean',
            'order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->except('thumbnail');
            $data['loop_enabled'] = $request->has('loop_enabled');
            $data['is_active'] = $request->has('is_active');
            $data['autoplay'] = $request->has('autoplay') ? true : false;
            $data['autopause'] = $request->has('autopause') ? true : false;

            // Extraire l'ID de la playlist Vimeo depuis l'URL
            if ($request->video_url) {
                $playlistId = WebtvVideo::extractVimeoPlaylistId($request->video_url);
                if ($playlistId) {
                    $data['vimeo_playlist_id'] = $playlistId;
                }
            }

            // Gérer l'upload de la miniature
            if ($request->hasFile('thumbnail')) {
                // Supprimer l'ancienne miniature
                if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                    Storage::disk('public')->delete($video->thumbnail);
                }

                $thumbnailPath = $request->file('thumbnail')->store('webtv/thumbnails', 'public');
                $data['thumbnail'] = $thumbnailPath;
            }

            $video->update($data);

            // Régénérer le code embed si c'est une playlist Vimeo
            if ($video->vimeo_playlist_id) {
                $video->update(['embed_code' => $video->generateEmbedCode()]);
            }

            return redirect()->route('admin.webtv.videos')
                ->with('success', 'Playlist Vimeo mise à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour vidéo WebTV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour de la vidéo.')
                ->withInput();
        }
    }

    /**
     * Supprimer une vidéo
     */
    public function destroy($id)
    {
        try {
            $video = WebtvVideo::findOrFail($id);

            // Supprimer la miniature si elle existe
            if ($video->thumbnail && Storage::disk('public')->exists($video->thumbnail)) {
                Storage::disk('public')->delete($video->thumbnail);
            }

            $video->delete();

            return redirect()->route('admin.webtv.videos')
                ->with('success', 'Vidéo supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur suppression vidéo WebTV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la vidéo.');
        }
    }

    /**
     * Démarrer une vidéo
     */
    public function start($id)
    {
        try {
            $video = WebtvVideo::findOrFail($id);
            $video->start();

            // Notifier les abonnés si c'est un live
            if ($video->type === 'live') {
                $this->notifySubscribersAboutLive($video);
            }

            return redirect()->back()
                ->with('success', 'Vidéo démarrée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur démarrage vidéo WebTV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors du démarrage de la vidéo.');
        }
    }

    /**
     * Mettre en pause une vidéo
     */
    public function pause($id)
    {
        try {
            $video = WebtvVideo::findOrFail($id);
            $video->pause();

            return redirect()->back()
                ->with('success', 'Vidéo mise en pause.');
        } catch (\Exception $e) {
            Log::error('Erreur pause vidéo WebTV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise en pause.');
        }
    }

    /**
     * Terminer une vidéo
     */
    public function end($id)
    {
        try {
            $video = WebtvVideo::findOrFail($id);
            $video->end();

            return redirect()->back()
                ->with('success', 'Vidéo terminée.');
        } catch (\Exception $e) {
            Log::error('Erreur fin vidéo WebTV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Erreur lors de la fin de la vidéo.');
        }
    }

    /**
     * Mettre à jour l'ordre des vidéos
     */
    public function updateOrder(Request $request)
    {
        try {
            $orders = $request->input('orders', []);

            foreach ($orders as $id => $order) {
                WebtvVideo::where('id', $id)->update(['order' => $order]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ordre mis à jour avec succès.'
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur mise à jour ordre vidéos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'ordre.'
            ], 500);
        }
    }

    /**
     * Obtenir la vidéo en cours pour le player public
     */
    public function getCurrentVideo()
    {
        try {
            $video = WebtvVideo::active()
                ->currentlyActive()
                ->orderBy('order', 'asc')
                ->first();

            if (!$video) {
                // Si aucune vidéo active, prendre la première programmée
                $video = WebtvVideo::active()
                    ->scheduled()
                    ->orderBy('order', 'asc')
                    ->first();
            }

            if ($video) {
                $video->incrementViewCount();
            }

            return response()->json([
                'success' => true,
                'video' => $video
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur récupération vidéo courante: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la vidéo.'
            ], 500);
        }
    }

    /**
     * Obtenir toutes les vidéos actives pour la playlist
     */
    public function getPlaylist()
    {
        try {
            $videos = WebtvVideo::active()
                ->whereIn('status', ['active', 'scheduled'])
                ->orderBy('order', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'videos' => $videos
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur récupération playlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération de la playlist.'
            ], 500);
        }
    }

    /**
     * Notifier les abonnés qu'un live va commencer
     */
    private function notifySubscribersAboutLive($video)
    {
        try {
            $subscribers = WebtvSubscriber::active()->verified()->get();
            $sentCount = 0;

            foreach ($subscribers as $subscriber) {
                try {
                    Mail::send('emails.webtv_live_notification', [
                        'subscriber' => $subscriber,
                        'video' => $video
                    ], function ($message) use ($subscriber, $video) {
                        $message->to($subscriber->email)
                            ->subject('Live WebTV en cours : ' . $video->title);
                    });

                    $subscriber->update(['last_notified_at' => now()]);
                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email live WebTV à ' . $subscriber->email . ': ' . $e->getMessage());
                }
            }

            Log::info("Notification live envoyée à $sentCount abonnés pour : " . $video->title);
        } catch (\Exception $e) {
            Log::error('Erreur notification abonnés live: ' . $e->getMessage());
        }
    }
}
