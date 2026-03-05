<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CertificationAdminController extends Controller
{
    /**
     * Liste de toutes les certifications
     */
    public function index()
    {
        $certifications = DB::table('certifications')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($cert) {
                $cert->questions_count = DB::table('certification_questions')
                    ->where('certification_id', $cert->id)
                    ->count();
                $cert->participants_count = DB::table('certification_student')
                    ->where('certification_id', $cert->id)
                    ->count();
                $cert->attempts_count = DB::table('certification_attempts')
                    ->where('certification_id', $cert->id)
                    ->count();
                $cert->submitted_count = DB::table('certification_attempts')
                    ->where('certification_id', $cert->id)
                    ->whereIn('status', ['submitted', 'graded'])
                    ->count();
                $cert->graded_count = DB::table('certification_attempts')
                    ->where('certification_id', $cert->id)
                    ->where('status', 'graded')
                    ->count();
                $cert->passed_count = DB::table('certification_attempts')
                    ->where('certification_id', $cert->id)
                    ->where('passed', true)
                    ->count();
                return $cert;
            });

        return view('admin.certifications.index', compact('certifications'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $formations = [
            'Design Graphique',
            'Community Management',
            'Design Graphique & Community Management',
            'Gestion Informatique',
            'Intelligence Artificielle',
        ];

        // Étudiants actifs avec au moins 2 TP/projets
        $eligibleStudents = $this->getEligibleStudents();

        return view('admin.certifications.create', compact('formations', 'eligibleStudents'));
    }

    /**
     * Enregistrer une nouvelle certification
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formation' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'passing_score' => 'required|numeric|min:0|max:100',
            'instructions' => 'nullable|string',
            'shuffle_questions' => 'boolean',
            'status' => 'required|in:draft,published,scheduled',
            'scheduled_at' => 'nullable|date|after:now',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'integer|exists:students,id',
        ]);

        $status = $validated['status'] ?? 'draft';
        $isActive = $status === 'published';
        $scheduledAt = null;

        if ($status === 'scheduled') {
            if (empty($validated['scheduled_at'])) {
                return back()->withErrors(['scheduled_at' => 'La date de programmation est requise.'])->withInput();
            }
            $scheduledAt = $validated['scheduled_at'];
            $isActive = false;
        }

        $certId = DB::table('certifications')->insertGetId([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'formation' => $validated['formation'] ?? null,
            'duration_minutes' => $validated['duration_minutes'],
            'passing_score' => $validated['passing_score'],
            'instructions' => $validated['instructions'] ?? null,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'is_active' => $isActive,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'total_points' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assigner les étudiants sélectionnés
        $studentIds = $validated['student_ids'] ?? [];
        $notifiedCount = 0;

        if (!empty($studentIds)) {
            $certification = DB::table('certifications')->where('id', $certId)->first();

            foreach ($studentIds as $studentId) {
                DB::table('certification_student')->insert([
                    'certification_id' => $certId,
                    'student_id' => $studentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Envoyer email de notification si publié
                if ($status === 'published' || $status === 'scheduled') {
                    $sent = $this->notifyStudent($studentId, $certification);
                    if ($sent) {
                        DB::table('certification_student')
                            ->where('certification_id', $certId)
                            ->where('student_id', $studentId)
                            ->update(['notified_at' => now()]);
                        $notifiedCount++;
                    }
                }
            }
        }

        $msg = 'Certification créée. Ajoutez maintenant les questions.';
        if ($notifiedCount > 0) {
            $msg .= " {$notifiedCount} étudiant(s) notifié(s) par email.";
        }

        return redirect()->route('admin.certifications.edit', $certId)
            ->with('success', $msg);
    }

    /**
     * Formulaire d'édition avec gestion des questions
     */
    public function edit($id)
    {
        $certification = DB::table('certifications')->where('id', $id)->first();
        if (!$certification) {
            return redirect()->route('admin.certifications.index')
                ->with('error', 'Certification introuvable.');
        }

        $formations = [
            'Design Graphique',
            'Community Management',
            'Design Graphique & Community Management',
            'Gestion Informatique',
            'Intelligence Artificielle',
        ];

        $questions = DB::table('certification_questions')
            ->where('certification_id', $id)
            ->orderBy('order_index')
            ->get()
            ->map(function ($q) {
                if ($q->type === 'qcm') {
                    $q->options = DB::table('certification_options')
                        ->where('question_id', $q->id)
                        ->orderBy('order_index')
                        ->get();
                }
                return $q;
            });

        // Stats des tentatives
        $attempts = DB::table('certification_attempts')
            ->join('students', 'certification_attempts.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where('certification_attempts.certification_id', $id)
            ->select(
                'certification_attempts.*',
                'students.first_name',
                'students.last_name',
                'students.program',
                'users.email'
            )
            ->orderBy('certification_attempts.created_at', 'desc')
            ->get();

        // Étudiants éligibles et déjà assignés
        $eligibleStudents = $this->getEligibleStudents();
        $assignedStudentIds = DB::table('certification_student')
            ->where('certification_id', $id)
            ->pluck('student_id')
            ->toArray();

        return view('admin.certifications.edit', compact(
            'certification',
            'formations',
            'questions',
            'attempts',
            'eligibleStudents',
            'assignedStudentIds'
        ));
    }

    /**
     * Mettre à jour une certification
     */
    public function update(Request $request, $id)
    {
        $current = DB::table('certifications')->where('id', $id)->first();
        if (!$current) {
            return back()->with('error', 'Certification introuvable.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formation' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5|max:480',
            'passing_score' => 'required|numeric|min:0|max:100',
            'instructions' => 'nullable|string',
            'shuffle_questions' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'nullable|in:draft,published,scheduled',
            'scheduled_at' => 'nullable|date',
            'student_ids' => 'nullable|array',
            'student_ids.*' => 'integer|exists:students,id',
        ]);

        $status = $validated['status'] ?? ($current->status ?? 'draft');
        $isActive = $request->boolean('is_active');
        $scheduledAt = $validated['scheduled_at'] ?? null;

        if (!array_key_exists('status', $validated) || $validated['status'] === null) {
            if ($isActive && $status === 'draft') {
                $status = 'published';
            }
            if (!$isActive) {
                $status = 'draft';
            }
        }

        if ($status === 'published') {
            $isActive = true;
        } elseif ($status === 'draft') {
            $isActive = false;
        }

        DB::table('certifications')->where('id', $id)->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'formation' => $validated['formation'] ?? null,
            'duration_minutes' => $validated['duration_minutes'],
            'passing_score' => $validated['passing_score'],
            'instructions' => $validated['instructions'] ?? null,
            'shuffle_questions' => $request->boolean('shuffle_questions'),
            'is_active' => $isActive,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'updated_at' => now(),
        ]);

        // Mise à jour des étudiants assignés
        $studentIds = $validated['student_ids'] ?? [];
        $existingIds = DB::table('certification_student')
            ->where('certification_id', $id)
            ->pluck('student_id')
            ->toArray();

        $newIds = array_diff($studentIds, $existingIds);
        $removedIds = array_diff($existingIds, $studentIds);

        // Supprimer les étudiants retirés (seulement si pas de tentative en cours)
        if (!empty($removedIds)) {
            DB::table('certification_student')
                ->where('certification_id', $id)
                ->whereIn('student_id', $removedIds)
                ->delete();
        }

        // Ajouter les nouveaux étudiants et notifier
        $certification = DB::table('certifications')->where('id', $id)->first();
        $shouldNotify = (bool) ($certification && $certification->is_active && in_array($certification->status, ['published', 'scheduled'], true));
        $notifiedCount = 0;

        foreach ($newIds as $studentId) {
            DB::table('certification_student')->insert([
                'certification_id' => $id,
                'student_id' => $studentId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($shouldNotify) {
                $sent = $this->notifyStudent($studentId, $certification);
                if ($sent) {
                    DB::table('certification_student')
                        ->where('certification_id', $id)
                        ->where('student_id', $studentId)
                        ->update(['notified_at' => now()]);
                    $notifiedCount++;
                }
            }
        }

        $msg = 'Certification mise à jour.';
        if ($notifiedCount > 0) {
            $msg .= " {$notifiedCount} nouvel(aux) étudiant(s) notifié(s) par email.";
        }

        return back()->with('success', $msg);
    }

    /**
     * Activer/Désactiver une certification
     */
    public function toggleActive($id)
    {
        $cert = DB::table('certifications')->where('id', $id)->first();
        if (!$cert) {
            return back()->with('error', 'Certification introuvable.');
        }

        DB::table('certifications')->where('id', $id)->update([
            'is_active' => !$cert->is_active,
            'updated_at' => now(),
        ]);

        $status = !$cert->is_active ? 'activée' : 'désactivée';
        return back()->with('success', "Certification {$status}.");
    }

    /**
     * Supprimer une certification
     */
    public function destroy($id)
    {
        DB::table('certifications')->where('id', $id)->delete();
        return redirect()->route('admin.certifications.index')
            ->with('success', 'Certification supprimée.');
    }

    // ─── GESTION DES QUESTIONS ──────────────────────────────────────

    /**
     * Ajouter une question
     */
    public function storeQuestion(Request $request, $certificationId)
    {
        $validated = $request->validate([
            'type' => 'required|in:qcm,redaction',
            'question_text' => 'required|string',
            'points' => 'required|numeric|min:0.5',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:5120',
            'options' => 'required_if:type,qcm|array|min:2',
            'options.*.text' => 'required_if:type,qcm|nullable|string',
            'options.*.is_correct' => 'nullable',
            'correct_option' => 'required_if:type,qcm|integer',
        ]);

        $maxOrder = DB::table('certification_questions')
            ->where('certification_id', $certificationId)
            ->max('order_index') ?? 0;

        $mediaUrl = null;
        if ($request->hasFile('media')) {
            $mediaUrl = $request->file('media')->store('certifications/media', 'public');
        }

        $questionId = DB::table('certification_questions')->insertGetId([
            'certification_id' => $certificationId,
            'type' => $validated['type'],
            'question_text' => $validated['question_text'],
            'points' => $validated['points'],
            'media_url' => $mediaUrl,
            'order_index' => $maxOrder + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ajouter les options QCM
        if ($validated['type'] === 'qcm' && !empty($validated['options'])) {
            $correctIndex = (int) ($validated['correct_option'] ?? 0);
            foreach ($validated['options'] as $index => $option) {
                if (empty($option['text'])) continue;
                DB::table('certification_options')->insert([
                    'question_id' => $questionId,
                    'option_text' => $option['text'],
                    'is_correct' => ($index == $correctIndex),
                    'order_index' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Recalculer total_points
        $this->recalcTotalPoints($certificationId);

        return back()->with('success', 'Question ajoutée.');
    }

    /**
     * Mettre à jour une question
     */
    public function updateQuestion(Request $request, $questionId)
    {
        $question = DB::table('certification_questions')->where('id', $questionId)->first();
        if (!$question) {
            return back()->with('error', 'Question introuvable.');
        }

        $validated = $request->validate([
            'question_text' => 'required|string',
            'points' => 'required|numeric|min:0.5',
            'media' => 'nullable|file|mimes:jpg,jpeg,png,gif,pdf|max:5120',
            'options' => 'nullable|array|min:2',
            'options.*.text' => 'nullable|string',
            'correct_option' => 'nullable|integer',
        ]);

        $mediaUrl = $question->media_url;
        if ($request->hasFile('media')) {
            if ($mediaUrl) {
                Storage::disk('public')->delete($mediaUrl);
            }
            $mediaUrl = $request->file('media')->store('certifications/media', 'public');
        }

        DB::table('certification_questions')->where('id', $questionId)->update([
            'question_text' => $validated['question_text'],
            'points' => $validated['points'],
            'media_url' => $mediaUrl,
            'updated_at' => now(),
        ]);

        // Mettre à jour les options QCM
        if ($question->type === 'qcm' && !empty($validated['options'])) {
            DB::table('certification_options')->where('question_id', $questionId)->delete();
            $correctIndex = (int) ($validated['correct_option'] ?? 0);
            foreach ($validated['options'] as $index => $option) {
                if (empty($option['text'])) continue;
                DB::table('certification_options')->insert([
                    'question_id' => $questionId,
                    'option_text' => $option['text'],
                    'is_correct' => ($index == $correctIndex),
                    'order_index' => $index,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->recalcTotalPoints($question->certification_id);

        return back()->with('success', 'Question mise à jour.');
    }

    /**
     * Supprimer une question
     */
    public function destroyQuestion($questionId)
    {
        $question = DB::table('certification_questions')->where('id', $questionId)->first();
        if (!$question) {
            return back()->with('error', 'Question introuvable.');
        }

        $certId = $question->certification_id;
        DB::table('certification_questions')->where('id', $questionId)->delete();
        $this->recalcTotalPoints($certId);

        return back()->with('success', 'Question supprimée.');
    }

    // ─── GESTION DES RÉSULTATS ──────────────────────────────────────

    /**
     * Voir les détails d'une tentative (réponses de l'étudiant)
     */
    public function showAttempt($attemptId)
    {
        $attempt = DB::table('certification_attempts')
            ->join('students', 'certification_attempts.student_id', '=', 'students.id')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->join('certifications', 'certification_attempts.certification_id', '=', 'certifications.id')
            ->where('certification_attempts.id', $attemptId)
            ->select(
                'certification_attempts.*',
                'students.first_name',
                'students.last_name',
                'students.program',
                'users.email',
                'certifications.title as certification_title',
                'certifications.total_points',
                'certifications.passing_score',
                'certifications.duration_minutes'
            )
            ->first();

        if (!$attempt) {
            return back()->with('error', 'Tentative introuvable.');
        }

        $answers = DB::table('certification_answers')
            ->join('certification_questions', 'certification_answers.question_id', '=', 'certification_questions.id')
            ->leftJoin('certification_options', 'certification_answers.selected_option_id', '=', 'certification_options.id')
            ->where('certification_answers.attempt_id', $attemptId)
            ->select(
                'certification_answers.*',
                'certification_questions.question_text',
                'certification_questions.type as question_type',
                'certification_questions.points as max_points',
                'certification_questions.media_url',
                'certification_options.option_text as selected_option_text',
                'certification_options.is_correct as option_is_correct'
            )
            ->orderBy('certification_questions.order_index')
            ->get()
            ->map(function ($answer) {
                if ($answer->question_type === 'qcm') {
                    $answer->all_options = DB::table('certification_options')
                        ->where('question_id', $answer->question_id)
                        ->orderBy('order_index')
                        ->get();
                }
                return $answer;
            });

        return view('admin.certifications.attempt', compact('attempt', 'answers'));
    }

    /**
     * Noter une rédaction (admin)
     */
    public function gradeAnswer(Request $request, $answerId)
    {
        $validated = $request->validate([
            'score' => 'required|numeric|min:0',
            'admin_comment' => 'nullable|string|max:1000',
        ]);

        $answer = DB::table('certification_answers')->where('id', $answerId)->first();
        if (!$answer) {
            return back()->with('error', 'Réponse introuvable.');
        }

        // Vérifier que le score ne dépasse pas les points max de la question
        $question = DB::table('certification_questions')->where('id', $answer->question_id)->first();
        if ($validated['score'] > $question->points) {
            return back()->with('error', "Le score ne peut pas dépasser {$question->points} points.");
        }

        DB::table('certification_answers')->where('id', $answerId)->update([
            'score' => $validated['score'],
            'admin_comment' => $validated['admin_comment'] ?? null,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Note enregistrée.');
    }

    /**
     * Finaliser la notation d'une tentative
     */
    public function finalizeGrading($attemptId)
    {
        $attempt = DB::table('certification_attempts')->where('id', $attemptId)->first();
        if (!$attempt) {
            return back()->with('error', 'Tentative introuvable.');
        }

        $certification = DB::table('certifications')->where('id', $attempt->certification_id)->first();

        // Vérifier que toutes les rédactions ont été notées
        $ungradedCount = DB::table('certification_answers')
            ->join('certification_questions', 'certification_answers.question_id', '=', 'certification_questions.id')
            ->where('certification_answers.attempt_id', $attemptId)
            ->where('certification_questions.type', 'redaction')
            ->whereNull('certification_answers.score')
            ->count();

        if ($ungradedCount > 0) {
            return back()->with('error', "Il reste {$ungradedCount} rédaction(s) à noter.");
        }

        // Calculer le score total
        $totalScore = DB::table('certification_answers')
            ->where('attempt_id', $attemptId)
            ->sum('score');

        $scorePercentage = $certification->total_points > 0
            ? round(($totalScore / $certification->total_points) * 100, 2)
            : 0;

        $passed = $scorePercentage >= $certification->passing_score;

        DB::table('certification_attempts')->where('id', $attemptId)->update([
            'score' => $totalScore,
            'score_percentage' => $scorePercentage,
            'passed' => $passed,
            'status' => 'graded',
            'updated_at' => now(),
        ]);

        $result = $passed ? 'réussi' : 'échoué';
        return back()->with('success', "Notation finalisée. L'étudiant a {$result} ({$scorePercentage}%).");
    }

    // ─── HELPERS ─────────────────────────────────────────────────────

    /**
     * Recalculer le total des points d'une certification
     */
    private function recalcTotalPoints($certificationId): void
    {
        $total = DB::table('certification_questions')
            ->where('certification_id', $certificationId)
            ->sum('points');

        DB::table('certifications')->where('id', $certificationId)->update([
            'total_points' => $total,
            'updated_at' => now(),
        ]);
    }

    /**
     * Récupérer les étudiants actifs ayant au moins 2 TP/projets réalisés
     */
    private function getEligibleStudents()
    {
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->where(function ($q) {
                $q->where('students.status', 'active')
                    ->orWhereNull('students.status')
                    ->orWhere('students.status', '');
            })
            ->select('students.*', 'users.email')
            ->get();

        return $students->filter(function ($student) {
            // Compter les TP assignés traités (par student_id)
            $tpCount = DB::table('tp_assignments')
                ->where('student_id', $student->id)
                ->whereIn('status', ['submitted', 'pending', 'validated'])
                ->count();

            // Compter les projets traités (par user_id, table projects)
            $projectCount = 0;
            if ($student->user_id) {
                $projectCount = DB::table('projects')
                    ->where('user_id', $student->user_id)
                    ->whereIn('status', ['en_cours', 'termine', 'valide', 'soumis'])
                    ->count();
            }

            $student->tp_project_count = $tpCount + $projectCount;
            return $student->tp_project_count >= 2;
        })->values();
    }

    /**
     * Notifier un étudiant par email de sa certification assignée
     */
    private function notifyStudent($studentId, $certification): bool
    {
        try {
            $student = DB::table('students')
                ->leftJoin('users', 'students.user_id', '=', 'users.id')
                ->where('students.id', $studentId)
                ->select('students.*', 'users.email')
                ->first();

            if (!$student || empty($student->email)) {
                return false;
            }

            $scheduledInfo = '';
            if ($certification->status === 'scheduled' && $certification->scheduled_at) {
                $date = \Carbon\Carbon::parse($certification->scheduled_at)->format('d/m/Y à H:i');
                $scheduledInfo = "<p style='color:#f59e0b;font-weight:bold;'>📅 Date programmée : {$date}</p>";
            }

            $studentName = trim(($student->first_name ?? '') . ' ' . ($student->last_name ?? ''));
            if (empty($studentName)) {
                $studentName = 'Cher(e) étudiant(e)';
            }

            $instructions = $certification->instructions
                ? $certification->instructions
                : '<p>Aucune consigne spécifique.</p>';

            $formation = $student->program ?? 'votre formation';
            $certUrl = url('/evc/compte/certifications');
            $duration = $certification->duration_minutes;
            $passingScore = $certification->passing_score;

            $htmlBody = view('emails.certification_notification', compact(
                'studentName',
                'formation',
                'scheduledInfo',
                'duration',
                'passingScore',
                'instructions',
                'certUrl'
            ))->render();

            Mail::send([], [], function ($message) use ($student, $certification, $htmlBody) {
                $message->to($student->email)
                    ->subject("🎓 Certification Officielle : {$certification->title} - École Virtuelle des Créatifs")
                    ->html($htmlBody);
            });

            Log::info("Certification email sent to {$student->email} for cert #{$certification->id}");
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send certification email to student #{$studentId}: " . $e->getMessage());
            return false;
        }
    }
}
