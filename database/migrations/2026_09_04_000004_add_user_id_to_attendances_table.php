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
        // La colonne peut déjà exister si la table a été créée avec (migration 000002 à jour)
        if (Schema::hasTable('attendances') && !Schema::hasColumn('attendances', 'user_id')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('student_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op : la colonne est gérée par la migration de création
    }
};
