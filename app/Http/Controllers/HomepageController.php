<?php

namespace App\Http\Controllers;

use App\Models\PreRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PreRegistrationSubmitted;
use App\Mail\AdminPreRegistrationNotification;

class HomepageController extends Controller
{
    /**
     * Affiche la page d'accueil.
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * Nouveau endpoint pour le formulaire "/candidature" (noms de champs personnalisés du client).
     */
    public function candidatureStore(Request $request)
    {
        // Normalisations de valeurs
        $programmeMap = [
            'Infographie' => 'infographie',
            'Community Management' => 'community_management',
            'Informatique' => 'informatique',
            'Infographie + Community Management' => 'infographie_cm',
            'infographie' => 'infographie',
            'community_management' => 'community_management',
            'informatique' => 'informatique',
            'infographie_cm' => 'infographie_cm',
        ];
        $niveauFormationMap = [
            'Aucune notion' => 'aucune_notion',
            'Certaines notions' => 'quelques_notions',
            'Monter en compétence' => 'me_perfectionner',
            'aucune_notion' => 'aucune_notion',
            'quelques_notions' => 'quelques_notions',
            'me_perfectionner' => 'me_perfectionner',
        ];
        $origineMap = [
            'Réseaux sociaux' => 'reseaux',
            'Ami' => 'ami',
            'Publicité' => 'publicite',
            'Autre' => 'autre',
            'reseaux' => 'reseaux',
            'ami' => 'ami',
            'publicite' => 'publicite',
            'autre' => 'autre',
        ];

        $validator = Validator::make($request->all(), [
            // Informations personnelles
            'nom_complet' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'age' => 'required|integer|min:10|max:100',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'nationalite' => 'required|string|max:120',
            'photo_profil' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            // Coordonnées
            'email' => 'required|string|email|max:255|unique:pre_registrations|unique:users,email',
            'whatsapp' => 'required|string|max:30',
            'ville_pays' => 'required|string|max:180',
            // Académiques & pro
            'niveau_etude' => 'required|string|max:255',
            'domaine_etude' => 'required|string|max:255',
            'competences' => 'required|string|max:1500',
            // Formation
            'programme' => 'required|string',
            'niveau_formation' => 'required|string',
            'motivation' => 'required|string|max:5000',
            'origine' => 'required|string',
            // Équipements
            'ordinateur' => 'required|string|in:Oui,Non',
            'smartphone' => 'required|string|in:Oui,Non',
            'disponibilite' => 'required|in:semaine_soir,weekend,flexible',
            // Consentements
            'veracite' => 'accepted',
            'consentement' => 'accepted',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $v = $validator->validated();

        // Découpage ville / pays si possible
        $ville = null; $pays = null;
        if (!empty($v['ville_pays'])) {
            if (str_contains($v['ville_pays'], '/')) {
                [$ville, $pays] = array_map('trim', explode('/', $v['ville_pays'], 2));
            } elseif (str_contains($v['ville_pays'], ',')) {
                [$ville, $pays] = array_map('trim', explode(',', $v['ville_pays'], 2));
            } else {
                $ville = trim($v['ville_pays']);
            }
        }

        // Normalisation programme -> enums internes et choix_formation
        $programme = $programmeMap[$v['programme']] ?? 'infographie';
        $choixFormationMap = [
            'infographie' => 'design_graphique',
            'community_management' => 'community_management',
            'informatique' => 'gestion_informatique',
            'infographie_cm' => 'design_graphique', // fallback sur un enum accepté
        ];
        $choixFormation = $choixFormationMap[$programme] ?? 'design_graphique';

        $niveauDansFormation = $niveauFormationMap[$v['niveau_formation']] ?? 'aucune_notion';
        $howKnown = $origineMap[$v['origine']] ?? 'autre';

        // Construire payload conforme au modèle PreRegistration
        $data = [
            'nom' => $v['nom_complet'],
            'prenom' => $v['prenom'],
            'age' => $v['age'],
            'date_naissance' => $v['date_naissance'],
            'sexe' => $v['sexe'],
            'nationalite' => $v['nationalite'],
            'email' => $v['email'],
            'whatsapp' => $v['whatsapp'],
            'ville' => $ville,
            'pays' => $pays,
            'niveau_etude' => $v['niveau_etude'],
            'domaine_etude' => $v['domaine_etude'],
            'competences' => $v['competences'],
            'programme' => $programme,
            'choix_formation' => $choixFormation,
            'niveau_dans_formation' => $niveauDansFormation,
            'how_known' => $howKnown,
            'has_computer' => $v['ordinateur'] === 'Oui',
            'has_smartphone' => $v['smartphone'] === 'Oui',
            'disponibilite' => $v['disponibilite'],
            'motivation' => $v['motivation'],
            'certify' => (bool)$request->boolean('veracite'),
            'consent' => (bool)$request->boolean('consentement'),
        ];

        if ($request->hasFile('photo_profil')) {
            $data['photo'] = $request->file('photo_profil')->store('photos_preregistrations', 'public');
        }

        $pre = PreRegistration::create($data);

        // Reutilise la logique d'envoi mail
        $useQueue = config('queue.default') && config('queue.default') !== 'sync' && !app()->environment('local');
        try {
            if ($useQueue) {
                Mail::to($pre->email)->queue(new PreRegistrationSubmitted($pre));
            } else {
                Mail::to($pre->email)->send(new PreRegistrationSubmitted($pre));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send candidate pre-registration email (candidature)', ['error' => $e->getMessage()]);
        }
        try {
            $adminEmail = config('mail.admin_address') ?? config('mail.from.address');
            if ($adminEmail) {
                if ($useQueue) {
                    Mail::to($adminEmail)->queue(new AdminPreRegistrationNotification($pre));
                } else {
                    Mail::to($adminEmail)->send(new AdminPreRegistrationNotification($pre));
                }
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send admin pre-registration email (candidature)', ['error' => $e->getMessage()]);
        }

        return response()->json(['success' => 'Votre candidature a été envoyée avec succès. Nous vous contacterons prochainement.']);
    }

    /**
     * Enregistre une nouvelle demande de pré-inscription.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Informations personnelles
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'age' => 'required|integer|min:16|max:100',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'nationalite' => 'required|string|max:120',
            'photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            // Coordonnées
            'email' => 'required|string|email|max:255|unique:pre_registrations|unique:users,email',
            'whatsapp' => 'required|string|max:30',
            'ville' => 'required|string|max:120',
            'pays' => 'required|string|max:120',
            // Académiques & pro
            'niveau_etude' => 'required|string|max:255',
            'domaine_etude' => 'required|string|max:255',
            'competences' => 'required|string|max:1500',
            // Formation
            'programme' => 'required|in:infographie,community_management,informatique,infographie_cm',
            'choix_formation' => 'required|in:design_graphique,community_management,gestion_informatique,intelligence_artificielle',
            'niveau_dans_formation' => 'required|in:aucune_notion,quelques_notions,me_perfectionner',
            'how_known' => 'required|in:reseaux,ami,publicite,autre',
            'motivation' => 'required|string|max:5000',
            // Équipements
            'has_computer' => 'required|boolean',
            'has_smartphone' => 'required|boolean',
            'disponibilite' => 'required|in:semaine_soir,weekend,flexible',
            // Consentements
            'certify' => 'accepted',
            'consent' => 'accepted',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        // Normalize booleans just in case
        $data['has_computer'] = filter_var($request->boolean('has_computer'), FILTER_VALIDATE_BOOLEAN);
        $data['has_smartphone'] = filter_var($request->boolean('has_smartphone'), FILTER_VALIDATE_BOOLEAN);
        $data['certify'] = $request->boolean('certify');
        $data['consent'] = $request->boolean('consent');

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos_preregistrations', 'public');
            $data['photo'] = $path;
        }

        $pre = PreRegistration::create($data);

        // Helper: decide between queue() and send() depending on env/queue config
        $useQueue = config('queue.default') && config('queue.default') !== 'sync' && !app()->environment('local');

        // Send confirmation email to candidate
        try {
            Log::info('PreRegistration mail: preparing candidate email', [
                'useQueue' => $useQueue,
                'to' => $pre->email,
                'pre_id' => $pre->id,
            ]);
            if ($useQueue) {
                Mail::to($pre->email)->queue(new PreRegistrationSubmitted($pre));
                Log::info('PreRegistration mail: candidate email queued', ['to' => $pre->email, 'pre_id' => $pre->id]);
            } else {
                Mail::to($pre->email)->send(new PreRegistrationSubmitted($pre));
                Log::info('PreRegistration mail: candidate email sent (sync)', ['to' => $pre->email, 'pre_id' => $pre->id]);
            }
        } catch (\Throwable $e) {
            // Log silently to avoid breaking UX
            Log::error('Failed to send candidate pre-registration email', ['error' => $e->getMessage()]);
        }

        // Send notification to admin
        try {
            $adminEmail = config('mail.admin_address') ?? config('mail.from.address');
            if ($adminEmail) {
                Log::info('PreRegistration mail: preparing admin email', [
                    'useQueue' => $useQueue,
                    'to' => $adminEmail,
                    'pre_id' => $pre->id,
                ]);
                if ($useQueue) {
                    Mail::to($adminEmail)->queue(new AdminPreRegistrationNotification($pre));
                    Log::info('PreRegistration mail: admin email queued', ['to' => $adminEmail, 'pre_id' => $pre->id]);
                } else {
                    Mail::to($adminEmail)->send(new AdminPreRegistrationNotification($pre));
                    Log::info('PreRegistration mail: admin email sent (sync)', ['to' => $adminEmail, 'pre_id' => $pre->id]);
                }
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send admin pre-registration email', ['error' => $e->getMessage()]);
        }

        return response()->json(['success' => 'Votre demande de pré-inscription a été envoyée avec succès ! Nous vous contacterons bientôt.']);
    }

    /**
     * Affiche la page de la WebTV.
     */
    public function webtv()
    {
        $videos = [
            [
                'id' => 'M7lc1UVf-VE', // A popular design video
                'title' => 'Introduction au Design UI/UX',
                'description' => 'Une vue d\'ensemble complète des principes de l\'interface utilisateur et de l\'expérience utilisateur.',
                'speaker' => 'Jane Doe',
            ],
            [
                'id' => 'Q8TXgCzxEnw', // A popular coding video
                'title' => 'Maîtriser Tailwind CSS',
                'description' => 'Apprenez à construire des designs web modernes et responsives avec Tailwind CSS à partir de zéro.',
                'speaker' => 'John Smith',
            ],
            [
                'id' => '3q3aH7X9-sE', // A popular marketing video
                'title' => 'Stratégie Social Media pour 2025',
                'description' => 'Découvrez les dernières tendances et stratégies pour dominer les médias sociaux dans l\'année à venir.',
                'speaker' => 'Emily White',
            ],
            [
                'id' => 'V74l_zS1x8E', // A popular AI video
                'title' => 'Le Futur de l\'Intelligence Artificielle',
                'description' => 'Une conférence sur l\'avenir de l\'IA et son impact sur notre monde.',
                'speaker' => 'Dr. Alan Grant',
            ],
        ];

        return view('webtv', compact('videos'));
    }

    /**
     * Affiche la page de présentation.
     */
    public function presentation()
    {
        return view('presentation');
    }

    /**
     * Affiche la page des formations.
     */
    public function formations()
    {
        return view('formations');
    }

    /**
     * Affiche la page des travaux étudiants.
     */
    public function travaux()
    {
        return view('travaux');
    }

    /**
     * Affiche la page des lauréats.
     */
    public function laureats()
    {
        return view('laureats');
    }
}
