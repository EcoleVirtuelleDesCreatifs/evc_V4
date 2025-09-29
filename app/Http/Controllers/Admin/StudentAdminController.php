<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StudentAdminController extends Controller
{
    /**
     * Lister les étudiants d'une formation (slug): design-graphique, community-manager, intelligence-artificielle, gestion-informatique
     */
    public function listByFormation(Request $request, string $formation)
    {
        // Map slug -> label et clés internes possibles
        $formationMap = [
            'design-graphique' => ['label' => 'Design Graphique', 'keys' => ['design_graphique','infographie','design-graphique']],
            'community-manager' => ['label' => 'Community Management', 'keys' => ['community_management','community-manager']],
            'intelligence-artificielle' => ['label' => 'Intelligence Artificielle', 'keys' => ['intelligence_artificielle','intelligence-artificielle']],
            'gestion-informatique' => ['label' => 'Gestion Informatique', 'keys' => ['gestion_informatique','informatique','gestion-informatique']],
        ];
        abort_unless(isset($formationMap[$formation]), 404);

        $label = $formationMap[$formation]['label'];
        $keys = $formationMap[$formation]['keys'];

        $userCols = Schema::getColumnListing('users');
        $select = ['id','email','created_at'];
        foreach (['first_name','last_name','name','phone','city','ville'] as $c) {
            if (in_array($c, $userCols, true)) $select[] = $c;
        }

        // Base requête sur users si une colonne formation existe, sinon fallback via pre_registrations
        $students = collect();
        if (in_array('formation_souhaitee', $userCols, true) || in_array('choix_formation', $userCols, true)) {
            $query = DB::table('users')->select($select);
            if (in_array('formation_souhaitee', $userCols, true)) {
                $query->whereIn('formation_souhaitee', $keys);
            } else {
                $query->whereIn('choix_formation', $keys);
            }
            if (in_array('status', $userCols, true)) {
                $query->where('status', 'Actif');
            } elseif (in_array('email_verified_at', $userCols, true)) {
                $query->whereNotNull('email_verified_at');
            }
            $students = $query->orderBy('id','desc')->get();
        } else {
            // Fallback: joindre via pre_registrations par email et status Actif
            $preCols = Schema::getColumnListing('pre_registrations');
            $raws = [];
            $raws[] = 'u.id';
            $raws[] = 'u.email';
            $raws[] = 'u.created_at';
            $raws[] = in_array('first_name', $userCols, true) ? DB::raw('COALESCE(u.first_name, "") as first_name') : DB::raw('"" as first_name');
            $raws[] = in_array('last_name', $userCols, true) ? DB::raw('COALESCE(u.last_name, "") as last_name') : DB::raw('"" as last_name');
            $raws[] = in_array('name', $userCols, true) ? DB::raw('COALESCE(u.name, "") as name') : DB::raw('"" as name');
            $raws[] = in_array('phone', $userCols, true) ? DB::raw('COALESCE(u.phone, "") as phone') : DB::raw('"" as phone');
            $raws[] = in_array('city', $userCols, true) ? DB::raw('COALESCE(u.city, "") as city') : DB::raw('"" as city');

            $pre = DB::table('pre_registrations as p')
                ->join('users as u', 'u.email', '=', 'p.email')
                ->whereIn('p.choix_formation', $keys)
                ->where('p.status', 'Actif')
                ->select($raws);
            if (in_array('email_verified_at', $userCols, true)) {
                $pre->whereNotNull('u.email_verified_at');
            }
            $students = $pre->orderBy('u.id','desc')->get();
        }

        // Formatter pour la vue existante
        $rows = $students->map(function($u){
            $prenom = property_exists($u,'first_name') ? $u->first_name : '';
            $nom = property_exists($u,'last_name') ? $u->last_name : '';
            if ((!$prenom || !$nom) && property_exists($u,'name')) {
                $parts = preg_split('/\s+/', (string)$u->name, 2);
                $prenom = $prenom ?: ($parts[0] ?? '');
                $nom = $nom ?: ($parts[1] ?? '');
            }
            $ville = property_exists($u,'city') ? $u->city : (property_exists($u,'ville') ? $u->ville : '');
            return [
                'id' => $u->id,
                'email' => $u->email,
                'prenom' => $prenom,
                'nom' => $nom,
                'phone' => property_exists($u,'phone') ? $u->phone : '',
                'ville' => $ville,
                'created_at' => $u->created_at,
                'tp_count' => 0,
                'progression' => 0,
            ];
        })->values();

        $data = [
            'formation' => $formation,
            'formation_name' => $label,
            'students' => $rows,
            'stats' => [
                'total' => $rows->count(),
                'active' => $rows->count(),
                'avg_progression' => 0,
            ],
        ];

        return view('admin.students.by-formation', compact('data'));
    }

    /**
     * Profil étudiant (admin)
     */
    public function profile(int $id)
    {
        $cols = Schema::getColumnListing('users');
        $select = ['id','email','created_at'];
        foreach (['first_name','last_name','name','phone','city','country','ville','pays','formation_souhaitee','choix_formation'] as $c) {
            if (in_array($c, $cols, true)) $select[] = $c;
        }
        $u = DB::table('users')->select($select)->where('id', $id)->first();
        abort_unless($u, 404);

        // Hydrater prenom/nom depuis name au besoin
        $prenom = property_exists($u,'first_name') ? ($u->first_name ?? '') : '';
        $nom = property_exists($u,'last_name') ? ($u->last_name ?? '') : '';
        if ((!$prenom || !$nom) && property_exists($u,'name')) {
            $parts = preg_split('/\s+/', (string)($u->name ?? ''), 2);
            $prenom = $prenom ?: ($parts[0] ?? '');
            $nom = $nom ?: ($parts[1] ?? '');
        }

        // Ville/Pays
        $ville = property_exists($u,'city') ? ($u->city ?? '') : (property_exists($u,'ville') ? ($u->ville ?? '') : '');
        $pays = property_exists($u,'country') ? ($u->country ?? '') : (property_exists($u,'pays') ? ($u->pays ?? '') : '');

        // Formation
        $formationKey = '';
        if (property_exists($u,'formation_souhaitee')) $formationKey = (string)($u->formation_souhaitee ?? '');
        elseif (property_exists($u,'choix_formation')) $formationKey = (string)($u->choix_formation ?? '');
        $formationMap = [
            'design_graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'intelligence_artificielle' => 'Intelligence Artificielle',
            'gestion_informatique' => 'Gestion Informatique',
            'infographie' => 'Design Graphique',
            'informatique' => 'Gestion Informatique',
            'design-graphique' => 'Design Graphique',
            'community-manager' => 'Community Management',
            'intelligence-artificielle' => 'Intelligence Artificielle',
            'gestion-informatique' => 'Gestion Informatique',
        ];
        $formationLabel = $formationMap[$formationKey] ?? $formationKey;

        // Compléments depuis pre_registrations (toujours récupérer la dernière pour enrichir le profil)
        $pre = DB::table('pre_registrations')->where('email', $u->email)->orderByDesc('id')->first();
        if ($pre) {
            if (!$ville && property_exists($pre,'ville')) $ville = $pre->ville;
            if (!$pays && property_exists($pre,'pays')) $pays = $pre->pays;
            if (!$formationLabel && property_exists($pre,'choix_formation')) {
                $formationLabel = $formationMap[$pre->choix_formation] ?? $pre->choix_formation;
            }
        }

        // Photo profil: priorité à users.profile_photo, sinon pre_registrations.photo
        $photoUrl = null;
        if (in_array('profile_photo', $cols, true) && !empty($u->profile_photo)) {
            $photoUrl = asset('storage/' . ltrim($u->profile_photo, '/'));
        } elseif ($pre && property_exists($pre, 'photo') && !empty($pre->photo)) {
            $photoUrl = asset('storage/' . ltrim($pre->photo, '/'));
        }

        $data = [
            'student' => [
                'id' => $u->id,
                'email' => $u->email,
                'prenom' => $prenom ?: '—',
                'nom' => $nom ?: '—',
                'phone' => property_exists($u,'phone') ? ($u->phone ?? '') : '',
                'ville' => $ville ?: '—',
                'pays' => $pays ?: '—',
                'created_at' => $u->created_at,
                'formation_souhaitee' => $formationLabel ?: '—',
                'photo_url' => $photoUrl,
            ],
            'prereg' => $pre ? [
                'niveau_etude' => property_exists($pre,'niveau_etude') ? ($pre->niveau_etude ?? '—') : '—',
                'domaine_etude' => property_exists($pre,'domaine_etude') ? ($pre->domaine_etude ?? '—') : '—',
                'choix_formation' => property_exists($pre,'choix_formation') ? ($formationMap[$pre->choix_formation] ?? $pre->choix_formation) : ($formationLabel ?: '—'),
                'niveau_dans_formation' => property_exists($pre,'niveau_dans_formation') ? ($pre->niveau_dans_formation ?? '—') : '—',
                'disponibilite' => property_exists($pre,'disponibilite') ? ($pre->disponibilite ?? '—') : '—',
                'has_computer' => property_exists($pre,'has_computer') ? (bool)$pre->has_computer : null,
                'has_smartphone' => property_exists($pre,'has_smartphone') ? (bool)$pre->has_smartphone : null,
                'origine' => property_exists($pre,'how_known') ? ($pre->how_known ?? ($pre->origine ?? '—')) : (property_exists($pre,'origine') ? ($pre->origine ?? '—') : '—'),
                'motivation' => property_exists($pre,'motivation') ? ($pre->motivation ?? '') : '',
                'competences' => property_exists($pre,'competences') ? ($pre->competences ?? '') : '',
                'status' => property_exists($pre,'status') ? ($pre->status ?? '—') : '—',
            ] : null,
            'stats' => [
                'total_tp' => 0,
                'tp_valides' => 0,
                'tp_en_cours' => 0,
                'progression' => 0,
                'total_files_size' => 0,
            ],
            'projects' => [],
        ];

        return view('admin.students.profile', compact('data'));
    }

    /**
     * Éditer un étudiant (admin)
     */
    public function edit(int $id)
    {
        $cols = Schema::getColumnListing('users');
        $select = ['id','email','created_at'];
        foreach (['first_name','last_name','name','phone','city','country','ville','pays','formation_souhaitee','choix_formation','profile_photo'] as $c) {
            if (in_array($c, $cols, true)) $select[] = $c;
        }
        $u = DB::table('users')->select($select)->where('id', $id)->first();
        abort_unless($u, 404);

        // Hydrater prenom/nom
        $prenom = property_exists($u,'first_name') ? ($u->first_name ?? '') : '';
        $nom = property_exists($u,'last_name') ? ($u->last_name ?? '') : '';
        if ((!$prenom || !$nom) && property_exists($u,'name')) {
            $parts = preg_split('/\s+/', (string)($u->name ?? ''), 2);
            $prenom = $prenom ?: ($parts[0] ?? '');
            $nom = $nom ?: ($parts[1] ?? '');
        }

        $ville = property_exists($u,'city') ? ($u->city ?? '') : (property_exists($u,'ville') ? ($u->ville ?? '') : '');
        $pays = property_exists($u,'country') ? ($u->country ?? '') : (property_exists($u,'pays') ? ($u->pays ?? '') : '');
        $formationKey = '';
        if (property_exists($u,'formation_souhaitee')) $formationKey = (string)($u->formation_souhaitee ?? '');
        elseif (property_exists($u,'choix_formation')) $formationKey = (string)($u->choix_formation ?? '');
        $formationMap = [
            'design_graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'intelligence_artificielle' => 'Intelligence Artificielle',
            'gestion_informatique' => 'Gestion Informatique',
            'infographie' => 'Design Graphique',
            'informatique' => 'Gestion Informatique',
            'design-graphique' => 'Design Graphique',
            'community-manager' => 'Community Management',
            'intelligence-artificielle' => 'Intelligence Artificielle',
            'gestion-informatique' => 'Gestion Informatique',
        ];
        $formationLabel = $formationMap[$formationKey] ?? $formationKey;

        $photoUrl = null;
        if (in_array('profile_photo', $cols, true) && !empty($u->profile_photo)) {
            $photoUrl = asset('storage/' . ltrim($u->profile_photo, '/'));
        }

        // Tableau attendu par la vue
        $student = [
            'id' => $u->id,
            'email' => $u->email,
            'prenom' => $prenom ?: '—',
            'nom' => $nom ?: '—',
            'phone' => property_exists($u,'phone') ? ($u->phone ?? '') : '',
            'ville' => $ville ?: '—',
            'pays' => $pays ?: '—',
            'created_at' => $u->created_at,
            'formation_souhaitee' => $formationLabel ?: '—',
            'photo_url' => $photoUrl,
        ];

        return view('admin.students.edit', compact('student'));
    }
}
