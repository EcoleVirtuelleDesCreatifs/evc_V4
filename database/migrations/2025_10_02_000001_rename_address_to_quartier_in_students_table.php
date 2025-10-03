<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                // Renommer la colonne seulement si elle existe et que la nouvelle n'existe pas déjà
                if (Schema::hasColumn('students', 'address') && !Schema::hasColumn('students', 'quartier')) {
                    $table->renameColumn('address', 'quartier');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('students')) {
            Schema::table('students', function (Blueprint $table) {
                if (Schema::hasColumn('students', 'quartier') && !Schema::hasColumn('students', 'address')) {
                    $table->renameColumn('quartier', 'address');
                }
            });
        }
    }
};
