<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JuryEvaluation;
use App\Models\JuryMember;
use Illuminate\View\View;

class JuryEvaluationsAdminController extends Controller
{
    private function ensureAllowed(): void
    {
        if (!in_array(session('admin_role'), ['super_admin', 'manager'], true)) {
            abort(403);
        }
    }

    public function index(JuryMember $juryMember): View
    {
        $this->ensureAllowed();

        $evaluations = $juryMember->evaluations()
            ->with('scores')
            ->orderBy('evaluation_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.jury_evaluations.index', compact('juryMember', 'evaluations'));
    }

    public function rankings(): View
    {
        $this->ensureAllowed();

        $categories = [
            'visual_identity' => 'Meilleure identité visuelle',
            'digital_campaign' => 'Meilleure campagne digitale',
            'professional_presentation' => 'Meilleure présentation professionnelle',
            'jury_favorite' => 'Prix Coup de Cœur du Jury',
        ];

        $rankings = [];

        foreach ($categories as $categoryKey => $categoryLabel) {
            $rankings[$categoryKey] = JuryEvaluation::query()
                ->where('status', 'submitted')
                ->with(['scores' => function ($query) use ($categoryKey) {
                    $query->where('category_key', $categoryKey);
                }])
                ->get()
                ->map(function ($evaluation) use ($categoryKey) {
                    $categoryScore = $evaluation->scores
                        ->where('category_key', $categoryKey)
                        ->sum('score');

                    return [
                        'group_name' => $evaluation->group_name,
                        'category_score' => $categoryScore,
                        'total_score' => $evaluation->total_score,
                        'jury_name' => $evaluation->jury_name,
                        'evaluation_date' => $evaluation->evaluation_date,
                    ];
                })
                ->sortByDesc('category_score')
                ->values();
        }

        return view('admin.jury_evaluations.rankings', compact('rankings', 'categories'));
    }
}
