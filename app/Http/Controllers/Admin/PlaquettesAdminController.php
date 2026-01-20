<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use App\Models\Plaquette;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PlaquettesAdminController extends Controller
{
    private function ensureAllowed(): void
    {
        if (!in_array(session('admin_role'), ['super_admin'], true)) {
            abort(403);
        }
    }

    private function formationsList()
    {
        return Formation::query()
            ->whereIn('status', ['active', 'draft'])
            ->orderBy('name')
            ->get();
    }

    public function index(): View
    {
        $this->ensureAllowed();

        $plaquettes = Plaquette::query()
            ->with('formation')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.plaquettes.index', compact('plaquettes'));
    }

    public function create(): View
    {
        $this->ensureAllowed();
        $formations = $this->formationsList();

        return view('admin.plaquettes.create', compact('formations'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAllowed();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'formation_id' => 'required|integer|exists:formations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:online,offline',
            'file' => 'required|file|mimes:pdf|max:20480',
            'is_published' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        $path = $file->store('plaquettes', 'public');

        Plaquette::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'formation_id' => (int) $validated['formation_id'],
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'format' => $validated['format'],
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'published_at' => ($validated['is_published'] ?? false) ? now() : null,
            'is_active' => true,
        ]);

        return redirect()->route('admin.plaquettes.index')->with('success', 'Plaquette créée avec succès.');
    }

    public function edit(Plaquette $plaquette): View
    {
        $this->ensureAllowed();
        $formations = $this->formationsList();

        return view('admin.plaquettes.edit', compact('plaquette', 'formations'));
    }

    public function update(Request $request, Plaquette $plaquette): RedirectResponse
    {
        $this->ensureAllowed();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'formation_id' => 'required|integer|exists:formations,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:online,offline',
            'file' => 'nullable|file|mimes:pdf|max:20480',
            'is_published' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'formation_id' => (int) $validated['formation_id'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'format' => $validated['format'],
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'published_at' => ($validated['is_published'] ?? false) ? ($plaquette->published_at ?? now()) : null,
        ];

        if ($request->hasFile('file')) {
            if (!empty($plaquette->file_path) && Storage::disk('public')->exists($plaquette->file_path)) {
                Storage::disk('public')->delete($plaquette->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('plaquettes', 'public');

            $data['file_path'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $plaquette->update($data);

        return redirect()->route('admin.plaquettes.index')->with('success', 'Plaquette mise à jour.');
    }

    public function destroy(Plaquette $plaquette): RedirectResponse
    {
        $this->ensureAllowed();

        if (!empty($plaquette->file_path) && Storage::disk('public')->exists($plaquette->file_path)) {
            Storage::disk('public')->delete($plaquette->file_path);
        }

        $plaquette->delete();

        return redirect()->route('admin.plaquettes.index')->with('success', 'Plaquette supprimée.');
    }

    public function togglePublish(Plaquette $plaquette): RedirectResponse
    {
        $this->ensureAllowed();

        $newStatus = !$plaquette->is_published;
        $plaquette->update([
            'is_published' => $newStatus,
            'published_at' => $newStatus ? now() : null,
        ]);

        return redirect()->route('admin.plaquettes.index')->with('success', $newStatus ? 'Plaquette publiée.' : 'Plaquette dépubliée.');
    }

    public function toggleActive(Plaquette $plaquette): RedirectResponse
    {
        $this->ensureAllowed();

        $plaquette->update([
            'is_active' => !$plaquette->is_active,
        ]);

        return redirect()->route('admin.plaquettes.index')->with('success', $plaquette->is_active ? 'Plaquette activée.' : 'Plaquette désactivée.');
    }

    public function download(Plaquette $plaquette)
    {
        $this->ensureAllowed();

        $path = (string) ($plaquette->file_path ?? '');
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if ($path === '' || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->download(
            Storage::disk('public')->path($path),
            $plaquette->original_filename
        );
    }
}
