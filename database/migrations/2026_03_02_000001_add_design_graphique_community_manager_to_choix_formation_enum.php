<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pre_registrations')) {
            return;
        }

        try {
            DB::statement("ALTER TABLE pre_registrations MODIFY COLUMN choix_formation ENUM('design_graphique', 'community_management', 'gestion_informatique', 'intelligence_artificielle', 'design_graphique_community_management', 'design_graphique_community_manager', 'design_cm') NOT NULL");
        } catch (\Throwable $e) {
            // ignore
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('pre_registrations')) {
            return;
        }

        try {
            DB::statement("ALTER TABLE pre_registrations MODIFY COLUMN choix_formation ENUM('design_graphique', 'community_management', 'gestion_informatique', 'intelligence_artificielle', 'design_graphique_community_management') NOT NULL");
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
