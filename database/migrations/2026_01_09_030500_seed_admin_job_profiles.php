<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('admin_job_profiles')) {
            return;
        }

        $profiles = [
            ['code' => 'assistant', 'label' => 'Assistant', 'base_monthly_amount' => 25000, 'commission_rate_bp' => 0, 'is_active' => 1],
            ['code' => 'photographer', 'label' => 'Photographe', 'base_monthly_amount' => 30000, 'commission_rate_bp' => 0, 'is_active' => 1],
            ['code' => 'designer_graphic', 'label' => 'Designer Graphic', 'base_monthly_amount' => 40000, 'commission_rate_bp' => 0, 'is_active' => 1],
            ['code' => 'video_editor', 'label' => 'Monteur Video', 'base_monthly_amount' => 40000, 'commission_rate_bp' => 0, 'is_active' => 1],
            ['code' => 'community_manager', 'label' => 'Community Manager', 'base_monthly_amount' => 30000, 'commission_rate_bp' => 0, 'is_active' => 1],
            ['code' => 'commercial', 'label' => 'Commercial', 'base_monthly_amount' => 25000, 'commission_rate_bp' => 500, 'is_active' => 1],
            ['code' => 'support_tech', 'label' => 'Support Tech', 'base_monthly_amount' => 25000, 'commission_rate_bp' => 0, 'is_active' => 1],
        ];

        foreach ($profiles as $p) {
            DB::table('admin_job_profiles')->updateOrInsert(
                ['code' => $p['code']],
                [
                    'label' => $p['label'],
                    'base_monthly_amount' => $p['base_monthly_amount'],
                    'commission_rate_bp' => $p['commission_rate_bp'],
                    'is_active' => $p['is_active'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }

    public function down(): void
    {
    }
};
