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
            'visual_identity'           => ['label' => 'Identité visuelle',           'icon' => '🎨', 'color' => '#8b5cf6'],
            'digital_campaign'          => ['label' => 'Campagne digitale',            'icon' => '📱', 'color' => '#3b82f6'],
            'professional_presentation' => ['label' => 'Présentation professionnelle', 'icon' => '🎤', 'color' => '#10b981'],
            'jury_favorite'             => ['label' => 'Coup de Cœur du Jury',         'icon' => '❤️', 'color' => '#f43f5e'],
        ];

        $allGroups = ['Groupe A', 'Groupe B', 'Groupe C', 'Groupe D', 'Groupe E'];

        $evaluations = JuryEvaluation::query()
            ->where('status', 'submitted')
            ->with('scores')
            ->get();

        $totalEvaluations = $evaluations->count();
        $totalJurors      = $evaluations->pluck('jury_name')->unique()->count();

        $rankings = [];
        foreach ($categories as $categoryKey => $meta) {
            $rankings[$categoryKey] = $evaluations->map(function ($evaluation) use ($categoryKey) {
                $categoryScore = $evaluation->scores
                    ->filter(fn($s) => $s->category_key === $categoryKey)
                    ->sum(fn($s) => (int) $s->score);
                return [
                    'group_name'     => $evaluation->group_name,
                    'category_score' => $categoryScore,
                    'total_score'    => (int) $evaluation->total_score,
                    'jury_name'      => $evaluation->jury_name,
                    'evaluation_date'=> $evaluation->evaluation_date,
                ];
            })
            ->sortByDesc('category_score')
            ->values();
        }

        $globalScores = collect($allGroups)->map(function ($group) use ($evaluations) {
            $groupEvals = $evaluations->filter(fn($e) => $e->group_name === $group);
            $count = $groupEvals->count();
            $total = $groupEvals->sum(fn($e) => (int) $e->total_score);
            return [
                'group_name' => $group,
                'total'      => $total,
                'avg'        => $count > 0 ? round($total / $count) : 0,
                'count'      => $count,
            ];
        })->sortByDesc('avg')->values();

        return view('admin.jury_evaluations.rankings', compact(
            'rankings', 'categories', 'globalScores',
            'totalEvaluations', 'totalJurors', 'allGroups'
        ));
    }
}
