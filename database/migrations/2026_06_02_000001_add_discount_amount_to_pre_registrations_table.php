<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('pre_registrations') || Schema::hasColumn('pre_registrations', 'discount_amount')) return;
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->unsignedInteger('discount_amount')->default(0)->after('commercial_admin_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('pre_registrations') || !Schema::hasColumn('pre_registrations', 'discount_amount')) return;
        Schema::table('pre_registrations', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
};
