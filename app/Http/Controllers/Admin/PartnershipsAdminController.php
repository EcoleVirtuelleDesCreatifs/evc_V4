<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnershipsAdminController extends Controller
{
    private function ensureAllowed(): void
    {
        if (!in_array(session('admin_role'), ['super_admin'], true)) {
            abort(403);
        }
    }

    public function index(): View
    {
        $this->ensureAllowed();

        $partnerships = Partnership::query()
            ->orderByDesc('is_active')
            ->orderBy('name')
            ->get();

        return view('admin.partnerships.index', compact('partnerships'));
    }

    public function edit(Partnership $partnership): View
    {
        $this->ensureAllowed();

        return view('admin.partnerships.edit', compact('partnership'));
    }

    public function update(Request $request, Partnership $partnership): RedirectResponse
    {
        $this->ensureAllowed();

        $validated = $request->validate([
            'prefix' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'document' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('partenariats', 'public');
            $partnership->document_path = $path;
        }

        $partnership->prefix = $validated['prefix'];
        $partnership->name = $validated['name'];
        $partnership->subtitle = $validated['subtitle'] ?? null;

        $isActive = (bool) ($validated['is_active'] ?? false);
        $partnership->is_active = $isActive;
        $partnership->save();

        if ($isActive) {
            Partnership::query()
                ->where('id', '!=', $partnership->id)
                ->update(['is_active' => false]);
        }

        return redirect()
            ->route('admin.partnerships.index')
            ->with('success', 'Partenariat mis à jour.');
    }

    public function deleteDocument(Partnership $partnership): RedirectResponse
    {
        $this->ensureAllowed();

        if (!empty($partnership->document_path)) {
            try {
                Storage::disk('public')->delete($partnership->document_path);
            } catch (\Throwable $e) {
            }
        }

        $partnership->document_path = null;
        $partnership->save();

        return redirect()
            ->route('admin.partnerships.edit', $partnership)
            ->with('success', 'Document supprimé.');
    }
}
