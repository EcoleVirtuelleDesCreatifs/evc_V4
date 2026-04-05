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
        if (Schema::hasTable('projects') && !Schema::hasColumn('projects', 'admin_hidden')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->boolean('admin_hidden')->default(false)->index();
            });
        }

        if (Schema::hasTable('tp_assignments') && !Schema::hasColumn('tp_assignments', 'admin_hidden')) {
            Schema::table('tp_assignments', function (Blueprint $table) {
                $table->boolean('admin_hidden')->default(false)->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('projects') && Schema::hasColumn('projects', 'admin_hidden')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropColumn('admin_hidden');
            });
        }

        if (Schema::hasTable('tp_assignments') && Schema::hasColumn('tp_assignments', 'admin_hidden')) {
            Schema::table('tp_assignments', function (Blueprint $table) {
                $table->dropColumn('admin_hidden');
            });
        }
    }
};
