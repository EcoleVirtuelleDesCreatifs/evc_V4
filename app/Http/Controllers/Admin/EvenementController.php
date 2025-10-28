<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evenement;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EvenementController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        try {
            // Récupérer tous les événements avec soft deletes et la relation author
            $evenements = Evenement::with('author')->orderBy('created_at', 'desc')->get();
            
            // Calculer les statistiques
            $stats = [
                'total' => $evenements->count(),
                'publies' => $evenements->where('status', 'published')->count(),
                'brouillons' => $evenements->where('status', 'draft')->count(),
            ];
            
            return view('admin.articles.evenements', compact('evenements', 'stats'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des événements: ' . $e->getMessage());
            return view('admin.articles.evenements', [
                'evenements' => collect(),
                'stats' => ['total' => 0, 'publies' => 0, 'brouillons' => 0]
            ])->with('error', '❌ Erreur lors du chargement des événements.');
        }
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        try {
            // Liste des types de formations (pas les cours individuels)
            $formations = collect([
                (object)['id' => 1, 'name' => 'Design Graphique'],
                (object)['id' => 2, 'name' => 'Community Management'],
                (object)['id' => 3, 'name' => 'Gestion Informatique'],
                (object)['id' => 4, 'name' => 'Intelligence Artificielle'],
            ]);
            
            return view('admin.articles.create-evenement', compact('formations'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire de création: ' . $e->getMessage());
            
            return view('admin.articles.create-evenement', [
                'formations' => collect()
            ]);
        }
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:evenements,slug',
                'excerpt' => 'required|string|max:500',
                'content' => 'required|string',
                'event_date' => 'required|date',
                'event_end_date' => 'nullable|date|after_or_equal:event_date',
                'location' => 'nullable|string|max:255',
                'event_type' => 'required|in:online,physical,hybrid',
                'registration_link' => 'nullable|url',
                'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
                'cover_image_alt' => 'nullable|string|max:255',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'meta_keywords' => 'nullable|string|max:255',
                'visibility' => 'required|in:public,all,specific',
                'formations' => 'nullable|array',
                'formations.*' => 'integer|in:1,2,3,4',
                'status' => 'required|in:draft,published',
                'is_featured' => 'nullable|boolean',
            ]);

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            // Ensure slug is unique
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Evenement::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $imageName = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('evenements/covers', $imageName, 'public');
                $validated['cover_image'] = $imagePath;
            }

            // Set author
            $validated['author_id'] = session('admin_id');

            // Handle is_featured checkbox
            $validated['is_featured'] = $request->has('is_featured');

            // Set published_at if status is published
            if ($validated['status'] === 'published') {
                $validated['published_at'] = now();
            }

            // Create event
            $evenement = Evenement::create($validated);

            $message = $validated['status'] === 'published' 
                ? '✅ Événement créé et publié avec succès!' 
                : '✅ Événement créé en brouillon avec succès!';

            return redirect()->route('admin.articles.evenements')
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de l\'événement: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Erreur lors de la création de l\'événement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified event.
     */
    public function show(Evenement $evenement)
    {
        try {
            $evenement->load('author');
            return view('admin.articles.show-evenement', compact('evenement'));
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'affichage de l\'événement: ' . $e->getMessage());
            return redirect()->route('admin.articles.evenements')
                ->with('error', '❌ Erreur lors de l\'affichage de l\'événement.');
        }
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Evenement $evenement)
    {
        try {
            // Liste des types de formations (pas les cours individuels)
            $formations = collect([
                (object)['id' => 1, 'name' => 'Design Graphique'],
                (object)['id' => 2, 'name' => 'Community Management'],
                (object)['id' => 3, 'name' => 'Gestion Informatique'],
                (object)['id' => 4, 'name' => 'Intelligence Artificielle'],
            ]);

            return view('admin.articles.edit-evenement', compact('evenement', 'formations'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement du formulaire d\'édition: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return view with empty formations if there's an error
            return view('admin.articles.edit-evenement', [
                'evenement' => $evenement,
                'formations' => collect()
            ]);
        }
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Evenement $evenement)
    {
        try {
            // Handle is_featured checkbox before validation
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'nullable|string|max:255|unique:evenements,slug,' . $evenement->id,
                'excerpt' => 'required|string|max:500',
                'content' => 'required|string',
                'event_date' => 'required|date',
                'event_end_date' => 'nullable|date|after_or_equal:event_date',
                'location' => 'nullable|string|max:255',
                'event_type' => 'required|in:online,physical,hybrid',
                'registration_link' => 'nullable|url',
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
                'cover_image_alt' => 'nullable|string|max:255',
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'meta_keywords' => 'nullable|string|max:255',
                'visibility' => 'required|in:public,all,specific',
                'formations' => 'nullable|array',
                'formations.*' => 'integer|in:1,2,3,4',
                'status' => 'required|in:draft,published',
            ]);

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['title']);
            }

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                // Delete old image
                if ($evenement->cover_image) {
                    Storage::disk('public')->delete($evenement->cover_image);
                }

                $image = $request->file('cover_image');
                $imageName = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('evenements/covers', $imageName, 'public');
                $validated['cover_image'] = $imagePath;
            }

            // Handle is_featured checkbox
            $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

            // Set published_at if status changes to published
            if ($validated['status'] === 'published' && $evenement->status !== 'published') {
                $validated['published_at'] = now();
            }

            // Update event
            $evenement->update($validated);

            return redirect()->route('admin.articles.evenements')
                ->with('success', '✅ Événement mis à jour avec succès!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'événement: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Erreur lors de la mise à jour de l\'événement: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Evenement $evenement)
    {
        try {
            // Delete cover image
            if ($evenement->cover_image) {
                Storage::disk('public')->delete($evenement->cover_image);
            }

            $evenement->delete();

            return redirect()->route('admin.articles.evenements')
                ->with('success', '✅ Événement supprimé avec succès!');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de l\'événement: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Erreur lors de la suppression de l\'événement.');
        }
    }

    /**
     * Toggle event status (draft/published).
     */
    public function toggleStatus(Evenement $evenement)
    {
        try {
            $newStatus = $evenement->status === 'published' ? 'draft' : 'published';
            
            $evenement->update([
                'status' => $newStatus,
                'published_at' => $newStatus === 'published' ? now() : null,
            ]);

            $message = $newStatus === 'published' 
                ? '✅ Événement publié avec succès!' 
                : '✅ Événement mis en brouillon avec succès!';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Erreur lors du changement de statut.');
        }
    }

    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Evenement $evenement)
    {
        try {
            $evenement->update([
                'is_featured' => !$evenement->is_featured,
            ]);

            $message = $evenement->is_featured 
                ? '✅ Événement mis à la une!' 
                : '✅ Événement retiré de la une!';

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut à la une: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', '❌ Erreur lors du changement de statut à la une.');
        }
    }
}
