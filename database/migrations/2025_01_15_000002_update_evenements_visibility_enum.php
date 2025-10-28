<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modifier la colonne visibility pour ajouter 'public'
        DB::statement("ALTER TABLE evenements MODIFY COLUMN visibility ENUM('public', 'all', 'specific') DEFAULT 'public'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'ancien ENUM
        DB::statement("ALTER TABLE evenements MODIFY COLUMN visibility ENUM('all', 'specific') DEFAULT 'all'");
    }
};
