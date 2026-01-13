<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Éviter doctrine/dbal: on utilise SQL direct (MySQL)
        DB::statement("ALTER TABLE programmes MODIFY fichier_pdf VARCHAR(500) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE programmes MODIFY fichier_pdf VARCHAR(500) NOT NULL");
    }
};
