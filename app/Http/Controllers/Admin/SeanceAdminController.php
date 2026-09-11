<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Seance;
use App\Models\SeanceQrToken;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class SeanceAdminController extends Controller
{
    /**
     * Liste des séances avec filtres + pagination + compteurs de présences.
     */
    public function index(Request $request): View
    {
        $query = Seance::query()
            ->withCount([
                'attendances as presents_count' => fn ($q) => $q->whereIn('status', ['present', 'late']),
                'attendances as attendances_count',
            ])
            ->orderByDesc('scheduled_at');

        if ($request->filled('formation')) {
            $query->where('formation', $request->get('formation'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        $seances = $query->paginate(15)->withQueryString();

        return view('admin.seances.index', [
            'seances' => $seances,
            'formations' => $this->formationOptions(),
        ]);
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
        return view('admin.seances.form', [
            'seance' => $seance,
            'formations' => $this->formationOptions(),
        ]);
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

        $present = $attendances->where('status', 'present')->count();
        $late = $attendances->where('status', 'late')->count();
        $absent = $attendances->where('status', 'absent')->count();
        $excused = $attendances->where('status', 'excused')->count();
        $total = $students->count();

        $stats = [
            'total' => $total,
            'present' => $present,
            'late' => $late,
            'absent' => $absent,
            'excused' => $excused,
            'marked' => $attendances->count(),
            'unmarked' => max(0, $total - $attendances->count()),
            'rate' => $total > 0 ? round((($present + $late) / $total) * 100, 1) : 0.0,
        ];

        return view('admin.seances.attendance', compact('seance', 'students', 'attendances', 'stats'));
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
                    'user_id' => $student->user?->id,
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
     * Ouvre/génère le QR code de pointage d'une séance.
     */
    public function qr(Seance $seance): View
    {
        $qrToken = SeanceQrToken::updateOrCreate(
            ['seance_id' => $seance->id],
            [
                'token' => hash('sha256', $seance->id . '-' . Str::random(32) . '-' . now()->timestamp),
                'expires_at' => now()->addMinutes(3),
                'closed_at' => null,
            ]
        );

        $qrUrl = route('pointage-qr', ['token' => $qrToken->token]);

        return view('admin.seances.qr', compact('seance', 'qrToken', 'qrUrl'));
    }

    /**
     * Régénère le QR code (nouveau token + expiration 3 min).
     */
    public function regenerateQr(Seance $seance): RedirectResponse
    {
        SeanceQrToken::updateOrCreate(
            ['seance_id' => $seance->id],
            [
                'token' => hash('sha256', $seance->id . '-' . Str::random(32) . '-' . now()->timestamp),
                'expires_at' => now()->addMinutes(3),
                'closed_at' => null,
            ]
        );

        return redirect()->route('admin.seances.qr', $seance)
            ->with('success', 'QR code régénéré.');
    }

    /**
     * Ferme le pointage QR.
     */
    public function closeQr(Seance $seance): RedirectResponse
    {
        $qrToken = SeanceQrToken::where('seance_id', $seance->id)->first();

        if ($qrToken) {
            $qrToken->update(['closed_at' => now()]);
        }

        return redirect()->route('admin.seances.attendance', $seance)
            ->with('success', 'Pointage fermé.');
    }

    /**
     * Liste complète des formations : catalogue EVC + programmes étudiants
     * + formations déjà utilisées par des séances existantes.
     */
    private function formationOptions(): \Illuminate\Support\Collection
    {
        $catalog = collect([
            'Design Graphique',
            'Community Management',
            'Gestion Informatique',
            'Intelligence Artificielle',
        ]);

        $fromStudents = Student::select('program')
            ->whereNotNull('program')
            ->distinct()
            ->pluck('program');

        $fromSeances = Seance::select('formation')
            ->whereNotNull('formation')
            ->distinct()
            ->pluck('formation');

        return $catalog
            ->merge($fromStudents)
            ->merge($fromSeances)
            ->filter()
            ->unique()
            ->sort()
            ->values();
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
