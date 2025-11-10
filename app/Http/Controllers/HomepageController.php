<?php

namespace App\Http\Controllers;

use App\Models\PreRegistration;
use App\Models\Evenement;
use App\Models\Actualite;
use App\Models\CandidatureCollaborateur;
use App\Models\DemandePartenariat;
use App\Models\CandidatureFormateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\PreRegistrationSubmitted;
use App\Mail\AdminPreRegistrationNotification;
use App\Http\Requests\StoreCandidatureRequest;
use App\Services\PreRegistrationService;

class HomepageController extends Controller
{
    /**
     * Affiche la page d'accueil.
     */
    public function index()
    {
        // Récupérer les 3 événements publiés : "A la une" en premier, puis les plus récents
        $evenements = Evenement::where('status', 'published')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Récupérer les 3 actualités publiées : "A la une" en premier, puis les plus récentes
        $actualites = Actualite::where('status', 'published')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('evenements', 'actualites'));
    }

    /**
     * Nouveau endpoint pour le formulaire "/candidature" (noms de champs personnalisés du client).
     */
    public function candidatureStore(StoreCandidatureRequest $request, PreRegistrationService $service)
    {
        // Données validées
        $v = $request->validated();
        // Déléguer au service (normalisation, création, e-mails)
        [$pre, $mailWarnings] = $service->handle($v);

        $successMsg = "Votre candidature a été envoyée avec succès. Un e-mail de confirmation vous sera adressé et notre équipe vous répondra sous 24h.";
        if ($request->expectsJson() || $request->ajax()) {
            $resp = ['success' => $successMsg];
            if (!empty($mailWarnings)) { $resp['warnings'] = $mailWarnings; }
            return response()->json($resp);
        }
        $redirect = redirect()->route('preinscription.start')->with('success', $successMsg);
        if (!empty($mailWarnings)) {
            $redirect->with('warning', implode(' ', $mailWarnings));
        }
        return $redirect;
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
        // Vidéos de la chaîne YouTube EVC
        // https://www.youtube.com/@ecolevirtuelledescreatifs459
        // IMPORTANT: Remplacez ces IDs par les vrais IDs de vos vidéos YouTube
        // Pour obtenir l'ID: allez sur votre vidéo, l'ID est après "watch?v=" dans l'URL
        $videos = [
            [
                'id' => 'M7lc1UVf-VE', // REMPLACER par votre ID réel
                'title' => 'Tutoriel Photoshop : Créer un Flyer Professionnel',
                'description' => 'Apprenez à créer un flyer professionnel de A à Z avec Photoshop. Techniques de design graphique pour débutants et intermédiaires.',
                'speaker' => 'Bilé Bossombra',
            ],
            [
                'id' => 'Q8TXgCzxEnw', // REMPLACER par votre ID réel
                'title' => 'Community Management : Stratégie de Contenu 2024',
                'description' => 'Les meilleures pratiques pour gérer vos réseaux sociaux et créer du contenu engageant qui convertit.',
                'speaker' => 'Équipe EVC',
            ],
            [
                'id' => '3q3aH7X9-sE', // REMPLACER par votre ID réel
                'title' => 'Design Graphique : Les Bases de la Typographie',
                'description' => 'Maîtrisez l\'art de la typographie pour créer des designs percutants. Choix des polices, hiérarchie et lisibilité.',
                'speaker' => 'Bilé Bossombra',
            ],
            [
                'id' => 'V74l_zS1x8E', // REMPLACER par votre ID réel
                'title' => 'Intelligence Artificielle : ChatGPT pour les Créatifs',
                'description' => 'Comment utiliser ChatGPT et les outils IA pour booster votre créativité et votre productivité en tant que designer.',
                'speaker' => 'Équipe EVC',
            ],
            [
                'id' => 'M7lc1UVf-VE', // REMPLACER par votre ID réel
                'title' => 'Illustrator : Créer un Logo de A à Z',
                'description' => 'Tutoriel complet pour créer un logo professionnel avec Adobe Illustrator. De l\'esquisse à la vectorisation.',
                'speaker' => 'Bilé Bossombra',
            ],
            [
                'id' => 'Q8TXgCzxEnw', // REMPLACER par votre ID réel
                'title' => 'Social Media Marketing : Publicité Facebook & Instagram',
                'description' => 'Guide complet pour créer et gérer des campagnes publicitaires efficaces sur Facebook et Instagram.',
                'speaker' => 'Équipe EVC',
            ],
            [
                'id' => '3q3aH7X9-sE', // REMPLACER par votre ID réel
                'title' => 'Canva Pro : Astuces et Techniques Avancées',
                'description' => 'Découvrez les fonctionnalités avancées de Canva Pro pour créer des designs professionnels rapidement.',
                'speaker' => 'Équipe EVC',
            ],
            [
                'id' => 'V74l_zS1x8E', // REMPLACER par votre ID réel
                'title' => 'Portfolio de Designer : Comment se Démarquer',
                'description' => 'Conseils pour créer un portfolio qui attire l\'attention des recruteurs et des clients potentiels.',
                'speaker' => 'Bilé Bossombra',
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

    /**
     * Affiche le détail d'un événement.
     */
    public function showEvenement($slug)
    {
        // Récupérer uniquement les événements publiés
        $evenement = Evenement::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Incrémenter le compteur de vues
        $evenement->incrementViews();
        
        return view('evenement-detail', compact('evenement'));
    }

    /**
     * Affiche la liste complète des actualités.
     */
    public function actualites()
    {
        // Récupérer toutes les actualités publiées : "A la une" en premier, puis les plus récentes
        $actualites = Actualite::where('status', 'published')
            ->orderBy('is_featured', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('actualites-liste', compact('actualites'));
    }

    /**
     * Affiche le détail d'une actualité.
     */
    public function showActualite($slug)
    {
        // Récupérer uniquement les actualités publiées
        $actualite = Actualite::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
        
        // Incrémenter le compteur de vues
        $actualite->increment('views_count');
        
        return view('actualite-detail', compact('actualite'));
    }

    /**
     * Traite la soumission du formulaire Collaborateur.
     */
    public function collaborateurSubmit(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:30',
            'poste' => 'required|string|max:255',
            'experience' => 'required|string|max:50',
            'message' => 'required|string|max:5000',
            'cv' => 'required|file|mimes:pdf|max:2048',
            'portfolio' => 'nullable|url|max:500',
        ]);

        // Upload du CV
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('candidatures/collaborateurs', 'public');
            $validated['cv_path'] = $cvPath;
        }

        // Enregistrer la candidature dans la base de données
        $candidature = CandidatureCollaborateur::create($validated);

        // Envoyer un email à l'admin
        try {
            // Utiliser l'email depuis .env ou une valeur par défaut
            $adminEmail = env('MAIL_ADMIN_ADDRESS') ?: env('MAIL_FROM_ADDRESS', 'recrutement@evc.ci');
            
            // Vérifier que l'email est valide avant d'envoyer
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::send('emails.collaborateur-candidature', ['data' => $validated], function ($message) use ($adminEmail, $validated) {
                    $message->to($adminEmail)
                        ->subject('Nouvelle candidature Collaborateur - ' . $validated['prenom'] . ' ' . $validated['nom']);
                });
            } else {
                Log::warning('Email admin non configuré. Candidature enregistrée mais email non envoyé.');
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi email candidature collaborateur: ' . $e->getMessage());
        }

        return redirect()->route('rejoignez-nous.collaborateur')
            ->with('success', 'Votre candidature a été envoyée avec succès ! Nous vous contacterons bientôt.');
    }

    /**
     * Traite la soumission du formulaire Partenaire.
     */
    public function partenaireSubmit(Request $request)
    {
        $validated = $request->validate([
            'organisation' => 'required|string|max:255',
            'nom_contact' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:30',
            'site_web' => 'nullable|url|max:500',
            'type_partenariat' => 'required|string|max:255',
            'secteur' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // Enregistrer la demande dans la base de données
        $demande = DemandePartenariat::create($validated);

        // Envoyer un email à l'admin
        try {
            // Utiliser l'email depuis .env ou une valeur par défaut
            $adminEmail = env('MAIL_ADMIN_ADDRESS') ?: env('MAIL_FROM_ADDRESS', 'partenariats@evc.ci');
            
            // Vérifier que l'email est valide avant d'envoyer
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::send('emails.partenaire-demande', ['data' => $validated], function ($message) use ($adminEmail, $validated) {
                    $message->to($adminEmail)
                        ->subject('Nouvelle demande de partenariat - ' . $validated['organisation']);
                });
            } else {
                Log::warning('Email admin non configuré. Demande de partenariat enregistrée mais email non envoyé.');
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi email demande partenariat: ' . $e->getMessage());
        }

        return redirect()->route('rejoignez-nous.partenaire')
            ->with('success', 'Votre demande de partenariat a été envoyée avec succès ! Nous vous contacterons bientôt.');
    }

    /**
     * Traite la soumission du formulaire Formateur.
     */
    public function formateurSubmit(Request $request)
    {
        $validated = $request->validate([
            'prenom' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:30',
            'domaine' => 'required|string|max:255',
            'experience' => 'required|string|max:50',
            'diplomes' => 'required|string|max:5000',
            'motivation' => 'required|string|max:5000',
            'cv' => 'required|file|mimes:pdf|max:2048',
            'portfolio' => 'nullable|url|max:500',
        ]);

        // Upload du CV
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('candidatures/formateurs', 'public');
            $validated['cv_path'] = $cvPath;
        }

        // Enregistrer la candidature dans la base de données
        $candidature = CandidatureFormateur::create($validated);

        // Envoyer un email à l'admin
        try {
            // Utiliser l'email depuis .env ou une valeur par défaut
            $adminEmail = env('MAIL_ADMIN_ADDRESS') ?: env('MAIL_FROM_ADDRESS', 'formateurs@evc.ci');
            
            // Vérifier que l'email est valide avant d'envoyer
            if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                Mail::send('emails.formateur-candidature', ['data' => $validated], function ($message) use ($adminEmail, $validated) {
                    $message->to($adminEmail)
                        ->subject('Nouvelle candidature Formateur - ' . $validated['prenom'] . ' ' . $validated['nom']);
                });
            } else {
                Log::warning('Email admin non configuré. Candidature formateur enregistrée mais email non envoyé.');
            }
        } catch (\Exception $e) {
            Log::error('Erreur envoi email candidature formateur: ' . $e->getMessage());
        }

        return redirect()->route('rejoignez-nous.formateur')
            ->with('success', 'Votre candidature a été envoyée avec succès ! Nous vous contacterons bientôt.');
    }
}
