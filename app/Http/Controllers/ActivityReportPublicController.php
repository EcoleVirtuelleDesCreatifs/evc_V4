<?php

namespace App\Http\Controllers;

use App\Models\ActivityReport;
use Illuminate\Http\Request;
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
            ->get();

        return view('activity_reports.index', compact('reports'));
    }

    public function download(ActivityReport $activityReport)
    {
        if (!$activityReport->is_published) {
            abort(404);
        }

        if (!$activityReport->file_path || !Storage::disk('public')->exists($activityReport->file_path)) {
            abort(404);
        }

        return response()->download(
            Storage::disk('public')->path($activityReport->file_path),
            $activityReport->original_filename
        );
    }
}
