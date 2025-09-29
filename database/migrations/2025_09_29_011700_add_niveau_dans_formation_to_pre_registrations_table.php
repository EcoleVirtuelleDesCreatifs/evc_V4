<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            // Add after choix_formation for readability
            $table->string('niveau_dans_formation')->nullable()->after('choix_formation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn('niveau_dans_formation');
        });
    }
};
