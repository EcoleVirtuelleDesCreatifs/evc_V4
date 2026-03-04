<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CertificationController extends Controller
{
    public function index()
    {
        $userId = session('user_id');
        $student = DB::table('students')->where('user_id', $userId)->first();
        if (!$student) return redirect()->back()->with('error', 'Profil introuvable.');

        $certifications = DB::table('certifications')->where('is_active', true)
            ->where(function ($q) use ($student) {
                $q->where('formation', $student->program)->orWhereNull('formation')->orWhere('formation', '');
            })->orderBy('created_at', 'desc')->get()->map(function ($cert) use ($student) {
                $attempt = DB::table('certification_attempts')->where('certification_id', $cert->id)->where('student_id', $student->id)->first();
                $cert->attempt = $attempt;
                $cert->questions_count = DB::table('certification_questions')->where('certification_id', $cert->id)->count();
                $cert->can_start = !$attempt;
                $cert->status_label = !$attempt ? 'Disponible' : match ($attempt->status) {
                    'in_progress' => 'En cours', 'submitted' => 'En attente de notation',
                    'graded' => $attempt->passed ? 'Réussi ✓' : 'Non réussi', default => 'Non commencé',
                };
                return $cert;
            });
        $formationSlug = $this->getFormationSlug($student->program);
        return view('certifications.index', compact('certifications', 'student', 'formationSlug'));
    }

    public function start($id)
    {
        $student = DB::table('students')->where('user_id', session('user_id'))->first();
        if (!$student) return redirect()->back()->with('error', 'Profil introuvable.');
        $certification = DB::table('certifications')->where('id', $id)->where('is_active', true)->first();
        if (!$certification) return redirect()->back()->with('error', 'Certification inactive.');
        $existing = DB::table('certification_attempts')->where('certification_id', $id)->where('student_id', $student->id)->first();
        if ($existing && $existing->status !== 'in_progress') return redirect()->back()->with('error', 'Déjà passée.');
        if ($existing) return redirect()->route('certification.take', $id);
        $formationSlug = $this->getFormationSlug($student->program);
        return view('certifications.start', compact('certification', 'student', 'formationSlug'));
    }

    public function confirmStart(Request $request, $id)
    {
        $student = DB::table('students')->where('user_id', session('user_id'))->first();
        if (!$student) return redirect()->back()->with('error', 'Profil introuvable.');
        $cert = DB::table('certifications')->where('id', $id)->where('is_active', true)->first();
        if (!$cert) return redirect()->back()->with('error', 'Certification inactive.');
        $existing = DB::table('certification_attempts')->where('certification_id', $id)->where('student_id', $student->id)->first();
        if ($existing) {
            return $existing->status === 'in_progress' ? redirect()->route('certification.take', $id) : redirect()->back()->with('error', 'Déjà passée.');
        }
        $now = now();
        $attemptId = DB::table('certification_attempts')->insertGetId([
            'certification_id' => $id, 'student_id' => $student->id, 'started_at' => $now,
            'finished_at' => (clone $now)->addMinutes($cert->duration_minutes),
            'status' => 'in_progress', 'created_at' => $now, 'updated_at' => $now,
        ]);
        foreach (DB::table('certification_questions')->where('certification_id', $id)->get() as $q) {
            DB::table('certification_answers')->insert(['attempt_id' => $attemptId, 'question_id' => $q->id, 'created_at' => $now, 'updated_at' => $now]);
        }
        return redirect()->route('certification.take', $id);
    }

    public function take($id)
    {
        $student = DB::table('students')->where('user_id', session('user_id'))->first();
        if (!$student) return redirect()->back()->with('error', 'Profil introuvable.');
        $certification = DB::table('certifications')->where('id', $id)->first();
        if (!$certification) return redirect()->back()->with('error', 'Certification introuvable.');
        $attempt = DB::table('certification_attempts')->where('certification_id', $id)->where('student_id', $student->id)->where('status', 'in_progress')->first();
        if (!$attempt) return redirect()->back()->with('error', 'Aucune tentative en cours.');
        $finishedAt = Carbon::parse($attempt->finished_at);
        if (now()->gte($finishedAt)) {
            $this->finalizeAttempt($attempt->id, true);
            return redirect()->route('certification.result', $id)->with('info', 'Temps écoulé. Test soumis automatiquement.');
        }
        $remainingSeconds = now()->diffInSeconds($finishedAt, false);
        $questions = DB::table('certification_questions')->where('certification_id', $id)->orderBy('order_index')->get()->map(function ($q) use ($attempt) {
            if ($q->type === 'qcm') $q->options = DB::table('certification_options')->where('question_id', $q->id)->orderBy('order_index')->get();
            $q->answer = DB::table('certification_answers')->where('attempt_id', $attempt->id)->where('question_id', $q->id)->first();
            return $q;
        });
        $formationSlug = $this->getFormationSlug($student->program);
        return view('certifications.take', compact('certification', 'attempt', 'questions', 'remainingSeconds', 'student', 'formationSlug'));
    }

    public function saveAnswer(Request $request, $id)
    {
        $student = DB::table('students')->where('user_id', session('user_id'))->first();
        if (!$student) return response()->json(['error' => 'Profil introuvable'], 403);
        $attempt = DB::table('certification_attempts')->where('certification_id', $id)->where('student_id', $student->id)->where('status', 'in_progress')->first();
        if (!$attempt) return response()->json(['error' => 'Pas de tentative'], 403);
        if (now()->gte(Carbon::parse($attempt->finished_at))) {
            $this->finalizeAttempt($attempt->id, true);
            return response()->json(['error' => 'Temps écoulé', 'expired' => true], 403);
        }
        $qId = $request->input('question_id');
        $question = DB::table('certification_questions')->where('id', $qId)->first();
        if (!$question) return response()->json(['error' => 'Question introuvable'], 404);
        $data = ['updated_at' => now()];
        if ($question->type === 'qcm') {
            $optId = $request->input('selected_option_id');
            $opt = DB::table('certification_options')->where('id', $optId)->first();
            $data['selected_option_id'] = $optId;
            $data['is_correct'] = $opt ? $opt->is_correct : false;
            $data['score'] = ($opt && $opt->is_correct) ? $question->points : 0;
        } else {
            $data['answer_text'] = $request->input('answer_text', '');
        }
        DB::table('certification_answers')->where('attempt_id', $attempt->id)->where('question_id', $qId)->update($data);
        return response()->json(['success' => true]);
    }

    public function submit(Request $request, $id)
    {
        $student = DB::table('students')->where('user_id', session('user_id'))->first();
        if (!$student) return redirect()->back()->with('error', 'Profil introuvable.');
        $attempt = DB::table('certification_attempts')->where('certification_id', $id)->where('student_id', $student->id)->where('status', 'in_progress')->first();
        if (!$attempt) return redirect()->back()->with('error', 'Pas de tentative en cours.');
        $this->finalizeAttempt($attempt->id, false);
        return redirect()->route('certification.result', $id)->with('success', 'Certification soumise.');
    }

    public function result($id)
    {
        $student = DB::table('students')->where('user_id', session('user_id'))->first();
        if (!$student) return redirect()->back()->with('error', 'Profil introuvable.');
        $certification = DB::table('certifications')->where('id', $id)->first();
        $attempt = DB::table('certification_attempts')->where('certification_id', $id)->where('student_id', $student->id)->first();
        if (!$attempt) return redirect()->back()->with('error', 'Aucune tentative.');
        $formationSlug = $this->getFormationSlug($student->program);
        return view('certifications.result', compact('certification', 'attempt', 'student', 'formationSlug'));
    }

    private function finalizeAttempt($attemptId, bool $isAuto): void
    {
        $attempt = DB::table('certification_attempts')->where('id', $attemptId)->first();
        if (!$attempt || $attempt->status !== 'in_progress') return;
        $qcmScore = DB::table('certification_answers')->join('certification_questions', 'certification_answers.question_id', '=', 'certification_questions.id')
            ->where('certification_answers.attempt_id', $attemptId)->where('certification_questions.type', 'qcm')->sum('certification_answers.score');
        $hasRedaction = DB::table('certification_answers')->join('certification_questions', 'certification_answers.question_id', '=', 'certification_questions.id')
            ->where('certification_answers.attempt_id', $attemptId)->where('certification_questions.type', 'redaction')->exists();
        $cert = DB::table('certifications')->where('id', $attempt->certification_id)->first();
        $pct = $cert->total_points > 0 ? round(($qcmScore / $cert->total_points) * 100, 2) : 0;
        $upd = ['submitted_at' => now(), 'is_auto_submitted' => $isAuto, 'score' => $qcmScore, 'status' => $hasRedaction ? 'submitted' : 'graded', 'updated_at' => now()];
        if (!$hasRedaction) { $upd['score_percentage'] = $pct; $upd['passed'] = $pct >= $cert->passing_score; }
        DB::table('certification_attempts')->where('id', $attemptId)->update($upd);
    }

    private function getFormationSlug(?string $p): string
    {
        if (!$p) return 'design-graphique';
        $l = strtolower($p);
        if (str_contains($l, 'design') && str_contains($l, 'community')) return 'design-graphique-community-manager';
        if (str_contains($l, 'community')) return 'community-management';
        if (str_contains($l, 'informatique')) return 'gestion-informatique';
        if (str_contains($l, 'intelligence')) return 'intelligence-artificielle';
        return 'design-graphique';
    }
}
