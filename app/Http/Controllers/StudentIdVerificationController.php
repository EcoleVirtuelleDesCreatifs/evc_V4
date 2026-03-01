<?php

namespace App\Http\Controllers;

use App\Helpers\AccountExpirationHelper;
use App\Helpers\ProfilePhotoHelper;
use App\Services\CertificateGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class StudentIdVerificationController extends Controller
{
    public function show(Request $request): View
    {
        $searchedId = trim((string) $request->query('student_id', ''));

        if ($searchedId === '' && auth()->check()) {
            $userId = (int) auth()->id();
            if ($userId > 0) {
                $studentRow = DB::table('students')->where('user_id', $userId)->first();
                if ($studentRow && !empty($studentRow->student_id)) {
                    $searchedId = (string) $studentRow->student_id;
                }
            }
        }

        if ($searchedId !== '') {
            return $this->buildVerificationView($searchedId);
        }

        return view('auth.verify-id', [
            'searchedId' => null,
            'notFound' => false,
            'student' => null,
            'stats' => null,
        ]);
    }

    public function check(Request $request): View
    {
        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:64'],
        ]);

        $searchedId = trim((string) $validated['student_id']);

        return $this->buildVerificationView($searchedId);
    }

    private function buildVerificationView(string $searchedId): View
    {
        $searchedId = trim($searchedId);

        $student = DB::table('students')
            ->where('student_id', $searchedId)
            ->first();

        if (!$student) {
            return view('auth.verify-id', [
                'searchedId' => $searchedId,
                'notFound' => true,
                'student' => null,
                'stats' => null,
            ]);
        }

        $userId = (int) ($student->user_id ?? 0);
        $studentPk = (int) ($student->id ?? 0);

        $userRow = null;
        if ($userId > 0 && Schema::hasTable('users')) {
            $userRow = DB::table('users')->select(['id', 'email', 'created_at'])->where('id', $userId)->first();
        }

        $registrationDate = null;
        if (!empty($student->created_at)) {
            $registrationDate = $student->created_at;
        } elseif ($userId > 0 && Schema::hasTable('users')) {
            $u = DB::table('users')->select('created_at')->where('id', $userId)->first();
            $registrationDate = $u->created_at ?? null;
        }

        $expirationDate = null;
        $daysRemaining = null;
        $isExpired = false;
        try {
            $userForExpiration = (object) [
                'id' => $userId,
                'email' => $userRow->email ?? ($student->email ?? null),
                'created_at' => $userRow->created_at ?? ($registrationDate ?? null),
            ];
            $expirationDate = AccountExpirationHelper::getExpirationDate($userForExpiration);
            $isExpired = AccountExpirationHelper::isAccountExpired($userForExpiration);
            $daysRemaining = AccountExpirationHelper::getDaysRemaining($userForExpiration);
        } catch (\Exception $e) {
            $expirationDate = null;
            $isExpired = false;
            $daysRemaining = null;
        }

        $studentStatus = strtolower((string) ($student->status ?? ''));
        $isActive = ($studentStatus === '' || $studentStatus === 'active') && !$isExpired;

        $designProjectsTotal = Schema::hasTable('design_projects')
            ? (int) DB::table('design_projects')->where('user_id', $userId)->count()
            : 0;

        $designProjectsValidated = Schema::hasTable('design_projects')
            ? (int) DB::table('design_projects')
                ->where('user_id', $userId)
                ->whereIn('status', ['completed', 'validated'])
                ->count()
            : 0;

        $tpTotal = Schema::hasTable('tp')
            ? (int) DB::table('tp')->where('user_id', $userId)->count()
            : 0;

        $tpValidated = 0;
        if (Schema::hasTable('tp') && $userId > 0) {
            $tpValidated = (int) DB::table('tp')
                ->where('user_id', $userId)
                ->where('status', 'validated')
                ->count();
        }

        $tpAssigned = 0;
        if (Schema::hasTable('tp_assignments') && $studentPk > 0) {
            $tpAssigned = (int) DB::table('tp_assignments')
                ->where('student_id', $studentPk)
                ->count();
        }

        $projectsAssigned = 0;
        $projectsCompleted = 0;
        if (Schema::hasTable('projects')) {
            $projectsAssigned = (int) DB::table('projects')
                ->where('user_id', $userId)
                ->count();
            $projectsCompleted = (int) DB::table('projects')
                ->where('user_id', $userId)
                ->where('status', 'valide')
                ->count();
        }

        $reportUploaded = false;
        if (Schema::hasTable('end_of_training_reports') && $studentPk > 0) {
            $reportUploaded = DB::table('end_of_training_reports')
                ->where('student_id', $studentPk)
                ->exists();
        }

        if (!$reportUploaded && Schema::hasTable('tp') && Schema::hasTable('tp_files') && $userId > 0) {
            $reportUploaded = DB::table('tp')
                ->where('tp.user_id', $userId)
                ->leftJoin('tp_files', 'tp.id', '=', 'tp_files.tp_id')
                ->whereNotNull('tp_files.original_name')
                ->whereRaw("LOWER(RIGHT(tp_files.original_name, 4)) = '.pdf'")
                ->exists();
        }

        $program = (string) ($student->program ?? '');
        $programLower = strtolower($program);
        $isDesign = str_contains($programLower, 'design');
        $isCommunity = str_contains($programLower, 'community') || str_contains($programLower, 'manager') || str_contains($programLower, 'management');

        $currentModule = $isDesign && $isCommunity
            ? 'design-graphique-cm'
            : ($isDesign ? 'design-graphique' : ($isCommunity ? 'community-management' : 'community-management'));

        $minTPRequired = match (true) {
            $currentModule === 'design-graphique-cm' => 18,
            $currentModule === 'design-graphique' => 35,
            in_array($currentModule, ['community-management', 'community-manager'], true) => 15,
            default => 15,
        };

        $minProjectsRequired = 4;

        $tpEligible = $tpValidated >= $minTPRequired;
        $projectsEligible = ($projectsCompleted + $designProjectsTotal) >= $minProjectsRequired;
        $isEligible = $tpEligible && $projectsEligible && $reportUploaded;

        $isCertified = false;
        if (Schema::hasTable('certificates') && $studentPk > 0) {
            $isCertified = DB::table('certificates')->where('student_id', $studentPk)->exists();
        }

        $photoUrl = ProfilePhotoHelper::getUrlOrDefault($student->profile_photo ?? null);

        return view('auth.verify-id', [
            'searchedId' => $searchedId,
            'notFound' => false,
            'student' => $student,
            'stats' => [
                'registration_date' => $registrationDate,
                'student_status' => $student->status ?? null,
                'is_active' => $isActive,
                'is_expired' => $isExpired,
                'expiration_date' => $expirationDate instanceof Carbon ? $expirationDate->toDateTimeString() : null,
                'days_remaining' => $daysRemaining,
                'design_projects_total' => $designProjectsTotal,
                'design_projects_validated' => $designProjectsValidated,
                'tp_total' => $tpTotal,
                'tp_assigned' => $tpAssigned,
                'tp_validated' => $tpValidated,
                'projects_assigned' => $projectsAssigned,
                'projects_completed' => $projectsCompleted,
                'report_uploaded' => $reportUploaded,
                'min_tp_required' => $minTPRequired,
                'min_projects_required' => $minProjectsRequired,
                'eligible' => $isEligible,
                'certified' => $isCertified,
                'photo_url' => $photoUrl,
            ],
        ]);
    }

    public function certificatePreview(Request $request)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'string', 'max:64'],
        ]);

        $searchedId = trim((string) $validated['student_id']);

        $student = DB::table('students')->where('student_id', $searchedId)->first();
        if (!$student) {
            abort(404);
        }

        $userId = (int) ($student->user_id ?? 0);
        $studentPk = (int) ($student->id ?? 0);

        // Recalcule une éligibilité minimale (mêmes règles que la page)
        $tpValidated = 0;
        if (Schema::hasTable('tp') && $userId > 0) {
            $tpValidated = (int) DB::table('tp')
                ->where('user_id', $userId)
                ->where('status', 'validated')
                ->count();
        }

        $designProjectsTotal = Schema::hasTable('design_projects')
            ? (int) DB::table('design_projects')
                ->where('user_id', $userId)
                ->count()
            : 0;

        $projectsCompleted = Schema::hasTable('projects')
            ? (int) DB::table('projects')
                ->where('user_id', $userId)
                ->where('status', 'valide')
                ->count()
            : 0;

        $reportUploaded = false;
        if (Schema::hasTable('end_of_training_reports') && $studentPk > 0) {
            $reportUploaded = DB::table('end_of_training_reports')
                ->where('student_id', $studentPk)
                ->exists();
        }

        if (!$reportUploaded && Schema::hasTable('tp') && Schema::hasTable('tp_files') && $userId > 0) {
            $reportUploaded = DB::table('tp')
                ->where('tp.user_id', $userId)
                ->leftJoin('tp_files', 'tp.id', '=', 'tp_files.tp_id')
                ->whereNotNull('tp_files.original_name')
                ->whereRaw("LOWER(RIGHT(tp_files.original_name, 4)) = '.pdf'")
                ->exists();
        }

        $program = (string) ($student->program ?? '');
        $programLower = strtolower($program);
        $isDesign = str_contains($programLower, 'design');
        $isCommunity = str_contains($programLower, 'community') || str_contains($programLower, 'manager') || str_contains($programLower, 'management');

        $currentModule = $isDesign && $isCommunity
            ? 'design-graphique-cm'
            : ($isDesign ? 'design-graphique' : ($isCommunity ? 'community-management' : 'community-management'));

        $minTPRequired = match (true) {
            $currentModule === 'design-graphique-cm' => 18,
            $currentModule === 'design-graphique' => 35,
            in_array($currentModule, ['community-management', 'community-manager'], true) => 15,
            default => 15,
        };

        $minProjectsRequired = 4;

        $isEligible = ($tpValidated >= $minTPRequired)
            && (($projectsCompleted + $designProjectsTotal) >= $minProjectsRequired)
            && $reportUploaded;

        if (!$isEligible) {
            abort(403);
        }

        $certificateGenerator = new CertificateGenerator();

        $data = [
            'first_name' => (string) ($student->first_name ?? ''),
            'last_name' => (string) ($student->last_name ?? ''),
            'student_id' => (string) ($student->student_id ?? ''),
            'date' => now()->format('d/m/Y'),
        ];

        if ($program === 'Community Management' || $program === 'Social Media Marketing') {
            $certificatePath = $certificateGenerator->generateCommunityManagement($data);
        } else {
            $certificatePath = $certificateGenerator->generateCommunityManagement($data);
        }

        return response()->file($certificatePath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Certificat_EVC.pdf"',
        ])->deleteFileAfterSend(true);
    }
}
