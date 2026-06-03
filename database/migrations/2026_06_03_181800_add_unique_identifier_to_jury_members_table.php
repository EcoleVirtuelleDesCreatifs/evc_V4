<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jury_members', function (Blueprint $table) {
            $table->string('unique_identifier')->nullable()->unique()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('jury_members', function (Blueprint $table) {
            $table->dropUnique(['unique_identifier']);
            $table->dropColumn('unique_identifier');
        });
    }
};
