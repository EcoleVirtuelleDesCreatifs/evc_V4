<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PlaquettesAdminController extends Controller
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

        $relativeDir = 'assets/plaquettes';
        $dir = public_path($relativeDir);

        $plaquettes = [];
        if (is_dir($dir)) {
            $files = File::glob($dir . '/*.pdf') ?: [];
            sort($files);

            foreach ($files as $path) {
                $base = basename($path);
                $name = pathinfo($base, PATHINFO_FILENAME);

                $sizeLabel = '';
                try {
                    $bytes = (int) File::size($path);
                    if ($bytes > 0) {
                        $sizeLabel = number_format($bytes / 1024 / 1024, 1, ',', ' ') . ' Mo';
                    }
                } catch (\Throwable $e) {
                    $sizeLabel = '';
                }

                $updatedAt = null;
                try {
                    $updatedAt = date('d/m/Y H:i', (int) File::lastModified($path));
                } catch (\Throwable $e) {
                    $updatedAt = null;
                }

                $plaquettes[] = [
                    'filename' => $base,
                    'title' => Str::of($name)->replace(['_', '-'], ' ')->title()->toString(),
                    'url' => asset($relativeDir . '/' . $base),
                    'size_label' => $sizeLabel,
                    'updated_at' => $updatedAt,
                ];
            }
        }

        return view('admin.plaquettes.index', [
            'plaquettes' => $plaquettes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAllowed();

        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'file' => 'required|file|mimes:pdf|max:20480',
        ]);

        $relativeDir = 'assets/plaquettes';
        $dir = public_path($relativeDir);
        if (!is_dir($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $originalExt = strtolower($request->file('file')->getClientOriginalExtension() ?: 'pdf');
        $safeBase = Str::slug((string) $validated['title']);
        if ($safeBase === '') {
            $safeBase = 'plaquette';
        }

        $filename = $safeBase . '-' . now()->format('YmdHis') . '.' . $originalExt;

        $request->file('file')->move($dir, $filename);

        return redirect()->route('admin.plaquettes.index')->with('success', 'Plaquette ajoutée avec succès.');
    }

    public function destroy(string $filename): RedirectResponse
    {
        $this->ensureAllowed();

        $relativeDir = 'assets/plaquettes';
        $dir = public_path($relativeDir);

        $filename = basename($filename);
        $path = rtrim($dir, '/') . '/' . $filename;

        if (!str_ends_with(strtolower($filename), '.pdf')) {
            return redirect()->route('admin.plaquettes.index')->with('error', 'Fichier invalide.');
        }

        if (is_file($path)) {
            try {
                File::delete($path);
            } catch (\Throwable $e) {
                return redirect()->route('admin.plaquettes.index')->with('error', 'Impossible de supprimer la plaquette pour le moment.');
            }
        }

        return redirect()->route('admin.plaquettes.index')->with('success', 'Plaquette supprimée.');
    }
}
