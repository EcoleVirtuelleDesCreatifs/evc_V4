<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\CertificationEligibilityService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class CertificationEligibilityAdminController extends Controller
{
    private CertificationEligibilityService $service;

    public function __construct(CertificationEligibilityService $service)
    {
        $this->service = $service;
    }

    private function ensureManager(): ?RedirectResponse
    {
        if (! in_array(session('admin_role'), ['super_admin', 'manager'])) {
            return redirect()->back()->with('error', 'Vous n\'avez pas les droits pour gérer l\'éligibilité.');
        }

        return null;
    }

    public function index(Request $request): View
    {
        $filter = $request->get('filter', 'all');
        $q = trim($request->get('q', ''));

        $students = Student::query()
            ->with('user')
            ->where('status', 'active')
            ->where(function ($query) {
                $query->where('program', 'like', '%Design Graphique%')
                    ->orWhere('program', 'like', '%Design Graphic%')
                    ->orWhere('program', 'like', '%design_graphique%')
                    ->orWhere('program', 'like', '%design_graphique_community%');
            })
            ->get();

        $items = $students->map(function (Student $student) {
            $eval = $this->service->evaluate($student);
            $eval['student'] = $student;
            return $eval;
        })->filter(fn ($eval) => $eval['formation_supported']);

        if (filled($q)) {
            $qLower = strtolower($q);
            $items = $items->filter(function ($eval) use ($qLower) {
                $student = $eval['record']->student ?? Student::find($eval['student_id']);
                $values = [
                    strtolower($student?->first_name ?? ''),
                    strtolower($student?->last_name ?? ''),
                    strtolower($student?->student_id ?? ''),
                    strtolower($student?->email ?? ''),
                ];
                return collect($values)->some(fn ($v) => str_contains($v, $qLower));
            });
        }

        $items = match ($filter) {
            'pre_eligible' => $items->filter(fn ($e) => $e['pre_eligible'] && ! $e['eligible']),
            'eligible_confirmed' => $items->filter(fn ($e) => $e['eligible']),
            'not_eligible' => $items->filter(fn ($e) => ! $e['pre_eligible']),
            'reviewing' => $items->filter(fn ($e) => ($e['record']?->admin_status ?? 'pending') === 'reviewing'),
            'payment_incomplete' => $items->filter(fn ($e) => ! $e['payment']['ok']),
            'projects_missing' => $items->filter(fn ($e) => ! $e['projects_ok']),
            'report_missing' => $items->filter(fn ($e) => ! $e['report_ok']),
            'portfolio_missing' => $items->filter(fn ($e) => ! $e['portfolio_ok']),
            'studio_unverified' => $items->filter(fn ($e) => $e['studio_creative_status'] === 'pending'),
            default => $items,
        };

        $stats = [
            'total' => $items->count(),
            'pre_eligible' => $items->where('pre_eligible', true)->where('eligible', false)->count(),
            'eligible_confirmed' => $items->where('eligible', true)->count(),
            'not_eligible' => $items->where('pre_eligible', false)->count(),
            'reviewing' => $items->filter(fn ($e) => ($e['record']?->admin_status ?? 'pending') === 'reviewing')->count(),
        ];

        $statusLabels = $this->statusLabels();

        return view('admin.certification_eligibility.index', compact('items', 'stats', 'filter', 'q', 'statusLabels'));
    }

    public function show(Student $student): View
    {
        $eval = $this->service->evaluate($student);

        if (! $eval['formation_supported']) {
            return view('admin.certification_eligibility.not_supported', compact('student'));
        }

        $record = $this->service->getOrCreateRecord($student, $eval['formation']);
        $histories = $record->histories()->with('admin')->latest()->take(20)->get();

        $statusLabels = $this->statusLabels();

        return view('admin.certification_eligibility.show', compact('student', 'eval', 'record', 'histories', 'statusLabels'));
    }

    public function sync(Student $student): RedirectResponse
    {
        if ($redirect = $this->ensureManager()) {
            return $redirect;
        }

        try {
            $this->service->syncRecord($student);
            return redirect()->back()->with('success', 'Dossier resynchronisé avec succès.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erreur lors de la synchronisation : ' . $e->getMessage());
        }
    }

    public function updateStudioCreative(Request $request, Student $student): RedirectResponse
    {
        if ($redirect = $this->ensureManager()) {
            return $redirect;
        }

        $validated = $request->validate([
            'studio_creative_status' => 'required|in:pending,validated,rejected',
            'studio_creative_name' => 'nullable|string|max:255',
            'studio_creative_comment' => 'nullable|string|max:1000',
        ]);

        try {
            $this->service->setStudioCreative(
                $student,
                $validated['studio_creative_status'],
                $validated['studio_creative_name'] ?? null,
                $validated['studio_creative_comment'] ?? null,
                (int) session('admin_id')
            );
            return redirect()->back()->with('success', 'Statut Studio Creative mis à jour.');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function setReviewing(Request $request, Student $student): RedirectResponse
    {
        if ($redirect = $this->ensureManager()) {
            return $redirect;
        }

        $validated = $request->validate([
            'admin_comment' => 'nullable|string|max:1000',
        ]);

        $this->service->setAdminStatus(
            $student,
            'reviewing',
            $validated['admin_comment'] ?? null,
            (int) session('admin_id')
        );

        return redirect()->back()->with('success', 'Dossier mis en attente de vérification.');
    }

    public function confirmEligible(Request $request, Student $student): RedirectResponse
    {
        if ($redirect = $this->ensureManager()) {
            return $redirect;
        }

        $validated = $request->validate([
            'admin_comment' => 'nullable|string|max:1000',
        ]);

        $eval = $this->service->evaluate($student);

        if (! $eval['pre_eligible']) {
            return redirect()->back()->with('error', 'L\'étudiant n\'est pas pré-éligible.');
        }

        if (! $eval['studio_creative_validated']) {
            return redirect()->back()->with('error', 'Le Studio Creative doit être validé avant confirmation.');
        }

        $this->service->setAdminStatus(
            $student,
            'eligible',
            $validated['admin_comment'] ?? null,
            (int) session('admin_id')
        );

        return redirect()->back()->with('success', 'Éligibilité confirmée avec succès.');
    }

    public function reject(Request $request, Student $student): RedirectResponse
    {
        if ($redirect = $this->ensureManager()) {
            return $redirect;
        }

        $validated = $request->validate([
            'admin_comment' => 'required|string|max:1000',
        ]);

        $this->service->setAdminStatus(
            $student,
            'rejected',
            $validated['admin_comment'],
            (int) session('admin_id')
        );

        return redirect()->back()->with('success', 'Éligibilité refusée. Le motif a été enregistré.');
    }

    private function statusLabels(): array
    {
        return [
            'system' => [
                'not_eligible' => 'Non éligible',
                'pre_eligible' => 'Pré-éligible',
            ],
            'admin' => [
                'pending' => 'En attente',
                'reviewing' => 'En vérification',
                'eligible' => 'Éligible confirmé',
                'rejected' => 'Refusé',
            ],
            'studio' => [
                'pending' => 'À vérifier',
                'validated' => 'Confirmé',
                'rejected' => 'Refusé',
            ],
        ];
    }
}
