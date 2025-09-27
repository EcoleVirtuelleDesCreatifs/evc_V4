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
        Schema::table('cvtheque_profiles', function (Blueprint $table) {
            // Champs pour le CV
            $table->string('cv_file_path')->nullable()->after('profile_completion_score');
            $table->string('cv_file_name')->nullable()->after('cv_file_path');
            
            // Champs pour la lettre de motivation
            $table->string('motivation_letter_path')->nullable()->after('cv_file_name');
            $table->string('motivation_letter_name')->nullable()->after('motivation_letter_path');
            
            // Champ pour les fichiers de portfolio/réalisations (JSON array)
            $table->json('portfolio_files')->nullable()->after('motivation_letter_name');
            
            // Champs pour le pressbook
            $table->string('pressbook_file_path')->nullable()->after('portfolio_files');
            $table->string('pressbook_file_name')->nullable()->after('pressbook_file_path');
            
            // Champs pour le rapport de fin de formation
            $table->string('report_file_path')->nullable()->after('pressbook_file_name');
            $table->string('report_file_name')->nullable()->after('report_file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cvtheque_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'cv_file_path',
                'cv_file_name',
                'motivation_letter_path',
                'motivation_letter_name',
                'portfolio_files',
                'pressbook_file_path',
                'pressbook_file_name',
                'report_file_path',
                'report_file_name'
            ]);
        });
    }
};
