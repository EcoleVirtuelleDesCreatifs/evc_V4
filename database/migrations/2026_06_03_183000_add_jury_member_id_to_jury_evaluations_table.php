<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jury_evaluations', function (Blueprint $table) {
            $table->foreignId('jury_member_id')->nullable()->after('id')->constrained('jury_members')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('jury_evaluations', function (Blueprint $table) {
            $table->dropForeign(['jury_member_id']);
            $table->dropColumn('jury_member_id');
        });
    }
};
