<?php

namespace App\Http\Controllers;

use App\Models\JuryEvaluation;
use App\Models\JuryMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class JuryEvaluationController extends Controller
{
    public function create()
    {
        $isEvc = request()->is('evc/*');
        return view('jury.evaluation', [
            'groups'      => $this->groups(),
            'categories'  => $this->categories(),
            'storeRoute'  => $isEvc ? '/evc/jury/evaluation' : '/jury/evaluation',
            'lookupRoute' => $isEvc ? '/evc/jury/evaluation/lookup' : '/jury/evaluation/lookup',
        ]);
    }

    public function lookupMember(Request $request)
    {
        try {
            $identifier = trim((string) $request->input('jury_identifier', ''));

            if (!$identifier) {
                return response()->json(['found' => false]);
            }

            if (!Schema::hasTable('jury_members')) {
                return response()->json(['found' => false, 'debug' => 'table jury_members manquante']);
            }

            $member = JuryMember::query()
                ->where('unique_identifier', $identifier)
                ->where('is_active', true)
                ->first();

            if (!$member) {
                return response()->json(['found' => false]);
            }

            $evaluatedGroups = [];
            if (Schema::hasTable('jury_evaluations')) {
                $evaluatedGroups = JuryEvaluation::query()
                    ->where('jury_member_id', $member->id)
                    ->where('status', 'submitted')
                    ->pluck('group_name')
                    ->all();
            }

            $availableGroups = array_values(
                array_diff(array_keys($this->groups()), $evaluatedGroups)
            );

            return response()->json([
                'found'            => true,
                'id'               => $member->id,
                'name'             => $member->name,
                'title'            => $member->title,
                'evaluated_groups' => $evaluatedGroups,
                'available_groups' => $availableGroups,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'found' => false,
                'debug' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $groups = array_keys($this->groups());
        $categories = $this->categories();

        $rules = [
            'jury_identifier' => ['required', 'string', 'max:100'],
            'evaluation_date' => ['required', 'date'],
            'group_name'      => ['required', Rule::in($groups)],
            'global_comment'  => ['nullable', 'string', 'max:5000'],
            'status'          => ['required', Rule::in(['draft', 'submitted'])],
        ];

        foreach ($categories as $categoryKey => $category) {
            foreach ($category['criteria'] as $criterionKey => $criterionLabel) {
                $rules["scores.{$categoryKey}.{$criterionKey}"] = ['required', 'integer', 'min:0', 'max:20'];
            }
        }

        $validated = $request->validate($rules);

        $juryMember = JuryMember::query()
            ->where('unique_identifier', $validated['jury_identifier'])
            ->where('is_active', true)
            ->first();

        if (!$juryMember) {
            return back()
                ->withInput()
                ->with('error', 'Identifiant non reconnu. Veuillez vérifier votre identifiant unique.');
        }

        $validated['jury_name'] = $juryMember->name;
        $validated['jury_function'] = $juryMember->title;
        $validated['jury_email'] = 'jury-' . $juryMember->id . '@evc.local';
        $validated['jury_member_id'] = $juryMember->id;
        $totalScore = collect($validated['scores'])->flatten()->sum();

        if (
            $validated['status'] === 'submitted'
            && JuryEvaluation::where('jury_member_id', $validated['jury_member_id'])
                ->where('group_name', $validated['group_name'])
                ->where('status', 'submitted')
                ->exists()
        ) {
            return back()
                ->withInput()
                ->with('error', 'Vous avez déjà noté ce groupe. Vous ne pouvez pas noter le même groupe plusieurs fois.');
        }

        try {
            DB::transaction(function () use ($validated, $categories, $totalScore) {
                $evaluation = JuryEvaluation::updateOrCreate(
                    [
                        'jury_email' => $validated['jury_email'],
                        'group_name' => $validated['group_name'],
                    ],
                    [
                        'jury_member_id' => $validated['jury_member_id'],
                        'jury_name' => $validated['jury_name'],
                        'jury_function' => $validated['jury_function'] ?? null,
                        'evaluation_date' => $validated['evaluation_date'],
                        'global_comment' => $validated['global_comment'] ?? null,
                        'total_score' => $totalScore,
                        'status' => $validated['status'],
                    ]
                );

                $evaluation->scores()->delete();

                foreach ($validated['scores'] as $categoryKey => $criteriaScores) {
                    foreach ($criteriaScores as $criterionKey => $score) {
                        $evaluation->scores()->create([
                            'category_key' => $categoryKey,
                            'category_label' => $categories[$categoryKey]['label'],
                            'criterion_key' => $criterionKey,
                            'criterion_label' => $categories[$categoryKey]['criteria'][$criterionKey],
                            'score' => $score,
                            'max_score' => 20,
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l’enregistrement de votre évaluation. Veuillez réessayer.');
        }

        if ($validated['status'] === 'draft') {
            return back()->with('success', 'Votre brouillon a été enregistré avec succès.');
        }

        $thankYouRoute = $request->is('evc/*') ? 'jury.evaluation.thank-you.evc' : 'jury.evaluation.thank-you';

        return redirect()->route($thankYouRoute)->with('success', 'Votre évaluation a été soumise avec succès.');
    }

    public function thankYou()
    {
        return view('jury.thank-you');
    }

    public function getEvaluatedGroups(Request $request)
    {
        // Kept for compatibility
        $juryMemberId = $request->input('jury_member_id');

        if (!$juryMemberId) {
            return response()->json(['evaluated_groups' => []]);
        }

        $evaluatedGroups = JuryEvaluation::query()
            ->where('jury_member_id', $juryMemberId)
            ->where('status', 'submitted')
            ->pluck('group_name')
            ->all();

        return response()->json(['evaluated_groups' => $evaluatedGroups]);
    }

    private function groups(): array
    {
        return [
            'Groupe A' => 'Groupe A',
            'Groupe B' => 'Groupe B',
            'Groupe C' => 'Groupe C',
            'Groupe D' => 'Groupe D',
        ];
    }

    private function availableGroups(?int $juryMemberId = null): array
    {
        $query = JuryEvaluation::query()->where('status', 'submitted');

        if ($juryMemberId) {
            $query->where('jury_member_id', $juryMemberId);
        }

        $evaluatedGroups = $query->pluck('group_name')->all();

        return array_diff_key($this->groups(), array_flip($evaluatedGroups));
    }

    private function activeJuryMembers()
    {
        if (!Schema::hasTable('jury_members')) {
            return collect();
        }

        return JuryMember::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
    }

    private function categories(): array
    {
        return [
            'visual_identity' => [
                'label' => 'Meilleure identité visuelle',
                'icon' => '🎨',
                'theme' => 'purple',
                'criteria' => [
                    'logo_originality' => 'Originalité du logo',
                    'graphic_consistency' => 'Cohérence de la charte graphique',
                    'visual_support_quality' => 'Qualité des supports visuels',
                    'brief_relevance' => 'Pertinence par rapport au brief',
                ],
            ],
            'digital_campaign' => [
                'label' => 'Meilleure campagne digitale',
                'icon' => '📣',
                'theme' => 'green',
                'criteria' => [
                    'digital_strategy' => 'Qualité de la stratégie digitale',
                    'content_creativity' => 'Créativité des contenus',
                    'channel_relevance' => 'Pertinence des canaux choisis',
                    'campaign_impact' => 'Impact potentiel de la campagne',
                ],
            ],
            'professional_presentation' => [
                'label' => 'Meilleure présentation professionnelle',
                'icon' => '👨‍🏫',
                'theme' => 'orange',
                'criteria' => [
                    'presentation_clarity' => 'Clarté de la présentation',
                    'subject_mastery' => 'Maîtrise du sujet',
                    'pitch_quality' => 'Qualité du pitch',
                    'time_posture' => 'Gestion du temps et posture',
                ],
            ],
            'jury_favorite' => [
                'label' => 'Prix Coup de Cœur du Jury',
                'icon' => '❤️',
                'theme' => 'pink',
                'criteria' => [
                    'emotion' => 'Émotion ressentie',
                    'global_originality' => 'Originalité globale du projet',
                    'wow_effect' => 'Effet “waouh”',
                    'real_potential' => 'Potentiel réel du projet',
                ],
            ],
        ];
    }
}
