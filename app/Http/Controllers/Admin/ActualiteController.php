<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Actualite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ActualiteController extends Controller
{
    /**
     * Display a listing of actualites.
     */
    public function index()
    {
        $actualites = Actualite::with('author')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $actualites->count(),
            'published' => $actualites->where('status', 'published')->count(),
            'draft' => $actualites->where('status', 'draft')->count(),
        ];

        return view('admin.articles.actualites', compact('actualites', 'stats'));
    }

    /**
     * Show the form for creating a new actualite.
     */
    public function create()
    {
        $formations = collect([
            (object)['id' => 1, 'name' => 'Design Graphique'],
            (object)['id' => 2, 'name' => 'Community Management'],
            (object)['id' => 3, 'name' => 'Gestion Informatique'],
            (object)['id' => 4, 'name' => 'Intelligence Artificielle'],
        ]);

        return view('admin.articles.create-actualite', compact('formations'));
    }

    /**
     * Store a newly created actualite in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:actualites,slug',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:general,formation,evenement,partenariat,succes',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
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
            $image = $request->file('cover_image');
            $imageName = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('actualites/covers', $imageName, 'public');
            $validated['cover_image'] = $imagePath;
        }

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

        // Set published_at if status is published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Convert formations array to JSON
        if (isset($validated['formations'])) {
            $validated['formations'] = json_encode($validated['formations']);
        }

        // Set author
        $validated['admin_id'] = session('admin_id');

        Actualite::create($validated);

        return redirect()->route('admin.articles.actualites')
            ->with('success', 'Actualité créée avec succès !');
    }

    /**
     * Display the specified actualite.
     */
    public function show(Actualite $actualite)
    {
        return view('admin.articles.show-actualite', compact('actualite'));
    }

    /**
     * Show the form for editing the specified actualite.
     */
    public function edit(Actualite $actualite)
    {
        $formations = collect([
            (object)['id' => 1, 'name' => 'Design Graphique'],
            (object)['id' => 2, 'name' => 'Community Management'],
            (object)['id' => 3, 'name' => 'Gestion Informatique'],
            (object)['id' => 4, 'name' => 'Intelligence Artificielle'],
        ]);

        return view('admin.articles.edit-actualite', compact('actualite', 'formations'));
    }

    /**
     * Update the specified actualite in storage.
     */
    public function update(Request $request, Actualite $actualite)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:actualites,slug,' . $actualite->id,
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category' => 'required|in:general,formation,evenement,partenariat,succes',
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
            if ($actualite->cover_image) {
                Storage::disk('public')->delete($actualite->cover_image);
            }

            $image = $request->file('cover_image');
            $imageName = time() . '_' . Str::slug($validated['title']) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('actualites/covers', $imageName, 'public');
            $validated['cover_image'] = $imagePath;
        }

        // Handle is_featured checkbox
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;

        // Set published_at if status changes to published
        if ($validated['status'] === 'published' && $actualite->status !== 'published') {
            $validated['published_at'] = now();
        }

        // Convert formations array to JSON
        if (isset($validated['formations'])) {
            $validated['formations'] = json_encode($validated['formations']);
        }

        $actualite->update($validated);

        return redirect()->route('admin.articles.actualites')
            ->with('success', 'Actualité mise à jour avec succès !');
    }

    /**
     * Remove the specified actualite from storage.
     */
    public function destroy(Actualite $actualite)
    {
        // Delete cover image
        if ($actualite->cover_image) {
            Storage::disk('public')->delete($actualite->cover_image);
        }

        $actualite->delete();

        return redirect()->route('admin.articles.actualites')
            ->with('success', 'Actualité supprimée avec succès !');
    }

    /**
     * Toggle the status of the actualite.
     */
    public function toggleStatus(Actualite $actualite)
    {
        $newStatus = $actualite->status === 'published' ? 'draft' : 'published';
        
        $actualite->update([
            'status' => $newStatus,
            'published_at' => $newStatus === 'published' ? now() : null,
        ]);

        return back()->with('success', 'Statut mis à jour avec succès !');
    }

    /**
     * Toggle the featured status of the actualite.
     */
    public function toggleFeatured(Actualite $actualite)
    {
        $actualite->update([
            'is_featured' => !$actualite->is_featured,
        ]);

        return back()->with('success', 'Statut "À la une" mis à jour avec succès !');
    }
}
