<?php

namespace App\Services;

use App\Models\CertificationEligibility;
use App\Models\CertificationEligibilityHistory;
use App\Models\CVThequeProfile;
use App\Models\DesignProject;
use App\Models\Payment;
use App\Models\PreRegistration;
use App\Models\Project;
use App\Models\Student;
use App\Models\TpAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificationEligibilityService
{
    public const DESIGN_GRAPHIQUE = 'design_graphique';

    private array $criteria = [
        self::DESIGN_GRAPHIQUE => [
            'required_projects' => 50,
        ],
    ];

    private array $formationLabels = [
        self::DESIGN_GRAPHIQUE => 'Design Graphique',
    ];

    public function getFormationForStudent(Student $student): ?string
    {
        $program = strtolower((string) $student->program);

        if (str_contains($program, 'design') && (str_contains($program, 'graphique') || str_contains($program, 'graphic'))) {
            return self::DESIGN_GRAPHIQUE;
        }

        return null;
    }

    public function getFormationLabel(string $formation): string
    {
        return $this->formationLabels[$formation] ?? ucfirst(str_replace('_', ' ', $formation));
    }

    public function getCriteria(string $formation): ?array
    {
        return $this->criteria[$formation] ?? null;
    }

    public function evaluate(Student $student, ?string $formation = null): array
    {
        $formation = $formation ?? $this->getFormationForStudent($student);

        if ($formation === null || !isset($this->criteria[$formation])) {
            return [
                'student_id' => $student->id,
                'formation' => null,
                'formation_supported' => false,
                'pre_eligible' => false,
                'eligible' => false,
            ];
        }

        $criteria = $this->criteria[$formation];
        $record = $this->getRecord($student, $formation);

        $projectsCount = $this->countValidatedProjects($student);
        $projectsOk = $projectsCount >= $criteria['required_projects'];

        $payment = $this->getPaymentStatus($student);

        $reportOk = $this->hasReport($student);

        $portfolioOk = $this->hasPortfolio($student);

        $preEligible = $projectsOk && $payment['ok'] && $reportOk && $portfolioOk;

        $studioStatus = $record?->studio_creative_status ?? 'pending';
        $studioValidated = $studioStatus === 'validated';

        $adminStatus = $record?->admin_status ?? 'pending';

        $eligible = $preEligible && $studioValidated && $adminStatus === 'eligible';

        $missing = [];
        if (!$projectsOk) {
            $missing[] = sprintf(
                '%d projet(s) / TP validé(s) sur %d requis',
                $projectsCount,
                $criteria['required_projects']
            );
        }
        if (!$payment['ok']) {
            $missing[] = 'Paiement non soldé (reste ' . number_format($payment['remaining'], 0, ',', ' ') . ' FCFA)';
        }
        if (!$reportOk) {
            $missing[] = 'Rapport de fin de formation manquant';
        }
        if (!$portfolioOk) {
            $missing[] = 'Portfolio manquant';
        }

        return [
            'student_id' => $student->id,
            'formation' => $formation,
            'formation_label' => $this->getFormationLabel($formation),
            'formation_supported' => true,
            'projects_count' => $projectsCount,
            'projects_required' => $criteria['required_projects'],
            'projects_ok' => $projectsOk,
            'payment' => $payment,
            'report_ok' => $reportOk,
            'portfolio_ok' => $portfolioOk,
            'studio_creative_status' => $studioStatus,
            'studio_creative_validated' => $studioValidated,
            'admin_status' => $adminStatus,
            'pre_eligible' => $preEligible,
            'eligible' => $eligible,
            'missing' => $missing,
            'record' => $record,
        ];
    }

    public function countValidatedProjects(Student $student): int
    {
        $designProjects = DesignProject::query()
            ->where('user_id', $student->user_id)
            ->where('status', 'validated')
            ->count();

        $projects = Project::query()
            ->where('user_id', $student->user_id)
            ->where('status', 'valide')
            ->count();

        $tpCount = TpAssignment::query()
            ->where('student_id', $student->id)
            ->where('status', 'validated')
            ->count();

        return $designProjects + $projects + $tpCount;
    }

    public function hasReport(Student $student): bool
    {
        return DB::table('end_of_training_reports')
            ->where('student_id', $student->id)
            ->exists();
    }

    public function hasPortfolio(Student $student): bool
    {
        $profile = CVThequeProfile::query()
            ->where('user_id', $student->user_id)
            ->first();

        if (!$profile) {
            return false;
        }

        if (!empty($profile->portfolio_url)) {
            return true;
        }

        $portfolioFiles = $profile->portfolio_files ?? [];
        if (is_array($portfolioFiles) && count($portfolioFiles) > 0) {
            return true;
        }

        if (is_string($portfolioFiles) && filled($portfolioFiles) && $portfolioFiles !== '[]' && $portfolioFiles !== 'null') {
            return true;
        }

        return false;
    }

    public function getPaymentStatus(Student $student): array
    {
        $preReg = PreRegistration::query()
            ->where('email', $student->email)
            ->latest('id')
            ->first();

        if (!$preReg) {
            return [
                'ok' => false,
                'amount_paid' => 0,
                'total_amount' => 0,
                'remaining' => 0,
                'formation_label' => null,
            ];
        }

        $formationLabel = $this->mapProgramToFormationPrice($student->program);
        $pricingDate = $preReg->created_at;
        $grossTotalAmount = (int) CinetPayService::getFormationPrice($formationLabel, $pricingDate);

        $storedDiscount = (int) ($preReg->discount_amount ?? 0);
        $storedDiscount = min($storedDiscount, $grossTotalAmount);

        $payments = Payment::query()
            ->where('pre_registration_id', $preReg->id)
            ->get();

        $amountPaid = (int) round($payments->where('status', 'completed')->sum('amount'));
        $paymentsTotal = (int) round($payments->max('amount') ?? 0);

        $inferredDiscount = ($storedDiscount <= 0 && $paymentsTotal > 0 && $paymentsTotal < $grossTotalAmount)
            ? ($grossTotalAmount - $paymentsTotal)
            : 0;

        $discountAmount = max($storedDiscount, $inferredDiscount);
        $expectedTotal = max(0, $grossTotalAmount - $discountAmount);
        $totalAmount = $discountAmount > 0 ? $expectedTotal : max($paymentsTotal, $expectedTotal);
        $remaining = max(0, $totalAmount - $amountPaid);

        return [
            'ok' => $remaining <= 0,
            'amount_paid' => $amountPaid,
            'total_amount' => $totalAmount,
            'remaining' => $remaining,
            'formation_label' => $formationLabel,
        ];
    }

    private function mapProgramToFormationPrice(?string $program): string
    {
        $key = strtolower($program ?? '');

        $mapping = [
            'design graphique & community management' => 'Design Graphique & Community Management',
            'design graphique & community manager' => 'Design Graphique & Community Management',
            'design graphique' => 'Design Graphique',
            'design graphic' => 'Design Graphique',
            'community management' => 'Community Management',
            'gestion informatique' => 'Gestion Informatique',
            'intelligence artificielle' => 'Intelligence Artificielle',
        ];

        foreach ($mapping as $needle => $label) {
            if (str_contains($key, $needle)) {
                return $label;
            }
        }

        return 'Design Graphique';
    }

    public function getRecord(Student $student, string $formation): ?CertificationEligibility
    {
        return CertificationEligibility::query()
            ->where('student_id', $student->id)
            ->where('formation', $formation)
            ->first();
    }

    public function getOrCreateRecord(Student $student, string $formation): CertificationEligibility
    {
        return CertificationEligibility::firstOrCreate(
            ['student_id' => $student->id, 'formation' => $formation],
            [
                'system_status' => 'not_eligible',
                'admin_status' => 'pending',
                'studio_creative_status' => 'pending',
            ]
        );
    }

    public function syncRecord(Student $student, ?string $formation = null): CertificationEligibility
    {
        $formation = $formation ?? $this->getFormationForStudent($student);

        if ($formation === null) {
            throw new \InvalidArgumentException('Aucune formation certifiable détectée pour cet étudiant.');
        }

        $eval = $this->evaluate($student, $formation);
        $record = $this->getOrCreateRecord($student, $formation);

        $newSystemStatus = $eval['pre_eligible'] ? 'pre_eligible' : 'not_eligible';
        $oldSystemStatus = $record->system_status;

        $record->update([
            'system_status' => $newSystemStatus,
            'last_evaluated_at' => now(),
        ]);

        if ($oldSystemStatus !== $newSystemStatus) {
            $this->recordHistory(
                $record,
                $oldSystemStatus,
                $newSystemStatus,
                $record->admin_status,
                $record->admin_status,
                $record->studio_creative_status,
                $record->studio_creative_status,
                null,
                'Recalcul automatique des critères'
            );
        }

        return $record->fresh();
    }

    public function setStudioCreative(
        Student $student,
        string $status,
        ?string $studioName = null,
        ?string $comment = null,
        ?int $adminId = null
    ): CertificationEligibility {
        $formation = $this->getFormationForStudent($student);
        if ($formation === null) {
            throw new \InvalidArgumentException('Aucune formation certifiable détectée pour cet étudiant.');
        }

        $record = $this->getOrCreateRecord($student, $formation);
        $oldStudio = $record->studio_creative_status;

        $record->update([
            'studio_creative_status' => $status,
            'studio_creative_name' => $studioName,
            'studio_creative_comment' => $comment,
            'studio_creative_validated_by' => $adminId,
            'studio_creative_validated_at' => now(),
        ]);

        $this->recordHistory(
            $record,
            $record->system_status,
            $record->system_status,
            $record->admin_status,
            $record->admin_status,
            $oldStudio,
            $status,
            $adminId,
            $comment ?: 'Modification du statut Studio Creative'
        );

        return $record->fresh();
    }

    public function setAdminStatus(
        Student $student,
        string $adminStatus,
        ?string $comment = null,
        ?int $adminId = null
    ): CertificationEligibility {
        $formation = $this->getFormationForStudent($student);
        if ($formation === null) {
            throw new \InvalidArgumentException('Aucune formation certifiable détectée pour cet étudiant.');
        }

        $record = $this->getOrCreateRecord($student, $formation);
        $oldAdmin = $record->admin_status;

        $data = [
            'admin_status' => $adminStatus,
            'admin_comment' => $comment,
        ];

        if ($adminStatus === 'eligible') {
            $data['validated_by'] = $adminId;
            $data['validated_at'] = now();
        } else {
            $data['validated_by'] = null;
            $data['validated_at'] = null;
        }

        $record->update($data);

        $this->recordHistory(
            $record,
            $record->system_status,
            $record->system_status,
            $oldAdmin,
            $adminStatus,
            $record->studio_creative_status,
            $record->studio_creative_status,
            $adminId,
            $comment ?: 'Modification du statut administratif'
        );

        return $record->fresh();
    }

    private function recordHistory(
        CertificationEligibility $record,
        ?string $fromSystem,
        ?string $toSystem,
        ?string $fromAdmin,
        ?string $toAdmin,
        ?string $fromStudio,
        ?string $toStudio,
        ?int $adminId,
        ?string $comment
    ): void {
        try {
            CertificationEligibilityHistory::create([
                'certification_eligibility_id' => $record->id,
                'from_system_status' => $fromSystem,
                'to_system_status' => $toSystem,
                'from_admin_status' => $fromAdmin,
                'to_admin_status' => $toAdmin,
                'from_studio_status' => $fromStudio,
                'to_studio_status' => $toStudio,
                'admin_id' => $adminId,
                'comment' => $comment,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur enregistrement historique éligibilité', [
                'record_id' => $record->id,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
