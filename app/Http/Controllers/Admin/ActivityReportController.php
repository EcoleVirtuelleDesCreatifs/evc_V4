<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ActivityReportController extends Controller
{
    public function index(): View
    {
        $reports = ActivityReport::query()
            ->orderByDesc('year')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.activity_reports.index', compact('reports'));
    }

    public function create(): View
    {
        return view('admin.activity_reports.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|integer|min:2000|max:2100',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf|max:20480',
            'is_published' => 'nullable|boolean',
        ]);

        $file = $request->file('file');
        $path = $file->store('activity_reports', 'public');

        ActivityReport::create([
            'title' => $validated['title'],
            'year' => $validated['year'] ?? null,
            'description' => $validated['description'] ?? null,
            'file_path' => $path,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'published_at' => ($validated['is_published'] ?? false) ? now() : null,
        ]);

        return redirect()->route('admin.activity-reports.index')->with('success', "Rapport d'activité créé avec succès.");
    }

    public function edit(ActivityReport $activityReport): View
    {
        return view('admin.activity_reports.edit', ['report' => $activityReport]);
    }

    public function update(Request $request, ActivityReport $activityReport): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|integer|min:2000|max:2100',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf|max:20480',
            'is_published' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $validated['title'],
            'year' => $validated['year'] ?? null,
            'description' => $validated['description'] ?? null,
            'is_published' => (bool) ($validated['is_published'] ?? false),
            'published_at' => ($validated['is_published'] ?? false) ? ($activityReport->published_at ?? now()) : null,
        ];

        if ($request->hasFile('file')) {
            if ($activityReport->file_path && Storage::disk('public')->exists($activityReport->file_path)) {
                Storage::disk('public')->delete($activityReport->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('activity_reports', 'public');

            $data['file_path'] = $path;
            $data['original_filename'] = $file->getClientOriginalName();
            $data['file_size'] = $file->getSize();
        }

        $activityReport->update($data);

        return redirect()->route('admin.activity-reports.index')->with('success', "Rapport d'activité mis à jour.");
    }

    public function destroy(ActivityReport $activityReport): RedirectResponse
    {
        if ($activityReport->file_path && Storage::disk('public')->exists($activityReport->file_path)) {
            Storage::disk('public')->delete($activityReport->file_path);
        }

        $activityReport->delete();

        return redirect()->route('admin.activity-reports.index')->with('success', "Rapport d'activité supprimé.");
    }

    public function togglePublish(ActivityReport $activityReport): RedirectResponse
    {
        $newStatus = !$activityReport->is_published;
        $activityReport->update([
            'is_published' => $newStatus,
            'published_at' => $newStatus ? now() : null,
        ]);

        return redirect()->route('admin.activity-reports.index')->with('success', $newStatus ? "Rapport publié." : "Rapport dépublié.");
    }

    public function download(ActivityReport $activityReport)
    {
        if (!$activityReport->file_path || !Storage::disk('public')->exists($activityReport->file_path)) {
            abort(404);
        }

        return response()->download(
            Storage::disk('public')->path($activityReport->file_path),
            $activityReport->original_filename
        );
    }
}
