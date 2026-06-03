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

            $alreadyVoted = false;
            $votedGroup   = null;
            $availableGroups = array_keys($this->groups());

            if (Schema::hasTable('jury_evaluations')) {
                $existing = JuryEvaluation::query()
                    ->where('jury_member_id', $member->id)
                    ->where('status', 'submitted')
                    ->first();

                if ($existing) {
                    $alreadyVoted    = true;
                    $votedGroup      = $existing->group_name;
                    $availableGroups = [];
                }
            }

            return response()->json([
                'found'          => true,
                'id'             => $member->id,
                'name'           => $member->name,
                'title'          => $member->title,
                'already_voted'  => $alreadyVoted,
                'voted_group'    => $votedGroup,
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

        if ($validated['status'] === 'submitted') {
            $alreadyEvaluated = JuryEvaluation::where('jury_member_id', $validated['jury_member_id'])
                ->where('status', 'submitted')
                ->first();

            if ($alreadyEvaluated) {
                return back()
                    ->withInput()
                    ->with('error', 'Vous avez déjà soumis une évaluation pour ' . $alreadyEvaluated->group_name . '. Chaque membre du jury ne peut noter qu\'un seul groupe.');
            }
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
            'Groupe E' => 'Groupe E',
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
            'best_logo' => [
                'label' => 'Meilleur Logo',
                'icon'  => '🏅',
                'theme' => 'purple',
                'brief' => "Le logo doit être simple, mémorable et adapté à une large gamme de supports (imprimés et numériques). Il peut incorporer des éléments visuels tels que des personnes, des symboles, une dimension poétique, de fraternité et de paix.",
                'criteria' => [
                    'logo_creativity'    => "Créativité et originalité du logo en relation avec les valeurs de l'association",
                    'logo_technical'     => "Technicité de réalisation (vérifiable sur les docs sources)",
                    'logo_visual_choice' => "Pertinence visuelle et typographique",
                    'logo_readability'   => "Hiérarchisation des informations",
                ],
            ],
            'best_graphic_charter' => [
                'label' => 'Meilleure Charte Graphique',
                'icon'  => '🎨',
                'theme' => 'green',
                'brief' => "Palette de couleurs accessibles, polices cohérentes, éléments visuels (icônes, illustrations, motifs), directives d'utilisation du logo, règles couleurs, exemples de mise en page (papeterie, carte de visite, gadgets, flyer, affichage, signature email). Assemblage InDesign requis.",
                'criteria' => [
                    'charter_comprehension' => "Niveau de compréhension du sujet",
                    'charter_constraints'   => "Respect des contraintes graphiques et du brief technique",
                    'charter_coherence'     => "Cohérence de la charte graphique avec l'inclusion et l'accessibilité",
                    'charter_creativity'    => "Créativité et originalité / technicité de réalisation",
                ],
            ],
            'professional_presentation' => [
                'label' => 'Meilleure Présentation Professionnelle',
                'icon'  => '🎤',
                'theme' => 'orange',
                'brief' => "Évaluation de la capacité du groupe à présenter clairement leur projet, à maîtriser le sujet, à respecter les contraintes données et à travailler en équipe de façon structurée et organisée.",
                'criteria' => [
                    'pres_comprehension' => "Niveau de compréhension du sujet",
                    'pres_creativity'    => "Créativité et originalité",
                    'pres_brief_respect' => "Respect du brief technique",
                    'pres_teamwork'      => "Travail de groupe, structure et organisation",
                ],
            ],
            'jury_favorite' => [
                'label' => 'Prix Coup de Coeur du Jury',
                'icon'  => '❤️',
                'theme' => 'pink',
                'brief' => "Distinction libre accordée au groupe qui a su se démarquer par son authenticité, sa cohérence globale et l'émotion suscitée — indépendamment des critères techniques.",
                'criteria' => [
                    'fav_comprehension' => "Niveau de compréhension du sujet",
                    'fav_creativity'    => "Créativité et originalité",
                    'fav_technical'     => "Technicité de réalisation",
                    'fav_teamwork'      => "Travail de groupe, structure et organisation",
                ],
            ],
        ];
    }
}
