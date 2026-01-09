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

        // 15% = 1500 basis points
        DB::table('admin_job_profiles')
            ->where('code', 'commercial')
            ->update([
                'commission_rate_bp' => 1500,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('admin_job_profiles')) {
            return;
        }

        DB::table('admin_job_profiles')
            ->where('code', 'commercial')
            ->update([
                'commission_rate_bp' => 500,
                'updated_at' => now(),
            ]);
    }
};
