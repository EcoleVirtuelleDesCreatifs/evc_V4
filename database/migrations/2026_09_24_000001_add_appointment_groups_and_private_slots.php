<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->boolean('is_private')->default(false);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->uuid('group_id')->nullable()->index();
        });
    }

    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['group_id']);
            $table->dropColumn('group_id');
        });

        Schema::table('appointment_slots', function (Blueprint $table) {
            $table->dropColumn('is_private');
        });
    }
};
