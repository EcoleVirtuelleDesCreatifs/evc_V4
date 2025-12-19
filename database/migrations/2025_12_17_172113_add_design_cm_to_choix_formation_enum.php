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
        // Modifier l'ENUM pour ajouter 'design_graphique_community_management'
        DB::statement("ALTER TABLE pre_registrations MODIFY COLUMN choix_formation ENUM('design_graphique', 'community_management', 'gestion_informatique', 'intelligence_artificielle', 'design_graphique_community_management') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retour à l'ENUM original sans 'design_graphique_community_management'
        DB::statement("ALTER TABLE pre_registrations MODIFY COLUMN choix_formation ENUM('design_graphique', 'community_management', 'gestion_informatique', 'intelligence_artificielle') NOT NULL");
    }
};
