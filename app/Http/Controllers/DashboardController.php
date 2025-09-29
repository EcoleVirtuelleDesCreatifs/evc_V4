<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Redirect to the login page to ensure stability.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('login'); 
    }

    /**
     * Placeholder for the design graphique dashboard.
     */
    public function designGraphique(): View
    {
        return view('dashboard.design-graphique', ['data' => 'placeholder']);
    }

    /**
     * A placeholder for the showAllTP method to prevent crashes.
     */
    public function showAllTP(): View
    {
        return view('tp.view', [
            'projects' => [],
            'stats' => [],
            'userProfile' => (object)[]
        ]);
    }

    /**
     * Page d'index des formations (Design Graphique)
     */
    public function formationsIndex(): View
    {
        // Données minimales; à connecter à la base/formations réelles si nécessaire
        $formations = [
            [
                'slug' => 'initiation-graphisme',
                'title' => 'Initiation au Graphisme',
                'level' => 'Débutant',
                'duration' => '4 semaines',
            ],
            [
                'slug' => 'photoshop-avance',
                'title' => 'Photoshop Avancé',
                'level' => 'Intermédiaire',
                'duration' => '6 semaines',
            ],
        ];

        // Modules principaux publiés pour l'étudiant (tolérant au schéma)
        $modulesPrincipaux = [];
        $formationsPubliees = [];
        try {
            $user = Auth::user();
            $formationKeys = ['design_graphique','design-graphique','infographie'];
            if ($user) {
                $uCols = \Illuminate\Support\Facades\Schema::getColumnListing('users');
                if (in_array('formation_souhaitee', $uCols, true) && !empty($user->formation_souhaitee)) {
                    $formationKeys = [$user->formation_souhaitee];
                } elseif (in_array('choix_formation', $uCols, true) && !empty($user->choix_formation)) {
                    $formationKeys = [$user->choix_formation];
                }
            }

            // Détecter une table plausible de modules
            $modulesTable = null;
            foreach (['modules', 'formation_modules', 'cours_modules'] as $t) {
                if (\Illuminate\Support\Facades\Schema::hasTable($t)) { $modulesTable = $t; break; }
            }
            if ($modulesTable) {
                $cols = \Illuminate\Support\Facades\Schema::getColumnListing($modulesTable);
                $q = \Illuminate\Support\Facades\DB::table($modulesTable);
                // Publication
                if (in_array('published', $cols, true)) { $q->where('published', 1); }
                elseif (in_array('is_published', $cols, true)) { $q->where('is_published', 1); }
                // Type principal
                if (in_array('type', $cols, true)) { $q->where('type', 'principal'); }
                elseif (in_array('is_main', $cols, true)) { $q->where('is_main', 1); }
                // Filtre formation si colonne présente
                foreach (['formation','formation_slug','formation_key','programme','filiere'] as $fk) {
                    if (in_array($fk, $cols, true)) { $q->whereIn($fk, $formationKeys); break; }
                }
                // Colonnes à sélectionner au mieux
                $select = [];
                $select[] = in_array('id', $cols, true) ? 'id' : \Illuminate\Support\Facades\DB::raw('NULL as id');
                $select[] = in_array('title', $cols, true) ? 'title' : (in_array('name',$cols,true)?'name':\Illuminate\Support\Facades\DB::raw("'' as title"));
                $select[] = in_array('module_number', $cols, true) ? 'module_number' : (in_array('numero',$cols,true)?'numero':\Illuminate\Support\Facades\DB::raw('NULL as module_number'));
                $select[] = in_array('published_at', $cols, true) ? 'published_at' : (in_array('created_at',$cols,true)?'created_at':\Illuminate\Support\Facades\DB::raw('NULL as published_at'));
                $modulesPrincipaux = $q->orderByDesc(in_array('published_at',$cols,true)?'published_at':'id')->limit(12)->get($select);
            }

            // Formations publiées (tolérant au schéma)
            $formationsTable = null;
            foreach (['formations','courses','programmes','formation_courses'] as $t) {
                if (\Illuminate\Support\Facades\Schema::hasTable($t)) { $formationsTable = $t; break; }
            }
            if ($formationsTable) {
                $fcols = \Illuminate\Support\Facades\Schema::getColumnListing($formationsTable);
                $fq = \Illuminate\Support\Facades\DB::table($formationsTable);
                // Publié
                if (in_array('published', $fcols, true)) { $fq->where('published', 1); }
                elseif (in_array('is_published', $fcols, true)) { $fq->where('is_published', 1); }
                // Filtre formation
                foreach (['formation','formation_slug','formation_key','programme','filiere'] as $fk) {
                    if (in_array($fk, $fcols, true)) { $fq->whereIn($fk, $formationKeys); break; }
                }
                // Sélection
                $fselect = [];
                $fselect[] = in_array('id',$fcols,true)?'id':\Illuminate\Support\Facades\DB::raw('NULL as id');
                $fselect[] = in_array('title',$fcols,true)?'title':(in_array('name',$fcols,true)?'name':\Illuminate\Support\Facades\DB::raw("'' as title"));
                $fselect[] = in_array('category',$fcols,true)?'category':(in_array('categorie',$fcols,true)?'categorie':\Illuminate\Support\Facades\DB::raw("'' as category"));
                $fselect[] = in_array('level',$fcols,true)?'level':(in_array('niveau',$fcols,true)?'niveau':\Illuminate\Support\Facades\DB::raw("'' as level"));
                $fselect[] = in_array('duration',$fcols,true)?'duration':(in_array('duree',$fcols,true)?'duree':\Illuminate\Support\Facades\DB::raw("'' as duration"));
                $fselect[] = in_array('created_at',$fcols,true)?'created_at':\Illuminate\Support\Facades\DB::raw('NULL as created_at');
                $formationsPubliees = $fq->orderByDesc(in_array('created_at',$fcols,true)?'created_at':'id')->limit(12)->get($fselect);
            }
        } catch (\Throwable $e) {
            Log::warning('formationsIndex modulesPrincipaux load failed', ['error' => $e->getMessage()]);
        }

        return view('formations.index', [
            'title' => 'Formations - Design Graphique',
            'formations' => $formations,
            'modules_principaux' => $modulesPrincipaux,
            'formations_publiees' => $formationsPubliees,
        ]);
    }

    /**
     * Liste des formations par catégorie
     */
    public function formationsCategory(string $category): View
    {
        // Placeholder: charger les formations de la catégorie
        $formations = [];
        return view('formations.category', [
            'category' => $category,
            'formations' => $formations,
        ]);
    }

    /**
     * Détail d'une formation
     */
    public function formationsShow(int $id): View
    {
        // Chargement tolérant au schéma depuis une table plausible
        $formationsTable = null;
        foreach (['formations','courses','programmes','formation_courses'] as $t) {
            if (\Illuminate\Support\Facades\Schema::hasTable($t)) { $formationsTable = $t; break; }
        }

        $formation = null;
        if ($formationsTable) {
            $cols = \Illuminate\Support\Facades\Schema::getColumnListing($formationsTable);
            $q = \Illuminate\Support\Facades\DB::table($formationsTable)->where('id', $id);
            // Publié uniquement si colonne présente
            if (in_array('published', $cols, true)) { $q->where('published', 1); }
            elseif (in_array('is_published', $cols, true)) { $q->where('is_published', 1); }

            $select = [];
            $select[] = in_array('id',$cols,true)?'id':\Illuminate\Support\Facades\DB::raw('NULL as id');
            $select[] = in_array('title',$cols,true)?'title':(in_array('name',$cols,true)?'name':\Illuminate\Support\Facades\DB::raw("'' as title"));
            $select[] = in_array('category',$cols,true)?'category':(in_array('categorie',$cols,true)?'categorie':\Illuminate\Support\Facades\DB::raw("'' as category"));
            $select[] = in_array('level',$cols,true)?'level':(in_array('niveau',$cols,true)?'niveau':\Illuminate\Support\Facades\DB::raw("'' as level"));
            $select[] = in_array('duration',$cols,true)?'duration':(in_array('duree',$cols,true)?'duree':\Illuminate\Support\Facades\DB::raw("'' as duration"));
            $select[] = in_array('description',$cols,true)?'description':(in_array('content',$cols,true)?'content':\Illuminate\Support\Facades\DB::raw("'' as description"));
            $select[] = in_array('video_url',$cols,true)?'video_url':(in_array('video',$cols,true)?'video':\Illuminate\Support\Facades\DB::raw("'' as video_url"));
            $select[] = in_array('created_at',$cols,true)?'created_at':\Illuminate\Support\Facades\DB::raw('NULL as created_at');
            $formation = $q->first($select);

            // Related formations for sidebar
            $rq = \Illuminate\Support\Facades\DB::table($formationsTable);
            // Only published if column exists
            if (in_array('published', $cols, true)) { $rq->where('published', 1); }
            elseif (in_array('is_published', $cols, true)) { $rq->where('is_published', 1); }
            // Exclude current id
            if (in_array('id',$cols,true)) { $rq->where('id', '<>', $id); }
            // Same category if possible
            $catCol = in_array('category',$cols,true) ? 'category' : (in_array('categorie',$cols,true) ? 'categorie' : null);
            if ($catCol && $formation && !empty($formation->category)) {
                $rq->where($catCol, $formation->category);
            }
            $rselect = [];
            $rselect[] = in_array('id',$cols,true)?'id':\Illuminate\Support\Facades\DB::raw('NULL as id');
            $rselect[] = in_array('title',$cols,true)?'title':(in_array('name',$cols,true)?'name':\Illuminate\Support\Facades\DB::raw("'' as title"));
            $rselect[] = $catCol ? $catCol.' as category' : \Illuminate\Support\Facades\DB::raw("'' as category");
            $rselect[] = in_array('created_at',$cols,true)?'created_at':\Illuminate\Support\Facades\DB::raw('NULL as created_at');
            $related = $rq->orderByDesc(in_array('created_at',$cols,true)?'created_at':'id')->limit(6)->get($rselect);
        } else {
            $related = collect();
        }

        // Fallback minimal si rien en base
        if (!$formation) {
            $formation = (object) [
                'id' => $id,
                'title' => 'Formation #' . $id,
                'category' => '',
                'level' => '',
                'duration' => '',
                'description' => 'Description à venir',
                'video_url' => '',
                'created_at' => null,
            ];
        }

        return view('formations.show', [
            'formation' => $formation,
            'related_formations' => $related ?? collect(),
        ]);
    }
}
