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
            'best_logo'                 => ['label' => 'Meilleur Logo',                         'icon' => '�', 'color' => '#8b5cf6'],
            'best_graphic_charter'      => ['label' => 'Meilleure Charte Graphique',             'icon' => '🎨', 'color' => '#3b82f6'],
            'professional_presentation' => ['label' => 'Meilleure Présentation Professionnelle', 'icon' => '🎤', 'color' => '#10b981'],
            'jury_favorite'             => ['label' => 'Prix Coup de Cœur du Jury',              'icon' => '❤️', 'color' => '#f43f5e'],
        ];

        $allGroups = ['Groupe A', 'Groupe B', 'Groupe C', 'Groupe D'];

        $evaluations = JuryEvaluation::query()
            ->where('status', 'submitted')
            ->whereIn('group_name', $allGroups)
            ->whereHas('juryMember')
            ->with('scores')
            ->get();

        $totalEvaluations = $evaluations->count();
        $totalJurors      = $evaluations->pluck('jury_member_id')->unique()->count();

        $rankings = [];
        foreach ($categories as $categoryKey => $meta) {
            $rankings[$categoryKey] = collect($allGroups)->map(function ($group) use ($evaluations, $categoryKey) {
                $groupEvals = $evaluations->filter(fn($evaluation) => $evaluation->group_name === $group);
                $count = $groupEvals->count();
                $total = $groupEvals->sum(function ($evaluation) use ($categoryKey) {
                    return $evaluation->scores
                        ->filter(fn($score) => $score->category_key === $categoryKey)
                        ->sum(fn($score) => (int) $score->score);
                });

                return [
                    'group_name'     => $group,
                    'category_score' => $count > 0 ? round($total / $count) : 0,
                    'count'          => $count,
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
