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
        if (Schema::hasTable('attendances') && !Schema::hasColumn('attendances', 'recorded_at')) {
            Schema::table('attendances', function (Blueprint $table) {
                $table->dateTime('recorded_at')->nullable()->after('recorded_by');
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
