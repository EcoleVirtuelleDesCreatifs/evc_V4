<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Communique;
use App\Models\Actualite;
use App\Models\Evenement;
use App\Models\Student;
use App\Mail\CommuniqueNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CommuniqueController extends Controller
{
    public function index()
    {
        $communiques = Communique::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('admin.communiques.index', compact('communiques'));
    }

    /**
     * Helper pour récupérer les étudiants actifs correspondant à une cible donnée.
     * Gère les variations de nom (alias) et la casse.
     */
    private function getActiveStudentsForTarget($targetAudience)
    {
        $allActiveStudents = Student::where('status', 'active')->with('user')->get();

        if ($targetAudience === 'all') {
            return $allActiveStudents;
        }

        // Map des alias pour normalisation
        $aliases = [
            // Design
            'design graphique' => 'Design Graphique',
            'design graphic' => 'Design Graphique',
            'graphic design' => 'Design Graphique',
            'infographie' => 'Design Graphique',
            'graphisme' => 'Design Graphique',
            'infographiste' => 'Design Graphique',

            // CM
            'community management' => 'Community Management',
            'community manager' => 'Community Management',
            'social media manager' => 'Community Management',
            'cm' => 'Community Management',

            // Info
            'gestion informatique' => 'Gestion Informatique',
            'informatique' => 'Gestion Informatique',
            'développement' => 'Gestion Informatique',
            'developpement' => 'Gestion Informatique',
            'programmation' => 'Gestion Informatique',
            'dev web' => 'Gestion Informatique',

            // IA
            'intelligence artificielle' => 'Intelligence Artificielle',
            'artificial intelligence' => 'Intelligence Artificielle',
            'ia' => 'Intelligence Artificielle',
            'ai' => 'Intelligence Artificielle',
            'data science' => 'Intelligence Artificielle',
        ];

        // Filtrer les étudiants
        return $allActiveStudents->filter(function ($student) use ($targetAudience, $aliases) {
            if (empty($student->program)) return false;

            $studentProgram = strtolower(trim($student->program));
            $targetKeyNormal = strtolower(trim($targetAudience));

            // 1. Correspondance exacte ou via alias
            if (isset($aliases[$studentProgram]) && $aliases[$studentProgram] === $targetAudience) {
                return true;
            }

            // 2. Correspondance fuzzy (contient le nom de la cible)
            if (str_contains($studentProgram, $targetKeyNormal)) {
                return true;
            }

            // 3. Fallback par mots-clés spécifiques si la cible correspond
            if ($targetAudience === 'Design Graphique' && (str_contains($studentProgram, 'design') || str_contains($studentProgram, 'graphi'))) return true;
            if ($targetAudience === 'Community Management' && (str_contains($studentProgram, 'community') || str_contains($studentProgram, 'manager') || str_contains($studentProgram, 'social'))) return true;
            if ($targetAudience === 'Gestion Informatique' && (str_contains($studentProgram, 'informatique') || str_contains($studentProgram, 'dev') || str_contains($studentProgram, 'code'))) return true;
            if ($targetAudience === 'Intelligence Artificielle' && (str_contains($studentProgram, 'intelligence') || str_contains($studentProgram, 'artificielle') || str_contains($studentProgram, 'data'))) return true;

            return false;
        });
    }

    private function getStudentCounts()
    {
        $counts = [];

        // Calculer pour 'all'
        $counts['all'] = $this->getActiveStudentsForTarget('all')->count();

        // Calculer pour chaque cible spécifique
        foreach (Communique::TARGETS as $key => $label) {
            if ($key !== 'all') {
                $counts[$key] = $this->getActiveStudentsForTarget($key)->count();
            }
        }

        return $counts;
    }

    public function create()
    {
        $studentCounts = $this->getStudentCounts();
        $actualites = Actualite::query()
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'title', 'status', 'published_at']);

        $evenements = Evenement::query()
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'title', 'status', 'event_date', 'published_at']);

        return view('admin.communiques.create', compact('studentCounts', 'actualites', 'evenements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:150|unique:communiques,content',
            'order' => 'integer',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'target_audience' => 'required|string',
            'actualite_id' => 'nullable|integer|exists:actualites,id',
            'evenement_id' => 'nullable|integer|exists:evenements,id',
        ]);

        if (!empty($request->actualite_id) && !empty($request->evenement_id)) {
            return back()
                ->withInput()
                ->withErrors([
                    'actualite_id' => 'Veuillez choisir soit une actualité, soit un évènement (pas les deux).',
                    'evenement_id' => 'Veuillez choisir soit une actualité, soit un évènement (pas les deux).',
                ]);
        }

        $communique = Communique::create([
            'content' => $request->content,
            'is_active' => $request->has('is_active'),
            'order' => $request->order ?? 0,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'target_audience' => $request->target_audience,
            'actualite_id' => $request->actualite_id,
            'evenement_id' => $request->evenement_id,
        ]);

        // Envoyer un email à tous les étudiants actifs si le communiqué est actif
        $sentCount = 0;
        if ($communique->is_active) {

            // Utiliser la méthode robuste pour récupérer les étudiants
            $students = $this->getActiveStudentsForTarget($communique->target_audience);

            \Illuminate\Support\Facades\Log::info("Communiqué #{$communique->id} : " . $students->count() . " étudiants ciblés ({$communique->target_audience})");

            foreach ($students as $student) {
                // Tenter de récupérer l'email via la relation User, sinon via la table students directement
                $recipientEmail = $student->user ? $student->user->email : $student->email;

                if ($recipientEmail) {
                    try {
                        Mail::to($recipientEmail)->send(new CommuniqueNotification($communique));
                        $sentCount++;
                        \Illuminate\Support\Facades\Log::info("Email envoyé à l'étudiant {$student->id} ({$recipientEmail}) pour le communiqué #{$communique->id}");
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Erreur envoi mail communiqué à l'étudiant {$student->id} : " . $e->getMessage());
                    }
                } else {
                    \Illuminate\Support\Facades\Log::warning("Étudiant {$student->id} sans email associé (ni User ni Student).");
                }
            }
        }

        return redirect()->route('admin.communiques.index')->with('success', "Communiqué ajouté. Notification envoyée à $sentCount étudiant(s).");
    }

    public function edit(Communique $communique)
    {
        $studentCounts = $this->getStudentCounts();
        $actualites = Actualite::query()
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'title', 'status', 'published_at']);

        $evenements = Evenement::query()
            ->orderByDesc('id')
            ->limit(100)
            ->get(['id', 'title', 'status', 'event_date', 'published_at']);

        return view('admin.communiques.edit', compact('communique', 'studentCounts', 'actualites', 'evenements'));
    }

    public function update(Request $request, Communique $communique)
    {
        $request->validate([
            'content' => 'required|string|max:150',
            'order' => 'integer',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'target_audience' => 'required|string',
            'actualite_id' => 'nullable|integer|exists:actualites,id',
            'evenement_id' => 'nullable|integer|exists:evenements,id',
        ]);

        if (!empty($request->actualite_id) && !empty($request->evenement_id)) {
            return back()
                ->withInput()
                ->withErrors([
                    'actualite_id' => 'Veuillez choisir soit une actualité, soit un évènement (pas les deux).',
                    'evenement_id' => 'Veuillez choisir soit une actualité, soit un évènement (pas les deux).',
                ]);
        }

        $communique->update([
            'content' => $request->content,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'target_audience' => $request->target_audience,
            'actualite_id' => $request->actualite_id,
            'evenement_id' => $request->evenement_id,
        ]);

        return redirect()->route('admin.communiques.index')->with('success', 'Communiqué mis à jour.');
    }

    public function destroy(Communique $communique)
    {
        $communique->delete();
        return redirect()->route('admin.communiques.index')->with('success', 'Communiqué supprimé.');
    }

    public function toggleStatus(Communique $communique)
    {
        $newStatus = !$communique->is_active;
        $communique->update(['is_active' => $newStatus]);

        $message = 'Statut du communiqué mis à jour.';

        // Si le communiqué devient actif, envoyer les notifications
        if ($newStatus) {
            $sentCount = 0;

            // Utiliser la méthode robuste pour récupérer les étudiants
            $students = $this->getActiveStudentsForTarget($communique->target_audience);

            \Illuminate\Support\Facades\Log::info("Activation communiqué #{$communique->id} : " . $students->count() . " étudiants ciblés");

            foreach ($students as $student) {
                // Tenter de récupérer l'email via la relation User, sinon via la table students directement
                $recipientEmail = $student->user ? $student->user->email : $student->email;

                if ($recipientEmail) {
                    try {
                        Mail::to($recipientEmail)->send(new CommuniqueNotification($communique));
                        $sentCount++;
                        \Illuminate\Support\Facades\Log::info("Email envoyé à l'étudiant {$student->id} ({$recipientEmail}) pour le communiqué #{$communique->id}");
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Erreur envoi mail activation communiqué à l'étudiant {$student->id} : " . $e->getMessage());
                    }
                } else {
                     \Illuminate\Support\Facades\Log::warning("Étudiant {$student->id} sans email associé (ni User ni Student) lors de l'activation.");
                }
            }

            $message .= " Notification envoyée à $sentCount étudiant(s).";
        }

        return redirect()->route('admin.communiques.index')->with('success', $message);
    }
}
