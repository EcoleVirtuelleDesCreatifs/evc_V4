<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\Category;
use App\Models\LibraryCategory;
use App\Models\Formation;
use App\Models\Library;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminDashboardController extends Controller
{
    public function dashboard(): View
    {
        return view('admin.dashboard'); 
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $students = User::orderBy('name')->get();
        return view('admin.formations.create', compact('categories', 'students'));
    }

    public function index(): View
    {
        try {
            $formations = \App\Models\Formation::with('category')->latest()->get();
            return view('admin.formations.index', compact('formations'));
        } catch (\Exception $e) {
            Log::error('Erreur lors du chargement des formations: ' . $e->getMessage());
            return view('admin.formations.index', ['formations' => collect()])->with('error', 'Impossible de charger la liste des formations.');
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'vimeo_code' => 'nullable|string',
            'module' => 'required|string',
            'type' => 'required|in:en_ligne,presentiel',
            'destinataire' => 'required|in:etudiants-actifs,etudiants-specifiques',
            'is_featured' => 'required|boolean',
            'action' => 'required|in:draft,pending,published',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        try {
            $formation = new Formation();

            $formation->name = $validatedData['name'];
            $formation->slug = Str::slug($validatedData['name']);
            $formation->category_id = $validatedData['category_id'];
            $formation->description = $validatedData['description'];
            $formation->is_featured = $validatedData['is_featured'];

            $statusMap = [
                'draft' => 'draft',
                'pending' => 'inactive',
                'published' => 'active',
            ];
            $formation->status = $statusMap[$validatedData['action']]; 

            $formation->modules = [$validatedData['module']]; 

            $formation->format = ($validatedData['type'] === 'en_ligne') ? 'online' : 'offline';
            $formation->student_restriction = ($validatedData['destinataire'] === 'etudiants-actifs') ? 'active_only' : 'all';

            if ($request->filled('vimeo_code')) {
                $formation->vimeo_code = $validatedData['vimeo_code'];
            }

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('formations', 'public');
                $formation->image_url = $path;
            }

            $formation->save();

            if ($request->filled('student_ids')) {
                $formation->students()->sync($validatedData['student_ids']);
            }
            
            return redirect()->route('admin.formations.index')->with('success', 'Formation créée avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la formation: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la création de la formation: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Formation $formation)
    {
        try {
            $formation->delete();
            return redirect()->route('admin.formations.index')->with('success', 'Formation supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de la formation: ' . $e->getMessage());
            return redirect()->route('admin.formations.index')->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    public function edit(Formation $formation)
    {
        $categories = Category::orderBy('name')->get();
        $students = User::orderBy('name')->get();
        return view('admin.formations.edit', compact('formation', 'categories', 'students'));
    }

    public function update(Request $request, Formation $formation)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'vimeo_code' => 'nullable|string',
            'module' => 'required|string',
            'type' => 'required|in:en_ligne,presentiel',
            'destinataire' => 'required|in:etudiants-actifs,etudiants-specifiques',
            'is_featured' => 'required|boolean',
            'action' => 'required|in:draft,pending,published',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'exists:users,id',
        ]);

        try {
            $formation->name = $validatedData['name'];
            $formation->slug = Str::slug($validatedData['name']);
            $formation->category_id = $validatedData['category_id'];
            $formation->description = $validatedData['description'];
            $formation->is_featured = $validatedData['is_featured'];

            $statusMap = [
                'draft' => 'draft',
                'pending' => 'inactive',
                'published' => 'active',
            ];
            $formation->status = $statusMap[$validatedData['action']];

            $formation->modules = [$validatedData['module']];

            $formation->format = ($validatedData['type'] === 'en_ligne') ? 'online' : 'offline';
            $formation->student_restriction = ($validatedData['destinataire'] === 'etudiants-actifs') ? 'active_only' : 'all';

            if ($request->filled('vimeo_code')) {
                $formation->vimeo_code = $validatedData['vimeo_code'];
            }

            if ($request->hasFile('image')) {
                if ($formation->image_url) {
                    Storage::disk('public')->delete($formation->image_url);
                }
                $path = $request->file('image')->store('formations', 'public');
                $formation->image_url = $path;
            }

            $formation->save();

            if ($request->filled('student_ids')) {
                $formation->students()->sync($validatedData['student_ids']);
            } else {
                $formation->students()->detach();
            }

            return redirect()->route('admin.formations.index')->with('success', 'Formation mise à jour avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour de la formation: ' . $e->getMessage());
            return back()->with('error', 'Erreur de mise à jour: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Formation $formation)
    {
        return view('admin.formations.show', compact('formation'));
    }

    public function toggleStatus(Formation $formation)
    {
        try {
            $formation->status = ($formation->status === 'active') ? 'inactive' : 'active';
            $formation->save();
            return redirect()->route('admin.formations.index')->with('success', 'Statut de la formation mis à jour.');
        } catch (\Exception $e) {
            Log::error('Erreur lors du changement de statut: ' . $e->getMessage());
            return redirect()->route('admin.formations.index')->with('error', 'Une erreur est survenue.');
        }
    }

    public function categoriesIndex()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($validatedData);

        return redirect()->route('admin.formations.categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    public function studentsDesignGraphique()
    {
        $students = User::whereHas('formations.category', function ($query) {
            $query->where('name', 'Design Graphique');
        })->get();
        return view('admin.etudiants.design-graphique', compact('students'));
    }

    public function bibliothequeCategories()
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.categories.index', compact('categories'));
    }

    public function bibliotheque()
    {
        $items = Library::with('libraryCategory')->latest()->get();
        return view('admin.bibliotheque.index', compact('items'));
    }

    public function createBibliothequeItem()
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.create', compact('categories'));
    }

    public function storeBibliothequeItem(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,gif,svg,zip|max:20480', // Max 20MB
            'library_category_id' => 'nullable|exists:library_categories,id',
            'download_url' => 'nullable|url',
            'recipients' => 'nullable|array',
        ]);

        $file = $request->file('file');
        $path = $file->store('library', 'public');

        Library::create([
            'title' => $validatedData['title'],
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'library_category_id' => $validatedData['library_category_id'] ?? null,
            'download_url' => $validatedData['download_url'] ?? null,
            'recipients' => $validatedData['recipients'] ?? [],
        ]);

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média ajouté avec succès.');
    }

    public function showBibliothequeItem(Library $item): View
    {
        return view('admin.bibliotheque.show', compact('item'));
    }

    public function editBibliothequeItem(Library $item): View
    {
        $categories = LibraryCategory::all();
        return view('admin.bibliotheque.edit', compact('item', 'categories'));
    }

    public function updateBibliothequeItem(Request $request, Library $item): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'library_category_id' => 'nullable|exists:library_categories,id',
            'recipients' => 'nullable|array',
        ]);

        $item->update([
            'title' => $validatedData['title'],
            'library_category_id' => $validatedData['library_category_id'] ?? null,
            'recipients' => $validatedData['recipients'] ?? [],
        ]);

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média mis à jour avec succès.');
    }

    public function destroyBibliothequeItem(Library $item): RedirectResponse
    {
        Storage::disk('public')->delete($item->path);
        $item->delete();

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Média supprimé avec succès.');
    }

    public function toggleBibliothequeItemStatus(Library $item): RedirectResponse
    {
        $item->status = $item->status == 'active' ? 'inactive' : 'active';
        $item->save();

        return redirect()->route('admin.bibliotheque.index')->with('success', 'Statut du média mis à jour avec succès.');
    }

    public function documentsAll(): View
    {
        // Récupère tous les médias de la bibliothèque, en chargeant les relations
        // avec la catégorie et l'utilisateur pour un affichage complet.
        $documents = Library::with(['libraryCategory', 'user'])->latest()->get();
        
        // Retourne la vue en passant la collection de documents
        return view('admin.documents.all', compact('documents'));
    }
}
