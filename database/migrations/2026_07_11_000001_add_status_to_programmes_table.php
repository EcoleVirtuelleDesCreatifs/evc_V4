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
        if (!Schema::hasTable('programmes')) {
            return;
        }

        if (!Schema::hasColumn('programmes', 'status')) {
            Schema::table('programmes', function (Blueprint $table) {
                $table->enum('status', ['draft', 'published'])->default('draft')->after('formation');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('programmes')) {
            return;
        }

        if (Schema::hasColumn('programmes', 'status')) {
            Schema::table('programmes', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
