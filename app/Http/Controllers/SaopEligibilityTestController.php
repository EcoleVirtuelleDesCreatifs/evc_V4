<?php

namespace App\Http\Controllers;

use App\Models\SaopEligibilityTest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SaopEligibilityTestController extends Controller
{
    public const QUESTIONS = [
        "Quelle est la configuration technique complète de l’ordinateur de bureau ou portable que vous exploiterez au quotidien durant votre formation (RAM, processeur, stockage, etc.) ?",
        "De quel volume horaire hebdomadaire disposez-vous de manière effective pour la production des projets en dehors des heures de formations ?",
        "Quel est le projet professionnel précis que vous visez à l’issue de votre formation à EVC ? Dans quel but voulez-vous faire cette formation ?",
        "Quelle est la nature et la stabilité de la connexion internet dont vous disposez pour le téléchargement des ressources de haute définition ? Avez-vous un Wi-Fi à domicile, utilisez-vous un cyber café ou une souscription mobile ?",
        "Avez-vous déjà manipulé un logiciel de la Suite Adobe (Photoshop, Illustrator, InDesign) ou démarrez-vous un apprentissage totalement à zéro ?",
        "Face à un environnement d'apprentissage 100 % digital, quelles sont vos stratégies personnelles pour maintenir votre autodiscipline et éviter la procrastination ?",
        "La formation d'EVC exclut les examens théoriques au profit d'une matrice intensive de 75 projets pratiques. Comment gérez-vous le stress des rendus multiples et la critique technique de vos livrables ?",
        "Quelle est votre maîtrise ou votre niveau d'aisance actuel avec les outils informatiques de base (gestion des fichiers, navigation internet, installation de logiciels) ?",
        "Dans quelle mesure êtes-vous disponible pour participer activement aux événements majeurs de l'écosystème, notamment la remise des certificats en ligne ou en présentiel ? Si vous résidez en Côte d’Ivoire, pourrez-vous effectuer le déplacement ?",
        "Si votre profil est retenu, êtes-vous prêt à valider administrativement et financièrement votre inscription sous un délai de 72 heures pour bloquer votre place au sein de la cohorte ?",
    ];

    public function index()
    {
        return response()
            ->view('eligibilite.index', ['questions' => self::QUESTIONS])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function store(Request $request)
    {
        $isAutoSubmit = $request->boolean('auto_submit');

        $validated = $request->validate([
            'full_name' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'whatsapp' => 'nullable|string|max:50',
            'formation' => 'nullable|string|max:100',
            'started_at' => 'required|date',
            'auto_submit' => 'nullable|boolean',
            'answers' => $isAutoSubmit ? 'nullable|array|max:10' : 'required|array|size:10',
            'answers.*' => $isAutoSubmit ? 'nullable|string|max:5000' : 'required|string|min:10|max:5000',
        ]);

        $startedAt = Carbon::parse($validated['started_at']);
        $durationSeconds = max(0, $startedAt->diffInSeconds(now()));

        if ($durationSeconds > 3600 && !$isAutoSubmit) {
            return back()
                ->withInput()
                ->withErrors(['time' => 'Le délai imparti de 1h est dépassé. Veuillez recommencer le test.']);
        }

        $durationSeconds = min($durationSeconds, 3600);

        $answers = [];
        foreach (self::QUESTIONS as $index => $question) {
            $answers[] = [
                'question' => $question,
                'answer' => trim((string) ($validated['answers'][$index] ?? '')),
            ];
        }

        SaopEligibilityTest::create([
            'full_name' => $validated['full_name'] ?? 'Candidat non renseigné',
            'email' => $validated['email'] ?? 'non-renseigne-' . now()->timestamp . '-' . uniqid() . '@saop.local',
            'whatsapp' => $validated['whatsapp'] ?? null,
            'formation' => $validated['formation'] ?? null,
            'answers' => $answers,
            'duration_seconds' => $durationSeconds,
            'started_at' => $startedAt,
            'submitted_at' => now(),
            'status' => $isAutoSubmit ? 'auto_submitted' : 'submitted',
            'ip_address' => $request->ip(),
            'user_agent' => (string) $request->userAgent(),
        ]);

        return redirect()
            ->route('eligibilite.saop')
            ->with('success', $isAutoSubmit
                ? 'Le temps est écoulé. Vos informations et réponses saisies ont été enregistrées automatiquement.'
                : 'Votre test d’éligibilité a été soumis avec succès. Notre équipe pédagogique analysera vos réponses.');
    }
}
