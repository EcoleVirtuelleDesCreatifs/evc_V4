<?php

namespace App\Console\Commands;

use App\Models\PreRegistration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MigratePreRegistrationsToStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:preregistrations-to-students 
                            {--force : Force migration even if students table has data}
                            {--email= : Migrate only specific email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from pre_registrations table to students table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting migration from pre_registrations to students...');
        $this->newLine();

        // Check if students table has data
        $existingStudents = Student::count();
        if ($existingStudents > 0 && !$this->option('force')) {
            $this->warn("⚠️  Students table already has {$existingStudents} records.");
            if (!$this->confirm('Do you want to continue? (Existing records will be skipped based on email)', false)) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        // Get pre-registrations to migrate
        $query = PreRegistration::query();
        
        if ($this->option('email')) {
            $query->where('email', $this->option('email'));
        }

        $preRegistrations = $query->get();
        
        if ($preRegistrations->isEmpty()) {
            $this->warn('No pre-registrations found to migrate.');
            return 0;
        }

        $this->info("Found {$preRegistrations->count()} pre-registrations to process.");
        $this->newLine();

        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        $progressBar = $this->output->createProgressBar($preRegistrations->count());
        $progressBar->start();

        foreach ($preRegistrations as $preReg) {
            try {
                // Check if student already exists with this email
                if (Student::where('email', $preReg->email)->exists()) {
                    $skipped++;
                    $progressBar->advance();
                    continue;
                }

                // Generate unique student_id
                $studentId = $this->generateStudentId($preReg);

                // Map gender
                $gender = $this->mapGender($preReg->sexe);

                // Create student record
                $student = Student::create([
                    'first_name' => $preReg->prenom ?? 'Prénom',
                    'last_name' => $preReg->nom ?? 'Nom',
                    'email' => $preReg->email,
                    'phone' => null, // Not available in pre_registrations
                    'whatsapp' => $preReg->whatsapp,
                    'date_of_birth' => $preReg->date_naissance,
                    'gender' => $gender,
                    'student_id' => $studentId,
                    'program' => $preReg->programme ?? $this->mapFormationToProgram($preReg->choix_formation),
                    'level' => $preReg->niveau_dans_formation ?? $preReg->niveau_etude,
                    'specialization' => $preReg->choix_formation ?? $preReg->domaine_etude,
                    'quartier' => null, // Not available in pre_registrations
                    'city' => $preReg->ville,
                    'country' => $preReg->pays ?? 'Côte d\'Ivoire',
                    'profile_photo' => $preReg->photo,
                    'status' => $this->mapStatus($preReg->status),
                    'gpa' => null,
                    'credits_earned' => 0,
                    'years_experience' => null,
                    'industry_sector' => null,
                ]);

                $migrated++;
                
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Error migrating {$preReg->email}: " . $e->getMessage());
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('✅ Migration completed!');
        $this->newLine();
        $this->table(
            ['Status', 'Count'],
            [
                ['Migrated', $migrated],
                ['Skipped (already exists)', $skipped],
                ['Errors', $errors],
                ['Total processed', $preRegistrations->count()],
            ]
        );

        if ($migrated > 0) {
            $this->newLine();
            $this->info('💡 Next steps:');
            $this->line('   1. Verify the migrated data in the students table');
            $this->line('   2. Update your controllers to use Student model instead of PreRegistration');
            $this->line('   3. Consider creating User accounts for students if needed');
        }

        return 0;
    }

    /**
     * Generate a unique student ID
     */
    private function generateStudentId($preReg): string
    {
        $year = now()->year;
        $formationCode = $this->getFormationCode($preReg->choix_formation);
        
        // Get the last student ID for this year and formation
        $lastStudent = Student::where('student_id', 'LIKE', "EVC{$year}{$formationCode}%")
            ->orderBy('student_id', 'desc')
            ->first();

        if ($lastStudent) {
            // Extract the sequence number and increment
            $lastSequence = (int) substr($lastStudent->student_id, -4);
            $newSequence = $lastSequence + 1;
        } else {
            $newSequence = 1;
        }

        return sprintf('EVC%s%s%04d', $year, $formationCode, $newSequence);
    }

    /**
     * Get formation code for student ID
     */
    private function getFormationCode($formation): string
    {
        $codes = [
            'design_graphique' => 'DG',
            'community_management' => 'CM',
            'gestion_informatique' => 'GI',
            'intelligence_artificielle' => 'IA',
        ];

        return $codes[$formation] ?? 'GN'; // GN = General
    }

    /**
     * Map formation choice to program name
     */
    private function mapFormationToProgram($formation): string
    {
        $programs = [
            'design_graphique' => 'Design Graphique',
            'community_management' => 'Community Management',
            'gestion_informatique' => 'Gestion Informatique',
            'intelligence_artificielle' => 'Intelligence Artificielle',
        ];

        return $programs[$formation] ?? 'Formation Générale';
    }

    /**
     * Map gender from sexe field
     */
    private function mapGender($sexe): ?string
    {
        if (empty($sexe)) {
            return null;
        }

        $sexe = strtoupper($sexe);
        
        return match($sexe) {
            'M', 'H', 'HOMME', 'MALE' => 'Homme',
            'F', 'FEMME', 'FEMALE' => 'Femme',
            default => 'Autre',
        };
    }

    /**
     * Map pre-registration status to student status
     */
    private function mapStatus($status): string
    {
        return match($status) {
            'accepted' => 'active',
            'rejected' => 'inactive',
            'pending' => 'inactive',
            default => 'active',
        };
    }
}
