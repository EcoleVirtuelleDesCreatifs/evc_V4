<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('seances')) {
            // Ajouter module et formateur
            DB::statement('ALTER TABLE `seances` ADD COLUMN `module` VARCHAR(255) NULL AFTER `title`, ADD COLUMN `formateur` VARCHAR(255) NULL AFTER `module`');

            // Migrer les anciennes valeurs
            DB::statement("UPDATE `seances` SET `type` = 'onsite' WHERE `type` = 'presentiel'");
            DB::statement("UPDATE `seances` SET `status` = 'scheduled' WHERE `status` = 'planned'");

            // Modifier les enums
            DB::statement("ALTER TABLE `seances` MODIFY COLUMN `type` ENUM('onsite', 'online', 'hybrid') NOT NULL DEFAULT 'onsite'");
            DB::statement("ALTER TABLE `seances` MODIFY COLUMN `status` ENUM('scheduled', 'ongoing', 'completed', 'cancelled') NOT NULL DEFAULT 'scheduled'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('seances')) {
            DB::statement("ALTER TABLE `seances` MODIFY COLUMN `type` ENUM('presentiel', 'online') NOT NULL DEFAULT 'presentiel'");
            DB::statement("ALTER TABLE `seances` MODIFY COLUMN `status` ENUM('planned', 'completed', 'cancelled') NOT NULL DEFAULT 'planned'");
            DB::statement("ALTER TABLE `seances` DROP COLUMN `formateur`, DROP COLUMN `module`");
        }
    }
};
