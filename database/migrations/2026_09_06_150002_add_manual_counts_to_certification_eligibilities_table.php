<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certification_eligibilities', function (Blueprint $table) {
            $table->integer('manual_tp_count')->unsigned()->nullable()->after('admin_comment');
            $table->integer('manual_projects_count')->unsigned()->nullable()->after('manual_tp_count');
        });
    }

    public function down(): void
    {
        Schema::table('certification_eligibilities', function (Blueprint $table) {
            $table->dropColumn(['manual_tp_count', 'manual_projects_count']);
        });
    }
};
