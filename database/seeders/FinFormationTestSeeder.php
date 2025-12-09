<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FinFormationTestSeeder extends Seeder
{
    /**
     * Seeder pour tester l'éligibilité de fin de formation
     *
     * Critères d'éligibilité :
     * - Minimum 15 TP validés
     * - Minimum 4 projets complétés
     * - Rapport de fin de formation uploadé
     * - Paiement complet (pas encore implémenté)
     */
    public function run(): void
    {
        // 1. Créer ou récupérer un étudiant de test
        $userEmail = 'test.eligibilite@evc.ci';

        // Supprimer les anciennes données de test si elles existent
        $existingUser = DB::table('users')->where('email', $userEmail)->first();
        if ($existingUser) {
            $student = DB::table('students')->where('user_id', $existingUser->id)->first();
            if ($student) {
                // Supprimer les TP assignments
                DB::table('tp_assignments')->where('student_id', $student->id)->delete();
                // Supprimer les projets
                DB::table('projects')->where('user_id', $existingUser->id)->delete();
                // Supprimer le rapport
                DB::table('end_of_training_reports')->where('user_id', $existingUser->id)->delete();
            }
            // Supprimer l'étudiant et l'utilisateur
            DB::table('students')->where('user_id', $existingUser->id)->delete();
            DB::table('users')->where('id', $existingUser->id)->delete();
        }

        // Créer un nouvel utilisateur de test
        $userId = DB::table('users')->insertGetId([
            'name' => 'Test Éligibilité',
            'email' => $userEmail,
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Générer un student_id unique
        $generatedStudentId = 'EVC' . now()->format('Y') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        // Créer le profil étudiant
        $studentId = DB::table('students')->insertGetId([
            'user_id' => $userId,
            'student_id' => $generatedStudentId,
            'first_name' => 'Test',
            'last_name' => 'Éligibilité',
            'email' => $userEmail,
            'phone' => '+2250700000000',
            'whatsapp' => '+2250700000000',
            'program' => 'Community Management',
            'specialization' => 'community_management',
            'level' => 'Intermédiaire',
            'Level_education' => 'Licence',
            'degree' => 'En cours',
            'status' => 'active',
            'city' => 'Abidjan',
            'country' => 'Côte d\'Ivoire',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('✅ Utilisateur de test créé : ' . $userEmail);
        $this->command->info('   ID User: ' . $userId);
        $this->command->info('   ID Student: ' . $studentId);

        // 2. Créer 20 TP validés (dépasse le minimum de 15)
        $tpTitles = [
            'Création de contenu pour Facebook',
            'Stratégie de contenu Instagram',
            'Analyse des KPI réseaux sociaux',
            'Campagne publicitaire Facebook Ads',
            'Community Management sur Twitter',
            'Création de visuels avec Canva',
            'Planification éditoriale mensuelle',
            'Gestion de crise sur les réseaux',
            'Storytelling de marque',
            'Optimisation SEO du contenu',
            'Analyse de la concurrence',
            'Création de personas clients',
            'Calendrier de publications',
            'Engagement communautaire',
            'Veille stratégique digitale',
            'Création de newsletters',
            'Live streaming et interaction',
            'Analyse des tendances sociales',
            'Création de contenus vidéo',
            'Rapport d\'activité mensuel',
        ];

        $this->command->info("\n📝 Création des TP validés...");
        foreach ($tpTitles as $index => $title) {
            DB::table('tp_assignments')->insert([
                'user_id' => $userId,
                'student_id' => $studentId,
                'title' => $title,
                'description' => "Description du TP : $title. Ce travail pratique a été complété avec succès.",
                'deadline' => Carbon::now()->addDays(30)->format('Y-m-d H:i:s'),
                'formation' => 'Community Management',
                'status' => 'validated',
                'validated_at' => Carbon::now()->subDays(rand(5, 60)),
                'assigned_by' => 1, // Admin ID
                'created_at' => Carbon::now()->subDays(rand(60, 120)),
                'updated_at' => Carbon::now()->subDays(rand(5, 60)),
            ]);
        }
        $this->command->info('✅ ' . count($tpTitles) . ' TP validés créés');

        // 3. Créer 5 projets complétés (dépasse le minimum de 4)
        $projectTitles = [
            'Stratégie Social Media Entreprise X',
            'Campagne de lancement produit Y',
            'Audit complet réseaux sociaux',
            'Plan de communication annuel',
            'Projet de gestion de communauté',
        ];

        $categories = [
            'Social Media Strategy',
            'Content Creation',
            'Community Management',
            'Digital Marketing',
            'Brand Management',
        ];

        $this->command->info("\n🎨 Création des projets validés...");
        foreach ($projectTitles as $index => $title) {
            DB::table('projects')->insert([
                'user_id' => $userId,
                'title' => $title,
                'description' => "Projet complet : $title. Travail professionnel réalisé avec soin et expertise.",
                'category' => $categories[$index],
                'status' => 'valide',
                'link' => 'https://example.com/projet-' . ($index + 1),
                'tags' => json_encode(['Social Media', 'Community', 'Strategy']),
                'software_used' => json_encode(['Canva', 'Hootsuite', 'Meta Business Suite']),
                'created_at' => Carbon::now()->subDays(rand(30, 90)),
                'updated_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }
        $this->command->info('✅ ' . count($projectTitles) . ' projets validés créés');

        // 4. Créer un rapport de fin de formation
        $this->command->info("\n📄 Création du rapport de fin de formation...");

        // Vérifier si la table existe
        if (!DB::getSchemaBuilder()->hasTable('end_of_training_reports')) {
            // Créer la table si elle n'existe pas
            DB::statement("
                CREATE TABLE IF NOT EXISTS end_of_training_reports (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NOT NULL,
                    file_path VARCHAR(255) NOT NULL,
                    file_name VARCHAR(255) NOT NULL,
                    file_size INT NOT NULL,
                    status VARCHAR(50) DEFAULT 'pending',
                    admin_comment TEXT NULL,
                    validated_at TIMESTAMP NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                )
            ");
            $this->command->info('   Table end_of_training_reports créée');
        }

        DB::table('end_of_training_reports')->insert([
            'user_id' => $userId,
            'student_id' => $studentId,
            'formation' => 'Community Management',
            'file_path' => 'reports/rapport_fin_formation_test.pdf',
            'original_filename' => 'Rapport_Fin_Formation_Test_Eligibilite.pdf',
            'file_size' => 2048576,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->command->info('✅ Rapport de fin de formation créé');

        // 5. Afficher le récapitulatif
        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info('📊 RÉCAPITULATIF DES DONNÉES DE TEST');
        $this->command->info(str_repeat('=', 60));
        $this->command->info('');
        $this->command->info('👤 Compte de test :');
        $this->command->info('   Email    : ' . $userEmail);
        $this->command->info('   Password : password');
        $this->command->info('');
        $this->command->info('📈 Critères d\'éligibilité :');
        $this->command->info('   ✅ TP validés       : 20 / 15 minimum (ÉLIGIBLE)');
        $this->command->info('   ✅ Projets complétés : 5 / 4 minimum (ÉLIGIBLE)');
        $this->command->info('   ✅ Rapport uploadé  : Oui (ÉLIGIBLE)');
        $this->command->info('   ⚠️  Paiement        : À implémenter');
        $this->command->info('');
        $this->command->info('🔗 URL de test :');
        $this->command->info('   http://127.0.0.1:8000/evc/compte/community-management/fin-formation/index');
        $this->command->info('');
        $this->command->info('📝 Pour tester :');
        $this->command->info('   1. Connectez-vous avec le compte ci-dessus');
        $this->command->info('   2. Accédez à la page de fin de formation');
        $this->command->info('   3. Vérifiez que l\'éligibilité s\'affiche correctement');
        $this->command->info('');
        $this->command->info(str_repeat('=', 60));
    }
}
