<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pre_registrations')) {
            return;
        }

        if (Schema::hasColumn('pre_registrations', 'eligibility_notified_at')) {
            return;
        }

        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->timestamp('eligibility_notified_at')->nullable()->after('date_inscription_souhaitee');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('pre_registrations')) {
            return;
        }

        if (!Schema::hasColumn('pre_registrations', 'eligibility_notified_at')) {
            return;
        }

        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn('eligibility_notified_at');
        });
    }
};
