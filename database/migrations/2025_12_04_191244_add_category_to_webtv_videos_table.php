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
        Schema::table('webtv_videos', function (Blueprint $table) {
            $table->enum('category', [
                'design-graphique',
                'community-management',
                'intelligence-artificielle',
                'gestion-informatique'
            ])->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webtv_videos', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
