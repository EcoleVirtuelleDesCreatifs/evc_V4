<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Seance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SeanceAdminController extends Controller
{
    /**
     * Liste des séances avec filtre par formation.
     */
    public function index(Request $request): View
    {
        $query = Seance::query()->orderByDesc('scheduled_at');

        if ($request->filled('formation')) {
            $query->where('formation', $request->get('formation'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        $seances = $query->get();

        $formations = Student::select('program')
            ->whereNotNull('program')
            ->distinct()
            ->orderBy('program')
            ->pluck('program');

        return view('admin.seances.index', compact('seances', 'formations'));
    }

    /**
     * Formulaire de création d'une séance.
     */
    public function create(): View
    {
        $formations = Student::select('program')
            ->whereNotNull('program')
            ->distinct()
            ->orderBy('program')
            ->pluck('program');

        return view('admin.seances.form', compact('formations'));
    }

    /**
     * Enregistre une nouvelle séance.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSeance($request);

        $validated['created_by'] = session('admin_id') ?? auth()->id();

        Seance::create($validated);

        return redirect()->route('admin.seances.index')
            ->with('success', 'Séance créée avec succès.');
    }

    /**
     * Formulaire d'édition d'une séance.
     */
    public function edit(Seance $seance): View
    {
        $formations = Student::select('program')
            ->whereNotNull('program')
            ->distinct()
            ->orderBy('program')
            ->pluck('program');

        return view('admin.seances.form', compact('seance', 'formations'));
    }

    /**
     * Met à jour une séance.
     */
    public function update(Request $request, Seance $seance): RedirectResponse
    {
        $validated = $this->validateSeance($request, $seance);

        $seance->update($validated);

        return redirect()->route('admin.seances.index')
            ->with('success', 'Séance mise à jour avec succès.');
    }

    /**
     * Supprime une séance.
     */
    public function destroy(Seance $seance): RedirectResponse
    {
        try {
            $seance->delete();
            return redirect()->route('admin.seances.index')
                ->with('success', 'Séance supprimée.');
        } catch (\Exception $e) {
            Log::error('Erreur suppression séance : ' . $e->getMessage());
            return redirect()->route('admin.seances.index')
                ->with('error', 'Impossible de supprimer la séance.');
        }
    }

    /**
     * Page de marquage des présences pour une séance.
     */
    public function attendance(Seance $seance): View
    {
        $students = Student::where('program', $seance->formation)
            ->where('status', 'active')
            ->orderBy('first_name')
            ->get();

        $attendances = Attendance::where('seance_id', $seance->id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        return view('admin.seances.attendance', compact('seance', 'students', 'attendances'));
    }

    /**
     * Sauvegarde les présences d'une séance.
     */
    public function saveAttendance(Request $request, Seance $seance): RedirectResponse
    {
        $data = $request->input('attendances', []);
        $recorder = session('admin_id') ?? auth()->id();

        foreach ($data as $studentId => $record) {
            $status = $record['status'] ?? 'absent';
            if (!in_array($status, ['present', 'absent', 'late', 'excused'])) {
                continue;
            }

            $student = Student::find($studentId);
            if (!$student) {
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'seance_id' => $seance->id,
                    'student_id' => $student->id,
                ],
                [
                    'user_id' => $student->user_id,
                    'status' => $status,
                    'check_method' => $record['check_method'] ?? 'manual',
                    'recorded_by' => $recorder,
                    'recorded_at' => now(),
                    'notes' => $record['notes'] ?? null,
                ]
            );
        }

        return redirect()->route('admin.seances.attendance', $seance)
            ->with('success', 'Présences enregistrées avec succès.');
    }

    /**
     * Validation commune pour la création / modification.
     */
    private function validateSeance(Request $request, ?Seance $seance = null): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'module' => 'required|string|max:255',
            'formateur' => 'required|string|max:255',
            'description' => 'nullable|string',
            'formation' => 'required|string|max:255',
            'type' => 'required|in:onsite,online,hybrid',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:1',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ];

        $type = $request->input('type');
        $isOnline = in_array($type, ['online', 'hybrid']);
        $isOnsite = in_array($type, ['onsite', 'hybrid']);

        $rules['meet_link'] = $isOnline ? 'required|url|max:1000' : 'nullable|url|max:1000';
        $rules['location'] = $isOnsite ? 'required|string|max:255' : 'nullable|string|max:255';

        return $request->validate($rules);
    }
}
