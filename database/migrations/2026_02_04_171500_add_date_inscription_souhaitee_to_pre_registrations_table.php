<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('pre_registrations', 'date_inscription_souhaitee')) {
                $table->date('date_inscription_souhaitee')->nullable()->after('motivation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            if (Schema::hasColumn('pre_registrations', 'date_inscription_souhaitee')) {
                $table->dropColumn('date_inscription_souhaitee');
            }
        });
    }
};
