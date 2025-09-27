<?php

namespace App\Http\Controllers;

use App\Models\PreRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
     * Enregistre une nouvelle demande de pré-inscription.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'age' => 'nullable|integer|min:16',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'required|string|email|max:255|unique:pre_registrations|unique:users,email',
            'whatsapp' => 'nullable|string|max:255',
            'pays' => 'required|string|max:255',
            'niveau_etude' => 'required|string|max:255',
            'choix_formation' => 'required|in:design_graphique,community_management,gestion_informatique,intelligence_artificielle',
            'has_computer' => 'required|boolean',
            'has_smartphone' => 'required|boolean',
            'disponibilite' => 'required|string|max:255',
            'motivation' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('photos_preregistrations', 'public');
            $data['photo'] = $path;
        }

        PreRegistration::create($data);

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
}
