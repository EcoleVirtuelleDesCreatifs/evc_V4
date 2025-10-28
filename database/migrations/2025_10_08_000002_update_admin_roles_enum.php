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
        // Étape 1: Convertir temporairement la colonne en VARCHAR pour permettre la modification
        DB::statement("ALTER TABLE `admins` MODIFY COLUMN `role` VARCHAR(50) NOT NULL DEFAULT 'assistant'");
        
        // Étape 2: Convertir les anciennes valeurs vers les nouvelles
        // 'admin' -> 'comptable'
        // 'moderator' -> 'assistant'
        // 'super_admin' reste 'super_admin'
        DB::table('admins')
            ->where('role', 'admin')
            ->update(['role' => 'comptable']);
            
        DB::table('admins')
            ->where('role', 'moderator')
            ->update(['role' => 'assistant']);
        
        // Étape 3: Reconvertir en ENUM avec les nouvelles valeurs
        DB::statement("ALTER TABLE `admins` MODIFY COLUMN `role` ENUM('super_admin', 'assistant', 'comptable') DEFAULT 'assistant'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer les anciens rôles
        DB::statement("ALTER TABLE `admins` MODIFY COLUMN `role` ENUM('super_admin', 'admin', 'moderator') DEFAULT 'admin'");
    }
};
