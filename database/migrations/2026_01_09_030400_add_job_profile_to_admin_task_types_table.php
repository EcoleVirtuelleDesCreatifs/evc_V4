<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_task_types', function (Blueprint $table) {
            if (!Schema::hasColumn('admin_task_types', 'job_profile_id')) {
                $table->unsignedBigInteger('job_profile_id')->nullable()->index()->after('role');
            }

            if (!Schema::hasColumn('admin_task_types', 'kpi_catalog_key')) {
                $table->string('kpi_catalog_key')->nullable()->index()->after('job_profile_id');
            }

            if (!Schema::hasColumn('admin_task_types', 'weight')) {
                $table->unsignedTinyInteger('weight')->default(10)->after('expected_per_month');
            }

            if (!Schema::hasColumn('admin_task_types', 'deadline_hours')) {
                $table->unsignedSmallInteger('deadline_hours')->nullable()->after('weight');
            }

            if (!Schema::hasColumn('admin_task_types', 'is_critical')) {
                $table->boolean('is_critical')->default(false)->after('deadline_hours');
            }
        });

        Schema::table('admin_task_types', function (Blueprint $table) {
            if (Schema::hasColumn('admin_task_types', 'job_profile_id')) {
                $table->foreign('job_profile_id')->references('id')->on('admin_job_profiles')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('admin_task_types', function (Blueprint $table) {
            if (Schema::hasColumn('admin_task_types', 'job_profile_id')) {
                $table->dropForeign(['job_profile_id']);
                $table->dropColumn('job_profile_id');
            }

            if (Schema::hasColumn('admin_task_types', 'kpi_catalog_key')) {
                $table->dropColumn('kpi_catalog_key');
            }

            if (Schema::hasColumn('admin_task_types', 'weight')) {
                $table->dropColumn('weight');
            }

            if (Schema::hasColumn('admin_task_types', 'deadline_hours')) {
                $table->dropColumn('deadline_hours');
            }

            if (Schema::hasColumn('admin_task_types', 'is_critical')) {
                $table->dropColumn('is_critical');
            }
        });
    }
};
