<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tp', function (Blueprint $table) {
            if (!Schema::hasColumn('tp', 'is_report')) {
                $table->boolean('is_report')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tp', function (Blueprint $table) {
            if (Schema::hasColumn('tp', 'is_report')) {
                $table->dropColumn('is_report');
            }
        });
    }
};
