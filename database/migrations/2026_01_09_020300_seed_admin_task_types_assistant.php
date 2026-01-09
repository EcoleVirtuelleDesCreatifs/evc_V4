<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin_task_types')) {
            return;
        }

        $now = now();

        $rows = [
            [
                'code' => 'assistant_manage_projects',
                'label' => 'Gérer les projets (valider/rejeter/supprimer) sous 24h',
                'role' => 'assistant',
                'recurrence' => 'monthly',
                'expected_per_month' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'assistant_payment_reminders_second_month',
                'label' => 'Relancer ceux qui doivent solder (à partir du 2e mois)',
                'role' => 'assistant',
                'recurrence' => 'monthly',
                'expected_per_month' => 4,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'assistant_publish_actualite',
                'label' => 'Publier une actualité (au moins 1/mois)',
                'role' => 'assistant',
                'recurrence' => 'monthly',
                'expected_per_month' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'assistant_publish_communique',
                'label' => 'Publier un communiqué (au moins 1 toutes les 2 semaines)',
                'role' => 'assistant',
                'recurrence' => 'monthly',
                'expected_per_month' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'assistant_remind_end_of_training_report',
                'label' => 'Relancer les étudiants pour le rapport de fin de formation (dernier mois)',
                'role' => 'assistant',
                'recurrence' => 'monthly',
                'expected_per_month' => 1,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'code' => 'assistant_remind_profile_completion',
                'label' => 'Relancer pour renseigner tous les profils (toutes les 2 semaines)',
                'role' => 'assistant',
                'recurrence' => 'monthly',
                'expected_per_month' => 2,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($rows as $row) {
            DB::table('admin_task_types')->updateOrInsert(
                ['code' => $row['code']],
                $row
            );
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('admin_task_types')) {
            return;
        }

        DB::table('admin_task_types')->where('role', 'assistant')->delete();
    }
};
