<?php

namespace App\Http\Controllers;

use App\Models\ActivityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ActivityReportPublicController extends Controller
{
    public function index(Request $request): View
    {
        $reports = ActivityReport::query()
            ->where('is_published', true)
            ->orderByDesc('year')
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('activity_reports.index', compact('reports'));
    }

    public function download(ActivityReport $activityReport)
    {
        if (!$activityReport->is_published) {
            abort(404);
        }

        $path = (string) ($activityReport->file_path ?? '');
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, strlen('storage/'));
        }

        if ($path === '' || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        try {
            if (Schema::hasColumn('activity_reports', 'download_count')) {
                $activityReport->increment('download_count');
            }
        } catch (\Throwable $e) {
            // Ne pas bloquer le téléchargement si la colonne n'existe pas encore en production.
        }

        return response()->download(
            Storage::disk('public')->path($path),
            $activityReport->original_filename
        );
    }
}
